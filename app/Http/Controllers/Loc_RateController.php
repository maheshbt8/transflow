<?php

namespace App\Http\Controllers;
use App\Kptorganization;
use App\loc_languages;
use App\locService;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\loc_ratecard;
use App\currencies;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\IOFactory;
class Loc_RateController extends Controller
{

    public function index(){
        if (! checkpermission('ratecard')) {
            return abort(401);
        }
         $user_role = Auth::user()->roles[0]->name;
         if($user_role=='administrator'){
            $kptorganizations = Kptorganization::where('org_status','1')->get();
         }else{
            $userorg=get_user_org('org','org_id');
            $kptorganizations = Kptorganization::where(['org_status'=>'1','org_id'=>$userorg])->get();
         }
         if(isset($_GET['service_type']) && $_GET['service_type'] != ''){
            $service_type=$_GET['service_type'];
         }else{
             $service_type='';
         }
         if(isset($_GET['source_language']) && $_GET['source_language'] != ''){
            $source_language=$_GET['source_language'];
         }else{
             $source_language='';
         }
         if(isset($_GET['organization']) && $_GET['organization'] != ''){
            $organization=$_GET['organization'];
         }else{
             $organization='';
         }
         if(isset($_GET['currency']) && $_GET['currency'] != ''){
            $currency=$_GET['currency'];
         }else{
             $currency='';
         }
        // if($service_type != '' && $source_language != '' && $organization != ''){
        //     $ratecard=
        // }else{
        //     $ratecard=array();
        // }
      $user  = new user;
      $loc_ratecard=new loc_ratecard();
      $loc_services = locService::orderBy('id', 'ASC')->get();
      $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
      $currency_list = currencies::where('status','Active')->get();
      return view('admin.ratecard.index',compact('kptorganizations','user','loc_languages','loc_services','loc_ratecard','organization','source_language','service_type','currency_list','currency'))->with(['page_title'=>'Rate Card']);

        
       // return view('admin.ratecard.index');
    }



    public function create()
    {
        if (! checkpermission('ratecard')) {
            return abort(401);
        }
        return view('admin.ratecard.create');
    }
    public function store(Request $request){
        if (!checkpermission('ratecard','add')) {
            return abort(401);
        }

        $user_id = Auth::user()->id;
     
        //$org_id = get_user_org('org', 'org_id');

        $organization = $request->input('organization');
        $service_type = $request->input('service_type');
        $source_language =$request->input('source_language');
        $target_language =$request->input('target_language');
        $currency =$request->input('currency');


        $ser_type=gettabledata('loc_service','type',['id'=>$service_type]);
        if($ser_type == 'slab_minute'){
          $target_price_15 =$request->input('target_price_15');
          $target_price_30 =$request->input('target_price_30');
          $target_price_45 =$request->input('target_price_45');
          $target_price_60 =$request->input('target_price_60');
        }else{
          $target_price =$request->input('target_price');
        }

        $rate_card_details = new loc_ratecard();
        $target_data= $rate_card_details->get_target_price($organization,$service_type,$currency,$source_language,$target_language);
        $ratecard = array(
            "source_lang" => $source_language,
            'updated_by' => $user_id, 
            'org_id' => $organization,
            'currency_id'=> $currency,
            "servie_id" => $service_type,
            "target_lang" => $target_language,
            );
        if($ser_type == 'slab_minute'){
            $ratecard['minute_cost_15']=$target_price_15;
            $ratecard['minute_cost_30']=$target_price_30;
            $ratecard['minute_cost_45']=$target_price_45;
            $ratecard['minute_cost_60']=$target_price_60;
        }elseif($ser_type == 'page'){
            $ratecard['page_cost']=$target_price;
        }elseif($ser_type == 'minute'){
            $ratecard['minute_cost']=$target_price;
        }else{  
            $ratecard['word_cost']=$target_price;
        }
        if($target_data){
            loc_ratecard::where(['id'=>$target_data->id])->update($ratecard);
        }else{
            loc_ratecard::insert($ratecard);
        }
        echo "1";
 
    }
    public function updateratecard(Request $request)
    {
      if (!checkpermission('ratecard','update')) {
            return abort(401);
        }

        $user_id = Auth::user()->id;
        $organization = $request->input('organization');
        $service_type = $request->input('service_type');
        $source_language =$request->input('source_language');
        $currency =$request->input('currency');
        //print_r($_POST);die;
        $translation_csv_file = $request->file('ratecard_file')->getRealPath();      
        $translation_file_extension = $request->file('ratecard_file')->getClientOriginalExtension();
        if($organization != '' && $source_language != '' && $service_type != '' && $currency != '' && $translation_file_extension == 'csv')
        {
          $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucfirst($translation_file_extension));
          $reader->setDelimiter("\t");
          $spreadsheet = $reader->load($translation_csv_file);
          $arr_csv_values = $spreadsheet->getActiveSheet()->toArray();
          $ser_type=gettabledata('loc_service','type',['id'=>$service_type]);
          foreach($arr_csv_values as $rows){
            $spl=explode(',',$rows[0]);
            $target_language='';
            if(isset($spl[1]) && $spl[1] != ''){
              $target_language=gettabledata('loc_languages','lang_id',['lang_code'=>$spl[1]]);
            }
            $ratecard_state = 0;
            $ratecard = array(
              "source_lang" => $source_language,
              'updated_by' => $user_id, 
              'org_id' => $organization,
              "servie_id" => $service_type,
              "target_lang" => $target_language,
            );
            if($ser_type == 'slab_minute' && ((isset($spl[2]) && $spl[2] != '' && $spl[2] > 0) || (isset($spl[3]) && $spl[3] != '' && $spl[3] > 0) || (isset($spl[4]) && $spl[4] != '' && $spl[4] > 0) || (isset($spl[5]) && $spl[5] != '' && $spl[5] > 0)) && $target_language != ''){
                $ratecard['minute_cost_15']=$spl[2];
                $ratecard['minute_cost_30']=$spl[3];
                $ratecard['minute_cost_45']=$spl[4];
                $ratecard['minute_cost_60']=$spl[5];
                $ratecard_state = 1;
            }elseif(($ser_type == 'word' || $ser_type == 'minute' || $ser_type == 'page') && isset($spl[2]) && $spl[2] != '' && $spl[2] > 0 && $target_language != ''){
                if($ser_type == 'page'){
                  $ratecard['page_cost']=$spl[2];
                }elseif($ser_type == 'minute'){
                  $ratecard['minute_cost']=$spl[2];
                }else{  
                  $ratecard['word_cost']=$spl[2];
                }
                $ratecard_state = 1;
            }
            $rate_card_details = new loc_ratecard();
            $target_data= $rate_card_details->get_target_price($organization,$service_type,$currency,$source_language,$target_language);
            if($ratecard_state == 1){
              if($target_data){
                  loc_ratecard::where(['id'=>$target_data->id])->update($ratecard);
              }else{
                  loc_ratecard::insert($ratecard);
              }
            }
          }
          Session()->flash('message', 'Data updated successfully');
        }else{
          Session()->flash('error_message', 'Something went wrong');
        }
        
