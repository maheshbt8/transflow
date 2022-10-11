@extends('layouts.admin')
@section('content')
<style>
    .select-checkbox{
        display:none;
    }
</style>
@if(checkpermission('org_manage','add'))

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.org.create") }}">
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
                        <th width="10">
                            Sl.No 
                        </th>
                        <th>
                            {{ trans('cruds.kptorganization.fields.org_id') }}
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
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; ?>
                    @foreach($kptorganizations as $key => $kptorg)
                        <tr data-entry-id="{{ $kptorg ?? ''->org_id }}">
                            <td>
                            {{$i++}}
                            </td>
                            <td>
                            <img src="<?php echo env('AWS_CDN_URL') . '/org_images/'.$kptorg->org_id.'.png' ;?>" height="60" width="60"/>
                                {{ $kptorg->org_id ?? '' }}
                            </td>
                            <td>
                                {{ $kptorg->org_name ?? '' }}
                            </td>
                            <td>
								<?php
								if ($kptorg->org_status == 1)
									echo "Active";
								elseif($kptorg->org_status == 0)
									echo "Inactive";
                                ?>
								
								
                            </td>
                            <td><?php
								$user = $user->find($kptorg->created_by);
								echo $user->name;
								?>
                            </td>
                            <td>
                                <a class="btn btn-xs" href="{{ route('admin.org.show', $kptorg->org_id) }}">
                                    <i class="fa fa-eye text-success"></i>
                                </a>
								
								<?php
									$created_by = $kptorg->created_by;
									$session_userid = Auth::user()->id;									
									$user_role = Auth::user()->roles[0]->name;						
									
									if(($created_by == $session_userid) || ($user_role =='administrator') || ($user_role =='orgadmin')){
								?>
                                 @if(checkpermission('org_manage','update'))
                                <a class="btn btn-xs" href="{{ route('admin.org.edit', $kptorg->org_id) }}" >
                                    <i class="fa fa-edit text-info"></i>
                                </a>
								@endif
                                @if(checkpermission('org_manage','delete'))
								<form action="{{ route('admin.org.destroy', $kptorg->org_id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-xs"><i class='fa fa-trash text-danger'></i></button>
                                </form>
								@endif
								<?php
								  }
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
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('users_manage')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.org.mass_destroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
    //   var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
        //   return $(entry).data('entry-id')
      });

    //   if (ids.length === 0) {
    //     alert('{{ trans('global.datatables.zero_selected') }}')

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
