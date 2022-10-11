<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
//use App\KptDepartments;
//use App\Kptorganization;
use App\Clientorganization;
//use App\KptSubOrganizations;
use App\User;
use App\personal_details;
use App\user_orgizations;
use App\client_user_orgizations;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class ClientController extends Controller
{
    public function index()
    {
        if (!checkpermission('client_user')) {
            return abort(401);
        }

        $user_role = Auth::user()->roles[0]->name;
       /* if ($user_role == 'administrator') {
            $created_by = '';
        } else {
            $created_by = Auth::user()->id;
        }

        $users = User::getClientUser($created_by);*/

        $user_role = Auth::user()->roles[0]->name;  
        if($user_role == "orgadmin"){
            //$created_by = Auth::user()->id;
            $org_id=get_user_org('org','org_id');
        }else{
            //$created_by="";
            $org_id='';
        }
        
        $req_status='';
        if(isset($_GET['status']) && $_GET['status'] != ''){
            $req_status = $_GET['status'];
        }
        if($req_status != ''){
            $exp_status=explode(',', $req_status);   
        }else{
            $exp_status=[];
        }
        $roles=Role::select('id','label','name')->get()->whereIn('name', ['clientuser','requester','approval','reviewer']);

        if($user_role == "orgadmin" || $user_role == 'projectmanager'){
            $org_id=get_user_org('org','org_id');
            $orgs = Clientorganization::select('org_id')->where(['org_status'=>'1','kpt_org'=>$org_id])->get();
            $org_data=array();
            foreach ($orgs as $org) {
                $org_data[]=$org->org_id;
            }
        }elseif($user_role == 'sales'){
            $org_id=get_user_org('org','org_id');
            $orgs = Clientorganization::select('org_id')->where(['org_status'=>'1','kpt_org'=>$org_id,'created_by'=>Auth::user()->id])->get();
            $org_data=array();
            foreach ($orgs as $org) {
                $org_data[]=$org->org_id;
            }
        }elseif($user_role == "clientuser"){
            $org_id=get_user_org('clientorg','org_id');
            $orgs = Clientorganization::select('org_id')->where(['org_status'=>'1','org_id'=>$org_id])->get();
            $org_data=array();
            foreach ($orgs as $org) {
                $org_data[]=$org->org_id;
            }
        }elseif($user_role == "administrator"){
            $org_data=array();
        }
        
        if(count($exp_status) > 0){
            $users = User::getClientUser($org_data,'',$exp_status);
        }else{
            $users = User::getClientUser($org_data,'',$exp_status);
        }
        //$users = User::getClientUser($org_data);
        //print_r($users);die;

        $personal_details=new personal_details();
        return view('admin.client.index', compact('users','exp_status','roles','personal_details'))->with(['page_title'=>'Client Users']);;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!checkpermission('client_user', 'add')) {
            return abort(401);
        }

        $roles = Role::select('id','label','name')->get()->whereIn('name', ['requester', 'approval', 'reviewer','clientuser']);
        // $kptorganization    =   array();
        // print_r(get_user_org('org','org_id'));die;
        $user_role = Auth::user()->roles[0]->name;
        $org_id=get_user_org('org','org_id');
       
        if ($user_role == 'orgadmin' || $user_role == 'projectmanager' || $user_role == 'sales') {
            $orgs = Clientorganization::where(['org_status'=>'1','kpt_org'=>$org_id])->get();
        }else{
            $orgs = Clientorganization::where(['org_status'=>'1','org_id'=>$org_id])->get();
        }
       
        // $kptsuborganization = 	array();
        // $kptsuborganization = 	KptSubOrganizations::get()->pluck('sub_org_name', 'sub_org_id');
        // $KptDepartments 	= 	KptDepartments::get()->pluck('department_name', 'department_id');
//print_r($orgs);die;
        return view('admin.client.create', compact('roles','orgs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!checkpermission('client_user')) {
            return abort(401);
        }

        $validatedData = $request->validate([
            'email' => ['required', Rule::unique('users')],
            'mobile' => ['required', Rule::unique('users')]
           
        ]);
        $AuthenticatedUsers = User::create($request->all());

        $roles = $request->input('roles') ? $request->input('roles') : [];
        $AuthenticatedUsers->assignRole($roles);
        $user_id = $AuthenticatedUsers->id;

        $authenticated_userid = Auth::user()->id;
        if ($authenticated_userid != '') {
            $arr_user_details = ['created_by' => $authenticated_userid];
            User::where('id', $user_id)->update($arr_user_details);
        }

        /* User Organization mapping block */
        $user_org_result = client_user_orgizations::where('user_id',$user_id)->get();

        $organization = $request->input('organization') ? $request->input('organization') : '';
        if($organization !=""){
        	$org_id = $organization;
        }else {
        	$org_id = $user_org_result[0]->org_id;
        }

        // $suborganization = $request->input('suborganization') ? $request->input('suborganization') : '';
        // if($suborganization !=""){
        // 	$sub_id = $suborganization;
        // }else {
        // 	$sub_id = $user_org_result[0]->sub_id;
        // }

        // $department_id = $request->input('department') ? $request->input('department') : '';
        // if($department_id !=""){
        // 	$sub_id = $department_id;
        // }else {
        // 	$sub_id = '';
        // }

        $sub_id=$department_id=0;
        $kpt_org=$request->input('kpt_org') ? $request->input('kpt_org') : '';
        if($kpt_org !="") {
            $arr_user_organizations = array("user_id"=>$user_id,"org_id"=>$org_id,"sub_id"=>$sub_id,"sub_sub_id"=>$department_id);
            client_user_orgizations::create($arr_user_organizations);
        }
        
        $sub_id=$department_id=0;
        if($org_id !="") {
            $arr_user_organizations = array("user_id"=>$user_id,"org_id"=>$org_id,"kpt_org"=>$kpt_org,"sub_id"=>$sub_id,"sub_sub_id"=>$department_id);
            user_orgizations::create($arr_user_organizations);
        }
        /* User Organization mapping block */

        Session()->flash('message', 'User successfully added');

        return redirect()->route('admin.client.index');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!checkpermission('client_user')) {
            return abort(401);
        }

        $DepartmentUsers = User::find($id);

        return view('admin.client.show', compact('DepartmentUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!checkpermission('client_user', 'update')) {
            return abort(401);
        }

        $AuthenticatedUsers = User::getuserdetails($id);
        
        $users = User::getUserrolesforedit($id);
       
        $roles = Role::select('id','label','name')->get()->whereIn('name', ['requester', 'approval', 'reviewer','clientuser']);
        // $kptorganization = Kptorganization::get()->pluck('org_name', 'org_id');
        /*$kptsuborganization = KptSubOrganizations::get()->pluck('sub_org_name', 'sub_org_id');
        $KptDepartments 	= KptDepartments::get()->pluck('department_name', 'department_id');	*/
        // $user_org_result = user_orgizations::where('user_id', $id)->get();

        // if (isset($user_org_result[0]->org_id) && $user_org_result[0]->org_id != '') {
        //     $kptsuborganization = KptSubOrganizations::where('org_id', $user_org_result[0]->org_id)->get()->pluck('sub_org_name', 'sub_org_id');
        // } else {
        //     $kptsuborganization = [];
        // }

        // if (isset($user_org_result[0]->sub_id) && $user_org_result[0]->sub_id != '') {
        //     $KptDepartments = KptDepartments::where('sub_org_id', $user_org_result[0]->sub_id)->get()->pluck('department_name', 'department_id');
        // } else {
        //     $KptDepartments = [];
        // }
        
        return view('admin.client.edit', compact('AuthenticatedUsers','roles','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!checkpermission('client_user', 'update')) {
            return abort(401);
        }

        $this->validate($request, ['email' => ['required', 'email', Rule::unique('users')->ignore($id)]]);
     
        $data['name'] = $request['name'];
        $data['mobile'] = $request['mobile'];
        $data['email'] = $request['email'];
        // $data['roles']=$request['roles'];
        // $data['old_org_id']=$request['old_org_id'];
        $roles_id = $request->input('roles') ? $request->input('roles') : [];
        if ($request['password'] != '') {
            $data['password'] = bcrypt($request['password']);
        }
        $old_dept_id = $request["old_org_id"];
       
        //  print_r($data);die;
        $user_id = $id;
        // $roles = Role::get()->whereIn('name', ['requester', 'approval', 'reviewer'])->pluck('name', 'name');
        $AuthenticatedUsers1= User::where('id', $user_id)->update($data); 
        // $roles = $request->input('roles') ? $request->input('roles') : [];
        $organization = $request->input('organization') ? $request->input('organization') : '';
        $arr_user_organizations = array("user_id"=>$user_id);
        $user_org_result = client_user_orgizations::where($arr_user_organizations)->get();
        $AuthenticatedUsers =  User::find($id); 
        $AuthenticatedUsers->syncRoles($roles_id);
       



        if($organization !=""){
           			
			$org_id = $organization;
						
			$arr_user_organizations_update = array("org_id"=>$org_id);			
			client_user_orgizations::where('user_id', $user_id)->update($arr_user_organizations_update);
				
		}
		
        elseif(count($user_org_result)==0){			
			$arr_user_organizations_insert = array("user_id"=>$user_id);
         
           client_user_orgizations::where('user_id', $user_id)->update($arr_user_organizations_insert);		
			
		}
        else{
            echo "not working fine";
        }
        
		

        

        Session()->flash('message', 'User successfully updated');

        return redirect()->route('admin.client.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!checkpermission('client_user', 'delete')) {
            return abort(401);
        }

        $Authenticatedusers = User::findOrFail($id);
        $Authenticatedusers->delete();

        Session()->flash('message', 'User successfully deleted');

        return redirect()->route('admin.client.index');
    }

    /**
     * Delete all selected User at once.
     */
    public function massDestroy(Request $request)
    {
        if (!checkpermission('client_user', 'delete')) {
            return abort(401);
        }

        User::whereIn('id', request('ids'))->delete();
        Session()->flash('message', 'User successfully deleted');

        return response()->noContent();
    }
}
