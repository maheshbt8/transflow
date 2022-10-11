<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;
class quote_generation_vendor extends Model
{
    protected $table="loc_quote_generation_vendor_child";
    protected $fillable = ['vendor_id','organization_id','source_lang_id','target_lang_id','vendor_assigned','vendor_status','pm_id','pm_assigned_date','vendor_child_id'];
    protected $primaryKey = 'id';
    public $timestamps	= false;

public static function vendor_lang_select($quote_id,$type='source')
    {
         $user_role = Auth::user()->roles[0]->name; 
         $userid=Auth::user()->id;
    	// if($type =='sl_tl'){
        //  if($user_role=='translator'){ 
        //          $res=DB::table('loc_request_assigned')->where(['request_id'=>$reference_id,'tr_id'=>$userid])->join('loc_languages as sl', 'loc_request_assigned.source_language', '=', 'sl.lang_id')->join('loc_languages as tl', 'loc_request_assigned.target_language', '=', 'tl.lang_id')->select('*','sl.lang_id as source_lang_id','sl.lang_name as source_lang_name','sl.lang_code as source_lang_code','tl.lang_id as target_lang_id','tl.lang_name as target_lang_name','tl.lang_code as target_lang_code')->get();
        //     }elseif($user_role=='qualityanalyst'){
        //          $res=DB::table('loc_request_assigned')->where(['request_id'=>$reference_id,'qa_id'=>$userid])->join('loc_languages as sl', 'loc_request_assigned.source_language', '=', 'sl.lang_id')->join('loc_languages as tl', 'loc_request_assigned.target_language', '=', 'tl.lang_id')->select('*','sl.lang_id as source_lang_id','sl.lang_name as source_lang_name','sl.lang_code as source_lang_code','tl.lang_id as target_lang_id','tl.lang_name as target_lang_name','tl.lang_code as target_lang_code')->get();  
        //     }elseif($user_role=='proofreader'){
        //         $res=DB::table('loc_request_assigned')->where(['request_id'=>$reference_id,'pr_id'=>$userid])->join('loc_languages as sl', 'loc_request_assigned.source_language', '=', 'sl.lang_id')->join('loc_languages as tl', 'loc_request_assigned.target_language', '=', 'tl.lang_id')->select('*','sl.lang_id as source_lang_id','sl.lang_name as source_lang_name','sl.lang_code as source_lang_code','tl.lang_id as target_lang_id','tl.lang_name as target_lang_name','tl.lang_code as target_lang_code')->get();  
        //      }else{
        //         $res=DB::table('loc_request_assigned')->where('request_id',$reference_id)->join('loc_languages as sl', 'loc_request_assigned.source_language', '=', 'sl.lang_id')->join('loc_languages as tl', 'loc_request_assigned.target_language', '=', 'tl.lang_id')->select('*','sl.lang_id as source_lang_id','sl.lang_name as source_lang_name','sl.lang_code as source_lang_code','tl.lang_id as target_lang_id','tl.lang_name as target_lang_name','tl.lang_code as target_lang_code')->get();  
        //      }
        if($type =='target'){
    		    $res=DB::table('loc_quote_generation_vendor_child')->where('quote_child_id',$quote_id)->join('loc_languages', 'loc_quote_generation_vendor_child.target_lang_id', '=', 'loc_languages.lang_id')->select('*')->get();	
    	}else{
            $res=DB::table('loc_quote_generation_vendor_child')->where('quote_child_id',$quote_id)->join('loc_languages', 'loc_quote_generation_vendor_child.source_lang_id', '=', 'loc_languages.lang_id')->select('*')->get();
        }
    	return $res;
        
 }
 public static function request_assign_update_by_id($quote_id,$update,$translation_type='id')
    { 
    	$ref=DB::table('loc_quote_generation_vendor_child')->where($translation_type,$quote_id)->update($update);
    	return $quote_id; 
    }
 }
