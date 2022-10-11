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
    <div class="card-body">
        <div class="">
        <div class="col-md-2">
          <select  class="form-control select2" onchange="change_vendor(this.value)">
            <option value="">Select User</option>
              <?php for($i1=0;$i1<count($translators);$i1++){?>
                <option value="{{$translators[$i1]->id}}">{{$translators[$i1]->name}}</option>
              <?php }?>
          </select>
        </div>
        	<form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="" action="{{ route("admin.request.bulkassigntr") }}">
                @csrf
                @method('POST')
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
                        			<th>Action</th>
                        		</tr>
                        	</thead>
                        	<tbody>
                        <?php $coun=1;$total=0;
                    ?>
                        @foreach($get_source_lang as $get_source)
                        <?php
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
                                    $per_unit_i=$gtl->per_minute_cost;
                                }elseif($get_service_type == 'word'){
                                    $get_service_type_label='Words';
                                    $per_unit_i=$gtl->per_word_cost;
                                }elseif($get_service_type == 'page'){
                                    $get_service_type_label='Pages';
                                    $per_unit_i=$gtl->per_page_cost;
                                }elseif($get_service_type == 'resource'){
                                    $get_service_type_label='Resource';
                                    $per_unit_i=$gtl->cost_per_resource;
                                }else{
                                  $per_unit_i=0;
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
                             ?>
                                <tr>
                                <td>{{$i+1}}.{{$j+1}}</td>
                                <td id="file_data" value="{{$llf->id}}">Source-File: &nbsp;<?php echo $llf->original_file?><span>&nbsp; <a href="<?php echo env('AWS_CDN_URL') . '/request/source/'.$sfile_date.'/'.$llf->file_name ;?>" download><i class="fa fa-download"></i></a></span><br><?php if(isset($lcf->target_file) && $lcf->target_file != ''){?>Target-File: &nbsp;<?php echo $lcf->original_t_file?><span>&nbsp;<a href="<?php echo env('AWS_CDN_URL') . '/request/target/'.$tfile.'/'.$lcf->target_file;?>"  download><i class="fa fa-download"></i></a></span><?php }?></td>
                            
                                <td> 
                                    <?php 
                                  if($user_role=="projectmanager" || $user_role=="orgadmin"){
                                                $lcf_where_v_t=['request_id' => $loc_get->req_id,'req_lang_id'=>$gtl->id,'req_file_id'=>$llf->id];
                                                $sql_q=`(v_id != "" OR tr_id != "")`;
                                               
                                                $lcf_v_t_list=DB::table('loc_target_file')->where($lcf_where_v_t)->where($sql_q)->get();
                            if($lcf_v_t_list == '' || count($lcf_v_t_list) == 0){
                                                $serve_type_label=0;
                                                if($get_loc_services_type == 'word'){
                                                    $serve_type_label=$gtl->word_count;
                                                }
                                                elseif($get_loc_services_type == 'minute' || $get_loc_services_type == 'slab_minute'){
                                                    $serve_type_label=$gtl->minute_count;
                                                }elseif($get_loc_services_type == 'page'){
                                                    $serve_type_label=$gtl->page_count;
                                                }
                                                
                                                   ?>  
      <div id="addmorevendor_{{$llf->id}}">
      <div class="row">
      <div class="col-md-11">
      <div class="row">
        <div class="col-md-2">
          <input type="hidden" name="source_file_ids[]" value="{{$llf->id}}">
          <input type="hidden" name="request_lang_id[{{$llf->id}}]" value="{{$gtl->id}}">
          <input type="hidden" name="req_id" value="{{$loc_get->req_id}}">
          <select  class="form-control select2" id="tr_list_select_{{$llf->id}}_0" name="tr_list_select[{{$llf->id}}][]" required>
            <option value="">Select User</option>
              <?php for($i1=0;$i1<count($translators);$i1++){?>
                <option value="{{$translators[$i1]->id}}">{{$translators[$i1]->name}}</option>
              <?php }?>
          </select>
        </div>
        <div class="col-md-2">
          <select id="assign_gst_{{$llf->id}}_0" name="assign_gst[{{$llf->id}}][]" onchange="gstchange1(this.value,'{{$llf->id}}',0)" class="form-control select2" required>
            <option value="gst">GST</option>
            <option value="both">SGST & CGST</option>
            <option value="no_gst">No GST</option>
          </select>
        </div>
        <div class="col-md-2">
          <select name="currency[{{$llf->id}}][]" id="currency_{{$llf->id}}_0" class="form-control select2" required>
            @foreach($currency_list as $cl)
              <option value="{{$cl->id}}">{{$cl->currency_name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <input type="number" name="unit_count[{{$llf->id}}][]" id="unit_count_{{$llf->id}}_0" onkeyup="unit_count1(this.value,'{{$llf->id}}',0)" class="form-control" value="" placeholder="{{str_replace('_',' ',ucwords($get_loc_services_type))}} Count" min="1" required="" />
          <input id="req_service_{{$llf->id}}" value="{{str_replace('_',' ',ucwords($get_loc_services_type))}} Count" type="hidden"/>
        </div>
        <div class="col-md-2">
          <input type="number" name="per_unit[{{$llf->id}}][]" id="per_unit_{{$llf->id}}_0" onkeyup="per_unit1(this.value,'{{$llf->id}}',0)" placeholder="Per Unit" class="form-control" min="0" step="0.1" required="" value="{{$per_unit_i}}">
        </div>
        <div class="col-md-2">
          <input type="number" name="amount_per_vendor[{{$llf->id}}][]" id="amount_per_vendor_{{$llf->id}}_0" min="0.1" class="form-control amount_list" required placeholder="Total" readonly/>
        </div>
      </div>   
      </div>
      <div class="col-md-1">
      <button type="button" class="btn btn-success btn-xs" id="id_more_upload_0" onclick="add_more_upload('{{$llf->id}}',0)" value="" name="submit"> <i class="fas fa-plus"></i> </button>
      </div> 
      </div>   
              </div>                       
                        <?php }else{
                       $i = 1;
                          ?>

<table class=" table table-bordered table-striped table-hover datatable datatable-User">
                        	<thead>
                        		<tr>
                        			<th>S.No.</th>
                        			<th>Translator</th>
                        			<th>Gst</th>
                        			<th>Unit Count</th>
                        			<th>Per Unit</th>
                        			<th>Total Amount</th>
                        		</tr>
                        	</thead>
                        <?php
                        
                          foreach ($lcf_v_t_list as $lcf_v_t) {
                      //  echo "<pre/>";  print_r($lcf_v_t);die;
                          if($lcf_v_t && $lcf_v_t->tr_id != '' && $user_role != 'translator'){
                            ?>
                            <tbody>
                              <tr>
                                <td>{{$i++}}</td>
                                <td>{{getusernamebyid($lcf_v_t->tr_id)}}</td>
                                <td><?php if($lcf_v_t->v_gst == 'gst'){
                                  echo "GST";
                                }elseif($lcf_v_t->v_gst == 'both'){echo "SGST&CGST";}else{echo "No GST";}?></td>
                                <td>{{$lcf_v_t->unit_count.' '.str_replace('_',' ',ucwords($get_loc_services_type)).'s'}}</td>
                                <td>{{checkcurrency($lcf_v_t->per_unit,$lcf_v_t->currency_id)}}</td>
                                <td>{{checkcurrency($lcf_v_t->v_total,$lcf_v_t->currency_id)}}</td>
                              </tr>
                            <?php
                            }elseif($lcf_v_t && $lcf_v_t->v_id != '' && $user_role != 'vendor'){
                              ?>
                            
                            <tbody>
                              <tr>
                                <td>{{$i++}}</td>
                                <td>{{getusernamebyid($lcf_v_t->v_id)}}</td>
                                <td><?php if($lcf_v_t->v_gst == 'gst'){
                                  echo "GST";
                                }elseif($lcf_v_t->v_gst == 'both'){echo "SGST&CGST";}else{echo "No GST";}?></td>
                                <td>{{$lcf_v_t->unit_count.' '.str_replace('_',' ',ucwords($get_loc_services_type)).'s'}}</td>
                                <td>{{checkcurrency($lcf_v_t->per_unit,$lcf_v_t->currency_id)}}</td>
                                <td>{{checkcurrency($lcf_v_t->v_total,$lcf_v_t->currency_id)}}</td>
                              </tr>
                            <?php
                          }                    
                        }?>
                           
                        </tbody>  
                        </table>
                        <?php }}
                                    ?>
                                        
                                </td>
                           
                            </tr>
                            <?php $j++;}}?><tr><td colspan="5"></td></tr><?php }$i++;}?>
                        @endforeach
                    		</tbody>
                        <tfoot>
                          <tr>
                            <th colspan="2">Grand Total</th>
                            <th><b id="grandtotal" class="float-right">0</b></th>
                          </tr>
                        </tfoot>
                        </table>
                    </div>
                    <div class="col-md-6">&nbsp;</div>
                    <div class="col-md-6">
                        <input class="btn btn-danger" id="translation_submit" type="submit" value="{{ trans('global.submit') }}">
                    </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
