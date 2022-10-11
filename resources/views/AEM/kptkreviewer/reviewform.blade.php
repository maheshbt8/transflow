@extends('layouts.admin')
@section('content')


@if(Session::has('flash_message'))
        <div class="alert alert-success">
            {{ Session::get('flash_message') }}
        </div>
@endif

@if(Session::has('flash_message_error'))
        <div class="alert alert-danger">
            {{ Session::get('flash_message_error') }}
        </div>
@endif

	<div id="error_on_header" style="display:none;">&nbsp;</div>

<div class="card">
    <div class="card-header">
        AEM Request
    </div>
	
	<form id="frm_segment" action="{{ route("admin.kptaemkreviewerrequests.aem_request_kpt_proofreading_submit") }}" method="POST" enctype="multipart/form-data">
	 @csrf
	
    <div class="card-body">
        <div class="table-responsive">					
           <?php						
			if(!empty($arr_aem_data)){		
							
			$aem_req_id			    =	 $arr_aem_data[0]->aem_req_id;
			$aem_reference_code 	=	 $arr_aem_data[0]->aem_reference_code;
			$aem_project_name		=    $arr_aem_data[0]->aem_project_name;
			$aem_source_filename	=	 $arr_aem_data[0]->aem_source_filename;
			$aem_original_filename	=	 $arr_aem_data[0]->aem_original_filename;
			$aem_project_status		=	 $arr_aem_data[0]->aem_project_status;			
			
			$aem_source_language	=	 $arr_aem_data[0]->aem_source_language;
			$aem_target_language	=	 $arr_aem_data[0]->aem_target_language;
			
			$aem_object_id 			=    $arr_object_id['objectid'];	
			
			$arr_aem_translated_strings = $kptaemkreviewer->getting_translated_string_aem_request($aem_reference_code,$aem_object_id,$aem_source_language,$aem_target_language);			
			
	?>
	<div class="container">
					<div class="row">
						<div class="col-sm" style="text-align:right;">Project:</div>	
						<div class="col-sm"><strong><?php print $aem_project_name;?></strong></div>
					 </div>
		
				<div class="row">
						<div class="col-sm" style="text-align:right;">Project Status:</div>			
						<div class="col-sm"><strong><?php if($aem_project_status =="Assigned"){ print "Translation is In Progress"; } else { print $aem_project_status; }?></strong></div>
				</div>
		
		<input type="hidden" name="hid_aem_reference_code" id="hid_aem_reference_code" value="<?php print $aem_reference_code; ?>">
		<input type="hidden" name="hid_aem_source_language" id="hid_aem_source_language" value="<?php print $aem_source_language;?>">		
		<input type="hidden" name="hid_aem_target_language" id="hid_aem_target_language" value="<?php print $aem_target_language;?>">
		<input type="hidden" name="hid_aem_object_id" id="hid_aem_object_id" value="<?php print $aem_object_id;?>">		
		
		<?php			
			if(!empty($arr_aem_translated_strings)){
					$k=1;
					foreach($arr_aem_translated_strings as $t_rows){					
																		
						 $aem_translation_id =  $t_rows->aem_translation_id;
						 $aem_reference_code =  $t_rows->aem_reference_code;
						 $source_language	 =  $t_rows->source_language;
						 $target_language    =  $t_rows->target_language;
						 $source_text		 =  $t_rows->source_text;
						 $target_text        =  $t_rows->target_text;
						 $translate_status	 =  $t_rows->translate_status;
						 $reviewer_comments  =  $t_rows->kpt_reviewer_comments;
						 $kpt_reviewer_status =  $t_rows->kpt_reviewer_status;
						 $qatar_reviewer_status =  $t_rows->qatar_reviewer_status;
						 		 					 
											
						$translation_success="";
						 $translaton_failure="";
						 if($kpt_reviewer_status ==0)
							$translaton_failure ="checked";
						elseif($kpt_reviewer_status == 1)
							$translation_success ="checked";				
					
				
				$classname = ($k%2==0)?'table-secondary':'table-primary';			
						 
		?>
		
		
		<div class="row <?php print $classname;?>">
							<input type="hidden" name="aem_translation_id[]" value="<?php print $aem_translation_id;?>">
							<div class="col-sm" style="margin-left:20px;">
							<b>Source Text:</b> 
							
							<?php 
							$sourcetext = strlen($source_text);							
							if($sourcetext > 100){
								print  strip_tags(substr($source_text, 0,100));
							}else  {
								print $source_text;								
							}							
							
							if($sourcetext > 100){
							?>
							
							... <a href="#" data-toggle="modal" id="aem_source_text_details" aem_main_request_id="<?php print $aem_translation_id;?>" translation_type="Source">More Text</a>
						<!-- Modal -->
					<div class="modal fade" id="aem_sourcetext_modal_<?php print $aem_translation_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					  <div class="modal-dialog">
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel"><?php print $aem_project_name;?></h4>
						  </div>
						  <div class="modal-body" id="aem_assign_modal_body_<?php print $aem_translation_id;?>">
						   
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>           
						  </div>
						</div><!-- /.modal-content -->
					  </div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	
					
					<?php
							}
						?>						
							
						<br>
							<b>Target Text:</b> <?php 
							
							$targettext = strlen($target_text);							
							if($targettext > 100){
								print  strip_tags(substr($target_text, 0,100));
							}else  {
								print $target_text;								
							}					
							
							if($sourcetext > 100){
							?>
							... <a href="#" data-toggle="modal" id="aem_source_text_details" aem_main_request_id="<?php print $aem_translation_id;?>" translation_type="Target">More Text</a>
						<!-- Modal -->
					<div class="modal fade" id="aem_sourcetext_modal_<?php print $aem_translation_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					  <div class="modal-dialog">
						<div class="modal-content">
						  <div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title" id="myModalLabel"><?php print$aem_project_name;?></h4>
						  </div>
						  <div class="modal-body" id="aem_assign_modal_body_<?php print $aem_translation_id;?>">
						   
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>           
						  </div>
						</div><!-- /.modal-content -->
					  </div><!-- /.modal-dialog -->
					</div><!-- /.modal -->	


								
							<?php
							 }							
							?>						
							
							<br>
							<span style="margin-left:60%;"><input type="radio" name="translation_status_<?php print $aem_translation_id;?>" value="1" style="margin:0px;" <?php print $translation_success;?> >Approve
							&nbsp;
							<input type="radio" name="translation_status_<?php print $aem_translation_id;?>" value="0" style="margin:0px;" <?php print $translaton_failure;?>>Reject</span>
							
							
							</div>			
					<div class="col-sm"><textarea name="target_comments[]" id="target_translation_status_<?php print $aem_translation_id;?>" rows="4" cols="80" style="margin-left: 40px; width: 90%;" class="form-control"><?php print $reviewer_comments;?></textarea></div>
				</div>
	  
			<?php
					$k++;
					}						
			?>
			
			
   <?php		
			$arr_review_status = array("Review","KPT Review Failed","Qatar Review Failed");
			if(in_array($aem_project_status,$arr_review_status)){
	?>
		<div class="row">&nbsp;</div>
		<div class="row">
			<div class="col-sm" style="text-align:right;">&nbsp;</div>
			<div class="col-sm"><input type="button" class="btn btn-success" name="btn_verify" id="btn_verify" value="Submit"></div>
		</div>
	<?php
			}
	
		}
	?>	
		
	
	</div>
	<?php
			}
	?>
        </div>
    </div>
	</form>
