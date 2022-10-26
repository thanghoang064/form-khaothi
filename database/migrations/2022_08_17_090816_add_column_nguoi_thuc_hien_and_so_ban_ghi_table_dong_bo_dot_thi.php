<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dong_bo_dot_thi', function (Blueprint $table) {
            $table->unsignedBigInteger('nguoi_thuc_hien')->nullable();
            $table->integer('so_ban_ghi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dong_bo_dot_thi', function (Blueprint $table) {
            $table->dropColumn('nguoi_thuc_hien');
            $table->dropColumn('so_ban_ghi');
        });
    }
};
