<?php

namespace App\Http\Controllers;

use App\Models\BoMon;
use App\Models\DotThi;
use App\Models\LuotBaoCaoThi;
use App\Models\Monhoc;
use App\Models\MonDotThi;
use App\Models\LopDotThi;
use App\Models\CaDotThi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FormBaoCaoThiController extends Controller
{
    public function index()
    {
        $luotbaocao = LuotBaoCaoThi::all()->toArray();
        $luotbaocaoArr = array_map(function ($row) {
            extract($row);
            return implode('|', [$mon_hoc_id, $ten_lop, $ngay_thi, $ca_thi]);
        }, $luotbaocao);

        $bomon = BoMon::all();
        $dotthi = DotThi::where('status', 1)->first();
        $mondotthi = MonDotThi::select('mon_hoc.*')
            ->leftJoin('mon_hoc', 'mon_dot_thi.mon_hoc_id', '=', 'mon_hoc.id')
            ->where('mon_dot_thi.dot_thi_id', $dotthi->id)
            ->get();
        $lopdotthi = LopDotThi::select('lop_dot_thi.name', 'mon_hoc.id as mon_hoc_id')
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
        foreach ($cadotthi as $key => $cdt) {
            $dot_thi_info = implode('|', [$cdt->mon_hoc_id, $cdt->ten_lop, $cdt->ngay_thi, $cdt->ca_thi_id]);
            if (in_array($dot_thi_info, $luotbaocaoArr)) {
                unset($cadotthi[$key]);
            }
        }
        return view('form.baocaothi', compact('bomon', 'mondotthi', 'lopdotthi', 'cadotthi', 'dotthi'));
    }

    public function postBaoCaoThi(Request $request)
    {

        $bomon = BoMon::find($request->bo_mon);
        $monhoc = Monhoc::find($request->mon_hoc_id);
        $dotthi = DotThi::where('status', 1)->first();
        [$ca_thi, $ngaythi] = explode('|', $request->ca_thi);
//        $ngaythi = Carbon::createFromFormat('d/m/Y', $request->ngay_thi)->format('Y-m-d');
        $model = LuotBaoCaoThi::where('mon_hoc_id', $request->mon_hoc_id)
            ->where('ngay_thi', $ngaythi)
            ->where('ca_thi', $ca_thi)
            ->where('ten_lop', mb_strtoupper(trim($request->ten_lop)))
            ->first();

        $dirName = 'file-thi-10b/' . $dotthi->name . '/' . $bomon->name . '/' . $monhoc->name . '/' . mb_strtoupper(trim($request->ten_lop));
        $dirName .= '/' . str_replace('-', '_', $ngaythi) . ".ca-" . $ca_thi;
//        dd($dirName);
        $googleDisk = Storage::disk('google');
        $filePath = $googleDisk->put($dirName, $request->file('file_excel'));

        if ($model) {
            $googleDisk->delete($model->file_10b);
        } else {
            $model = new LuotBaoCaoThi();
        }
        $model->fill($request->all());
        $model->dot_thi_id = $dotthi->id;
        $model->email_gv = Auth::user()->email;
        $model->file_10b = $filePath;
        $model->ngay_thi = $ngaythi;
        $model->ca_thi = $ca_thi;
        $model->save();
        return redirect(route('form.thanhcong'));
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
        $googleDisk = Storage::disk('google');
        $file = $googleDisk->get($luotBaoCao->file_10b);
        return response()->streamDownload(function () use ($file) {
            echo $file;
        }, $downloadFileName);
//        dd($path);

    }
}
