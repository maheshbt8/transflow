<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class clientSubOrganization extends Model
{
    protected $table="client_sub_organizations";
    protected $fillable = ['client_suborg_name', 'client_org_id','client_suborg_status', 'created_by', 'created_at','updated_at'];
    protected $primaryKey = 'client_suborg_id';
}
