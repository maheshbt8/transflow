@extends('layouts.admin')
@section('content')
<style>
     .select2 {
width:100%!important;
}
</style>
<div class="card">
    <div class="card-header">
        {{ trans('cruds.locrequest.title_singular') }} {{ trans('global.edit') }}
    </div>
    <div class="card-body"> 
        <div class="d-flex justify-content">
        <form action="{{ url('admin/request/update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="reference_id" value="{{ $edit_request->reference_id }}">
       
        <div class="form-group {{ $errors->has('project_name') ? 'has-error' : '' }}">
            <label for="project_name" class="required">File Name</label>
             <input type="text" name="project_name" id="project_name" class="form-control" value="{{ $edit_request->project_name }}" required >
                 @if($errors->has('project_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('project_name') }}
                    </em>
                @endif               
        </div>
        <div class="form-group {{ $errors->has('brief_description') ? 'has-error' : '' }}">
            <label for="project_name">Brief Description</label>
             <textarea rows="4" cols="50" name="brief_description" id="brief_description" id="brief_description" class="form-control" >{{ $edit_request->brief_description }}</textarea>
                 @if($errors->has('brief_description'))
                    <em class="invalid-feedback">
                        {{ $errors->first('brief_description') }}
                    </em>
                @endif               
        </div>
        <div class="form-group {{ $errors->has('category') ? 'has-error' : '' }}">
            <label for="category" class="required">Document Type</label>
            <select name="category" id="category" class="form-control select2" required="">
                <option value=''>Select Category</option>
                <option value='Agreement' {{ $edit_request->category == 'Agreement'? 'selected' : '' }}>Agreement</option>
                <option value='attachment' {{ $edit_request->category == 'attachment'? 'selected' : '' }}>Attachment</option>
                <option value='contract' {{ $edit_request->category == 'contract'? 'selected' : '' }}>Contract</option>
                <option value='email' {{ $edit_request->category == 'email'? 'selected' : '' }}>Email</option>
                <option value='letter' {{ $edit_request->category == 'letter'? 'selected' : '' }}>Letter</option>        
                <option value='notice' {{ $edit_request->category == 'notice'? 'selected' : '' }}>Notice</option>
                <option value='others' {{ $edit_request->category == 'others'? 'selected' : '' }}>Others</option>
            </select>
                 @if($errors->has('category'))
                    <em class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </em>
                @endif               
        </div>

            <!-- Source Language block -->
         <div class="form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
            <label for="source_language" class="required">Source Language</label>
             <select name="source_language" id="source_language" class="form-control select2" required >
             <option value="" >Select Source Language</option>
              @foreach($loc_languages as $key => $lang)
                        <option value="{{ $lang->lang_id }}" {{ $edit_request_lang->source_lang == $lang->lang_id? 'selected' : '' }}>{{ $lang->lang_name }}</option>
               @endforeach                    
              </select>
                
                 @if($errors->has('source_language'))
                    <em class="invalid-feedback">
                        {{ $errors->first('source_language') }}
                    </em>
                @endif               
             </div>
            <!-- Organization block -->
            
            
            
            <!-- Sub Organization block -->
            <div class="form-group {{ $errors->has('destination_language') ? 'has-error' : '' }}">
                <label for="destination_language" class="required">Target Language</label>
                 <select name="destination_language" id="destination_language" class="form-control select2" required >
                 <option value="" >Select Target Language</option>
                @foreach($loc_languages as $key => $lang)
                    @if($edit_request_lang->source_lang != $lang->lang_id)
                        <option value="{{ $lang->lang_id }}" <?php echo (($edit_request_lang->destination_language == $lang->lang_id) ? 'selected' : '') ?>><?php echo $lang->lang_name;?></option>
                    @endif
                @endforeach   
                </select>                   
                     @if($errors->has('destination_language'))
                        <em class="invalid-feedback">
                            {{ $errors->first('destination_language') }}
                        </em>
                    @endif             
            </div>
          <!-- Sub Organization block -->


        <div class="form-group {{ $errors->has('source_type') ? 'has-error' : '' }}">
            <label for="source_type" class="required">Source Type</label>
            <select name="source_type" id="source_type" class="form-control select2">
                <option value='File' {{ $edit_request->source_type == 'File'? 'selected' : '' }}>File</option>
                <option value='Text' {{ $edit_request->source_type == 'Text'? 'selected' : '' }}>Text</option>
            </select>
                 @if($errors->has('source_type'))
                    <em class="invalid-feedback">
                        {{ $errors->first('source_type') }}
                    </em>
                @endif               
        </div>

            <?php
            $more_upload_display = "More Files";
            $multiple_display = "display:none !important;";
            $mutiple_files_status=0;
			if(($mutiple_files_result->source_file_name1 !="") || ($mutiple_files_result->source_file_name2 !="") || ($mutiple_files_result->source_file_name3 !="") || ($mutiple_files_result->source_file_name4 !="")){
					$more_upload_display = "Dismiss";
					$multiple_display = "display:block !important;";
					$mutiple_files_status=1;
				}
				?>
            <div id="div_source_file" class="form-group {{ $errors->has('source_file') ? 'has-error' : '' }}">               
                <label>Source File </label>
                <input type="file" class="form-control" name="source_file" id="source_file" >
                <!-- <span style="color:red;">Note: Supports only .xlsx,.xls</span> -->
                <br/>
                @if($edit_request->source_file_path != '')
                <a href="{{ asset('storage/request/'.$edit_request->source_file_path) }}" download=""> {{ $edit_request->source_file_path }}</a>
				<input type="hidden" name="source_file_path_old" value="{{ $edit_request->source_file_path }}">
                @endif
                <br/>
                <input type="button" class="btn btn-success float-right" id="id_more_upload" value="{{ $more_upload_display }}" name="submit">
            </div>
            <div id="div_more_upload_first_block" style="{{ $multiple_display }}">
                <div id="div_more_upload_first_block1" class="form-group {{ $errors->has('source_type1') ? 'has-error' : '' }}">               
                    <label>Source File 1 </label>
                    <input type="file" class="form-control" name="source_file1" id="source_file1" >
                <br/>
                @if( $mutiple_files_result->source_file_name1 != '')
                <a href="{{ asset('storage/request/sub_source_files/'.$mutiple_files_result->source_file_name1) }}" download=""> {{ $mutiple_files_result->source_file_name1 }}</a>
				<input type="hidden" name="source_file_path_old1" value="{{ $mutiple_files_result->source_file_name1 }}">
                @endif
                </div>
                <div id="div_more_upload_first_block2" class="form-group {{ $errors->has('source_type2') ? 'has-error' : '' }}">               
                    <label>Source File 2 </label>
                    <input type="file" class="form-control" name="source_file2" id="source_file2" >
                <br/>
                @if( $mutiple_files_result->source_file_name2 != '')
                <a href="{{ asset('storage/request/sub_source_files/'.$mutiple_files_result->source_file_name2) }}" download=""> {{ $mutiple_files_result->source_file_name2 }}</a>
				<input type="hidden" name="source_file_path_old2" value="{{ $mutiple_files_result->source_file_name2 }}">
                @endif
                </div>
                <div id="div_more_upload_first_block3" class="form-group {{ $errors->has('source_type3') ? 'has-error' : '' }}">               
                    <label>Source File 3 </label>
                    <input type="file" class="form-control" name="source_file3" id="source_file3" >
                <br/>
                @if( $mutiple_files_result->source_file_name3 != '')
                <a href="{{ asset('storage/request/sub_source_files/'.$mutiple_files_result->source_file_name3) }}" download=""> {{ $mutiple_files_result->source_file_name3 }}</a>
				<input type="hidden" name="source_file_path_old3" value="{{ $mutiple_files_result->source_file_name3 }}">
                @endif
                </div>
                <div id="div_more_upload_first_block4" class="form-group {{ $errors->has('source_type4') ? 'has-error' : '' }}">               
                    <label>Source File 4 </label>
                    <input type="file" class="form-control" name="source_file4" id="source_file4" >
                <br/>
                @if( $mutiple_files_result->source_file_name4 != '')
                <a href="{{ asset('storage/request/sub_source_files/'.$mutiple_files_result->source_file_name4) }}" download=""> {{ $mutiple_files_result->source_file_name4 }}</a>
				<input type="hidden" name="source_file_path_old4" value="{{ $mutiple_files_result->source_file_name4 }}">
                @endif
                </div>
            </div>


        <div id="div_source_text" class="form-group {{ $errors->has('source_text') ? 'has-error' : '' }}" style="display:none">
            <label for="source_text" class="required">Source Text</label>
             <textarea rows="4" cols="50" name="source_text" id="source_text" id="source_text" class="form-control" >{{ $edit_request->source_text }}</textarea>
                 @if($errors->has('source_text'))
                    <em class="invalid-feedback">
                        {{ $errors->first('source_text') }}
                    </em>
                @endif               
        </div>
            
        <div class="form-group {{ $errors->has('special_instruction') ? 'has-error' : '' }}">
            <label for="special_instruction" class="">Specific instructions</label>
             <textarea rows="4" cols="50" name="special_instruction" id="special_instruction" id="special_instruction" class="form-control" >{{ $edit_request->special_instructions }}</textarea>
                 @if($errors->has('special_instruction'))
                    <em class="invalid-feedback">
                        {{ $errors->first('special_instruction') }}
                    </em>
                @endif               
        </div>
            
            <div>&nbsp;</div>                   
            <div>
                <input class="btn btn-danger" id="translation_submit" type="submit" value="{{ trans('global.submit') }}">
            </div>
        </form>
    </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
    /*Onload Start*/
	var i = $('#source_type').val();
	if(i == "Text"){
		$('#div_source_text').show();
        $('#source_text').prop("required", true);
        $('#div_source_file').hide();
        $('#source_file').prop("required", false);
        $('#div_more_upload_first_block').hide();
	}else{
		$('#div_source_text').hide();
        $('#source_text').prop("required", false);
        $('#div_source_file').show();
        $('#source_file').prop("required", true);
        <?php 
        if($mutiple_files_status == 1){
        ?>
        $('#div_more_upload_first_block').show();
        <?php
        }
        ?>
        
	}    
    /*Onload End*/
})
$('#source_type').on('change', function (event) {

        var i = $('#source_type').val();
        if (i == "Text") // equal to a selection option
        {
            $('#div_source_text').show();
            $('#source_text').prop("required", true);
            $('#div_source_file').hide();
            $('#source_file').prop("required", false);
            $('#div_more_upload_first_block').hide();
            $('#div_source_website').hide();
        }
        else
        if(i == "File")
        {
            $('#div_source_text').hide();
            $('#source_text').prop("required", false);
            $('#div_source_website').hide();
            $('#div_source_file').show();
            $('#source_file').prop("required", true);
            $('#div_more_upload_first_block').show();
        }
        
    });
        
  $("#id_more_upload").click(function(){
        var more_upload_label = $("#id_more_upload").val();
        if(more_upload_label == "More Files"){      
            $("#div_more_upload_first_block").show();
            $("#id_more_upload").val("Dismiss");
        }else if(more_upload_label =="Dismiss" ){
            $("#div_more_upload_first_block").hide();
            $("#id_more_upload").val("More Files");
        }
  });
  $("#source_language").change(function(){
    var source_language = $("#source_language").val();
    var destination_language = $("#destination_language").val();
    if(source_language == destination_language){
    var res='<option value="" >Select Target Language</option>';
      <?php
      foreach($loc_languages as $key => $lang){
        ?>
        var t_l='<?php echo $lang->lang_id;?>';
        if(source_language != t_l){
        res +='<option value="<?php echo $lang->lang_id;?>" > <?php echo $lang->lang_name;?></option>';
      }
      <?php }?>
      $('#destination_language').html(res);
    }
  });
</script>
@endsection
