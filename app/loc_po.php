<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loc_po extends Model
{
    protected $table="loc_po";
    protected $fillable = ['req_id','po_file_path'];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
