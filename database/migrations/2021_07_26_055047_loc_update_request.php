<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocUpdateRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('loc_update_request')) {
        Schema::create('loc_update_request', function (Blueprint $table) {
                $table->increments('updated_request_id');
                $table->string('reference_id',128);
                $table->integer('user_id');
                $table->enum('source_type', ['File', 'Text'])->nullable();
                $table->text('source_file_path')->nullable();
                $table->text('source_text')->nullable();
                $table->text('special_instructions');
                $table->dateTime('created_time');
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
