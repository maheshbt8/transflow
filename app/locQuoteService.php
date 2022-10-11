<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class locQuoteService extends Model
{
   protected $table='loc_quote_service';
   public $timestamps=false;
   protected $fillable = ['quote_id','loc_source_id','service_type'];
   protected $primaryKey = 'id';
}
