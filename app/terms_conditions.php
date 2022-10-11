<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class terms_conditions extends Model
{
    protected $table ="terms_conditions";
    protected $fillable = ['template_name','description'];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
