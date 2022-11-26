<?php

namespace App\Http\Controllers;

use App\Models\BoMon;
use App\Models\DotThi;
use App\Models\LuotBaoCaoThi;
use App\Models\Monhoc;
use App\Models\MonDotThi;
use App\Models\LopDotThi;
use App\Models\CaDotThi;
use App\Models\SinhVien;
use App\Models\DiemSinhVien;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
//use RealRashid\SweetAlert\Facades\Alert;

class FormBaoCaoThiController extends Controller
{
    public function index()
    {
        $bomon = BoMon::all();
        $dotthi = DotThi::where('status', 1)->first();
        $mondotthi = MonDotThi::select('mon_hoc.*')
            ->leftJoin('mon_hoc', 'mon_dot_thi.mon_hoc_id', '=', 'mon_hoc.id')
            ->where('mon_dot_thi.dot_thi_id', $dotthi->id)
            ->get();
        $lopdotthi = LopDotThi::select('lop_dot_thi.id', 'lop_dot_thi.name', 'mon_hoc.id as mon_hoc_id')
            ->join('mon_dot_thi', 'lop_dot_thi.mon_dot_thi_id', '=', 'mon_dot_thi.id')
            ->join('mon_hoc', 'mon_dot_thi.mon_hoc_id', '=', 'mon_hoc.id')
            ->where('lop_dot_thi.dot_thi_id', $dotthi->id)
            ->get();
        $cadotthi = CaDotThi::select('ca_dot_thi.ngay_thi', 'ca_thi.id as ca_thi_id', 'ca_thi.name', 'lop_dot_thi.name as ten_lop', 'mon_hoc.id as mon_hoc_id')
            ->join('lop_dot_thi', 'ca_dot_thi.lop_dot_thi_id', '=', 'lop_dot_thi.id')
            ->join('ca_thi', 'ca_dot_thi.ca_thi_id', '=', 'ca_thi.id')
            ->join('mon_dot_thi', 'lop_dot_thi.mon_dot_thi_id', '=', 'mon_dot_thi.id')
            ->join('mon_hoc', 'mon_dot_thi.mon_hoc_id', '=', 'mon_hoc.id')
            ->where('lop_dot_thi.dot_thi_id', $dotthi->id)
            ->get();
        return view('form.baocaothi', compact('bomon', 'mondotthi', 'lopdotthi', 'cadotthi', 'dotthi'));
    }

