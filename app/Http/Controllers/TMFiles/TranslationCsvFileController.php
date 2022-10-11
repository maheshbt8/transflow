<?php

namespace App\Http\Controllers\TMFiles;

use App\Http\Controllers\Controller;
use App\TranslationCsvFile;
use App\file_translation_memory;
use App\file_temp;
use App\Translationmodel;
use App\Kptorganization;
use App\kptAEMtranslator;
use Illuminate\Http\Request;
use App\loc_request;
use App\loc_multiple_file;
use Illuminate\Validation\Rule;
use Auth;
use App\locrequestassigned;
use App\User;
use App;
// use App\Http\Controllers\AEM\DB;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
//to execute python script
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
//to execute python script
use Spatie\PdfToText\Pdf;
use App\loc_languages;
use App\locQuoteSourcelang;
use App\loc_translation_master;
use PhpParser\Node\Expr\Print_;
use Storage;
use \Statickidz\GoogleTranslate;
use DOMDocument;
use DOMXpath;
class TranslationCsvFileController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	/* Upload CSV file */
	public function index() {
		$userid=Auth::user()->id;
		$translationcsvfile = new TranslationCsvFile();	
		$domains= DB::connection('mysql2')->table('tm_domains')->select('domain_name','domain_id')->get();
		$loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status','ACTIVE')->get();
		$file_data=file_translation_memory::where(['created_by' => $userid])->orderBy('id','DESC')->get();
		return view('TMFiles.csv.index', compact('translationcsvfile', 'domains', 'loc_languages', 'file_data'))->with(['page_title' => 'File Translation']);
	}
	public function approve_translations()
	{
		$translation_memory_data = DB::connection('mysql2')->table('tm_temp')->get();
		$loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
		return view('TMFiles.csv.approve', compact('translation_memory_data', 'loc_languages'));
	}
	public function massDestroy(Request $request)
	{
		/*if (!checkpermission('client_user', 'delete')) {
            return abort(401);
        }*/
		DB::connection('mysql2')->table('tm_temp')->whereIn('sid', request('ids'))->delete();
		Session()->flash('message', 'User successfully deleted');

		return response()->noContent();
	}
	public function tm_approve_data(Request $request)
	{
		/*if (!checkpermission('client_user', 'delete')) {
            return abort(401);
        }*/
		//print_r(request('ids'));die;
		$userid = Auth::user()->id;
		//$data=DB::connection('mysql2')->table('tm_temp')->whereIn('sid', request('ids'))->get();
		$data = DB::connection('mysql2')->table('tm_temp')->get();
		foreach ($data as $row) {
			$arr_source_tm_data = array(
				"source_lang" => $row->source_lang,
				"source_text" => $row->source_text,
				"tareget_lang" => $row->target_lang,
				"target_text" => $row->target_text,
				"domain_id" => $row->domain_id,
				"created_userid" => $userid,
				"updated_userid" => $userid,
				"created_at" => date('Y-m-d H:i:s'),
				"updated_at" => date('Y-m-d H:i:s')
			);
			//print_r($arr_source_tm_data);die;		
			$response = Translationmodel::add_update_translationmemory_webapi($arr_source_tm_data);
			//print_r($response);die;
		}
		//DB::connection('mysql2')->table('tm_temp')->whereIn('sid', request('ids'))->delete();
		DB::connection('mysql2')->table('tm_temp')->delete();
		Session()->flash('message', 'Data approved successfully.');

		return redirect()->route('admin.translationcsvfile.approve_translations');
	}
	/* Upload CSV file */

      /* direct translation from projects */
	public function projecte_file_translation_memory($id){
		$multipleFiles = new loc_multiple_file();
		$req_file_data = $multipleFiles::where('id',$id)->first();
		$created_at=$req_file_data->created_at;
		$source_id=$req_file_data->source_language;
		$req_id=$req_file_data->request_id;
		$language = new locQuoteSourcelang();
		$source_lang_id = locQuoteSourcelang::where(['id' => $source_id])->value('sourcelang_id');
   		$loc_file_data=$req_file_data->original_file;
		$project=$req_file_data->file_name;
		$loc_request = loc_request::where(['req_id' => $req_id])->first();
		$user_id=$loc_request->user_id;
		$org_id=$loc_request->organization_id;
		$requestassigned = array('request_id' => $req_id, 'loc_source_id' => $source_id);
		$get_target_lang=locrequestassigned::where($requestassigned)->first();
		$target_language_id=$get_target_lang->target_language;
		$data = [
			'project_name' => $project,
			'source_lang_id' => $source_lang_id,
			'target_lang_id' => $target_language_id,
			'domain_id' => '1',
			'user_id' => $user_id,
			"created_by" => $user_id,
			'org_id' => $org_id,
			'loc_file_id'=>$id
		];
		$file_data = file_translation_memory::create($data);
		$mian_id = $file_data->id;
		if ($mian_id > 0) {
			$sfile_date=date('Ymd',strtotime($created_at));
			$loc_csv_file = $file_url= env('AWS_CDN_URL') . '/request/source/'.$sfile_date.'/'.$project ;
			$exp_file = explode('.', $project);
			$extension = end($exp_file);
			$act_filename = $id . '_' . time() . '.' . $extension;
			$file_data = file_get_contents($loc_csv_file, true);
			$filePath = public_path("storage/temp_loc/" . $act_filename);	
			$file_data_res=file_put_contents($filePath, $file_data);
			$act_filename_tr = $mian_id . '_' . time() . '.' . $extension;
			$filePath_res = 'translation_csv_file/source/' . $act_filename_tr;	
			$file_data_loc=file_get_contents($filePath);
		  	$res = Storage::disk('s3')->put($filePath_res, $file_data_loc);
			$arr_csv_values=get_file_content($filePath);
			$word_countss =  get_word_count($arr_csv_values);
			$total_words=$word_countss['total_words'];
			$repeated_words=$word_countss['repeated_words'];
			$character_count=$word_countss['character_count'];
			$segment_count=$word_countss['segment_count'];
			$data = [
				'translation_files_name' => $act_filename_tr, 
				'files_name' => $project, 
				'word_count' => $word_countss['total_words'],
				'repeated_words' => $word_countss['repeated_words'], 
				'character_count' => $word_countss['character_count'], 
				'segment_count' => $word_countss['segment_count']
				];
			$filepath=file_translation_memory::where(['loc_file_id'=>$id])->update($data);
			return redirect()->route('admin.translationcsvfile.get_file_data_content',[$mian_id]);
		}else{
			return abort(401);
		}
	}

	/* CSV segmentation submit form */
	public function file_translation_memory(Request $request)
	{
		$userid = Auth::user()->id;
		$user_role = Auth::user()->roles[0]->name;
		if ($user_role == "orgadmin") {
			$kpt_org_id = get_user_org('org', 'org_id');
		} else {
			$kpt_org_id = '';
		}
		$validator = $this->validate(
			$request,
			[
				'sourcelanguage' => ['required'],
				'targetlanguages' => ['required'],
				'translation_csv_file' => ['required']
			]
		);
		$data = [
			'project_name' => $request['project_name'],
			'source_lang_id' => $request['sourcelanguage'],
			'target_lang_id' => $request['targetlanguages'],
			'domain_id' => $request['domain'],
			'word_count' => $request['word_count'],
			'repeated_words' => $request['repeated_words'],
			'segment_count' => $request['segment_count'],
			'character_count' => $request['character_count'],
			'user_id' => $userid,
			"created_by" => $userid,
			'org_id' => $kpt_org_id,
		];
		$file_data = file_translation_memory::create($data);
		$mian_id = $file_data->id;
		if ($mian_id > 0) {
			if ($_FILES['translation_csv_file']['name'] != "") {
				//Source File upload
				$file_name4 = $_FILES['translation_csv_file']['name'];     //file name
				$file_size4 = $_FILES['translation_csv_file']['size'];     //file size
				$file_temp = $_FILES['translation_csv_file']['tmp_name']; //file temp 
				$ext4 = strtolower(pathinfo($file_name4, PATHINFO_EXTENSION));
				$act_filename = $mian_id . '_' . time() . '.' . $ext4;
				$filePath = 'translation_csv_file/source/' . $act_filename;
				$file = $request->file('translation_csv_file');
				$file_name = $request->file('translation_csv_file')->getClientOriginalName();
				$file_name4 = $request->file('translation_csv_file')->getRealPath();
				$res = Storage::disk('s3')->put($filePath, file_get_contents($file));
				$translation_csv_file = $file_url = env('AWS_CDN_URL') . '/translation_csv_file/source/' . $act_filename;
				$file_data = file_get_contents($translation_csv_file, true);
				$filePath = public_path("storage/temp_dir/" . $act_filename);
				file_put_contents($filePath, $file_data);
				$file_data_arr=get_file_content($filePath);
				$word_countss =  get_word_count($file_data_arr);
				$filepath = file_translation_memory::where(['id' => $mian_id])->update(['translation_files_name' => $act_filename, 'files_name' => $file_name, 'word_count' => $word_countss['total_words'], 'repeated_words' => $word_countss['repeated_words'], 'character_count' => $word_countss['character_count'], 'segment_count' => $word_countss['segment_count']]);
			}
			Session()->flash('message', 'File uploaded successfully.');
		}else{
			Session()->flash('message', 'Something went wrong.');
		}
		return redirect()->route('admin.translationcsvfile.index');
	}
	public function get_file_data_content($id)
	{
		$file_tr_data = $file_data = file_translation_memory::where(['id' => $id])->first();
		$sourcelanguage = strtolower(gettabledata('loc_languages','lang_code',['lang_id'=>$file_tr_data->source_lang_id]));
		$targetlanguages = strtolower(gettabledata('loc_languages','lang_code',['lang_id'=>$file_tr_data->target_lang_id]));
		$translationcsvfile = new TranslationCsvFile();
		$kptaemtranslator = new kptAEMtranslator();
		$userid = Auth::user()->id;
		$kptorganization 	= Kptorganization::get()->pluck('org_name', 'org_id');
		$domain = $file_tr_data->domain_id;
		$file_name = $file_data->translation_files_name;
		$translation_csv_file = $file_url = env('AWS_CDN_URL') . '/translation_csv_file/source/' . $file_name;
		$exp_file = explode('.', $file_name);
		$extension = end($exp_file);
		$file_data = file_get_contents($translation_csv_file, true);
		$translation_csv_file = $filePath = public_path("storage/temp_dir/" . $file_name);
		file_put_contents($filePath, $file_data);
		$arr_csv_values=get_file_content($filePath);
		/*$grep = new DOMDocument();
		@$grep->loadHTMLFile("https://integritysoftsolutions.com/");
		$res=$grep->getElementsByTagName('html')->item(0);
		$finder = new DOMXpath($grep);
		$products = array();
		$nodes=$res->nodeValue;
		$text_data = addslashes(strip_tags($nodes));
		//$arr_csv_values = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $text_data, -1, PREG_SPLIT_NO_EMPTY);
		$arr_csv_values = sentence_split($text_data);
		$arr_csv_values = arraydataformated($arr_csv_values);
		echo "<pre/>";
		print_r($arr_csv_values);die;*/
		$file_type=$extension;
		$file_temp = new file_temp();
		return view('TMFiles.csv.translateform', compact('arr_csv_values', 'sourcelanguage', 'targetlanguages', 'kptaemtranslator', 'domain', 'file_type', 'file_tr_data','file_temp'));
	}

	public function csvsegmentationformsubmit(Request $request)
	{
		$userid = Auth::user()->id;
		$source_text = $request->input('source_text');
		$target_text = $request->input('target_text');
		$translated_flag = $request->input('translated_flag');
		$domain_id = $request->input('domain_id');
		$source_language = $request->input('hid_source_language');
		$target_language = $request->input('hid_target_language');
		if (count($target_text) > 0) {
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
			$k = 0;
			$arr_create_csv = array();
			foreach ($source_text as $key => $rowvalue) {
				$sourcetext = $rowvalue;
				$targettext = $target_text[$k];
				$arr_create_csv[] = array("sourcelanguage" => $source_language, "sourcetext" => $sourcetext, "targetlanguage" => $target_language, "targettext" => $targettext);
				if ($translated_flag[$k] != 'TM') {
					$arr_source_tm_data = array(
						"source_lang" => $source_language,
						"source_text" => $sourcetext,
						"target_lang" => $target_language,
						"target_text" => $targettext,
						"domain_id" => $domain_id,
						"created_userid" => $userid,
						"updated_userid" => $userid,
						"created_at" => date('Y-m-d H:i:s'),
						"updated_at" => date('Y-m-d H:i:s')
					);
					$response = Translationmodel::add_translationmemory_temp($arr_source_tm_data);
				}

				$row = (int)$key + 2;
				$activeSheet->setCellValue('A' . $row,  $source_language);
				$activeSheet->setCellValue('B' . $row, $sourcetext);
				$activeSheet->setCellValue('C' . $row, $target_language);
				$activeSheet->setCellValue('D' . $row, $targettext);

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
			
			/*$filePath = 'translation_csv_file/sourcetarget/' . $qfiledate . '/' . $quote_file;

            $res = Storage::disk('s3')->put($filePath, $pdf->output());*/
			exit;
		}
	}
	/* CSV segmentation submit form */

	public function gettargettext()
	{
		$source_language = $_POST["source_language"];
		$target_languages = $_POST["target_languages"];
		$source_text = $_POST["source_text"];
		$domain_id = $_POST["domain_id"];
		$file_tr_id = $_POST["file_tr_id"];
		$kptaemtranslator = new kptAEMtranslator();
		if (numU($source_text)) {
			$target_text = $source_text;
		} else {
			$target_text = '';
			$get_filetemp_data = file_temp::where(['source_text' => str_replace("\r\n","\n",strip_tags(nl2br($source_text)))])->first();
			if($get_filetemp_data != ''){
				$target_text=$get_filetemp_data->target_text;
			}else{
			$arr_tm = Translationmodel::get_targettext_by_source_tm($source_language, $target_languages, $source_text, $domain_id);
			if (is_countable($arr_tm) != '' && count($arr_tm) > 0 && $arr_tm[0]->target_text != "") {
				$target_text = $arr_tm[0]->target_text;
			}/* elseif (($source_language  == "en" && $target_languages  == "hi") || ($source_language  == "hi" && $target_languages  == "en")) {
				$target_text = '';
				$arr_tm = Translationmodel::nntranslation($source_text);
				$t_text=$arr_tm;
				if($t_text != ''){
					$target_text = $t_text;
				}
			}*/ else {
					$trans = new GoogleTranslate();
					$tr_result = $trans->translate($source_language, $target_languages, $source_text);
					//$tr_result='';
					if($tr_result != '' && $tr_result != $source_text){
						$target_text = $tr_result;
					}else{
						$target_text = '';
					}
				}
			}
		}
		return $target_text;
	}

	public function delete_data()
	{
		$id = $_POST["id"];
		DB::connection('mysql2')->table('tm_temp')->where('sid', $id)->delete();
		return $id;
	}

	public function savefiledata()
	{
		$source_text = $_POST["source_text"];
		$target_text = $_POST["target_text"];
		$file_tr_id = $_POST["file_tr_id"];
		$save_data = array(
			'source_text' => $source_text,
			'target_text' => $target_text,
			'file_tr_id' => $file_tr_id,
		);
		$get_filetemp_data = file_temp::where(['source_text' => $source_text, 'file_tr_id' => $file_tr_id])->first();
		//$get_target_text=gettabledata('file_temp','target_text',['source_text'=>$source_text,'file_tr_id'=>$file_tr_id]);
		if ($get_filetemp_data == '') {
			$loc_files_update = file_temp::insert($save_data);
		} else {
			if($target_text != $get_filetemp_data->target_text){
				$loc_files_update = file_temp::where(['id' => $get_filetemp_data->id])->update($save_data);
			}else{
				//$loc_files_update='exist';
				$res = ['status' => 2];
				echo json_encode($res);die;
			}
		}
		if ($loc_files_update == true) {
			$res = ['status' => 1, 'msg' => 'Saved content successfully'];
		}else {
			$res = ['status' => 0, 'msg' => 'Content not saved'];
		}
		echo json_encode($res);
	}
}
