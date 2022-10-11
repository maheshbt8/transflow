<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSettings extends Model
{
    protected $table="loc_email_settings";
    protected $fillable = ['email_setting_id','email_code','email_template','email_subject','email_to_address','email_cc_address','email_bcc_address','email_org',];
    protected $primaryKey = 'email_setting_id';
    public $timestamps	= false;
}
