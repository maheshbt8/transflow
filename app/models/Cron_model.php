<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cron_model extends Model
{

	
	function __construct() {
		parent::__construct();
		
	}//end __construct() 


	static function video_subtitles_embed(){
		
		return DB::select('call xp_loc_select_aws_transcribe_status_flag()');
	}
	
	static function srt_flag_update_video($arr_transcribe_values){
		
		return DB::select('call xp_loc_aws_transcribe_status_update(?,?,?,?,?)',$arr_transcribe_values);
	}
	
	static function video_flag_update_video($arr_transcribe_values){
			
		return DB::select('call xp_loc_aws_transcribe_video_flag_status_update(?,?,?)',$arr_transcribe_values);
		
	}
	
	
	
	/* Google Translate Convert Subtitles */
	static function gtranslate_convert_subtitle($jobname,$target_language){		
			
		   $path=base_path()."/public/storage/videototext/";		   
		   $job_name = $jobname;
		   $srt_source_file = $path.$job_name.".srt";

		   define('SRT_STATE_SUBNUMBER', 0);
			define('SRT_STATE_TIME',      1);
			define('SRT_STATE_TEXT',      2);
			define('SRT_STATE_BLANK',     3);	
						

			$lines   = file($srt_source_file);			
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
						$sub = new \stdClass();
						$sub->number 	= $subNum;
						$sub->srttime 	= $subTime;
						$sub->text   	= $subText;
						$subText     	= '';
						$state       	= SRT_STATE_SUBNUMBER;

						$subs[]      = $sub;
					} else {
						$subText .= $line;
					}
					break;
			}
		}
		
		if ($state == SRT_STATE_TEXT) {
			// if file was missing the trailing newlines, we'll be in this
			// state here.  Append the last read text and add the last sub.
			$sub->text = $subText;
			$subs[] = $sub;
		}
		
		
		/* SRT file converted as rows */
		$arr_translated_data = array();		
		$total_rows = count($subs);	
		$arr_translated_data1 = array();		
		$arr_str_values = array_chunk($subs,100,true);
		
		
		$string_source_text="";
		$p=0;
		foreach($arr_str_values as $rows){
			///$string_source_text="";
			//$target_language  = 	"ko";			
			foreach($rows as $row){								
				$number 		  = 	$row->number;				
				$srttime		  = 	$row->srttime;			
				$source_text 	  = 	$row->text;				
				
				$string_source_text .= $source_text."787878";								
				$arr_translated_data[] = array("number"=>$number,"srttime"=>$srttime,"TargetText"=>$source_text);	
			}
			
			
			$gtranslate_response = Cron_model::gtranslate_curl_request($string_source_text,$target_language);
			$arr_gtranslate_res = explode("787878",$gtranslate_response);				
			foreach($arr_gtranslate_res as $gtelvalues){
					if($gtelvalues !=""){
						$arr_translated_data1[]= $gtelvalues;
					}
				}	
			$string_source_text="";						
		}
		/* SRT file converted as rows */
		
		
		$arr_org_srt_data = array();
		$k=0;
		foreach($arr_translated_data as $srtdata_rows){			
			$number 		= $srtdata_rows['number'];
			$srttime 		= $srtdata_rows['srttime'];
			$targettext		= $arr_translated_data1[$k];
			
			$arr_org_srt_data[] = array('number'=>$number,'srttime'=>$srttime,'targettext'=>$targettext);			
			$k++;			
		}

		$path=base_path()."/public/storage/videototext/";
		$filename= $path.$job_name."-".$target_language.".srt";	
		$srtfilehandler = fopen($filename, 'w'); 
			foreach($arr_org_srt_data as $srtdatavalues){
				$number 	= $srtdatavalues["number"]."\n";
				$srttime 	= $srtdatavalues["srttime"]."\n";
				$targettext = $srtdatavalues["targettext"]."\n";
				
				fwrite($srtfilehandler, $number);
				fwrite($srtfilehandler, $srttime);
				fwrite($srtfilehandler, $targettext);				
			}	
		fclose($srtfilehandler);	
	}
	/* Google Translate Convert Subtitles */
	
	
	
	 /* function for Google Translate request */
	static function gtranslate_curl_request($target_text,$target_lang){
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
