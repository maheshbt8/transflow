<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FileTempSourceTextClear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('file_temp', function (Blueprint $table) {
            $table->dropColumn('source_text');
            $table->dropColumn('target_text');
        });

        Schema::table('file_temp', function (Blueprint $table) {           
            $table->longText('source_text')->after('file_tr_id');
            $table->longText('target_text')->after('source_text');
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
