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
  tr,td{font-family: Arial, sans-serif;   padding:5px 0 5px 6px;}
  hr {
    border: 6px dotted #5184AF;
    border-style:none none dotted;  
    background-color: #fff;
  }

  .font{
    font-size: 14px;
    font-family:Arial, Helvetica, sans-serif;
    line-height: 1.5rem;

  }

</style>
</head>
<body class="font" >

  <div class="container">
    <table width="100%" cellpadding="5" border="0">
     <tbody>
       <tr>
         <td style="width:50%;text-align:left;">
          <img src="{{ url('img/kpt.png') }}" width="174" height="60" border="0">
        </td>          
        <td style="font-family:sans-serif; width:50%;text-align:right">
          <p class="font" ><b>KeyPoint Technologies India Pvt Ltd. </b><br>RAJAPRAASADAMU <br>D.No. 1-55/4/RP/L2/W1  Level 2, Wing 1B & 2  <br>Botanical Gardens Road  <br>Kondapur, Hyderabad - 500084<br> www.keypoint-tech.com<br> CIN : U72200TG2007FTC054351 <br></p>
        </td>                        
       </tr>
     </tbody>
   </table><hr> 
   <h5 style=" margin:15px 0 5px 0;text-align: center; font-size:20px"><b>Tax Invoice</b></h5>
   <div class="container">
    <table  width="100%" style="border: 1px solid black;">
      <tr  style="border: 1px solid black;">

        <td width=50%  style="border: 1px solid black; padding-top:-1px;">
          <p>
            <b>Vendor</b><br>{{$v_org_adress->address}}<br>State Code : {{$v_personal->state_code}}<br>GSTIN  : {{$v_personal->gst}}<br>PAN : {{$v_personal->pan}}
          </p>
        </td>
        <td width=35% style="border: 1px solid black; padding-top:-1px;">
          <p>
            <b>Invoice No.</b><br/>{{$invoicno}}<br>
            <b>Request ID</b>: {{$request_id}}<br><br>
          </p>
        </td>
        <td width=15% style="border: 1px solid black;">
          <p>
            <b>Date:</b><br/>{{date("j M, Y",strtotime($loc_invoices->created_at))}}
          </p>
        </td>
      </tr>
    </table>
    <table  width="100%" style="border: 1px solid black;">
      <tr  style="border: 1px solid black;">
        <td width=50% style="border: 1px solid black;"><p><b>Buyer</b><br>{{str_replace('<br/>','',$org_adress->address)}}<br>State Code: {{$org_personal->state_code}} <br>GSTIN : {{$org_personal->gst}}   <br>PAN : {{$org_personal->pan}}</p></td>
        <td colspan="5" style="border: 1px solid black;margin-top: 0px;"><p><b>Bank Details</b><br>Bank Name : {{$v_bankdetail->bank_name}} <br>Bank Address :    {{$v_bankdetail->bank_address}},<br>Account Name:    {{$v_bankdetail->account_name}}<br>Account Number: {{$v_bankdetail->account_number}}<br> IFSC: {{$v_bankdetail->ifsc_code}} </p></td>
      </tr>
    </table>
  </div>
  <div class="row">
   <div class="col-md-12">
     <?php $i=$j = 0;
     $t = $f = 1;
     ?>
     <?php 
     $get_source_lang=$getst_lang->quote_lang_select($loc_get->quote_gen_id);
     ?>

     <table style="border: 1px solid black;" width="100%">
       <thead>
        <tr>
         <th style="border: 1px solid black;" align="left" width="50%">S.No.</th>
         <th style="border: 1px solid black;"  align="left" width="50%">Description</th>
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
					//    print_r($check_lang);die;
      $check_lang = array_column((array)$check_lang, 'req_lang_id');
                       //print_r($check_lang);die;
      ?>
      <?php 
      foreach ($target_lang as $gtl) {
        if(in_array($gtl->id,$check_lang)){
          $total=$total+$vendors_list_data[$j]->v_amount;
          ?>
          <tr>
            <td style="border: 1px solid black;">{{$coun}}</td>
            <td style="border: 1px solid black;"><?php echo $get_service_data.' for <b>'.$get_source->lang_name.' </b> to <b> '.gettabledata('loc_languages','lang_name',['lang_id'=>$gtl->target_language]).'</b>'?></td>
          </tr>
          <?php $coun++;$j++;}$i++;}?>
          @endforeach
        </tbody>
        <tfoot>

         <tr>
           <td style="border: 1px solid black;" colspan="1">Total</td>
           <?php $ttl_amnts=(float) str_replace(',', '', $ttl_amnt);
           $taxable_amnts=(float) str_replace(',', '', $taxable_amnt);
           $unbilled_amounts=(float) str_replace(',', '', $unbilled_amount);
           $invoice_amnts=(float) str_replace(',', '', $invoice_amnt);
           $gstdatas=(float) str_replace(',', '', $gstdata);
           $net_amnts=(float) str_replace(',', '', $net_amnt);
           ?>
           <td style="border: 1px solid black;">{{number_format($ttl_amnts,2)}}</td>

         </tr>


         <tr>
          <td style="border: 1px solid black;" colspan="1">Taxble Amount</td>
          <td style="border: 1px solid black;">{{number_format($taxable_amnts,2)}}</td>


        </tr>

        <tr>
          <td style="border: 1px solid black;" colspan="1">UnBilled Amount</td>
          <td style="border: 1px solid black;">{{$unbilled_amounts}}</td>


        </tr>
        <tr>
          <td style="border: 1px solid black;" colspan="1">Invoicing Amount &nbsp; (<span>{{$payment_per}} % of {{$unbilled_amounts}}</span>)</td>
          <td  style="border: 1px solid black;">{{$invoice_amnts}}</td>

        </tr>

        <tr>
          <td style="border: 1px solid black;" colspan="1">{{$gst}}</td>
          <td style="border: 1px solid black;" >{{number_format($gstdatas,2)}}</td>
        </tr>
        <tr>
          <td style="border: 1px solid black;" colspan="1">Net Amount</td>
          <td style="border: 1px solid black;">{{number_format($net_amnts,2)}}</td>
        </tr>
      </tfoot>
    </table>

  </div>
</div>
<table width="100%" style="border: 1px solid black;">
  <tr>
    <td width="50%" style="border: 1px solid black; text-align: left;">Tax amount (in words) To be Paid<br><b>INR {{currenychange($net_amnt)}}</b></td>
    <td width="50%" style="border: 1px solid black; text-align: left;">
      <?php $vendor_name= DB::table('users')->where('id',$v_personal->user_id)->first() ?> 
      <p style="margin-top:2px;">{{$vendor_name->name}}</p>
    </td>
  </tr>
</table>

</body>
</html>

