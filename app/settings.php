<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class settings extends Model
{
    protected $table ="settings";
    protected $fillable = ['id','key','description'];
    public $timestamps	= false;
    protected $primaryKey = 'id';
}
