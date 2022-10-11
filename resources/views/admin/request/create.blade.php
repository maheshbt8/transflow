@extends('layouts.admin')
@section('content')
<style>
.select2 {
    width:100%!important;
}
</style>
<div class="card">
<?php $user_role = Auth::user()->roles[0]->name; ?>
    <div class="card-header">
        {{ trans('cruds.locrequest.title_singular') }} {{ trans('global.create') }}
    </div>

    <div class="card-body">
        <div class="">
            <form action="{{ route("admin.request.store") }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
                @csrf
                <?php
                $arr_languages = array("en" => "English", "fr" => "French", "de" => "German", "es" => "Spanish", "ko" => "Korea", "te" => "Telugu", "hi" => "Hindi", "th" => "Thai", "ta" => "Tamil", "bn" => "Bengali", "gu" => "Gujarati", "kn" => "Kannada", "ml" => "Malayalam", "mr" => "Marathi", "pa" => "Punjabi", "ur" => "Urdu", "as" => "Assamese", "or" => "Oriya", "si" => "Sinhala", "ar" => "Arabic", "en_au" => "Austria English", "cs" => "Czech", "da" => "Danish", "nl" => "Dutch", "pt" => "Portuguese", "fi" => "Finnish", "hu" => "Hungarian", "es_mx" => "Mexican Spanish", "no" => "Norwegian", "pl" => "Polish", "sk_sk" => "Slovak", "uk" => "Ukrainian");
                asort($arr_languages);


                ?>
                <div class="row">
                    <div class="col-md-6">
                        <!-- <div class="form-group {{ $errors->has('project_name') ? 'has-error' : '' }}">
                            <label for="project_name" class="required">File Name</label>
                            <input type="text" name="project_name" id="project_name" class="form-control" required>
                            @if($errors->has('project_name'))
                            <em class="invalid-feedback">
                                {{ $errors->first('project_name') }}
                            </em>
                            @endif
                        </div> -->
                        <div class="form-group {{ $errors->has('brief_description') ? 'has-error' : '' }}">
                            <label for="project_name" class="required">Brief Description</label>
                            <textarea rows="4" cols="50" name="brief_description" id="brief_description" id="brief_description" class="form-control" required></textarea>
                            @if($errors->has('brief_description'))
                            <em class="invalid-feedback">
                                {{ $errors->first('brief_description') }}
                            </em>
                            @endif
                        </div>
                        <?php 
                        // dd($user_role);
                        
                        if($user_role != 'clientuser' && $user_role != 'requester' && $user_role != 'approval' && $user_role != 'reviewer'){ ?>
                        <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                       <label class="required">Client Organization</label>
                       <select name="client_org" id="client_org" class="form-control select2" required>
                       <option value="">Select Client Organization</option>
                       @foreach($clientorganizations as $id => $org)
                           <option value="{{$org->org_id}}">{{$org->org_name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" for="">Please Select Client Organization</div>
                    </div>
                    <?php }?>
                        <!-- <div class="form-group {{ $errors->has('document_type') ? 'has-error' : '' }}">
                            <label for="document_type" class="required">Document Type</label>
                            <select name="document_type" id="document_type" class="form-control select2" required>
                                <option value="" selected>Select Category</option>
                                <option value="agreement">Agreement</option>
                                <option value="attachment">Attachment</option>
                                <option value="contract">Contract</option>
                                <option value="email">Email</option>
                                <option value="letter">Letter</option>
                                <option value="notice">Notice</option>
                                <option value="others">Others</option>
                            </select>
                            @if($errors->has('document_type'))
                            <em class="invalid-feedback">
                                {{ $errors->first('document_type') }}
                            </em>
                            @endif
                            <div class="invalid-feedback" for="">Must Select your Document Type</div>
                        </div> -->
                    </div>
                </div>
                
                <div class="">
    <div class="row" id="add_rows_request">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Section 1
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool btn-sm bg-success" id="id_request_div">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Source Language block -->
                        <div class="col-md-6 form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
                            <label for="source_language" class="required">Source Language</label>
                            <select name="source_language[]" id="source_language" class="form-control select2" required>
                                <option value="">Select Source Language</option>
                                @foreach($loc_languages as $key => $lang)
                                <option value="{{ $lang->lang_id }}">{{ $lang->lang_name }}</option>
                                @endforeach
                            </select>

                            @if($errors->has('source_language'))
                            <em class="invalid-feedback">
                                {{ $errors->first('source_language') }}
                            </em>
                            @endif
                            <div class="invalid-feedback" for="">Must Select your Source Language</div>
                        </div>


                        <div class="form-group col-md-6 {{ $errors->has('destination_language') ? 'has-error' : '' }}">
                            <label for="destination_language" class="required">Target Language</label>
                            <select name="destination_language_0[]" id="destination_language_0" class="form-control select2" required>
                                <option value="">Select Target Language</option>
                                @foreach($loc_languages as $key => $lang)
                                <option value="{{ $lang->lang_id }}">{{ $lang->lang_name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('destination_language'))
                            <em class="invalid-feedback">
                                {{ $errors->first('destination_language') }}
                            </em>
                            @endif
                            <div class="invalid-feedback" for="">Must Select your Target Language</div>
                        </div>
                        <input type="hidden" name="source_type_0" id="source_type_0" value="File" data-id="0" class="form-control source_type" required />
                        <div class="col-md-12">
                            <div class="row" id="div_source_file_0">
                                <table class=" {{ $errors->has('source_file') ? 'has-error' : '' }} input_fields_wrap2_0">
                                        <tr>
                                            <th>Source File</th>
                                            <th></th>
                                        </tr>
                                        <tr >
                                            <td><input type="file" class="form-control source_file_0" name="source_file_0[]"  id="source_file_0" required></td>
                                            <td><button type="button" class="btn btn-success btn-xs" id="id_more_upload_0" onclick="add_more_upload(0)" value="" name="submit"> <i class="fas fa-plus"></i> </button></td>
                                        </tr>
                                    </table>
                                <!-- <div class="form-group col-md-10 {{ $errors->has('source_file') ? 'has-error' : '' }} input_fields_wrap2_0">
                                    <label class="required">Source File </label>
                                    <input type="file" class="form-control" name="source_file_0[]" id="source_file_0">
                                </div>
                                <div style="margin-top:29px;" class="form-group col-md-2">
                                    <button type="button" class="btn btn-sm btn-success float-right" id="id_more_upload" onclick="add_more_upload(0)" value="More Files" name="submit"><i class="fas fa-plus"></i></button><br>
                                </div>
                                <div class="col-md-2"></div> -->
                                <!-- <div style="margin-top:29px;" class="form-group col-md-2">
                                    <input type="button" class="btn btn-success float-right" id="id_request_div" value="Add More" name="submit">
                                </div> -->
                            </div>

                            <div class="row" id="div_source_text_0" style="display:none">
                                <div class="form-group col-md-6 {{ $errors->has('source_text') ? 'has-error' : '' }} input_fields_wrap_text_0">
                                    <label for="source_text" class="required">Source Text</label>
                                    <textarea rows="4" cols="50" name="source_text_0[]" id="source_text_0" id="special_instruction" class="form-control"></textarea>
                                </div>
                                <div style="margin-top:29px;" class="form-group col-md-2">
                                    <input type="button" class="btn btn-success float-right" onclick="add_more_text_upload(0)" id="id_more_text_upload_0" value="More Text" name="submit">
                                </div>
                                <div class="col-md-2"></div>
                                <!-- <div style="margin-top:29px;" class="form-group col-md-2">
                                    <input type="button" class="btn btn-success float-right" id="id_request_div" value="Add More" name="submit">
                                </div> -->
                            </div>
                        </div>


                        <!-- <div id="add_rows_request"></div> -->
                    </div>
                </div>
            </div>
        </div>
        
    </div>        
                <div class="form-group col-md-6 {{ $errors->has('special_instruction') ? 'has-error' : '' }}">
                    <label for="special_instruction">Specific instructions</label>
                    <textarea rows="4" cols="50" name="special_instruction" id="special_instruction" id="special_instruction" class="form-control"></textarea>
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
function checksourcetype(type_id){
        var i = $('#source_type_'+type_id).val();
        
        if (i == "Text") // equal to a selection option
        {
            $('#div_source_text_'+type_id).show();
            $('#source_text_'+type_id).prop("required", true);
            $('#div_source_file_'+type_id).hide();
            $('#source_file_'+type_id).prop("required", false);
            //$('#div_source_website').hide();
        } else
        if (i == "File") {
            $('#div_source_text_'+type_id).hide();

            $('#source_text_'+type_id).prop("required", false);
            //$('#div_source_website').hide();
            $('#div_source_file_'+type_id).show();

            $('#source_file_'+type_id).prop("required", true);
        }

    }


    // $(document).ready(function() {
        var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap2"); //Fields wrapper
        var add_button = $("#id_more_upload"); //Add button ID

        var x = 1; //initlal text box count
      
    
    function remove_more_upload(id){
        
        $("#source_file_div_"+id).remove();
        x--;
    }
    function add_more_upload(id){
        //var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap2_"+id); //Fields wrapper
        var add_button = $("#id_more_upload"); //Add button ID

        //var x = 1; //initlal text box count
        //if (x < max_fields) { //max input box allowed

//text box increment
$(wrapper).append('<tr id="source_file_div_' + x + '" ><td><input type="file" class="form-control source_file_'+ id +'" name="source_file_' + id + '[]"  id="source_file_'+ id +'" required></td><td><button type="button" class="btn btn-danger btn-xs float-right" id="remove_field" onclick="remove_more_upload(' + x + ')" name="submit"><i class="fas fa-trash"></i></button></td></tr>');
// $(wrapper).append('<div id="source_file_div_'+x+'" ><div class="row"><div class="col-md-10"><input type="file" class="form-control" name="source_file_'+id+'[]" id="source_file"></div><div class="col-md-2"><button type="button" class="btn btn-sm btn-danger float-right" id="remove_field" onclick="remove_more_upload('+x+')" value="Remove" name="submit"><i class="fas fa-trash"></i></button></div></div></div>');
x++;
//}
    }
// });
    // $(document).ready(function() {
        var max_text_fields = 10; //maximum input boxes allowed
        var wrapper_text = $(".input_fields_wrap_text"); //Fields wrapper
        var add_button = $("#id_more_text_upload");

         
        var y = 1;
        function remove_text_upload(id){
      
        $("#source_text_div_"+id).remove();
        y--;
    }
    function add_more_text_upload(id){//alert(id);
        var max_text_fields = 10; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap_text_"+id); //Fields wrapper
        var add_button = $("#id_more_text_upload"); //Add button ID

        //var x = 1; //initlal text box count
        if (y < max_text_fields) { //max input box allowed

//text box increment
$(wrapper).append('<div id="source_text_div_'+y+'" class="form-group"><label for="source_text" class="required">Source Text</label><textarea rows="4" cols="50" name="source_text_'+id+'[]" id="source_text" id="special_instruction" class="form-control"></textarea><br/><input type="button" class="btn btn-danger float-right" id="remove_field" onclick="remove_text_upload('+y+')" value="Remove" name="submit"> </div>');
y++;
}
    }
    // });
    var addrow_page = 0;
    $(document.body).on('click', "#id_request_div", function(e) {
        addrow_page++;
        $.ajax({
            type: "post",
            dataType: "html",
            url: "{{route('admin.request.request_add_fields')}}",
            data: {
                "addrow": addrow_page,
                "_token": "{{csrf_token()}}"
            },
            beforeSend: function(xhr) {},
            success: function(data) {
                $("#add_rows_request").append(data);
                selectRefresh();
            },
            error: function() {
                alert("Error try again or contact administrator");
                return false;
            }
        });
    });
    
    function validate_field_page(id) {
        var dyanmic_row_id = id;
        var div = document.getElementById("dyanmic_fields_" + dyanmic_row_id);
        div.parentNode.removeChild(div);
    }
    function selectRefresh() {
       $('.select2').select2({
           tags: true,
           /*placeholder: "Select an Option"*/
       });
   }
</script>
@endsection