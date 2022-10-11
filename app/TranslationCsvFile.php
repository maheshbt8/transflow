<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;


class TranslationCsvFile extends Model
{
	/* Getting AEM data for Client PM assigned list  */	
	public function aem_listing_assigned_translator_results($user_id){				
		$queryResult = DB::select('call xp_loc_request_assigned_translator_select(?)',array($user_id));
		$result = collect($queryResult);		
		return $result;	
	}
	/* Getting AEM data for Client PM assigned list  */
	
	
	// protected $table="file_translation_memory";
    // protected $fillable = ['id','domain_id','organization_id','source_language','target_language','translation_files_name','org_id','user_id','created_by'];
    // protected $primaryKey = 'id';
    // public $timestamps	= false;
	 
  
	
	
	 /* Web API for Tranlsation Memory function starts here */   
   function get_translationmemory_by_source($source_lang,$tareget_lang,$source_text,$domain){
   
				$arr_tm = array();
				$arr_tm["source_lang"]  = $source_lang;
				$arr_tm["source_text"]  = $source_text;
				$arr_tm["tareget_lang"] = $tareget_lang;
				$arr_tm['domain']=$domain;				
				
				$json_tm_dump = json_encode($arr_tm);
				
				$webapi_url = "https://webapi.transflowtms.com/api/getbysource";				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $webapi_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERPWD, "admin:tmserver");
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json_tm_dump);
				
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				
				$json_output = curl_exec($ch);
				$info = curl_getinfo($ch);
				$err = curl_error($ch);
				
				curl_close($ch);
			return json_decode($json_output);
   }   
    /* Web API for Tranlsation Memory function end here */ 
	
	
	

	/* AEM request insert or update translated text row by row */
	public function aem_request_insert_or_update_translated_text_row_by_row($arr_import_translation){		
		
		$queryResult = DB::select('call xp_loc_aem_request_insert_or_update_translated_text_row_by_row(?,?,?,?,?,?,?,?,?,?)',array($arr_import_translation[0],$arr_import_translation[1],$arr_import_translation[2],$arr_import_translation[3],$arr_import_translation[4],$arr_import_translation[5],$arr_import_translation[6],$arr_import_translation[7],$arr_import_translation[8],$arr_import_translation[9]));
		$result = collect($queryResult);	
		return $result;		
		
	}
	

	
	
	
	
   
}
