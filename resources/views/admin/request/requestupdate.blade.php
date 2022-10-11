@extends('layouts.admin')
@section('content')
<?php $user_role = Auth::user()->roles[0]->name; ?>
<?php
if($edit_request->quote_gen_id != ''){
  $quote_code=gettabledata('loc_translation_qoute_generation_master','quote_code',['translation_quote_id'=>$edit_request->quote_gen_id]);
}else{
  $quote_code = '';
}
  ?>
<div class="card">
  <div class="card-header">
    {{ trans('cruds.locrequest.title_singular') }} {{ trans('global.show') }}
    <span class="float-right">
    <?php if($edit_request->request_status !="new"){?>
      @if(checkpermission('request_invoice','add') && ($user_role=='orgadmin' || $user_role=='projectmanager' ||$user_role=='finance' ))
      <!-- <a href="#" class="btn btn-sm btn-info" onclick="show_client_invoice_popup('{{ $edit_request->reference_id }}')" >Raise Client Invoice</a> -->
      <a href="{{ route('admin.request.requestinvoice',['refid'=>$edit_request->reference_id]) }}" class="btn btn-sm btn-info">Upload Client Invoice</a>
      @endif
      @if(checkpermission('request_invoice','add') && ($user_role=='vendor' || $user_role=='orgadmin' || $user_role=='projectmanager' ||$user_role=='finance'))
      <a href="{{ route('admin.request.requestvendorinvoice',['refid'=>$edit_request->reference_id]) }}" class="btn btn-sm btn-info">Upload {{ getrolename('translator') }} Invoice</a>
      @endif
      <?php }?>
      @if(checkpermission('request_invoice','add') && ($user_role=='orgadmin' || $user_role=='projectmanager' ||$user_role=='finance' || $user_role=='sales'))
      <a href="{{ route('admin.request.requesttransaction',['refid'=>$edit_request->reference_id]) }}" class="btn btn-sm btn-info">View Transaction</a>
      @endif
    </span>
  </div>
  <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link invoice_link active" id="comments_data-tab" data-toggle="tab" href="#comments_data" role="tab" aria-controls="comments_data" aria-selected="false">Request Details</a>
  </li>
  <?php 
    if(($user_role != 'translator' && $user_role != 'vendor' && $user_role != 'proofreader' && $user_role != 'qualityanalyst')){
      if($quote_code != ''){
      ?>
  <li class="nav-item">
    <!-- <a class="nav-link invoice_link" id="quote_data-tab" data-toggle="tab" href="#quote_data" role="tab" aria-controls="quote_data" aria-selected="true">Quote</a> -->
    <a class="nav-link invoice_link" href="{{ route('admin.quotegeneration.editquote',$quote_code) }}" target="_blank">Quote</a>
  </li>
  <?php }}?>
  <?php if($edit_request->request_status !="new"){?>
  <li class="nav-item">
    <a class="nav-link invoice_link" href="{{ route('admin.request.assigntotranslator',['refid'=>$edit_request->reference_id]) }}">Assigned</a>
  </li>
  <?php }?>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="comments_data" role="tabpanel" aria-labelledby="comments_data-tab">

    <div class="row">    
      
      @if($arr_sales_quote_generation_details != '')
      <div class="col-md-6">
       <table class="table table-bordered table-striped table-hover">
          <tr>
            <td>{{ trans('cruds.quotegenerate.fields.QuoteID') }}</td>
            <td><b>{{ $arr_sales_quote_generation_details->quote_code ?? ''}}</b></td>
          </tr>
             
          <tr>

            <td>{{ trans('cruds.quotegenerate.fields.client_name') }}</td>
            <td><b> {{$fullname= $arr_sales_quote_generation_details->first_name ." ".$arr_sales_quote_generation_details->last_name ?? ''}}</b></td>
          </tr>
        
          <tr>
            <td>{{ trans('cruds.quotegenerate.fields.mobile') }}</td>
            <td><b>{{$arr_sales_quote_generation_details->mob_number}}</b></td>
          </tr>
          <tr>
            <td>{{ trans('cruds.quotegenerate.fields.status') }}</td>
            <td><b>{{ $arr_sales_quote_generation_details->translation_status }}</b></td>
          </tr>
          <tr>
            <td>Project Manager</td>
            <td><b>{{getusernamebyid($arr_sales_quote_generation_details->pm_id)}}</b></td>
          </tr>
          <tr>
            <td>{{ trans('cruds.quotegenerate.fields.quote_address') }}</td>
            <td><b>{{ str_replace('<br />','',$arr_sales_quote_generation_details->translation_quote_address )}}</b></td>
          </tr>
          
          <tr>
            <td>{{ trans('cruds.locrequest.fields.description') }}</td>
            <td><b>{{ $edit_request->brief_description }}</b></td>
          </tr>
          <!-- <tr>
            <td>{{ trans('cruds.locrequest.fields.delivery_date') }}</td>
            <td><b>{{ $edit_request->request_date }}</b></td>
          </tr> -->
          <!-- <tr>
            <td>{{ trans('cruds.locrequest.fields.special_instructions') }}</td>
            <td><b>{{ $edit_request->special_instructions }}</b></td>
          </tr> -->
          @if($user_role=='orgadmin' || $user_role=='projectmanager' ||$user_role=='finance' || $user_role=='sales')
          <tr>
            <td>Client Cost</td>
            <td><b class="bg-dark p-1">{{ (isset($arr_sales_quote_generation_details->grand_total) && $arr_sales_quote_generation_details->grand_total != '')? checkcurrency($arr_sales_quote_generation_details->grand_total,$arr_sales_quote_generation_details->translation_quote_currency) : ''}}</b></td>
          </tr>
          @endif
       <!--   <tr>
            <td>{{ trans('cruds.locrequest.fields.no_words') }}</td>
            <td><b>{{ $edit_request->no_words }}</b></td>
          </tr> -->
          <tr>
            @if($edit_request->source_type == 'File')
            <td>{{ trans('cruds.locrequest.fields.source_file') }}</td>
            <td><b><a href="{{ asset('storage/request/'.$edit_request->source_file_path)}}" download="">{{ $edit_request->source_file_path }}</a></b></td>
            @endif
            @if($edit_request->source_type == 'Text')
            <td>{{ trans('cruds.locrequest.fields.source_text') }}</td>
            <td>{{ $edit_request->source_text }}</td>
            @endif
          </tr>

        </table>
      </div>
      @if($user_role=='orgadmin' || $user_role=='projectmanager' ||$user_role=='finance' || $user_role=='sales')
      <div class="col-md-6">
    
        <table class="table table-bordered table-striped table-hover">
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Booking Amount</th>
            <th>Total</th>
          </tr>
          <?php $coun=1;$total=0;$gtotal=0; $sum_of_pending=$arr_sales_quote_generation_details->grand_total;?>
          @foreach($vendors_list as $vl)
          <tr>
            <td><?php  if($vl->v_id != ''){echo getusernamebyid($vl->v_id);}elseif($vl->tr_id != ''){echo getusernamebyid($vl->tr_id);}?></td>
            <td><?php  if($vl->v_id != ''){echo getusernamebyid($vl->v_id,'email');}elseif($vl->tr_id != ''){echo getusernamebyid($vl->tr_id,'email');}?></td>
            <td><?php  if($vl->v_id != ''){echo getusernamebyid($vl->v_id,'mobile');}elseif($vl->tr_id != ''){echo getusernamebyid($vl->tr_id,'mobile');}?></td>
            <td> 
              {{ checkcurrency($vl->v_total,$vl->currency_id) }}
            </td>
            <td> 
              <?php 
              //$p_total=(($sum_of_pending*$lcf->work_per)/100)-$lcf->v_total;
              if($vl->currency_id == $arr_sales_quote_generation_details->translation_quote_currency){
                $gtotal = $gtotal+$vl->v_total;
                echo checkcurrency($vl->v_total,$arr_sales_quote_generation_details->translation_quote_currency);
              }else{
                $v_con_total=convertcurrency($vl->v_total,$arr_sales_quote_generation_details->currency_cost,$vl->currency_cost);
                $gtotal=$gtotal+$v_con_total;
                echo checkcurrency($v_con_total,$arr_sales_quote_generation_details->translation_quote_currency);
              }?>
            </td>
          </tr>
          @endforeach
          <tr><td colspan="5"></td></tr>
          <tr>
            <td colspan="4"><b>Total</b> </td>
            <td><b>   {{ checkcurrency($gtotal,$arr_sales_quote_generation_details->translation_quote_currency) }}</b></td>
          </tr>
          <tr>
            <td colspan="4"><b>Booking Amount</b></td>
            <td><b>{{ checkcurrency($sum_of_pending,$arr_sales_quote_generation_details->translation_quote_currency) }}</b></td>
          </tr>
          <tr>
            <td colspan="4"><b>Profit Margin in ({{ number_format((float)((($sum_of_pending-$gtotal)/$sum_of_pending)*100), 2, '.', '') }}%)</b></td>
            <td><b>{{ checkcurrency(($sum_of_pending-$gtotal),$arr_sales_quote_generation_details->translation_quote_currency) }}</b></td>
          </tr>
        </table>
    
      </div>
              @endif

      @else
      
      <div class="col-md-6">
        <table class="table table-bordered table-striped table-hover">
        
          <tr>
            <td>{{ trans('cruds.locrequest.fields.request_id') }}</td>
            <td><b>{{ $edit_request->reference_id }}</b></td>
          </tr>
          <tr>
            <td>{{ trans('cruds.locrequest.fields.status') }}</td>
            <td><b>{{ showcrstatus($edit_request->request_status) }}</b></td>
          </tr>
          <tr>
            <td>{{ trans('cruds.locrequest.fields.cl_organization') }}</td>
            <td><b>{{ $client_org->org_name }}</b></td>
          </tr>
          
        
          <tr>
            <td>{{ trans('cruds.locrequest.fields.cl_a_mobile') }}</td>
            <td><b>{{ getusernamebyid( $edit_request->user_id,'mobile') ?? '' }}</b></td>
          </tr>
          <tr>
            <td>Client Name</td>
            <td><b>{{ getusernamebyid($edit_request->user_id) ?? ''}}</b></td>
          </tr>
          <tr>
            <td>{{ trans('cruds.locrequest.fields.cl_c_email') }}</td>
            <td><b>{{ getusernamebyid($edit_request->user_id,'email') ?? ''}}</b></td>
          </tr>

          <!-- <tr>
            <td>{{ trans('cruds.locrequest.fields.category') }}</td>
            <td><b>{{ ucwords($edit_request->document_type) }}</b></td>
          </tr> -->
          <tr>
            <td>{{ trans('cruds.locrequest.fields.description') }}</td>
            <td><b>{{ $edit_request->brief_description }}</b></td>
          </tr>
          <!-- <tr>
            <td>{{ trans('cruds.locrequest.fields.delivery_date') }}</td>
            <td><b>{{ $edit_request->request_date }}</b></td>
          </tr> -->
        
          
       <!--   <tr>
            <td>{{ trans('cruds.locrequest.fields.no_words') }}</td>
            <td><b>{{ $edit_request->no_words }}</b></td>
          </tr> -->
          <tr>
            @if($edit_request->source_type == 'File')
            <td>{{ trans('cruds.locrequest.fields.source_file') }}</td>
            <td><b><a href="{{ asset('storage/request/'.$edit_request->source_file_path)}}" download="">{{ $edit_request->source_file_path }}</a></b></td>
            @endif
            @if($edit_request->source_type == 'Text')
            <td>{{ trans('cruds.locrequest.fields.source_text') }}</td>
            <td>{{ $edit_request->source_text }}</td>
            @endif
          </tr>

        </table>
      </div>
      @endif

    </div>
  </div>
  <div class="tab-pane fade show" id="quote_data" role="tabpanel" aria-labelledby="quote_data-tab">
  <?php
  if($edit_request->quote_gen_id != ''){
  $quote_file=gettabledata('loc_translation_qoute_generation_master','quote_file',['translation_quote_id'=>$edit_request->quote_gen_id]);
  ?>
    <div class="embed-responsive embed-responsive-16by9">
      <iframe class="embed-responsive-item" src="{{ asset('storage/quotegeneration/'.$quote_file)}}" allowfullscreen></iframe>
    </div>
    <?php }?>
  </div>
  <div class="tab-pane fade show" id="assigned_data" role="tabpanel" aria-labelledby="assigned_data-tab">
      
    <?php if ($user_role == 'orgadmin' || $user_role == 'projectmanager') { ?>
      <div class="row">
        <?php
        $sl_tl = $loc_request->loc_reference_lang_select($edit_request->req_id, 'sl_tl');
        ?>
        <table class="table table-bordered table-striped table-hover">
          <tr>
            <th>Source-Target</th>
            <th>{{ getrolename('translator') }}/{{ getrolename('vendor') }}</th>
            <th>Quality Analist</th>
            <th>Proof Reader</th>
          </tr>
          <?php
          $i = 0;
          foreach ($sl_tl as $sl) {
          ?>
            <tr>
              <td>{{ $sl->source_lang_name }} - {{ $sl->target_lang_name}}</td>

              <td>
                <?php $tr_name= DB::table('users')->join('loc_target_file','loc_target_file.tr_id','users.id')->where(['tr_id'=>$sl->tr_id,'request_id'=>$sl->request_id])->first('users.name');
                 ?>
                @if(isset($tr_name->name))
                <ul>
                 <li><b>Name: </b>{{$tr_name->name}}</li>
                 
                 <li><b>Date: </b>{{$sl->tr_assigned_date}}</li>
                 <li><b>Status: </b>{{$sl->tr_status ?? 0}}% Completed</li>
                </ul>
                @endif
                @if(isset($sl->v_id) && $sl->v_id != '')
                <ul>
                 <li><b>Name: </b> {{getusernamebyid($sl->v_id)}}</li>
                 <li><b>Date: </b>{{$sl->v_assigned_date}}</li>
                 <li><b>Status: </b>{{$sl->v_status ?? 0}}% Completed</li>
                 <li><b>Work%: </b>{{$sl->work_per}}%</li>
                 <li><b>Amount: </b>{{$sl->amount ?? 0}}</li>
                 <li><a href="#" class="btn btn-sm btn-info" onclick="show_vendor_invoice_popup('{{ $edit_request->reference_id }}','{{ $sl->v_id }}')" >Raise {{ getrolename('vendor') }} Invoice</a></li>
                </ul>
                @endif
              </td>
              <td>
                <?php $qa_name= DB::table('users')->join('loc_target_file','loc_target_file.qa_id','users.id')->where(['qa_id'=>$sl->qa_id,'request_id'=>$sl->request_id])->first('users.name');
                 ?>
                 @if(isset($qa_name->name))
                <ul>
                 
                  <li><b>Name: </b>{{$qa_name->name}}</li>
                <li><b>Date: </b>{{$sl->qa_assigned_date}}</li>
                 <li><b>Status: </b>{{$sl->qa_status ?? 0}}% Completed</li>
                </ul>
                @endif
              </td>
              <td>
                <?php $pr_name= DB::table('users')->join('loc_target_file','loc_target_file.pr_id','users.id')->where(['pr_id'=>$sl->pr_id,'request_id'=>$sl->request_id])->first('users.name'); ?>
                @if(isset($pr_name->name))
                <ul>
                  <li><b>Name: </b>{{$pr_name->name}}</li>
                 <li><b>Date: </b>{{$sl->pr_assigned_date}}</li>
                 <li><b>Status: </b>{{$sl->pr_status ?? 0}}% Completed</li>
                </ul>
                @endif
              </td>
            </tr>
          <?php $i++;
          } ?>
        </table>
      </div>
    <?php } ?>
  </div>
