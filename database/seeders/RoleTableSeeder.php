<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Giảng viên'],
            ['name' => 'Khảo thí'],
            ['name' => 'Chủ nhiệm bộ môn']
        ];
        DB::table('roles')->insert($data);
    }
}
