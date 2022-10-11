<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocTargetFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {Schema::create('loc_target_file', function (Blueprint $table) {
            $table->bigIncrements('id')->autoIncrement();
            $table->integer('request_id')->unsigned();
            $table->string('target_file')->nullable();
            $table->integer('req_lang_id')->unsigned();
            $table->integer('req_file_id')->unsigned();
            $table->integer('tr_id')->unsigned()->nullable();
            $table->date('tr_assigned_date')->nullable();
            $table->enum('tr_status',['25', '50', '75', '100'])->nullable();
            $table->integer('qa_id')->unsigned()->nullable();
            $table->date('qa_assigned_date')->nullable();
            $table->enum('qa_status',['25', '50', '75', '100'])->nullable();
            $table->integer('pr_id')->unsigned()->nullable();
            $table->date('pr_assigned_date')->nullable();
            $table->enum('pr_status',['25', '50', '75', '100'])->nullable();
            $table->integer('v_id')->unsigned()->nullable();
            $table->date('v_assigned_date')->nullable();
            $table->smallInteger('work_per')->nullable();
            $table->enum('v_gst',['gst','both','no_gst'])->nullable();
            $table->double('v_gst_amnt',10,2)->nullable();
            $table->double('v_amount',10,2)->nullable();
            $table->double('v_total',10,2)->nullable();
            $table->enum('v_status',['25', '50', '75', '100'])->nullable();
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
