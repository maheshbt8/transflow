<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Translationmodel extends Model
{
    /*protected $table="marketing_campaign";
    protected $fillable = [];
    protected $primaryKey = 'mk_campign_id';*/
    public static function get_curl_translate_language($target_lanuages,$target_text){	
		$target_text = mb_convert_encoding($target_text, "UTF-8");
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://translate.yandex.net/api/v1.5/tr.json/translate?lang=".$target_lanuages."&key=trnsl.1.1.20190208T093045Z.db7d853fa0732a7b.f66900a575e8c4fcd0b5ada1d4aa6c6aded8dd75",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "text=".$target_text,
		  CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"content-type: application/x-www-form-urlencoded",			
		  ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		return $response;			
	}
	public static function checktableavailability($table_name)
	{
		if($table_name != ''){
			$res=Schema::connection('mysql2')->hasTable($table_name);
			if($res){
				return true;
			}
		}
		return false;
		
	}
	public static function view_translationmemory_webapi($table_name){	
				$webapi_url = "https://webapi.transflowtms.com/api/get?name=".$table_name;				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $webapi_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERPWD, "admin:tmserver");
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				$json_output = curl_exec($ch);
				$info = curl_getinfo($ch);
				curl_close($ch);
			return json_decode($json_output);
   }
   public static function create_table_request($table_name)
   {
   		$res=Translationmodel::checktableavailability($table_name);
   		if(!$res){
   			Schema::connection('mysql2')->create($table_name, function($table){
	            $table->increments('sid', 11);
	            $table->string('source_lang', 128);
	            $table->text('source_text');
	            $table->string('target_lang', 128);
	            $table->text('target_text');
	            $table->integer('domain_id')->unsigned()->default(1);
	            $table->integer('created_userid');
	            $table->dateTime('created_at');
	            $table->integer('updated_userid');
	            $table->dateTime('updated_at');
	            $table->index('source_lang');
	            $table->index('source_text');
	            $table->index('target_lang');
	            $table->index('target_text');
	            $table->index('created_userid');
	            $table->index('created_at');
	            $table->index('updated_userid');
	            $table->index('updated_at');
	            $table->index('domain_id');
        	});
        	 /*Schema::connection('mysql2')->table($table_name, function($table) {
				$table->foreign('domain_id')->references('id')->on('domains');
   			});*/
   		}
   		return true;
   	}
   	public static function checksourcetextavailability($table_name,$source_text)
   	{
   		return DB::connection('mysql2')->table($table_name)->where('source_text',$source_text)->count();
   	}
   	public static function add_update_translationmemory_webapi($arr_tm){
			  $json_tm_dump = json_encode($arr_tm);
			  
			  $curl = curl_init();
			  curl_setopt($curl, CURLOPT_URL ,"https://webapi.transflowtms.com/api/create");
			  curl_setopt($curl, CURLOPT_USERPWD, "admin:tmserver");
			  curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
			  curl_setopt($curl, CURLOPT_POSTFIELDS, $json_tm_dump);
			  
			 $response = curl_exec($curl);
			 $err = curl_error($curl);
			 curl_close($curl);

			if ($err) {
			  echo "cURL Error #:" . $err;
			} else {
			  //return $response;
			}
   	}  
   	public static function add_translationmemory_temp($arr_tm)
   	{
   		return DB::connection('mysql2')->table('tm_temp')->insert($arr_tm);
   	}
   	public static function get_targettext_by_source_tm($source_lang,$tareget_lang,$source_text,$domain='1'){
				$arr_tm = array();
				$arr_tm["source_lang"]  = $source_lang;
				$arr_tm["source_text"]  = $source_text;
				$arr_tm["tareget_lang"] = $tareget_lang;				
				$arr_tm["domain_id"] = $domain;				
				
				$json_tm_dump = json_encode($arr_tm);
				
				$webapi_url = "https://webapi.transflowtms.com/api/getbysource";				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $webapi_url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERPWD, "admin:tmserver");
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $json_tm_dump);
				
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				
				$json_output = curl_exec($ch);
				$info = curl_getinfo($ch);
				$err = curl_error($ch);
				
				curl_close($ch);
			return json_decode($json_output);
   }   
   public static function nntranslation($query)
	{
		$arr_tm = array();
		$arr_tm["query"]  = $query;
		$json_tm_dump = json_encode($arr_tm);
		$webapi_url = "https://webapp.transflowtms.com/neuralnetwork";				
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_URL, $webapi_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_tm_dump);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$info = curl_getinfo($ch);
		//$err = curl_error($ch);
		if(curl_errno($ch))
		{
			//echo 'Curl error:'.curl_error($ch);die;
			return '';
			/*$arr_tm["TranslatedText"]  = '';
			$json_output = json_encode($arr_tm);
			print_r($json_output);die;
*/		}else{
			$json_output = curl_exec($ch);
		}
		curl_close($ch);
		$res=json_decode($json_output);
		if(isset($res->TranslatedText) && $res->TranslatedText != ''){
			return $res->TranslatedText;
		}
		return '';
		/*if($err){
			$arr_tm["TranslatedText"]  = '';
			$json_output = json_encode($arr_tm);
			print_r($json_output);die;
		}*/
		
	}
}
