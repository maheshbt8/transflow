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

<div class="card">
    <div class="card-header">
        AEM Request
    </div>

    <div class="card-body">
        <div class="table-responsive">
			<div class="container">

		   
		   <?php
						
			if(!empty($arr_aemrequest_details)){				
							
			$aem_req_id			    =	 $arr_aemrequest_details[0]->aem_req_id;
			$aem_reference_code 	=	 $arr_aemrequest_details[0]->aem_reference_code;
			$aem_project_name		=    $arr_aemrequest_details[0]->aem_project_name;
			$aem_source_filename	=	 $arr_aemrequest_details[0]->aem_source_filename;
			$aem_original_filename	=	 $arr_aemrequest_details[0]->aem_original_filename;
			$aem_project_status		=	 $arr_aemrequest_details[0]->aem_project_status;
			
			$aem_source_language	=	 $arr_aemrequest_details[0]->aem_source_language;
			$aem_target_language	=	 $arr_aemrequest_details[0]->aem_target_language;
					
			
			$arr_languages_list = $kptaemclientpm->getting_language_list_reference_code($aem_reference_code);
			$aem_source_language  = $arr_languages_list[0]->aem_source_language;
			$aem_target_language  = $arr_languages_list[0]->aem_target_language;
			
			$aem_object_id 			=    $arr_object_id['objectid'];
			
?>

		<div class="row" style="padding:8px;">
						<div class="col-sm" style="text-align:right;">Project:</div>			
						<div class="col-sm"><strong><?php print $aem_project_name;?></strong></div>
		</div>
		
		<div class="row" style="padding:8px;">
						<div class="col-sm" style="text-align:right;">Project Status:</div>		
						<div class="col-sm"><strong><?php if($aem_project_status =="Assigned"){ print "Translation is In Progress"; }else{ print $aem_project_status; }?></strong></div>
		</div>
		
		<div class="row" style="padding:8px;">
						<div class="col-sm" style="text-align:right;">Source Language:</div>	
						<div class="col-sm"><strong><?php print $aem_source_language; ?></strong></div>
		</div>
		
		<div class="row" style="padding:8px;">
						<div class="col-sm" style="text-align:right;">Target Language:</div>	
						<div class="col-sm"><strong><?php print $aem_target_language; ?></strong></div>
		</div>
		
		<input type="hidden" name="hid_aem_reference_code" id="hid_aem_reference_code" value="<?php print $aem_reference_code; ?>">
		<input type="hidden" name="hid_aem_source_language" id="hid_aem_source_language" value="<?php print $aem_source_language;?>">		
		<input type="hidden" name="hid_aem_target_language" id="hid_aem_target_language" value="<?php print $aem_target_language;?>">
		
		
		<?php
				 $arr_aem_translated_strings = $kptaemclientpm->getting_translated_string_aem_request($aem_reference_code,$aem_object_id,$aem_source_language,$aem_target_language);
				 
				$k=0;				 
		     if(!empty($arr_aem_translated_strings)){
				 
				 foreach($arr_aem_translated_strings as $t_rows){					 
															 
					 $aem_translation_id	   =  $t_rows->aem_translation_id;
					 $aem_reference_code 	   =  $t_rows->aem_reference_code;
					 $source_language	 	   =  $t_rows->source_language;
					 $target_language   	   =  $t_rows->target_language;
					 $source_text			   =  $t_rows->source_text;
					 $target_text        	   =  $t_rows->target_text;
					 $translate_status	 	   =  $t_rows->translate_status;
					 $kpt_reviewer_comments    = $t_rows->kpt_reviewer_comments;
					 $qatar_reviewer_comments  = $t_rows->qatar_reviewer_comments;
					 
				$classname = ($k%2==0)?'table-secondary':'table-primary'; 	
				$k++;
	?>
	
	
		<?php 
			if($translate_status == "Review Failed" || $translate_status == "Completed Review" ){
			?>
			<div class="row" style="background-color:#bfbcbc;padding:8px;">
				<div class="col-sm">Translation Status:
				<?php
				if($translate_status == "Review Failed" )
					print '<span style="color:red;font-weight:bold;">'.$translate_status.'</span>';
				elseif($translate_status == "Completed Review" )
					print '<span style="color:green;font-weight:bold;">'.$translate_status.'</span>';
				?>				
				</div>			
				<div class="col-sm"><b>Comments:</b>
					<?php 
					if($kpt_reviewer_comments !="")
						print $kpt_reviewer_comments;
				   ?>				
				</div>
			</div>
		 <?php
			}
		 ?>	


		<?php 
			if($translate_status == "Qatar Review Failed" || $translate_status == "Qatar Review Completed" ){
			?>
			<div class="row" style="background-color:#bfbcbc;padding:8px;">
				<div class="col-sm">Translation Status:
				<?php
				if($translate_status == "Qatar Review Failed" )
					print '<span style="color:red;font-weight:bold;">'.$translate_status.'</span>';
				elseif($translate_status == "Qatar Review Completed" )
					print '<span style="color:green;font-weight:bold;">'.$translate_status.'</span>';
				?>				
				</div>			
				<div class="col-sm"><b>Comments:</b>
					<?php 
					if($qatar_reviewer_comments !="")
						print $qatar_reviewer_comments;
				   ?>				
				</div>
			</div>
		 <?php
			}
		 ?>	


				 
		 
			<div class="row <?php print $classname;?>" style="padding:8px;">
						<div class="col-sm"><b>Source:</b> <?php 
							
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
							<h4 class="modal-title" id="myModalLabel"><?php print$aem_project_name;?></h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
				</div>			
				<div class="col-sm"><b>Target:</b> <?php 
				
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
							
							<h4 class="modal-title" id="myModalLabel"><?php e($aem_project_name);?></h4>
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
				
				</div>
			</div>
			
			
			
		<?php		 		 
		 if($translate_status == "Translation wrong" || $translate_status == "Translation success" ){
			?>
			<div class="row" style="background-color:#969ba2;padding:8px;">
				<div class="col-sm">Translation Status:<?php print $translate_status;?></div>			
				<div class="col-sm">
					<?php 
					if($qatar_reviewer_comments !="")
						print $qatar_reviewer_comments;
				   ?>				
				</div>
			</div>
		 <?php
			}
		 ?>
	
	<?php							 
			}
				 
	 ?>
	 
	 <?php				 
			 }else{								
	?>
			<div class="row" style="padding-top:50px;font-size:20px;text-align:center;color:red;font-weight:bold;border-top:1px solid #333745;">
						Translation is in Pending...
			</div>
  
		<?php
 				
			 }
			
		}	    
	 ?>	
		   
		   
		   </div>
        </div>
    </div>
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
 



</script>



@endsection