</div>
@endsection
@section('scripts')
@parent
<script>
   /*
   $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  });
  $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
	
	//$(".select-checkbox").hide();
}) */




 /* View source text longer start here */	
	$(document.body).on('click', "#aem_source_text_details", function(e){       
			var aem_main_request_id  = $(this).attr('aem_main_request_id'); 
			var translation_type  = $(this).attr('translation_type'); 
			
			var accessurl = '{{ route("admin.kptaemqreviewerrequests.view_aem_translation_sourcetext") }}';	
											
			// AJAX request
                    $.ajax({
                        url: accessurl,
                        type: 'post',
                        data: {
							"_token": "{{ csrf_token() }}",
							"aem_translation_id": aem_main_request_id,
								"translation_type":translation_type},
                        success: function(response){ 
							
                            // Add response in Modal body
                            $('#aem_assign_modal_body_'+aem_main_request_id).html(response); 

                            // Display Modal
                            $('#aem_sourcetext_modal_'+aem_main_request_id).modal('show'); 
                        }
                    });	
    });	
 /* View source text longer end here */
 
 
 
 
 /* Reviewer Submit start here */
 $("#btn_verify").click(function(){
		 
		var all_answered = true;
		$("input:radio").each(function(){
			var name = $(this).attr("name");			
			if($("input:radio[name="+name+"]:checked").length == 0)	{
					all_answered = false;
					
			}else if($("input:radio[name="+name+"]:checked").length == 1){
				if($("input:radio[name="+name+"]:checked").val() ==0){					
					var txt_name = "#target_"+name;					
					if($(txt_name).val() ==""){
						$('#error_on_header').html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong>Please enter comments</div>');
						$(txt_name).css("border","1px solid red");
						all_answered = false;
						return false;
					}
				}			
			}
		});
		
		if(all_answered == false){			
			$('#error_on_header').html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong>Please select Status of Translation</div>');
			return false;
			
		}else if(all_answered == true){
			$('#error_on_header').html('');			
			$("#frm_segment").submit();			
		}	 
	 
	});
/* Reviewer Submit end here */
</script>



@endsection