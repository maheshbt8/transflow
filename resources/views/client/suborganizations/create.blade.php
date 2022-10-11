@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.kptsuborganization.title_singular') }}
    </div>

    <div class="card-body">
        <div class="d-flex justify-content">
        <form action="{{ route("admin.clientsuborg.store") }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
            @csrf

            <div class="form-group {{ $errors->has('org_name') ? 'has-error' : '' }}">
                <label for="email" class="required">{{ trans('cruds.kptsuborganization.fields.sub_org_name') }}</label>
                <input type="text" id="sub_org_name" name="sub_org_name" class="form-control" value="{{ old('sub_org_name', isset($user) ? $user->sub_org_name : '') }}" required>
                @if($errors->has('sub_org_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('sub_org_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
                <div class="invalid-feedback" for="">Must enter your suborganization</div>
            </div>
            <div class="form-group {{ $errors->has('organization') ? 'has-error' : '' }}">
            <?php
                if(get_user_org('name')== "orgadmin"){
            ?>
            <label for="org" class="required">Organizations</label>
             <select name="organization" id="org" class="form-control select2"  >
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
                  
                <?php
                }else{
            ?>
            <input type="hidden" value="<?php echo get_user_org('org','org_id');?>" id="org_id" name="organization"/>
            <?php }?>
              
             </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
