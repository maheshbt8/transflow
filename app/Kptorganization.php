<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kptorganization extends Model
{
	protected $table;
    protected $fillable = ['org_name', 'org_status', 'created_by', 'created_at','updated_at'];
    protected $primaryKey = 'org_id';
}