</div>
  </div>
</div>

<div id="show_data_popup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="show_data_popup_header"></h3>
            </div>
            <div class="modal-body message_body">
              <div class="row">
                <div class="col-md-12 col-xs-12 col-sm-12">
                  <div id="show_data">
                           
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info" onclick="downloadpdf()" >Donwload </button>
            </div>
        </div>
    </div>
</div>
<div id="show_client_invoice_popup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="show_client_invoice_popup_header">Client Invoice</h3>
            </div>
            <div class="modal-body message_body">
              <div class="row">
                <div class="col-md-12 col-xs-12 col-sm-12">
                  <div id="show_data">
          <div class="form-group">
            <label for="comments" class="required">Pending Amount</label>
            <input type="text" readonly="" name="amount" id="disable_amount" class="form-control" value="0"/>
          </div>
          <div class="form-group">
            <label for="invoicing" class="required">Invoicing Amount</label>
            <input type="number" name="invoicing_amount" id="invoicing_amount" class="form-control" min="0.1" value="0"/>
          </div>
          <div class="form-group">
            <label for="comments">Comments</label>
            <textarea rows="4" cols="50" name="comments" id="comments" class="form-control" required></textarea>
          </div>
          <input type="hidden" name="total_invoice_amount" id="total_invoice_amount" value="0">
          <input class="btn btn-danger" id="client_invoice_submit" type="submit" value="{{ trans('global.submit') }}">     
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
                <button class="btn btn-info" onclick="downloadpdf()" >Donwload </button>
            </div> -->
        </div>
    </div>
