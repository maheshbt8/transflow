<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class KptDepartments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('kptdepartments', function (Blueprint $table) {
            $table->bigIncrements('department_id');
            $table->bigInteger('sub_org_id');
            $table->string('department_name');
            $table->boolean('department_status')->default(1);
            $table->integer('created_by')->unsigned();
            $table->timestamps();
        });
		
		
		/* AEM Tables start here */		
		Schema::create('aem_request', function (Blueprint $table) {
					$table->bigIncrements('aem_req_id');
					$table->string('aem_reference_code', 16);
					$table->string('original_aem_job_id', 256);
					$table->string('aem_project_name', 256);
					$table->text('aem_source_filename');
					$table->text('aem_original_filename');
					$table->enum('aem_project_status', [ 'New','Accepted','Approved for Translation','Assigned','Translation in-Progress','KPT Translation Completed','Translation Completed','Review','KPT Review Failed','KPT Review Completed','Confirmed Translation Completed','Assigned Qatar Review','Qatar Review Failed','Qatar Review Success','Completed','READY_FOR_REVIEW']);
					$table->integer('no_words')->unsigned();
					$table->integer('no_duplicate_words')->unsigned();
					$table->date('delivery_date');
					$table->integer('user_id')->unsigned();
					$table->dateTime('created_on');
					$table->string('aem_translation_type', 128); 
					$table->index('aem_reference_code');
					$table->index('original_aem_job_id');
					$table->index('aem_project_name');
					$table->index('aem_project_status');
					$table->index('user_id');
					$table->index('aem_translation_type');
        });	
		
		
		Schema::create('aem_request_assigned_clientpm', function (Blueprint $table) {
					$table->string('aem_reference_code', 16);			
					$table->integer('client_pm_user_id')->unsigned();
					$table->index('aem_reference_code');
					$table->index('client_pm_user_id');
        });		
		
		Schema::create('aem_request_assigned_kreviewer', function (Blueprint $table) {
					$table->string('aem_reference_code', 16);			
					$table->integer('reviewer_user_id')->unsigned();
					$table->index('aem_reference_code');
					$table->index('reviewer_user_id');
        });		
		
		Schema::create('aem_request_assigned_qreviewer', function (Blueprint $table) {
					$table->string('aem_reference_code', 16);			
					$table->integer('reviewer_user_id')->unsigned();
					$table->index('aem_reference_code');
					$table->index('reviewer_user_id');
        });
		
		Schema::create('aem_request_assigned_translator', function (Blueprint $table) {
					$table->string('aem_reference_code', 16);			
					$table->integer('translator_user_id')->unsigned();
					$table->index('aem_reference_code');
					$table->index('translator_user_id');
        });		
		
		Schema::create('aem_request_xml_files', function (Blueprint $table) {
					$table->bigIncrements('xml_file_id');
					$table->string('aem_reference_code', 16);
					$table->longText('xml_file');
					$table->string('aem_object_id', 256);
					$table->text('original_aem_object_id');
					$table->string('mime_type',32);
					$table->string('aem_xml_status',64);			
					$table->dateTime('created_on');
					$table->index('aem_reference_code');
					$table->index('aem_object_id');
        });			
		
		Schema::create('aem_request_languages', function (Blueprint $table) {            
					$table->string('aem_reference_code', 16);
					$table->string('aem_source_language', 32);
					$table->string('aem_target_language', 32);
					$table->enum('translation_status', [ 'New','Accepted','Approved for Translation','Assigned','Translation in-Progress','Translation Completed','Review','KPT Review Failed','KPT Review Completed','Assigned Qatar Review','Qatar Review Failed','Qatar Review Success','Completed'])->default('New');
					$table->Double('per_word_cost', 20,0);
					$table->integer('unique_words')->unsigned();
					$table->integer('duplicate_words')->unsigned();
					$table->float('total_unique_words_cost');
					$table->float('total_duplicate_words_cost');
					$table->index('aem_reference_code');
					$table->index('translation_status');
			});			
			
		Schema::create('aem_request_translation', function (Blueprint $table) {
					$table->bigIncrements('aem_translation_id');
					$table->string('aem_reference_code', 16);
					$table->string('aem_object_id', 16);
					$table->string('source_language', 16);
					$table->string('target_language', 16);
					$table->text('source_text');
					$table->text('target_text');
					$table->string('translate_status', 64);
					$table->text('translator_comments');
					$table->integer('translated_user_id')->unsigned();
					$table->dateTime('translated_created_on');	
					$table->dateTime('translated_modified_on');	
					$table->enum('kpt_reviewer_status', [ '0','1'])->default('1');
					$table->text('kpt_reviewer_comments');
					$table->integer('reviewer_user_id')->unsigned();
					$table->dateTime('reviewer_created_on');	
					$table->dateTime('reviewer_modified_on');
					$table->enum('qatar_reviewer_status', [ '0','1'])->default('1');
					$table->text('qatar_reviewer_comments');
					$table->integer('qatar_reviewer_user_id')->unsigned();
					$table->dateTime('qatar_reviewer_created_on');	
					$table->dateTime('qatar_reviewer_modified_on');
					$table->index('aem_reference_code');
					$table->index('translate_status');
					$table->index('translated_user_id');
					$table->index('reviewer_user_id');
					$table->index('qatar_reviewer_user_id');
        });		
		
		
		Schema::create('aem_request_target_xml_file', function (Blueprint $table) {
					$table->bigIncrements('aem_target_xml_id');
					$table->string('aem_reference_code', 16);
					$table->string('aem_object_code', 32);
					$table->longText('xml_file_data');
					$table->index('aem_reference_code');
					
        });	

		Schema::create('aem_response_metadata_child_details', function (Blueprint $table) {
					$table->bigIncrements('metadata_child_id');
					$table->string('aem_job_id', 16);
					$table->string('original_aem_job_id',256);
					$table->string('object_job_id', 256);
					$table->longText('original_aem_object_id');
					$table->string('aem_mime_type', 32);
					$table->longText('content_data');
					$table->dateTime('created_on');	
					$table->index('original_aem_job_id');
					$table->index('object_job_id');
        });	
		
		
		Schema::create('aem_response_metadata_master', function (Blueprint $table) {
					$table->bigIncrements('metadata_master_id');
					$table->string('aem_job_id', 256);
					$table->string('original_aem_job_id', 256);
					$table->string('project_name', 256);
					$table->text('project_desc');
					$table->string('source_language', 16);
					$table->string('target_language', 16);
					$table->dateTime('due_date');
					$table->text('job_meta');
					$table->string('translation_type', 128);
					$table->dateTime('created_on');
					$table->index('aem_job_id');
					$table->index('project_name');												         
        });			
		/* AEM Tables end here */	
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kptdepartments');
		Schema::dropIfExists('aem_request');
		Schema::dropIfExists('aem_request_assigned_clientpm');
		Schema::dropIfExists('aem_request_assigned_kreviewer');
		Schema::dropIfExists('aem_request_assigned_qreviewer');
		Schema::dropIfExists('aem_request_assigned_translator');
		Schema::dropIfExists('aem_request_xml_files');
		Schema::dropIfExists('aem_request_languages');
		Schema::dropIfExists('aem_request_translation');
		Schema::dropIfExists('aem_request_target_xml_file');
		Schema::dropIfExists('aem_response_metadata_child_details');
		Schema::dropIfExists('aem_response_metadata_master');		
		
    }
}
