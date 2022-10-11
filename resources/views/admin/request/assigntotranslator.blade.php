@extends('layouts.admin')
@section('content')
<div class="card">
    <?php $user_role = Auth::user()->roles[0]->name; 
    $userid=Auth::user()->id;
    $per_word_count=0;
    ?>
    <div class="card-header">
        {{ trans('cruds.locrequest.title_singular') }} {{ trans('global.create') }}
    </div>
<?php //echo "<pre>"; print_r($users);die; ?>
    <div class="card-body">
        <div class="">
        	<!-- <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate=""> -->
                @csrf
                @method('PUT')
                <!-- Source Language block -->
                <div class="row">
                    <div class="col-md-12">
                        <?php $i = 0;
                         $t = $f = 1;
                        ?>
                        <?php 
                        $get_source_lang=$getst_lang->quote_lang_select($loc_get->quote_gen_id);
                      
                      
                        ?>
                        <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                        	<thead>
                        		<tr>
                        			<th>S.No.</th>
                        			
                        			<th>Files</th>
                                    <!-- <th>Target Files</th> -->
                        			<th>Action</th>
                        		</tr>
                        	</thead>
                        	<tbody>
                        <?php $coun=1;$total=0;
                    ?>
                        @foreach($get_source_lang as $get_source)
                      
                        <?php
                    //  echo "<pre/>";  print_r($loc_get);die;
                        $loc_lang_files = DB::table('loc_request_files')->where(['request_id' => $loc_get->req_id,'source_language'=>$get_source->id])->get();
                       
                        $target_lang = DB::table('loc_request_assigned')->where(['request_id' => $loc_get->req_id,'loc_source_id'=>$get_source->id])->get()->toArray();
                      
                       $check_state=1;
                       $check_lang=[];
                    if($user_role == 'vendor'){
                       $v_list_data=DB::table('loc_target_file')->where(['request_id'=>$loc_get->req_id,'v_id'=>$userid])->where('v_id', '!=', '', 'and')->get();
                       $check_lang_v=$v_list_data->toArray();
                      
                       $check_lang = array_column((array)$check_lang_v, 'req_lang_id');
                    }elseif($user_role == 'translator'){
                       $tr_list_data=DB::table('loc_target_file')->where(['request_id'=>$loc_get->req_id,'tr_id'=>$userid])->where('tr_id', '!=', '', 'and')->get();
                       $check_lang_tr=$tr_list_data->toArray();
                       $check_lang = array_column((array)$check_lang_tr, 'req_lang_id');
                    }elseif($user_role == 'qualityanalyst'){
                       $tr_list_data=DB::table('loc_target_file')->where(['request_id'=>$loc_get->req_id,'qa_id'=>$userid])->where('qa_id', '!=', '', 'and')->get();
                       $check_lang_tr=$tr_list_data->toArray();
                       $check_lang = array_column((array)$check_lang_tr, 'req_lang_id');
                    }elseif($user_role == 'proofreader'){
                       $tr_list_data=DB::table('loc_target_file')->where(['request_id'=>$loc_get->req_id,'pr_id'=>$userid])->where('pr_id', '!=', '', 'and')->get();
                       $check_lang_tr=$tr_list_data->toArray();
                       $check_lang = array_column((array)$check_lang_tr, 'req_lang_id');
                    }
                        ?>
                        <?php 
                      
                        foreach ($target_lang as $gtl) {
                            if(($user_role == 'translator' && in_array($gtl->id,$check_lang)) || ($user_role == 'vendor' && in_array($gtl->id,$check_lang)) || ($user_role == 'proofreader' && in_array($gtl->id,$check_lang)) || ($user_role == 'qualityanalyst' && in_array($gtl->id,$check_lang)) || ($user_role != 'translator' && $user_role != 'vendor' && $user_role != 'proofreader' && $user_role != 'qualityanalyst')){
                               
                                $get_service_data=gettabledata('loc_service','service_type',['id'=>$gtl->service_type]);
                             
                                $get_loc_services_type =$get_service_type=gettabledata('loc_service','type',['id'=>$gtl->service_type]);
                             
                                $get_service_type_label='';
                                if($get_service_type == 'minute' || $get_service_type == 'slab_minute'){
                                    $get_service_type_label='Minutes';
                                }elseif($get_service_type == 'word'){
                                    $get_service_type_label='Words';
                                }elseif($get_service_type == 'page'){
                                    $get_service_type_label='pages';
                                }
                              
                        	?>
                            <tr>
                                <td>{{$i+1}}</td>
                                <td colspan="3"><b>LANGUAGE PAIR :</b><span> </span><?php echo $get_service_data.' for '.$get_source->lang_name.' to '.gettabledata('loc_languages','lang_name',['lang_id'=>$gtl->target_language])?></td>
                            </tr>
                            <?php 
                            $j=0;
                          
                            $check_count_where=['request_id' => $loc_get->req_id,'req_lang_id'=>$gtl->id];
                         
                            $unit_count_total=DB::table('loc_target_file')->where($check_count_where)->sum('unit_count');
                         
                            foreach ($loc_lang_files as $llf) {
                             
                                $created_at1=$llf->created_at;
                                $sfile_date=date('Ymd',strtotime($created_at1));

                                $lcf_where=['request_id' => $loc_get->req_id,'req_lang_id'=>$gtl->id,'req_file_id'=>$llf->id];
                                if($user_role == 'translator' || $user_role == 'vendor' || $user_role == 'proofreader' || $user_role == 'qualityanalyst'){
                                    if($user_role == 'translator'){
                                        $lcf_where['tr_id']=$userid;
                                    }elseif($user_role == 'vendor'){
                                        $lcf_where['v_id']=$userid;
                                    }elseif($user_role == 'proofreader'){
                                        $lcf_where['pr_id']=$userid;
                                    }elseif($user_role == 'qualityanalyst'){
                                        $lcf_where['qa_id']=$userid;
                                    }
                                }
                                
                                $lcf=DB::table('loc_target_file')->where($lcf_where)->first();
                          
                                $created_at=$lcf->created_at ?? '';
                                if($created_at !=''){
                                  $tfile=date('Ymd',strtotime($created_at));
                             
                                  }else{
                                    $tfile='';  
                               
                                  }
                                if(($user_role == 'translator' && $lcf && $lcf->tr_id == $userid) || ($user_role == 'vendor' && $lcf && $lcf->v_id == $userid) || ($user_role == 'proofreader' && $lcf && $lcf->pr_id == $userid) || ($user_role == 'qualityanalyst' && $lcf && $lcf->qa_id == $userid) || ($user_role != 'translator' && $user_role != 'vendor' && $user_role != 'proofreader' && $user_role != 'qualityanalyst')){
                               //  echo "<pre/>";   print_r($lcf->qa_status);die;
                             ?>

                                <tr>
                                <td>{{$i+1}}.{{$j+1}}</td>
                                <td id="file_data" value="{{$llf->id}}">Source-File: &nbsp;<?php echo $llf->original_file?><span>&nbsp; <a href="<?php echo env('AWS_CDN_URL') . '/request/source/'.$sfile_date.'/'.$llf->file_name ;?>" download><i class="fa fa-download"></i></a></span><br><?php if(isset($lcf->target_file) && $lcf->target_file != ''){?>Target-File: &nbsp;<?php echo $lcf->original_t_file?><span>&nbsp;<a href="<?php echo env('AWS_CDN_URL') . '/request/target/'.$tfile.'/'.$lcf->target_file;?>"  download><i class="fa fa-download"></i></a></span><?php }?></td>
                            
                                <td> 
                                    <?php 
                                        if($user_role=="projectmanager" || $user_role=="orgadmin"){
                                            //if($lcf == '' || ($lcf->tr_id == '' && $lcf->v_id =='')){
                                                $lcf_where_v_t=['request_id' => $loc_get->req_id,'req_lang_id'=>$gtl->id,'req_file_id'=>$llf->id];
                                                $sql_q=`(v_id != "" OR tr_id != "")`;
                                               
                                                $lcf_v_t=DB::table('loc_target_file')->where($lcf_where_v_t)->where($sql_q)->first();
                                           // echo "<pre/>";print_r($lcf_v_t); die;
                                               if($lcf_v_t == '' || ($lcf_v_t->tr_id == '' && $lcf_v_t->v_id =='')){
                                                $serve_type_label=0;
                                                if($get_loc_services_type == 'word'){
                                                 //   if( $serve_type_label=$gtl->word_count ||  $serve_type_label=$gtl->word_count;)
                                               //   if($gtl== 'word_count') {
                                                    $serve_type_label=$gtl->word_count;

                                                //  }else{
                                                  // $serve_type_label=$gtl->word_fixed_cost;

                                                //  }
                                                 
                                               
                                               
                                                }
                                                elseif($get_loc_services_type == 'minute' || $get_loc_services_type == 'slab_minute'){
                                                   
                                                  //  if($gtl== 'minute_count') {
                                                    $serve_type_label=$gtl->minute_count;
                                             //   }else{
                                                  //  $serve_type_label=$gtl->minute_fixed_cost;

                                                //  }
                                                }elseif($get_loc_services_type == 'page'){
                                                  //  if($gtl== 'page_count') {
                                                    $serve_type_label=$gtl->page_count;
                                                // }else{
                                                   // $serve_type_label=$gtl->page_fixed_cost;

                                                 // }
                                                }
                                              // print_r($get_loc_services_type);
                                            //   print_r($serve_type_label);
                                             //  print_r($lcf_v_t);
                                                   ?>  
                                                    <input type="button" class="btn btn-success btn-sm apply_action_request_status"  id="button_data_{{$i}}" data-type="translator_{{$i}}" data-status_type='tr_assign' data-file_id="{{$llf->id}}" data-reference_id="{{$loc_get->req_id}}" data-req_lang_id="{{$gtl->id}}" data-req_added_unit_count="{{ $unit_count_total ?? 0 }}" data-unit_count_compar="{{ $serve_type_label-$unit_count_total }}" data-req_unit_count="{{ $serve_type_label >0? $serve_type_label : 0 }}" data-req_service="{{ $get_loc_services_type }}" value="Assign {{ getrolename('translator') }}" alert-msg="Are you sure you want to change the Request Status?">
                                                    <!-- <input class="btn btn-success btn-sm apply_action_request_status" id="button_data" data-type="vendor_{{$i}}" data-status_type="v_assign" data-file_id="{{$llf->id}}" data-reference_id="{{$loc_get->req_id}}" data-req_lang_id="{{$gtl->id}}"   type="button" value="Assign {{ getrolename('vendor') }}" alert-msg="Are you sure you want to change the Request Status?"> -->
                                                    
                                                    <?php }elseif($lcf_v_t && $lcf_v_t->tr_id != '' && $user_role != 'translator'){
                                                        //$unit_count_g_total.'_'.$i=$unit_count_g_total.'_'.$i+$lcf_v_t->unit_count;
                                                     // echo "<pre/>";  print_r($lcf_v_t); die;
                                                       ?>
                                                     
                                                        <input class="btn btn-success btn-sm getuserdetails" data-status_type='tr_assign' data-tr_name="{{getusernamebyid($lcf_v_t->tr_id)}}" id="button_value_{{$i}}" data-toggle="modal" data-v_amount="{{ checkcurrency($lcf_v_t->v_total,$lcf_v_t->currency_id) }}"   data-type="tr_{{$i}}" data-status="{{$lcf_v_t->tr_status}}" data-assigned_date="{{$lcf_v_t->tr_assigned_date}}"   data-tr_id="{{$lcf_v_t->tr_id}}"   data-currency_id="{{ strtoupper(gettabledata('currencies','currency_name',['id'=>$lcf_v_t->currency_id])).' - '.strtoupper(gettabledata('currencies','currency_code',['id'=>$lcf_v_t->currency_id])) }}" data-unit_count="{{$lcf_v_t->unit_count.' '.$get_service_type_label}}"  data-per_unit="{{ checkcurrency($lcf_v_t->per_unit,$lcf_v_t->currency_id) }}" type="button" value="Assigned to {{ getrolename('translator') }}">
                                                    <?php }elseif($lcf_v_t && $lcf_v_t->v_id != '' && $user_role != 'vendor'){
                                                        //$unit_count_g_total.'_'.$i=$unit_count_g_total.'_'.$i+$lcf_v_t->unit_count;
                                                      ?>
                                                        <input class="btn btn-success btn-sm getuserdetails"  data-status_type='v_assign' data-tr_name="{{getusernamebyid($lcf_v_t->v_id)}}" id="button_value_{{$i}}" data-v_amount="{{ checkcurrency($lcf_v_t->v_total,$lcf_v_t->currency_id) }}" data-type="v_{{$i}}"  data-status="{{$lcf_v_t->v_status}}" data-assigned_date="{{$lcf_v_t->v_assigned_date}}"   data-tr_id="{{$lcf_v_t->v_id}}" data-currency_id="{{ strtoupper(gettabledata('currencies','currency_name',['id'=>$lcf_v_t->currency_id])).' - '.strtoupper(gettabledata('currencies','currency_code',['id'=>$lcf_v_t->currency_id])) }}"   data-unit_count="{{$lcf_v_t->unit_count.' '.$get_service_type_label}}"  data-per_unit="{{ checkcurrency($lcf_v_t->per_unit,$lcf_v_t->currency_id) }}"  type="button" value="Assigned to {{ getrolename('vendor') }}">
                                                    <?php } 
                                            //}
                                        }
                                    ?>
                                    <?php if($user_role=="projectmanager" || $user_role=="orgadmin"){
                                        $lcf_where_v_t=['request_id' => $loc_get->req_id,'req_lang_id'=>$gtl->id,'req_file_id'=>$llf->id];
                                        $sql_qa=`('qa_id','!=','')`;
                                        $lcf_qa=DB::table('loc_target_file')->where($lcf_where_v_t)->where($sql_qa)->first();
                                              
                                        $sql_pr=`('pr_id','!=','')`;
                                        $lcf_pr=DB::table('loc_target_file')->where($lcf_where_v_t)->where($sql_pr)->first();
                                         
                                      ?>
                                        <?php if($lcf_qa == '' || ($lcf_qa->qa_id == '' )) {?>
                                        <input class="btn btn-primary btn-sm apply_action_request_status" id="button_data" data-type="qa_{{$i}}" data-status_type="qa_assign" data-file_id="{{$llf->id}}" data-reference_id="{{$loc_get->req_id}}" data-req_lang_id="{{$gtl->id}}"   type="button" value="Assign QA" alert-msg="Are you sure you want to change the Request Status?">
                                       
                                        <?php }elseif($lcf_qa && $lcf_qa->qa_id != '' && $user_role != 'qualityanalyst'){?>
                                           
                                        <input class="btn btn-primary btn-sm getuserdetails" data-status_type='qa_assign' data-tr_name="{{getusernamebyid($lcf_qa->qa_id)}}" id="button_value_{{$i}}" data-toggle="modal"  data-type="qa_{{$i}}" data-status="{{$lcf_qa->qa_status}}" data-assigned_date="{{$lcf_qa->qa_assigned_date}}"   data-tr_id="{{$lcf_qa->qa_id}}" type="button" value="Assigned to QA">
                                        <?php }?>

                                        <?php if($lcf_pr == '' || ($lcf_pr->pr_id == '' )) {?>
                                        <input class="btn btn-info btn-sm apply_action_request_status" id="button_data" data-type="pr_{{$i}}" data-status_type="pr_assign" data-file_id="{{$llf->id}}" data-reference_id="{{$loc_get->req_id}}" data-req_lang_id="{{$gtl->id}}"   type="button" value="Assign PR" alert-msg="Are you sure you want to change the Request Status?">
                                        <?php }elseif($lcf_pr && $lcf_pr->pr_id != '' && $user_role != 'proofreader'){?>
                                            
                                        <input class="btn btn-info btn-sm getuserdetails" data-status_type='pr_assign' data-tr_name="{{getusernamebyid($lcf_pr->pr_id)}}" id="button_value_{{$i}}" data-toggle="modal"  data-type="pr_{{$i}}" data-status="{{$lcf_pr->pr_status}}" data-assigned_date="{{$lcf_pr->pr_assigned_date}}"   data-tr_id="{{$lcf_pr->pr_id}}" type="button" value="Assigned to PR">
                                        <?php }
                                    } ?>
                                    <a href="{{ route('admin.request.add_comment',[$llf->id,$gtl->id]) }}" class="btn btn-warning btn-sm" value="Comments">Comments</a>
                                    
                                    <a href="{{ route('admin.translationcsvfile.projecte_file_translation_memory',[$llf->id]) }}" class="btn btn-warning btn-sm" value="File Translation">File Translate</a>
                                </td>
                           
                            </tr>
                            <?php $j++;}}?><tr><td colspan="5"></td></tr><?php }$i++;}?>
                        @endforeach
                    		</tbody>
                           
                        </table>
                    </div>
                    <div class="col-md-6">&nbsp;</div>
                    <div class="col-md-6">
                        <!-- <input class="btn btn-danger" id="translation_submit" type="submit" value="{{ trans('global.submit') }}"> -->
                    </div>
            <!-- </form> -->
        </div>
    </div>
