<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loc_ratecard extends Model
{
    protected $table ="loc_ratecard";
    protected $fillable = ['org_id','target_lang','price','source_lang','service_id','updated_by'];
    public $timestamps	= false;
    protected $primaryKey = 'id';

    public function get_target_price($organization,$service_type,$currency,$source_lang,$target_lang){
        return loc_ratecard::where(['source_lang'=>$source_lang,'servie_id'=>$service_type,'org_id'=>$organization,'target_lang'=>$target_lang,'currency_id'=>$currency])->first();
    }
}
