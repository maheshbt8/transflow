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
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>                       
                        <th>Project</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th>Assigned</th>
                        <th>Cost Centric</th>						
						<th>Target</th>						
                    </tr>
                </thead>
                <tbody>	
								
                    @foreach($kptaemresults as $key => $row)		
					
                        <tr>                            
                           <td>{{ $row->aem_project_name ?? '' }}</td>
                           <td><a href="{{ route('admin.kptaemrequest.downloadrequestfile', $row->aem_reference_code) }}">Download</a></td>
                           <td>{{ $row->aem_project_status ?? '' }} </td>
                           <td><a href="#" data-toggle="modal" id="aem_kpt_admin_assign_details" aem_main_request_id="<?php print $row->aem_req_id;?>">Assign</a>
						   
						   
				<!-- Modal for Assigned Translator/Reviewer -->
				<div class="modal fade" id="aem_kpt_admin_assign_modal_<?php print $row->aem_req_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
				<div class="modal-content">
				<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">{{ $row->aem_project_name ?? '' }}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>				
				</div>
				<div class="modal-body" id="aem_kpt_admin_assign_modal_body_<?php print $row->aem_req_id;?>">

				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>           
				</div>
				</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
				</div><!-- Modal for Assigned Translator/Reviewer -->	
				</td>
                
				<td>
						   <a href="#" data-toggle="modal" id ="aem_cost_view_details" aem_main_request_id="<?php print $row->aem_req_id;?>">View</a>
					<!-- Modal CostCentric View -->
					<div class="modal fade" id="aem_cost_view_modal_<?php print $row->aem_req_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel">{{ $row->aem_project_name ?? '' }}</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					
					</div>
					<div class="modal-body">
					<div class="modal-body" id="aem_costcentric_modal_body_<?php print $row->aem_req_id;?>">           
					</div>
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-success" data-dismiss="modal">Close</button>           
					</div>
					</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
					</div><!-- /.modal CostCentric View -->	
				</td>						
				<td>
					<?php
			$arr_tranalstion_values = array('KPT Translation Completed','KPT Translation Completed','Translation Completed','Review','KPT Review Failed','KPT Review Completed','Assigned Qatar Review','Qatar Review Failed','Qatar Review Success','Completed','Confirmed Translation Completed');			
				if(in_array($row->aem_project_status,$arr_tranalstion_values)){
		 ?>
		<div class="xp_col11 actionstoggle" aem_main_request_id="<?php print $row->aem_reference_code;?>"><a href="#" aem_main_request_id="<?php print $row->aem_reference_code;?>" >View Translation(s)</a></div>
		<?php
			}elseif($row->aem_project_status =='New') {
		?>
			<div class="xp_col11" style="color:#0008ff;">New Job Translation </div>
		<?php
			}elseif($row->aem_project_status =='Accepted') {
		?>
			<div class="xp_col11" style="color:green;">Accepted for Translation </div>
		<?php
			}elseif($row->aem_project_status =='Assigned') {
		?>
			<div class="xp_col11" style="color:green;">Assigned to Translator</div>	
		<?php
			}
		?>						
			</td>
		</tr>
		
		<tr><td colspan="6" class="campaigntoggleactions" id="toggleactiondiv_<?php echo $row->aem_reference_code; ?>" style="display:none;background-color:#17a2b8;">&nbsp;</td></tr>
				
			  
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
  




