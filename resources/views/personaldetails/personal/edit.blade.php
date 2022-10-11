@extends('layouts.admin')
@section('content')





            <form id="form_sms"action="{{ route('admin.personaldetails.update_personal',[$edit_personal->id]) }}" class="needs-validation" novalidate="" method="post" enctype="multipart/form-data">
            @csrf
            <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss=""></a>
                        </div>
                        <h2 class="card-title ven"> Edit personal Details</h2>
                    </header>
                    <div class="card-body">
                   

                    <div class="form-group row">
                            <label class="col-sm-3 text-lg-center ">Register Number<span class="required"></span></label>
                            <div class="col-sm-6">
                             
                                <input name="register_no" class="form-control" id="" value="{{ old('name', isset($edit_personal->register_no) ? $edit_personal->register_no : '') }}" >
                               
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                          
                        </div>
                    
                        <div class="form-group row">
                            <label class="col-sm-3 text-lg-center ">GST Number<span class="required">*</span></label>
                            <div class="col-sm-6">
                             
                                <input name="gst" class="form-control" id="" value="{{ old('name', isset($edit_personal->gst) ? $edit_personal->gst : '') }}" >
                               
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                            <input type="hidden" name="id" value="{{$edit_personal->id}}">
                                <input type="hidden" name="type" value="{{$edit_personal->type}}">
                                <div class="col-sm-3"></div>
                                <br>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-3 text-lg-center ">PAN Number<span class="required">*</span></label>
                            <div class="col-sm-6">
                             
                                <input name="pan" class="form-control" id="" value="{{ old('name', isset($edit_personal->pan) ? $edit_personal->pan : '') }}" >
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                                <input type="hidden" name="id" value="">
                                <div class="col-sm-3"></div>
                                <br>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 text-lg-center ">State Code<span class="required">*</span></label>
                            <div class="col-sm-6">
                             
                                <input name="state_code" class="form-control" id="" value="{{ old('name', isset($edit_personal->state_code) ? $edit_personal->state_code : '') }}" >
                            </div>
                            <div class="invalid-feedback">System Name?</div>
                                <input type="hidden" name="id" value="">
                                <div class="col-sm-3"></div>
                                <br>
                        </div>
                        <div class="form-group row {{ $errors->has('pan_img') ? 'has-error' : '' }}">               
                        <label class="col-sm-3 text-lg-center "> Upload Pan Card<span class="required">*</span></label>
                     <div class="col-sm-6">
                     <input type="file" class="form-control" name="pan_img"  >
                     <span style="color:red; font-weight:5px; font-size:12px;">Note:  Supports  &nbsp;&nbsp;  All  &nbsp;&nbsp; File   Formats,</span>
                           </div>
                     </div>
                      <?php if($edit_personal->msme_file_path != ''){ ?>
                     <div class="form-group row">               
                        <label class=" col-sm-3 text-lg-center">Upload MSME Registration Certificate. <span class="required">*</span> </label>
                        <div class="col-sm-6">
                        <input type="file" class="form-control" name="msme_file"  >
                        <span style="color:red; font-weight:5px; font-size:12px;">Note:  Supports  &nbsp;&nbsp;  All  &nbsp;&nbsp; File   Formats,</span>
                        <div class="invalid-feedback">Please Upload MSME Registration Certificate.?</div>
                        </div>
                    </div>
       <?php } ?>
                        <input type="text" name="user_id" placeholder="Org Name" class="form-control" value="{{ old('name', isset($edit_personal->user_id) ? $edit_personal->user_id : '') }}" hidden> 
                        <div class="row justify-content-end">
                            <div class="col-sm-9">
                                <button class="btn btn-primary">Submit</button>
                               
                            </div>
                        </div>

                    </div>
            
            </section>
           
        </form>


    
          
    



@endsection