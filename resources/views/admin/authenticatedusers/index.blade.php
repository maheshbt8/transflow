@extends('layouts.admin')
@section('content')
@if(checkpermission('authenticate_user_manage','add'))
    <!-- <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.authenticatedusers.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.kptauthenticated_users.title_singular') }}
            </a>
        </div>
    </div> -->
@endif
<style type="text/css">
    span.select2.select2-container.select2-container--default{
    width: auto !important;
}
</style>
@if(Session::has('flash_message'))
        <div class="alert alert-success">
            {{ Session::get('flash_message') }}
        </div>
@endif


<div class="card">
    <div class="card-header">
            {{ trans('cruds.kptauthenticated_users.title_singular') }} {{ trans('global.list') }}
        <div class="btn-group float-right">
            @if(checkpermission('authenticate_user_manage','add'))
                <a class="btn btn-success btn-sm" href="{{ route("admin.authenticatedusers.create") }}">{{ trans('global.add') }} {{ trans('cruds.kptauthenticated_users.title_singular') }}
                </a>&nbsp;&nbsp;&nbsp;
            @endif
            <select name="roles[]" id="roles" class="form-control select2" multiple="multiple" required>
                 @if(isset($roles) && !empty ($roles))               
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ (in_array($role->name,$exp_status)? 'selected' : '') }}>{{ $role->label }}</option>
                    @endforeach
                @endif
            </select>
            <button type="button" class="btn btn-success btn-sm" id="search_users_list">Search</button>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th>
                            Sl.No.
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.mobile') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
                        <th>
                           Employee Id
                        </th>
						<th>
                        {{ trans('cruds.user.fields.org_label') }} / {{ trans('cruds.user.fields.suborg_label') }} / {{ trans('cruds.user.fields.dept_label') }}
                        </th>
                        <th class="ext-th-data">
                            GST
                        </th>
                        <th class="ext-th-data">
                        PAN
                        </th>
                        <th>
                            {{ trans('cruds.kptdepartment.fields.created_by') }}
                        </th>
                        <th style="width:15% !important;" class="noExport">
                           Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0;?>
                    @foreach($users as $key => $user)
                    <?php $details=$personal_details->where(['user_id' => $user->id,'type'=>'user'])->get();?>
                        <tr data-entry-id="{{ $user->id }}">
                            
                            <td>
                                {{ $i+1 }}
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->mobile ?? '' }}
                            </td>
                            <td>
                                {{ $user->email ?? '' }}
                            </td>
                            <td>
                                @foreach($user->roles()->pluck('label') as $role)
                                    <span class="badge badge-info">{{ $role }}</span>
                                @endforeach
                            </td>
                            <td>
                                {{ $user->employee_id ?? '' }}
                            </td>
							<td>
							<?php
                            $org_details = DB::table('kptorganizations')->join('user_orgizations', 'kptorganizations.org_id', '=', 'user_orgizations.org_id')->where('user_id', $user->id)->get();	  
                          //   print_r($org_details);die;													
                        $suborg_details = DB::table('kptsuborganizations')->join('user_orgizations', 'kptsuborganizations.sub_org_id', '=', 'user_orgizations.sub_id')->where('user_id', $user->id)->get();
                        $dept_details = DB::table('kptdepartments')->join('user_orgizations', 'kptdepartments.department_id', '=', 'user_orgizations.sub_sub_id')->where('user_id', $user->id)->get();	
                        ?>	
                        @if(count($org_details) >0)
							<span class="badge badge-info"><?php echo $org_details[0]->org_name ?? UCfirst($org_details[0]->org_name); ?></span>
                            @endif
                            @if(count($suborg_details) > 0)						
							<span class="badge badge-info"><?php echo $suborg_details[0]->sub_org_name ?? UCfirst($suborg_details[0]->sub_org_name); ?></span>
							@endif
                            @if(count($dept_details) > 0)
                            <span class="badge badge-info"><?php echo  $dept_details[0]->department_name ?? UCfirst($dept_details[0]->department_name); ?></span>
                            @endif
                            </td>
                            
                           
                            <td><ul>@foreach($details as $id=> $dtl) <li>{{ $dtl->gst ?? '' }}</li>  @endforeach</ul></td> 
                            <td><ul>@foreach($details as $id=> $dtl) <li>{{ $dtl->pan ?? '' }}</li>  @endforeach</ul></td> 
                                
                            
                            <td><?php
                                $user1 = $user->find($user->created_by);
                                if(!empty($user1)){
                                    echo $user1->name;
                                }
                                ?>
                            </td>						
                            <td style="width:15% !important;">
                                <a class="btn btn-xs" href="{{ route('admin.authenticatedusers.show', $user->id) }}">
                                    <i class="fa fa-eye text-success"></i>
                                </a>
                                @if(checkpermission('authenticate_user_manage','add'))
                                <a class="btn btn-xs" href="{{ route('admin.authenticatedusers.edit', $user->id) }}">
                                   <i class="fa fa-edit text-info"></i>
                                </a>
                                 @endif
                                 @if(checkpermission('authenticate_user_manage','add'))
                                <form action="{{ route('admin.authenticatedusers.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-xs"><i class='fa fa-trash text-danger'></i></button>
                                </form>
                                 @endif
                            </td>

                        </tr>
                        <?php $i++;?>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $('#search_users_list').click(function() {
        var multipleValues = $( "#roles" ).val() || [];
        if(multipleValues.length > 0){
            window.location.href = "{{route('admin.authenticatedusers.index')}}?status="+multipleValues.join( "," );
        }else{
            window.location.href = "{{route('admin.authenticatedusers.index')}}";
        }
        /*if(jQuery.inArray('all',multipleValues) !== -1){
                alert(multipleValues.join( ", " ));
        }else{
                alert('no'+multipleValues.join( ", " ));
        }*/
    })
</script>
@endsection
