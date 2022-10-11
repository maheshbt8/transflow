@extends('layouts.admin')
@section('content')
@if( checkpermission('department_users_manage'))
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
        @if(checkpermission('department_users_manage','add'))
            <a class="btn btn-success" href="{{ route("admin.departmentusers.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.kptdepartment.title_singular') }}
            </a>
            @endif
        </div>
    </div>
@endif

@if(Session::has('flash_message'))
        <div class="alert alert-success">
            {{ Session::get('flash_message') }}
        </div>
@endif





<div class="card">
    <div class="card-header">
        {{ trans('cruds.kptdepartment.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.user.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.roles') }}
                        </th>
						 <th>
                            {{ trans('cruds.kptdepartment.fields.created_by') }}
                        </th>
						<th>
                        {{ trans('cruds.user.fields.org_label') }} / {{ trans('cruds.user.fields.suborg_label') }} / {{ trans('cruds.user.fields.dept_label') }}
                        </th>
						
						
                        <th style="width:15% !important;" class="noExport">
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $user->id ?? '' }}
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->email ?? '' }}
                            </td>						
                            <td>
                                @foreach($user->roles()->pluck('name') as $role)
                                    <span class="badge badge-info">{{ $role }}</span>
                                @endforeach
                            </td>
							<td><?php
								$user1 = $user->find($user->created_by);
								echo $user1->name;
								?>
                            </td>
							<td>
							<?php
                            $org_details = DB::table('kptorganizations')->join('user_orgizations', 'kptorganizations.org_id', '=', 'user_orgizations.org_id')->where('user_id', $user->id)->get();								
								$suborg_details = DB::table('kptsuborganizations')->join('user_orgizations', 'kptsuborganizations.sub_org_id', '=', 'user_orgizations.sub_id')->where('user_id', $user->id)->get();

								$dept_details = DB::table('kptdepartments')->join('user_orgizations', 'kptdepartments.department_id', '=', 'user_orgizations.sub_sub_id')->where('user_id', $user->id)->get();	
								
							?>
							@if(count($org_details) >0)
							<span class="badge badge-info"><?php echo UCfirst($org_details[0]->org_name); ?></span>
                            @endif
                            @if(count($suborg_details) >0)
							<span class="badge badge-info"><?php echo UCfirst($suborg_details[0]->sub_org_name); ?></span>
							@endif
                            @if(count($dept_details) >0)
                            <span class="badge badge-info"><?php echo  UCfirst($dept_details[0]->department_name); ?></span>							
                            @endif
                            </td>							
								
                            <td style="width:15% !important;">
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.departmentusers.show', $user->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                                @if(checkpermission('department_users_manage','update'))
                                <a class="btn btn-xs btn-info" href="{{ route('admin.departmentusers.edit', $user->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                                 @endif
                                 @if(checkpermission('department_users_manage','delete'))
                                <form action="{{ route('admin.departmentusers.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>
                                  @endif
                            </td>

                        </tr>
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

</script>
@endsection
