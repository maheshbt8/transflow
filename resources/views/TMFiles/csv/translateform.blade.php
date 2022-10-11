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
	 <form id="frm_segment" action="{{ route("admin.translationcsvfile.csvsegmentationformsubmit") }}" method="POST" enctype="multipart/form-data">
	 @csrf

    <div class="card-body">
        <div class="table-responsive">
			<div class="container">
		
		<?php
			 $basepath = public_path();
			 $translation_memory_data= DB::connection('mysql2')->table('tm_temp')->get();
			 $c=0;
		?>		
		<?php
			$k=0;
			$p=1;
			$s_to_json='';
			$response_data=array();
			$domain_id=$domain;
			$file_tr_id=$file_tr_data->id;

		//	echo "<pre/>";	print_r($arr_csv_values);die;
		if(!empty($arr_csv_values) && count($arr_csv_values)>0){	
		$arr_csv_values=uniquearray($arr_csv_values);
			foreach($arr_csv_values as $rows){
		$classname = ($k%2==0)?'table-secondary':'table-primary';
				//$source_text = ((isset($file_type) && ($file_type == 'pdf' || $file_type == 'docx' || $file_type == 'csv' ||$file_type == 'txt' ||$file_type == 'xlx'))? trim($rows) : $rows[0]);
				$source_text = trim($rows);
				$where_status='source_text = "'.nl2br($source_text).'"';
				//echo $where_status;die;
				/*$res=DB::table('file_temp')
    ->orWhere('source_text', '=', str_replace("<br/>","\n",nl2br($source_text)))
    ->get();*/
				/*$res=$file_temp->havingRaw($where_status)->first();
				print_r($res);die;*/
				if(!empty($source_text) || $source_text != ''){
				$get_target_text=gettabledata('file_temp','target_text',['source_text'=>nl2br($source_text),'file_tr_id'=>$file_tr_id]);
				$word_count_data[]=$source_text;
				$source_language  = $sourcelanguage;
				$target_languages = $targetlanguages;
				$target_text =$get_target_text;
				$tm_flag = "";
				$flag_tm = "";
				$flag_gt = "";
				$flag_ts = "";			
		$arr_data['p']=$p;
		$arr_data['classname']=$classname;
		$arr_data['source_language']=$source_language;
		$arr_data['source_text']=$source_text;
		$arr_data['target_languages']=$target_languages;
		$arr_data['target_text']=$target_text;
		$arr_data['flag_gt']=$flag_gt;
		$arr_data['flag_tm']=$flag_tm;
		$arr_data['flag_ts']=$flag_ts;
		$arr_data['tm_flag']=$tm_flag;
		$arr_data['domain_id']=$domain_id;
		$response_data[]=$arr_data;
			$k++;
			$p++;
			}
		 }
		//  echo "<pre/>";
		// print_r($response_data);die;
		// $s_to_json=json_encode(utf8ize((array)$response_data));
		//$s_to_json=json_encode(utf8ize((array)$response_data),JSON_UNESCAPED_UNICODE);
		$s_to_json=json_encode((array)$response_data,JSON_UNESCAPED_UNICODE);

		// echo "<pre/>";
		// print_r(json_decode($s_to_json));die;
		//$s_to_json=json_encode((array)$response_data,JSON_UNESCAPED_UNICODE);
		/*echo "<pre/>";
		print_r(json_decode($s_to_json));die;*/
		?>
		<div class="row">&nbsp;</div>
		<p><?php matching_word_percantage($word_count_data,$translation_memory_data) ?></p>
		<div class="row">
			<div class="col-sm">Translation Memory Indication:- <img src="{{URL::asset('/img/translation_memory.jpg')}}" border="0"></div>
			<div class="col-sm">Translation Suggestion Indication:- <img src="{{URL::asset('/img/translation_suggestion.jpg')}}" border="0"></div>
		
			<div class="col-sm">Total Word Count  :<?php  //$word_countss=  get_word_count($word_count_data); ?> <?php echo $file_tr_data->word_count; ?></div>
			<div class="col-sm">Repeated Word Count :<?php echo $file_tr_data->repeated_words; ?></div>
			<div class="col-sm"><input type="button" name="btn_segment"  class="btn btn-success" id="btn_gtranslate_segment_all" value="Translate All"></div>
		</div>
		<div class="row">&nbsp;</div>
		<div class="row">&nbsp;</div>

		
	<div id="translation_data"></div>
	 <div id="loading">
      Loading Please Wait......
     </div>
	<?php 			 
			 	
		if(count($arr_csv_values)>0){			
	?>
		<div class="row">&nbsp;</div>
		<div class="row">
				<input type="hidden" name="domain_id" value="<?php echo $domain_id;?>">
				<div class="col-sm" style="text-align:right;"><input type="button" name="btn_segment"  class="btn btn-success" id="btn_segment" value="Download"></div>
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

<style type="text/css">

#translation_data{
  display: block;
  max-height: 600px;
  overflow-y: scroll;
}

/*table thead, table tbody tr {
  display: table;
  width: 100%;
  table-layout: fixed;
}*/
    #loading{
      display:none;
      width: 100%;
      padding:5px 10px;
      position: fixed;
      bottom: 0;
      left: 0;
      text-align: center;
      color:white;
      background: rgba(0,0,23,0.71);
      box-shadow: 0 0 10px black; 
    }
