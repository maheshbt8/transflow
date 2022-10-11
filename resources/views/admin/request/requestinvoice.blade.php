@extends('layouts.admin')
@section('content')
<div class="card">
	<?php $user_role = Auth::user()->roles[0]->name;
	$gst_quote = gettabledata('loc_translation_qoute_generation_master', 'translation_quote_gst', ['translation_quote_id' => $loc_get->quote_gen_id]);
	?>
	<div class="card-header">
	{{ trans('cruds.locrequest.fields.client_invoice') }}
	</div>

	<div class="card-body" >
	
		<div class="d-flex justify-content">
			<form action="{{route("admin.request.submitinvoice")}}"  id="dp" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
				@csrf
				<div class="row">
					<div class="col-md-6">
						<div class="form-group {{ $errors->has('invoice_no') ? 'has-error' : '' }}">
							<label for="invoice_name" class="required">Invoice Number</label>
							<input type="text" name="invoice_no" id="invoice_no" class="form-control" required placeholder="Invoice Number" value="{{old('invoice_no', isset($client_invoice_list) ? $client_invoice_list->invoice_no : '') }}">
							@if($errors->has('invoice_no'))
							<em class="invalid-feedback">
								{{ $errors->first('invoice_no') }}
							</em>
							@endif
						</div>

						<div class="form-group {{ $errors->has('invoice_no') ? 'has-error' : '' }}">
							<label for="invoice_name">Purchase  Order  Number</label>
							<input type="text" name="po_no" id="po_no" class="form-control" placeholder="PO Number" value="{{old('po_no', isset($client_invoice_list) ? $client_invoice_list->po_no : '') }}">
							
						</div>
						<div class="form-group {{ $errors->has('invoice_date') ? 'has-error' : '' }}">
							<label for="invoice_date" class="required">Invoice Date</label>
							<input type="date" name="invoice_date" id="invoice_date" class="form-control" required placeholder="Invoice Number" value="{{date('Y-m-d')}}">
							@if($errors->has('invoice_date'))
							<em class="invalid-feedback">
								{{ $errors->first('invoice_date') }}
							</em>
							@endif
						</div>
						<div class="form-group {{ $errors->has('gst') ? 'has-error' : '' }}">
							<label for="gst" class="required">GST</label>
							<select name="gst" id="gst" class="form-control" required onchange="checkgst(this.value)">
								<option value="igst" {{($gst_quote == 'yes')? 'selected' : ''}}>IGST</option>
								<option value="both">CGST & SGST</option>
								<option value="no_gst" {{($gst_quote == 'no')? 'selected' : ''}}>NO GST</option>
							</select>
							@if($errors->has('gst'))
							<em class="invalid-feedback">
								{{ $errors->first('gst') }}
							</em>
							@endif
						</div>
						</div>
					<div class="col-md-6">

                        <div class="form-group {{ $errors->has('invoice_date') ? 'has-error' : '' }}">
							<label for="payment_amount" class="required">Amount</label>
							<input type="number" name="payment_amount" id="payment_amount" class="form-control" required placeholder="Enter Amount" value="{{old('payment_amount', isset($client_invoice_list) ? $client_invoice_list->payment_amount : '') }}">
							@if($errors->has('payment_amount'))
							<em class="invalid-feedback">
								{{ $errors->first('payment_amount') }}
							</em>
							@endif
						</div>
                        <div class="form-group {{ $errors->has('invoice_date') ? 'has-error' : '' }}">
							<label for="total_payment_amount" class="required">Total Amount</label>
							<input type="number" name="total_payment_amount" id="total_payment_amount" class="form-control" required placeholder="Enter Total Amount" value="{{old('total_payment_amount', isset($client_invoice_list) ? $client_invoice_list->total_payment_amount : '') }}">
							@if($errors->has('total_payment_amount'))
							<em class="invalid-feedback">
								{{ $errors->first('total_payment_amount') }}
							</em>
							@endif
						</div>
						<div class="form-group {{ $errors->has('invoice_date') ? 'has-error' : '' }}">
							<label for="payment" class="required">Upload Invoice</label>
							<input type="file" name="upload_invoice" id="upload_invoice" class="form-control" required  value="">
							@if($errors->has('upload_invoice'))
							<em class="invalid-feedback">
								{{ $errors->first('upload_invoice') }}
							</em>
							@endif
						</div>
						<div class="form-group {{ $errors->has('invoice_date') ? 'has-error' : '' }}">
							<label for="payment" class="required">Upload Purchase Invoice</label>
							<input type="file" name="uploads_purches" id="upload_purches" class="form-control"   value="">
							@if($errors->has('upload_purches'))
							<em class="invalid-feedback">
								{{ $errors->first('upload_purches') }}
							</em>
							@endif
						</div>
						<input type="hidden" name="req_id" value="{{$loc_get->req_id}}">
						<input type="hidden" id="invoicing_amount1" name="invoice_amount">
						<input type="hidden" id="net_amount1" name="net_amount">
						<input type="hidden" id="invoice_types" name="invoice_type" value="client">
				




					</div>
				</div>
				<!-- Source Language block -->
				<!-- <div class="row">
					<div class="col-md-6">&nbsp;</div>
					<div class="col-md-6"> -->
					<!-- <input type="button" required="" value="Preview" class="btn btn-info" > -->
					<?php if($unbilled_amount >0){?>
						<input class="btn btn-danger" id="translation_submit" type="submit" value="{{ trans('global.submit') }}">
					<?php }?>
					<!-- </div>
				</div> -->
			</form>
			</div>
  
		</div>
	</div>
</div>







<!-- <section class="vh-100 bg-image"
  style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">
          <div class="card" style="border-radius: 15px;">
            <div class="card-body p-5">
              <h2 class="text-uppercase text-center mb-2">Create Client Invoice</h2>

              <form>

                <div class="form-outline mb-4">
                  <input type="text" id="form3Example1cg" class="form-control form-control-lg" />
                  <label class="form-label" for="form3Example1cg">Your Name</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="email" id="form3Example3cg" class="form-control form-control-lg" />
                  <label class="form-label" for="form3Example3cg">Your Email</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="password" id="form3Example4cg" class="form-control form-control-lg" />
                  <label class="form-label" for="form3Example4cg">Password</label>
                </div>

                <div class="form-outline mb-4">
                  <input type="password" id="form3Example4cdg" class="form-control form-control-lg" />
                  <label class="form-label" for="form3Example4cdg">Repeat your password</label>
                </div>

                <div class="form-check d-flex justify-content-center mb-5">
                  <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3cg" />
                  <label class="form-check-label" for="form2Example3g">
                    I agree all statements in <a href="#!" class="text-body"><u>Terms of service</u></a>
                  </label>
                </div>

                <div class="d-flex justify-content-center">
                  <button type="button"
                    class="btn btn-success btn-block btn-lg gradient-custom-4 text-body">Register</button>
                </div>

                <p class="text-center text-muted mt-5 mb-0">Have already an account? <a href="#!"
                    class="fw-bold text-body"><u>Login here</u></a></p>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section> -->
@endsection
@section('scripts')
@parent

@endsection