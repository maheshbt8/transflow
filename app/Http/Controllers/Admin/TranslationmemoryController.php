<?php

namespace App\Http\Controllers\Admin;
use App\User;
use Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\loc_languages;
use Storage;
use App\Translationmodel;
use File;

class TranslationmemoryController extends Controller
{
	public function index(Request $request)
	{
        if (! checkpermission('translation_memory')) {
            return abort(401);
        }

		if(isset($_GET["source_language"]) && $_GET["source_language"] !=""){
			$source_lang = $_GET["source_language"];
		}else{
			$source_lang = '';
		}
				
		if(isset($_GET["target_language"]) && $_GET["target_language"] !=""){
			$target_lang = $_GET["target_language"];
		}else{
			$target_lang = '';
		}
					
		$translation_memory_data = array();
			if($source_lang !="" && $target_lang !=""){
				$table_name = strtolower("tm_".$source_lang."_".$target_lang);
				$result_table = Translationmodel::checktableavailability($table_name);
				if($result_table){		
					$translation_memory_data = Translationmodel::view_translationmemory_webapi($table_name);				
					Session()->flash('message', 'Preparing Data Please Wait');
				}else{
					Session()->flash('error_message', 'No data found');
				}							 
			}
		$loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status','ACTIVE')->get();
		return view('admin.translationmemory.index',compact('loc_languages','source_lang','target_lang','translation_memory_data'))->with(['page_title'=>'Translation Memory']);;
	}
	public function create()
	{
        if (! checkpermission('translation_memory','add')) {
            return abort(401);
        }
        ini_set('post_max_size', '40M');
		ini_set('upload_max_filesize', '40M');
		ini_set('max_execution_time', '600');
		$loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status','ACTIVE')->get();
		$domains= DB::connection('mysql2')->table('tm_domains')->select('domain_name','domain_id')->get();
		return view('admin.translationmemory.create',compact('loc_languages','domains'));
	}
	public function store(Request $request)
    {
        if (! checkpermission('translation_memory')) {
            return abort(401);
        }
        ini_set('post_max_size', '40M');
		ini_set('upload_max_filesize', '40M');
		ini_set('max_execution_time', '600');
        $userid=Auth::user()->id;
        $this->validate($request, ['source_language' => ['required']]);
        $this->validate($request, ['domain' => ['required']]);
        $this->validate($request, ['destination_language' => ['required']]);
        $this->validate($request, ['source_file' => ['required']]);
        $source_file            = $_FILES['source_file'];
        
        $source_language = strtolower($request->input('source_language'));
		$target_language = strtolower($request->input('destination_language'));
		$domain_id = $request->input('domain');
        $file = $request->file('source_file');
	
        if($file != "") {
        	$file_name = $request->file('source_file')->getClientOriginalName(); 
            $source_file = $request->file('source_file')->getRealPath();
            $source_file_extension = strtolower($request->file('source_file')->getClientOriginalExtension());
            $supported_image = array('tmx');
            if (in_array($source_file_extension, $supported_image)) {
                $path=base_path()."/public/storage/translationeditor/";
                if(!File::isDirectory($path)){
                    $some=  File::makeDirectory($path, 0777, true);
                }
               // $path_save="public/request";
                $source_file_path=$upload_file_name = time().'_'.$file_name;
                // if(!file_exists("$path/$upload_file_name")){
                //     $file->move($path,$upload_file_name);
                // }
				$file = $request->file('source_file');
				$filePath = 'translationeditor/' . $upload_file_name;
               // $res = simplexml_load_file($path."/".$upload_file_name);
				$rr=Storage::disk('s3')->put($filePath, file_get_contents($file));
				$res = simplexml_load_file(env('AWS_CDN_URL') .'/'. $filePath);
				// echo "<pre>";
				// print_r($res);die;
                $arr_tmx_data = $res->body;
			//	print_r($arr_tmx_data);die;
				
				$p=0;
				$arr_tmx_data1 = array("source_languge"=>$source_language,"target_language"=>$target_language);
				$source_table_name = strtolower("tm_".$source_language."_".$target_language);
				$target_table_name = strtolower("tm_".$target_language."_".$source_language);
				Translationmodel::create_table_request($source_table_name);
				Translationmodel::create_table_request($target_table_name);
				foreach($arr_tmx_data as $rows_data){					
					foreach($rows_data->tu as $row_data){
						$source_text =  addslashes(htmlentities($row_data->tuv[0]->seg, ENT_QUOTES, "UTF-8"));
						$target_text =  addslashes(htmlentities($row_data->tuv[1]->seg, ENT_QUOTES, "UTF-8"));

						$source_count=Translationmodel::checksourcetextavailability($source_table_name,$source_text);
						if($source_count == 0){
							$arr_source_tm_data = array("source_lang"=>$source_language,
								"source_text"=>$source_text,
								"tareget_lang"=>$target_language,
								"target_text"=>$target_text,
								"domain_id"=>$domain_id,
								"created_userid"=>$userid,
								"updated_userid"=>$userid);										
							$response = Translationmodel::add_update_translationmemory_webapi($arr_source_tm_data);
							$response ="";
						}
						
						$source_count=Translationmodel::checksourcetextavailability($target_table_name,$target_text);
						if($source_count == 0){
						$arr_target_tm_data = array("source_lang"=>$target_language,
								"source_text"=>$target_text,
								"tareget_lang"=>$source_language,
								"target_text"=>$source_text,
								"domain_id"=>$domain_id,
								"created_userid"=>$userid,
								"updated_userid"=>$userid);
											
							$response = Translationmodel::add_update_translationmemory_webapi($arr_target_tm_data);
							$response =""; 
						}

					}
				}
				Session()->flash('message', "Uploaded Successfully.");
            }else{
            		Session()->flash('error_message', 'No Support to uploaded file.');
            	}
        }
        return redirect()->route('admin.translationmemory.create');
    }

