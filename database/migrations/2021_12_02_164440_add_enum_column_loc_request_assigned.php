<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnumColumnLocRequestAssigned extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_request_assigned', function (Blueprint $table) {
            $table->enum('tr_status',['Null','25','50','75','100'])->default('Null');
            $table->enum('qa_status',['Null','25','50','75','100'])->default('Null');
            $table->enum('pr_status',['Null','25','50','75','100'])->default('Null');
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
