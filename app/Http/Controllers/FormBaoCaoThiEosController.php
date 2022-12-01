<?php

namespace App\Http\Controllers;

use App\Models\BoMon;
use App\Models\DotThi;
use App\Models\LuotBaoCaoThi;
use App\Models\Monhoc;
use App\Models\SinhVien;
use App\Models\DiemEos;
use App\Models\KyHoc;
use App\Models\QuanLyFileEos;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

//use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

//use RealRashid\SweetAlert\Facades\Alert;

class FormBaoCaoThiEosController extends Controller
{
    public $hocKy;
    public $hocKyHienTai;

    public function __construct()
    {
        $this->hocKy = KyHoc::orderBy('id', 'asc')->get()->toArray();
        $this->hocKyHienTai = end($this->hocKy);
    }

    public function index()
    {
        $dataView = [
            'hocKy' => $this->hocKy,
            'hocKyHienTai' => $this->hocKyHienTai,
        ];
        return view('admin.bao-cao-thi-eos.baocaothieos', $dataView);
    }

    public function postBaoCaoEos(Request $request)
    {
        $hoc_ky_id = $request->hoc_ky_id;
        $hocKy = KyHoc::select('name')
            ->where('id', $hoc_ky_id)
            ->first();
        $mainSheet = 'Result';
        $colsGet = [
            'B' => 'ten_dang_nhap',
            'H' => 'diem',
            'J' => 'ma_mon',
        ];
        $temp = $this->handleExcelFile($request->file('file_excel'), $mainSheet, $colsGet, ['offset' => 1, 'colCheckEmpty' => 'B']);
        if (!$temp) {
            $error = 'Quý thầy cô vui lòng báo cáo bằng file điểm eos đúng định dạng';
            session()->flash('error', $error);
            return redirect()->route('form.baocaothieos');
        }

        // Lấy ra môn học
        $monHocDb = MonHoc::all();
        $monHocDbArr = [];
        foreach ($monHocDb as $mh) {
            $mon_hoc_id = $mh->id;
            $ma_mon = $mh->ma_mon_hoc;
            $monHocDbArr[$ma_mon] = $mon_hoc_id;
        }

        [$thongTinSinhVien, $diemThi] = $this->handleData($temp, $monHocDbArr);

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
            $tenDangNhap = $thongTinSinhVien[$msv];
//            $tenDangNhap = $this->getSinhVienUsername($tenSinhVien, $msv);
            $sinhVien['ma_sinh_vien'] = $msv;
            $sinhVien['ten_dang_nhap'] = $tenDangNhap;
            $sinhVienAdd[] = $sinhVien;
        }
//        dd($sinhVienAdd);
        SinhVien::insert($sinhVienAdd);

        // Sau khi thêm thì lấy ra toàn bộ sinh viên của lớp trong db
        $msvToId = [];
        $idToMsv = [];
        foreach ($maSinhVienDb->get()->toArray() as $item) { // convert dạng $arr[maSinhVien] = sinhVienId_monHocId
//            dd($item);
//            $ma_mon = $diemThi[$key]['ma_mon'];
//            $mon_hoc_id = $monHocDbArr[$ma_mon] ?? false;
            $msv = $item['ma_sinh_vien'];
            $id = $item['id'];
//            if (!$mon_hoc_id): continue; endif;
            $msvToId[$msv] = $id;
            $idToMsv[$id] = $msv;
        }

        $diemEosExcel = array_keys($diemThi);
        $diemEosExcelArr = [];
        foreach ($diemEosExcel as $diem) {
            [$msv, $mon_hoc_id] = explode('_', $diem);
            $sinhVienId = $msvToId[$msv];
            $diemEosExcelArr[] = implode('_', [$sinhVienId, $mon_hoc_id]);
        }
//        dd($diemEosExcelArr);


        // Lấy ra các sinh viên đã nhập điểm của lớp
        $diemEosDb = DiemEos::select('sinh_vien_id', 'mon_hoc_id')
            ->where('ky_hoc_id', $hoc_ky_id)->get()->toArray();

        $diemEosDbArr = array_reduce($diemEosDb, function ($result, $item) {
            // convert dạng sinhVienId_lopDotThiId
            $result[] = implode('_', $item);
            return $result;
        }, []);
//        dd($diemEosDbArr);

        // Lấy ra các sinh viên chưa nhập điểm
        $diemEosConThieu = array_diff($diemEosExcelArr, $diemEosDbArr);

