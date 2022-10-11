<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewUpdatedVarcharDigitsQuoteGenerationChild extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_translation_qoute_generation_child', function (Blueprint $table) {
            $table->string('translation_count',20)->nullable();
            $table->string('translation_per_count',20)->nullable();
            $table->string('translation_fixed_count',20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_translation_qoute_generation_child', function (Blueprint $table) {
            //
        });
    }
}
