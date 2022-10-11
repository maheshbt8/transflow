<?php

namespace App\Http\Controllers\AEM;

use App\Http\Controllers\Controller;
use App\kptAEM;
use Illuminate\Http\Request;
use App\kptAEMtranslator;

use App\Http\Controllers\AEM\DB;

class KptAEMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {       
		if ( checkpermission('aem_kpt_pm_manage')) {
		$kptaem = new kptAEM();
		$kptaemresults = $kptaem->kptaempmlisting();	
		
		return view('AEM.kptpm.index',compact('kptaemresults'));
    } 
}
	
	
	public function pm_assigned_request_details(){
		
			$aem_request_id = $_POST["aem_p_id"];
		
			$kptaem = new kptAEM();
			$arr_aemrequestdetails = $kptaem->getting_fulldetails_aemrequest_primaryid($aem_request_id);	
			
			$aem_req_id			    	=	 $arr_aemrequestdetails[0]->aem_req_id;
			$aem_reference_code 		=	 $arr_aemrequestdetails[0]->aem_reference_code;
			$aem_project_name			=    $arr_aemrequestdetails[0]->aem_project_name;
			$aem_source_filename		=	 $arr_aemrequestdetails[0]->aem_source_filename;
			$aem_original_filename		=	 $arr_aemrequestdetails[0]->aem_original_filename;
			$aem_project_status			=	 $arr_aemrequestdetails[0]->aem_project_status;			
			$no_words					= 	 $arr_aemrequestdetails[0]->no_words;		
			$no_duplicate_words 		=	 $arr_aemrequestdetails[0]->no_duplicate_words;
			$delivery_date		 		=	 $arr_aemrequestdetails[0]->delivery_date;
			$user_id		 			=	 $arr_aemrequestdetails[0]->user_id;
			$created_on		 			=	 $arr_aemrequestdetails[0]->created_on;
			
			
			$arr_languages_list = $kptaem->getting_language_list_reference_code($aem_reference_code);
			
			$aem_source_language  = $arr_languages_list[0]->aem_source_language;
			$aem_target_language  = $arr_languages_list[0]->aem_target_language;
			
		$output="";
		$output .='<div class="row">
				<input type="hidden" name="hid_aem_req_id" id="hid_aem_req_id" value="'.$aem_req_id.'">
				<input type="hidden" name="hid_aem_reference_code" id="hid_aem_reference_code" value="'.$aem_reference_code.'">
				<div class="col-sm">Reference Code :</div> ';				
		$output .='<div class="col-sm">'.$aem_reference_code.'</div>';	
		$output .='</div>';
		
		
		
		$output .='<div class="row">
				  <div class="col-sm">Source Language :</div> ';				
		$output .='<div class="col-sm">'.$aem_source_language.'</div>';	
		$output .='</div>';
		
		
		$output .='<div class="row">
				  <div class="col-sm">Target Language :</div> ';				
		$output .='<div class="col-sm">'.$aem_target_language.'</div>';	
		$output .='</div>';	
		
		
		if($aem_project_status == "Accepted")
			$txt_project_status = $aem_project_status. " [ Approved for Translation ]";
		else
			$txt_project_status = $aem_project_status;
		
		
		$output .='<div class="xp_row" id="sucess_translator" style="color:green;font-weight:bold;display:none;">&nbsp;</div>';
		
		$output.='<div class="row">
				<div class="col-sm">Status :</div> ';				
		$output .='<div class="col-sm">'.$txt_project_status.'</div>';	
		$output .='</div>';		
		
		if($aem_project_status == "Accepted"){
			
			$arr_translators = $kptaem->getting_translator_lists();			
			
			
			$output .='<div class="row">
					<div class="col-sm">Translator</div> ';				
			$output .='<div class="col-sm">';			
			$output .='<select name="traslator_name" id="id_translator">                                
						<option value="">Select Translator</option>';	
						
			  if(!empty($arr_translators)){
				  foreach($arr_translators as $arr_translators_rows){
					  
				$output .='<option  value="'.$arr_translators_rows->user_id.'">'.$arr_translators_rows->name.' [ '.$arr_translators_rows->name.' ]'.'</option>';   							 
					}
			  }							 								
			$output .='</select>';		
			$output .='</div>';	
			$output .='</div>';

		    $output .='<div class="row">&nbsp;</div>';
			
			$output.='<div class="row">
				<div class="col-sm">&nbsp;</div> ';				
				$output .='<div class="col-sm"><input type="button" id="id_kptadmin_assign_ktranslator" name="assign_submit" value="Submit" class="btn btn-success"></div>';			
			$output .='</div>';
		
		}elseif($aem_project_status =="Assigned"){
			
			$arr_translators 	= $kptaem->getting_translators_by_referencecode($aem_reference_code);
			
			 if(!empty($arr_translators)){
					$p=1;
					foreach($arr_translators as $t_rows){
						$first_name    = $t_rows->name;						
						$language_spl  = "testing";
						
						$translator = $first_name." [".$language_spl."]";
						
				$output.='<div class="row">
						<div class="col-sm">Translator '.$p.' :</div> ';				
				$output .='<div class="col-sm">'.$translator.'</div>';	
				$output .='</div>';
				 $p++;
						
					}
			 }		
			
		}elseif($aem_project_status == "KPT Translation Completed"){
			
			$arr_kreviewers = $kptaem->getting_kreviewers_lists();						
			
			$output .='<div class="row" id="sucess_reviewer" style="color:green;font-weight:bold;display:none;">&nbsp;</div>';
			
			$output .='<div class="row">
					<div class="col-sm">Reviewer(KPT)</div> ';				
			$output .='<div class="col-sm">';			
			$output .='<select name="kreviewer_name"  id="id_kreviewer">                                
					<option value="">Select Reviewer</option>';						
			  if(!empty($arr_kreviewers)){
				  foreach($arr_kreviewers as $arr_kreviewers_rows){
					
					
				$output .='<option  value="'.$arr_kreviewers_rows->user_id.'">'.$arr_kreviewers_rows->name.'</option>';                              
								 
					}
			  }							 								
			$output .='</select>';		
			$output .='</div>';	
			$output .='</div>';			
			
			$output .='<div class="row">&nbsp;</div>';
			$output.='<div class="row">
				<div class="col-sm">&nbsp;</div> ';				
			$output .='<div class="col-sm"><input type="button" class="btn btn-success" id="id_kptadmin_assign" name="assign_submit" value="Submit"></div>';
			
			$output .='</div>';		
			
		}elseif($aem_project_status == "Review"){	
			
			$arr_translators 	= $kptaem->getting_translators_by_referencecode($aem_reference_code);
			
			 if(!empty($arr_translators)){
					$p=1;
					foreach($arr_translators as $t_rows){
						$first_name    = $t_rows->name;						
						$language_spl  = $t_rows->name;
						
						$translator = $first_name." [".$language_spl."]";
						
				$output.='<div class="row">
						<div class="col-sm">Translator '.$p.' :</div> ';				
				$output .='<div class="col-sm">'.$translator.'</div>';	
				$output .='</div>';
				 $p++;
						
					}
			 }		
					
			
			$arr_kreviewers 	= $kptaem->getting_kreviewers_by_referencecode($aem_reference_code);
			
			 if(!empty($arr_kreviewers)){
					$p=1;
					foreach($arr_kreviewers as $t_rows){
						$first_name    = $t_rows->first_name;
						$last_name     = $t_rows->last_name;
						$language_spl  = $t_rows->language_spl;
						
						$kreviewer = $first_name." ".$last_name;
						
				$output.='<div class="row">
						<div class="col-sm">Reviewer '.$p.' :</div> ';				
				$output .='<div class="col-sm">'.$kreviewer.'</div>';	
				$output .='</div>';
				 $p++;
						
					}
			 }		
			
			
		}elseif($aem_project_status == "Qatar Review Success"){		
			
			$arr_translators 	= $kptaem->getting_translators_by_referencecode($aem_reference_code);
			
			 if(!empty($arr_translators)){
					$p=1;
					foreach($arr_translators as $t_rows){
						$name    = $t_rows->name;
						//$last_name     = $t_rows->last_name;
						$language_spl  = $t_rows->name;
						
						$translator = $name." [".$language_spl."]";
						
					$output.='<div class="row">
						<div class="col-sm">Translator '.$p.' :</div> ';				
					$output .='<div class="col-sm">'.$translator.'</div>';	
					$output .='</div>';
				 $p++;
						
					}
			 }		
					
			
			$arr_kreviewers 	= $kptaem->getting_kreviewers_by_referencecode($aem_reference_code);
			
			 if(!empty($arr_kreviewers)){
					$p=1;
					foreach($arr_kreviewers as $t_rows){
						$first_name    = $t_rows->first_name;
						$last_name     = $t_rows->last_name;
						$language_spl  = $t_rows->language_spl;
						
						$kreviewer = $first_name." ".$last_name;
						
				$output.='<div class="row">
						<div class="col-sm">KPT Reviewer '.$p.' :</div> ';				
				$output .='<div class="col-sm">'.$kreviewer.'</div>';	
				$output .='</div>';
				 $p++;
						
					}
			 }
				
			$arr_qreviewers 	= $kptaem->getting_qreviewers_by_referencecode($aem_reference_code);
			
			 if(!empty($arr_qreviewers)){
					$p=1;
					foreach($arr_qreviewers as $t_rows){
						$first_name    = $t_rows->first_name;
						$last_name     = $t_rows->last_name;
						$language_spl  = $t_rows->language_spl;
						
						$kreviewer = $first_name." ".$last_name;
						
				$output.='<div class="row">
						<div class="col-sm">QATAR Reviewer '.$p.' :</div> ';				
				$output .='<div class="col-sm">'.$kreviewer.'</div>';	
				$output .='</div>';
				 $p++;
						
					}
			 }		
			
		}	
		
		$output .='</div>';				
		echo $output;
		exit;		
	}

	/* Cost Centric details */
	public function costcentric_details(){
		$aem_request_id = $_POST["aem_p_id"];
		
		$kptaem = new kptAEM();
		$arr_aemrequestdetails = $kptaem->getting_fulldetails_aemrequest_primaryid($aem_request_id);
		
	
			
			$aem_req_id			    	=	 $arr_aemrequestdetails[0]->aem_req_id;
			$aem_reference_code 		=	 $arr_aemrequestdetails[0]->aem_reference_code;
			$aem_project_name			=    $arr_aemrequestdetails[0]->aem_project_name;
			$aem_source_filename		=	 $arr_aemrequestdetails[0]->aem_source_filename;
			$aem_original_filename		=	 $arr_aemrequestdetails[0]->aem_original_filename;
			$aem_project_status			=	 $arr_aemrequestdetails[0]->aem_project_status;			
			$no_words					= 	 $arr_aemrequestdetails[0]->no_words;		
			$no_duplicate_words 		=	 $arr_aemrequestdetails[0]->no_duplicate_words;
			$delivery_date		 		=	 $arr_aemrequestdetails[0]->delivery_date;
			$user_id		 			=	 $arr_aemrequestdetails[0]->user_id;
			$created_on		 			=	 $arr_aemrequestdetails[0]->created_on;
			
			$arr_metadata_child_results = $kptaem->aem_response_metadata($aem_reference_code);
			
			$basepath = public_path();
			$arr_data = array();			
			$arr_aem_xml_text_data = array();
			if(!empty($arr_metadata_child_results)){
					foreach($arr_metadata_child_results as $metachild_rows){
						
						    $aem_mime_type = $metachild_rows->aem_mime_type;				
							$object_job_id = $metachild_rows->aem_object_id;
							
							if($aem_mime_type == 'text/html'){
													
							  $xml_file =$basepath."/uploadfiles/aem/restapi/".$aem_reference_code."/".$object_job_id.".xml";
							
							if (file_exists($xml_file)){								
							$node_cmd = "node ".$basepath."/converters/from-xml2json.js $xml_file  ";
								$returnjson = shell_exec($node_cmd);								
								$result_json = json_decode($returnjson);
								
								$arr_aem_xml_text_data[]    = $result_json->translationObjectFile->translationObjectProperties->property;	
																
								
								if(count($arr_aem_xml_text_data)>0){
									foreach($arr_aem_xml_text_data as $rows){
											if(empty($rows->_text)){
												foreach($rows as $rows1){
												  $arr_data[] = addslashes($rows1->_text);
												}
											}else {
												$arr_data[] = addslashes($rows->_text);
											}										
																			
									}
								}									
							}
						 }					
							
					}//end foreach
			}
			
			
			if(count($arr_aem_xml_text_data) ==0) {
				$xliff_file = $basepath.'/uploadfiles/aem/'.$aem_source_filename;
				$node_cmd = "node ".$basepath."/converters/from-xliff.js $xliff_file ";
				$returnjson  = exec($node_cmd);								
				$result_json = json_decode($returnjson);
				$arr_data    = $result_json->resources->f1;
				
				$arr_sourcelanage   = explode("-",$result_json->sourceLanguage);
				$arr_targetlanguage = explode("-",$result_json->targetLanguage);
			
			}else{
				
				$arr_languages_list = $kptaem->getting_language_list_reference_code($aem_reference_code);
				$aem_source_language  = $arr_languages_list[0]->aem_source_language;
				$arr_sourcelanage[0] = $aem_source_language;
				$aem_target_language  = $arr_languages_list[0]->aem_target_language;
				$arr_targetlanguage[0] = $aem_target_language;
			}

			
			$arr_source_language 	= $kptaem->getting_language_name($arr_sourcelanage[0]);	
			$source_language_name 	= $arr_source_language[0]->lang_name;
			$arr_target_language 	= $kptaem->getting_language_name($arr_targetlanguage[0]);
			$target_language_name 	= $arr_target_language[0]->lang_name;
			
			
			$arr_target_language_details = $kptaem->ratecard_master_target_language_where_condition($target_language_name);
			$translation_word_price = $arr_target_language_details[0]->translation_word_price;
						
			
			
			$output='<div class="row">
			<div class="col-sm">Reference Code :</div> ';				
			$output .='<div class="col-sm">'.$aem_reference_code.'</div>';	
			$output .='</div>';
			
			$output.='<div class="row"><div class="col-sm">Source Language :</div> ';				
			$output .='<div class="col-sm">'.$source_language_name.' [ '.$aem_source_language.' ]</div>';	
			$output .='</div>';
			
			$output.='<div class="row"><div class="col-sm">Target Language :</div> ';				
			$output .='<div class="col-sm">'.$target_language_name.' [ '.$aem_target_language.' ]</div>';	
			$output .='</div>';		
					
						
			
			$arr_duplicate_words =array();
			$counts = array();
			$array_unique_words = array();
			
						
			if(!empty($arr_data)){			
				foreach($arr_data as $souce_text){
					$source_words = $souce_text;				
					$words = explode(' ', $source_words);
					foreach ($words as $word) {						
						//$word = preg_replace("#[^a-zA-Z\-]#", "", $word);
						//$counts[$word] += 1;
						if(isset($counts[$word])&& $counts[$word]>1){
							$arr_duplicate_words[$word] +=1;
						}else{
							$array_unique_words[$word] =1;
						}
					}
				}			
		   }		


		$total_unique_words_price = array_sum($array_unique_words)*$translation_word_price;
		$total_unique_words_price = number_format($total_unique_words_price, 2, '.', '');
		
		$duplicate_words_price  = array_sum($arr_duplicate_words)* ($translation_word_price*0.3);
		$duplicate_words_price =  number_format($duplicate_words_price, 2, '.', '');
		
		$total_words_cost = $total_unique_words_price+$duplicate_words_price;
		$total_words_cost = number_format($total_words_cost, 2, '.', '');
			 	
		
		
		$output.='<div class="row">
				<div class="col-sm">Per Word Cost  :</div> ';				
		$output .='<div class="col-sm">$'.$translation_word_price.'</div>';	
		$output .='</div>';
		
		
		$output.='<div class="row">
				<div class="col-sm">Total Unique Words :</div> ';				
		$output .='<div class="col-sm">'.array_sum($array_unique_words).'</div>';	
		$output .='</div>';

		

		$output.='<div class="row">
				<div class="col-sm">Unique Words Cost :</div> ';				
		$output .='<div class="col-sm">$'.$total_unique_words_price.'</div>';	
		$output .='</div>';
		
		$output.='<div class="row">
				<div class="col-sm">Duplicate Words :</div> ';				
		$output .='<div class="col-sm">'.array_sum($arr_duplicate_words).'</div>';	
		$output .='</div>';
		
		
		$output.='<div class="row">
				<div class="col-sm">Total cost :</div> ';				
		$output .='<div class="col-sm">$'.$total_words_cost.'</div>';	
		$output .='</div>';	
		
		
		echo $output;	 
		exit;		
	}	
	/* Cost Centric details */
	
	/* AEM Xmlfiles request */
	public function aem_xmlfiles_request(){		
				
		$aem_main_request_id = $_POST["aem_main_request_id"];
		
		$kptaem = new kptAEM();		
		$arr_request_xml_files = $kptaem->aem_xml_files_select($aem_main_request_id);
		
		$k=1;
	$count_xmlfiles = count($arr_request_xml_files);
	if(!empty($arr_request_xml_files)){
			$output ='<div class="container" >';		
			foreach($arr_request_xml_files as $row_xml){
				$xml_file_id 		= $row_xml->xml_file_id;
				$aem_reference_code = $row_xml->aem_reference_code;
				$xml_file 			= $row_xml->xml_file;
				$aem_object_id		= $row_xml->aem_object_id;
				$mime_type			= $row_xml->mime_type;
				$aem_xml_status		= $row_xml->aem_xml_status;
				$created_on			= $row_xml->created_on;
				
				$translate_url = url("admin/kptaemrequest/aem_request_translated_output/".$aem_reference_code."/".$aem_object_id);
				
				$download_url = url("admin/kptaemrequest/aem_request_translated_download/".$aem_reference_code."/".$aem_object_id);			 
											
				
				$output .='<div class="row">
				<div class="col-sm"  style="color:#FFFFFF;">AEM Object:</div> <div class="col-sm" style="color:#FFFFFF;">'.$aem_object_id.'</div>';
				
				$output .='<div class="col-sm" style="text-align:right;color:#FFFFFF;">Status :</div> <div class="col-sm" style="color:#FFFFFF;">'.ucfirst($aem_xml_status).'</div>
				
				<div class="col-sm" style="text-align:center;" style="color:#FFFFFF;"><a href="'.$download_url.'" style="color:#FFFFFF;" >Download</a></div>
				<div class="col-sm" style="text-align:center;" style="color:#FFFFFF;"><a href="'.$translate_url.'" style="color:#FFFFFF;">Updates on Translation</a></div>
				</div>';			
				
				
				if($k != $count_xmlfiles)
					$output .='<hr style="border:1px solid #FFFFFF;">';
				
				$k++;			
			}

			$output .='</div>';
			
		}else{			
			$output .='<div class="xp_row" style="text-align:center;"> No Record(s) Found...</div>';			
		}	
				
		echo $output;
		exit;	
		
	}
	/* AEM Xmlfiles request */
	
	
	/* Download request file */	
	public function downloadrequestfile($id){	
		$kptaem = new kptAEM();
		
		$basepath = base_path();				
		$aem_reference_code = $id;
		$arr_references = $kptaem->getting_fulldetails_aemrequest($aem_reference_code);
		
		$basepath = public_path();
				
		$xml_file="";		
		$arr_request_xml_files = $kptaem->aem_xml_files_select($aem_reference_code);
		if(!empty($arr_request_xml_files)){
			$aem_object_id = $arr_request_xml_files[0]->aem_object_id;
			$xml_file =$basepath."/uploadfiles/aem/restapi/".$aem_reference_code."/".$aem_object_id.".xml";
		}

		$source_file_path = $arr_references[0]->aem_source_filename;
		$file_path 		= $basepath."/uploadfiles/aem/".$source_file_path;
						
		
		if (file_exists($xml_file)){
			$file_path = $xml_file;
			$full_file_download = $file_path;
			$source_file_path = $aem_object_id.".xml";
		}else
			$file_path = $file_path;

		
			if (file_exists($file_path)) { 
							 
							$download_name 			= $source_file_path;
							$full_file_download		= $file_path;							
							header('Content-Description: File Transfer');
							header('Content-Type: application/octet-stream');
							header('Content-Disposition: attachment; filename='.$download_name);
							header('Content-Transfer-Encoding: binary');
							header('Expires: 0');
							header('Cache-Control: must-revalidate');
							header('Pragma: public');
							header('Content-Length: ' . filesize($full_file_download));
							ob_clean();
							flush();
							readfile($full_file_download);
							exit; 						 
									 
			}else{			
				echo "no download file found";										 
			}		
	}	
	/* Download request file */
	
	
	
	/* AEM request translated download */
	public function aem_request_translated_download($reference_id ='',$object_id='') {
			$reference_id   = $reference_id;
			$object_id 		= $object_id;
			
			if($reference_id !="" && $object_id !="" ){
				
			$basepath = public_path();
			
			$xml_file =$basepath."/uploadfiles/aem/restapi/".$reference_id."/". $object_id."_target.xml";			 				 			 
				

				if (file_exists($xml_file)){
					$file_path = $xml_file;
					$full_file_download = $file_path;
					$source_file_path = $object_id."_target.xml";
				
				 
				 if (file_exists($file_path)) {	 
							 
							$download_name 			= $source_file_path;
							$full_file_download		= $file_path;							
							header('Content-Description: File Transfer');
							header('Content-Type: application/octet-stream');
							header('Content-Disposition: attachment; filename='.$download_name);
							header('Content-Transfer-Encoding: binary');
							header('Expires: 0');
							header('Cache-Control: must-revalidate');
							header('Pragma: public');
							header('Content-Length: ' . filesize($full_file_download));
							ob_clean();
							flush();
							readfile($full_file_download);
							exit; 						 
									 
				  }else{
						echo "no file found"; 					 
				   } 
					
				
			}else{
				echo "no file found";				
			}		
		}else{			
			echo "no found reference code & object code";				
		}
		
	}
	/* AEM request translated download */
	
	
	
	/* AEM request Translate */
	public function aem_request_translated_output($reference_id ='',$object_id=''){
		
		$kptaem = new kptAEM();
		
		$aem_reference_code = $reference_id;				 
		$arr_aemrequest_details = $kptaem->getting_fulldetails_aemrequest_language($aem_reference_code);	
		
		
		$arr_object_id = array("objectid"=>$object_id);		
		return view('AEM.kptpm.translatedform',compact('arr_aemrequest_details','arr_object_id','kptaem'));		
		
	}
	/* AEM request Translate */
	
	
	/* Google Translate */
	public function Gtranslate(){
		$kptaem = new kptAEM();
		
					
		$source_text  = $_POST["source_text"];
		$target_lang  = $_POST["target_lange_code"];
			
				
		echo $g_translate_target_text = $kptaem->gtranslate_text_from_soucefile($source_text,$target_lang);
		exit;		
	}
	/* Google Translate */
	
	
	/* Auto Suggestion */
	public function autosuggestion(){
				
		$typedword = $_POST["typedword"];
				
		$source_lang  = $_POST['source_lang'];
		$target_lang  = $_POST['target_lang'];
		
		/*$source_lang = "en";
		$target_lang = "hi";		*/
		$source_text  = $_POST["typedword"];
		$btn_id  = $_POST["btn_id"];
		if($source_lang != '' && $target_lang != ''){
		$kptaem = new kptAEM();
		$tm_text = $kptaem->get_translationmemory_by_searchsuggestion($source_lang,$target_lang,$source_text);
 
		$arr_response = array();
		$k=0;
		$arr_response='';
		$all_response=array();
		if(!empty($tm_text) && count($tm_text)>0){
			foreach($tm_text as $rows){
				$targettext = $rows->target_text;
				//echo $targettext;die;
				//$targettext = 'Hello wehre to you testing';
				//$txt=explode(' ',$targettext);
			/*$string="How youa are you yobin ?";
$searchletters="yo";*/
//echo $source_text;die;
//echo preg_quote($source_text);die;
$re = '/\b' . preg_quote($source_text, '/') . '\w*\b/u';
//$re = preg_quote($source_text, '/');
preg_match_all($re, $targettext, $txt);
//print_r($txt);die;
$all_response=array_unique(array_merge($all_response,$txt[0]));
			/*$m_text='';
			for ($mt=0; $mt < count($txt[0]); $mt++) { 
				$m_text .='<li>'.$txt[0][$mt].'</li>';
			}
			$arr_response .=$m_text;*/
				//$arr_response[] = array("label"=>$m_text);
				/*$arr_response[] = array("label"=>$targettext);*/
				$k++;
			}			
			//$target_text = $tm_text[0]->target_text;		
			//$arr_response = array("label"=>$target_text);		

			$m_text='';
			$kptaemtranslator = new kptAEMtranslator();
			if(($source_lang  == "en" && $target_lang  == "hi") || ($source_lang  == "hi" && $target_lang  == "en")){
				$arr_tm = $kptaemtranslator->nntranslation($source_text);
				$t_text=$arr_tm;
				if($t_text != ''){
					$target_text = $t_text;
					$m_text .='<li class="suggestions_data" btn_id="'.$btn_id.'">'.$target_text.'</li>';
				}
			}
			for ($mt=0; $mt < count($all_response); $mt++) { 
				$m_text .='<li class="suggestions_data" btn_id="'.$btn_id.'">'.$all_response[$mt].'</li>';
			}
			$arr_response .=$m_text;
		}
		//print_r($all_response);die;
		echo $arr_response;
		//echo json_encode($arr_response);
		exit;
		}
		echo "";
	}
	/* Auto Suggestion */
	
	
	/* Translation Memory */
	public function translation_memroy_getbysource(){
		
			$source_lang  = $_POST['source_lang'];
			$target_lang = $_POST['tareget_lang'];
			$source_text  = $_POST['source_text'];			
		
			$kptaem = new kptAEM();
			$tm_text = $kptaem->get_translationmemory_by_source($source_lang,$target_lang,$source_text);
			
			$target_text = $tm_text[0]->target_text;
			echo $target_text;
			exit;		
	}
	/* Translation Memory */
	
	/* Assigned Translator to AEM Request*/
	public function kptadmin_assigned_trasnslator_aem_request(){
		
			$aem_p_id 			 =  $_POST["aem_p_id"];
			$id_translator  	 =  $_POST["id_translator"];
			$aem_reference_code  =  $_POST["aem_reference_code"];
			$kptaem = new kptAEM();
			
			if($aem_reference_code!="" && $aem_p_id !="" && $id_translator !="" ){
				
				$tm_status="assigned";							
				$result = $kptaem->update_aem_status_request($aem_reference_code,$tm_status);			
								
				$result1 = $kptaem->aem_request_assigned_translator_insert($aem_reference_code,$id_translator);
				
				
				$result1 = "SUCCESS";
				if($result1 == "SUCCESS"){						
						
					$output_res = $kptaem->aem_request_xml_file_job_objectid_update_status($tm_status,$aem_reference_code);
						
					print "Successfully Assigned Translator";	
					exit;	
				}			
		}else {			
				echo "No access this feature";
				exit;
		}
		
	}
	/* Assigned Translator to AEM Request*/
	
	/* Assigned Kreviewer to AEM Request*/
	public function kptadmin_assigned_kreviewer_aem_request(){
		
		$aem_p_id 			 =  $_POST["aem_p_id"];
		$id_kreviewer  	 =  $_POST["id_kreviewer"];
		$aem_reference_code  =  $_POST["aem_reference_code"];
		$kptaem = new kptAEM();
			
		if($aem_reference_code!="" && $aem_p_id !="" && $id_kreviewer !="" ){			
				$tm_status="Review";
				$arr_request_values = array($aem_reference_code,$tm_status);
				$result = $kptaem->update_aem_status_request($aem_reference_code,$tm_status);				
							
								
				$result1 = $kptaem->aem_request_assigned_translator_insert($aem_reference_code,$id_kreviewer);
				$result1 = "SUCCESS";
				if($result1 == "SUCCESS"){					
						
						$output_res = $kptaem->aem_request_xml_file_job_objectid_update_status($tm_status,$aem_reference_code);
						
																
						print "Successfully Assigned Reviewer";	
						exit;	
				}			
			}else{
				print 'Access proper URL Parameters';
				exit;				
			}		
		
	}
	/* Assigned Kreviewer to AEM Request*/
	
	
	/* AEM REST API services for pulling request start here */	
	public function aemcreatetranslationjob(){
		
				//$PHP_AUTH_USER = $_SERVER["PHP_AUTH_USER"];
				//$PHP_AUTH_PW   = $_SERVER["PHP_AUTH_PW"];
				
				$PHP_AUTH_USER = "transflow";
				$PHP_AUTH_PW   = "transflowtms";		
				
				
			if($_SERVER["REQUEST_METHOD"] =="POST"){				
				if($PHP_AUTH_USER =="transflow"  && $PHP_AUTH_PW =="transflowtms"){

					$kptaem = new kptAEM();
					$cotent_type = $_SERVER["CONTENT_TYPE"];			
					
					$headers ="";
					foreach($_SERVER as $i=>$val) {
						$headers .=$i."---".$val."\n";
					} 


					$content_body = @file_get_contents('php://input');					
					$file_name = 'assets/uploadfiles/aem/restapi/aemcreatetranslationjob.txt';
					$fp = fopen($file_name, 'a');
					fwrite($fp, $headers);
					fwrite($fp, "Bodycontent:".$content_body);
					fclose($fp);


					$json_contentbody = json_decode($content_body);								
					$project_name 		= $json_contentbody->name;
					$project_desc 	    = $json_contentbody->description;
					$source_language    = $json_contentbody->sourceLang;
					$target_language    = $json_contentbody->targetLang;
					$job_id				= $json_contentbody->jobId;
					$due_date		    = date("Y-m-d H:i:s",strtotime($json_contentbody->duedate));
					$job_meta		    = $json_contentbody->job_meta;
					$translation_type	= $json_contentbody->translationMethod;
					$created_on		    = date("Y-m-d H:i:s");		
					//$job_id 			= date('dmyHis');
					
					
					$arr_jobid = explode("/",$job_id);
					if(count($arr_jobid)>0){
					$str_job_id = $arr_jobid[count($arr_jobid)-1];
						if($str_job_id !="")
							$arr_aem_jobid = explode("_",$str_job_id);
							if(count($arr_aem_jobid)>0)
								$aem_reference_code = $arr_aem_jobid[count($arr_aem_jobid)-1];
					}
					
					
				$aem_original_reference_code = $job_id;			
				$arr_aemrequest_translation_job = array($aem_reference_code,$aem_original_reference_code,$project_name,$project_desc,$source_language,$target_language,$due_date,$job_meta,$created_on,$translation_type);
				
				/* AEM REST API for translation job insert */
				$output_res = $kptaem->aem_request_translation_job_pull_api_insert($arr_aemrequest_translation_job);
				exit;		
									
				
				}else{
					header('HTTP/1.1 401 Unauthorized', true, 401);
					echo "401 Authorization Failed";
					exit;			
			   }

			}else{				
				die("Method not allowed");
			}	
	}
	/* AEM REST API services for pulling request end here */

	/* AEM REST API two services start here */	
	public function aemtranslationdata(){
		if($_SERVER["REQUEST_METHOD"] =="POST"){
			
			$PHP_AUTH_USER = $_SERVER["PHP_AUTH_USER"];
			$PHP_AUTH_PW   = $_SERVER["PHP_AUTH_PW"];
			
			if($PHP_AUTH_USER =="transflow"  && $PHP_AUTH_PW =="transflowtms"){
				
				$kptaem = new kptAEM();
				$headers ="";
				foreach($_SERVER as $i=>$val) {
					$headers .=$i."---".$val."\n";
				}			
				
				$job_id = $_SERVER["HTTP_JOBID"];
				$original_aem_object_id = $_SERVER['HTTP_OBJECTID'];
				$aem_mime_type = $_SERVER["HTTP_MIMETYPE"];	
				
				$content_body = @file_get_contents('php://input');				
				$file_name = 'assets/uploadfiles/aem/restapi/aemtranslationdata.txt';
				$fp = fopen($file_name, 'a');
				fwrite($fp, $headers);
				fwrite($fp, "Bodycontent:".$content_body);
				fclose($fp);
				
				$content_body 		= addslashes($content_body);
				$created_on		    = date("Y-m-d H:i:s");
				$aem_mime_type		= $aem_mime_type;			
				
				
				$arr_jobid = explode("/",$job_id);
				if(count($arr_jobid)>0){
				$str_job_id = $arr_jobid[count($arr_jobid)-1];
					if($str_job_id !="")
						$arr_aem_jobid = explode("_",$str_job_id);
						if(count($arr_aem_jobid)>0)
							$aem_reference_code = $arr_aem_jobid[count($arr_aem_jobid)-1];
				}
				
				
				
				$job_object_id = rand(1111111111,9999999999);
				$arr_images_types 	= array('image/jpeg','image/jpg','image/png','image/gif');
				
				if(in_array($aem_mime_type,$arr_images_types)){
					
					/* creating XML file from AEM request */
					$aem_folder_dir = "/var/www/html/assets/uploadfiles/aem/restapi/".$aem_reference_code;
					if (!is_dir($aem_folder_dir)) {
							mkdir($aem_folder_dir, 0777, true);					
					}							
							
					if (is_dir($aem_folder_dir)) {
						
						$arr_image_type = explode("/",$aem_mime_type);
						$image_file_name = $job_object_id.".".strtolower($arr_image_type[1]);
						
						$aem_xml_file = $aem_folder_dir."/".$image_file_name;
						$img_body_content = @file_get_contents('php://input');
						$xmlfileobject = fopen($aem_xml_file, 'w');
						fwrite($xmlfileobject, trim($img_body_content));
						fclose($xmlfileobject);	
						$content_body = $image_file_name;
					}				
					
				}				
				
				$aem_original_reference_code = $job_id;
				$arr_aemrequest_translation_job_data = array($aem_reference_code,$aem_original_reference_code,$job_object_id,$original_aem_object_id,$content_body,$aem_mime_type,$created_on);
				
				
				/* AEM REST API for translation job insert */
				$output_res = $kptaem->aem_request_translation_pull_api_job_data_insert($arr_aemrequest_translation_job_data);	
				
				echo $job_object_id;
				exit;	
				
			
			}else {					
				header('HTTP/1.1 401 Unauthorized', true, 401);
				echo "401 Authorization Failed";
				exit;
			}
			
			
		}else{
				
				die("Method not allowed");
			}	
	}
	/* AEM REST API two services end here */
	
	
	
	
	/* AEM REST API get Object Translaton status start here */
	public function aemtranslationgetobjectstatus(){
		if($_SERVER["REQUEST_METHOD"] =="POST"){
			
			$PHP_AUTH_USER = $_SERVER["PHP_AUTH_USER"];
			$PHP_AUTH_PW   = $_SERVER["PHP_AUTH_PW"];
			
		if($PHP_AUTH_USER =="transflow"  && $PHP_AUTH_PW =="transflowtms"){		
			
			$kptaem = new kptAEM();
			$content_body 		= @file_get_contents('php://input');				
			$json_contentbody 	= json_decode($content_body);								
			$objid 				= $json_contentbody->objectId;
			$jobId 				= $json_contentbody->jobId;
			

			$aem_mime_type = $_SERVER["HTTP_MIMETYPE"];				
			 $arr_aem_request = array(trim($objid));
		 
			 		 
			 $arr_target_xml = $kptaem->target_xml_aem_request_job_status_select($arr_aem_request);
			 $job_status = $arr_target_xml[0]->transflow_status;
			 
			 
					
			
			$arr_transflow_status = array('New','Accepted','Approved for Translation','Assigned','Translation in-Progress','KPT Translation Completed','Review','KPT Review Failed','KPT Review Completed','Assigned Qatar Review','Qatar Review Failed');	
							
			$arr_transflow_status1 = array('Translation Completed');
			$arr_transflow_status2 = array('READY_FOR_REVIEW123');
			$arr_transflow_status3 = array('Completed');
			
			
				$arr_jobid = explode("/",$jobId);
				if(count($arr_jobid)>0){
				$str_job_id = $arr_jobid[count($arr_jobid)-1];
					if($str_job_id !="")
						$arr_aem_jobid = explode("_",$str_job_id);
						if(count($arr_aem_jobid)>0)
							$aem_reference_code = $arr_aem_jobid[count($arr_aem_jobid)-1];
				}				
				
			$this->db->close();		
			$arr_aem_request_job = array($aem_reference_code);	
			
			$arr_target_job_status = $kptaem->target_xml_aem_request_job_status_select($arr_aem_request_job);		
			$aem_job_status = $arr_target_job_status[0]->aem_project_status;	
				
					
				
			$arr_images_types = array('image/jpeg','image/jpg','image/png','image/gif');
			$findme="metadata";
			$meta_status = strpos($objid, $findme);

				/*
				if($meta_status){					 
					 if (in_array($aem_job_status, $arr_transflow_status)) {
						 $output ="TRANSLATION_IN_PROGRESS";
					 }elseif(in_array($aem_job_status,$arr_transflow_status1)){
						 //$output ='TRANSLATED';
						 $output = "READY_FOR_REVIEW";
					 }elseif(in_array($aem_job_status,$arr_transflow_status2)){
						 $output ='APPROVED';					 
					 }elseif(in_array($aem_job_status,$arr_transflow_status3)){
						 $output ='APPROVED';					 
					 }else{
						 $output ='TRANSLATION_IN_PROGRESS';
					 }					 
					 
				}elseif(in_array($aem_mime_type,$arr_images_types)){					
					
					if (in_array($aem_job_status, $arr_transflow_status)) {
						 $output ="TRANSLATION_IN_PROGRESS";
					 }elseif(in_array($aem_job_status,$arr_transflow_status1)){
						 //$output ='TRANSLATED';					 
						  $output = "READY_FOR_REVIEW";
					 }elseif(in_array($aem_job_status,$arr_transflow_status2)){
						 $output ='APPROVED';					 
					 }elseif(in_array($aem_job_status,$arr_transflow_status3)){
						 $output ='APPROVED';					 
					 }else{
						 $output ='TRANSLATION_IN_PROGRESS';
					 }				
					
				}else{*/	
				
					 if (in_array($job_status, $arr_transflow_status)) {
						 $output ="TRANSLATION_IN_PROGRESS";
					 }elseif(in_array($job_status,$arr_transflow_status1)){
						 $output ='TRANSLATED';					 
					 }elseif(in_array($job_status,$arr_transflow_status2)){
						 $output ='READY_FOR_REVIEW';					 
					 }elseif(in_array($job_status,$arr_transflow_status3)){
						 $output ='APPROVED';					 
					 }else{
						 $output ='TRANSLATION_IN_PROGRESS';
					 }
				//}
				 
				 echo $output;
				 exit;
		
			}else{
				header('HTTP/1.1 401 Unauthorized', true, 401);
				echo "401 Authorization Failed";
				exit;				
			}				
			
		}else{
			die("Method not allowed");
		}		
	}
	/* AEM REST API get Object Translaton status start here */
	
	
	
	
	/* AEM REST API get Job Translaton status start here */
	public function aemtranslationgetjobstatus(){
		if($_SERVER["REQUEST_METHOD"] =="POST"){
			
			$PHP_AUTH_USER = $_SERVER["PHP_AUTH_USER"];
			$PHP_AUTH_PW   = $_SERVER["PHP_AUTH_PW"];
			
		if($PHP_AUTH_USER =="transflow"  && $PHP_AUTH_PW =="transflowtms"){		
			
				$kptaem = new kptAEM();
				
				$content_body = @file_get_contents('php://input');				
				$json_contentbody = json_decode($content_body);								
				$objid 				= $json_contentbody->objectId;
				$jobId		 	    = $json_contentbody->jobId;				
				
				$arr_jobid = explode("/",$jobId);
				if(count($arr_jobid)>0){
				$str_job_id = $arr_jobid[count($arr_jobid)-1];
					if($str_job_id !="")
						$arr_aem_jobid = explode("_",$str_job_id);
						if(count($arr_aem_jobid)>0)
							$aem_reference_code = $arr_aem_jobid[count($arr_aem_jobid)-1];
				}
				
				
				 $arr_aem_request = array($aem_reference_code,$objid);			 			 
				 $arr_target_xml = $kptaem->target_xml_aem_request_job_object_status_select($arr_aem_request);		 
				 
				 $job_status = $arr_target_xml[0]->transflow_status;
				
				$arr_transflow_status = array('New','Accepted','Approved for Translation','Assigned','Translation in-Progress','KPT Translation Completed','Review','KPT Review Failed','KPT Review Completed','Assigned Qatar Review','Qatar Review Failed');				
				$arr_transflow_status1 = array('Translation Completed');				
				$arr_transflow_status2 = array('READY_FOR_REVIEW123');				
				$arr_transflow_status3 = array('Completed');
				
				
				$this->db->close(); 
				$arr_aem_request_job = array($aem_reference_code);
					
							
				$arr_target_job_status = $kptaem->target_xml_aem_request_job_object_status_select($arr_aem_request_job);		
				$aem_job_status = $arr_target_job_status[0]->aem_project_status;
				
				
									
				
												
				/*
				$findme="metadata";
				$meta_status = strpos($objid, $findme);				
				if($meta_status){					
					if (in_array($aem_job_status, $arr_transflow_status)) {
						 $output ="TRANSLATION_IN_PROGRESS";
					 }elseif(in_array($aem_job_status,$arr_transflow_status1)){
						 //$output ='TRANSLATED';					 
						 $output = "READY_FOR_REVIEW";
					 }elseif(in_array($aem_job_status,$arr_transflow_status2)){
						 $output ='APPROVED';					 
					 }elseif(in_array($aem_job_status,$arr_transflow_status3)){
						 $output ='APPROVED';					 
					 }else{
						 $output ='TRANSLATION_IN_PROGRESS';
					 }				
					 	
				}else{
					*/
				
					 if (in_array($job_status, $arr_transflow_status)) {
						 $output ="TRANSLATION_IN_PROGRESS";
					 }elseif(in_array($job_status,$arr_transflow_status1)){
						 $output ='TRANSLATED';					 
					 }elseif(in_array($job_status,$arr_transflow_status2)){
						 $output ='READY_FOR_REVIEW';					 
					 }elseif(in_array($job_status,$arr_transflow_status3)){
						 $output ='APPROVED';					 
					 }else{
						 $output ='TRANSLATION_IN_PROGRESS';
					 }
				//}
				 
				 echo $output;
				 exit;

		}else{
				header('HTTP/1.1 401 Unauthorized', true, 401);
				echo "401 Authorization Failed";
				exit;				
		}				 
			
		}else{
			die("Method not allowed");
		}	
	}
	
	
	
	/* AEM REST API Update Object Translaton status start here */
	public function aemtranslationupdateobjectstatus(){
			if($_SERVER["REQUEST_METHOD"] =="POST"){

			$PHP_AUTH_USER = $_SERVER["PHP_AUTH_USER"];
			$PHP_AUTH_PW   = $_SERVER["PHP_AUTH_PW"];
			
		 if($PHP_AUTH_USER =="transflow"  && $PHP_AUTH_PW =="transflowtms"){		
			$kptaem = new kptAEM();
			$content_body = @file_get_contents('php://input');	
			$json_contentbody = json_decode($content_body);	
		
			
			$objid 				= $json_contentbody->objectId;
			$jobId		 	    = $json_contentbody->jobId;	
			$aem_status	 	    = $json_contentbody->status;	
			
				
				$arr_jobid = explode("/",$jobId);
				if(count($arr_jobid)>0){
				$str_job_id = $arr_jobid[count($arr_jobid)-1];
					if($str_job_id !="")
						$arr_aem_jobid = explode("_",$str_job_id);
						if(count($arr_aem_jobid)>0)
							$aem_reference_code = $arr_aem_jobid[count($arr_aem_jobid)-1];
				}			
				
				if($aem_status =="APPROVED"){
					
					$tm_status="Completed";
					$arr_request_values = array($aem_reference_code,$tm_status);			
					$result = $kptaem->update_aem_status_request($aem_reference_code,$tm_status);				
								
					$output_res = $kptaem->aem_request_xml_file_job_objectid_update_status('Completed',$aem_reference_code);
					
					
				}elseif($aem_status =="READY_FOR_REVIEW123"){
					
					$tm_status="READY_FOR_REVIEW";
					$arr_request_values = array($aem_reference_code,$tm_status);
					$this->db->close(); 
					$result = $this->connector_model->call_sp('xp_loc_update_aem_status_request',$arr_request_values,'output',TRUE);
					
					$this->db->close(); 
					$output_res = $this->connector_model->call_sp('xp_loc_aem_request_xml_file_job_objectid_update_status',array('READY_FOR_REVIEW',$aem_reference_code),FALSE,FALSE);	
					
						
				}elseif($aem_status =="REJECTED"){
					
					$tm_status="Translation in-Progress";
					$arr_request_values = array($aem_reference_code,$tm_status);
					$this->db->close(); 
					$result = $this->connector_model->call_sp('xp_loc_update_aem_status_request',$arr_request_values,'output',TRUE);
					
					$this->db->close(); 
					$output_res = $this->connector_model->call_sp('xp_loc_aem_request_xml_file_job_objectid_update_status',array('Translation in-Progress',$aem_reference_code),FALSE,FALSE);	
					
				}

		
		}else{
				header('HTTP/1.1 401 Unauthorized', true, 401);
				echo "401 Authorization Failed";
				exit;				
		}	
			
			
		}else{
			die("Method not allowed");
		}

	}
	
	
	
	
	/* AEM create project start here */
	public function aem_request_create_project(){
		
		$kptaem = new kptAEM();
		$arr_results = $kptaem->aem_response_metadata_master_select();
		
				
			
		if(!empty($arr_results)){			
			foreach($arr_results as $aem_master_rows){

							
				$metadata_master_id = $aem_master_rows->metadata_master_id;
				$aem_job_id			= $aem_master_rows->aem_job_id;
				$original_aem_job_id = $aem_master_rows->original_aem_job_id;
				$project_name		= $aem_master_rows->project_name;
				$project_desc		= $aem_master_rows->project_desc;
				$source_language	= $aem_master_rows->source_language;
				$target_language	= $aem_master_rows->target_language;
				$due_date			= $aem_master_rows->due_date;
				$job_meta			= $aem_master_rows->job_meta;
				$created_on			= $aem_master_rows->created_on;
				$translation_type	= $aem_master_rows->translation_type;
				$userid				= 283;
				$filename 			= "";
				$org_filename		= "";
				$created_time		= date("Y-m-d H:i:s");			
				
				
				$arr_aem_request = array($aem_job_id,$original_aem_job_id,$project_name,$filename,$org_filename,$userid,$created_time,$translation_type);
							
				
				$result = $kptaem->aem_request_pull_api_create_request_insert($arr_aem_request);
				
				//$result = "SUCCESS";
				if($result == "SUCCESS"){
										
				
					$translation_word_price	=0;
					$unique_words		   	=0;
					$duplicate_words       	=0;
					$total_unique_words_price =0;
					$duplicate_words_price =0;
					
					$arr_aem_languages = array($aem_job_id,$source_language,$target_language,$translation_word_price,$unique_words,$duplicate_words,$total_unique_words_price,$duplicate_words_price);					$result2 = $kptaem->aem_request_languages_insert($arr_aem_languages);
					
					
										
					
					/* QATAR Project Manager static ID */
					$client_pm_userid = 284;
					$arr_pm_client = array($aem_job_id,$client_pm_userid);				
					$result1 = $kptaem->aem_request_assigned_client_pm_insert($arr_pm_client);
					
									
					

					/* Meta child records start here */					
					$arr_metadata_child_results = $kptaem->aem_response_metadata($aem_job_id);
											
					if(!empty($arr_metadata_child_results)){
						foreach($arr_metadata_child_results as $aem_child_rows){			
														
							$metadata_child_id  = $aem_child_rows->metadata_child_id;
							$aem_job_id			= $aem_child_rows->aem_job_id;
							$object_job_id1		 = $aem_child_rows->aem_object_id;
							$original_aem_object_id = $aem_child_rows->original_aem_object_id;
							$aem_mime_type		= $aem_child_rows->aem_mime_type;
							$content_data		= addslashes($aem_child_rows->content_data);
							$created_on			= $aem_child_rows->created_on;
							$created_on			= date("Y-m-d H:i:s");	
							
							/*if($aem_mime_type !="text/html"){								
								$content_data = str_replace('<?xml version="1.0" encoding="UTF-8"?>','',$content_data);
							}	*/		
							
						
							
							$translation_status ="New";
							$arr_aem_meta_child = array($aem_job_id,$content_data,$object_job_id1,$original_aem_object_id,$aem_mime_type,$translation_status,$created_on);
							
							$result2 = $kptaem->create_aem_request_xml_files($arr_aem_meta_child);										
										
							 $basepath = public_path();
							/* creating XML file from AEM request */
							$aem_folder_dir = $basepath."/uploadfiles/aem/restapi/".$aem_job_id;
							if (!is_dir($aem_folder_dir)) {
								mkdir($aem_folder_dir, 0777, true);					
							}
							
							
							$arr_images_types 	= array('image/jpeg','image/jpg','image/png','image/gif');
							if(!in_array($aem_mime_type,$arr_images_types)){							
								if (is_dir($aem_folder_dir)) {			
										$aem_xml_file = $aem_folder_dir."/".$object_job_id1.".xml";
										$xmlfileobject = fopen($aem_xml_file, 'w');
										fwrite($xmlfileobject, $aem_child_rows->content_data);
										fclose($xmlfileobject);		
									}
							}
							
						 //}
							
							
						}					
						
					}					
					
				}				
			}			
		}
		
	}
	/* AEM create project end here */
	
	
	
}
