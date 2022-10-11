<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LocTranslationQuoteGenerationChild extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loc_translation_qoute_generation_child', function (Blueprint $table) {
            $table->unsignedBigInteger('translation_totalamount')->unsigned();
            $table->bigIncrements('translation_quote_child_id',40)->autoIncrement();
            $table->unsignedBigInteger('translation_quote_id')->unsigned();
            $table->text('translation_quote_type');
            $table->text('translation_description');
            $table->string('translation_count')->nullable();
            $table->string('translation_per_count')->nullable();
            $table->string('translation_fixed_count')->nullable();
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
        Schema::dropIfExists('loc_translation_qoute_generation_child');
    }
}
