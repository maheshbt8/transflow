<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('bank_details')) {
        Schema::create('bank_details', function (Blueprint $table) {
            $table->bigIncrements('id')->autoIncrement();
            $table->string('bank_name',60);
            $table->string('bank_address',250);
            $table->string('account_name',60);
            $table->string('account_number',60);
            $table->string('routing_number',40)->nullable();
            $table->string('ifsc_code',40)->nullable();
            $table->string('swift_code',60)->nullable();
            $table->string('sort_code',60)->nullable();
            $table->string('bic',60)->nullable();
            $table->integer('user_id');
            $table->enum('type',['client_org','org','user']);
            $table->timestamps();
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
        Schema::dropIfExists('bank_details');
    }
}
