<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\loc_languages;
use Illuminate\Http\Request;
use Auth;
use App\models\speechtospeech;


class speechtospeechController extends Controller
{
    public function index(){
        if (! checkpermission('speech_to_speech_tool')) {
            return abort(401);}
$data=speechtospeech::get();


        return view('admin.speechtospeech.index',compact('data'));
    }
    public function create(){

        if (! checkpermission('speech_to_speech_tool','add')) {
            return abort(401);
        }

        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status','ACTIVE')->get();
    //    print_r($loc_languages);
       
        return view('admin.speechtospeech.create',compact('loc_languages'));
    }
    public function store(){
        $user_role = Auth::user()->roles[0]->name;
        $userid=Auth::user()->id;
		

			
							
				$supported_image = array('mp4');
				$video_language	= $_POST['source_language'];								
				$file_name 		= $_FILES['source_file']['name'];     //file name
                $act_filename=$file_name;
				$file_temp 		= $_FILES['source_file']['tmp_name']; //file temp 
				$subtitle_language = $_POST['destination_language'];

				/*Activity log values starts here*/	
			
				
				
				/*Activity log values ends here*/ 		
					
							
				
				if($video_language =="")
					$video_language="en-US";
                   // $file_name 		= $_FILES['source_file']['name']; 
				$ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
				$uploads_dir = '/public/storage/speechtospeech/';
				if (in_array($ext, $supported_image)) {
                            		$upload_filename = rand(1000000000,5000000000);
									$upload_filename_extension = $upload_filename.'.'.$ext;									
									if(!file_exists("$uploads_dir/$upload_filename_extension")){
                                        $path=base_path()."/public/storage/speechtospeech/";
                                       // $file_name->move($path,$path.$upload_filename_extension);
                                       					
										//$file->move($path,$upload_filename_extension);		
										move_uploaded_file($file_temp, $path.$upload_filename_extension);
                                         $full_path_video = "/public/storage/speechtospeech".$upload_filename_extension;      
									$job_status="in-Progress";	
									$job_org_filename = $file_name;			
                                    $get_uploaded_date = date("Y-m-d H:i:s");						
									$arr_video_details = array("file_name"=>$upload_filename,"org_filename"=>$job_org_filename,"source_file_name"=>$upload_filename_extension);
									
									/*Activity log values starts here*/	
                                    $arr_data_save= new speechtospeech;
                                    // $arr_data_save->job_name=$jobname;
                                    $arr_data_save->job_source_language=$video_language;
                                     $arr_data_save->job_media_name=$upload_filename;
                                    
                                    $arr_data_save->job_video_media_url=$upload_filename_extension;
                                    $arr_data_save->job_status=$job_status;
                                    $arr_data_save->job_org_filename=$act_filename;									
                                    
                                    $arr_data_save->job_created_at=$get_uploaded_date;
                                    $arr_data_save->job_target_language=$subtitle_language;
                                    $arr_data_save->job_user_id=$userid;
                                   
                                    $result= $arr_data_save->save();
                                    Session()->flash('message', 'upload file  successfully added');
                                    return redirect()->route("admin.speechtospeech.index"); 
									/*Activity log values ends here*/ 
                 }

            
           }


        

        }}
