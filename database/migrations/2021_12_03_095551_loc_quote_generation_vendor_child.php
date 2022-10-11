<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocQuoteGenerationVendorChild extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { Schema::create('loc_quote_generation_vendor_child', function (Blueprint $table) {
        $table->bigIncrements('id')->autoIncrement();
        $table->bigInteger('vendor_id')->unsigned()->nullable();
        $table->bigInteger('organization_id')->unsigned();
        $table->text('source_lang_id')->nullable();
        $table->text('target_lang_id')->nullable();
        $table->dateTime('vendor_assigned_date')->nullable();
        $table->enum('vendor_status',['null','25','50','75','100'])->default('null');
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