</div>
<div id="show_vendor_invoice_popup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="show_vendor_invoice_popup_header">Client Invoice</h3>
            </div>
            <div class="modal-body message_body">
              <div class="row">
                <div class="col-md-12 col-xs-12 col-sm-12">
                  <div id="show_data">
          <div class="form-group">
            <label for="comments" class="required">Pending Amount</label>
            <input type="text" readonly="" name="amount" id="disable_vendor_amount" class="form-control" value="0"/>
          </div>
          <div class="form-group">
            <label for="invoicing" class="required">Invoicing Amount</label>
            <input type="number" name="invoicing_vendor_amount" id="invoicing_vendor_amount" class="form-control" min="0.1" value="0"/>
          </div>
          <div class="form-group">
            <label for="comments">Comments</label>
            <textarea rows="4" cols="50" name="comments" id="vendor_comments" class="form-control" required></textarea>
          </div>
          <input type="hidden" name="total_vendor_invoice_amount" id="total_vendor_invoice_amount" value="0">
          <input type="hidden" name="vendor_id" id="vendor_id">
          <input class="btn btn-danger" id="vendor_invoice_submit" type="submit" value="{{ trans('global.submit') }}">     
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="modal-footer">
                <button class="btn btn-info" onclick="downloadpdf()" >Donwload </button>
            </div> -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
