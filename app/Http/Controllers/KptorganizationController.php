<?php
namespace App\Http\Controllers;
use App\Kptorganization;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Log;
use Image;
use File;
use Storage;
use App\address;
use App\bank_details;
use App\personal_details;

class KptorganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! checkpermission('org_manage')) {
            return abort(401);
        }


//         $url = 'https://s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com/' . env('AWS_BUCKET') . '/';
        
// $images = [];
// $files = Storage::disk('s3')->files('org_images/', '', '4.png');

// foreach ($files as $file) {
//     //echo "<pre/>";
//     // print_r($file);die;
// $images[] = [
// 'name' => str_replace('org_images/', '', $file),
// 'src' => $url . $file
// ];
// }

// echo "<pre/>";
// print_r($images);die;
        $user_role = Auth::user()->roles[0]->name;
         if($user_role=='administrator'){
            $kptorganizations = Kptorganization::where('org_status','1')->get();
         }else{
             $userorg=get_user_org('org','org_id');
             $kptorganizations = Kptorganization::where(['org_status'=>'1','org_id'=>$userorg])->get();
         }
        
		$user  = new user;
        return view('kpt.organization.index',compact('kptorganizations','user'))->with(['page_title'=>'Organization']);;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! checkpermission('org_manage','add')) {
            return abort(401);
        }
        return view('kpt.organization.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! checkpermission('org_manage')){
            return abort(401);
        }
        $validatedData = $request->validate([
            'org_name' => ['required', Rule::unique('kptorganizations')],
        ]);
       
        $this->validate($request, ['gst'      => ['required']]);
        $this->validate($request, ['pan'      => ['required', Rule::unique('personal_details')]]);
        $this->validate($request, ['state_code'      => ['required']]);
        $this->validate($request, ['address' => ['required']]);
      
        $data['created_by'] = Auth::user()->id;
        $data['org_name'] = $request['org_name'];
        $org = Kptorganization::firstOrCreate (
            ['org_name' => $data['org_name']],
            ['created_by' => $data['created_by']]
        );
        //  echo "<pre/>";
        //  print_r($org);die;
       
       
        $org_data=json_decode($org);
            if ($_FILES['org_img']['name'] != "") {
               
             $file = $request->file('org_img');
                // echo $file;die;
                $name =   $org_data->org_id.'.png';
                $filePath = 'org_images/' . $name;
               $res= Storage::disk('s3')->put($filePath, file_get_contents($file));


       

               
            }

     if($org->org_id){

          
            $data=[
                    'register_no'=>$request['register_no'],
                    'gst'=>$request['gst'],
                    'pan'=>$request['pan'],
                    'state_code'=>$request['state_code'],
                   'user_id'=>$org->org_id,
                   'type'=>'org'
                ];
               
            $req = personal_details::create($data);
           
    
            $maintblid = $req->id;
            if ($maintblid > 0) {
         if ($_FILES['pan_img']['name'] != "") {
             //Source File upload
             $file_name4 = $_FILES['pan_img']['name'];     //file name
             $file_size4 = $_FILES['pan_img']['size'];     //file size
             $file_temp = $_FILES['pan_img']['tmp_name']; //file temp 

             /* checking file type */
                
             $supported_image4 = array('jpeg', 'jpg','png');
             $uploads_dir = base_path()."/public/storage/pan/";	
             $ext4 = strtolower(pathinfo($file_name4, PATHINFO_EXTENSION));
                 if (in_array($ext4, $supported_image4)) {
                     $act_filename =  $maintblid.'.png';
         
                     //$upload_status = move_uploaded_file($file_temp, "$uploads_dir".$act_filename);                    
                     $file = $request->file('pan_img');
                     // echo $file;die;
                     //$name =   $org_data->org_id.'.png';
                     $filePath = 'pan/' . $act_filename;
                    $res= Storage::disk('s3')->put($filePath, file_get_contents($file));


                  
                    $address=[
                            'address'=> nl2br($request['address']),
                            'user_id'=>$org->org_id,
                            'type'=>'org'
                        ];
                        // echo nl2br($request['address']);die;
                        // print_r($address);die;
                       
                        $address=address::insert($address);
                 } else {
                     return back()->withErrors("Please upload correct format file");
                 }
                }
            
            }
             
                // echo "<pre>";
                // print_r($data);die;
              
        }     
    
         return redirect()->route('admin.org.index')->with(['page_title'=>'Organization Admin']);;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$kptorganization = kptorganization::find($id);
		$user  = new user;
        return view('kpt.organization.show', compact('kptorganization','user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


        
		$kptorganization = Kptorganization::find($id);
        $bank_data=bank_details::where(['user_id'=>$id,'type'=>'org'])->get();
		return view('kpt.organization.edit', compact('kptorganization','bank_data'));
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
		$kptorganization = $request->validate([
         'org_name' => ['required', Rule::unique('kptorganizations')->ignore($org_id,'org_id')],
        ]);

		Kptorganization::where('org_id', $org_id)->update($kptorganization);
		if ($_FILES['org_img']['name'] != "") {
            $file = $request->file('org_img');
            // echo $file;die;
            $name =   $org_id.'.png';
            $filePath = 'org_images/' . $name;
           $res= Storage::disk('s3')->put($filePath, file_get_contents($file));
            } 
		 
		  Session()->flash('message', 'Organization successfully updated');
		 return redirect()->route('admin.org.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit_bankdetails($id){
        // $user_id = Auth::user()->id;
        // $OrgUsers = User::get();
        // //$kptorganization = kptorganization::where()->get()->pluck('org_name', 'org_id');
        // $org=$request['org_id'];
        $kptorganization = Kptorganization::first();
        // print_r($kptorganization);die;
        $edit_bank=bank_details::where(['id'=>$id])->first();
        return view('kpt.organization.editbank',compact('edit_bank','kptorganization'));

    }
    public function destroy($id)
    {
        $kptorganization = kptorganization::findOrFail($id);
        $kptorganization->delete();
		Session()->flash('message', 'Organization successfully deleted');
        return redirect()->route('admin.org.index');
    }


	/**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! checkpermission('org_manage','delete')) {
            return abort(401);
        }
         kptorganization::whereIn('org_id', request('ids'))->delete();
		 
		 Session()->flash('message', 'Organization successfully deleted');
         return response()->noContent();
    }
    public function bank_details(Request $request ){
       
        $user_id = Auth::user()->id;
        $OrgUsers = User::get();
        //$kptorganization = kptorganization::where()->get()->pluck('org_name', 'org_id');
        $org=$request['org_id'];
        // print_r($kptorganization);die;
        $this->validate($request, ['bank_name' => ['required']]);
        $this->validate($request, ['bank_address' => ['required']]);
        $this->validate($request, ['account_name' => ['required']]);

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
        // return redirect()->route("admin.orgusers.edit/'.$id.'");
      //  return view('kpt.organization.edit', compact('kptorganization'));
       // return redirect()->route('kpt.organization.edit',['id'=>$org]);
       return redirect()->route('admin.org.edit', $org);

     }

     public function update_bankdetails(Request $request, $id){
        $user_id = Auth::user()->id;
        $OrgUsers = User::get();
        //$kptorganization = kptorganization::where()->get()->pluck('org_name', 'org_id');
        $org=$request['org_id'];
        // print_r($kptorganization);die;
        $this->validate($request, ['bank_name' => ['required']]);
        $this->validate($request, ['bank_address' => ['required']]);
        $this->validate($request, ['account_name' => ['required']]);

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
           
            $bank_details=bank_details::where(['id'=>$id])->update($data);
            // die;
            return redirect()->route('admin.org.edit', $org);
          //  return redirect()->route('kpt.organization.edit', [$id]);

     }

     public function delete_bank($id)
     {
        $Org = User::get();
      
     
       //  $kptorganization = kptorganization::findOrFail($id);
         $bank_details=bank_details::where('id',$id)->delete();
        //  print_r($bank_details);die;
        
         Session()->flash('message', 'Organization successfully deleted');
         return redirect()->route('admin.org.edit', $org);
     }
}
