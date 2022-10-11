<?php

namespace App\Http\Controllers\Admin;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionsRequest;
use App\Http\Requests\Admin\UpdatePermissionsRequest;

class PermissionsController extends Controller
{
    /**
     * Display a listing of Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! checkpermission('permissions')) {
            return abort(401);
        }

        //$permissions = Permission::all();
        $parent_list=Permission::where('type','=','parent')->get();
        $parent_per=array();
        foreach($parent_list as $per){
          $per['childs']=Permission::where('type','=',$per->id)->get();
          $parent_per[]=$per;
        }

        return view('admin.permissions.index')->with(['permissions'=>$parent_per]);
    }

    /**
     * Show the form for creating new Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! checkpermission('permissions','add')) {
            return abort(401);
        }
        $permissions = Permission::where('type','=','parent')->get();
        return view('admin.permissions.create', compact('permissions'));
    }

    /**
     * Store a newly created Permission in storage.
     *
     * @param  \App\Http\Requests\StorePermissionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermissionsRequest $request)
    {
        if (! checkpermission('permissions','add')) {
            return abort(401);
        }
        if($request->input('type') != 'parent'){
            $this->validate($request, ['name' => ['required']]);    
        }else{
            //$request->all()['name']='par_'.strtolower($request->input('label'));
            $request->request->add(['name' => 'par_'.str_replace(' ','_',strtolower($request->input('label')))]);
            //print_r($request->all());die;
        }
        

        Permission::create($request->all());

        return redirect()->route('admin.permissions.index');
    }


    /**
     * Show the form for editing Permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        if (! checkpermission('permissions','update')) {
            return abort(401);
        }
        $permissions = Permission::where('type','=','parent')->get();
        return view('admin.permissions.edit', compact('permission','permissions'));
    }

    /**
     * Update Permission in storage.
     *
     * @param  \App\Http\Requests\UpdatePermissionsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionsRequest $request, Permission $permission)
    {
        if (! checkpermission('permissions','update')) {
            return abort(401);
        }

        $permission->update($request->all());

        return redirect()->route('admin.permissions.index');
    }


    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        if (! checkpermission('permissions','delete')) {
            return abort(401);
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index');
    }

    public function show(Permission $permission)
    {
        if (! checkpermission('permissions')) {
            return abort(401);
        }

        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Delete all selected Permission at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
       
		
	   Permission::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }

}
