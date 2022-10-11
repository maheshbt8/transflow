<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocVideototextJob extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loc_videototext_job', function (Blueprint $table) {
            $table->bigIncrements('job_id');
            $table->string('job_name');
            $table->string('job_source_language');
            $table->string('job_media_format');
            $table->string('job_media_bucket');
            $table->text('job_audio_media_url')->nullable();
            $table->text('job_video_media_url');
            $table->enum('job_status',['inprogress','completed','failed']);
            $table->string('job_output_json_url')->nullable();
            $table->unsignedBigInteger('jtxt_flag_status');
            $table->string('target_language');
            $table->string('job_created_at');
            $table->unsignedBigInteger('job_user_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loc_videototext_job');
    }
}
