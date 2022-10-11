<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnVAmountGsntype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_request_assigned', function (Blueprint $table) {
            $table->enum('v_gst',['gst','sgst','cgst','no_gst'])->after('v_assigned_date');
            $table->double('v_gst_amnt',10,2)->nullable()->after('v_gst');
            $table->double('v_amount',10,2)->nullable()->after('v_gst_amnt');
            $table->double('v_total',10,2)->nullable()->after('v_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_request_assigned', function (Blueprint $table) {
            //
        });
    }
}
