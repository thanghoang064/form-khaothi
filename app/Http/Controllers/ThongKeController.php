<?php

namespace App\Http\Controllers;

use App\Models\DiemEos;
use App\Models\DiemSinhVien;
use App\Models\Monhoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Fuge;
use App\Models\KyHoc;
use App\Models\BoMon;
use App\Models\DotThi;
use App\Models\MonDotThi;
use App\Models\CaDotThi;
use App\Models\LopDotThi;
use App\Models\QuanLyFileEos;
use Illuminate\Support\Facades\Auth;

class ThongKeController extends Controller
{
    public $hocKy;
    public $hasHocKy;
    public $dotThi;
    public $hasDotThi;
    public $boMon;
    public $maBoMonToName = [];

    public function __construct()
    {
        $this->hocKy = KyHoc::select('id', 'name')->where('status', '1')->orderBy('id', 'DESC')->first();
        $this->hasHocKy = !empty($this->hocKy);
        $this->dotThi = DotThi::select('id', 'name')->where('status', '1')->first();
        $this->hasDotThi = !empty($this->dotThi);
        $this->boMon = BoMon::all()->toArray();
        foreach ($this->boMon as $bm) {
            $this->maBoMonToName[$bm['id']] = $bm['name'];
        }
    }

    public function baoCaoThi()
    {
        if (!$this->hasDotThi) {
            return view('admin.thongke.loi-thong-ke');
        }

        extract($this->getBaoCaoThiTheoBoMon());
        $thongKeBaoCaoThiTong = [
            'so_ca_thi' => $tongSoCaThi,
            'so_ca_da_bao_cao' => $soCaDaBaoCao,
            'so_ca_chua_bao_cao' => $tongSoCaThi - $soCaDaBaoCao
        ];

        $labels = [];
        $tongSoCa = [];
        $soCaDaBaoCao = [];
        $soCaChuaBaoCao = [];
        foreach ($thongKeBaoCaoThiTheoBoMon as $item) {
            $labels[] = $item['name'];
            $tongSoCa[] = $item['so_ca_thi'];
            $soCaDaBaoCao[] = $item['so_ca_da_bao_cao'];
            $soCaChuaBaoCao[] = $item['so_ca_chua_bao_cao'];
        }

        return view('admin.thongke.bao-cao-thi.tongquan', [
            'dotThiName' => $this->dotThi->name,
            'thongKeBaoCaoThiTong' => $thongKeBaoCaoThiTong,
            'boMon' => $this->boMon,
            'labels' => $labels,
            'tongSoCa' => $tongSoCa,
            'soCaDaBaoCao' => $soCaDaBaoCao,
            'soCaChuaBaoCao' => $soCaChuaBaoCao,
        ]);

    }

