<?php

namespace App\Http\Controllers;

use App\KptSubOrganizations;
use App\Kptorganization;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Log;

class KptSubOrganizationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! checkpermission('sub_organization_manage')) {
            return abort(401);
        }
        $user_role = Auth::user()->roles[0]->name;	
		if($user_role == "administrator"){
        $KptSubOrganizations = KptSubOrganizations::where(['sub_org_status'=>'1'])->get();
		}else{
			$created_by = Auth::user()->id;
            $org_id=get_user_org('org','org_id');
        $KptSubOrganizations = KptSubOrganizations::where(['sub_org_status'=>'1','org_id'=>$org_id])->get();
        }			
		$user  = new user;
        return view('kpt.suborganizations.index',compact('KptSubOrganizations','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! checkpermission('sub_organization_manage','add')) {
            return abort(401);
        }
        $orgs = Kptorganization::where('org_status',1)->get();
        return view('kpt.suborganizations.create',compact('orgs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        		
		if (! checkpermission('sub_organization_manage')) {
            return abort(401);
        }
        $validatedData = $request->validate([
            'sub_org_name' => 'required',
            'organization' => 'required',
        ]);
				
        $data['created_by'] = Auth::user()->id;
        $data['sub_org_name'] = $request['sub_org_name'];
        $data['org_id'] = $request['organization'];
        $sub_org = KptSubOrganizations::firstOrCreate (
            ['sub_org_name' => $data['sub_org_name'],'org_id' => $data['org_id'],'created_by' => $data['created_by']]
        );
        
		Session()->flash('message', 'Sub Organization successfully added');
		return redirect()->route('admin.suborganizations.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {		
		if (! checkpermission('sub_organization_manage')) {
            return abort(401);
        }
		
		 
		$KptSubOrganizations = KptSubOrganizations::find($id);
		$user  = new user;
        return view('kpt.suborganizations.show', compact('KptSubOrganizations','user'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        if (! checkpermission('sub_organization_manage','update')) {
            return abort(401);
        }
		
		$KptSubOrganizations = KptSubOrganizations::find($id);
        $orgs = Kptorganization::where('org_status',1)->get();
		return view('kpt.suborganizations.edit', compact('KptSubOrganizations','orgs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $sub_org_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $sub_org_id)
    {
        if (! checkpermission('sub_organization_manage','update')) {
            return abort(401);
        }
		
		
		$KptSubOrganizations = $request->validate([
         'sub_org_name' => ['required', Rule::unique('kptsuborganizations')->ignore($sub_org_id,'sub_org_id')],
            'organization' => 'required',
        ]);
        $KptSubOrganizations=['sub_org_name' => $request['sub_org_name'],'org_id' => $request['organization'],'sub_org_id' =>$sub_org_id];
		 KptSubOrganizations::where('sub_org_id', $sub_org_id)->update($KptSubOrganizations);
		 
		 Session()->flash('message', 'Sub Organization successfully updated');
		 return redirect()->route('admin.suborganizations.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
		if (! checkpermission('sub_organization_manage','delete')) {
            return abort(401);
        }
		
		$KptSubOrganizations = KptSubOrganizations::findOrFail($id);
        $KptSubOrganizations->delete();
		Session()->flash('message', 'Organization successfully deleted');
        return redirect()->route('admin.suborganizations.index');
    }
	
	
	/**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! checkpermission('sub_organization_manage','delete')) {
            return abort(401);
        }
		
		
		 $arr_request_ids   = request('ids');
		 $session_userid 	= Auth::user()->id;
		 foreach($arr_request_ids as $suborgs_ids){
			 $suborgsid 	= $suborgs_ids['sub_org_id'];
			 $created_by 	= $suborgs_ids['created_by'];
			 
			if($session_userid != $created_by ){
				Session()->flash('error_message', 'You are not created these Sub Organization(s). Sub Organization can not deleted. ');
				return response()->noContent();
			}			 
		 }
		
         KptSubOrganizations::whereIn('sub_org_id', request('ids'))->delete();
		 Session()->flash('message', 'Sub Organization successfully deleted');
         return response()->noContent();
    }
	
	public function suborganizations_org(Request $request)
    {
        $sub_orgs=KptSubOrganizations::where(['org_id'=>$request->input('org_id'),'sub_org_status'=>1])->get();
        echo '<option value="">Select Sub Organization</option>';
        foreach ($sub_orgs as $key => $sub_org) {
            echo '<option value="'.$sub_org->sub_org_id.'">'.$sub_org->sub_org_name.'</option>';
        }
    }
	
}
