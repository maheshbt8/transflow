@extends('layouts.admin')
@section('content')
<style>
    .h2, h2 {
    font-size: 1.5rem;
}
</style>
<!---sysytem-->
<div class="container">
    <div class="row">
        <div class="col-md-12" >
        <form action="{{ route("admin.settings.store") }}" method="post"  enctype="multipart/form-data" >
        @csrf
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">System Settings</h2>
                    </header>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 ">System Name<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="system_name" class="form-control"  name="system_name" value="{{settingsdata('system_name')}}">
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                                                            <input type="hidden" name="id" value="">
                                <br>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">System Title <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="system_title" class="form-control"  name="system_name" value="{{settingsdata('system_title')}}">
                            </div>
                            <div class="invalid-feedback">System Title ?</div>
                                                    </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Mobile Number<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="number" name="mobile" class="form-control" name="system_name" value="{{settingsdata('mobile')}}">
                            </div>
                            <div class="invalid-feedback">Mobile Number?</div>
                                                    </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Address<span class="required">*</span></label>
                            <div class="col-sm-9 ">
                                <input type="text" class="form-control" name="address" v name="system_name" value="{{settingsdata('address')}}" >
                               
                            </div>
                            <div class="invalid-feedback">Address?</div>
                                                    </div>
                       

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                               
                            </div>
                        </div>

                    </div>
            
            </section>
            </form>
        </div>
</div>


<!---end--->

<div class="row">

  <div class="col-md-6">
            <form id="form_sms"action="{{ route("admin.settings.email_update") }}" class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
            @csrf
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">SMS Settings</h2>
                    </header>
                    <br>
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-sm-3 ">Username <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="sms_username" class="form-control"  value="{{settingsdata('sms_username')}}">
                            </div>
                            <div class="invalid-feedback">sms_username?</div>
                                                    </div>
                        <br>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Sender <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="sms_sender" class="form-control" placeholder="Sender" required=""  value="{{settingsdata('sms_sender')}}">
                            </div>
                            <div class="invalid-feedback">sms_sender?</div>
                                                    </div>
                        <br>
                        <div class="form-group row">
                            <label class="col-sm-3 ">Hash Key <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="sms_hash" class="form-control" placeholder="Hash Key" required=""value="{{settingsdata('sms_hash')}}">
                            </div>
                            <div class="invalid-feedback">Hash Key?</div>
                                                    </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                               
                            </div>
                        </div>
                    </div>
             </section>
           
        </form>
        </div>

        <div class="col-md-6">
            <form id="form-smtp" action="{{ route("admin.settings.smpt_settings") }}" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
            @csrf  
            <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">SMTP Settings</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 ">SMTP Port <span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="smtp_port" class="form-control" placeholder="SMTP Port" required="" value="{{settingsdata('smtp_port')}}">
                            </div>
                            <div class="invalid-feedback">smtp_port?</div>
                                                    </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">SMTP Host<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="smtp_host" class="form-control" placeholder="SMTP Host" required="" value="{{settingsdata('smtp_host')}}">
                            </div>
                            <div class="invalid-feedback">smtp_host?</div>
                                                    </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">SMTP Username<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="smtp_username" class="form-control" placeholder="SMTP Username" required="" value="{{settingsdata('smtp_username')}}">
                            </div>
                            <div class="invalid-feedback">smtp_username?</div>
                                                    </div>
                        <div class="form-group row">
                            <label class="col-sm-3 ">SMTP Password<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="smtp_password" class="form-control" placeholder="SMTP Password" required="" value="{{settingsdata('smtp_password')}}">
                            </div>
                            <div class="invalid-feedback">smtp_password?</div>
                                                    </div>

                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                
                            </div>
                        </div>
                    </div>
            
            </section></form>
        </div>


        <div class="col-md-6">
            <form id="form-smtp" action="{{ route("admin.settings.logo_update") }}" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
                @csrf
            <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Logo</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 ">Logo</label>
                            <div class="col-sm-9">
                            <input type="file" name="file" class="form-control" accept="image/png, image/jpg, image/jpeg">
                                              <br><br>
                          <img id="blah" src="{{ asset('img/Transflow-logo.png') }}" style="height: 50px;width: 250px;" alt="Logo">
                        </div>
                    </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                               
                            </div>
                        </div>
                    </div>
            
            </section></form>
        </div>
        <div class="col-md-6">
            <form id="form-smtp" action="{{ route("admin.settings.favicon") }}" class="needs-validation form" novalidate="" method="post" enctype="multipart/form-data">
               @csrf
            <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven">Favicon</h2>
                    </header>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 ">Favicon</label>
                            <div class="col-sm-9">
                            <input type="file" name="file_fav" class="form-control" onchange="news_image(this);"  accept="image/png">
                                              <br><br>
                          <img id="blah" src="{{ asset('img/Transflow-fav.png') }}" style="height: 50px;width: 250px;" alt="Favicon">
                        </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                                <input type="button" class="btn btn-default" onclick="clear_form('form-smtp')" value="Reset">
                            </div>
                        </div>
                    
                    </div>
            
            </section></form>
        </div>

</div>











   
@endsection
