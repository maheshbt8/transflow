<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class quote_generation_lang_request extends Model
{
    protected $table="quote_generation_lang_request";
    protected $fillable = ['language_id','language_type','quote_generation_id'];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
