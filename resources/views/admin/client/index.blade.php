@extends('layouts.admin')
@section('content')
@if(checkpermission('client_user'))
    <!-- <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.client.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.clientuser.title_singular') }}
            </a>
        </div>
    </div> -->
@endif

@if(Session::has('flash_message'))
        <!-- <div class="alert alert-success">
            {{ Session::get('flash_message') }}
        </div> -->
@endif
<style type="text/css">
    span.select2.select2-container.select2-container--default{
    width: auto !important;
}
</style>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.clientuser.title_singular') }} {{ trans('global.list') }}
        <div class="btn-group float-right">
            @if(checkpermission('client_user','add'))
                <a class="btn btn-success btn-sm" href="{{ route("admin.client.create") }}">{{ trans('global.add') }} {{ trans('cruds.clientuser.title_singular') }}
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
                        <!-- <th width="10">

                        </th> -->
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
                        {{ trans('cruds.user.fields.org_label') }} / {{ trans('cruds.user.fields.suborg_label') }} / {{ trans('cruds.user.fields.dept_label') }}
                        </th>
                        <th>
                            {{ trans('cruds.kptdepartment.fields.created_by') }}
                        </th>
                        <th class="ext-th-data">
                            GST
                        </th>
                        <th class="ext-th-data">
                        PAN
                        </th>
                       
                        <th class="noExport">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1;?>
                    @foreach($users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            <!-- <td>

                            </td> -->
                            <td>
                                {{ $i }}
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
                            <?php
                            $org_details = DB::table('clientorganizations')->join('client_user_orgizations', 'clientorganizations.org_id', '=', 'client_user_orgizations.org_id')->where('user_id', $user->id)->get();    
                          //   print_r($org_details);die;                                                   
                        $suborg_details = DB::table('client_sub_organizations')->join('client_user_orgizations', 'client_sub_organizations.client_suborg_id', '=', 'client_user_orgizations.sub_id')->where('user_id', $user->id)->get();
                        $dept_details = DB::table('client_departments')->join('client_user_orgizations', 'client_departments.client_dpt_id', '=', 'client_user_orgizations.sub_sub_id')->where('user_id', $user->id)->get();  
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
                            <td><?php
                                $user1 = $user->find($user->created_by);
                                if(!empty($user1)){
                                    echo $user1->name;
                                }
                                ?>
                            </td>
                            <?php if(count($org_details) >0){
                                $c_org_id=$org_details[0]->org_id;   
                                $details=$personal_details->where(['user_id' => $c_org_id,'type'=>'client_org'])->get();
                            }else{
                                $details='';
                            } 
                            ?>
                            <td><ul>@foreach($details as $id=> $dtl)<?php if($dtl->gst != ''){?> <li>{{ $dtl->gst ?? '' }}</li>  <?php }?>@endforeach</ul></td> 
                            <td><ul>@foreach($details as $id=> $dtl) <?php if($dtl->pan != ''){?> <li>{{ $dtl->pan ?? '' }}</li> <?php }?> @endforeach</ul></td> 
                            <td style="width:15% !important;">
                                <a class="btn btn-xs" href="{{ route('admin.client.show', $user->id) }}">
                                    <i class="fa fa-eye text-success"></i>
                                </a>

                                <a class="btn btn-xs" href="{{ route('admin.client.edit', $user->id) }}">
                                    <i class="fa fa-edit text-info"></i>
                                </a>

                                <form action="{{ route('admin.client.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-xs"><i class='fa fa-trash text-danger'></i></button>
                                </form>

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
            window.location.href = "{{route('admin.client.index')}}?status="+multipleValues.join( "," );
        }else{
            window.location.href = "{{route('admin.client.index')}}";
        }
    })
</script>
@endsection