    public function baoCaoThiTheoBoMon()
    {
        if (!$this->hasDotThi) {
            return view('admin.thongke.loi-dot-thi-thong-ke');
        }

        extract($this->getBaoCaoThiTheoBoMon());

        $soCaThiTheoGiangVien = DB::table('ca_dot_thi')
            ->select(DB::raw('lop_dot_thi.giang_vien_id, count(lop_dot_thi.giang_vien_id) as so_ca_thi'))
            ->join('lop_dot_thi', 'lop_dot_thi.id', '=', 'ca_dot_thi.lop_dot_thi_id')
            ->join('users', 'users.id', '=', 'lop_dot_thi.giang_vien_id')
            ->groupBy('lop_dot_thi.giang_vien_id', 'users.role_id', 'ca_dot_thi.dot_thi_id')
            ->havingRaw("ca_dot_thi.dot_thi_id = {$this->dotThi->id} and users.role_id = 1")
            ->get()->toArray();

        $soCaThiTheoGiangVienArr = [];
        foreach ($soCaThiTheoGiangVien as $item) {
            $giang_vien_id = $item->giang_vien_id;
            $soCaThiTheoGiangVienArr[$giang_vien_id] = $item->so_ca_thi;
        }

        $giangVien = User::select('id', 'name', 'email', 'role_bomon')
            ->where('role_id', 1)->get()->toArray();

        $soCaDaBaoCaoCuaGiangVien = [];

        $soCaDaBaoCao = DB::table('luot_bao_cao')
            ->select(DB::raw('luot_bao_cao.mon_hoc_id, luot_bao_cao.ten_lop, count(*) as so_ca_da_bao_cao'))
            ->groupBy('luot_bao_cao.mon_hoc_id', 'luot_bao_cao.ten_lop', 'luot_bao_cao.dot_thi_id')
            ->having('luot_bao_cao.dot_thi_id', $this->dotThi->id)
            ->get()->toArray();
        $soCaDaBaoCaoArr = [];
        foreach ($soCaDaBaoCao as $item) {
            $key = $item->ten_lop . '|' . $item->mon_hoc_id;
            $soCaDaBaoCaoArr[$key] = $item->so_ca_da_bao_cao;
        }

        $dsLop = LopDotThi::select('lop_dot_thi.name', 'lop_dot_thi.giang_vien_id', 'mon_hoc.id as mon_hoc_id', 'mon_hoc.bo_mon_id')
            ->join('mon_dot_thi', 'mon_dot_thi.id', '=', 'lop_dot_thi.mon_dot_thi_id')
            ->join('mon_hoc', 'mon_hoc.id', '=', 'mon_hoc_id')
            ->where('lop_dot_thi.dot_thi_id', $this->dotThi->id)
            ->get()->toArray();
        $dsLopArr = [];
        foreach ($dsLop as $item) {
            $key = $item['name'] . '|' . $item['mon_hoc_id'];
            $dsLopArr[$key] = $item['giang_vien_id'];
        }
        $thongKeSoCaDaBaoCaoArr = [];
        foreach ($soCaDaBaoCaoArr as $key => $item) {
            $giang_vien_id = $dsLopArr[$key];
            if (!empty($giang_vien_id)) {
                if (!empty($thongKeSoCaDaBaoCaoArr[$giang_vien_id])) {
                    $thongKeSoCaDaBaoCaoArr[$giang_vien_id] += $item;
                } else {
                    $thongKeSoCaDaBaoCaoArr[$giang_vien_id] = $item;
                }
            }
        }

        foreach ($giangVien as $gv) {
//            dd($gv);
            $id_bomon = $gv['role_bomon'];
            $giang_vien_id = $gv['id'];
            $so_ca_thi = $soCaThiTheoGiangVienArr[$giang_vien_id] ?? 0;
            $so_ca_thi_da_bao_cao = $thongKeSoCaDaBaoCaoArr[$giang_vien_id] ?? 0;
            if ($so_ca_thi !== 0) {
                $ti_le_bao_cao = ($so_ca_thi_da_bao_cao / $so_ca_thi) * 100;
            } else {
                $ti_le_bao_cao = 0.0;
            }
            $ti_le_bao_cao = number_format((float)$ti_le_bao_cao, 1, '.', '');
            $thongKeBaoCaoThiTheoBoMon[$id_bomon]['danh_sach'][] = [
                'name' => $gv['name'],
                'email' => $gv['email'],
                'so_ca_thi' => $so_ca_thi,
                'so_ca_da_bao_cao' => $so_ca_thi_da_bao_cao,
                'ti_le_bao_cao' => $ti_le_bao_cao,
            ];
        }
        foreach ($thongKeBaoCaoThiTheoBoMon as $id => $item) {
            if (empty($item['danh_sach'])) {
                $thongKeBaoCaoThiTheoBoMon[$id]['danh_sach'] = [];
            }
        }
        return view('admin.thongke.bao-cao-thi.bao-cao-thi-theo-bo-mon', [
            'dotThiName' => $this->dotThi->name,
            'thongKeBaoCaoThiTheoBoMon' => $thongKeBaoCaoThiTheoBoMon,
            'boMon' => $this->boMon
        ]);

    }