</div>
<div id="assign_modal" class="modal fade" role="dialog" >
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
                                                <!-- <th>Translate to</th> -->
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
                <input id="file_id_popup" value="" type="hidden"/>
                <input id="req_id_popup" value="" type="hidden"/>
                <input id="req_lang_id_popup" value="" type="hidden"/>
                <input id="request_status_popup" value="" type="hidden"/>
                <input id="req_added_unit_count" value="" type="hidden"/>
                <input id="req_unit_count" value="" type="hidden"/>
                <input id="req_service" value="" type="hidden"/>
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="submitassigndata()">Submit</button>
                <button class="btn btn-info" data-dismiss="modal" >Cancel </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Assigned User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="exampleModalCenter_body">
       
      </div>
      <!-- <div class="modal-footer"> -->
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button> -->
      <!-- </div> -->
    </div>
  </div>
</div>

@endsection
@section('scripts')
@parent
<script>
  $('.apply_action_request_status').on('click', function (id) {
 var con = confirm($(this).attr('alert-msg'));
         if(con == false)
             return false;

    var fileid=$(this).data('file_id');
    var req_id=$(this).data('reference_id');
    var req_lang_id=$(this).data('req_lang_id');
    var trAssign=$(this).data('status_type');
    var assignType=$(this).data('type');
    var req_added_unit_count=$(this).data('req_added_unit_count');
    var req_service=$(this).data('req_service');
    var req_unit_count=$(this).data('req_unit_count');
    
    show_popup(fileid,trAssign,req_id,req_lang_id,req_added_unit_count,req_unit_count,req_service);
});
function submitassigndata(){
  
    var dataValid = true;
    var vendor_list='';
     var work_per_count=0;
    var amount_per_list='';
    var amount_per_count=0;
    var req_unit_count_list='';
    var req_unit_count=0;
    var req_per_unit_list='';
    var req_per_unit=0;
    var gst=$("#assign_gst").val();
    var currency='';
    var unit_count='';
    var per_unit='';

    var fileid=$('#file_id_popup').val();
    var req_id=$('#req_id_popup').val();
    var req_lang_id=$('#req_lang_id_popup').val();
    var trAssign=$('#request_status_popup').val();
    var req_added_unit_count=$('#req_added_unit_count').val();
    var req_service=$('#req_service').val();
    

    var req_unit_count_main=$('#req_unit_count').val();
   // alert(trAssign);
   // alert(req_service); 

    if(trAssign == 'tr_assign'){
        var type_l='tr';
        currency=$("#currency").val();
        unit_count=$("#unit_count").val();
        per_unit=$("#per_unit").val();
    }else if(trAssign == 'v_assign'){
        var type_l='v';
        currency=$("#currency_id").val();
        unit_count=$("#unit_count").val();
        per_unit=$("#per_unit").val();

    }else if(trAssign == 'qa_assign'){
        var type_l='qa';
        
    }else if(trAssign == 'pr_assign'){
        var type_l='pr';
        
    }
    
        $("[name^=tr_list_select]").each(function(index, value) {
            if ($(this).val() == '') {
                    //$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please select all</div>');
                    toastr.error("Please enter all fields.");
                    $(this).focus();
                    dataValid = false;
                    return false;
             }
            else{
               // var id_work_per = $('input[name="work_per"]').eq(index).val();
                var id_unit_count = $('input[name="unit_count"]').eq(index).val();
            //  alert(id_unit_count);
                var id_per_unit = $('input[name="per_unit"]').eq(index).val();
                var id_amount_per = $('input[name="amount_per_vendor"]').eq(index).val();
               
                // if (id_work_per == "" && trAssign == 'v_assign') {
                //     $('input[name="work_per"]').eq(index).focus();
                //         dataValid = false;
                //         return false;
                // }else{
                    if (id_unit_count == "" && (trAssign == 'v_assign' || trAssign == 'tr_assign')) {
                        $('input[name="unit_count"]').eq(index).focus();
                        dataValid = false;
                        return false;
                    }else{
                        if (id_per_unit == "" && (trAssign == 'v_assign' || trAssign == 'tr_assign')) {
                        $('input[name="per_unit"]').eq(index).focus();
                        dataValid = false;
                        return false;
                    }else{
                        if (id_amount_per == "" && (trAssign == 'v_assign' || trAssign == 'tr_assign')) {
                        $('input[name="amount_per_vendor"]').eq(index).focus();
                        dataValid = false;
                        return false;
                    }else{
                        if(vendor_list == ''){
                            vendor_list =$(this).val();
                        }else{
                            vendor_list +=','+$(this).val();
                        }
                       // if(work_per_list == ''){
                        //     work_per_list =id_work_per;
                        // }else{
                        //     work_per_list +=','+id_work_per;
                        // }
                        if(req_unit_count_list == ''){
                            req_unit_count_list = id_unit_count;
                        }else{
                            req_unit_count_list +=','+id_unit_count;
                        }
                        if(req_per_unit_list == ''){
                            req_per_unit_list = id_per_unit;
                        }else{
                            req_per_unit_list +=','+id_per_unit;
                        }
                        if(amount_per_list == ''){
                            amount_per_list = id_amount_per;
                        }else{
                            amount_per_list +=','+id_amount_per;
                        }
                        //work_per_count = parseInt(work_per_count)+ parseInt(id_work_per);
                        req_unit_count = parseInt(req_unit_count)+ parseInt(id_unit_count);
                        req_per_unit = parseInt(req_per_unit)+ parseInt(id_per_unit);
                        amount_per_count = parseInt(amount_per_count)+ parseInt(id_amount_per);
                    }  
                    }     
                    }
               // }
            }   
        });
        // if(trAssign == 'v_assign'){
        //     var per_work_count=parseInt('<?php echo $per_word_count;?>');
             
        //     var t_workper=parseInt(per_work_count)+parseInt(work_per_count);
        //     if(work_per_count <= 100 && t_workper <= 100){
        //         //  var percentage=((100-t_workper);
        //         // alert(per_work_count);
              
                
        //     }else if(work_per_count > 100 || t_workper > 100){
        //         alert("Work count is not correct it need to be 100% only you have already assigned "+per_work_count+"% .");
        //         dataValid = false;
        //         return false;
        //     }else{
        //         dataValid = false;
        //         return false;
        //     }
        // }
        if(trAssign == 'v_assign' || trAssign == 'tr_assign'){
             //  alert(per_unit_count);
              
            var per_unit_count=parseInt(req_added_unit_count);
            var t_workper=parseInt(per_unit_count)+parseInt(req_unit_count);
            if(req_unit_count <= parseInt(req_unit_count_main) && t_workper <= parseInt(req_unit_count_main)){
                // var percentage=((100-t_workper);
             
                
            }else if(req_unit_count > parseInt(req_unit_count_main) || t_workper > parseInt(req_unit_count_main)){
                toastr.error("Count should not be more than "+(parseInt(req_unit_count_main)-parseInt(per_unit_count))+" .");
                dataValid = false;
                return false;
            }else{
                dataValid = false;
                return false;
            }
        }
        if(dataValid == true){
            if(trAssign == 'pr_assign' ||trAssign == 'qa_assign' && vendor_list != ''){
                change_status_ajax(fileid,trAssign,req_id,req_lang_id,vendor_list);
            }else if((trAssign == 'v_assign' || trAssign == 'tr_assign') && amount_per_list !='' && vendor_list != ''){
                change_status_ajax(fileid,trAssign,req_id,req_lang_id,vendor_list,amount_per_list,gst,currency,unit_count,per_unit,req_unit_count_list,req_per_unit_list);
         }
        }
    }
