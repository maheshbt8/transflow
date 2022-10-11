<?php
namespace App\Http\Controllers;
use App\Clientorganization;
use App\Kptorganization;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Storage;
use Log;
use App\personal_details;
use App\address;

class ClientorganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! checkpermission('client_org_manage')) {
            return abort(401);
        }

        /*$clientorganizations = Clientorganization::where('org_status','1')->get();*/
        $user_role = Auth::user()->roles[0]->name;
        $org_id=get_user_org('org','org_id');
       
        if ($user_role == 'orgadmin' || $user_role == 'projectmanager') {
            $clientorganizations = Clientorganization::where(['org_status'=>'1','kpt_org'=>$org_id])->get();
        }elseif ($user_role == 'sales') {
            $clientorganizations = Clientorganization::where(['org_status'=>'1','kpt_org'=>$org_id,'created_by'=>Auth::user()->id])->get();
        }elseif ($user_role == 'administrator') {
            $clientorganizations = Clientorganization::where(['org_status'=>'1'])->get();
        }
       
		$user  = new user;
        return view('client.organization.index',compact('clientorganizations','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! checkpermission('client_org_manage','add')) {
            return abort(401);
        }
        $orgs = Kptorganization::where('org_status','1')->get();
        return view('client.organization.create',compact('orgs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! checkpermission('client_org_manage')){
            return abort(401);
        }
        $validatedData = $request->validate([
            'org_name' => ['required', Rule::unique('clientorganizations')],
            'kpt_org' =>'required',
            
        ]);
        // $validatedData = $request->validate([
        //     'pan' => 'required|unique:personal_details,pan',
        //     'company_pan' => 'required|unique:personal_details,pan',
        // ]);

       
        //$this->validate($request,['pan' =>['required|multiple_unique:'.personal_details::class.',pan,company_pan']]);
       // if($request['user_type'] == 'individual' || $request['user_type'] == 'company'){
            // $this->validate($request, ['pan' => ['required', Rule::unique('personal_details')]]);
            //  $this->validate($request, ['state_code' => ['required']]);
               // $this->validate($request, ['address' => ['required']]);

        // }
      
    //  $this->validate($request, [  'pan' => ['required', Rule::unique('personal_details')]]);
        // if($request['user_type'] == 'individual'){
        //     $validatedData =$request->validate(([
        //         'pan' => ['required', Rule::unique('personal_details')]
        //     ]));
        // }else{
          
        //     $validatedData =$request->validate(([
        //         'pan' => ['required', Rule::unique('personal_details'),'company_pan']
        //     ]));
        // }

    //  $this->validate($request,[
    //      'pan' => [
    //             Rule::unique('personal_details')
    //            ->where('pan', $request->input('company_pan'))
    //            ->where('pan', $request->input('pan'))
    //         ],
    //     ]);
      //  print_r($request['user_type']);die;
        // $this->validate($request, ['register_no'      => ['required']]);
        // $this->validate($request, ['gst'      => ['required']]);
       // $this->validate($request, ['pan'      => ['required', Rule::unique('personal_details')]]);
        // $this->validate($request, ['state_code' => ['required']]);
        // $this->validate($request, ['pan_img' => ['required']]);

        $data['created_by'] = Auth::user()->id;
        $data['org_name'] = $request['org_name'];
        $data['kpt_org'] = $request['kpt_org'];
        $data['user_type'] = $request['user_type'];
      
        $org = Clientorganization::firstOrCreate ($data);
        // echo "<pre/>";
        // print_r($org);die;
   

        if($org->org_id >0){

          
            $data='';
            if($request['user_type'] == 'individual'){
                if( $request['pan'] = ''){
                    $data=[
                        'pan'=>$request['pan'],
                        'state_code'=>$request['state_code'],
                        'user_id'=>$org->org_id,
                        'type'=>'client_org'
                    ];
                }
            }elseif($request['user_type'] =='company'){
                if($request['gst'] != ''){
                    $data=[
                        'register_no'=>$request['company_register'],
                        'gst'=>$request['gst'],
                        'pan'=>$request['pan'],
                        'state_code'=>$request['state_code'],
                        'user_id'=>$org->org_id,
                        'type'=>'client_org'
                    ];    
                }
            }elseif($request['user_type'] =='international'){
              
                    $data=[
                        'register_no'=>$request['company_register'],
                         'user_id'=>$org->org_id,
                        'type'=>'client_org'
                    ];    
             


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
                    }
            // } elseif($request['user_type'] == 'company'){

                // if($request['user_type'] == 'company'){
                //     if ($_FILES['pan_img']['name'] != "") {
                //     //Source File upload
                //     $file_name4 = $_FILES['company_pan_img']['name'];     //file name
                //     $file_size4 = $_FILES['company_pan_img']['size'];     //file size
                //     $file_temp = $_FILES['company_pan_img']['tmp_name']; //file temp 
    
                //     /* checking file type */
                        
                //     $supported_image4 = array('jpeg', 'jpg','png');
                //     $uploads_dir = base_path()."/public/storage/pan/";	
                //     $ext4 = strtolower(pathinfo($file_name4, PATHINFO_EXTENSION));
                //         if (in_array($ext4, $supported_image4)) {
                //             $act_filename =  $maintblid.'.png';
                
                //             //$upload_status = move_uploaded_file($file_temp, "$uploads_dir".$act_filename);                    
                //             $file = $request->file('company_pan_img');
                //             // echo $file;die;
                //             //$name =   $org_data->org_id.'.png';
                //             $filePath = 'pan/' . $act_filename;
                //             $res= Storage::disk('s3')->put($filePath, file_get_contents($file));
                //         }
                // }

           // }
         } 
        } 
        }
           
       // if($request['user_type'] == 'company' || $request['user_type'] == 'individual' || $request['user_type'] == 'international' ){
            if($request['address'] != ''){
                $address=[
                    'address'=> nl2br($request['address']),
                    'user_id'=>$org->org_id,
                    'type'=>'client_org'
                ];
                $address=address::insert($address);
            }
       // } 
        // }elseif($request['user_type'] == 'company'){
        //     if($request['company_address'] != ''){
        //         $address=[
        //             'address'=> nl2br($request['company_address']),
        //             'user_id'=>$org->org_id,
        //             'type'=>'client_org'
        //         ];
        //         $address=address::insert($address);
        //     }
        // }
       
    }
		Session()->flash('message', 'Organization successfully added');
        return redirect()->route('admin.clientorg.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$clientorganization = Clientorganization::find($id);
		$user  = new user;
        return view('client.organization.show', compact('clientorganization','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$clientorganization = Clientorganization::find($id);
        $orgs = Kptorganization::where('org_status',1)->get();
        
		return view('client.organization.edit', compact('clientorganization','orgs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $org_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $org_id)
    {
		$clientorganization = $request->validate([
         'org_name' => ['required', Rule::unique('clientorganizations')->ignore($org_id,'org_id')],
        ]);

		 Clientorganization::where('org_id', $org_id)->update($clientorganization);
		 
		 
		  Session()->flash('message', 'Organization successfully updated');
		 return redirect()->route('admin.clientorg.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $clientorganization = Clientorganization::findOrFail($id);
        $clientorganization->delete();
		Session()->flash('message', 'Organization successfully deleted');
        return redirect()->route('admin.clientorg.index');
    }


	/**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! checkpermission('client_org_manage','delete')) {
            return abort(401);
        }
         Clientorganization::whereIn('org_id', request('ids'))->delete();
		 
		 Session()->flash('message', 'Organization successfully deleted');
         return response()->noContent();
    }
}
