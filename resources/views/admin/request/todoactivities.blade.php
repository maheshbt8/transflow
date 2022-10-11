@extends('layouts.admin')
@section('content')
<style type="text/css">
</style>
<div class="card">
    <?php $user_role = Auth::user()->roles[0]->name; ?>
    <div class="card-header">
        {{ trans('cruds.locrequest.fields.todoactivities') }} {{ trans('global.list') }}
        <div class="btn-group float-right">
            @if(checkpermission('create_request','add'))
            <a class="btn btn-success" href="{{ route("admin.request.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.locrequest.title_singular') }}
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
                    <a class="dropdown-item" href="{{route('admin.request.todoactivities')}}/?status=active&sdate={{ $sdate }}&edate={{ $edate }}">Active</a>
                    <a class="dropdown-item" href="{{route('admin.request.todoactivities')}}/?status=inprogress&sdate={{ $sdate }}&edate={{ $edate }}">Inprogress</a>
                    <a class="dropdown-item" href="{{route('admin.request.todoactivities')}}/?status=completed&sdate={{ $sdate }}&edate={{ $edate }}">Completed</a>
                    <a class="dropdown-item" href="{{route('admin.request.todoactivities')}}/?status=cancel&sdate={{ $sdate }}&edate={{ $edate }}">Cancel</a>
                </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        
            <table class="table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th width="10">
                         S.No
                        </th>
                        <th>
                            {{ trans('cruds.locrequest.fields.request_id') }}
                        </th>
                        
                        <th>
                            Company 
                        </th>
                        <!-- <th> -->
                            <!-- {{ trans('cruds.locrequest.fields.translate_to') }} -->
                        <!-- </th> -->

						<th>
                            {{ trans('cruds.locrequest.fields.status') }}
                        </th>
                       
						
						<th>
                            {{ trans('cruds.locrequest.fields.created_at') }}
                        </th>
	
                        <th>
                            {{ trans('cruds.locrequest.fields.delivery_date') }}
                        </th>
                        <th>Action</th>
                       
                    </tr>
                </thead>
                <tbody>
                <?php $i=0;?>

           
                    @foreach($todoactivities as $key => $todoact)

                      
                         <tr data-entry-id="{{ $todoact->mk_campign_id }}">
                            <td>
                            {{$i+1}}
                            </td>  
                            <td>
                                <a href="{{ route('admin.request.requestupdate',['refid'=>$todoact->reference_id]) }}" >{{ $todoact->reference_id ?? '' }}
                                </a>
                                <?php
	if($todoact->request_status == "New"){
		?>
		<a href="request/{{$todoact->reference_id}}/edit" ><button style="font-size:10px;padding: 6px 1px;line-height:0px">edit</button></a>
	<?php
	}?>
                            </td>
                           
                           
                            
                                <!-- <ul> -->
                            	<?php
                              //  $sl_tl = $loc_request->loc_reference_lang_select($todoact->req_id, 'sl_tl');
                            	/*$source_lang=$loc_request->loc_reference_lang_select($todoact->req_id);
                                $target_lang=$loc_request->loc_reference_lang_select($todoact->req_id,'target');*/
                                /*$i=0;
                                foreach ($target_lang as $sl) {
                                    echo $source_lang[$i]->lang_name.' -'.$sl->lang_name.'<br/>';
                                    $i++;
                                }*/
                               // foreach ($sl_tl as $sl){
                                   // echo '<li>'.$sl->source_lang_name .' - '. $sl->target_lang_name.'</li>';
                              //  }
                            	?>
                                <!-- </ul> -->
                                <!-- {{ $source_lang[0]->lang_name ?? '' }} -->
                            <!-- </td> -->
                            <td>{{$todoact->client_comp_name}}</td>
                           <td>{{ showcrstatus($todoact->request_status) }}</td>
                            <td>
                                {{ date("j M, Y h:i A",strtotime($todoact->created_time ?? '' ))}}<br/>By:
                                <b>{{ getusernamebyid($todoact->user_id) ?? '' }}</b>
                            </td>
                            <td>
                                {{ $todoact->publish_date ?? '' }}
                            </td>
                            <td>
                          <?php 
                          $request_status=getcrstatus($todoact->request_status);        
                $arr_req_status = array($request_status);
                
                $arr_main_req_status=array();
              //  print_r($user_role)
   if($user_role == 'orgadmin' || $user_role=='projectmanager'){
        $arr_main_req_status    = array('tr_assign','qa_assign','pr_assign','publish','pm_reject');
         $arr_main_req_status_in    = array('approve','tr_inprogress','tr_assign','qa_assign','qa_inprogress','qa_accept','qa_reject','pr_assign','pr_inprogress','pr_reject','pr_accept','re_reject');
    }elseif($user_role=='clientuser'){
        $arr_main_req_status    = array('approve','re_accept','re_reject');
        $arr_main_req_status_in    = array('new','approve','publish');
   }elseif($user_role == 'approval'){
       if($todoact->request_status=='pm_reject'){
        $arr_main_req_status    = array('client_cancel');
        $arr_main_req_status_in    = array('pm_reject');
       }
       else{
        $arr_main_req_status    = array('approve','client_cancel');
        $arr_main_req_status_in    = array('new');
        
       }
   }
   elseif($user_role == 'reviewer'){
    $arr_main_req_status    = array('re_accept','re_reject');
    $arr_main_req_status_in    = array('publish');
   }else{
    $arr_main_req_status=array();
    $arr_main_req_status_in=array();
   }   
   
   // $arr_diff_req_status    = array_diff($arr_main_req_status,$arr_req_status); 
   if($user_role == 'orgadmin' || $user_role=='projectmanager'){            
    if($todoact->request_status == 'tr_assign'){
        array_unshift($arr_main_req_status, 'tr_inprogress','tr_completed');
    }elseif($todoact->request_status == 'tr_inprogress'){
        array_unshift($arr_main_req_status,'tr_completed');
    }elseif($todoact->request_status == 'v_assign'){
        array_unshift($arr_main_req_status, 'v_inprogress','v_completed');
    }elseif($todoact->request_status == 'v_inprogress'){
        array_unshift($arr_main_req_status,'v_completed');
    }elseif($todoact->request_status == 'qa_assign'){
        array_unshift($arr_main_req_status, 'qa_inprogress','qa_accept','qa_reject');
    }elseif($todoact->request_status == 'qa_inprogress'){
        array_unshift($arr_main_req_status, 'qa_accept','qa_reject');
    }elseif($todoact->request_status == 'pr_assign'){
        array_unshift($arr_main_req_status, 'pr_inprogress','pr_accept','pr_reject');
    }elseif($todoact->request_status == 'pr_inprogress'){
        array_unshift($arr_main_req_status, 'pr_accept','pr_reject');
    }
    }
    $arr_main_req_status=array_unique($arr_main_req_status);
    ?>
     <?php 
