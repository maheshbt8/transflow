@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>
	

    <div class="card-body">
        <div class="d-flex justify-content">
        <form action="{{ route("admin.suborgusers.update", [$SubOrgUsers->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="span9" id="error_on_header"></div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name" class="required">{{ trans('cruds.user.fields.name') }}</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($SubOrgUsers) ? $SubOrgUsers->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email" class="required">{{ trans('cruds.user.fields.email') }}</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($SubOrgUsers) ? $SubOrgUsers->email : '') }}" required>
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
			
			<?php
				$user_role = Auth::user()->roles[0]->name;	
				if($user_role == "administrator"){
			?>
			
			<!-- Organization block -->
			<div class="form-group {{ $errors->has('organization') ? 'has-error' : '' }}">
                <label for="roles" class="required">Organization</label>
                 <select name="organization" id="organization" class="form-control select2 select_org_list" required >
					<option value="" >Select Organization</option>
                    @foreach($kptorganization as $id => $org)
                        <option value="{{ $id }}" {{ (isset($user_org_result[0]->org_id) && $user_org_result[0]->org_id==$id) ? 'selected' : '' }}>{{ $org }}</option>
                    @endforeach
                </select>
				<input type="hidden" name="old_org_id"  value="{{(isset($user_org_result[0]->org_id) && $user_org_result[0]->org_id)? $user_org_result[0]->org_id:''}}">
                @if($errors->has('roles'))
                    <em class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.roles_helper') }}
                </p>
            </div>
			<!-- Organization block -->
			<?php
				}
			?>
			
			
			<!-- Sub Organization block -->
			<div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                <label for="roles" class="required">Sub Organization</label>
                 <select name="suborganization" id="sub_organization_list" class="form-control select2"  required>
					<option value="" >Select Sub Organization</option>
                    @foreach($kptsuborganization as $id => $org)
                        <option value="{{ $id }}" {{ (isset($user_org_result[0]->sub_id) && $user_org_result[0]->sub_id==$id) ? 'selected' : '' }}>{{ $org }}</option>
                    @endforeach
                </select>
				<input type="hidden" name="old_sub_id"  value="{{(isset($user_org_result[0]->sub_id) && $user_org_result[0]->sub_id)? $user_org_result[0]->sub_id:''}}">
                @if($errors->has('roles'))
                    <em class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.roles_helper') }}
                </p>
            </div>		
		    <!-- Sub Organization block -->	

			
			
            <div>
                <input class="btn btn-danger" id="email_validation" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
        </div>
    </div>
</div>
@endsection