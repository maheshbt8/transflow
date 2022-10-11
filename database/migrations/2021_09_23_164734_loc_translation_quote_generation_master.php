<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocTranslationQuoteGenerationMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('loc_translation_qoute_generation_master')) {
        Schema::create('loc_translation_qoute_generation_master', function (Blueprint $table) {
            $table->bigIncrements('translation_quote_id',256);
            $table->string('quote_code');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('mob_number');
            $table->string('organization');
            $table->text('type_request');
            $table->text('source_lan');
            $table->text('target_lan');
            $table->string('kpt_invoice_number')->nullable();
            $table->dateTime('kpt_invoice_date')->nullable();
            $table->string('kpt_total_amount')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('vendor_invoice_number')->nullable();
            $table->dateTime('vendor_invoice_date')->nullable();
            $table->string('vendor_total_amount')->nullable();
            $table->text('invoice_source_file')->nullable();
            $table->text('invoice_target_file');
            $table->dateTime('translation_quote_date');
            $table->text('translation_quote_address');
            $table->text('translation_quote_subject');
            $table->text('translation_quote_termuse');
            $table->enum('translation_quote_gst', ['yes', 'no']);
            $table->string('translation_quote_currency');
            $table->enum('payment_type', ['full', 'partial'])->nullable();
            $table->string('payment_invoice_number')->nullable();
            $table->dateTime('payment_invoice_date')->nullable();
            $table->enum('translation_status', ['Open','Invoiced','Billed','Paid','Assign','Delivered','Cancel']);
            $table->dateTime('translation_created_on')->nullable();
            $table->unsignedBigInteger('translation_user_id')->unsigned();
            $table->dateTime('translation_modified_on')->nullable();
            $table->unsignedBigInteger('translation_modified_user_id')->unsigned();
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
        Schema::dropIfExists('loc_translation_qoute_generation_master');
    }
}
