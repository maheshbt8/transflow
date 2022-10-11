<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocEmailSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('loc_email_settings')) {
        Schema::create('loc_email_settings', function (Blueprint $table) {
                    $table->increments('email_setting_id');
                    $table->string('email_template',130);
                    $table->text('email_subject');
                    $table->text('email_to_address');
                    $table->text('email_cc_address')->nullable();
                    $table->text('email_bcc_address')->nullable();
                    $table->index('email_template');
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
