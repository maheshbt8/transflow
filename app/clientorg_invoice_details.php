<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class clientorg_invoice_details extends Model
{
    protected $table="clientorg_invoice_details";
    protected $fillable = ['id'];
    protected $primaryKey = 'id';
    public $timestamps	= true;
}
