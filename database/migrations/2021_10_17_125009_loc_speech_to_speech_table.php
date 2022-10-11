<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocSpeechToSpeechTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loc_speech_to_speech_table', function (Blueprint $table) {
            $table->bigIncrements('job_id');
            $table->string('job_media_name')->nullable();
            $table->string('job_source_language');
            $table->string('job_target_language');
            $table->text('job_org_filename')->nullable();
            $table->text('job_video_media_url')->nullable();
            $table->enum('job_status',['In-Progress','Failed','Completed']);
            $table->datetime('job_created_at');
            $table->unsignedBigInteger('job_user_id');
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
        Schema::dropIfExists('loc_speech_to_speech_table'); 
    }
}
