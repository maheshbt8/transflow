<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientorganization extends Model
{
	protected $table;
    protected $fillable = ['org_name','kpt_org', 'org_status','user_type', 'created_by', 'created_at','updated_at'];
    protected $primaryKey = 'org_id';
}
