<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpadteQuoteTypeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_translation_qoute_generation_master', function (Blueprint $table) {
            $table->enum('quote_type',['quote','sample']);
            $table->double('total_amount',10,2)->nullable();
            $table->integer('gst')->nullable()->unsigned();
            $table->double('grand_total',10,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_translation_qoute_generation_master', function (Blueprint $table) {
            //
        });
    }
}
