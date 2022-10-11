<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateclientOrgizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_user_orgizations', function (Blueprint $table) {
            $table->bigIncrements('client_org_id');
            $table->integer('user_id')->unsigned;
            $table->integer('org_id')->unsigned();
            $table->integer('sub_id')->unsigned()->default(0);
            $table->integer('sub_sub_id')->unsigned()->default(0);
            $table->timestamps();
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
