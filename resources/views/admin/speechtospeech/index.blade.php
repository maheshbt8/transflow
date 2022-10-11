@extends('layouts.admin')
@section('content')

    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.speechtospeech.create") }}">
                {{ trans('global.add') }} video File
            </a>
        </div>
    </div>

<div class="card">
    <div class="card-header">
       speech to video {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.videototext.fields.job_id') }}
                        </th>
                        <th>
                           video
                        </th>
                        <th>
                            {{ trans('cruds.speechtospeech.fields.source_language') }}
                        </th>
                        <th>
                            {{ trans('cruds.speechtospeech.fields.target_languages') }}
                            <!-- target language -->
                        </th>
						<th>
                            {{ trans('cruds.speechtospeech.fields.transcribe_text') }}
                            <!-- transcribe text -->
                        </th>
						
						<th>
                            <!-- {{ trans('cruds.videototext.fields.uploaded_at') }} -->
                            translated text
                        </th>	
                        <th>
                            <!-- {{ trans('cruds.videototext.fields.uploaded_at') }} -->
                            status
                        </th>	
                        <th>
                            <!-- {{ trans('cruds.videototext.fields.uploaded_at') }} -->
                            Uploaded at
                        </th>
						<th>
                            {{ trans('cruds.videototext.fields.Action') }} 
                        </th>			
						
						
                    </tr>
                </thead>
                    <tbody>
                     @foreach($data as $speechdata)
                        <tr >
                           <td></td>
                            <td>
                                {{  $speechdata->job_id  ?? '' }}
                            </td>
                            <td>
                                {{ $speechdata->job_media_name ?? '' }}
                            </td>
                             <td>
                                 {{  $speechdata->job_source_language ?? '' }} 
                            
                            <td> 
                                {{  $speechdata->job_target_language ?? '' }}
                            <!-- </td>  -->
                            <td>
                                {{  $speechdata->job_org_filename ?? '' }}
                            </td>
                            <!-- <td>
                                {{  $speechdata->l ?? '' }}
                            </td> -->
                            <td>
                                {{  $speechdata->job_video_media_url ?? '' }}
                            </td>
                           
                            <td>
                                {{  $speechdata->job_status ?? '' }}
                            </td>
                            <td>
                                {{  $speechdata->job_created_at ?? '' }}
                            </td>
                            
                            <td>
                                <a class="btn btn-xs btn-primary" href="">
                                    {{ trans('global.view') }}
                                </a>
                                <a class="btn btn-xs btn-primary" href="">
                                    {{ trans('global.edit') }}
                                </a>
                                
                                
                                
                                <form action="" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>                              
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
