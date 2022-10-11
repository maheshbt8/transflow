<?php
use App\loc_languages;
use App\loc_request;
use App\user;
use App\settings;
use App\currencies;
use App\models\loc_translation_master;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;
use Symfony\Component\HttpFoundation\Response;
use Spatie\PdfToText\Pdf;
function currenychange($currency){
    $number =$currency;
$no = floor($number);
$point = round($number - $no, 2) * 100;
$hundred = null;
$digits_1 = strlen($no);
$i = 0;
$str = array();
$words = array('0' => '', '1' => 'one', '2' => 'two',
 '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
 '7' => 'seven', '8' => 'eight', '9' => 'nine',
 '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
 '13' => 'thirteen', '14' => 'fourteen',
 '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
 '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
 '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
 '60' => 'sixty', '70' => 'seventy',
 '80' => 'eighty', '90' => 'ninety');
$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
while ($i < $digits_1) {
  $divider = ($i == 2) ? 10 : 100;
  $number = floor($no % $divider);
  $no = floor($no / $divider);
  $i += ($divider == 10) ? 1 : 2;
  if ($number) {
     $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
     $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
     $str [] = ($number < 21) ? $words[$number] .
         " " . $digits[$counter] . $plural . " " . $hundred
         :
         $words[floor($number / 10) * 10]
         . " " . $words[$number % 10] . " "
         . $digits[$counter] . $plural . " " . $hundred;
  } else $str[] = null;
}
$str = array_reverse($str);
$result = implode('', $str);
$points = ($point) ?
 "." . $words[$point / 10] . " " . 
       $words[$point = $point % 10] : '';
$res= $result . "Rupees  " . $points . " Paise";
return $res;
}

function priceToFloat($s)
{
    // convert "," to "."
    $s = str_replace(',', '.', $s);

    // remove everything except numbers and dot "."
    $s = preg_replace("/[^0-9\.]/", "", $s);

    // remove all seperators from first part and keep the end
    $s = str_replace('.', '',substr($s, 0, -3)) . substr($s, -3);

    // return float
    return (float) $s;
}

function check_rate_cost($mycost,$range,$mytime=60,$count=1)
{
    if(in_array($range, range(($mytime+1), ($mytime+15))))
    {
        $g_total=$mycost+(($mycost/4)*$count);
        return $g_total;
    }else{
        $count++;
        return check_rate_cost($mycost,$range,($mytime+16),$count);
    }
}



function get_word_count($arr_csv_values){
  $result =$arr_unique=$arr_repeated =[];
  array_walk_recursive($arr_csv_values, function($v) use (&$result) {
      $result[] = trim($v);
    });
  $words=   implode(" ", $result);
  $segment_count=count($arr_csv_values);
  for($i=0;$i<$segment_count;$i++){
    if(isset($arr_csv_values[$i]) && $arr_csv_values[$i] != ''){
      $v=strtolower(trim($arr_csv_values[$i]));
      if(in_array($v,$arr_unique)){
        $arr_repeated[]=$v;
      }else{
        $arr_unique[]=$v;
      }
    }
  }
  $repeated_segment_words= implode(" ", $arr_repeated);
  $repeated_words=str_word_count($repeated_segment_words);  
  $charcter_count=strlen($words);
  $words_count=count(explode(' ',$words));  
  $arr=['total_words'=>$words_count,'repeated_words'=>$repeated_words,'character_count'=>$charcter_count,'segment_count'=>$segment_count];
  return $arr;
  }

function matching_word_percantage($arr_csv_values,$translation_memory_data){

//  $web= implode(" ",$arr_csv_values); 

//  $tms = explode(" ",$translation_memory_data); 

//  $tms_data = implode("",$tms);
  
//  // echo $tms_data."<br/>";
//   $words_counts=str_word_count($tms_data);

//   //echo $words_counts;
  

//   similar_text($tms_data, $web,$percent);  
    
//   echo $percent;
 
//   echo (int)$percent;    
  
//   $c = count(array_intersect($arr_csv_values, $translation_memory_data));
//   echo $c;
  

}


