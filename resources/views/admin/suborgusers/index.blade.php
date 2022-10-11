@extends('layouts.admin')
@section('content')
@if(checkpermission('sub_org_users_manage','add'))
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.suborgusers.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.kptsuborganization_users.title_singular') }}
            </a>
        </div>
    </div>
@endif
<div class="card">
    <div class="card-header">
        {{ trans('cruds.kptsuborganization_users.title_singular') }} {{ trans('global.list') }}
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
                            {{ trans('cruds.user.fields.org_label') }} / {{ trans('cruds.user.fields.suborg_label') }}
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
							
							<td style="width:15% !important;">							
							<?php				
								$org_details = DB::table('kptorganizations')->join('user_orgizations', 'kptorganizations.org_id', '=', 'user_orgizations.org_id')->where('user_id', $user->id)->get();
								
								$suborg_details = DB::table('kptsuborganizations')->join('user_orgizations', 'kptsuborganizations.sub_org_id', '=', 'user_orgizations.sub_id')->where('user_id', $user->id)->get();		
							?>
                            
							@if(count($org_details) >0)
							<span class="badge badge-info"><?php echo UCfirst($org_details[0]->org_name); ?></span>
                            @endif
                            @if(count($suborg_details)>0)
							<span class="badge badge-info"><?php echo UCfirst($suborg_details[0]->sub_org_name); ?></span>
                            @endif
                            </td>							
							
							
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.suborgusers.show', $user->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                                @if(checkpermission('sub_org_users_manage','update'))
                                <a class="btn btn-xs btn-info" href="{{ route('admin.suborgusers.edit', $user->id) }}">
                                    {{ trans('global.edit') }}
                                </a>@endif
                                @if(checkpermission('sub_org_users_manage','delete'))
                                <form action="{{ route('admin.suborgusers.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('sub_org_users_manage')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.orgusers.mass_destroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  });
  $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection
