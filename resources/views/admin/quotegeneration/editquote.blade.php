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
    <a class="nav-link invoice_link active" id="quote_data-tab" data-toggle="tab" href="#quote_data" role="tab" aria-controls="quote_data" aria-selected="true">Quote</a>
    </li>
   <li class="nav-item">
    <a class="nav-link invoice_link" id="history-tab" data-toggle="tab" href="#history" role="tab" aria-controls="vendor" aria-selected="false">Quote History</a>
  </li>
  </ul>
  <div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show " id="comments_data" role="tabpanel" aria-labelledby="comments_data-tab">

  </div>
  <div class="tab-pane fade show active" id="quote_data" role="tabpanel" aria-labelledby="quote_data-tab">
    <div class="embed-responsive embed-responsive-16by9">
      <iframe class="embed-responsive-item" src="<?php echo env('AWS_CDN_URL') . '/quotegeneration/'.date('Ymd',strtotime($quote_details->created_at)).'/'.$quote_details->quote_file ;?>"  allowfullscreen></iframe>
    </div>
  </div>
  <div class="tab-pane fade show" id="history" role="tabpanel" aria-labelledby="history-tab">
        <div id="accordion">
        
                 @foreach($quote_history as $history)
                  <div class="card card-primary">
                    <div class="card-header">
                      <p class="card-title w-100">
                        <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapse_{{$history['id']}}" aria-expanded="false">
                          {{ date('M d,Y h:i A',strtotime($history['created_at'])) }}
                        </a>
                      </p>
                    </div>
                    <div id="collapse_{{$history['id']}}" class="collapse" data-parent="#accordion" style="">
                      <div class="card-body">
                      <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="<?php echo env('AWS_CDN_URL') . '/quotegeneration/quotegeneration_history/'.date('Ymd',strtotime($history['created_at'])).'/'.$history['file_name'] ;?>"  allowfullscreen></iframe>
                      </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
        </div>
               
                  
</div>

 
  
</div>


@endsection

@section('scripts')
@parent
<script type="text/javascript">

</script>

@endsection