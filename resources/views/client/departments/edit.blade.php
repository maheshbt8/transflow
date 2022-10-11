@extends('layouts.admin')
@section('content')



<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.kptdepartment.title_singular') }}
    </div>

    <div class="card-body">
      <div class="d-flex justify-content">
        <form action="{{ route("admin.clientdept.update", [$clientdepartment?? ''->client_dpt_id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('org_name') ? 'has-error' : '' }}">
                <label for="name" class="required">{{ trans('cruds.kptdepartment.fields.dept_name') }}</label>
				<input type="text" name="client_dpt_name" placeholder="Department Name" class="form-control" value="{{ $clientdepartment->client_dpt_name }}" required>                                                         
                @if($errors->has('org_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('org_name') }}
                    </em>
                @endif
                <p class="helper-block" style="color:red; font-size:10px;">
                    {{ trans('cruds.kptdepartment.fields.dept_name_helper') }}
                </p>
            </div>                    
            
            <div class="form-group {{ $errors->has('organization') ? 'has-error' : '' }}">
            <label for="org" class="required">Organizations</label>
             <select name="client_org_id" id="org" class="form-control select2 select_client_org_list"  >
             <option value="" >Select Organization</option>
              @foreach($orgs as $org)
                        <option value="{{ $org->org_id }}" >{{ $org->org_name }}</option>
               @endforeach                    
              </select>
                
                 @if($errors->has('organization'))
                    <em class="invalid-feedback" >
                        {{ $errors->first('organization') }}
                    </em>
                @endif  
                             
             </div>
            <div class="form-group {{ $errors->has('sub_organization') ? 'has-error' : '' }}">
            <label for="org" class="required">Sub Organizations</label>
             <select name="client_suborg_id" id="client_sub_organization_list" class="form-control select2 "  >
             <option value="" >Select Sub Organization</option>
              @foreach($suborgs as $key => $sub_org)
                        <option value="{{ $sub_org->client_suborg_id }}"  >{{ $sub_org->client_suborg_name }}</option>
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