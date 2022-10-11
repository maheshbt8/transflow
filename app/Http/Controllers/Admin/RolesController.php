<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRolesRequest;
use App\Http\Requests\Admin\UpdateRolesRequest;
use App\models\Role_permissions;

class RolesController extends Controller
{
    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! checkpermission('roles')) {
            return abort(401);
        }

        $roles = Role::all();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! checkpermission('roles','add')) {
            return abort(401);
        }
        //$permissions = Permission::get()->pluck('id', 'name');
        //$permissions = Permission::get();
        $parent_list=Permission::where('type','=','parent')->get();
        $parent_per=array();
        foreach($parent_list as $per){
          $per['childs']=Permission::where('type','=',$per->id)->get();
          $parent_per[]=$per;
        }
        return view('admin.roles.create')->with(['permissions'=>$parent_per]);
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param  \App\Http\Requests\StoreRolesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRolesRequest $request)
    {
        if (! checkpermission('roles')) {
            return abort(401);
        }
        
        /*echo "<pre/>";
        print_r($permissions);
        echo "<pre/>";
        print_r($request->view_permissions);
        print_r($request->add_permissions);
        print_r($request->update_permissions);
        print_r($request->delete_permissions);
        die;*/
        $role = Role::create($request->except('permission'));
        $role_id=$role->id;

        //$permissions = $request->input('permission') ? $request->input('permission') : [];
        $view_permissions = $request->input('view_permissions') ? $request->input('view_permissions') : [];
        $add_permissions = $request->input('add_permissions') ? $request->input('add_permissions') : [];
        $update_permissions = $request->input('update_permissions') ? $request->input('update_permissions') : [];
        $delete_permissions = $request->input('delete_permissions') ? $request->input('delete_permissions') : [];

        $permissions = Permission::all();
        foreach ($permissions as $per) {
            if(in_array($per->id, $add_permissions) || in_array($per->id, $view_permissions) || in_array($per->id, $update_permissions) || in_array($per->id, $delete_permissions))
            {
                $roleper=new Role_permissions;
                $roleper->role_id=$role_id;
                $roleper->permission_id=$per->id;
                $roleper->add=((isset($add_permissions[$per->id]) && $add_permissions[$per->id] != '')? '1' : '0');
                $roleper->view=((isset($view_permissions[$per->id]) && $view_permissions[$per->id] != '')? '1' : '0');
                $roleper->update=((isset($update_permissions[$per->id]) && $update_permissions[$per->id] != '')? '1' : '0');
                $roleper->delete=((isset($delete_permissions[$per->id]) && $delete_permissions[$per->id] != '')? '1' : '0');
                $roleper->save();
            }
        }
        //$role->givePermissionTo($permissions);

        return redirect()->route('admin.roles.index');
    }


    /**
     * Show the form for editing Role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        if (! checkpermission('roles','update')) {
            return abort(401);
        }
        /*$permissions = Permission::get()->pluck('name', 'name');*/
        $parent_list=Permission::where('type','=','parent')->get();
        $parent_per=array();
        foreach($parent_list as $per){
          $per['childs']=Permission::where('type','=',$per->id)->get();
          $parent_per[]=$per;
        }
        $permissions=$parent_per;
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update Role in storage.
     *
     * @param  \App\Http\Requests\UpdateRolesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRolesRequest $request, Role $role)
    {
        if (! checkpermission('roles','update')) {
            return abort(401);
        }
        $view_permissions = $request->input('view_permissions') ? $request->input('view_permissions') : [];
        $add_permissions = $request->input('add_permissions') ? $request->input('add_permissions') : [];
        $update_permissions = $request->input('update_permissions') ? $request->input('update_permissions') : [];
        $delete_permissions = $request->input('delete_permissions') ? $request->input('delete_permissions') : [];
        
        $role_id=$request->get('role_id');
        $permissions = Permission::all();

        foreach ($permissions as $per) {
      $checkper=Role_permissions::where(['permission_id'=>$per->id,'role_id'=>$role_id])->first();
      if($checkper){
        $roleper['add']=((isset($add_permissions[$per->id]) && $add_permissions[$per->id] != '')? '1' : '0');
        $roleper['view']=((isset($view_permissions[$per->id]) && $view_permissions[$per->id] != '')? '1' : '0');
        $roleper['update']=((isset($update_permissions[$per->id]) && $update_permissions[$per->id] != '')? '1' : '0');
        $roleper['delete']=((isset($delete_permissions[$per->id]) && $delete_permissions[$per->id] != '')? '1' : '0');
        // print_r($roleper);die;
        $update=Role_permissions::where(['permission_id'=>$per->id,'role_id'=>$role_id])->update($roleper);
    }else{
      if(in_array($per->id, $add_permissions) || in_array($per->id, $view_permissions) || in_array($per->id, $update_permissions) || in_array($per->id, $delete_permissions))
      {
        $roleper=new Role_permissions;
        $roleper->permission_id=$per->id;
        $roleper->role_id=$role_id;
        $roleper->add=((isset($add_permissions[$per->id]) && $add_permissions[$per->id] != '')? '1' : '0');
        $roleper->view=((isset($view_permissions[$per->id]) && $view_permissions[$per->id] != '')? '1' : '0');
        $roleper->update=((isset($update_permissions[$per->id]) && $update_permissions[$per->id] != '')? '1' : '0');
        $roleper->delete=((isset($delete_permissions[$per->id]) && $delete_permissions[$per->id] != '')? '1' : '0');
        $roleper->save();
        }
        }
    }
        /*$role->update($request->except('permission'));
        $permissions = $request->input('permission') ? $request->input('permission') : [];
        $role->syncPermissions($permissions);*/

        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        if (! checkpermission('roles')) {
            return abort(401);
        }

        $role->load('permissions');

        return view('admin.roles.show', compact('role'));
    }


    /**
     * Remove Role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if (! checkpermission('roles','delete')) {
            return abort(401);
        }

        $role->delete();

        return redirect()->route('admin.roles.index');
    }

    /**
     * Delete all selected Role at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        Role::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }

}
