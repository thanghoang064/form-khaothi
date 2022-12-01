<?php

namespace App\Listeners;

use App\Events\GetDataDotThiProcessed;
use App\Models\BoMon;
use App\Models\CaDotThi;
use App\Models\DongBoDotThi;
use App\Models\LopDotThi;
use App\Models\MonDotThi;
use App\Models\Monhoc;
use App\Models\User;
use App\Models\DotThi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GetDataDotThiPodCast
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\GetDataDotThiProcessed $event
     * @return void
     */
    public function handle(GetDataDotThiProcessed $event)
    {
        // đọc dữ liệu từ file gg sheet
        $client = getGooogleClient();
        $service = new \Google_Service_Sheets($client);
        $range = 'KH thi Block 1!A4:O';
        $spreadsheetId = $event->dotthi->sheet_id;

        $data = $service->spreadsheets_values->get($spreadsheetId, $range);
        $soBanGhi = 0;

//        foreach ($data as $row){
//            $maMonThi = $row[6];
//            $tenMonThi = $row[5];
//            $tenLopThi = $row[10];
//            $nganh = $row[12];
//            $giamThi1 = $row[13];
//
//            // tạo tài khoản gv nếu chưa có
//            $user = User::where('email', $giamThi1 . "@fpt.edu.vn")->first();
//            if(!$user){
//                $user = new User();
//                $user->name = $giamThi1;
//                $user->email = $giamThi1 . "@fpt.edu.vn";
//                $user->password = Hash::make(uniqid());
//                $user->save();
//            }
//
//            // kiểm tra bộ môn
//            $boMon = BoMon::where('ma_bo_mon', $nganh)->first();
//            if(!$boMon){
//                continue;
//            }
//
//            // kiểm tra môn học, nếu chưa có thì tạo
//            $monHoc = Monhoc::where('ma_mon_hoc', $maMonThi)->first();
//            if(!$monHoc){
//                $monHoc = new Monhoc();
//                $monHoc->name = $tenMonThi;
//                $monHoc->ma_mon_hoc = $maMonThi;
//                $monHoc->bo_mon_id = $boMon->id;
//                $monHoc->save();
//            }
//
//            // bổ sung môn học của đợt thi
//            $monHocCuaDotThi = new MonDotThi();
//            $monHocCuaDotThi->dot_thi_id = $event->dotthi->id;
//            $monHocCuaDotThi->mon_hoc_id = $monHoc->id;
//            $monHocCuaDotThi->save();
//
//            // kiểm tra lớp của đợt thi
//            $lopDotThi = LopDotThi::where('dot_thi_id', $event->dotthi->id)
//                        ->where('name', trim($tenLopThi))->first();
//            if(!$lopDotThi){
//                $lopDotThi = new LopDotThi();
//                $lopDotThi->name = $tenLopThi;
//                $lopDotThi->dot_thi_id = $event->dotthi->id;
//                $lopDotThi->giang_vien_id = $user->id;
//                $lopDotThi->save();
//            }
//            $soBanGhi++;
//        }

        $dotThiId = $event->dotthi->id;
        $monThi = [];
        $tenLopThi = [];
        $nganh = [];
        $caDotThi = [];
        foreach ($data as $key => $row) {
            if ($key > 1) {
                if (!empty($row[7]) && !empty($row[6]) && !empty($row[13])) {
                    $monThi[$row[7]] = [
                        'ma_bo_mon' => $row[13],
                        'ten_mon' => $row[6],
                    ];
                }
                $nganh[] = isset($row[13]) ? $row[13] : "";

                if (!empty($row[2]) && !empty($row[7]) && !empty($row[11]) && !empty($row[12]) && !empty($row[1])) {
                    $ngayThi = date('Y-m-d', strtotime(str_replace('/', '-', $row[1])));
                    $lop = implode('|', [$row[7], $row[11], $row[12]]); // ma_mon|ten_lop|username|
                    $tenLopThi[] = $lop;
                    $caDotThi[] = implode('|', [$lop, $row[2], $ngayThi]); // ma_mon|ten_lop|username|ca|ngay_thi
                }

                if (!empty($row[14])) {
                    $giamThi1[] = mb_strtolower($row[14]) . "@fpt.edu.vn";
                }
            }
            $soBanGhi++;
        }


        // Lọc trùng data từ sheet
        $maMonThi = array_keys($monThi);
        $nganh = array_unique($nganh);
        $tenLopThi = array_values(array_unique($tenLopThi));
        $caDotThi = array_values(array_unique($caDotThi));
        $giamThi1 = array_values(array_unique($giamThi1));

        // Select users có trong data base và sheet
        $giamThi1Data = User::select('email')->whereIn('email', $giamThi1)->get()->toArray();

        // Flat mảng giamThiData vừa lấy về
        $giamThi1EmailData = array_reduce($giamThi1Data, function ($result, $email) {
            return array_merge($result, array_values($email));
        }, []);

        // Lọc ra những users chưa có trong database
        $usersDiff = array_diff($giamThi1, $giamThi1EmailData);
        $giamThi1DataAdd = [];

        // Nếu chưa có thì tạo tài khoản
        foreach ($usersDiff as $key => $email) {
            $name = explode("@", $email)[0];
            if (!empty($name)) { // Nếu tồn tại name mới cho thêm
                $email_fe = $name . '@fe.edu.vn';
                $giamThi1DataAdd[$key]['name'] = $name;
                $giamThi1DataAdd[$key]['email'] = $email;
                $giamThi1DataAdd[$key]['email_fe'] = $email_fe;
                $giamThi1DataAdd[$key]['password'] = Hash::make(uniqid());
                $giamThi1DataAdd[$key]['role_id'] = 1;
            }
            unset($usersDiff[$key]);
        }

        // Thêm users chưa có vào database
        DB::table('users')->insert($giamThi1DataAdd);

        // Môn học
        $maMonThiData = Monhoc::select('ma_mon_hoc')->whereIn('ma_mon_hoc', $maMonThi)->get()->toArray();
        $maMonThiData = array_reduce($maMonThiData, function ($result, $maMon) { // Flat
            return array_merge($result, array_values($maMon));
        }, []);
        $maMonThiDiff = array_diff($maMonThi, $maMonThiData); // Lấy ra môn học chưa có trong db
        $boMon = BoMon::select('id', 'ma_bo_mon')->whereIn('ma_bo_mon', $nganh)->get()->toArray();

        $arrBoMon = [];
        // Chuyển mã bộ môn thành key, id bộ môn thành value
        foreach ($boMon as $index => $each) {
            $arrBoMon[$each['ma_bo_mon']] = $each['id'];
            unset($boMon[$index]);
        }

        // Nếu chưa có môn học thì thêm vào bảng môn học
        $monHocDataAdd = [];
        foreach ($maMonThiDiff as $index => $ma_mon_hoc) {
            extract($monThi[$ma_mon_hoc]);
            if (!empty($arrBoMon[$ma_bo_mon])) { // Nếu tồn tại mã bộ môn mới cho thi
                $monHocAdd = [];
                $monHocAdd['name'] = $ma_mon_hoc . '_' . $ten_mon;
                $monHocAdd['bo_mon_id'] = $arrBoMon[$ma_bo_mon];
                $monHocAdd['ma_mon_hoc'] = $ma_mon_hoc;
                $monHocDataAdd[] = $monHocAdd;
                unset($maMonThiDiff[$index]);
            }
        }
        // Thêm môn học chưa có vào bảng mon_hoc
        DB::table('mon_hoc')->insert($monHocDataAdd);

        // Thêm vào bảng mon_dot_thi
        $monDotThi = MonDotThi::select('mon_hoc_id')->where('dot_thi_id', $dotThiId)->get()->toArray();
        $monDotThiArr = array_reduce($monDotThi, function ($result, $mon_hoc_id) { // Flat
            return array_merge($result, array_values($mon_hoc_id));
        }, []);

        // Lấy ra các môn thi trong bảng môn học từ db
        $monThiData = Monhoc::select('id', 'ma_mon_hoc')
            ->whereIn('ma_mon_hoc', $maMonThi)
            ->get()->toArray();
        $monHocArr = [];

        // Chuyền mã môn thành key, id môn thành value
        foreach ($monThiData as $each) {
            extract($each);
            $monHocArr[$ma_mon_hoc] = $id;
        }

        // Lọc ra id các môn đợt thi chưa tồn tại
        $monDotThiConThieu = array_diff($monHocArr, $monDotThiArr);

        // Thêm môn học còn thiếu vào bảng mon_dot_thi
        $monDotThiDataAdd = [];
        foreach ($monDotThiConThieu as $ma_mon_hoc => $id) {
            if (!empty($id)) {
                $monDotThi = [];
                $monDotThi['dot_thi_id'] = $dotThiId;
                $monDotThi['mon_hoc_id'] = $id;
                $monDotThiDataAdd[] = $monDotThi;
            }
        }

        DB::table('mon_dot_thi')->insert($monDotThiDataAdd);

        $giangVien = User::select('id', 'email')->get()->toArray();
        $giangVienArr = [];
        foreach ($giangVien as $gv) {
            $username = explode('@', $gv['email'])[0];
            $giangVienArr[$username] = $gv['id'];
        }

        $monHoc = MonHoc::select('id', 'ma_mon_hoc')->get()->toArray();
        $monHocArr = [];
        foreach ($monHoc as $mh) {
            extract($mh);
            $monHocArr[$ma_mon_hoc] = $id;
        }

        $monDotThi = MonDotThi::select('id', 'mon_hoc_id')->get()->toArray();
        $monDotThiArr = [];
        foreach ($monDotThi as $mdt) {
            extract($mdt);
            $monDotThiArr[$mon_hoc_id] = $id;
        }

        // Thêm lớp vào đợt thi
        $tenLopThiDb = DB::table('lop_dot_thi')
            ->join('users', 'lop_dot_thi.giang_vien_id', '=', 'users.id')
            ->join('mon_dot_thi', 'lop_dot_thi.mon_dot_thi_id', '=', 'mon_dot_thi.id')
            ->join('mon_hoc', 'mon_dot_thi.mon_hoc_id', '=', 'mon_hoc.id')
            ->select("lop_dot_thi.name", "users.email", "mon_hoc.ma_mon_hoc")
            ->where('lop_dot_thi.dot_thi_id', $dotThiId)
            ->get()->toArray();
        $tenLopThiDbArr = [];
        foreach ($tenLopThiDb as $lop) {
            $username = explode('@', $lop->email)[0];
            $tenLop = $lop->ma_mon_hoc . '|' . $lop->name . '|' . $username;
            $tenLopThiDbArr[] = $tenLop;
        }

        $lopConThieu = array_diff($tenLopThi, $tenLopThiDbArr);

        $lopDotThiDataAdd = [];
        foreach ($lopConThieu as $lop) {
            $lopAdd = [];
            [$ma_mon_hoc, $name, $username] = explode('|', $lop);
            $giangVienId = $giangVienArr[$username] ?? false;
            $mon_hoc_id = $monHocArr[$ma_mon_hoc] ?? false;
            $monDotThiId = $monDotThiArr[$mon_hoc_id] ?? false;
            if (!empty($giangVienId) && !empty($monDotThiId)) {
                $lopAdd['name'] = $name;
                $lopAdd['giang_vien_id'] = $giangVienId;
                $lopAdd['dot_thi_id'] = $dotThiId;
                $lopAdd['mon_dot_thi_id'] = $monDotThiId;
                $lopDotThiDataAdd[] = $lopAdd;
            }
        }

        DB::table('lop_dot_thi')->insert($lopDotThiDataAdd);
        // Xử lý ca đợt thi
        $lopDotThi = LopDotThi::select('id', 'name', 'giang_vien_id', 'mon_dot_thi_id')
            ->where('dot_thi_id', $dotThiId)
            ->get()->toArray();
        $lopDotThiArr = [];
        foreach ($lopDotThi as $lop) {
            extract($lop);
            $key = implode('_', [$name, $giang_vien_id, $mon_dot_thi_id]); // tenLop_giangVienId_monDotThiId
            $lopDotThiArr[$key] = $id;
        }

        $maMonDotThi = MonDotThi::select('id', 'mon_hoc_id')
            ->where('dot_thi_id', $dotThiId)
            ->get()->toArray();
        $maMonDotThiArr = [];
        foreach ($maMonDotThi as $each) {
            extract($each);
            $maMonDotThiArr[$mon_hoc_id] = $id; // truyền id môn học vào sẽ ra môn đợt thi
        }

        $caDotThiArr = [];
        foreach ($caDotThi as $each) {
            [$ma_mon_thi, $ten_lop, $username, $ca_thi_id, $ngay_thi] = explode('|', $each);
            $giang_vien_id = $giangVienArr[$username] ?? false;
            $mon_hoc_id = $monHocArr[$ma_mon_thi] ?? false;
            $mon_dot_thi_id = $maMonDotThiArr[$mon_hoc_id] ?? false;

            $key = implode('_', [$ten_lop, $giang_vien_id, $mon_dot_thi_id]);
            $lop_dot_thi_id = $lopDotThiArr[$key] ?? false;
            if (!empty($giang_vien_id) && !empty($lop_dot_thi_id) && !empty($mon_dot_thi_id)) {
                // format dạng lop_dot_thi_id|ca_thi_id|ngay_thi
                $ca_thi_add = implode('|', [$lop_dot_thi_id, $ca_thi_id, $ngay_thi]);
                $caDotThiArr[] = $ca_thi_add;
            }
        }
        $caDotThiDb = CaDotThi::select('ca_thi_id', 'lop_dot_thi_id', 'ngay_thi')
            ->where('ca_dot_thi.dot_thi_id', $dotThiId)
            ->get()->toArray();
        $caDotThiDbArr = [];
        foreach ($caDotThiDb as $each) {
            extract($each);
            $ca_dot_thi = implode('|', [$lop_dot_thi_id, $ca_thi_id, $ngay_thi]);
            $caDotThiDbArr[] = $ca_dot_thi;
        }

        $caDotThiConThieu = array_diff($caDotThiArr, $caDotThiDbArr);
        $caDotThiAdd = [];
        foreach ($caDotThiConThieu as $index => $cdt) {
            [$lop_dot_thi_id, $ca_thi_id, $ngay_thi] = explode('|', $cdt);
            $ca_dot_thi_add['dot_thi_id'] = $dotThiId;
            $ca_dot_thi_add['ca_thi_id'] = $ca_thi_id;
            $ca_dot_thi_add['lop_dot_thi_id'] = $lop_dot_thi_id;
            $ca_dot_thi_add['ngay_thi'] = $ngay_thi;
            $caDotThiAdd[] = $ca_dot_thi_add;
        }

        DB::table('ca_dot_thi')->insert($caDotThiAdd);

        // Cập nhật số lượng bản ghi của lượt đồng bộ
        $dongBoDotThiCuoiCung = DongBoDotThi::where('dot_thi_id', $event->dotthi->id)
            ->orderByDesc('id')
            ->first();
        $luotDongBo = 1;
        if ($dongBoDotThiCuoiCung) {
            $luotDongBo = $dongBoDotThiCuoiCung->luot_dong_bo + 1;
        }


        $dongBoDotThi = new DongBoDotThi();
        $dongBoDotThi->dot_thi_id = $event->dotthi->id;
        $dongBoDotThi->luot_dong_bo = $luotDongBo;
        $dongBoDotThi->sheet_id = $event->dotthi->sheet_id;
        $dongBoDotThi->nguoi_thuc_hien = isset(Auth::user()->id) ? Auth::user()->id : "1";
        $dongBoDotThi->so_ban_ghi = $soBanGhi;
        $dongBoDotThi->save();

        DotThi::where('id', '!=', $dotThiId)
            ->update(['status' => 0]);

        // đồng bộ xong thì đổi trạng thái của đợt thi
        $event->dotthi->trang_thai_dong_bo = 1;
        $event->dotthi->status = 1;
        $event->dotthi->save();
    }
}
