<?php

namespace App\Http\Controllers\personaldetails;
use Illuminate\Validation\Rule;
use App\User;
use Auth;
use Storage;
use App\address;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\bank_details;
use App\personal_details;
use App\Clientorganization;
class personalController extends Controller
{
    public function index(){
        $user_id = Auth::user()->id;
        if(isset($_GET['org']) && $_GET['org'] != ''){
            $type_id=$_GET['org'];
            $type_val='org';
        }elseif(isset($_GET['client_org']) && $_GET['client_org'] != ''){
            $type_id=$_GET['client_org'];
            $type_val='client_org';
        }elseif(isset($_GET['user']) && $_GET['user'] != ''){
            $type_id=$_GET['user'];
            $type_val='user';
        }
        
        if($type_id != ''){    
            $bank_details=bank_details::where(['user_id' => $type_id,'type'=>$type_val])->get();
          
            return view('personaldetails.bankdetails.index',compact('bank_details','type_id','type_val'));  
        }else{

        }
    }
    public function show(){
        $user_id = Auth::user()->id;
        if(isset($_GET['org']) && $_GET['org'] != ''){
            $type_id=$_GET['org'];
            $type_val='org';
        }elseif(isset($_GET['client_org']) && $_GET['client_org'] != ''){
            $type_id=$_GET['client_org'];
            $type_val='client_org';
        }elseif(isset($_GET['user']) && $_GET['user'] != ''){
            $type_id=$_GET['user'];
            $type_val='user';
        }
        
        if($type_id != ''){    
            $bank_details=bank_details::where(['user_id' => $type_id,'type'=>$type_val])->get();

        return view('personaldetails.bankdetails.index',compact('bank_details','type_id','type_val'));  
        }
    }


    public function bank_details(Request $request){

     

       // $address = new address;
     
      
        $user_id = Auth::user()->id;
      
        $OrgUsers = User::get();
       
        //$kptorganization = kptorganization::where()->get()->pluck('org_name', 'org_id');
         $org=$request['user_id'];
         
         $bank_details=bank_details::where(['user_id' => $request['user_id'],'type'=>$request['type']])->get();
        
        // print_r($org);die;
        $this->validate($request, ['bank_name' => ['required']]);
        $this->validate($request, ['bank_address' => ['required']]);
        $this->validate($request, ['account_name' => ['required']]);
        $this->validate($request, ['account_number' => ['required']]);
       

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
                'user_id'=>$request['user_id'],
                'type'=>$request['type']
    		];
            //  echo "<pre>";
            //  print_r($data);die;
        
          
           
           
            $bank_details=bank_details::insert($data);
          
