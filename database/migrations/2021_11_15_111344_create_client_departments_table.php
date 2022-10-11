<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_departments', function (Blueprint $table) {
            $table->bigIncrements('client_dpt_id');
            $table->bigInteger('client_suborg_id');
            $table->string('client_dpt_name');
            $table->boolean('client_dpt_status')->default(1);
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
        Schema::dropIfExists('client_departments');
    }
}
