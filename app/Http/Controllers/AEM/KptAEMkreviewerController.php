<?php

namespace App\Http\Controllers\AEM;

use App\Http\Controllers\Controller;
use App\kptAEMkreviewer;
use Illuminate\Http\Request;
use Auth;

use App\Http\Controllers\AEM\DB;

class KptAEMkreviewerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {	
		if ( checkpermission('aem_kreviewer_manage')) {
				
		$kptaemkreviewer = new kptAEMkreviewer();
		$user_id = 286;		
		$kptaemkreviewerresults = $kptaemkreviewer->aem_listing_assigned_kreviewer_results($user_id);		
		
		return view('AEM.kptkreviewer.index',compact('kptaemkreviewerresults'));
    }
}

	/* Toggle start for Translation data start here - KReviewer */
	public function aem_request_details_kreviewer(){
		$kptaemkreviewer = new kptAEMkreviewer();		
		$aem_main_request_id = $_POST["aem_main_request_id"];		
		$arr_request_xml_files = $kptaemkreviewer->aem_xml_files_select($aem_main_request_id);
		
		
		
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
				
				$translate_url = url("admin/kptaemkreviewerrequests/aem_request_kpt_proofreading/".$aem_reference_code."/".$aem_object_id);
								
				$download_url = url("admin/kptaemrequest/aem_request_translated_download/".$aem_reference_code."/".$aem_object_id);	 
				
				
				
				$output .='<div class="row">
				<div class="col-sm" style="color:#FFFFFF;">AEM Object:</div> <div class="col-sm" style="color:#FFFFFF;">'.$aem_object_id.'</div>';
				
				$output .='<div class="col-sm" style="text-align:right;color:#FFFFFF;">Status :</div> <div class="col-sm" style="color:#FFFFFF;">'.ucfirst($aem_xml_status).'</div>
				
				<div class="col-sm" style="text-align:center;color:#FFFFFF;"><a href="'.$download_url.'" style="color:#FFFFFF;">Download</a></div>
				<div class="col-sm" style="text-align:center;color:#FFFFFF;"><a href="'.$translate_url.'" style="color:#FFFFFF;">Submit for Review</a></div>
				</div>';
				
				
				
				if($k != $count_xmlfiles)
					$output .='<hr style="border:1px solid #FFFFFF;">';
				
				$k++;			
			}

			$output .='</div>';
			
		}else{
			
			$output .='<div class="row" style="text-align:center;"> No Record(s) Found...</div>';
			
		}	
				
	echo $output;
	exit;			
		
		
	}
	/* Toggle start for Translation data start here - KReviewer */
	
	
	
	
	
	
	
	/* AEM request Qatar proofreading  */	
	public function aem_request_kpt_proofreading($reference_id ='',$object_id=''){
		
		 $kptaemkreviewer = new kptAEMkreviewer();	
		
		 $aem_reference_code = $reference_id;
		 $arr_aem_data = $kptaemkreviewer->getting_fulldetails_aemrequest_language($aem_reference_code);		 
		
		 $arr_object_id = array("objectid"=>$object_id);
		 return view('AEM.kptkreviewer.reviewform',compact('arr_aem_data','arr_object_id','kptaemkreviewer'));		
	}
	/* AEM request Qatar proofreading  */

		
	/* AEM request for KPT Reviewer proofreading */
	public function aem_request_kpt_proofreading_submit(){
				 $kptaemkreviewer = new kptAEMkreviewer();	
		
				$hid_aem_reference_code 	= $_POST["hid_aem_reference_code"];
			if($hid_aem_reference_code !=""){				 
				$hid_aem_reference_code 	= $_POST["hid_aem_reference_code"];
				$arr_aem_translation_id		= $_POST["aem_translation_id"];
				$arr_target_comments		= $_POST["target_comments"];
				$hid_aem_source_language    = $_POST["hid_aem_source_language"];
				$hid_aem_target_language	= $_POST["hid_aem_target_language"];
				$reviewer_id			 	= Auth::user()->id;
				$created_time			    = date("Y-m-d H:i:s");
				$aem_object_id				= $_POST["hid_aem_object_id"];
				
				$k=0;					
				foreach($arr_aem_translation_id as $target_rows){					
					
					$aem_reference_code = $hid_aem_reference_code;
					$aem_translation_id = $target_rows;
					$translation_status = "translation_status_".$aem_translation_id;
					$translation_status = $_POST[$translation_status];
					$target_comments    = $arr_target_comments[$k];
					
					if($translation_status ==1){
						$kreviewer_status = '1';
						$translation_status ="KPT Review Completed";
					}elseif($translation_status ==0){
						$translation_status ="KPT Review Failed";
						$kreviewer_status = '0';
					}
					
					$arr_reviewer_comments_translation = array($aem_translation_id,$target_comments,$translation_status,$reviewer_id,$created_time,$kreviewer_status);					
										
					$arr_result = $kptaemkreviewer->aem_request_update_kpt_reviewer_text_row_by_row($arr_reviewer_comments_translation);						
										
					$k++;					
				}		
			
				
				$arr_proofreader_translation = $kptaemkreviewer->aem_request_count_proofread_translation_objectid_select($aem_reference_code,$aem_object_id);					
				
				if($arr_proofreader_translation[0]->failed_review_count ==0){
					
					$tm_status="KPT Review Completed";
					//$arr_request_values = array($aem_reference_code,$tm_status);
					//$result = $this->kptaem_model->call_sp('xp_loc_update_aem_status_request',$arr_request_values,'output',TRUE);					
					
										
					$output_res = $kptaemkreviewer->aem_request_xml_file_aem_ref_job_objectid_update_status($tm_status,$aem_reference_code,$aem_object_id);	
					
					$arr_aem_results = $kptaemkreviewer->aem_request_xml_files_get_status_select($aem_reference_code);
					
					$aem_job_status = array();
					if(!empty($arr_aem_results)){
						foreach($arr_aem_results as $aem_rows){
														
							$aem_xml_status = $aem_rows->aem_xml_status;
								
							if($aem_xml_status == "KPT Review Completed")
								$aem_job_status[] = 1;
							else
								$aem_job_status[] = 0;
						}				
						
						
						if(!in_array(0,$aem_job_status)){							
							$tm_status="KPT Review Completed";									
							$result = $kptaemkreviewer->update_aem_status_request($aem_reference_code,$tm_status);
														
						}						
					}					
					
				}else{
					
					$tm_status="KPT Review Failed";
					$arr_request_values = array($aem_reference_code,$tm_status);
					$result = $kptaemkreviewer->update_aem_status_request($aem_reference_code,$tm_status);		
								
				}		
			
			
			//$this->session->set_flashdata('success','Successfully completed Proofreading of the strings'); 
			//redirect(base_url().'kptaem/aem_request_kpt_proofreading/'.$hid_aem_reference_code.'/'.$aem_object_id);

			
			Session()->flash('flash_message', 'Successfully Reviewed the strings');
			return redirect()->route('admin.kptaemkreviewerrequests.index');
			
			}else{				
					print 'No reference request found...';
					 exit;				
			}
	}
	/* AEM request for KPT Reviewer proofreading */
	
}
