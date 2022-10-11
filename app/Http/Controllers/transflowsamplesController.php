<?php

namespace App\Http\Controllers;

use App\KptSubOrganizations;
use App\Kptorganization;
use App\User;
use App\models\transflowsample;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Log;

class transflowsamplesController extends Controller
{
   
    public function index(){

        if(! checkpermission('transflow_samples'))
        {
            return abort(401);
        }
      
       
          return view("admin.transflowsamples.index");
   
    }
}
