<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\loc_languages;
use App\Translationmodel;
use Auth;
use File;
use App\models\VideototextModel;
use App;
use App\kptAEMtranslator;

class VideototextController extends Controller
{
     public function index(){

		
			 
        if (! checkpermission('videototext')) {
            return abort(401);
        }
            $videototext= VideototextModel::get();
			
			
         return view('admin.videototext.index',compact('videototext'));
     }

     public function create(){
        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status','ACTIVE')->get();
		return view('admin.videototext.create',compact('loc_languages'));
	}
    	
	
	
	
	
	
	
	/* Bright Cove API for upload video submit */	
	public function store(Request $request){
		
				 if (! checkpermission('videototext')) {
						return abort(401);
					}
		
				$user_role = Auth::user()->roles[0]->name;
				$userid=Auth::user()->id;
				// if($user_role == 'Administrator'){
			
							
				$this->validate($request, ['destination_language' => ['required']]);				
				$this->validate($request, ['source_file' => ['required']]);
				
								
				$supported_image 	= 	array('mp3','mp4','avi','flv','wmv','mov');				
				$jobname 		 	= 	$request['job_name'];			
				$file_name 			= 	$request->file('source_file');
				$target_language 	= 	$request['destination_language'];
				
								
				
				$source_file_extension = strtolower($request->file('source_file')->getClientOriginalExtension());
				$uploads_dir = base_path()."/public/storage/videototext/";
				
				
				
				$file = $request->file('source_file');				
				if (in_array($source_file_extension, $supported_image)) {
					
                                    $get_uploaded_date = date("Y-m-d H:i:s");
									$job_status="completed";
									$upload_filename = rand(1000000000,5000000000);
									$upload_filename_extension = $upload_filename.'.'.$source_file_extension;

								$job_id = uniqid('TSFL');									
							if(!file_exists("$uploads_dir/$upload_filename_extension")){
										$path=base_path()."/public/storage/videototext/";									
										$file->move($path,$upload_filename_extension);									 
										
										$full_path_video = $path."public/storage/videototext/".$upload_filename_extension;
										
										$arr_video_details = array("job_name"=>$job_id,"source_file_name"=>$upload_filename,"source_fullpath"=>$full_path_video,"file_extension"=>$source_file_extension,"target_language"=>$target_language);
									
									
								$arr_aws_transcribe_results = VideototextModel::apiuploadvideo($arr_video_details);
									
									
                                    return redirect()->route("admin.videototext.index");								
						         }									
									
				}else {
					
					echo "MP4 not supported here";
					exit;
					
				}
                // else{					
				// 		$soucefilestatus = 2;
				// 		$this->session->set_flashdata('insert_status',7);//worong format
				// 		redirect(base_url().'videototext/index'); 

			// }			
				
						
    }
			
            
	/* Bright Cove API for upload video submit */
	
	
	
	
	
	/* Getting Job details of AWS Transcribe */
	public function get_job_details($arg1){		
		$user_role = Auth::user()->roles[0]->name;
        if($user_role == 'Administrator'){			
			$JobId = $arg1;
			$arr_aws_transcribe_values = array($JobId);
			$arr_aws_transcribe_results = $this->videototextModel->awstranscribelisting_id($arr_aws_transcribe_values);
			
			
			
			if(empty($arr_aws_transcribe_results)){				
				print 'No Data existed in the Transflow';				
			}else{										
				
				$job_name = $arr_aws_transcribe_results[0]->job_name;
				$job_id = $arr_aws_transcribe_results[0]->job_id;
				$arr_resp_api = $this->videototextModel->get_jobdetails_awstranscribe_refresh($job_name,$job_id);
					
				if(strtolower($arr_resp_api) =="success"){				
					$this->session->set_flashdata('success','Successfully added'); //sucesss message for subtitle update the status
					redirect(base_url().'videotext/view');									
				}elseif($arr_resp_api =="waiting"){										
					$this->session->set_flashdata('fail','Video still is in In-Progress State...'); //failed message for subtitle update the status
					redirect(base_url().'videotext/view'); 
				}								
			}
			
		}	
	}
	/* Getting Job details of AWS Transcribe */	
	
	
	
