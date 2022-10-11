<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;
use App\clientorg_invoice_details;
use App\loc_invoice;
use App\loc_targetfile;
use App\vendor_invoice_details;
class loc_request extends Model
{
    protected $table="loc_request";
    protected $fillable = ['req_id','user_id','reference_id','project_name','brief_description','source_type','source_text','source_file_path','special_instructions','request_status','priority','category','created_time','client_org_id','organization_id','quote_gen_id','document_type'];
    protected $primaryKey = 'req_id';
    public $timestamps	= false;

    public static function target_lang($array)
    {
    	return DB::table('loc_request_lang')->insert($array);
    }
    public static function target_lang_select($reference_id)
    {
    	$ref=DB::table('loc_request_lang')->where('reference_id',$reference_id)->first();
    	if($ref){
    		return true;
    	}else{
    		return false;
    	}
    }
    public static function target_lang_update($reference_id,$update)
    {
    	$ref=DB::table('loc_request_lang')->where('reference_id',$reference_id)->update($update);
    	return $reference_id; 
    }
    public static function request_assign_update_by_id($request_id,$update,$req_type='id')
    {
    	$ref=DB::table('loc_request_assigned')->where($req_type,$request_id)->update($update);
    	return $request_id; 
    }
    
    public static function loc_reference_multiple_files_select($reference_id)
    {
    	$ref=DB::table('loc_request_multiple_files')->where('reference_id',$reference_id)->first();
    	if($ref){
    		return true;
    	}else{
    		return false;
    	}
    }
    public static function loc_reference_multiple_files_insert($data)
    {
    	$ref=DB::table('loc_request_multiple_files')->insertGetId($data);
    	return $ref; 
    }
    public static function loc_reference_multiple_files_update($reference_id,$update)
    {
    	$ref=DB::table('loc_request_multiple_files')->where('reference_id',$reference_id)->update($update);
    	return $reference_id; 
    }
    public static function loc_reference_lang_select($reference_id,$type='source')
    {
      
         $user_role = Auth::user()->roles[0]->name; 
         $userid=Auth::user()->id;
    	if($type =='sl_tl'){
         if($user_role=='translator'){ 
                $where=['loc_request_assigned.request_id'=>$reference_id,'ltf.tr_id'=>$userid];
            }elseif($user_role=='qualityanalyst'){
                $where=['loc_request_assigned.request_id'=>$reference_id,'ltf.qa_id'=>$userid];
            }elseif($user_role=='proofreader'){
                $where=['loc_request_assigned.request_id'=>$reference_id,'ltf.pr_id'=>$userid];
             }elseif($user_role=='vendor'){
                $where=['loc_request_assigned.request_id'=>$reference_id,'ltf.v_id'=>$userid];
             }
             else{
                  $where=['loc_request_assigned.request_id'=>$reference_id];
                //   print_r($reference_id);die;
             }
             $res=DB::table('loc_request_assigned')->where($where)->join('loc_quote_sourcelang as lqs', 'loc_request_assigned.loc_source_id', '=', 'lqs.id')->join('loc_languages as sl', 'lqs.sourcelang_id', '=', 'sl.lang_id')->join('loc_languages as tl', 'loc_request_assigned.target_language', '=', 'tl.lang_id')->join('loc_target_file as ltf','loc_request_assigned.id','=','ltf.req_lang_id')->select('*','loc_request_assigned.id as mid','sl.lang_id as source_lang_id','sl.lang_name as source_lang_name','sl.lang_code as source_lang_code','tl.lang_id as target_lang_id','tl.lang_name as target_lang_name','tl.lang_code as target_lang_code')->get();
        }elseif($type =='target'){
    		    $res=DB::table('loc_request_assigned')->where('request_id',$reference_id)->join('loc_languages', 'loc_request_assigned.target_language', '=', 'loc_languages.lang_id')->select('*')->get();	
                
            }else{
            $res=DB::table('loc_request_assigned')->where('request_id',$reference_id)->join('loc_languages', 'loc_request_assigned.loc_source_id', '=', 'loc_languages.lang_id')->select('*')->get();
            
        }
    	return $res;
    }
    public static function loc_sub_request_insert($array)
    {
        return DB::table('loc_update_request')->insert($array);
    }
    public static function loc_request_linguist_insert($array)
    {
        return DB::table('loc_request_assigned_linguist')->insert($array);
    }
    public static function getting_linguist_by_reference_id($reference_id)
    {
        return DB::table('loc_request_assigned_linguist')->where(['reference_id'=>$reference_id])->join('users','loc_request_assigned_linguist.user_id','=','users.id')->select('loc_request_assigned_linguist.*','users.name')->get();
    }
    public static function check_create_request_option($quote_id)
    {
        $res=DB::table('loc_request')->where(['quote_gen_id'=>$quote_id])->first();
        if($res){
            return true;
        }
        return false;
    }
    public static function getpendingivoice($request_id){
        $edit_request= DB::table('loc_request')->where('req_id', $request_id)->first();
        $where_status=['org_id'=>$edit_request->organization_id,'clientorg'=>$edit_request->client_org_id,'req_id'=>$edit_request->req_id];
        $sum_of_pending=clientorg_invoice_details::where($where_status)->sum('pending');
        $sum_of_paid=clientorg_invoice_details::where($where_status)->sum('paid');
        $grand_total=$sum_of_pending-$sum_of_paid;
        return ['pending'=>$grand_total,'booked'=>$sum_of_pending,'received'=>$sum_of_paid];
    }
    public function getreqinvoices($request_id)
    {
        return $client_invoice_list=loc_invoice::where(['req_id'=>$request_id,'invoice_type'=>'client'])->get();
    }

    public static function getvendorpendinginvoice($request_id){
        $edit_request = loc_request::where('req_id', $request_id)->first();
        $where_status=['req_id'=>$edit_request->req_id];
       
        $vendor_sum_of_pending=vendor_invoice_details::where($where_status)->sum('pending');
        $vendor_sum_of_paid=vendor_invoice_details::where($where_status)->sum('paid');
        $vendor_grand_total=$vendor_sum_of_pending-$vendor_sum_of_paid;
        return ['pending'=>$vendor_grand_total,'booked'=>$vendor_sum_of_paid,'received'=>$vendor_sum_of_pending];
    }
    public static function getreqvendorinvoice($request_id){
      
        return $vendor_invoice_list=loc_invoice::where(['req_id'=>$request_id,'invoice_type'=>'vendor'])->get();
        //return $vendor_sum_of_pending=loc_targetfile::where(['request_id'=>$request_id,'v_id'=>$vendor_id])->sum('v_total');
    }

    public static function getvendortotalprofit($request_id){
        $where_status=['req_id'=>$request_id];
        $sum_of_pending=(clientorg_invoice_details::where($where_status)->sum('pending')) ?? 0;
        //$vendor_sum_of_pending=vendor_invoice_details::where($where_status)->sum('pending');
        $vendor_work_per=(loc_targetfile::where(['request_id'=>$request_id])->sum('work_per')) ?? 0;
        $vendor_sum_of_pending=(loc_targetfile::where(['request_id'=>$request_id])->sum('v_total')) ?? 0;
        $p_total=0;
        if($vendor_work_per>0){
            $p_total=(($sum_of_pending*$vendor_work_per)/100)-$vendor_sum_of_pending;
        }
        return ['client_pending'=>$sum_of_pending,'vendor_pending'=>$vendor_sum_of_pending,'profit'=>$p_total];
       
    }
}
