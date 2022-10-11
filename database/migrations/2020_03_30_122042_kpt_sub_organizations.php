<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KptSubOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('kptsuborganizations', function (Blueprint $table) {
            $table->bigIncrements('sub_org_id');
            $table->bigInteger('org_id');
            $table->string('sub_org_name');
            $table->boolean('sub_org_status')->default(1);
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
        Schema::dropIfExists('kptsuborganizations');
    }
}