if(in_array($todoact->request_status,$arr_main_req_status_in)){
?>

<div class="dropdown dropleft">
  <button class="btn btn-info btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    @foreach($arr_main_req_status as $row)
    @if($row!=$todoact->request_status)
    <a href="javascript:void(0)" class="dropdown-item apply_action_request_status" alert-msg="Are you sure you want to change the Request Status?" reference_id="{{$todoact->req_id}}" request_status="{{$row}}">{{(($row == 'approve' && ($user_role == 'approval' || $user_role == 'clientuser'))? 'Approve' : getcrstatus($row))}}</a>
    @endif
    @endforeach
  </div>
</div>
<?php }?>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<?php if($todoact->request_status !="new"){?>
    <a class="invoice_link" href="{{ route('admin.request.assigntotranslator',['refid'=>$todoact->reference_id]) }}"><i class="fas fa-tasks"></i></a>
  <?php }?>
    @if($todoact->quote_gen_id == '' && checkpermission('qoute_generation','add'))
        <a class="btn btn-xs btn-success" href="{{ route('admin.quotegeneration.createquote',$todoact->req_id) }}">Create Quote</a>
    @endif
                                  
                        <?php 
                            $arr_main_req_status_in=[];
    if($user_role == 'orgadmin' || $user_role=='projectmanager'){
        $arr_main_req_status    = array('approve');
        $arr_main_req_status_in    = array('new');
    }
