<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => "Nguyễn Hà Trung Hưng", 'email' => 'hungnth@fpt.edu.vn', 'password' => Hash::make('123456'),'role_id' => 1],
            ['name' => 'Trần Hữu Thiện', 'email' => 'thienth@fpt.edu.vn', 'password' => Hash::make('123456'),'role_id' => 1],
            ['name' => 'Hoàng Quang Thắng (FE FPL HN)', 'email' => 'thanghq12@fpt.edu.vn', 'password' => Hash::make('123456'),'role_id' => 2],
            ['name' => 'Bích test', 'email' => 'bichdtph18289@fpt.edu.vn', 'password' => Hash::make('123456'),'role_id' => 2],
        ];
        DB::table('users')->insert($data);
    }
}
