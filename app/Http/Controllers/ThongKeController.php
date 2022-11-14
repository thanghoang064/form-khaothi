<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Fuge;
use App\Models\KyHoc;
use App\Models\BoMon;
use Illuminate\Support\Facades\Auth;

class ThongKeController extends Controller
{


    public function nhapDiem()
    {
        $hocKy = KyHoc::select('id', 'name')->where('status', '1')->orderBy('id', 'DESC')->first();
        $hocKyId = $hocKy->id;
        $hocKyName = $hocKy->name;
//        $idGiangVienDaNhapDiem = Fuge::select('user_id')->where('hoc_ky_id', $hocKyId)->get();
//        $soGiangVienTheoBoMon = User::select('count(*)')->groupBy('role_bo_mon')->get();
        $soGiangVienDaNhapDiemTheoBoMon = DB::table('fuge')
            ->select(DB::raw('users.role_bomon, count(users.role_bomon) as so_giang_vien_da_nhap_diem'))
            ->join('users', 'users.id', "=", 'fuge.user_id')
            ->groupBy('users.role_bomon')
            ->get()->toArray();
        $soGiangVienTheoBoMon = DB::table('users')
            ->select(DB::raw('users.role_bomon, count(users.role_bomon) as so_giang_vien'))
            ->groupBy('users.role_bomon')
            ->get()->toArray();

        $soGiangVienDaNhapDiemTheoBoMonArr = [];
        foreach ($soGiangVienDaNhapDiemTheoBoMon as $gv) {
            $boMonId = $gv->role_bomon;
            $soGiangVien = $gv->so_giang_vien_da_nhap_diem;
            $soGiangVienDaNhapDiemTheoBoMonArr[$boMonId] = $soGiangVien;
        }

        $soGiangVienTheoBoMonArr = [];
        foreach ($soGiangVienTheoBoMon as $gv) {
            $boMonId = $gv->role_bomon;
            $soGiangVien = $gv->so_giang_vien;
            $soGiangVienTheoBoMonArr[$boMonId] = $soGiangVien;
        }
        $boMon = BoMon::all()->toArray();
        $thongKeNhapDiemTheoBoMon = [];
        foreach ($boMon as $bm) {
            $id = $bm['id'];
            $tong_so_giang_vien = $soGiangVienTheoBoMonArr[$id] ?? 0;
            $so_giang_vien_da_nhap_diem = $soGiangVienDaNhapDiemTheoBoMonArr[$id] ?? 0;
            $so_giang_vien_chua_nhap_diem = $tong_so_giang_vien - $so_giang_vien_da_nhap_diem;
            $thongKeNhapDiemTheoBoMon[] = [
                'id' => $id,
                'name' => $bm['name'],
                'tong_so_giang_vien' => $tong_so_giang_vien,
                'so_giang_vien_da_nhap_diem' => $so_giang_vien_da_nhap_diem,
                'so_giang_vien_chua_nhap_diem' => $so_giang_vien_chua_nhap_diem,
            ];
        }
        return view('admin.thongke.nhap-diem', compact('thongKeNhapDiemTheoBoMon', 'hocKyName'));
    }

    public function nhapDiemTheoBoMon($idBoMon)
    {
        $hocKyName = KyHoc::select('id', 'name')->where('status', '1')->orderBy('id', 'DESC')->first()->name;
        $boMon = BoMon::select('id', 'name')
            ->where('id', $idBoMon)
            ->first();

        $giangVienDaNhapDiemCuaBoMon = DB::table('fuge')
            ->select('users.id', 'users.name', 'users.email')
            ->join('users', 'users.id', "=", 'fuge.user_id')
            ->where('users.role_bomon', $boMon->id)
            ->get()->toArray();

        $giangVienDaNhapDiemCuaBoMonArr = $this->mergeGv($giangVienDaNhapDiemCuaBoMon);

        $giangVienCuaBoMon = DB::table('users')
            ->select('users.id', 'users.name', 'users.email')
            ->where('users.role_bomon', $boMon->id)
            ->get()->toArray();

        $giangVienCuaBoMonArr = $this->mergeGv($giangVienCuaBoMon);

        $giangVienChuaNhapDiemCuaBoMon = array_diff($giangVienCuaBoMonArr, $giangVienDaNhapDiemCuaBoMonArr);
        $tong_so_giang_vien = count($giangVienCuaBoMonArr);
        $so_giang_vien_chua_nhap_diem = count($giangVienChuaNhapDiemCuaBoMon);
        $so_giang_vien_da_nhap_diem = count($giangVienDaNhapDiemCuaBoMonArr);

        $giangVienCuaBoMonArr = $this->handleGv($giangVienCuaBoMonArr);
        $giangVienDaNhapDiemCuaBoMonArr = $this->handleGv($giangVienDaNhapDiemCuaBoMonArr);
        $giangVienChuaNhapDiemCuaBoMonArr = $this->handleGv($giangVienChuaNhapDiemCuaBoMon);

        $giangVien = [
            'tong_so' => $tong_so_giang_vien,
            'danh_sach' => $giangVienCuaBoMonArr
        ];

        $giangVienChuaNhap = [
            'tong_so' => $so_giang_vien_chua_nhap_diem,
            'danh_sach' => $giangVienChuaNhapDiemCuaBoMonArr
        ];

        $giangVienDaNhap = [
            'tong_so' => $so_giang_vien_da_nhap_diem,
            'danh_sach' => $giangVienDaNhapDiemCuaBoMonArr
        ];
        return view('admin.thongke.nhap-diem-theo-bo-mon', compact('giangVien', 'giangVienChuaNhap', 'giangVienDaNhap', 'boMon', 'hocKyName'));
    }

    public function handleGv($arr)
    {
        return array_reduce($arr, function ($result, $gv) {
            [$id, $name, $email] = explode('|', $gv);
            $result[] = [
                'id' => $id,
                'name' => $name,
                'email' => $email,
            ];
            return $result;
        }, []);
    }

    public function mergeGv($arr)
    {
        return array_reduce($arr, function ($result, $gv) {
            return array_merge($result, [implode('|', [$gv->id, $gv->name, $gv->email])]);
        }, []);
    }
}
