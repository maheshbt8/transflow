@extends('layouts.admin')
@section('content')
@foreach($users as $key => $user)
    @foreach($user->roles()->pluck('name') as $role)
                                  <?php $role_array[]=$role;?>
                                @endforeach
                                @endforeach
<p>
  <a class="btn btn-primary" href="#" role="button">
    Users
  </a>
  <a class="btn btn-default" href="{{ route('admin.personaldetails.index', ['user'=>$AuthenticatedUsers->id]) }}" role="button">
    Bank Details
  </a>
  <a class="btn btn-default" href="{{ route('admin.personaldetails.address', ['user'=>$AuthenticatedUsers->id]) }}" role="button">
    Address
  </a>
  <a class="btn btn-default" href="{{ route('admin.personaldetails.personal_data', ['user'=>$AuthenticatedUsers->id]) }}" role="button">
    Personal
  </a>
</p>                           
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} 
    </div>


    <div class="card-body">
        <div class="d-flex justify-content">
        <form action="{{ route("admin.authenticatedusers.update", [$AuthenticatedUsers->id]) }}" method="POST" enctype="multipart/form-data">
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
                <input type="mobile" id="mobile" name="mobile" class="form-control" value="{{ old('name', isset($AuthenticatedUsers) ? $AuthenticatedUsers->mobile : '') }}" required>
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
                <input type="password" id="password" name="password" class="form-control" value="{{ old('password', isset($AuthenticatedUsers) ? $AuthenticatedUsers->password : '') }}">
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
                <select name="roles[]" id="roles" class="form-control select2" multiple required>
                <!--  (isset($AuthenticatedUsers) && $AuthenticatedUsers->roles->contains($id)) ? 'selected' : '' 		  -->
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" <?php if(in_array($role->name,$role_array)) echo'selected';?> >{{ $role->label }}</option>
                    @endforeach
				
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
				//if($user_role == "administrator"){
			?>

             <div class="form-group ">
                <label for="Employee" class ="required">Employee Id</label>
                <input type="Employee" id="employee_id" name="employee_id" class="form-control" value="{{ old('employee_id', isset($AuthenticatedUsers) ? $AuthenticatedUsers->employee_id : '') }}" required>
                @if($errors->has(''))
                    <em class="invalid-feedback">
                        {{ $errors->first('') }}
                    </em>
                @endif
                <p class="helper-block">
                   
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
                        <option value="{{ $id }}" {{ (isset($user_org_result[0]->org_id) && $user_org_result[0]->org_id==$id) ? 'selected' : '' }}>{{ $org }}</option>
                    @endforeach
                </select>
				<input type="hidden" name="old_org_id"  value="{{(isset($user_org_result[0]->org_id) && $user_org_result[0]->org_id)? $user_org_result[0]->org_id:''}}">
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
			<div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
            <?php
                if(get_user_org('name')== "administrator" || get_user_org('name')== "orgadmin" ){
            ?>    
            <label for="roles">Sub Organization</label>
                 <select name="suborganization" id="sub_organization_list" class="form-control select2 select_suborg_list" >
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
				
			
			<?php
				//}
			?>
			
			
			<!-- Department block -->
			<div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
            <?php
                if(get_user_org('name')== "administrator" || get_user_org('name')== "orgadmin" || get_user_org('name')== "suborgadmin" ){
            ?>    
            <label for="roles"> Department</label>
                 <select name="department_id" id="department_list" class="form-control select2"  >
					<option value="" >Select Department</option>
                    @foreach($KptDepartments as $id => $org)
                        <option value="{{ $id }}" {{ (isset($user_org_result[0]->sub_sub_id) && $user_org_result[0]->sub_sub_id==$id) ? 'selected' : '' }}>{{ $org }}</option>
                    @endforeach
                </select>
				<input type="hidden" name="old_dept_id"  value="{{(isset($user_org_result[0]->sub_sub_id) && $user_org_result[0]->sub_sub_id)? $user_org_result[0]->sub_sub_id:''}}">
                @if($errors->has('roles'))
                    <em class="invalid-feedback">
                        {{ $errors->first('roles') }}
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
			
			
            <div>
                <input class="btn btn-danger" id="email_validation" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
        </div>
    </div>
</div>
@endsection