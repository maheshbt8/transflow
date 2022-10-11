@extends('layouts.admin')
@section('content')
<div class="card">
    <?php $user_role = Auth::user()->roles[0]->name; ?>
    <div class="card-header">
        {{ trans('cruds.locrequest.title_singular') }} {{ trans('global.create') }}
    </div>

    <div class="card-body">
        <div class="d-flex justify-content">
            <form action="{{ route("admin.request.store")}}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
                @csrf
                <div class="row">
                    <div class="form-group col-md-6 {{ $errors->has('brief_description') ? 'has-error' : '' }}">
                        <label for="project_name" class="required">Brief Description</label>
                        <textarea rows="4" cols="50" name="brief_description" id="brief_description" id="brief_description" class="form-control" required></textarea>
                        @if($errors->has('brief_description'))
                        <em class="invalid-feedback">
                            {{ $errors->first('brief_description') }}
                        </em>
                        @endif
                    </div>
                <div class="col-md-6">
                        <div class="row">
                    <div class="form-group col-md-6">
                        <label for="project_name" class="required">Choose Translator</label>
                        <select class="form-control select2" id="vendors_list" onchange="change_vendor(this.value)">
                            <option value="">Select User</option>
                            <?php for ($i1 = 0; $i1 < count($vendors); $i1++) { ?>
                                <option value="{{$vendors[$i1]->id}}" {{((isset($_GET["translator"]) && $_GET["translator"] == $vendors[$i1]->id)? "selected" : "")}}>{{$vendors[$i1]->name}}</option>
                            <?php } ?>

                        </select>
                    </div>
                    <div class="form-group col-md-6">
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
                    </div>
                    <?php
                    if ($user_role != 'clientuser' && $user_role != 'requester' && $user_role != 'approval' && $user_role != 'reviewer') {
                        if ($get_quote_data->client_org_id == '') { ?>
                            <div class="form-group col-md-6 {{ $errors->has('') ? 'has-error' : '' }}">
                                <label class="required">Client Organization</label>
                                <select name="client_org" id="client_org" class="form-control select2" required>
                                    <option value="">Select Client Organization</option>
                                    @foreach($clientorganizations as $id => $org)
                                    <option value="{{$org->org_id}}" <?php echo (($get_quote_data->client_org_id == $org->org_id) ? 'selected' : ''); ?>>{{$org->org_name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" for="">Please Select Client Organization</div>
                            </div>
                        <?php } else { ?><input type="hidden" name="client_org" value="{{ $get_quote_data->client_org_id }}" /><?php }
                                                                                                                        } ?>
                    <div class="col-md-12">
                        <input type="hidden" name="quote_id" value="{{$get_quote_data->translation_quote_id}}">
                        <?php $i = 0;
                        $t = $f = 1;
                        ?>
                        <?php
                        $get_source_lang = $getst_lang->quote_lang_select($get_quote_data->translation_quote_id);
                        ?>

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
                    <div class="col-md-6">&nbsp;</div>
                    <div class="col-md-6">
                        <input class="btn btn-danger" id="translation_submit" type="submit" value="{{ trans('global.submit') }}">
                    </div>


                    <!-- New end -->

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


    function gstchange1(value, id, inc) {
        var unit_count = $('#unit_count_' + id + '_' + inc).val();
        var per_unit = $('#per_unit_' + id + '_' + inc).val();
        calucalte_amount(unit_count, per_unit, id, inc);
    }

    function per_unit1(value, id, inc) {
        var unit_count = $('#unit_count_' + id + '_' + inc).val();
        calucalte_amount(unit_count, value, id, inc);
    }

    function unit_count1(value, id, inc) {
        var per_unit = $('#per_unit_' + id + '_' + inc).val();
        calucalte_amount(value, per_unit, id, inc);
    }

    function calucalte_amount(unit_count, per_unit, id, inc) {
        var assign_gst = $('#assign_gst_' + id + '_' + inc).val();
        var amount = unit_count * per_unit;
        if (assign_gst == 'no_gst') {
            var total = amount;
        } else {
            var total = amount + ((amount * 18) / 100);
        }
        $('#amount_per_vendor_' + id + '_' + inc).val(total);
        //grandtotal(id,inc);
    }

    var x = <?php echo $t; ?>; //initlal text box count


    function remove_more_upload(id) {
        $("#vendor_add_more_" + id).remove();
        x--;
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