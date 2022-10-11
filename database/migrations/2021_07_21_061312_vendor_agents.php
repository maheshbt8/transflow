<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VendorAgents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('marketing_campaign')) {
        Schema::create('vendor_agents', function (Blueprint $table) {
            $table->bigIncrements('vendor_agent_id');
            $table->string('venor_agent_name',256);
            $table->string('vendor_agent_code',32);
            $table->enum('vendor_agent_status',['0','1'])->default('1');
        });
        }
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
