<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocCommentChild extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { Schema::create('loc_comment_child', function (Blueprint $table) {
        $table->bigIncrements('id')->autoIncrement();
        $table->bigInteger('request_id')->unsigned();
        $table->bigInteger('comment_id')->unsigned();
        $table->string('target_file')->nullable();
        $table->string('target_text')->nullable();
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