        // Nhập điểm
        $diemEosAdd = [];
        foreach ($diemEosConThieu as $item) {
            [$sinh_vien_id, $mon_hoc_id] = explode('_', $item);
            $msv = $idToMsv[$sinh_vien_id];
            $key = implode('_', [$msv, $mon_hoc_id]);
            $diem = $diemThi[$key] ?? null;
            if ($diem === "" || $diem === null): continue; endif;
            $diemEos = [];
            $diemEos['sinh_vien_id'] = $sinh_vien_id;
            $diemEos['mon_hoc_id'] = $mon_hoc_id;
            $diemEos['ky_hoc_id'] = $hoc_ky_id;
            $diemEos['diem'] = $diem;
            $diemEosAdd[] = $diemEos;
        }
//        dd($diemEosAdd);
        DB::table('diem_eos')->insert($diemEosAdd);
//        dd('done');
        $dirName = 'eos/' . str_replace(' ', '-', mb_strtolower($hocKy->name)) . '/' . date("d_m_Y_H_i_s");
        $nameFile = date("d_m_Y_H_i_s") . '-eos' . '.' . $request->file('file_excel')->getClientOriginalExtension();
        $filePath = $dirName . '/' . $nameFile;
        $googleDisk = Storage::disk('second_google');
        $googleDisk->put($filePath, file_get_contents($request->file('file_excel')));


        $model = new QuanLyFileEos();
        $model->file_path = $filePath;
        $model->hoc_ky_id = $hoc_ky_id;
        $model->save();

        return redirect()->route('form.baocaoeosthanhcong');
    }

    public function handleData($data, $monHocArr)
    {
        $thong_tin_sinh_vien = [];
        $diem_thi = [];
        foreach ($data as $each) {
            extract($each);
            $tenDangNhapLength = strlen($ten_dang_nhap);
            $ma_sinh_vien = substr($ten_dang_nhap, $tenDangNhapLength - 7);
            $ma_sinh_vien = strtoupper($ma_sinh_vien);
            $ten_dang_nhap = strtolower($ten_dang_nhap);
            $thong_tin_sinh_vien[$ma_sinh_vien] = $ten_dang_nhap;
            $mon_hoc_id = $monHocArr[$ma_mon] ?? false;
            if (!$mon_hoc_id): continue; endif;
            $key = $ma_sinh_vien . '_' . $mon_hoc_id;
            $diem_thi[$key] = $diem;
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
        foreach ($sheet->getRowIterator() as $row) {
            if ($row->getRowIndex() <= $offset): continue; endif;
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
            $cells = [];
            foreach ($cellIterator as $index => $cell) {
                $colKey = $cell->getColumn();
                $value = $cell->getValue();
                $checkEmpty = $colKey === $colCheckEmpty && empty($value);
                if ($checkEmpty): break; endif;
                if (!array_key_exists($colKey, $colsGet)): continue; endif;
                $cells[$colsGet[$colKey]] = $value;
            }
            if (!empty($cells)) {
                $result[] = $cells;
            }
        }
//        $result['data'] = $rows;
        return $result;
    }

    function lichSuUpload()
    {
        $quanLyFileEos = QuanLyFileEos::select('id', 'file_path', 'hoc_ky_id', 'created_at')
            ->get()->toArray();
        $hocKyArr = [];
        foreach ($this->hocKy as $hk) {
            $hocKyArr[$hk['id']]['id'] = $hk['id'];
            $hocKyArr[$hk['id']]['name'] = $hk['name'];
        }
        foreach ($hocKyArr as $index => $item) {
            $hocKyArr[$index]['danh_sach'] = [];
        }
        foreach ($quanLyFileEos as $file) {
            $hocKyArr[$file['hoc_ky_id']]['danh_sach'][] = [
                'id' => $file['id'],
                'file_path' => $file['file_path'],
                'created_at' => date('H:i d-m-Y', strtotime($file['created_at'])),
            ];
        }
        $dataView = [
            'hocKy' => $this->hocKy,
            'hocKyHienTai' => $this->hocKyHienTai,
            'data' => $hocKyArr,
        ];
        return view('admin.bao-cao-thi-eos.danh-sach-upload', $dataView);
    }

    public function baoCaoEosThanhCong()
    {
        return view('admin.bao-cao-thi-eos.baocaothieos-thanhcong');
    }

    public function taiFileBaoCaoEos($id)
    {
        $fileFuge = QuanLyFileEos::find($id);
//        $googleDisk = Storage::disk('local');
        $googleDisk = Storage::disk('second_google');
        if (!empty($fileFuge->file_path)) {
            return $googleDisk->download($fileFuge->file_path);
        }
    }

    public function taiFileMau()
    {
        $downloadFileName = 'file_mau_bao_cao_thi_eos.xlsx';
        $filePath = 'file-mau/' . $downloadFileName;
        $googleDisk = Storage::disk('second_google');
        $file = $googleDisk->get($filePath);
        return response()->streamDownload(function () use ($file) {
            echo $file;
        }, $downloadFileName);

    }
}