    public function getBaoCaoThiTheoBoMon()
    {
        $caDotThi = DB::table('ca_dot_thi')
            ->select(DB::raw('mon_hoc.bo_mon_id, count(mon_hoc.bo_mon_id) as so_ca_thi'))
            ->join('lop_dot_thi', 'lop_dot_thi.id', '=', 'ca_dot_thi.lop_dot_thi_id')
            ->join('mon_dot_thi', 'mon_dot_thi.id', '=', 'lop_dot_thi.mon_dot_thi_id')
            ->join('mon_hoc', 'mon_hoc.id', '=', 'mon_dot_thi.mon_hoc_id')
            ->groupBy('mon_hoc.bo_mon_id', 'ca_dot_thi.dot_thi_id')
            ->having('ca_dot_thi.dot_thi_id', '=', $this->dotThi->id)
            ->get()->toArray();

        $caDaBaoCao = DB::table('luot_bao_cao')
            ->select(DB::raw('mon_hoc.bo_mon_id, count(mon_hoc.bo_mon_id) as so_ca_da_bao_cao'))
            ->join('mon_hoc', 'mon_hoc.id', '=', 'luot_bao_cao.mon_hoc_id')
            ->groupBy('mon_hoc.bo_mon_id', 'luot_bao_cao.dot_thi_id')
            ->having('luot_bao_cao.dot_thi_id', '=', $this->dotThi->id)
            ->get()->toArray();

        $tongSoCaThi = 0;
        $soCaDaBaoCao = 0;
        $caDotThiArr = [];
        foreach ($caDotThi as $cdt) {
            $id_bo_mon = $cdt->bo_mon_id;
            $so_ca_thi = $cdt->so_ca_thi;
            $tongSoCaThi += $so_ca_thi;
            $caDotThiArr[$id_bo_mon] = $so_ca_thi;
        }
        $caDaBaoCaoArr = [];
        foreach ($caDaBaoCao as $cdt) {
            $id_bo_mon = $cdt->bo_mon_id;
            $so_ca_da_bao_cao = $cdt->so_ca_da_bao_cao;
            $soCaDaBaoCao += $so_ca_da_bao_cao;
            $caDaBaoCaoArr[$id_bo_mon] = $so_ca_da_bao_cao;
        }

        $thongKeBaoCaoThiTheoBoMon = [];
        foreach ($this->boMon as $bm) {
            $id = $bm['id'];
            $so_ca_thi = $caDotThiArr[$id] ?? 0;
            $so_ca_da_bao_cao = $caDaBaoCaoArr[$id] ?? 0;
            $so_ca_chua_bao_cao = $so_ca_thi - $so_ca_da_bao_cao;
            $thongKeBaoCaoThiTheoBoMon[$id] = [
                'id' => $id,
                'name' => $bm['name'],
                'so_ca_thi' => $so_ca_thi,
                'so_ca_da_bao_cao' => $so_ca_da_bao_cao,
                'so_ca_chua_bao_cao' => $so_ca_chua_bao_cao,
            ];
        }
        return [
            'thongKeBaoCaoThiTheoBoMon' => $thongKeBaoCaoThiTheoBoMon,
            'tongSoCaThi' => $tongSoCaThi,
            'soCaDaBaoCao' => $soCaDaBaoCao,
        ];
    }

