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
        Schema::create('ca_dot_thi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('dot_thi_id');
            $table->bigInteger('ca_thi_id');
            $table->bigInteger('lop_dot_thi_id');
            $table->date('ngay_thi');
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
        Schema::dropIfExists('ca_dot_thi');
    }
};
