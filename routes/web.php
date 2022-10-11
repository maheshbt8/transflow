<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::redirect('/', 'admin/home');
Route::redirect('/home', 'admin/home');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Auth::routes(['register' => false]);

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

/* API pull request for AEM */
Route::get('aemcreatetranslationjob', 'AEM\KptAEMController@aemcreatetranslationjob');
Route::get('aemtranslationdata', 'AEM\KptAEMController@aemtranslationdata');
Route::get('aemtranslationgetobjectstatus', 'AEM\KptAEMController@aemtranslationgetobjectstatus');

Route::get('aem_request_create_project', 'AEM\KptAEMController@aem_request_create_project');




/* API pull request for AEM */
Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
	Route::get('/home', 'HomeController@index')->name('home');
	Route::resource('permissions', 'Admin\PermissionsController');
	Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy');
	Route::resource('roles', 'Admin\RolesController');
	Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy');
	Route::resource('users', 'Admin\UsersController');
	Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy');

	Route::resource('org', 'KptorganizationController');
	Route::delete('org_mass_destroy', 'KptorganizationController@massDestroy')->name('org.mass_destroy');
	Route::post('bank_details', 'KptorganizationController@bank_details')->name('Kptorganization.bank_details');
    Route::get('edit_bankdetails/{quote_code}', 'KptorganizationController@edit_bankdetails')->name('Kptorganization.edit_bankdetails');
	Route::resource('orgusers', 'Admin\OrgUsersController');
	Route::delete('orgusers_mass_destroy', 'Admin\OrgUsersController@massDestroy')->name('orgusers.mass_destroy');
    Route::post('update_bankdetails/{id}', 'KptorganizationController@update_bankdetails')->name('Kptorganization.update_bankdetails');
   Route::delete('delete_bank', 'KptorganizationController@delete_bank')->name('Kptorganization.delete_bank');
	Route::any('delete_bank/{id}', 'KptorganizationController@delete_bank')->name('Kptorganization.delete_bank');

	Route::resource('suborganizations', 'KptSubOrganizationsController');
	Route::delete('suborg_mass_destroy', 'KptSubOrganizationsController@massDestroy')->name('suborg.mass_destroy');
	Route::post('suborganizations_org', 'KptSubOrganizationsController@suborganizations_org')->name('kptsuborganizations.suborganizations_org');

	Route::resource('departments', 'KptDepartmentsController');
	Route::delete('departments_mass_destroy', 'KptDepartmentsController@massDestroy')->name('departments.mass_destroy');

	Route::post('departments_suborg', 'KptDepartmentsController@departments_suborg')->name('kptdepartments.departments_suborg');

	Route::resource('suborgusers', 'Admin\SubOrgUsersController');
	Route::delete('sub_orgusers_mass_destroy', 'Admin\SubOrgUsersController@massDestroy')->name('suborgusers.mass_destroy');


	Route::resource('departmentusers', 'Admin\DepartmentUsersController');
	Route::delete('departmentusers_mass_destroy', 'Admin\DepartmentUsersController@massDestroy')->name('departmentusers.mass_destroy');

	Route::resource('authenticatedusers', 'Admin\AuthenticatedUsersController');
	Route::delete('authenticatedusers_mass_destroy', 'Admin\AuthenticatedUsersController@massDestroy')->name('authenticatedusers.mass_destroy');

	/*Client Organization*/
	Route::resource('clientorg', 'ClientorganizationController');
	Route::delete('client_org_mass_destroy', 'ClientorganizationController@massDestroy')->name('client_org.mass_destroy');
	Route::resource('clientsuborg', 'ClientSubOrganizationController');
	Route::post('clientsuborganizations_org', 'ClientSubOrganizationController@suborganizations_org')->name('clientsuborganization.clientsuborganizations_org');
	Route::resource('clientdept', 'ClientDepartmentController');
	/*Client Organization End*/
	/* kptaemrequest for KPT PM block */
	Route::resource('kptaemrequest', 'AEM\KptAEMController');

	Route::post('kptaemrequest/assigned_details', 'AEM\KptAEMController@pm_assigned_request_details')->name('kptaemrequest.assigned_details');
	Route::post('kptaemrequest/costcentric_details', 'AEM\KptAEMController@costcentric_details')->name('kptaemrequest.costcentric_details');

	Route::post('kptaemrequest/aem_xmlfiles_request', 'AEM\KptAEMController@aem_xmlfiles_request')->name('kptaemrequest.aem_xmlfiles_request');

	Route::get('kptaemrequest/downloadrequestfile/{refid}', 'AEM\KptAEMController@downloadrequestfile')->name('kptaemrequest.downloadrequestfile');

	Route::get('kptaemrequest/aem_request_translated_download/{refid}/{objectid}', 'AEM\KptAEMController@aem_request_translated_download')->name('kptaemrequest.aem_request_translated_download');

	Route::get('kptaemrequest/aem_request_translated_output/{refid}/{objectid}', 'AEM\KptAEMController@aem_request_translated_output')->name('kptaemrequest.aem_request_translated_output');

	Route::post('kptaemrequest/googletranslate', 'AEM\KptAEMController@Gtranslate')->name('kptaemrequest.googletranslate');


	Route::post('kptaemrequest/autosuggestion', 'AEM\KptAEMController@autosuggestion')->name('kptaemrequest.autosuggestion');



	Route::get('tmgetbysource', 'AEM\KptAEMController@translation_memroy_getbysource')->name('kptaemrequest.translation_memroy_getbysource');

	Route::post('kptadmin_assigned_trasnslator_aem_request', 'AEM\KptAEMController@kptadmin_assigned_trasnslator_aem_request')->name('kptaemrequest.kptadmin_assigned_trasnslator_aem_request');

	Route::post('kptadmin_assigned_kreviewer_aem_request', 'AEM\KptAEMController@kptadmin_assigned_kreviewer_aem_request')->name('kptaemrequest.kptadmin_assigned_kreviewer_aem_request');
	/* kptaemrequest for KPT PM block */


	/* kptaemcpmrequest for Qatar PM block */
	Route::resource('kptaemcpmrequest', 'AEM\KptAEMclientpmController');
	Route::post('kptaemcpmrequest/assigned_view_details', 'AEM\KptAEMclientpmController@assigned_aem_request_details')->name('kptaemcpmrequest.assigned_aem_request_details');


	Route::post('kptaemcpmrequest/aem_xmlfiles_request', 'AEM\KptAEMclientpmController@aem_xmlfiles_request')->name('kptaemcpmrequest.aem_xmlfiles_request');


	Route::get('kptaemcpmrequest/aem_request_translated_output/{refid}/{objectid}', 'AEM\KptAEMclientpmController@aem_request_translated_output')->name('kptaemcpmrequest.aem_request_translated_output');

	Route::post('request_change_aem_status', 'AEM\KptAEMclientpmController@request_change_aem_status')->name('kptaemcpmrequest.request_change_aem_status');
	/* kptaemcpmrequest for Qatar PM block */


	/* kptaemcpmrequest for Qatar client block */
	Route::resource('kptaemqclientrequests', 'AEM\KptAEMqclientController');
	Route::post('assigned_view_details', 'AEM\KptAEMqclientController@assigned_aem_request_details')->name('kptaemqclientrequests.assigned_aem_request_details');

	Route::post('aem_xmlfiles_request', 'AEM\KptAEMqclientController@aem_xmlfiles_request')->name('kptaemqclientrequests.aem_xmlfiles_request');

	Route::get('kptaemqclientrequests/aem_request_translated_download/{refid}/{objectid}', 'AEM\KptAEMqclientController@aem_request_translated_download')->name('kptaemqclientrequests.aem_request_translated_download');


	Route::get('kptaemqclientrequests/aem_request_translated_output/{refid}/{objectid}', 'AEM\KptAEMqclientController@aem_request_translated_output')->name('kptaemqclientrequests.aem_request_translated_output');

	/* kptaemcpmrequest for Qatar client block */


	/* KptAEMtranslatorController for Translator block */
	Route::resource('kptaemtranslatorrequests', 'AEM\KptAEMtranslatorController');

	Route::post('aem_request_details_translator', 'AEM\KptAEMtranslatorController@aem_request_details_translator')->name('kptaemtranslatorrequests.aem_request_details_translator');

	Route::get('kptaemtranslatorrequests/aem_request_translated_download/{refid}/{objectid}', 'AEM\KptAEMtranslatorController@aem_request_translated_download')->name('KptAEMtranslatorController.aem_request_translated_download');

	Route::get('kptaemtranslatorrequests/aem_request_translate/{refid}/{objectid}', 'AEM\KptAEMtranslatorController@aem_request_translate')->name('kptaemtranslatorrequests.aem_request_translate');

	Route::post('aem_request_translation_submit', 'AEM\KptAEMtranslatorController@aem_request_translation_submit')->name('kptaemtranslatorrequests.aem_request_translation_submit');
	/* KptAEMtranslatorController for Translator block */


	/* KptAEMkreviewerController for KPT Reviewer block */
	Route::resource('kptaemkreviewerrequests', 'AEM\KptAEMkreviewerController');

	Route::post('aem_request_details_kreviewer', 'AEM\KptAEMkreviewerController@aem_request_details_kreviewer')->name('kptaemkreviewerrequests.aem_request_details_kreviewer');

	Route::get('kptaemkreviewerrequests/aem_request_translated_download/{refid}/{objectid}', 'AEM\KptAEMkreviewerController@aem_request_translated_download')->name('kptaemkreviewerrequests.aem_request_translated_download');

	Route::get('kptaemkreviewerrequests/aem_request_kpt_proofreading/{refid}/{objectid}', 'AEM\KptAEMkreviewerController@aem_request_kpt_proofreading')->name('kptaemkreviewerrequests.aem_request_kpt_proofreading');

	Route::post('aem_request_kpt_proofreading_submit', 'AEM\KptAEMkreviewerController@aem_request_kpt_proofreading_submit')->name('kptaemkreviewerrequests.aem_request_kpt_proofreading_submit');

	/* KptAEMtranslatorController for KPT Reviewer block */


	/* KptAEMqreviewerController for Qatar Reviewer block */
	Route::resource('kptaemqreviewerrequests', 'AEM\KptAEMqreviewerController');

	Route::post('aem_request_details_qreviewer', 'AEM\KptAEMqreviewerController@aem_request_details_qreviewer')->name('kptaemqreviewerrequests.aem_request_details_qreviewer');

	Route::get('kptaemqreviewerrequests/aem_request_translated_download/{refid}/{objectid}', 'AEM\KptAEMqreviewerController@aem_request_translated_download')->name('kptaemqreviewerrequests.aem_request_translated_download');

	Route::get('kptaemqreviewerrequests/aem_request_qatar_proofreading/{refid}/{objectid}', 'AEM\KptAEMqreviewerController@aem_request_qatar_proofreading')->name('kptaemqreviewerrequests.aem_request_qatar_proofreading');

	Route::post('view_aem_translation_sourcetext', 'AEM\KptAEMqreviewerController@view_aem_translation_sourcetext')->name('kptaemqreviewerrequests.view_aem_translation_sourcetext');

	Route::post('aem_request_qatar_proofreading_submit', 'AEM\KptAEMqreviewerController@aem_request_qatar_proofreading_submit')->name('kptaemqreviewerrequests.aem_request_qatar_proofreading_submit');

	/* KptAEMtranslatorController for Qatar Reviewer block */


	/* Translation Memory CSV files */
	Route::resource('translationcsvfile', 'TMFiles\TranslationCsvFileController');
	Route::get('approve_translations', 'TMFiles\TranslationCsvFileController@approve_translations')->name('translationcsvfile.approve_translations');
	Route::delete('tm_mass_destroy', 'TMFiles\TranslationCsvFileController@massDestroy')->name('translationcsvfile.mass_destroy');
	
	Route::post('tm_approve_data', 'TMFiles\TranslationCsvFileController@tm_approve_data')->name('translationcsvfile.tm_approve_data');
	
	Route::get('csvuploadsubmit/{id}', 'TMFiles\TranslationCsvFileController@csvuploadsubmit')->name('translationcsvfile.csvuploadsubmit');
	Route::post('file_translation_memory', 'TMFiles\TranslationCsvFileController@file_translation_memory')->name('translationcsvfile.file_translation_memory');
	Route::get('projecte_file_translation_memory/{id}', 'TMFiles\TranslationCsvFileController@projecte_file_translation_memory')->name('translationcsvfile.projecte_file_translation_memory');

	Route::get('get_file_data_content/{id}', 'TMFiles\TranslationCsvFileController@get_file_data_content')->name('translationcsvfile.get_file_data_content');
 
     
	Route::post('csvsegmentationformsubmit', 'TMFiles\TranslationCsvFileController@csvsegmentationformsubmit')->name('translationcsvfile.csvsegmentationformsubmit');

	Route::post('gettargettext', 'TMFiles\TranslationCsvFileController@gettargettext')->name('translationcsvfile.gettargettext');
	Route::post('savefiledata', 'TMFiles\TranslationCsvFileController@savefiledata')->name('translationcsvfile.savefiledata');

	Route::post('delete_data', 'TMFiles\TranslationCsvFileController@delete_data')->name('translationcsvfile.delete_data');
	
	/* Translation Memory CSV files */
	/*Marketing Campaign*/
	Route::resource('marketingcampaign', 'Admin\MarketingCampaignController');
	/*Marketing Campaign*/
	/*Request*/
	Route::resource('request', 'Admin\RequestController');
	Route::resource('ocrpdf', 'Admin\OcrpdfController');
	Route::get('ocrpdf/create', 'Admin\OcrpdfController@create')->name('ocrpdf.create');
	Route::post('ocrpdf/upload', 'Admin\OcrpdfController@import_kpt_tool_processing')->name('ocrpdf.upload');
	Route::post('requestcomments', 'Admin\RequestController@comments')->name('request.requestcomments');
	Route::post('todo_requestcomments', 'Admin\RequestController@todo_requestcomments')->name('request.todo_requestcomments');
	Route::get('todoactivities', 'Admin\RequestController@todoactivities')->name('request.todoactivities');
	Route::post('request_change_tm_status', 'Admin\RequestController@request_change_tm_status')->name('request.request_change_tm_status');
	Route::post('request_change_request_status', 'Admin\RequestController@request_change_request_status')->name('request.request_change_request_status');
	Route::post('trrequestupdate', 'Admin\RequestController@trrequestupdate')->name('request.trrequestupdate');
	Route::post('get_request_data', 'Admin\RequestController@get_request_data')->name('request.get_request_data');
	Route::post('get_request_assign_data', 'Admin\RequestController@get_request_assign_data')->name('request.get_request_assign_data');
	Route::get('requestupdate/{refid}', 'Admin\RequestController@requestupdate')->name('request.requestupdate');
	Route::get('assigntotranslator/{refid}', 'Admin\RequestController@assigntotranslator')->name('request.assigntotranslator');
	Route::get('requesttransaction/{refid}', 'Admin\RequestController@requesttransaction')->name('request.requesttransaction');
	Route::get('requestinvoice/{refid}', 'Admin\RequestController@requestinvoice')->name('request.requestinvoice');
	Route::get('requestvendorinvoice/{refid}', 'Admin\RequestController@requestvendorinvoice')->name('request.requestvendorinvoice');
	Route::any('/viewinvoice/{invoice_no}', 'Admin\RequestController@viewinvoice')->name('request.viewinvoice');
	Route::get('editrequest/{refid}', 'Admin\RequestController@editrequest')->name('request.editrequest');
	Route::any('deletefile/{id}', 'Admin\RequestController@deletefile')->name('request.deletefile');
	Route::any('cancel_request/{id}', 'Admin\RequestController@cancel_request')->name('request.cancel_request');
	
	// Route::post('addfield', 'Admin\RequestController@addfield')->name('request.addfield');
	Route::post('getclientinvoicedata', 'Admin\RequestController@getclientinvoicedata')->name('request.getclientinvoicedata');
	Route::post('getvendorinvoicedata', 'Admin\RequestController@getvendorinvoicedata')->name('request.getvendorinvoicedata');
	Route::post('getinvoicedata', 'Admin\RequestController@getinvoicedata')->name('request.getinvoicedata');
	Route::post('submitinvoice', 'Admin\RequestController@submitinvoice')->name('request.submitinvoice');
	Route::post('updateinvoicestatus', 'Admin\RequestController@updateinvoicestatus')->name('request.updateinvoicestatus');
	Route::post('add_comments','Admin\RequestController@add_comments')->name('request.add_comments');
	Route::get('add_comment/{id}/{target}','Admin\RequestController@add_comment')->name('request.add_comment');
	Route::any('deletesourcefiledata/{id}', 'Admin\RequestController@deletesourcefiledata')->name('request.deletesourcefiledata');
	Route::any('deleteassignedfile/{id}', 'Admin\RequestController@deleteassignedfile')->name('request.deleteassignedfile');
	Route::any('/request_add_fields', 'Admin\RequestController@request_add_fields')->name('request.request_add_fields');
	Route::any('createrequest/{id}', 'Admin\RequestController@createrequest')->name('request.createrequest');
	Route::post('bulkassigntr','Admin\RequestController@bulkassigntr')->name('request.bulkassigntr');
	/*currency*/
	Route::resource('currency', 'currency\CurrencyController');
	Route::post('currency/create','currency\CurrencyController@create')->name('curency.create');
	Route::post('currency/store','currency\CurrencyController@store')->name('curency.store');
	Route::any('currency/update/{id}','currency\CurrencyController@update')->name('currency.update');


	/* end currency*/
	Route::resource('languages', 'languages\LanguagesController');
	Route::post('languages/create','languages\LanguagesController@create')->name('languages.create');
	Route::post('languages/store','languages\LanguagesController@store')->name('languages.store');
	Route::any('languages/update/{id}','languages\LanguagesController@update')->name('languages.update');
	/* settings */
	//Route::resource('settings', 'SettingsController@index');
	Route::resource('settings', 'SettingsController');
	Route::post('settings/store','SettingsController@store')->name('settings.store');
	 Route::post('email_update','SettingsController@email_update')->name('settings.email_update');
	 Route::post('smpt_settings','SettingsController@smpt_settings')->name('settings.smpt_settings');
	 Route::post('logo_update','SettingsController@logo_update')->name('settings.logo_update');
	 Route::post('favicon','SettingsController@favicon')->name('settings.favicon');


	//Route::post('settings/store','SettingsController@store')->name('settings.store');
	
	/* end settings */

	/* Terms and condtions */

	Route::get('terms', 'Terms\TermsController@index')->name('terms');
	Route::post('terms/store', 'Terms\TermsController@store')->name('terms.store');
	Route::any('terms/update/{id}', 'Terms\TermsController@update')->name('terms.update');
	/* end Terms and condtions */


	/* loc_rate cards */

	
	
	Route::delete('org_mass_destroy', 'KptorganizationController@massDestroy')->name('org.mass_destroy');
	Route::post('bank_details', 'KptorganizationController@bank_details')->name('Kptorganization.bank_details');


	Route::resource('ratecard', 'Loc_RateController');
	Route::get('sampledownload', 'Loc_RateController@sampledownload')->name('ratecard.sampledownload');
	Route::post('updateratecard', 'Loc_RateController@updateratecard')->name('ratecard.updateratecard');
	//Route::post('store_price', 'Loc_RateController@store_price')->name('ratecard'.'store_price');
	/*  end loc_rate cards */

	/*Request*/
	Route::resource('emailsettings', 'Admin\EmailSettingsController');
	Route::resource('translation', 'Admin\TranslationController');
	Route::get('translationtext', 'Admin\TranslationController@translationtext')->name('translation.translationtext');
	Route::get('translationdoc', 'Admin\TranslationController@translationdoc')->name('translation.translationdoc');
	Route::get('translationslide', 'Admin\TranslationController@translationslide')->name('translation.translationslide');
	Route::get('translationword', 'Admin\TranslationController@translationword')->name('translation.translationword');

	Route::post('curl_translate_language', 'Admin\TranslationController@curl_translate_language')->name('translation.curl_translate_language');
	Route::post('curl_translate_document', 'Admin\TranslationController@curl_translate_document')->name('translation.curl_translate_document');
	Route::post('curl_translate_document', 'Admin\TranslationController@curl_translate_document')->name('translation.curl_translate_document');
	Route::post('curl_translate_document_download', 'Admin\TranslationController@curl_translate_document_download')->name('translation.curl_translate_document_download');


	Route::resource('translationmemory', 'Admin\TranslationmemoryController');
	Route::post('distroy_data', 'Admin\TranslationmemoryController@distroy_data')->name('translationmemory.distroy_data');
	Route::post('update_data', 'Admin\TranslationmemoryController@update_data')->name('translationmemory.update_data');
	Route::get('addtms', 'Admin\TranslationmemoryController@addtms')->name('translationmemory.addtms');
	Route::post('store_tms', 'Admin\TranslationmemoryController@store_tms')->name('translationmemory.store_tms');
	//Route::get('traslation/{id}{ticketid}', 'admin\TraslationController@index');
	Route::resource('client', 'Admin\ClientController');
	
	Route::get('client/create', 'Admin\ClientController@create')->name('client.create');
	Route::delete('client_mass_destroy', 'Admin\ClientController@massDestroy')->name('client.mass_destroy');
	/* romanization */
	Route::get('kptromanization', 'Admin\KptromanizationController@addRomanization')->name('kptromanization.addRomanization');
	Route::post('kptromanizationprocess', 'Admin\KptromanizationController@kptromanizationprocess')->name('kptromanization.kptromanizationprocess');
	Route::resource('quotegeneration', 'Admin\QuotegenerationController');
	Route::get('/export_requests', 'Admin\QuotegenerationController@export_requests');
	Route::any('/editquote/{quote_code}', 'Admin\QuotegenerationController@editquote')->name('quotegeneration.editquote');

	// Route::post('/updateinvoice/{quote_code}', 'Admin\QuotegenerationController@updateInvoice')->name('quotegeneration.updateinvoice');
	Route::post('get_request_quote_assign_data', 'Admin\QuotegenerationController@get_request_quote_assign_data')->name('quotegeneration.get_request_quote_assign_data');
	Route::post('request_change_quote_status', 'Admin\QuotegenerationController@request_change_quote_status')->name('quotegeneration.request_change_quote_status');
	Route::any('request_dynamic_fields', 'Admin\QuotegenerationController@request_dynamic_fields')->name('quotegeneration.request_dynamic_fields');
	Route::post('get_quote_rate', 'Admin\QuotegenerationController@get_quote_rate')->name('quotegeneration.get_quote_rate');
	Route::post('get_client_details', 'Admin\QuotegenerationController@get_client_details')->name('quotegeneration.get_client_details');
	Route::post('change_terms', 'Admin\QuotegenerationController@change_terms')->name('quotegeneration.change_terms');
    Route::any('createquote/{id}', 'Admin\QuotegenerationController@createquote')->name('quotegeneration.createquote');
    Route::get('upload_po', 'Admin\QuotegenerationController@upload_po_order')->name('quotegeneration.upload_po_order');
    Route::post('submit_po', 'Admin\QuotegenerationController@submit_po')->name('quotegeneration.submit_po');

	/* finance details*/
	// Route::resource('finance', 'Admin\financeController@clientdetail');
	Route::get('clientinvoice', 'Admin\financeController@clientinvoice')->name('finance.clientinvoice');
	Route::get('vendorinvoice', 'Admin\financeController@vendorinvoice')->name('finance.vendorinvoice');
	Route::get('get_invoice', 'Admin\financeController@get_invoice')->name('finance.get_invoice');

	


	Route::get('edit/{quote_code}', 'Admin\QuotegenerationController@edit')->name('quotegeneration.edit');
	Route::any('quote_cancel/{id}', 'Admin\QuotegenerationController@quote_cancel')->name('quotegeneration.quote_cancel');
	Route::resource('transflowsamples', 'transflowsamplesController');
	Route::resource('videototext', 'Admin\VideototextController');
	Route::post('srtsegmentationformsubmit', 'Admin\VideototextController@srtsegmentationformsubmit')->name('videototext.srtsegmentationformsubmit');

	Route::resource('speechtospeech', 'Admin\speechtospeechController');

	Route::resource('xliff', 'Admin\xliffController');
	Route::get('xliff/create', 'Admin\xliffController@create')->name('xliff.create');
	// Route::get('transflowsamples', 'transflowsamplesController@videodownload')->name('transflowsamples.videodownload');
	/* romanization */
 
    /*persoanl details */
	//Route::resource('personal', 'personaldetails\personalController');
     //Route::post('bank_details', 'Admin\personalController@bank_details')->name('personal.bank_details');
	//Route::post('bank_details', 'Admin\personalController@bank_details')->name('personal.bank_details');

	Route::resource('personaldetails', 'personaldetails\personalController');
	Route::post('personaldetails/bank_details','personaldetails\personalController@bank_details')->name('personaldetails.bank_details');
	Route::any('personaldetails/delete_bank/{id}', 'personaldetails\personalController@delete_bank')->name('personaldetails.delete_bank');
	Route::get('personaldetails/edit_bankdetails/{quote_code}', 'personaldetails\personalController@edit_bankdetails')->name('personaldetails.edit_bankdetails');
	Route::post('personaldetails/update_bankdetails/{id}', 'personaldetails\personalController@update_bankdetails')->name('personaldetails.update_bankdetails');
	//Route::delete('delete_bank', 'KptorganizationController@delete_bank')->name('Kptorganization.delete_bank');
