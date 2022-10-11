<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class curriences extends Model
{
    protected $table="currencies";
    protected $fillable = ['id','currency_name','currency_symbol','currency_code','unit','Inr','status'];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
