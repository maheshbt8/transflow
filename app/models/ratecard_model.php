<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ratecard_model extends Model
{
    function __construct() {
		parent::__construct();
		
	}//end __construct()        
    
    /* function decryption */
    function decrypt($input){      
        $this->iv = mcrypt_create_iv(32);
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->securekey, base64_decode($input), MCRYPT_MODE_ECB, $this->iv));
    }/* end decryption */
	
	
	/* Language master start here */
	function get_languagemaster_list() {           
			$output = $this->call_sp('xp_loc_languages_master_select1',NULL,NULL,FALSE);
            return $output;
	}
	/* Language master start here */
	
	/* Rate Card Master listing function start here */
	function ratecard_master_listing(){			
			$output = $this->call_sp('xp_loc_rate_card_master_listing',NULL,NULL,FALSE);
            return $output;
	}
	/* Rate Card Master listing function end here */

	
	/* Rate Card Master Target Language where condition start here */
	function ratecard_master_target_language_where_condition($target_language){		
		$output = $this->call_sp('xp_loc_rate_card_target_language_where_condition',array($target_language),NULL,FALSE);
            return $output;
		
		
	}
	/* Rate Card Master Target Language where condition start here */
	
	/* Edit Rate card start here */
	function ratecard_master_listing_with_ratecardid($ratecardid){
		
		$output = $this->call_sp('xp_loc_rate_card_master_listing_with_ratecardid',array($ratecardid),NULL,FALSE);
            return $output;		
	}
	
	
	
	/* Online Currency API start here */	
	function get_currency_api(){		
		$curl = curl_init();
					curl_setopt_array($curl, array(
					CURLOPT_URL =>"https://v3.exchangerate-api.com/bulk/76de08fc0bed927bfaed2f33/USD",
					CURLOPT_RETURNTRANSFER => true,			
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",					
					CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache"				
				),
				));
				
			$response = curl_exec($curl);
			$err = curl_error($curl);			
			curl_close($curl);			
			return $response;		
	}
	/* Online Currency API start here */	
	
	
	
	/* REmove array value start here */
	function array_remove_by_value($array, $value){
		return array_values(array_diff($array, array($value)));
	}
	/* REmove array value end here */
	
	
	
	
	
	/* Sales Quote Generation Master listing function start here */
	function sales_quote_generation_listing(){			
			$output = $this->call_sp('xp_loc_sales_quote_generation_listing',NULL,NULL,FALSE);
            return $output;
	}
	/* Sales Quote Generation Master listing function end here */
	
	
	
	/* Sales Quote Generation Child listing function start here */
	function sales_quote_generation_child_with_id_listing($quote_id){			
			$output = $this->call_sp('xp_loc_sales_quote_generation_child_with_id_listing',array($quote_id),NULL,FALSE);
            return $output;
	}
	/* Sales Quote Generation Child listing function end here */
	
	/* Sales Quote Generation Master with Id listing function start here */
	function sales_quote_generation_master_with_id_listing($quote_id){			
			$output = $this->call_sp('xp_loc_sales_quote_generation_master_with_id_listing',array($quote_id),NULL,FALSE);
            return $output;
	}
	/* Sales Quote Generation Master listing function end here */
	
	
	
	/* Sales Quote Generation Child with type listing function start here */
	function sales_quote_generation_child_with_type_id_listing($quote_id,$translationtype){			
			$output = $this->call_sp('xp_loc_sales_quote_generation_child_with_type_id_listing',array($quote_id,$translationtype),NULL,FALSE);
            return $output;
	}
	/* Sales Quote Generation Child with type listing function end here */
	
	
	
	
	
}

