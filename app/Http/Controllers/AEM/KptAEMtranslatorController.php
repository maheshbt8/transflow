<?php

namespace App\Http\Controllers\AEM;

use App\Http\Controllers\Controller;
use App\kptAEMtranslator;
use Illuminate\Http\Request;
use Auth;

use App\Http\Controllers\AEM\DB;

class KptAEMtranslatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {	
		if ( checkpermission('aem_translator_manage')) {	
		$kptaemtranslator = new kptAEMtranslator();
		$user_id = 285;		
		$kptaemtranslatorresults = $kptaemtranslator->aem_listing_assigned_translator_results($user_id);		
		
		return view('AEM.kpttranslator.index',compact('kptaemtranslatorresults'));
    } 
}
	
	/* AEM request details for Translator */
	public function aem_request_details_translator(){
		$kptaemtranslator = new kptAEMtranslator();
		
		$aem_main_request_id = $_POST["aem_main_request_id"];

		$arr_request_xml_files = $kptaemtranslator->aem_xml_files_select($aem_main_request_id);
		
		
		
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
				
				$translate_url = url("admin/kptaemtranslatorrequests/aem_request_translate/".$aem_reference_code."/".$aem_object_id);
				
				$download_url = url("admin/kptaemrequest/aem_request_translated_download/".$aem_reference_code."/".$aem_object_id);				
				 				
				$output .='<div class="row">
				<div class="col-sm"  style="color:#FFFFFF;">AEM Object:</div> <div class="col-sm" style="color:#FFFFFF;">'.$aem_object_id.'</div>';
				
				$output .='<div class="col-sm" style="text-align:right;color:#FFFFFF;">Status :</div> <div class="col-sm"  style="color:#FFFFFF;">'.ucfirst($aem_xml_status).'</div>
				
				<div class="col-sm" style="text-align:center;"><a href="'.$download_url.'" style="color:#FFFFFF;">Download</a></div>
				<div class="col-sm" style="text-align:center;"><a href="'.$translate_url.'" style="color:#FFFFFF;">Translate</a></div>
				</div>';
				
						
								
				
				if($k != $count_xmlfiles)
					$output .='<hr style="border:1px solid #FFFFFF;">';
				
				$k++;			
			}
			
			$output .='</div>';

			
		}else{
			
			$output .='<div class="row" style="color:#FFF;text-align:center;"> No Record(s) Found...</div>';
			
		}	
				
	echo $output;
	exit;			
	}
	/* AEM request details for Translator */
	
	
	/* AEM request Translate */
	public function aem_request_translate($reference_id ='',$object_id=''){
		$kptaemtranslator = new kptAEMtranslator();
		
		$aem_reference_code = $reference_id;				 
		$arr_aemrequest_details = $kptaemtranslator->getting_fulldetails_aemrequest_language($aem_reference_code);
		
		$arr_object_id = array("objectid"=>$object_id);		
		return view('AEM.kpttranslator.translateform',compact('arr_aemrequest_details','arr_object_id','kptaemtranslator'));		
		
	}
	/* AEM request Translate */
	
	
	/* AEM Translation submit */
	public function aem_request_translation_submit(){

			$kptaemtranslator = new kptAEMtranslator();
		
			$hid_aem_reference_code 	= $_POST["hid_aem_reference_code"];
			$arr_source_text			= $_POST["source_text"];
			$arr_target_text			= $_POST["target_text"];
			$hid_aem_source_language    = $_POST["hid_aem_source_language"];
			$hid_aem_target_language	= $_POST["hid_aem_target_language"];
			$translator_id			 	= Auth::user()->id;
			$created_time			    = date("Y-m-d H:i:s");			
			$hid_aem_object_id			= $_POST["hid_aem_object_id"];			
			$translated_flag			= $_POST["translated_flag"];		

			
			
			
			$basepath = public_path();
			$uploads_dir   = $basepath.'/uploadfiles/aem/restapi';		 
			$meta_xml_file = $uploads_dir."/".$hid_aem_reference_code."/".$hid_aem_object_id.".xml";
			
			$node_cmd 	 = "node ".$basepath."/converters/from-xml2json.js $meta_xml_file ";
			$returnjson  = shell_exec($node_cmd);								
			$result_json = json_decode($returnjson);
			
			$json_aem_object = array();			
			//$json_aem_object['_declaration']['_attributes']['version'] =$result_json->_declaration->_attributes->version;
			
			//$json_aem_object['_declaration']['_attributes']['encoding'] =$result_json->_declaration->_attributes->encoding;
			
			$json_aem_object['translationObjectFile']['_attributes']['fileType'] =$result_json->translationObjectFile->_attributes->fileType;
			
			$json_aem_object['translationObjectFile']['_attributes']['sourcePath'] =$result_json->translationObjectFile->_attributes->sourcePath;
			
			
			
			if($hid_aem_reference_code !=""){
				$p=0;				
				foreach($arr_target_text as $target_rows){				
					
				$json_aem_object['translationObjectFile']['translationObjectProperties']['property'][$p]['_attributes']['isMultiValue'] = $result_json->translationObjectFile->translationObjectProperties->property[$p]->_attributes->isMultiValue;
				
				$json_aem_object['translationObjectFile']['translationObjectProperties']['property'][$p]['_attributes']['nodePath'] = $result_json->translationObjectFile->translationObjectProperties->property[$p]->_attributes->nodePath;
				
				$json_aem_object['translationObjectFile']['translationObjectProperties']['property'][$p]['_attributes']['propertyName'] = $result_json->translationObjectFile->translationObjectProperties->property[$p]->_attributes->propertyName;				
				
				$json_aem_object['translationObjectFile']['translationObjectProperties']['property'][$p]['_text'] = $target_rows;				
									
					$aem_reference_code = $hid_aem_reference_code;				
					$target_text 		= addslashes($target_rows);
					$source_text 		= addslashes($arr_source_text[$p]);
					$translate_status   = "KPT Translation Completed";
					
					$translated_flag1    = $translated_flag[$p];
					
					$arr_import_translation = array($hid_aem_reference_code,$hid_aem_object_id,$hid_aem_source_language,$hid_aem_target_language,$source_text,$target_text,$translate_status,$translator_id,$created_time,$translated_flag1);

															
					$arr_result = $kptaemtranslator->aem_request_insert_or_update_translated_text_row_by_row($arr_import_translation);								
										
					$p++;
				}
				
								
				
				
				//$translate_status="KPT Translation Completed";
				//$arr_import_translation1 = array($hid_aem_reference_code,$hid_aem_object_id,$translate_status);
				
				
				//$arr_result1 = $this->kptaem_model->call_sp('xp_loc_aem_request_update_translated_text_all_rows',$arr_import_translation1,FALSE,FALSE);	
							
							
				$aem_object_json_data = json_encode($json_aem_object,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
				
							
				
		if($aem_object_json_data !=""){				
				$aem_folder_dir = $basepath.'/uploadfiles/aem/restapi/'.$hid_aem_reference_code;		 
				$meta_xml_file = $aem_folder_dir."/".$hid_aem_object_id.".xml";			
				
				if (is_dir($aem_folder_dir)) {
					
						$aem_xml_file = $aem_folder_dir."/".$hid_aem_object_id.".json";
						$jsonfileobject = fopen($aem_xml_file, 'w');
						fwrite($jsonfileobject, $aem_object_json_data);
						fclose($jsonfileobject);

					$node_cmd = "node ".$basepath."/converters/from-json2xml.js $aem_xml_file  ";
					$response_xml1 = shell_exec($node_cmd);
					
					$response_xml = htmlspecialchars_decode(addslashes($response_xml1));
					$arr_aemrequest_target_xml = array($hid_aem_reference_code,$hid_aem_object_id,$response_xml);
					
					
					$result5 = $kptaemtranslator->aem_request_target_xml_file_insert($arr_aemrequest_target_xml);	
					

					if (is_dir($aem_folder_dir)) {					
						
						$aem_xml_file_target = $aem_folder_dir."/".$hid_aem_object_id."_target.xml";
						$xml_targetobject = fopen($aem_xml_file_target, 'w');
						fwrite($xml_targetobject, htmlspecialchars_decode($response_xml1));
						fclose($xml_targetobject);
					}
						
				}
				
			 }						
								
				$arr_aem_target_text_count = $kptaemtranslator->aem_request_translation_verification_with_objectid($aem_reference_code,$hid_aem_object_id);
				
				if($arr_aem_target_text_count ==0){
					//$tm_status="KPT Translation Completed";
					//$arr_request_values = array($aem_reference_code,$tm_status);
					//$result = $this->kptaem_model->call_sp('xp_loc_update_aem_status_request',$arr_request_values,'output',TRUE);										
					
					$output_res = $kptaemtranslator->aem_request_xml_file_aem_ref_job_objectid_update_status($aem_reference_code,$hid_aem_object_id);
									
					
					$arr_aem_results = $kptaemtranslator->aem_request_xml_files_get_status_select($aem_reference_code);
					
					
					$aem_job_status = array();
					if(!empty($arr_aem_results)){
						foreach($arr_aem_results as $aem_rows){
														
							$aem_xml_status = $aem_rows->aem_xml_status;
								
							if($aem_xml_status == "KPT Translation Completed")
								$aem_job_status[] = 1;
							else
								$aem_job_status[] = 0;
						}				
						
						
						if(!in_array(0,$aem_job_status)){						
							$result = $kptaemtranslator->update_aem_status_request($aem_reference_code);							
						}						
					}

					
				}else{
					
					//$tm_status="Translation in-Progress";
					//$arr_request_values = array($aem_reference_code,$tm_status);
					//$result = $this->kptaem_model->call_sp('xp_loc_update_aem_status_request',$arr_request_values,'output',TRUE);
					$output_res = $kptaemtranslator->aem_request_xml_file_aem_ref_job_objectid_update_status1($aem_reference_code,$hid_aem_object_id);		
				}				
				
				
				Session()->flash('flash_message', 'Successfully Translated the strings');
				return redirect()->route('admin.kptaemtranslatorrequests.index');				
				
			}else{				
				print 'No reference request found...';
				exit;	
			}
			
		}	
	/* AEM Translation submit */
	
}
