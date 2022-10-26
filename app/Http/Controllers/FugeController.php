<?php

namespace App\Http\Controllers;

use App\Models\Fuge;
use App\Models\KyHoc;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FugeController extends Controller
{
    public function index()
    {
        $kyhoc = KyHoc::all();
//        dd(\Illuminate\Support\Facades\Auth::user());
        return view('form.uploadfuge', compact('kyhoc'));
    }

    public function postFugeFile(Request $request)
    {

        $kyhoc = KyHoc::find($request->ky_hoc);
        $user = \Illuminate\Support\Facades\Auth::user();
        $username = explode('@', $user->email)[0];
//        $model = LuotBaoCaoThi::where('mon_hoc_id', $request->mon_hoc_id)
//            ->where('ngay_thi', $ngaythi)
//            ->where('ca_thi', $request->ca_thi)
//            ->where('ten_lop', mb_strtoupper(trim($request->ten_lop)))
//            ->first();

//        $dirName = 'file-thi-10b/' . $dotthi->name . '/' . $bomon->name . '/' . $monhoc->name . '/' . mb_strtoupper(trim($request->ten_lop));
//        $dirName .= str_replace('-', '_', $ngaythi) . ".ca-" . $request->ca_thi;
//        $googleDisk = Storage::disk('google');
//        $filePath = $googleDisk->put($dirName, $request->file('file_fuge'));
        $dirName = 'public/uploads/fuge/hoc-ky-' . str_replace(' ', '-', mb_strtolower($kyhoc->name)) . '/' . $username;
        $googleDisk = Storage::disk('local');
        $filePath = $googleDisk->put($dirName, $request->file('file_fuge'));
//        dd($filePath);

//        if ($model) {
//            $googleDisk->delete($model->file_name);
//        } else {
//            $model = new Fuge();
//        }
        $model = new Fuge();
//        $model->fill($request->all());
        $model->user_id = $user->id;
        $model->hoc_ky_id = $kyhoc->id;
        $model->file_name = $filePath;
        $model->save();
        return redirect(route('form.thanhcongFuge'));
    }

    public function thanhCong()
    {
        return view('form.uploadfuge-thanhcong');
    }

    public function lichSuUpload()
    {
        $user = Auth::user();
        $ketqua = Fuge::where('user_id', $user->id)
            ->orderBy('id')->get();
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
