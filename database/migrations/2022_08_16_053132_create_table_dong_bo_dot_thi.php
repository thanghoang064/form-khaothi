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
        Schema::create('dong_bo_dot_thi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dot_thi_id');
            $table->integer('luot_dong_bo')->default(1);
            $table->string('sheet_id');

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
        Schema::dropIfExists('dong_bo_dot_thi');
    }
};