    public function thongKeDiem()
    {
        if (!$this->hasDotThi) {
            return view('admin.thongke.loi-dot-thi-thong-ke');
        }

        $tieuChiThongKe = [
            [0, 2],
            [2, 5],
            [5, 8],
            [8, 10],
        ];

        $monHoc = MonDotThi::select('mon_hoc.id as mon_hoc_id', 'mon_hoc.name', 'mon_hoc.bo_mon_id')
            ->join('mon_hoc', 'mon_hoc.id', '=', 'mon_dot_thi.mon_hoc_id')
            ->where('mon_dot_thi.dot_thi_id', $this->dotThi->id)
            ->get()->toArray();

        $lopDotThi = LopDotThi::select('lop_dot_thi.id', 'lop_dot_thi.name', 'mon_hoc.id as mon_hoc_id')
            ->join('mon_dot_thi', 'mon_dot_thi.id', '=', 'lop_dot_thi.mon_dot_thi_id')
            ->join('mon_hoc', 'mon_hoc.id', '=', 'mon_dot_thi.mon_hoc_id')
            ->where('lop_dot_thi.dot_thi_id', $this->dotThi->id)
            ->get()->toArray();

        $diemSinhVien = DiemSinhVien::select('diem_sinh_vien.lop_dot_thi_id', 'diem_sinh_vien.diem')
            ->join('lop_dot_thi', 'lop_dot_thi.id', '=', 'diem_sinh_vien.lop_dot_thi_id')
            ->where('lop_dot_thi.dot_thi_id', $this->dotThi->id)
            ->get()->toArray();

        $diemSinhVienTheoLop = [];
        foreach ($diemSinhVien as $diem) {
            $lop_dot_thi_id = $diem['lop_dot_thi_id'];
            $diemSinhVienTheoLop[$lop_dot_thi_id][] = $diem['diem'];
        }

        $lopDotThiArr = [];
        foreach ($lopDotThi as $item) {
            $lop = [];
            $lop_dot_thi_id = $item['id'];
            $lop['mon_hoc_id'] = $item['mon_hoc_id'];
            $lop['name'] = $item['name'];
            $lop['thong_ke_diem'] = [
                'range_0_2' => 0,
                'range_2_5' => 0,
                'range_5_8' => 0,
                'range_8_10' => 0,
            ];
            $lopDotThiArr[$lop_dot_thi_id] = $lop;
        }
        foreach ($diemSinhVienTheoLop as $lopId => $lop) {
            foreach ($lop as $diem) {
                foreach ($tieuChiThongKe as $each) {
                    if ($this->soSanhDiem($each, $diem)) {
                        $key = 'range_' . implode('_', $each);
                        if (!empty($lopDotThiArr[$lopId])) {
                            $lopDotThiArr[$lopId]['thong_ke_diem'][$key]++;
                        }
                        break;
                    }
                }
            }
        }

        $thongKeDiemTheoMon = [];
        foreach ($lopDotThiArr as $lop) {
            $mon_hoc_id = $lop['mon_hoc_id'];
//            dd($thongKeDiemTheoMon[$mon_hoc_id]);
            if (isset($thongKeDiemTheoMon[$mon_hoc_id])) {
                $this->tinhTongDiem($tieuChiThongKe, $thongKeDiemTheoMon[$mon_hoc_id], $lop['thong_ke_diem']);
            } else {
                $this->tinhTongDiem($tieuChiThongKe, $thongKeDiemTheoMon[$mon_hoc_id], $lop['thong_ke_diem'], true);
            }
        }
//        dd($thongKeDiemTheoMon);

        return view('admin.thongke.pho-diem.pho-diem', [
            'boMon' => $this->boMon,
            'monHoc' => $monHoc,
            'lopDotThi' => $lopDotThiArr,
            'dotThiName' => $this->dotThi->name,
            'thongKeDiemTheoMon' => $thongKeDiemTheoMon,
        ]);
    }

