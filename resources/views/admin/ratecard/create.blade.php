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
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            
		
			
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
            
					
			
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>
        </div>
    </div>
</div>
@endsection