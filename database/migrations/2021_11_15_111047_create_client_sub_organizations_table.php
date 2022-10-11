<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientSubOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_sub_organizations', function (Blueprint $table) {
            $table->bigIncrements('client_suborg_id');
            $table->bigInteger('client_org_id');
            $table->string('client_suborg_name');
            $table->boolean('client_suborg_status')->default(1);
            $table->integer('created_by')->unsigned();
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
        Schema::dropIfExists('client_sub_organizations');
    }
}
