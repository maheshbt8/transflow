<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\clientorg_invoice_details;
use App\vendor_invoice_details;
use App\loc_request;
use App\User;
use Auth;
use App\Finance;
use App\loc_invoice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\currencies;
use App\Clientorganization;


class financeController extends Controller
{
    public function clientinvoice(Request $request){
        // if (!checkpermission('client_user')) {
        //     return abort(401);
        // }
        $edit_request =new loc_request();
        $user_role = Auth::user()->roles[0]->name; 
        $client_sum_of_pending=0; 
        // if($user_role == "administrator"){
        //     $client_invoice_list=loc_invoice::where(['invoice_type'=>'client'])->get();
        //     $client_invoice_history=clientorg_invoice_details::get();
        //     $client_billed_amount=loc_invoice::where(['invoice_type'=>'client'])->sum('invoicing_amount');
        //     $client_sum_of_pending=clientorg_invoice_details::sum('pending');
        //     $client_sum_of_paid=clientorg_invoice_details::sum('paid');
        // }elseif($user_role == 'orgadmin'){
            $org_id=get_user_org('org','org_id');
            
            if(isset($_GET['client']) && $_GET['client'] != ''){
                $where_state=['clientorg_invoice_details.clientorg'=>$_GET['client']];
                $where_req=['client_org_id'=>$_GET['client']];
                $client_sel=$_GET['client'];
            }else{
                $where_state=['clientorg_invoice_details.org_id'=>$org_id];
                $where_req=[];
                $client_sel='';
               // echo "hai";die;
            }
            if(isset($_GET['currency']) && $_GET['currency'] != ''){
                $where_state=array_merge($where_state,['loc_translation_qoute_generation_master.translation_quote_currency'=>$_GET['currency']]);
                $currency=$_GET['currency'];
            }else{
                $where_state=array_merge($where_state,['loc_translation_qoute_generation_master.translation_quote_currency'=>1]);
                $currency=1;
            }
            if(isset($_GET['sales']) && $_GET['sales'] != ''){
                $where_state=array_merge($where_state,['loc_translation_qoute_generation_master.translation_user_id'=>$_GET['sales']]);
                $salse_sel=$_GET['sales'];
            }else{
                $salse_sel='';
            }

            if((isset($_GET['start_date']) && $_GET['start_date']) && (isset($_GET['end_date']) && $_GET['end_date'])){
                $s_date=$_GET['start_date'];
                $e_date=$_GET['end_date'];               
            }else{
                $query_date=date('Y-m-d');
                $s_date = date('Y-m-01', strtotime($query_date));
                $e_date = date('Y-m-d');//date('Y-m-t', strtotime($query_date));
            }
            //$client_invoice_list=loc_invoice::join('loc_request','loc_invoice.req_id','=','loc_request.req_id')->where(['loc_invoice.invoice_type'=>'client','loc_request.organization_id'=>$org_id])->where($where_req)->get();
            $client_invoice_history=clientorg_invoice_details::select('clientorg_invoice_details.*','loc_translation_qoute_generation_master.translation_user_id')->leftjoin('loc_request','clientorg_invoice_details.req_id','=','loc_request.req_id')->leftjoin('loc_translation_qoute_generation_master','loc_request.quote_gen_id','=','loc_translation_qoute_generation_master.translation_quote_id')->where('loc_request.request_status','!=','cancel')->where($where_state)->whereBetween('clientorg_invoice_details.created_at', [$s_date.' 00:00:00', $e_date.' 23:59:59'])->get();
           
            //$client_billed_amount=loc_invoice::join('loc_request','loc_invoice.req_id','=','loc_request.req_id')->where(['loc_invoice.invoice_type'=>'client','loc_request.organization_id'=>$org_id])->where($where_req)->sum('invoicing_amount');
            $client_sum_of_pending=clientorg_invoice_details::select('clientorg_invoice_details.*')->leftjoin('loc_request','clientorg_invoice_details.req_id','=','loc_request.req_id')->leftjoin('loc_translation_qoute_generation_master','loc_request.quote_gen_id','=','loc_translation_qoute_generation_master.translation_quote_id')->where('loc_request.request_status','!=','cancel')->where($where_state)->whereBetween('clientorg_invoice_details.created_at', [$s_date.' 00:00:00', $e_date.' 23:59:59'])->sum('pending');
            $client_sum_of_paid=clientorg_invoice_details::select('clientorg_invoice_details.*')->leftjoin('loc_request','clientorg_invoice_details.req_id','=','loc_request.req_id')->leftjoin('loc_translation_qoute_generation_master','loc_request.quote_gen_id','=','loc_translation_qoute_generation_master.translation_quote_id')->where('loc_request.request_status','!=','cancel')->where($where_state)->whereBetween('clientorg_invoice_details.created_at', [$s_date.' 00:00:00', $e_date.' 23:59:59'])->sum('paid');
        // }
        


        //$client_unbilled_amount=$client_sum_of_pending-$client_billed_amount;
        $client_grand_total=$client_sum_of_pending-$client_sum_of_paid;
        $get_user = new User();
        $loc_request = new loc_request();
        $currency_list = currencies::where('status', 'Active')->get();
        $sales_list = User::getAuthenticatedUsers($org_id,['sales']);
        $clients_list = Clientorganization::where(['org_status'=>'1','kpt_org'=>$org_id])->get();
        return view('admin.finance.clientdetail',compact('get_user','edit_request','client_sum_of_pending','client_sum_of_paid','client_grand_total','client_invoice_history','s_date','e_date','currency_list','sales_list','currency','salse_sel','clients_list','client_sel')); 
    }
    public function vendorinvoice(Request $request){

        $userid = Auth::user()->id;
        $edit_request =new loc_request();
        $user_role = Auth::user()->roles[0]->name;      
		// if($user_role != "administrator"){
            $org_id=get_user_org('org','org_id');
        // }else{
        //     $org_id='';
        // }
        if(isset($_GET['vendor']) && $_GET['vendor'] != ''){
            $orgvendor_ids=[$_GET['vendor']];
            
        }else{  
            $authenticated_users = User::whereHas(
            'roles', function($q) {
            $q->where('name','vendor');
            })->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')->where('user_orgizations.org_id',$org_id)->get();
            $orgvendors=$authenticated_users->toArray();
            $orgvendor_ids=array_column((array)$orgvendors,'id');
        }
        if((isset($_GET['start_date']) && $_GET['start_date']) && (isset($_GET['end_date']) && $_GET['end_date'])){
            // $s_date = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            // $e_date = Carbon::now()->subMonth()->endOfMonth()->toDateString();
          
            $s_date=$_GET['start_date'];
            $e_date=$_GET['end_date'];
        }else{
             $e_date = date('Y-m-d');
             $s_date = date("Y-m-d", strtotime ( '-1 month' , strtotime ( $e_date ) )) ;
          
        }
      
        $vendor_invoice_history=vendor_invoice_details::select('vendor_invoice_details.*')->leftJoin('loc_request','vendor_invoice_details.req_id','=','loc_request.req_id')->where('loc_request.request_status','!=','cancel')->whereIn('vendor_invoice_details.user_id',$orgvendor_ids)->whereBetween('vendor_invoice_details.created_at', [$s_date.' 00:00:00', $e_date.' 23:59:59'])->get();
        $vendor_sum_of_pending=vendor_invoice_details::select('vendor_invoice_details.*')->leftJoin('loc_request','vendor_invoice_details.req_id','=','loc_request.req_id')->where('loc_request.request_status','!=','cancel')->whereIn('vendor_invoice_details.user_id',$orgvendor_ids)->whereBetween('vendor_invoice_details.created_at', [$s_date.' 00:00:00', $e_date.' 23:59:59'])->sum('pending');
        $vendor_sum_of_paid=vendor_invoice_details::select('vendor_invoice_details.*')->leftJoin('loc_request','vendor_invoice_details.req_id','=','loc_request.req_id')->where('loc_request.request_status','!=','cancel')->whereIn('vendor_invoice_details.user_id',$orgvendor_ids)->whereBetween('vendor_invoice_details.created_at', [$s_date.' 00:00:00', $e_date.' 23:59:59'])->sum('paid');
        $vendor_grand_total=$vendor_sum_of_pending-$vendor_sum_of_paid;
        $get_user = new User();
        $users = Finance::financeUsers($org_id);
        $loc_request = new loc_request();
            //  echo "<pre>";
            //  print_r($loc_request);die;
             
        return view('admin.finance.vendordetail',compact('get_user','edit_request','vendor_sum_of_pending','vendor_sum_of_paid','vendor_grand_total','vendor_invoice_history','s_date','e_date')); 
    }

//     public function get_invoice(Request $request){


//         $start_date = Carbon::parse($request->start_date)
//         ->toDateTimeString();
      
// $end_date = Carbon::parse($request->end_date)
//         ->toDateTimeString();
// $invoice = new vendor_invoice_details;

// $get_invoice= vendor_invoice_details::whereBetween('created_at',[$start_date,$end_date])->get();

// //return view('admin.finance.vendordetail',$get_invoice); 
// //return redirect()->route('admin.finance.vendordetail');
//     }
    
       
}
