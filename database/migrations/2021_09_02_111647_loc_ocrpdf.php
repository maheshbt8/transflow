<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocOcrpdf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('loc_ocrpdf')) {
            Schema::create('loc_ocrpdf', function (Blueprint $table) {
                $table->bigIncrements('kpt_tool_id');
                $table->bigInteger('kpt_reference_code');
                $table->string('org_upload_file_name');
                $table->string('upload_file_name');
                $table->enum('uploaded_status',['In-progress','Done']);
                $table->dateTime('uploaded_date');
                $table->dateTime('completed_date')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        // Schema::dropIfExists('loc_ocrpdf');
         
    }
}
