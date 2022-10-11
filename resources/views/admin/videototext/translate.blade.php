@extends('layouts.admin')
@section('content')
<style type="text/css">
	textarea{
		min-height: auto;
	}
</style>
<style>
.suggestions_list {
  list-style-type: none;
  padding: 0;
  margin: 0;
  position: absolute; 
  z-index: 999; 
  height: 200px;
  width: 100%; 
  overflow-x: auto;
  /*background: #fff; */
  /*overflow-x: scroll; */
  /*border: 1px solid rgb(153, 153, 153); */
  /*display: block;*/
}

.suggestions_list li{
  border: 1px solid #ddd;
  margin-top: -1px; /* Prevent double borders */
  background-color: #f6f6f6;
  padding: 10px;
  text-decoration: none;
  font-size: 14px;
  color: black;
  display: block;
  cursor: pointer;
}

.suggestions_list li :hover:not(.header) {
  background-color: #eee;
}
</style>

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
       CSV File
    </div>
	 <div class="d-flex justify-content">
	 <form id="frm_segment" action="{{ route("admin.videototext.srtsegmentationformsubmit") }}" method="POST" enctype="multipart/form-data">
	 @csrf

    <div class="card-body">
        <div class="table-responsive">
			<div class="container">
		
		<?php
			 $basepath = public_path();
		?>		
		<div class="row">&nbsp;</div>
		<div class="row">
			<div class="col-sm">Translation Memory Indication :- <img src="{{URL::asset('/img/translation_memory.jpg')}}" border="0"></div>
			<div class="col-sm">Translation Suggestion Indication :- <img src="{{URL::asset('/img/translation_suggestion.jpg')}}" border="0"></div>
			<div class="col-sm"><input type="button" name="btn_segment"  class="btn btn-success" id="btn_gtranslate_segment_all" value="Translate All"></div>
		</div>
		<div class="row">&nbsp;</div>
		
		<div class="row">&nbsp;</div>
		<?php
			$k=0;
			$p=1;
		if(!empty($arr_csv_values) && count($arr_csv_values)>0){	
			foreach($arr_csv_values as $rows){
				$classname = ($k%2==0)?'table-secondary':'table-primary';
				$source_text =strip_tags($rows['text']);
				if(isset($arr_csv_values_target[$k]['text']) && $arr_csv_values_target[$k]['text'] != ''){
				$target_text =strip_tags($arr_csv_values_target[$k]['text']);
				}
				
				if(!empty($source_text) || $source_text != ''){
				
				$source_language  = $sourcelanguage;
				$target_languages = $targetlanguages;	
				$domain_id=$domain;
				//$source_language  = "en";
				//$target_languages = "hi";
				$tm_flag = "";
				$flag_tm = "style='display:none';";
				$flag_gt = "style='display:none';";
				$flag_ts = "style='display:none';";
				if($target_text == ''){
				$arr_tm = $kptaemtranslator->get_translationmemory_by_source($source_language,$target_languages,$source_text,$domain_id);
								
				if(is_countable($arr_tm) != '' && count($arr_tm)>0 && $arr_tm[0]->target_text !=""){
					$target_text = $arr_tm[0]->target_text;
					$flag_tm ="style='display:inline-block';";	
					$tm_flag = "TM";
				}
				}else{
					$flag_tm ="style='display:inline-block';";	
					$tm_flag = "TS";
				} 	
		?>
		
		<div class="row <?php print $classname;?>" style="padding:10px;">
				<div class="col"><textarea name="source_text[]" id="source_text" rows="2" cols="60" class="form-control" readonly=""><?php print $source_text;?></textarea></div>					
				<div class="col"><textarea name="target_text[]" id="target_text_<?php print $p;?>" rows="2" cols="60" class="form-control" btn_id="<?php print $p;?>" target_lange_code="<?php print $target_languages;?>" source_text="<?php print $source_text;?>" required><?php print $target_text;?></textarea >
				<ul id="suggestions_list_<?php print $p;?>" class="suggestions_list" style="display: none;"></ul>
				</div>
				<div class="col-md-2">
					<img src="{{URL::asset('/img/translation_memory.jpg')}}" border="0"  id="show_gt_<?php print $p;?>" <?php print $flag_gt; ?>>&nbsp;<img src="{{URL::asset('/img/translation_memory.jpg')}}" border="0" id="show_tm_<?php print $p;?>" <?php print $flag_tm; ?>>&nbsp;<img src="{{URL::asset('/img/translation_suggestion.jpg')}}" id="show_ts_<?php print $p;?>" border="0" <?php print $flag_ts; ?>>
					<input type="button" name="btn_segment"  class="btn btn-success" btn_id="<?php print $p;?>" id="btn_gtranslate_segment" target_lange_code="<?php print $target_languages;?>" source_text="<?php print $source_text;?>" value="Translate">
				</div>
				<input type="hidden" name="translated_flag[]" id="translated_flag_<?php print $p;?>" value="<?php print $tm_flag;?>">				
		</div>
		<input type="hidden" name="hid_source_language" id="hid_source_language" value="<?php print $source_language;?>">		
		<input type="hidden" name="hid_target_language" id="hid_target_language" value="<?php print $target_languages;?>">
		<div class="row">&nbsp;</div>
		<div class="row">&nbsp;</div>
		<?php
			$k++;
			$p++;
			}
		 }
		?>
		
	
	 
	<?php 			 
			 	
		if(count($arr_csv_values)>0){			
	?>
		<div class="row">&nbsp;</div>
		<div class="row">
				<input type="hidden" name="domain_id" value="<?php echo $domain_id;?>">
				<input type="hidden" name="job_id" value="<?php echo $job_id;?>">
				<div class="col-sm" style="text-align:right;"><input type="button" name="btn_segment"  class="btn btn-success" id="btn_segment" value="Submit"></div>
				<div class="col-sm">&nbsp;</div>
		</div>

	<?php	
			}

		}
	?> 	   
		   
		   </div>
        </div>
    </div>
	</form>
	</div>
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
		gtranslate_data(target_lange_code,source_text,btn_id);
    });
	/* Google Translate */ 
	function gtranslate_data(target_lange_code,source_text,btn_id) {
		$('#error_on_header').html('');						
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
	}
	$(document.body).on('click', "#btn_gtranslate_segment_all", function(e){
		$("[name^=target_text]").each(function(index, value) {
            if ($(this).val() == '') {
            var btn_id = $(this).attr('btn_id');
			var target_lange_code 	= $(this).attr('target_lange_code');
        	var source_text 	  	= $(this).attr('source_text');		
			gtranslate_data(target_lange_code,source_text,btn_id);
            }
        });
	});
	
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
	var aem_source_language = $("#hid_source_language").val();
	var aem_target_language = $("#hid_target_language").val();		
