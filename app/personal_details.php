<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class personal_details extends Model
{
    protected $table="personal_details";
    protected $fillable = ['id','state_code','pan','gst','register_no','user_id','type','pan_file_path','msme_registered','msme_file_path'];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}

