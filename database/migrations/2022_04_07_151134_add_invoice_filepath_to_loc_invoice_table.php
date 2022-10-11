<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceFilepathToLocInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_invoice', function (Blueprint $table) {
            $table->string('invoice_file_path')->nullable()->after('invoicing_total');
        });
        Schema::table('loc_invoice', function (Blueprint $table) {
            $table->string('po_file_path')->nullable()->after('invoice_file_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_invoice', function (Blueprint $table) {
            //
        });
    }
}
