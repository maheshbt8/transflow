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
				
                    @foreach($kptaemqclientresults as $key => $row)

											
                        <tr>                            
                           <td>{{ $row->aem_project_name ?? '' }}</td>
                           <td><a href="{{ route('admin.kptaemrequest.downloadrequestfile', $row->aem_reference_code) }}">Download</a></td>
                           <td>{{ $row->aem_project_status ?? '' }}
						   
		<div class="dropdown">
			
			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
			<a class="dropdown-item" href="#">Action</a>
			<a class="dropdown-item" href="#">Another action</a>
			<a class="dropdown-item" href="#">Something else here</a>
			</div>
		</div>		   
			   
						   
			<?php

				if($row->aem_project_status =="New") {
			
				$arr_aemreq_tm_status 	 	= array($row->aem_project_status);
				$arr_main_req_aem_status 	= array("New","Accepted");
				$arr_diff_req_aem_status 	= array_diff($arr_main_req_aem_status,$arr_aemreq_tm_status);	
				
			?>	
		
		<div class="dropdown" >
		  <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action			
			</button>
		  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
		   <?php		   
				foreach($arr_diff_req_aem_status as $row1){					
		  ?>
			<a href="javascript:void(0)" class="apply_action_aemtm_status dropdown-item"  tm_status="<?php print $row1;?>" refernce_id="<?php print $row->aem_reference_code;?>" alert-msg="Are you sure you want to change the Project Status?"><?php print $row1; ?>dddd</a>
			<?php
			}
			?>			
		  </div>
		</div>

		<?php
			}elseif($row->aem_project_status =="Qatar Review Success"){
				$arr_aemreq_tm_status 	 	= array($row->aem_project_status);
				$arr_main_req_aem_status 	= array("Translation Completed","Qatar Review Success");
				$arr_diff_req_aem_status 	= array_diff($arr_main_req_aem_status,$arr_aemreq_tm_status);	
		 ?>
		 <div class="dropdown" >
		  <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action			
			</button>
			<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
		   <?php		   
				foreach($arr_diff_req_aem_status as $row1){					
		  ?>
			<a href="javascript:void(0)" class="apply_action_aemtm_status dropdown-item"  tm_status="<?php print $row1;?>" refernce_id="<?php print $row->aem_reference_code;?>" alert-msg="Are you sure you want to change the Project Status?"><?php print $row1; ?></a>
			<?php
			}
			?>			
		  </div>
		</div>		 

		<?php
			}
		?>
	 </td>
           <td><a href="#" data-toggle="modal" id="aem_client_qatar_view_details" aem_main_request_id="<?php print $row->aem_req_id;?>">View</a>		   
						   
	<!-- Modal for  -->
    <div class="modal fade" id="aem_assign_modal_<?php print $row->aem_req_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?php print $row->aem_project_name;?></h4>
          </div>
          <div class="modal-body" id="aem_assign_modal_body_<?php print $row->aem_req_id;?>">
           
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>           
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->	
	
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



/* View assigned requests from client Qatar */	
	$(document.body).on('click', "#aem_client_qatar_view_details", function(e){       
			var aem_main_request_id  = $(this).attr('aem_main_request_id');
			
			//assigned_aem_request_details

			var accessurl = '{{ route("admin.kptaemqclientrequests.assigned_aem_request_details") }}';
									
			// AJAX request
                    $.ajax({
                        url: accessurl,
                        type: 'post',
                        data: {
							"_token": "{{ csrf_token() }}",
							"aem_p_id": aem_main_request_id},
                        success: function(response){ 
							
                            // Add response in Modal body
                            $('#aem_assign_modal_body_'+aem_main_request_id).html(response); 

                            // Display Modal
                            $('#aem_assign_modal_'+aem_main_request_id).modal('show'); 
                        }
                    });	
    });	
 /* View assigned requests from client PM */


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
			
		var accessurl = '{{ route("admin.kptaemqclientrequests.aem_xmlfiles_request") }}';		 
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
   
   
    /* change campaign TM status */
	$('.apply_action_aemtm_status').click(function () {
         var con = confirm($(this).attr('alert-msg'));
        if(con == false)
            return false;
		
		var accessurl = '{{ route("admin.kptaemcpmrequest.request_change_aem_status") }}';
		     
		var reference_id  = $(this).attr('refernce_id');        
		var tm_status 	  = $(this).attr('tm_status');      					       
              
            $.ajax({
                type: "post",
                url: accessurl,
                data: {
					"_token": "{{ csrf_token() }}",
                     "referenceid":reference_id,
					 "tm_status":tm_status                    
                },
                beforeSend: function (xhr) {					
					//$(".campaigntoggleactions").hide();

                },
                success: function (campdata) {
					location.reload();
                    
                },
                error: function () {
                    alert("Problem in getting Translation data");
                    return false;
                }
            });             
      });
	 /* change campaign TM status */



</script>



@endsection