function show_popup(fileid,trAssign,req_id,req_lang_id,req_added_unit_count,req_unit_count,req_service) {
    const uppercaseWords = str => str.replace(/^(.)|\s+(.)/g, c => c.toUpperCase());
        jQuery('#assign_modal').modal('show', {backdrop: 'true'});
        var header ='';
        jQuery('#assign_modal #assign_modal_header').html(header);
        jQuery('#file_id_popup').val(fileid);
        jQuery('#req_id_popup').val(req_id);
        jQuery('#req_lang_id_popup').val(req_lang_id);
        jQuery('#request_status_popup').val(trAssign);
        jQuery('#req_added_unit_count').val(req_added_unit_count);
        jQuery('#req_unit_count').val(req_unit_count);
        selectRefresh();
        if(trAssign=="tr_assign" ||trAssign=="v_assign" ){
            translator=<?php echo $translators;?>;   
        var options='<select  class="form-control select2 translators_list_select" id="tr_list_select" name="tr_list_select" required><option value="">Select User</option>';
        for(var i=0;i<translator.length;i++){
            options +='<option value="'+translator[i].id+'">'+translator[i].name+'</option>';
        }
   
        options +='</select><select id="assign_gst" name="assign_gst" onchange="gstchange(this.value)" class="form-control select2" required><option value="gst">GST</option><option value="both">SGST & CGST</option><option value="no_gst">No GST</option></select><select name="currency" id="currency" class="form-control select2" required>@foreach($currency_list as $cl)<option value="{{$cl->id}}">{{$cl->currency_name}}</option>@endforeach</select><input type="number" name="unit_count" id="unit_count" onkeyup="unit_count(this.value)" class="form-control" value="" placeholder="'+uppercaseWords(req_service.replace("_", " "))+' Count" /><input type="number" name="per_unit" id="per_unit" onkeyup="per_unit(this.value)" placeholder="Per Unit" class="form-control"><input type="number" name="amount_per_vendor" id="amount_per_vendor" min="0.1" class="form-control" required placeholder="Please enter amount" readonly />';

        $('#assign_user_data').html(options);
        }
       else if(trAssign=="qa_assign"){
        qualityanalysts=<?php echo $qualityanalyst;?>;   
        var options='<select  class="form-control select2 translators_list_select" name="tr_list_select" required><option value="">Select User</option>';
        for(var i=0;i<qualityanalysts.length;i++){
            options +='<option value="'+qualityanalysts[i].id+'">'+qualityanalysts[i].name+'</option>';
        }

      options +='</select>';

        $('#assign_user_data').html(options);
        }
       else if(trAssign=="pr_assign"){
        proofreaders=<?php echo $proofreader;?>;   
        var options='<select  class="form-control select2 translators_list_select" name="tr_list_select" required><option value="">Select User</option>';
        for(var i=0;i<proofreaders.length;i++){
            options +='<option value="'+proofreaders[i].id+'">'+proofreaders[i].name+'</option>';
        }
   
        options +='</select>';

        $('#assign_user_data').html(options);
        }
        else if(trAssign=="v_assign"){
            vendors=<?php echo $vendors;?>;

            var options='<select  class="form-control select2 translators_list_select" name="tr_list_select" required><option value="">Select User</option>';
            for(var i=0;i<vendors.length;i++){
                options +='<option value="'+vendors[i].id+'">'+vendors[i].name+'</option>';
            }
            options +='</select><input type="number" name="amount_per_vendor" min="0.1" class="form-control" required placeholder="Please enter amount"/><select id="assign_gst" name="assign_gst" class="form-control select2" required><option value="gst">GST</option><option value="both">SGST & CGST</option><option value="no_gst">No GST</option></select><select name="currency" id="currency_id" class="form-control select2" required>@foreach($currency_list as $cl)<option value="{{$cl->id}}">{{$cl->currency_name}}</option>@endforeach</select>';

            $('#assign_user_data').html(options);}
        }
        
    
    
    function change_status_ajax(fileid,trAssign,req_id,req_lang_id,assign_data='',assign_amount_per='',gst='',currency='',unit_count='',per_unit='',req_unit_count_list='',req_per_unit_list=''){
    //    alert(fileid);
    //    alert(trAssign);
    //    alert(req_id);
    //    alert(req_lang_id);
    //    alert(assign_data);
    //    alert(assign_amount_per);
    //    alert(gst);
    //    alert(currency);
    //    alert(unit_count);
    //    alert(per_unit);
    //    alert(req_unit_count_list);
    //    alert(req_per_unit_list);
        $.ajax({
            url:"{{ route("admin.request.trrequestupdate") }}",
            method:"POST",
            data:{"fileid":fileid, "req_id":req_id, "req_lang_id":req_lang_id, "trAssign":trAssign, "assign_data":assign_data,"assign_amount_per":assign_amount_per,"gst":gst,"currency":currency,"unit_count":unit_count,"per_unit":per_unit},
            headers: {'x-csrf-token': _token},
            success:function(response){
                location.reload(true);
            }
        });
    }
    $('.getuserdetails').on('click', function (id) {
       //  alert(unit_count);
        // alert(status);
        

        var trAssign=$(this).data('status_type');
        var v_amount=$(this).data('v_amount');
        var t_amount=$(this).data('v_amount');
        var currency_id=$(this).data('currency_id');
        var unit_count=$(this).data('unit_count');
        var   per_unit=$(this).data('per_unit');
        var type=$(this).data('type');
       
        //var v_work=$(this).data('v_work');
        var status=$(this).data('status');
       
        var assigned_date=$(this).data('assigned_date');
        var tr_id=$(this).data('tr_id');
      
       // var tr_id=$(this).data('tr_id');

        var tr_name=$(this).data('tr_name');
        var html="";
        jQuery('#exampleModalCenter').modal('show', {backdrop: 'true'});
        //alert(trAssign);
    if(trAssign=="v_assign" || trAssign=="tr_assign"){//alert(trAssign);
        html +='<ul><li><b>Name: </b>'+tr_name+'</li>';
        html +='<li><b>Date: </b>'+assigned_date+'</li>';
        // html +='<li><b>Status: </b>'+(trAssign == "tr_status") ? +status+' % Completed'+ +'"Assign"</li>';
         if(status !=''){
        html +='<li><b>Status: </b>'+status+' % completed </li>';
         }else{
            html +='<li><b>Status: </b>assign </li>';

         }
       // html +='<li><b>work %: </b>'+v_work+'%</li>';
        html +='<li><b>Total Amount: </b>'+v_amount+'</li>';
        html +='<li><b>Currency: </b>'+currency_id+'</li>';
        html +='<li><b>Unit Count: </b>'+unit_count+'</li>';
        html +='<li><b>Per Unit: </b>'+per_unit+'</li>';
        html +='</ul>';        
    }/*else if(trAssign=="tr_assign"){
        html +='<ul><li><b>Name: </b>'+tr_name+'</li>';
        html +='<li><b>Date: </b>'+assigned_date+'</li>';
        html +='<li><b>Status: </b>'+status+'% Completed</li>';
        html +='<li><b>Total Amount: </b>'+t_amount+'Rs.</li>';
        html +='</ul>';
    }*/else if(trAssign=="qa_assign"){
        html +='<ul><li><b>Name: </b>'+tr_name+'</li>';
        html +='<li><b>Date: </b>'+assigned_date+'</li>';
        if(status !=''){
        html +='<li><b>Status: </b>'+status+' % completed </li>';
         }else{
            html +='<li><b>Status: </b>assign </li>';

         }
        html +='</ul>';
    }else if(trAssign=="pr_assign"){
        html +='<ul><li><b>Name: </b>'+tr_name+'</li>';
        html +='<li><b>Date: </b>'+assigned_date+'</li>';
        if(status !=''){
        html +='<li><b>Status: </b>'+status+' % completed </li>';
         }else{
            html +='<li><b>Status: </b> assign </li>';

         }
        html +='</ul>';
    }
    $('#exampleModalCenter_body').html(html);
    });
    function showdata(tr_id,tr_name,tr_assigned_date){
        
    }
    function gstchange(value){
        var unit_count=$('#unit_count').val();
        var per_unit=$('#per_unit').val();
        calucalte_amount(unit_count,per_unit); 
    }
    function per_unit(value){
      var unit_count=$('#unit_count').val();
      calucalte_amount(unit_count,value);  

    }
    function unit_count(value){
        var per_unit=$('#per_unit').val();
        calucalte_amount(value,per_unit);
    }
    function calucalte_amount(unit_count,per_unit){
        var assign_gst=$('#assign_gst').val();
        var amount=unit_count*per_unit;
        if(assign_gst == 'no_gst'){
            var total=amount;
        }else{
            var total=amount+((amount*18)/100);
        }
        $('#amount_per_vendor').val(total);
    }


    function selectRefresh() {
       $('.translators_list_select').select2({
           tags: true,
           dropdownParent: $('#assign_modal')
           /*placeholder: "Select an Option"*/
       });
   }

</script>
@endsection

