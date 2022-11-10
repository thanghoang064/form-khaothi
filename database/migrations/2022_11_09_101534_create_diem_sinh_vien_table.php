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
        Schema::create('diem_sinh_vien', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sinh_vien_id');
            $table->bigInteger('lop_dot_thi_id');
            $table->double('diem', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diem_sinh_vien');
    }
};
