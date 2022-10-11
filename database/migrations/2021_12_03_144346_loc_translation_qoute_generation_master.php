<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocTranslationQouteGenerationMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_translation_qoute_generation_master', function (Blueprint $table) {
            $table->dropColumn('source_lan');
            $table->dropColumn('target_lan');
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
            $table->text('source_lan');
            $table->text('target_lan');
    });
}
}
