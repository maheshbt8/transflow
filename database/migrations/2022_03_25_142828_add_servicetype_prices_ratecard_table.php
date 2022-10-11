<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServicetypePricesRatecardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_ratecard', function (Blueprint $table) {
            $table->dropColumn('price');
        });
        Schema::table('loc_ratecard', function (Blueprint $table) {
            $table->integer('currency_id')->nullable()->after('servie_id');
            $table->double('word_cost',10,2)->nullable()->after('currency_id');
            $table->double('page_cost',10,2)->nullable()->after('word_cost');
            $table->double('minute_cost',10,2)->nullable()->after('page_cost');
            $table->double('minute_cost_15',10,2)->nullable()->after('page_cost');
            $table->double('minute_cost_30',10,2)->nullable()->after('minute_cost_15');
            $table->double('minute_cost_45',10,2)->nullable()->after('minute_cost_30');
            $table->double('minute_cost_60',10,2)->nullable()->after('minute_cost_45');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_ratecard', function (Blueprint $table) {
           
        });
    }
}