    public function thongKeDiemEos()
    {
        if (!$this->hasHocKy) {
            return view('admin.thongke.loi-ky-hoc-thong-ke');
        }
        $fileEos = QuanLyFileEos::select('created_at', 'hoc_ky_id')
            ->get()->toArray();
        $idHocKyHienTai = $this->hocKy->id;
        $kyHoc = KyHoc::select('id', 'name')->get()->toArray();
        $kyHocArr = array_map(function ($item) {
            return $item['id'];
        }, $kyHoc);
        $thongKeTheoKy = [];
        $thoiGianCapNhat = QuanLyFileEos::select('id', 'created_at', 'hoc_ky_id')
            ->orderBy('id', 'asc')
            ->get()->toArray();
        $thoiGianCapNhatArr = [];
        foreach ($thoiGianCapNhat as $each) {
//            dd($each);
            $hoc_ky_id = $each['hoc_ky_id'];
            $thoiGianCapNhatArr[$hoc_ky_id] = $each['created_at'];
        }
//        dd($thoiGianCapNhatArr, $thoiGianCapNhat);
//        dd($thoiGianCapNhat);
        foreach ($kyHocArr as $kh) {
            $thoi_gian_cap_nhat = $thoiGianCapNhatArr[$kh] ?? '';
            if (!empty($thoi_gian_cap_nhat)) {
                $thoi_gian_cap_nhat = date('H:i d-m-Y', strtotime($thoi_gian_cap_nhat));
            }
            $thongKeTheoKy[$kh] = [
                'hoc_ky_id' => $kh,
                'thoi_gian_cap_nhat' => $thoi_gian_cap_nhat,
                'thong_ke' => [],
            ];
        }

        $tieuChiThongKe = [
            [0, 0.99],
            [1, 1.99],
            [2, 2.99],
            [3, 3.99],
            [4, 4.99],
            [5, 5.99],
            [6, 6.99],
            [7, 7.99],
            [8, 8.99],
            [9, 9.99],
            [10],
        ];

        $diemEosDb = DiemEos::select('diem', 'mon_hoc_id', 'ky_hoc_id')
//            ->where('ky_hoc_id', $this->hocKy->id)
            ->get()->toArray();
//        dd($diemEosDb);
        $monHocId = DiemEos::select('mon_hoc_id', 'ky_hoc_id')
            ->groupBy('mon_hoc_id', 'ky_hoc_id')
//            ->having('ky_hoc_id', $this->hocKy->id)
            ->get()->toArray();
        $monHocIdArr = array_unique(array_map(function ($item) {
            return $item['mon_hoc_id'];
        }, $monHocId));
//        dd($monHocIdArr);
        $monHoc = Monhoc::select('id', 'name', 'bo_mon_id', 'ma_mon_hoc')
            ->whereIn('id', $monHocIdArr)
            ->get()->toArray();
        $idMonHocToInfo = [];
        foreach ($monHoc as $mh) {
            $idMonHocToInfo[$mh['id']] = $mh;
        }

//        $monHocArr = [];
        foreach ($monHocId as $mh) {
            extract($mh);
//            dd($mon_hoc_id);
            $monHoc = $idMonHocToInfo[$mon_hoc_id];
            $mon = [];
            $mon['id'] = $monHoc['id'];
            $mon['name'] = $monHoc['name'];
            $mon['ma_mon'] = $monHoc['ma_mon_hoc'];
            $mon['ma_bo_mon'] = $monHoc['bo_mon_id'];
            $mon['ten_bo_mon'] = $this->maBoMonToName[$monHoc['bo_mon_id']];
            $mon['thong_ke_diem'] = [];
            foreach ($tieuChiThongKe as $item) {
                $key = 'range_' . implode('_', $item);
                $mon['thong_ke_diem'][$key] = 0;
            }
            $thongKeTheoKy[$ky_hoc_id]['thong_ke'][$monHoc['id']] = $mon;
        }
//        dd($thongKeTheoKy);

        foreach ($diemEosDb as $diemEos) {
//            dd($diemEos);
            extract($diemEos);
            foreach ($tieuChiThongKe as $each) {
                if ($this->soSanhDiemEos($each, $diem)) {
                    $key = 'range_' . implode('_', $each);
                    if (!empty($thongKeTheoKy[$ky_hoc_id]['thong_ke'][$mon_hoc_id])) {
                        $thongKeTheoKy[$ky_hoc_id]['thong_ke'][$mon_hoc_id]['thong_ke_diem'][$key]++;
                    }
                    break;
                }
            }
        }
        $dataView = [
            'hocKy' => $kyHoc,
            'idHocKyHienTai' => $idHocKyHienTai,
            'thongKeDiemTheoKy' => $thongKeTheoKy,
        ];
        return view('admin.thongke.pho-diem.pho-diem-eos', $dataView);
    }

