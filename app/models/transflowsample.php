<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class transflowsample extends Model
{
   
	 public $upload_excel_config = array(
        'upload_path'   =>'assets/uploadfiles/',
        'allowed_types' =>'*',
        'max_size' => 10000
    );
		
	function __construct() {
		parent::__construct();
		$this->load->database();
	}//end __construct()        
    
    /* function decryption */
    // function decrypt($input){      
    //     $this->iv = mcrypt_create_iv(32);
    //     return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->securekey, base64_decode($input), MCRYPT_MODE_ECB, $this->iv));
    // }/* end decryption */
	
		
}

