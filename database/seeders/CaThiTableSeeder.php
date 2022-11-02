<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CaThiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => "Ca 1",],
            ['name' => "Ca 2",],
            ['name' => "Ca 3",],
            ['name' => "Ca 4",],
            ['name' => "Ca 5",],
            ['name' => "Ca 6",],
        ];
        DB::table('ca_thi')->insert($data);
    }
}