?>
<?php 
if(in_array($todoact->request_status,$arr_main_req_status_in)){
?>
<div class="dropdown dropleft">
  <button class="btn btn-info btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    @foreach($arr_main_req_status as $row)
    @if($row!=$todoact->request_status)
    <a href="javascript:void(0)" class="dropdown-item apply_action_request_status" alert-msg="Are you sure you want to change the Request Status?" reference_id="{{$todoact->req_id}}" request_status="{{$row}}">{{getcrstatus($row)}}</a>
    @endif
    @endforeach
  </div>
</div>
<?php }?>
@if( $user_role=='orgadmin' || $user_role=='administrator')
<a type="submit"  class="btn btn-danger btn-sm" href="{{ route('admin.request.cancel_request',[$todoact->req_id])}}"><i class="fa fa-trash" aria-hidden="true"></i>
    </a>
    @endif

                            @if(checkpermission('create_request','update'))
                            <?php
                            if(getcrstatus($todoact->request_status=="new")){ ?>
                                <a class="btn btn-xs" href="{{ route('admin.request.editrequest',['refid'=>$todoact->reference_id])}}">
                                    <i class="fa fa-edit text-info"></i>
                                </a><?php } ?>
                                 @endif
                               
                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                </tbody>
            </table>
        </div>


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
                                                <th>Translate to</th>
                                                <th>Assign User</th>
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
                <button class="btn btn-info" >Cancel </button>
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

    var reference_id  = $(this).attr('reference_id');        
    var request_status     = $(this).attr('request_status');
    /*if(request_status == 'tr_assign' || request_status == 'v_assign' || request_status == 'qa_assign' || request_status == 'pr_assign'){
        show_popup(request_status,reference_id);
    }else{*/
        change_status_ajax(reference_id,request_status);
    /*}*/
});
function show_popup(request_status,reference_id) {
        jQuery('#assign_modal').modal('show', {backdrop: 'true'});
        var header ='';
        if(request_status == 'tr_assign'){
            header='Assign To {{ getrolename("translator") }}';
        }else if(request_status == 'v_assign'){
            header='Assign To Vendor';
        }else if(request_status == 'qa_assign'){
            header='Assign To Quality Analist';
        }else if(request_status == 'pr_assign'){
            header='Assign To Proof Reader';
        }
        jQuery('#assign_modal #assign_modal_header').html(header);
        jQuery('#request_id_popup').val(reference_id);
        jQuery('#request_status_popup').val(request_status);
        $.ajax({
            url:"{{ route("admin.request.get_request_assign_data") }}",
            method:"POST",
            data:{"reference_id":reference_id, "request_status":request_status},
            headers: {'x-csrf-token': _token},
            success:function(response){
                $('#assign_user_data').html(response);
                selectRefresh();
                //location.reload(true);
            }
        });
        //jQuery('#assign_modal #success_message').html(msg);
        //etTimeout(function() {$('#success_modal').modal('hide');}, 3000);
    }
    
    function submitassigndata(){
        var dataValid = true;
        var translatiors_list='';
        var work_per_list='';
        var work_per_count=0;
        var amount_per_list='';
        var amount_per_count=0;
        var request_status_popup=$('#request_status_popup').val();
        $("[name^=translators_list_select]").each(function(index, value) {
            if ($(this).val() == '') {
                    $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please select all</div>');
                    $(this).focus();
                    dataValid = false;
                    return false;
            }else{
                var id_work_per = $('input[name="work_per\\[\\]"]').eq(index).val();
                var id_amount_per = $('input[name="amount_per_vendor\\[\\]"]').eq(index).val();
                
                if (id_work_per == "" && request_status_popup == 'v_assign') {
                    $('input[name="work_per\\[\\]"]').eq(index).focus();
                        dataValid = false;
                        return false;
                }else{
                    if (id_amount_per == "" && request_status_popup == 'v_assign') {
                        $('input[name="amount_per_vendor\\[\\]"]').eq(index).focus();
                        dataValid = false;
                        return false;
                    }else{
                        if(translatiors_list == ''){
                            translatiors_list =$(this).val();
                        }else{
                            translatiors_list +=','+$(this).val();
                        }
                        if(work_per_list == ''){
                            work_per_list =id_work_per;
                        }else{
                            work_per_list +=','+id_work_per;
                        }
                        if(amount_per_list == ''){
                            amount_per_list =id_amount_per;
                        }else{
                            amount_per_list +=','+id_amount_per;
                        }
                        work_per_count = parseInt(work_per_count)+ parseInt(id_work_per);
                        amount_per_count = parseInt(amount_per_count)+ parseInt(id_amount_per);
                    }
                } 
            }
        });
        //alert(work_per_count);
        if(request_status_popup == 'v_assign'){
            if(work_per_count == 100){
                
            }else if(work_per_count > 100){
                alert("Work count is not correct it need to be 100% only.");
                dataValid = false;
                return false;
            }else{
                dataValid = false;
                return false;
            }
        }
        if(dataValid == true){
            var reference_id=$('#request_id_popup').val();
            var request_status=$('#request_status_popup').val();
            if((request_status_popup == 'v_assign' && work_per_list !='' && amount_per_list !='' && translatiors_list != '') || translatiors_list != ''){
                change_status_ajax(reference_id,request_status,translatiors_list,work_per_list,amount_per_list);
            }else{
                alert('Something went wrong!');
            }
        }
    }

    function change_status_ajax(reference_id,request_status,assign_data='',assign_work_per='',assign_amount_per=''){
        $.ajax({
            url:"{{ route("admin.request.request_change_request_status") }}",
            method:"POST",
            data:{"reference_id":reference_id, "request_status":request_status,"assign_data":assign_data,"assign_work_per":assign_work_per,"assign_amount_per":assign_amount_per},
            headers: {'x-csrf-token': _token},
            success:function(response){
                location.reload(true);
            }
        });
    }
    function selectRefresh() {
  $('.main .select2').select2({
    tags: true
  });
}
function filterofdaterange(start,end) {
    /*alert(start.format('MMMM D, YYYY'));
    alert(end.format('MMMM D, YYYY'));*/
    var sdate=start.format('Y-M-D');
    var edate=end.format('Y-M-D');
    var status="{{ $req_status }}";
    window.location.href = "{{route('admin.request.todoactivities')}}/?status="+status+"&sdate="+sdate+"&edate="+edate;
}
</script>
@endsection
