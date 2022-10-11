<?php

namespace App\Http\Controllers;

use App\settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (! checkpermission('system_settings')){
        //     return abort(401);
        // }
        return view('settings.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (! checkpermission('system_settings')){
        //     return abort(401);
        // }
        // $this->validate($request, ['key' => ['required']]);
        // $this->validate($request, ['Description' => ['required']]);

            $settings = new settings;
            $system_name = $request->input('system_name');
            $system_name = array('description' => $system_name);
            settings::where('key','system_name')->update($system_name);

            
            $system_title = $request->input('system_title');
            $system_title = array('description' => $system_title);
            settings::where('key','system_title')->update($system_title);

            $mobile = $request->input('mobile');
            $mobile = array('description' => $mobile);
            settings::where('key','mobile')->update($mobile);

            $address = $request->input('address');
            $address = array('description' => $address);
            settings::where('key','address')->update($address);



            return redirect()->route('admin.settings.index');
    }
    
    public function email_update(Request $request){
        // if (! checkpermission('system_settings')){
        //     return abort(401);
        // }
        $email_settings = new settings;
        $email_settings = $request->input('sms_username');
        $email_settings = array('description' => $email_settings);
        settings::where('key','sms_username')->update($email_settings);

        $email_settings = $request->input('sms_sender');
        $email_settings = array('description' => $email_settings);
        settings::where('key','sms_sender')->update($email_settings);

        $email_settings = $request->input('sms_hash');
        $email_settings = array('description' => $email_settings);
        settings::where('key','sms_hash')->update($email_settings);


        return redirect()->route('admin.settings.index');
    }
    public function smpt_settings(Request $request){
        // if (! checkpermission('system_settings')){
        //     return abort(401);
        // }k
        $smpt_settings =new settings;

        $smpt_settings = $request->input('smtp_port');
        $smpt_settings = array('description' => $smpt_settings);
        settings::where('key','smtp_port')->update($smpt_settings);

        $smpt_settings = $request->input('smtp_host');
        $smpt_settings = array('description' => $smpt_settings);
        settings::where('key','smtp_host')->update($smpt_settings);

        $smpt_settings = $request->input('smtp_username');
        $smpt_settings = array('description' => $smpt_settings);
        settings::where('key','smtp_username')->update($smpt_settings);

        $smpt_settings = $request->input('smtp_password');
        $smpt_settings = array('description' => $smpt_settings);
        settings::where('key','smtp_password')->update($smpt_settings);
        return redirect()->route('admin.settings.index');
    }

    public function logo_update(){
        // if (! checkpermission('system_settings')){
        //     return abort(401);
        // }
          
        $file_name4 = $_FILES['file']['name'];     //file name
        $file_size4 = $_FILES['file']['size'];     //file size
        $file_temp = $_FILES['file']['tmp_name']; //file temp 

        /* checking file type */
           
        $supported_image4 = array('jpeg', 'jpg','png');
        $uploads_dir = base_path()."/public/img/";	
        $ext4 = strtolower(pathinfo($file_name4, PATHINFO_EXTENSION));
            if (in_array($ext4, $supported_image4)) {
                $act_filename = 'Transflow-logo.png';
    
                $upload_status = move_uploaded_file($file_temp, "$uploads_dir".$act_filename);   
                return redirect()->route('admin.settings.index');
    }
}
public function favicon(){


    $favicon = $_FILES['file_fav']['name'];     //file name
    $favicon_size4 = $_FILES['file_fav']['size'];     //file size
    $favicon_temp = $_FILES['file_fav']['tmp_name']; //file temp 

    /* checking file type */
       
    $supported_image = array('png');
    $uploads_dir = base_path()."/public/img/";	
    $ext = strtolower(pathinfo($favicon, PATHINFO_EXTENSION));
        if (in_array($ext, $supported_image)) {
            $act_filename1 = 'Transflow-fav.png';

            $upload_status = move_uploaded_file($favicon_temp, "$uploads_dir".$act_filename1);   
            return redirect()->route('admin.settings.index');
}

}

    /**
     * Display the specified resource.
     *
     * @param  \App\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function edit(settings $settings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, settings $settings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function destroy(settings $settings)
    {
        //
    }
}
