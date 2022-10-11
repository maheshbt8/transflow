<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GstEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_invoice', function (Blueprint $table) {
           $table->enum('gst_type',['igst','no_gst','both'])->after('invoicing_amount');
           $table->date('invoice_date')->after('gst_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_invoice', function (Blueprint $table) {
            //
        });
    }
}
