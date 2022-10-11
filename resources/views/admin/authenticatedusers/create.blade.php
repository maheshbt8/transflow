@extends('layouts.admin')
@section('content')
<style>
    /* This CSS class is applied, by default to all the labels and
       textboxes. The JavaScript just adds or removes it as needed. */
    #company_form{ display:none; }
    .individual_required{display: none;}
    #company_data{display: none;}
    .msme_data{display: none;}
    .select2 {
width:100%!important;
}
  </style>

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} users

        
    </div>
	
	@if(Session::has('flash_message'))
          <div class="alert alert-success">
              {{ Session::get('flash_message') }}
          </div>
	@endif

    <div class="card-body">
       
         <form action="{{ route("admin.authenticatedusers.store") }}" method="POST" enctype="multipart/form-data"  class="needs-validation" novalidate="">
            @csrf
 <div class="row">
    <div class="col-md-5">
            <div class="span9" id="error_on_header"></div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name" class="required">{{ trans('cruds.user.fields.name') }}</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
                <div class="invalid-feedback" for="">Must enter your Name</div>
            </div>
            <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                <label for="mobile" class="required">{{ trans('cruds.user.fields.mobile') }}</label>
                <input type="mobile" id="mobile" name="mobile" class="form-control" value="{{ old('mobile', isset($user) ? $user->mobile : '') }}" required>
                @if($errors->has('mobile'))
                    <em class="invalid-feedback">
                        {{ $errors->first('mobile') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
                <div class="invalid-feedback" for="">Must enter your Mobile Number</div>
            </div>

            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email" class="required">{{ trans('cruds.user.fields.email') }}</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
                @if($errors->has('email'))
                    <em class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
                <div class="invalid-feedback" for="">Must enter your Email</div>
            </div>

           
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password" class="required">{{ trans('cruds.user.fields.password') }}</label>
                <input type="password" id="password" name="password" class="form-control" value="{{ old('mobile', isset($user) ? $user->mobile : '') }}" required>
                @if($errors->has('password'))
                    <em class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.password_helper') }}
                </p>
                <div class="invalid-feedback" for="">Must enter your Password</div>
            </div>
            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                <label for="roles" class="required">{{ trans('cruds.user.fields.roles') }}</label>
                <select name="roles[]" id="roles" class="form-control select2" multiple="multiple" required>
				 @if(isset($roles) && !empty ($roles))				 
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ (in_array($role->name,(old('roles') ?? []))? 'selected' : '') }}  {{ (isset($user) && $user->roles->contains($role->name)) ? 'selected' : '' }}>{{ $role->label }}</option>
                    @endforeach
				@endif
                </select>
                @if($errors->has('roles'))
                    <em class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.roles_helper') }}
                </p>
                <div class="invalid-feedback" for="">Must enter your Role</div>
            </div>
            <div class="form-group {{ $errors->has('employee_id') ? 'has-error' : '' }} ">
                <label for="Employee" class ="required">Employee Id</label>
                <input type="Employee" id="employee_id" name="employee_id" class="form-control" value="{{ old('employee_id', isset($user) ? $user->employee_id : '') }}" required>
                @if($errors->has(''))
                    <em class="invalid-feedback">
                        {{ $errors->first('') }}
                    </em>
                @endif
                <p class="helper-block">
                {{ trans('cruds.user.fields.roles_helper') }}
                </p>
            </div>
			
			
			<!-- Organization block -->
            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
             <?php
                if(get_user_org('name')== "administrator"){
              ?>
			  <label for="roles" class="required">Organization</label>

			 <select name="organization" id="organization" class="form-control select2 select_org_list" required >
			 <option value="" >Select Organization</option>
                 
                    @foreach($kptorganization as $id => $org)
                        <option value="{{ $id }}"  {{ (($id == (old('organization') ?? ''))? 'selected' : '') }}   {{ (isset($user) && $user->org->contains($org->name)) ? 'selected' : '' }}>{{ $org }}</option>
                    @endforeach
                </select>
				
				 @if($errors->has('roles'))
                    <em class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </em>
                @endif
                <div class="invalid-feedback" for="">Must select  your organization</div>
                <?php
                }else{
            ?> 
            <input type="hidden" value="<?php echo get_user_org('org','org_id');?>" id="org_id" name="organization"/>
            <?php }?> 
                <p class="helper-block">
                    {{ trans('cruds.user.fields.roles_helper') }}
                </p>				
			 </div>
			<!-- Organization block -->
			
			
			
			<!-- Sub Organization block -->
			<div class="form-group {{ $errors->has('suborganization') ? 'has-error' : '' }}">
            <?php
                if(get_user_org('name')== "administrator" || get_user_org('name')== "orgadmin" ){
            ?>
				<label for="suborganization" >Sub Organization</label>
				 <select name="suborganization" id="sub_organization_list" class="form-control select2 select_suborg_list" >
				 <option value="" >Select Sub Organization</option>
						@foreach($kptsuborganization as $id => $suborg)
							<option value="{{ $id  }} " >{{ $suborg }}</option>
						@endforeach
					</select>
					
					 @if($errors->has('suborganization'))
						<em class="invalid-feedback">
							{{ $errors->first('suborganization') }}
						</em>
					@endif
                    <?php
                }else{
            ?> 
            <input type="hidden" value="<?php echo get_user_org('org','sub_id');?>" id="sub_org_id" name="suborganization"/>
            <?php }?>  
					<p class="helper-block">
						{{ trans('cruds.user.fields.roles_helper') }}
					</p>				
			 </div>
		  <!-- Sub Organization block -->			
         
		   <!-- Department block -->
			<div class="form-group {{ $errors->has('department') ? 'has-error' : '' }}">
			<?php
                if(get_user_org('name')== "administrator" || get_user_org('name')== "orgadmin" || get_user_org('name')== "suborgadmin" ){
            ?>	
            <label for="department">Department </label>
				 <select name="department" id="department_list" class="form-control select2" >
					<option value="" >Select Department</option>
						@foreach($KptDepartments as $id => $suborg)
							<option value="{{ $id }}" >{{ $suborg }}</option>
						@endforeach
				</select>
					
					 @if($errors->has('Department'))
						<em class="invalid-feedback">
							{{ $errors->first('Department') }}
						</em>
					@endif
                    <?php
                }else{
            ?> 
            <input type="hidden" value="<?php echo get_user_org('org','sub_sub_id');?>" id="department_list" name="department"/>

            <?php }?> 
					<p class="helper-block">
						{{ trans('cruds.user.fields.roles_helper') }}
					</p>				
			 </div>
		  <!-- Department block -->
    </div>
        <div class="col-md-2"></div>
        <div class="col-md-5" id="company_data">
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
                        <label class=" text-lg-center ">Register Number</label>
                        <input type="text" name="company_register" class="form-control" id="company_register"  value="{{ old('company_register', isset($data) ? $data->company_register : '') }}">
                        <div class="invalid-feedback">Register Number?</div>
                        <input type="hidden" name="id" value="">
                    </div>
                    <div class="form-group">
                        <label class=" text-lg-center ">GST Number<span class="required">*</span></label>
                        <input type="text" name="gst" class="form-control" id="company_gst"   value="{{ old('gst', isset($data) ? $data->gst : '') }}"  >
                        <div class="invalid-feedback">GST Numbe?</div>
                        <input type="hidden" name="id" value="">
                    </div>

                    <div class="form-group">
                        <label class=" text-lg-center ">MSME Registered<span class="required"></span></label>
                        <select name="msme_registered" class="form-control" id="individuat_msme"  value=""  >
                            <option value="">Select  MSME Registered</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        <div class="invalid-feedback">Please Select MSME Registered?</div>

                        <input type="hidden" name="id" value="">
                    </div>

                    <div class="form-group  msme_data" id="upload_msme">               
                        <label class="text-lg-center ">Upload MSME Registration Certificate. <span class="required">*</span> </label>
                        <input type="file" class="form-control" name="msme_file"  id="individuat_msme_file" >
                        <span style="color:red; font-weight:5px; font-size:12px;">Note:  Supports  &nbsp;&nbsp;  All  &nbsp;&nbsp; File   Formats,</span>
                        <div class="invalid-feedback">Please Upload MSME Registration Certificate.?</div>
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
                        <input type="file" class="form-control" name="pan_img"  id="individuat_pan_img" >
                        <span style="color:red; font-weight:5px; font-size:12px;">Note:  Supports  &nbsp;&nbsp;  All  &nbsp;&nbsp; File   Formats,</span>
                        <div class="invalid-feedback">Pan Image?</div>
                    </div>
                   
                    <div class="form-group">
                        <label class=" text-lg-center ">Address<span class="required individual_required">*</span></label>
                        <textarea name="address" class="form-control" id="individuat_address" rows="5"  value="{{ old('address', isset($address) ? $address->address : '') }}"  ></textarea>
                        <div class="invalid-feedback">Please Enter Address?</div>
                        <input type="hidden" name="id" value="">
                    </div>


                        <div class="form-group ">
                            <label class="text-lg-center ">Bank Name<span class="required">*</span></label>
                           
                             <input type="text" name="bank_name" class="form-control" id="individuat_bankname"  value="{{ old('bank_name', isset($bank_details) ? $bank_details->bank_name : '') }}" required>
                           
                              <div class="invalid-feedback">Bank Name?</div>
                                <input type="hidden" name="id" value="">
                                
                        </div>

                        <div class="form-group">
                            <label class="text-lg-center">Bank Address <span class="required">*</span></label>
                            
                                <input type="text" name="bank_address" class="form-control" id="individuat_bank_address"  value="{{ old('bank_address', isset($bank_details) ? $bank_details->bank_address : '') }}"  required>
                            
                            <div class="invalid-feedback">Bank Address ?</div>
                        </div>


                        <div class="form-group">
                            <label class="text-lg-center ">Account Name<span class="required">*</span></label>
                            
                                <input type="text" name="account_name" id="individuat_account_name" class="form-control"  value="{{ old('account_name', isset($bank_details) ? $bank_details->account_name : '') }}">
                            
                            <div class="invalid-feedback">Account Name?</div>
                        </div>

                        <div class="form-group ">
                            <label class="text-lg-center ">Account Number<span class="required">*</span></label>
                            
                                <input type="text" class="form-control" name="account_number" id="individuat_account_no" required  value="{{ old('account_number', isset($bank_details) ? $bank_details->account_number : '') }}">
                               
                            
                            <div class="invalid-feedback">Account Number?</div>
                         </div>
                         <div class="form-group">
                            <label class="text-lg-center ">IFSC Code<span class="required">*</span></label>
                            
                                <input type="text" class="form-control" name="ifsc_code"  id="individuat_ifsc_code" value="{{ old('ifsc_code', isset($bank_details) ? $bank_details->ifsc_code : '') }}" >
                               
                            
                            <div class="invalid-feedback">IFSC Code?</div>
                         </div>
                         <div class="form-group">
                            <label class="text-lg-center ">Routing Number<span class="required"></span></label>
                            
                                <input type="text" class="form-control" name="routing_number"  id="individuat_routing_number" value="{{ old('routing_number', isset($bank_details) ? $bank_details->routing_number : '') }}" >
                               
                           
                            <div class="invalid-feedback">Routing Number?</div>
                          </div>
                        
                          <div class="form-group ">
                            <label class="text-lg-center">SWIFT Code<span class="required"></span></label>
                           
                            <input type="text" class="form-control" name="swift_code" id="individuat_swift_code"  value="{{ old('swift_code', isset($bank_details) ? $bank_details->swift_code : '') }}" >
                               
                           
                            <div class="invalid-feedback">SWIFT Code?</div>
                          </div>
                         
                         <div class="form-group">
                            <label class="text-lg-center">Sort Code<span class="required"></span></label>
                           
                                <input type="text" class="form-control" name="sort_code"  id="individuat_soft_code"  value="{{ old('sort_code', isset($bank_details) ? $bank_details->sort_code : '') }}">
                               
                           
                            <div class="invalid-feedback">Sort Code?</div>
                         </div>
                        
                     
                        
                </div>


                
                <!-- <div class="form-group row">
                    <label class=" text-lg-center ">PAN Number<span class="required">*</span></label>
                    <input  type="text" name="pan" class="form-control" id="company_pan"  value="{{ old('pan', isset($data) ? $data->pan : '') }}" >
                    <div class="invalid-feedback">PAN Number?</div>
                    <input type="hidden" name="" value="">    
                </div>
                <div class="form-group row">
                    <label class=" text-lg-center ">State Code<span class="required">*</span></label>
                    <input type="text" name="state_code" class="form-control" id="company_state_code" value="{{ old('state_code', isset($data) ? $data->state_code : '') }}" >
                    <div class="invalid-feedback">State Code?</div>
                </div>
                <div class="form-group row {{ $errors->has('pan_img') ? 'has-error' : '' }}">               
                    <label class="text-lg-center ">Upload Pan Card<span class="required">*</span></label>
                    <input type="file" class="form-control"  name="pan_img" id="company_pan_img" accept="image/png, image/jpg, image/jpeg"  >
                    <span style="color:red; font-weight:5px; font-size:12px;">Note: Supports only .jpg, .jpeg, .png</span>
                    <div class="invalid-feedback">Pan Image?</div>
                </div>
                <div class="form-group  ">
                    <label class=" text-lg-center ">Address<span class="required">*</span></label>
                    <textarea name="address" class="form-control" id="company_address" rows="5"  value="{{ old('address', isset($address) ? $address->address : '') }}"  ></textarea>
                    <div class="invalid-feedback">Please Enter Address?</div>
                    <input type="hidden" name="id" value="">
                </div> -->
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
    $('#roles').change(function(e) {
        var my_roles=$(this).val();
        checkuser_type(my_roles);
    });
    





