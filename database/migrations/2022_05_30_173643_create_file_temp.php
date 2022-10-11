<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_temp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('file_tr_id')->nullable();
            $table->string('source_text');
            $table->string('target_text');
            $table->integer('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index('file_tr_id');
            $table->index('source_text');
            $table->index('target_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_temp');
    }
}
