<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class file_translation_memory extends Model
{
    protected $table="file_translation_memory";
    protected $fillable = ['id','domain_id','project_name','source_lang_id','target_lang_id','translation_files_name','files_name','word_count','repeated_words','org_id','user_id','created_by','loc_file_id'];
    protected $primaryKey = 'id';
    public $timestamps	= false;
}