$("textarea").keyup(function(e) {
	var target_text_main = $(this).val().toLowerCase();
	var strarray = target_text_main.split(' ');
	var target_text = strarray.at(-1);
	var btn_id = $(this).attr('btn_id');
	if(target_text == "") {
        	$('#suggestions_list_'+btn_id).hide();
        	$('#suggestions_list_'+btn_id).html('');
        }else{
	$.ajax({
        url: auto_accessurl,
        type: 'post',
        data: {
			"_token": "{{ csrf_token() }}",
            typedword: target_text,
			source_lang : aem_source_language,
			target_lang : aem_target_language,
			btn_id : btn_id
        },
        success: function( data ) {
            $('#suggestions_list_'+btn_id).show();
            $('#suggestions_list_'+btn_id).html(data);
        }
    });
}
});   
$('ul').on('click','.suggestions_data',function(){
	var btn_id=$(this).attr('btn_id');
	var text=$(this).text();
	var m_text=$('#target_text_'+btn_id).val();
	var result = m_text.split(' ');
	var poppedItem = result.pop();
	var target_text = result.join(" ");
	if(target_text != ''){
	var text=	target_text+' '+text;
	}
	$('#target_text_'+btn_id).val(text);
	$('#suggestions_list_'+btn_id).hide();
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