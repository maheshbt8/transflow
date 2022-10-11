<?php

namespace App\Http\Controllers;

use App\KptDepartments;
use App\Kptorganization;
use App\KptSubOrganizations;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Log;

class KptDepartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! checkpermission('departments_manage')) {
            return abort(401);
        }
        $user_role = Auth::user()->roles[0]->name;	
        if($user_role == "administrator"){
        $KptDepartments = KptDepartments::select('kptdepartments.created_by as createduser','kptdepartments.*')->where(['department_status'=>'1'])->get();
        }
		elseif($user_role =="orgadmin"){
			$created_by = Auth::user()->id;
            $org_id=get_user_org('org','org_id');
            $KptDepartments =KptDepartments::select('kptdepartments.created_by as createduser','kptdepartments.*','kptsuborganizations.*','kptorganizations.*')
            ->join('kptsuborganizations', 'kptsuborganizations.sub_org_id','=','kptdepartments.sub_org_id')
            ->join('kptorganizations', 'kptsuborganizations.org_id','=','kptorganizations.org_id')
            ->where(['kptdepartments.department_status'=>'1','kptorganizations.org_id'=>$org_id])
             ->get();
        }
		else{
			$created_by = Auth::user()->id;
            $sub_org_id=get_user_org('org','sub_id');
            $KptDepartments =KptDepartments::select('kptdepartments.created_by as createduser','kptdepartments.*','kptsuborganizations.*')
            ->join('kptsuborganizations', 'kptsuborganizations.sub_org_id','=','kptdepartments.sub_org_id')
            ->where(['kptdepartments.department_status'=>'1','kptsuborganizations.sub_org_id'=>$sub_org_id])
             ->get();
        }
        
	
		$user  = new user;
        return view('kpt.departments.index',compact('KptDepartments','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! checkpermission('departments_manage')) {
            return abort(401);
        }
        if(get_user_org('name') == "administrator"){
        $orgs = Kptorganization::where('org_status',1)->get();
        $kptorganization 	=   Kptorganization::get()->pluck('org_name', 'org_id');
            $orgs = Kptorganization::where('org_status',1)->get();
            $kptsuborganization = 	KptSubOrganizations::get()->pluck('sub_org_name', 'sub_org_id');
        }else{
            $org_id=get_user_org('org','org_id');
            $kptorganization 	=   Kptorganization::get()->pluck('org_name', 'org_id');
            $orgs = Kptorganization::where('org_status',1)->get();
            $kptsuborganization = 	KptSubOrganizations::where('org_id',$org_id)->get()->pluck('sub_org_name', 'sub_org_id');
        }
    
        return view('kpt.departments.create',compact('orgs','kptorganization','kptsuborganization'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! checkpermission('departments_manage')) {
            return abort(401);
        }
        $validatedData = $request->validate([
            'department_name' => 'required|unique:kptdepartments',
            'organization' => 'required',
            'sub_organization' => 'required',
        ]);

		$data['created_by'] = Auth::user()->id;
        $data['department_name'] = $request['department_name'];
        $data['sub_org_id'] = $request['sub_organization'];
        $dept = KptDepartments::firstOrCreate (
            ['department_name' => $data['department_name'],'sub_org_id' => $data['sub_org_id'],'created_by' => $data['created_by']]
        );
		Session()->flash('message', 'Department successfully added');
		 return redirect()->route('admin.departments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\KptDepartments  $kptDepartments
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! checkpermission('departments_manage')) {
            return abort(401);
        }
		
		$KptDepartments = KptDepartments::find($id);
		$user  = new user;
        return view('kpt.departments.show', compact('KptDepartments','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KptDepartments  $kptDepartments
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! checkpermission('departments_manage','update')) {
            return abort(401);
        }
		
		$KptDepartments = KptDepartments::find($id);
        $org_id=KptSubOrganizations::getOrgbySubOrgid($KptDepartments->sub_org_id);
		$orgs = Kptorganization::where('org_status',1)->get();

        $sub_orgs = KptSubOrganizations::where(['sub_org_status'=>1,'org_id'=>$org_id])->get();
		return view('kpt.departments.edit', compact('KptDepartments','org_id','orgs','sub_orgs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KptDepartments  $kptDepartments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $dept_id)
    {
        if (! checkpermission('departments_manage','update')) {
            return abort(401);
        }		
		
		$KptDepartments = $request->validate([
         'department_name' => ['required', Rule::unique('kptdepartments')->ignore($dept_id,'department_id')],
            'organization' => 'required',
            'sub_organization' => 'required',
        ]);

        $KptDepartments =['department_name' => $request['department_name'],'sub_org_id' => $request['sub_organization'],'department_id' => $dept_id];
		 KptDepartments::where('department_id', $dept_id)->update($KptDepartments);
		 
		 Session()->flash('message', 'Department successfully updated');
		 return redirect()->route('admin.departments.index');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KptDepartments  $kptDepartments
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! checkpermission('departments_manage','delete')) {
            return abort(401);
        }
		
		$KptDepartments = KptDepartments::findOrFail($id);
        $KptDepartments->delete();
		Session()->flash('message', 'Department successfully deleted');
        return redirect('admin/departments')->with('success', 'Department deleted successfully');
    }
	
	
	
	/**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! checkpermission('departments_manage','delete')) {
            return abort(401);
        }	
		 
         $arr_request_ids   = request('ids');
		 $session_userid 	= Auth::user()->id;
		 foreach($arr_request_ids as $dept_id){
			 $deptid 		= $dept_id['department_id'];
			 $created_by 	= $dept_id['created_by'];
			 
			if($session_userid != $created_by ){
				Session()->flash('error_message', 'You are not created these Department(s). Department can not deleted. ');
				return response()->noContent();
			}			 
		 }
		 
		 
		 KptDepartments::whereIn('department_id', request('ids'))->delete();
		 Session()->flash('error_message', 'Department successfully deleted');
         return response()->noContent();
    }

    public function departments_suborg(Request $request)
    {
        $sub_orgs=KptDepartments::where(['sub_org_id'=>$request->input('sub_org_id'),'department_status'=>1])->get();
        echo '<option value="">Select Department</option>';
        foreach ($sub_orgs as $key => $sub_org) {
            echo '<option value="'.$sub_org->department_id.'">'.$sub_org->department_name.'</option>';
        }
    }
}
