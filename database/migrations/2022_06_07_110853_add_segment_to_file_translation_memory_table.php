<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSegmentToFileTranslationMemoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_translation_memory', function (Blueprint $table) {
           $table->integer('segment_count')->nullable();
           $table->integer('character_count')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('file_translation_memory', function (Blueprint $table) {
            //
        });
    }
}
