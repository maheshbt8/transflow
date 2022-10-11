<?php

use Illuminate\Database\Seeder;
use App\EmailSettings;

class EmailSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=array(
    		[
            'email_code' => 'create_request',
            'email_template' => 'Create request',
            'email_subject' => '[Testing] - New Translation Request Created',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ],[
	            'email_code' => 'edit_request',
	            'email_template' => 'Edit Request',
	            'email_subject' => '[Testing] - Translation Request has been modified',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ],[
	            'email_code' => 'status_new',
	            'email_template' => 'Status New',
	            'email_subject' => '[Testing] - New Request -',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ],[
	            'email_code' => 'status_assigned',
	            'email_template' => 'Status Assigned',
	            'email_subject' => '[Testing] - New Request -',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ],[
	            'email_code' => 'status_25',
	            'email_template' => 'Status 25% Completed',
	            'email_subject' => '[Testing] - Alert: Translation Progress',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ],[
	            'email_code' => 'status_50',
	            'email_template' => 'Status 50% Completed',
	            'email_subject' => '[Testing] - Alert: Translation Progress - 50% Completed',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ],[
	            'email_code' => 'status_75',
	            'email_template' => 'Status 75% Completed',
	            'email_subject' => '[Testing] - Alert: Translation Progress - 75% Completed',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ],[
	            'email_code' => 'status_100',
	            'email_template' => 'Status 100% Completed',
	            'email_subject' => '[Testing] - Alert: Translation Progress - 100% Completed',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ],[
	            'email_code' => 'linguist_comments',
	            'email_template' => 'Linguist Comments',
	            'email_subject' => 'Subject here',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ],[
	            'email_code' => 'analysis_progress',
	            'email_template' => 'Analysis in Progress',
	            'email_subject' => '[Testing] - Linguist Started Work - Analysis in Progress',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ],[
	            'email_code' => 'translation_progress',
	            'email_template' => 'Translation in Progress',
	            'email_subject' => '[Testing] - Linguist Started Work - Translation in Progress',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ],[
	            'email_code' => 'proofreading_complete',
	            'email_template' => 'Proofreading Complete',
	            'email_subject' => '[Testing] - Linguist Started Work - Proofreading Complete',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ],[
	            'email_code' => 'translation_complete',
	            'email_template' => 'Translation Complete',
	            'email_subject' => '[Testing] - Linguist Started Work - Translation Complete',
            'email_to_address' => '',
            'email_cc_address' => '',
            'email_bcc_address' => ''
	        ]
	    );
	    $user1 = EmailSettings::insert($data);
    }
}
