<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Auth;
use App\user_orgizations;
use Spatie\Permission\Models\Role;
use App\Kptorganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of User.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! checkpermission('administrator')) {
            return abort(401);
        }
        $users = User::getadminUsers();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! checkpermission('administrator','update')) {
            return abort(401);
        }
		
        $roles = Role::select('id','label','name')->where('name', '=', 'administrator')->get();		
		$kptorganization = Kptorganization::get()->pluck('org_name', 'org_id');
        return view('admin.users.create', compact('roles','kptorganization'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUsersRequest $request)
    {
        if (! checkpermission('administrator')) {
            return abort(401);
        }
		
		$this->validate($request, ['email' => ['required','email',Rule::unique('users')->ignore(Auth::user()->id)]]);
        	
		
		$user = User::create($request->all());		
		$organization_id = $request["organization"];		
		
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->assignRole($roles);
		$user_id = $user->id;
		$authenticated_userid = Auth::user()->id;
		if($authenticated_userid !=""){
			$arr_user_details = array("created_by"=>$authenticated_userid);
			User::where('id', $user_id)->update($arr_user_details);
		}
		
		if($organization_id !="") {			
			$arr_user_organizations = array("user_id"=>$user_id,"org_id"=>$organization_id);
			user_orgizations::create($arr_user_organizations);			
		}		
        		
		 Session()->flash('message', 'User successfully added');
         return redirect()->route('admin.users.index');
    }


    /**
     * Show the form for editing User.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (! checkpermission('administrator','update')) {
            return abort(401);
        }
        
		//$roles = Role::where('name', '=', 'administrator')->get()->pluck('name', 'name');
        $roles = Role::select('id','label','name')->where('name', '=', 'administrator')->get();
		$kptorganization = Kptorganization::get()->pluck('org_name', 'org_id');
		$user_id = $user->id;
		
		$arr_user_organizations = array("user_id"=>$user_id);		
		$user_org_result = user_orgizations::where ('user_id',$user_id)->get();
		
				
        		
		return view('admin.users.edit', compact('user', 'roles','kptorganization','user_org_result'));
    }

    /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUsersRequest $request, User $user)
    {
        if (! checkpermission('administrator')) {
            return abort(401);
        }
		
		
	$this->validate($request, ['email' => ['required','email',Rule::unique('users')->ignore($user->id),  ]]);

        $user->update($request->all());
        $roles = $request->input('roles') ? $request->input('roles') : [];
        $user->syncRoles($roles);
		
		
		$old_org_id = $request["old_org_id"];
		$user_id = $user->id;
		$organization_id = $request['organization'];
		
		$arr_user_organizations = array("org_id"=>$old_org_id,"user_id"=>$user_id);		
		$user_org_result = user_orgizations::where($arr_user_organizations)->get();		
		if(count($user_org_result)==0 && $organization_id !="" ){
			$created_at =new \DateTime();
			$arr_user_organizations_insert = array("user_id"=>$user_id,"org_id"=>$organization_id);
			user_orgizations::create($arr_user_organizations_insert);				
		}else {
			if($organization_id !=""){
				$arr_user_organizations_update = array("org_id"=>$organization_id);			
				user_orgizations::where('user_id', $user_id)->update($arr_user_organizations_update);
			}else{				
				user_orgizations::destroy($user_id);
			}
		}

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        if (! checkpermission('administrator')) {
            return abort(401);
        }

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (! checkpermission('administrator','delete')) {
            return abort(401);
        }

        $user->delete();

        return redirect()->route('admin.users.index');
    }

    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
		
		
        if (! checkpermission('administrator','delete')) {
            return abort(401);
        }
        User::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }

}
