@extends('layouts.admin')
@section('content')

@if(checkpermission('client_dept_manage'))
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.clientdept.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.kptdepartment.title_singular') }}
            </a>
        </div>
    </div>
@endif

@if(Session::has('flash_message'))
        <div class="alert alert-success">
            {{ Session::get('flash_message') }}
        </div>
@endif

@if(Session::has('flash_message_error'))
        <div class="alert alert-danger">
            {{ Session::get('flash_message_error') }}
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
                            {{ trans('cruds.kptdepartment.fields.dept_id') }}
                        </th>
                        <th>
                            {{ trans('cruds.kptdepartment.fields.dept_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.kptdepartment.fields.dept_status') }}
                        </th>
                        <th>
                            {{ trans('cruds.kptdepartment.fields.created_by') }}
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.org_label') }} / {{ trans('cruds.user.fields.suborg_label') }}
                        </th>
                        <th class="noExport">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientdept as $dept )
                        <tr data-entry-id="">
                            <td>
                           
                            </td>
                            <td>
                            {{ $dept->client_dpt_id ?? '' }}
                            </td>
                            <td>
                            {{ $dept->client_dpt_name ?? '' }}
                            </td>
                            <td>
								<?php
								if ($dept->client_dpt_status == 1)
									echo "Active";
								elseif($dept->client_dpt_status == 0)
									echo "Inactive";
                                ?>
								
								
                            </td>
                            <td><?php
								$user_data = $user->find($dept->created_by);
                                if (!empty($user_data)) {
                                    echo $user_data->name;
                                }
								?>
								
                            </td>
                            <td >
                            <?php							
                                $sub_org_details = DB::table('client_sub_organizations')->where('client_suborg_id', $dept->client_suborg_id)->get();
                                if(count($sub_org_details) >0){
								    $org_details = DB::table('clientorganizations')->where('org_id', $sub_org_details[0]->client_org_id)->get();
                                }
							?>
                        	@if(isset($org_details) && count($org_details) >0)
							<span class="badge badge-info"><?php echo UCfirst($org_details[0]->org_name); ?></span>
							@endif
                            @if(count($sub_org_details) >0)
                            <span class="badge badge-info"><?php echo  UCfirst($sub_org_details[0]->client_suborg_name); ?></span>							
                            @endif						
                           
                        </td>
                            <td style="width:15% !important;">
                                <a class="btn btn-xs" href="{{route('admin.clientdept.show', $dept->client_dpt_id) }}">
                                    <i class="fa fa-eye text-success"></i>
                                </a>
								
								
                                 @if(checkpermission('client_client_dept_manage','update'))
                                <a class="btn btn-xs" href="{{route('admin.clientdept.edit', $dept->client_dpt_id) }}" >
                                    <i class="fa fa-edit text-info"></i>
                                </a>@endif
								@if(checkpermission('client_client_dept_manage','delete'))
								<form action="{{route('admin.clientdept.destroy', $dept->client_dpt_id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-xs"><i class='fa fa-trash text-danger'></i></button>
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