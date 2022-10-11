<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocLanguages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('loc_languages')) {
        Schema::create('loc_languages', function (Blueprint $table) {
                    $table->increments('lang_id');
                    $table->string('lang_code',10);
                    $table->string('lang_name',64);
                    $table->string('lang_status',10);
                    $table->index('lang_code');
                    $table->index('lang_name');
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
