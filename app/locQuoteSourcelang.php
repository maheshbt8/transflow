<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class locQuoteSourcelang extends Model
{
    protected $table='loc_quote_sourcelang';
    public $timestamps=false;
    protected $fillable = ['quote_id','request_id','sourcelang_id'];
    protected $primaryKey = 'id';
}
