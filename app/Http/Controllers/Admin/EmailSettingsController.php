<?php

namespace App\Http\Controllers\Admin;
use App\User;
use Auth;
use App\EmailSettings;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\email_templets;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailDemo;
use Symfony\Component\HttpFoundation\Response;
use App\Kptorganization;
class EmailSettingsController extends Controller
{
	public function index()
    {
        if (! checkpermission('email_settings')) {
            return abort(401);
        }
        $email_settings = EmailSettings::join('email_template','loc_email_settings.template_id','=','email_template.id')->select('loc_email_settings.*','email_template.email_template','email_template.email_subject')->get();
        return view('admin.emailsettings.index', compact('email_settings'));
    }

    public function create()
    {
        if (! checkpermission('email_settings','add')) {
            return abort(401);
        }
          $email_template = email_templets::get();
        $kptorganization = Kptorganization::get()->pluck('org_name', 'org_id');
        return view('admin.emailsettings.create', compact('kptorganization','email_template'));   
    }
    public function store(Request $request)
    {
        if (! checkpermission('email_settings')) {
            return abort(401);
        }
        // $orgs= Kptorganization::get()->pluck('org_name', 'org_id');
    	$this->validate($request, ['email_template' => ['required']]);
    	//$this->validate($request, ['email_subject' => ['required']]);
    	$this->validate($request, ['to_address' => ['required']]);
        $this->validate($request, ['organization' => ['required']]);

        // if($request['email_template'] == 'Create request'){
        //     $email_code='create_request';
        // }elseif($request['email_template'] == 'Edit Request'){
        //     $email_code='Edit_request';
        // }elseif($request['email_template'] == 'Status New'){
        //     $email_code='Status New';
        // }elseif($request['email_template'] == 'Status Assigned'){
        //     $email_code='Status Assigned';
        // }elseif($request['email_template'] == 'Status 25% Completed'){
        //     $email_code='Status 25% Completed';
        // }elseif($request['email_template'] == 'Status 50% Completed'){
        //     $email_code='Status 50% Completed';
        // }elseif($request['email_template'] == 'Status 75% Completed'){
        //     $email_code='Status 75% Completed';
        // }elseif($request['email_template'] == 'Status 100% Completed'){
        //     $email_code='Status 100% Completed';
        // }elseif($request['email_template'] == 'Analysis in Progress'){
        //     $email_code='Analysis in Progress';
        // }elseif($request['email_template'] == 'Translation in Progress'){
        //     $email_code='Translation in Progress';
        // }elseif($request['email_template'] == 'Proofreading Complete'){
        //     $email_code='Proofreading Complete';
        // }elseif($request['email_template'] == 'Translation Complete'){
        //     $email_code='Translation Complete';
        // }elseif($request['email_template'] == 'Linguist Comments'){
        //     $email_code='Linguist Comments';
        // }
    // 	$data=['template_id'=>$request['email_template'],
    // 			'email_subject'=>$request['email_subject'],
    //     ];

    //     $template=email_templets::create($data);
      
    //    $template_id=$template->id;
   
    //    if($template_id>0){
       // $email_template = email_templets::get();
    		$data=['template_id'=>$request['email_template'],
                'email_to_address'=>$request['to_address'],
    			'email_cc_address'=>$request['cc_address'],
    			'email_bcc_address'=>$request['bcc_address'],
                'email_org'=>$request['organization'],
    		];
            EmailSettings::insert($data);
    //    }
    // 	EmailSettings::create($data);
    	Session()->flash('message', 'Email template successfully added');
        return redirect()->route('admin.emailsettings.index');
    }
    public function edit($email_setting_id)
    {
        if (! checkpermission('email_settings','update')) {
            return abort(401);
        }
       $email_templates = email_templets::get();
    //    print_r($email_template);die;

        $email_settings = EmailSettings::join('email_template','loc_email_settings.template_id','=','email_template.id')->get();
        //  echo "<pre>";
        // print_r($email_settings);die;   
    //  $email_template = email_templets::get()->pluck('id');
    //   //  $email_template = EmailSettings::join('email_template','loc_email_settings.template_id','=','email_template.id')->select('loc_email_settings.*','email_template.email_template','email_template.email_subject')->get();
 
    	$edit_email_setting=EmailSettings::where('email_setting_id',$email_setting_id)->first();
        // echo "<pre>";
        // print_r($edit_email_setting);die;
        $kptorganization = Kptorganization::get()->pluck('org_name', 'org_id');
    	return view('admin.emailsettings.edit',compact('edit_email_setting','kptorganization','email_templates','email_settings'));   
    }
    public function update(Request $request)
    {
        if (! checkpermission('email_settings','update')) {
            return abort(401);
        }
    	$this->validate($request, ['email_setting_id' => ['required']]);
    	$this->validate($request, ['email_template' => ['required']]);
    	// $this->validate($request, ['email_subject' => ['required']]);
    	$this->validate($request, ['to_address' => ['required']]);
    	$email_setting_id=$request['email_setting_id'];
        $data=['template_id'=>$request['email_template'],
        'email_to_address'=>$request['to_address'],
        'email_cc_address'=>$request['cc_address'],
        'email_bcc_address'=>$request['bcc_address'],
        'email_org'=>$request['organization'],
    ];
              // EmailSettings::update($data);
    	EmailSettings::where('email_setting_id',$email_setting_id)->update($data);
    	Session()->flash('message', 'Email template successfully updated');
        return redirect()->route('admin.emailsettings.index');
    }
    public function show()
    {
        if (! checkpermission('email_settings')) {
            return abort(401);
        }
    	/*$data = array('name'=>"Virat Gandhi");
   
      Mail::send(['text'=>'mail'], $data, function($message) {
         $message->to('mtailor@keypoint-tech.com', 'Test')->subject('Laravel Basic Testing Mail');
         $message->from('mtailor@keypoint-tech.com','Virat Gandhi');
      });
      echo "Basic Email Sent. Check your inbox.";*/
      $email = 'maheshbt22@gmail.com';
   
        $mailData = [
            'title' => 'Demo Email',
            'url' => 'https://www.positronx.io'
        ];
        sendstatusmail($email,$mailData);
        /*Mail::to($email)->send(new EmailDemo($mailData));*/
   
        return response()->json([
            'message' => 'Email has been sent.'
        ], Response::HTTP_OK);
    	/*Mail::to('mtailor@keypoint-tech.com')
	    ->send('Hi this is for testing');*/
    }
}