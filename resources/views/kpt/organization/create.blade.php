@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.kptorganization.title_singular') }}
    </div>

    <div class="card-body">
    
     <form action="{{ route("admin.org.store") }}" method="POST" enctype="multipart/form-data"  class="needs-validation" novalidate="">
            @csrf
<div class="row">
    <div class="col-md-6">   

        <div class="form-group {{ $errors->has('org_name') ? 'has-error' : '' }}">
                <label for="email" class="required">{{ trans('cruds.kptorganization.fields.org_name') }}</label>
                <input type="text" id="org_name" name="org_name" class="form-control"  value="{{ old('org_name', isset($org) ? $org->org_name : '') }}"required>
                
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
                <!-- <div class="invalid-feedback" for="">Must enter your Organization</div> -->
        </div>
         <div class="form-group {{ $errors->has('org_img') ? 'has-error' : '' }}">               
                <label>Organization Image  </label>
                <input type="file" class="form-control" name="org_img" accept="image/png, image/jpg, image/jpeg" >
                <span style="color:red; font-weight:5px; font-size:12px;">Note: Supports only .jpg, .jpeg, .png</span>
         </div>


            <div class="form-group  ">
                <label class=" text-lg-center ">Address<span class="required">*</span></label>
                <div class="">
                             
                <textarea name="address" class="form-control" id="" rows="5"  value="{{ old('address', isset($address) ? $address->address : '') }}"  required></textarea>
                </div>
                <div class="invalid-feedback">Please Enter Address?</div>
                <input type="hidden" name="id" value="">
            </div> 
            
      
        
    </div> 
    <div class="col-md-6">
                        


    <div class="form-group row">
         <label class=" text-lg-center ">Register Number<span class="required"></span></label>
       

        <input type="text" name="register_no" class="form-control" id=""  value="{{ old('register_no', isset($data) ? $data->register_no : '') }}"  >
        
        <div class="invalid-feedback">Register Number?</div>
        <input type="hidden" name="id" value="">
       
        
    </div>

            <div class="form-group row">
                <label class=" text-lg-center ">GST Number<span class="required">*</span></label>
               
                    
                    <input type="text" name="gst" class="form-control" id=""   value="{{ old('gst', isset($data) ? $data->gst : '') }}"  required>
               
                <div class="invalid-feedback">GST Numbe?</div>
                    <input type="hidden" name="id" value="">
                    
                   
            </div>
            <div class="form-group row">
                <label class=" text-lg-center ">PAN Number<span class="required">*</span></label>
               
                    
                    <input  type="text" name="pan" class="form-control" id=""  value="{{ old('pan', isset($data) ? $data->pan : '') }}"  required >
               
                <div class="invalid-feedback">PAN Number?</div>
                    <input type="hidden" name="" value="">
                    
            </div>
            <div class="form-group row">
                <label class=" text-lg-center ">State Code<span class="required">*</span></label>
                
                    
                    <input name="state_code" class="form-control" id="" value="{{ old('state_code', isset($data) ? $data->state_code : '') }}" required >
                
                <div class="invalid-feedback">State Code?</div>
                    <input type="hidden" name="id" value=""  required>
                    
            </div>
             <div class="form-group row {{ $errors->has('pan_img') ? 'has-error' : '' }}">               
            <label class="text-lg-center "> Upload pan Card <span class="required">*</span> </label>
           
            <input type="file" class="form-control" name="pan_img" accept="image/png, image/jpg, image/jpeg" required >
            <span style="color:red; font-weight:5px; font-size:12px;">Note:  Supports  &nbsp;&nbsp;  All  &nbsp;&nbsp; File   Formats,</span>
            <div class="invalid-feedback"> Upload pan Card?</div>
                    <input type="hidden" name="" value="">
           </div>
           
            
        
        </div>

        <div class="footer">
      
            <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
      

        </div>
    </form>

</div>
</div>
@endsection
