<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocQuoteService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loc_quote_service', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quote_id')->unsigned();
            $table->integer('source_lang')->unsigned();
            $table->string('service_type');
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
