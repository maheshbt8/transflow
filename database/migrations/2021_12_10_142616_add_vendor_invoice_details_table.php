<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVendorInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vendor_invoice_details')) {       
            Schema::create('vendor_invoice_details', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_id');
                $table->string('invoice_id')->nullable();
                $table->integer('req_id')->nullable();
                $table->string('desc')->nullable();
                $table->double('pending',10,2)->nullable();
                $table->double('paid',10,2)->nullable();
                $table->integer('created_by')->default(1);
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
        //
    }
}
