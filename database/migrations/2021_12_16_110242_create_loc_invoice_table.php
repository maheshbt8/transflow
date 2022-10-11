<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loc_invoice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no', 60);
            $table->integer('req_id');
            $table->integer('vendor_id')->nullable();
            $table->double('invoicing_amount',10,2)->nullable();
            $table->enum('invoice_status',['partial_payment','full_paid'])->nullable();
            $table->enum('invoice_type',['client','vendor'])->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loc_invoice');
    }
}