<script type="text/javascript">
  $("#request_task").on('change', function(event) {
    var sel_task = $(this).val();
    //alert(sel_task);
    if (sel_task == "Translation") {
      $("#id_linguist").show();
      $("#id_reviewer").hide();

    } else if (sel_task == "Proofreading") {
      $("#id_linguist").hide();
      $("#id_reviewer").show();
    } else {
      $("#id_linguist").show();
      $("#id_reviewer").hide();
    }
  });
  
$("#client_invoice_submit").on('click', function(event) {
  var request_id='{{ $edit_request->reference_id }}';
  var req_id='{{ $edit_request->req_id }}';
  var total_invoice_amount=$('#total_invoice_amount').val();
  var invoicing_amount=$('#invoicing_amount').val();
  if(parseFloat(invoicing_amount) > 0 && parseFloat(invoicing_amount) <= parseFloat(total_invoice_amount)){
    var comments=$('#comments').val();
    $.ajax({
      url: "{{ route('admin.request.submitinvoice') }}",
      method: "POST",
      dataType: "json",
      data: {
        "req_id": req_id,
        "invoicing_amount": invoicing_amount,
        "invoice_type":'client',
        "comments":comments
      },
      headers: {
        'x-csrf-token': _token
      },
      success: function(response) {
        alert(response.message);
        if(response.status == 1){
          downloadFile(response.file);
          location.reload();
        }
      }
    });
  }else{
    alert('Invoicing amount is invalid');
  }
});
function downloadFile(response) {
  var link = document.createElement('a');
  link.href = response;
  link.download = response;
  link.click();
}
$("#vendor_invoice_submit").on('click', function(event) {
  var request_id='{{ $edit_request->reference_id }}';
  var req_id='{{ $edit_request->req_id }}';
  var total_invoice_amount=$('#total_vendor_invoice_amount').val();
  var invoicing_amount=$('#invoicing_vendor_amount').val();
  var vendor_id=$('#vendor_id').val();
  if(parseFloat(invoicing_amount) > 0 && parseFloat(invoicing_amount) <= parseFloat(total_invoice_amount)){
    var comments=$('#vendor_comments').val();
    $.ajax({
      url: "{{ route('admin.request.submitinvoice') }}",
      method: "POST",
      dataType: "json",
      data: {
        "req_id": req_id,
        "invoicing_amount": invoicing_amount,
        "invoice_type":'vendor',
        "vendor_id":vendor_id,
        "comments":comments
      },
      headers: {
        'x-csrf-token': _token
      },
      success: function(response) {
        alert(response.message);
        if(response.status == 1){
          location.reload();
        }
      }
    });
  }else{
    alert('Invoicing amount is invalid');
  }
});

