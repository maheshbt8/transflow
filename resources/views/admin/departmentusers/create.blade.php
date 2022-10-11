@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
    </div>
	
	@if(Session::has('flash_message'))
          <div class="alert alert-success">
              {{ Session::get('flash_message') }}
          </div>
	@endif

    <div class="card-body">
        <div class="d-flex justify-content">
        <form action="{{ route("admin.departmentusers.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
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
            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                <label for="roles" class="required">{{ trans('cruds.user.fields.roles') }}</label>
                <select name="roles" id="roles" class="form-control select2"  required>
				 @if(isset($roles) && !empty ($roles))				 
                    @foreach($roles as $id => $roles)
                        <option value="{{ $id }}" {{ (isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
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
			
		
			
			<!-- Organization block -->
			 <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
             <?php
                if(get_user_org('name') == "administrator"){
            ?>
			<label for="roles" class="required">Organization</label>
			 <select name="organization" id="roles" class="form-control select2 select_org_list" required >
			 <option value="" >Select Organization</option>
                    @foreach($kptorganization as $id => $org)
                        <option value="{{ $id }}" >{{ $org }}</option>
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

			
			
			<!-- Sub Organization block -->
            
			<div class="form-group {{ $errors->has('suborganization') ? 'has-error' : '' }}">
            <?php
                if(get_user_org('name')== "administrator" || get_user_org('name')== "orgadmin" ){
            ?>
				<label for="suborganization" class="required">Sub Organization</label>
				 <select name="suborganization" id="sub_organization_list" class="form-control select2 select_suborg_list" required >
				 <option value="" >Select Sub Organization</option>
						@foreach($kptsuborganization as $id => $suborg)
							<option value="{{ $id }}" >{{ $suborg }}</option>
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
				<label for="department" class="required">Department</label>
				 <select name="department" id="department_list" class="form-control select2" required >
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
            <input type="hidden" value="<?php echo get_user_org('org','sub_sub_id');?>" id="sub_sub_id" name="department"/>
            <?php }?>
					<p class="helper-block">
						{{ trans('cruds.user.fields.roles_helper') }}
					</p>				
			 </div>
		  <!-- Department block -->			
			
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
        </div>
    </div>
</div>
@endsection