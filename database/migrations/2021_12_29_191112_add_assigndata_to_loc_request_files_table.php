<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssigndataToLocRequestFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_comment_child', function (Blueprint $table) {
            $table->integer('tr_id')->unsigned()->nullable();
            $table->date('tr_assigned_date')->nullable();
            $table->string('tr_status')->nullable();
            $table->integer('qa_id')->unsigned()->nullable();
            $table->date('qa_assigned_date')->nullable();
            $table->string('qa_status')->nullable();
            $table->integer('pr_id')->unsigned()->nullable();
            $table->date('pr_assigned_date')->nullable();
            $table->string('pr_status')->nullable();
            $table->integer('v_id')->unsigned()->nullable();
            $table->date('v_assigned_date')->nullable();
            $table->smallInteger('work_per')->nullable();
            $table->enum('v_gst',['gst','both','no_gst'])->nullable();
            $table->double('v_gst_amnt',10,2)->nullable();
            $table->double('v_amount',10,2)->nullable();
            $table->double('v_total',10,2)->nullable();
            $table->string('v_status')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_comment_child', function (Blueprint $table) {
            
        });
    }
}
