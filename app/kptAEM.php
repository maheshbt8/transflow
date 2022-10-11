<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class kptAEM extends Model
{
	/* Getting AEM data for PM assigned list  */	
	public function kptaempmlisting(){		
		$queryResult = DB::select('call xp_loc_request_assigned_kpt_admin_select()');
		$result = collect($queryResult);		
		return $result;			
	}
	/* Getting AEM data for PM assigned list  */
	
	
   /* getting request full details with primary id  */
   public function getting_fulldetails_aemrequest_primaryid($aem_ref_id){
		$queryResult = DB::select('call xp_loc_getting_fulldetails_aemrequest_primaryid(?)',array($aem_ref_id));
		$result = collect($queryResult);		
		return $result;	   
   }
   /* getting request full details with primary id  */
   
   
   /* Getting Languages lists by reference code */
   public function getting_language_list_reference_code($aem_reference_code){	   
	   $queryResult = DB::select('call xp_loc_aem_request_language_select_by_referencecode(?)',array($aem_reference_code));
		$result = collect($queryResult);		
		return $result;    
   }
   /* Getting Languages lists by reference code */
   
   
   /* Getting Language name from Language Master */
   public function getting_language_name($language_code){	   
	    $queryResult = DB::select('call xp_loc_language_name_with_lang_code_select(?)',array($language_code));
		$result = collect($queryResult);		
		return $result; 
	   
   }
   /* Getting Language name from Language Master */
   
   
   /* Rate Card Master Target Language where condition start here */
	public function ratecard_master_target_language_where_condition($target_language){	
					
		$queryResult = DB::select('call xp_loc_rate_card_target_language_where_condition(?)',array($target_language));
		$result = collect($queryResult);		
		return $result; 
		
	}
	/* Rate Card Master Target Language where condition start here */
	
   
   /* Translator list */
   public function getting_translator_lists(){
		$queryResult = DB::select('call xp_getting_translator_names()');
		$result = collect($queryResult);		
		return $result;		
   }
   /* Translator list */
   
   
   /* Getting Translator by reference code */
   public function getting_translators_by_referencecode($aem_reference_code){
	   $queryResult = DB::select('call xp_loc_getting_translator_names_by_reference_id(?)',array($aem_reference_code));
		$result = collect($queryResult);		
		return $result;	   
   }
   /* Getting Translator by reference code */
   
   /* KPT Reviewer list */
   public function getting_kreviewers_lists(){	   
	    $queryResult = DB::select('call xp_getting_kreviewers_names()');
		$result = collect($queryResult);		
		return $result;	   
   }
   /* KPT Reviewer list */
   
   /* Getting KReviewer by reference code */
   public function getting_kreviewers_by_referencecode($aem_reference_code){
	   
	   $queryResult = DB::select('call xp_loc_getting_kreviewer_names_by_reference_id(?)',array($aem_reference_code));
		$result = collect($queryResult);		
		return $result;
	   
   }
   /* Getting KReviewer by reference code */
   
   
   /* Getting QReviewer by reference code  */
   public function getting_qreviewers_by_referencecode($aem_reference_code){	   
		
		$queryResult = DB::select('call xp_loc_getting_qreviewer_names_by_reference_id(?)',array($aem_reference_code));
		$result = collect($queryResult);		
		return $result;	   
   }
   /* Getting QReviewer by reference code */
   
   /* AEM response metadata */
   public function aem_response_metadata($aem_reference_code){	   
	   $queryResult = DB::select('call xp_loc_aem_response_metadata_child_select(?)',array($aem_reference_code));
		$result = collect($queryResult);		
		return $result;	  
   }
   /* AEM response metadata */
   
   /* AEM xml files request */
   public function aem_xml_files_select($aem_reference_code){
	   
	    $queryResult = DB::select('call xp_loc_get_aem_request_xml_files_select(?)',array($aem_reference_code));
		$result = collect($queryResult);		
		return $result;	   
   }
  /* AEM xml files request */ 
  
  
  /* AEM getting full details of the request */
  public function getting_fulldetails_aemrequest($aem_reference_code){
	  
	  $queryResult = DB::select('call xp_loc_getting_fulldetails_aemrequest(?)',array($aem_reference_code));
		$result = collect($queryResult);		
		return $result;	
  }
 /* AEM getting full details of the request */ 
 
 
  /* AEM full details based on language */
	public function getting_fulldetails_aemrequest_language($aem_reference_code){
		$queryResult = DB::select('call xp_loc_getting_fulldetails_aemrequest_language(?)',array($aem_reference_code));
		$result = collect($queryResult);		
		return $result;  
	}
	/* AEM full details based on language */
	
	
	/* Getting Translated strings start here */
	public function getting_translated_string_aem_request($aem_reference_code,$aem_object_id,$source_language,$target_language){		
		$queryResult = DB::select('call xp_loc_translated_strings_by_reference_code_from_aem_request(?,?,?,?)',array($aem_reference_code,$aem_object_id,$source_language,$target_language));
		$result = collect($queryResult);	
		return $result;		
	}
	/* Getting Translated strings end here */
	
	
	/* AEM update status request */
	public function update_aem_status_request($aem_reference_code,$tm_status){
		$queryResult = DB::select('call xp_loc_update_aem_status_request(?,?,?)',array($aem_reference_code,$tm_status,'@output'));
		$result = collect($queryResult);	
		return $result;		
	}
	/* AEM update status request */
	
	/* AEM assigned translator insert */
	public function aem_request_assigned_translator_insert($aem_reference_code,$id_translator){	
		$queryResult = DB::select('call xp_loc_aem_request_assigned_translator_insert(?,?,?)',array($aem_reference_code,$id_translator,'@output'));
		$result = collect($queryResult);	
		return $result;	
	}
	/* AEM assigned translator insert */
	
	
	/* AEM assigned translator insert */
	public function aem_request_assigned_kreviewr_insert($aem_reference_code,$id_kreviewer){	
		$queryResult = DB::select('call xp_loc_aem_request_assigned_kreviewr_insert(?,?,?)',array($aem_reference_code,$id_kreviewer,'@output'));
		$result = collect($queryResult);	
		return $result;	
	}
	/* AEM assigned translator insert */
	
	
	
	/* AEM request XML file job object update status */
	public function aem_request_xml_file_job_objectid_update_status($tm_status,$aem_reference_code){
		$queryResult = DB::select('call xp_loc_aem_request_xml_file_job_objectid_update_status(?,?)',array($tm_status,$aem_reference_code));
		$result = collect($queryResult);	
		return $result;			
	}
	/* AEM request XML file job object update status */
	
	
	 /* function for Google Translate curl detect language request */
	function gtranslate_text_from_soucefile($target_text,$target_lang){		
		
		if($target_text !=""){
			$target_text  =  mb_convert_encoding($target_text, "UTF-8");
			$post_data 	  =  json_encode(array("text"=>$target_text,"dest_lg"=>$target_lang));
				
			$curl = curl_init();
					curl_setopt_array($curl, array(
					CURLOPT_URL =>"https://webapp.transflowtms.com/translate2",
					CURLOPT_RETURNTRANSFER => true,			
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "POST",
					CURLOPT_POSTFIELDS => $post_data,
					CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"content-type: application/json",			
				),
				));
				
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				
			$response = curl_exec($curl);
			if ($errno = curl_errno($curl)) {
	            $error_message = curl_strerror($errno);
	            return '';
        	}
			curl_close($curl);		
			$response = json_decode($response);	
			return $response->translatedText;				
		}		
	}
	/* function for Google Translate curl detect language request */
	
	
	
	 /* Web API for Tranlsation Memory function starts here */   
   function get_translationmemory_by_source($source_lang,$tareget_lang,$source_text,$domain='1'){
   
				$arr_tm = array();
				$arr_tm["source_lang"]  = $source_lang;
				$arr_tm["source_text"]  = $source_text;
				$arr_tm["tareget_lang"] = $tareget_lang;				
				$arr_tm["domain_id"] = $domain;				
				
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


 /* Web API for Search Suggestion Tranlsation Memory function starts here */   
   function get_translationmemory_by_searchsuggestion($source_lang,$tareget_lang,$source_text){
   
				$arr_tm = array();
				$arr_tm["source_lang"]  = $source_lang;
				$arr_tm["source_text"]  = $source_text;
				$arr_tm["tareget_lang"] = $tareget_lang;				
				
				$json_tm_dump = json_encode($arr_tm);
				
				$webapi_url = "https://webapi.transflowtms.com/api/getbysearchsuggestion";				
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


	/* AEM request translation job pull api insert */
	function aem_request_translation_job_pull_api_insert($arr_aemrequest_translation_job){
		
		$queryResult = DB::select('call xp_loc_aem_request_translation_job_insert(?,?,?,?,?,?,?,?,?,?)',array($arr_aemrequest_translation_job[0],$arr_aemrequest_translation_job[1],$arr_aemrequest_translation_job[2],$arr_aemrequest_translation_job[3],$arr_aemrequest_translation_job[4],$arr_aemrequest_translation_job[5],$arr_aemrequest_translation_job[6],$arr_aemrequest_translation_job[7],$arr_aemrequest_translation_job[8],$arr_aemrequest_translation_job[9],'@output'));
		$result = collect($queryResult);	
		return $result;			
	}
	/* AEM request translation job pull api insert */
	
	
	
	/* AEM request translation pull api job data insert */
	function aem_request_translation_pull_api_job_data_insert($arr_aemrequest_translation_job){		
		$queryResult = DB::select('call xp_loc_aem_request_translation_job_data_insert(?,?,?,?,?,?,?)',array($arr_aemrequest_translation_job[0],$arr_aemrequest_translation_job[1],$arr_aemrequest_translation_job[2],$arr_aemrequest_translation_job[3],$arr_aemrequest_translation_job[4],$arr_aemrequest_translation_job[5],$arr_aemrequest_translation_job[6],'@output'));
		$result = collect($queryResult);	
		return $result;		
	}
	/* AEM request translation pull api job data insert */
	
	
	
	/* Target XML AEM request job status select */
	function target_xml_aem_request_job_status_select($arr_aem_request_job){	
			
		$queryResult = DB::select('call xp_loc_target_xml_aem_request_job_status_select(?)',array($arr_aem_request_job[0]));		
		
		$result = collect($queryResult);	
		return $result;
		
	}
	/* AEM request translation pull api job data insert */
	
	
	/* Target XML AEM request job status select */
	function target_xml_aem_request_job_object_status_select($arr_aem_request_job){	
			
		$queryResult = DB::select('call xp_loc_target_xml_aem_request_object_status_select(?)',array($arr_aem_request_job[0]));		
		
		$result = collect($queryResult);	
		return $result;		
	}
	/* AEM request translation pull api job data insert */
	
	
	
	 /* aem_response_metadata_master_select list */
   public function aem_response_metadata_master_select(){
		$queryResult = DB::select('call xp_loc_aem_response_metadata_master_select()');
		$result = collect($queryResult);		
		return $result;		
   }
   /* aem_response_metadata_master_select list */
   
   
   
   /* AEM request translation pull api job data insert */
	function aem_request_pull_api_create_request_insert($arr_aem_request){		
		$queryResult = DB::select('call xp_loc_create_aem_request_cron(?,?,?,?,?,?,?,?,?)',array($arr_aem_request[0],$arr_aem_request[1],$arr_aem_request[2],$arr_aem_request[3],$arr_aem_request[4],$arr_aem_request[5],$arr_aem_request[6],$arr_aem_request[7],'@output'));
		$result = collect($queryResult);	
		return $result;		
	}
	/* AEM request translation pull api job data insert */
	
	
	
	
	/* AEM request language insert */
	function aem_request_languages_insert($arr_aem_request){		
		$queryResult = DB::select('call xp_loc_aem_request_languages_insert(?,?,?,?,?,?,?,?,?)',array($arr_aem_request[0],$arr_aem_request[1],$arr_aem_request[2],$arr_aem_request[3],$arr_aem_request[4],$arr_aem_request[5],$arr_aem_request[6],$arr_aem_request[7],'@output'));
		$result = collect($queryResult);	
		return $result;		
	}
	/* AEM request language insert */
	
	
	
	/* AEM request language insert */
	function aem_request_assigned_client_pm_insert($arr_aem_request){		
		$queryResult = DB::select('call xp_loc_aem_request_assigned_client_pm_insert(?,?)',array($arr_aem_request[0],$arr_aem_request[1],'@output'));
		$result = collect($queryResult);	
		return $result;		
	}
	/* AEM request language insert */
   
   
   
   
   /* AEM request language insert */
	function create_aem_request_xml_files($arr_aem_request){		
		$queryResult = DB::select('call xp_loc_create_aem_request_xml_files(?,?,?,?,?,?,?)',array($arr_aem_request[0],$arr_aem_request[1],$arr_aem_request[2],$arr_aem_request[3],$$arr_aem_request[4],$arr_aem_request[5],$arr_aem_request[6],'@output'));
		$result = collect($queryResult);	
		return $result;		
	}
	/* AEM request language insert */
   
   
   
   
   
   
}
