<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('loc_request')) {
        Schema::create('loc_request', function (Blueprint $table) {
                    $table->bigIncrements('req_id');
                    $table->integer('user_id');
                    $table->string('reference_id', 128);
                    $table->string('project_name', 45);
                    $table->text('brief_description')->nullable();
                    $table->string('source_type', 45);
                    $table->text('source_text');
                    $table->text('source_file_path');
                    $table->text('special_instructions');
                    $table->enum('request_status', ['New','Assigned','25% Completed','50% Completed','75% Completed','100%']);
                    $table->enum('priority', ['Low', 'Medium', 'High']);
                    $table->date('request_date')->nullable();
                    $table->string('category', 45); 
                    $table->string('request_country', 45)->nullable(); 
                    $table->integer('no_words')->nullable();
                    $table->integer('no_pages')->nullable();
                    $table->string('request_task',32)->nullable();
                    $table->enum('tm_status',['0', '25', '50', '75', '100']);
                    $table->dateTime('created_time');
                    $table->index('reference_id');
                    $table->index('no_words');
                    $table->index('no_pages');
                    $table->index('request_task');
                    $table->index('tm_status');
        });
        }
        if (!Schema::hasTable('loc_request_assigned_linguist')) {
        Schema::create('loc_request_assigned_linguist', function (Blueprint $table) {
                    $table->string('reference_id', 64);
                    $table->integer('user_id');
        });
        }
        if (!Schema::hasTable('loc_request_lang')) {
        Schema::create('loc_request_lang', function (Blueprint $table) {
                    $table->string('reference_id', 64);
                    $table->integer('source_lang');
                    $table->integer('target_lang');
        });
        }
        if (!Schema::hasTable('loc_request_linguist_comments')) {
        Schema::create('loc_request_linguist_comments', function (Blueprint $table) {
                    $table->string('reference_id', 32);
                    $table->string('request_status',32);
                    $table->text('request_comments');
                    $table->integer('created_by');
                    $table->dateTime('created_on');
        });
        }
        if (!Schema::hasTable('loc_request_multiple_files')) {
        Schema::create('loc_request_multiple_files', function (Blueprint $table) {
                    $table->bigInteger('reference_id', 16);
                    $table->text('source_file_name1')->nullable();
                    $table->text('source_file_name2')->nullable();
                    $table->text('source_file_name3')->nullable();
                    $table->text('source_file_name4')->nullable();
        });
        }
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
