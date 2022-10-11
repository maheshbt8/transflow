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

  .myclass {
    text-transform: capitalize;
  }

  .hide_price_div {
    display: none;
  }

  /*     .select2 {
width:100%!important;
}*/
</style>
<meta name="csrf_token" content="{{csrf_token()}}">
<?php
echo get_user_org('org_id');
?>
<div class="card">
  <div class="card-header">
    <!-- {{ trans('cruds.quotegenerate.title_singular') }} {{ trans('global.list') }} -->
    Create New Quote
  </div>
  <div class="card-body">
    <form action="{{ route("admin.quotegeneration.store") }}" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate="">
      @csrf
      <!-- <div class="span9" id="error_on_header"></div> -->
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
            <label class="required">Client Organization</label>
            <select name="client_org" id="client_org" class="form-control select2" required>
              <option value="">Select Client Organization</option>
              @foreach($clientorganizations as $id => $org)
              <option value="{{$org->org_id}}" data-org_name="{{$org->org_name}}">{{$org->org_name}}</option>
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
            </select>
            <div class="invalid-feedback" for="">Please Select Client Organization</div>
          </div>
          <input type="hidden" name="comp_name" id="comp_name" class="form-control" value="{{ old('name', isset($user)) ? $user->comp_name : '' }}" required>
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
              <option value="">Select Client Address</option>
            </select>
            <div class="invalid-feedback" for="">Must Add The Address</div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
            <label for="first_name" class="required">First Name</label>
            <input type="text" name="first_name" id="fname" class="form-control myclass" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
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
            <input type="text" name="last_name" id="lname" class="form-control " required>
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
                @foreach($org_list as $org_data)
                <option value="{{$org_data->org_id}}">{{$org_data->org_name}}</option>
                @endforeach
              </select>
              <div class="invalid-feedback" for="">Please Select Organization</div>
            </div>
          </div>
        <?php
        } else {
        ?>
          <input type="hidden" value="<?php echo get_user_org('org', 'org_id'); ?>" id="org_id" name="org" />
        <?php } ?>
        <div class="col-md-3">
          <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
            <label for="Email Id" class="required">Email Id</label>
            <input type="email" class="form-control" placeholder="Email id" id="email" name="email" value="{{ old('name', isset($user) ? $user->email : ((isset($org) && $org != '')? ($org->email ?? '') : '')) }}" required>
            <div class="invalid-feedback" for="">Must enter your Email Id</div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
            <label for="number" class="required">Mobile Number</label>
            <input type="text" id="mobile" class="form-control" placeholder="Mobile Number" name="mob_number" pattern="^\+[1-9]{1}[0-9]{3,14}$" required>
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
            <input type="date" name="current_date" value="{{date('Y-m-d')}}" class="form-control" id="id_cuurent_date" required selected>

            <div class="invalid-feedback" for="">Must select the Date</div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
            <label for="Currency" class="required">Currency:</label>
            <select name="currency" id="currency" class="form-control select2" required>
              @foreach($currency_list as $cl)
              <option value="{{$cl->id}}">{{$cl->currency_name .' - '. $cl->currency_code}}</option>
              @endforeach
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
              @foreach($org_addr as $adr)
              <option value="{{$adr->id}}"><?php echo $adr->address; ?></option>
              @endforeach
            </select>
            <div class="invalid-feedback" for="">Please Select Client Organization</div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
            <label for="GST" class="required">Estimated Project Duration:</label>
            <select name="weeks" id="weeks" class="form-control select2" required>
            
            <?php
    for ($i=1; $i<=10; $i++)
    {
        ?>
            <option value="<?php echo $i;?>"><?php echo ($i == 1)? $i.' Week' : $i.' Weeks' ;?></option>
        <?php
    }
