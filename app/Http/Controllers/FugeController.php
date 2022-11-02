<?php

namespace App\Http\Controllers;

use App\Models\Fuge;
use App\Models\KyHoc;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

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


        $dirName = 'public/uploads/fuge/hoc-ky-' . str_replace(' ', '-', mb_strtolower($kyhoc->name)) . '/' . $username;
        $nameFile = date("d_m_Y_H_i_s").'-' .$request->file('file_fuge')->getClientOriginalName();
        $text = strlen($nameFile);
        $code = substr($nameFile, $text - 3);
        $filePath = $request->file('file_fuge')->storeAs($dirName, $nameFile);
        $model = new Fuge();
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

    public function danhSachUpload(Request $request)
    {
        $test = $request->ky_hoc;

        $id = $request->id;
        $user = User::find($request->id);
        $kyhoc = KyHoc::all();
        $list = Fuge::where('user_id', $user->id);
        if (isset($test)) {
            $list->where('hoc_ky_id', $test);
        }
        $danhsach = $list->orderBy('id', 'desc')->get();
        $arrKyHoc = [];
        foreach ($kyhoc as $kh) {
            $arrKyHoc[$kh->id] = $kh->name;
        }
        return view('admin.fuge.danh-sach-upload', compact('user', 'danhsach', 'arrKyHoc', 'test'));
    }

    public function lichSuUpload()
    {
        $user = Auth::user();
        $ketqua = Fuge::where('user_id', $user->id)
            ->orderBy('id')->get();
        $ketqua->load('monhoc');
        return view('form.baocaothi-lichsu', compact('ketqua', ));

    }

    public function taiFileFuge($id)
    {
        $fileFuge = Fuge::find($id);
        $googleDisk = Storage::disk('local');
        if (!empty($fileFuge->file_name)) {
            return $googleDisk->download($fileFuge->file_name);
        }

    }
}
