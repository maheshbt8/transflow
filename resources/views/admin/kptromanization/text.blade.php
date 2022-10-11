@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.kptromanization.title_singular') }} 
       
    </div>
    <div class="card-body">
        <!-- <form action="{{ route("admin.request.store") }}" method="POST" enctype="multipart/form-data">
            @csrf -->

        	<div class="row">
               
        		<div class="col-md-6">
                    <div class="form-group">
                        <p>Enter the text</p>
                    </div>
                    <div class="form-group ">
                    <div > <textarea rows="8" cols="95" name="tobetranslated" id="tobetranslated" style="min-height:150px;" ></textarea></div>
                    </div>
                    </div>
                    <div class="col-md-7">
                     <div style="text-align:center;"><input type="submit"  name="romanzation_btn" value="Romanize" id="romanization_btn" class="btn btn-success"></div>	
					<div id="romanzation_ouput_txt"><p>Output :-</p></div>
                    <div id="romanization_output_block" rows="4" cols="95" name="target_language" class="form-control area-width"></div>
					
				   </div>     
                </div>
            </div>
        </div>
    </div>
</div>


<style type="text/css">

.area-width{
    width:107%;
    min-height:150px;
}

</style>
@endsection
@section('scripts')
@parent
<script type="text/javascript">
   $( document ).ready(function() {
				
				
				$('#romanization_btn').click(function() {
					var tobetranslated = $("#tobetranslated").val();
					
					
				if(tobetranslated ==""){					
					alert("Please enter the Text");
					$("#tobetranslated").focus();
					return false;
				}else {
					
							
					
				$.ajax({
                    url: "{{ route("admin.kptromanization.kptromanizationprocess") }}",
                    data: {sourcetext: tobetranslated},
                    headers: {'x-csrf-token': _token},
					error: function() {
							$('#info').html('<p>An error has occurred</p>');
					},
					success: function(data) {
						console.log(data);
						$("#romanization_output_block").show();
						$("#romanization_output_block").html(data);
					},
					type: 'POST'
					});
				}
				                       	
			});
			
			
			
            $(".fl-copy").click(function(){
                 var textarea = document.getElementById("translated");
                 textarea.select();				 
                 document.execCommand("copy");
           }); 

			
		   
		});	
</script>
@endsection
