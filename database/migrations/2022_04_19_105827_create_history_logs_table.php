<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('main_id')->nullable();
            $table->string('action',60)->nullable();
            $table->longText('message')->nullable();
            $table->string('lookup_table',100)->nullable();
            $table->string('type',60)->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index('id');
            $table->index('main_id');
            $table->index('action');
            $table->index('lookup_table');
            $table->index('type');
            $table->index('created_at');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_logs');
    }
}
