<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    public static function financeUsers($org_id='')
    {
        if($org_id != ""){
			$finance = User::whereHas(
				'roles', function($q) {
					$q->where('name', 'vendor');
				}
			)->join('user_orgizations', 'users.id', '=', 'user_orgizations.user_id')
            ->join('vendor_invoice_details','user_orgizations.user_id','=','vendor_invoice_details.user_id')->where('user_orgizations.org_id',$org_id)->get();
        }else{
            $finance=''; 
        }
       return $finance;
    }
}