    public function tinhTongDiem($tieuChiThongKe, &$tong, &$item, $setDefault = false)
    {
        foreach ($tieuChiThongKe as $each) {
            $key = 'range_' . implode('_', $each);
            if (!$setDefault) {
                $tong[$key] += $item[$key];
            } else {
                $tong[$key] = $item[$key];
            }
//            dd($tong);
        }
    }

    public function soSanhDiem(array $khoangDiem, $diem)
    {
        $diem = number_format((float)$diem, 2, '.', '');
        [$min, $max] = $khoangDiem;
        if ($max === 10) {
            return $diem >= $min && $diem <= $max;
        }
        return $diem >= $min && $diem < $max;
    }

    public function soSanhDiemEos(array $khoangDiem, $diem)
    {
        if (count($khoangDiem) === 2) {
            [$min, $max] = $khoangDiem;
            return $diem >= $min && $diem < $max;
        }
        return $diem === $khoangDiem[0];
    }


//    public function nhapDiem()
//    {
//        if (!$this->hasHocKy) {
//            return view('admin.thongke.loi-thong-ke');
//        }
//        $hocKyId = $this->hocKy->id;
//        $hocKyName = $this->hocKy->name;
//
//        $soGiangVienDaNhapDiemTheoBoMon = DB::table('fuge')
//            ->select(DB::raw('users.role_bomon, count(users.role_bomon) as so_giang_vien_da_nhap_diem'))
//            ->join('users', 'users.id', "=", 'fuge.user_id')
//            ->groupBy('users.role_bomon', 'users.role_id', 'fuge.hoc_ky_id')
//            ->havingRaw("users.role_id = 1 and fuge.hoc_ky_id = {$hocKyId}")
//            ->get()->toArray();
//
//        $soGiangVienTheoBoMon = DB::table('users')
//            ->select(DB::raw('users.role_bomon, count(users.role_bomon) as so_giang_vien'))
//            ->groupBy('users.role_bomon', 'users.role_id')
//            ->havingRaw('users.role_id = ?', [1])
//            ->get()->toArray();
//
//        $soGiangVienDaNhapDiemTheoBoMonArr = [];
//        foreach ($soGiangVienDaNhapDiemTheoBoMon as $gv) {
//            $boMonId = $gv->role_bomon;
//            $soGiangVien = $gv->so_giang_vien_da_nhap_diem;
//            $soGiangVienDaNhapDiemTheoBoMonArr[$boMonId] = $soGiangVien;
//        }
//
//        $soGiangVienTheoBoMonArr = [];
//        foreach ($soGiangVienTheoBoMon as $gv) {
//            $boMonId = $gv->role_bomon;
//            $soGiangVien = $gv->so_giang_vien;
//            $soGiangVienTheoBoMonArr[$boMonId] = $soGiangVien;
//        }
//        $boMon = BoMon::all()->toArray();
//        $thongKeNhapDiemTheoBoMon = [];
//        foreach ($boMon as $bm) {
//            $id = $bm['id'];
//            $tong_so_giang_vien = $soGiangVienTheoBoMonArr[$id] ?? 0;
//            $so_giang_vien_da_nhap_diem = $soGiangVienDaNhapDiemTheoBoMonArr[$id] ?? 0;
//            $so_giang_vien_chua_nhap_diem = $tong_so_giang_vien - $so_giang_vien_da_nhap_diem;
//            $thongKeNhapDiemTheoBoMon[] = [
//                'id' => $id,
//                'name' => $bm['name'],
//                'tong_so_giang_vien' => $tong_so_giang_vien,
//                'so_giang_vien_da_nhap_diem' => $so_giang_vien_da_nhap_diem,
//                'so_giang_vien_chua_nhap_diem' => $so_giang_vien_chua_nhap_diem,
//            ];
//        }
//        return view('admin.thongke.nhap-diem.nhap-diem', compact('thongKeNhapDiemTheoBoMon', 'hocKyName'));
//    }

//    public function nhapDiemTheoBoMon($idBoMon)
//    {
//        if (!$this->hasHocKy) {
//            return view('admin.thongke.loi-thong-ke');
//        }
//        $hocKyId = $this->hocKy->id;
//        $hocKyName = $this->hocKy->name;
//        $boMon = BoMon::select('id', 'name')
//            ->where('id', $idBoMon)
//            ->first();
//
//        $giangVienDaNhapDiemCuaBoMon = DB::table('fuge')
//            ->select('users.id', 'users.name', 'users.email')
//            ->join('users', 'users.id', "=", 'fuge.user_id')
//            ->where('fuge.hoc_ky_id', $hocKyId)
//            ->where('users.role_bomon', $boMon->id)
//            ->where('role_id', 1)
//            ->get()->toArray();
//
//        $giangVienDaNhapDiemCuaBoMonArr = $this->mergeGv($giangVienDaNhapDiemCuaBoMon);
//
//        $giangVienCuaBoMon = DB::table('users')
//            ->select('users.id', 'users.name', 'users.email')
//            ->where('users.role_bomon', $boMon->id)
//            ->where('role_id', 1)
//            ->get()->toArray();
//
//        $giangVienCuaBoMonArr = $this->mergeGv($giangVienCuaBoMon);
//
//        $giangVienChuaNhapDiemCuaBoMon = array_diff($giangVienCuaBoMonArr, $giangVienDaNhapDiemCuaBoMonArr);
//        $tong_so_giang_vien = count($giangVienCuaBoMonArr);
//        $so_giang_vien_chua_nhap_diem = count($giangVienChuaNhapDiemCuaBoMon);
//        $so_giang_vien_da_nhap_diem = count($giangVienDaNhapDiemCuaBoMonArr);
//
//        $giangVienCuaBoMonArr = $this->handleGv($giangVienCuaBoMonArr);
//        $giangVienDaNhapDiemCuaBoMonArr = $this->handleGv($giangVienDaNhapDiemCuaBoMonArr);
//        $giangVienChuaNhapDiemCuaBoMonArr = $this->handleGv($giangVienChuaNhapDiemCuaBoMon);
//
//        $giangVien = [
//            'tong_so' => $tong_so_giang_vien,
//            'danh_sach' => $giangVienCuaBoMonArr
//        ];
//
//        $giangVienChuaNhap = [
//            'tong_so' => $so_giang_vien_chua_nhap_diem,
//            'danh_sach' => $giangVienChuaNhapDiemCuaBoMonArr
//        ];
//
//        $giangVienDaNhap = [
//            'tong_so' => $so_giang_vien_da_nhap_diem,
//            'danh_sach' => $giangVienDaNhapDiemCuaBoMonArr
//        ];
//        return view('admin.thongke.nhap-diem.nhap-diem-theo-bo-mon', compact('giangVien', 'giangVienChuaNhap', 'giangVienDaNhap', 'boMon', 'hocKyName'));
//    }

//    public function handleGv($arr)
//    {
//        return array_reduce($arr, function ($result, $gv) {
//            [$id, $name, $email] = explode('|', $gv);
//            $result[] = [
//                'id' => $id,
//                'name' => $name,
//                'email' => $email,
//            ];
//            return $result;
//        }, []);
//    }

//    public function mergeGv($arr)
//    {
//        return array_reduce($arr, function ($result, $gv) {
//            return array_merge($result, [implode('|', [$gv->id, $gv->name, $gv->email])]);
//        }, []);
//    }
}
