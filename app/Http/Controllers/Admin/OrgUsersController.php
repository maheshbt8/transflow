<?php
namespace App\Http\Controllers\Admin;
use App\User;
use Auth;
use App\bank_details;
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


class OrgUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if (! checkpermission('org_users_manage')) {
            return abort(401);
        }

        $user_role = Auth::user()->roles[0]->name;	
		if($user_role == "administrator")
			$created_by="";
		else
			$created_by = Auth::user()->id;
		
		$users = User::getOrgUsers($created_by);		
        return view('admin.orgusers.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! checkpermission('org_users_manage','add')) {
            return abort(401);
        }

        //$roles = Role::get()->where('name', 'orgadmin')->pluck('name','name');
        $roles = Role::get()->whereIn('name','orgadmin')->pluck('name','name');		
        if(get_user_org('name') == "administrator"){
            $kptorganization = kptorganization::get()->pluck('org_name', 'org_id');
        }else{
            $org_id=get_user_org('org','org_id');
		$kptorganization = Kptorganization::where('org_id',$org_id)->pluck('org_name', 'org_id');
    }
		return view('admin.orgusers.create', compact('roles','kptorganization'));
   
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! checkpermission('org_users_manage')) {
            return abort(401);
        }

		$this->validate($request, ['email' => ['required','email',Rule::unique('users')->ignore(Auth::user()->id)]]);

		$OrgUsers = User::create($request->all());
		$organization_id = $request["organization"];

		$roles = $request->input('roles') ? $request->input('roles') : [];	
		$OrgUsers->assignRole($roles);
		$user_id = $OrgUsers->id;
		
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
        return redirect()->route('admin.orgusers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if (! checkpermission('org_users_manage')) {
            return abort(401);
        }

		 $OrgUsers = User::find($id);
        return view('admin.orgusers.show', compact('OrgUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! checkpermission('org_users_manage','update')) {
            return abort(401);
        }

		$OrgUsers = User::find($id);
		$kptorganization = Kptorganization::get()->pluck('org_name', 'org_id');
		$user_id = $id;		
		$arr_user_organizations = array("user_id"=>$user_id);		
		$user_org_result = user_orgizations::where ('user_id',$user_id)->get();
		return view('admin.orgusers.edit', compact('OrgUsers','kptorganization','user_org_result'));

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

        if (! checkpermission('org_users_manage','update')) {
            return abort(401);
        }
		
		$this->validate($request, ['email' => ['required','email',Rule::unique('users')->ignore($id),  ]]);
		$data['name']  = $request['name'];
		$data['email'] = $request['email'];
		if($request['password'] !="")
			$data['password'] = bcrypt($request['password']);

         $OrgUsers = User::whereId($id)->update($data);	
		 
		 
		 
		$old_org_id = $request["old_org_id"];
		$user_id = $id;
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
		 
		 
		 return redirect()->route('admin.orgusers.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     * 
     */


     public function bank_details(Request $request ,$id){
         
        $user_id = Auth::user()->id;
        $OrgUsers = User::get();
        //$kptorganization = kptorganization::where()->get()->pluck('org_name', 'org_id');
        $org=$request['org_id'];
        // print_r($kptorganization);die;
        $data=[
                'bank_name'=>$request['bank_name'],
    			'bank_address'=>$request['bank_address'],
    			'account_name'=>$request['account_name'],
                'account_number'=>$request['account_number'],
                'routing_number'=>$request['routing_number'],
                'ifsc_code'=>$request['ifsc_code'],
                'swift_code'=>$request['swift_code'],
                'sort_code'=>$request['sort_code'],
                'bic'=>$request['bic'],
                'user_id'=>$org,
                'type'=>'org'
    		];
        
            //print_r($data);die;
           
            $bank_details=bank_details::insert($data);
          
        //    if($org){
        //     bank_details['user_id']=>  
        //    }
        return redirect()->route("admin.orgusers.edit/'.$id.'");


     }
    public function destroy($id)
    {
        if (! checkpermission('org_users_manage','delete')) {
            return abort(401);
        }

		 $OrgUsers = User::findOrFail($id);
         $OrgUsers->delete();

        return redirect()->route('admin.orgusers.index');

    }




	/**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! checkpermission('org_users_manage','delete')) {
            return abort(401);
        }

        User::whereIn('id', request('ids'))->delete();
        return response()->noContent();
    }
}