<script type="text/javascript">
    function gstchange1(value,id,inc){
        var unit_count=$('#unit_count_'+id+'_'+inc).val();
        var per_unit=$('#per_unit_'+id+'_'+inc).val();
        calucalte_amount(unit_count,per_unit,id,inc); 
    }
    function per_unit1(value,id,inc){
      var unit_count=$('#unit_count_'+id+'_'+inc).val();
      calucalte_amount(unit_count,value,id,inc);  
    }
    function unit_count1(value,id,inc){
        var per_unit=$('#per_unit_'+id+'_'+inc).val();
        calucalte_amount(value,per_unit,id,inc);
    }
    function calucalte_amount(unit_count,per_unit,id,inc){
        var assign_gst=$('#assign_gst_'+id+'_'+inc).val();
        var amount=unit_count*per_unit;
        if(assign_gst == 'no_gst'){
            var total=amount;
        }else{
            var total=amount+((amount*18)/100);
        }
        $('#amount_per_vendor_'+id+'_'+inc).val(total);
        //grandtotal(id,inc);
    }
   /* function grandtotal(id,inc) {
      var g_total=0;
      $('[name="amount_per_vendor\\[\\]"]').each(function(index, value) {
          if($(this).val() > 0){
            g_total=parseInt(g_total)+parseInt($(this).val());
          }
      });
      $('#grandtotal').html(g_total);
    }*/
    function add_more_upload(id,inc){
      //alert(inc);
      var wrapper = $("#addmorevendor_"+id);
      var req_service=$('#req_service_'+id).val();
      //inc=inc+1;
      var vendors=<?php echo $vendors;?>;
      var options='<div class="row" id="vendor_add_more_'+x+'"><div class="col-md-11"><div class="row"><div class="col-md-2"><select  class="form-control select2" id="tr_list_select_'+id+'_'+x+'" name="tr_list_select['+id+'][]" required><option value="">Select User</option>';
      for(var i=0;i<vendors.length;i++){
                options +='<option value="'+vendors[i].id+'">'+vendors[i].name+'</option>';
            }
          options +='</select></div><div class="col-md-2"><select id="assign_gst_'+id+'_'+x+'" name="assign_gst['+id+'][]" onchange="gstchange1(this.value,'+id+','+x+')" class="form-control select2" required><option value="gst">GST</option><option value="both">SGST & CGST</option><option value="no_gst">No GST</option></select></div><div class="col-md-2"><select name="currency['+id+'][]" id="currency_'+id+'_'+x+'" class="form-control select2" required>@foreach($currency_list as $cl)<option value="{{$cl->id}}">{{$cl->currency_name}}</option>@endforeach</select></div><div class="col-md-2"><input type="number" name="unit_count['+id+'][]" id="unit_count_'+id+'_'+x+'" onkeyup="unit_count1(this.value,'+id+','+x+')" class="form-control" value="" placeholder="'+req_service+' Count" min="1" required="" /></div><div class="col-md-2"><input type="number" name="per_unit['+id+'][]" id="per_unit_'+id+'_'+x+'" onkeyup="per_unit1(this.value,'+id+','+x+')" placeholder="Per Unit" class="form-control" min="0" step="0.1" required=""></div><div class="col-md-2"><input type="number" name="amount_per_vendor['+id+'][]" id="amount_per_vendor_'+id+'_'+x+'" min="0.1" class="form-control amount_list" required placeholder="Total" readonly/></div></div></div><div class="col-md-1"><button type="button" class="btn btn-danger btn-xs" id="remove_field" onclick="remove_more_upload(' + x + ')" name="submit"><i class="fas fa-trash"></i></button></div></div>';
      $(wrapper).append(options);
      x++;
      selectRefresh();
    }
    
    var x = 1;
    function remove_more_upload(id){   
      $("#vendor_add_more_"+id).remove();
      x--;
    }
    function selectRefresh(){
      $('.select2').select2();
    }
    function change_vendor(v_id){
      window.location.href = "{{route('admin.authenticatedusers.index')}}?status="+multipleValues.join( "," );
    }
</script>
@endsection

