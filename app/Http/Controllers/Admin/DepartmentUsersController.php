<?php

namespace App\Http\Controllers\Admin;
use App\User;
use Auth;
use App\user_orgizations;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use App\Kptorganization;
use App\KptSubOrganizations;
use App\KptDepartments;



class DepartmentUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (! checkpermission('department_users_manage')) {
            return abort(401);
        }

        $user_role = Auth::user()->roles[0]->name;	
		if($user_role == "administrator")
			$created_by="";
            elseif($user_role == "departmentadmin")
			$created_by="";
		else
			$created_by = Auth::user()->id;
		
		
		$users = User::getDepartmentUsers($created_by);
        return view('admin.departmentusers.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! checkpermission('department_users_manage','add',)) {
            return abort(401);
        }

        $roles = Role::get()->where('name', 'departmentadmin')->pluck('name','name');
		
		if(get_user_org('name') == "administrator"){
		$kptorganization    =   array();
		$kptorganization 	=   Kptorganization::get()->pluck('org_name', 'org_id');
		$kptsuborganization = 	array();
		$kptsuborganization = 	KptSubOrganizations::get()->pluck('sub_org_name', 'sub_org_id');		
		$KptDepartments 	=   KptDepartments::get()->pluck('department_name', 'department_id');	
        }
        else{
            $org_id=get_user_org('org','org_id');
            $kptorganization 	=   Kptorganization::get()->pluck('org_name', 'org_id');
            $kptsuborganization = 	array();
	    	$kptsuborganization = 	KptSubOrganizations::get()->pluck('sub_org_name', 'sub_org_id');
            $KptDepartments = KptDepartments::get()->pluck('department_name', 'department_id');
        }		
		
		return view('admin.departmentusers.create', compact('roles','kptorganization','kptsuborganization','KptDepartments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! checkpermission('department_users_manage','add')) {
            return abort(401);
        }		

		$this->validate($request, ['email' => ['required','email',Rule::unique('users')->ignore(Auth::user()->id)]]);
		$SubOrgUsers = User::create($request->all());			

		$roles = $request->input('roles') ? $request->input('roles') : [];	
		$SubOrgUsers->assignRole($roles);
		$user_id = $SubOrgUsers->id;
		
		$authenticated_userid = Auth::user()->id;
		if($authenticated_userid !=""){
			$arr_user_details = array("created_by"=>$authenticated_userid);
			User::where('id', $user_id)->update($arr_user_details);
		}

		
		
		/* User Organization mapping block */
		$user_org_result = user_orgizations::where ('user_id',Auth::user()->id)->get();
		
		$organization = $request->input('organization') ? $request->input('organization') : '';
		if($organization !=""){
			$org_id = $organization;
		}else {			
			$org_id = $user_org_result[0]->org_id;
		}
		
		
		$suborganization = $request->input('suborganization') ? $request->input('suborganization') : '';
		if($suborganization !=""){
			$sub_id = $suborganization;
		}else {			
			$sub_id = $user_org_result[0]->sub_id;	
		}		
			
		$department_id = $request->input('department') ? $request->input('department') : [];
		if($department_id !="" && $org_id !="") {
						
			$arr_user_organizations = array("user_id"=>$user_id,"org_id"=>$org_id,"sub_id"=>$sub_id,"sub_sub_id"=>$department_id);
			user_orgizations::create($arr_user_organizations);			
		}	
		/* User Organization mapping block */
		

		Session()->flash('message', 'User successfully added');
        return redirect()->route('admin.departmentusers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

	    if (! checkpermission('department_users_manage')) {
            return abort(401);
        }

		 $DepartmentUsers = User::find($id);
        return view('admin.departmentusers.show', compact('DepartmentUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! checkpermission('department_users_manage','update')) {
        }

		$DepartmentUsers = User::find($id);
		$kptorganization = Kptorganization::get()->pluck('org_name', 'org_id');
		/*$kptsuborganization = KptSubOrganizations::get()->pluck('sub_org_name', 'sub_org_id');	
		$KptDepartments = KptDepartments::get()->pluck('department_name', 'department_id');	*/
		$user_org_result = user_orgizations::where ('user_id',$id)->get();	

		if(isset($user_org_result[0]->org_id) && $user_org_result[0]->org_id != ''){
            $kptsuborganization = KptSubOrganizations::where('org_id',$user_org_result[0]->org_id)->get()->pluck('sub_org_name', 'sub_org_id');
        }else{
            $kptsuborganization=[];
        }

		if(isset($user_org_result[0]->sub_id) && $user_org_result[0]->sub_id != ''){
            $KptDepartments = KptDepartments::where('sub_org_id',$user_org_result[0]->sub_id)->get()->pluck('department_name', 'department_id');
        }else{
            $KptDepartments=[];
        }
		return view('admin.departmentusers.edit', compact('DepartmentUsers','kptorganization','kptsuborganization','KptDepartments','user_org_result'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if (! checkpermission('department_users_manage','update')) {
            return abort(401);
        }

		$this->validate($request, ['email' => ['required','email',Rule::unique('users')->ignore($id),  ]]);

		$data['name']  = $request['name'];
		$data['email'] = $request['email'];
		if($request['password'] !="")
			$data['password'] = bcrypt($request['password']);

         $DepartmentUsers = User::whereId($id)->update($data);


		$old_dept_id = $request["old_dept_id"];
		$user_id = $id;
		$department_id = $request->input('department_id') ? $request->input('department_id') : [];		
		$arr_user_organizations = array("sub_sub_id"=>$old_dept_id,"user_id"=>$user_id);		
		$user_org_result = user_orgizations::where($arr_user_organizations)->get();
		
		$organization = $request->input('organization') ? $request->input('organization') : '';
		$suborganization = $request->input('suborganization') ? $request->input('suborganization') : '';
		
		
		if($organization !="" && $suborganization !=""){
			$org_id = $organization;
			$sub_id = $suborganization;			
			$arr_user_organizations_update = array("org_id"=>$org_id,"sub_id"=>$sub_id,"sub_sub_id"=>$department_id);			
			user_orgizations::where('user_id', $user_id)->update($arr_user_organizations_update);
				
		}elseif(count($user_org_result)==0 && $department_id !="" ){			
			
			$arr_user_organizations_insert = array("user_id"=>$user_id,"sub_sub_id"=>$department_id);
			user_orgizations::create($arr_user_organizations_insert);		
			
		}else {
			if($department_id !=""){
				$arr_user_organizations_update = array("sub_sub_id"=>$department_id);			
				user_orgizations::where('user_id', $user_id)->update($arr_user_organizations_update);
			}else{				
				user_orgizations::destroy($user_id);
			}
		}
		
		Session()->flash('message', 'User successfully updated');
		 return redirect()->route('admin.departmentusers.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! checkpermission('department_users_manage','delete')) {
            return abort(401);
        }

		 $SubOrgUsers = User::findOrFail($id);
         $SubOrgUsers->delete();
			
		Session()->flash('message', 'User successfully deleted');
        return redirect()->route('admin.departmentusers.index');
    }




	/**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! checkpermission('department_users_manage','delete')) {
            return abort(401);
        }

        User::whereIn('id', request('ids'))->delete();
		Session()->flash('message', 'User successfully deleted');
        return response()->noContent();
    }
}
