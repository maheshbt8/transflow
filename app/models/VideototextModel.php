<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App;
use Auth;


use Illuminate\Support\Facades\DB;

class VideototextModel extends Model
{
	protected $table='loc_videototext_job';
	public $timestamps	= false;
    public $upload_excel_config = array(
        'upload_path'   =>'/public/storage/videototext/',
        'allowed_types' =>'*',
        'max_size' => 10000
    );
		
	function __construct() {
		parent::__construct();
		
	}//end __construct()        
    
    /* function decryption */
    function decrypt($input){      
        $this->iv = mcrypt_create_iv(32);
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->securekey, base64_decode($input), MCRYPT_MODE_ECB, $this->iv));
    }/* end decryption */
    function awstranscribelistingvideos(){			
		$output = $this->call_sp('xp_loc_select_videotext_job',NULL,NULL,FALSE);
          return $output;
	}	
	/* AWS Transcribe data list */
	
	
	/* AWS Transcribe with id data list */
	function awstranscribelisting_id($arr_values){			
		$output = $this->call_sp('xp_loc_select_videotext_job_id_select',$arr_values,NULL,FALSE);
          return $output;
	}	
	/* AWS Transcribe with id data list */	
	
	
		
	
	/* Upload Brightcove start here */	
	 static function apiuploadvideo($arr_metadata){
					
					/* $uploads_dir = base_path()."/public/storage/videototext/";
					$fileFullName ="testpdf.pdf";
					$s3 = App::make('aws')->createClient('s3');
					
					echo $s3 = App::make('aws')->createClient('s3');
					exit;
					
					
					$s3->putObject(array(
					'Bucket'     => 'devtransflow',
					'Key'        => $fileFullName,
					'SourceFile' => $uploads_dir.$fileFullName,
					));
					exit; */
						
			
			
			$job_name 				= 	$arr_metadata['job_name'];
			$source_file_name 		= 	$arr_metadata['source_file_name'];
			$source_fullpath		= 	$arr_metadata['source_fullpath'];
			$file_extension			=   $arr_metadata['file_extension'];
			$target_language		=   $arr_metadata['target_language'];
			
			$uploads_dir = base_path().'/public/storage/videototext/';
			$source_file_name_mp3 = $uploads_dir."/".$source_file_name.".".$file_extension;
			$s3_source_file_mp3_name = $source_file_name.".".$file_extension;
			
			/*
			$s3_source_file_mp3_name = $source_file_name.".mp3";
			if(strtolower($file_extension) =="mp4"){			
			/* FFMPEG cmd start here 
			$ffmpeg_convert_audio = "ffmpeg -i $source_fullpath  -acodec libmp3lame $source_file_name_mp3";
			$ffmpeg_result = exec($ffmpeg_convert_audio);	
			/* FFMPEG cmd end here 
			}elseif(strtolower($file_extension) == "mp3"){				
				 $s3_source_file_mp3_name = basename($source_fullpath);	
				 $source_file_name_mp3 = $uploads_dir."/".basename($source_fullpath);
			}			
			/* AWS S3 cmd start here */
			
			 $s3_upload_cmd = "AWS_ACCESS_KEY_ID=AKIAYKBVOPKBY7RHJ4KO  AWS_SECRET_ACCESS_KEY=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  aws s3 cp $source_file_name_mp3 s3://devtransflow/$s3_source_file_mp3_name  ";
			$s3_result = exec($s3_upload_cmd);
			/* AWS S3 cmd end here */		

			
						
						
			
			/* AWS Transcribe start here */
			$media_url = '{"MediaFileUri":"s3://devtransflow/'.$s3_source_file_mp3_name.'"}';			
			$aws_transribe_cmd = "AWS_ACCESS_KEY_ID=AKIAYKBVOPKBY7RHJ4KO  AWS_SECRET_ACCESS_KEY=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 aws transcribe --region ap-south-1 start-transcription-job --language-code 'en-US' --media-format '$file_extension' --transcription-job-name '$job_name' --media '$media_url'	--output-bucket-name 'devtransflow'  2>&1";				
			$transcribe_result = exec($aws_transribe_cmd);			
			/* AWS Transcribe end here */
			
				
			
			// AWS Transcribe getting the job details start here */			
			// Require the Composer autoloader.
			//require '/var/www/html/video/aws/aws-autoloader.php';
			$aws_transcribe = App::make('aws')->createClient('TranscribeService');
			
			
			$transcription_name = "Transflow-sample-video";
			$aws_transcribe_get_job_result = $aws_transcribe->getTranscriptionJob([
				'TranscriptionJobName' => $job_name, // REQUIRED
			]);
			/* AWS Transcribe getting the job details start here */	
			
			
			$TranscriptionJobName 		= $aws_transcribe_get_job_result['TranscriptionJob']['TranscriptionJobName'];
			$TranscriptionJobStatus		= $aws_transcribe_get_job_result['TranscriptionJob']['TranscriptionJobStatus'];
			$LanguageCode				= $aws_transcribe_get_job_result['TranscriptionJob']['LanguageCode'];
			$MediaFormat			 	= $aws_transcribe_get_job_result['TranscriptionJob']['MediaFormat'];
			$MediaFileUri				= $aws_transcribe_get_job_result['TranscriptionJob']['Media']['MediaFileUri'];
			$bucketname 				= "devtransflow";
			$CreatedTime				= date("Y-m-d H:i:s");
			$UserId 					= Auth::user()->id;
			
			if($TranscriptionJobStatus == "IN_PROGRESS")
					$TranscriptionJobStatus ="Inprogress";
			elseif($TranscriptionJobStatus == "COMPLETED")
					$TranscriptionJobStatus = "Complete";
			elseif($TranscriptionJobStatus == "FAILED")
					$TranscriptionJobStatus = "Failed";
					
			$mp4_file_name = basename($source_fullpath);
						
			$arr_transcribe_values = array($TranscriptionJobName,$MediaFormat,$bucketname,$mp4_file_name,$MediaFileUri,$TranscriptionJobStatus,$CreatedTime,$UserId,$target_language,'@output');
			if($TranscriptionJobName !=""){
				$result = DB::select('call xp_create_videotext_job(?,?,?,?,?,?,?,?,?,?)',$arr_transcribe_values);
				return $result;
			}else {				
				return 'Failed';
			}			
		
	}
	/* Upload Brightcove end here */
	
	
	
	/* function get aws Transcribe get job details */
	function get_jobdetails_awstranscribe($job_name){
		
			$uploads_dir = 'assets/uploadfiles';
			$srt_file_name = $uploads_dir."/".$job_name.".json";
						
			if(file_exists($srt_file_name)){				
				return 'COMPLETED';				
				
			}else{	
		/* AWS Transcribe getting the job details start here */			
			// Require the Composer autoloader.
			require '/var/www/html/video/aws/aws-autoloader.php';			
			$transcribe = new Aws\TranscribeService\TranscribeServiceClient([
			'region'  => 'ap-south-1',
			'version' => '2017-10-26',
			 'credentials' => [
						'key' => 'AKIAYKBVOPKBY7RHJ4KO',
						'secret' => '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',
				]
			]);			
			
			$aws_transcribe_get_job_result = $transcribe->getTranscriptionJob([
				'TranscriptionJobName' => $job_name, // REQUIRED
			]);
			/* AWS Transcribe getting the job details start here */			
						
			$TranscriptionJobName 		= $aws_transcribe_get_job_result['TranscriptionJob']['TranscriptionJobName'];
			$TranscriptionJobStatus		= $aws_transcribe_get_job_result['TranscriptionJob']['TranscriptionJobStatus'];
			
			if($TranscriptionJobStatus =="COMPLETED"){
			
				$Transcript = "s3://devtransflow/".$TranscriptionJobName.".json";
				/* AWS S3 cmd start here */
				$uploads_dir = 'assets/uploadfiles';
				$s3_source_file_json = $uploads_dir."/".$TranscriptionJobName.".json";
				$s3_upload_cmd = "AWS_ACCESS_KEY_ID=AKIAYKBVOPKBY7RHJ4KO  AWS_SECRET_ACCESS_KEY=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  aws s3 cp $Transcript   $s3_source_file_json ";			
				$s3_result = exec($s3_upload_cmd);
				/* AWS S3 cmd end here */
				
				/* node convert SRT file */
					$uploads_dir 	= '/var/www/html/assets/uploadfiles';			
					
					$json_output_file = $uploads_dir."/".$job_name.".json";	
					$srt_output_file1  = $uploads_dir."/".$job_name."1.srt";				
					$node_cmd = "node /home/ubuntu/subtitlescript/srtconvertnode/index.js $json_output_file $srt_output_file1 ";
					exec($node_cmd);
					 
					 $srt_output_file  = $uploads_dir."/".$job_name.".srt";				
					 $ffmpeg_srt_utf8 ="ffmpeg -sub_charenc ISO-8859-1 -i $srt_output_file1 $srt_output_file";
					 exec($ffmpeg_srt_utf8);
					
				/* node convert SRT file */
			
				return $TranscriptionJobStatus;	
			}else{				
				return "waiting";
			}
		 }		
	}	
	/* function get aws Transcribe get job details */
	
	
	
	
	
	/* function get aws Transcribe get job details */
	function get_jobdetails_awstranscribe_refresh($job_name,$job_id){		
			
		/* AWS Transcribe getting the job details start here */			
			// Require the Composer autoloader.
			require '/var/www/html/video/aws/aws-autoloader.php';
			
			$transcribe = new Aws\TranscribeService\TranscribeServiceClient([
			'region'  => 'ap-south-1',
			'version' => '2017-10-26',
			 'credentials' => [
						'key' => 'AKIAYKBVOPKBY7RHJ4KO',
						'secret' => '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',
				]
			]);			
			
			$aws_transcribe_get_job_result = $transcribe->getTranscriptionJob([
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
				
				
				
				
				$Transcript = "s3://devtransflow/".$TranscriptionJobName.".json";
				/* AWS S3 cmd start here */
				$uploads_dir = 'assets/uploadfiles';
				$s3_source_file_json = $uploads_dir."/".$TranscriptionJobName.".json";
				$s3_upload_cmd = "AWS_ACCESS_KEY_ID=AKIAYKBVOPKBY7RHJ4KO  AWS_SECRET_ACCESS_KEY=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  aws s3 cp $Transcript   $s3_source_file_json ";			
				$s3_result = exec($s3_upload_cmd);
				/* AWS S3 cmd end here */				
						
							
				$job_json_file = $TranscriptionJobName.".json";
				$arr_transcribe_values = array($job_id,$TranscriptionJobStatus,$job_json_file);				
				$result = $this->call_sp('xp_loc_videotext_job_status_update',$arr_transcribe_values,'output',TRUE);
				return $result;	
			}else{
				
				return "waiting";
			}			
		
	}	
	/* function get aws Transcribe get job details */
	
	
	
	/* function get aws Transcribe get job details */
	function get_delete_jobdetails_awstranscribe($job_name,$job_id,$video_url){		
			
		/* AWS Transcribe getting the job details start here */			
			// Require the Composer autoloader.
			require '/var/www/html/video/aws/aws-autoloader.php';
			
			$transcribe = new Aws\TranscribeService\TranscribeServiceClient([
			'region'  => 'ap-south-1',
			'version' => '2017-10-26',
			 'credentials' => [
						'key' => 'AKIAYKBVOPKBY7RHJ4KO',
						'secret' => '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',
				]
			]);			
					
			$uploads_dir = 'assets/uploadfiles';
			$job_video = $uploads_dir."/".$video_url;
			
			if(file_exists($job_video)){				
				$aws_transcribe_get_job_result = $transcribe->deleteTranscriptionJob([
					'TranscriptionJobName' => "$job_name", // REQUIRED
					]); 
					
				$arr_transcribe_values = array("job_id"=>$job_id);
				$result = $this->call_sp('xp_loc_videotext_video_delete',$arr_transcribe_values,'output',TRUE);
				if($result ==1)
					return 'success';
				else
					return 'waiting';
				
			}else{				
				return "waiting";
			}		
	}	
	/* function get aws Transcribe get job details */
	
	
	
	 /* function for Google Translate request */
	function gtranslate_curl_request($target_text,$target_lang){
		if($target_text !=""){
				$target_text = mb_convert_encoding($target_text, "UTF-8");
				$post_data = json_encode(array("text"=>$target_text,"dest_lg"=>$target_lang));				
				$curl = curl_init();
				curl_setopt_array($curl, array(
				CURLOPT_URL => "https://dev.transflowtms.com/translate",
				CURLOPT_RETURNTRANSFER => true,			
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $post_data,
				CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"content-type: application/json"			
				),
				));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);			
			return $response;			  
				
		}
	}
	/* function for Google Translate request */
}