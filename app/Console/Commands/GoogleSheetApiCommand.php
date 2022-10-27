<?php

namespace App\Console\Commands;

use App\Models\BoMon;
use App\Models\LopDotThi;
use App\Models\MonDotThi;
use App\Models\Monhoc;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Google_Service_Sheets;

class GoogleSheetApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:sheet_api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //hau
        //heloo
        Log::debug('start update sheet 1 data');
        $client = $this->getGooogleClient();
        $service = new Google_Service_Sheets($client);
        $spreadsheetId = '1yOqz4qAmKXTPtKbXYA-fTKK7-z6CNTCNaZmNoYJ48rg';
        $range = 'KH thi Block 1!A2:T';

        // get values
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();
        dd($values);

//        print_r($response);   print_r($response);
        $soBanGhi = 0;
        $maMonThi = [];
        $tenMonThi = [];
        $tenLopThi = [];
        $nganh = [];
        foreach ($response as $key=> $row){
            if ($key >1) {
                $maMonThi[] = isset($row[7]) ? $row[7] : "";
                $tenMonThi[] = isset($row[6]) ? $row[6] : "";
                $tenLopThi[] = isset($row[11]) ? $row[11] : "";
                $nganh[] = isset($row[13]) ? $row[13] : "";
                $giamThi1[] = isset($row[14]) ? $row[14] . "@fpt.edu.vn" : "";
            }
            // tạo tài khoản gv nếu chưa có
//            $user = User::where('email', $giamThi1 . "@fpt.edu.vn")->first();
//            if(!$user){
//                $user = new User();
//                $user->name = $giamThi1;
//                $user->email = $giamThi1 . "@fpt.edu.vn";
//                $user->password = Hash::make(uniqid());
//                $user->save();
//            }

            // kiểm tra bộ môn
//            $boMon = BoMon::where('ma_bo_mon', $nganh)->first();
//            if(!$boMon){
//                continue;
//            }

            // kiểm tra môn học, nếu chưa có thì tạo
//            $monHoc = Monhoc::where('ma_mon_hoc', $maMonThi)->first();
//            if(!$monHoc){
//                $monHoc = new Monhoc();
//                $monHoc->name = $tenMonThi;
//                $monHoc->ma_mon_hoc = $maMonThi;
//                $monHoc->bo_mon_id = $boMon->id;
//                $monHoc->save();
//            }

            // bổ sung môn học của đợt thi
//            $monHocCuaDotThi = new MonDotThi();
//            $monHocCuaDotThi->dot_thi_id = $event->dotthi->id;
//            $monHocCuaDotThi->mon_hoc_id = $monHoc->id;
//            $monHocCuaDotThi->save();

            // kiểm tra lớp của đợt thi
//            $lopDotThi = LopDotThi::where('dot_thi_id', $event->dotthi->id)
//                ->where('name', trim($tenLopThi))->first();
//            if(!$lopDotThi){
//                $lopDotThi = new LopDotThi();
//                $lopDotThi->name = $tenLopThi;
//                $lopDotThi->dot_thi_id = $event->dotthi->id;
//                $lopDotThi->giang_vien_id = $user->id;
//                $lopDotThi->save();
//            }
            $soBanGhi++;
        }

        $tenMonThi =  array_unique($tenMonThi);
        $tenLopThi =  array_unique($tenLopThi);
        $nganh =  array_unique($nganh);
        $giamThi1 =  array_unique($giamThi1);
        $giamThi1Data = User::whereIn('email', $giamThi1)->get()->toArray();
        dd($giamThi1Data);
        $giamThi1DataAdd = [];
        foreach ($giamThi1 as $key => $values) {
           if (!empty($values) && $values  != $giamThi1Data[0]['email']) {
               $name = explode("@", $values);
               $giamThi1DataAdd[$key]['name'] = $name[0];
               $giamThi1DataAdd[$key]['email'] = $values;
               $giamThi1DataAdd[$key]['password'] = Hash::make(uniqid());
               $giamThi1DataAdd[$key]['role_id'] = 1;
           }
        }
//        DB::table('users')->insert($giamThi1DataAdd);
        $maMonThi =  array_unique($maMonThi);
        $maMonThiData = Monhoc::whereIn('ma_mon_hoc', $maMonThi)->get()->toArray();
        dd($maMonThiData);
        $maMonThiDataAdd = [];
        $boMon = BoMon::whereIn('ma_bo_mon', $nganh)->get()->toArray();
//        dd($boMon);
       foreach ($maMonThi as $key => $value) {
//            if (!empty($values) && $values != $maMonThiData[0][] ) {
                $maMonThiDataAdd[$key]['name'] = "123";
                $maMonThiDataAdd[$key]['ma_mon_hoc'] = "123";
                $maMonThiDataAdd[$key]['bo_mon_id'] = "123";

//            }
       }

        // add/edit values
        $data = [
            [
                'column A2',
                'column B2',
                'column C2',
                'column D2',
            ],
            [
                'column A3',
                'column B3',
                'column C3',
                'column D3',
            ],
        ];
        $requestBody = new \Google_Service_Sheets_ValueRange([
            'values' => $data
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        $service->spreadsheets_values->update($spreadsheetId, $range, $requestBody, $params);
        echo "SUCCESS \n";
        Log::debug('update sheet 1 data success');
    }


    public function getGooogleClient()
    {
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
        $client->setAuthConfig(config_path('credentials.json'));
        $client->setAccessType('offline');

        $tokenPath = storage_path('app/token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }

            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        return $client;
    }

}
