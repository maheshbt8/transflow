<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loc_request_comment_child extends Model
{
    protected $table ="loc_comment_child";
    protected $fillable = ['comment_id','target_text','target_file','req_lang_id'];
    public $timestamps	= false;
    protected $primaryKey = 'id';
}
