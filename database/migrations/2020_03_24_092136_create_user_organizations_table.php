<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_orgizations', function (Blueprint $table) {
            $table->bigIncrements('user_org_id');
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
        Schema::dropIfExists('user_orgizations');
    }
}
