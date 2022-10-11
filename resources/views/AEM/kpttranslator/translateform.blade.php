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

<div id="error_on_header" style="display:none;"></div>


<div class="card">
    <div class="card-header">
        AEM Request
    </div>
	 
	 <form id="frm_segment" action="{{ route("admin.kptaemtranslatorrequests.aem_request_translation_submit") }}" method="POST" enctype="multipart/form-data">
	 @csrf

    <div class="card-body">
        <div class="table-responsive">
			<div class="container">
	 <?php		  
			$arr_data = array();			
		if(!empty($arr_aemrequest_details)){				
							
			$aem_req_id			    =	 $arr_aemrequest_details[0]->aem_req_id;			
			$aem_reference_code 	=	 $arr_aemrequest_details[0]->aem_reference_code;
			$aem_project_name		=    $arr_aemrequest_details[0]->aem_project_name;
			$aem_source_filename	=	 $arr_aemrequest_details[0]->aem_source_filename;
			$aem_original_filename	=	 $arr_aemrequest_details[0]->aem_original_filename;
			$aem_project_status		=	 $arr_aemrequest_details[0]->aem_project_status;
			
			$aem_source_language	=	 $arr_aemrequest_details[0]->aem_source_language;
			$aem_target_language	=	 $arr_aemrequest_details[0]->aem_target_language;
			
			
			
		$arr_languages_list = $kptaemtranslator->getting_language_list_reference_code($aem_reference_code);
		$aem_source_language  = $arr_languages_list[0]->aem_source_language;
		$aem_target_language  = $arr_languages_list[0]->aem_target_language;
		
		$aem_object_id 			=    $arr_object_id['objectid'];		
		
		
		$arr_object_details = $kptaemtranslator->xp_loc_get_aem_request_xml_files_with_object_select($aem_reference_code,$aem_object_id);
	    $object_aem_xml_status = $arr_object_details[0]->aem_xml_status;
		
		
			
		$arr_object_translation_status = array('Translation in-Progress','KPT Review Failed','Qatar Review Failed');
		if(in_array($object_aem_xml_status,$arr_object_translation_status)) {
			
	?>	
	
	<div class="row">
		<div class="col-sm" style="text-align:right;">&nbsp;</div>			
		<div class="col-sm" style="text-align:right;"><input class="btn btn-success" type="button" id="id_machine_translate" value="Machine Translate" name="machine_translate" aem_reference_id ="<?php print $aem_reference_code; ?>" aem_object_id="<?php print $aem_object_id; ?>" source_language="<?php print $aem_source_language;?>" target_language="<?php print $aem_target_language;?>"></div>
  </div>
  <?php
		}
   ?>
   
   
   
		<div class="row">
			<div class="col-sm" style="text-align:right;">Project:</div>			
			<div class="col-sm"><strong><?php print $aem_project_name;?></strong></div>
		</div>
		
		<div class="row">
			<div class="col-sm" style="text-align:right;">Project Status:</div>		
			<div class="col-sm"><strong><?php if($aem_project_status =="Assigned"){ print "Translation is In Progress"; }else{ print $aem_project_status; }?></strong></div>
		</div>
		
		<div class="row">
			<div class="col-sm" style="text-align:right;">Source Language:</div>	
			<div class="col-sm"><strong><?php print $aem_source_language; ?></strong></div>
		</div>
		
		<div class="row">
			<div class="col-sm" style="text-align:right;">Target Language:</div>	
			<div class="col-sm"><strong><?php print $aem_target_language; ?></strong></div>
		</div>
		<?php
			 $basepath = public_path();
		?>		
		<div class="row">&nbsp;</div>
		<div class="row">
			<div class="col-sm">Google Translation Indication :- <img src="{{URL::asset('/img/gtranslate.jpg')}}" border="0"></div>
			<div class="col-sm">Translation Memory Indication :- <img src="{{URL::asset('/img/translation_memory.jpg')}}" border="0"></div>
			<div class="col-sm">Translation Suggestion Indication :- <img src="{{URL::asset('/img/translation_suggestion.jpg')}}" border="0"></div>
		</div>
		<div class="row">&nbsp;</div>
		
		<div class="row">&nbsp;</div>
		
		<input type="hidden" name="hid_aem_reference_code" id="hid_aem_reference_code" value="<?php print $aem_reference_code; ?>">
		<input type="hidden" name="hid_aem_source_language" id="hid_aem_source_language" value="<?php print $aem_source_language;?>">		
		<input type="hidden" name="hid_aem_target_language" id="hid_aem_target_language" value="<?php print $aem_target_language;?>">
		
		
		
		<?php
				 $arr_aem_translated_strings = $kptaemtranslator->getting_translated_string_aem_request($aem_reference_code,$aem_object_id,$aem_source_language,$aem_target_language);
				 			 
				
				  $k =0;
		     if(!empty($arr_aem_translated_strings) && count($arr_aem_translated_strings)>0){				 
				 $p=1;
				 foreach($arr_aem_translated_strings as $t_rows){
					 				 										 
					 $aem_translation_id       =  $t_rows->aem_translation_id;
					 $aem_reference_code 	   =  $t_rows->aem_reference_code;
					 $source_language	 	   =  $t_rows->source_language;
					 $target_language   	   =  $t_rows->target_language;
					 $source_text			   =  $t_rows->source_text;
					 $target_text        	   =  $t_rows->target_text;
					 $translate_status	 	   =  $t_rows->translate_status;
					 $kpt_reviewer_comments    =  $t_rows->kpt_reviewer_comments;
					 $qatar_reviewer_comments  =  $t_rows->qatar_reviewer_comments;
					 $translated_flag		   =  $t_rows->translated_flag;
					 
				$classname = ($k%2==0)?'table-secondary':'table-primary'; 	 
				$k++;
				
				$flag_tm = "style='display:none';";
				$flag_gt = "style='display:none';";
				$flag_ts = "style='display:none';";	
				
				if($translated_flag =="G"){
					$flag_gt = "style='display:inline-block';";
					$tm_flag = "G";
				}elseif($translated_flag =="TS"){
					$flag_ts = "style='display:inline-block';";
					$tm_flag = "TS";
					
				}elseif($translated_flag =="TM"){
					$flag_tm ="style='display:inline-block';";	
					$tm_flag = "TM";
				}
				
		?>
		
		
		<?php 
			if($translate_status == "KPT Review Failed" || $translate_status == "KPT Review Completed" ){
			?>
			<div class="row" style="background-color:#bfbcbc;">
				<div class="col-sm">Translation Status:
				<?php
				if($translate_status == "KPT Review Failed" )
					print '<span style="color:red;font-weight:bold;">'.$translate_status.'</span>';
				elseif($translate_status == "KPT Review Completed" )
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
			<div class="row">&nbsp;</div>
		 <?php
			}
		 ?>		
			<div class="row <?php print $classname;?>" style="padding:10px;">
				<div class="col"><textarea name="source_text[]" id="source_text" rows="4" cols="60" class="form-control"><?php print $source_text;?></textarea></div>					
				<div class="col"><textarea name="target_text[]" id="target_text" rows="4" cols="60" class="form-control" btn_id="<?php print $p;?>"><?php print $target_text;?></textarea></div>
				<input type="hidden" name="translated_flag[]" id="translated_flag_<?php print $p;?>" value="">
				
			</div>
			
			<div class="col-sm" style="text-align:left;padding-top:10px;">
				Translation Indication:
				<img src="{{URL::asset('/img/gtranslate.jpg')}}" border="0"  id="show_gt_<?php print $p;?>" <?php print $flag_gt; ?>>&nbsp;<img src="{{URL::asset('/img/translation_memory.jpg')}}" border="0" id="show_tm_<?php print $p;?>" <?php print $flag_tm; ?>>&nbsp;<img src="{{URL::asset('/img/translation_suggestion.jpg')}}" id="show_ts_<?php print $p;?>" border="0" <?php print $flag_ts; ?>>		
				
			</div>
			<div class="row">&nbsp;</div>
			
		<?php
		 		 
		 if($translate_status == "Qatar Review Failed" || $translate_status == "Qatar Review Success" ){
			?>
			<div class="row" style="background-color:#969ba2;">
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
			$arr_object_translation_status = array('Translation in-Progress','KPT Review Failed','Qatar Review Failed','KPT Review Failed');
			if(in_array($aem_project_status,$arr_object_translation_status)) {				 
	 ?>	 
		<div class="row ">
				<input type="hidden" name="hid_aem_object_id" id="hid_aem_object_id" value="<?php print $aem_object_id;?>">
				<div class="col-sm" style="text-align:right;">&nbsp;</div>
				
				
					
				
		</div>
		<div class="row">&nbsp;</div>
	 
	 <?php 
				  }
				  
				  $p++;
				
			    }
				 
				 
			 }else{			 
				 
					
				$basepath = public_path();			 
				$uploads_dir = $basepath.'/uploadfiles/aem/restapi';		 
				 $meta_xml_file = $uploads_dir."/".$aem_reference_code."/".$aem_object_id.".xml";
			
				$node_cmd = "node ".$basepath."/converters/from-xml2json.js $meta_xml_file ";
				$returnjson  = shell_exec($node_cmd);								
				$result_json = json_decode($returnjson);
							
				
				$arr_data    = $result_json->translationObjectFile->translationObjectProperties->property;		
				
				$arr_segments = array();
				$k=0;
				$p=1;
					
				foreach($arr_data as $rows){					
					
						$source_text = $rows->_text;			
						$classname = ($k%2==0)?'table-secondary':'table-primary'; 	 

								
				$arr_tm = $kptaemtranslator->get_translationmemory_by_source($aem_source_language,$aem_target_language,$source_text);
				
												
				$target_text ="";
				$tm_flag = "";
				$flag_tm = "style='display:none';";
				$flag_gt = "style='display:none';";
				$flag_ts = "style='display:none';";				
				if(count($arr_tm)>0 && $arr_tm[0]->target_text !=""){
					$target_text = $arr_tm[0]->target_text;
					$flag_tm ="style='display:inline-block';";	
					$tm_flag = "TM";
				} 
				
	?>
			<div class="row <?php print $classname;?>">
						<div class="col-sm"><textarea name="source_text[]" id="source_text" rows="4" cols="80" class="form-control"><?php print $source_text;?></textarea></div>			
				<div class="col-sm"><textarea name="target_text[]" id="target_text_<?php print $p;?>" rows="4" cols="80" class="form-control"  btn_id="<?php print $p;?>" ><?php print $target_text;?></textarea></div>
				<input type="hidden" name="translated_flag[]" id="translated_flag_<?php print $p;?>" value="<?php print $tm_flag;?>">
			</div>
			
			<div class="row">&nbsp;</div>
			<div class="row ">
				<input type="hidden" name="hid_aem_object_id" id="hid_aem_object_id" value="<?php print $aem_object_id;?>">
				<div class="col-sm" style="text-align:left;">
				Translation Indication:
				<img src="{{URL::asset('/img/gtranslate.jpg')}}" border="0"  id="show_gt_<?php print $p;?>" <?php print $flag_gt; ?>>&nbsp;<img src="{{URL::asset('/img/translation_memory.jpg')}}" border="0" id="show_tm_<?php print $p;?>" <?php print $flag_tm; ?>>&nbsp;<img src="{{URL::asset('/img/translation_suggestion.jpg')}}" id="show_ts_<?php print $p;?>" border="0" <?php print $flag_ts; ?>>		
				
				</div>
				<div class="col-sm" style="text-align:right;"><input type="button" name="btn_segment"  class="btn btn-success" btn_id="<?php print $p;?>" id="btn_gtranslate_segment" target_lange_code="<?php print $aem_target_language;?>" source_text="<?php print $source_text;?>" value="GTranslate"></div>
			</div>
		<div class="row">&nbsp;</div>
  
 <?php			
				$k++;
				$p++;
				
				}
 
			}
			
		if(count($arr_data)>0){			
?>
		<div class="row">&nbsp;</div>
		<div class="row">
				<input type="hidden" name="hid_aem_object_id" id="hid_aem_object_id" value="<?php print $aem_object_id;?>">
				<div class="col-sm" style="text-align:right;">&nbsp;</div>
				<div class="col-sm"><input type="button" name="btn_segment"  class="btn btn-success" id="btn_segment" value="Translate"></div>
		</div>

	<?php	
			}

		}	    
	?>



		
<?php
		//}
			
?>		   
		   
		   
		   </div>
        </div>
    </div>
	</form>
</div>
@endsection
@section('scripts')
@parent


<!-- jQuery UI -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>



<script>
 
  /* Google Translate */	 
	 $(document.body).on('click', "#btn_gtranslate_segment", function(e){			
        $('#error_on_header').html('');		
			
		var btn_id = $(this).attr('btn_id');		
		var target_lange_code 	= $(this).attr('target_lange_code');
        var source_text 	  	= $(this).attr('source_text');		
						
		var accessurl = '{{ route("admin.kptaemrequest.googletranslate") }}';	
		
		
        $.ajax({
            type: "post",
            dataType: "html",
            url : accessurl,
            data: {                
                "_token": "{{ csrf_token() }}",
				"target_lange_code":target_lange_code,
                "source_text":source_text,				
            },
            beforeSend: function( xhr ) {
				
            },
            success: function(data) {					
					var target_text = "#target_text_"+btn_id;
					var show_gt 	= "#show_gt_"+btn_id;
					var show_tm		= "#show_tm_"+btn_id;
					var show_ts 	= "#show_ts_"+btn_id;					
					
					$(target_text).val(data);
					
					var translated_flag = "#translated_flag_"+btn_id;
					 $(translated_flag).val("TS");
					
					$(show_gt).css('display', 'inline-block');
					$(show_tm).hide();
					$(show_ts).hide();					
            },
			error: function(){
                alert("Error try again or contact administrator");
                return false;
            }
        });
    });
	/* Google Translate */ 
	
	
	
	/* Translator Submit start here */
 $("#btn_segment").click(function(){
	 var flag1 =1;
	 $("textarea").each(function(){
			if(this.value ==""){
				$('#error_on_header').show();
				$('#error_on_header').html('<div class="alert alert-danger"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong>Please Translate Source Text</div>');
				this.focus();
				flag1 =0;
				return false;			
			}			
		});	

	if(flag1 == 1){
		$('#error_on_header').html('');		
		$("#frm_segment").submit();			
	}
	
 });
/* Translator Submit end here */

	
 var auto_accessurl = '{{ route("admin.kptaemrequest.autosuggestion") }}';

$( function() {
	
		var aem_source_language = $("#hid_aem_source_language").val();
		var aem_target_language = $("#hid_aem_target_language").val();		
		
		
  
        $('textarea').autocomplete({
            source: function( request, response ) {                
                $.ajax({
                    url: auto_accessurl,
                    type: 'post',
                    dataType: "json",
                    data: {
						"_token": "{{ csrf_token() }}",
                        typedword: request.term,
						source_lang : aem_source_language,
						target_lang : aem_target_language
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {				
				var prevtxtval = $('#target_text').val();
				var btn_id = $(this).attr('btn_id');
				
				var show_gt 	= "#show_gt_"+btn_id;
				var show_tm		= "#show_tm_"+btn_id;
				var show_ts 	= "#show_ts_"+btn_id;
				
				var translated_flag = "#translated_flag_"+btn_id;
				$(translated_flag).val("TS");
				
				
				$(show_gt).hide();
				$(show_tm).hide();
				$(show_ts).css('display', 'inline-block');
				
				$(this).val(ui.item.label); // display the selected text               
                return false;
            }
        });

      
    });

    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }	

</script>

@endsection