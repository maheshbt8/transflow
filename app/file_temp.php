<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class file_temp extends Model
{
    protected $table="file_temp";
    protected $fillable = ['id','file_tr_id ','source_text ','target_text ','created_by'];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