?>
            </select>
          </div>
        </div>

        <div class="col-md-3">
          <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
            <label for="GST" class="required">Repeat section:</label>
            <select name="repeat_section" id="repeat_section" class="form-control select2" required>
              <option value="no">NO</option>
              <option value="yes">YES</option>
            </select>
          </div>
        </div>
        <div class="col-md-3" id="repeat_target_div" style="display: none;">
          <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
            <label for="GST" class="required">Target Languages:</label>
            <select name="repeat_target" id="repeat_target" class="form-control select2" multiple="">
              <option value="" disabled="">Select Target Languages</option>
              @foreach($loc_languages as $key => $lang)
              <option value="{{ $lang->lang_id }}">{{ $lang->lang_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      
      <div class="col-md-3" id="">
          <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
            <label for="Terms" class="required">Select Template:</label>
            <select name="name" id="terms" class="form-control">
              <option value="">Select  Template</option>
              @foreach($terms as $key => $term)
              <option value="{{ $term->id }}" data-terms_name="{{$term->template_name}}">{{ $term->template_name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
  </div>
      <div class="">
        <div class="row" id="add_rows_request">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                Section 1
                <div class="card-tools">

                  <button type="button" class="btn btn-tool bg-success" id="id_more_div">
                    <i class="fas fa-plus"></i>
                  </button>

                </div>

              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4 form-group {{ $errors->has('source_language') ? 'has-error' : '' }}">
                    <label for="source_language" class="required">Source Language</label>
                    <select name="source_language[]" id="source_language_0" class="source-lang-class form-control  select2" required>
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
                  <div class="form-group col-md-4 {{ $errors->has('destination_language') ? 'has-error' : '' }}">
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
                  <div class="form-group col-md-4 {{ $errors->has('service_type') ? 'has-error' : '' }}">
                    <label class="required">Type of Service</label>
                    <select name="service_type_0[]" id="service_type_0" class="form-control select2 service_type" onchange="getservice_type(this.value,0)" required>
                      <option value="" data-type="">Select Service Type</option>
                      @foreach($loc_services as $service)
                      <option value="{{$service->id}}" data-type="{{ $service->type }}">{{$service->service_type}}</option>
                      @endforeach
                    </select>
                    <div class="invalid-feedback" for="">Must select one of the Service</div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6 hide_price_div" id="description_div_0">
                    <div class="form-group">
                      <label for="description">Description: </label>
                      <textarea name="description_0" id="description_0" class="form-control" rows="5"></textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group hide_price_div" id="word_price_div_0">
                      <label>Word Count & Cost Per word: *</label>
                      <div class="form-row">
                        <div class="col-md-12"><input type="number" min="1" max="1000000000" id="words_count_0" name="words_count_0" class="form-control" onkeyup="getpercost('word',0,this.value)" placeholder="word count"><br></div>
                        <div class=col-md-12><input type="text" id="cost_per_word_0" name="cost_per_word_0" class="form-control float-number" placeholder="cost for word"><br>
                        </div>
                        <div class="col-md-12"><input type="text" name="word_fixed_cost_0" class="form-control float-number" id="word_fixed_cost_0" placeholder="fixed cost">
                        </div>
                      </div>
                    </div>
                    <div class="form-group hide_price_div" id="page_price_div_0">
                      <label>Page Count & Cost Per Page: </label>
                      <div class="form-row">
                        <div class="col-md-12"><input type="number" min="1" max="1000000000" id="page_count_0" name="page_count_0" class="form-control" placeholder="page count" onkeyup="getpercost('page',0,this.value)"><br></div>
                        <div class=col-md-12><input type="text" name="cost_per_page_0" id="cost_per_page_0" class="form-control float-number" placeholder="cost for page"><br>
                        </div>
                        <div class="col-md-12"> <input type="text" name="page_fixed_cost_0" id="page_fixed_cost_0" class="form-control float-number" placeholder="fixed cost">
                        </div>
                      </div>
                    </div>
                    <div class="form-group hide_price_div" id="minute_price_div_0">
                      <label id="minute_label_0">Minutes Count & Cost Per Minute: *</label>
                      <div class="form-row">
                        <div class="col-md-12"> <input type="number" min="1" max="1000000000" id="minute_words_count_0" name="minute_words_count_0" class="form-control" placeholder="Minute count" onkeyup="getpercost('minute',0,this.value)"><br></div>
                        <div class="col-md-12"> <input type="text" name="minute_cost_per_word_0" id="minute_cost_per_word_0" class="form-control float-number" placeholder="cost for Minute"><br></div>
                        <div class="col-md-12"> <input type="text" name="minute_fixed_cost_0" id="minute_fixed_cost_0" class="form-control float-number" placeholder="fixed cost"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <lable>Terms of Use: </lable>
              <textarea style="height:100px;" name="terms_of_use" class="form-control" id="id_terms_of_use" cols="30" rows="10">
1. Before we start the project, we must be provided with requisite Purchase Order/Work Order duly signed by an Authorized person of your organization.
2. In case of any corrections to be carried out you must mark all the corrections once at a time so that the entire document will be corrected accordingly on free of charge basis. In case of repeated corrections required by you with your own ideas and creative thinking, then the same will be charged on per word or per minute basis.
                    </textarea>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <button class="btn btn-success" id="id_btn_getquote" name="Generate_Quote" value="<?php echo 'Generate Quote'; ?>" type="submit">Generate quote </button>
            <button class="btn btn-success" id="btn_reset" type="reset">Cancel </button>
          </div>
        </div>
        <div class="col-md-8"></div>
      </div>
    </form>
  </div>
</div>
@endsection
@section('scripts')
@parent
<script>
  function getservice_type(service_type, index) {

    var type = $('#service_type_' + index).find(':selected').data('type');
    $('.float-number').keypress(function(event) {
      if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
      }
    });
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
      if (type == 'minute') {
        $('#minute_label_' + index).html('Minutes Count & Cost Per Minute: *');
      } else {
        $('#minute_label_' + index).html('Minutes Count & Cost for slab: *');
      }
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
    /*if(mobile != "" && mobile.length > str  ){
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

  //   jQuery(document).ready(function() {
  //     $('.float-number').keypress(function(event) {
  //         if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
  //             event.preventDefault();
  //         }
  //     });
  // });

  function validate_dynamic_field_page(id) {
    var dyanmic_row_id = id;
    var div = document.getElementById("page_dyanmic_fields_" + dyanmic_row_id);
    div.parentNode.removeChild(div);
  }

  var addrow_page = 1;

  $(document.body).on('change', "#repeat_section", function(e) {
   
    var repeat_section = $('#repeat_section').val();
   
    if (repeat_section == 'yes') {
      $('#repeat_target_div').show();
    } else {
      $('#repeat_target_div').hide();
    }
  });
  $(document.body).on('click', "#id_more_div", function(e) {
    var repeat_section = $('#repeat_section').val();
   
    var repeat_target = $('#repeat_target').val();
   
    // var addrow_page = 0;
    // addrow_page++;
    //alert(repeat_section);

    if (repeat_section == 'yes') {
      var word_count = cost_per_word = word_fixed_cost = page_count = cost_per_page = fixed_page_count = minute_count = minute_per_cost = minute_fixed_cost = '';
      var source_lang = document.getElementById("source_language_0").value;
      var destination_lang = document.getElementById("destination_language_0").value;
      //var service_type = $('#service_type_0').val();
      // if(description != ''){
      // var description = $("#description_0").val();
      // }else{
      //   var description = $("#description_0").val();

      // }
      var description = $("#description_0").val();
      var service_type_id = $(".service_type").val();
      var type = $('#service_type_0').find(':selected').data('type');
      if (type == 'word' || type == '1') {

        var word_count = $('#words_count_0').val();

        var cost_per_word = $('#cost_per_word_0').val();

        var word_fixed_cost = $('#word_fixed_cost_0').val();

      } else if (type == 'page' || type == '2') {
        var page_count = $('#page_count_0').val();
        //alert(page_count);
        var cost_per_page = $('#cost_per_page_0').val();
        var fixed_page_count = $('#page_fixed_cost_0').val();

      } else if (type == 'minute' || type == 'slab_minute' || type == '3') {
        var minute_count = $('#minute_words_count_0').val();

        var minute_per_cost = $('#minute_cost_per_word_0').val();

        var minute_fixed_cost = $('#minute_fixed_cost_0').val();

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
        "repeat_section": 'yes',
        "_token": "{{csrf_token()}}"
      };

      // if(description != ''){
      // var description = $("#description_0").val();
      // }else{
      //   var v2 = $(source_lang,destination_lang).val();
      
      //   data_list.description = v2;

      // }

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
    //alert(data_list);

    //alert(data_list);

    //alert(addrow_page);
    /*$.ajax({
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
    });*/
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
  // 

    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    var id =  $(this).val();
   
    $.ajax({

      type:'post',
       url: "{{route('admin.quotegeneration.change_terms')}}",
      data: {
        "id": id,
      },
      success: function(result) {
        $('#id_terms_of_use').html(result);

       
      },
      error:function(res){
        alert(res);
       
      }
    

      });
  });
 
</script>
@endsection