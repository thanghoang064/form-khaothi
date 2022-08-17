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
        Schema::table('lop_dot_thi', function (Blueprint $table) {
            $table->renameColumn('bo_mon_id', 'dot_thi_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lop_dot_thi', function (Blueprint $table) {
            $table->renameColumn('dot_thi_id', 'bo_mon_id');
        });
    }
};
