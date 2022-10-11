<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Role_permissions extends Model
{
    protected $table='role_has_permissions';
    public $timestamps	= false;
}
