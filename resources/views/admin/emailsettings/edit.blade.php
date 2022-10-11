@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.e_settings.title_singular') }} {{ trans('global.create') }}
    </div>
   
    <div class="card-body">
       
        <div class="d-flex justify-content">
        <form action="{{ url('admin/emailsettings/update') }}" method="POST" enctype="multipart/form-data">
            @csrf 
            @method('PUT')
            
            <input type="hidden" name="email_setting_id" value="{{ $edit_email_setting->email_setting_id }}">
            <div class="form-group {{ $errors->has('template') ? 'has-error' : '' }}">
            <label for="template" class="required">{{ trans('cruds.e_settings.fields.template') }}</label>
            <select name="email_template" id="template" class="form-control select2" >
                <option value=''>Select Template</option>
                @foreach( $email_templates as  $item)
              
                        <option value="{{ $item->id }}"   {{ (old('email_template') == $item->id)? 'selected' : '' }} {{ ($edit_email_setting->template_id ==  $item->id )? 'selected' : '' }} >{{ $item->email_template}}</option>
                    @endforeach
            </select>
                 @if($errors->has('email_template'))
                    <em class="invalid-feedback">
                        {{ $errors->first('email_template') }}
                    </em>
                @endif               
        </div>

        


        <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
             <?php
                if(get_user_org('name')== "administrator"){
              ?>
			  <label for="roles" class="required">Organization</label>
			 <select name="organization" id="roles" class="form-control select2 select_org_list"  required >
			 <option value="" >Select Organization</option>
                    @foreach($kptorganization as $id => $org)
                        <option value="{{ $id }}"  {{ (old('organization') == $id)? 'selected' : '' }} {{ ($edit_email_setting->email_org ==  $id )? 'selected' : '' }} >{{ $org }}</option>
                    @endforeach
                </select>
				
				 @if($errors->has('roles'))
                    <em class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </em>
                @endif
                
                <?php
                }else{
            ?> 
            <input type="hidden" value="<?php echo get_user_org('org','org_id');?>" id="org_id" name="organization"/>
            <?php }?> 
                <p class="helper-block">
                    {{ trans('cruds.user.fields.roles_helper') }}
                </p>				
			 </div>
        <!---org end-->
        <div class="form-group {{ $errors->has('to_address') ? 'has-error' : '' }}">
            <label for="to_address" class="required">{{ trans('cruds.e_settings.fields.to_address') }}</label>
             <textarea rows="4" cols="50" name="to_address" id="to_address" class="form-control">{{ old('to_address')? old('to_address') : $edit_email_setting->email_to_address }}</textarea>
                 @if($errors->has('to_address'))
                    <em class="invalid-feedback">
                        {{ $errors->first('to_address') }}
                    </em>
                @endif               
        </div>
        <div class="form-group {{ $errors->has('cc_address') ? 'has-error' : '' }}">
            <label for="cc_address" class="">{{ trans('cruds.e_settings.fields.cc_address') }}</label>
             <textarea rows="4" cols="50" name="cc_address" id="cc_address" class="form-control">{{ old('cc_address')? old('cc_address') : $edit_email_setting->email_cc_address }}</textarea>
                 @if($errors->has('cc_address'))
                    <em class="invalid-feedback">
                        {{ $errors->first('cc_address') }}
                    </em>
                @endif               
        </div>
        <div class="form-group {{ $errors->has('bcc_address') ? 'has-error' : '' }}">
            <label for="bcc_address" class="">{{ trans('cruds.e_settings.fields.bcc_address') }}</label>
             <textarea rows="4" cols="50" name="bcc_address" id="cc_address" class="form-control">{{ old('bcc_address')? old('bcc_address') : $edit_email_setting->email_bcc_address }}</textarea>
                 @if($errors->has('bcc_address'))
                    <em class="invalid-feedback">
                        {{ $errors->first('bcc_address') }}
                    </em>
                @endif               
        </div>
            
            <div>&nbsp;</div>                   
            <div>
                <input class="btn btn-danger" id="translation_submit" type="submit" value="{{ trans('global.submit') }}">
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
