@extends('layouts.admin')
@section('content')
<?php $user_role = Auth::user()->roles[0]->name;
?>
<div class="card">
  <div class="card-header">
    {{ trans('cruds.locrequest.title_singular') }} {{ trans('global.show') }}
    <span class="float-right">
    </span>
  </div>
  <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link invoice_link active" id="quote_data-tab" data-toggle="tab" href="#quote_data" role="tab" aria-controls="quote_data" aria-selected="true">Invoice</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Purchase Order</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="quote_data" role="tabpanel" aria-labelledby="quote_data-tab">
    <div class="embed-responsive embed-responsive-16by9">
      <iframe class="embed-responsive-item" src="<?php  echo env('AWS_CDN_URL') . '/invoices/'.$invoice_details->invoice_type.'/'.date('Ymd',strtotime($invoice_details->created_at)).'/'.$invoice_details->invoice_file_path ;?>"  allowfullscreen></iframe>
    </div>
  </div>
  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    <div class="embed-responsive embed-responsive-16by9">
      <iframe class="embed-responsive-item" src="<?php  echo env('AWS_CDN_URL') . '/po_order/'.$invoice_details->invoice_type.'/'.date('Ymd',strtotime($invoice_details->created_at)).'/'.$invoice_details->po_file_path ;?>"  allowfullscreen></iframe>
    </div>
  </div>
</div>
  </div>
</div>
@endsection

@section('scripts')
@parent
<script type="text/javascript">

</script>

@endsection