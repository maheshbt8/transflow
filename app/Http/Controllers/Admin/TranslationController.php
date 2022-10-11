<?php

namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use App\loc_languages;
use App\Translationmodel;
use App\kptAEMtranslator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Auth;

//use App\Http\Controllers\AEM\DB;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
//to execute python script
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
//to execute python script
use File;

class TranslationController extends Controller
{
	public function index($type = 'text')
	{
		$blogs = DB::connection('mysql2')->table("loc_lang")->where('lang_status','ACTIVE')->get();

// print_r($blogs);die;
		return view('admin.translation.index',compact('type'));
	}
	public function show($type = 'text')
	{
		return view('admin.translation.index',compact('type'));	
	}

	public function translationtext()
	{
		$loc_languages = loc_languages::orderBy('lang_name', 'ASC')->get();
		return view('admin.translation.text',compact('loc_languages'));	
	}
	public function translationdoc()
	{
		return view('admin.translation.document');	
	}
	public function translationslide()
	{
		return view('admin.translation.slider');	
	}
	public function translationword()
	{
		return view('admin.translation.word2vec');	
	}
	public function curl_translate_language(Request $request)
	{
		$text = $request->input('text');
		$code = $request->input('dest_lg');
		$result=Translationmodel::get_curl_translate_language($code,$text);
		$response = json_decode($result);
		if($response->code >= '200' && $response->code < '300'){
			$res['data']=$response->text[0];
			$res['status']=1;
		}else{
			$res['status']=0;
			$res['message']=$response->message;
		}
		echo json_encode($res);
		exit;
	}
	
