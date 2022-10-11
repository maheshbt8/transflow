<?php

namespace App\Http\Controllers\AEM;

use App\Http\Controllers\Controller;
use App\kptAEMclientpm;
use Illuminate\Http\Request;

use App\Http\Controllers\AEM\DB;

class KptAEMclientpmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {    
		if (! checkpermission('authenticate_user_manage')) {
		$kptaemclientpm = new kptAEMclientpm();
		$user_id = 284;
		$kptaemclientpmresults = $kptaemclientpm->kptaemclientpmlisting($user_id);	
		
		return view('AEM.kptclientpm.index',compact('kptaemclientpmresults'));
    } 
}
	
	
	public function assigned_aem_request_details(){
		$aem_request_id = $_POST["aem_p_id"];
		
		$kptaemclientpm = new kptAEMclientpm();
		$arr_aemrequestdetails = $kptaemclientpm->getting_fulldetails_aemrequest_primaryid($aem_request_id);		
			
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
			
			
			$arr_languages_list = $kptaemclientpm->getting_language_list_reference_code($aem_reference_code);
			
			$aem_source_language  = $arr_languages_list[0]->aem_source_language;
			$aem_target_language  = $arr_languages_list[0]->aem_target_language;
			
			$output="";
			$output='<div class="row">
				<div class="col-sm">Reference Code :</div> <input type="hidden" name="hid_aem_req_id" id="hid_aem_req_id" value="'.$aem_req_id.'">
				<input type="hidden" name="hid_aem_reference_code" id="hid_aem_reference_code" value="'.$aem_reference_code.'">';				
		$output .='<div class="col-sm">'.$aem_reference_code.'</div>';	
		$output .='</div>';
		
		$output.='<div class="row">
				<div class="col-sm">Project Name :</div> ';				
		$output .='<div class="col-sm">'.$aem_project_name.'</div>';	
		$output .='</div>';
		
				
		$output.='<div class="row">
				<div class="col-sm">Source Language :</div> ';				
		$output .='<div class="col-sm">'.$aem_source_language.'</div>';	
		$output .='</div>';
		
		$output.='<div class="row">
				<div class="col-sm">Target Language :</div> ';				
		$output .='<div class="col-sm">'.$aem_target_language.'</div>';	
		$output .='</div>';		
		
		
		if($aem_project_status =="Confirmed Translation Completed" ){
			
			$arr_qreviewers = $kptaemclientpm->getting_qreviewers_lists();
			
			$output .='<div class="row" id="sucess_qreviewer" style="color:green;font-weight:bold;">';
			
			$output .='<div class="xp_row">
					<div class="col-sm">Reviewer</div> ';				
			$output .='<div class="col-sm">';			
			$output .='<select name="reviewer_name" id="id_qreviewer">                                
						<option value="">Select Reviewer</option>';	
						
			  if(!empty($arr_qreviewers)){
				  foreach($arr_qreviewers as $arr_reviewers_rows){									
				$output .='<option  value="'.$arr_reviewers_rows->user_id.'">'.$arr_reviewers_rows->first_name.' '.$arr_reviewers_rows->last_name.'</option>';                               
								 
					}
			  }							 								
			$output .='</select>';		
			$output .='</div>';	
			$output .='</div>';

		  
			$output.='<div class="row">
				<div class="col-sm">&nbsp;</div> ';				
			$output .='<div class="col-sm"><input type="button" id="id_clientpm_assign_qreviewer" name="assign_submit" value="Submit" class="btn btn-success"></div>';
			
			$output .='</div>';	
			
			
		}elseif($aem_project_status == "Assigned Qatar Review"){			
			
			$arr_qreviewers 	= $kptaemclientpm->getting_qreviewers_by_referencecode($aem_reference_code);
			
			 if(!empty($arr_qreviewers)){
					$p=1;
					foreach($arr_qreviewers as $t_rows){
						$first_name    = $t_rows->first_name;
						$last_name     = $t_rows->last_name;
						$language_spl  = $t_rows->language_spl;
						
						$qreviewer = $first_name." ".$last_name;
						
				$output.='<div class="row">
						<div class="col-sm">Reviewer '.$p.' :</div> ';				
				$output .='<div class="col-sm">'.$qreviewer.'</div>';	
				$output .='</div>';
				 $p++;
						
					}
			 }			
			
		}elseif($aem_project_status == "Qatar Review Success"){		
			
			$arr_translators 	= $kptaemclientpm->getting_translators_by_referencecode($aem_reference_code);
			
			 if(!empty($arr_translators)){
					$p=1;
					foreach($arr_translators as $t_rows){
						$first_name    = $t_rows->first_name;
						$last_name     = $t_rows->last_name;
						$language_spl  = $t_rows->language_spl;
						
						$translator = $first_name." ".$last_name." [".$language_spl."]";
						
					$output.='<div class="row">
						<div class="col-sm">Translator '.$p.' :</div> ';				
					$output .='<div class="col-sm">'.$translator.'</div>';	
					$output .='</div>';
				 $p++;
						
					}
			 }		
					
			
			$arr_kreviewers 	= $kptaemclientpm->getting_kreviewers_by_referencecode($aem_reference_code);
			
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


				
			$arr_qreviewers 	= $kptaemclientpm->getting_qreviewers_by_referencecode($aem_reference_code);
			
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
			
			
		}elseif($aem_project_status =="Accepted"){
			
			
			
			$output.='<div class="row">
				<div class="col-sm">Status :</div> ';				
		$output .='<div class="col-sm"><b>'.$aem_project_status.'</b></div>';	
		$output .='</div>';
			
			
			$output.='<div class="row">
					<div class="col-sm">&nbsp;</div> ';				
			$output .='<div class="col-sm">Job Translation is in Progress</div>';	
			$output .='</div>';
		}else {
			
			$output.='<div class="row">
				<div class="col-sm">Status :</div> ';				
			$output .='<div class="col-sm"><b>'.$aem_project_status.'</b></div>';	
			$output .='</div>';		
			
		}
					
				
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
			
			$arr_data = array();
			$arr_aem_xml_text_data = array();
			if(!empty($arr_metadata_child_results)){
					foreach($arr_metadata_child_results as $metachild_rows){
						
						    $aem_mime_type = $metachild_rows->aem_mime_type;				
							$object_job_id = $metachild_rows->aem_object_id;
							
							if($aem_mime_type == 'text/html'){
													
							  $xml_file ="/var/www/html/assets/uploadfiles/aem/restapi/".$aem_reference_code."/".$object_job_id.".xml";
							
							if (file_exists($xml_file)){								
								$node_cmd = "node /home/ubuntu/scripts/converters/from-xml2json.js $xml_file  ";
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
				$xliff_file = 'assets/uploadfiles/aem/'.$aem_source_filename;
				$node_cmd = "node /home/ubuntu/scripts/converters/from-xliff.js $xliff_file ";
				$returnjson  = exec($node_cmd);								
				$result_json = json_decode($returnjson);
				$arr_data    = $result_json->resources->f1;
				
				$arr_sourcelanage   = explode("-",$result_json->sourceLanguage);
				$arr_targetlanguage = explode("-",$result_json->targetLanguage);
			
			}else{
				
				$arr_languages_list = $this->kptaem_model->getting_language_list_reference_code($aem_reference_code);
				$aem_source_language  = $arr_languages_list[0]->aem_source_language;
				$arr_sourcelanage[0] = $aem_source_language;
				$aem_target_language  = $arr_languages_list[0]->aem_target_language;
				$arr_targetlanguage[0] = $aem_target_language;
			}

			
			$arr_source_language 	= $this->kptaem_model->getting_language_name($arr_sourcelanage[0]);	
			$source_language_name = $arr_source_language[0]->lang_name;
			$arr_target_language 	= $this->kptaem_model->getting_language_name($arr_targetlanguage[0]);
			$target_language_name = $arr_target_language[0]->lang_name;
			
			
			$arr_target_language_details = $this->kptaem_model->ratecard_master_target_language_where_condition($target_language_name);
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
						$word = preg_replace("#[^a-zA-Z\-]#", "", $word);
						$counts[$word] += 1;
						if($counts[$word]>1){
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
		
		$kptaemclientpm = new kptAEMclientpm();
		$arr_request_xml_files = $kptaemclientpm->aem_xml_files_select($aem_main_request_id);
		
		$k=1;
	$count_xmlfiles = count($arr_request_xml_files);
	if(!empty($arr_request_xml_files)){
			$output ='<div class="container">';		
			foreach($arr_request_xml_files as $row_xml){
				$xml_file_id 		= $row_xml->xml_file_id;
				$aem_reference_code = $row_xml->aem_reference_code;
				$xml_file 			= $row_xml->xml_file;
				$aem_object_id		= $row_xml->aem_object_id;
				$mime_type			= $row_xml->mime_type;
				$aem_xml_status		= $row_xml->aem_xml_status;
				$created_on			= $row_xml->created_on;
				
				$translate_url = url("admin/kptaemcpmrequest/aem_request_translated_output/".$aem_reference_code."/".$aem_object_id);
				
				$download_url = url("admin/kptaemrequest/aem_request_translated_download/".$aem_reference_code."/".$aem_object_id);
				 
				
								
				
				$output .='<div class="row">
				<div class="col-sm" style="color:#FFFFFF;">AEM Object:</div> <div class="col-sm" style="color:#FFFFFF;">'.$aem_object_id.'</div>';
				
				$output .='<div class="col-sm" style="text-align:right;color:#FFFFFF;" >Status :</div> <div class="col-sm" style="color:#FFFFFF;" >'.ucfirst($aem_xml_status).'</div>
				
				<div class="col-sm" style="text-align:center;color:#FFFFFF;"><a href="'.$download_url.'" style="color:#FFFFFF;" >Download</a></div>
				<div class="col-sm" style="text-align:center;color:#FFFFFF;"><a href="'.$translate_url.'" style="color:#FFFFFF;">Updates on Translation</a></div>
				</div>';
				
				
				
				
				if($k != $count_xmlfiles)
					$output .='<hr style="border:1px solid #FFFFFF;">';
				
				$k++;			
			}
		
			$output .='</div>';
			
		}else{			
			$output .='<div class="xp_row" style="text-align:center;color:#FFFFFF;"> No Record(s) Found...</div>';			
		}	
				
		echo $output;
		exit;	
		
	}
	/* AEM Xmlfiles request */
	
	
	
	
	
	/* AEM request translated download */
	public function aem_request_translated_download($reference_id ='',$object_id='') {
			$reference_id   = $reference_id;
			$object_id 		= $object_id;
			
			if($reference_id !="" && $object_id !="" ){
			
			$xml_file ="/var/www/html/assets/uploadfiles/aem/restapi/".$reference_id."/". $object_id."_target.xml";			 				 			 
				

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
			
		$kptaemclientpm = new kptAEMclientpm();		
		$aem_reference_code = $reference_id;				 
		$arr_aemrequest_details = $kptaemclientpm->getting_fulldetails_aemrequest_language($aem_reference_code);
				
		$arr_object_id = array("objectid"=>$object_id);		
		return view('AEM.kptclientpm.translatedform',compact('arr_aemrequest_details','arr_object_id','kptaemclientpm'));		
	}
	/* AEM request Translate */
	
	/* AEM request for change the status */
	public function request_change_aem_status(){		
				$kptaemclientpm = new kptAEMclientpm();	
				
				$reference_id 	 = $_POST['referenceid'];
				$tm_status		 = $_POST['tm_status'];	
				
				$result = array('message'=>'Status Updated Successfully.');
				$arr_request_values = array($reference_id,$tm_status);
				$result = $kptaemclientpm->update_aem_status_request($reference_id,$tm_status);	


				if($result == "Status Updated Successfully."){					
					if($tm_status == "Accepted"){
						$arr_request_xml_files = $kptaemclientpm->aem_xml_files_select($reference_id);					
										
						if(!empty($arr_request_xml_files)){
							foreach($arr_request_xml_files as $row_xml){
								$xml_file_id 		= $row_xml->xml_file_id;
								$aem_reference_code = $row_xml->aem_reference_code;
								$xml_file 			= $row_xml->xml_file;
								$aem_object_id		= $row_xml->aem_object_id;
								$mime_type			= $row_xml->mime_type;
								$aem_xml_status		= $row_xml->aem_xml_status;
								$created_on			= $row_xml->created_on;				
												
								
								$aem_folder_dir = "/var/www/html/assets/uploadfiles/aem/restapi/".$reference_id;
								if (!is_dir($aem_folder_dir)) {
									mkdir($aem_folder_dir, 0777, true);					
								}
								
								
								if (is_dir($aem_folder_dir)) {			
									$aem_xml_file = $aem_folder_dir."/".$aem_object_id.".xml";
									$xmlfileobject = fopen($aem_xml_file, 'w');
									fwrite($xmlfileobject, $xml_file);
									fclose($xmlfileobject);		
								}							
															
								$output_res = $this->kptaem_model->call_sp('xp_loc_aem_request_xml_file_job_objectid_update_status',array($tm_status,$reference_id),FALSE,FALSE);
								
								
							}
						}
					
				 }elseif($tm_status =="Translation Completed"){
					 
					 $arr_request_xml_files = $this->kptaem_model->call_sp('xp_loc_get_aem_request_xml_files_other_xml_type_select',array($reference_id),FALSE,FALSE);
					 
					 if(!empty($arr_request_xml_files)){
						foreach($arr_request_xml_files as $row_xml){
							$xml_file_id 		= $row_xml->xml_file_id;
							$aem_reference_code = $row_xml->aem_reference_code;
							$xml_file 			= $row_xml->xml_file;
							$aem_object_id		= $row_xml->aem_object_id;
							$mime_type			= $row_xml->mime_type;
							$aem_xml_status		= $row_xml->aem_xml_status;
							$created_on			= $row_xml->created_on;
							
													
								
							if($mime_type =="text/xml"){											
								 $xml_file = str_replace('<?xml version="1.0" encoding="UTF-8"?>','',$xml_file);
							}					
							
							
					$arr_aemrequest_target_xml = array($aem_reference_code,$aem_object_id,$xml_file);
					
					$result5 = $this->kptaem_model->call_sp('xp_loc_aem_request_target_xml_file_insert',$arr_aemrequest_target_xml,'output',TRUE);
					
					
					$output_res = $this->kptaem_model->call_sp('xp_loc_aem_request_xml_file_job_objectid_update_status',array($tm_status,$reference_id),FALSE,FALSE);
							
						}
					 }			 
					 
				  }else{					  
					  
					  $output_res = $this->kptaem_model->call_sp('xp_loc_aem_request_xml_file_job_objectid_update_status',array($tm_status,$reference_id),FALSE,FALSE);					
					  
				  }					
					
				}

				
				if($result =='Status Updated Successfully.'){					
					$msg = 'Status Updated Successfully.';
					$status ='success';
						
				}else{
					$msg = 'Failed updation';
					$status ='fail';
				}	
				Session()->flash('flash_message', 'AEM status updated successfully.');
				print_r(json_encode(array($result)));
				exit;



				
				
	}
	/* AEM request for change the status */
	
}
