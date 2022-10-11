<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class address extends Model
{
    protected $table="address";
    protected $fillable = ['id','address','state_code','pan','gst','user_id','type',];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
