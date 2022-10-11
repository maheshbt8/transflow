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



class SubOrgUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (! checkpermission('sub_org_users_manage')) {
            return abort(401);
        }
		
		$user_role = Auth::user()->roles[0]->name;	
		if($user_role == "administrator")
			$created_by="";
            // elseif($user_role == "suborgadmin")
			// $created_by="";
		else
			$created_by = Auth::user()->id;

        $users = User::getSubOrgUsers($created_by);
        return view('admin.suborgusers.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! checkpermission('sub_org_users_manage','add')) {
            return abort(401);
        }

        $roles = Role::get()->where('name', 'suborgadmin')->pluck('name','name');
        if(get_user_org('name') == "administrator"){
            $kptsuborganization = KptSubOrganizations::get()->pluck('sub_org_name', 'sub_org_id');
        }else{
            $org_id=get_user_org('org','org_id');
            $kptsuborganization = KptSubOrganizations::where('org_id',$org_id)->get()->pluck('sub_org_name', 'sub_org_id');
        }
		
		$kptorganization = array();
		$kptorganization = Kptorganization::get()->pluck('org_name', 'org_id');
					
		return view('admin.suborgusers.create', compact('roles','kptsuborganization','kptorganization'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		if (! checkpermission('sub_org_users_manage')) {
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
		$organization = $request->input('organization') ? $request->input('organization') : '';
		if($organization !=""){
			$org_id = $organization;
		}else {
			$user_org_result = user_orgizations::where ('user_id',Auth::user()->id)->get();
			$org_id = $user_org_result[0]->org_id;
		}
		
		$suborganization_id = $request->input('suborganization') ? $request->input('suborganization') : [];
		if($suborganization_id !="" && $org_id !="") {
						
			$arr_user_organizations = array("user_id"=>$user_id,"org_id"=>$org_id,"sub_id"=>$suborganization_id);
			user_orgizations::create($arr_user_organizations);			
		}	
		/* User Organization mapping block */
		

		Session()->flash('message', 'User successfully added');
        return redirect()->route('admin.suborgusers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if (! checkpermission('sub_org_users_manage')) {
            return abort(401);
        }

		 $SubOrgUsers = User::find($id);
        return view('admin.suborgusers.show', compact('SubOrgUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! checkpermission('sub_org_users_manage','update')) {
            return abort(401);
        }

		$SubOrgUsers = User::find($id);
		$kptorganization = array();
		$kptorganization = Kptorganization::get()->pluck('org_name', 'org_id');	
		$user_org_result = user_orgizations::where ('user_id',$id)->get();
        if(isset($user_org_result[0]->org_id) && $user_org_result[0]->org_id != ''){
            $kptsuborganization = KptSubOrganizations::where('org_id',$user_org_result[0]->org_id)->get()->pluck('sub_org_name', 'sub_org_id');
        }else{
            $kptsuborganization=[];
        }
		return view('admin.suborgusers.edit', compact('SubOrgUsers','kptorganization','kptsuborganization','user_org_result'));

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

		if (! checkpermission('sub_org_users_manage','update')) {
            return abort(401);
        }


		$this->validate($request, ['email' => ['required','email',Rule::unique('users')->ignore($id),  ]]);

		$data['name']  = $request['name'];
		$data['email'] = $request['email'];
		if($request['password'] !="")
			$data['password'] = bcrypt($request['password']);

         $SubOrgUsers = User::whereId($id)->update($data);


		$old_sub_id = $request["old_sub_id"];
		$user_id = $id;
		$suborganization_id = $request->input('suborganization') ? $request->input('suborganization') : [];	

		$org_id="";
		$organization = $request->input('organization') ? $request->input('organization') : '';
		if($organization !=""){
			$org_id = $organization;
		}
		
		
		$arr_user_organizations = array("sub_id"=>$old_sub_id,"user_id"=>$user_id);		
		$user_org_result = user_orgizations::where($arr_user_organizations)->get();
		if($org_id !=""){
			
			$arr_user_organizations_update = array("org_id"=>$org_id,"sub_id"=>$suborganization_id);
			user_orgizations::where('user_id', $user_id)->update($arr_user_organizations_update);		
			
		}elseif(count($user_org_result)==0 && $suborganization_id !="" ){			
			
			$arr_user_organizations_insert = array("user_id"=>$user_id,"sub_id"=>$suborganization_id);
			user_orgizations::create($arr_user_organizations_insert);		
			
		}else {
			if($suborganization_id !=""){
				$arr_user_organizations_update = array("sub_id"=>$suborganization_id);			
				user_orgizations::where('user_id', $user_id)->update($arr_user_organizations_update);
			}else{				
				user_orgizations::destroy($user_id);
			}
		}
		
		 return redirect()->route('admin.suborgusers.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		if (! checkpermission('sub_org_users_manage','delete')) {
            return abort(401);
        }

		 $SubOrgUsers = User::findOrFail($id);
         $SubOrgUsers->delete();

        return redirect()->route('admin.suborgusers.index');

    }




	/**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! checkpermission('sub_org_users_manage','delete')) {
            return abort(401);
        }

        User::whereIn('id', request('ids'))->delete();
        return response()->noContent();
    }
}
