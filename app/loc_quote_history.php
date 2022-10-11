<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use HasFactory;
class loc_quote_history extends Model
{
  
    protected $table="loc_quote_history";
    protected $fillable = ['quote_id','file_name'];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
