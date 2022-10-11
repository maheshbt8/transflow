<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnLocRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_request', function (Blueprint $table) {
            $table->bigInteger('client_org_id')->unsigned();
            $table->enum('request_status',['new','approve','tr_assign','tr_inprogress','tr_completed','qa_assign','qa_inprogress','qa_reject','qa_accept','pr_assign','pr_inprogress','pr_reject','pr_accept','publish','re_reject','re_accept']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_request', function (Blueprint $table) {
            //
        });
    }
}
