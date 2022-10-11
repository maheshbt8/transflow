<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\models\Kptromanization;
use Illuminate\Support\Facades\Gate;
//use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\loc_languages;

class KptromanizationController extends Controller
{
    
    public function addRomanization(){
        if (! checkpermission('kpt_romanization')) {
            return abort(401);
        }

        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->get();
		return view('admin.kptromanization.text',compact('loc_languages'));
        


    }
    public function kptromanizationprocess(Request $request) {		
		if($_SERVER['REQUEST_METHOD'] == "POST"){
				
				
				$sourcetext =  $_POST['sourcetext'];
				
				$result_output= new Kptromanization();
                $resulting_output=$result_output->romanization_api_curl_request($sourcetext);
				echo $resulting_output;
				exit;	
		
		}else {
				print 'No Access to this Request Method.';
				exit;
		}
	}
}
