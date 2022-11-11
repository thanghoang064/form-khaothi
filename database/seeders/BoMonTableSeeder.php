<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoMonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Bộ môn Cơ bản',
                'ma_bo_mon' => 'CB',
            ],
            [
                'name' => 'Bộ môn Công nghệ thông tin',
                'ma_bo_mon' => 'CNTT',
            ],
            [
                'name' => 'Đồ họa Mỹ thuật đa phương tiện',
                'ma_bo_mon' => 'TKDH',
            ],
            [
                'name' => 'Bộ môn Kinh tế',
                'ma_bo_mon' => 'KT',
            ],
            [
                'name' => 'Bộ môn Du lịch - Nhà hàng - Khách sạn',
                'ma_bo_mon' => 'DL-KS-NH',
            ],
            [
                'name' => 'Bộ môn Điện - Cơ khí',
                'ma_bo_mon' => 'Đ-CK',
            ],
            [
                'name' => 'Bộ môn Thương mại điện tử',
                'ma_bo_mon' => 'TMĐT',
            ],
            [
                'name' => 'Bộ môn Ứng dụng phần mềm',
                'ma_bo_mon' => 'UDPM',
            ],
            [
                'name' => 'Bộ môn tiếng Anh',
                'ma_bo_mon' => 'ENG',
            ],
        ];
        DB::table('bo_mon')->insert($data);
    }
}
