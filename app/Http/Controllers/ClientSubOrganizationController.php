<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\clientSubOrganization;
use Illuminate\Support\Facades\Auth;
use App\Clientorganization;
use App\User;
use Illuminate\Validation\Rule;
use App\Kptorganization;

class ClientSubOrganizationController extends Controller
{
    public function index()
    {
        if (!checkpermission('client_suborg_manage')) {
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
            
        } elseif ($user_role == 'clientuser') {
            $org_id = get_user_org('clientorg', 'org_id');
            $clientsuborg = clientSubOrganization::where(['org_status' => '1'])->get();
        }
        $user  = new user;
        return view('client.suborganizations.index', compact('clientsuborg', 'user'));
    }

    /* create functionality starts here*/ 

    public function create()
    {
        if (!checkpermission('client_suborg_manage', 'add')) {
            return abort(401);
        }
        $org_id = get_user_org('org', 'org_id');
        $orgs = Clientorganization::where(['org_status'=> '1','kpt_org'=> $org_id])->get();
        return view('client.suborganizations.create', compact('orgs'));
    }

    /* create functionality ends here*/ 
    /* store functionality starts here*/

    public function store(Request $request)
    {
        if (!checkpermission('client_suborg_manage')) {
            return abort(401);
        }
        $validatedData = $request->validate([
            'sub_org_name' => 'required',
            'organization' => 'required'
        ]);
        $data['created_by'] = Auth::user()->id;
        $data['sub_org_name'] = $request['sub_org_name'];
        $data['client_org_id'] = $request['organization'];
        $sub_org = clientSubOrganization::firstOrCreate(
            ['client_suborg_name' => $data['sub_org_name'], 'client_org_id' => $data['client_org_id'], 'created_by' => $data['created_by']]
        );

        Session()->flash('message', 'Sub Organization successfully added');
        return redirect()->route('admin.clientsuborg.index');
    }
    
     /* store functionality ends here*/ 
    /* show functionality starts here */

    public function show($id)
    {
        $clientsuborg = clientSubOrganization::find($id);
        $user  = new user;
        return view('client.suborganizations.show', compact('clientsuborg', 'user'));
    }

     /* show functionality ends here*/ 
     /* edit functionality starts here*/

    public function edit($id)
    {
        $clientsuborganization = clientSubOrganization::find($id);

        $org_id = get_user_org('org', 'org_id');


        $orgs = Clientorganization::select('org_name', 'org_id')->where(['org_status' => '1', 'kpt_org' => $org_id])->get();

        return view('client.suborganizations.edit', compact('clientsuborganization', 'orgs'));
    }
     
     /* edit functionality ends here*/ 
     /* update functionality starts here*/ 

    public function update(Request $request, $client_suborg_id)
    {
        $clientsuborganization = $request->validate([
            'client_suborg_name' => ['required', Rule::unique('client_sub_organizations')->ignore($client_suborg_id,'client_suborg_id')],
            'client_org_id' => ['required']
           ]);
   
           clientSubOrganization::where('client_suborg_id', $client_suborg_id)->update($clientsuborganization);
            
            
             Session()->flash('message', 'Organization successfully updated');
            return redirect()->route('admin.clientsuborg.index');
    }

     /* update functionality ends here*/
     
    public function destroy($client_suborg_id)
    {
        $clientsuborganization = clientSubOrganization::findOrFail($client_suborg_id);
        $clientsuborganization->delete();
		Session()->flash('message', 'Organization successfully deleted');
        return redirect()->route('admin.clientsuborg.index');
    }
    public function suborganizations_org(Request $request)
    {
        $sub_orgs=clientSubOrganization::where(['client_org_id'=>$request->input('org_id'),'client_suborg_status'=>1])->get();
        echo '<option value="">Select Sub Organization</option>';
        foreach ($sub_orgs as $key => $sub_org) {
            echo '<option value="'.$sub_org->client_suborg_id.'">'.$sub_org->client_suborg_name.'</option>';
        }
    }
}
