<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loc_languages extends Model
{
    protected $table="loc_languages";
    protected $fillable = ['lang_id','lang_name','lang_code','lang_status'];
    protected $primaryKey = 'lang_id';
    public $timestamps	= false;
}
