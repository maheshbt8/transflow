<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocQuoteSourcelang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loc_quote_sourcelang', function (Blueprint $table) {
          $table->bigIncrements('id')->autoIncrement();
          $table->integer('quote_id')->unsigned()->nullable();
          $table->integer('request_id')->unsigned()->nullable();
          $table->integer('sourcelang_id')->unsigned();
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
