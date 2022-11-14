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
    public $hocKy;
    public $hasHocKy;

    public function __construct()
    {
        $this->hocKy = KyHoc::select('id', 'name')->where('status', '1')->orderBy('id', 'DESC')->first();
        $this->hasHocKy = !empty($this->hocKy);
    }

    public function renderLoiThongKe()
    {

    }

    public function nhapDiem()
    {
        if (!$this->hasHocKy) {
            return view('admin.thongke.loi-thong-ke');
        }
        $hocKyId = $this->hocKy->id;
        $hocKyName = $this->hocKy->name;
//        $idGiangVienDaNhapDiem = Fuge::select('user_id')->where('hoc_ky_id', $hocKyId)->get();
//        $soGiangVienTheoBoMon = User::select('count(*)')->groupBy('role_bo_mon')->get();
        $sql_giang_vien_da_nhap_diem_theo_bo_mon = "
                select
                    user_dupli.role_bomon,
                    count(user_dupli.role_bomon) as so_giang_vien_da_nhap_diem
                from fuge
                inner join (select * from users where role_id = 1) as user_dupli on fuge.user_id = user_dupli.id
                group by user_dupli.role_bomon
                ";
        $soGiangVienDaNhapDiemTheoBoMon = DB::select($sql_giang_vien_da_nhap_diem_theo_bo_mon);
//        $soGiangVienDaNhapDiemTheoBoMon = DB::table('fuge')
//            ->select(DB::raw('users.role_bomon, count(users.role_bomon) as so_giang_vien_da_nhap_diem'))
//            ->join('users', 'users.id', "=", 'fuge.user_id')
//            ->groupBy('users.role_bomon')
//            ->get()->toArray();

//        $soGiangVienTheoBoMon = DB::table('users')
//            ->select(DB::raw('users.role_bomon, count(users.role_bomon) as so_giang_vien'))
//            ->groupBy('users.role_bomon')
//            ->havingRaw('users.role_id = ?', [1])
//            ->get()->toArray();
        $sql_giang_vien_theo_bo_mon = "
                select
                    t.role_bomon,
                    count(t.role_bomon) as so_giang_vien
                from (select * from users where role_id = 1) as t
                group by t.role_bomon
                ";
        $soGiangVienTheoBoMon = DB::select($sql_giang_vien_theo_bo_mon);
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
        return view('admin.thongke.nhap-diem.nhap-diem', compact('thongKeNhapDiemTheoBoMon', 'hocKyName'));
    }

    public function nhapDiemTheoBoMon($idBoMon)
    {
        if (!$this->hasHocKy) {
            return view('admin.thongke.loi-thong-ke');
        }
        $hocKyName = $this->hocKy->name;
        $boMon = BoMon::select('id', 'name')
            ->where('id', $idBoMon)
            ->first();

        $giangVienDaNhapDiemCuaBoMon = DB::table('fuge')
            ->select('users.id', 'users.name', 'users.email')
            ->join('users', 'users.id', "=", 'fuge.user_id')
            ->where('users.role_bomon', $boMon->id)
            ->where('role_id', 1)
            ->get()->toArray();

        $giangVienDaNhapDiemCuaBoMonArr = $this->mergeGv($giangVienDaNhapDiemCuaBoMon);

        $giangVienCuaBoMon = DB::table('users')
            ->select('users.id', 'users.name', 'users.email')
            ->where('users.role_bomon', $boMon->id)
            ->where('role_id', 1)
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
        return view('admin.thongke.nhap-diem.nhap-diem-theo-bo-mon', compact('giangVien', 'giangVienChuaNhap', 'giangVienDaNhap', 'boMon', 'hocKyName'));
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
