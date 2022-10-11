@extends('layouts.admin')
@section('content')
@if(checkpermission('qoute_generation','add'))
<!-- <div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.quotegeneration.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.quotegenerate.title_singular') }}
        </a>
    </div>
</div> -->
@endif
@if(Session::has('flash_message'))
<div class="alert alert-success">
    {{ Session::get('flash_message') }}
</div>
@endif


<div class="card">
    <div class="card-header">
    Generated Quotes List
        <div class="btn-group float-right">
            @if(checkpermission('qoute_generation','add'))
            <a class="btn btn-success" href="{{ route("admin.quotegeneration.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.quotegenerate.title_singular') }}
            </a>
            @endif
            <button type="button" class="btn btn-default float-right" id="daterange-btn">
              <i class="far fa-calendar-alt"></i> {{ date('M d, Y', strtotime($sdate)).' - '.date('M d, Y', strtotime($edate)) }}
              <i class="fas fa-caret-down"></i>
            </button>
            <button type="button" class="btn btn-success btn-sm">{{ ucwords($req_status) }}</button>
                <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu" role="menu" style="">
                    <a class="dropdown-item" href="{{route('admin.quotegeneration.index')}}/?status=booked&sdate={{ $sdate }}&edate={{ $edate }}">Booked</a>
                    <a class="dropdown-item" href="{{route('admin.quotegeneration.index')}}/?status=billed&sdate={{ $sdate }}&edate={{ $edate }}">Billed</a>
                    <a class="dropdown-item" href="{{route('admin.quotegeneration.index')}}/?status=paid&sdate={{ $sdate }}&edate={{ $edate }}">Paid</a>
                    <a class="dropdown-item" href="{{route('admin.quotegeneration.index')}}/?status=cancel&sdate={{ $sdate }}&edate={{ $edate }}">Cancel</a>
                </div>
        </div>
        <a href="{{ route("admin.quotegeneration.index") }}" class="btn btn-xs btn-info float-right">Clear Search</a>
    </div>
    <?php $user_role = Auth::user()->roles[0]->name;
    $userid = Auth::user()->id;
    ?>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.quotegenerate.fields.QuoteID') }}
                        </th>
                        <th class="ext-th-data">
                            {{ trans('cruds.quotegenerate.fields.name') }}
                        </th>
                        <th>
                           Company
                        </th>
                        <th>
                            Sales
                        </th>
                        <th class="ext-th-data">
                            {{ trans('cruds.quotegenerate.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.quotegenerate.fields.date') }}
                        </th>
                        <th>
                            {{ trans('cruds.quotegenerate.fields.type_of_request') }}
                        </th>
                        <th>
                          Source Language-Target Language 
                        </th>
                        <th>
                            {{ trans('cruds.quotegenerate.fields.currency') }}
                        </th>
                        <th>
                            Amount
                        </th>
                        <th>
                            {{ trans('cruds.locrequest.fields.created_at') }}
                        </th>
                        <?php $vlc=0; $vendors_data=[];?>
                        @foreach($translation as $key => $trans)
                    <?php $req_data=$loc_request->where('quote_gen_id',$trans->translation_quote_id)->first();
                    $vendor_records=[];
                    if($req_data){
                        $where_status ='(v_id != "" OR tr_id != "")';
                        $vendordata=DB::table('loc_target_file')->where('request_id',$req_data->req_id)->whereRaw($where_status)->groupBy(['v_id','tr_id']);
                        $vendor_cc=$vendordata->get();
                        if(count($vendor_cc) > $vlc){
                            $vlc=count($vendor_cc);
                        }
                    }
                    ?>
                    @endforeach
                    @if($vlc > 0)
                        <th style="display: none;">Assigned at</th>
                        @for($thv= 0; $thv < $vlc ;$thv++)
                            <th style="display: none;">Vendor <?php echo $thv+1;?></th>
                            <th style="display: none;">Amount</th>
                        @endfor
                        <th style="display: none;">Total Amount</th>
                        <th style="display: none;">Profit Margin %</th>
                    @endif
                        <th class="noExport">
                        Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                  <?php $v1=0;?>
                    @foreach($translation as $key => $translation)
                    <?php 
                    $req_data=$loc_request->where('quote_gen_id',$translation->translation_quote_id)->first();
                    if($req_data){
                        $where_status ='(v_id != "" OR tr_id != "")';
                        $vendordata=DB::table('loc_target_file')->where('request_id',$req_data->req_id)->whereRaw($where_status)->groupBy(['v_id','tr_id']);
                        $vendor_records_list=$vendordata->get();
                        $total=0;
                        $vendor_records=[];
                        foreach ($vendor_records_list as $vr) {
                                if($vr->tr_id != ''){
                                    $tr_id=$vr->tr_id;
                                }elseif($vr->v_id != ''){
                                    $tr_id=$vr->v_id;
                                }else{
                                    $tr_id='';
                                }
                                $vendor_records['v_assigned']=$vr->v_assigned_date;
                                $vendor_records['vendors'][]=[
                                        'created_at'=>$vr->v_assigned_date,
                                        'user_id'=>$tr_id,
                                        'amount'=>$vr->v_total,
                                    ];

                                $total=$total+$vr->v_total;
                            }
                        $sum_of_pending=$trans->grand_total;
                      
                      
                    if($sum_of_pending>0){
                        $profit=number_format((float)((($sum_of_pending-$total)/$sum_of_pending)*100), 2, '.', '');
                    } else{
                      
                        $profit=0;
                }
                        $vendor_records['total']=$total;

                        $vendor_records['profit']=$profit;
                    }
                    ?>
                    <tr>
                        <td>
                            <a href="{{ route('admin.quotegeneration.editquote',$translation->quote_code) }}">{{ $translation->quote_code }}</a>
                            <!-- @if($translation->request_id != '') -->
                                
                            <!-- @else
                                
                            @endif -->
                            <!-- <a href="{{ route('admin.request.requestupdate',['refid'=>$translation->quote_code]) }}">{{ $translation->quote_code }}</a> -->
                        </td>
                        <td>{{ $fullname= $translation->first_name." ".$translation->last_name}}</td>
                        <!-- <td> {{ $translation->first_name}}</td>-->
                        <td> <a href="{{ route("admin.quotegeneration.index",'client='.$translation->client_org_id.'&status='.$req_status.'&sdate='.$sdate.'&edate='.$edate) }}">{{ $translation->client_comp_name }}</a></td> 
                        <td><a href="{{ route("admin.quotegeneration.index",'sales='.$translation->translation_user_id.'&status='.$req_status.'&sdate='.$sdate.'&edate='.$edate) }}"> {{ getusernamebyid($translation->translation_user_id) }}</a></td> 
                        <td> {{ $translation->email }}</td>
                        <td>
                            {{ date("j M, Y",strtotime($translation->translation_quote_date)) }}
                        </td>
                        <td>
                            <?php 
                            $source_lang=$getst_lang->quote_lang_select($translation->translation_quote_id);
                            
                            // $sourcelang=(array)source_lang
                            foreach($source_lang as $lang){
                            $service=$getst_lang->quote_service_select($translation->translation_quote_id,$lang->id);
                          //  echo "<pre/>";
                           // print_r($service);die;
                            foreach ($service as $sl) {
                                echo '<li>'. $sl->service_type.'</li>';
                                
                            }
                        }
                            ?>
                         
                        </td>
                        <td>
                        <?php
                            $source_lang=$getst_lang->quote_lang_select($translation->translation_quote_id);
                            foreach($source_lang as $lang){
                                $target_lang=$getst_lang->quote_lang_select($translation->translation_quote_id,'target',$lang->id);
                                foreach ($target_lang as $sl) {
                                    echo '<li>'. $lang->lang_name.' -'.$sl->lang_name.'</li>';
                                    
                                }
                            }
                            ?>

                           
                        </td>
                        <td>
                            {{ gettabledata('currencies','currency_name',['id'=>$translation->translation_quote_currency]) }}
                        </td>
                        <td>
                            {{ $translation->grand_total }}
                        </td>
                        <td>
                            {{ date("j M, Y h:i A",strtotime($translation->created_at)) }}
                        </td>
                        <?php if($vlc > 0){ 
                            if(count($vendor_records) == 4){
                                $vd=$vendor_records;
                            ?>
                            <td style="display: none;">{{ $vd['v_assigned'] ?? '' }}</td>
                            @for($thv= 0; $thv < $vlc ;$thv++)
                                @if(isset($vd['vendors'][$thv]['user_id']) && $vd['vendors'][$thv]['user_id'] != '')
                                <td style="display: none;">{{ $vd['vendors'][$thv]['user_id'] }}</td>
                                <td style="display: none;">{{ $vd['vendors'][$thv]['amount'] }}</td>
                                @else
                                    <td style="display: none;"></td>
                                    <td style="display: none;"></td>
                                @endif
                            @endfor
                            <td style="display: none;">{{ $vd['total'] ?? '' }}</td>
                            <td style="display: none;">{{ $vd['profit'] ?? '' }} %</td>
                        <?php }else{
                            ?>
                            @if($vlc > 0)
                                    <td style="display: none;"></td>
                                    @for($thv= 0; $thv < $vlc ;$thv++)
                                        <td style="display: none;"></td>
                                        <td style="display: none;"></td>
                                    @endfor
                                        <td style="display: none;"></td>
                                        <td style="display: none;"></td>
                                @endif
                            <?php }}?>
                        <td>
                        <?php 
                     
                        $translation_status=$translation->translation_status;
                        $arr_req_status = array($translation_status);
                        $arr_main_req_status_in    = array();
                        
                        if( $user_role=='sales' ||  $user_role=='administrator' ){
                        $arr_main_req_status = array('Assign');
                        $arr_main_req_status_in    = array('Open');
                        }  
                        elseif($user_role=='projectmanager'){
                            $arr_main_req_status = array('Assign');
                            $arr_main_req_status_in    = array('Assign');
                        }
                        ?>
  <?php 
