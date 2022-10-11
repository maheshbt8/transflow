@extends('layouts.admin')
@section('content')
<div class="card">
    <?php $user_role = Auth::user()->roles[0]->name; 
    //$vendor= Auth::user()->id;
    $gst_quote=gettabledata('loc_translation_qoute_generation_master','translation_quote_gst',['translation_quote_id'=>$loc_get->quote_gen_id]);
    ?>
    <div class="card-header">
	{{ trans('cruds.locrequest.fields.vendor_invoice') }}
    </div>

    <div class="card-body">
        <div class="d-flex justify-content-between">
        	<form action="{{route("admin.request.submitinvoice")}}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
                @csrf
                <div class="row">
                <div class="col-md-6">
                    @if($user_role=='orgadmin' || $user_role=='projectmanager' ||$user_role=='finance' || $user_role=='sales')
                        <div class="form-group {{ $errors->has('invoice_no') ? 'has-error' : '' }}">
                            <label for="vendor_id" class="required">Linguist</label>
                            <select name="vendor_id" id="vendor" class="form-control"  required>
                                <option value="">Select Linguist</option>
                                @foreach($vendors_list as $vendor)
                                <option value="{{ $vendor->v_id ?? $vendor->tr_id }}" {{(isset($vendor_id) && ($vendor_id == ($vendor->v_id ?? $vendor->tr_id)))? 'selected' : ''}} >{{getusernamebyid($vendor->v_id ?? $vendor->tr_id)}}</option>
								@endforeach
                            </select>
                            @if($errors->has('vendor_id'))
                            <em class="invalid-feedback">
                                {{ $errors->first('vendor_id') }}
                            </em>
                            @endif
                        </div>
                        @else
                        <input type="hidden" value="{{$vendor}}" name="vendor_id">
                        @endif
                        <div class="form-group {{ $errors->has('invoice_no') ? 'has-error' : '' }}">
                            <label for="invoice_name" class="required">Invoice Number</label>
                            <input type="text" name="invoice_no" id="invoice_no" class="form-control"  required placeholder="Invoice Number" value="{{old('invoice_no', isset($vendor_invoice_list) ? $vendor_invoice_list->invoice_no : '') }}">  
                            @if($errors->has('invoice_no'))
                            <em class="invalid-feedback">
                                {{ $errors->first('invoice_no') }}
                            </em>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('invoice_date') ? 'has-error' : '' }}">
                            <label for="invoice_date" class="required">Invoice Date</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control"  required placeholder="Invoice Number" value="{{date('Y-m-d')}}">
                            @if($errors->has('invoice_date'))
                            <em class="invalid-feedback">
                                {{ $errors->first('invoice_date') }}
                            </em>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('gst') ? 'has-error' : '' }}">
                            <label for="gst" class="required">GST</label>
                            <select name="gst" id="gst" class="form-control"  required onchange="checkgst(this.value)">
                            	<option value="igst" {{($gst_quote == 'yes')? 'selected' : ''}} >IGST</option>
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
							<input type="number" name="payment_amount" id="payment_amount" class="form-control" required placeholder="Enter Amount" value="{{old('payment_amount', isset($vendor_invoice_list) ? $vendor_invoice_list->payment_amount : '') }}"> 
							@if($errors->has('payment_amount'))
							<em class="invalid-feedback">
								{{ $errors->first('payment_amount') }}
							</em>
							@endif
						</div>
                        <div class="form-group {{ $errors->has('invoice_date') ? 'has-error' : '' }}">
							<label for="total_payment_amount" class="required">Total Amount</label>
							<input type="number" name="total_payment_amount" id="total_payment_amount" class="form-control" required placeholder="Enter Total Amount"value="{{old('total_payment_amount', isset($vendor_invoice_list) ? $vendor_invoice_list->total_payment_amount : '') }}"> 
							@if($errors->has('total_payment_amount'))
							<em class="invalid-feedback">
								{{ $errors->first('total_payment_amount') }}
							</em>
							@endif
						</div>
						<div class="form-group {{ $errors->has('invoice_date') ? 'has-error' : '' }}">
							<label for="payment" class="required">Upload Invoice</label>
							<input type="file" name="upload_invoice" id="upload_invoice" class="form-control" required  value="">
							@if($errors->has('invoice_amount'))
							<em class="invalid-feedback">
								{{ $errors->first('invoice_amount') }}
							</em>
							@endif
						</div>

                        <input type="hidden" name="req_id" value="{{$loc_get->req_id}}">
						<input type="hidden" id="invoicing_amount1" name="invoice_amount">
                        <input type="hidden" id="net_amount1" name="net_amount">
                        <input type="hidden" id="invoice_types" name="invoice_type" value="vendor">
                        <input type="hidden" name="igst" id="igst_amount1" >
                        <input type="hidden" name="cgst" id="cgst_amount1" >
                        <input type="hidden" name="sgst" id="sgst_amount1" >
                </div>
				</div>
                    <!-- <div class="col-md-6">

						<div id="accordion" style="margin-top: 28px;">

							<div class="card" style="margin-bottom:6px">
								<div class="card-header" style="padding: 0;" id="headingOne">
									<h5 class="mb-0">
										<button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
										Linguist Organization Addresss
										</button>
									</h5>
								</div>

								<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
									<div class="card-body" style="height:142px;overflow-y: auto;">
                                     <ul style="list-style: none;">
									@foreach($vendor_org_addr as $v_adr)
									 <li><input style="position: relative" class="form-check-input" type="radio" value="{{$v_adr->id}}" name="vendor_address" id="flexRadioDefault1" checked>&nbsp;  {{str_replace('<br />','',$v_adr->address)}}</li>
								      @endforeach
									 </ul>
									</div>
								</div>
							</div>

							<div class="card" style="margin-bottom:6px">
								<div class="card-header" style="padding: 0;" id="headingTwo">
									<h5 class="mb-0">
										<button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
										Linguist Organization Bank Details
										</button>
									</h5>
								</div>
								<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
									<div class="card-body" style="height:142px;overflow-y: auto">
									<ul style="list-style: none;">
									@foreach($vendor_bank_detail as $vbdtl)
									<li>
									<input style="position: relative; margin-right: 6px;" class="form-check-input" type="radio" value="{{$vbdtl->id}}" name="vendor_bankdetails" id="exampleRadios2" checked><b>Bank Name:</b> {{$vbdtl->bank_address}}<br><b>Bank Address:</b> {{$vbdtl->bank_address}}<br><b>Account Name:</b>{{$vbdtl->account_name}}<br><b>Bank Account:</b> {{$vbdtl->account_number}}<br><b>IFSC Code:</b> {{$vbdtl->ifsc_code}}
									</li><br>
									@endforeach	
								</ul>	
								</div>
								</div>
							</div>

							<div class="card" style="margin-bottom:6px">
								<div class="card-header" style="padding: 0;" id="headingThree">
									<h5 class="mb-0">
										<button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
										Linguist Personal Details
										</button>
									</h5>
								</div>
								<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
									<div class="card-body" style="height:142px;overflow-y: auto">
									<ul>
										@foreach($vendor_personal_detail as $v_pdetail)
										<li>
										<input style="position: relative; margin-right: 6px;"  class="form-check-input" value="{{$v_pdetail->id}}" type="radio" name="v_pdetail" id="exampleRadios2" checked> <b>State Code:</b> {{$v_pdetail->state_code}}<br><b>GSTIN:</b> {{$v_pdetail->gst}}<br><b>PAN:</b> {{$v_pdetail->pan}}
										</li>
										@endforeach
									</ul>
									
									</div>
								</div>
							</div>
							<div class="card" style="margin-bottom:6px">
								<div class="card-header" style="padding: 0;" id="headingThree">
									<h5 class="mb-0">
										<button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
										 Organization Addresss
										</button>
									</h5>
								</div>
								<div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
									<div class="card-body" style="height:142px;overflow-y: auto">
									<ul>
										@foreach($kpt_adrss as $kpt_adr)
										<li>
									    <input style="position: relative; margin-right: 6px;" class="form-check-input" value="{{$kpt_adr->id}}" type="radio" name="kpt_address" id="exampleRadios2" checked>&nbsp; {{str_replace('<br />','',$kpt_adr->address)}}
										</li>
										@endforeach
									</ul>
									</div>
								</div>
							</div>
							<div class="card" style="margin-bottom:6px">
								<div class="card-header" style="padding: 0;" id="headingFive">
									<h5 class="mb-0">
										<button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
										Organization Personal Details
										</button>
									</h5>
								</div>
								<div id="collapseFive" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
									<div class="card-body" style="height:142px;overflow-y: auto">
									<ul>
										@foreach($org_personal_detail as $org_pdetail)
										<li>
										<input class="form-check-input" value="{{$org_pdetail->id}}" type="radio" name="org_detail" id="exampleRadios2" checked> <b>State Code:</b> {{$org_pdetail->state_code}}<br><b>GSTIN:</b> {{$org_pdetail->gst}}<br><b>PAN:</b> {{$org_pdetail->pan}}
										</li>
										@endforeach
									</ul>
									
									</div>
								</div>
							</div>
							
						</div>

					</div> -->
                </div>
                <!-- Source Language block -->
                <!-- <div class="row">
                    <div class="col-md-12">
                        <?php $i=$j = 0;
                         $t = $f = 1;
                        ?>
                        <?php 
                        $get_source_lang=$getst_lang->quote_lang_select($loc_get->quote_gen_id);
                        ?>
                      <?php if(isset($vendor_id) && $vendor_id != ''){?>
                        <table class=" table table-bordered table-striped table-hover">
                        	<thead>
                        		<tr>
                        			<th>S.No.</th>
                        			<th>Description</th>
                        		</tr>
                        	</thead>
                        	<tbody>
                        <?php $coun=1;$total=0;?>
                        @foreach($get_source_lang as $get_source)
                        <?php
                        $target_lang = DB::table('loc_request_assigned')->where(['quote_id' => $loc_get->quote_gen_id,'loc_source_id'=>$get_source->id])->get()->toArray();
                       $loc_services_data=$loc_services::join('loc_service','loc_quote_service.service_type', '=', 'loc_service.id')->where(['quote_id'=>$loc_get->quote_gen_id,'loc_quote_service.loc_source_id'=>$get_source->id])->get()->toArray();
                       $get_loc_services_data = array_column((array)$loc_services_data, 'service_type');
                       $get_service_data=implode(',', $get_loc_services_data);
                       $check_lang=$vendors_list_data->toArray();
                       $check_lang = array_column((array)$check_lang, 'req_lang_id');
                       //print_r($check_lang);die;
                        ?>
                        <?php 
                        foreach ($target_lang as $gtl) {
                            if(in_array($gtl->id,$check_lang)){
                                $total=$total+$vendors_list_data[$j]->v_amount;
                        	?>
                            <tr>
                                <td>{{$coun}}</td>
                                <td><?php echo $get_service_data.' for '.$get_source->lang_name.' to '.gettabledata('loc_languages','lang_name',['lang_id'=>$gtl->target_language])?></td>
                            </tr>
                        <?php $coun++;$j++;}$i++;}?>
                        @endforeach
                    		</tbody>
                    		<tfoot>
					<?php
					$grand_total=$total;
					$pm_cost=0;
					if ($pm_cost > 0) {
						$pm_per=($pm_cost/100);
						$pm_total = $grand_total * $pm_per;
						$pm_total_display = $pm_total;
						$taxble_amount=$grand_total+$pm_total;
					}else{
						$pm_total = 0;
						$pm_total_display = null;
						$taxble_amount=$grand_total;
					}
					$gst_per=18;
					$cgst_per=9;
					$sgst_per=9;
					$gst_type=gettabledata('loc_translation_qoute_generation_master','translation_quote_gst',['translation_quote_id'=>$loc_get->quote_gen_id]);
                    if ($gst_type == 'yes') {
                        $g_per=($gst_per/100);
                        $gst_total = ($grand_total + $pm_total) * $g_per;
                        $gst_total_display = $gst_total;
                    } else {
                        $gst_total = 0;
                        $gst_total_display = null;
                    }
                    $final_grand_total = $grand_total + $pm_total + $gst_total;

                    if($cgst_per > 0){
                    	$cg_per=($cgst_per/100);
                    	$cgst_total = ($grand_total + $pm_total) * $cg_per;
                    	$cgst_total_display = $cgst_total;
                    }else{
                    	$cgst_total = 0;
                        $cgst_total_display = null;
                    }
                    $cfinal_grand_total = $grand_total + $pm_total + $cgst_total;
                    if($sgst_per > 0){
                    	$sg_per=($sgst_per/100);
                    	$sgst_total = ($grand_total + $pm_total) * $sg_per;
                    	$sgst_total_display = $sgst_total;
                    }else{
                    	$sgst_total = 0;
                        $sgst_total_display = null;
                    }
                    $sfinal_grand_total = $grand_total + $pm_total + $sgst_total;
					?>
					<tr>
	    				<th colspan="1">Total</th>
	    				<th>{{number_format($total,2)}}</th>
                        <input type="hidden" name="total_amnt" value="{{number_format($total,2)}}">

	    			</tr>
					<?php
					if ($pm_cost > 0) {
					?>
        			<tr>
        				<th colspan="1">Project Manager Cost {{$pm_cost}} %</th>
        				<th>{{number_format($pm_total_display,2)}}</th>
        			</tr>
                    <?php }?>
                    <tr>
        				<th colspan="1">Taxble Amount</th>
        				<th>{{number_format($taxble_amount,2)}}</th>
                        <input type="hidden" name="taxble_amnt" value="{{number_format($taxble_amount,2)}}">

        			</tr>
                    <tr><td colspan="5"></td></tr>
                    <tr>
        				<th colspan="1">UnBilled Amount</th>
        				<th>{{$vendor_unbilled_amount}}</th>
                        <input type="hidden" name="unbilled_amnt" value="{{number_format($vendor_unbilled_amount,2)}}">

        			</tr>
                    <tr>
        				<th colspan="1">Invoicing Amount</th>
        				<th id="invoicing_amount">0</th>
        			</tr>
					<?php
					//if ($gst_type == 'yes') {
					?>
        			<tr style="{{($gst_type == 'no')? 'display: none;': ''}}" id="igst_div">
        				<th colspan="1">IGST {{$gst_per}} %</th>
        				<th id="igst_amount">{{number_format($gst_total_display,2)}}</th>
        			</tr>
                    <?php //}?>
        			<tr style="display: none;" id="cgst_div">
        				<th colspan="1">CGST {{$cgst_per}} %</th>
        				<th id="cgst_amount">{{number_format($cgst_total_display,2)}}</th>
        			</tr>
        			<tr style="display: none;" id="sgst_div">
        				<th colspan="1">SGST <span id="sgst_per">9</span> %</th>
        				<th id="sgst_amount">{{number_format($sgst_total_display,2)}}</th>
        			  </tr>
        			 <tr>
        				<th colspan="1">Net Amount</th>
        				<th id="net_amount">{{number_format($final_grand_total,2)}}</th>
        			   </tr>
                    		</tfoot>
                        </table>
                      <?php }?>
                       </div> -->
                        <!-- <div class="col-md-6">&nbsp;</div>
                        <div class="col-md-6"> -->
                       
                        <input class="btn btn-danger" id="translation_submit" type="submit" value="{{ trans('global.submit') }}">
                       
                    </div>
             </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<!-- <script>
	checkgst('<?php echo $gst_quote;?>');
	function checkgst(type) {
		var payment=$('#payment').val();
		calculate_netamount(type,payment);
	}
	//checkpayment('100');
	function checkpayment(payment) {
		var type=$('#gst').val();
		calculate_netamount(type,payment);
	}
	function calculate_netamount(type,payment) {
		var vendor_unbilled_amount='{{$vendor_unbilled_amount}}';
		var invoicing_amount=((vendor_unbilled_amount*payment)/100);
		var net_amount=invoicing_amount;
		if(type =='igst' || type == 'yes'){
			$('#igst_div').show();
			$('#cgst_div').hide();
			$('#sgst_div').hide();
			var gst=18;
			var igst_amount=((invoicing_amount*gst)/100);
			$('#igst_amount').html(igst_amount);
			$('#igst_amount1').val(igst_amount);
			net_amount=invoicing_amount+igst_amount;
		}else if(type == 'both'){
			$('#igst_div').hide();
			$('#cgst_div').show();
			$('#sgst_div').show();
			var cgst=9;
			var sgst=9;
			var cnet_amount=((invoicing_amount*cgst)/100);
			var snet_amount=((invoicing_amount*sgst)/100);
			$('#cgst_amount').html(cnet_amount);
			$('#cgst_amount1').val(cnet_amount);
			$('#sgst_amount').html(snet_amount);
			$('#sgst_amount1').val(snet_amount);
			net_amount=invoicing_amount+cnet_amount+snet_amount;
		}else{
			$('#igst_div').hide();
			$('#cgst_div').hide();
			$('#sgst_div').hide();
		}
		$('#invoicing_amount').html(invoicing_amount);
		$('#invoicing_amount1').val(invoicing_amount);
		$('#net_amount').html(net_amount);
        $('#net_amount1').val(net_amount);
	}
function changevendor(vendor_id) {
    window.location.href='{{ route('admin.request.requestvendorinvoice',['refid'=>$loc_get->reference_id]) }}?vendor='+vendor_id;
}
</script> -->
@endsection