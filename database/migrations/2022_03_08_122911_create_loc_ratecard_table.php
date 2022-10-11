<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocRatecardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loc_ratecard', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('source_lang');
            $table->integer('target_lang');
            $table->double('price',10,2)->nullable();
            $table->integer('servie_id');
            $table->integer('updated_by');
            $table->integer('org_id');
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
        Schema::dropIfExists('loc_ratecard');
    }
}
