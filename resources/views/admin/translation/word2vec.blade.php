@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.translation.title_singular') }} 
        {{ trans('cruds.translation.fields.word') }}
    </div>
    <div class="card-body">
        <div class="col-md-8">
        <form action="https://proof-reader.xploree.in/uploader" method="POST" enctype="multipart/form-data">
            @csrf
        	<div class="row">
        		<div class="col-md-12">
                    <div class="form-group files">
                        <label for="special_instruction" class="">Upload Your File</label>
                        <input type="file" name="file" id="translation_file" class="form-control" accept=".xls,.xlsx" >
                    </div>
                    <span class="error">Note: Supports only .xlsx,.xls</span>
                </div>
                <div class="col-md-12">
                    <input type="submit" class="btn btn-success" value="Submit" id="word2vec_upload_excel" />
                </div>
        	</div>
        </form>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
<script type="text/javascript">
  $("#word2vec_upload_excel").click(function(){         
        
        var id_translation_file = $("#translation_file").val();
    
                
        if(id_translation_file ==""){
            $('#ajax_message').html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong>Please upload the file.</div>');
            $("#translation_file").focus();
            return false;           
        }else if(id_translation_file !=""){ 
            
            var ext = id_translation_file.split('.').pop().toLowerCase();           
            if($.inArray(ext, ['xlsx','xls']) == -1) {
                 $("#translation_file").attr('value',''); 
                $('#ajax_message').html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong>Uploaded file format not supported.</div>');
                return false;
            }       
        }        
    });
</script>
@endsection