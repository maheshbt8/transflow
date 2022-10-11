<?php

namespace App\Http\Controllers\AEM;
use App\Http\Controllers\Controller;
use App\kptAEMqreviewer;
use Illuminate\Http\Request;
use Auth;

use App\Http\Controllers\AEM\DB;

class KptAEMqreviewerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		if ( checkpermission('aem_qreviewer_manage')) {
		$kptaemqreviewer = new kptAEMqreviewer();
		$user_id = 286;		
		$kptaemqreviewerresults = $kptaemqreviewer->aem_listing_assigned_qreviewer_results($user_id);		
		
		return view('AEM.kptqreviewer.index',compact('kptaemqreviewerresults'));
    }
	}


	/* request view for Qatar Reviewer */
	public function aem_request_details_qreviewer(){
		
	$kptaemqreviewer = new kptAEMqreviewer();	
	$aem_main_request_id = $_POST["aem_main_request_id"];
	
	$arr_request_xml_files = $kptaemqreviewer->aem_xml_files_select($aem_main_request_id);
	
			
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
				
				$translate_url = url("admin/kptaemqreviewerrequests/aem_request_qatar_proofreading/".$aem_reference_code."/".$aem_object_id);
				
							
				$download_url =url("admin/kptaemrequest/aem_request_translated_download/".$aem_reference_code."/".$aem_object_id);			 
				
								
				$output .='<div class="row">
				<div class="col-sm" style="color:#FFFFFF;">AEM Object:</div> <div class="col-sm" style="color:#FFFFFF;">'.$aem_object_id.'</div>';
				
				$output .='<div class="col-sm" style="text-align:right;color:#FFFFFF;">Status :</div> <div class="col-sm" style="color:#FFFFFF;">'.ucfirst($aem_xml_status).'</div>
				
				<div class="col-sm" style="text-align:center;"><a href="'.$download_url.'" style="color:#FFFFFF;">Download</a></div>
				<div class="col-sm" style="text-align:center;"><a href="'.$translate_url.'" style="color:#FFFFFF;" >Submit for Review</a></div>
				</div>';
				
				
				
				if($k != $count_xmlfiles)
					$output .='<hr style="border:1px solid #FFFFFF;">';
				
				$k++;			
			}

			$output .='</div>';
			
		}else{
			
			$output .='<div class="xp_row" style="color:#FFF;text-align:center;"> No Record(s) Found...</div>';
			
		}	
				
		echo $output;
		exit;			
		
	}
	/* request view for Qatar Reviewer */
	
	
	
	/* AEM request Qatar proofreading  */	
	public function aem_request_qatar_proofreading($reference_id ='',$object_id=''){
		 $kptaemqreviewer = new kptAEMqreviewer();	
		
		 $aem_reference_code = $reference_id;
		 $arr_aem_data = $kptaemqreviewer->getting_fulldetails_aemrequest_language($aem_reference_code);		 
		
		 $arr_object_id = array("objectid"=>$object_id);
		 return view('AEM.kptqreviewer.reviewform',compact('arr_aem_data','arr_object_id','kptaemqreviewer'));	 
		
	}
	/* AEM request Qatar proofreading  */

	/* AEM Translation Source Text */
	public function view_aem_translation_sourcetext(){
		$kptaemqreviewer = new kptAEMqreviewer();	

		$aem_translation_id = $_POST['aem_translation_id'];
		$translation_type   = $_POST['translation_type'];			 
			 
		$arr_translated_text = $kptaemqreviewer->getting_aem_request_sourcetext_translation_select($aem_translation_id);
			
			$sourcetext  = $arr_translated_text[0]->source_text;
			$targettext  = $arr_translated_text[0]->target_text;			
						
			if($translation_type == "Source"){
				$output='<div class="row">
						<div class="col-3"><b>Source Text</b> :</div>';				
				$output .='<div class="col-9">'.strip_tags($sourcetext).'</div>';	
				$output .='</div>';
			}elseif($translation_type =="Target"){			
				$output='<div class="row">
						<div class="col-3"><b>Target Text</b> :</div>';				
				$output .='<div class="col-9">'.strip_tags($targettext).'</div>';	
				$output .='</div>';
			}	
		
			echo $output;
			exit;
		
	}
	/* AEM Translation Source Text */
	
	
	/* AEM request for Qatar Reviewer proofreading */
	public function aem_request_qatar_proofreading_submit(){
		
			$kptaemqreviewer = new kptAEMqreviewer();	
			
			$hid_aem_reference_code 	= $_POST["hid_aem_reference_code"];
			if($hid_aem_reference_code !=""){				 
				$hid_aem_reference_code 	= $_POST["hid_aem_reference_code"];
				$arr_aem_translation_id		= $_POST["aem_translation_id"];
				$arr_target_comments		= $_POST["target_comments"];
				$hid_aem_source_language    = $_POST["hid_aem_source_language"];
				$hid_aem_target_language	= $_POST["hid_aem_target_language"];
				$reviewer_id			 	= Auth::user()->id;
				$created_time			    = date("Y-m-d H:i:s");
				
				$hid_aem_object_id 			= $_POST["hid_aem_object_id"];
				
				$k=0;
				$flag =1;
				foreach($arr_aem_translation_id as $target_rows){
					$aem_reference_code = $hid_aem_reference_code;
					$aem_translation_id = $target_rows;
					$translation_status = "translation_status_".$aem_translation_id;
					$translation_status = $_POST[$translation_status];
					$target_comments    = $arr_target_comments[$k];
					
					if($translation_status ==1){
						$qreviewer_status = $translation_status;
						$translation_status ="Qatar Review Completed";
					}elseif($translation_status ==0){
						$flag =0;
						$qreviewer_status = $translation_status;
						$translation_status ="Qatar Review Failed";
					}
					
					$arr_reviewer_comments_translation = array($aem_translation_id,$hid_aem_object_id,$target_comments,$translation_status,$reviewer_id,$created_time,$qreviewer_status);
								
										
					$arr_result = $kptaemqreviewer->aem_request_update_kpt_reviewer_text_row_by_row($arr_reviewer_comments_translation);

				
						
						
					$k++;					
				}
				
				if($flag ==1){					
					$tm_status="Qatar Review Success";
					//$arr_request_values = array($aem_reference_code,$tm_status);
					//$result = $this->kptaem_model->call_sp('xp_loc_update_aem_status_request',$arr_request_values,'output',TRUE);
					
					
					
					
					$output_res = $kptaemqreviewer->aem_request_xml_file_aem_ref_job_objectid_update_status($tm_status,$aem_reference_code,$hid_aem_object_id);	
					
					$arr_aem_results = $kptaemqreviewer->aem_request_xml_files_get_status_select($aem_reference_code);
					
							
					
					
					$aem_job_status = array();
					if(!empty($arr_aem_results)){
						foreach($arr_aem_results as $aem_rows){
														
							$aem_xml_status = $aem_rows->aem_xml_status;
								
							if($aem_xml_status == "Qatar Review Success")
								$aem_job_status[] = 1;
							else
								$aem_job_status[] = 0;
						}				
						
						
						if(!in_array(0,$aem_job_status)){							
							$tm_status="Qatar Review Success";
							$arr_request_values = array($aem_reference_code,$tm_status);
							
							$result = $kptaemqreviewer->update_aem_status_request($aem_reference_code,$tm_status);				
														
						}						
					}				
					
				}elseif($flag ==0){					
					$tm_status="Qatar Review Failed";
					$arr_request_values = array($aem_reference_code,$tm_status);
					
					$result = $kptaemqreviewer->update_aem_status_request($aem_reference_code,$tm_status);									
				}

				Session()->flash('flash_message', 'Successfully Reviewed the strings');
				return redirect()->route('admin.kptaemqreviewerrequests.index');
			
			}else {				
					print 'No reference request found...';
					 exit;				
			}
	}
	/* AEM request for Qatar Reviewer proofreading */
}