/* View assigned requests from KPT Admin */	
	$(document.body).on('click', "#aem_kpt_admin_assign_details", function(e){       
			var aem_main_request_id  = $(this).attr('aem_main_request_id'); 
			
			var accessurl = '{{ route("admin.kptaemrequest.assigned_details") }}';
									
			// AJAX request
                    $.ajax({
                        url: accessurl,
                        type: 'post',
                        data: {
							"_token": "{{ csrf_token() }}",
							"aem_p_id": aem_main_request_id},
                        success: function(response){					
													
                            // Add response in Modal body
                            $('#aem_kpt_admin_assign_modal_body_'+aem_main_request_id).html(response); 

                            // Display Modal
                            $('#aem_kpt_admin_assign_modal_'+aem_main_request_id).modal('show'); 
                        }
                    });	
    });	
 /* View assigned requests from KPT Admin */
 
 
 
 
 /* View assigned requests from client PM for cost centric*/	
	$(document.body).on('click', "#aem_cost_view_details", function(e){       
			var aem_main_request_id  = $(this).attr('aem_main_request_id');		
					
			var accessurl = '{{ route("admin.kptaemrequest.costcentric_details") }}';
									
			// AJAX request
                    $.ajax({
                        url: accessurl,
                        type: 'post',
                        data: {
							"_token": "{{ csrf_token() }}",
							"aem_p_id": aem_main_request_id},
                        success: function(response){ 
													
                            // Add response in Modal body
                            $('#aem_costcentric_modal_body_'+aem_main_request_id).html(response); 

                            // Display Modal
                            $('#aem_cost_view_modal_'+aem_main_request_id).modal('show'); 
                        }
                    });	
    });	
 /* View assigned requests from client PM */
 
 
 
 
 /* Slide Toggle for actions and options in active dashboard - END */
	$('.actionstoggle').click(function () {
        var aem_main_request_id  = $(this).attr('aem_main_request_id'); 
			
		var accessurl = '{{ route("admin.kptaemrequest.aem_xmlfiles_request") }}';		 
        var togstatus = $("#toggleactiondiv_" + aem_main_request_id).css('display');
						       
        if (togstatus == 'none') {           
            $.ajax({
                type: "post",
                url: accessurl,
                data: {
					"_token": "{{ csrf_token() }}",
                    "aem_main_request_id": aem_main_request_id
                    
                },
                beforeSend: function (xhr) {					
					$(".campaigntoggleactions").hide();

                },
                success: function (campdata) {
                    $("#toggleactiondiv_" + aem_main_request_id).html(campdata);                  
                    $("#toggleactiondiv_" + aem_main_request_id).slideToggle("slow");
                },
                error: function () {
                    $('#box').trigger("click");
                    alert("Problem in getting Translation data");
                    return false;
                }
            });
        }else{
            $("#togglediv_" + aem_main_request_id).hide();
            $("#toggleactiondiv_" + aem_main_request_id).slideToggle("slow");
        }
        
        return false;
    });
   /* Slide Toggle for actions and options in active dashboard - END */
   
   
   
   /* Assign KPT Admin to Translator/Reviewer */	
	$(document.body).on('click', "#id_kptadmin_assign_ktranslator", function(e){
		
				
			var aem_main_request_id    = $("#hid_aem_req_id").val();
			var id_translator 		   = $("#id_translator").val();
			var hid_aem_reference_code = $("#hid_aem_reference_code").val();
			
			if(id_translator ==""){				
				alert("Please select Translator");
				$("#id_translator").focus();
				return false;
			}else {

				var accessurl = '{{ route("admin.kptaemrequest.kptadmin_assigned_trasnslator_aem_request") }}';	
														
			// AJAX request
                    $.ajax({
                        url: accessurl,
                        type: 'post',
                        data: {
							"_token": "{{ csrf_token() }}",
							"aem_p_id": aem_main_request_id,
							"id_translator":id_translator,
							"aem_reference_code":hid_aem_reference_code},
                        success: function(response){											
                            // Add response in Modal body
							$('#sucess_translator').show();
                            $('#sucess_translator').html(response);
							// Display Modal
                            $('#aem_kpt_admin_assign_modal_'+aem_main_request_id).modal('show'); 
                        }
                    });	
			}
    });	
 /* Assign KPT Admin to Translator/Reviewer */ 
 
 
 
 
 
 /* Assign KPT Admin to Reviewer (KPT) */	
	$(document.body).on('click', "#id_kptadmin_assign", function(e){
		
			var aem_main_request_id    = $("#hid_aem_req_id").val();
			var id_kreviewer 		   = $("#id_kreviewer").val();
			var hid_aem_reference_code = $("#hid_aem_reference_code").val();
			
			
			if(id_kreviewer ==""){				
				alert("Please select Reviewer");
				$("#id_kreviewer").focus();
				return false;
			}else {
			
			var accessurl = '{{ route("admin.kptaemrequest.kptadmin_assigned_kreviewer_aem_request") }}';	
																
			// AJAX request
                    $.ajax({
                        url: accessurl,
                        type: 'post',
                        data: {
							"_token": "{{ csrf_token() }}",
							"aem_p_id": aem_main_request_id,
							"id_kreviewer":id_kreviewer,
							"aem_reference_code":hid_aem_reference_code
							},
                        success: function(response){												
                            // Add response in Modal body
							$('#sucess_reviewer').show();
                            $('#sucess_reviewer').html(response);

							// Display Modal
                            $('#aem_kpt_admin_assign_modal_'+aem_main_request_id).modal('show'); 
                        }
                    });	
			}
    });	
 /* Assign KPT Admin to Reviewer (KPT) */



</script>



@endsection