	/* Upload CSV Submit */
	public function curl_translate_document(Request $request){	
		$kptaemtranslator = new kptAEMtranslator();
		$validator = $this->validate($request, 
					['translation_file' => ['required']]);	
		$input = $request->input();
				
		$file = $request->file('translation_file');
		$file_name = $request->file('translation_file')->getClientOriginalName(); 
		$translation_file = $request->file('translation_file')->getRealPath();			
		$translation_file_extension = $request->file('translation_file')->getClientOriginalExtension();
		$supported_image = array('xls','xlsx');

        /*if (in_array($translation_file_extension, $supported_image)) {
            $path=base_path()."/public/storage/dell/";
            if(!File::isDirectory($path)){
                $some=  File::makeDirectory($path, 0777, true);
            }
            $path_save="public/dell";
            $translation_file_path=$upload_file_name = time().'_'.$file_name;
            if(!file_exists("$path/$upload_file_name")){
                $file->move($path,$upload_file_name);
            }
            $xml=simplexml_load_file("$path/$upload_file_name");
			print_r($xml);die;
        }*/
			$res['status']=0;
			$res['msg']='File not support this format';
		if(in_array($translation_file_extension, $supported_image)){	
		        
			/**  Create a new Reader of the type defined in $inputFileType  **/
			$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($translation_file);
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
			/**  Set the delimiter to a TAB character  **/
			//$reader->setDelimiter("\t");		
			//$reader->setReadDataOnly(true);
			/**  Load the file to a Spreadsheet Object  **/
			$spreadsheet = $reader->load($translation_file);
			
			/**  Convert Spreadsheet Object to an Array for ease of use  **/
			$arr_csv_values = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);
			//print_r($arr_csv_values);die;
$arr_csv=array_shift($arr_csv_values);
$target_response = array();
$p=0;
			foreach ($arr_csv_values as $row) {
				if($row['A']){
					$customer_comments = $row['A'];
					$country 		   = $row['B'];
					$created_date     = $row['C']; 

					$response_detect_language = $kptaemtranslator->dell_curl_detect_language_request($customer_comments);
																					
					$response_target_text = $kptaemtranslator->dell_curl_request($customer_comments);
							
					$target_response[$p] = array('source_txt'=>$customer_comments,'country'=>$country,'created_date'=>$created_date,'detect_language'=>$response_detect_language,'target_txt'=>$response_target_text);
					$p++;
				}
			}
			

			/*print_r($target_response);die;
			die;
			return view('admin.translation.document',compact('arr_csv_values','kptaemtranslator'));*/
			if(count($target_response)>0){
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();
		$activeSheet = $spreadsheet->getActiveSheet();
		$activeSheet->setTitle('Translated File - Transflow');
		$activeSheet->setCellValue('A1', 'Customer Comments');
		$activeSheet->setCellValue('B1', 'Country');
		$activeSheet->setCellValue('C1', 'Create Date');
		$activeSheet->setCellValue('D1', 'Detected Language');
		$activeSheet->setCellValue('E1', 'Target Text');
		$activeSheet->getStyle("A1")->getFont()->setSize(16);
		$activeSheet->getStyle("B1")->getFont()->setSize(16);
		$activeSheet->getStyle("C1")->getFont()->setSize(16);
		$activeSheet->getStyle("D1")->getFont()->setSize(16);		
		$activeSheet->getStyle("E1")->getFont()->setSize(16);		
			
			$k=0;
			$arr_create_csv = array();
			foreach($target_response as $row){
				$source_txt 		= $row['source_txt'];
				$country			= $row['country'];
				$created_date		= $row['created_date'];
				$detect_language	= $row['detect_language'];
				$target_txt			= $row['target_txt'];

				$row = (int)$k+2;
			  	$activeSheet->setCellValue('A'.$row,  $source_txt);     
			  	$activeSheet->setCellValue('B'.$row , $country);
			  	$activeSheet->setCellValue('C'.$row , $created_date);
			  	$activeSheet->setCellValue('D'.$row , $detect_language); 
			  	$activeSheet->setCellValue('E'.$row , $target_txt); 
				$k++;
			}
			
			
			
			// Redirect output to a client's web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="Translated_file_Transflow.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			 
			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0
			 
			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			/*$save_path = $_SERVER['DOCUMENT_ROOT']."/assets/uploadfiles/dell/Tranflow_Translated_".date('dMy').".xlsx";*/
			$path=base_path()."/public/storage/dell/";
                if(!File::isDirectory($path)){
                    $some=  File::makeDirectory($path, 0777, true);
                }
                $path_save="public/dell";
                $source_file_path=$upload_file_name = "Tranflow_Translated_".time().".xlsx";
                $save_path=$path."/".$upload_file_name;
			if(file_exists($save_path))
				unlink($save_path);

			$response_save = $writer->save($save_path);
			//$writer->save('php://output');
			$res['status']=1;
			$res['msg']='Success';
			$res['result']=$upload_file_name;
			
		}else{
			$res['status']=0;
			$res['msg']='Something went wrong';
		}		
				
		}
		echo json_encode($res);
	}
	/* Upload CSV Submit */
	
	public function curl_translate_document_download(Request $request)
	{
		//header('Content-Type: application/json');
		//print_r($request->input('target_response'));die;
		$target_response = json_decode($request->input('excel_data'));
		//print_r($target_response);die;
		if(count($target_response)>0){
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();
		$activeSheet = $spreadsheet->getActiveSheet();
		$activeSheet->setTitle('Translated File - Transflow');
		$activeSheet->setCellValue('A1', 'Customer Comments');
		$activeSheet->setCellValue('B1', 'Country');
		$activeSheet->setCellValue('C1', 'Create Date');
		$activeSheet->setCellValue('D1', 'Detected Language');
		$activeSheet->setCellValue('E1', 'Target Text');
		$activeSheet->getStyle("A1")->getFont()->setSize(16);
		$activeSheet->getStyle("B1")->getFont()->setSize(16);
		$activeSheet->getStyle("C1")->getFont()->setSize(16);
		$activeSheet->getStyle("D1")->getFont()->setSize(16);		
		$activeSheet->getStyle("E1")->getFont()->setSize(16);		
			
			$k=0;
			$arr_create_csv = array();
			foreach($target_response as $row){
				$source_txt 		= $row['source_txt'];
				$country			= $row['country'];
				$created_date		= $row['created_date'];
				$detect_language	= $row['detect_language'];
				$target_txt			= $row['target_txt'];

				$row = (int)$key+2;
			  	$activeSheet->setCellValue('A'.$source_txt,  $source_txt);     
			  	$activeSheet->setCellValue('B'.$source_txt , $country);
			  	$activeSheet->setCellValue('C'.$source_txt , $created_date);
			  	$activeSheet->setCellValue('D'.$source_txt , $detect_language); 
			  	$activeSheet->setCellValue('E'.$source_txt , $target_txt); 
				$k++;
			}
			
			
			
			// Redirect output to a client's web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="Translated_file_Transflow.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			 
			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0
			 
			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			$save_path = $_SERVER['DOCUMENT_ROOT']."/assets/uploadfiles/dell/Tranflow_Translated_".date('dMy').".xlsx";
			$path=base_path()."/public/storage/request/";
                if(!File::isDirectory($path)){
                    $some=  File::makeDirectory($path, 0777, true);
                }
                $path_save="public/request";
                $source_file_path=$upload_file_name = time().'_'.$file_name;
                if(!file_exists("$path/$upload_file_name")){
                    $file->move($path,$upload_file_name);
                }
			if(file_exists($save_path))
				unlink($save_path);

			$response_save = $objWriter->save($save_path);
			$writer->save('php://output');
			exit;
			
		}
	}
	
	/* CSV segmentation submit form */
	public function csvsegmentationformsubmit(Request $request){
		
		$source_text = $request->input('source_text');
		$target_text = $request->input('target_text');
		
		
		
		$source_language = $request->input('hid_source_language');
		$target_language = $request->input('hid_target_language');
		
				
		if(count($target_text)>0){
			
			
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();
		$activeSheet = $spreadsheet->getActiveSheet();
		$activeSheet->setTitle('Translated File - Transflow');
		$activeSheet->setCellValue('A1', 'Source Language');
		$activeSheet->setCellValue('B1', 'Source Text');
		$activeSheet->setCellValue('C1', 'Target Language');
		$activeSheet->setCellValue('D1', 'Target Text');
		$activeSheet->getStyle("A1")->getFont()->setSize(16);
		$activeSheet->getStyle("B1")->getFont()->setSize(16);
		$activeSheet->getStyle("C1")->getFont()->setSize(16);
		$activeSheet->getStyle("D1")->getFont()->setSize(16);		
			
			$k=0;
			$arr_create_csv = array();
			foreach($source_text as $key => $rowvalue){				
				$sourcetext = $rowvalue;
				$targettext = $target_text[$k];				
				$arr_create_csv[] = array("sourcelanguage"=>$source_language,"sourcetext"=>$sourcetext,"targetlanguage"=>$target_language,"targettext"=>$targettext);
				
			  $row = (int)$key+2;
			  $activeSheet->setCellValue('A'.$row,  $source_language);     
			  $activeSheet->setCellValue('B'.$row , $sourcetext);
			  $activeSheet->setCellValue('C'.$row , $target_language);
			  $activeSheet->setCellValue('D'.$row , $targettext); 
				
				$k++;
			}
			
			
			
			// Redirect output to a client's web browser (Xlsx)
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="Translated_file_Transflow.xlsx"');
			header('Cache-Control: max-age=0');
			// If you're serving to IE 9, then the following may be needed
			header('Cache-Control: max-age=1');
			 
			// If you're serving to IE over SSL, then the following may be needed
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
			header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
			header('Pragma: public'); // HTTP/1.0
			 
			$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			$writer->save('php://output');
			exit;
			
		}		
		
		
	}
	/* CSV segmentation submit form */
	
	
	
	
	
}
