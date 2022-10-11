<?php

namespace App\Http\Controllers\languages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\loc_languages;

class LanguagesController extends Controller
{
    public function index(){

        //echo "hai";die;
        //     if (! checkpermission('authenticate_user_manage')) {
        //        return abort(401);
        
        //    }
           $languages= loc_languages::get();
           
          
           return view('language.index',compact('languages'));
        }
        public function create(){
            return view('language.index',compact('currencies'));

        }

        public function store(Request $request){
             $this->validate($request, ['lang_code' => ['required']]);
             $this->validate($request, ['lang_name' => ['required']]);
          
                $languages = new loc_languages;
                $languages->lang_code = $request->input('lang_code');
                $languages->lang_name = $request->input('lang_name');
                $languages->lang_status = 'ACTIVE';
                
                $languages->save();
              
                return redirect()->route('admin.languages.index');
        }
        public function show()
        {

        }
        public function update(Request $request){
           
            // if (! checkpermission('authenticate_user_manage')) {
            //     return abort(401);
            // }
            
          
        $this->validate($request, ['lang_name' => ['required']]);
        $this->validate($request, ['lang_code' => ['required']]);
        $this->validate($request, ['lang_status' => ['required']]);
            $data=array(
                "lang_name" => $request->input('lang_name'),
                "lang_code" => $request->input('lang_code'),
                "lang_status"=>$request->input('lang_status')
            );
           $id=$request->input('id');
            $currency_update = loc_languages::where('lang_id', $id)->update($data);
            return redirect()->route('admin.languages.index');
        }
}