	/* Getting Job details of AWS Transcribe */
	public function get_job_details_download_txt($arg1){
		
		$user_role = Auth::user()->roles[0]->name;
        if($user_role == 'Administrator'){			
			$JobId = $arg1;
			$arr_aws_transcribe_values = array($JobId);
			$arr_aws_transcribe_results = $this->videototextModel->awstranscribelisting_id($arr_aws_transcribe_values);
								
			if(empty($arr_aws_transcribe_results)){				
				print 'No Data existed in the Transflow';				
			}else{										
				
				$job_name 		 = $arr_aws_transcribe_results[0]->job_name;
				$target_language = $arr_aws_transcribe_results[0]->target_language;
				$arr_resp_api = $this->videototextModel->get_jobdetails_awstranscribe($job_name);
				
											
				if(strtoupper($arr_resp_api) =="COMPLETED"){				
				
						
						$output_json_file = $job_name.".json";
						$uploads_dir = 'https://dev.transflowtms.com/assets/uploadfiles';
						$output_json_file = $uploads_dir."/".$output_json_file;						
						$output_json = json_decode(file_get_contents($output_json_file));
						
						$output_video_txt = $output_json->results->transcripts[0]->transcript;
						
						if($target_language !="en"){
							$gtranslate_response = $this->videototextModel->gtranslate_curl_request($output_video_txt,$target_language);
							$output_video_txt = $gtranslate_response;
						}
						
						
						$upload_dir = "assets/uploadfiles";					
						$output_video_filename = $job_name.".txt";
						$org_output_video_txt_file = $upload_dir."/".$output_video_filename;
						
						/* writing video file start here */
						$video_file_txt = fopen($org_output_video_txt_file, 'w');
						fwrite($video_file_txt, $output_video_txt);
						fclose($video_file_txt);
						/* writing video file start here */
						
					if(file_exists($org_output_video_txt_file)){						
						
						$uploads_dir = 'https://dev.transflowtms.com/assets/uploadfiles';
						$org_txt_file = $uploads_dir."/".$output_video_filename;
						header('Content-Type: html/txt');
						header('Content-Disposition: attachment;filename="'.$output_video_filename.'"');
						header('Cache-Control: max-age=0');
						echo file_get_contents($org_txt_file);
						exit;
					 }else{
						 
						 $this->session->set_flashdata('fail','Video still is in In-Progress State...'); //failed message for subtitle update the status
						 redirect(base_url().'videotext/view');					  
						  
					  }					
						
				}elseif($arr_resp_api =="waiting"){					
					$this->session->set_flashdata('fail','Video still is in In-Progress State...'); //failed message for subtitle update the status
					redirect(base_url().'videotext/view'); 
				}							
			}
			
		}	
	}
	/* Getting Job details of AWS Transcribe */	
	
	
	
	
	/* Getting Job details of AWS Transcribe */
	public function get_job_details_download_json($arg1){
				
		$user_role = $this->current_user->role_name;
		if($user_role == 'Admin' || $user_role == 'Tclient' || $user_role == 'Tclientdoc'|| $user_role == 'brightcove'){			
			$JobId = $arg1;
			$arr_aws_transcribe_values = array($JobId);
			$arr_aws_transcribe_results = $this->videototextModel->awstranscribelisting_id($arr_aws_transcribe_values);
								
			if(empty($arr_aws_transcribe_results)){				
				print 'No Data existed in the Transflow';				
			}else{										
				
				$job_name = $arr_aws_transcribe_results[0]->job_name;
				$arr_resp_api = $this->videototextModel->get_jobdetails_awstranscribe($job_name);
							
				if(strtoupper($arr_resp_api) =="COMPLETED"){
											
						$upload_dir = "assets/uploadfiles";					
						$output_video_filename = $job_name.".json";
						$org_output_video_json_file = $upload_dir."/".$output_video_filename;					
												
					if(file_exists($org_output_video_json_file)){					
						$uploads_dir = 'https://dev.transflowtms.com/assets/uploadfiles';
						$org_txt_file = $uploads_dir."/".$output_video_filename;
						header('Content-Type: application/json');
						header('Content-Disposition: attachment;filename="'.$output_video_filename.'"');
						header('Cache-Control: max-age=0');
						echo file_get_contents($org_txt_file);
						exit;
					 }else{
						 
						 $this->session->set_flashdata('fail','Video still is in In-Progress State...'); //failed message for subtitle update the status
						 redirect(base_url().'videotext/view');					  
						  
					  }					
						
				}elseif($arr_resp_api =="waiting"){					
					$this->session->set_flashdata('fail','Video still is in In-Progress State...'); //failed message for subtitle update the status
					redirect(base_url().'videotext/view'); 
				}							
			}
			
		}
	}
	/* Getting Job details of AWS Transcribe */	
	
	
		
	
	
	
	
	
	/* Getting Job details of AWS Transcribe */
	public function embed_subtitles_video($arg1){
		
		$user_role = $this->current_user->role_name;
		if($user_role == 'Admin' || $user_role == 'Tclient' || $user_role == 'Tclientdoc'|| $user_role == 'ramco_salesd'){
			$JobId = $arg1;
			$arr_aws_transcribe_values = array($JobId);
			$arr_aws_transcribe_results = $this->subtitles_model->awstranscribelisting_id($arr_aws_transcribe_values);
			if(empty($arr_aws_transcribe_results)){				
				print 'No Data existed in the Transflow';				
			}else{							
				
				$job_name = $arr_aws_transcribe_results[0]->job_name;
				$job_video_media_url = $arr_aws_transcribe_results[0]->job_video_media_url;
							
				$uploads_dir = 'assets/uploadfiles';
				$output_srt_mp4_file = $uploads_dir."/".$job_name."_output.mp4";
								
				if(file_exists($output_srt_mp4_file)){
					
					$output_video_filesize =  filesize($output_srt_mp4_file);
					$org_Video_filesize = filesize($uploads_dir."/".$job_video_media_url);
					$org_Video_filesize - $output_video_filesize;
															
					$calculate_filesize = round((($org_Video_filesize - $output_video_filesize)/$org_Video_filesize)*100);												
					
					if($calculate_filesize>20){						
						$this->session->set_flashdata('fail','Video still is in embeding the Subtitles'); //failed message for subtitle update the status
							redirect(base_url().'subtitles/view');						
					}else{	
						
						/* download video with SRT file start here */				
						header('Content-Description: File Transfer');
						header('Content-Type: application/octet-stream');
						header('Content-Disposition: attachment; filename='.basename($output_srt_mp4_file));
						header('Expires: 0');
						header('Cache-Control: must-revalidate');
						header('Pragma: public');
						header('Content-Length: ' . filesize($output_srt_mp4_file));
						ob_clean();
						flush();
						readfile($output_srt_mp4_file);
						exit;					
						/* download video with SRT file start here */
					}
				
				}else{
					
					$uploads_dir = 'assets/uploadfiles';
					$source_mp4_file = $uploads_dir."/".$job_video_media_url;
					$srt_file 	= $uploads_dir."/".$job_name.".srt";
					$output_srt_mp4_file = $uploads_dir."/".$job_name."_output.mp4";								
					
					/* ffmpeg SRT file embed into Video */
					 $ffmpeg_srt_cmd ='ffmpeg -i '.$source_mp4_file.' -acodec aac -strict experimental -vf "subtitles='.$srt_file.':force_style=\'FontName=Arial,fontsize=14,BorderStyle=3,OutlineColour=&H80000000\'"  '.$output_srt_mp4_file;
					 				
					exec($ffmpeg_srt_cmd);
					/* ffmpeg SRT file embed into Video */
					sleep(60);
					
					if(file_exists($output_srt_mp4_file)){
					
						$output_video_filesize =  filesize($output_srt_mp4_file);
						$org_Video_filesize = filesize($uploads_dir.$job_video_media_url);
						$org_Video_filesize - $output_video_filesize;
															
						$calculate_filesize = round((($org_Video_filesize - $output_video_filesize)/$org_Video_filesize)*100);												
					
					if($calculate_filesize>20){						
						$this->session->set_flashdata('fail','Video still is in embeding the Subtitles'); //failed message for subtitle update the status
							redirect(base_url().'subtitles/view');						
					}else{
				
						/* download video with SRT file start here */				
							header('Content-Description: File Transfer');
							header('Content-Type: application/octet-stream');
							header('Content-Disposition: attachment; filename='.basename($output_srt_mp4_file));
							header('Expires: 0');
							header('Cache-Control: must-revalidate');
							header('Pragma: public');
							header('Content-Length: ' . filesize($output_srt_mp4_file));
							ob_clean();
							flush();
							readfile($output_srt_mp4_file);
							exit;
							/* download video with SRT file start here */
					 }	
				  }
				}				
			}
			
			
		}		
	}
	/* Getting Job details of AWS Transcribe */
	
	
	/* Delete Job details of AWS Transcribe */
	public function delete_videotext_video($arg1){			
						
		$user_role = $this->current_user->role_name;
		if($user_role == 'Admin' || $user_role == 'Tclient' || $user_role == 'Tclientdoc'|| $user_role == 'brightcove'){			
			$JobId = $arg1;
			$arr_aws_transcribe_values = array($JobId);
			$arr_aws_transcribe_results = $this->videototextModel->awstranscribelisting_id($arr_aws_transcribe_values);
			
										
			if(empty($arr_aws_transcribe_results)){				
				print 'No Data existed in the Transflow';				
			}else{
								
				$job_name = $arr_aws_transcribe_results[0]->job_name;
				$job_id = $arr_aws_transcribe_results[0]->job_id;
				$job_video_media_url = $arr_aws_transcribe_results[0]->job_video_media_url;
				
				$arr_resp_api = $this->videototextModel->get_delete_jobdetails_awstranscribe($job_name,$job_id,$job_video_media_url);					
				if(strtolower($arr_resp_api) =="success"){				
					$this->session->set_flashdata('success','Successfully added'); //sucesss message for subtitle update the status
					redirect(base_url().'videotext/view');									
				}elseif($arr_resp_api =="waiting"){										
					$this->session->set_flashdata('fail','Can not delete this Video.Please contact site Administrator...'); //failed message for subtitle update the status
					redirect(base_url().'videotext/view'); 
				}								
			}
			
		}
	}
	/* Delete Job details of AWS Transcribe */	
		
