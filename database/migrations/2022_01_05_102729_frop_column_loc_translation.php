<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FropColumnLocTranslation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loc_translation_qoute_generation_master', function (Blueprint $table) {
            $table->dropColumn(['kpt_invoice_number','kpt_invoice_date','kpt_total_amount','vendor_invoice_number','vendor_invoice_date','vendor_total_amount','invoice_source_file','invoice_target_file','payment_invoice_number','payment_invoice_date','translation_created_on','translation_modified_on','translation_modified_user_id']); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loc_translation_qoute_generation_master', function (Blueprint $table) {
            //
        });
    }
}
