<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class locService extends Model
{
    protected $table="loc_service";
    public $timestamps=false;
    protected $fillable = ['service_type'];
    protected $primaryKey = 'id';
}
