<?php

namespace App\Http\Controllers\Admin;

use App\Clientorganization;
use App\user_orgizations;
use App\User;
use Auth;
use App\loc_request;
use App\loc_targetfile;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\loc_languages;
use App\locrequestassigned;
use App\loc_multiple_file;
use App\loc_request_comment;
use App\quote_generation_request_type;
use File;
use App\models\loc_translation_master;
use App\loc_translation_child;
use App\clientorg_invoice_details;
use App\vendor_invoice_details;
use App\locQuoteService;
use App\loc_invoice;
use App\loc_po;
use App\client_user_orgizations;
use App\locQuoteSourcelang;
use App\bank_details;
use App\address;
use App\currencies;
use PDF;
use App\personal_details;
use Storage;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        if (!checkpermission('create_request')) {
            return abort(401);
        }
        $loc_request = new loc_request();
        $request_id = $loc_request::get('req_id');
        // $get_source_lang=locrequestassigned::where('request_id',$request_id->req_id )->groupBy('source_language')->get('source_language');
                // print_r($get_source_lang);die;
        $userid = Auth::user()->id;
        $marketing_campaign = loc_request::where(['user_id' => $userid])->orderBy('created_time', 'desc')->get();
        //echo "<pre>"; print_r($marketing_campaign);die;
        return view('admin.request.index', compact('marketing_campaign', 'loc_request'));
    }
    public function create()
    {
        if (!checkpermission('create_request', 'add')) {
            return abort(401);
        }
        $user_role = Auth::user()->roles[0]->name;
        $org_id = get_user_org('org', 'org_id');
        $clientorganizations = Clientorganization::where(['org_status' => '1', 'kpt_org' => $org_id])->get();
        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
       
        return view('admin.request.create', compact('loc_languages', 'clientorganizations'))->with(['page_title'=>'Project Create']);
    }
    public function store(Request $request)
    {
        if (checkallpermission(['create_request','quote_request'])) {
    
        $this->validate($request, ['source_language' => ['required']]);
        $post = $request;
        $userid = Auth::user()->id;
        $quote_id = $post['quote_id'];
        $brief_description     = $post['brief_description'];
        //$document_type              = $post['document_type'];
        $source_language       = $post['source_language'];
        $priority              = 'High';
        $special_instructions  = $post['special_instruction'] ? $post['special_instruction'] : '';
        $request_date          = $post['request_date'];
        $soucefilestatus        = 1;
        $reference_id   = "R".rand(0001,9999) .time();
        $created_time   = date("Y-m-d H:i:s");
        $file_sdate=date("Ymd");


        // echo "<pre/>";print_r($_POST);
    
        // for ($j = 0; $j < count($source_language); $j++) {
        //     $vendors_li=$request['tr_list_select'][$j];
        //     $assign_gst=$request['assign_gst'][$j];
        //     $currency=$request['currency'][$j];
        //     $unit_count_d=$request['unit_count'][$j];
        //     $per_unit_d=$request['per_unit'][$j];
        //     print_r($vendors_li);
        //     print_r($assign_gst);
        //     print_r($currency);
        //     print_r($unit_count_d);
        // }
        // die;

        
        $userid = Auth::user()->id;
        $user_role = Auth::user()->roles[0]->name;

        if ($user_role == 'clientuser' || $user_role == "requester" || $user_role == "approval" || $user_role == "reviewer") {
            $client_org_id = get_user_org('clientorg', 'org_id');
            $kpt_org_id = Clientorganization::select('kpt_org')->where('org_id', $client_org_id)->first();
            $kpt_org_id = $kpt_org_id->kpt_org;
        } else {
            $kpt_org_id = get_user_org('org', 'org_id');
            $client_org_id = $post['client_org'];
        }
        $arr_createrequests = array(
            'user_id' => $userid, 
            'reference_id'=>$reference_id,
            'brief_description' => $brief_description, 
            'special_instructions' => $special_instructions, 
            'request_status' => 'New', 
            'priority' => $priority, 
            'created_time' => $created_time, 
            'client_org_id' => $client_org_id, 
            'organization_id' => $kpt_org_id
        );
     // print_r($arr_createrequests);die;
        if($quote_id){
            $arr_createrequests['quote_gen_id']=$quote_id;
            $quote_code=loc_translation_master::where('translation_quote_id',$quote_id)->first();
            $arr_createrequests['reference_id']=$quote_code->quote_code;
        }
        $req = loc_request::create($arr_createrequests);
        
        
        if ($req->req_id > 0) {
            if($quote_id){
                $requestassign = array('request_id' => $req->req_id);
              
                $locrequestassige = locrequestassigned::where('quote_id',$quote_id)->update($requestassign);
                $quote_data = loc_translation_master::where('translation_quote_id', $quote_id)->first();
               // print_r($quote_data);die;
                $client_invoice_data = array(
                    'org_id' => $kpt_org_id, 
                    'clientorg' => $client_org_id,
                    'req_id' => $req->req_id,
                    'pending' => $quote_data->grand_total,
                    'desc' => 'Booking Amount for: '.$quote_code->quote_code, 
                    'created_by' => $userid,   
                    'created_at' => $created_time 
                    );
                clientorg_invoice_details::insert($client_invoice_data);
            }
            for ($j = 0; $j < count($source_language); $j++) {
               if($quote_id == ''){
                    $language =new locQuoteSourcelang();
                    $language->request_id=$req->req_id;
                    $language->sourcelang_id=$source_language[$j];
                    $language->save();
                    $Q_sourcelang_id=$language->id;
               }else{
                    $update_id=array('request_id'=>$req->req_id);
                    $locquotesource=locQuoteSourcelang::where('quote_id',$quote_id)->update($update_id);
                    $Q_sourcelang_id=$source_language[$j];
               }
               
                $destination_lang  = $post['destination_language_' . $j];
               
                if($quote_id == ''){
                    for ($i = 0; $i < count($destination_lang); $i++) {
                        $requestassigned = array('request_id' => $req->req_id, 'loc_source_id' => $Q_sourcelang_id, 'target_language' => $destination_lang[$i]);
                        $locrequestassige = locrequestassigned::insert($requestassigned);
                    }
                }
                $source_type           = $post['source_type_' . $j];
                $source_file_path      = $_FILES['source_file_' . $j];
                $source_text           = $post["source_text_" . $j];
                if ($source_type == 'File') {
                    $multiplefiles=$request['multiplefiles'];
                    if($multiplefiles == 1){
                        $file_data = $request->file('multiple_files');
                    }else{
                        $file_data = $request->file('source_file_' . $j);    
                    }
                    for ($i = 0; $i < count($file_data); $i++) {
                        $file = $file_data[$i];
                        
                        $file_name = $file->getClientOriginalName();
                        $original_file=$file_name;
                       
                        $source_file = $file->getRealPath();
                      
                        $source_file_extension = strtolower($file->getClientOriginalExtension());
                        $supported_image = array('zip', 'zip', 'tar', 'gz', 'rar', 'doc', 'docx', 'xls', 'apk', 'xlsx', 'jpeg', 'jpg', 'png', 'rtf', 'html', 'xml', 'pdf', 'ppt', 'txt', 'PPT', 'pptx', 'PPTX', 'mp3', 'mp4', 'avi', 'flv', 'wmv', 'mov', 'xliff', 'json');
                        if (in_array($source_file_extension, $supported_image)) {
                            $path = base_path() . "/public/storage/request/source/";
                          // print_r($path);die;
                            $currentfile_date=base_path() . "/public/storage/request/source/".$file_sdate;
                            $path_save =  $currentfile_date;
                            $source_file_path = $upload_file_name = $req->req_id . '_'.$j. '_' . $i . '.' . $source_file_extension;
                            $name =   $upload_file_name;
                            $filePath = 'request/source/'.$file_sdate.'/' . $name;
                          
                            $res= Storage::disk('s3')->put($filePath, file_get_contents($file));
                            $multipleFile =new loc_multiple_file();
                            $multipleFile->request_id=$req->req_id;
                            $multipleFile->source_language=$Q_sourcelang_id;
                            $multipleFile->source_type=$source_type;
                            $multipleFile->file_name = $source_file_path;
                            $multipleFile->original_file =$original_file;
                            $multipleFile->created_at=$created_time;
                            $multipleFile->save();
                            $fileid=$multipleFile->id;
                            if($fileid > 0){
                                $fileid=$fileid;
                                $req_id=$req->req_id;
                                $vendors_li=$request['tr_list_select'][$j];
                                $assign_gst_li=$request['assign_gst'][$j];
                                $currency=$request['currency'][$j];
                                $unit_count_d=$request['unit_count'][$j];
                                $per_unit_d=$request['per_unit'][$j];
                                $requestassigned = array('request_id' => $req->req_id, 'loc_source_id' => $Q_sourcelang_id, 'target_language' => $destination_lang[0]);
                                $request_lang_id=locrequestassigned::where($requestassigned)->first();
                                $request_lang_id=$request_lang_id->id;
                                for ($j1=0; $j1 < count($vendors_li); $j1++) { 
                                        $userid = Auth::user()->id;
                                    $assign_date=date('Y-m-d H:i:s');
                                    $assign_data = $vendors_li[$j1];//vendor_id
                                    $trAssign = 'tr_assign';//
                                    $fileid= $fileid;
                                    $req_id= $req_id;
                                    $req_lang_id= $request_lang_id;
                                    $assign_work_per='';
                                    $assign_gst=$assign_gst_li[$j1];
                                    $currency_id=$currency[$j1];
                                    $unit_count=$unit_count_d[$j1];
                                    $per_unit=$per_unit_d[$j1];
                                    $currency_cost  = gettabledata('currencies','inr',['id'=>$currency_id]);
                                    $assign_amount_per=($unit_count*$per_unit);
                            if ($trAssign == 'tr_assign') {
                                $translators = user::select('id', 'name')->whereHas(
                                    'roles',
                                    function ($q) {
                                        $q->select('name')->whereIn('name', ['translator','vendor']);
                                    }
                                )->where('id',$assign_data)->with('roles')->first();
                                if($translators->roles[0]->name == 'vendor'){
                                    $trAssign = 'v_assign';
                                }
                            }
                                $assign_id = $assign_data;
                                if ($trAssign == 'tr_assign') {
                                   // $work_per=$assign_work_per;
                                    $amount_per=$assign_amount_per;
                                    $type_gst=$assign_gst;
                                     if($type_gst=='no_gst'){
                                       $gst=0;
                                       $total_amount=$amount_per;
                                     }else{
                                       $gst=18;
                                       $total_amount=$amount_per+(($amount_per*$gst)/100);
                                        }
                                   $ass_data=array(
                                     'tr_id'=>$assign_id,
                                     'tr_assigned_date'=>date('Y-m-d H:i:s'),
                                     'v_amount'=>$amount_per,
                                     'v_total'=>$total_amount,
                                     'v_gst'=>$type_gst,
                                     'v_gst_amnt'=>$gst,
                                     'currency_id'=>$currency_id,
                                     'currency_cost'=>$currency_cost,
                                     'unit_count'=>$unit_count,
                                     'per_unit'=>$per_unit,
                                    );
                                   // print_r($ass_data);die;
                                }elseif ($trAssign == 'v_assign') {
                                     $work_per=$assign_work_per;
                                     $amount_per=$assign_amount_per;
                                     $type_gst=$assign_gst;
                                      if($type_gst=='no_gst'){
                                        $gst=0;
                                        $total_amount=$amount_per;
                                      }else{
                                        $gst=18;
                                        $total_amount=$amount_per+(($amount_per*$gst)/100);
                                         }
                                    $ass_data=array(
                                      'v_id'=>$assign_id,
                                      'work_per'=>$work_per,
                                      'v_assigned_date'=>date('Y-m-d H:i:s'),
                                      'v_amount'=>$amount_per,
                                      'v_total'=>$total_amount,
                                      'v_gst'=>$type_gst,
                                      'v_gst_amnt'=>$gst,
                                      'currency_id'=>$currency_id,
                                      'currency_cost'=>$currency_cost,
                                        'unit_count'=>$unit_count,
                                     'per_unit'=>$per_unit,
                                     );
                                }
                                $ass_data['req_lang_id']=$req_lang_id;
                                $ass_data['request_id']=$req_id;
                                $ass_data['req_file_id']=$fileid;
                                /*$lcf_where_v_t=['request_id' => $req_id,'req_lang_id'=>$req_lang_id,'req_file_id'=>$fileid];
                                $loc_files_update_data = loc_targetfile::where($lcf_where_v_t)->first();
                                if($loc_files_update_data){
                                    $loc_files_update = loc_targetfile::where(['id'=>$loc_files_update_data->id])->update($ass_data);
                                }else{*/        
                                    $loc_files_update = loc_targetfile::insert($ass_data);
                                /*}*/
                            /*if($loc_files_update){
                                if ($trAssign == 'v_assign' || $trAssign == 'tr_assign') {
                                $loc_data=loc_request::where(['req_id' => $req_id])->first();
                                $vendor_invoice_data = array( 
                                    'user_id' => $assign_id,
                                    'req_id' => $req_id,
                                    'pending' => $total_amount,
                                    'desc' => 'Booking Amount for: '.$loc_data->reference_id, 
                                    'created_by' => $userid,   
                                    'created_at' => date('Y-m-d H:i:s')
                                );
                                vendor_invoice_details::insert($vendor_invoice_data);
                                }
                                
                            }*/
                            $vtorg=get_user_org('org','org_id');
                            $authenticated_users = User::whereHas(
                                'roles', function($q) {
                                    $q->where('name','orgadmin');
                                }
                            )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id',$vtorg)->get();
                            $orgadmin=$authenticated_users->toArray();
                            $orgadmin_emails=array_column((array)$orgadmin,'email');
                            $vtname=getusernamebyid($assign_id);
                            $reference_id=gettabledata('loc_request','reference_id',['req_id'=>$req_id]);
                            $vt_email=getusernamebyid($assign_id,'email');
                            $pmemail=getusernamebyid($userid,'email');
                            if($trAssign != 'v_assign'){
                                $email =[$vt_email];
                                $ccemail =[$pmemail];
                            }else{
                                $email =[$pmemail];
                                $ccemail =[];
                            }
                            
                            $ccemail = array_merge($orgadmin_emails,$ccemail);
                            $mailData = [
                                'title' => ucwords(showcrstatus($trAssign)).'  - Transflow',
                                'subject' => ucwords(showcrstatus($trAssign)).' for '.$reference_id,
                                'req_id' =>$reference_id,
                                'name'=>ucwords($vtname),
                                'email'=>$vt_email,
                                'date' => $assign_date,
                                'created_by' =>getusernamebyid($userid),
                                'request_url' => env('APP_URL').'admin/assigntotranslator/'.$reference_id//route('admin.request.assigntotranslator',[$reference_id]) 
                            ];
                            try{
                                //$res=sendstupdatemail($email,$mailData,$ccemail);
                            }catch (exception $e) {
                                createlog('error',$e->getMessage());
                            }
                            }
                            }
                             //  print_r($multipleFile);die;
                            //$multipleFiles = loc_multiple_file::insert($multipleFile);
                            
                        }
                    }
                } elseif ($source_type == 'Text') {
                    for ($i = 0; $i < count($source_text); $i++) {
                        // $source_text=$source_text[$i] ;        
                        $multipleFile = array('request_id' => $req->req_id, 'source_language' =>  $Q_sourcelang_id, 'source_type' => $source_type, 'source_text' => $source_text[$i]);
                        $multipleFiles = loc_multiple_file::insert($multipleFile);
                    }
                }
            }
            createlog('create_request','Project created successfully '.$arr_createrequests['reference_id'],$req->req_id);
            Session()->flash('message', 'Request successfully added');
        }else{
           Session()->flash('error_message', 'Request not added');
        }
        return redirect()->route('admin.request.todoactivities',['status'=>'active']);
    }else{
        
        return abort(401);
    }
    }
    public function edit($reference_id)
    {
        if (!Gate::allows('create_request') && !Gate::allows('request_todo_activities')) {
            return abort(401);
        }

        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
      
        $edit_request    = loc_request::where('reference_id', $reference_id)->first();
      
       
       
        $edit_request_lang    = DB::table('loc_request_lang')->where('reference_id', $reference_id)->first();
        $org_id=get_user_org('org','org_id');
        $mutiple_files_result = DB::table('loc_request_multiple_files')->where('reference_id', $reference_id)->first();
        if ($mutiple_files_result == '') {
            $mutiple_files_result = (object)array('source_file_name1' => '', 'source_file_name2' => '', 'source_file_name3' => '', 'source_file_name4' => '');
        }

          
        $vendors = user::select('id', 'name')->whereHas(
            'roles',
            function ($q) {
                $q->whereIn('name', ['translator','vendor']);
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->get();
  
        return view('admin.request.edit', compact('edit_request', 'loc_languages', 'edit_request_lang', 'mutiple_files_result','vendors'))->with(['page_title'=>'Project Edit - '.$reference_id]);
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     **/

    public function update(Request $request, $req_id)
    {
    //    echo "<pre/>";
    //   print_r($_POST);die;
        $this->validate($request, ['source_language' => ['required']]);
        $post = $request;
        $userid = Auth::user()->id;
        $reference_id     = $post['reference_id'];
        $brief_description     = $post['brief_description'];
        $source_language       = $post['source_language'];
        // echo "<pre/>";
        // print_r($_POST);
        // print_r($source_language);die;
        //$document_type              = $post['document_type'];
        $priority              = 'High';
        $special_instructions  = $post['special_instruction'] ? $post['special_instruction'] : '';
        $request_date          = $post['request_date'];
        $soucefilestatus        = 1;
        $reference_id   = date('dmyHis');
        $created_time   = date("Y-m-d H:i:s");
        $db_source_lang_id = $_POST['db_source_lang_id'];
        $db_source_lang_index = $_POST['db_source_lang_index'];
        $user_role = Auth::user()->roles[0]->name;

        if ($user_role == 'clientuser' || $user_role == "requester" || $user_role == "approval" || $user_role == "reviewer") {
            $client_org_id = get_user_org('clientorg', 'org_id');
            $kpt_org_id = Clientorganization::select('kpt_org')->where('org_id', $client_org_id)->first();
            $kpt_org_id = $kpt_org_id->kpt_org;
        } else {
            $kpt_org_id = get_user_org('org', 'org_id');
            $client_org_id = $post['client_org'];
        }
        $arr_createrequests = array('user_id' => $userid,'brief_description' => $brief_description, 'special_instructions' => $special_instructions, 'request_status' => 'New', 'priority' => $priority, 'client_org_id' => $client_org_id, 'organization_id' => $kpt_org_id);
        $req = loc_request::where('req_id', $req_id)->update($arr_createrequests);
        //print_r($req);die;
        $old_req_id = $req_id;
        if ($old_req_id > 0) {
            $get_source_lang=loc_translation_master::request_lang_select($req_id);
            $get_source_lang = array_column((array)$get_source_lang->toArray(), 'id');
            $my_source = $db_source_lang_id;
   
        // print_r($my_source);die;
            $db_source = $get_source_lang;
            
          //  print_r($db_source);die;
            $insert_source=array_values(array_diff($my_source, $db_source));
          
            $delete_source=array_values(array_diff($db_source, $my_source));
        
            for ($ds=0; $ds < count($delete_source); $ds++) { 
                $source_get_first=locQuoteSourcelang::where(['request_id'=> $req_id,'id'=>$delete_source[$ds]])->first(); 
                locrequestassigned::where(['request_id'=> $req_id,'loc_source_id'=>$source_get_first->id])->delete();
                $fid=loc_multiple_file::where(['request_id' => $old_req_id, 'source_type' => 'file','source_language'=>$source_get_first->id])->get();
                foreach($fid as $fileid){
                    $file_delete = loc_multiple_file::findOrFail($fileid->id);
                    $created_at=$file_delete->created_at;
                    $sfile_date=date('Ymd',strtotime($created_at));
                    $filePath = 'request/source/'.$sfile_date.'/' . $file_delete->file_name;
                    Storage::disk('s3')->delete($filePath);
                    $file_delete->delete();
                }

                locQuoteSourcelang::where(['id'=> $source_get_first->id])->delete(); 
            }
            // locQuoteSourcelang::where('request_id', $req_id)->delete();
            // locrequestassigned::where('request_id', $req_id)->delete();

            // $fid=loc_multiple_file::where(['request_id' => $old_req_id, 'source_type' => 'file'])->get();
            // foreach($fid as $fileid){
            //     $file_delete = loc_multiple_file::findOrFail($fileid->id);
            //     $created_at=$file_delete->created_at;
            //     $sfile_date=date('Ymd',strtotime($created_at));
            //     $filePath = 'request/source/'.$sfile_date.'/' . $file_delete->file_name;
            //     Storage::disk('s3')->delete($filePath);
            //     $file_delete->delete();
            // }
              // echo $source_language[0];die;
              $db_source_lang_id  = $post['db_source_lang_id'];
        //   
            for ($j = 0; $j < count($source_language); $j++) {
              //  $db_source_lang_index  = $post['db_source_lang_index'.$j];
                $index=$db_source_lang_index[$j];
             
                $source_get_first=locQuoteSourcelang::where(['request_id'=> $req_id,'id'=>$db_source_lang_id[$j]])->first(); 
                if($source_get_first){
                    $Q_sourcelang_id=$source_get_first->id;
                }else{
                    $language1 =new locQuoteSourcelang();
                    $language1->request_id=$req_id;
                    $language1->sourcelang_id=$source_language[$j];
                    $language1->description='';
                    $language1->save();
                    $Q_sourcelang_id=$language1->id;
                }

                $destination_lang  = $post['destination_language_'. $index];
             // 
                $requestassigned = array('request_id' => $req_id, 'loc_source_id' => $Q_sourcelang_id);
                $get_target_lang=locrequestassigned::where($requestassigned)->get();
                $get_target_lang = array_column((array)$get_target_lang->toArray(), 'target_language');
               
                $my_target = $destination_lang;
                $db_target = $get_target_lang;
             
                //$insert_target=array_values(array_diff($my_target, $db_target));
                if($db_target && $my_target){
                    $delete_target=array_values(array_diff($db_target, $my_target));
                    for ($ds=0; $ds < count($delete_target); $ds++) { 
                        locrequestassigned::where(['request_id' => $req_id, 'loc_source_id' => $Q_sourcelang_id,'target_language'=>$delete_target[$ds]])->delete();
                    }
                }



                if (is_countable($destination_lang)) {
                    for ($i = 0; $i < count($destination_lang); $i++) {
                        $requestassigned = array('request_id' => $req_id, 'loc_source_id' => $Q_sourcelang_id, 'target_language' => $destination_lang[$i]);
                        $check_target_lang=locrequestassigned::where($requestassigned)->count();
                        if($check_target_lang == 0){
                            $locrequestassige = locrequestassigned::insert($requestassigned);
                        }
                    }
                }


                $source_type           = $post['source_type_' . $j];

                $source_text           = $post["source_text_" . $j];
                $file_data = $request->file('source_file_' . $j);
                $old_file_data = $post["source_file_old_" . $j];
                if (is_countable($old_file_data)) {
                    $old_file_data_count = count($old_file_data);
                }else{
                    $old_file_data_count =0;
                }
                //print_r($old_file_data);die;
                if ($source_type == 'File') {
                    //loc_multiple_file::where(['request_id' => $old_req_id, 'source_language' => $Q_sourcelang_id, 'source_type' => 'file'])->delete();
                    if (is_countable($file_data)) {
                        for ($i = 0; $i < count($file_data); $i++) {
                            $file = $file_data[$i];
                            $rfiledate=date("Ymd");
                            $source_file_path      = $_FILES['source_file_' . $j];
                            $file_name = $file->getClientOriginalName();
                            $original_file=$file_name;
                            $source_file = $file->getRealPath();
                            $source_file_extension = strtolower($file->getClientOriginalExtension());
                            $supported_image = array('zip', 'zip', 'tar', 'gz', 'rar', 'doc', 'docx', 'xls', 'apk', 'xlsx', 'jpeg', 'jpg', 'png', 'rtf', 'html', 'xml', 'pdf', 'ppt', 'txt', 'PPT', 'pptx', 'PPTX', 'mp3', 'mp4', 'avi', 'flv', 'wmv', 'mov', 'xliff', 'json');
                            if (in_array($source_file_extension, $supported_image)) {
                                $source_file_path = $upload_file_name =$req_id . '_'.$j. '_' . ($i+$old_file_data_count) . '.' . $source_file_extension;
                                $name =   $upload_file_name;
                                $filePath = 'request/source/'.$rfiledate.'/' . $name;
                                $res= Storage::disk('s3')->put($filePath, file_get_contents($file));
                                    $multipleFile = array('request_id' => $old_req_id, 'source_language' => $Q_sourcelang_id, 'source_type' => $source_type, 'file_name' => $source_file_path,'original_file'=>$original_file,'created_at'=>$created_time);
                                    $multipleFiles = loc_multiple_file::insert($multipleFile);
                              
                            }
                        }
                    }
                }elseif($source_type == 'text') {
                    if (is_countable($source_text)) {
                        loc_multiple_file::where(['request_id' => $old_req_id, 'source_type' => 'text '])->delete();
                        for ($i = 0; $i < count($source_text); $i++) {

                            // $source_text=$source_text[$i] ;        
                            $multipleFile = array('request_id' => $old_req_id, 'source_language   ' => $Q_sourcelang_id, 'source_type' => $source_type, 'source_text' => $source_text[$i]);
                            $multipleFiles = loc_multiple_file::insert($multipleFile);
                            
                        }
                    }
                }
            }
            createlog('update_request','Project updated successfully '.$req['reference_id'],$req_id);
            Session()->flash('message', 'Request successfully added');
            return redirect()->route('admin.request.index');
        }
    }
    public function download($reference_id)
    {
        $file = public_path() . "/download/info.pdf";

        $headers = array(
            'Content-Type: application/pdf',
        );

        return Response::download($file, 'filename.pdf', $headers);
    }


    public function todoactivities()
    {
        if (!checkpermission('request_todo_activities')) {
            return abort(401);
        }

        $req_status='inprogress';
        if(isset($_GET['status']) && $_GET['status'] != ''){
            $req_status = $_GET['status'];
        }
        $sdate=date('Y-m-d', strtotime('today - 29 days'));
        $edate=date('Y-m-d');
        if(isset($_GET['sdate']) && $_GET['sdate'] != ''){
            $sdate = $_GET['sdate'];
        }
        if(isset($_GET['edate']) && $_GET['edate'] != ''){
            $edate = $_GET['edate'];
        }
        


        //echo $_GET['status'];die;
        $userid = Auth::user()->id;
        $user_role = Auth::user()->roles[0]->name;
        $get_user = new User();
        $loc_request = new loc_request();

        if ($user_role == 'orgadmin' || $user_role == 'sales') {
            $org_id = get_user_org('org', 'org_id');
            /*$todoactivities = loc_request::where([['request_status', '!=', 'new'], ['request_status', '!=', 'client_cancel']])->where('organization_id', $org_id)->orderBy('created_time', 'desc')->get();*/

            $where_status='organization_id= '.$org_id;
            if($req_status == 'active'){
                $where_status .=' AND (request_status = "new")';   
            }elseif($req_status == 'completed'){
                $where_status .=' AND (request_status = "publish")';
            }elseif($req_status == 'cancel'){
                $where_status .=' AND (request_status = "cancel" OR request_status = "client_cancel")';
            }else{
                $where_status .=' AND (request_status != "new" AND request_status != "client_cancel" AND request_status != "cancel")';
            }
         //   echo $where_status;die;
            $todoactivities = loc_request::leftJoin('loc_translation_qoute_generation_master','loc_request.quote_gen_id','=','loc_translation_qoute_generation_master.translation_quote_id')->whereBetween('created_time',[$sdate.' 00:00:00',$edate.' 23:59:59'])->whereRaw($where_status)->orderBy('created_time', 'desc')->get();
        }elseif ($user_role == 'finance') {
            $org_id = get_user_org('org', 'org_id');
            /*$todoactivities = loc_request::where([['request_status', '!=', 'new'], ['request_status', '!=', 'client_cancel']])->where('organization_id', $org_id)->orderBy('created_time', 'desc')->get();*/
            $where_status='organization_id= '.$org_id;
            if($req_status == 'active'){
                $where_status .=' AND (request_status = "new")';   
            }elseif($req_status == 'completed'){
                $where_status .=' AND (request_status = "publish")';
            
        }elseif($req_status == 'cancel'){
            $where_status .=' AND (request_status = "cancel" OR request_status = "client_cancel")';
        }else{
            $where_status .=' AND (request_status != "new" AND request_status != "client_cancel" AND request_status != "cancel")';
        }
            $todoactivities = loc_request::leftJoin('loc_translation_qoute_generation_master','loc_request.quote_gen_id','=','loc_translation_qoute_generation_master.translation_quote_id')->whereBetween('created_time',[$sdate.' 00:00:00',$edate.' 23:59:59'])->whereRaw($where_status)->orderBy('created_time', 'desc')->get();
        }
        elseif ($user_role == 'projectmanager') {
            $org_id = get_user_org('org', 'org_id');
            $where_status='organization_id= '.$org_id.' ANd (loc_translation_qoute_generation_master.pm_id = '.$userid.' OR user_id = '.$userid.')';
            if($req_status == 'active'){
                $where_status .=' AND request_status = "new"';   
            }elseif($req_status == 'completed'){
                $where_status .=' AND (request_status = "publish")';
            }elseif($req_status == 'cancel'){
                $where_status .=' AND (request_status = "cancel" OR request_status = "client_cancel")';
            }
            else{
                $where_status .=' AND (request_status != "new" AND request_status != "client_cancel")';
            }
            $todoactivities = loc_request::leftJoin('loc_translation_qoute_generation_master','loc_request.quote_gen_id','=','loc_translation_qoute_generation_master.translation_quote_id')->whereBetween('created_time',[$sdate.' 00:00:00',$edate.' 23:59:59'])->whereRaw($where_status)->orderBy('created_time', 'desc')->get();
        } elseif ($user_role == 'translator') {

           $where_status="";
            $org_id = get_user_org('org', 'org_id');
            $where_status='organization_id= '.$org_id.' ANd (loc_target_file.tr_id = '.$userid.' OR user_id = '.$userid.')';
            if($req_status == 'active'){
                $where_status .=' AND request_status != "new" AND request_status = "tr_assign"';   
            }elseif($req_status == 'completed'){
                $where_status .=' AND (request_status = "publish")';
            }
            elseif($req_status == 'cancel'){
                $where_status .=' AND (request_status = "cancel" OR request_status = "client_cancel")';
            }else{
                $where_status .=' AND (request_status != "tr_completed" AND request_status != "new" AND request_status != "client_cancel")';
            }


            $todoactivities = loc_request::leftJoin('loc_target_file', 'loc_request.req_id', '=', 'loc_target_file.request_id')->whereBetween('created_time',[$sdate.' 00:00:00',$edate.' 23:59:59'])->where('tr_id', $userid)
                ->orderBy('created_time', 'desc')->groupBy('req_id')->get();
 
              
        } elseif ($user_role == 'vendor') {
            $org_id = get_user_org('org', 'org_id');
            $where_status='organization_id= '.$org_id.' ANd (loc_target_file.v_id = '.$userid.' OR user_id = '.$userid.')';
            if($req_status == 'active'){
                $where_status .=' AND request_status != "new" AND request_status = "v_assign"';    
            }elseif($req_status == 'completed'){
                $where_status .=' AND (request_status = "publish"  AND request_status != "v_completed")';
            }elseif($req_status == 'cancel'){
                $where_status .=' AND (request_status = "cancel" OR request_status = "client_cancel")';
            }
            else{
                $where_status .=' AND (request_status != "new" AND request_status != "client_cancel" AND request_status != v_inprogress")';
            }
           // echo "<pre/>";    print_r($where_status);die;


            $todoactivities = loc_request::join('loc_target_file', 'loc_request.req_id', '=', 'loc_target_file.request_id')->whereBetween('created_time',[$sdate.' 00:00:00',$edate.' 23:59:59'])->where('v_id', $userid)
                ->orderBy('created_time', 'desc')->groupBy('req_id')->get();
             
        } elseif ($user_role == 'qualityanalyst') {
            $todoactivities = loc_request::join('loc_target_file', 'loc_request.req_id', '=', 'loc_target_file.request_id')->whereBetween('created_time',[$sdate.' 00:00:00',$edate.' 23:59:59'])->where('qa_id', $userid)
                ->orderBy('created_time', 'desc')->groupBy('req_id')->get();
        } elseif ($user_role == 'proofreader') {
            $todoactivities = loc_request::join('loc_target_file', 'loc_request.req_id', '=', 'loc_target_file.request_id')->whereBetween('created_time',[$sdate.' 00:00:00',$edate.' 23:59:59'])->where('pr_id', $userid)
                ->orderBy('created_time', 'desc')->groupBy('req_id')->get();
        } elseif ($user_role == 'reviewer') {
            $todoactivities = loc_request::whereBetween('created_time',[$sdate.' 00:00:00',$edate.' 23:59:59'])->whereIn('request_status', ['publish', 're_accept', 're_reject'])->orderBy('created_time', 'desc')->get();
        } elseif ($user_role == 'approval') {
            $org_id = get_user_org('clientorg', 'org_id');
            $todoactivities = loc_request::whereBetween('created_time',[$sdate.' 00:00:00',$edate.' 23:59:59'])->whereIn('request_status', ['new', 'approve', 'tr_assign', 'tr_inprogress', 'qa_assign', 'qa_inprogress', 'qa_accept', 'qa_reject', 'pr_assign', 'pr_inprogress', 'pr_accept', 'pr_accept', 'publish', 're_accept', 're_reject', 'client_cancel', 'pm_reject'])->where('client_org_id', $org_id)->orderBy('created_time', 'desc')->get();
        } elseif ($user_role == 'clientuser') {
            $org_id = get_user_org('clientorg', 'org_id');
            $todoactivities = loc_request::whereBetween('created_time',[$sdate.' 00:00:00',$edate.' 23:59:59'])->whereIn('request_status', ['new', 'approve', 'tr_assign', 'tr_inprogress', 'qa_assign', 'qa_inprogress', 'qa_accept', 'qa_reject', 'pr_assign', 'pr_inprogress', 'pr_accept', 'pr_accept', 'publish', 're_accept', 're_reject', 'client_cancel'])->where('client_org_id', $org_id)->orderBy('created_time', 'desc')->get();
        }  elseif ($user_role == 'requester') {
            $org_id = get_user_org('clientorg', 'org_id');
            //$todoactivities = loc_request::whereBetween('created_time',[$sdate.' 00:00:00',$edate.' 23:59:59'])->whereIn('request_status', ['new', 'approve', 'tr_assign', 'tr_inprogress', 'qa_assign', 'qa_inprogress', 'qa_accept', 'qa_reject', 'pr_assign', 'pr_inprogress', 'pr_accept', 'pr_accept', 'publish', 're_accept', 're_reject', 'client_cancel'])->where('client_org_id', $org_id)->orderBy('created_time', 'desc')->get();
            $todoactivities = loc_request::whereBetween('created_time',[$sdate.' 00:00:00',$edate.' 23:59:59'])->where(['user_id' => $userid])->orderBy('created_time', 'desc')->get();
        } else {
            $todoactivities = array();
        }
        return view('admin.request.todoactivities', compact('todoactivities', 'loc_request', 'get_user','req_status','sdate','edate'))->with(['page_title'=>'Projects']);
    }
    // public function request_change_tm_status(Request $request)
    // {
    //     if (! checkpermission('request_todo_activities')) {
    //         return abort(401);
    //     }
    //     $userid=Auth::user()->id;
    //     $reference_id=$request['reference_id'];
    //     $tm_status=$request['tm_status'];
    //     if($reference_id != '' && $tm_status !=''){
    //         loc_request::where(['reference_id'=>$reference_id])->update(['tm_status'=>$tm_status]);
    //         Session()->flash('message', 'TM Status successfully updated');
    //     }else{
    //         Session()->flash('error_message', 'TM Status not updated');
    //     }
    // }
    public function request_change_request_status(Request $request)
    {
        if (!checkpermission('request_todo_activities')) {
            return abort(401);
        }
        
        $date=date("Y-m-d H:i:s");
        $userid = Auth::user()->id;
        $reference_id = $request['reference_id'];
        $request_status = $request['request_status'];
        $assign_data = $request['assign_data'];
        $assign_work_per=$request['assign_work_per'];
        $assign_gst=$request['assign_gst'];
         //print_r($assign_gst);die;
        $assign_amount_per=$request['assign_amount_per'];
        if ($reference_id != '' && $request_status != '') {
            $ref_id=loc_request::where('req_id',$reference_id)->first();
            $refid=$ref_id->reference_id;
            $getdata=loc_translation_master::where('quote_code',$refid)->first();
           //print_r($getdata);die;
            $useremail=$getdata->email ?? '';
            //echo $useremail;die;
            loc_request::where(['req_id' => $reference_id])->update(['request_status' => $request_status]);
            /*$assign_data = explode(',', $assign_data);
            $assign_work_per=explode(',',$assign_work_per);
            $assign_amount_per=explode(',',$assign_amount_per);
            $assign_gst=explode(',',$assign_gst);*/
            if($request_status=='publish'){
                loc_request::where(['req_id' => $reference_id])->update(['publish_date' =>date('Y-m-d H:i:s')]);   
            }
            
            if($request_status=='approve'){
                $vendors_list=loc_targetfile::where(['request_id'=>$ref_id->req_id])->get();
                foreach ($vendors_list as $vl) {
                    if($vl->tr_id != ''){
                        $assign_id=$vl->tr_id;
                    }elseif($vl->v_id != ''){
                        $assign_id=$vl->v_id;
                    }else{
                        $assign_id='';
                    }
                    if($assign_id != ''){
                        $vendor_invoice_data = array( 
                            'user_id' => $assign_id,
                            'req_id' => $ref_id->req_id,
                            'pending' => $vl->v_total,
                            'desc' => 'Booking Amount for: '.$ref_id->reference_id, 
                            'created_by' => $userid,   
                            'created_at' => date('Y-m-d H:i:s'));
                        vendor_invoice_details::insert($vendor_invoice_data);
                    }
                }
            }



            if($useremail){
            $pm_email=getusernamebyid($getdata->pm_id,'email');
            $sales_email=getusernamebyid($getdata->translation_user_id,'email');
            $email =[$pm_email];
            $ccemail =[$sales_email];
            $mailData = [
                'title' => ucwords(showcrstatus($request_status)).' - Transflow',
                'subject' => ucwords(showcrstatus($request_status)),
                'req_id' =>$refid,
                'date' => $date,
                'created_by' =>getusernamebyid($userid),
                'req_url' => env('APP_URL').'admin/requestupdate/'.$refid//route('admin.request.requestupdate',['refid'=>$refid]) 
            ];
            try{
                $res=sendstatusmail($email,$mailData,$ccemail);
            }catch (exception $e) {
                createlog('error',$e->getMessage());
            }

                
            }
            createlog('change_request_status','Project ID:'.$refid.' status changed to '.ucwords(showcrstatus($request_status)),$reference_id);
            Session()->flash('message', 'Request Status successfully updated');
          }else{
            Session()->flash('error_message', 'Request Status not updated');
        }
    }
    public function get_request_assign_data(Request $request)
    {
        if (!checkpermission('request_todo_activities')) {
            return abort(401);
        }
        $loc_request = new loc_request();
        $userid = Auth::user()->id;
        $reference_id = $request['reference_id'];
        $request_status = $request['request_status'];
        $org_id_req = $loc_request::where('req_id', $reference_id)->select('organization_id')->first();
        $org_id_req = $org_id_req->organization_id;
        $role_type = '';
        $users = array();
        if ($request_status == 'tr_assign') {
            $role_type = 'translator';
            $users = user::select('id', 'name')->whereHas(
                'roles',
                function ($q) {
                    $q->where('name', 'translator');
                }
            )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id_req)->get();
        }elseif($request_status == 'v_assign'){
            $role_type='vendor';
            $users = user::select('id','name')->whereHas(
            'roles', function($q){
            $q->where('name', 'vendor');
            })->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id',$org_id_req)->get();
         }elseif ($request_status == 'qa_assign') {
            $role_type = 'qualityanalyst';
            $users = user::select('id', 'name')->whereHas(
                'roles',
                function ($q) {
                    $q->where('name', 'qualityanalyst');
                }
            )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id_req)->get();
        } elseif ($request_status == 'pr_assign') {
            $role_type = 'proofreader';
            $users = user::select('id', 'name')->whereHas(
                'roles',
                function ($q) {
                    $q->where('name', 'proofreader');
                }
            )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id_req)->get();
        }

        $options = '<option value="">Select User</option>';
        foreach ($users as $user) {
            $options .= '<option value="' . $user['id'] . '">' . $user['name'] . '</option>';
        }


        // $source_lang = $loc_request->loc_reference_lang_select($reference_id);
        // $target_lang = $loc_request->loc_reference_lang_select($reference_id, 'target');
        $sl_tl = $loc_request->loc_reference_lang_select($reference_id, 'sl_tl');
        $i = 0;
        $out_data = '';
        foreach ($sl_tl as $sl){
            $out_data .='<tr><td>'.$sl->source_lang_name .' - '. $sl->target_lang_name.'</td><td><select  class="form-control select2 translators_list_select"  id="translators_list_select" name="translators_list_select" required>'.$options.'</select>'.(($request_status == 'v_assign') ? '<input type="number" name="work_per[]" min="1" max="100" class="form-control" required placeholder="Please enter work %"/><input type="number" name="amount_per_vendor[]" min="0.1" class="form-control" required placeholder="Please enter amount"/><select name="assign_gst[]" class="form-control select2 ><option value="">Select GST</option><option value="gst">GST</option><option value="sgst">SGST</option><option value="cgst">CGST</option><option value="no_gst">No GST</option></select>' : '').'</td></tr>';
            $i++;
        }
        echo $out_data;
    }
    // public function update_req_status_approver(Request $request){
    //     $userid=Auth::user()->id;
    //     $reference_id=$request['reference_id'];
    //     $status_request=$request['approval'];
    //     if($status_request !=''){
    //         loc_request::where(['reference_id'=>$reference_id]);
    //         Session()->flash('message', 'Status successfully updated');
    //     }else{
    //         Session()->flash('error_message', 'Request Status not updated');
    //     }

    // }

    public function requestupdate($reference_id)
    {
        if (!checkpermission('request_todo_activities', 'update')) {
            return abort(401);
        }
        $userid = Auth::user()->id;
        $user_role = Auth::user()->roles[0]->name;
        $edit_request    = loc_request::where('reference_id', $reference_id)->first();
        $client_org = clientorganization::where(["org_id"=>$edit_request->client_org_id])->first();
        // $get_tr = locrequestassigned::select('users.name', 'users.id', 'loc_request_assigned.tr_status', 'loc_request_assigned.tr_assigned_date')->join('users', 'users.id', 'loc_request_assigned.tr_id')->where('request_id', $edit_request->req_id)->get();
    //   
    //
    //   $client_name=$client_org->org_name;
    $where_status=$edit_request->req_id;
        $vendor_invoice_history=vendor_invoice_details::where('req_id',$where_status)->get();
   // $vendor_invoice_list=loc_invoice::where(['req_id'=>$edit_request->req_id,'vendor_id'=>$userid,'invoice_type'=>'vendor'])->get();
  
   $vendor_invoice_list=loc_invoice::where(['req_id'=>$edit_request->req_id,'vendor_id'=>$userid,'invoice_type'=>'vendor'])->get();
    
    $where_status ='(v_id != "" OR tr_id != "")';
    $vendors_list=loc_targetfile::where(['request_id'=>$edit_request->req_id])->whereRaw($where_status)->groupBy(['v_id','tr_id'])->get();

     // echo "<pre>";
   //print_r($vendors_list);die; 
   $client_users = User::whereHas(
          'roles', function($q) {
              $q->whereIn('name', ['clientuser']);
          }
      )->join('client_user_orgizations', 'users.id', '=', 'client_user_orgizations.user_id')->where('client_user_orgizations.org_id',$edit_request->client_org_id)->first();
       
    
  

    $arr_sales_quote_generation_details =  loc_translation_master::where('quote_code', $reference_id)
    ->join('loc_request_assigned', 'loc_request_assigned.quote_id', 'loc_translation_qoute_generation_master.translation_quote_id')->first();
   
 
   //$fullname= $arr_sales_quote_generation_details->first_name ." ".$arr_sales_quote_generation_details->last_name;
 
      $get_user = new User();
        if ($edit_request) {

            $loc_request = new loc_request();
            //$loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
            $edit_request_lang    = DB::table('loc_request_assigned')->where('request_id', $edit_request->req_id)->get();
            // $request_comments    = DB::table('loc_update_request')
            //     ->join('users', 'loc_update_request.user_id', '=', 'users.id')->select('loc_update_request.*', 'users.name as user_name')->orderBy('updated_request_id', 'DESC')->where('reference_id', $reference_id)->get();
          /// echo "<pre>";
         //print_r($edit_request_lang);die;
            // $linguist_list    = DB::table('user_orgizations')
            //     ->join('users', 'user_orgizations.user_id', '=', 'users.id')->select('users.id', 'users.name as user_name')->orderBy('name', 'ASC')->where('user_orgizations.org_id', '5')->get();
            return view('admin.request.requestupdate', compact('edit_request', 'get_user', 'loc_request','client_org','client_users','arr_sales_quote_generation_details','vendor_invoice_history','vendors_list'))->with(['page_title'=>'Project Details - '.$reference_id]);
        } else {
            return abort(404);
        }
    }
    public function todo_requestcomments(Request $request)
    {
        if (!checkpermission('request_todo_activities')) {
            return abort(401);
        }
        $post = $request;
        $userid = Auth::user()->id;
        $reference_id           = $post['reference_id'];
        $source_type            = $post['source_type'];
        $source_text            = $post['source_text'];
        $source_date            = $post['delivery_date'];
        $comments               = addslashes($post['comments']);
        $source_file            = $_FILES['source_file'];
        $soucefilestatus        = 1;
        $source_file_path       = NULL;
        $request_status         = $post['request_status'];
        $No_words               = $post['no_words'];
        $No_pages               = $post['no_pages'];
        $request_task           = $post['request_task'];
        $assigned_linguist      = $post['assigned_linguist'];



        $file = $request->file('source_file');
        if ($file != "") {
            $file_name = $request->file('source_file')->getClientOriginalName();
            $source_file = $request->file('source_file')->getRealPath();
            $source_file_extension = strtolower($request->file('source_file')->getClientOriginalExtension());
            $supported_image = array('zip', 'zip', 'tar', 'gz', 'rar', 'doc', 'docx', 'xls', 'apk', 'xlsx', 'jpeg', 'jpg', 'png', 'rtf', 'html', 'xml', 'pdf', 'ppt', 'txt', 'PPT', 'pptx', 'PPTX', 'mp3', 'mp4', 'avi', 'flv', 'wmv', 'mov', 'xliff', 'json');
            if (in_array($source_file_extension, $supported_image)) {
                $path = base_path() . "/public/storage/request/comments/";
                /*if (!File::isDirectory($path)) {
                    $some =  File::makeDirectory($path, 0777, true);
                }*/
                $path_save = "public/request/comments/";
                $source_file_path = $upload_file_name = $reference_id . '_' . time() . '.' . $source_file_extension;
                if (!file_exists("$path/$upload_file_name")) {
                    $file->move($path, $upload_file_name);
                }
            }
        } else {
            $source_file_path = NULL;
        }
        if ($soucefilestatus == 1) {

            $created_time = date("Y-m-d H:i:s");
            if ($source_date != "")
                $deliverydate = date('Y-m-d H:i:s', strtotime($source_date));
            else
                $deliverydate = '0000-00-00 00:00:00';

            $source_file_path = $source_file_path;

            /* insert into database  */
            $arr_requests = array('request_status' => $request_status, 'request_date' => $source_date, 'no_words' => $No_words, 'no_pages' => $No_pages, 'request_task' => $request_task);
            $req = loc_request::where(['reference_id' => $reference_id])->update($arr_requests);

            $arr_subrequests = array('reference_id' => $reference_id, 'user_id' => $userid, 'source_type' => $source_type, 'source_text' => $source_text, 'source_file_path' => $source_file_path, 'special_instructions' => $comments, 'created_time' => $created_time);
            $req = loc_request::loc_sub_request_insert($arr_subrequests);
            if ($assigned_linguist != '') {
                $arr_linguists = array('reference_id' => $reference_id, 'user_id' => $assigned_linguist);
                $req = loc_request::loc_request_linguist_insert($arr_linguists);
            }
        }
        Session()->flash('message', 'Request successfully updated');
        return redirect()->route('admin.request.show', [$reference_id]);
    }
    public function editrequest($id)
    {  
       
        $getreq=loc_request::where('reference_id',$id)->first();

   
        $getst_lang=locrequestassigned::where('quote_id',$getreq->quote_gen_id)->get();
     
         
      
        $service=locQuoteService::where('quote_id',$getreq->quote_gen_id)->join('loc_service','loc_service.id','loc_quote_service.service_type')->get();
  
        $source_lang=locQuoteSourcelang::where('quote_id',$getreq->quote_gen_id)->first();
       
        $user_role = Auth::user()->roles[0]->name;
        $quote= $loc_master =new loc_translation_master();
        $loc_multiple_file =new loc_multiple_file();
        $get_quote_data = loc_translation_master::where('translation_quote_id', $getreq->quote_gen_id)->first();
 
        $currency_list = currencies::where('status','Active')->get();
       
        $clientorganizations =[];
        if($get_quote_data){
            
            $clientorganizations = Clientorganization::where(['org_status' => '1', 'kpt_org' => $get_quote_data->organization])->get();
          //  echo "<pre>";print_r($clientorganizations);die;
        }
        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();

 //   

    $org_id = get_user_org('org', 'org_id');      
 $vendors = user::select('id', 'name')->whereHas(
    'roles',
    function ($q) {
        $q->whereIn('name', ['translator','vendor']);
    }
)->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->get();

//echo "<pre/>";print_r($vendors);die;

        return view('admin.request.editrequest', compact('get_quote_data','service','source_lang', 'loc_languages', 'clientorganizations','getst_lang','getreq','loc_master','loc_multiple_file','vendors','currency_list'))->with(['page_title'=>'Project Edit - '.$id]);;
    }
    public function deletefile($id)
    {
        if (!checkpermission('create_request', 'delete')) {
            return abort(401);
        }
       // echo "hai";die;
        $file_delete = loc_multiple_file::findOrFail($id);
        $created_at=$file_delete->created_at;
        $sfile_date=date('Ymd',strtotime($created_at));
        $filePath = 'request/source/'.$sfile_date.'/' . $file_delete->file_name;
        Storage::disk('s3')->delete($filePath);
        $file_delete->delete();

        //$file_delete = loc_multiple_file::where(['id'=>$id])->delete();
        // $path = base_path() . "/public/storage/request/{$file_delete->file_name}";
        // if (File::exists($path)) {
        //     unlink($path);
        // }
        // $file_delete->delete();
        return redirect()->back();
    }


    // public function delete_files($id){
    //     $details=personal_details::where(['id'=>$id])->delete();
    //     return redirect()->back();
    // }
   
    public function getclientinvoicedata()
    {
        $id = $_REQUEST['id'];
        $user = Auth::user()->roles[0]->name;
        $loc_get = loc_request::where('reference_id', $id)->first();
        if($loc_get){
            $where_status=['org_id'=>$loc_get->organization_id,'clientorg'=>$loc_get->client_org_id,'req_id'=>$loc_get->req_id];
            $sum_of_pending=clientorg_invoice_details::where($where_status)->sum('pending');
            $sum_of_paid=clientorg_invoice_details::where($where_status)->sum('paid');
            $billed_amount=loc_invoice::where(['req_id'=>$loc_get->req_id,'invoice_type'=>'client'])->sum('invoicing_amount');
            //$total=$sum_of_pending-$sum_of_paid-$billed_amount;
            $total=$sum_of_pending-$billed_amount;
            echo $total;
            die;
        }
        echo 0;
    }
    public function getvendorinvoicedata()
    {
        $id = $_REQUEST['id'];
        $user_id = $_REQUEST['user_id'];
        $user = Auth::user()->roles[0]->name;
        $loc_get = loc_request::where('reference_id', $id)->first();
        if($loc_get){
            $where_status=['user_id'=>$user_id,'req_id'=>$loc_get->req_id];
            $sum_of_pending=vendor_invoice_details::where($where_status)->sum('pending');
            $sum_of_paid=vendor_invoice_details::where($where_status)->sum('paid');
            $billed_amount=loc_invoice::where(['req_id'=>$loc_get->req_id,'vendor_id'=>$user_id,'invoice_type'=>'vendor'])->sum('invoicing_amount');
            $total=$sum_of_pending-$billed_amount;
            echo $total;
            die;
        }
        echo 0;
    }
    public function getinvoicedata()
    {
        $id = $_REQUEST['id'];
        $user = Auth::user()->roles[0]->name;
        $loc_get = loc_invoice::where('id', $id)->first();
        if($loc_get){
            if($loc_get->invoice_type == 'client'){
                $sum_of_paid=clientorg_invoice_details::where(['invoice_id'=>$id])->sum('paid');
            }else{
                $sum_of_paid=vendor_invoice_details::where(['invoice_id'=>$id])->sum('paid');
            }
            $inv_amount=$loc_get->invoicing_total-$sum_of_paid;
            $options='';
            /*$options ='<option>Select Option</option>';
            $options .='<option value="partial_payment">Partial Payment</option>';
            $options .='<option value="full_paid">Full Payment</option>';*/
            $where=['main_amount'=>$loc_get->invoicing_total,'amount'=>$inv_amount,'options'=>$options,'status'=>1];
        }else{
            $where=['status'=>0];
        }
        echo json_encode($where);
    }
    public function submitinvoice(Request $request)
    {
   
        $validated =$request->validate([
             'invoice_no'=>['required', Rule::unique('loc_invoice')],
             'invoice_date'=>'required',
             'payment_amount' => 'required',
             'total_payment_amount' => 'required',
             'gst' => 'required',
        ]);
      //  echo "<pre/>";    print_r($_REQUEST);die;
        $userid = Auth::user()->id;
        $array_d['req_id'] = $request['req_id'];
        $array_d['invoicing_amount'] = $request['payment_amount'];
        $array_d['invoicing_total'] = $request['total_payment_amount'];
         $array_d['invoice_type'] = $request['invoice_type'];
        $array_d['invoice_no']=$request['invoice_no'];
     
        $array_d['gst_type']=$request['gst'];
       
        $array_d['invoice_date']=$request['invoice_date'];
        $quote_amnt_status=loc_request::where('req_id',$array_d['req_id'])->first();
        
        if($array_d['invoice_type'] == 'vendor'){
            $array_d['vendor_id'] = $request['vendor_id'];
        }else{
            $array_d['invoice_type'] = 'client';
        }
        if($array_d['invoice_type']=='client'){
            if($array_d['invoicing_total']==0){
                $status="full_paid";
            }else{
                $status="partial_payment";
            }
            $status_amnt=loc_translation_master::where('translation_quote_id',$quote_amnt_status->quote_gen_id)->update(['client_amnt_status'=>'billed']);
        }elseif($array_d['invoice_type']=='vendor'){
            if($array_d['invoicing_total']==0){
                $status="full_paid";
            }else{
                $status="partial_payment";
            }
            $status_amnt=loc_translation_master::where('translation_quote_id',$quote_amnt_status->quote_gen_id)->update(['vendor_amnt_status'=>'billed']); 
        }
       
        $array_d['invoice_status']=$status;
        $array_d['created_by']=Auth::user()->id;
        $array_d['created_at']=date('Y-m-d H:i:s');
// print_r($array_d);die;
        $response=loc_invoice::insert($array_d);
      
        $invoice_id = DB::getPdo()->lastInsertId();
      
        $user= Auth::user()->roles[0]->name;
        if($array_d['gst_type']=="igst"){
            $gst=" IGST 18%";
        }elseif($array_d['gst_type']=="both"){ 
            $gst="SGST= 9% & CGST= 9%";
        }else{
            $gst='';
        }
        $invoicno=$array_d['invoice_no'];
        $quote_id=$quote_amnt_status->quote_gen_id;
         // print_r($quote_id);die;
         $req= $array_d['req_id'];
         $po_no=$request['po_no'];
        if($response){

            $invoiceno= $array_d['invoice_no'];
            $invoicedate=date("Ymd");
            if ($_FILES['upload_invoice']['name'] != "") {
                //Source File upload
                $file_name4 = $_FILES['upload_invoice']['name'];     //file name
                $file_size4 = $_FILES['upload_invoice']['size'];     //file size
                $file_temp = $_FILES['upload_invoice']['tmp_name']; //file temp 
                $ext4 = strtolower(pathinfo($file_name4, PATHINFO_EXTENSION));
                $act_filename = $invoiceno.'.'.$ext4;
                if($array_d['invoice_type']=='client'){
                    $filePath = 'invoices/client/'.$invoicedate .'/'.$act_filename;
                }elseif($array_d['invoice_type']=='vendor'){
                    $filePath = 'invoices/vendor/'.$invoicedate .'/'.$act_filename; 
                }
                $file = $request->file('upload_invoice');
                $res= Storage::disk('s3')->put($filePath, file_get_contents($file));

                if($res>0){
                    $filepath=loc_invoice::where(['invoice_no'=>$invoicno])->update(['invoice_file_path' => $act_filename]);
                }

            }

            if (isset($_FILES['uploads_purches']['name']) && $_FILES['uploads_purches']['name'] != "") {
                //Source File upload
                $file_name5 = $_FILES['uploads_purches']['name'];     //file name
                $file_size45 = $_FILES['uploads_purches']['size'];     //file size
                $file_temp1 = $_FILES['uploads_purches']['tmp_name']; //file temp 
                $ext5 = strtolower(pathinfo($file_name5, PATHINFO_EXTENSION));
                $act_filename1 = $po_no.'_'.time().'.'.$ext5;
                if($array_d['invoice_type']=='client'){
                    $filePath2 = 'po_order/client/'.$invoicedate .'/'.$act_filename1;
               
             }elseif($array_d['invoice_type']=='vendor'){
                    $filePath2 = 'po_order/vendor/'.$invoicedate .'/'.$act_filename1; 
             }
                
                $file2 = $request->file('uploads_purches');
                $res= Storage::disk('s3')->put($filePath2, file_get_contents($file2));
              
                if($res>0){
                      $data= [
                        'invoice_id'=>$invoice_id,
                        'quote_id'=>$quote_id,
                        'po_file_path'=>$act_filename1,  
                        'created_by'=>$userid,
                        'po_order_no'=>$po_no];    
                       //   print_r($data);    
                    $filepath2=loc_po::insert($data);
                }
            }
        

            $pmorg=get_user_org('org','org_id');
            $authenticated_users = User::whereHas(
                'roles', function($q) {
                    $q->where('name','orgadmin');
                }
            )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id',$pmorg)->get();
            $orgadmin=$authenticated_users->toArray();
            $orgadmin_emails=array_column((array)$orgadmin,'email');
            $reference_id=gettabledata('loc_request','reference_id',['req_id'=>$array_d['req_id']]);
            $get_pmid=loc_translation_master::where('quote_code',$reference_id)->first();
            $pmid=$get_pmid->pm_id;
            $financename=getusernamebyid($userid,'name');
            $pm_email=getusernamebyid($pmid,'email');
             $client_org=loc_request::where('req_id',$array_d['req_id'])->select('loc_request.client_org_id')->first();
             $client=$client_org->client_org_id;
             $client_users = User::whereHas(
                'roles', function($q) {
                    $q->where('name','clientuser');
                }
            )->join('client_user_orgizations', 'users.id', '=', 'client_user_orgizations.user_id')->where('client_user_orgizations.org_id',$client)->first();
            if($client_users != ''){
            $client_user=$client_users->id;
            $clients=getusernamebyid($client_user,'name');
              $email=$pm_email;//getusernamebyid($client_user,'email');
              $ccemail=[];
               //$ccemail = array_merge($orgadmin_emails,$ccemail);
               $mailData = [
                   'title' =>'Uploaded Invoice',
                   'subject' =>'Invoice for '.$reference_id. ' Id',
                   'client_name'=>ucwords($clients),
                   'client_email'=>$email,
                   'invoice_no'=>$invoicno,
                   'invoicing_amnt'=>$array_d['invoicing_amount'],
                   'gst'=>$gst,
                   'total_amnt'=>$array_d['invoicing_total'],
                   'pymnt_type'=>$array_d['invoice_status'],
                   'date' =>$array_d['created_at'],
                   'created_by'=>$financename,
                //    'request_id' => route('admin.request.assigntotranslator',[$reference_id]) 
               ];
                try{
                    $res=clientinvoicemail($email,$mailData,$ccemail);
                }catch (exception $e) {
                    createlog('error',$e->getMessage());
                }
            }
        createlog('upload_invoice','Uploaded Invoice:'.$invoicno.' for the project: '.$quote_amnt_status->reference_id,$invoice_id,'loc_invoice','invoice');
        }else{
            $res['status']=0;
            $res['message']="Invoice not created";
        }
    
        
        Session()->flash('message', 'Request invoice   successfully updated');
        //return $pdf->download($act_filename);
       return redirect()->route('admin.request.requesttransaction',['refid'=>$quote_amnt_status->reference_id]);
      //  return redirect()->route('requesttransaction');
       //  return redirect()->back();
    }
    public function updateinvoicestatus()
    {
        $userid = Auth::user()->id;
        $invoice_id = $_REQUEST['invoice_id'];
        $invoicing_amount = $_REQUEST['invoicing_amount'];
      
     
        //$invoice_status = $_REQUEST['invoice_status'];
        $comments = $_REQUEST['comments'];
        $loc_get = loc_invoice::where('id', $invoice_id)->first();
        if($loc_get){
            if($loc_get->invoice_type == 'client'){
                $sum_of_paid=clientorg_invoice_details::where(['invoice_id'=>$invoice_id])->sum('paid');
            }else{
                $sum_of_paid=vendor_invoice_details::where(['invoice_id'=>$invoice_id])->sum('paid');
            }
            $inv_amount=$loc_get->invoicing_total-$invoicing_amount-$sum_of_paid;
            if($inv_amount == 0){
                $status='full_paid';

            }else{
                $status='partial_payment';
            }
            $inv_pay=['invoice_status'=>$status];
            $res=loc_invoice::where('id',$invoice_id)->update($inv_pay);
            if($res){
                $loc_req = loc_request::where('req_id', $loc_get->req_id)->first();
                $pay_invoice_data = array( 
                        'invoice_id' => $invoice_id,
                        'req_id' => $loc_req->req_id,
                        'paid' => $invoicing_amount,
                        'desc' => 'Paid amount for the Invoice No:'.$loc_get->invoice_no,  
                        'created_by' => $userid,   
                        'created_at' => date('Y-m-d H:i:s')
                        );
                if($loc_get->invoice_type == 'client'){
                    $pay_invoice_data['org_id'] = $loc_req->organization_id;
                    $pay_invoice_data['clientorg'] = $loc_req->client_org_id;
                    $pay_invoice_data['clientorg'] = $loc_req->client_org_id;
                    clientorg_invoice_details::insert($pay_invoice_data);

                    $where_status=['org_id'=>$loc_req->organization_id,'clientorg'=>$loc_req->client_org_id,'req_id'=>$loc_req->req_id];
                    //$invoice_history=clientorg_invoice_details::where($where_status)->get();
                    $sum_of_pending=clientorg_invoice_details::where($where_status)->sum('pending');
                    $sum_of_paid=clientorg_invoice_details::where($where_status)->sum('paid');
                    //$grand_total=$sum_of_pending-$sum_of_paid;
                    if($sum_of_pending == $sum_of_paid){
                        $status_amnt=loc_translation_master::where('translation_quote_id',$loc_req->quote_gen_id)->update(['client_amnt_status'=>'paid']);
                    }
                }else{
                    $pay_invoice_data['user_id'] = $loc_get->vendor_id;
                    vendor_invoice_details::insert($pay_invoice_data);
                   
                    $where_status=['req_id'=>$loc_req->req_id];
                    //$vendor_invoice_history=vendor_invoice_details::where($where_status)->get();
                    $vendor_sum_of_pending=vendor_invoice_details::where($where_status)->sum('pending');
                    $vendor_sum_of_paid=vendor_invoice_details::where($where_status)->sum('paid');
                    //$vendor_grand_total=$vendor_sum_of_pending-$vendor_sum_of_paid;  
                    if($vendor_sum_of_pending == $vendor_sum_of_paid){
                        $status_amnt=loc_translation_master::where('translation_quote_id',$loc_req->quote_gen_id)->update(['vendor_amnt_status'=>'paid']);
                    }
                }
                $kpt_org=get_user_org('org','org_id');
            $client_comp=gettabledata('clientorganizations','org_name',['org_id'=> $loc_req->client_org_id]);
           
          $orgadmins = User::whereHas(
          'roles', function($q) {
            $q->where('name','orgadmin');
          }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id',$kpt_org)->get();
          $orgadmin=$orgadmins->toArray();
        $orgadmin_emails=array_column((array)$orgadmin,'email');
           
       $client=client_user_orgizations::join('users','client_user_orgizations.user_id','users.id')->where('client_user_orgizations.org_id',$loc_req->client_org_id)->first();
       $client_name=$client->name;
       $client_email=$client->email;
            

   $pm=loc_translation_master::where('quote_code',$loc_req->reference_id)->first();
   $pm_id=$pm->pm_id;
   $pm_email=getusernamebyid($pm_id,'email');
   $f_email=getusernamebyid($userid,'email');
   $fname=getusernamebyid($userid,'name');
   $email_m =[$f_email];
   $ccemail =[$pm_email];
   $ccemail = array_merge($orgadmin_emails,$ccemail);
   $mailData = [
       'title' =>'Payment Invoice',
       'subject' =>'Payment Done for Quote  '.$loc_req->reference_id. ' Id',
       'c_comp_name'=>ucwords($client_comp),
       'client_name'=>ucwords($client_name),
       'client_email'=>$client_email,
       'invoice_no'=>$loc_get->invoice_no,
       'invoicing_amnt'=>$invoicing_amount,
       'total_amnt'=>$loc_get->invoicing_total,
       'pymnt_type'=>$status,
       'date' => date('Y-m-d H:i:s'),
       'created_by' =>ucwords($fname),
    //    'quote_url' => route('admin.quotegeneration.editquote',['quote_code'=>$order_id]) 
   ];
    try{
        $res=clientpmntinvoice($email_m,$mailData,$ccemail);
    }catch (exception $e) {
        createlog('error',$e->getMessage());
    }
        createlog('update_invoice','Invoice NO.:'.$loc_get->invoice_no.' updated for the project: '.$loc_req->reference_id,$invoice_id,'loc_invoice','invoice');
                $where=['status'=>1,'msg'=>"Successfully Updated"];
            }
            else{
                $where=['status'=>0,'msg'=>"Data Not Updated"];
            }
        }else{
            $where=['status'=>0,'msg'=>"Something went wrong"];
        }

        echo json_encode($where);
    }
    public function add_comments(Request $request)
    {
        $comments           = $request['comment'];
        $status             = $request['status'];
        $target_file        = $request['target_file'];
        $get_file_id        = $request['get_file_id'];
        $target_lang_id     = $request['target_file_id'];
        $created_time   = date("Y-m-d H:i:s");
        // print_r($get_file_id); // req_lang_id
        // print_r($target_lang_id);die; //  target_lang_id
        
        $date=date('Y-m-d H:i:s');
        $userid = Auth::user()->id;
        // print_r($userid);die;
        $user_role = Auth::user()->roles[0]->name;
        $source_types = loc_multiple_file::where('id',$get_file_id)->first();
        $req_id=$source_types->request_id;
        // $s_lang=$source_types->source_language;
        $loc_get_status = loc_request::where('req_id', $req_id)->select('request_status')->first();
      
        if ($req_id > 0) {
            $commtes_save=new loc_request_comment();
            $commtes_save->comment= $comments;
            $commtes_save->request_id= $req_id;
            $commtes_save->req_lang_id= $target_lang_id;
            $commtes_save->req_file_id=$get_file_id;
            $commtes_save->created_time= $created_time;
            $commtes_save->user_id= $userid;
        //  echo "<pre/>";  print_r($commtes_save);die;
            $commtes_save->save();
            $commentid = $commtes_save->id;
            $target_file_data=loc_targetfile::where(['req_lang_id' => $target_lang_id, 'request_id' => $req_id, 'req_file_id' =>$get_file_id])->first();
           // echo "<pre/>"; print_r($target_file_data);die;
                if ($user_role=="translator" || $loc_get_status['request_status'] == 'tr_assign' || $loc_get_status['request_status'] == 'tr_inprogress') {
                loc_targetfile::where('id', $target_file_data->id)->update(['tr_status' => $status]);
                }elseif ($user_role=="vendor" || $loc_get_status['request_status'] == 'v_assign' || $loc_get_status['request_status'] == 'v_inprogress') {
                    loc_targetfile::where('id',$target_file_data->id)->update(['v_status' => $status]);
                } elseif ($user_role=="qualityanalyst" || $loc_get_status['request_status'] == 'qa_assign' || $loc_get_status['request_status'] == 'qa_inprogress') {
                    loc_targetfile::where('id', $target_file_data->id)->update(['qa_status' => $status]);
                } elseif ($user_role=="proofreader" || $loc_get_status['request_status'] == 'pr_assign' || $loc_get_status['request_status'] == 'pr_inprogress') {
                    loc_targetfile::where('id',$target_file_data->id)->update(['pr_status' => $status]);
                }

              
                if ($source_types->source_type == 'file') {

                    $file = $target_file ?? '';
                    if ($file) {
                        $tfiledate=date("Ymd");
                        $file_name = $comment_file_name = $file->getClientOriginalName();
                        $original_t_file=$file_name.'_'.time();
                       
                        $source_file = $file->getRealPath();
                        $source_file_extension = strtolower($file->getClientOriginalExtension());
                        $supported_image = array('zip', 'zip', 'tar', 'gz', 'rar', 'doc', 'docx', 'xls', 'apk', 'xlsx', 'jpeg', 'jpg', 'png', 'rtf', 'html', 'xml', 'pdf', 'ppt', 'txt', 'PPT', 'pptx', 'PPTX', 'mp3', 'mp4', 'avi', 'flv', 'wmv', 'mov', 'xliff', 'json');
                         if (in_array($source_file_extension, $supported_image)) {
                        $tfile_path=base_path() . "/public/storage/request/target/".$tfiledate;
                            $multipleFile = array('request_id' => $req_id,'req_lang_id' => $target_lang_id);
                            if ($target_file_data){
                                $file_id=$target_file_data->id;
                                //$multipleFiles = loc_targetfile::where(['id' => $target_file_data->id])->update($multipleFile);
                            } else {
                                $multipleFiles = loc_targetfile::insert($multipleFile);
                               //  print_r($multipleFiles);die;
                                $file_id=$multipleFiles;
                            }
                             
                                 $path_save =$tfile_path;
                                $source_file_path = $upload_file_name = time() . '_' . $req_id . '_' . $file_id . '.' . $source_file_extension;
                                // echo $source_file_path;die;
                                   $multipleFile = array('target_file' => $source_file_path,'original_t_file'=>$original_t_file,'created_at'=>$created_time);
                                    $multipleFiles = loc_targetfile::where(['id' => $file_id])->update($multipleFile);
                                     

                                $name =   $upload_file_name;
                                $filePath = 'request/target/'.$tfiledate.'/' . $name;
                                $res= Storage::disk('s3')->put($filePath, file_get_contents($file));

                                $c_name=$commentid.'.'.$source_file_extension;
                                $filePath = 'request/comments/'.$tfiledate.'/' . $c_name;
                                $res= Storage::disk('s3')->put($filePath, file_get_contents($file));

                               loc_request_comment::where('id',$commentid)->update(['target_file_id' => $file_id,'file_name' => $comment_file_name]); 
                        }
                    }
                }

        }
        $gettarget_lang=locrequestassigned::join('loc_quote_sourcelang','loc_request_assigned.loc_source_id','=','loc_quote_sourcelang.id')->where('loc_request_assigned.id',$target_lang_id)->select('loc_quote_sourcelang.sourcelang_id','loc_request_assigned.target_language')->first();
        $s_lang=$gettarget_lang->sourcelang_id;
        $t_lang=$gettarget_lang->target_language;
        $pmorg=get_user_org('org','org_id');
        $authenticated_users = User::whereHas(
            'roles', function($q) {
                $q->where('name','orgadmin');
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id',$pmorg)->get();
        $orgadmin=$authenticated_users->toArray();
        $orgadmin_emails=array_column((array)$orgadmin,'email');
        $s_langs=getlangbyid($s_lang,'lang_name');
        $t_langs=getlangbyid($t_lang,'lang_name');
        $reference_id=gettabledata('loc_request','reference_id',['req_id'=>$req_id]);
        $get_pmid=loc_translation_master::where('quote_code',$reference_id)->first();
        $pmid=$get_pmid->pm_id;
        $pm_email=getusernamebyid($pmid,'email');
        $vt_email=getusernamebyid($userid,'email');
        $vt_name=getusernamebyid($userid,'name');
        $status;
        //print_r($status);die;
        $email =[$pm_email];
        if($user_role != "vendor"){
            $ccemail =[$vt_email];
        }else{
            $ccemail =[];
        }
        
        $ccemail = array_merge($orgadmin_emails,$ccemail);
      //  print_r($ccemail);die;
        $mailData = [
            'title' =>'Work in progress',
            'subject' =>'Work status of '.$reference_id,
            'quote_code' =>$reference_id,
            'name'=>ucwords($vt_name),
            'email'=>$vt_email,
            'completed_task'=>(($status != '')? $status.'% has been completed for '.$s_langs.'-'.$t_langs : ''),
            'date' => $date,
            'request_id' => env('APP_URL').'admin/assigntotranslator/'.$reference_id//route('admin.request.assigntotranslator',[$reference_id]) 
        ];
        try{
            $res=sendvtstatusemail($email,$mailData,$ccemail);
        }catch (exception $e) {
            createlog('error',$e->getMessage());
        }
       // print_r($res);die;
        createlog('add_comment','Comment added successfully of project ID:'.$reference_id.' and '.$status.'% has been completed for '.$s_langs.'-'.$t_langs,$commentid,'loc_request_comments','comment');
        Session()->flash('message', 'Comment is successfully added');
        return Redirect()->back();
    }



    // public function deletesourcefiledata($id)
    // {
    //     $file_delete = loc_request_comment_child::findOrFail($id);
    //     $path = base_path() . "/public/storage/request/translation-file/{$file_delete->target_file}";
    //     if (File::exists($path)) {
    //         unlink($path);
    //     }
    //     $file_delete->delete();
    //     return redirect()->back();
    // }


    public function request_add_fields()
    {
        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
        $addrow = $_REQUEST['addrow'];
        $increment_div = $addrow + 1;
        $html = '<div class="col-md-6"  id="add_rows_request' . $addrow . '"><div class="card">
        <div class="card-header">
          Section '.$increment_div.'
          <div class="card-tools">
            <button type="button" class="remove_field btn btn-tool bg-danger" id="' . $addrow . '" onClick="javascript:validate_dynamic_field_page(' . $addrow . ');">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
    <div class="col-md-12">
                        <div class="row">
                        <input type="hidden" value="" name="db_source_lang_id[]" />
                        <input type="hidden" value="' . $addrow . '" name="db_source_lang_index[]" />
                            <!-- Source Language block -->
                            <div class="col-md-6 form-group ">
                                <label for="source_language" class="required">Source Language</label>
                                <select name="source_language[]" id="source_language_' . $addrow . '" class="form-control select2" required>
                                    <option value="">Select Source Language</option>';
        foreach ($loc_languages as $key => $lang) {
            $html .= '<option value="' . $lang->lang_id . '">' . $lang->lang_name . '</option>';
        }
        $html .= '</select>';


        $html .= '<div class="invalid-feedback" for="">Must Select your Source Language</div>
                            </div>


                            <div class="form-group col-md-6">
                                <label for="destination_language" class="required">Target Language</label>
                                <select name="destination_language_' . $addrow .'[]" id="destination_language_' . $addrow . '" class="form-control select2" required>
                                    <option value="">Select Target Language</option>';

        foreach ($loc_languages as  $lang) {
            $html .= '<option value="' . $lang->lang_id . '">' . $lang->lang_name . '</option>';
        }

        $html .= '</select>';

        $html .=  '<div class="invalid-feedback" for="">Must Select your Target Language</div>
                            </div>
                            <div class="form-group col-md-4">
                            <input type="hidden" name="source_type_' . $addrow . '" id="source_type_' . $addrow . '" value="File" data-id="' . $addrow . '" class="form-control source_type" required/>

                                <!--<label for="source_type" class="required">Source Type</label>
                                <select name="source_type_' . $addrow . '" id="source_type_' . $addrow . '" onchange="checksourcetype(' . $addrow . ')" data-id="' . $addrow . '" class="form-control" required>
                                    <option value="File">File</option>
                                    <option value="Text">Text</option>
                                </select>
                                <div class="invalid-feedback" for="">Must Select your Source Type</div>-->
                            </div>
                            <div class="col-md-12">
                                <div class="row" id="div_source_file_' . $addrow . '" >
                                    <div class="form-group col-md-8 input_fields_wrap2_' . $addrow . '">
                                        <label class="required">Source File </label>
                                        <input type="file" class="form-control" name="source_file_' . $addrow . '[]" id="source_file_' . $addrow . '">
       
                                    </div>
                                    <div style="margin-top:29px;" class="form-group col-md-4">
                                        <button type="button" class="btn btn-success btn-sm float-right" data-id="' . $addrow . '" id="id_more_upload" onclick="add_more_upload(' . $addrow . ')" value="More Files" name="submit"><i class="fas fa-plus"></i></button>
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div style="margin-top:29px;display:none;"class="form-group col-md-2">
                                     <button class="remove_field btn btn-danger float-right" id="' . $addrow . '" onClick="javascript:validate_field_page(' . $addrow . ');">remove</button>

                                    </div>
                                </div>
                                
                                <div class="row" id="div_source_text_' . $addrow . '" style="display:none" >
                                    <div class="form-group col-md-6 input_fields_wrap_text_' . $addrow . '">
                                    <label for="source_text" class="required">Source Text</label>
                                    <textarea rows="4" cols="50" name="source_text_' . $addrow . '[]"  id="source_text_' . $addrow . '" id="special_instruction" class="form-control"></textarea>
                                    </div>
                                    <div style="margin-top:29px;" class="form-group col-md-2">
                                    <input type="button" class="btn btn-success btn-sm float-right" onclick="add_more_text_upload(' . $addrow . ')" data-id="' . $addrow . '" id="id_more_text_upload_' . $addrow . '" value="More Text" name="submit">
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div style="margin-top:29px;"class="form-group col-md-2">
                                    <button class="remove_field btn btn-danger float-right" id="' . $addrow . '" onClick="javascript:validate_field_page(' . $addrow . ');">remove</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
               </div>
              </div>';
        //$html .= '<div id="add_rows_request_' . $increment_div . '"></div>';
        echo $html;
        exit;
    }
    // public function comments(Request $request)
    // {
    //     if (!checkpermission('create_request')) {
    //         return abort(401);
    //     }
    //     $post = $request;
    //     $userid = Auth::user()->id;
    //     $reference_id     = $post['reference_id'];
    //     $source_type            = 'Text';
    //     $source_text            = '';
    //     $source_date            = '';
    //     $comments               = $post['comments'];
    //     $source_file            = $_FILES['source_file'];
    //     $soucefilestatus        = 1;
    //     $source_file_path       = NULL;
    //     $request_status         = '';
    //     $file = $request->file('source_file');
    //     if ($file != "") {
    //         $file_name = $request->file('source_file')->getClientOriginalName();
    //         $source_file = $request->file('source_file')->getRealPath();
    //         $source_file_extension = strtolower($request->file('source_file')->getClientOriginalExtension());
    //         $supported_image = array('zip', 'zip', 'tar', 'gz', 'rar', 'doc', 'docx', 'xls', 'apk', 'xlsx', 'jpeg', 'jpg', 'png', 'rtf', 'html', 'xml', 'pdf', 'ppt', 'txt', 'PPT', 'pptx', 'PPTX', 'mp3', 'mp4', 'avi', 'flv', 'wmv', 'mov', 'xliff', 'json');
    //         if (in_array($source_file_extension, $supported_image)) {
    //             $path = base_path() . "/public/storage/request/comments/";
    //             if (!File::isDirectory($path)) {
    //                 $some =  File::makeDirectory($path, 0777, true);
    //             }
    //             $path_save = "public/request/comments/";
    //             $source_file_path = $upload_file_name = $reference_id . '_' . time() . '.' . $source_file_extension;
    //             if (!file_exists("$path/$upload_file_name")) {
    //                 $file->move($path, $upload_file_name);
    //             }
    //         }
    //     } else {
    //         $source_file_path = NULL;
    //     }
    //     if ($soucefilestatus == 1) {
    //         $created_time = date("Y-m-d H:i:s");
    //         if ($source_date != "")
    //             $deliverydate = date('Y-m-d H:i:s', strtotime($source_date));
    //         else
    //             $deliverydate = '0000-00-00 00:00:00';

    //         $source_file_path = $source_file_path;

    //         /* insert into database  */
    //         $arr_subrequests = array('reference_id' => $reference_id, 'user_id' => $userid, 'source_type' => $source_type, 'source_text' => $source_text, 'source_file_path' => $source_file_path, 'special_instructions' => $comments, 'created_time' => $created_time);
    //         $req = loc_request::loc_sub_request_insert($arr_subrequests);
    //     }
    //     return redirect()->route('admin.request.show', [$reference_id]);
    // }
    public function show($reference_id)
    {
        if ((!checkpermission('create_request')) && (!checkpermission('request_todo_activities'))) {
            return abort(401);
        }

        $edit_request    = loc_request::where('reference_id', $reference_id)->first();

        if (isset($edit_request->req_id) && $edit_request->req_id != '') {
            $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
            //$edit_request_lang    = DB::table('loc_request_lang')->where('reference_id', $reference_id)->first();
            /*$request_comments    = DB::table('loc_update_request')->select('loc_update_request.*', 'users.name as user_name')->orderBy('updated_request_id', 'DESC')->where('reference_id', $reference_id)->get();*/
            $linguist_list    = DB::table('user_orgizations')
                ->join('users', 'user_orgizations.user_id', '=', 'users.id')->select('users.id', 'users.name as user_name')->orderBy('name', 'ASC')->where('user_orgizations.org_id', '5')->get();
            return view('admin.request.show', compact('edit_request', 'loc_languages', 'linguist_list'));
        } else {
            return abort(404);
        }
    }
    //craete request by pm for quote genrtaion
    //craete request by pm for quote genrtaion
    public function createrequest($id)
    {
        //     if (! checkpermission('create_request')) {
        //         return abort(401);
        //     }
        $loc_req=loc_request::where('reference_id', $id)->count();
        if($loc_req == 0){
        $getst_lang= new loc_translation_master();
        $user_role = Auth::user()->roles[0]->name;
        $get_quote_data = loc_translation_master::where('quote_code', $id)->first();
        
        //$loc_services=locQuoteService::join('loc_service','loc_quote_service.service_type', '=', 'loc_service.id')->where('quote_id',$get_quote_data->translation_quote_id)->get();
        $loc_services=new locQuoteService();
        $clientorganizations = Clientorganization::where(['org_status' => '1', 'kpt_org' => $get_quote_data->organization])->get();
       
        $getst_lang= new loc_translation_master();
        $loc_services=new locQuoteService();
        // $loc_multiple_file=loc_multiple_file::where('request_id',$loc_get->req_id)->get();
        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
        $currency_list = currencies::where('status','Active')->get();
        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
        $org_id = get_user_org('org', 'org_id');
        // $vendors = user::select('id', 'name')->whereHas(
        //     'roles',
        //     function ($q) {
        //         $q->where('name', 'vendor');
        //     }
        // )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->get(); 
        
        $vendors = user::select('id', 'name')->whereHas(
            'roles',
            function ($q) {
                $q->whereIn('name', ['translator','vendor']);
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->get();
  
        return view('admin.request.pmcreaterequest', compact('get_quote_data', 'loc_languages', 'clientorganizations','getst_lang','loc_services','currency_list','vendors'));
        }else{
            return abort(404);
        }
    }
    public function requesttransaction($reference_id)
    {
        if (!checkpermission('request_todo_activities', 'update') || !checkpermission('request_invoice')) {
            return abort(401);
        }
        $edit_request = loc_request::where('reference_id', $reference_id)->first();
        if ($edit_request) {
          $userid = Auth::user()->id;
          $user_role = Auth::user()->roles[0]->name;
          $quote_data=loc_translation_master::where('translation_quote_id',$edit_request->quote_gen_id)->first();
          $where_status=['org_id'=>$edit_request->organization_id,'clientorg'=>$edit_request->client_org_id,'req_id'=>$edit_request->req_id];
          $invoice_history=clientorg_invoice_details::where($where_status)->get();
          $sum_of_pending=clientorg_invoice_details::where($where_status)->sum('pending');
          $sum_of_paid=clientorg_invoice_details::where($where_status)->sum('paid');
          $grand_total=$sum_of_pending-$sum_of_paid;
         

          $where_status=['req_id'=>$edit_request->req_id];
          $vendor_invoice_history=vendor_invoice_details::where($where_status)->get();
          $vendor_sum_of_pending=vendor_invoice_details::where($where_status)->sum('pending');
          $vendor_sum_of_paid=vendor_invoice_details::where($where_status)->sum('paid');
          $vendor_grand_total=$vendor_sum_of_pending-$vendor_sum_of_paid;
          // echo "<pre/>";
        // print_r($vendor_invoice_history);die;

        $client_invoice_list=loc_invoice::select('loc_invoice.*','clientorg_invoice_details.paid')->selectRaw('sum(clientorg_invoice_details.paid) as invoice_paid')->leftJoin('clientorg_invoice_details', 'loc_invoice.id', '=', 'clientorg_invoice_details.invoice_id')->where(['loc_invoice.req_id'=>$edit_request->req_id,'loc_invoice.invoice_type'=>'client'])->groupBy('invoice_id')->get();
         //print_r($client_invoice_list);die;
        // $client_invoice_list=loc_invoice::where(['req_id'=>$edit_request->req_id,'invoice_type'=>'client'])->get();
          //$sum_of_paid=clientorg_invoice_details::where(['invoice_id'=>$id])->sum('paid');
          $client_billed_amount=loc_invoice::where(['req_id'=>$edit_request->req_id,'invoice_type'=>'client'])->sum('invoicing_total');
          $client_unbilled_amount=$sum_of_pending-$client_billed_amount;

          $vendor_invoice_list=loc_invoice::select('loc_invoice.*','vendor_invoice_details.paid')->selectRaw('sum(vendor_invoice_details.paid) as invoice_paid')->leftjoin('vendor_invoice_details', 'loc_invoice.id', '=', 'vendor_invoice_details.invoice_id')->where(['loc_invoice.req_id'=>$edit_request->req_id,'loc_invoice.invoice_type'=>'vendor'])->get();
          $vendor_billed_amount=loc_invoice::where(['req_id'=>$edit_request->req_id,'invoice_type'=>'vendor'])->sum('invoicing_total');
          $vendor_unbilled_amount=$vendor_sum_of_pending-$vendor_billed_amount;

          $taxble_amount=loc_translation_master::where('translation_quote_id',$edit_request->quote_gen_id)->first();
          $total_amount=$taxble_amount->total_amount;
          $pm_cost=($total_amount*$taxble_amount->pm_cost)/100;
          $unbilled_amount=$total_amount+$pm_cost-$client_billed_amount;
          //echo $unbilled_amount;die;
         

          $get_user = new User();
          $loc_request = new loc_request();
          $getst_lang= new loc_translation_master();
          $loc_services=new locQuoteService();
            //$vendors_list_data=loc_targetfile::where(['request_id'=>$loc_get->req_id,'v_id'=>$vendor_id])->get();
          $vendors_list_data=loc_targetfile::where(['request_id'=>$edit_request->req_id])->where('v_id', '!=', '', 'and')->get();

       //  echo "<pre>";
      // print_r($vendors_list_data);die;
          return view('admin.request.requesttransaction', compact('edit_request', 'get_user', 'loc_request','invoice_history','sum_of_pending','sum_of_paid','grand_total','vendor_invoice_history','vendor_sum_of_pending','vendor_sum_of_paid','vendor_grand_total','client_invoice_list','client_billed_amount','client_unbilled_amount','vendor_invoice_list','vendor_billed_amount','vendor_unbilled_amount','getst_lang','loc_services','vendors_list_data','quote_data'))->with(['page_title'=>'Transaction Details - '.$reference_id]);
        } else {
            return abort(404);
        }
    }

   
    public function requestinvoice($reference_id)
    {
        if (!checkpermission('request_invoice')) {
            return abort(401);
        }
        $loc_get = loc_request::where('reference_id', $reference_id)->first();
        if ($loc_get) {
            $userid = Auth::user()->id;
            $user_role = Auth::user()->roles[0]->name;
            $invoice_type='client';
            $getst_lang= new loc_translation_master();
            $loc_services=new locQuoteService();
            $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
            $where_status=['org_id'=>$loc_get->organization_id,'clientorg'=>$loc_get->client_org_id,'req_id'=>$loc_get->req_id];
            $taxble_amount=loc_translation_master::where('translation_quote_id',$loc_get->quote_gen_id)->first();
            $sum_of_pending=clientorg_invoice_details::where($where_status)->sum('pending');
            $sum_of_paid=clientorg_invoice_details::where($where_status)->sum('paid');
            $billed_amount=loc_invoice::where(['req_id'=>$loc_get->req_id,'invoice_type'=>'client'])->sum('invoicing_amount');
            //$unbilled_amount=$sum_of_pending-$billed_amount;
            $bank_detail=bank_details::where(["user_id"=>$loc_get->organization_id,"type"=>"org"])->get();
            $personal_detail=personal_details::where(["user_id"=>$loc_get->organization_id,"type"=>"org"])->get();
            $org_addr=address::where(["user_id"=>$loc_get->organization_id,"type"=>"org"])->get();
            $client_adrss=address::where(["user_id"=>$loc_get->client_org_id,"type"=>"client_org"])->get();
            $client_perdetail=personal_details::where(["user_id"=>$loc_get->client_org_id,"type"=>"client_org"])->get();
            $total_amount=$taxble_amount->total_amount;
            $pm_cost=($total_amount*$taxble_amount->pm_cost)/100;
            $unbilled_amount=$total_amount+$pm_cost-$billed_amount;
            return view('admin.request.requestinvoice', compact('loc_get', 'unbilled_amount','loc_services','loc_languages','getst_lang','invoice_type','bank_detail','personal_detail','org_addr','client_adrss','client_perdetail'))->with(['page_title'=>'Create Client Invoice ']);;
        }else {
            return abort(404);
        }
        
    }
    public function requestvendorinvoice($reference_id)
    {
        if (!checkpermission('request_invoice')) {
            return abort(401);
        }
        $userid = Auth::user()->id;
            $user_role = Auth::user()->roles[0]->name;
        
            // $translators = user::select('id', 'name')->whereHas(
            //     'roles',
            //     function ($q) {
            //         $q->select('name')->whereIn('name', ['translator','vendor']);
            //     }
            // )->where('id',$assign_data)->with('roles')->first();
  

        $loc_get = loc_request::where('reference_id', $reference_id)->first();
        if ($loc_get) {
            if(isset($_GET['vendor']) && $_GET['vendor'] != ''){
                $vendor_id=$_GET['vendor'];
            }else{
                if($user_role=="vendor"){
                    $vendor_id=$userid;
            //   print_r($kpt_adrss);die;     
                }else{
                $vendor_id=0;
            }
            }
            $invoice_type='vendor';
            
            $getst_lang= new loc_translation_master();
            $loc_services=new locQuoteService();
             
            
            // $kpt_perdetail=personal_details::where(["user_id"=>$loc_get->client_org_id,"type"=>"client_org"])->get();
         
            $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
            $where_status=['user_id'=>$vendor_id,'req_id'=>$loc_get->req_id];
            $vendor_invoice_history=vendor_invoice_details::where($where_status)->get();
          
            $vendor_sum_of_pending=loc_targetfile::where(['request_id'=>$loc_get->req_id,'v_id'=>$vendor_id])->sum('v_amount');
            $vendor_sum_of_paid=vendor_invoice_details::where($where_status)->sum('paid');
            $vendor_grand_total=$vendor_sum_of_pending-$vendor_sum_of_paid;
            $vendor_invoice_list=loc_invoice::where(['req_id'=>$loc_get->req_id,'vendor_id'=>$vendor_id,'invoice_type'=>'vendor'])->get();
            $vendor_billed_amount=loc_invoice::where(['req_id'=>$loc_get->req_id,'vendor_id'=>$vendor_id,'invoice_type'=>'vendor'])->sum('invoicing_amount');
            $vendor_unbilled_amount=$vendor_sum_of_pending-$vendor_billed_amount;
            $where_status ='(v_id != "" OR tr_id != "")';
            $vendors_list=loc_targetfile::where(['request_id'=>$loc_get->req_id])->whereRaw($where_status)->groupBy(['v_id','tr_id'])->get();
            //  echo "<pre/>"; print_r($vendors_list);die;
            //   print_r($vendors_list);die;
            $vendors_list_data=[]; 
            // if($user_role=='administrator' || $user_role=='orgadmin' || $user_role=='finance'){
            // $vendor_bank_detail=bank_details::where(["user_id"=>$vendor_id,"type"=>"user"])->get();
            //     $vendor_personal_detail=personal_details::where(["user_id"=>$vendor_id,"type"=>"user"])->get();
            //     print_r($vendor_personal_detail);die;
            //     $vendor_org_addr=address::where(["user_id"=>$vendor_id,"type"=>"user"])->get();
            //     $kpt_adrss=address::where(["user_id"=>$loc_get->organization_id,"type"=>"org"])->get();
            // }
            $kpt_adrss=$vendor_bank_detail=$vendor_org_addr=$vendor_personal_detail=[];
            $kpt_adrss=address::where(["user_id"=>$loc_get->organization_id,"type"=>"org"])->get();
            $org_personal_detail=personal_details::where(["user_id"=>$loc_get->organization_id,"type"=>"org"])->get();
            // echo "<pre>";
            // print_r($org_personal_detail);die;
            if($vendor_id !=0){
                $vendors_list_data=loc_targetfile::where(['request_id'=>$loc_get->req_id,'v_id'=>$vendor_id])->get();
                $vendor_bank_detail=bank_details::where(["user_id"=>$vendor_id,"type"=>"user"])->get();
                $vendor_personal_detail=personal_details::where(["user_id"=>$vendor_id,"type"=>"user"])->get();
                //  print_r($vendor_personal_detail);die;
                $vendor_org_addr=address::where(["user_id"=>$vendor_id,"type"=>"user"])->get(); 
                

            }
            return view('admin.request.requestvendorinvoice', compact('kpt_adrss','org_personal_detail','vendor_org_addr','vendor_personal_detail','vendor_bank_detail','loc_get', 'vendor_unbilled_amount','loc_services','loc_languages','getst_lang','vendors_list','invoice_type','vendor_sum_of_pending','vendors_list_data','vendor_id'))->with(['page_title'=>'Create Linguist Invoice ']);;
        }else {
            return abort(404);
        }
    }
    public function viewinvoice($invoice_no)
    {
        $user_role = Auth::user()->roles[0]->name;

        if($user_role == 'translator' || $user_role == 'vendor' || $user_role == 'proofreader' || $user_role == 'qualityanalyst'){
            return abort(401);
        }
        $invoice_details =  loc_invoice::where('invoice_no', $invoice_no)->first();
       
        return view('admin.request.viewinvoice', compact('invoice_details'));
    }
    public function assigntotranslator($reference_id)
    {
        // if (!checkpermission('request_invoice')) {
        //     return abort(401);
        // }
        $org_id = get_user_org('org', 'org_id');
        // $role_type = ;
        $user_role = Auth::user()->roles[0]->name;
        
       
        $translators = user::select('id', 'name')->whereHas(
            'roles',
            function ($q) {
                $q->whereIn('name', ['translator','vendor']);
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->get();
  
        $vendors = user::select('id', 'name')->whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'vendor');
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->get(); 
    
        $qualityanalyst = user::select('id', 'name')->whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'qualityanalyst');
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->get(); 
        
        $proofreader = user::select('id', 'name')->whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'proofreader');
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->get(); 

        $loc_get = loc_request::where('reference_id', $reference_id)->first();
      
        if ($loc_get) {
            $userid = Auth::user()->id;
            $user_role = Auth::user()->roles[0]->name;
            $getst_lang= new loc_translation_master();
            $loc_services=new locQuoteService();
            // $loc_multiple_file=loc_multiple_file::where('request_id',$loc_get->req_id)->get();
            $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
            $currency_list = currencies::where('status','Active')->get();
       
            return view('admin.request.assigntotranslator', compact('loc_get','loc_services','loc_languages','getst_lang','currency_list','translators','vendors','qualityanalyst','proofreader'))->with(['page_title'=>'Assign Projects - '.$reference_id]);;
        }else {
            return abort(404);
        }
    }
    public function trrequestupdate(Request $request){
        // if (!checkpermission('request_todo_activities')) {
        //     return abort(401);
        // }
        
 // print_r($_POST);die;
        $userid = Auth::user()->id;
        $assign_date=date('Y-m-d H:i:s');
        //$reference_id = $request['reference_id'];
        $assign_data = $request['assign_data'];
        $trAssign = $request['trAssign'];
        $fileid= $request['fileid'];
        $req_id= $request['req_id'];
        $req_lang_id= $request['req_lang_id'];
        $assign_work_per=$request['assign_work_per'];
        $assign_gst=$request['gst'];
        $currency_id=$request['currency'];
        $unit_count=$request['unit_count'];
        $per_unit=$request['per_unit'];
        $currency_cost  = gettabledata('currencies','inr',['id'=>$currency_id]);
       
        $assign_amount_per=$request['assign_amount_per'];
        // $assign_data = explode(',', $assign_data);
        // $assign_work_per=explode(',',$assign_work_per);
        // $assign_amount_per=explode(',',$assign_amount_per);
        // $assign_gst=explode(',',$assign_gst);
        if ($trAssign == 'tr_assign') {
            $translators = user::select('id', 'name')->whereHas(
                'roles',
                function ($q) {
                    $q->select('name')->whereIn('name', ['translator','vendor']);
                }
            )->where('id',$assign_data)->with('roles')->first();
            if($translators->roles[0]->name == 'vendor'){
                $trAssign = 'v_assign';
            }
        }
            $assign_id = $assign_data;
            if ($trAssign == 'tr_assign') {
               // $work_per=$assign_work_per;
                $amount_per=$assign_amount_per;
                $type_gst=$assign_gst;
                 if($type_gst=='no_gst'){
                   $gst=0;
                   $total_amount=$amount_per;
                 }else{
                   $gst=18;
                   $total_amount=$amount_per+(($amount_per*$gst)/100);
                    }
               $ass_data=array(
                 'tr_id'=>$assign_id,
                 'tr_assigned_date'=>date('Y-m-d H:i:s'),
                 'v_amount'=>$amount_per,
                 'v_total'=>$total_amount,
                 'v_gst'=>$type_gst,
                 'v_gst_amnt'=>$gst,
                 'currency_id'=>$currency_id,
                 'currency_cost'=>$currency_cost,
                 'unit_count'=>$unit_count,
                 'per_unit'=>$per_unit,
                );
               // print_r($ass_data);die;
            }elseif ($trAssign == 'qa_assign') {
               $ass_data = array(
                'qa_id' => $assign_id,
                'qa_assigned_date' => date('Y-m-d H:i:s')
                );
              
            }elseif ($trAssign == 'pr_assign') {
               $ass_data = array(
                'pr_id' => $assign_id,
                'pr_assigned_date' => date('Y-m-d H:i:s')
                 );
                // print_r($ass_data);die;
             }elseif ($trAssign == 'v_assign') {
                 $work_per=$assign_work_per;
                 $amount_per=$assign_amount_per;
                 $type_gst=$assign_gst;
                  if($type_gst=='no_gst'){
                    $gst=0;
                    $total_amount=$amount_per;
                  }else{
                    $gst=18;
                    $total_amount=$amount_per+(($amount_per*$gst)/100);
                     }
                $ass_data=array(
                  'v_id'=>$assign_id,
                  'work_per'=>$work_per,
                  'v_assigned_date'=>date('Y-m-d H:i:s'),
                  'v_amount'=>$amount_per,
                  'v_total'=>$total_amount,
                  'v_gst'=>$type_gst,
                  'v_gst_amnt'=>$gst,
                  'currency_id'=>$currency_id,
                  'currency_cost'=>$currency_cost,
                    'unit_count'=>$unit_count,
                 'per_unit'=>$per_unit,
                 );
            }
        // $ass_data['comment_id']=0;
        $ass_data['req_lang_id']=$req_lang_id;
        $ass_data['request_id']=$req_id;
        $ass_data['req_file_id']=$fileid;
        $lcf_where_v_t=['request_id' => $req_id,'req_lang_id'=>$req_lang_id,'req_file_id'=>$fileid];
        $loc_files_update_data = loc_targetfile::where($lcf_where_v_t)->first();
        if($loc_files_update_data){
            $loc_files_update = loc_targetfile::where(['id'=>$loc_files_update_data->id])->update($ass_data);
        }else{        
            $loc_files_update = loc_targetfile::insert($ass_data);
        }
     //   print_r($loc_files_update);die;
        if($loc_files_update){
            if ($trAssign == 'v_assign' || $trAssign == 'tr_assign') {
            $loc_data=loc_request::where(['req_id' => $req_id])->first();
            $vendor_invoice_data = array( 
                'user_id' => $assign_id,
                'req_id' => $req_id,
                'pending' => $total_amount,
                'desc' => 'Booking Amount for: '.$loc_data->reference_id, 
                'created_by' => $userid,   
                'created_at' => date('Y-m-d H:i:s')
            );
            vendor_invoice_details::insert($vendor_invoice_data);
            }
            
        }
        $vtorg=get_user_org('org','org_id');
        $authenticated_users = User::whereHas(
            'roles', function($q) {
                $q->where('name','orgadmin');
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id',$vtorg)->get();
        $orgadmin=$authenticated_users->toArray();
        $orgadmin_emails=array_column((array)$orgadmin,'email');
        $vtname=getusernamebyid($assign_id);
        $reference_id=gettabledata('loc_request','reference_id',['req_id'=>$req_id]);
        $vt_email=getusernamebyid($assign_id,'email');
        $pmemail=getusernamebyid($userid,'email');
        if($trAssign != 'v_assign'){
            $email =[$vt_email];
            $ccemail =[$pmemail];
        }else{
            $email =[$pmemail];
            $ccemail =[];
        }
        
        $ccemail = array_merge($orgadmin_emails,$ccemail);
        $mailData = [
            'title' => ucwords(showcrstatus($trAssign)).'  - Transflow',
            'subject' => ucwords(showcrstatus($trAssign)).' for '.$reference_id,
            'req_id' =>$reference_id,
            'name'=>ucwords($vtname),
            'email'=>$vt_email,
            'date' => $assign_date,
            'created_by' =>getusernamebyid($userid),
            'request_url' => env('APP_URL').'admin/assigntotranslator/'.$reference_id//route('admin.request.assigntotranslator',[$reference_id]) 
        ];
        try{
            $res=sendstupdatemail($email,$mailData,$ccemail);
        }catch (exception $e) {
            createlog('error',$e->getMessage());
        }
        createlog('change_request_status','Project ID:'.$reference_id.' status changed to '.ucwords(showcrstatus($trAssign)),$req_id);
        Session()->flash('message', 'Request Status successfully updated');
        
        
    }
    public function add_comment($id,$target){
        // echo $target;
       $get_file_id=loc_multiple_file::where('id',$id)->first();
       $get_taget_file=loc_targetfile::where(['req_file_id'=>$id,'req_lang_id'=>$target])->first();
      // echo "<pre>";print_r($get_taget_file);die;
    
        $target_file_id=$target;
       
  
         $comments=loc_request_comment::where(['req_lang_id' => $target,'req_file_id'=>$id])->get();
       // echo "<pre>"; print_r($comments);die;

    //     $file = $request->file('source_file');
    //     $filePath = 'translationeditor/' . $upload_file_name;
    //    // $res = simplexml_load_file($path."/".$upload_file_name);
    //     $rr=Storage::disk('s3')->put($filePath, file_get_contents($file));
    //     $res = simplexml_load_file(env('AWS_CDN_URL') .'/'. $filePath);


        // $name =   $get_taget_file;
        // $filePath = 'request/comments/'. $name;
        // $name =   $get_taget_file;
        // $res= Storage::disk('s3')->put($filePath, file_get_contents($name));
   
        $getstatus=loc_request::where('req_id',$get_file_id->request_id)->first();
  
       return view('admin.request.addcomments',compact('get_file_id','target_file_id','getstatus','comments','get_taget_file'));
    }



    public function cancel_request($req_id){
       // print_r($req_id);die;
        $req_status='cancel';
        $res=loc_request::where('req_id',$req_id)->first(); 
        loc_request::where('req_id',$req_id)->update(['request_status'=>$req_status]); 
        if($res->quote_get_id != ''){
            loc_translation_master::where('translation_quote_id', $res->quote_gen_id)->update(['client_amnt_status'=>$req_status]);
        }
        $quote_data=loc_translation_master::where('translation_quote_id',$res->quote_gen_id)->first();
      // echo "<pre/>"; print_r($quote_data);die;
        $userid = Auth::user()->id;
       
        $user_role = Auth::user()->roles[0]->name;
    
        $getorgid = user_orgizations::where('user_id', $userid)->first();
    
        $org_id=$getorgid->org_id;
        $org_id = get_user_org('org', 'org_id');

        $authenticated_users = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'orgadmin');
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id', $org_id)->first();
        $orgadmin = $authenticated_users->toArray();
      
        $orgadmin_emails = array_column((array)$orgadmin, 'email');

       
        $translation_user_id=$quote_data->translation_user_id;
       

        $quote_code=$quote_data->quote_code;
      
        $translation_quote_id=$quote_data->translation_quote_id;
     // print_r($translation_quote_id);die;
        // getclientid($translation_quote_id);die;
      
      
        $quote_comp=$quote_data->client_comp_name;
        $pm_id=$quote_data->pm_id;
      //  echo "<pre/>";    print_r($pm_id);die;
        $org_id = $userid;
        $date = date("Y-m-d H:i:s");
        // $orgadmin_mail=getusernamebyid($orgadmin,'email');
        $org_email = getusernamebyid($org_id, 'email');
      //  
        $pm_email = getusernamebyid($pm_id, 'email');
        $sales_email = getusernamebyid($translation_user_id, 'email');
        // echo "<pre/>"; print_r($pm_email);die;
      
        $email = [$pm_email,$sales_email];
        $ccemail = [$org_email];
       
        
        $mailData = [
            'title' => 'project  Cancelled ',
            'subject' => 'project cancelled ' . $quote_code,
            'req_id' => $quote_code,
            'client_org_id'=> getclientid($translation_quote_id),
            'company_name' => ucwords($quote_data->client_comp_name),
            'name' => ucwords(getusernamebyid($pm_id)),
            "number" => $quote_data->mob_number,
            'email' => $pm_email,
            'date' => $date,
            'created_by' => ucwords(getusernamebyid($userid)),
            'request_url' => env('APP_URL') . '/admin/cancel_requests/' . $quote_code
        ];
        try{
            $res = sendstupdatemail($email, $mailData, $ccemail);
        }catch (exception $e) {
            createlog('error',$e->getMessage());
        }

        Session()->flash('message', 'Project Cancel successfully ');
        return redirect()->back();
    }


public function download_pdf_url()
{
$file_name = 'file.pdf';
$file_url = "https://d1458le5lr1pr2.cloudfront.net/request/target/'.$file_name";
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"".$file_name."\"");
readfile($file_url);
exit;
}
}