function getHtml($url, $post = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    if(!empty($post)) {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    } 
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
function get_curldata($url)
  {
    $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HEADER, FALSE);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
      curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
      curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
      $result = curl_exec($ch);
      curl_close($ch);
      return $result;
  }

function get_web_page( $url )
  {
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }

function get_file_content($filePath)
{
 
  $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
  $inputFileType = array('csv', 'xlsx', 'xls');
  if (in_array($extension, $inputFileType)) {
      $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucfirst($extension));
      if ($extension == 'csv')
        $reader->setDelimiter("\t");

      /**  Load the file to a Spreadsheet Object  **/
      $spreadsheet = $reader->load($filePath);
      /**  Convert Spreadsheet Object to an Array for ease of use  **/
      $arr_csv_values_list = array_values($spreadsheet->getActiveSheet()->toArray(null, true, true, true));
      $sheetcount=$spreadsheet->getSheetCount();
      $sheet_name=$spreadsheet->getSheetNames();
      for ($sc=0; $sc < $sheetcount; $sc++) { 
        $arr_csv_values_list = array_values($spreadsheet->getSheetByName($sheet_name[$sc])->toArray(null, true, true, true));
        for ($c = 0; $c < count($arr_csv_values_list); $c++) {
          if (isset($arr_csv_values_list[$c]) && $arr_csv_values_list[$c] != '') {
            $arr_keys=array_keys($arr_csv_values_list[$c]);
            for ($cc=0; $cc < count($arr_keys); $cc++) { 
              $arr_csv_values[] = addslashes(strip_tags($arr_csv_values_list[$c][$arr_keys[$cc]]));
            }
          }
        }
      }
      $arr_csv_values = arraydataformated($arr_csv_values);
      $file_type = 'csv';
    } elseif (strtolower($extension) == "docx") {
      $striped_content = '';
      $content = '';
      $zip = zip_open($filePath);

      if (!$zip || is_numeric($zip)) return false;

      while ($zip_entry = zip_read($zip)) {

        if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

        if (zip_entry_name($zip_entry) != "word/document.xml") continue;

        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

        zip_entry_close($zip_entry);
      } // end while
      zip_close($zip);
      $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
      $content = str_replace('</w:r></w:p>', "\r\n", $content);
      $text_data = addslashes(strip_tags($content));
      //$arr = preg_split('/(?<!\d)\.(?!\d)/', $text_data);
      $arr = sentence_split(addslashes(strip_tags($text_data)));
      $arr_csv_values = arraydataformated($arr);
    } else if (strtolower($extension) == "txt") {
      $fileHandle = fopen($filePath, "r") or die("Unable to open file!");
      $text_data = fread($fileHandle, filesize($filePath));
      $text_data = addslashes(strip_tags($text_data));
      //$arr = preg_split('/(?<!\d)\.(?!\d)/', $text_data);
      $arr = sentence_split(addslashes(strip_tags($text_data)));
      $arr_csv_values = arraydataformated($arr);
      $file_type = 'txt';
    } else if (strtolower($extension) == "json") {
      $fileHandle = fopen($filePath, "r") or die("Unable to open file!");
      $text_data = fread($fileHandle, filesize($filePath));
      //$text_data = addslashes(strip_tags($text_data));
      //$arr = preg_split('/(?<!\d)\.(?!\d)/', $text_data);
      //$arr = sentence_split(addslashes(strip_tags($text_data)));
      //$arr_csv_values=[];
      $arr =json_decode($text_data, true);
      $result = [];
      array_walk_recursive($arr, function($value, $key) use(&$result) {
              $result[] = $value;
      });
      /*foreach ($arr as $key => $value) {
        if(is_string($value)){
          $arr_csv_values[] = addslashes(strip_tags($value));
        }elseif(is_array($value) || is_object($value)){
          $arr_csv=(array)$value;
          foreach ($arr_csv as $key => $value) {
            if(is_string($value)){
              $arr_csv_values[] = addslashes(strip_tags($value));
            }
          }
        }else{

        }
      }*/
      /*for ($c = 0; $c < count($arr); $c++) {
          if (isset($arr[$c]) && $arr[$c] != '') {
            echo "string";die;
            $arr_csv=(array)$arr[$c];
            $arr_keys=array_keys($arr_csv);
            print_r($arr_csv);die;
            for ($cc=0; $cc < count($arr_keys); $cc++) { 
              $arr_csv_values[] = addslashes(strip_tags($arr_csv[$arr_keys[$cc]]));
              print_r($arr_csv_values);die;
            }
          }
        }*/
      $arr_csv_values = arraydataformated((array)$result);
      $file_type = 'json';
    } else if (strtolower($extension) == "doc") {
      $fileHandle = fopen($filePath, "r");
      $line = fread($fileHandle, filesize($filePath));
      $lines = explode(chr(0x0D), $line);
      $outtext = "";
      $arr_csv_values = array();
      foreach ($lines as $thisline) {
        $pos = strpos($thisline, chr(0x00));
        if (($pos !== FALSE) || (strlen($thisline) == 0)) {

        } else {
          $arr_csv_values[] = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", addslashes(strip_tags($thisline)));
        }
      }
      $arr_csv_values=arraydataformated($arr_csv_values);




   /*   if (($fh = fopen($file, 'rb')) !== false) {
                $headers = fread($fh, 0xA00);

                // read doc from 0 to 255 characters
                $n1 = (ord($headers[0x21C]) - 1);

                // read doc from 256 to 63743 characters
                $n2 = ((ord($headers[0x21D]) - 8) * 256);

                // read doc from 63744 to 16775423 characters
                $n3 = ((ord($headers[0x21E]) * 256) * 256);

                //read doc from 16775424 to 4294965504 characters
                $n4 = (((ord($headers[0x21F]) * 256) * 256) * 256);

                // Total length of text in the document
                $textLength = ($n1 + $n2 + $n3 + $n4);
                ini_set('memory_limit', '-1');
                $extracted_plaintext = fread($fh, $textLength);
            }
            */
      $file_type = 'docx';
    } elseif (strtolower($extension) == "pdf") {
      if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $path = 'c:/Program Files/Git/mingw64/bin/pdftotext';
      
        $text_data = Pdf::getText($filePath, $path);
       // print_r($text_data);die;
      } else {
        $text_data = Pdf::getText($filePath);
      }
      $arr = sentence_split(addslashes(strip_tags($text_data)));
      //$arr = preg_split('/(?<!\d)\.(?!\d)/', addslashes(strip_tags($text_data)));
      $arr_csv_values = arraydataformated($arr);
    } else {
      $arr_csv_values=[];
    }
    return $arr_csv_values;
