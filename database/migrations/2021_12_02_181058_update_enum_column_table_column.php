<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEnumColumnTableColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_translation_qoute_generation_master', function (Blueprint $table) {
            $table->enum('translation_status', ['Open','Invoiced','assign_vendor','Billed','Paid','Assign','Delivered','Cancel']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_translation_qoute_generation_master', function (Blueprint $table) {
            //
        });
    }
}