    public function postBaoCaoThi(Request $request)
    {
        $bomon = BoMon::find($request->bo_mon);
        $monhoc = Monhoc::find($request->mon_hoc_id);
        $dotthi = DotThi::where('status', 1)->first();
        [$ca_thi, $ngaythi] = explode('|', $request->ca_thi);
        [$lop_dot_thi_id, $ten_lop] = explode('|', $request->lop);

        $mainSheet = 'Tong hop';
        $colsGet = [
            'B' => 'ma_sinh_vien',
            'C' => 'ten_sinh_vien',
            'G' => 'diem'
        ];
        $temp = $this->handleExcelFile($request->file('file_excel'), $mainSheet, $colsGet, ['offset' => 7, 'colCheckEmpty' => 'B']);
        if (!$temp) {
            $error = 'Quý thầy cô vui lòng báo cáo bằng file excel đúng định dạng';
            session()->flash('error', $error);
            return redirect()->route('form.baocaothi');
        }

        extract($temp);

        $lopPost = $ten_lop . '|' . $monhoc->ma_mon_hoc;
        $lopExcel = $lop_excel . '|' . $mon_excel;

        $isCorrectClass = $lopPost === $lopExcel;
        if (!$isCorrectClass) {
            $error = 'Quý thầy cô vui lòng nộp báo cáo đúng lớp';
            session()->flash('error', $error);
            return redirect()->route('form.baocaothi');
        }

        unset($temp);
        [$thongTinSinhVien, $diemThi] = $this->handleData($data);

        // Lấy ra toàn bộ mã sinh viên có trong file excel
        $maSinhVien = array_keys($thongTinSinhVien);

        // Lấy ra các mã sinh viên có trong db và file excel
        $maSinhVienDb = SinhVien::select('id', 'ma_sinh_vien')->whereIn('ma_sinh_vien', $maSinhVien);
        $maSinhVienDbArr = array_reduce($maSinhVienDb->get()->toArray(), function ($result, $item) { // Flat
            return array_merge($result, [$item['ma_sinh_vien']]);
        }, []);

        // Lọc ra các sinh viên chưa có trong database
        $maSinhVienFil = array_diff($maSinhVien, $maSinhVienDbArr);

        // Thêm sinh viên chưa có
        $sinhVienAdd = [];
        foreach ($maSinhVienFil as $msv) {
            $sinhVien = [];
            $sinhVien['ma_sinh_vien'] = $msv;
            $sinhVien['ten_sinh_vien'] = $thongTinSinhVien[$msv];
            $sinhVienAdd[] = $sinhVien;
        }
        DB::table('sinh_vien')->insert($sinhVienAdd);

        // Sau khi thêm thì lấy ra toàn bộ sinh viên của lớp trong db
        $sinhVienArr = [];
        foreach ($maSinhVienDb->get()->toArray() as $item) { // convert dạng $arr[maSinhVien] = sinhVienId_lopDotThiId
            $key = $item['ma_sinh_vien'];
            $sinhVienDbArr[$key] = $item['id'] . '_' . $lop_dot_thi_id;
        }

        // Lấy ra các sinh viên đã nhập điểm của lớp
        $diemSinhVienDb = DiemSinhVien::select('sinh_vien_id', 'lop_dot_thi_id')
            ->where('lop_dot_thi_id', $lop_dot_thi_id)->get()->toArray();

        $diemSinhVienDbArr = array_reduce($diemSinhVienDb, function ($result, $item) {
            // convert dạng sinhVienId_lopDotThiId
            $result[] = implode('_', $item);
            return $result;
        }, []);

        // Lấy ra các sinh viên chưa nhập điểm
        $diemSinhVienConThieu = array_diff($sinhVienDbArr, $diemSinhVienDbArr);

        // Nhập điểm
        $diemSinhVienAdd = [];
        foreach ($diemSinhVienConThieu as $ma_sinh_vien => $item) {
            [$sinh_vien_id, $lop_dot_thi_id] = explode('_', $item);
            $diem = $diemThi[$ma_sinh_vien] ?? null;
            if ($diem === "" || $diem === null): continue; endif;
            $diemSinhVien = [];
            $diemSinhVien['sinh_vien_id'] = $sinh_vien_id;
            $diemSinhVien['lop_dot_thi_id'] = $lop_dot_thi_id;
            $diemSinhVien['diem'] = $diem;
            $diemSinhVienAdd[] = $diemSinhVien;
        }
        DB::table('diem_sinh_vien')->insert($diemSinhVienAdd);

        //        $ngaythi = Carbon::createFromFormat('d/m/Y', $request->ngay_thi)->format('Y-m-d');
        $model = LuotBaoCaoThi::where('mon_hoc_id', $request->mon_hoc_id)
            ->where('ngay_thi', $ngaythi)
            ->where('ca_thi', $ca_thi)
            ->where('ten_lop', mb_strtoupper(trim($ten_lop)))
            ->first();

        $dirName = 'file-thi-10b/' . $dotthi->name . '/' . $bomon->name . '/' . $monhoc->name . '/' . mb_strtoupper(trim($ten_lop));
        $dirName .= '/' . str_replace('-', '_', $ngaythi) . ".ca-" . $ca_thi;
        //        dd($dirName);
        $googleDisk = Storage::disk('second_google');
        $nameFile = $request->file('file_excel')->getClientOriginalName();
        $filePath = $dirName . '/' . $nameFile;
//        $filePath = $request->file('file_excel')->storeAs($dirName, $nameFile);
        $googleDisk->put($filePath, file_get_contents($request->file('file_excel')));
        if ($model) {
            $googleDisk->delete($model->file_10b);
        } else {
            $model = new LuotBaoCaoThi();
        }
        // dd($filePath);
        $model->fill($request->all());
        $model->dot_thi_id = $dotthi->id;
        $model->email_gv = Auth::user()->email;
        $model->mon_hoc_id = $monhoc->id;
        $model->ten_lop = $ten_lop;
        $model->file_10b = $filePath;
        $model->ngay_thi = $ngaythi;
        $model->ca_thi = $ca_thi;
        $model->save();
        return redirect(route('form.thanhcong'));
    }

