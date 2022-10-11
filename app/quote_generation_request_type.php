<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class quote_generation_request_type extends Model
{
    protected $table="quote_generation_request_type";
    protected $fillable = ['request_type','quote_generation_id'];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
