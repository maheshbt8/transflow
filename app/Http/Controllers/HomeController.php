<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\loc_request;
use App\User;
use Auth;
use App\loc_invoice;
use App\loc_targetfile;
use App\Clientorganization;
use App\Kptorganization;
use App\client_user_orgizations;
use App\models\loc_translation_master;
use Carbon\Carbon;
use App\clientorg_invoice_details;
use App\vendor_invoice_details;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$h=createlog('pm_asign','Request asigned to project manager','1');
        echo "<pre/>";
        print_r($h);die;*/
        $user_role = Auth::user()->roles[0]->name;
        $userid=Auth::user()->id;
        $function=Auth::user()->roles[0]->name;
        if($user_role =="orgadmin" ||$user_role =="departmentadmin" || $user_role =="suborgadmin"){
            $function='orgadmin';
        }elseif($user_role =="clientuser" || $user_role =="requester" || $user_role =="approval"){
            $function='clientuser';
        }
        if(method_exists($this, $function)){
            return $this->$function();
        }else{
            return view('nodashboard');
        }
    }
    public function administrator()
    {
        $user_role = Auth::user()->roles[0]->name;
        $userid=Auth::user()->id;
        $created_by = '';
        $count  = new Kptorganization;
        $count  = Kptorganization::first();
        $orgcount=Kptorganization::count();
        $org_id='';
        $usercount = count(User::getAuthenticatedUsers($org_id));   

        return view('home',compact('orgcount','usercount'))->with(['page_title'=>'Super Admin Dashboard']);
    }
    public function orgadmin()
    {
        $user_role = Auth::user()->roles[0]->name;
        $userid=Auth::user()->id;
        $org_id=get_user_org('org','org_id');
        $loc_request = new loc_request();
        $active_count = loc_request::where([['request_status','!=','new'],['request_status','!=','client_cancel']])->where('organization_id',$org_id)->count();
        $inprogress_count = loc_request::where([['request_status','!=','new'],['request_status','!=','publish'],['request_status','!=','client_cancel'],['request_status','!=','pm_reject']])->where('organization_id',$org_id)->count();
        $completed_count = loc_request::where(['request_status'=>'re_accept','organization_id'=>$org_id])->count();
        $total_count=$active_count+$inprogress_count+$completed_count;
        $active_pie=$inprogress_pie=$completed_pie=0;
        if($total_count != 0){
            $active_pie=number_format((float)(($active_count/$total_count)*100), 2, '.', '');
            $inprogress_pie=number_format((float)(($inprogress_count/$total_count)*100), 2, '.', '');
            $completed_pie=number_format((float)(($completed_count/$total_count)*100), 2, '.', '');
        }
        $pie_chat_data='['.$active_pie.','.$inprogress_pie.','.$completed_pie.']';
        $user_role = Auth::user()->roles[0]->name; 
        $userid=Auth::user()->id;
        $loc_request_data = loc_request::select('reference_id','request_status')->where (['user_id'=>$userid])->orderBy('req_id', 'desc')->limit(10)->get();
        $linechat_label='[';
        $linechat_data='[';
        $linechat_status='[';
        foreach ($loc_request_data as $lr) {
            $linechat_label .=$lr->reference_id.',';
            $linechat_status .="'".$lr->request_status."',";
            if($lr->request_status =="new")
                $request_status_value = 0;
            elseif($lr->request_status =="tr_assign")
                $request_status_value = 25;
            elseif($lr->request_status =="tr_inprogress")
                $request_status_value = 50;
            elseif($lr->request_status =="tr_completed")
                $request_status_value = 100;
                else
                $request_status_value=0;
           
            $linechat_data .=$request_status_value.',';
        }
        $linechat_label .=']';
        $linechat_data .=']';
        $linechat_status .=']';
       
        /*get client users */
        if($userid !=""){
            $client_users = User::whereHas(
                'roles', function($q) {
                    $q->whereIn('name', ['clientuser','requester','approval','reviewer']);
                }
            )->join('client_user_orgizations', 'users.id', '=', 'client_user_orgizations.user_id')->where('client_user_orgizations.client_org_id',$org_id)->count();
          // echo "<pre/>"; print_r($client_users);die;
        }else{
           $client_users = User::whereHas(
            'roles', function($q) {
                $q->whereIn('name', ['clientuser','requester','approval','reviewer']);
            }
            )->count();    
        }

      
       /*end client users */

        /*get organization  users */
       if($org_id != ""){
        $authenticated_users = User::whereHas(
            'roles', function($q) {
                $q->whereIn('name', ['orgadmin','departmentadmin','suborgadmin','projectmanager','translator','proofreader','qualityanalyst','sales','finance','vendor']);
            }
        )->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id',$org_id)->count();
     
    }
        else{
       $authenticated_users = User::whereHas(
        'roles', function($q) {
            $q->whereIn('name', ['orgadmin','departmentadmin','suborgadmin','projectmanager','translator','proofreader','qualityanalyst','sales','finance','vendor']);
        }
        )->count();    
       }
     //  print_r($authenticated_users);die;
        $clientorganizations = Clientorganization::where(['org_status'=>'1','kpt_org'=>$org_id])->count();
        $last15days = loc_request::where([['request_status', '!=', 'new'], ['request_status', '!=', 'client_cancel']])->where('organization_id', $org_id)->where('created_time', '<=', Carbon::now()->subdays(15))->join('loc_translation_qoute_generation_master','loc_translation_qoute_generation_master.translation_quote_id','=','loc_request.quote_gen_id')->where('loc_translation_qoute_generation_master.client_amnt_status','!=','paid')->orderBy('created_time', 'desc')->get();
        $get_user = new User();
        $loc_request = new loc_request();
        return view('home',compact('active_count','inprogress_count','completed_count','pie_chat_data','linechat_label','linechat_data','linechat_status','user_role','client_users','authenticated_users','clientorganizations','last15days','get_user','loc_request'))->with(['page_title'=>'Organization Admin Dashboard']);;
    }
    public function projectmanager()
    {
        $user_role = Auth::user()->roles[0]->name;
        $userid=Auth::user()->id;
        $org_id=get_user_org('org','org_id');
        $loc_request = new loc_request();
        $org_id = get_user_org('org', 'org_id');
        $active_count = loc_request::join('loc_translation_qoute_generation_master','loc_request.quote_gen_id','=','loc_translation_qoute_generation_master.translation_quote_id')->where([['request_status', '!=', 'new'], ['request_status', '!=', 'client_cancel']])->where(['organization_id'=> $org_id,'loc_translation_qoute_generation_master.pm_id'=>$userid])->orderBy('created_time', 'desc')->count();
        $inprogress_count = loc_request::join('loc_translation_qoute_generation_master','loc_request.quote_gen_id','=','loc_translation_qoute_generation_master.translation_quote_id')->where([['request_status','!=','new'],['request_status','!=','publish'],['request_status','!=','client_cancel'],['request_status','!=','pm_reject']])->where(['organization_id'=>$org_id,'loc_translation_qoute_generation_master.pm_id'=>$userid])->count();
        $completed_count = loc_request::join('loc_translation_qoute_generation_master','loc_request.quote_gen_id','=','loc_translation_qoute_generation_master.translation_quote_id')->where(['request_status'=>'re_accept','organization_id'=>$org_id,'loc_translation_qoute_generation_master.pm_id'=>$userid])->count();   
        $total_count=$active_count+$inprogress_count+$completed_count;
        $active_pie=$inprogress_pie=$completed_pie=0;
        if($total_count != 0){
            $active_pie=number_format((float)(($active_count/$total_count)*100), 2, '.', '');
            $inprogress_pie=number_format((float)(($inprogress_count/$total_count)*100), 2, '.', '');
            $completed_pie=number_format((float)(($completed_count/$total_count)*100), 2, '.', '');
        }
        $pie_chat_data='['.$active_pie.','.$inprogress_pie.','.$completed_pie.']';
        $loc_request_data = loc_request::select('reference_id','request_status')->where (['user_id'=>$userid])->orderBy('req_id', 'desc')->limit(10)->get();
       
        $linechat_label='[';
        $linechat_data='[';
        $linechat_status='[';
        foreach ($loc_request_data as $lr) {
            $linechat_label .=$lr->reference_id.',';
            $linechat_status .="'".$lr->request_status."',";
            if($lr->request_status =="new")
                $request_status_value = 0;
            elseif($lr->request_status =="tr_assign")
                $request_status_value = 25;
            elseif($lr->request_status =="tr_inprogress")
                $request_status_value = 50;
            elseif($lr->request_status =="tr_completed")
                $request_status_value = 100;
                else
                $request_status_value=0;
            $linechat_data .=$request_status_value.',';
        }
        $linechat_label .=']';
        $linechat_data .=']';
        $linechat_status .=']';
        $last15days = loc_request::where([['request_status', '!=', 'new'], ['request_status', '!=', 'client_cancel']])->where('organization_id', $org_id)->where('created_time', '<=', Carbon::now()->subdays(15))->join('loc_translation_qoute_generation_master','loc_translation_qoute_generation_master.translation_quote_id','=','loc_request.quote_gen_id')->where([['loc_translation_qoute_generation_master.client_amnt_status','!=','paid'],['loc_translation_qoute_generation_master.pm_id','=',$userid]])->orderBy('created_time', 'desc')->get();
        $get_user = new User();
        $loc_request = new loc_request();
        return view('home',compact('active_count','inprogress_count','completed_count','pie_chat_data','linechat_label','linechat_data','linechat_status','last15days','loc_request','get_user'))->with(['page_title'=>'Project Manger Dashboard']);;
    }
    public function clientuser($value='')
    {
        $user_role = Auth::user()->roles[0]->name;
        $userid=Auth::user()->id;
        $org_id = get_user_org('clientorg', 'org_id'); 
            $requests = loc_request::whereIn('request_status',['new','approve','tr_assign','tr_inprogress','qa_assign','qa_inprogress','qa_accept','qa_reject','pr_assign','pr_inprogress','pr_accept','pr_accept','publish','re_accept','re_reject','client_cancel'])->where('client_org_id',$org_id)->orderBy('created_time', 'desc')->count();   
           
            $org_id=get_user_org('org','org_id');
            $loc_request = new loc_request();
           
            $active_count = loc_request::orWhere('request_status','new')->orWhere('request_status','pr_inprogress')->orWhere('request_status','qr_inprogress')->orWhere('request_status','tr_inprogress')->where('organization_id',$org_id)->count();
    // print_r($active_count);die;
            $inprogress_count = loc_request::orwhere('request_status','tr_assign')->orWhere('request_status','qa_assign')->orWhere('request_status','qa_accept')->orwhere('request_status','approval')->orWhere('request_status','pr_assign')->orWhere('request_status','qa_reject')->orWhere('request_status','re_reject')->orWhere('request_status','pm_reject')->orwhere('request_status','client_cancel')->where('organization_id',$org_id)->count();
            $completed_count = loc_request::where(['request_status'=>'re_accept','organization_id'=>$org_id])->count();
            $total_count=$active_count+$inprogress_count+$completed_count;
        
            $active_pie=$inprogress_pie=$completed_pie=0;
            if($total_count != 0){
                $active_pie=number_format((float)(($active_count/$total_count)*100), 2, '.', '');
                $inprogress_pie=number_format((float)(($inprogress_count/$total_count)*100), 2, '.', '');
                $completed_pie=number_format((float)(($completed_count/$total_count)*100), 2, '.', '');
            }
        $pie_chat_data='['.$active_pie.','.$inprogress_pie.','.$completed_pie.']';
       
        $user_role = Auth::user()->roles[0]->name; 
        $client_id=Auth::user()->id;
        
        $loc_request_data = loc_request::select('reference_id','request_status')->where (['client_org_id'=>$client_id])->orderBy('req_id', 'desc')->limit(10)->get();
      
        $linechat_label='[';
        $linechat_data='[';
        $linechat_status='[';
        foreach ($loc_request_data as $lr) {
            $linechat_label .=$lr->reference_id.',';
            $linechat_status .="'".$lr->request_status."',";
            if($lr->request_status =="new")
                $request_status_value = 0;
            elseif($lr->request_status =="tr_assign")
                $request_status_value = 25;
            elseif($lr->request_status =="tr_inprogress")
                $request_status_value = 50;
            elseif($lr->request_status =="tr_completed")
                $request_status_value = 100;
                else
                $request_status_value=0;
           
            $linechat_data .=$request_status_value.',';
        }
        $linechat_label .=']';
        $linechat_data .=']';
        $linechat_status .=']';
        return view('home',compact('active_count','inprogress_count','completed_count','pie_chat_data','linechat_label','linechat_data','linechat_status','requests'))->with(['page_title'=>'Client Admin Dashboard']);
    }
    public function translator()
    {
       
        $user_role = Auth::user()->roles[0]->name;
        $userid=Auth::user()->id;
        $org_id=get_user_org('org','org_id');
        $loc_request = new loc_request();    
      //  $active_count=  loc_request::join('loc_target_file', 'loc_request.req_id', '=', 'loc_target_file.request_id')->where('tr_id', $userid)
         // ->orderBy('created_time', 'desc')->groupBy('req_id')->count();
          $active_count = loc_request::join('loc_target_file','loc_request.req_id','=','loc_target_file.request_id')->where([['request_status', '!=', 'new'], ['request_status', '!=', 'tr_assign']])->where(['organization_id'=> $org_id,'tr_id'=>$userid])->orderBy('created_time', 'desc')->count();
           $inprogress_count = loc_request::join('loc_target_file','loc_request.req_id','=','loc_target_file.request_id')->where([['request_status', 'tr_inprogress']])->where(['organization_id'=> $org_id,'tr_id'=>$userid])->orderBy('created_time', 'desc')->count();
         
           $completed_count = loc_request::join('loc_target_file','loc_request.req_id','=','loc_target_file.request_id')->where([['request_status','tr_completed']])->where(['organization_id'=> $org_id,'tr_id'=>$userid])->orderBy('created_time', 'desc')->count();

 //  echo "<pre/>";  print_r($active_count);die;
      //  $inprogress_count = loc_request::orwhere('request_status','qr_inprogress')->orWhere('request_status','qa_assign')->orWhere('request_status','qa_accept')->orwhere('request_status','approval')->orWhere('request_status','pr_assign')->orWhere('request_status','qa_reject')->orWhere('request_status','re_reject')->orWhere('request_status','pm_reject')->orwhere('request_status','client_cancel')->where('organization_id',$org_id)->count();
        //    $completed_count = loc_request::where(['request_status'=>'tr_completed','organization_id'=>$org_id,'tr_id'=>$userid'])->count();
            $total_count=$active_count+$inprogress_count+$completed_count;
           // print_r($total_count);die;
            $active_pie=$inprogress_pie=$completed_pie=0;
        if($total_count != 0){
            $active_pie=number_format((float)(($active_count/$total_count)*100), 2, '.', '');
            $inprogress_pie=number_format((float)(($inprogress_count/$total_count)*100), 2, '.', '');
            $completed_pie=number_format((float)(($completed_count/$total_count)*100), 2, '.', '');
        }
        $pie_chat_data='['.$active_pie.','.$inprogress_pie.','.$completed_pie.']';
        $loc_request_data = loc_request::select('reference_id','request_status')->where (['user_id'=>$userid])->orderBy('req_id', 'desc')->limit(10)->get();
        $linechat_label='[';
        $linechat_data='[';
        $linechat_status='[';
        foreach ($loc_request_data as $lr) {
            $linechat_label .=$lr->reference_id.',';
            $linechat_status .="'".$lr->request_status."',";
            if($lr->request_status =="new")
                $request_status_value = 0;
            elseif($lr->request_status =="tr_assign")
                $request_status_value = 25;
            elseif($lr->request_status =="tr_inprogress")
                $request_status_value = 50;
            elseif($lr->request_status =="tr_completed")
                $request_status_value = 100;
                else
                $request_status_value=0;
           
            $linechat_data .=$request_status_value.',';
        }
        $linechat_label .=']';
        $linechat_data .=']';
        $linechat_status .=']';
       
        return view('home',compact('active_count','inprogress_count','completed_count','pie_chat_data','linechat_label','linechat_data','linechat_status'))->with(['page_title'=>'Linguist Dashboard']);
    }
    public function sales()
    {
        $user_role = Auth::user()->roles[0]->name;
        $userid=Auth::user()->id;
        $org_id=get_user_org('org','org_id');
        $assign_quotes = loc_translation_master::where(['translation_status'=>'Assign','organization'=>$org_id,'translation_user_id'=>Auth::user()->id])->count();
        $pending_quotes = loc_translation_master::where(['translation_status'=>'Open','organization'=>$org_id,'translation_user_id'=>Auth::user()->id])->count();
        $total_quotes=$assign_quotes+$pending_quotes;
        $last15days = loc_request::where([['request_status', '!=', 'new'], ['request_status', '!=', 'client_cancel']])->where('organization_id', $org_id)->where('created_time', '<=', Carbon::now()->subdays(15))->join('loc_translation_qoute_generation_master','loc_translation_qoute_generation_master.translation_quote_id','=','loc_request.quote_gen_id')->where([['loc_translation_qoute_generation_master.client_amnt_status','!=','paid'],['loc_translation_qoute_generation_master.translation_user_id','=',$userid]])->orderBy('created_time', 'desc')->get();
        $get_user = new User();
        $loc_request = new loc_request();
        return view('home',compact('assign_quotes','pending_quotes','total_quotes','last15days','get_user','loc_request'))->with(['page_title'=>'Sales Dashboard']);
    }
    public function qualityanalyst()
    {
        $user_role = Auth::user()->roles[0]->name;
        $userid=Auth::user()->id;
        $org_id=get_user_org('org','org_id');
        $loc_request = new loc_request();
        $active_count= loc_request::join('loc_target_file', 'loc_request.req_id', '=', 'loc_target_file.request_id')->where([['request_status', '!=', 'new'], ['request_status', '!=', 'qa_assign']])->where('qa_id', $userid)->orderBy('created_time', 'desc')->groupBy('req_id')->count();
        $inprogress_count = loc_request::orwhere('request_status','qa_inprogress')->orWhere('request_status','qa_assign')->orWhere('request_status','qa_reject')->orwhere('request_status','approval')->orWhere('request_status','qa_accept')->orWhere('request_status','re_reject')->orWhere('request_status','pm_reject')->orwhere('request_status','client_cancel')->where('organization_id',$org_id)->count();
        $completed_count = loc_request::where(['request_status'=>'re_accept','request_status'=>'qa_complete','organization_id'=>$org_id])->count();
        $total_count=$active_count+$inprogress_count+$completed_count;
        $active_pie=$inprogress_pie=$completed_pie=0;
        if($total_count != 0){
            $active_pie=number_format((float)(($active_count/$total_count)*100), 2, '.', '');
            $inprogress_pie=number_format((float)(($inprogress_count/$total_count)*100), 2, '.', '');
            $completed_pie=number_format((float)(($completed_count/$total_count)*100), 2, '.', '');
        }
        $pie_chat_data='['.$active_pie.','.$inprogress_pie.','.$completed_pie.']';
        $loc_request_data = loc_request::select('reference_id','request_status')->where (['user_id'=>$userid])->orderBy('req_id', 'desc')->limit(10)->get();
        $linechat_label='[';
        $linechat_data='[';
        $linechat_status='[';
        foreach ($loc_request_data as $lr) {
            $linechat_label .=$lr->reference_id.',';
            $linechat_status .="'".$lr->request_status."',";
            if($lr->request_status =="new")
                $request_status_value = 0;
            elseif($lr->request_status =="tr_assign")
                $request_status_value = 25;
            elseif($lr->request_status =="tr_inprogress")
                $request_status_value = 50;
            elseif($lr->request_status =="tr_completed")
                $request_status_value = 100;
                else
                $request_status_value=0;
           
            $linechat_data .=$request_status_value.',';
        }
        $linechat_label .=']';
        $linechat_data .=']';
        $linechat_status .=']';
   
        return view('home',compact('active_count','inprogress_count','completed_count','pie_chat_data','linechat_label','linechat_data','linechat_status'))->with(['page_title'=>'Quality Analist Dashboard']);
    }
    public function proofreader()
    {
        $user_role = Auth::user()->roles[0]->name;
        $userid=Auth::user()->id;
        $org_id=get_user_org('org','org_id');
        $loc_request = new loc_request();
        $active_count= loc_request::join('loc_target_file', 'loc_request.req_id', '=', 'loc_target_file.request_id')->where([['request_status', '!=', 'new'], ['request_status', '!=', 'pr_assign']])->where('pr_id', $userid)->orderBy('created_time', 'desc')->groupBy('req_id')->count();
        $inprogress_count = loc_request::orwhere('request_status','pr_inprogress')->orWhere('request_status','pr_accept')->orWhere('request_status','_accept')->orwhere('request_status','approval')->orWhere('request_status','pr_assign')->orWhere('request_status','re_reject')->orWhere('request_status','pm_reject')->orwhere('request_status','client_cancel')->where('organization_id',$org_id)->count();
        $completed_count = loc_request::where(['request_status'=>'re_accept','request_status'=>'pr_complete','organization_id'=>$org_id])->count();
        $total_count=$active_count+$inprogress_count+$completed_count;
        $active_pie=$inprogress_pie=$completed_pie=0;
        if($total_count != 0){
            $active_pie=number_format((float)(($active_count/$total_count)*100), 2, '.', '');
            $inprogress_pie=number_format((float)(($inprogress_count/$total_count)*100), 2, '.', '');
            $completed_pie=number_format((float)(($completed_count/$total_count)*100), 2, '.', '');
        }
        $pie_chat_data='['.$active_pie.','.$inprogress_pie.','.$completed_pie.']';
        $loc_request_data = loc_request::select('reference_id','request_status')->where (['user_id'=>$userid])->orderBy('req_id', 'desc')->limit(10)->get();
        $linechat_label='[';
        $linechat_data='[';
        $linechat_status='[';
        foreach ($loc_request_data as $lr) {
            $linechat_label .=$lr->reference_id.',';
            $linechat_status .="'".$lr->request_status."',";
            if($lr->request_status =="new")
                $request_status_value = 0;
            elseif($lr->request_status =="tr_assign")
                $request_status_value = 25;
            elseif($lr->request_status =="tr_inprogress")
                $request_status_value = 50;
            elseif($lr->request_status =="tr_completed")
                $request_status_value = 100;
                else
                $request_status_value=0;
           
            $linechat_data .=$request_status_value.',';
        }
        $linechat_label .=']';
        $linechat_data .=']';
        $linechat_status .=']';
        return view('home',compact('active_count','inprogress_count','completed_count','pie_chat_data','linechat_label','linechat_data','linechat_status'))->with(['page_title'=>'Proofreader Dashboard']);
    }
    public function vendor()
    {
           
      //  echo "hai";die;
        $user_role = Auth::user()->roles[0]->name;
        $userid=Auth::user()->id;
        $org_id=get_user_org('org','org_id');
        $loc_request = new loc_request();
        $active_count=  loc_request::join('loc_target_file', 'loc_request.req_id', '=', 'loc_target_file.request_id')->where([['request_status', '!=', 'new'], ['request_status', '!=', 'v_assign']])->where('v_id', $userid)->orderBy('created_time', 'desc')->groupBy('req_id')->count();
 
        $inprogress_count = loc_request::orwhere('request_status','v_inprogress')->orWhere('request_status','re_reject')->orWhere('request_status','pm_reject')->where('organization_id',$org_id)->count();
    
        $completed_count = loc_request::where(['request_status'=>'re_accept', 'request_status'=>'v_complete','organization_id'=>$org_id])->count();
        $total_count=$active_count+$inprogress_count+$completed_count;
        $active_pie=$inprogress_pie=$completed_pie=0;
        if($total_count != 0){
            $active_pie=number_format((float)(($active_count/$total_count)*100), 2, '.', '');
            $inprogress_pie=number_format((float)(($inprogress_count/$total_count)*100), 2, '.', '');
            $completed_pie=number_format((float)(($completed_count/$total_count)*100), 2, '.', '');
        }
        $pie_chat_data='['.$active_pie.','.$inprogress_pie.','.$completed_pie.']';
        $loc_request_data = loc_request::select('reference_id','request_status')->where (['user_id'=>$userid])->orderBy('req_id', 'desc')->limit(10)->get();
        $linechat_label='[';
        $linechat_data='[';
        $linechat_status='[';
        foreach ($loc_request_data as $lr) {
            $linechat_label .=$lr->reference_id.',';
            $linechat_status .="'".$lr->request_status."',";
            if($lr->request_status =="new")
                $request_status_value = 0;
            elseif($lr->request_status =="tr_assign")
                $request_status_value = 25;
            elseif($lr->request_status =="tr_inprogress")
                $request_status_value = 50;
            elseif($lr->request_status =="tr_completed")
                $request_status_value = 100;
                else
                $request_status_value=0;
           
            $linechat_data .=$request_status_value.',';
        }
        $linechat_label .=']';
        $linechat_data .=']';
        $linechat_status .=']';
        return view('home',compact('active_count','inprogress_count','completed_count','pie_chat_data','linechat_label','linechat_data','linechat_status'))->with(['page_title'=>'Remote Linguist Dashboard']);
    }
    public function finance()
    {
        $user_role = Auth::user()->roles[0]->name;
        $userid=Auth::user()->id;
        $org_id=get_user_org('org','org_id');
        $loc_request = new loc_request();
        $active_count = loc_request::where([['request_status', '!=', 'new'], ['request_status', '!=', 'client_cancel']])->where('organization_id', $org_id)->orderBy('created_time', 'desc')->count();
        $inprogress_count = loc_request::where([['request_status','!=','new'],['request_status','!=','publish'],['request_status','!=','client_cancel'],['request_status','!=','pm_reject']])->where('organization_id',$org_id)->count();
        $completed_count = loc_request::where(['request_status'=>'re_accept','organization_id'=>$org_id])->count();
        $total_count=$active_count+$inprogress_count+$completed_count;
        $active_pie=$inprogress_pie=$completed_pie=0;
        if($total_count != 0){
            $active_pie=number_format((float)(($active_count/$total_count)*100), 2, '.', '');
            $inprogress_pie=number_format((float)(($inprogress_count/$total_count)*100), 2, '.', '');
            $completed_pie=number_format((float)(($completed_count/$total_count)*100), 2, '.', '');
        }
        $pie_chat_data='['.$active_pie.','.$inprogress_pie.','.$completed_pie.']';
        $loc_request_data = loc_request::select('reference_id','request_status')->where (['user_id'=>$userid])->orderBy('req_id', 'desc')->limit(10)->get();
        $linechat_label='[';
        $linechat_data='[';
        $linechat_status='[';
        foreach ($loc_request_data as $lr) {
            $linechat_label .=$lr->reference_id.',';
            $linechat_status .="'".$lr->request_status."',";
            if($lr->request_status =="new")
                $request_status_value = 0;
            elseif($lr->request_status =="tr_assign")
                $request_status_value = 25;
            elseif($lr->request_status =="tr_inprogress")
                $request_status_value = 50;
            elseif($lr->request_status =="tr_completed")
                $request_status_value = 100;
                else
                $request_status_value=0;
           
            $linechat_data .=$request_status_value.',';
        }
        $linechat_label .=']';
        $linechat_data .=']';
        $linechat_status .=']';
        $org_id = get_user_org('org', 'org_id');
        $last15days = loc_request::where([['request_status', '!=', 'new'], ['request_status', '!=', 'client_cancel']])->where('organization_id', $org_id)->where('created_time', '<=', Carbon::now()->subdays(15))->join('loc_translation_qoute_generation_master','loc_translation_qoute_generation_master.translation_quote_id','=','loc_request.quote_gen_id')->where('loc_translation_qoute_generation_master.client_amnt_status','!=','paid')->orderBy('created_time', 'desc')->get();
        $get_user = new User();
        $loc_request = new loc_request();
        $profit= new loc_targetfile();
        return view('home',compact('active_count','inprogress_count','completed_count','pie_chat_data','linechat_label','linechat_data','linechat_status','last15days','loc_request','get_user','profit'))->with(['page_title'=>'Finance Dashboard']);
    }
}
