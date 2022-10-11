<?php

namespace App\Http\Controllers;
use App\KptDepartments;
use App\Kptorganization;
use App\KptSubOrganizations;
use App\User;
use App\user_orgizations;
use Auth;
use App\xlifesegementaion;
use Illuminate\Http\Request;

class xlifesegmentionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        //print_r($users);die;
        return view('admin.xlifesegmention.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\xlifesegementaion  $xlifesegementaion
     * @return \Illuminate\Http\Response
     */
    public function show(xlifesegementaion $xlifesegementaion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\xlifesegementaion  $xlifesegementaion
     * @return \Illuminate\Http\Response
     */
    public function edit(xlifesegementaion $xlifesegementaion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\xlifesegementaion  $xlifesegementaion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, xlifesegementaion $xlifesegementaion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\xlifesegementaion  $xlifesegementaion
     * @return \Illuminate\Http\Response
     */
    public function destroy(xlifesegementaion $xlifesegementaion)
    {
        //
    }
}
