<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnitCostToLocTargetFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_target_file', function (Blueprint $table) {
            $table->integer('unit_count')->nullable();
        });
        Schema::table('loc_target_file', function (Blueprint $table) {
            $table->double('per_unit',10,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_target_file', function (Blueprint $table) {
            //
        });
    }
}