           // print_r($bank_details);die;
     // return view('personaldetails.bankdetails.index', $request['type'].'='.$org);
     return redirect()->route('admin.personaldetails.bank_details', $request['type'].'='.$org);
     // return redirect()->back();


     }

     public function update_bankdetails(Request $request, $id){
        $user_id                                    = Auth::user()->id;
        $OrgUsers                                   = User::get();
        //$kptorganization                          = kptorganization::where()->get()->pluck('org_name', 'org_id');
        $org=$request['user_id'];
        // print_r($kptorganization);die;
        $this->validate($request, ['bank_name'      => ['required']]);
        $this->validate($request, ['bank_address'   => ['required']]);
        $this->validate($request, ['account_name'   => ['required']]);
        $this->validate($request, ['account_number' => ['required']]);
      

        $data=[
                'bank_name'=>$request['bank_name'],
    			'bank_address'=>$request['bank_address'],
    			'account_name'=>$request['account_name'],
                'account_number'=>$request['account_number'],
                'routing_number'=>$request['routing_number'],
                'ifsc_code'=>$request['ifsc_code'],
                'swift_code'=>$request['swift_code'],
                'sort_code'=>$request['sort_code'],
                
                'user_id'=>$request['user_id'],
                'type'=>$request['type']
    		];
        
            //print_r($data);die;
         
           
            $bank_details=bank_details::where(['id'=>$id])->update($data);
            // echo "<pre>";
            // print_r($data);die;
            // die;
            return redirect()->route('admin.personaldetails.bank_details', $request['type'].'='.$org);
          //  return redirect()->route('kpt.organization.edit', [$id]);

     }
     public function edit_bankdetails($id){
        // $user_id = Auth::user()->id;
        // $OrgUsers = User::get();
        // //$kptorganization = kptorganization::where()->get()->pluck('org_name', 'org_id');
        // $org=$request['org_id'];
      //  $kptorganization = Kptorganization::first();
        // print_r($kptorganization);die;
        $edit_bank=bank_details::where(['id'=>$id])->first();
        return view('personaldetails.bankdetails.edit',compact('edit_bank'));  

    }

     public function delete_bank($id)
     {
      
      
     
       //  $kptorganization = kptorganization::findOrFail($id);
         $bank_details=bank_details::where('id',$id)->delete();
        //  print_r($bank_details);die;
        
         Session()->flash('message', 'Organization successfully deleted');
         return redirect()->back();

     }
     public function address(){


        $user_id = Auth::user()->id;
        if(isset($_GET['org']) && $_GET['org'] != ''){
            $type_id=$_GET['org'];
            $type_val='org';
        }elseif(isset($_GET['client_org']) && $_GET['client_org'] != ''){
            $type_id=$_GET['client_org'];
            $type_val='client_org';
        }elseif(isset($_GET['user']) && $_GET['user'] != ''){
            $type_id=$_GET['user'];
            $type_val='user';
        }
       
        if($type_id != ''){    

       // $address = new address;
        $address=address::where(['user_id' => $type_id,'type'=>$type_val])->get();
   
        return view('personaldetails.address.address',compact('address','type_id','type_val'));
        }else{

            echo "error";
        }
     }

     public function add_address( Request $request){

        $user_id = Auth::user()->id;
        $OrgUsers = User::get();
        //$kptorganization = kptorganization::where()->get()->pluck('org_name', 'org_id');
         $org=$request['user_id'];
        // print_r($org);die;
        $this->validate($request, ['address' => ['required']]);
        $address=[
                'address'=> nl2br($request['address']),
                'user_id'=>$request['user_id'],
                'type'=>$request['type']
    		];
            // echo nl2br($request['address']);die;
            // print_r($address);die;
           
            $address=address::insert($address);
            return redirect()->route('admin.personaldetails.address', $request['type'].'='.$org);

     }

     public function edit_address($id){
        $edit_address=address::where(['id'=>$id])->first();
        return view('personaldetails.address.edit',compact('edit_address'));
     }

     public function update_address( Request $request, $id){
        $user_id = Auth::user()->id;
        $OrgUsers = User::get();
        //$kptorganization = kptorganization::where()->get()->pluck('org_name', 'org_id');
         $org=$request['user_id'];
        // print_r($org);die;
        $this->validate($request, ['address' => ['required']]);
        $address=[
            'address'=> nl2br($request['address']),
    			
                 'user_id'=>$request['user_id'],
                'type'=>$request['type']
    		];
        
            //print_r($data);die;
           
            $address=address::where(['id'=>$id])->update($address);
            return redirect()->route('admin.personaldetails.address', $request['type'].'='.$org);


     }

     public function delete_address($id){

        $address=address::where('id',$id)->delete();
        return redirect()->back();

     }
     public function personal_data(){
        $user_id = Auth::user()->id;
        if(isset($_GET['org']) && $_GET['org'] != ''){
            $type_id=$_GET['org'];
            $type_val='org';
        }elseif(isset($_GET['client_org']) && $_GET['client_org'] != ''){
            $type_id=$_GET['client_org'];
            $type_val='client_org';
        }elseif(isset($_GET['user']) && $_GET['user'] != ''){
            $type_id=$_GET['user'];
            $type_val='user';
        }
        
        if($type_id != ''){    
         
      //  $personal = new personal_details;
        $details=personal_details::where(['user_id' => $type_id,'type'=>$type_val])->get();
    
 //echo "<pre/>"; print_r($details);die;
 
        return view('personaldetails.personal.details',compact('details','type_id','type_val'));
       
     

     }
    }

    public function add_personal( Request $request ){
      
        $user_id = Auth::user()->id;
        $OrgUsers = User::get();
        //$kptorganization = kptorganization::where()->get()->pluck('org_name', 'org_id');
         $org=$request['user_id'];
       //  $details=personal_details::where(['user_id' => $request['user_id'],'type'=>$request['type']])->get();
        // print_r($org);die;
      //  $this->validate($request, ['gst'      => ['required']]);
      
        $this->validate($request, ['pan'      => ['required']]);
        $this->validate($request, ['state_code'      => ['required']]);
        $this->validate($request, [  'pan' => ['required', Rule::unique('personal_details')]]);


        
        $details=new personal_details();
          
           $details->register_no=$request['register_no'];
           $details->gst=$request['gst'];
           $details->pan=$request['pan'];
           $details->state_code=$request['state_code'];
          // $details->msme_registered=$request['msme_registered'];
           $details->user_id=$request['user_id'];
           $details->type=$request['type'];
          // echo "<pre/>";
          // print_r($details);die;
           $details->save();
          
         $maintblid = $details->id;
            if ($maintblid > 0) {

                if ($_FILES['pan_img']['name'] != "") {
                    //Source File upload
                    $file_name4 = $_FILES['pan_img']['name'];     //file name
                    $file_size4 = $_FILES['pan_img']['size'];     //file size
                    $file_temp = $_FILES['pan_img']['tmp_name']; //file temp 
        
                    /* checking file type */
                        
                   // $supported_image4 = array('jpeg', 'jpg','png');
                   // $uploads_dir = base_path()."/public/storage/pan/";	
                    $ext4 = strtolower(pathinfo($file_name4, PATHINFO_EXTENSION));
                      //  if (in_array($ext4, $supported_image4)) {
                            $act_filename =  $maintblid .'.' .$ext4;
                
                            //$upload_status = move_uploaded_file($file_temp, "$uploads_dir".$act_filename);                    
                           
                            // echo $file;die;
                            //$name =   $org_data->org_id.'.png';
                            $filePath = 'pan/' . $act_filename;
                            $file = $request->file('pan_img');
                            $res= Storage::disk('s3')->put($filePath, file_get_contents($file));

                          //  print_r($res);die;
                            if($res>0){
                               
                                $filepath=personal_details::where(['id'=>$maintblid])->update(['pan_file_path' => $act_filename]);
                            }
                } else {
                    return back()->withErrors("Please upload correct format file");
                }
                if($request['msme_file'] != '' ){
              
                    if ($_FILES['msme_file']['name'] != "") {
                    //Source File upload
                    $file_name4 = $_FILES['msme_file']['name'];     //file name
                    $file_size4 = $_FILES['msme_file']['size'];     //file size
                    $file_temp = $_FILES['msme_file']['tmp_name']; //file temp 
        
                   
                     $ext4 = strtolower(pathinfo($file_name4, PATHINFO_EXTENSION));
                      //  if (in_array($ext4, $supported_image4)) {
                            $act_filename ='MSME'.$maintblid.'.'.$ext4;
                          //  echo $act_filename;die;
                            $filePath = 'msme_file/' . $act_filename;
                            $file = $request->file('msme_file');
                            $res= Storage::disk('s3')->put($filePath, file_get_contents($file));
                            if($res>0){
                                       
                                $filepath=personal_details::where(['id'=>$maintblid])->update(['msme_file_path' => $act_filename]);
                            }
                        }
                         else {
                            return back()->withErrors("Please upload correct format file");
                        }
                
                    }
             
     
                
          

            // echo "<pre/>"; print_r($_POST);die;
           
            // $maintblid = $details->id;
            // if($maintblid > 0){
            //     $userid = Auth::user()->id;
        
               
            // }
        
        
        
            }
        
                     
        return redirect()->route('admin.personaldetails.personal_data', $request['type'].'='.$org);


    }
    public function edit_personal($id){
        $edit_personal=personal_details::where(['id'=>$id])->first();
       $client_id=$edit_personal->id;
       
      
        $user_type = Clientorganization::where([ 'user_type'=>$id])->first();
       
 
        return view('personaldetails.personal.edit',compact('edit_personal','user_type'));


    }
    public function update_personal( Request $request, $id){
        $user_id = Auth::user()->id;
        $OrgUsers = User::get();
        //$kptorganization = kptorganization::where()->get()->pluck('org_name', 'org_id');
         $org=$request['user_id'];
         $edit_personal=personal_details::where(['id'=>$id])->first();
     
        $this->validate($request, ['pan'      => ['required']]);
        $this->validate($request, ['state_code'      => ['required']]);
        $this->validate($request, [  'pan' => ['required', Rule::unique('personal_details')->ignore($id)]]);
        $details=new personal_details();


        $details=[
            'register_no'=>$request['register_no'],
                'gst'=>$request['gst'],
                'pan'=>$request['pan'],
                'state_code'=>$request['state_code'],
               'user_id'=>$request['user_id'],
                'type'=>$request['type']];
               
                
    	 
     
           $maintblid =personal_details::where(['id'=>$id])->update($details);

         if ($_FILES['pan_img']['name'] != "") {
            $old_path=personal_details::where(['id'=>$id])->first();
            $old_file_path= $old_path->pan_file_path;
            Storage::disk('s3')->delete('pan/'. $old_file_path);
             //Source File upload
             $file_name4 = $_FILES['pan_img']['name'];     //file name
             $file_size4 = $_FILES['pan_img']['size'];     //file size
             $file_temp = $_FILES['pan_img']['tmp_name']; //file temp 

             /* checking file type */
            
             $ext4 = strtolower(pathinfo($file_name4, PATHINFO_EXTENSION));
                      //  if (in_array($ext4, $supported_image4)) {
                            $act_filename =  $id.'_'.time() .'.' .$ext4;
                          
                            $filePath = 'pan/' . $act_filename;
                            $file = $request->file('pan_img');
                            $res= Storage::disk('s3')->put($filePath, file_get_contents($file));
                            if($res>0){   
                                $filepath=personal_details::where(['id'=>$id])->update(['pan_file_path' => $act_filename]);
                            } else {
                     return back()->withErrors("Please upload correct format file");
                 }
               }
              
                $userid = Auth::user()->id;
                if($edit_personal->msme_file != ''){
                 
                    if ($_FILES['msme_file']['name'] != "") {
                    //Source File upload
                    $file_name4 = $_FILES['msme_file']['name'];     //file name
                    $file_size4 = $_FILES['msme_file']['size'];     //file size
                    $file_temp = $_FILES['msme_file']['tmp_name']; //file temp 
        
                   
                     $ext4 = strtolower(pathinfo($file_name4, PATHINFO_EXTENSION));
                      //  if (in_array($ext4, $supported_image4)) {
                            $act_filename ='MSME'.$userid.'.'.$ext4;
                
                            $filePath = 'msme_file/' . $act_filename;
                            $file = $request->file('msme_file');
                            $res= Storage::disk('s3')->put($filePath, file_get_contents($file));
                            if($res>0){
                                       
                                $filepath=personal_details::where(['id'=>$id])->update(['msme_file_path' => $act_filename]);
                            }
                         else {
                            return back()->withErrors("Please upload correct format file");
                        }
                    }
            }
        
        
        
        




    
            return redirect()->route('admin.personaldetails.personal_data', $request['type'].'='.$org);
      
    }
    public function delete_data($id){
        $details=personal_details::where(['id'=>$id])->delete();
        return redirect()->back();
    }
}
