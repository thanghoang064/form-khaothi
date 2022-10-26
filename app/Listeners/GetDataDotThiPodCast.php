<?php

namespace App\Listeners;

use App\Events\GetDataDotThiProcessed;
use App\Models\BoMon;
use App\Models\DongBoDotThi;
use App\Models\LopDotThi;
use App\Models\MonDotThi;
use App\Models\Monhoc;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
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
     * @param  \App\Events\GetDataDotThiProcessed  $event
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

        foreach ($data as $row){
            $maMonThi = $row[6];
            $tenMonThi = $row[5];
            $tenLopThi = $row[10];
            $nganh = $row[12];
            $giamThi1 = $row[13];

            // tạo tài khoản gv nếu chưa có
            $user = User::where('email', $giamThi1 . "@fpt.edu.vn")->first();
            if(!$user){
                $user = new User();
                $user->name = $giamThi1;
                $user->email = $giamThi1 . "@fpt.edu.vn";
                $user->password = Hash::make(uniqid());
                $user->save();
            }

            // kiểm tra bộ môn
            $boMon = BoMon::where('ma_bo_mon', $nganh)->first();
            if(!$boMon){
                continue;
            }

            // kiểm tra môn học, nếu chưa có thì tạo
            $monHoc = Monhoc::where('ma_mon_hoc', $maMonThi)->first();
            if(!$monHoc){
                $monHoc = new Monhoc();
                $monHoc->name = $tenMonThi;
                $monHoc->ma_mon_hoc = $maMonThi;
                $monHoc->bo_mon_id = $boMon->id;
                $monHoc->save();
            }

            // bổ sung môn học của đợt thi
            $monHocCuaDotThi = new MonDotThi();
            $monHocCuaDotThi->dot_thi_id = $event->dotthi->id;
            $monHocCuaDotThi->mon_hoc_id = $monHoc->id;
            $monHocCuaDotThi->save();

            // kiểm tra lớp của đợt thi
            $lopDotThi = LopDotThi::where('dot_thi_id', $event->dotthi->id)
                        ->where('name', trim($tenLopThi))->first();
            if(!$lopDotThi){
                $lopDotThi = new LopDotThi();
                $lopDotThi->name = $tenLopThi;
                $lopDotThi->dot_thi_id = $event->dotthi->id;
                $lopDotThi->giang_vien_id = $user->id;
                $lopDotThi->save();
            }
            $soBanGhi++;
        }
        // Cập nhật số lượng bản ghi của lượt đồng bộ
        $dongBoDotThiCuoiCung = DongBoDotThi::where('dot_thi_id', $event->dotthi->id)
                                    ->orderByDesc('id')
                                    ->first();
        $luotDongBo = 1;
        if($dongBoDotThiCuoiCung){
            $luotDongBo = $dongBoDotThiCuoiCung->luot_dong_bo + 1;
        }

        $dongBoDotThi = new DongBoDotThi();
        $dongBoDotThi->dot_thi_id = $event->dotthi->id;
        $dongBoDotThi->luot_dong_bo = $luotDongBo;
        $dongBoDotThi->sheet_id = $event->dotthi->sheet_id;
        $dongBoDotThi->nguoi_thuc_hien = Auth::user()->id;
        $dongBoDotThi->so_ban_ghi = $soBanGhi;
        $dongBoDotThi->save();

        // đồng bộ xong thì đổi trạng thái của đợt thi
        $event->dotthi->trang_thai_dong_bo = 1;
        $event->dotthi->save();
    }
}
