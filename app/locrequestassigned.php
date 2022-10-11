<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class locrequestassigned extends Model
{
    protected $table="loc_request_assigned";
    protected $fillable = ['req_id','loc_source_id','target_language','tr_id','qa_id','pr_id','tr_assigned_date','qa_assigned_date','pr_assigned_date'];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
