@extends('layouts.admin')
@section('content')

@foreach($users as $key => $user)

    @foreach($user->roles()->pluck('name') as $role)
                                  <?php $role_array[]=$role;?>
                                @endforeach
                                @endforeach

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>
	

    <div class="card-body">
        <div class="d-flex justify-content">
        <form action="{{ route("admin.client.update", [$AuthenticatedUsers->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="span9" id="error_on_header"></div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name" class="required">{{ trans('cruds.user.fields.name') }}</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($AuthenticatedUsers) ? $AuthenticatedUsers->name : '') }}" required>
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
                <input type="mobile" id="mobile" name="mobile" class="form-control" value="{{ old('mobile', isset($AuthenticatedUsers) ? $AuthenticatedUsers->mobile : '') }}" required>
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
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($AuthenticatedUsers) ? $AuthenticatedUsers->email : '') }}" required>
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
                <input type="password" id="password" name="password" class="form-control">
                @if($errors->has('password'))
                    <em class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.password_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <input type="hidden" name="old_org_id"  value="{{(isset($user_org_result[0]->org_id) && $user_org_result[0]->org_id)? $user_org_result[0]->org_id:''}}"></div>
            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                <label for="roles" class="required">{{ trans('cruds.user.fields.roles') }}</label>
                <select name="roles[]" id="roles" class="form-control select2" multiple required>
				 @if(isset($roles) && !empty ($roles))				 
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" <?php if(in_array($role->name,$role_array)) echo'selected';?>  >{{ $role->label }}</option>
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
            </div>
			
			
			<?php
                $user_role = Auth::user()->roles[0]->name;
                if ($user_role == 'clientuser') {
                    ?>
            <?php
                }
            ?>
	
            <div>
                <input class="btn btn-danger" id="email_validation" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
    </div>
 </div>
</div>
@endsection