<?php

namespace App\Http\Controllers;
use App\clientDepartment;
use Illuminate\Http\Request;
use App\Clientorganization;
use App\clientSubOrganization;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Validation\Rule;

class ClientDepartmentController extends Controller
{
   public function index(){
    if (! checkpermission('client_dept_manage')){
        return abort(401);
    }
    $user_role = Auth::user()->roles[0]->name;
    if ($user_role == 'orgadmin') {

        $org_id = get_user_org('org', 'org_id');
        $orgs = Clientorganization::select('org_id')->where(['org_status' => '1', 'kpt_org' => $org_id])->get();
        $org_data = array();
            foreach ($orgs as $org) {
                $org_data[] = $org->org_id;
            }
            $clientsuborg = clientSubOrganization::where(['client_suborg_status' => '1'])->whereIn('client_org_id', $org_data)->get();
    //    echo "<pre>";     print_r($clientsuborg);die;
            $suborg_data = array();
            
            foreach ($clientsuborg as $suborg) {
                $suborg_data[] = $suborg->client_suborg_id;
            }
            
            $clientdept=clientDepartment::where(['client_dpt_status' => '1'])->whereIn('client_suborg_id', $suborg_data)->get();
           
        }
            elseif ($user_role == 'clientuser') {
                $org_id = get_user_org('clientorg', 'org_id');
                $clientdept = clientDepartment::where(['org_status' => '1'])->get();
            
    }
// print_r($clientdept);die;
    $user  = new user;
       return view('client.departments.index',compact('clientdept','user'));
   }

   /* create functionality starts here */

public function create(){
    if (! checkpermission('client_dept_manage','add')){
        return abort(401);
    }
           $org_id = get_user_org('org', 'org_id');
            $orgs = Clientorganization::select('org_id','org_name')->where(['org_status' => '1', 'kpt_org' => $org_id])->get();
            $org_data = array();
            foreach ($orgs as $org) {
                $org_data[] = $org->org_id;
            }
            $clientsuborg = clientSubOrganization::where(['client_suborg_status' => '1'])->whereIn('client_org_id', $org_data)->get();
    return view('client.departments.create',compact('orgs','clientsuborg'));
}

   /* create functionality starts here */
   /* store functionality starts here */

public function store(Request $request){
    if (! checkpermission('departments_manage')) {
        return abort(401);
    }
    $validatedData = $request->validate([
        'department_name' => 'required',
        'client_org_id' => 'required',
        'client_suborg_name'=>'required'
    ]);

    $data['created_by'] = Auth::user()->id;
    $data['client_dpt_name'] = $request['department_name'];
    $data['client_suborg_id'] = $request['client_suborg_name'];
    $dept = clientDepartment::firstOrCreate (
        ['client_dpt_name' => $data['client_dpt_name'],'client_suborg_id' => $data['client_suborg_id'],'created_by' => $data['created_by']]
    );
    Session()->flash('message', 'Department successfully added');
     return redirect()->route('admin.clientdept.index');
}
/* store functionality ends here */
 /* edit functionality starts here */

 public function show($id ){
    $clientdept = clientDepartment::find($id);
    $user  = new user;
    return view('client.departments.show', compact('clientdept', 'user'));
 }


 public function edit($id){
    $clientdepartment = clientDepartment::find($id);

    $org_id = get_user_org('org', 'org_id');


    $orgs = Clientorganization::select('org_name', 'org_id')->where(['org_status' => '1', 'kpt_org' => $org_id])->get();
    $suborgs=clientSubOrganization::select('client_suborg_name','client_suborg_id')->where(['client_suborg_status' => '1','client_org_id'=>$org_id])->get();

    return view('client.departments.edit', compact('clientdepartment', 'orgs','suborgs'));
 }
   

  public function update(Request $request, $client_dpt_id){
    $clientdpt = $request->validate([
        'client_dpt_name' => ['required', Rule::unique('client_departments')->ignore($client_dpt_id,'client_dpt_id')],
        'client_org_id' => ['required'],
        'client_suborg_id' =>['required']    
       ]);
       $clientdpt =['client_dpt_name' => $request['client_dpt_name'],'client_suborg_id' => $request['client_suborg_id'],'client_dpt_id' => $client_dpt_id];
       clientDepartment::where('client_dpt_id', $client_dpt_id)->update($clientdpt);
       Session()->flash('message', 'Department successfully updated');
       return redirect()->route('admin.clientdept.index');
  }
  public function destroy($client_dpt_id){
    $clientdept = clientDepartment::findOrFail($client_dpt_id);
    $clientdept->delete();
    Session()->flash('message', 'Organization successfully deleted');
    return redirect()->route('admin.clientdept.index');
  }
}
