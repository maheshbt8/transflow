<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostLocRequestAssignedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_request_assigned', function (Blueprint $table) {
            $table->integer('quote_id')->nullable();
            $table->bigInteger('word_count')->unsigned()->nullable();
            $table->double('per_word_cost',10,2)->nullable();
            $table->double('word_fixed_cost',10,2)->nullable();
            $table->bigInteger('page_count')->unsigned()->nullable();
            $table->double('per_page_cost',10,2)->nullable();
            $table->double('page_fixed_cost',10,2)->nullable();
            $table->bigInteger('minute_count')->unsigned()->nullable();
            $table->double('per_minute_cost',10,2)->nullable();
            $table->double('minute_fixed_cost',10,2)->nullable();
            $table->double('total',10,2)->nullable();
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
