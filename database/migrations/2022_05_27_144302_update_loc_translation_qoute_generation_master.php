<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLocTranslationQouteGenerationMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('loc_translation_qoute_generation_master', function (Blueprint $table) {
            $table->DropColumn(['client_amnt_status']);
            $table->DropColumn(['vendor_amnt_status']);
        });
        Schema::table('loc_translation_qoute_generation_master', function (Blueprint $table) {
        $table->enum('client_amnt_status',['booked','billed','paid','cancel'])->default('booked')->after('translation_status');
        });
        Schema::table('loc_translation_qoute_generation_master', function (Blueprint $table) {
            $table->enum('vendor_amnt_status',['booked','billed','paid','cancel'])->default('booked')->after('translation_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
