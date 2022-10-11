@extends('layouts.admin')
@section('content')
<?php $user_role = Auth::user()->roles[0]->name; ?>
@if(checkpermission('create_request','add'))
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route("admin.request.create") }}">
            {{ trans('global.add') }} {{ trans('cruds.locrequest.title_singular') }}
        </a>
    </div>
</div>
@endif
<div class="card">
    <div class="card-header">
        {{ trans('cruds.locrequest.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable_table">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.locrequest.fields.request_id') }}
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
						
						<!-- <th>
                            {{ trans('cruds.locrequest.fields.updated_at') }}
                        </th> -->				
						
						
                        <th>
                           Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0;?>
                    @foreach($marketing_campaign as $key => $mcampaign)
                  
                         <tr data-entry-id="{{ $mcampaign->mk_campign_id }}">
                            <td>
                                {{$i+1}}
                            </td>  
                            <td>
                                <?php //if($user_role =="translator" || $user_role == "projectmanager" || $user_role =="qualityanalyst" || $user_role =="proofreader"){ ?>
                                <!-- <a href="request/{{$mcampaign->reference_id}}" > --><a href="{{ route('admin.request.requestupdate',['refid'=>$mcampaign->reference_id]) }}" >{{ $mcampaign->reference_id ?? '' }}
                                </a>
                                <?php //}else{
                                     ?>
                                    <!-- <a href="request/{{$mcampaign->reference_id}}/edit" >{{ $mcampaign->reference_id }}</a> -->
                                      <!-- <a href="#" >{{ $mcampaign->reference_id ?? '' }}</a> -->
                                    <?php //}?>
                            </td>
                           
                            <!-- <td> -->
                            	<?php
                               // $sl_tl = $loc_request->loc_reference_lang_select($mcampaign->req_id, 'sl_tl');
                                //    foreach ($sl_tl as $sl){
                                    //echo '<li>'.$sl->source_lang_name .' - '. $sl->target_lang_name.'</li>';
                                //}
                            	?>
                            <!-- </td> -->
                            <td>
                            	{{ showcrstatus($mcampaign->request_status) ?? '' }}
                            </td>
                            <td>
                                {{ date("j M, Y h:i A",strtotime($mcampaign->created_time)) }}
                            </td>
                            <!-- <td>
                                {{ $mcampaign->created_time ?? '' }}
                            </td> -->
                            <td>
                            <?php 
                            $arr_main_req_status_in=[];
    if($user_role == 'orgadmin' || $user_role=='projectmanager'){
        $arr_main_req_status    = array('approve');
        $arr_main_req_status_in    = array('new');
    }
?>
<?php 
if(in_array($mcampaign->request_status,$arr_main_req_status_in)){
?>
<div class="dropdown dropleft">
  <button class="btn btn-info btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    @foreach($arr_main_req_status as $row)
    @if($row!=$mcampaign->request_status)
    <a href="javascript:void(0)" class="dropdown-item apply_action_request_status" alert-msg="Are you sure you want to change the Request Status?" reference_id="{{$mcampaign->req_id}}" request_status="{{$row}}">{{getcrstatus($row)}}</a>
    @endif
    @endforeach
  </div>
</div>
<?php }?>

                            @if(checkpermission('create_request','update'))
                            <?php
                            if(getcrstatus($mcampaign->request_status=="new")){ ?>
                                <a class="btn btn-xs" href="{{ route('admin.request.editrequest',['refid'=>$mcampaign->reference_id])}}">
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
    if(request_status == 'tr_assign' || request_status == 'qa_assign' || request_status == 'pr_assign'){
        show_popup(request_status,reference_id);
    }else{
        change_status_ajax(reference_id,request_status);
    }
});
function change_status_ajax(reference_id,request_status,assign_data=''){
        $.ajax({
            url:"{{ route("admin.request.request_change_request_status") }}",
            method:"POST",
            data:{"reference_id":reference_id, "request_status":request_status,"assign_data":assign_data},
            headers: {'x-csrf-token': _token},
            success:function(response){
            location.reload(true);
            }
        });
    }
</script>
@endsection
