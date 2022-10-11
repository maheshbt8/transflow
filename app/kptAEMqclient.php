<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class kptAEMqclient extends Model
{
	/* Getting AEM data for Client PM assigned list  */	
	public function kptaemqclientlisting($user_id){				
		$queryResult = DB::select('call xp_loc_aem_select_listing(?)',array($user_id));
		$result = collect($queryResult);		
		return $result;	
	}
	/* Getting AEM data for Client PM assigned list  */ 


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
   
   
    /* Getting Translator by reference code */
   public function getting_translators_by_referencecode($aem_reference_code){
	   $queryResult = DB::select('call xp_loc_getting_translator_names_by_reference_id(?)',array($aem_reference_code));
		$result = collect($queryResult);		
		return $result;	   
   }
   /* Getting Translator by reference code */
   
   
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
	
	
	/* Getting Translated strings start here */
	public function getting_translated_string_aem_request($aem_reference_code,$aem_object_id,$source_language,$target_language){		
		$queryResult = DB::select('call xp_loc_translated_strings_by_reference_code_from_aem_request(?,?,?,?)',array($aem_reference_code,$aem_object_id,$source_language,$target_language));
		$result = collect($queryResult);	
		return $result;		
	}
	/* Getting Translated strings end here */
   
   
}
