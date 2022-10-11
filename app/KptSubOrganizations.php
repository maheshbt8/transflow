<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KptSubOrganizations extends Model
{
    protected $table="kptsuborganizations";
    protected $fillable = ['sub_org_name', 'org_id','sub_org_status', 'created_by', 'created_at','updated_at'];
    protected $primaryKey = 'sub_org_id';
    public static function getOrgbySubOrgid($sub_org_id)
    {
    	$res=KptSubOrganizations::where('sub_org_id',$sub_org_id)->first();
    	if(!empty($res)){
    		return $res->org_id;
    	}
    }
}