// Route::any('personaldetails/delete_bank/{id}', 'personaldetails\personalController@delete_bank')->name('personaldetails.delete_bank');
Route::any('/address','personaldetails\personalController@address')->name('personaldetails.address');
Route::post('/add_address','personaldetails\personalController@add_address')->name('personaldetails.add_address');
Route::get('/address/edit_address/{id}','personaldetails\personalController@edit_address')->name('personaldetails.edit_address');
Route::post('/address/update_address/{id}','personaldetails\personalController@update_address')->name('personaldetails.update_address');
Route::any('/address/delete_address/{id}','personaldetails\personalController@delete_address')->name('personaldetails.delete_address');

Route::any('/personal_data','personaldetails\personalController@personal_data')->name('personaldetails.personal_data');
Route::any('/personal_data/add_personal','personaldetails\personalController@add_personal')->name('personaldetails.add_personal');
Route::get('/personal_data/edit_personal/{quote}','personaldetails\personalController@edit_personal')->name('personaldetails.edit_personal');
Route::post('/personal_data/update_personal/{quote}','personaldetails\personalController@update_personal')->name('personaldetails.update_personal');
Route::any('/personal_data/delete_data/{quote}','personaldetails\personalController@delete_data')->name('personaldetails.delete_data');

/* end persoanl details */



});
Route::get('/pdftest', 'Admin\QuotegenerationController@test');



Route::post('/get_quote_request_assign_data', 'Admin\QuotegenerationController@get_quote_request_assign_data')->name('quotegeneration.get_quote_request_assign_data');
Route::any('/generate_page_quote_dynamic_fields', 'Admin\QuotegenerationController@generate_page_quote_dynamic_fields')->name('generate_page_quote_dynamic_fields');
// Route::get('addocrpdf', 'Admin\ocrpdfController@addocrfile')->name('addocrfile');
Route::any('/generate_quote_dynamic_fields_per_minute', 'Admin\QuotegenerationController@generate_quote_dynamic_fields_per_minute')->name('generate_quote_dynamic_fields_per_minute');
Route::any('/generate_quote_dynamic_fields', 'Admin\QuotegenerationController@generate_quote_dynamic_fields')->name('generate_quote_dynamic_fields');


//Crontab setup for Video stiching 
Route::get('/embedvideosubtitles', 'Admin\CronController@videototext');

//Route::any('editquote', 'Admin\CronController@editquote')->name('admin.quotegenration.editquote');





//Route::any('editquote', 'Admin\QuotegenerationController@editquote')->name('admin.quotegenration.editquote');

