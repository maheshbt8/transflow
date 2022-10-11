<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="x-apple-disable-message-reformatting">
	<!-- CSS only -->

	<title>invoice</title>
	<style>
		table{
			border-collapse: collapse;
		}
		tr,td{font-family: Arial, sans-serif; border-collapse: collapse;  padding:5px 0 5px 6px;}
		hr {
			border: 6px dotted #5184AF;
			border-style:none none dotted;  
			background-color: #fff;
		}
		#digital{
			background-image: url("/img/gopisignature.jpg");
			background-repeat: no-repeat;
			background-size: 100px 75px;
		}
		.font{
			font-size: 14px;
			font-family:Arial, Helvetica, sans-serif;
			line-height: 1.27rem;

		}

	</style>
</head>
<body class="font" >

	<div class="container">
		<table width="100%" cellpadding="5" border="0">
			<tbody>
				<tr>
					<td style="width:50%;text-align:left;"><img src="{{ url('img/kpt.png') }}" width="174" height="60" border="0"></td>          
					<td style="font-family:sans-serif; width:50%;text-align:right"><b><p class="font" >KeyPoint Technologies India Pvt Ltd. </b><br>RAJAPRAASADAMU <br>D.No. 1-55/4/RP/L2/W1  Level 2, Wing 1B & 2  <br>Botanical Gardens Road  <br>Kondapur, Hyderabad - 500084<br> www.keypoint-tech.com<br> CIN : U72200TG2007FTC054351 <br></p></td>                        
				</tr>
			</tbody>
		</table><hr> 
		<h5 style=" margin:15px 0 5px 0;text-align: center; font-size:20px"><b>Tax Invoice</b></h5>
		<div class="container">
			<table  width="100%" style="border: 1px solid black;">
				<tr  style="border: 1px solid black;">
					<td width=50%  style="border: 1px solid black; padding-top:-1px;"><p><b>Seller</b><br>{{str_replace('<br/>','',$org_adress->address)}}<br>State Code : {{$org_personal->state_code}}<br>GSTIN  : {{$org_personal->gst}}<br>PAN : {{$org_personal->pan}}</p></td>
					<td width=35% style="border: 1px solid black; padding-top:-1px;"><p><b>Invoice No.</b><br>{{$invoicno}} <br><br><br><?php if($po){ echo '<b> PO.NO</b> :'; }?> {{$po ?? ''}}<br><b>Quote ID</b>: {{$org_quote_code}}<br><br></p></td>
					<td width=15% style="border: 1px solid black;"><p><b>Date</b><br>{{date("j M, Y",strtotime($loc_invoices->created_at))}}</p></td>
				</tr>

			</table>
			<table  width="100%" style="border: 1px solid black;">
				<tr style="border: 1px solid black;">
					<td width=50% style="border: 1px solid black;"><p><b>Buyer</b><br>{{$c_org_adress->address}}<br>State Code: {{$c_personal->state_code}} <br>GSTIN : {{$c_personal->gst}}   <br>PAN : {{$c_personal->pan}}</p></td>
					<td colspan="5" style="border: 1px solid black;"><p>Bank Name     : {{$bankdetail->bank_name}} <br>Bank Address :    {{$bankdetail->bank_address}},<br>Account Name:    {{$bankdetail->account_name}}<br>Account Number: {{$bankdetail->account_number}}<br> IFSC Code: {{$bankdetail->ifsc_code}} </p></td>
				</tr>
			</table>
		</div>
		<div class="row">
			<div class="col-md-12">
				<?php $i = 0;
				$t = $f = 1;
				?>
				<?php
				$get_source_lang = $getst_lang->quote_lang_select($loc_get->quote_gen_id);
				?>
				<table width="100%" style="border: 1px solid black;">
					<thead>
						<tr>
							<th style="border: 1px solid black;">S.No.</th>
							<th style="border: 1px solid black;">Description</th>
							<th style="border: 1px solid black;">Item</th>
							<th style="border: 1px solid black;">Unit/rate</th>
							<th style="border: 1px solid black;">Amount (INR)</th>
						</tr>
					</thead>
					<tbody>
						<?php $coun = 1;
						$total = 0; ?>
						@foreach($get_source_lang as $get_source)
						<?php
						$target_lang = DB::table('loc_request_assigned')->where(['quote_id' => $loc_get->quote_gen_id, 'loc_source_id' => $get_source->id])->get()->toArray();
								//$get_target_lang = array_column((array)$target_lang, 'target_language');
						$loc_services_data = $loc_services::join('loc_service', 'loc_quote_service.service_type', '=', 'loc_service.id')->where(['quote_id' => $loc_get->quote_gen_id, 'loc_quote_service.loc_source_id' => $get_source->id])->get()->toArray();
						$get_loc_services_data = array_column((array)$loc_services_data, 'service_type');
						$get_service_data = implode(',', $get_loc_services_data);
						?>
						<?php
								//for ($tl=0;$tl<count($get_target_lang);$tl++){

						foreach ($target_lang as $gtl) {
									/*echo "<p class="font"re/>";
									print_r($gtl);die;*/
									?>
									<?php
									if (!empty($gtl->word_count) && !empty($gtl->per_word_cost)) {
										$row_total = ($gtl->word_count * $gtl->per_word_cost);
										$total = $total + $row_total;
										?>
										<tr>
											<td  style="border: 1px solid black;">{{$coun}}</td>
											<td  style="border: 1px solid black;"><?php echo $get_service_data . ' for ' . $get_source->lang_name . ' to ' . gettabledata('loc_languages', 'lang_name', ['lang_id' => $gtl->target_language]) ?></td>
											<td  style="border: 1px solid black;">{{$gtl->word_count .' Words'}}</td>
											<td  style="border: 1px solid black;">{{number_format($gtl->per_word_cost,2). ' Per Word'}}</td>
											<td  style="border: 1px solid black;">{{ number_format($row_total,2) }}</td>
										</tr>
										<?php
										$coun++;
									} elseif (!empty($gtl->word_fixed_cost)) {
										$row_total = $gtl->word_fixed_cost;
										$total = $total + $row_total;
										?>
										<tr>
											<td style="border: 1px solid black;">{{$coun}}</td>
											<td style="border: 1px solid black;"><?php echo $get_service_data . ' for ' . $get_source->lang_name . ' to ' . gettabledata('loc_languages', 'lang_name', ['lang_id' => $gtl->target_language]) ?></td>
											<td style="border: 1px solid black;">NA</td>
											<td style="border: 1px solid black;">{{ number_format($row_total,2) . ' Fixed Words' }}</td>
											<td style="border: 1px solid black;">{{ number_format($row_total,2) }}</td>
										</tr>
										<?php
										$coun++;
									}
									?>

									<?php
									if (!empty($gtl->page_count) && !empty($gtl->per_page_cost)) {
										$row_total = ($gtl->page_count * $gtl->per_page_cost);
										$total = $total + $row_total;
										?>
										<tr>
											<td  style="border: 1px solid black;">{{$coun}}</td>
											<td  style="border: 1px solid black;"><?php echo $get_service_data . ' for ' . $get_source->lang_name . ' to ' . gettabledata('loc_languages', 'lang_name', ['lang_id' => $gtl->target_language]) ?></td>
											<td  style="border: 1px solid black;">{{$gtl->page_count.' Pages'}}</td>
											<td  style="border: 1px solid black;">{{number_format($gtl->per_page_cost,2).' Per Page'}}</td>
											<td  style="border: 1px solid black;">{{ number_format($row_total,2) }}</td>
										</tr>
										<?php
										$coun++;
									} elseif (!empty($gtl->page_fixed_cost)) {
										$row_total = $gtl->page_fixed_cost;
										$total = $total + $row_total;
										?>
										<tr>
											<td  style="border: 1px solid black;">{{$coun}}</td>
											<td  style="border: 1px solid black;"><?php echo $get_service_data . ' for ' . $get_source->lang_name . ' to ' . gettabledata('loc_languages', 'lang_name', ['lang_id' => $gtl->target_language]) ?></td>
											<td  style="border: 1px solid black;">NA</td>
											<td  style="border: 1px solid black;">{{ number_format($row_total,2)  . ' Fixed Pages' }}</td>
											<td  style="border: 1px solid black;">{{ number_format($row_total,2) }}</td>
										</tr>
										<?php
										$coun++;
									}
									?>

									<?php
									if (!empty($gtl->minute_count) && !empty($gtl->per_minute_cost)) {
										$row_total = ($gtl->minute_count * $gtl->per_minute_cost);
										$total = $total + $row_total;
										?>
										<tr>
											<td  style="border: 1px solid black;">{{$coun}}</td>
											<td  style="border: 1px solid black;"><?php echo $get_service_data . ' for ' . $get_source->lang_name . ' to ' . gettabledata('loc_languages', 'lang_name', ['lang_id' => $gtl->target_language]) ?></td>
											<td  style="border: 1px solid black;">{{$gtl->minute_count.' Minutes'}}</td>
											<td  style="border: 1px solid black;">{{number_format($gtl->per_minute_cost,2).' Per Minute'}}</td>
											<td  style="border: 1px solid black;">{{ number_format($row_total,2) }}</td>
										</tr>
										<?php
										$coun++;
									} elseif (!empty($gtl->minute_fixed_cost)) {
										$row_total = $gtl->minute_fixed_cost;
										$total = $total + $row_total;
										?>
										<tr>
											<td  style="border: 1px solid black;">{{$coun}}</td>
											<td  style="border: 1px solid black;"><?php echo $get_service_data . ' for ' . $get_source->lang_name . ' to ' . gettabledata('loc_languages', 'lang_name', ['lang_id' => $gtl->target_language]) ?></td>
											<td  style="border: 1px solid black;">NA</td>
											<td  style="border: 1px solid black;">{{ number_format($row_total,2)  . ' Fixed Minutes' }}</td>
											<td  style="border: 1px solid black;">{{ number_format($row_total,2) }}</td>
										</tr>
										<?php
										$coun++;
									}
									?>
									<?php $i++;
								} ?>
								@endforeach
							</tbody>
							<tfoot>
								
								<?php
								$grand_total = $ttl_amnt;
								$pm_cost = $pmcosts->pm_cost;
								/*$pmcostss = $pmcosts;
								$pm_cost=(float) str_replace(',', '', $pmcostss);*/
								// echo $pm_cost ; die;
								
								if ($pm_cost > 0) {
									$pm_total_display = $pmcost;
									$taxble_amount = $taxable_amnt;
								} else {
									$pm_total_display = '';
									$taxble_amount = $taxable_amnt;
								}
								
								?>
								<tr>
									<td  style="border: 1px solid black;" colspan="4">Total</td>
									<td style="border: 1px solid black;">{{ $ttl_amnt }}</td>
								</tr>
								<?php
								if ($pm_cost > 0) {
									?>
									<tr>
										<td  style="border: 1px solid black;" colspan="4">Project Manager Cost {{ $pm_cost }} %</td>
										<td  style="border: 1px solid black;">{{ $pm_total_display }}</td>
									</tr>
								<?php } ?>
								<tr>
									<td  style="border: 1px solid black;" colspan="4">Taxble Amount</td>
									<td  style="border: 1px solid black;">{{ $taxble_amount }}</td>
								</tr>
								<tr>
									<td  style="border: 1px solid black;" colspan="4">UnBilled Amount</td>
									<td  style="border: 1px solid black;">{{ $unbilled_amount }}</td>
								</tr>
								<tr>
									<td  style="border: 1px solid black;" colspan="4">Invoicing Amount &nbsp; (<span>{{ $payment_per }} % of UnBilled Amount</span>)</td>
									<td  style="border: 1px solid black;" id="invoicing_amount">{{ $invoice_amnt }}</td>
								</tr>
								<tr>
									<td  style="border: 1px solid black;" colspan="4">{{ $gst }} </td>
									<td  style="border: 1px solid black;" >{{ $gstdata }}</td>
								</tr>
								<tr>
									<td  style="border: 1px solid black;" colspan="4">Net Amount</td>
									<td  style="border: 1px solid black;" id="net_amount">{{ $net_amnt }}</td>
								</tr>
							</tfoot>
						</table>

				<table width="100%" style="border: 1px solid black;"> 
					<tbody >
						<tr>
							<td  style="border: 1px solid black;" width="80%"><b>Amount Chargeable (in words):</b><br>INR {{ $net_amnt }} </td>
							<td style="border: 1px solid black; text-align:center;"  width="20%">E& O.E</td>
						</tr>
					</tbody>
				</table>	
				<table  width="100%" style="border: 1px solid black;">
					<tbody>
						<tr style="border: 1px solid black;">
							<th  style="border: 1px solid black;" width="10%">SAC</th>
							<th  style="border: 1px solid black;" width="30%">Taxable Value</th>
							<th  style="border: 1px solid black;" width="42%">Tax Rate</th>
							<th  style="border: 1px solid black;" width="18%">Amount</th>
						</tr>
						<tr style="border: 1px solid black;">
							<td  style="border: 1px solid black;" width="10%">4376</td>
							<td  style="border: 1px solid black;" width="30%">{{ $invoice_amnt }}</td>
							<td  style="border: 1px solid black;" width="42%">{{ $gst }}</td>
							<td  style="border: 1px solid black;" width="18%">{{ $gstdata }}</td>
						</tr>
						<tr style="border: 1px solid black;">
							<td  style="border: 1px solid black;" width="10%"><b>Total</b></td>
							<td  style="border: 1px solid black;" width="30%"><b>{{ $invoice_amnt }}</b></td>
							<td  style="border: 1px solid black;" width="42%"></td>
							<td  style="border: 1px solid black;" width="18%"><b>{{ $gstdata }}</b></td>
						</tr>
					</tbody>
				</table>
				<table width="100%" style="border: 1px solid black;">
					<tr>
						<td width="50%" style="border: 1px solid black; text-align: left;">Tax amount (in words) To be Paid<br><b>INR {{ $gstdata }} </b></td>
						<td colspan="2" style="border: 1px solid black; text-align: left;">
							<table>
								<tbody>
									<tr>
										<td colspan="2"><b>For KeyPoint Technologies India Private Limited.,</b></td>
									</tr>
									<tr>
										<td class="font">GOPI<br>SRINIVAS<br>THEDLAPU</td>
										<td id="digital" style="font-size:9px;line-height:10px;"><p style="text-align:left;margin-right:140px; ">Digitally signed by<br>GOPI SRINIVAS <br>THEDLAPU<br>Date: {{ $loc_invoices->created_at }}</p></td>
									</tr>
									<tr>
										<td colspan="2"><b>Authorized Signatory</b></td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</table>			
			</div>
		</div>
	</body>
</html>

