<?php

namespace App\models;

use App\locQuoteSourcelang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;
class loc_translation_master extends Model
{
    protected $table='loc_translation_qoute_generation_master';
    public $timestamps	= false;
  

    public static function quote_lang_select($quote_id,$type='source',$source_id='')
    {
         $user_role = Auth::user()->roles[0]->name; 
         $userid=Auth::user()->id;
        //  $s_lang=locQuoteSourcelang::where('quote_id',$quote_id)->get();
       
        if($type =='target'){
            if($source_id){
                $where=['quote_id'=>$quote_id,'loc_source_id'=>$source_id];
            }else{
                $where=['quote_id'=>$quote_id];
            }
    		    $res=DB::table('loc_request_assigned')->where($where)->join('loc_languages', 'loc_request_assigned.target_language', '=', 'loc_languages.lang_id')->select('*')->get();
    	}else{
            $res=DB::table('loc_quote_sourcelang')->where('quote_id',$quote_id)->join('loc_languages', 'loc_quote_sourcelang.sourcelang_id', '=', 'loc_languages.lang_id')->select('*')->get();
        }
    	return $res;
        
 	}
     public static function request_lang_select($req_id,$type='source',$source_id='')
     {
          $user_role = Auth::user()->roles[0]->name; 
          $userid=Auth::user()->id;
         //  $s_lang=locQuoteSourcelang::where('quote_id',$quote_id)->get();
        
         if($type =='target'){
             if($source_id){
                 $where=['request_id'=>$req_id,'loc_source_id'=>$source_id];
             }else{
                 $where=['request_id'=>$req_id];
             }
                 $res=DB::table('loc_request_assigned')->where($where)->join('loc_languages', 'loc_request_assigned.target_language', '=', 'loc_languages.lang_id')->select('*')->get();
         }else{
             $res=DB::table('loc_quote_sourcelang')->where('request_id',$req_id)->join('loc_languages', 'loc_quote_sourcelang.sourcelang_id', '=', 'loc_languages.lang_id')->select('*')->get();
         }
         return $res;
         
      }
     public static function quote_service_select($quote_id,$source_id='')
     {
         $res=DB::table('loc_request_assigned')->where(['quote_id'=>$quote_id,'loc_source_id'=>$source_id])->join('loc_service', 'loc_request_assigned.service_type', '=', 'loc_service.id')->select('*')->get(); 
         return $res;
         
      }
}
