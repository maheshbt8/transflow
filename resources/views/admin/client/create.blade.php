@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.clientuser.title_singular') }}
    </div>
	
	@if(Session::has('flash_message'))
          <div class="alert alert-success">
              {{ Session::get('flash_message') }}
          </div>
	@endif

    <div class="card-body">
   
        <div class="d-flex justify-content">
        <form action="{{ route("admin.client.store") }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
            @csrf
            <div class="span9" id="error_on_header"></div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name" class="required">{{ trans('cruds.user.fields.name') }}</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                <input type="hidden" name="kpt_org" value="<?php echo get_user_org('org','org_id');?>">
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
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
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password" class="required">{{ trans('cruds.user.fields.password') }}</label>
                <input type="password" id="password" name="password" class="form-control" required>
                @if($errors->has('password'))
                    <em class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.password_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('organization') ? 'has-error' : '' }}">
            <label for="org" class="required">Organizations</label>
             <select name="organization" id="org" class="form-control select2" required="" >
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
                <!-- <p class="helper-block">
                    {{ trans('cruds.user.fields.password_helper') }}
                </p> -->
                <div class="invalid-feedback" for="">Must enter your Organization</div>
             </div>
            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                <label for="roles" class="required">{{ trans('cruds.user.fields.roles') }}</label>
                <select name="roles[]" id="roles" class="form-control select2" multiple  required>
				 @if(isset($roles) && !empty ($roles))				 
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ (isset($user) && $user->roles->contains($role->name)) ? 'selected' : '' }}>{{ $role->label }}</option>
                    @endforeach
				@endif
                </select>
                @if($errors->has('roles'))
                    <em class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </em>
                @endif
                <!-- <p class="helper-block">
                    {{ trans('cruds.user.fields.roles_helper') }}
                </p> -->
                <div class="invalid-feedback" for="">Must Select your Role</div>
            </div>		
			
			
			<?php
                $user_role = Auth::user()->roles[0]->name;
                if ($user_role == 'clientuser') {
                    ?> 
			<?php
                }
            ?>
	
		<!-- Department block -->
			 
					<p class="helper-block">
						{{ trans('cruds.user.fields.roles_helper') }}
					</p>				
			 </div>
		  <!-- Department block -->

            <div>
                <input class="btn btn-danger" id="email_validation" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
        </div>
    </div>
</div>
@endsection