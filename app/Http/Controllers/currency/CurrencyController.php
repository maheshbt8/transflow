<?php

namespace App\Http\Controllers\currency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\currencies;

class CurrencyController extends Controller
{
    public function index(){

        
        //     if (! checkpermission('authenticate_user_manage')) {
        //        return abort(401);
        //    }
           $currencies= currencies::get();
           
          
           return view('currency.index',compact('currencies'));
        }
        public function create(){
            return view('currency.index',compact('currencies'));

        }

        public function store(Request $request){
             $this->validate($request, ['currency_name' => ['required']]);
             $this->validate($request, ['currency_symbol' => ['required']]);
             $this->validate($request, ['currency_code' => ['required']]);
            $validatedData = $request->validate([
                'currency_symbol' => 'required|unique:currencies',
                'currency_code' =>'required|unique:currencies',
            ]);

                $currency = new currencies;
                $currency->currency_name = $request->input('currency_name');
                $currency->currency_symbol = $request->input('currency_symbol');
                $currency->currency_code = $request->input('currency_code');
                $currency->save();
              
                return redirect()->route('admin.currency.index');
        }
        public function show()
        {

        }
        public function update(Request $request){
           
            // if (! checkpermission('authenticate_user_manage')) {
            //     return abort(401);
            // }
            
          
         $this->validate($request, ['unit' => ['required']]);
         $this->validate($request, ['inr' => ['required']]);
        $this->validate($request, ['status' => ['required']]);
            $data=array(
                "unit" => $request->input('unit'),
                "inr" => $request->input('inr'),
                "status"=>$request->input('status')
            );
           $id=$request->input('id');
            $currency_update = currencies::where('id', $id)->update($data);
            return redirect()->route('admin.currency.index');
        }
}
