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
                        <th>Action</th>                       					
                    </tr>
                </thead>
                <tbody>			
				
                    @foreach($kptaemtranslatorresults as $key => $row)											
                        <tr>                            
                           <td>{{ $row->aem_project_name ?? '' }}</td>
                           <td><a href="{{ route('admin.kptaemrequest.downloadrequestfile', $row->aem_reference_code) }}">Download</a></td>
                           <td>{{ $row->aem_project_status ?? '' }}</td>         
                
										
				<td>
					
		<div class="xp_col11 actionstoggle_translator" aem_main_request_id="<?php print $row->aem_reference_code;?>"><a href="#" aem_main_request_id="<?php print $row->aem_reference_code;?>" >View Translation(s)</a></div>
					
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



/* Slide Toggle for actions - Translator */
	$('.actionstoggle_translator').click(function () {
        var aem_main_request_id  = $(this).attr('aem_main_request_id'); 
		    
        var togstatus = $("#toggleactiondiv_" + aem_main_request_id).css('display');
		
		var accessurl = '{{ route("admin.kptaemtranslatorrequests.aem_request_details_translator") }}';	
						       
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
    /* Slide Toggle for actions - Translator*/



</script>



@endsection