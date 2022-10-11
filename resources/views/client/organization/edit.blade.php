@extends('layouts.admin')
@section('content')
<p>
  <a class="btn btn-primary" href="#" role="button">
   Client 
  </a>
  <!-- <a class="btn btn-default" href="{{ route('admin.personaldetails.index', ['client_org'=>$clientorganization->org_id]) }}" role="button">
    Bank Details
  </a> -->
  <a class="btn btn-default" href="{{ route('admin.personaldetails.address', ['client_org'=>$clientorganization->org_id]) }}" role="button">
    Address
  </a>
  <a class="btn btn-default" href="{{ route('admin.personaldetails.personal_data', ['client_org'=>$clientorganization->org_id]) }}" role="button">
    personal data
  </a>
</p>

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.kptorganization.title_singular') }}
    </div>
	
	

    <div class="card-body">
   
        <div class="d-flex justify-content">
        <form action="{{ route("admin.clientorg.update", [$clientorganization ?? ''->org_id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('organization') ? 'has-error' : '' }}">
            <?php
            if(get_user_org('name')== "administrator"){
            ?>
            <label for="org" class="required">Organizations</label>
             <select name="kpt_org" id="org" class="form-control select2"  >
             <option value="" >Select Organization</option>
              @foreach($orgs as $key => $org)
                        <option value="{{ $org->org_id }}" {{ ($org->org_id == $clientorganization->kpt_org)? 'selected' : ''}} >{{ $org->org_name }}</option>
               @endforeach                    
              </select>
                
                 @if($errors->has('organization'))
                    <em class="invalid-feedback">
                        {{ $errors->first('organization') }}
                    </em>
                @endif  
                <?php
                }else{
            ?>
            <input type="hidden" value="<?php echo get_user_org('org','org_id');?>" id="org_id" name="kpt_org"/>
            <?php }?>             
             </div>
            <div class="form-group {{ $errors->has('org_name') ? 'has-error' : '' }}">
                <label for="name" class="required">{{ trans('cruds.kptorganization.fields.org_name') }}</label>
				<input type="text" name="org_name" placeholder="Org Name" class="form-control" value="{{ $clientorganization->org_name }}" required>                                                         
                @if($errors->has('org_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('org_name') }}
                    </em>
                @endif
                <p class="helper-block" style="color:red; font-size:10px;">
                    {{ trans('cruds.kptorganization.fields.org_name_helper') }}
                </p>
                <!-- <div class="invalid-feedback" for="">Organization name should be unique</div>  -->
            </div>                    
           
            <div>
                <input class="btn btn-danger" id="email_validation" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
        </div>
    </div>
</div>
@endsection