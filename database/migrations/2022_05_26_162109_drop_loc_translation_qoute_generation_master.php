<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLocTranslationQouteGenerationMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_request', function (Blueprint $table) {
            $table->dropColumn('request_status');

           
        });


        Schema::table('loc_request', function (Blueprint $table) {
           
            $table->enum('request_status',['new','approve','tr_assign','tr_inprogress','tr_completed','v_assign','v_inprogress','v_completed','qa_assign','qa_inprogress','qa_reject','qa_accept','pr_assign','pr_inprogress','pr_reject','pr_accept','publish','re_reject','re_accept','pm_reject','client_cancel','cancel']);

          
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