	public function distroy_data(){
		$id = $_POST["id"];
		if(isset($_POST["source_language"]) && $_POST["source_language"] !=""){
			$source_lang = $_POST["source_language"];
		}else{
			$source_lang = '';
		}
				
		if(isset($_POST["target_language"]) && $_POST["target_language"] !=""){
			$target_lang = $_POST["target_language"];
		}else{
			$target_lang = '';
		}
					
		if($source_lang !="" && $target_lang !=""){
			$table_name = strtolower("tm_".$source_lang."_".$target_lang);
			$result_table = Translationmodel::checktableavailability($table_name);
			if($result_table){		
				DB::connection('mysql2')->table($table_name)->where('sid',$id)->delete();
				return 1;
			}						 
		}
		return 0;
	}

	public function update_data(){

		$id = $_POST["id"];
		$target_text = $_POST["target_text"];
		if(isset($_POST["source_language"]) && $_POST["source_language"] !=""){
			$source_lang = $_POST["source_language"];
		}else{
			$source_lang = '';
		}
				
		if(isset($_POST["target_language"]) && $_POST["target_language"] !=""){
			$target_lang = $_POST["target_language"];
		}else{
			$target_lang = '';
		}
					
		if($source_lang !="" && $target_lang !=""){
			$table_name = strtolower("tm_".$source_lang."_".$target_lang);
			$result_table = Translationmodel::checktableavailability($table_name);
			if($result_table){		
				DB::connection('mysql2')->table($table_name)->where('sid',$id)->update(['target_text'=>$target_text]);
				return 1;
			}						 
		}
		return 0;
	}

