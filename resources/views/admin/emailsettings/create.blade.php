@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.e_settings.title_singular') }} {{ trans('global.create') }}
    </div>

    <div class="card-body">
        <div class="d-flex justify-content">
        <form action="{{ route("admin.emailsettings.store") }}" method="POST" enctype="multipart/form-data">
            @csrf 

        <div class="form-group {{ $errors->has('template') ? 'has-error' : '' }}">
            <label for="template" class="required">{{ trans('cruds.e_settings.fields.template') }}</label>
            <select name="email_template" id="template" class="form-control select2" value="{{ old('email_template') }}">
                <option value=''>Select Template</option>
                @foreach( $email_template as $id => $item)
                        <option value="{{ $item->id }}"  >{{ $item->email_template}}</option>
                    @endforeach
            </select>
                 @if($errors->has('email_template'))
                    <em class="invalid-feedback">
                        {{ $errors->first('email_template') }}
                    </em>
                @endif               
        </div>

         	
<!----email--->

<!----organization--->
             <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
             <?php
                if(get_user_org('name')== "administrator"){
              ?>
			  <label for="roles" class="required">Organization</label>
			 <select name="organization" id="roles" class="form-control select2 select_org_list"   required >
			 <option value="" >Select Organization</option>
                    @foreach($kptorganization as $id => $org)
                        <option value="{{ $id }}"  {{ (old('organization') == $id)? 'selected' : '' }} >{{ $org }}</option>
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
			<!-- Organization block -->
			

        <!-- <div class="form-group {{ $errors->has('email_subject') ? 'has-error' : '' }}">
            <label for="email_subject" class="required">{{ trans('cruds.e_settings.fields.subject') }}</label>
             
             <select name="email_subject" id="template" class="form-control select2" value="{{ old('email_template') }}">
                <option value=''>Select subject</option>
                @foreach( $email_template as $id => $item)
                        <option value="{{ $id }}"  >{{ $item-> email_subject}}</option>
                    @endforeach
            </select>
        </div> -->
        <div class="form-group {{ $errors->has('to_address') ? 'has-error' : '' }}">
            <label for="to_address" class="required">{{ trans('cruds.e_settings.fields.to_address') }}</label>
             <textarea rows="4" cols="50" name="to_address" id="to_address" class="form-control" value="{{ old('to_address') }}">{{ old('email_to_address') }}</textarea>
                 @if($errors->has('to_address'))
                    <em class="invalid-feedback">
                        {{ $errors->first('to_address') }}
                    </em>
                @endif               
        </div>
        <div class="form-group {{ $errors->has('cc_address') ? 'has-error' : '' }}">
            <label for="cc_address" class="">{{ trans('cruds.e_settings.fields.cc_address') }}</label>
             <textarea rows="4" cols="50" name="cc_address" id="cc_address" class="form-control" value="{{ old('cc_address') }}">{{ old('email_cc_address') }}</textarea>
                 @if($errors->has('cc_address'))
                    <em class="invalid-feedback">
                        {{ $errors->first('cc_address') }}
                    </em>
                @endif               
        </div>
        <div class="form-group {{ $errors->has('bcc_address') ? 'has-error' : '' }}">
            <label for="bcc_address" class="">{{ trans('cruds.e_settings.fields.bcc_address') }}</label>
             <textarea rows="4" cols="50" name="bcc_address" id="bcc_address" class="form-control" value="{{ old('bcc_address') }}">{{ old('email_bcc_address') }}</textarea>
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
