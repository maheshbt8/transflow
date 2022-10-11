<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class client_user_orgizations extends Model
{
    protected $table;
    protected $fillable = ['user_id', 'org_id', 'sub_id', 'sub_sub_id','created_at','created_at'];
    protected $primaryKey = 'user_id';	
	
}
