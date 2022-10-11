<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEnumLocRequestAssigned extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_request_assigned', function (Blueprint $table) {
            $table->enum('tr_status',['25','50','75','100']);
            $table->enum('qa_status',['25','50','75','100']);
            $table->enum('pr_status',['25','50','75','100']);
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
