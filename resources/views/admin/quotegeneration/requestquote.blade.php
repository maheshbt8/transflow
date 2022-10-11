@extends('layouts.admin')
@section('content')
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
   .myclass 
{
    text-transform:capitalize;
}
.hide_price_div{
      display: none;
     }
/*     .select2 {
width:100%!important;
}*/
</style>
<meta name="csrf_token" content="{{csrf_token()}}">
<?php

use Aws\ECRPublic\ECRPublicClient;

echo get_user_org('org_id');
?>
<div class="card">
    <div class="card-header">
        Create Quote 
        <!-- {{ trans('cruds.quotegenerate.title_singular') }} {{ trans('global.list') }} -->
    </div>
    <div class="card-body">
        <form action="{{ route("admin.quotegeneration.store") }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate="">
            @csrf
            <div class="span9" id="error_on_header"></div>
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="Subject:" class="required">Subject: </label>
                        <!-- <textarea name="subject" id="id_subject" required class="form-control myclass" rows="5"></textarea> -->
                        <input type="text" name="subject" id="id_subject" required class="form-control myclass">
                        <div class="invalid-feedback" for="">Must Add The Subject</div>
                    </div>
                </div>
               
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="first_name" class="required">Company Name</label>
                        <input type="text" name="comp_name" id="comp_name" class="form-control" value="{{ old('name', isset($user) ? $user->name : ((isset($client_org) && $client_org != '')? $client_org->org_name : '')) }}" required >
                        <input type="hidden" name="client_org" id="client_id" class="form-control" value="{{$client_org->org_id}}" required readonly>
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
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label class="required">Contact Person</label>
                        <select name="" id="client_org_users" class="form-control select2">
                            <option value="">Select contact person</option>
                        </select>
                        <div class="invalid-feedback" for="">Please Select Client Organization</div>
                    </div>
                    <input type="hidden" name="" id="client_org" class="form-control" value="{{ old('name', isset($client_org->client_comp_name) ? $client_org->client_comp_name : '') }}" required>
                </div>

                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="To Address" class="required">Client Address: </label>
                        <!-- <textarea name="to_address" id="id_to_address" class="form-control myclass" rows="1" required></textarea> -->
                        <select name="to_address" id="id_to_address" class="form-control select2" required>
                            <option value="">Select Client Organization</option>
                            @foreach($client_org_addr as $adr)
                            <option value="{{$adr->id}}"><?php echo $adr->address; ?></option>
                            @endforeach
                        </select>
                        </select>
                        <div class="invalid-feedback" for="">Must Add The Address</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="first_name" class="required">First Name</label>
                        <input type="text" name="first_name" id="fname" class="form-control myclass" value="{{ old('name', isset($user) ? $user->name : ((isset($client_users) && $client_users != '')? $client_users->name : '')) }}" required>
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
                        <input type="text" name="last_name" id="lname" class="form-control" required>
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
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="Email Id" class="required">Email Id</label>
                        <input type="email" class="form-control" placeholder="Email id" id="email" name="email" value="{{ old('name', isset($user) ? $user->name : ((isset($client_users) && $client_users != '')? $client_users->email : '')) }}" required>
                        <div class="invalid-feedback" for="">Must enter your Email Id</div>
                    </div>
                </div>

                <div class="col-md-3">

                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="number" class="required">Mobile Number</label>
                        <input type="number" id="mobile" class="form-control" placeholder="Mobile Number" name="mob_number" pattern="[1-9]{1}[0-9]{9}" value="{{ old('name', isset($user) ? $user->mobile : ((isset($client_users) && $client_users != '')? $client_users->mobile : '')) }}" required>
                        <div class="invalid-feedback" for="">Must enter your 10 Digit Mobile Number</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
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
                        <input type="date" name="current_date" value="{{date('Y-m-d')}}" class="form-control" id="id_cuurent_date" required selected>

                        <div class="invalid-feedback" for="">Must select the Date</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="Currency" class="required">Currency:</label>
                        <select name="currency" id="currency" class="form-control select2" required>
                            @foreach($currency_list as $cl)
                            <option value="{{$cl->id}}">{{$cl->currency_name}}</option>
                            @endforeach
                            <!-- <option value="indain Rupee">indain Rupee</option>
                        <option disabled value="Dollar">Dollar</option>
                        <option disabled value="SDollar">Singapore Dollar</option> -->
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="GST" class="required">GST:</label>
                        <select name="gst_type" id="gst" class="form-control select2" required>
                            <option value="yes">YES</option>
                            <option value="no">NO</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">

                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="GST" class="required">Quote Type:</label>
                        <select name="quote_type" id="gst" class="form-control select2" required>
                            <option value="quote">Quote</option>
                            <option value="sample">Sample</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label for="number" class="required">Project Manager Cost</label>
                        <input type="number" class="form-control" value="0" min="0" max="100" name="pm_cost" required>
                        <div class="invalid-feedback" for="">inter the pm cost</div>
                    </div>
                </div>
                <div class="col-md-3">

                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label class="required">Organization Addresss</label>
                        <select name="org_adrs" id="org_adrs" class="form-control select2" required>
                            <option value="">Select Client Organization</option>
                            <?php if (count($org_addr) > 0) { ?>
                                @foreach($org_addr as $adr)
                                <option value="{{$adr->id}}"><?php echo $adr->address; ?></option>
                                @endforeach
                        </select>
                        <div class="invalid-feedback" for="">Please Select Client Organization</div>
                    <?php } else { ?>
                        <input type="hidden" id="org_adrs" value="">
                    <?php } ?>
                    </div>
                </div>
            </div>
            <!---row -end --->

            <div class="row">
                <?php
                if (get_user_org('name') == "administrator") {
                ?>
                    <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
                        <label class="required">organization</label>
                        <select name="org" id="organization" class="form-control select2" required>
                            <option value="">Select Organization</option>
                            @foreach($org_addr as $adr)
                            <option value="{{$id}}">{{$org}}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" for="">Please Select Organization</div>
                    </div>
                <?php
                } else {
                ?>
                    <input type="hidden" value="<?php echo get_user_org('org', 'org_id'); ?>" id="org_id" name="org" />
                <?php } ?>
            </div>

    </div>

    <div class="row" id="id_more_div">
        <?php
        $i = 0;
        ?>

        <?php $i = 0;
        $t = $f = 1;
        ?>

        <?php
        $get_source_lang = $getst_lang->request_lang_select($request_data->req_id);


        ?>
        @foreach($get_source_lang as $get_source)
        <div class="col-md-6" id="<?php if ($i != 0) {
                                        echo 'page_dyanmic_fields_' . $i;
                                    } ?>">
            <div class="card">
                <div class="card-header">
                    Section {{$i+1}}
                    <div class="card-tools">

                    </div>
                </div>
                <div class="card-body">

                    <?php
                    $target_lang = DB::table('loc_request_assigned')->where(['request_id' => $request_data->req_id, 'loc_source_id' => $get_source->id])->get('target_language')->toArray();
                    $get_target_lang = array_column((array)$target_lang, 'target_language');
                    // echo "<pre>";
                    // print_r($get_source_lang);die;

                    ?>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4 form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
                                <label for="source_language" class="required">Source Language</label>
                                <input type="hidden" name="source_language[]" id="source_language" value="{{ $get_source->lang_id }}">
                                <ul>
                                    <li li style="margin-left: -24px;">{{ $get_source->lang_name }}</li>
                                </ul>
                                @if($errors->has('source_language'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('source_language') }}
                                </em>
                                @endif
                                <div class="invalid-feedback" for="">Must Select your Source Language</div>
                            </div>
                            <div class="form-group col-md-4 {{ $errors->has('destination_language') ? 'has-error' : '' }}">
                                <label for="destination_language" class="required">Target Language</label>
                                @foreach($loc_languages as $key => $lang)

                                <?php if (in_array($lang->lang_id, $get_target_lang)) { ?>

                                    <input type="hidden" name="destination_language_{{$i}}[]" id="destination_language" value="{{ $lang->lang_id }}">
                                    <ul>
                                        <li style="margin-left: -24px;"><?php ?>{{ $lang->lang_name }}</li>
                                    </ul>
                                <?php }
                                //$i++;
                                ?>
                                @endforeach
                                @if($errors->has('destination_language'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('destination_language') }}
                                </em>
                                @endif
                                <div class="invalid-feedback" for="">Must Select your Target Language</div>
                            </div>
                            <div class="form-group col-md-4 {{ $errors->has('service_type') ? 'has-error' : '' }}">
                                <label class="required">Type of Service</label>
                                <select name="service_type_{{$i}}[]" id="service_type_{{$i}}" data-index="0" class="form-control select2 service_type" onchange="getservice_type(this.value,{{$i}})" required>
                                    <option value="">Select Service Type</option>
                                 
                                    @foreach($loc_services as $service)
                                   
                                    <option value="{{$service->id}}" data-type="{{ $service->type }}">{{$service->service_type}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" for="">Must select one of the Service</div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6 hide_price_div" id="description_div_{{$i}}">
                                    <div class="form-group">
                                        <label for="description">Description: </label>
                                        <textarea name="description_{{$i}}" id="description_{{$i}}" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group hide_price_div" id="word_price_div_{{$i}}">
                                        <label>Word Count & Cost Per word: *</label>
                                        <div class="form-row">
                                            <div class="col-md-12"><input type="number" min="10" max="1000000000" id="words_count_{{$i}}" name="words_count_{{$i}}" class="form-control" onkeyup="getpercost('word',{{$i}},this.value)" placeholder="word count"><br></div>
                                            <div class=col-md-12><input type="text" id="cost_per_word_{{$i}}" name="cost_per_word_{{$i}}" class="form-control" placeholder="cost for word"><br>
                                            </div>
                                            <div class="col-md-12"><input type="text" name="word_fixed_cost_{{$i}}" class="form-control" id="word_fixed_cost_{{$i}}" placeholder="fixed cost">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group hide_price_div" id="page_price_div_{{$i}}">
                                        <label>Page Count & Cost Per Page: </label>
                                        <div class="form-row">
                                            <div class="col-md-12"><input type="number" min="10" max="1000000000" id="page_count_{{$i}}" name="page_count_{{$i}}" class="form-control" placeholder="page count" onkeyup="getpercost('page',{{$i}},this.value)"><br></div>
                                            <div class=col-md-12><input type="text" name="cost_per_page_{{$i}}" id="cost_per_page_{{$i}}" class="form-control" placeholder="cost for page"><br>
                                            </div>
                                            <div class="col-md-12"> <input type="text" name="page_fixed_cost_{{$i}}" id="page_fixed_cost_{{$i}}" class="form-control" placeholder="fixed cost">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group hide_price_div" id="minute_price_div_{{$i}}">
                                        <label>Minutes Cost & Cost Per Minute: *</label>
                                        <div class="form-row">
                                            <div class="col-md-12"> <input type="number" min="100" max="1000000000" id="minute_words_count_{{$i}}" name="minute_words_count_{{$i}}" class="form-control" placeholder="Minute count" onkeyup="getpercost('minute',{{$i}},this.value)"><br></div>
                                            <div class="col-md-12"> <input type="text" name="minute_cost_per_word_{{$i}}" id="minute_cost_per_word_{{$i}}" class="form-control" placeholder="cost for Minute"><br></div>
                                            <div class="col-md-12"> <input type="text" name="minute_fixed_cost_{{$i}}" id="minute_fixed_cost_{{$i}}" class="form-control" placeholder="fixed cost"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- section card -body-->
                </div>

                <!-- section card end -->
            </div>
            <!-- section col-6 end -->

            <!-- section row end -->
        </div>
        <?php $i++; ?>
        @endforeach
    </div>
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
        <div class="col-md-6">
            <input type="hidden" name="request_id" value="{{$request_data->req_id}}">
            <button class="btn btn-success" id="id_btn_getquote" name="Generate_Quote" value="<?php echo 'Generate Quote'; ?>" type="submit">Generate quote </button>
            <button class="btn btn-success" id="btn_reset" type="submit">Cancel </button>
        </div>
    </div>

    </form>

</div>



@endsection
@section('scripts')
@parent
<script>
    function getservice_type(service_type, index) {
         // alert(index);
        var type = $('#service_type_' + index).find(':selected').data('type');
        if (type == 'word') {
            $('#word_price_div_' + index).show();
            $('#page_price_div_' + index).hide();
            $('#minute_price_div_' + index).hide();
            $('#description_div_' + index).show();
        } else if (type == 'page') {
            $('#word_price_div_' + index).hide();
            $('#page_price_div_' + index).show();
            $('#minute_price_div_' + index).hide();
            $('#description_div_' + index).show();
        } else if (type == 'minute' || type == 'slab_minute') {
            $('#word_price_div_' + index).hide();
            $('#page_price_div_' + index).hide();
            $('#minute_price_div_' + index).show();
            $('#description_div_' + index).show();
        } else {
            $('#word_price_div_' + index).hide();
            $('#page_price_div_' + index).hide();
            $('#minute_price_div_' + index).hide();
            $('#description_div_' + index).hide();
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
       var comp_name=$('#comp_name').val();
       var org_adrs=$('#org_adrs').val();
       var str = '10';

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
       if(mobile != "" && mobile.length > str  ){
           toastr.error("Please enter 10 digit Mobile Number");
           $("#mobile").focus().addClass('border_error');
           return false; 
       }
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
       //if (source_language == '') {
       toastr.error("Please select source language");
       $(this).focus().addClass('border_error');
       dataValid = false;
       return false;
       //}
   } else { //alert(index);
      var destination_language_id = $("#destination_language_" + index).val();
      //alert(destination_language_id);
       //var id_destination_language = $('input[name="destination_language_0\\[\\]"] :selected').map((_, e) => e.value).get();
       var id_destination_language = 0;
      // $("[name^=destination_language_" + index + "] :selected").each(function() {alert('hi');
       /*$('[name="destination_language_' + index + '\\[\\]"]').each(function() {
           id_destination_language = 1;
       });*/
       if(destination_language_id != ''){
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
           /*$("[name^=service_type_" + index + "] :selected").each(function() {
               service_type = 1;
           });*/
           if(service_type_id != ''){
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
           

           var type=$('#service_type_'+index).find(':selected').data('type');
                  if(type == 'word'){ 
                    
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
                           }else{
                      if (id_fixed_word_value_r != "" && !$.isNumeric(id_fixed_word_value_r)) {
                           toastr.error("Please enter numbers instead of characters");
                           $("#word_fixed_cost_" + index).focus().addClass('border_error');
                           dataValid = false;
                           return false;
                       }
                     }
                       }
                     
                  }else if(type == 'page'){
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
                           }else{
                       if (id_fixed_page_value_r != "" && !$.isNumeric(id_fixed_page_value_r)) {
                           toastr.error("Please enter numbers instead of characters");
                           $("#page_fixed_cost_" + index).focus().addClass('border_error');
                           dataValid = false;
                           return false;
                       }
                     }
                       }
                     
                  }else if(type == 'minute' || type == 'slab_minute'){ 
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
                           }else{
                       if (id_fixed_minute_value_r != "" && !$.isNumeric(id_fixed_minute_value_r)) {
                           toastr.error("Please enter numbers instead of characters");
                           $("#minute_fixed_cost_" + index).focus().addClass('border_error');
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


        $('#error_on_header').html('');
        pdf_getquote = 1;
    });



    function validate_dynamic_field_page(id) {
        var dyanmic_row_id = id;
        var div = document.getElementById("page_dyanmic_fields_" + dyanmic_row_id);
        div.parentNode.removeChild(div);
    }

    var addrow_page = 0;
    $(document.body).on('click', "#id_more_div", function(e) {
        addrow_page++;
        $.ajax({
            type: "post",
            dataType: "html",
            url: "{{route('admin.quotegeneration.request_dynamic_fields')}}",
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


    $("#client_org").change(function () {
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
        dataType:'json',
        data: {
            "client_id": org_id,
        },
        success: function (result) {
            $('#client_org_users').html(result.users);
            $('#id_to_address').html(result.address);
        },
 });
});
getuserdata('{{ $request_data->client_org_id }}');
function getuserdata(org_id ){
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var company_name = $('#comp_name').val();
    $('#comp_name').val(company_name);
   $.ajax({
        type: "POST",
        url: "{{route('admin.quotegeneration.get_client_details')}}",
        dataType:'json',
        data: {
            "client_id": org_id,
        },
        success: function (result) {
            $('#client_org_users').html(result.users);
            $('#id_to_address').html(result.address);
        },
    });
}

    $("#client_org_users").change(function () {

  // var company_name = $('#client_org').find(':selected').data('org_name');
   var name= $(this).find(':selected').data('name');
   var email= $(this).find(':selected').data('email');
   var mobile= $(this).find(':selected').data('mobile');
 
  
  
   if(name != ''){
       $('#fname').val(name);
   }else{
       $('#fname').val('');
   }
   if(email != ''){
       $('#email').val(email);
   }else{
       $('#email').val('');
   }
   if(mobile != ''){
       $('#mobile').val(mobile);
   }else{
       $('#mobile').val('');
   }
});

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
                        if(parseInt(total) > 0){
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

    function selectRefresh() {
        $('.main .select2').select2({
            tags: true,
            placeholder: "Select an Option"
        });
    }
</script>
@endsection