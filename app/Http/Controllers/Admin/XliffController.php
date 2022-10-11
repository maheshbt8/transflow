<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\KptDepartments;
use App\Kptorganization;
use App\KptSubOrganizations;
use App\User;
use App\user_orgizations;
use Auth;
use App\loc_languages;

class XliffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!checkpermission('xliff_segmentation')) {
            return abort(401);
        }

       $user_role = Auth::user()->roles[0]->name;
       if ($user_role == 'administrator') {
           $created_by = '';
       } else {
           $created_by = Auth::user()->id;
       }

       $users = User::getClientUser($created_by);
       //print_r($users);die;
       return view('admin.xlifesegmention.index', compact('users')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        if (!checkpermission('xliff_segmentation')) {
            return abort(401);
        }
        $user_role = Auth::user()->roles[0]->name;
        if ($user_role == 'administrator') {
            $created_by = '';
        } else {
            $created_by = Auth::user()->id;
        }

        $users = User::getClientUser($created_by);
        $loc_languages = loc_languages::orderBy('lang_name', 'ASC')->get();
        //print_r($users);die;
        return view('admin.xlifesegmention.create',compact('users','loc_languages')); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
