<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('address')) {
            Schema::create('address', function (Blueprint $table) {
                $table->bigIncrements('id')->autoIncrement();
                 $table->string('address',250);
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
        Schema::dropIfExists('address');
    }
}