	public function edit($id){	
		$kptaemtranslator = new kptAEMtranslator();
		
		$videototext= VideototextModel::where('job_id',$id)->first();
		$sourcelanguage  = $videototext->job_source_language;
		$sourcelanguage = 'en';
		$targetlanguages = $videototext->target_language;
		$domain='1';
		$job_id=$id;
		
		if($sourcelanguage == $targetlanguages){
		
			Session()->flash('error_message', 'Source & Target Languages should not be same.');
			return redirect()->route('admin.videototext.index');
		}
		$path=base_path()."/public/storage/videototext/";	
		$translation_csv_file = $path.$videototext->job_name.'.srt';
	if(file_exists("$translation_csv_file")){
			define('SRT_STATE_SUBNUMBER', 0);
			define('SRT_STATE_TIME',      1);
			define('SRT_STATE_TEXT',      2);
			define('SRT_STATE_BLANK',     3);
			$lines   = file($translation_csv_file);
			$subs    = array();
			$state   = SRT_STATE_SUBNUMBER;
			$subNum  = 0;
			$subText = '';
			$subTime = '';
			foreach($lines as $line) {
			    switch($state) {
			        case SRT_STATE_SUBNUMBER:
			            $subNum = trim($line);
			            $state  = SRT_STATE_TIME;
			            break;

			        case SRT_STATE_TIME:
			            $subTime = trim($line);
			            $state   = SRT_STATE_TEXT;
			            break;

			        case SRT_STATE_TEXT:
			            if (trim($line) == '') {
			                //$sub = new stdClass;
			                $sub['number'] = $subNum;
			                list($sub['startTime'], $sub['stopTime']) = explode(' --> ', $subTime);
			                $sub['text']  = $subText;
			                $subText     = '';
			                $state       = SRT_STATE_SUBNUMBER;

			                $subs[]      = $sub;
			            } else {
			                $subText .= $line;
			            }
			            break;
			    }
			}

			if ($state == SRT_STATE_TEXT) {
			    $sub->text = $subText;
			    $subs[] = $sub;
			}
			$arr_csv_values=$subs;

			$translation_csv_file1 = $path.$videototext->job_name.'-'.$videototext->target_language.'.srt';
			$lines1   = file($translation_csv_file1);
			$subs1    = array();
			$state1   = SRT_STATE_SUBNUMBER;
			$subNum1  = 0;
			$subText1 = '';
			$subTime1 = '';
			foreach($lines1 as $line) {
			    switch($state1) {
			        case SRT_STATE_SUBNUMBER:
			            $subNum1 = trim($line);
			            $state1  = SRT_STATE_TIME;
			            break;

			        case SRT_STATE_TIME:
			            $subTime1 = trim($line);
			            $state1   = SRT_STATE_TEXT;
			            break;

			        case SRT_STATE_TEXT:
			            if (trim($line) == '') {
			                //$sub = new stdClass;
			                $sub1['number'] = $subNum1;
			                list($sub1['startTime'], $sub1['stopTime']) = explode(' --> ', $subTime);
			                $sub1['text']  = $subText1;
			                $subText1     = '';
			                $state1       = SRT_STATE_SUBNUMBER;

			                $subs1[]      = $sub1;
			            } else {
			                $subText1 .= $line;
			            }
			            break;
			    }
			}

			if ($state1 == SRT_STATE_TEXT) {
			    $sub1->text = $subText1;
			    $subs1[] = $sub1;
			}
			$arr_csv_values_target=$subs1;

			$file_type='srt';
			return view('admin.videototext.translate',compact('arr_csv_values','arr_csv_values_target','sourcelanguage','targetlanguages','kptaemtranslator','domain','file_type','job_id','videototext'));
        }else{
			Session()->flash('error_message', 'Missing SRT file');
			return redirect()->route('admin.videototext.index');
			
		}
				
	}
	public function srtsegmentationformsubmit(Request $request){
		$userid=Auth::user()->id;
		$source_text = $request->input('source_text');
		$target_text = $request->input('target_text');
		$translated_flag = $request->input('translated_flag');
		$domain_id = $request->input('domain_id');
		$job_id = $request->input('job_id');
		$source_language = $request->input('hid_source_language');
		$target_language = $request->input('hid_target_language');


		$videototext= VideototextModel::where('job_id',$job_id)->first();
		$sourcelanguage  = $videototext->job_source_language;
		$sourcelanguage = 'en';
		$targetlanguages = $videototext->target_language;
		$domain='1';
		
		if($sourcelanguage == $targetlanguages){
		
			Session()->flash('error_message', 'Source & Target Languages should not be same.');
			return redirect()->route('admin.videototext.index');
		}
		$path=base_path()."/public/storage/videototext/";	
		$translation_csv_file = $path.$videototext->job_name.'-'.$videototext->target_language.'.srt';
		
	if(file_exists("$translation_csv_file")){
			define('SRT_STATE_SUBNUMBER', 0);
			define('SRT_STATE_TIME',      1);
			define('SRT_STATE_TEXT',      2);
			define('SRT_STATE_BLANK',     3);
			$lines   = file($translation_csv_file);
			$subs    = array();
			$state   = SRT_STATE_SUBNUMBER;
			$subNum  = 0;
			$subText = '';
			$subTime = '';
			foreach($lines as $line) {
			    switch($state) {
			        case SRT_STATE_SUBNUMBER:
			            $subNum = trim($line);
			            $state  = SRT_STATE_TIME;
			            break;

			        case SRT_STATE_TIME:
			            $subTime = trim($line);
			            $state   = SRT_STATE_TEXT;
			            break;

			        case SRT_STATE_TEXT:
			            if (trim($line) == '') {
			                //$sub = new stdClass;
			                $sub['number'] = $subNum;
			                list($sub['startTime'], $sub['stopTime']) = explode(' --> ', $subTime);
			                $sub['text']  = $subText;
			                $subText     = '';
			                $state       = SRT_STATE_SUBNUMBER;

			                $subs[]      = $sub;
			            } else {
			                $subText .= $line;
			            }
			            break;
			    }
			}

			if ($state == SRT_STATE_TEXT) {
			    $sub->text = $subText;
			    $subs[] = $sub;
			}
			$arr_csv_values=$subs;
			$file_type='srt';
        }else{
			Session()->flash('error_message', 'Missing SRT file');
			return redirect()->route('admin.videototext.index');
			
		}
		$filename = $path.$videototext->job_name.'-'.$videototext->target_language.'-new.srt';
		$srtfilehandler = fopen($filename, 'w');
		$i=0;
		foreach($arr_csv_values as $srtdatavalues){
		$number = $srtdatavalues["number"]."\n";
		$srttime = $srtdatavalues["startTime"].' --> '.$srtdatavalues["stopTime"]."\n";
		$targettext = $target_text[$i]."\n\n";

		fwrite($srtfilehandler, $number);
		fwrite($srtfilehandler, $srttime);
		fwrite($srtfilehandler, $targettext);
		$i++;
		}
		fclose($srtfilehandler);
		
		
		
		if(strtolower($videototext->job_media_format) == "mp4"){		
					if(file_exists($filename)){
							
							$path=base_path()."/public/storage/videototext";
							$job_video_media_url = $videototext->job_video_media_url;
							$job_name = $videototext->job_name;
							
							$source_mp4_file = $path."/".$job_video_media_url;
							$srt_file 	= $filename;
							$output_srt_mp4_file = $path."/".$job_name."_output.mp4";
							$arr_languages_list = array("ko","te","hi","th","en");
							$target_language  = $videototext->target_language;
							
							if(in_array($target_language,$arr_languages_list)){							
									
									
								$srt_file_utf8 	= $filename;
								$srt_file 	= $path.$job_name."-".$target_language.".srt";
								
								$convert_srt_ffmpeg_cmd ="ffmpeg -sub_charenc UTF-8 -i $srt_file_utf8 $srt_file";
								exec($convert_srt_ffmpeg_cmd);
								
								$ass_file = $path.$job_name."-".$target_language.".ass";
								$convert_ass_ffmpeg_cmd ="ffmpeg -i $srt_file $ass_file";
								exec($convert_ass_ffmpeg_cmd);								
																
								/* ffmpeg SRT file embed into Video */
								$ffmpeg_srt_cmd ='ffmpeg -i '.$source_mp4_file.' -acodec aac -c:v libx264 -crf 18  -vf  "ass='.$ass_file.':shaping=complex"  '.$output_srt_mp4_file;									
								exec($ffmpeg_srt_cmd);
								/* ffmpeg SRT file embed into Video */	
								
								
							}elseif($target_language !="en"){						
								
								
								$srt_file_utf8 	= $filename;
								$srt_file 	= $path.$job_name."-".$target_language.".srt";
								
								$convert_srt_ffmpeg_cmd ="ffmpeg -sub_charenc ISO-8859-1 -i $srt_file_utf8 $srt_file";
								exec($convert_srt_ffmpeg_cmd);
								
								/* ffmpeg SRT file embed into Video */
								 $ffmpeg_srt_cmd ='ffmpeg -i '.$source_mp4_file.' -acodec aac -strict experimental -vf "subtitles='.$srt_file.':force_style=\'FontName=Arial,fontsize=14,BorderStyle=3,OutlineColour=&H80000000\'"  '.$output_srt_mp4_file." 2>&1 ";	
								
								exec($ffmpeg_srt_cmd);
								//exit;
								/* ffmpeg SRT file embed into Video */								
								
							}
			
				}
		}
		
		
		Session()->flash('message', 'Transcription completed successfully.');
		return redirect()->route('admin.videototext.index');
		exit;
	}
}
        

