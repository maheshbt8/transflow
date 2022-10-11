<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class kptAEMtranslator extends Model
{
	/* Getting AEM data for Client PM assigned list  */	
	public function aem_listing_assigned_translator_results($user_id){				
		$queryResult = DB::select('call xp_loc_request_assigned_translator_select(?)',array($user_id));
		$result = collect($queryResult);		
		return $result;	
	}
	/* Getting AEM data for Client PM assigned list  */


	 /* AEM xml files request */
   public function aem_xml_files_select($aem_reference_code){	   
	    $queryResult = DB::select('call xp_loc_get_aem_request_xml_files_select(?)',array($aem_reference_code));
		$result = collect($queryResult);		
		return $result;	   
   }
  /* AEM xml files request */ 
  
  
  /* AEM full details based on language */
	public function getting_fulldetails_aemrequest_language($aem_reference_code){
		$queryResult = DB::select('call xp_loc_getting_fulldetails_aemrequest_language(?)',array($aem_reference_code));
		$result = collect($queryResult);		
		return $result;  
	}
	/* AEM full details based on language */
	
	
	/* Getting Languages lists by reference code */
   public function getting_language_list_reference_code($aem_reference_code){	   
	   $queryResult = DB::select('call xp_loc_aem_request_language_select_by_referencecode(?)',array($aem_reference_code));
		$result = collect($queryResult);		
		return $result;    
   }
   /* Getting Languages lists by reference code */
   
   /* Getting AEM request XML files with object */
   public function xp_loc_get_aem_request_xml_files_with_object_select($aem_reference_code,$aem_object_id){
	   $queryResult = DB::select('call xp_loc_get_aem_request_xml_files_with_object_select(?,?)',array($aem_reference_code,$aem_object_id));
		$result = collect($queryResult);		
		return $result;	   
   }
    /* Getting AEM request XML files with object */
	
	
	/* Getting Translated strings start here */
	public function getting_translated_string_aem_request($aem_reference_code,$aem_object_id,$source_language,$target_language){		
		$queryResult = DB::select('call xp_loc_translated_strings_by_reference_code_from_aem_request(?,?,?,?)',array($aem_reference_code,$aem_object_id,$source_language,$target_language));
		$result = collect($queryResult);	
		return $result;		
	}
	/* Getting Translated strings end here */
	
	
	
	 /* Web API for Tranlsation Memory function starts here */   
   function get_translationmemory_by_source($source_lang,$tareget_lang,$source_text){
   
				$arr_tm = array();
				$arr_tm["source_lang"]  = $source_lang;
				$arr_tm["source_text"]  = $source_text;
				$arr_tm["tareget_lang"] = $tareget_lang;				
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
				$info = curl_getinfo($ch);
				$err = curl_error($ch);
				if(curl_errno($ch))
				{	
					return [];
				}else{
					$json_output = curl_exec($ch);
				}
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
	
	/* AEM request insert or update translated text row by row */
	public function aem_request_target_xml_file_insert($arr_aemrequest_target_xml){		
				
		$queryResult = DB::insert('call xp_loc_aem_request_target_xml_file_insert(?,?,?,?)',array($arr_aemrequest_target_xml[0],$arr_aemrequest_target_xml[1],$arr_aemrequest_target_xml[2],'output'));
		$result = collect($queryResult);	
		return $result;	
	}
	/* AEM request insert or update translated text row by row */
	
	
	/* KPT Translation completed status start here */
	public function aem_request_translation_verification_with_objectid($aem_reference_code,$aem_objectid){
		$queryResult = DB::select('call xp_loc_aem_request_count_target_text_translation_objectid_select(?,?)',array($aem_reference_code,$aem_objectid));
		$arr_aem_data = collect($queryResult);
		$output = $arr_aem_data[0]->target_text_count;
		return $output;	
	}	
	/* KPT Translation completed status end here */

	/* AEM request XML file with ref job object id Update status */
	public function aem_request_xml_file_aem_ref_job_objectid_update_status($aem_reference_code,$hid_aem_object_id){
		
		$queryResult = DB::select('call xp_loc_aem_request_xml_file__aem_ref_job_objectid_update_status(?,?,?)',array('KPT Translation Completed',$aem_reference_code,$hid_aem_object_id));
		$result = collect($queryResult);	
		return $result;	
		
	}
	/* AEM request XML file with ref job object id Update status */
	
	/* AEM request XML files to get status */
	public function aem_request_xml_files_get_status_select($aem_reference_code){		
		$queryResult = DB::select('call xp_loc_get_aem_request_xml_files_get_status_select(?)',array($aem_reference_code));
		$result = collect($queryResult);	
		return $result;	
	}
	/* AEM request XML files to get status */
	
	
	public function update_aem_status_request($aem_reference_code){		
		$queryResult = DB::select('call xp_loc_update_aem_status_request(?,?,?)',array($aem_reference_code,"KPT Translation Completed",'output'));
		$result = collect($queryResult);	
		return $result;	
	}
	
	
	
	/* AEM request XML file with ref job object id Update status */
	public function aem_request_xml_file_aem_ref_job_objectid_update_status1($aem_reference_code,$hid_aem_object_id){
		
		$queryResult = DB::select('call xp_loc_aem_request_xml_file__aem_ref_job_objectid_update_status(?,?,?)',array('Translation in-Progress',$aem_reference_code,$hid_aem_object_id));
		$result = collect($queryResult);	
		return $result;	
		
	}
	/* AEM request XML file with ref job object id Update status */
	
		/* function for dell curl request */
	function dell_curl_detect_language_request($target_text){
		if($target_text !=""){
				$target_text = mb_convert_encoding($target_text, "UTF-8");
				$post_data = json_encode(array("text"=>$target_text));				
				$curl = curl_init();
				curl_setopt_array($curl, array(
				CURLOPT_URL => "https://dev.transflowtms.com/detect_new",
				CURLOPT_RETURNTRANSFER => true,			
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $post_data,
				CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"content-type: application/json"			
				),
				));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);			
			return $response;				
		}
	}
	/* function for dell curl request */
	/* function for dell curl request */
	function dell_curl_request($target_text){
		if($target_text !=""){
				$target_text = mb_convert_encoding($target_text, "UTF-8");
				$post_data = json_encode(array("text"=>$target_text,"dest_lg"=>"en"));				
				$curl = curl_init();
				curl_setopt_array($curl, array(
				CURLOPT_URL => "https://dev.transflowtms.com/translate",
				CURLOPT_RETURNTRANSFER => true,			
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $post_data,
				CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"content-type: application/json"			
				),
				));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);			
			return $response;			  
				
		}
	}
	/* function for dell curl request */
   
   
   
	
	function nntranslation($query)
	{
		$arr_tm = array();
		$arr_tm["query"]  = $query;
		$json_tm_dump = json_encode($arr_tm);
		$webapi_url = "https://webapp.transflowtms.com/neuralnetwork";				
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_URL, $webapi_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_tm_dump);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$info = curl_getinfo($ch);
		//$err = curl_error($ch);
		if(curl_errno($ch))
		{
			//echo 'Curl error:'.curl_error($ch);die;
			return '';
			/*$arr_tm["TranslatedText"]  = '';
			$json_output = json_encode($arr_tm);
			print_r($json_output);die;
*/		}else{
			$json_output = curl_exec($ch);
		}
		curl_close($ch);
		$res=json_decode($json_output);
		if(isset($res->TranslatedText) && $res->TranslatedText != ''){
			return $res->TranslatedText;
		}
		return '';
		/*if($err){
			$arr_tm["TranslatedText"]  = '';
			$json_output = json_encode($arr_tm);
			print_r($json_output);die;
		}*/
		
	}
}
