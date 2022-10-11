@extends('layouts.admin')
@section('content')
 <?php 
$form_url=route('admin.finance.clientinvoice');

/*$param='';

if(isset($_GET['org_id']) && $_GET['org_id'] != ''){
  $param='org_id='.$_GET['org_id'];
}


  if($param != ''){
    $param=$param.'&start_date='.$s_date.'&end_date='.$e_date;
  }else{
    $param='start_date='.$s_date.'&end_date='.$e_date;
  }
if($param != ''){
  $form_url=$form_url.'?'.$param;
}*/
?> 

<form action="{{$form_url}}" method="get">
<div class="form-row">
              <div class="form-group col-md-2">
          <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
            <label for="Currency" class="required">Currency:</label>
            <select name="currency" id="currency" class="form-control select2" required>
              @foreach($currency_list as $cl)
              <option value="{{$cl->id}}" {{ ($cl->id == $currency)? 'selected' : '' }}>{{$cl->currency_name .' - '. $cl->currency_code}}</option>
              @endforeach
            </select>
          </div>
        </div>
              <div class="form-group col-md-3">
          <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
            <label for="client" class="required">Clients:</label>
            <select name="client" id="client" class="form-control select2">
              <option value="">All</option>
              @foreach($clients_list as $sl)
              <option value="{{$sl->org_id}}" {{ ($sl->org_id == $client_sel)? 'selected' : '' }}>{{$sl->org_name}}</option>
              @endforeach
            </select>
          </div>
        </div>
              <div class="form-group col-md-2">
          <div class="form-group {{ $errors->has('') ? 'has-error' : '' }}">
            <label for="sales" class="required">Sales:</label>
            <select name="sales" id="sales" class="form-control select2">
              <option value="">All</option>
              @foreach($sales_list as $sl)
              <option value="{{$sl->id}}" {{ ($sl->id == $salse_sel)? 'selected' : '' }}>{{$sl->name}}</option>
              @endforeach
            </select>
          </div>
        </div>
              <div class="form-group col-md-2">
              <label for="">Start Date</label>
              <input type="date" class="form-control" name="start_date" max="<?php echo date("Y-m-d"); ?>" value="{{$s_date}}">
             </div>
            

            <div class=" form-group col-md-2">
             
              <label for="">End Date</label>
              <input type="date" class="form-control" name="end_date"  max="<?php echo date("Y-m-d"); ?>" value="{{$e_date}}">
              <!-- <input type="hidden" class="form-control" name="created_at"> -->

            </div>
          

          
               <div class="form-group col-md-1" style="margin-top: 24px;">
                 <input type="submit" class="btn btn-primary" value="Submit">
                </div>
                </div>
          </form>
          
    
<div class="card">
          <div class="card-header">
            <h3>Client Invoice
            <a href="{{ route("admin.finance.clientinvoice") }}" class="btn btn-xs btn-info float-right">Clear Search</a>
            </h3>
          </div>
          <div class="card-body" id="client_invoice_div">
            <div class="col-md-12">
              <h5>Booking Amount: <span class="float-right">{{checkcurrency($client_sum_of_pending,$currency)}}</span></h5>
              <h5>Received Amount: <span class="float-right">{{checkcurrency($client_sum_of_paid,$currency)}}</span></h5>
              <hr/>
              <h5>Pending Amount: <span class="float-right">{{checkcurrency($client_grand_total,$currency)}}</span></h5>
              <br/><br/>
            </div>
            <table class="table table-bordered table-striped table-hover datatable_table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Client</th>
                  <th>Salse</th>
                  <th>Narration</th>
                  <th>Invoice</th>
                  <th>Pending</th>
                  <th>Paid</th>
                </tr>
              </thead>
              <tbody>
                @foreach($client_invoice_history as $ih)
                <tr>
                  <td>{{ $ih->created_at }}</td>
                  <td>{{gettabledata('clientorganizations','org_name',['org_id'=>$ih->clientorg])}}
                  </td>
                  <td>{{ getusernamebyid($ih->translation_user_id) }}</td>
                  <td>{{ $ih->desc }}</td>
                  <td>{{ gettabledata('loc_invoice','invoice_no',['id'=>$ih->invoice_id]) }}</td>
                  <td class="text-right">{{ checkcurrency($ih->pending,$currency) }}</td>
                  <td class="text-right">{{ checkcurrency($ih->paid,$currency) }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="5">Total</th>
                  <th class="text-right">{{checkcurrency($client_sum_of_pending,$currency)}}</th>
                  <th class="text-right">{{checkcurrency($client_sum_of_paid,$currency)}}</th>
                </tr>
              </tfoot>
            </table>
          </div>
          <!-- <div class="card-footer">
            <div id="editor"></div>
            <button class="btn btn-info btn-sm" onclick="downloadpdf()" >PDF </button>
          </div> -->
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
 
  $( "#datepicker" ).datepicker({  maxDate: 0 });

</script>

@endsection