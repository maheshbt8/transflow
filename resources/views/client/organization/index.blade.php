@extends('layouts.admin')
@section('content')
@if(checkpermission('client_org_manage','add'))

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.clientorg.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.kptorganization.title_singular') }}
            </a>
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
        {{ trans('cruds.kptorganization.title_singular') }} {{ trans('global.list') }}
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
                            {{ trans('cruds.kptorganization.fields.org_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.kptorganization.fields.org_status') }}
                        </th>
                        <th>
                            {{ trans('cruds.kptorganization.fields.created_by') }}
                        </th>
                        <th class="noExport">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                $n = 1;?>
                    @foreach($clientorganizations as $key => $clientorg)
                        <tr data-entry-id="{{ $clientorg ?? ''->org_id }}">
                            <!-- <td>

                            </td> -->
                            <td>
                                <!-- {{ $clientorg->org_id ?? '' }} -->{{$n++}}
                            </td>
                            <td>
                            {{ $clientorg->org_name ?? '' }}
                            </td>
                            <td>
								<?php
								if ($clientorg->org_status == 1)
									echo "Active";
								elseif($clientorg->org_status == 0)
									echo "Inactive";
                                ?>
								
								
                            </td>
                            <td><?php
								$user_data = $user->find($clientorg->created_by);
                                if (!empty($user_data)) {
                                    echo $user_data->name;
                                }
								?>
                            </td>
                            <td>
                                <a class="btn btn-xs" href="{{ route('admin.clientorg.show', $clientorg->org_id) }}">
                                    <i class="fa fa-eye text-success"></i>
                                </a>
								
								<?php
									$created_by = $clientorg->created_by;
									$session_userid = Auth::user()->id;									
									$user_role = Auth::user()->roles[0]->name;						
									
									//if(($created_by == $session_userid) || ($user_role =='administrator')){
								?>
                                 @if(($created_by == $session_userid && checkpermission('client_org_manage','update')) || checkpermission('client_org_manage','update'))
                                <a class="btn btn-xs" href="{{ route('admin.clientorg.edit', $clientorg->org_id) }}" >
                                    <i class="fa fa-edit text-info"></i>
                                </a>
								@endif
                                @if(($created_by == $session_userid && checkpermission('client_org_manage','delete')) || checkpermission('client_org_manage','delete'))
								<form action="{{ route('admin.clientorg.destroy', $clientorg->org_id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-xs"><i class='fa fa-trash text-danger'></i></button>
                                </form>
								@endif
								<?php
								  //}
								?>  
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