<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class clientDepartment extends Model
{
    protected $table = "client_departments";
    protected $fillable = ['client_dpt_name', 'client_suborg_id', 'client_dpt_status', 'created_by', 'created_at','updated_at'];
    protected $primaryKey = 'client_dpt_id';
}
