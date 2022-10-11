<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Kptromanization extends Model
{
    function romanization_api_curl_request($source_text){
		if($source_text !=""){				 
				 
				 $target_text = mb_convert_encoding($source_text, "UTF-8");
				
				$post_data = json_encode(array("input_text"=>$target_text));				
				$curl = curl_init();
				curl_setopt_array($curl, array(
				CURLOPT_URL => "https://dev.transflowtms.com/romanizationapi",
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
}
