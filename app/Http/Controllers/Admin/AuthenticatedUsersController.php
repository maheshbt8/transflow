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
use App\personal_details;
use App\address;
use App\bank_details;
use Storage;
use App\Kptorganization;
use App\KptSubOrganizations;
use App\KptDepartments;



class AuthenticatedUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
   
    {
         if (! checkpermission('authenticate_user_manage')) {
            return abort(401);
        }
		
		
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
        if(count($exp_status) > 0){
            $users = User::getAuthenticatedUsers($org_id,$exp_status);
        }else{
            $users = User::getAuthenticatedUsers($org_id);
        }
        $roles=getuserbasesroleslist($user_role);
       // echo "<pre/>";
 
    
        //$user_id = $users[0]->id;
       // $user_id = Auth::user()->id;
        //$type =  'user';
      
       
        $personal_details=new personal_details();
      
        return view('admin.authenticatedusers.index', compact('users','exp_status','roles','personal_details'))->with(['page_title'=>'Users']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! checkpermission('authenticate_user_manage','add')) {
            return abort(401);
        }
        
        $user_role = Auth::user()->roles[0]->name;	
        $roles=getuserbasesroleslist($user_role);
        // $roles = Role::get()->whereIn('name', ['orgadmin','suborgadmin','departmentadmin','requester','approval','reviewer','projectmanager','translator','proofreader','qualityanalyst'])->pluck('name','name');		
		$kptorganization    =   array();
		$kptorganization 	=   Kptorganization::get()->pluck('org_name', 'org_id');
      //  print_r($kptorganization);die;
		$kptsuborganization = 	array();
		$kptsuborganization = 	KptSubOrganizations::get()->pluck('sub_org_name', 'sub_org_id');
		$KptDepartments 	= 	KptDepartments::get()->pluck('department_name', 'department_id');		
		
		return view('admin.authenticatedusers.create', compact('roles','kptorganization','kptsuborganization','KptDepartments'))->with(['page_title'=>'Create Users']);
        
    }
        
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! checkpermission('authenticate_user_manage')) {
            return abort(401);
        }		
		//$this->validate($request, ['email' => ['required','email',Rule::unique('users')->ignore(Auth::user()->id)]]);
        $validatedData = $request->validate([
            'email' => ['required', Rule::unique('users')],
            'employee_id' =>'required|unique:users',
            'mobile' => ['required', Rule::unique('users')]
            ]);
        //     if(in_array('vendor',$request->input('roles'))){
        //    //     $this->validate($request, [  'pan' => ['required', Rule::unique('personal_details')]]);
        //     }
            if($request['user_type'] == 'individual' || $request['user_type'] == 'company'){
            //    $this->validate($request, ['pan' => ['required', Rule::unique('personal_details')]]);
              //  $this->validate($request, ['state_code' => ['required']]);
              //    $this->validate($request, ['pan_img' => ['required']]);
   
            }
            

      
		$AuthenticatedUsers = User::create($request->all());
       // echo "<pre/>";	
       // print_r($AuthenticatedUsers);die; 	

		$roles = $request->input('roles') ? $request->input('roles') : [];	
		$AuthenticatedUsers->assignRole($roles);
		$user_id = $AuthenticatedUsers->id;	
		 //print_r($user_id);die;
		$authenticated_userid = Auth::user()->id;
		if($authenticated_userid !=""){
			$arr_user_details = array("created_by"=>$authenticated_userid);
			User::where('id', $user_id)->update($arr_user_details);
		}	
		
      
		 //print_r($authenticated_userid)
		/* User Organization mapping block */
		$user_org_result = user_orgizations::where('user_id', $authenticated_userid)->get();
      //  print_r($user_org_result);die;
		$organization = $request->input('organization') ? $request->input('organization') : '';
       
		if($organization !=""){
			$org_id = $organization;
		}else {			
			$org_id = $user_org_result[0]->org_id;
		}	

       // print_r($org_id);die;
     // print_r($user_org_result);die;
      
    if($org_id >0){

          
        $data='';
        if($request['user_type'] == 'individual'){
             // if($request['state_code'] = '' && $request['pan'] != ''){
                $data=[
                    'pan'=>$request['pan'],
                  
                    'state_code'=>$request['state_code'],
                   // 'msme_registered'=>$request['msme_registered'],
                    'user_id'=>$user_id,
                    'type'=>'user'
                ];
               //}
        }elseif($request['user_type'] =='company'){
           // if($request['gst'] != '' && $request['state_code'] != '' && $request['pan'] != ''){
                $data=[
                    'register_no'=>$request['company_register'],
                
                    'gst'=>$request['gst'],
                    'pan'=>$request['pan'],
                    'state_code'=>$request['state_code'],
                    'msme_registered'=>$request['msme_registered'],
                    'user_id'=>$user_id,
                    'type'=>'user'
                ];    
           // }
        }
    }
           $req='';
         if($data != ''){
            $req = personal_details::create($data);
            
           }   

       
        
    if($req){
        $maintblid = $req->id;
     if ($maintblid > 0) {
        if($request['user_type'] == 'company' ||$request['user_type'] == 'individual' ){
            if ($_FILES['pan_img']['name'] != "") {
            //Source File upload
            $file_name4 = $_FILES['pan_img']['name'];     //file name
            $file_size4 = $_FILES['pan_img']['size'];     //file size
            $file_temp = $_FILES['pan_img']['tmp_name']; //file temp 

           
             $ext4 = strtolower(pathinfo($file_name4, PATHINFO_EXTENSION));
              //  if (in_array($ext4, $supported_image4)) {
                    $act_filename =  $maintblid .'.' .$ext4;
        
                    $filePath = 'pan/' . $act_filename;
                    $file = $request->file('pan_img');
                    $res= Storage::disk('s3')->put($filePath, file_get_contents($file));
                    if($res>0){
                               
                        $filepath=personal_details::where(['id'=>$maintblid])->update(['pan_file_path' => $act_filename]);
                    }
                // }
        }
       
    } 
    }

    if($maintblid>0){
        $userid = Auth::user()->id;

        if($request['user_type'] == 'company' ||$request['user_type'] == 'individual' ){
            if ($_FILES['msme_file']['name'] != "") {
            //Source File upload
            $file_name4 = $_FILES['msme_file']['name'];     //file name
            $file_size4 = $_FILES['msme_file']['size'];     //file size
            $file_temp = $_FILES['msme_file']['tmp_name']; //file temp 

           
             $ext4 = strtolower(pathinfo($file_name4, PATHINFO_EXTENSION));
              //  if (in_array($ext4, $supported_image4)) {
                    $act_filename ='MSME'.$maintblid.'.'.$ext4;
        
                    $filePath = 'msme_file/' . $act_filename;
                    $file = $request->file('msme_file');
                    $res= Storage::disk('s3')->put($filePath, file_get_contents($file));
                    if($res>0){
                               
                        $filepath=personal_details::where(['id'=>$maintblid])->update(['msme_file_path' => $act_filename]);
                    }
                // }
        }
    }



    }
           
       
       
                if($request['user_type'] == 'company' || $request['user_type'] == 'individual' ){
                    if($request['address'] != ''){
                        $address=[
                            'address'=> nl2br($request['address']),
                            'user_id'=>$user_id,
                            'type'=>'user'
                        ];
                        $address=address::insert($address);
                    }
                } 
            } 
            
            

            if($request['user_type'] == 'company' || $request['user_type'] == 'individual' ){
                if($request['bank_name'] != '' &&  $request['bank_address'] != '' && $request['account_name'] ){
                    $data=[
                        'bank_name'=>$request['bank_name'],
                        'bank_address'=>$request['bank_address'],
                        'account_name'=>$request['account_name'],
                        'account_number'=>$request['account_number'],
                        'routing_number'=>$request['routing_number'],
                        'ifsc_code'=>$request['ifsc_code'],
                        'swift_code'=>$request['swift_code'],
                        'sort_code'=>$request['sort_code'],
                        // 'bic'=>$request['bic'],
                        'user_id'=>$user_id,
                        'type'=>'user',
                    ];
                    //  echo "<pre>";
                    //  print_r($data);die;
                
                  
                   
                   
                    $bank_details=bank_details::insert($data);
                }
            } 
           
                $suborganization = $request->input('suborganization') ? $request->input('suborganization') : '';
                if($suborganization !=""){
                    $sub_id = $suborganization;
                }else {			
                    $sub_id = 0;	
                }
                $department_id = $request->input('department') ? $request->input('department') : '';
                if($department_id !=""){
                    $department_id = $department_id;
                }else {			
                    $department_id = 0;	
                }
                if($org_id !="") {					
                    $arr_user_organizations = array("user_id"=>$user_id,"org_id"=>$org_id,"sub_id"=>$sub_id,"sub_sub_id"=>$department_id);
                    user_orgizations::create($arr_user_organizations);			
                }
            	
		/* User Organization mapping block */
		Session()->flash('message', 'User successfully added');
        return redirect()->route('admin.authenticatedusers.index');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
    */
    public function show($id)
    {

        if (! checkpermission('authenticate_user_manage')) {
            return abort(401);
        }

		 $DepartmentUsers = User::find($id);
    //    echo"<pre>" ; print_r($DepartmentUsers);die;
        return view('admin.authenticatedusers.show', compact('DepartmentUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! checkpermission('authenticate_user_manage','update')) {
            return abort(401);
        }

		$AuthenticatedUsers = User::find($id);
		$kptorganization 	= Kptorganization::get()->pluck('org_name', 'org_id');

        $user_role = Auth::user()->roles[0]->name;	
        if($user_role == "administrator"){
            $roles = Role::select('id','label','name')->get()->whereIn('name', ['orgadmin','departmentadmin','suborgadmin','projectmanager','translator','proofreader','qualityanalyst','sales','finance','vendor']);		
            }
          elseif($user_role == "orgadmin" || $user_role == 'projectmanager' || $user_role == 'sales'){
              $roles = Role::select('id','label','name')->get()->whereIn('name', ['departmentadmin','suborgadmin','projectmanager','translator','proofreader','qualityanalyst','sales','finance','vendor']);
          }
          elseif($user_role == "suborgadmin"){
              $roles = Role::select('id','label','name')->get()->whereIn('name', ['departmentadmin','projectmanager','translator','proofreader','qualityanalyst',]);
          }else{
              
          $roles = Role::select('id','label','name')->get()->whereIn('name', ['requester','approval','reviewer']);
            }
		/*$kptsuborganization = KptSubOrganizations::get()->pluck('sub_org_name', 'sub_org_id');
		$KptDepartments 	= KptDepartments::get()->pluck('department_name', 'department_id');	*/
		$user_org_result 	= user_orgizations::where ('user_id',$id)->get();	

		
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
       
        $users = User::getUserrolesforedit($id);
 

		return view('admin.authenticatedusers.edit', compact('users','AuthenticatedUsers','kptorganization','kptsuborganization','KptDepartments','user_org_result','roles'));

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

        if (! checkpermission('authenticate_user_manage','update')) {
            return abort(401);
        }


		$this->validate($request, ['email' => ['required','email',Rule::unique('users')->ignore($id),  ]]);
        // $this->validate($request, [
        //    'employee_id' =>['required','employee_id',Rule::unique('users')->ignore($id), ]]);
           

        $request->validate([
            'employee_id' => ['required',Rule::unique('users')->ignore($id),  ]]);
        $request->validate([
                'mobile' => ['required',Rule::unique('users')->ignore($id)]]);
      
		$data['name']  = $request['name'];
		$data['mobile'] = $request['mobile'];
        $data['email'] = $request['email'];
        $data['employee_id'] =  $request['employee_id'];
		if($request['password'] !="")
			$data['password'] = bcrypt($request['password']);
            

         $DepartmentUsers = User::whereId($id)->update($data);

		$old_dept_id = $request["old_dept_id"];
		$user_id = $id;
		$department_id = $request->input('department_id') ? $request->input('department_id') : "";		
		$arr_user_organizations = array("sub_sub_id"=>$old_dept_id,"user_id"=>$user_id);		
		$user_org_result = user_orgizations::where($arr_user_organizations)->get();
       
		
		$organization = $request->input('organization') ? $request->input('organization') : '';
        $res_data=user_orgizations::where('user_id', $user_id)->first();
        if($res_data == ''){
            $arr_user_organizations_update['user_id']=$user_id;
		    user_orgizations::insert($arr_user_organizations_update);
        }
		$suborganization = $request->input('suborganization') ? $request->input('suborganization') : '';
		$AuthenticatedUsers =  User::find($id);	
		$roles = $request->input('roles') ? $request->input('roles') : [];	
        
	    $AuthenticatedUsers->syncRoles($roles);
		if($organization !="" && $suborganization !=""){
           			
			$org_id = $organization;
			$sub_id = $suborganization;			
			$arr_user_organizations_update = array("org_id"=>$org_id,"sub_id"=>$sub_id,"sub_sub_id"=>$department_id);			
            $res_data=user_orgizations::where('user_id', $user_id)->first();
            user_orgizations::where('user_id', $user_id)->update($arr_user_organizations_update);
				
		}elseif($organization !="" && $suborganization ==""){			
			$org_id = $organization;
			$arr_user_organizations_update = array("org_id"=>$org_id);	
			user_orgizations::where('user_id', $user_id)->update($arr_user_organizations_update);
        
            

		}elseif(count($user_org_result)==0 && $department_id !="" ){			
			$arr_user_organizations_insert = array("user_id"=>$user_id,"sub_sub_id"=>$department_id);
           // dd($arr_user_organizations_insert);
			user_orgizations::create($arr_user_organizations_insert);		
			
		}else {
			if($department_id !=""){
				$arr_user_organizations_update = array("sub_sub_id"=>$department_id);			
				user_orgizations::where('user_id', $user_id)->update($arr_user_organizations_update);
			}
            // else{	
               			
			// 	user_orgizations::destroy($user_id);
			// }
		}
		
		Session()->flash('message', 'User successfully updated');
		 return redirect()->route('admin.authenticatedusers.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrgUsers  $orgUsers
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! checkpermission('authenticate_user_manage','delete')) {
            return abort(401);
        }

		 $Authenticatedusers = User::findOrFail($id);
         $Authenticatedusers->delete();
			
		Session()->flash('message', 'User successfully deleted');
        return redirect()->route('admin.authenticatedusers.index');
    }




	/**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! checkpermission('authenticate_user_manage','delete')) {
            return abort(401);
        }

        User::whereIn('id', request('ids'))->delete();
		Session()->flash('message', 'User successfully deleted');
        return response()->noContent();
    }
}
