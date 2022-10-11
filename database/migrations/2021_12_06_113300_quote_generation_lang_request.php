<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class QuoteGenerationLangRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_generation_lang_request', function (Blueprint $table) {
         $table->bigIncrements('id')->autoIncrement();
         $table->bigInteger('quote_generation_id')->unsigned();
         $table->bigInteger('language_id')->unsigned();
         $table->enum('language_type',['source','target']);
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
