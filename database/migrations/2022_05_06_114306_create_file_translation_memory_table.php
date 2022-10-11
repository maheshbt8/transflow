<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileTranslationMemoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_translation_memory', function (Blueprint $table) {
            $table->bigIncrements('id')->autoIncrement();
            $table->integer('domain_id')->nullable();
            $table->string('project_name');
            $table->integer('source_lang_id');
            $table->integer('target_lang_id');
            $table->string('files_name');
            $table->string('translation_files_name');
           
            $table->string('word_count')->nullable();
            $table->string('repeated_words')->nullable();
            $table->integer('org_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_translation_memory');
    }
}