if(in_array($translation->translation_status,$arr_main_req_status_in)){
 
?>
<div class="dropdown dropleft">
  <button class="btn btn-info btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    @foreach($arr_main_req_status as $row)
    <a href="javascript:void(0)" class="dropdown-item apply_action_request_status" alert-msg="Are you sure you want to change the Request Status?" quote_code="{{$translation->quote_code}}" translation_status="{{$row}}">{{$user_role=='projectmanager'? 'Re-Assign' : $row}}</a>
    @endforeach
  </div>
</div>
<?php } ?>    
<?php if(($user_role=="projectmanager" || $user_role=="sales" ) && $translation->translation_status=="Assign"){
    $check_cr_btn=$loc_request->check_create_request_option($translation->translation_quote_id);
    if(!$check_cr_btn){
    ?>    
    <a class="btn btn-xs" href="{{ route('admin.request.createrequest',$translation->quote_code) }}" alt="Create a Project">
    <i class="fa fa-file-upload text-info"></i></a>
    <?php } }?>

   @if($translation->pm_id == '' || $user_role=='administrator'||$user_role=='orgadmin')
        <a class="btn btn-xs" href="{{ route('admin.quotegeneration.edit',$translation->quote_code) }}">
        <i class="fa fa-edit text-info"></i>
        </a>
   @endif
   @if( $user_role=='sales')
   <a href="{{ route('admin.quotegeneration.edit',$translation->quote_code) }}?repeat_quote={{base64_encode('yes')}}">
        Retrive Quote
    </a>
    @endif 
   @if( $user_role=='orgadmin' || $user_role=='administrator')
    <a type="submit"  class="btn btn-danger btn-sm" href="{{ route('admin.quotegeneration.quote_cancel',$translation->translation_quote_id) }}"><i class="fa fa-trash" aria-hidden="true"></i>
    </a>
    @endif                 
