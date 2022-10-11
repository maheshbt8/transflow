@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.kptdepartment.title_singular') }}
    </div>

    <div class="card-body">
      <div class="d-flex justify-content">
        <form action="{{ route("admin.clientdept.store") }}" method="POST" enctype="multipart/form-data"  class="needs-validation" novalidate="">
            @csrf
            
            <div class="form-group {{ $errors->has('department_name') ? 'has-error' : '' }}">
                <label for="email" class="required">{{ trans('cruds.kptdepartment.fields.dept_name') }}</label>
                <input type="text" id="department_name" name="department_name" class="form-control" value="{{ old('department_name', isset($user) ? $user->department_name: '') }}" required>
                @if($errors->has('department_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('department_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
                <div class="invalid-feedback" for="">Must enter your Department </div>
            </div>
            <div class="form-group {{ $errors->has('organization') ? 'has-error' : '' }}">
           
            <label for="org" class="required">Organizations</label>
             <select name="client_org_id" id="org" class="form-control select2 select_client_org_list"  >
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
               
            <input type="hidden" value="<?php echo get_user_org('org','org_id');?>" id="org_id" name="organization"/>
                    
             </div>
            
            <div class="form-group {{ $errors->has('sub_organization') ? 'has-error' : '' }}">
           
            <label for="org" class="required">Sub Organizations</label>
             <select name="client_suborg_name" id="client_sub_organization_list" class="form-control select2"  >
             <option value="" >Select Organization First</option>  
             @foreach($clientsuborg as $clientsuborgs)
							<option value="{{$clientsuborgs->client_suborg_id}}" >{{$clientsuborgs->client_suborg_name}}</option>
				@endforeach	
              </select>
              
                 @if($errors->has('sub_organization'))
                    <em class="invalid-feedback">
                        {{ $errors->first('sub_organization') }}
                    </em>
                @endif  
                                     
             </div>
            
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
           
        </form>
      </div>
    </div>
</div>
@endsection
