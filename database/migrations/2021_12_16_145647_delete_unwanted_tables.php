<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteUnwantedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('loc_request_assigned_linguist');
        Schema::dropIfExists('loc_quote_generation_vendor_child');
        Schema::dropIfExists('loc_request_lang');
        Schema::dropIfExists('loc_request_linguist_comments');
        Schema::dropIfExists('loc_request_multiple_files');
        Schema::dropIfExists('loc_translation_qoute_generation_child');
        Schema::dropIfExists('loc_update_request');
        Schema::dropIfExists('quote_generation_lang_request');
        Schema::dropIfExists('quote_generation_request_type');
        Schema::dropIfExists('tm_domains');
        Schema::dropIfExists('vendor_agents');
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
