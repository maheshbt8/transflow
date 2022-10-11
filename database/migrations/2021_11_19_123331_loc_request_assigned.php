<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocRequestAssigned extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loc_request_assigned', function (Blueprint $table) {
            $table->bigIncrements('id')->autoIncrement();
            $table->bigInteger('request_id')->unsigned();
            $table->text('source_language');
            $table->text('target_language');
            $table->bigInteger('tr_id')->unsigned()->nullable();
            $table->date('tr_assigned_date')->nullable();
            $table->bigInteger('qa_id')->unsigned()->nullable();
            $table->date('qa_assigned_date')->nullable();
            $table->bigInteger('pr_id')->unsigned()->nullable();
            $table->date('pr_assigned_date')->nullable();
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
        //
    }
}
