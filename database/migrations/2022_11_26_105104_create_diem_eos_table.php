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
        Schema::create('diem_eos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sinh_vien_id');
            $table->double('diem', 10, 2);
            $table->bigInteger('mon_hoc_id');
            $table->bigInteger('ky_hoc_id');
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
        Schema::dropIfExists('diem_eos');
    }
};
