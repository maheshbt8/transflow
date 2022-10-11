@extends('layouts.admin')
@section('content')
@php($k=1)
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.translationmemory.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.translation_memory.title_singular') }}
        </a>

        <a  type="button" href="{{ route("admin.translationmemory.addtms") }}" class="btn btn-success float-right">Add TMS</a>
    </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.locrequest.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="card-header">
        <form class="form-inline">
            <div class="form-group col-md-4">
                <label for="source_language" class="required">Source Language</label>
             <select name="source_language" id="source_language" class="form-control select2" required >
             <option value="" >Select Source Language</option>
              @foreach($loc_languages as $key => $lang)
                        <option value="{{ $lang->lang_code }}" >{{ $lang->lang_name }}</option>
               @endforeach                    
              </select>
            </div>
            <div class="form-group col-md-4">
                <label for="source_language" class="required">Target Language</label>
             <select name="target_language" id="target_language" class="form-control select2" required >
             <option value="" >Select Target Language</option>
              @foreach($loc_languages as $key => $lang)
                        <option value="{{ $lang->lang_code }}" >{{ $lang->lang_name }}</option>
               @endforeach                    
              </select>
            </div>
            <div class="form-group col-md-4">
                <input type="Submit" class="btn btn-success" name="" value="Submit">

             

            </div>
                
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>S.N0</th>
                        <th>
                            {{ trans('cruds.locrequest.fields.source_language') }}
                        </th>
                        <th>
                            {{ trans('cruds.locrequest.fields.source_text') }}
                        </th>
                        <th>
                            {{ trans('cruds.locrequest.fields.target_language') }}
                        </th>
                        <th >
                            {{ trans('cruds.locrequest.fields.target_text') }}
                        </th>
                        <th>
                            {{ trans('cruds.locrequest.fields.action') }}
                        </th>
                    </tr>
                </thead>
                <tbody id="content" align="center" onscroll="doscroll()">
                </tbody></pre>
     <div id="loading">
      Loading Please Wait......
     </div>
            </table>
        </div>
    </div>
</div>
<?php

$s_to_json=json_encode((array)$translation_memory_data);
?>
@endsection
@section('scripts')
@parent
<style type="text/css">

table tbody {
  display: block;
  max-height: 450px;
  overflow-y: scroll;
}

table thead, table tbody tr {
  display: table;
  width: 100%;
  table-layout: fixed;
}
    #loading{
      display:none;
      width: 100%;
      padding:5px 10px;
      position: fixed;
      bottom: 0;
      left: 0;
      text-align: center;
      color:white;
      background: rgba(0,0,23,0.71);
      box-shadow: 0 0 10px black; 
    }
</style>
<script>
    var s_to_json = <?php echo $s_to_json;?>;
    var row_count=0;
      $(document).ready(function(){
        setTimeout("appendContent()", 10);
      });
      var appendContent=function(){
        var j=1;
        for(var i=row_count;i<s_to_json.length;i++){
          var translation_memory=s_to_json[i];
        if(j<=20){
          $('#content').append("<tr id='data_tr_"+translation_memory.sid+"'><td>"+(row_count+1)+"</td><td>"+translation_memory.source_lang+"</td><td>"+translation_memory.source_text+"</td><td>"+translation_memory.target_lang+"</td><td style='min-width:200px'><input class='form-control' id='target_test_"+translation_memory.sid+"' value="+translation_memory.target_text+" ></td><td><a href='#' class='update_data' data-id="+translation_memory.sid+"><i class='fa fa-edit bg-success'></i></a>&nbsp;&nbsp;<a href='#' class='delete' data-id="+translation_memory.sid+"><i class='fa fa-trash bg-danger'></i></a></td></tr>");
        row_count++;j++;}}
        $('#loading').fadeOut();
      };
    $(function () {
      $('tbody').bind('scroll', function()
      {
        if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight)
        {
          $('#loading').fadeIn();
          setTimeout("appendContent()", 1000);
        }
      });

})


$('body').on('click', '.delete', function() {
            if (confirm("Are You Sure Want Delete?") == true) {
                var id = $(this).data('id');
                $.ajax({
                    type: "POST",
                    url: "<?php echo route('admin.translationmemory.distroy_data');?>",
                    headers: {'x-csrf-token': _token},
                    data: {
                        'id': id,
                        'source_language':'<?php echo $source_lang;?>',
                        'target_language':'<?php echo $target_lang;?>'
                    },
                    success: function(res) {
                       if(res == 1){
                        $('#data_tr_'+id).remove();
                       }
                        //$(this).closest('tr').remove();
                        
                        // $('#name').html(res.name);
                        // $('#age').html(res.age);
                        // $('#email').html(res.email);
                        // window.location.reload();
                    }
                });
            }
        });

        

$('body').on('click', '.update_data', function() {
            if (confirm("Are You Sure Want Update?") == true) {
                var id = $(this).data('id');
                var traget=$('#target_test_'+id).val();
                //alert(traget);
                $.ajax({
                    type: "POST",
                    url: "<?php echo route('admin.translationmemory.update_data');?>",
                    headers: {'x-csrf-token': _token},
                    data: {
                        'id': id,
                        'source_language':'<?php echo $source_lang;?>',
                        'target_language':'<?php echo $target_lang;?>',
                        'target_text':traget
                    },
                    success: function(res) {
                      if(res == 1){
                       alert('Updated Successfully');
                      }else{
                        alert('Not Updated');
                      }
                    }
                });
            }
        });

</script>
@endsection