        return Redirect()->back();
    }
    public function sampledownload()
    { 
      $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->where('lang_status', 'ACTIVE')->get();
      if(isset($_GET['service_type']) && $_GET['service_type'] != ''){
            $service_type=$_GET['service_type'];
         }else{
             $service_type='';
         }
         if(isset($_GET['source_language']) && $_GET['source_language'] != ''){
            $source_language=$_GET['source_language'];
         }else{
             $source_language='';
         }
         if(isset($_GET['organization']) && $_GET['organization'] != ''){
            $organization=$_GET['organization'];
         }else{
             $organization='';
         }
         if(isset($_GET['currency']) && $_GET['currency'] != ''){
            $currency=$_GET['currency'];
         }else{
             $currency='';
         }
      if($organization != '' && $source_language != '' && $service_type != '' && $currency != '')
      {
        $loc_ratecard=new loc_ratecard();
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->setTitle('Ratecard - Transflow');
        $activeSheet->setCellValue('A1', 'Target Language');
        $activeSheet->setCellValue('B1', 'Language Code');
        $activeSheet->getStyle("A1")->getFont()->setSize(16);
        $activeSheet->getStyle("B1")->getFont()->setSize(16);

        $ser_type=gettabledata('loc_service','type',['id'=>$service_type]);
        $currency_type=gettabledata('currencies','currency_code',['id'=>$currency]);
        if($ser_type == 'slab_minute'){
          $activeSheet->setCellValue('C1', 'Rate in '.$currency_type.' (15 minutes)');
          $activeSheet->getStyle("C1")->getFont()->setSize(16);
          $activeSheet->setCellValue('D1', 'Rate in '.$currency_type.' (30 minutes)');
          $activeSheet->getStyle("D1")->getFont()->setSize(16);
          $activeSheet->setCellValue('E1', 'Rate in '.$currency_type.' (45 minutes)');
          $activeSheet->getStyle("E1")->getFont()->setSize(16);
          $activeSheet->setCellValue('F1', 'Rate in '.$currency_type.' (60 minutes)');
          $activeSheet->getStyle("F1")->getFont()->setSize(16);
          $k=0;
          $arr_create_csv = array();
          foreach($loc_languages as $key => $rowvalue){       
            $row = (int)$k+2;
            $target_price=$loc_ratecard->get_target_price($organization,$service_type,$currency,$source_language,$rowvalue->lang_id);
            $price_15=$price_30=$price_45=$price_60=0;
            if($target_price){
              $price_15=$target_price->minute_cost_15;
              $price_30=$target_price->minute_cost_30;
              $price_45=$target_price->minute_cost_45;
              $price_60=$target_price->minute_cost_60;
            }
            $activeSheet->setCellValue('A'.$row,  $rowvalue->lang_name);     
            $activeSheet->setCellValue('B'.$row,  $rowvalue->lang_code);     
            $activeSheet->setCellValue('C'.$row , $price_15);
            $activeSheet->setCellValue('D'.$row , $price_30);
            $activeSheet->setCellValue('E'.$row , $price_45);
            $activeSheet->setCellValue('F'.$row , $price_60);
            $k++;
          }
        }else{
          $activeSheet->setCellValue('C1', 'Rate in '.$currency_type);
          $activeSheet->getStyle("C1")->getFont()->setSize(16);
          $k=0;
          $arr_create_csv = array();
          foreach($loc_languages as $key => $rowvalue){       
            $row = (int)$k+2;
            $target_price=$loc_ratecard->get_target_price($organization,$service_type,$currency,$source_language,$rowvalue->lang_id);
            $price=0;
            if($target_price){
              $price=$target_price->price;
            }
            $activeSheet->setCellValue('A'.$row,  $rowvalue->lang_name);     
            $activeSheet->setCellValue('B'.$row,  $rowvalue->lang_code);     
            $activeSheet->setCellValue('C'.$row , $price);
            $k++;
          }
      }
      // Redirect output to a client's web browser (Xlsx)
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="Ratecard.csv"');
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      header('Cache-Control: max-age=1');
       
      // If you're serving to IE over SSL, then the following may be needed
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified

      header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header('Pragma: public'); // HTTP/1.0
      $writer = IOFactory::createWriter($spreadsheet, 'Csv');
      $writer->save('php://output');
      exit;
    }
    return true;
  }
}
