<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropEmailCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_email_settings', function (Blueprint $table) {
            $table->dropColumn(['email_code','email_template','email_subject']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_email_settings', function (Blueprint $table) {
            $table->string('email_code');
            $table->string('email_template');
            $table->string('email_subject');
        });
    }
}
