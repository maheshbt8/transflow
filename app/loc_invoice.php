<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class loc_invoice extends Model
{
    protected $table="loc_invoice";
    protected $fillable = ['id'];
    protected $primaryKey = 'id';
    public $timestamps	= true;
}
