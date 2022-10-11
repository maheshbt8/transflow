<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsertypeOrgUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type',['individual','company','international'])->default('individual')->after("mobile");
        });
        Schema::table('clientorganizations', function (Blueprint $table) {
            $table->enum('user_type',['individual','company','international'])->default('individual')->after("org_status");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
        Schema::table('clientorganizations', function (Blueprint $table) {
            //
        });
    }
}
