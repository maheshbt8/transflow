<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class email_templets extends Model
{
    protected $table="email_template";
    protected $fillable = ['id','email_code','email_template','email_subject',];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
