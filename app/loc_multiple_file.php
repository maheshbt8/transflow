<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loc_multiple_file extends Model
{
    protected $table="loc_request_files";
    protected $fillable = ['req_id','source_language','file_name','source_type','source_text'];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
