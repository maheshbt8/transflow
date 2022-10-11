@extends('layouts.admin')
@section('content')
<?php 
$form_url=route('admin.finance.vendorinvoice');
/*$param='';
if(isset($_GET['vendor']) && $_GET['vendor'] != ''){
  $param='vendor='.$_GET['vendor'];
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
    <div class="form-group col-md-4">
      <label for="">Start Date</label>
      @if(isset($_GET['vendor']) && $_GET['vendor'] != '')
      <input type="hidden" name="vendor" value="{{$_GET['vendor']}}">
      @endif
      <input type="date" class="form-control" name="start_date" value="{{$s_date}}"  max="<?php echo date("Y-m-d"); ?>">
    </div>
    <div class=" form-group col-md-4">
      <label for="">End Date</label>
      <input type="date" class="form-control" name="end_date" max="<?php echo date("Y-m-d"); ?>" value="{{$e_date}}">
    </div>          
    <div class="form-group col-md-4" style="margin-top: 24px;">
      <input type="submit" class="btn btn-primary" value="Submit">
    </div>
  </div>
</form>
<div class="card">
    <div class="card-header">
      <h3>{{ getrolename('vendor') }} Transactions</h3>
    </div>
    <div class="card-body" id="vendor_invoice_div">
      <div class="col-md-12">
        <h5>Billing Amount: <span class="float-right">{{number_format($vendor_sum_of_pending,2)}}</span></h5>
        <h5>Paid Amount: <span class="float-right">{{number_format($vendor_sum_of_paid,2)}}</span></h5>
        <hr/>
        <h5>Pending Amount: <span class="float-right">{{number_format($vendor_grand_total,2)}}</span></h5>
        <br/><br/>
      </div>
      <table class="table table-bordered table-striped table-hover datatable_table">
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
            <td><a href="{{ route('admin.finance.vendorinvoice','vendor='.$vih->user_id.'&start_date='.$s_date.'&end_date='.$e_date) }}">{{ getusernamebyid($vih->user_id) }}</a></td>
            <td>{{ $vih->desc }}</td>
            <td>{{ $vih->Invoice_id }}</td>
            <td class="text-right">{{number_format($vih->pending,2)}}</td>
            <td class="text-right">{{number_format($vih->paid,2)}}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th colspan="4">Total</th>
            <th class="text-right">{{number_format($vendor_sum_of_pending,2)}}</th>
            <th class="text-right">{{number_format($vendor_sum_of_paid,2)}}</th>
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
  doc.fromHTML($('#vendor_invoice_div').html(), 0, 0, {
        'elementHandlers': specialElementHandlers
    });
    doc.save('{{$edit_request->reference_id}}.pdf');
 } 
 
</script>
  <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> -->

<script>
$(function() {
  $('input[name="created_at"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>
@endsection