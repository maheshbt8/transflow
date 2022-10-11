@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Approve Translation Data
    </div>
    <div class="card-header">
        <form action="{{ route("admin.translationcsvfile.tm_approve_data") }}" method="POST" class="form-inline">
          @csrf
            <!-- <div class="form-group col-md-4">
                <label for="source_language" class="required">Source Language</label>
             <select name="source_language" id="source_language" class="form-control select2" required >
             <option value="" >Select Source Language</option>
              @foreach($loc_languages as $key => $lang)
                        <option value="{{ $lang->lang_code }}" >{{ $lang->lang_name }}</option>
               @endforeach                    
              </select>
            </div> -->
            <!-- <div class="form-group col-md-4">
                <label for="source_language" class="required">Target Language</label>
             <select name="target_language" id="target_language" class="form-control select2" required >
             <option value="" >Select Target Language</option>
              @foreach($loc_languages as $key => $lang)
                        <option value="{{ $lang->lang_code }}" >{{ $lang->lang_name }}</option>
               @endforeach                    
              </select>
            </div> -->
            <div class="form-group col-md-4">
                <input type="Submit" class="btn btn-success" name="" value="Transfer to TMS">
            </div>
                
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th class="td_check_show">Sl.No</th>
                        <th>
                            {{ trans('cruds.locrequest.fields.source_language') }}
                        </th>
                        <th>
                            {{ trans('cruds.locrequest.fields.source_text') }}
                        </th>
                        <th>
                            {{ trans('cruds.locrequest.fields.target_language') }}
                        </th>
                        <th>
                            {{ trans('cruds.locrequest.fields.target_text') }}
                        </th>
                        <th>
                            {{ trans('cruds.locrequest.fields.action') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; ?>
                  @foreach($translation_memory_data as $key => $translation_memory)
                    <tr data-entry-id="{{ $translation_memory->sid }}" id="data_tr_{{ $translation_memory->sid }}">
                      <td class="td_check_show">{{$i++}}</td>
                      <td>{{ $translation_memory->source_lang }}</td>
                      <td>{{ $translation_memory->source_text }}</td>
                      <td>{{ $translation_memory->target_lang }}</td>
                      <td>{{ $translation_memory->target_text }}</td>
                      <td><a href="#" class="delete" data-id="<?php echo $translation_memory->sid ?? '';?>"><i class="fa fa-trash-alt bg-danger"></i></a></td>
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
 $('body').on('click', '.delete', function() {
            if (confirm("Are You Sure Want Delete?") == true) {
                var id = $(this).data('id');
                // ajax
                $.ajax({
                    type: "POST",
                    url: "<?php echo route('admin.translationcsvfile.delete_data');?>",
                    headers: {'x-csrf-token': _token},
                    data: {
                        id: id
                    },
                    success: function(res) {
                       
                        //$(this).closest('tr').remove();
                        $('#data_tr_'+id).remove()
                        // $('#name').html(res.name);
                        // $('#age').html(res.age);
                        // $('#email').html(res.email);
                        // window.location.reload();
                    }
                });
            }
        });
</script>
@endsection