    public function handleData($data)
    {
        $thong_tin_sinh_vien = [];
        $diem_thi = [];
        foreach ($data as $each) {
            extract($each);
            $thong_tin_sinh_vien[$ma_sinh_vien] = $ten_sinh_vien;
            $diem_thi[$ma_sinh_vien] = $diem;
        }
        return [$thong_tin_sinh_vien, $diem_thi];
    }

    public function handleExcelFile($file, $mainSheet, $colsGet, $options): bool|array
    {
        $offset = $options['offset'] ?? 0;
        $colCheckEmpty = $options['colCheckEmpty'] ?? null;
        $result = [];

        $reader = new Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheets = $reader->load($file);

        $sheet = $spreadsheets->getSheetByName($mainSheet);
        if ($sheet === null) {
            return false;
        }
        $monExcel = $sheet->getCell('D3')->getOldCalculatedValue() ?? false;
        $lopExcel = $sheet->getCell('D4')->getOldCalculatedValue() ?? false;
        $giangVienExcel = $spreadsheets->getSheetByName('Danh sach AP')->getCell('K2')->getValue() ?? false;
        if (empty($monExcel) || empty($lopExcel) || empty($giangVienExcel)) {
            return false;
        }
        $result['mon_excel'] = $monExcel;
        $result['lop_excel'] = $lopExcel;
        $result['giang_vien_excel'] = $giangVienExcel;
        foreach ($sheet->getRowIterator() as $row) {
            if ($row->getRowIndex() <= $offset): continue; endif;
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
            $cells = [];
            foreach ($cellIterator as $index => $cell) {
                $colKey = $cell->getColumn();
                $value = $cell->getOldCalculatedValue();
                $checkEmpty = $colKey === $colCheckEmpty && empty($value);
                if ($checkEmpty): break; endif;
                if (!array_key_exists($colKey, $colsGet)): continue; endif;
                $cells[$colsGet[$colKey]] = $value;
            }
            if (!empty($cells)) {
                $rows[] = $cells;
            }
        }
        $result['data'] = $rows;
        return $result;
    }

    public function thanhCong()
    {
        return view('form.baocaothi-thanhcong');
    }

    public function lichSuBaoCao()
    {
        $user = Auth::user();
        $dotthi = DotThi::where('status', 1)->first();
        $ketqua = LuotBaoCaoThi::where('email_gv', $user->email)
            ->where('dot_thi_id', $dotthi->id)
            ->orderByDesc('ngay_thi')
            ->orderBy('ca_thi')->get();
        $ketqua->load('monhoc');
        return view('form.baocaothi-lichsu', compact('ketqua', 'dotthi'));
    }

    public function taiFileBaocao($luotbaocao)
    {
        $luotBaoCao = LuotBaoCaoThi::find($luotbaocao);
        $fileInfo = pathinfo($luotBaoCao->file_10b);
        $ext = $fileInfo['extension'];
        $downloadFileName = $luotBaoCao->ten_lop . '_' . $luotBaoCao->ngay_thi . "_ca-" . $luotBaoCao->ca_thi . '.' . $ext;
        $googleDisk = Storage::disk('second_google');
        $file = $googleDisk->get($luotBaoCao->file_10b);
        return response()->streamDownload(function () use ($file) {
            echo $file;
        }, $downloadFileName);
        //        dd($path);

    }
}
