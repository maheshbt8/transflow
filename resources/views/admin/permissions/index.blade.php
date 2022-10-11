@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
      <?php
      $dev_per=0;
      if(isset($_GET['dev']) && $_GET['dev'] == 'Kpt_Dev_2021'){
        $dev_per=1;
      }
      ?>
      <?php 
      if($dev_per ==1){
      ?>
        <a class="btn btn-success" href="{{ route("admin.permissions.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}
        </a>
        <?php }?>
    </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.permission.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Permission">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>Sl.No</th>
                        <th>Module Name</th>
                        <th>Function Name</th>
                        <th>Key</th>
                        <!-- <th>
                            {{ trans('cruds.permission.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.permission.fields.key') }}
                        </th> -->
                         <?php 
              if($dev_per ==1){
              ?>
                        <th class="noExport">
                            &nbsp;
                        </th>
                         <?php }?>
                    </tr>
                </thead>
                <tbody>
                <?php $i=1;
                ?>
                @foreach($permissions as $par)
                <tr>
                  <td></td>
                  <td>{{$i}}</td>
                  <td colspan="3"><b>{{$par->label}}</b></td>
                   <?php 
              if($dev_per ==1){
              ?>
                  <td>
                    <a class="btn btn-xs btn-primary" href="{{ route('admin.permissions.show', $par->id) }}">{{ trans('global.view') }}</a>
                    <a class="btn btn-xs btn-info" href="{{ route('admin.permissions.edit', $par->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                  </td>
                   <?php }?>
                </tr>
                <?php $j=1;?>
                @foreach($par->childs as $child)
                <tr>
                  <td></td>
                  <td>{{ $i.'.'.$j }}</td>
                  <td colspan="2"><span class="float-right">{{$child->label}}</span></td>
                  <td>{{$child->name}}</td>
                   <?php 
              if($dev_per ==1){
              ?>
                  <td>
                    <a class="btn btn-xs btn-primary" href="{{ route('admin.permissions.show', $child->id) }}">{{ trans('global.view') }}</a>
                    <a class="btn btn-xs btn-info" href="{{ route('admin.permissions.edit', $child->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
               
                  </td>
                   <?php }?>
                </tr>
                <?php $j++;?>
                @endforeach
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
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.permissions.mass_destroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      // if (ids.length === 0) {
      //   alert('{{ trans('global.datatables.zero_selected') }}')

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
  $('.datatable-Permission:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection