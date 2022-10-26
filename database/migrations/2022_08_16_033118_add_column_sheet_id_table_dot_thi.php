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
        Schema::table('dot_thi', function (Blueprint $table) {
            $table->string('sheet_id')->nullable();
            $table->integer("trang_thai_dong_bo")->default(1);
            $table->integer('luot_dong_bo')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dot_thi', function (Blueprint $table) {
            $table->dropColumn('sheet_id');
            $table->dropColumn('trang_thai_dong_bo');
            $table->dropColumn('luot_dong_bo');
        });
    }
};
