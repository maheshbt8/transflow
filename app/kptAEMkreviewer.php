<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class kptAEMkreviewer extends Model
{
	/* Getting AEM data for KReviewer assigned list  */	
	public function aem_listing_assigned_kreviewer_results($user_id){				
		$queryResult = DB::select('call xp_loc_request_assigned_kreviewer_select(?)',array($user_id));
		$result = collect($queryResult);		
		return $result;	
	}
	/* Getting AEM data for KReviewer assigned list  */  

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
	
	
	/* getting translated string AEM request */
	public function getting_translated_string_aem_request($aem_reference_code,$aem_object_id,$aem_source_language,$aem_target_language){
		
		$queryResult = DB::select('call xp_loc_translated_strings_by_reference_code_from_aem_request(?,?,?,?)',array($aem_reference_code,$aem_object_id,$aem_source_language,$aem_target_language));
		$result = collect($queryResult);		
		return $result; 		
	}
	/* getting translated string AEM request */
	
	
	/* AEM request insert or update kreviewer text row by row */	
	public function aem_request_update_kpt_reviewer_text_row_by_row($arr_import_translation){		
		
		$queryResult = DB::select('call xp_loc_aem_request_update_kpt_reviewer_text_row_by_row(?,?,?,?,?,?)',array($arr_import_translation[0],$arr_import_translation[1],$arr_import_translation[2],$arr_import_translation[3],$arr_import_translation[4],$arr_import_translation[5]));
		$result = collect($queryResult);	
		return $result;		
	}
  /* AEM request insert or update translated text row by row */	
  
  
  
  /* AEM request count of Proofread translation object id */
  public function aem_request_count_proofread_translation_objectid_select($aem_reference_code,$aem_object_id){	  
		$queryResult = DB::select('call xp_loc_aem_request_count_proofread_translation_objectid_select(?,?)',array($aem_reference_code,$aem_object_id));
		$result = collect($queryResult);		
		return $result; 	
  }
   /* AEM request count of Proofread translation object id */
   
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
	
	
	public function update_aem_status_request($aem_reference_code,$tm_status){		
		$queryResult = DB::select('call xp_loc_update_aem_status_request(?,?,@output)',array($aem_reference_code,$tm_status));
		$result = collect($queryResult);	
		return $result;	
	}
  
   
   
}
