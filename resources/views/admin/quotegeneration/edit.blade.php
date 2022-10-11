@extends('layouts.admin')
@section('content')
@if(isset($_GET['repeat_quote']) && $_GET['repeat_quote'] != '')
    @if(base64_decode($_GET['repeat_quote']) != 'yes')
        <?php echo "Something went wrong please contact admin.";die;?>
    @endif
@endif
<style>
    /*.remove_field {
        margin-top: -80px;
        position: relative;
    }*/

    .alert {
        background-color: #ff9999;

    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    .myclass {
        text-transform: capitalize;
    }

    .hide_price_div {
        display: none;
    }

    /*    .select2 {
width:100%!important;
}*/
</style>


<meta name="csrf_token" content="{{csrf_token()}}">
<?php
echo get_user_org('org_id');
?>
<div class="card">

    <div class="card-header">
        Edit Quote
        <!-- {{ trans('cruds.quotegenerate.title_singular') }} {{ trans('global.list') }} -->
    </div>

    <div class="card-body">
        @if(isset($_GET['repeat_quote']) && $_GET['repeat_quote'] != '')
            @if(base64_decode($_GET['repeat_quote']) == 'yes')
                <form action="{{ route("admin.quotegeneration.store") }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate="">
            @endif
        @else
        <form action="{{ route("admin.quotegeneration.update",[$arr_sales_quote_generation_details->quote_code]) }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate="">
        @method('PUT')
        @endif
            @csrf
            <div class="span9" id="error_on_header"></div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="Subject:" class="required">Subject: </label>
                        <!-- <textarea name="subject" id="id_subject" required class="form-control myclass" rows="5"></textarea> -->
                        <input type="text" name="subject" id="id_subject" required class="form-control myclass" value="<?php echo  old('name', isset($arr_sales_quote_generation_details->translation_quote_subject) ? str_replace('<br />', '', $arr_sales_quote_generation_details->translation_quote_subject) : '') ?>">
                        <div class="invalid-feedback" for="">Must Add The Subject</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label class="required">Client Organization</label>
                        <select name="client_org" id="client_org" class="form-control select2" required>
                            <option value="">Select Client Organization</option>
                            @foreach($clientorganizations as $id => $org)
                            <option value="{{$org->org_id}}" data-org_name="{{$org->org_name}}" <?php echo (($arr_sales_quote_generation_details->client_org_id == $org->org_id) ? 'selected' : ''); ?>>{{$org->org_name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" for="">Please Select Client Organization</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label class="required">Contact Person</label>
                        <select name="" id="client_org_users" class="form-control select2">
                            <option value="">Select contact person</option>
                            @foreach($client_users as $row)
                            <option value="{{$row->id}}" data-name="{{$row->name}}" data-email="{{$row->email}}" data-mobile="{{$row->mobile}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" for="">Please Select Client Organization</div>
                    </div>
                    <input type="hidden" name="comp_name" id="comp_name" class="form-control" value="{{ old('name', isset($arr_sales_quote_generation_details->client_comp_name) ? $arr_sales_quote_generation_details->client_comp_name : '') }}" required>
                </div>
                <!-- <div class="col-md-3">
                <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                <label for="first_name" class="required">Company Name</label>
                <input type="text" name="comp_name" id="comp_name" class="form-control" value="{{ old('name', isset($user)) ? $user->comp_name : '' }}" required>
                @if($errors->has('name'))
                <em class="invalid-feedback">
                {{ $errors->first('name') }}
                </em>
                @endif
                <p class="helper-block">
                {{ trans('cruds.quotegenerate.fields.fname_helper') }}
                </p>
                <div class="invalid-feedback" for="">Must enter your Company Name</div>
                </div>
                </div> -->
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="To Address" class="required">Client Address: </label>
                        <!-- <textarea name="to_address" id="id_to_address" class="form-control myclass" rows="1" required></textarea> -->
                        <select name="to_address" id="id_to_address" class="form-control select2" required>
                            <option value="">Select Client Organization</option>
                            @foreach($client_org_addr as $adr)
                            <option value="{{$adr->id}}" {{ ($adr->id == $arr_sales_quote_generation_details->to_address_id)? 'selected' : ''  }}><?php echo $adr->address; ?></option>
                            @endforeach
                        </select>
                        </select>
                        <div class="invalid-feedback" for="">Must Add The Address</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="first_name" class="required">First Name</label>
                        <input type="text" name="first_name" id="fname" class="form-control myclass" value="{{ old('name', isset($arr_sales_quote_generation_details->first_name) ? $arr_sales_quote_generation_details->first_name : '') }}" required>
                        @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.quotegenerate.fields.fname_helper') }}
                        </p>
                        <div class="invalid-feedback" for="">Must enter your First Name</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="last_name" class="required">Last Name</label>
                        <input type="text" name="last_name" id="lname" class="form-control " value="{{ old('name', isset($arr_sales_quote_generation_details->last_name) ? $arr_sales_quote_generation_details->last_name : '') }}" required>
                        @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.quotegenerate.fields.lname_helper') }}
                        </p>
                        <div class="invalid-feedback" for="">Must enter your Last Name</div>
                    </div>
                </div>
                <?php
                if (get_user_org('name') == "administrator") {
                ?>
                    <div class="col-md-3">
                        <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                            <label class="required">organization</label>
                            <select name="org" id="organization" class="form-control select2" required>
                                <option value="">Select Organization</option>
                                @foreach($kptorganization as $id => $org)
                                <option value="{{ $id }}" {{ (($id == (old('organization') ?? ''))? 'selected' : '') }} {{ (isset($user) && $user->org->contains($org->name)) ? 'selected' : '' }}>{{ $org }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" for="">Please Select Organization</div>
                        </div>
                    </div>
                <?php
                } else {
                ?>
                    <input type="hidden" value="{{ old('name', isset($arr_sales_quote_generation_details->organization) ? $arr_sales_quote_generation_details->organization : '') }}" id="org_id" name="org" />
                <?php } ?>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="Email Id" class="required">Email Id</label>
                        <input type="email" class="form-control" placeholder="Email id" id="email" name="email" value="{{ old('name', isset($arr_sales_quote_generation_details->email) ? $arr_sales_quote_generation_details->email : '') }}" required>
                        <div class="invalid-feedback" for="">Must enter your Email Id</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="number" class="required">Mobile Number</label>
                        <input type="text" id="mobile" class="form-control" placeholder="Mobile Number" name="mob_number" pattern="^\+[1-9]{1}[0-9]{3,14}$" value="{{ old('name', isset($arr_sales_quote_generation_details->mob_number) ? $arr_sales_quote_generation_details->mob_number : '') }}" required>
                        <div class="invalid-feedback" for="">Must enter your 10 Digit Mobile Number</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('quote_heading') ? 'has-error' : '' }}">
                        <label for="Heading" class="required">Heading:</label>
                        <select name="quote_heading" id="" class="form-control select2" required>
                            <option value="yes">YES</option>
                            <option value="no">NO</option>
                            <option value="po">Purches Order</option>
                        </select>
                        <div class="invalid-feedback" for="">Must select the Heading</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="date" class="required">Date</label>
                        <input type="date" name="current_date" value="{{ date('Y-m-d',strtotime($arr_sales_quote_generation_details->translation_quote_date)) }}" class="form-control" id="id_cuurent_date" required selected>

                        <div class="invalid-feedback" for="">Must select the Date</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label class="required">Organization Addresss</label>
                        <select name="org_adrs" id="org_adrs" class="form-control select2" required onchange="selectaddress(this.value)">
                            <option value="">Select Client Organization</option>
                            @foreach($org_addr as $adr)
                            <option value="{{$adr->id}}" {{ ($adr->id == $arr_sales_quote_generation_details->address_id)? 'selected' : '' }}  data-addr="<?php echo $adr->address; ?>"><?php echo $adr->address; ?></option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" for="">Please Select Client Organization</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="GST" class="required">Product Delivery Date:</label>
                        <select name="weeks" id="weeks" class="form-control select2" required>
                            <?php
                            for ($i1 = 1; $i1 <= 10; $i1++) {
                            ?>
                                <option value="<?php echo $i1; ?>" {{$i1==$arr_sales_quote_generation_details->weeks? 'selected' : '' }}><?php echo ($i1 == 1) ? $i1 . ' Week' : $i1 . ' Weeks'; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="GST" class="required">Quote Type:</label>
                        <select name="quote_type" id="quote_type" class="form-control select2" required>
                            <option value="quote" <?php echo (('quote' == $arr_sales_quote_generation_details->quote_type) ? 'selected' : '') ?>>Quote</option>
                            <option value="sample" <?php echo (('sample' == $arr_sales_quote_generation_details->quote_type) ? 'selected' : '') ?>>Sample</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="number" class="required">Project Manager Cost</label>
                        <input type="number" class="form-control" value="{{ old('name', isset($arr_sales_quote_generation_details->pm_cost) ? strip_tags($arr_sales_quote_generation_details->pm_cost) : 0) }}" min="0" max="100" name="pm_cost" required>
                        <div class="invalid-feedback" for="">inter the pm cost</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="Currency" class="required">Currency:</label>
                        <select name="currency" id="currency" class="form-control select2" required>
                            @foreach($currency_list as $cl)
                            <option value="{{$cl->id}}" {{ (($cl->id == $arr_sales_quote_generation_details->translation_quote_currency)? 'selected':'')}}  data-currency="{{$cl->currency_code}}">{{$cl->currency_name .' - '. $cl->currency_code}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="GST" class="required">GST:</label>
                        <select name="gst_type" id="gst" class="form-control select2" required>
                            <option value="yes" <?php echo (('sample' == $arr_sales_quote_generation_details->translation_quote_gst) ? 'selected' : ''); ?>>YES</option>
                            <option value="no" <?php echo (('no' == $arr_sales_quote_generation_details->translation_quote_gst) ? 'selected' : ''); ?>>NO</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="GST" class="required">Repeat section:</label>
                        <select name="repeat_section" id="repeat_section" class="form-control select2" required>

                            <option value="no">no</option>
                            <option value="yes">yes</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3" id="repeat_target_div" style="display: none;">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="GST" class="required">Target Languages:</label>
                        <select name="repeat_target" id="repeat_target" class="form-control select2" multiple="">
                            <option value="" disabled="">Select Target Languages</option>
                            @foreach($language as $key => $lang)
                            <option value="{{ $lang->lang_id }}">{{ $lang->lang_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3" id="">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="Terms" class="required">Select Template:</label>
                        <select name="name" id="terms" class="form-control">
                            <option value="">Select Template</option>
                            @foreach($terms as $key => $term)
                            <option value="{{ $term->id }}" data-terms_name="{{$term->template_name}}">{{ $term->template_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
    <div class="row" id="add_rows_request">
        <?php
        $i = 0;
        ?>
        @foreach($loc_source_language as $assign)
        <div class="col-md-6" id="<?php if ($i != 0) {
                                        echo 'page_dyanmic_fields_' . $i;
                                    } ?>">

            <input type="hidden" value="{{ $assign->id }}" name="db_source_lang_id[]" />
            <input type="hidden" value="{{ $i }}" name="db_source_lang_index[]" />
            <div class="card">
                <div class="card-header">
                    Section {{$i+1}}
                    <div class="card-tools">
                        @if($i== 0)
                        <button type="button" class="btn btn-tool bg-success" id="id_more_div"><i class="fas fa-plus"></i></button>
                        @else
                        <button type="button" class="remove_field btn btn-tool bg-danger" id="{{ $i }}" onClick="javascript:validate_dynamic_field_page({{ $i }});"><i class="fas fa-times"></i></button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    $service_type = DB::table('loc_quote_service')->where(['quote_id' => $assign->quote_id, 'loc_source_id' => $assign->id])->get()->toArray();
                    $get_service_type = array_column((array)$service_type, 'service_type');
                    ?>
                    <?php
                    $target_lang = DB::table('loc_request_assigned')->where(['quote_id' => $assign->quote_id, 'loc_source_id' => $assign->id])->get('target_language')->toArray();
                    $get_target_lang = array_column((array)$target_lang, 'target_language');

                    $target_lang_data = DB::table('loc_request_assigned')->where(['quote_id' => $assign->quote_id, 'loc_source_id' => $assign->id])->first();
                    /*$target_lang=$getst_lang->quote_lang_select($arr_sales_quote_generation_details->translation_quote_id,'target',$assign->id);
        print_r($target_lang);
        $get_target_lang = array_column((array)$target_lang, 'target_language');*/
                    $price_data = DB::table('loc_request_assigned')->where(['quote_id' => $assign->quote_id, 'loc_source_id' => $assign->id])->first();
                    // print_r(['quote_id' => $assign->quote_id, 'loc_source_id' => $assign->id]);die;
                    ?>
                    <div class="col-md-12">
                        <div class="row">
                            @if($req_quote_count == 0)
                            <div class="col-md-4 form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
                                <label for="source_language" class="required">Source Language</label>
                                <select name="source_language[]" id="source_language_{{$i}}" class="form-control select2" required>
                                    <option value="">Select Source Language</option>
                                    @foreach($language as $key => $lang)
                                    <option value="{{ $lang->lang_id }}" <?php echo (($lang->lang_id == $assign->lang_id) ? 'selected' : '') ?>>{{ $lang->lang_name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('source_language'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('source_language') }}
                                </em>
                                @endif
                                <div class="invalid-feedback" for="">Must Select your Source Language</div>
                            </div>
                            <?php  ?>

                            <div class="form-group col-md-4 {{ $errors->has('destination_language') ? 'has-error' : '' }}">
                                <label for="destination_language" class="required">Target Language</label>
                                <select name="destination_language_{{$i}}[]" id="destination_language_{{$i}}" class="form-control select2" required>
                                    <option value="">Select Target Language</option>
                                    @foreach($language as $key => $lang)
                                    <option value="{{ $lang->lang_id }}" <?php echo ((isset($target_lang_data->target_language) && $lang->lang_id == $target_lang_data->target_language) ? 'selected' : '') ?>>{{ $lang->lang_name }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('destination_language'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('destination_language') }}
                                </em>
                                @endif
                                <div class="invalid-feedback" for="">Must Select your Target Language</div>
                            </div>
                            @else
                            <div class="col-md-4 form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
                                <label for="source_language">Source Language</label>
                                <input type="hidden" name="source_language[]" id="source_language_{{$i}}" value="{{ $assign->lang_id }}">
                                <ul>
                                    <li>{{ $assign->lang_name }}</li>
                                </ul>
                            </div>
                            <div class="form-group col-md-4 {{ $errors->has('destination_language') ? 'has-error' : '' }}">
                                <label for="destination_language">Target Language</label>
                                @foreach($language as $key => $lang)
                                <?php if ($lang->lang_id == $target_lang_data->target_language) { ?>
                                    <input type="hidden" name="destination_language_{{$i}}[]" selected id="destination_language_{{$i}}" value="{{ $lang->lang_id }}">
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
                            @endif
                            <div class="form-group col-md-4 {{ $errors->has('source_language') ? 'has-error' : '' }}">
                                <label class="required">Type of Service</label>
                                <select name="service_type_{{$i}}[]" id="service_type_{{$i}}" data-index="{{$i}}" class="form-control select2 service_type" onchange="getservice_type(this.value,{{$i}})" required>
                                    @foreach($loc_services as $service)
                                    <option value="{{$service->id}}" data-type="{{ $service->type }}" <?php echo ((isset($target_lang_data->service_type) && $service->id == $target_lang_data->service_type) ? 'selected' : '') ?>>{{$service->service_type}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" for="">Must select one of the Service</div>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description">Description: </label>
                                            <textarea name="description_{{$i}}" id="description_{{$i}}" class="form-control myclass" rows="5"> {{ old('name', isset($assign->description) ? $assign->description : '') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group {{((isset($price_data) && isset($price_data->word_count) && $price_data->word_count !='') || (isset($price_data) && isset($price_data->word_fixed_cost) && $price_data->word_fixed_cost != ''))? '' : 'hide_price_div'}}" id="word_price_div_{{$i}}">
                                            <label>Word Count & Cost Per word: *</label>
                                            <div class="form-row">
                                                <div class="col-md-12"><input type="number" min="1" max="1000000000" id="words_count_{{$i}}" name="words_count_{{$i}}" class="form-control" onkeyup="getpercost('word',0,this.value)" placeholder="word count" value="{{$price_data->word_count ?? ''}}"><br /></div>
                                                <div class=col-md-12><input type="text" id="cost_per_word_{{$i}}" name="cost_per_word_{{$i}}" class="form-control float-number " placeholder="cost for word" value="{{(isset($price_data) && isset($price_data->per_word_cost) && $price_data->per_word_cost !='')? $price_data->per_word_cost : ''}}"><br />
                                                </div>
                                                <div class="col-md-12"><input type="text" name="word_fixed_cost_{{$i}}" class="form-control float-number" id="word_fixed_cost_{{$i}}" placeholder="fixed cost" value="{{$price_data->word_fixed_cost ?? ''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group  {{((isset($price_data->page_count) && $price_data->page_count !='') || (isset($price_data->page_fixed_cost) && $price_data->page_fixed_cost != ''))? '' : 'hide_price_div'}}" id="page_price_div_{{$i}}">
                                            <label>Page Count & Cost Per Page: </label>
                                            <div class="form-row">
                                                <div class="col-md-12"><input type="number" min="1" max="1000000000" id="page_count_{{$i}}" name="page_count_{{$i}}" class="form-control" placeholder="page count" value="{{$price_data->page_count ?? ''}}" onkeyup="getpercost('page',0,this.value)"><br /></div>
                                                <div class=col-md-12><input type="text" name="cost_per_page_{{$i}}" id="cost_per_page_{{$i}}" class="form-control float-number" placeholder="cost for page" value="{{$price_data->per_page_cost ?? ''}}"><br />
                                                </div>
                                                <div class="col-md-12"> <input type="text" name="page_fixed_cost_{{$i}}" id="page_fixed_cost_{{$i}}" class="form-control float-number" placeholder="fixed cost" value="{{$price_data->page_fixed_cost ?? ''}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group  {{((isset($price_data->minute_count) && $price_data->minute_count !='') || (isset($price_data->minute_fixed_cost) && $price_data->minute_fixed_cost != ''))? '' : 'hide_price_div'}}" id="minute_price_div_{{$i}}">
                                            <label id="minute_label_{{$i}}">
                                                <?php $service_type_lab = gettabledata('loc_service', 'type', ['id' => ($target_lang_data->service_type ?? '')]);
                                                if ($service_type == 'minute') {
                                                    echo 'Minutes Count & Cost Per Minute: *';
                                                } else {
                                                    echo "Minutes Count & Cost for slab: *";
                                                } ?></label>
                                            <div class="form-row">
                                                <div class="col-md-12"> <input type="number" min="1" max="1000000000" id="minute_words_count_{{$i}}" name="minute_words_count_{{$i}}" class="form-control" placeholder="Minute count" value="{{$price_data->minute_count ?? ''}}" onkeyup="getpercost('minute',0,this.value)"><br /></div>
                                                <div class="col-md-12"> <input type="text" name="minute_cost_per_word_{{$i}}" id="minute_cost_per_word_{{$i}}" class="form-control float-number" placeholder="cost for Minute" value="{{$price_data->per_minute_cost ?? ''}}"><br /></div>
                                                <div class="col-md-12"> <input type="text" name="minute_fixed_cost_{{$i}}" id="minute_fixed_cost_{{$i}}" class="form-control float-number" placeholder="fixed cost" value="{{$price_data->minute_fixed_cost ?? ''}}"></div>

                                            </div>
                                        </div>
                                        <div class="form-group   {{((isset($price_data->resource_count) && $price_data->resource_count !='') || (isset($price_data->resource_fixed_cost) && $price_data->resource_fixed_cost != ''))? '' : 'hide_price_div'}}" id="resource_price_div_{{$i}}">
                                            <label id="resource_label_{{$i}}"> Resource Count & Cost Per Resource: *</label>
                                            <div class="form-row">
                                                <div class="col-md-12"> <input type="number" min="1" max="1000000000" id="resource_words_count_{{$i}}" name="resource_words_count_{{$i}}" class="form-control" placeholder="Resource Count" value="{{$price_data->resource_count ?? ''}}" onkeyup=" getpercost('resource',0,this.value)"><br></div>
                                                <div class="col-md-12"> <input type="text" name="resource_cost_per_word_{{$i}}" id="resource_cost_per_word_{{$i}}" class="form-control float-number" value="{{$price_data->cost_per_resource ?? ''}}" placeholder=" Cost Per Resource."><br></div>
                                                <div class="col-md-12"> <input type="text" name="resource_fixed_cost_{{$i}}" id="resource_fixed_cost_{{$i}}" class="form-control float-number" value="{{$price_data->resource_fixed_cost ?? ''}}" placeholder=" fixed cost"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $i++; ?>
        @endforeach

    </div>
    <!-- <div id="add_rows_request"></div> -->
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-md-12">
                    <lable>Terms of Use: </lable>
                    <textarea style="height:100px;" name="terms_of_use" class="form-control" id="id_terms_of_use" cols="30" rows="10">
1. Before we start the project, we must be provided with requisite Purchase Order/Work Order duly signed by an Authorized person of your organization.
                                                                                                                                                                                      
2. In case of any corrections to be carried out you must mark all the corrections once at a time so that the entire document will be corrected accordingly on free of charge basis. In case of repeated corrections required by you with your own ideas and creative thinking, then the same will be charged on per word or per minute basis.
                                                                               
                              </textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <button class="btn btn-success" id="id_btn_getquote" name="Generate_Quote" value="<?php echo 'Generate Quote'; ?>" type="submit">Generate quote </button>
            <button class="btn btn-success" id="btn_reset" type="submit">Cancel </button>
        </div>
    </div>

    </form>
</div>
</div>
@endsection
@section('scripts')
@parent
<script>
    function getservice_type(service_type, index) {

        $('.float-number').keypress(function(event) {
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        var type = $('#service_type_' + index).find(':selected').data('type');
        if (type == 'word') {
            $('#word_price_div_' + index).show();
            $('#page_price_div_' + index).hide();
            $('#minute_price_div_' + index).hide();
            $('#resource_price_div_' + index).hide();
            $('#description_div_' + index).show();
        } else if (type == 'page') {
            $('#word_price_div_' + index).hide();
            $('#page_price_div_' + index).show();
            $('#minute_price_div_' + index).hide();
            $('#resource_price_div_' + index).hide();
            $('#description_div_' + index).show();
        } else if (type == 'minute' || type == 'slab_minute') {
            $('#word_price_div_' + index).hide();
            $('#page_price_div_' + index).hide();
            $('#minute_price_div_' + index).show();
            $('#resource_price_div_' + index).hide();
            $('#description_div_' + index).show();
        } else if (type == 'resource') {

            $('#word_price_div_' + index).hide();
            $('#page_price_div_' + index).hide();
            $('#minute_price_div_' + index).hide();
            $('#resource_price_div_' + index).show();
            $('#description_div_' + index).show();

        } else {
            $('#word_price_div_' + index).hide();
            $('#page_price_div_' + index).hide();
            $('#minute_price_div_' + index).hide();
            $('#description_div_' + index).hide();
            $('#resource_price_div_' + index).hide();
        }

        $('#words_count_' + index).val('');
        $('#cost_per_word_' + index).val('');
        $('#word_fixed_cost_' + index).val('');

        $('#page_count_' + index).val('');
        $('#cost_per_page_' + index).val('');
        $('#page_fixed_cost_' + index).val('');

        $('#minute_words_count_' + index).val('');
        $('#minute_cost_per_word_' + index).val('');
        $('#minute_fixed_cost_' + index).val('');

        $('#resource_words_count_' + index).val('');
        $('#resource_cost_per_word_' + index).val('');
        $('#resource_fixed_cost_' + index).val('');
    }
    var pdf_getquote = 0;
    $("#id_btn_getquote").click(function() {
        var fname = $('#fname').val();
        var lname = $('#lname').val();
        var email = $('#email').val();
        var org = $('#organization').val();
        var mobile = $('#mobile').val();
        var id_type_request = $("#type_request").val();
        //var id_source_lan = $("#source_language").val();
        //var id_target_lan = $("#destination_language").val();
        var id_cuurent_date = $("#id_cuurent_date").val();
        var id_to_address = $("#id_to_address").val();
        var id_subject = $("#id_subject").val();
        var id_words_count = $("#id_words_count").val();
        var id_cost_per_word = $("#id_cost_per_word").val();
        var id_terms_of_use = $("#id_terms_of_use").val();
        //var source_language = $('#source_language').val();
        var comp_name = $('#comp_name').val();
        var org_adrs = $('#org_adrs').val();
        var str = '20';

        if (id_subject == "") {
            toastr.error("Please enter subject");
            $("#id_subject").focus().addClass('border_error');
            return false;
        }
        if (comp_name == "" || comp_name == null) {
            toastr.error("Please Select Client Organization");
            $("#client_org").focus().addClass('border_error');
            return false;
        }
        if (id_to_address == "") {
            toastr.error("Please select client address");
            $("#id_to_address").focus().addClass('border_error');
            return false;
        }
        if (fname == "" || fname == null) {
            toastr.error("Please enter first name");
            $("#fname").focus().addClass('border_error');
            return false;
        }
        if (lname == "" || lname == null) {
            toastr.error("Please enter last name");
            $("#lname").focus().addClass('border_error');
            return false;
        }
        if (email == "" || email == null) {
            toastr.error("Please enter email");
            $("#email").focus().addClass('border_error');
            return false;
        }
        if (mobile == "" || mobile == null) {
            toastr.error("Please enter mobile number");
            $("#mobile").focus().addClass('border_error');
            return false;
        }
        /* if(mobile != "" && mobile.length > str  ){
             toastr.error("Please enter 10 digit Mobile Number");
             $("#mobile").focus().addClass('border_error');
             return false; 
         }*/
        /*if (id_source_lan == "" || id_source_lan == null) {
            toastr.error("Please enter source languages");
            $("#source_lan").focus().addClass('border_error');
            return false;
        }
        if (id_target_lan == "" || id_target_lan == null) {
            toastr.error("Please enter target languages");
            $("#target_lan").focus().addClass('border_error');
            return false;
        }*/
        if (id_cuurent_date == "") {
            toastr.error("Please enter date");
            $("#id_cuurent_date").focus().addClass('border_error');
            return false;
        }
        if (org_adrs == "" || org_adrs == null) {
            toastr.error("Please select organization address");
            $("#org_adrs").focus().addClass('border_error');
            return false;
        }
        var dataValid = true;
        $('[name="source_language\\[\\]"]').each(function(index, value) {

            if ($(this).val() == '') {
                toastr.error("Please select source language");
                $(this).focus().addClass('border_error');
                dataValid = false;
                return false;
            } else {
                var destination_language_id = $("#destination_language_" + index).val();
                var id_destination_language = 0;
                if (destination_language_id != '') {
                    id_destination_language = 1;
                }
                if (id_destination_language == 0) {
                    toastr.error("Please select destination language");
                    $("[name^=destination_language_" + index + "]").eq(index).focus().addClass('border_error');
                    dataValid = false;
                    return false;
                } else {
                    var service_type_id = $("#service_type_" + index).val();
                    var service_type = 0;
                    if (service_type_id != '') {
                        service_type = 1;
                    }
                    if (service_type == 0) {
                        toastr.error("Please select service type");
                        $("[name^=service_type_" + index + "]").eq(index).focus().addClass('border_error');
                        dataValid = false;
                        return false;
                    }
                    var description = $("#description_" + index).val();

                    if (description == "") {
                        toastr.error("Please enter description");
                        $("#description_" + index).focus().addClass('border_error');
                        dataValid = false;
                        return false;
                    }
                    var type = $('#service_type_' + index).find(':selected').data('type');
                    if (type == 'word') {
                        var id_words_count_r = $("#words_count_" + index).val();
                        if (id_words_count_r == "") {
                            toastr.error("Please enter word count");
                            $("#words_count_" + index).focus().addClass('border_error');
                            dataValid = false;
                            return false;
                        }
                        if (id_words_count_r != "") {
                            if (!$.isNumeric(id_words_count_r)) {
                                toastr.error("please enter numbers instead of characters");
                                $("#words_count_" + index).focus().addClass('border_error');
                                dataValid = false;
                                return false;
                            }
                            var id_fixed_word_value_r = $("#word_fixed_cost_" + index).val();
                            if (id_fixed_word_value_r == "") {
                                var id_cost_per_word_r = $("#cost_per_word_" + index).val();
                                if (id_cost_per_word_r == "") {
                                    toastr.error("Please enter cost per word");
                                    $("#cost_per_word_" + index).focus().addClass('border_error');
                                    dataValid = false;
                                    return false;
                                }
                                if (id_cost_per_word_r != "") {
                                    if (!$.isNumeric(id_cost_per_word_r)) {
                                        toastr.error("please enter numbers instead of characters");
                                        $("#cost_per_word_" + index).focus().addClass('border_error');
                                        dataValid = false;
                                        return false;
                                    }
                                }
                            } else {
                                if (id_fixed_word_value_r != "" && !$.isNumeric(id_fixed_word_value_r)) {
                                    toastr.error("Please enter numbers instead of characters");
                                    $("#word_fixed_cost_" + index).focus().addClass('border_error');
                                    dataValid = false;
                                    return false;
                                }
                            }
                        }

                    } else if (type == 'page') {
                        var id_page_count_r = $("#page_count_" + index).val();
                        if (id_page_count_r == "") {
                            toastr.error("Please enter page count");
                            $("#page_count_" + index).focus().addClass('border_error');
                            dataValid = false;
                            return false;
                        }
                        if (id_page_count_r != "") {
                            if (!$.isNumeric(id_page_count_r)) {
                                toastr.error("please enter numbers instead of characters");
                                $("#page_count_" + index).focus().addClass('border_error');
                                dataValid = false;
                                return false;
                            }

                            var id_fixed_page_value_r = $("#page_fixed_cost_" + index).val();
                            if (id_fixed_page_value_r == "") {
                                var id_cost_per_page_r = $("#cost_per_page_" + index).val();
                                if (id_cost_per_page_r == "") {
                                    toastr.error("Please enter cost per page");
                                    $("#cost_per_page_" + index).focus().addClass('border_error');
                                    dataValid = false;
                                    return false;
                                }

                                if (id_cost_per_page_r != "") {
                                    if (!$.isNumeric(id_cost_per_page_r)) {
                                        toastr.error("please enter numbers instead of characters");
                                        $("#cost_per_page_" + index).focus().addClass('border_error');
                                        dataValid = false;
                                        return false;
                                    }
                                }
                            } else {
                                if (id_fixed_page_value_r != "" && !$.isNumeric(id_fixed_page_value_r)) {
                                    toastr.error("Please enter numbers instead of characters");
                                    $("#page_fixed_cost_" + index).focus().addClass('border_error');
                                    dataValid = false;
                                    return false;
                                }
                            }
                        }

                    } else if (type == 'minute' || type == 'slab_minute') {
                        var id_minute_count_r = $("#minute_words_count_" + index).val();

                        if (id_minute_count_r == "") {
                            toastr.error("Please enter minute count");
                            $("#minute_words_count_" + index).focus().addClass('border_error');
                            dataValid = false;
                            return false;
                        }
                        if (id_minute_count_r != "") {
                            if (!$.isNumeric(id_minute_count_r)) {
                                toastr.error("please enter numbers instead of characters");
                                $("#minute_words_count_" + index).focus().addClass('border_error');
                                dataValid = false;
                                return false;
                            }

                            var id_fixed_minute_value_r = $("#minute_fixed_cost_" + index).val();
                            if (id_fixed_minute_value_r == "") {
                                var id_cost_per_minute_r = $("#minute_cost_per_word_" + index).val();
                                if (id_cost_per_minute_r == "") {
                                    toastr.error("Please enter cost per minute");
                                    $("#minute_cost_per_word_" + index).focus().addClass('border_error');
                                    dataValid = false;
                                    return false;
                                }

                                if (id_cost_per_minute_r != "") {
                                    if (!$.isNumeric(id_cost_per_minute_r)) {
                                        toastr.error("Please enter numbers instead of characters");
                                        $("#minute_cost_per_word_" + index).focus().addClass('border_error');
                                        dataValid = false;
                                        return false;
                                    }
                                }
                            } else {
                                if (id_fixed_minute_value_r != "" && !$.isNumeric(id_fixed_minute_value_r)) {
                                    toastr.error("Please enter numbers instead of characters");
                                    $("#minute_fixed_cost_" + index).focus().addClass('border_error');
                                    dataValid = false;
                                    return false;
                                }
                            }
                        }

                    } else if (type == 'resource') {
                        var id_resource_count = $('#resource_words_count_' + index).val();


                        if (id_resource_count == "") {
                            toastr.error("Please enter Resource count");
                            $("#resource_words_count_" + index).focus().addClass('border_error');
                            dataValid = false;
                            return false;
                        }
                        if (id_resource_count != "") {
                            if (!$.isNumeric(resource_count)) {
                                toastr.error("please enter numbers instead of characters");
                                $("#resource_words_count_" + index).focus().addClass('border_error');
                                dataValid = false;
                                return false;
                            }

                            var resorce_fixed_cost = $("#resource_fixed_cost_" + index).val();
                            if (resorce_fixed_cost == "") {
                                var id_resource_cost_per_word = $("#resource_cost_per_word_" + index).val();
                                if (id_resource_cost_per_word == "") {
                                    toastr.error("Please enter cost per page");
                                    $("#resource_cost_per_word_" + index).focus().addClass('border_error');
                                    dataValid = false;
                                    return false;
                                }

                                if (id_resource_cost_per_word != "") {
                                    if (!$.isNumeric(id_resource_cost_per_word)) {
                                        toastr.error("please enter numbers instead of characters");
                                        $("#resource_cost_per_word_" + index).focus().addClass('border_error');
                                        dataValid = false;
                                        return false;
                                    }
                                }
                            } else {
                                if (resorce_fixed_cost != "" && !$.isNumeric(resorce_fixed_cost)) {
                                    toastr.error("Please enter numbers instead of characters");
                                    $("#resource_fixed_cost_" + index).focus().addClass('border_error');
                                    dataValid = false;
                                    return false;
                                }
                            }
                        }


                    }
                }

            }
        });

        if (dataValid) {
            // send data
        } else {
            return false;
        }
        pdf_getquote = 1;
    });



    function validate_dynamic_field_page(id) {
        var dyanmic_row_id = id;
        var div = document.getElementById("page_dyanmic_fields_" + dyanmic_row_id);
        div.parentNode.removeChild(div);
    }
    var id = <?php echo ($i > 0) ? $i : 0; ?>;

    // var count = id;
    //alert(id.length)



    $(document.body).on('change', "#repeat_section", function(e) {
        var repeat_section = $('#repeat_section').val();
        if (repeat_section == 'yes') {
            $('#repeat_target_div').show();
        } else {
            $('#repeat_target_div').hide();
        }
    });
    var addrow_page = id;
    $(document.body).on('click', "#id_more_div", function(e) {
    var repeat_section = $('#repeat_section').val();
    var repeat_target = $('#repeat_target').val();
    if (repeat_section == 'yes') {
      var word_count = cost_per_word = word_fixed_cost = page_count = cost_per_page = fixed_page_count = minute_count = minute_per_cost = minute_fixed_cost = '';
      var source_lang = document.getElementById("source_language_0").value;
      var destination_lang = document.getElementById("destination_language_0").value;
      var description = $("#description_0").val();
      var service_type_id = $(".service_type").val();
      var type = $('#service_type_0').find(':selected').data('type');
      if (type == 'word' || type == '1') {
        var word_count = $('#words_count_0').val();
        var cost_per_word = $('#cost_per_word_0').val();
        var word_fixed_cost = $('#word_fixed_cost_0').val();
      } else if (type == 'page' || type == '2') {
        var page_count = $('#page_count_0').val();
        var cost_per_page = $('#cost_per_page_0').val();
        var fixed_page_count = $('#page_fixed_cost_0').val();
      } else if (type == 'minute' || type == 'slab_minute' || type == '3') {
        var minute_count = $('#minute_words_count_0').val();
        var minute_per_cost = $('#minute_cost_per_word_0').val();
        var minute_fixed_cost = $('#minute_fixed_cost_0').val();
      }else if(type == 'resource'){
         var resource_count= $('#resource_words_count_0').val();
         var resource_per_cost=$('#resource_cost_per_word_0').val();
         var resource_fixed_cost= $('#resource_fixed_cost_0').val();
      }
      var data_list = {
        "addrow": addrow_page,
        "source_language_0": source_lang,
        "destination_language_0": destination_lang,
        "service_type": service_type_id,
        "description_0": description,
        "service_type_0": type,
        "words_count_0": word_count,
        "cost_per_word_0": cost_per_word,
        "word_fixed_cost_0": word_fixed_cost,
        "page_count_0": page_count,
        "cost_per_page_0": cost_per_page,
        "page_fixed_cost_0": fixed_page_count,
        "minute_words_count_0": minute_count,
        "minute_cost_per_word_0": minute_per_cost,
        "minute_fixed_cost_0": minute_fixed_cost,
        "resource_words_count_0": resource_count,
        "resource_cost_per_word_0": resource_per_cost,
        "resource_fixed_cost_0": resource_fixed_cost,
        "repeat_section": 'yes',
        "_token": "{{csrf_token()}}"
      };
      if (repeat_target != '') {
        $("#repeat_target :selected").map(function(i, el) {
          var v1 = $(el).val();
          data_list.destination_language_0 = v1;
          data_list.addrow = addrow_page;
          addrow_page++;
          addnewrow(data_list);
        }).get();
        $('#repeat_target').val(false).trigger("change");
      } else {
        addrow_page++;
        addnewrow(data_list);
      }

    } else {
      var data_list = {
        "addrow": addrow_page,
        "repeat_section": 'no',
        "_token": "{{csrf_token()}}"
      }
      addrow_page++;
      addnewrow(data_list);
    }
  });
    function addnewrow(data_list) {
        $.ajax({
            type: "post",
            dataType: "html",
            url: "{{route('admin.quotegeneration.request_dynamic_fields')}}",
            data: data_list,
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
    }

    function selectRefresh() {
    $('.select2').select2();
  }


    // function selectRefresh() {
    //     //$('.select2').select2();
    //     /*{
    //         tags: true,
    //         placeholder: "Select an Option"
    //     }*/
    // }

    function getpercost(type, index, count) {
        var source_lang = $('[name="source_language\\[\\]"]:eq(' + index + ')').val();
        var destination_lang = $("[name^=destination_language_" + index + "]").val();
        var service_type = $('#service_type_' + index).find(':selected').val();
        var currency = $('#currency').val();
        var total = '';
        if (type == 'word' || type == '1') {
            $('#cost_per_word_' + index).val(total);
        } else if (type == 'page' || type == '2') {
            $('#cost_per_page_' + index).val(total);
        } else if (type == 'minute' || type == 'slab_minute' || type == '3') {
            $('#minute_cost_per_word_' + index).val(total);
        }
        if ($.isNumeric(count) && count > 0) {
            if (source_lang != '' && destination_lang != '' && service_type != '' && currency != '') {
                $.ajax({
                    type: "POST",
                    url: "<?php echo route('admin.quotegeneration.get_quote_rate'); ?>",
                    headers: {
                        'x-csrf-token': _token
                    },
                    data: {
                        'count': count,
                        'source_language': source_lang,
                        'target_language': destination_lang,
                        'currency': currency,
                        'service_type': service_type
                    },

                    success: function(total) {
                        if (parseInt(total) > 0) {
                            if (type == 'word' || type == '1') {
                                $('#cost_per_word_' + index).val(total);
                            } else if (type == 'page' || type == '2') {
                                $('#cost_per_page_' + index).val(total);
                            } else if (type == 'minute' || type == '3') {
                                $('#minute_cost_per_word_' + index).val(total);
                            }
                        }
                    }
                });
            }
        } else {
            if (type == 'word' || type == '1') {
                $('#cost_per_word_' + index).val('');
            } else if (type == 'page' || type == '2') {
                $('#cost_per_page_' + index).val('');
            } else if (type == 'minute' || type == '3') {
                $('#minute_cost_per_word_' + index).val('');
            }
        }

    }

    $("#client_org").change(function() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        const org_id = $(this).val();
        var company_name = $(this).find(':selected').data('org_name');
        $('#comp_name').val(company_name);
        $.ajax({
            type: "POST",
            url: "{{route('admin.quotegeneration.get_client_details')}}",
            dataType: 'json',
            data: {
                "client_id": org_id,
            },
            success: function(result) {
                $('#client_org_users').html(result.users);
                $('#id_to_address').html(result.address);
            },
        });
    });
    $("#client_org_users").change(function() {

        var company_name = $('#client_org').find(':selected').data('org_name');
        var name = $(this).find(':selected').data('name');
        var email = $(this).find(':selected').data('email');
        var mobile = $(this).find(':selected').data('mobile');


        if (company_name != '') {
            $('#comp_name').val(company_name);
        } else {
            $('#comp_name').val('');
        }
        if (name != '') {
            $('#fname').val(name);
        } else {
            $('#fname').val('');
        }
        if (email != '') {
            $('#email').val(email);
        } else {
            $('#email').val('');
        }
        if (mobile != '') {
            $('#mobile').val(mobile);
        } else {
            $('#mobile').val('');
        }
    });



    $("#terms").change(function() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        var id = $(this).val();
        $.ajax({
            type: 'post',
            url: "{{route('admin.quotegeneration.change_terms')}}",
            data: {
                "id": id,
            },
            success: function(result) {
                $('#id_terms_of_use').html(result);
            },
            error: function(res) {
                alert(res);
            }
        });
    });
    function selectaddress(addr_id) {
    var addr=$('#org_adrs').find(':selected').data('addr');
    if(addr.toLocaleLowerCase().indexOf('usa') != -1 || addr.indexOf('us') != -1){
      $("#gst option[value='no']").prop('selected', true).trigger('change');

      $("#currency").find("[data-currency='USD']").prop('selected', true).trigger('change');
    }else if(addr.toLocaleLowerCase().indexOf('india') != -1){
      $("#gst option[value='yes']").prop('selected', true).trigger('change');

      $("#currency").find("[data-currency='INR']").prop('selected', true).trigger('change');
    }
  }
</script>

@endsection