/*    $NewString = preg_split('/(?<!\d)\.(?!\d)/', $text_data);
    //$arr=array_map('trim', explode('.', $text_data));
    $arr=array_map('trim', $NewString);
    $arr_csv_values = array_filter($arr);*/
}

function arraydataformated($arr)
{
  $arr=array_values(array_filter(array_map('trim',$arr)));
  return $arr;
}

function uniquearray($arr)
{
  $res=array_intersect_key($arr, array_unique(array_map('strtolower', $arr)));
  return arraydataformated($res);
}

function getclientid($translation_quote_id )
{
  

$quote_data=loc_translation_master::where('translation_quote_id',$translation_quote_id)->first();


$client_org=$quote_data->client_org_id;
$project_count=loc_translation_master::where(["client_org_id" => $client_org])->count();

$id=$quote_data->translation_quote_id;

if (date('m') <= 3) {//Upto June 2014-2015
$financial_year = (date('y')-1) .'-'.'' . date('y');
} else {//After June 2015-2016
$financial_year = date('y') . '-' .''. (date('y') + 1);
}
$month=date('m');
 
// if($month >= 4){
// $f_month=$month-3;
// print_r($f_month);die;
// }else{
// $f_month=$month+9;
// }
return 'KPT'.'-'.$financial_year.'-'.$client_org.'-'.$month.'-'.$project_count;
}
function replace_in_file($FilePath, $newpath, $OldText, $NewText)
{
    $Result = array('status' => 'error', 'message' => '');
    if(file_exists($FilePath)===TRUE)
    {
        if(is_writeable($FilePath))
        {
            try
            {
                $FileContent = file_get_contents($FilePath);
                $FileContent = str_ireplace($OldText, $NewText, $FileContent);
                if(file_put_contents($newpath, $FileContent) > 0)
                {
                    $Result["status"] = 'success';
                }
                else
                {
                   $Result["message"] = 'Error while writing file';
                }
            }
            catch(Exception $e)
            {
                $Result["message"] = 'Error : '.$e;
            }
        }
        else
        {
            $Result["message"] = 'File '.$FilePath.' is not writable !';
        }
    }
    else
    {
        $Result["message"] = 'File '.$FilePath.' does not exist !';
    }
    return $Result;
}
function convert_source_file_target($path)
{
    /*$path='C:/Users/mtailor/Desktop/tmstest/test.xlsx';
    $path1='C:/Users/mtailor/Desktop/tmstest/test_new.xlsx';*/

    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
      $inputFileType = array('csv', 'xlsx', 'xls');
    if (in_array($extension, $inputFileType)) {
      $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader(ucfirst($extension));
      if ($extension == 'csv')
        $reader->setDelimiter("\t");
      /**  Load the file to a Spreadsheet Object  **/
      $spreadsheet = $reader->load($path);
      /**  Convert Spreadsheet Object to an Array for ease of use  **/
      $arr_csv_values_list = array_values($spreadsheet->getActiveSheet()->toArray(null, true, true, true));
      $spreadsheet = new Spreadsheet();
    $activeSheet = $spreadsheet->getActiveSheet();
    $activeSheet->setTitle('Translated File - Transflow');
    $c=0;
      foreach ($arr_csv_values_list as $key => $rowvalue) {
        if (isset($arr_csv_values_list[$c]) && $arr_csv_values_list[$c] != '') {
          $arr_keys=array_keys($arr_csv_values_list[$c]);
          $row = (int)$key + 1;
          for ($cc=0; $cc < count($arr_keys); $cc++) { 
            $target_response=str_ireplace('Hello mahesh how are you.','successfully',  $arr_csv_values_list[$c][$arr_keys[$cc]]);
      $activeSheet->setCellValue($arr_keys[$cc].''.$row, $target_response);
          }
          $c++;
        }
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
function splitSentences($text) {
    $re = '/                # Split sentences on whitespace between them.
        (?<=                # Begin positive lookbehind.
          [.!?]             # Either an end of sentence punct,
        | [.!?][\'"]        # or end of sentence punct and quote.
        )                   # End positive lookbehind.
        (?<!                # Begin negative lookbehind.
          Mr\.              # Skip either "Mr."
        | Mrs\.             # or "Mrs.",
        | T\.V\.A\.         # or "T.V.A.",
                            # or... (you get the idea).
        )                   # End negative lookbehind.
        \s+                 # Split on whitespace between sentences.
        /ix';

    $sentences = preg_split($re, $text, -1, PREG_SPLIT_NO_EMPTY);
    return $sentences;
}
function sentence_split($text) {
    $before_regexes = array('/(?:(?:[\'\"„][\.!?…][\'\"”]\s)|(?:[^\.]\s[A-Z]\.\s)|(?:\b(?:St|Gen|Hon|Prof|Dr|Mr|Ms|Mrs|[JS]r|Col|Maj|Brig|Sgt|Capt|Cmnd|Sen|Rev|Rep|Revd)\.\s)|(?:\b(?:St|Gen|Hon|Prof|Dr|Mr|Ms|Mrs|[JS]r|Col|Maj|Brig|Sgt|Capt|Cmnd|Sen|Rev|Rep|Revd)\.\s[A-Z]\.\s)|(?:\bApr\.\s)|(?:\bAug\.\s)|(?:\bBros\.\s)|(?:\bCo\.\s)|(?:\bCorp\.\s)|(?:\bDec\.\s)|(?:\bDist\.\s)|(?:\bFeb\.\s)|(?:\bInc\.\s)|(?:\bJan\.\s)|(?:\bJul\.\s)|(?:\bJun\.\s)|(?:\bMar\.\s)|(?:\bNov\.\s)|(?:\bOct\.\s)|(?:\bPh\.?D\.\s)|(?:\bSept?\.\s)|(?:\b\p{Lu}\.\p{Lu}\.\s)|(?:\b\p{Lu}\.\s\p{Lu}\.\s)|(?:\bcf\.\s)|(?:\be\.g\.\s)|(?:\besp\.\s)|(?:\bet\b\s\bal\.\s)|(?:\bvs\.\s)|(?:\p{Ps}[!?]+\p{Pe} ))\Z/su',
        '/(?:(?:[\.\s]\p{L}{1,2}\.\s))\Z/su',
        '/(?:(?:[\[\(]*\.\.\.[\]\)]* ))\Z/su',
        '/(?:(?:\b(?:pp|[Vv]iz|i\.?\s*e|[Vvol]|[Rr]col|maj|Lt|[Ff]ig|[Ff]igs|[Vv]iz|[Vv]ols|[Aa]pprox|[Ii]ncl|Pres|[Dd]ept|min|max|[Gg]ovt|lb|ft|c\.?\s*f|vs)\.\s))\Z/su',
        '/(?:(?:\b[Ee]tc\.\s))\Z/su',
        '/(?:(?:[\.!?…]+\p{Pe} )|(?:[\[\(]*…[\]\)]* ))\Z/su',
        '/(?:(?:\b\p{L}\.))\Z/su',
        '/(?:(?:\b\p{L}\.\s))\Z/su',
        '/(?:(?:\b[Ff]igs?\.\s)|(?:\b[nN]o\.\s))\Z/su',
        '/(?:(?:[\"”\']\s*))\Z/su',
        '/(?:(?:[\.!?…][\x{00BB}\x{2019}\x{201D}\x{203A}\"\'\p{Pe}\x{0002}]*\s)|(?:\r?\n))\Z/su',
        '/(?:(?:[\.!?…][\'\"\x{00BB}\x{2019}\x{201D}\x{203A}\p{Pe}\x{0002}]*))\Z/su',
        '/(?:(?:\s\p{L}[\.!?…]\s))\Z/su');
    $after_regexes = array('/\A(?:)/su',
        '/\A(?:[\p{N}\p{Ll}])/su',
        '/\A(?:[^\p{Lu}])/su',
        '/\A(?:[^\p{Lu}]|I)/su',
        '/\A(?:[^p{Lu}])/su',
        '/\A(?:\p{Ll})/su',
        '/\A(?:\p{L}\.)/su',
        '/\A(?:\p{L}\.\s)/su',
        '/\A(?:\p{N})/su',
        '/\A(?:\s*\p{Ll})/su',
        '/\A(?:)/su',
        '/\A(?:\p{Lu}[^\p{Lu}])/su',
        '/\A(?:\p{Lu}\p{Ll})/su');
    $is_sentence_boundary = array(false, false, false, false, false, false, false, false, false, false, true, true, true);
    $count = 13;

    $sentences = array();
    $sentence = '';
    $before = '';
    $after = substr($text, 0, 10);
    $text = substr($text, 10);

    while($text != '') {
        for($i = 0; $i < $count; $i++) {
            if(preg_match($before_regexes[$i], $before) && preg_match($after_regexes[$i], $after)) {
                if($is_sentence_boundary[$i]) {
                    array_push($sentences, $sentence);
                    $sentence = '';
                }
                break;
            }
        }

        $first_from_text = $text[0];
        $text = substr($text, 1);
        $first_from_after = $after[0];
        $after = substr($after, 1);
        $before .= $first_from_after;
        $sentence .= $first_from_after;
        $after .= $first_from_text;
    }

    if($sentence != '' && $after != '') {
        array_push($sentences, $sentence.$after);
    }

    return $sentences;
}
function numU($input){ 
  $result=preg_match('/^[0-9_ #$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]+$/i', $input); 
  return $result;
}