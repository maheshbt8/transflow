<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class vendor_invoice_details extends Model
{
    protected $table="vendor_invoice_details";
    protected $fillable = ['id'];
    protected $primaryKey = 'id';
    public $timestamps	= true;
}
