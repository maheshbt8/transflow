@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.ocrpdf.title_singular') }} <!-- {{ trans('cruds.ocrpdf.fields.doc') }} -->
    </div>
    <div class="card-body">
        <div class="col-md-8">
        <form action="{{ route("admin.ocrpdf.upload") }}" method="POST" enctype="multipart/form-data">
            @csrf
        	<div class="row">
        		<div class="col-md-12">
                    <div class="form-group files">
                        <label for="special_instruction" class="">Upload Your File</label>
                        <input type="file" name="translation_file" id="ocr_file" class="form-control" >
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                    <span class="error">Note: Supports only .pdf files</span>
                </div>
                <div class="col-md-12">
                    <input type="submit" id="upload_ocr" class="btn btn-success" value="Submit"/>
                </div>
        	</div>
        </form>
        </div>
        <br/><br/><br/>
        <div id="download_btn" style="display: none;">
            <a href="#" download="" id="download_path" class="btn btn-sm btn-success center">Download-Excel</a>
        </div>
    </div>
</div>
@endsection


@section('scripts')
@parent
<script type="text/javascript">
        $("#upload_ocr").click(function(){       
        var id_ocrfile_file = $("#ocr_file").val();
        if(id_ocrfile_file ==""){
            $('#ajax_message').html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please upload the file</div>');
            $("#ocr_file").focus();
            return false;           
        }else if(id_ocr_file !=""){         
            
            var ext = id_ocr_file.split('.').pop().toLowerCase();           
            if($.inArray(ext, ['xlsx','xls']) == -1) {
                 $("#ocr_file").attr('value',''); 
                $('#ajax_message').html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong>Uploaded file format not supported.</div>');
                return false;
            }else{      
                var form_data = new FormData();
                form_data.append("ocr_file", document.getElementById('ocr_file').files[0]);
                var file_data=document.getElementById('ocr_file').files[0];
                $.ajax({
                    url:"{{ route("admin.translation.curl_translate_document") }}",
                    method:"POST",
                    data:form_data,
                    dataType:'json',
                    processData: false,
                    contentType: false,
                    headers: {'x-csrf-token': _token},
                    success:function(response){
                        //alert(response.result);
                        var path="{{ asset('storage/dell/') }}"+'/'+response.result;
                        //alert(path);
                        if(response.status == '1'){
                            $('#download_btn').show();
                            $('#download_path').attr('href',path);
                        }
                    }
                });
            
           }
            
        }
        
    });
</script>
@endsection



