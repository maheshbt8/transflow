$(document).ready(function(){
   
	
	
	
	/* Add Rate Card submit start here */
	$("#submit_btn").click(function(){
        var source_language 			= 	$("#id_source_language").val();
		var target_language				=	$("#id_target_language").val();
        var currency 					= 	$("#id_currency").val();
        var words_count 				= 	$("#id_words_count").val();
		var id_ratecard_translation_price	= 	$("#id_ratecard_translation_price").val();
		var id_ratecard_proofreading_price	= 	$("#id_ratecard_proofreading_price").val();	
		
        if(source_language==""){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please select Source Language</div>');
            $("#id_language").focus();
            return false;
        }
		
		 if(target_language==""){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please select Target Language</div>');
            $("#id_language").focus();
            return false;
        }    


		
	   if(currency==""){
           $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please select Currency</div>');
            $("#id_currency").focus();
            return false;
        }
        if(words_count==""){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please Enter Word Count</div>');
            $("#id_words_count").focus();
            return false;
        }
		if(words_count !=""){
			if(!$.isNumeric(words_count)) {				
				$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
				$("#id_words_count").focus();
				return false;
			}
		}
		
		if(id_ratecard_translation_price==""){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please Enter Translation Price Value</div>');
            $("#id_ratecard_translation_price").focus();
            return false;
        }		
		
		if(id_ratecard_translation_price !=""){
			if(!$.isNumeric(id_ratecard_translation_price)) {				
				$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
				$("#id_ratecard_translation_price").focus();
				return false;
			}			
		}


		if(id_ratecard_proofreading_price==""){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please Enter Proofreading Price Value</div>');
            $("#id_ratecard_proofreading_price").focus();
            return false;
        }		
		
		if(id_ratecard_proofreading_price !=""){
			if(!$.isNumeric(id_ratecard_proofreading_price)) {				
				$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
				$("#id_ratecard_proofreading_price").focus();
				return false;
			}			
		}		
		
    });
	/* Add Rate Card submit end here */
	
	/* Edit Rate card update start here */	
	$("#update_btn").click(function(){		
		var id_ratecard_translation_price	= 	$("#id_ratecard_translation_price").val();	
		var id_ratecard_proofreading_price	= 	$("#id_ratecard_proofreading_price").val();	
		
		if(id_ratecard_translation_price==""){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please Enter Translation Price Value</div>');
            $("#id_ratecard_translation_price").focus();
            return false;
        }		
		
		if(id_ratecard_translation_price !=""){
			if(!$.isNumeric(id_ratecard_translation_price)) {				
				$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
				$("#id_ratecard_translation_price").focus();
				return false;
			}			
		}		
		
		if(id_ratecard_proofreading_price==""){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please Enter Proofreading Price Value</div>');
            $("#id_ratecard_proofreading_price").focus();
            return false;
        }		
		
		if(id_ratecard_proofreading_price !=""){
			if(!$.isNumeric(id_ratecard_proofreading_price)) {				
				$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
				$("#id_ratecard_proofreading_price").focus();
				return false;
			}			
		}
		
		
	});
	/* Edit Rate card update end here */
	$("#id_btn_getquote_update").click(function(){		
		var id_status_of_quote	= 	$("#id_status_of_quote").val();	
	    var id_kpt_invoice_number	        = 	$("#id_kpt_invoice_number").val();	
	    var id_kpt_total_amount          	= 	$("#id_kpt_total_amount").val();	
		
		if(id_status_of_quote==""){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please Select Status Of The Quote</div>');
            $("#id_status_of_quote").focus();
            return false;
        }	
		if(id_kpt_invoice_number=="" && id_status_of_quote == 'Invoiced'){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please Enter KPT Invoice Number</div>');
            $("#id_kpt_invoice_number").focus();
            return false;
        }	
		if(id_kpt_total_amount=="" && id_status_of_quote == 'Invoiced'){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please Enter Total Amount</div>');
            $("#id_kpt_total_amount").focus();
            return false;
        }	
		
		if(id_kpt_total_amount!=""){
			if(!$.isNumeric(id_kpt_total_amount)) {
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please Enter Total Amount Only In Numbers</div>');
            $("#id_kpt_total_amount").focus();
            return false;
        }	
		}	
		
		
	});
	// edit quoteupdate start here


	// edit quoteupdate End here
	
	
	/* Reset Rate card start here */
	$("#btn_reset").click(function(){		
		$("#id_language").val('');
        $("#id_currency").val('');
        $("#id_words_count").val('');
		$("#id_translation").val('');
		$("#id_proofreading").val('');
		$("#id_quality_assurance").val('');	
	 });	
	/* Reset Rate card end here */
	
	
	
	/* Add Dynamic form elements for Generate Quotation start here */
	var addrow = 0;
	 $(document.body).on('click', "#quote_generation_dynamic_field", function(e){ 
		addrow++;
        $.ajax({
            type: "post",
            dataType: "html",
            url : base_url+"quotegeneration/generate_quote_dynamic_fields",  
			data: {                
                "addrow":addrow               
            },
            beforeSend: function( xhr ) {
            },
            success: function(data) {
					$("#add_rows_quote_"+addrow).html(data);               	    
            },
			error: function(){
                alert("Error try again or contact administrator");
                return false;
            }
        });
    });
	/* Add Dynamic form elements for Generate Quotation start here */




/* Add Dynamic form elements for Generate Quotation start here */
	var addrow_page = 0;
	 $(document.body).on('click', "#quote_generation_page_dynamic_field", function(e){ 
		addrow_page++;
        $.ajax({
            type: "post",
            dataType: "html",
            url : base_url+"quotegeneration/generate_page_quote_dynamic_fields",  
			data: {                
                "addrow":addrow_page               
            },
            beforeSend: function( xhr ) {
            },
            success: function(data) {
					$("#add_rows_page_quote_"+addrow_page).html(data);               	    
            },
			error: function(){
                alert("Error try again or contact administrator");
                return false;
            }
        });
    });
	/* Add Dynamic form elements for Generate Quotation start here */		
	
	
		
	
	
	
	 /* To check all chceckbox with have the given class (campaign mode) */
    $('.select_all').click(function(event) {  
        $heading = $(this);
        if(this.checked) { 
            $('.select_one').each(function() { 
                $('#ratecard_delete').attr('disabled',false); 
                $('#ratecard_status').attr('disabled',false);                
                this.checked = true;            
            });
        }else{
            $('.select_one').each(function() { 
                $('#ratecard_delete').attr('disabled',true);
                $('#ratecard_status').attr('disabled',true);                
                this.checked = false;                     
            });       
        }
    });	
	/* To check all chceckbox with have the given class (campaign mode) */
	
	
	//This function is used to check whenere input is empty or not	
	$('.select_one').click(function(event) {  //on click       
       var count = $('input.select_one:checked').length;
       var check_box_count = $('input.select_one').length;      
       if(count == check_box_count){
           $('input.select_all').attr('checked', true);       
           $('#ratecard_delete').attr('disabled',false); 
           $('#ratecard_status').attr('disabled',false);      
       }else{
           $('input.select_all').attr('checked', false);       
           $('#ratecard_delete').attr('disabled',true);
           $('#ratecard_status').attr('disabled',true);           
       }
	   
       if(count == 0){
            $('input.select_all').attr('checked', false);              
            $('#ratecard_delete').attr('disabled',true);
            $('#ratecard_status').attr('disabled',true);           
       }else{
              $('#ratecard_delete').attr('disabled',false); 
              $('#ratecard_status').attr('disabled',false);  
       }
	   
       });	
	//This function is used to check whenere input is empty or not
	
	var pdf_getquote=0;
	$("#id_btn_getquote").click(function(){	
		var id_type_request				=	$("#type_request").val();
		var id_source_lan				=	$("#source_lan").val();
		var id_target_lan				=	$("#target_lan").val();
		var id_cuurent_date 			= 	$("#id_cuurent_date").val();
		var id_to_address				=	$("#id_to_address").val();
		var id_subject					=	$("#id_subject").val();
		var id_price_description		=   $("#id_price_description").val();
		var id_page_price_description	=   $("#id_page_price_description").val();
		var id_words_count				= 	$("#id_words_count").val();
		var id_cost_per_word			= 	$("#id_cost_per_word").val();
		var id_terms_of_use				=   $("#id_terms_of_use").val();
	  
	

        if(id_type_request=="" || id_type_request == null ){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Type of the Request</div>');
            $("#type_request").focus();
            return false;
        }

        if(id_source_lan=="" || id_source_lan == null ){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Source Languages</div>');
            $("#source_lan").focus();
            return false;
        }

        if(id_target_lan=="" || id_target_lan == null ){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Target Languages</div>');
            $("#target_lan").focus();
            return false;
        }
		
		
		if(id_cuurent_date==""){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Date</div>');
            $("#id_cuurent_date").focus();
            return false;
        }
		
		if(id_to_address == ""){
			$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter To Address</div>');
            $("#id_to_address").focus();
            return false;
			
		}
		
		if(id_subject == ""){
			$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Subject</div>');
            $("#id_subject").focus();
            return false;			
		}
		
		
		
		/*if(id_price_description == ""){
			$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Description</div>');
            $("#id_price_description").focus();
            return false;			
		}
		
		if(id_words_count == ""){
			$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Word Count</div>');
            $("#id_words_count").focus();
            return false;			
		}
		
		
		if(id_words_count !=""){
				if(!$.isNumeric(id_words_count)) {				
					$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
					$("#id_words_count").focus();
					return false;
				}
		}
		
		if(id_cost_per_word == ""){
			$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Cost Per Word</div>');
            $("#id_cost_per_word").focus();
            return false;			
		}
		
		if(id_cost_per_word !=""){
				if(!$.isNumeric(id_cost_per_word)) {				
					$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
					$("#id_cost_per_word").focus();
					return false;
				}
		}*/ 

		var dataValid = true;
		$("[name^=price_description]").each(function(index, value){
		    if ($(this).val() == ''){
		    	$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Description</div>');
		        $(this).focus();
		        dataValid = false;
		        return false;
		    }else{
		    	var id_fixed_minutes_value_r=$('input[name="fixed_minutes_value\\[\\]"]').eq(index).val();
		    	if(id_fixed_minutes_value_r == ""){
		    	var id_words_count_r=$('input[name="words_count\\[\\]"]').eq(index).val();
		    	if(id_words_count_r == ""){
					$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Word Count</div>');
		            $('input[name="words_count\\[\\]"]').eq(index).focus();
		            dataValid = false;
		            return false;			
				}

				if(id_words_count_r !=""){
						if(!$.isNumeric(id_words_count_r)) {				
							$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
							$('input[name="words_count\\[\\]"]').eq(index).focus();
							dataValid = false;
							return false;
						}
				}

		    	var id_cost_per_word_r=$('input[name="cost_per_word\\[\\]"]').eq(index).val();
		    	if(id_cost_per_word_r == ""){
					$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Cost Per Word</div>');
		            $('input[name="cost_per_word\\[\\]"]').eq(index).focus();
		            dataValid = false;
		            return false;			
				}

				if(id_cost_per_word_r !=""){
						if(!$.isNumeric(id_cost_per_word_r)) {				
							$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
							$('input[name="cost_per_word\\[\\]"]').eq(index).focus();
							dataValid = false;
							return false;
						}
				}
			}else{
				if(!$.isNumeric(id_fixed_minutes_value_r)) {				
					$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
					$('input[name="fixed_minutes_value\\[\\]"]').eq(index).focus();
					dataValid = false;
					return false;
				}
			}
		    }
		});

		if (dataValid){
		   // send data
		} else {
		   return false;
		}

		var dataValid1 = true;
		var empty = false;
		$("[name^=page_price_description]").each(function() {
		   if ($(this).val() != "") {
		      empty = true;
		      return false;
		   }
		});
		if(empty){
		$("[name^=page_price_description]").each(function(index, value){
		    if ($(this).val() == ''){
		    	$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Description</div>');
		        $(this).focus();
		        dataValid1 = false;
		        return false;
		    }else{
		    	var id_fixed_page_value_r=$('input[name="fixed_page_value\\[\\]"]').eq(index).val();
		    	if(id_fixed_page_value_r == ""){
		    	var id_page_count_r=$('input[name="page_count\\[\\]"]').eq(index).val();
		    	if(id_page_count_r == ""){
					$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Word Count</div>');
		            $('input[name="page_count\\[\\]"]').eq(index).focus();
		            dataValid1 = false;
		            return false;			
				}

				if(id_page_count_r !=""){
						if(!$.isNumeric(id_page_count_r)) {				
							$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
							$('input[name="page_count\\[\\]"]').eq(index).focus();
							dataValid1 = false;
							return false;
						}
				}

		    	var id_cost_per_page_r=$('input[name="cost_per_page\\[\\]"]').eq(index).val();
		    	if(id_cost_per_page_r == ""){
					$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Cost Per Word</div>');
		            $('input[name="cost_per_page\\[\\]"]').eq(index).focus();
		            dataValid1 = false;
		            return false;			
				}

				if(id_cost_per_page_r !=""){
						if(!$.isNumeric(id_cost_per_page_r)) {				
							$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
							$('input[name="cost_per_page\\[\\]"]').eq(index).focus();
							dataValid1 = false;
							return false;
						}
				}
				}else{
					if(!$.isNumeric(id_fixed_page_value_r)) {				
						$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
						$('input[name="fixed_page_value\\[\\]"]').eq(index).focus();
						dataValid1 = false;
						return false;
					}
				}
		    }
		});

			if (dataValid1){
			   // send data
			} else {
			   return false;
			}
		}

		var dataValid2 = true;
		var empty2 = false;
		$("[name^=minute_price_description]").each(function() {
		   if ($(this).val() != "") {
		      empty2 = true;
		      return false;
		   }
		});
		if(empty2){
		$("[name^=minute_price_description]").each(function(index, value){
		    if ($(this).val() == ''){
		    	$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Description</div>');
		        $(this).focus();
		        dataValid2 = false;
		        return false;
		    }else{
		    	var id_minute_fixed_minutes_value_r=$('input[name="minute_fixed_minutes_value\\[\\]"]').eq(index).val();
		    	if(id_minute_fixed_minutes_value_r == ""){
		    	var id_minute_words_count_r=$('input[name="minute_words_count\\[\\]"]').eq(index).val();
		    	if(id_minute_words_count_r == ""){
					$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Word Count</div>');
		            $('input[name="minute_words_count\\[\\]"]').eq(index).focus();
		            dataValid2 = false;
		            return false;			
				}

				if(id_minute_words_count_r !=""){
						if(!$.isNumeric(id_minute_words_count_r)) {				
							$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
							$('input[name="minute_words_count\\[\\]"]').eq(index).focus();
							dataValid2 = false;
							return false;
						}
				}

		    	var id_minute_cost_per_word_r=$('input[name="minute_cost_per_word\\[\\]"]').eq(index).val();
		    	if(id_minute_cost_per_word_r == ""){
					$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Cost Per Word</div>');
		            $('input[name="minute_cost_per_word\\[\\]"]').eq(index).focus();
		            dataValid2 = false;
		            return false;			
				}

				if(id_minute_cost_per_word_r !=""){
						if(!$.isNumeric(id_minute_cost_per_word_r)) {				
							$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
							$('input[name="minute_cost_per_word\\[\\]"]').eq(index).focus();
							dataValid2 = false;
							return false;
						}
				}
				}else{
					if(!$.isNumeric(id_minute_fixed_minutes_value_r)) {				
						$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
						$('input[name="minute_fixed_minutes_value\\[\\]"]').eq(index).focus();
						dataValid2 = false;
						return false;
					}
				}
		    }
		});

			if (dataValid2){
			   // send data
			} else {
			   return false;
			}
		}
		$('#error_on_header').html('');
		pdf_getquote=1;
	});
	 /* Translation Quote Generation end here */
	 
	 
	 if(pdf_getquote == 1){
	 	alert('hi');	
	 }
	 
	 
	 
	 
	  
	 $("#id_status_of_quote").change(function(){  
		var statusofquote = $(this).val();
		id_status_of_quote(statusofquote);
	 });
$(document).ready(function(){
	var statusofquote = $('#id_status_of_quote').val();
	id_status_of_quote(statusofquote);
});
	 function id_status_of_quote(statusofquote) {
	 	var disabled_vendor_name=$('#id_vendor_name').val();
	 	var disabled_payment_type=$('#id_payment_type').val();
	 	if(statusofquote == "Delivered"){			
			$("#div_source_file1").show();			
			$("#div_source_file2").hide();			
			$("#id_kpt_invoice_number").prop("readonly", true);
			$("#id_kpt_invoice_date").prop("readonly", true);			
			$("#id_kpt_total_amount").prop("readonly", true);					
			$("#id_vendor_name").prop("disabled", false);
			$("#disabled_vendor_name").html('');		
			$("#id_vendor_invoice_number").prop("readonly", true);
			$("#id_vendor_invoice_date").prop("readonly", true);			
			$("#id_vendor_total_amount").prop("readonly", false);
			$("#id_payment_type").prop("disabled", true);
			$("#disabled_payment_type").html('<input type="hidden" name="payment_type" value="'+disabled_payment_type+'"/>');			
			$("#id_payment_invoice_number").prop("readonly", true);
			$("#id_payment_invoice_date").prop("readonly", true);
		}else if(statusofquote == "Invoiced"){	
			$("#div_source_file1").hide();			
			$("#div_source_file2").hide();			
			$("#id_kpt_invoice_number").prop("readonly", false);			
			$("#id_kpt_invoice_date").prop("readonly", false);			
			$("#id_kpt_total_amount").prop("readonly", false);					
			$("#id_vendor_name").prop("disabled", true);
			$("#disabled_vendor_name").html('<input type="hidden" name="vendor_name" value="'+disabled_vendor_name+'"/>');			
			$("#id_vendor_invoice_number").prop("readonly", true);			
			$("#id_vendor_invoice_date").prop("readonly", true);			
			$("#id_vendor_total_amount").prop("readonly", true);
			$("#id_payment_type").prop("disabled", true);
			$("#disabled_payment_type").html('<input type="hidden" name="payment_type" value="'+disabled_payment_type+'"/>');		
			$("#id_payment_invoice_number").prop("readonly", true);
			$("#id_payment_invoice_date").prop("readonly", true);
		}else if(statusofquote == "Billed"){			
			$("#div_source_file1").hide();		
			$("#div_source_file2").hide();		
			$("#id_kpt_invoice_number").prop("readonly", true);			
			$("#id_kpt_invoice_date").prop("readonly", true);			
			$("#id_kpt_total_amount").prop("readonly", true);					
			$("#id_vendor_name").prop("disabled", false);	
			$("#disabled_vendor_name").html('');		
			$("#id_vendor_invoice_number").prop("readonly", false);			
			$("#id_vendor_invoice_date").prop("readonly", false);			
			$("#id_vendor_total_amount").prop("readonly", false);
			$("#id_payment_type").prop("disabled", true);
			$("#disabled_payment_type").html('<input type="hidden" name="payment_type" value="'+disabled_payment_type+'"/>');			
			$("#id_payment_invoice_number").prop("readonly", true);
			$("#id_payment_invoice_date").prop("readonly", true);
		}else if(statusofquote == "Paid"){			
			$("#div_source_file1").hide();		
			$("#div_source_file2").hide();		
			$("#id_kpt_invoice_number").prop("readonly", true);			
			$("#id_kpt_invoice_date").prop("readonly", true);			
			$("#id_kpt_total_amount").prop("readonly", true);					
			$("#id_vendor_name").prop("disabled", true);
			$("#disabled_vendor_name").html('<input type="hidden" name="vendor_name" value="'+disabled_vendor_name+'"/>');						
			$("#id_vendor_invoice_number").prop("readonly", true);			
			$("#id_vendor_invoice_date").prop("readonly", true);			
			$("#id_vendor_total_amount").prop("readonly", true);
			$("#id_payment_type").prop("disabled", false);
			$("#disabled_payment_type").html('');
			$("#id_payment_invoice_number").prop("readonly", false);
			$("#id_payment_invoice_date").prop("readonly", false);
		}else if(statusofquote == "Assign"){			
			$("#div_source_file1").hide();		
			$("#div_source_file2").show();		
			$("#id_kpt_invoice_number").prop("readonly", true);			
			$("#id_kpt_invoice_date").prop("readonly", true);			
			$("#id_kpt_total_amount").prop("readonly", true);					
			$("#id_vendor_name").prop("disabled", true);
			$("#disabled_vendor_name").html('<input type="hidden" name="vendor_name" value="'+disabled_vendor_name+'"/>');						
			$("#id_vendor_invoice_number").prop("readonly", true);			
			$("#id_vendor_invoice_date").prop("readonly", true);			
			$("#id_vendor_total_amount").prop("readonly", true);
			$("#id_payment_type").prop("disabled", true);
			$("#disabled_payment_type").html('<input type="hidden" name="payment_type" value="'+disabled_payment_type+'"/>');			
			$("#id_payment_invoice_number").prop("readonly", true);
			$("#id_payment_invoice_date").prop("readonly", true);
		}else{			
			$("#div_source_file1").hide();	
			$("#id_kpt_invoice_number").prop("readonly", true);			
			$("#id_kpt_invoice_date").prop("readonly", true);			
			$("#id_kpt_total_amount").prop("readonly", true);
			$("#id_vendor_name").prop("disabled", true);
			$("#disabled_vendor_name").html('<input type="hidden" name="vendor_name" value="'+disabled_vendor_name+'"/>');	
			$("#id_vendor_invoice_number").prop("readonly", true);
			$("#id_vendor_invoice_date").prop("readonly", true);			
			$("#id_vendor_total_amount").prop("readonly", true);
			$("#id_payment_type").prop("disabled", true);
			$("#disabled_payment_type").html('<input type="hidden" name="payment_type" value="'+disabled_payment_type+'"/>');			
			$("#id_payment_invoice_number").prop("readonly", true);
			$("#id_payment_invoice_date").prop("readonly", true);
		}
	 }
    
	 
	  
	  
	  
	  /* Minute Quote Generation start here */
	$("#id_btn_getquote_per_minute").click(function(){	
		var id_cuurent_date 			= 	$("#id_cuurent_date").val();
		var id_to_address				=	$("#id_to_address").val();
		var id_subject					=	$("#id_subject").val();
		var id_price_description		=   $("#id_price_description").val();
		var id_words_count				= 	$("#id_words_count").val();
		var id_cost_per_word			= 	$("#id_cost_per_word").val();
		var id_terms_of_use				=   $("#id_terms_of_use").val();       
		
        if(id_cuurent_date==""){
            $('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Date</div>');
            $("#id_cuurent_date").focus();
            return false;
        }		
		if(id_to_address == ""){
			$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter To Address</div>');
            $("#id_to_address").focus();
            return false;			
		}
		
		if(id_subject == ""){
			$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Subject</div>');
            $("#id_subject").focus();
            return false;			
		}
		
		if(id_price_description == ""){
			$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Description</div>');
            $("#id_price_description").focus();
            return false;			
		}
		
		if(id_words_count == ""){
			$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Word Count</div>');
            $("#id_words_count").focus();
            return false;			
		}		
		
		if(id_words_count !=""){
				if(!$.isNumeric(id_words_count)) {				
					$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
					$("#id_words_count").focus();
					return false;
				}
		}
		
		if(id_cost_per_word == ""){
			$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter Cost Per Word</div>');
            $("#id_cost_per_word").focus();
            return false;			
		}
		
		if(id_cost_per_word !=""){
				if(!$.isNumeric(id_cost_per_word)) {				
					$('#error_on_header').html('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong> Please enter the numbers instead of characters </div>');
					$("#id_cost_per_word").focus();
					return false;
				}
		}	
	  });
	  /* Minute Quote Generation end here */
	  
	  
	  
	  /* Add Dynamic form elements for Generate Quotation Per Minute start here */
	var minute_addrow = 0;
	 $(document.body).on('click', "#quote_generation_dynamic_field_per_minute", function(e){ 
		minute_addrow++;
        $.ajax({
            type: "post",
            dataType: "html",
            url : base_url+"quotegeneration/generate_quote_dynamic_fields_per_minute",  
			data: {                
                "addrow":minute_addrow               
            },
            beforeSend: function( xhr ) {
            },
            success: function(data) {
					$("#add_rows_quote_minute_"+minute_addrow).html(data);               	    
            },
			error: function(){
                alert("Error try again or contact administrator");
                return false;
            }
        });
    });
	/* Add Dynamic form elements for Generate Quotation Per Minute start here */



	/* Quote Generation Toggle start here */
	$('.optionstoggle').click(function () {	
	    var quote_id = $(this).attr('data-id');		
		var togstatus = $("#togglediv_" + quote_id).css('display');
		
        if (togstatus == 'none') {           
            $.ajax({
                type: "post",
                url: base_url + "quotegeneration/ajax_quote_generation_more_details",
                data: {
                    "quote_id": quote_id			 
                   
                },
                beforeSend: function (xhr) {

                },
                success: function (campdata) {					
                    $("#togglediv_" + quote_id).html(campdata);                   
                    $("#togglediv_" + quote_id).slideToggle("slow");
                },
                error: function () {
                    $('#box').trigger("click");
                    alert("Problem in getting Presto data");
                    return false;
                }
            });
        }else{         
            $("#togglediv_" + quote_id).slideToggle("slow");
        }
        return false;
    });
	/* Quote Generation Toggle end here */

	
	
	
	 /* file select */
    $('.icon_select_file').click(function(){       
        $('#uploadfile').parent().find('input').click();     
    });
    
    /* file validation when selecting a file */
    $("#uploadfile").change(function(){        
        $('#error_on_header').html(''); 
        var ext = $(this).val().split('.').pop().toLowerCase();

		if($.inArray(ext, ['zip','rar','gz','tar','doc','docx','xls','xlsx','txt','doc','pdf','ppt','PPT','pptx','PPTX','xls','xlsx','jpg','jpeg','rtf','html','xml','png','mp3','mp4','avi','flv','wmv','mov']) == -1) {          
           $(this).attr('value',''); 
           $('#error_on_header').html('<div class="alert alert-war"><a class="close" data-dismiss="alert">×</a><strong>Info! </strong>Please Select Proper file</div>');
        }
        else{ 
           var url = $(this).val().split('\\').pop(); 
           $("#icon_file_name").val(url);
		   $("#id_more_upload").show();
        }      
    });
	
	
	
	
	
 });
 
 
 
 
 
 function validate_dynamic_field(id){
	 var dyanmic_row_id = id;	 
	 var div = document.getElementById("dyanmic_fields_" + dyanmic_row_id);
		 div.parentNode.removeChild(div);	 
 }
 
 
 function validate_dynamic_field_page(id){
	 var dyanmic_row_id = id;	 
	 var div = document.getElementById("page_dyanmic_fields_" + dyanmic_row_id);
		 div.parentNode.removeChild(div);	 
 }
 
 
 function validate_dynamic_field_minute(id){
	 var dyanmic_row_id = id;	 
	 var div = document.getElementById("dyanmic_fields_minute_" + dyanmic_row_id);
		 div.parentNode.removeChild(div);	 
 }
 
 
 
     /* change Quotation status */
        $(document.body).on('click', ".quotation_action_status", function(e){
        var con = confirm($(this).attr('alert-msg'));
        if(con == false)
            return false;

        $('#error_on_header').html('');
        var translation_quote_id  = $(this).attr('translation_quote_id');
        var status        = $(this).attr('status');
                
        $.ajax({
            type: "post",
            dataType: "json",
            url : base_url+"quotegeneration/request_change_status",
            data: {                
                "translation_quote_id":translation_quote_id,
                "status":status
            },
            beforeSend: function( xhr ) {
            },
            success: function(response) {
            	$('#error_on_header').html('<div class="alert alert-success"><a class="close" data-dismiss="alert">×</a>'+response.msg+'</div>');
                setInterval(function(){ $('#form_association_loc_delete').submit(); }, 3000);   
            },
            error: function(){
                alert("Error try again or contact administrator");
                return false;
            }
        });
    });/* end change Quotation status */



      $('#export_btn').click(function(){
		  
		window.location.href=base_url+'quotegeneration/export_requests';
		return false;
		
	  });
