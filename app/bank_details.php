<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class bank_details extends Model
{
    protected $table="bank_details";
    protected $fillable = ['id','bank_name','bank_address','account_name','account_number','routing_number','ifsc_code','swift_code','sort_code','bic','user_id','type',];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
