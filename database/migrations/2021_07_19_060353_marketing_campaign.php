<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MarketingCampaign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('marketing_campaign')) {   
        Schema::create('marketing_campaign', function (Blueprint $table) {
            $table->bigIncrements('mk_campign_id');
            $table->bigInteger('reference_id');
            $table->string('visitor_name',128);
            $table->string('visitor_email',512);
            $table->string('visitor_contact',20);
            $table->string('visitor_organization',512);
            $table->string('source_language',128);
            $table->string('target_language',128);
            $table->text('source_text');
            $table->text('source_file');
            $table->enum('campaign_status',['In-progress','Translation','Review','Completed','Done']);
            $table->enum('email_verification',['0','1']);
            $table->dateTime('link_expiry_date_time');
            $table->dateTime('created_on');
            $table->string('ip_address',32);
            $table->bigInteger('modified_by');
            $table->dateTime('modified_on');
        });
        }
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