	public function addtms(){

		if(isset($_POST["source_language"]) && $_POST["source_language"] !=""){
			$source_lang = $_GET["source_language"];
		}else{
			$source_lang = '';
		}
				
		if(isset($_GET["target_language"]) && $_GET["target_language"] !=""){
			$target_lang = $_GET["target_language"];
		}else{
			$target_lang = '';
		}
					
		// $translation_memory_data = array();
		// 	if($source_lang !="" && $target_lang !=""){
		// 		$table_name = strtolower("tm_".$source_lang."_".$target_lang);
		// 		$result_table = Translationmodel::checktableavailability($table_name);
		// 		if($result_table){		
		// 			$translation_memory_data = Translationmodel::view_translationmemory_webapi($table_name);				
		// 			Session()->flash('message', 'Preparing Data Please Wait');
		// 		}else{
		// 			Session()->flash('error_message', 'No data found');
		// 		}							 
		// 	}
		$domains= DB::connection('mysql2')->table('tm_domains')->select('domain_name','domain_id')->get();
		$loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status','ACTIVE')->get();
		return view('admin.translationmemory.tms',compact('loc_languages','source_lang','target_lang','domains'));
	}



	public function store_tms(Request $request){

		$this->validate($request, ['source_language' => ['required']]);
        $this->validate($request, ['target_language' => ['required']]);
		$this->validate($request, ['source_text' => ['required']]);
		$this->validate($request, ['target_text' => ['required']]);
		// $this->validate($request, ['domain_id' => ['required']]);


		$userid=Auth::user()->id;
		$target_text = $_POST["target_text"];
		$domain_id = $_POST["domain_id"];
		$source_lang = $_POST["source_language"];
		$source_text = $_POST["source_text"];
		$target_lang = $_POST["target_language"];
		if(isset($_POST["source_language"]) && $_POST["source_language"] !=""){
			$source_lang = $_POST["source_language"];
		}else{
			$source_lang = '';
		}
				
		if(isset($_POST["target_language"]) && $_POST["target_language"] !=""){
			$target_lang = $_POST["target_language"];
		}else{
			$target_lang = '';
		}
					
		if($source_lang !="" && $target_lang !=""){
			//$table_name = strtolower("tm_".$source_lang."_".$target_lang);



			$source_table_name = strtolower("tm_".$source_lang."_".$target_lang);
			$target_table_name = strtolower("tm_".$target_lang."_".$source_lang);
			Translationmodel::create_table_request($source_table_name);
			Translationmodel::create_table_request($target_table_name);

			$source_text =  addslashes(htmlentities($source_text, ENT_QUOTES, "UTF-8"));
			$target_text =  addslashes(htmlentities($target_text, ENT_QUOTES, "UTF-8"));

			$source_count=Translationmodel::checksourcetextavailability($source_table_name,$source_text);
			if($source_count == 0){
				$arr_source_tm_data = array("source_lang"=>$source_lang,
					"source_text"=>$source_text,
					"tareget_lang"=>$target_lang,
					"target_text"=>$target_text,
					"domain_id"=>$domain_id,
					"created_userid"=>$userid,
					"updated_userid"=>$userid);										
				$response = Translationmodel::add_update_translationmemory_webapi($arr_source_tm_data);
			}
			
			$source_count=Translationmodel::checksourcetextavailability($target_table_name,$target_text);
			if($source_count == 0){
			$arr_target_tm_data = array("source_lang"=>$target_lang,
					"source_text"=>$target_text,
					"tareget_lang"=>$source_lang,
					"target_text"=>$source_text,
					"domain_id"=>$domain_id,
					"created_userid"=>$userid,
					"updated_userid"=>$userid);
								
				$response = Translationmodel::add_update_translationmemory_webapi($arr_target_tm_data);
			}
		
			return 1;




			// $result_table = Translationmodel::checktableavailability($table_name);
			// if($result_table){		
			// 	DB::connection('mysql2')->table($table_name)->insert(['source_language'=>$source_lang,'target_language'=>$target_lang,'source_text'=>$source_text,'target_text'=>$target_text]);
			// 	return 1;
			// }						 
		}
		return 0;


	}

}