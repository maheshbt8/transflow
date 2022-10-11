<?php

return [
    
	'adminManagement' => [
        'title'          => 'Admin Management',
        'title_singular' => 'Admin Management',
    ],	
	
	'userManagement' => [
        'title'          => 'User Management',
        'title_singular' => 'User Management',
    ],
	
	'orgManagement' => [
        'title'          => 'Org Management',
        'title_singular' => 'Org Management',
    ],
	
	'suborgManagement' => [
        'title'          => 'Sub Org Management',
        'title_singular' => 'Sub Org Management',
    ],
	
	
	'deptManagement' => [
        'title'          => 'Dept Management',
        'title_singular' => 'Dept Management',
    ],
	'clientManagement' => [
        'title'          => 'Client Management',
        'title_singular' => 'Client Management',
    ],
    'AEMMgmt'        => [
        'title'          => 'AEM',
        'title_singular' => 'AEM',
        'menu' => 'AEM',
        'fields'         => [
            'Kreviewer'      => 'KPT Reviewer',
            'Qreviewer'      => '  Client Reviewer ',
            'Translator'      => 'KPT Translator',
            'QatarClient'      => 'Client Approval',
            'KPTPMManage'      => 'KPT PM ',
            'QatarPMManage'=>'Client PM '
        ],
	],
	 'permission'     => [
        'title'          => 'Permissions',
        'title_singular' => 'Permission',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => '',
            'title'             => 'Title',
            'key'             => 'Key',
            'title_helper'      => '',
            'created_at'        => 'Created at',
            'created_at_helper' => '',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => '',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => '',
        ],
    ],
    'role'           => [
        'title'          => 'Roles',
        'title_singular' => 'Role',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => '',
            'title'              => 'Title',
            'title_helper'       => '',
            'permissions'        => 'Permissions',
            'permissions_helper' => '',
            'created_at'         => 'Created at',
            'created_at_helper'  => '',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => '',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => '',
            'add'         => 'Add',
            'view'         => 'View',
            'update'         => 'Update',
            'delete'         => 'Delete',
        ],
    ],
    'user'           => [
        'title'          => 'Super Admin',
        'title_singular' => 'Administrator',
        'fields'         => [
            'id'                       => 'ID',
            'id_helper'                => '',
            'name'                     => 'Name',
            'name_helper'              => '',
            'email'                    => 'Email',
            'email_helper'             => '',
            'email_verified_at'        => 'Email verified at',
            'email_verified_at_helper' => '',
            'password'                 => 'Password',
            'password_helper'          => '',
            'roles'                    => 'Roles',
            'roles_helper'             => '',
            'remember_token'           => 'Remember Token',
            'remember_token_helper'    => '',
            'created_at'               => 'Created at',
            'created_at_helper'        => '',
            'updated_at'               => 'Updated at',
            'updated_at_helper'        => '',
            'deleted_at'               => 'Deleted at',
            'deleted_at_helper'        => '',
			'org_label'                => 'organization',
			'suborg_label'             => 'SubOrganization',
			'dept_label'               => 'Department',
            'mobile'               => 'Mobile',
        ],
    ],

	 'kptorganization'           => [
        'title'          => 'organizations',
        'title_singular' => 'organization',
        'fields'         => [
            'org_id'                       => 'Org ID',
            'org_id_helper'                => '',
            'org_name'                     => 'Organization Name',
            'org_name_helper'              => 'Organization name should be unique',
            'org_status'                 	=> 'Org Status',
            'org_status_helper'          	=> '',
            'created_by'                 	=> 'Created By',
            'created_by_helper'           	=> '',
            'remember_token'           		=> 'Remember Token',
            'remember_token_helper'    		=> '',
            'created_at'               		=> 'Created at',
            'created_at_helper'        		=> '',
            'updated_at'               		=> 'Updated at',
            'updated_at_helper'        		=> '',
            'deleted_at'              		=> 'Deleted at',
            'deleted_at_helper'        		=> '',
        ],
    ],
	
	
	
	'kptsuborganization'           => [
        'title'          => 'Sub organizations',
        'title_singular' => 'Sub organization',
        'fields'         => [
            'sub_org_id'                       => 'Sub Org ID',
            'sub_org_id_helper'                => '',
            'sub_org_name'                     => 'Sub Org Name',
            'sub_org_name_helper'              => 'Sub Organization name should be unique',
            'sub_org_status'                 	=> 'Sub Org Status',
            'sub_org_status_helper'          	=> '',
            'created_by'                 	=> 'Created By',
            'created_by_helper'           	=> '',
            'remember_token'           		=> 'Remember Token',
            'remember_token_helper'    		=> '',
            'created_at'               		=> 'Created at',
            'created_at_helper'        		=> '',
            'updated_at'               		=> 'Updated at',
            'updated_at_helper'        		=> '',
            'deleted_at'              		=> 'Deleted at',
            'deleted_at_helper'        		=> '',
        ],
    ],
		
	
	'kptsuborganization_users'           => [
        'title'          => 'Sub Org Users',
        'title_singular' => 'Sub Org Users',        
    ],
	
	'kptorganization_users'           => [
        'title'          => 'Org Users',
        'title_singular' => 'Org Users',        
    ],
	
	'kptdepartment_users'           => [
        'title'          => 'Department Users',
        'title_singular' => 'Department Users',        
    ],
	
	'kptauthenticated_users'     => [
        'title'          => 'Users',
        'title_singular' => 'Users',        
    ],
	
	
	'kptdepartment'           => [
        'title'          => 'Departments',
        'title_singular' => 'Department',
        'fields'         => [
            'dept_id'                       => 'Department ID',
            'dept_id_helper'                => '',
            'dept_name'                     => 'Department Name',
            'dept_name_helper'              => 'Department name should be unique',
            'dept_status'                 	=> 'Department Status',
            'dept_status_helper'          	=> '',
            'created_by'                 	=> 'Created By',
            'created_by_helper'           	=> '',
            'remember_token'           		=> 'Remember Token',
            'remember_token_helper'    		=> '',
            'created_at'               		=> 'Created at',
            'created_at_helper'        		=> '',
            'updated_at'               		=> 'Updated at',
            'updated_at_helper'        		=> '',
            'deleted_at'              		=> 'Deleted at',
            'deleted_at_helper'        		=> '',
        ],
    ],

    'marketingcampaign'           => [
        'title'          => 'Marketing Campaign',
        'title_singular' => 'Marketing Campaign',
        'fields'         => [
            'mk_campign_id'                 => 'Reference No',
            'mk_campign_id_helper'          => '',
            'campaign_name'                 => 'Name',
            'campaign_name_helper'          => '',
            'campaign_email'                => 'Email',
            'campaign_email_helper'         => '',
            'campaign_contact'              => 'Contact',
            'campaign_contact_helper'       => '',
            'campaign_organization'         => 'Organization',
            'campaign_organization_helper'  => '',
            'target_language'               => 'Target Language',
            'target_language_helper'        => '',
            'campaign_status'               => 'Status',
            'campaign_status_helper'        => '',
            'created_at'                    => 'Created at',
            'created_at_helper'             => '',
        ],
    ],

    'locrequest'           => [
        'title'          => 'Project',
        'title_singular' => 'Project',
        'fields'         => [
            'request_id'                 => 'Reference No',
            'request_id_helper'          => '',
            'client_admin'          => 'Client Admin Name',
            'name_helper'                => '',
            'category'                   => 'Category',
            'category_helper'            => '',
            'source_language'            => 'Source Language',
            'source_language_helper'     => '',
            'target_language'            => 'Target Language',
            'target_language_helper'     => '',
            'status'                     => 'Status',
            'status_helper'              => '',
            'created_at'                 => 'Created at',
            'created_at_helper'          => '',
            'updated_at'                 => 'Updated at',
            'updated_at_helper'          => '',
            'special_instructions'       => 'Special Instructions',
            'source_file'       => 'Source File',
            'source_text'       => 'Source Text',
            'target_text'       => 'Target Text',
            'comments'       => 'Comments',
            'upload_file'       => 'Upload File',
            'updated_on'       => 'Updated On',
            'updated_by'       => 'Updated By',
            'download_link'       => 'Download Link',
            'todoactivities'       => 'Projects',
            'addprojects'       => 'Add Projects',
            'tm_status'       => 'TM Status',
            'translate_to'       => 'Translate to',
            'delivery_date'       => 'Delivery Date',
            'no_pages'       => 'Number of Pages',
            'no_words'       => 'Number of Words',
            'task'       => 'Task',
            'linguist'       => 'Linguist',
            'reviewer'       => 'Reviewer',
            'delivery_date'       => 'Date of Delivery',
            'created_by'                    => 'Created Person',
            'cl_a_email'                    => 'Client Admin Email',
            'cl_c_email'                    => 'Client  Email',
            'cl_organization'           => 'Client Organization',
            'description'           => 'Project Description',
            'action'           => 'Action',
            'cl_a_mobile' => 'Client Admin Mobile',
            'add_tms' => 'Add TMS',
            'client_invoice' => 'Create Client Invoice',
            'vendor_invoice' => 'Create Linguist Invoice',
        ],
    ],
    'e_settings'           => [
        'title'          => 'Email Settings',
        'title_singular' => 'Email Settings',
        'fields'         => [
            'template'      => 'Template',
            'subject'       => 'Subject',
            'to_address'    => 'To Address',
            'cc_address'    => 'CC Address',
            'bcc_address'   => 'BCC Address',
            'organization'      =>' Organization',
            'action'        => 'Action',
        ],
    ],
    'translation'        => [
        'title'          => 'Translation',
        'title_singular' => 'Translation',
        'menu' => 'Translation Tool',
        'fields'         => [
            'text'      => 'Translation Text',
            'doc'      => 'Document',
            'slider'      => 'Slider',
            'word'      => 'Word to Vec',
        ],
    ],
    'translation_memory'        => [
        'title'          => 'Translation Memory',
        'title_singular' => 'Translation Memory',
        'menu' => 'Translation Memory',
        'fields'         => [
            'text'      => 'Translation Text',
            'doc'      => 'Document',
            'slider'      => 'Slider',
            'word'      => 'Word to Vec',
        ],
    ],
    'ocrpdf'           =>[
    'title'  =>'OCRPDF',
    'title_singular' => 'OCRPDF',
    'menu'=>'OCRPDF',
    'fields'=>[
            'reference_code'             => 'Reference Code',
            'reference_code_helper'      => '',
            'uploaded_files'             => 'Uploaded Files',
            'uploaded_files_helper'      => '',
            'uploaded_files_name'        =>'Uploaded Files Name',
            'uploaded_files_name_helper' => '',
            'status'                     => 'Status',
            'status_helper'              => '',
            'uploaded_on'                => 'Uploaded On',
            'uploaded_on_helper'         => '',
            'completed_date'             => 'Completed_Date',
            'completed_date_helper'      => '',
    ],
],
'clientuser'=>[
    'title'  =>'Client User',
    'title_singular' => 'Client User',
    'menu'=>'Client User',],



