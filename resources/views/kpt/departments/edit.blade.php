@extends('layouts.admin')
@section('content')



<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.kptdepartment.title_singular') }}
    </div>

    <div class="card-body">
      <div class="d-flex justify-content">
        <form action="{{ route("admin.departments.update", [$KptDepartments ?? ''->department_id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('org_name') ? 'has-error' : '' }}">
                <label for="name" class="required">{{ trans('cruds.kptdepartment.fields.dept_name') }}</label>
				<input type="text" name="department_name" placeholder="Department Name" class="form-control" value="{{ $KptDepartments->department_name }}" required>                                                         
                @if($errors->has('org_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('org_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.kptdepartment.fields.dept_name_helper') }}
                </p>
            </div>                    
            
            <div class="form-group {{ $errors->has('organization') ? 'has-error' : '' }}">
            <label for="org" class="required">Organizations</label>
             <select name="organization" id="org" class="form-control select2 select_org_list"  >
             <option value="" >Select Organization</option>
              @foreach($orgs as $key => $org)
                        <option value="{{ $org->org_id }}" {{ ($org->org_id == $org_id)? 'selected' : ''}} >{{ $org->org_name }}</option>
               @endforeach                    
              </select>
                
                 @if($errors->has('organization'))
                    <em class="invalid-feedback">
                        {{ $errors->first('organization') }}
                    </em>
                @endif               
             </div>
            <div class="form-group {{ $errors->has('sub_organization') ? 'has-error' : '' }}">
            <label for="org" class="required">Sub Organizations</label>
             <select name="sub_organization" id="sub_organization_list" class="form-control select2"  >
             <option value="" >Select Sub Organization</option>
              @foreach($sub_orgs as $key => $sub_org)
                        <option value="{{ $sub_org->sub_org_id }}" {{ ($sub_org->sub_org_id == $KptDepartments->sub_org_id)? 'selected' : ''}} >{{ $sub_org->sub_org_name }}</option>
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