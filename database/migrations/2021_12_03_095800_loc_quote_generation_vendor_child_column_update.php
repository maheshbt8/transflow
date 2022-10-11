<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocQuoteGenerationVendorChildColumnUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_quote_generation_vendor_child', function (Blueprint $table) {
            $table->bigInteger('quote_child_id')->unsigned();
            $table->bigInteger('pm_id')->unsigned()->nullable();
            $table->dateTime('pm_assigned_date')->nullable();                     
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_quote_generation_vendor_child', function (Blueprint $table) {
            //
        });
    }
}
