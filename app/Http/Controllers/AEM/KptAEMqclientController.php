<?php

namespace App\Http\Controllers\AEM;

use App\Http\Controllers\Controller;
use App\kptAEMqclient;
use Illuminate\Http\Request;

use App\Http\Controllers\AEM\DB;

class KptAEMqclientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
		if ( checkpermission('aem_qatar_client_manage')) {
		$kptaemqclient = new kptAEMqclient();
		$user_id = 283;		
		$kptaemqclientresults = $kptaemqclient->kptaemqclientlisting($user_id);	
		
		
		return view('AEM.kptqclient.index',compact('kptaemqclientresults'));
    } 
}
	
	
	
	
	
	/* Assigned AEM request details */
	public function assigned_aem_request_details(){
		$aem_request_id = $_POST["aem_p_id"];		
		$kptaemqclient = new kptAEMqclient();
		$arr_aemrequestdetails = $kptaemqclient->getting_fulldetails_aemrequest_primaryid($aem_request_id);

					
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
			
			
			$arr_languages_list = $kptaemqclient->getting_language_list_reference_code($aem_reference_code);
			
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
			
			$arr_qreviewers = $this->kptaem_model->getting_qreviewers_lists();
			
			$output .='<div class="row" id="sucess_qreviewer" style="color:green;font-weight:bold;">';
			
			$output .='<div class="row">
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
			
			$arr_qreviewers 	= $this->kptaem_model->getting_qreviewers_by_referencecode($aem_reference_code);
			
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
			
			$arr_translators 	= $kptaemqclient->getting_translators_by_referencecode($aem_reference_code);
			
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
					
			
			$arr_kreviewers 	= $kptaemqclient->getting_kreviewers_by_referencecode($aem_reference_code);
			
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


				
			$arr_qreviewers 	= $kptaemqclient->getting_qreviewers_by_referencecode($aem_reference_code);
			
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
	/* Assigned AEM request details */
	
	
	/* AEM Xmlfiles request */
	public function aem_xmlfiles_request(){					
		$aem_main_request_id = $_POST["aem_main_request_id"];
		
		$kptaemqclient = new kptAEMqclient();	
		$arr_request_xml_files = $kptaemqclient->aem_xml_files_select($aem_main_request_id);
		
		
		
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
				
				$translate_url = url("admin/kptaemqclientrequests/aem_request_translated_output/".$aem_reference_code."/".$aem_object_id);
				
				$download_url = url("admin/kptaemrequest/aem_request_translated_download/".$aem_reference_code."/".$aem_object_id);
				 
				
								
				
				$output .='<div class="row">
				<div class="col-sm" style="color:#FFFFFF;" >AEM Object:</div> <div class="col-sm" style="color:#FFFFFF;">'.$aem_object_id.'</div>';
				
				$output .='<div class="col-sm" style="text-align:right;color:#FFFFFF;">Status :</div> <div class="col-sm" style="color:#FFFFFF;">'.ucfirst($aem_xml_status).'</div>
				
				<div class="col-sm" style="text-align:center;"><a href="'.$download_url.'" style="color:#FFFFFF;">Download</a></div>
				<div class="col-sm" style="text-align:center;"><a href="'.$translate_url.'" style="color:#FFFFFF;">Updates on Translation</a></div>
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
	
	
	
	
	
	
	/* AEM request Translate */
	public function aem_request_translated_output($reference_id ='',$object_id=''){
		
		$kptaemqclient = new kptAEMqclient();
		
		$aem_reference_code = $reference_id;				 
		$arr_aemrequest_details = $kptaemqclient->getting_fulldetails_aemrequest_language($aem_reference_code);
		
		
		
		$arr_object_id = array("objectid"=>$object_id);		
		return view('AEM.kptqclient.translatedform',compact('arr_aemrequest_details','arr_object_id','kptaemqclient'));		
		
	}
	/* AEM request Translate */
	
	
	
	
}
