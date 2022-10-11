<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class quotegeneration_model extends Model
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
    public function call_sp($sp_name = NULL, $input_array = NULL, $output = NULL, $output_res = FALSE){
	
		$query_results = array();
       
	   if($sp_name == NULL)
            return FALSE;        
        if($input_array != NULL){
           $input_parameters= array();    
           foreach($input_array as $value){
               $input_parameters[]= "'".$value."'";
           }            
           $input_parameters_str = implode(',',$input_parameters);
        }
        
        if($output != NULL && is_string($output))
           $output_parameters = '@'.$output;
            
        
        if($output != NULL && is_array($output))
           $output_parameters = '@'.implode(',@',$output); 
        
        $query = "CALL $sp_name(";
        if(isset($input_parameters_str))
            $query .= $input_parameters_str;        
        if(isset($output_parameters))
            $query .= ','.$output_parameters;
        
        $query .=')';      
		
      //  $final_query = $this->db->query("$query");
        echo $query;
		die; 
        
        //SET @p0='hhhhh'; SET @p1='A'; SET @p2='1'; SET @p3='2014-08-14 00:00:00.000000'; SET @p4='1'; 
        //CALL `xp_context_insert`(@p0, @p1, @p2, @p3, @p4, @p5, @p6); 
        //SELECT @p5 AS `output`, @p6 AS `lastid`;
        
        
        if($output_res == TRUE){
            
            if(is_string($output)){
              $output_query = '@'.$output;
              $result = $this->db->query("select $output_query as res_output");
              $sp_res=$result->row(); 
              $output_res = $sp_res->res_output;
             // print_r($output_res);  exit; 
              return $output_res;
            }            
            
            if(is_array($output)){
              $output_query ='SELECT ';  
              foreach($output as $out_val){    
                $output_arr_list[] = '@'.$out_val." AS ".$out_val; 
              }
              $output_arr_str = implode(',',$output_arr_list);
              $output_query .= $output_arr_str;  
              $result = $this->db->query("$output_query");
              $sp_res=$result->result_array(); 
              //print_r($sp_res);   exit;
              return $sp_res;                  
            }
        }
        else{
           if ($final_query->num_rows() > 0) {
				if ($final_query->result() !="") {
                foreach ($final_query->result() as $row) {
                    $query_results[] = $row;
                }
			 }
               return $query_results;
           }
           return FALSE;
       }
    }
   
	
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
	
	
	
	
	
	/* PM Quote Generation Master listing function start here */
	function pm_quote_generation_listing(){			
			$output = $this->call_sp('xp_loc_pm_quote_generation_listing',NULL,NULL,FALSE);
            return $output;
	}
	/* Sales Quote Generation Master listing function end here */
	
	/* Sales Quote Generation Master listing function start here */
	function sales_quote_generation_listing($currentuser){			
			$output = $this->call_sp('xp_loc_sales_quote_generation_listing',array($currentuser),NULL,FALSE);
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
}