function show_data_popup(id) {
  var message=$('#view_my_text_'+id).data('text');
  var header=$('#view_my_text_'+id).data('type');
  jQuery('#show_data_popup').modal('show', {backdrop: 'true'});
  jQuery('#show_data_popup #show_data_popup_header').html(header);
  jQuery('#show_data').html(message);
}

var doc = new jsPDF();
var specialElementHandlers = {
    '#editor': function (element, renderer) {
        return true;
    }
};
 function downloadpdf(){
  doc.fromHTML($('#show_data').html(), 15, 15, {
        'width': 700,


        // 'elementHandlers': specialElementHandlers
    });
    doc.save('sample_file.pdf');
 }
 function show_client_invoice_popup(id) {
  jQuery('#show_client_invoice_popup').modal('show', {backdrop: 'true'});
  $.ajax({
    url: "{{ route('admin.request.getclientinvoicedata') }}",
    method: "POST",
    dataType: "html",
    data: {
      "id": id,
    },
    headers: {
      'x-csrf-token': _token
    },
    success: function(response) {
      $('#total_invoice_amount').val(response);
      $('#disable_amount').val(response);
    }
  });
}
function show_vendor_invoice_popup(id,vendor_id) {
  jQuery('#show_vendor_invoice_popup').modal('show', {backdrop: 'true'});
  $.ajax({
    url: "{{ route('admin.request.getvendorinvoicedata') }}",
    method: "POST",
    dataType: "html",
    data: {
      "id": id,
      "user_id":vendor_id
    },
    headers: {
      'x-csrf-token': _token
    },
    success: function(response) {
      $('#total_vendor_invoice_amount').val(response);
      $('#disable_vendor_amount').val(response);
      $('#vendor_id').val(vendor_id);
    }
  });
}
</script>

@endsection