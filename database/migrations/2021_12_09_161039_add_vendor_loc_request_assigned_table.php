<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorLocRequestAssignedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_request_assigned', function (Blueprint $table) {
            $table->bigInteger('v_id')->unsigned()->nullable();
            $table->smallInteger('work_per')->unsigned()->nullable();
            $table->date('v_assigned_date')->nullable();
            $table->enum('v_status',['Null','25','50','75','100'])->default('Null');
            $table->double('amount',10,2)->nullable();
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