<a href="{{ route('admin.quotegeneration.upload_po_order').'?q='.base64_encode(base64_encode($translation->quote_code)).'&s='.base64_encode(base64_encode($translation->translation_user_id)) }}" class="btn btn-xs" ><i class="fa fa-upload text-info" aria-hidden="true">PO</i></a>
</td>
                     
                    </tr>
                    <?php $v1++;?>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
    
<div id="assign_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="assign_modal_header"></h3>
            </div>
            <div class="modal-body message_body">
                <div class="row">
                <div class="span9" id="error_on_header"></div>
                    <div class="col-md-12 col-xs-12 col-sm-12">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class=" table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Assign Project Manager</th>
                                            </tr>
                                        </thead>
                                        <tbody id="assign_user_data">
                                                            
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                          </div>
                       </div>
                <input id="request_id_popup" value="" type="hidden"/>
                <input id="request_status_popup" value="" type="hidden"/>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="submitassigndata()">Submit</button>
                <button class="btn btn-info" data-dismiss="modal" >Cancel </button>
            </div>
        </div>
    </div>
    
</div>
@endsection
@section('scripts')
@parent
<script>
   $('.apply_action_request_status').on('click', function () {
var con = confirm($(this).attr('alert-msg'));
        if(con == false)
            return false;

    var quote_code  = $(this).attr('quote_code');        
    var translation_status     = $(this).attr('translation_status');
    if(translation_status == 'Assign'|| translation_status == 'assign_vendor' ){
        show_popup(quote_code,translation_status);
    }else{
        change_status_ajax(quote_code,translation_status);
    }
});
    function submitassigndata(){
        var dataValid = true;
        var vendor_list='';
        $("[name^=vendor_list_select]").each(function(index, value) {
            if ($(this).val() == '') {
                    $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please select all</div>');
                    $(this).focus();
                    dataValid = false;
                    return false;
            }else{
                if(vendor_list == ''){
                    vendor_list =$(this).val();
                }else{
                    vendor_list +=','+$(this).val();
                }
            }
        });
        if(dataValid == true){
            var quote_code=$('#request_id_popup').val();
        
            var translation_status=$('#request_status_popup').val();
            
            change_status_ajax(quote_code,translation_status,vendor_list);
        }
    }
    function show_popup(quote_code,translation_status) {
        jQuery('#assign_modal').modal('show', {backdrop: 'true'});
        var header ='';
        if(translation_status == 'Assign'){
            header='Assign To Project Manger';
           }
        selectRefresh();
        pm_users=<?php echo $pm_users;?>;
        jQuery('#assign_modal #assign_modal_header').html(header);
        jQuery('#request_id_popup').val(quote_code);
        jQuery('#request_status_popup').val(translation_status);
        if(translation_status == 'Assign'){
        var options='<select  class="form-control select2 translators_list_select" id="translators_list_select" name="vendor_list_select" required><option value="">Select User</option>';
        for(var i=0;i<pm_users.length;i++){
           
            options +='<option value="'+pm_users[i].id+'">'+pm_users[i].name+'</option>';
        }
   
        options +='</select>';

        $('#assign_user_data').html(options);
    }
    else{
        $.ajax({
            url:"{{ route("admin.quotegeneration.get_request_quote_assign_data") }}",
            method:"POST",
            data:{"quote_code":quote_code, "translation_status":translation_status},
            headers: {'x-csrf-token': _token},
            success:function(response){
                $('#assign_user_data').html(response);
                //location.reload(true);
            }
        });
    }
    }
    function change_status_ajax(quote_code,translation_status,assign_data=''){
        
        $.ajax({
            url:"{{ route("admin.quotegeneration.request_change_quote_status") }}",
            method:"POST",
            data:{"quote_code":quote_code, "translation_status":translation_status,"assign_data":assign_data},
            headers: {'x-csrf-token': _token},
            success:function(response){
                location.reload(true);
            }
        });
    }
function selectRefresh() {
       $('.select2').select2({
           dropdownParent: $('#assign_modal')
       });
   }

function filterofdaterange(start,end) {
    var sdate=start.format('Y-M-D');
    var edate=end.format('Y-M-D');
    var status="{{ $req_status }}";
    window.location.href = "{{route('admin.quotegeneration.index')}}/?status="+status+"&sdate="+sdate+"&edate="+edate;
}
</script>
@endsection