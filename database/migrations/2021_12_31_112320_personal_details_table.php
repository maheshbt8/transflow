<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PersonalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      
        if (!Schema::hasTable('personal_details')) {
            Schema::create('personal_details', function (Blueprint $table) {
                $table->bigIncrements('id')->autoIncrement();
                $table->string('gst',60)->nullable();
                $table->string('pan',60);
                $table->string('state_code',60);
               
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
        Schema::table('personal_details', function (Blueprint $table) {
            //
        });
    }
}