$("#individuat_msme").change(function() {
  if ($(this).val() == "yes") {
    $('#upload_msme').show();
    $('#individuat_msme_file').attr('required', '');
    $('#individuat_msme_file').attr('data-error', 'This field is required.');
  } else {
    $('#upload_msme').hide();
    $('#individuat_msme_file').removeAttr('');
    $('#individuat_msme_file').removeAttr('data-error');
  }
});

    function checkuser_type(my_roles){
        const my_roles_data= my_roles.toString().split(",");
        var user_type=$('input[name=user_type]:checked').val();
           
        if(jQuery.inArray('vendor', my_roles_data) >= 0){
            $('#company_data').show();
            $('#employee_id').val('<?php echo generateemployeeID();?>');
            if(user_type == 'individual'){
                $('#individuat_pan').prop("required", false);
                $('#individuat_state').prop("required", false);
                $('#individuat_msme').prop("required", false);
                $('#individuat_address').prop("required", true);
                $('#individuat_pan_img').prop("required", false);
                $('#individuat_bankname').prop("required", true);
                $('#individuat_bank_address').prop("required", true);
                $('#individuat_account_name').prop("required", true);
                $('#individuat_account_no').prop("required", true);
                $('#individuat_ifsc_code').prop("required", true);
                $('#individuat_routing_number').prop("required", false);
                $('#individuat_swift_code').prop("required", false);
                $('#individuat_soft_code').prop("required", false);
                
             //$('#individuat_msme').prop("required", false);

                $('.individual_required').css('display', 'inherit');

                $('#company_gst').prop("required", false);
                // $('#company_state_code').prop("required", false);
                // $('#company_pan_img').prop("required", false);
                // $('#company_address').prop("required", false);
            }else{
                $('#company_gst').prop("required", true);
                $('#individuat_msme').prop("required", false);
                // $('#company_state_code').prop("required", true);
                // $('#company_pan_img').prop("required", true);
                // $('#company_address').prop("required", true);

                $('#individuat_pan').prop("required", false);
                $('#individuat_state').prop("required", false);

                $('#individuat_address').prop("required", true);
                $('#individuat_pan_img').prop("required", false);
                $('#individuat_bankname').prop("required", true);
                $('#individuat_bank_address').prop("required", true);
                $('#individuat_account_name').prop("required", true);
                $('#individuat_account_no').prop("required", true);
                $('#individuat_ifsc_code').prop("required", true);
                $('#individuat_routing_number').prop("required", false);
                $('#individuat_swift_code').prop("required", false);
                $('#individuat_soft_code').prop("required", false);
                
                
                $('.individual_required').css('display', 'inherit');
            }
         
        }else{
            $('#company_data').hide();
            if(user_type == 'individual'){
                $('#individuat_pan').prop("required", false);
                $('#individuat_state').prop("required", false);
                $('#individuat_msme').prop("required", false);
                $('#individuat_address').prop("required", false);
                $('#individuat_pan_img').prop("required", false);
                $('#individuat_bankname').prop("required", false);
                $('#individuat_bank_address').prop("required", false);
                $('#individuat_account_name').prop("required", false);
                $('#individuat_account_no').prop("required", false);
                $('#individuat_ifsc_code').prop("required", false);
                $('#individuat_routing_number').prop("required", false);
                $('#individuat_swift_code').prop("required", false);
                $('#individuat_soft_code').prop("required", false);
                
                //$('.individual_required').hide();
                $('.individual_required').css('display', 'inherit');

             //   $('#individuat_msme').prop("required", false);
                $('#company_gst').prop("required", false);
              //  $('#company_pan').prop("required", false);
                $('#company_state_code').prop("required", false);
                $('#company_pan_img').prop("required", false);
                $('#company_address').prop("required", false);
            }else{
                //$('#company_register').prop("required", false);
                $('#company_gst').prop("required", false);
                $('#individuat_msme').prop("required", false);
                //$('#company_pan').prop("required", false);
                // $('#company_state_code').prop("required", false);
                // $('#company_pan_img').prop("required", false);
                // $('#company_address').prop("required", false);

                $('#individuat_pan').prop("required", false);
                $('#individuat_state').prop("required", false);
                $('#individuat_address').prop("required", false);
                $('#individuat_pan_img').prop("required", false);
                //$('.individual_required').hide();
                $('.individual_required').css('display', 'inherit');
            }
        }
    }
  
  $('input[name=user_type]:radio').change(function(e) {
    let value = e.target.value.trim()
   // $('[class^="form"]').css('display', 'none');
    switch (value) {
      case 'company':
        $('#company_form').show()
        $('#individual_form').show()
        break;
      case 'individual':
        $('#individual_form').show()
        $('#company_form').hide()
        break;
      default:
        break;
    }
    var my_roles=$('#roles').val();
    checkuser_type(my_roles);
  })
})
</script>     
@endsection