</style>

<script>
 
  /* Google Translate */	 
	 $(document.body).on('click', "#btn_gtranslate_segment", function(e){			
        $('#error_on_header').html('');		
			
		var btn_id = $(this).attr('btn_id');		
		var target_lange_code 	= $(this).attr('target_lange_code');
        var source_text 	  	= $(this).attr('source_text');		
		gtranslate_data(target_lange_code,source_text,btn_id);			
		/*var accessurl = '{{ route("admin.kptaemrequest.googletranslate") }}';	
		
		
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
        });*/
    });
	/* Google Translate */ 
	function gtranslate_data(target_lange_code,source_text,btn_id) {
		$('#error_on_header').html('');		
			
		/*var btn_id = $(this).attr('btn_id');		
		var target_lange_code 	= $(this).attr('target_lange_code');
        var source_text 	  	= $(this).attr('source_text');*/		
						
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
					if(data != ''){
						savefiledata(btn_id,data);
					}					
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
            /*alert(btn_id);
            alert(target_lange_code);
            alert(source_text);*/
            }/* else {
                alert('hi');
               
            }*/
        });
	});
var aem_source_language = '<?php echo $sourcelanguage;?>';
	var aem_target_language = '<?php echo $targetlanguages;?>';		
//$("textarea").keyup(function(e) {
$(document.body).on('keyup', "textarea", function(e){
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
$(document.body).on('click','.suggestions_data',function(){
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

var s_to_json = <?php echo $s_to_json ?? '';?>;
    var row_count=0;
      $(document).ready(function(){
        setTimeout("appendContent()", 10);
      });
      var appendContent=function(){
        var j=1;
        for(var i=row_count;i<s_to_json.length;i++){
          var tm_data=s_to_json[i];
        if(j<=20){
        	var target_text =tm_data.text;
        	if(target_text == ''){

        	}
        	var btn_id=tm_data.p;
        	if(target_text == ''){
				var tm_flag = "";
				var flag_tm = "style='display:none';";
				var flag_gt = "style='display:none';";
				var flag_ts = "style='display:none';";
			}else{
				var target_text_data = "#target_text_"+btn_id;
				var show_gt 	= "#show_gt_"+btn_id;
				var show_tm		= "#show_tm_"+btn_id;
				var show_ts 	= "#show_ts_"+btn_id;
			}
          $('#translation_data').append('<div class="row '+tm_data.classname+'" style="padding:10px;"><div class="col"><textarea name="source_text[]" id="source_text'+btn_id+'" rows="2" cols="60" class="form-control" readonly="">'+tm_data.source_text+'</textarea></div><div class="col"><textarea name="target_text[]" onchange="savefiledata('+btn_id+',this.value)" id="target_text_'+tm_data.p+'" rows="2" cols="60" class="form-control" btn_id="'+tm_data.p+'" target_lange_code="'+tm_data.target_languages+'" source_text="'+tm_data.source_text+'" required>'+tm_data.target_text+'</textarea><ul id="suggestions_list_'+tm_data.p+'" class="suggestions_list" style="display: none;"></ul></div><div class="col-md-2"><img src="{{URL::asset('/img/translation_memory.jpg')}}" border="0"  id="show_gt_'+tm_data.p+'" '+tm_data.flag_gt+'>&nbsp;<img src="{{URL::asset('/img/translation_memory.jpg')}}" border="0" id="show_tm_'+tm_data.p+'" '+tm_data.flag_tm+'>&nbsp;<img src="{{URL::asset('/img/translation_suggestion.jpg')}}" id="show_ts_'+tm_data.p+'" border="0" '+tm_data.flag_ts+'>&nbsp;&nbsp;<a type="button" name="btn_segment"  class="btn btn-success" btn_id="'+tm_data.p+'" id="btn_gtranslate_segment" target_lange_code="'+tm_data.target_languages+'" source_text="'+tm_data.source_text+'"><i class="fa fa-language" aria-hidden="true"></i></a></div><input type="hidden" name="translated_flag[]" id="translated_flag_'+tm_data.p+'" value="'+tm_data.tm_flag+'"></div><input type="hidden" name="hid_source_language" id="hid_source_language" value="'+tm_data.source_language+'"><input type="hidden" name="hid_target_language" id="hid_target_language" value="'+tm_data.target_languages+'">');

          
        row_count++;j++;
        var source_text=tm_data.source_text;
	        //if(target_text_data == ''){
	        	gettargettext(source_text,btn_id);
	    	//}
    	}
    }
        //gettargettexttranslate();
        $('#loading').fadeOut();
      };
   // $(function () {
     
      $('#translation_data').bind('scroll', function()
      {
        if($(this).scrollTop() + $(this).innerHeight()>=$(this)[0].scrollHeight)
        {
        	if(row_count < s_to_json.length){
             $('#loading').fadeIn();
          	 setTimeout("appendContent()", 1000);
  			}
        }
      });
	/*var aem_source_language = $("#hid_source_language").val();
	var aem_target_language = $("#hid_target_language").val();*/
	
//});
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }	



//function gettargettext($source_language,$target_languages,$source_text,$domain_id) {
function gettargettext($source_text,btn_id) {//alert($source_text);
	//alert($source_language+','+$target_languages+','+$source_text+','+$domain_id);
	var source_text=$('#source_text'+btn_id).val();
	var $source_language='<?php echo $sourcelanguage;?>';
	var $target_languages='<?php echo $targetlanguages;?>';
	var $domain_id='<?php echo $domain_id;?>';
	var $file_tr_id='<?php echo $file_tr_id;?>';
	$.ajax({
        url: '{{ route("admin.translationcsvfile.gettargettext") }}',
        type: 'post',
        data: {
			"_token": "{{ csrf_token() }}",
			'source_language':$source_language,
			'target_languages':$target_languages,
			'source_text':$source_text,
			'domain_id':$domain_id,
			'file_tr_id':$file_tr_id
        },
        success: function(data) {
            //return result;
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
			/*if(data != ''){
				savefiledata(btn_id,data);
			}*/
        },
     error:function(){
         //alert("Error");
         console.log('Something went wrong!!!');
     } 
    });
}
function gettargettexttranslate() {
	$("[name^=target_text]").each(function(index, value) {
            if ($(this).val() == '') {
            var btn_id = $(this).attr('btn_id');
			var target_lange_code 	= $(this).attr('target_lange_code');
        	var source_text=$('#source_text'+btn_id).val();
			gettargettext(source_text,btn_id);
            }
    });
}

function savefiledata(btn_id,target_text){
	var source_text=$('#source_text'+btn_id).val();
	var file_tr_id='<?php echo $file_tr_id;?>';
	$.ajax({
		url: '{{ route("admin.translationcsvfile.savefiledata") }}',		
		type: 'post',
		dataType:'json',
        data: {
			"_token": "{{ csrf_token() }}",
			'source_text':source_text,
			'target_text':target_text,
			'file_tr_id':file_tr_id
        },
        success: function(data) {
        	if(data.status==1){
				toastr.success(data.msg);
        	}else if(data.status==0){
				toastr.error(data.msg);
        	}
		}
	});

}




</script>

@endsection