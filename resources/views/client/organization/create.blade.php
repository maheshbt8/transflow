@extends('layouts.admin')
@section('content')
<style>
    /* This CSS class is applied, by default to all the labels and
       textboxes. The JavaScript just adds or removes it as needed. */
    #company_form{ display:none; }
    .individual_required{display: none;}
  </style>
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.kptorganization.title_singular') }}
    </div>

    <div class="card-body">
        
    <form action="{{ route("admin.clientorg.store") }}" method="POST" enctype="multipart/form-data"  class="needs-validation" novalidate="">
     @csrf
    <div class="row">
        <div class="col-md-5">

         <div class=" {{ $errors->has('organization') ? 'has-error' : '' }}">
            <?php
                if(get_user_org('name')== "administrator"){
            ?>
            <label for="org" class="required">Organizations</label>
             <select name="kpt_org" id="org" class="form-control select2" required="" >
             <option value="" >Select Organization</option>
              @foreach($orgs as $key => $org)
                        <option value="{{ $org->org_id }}" >{{ $org->org_name }}</option>
               @endforeach                    
              </select>  
                 @if($errors->has('organization'))
                    <em class="invalid-feedback">
                        {{ $errors->first('organization') }}
                    </em>
                @endif    
                <?php
                }else{
            ?>
            <input type="hidden" value="<?php echo get_user_org('org','org_id');?>" id="org_id" name="kpt_org"/>
            <?php }?>
              
             </div>
            <div class="form-group {{ $errors->has('org_name') ? 'has-error' : '' }}">
                <label for="org_name" class="required">{{ trans('cruds.kptorganization.fields.org_name') }} <span class="required">*</span></label>
                <input type="text" id="org_name" name="org_name" class="form-control" value="{{ old('org_name', isset($data) ? $data->org_name : '') }}" required>
                @if($errors->has('org_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('org_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
                <div class="invalid-feedback" for="">Must enter your Organization</div>
            </div>

            
        </div>
               <div class="col-md-2"></div>
        <div class="col-md-5">
            <div class="col-md-12 form-group">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" name="user_type" class="form-check-input"  value="individual" checked="checked">
                        Individual
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" name="user_type" class="form-check-input"  value="company">
                        Company
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" name="user_type" class="form-check-input"  value="international">
                        International
                    </label>
                </div>
            </div>
            <!-- <div class="col-md-12" id="individual_form">
                <div class="form-group">
                    <label class=" text-lg-center ">PAN Number<span class="required individual_required">*</span></label>
                    <input  type="text" name="pan" class="form-control" id="individuat_pan"  value="{{ old('pan', isset($data) ? $data->pan : '') }}"  >
                    <div class="invalid-feedback">PAN Number?</div>
                    <input type="hidden" name="" value="">    
                </div>
                <div class="form-group">
                    <label class=" text-lg-center ">State Code<span class="required individual_required">*</span></label>
                    <input type="text" name="state_code" class="form-control" id="individuat_state" value="{{ old('state_code', isset($data) ? $data->state_code : '') }}" >
                    <div class="invalid-feedback">State Code?</div>
                </div>
                <div class="form-group {{ $errors->has('pan_img') ? 'has-error' : '' }}">               
                    <label class="text-lg-center ">Upload Pan Card<span class="required individual_required">*</span> </label>
                    <input type="file" class="form-control" name="pan_img"  id="individuat_pan_img" accept="image/png, image/jpg, image/jpeg" >
                    <span style="color:red; font-weight:5px; font-size:12px;">Note: Supports only .jpg, .jpeg, .png</span>
                    <div class="invalid-feedback">Pan Image?</div>
                </div>
                <div class="form-group">
                    <label class=" text-lg-center ">Address<span class="required individual_required">*</span></label>
                    <textarea name="address" class="form-control" id="individuat_address" rows="5"  value="{{ old('address', isset($address) ? $address->address : '') }}"  ></textarea>
                    <div class="invalid-feedback">Please Enter Address?</div>
                    <input type="hidden" name="id" value="">
                </div>
             </div> -->
            <div class="row">
                <div class="col-md-12" id="company_form">
                    <div class="form-group">
                        <label class=" text-lg-center ">Company Registration Number</label>
                        <input type="text" name="company_register" class="form-control" id="company_register"  value="{{ old('company_register', isset($data) ? $data->company_register : '') }}">
                        <div class="invalid-feedback">Company Registration Numbe</div>
                        <input type="hidden" name="id" value="">
                    </div>
                    <div class="form-group">
                        <label class=" text-lg-center ">GST Number<span class="required">*</span></label>
                        <input type="text" name="gst" class="form-control" id="company_gst"   value="{{ old('gst', isset($data) ? $data->gst : '') }}"  >
                        <div class="invalid-feedback">GST Numbe?</div>
                        <input type="hidden" name="id" value="">
                    </div>
                </div>
                <div class="col-md-12" id="individual_form">
                    <div class="form-group">
                        <label class=" text-lg-center ">PAN Number<span class="required individual_required"></span></label>
                        <input  type="text" name="pan" class="form-control" id="individuat_pan"  value="{{ old('pan', isset($data) ? $data->pan : '') }}"  >
                        <div class="invalid-feedback">PAN Number?</div>
                        <input type="hidden" name="" value="">    
                    </div>
                    <div class="form-group">
                        <label class=" text-lg-center ">State Code<span class="required individual_required"></span></label>
                        <input type="text" name="state_code" class="form-control" id="individuat_state" value="{{ old('state_code', isset($data) ? $data->state_code : '') }}" >
                        <div class="invalid-feedback">State Code?</div>
                    </div>
                    <div class="form-group {{ $errors->has('pan_img') ? 'has-error' : '' }}">               
                        <label class="text-lg-center ">Upload Pan Card<span class="required individual_required"></span> </label>
                        <input type="file" class="form-control" name="pan_img"  id="individuat_pan_img" accept="image/png, image/jpg, image/jpeg" >
                        <span style="color:red; font-weight:5px; font-size:12px;">Note:  Supports  &nbsp;&nbsp;  All  &nbsp;&nbsp; File   Formats,</span>
                        <div class="invalid-feedback">Pan Image?</div>
                    </div>
                   
                </div>
              
                <div class="col-md-12" id="international"> 

                <div class="form-group">
                        <label class=" text-lg-center ">Company Registration Number</label>
                        <input type="text" name="company_register" class="form-control" id="international_register"  value="{{ old('company_register', isset($data) ? $data->company_register : '') }}">
                        <div class="invalid-feedback">Company Registration Numbe</div>
                        <input type="hidden" name="id" value="">
                 </div>
                <!-- <div class="form-group">
                        <label class=" text-lg-center ">Address<span class="required individual_required">*</span></label>
                        <textarea name="address" class="form-control" id="international_address" rows="5"  value="{{ old('address', isset($address) ? $address->address : '') }}"  ></textarea>
                        <div class="invalid-feedback">Please Enter Address?</div>
                        <input type="hidden" name="id" value="">
                 </div>


                </div>    -->
             </div>   
             <div class="col-md-12">
                <div class="form-group">
                        <label class=" text-lg-center ">Address<span class="required individual_required"></span></label>
                        <textarea name="address" class="form-control" id="individuat_address" rows="5"  value="{{ old('address', isset($address) ? $address->address : '') }}"  ></textarea>
                        <div class="invalid-feedback">Please Enter Address?</div>
                        <input type="hidden" name="id" value="">
                    </div>
                    </div>  
   
        </div>
    <div>
      
      <input class="btn btn-danger" id="email_validation" type="submit" value="{{ trans('global.save') }}">

      </div>

      
    </form>  
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>

$(document).ready(function() {
    // $('#roles').change(function(e) {
    //     var my_roles=$(this).val();
    //     checkuser_type(my_roles);
    // });
checkuser_type();
    function checkuser_type(){
        //const my_roles_data= my_roles.toString().split(",");
        var user_type=$('input[name=user_type]:checked').val();
      
        //if(jQuery.inArray('vendor', my_roles_data) >= 0){
            $('#company_data').show();
            if(user_type == 'individual'){
                $('#individuat_pan').prop("required", false);
               
              //  $('#individuat_address').prop("required", false);
              
                $('.individual_required').css('display', 'inherit');

                $('#company_gst').prop("required", false);
              //  $('#individuat_state').prop("required", false);
                //$('#individuat_pan_img').prop("required", false);
                // $('#company_state_code').prop("required", false);
                // $('#company_pan_img').prop("required", false);
              //  $('#company_address').prop("required", false);
            }else if(user_type == 'company'){
                $('#company_gst').prop("required", true);
                // $('#company_state_code').prop("required", true);
                // $('#company_pan_img').prop("required", true);
              //  $('#company_address').prop("required", false);

                $('#individuat_pan').prop("required", false);
             //   $('#individuat_state').prop("required", false);
              //  $('#individuat_address').prop("required", false);
              //  $('#individuat_pan_img').prop("required", false);
                $('.individual_required').css('display', 'inherit');
            }else if(user_type == 'international'){
            
                $('#international_register').prop("required", false);
            
            
            
            }else{
                $('#individuat_pan').prop("required", false);
                $('#individuat_state').prop("required", false);
             //   $('#individuat_address').prop("required", false);
                $('#individuat_pan_img').prop("required", false);
                $('.individual_required').css('display', 'none');
                $('#company_gst').prop("required", false);
                $('#individuat_pan_img').prop("required", false);
                $('#individuat_state').prop("required", false);
            }
               
            // } else if(user_type == 'company') {
            //     //$('#company_register').prop("required", false);
                $('#individual').hide();
           
            // if(user_type == 'individual'){
            //     $('#individuat_pan').prop("required", true);
            //     $('#individuat_state').prop("required", true);
            //     $('#individuat_address').prop("required", true);
            //     $('#individuat_pan_img').prop("required", true);
            //     //$('.individual_required').hide();
            //     $('.individual_required').css('display', 'inherit');


            //     $('#company_gst').prop("required", true);
            //   //  $('#company_pan').prop("required", false);
            //     $('#company_state_code').prop("required", true);
            //     $('#company_pan_img').prop("required", true);
            //     $('#company_address').prop("required", true);
            // }else {
            //     //$('#company_register').prop("required", false);
            //     $('#company_gst').prop("required", false);
            //     //$('#company_pan').prop("required", false);
            //     // $('#company_state_code').prop("required", false);
            //     // $('#company_pan_img').prop("required", false);
            //     // $('#company_address').prop("required", false);

            //     $('#individuat_pan').prop("required", true);
            //     $('#individuat_state').prop("required", true);
            //     $('#individuat_address').prop("required", false);
            //     $('#individuat_pan_img').prop("required", false);
            //     //$('.individual_required').hide();
            //     $('.individual_required').css('display', 'inherit');
            
            // }
            
            // if(user_type == 'international'){

            //     $('#company_gst').prop("required", false);
            //     $('#company_pan').prop("required", false);
            //     $('#company_state_code').prop("required", false);
            //     $('#company_pan_img').prop("required", false);
            //     $('#company_address').prop("required", false);



            //     $('#individuat_pan').prop("required", false);
            //     $('#individuat_state').prop("required", false);
            //     $('#individuat_address').prop("required", false);
            //     $('#individuat_pan_img').prop("required", false);
            //     //$('.individual_required').hide();
            //     $('.individual_required').css('display', 'inherit');
            // }
        
          
        //}else{
            
       // }
    }
   
  $('input[name=user_type]:radio').change(function(e) {
    let value = e.target.value.trim()
   // $('[class^="form"]').css('display', 'none');
    switch (value) {
      case 'company':
        $('#company_form').show()
        $('#individual_form').show()
        $('#international').hide()
        break;
      case 'individual':
        $('#individual_form').show()
        $('#company_form').hide()
        $('#international').hide()
        break;
        case 'international':
            $('#international').show()
        $('#company_form').hide()
        $('#individual_form').hide()
       
        break;
      default:
        break;
    }
    //var my_roles=$('#roles').val();
    checkuser_type();
  })
})
  </script>     
@endsection
