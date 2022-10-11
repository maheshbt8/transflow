<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class my_model extends Model
{
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
		
        
		die; 
        //SET @p0='hhhhh'; SET @p1='A'; SET @p2='1'; SET @p3='2014-08-14 00:00:00.000000'; SET @p4='1'; 
        //CALL `xp_context_insert`(@p0, @p1, @p2, @p3, @p4, @p5, @p6); 
        //SELECT @p5 AS `output`, @p6 AS `lastid`;
        
        
        if($output_res == TRUE){
            
            if(is_string($output)){
              $output_query = '@'.$output;
            //   $result = $this->db->query("select $output_query as res_output");
            //   $sp_res=$result->row(); 
            //   $output_res = $sp_res->res_output;
            //  // print_r($output_res);  exit; 
            //   return $output_res;
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
}
