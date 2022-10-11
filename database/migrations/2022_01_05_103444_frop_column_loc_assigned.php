<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FropColumnLocAssigned extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_request_assigned', function (Blueprint $table) {
            $table->dropColumn(['tr_id','tr_assigned_date','work_per','v_gst','v_gst_amnt','v_amount','v_total','v_assigned_date','qa_id','qa_assigned_date','pr_id','pr_assigned_date','v_id','tr_status','v_status','qa_status','pr_status']);
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
