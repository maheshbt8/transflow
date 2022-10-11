@extends('layouts.admin')
@section('content')
<?php $user_role = Auth::user()->roles[0]->name; ?>
<div class="card">
  <div class="card-header">
    {{ trans('cruds.locrequest.title_singular') }} Invoice
    <span class="float-right">
      <a href="{{ route('admin.request.requestupdate',['refid'=>$edit_request->reference_id]) }}" class="btn btn-sm btn-info" target="_blank">{{ $edit_request->reference_id }}</a>
    </span>
  </div>
  <div class="card-body">
    <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link invoice_link active" id="client-tab" data-toggle="tab" href="#client" role="tab" aria-controls="client" aria-selected="true">Client</a>
  </li>
  <li class="nav-item">
    <a class="nav-link invoice_link" id="vendor-tab" data-toggle="tab" href="#vendor" role="tab" aria-controls="vendor" aria-selected="false">{{ getrolename('vendor') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link invoice_link" id="profit-tab" data-toggle="tab" href="#profit" role="tab" aria-controls="profit" aria-selected="false">Profit Margin</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="client" role="tabpanel" aria-labelledby="client-tab">
      <div class="card">
          <div class="card-header">
            <h3>Client Invoice</h3>
          </div>
          <div class="card-body" id="client_invoice_div">
            <div class="col-md-12">
              <h5>Booking Amount: <span class="float-right">{{ checkcurrency($sum_of_pending,$quote_data->translation_quote_currency) }}</span></h5>
              <h5>Received Amount: <span class="float-right">{{ checkcurrency($sum_of_paid,$quote_data->translation_quote_currency) }}</span></h5>
              <hr/>
              <h5>Pending Amount: <span class="float-right">{{ checkcurrency($grand_total,$quote_data->translation_quote_currency) }}</span></h5>
              <br/><br/>
            </div>
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Narration</th>
                  <th>Invoice</th>
                  <th>Pending</th>
                  <th>Received</th>
                </tr>
              </thead>
              <tbody>
                @foreach($invoice_history as $ih)
                <tr>
                  <td>{{ $ih->created_at }}</td>
                  <td>{{ $ih->desc }}</td>
                  <td>{{ $ih->invoice_id? gettabledata('loc_invoice','invoice_no',['id'=>$ih->invoice_id]) : '' }}</td>
                  <td class="text-right">{{ checkcurrency($ih->pending,$quote_data->translation_quote_currency) }}</td>
                  <td class="text-right">{{ checkcurrency($ih->paid,$quote_data->translation_quote_currency) }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="3">Total</th>
                  <th class="text-right">{{ checkcurrency($sum_of_pending,$quote_data->translation_quote_currency) }}</th>
                  <th class="text-right">{{ checkcurrency($sum_of_paid,$quote_data->translation_quote_currency) }}</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="card-footer">
            <div id="editor"></div>
            <!-- <button class="btn btn-info btn-sm" onclick="downloadpdf()" >PDF </button> -->
          </div>
      </div>
    
      <div class="card">
          <div class="card-header">
            <h3>Client Invoice History</h3>
          </div>
          <div class="card-body" id="client_invoice_div">
            <div class="col-md-12">
              <h5>Booking Amount: <span class="float-right">{{ checkcurrency($sum_of_pending,$quote_data->translation_quote_currency) }}</span></h5>
              <h5>Billed Amount: <span class="float-right">{{ checkcurrency($client_billed_amount,$quote_data->translation_quote_currency) }}</span></h5>
              <hr/>
              <h5>UnBilled Amount: <span class="float-right">{{ checkcurrency($client_unbilled_amount,$quote_data->translation_quote_currency) }}</span></h5>
              <br/><br/>
            </div>
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Invoice</th>
                  <th>Invoicing</th>
                  <th>GST</th>
                  <th>Total</th>
                  <th>Received</th>
                  <th>Pending</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($client_invoice_list as $cil)
                <tr>
                  <td>{{ $cil->invoice_date }}</td>
                  <td><a href="{{ route('admin.request.viewinvoice',$cil->invoice_no) }}"> {{ $cil->invoice_no }}</a></td>
                  <td class="text-right">{{ checkcurrency($cil->invoicing_amount,$quote_data->translation_quote_currency) }}</td>
                  <td class="text-right"><?php if($cil->gst_type == 'igst'){$gst_type='IGST';}elseif($cil->gst_type == 'both'){$gst_type='CGST & SGST';}else{$gst_type='No GST';}?>{{ $gst_type }}</td>
                  <td class="text-right">{{ checkcurrency($cil->invoicing_total,$quote_data->translation_quote_currency) }}</td>                  
                  <td>{{ checkcurrency($cil->invoice_paid,$quote_data->translation_quote_currency) }}</td>
                  <td>{{ checkcurrency(($cil->invoicing_total-$cil->invoice_paid),$quote_data->translation_quote_currency) }}</td>
                  <td>{{ $cil->invoice_status? ucwords(str_replace('_',' ',$cil->invoice_status)) : 'Pending' }}</td>
                  <td>
                    <?php
                    if($cil->invoice_status != 'full_paid'){
                    ?>
                    <a href="#" class="btn btn-sm btn-info" onclick="show_invoice_popup('{{ $cil->id }}')" >Update</a>
                    <?php }?>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
  </div>
  <div class="tab-pane fade" id="vendor" role="tabpanel" aria-labelledby="vendor-tab">
    <div class="card">
          <div class="card-header">
            <h3>{{ getrolename('vendor') }} Invoice</h3>
          </div>
          <div class="card-body" id="client_invoice_div">
            <div class="col-md-12">
              <h5>Billing Amount: <span class="float-right">{{ checkcurrency($vendor_sum_of_pending,$quote_data->translation_quote_currency) }}</span></h5>
              <h5>Paid Amount: <span class="float-right">{{ checkcurrency($vendor_sum_of_paid,$quote_data->translation_quote_currency) }}</span></h5>
              <hr/>
              <h5>Pending Amount: <span class="float-right">{{ checkcurrency($vendor_grand_total,$quote_data->translation_quote_currency)}}</span></h5>
              <br/><br/>
            </div>
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>{{ getrolename('vendor') }}</th>
                  <th>Narration</th>
                  <th>Invoice</th>
                  <th>Pending</th>
                  <th>Paid</th>
                </tr>
              </thead>
              <tbody>
                @foreach($vendor_invoice_history as $vih)
             
                <tr>
                  <td>{{ $vih->created_at }}</td>
                  <td>{{ getusernamebyid($vih->user_id) }}</td>
                  <td>{{ $vih->desc }}</td>
                  <td>{{ $vih->Invoice_id }}</td>
                  <td class="text-right">{{ checkcurrency($vih->pending,$quote_data->translation_quote_currency) }}</td>
                  <td class="text-right">{{ checkcurrency($vih->paid,$quote_data->translation_quote_currency) }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="4">Total</th>
                  <th class="text-right">{{ checkcurrency($vendor_sum_of_pending,$quote_data->translation_quote_currency) }}</th>
                  <th class="text-right">{{ checkcurrency($vendor_sum_of_paid,$quote_data->translation_quote_currency) }}</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="card-footer">
            <div id="editor"></div>
           <!-- <button class="btn btn-info btn-sm" onclick="downloadpdf()" >PDF </button> -->
          </div>
        </div>
    <div class="card">
          <div class="card-header">
            <h3>{{ getrolename('vendor') }} Invoice</h3>
          </div>
          <div class="card-body" id="client_invoice_div">
            <div class="col-md-12">
              <h5>Booked Amount: <span class="float-right">{{ checkcurrency($vendor_sum_of_pending,$quote_data->translation_quote_currency) }}</span></h5>
              <input type="hidden" name="booking_amnt" value="{{number_format($vendor_sum_of_pending,2)}}">
              <h5>Billed Amount: <span class="float-right">{{ checkcurrency($vendor_billed_amount,$quote_data->translation_quote_currency) }}</span></h5>
              <input type="hidden" name="recieved_amnt" value="{{number_format($vendor_billed_amount,2)}}">
              <hr/>
              <h5>UnBilled Amount: <span class="float-right">{{ checkcurrency($vendor_unbilled_amount,$quote_data->translation_quote_currency) }}</span></h5>
              <br/><br/>
            </div>
            <table class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                <th>Date</th>
                  <th>Invoice</th>
                  <th>{{ getrolename('vendor') }}</th>
                  <th>Invoicing Amount</th>
                  <th>GST</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($vendor_invoice_list as $vil)
           
                <tr>
                  <td>{{ $vil->created_at }}</td>
                  <td>{{ $vil->invoice_no }}</td>
                  <td>{{ getusernamebyid($vil->vendor_id) }}</td>
                  <td class="text-right">{{ checkcurrency($vil->invoicing_amount,$quote_data->translation_quote_currency) }}</td>
                  <td class="text-right"><?php if($vil->gst_type == 'igst'){$gst_type='IGST';}elseif($vil->gst_type == 'both'){$gst_type='CGST & SGST';}else{$gst_type='No GST';}?>{{ $gst_type }}</td>
                  <td class="text-right">{{  checkcurrency($vil->invoicing_total,$quote_data->translation_quote_currency) }}</td>
                  <td>{{ $vil->invoice_status? ucwords(str_replace('_',' ',$vil->invoice_status)) : 'Pending' }}</td>
                  <td>
                    <?php
                    if($vil->invoice_status != 'full_paid'){
                    ?>
                    <a href="#" class="btn btn-sm btn-info" onclick="show_invoice_popup('{{ $vil->id }}')" >Update</a>
                  <?php }?>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
  </div>
  <div class="tab-pane fade" id="profit" role="tabpanel" aria-labelledby="profit-tab">
    <?php $i = 0;
                         $t = $f = 1;
                        ?>
                        <?php 
                        $get_source_lang=$getst_lang->quote_lang_select($edit_request->quote_gen_id);
                        ?>
    <table class=" table table-bordered table-striped table-hover">
                          <thead>
                            <tr>
                              <th>S.No.</th>
                              <th>Description</th>
                              <th>Files</th>
                              <th>{{ getrolename('vendor') }}</th>
                             <!--  <th>Work %</th> -->
                              <th>Booking Amount</th>
                              <th>Total</th>
                            </tr>
                          </thead>
                          <tbody>
                        <?php $coun=1;$total=0;$gtotal=0;?>
                        @foreach($get_source_lang as $get_source)
                        <?php
                        $target_lang = DB::table('loc_request_assigned')->where(['quote_id' => $edit_request->quote_gen_id,'loc_source_id'=>$get_source->id])->get()->toArray();
                       $loc_services_data=$loc_services::join('loc_service','loc_quote_service.service_type', '=', 'loc_service.id')->where(['quote_id'=>$edit_request->quote_gen_id,'loc_quote_service.loc_source_id'=>$get_source->id])->get()->toArray();
                       $get_loc_services_data = array_column((array)$loc_services_data, 'service_type');
                       $get_service_data=implode(',', $get_loc_services_data);

                       $loc_lang_files = DB::table('loc_request_files')->where(['request_id' => $edit_request->req_id,'source_language'=>$get_source->id])->get();
                        ?>
                        <?php 
                        foreach ($target_lang as $gtl) {
                          $sql_q=`(v_id != "" OR tr_id != "")`;
                          $lcfcc=DB::table('loc_target_file')->where(['request_id' => $edit_request->req_id,'req_lang_id'=>$gtl->id])->where($sql_q)->count();
                          if($lcfcc > 0){
                          ?>
                          <tr>
                                <td>{{$i+1}}</td>
                                <td colspan="5"><?php echo $get_service_data.' for '.$get_source->lang_name.' to '.gettabledata('loc_languages','lang_name',['lang_id'=>$gtl->target_language])?></td>
                            </tr>
                            <?php 
                            $j=0;
                           
                            foreach ($loc_lang_files as $llf) {
                                $sql_q=`(v_id != "" OR tr_id != "")`;
                                $lcf=DB::table('loc_target_file')->where(['request_id' => $edit_request->req_id,'req_lang_id'=>$gtl->id,'req_file_id'=>$llf->id])->where($sql_q)->first();
                                if($lcf && ($lcf->v_id != '' || $lcf->tr_id != '')){
                                  $created_at1=$llf->created_at;
                                  $sfile_date=date('Ymd',strtotime($created_at1));
                                ?>
                               <tr>
                                  <td>{{$i+1}}.{{$j+1}}</td>
                                  <td></td>
                                  <td id="file_data" value="{{$llf->id}}">Source-File: &nbsp;<?php echo $llf->original_file?><span>&nbsp; <a href="<?php echo env('AWS_CDN_URL') . '/request/source/'.$sfile_date.'/'.$llf->file_name ;?>" download><i class="fa fa-download"></i></a></span><br><?php if(isset($lcf->target_file) && $lcf->target_file != ''){?>Target-File: &nbsp;<?php echo $lcf->target_file?><span>&nbsp; <a href="{{url('storage/request/translation-file')}}/{{$lcf->target_file}} " download><i class="fa fa-download"></i></a></span><?php }?></td>
                                  <td> 
                                    {{ getusernamebyid($lcf->v_id) ?? getusernamebyid($lcf->tr_id) }}
                                  </td>
                                  <!-- <td> 
                                    {{$lcf->work_per}} %
                                  </td> -->
                                  <td> 
                                    {{ checkcurrency($lcf->v_total,$lcf->currency_id) }}
                                  </td>
                                  <td> 
                                    <?php 
                                    //$p_total=(($sum_of_pending*$lcf->work_per)/100)-$lcf->v_total;
                                    if($lcf->currency_id == $quote_data->translation_quote_currency){
                                      $gtotal = $gtotal+$lcf->v_total;
                                    echo checkcurrency($lcf->v_total,$quote_data->translation_quote_currency);
                                  }else{
                                      $v_con_total=convertcurrency($lcf->v_total,$quote_data->currency_cost,$lcf->currency_cost);
                                      $gtotal=$gtotal+$v_con_total;
                                    
                                      echo checkcurrency($v_con_total,$quote_data->translation_quote_currency);
                                    }?>
                                  </td>
                              </tr>
                        <?php }$j++;$i++; }?>
                        
                        <!-- <tr><td colspan="6"></td></tr> -->
                        <?php $i++; }}?>
                       
                       
                        @endforeach
                       
                          <tr><td colspan="6"></td></tr>
                          <tr>
                            <td colspan="5"><b>Total</b> </td>
                            <td><b>   {{ checkcurrency($gtotal,$quote_data->translation_quote_currency) }}</b></td>
                          </tr>
                          <tr>
                            <td colspan="5"><b>Booking Amount</b></td>
                            <td><b>{{ checkcurrency($sum_of_pending,$quote_data->translation_quote_currency) }}</b></td>
                          </tr>
                          
                          <tr>
                            <td colspan="5"><b>Profit ({{ number_format((float)((($sum_of_pending-$gtotal)/$sum_of_pending)*100), 2, '.', '') }}%)</b></td>
                            <td><b>{{ checkcurrency(($sum_of_pending-$gtotal),$quote_data->translation_quote_currency) }}</b></td>
                          </tr>
                        </tbody>
                      </table>
  </div>
</div>
  </div>
</div>
<div id="show_invoice_popup" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="show_invoice_popup_header">Client Invoice</h3>
            </div>
            <div class="modal-body message_body">
              <div class="row">
                <div class="col-md-12 col-xs-12 col-sm-12">
                  <div id="show_data">
          <div class="form-group">
            <label for="comments" class="required">Billed Amount</label>
            <input type="text" readonly="" name="amount" id="disable_amount_main" class="form-control" value="0"/>
          </div>
          <div class="form-group">
            <label for="comments" class="required">Pending Amount</label>
            <input type="text" readonly="" name="amount" id="disable_amount" class="form-control" value="0"/>
          </div>
          <div class="form-group">
            <label for="invoicing" class="required">Paid Amount</label>
            <input type="number" name="invoicing_amount" id="invoicing_amount" class="form-control" min="0.1" value="0"/>
          </div>
          <!-- <div class="form-group">
            <label for="invoicing" class="required">Invoice Status</label>
            <select name="invoice_status" id="invoice_status" class="form-control">
            </select>
          </div> -->
          <div class="form-group">
            <label for="comments">Comments</label>
            <textarea rows="4" cols="50" name="comments" id="comments" class="form-control" required></textarea>
          </div>
          <input type="hidden" name="total_invoice_amount" id="total_invoice_amount" value="0">
          <input type="hidden" name="invoice_id" id="invoice_id">
          <input class="btn btn-danger" id="invoice_submit" type="submit" value="{{ trans('global.submit') }}">     
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
var doc = new jsPDF({orientation: 'landscape',});
var specialElementHandlers = {
    '#editor': function (element, renderer) {
        return true;
    }
};
 function downloadpdf(){
  doc.fromHTML($('#client_invoice_div').html(), 0, 0, {
        'elementHandlers': specialElementHandlers
    });
    doc.save('{{$edit_request->reference_id}}.pdf');
 }

  function show_invoice_popup(id) {
  jQuery('#show_invoice_popup').modal('show', {backdrop: 'true'});
  $.ajax({
    url: "{{ route('admin.request.getinvoicedata') }}",
    method: "POST",
    dataType: "json",
    data: {
      "id": id,
    },
    headers: {
      'x-csrf-token': _token
    },
    success: function(response) {
      if(response.status == 1){
        $('#total_invoice_amount').val(response.amount);
        $('#disable_amount_main').val(response.main_amount);
        $('#disable_amount').val(response.amount);
        $('#invoice_id').val(id);
        //$('#invoice_status').html(response.options);
      }else{
        toastr.error("Something went wrong!");
      }
    }
  });
}
$("#invoice_submit").on('click', function(event) {
  var request_id='{{ $edit_request->reference_id }}';
  var req_id='{{ $edit_request->req_id }}';
  var total_invoice_amount=$('#total_invoice_amount').val();
  var invoicing_amount=$('#invoicing_amount').val();
  var invoice_id=$('#invoice_id').val();
  

  //var invoice_status=$('#invoice_status').val();
  if(parseFloat(invoicing_amount) > 0 && parseFloat(invoicing_amount) <= parseFloat(total_invoice_amount)){
    var comments=$('#comments').val();
    $.ajax({
      url: "{{ route('admin.request.updateinvoicestatus') }}",
      method: "POST",
      dataType: "json",
      data: {
        "invoice_id": invoice_id,
        "invoicing_amount": invoicing_amount,
        "comments":comments,
      },
      headers: {
        'x-csrf-token': _token
      },
      success: function(response) {
        if(response.status == 1){
          toastr.success(response.msg);
          setTimeout(function() {
            location.reload();
          }, 3000);
        }else{
          toastr.error(response.msg);
        }
      }
    });
  }else{
    toastr.error('Invoicing amount is invalid');
  }
});
</script>
@endsection