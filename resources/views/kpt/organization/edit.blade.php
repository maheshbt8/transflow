@extends('layouts.admin')
@section('content')
<style>
    .bank {
  
  height: 650px;
  overflow: scroll;
}
</style>
<p>
  <a class="btn btn-primary" href="#" role="button">
    Organization
  </a>
  <a class="btn btn-default" href="{{ route('admin.personaldetails.index', ['org'=>$kptorganization->org_id]) }}" role="button">
    Bank Details
  </a>
  <a class="btn btn-default" href="{{ route('admin.personaldetails.address', ['org'=>$kptorganization->org_id]) }}" role="button">
    Address
  </a>
  <a class="btn btn-default" href="{{ route('admin.personaldetails.personal_data',['org'=>$kptorganization->org_id]) }}" role="button">
    Personal Data
  </a>
</p>
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.kptorganization.title_singular') }}
    </div>
	
	

    <div class="card-body">
        <div class="d-flex justify-content">
        <form action="{{ route("admin.org.update", [$kptorganization ?? ''->org_id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('org_name') ? 'has-error' : '' }}">
                <label for="name" class="required">{{ trans('cruds.kptorganization.fields.org_name') }}</label>
				<input type="text" name="org_name" placeholder="Org Name" class="form-control" value="{{ $kptorganization->org_name }}" required>                                                         
                @if($errors->has('org_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('org_name') }}
                    </em>
                @endif
                <!-- <p class="helper-block">
                    {{ trans('cruds.kptorganization.fields.org_name_helper') }}
                </p> -->
            </div>  
            <div class="form-group {{ $errors->has('org_img') ? 'has-error' : '' }}">               
                <label>Source File </label>
                <input type="file" class="form-control" name="org_img" accept="image/png, image/jpg, image/jpeg" >
                <span style="color:red;">Note: Supports only .jpg, .jpeg, .png</span>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
        </div>
    </div>
</div>








@endsection