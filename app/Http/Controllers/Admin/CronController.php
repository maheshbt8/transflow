<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\models\Cron_model;
use App;

class CronController extends Controller
{
    
   
	
	public function videototext(){
		
		$arr_aws_transcribe_results = Cron_model::video_subtitles_embed();
		if(!empty($arr_aws_transcribe_results)){

			$s3 = App::make('aws')->createClient('s3');
			$awsTranscribeClient = App::make('aws')->createClient('TranscribeService');
		
					foreach($arr_aws_transcribe_results as $row){
						
						$job_id				 = $row->job_id;
						$job_name			 = $row->job_name;
						$job_video_media_url = $row->job_video_media_url;
						$job_audio_media_url = $row->job_audio_media_url;
						$srt_flag_status	 = $row->srt_flag_status;
						$video_flag_status	 = $row->video_flag_status;
						$target_language	 = $row->target_language;


						if($srt_flag_status ==0){
							
							$aws_transcribe_get_job_result = $awsTranscribeClient->getTranscriptionJob([
															'TranscriptionJobName' => $job_name, // REQUIRED
															]);
															
							/* AWS Transcribe getting the job details start here */			

						$TranscriptionJobName 		= $aws_transcribe_get_job_result['TranscriptionJob']['TranscriptionJobName'];
						$TranscriptionJobStatus		= $aws_transcribe_get_job_result['TranscriptionJob']['TranscriptionJobStatus'];
							if($TranscriptionJobStatus =="COMPLETED"){
								if($TranscriptionJobStatus == "IN_PROGRESS")
											$TranscriptionJobStatus ="Inprogress";
									elseif($TranscriptionJobStatus == "COMPLETED")
											$TranscriptionJobStatus = "Complete";
									elseif($TranscriptionJobStatus == "FAILED")
											$TranscriptionJobStatus = "Failed";
									else
										$TranscriptionJobStatus ="Inprogress";
									
							$Transcript = $job_name.".json";
							$targetBucket="devtransflow";
							$path=base_path()."/public/storage/videototext/";
									
									
							/* AWS S3 cmd start here */
							$s3_source_file_json = $path.$job_name.".json";
							$copy_output = $s3->getObject([
							'Bucket'     => $targetBucket,
							'Key'        => $Transcript,
							'SaveAs' => $s3_source_file_json,
							]);
							
							
							/* node convert SRT file */
								$uploads_dir 	= '/var/www/html/assets/uploadfiles';						
								$json_output_file = $path."/".$job_name.".json";	
								$srt_output_file1  = $path."/".$job_name."1.srt";				
								$node_cmd = "node /var/www/html/newtransflow/subtitlescript/srtconvertnode/index.js $json_output_file $srt_output_file1 2>&1 ";
								exec($node_cmd);
								

								$srt_output_file  = $path."/".$job_name.".srt";				
								$ffmpeg_srt_utf8 ="ffmpeg -sub_charenc ISO-8859-1 -i $srt_output_file1 $srt_output_file 2>&1 ";
								exec($ffmpeg_srt_utf8);
								
								
								$srt_flag_status=1;
								$job_json_file = $job_name.".json";
								$arr_transcribe_values = array($job_id,$TranscriptionJobStatus,$job_json_file,$srt_flag_status,'@output');
								
								$arr_aws_transcribe_results1 = Cron_model::srt_flag_update_video($arr_transcribe_values);
								
								
								 
							/* node convert SRT file */	
							
							
							
							/* AWS S3 cmd end here */
							}
						
						}elseif($video_flag_status ==0){

							$path=base_path()."/public/storage/videototext/";							
							
							$source_mp4_file = $path."/".$job_video_media_url;
							$srt_file 	= $path."/".$job_name.".srt";
							$output_srt_mp4_file = $path."/".$job_name."_output.mp4";
							$arr_languages_list = array("ko","te","hi","th","en");
							
							if(in_array($target_language,$arr_languages_list)){
								
									Cron_model::gtranslate_convert_subtitle($job_name,$target_language);
									
								$srt_file_utf8 	= $path.$job_name."-".$target_language."1.srt";
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
								
								Cron_model::gtranslate_convert_subtitle($job_name,$target_language);
								
								$srt_file_utf8 	= $path.$job_name."-".$target_language."1.srt";
								$srt_file 	= $path.$job_name."-".$target_language.".srt";
								
								$convert_srt_ffmpeg_cmd ="ffmpeg -sub_charenc ISO-8859-1 -i $srt_file_utf8 $srt_file";
								exec($convert_srt_ffmpeg_cmd);
								
								/* ffmpeg SRT file embed into Video */
								$ffmpeg_srt_cmd ='ffmpeg -i '.$source_mp4_file.' -acodec aac -strict experimental -vf "subtitles='.$srt_file.':force_style=\'FontName=Arial,fontsize=14,BorderStyle=3,OutlineColour=&H80000000\'"  '.$output_srt_mp4_file;									
								exec($ffmpeg_srt_cmd);
								/* ffmpeg SRT file embed into Video */								
								
							}
							
							$video_flag_status=1;
							$arr_transcribe_values = array($job_id,$video_flag_status,'@output');
														
							$arr_aws_transcribe_results1 = Cron_model::video_flag_update_video($arr_transcribe_values);
							
						}		
				}
			}	
		
	}
    

   
   

    

   




  

  
    
    
}
