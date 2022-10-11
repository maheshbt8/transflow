<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KptDepartments extends Model
{
    protected $table = "kptdepartments";
    protected $fillable = ['department_name', 'sub_org_id', 'department_status', 'created_by', 'created_at','updated_at'];
    protected $primaryKey = 'department_id';
}
