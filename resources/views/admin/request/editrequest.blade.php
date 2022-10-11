@extends('layouts.admin')
@section('content')
<div class="card">
    <?php $user_role = Auth::user()->roles[0]->name; ?>
    <div class="card-header">
        {{ trans('cruds.locrequest.title_singular') }} {{ trans('global.edit') }}
    </div>
    <?php 
    $show_add_more = 0;
    if ($user_role == 'clientuser' || $user_role == "requester" || $user_role == "approval" || $user_role == "reviewer") {
        $show_add_more = 1;
    } ?>
    <div class="card-body">
        <?php //print_r($get_quote_data->translation_quote_id);die; 
        ?>
        <div class="justify-content">
            <form action="{{ route("admin.request.update", $get_quote_data->translation_quote_id ?? $getreq->req_id)}}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('brief_description') ? 'has-error' : '' }}">
                            <label for="project_name" class="required">Brief Description</label>
                            <textarea rows="4" cols="50" name="brief_description" id="brief_description" id="brief_description" class="form-control" required>{{$getreq->brief_description}}</textarea>
                            @if($errors->has('brief_description'))
                            <em class="invalid-feedback">
                                {{ $errors->first('brief_description') }}
                            </em>
                            @endif
                            <input type="hidden" name="quote_id" value="{{$get_quote_data->translation_quote_id ?? ''}}">
                        </div>
                        
                        <?php
                        if ($user_role != 'clientuser' && $user_role != 'requester' && $user_role != 'approval' && $user_role != 'reviewer' && $user_role != 'projectmanager') { ?>
                            <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                                <label class="required">Client Organization</label>
                                <select name="client_org" id="client_org" class="form-control select2" required style="max-width:100%; text-overflow: ellipsis">
                                    <option value="">Select Client Organization</option>
                                    @foreach($clientorganizations as $id => $org)
                                    <option value="{{ $org->org_id}}" {{ (isset($getreq->client_org_id) && $getreq->client_org_id==$org->org_id) ? 'selected' : '' }}>{{$org->org_name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" for="">Please Select Client Organization</div>
                              
                            </div>
                        <?php } ?>

                    </div>
                   
                    <div class="form-group col-md-4">
                        <label for="project_name" class="required">Choose Translator</label>
                        <select class="form-control select2" id="vendors_list" onchange="change_vendor(this.value)">
                            <option value="">Select User</option>
                            <?php for ($i1 = 0; $i1 < count($vendors); $i1++) { ?>
                                <option value="{{$vendors[$i1]->id}}" {{((isset($_GET["translator"]) && $_GET["translator"] == $vendors[$i1]->id)? "selected" : "")}}>{{$vendors[$i1]->name}}</option>
                            <?php } ?>

                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="project_name" class="required">Choose files</label>
                        <input type="file" class="form-control " name="multiple_files[]"  id="fileToUpload" multiple="">
                        <input type="hidden" value="0" name="multiplefiles" id="multiplefiles">
                        @if($errors->has('brief_description'))
                        <em class="invalid-feedback">
                            {{ $errors->first('brief_description') }}
                        </em>
                        @endif
                        <br/>
                        <ul id="myfiles_list">
                        </ul>
                    </div>
                </div>
                <div class="row" id="add_rows_request">
                        <?php $i = 0;
                        $t = $f = 1;
                        ?>
                        <?php
                        if ($get_quote_data) {
                            $get_source_lang = $loc_master->quote_lang_select($get_quote_data->translation_quote_id);
                        } else {
                            $get_source_lang = $loc_master->request_lang_select($getreq->req_id);
                        }
                        // echo "<pre>";
                        //  print_r($get_source_lang);die;
                        ?>
                        @foreach($get_source_lang as $get_source)
                        <?php
                        if ($get_quote_data) {
                            $target_lang = DB::table('loc_request_assigned')->where(['quote_id' => $get_quote_data->translation_quote_id, 'loc_source_id' => $get_source->id])->get('target_language')->toArray();
                        } else {
                            $target_lang = DB::table('loc_request_assigned')->where(['request_id' => $getreq->req_id, 'loc_source_id' => $get_source->id])->get('target_language')->toArray();
                        }
                    //echo "<pre/>";   print_r($get_quote_data);die;
                        $get_target_lang = array_column((array)$target_lang, 'target_language');
                        ?>
                        <div class="col-md-12" id="<?php if ($i != 0) {
                                                        echo 'dyanmic_fields_' . $i;
                                                    } ?>">
                            <div class="card">
                                
                              
                        </div>    
                                <div class="card-body">
                       <divclass="row">
                       <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Files</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $coun = 1;
                                $total = 0;
                                ?>
                                @foreach($get_source_lang as $get_source)
                                <?php
                                $target_lang = DB::table('loc_request_assigned')->where(['quote_id' => $get_quote_data->translation_quote_id, 'loc_source_id' => $get_source->id])->first();
                                $get_target_lang = array_column((array)$target_lang, 'target_language');
                                $get_service_type = $get_loc_services_type = gettabledata('loc_service', 'type', ['id' => $target_lang->service_type]);
                                $get_loc_services_name = gettabledata('loc_service', 'service_type', ['id' => $target_lang->service_type]);
                              
                                $my_files = $loc_multiple_file->where(['request_id' => $getreq->req_id, 'source_language' => $get_source->id, 'source_type' => 'file']);
                                $my_files_count = $my_files->count();
                               
                                $get_service_type_label = '';
                                if ($get_service_type == 'minute' || $get_service_type == 'slab_minute') {
                                    $get_service_type_label = 'Minutes';
                                    $per_unit_i = $target_lang->per_minute_cost;
                                    $total_count = $target_lang->minute_count;
                                } elseif ($get_service_type == 'word') {
                                    $get_service_type_label = 'Words';
                                    $per_unit_i = $target_lang->per_word_cost;
                                    $total_count = $target_lang->word_count;
                                } elseif ($get_service_type == 'page') {
                                    $get_service_type_label = 'Pages';
                                    $per_unit_i = $target_lang->per_page_cost;
                                    $total_count = $target_lang->page_count;
                                } elseif ($get_service_type == 'resource') {
                                    $get_service_type_label = 'Resources';
                                    $per_unit_i = $target_lang->cost_per_resource;
                                    $total_count = $target_lang->resource_count;
                                } else {
                                    $per_unit_i = 0;
                                }
                                ?>
                                <tr>
                                    <td>{{$i+1}}</td>
                                    <td colspan="3"><b>LANGUAGE PAIR :</b><span> </span><input type="hidden" name="source_language[]" value="{{ $get_source->id }}">
                                        <input type="hidden" name="destination_language_{{$i}}[]" value="{{$target_lang->target_language}}">
                                        <input type="hidden" name="source_type_{{$i}}" id="source_type_{{$i}}" value="File" data-id="{{$i}}" class="form-control source_type" required />
                                        {{ $get_source->lang_name }} - {{getlangbyid($target_lang->target_language)}}
                                        <br />
                                        <b>Service Type:</b> {{$get_loc_services_name}}
                                        <span class="float-right">
                                            <b>{{$total_count}} - {{$get_service_type_label}}</b><br />
                                            Per Unit: <b>{{checkcurrency($per_unit_i,$get_quote_data->translation_quote_currency)}}</b>
                                        </span>
                                </tr>
                                  <tr id="addmorevendor_{{$i}}">
                                    <td>{{$i+1}}.1</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input type="file" class="form-control source_files_list source_file_{{$i}}" name="source_file_{{$i}}[]" id="source_file_{{$i}}_0" onchange="show(this)" required>
                                           
                                           
                                            </div>
                                            <div class="col-md-3">
                                                <input type="hidden" name="source_file_ids[]" value="{{$i}}">
                                                <select class="form-control select2" id="tr_list_select_{{$i}}_0" name="tr_list_select[{{$i}}][]" required>
                                                    <option value="">Select User</option>
                                                    <?php for ($i1 = 0; $i1 < count($vendors); $i1++) { ?>
                                                        <option value="{{$vendors[$i1]->id}}" {{((isset($_GET["translator"]) && $_GET["translator"] == $vendors[$i1]->id)? "selected" : "")}}>{{$vendors[$i1]->name}}</option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <select id="assign_gst_{{$i}}_0" name="assign_gst[{{$i}}][]" onchange="gstchange1(this.value,'{{$i}}',0)" class="form-control select2" required>
                                                    <option value="gst">GST</option>
                                                    <option value="both">SGST & CGST</option>
                                                    <option value="no_gst">No GST</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <select name="currency[{{$i}}][]" id="currency_{{$i}}_0" class="form-control select2" required>
                                                    @foreach($currency_list as $cl)
                                                    <option value="{{$cl->id}}" {{(($get_quote_data->translation_quote_currency == $cl->id)? 'selected' : '')}}>{{$cl->currency_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col">
                                                <input type="number" name="unit_count[{{$i}}][]" id="unit_count_{{$i}}_0" onkeyup="unit_count1(this.value,'{{$i}}',0)" class="form-control" value="{{$total_count}}" placeholder="{{str_replace('_',' ',ucwords($get_loc_services_type))}} Count" min="1" required="" />
                                                <input id="req_service_{{$i}}" value="{{str_replace('_',' ',ucwords($get_loc_services_type))}} Count" type="hidden" />
                                            </div>
                                            <div class="col">
                                                <input type="number" name="per_unit[{{$i}}][]" id="per_unit_{{$i}}_0" onkeyup="per_unit1(this.value,'{{$i}}',0)" placeholder="Per Unit" class="form-control" min="0" step="0.1" required="" value="{{$per_unit_i}}">
                                            </div>
                                            <div class="col">
                                                <input type="number" name="amount_per_vendor[{{$i}}][]" id="amount_per_vendor_{{$i}}_0" min="0.1" class="form-control amount_list" required placeholder="Total" readonly />
                                            </div>
                                        </div>
                                    </td>
                                    <td><button type="button" class="btn btn-success btn-xs" id="id_more_upload" onclick="add_more_upload('{{$i}}')" value="" name="submit"> <i class="fas fa-plus"></i> </button></td>
                                   
                                                            </td>
          
                                </tr>

                              
                                <?php $i++; ?>
                                @endforeach
                            </tbody>
                            <!-- <tfoot>
                          <tr>
                            <th colspan="2">Grand Total</th>
                            <th><b id="grandtotal" class="float-right">0</b></th>
                          </tr>
                        </tfoot> -->
                     </table>














                                        <!-- Source Language block -->
                                        <div class="col-md-12">
                                            <div class="row">
                                                
                                            <input type="hidden" value="{{ $get_source->id }}" name="db_source_lang_id[]" />
                                            <input type="hidden" value="{{ $i }}" name="db_source_lang_index[]" />
                                                <?php if ($show_add_more == 1) {
                                                    $show_add_more = 1; ?>
                                                    <div class="col-md-6 form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
                                                        <label for="source_language">Source Language</label>

                                                        <select name="source_language[]" id="source_language_{{$i}}" data-source_type="{{$i}}" data-source_id="{{$get_source->lang_id}}" class="form-control select2" required>
                                                            <option value="">Select Source Language</option>
                                                            @foreach($loc_languages as $key => $lang)
                                                            <option value="{{ $lang->lang_id }}" <?php echo (($lang->lang_id == $get_source->lang_id) ? 'selected' : '') ?>>{{ $lang->lang_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="invalid-feedback" for="">Must Select your Source Language</div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{ $errors->has('destination_language') ? 'has-error' : '' }}">
                                                        <label for="destination_language">Target Language</label>
                                                        <select name="destination_language_{{$i}}[]" id="destination_language_{{$i}}" class="form-control select2" required >
                                                            <option value="">Select Target Language</option>
                                                            @foreach($loc_languages as $key => $lang)
                                                            <option value="{{ $lang->lang_id }}" <?php echo ((in_array($lang->lang_id, $get_target_lang)) ? 'selected' : ''); ?>>{{ $lang->lang_name }}</option>
                                                            @endforeach
                                                        </select>

                                                        @if($errors->has('destination_language'))
                                                        <em class="invalid-feedback">
                                                            {{ $errors->first('destination_language') }}
                                                        </em>
                                                        @endif
                                                        <div class="invalid-feedback" for="">Must Select your Target Language</div>
                                                    </div>

                                                <?php } else {
                                                    $show_add_more = 0; ?>
                                                    <div class="col-md-6 form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
                                                        <label for="source_language">Source Language</label>
                                                        <input type="hidden" name="source_language[]" value="{{ $get_source->lang_id }}">
                                                        <ul>
                                                            <li>{{ $get_source->lang_name }}</li>
                                                        </ul>
                                                        @if($errors->has('source_language'))
                                                        <em class="invalid-feedback">
                                                            {{ $errors->first('source_language') }}
                                                        </em>
                                                        @endif
                                                        <div class="invalid-feedback" for="">Must Select your Source Language</div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{ $errors->has('destination_language') ? 'has-error' : '' }}">
                                                        <label for="destination_language">Target Language</label>
                                                        @foreach($loc_languages as $key => $lang)
                                                        <?php if (in_array($lang->lang_id, $get_target_lang)) { ?>
                                                            <input type="hidden" name="destination_language_{{$i}}[]" value="{{ $lang->lang_id }}">
                                                            <ul>
                                                                <li><?php ?>{{ $lang->lang_name }}</li>
                                                            </ul>
                                                        <?php }
                                                        ?>
                                                        @endforeach

                                                        @if($errors->has('destination_language'))
                                                        <em class="invalid-feedback">
                                                            {{ $errors->first('destination_language') }}
                                                        </em>
                                                        @endif
                                                        <div class="invalid-feedback" for="">Must Select your Target Language</div>
                                                    </div>
                                                <?php } ?>
                                                <input type="hidden" name="source_type_{{$i}}" id="source_type_{{$i}}" value="File" data-id="{{$i}}" class="form-control source_type" required /> 
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="row" id="div_source_file_{{$i}}">
                                                <?php
                                                $my_files = $loc_multiple_file->where(['request_id' => $getreq->req_id, 'source_language' => $get_source->id, 'source_type' => 'file']);
                                                $my_files_count = $my_files->count();
                                                ?>

                                                <div class=" col-md-12 {{ $errors->has('source_file') ? 'has-error' : '' }} input_fields_wrap2_{{$i}}">
                                                    <div class="form-group " id="my_files_{{$get_source->lang_id}}">
                                                        <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>File Name</th>
                                                                <th><button type="button" class="btn btn-success btn-xs float-right" id="id_more_upload_{{$i}}" onclick="add_more_upload({{$i}})"><i class="fas fa-plus"></i></button></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="my_files_{{$get_source->lang_id}}">
                                                            <?php
                                                            foreach ($my_files->get() as $my_f) {
                                                            ?>

                                                                <tr>
                                                                    <td>{{ $my_f->original_file }}<input type="hidden" name="source_file_old_{{$i}}[]" id="source_file_old_{{$i}}" value="{{ $my_f->id }}" /></td>
                                                                    <td class="text-right py-0 align-middle">
                                                                        <div class="btn-group btn-group-xs">
                                                                            <a href="{{ route('admin.request.deletefile',[$my_f->id]) }}" class="btn btn-danger btn-xs show_confirm"><i class="fas fa-trash"></i></a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <!-- <label>{{ $my_f->original_file }}<input type="hidden"  name="source_file_old_{{$i}}[]" id="source_file_old_{{$i}}" value="{{ $my_f->id }}"/></label> <a href="{{ route('admin.request.deletefile',[$my_f->id]) }}" class="show_confirm"><span class="text-danger"><i class="fa fa-trash float-right " aria-hidden="true"></i></span></a> <br/> -->
                                                            <?php } ?>
                                                        </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="div_source_text_{{$i}}" style="display:none">
                                                <div class="form-group col-md-6 {{ $errors->has('source_text') ? 'has-error' : '' }} input_fields_wrap_text_{{$i}}">
                                                    <label for="source_text" class="required">Source Text</label>
                                                    <textarea rows="4" cols="50" name="source_text_{{$i}}[]" id="source_text_{{$i}}" class="form-control source_text_{{$i}}"></textarea>
                                                </div>
                                                <div style="margin-top:29px;" class="form-group col-md-2">
                                                    @if($i== 0)
                                                    <input type="button" class="btn btn-success float-right" onclick="add_more_text_upload({{$i}})" id="id_more_text_upload_{{$i}}" value="More Text" name="submit">
                                                    @else
                                                    <input type="button" class="remove_field btn btn-danger float-right" id="{{ $i }}" onclick="javascript:remove_more_upload_({{ $i }});" value="Remove">
                                                    @endif
                                                </div>
                                                <div class="col-md-2"></div>
                                                <div style="margin-top:29px;" class="form-group col-md-2">
                                                </div>
                                            </div>
                                        </div>
                                        <?php $f++;
                                        $t++; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php $i++; ?>
                    @endforeach
                </div>
                <!-- <div id="add_rows_request"></div> -->
                <div class="form-group col-md-6 {{ $errors->has('special_instruction') ? 'has-error' : '' }}">
                    <label for="special_instruction">Specific instructions</label>
                    <textarea rows="4" cols="50" name="special_instruction" id="special_instruction" id="special_instruction" class="form-control">{{$getreq->special_instructions ?? ''}}</textarea>
                    @if($errors->has('special_instruction'))
                    <em class="invalid-feedback">
                        {{ $errors->first('special_instruction') }}
                    </em>
                    @endif
                </div>
                <div class="col-md-6">&nbsp;</div>
                <div class="col-md-6">
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
    function checksourcetype(type_id) {
        var i = $('#source_type_' + type_id).val();

        if (i == "Text") // equal to a selection option
        {
            $('#div_source_text_' + type_id).show();
            $('.source_text_' + type_id).prop("required", true);
            $('#div_source_file_' + type_id).hide();
            $('.source_file_' + type_id).prop("required", false);
            //$('#div_source_website').hide();
        } else
        if (i == "File") {
            $('#div_source_text_' + type_id).hide();

            $('.source_text_' + type_id).prop("required", false);
            //$('#div_source_website').hide();
            $('#div_source_file_' + type_id).show();

            $('.source_file_' + type_id).prop("required", true);
        }

    }

    $("#source_language").change(function() {
        var source_value = $(this).data('source_id');
        var type_id = $(this).data('source_type');
        $('#my_files_' + source_value).html('');
        $('.source_file_' + type_id).prop("required", true);
        $('#my_source_files_' + type_id).addClass('required');
    });
    // $(document).ready(function() {
    var max_fields = 10; //maximum input boxes allowed
    var wrapper = $(".input_fields_wrap2"); //Fields wrapper
    var add_button = $("#id_more_upload"); //Add button ID

    var x = <?php echo $t; ?>; //initlal text box count


    function remove_more_upload(id) {
        $("#div_source_file_" + id).remove();
        x--;
    }

    function add_more_upload(id) {

        //var max_fields = 10; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap2_" + id); //Fields wrapper
        var add_button = $("#id_more_upload"); //Add button ID

        //var x = 1; //initlal text box count
       // if (x < max_fields) { //max input box allowed
            //text box increment
            $(wrapper).append('<tr id="div_source_file_' + x + '" ><td><input type="file" class="form-control source_file_'+ id +'" name="source_file_' + id + '[]"  id="source_file_'+ id +'" required></td><td><button type="button" class="btn btn-danger btn-xs float-right" id="remove_field" onclick="remove_more_upload(' + x + ')" name="submit"><i class="fas fa-trash"></i></button></td></tr>');
            // $(wrapper).append('<div id="div_source_file_' + x + '" ><div class="row"><div class="col-md-10"><input type="file" class="form-control source_file_' + id + '" name="source_file_' + id + '[]"  id="source_file_' + id + '" required></div><div class="col-md-2"><button type="button" class="btn btn-danger " id="remove_field" onclick="remove_more_upload(' + x + ')" value="Remove" name="submit"><i class="fas fa-trash"></i></button></div></div></div></div>');
            x++;
        //}
    }
    // });
    // $(document).ready(function() {
    var max_text_fields = 10; //maximum input boxes allowed
    var wrapper_text = $(".input_fields_wrap_text"); //Fields wrapper
    var add_button = $("#id_more_text_upload");
    var y = <?php echo $f; ?>;

    function remove_text_upload(id) {

        $("#source_text_div_" + id).remove();
        y--;
    }

    function add_more_text_upload(id) { //alert(id);
        var max_text_fields = 10; //maximum input boxes allowed
        var wrapper = $(".input_fields_wrap_text_" + id); //Fields wrapper
        var add_button = $("#id_more_text_upload"); //Add button ID
        //var x = 1; //initlal text box count
        if (y < max_text_fields) { //max input box allowed
            //text box increment
            $(wrapper).append('<div id="source_text_div_' + y + '" class="form-group"><label for="source_text" class="required">Source Text</label><textarea rows="4" cols="50" name="source_text_' + id + '[]" id="source_text_' + id + '" class="form-control source_text_' + id + '" required></textarea><br/><input type="button" class="btn btn-danger float-right" id="remove_field" onclick="remove_text_upload(' + y + ')" value="Remove" name="submit"> </div>');
            y++;
        }
    }
    // });
    var id = <?php echo ($i > 0) ? $i - 1 : 0; ?>;

    // var count = id;
    // alert(id.length)
    //alert(id);
    var addrow_page = id;
    $(document.body).on('click', "#id_more_div", function(e) { //alert(addrow_page);
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
    function selectRefresh() {
       $('.select2').select2({
           tags: true,
           /*placeholder: "Select an Option"*/
       });
    }


    function add_more_upload(id) {
        var wrapper = $("#addmorevendor_" + id);
        var req_service = $('#req_service_' + id).val();
        var per_unit = $('#per_unit_0_0').val();
        var unit_count = $('#unit_count_0_0').val();
        var amount_per_vendor = $('#amount_per_vendor_0_0').val();
        var vendors = <?php echo $vendors; ?>;
        var vendor_id = '{{((isset($_GET["translator"]) && $_GET["translator"] !="")? $_GET["translator"] : "")}}';
        var options = '<tr id="vendor_add_more_' + x + '"><td>' + (id) + '</td><td><div class="row"><div class="col-md-2"><input type="file" class="form-control source_files_list source_file_' + id + '" name="source_file_' + id + '[]"  id="source_file_' + id + '" required></div><div class="col-md-3"><select  class="form-control select2" id="tr_list_select_' + id + '_' + x + '" name="tr_list_select[' + id + '][]" required><option value="">Select User</option>';
        for (var i = 0; i < vendors.length; i++) {
            var v_state = (vendors[i].id == vendor_id) ? 'selected' : '';
            options += '<option value="' + vendors[i].id + '" ' + v_state + '>' + vendors[i].name + '</option>';
        }
        options += '</select></div><div class="col"><select id="assign_gst_' + id + '_' + x + '" name="assign_gst[' + id + '][]" onchange="gstchange1(this.value,' + id + ',' + x + ')" class="form-control select2" required><option value="gst">GST</option><option value="both">SGST & CGST</option><option value="no_gst">No GST</option></select></div><div class="col"><select name="currency[' + id + '][]" id="currency_' + id + '_' + x + '" class="form-control select2" required>@foreach($currency_list as $cl)<option value="{{$cl->id}}" {{(($get_quote_data->translation_quote_currency == $cl->id)? "selected" : "")}}>{{$cl->currency_name}}</option>@endforeach</select></div><div class="col"><input type="number" name="unit_count[' + id + '][]" id="unit_count_' + id + '_' + x + '" onkeyup="unit_count1(this.value,' + id + ',' + x + ')" class="form-control" value="' + unit_count + '" placeholder="' + req_service + ' Count" min="1" required="" /></div><div class="col"><input type="number" name="per_unit[' + id + '][]" id="per_unit_' + id + '_' + x + '" onkeyup="per_unit1(this.value,' + id + ',' + x + ')" placeholder="Per Unit" class="form-control" min="0" step="0.1" required="" value="' + per_unit + '"></div><div class="col"><input type="number" name="amount_per_vendor[' + id + '][]" id="amount_per_vendor_' + id + '_' + x + '" min="0.1" class="form-control amount_list" required placeholder="Total" value="' + amount_per_vendor + '" readonly/></div></div></td><td><button type="button" class="btn btn-danger btn-xs" id="remove_field" onclick="remove_more_upload(' + x + ')" name="submit"><i class="fas fa-trash"></i></button></td></tr>';
        $(wrapper).after(options);
        x++;
        selectRefresh();
    }



    function validate_field_page(id) {
        var dyanmic_row_id = id;
        var div = document.getElementById("dyanmic_fields_" + dyanmic_row_id);
        div.parentNode.removeChild(div);
    }

//     function selectRefresh() {
     
//        $('.select2').select2({
//         alert("hai");
//            tags: true,
//            /*placeholder: "Select an Option"*/
//        });
//    }



    $('.show_confirm').click(function(e) {

        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });


    function add_more_upload(id) {
        var wrapper = $("#addmorevendor_" + id);
        var req_service = $('#req_service_' + id).val();
        var per_unit = $('#per_unit_0_0').val();
        var unit_count = $('#unit_count_0_0').val();
        var amount_per_vendor = $('#amount_per_vendor_0_0').val();
        var vendors = <?php echo $vendors; ?>;
        var vendor_id = '{{((isset($_GET["translator"]) && $_GET["translator"] !="")? $_GET["translator"] : "")}}';
        var options = '<tr id="vendor_add_more_' + x + '"><td>' + (id) + '</td><td><div class="row"><div class="col-md-2"><input type="file" class="form-control source_files_list source_file_' + id + '" name="source_file_' + id + '[]"  id="source_file_' + id + '" required></div><div class="col-md-3"><select  class="form-control select2" id="tr_list_select_' + id + '_' + x + '" name="tr_list_select[' + id + '][]" required><option value="">Select User</option>';
        for (var i = 0; i < vendors.length; i++) {
            var v_state = (vendors[i].id == vendor_id) ? 'selected' : '';
            options += '<option value="' + vendors[i].id + '" ' + v_state + '>' + vendors[i].name + '</option>';
        }
        options += '</select></div><div class="col"><select id="assign_gst_' + id + '_' + x + '" name="assign_gst[' + id + '][]" onchange="gstchange1(this.value,' + id + ',' + x + ')" class="form-control select2" required><option value="gst">GST</option><option value="both">SGST & CGST</option><option value="no_gst">No GST</option></select></div><div class="col"><select name="currency[' + id + '][]" id="currency_' + id + '_' + x + '" class="form-control select2" required>@foreach($currency_list as $cl)<option value="{{$cl->id}}" {{(($get_quote_data->translation_quote_currency == $cl->id)? "selected" : "")}}>{{$cl->currency_name}}</option>@endforeach</select></div><div class="col"><input type="number" name="unit_count[' + id + '][]" id="unit_count_' + id + '_' + x + '" onkeyup="unit_count1(this.value,' + id + ',' + x + ')" class="form-control" value="' + unit_count + '" placeholder="' + req_service + ' Count" min="1" required="" /></div><div class="col"><input type="number" name="per_unit[' + id + '][]" id="per_unit_' + id + '_' + x + '" onkeyup="per_unit1(this.value,' + id + ',' + x + ')" placeholder="Per Unit" class="form-control" min="0" step="0.1" required="" value="' + per_unit + '"></div><div class="col"><input type="number" name="amount_per_vendor[' + id + '][]" id="amount_per_vendor_' + id + '_' + x + '" min="0.1" class="form-control amount_list" required placeholder="Total" value="' + amount_per_vendor + '" readonly/></div></div></td><td><button type="button" class="btn btn-danger btn-xs" id="remove_field" onclick="remove_more_upload(' + x + ')" name="submit"><i class="fas fa-trash"></i></button></td></tr>';
        $(wrapper).after(options);
        x++;
        selectRefresh();
    }

    function selectRefresh() {
        $('.select2').select2();
    }

    function change_vendor(v_id) {
        window.location.href = "{{ route('admin.request.createrequest',$get_quote_data->quote_code) }}?translator=" + v_id;
    }


    $("#fileToUpload").on('change', function() {
        $('#myfiles_list').html('');
        var names = [];
        for (var i = 0; i < $(this).get(0).files.length; ++i) {
            $('#myfiles_list').append('<li>'+($(this).get(0).files[i].name)+'</li>');
        }
        $('.source_files_list').hide();
        //$('.source_files_list').prop("required", true);
        $('.source_files_list').prop("required", false);
        $('#multiplefiles').val('1');
    });
</script>
@endsection