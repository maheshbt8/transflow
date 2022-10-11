<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loc_request_comment extends Model
{
    protected $table ="loc_request_comments";
    protected $fillable = ['comment','request_id','req_lang_id','created_time'];
    public $timestamps	= false;
    protected $primaryKey = 'id';
}