'kptromanization'=>[
    'title'  =>'Romanization',
    'title_singular' => 'Romanization',
    'menu'=>'Romanization',],


'transflowsample'=>[
    'title'  =>'Samples',
    'title_singular' => 'Samples',
    'menu'=>'Samples',],



'videototext'=>[
    'title'  =>'Voice to Text',
    'title_singular' => 'Voice to Text',
    'menu'=>'Voice to Text',
    'fields'=>[
        'job_id'=>'Id',
        'job_id_helper'=>'',
        'job_name'=>'Job Name',
        'job_name_helper'=>'',
        'source_language'=>'Source Language',
        'source_language_helper'=>'',
        'JSON_file'=> 'JSON File',
        'JSON_file_helper'=>'',
        'video_text'=>'Video Text',
        'video_text_helper'=>'',
        'uploaded_at'=>'Uploaded at',
        'uploaded_at_helper'=>'',
        'Action'=>'Action',
        'Action_helper'=>'',
		'target_language'=>'Target Language',
		'edit_translation' => 'Edit Translation',	

    ],
],
    'quotegenerate'     => [
        'title'          => 'Quote Generation',
        'title_singular' => 'Quote Generation', 
        'fields'         => [
            'id'=>'Id',
            'mobile'=>'Contact Number',
            'name'=>'Name',
            'company'=>'Company Name',
            'quote_address'=>'Address',
            'quote_subject'=>'Subject',
            'lname'=>'Last Name',
            'lname_helper'=>'',
            'fname'=>'First Name',
            'fname_helper'=>'',
            'email'=>'email',
            'email_helper'=>'',
            'QuoteID'      => 'Quote ID',
            'QuoteID_helper'=>'',
            'date'       => 'Date',
            'date_helper'=>'',
            'type_of_request'    => 'Type of Request',
            'type_of_request_helper'=>'',
            'source_language'    => 'Source Language',
            'source_language_helper'=>'',
            'target_languages'   => 'Target Languages',
            'target_languages_helper'=>'',
            'currency'   => 'Currency',
            'currency_helper'=>'',
            'status'   => 'Status',
            'client_name'=>'Client Name',
            'status_helper'=>'',
            'profit_margin'        => 'Profit Margin',
         'profit_margin_helper'=>'',
         'request'        => 'Raise Request',
         'request_helper'=>'',
        ], 
    ],
    'xliffsegmentation'     => [
        'title'          => 'XLIFF Segmentation',
        'title_singular' => 'XLIFF Segmentation', 
        'fields'         => [
            'Refrence_Code'      => 'Reference Code',
            'Uploaded_file'       => 'Uploaded File',
            'Uploaded_on'       => 'Uploaded On',
             'Status'   => 'Status',
            'Completed_On'   => 'Completed On',
           
        ],  
    ],
    'speechtospeech'     => [
        'title'          => 'Speech To Speech',
        'title_singular' => 'Speech To Speech', 
        'fields'         => [
            'Video'      => 'Video',
            'source_language'    => 'Source Language',
            'source_language_helper'=>'',
            'target_languages'   => 'Target Languages',
            'target_languages_helper'=>'',
            'transcribe_text'       => 'Transcribe Text',
            
             'Status'   => 'Status',
             'Uploaded_at'       => 'Uploaded at',
          
        ],  
    ],

    'Currencies'     => [
        'title'          => 'Currencies',
        'title_singular' => 'Currencies', 
        'fields'         => [
            'currency_name'      => 'Currency Name',
            'currency_symbol'    => 'Currency Symbol',
            'source_language_helper'=>'',
            'currency_code'   => 'Currency Code',
            'Currencies_helper'=>'',
           
            
             'Status'   => 'Status',
             'Uploaded_at'       => 'Uploaded at',
          
        ],  
    ],


    'Languages'     => [
        'title'          => 'Languages',
        'title_singular' => 'Languages', 
        'fields'         => [
            'Language_name'      => 'Language Name',
            'Language_code'    =>'Language code',
            'Language_helper'=>'',
           'lang_status'   => 'Status',
             'Uploaded_at'       => 'Uploaded at',
          
        ],  
    ],



];