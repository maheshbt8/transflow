@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.kptsuborganization.title_singular') }}
    </div>
	
	

    <div class="card-body">
        <div class="d-flex justify-content">
        <form action="{{ route("admin.suborganizations.update", [$KptSubOrganizations ?? ''->sub_org_id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('sub_org_name') ? 'has-error' : '' }}">
                <label for="name" class="required">{{ trans('cruds.kptsuborganization.fields.sub_org_name') }}</label>
				<input type="text" name="sub_org_name" placeholder="Sub Organization Name" class="form-control" value="{{ $KptSubOrganizations->sub_org_name }}" required>                                                         
                @if($errors->has('sub_org_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('sub_org_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.kptsuborganization.fields.sub_org_name_helper') }}
                </p>
            </div>                    
            
            <div class="form-group {{ $errors->has('organization') ? 'has-error' : '' }}">
            <label for="org" class="required">Organizations</label>
             <select name="organization" id="org" class="form-control select2"  >
             <option value="" >Select Organization</option>
              @foreach($orgs as $key => $org)
                        <option value="{{ $org->org_id }}" {{ ($org->org_id == $KptSubOrganizations->org_id)? 'selected' : ''}} >{{ $org->org_name }}</option>
               @endforeach                    
              </select>
                
                 @if($errors->has('organization'))
                    <em class="invalid-feedback">
                        {{ $errors->first('organization') }}
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