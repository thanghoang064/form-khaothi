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
            ['name' => "Nguyễn Hà Trung Hưng", 'email' => 'hungnth@fpt.edu.vn', 'email_fe' => 'hungnth@fe.edu.vn', 'password' => Hash::make('123456'), 'role_id' => 1],
            ['name' => 'Trần Hữu Thiện', 'email' => 'thienth@fpt.edu.vn', 'email_fe' => 'thienth@fe.edu.vn', 'password' => Hash::make('123456'), 'role_id' => 1],
            ['name' => 'Hoàng Quang Thắng (FE FPL HN)', 'email' => 'thanghq12@fpt.edu.vn', 'email_fe' => 'thanghq12@fe.edu.vn', 'password' => Hash::make('123456'), 'role_id' => 2],
            ['name' => 'Bích test', 'email' => 'bichdtph18289@fpt.edu.vn', 'email_fe' => 'bichdtph18289@fe.edu.vn', 'password' => Hash::make('123456'), 'role_id' => 2],
            ['name' => "linhnh72", 'email' => 'linhnh72@fpt.edu.vn', 'email_fe' => 'linhnh72@fe.edu.vn', 'password' => Hash::make('123456'), 'role_id' => 1],
            ['name' => "thanhnv96", 'email' => 'thanhnv96@fpt.edu.vn', 'email_fe' => 'thanhnv96@fe.edu.vn', 'password' => Hash::make('123456'), 'role_id' => 1],
            ['name' => "anhnh69", 'email' => 'anhnh69@fpt.edu.vn', 'email_fe' => 'anhnh69@fe.edu.vn', 'password' => Hash::make('123456'), 'role_id' => 2],
        ];
        DB::table('users')->insert($data);
    }
}
