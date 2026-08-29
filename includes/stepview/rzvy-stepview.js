var rzvy_stripe, rzvy_stripe_plan_card;

var rzvy_loader = '<div class="rzvy-loader"><div class="spinner-border m-5" role="status"><span class="visually-hidden">Loading...</span></div></div>';								  
$(document).ready(function () {
    var site_url = generalObj.site_url;
	var ajax_url = generalObj.ajax_url;
    /* Initialize tooltips */
	$('#rzvy_main_wizard .nav-tabs > li a[title]').tooltip();
	
	/** Show Location selector Modal **/
	if(generalObj.location_selector == "Y"){
		$("#rzvy-location-selector-modal").modal("show");
	}
	
	/** Validation patterns **/
	$.validator.addMethod("pattern_name", function(value, element) {
		return this.optional(element) || /^[a-zA-Z '.']+$/.test(value);
	}, langObj.please_enter_only_alphabets);
	$.validator.addMethod("pattern_price", function(value, element) {
		return this.optional(element) || /^[0-9]\d*(\.\d{1,2})?$/.test(value);
	}, langObj.please_enter_only_numerics);
	
	$.validator.addMethod("pattern_phone", function(value, element) {
		return this.optional(element) || /\d+(?:[ -]*\d+)*$/.test(value);
	}, langObj.please_enter_valid_phone_number_without_country_code);
	$.validator.addMethod("pattern_zip", function(value, element) {
		return this.optional(element) || /^[a-zA-Z 0-9\-]*$/.test(value);
	}, langObj.please_enter_valid_zip);
	
	/** validate feedback form **/
	$('#rzvy_feedback_form').validate({
		rules: {
			rzvy_fb_name:{ required: true },
			rzvy_fb_email: { required:true, email:true },
			rzvy_fb_review: { required:true }
		},
		messages: {
			rzvy_fb_name:{ required: langObj.please_enter_name },
			rzvy_fb_email: { required: langObj.please_enter_email, email: langObj.please_enter_valid_email },
			rzvy_fb_review: { required: langObj.please_enter_review }
		}
	});
	
	/** JS to get First box on load with category, Service and Addons Selection **/
	$.ajax({
		type: 'post',
		data: {
			'get_first_step_box': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			/** To Enable Disable Menus START **/
			$('#rzvy_main_wizard a[data-toggle="tab"]').removeClass('active');
			$('#rzvy_main_wizard a[data-toggle="tab"]').addClass('disabled');
			$('.rzvy-first-step-tab').removeClass('disabled');
			$('.rzvy-first-step-tab').addClass('active');
			$(".rzvy-steps").removeClass("active");
			$(".rzvy-steps").removeClass("fade");
			$(".rzvy-steps").removeClass("show");
			$("#rzvy-first-step").addClass("fade show active");
			$("#rzvy-first-step").slideDown(1000);
			/** To Enable Disable Menus END **/
			
			$("#rzvy-first-step").html(res);

			/** Trigger Category On Page Load **
			if($('.rzvy-pcategories-selection').length>0){
				$('.rzvy-pcategories-selection.list_active').trigger('click');
			}else{
				var single_category_status = generalObj.single_category_status;
				if(single_category_status == "Y"){
					var countcats = 0;
					$('.rzvy-categories-radio-change').each(function(){		
						countcats++;
					});
					if(countcats==1){
						$('.rzvy-categories-radio-change').trigger('click');	
						$('.rzvy-category-container').hide();		
					}
				}
			}*/
			
			/** To Check If category Selected **/
			if($('.rzvy-pcategories-selection').length>0){
				$('.rzvy-pcategories-selection.list_active').trigger('click');
			}else{
				var single_category_status = generalObj.single_category_status;
				if(generalObj.booking_first_selection_as == "category"){
					if(single_category_status == "Y"){
						if($('.rzvy-categories-radio-change').length==1){
							$('.rzvy-categories-radio-change').trigger('click');	
							$('.rzvy-category-container').slideUp();
						}else{
							$('.rzvy-categories-radio-change.list_active').trigger('click');
							$('.rzvy-category-container').slideDown();
						}
					}else{
						$('.rzvy-categories-radio-change.list_active').trigger('click');
						$('.rzvy-category-container').slideDown();
					}
				}else{
					/** Auto Trigger Service Check **/
					var single_service_status = generalObj.single_service_status;
					if(single_service_status == "Y"){
						if($('.rzvy-services-radio-change').length==1){
							$('.rzvy-services-radio-change').trigger('click');	
							$('.rzvy-services-container').slideUp();
						}else{
							$('.rzvy-services-radio-change.list_active').trigger('click');
							$('.rzvy-services-container').slideDown();
						}
					}
					/** To Check If Service Selected on prev **/
					else{
						$('.rzvy-services-radio-change.list_active').trigger('click');
						$('.rzvy-services-container').slideDown();
					}
				}
			}
		}
	});
});

/* Function Payment Methods Refresh */
	function rzvy_sv_payment_method_refresh_func(){
		var ajax_url = generalObj.ajax_url;
		$.ajax({
			type: 'post',
			async:true,
			data: {
				'get_payment_methods': 1
			},
			url: ajax_url + "rzvy_front_stepview_ajax.php",
			success: function (response) {
				$(".rzvy_payment_methods_container").html(response);
				var rzvynet_amount = $('#rzvy_net_total_amount').data('amount');
				// * stripe check **
				var stripe_status = generalObj.stripe_status;
				if(stripe_status == "Y" && parseFloat(rzvynet_amount)>parseFloat(0)){
					var stripe_pkey = generalObj.stripe_pkey;
					if(stripe_pkey != ""){
						// * Create a Stripe client. *
						rzvy_stripe = Stripe(stripe_pkey);

						// * Create an instance of Elements. *
						var rzvy_stripe_elements = rzvy_stripe.elements();

						// * Custom styling can be passed to options when creating an Element. *
						var rzvy_stripe_plan_style = {
							base: {
								color: '#32325d',
								lineHeight: '18px',
								fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
								fontSmoothing: 'antialiased',
								fontSize: '16px',
								'::placeholder': {
									color: '#aab7c4'
								}
							},
							invalid: {
								color: '#fa755a',
								iconColor: '#fa755a'
							}
						};

						// * Create an instance of the card Element. *
						rzvy_stripe_plan_card = rzvy_stripe_elements.create('card', {style: rzvy_stripe_plan_style});

						// * Add an instance of the card Element. *
						rzvy_stripe_plan_card.mount('#rzvy_stripe_plan_card_element');
					}
				}
					
				// ** two checkout configuration **
				var twocheckout_status = generalObj.twocheckout_status;
				if(twocheckout_status == 'Y' && parseFloat(rzvynet_amount)>parseFloat(0)){
					$(function(){ TCO.loadPubKey('sandbox'); });
				}
			}
		});
	}	

$(document).bind("ready ajaxComplete", function(){
	var site_url = generalObj.site_url;
	var ajaxurl = generalObj.ajax_url;
    $('[data-toggle="tooltip"]').tooltip();

	$('#rzvy_start_slot').selectpicker();
	$('#rzvy_end_slot').selectpicker();
		
	/** card payment validation **/
	if(generalObj.authorizenet_status == "Y" || generalObj.twocheckout_status == "Y"){
		$('#rzvy-cardnumber').payment('formatCardNumber');
		$('#rzvy-cardcvv').payment('formatCardCVC');
		$('#rzvy-cardexmonth').payment('restrictNumeric');
		$('#rzvy-cardexyear').payment('restrictNumeric');
	}

	/** JS to add intltel input to phone number **/
	if(formfieldsObj.en_ff_phone_status == "Y"){
		$("#rzvy_user_phone").intlTelInput({ initialCountry: generalObj.defaultCountryCode, separateDialCode: true, utilsScript: site_url+"includes/vendor/intl-tel-input/js/utils.js" });
	}
	if(formfieldsObj.g_ff_phone_status == "Y"){
		$("#rzvy_guest_phone").intlTelInput({ initialCountry: generalObj.defaultCountryCode, separateDialCode: true, utilsScript: site_url+"includes/vendor/intl-tel-input/js/utils.js" });
	}
	
	/** validate login form **/
	$('#rzvy_login_form').validate({
		rules: {
			rzvy_login_email: { required:true, email:true },
			rzvy_login_password: { required:true, minlength: 8, maxlength: 20 }
		},
		messages: {
			rzvy_login_email: { required: langObj.please_enter_email, email: langObj.please_enter_valid_email },
			rzvy_login_password: { required: langObj.please_enter_password, minlength: langObj.please_enter_minimum_8_characters, maxlength: langObj.please_enter_maximum_20_characters },
		}
	});
	$('#rzvy_login_form_s3').validate({
		rules: {
			rzvy_login_email_s3: { required:true, email:true },
			rzvy_login_password_s3: { required:true, minlength: 8, maxlength: 20 }
		},
		messages: {
			rzvy_login_email_s3: { required: langObj.please_enter_email, email: langObj.please_enter_valid_email },
			rzvy_login_password_s3: { required: langObj.please_enter_password, minlength: langObj.please_enter_minimum_8_characters, maxlength: langObj.please_enter_maximum_20_characters },
		}
	});
	
	if(formfieldsObj.en_ff_firstname == "Y"){ var is_required_firstname = true; }else{ var is_required_firstname = false; }
	if(formfieldsObj.en_ff_lastname == "Y"){ var is_required_lastname = true; }else{ var is_required_lastname = false; }
	if(formfieldsObj.en_ff_phone == "Y"){ var is_required_phone = true; }else{ var is_required_phone = false; }
	if(formfieldsObj.en_ff_address == "Y"){ var is_required_address = true; }else{ var is_required_address = false; }
	if(formfieldsObj.en_ff_city == "Y"){ var is_required_city = true; }else{ var is_required_city = false; }
	if(formfieldsObj.en_ff_state == "Y"){ var is_required_state = true; }else{ var is_required_state = false; }
	if(formfieldsObj.en_ff_country == "Y"){ var is_required_country = true; }else{ var is_required_country = false; }
	if(formfieldsObj.en_ff_dob == "Y"){ var is_required_dob = true; }else{ var is_required_dob = false; }
	if(formfieldsObj.en_ff_notes == "Y"){ var is_required_notes = true; }else{ var is_required_notes = false; }
	
	if(formfieldsObj.g_ff_firstname == "Y"){ var is_required_gfirstname = true; }else{ var is_required_gfirstname = false; }
	if(formfieldsObj.g_ff_lastname == "Y"){ var is_required_glastname = true; }else{ var is_required_glastname = false; }
	if(formfieldsObj.g_ff_email == "Y"){ var is_required_gemail = true; }else{ var is_required_gemail = false; }
	if(formfieldsObj.g_ff_phone == "Y"){ var is_required_gphone = true; }else{ var is_required_gphone = false; }
	if(formfieldsObj.g_ff_address == "Y"){ var is_required_gaddress = true; }else{ var is_required_gaddress = false; }
	if(formfieldsObj.g_ff_city == "Y"){ var is_required_gcity = true; }else{ var is_required_gcity = false; }
	if(formfieldsObj.g_ff_state == "Y"){ var is_required_gstate = true; }else{ var is_required_gstate = false; }
	if(formfieldsObj.g_ff_country == "Y"){ var is_required_gcountry = true; }else{ var is_required_gcountry = false; }
	if(formfieldsObj.g_ff_dob == "Y"){ var is_required_gdob = true; }else{ var is_required_gdob = false; }
	if(formfieldsObj.g_ff_notes == "Y"){ var is_required_gnotes = true; }else{ var is_required_gnotes = false; }
	
	/** validate user detail form **/
	$("#rzvy_user_detail_form").validate({
		rules: {
			rzvy_user_email:{ required: true, email:true, remote: { 
				url:ajaxurl+"rzvy_check_email_ajax.php",
				type:"POST",
				async:false,
				data: {
					email: function(){ return $("#rzvy_user_email").val(); },
					check_front_email_exist: 1
				}
			} },
			rzvy_user_password: { required:true, minlength: 8, maxlength: 20 },
			rzvy_user_firstname:{ required: is_required_firstname, maxlength: 50 },
			rzvy_user_lastname: { required:is_required_lastname, maxlength: 50 },
			rzvy_user_phone: { required:is_required_phone, minlength: 10, maxlength: 15, pattern_phone:true },
			rzvy_user_address: { required:is_required_address },
			rzvy_user_city: { required:is_required_city },
			rzvy_user_state: { required:is_required_state },
			rzvy_user_zip: { required:true, pattern_zip:true, minlength: 5, maxlength: 10 },
			rzvy_user_country: { required:is_required_country },
			rzvy_user_dob: { required:is_required_dob },
			rzvy_user_notes: { required:is_required_notes },
		},
		messages: {
			rzvy_user_email:{ required: langObj.please_enter_email, email: langObj.please_enter_valid_email, remote: langObj.email_already_exist },
			rzvy_user_password: { required: langObj.please_enter_password, minlength: langObj.please_enter_minimum_8_characters, maxlength: langObj.please_enter_maximum_20_characters },
			rzvy_user_firstname:{ required: langObj.please_enter_first_name, maxlength: langObj.please_enter_maximum_50_characters },
			rzvy_user_lastname: { required: langObj.please_enter_last_name, maxlength: langObj.please_enter_maximum_50_characters },
			rzvy_user_phone: { required: langObj.please_enter_phone_number, minlength: langObj.please_enter_minimum_10_digits, maxlength: langObj.please_enter_maximum_15_digits },
			rzvy_user_address: { required: langObj.please_enter_address },
			rzvy_user_city: { required: langObj.please_enter_city },
			rzvy_user_state: { required: langObj.please_enter_state },
			rzvy_user_zip: { required: langObj.please_enter_state, minlength: langObj.please_enter_minimum_5_characters, maxlength: langObj.please_enter_maximum_10_characters },
			rzvy_user_country: { required: langObj.please_enter_country },
			rzvy_user_dob: { required: langObj.please_enter_birthdate },
			rzvy_user_notes: { required: langObj.please_enter_notes },
		}
	});
	
	/** validate guest user detail form **/
	$("#rzvy_guestuser_detail_form").validate({
		rules: {
			rzvy_guest_email:{ required: is_required_gemail, email:true, remote: { 
				url:ajaxurl+"rzvy_check_email_ajax.php",
				type:"POST",
				async:false,
				data: {
					email: function(){ return $("#rzvy_guest_email").val(); },
					check_front_email_exist: 1
				}
			} },
			rzvy_guest_firstname: { required: is_required_gfirstname, maxlength: 50 },
			rzvy_guest_lastname: { required:is_required_glastname, maxlength: 50 },
			rzvy_guest_phone: { required:is_required_gphone, minlength: 10, maxlength: 15, pattern_phone:true },
			rzvy_guest_address: { required:is_required_gaddress },
			rzvy_guest_city: { required:is_required_gcity },
			rzvy_guest_state: { required:is_required_gstate },
			rzvy_guest_zip: { required:true, pattern_zip:true, minlength: 5, maxlength: 10 },
			rzvy_guest_country: { required:is_required_gcountry },
			rzvy_guest_dob: { required:is_required_gdob },
			rzvy_guest_notes: { required:is_required_gnotes },
		},
		messages: {
			rzvy_guest_email:{ required: langObj.please_enter_email, email: langObj.please_enter_valid_email, remote: langObj.email_is_already_registered },
			rzvy_guest_firstname:{ required: langObj.please_enter_first_name, maxlength: langObj.please_enter_maximum_50_characters },
			rzvy_guest_lastname: { required: langObj.please_enter_last_name, maxlength: langObj.please_enter_maximum_50_characters },
			rzvy_guest_phone: { required: langObj.please_enter_phone_number, minlength: langObj.please_enter_minimum_10_digits, maxlength: langObj.please_enter_maximum_15_digits },
			rzvy_guest_address: { required: langObj.please_enter_address },
			rzvy_guest_city: { required: langObj.please_enter_city },
			rzvy_guest_state: { required: langObj.please_enter_state },
			rzvy_guest_zip: { required: langObj.please_enter_state, minlength: langObj.please_enter_minimum_5_characters, maxlength: langObj.please_enter_maximum_10_characters },
			rzvy_guest_country: { required: langObj.please_enter_country },
			rzvy_guest_dob: { required: langObj.please_enter_birthdate },
			rzvy_guest_notes: { required: langObj.please_enter_notes },
		}
	});
	
	/** validate forget password form **/
	$('#rzvy_forgot_password_form').validate({
        rules: {
            rzvy_forgot_password_email: {required: true, email: true}
        },
        messages: {
            rzvy_forgot_password_email: { required: langObj.please_enter_email_address, email: langObj.please_enter_valid_email_address }
        }
    });
});

/** JS to show services according category selection **/
$(document).on("click", ".rzvy-categories-radio-change", function(){
	$(".rzvy-services-container").html("");
	$(".rzvy-addons-container").html("");
	var ajax_url = generalObj.ajax_url;
	var id = $(this).data("id");
	$(".rzvy-categories-radio-change").removeClass("list_active");
	$("#rzvy-categories-radio-"+id).addClass("list_active");
	$(this).parent().parent().append(rzvy_loader);

	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'get_services_by_cat_id': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			$(".rzvy-loader").remove();
			$(".rzvy-services-container").html(res);
			$('.rzvy-services-container').slideDown();
			/** Auto Trigger Service Check **/
			var single_service_status = generalObj.single_service_status;
			if(single_service_status == "Y"){
				if($('.rzvy-services-radio-change').length==1){
					$('.rzvy-services-radio-change').trigger('click');	
					$('.rzvy-services-container').slideUp();
				}else{
					$('.rzvy-services-radio-change.list_active').trigger('click');
					$('.rzvy-services-container').slideDown();
				}
			}
			/** To Check If Service Selected on prev **/
			else{
				$('.rzvy-services-radio-change.list_active').trigger('click');
				$('.rzvy-services-container').slideDown();
			}
		}
	});
});

/** JS to show addons according services selection **/
$(document).on("click", ".rzvy-services-radio-change", function(){
	$(".rzvy-addons-container").html("");
	var ajax_url = generalObj.ajax_url;
	var id = $(this).data("id");
	$(".rzvy-services-radio-change").removeClass("list_active");
	$("#rzvy-services-radio-"+id).addClass("list_active");
	$(".rzvy-services-radio-change").parent().parent().parent().parent().removeClass('selected');
	$(this).parent().parent().parent().parent().addClass('selected');
	$(this).parent().parent().append(rzvy_loader);
	/** To get addons **/
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'get_multi_and_single_qty_addons_content': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			$(".rzvy-loader").remove();
			$(".rzvy-addons-container").html(res);
			if($(".rzvy-services-radio-change").length>0){
				$(".hide_service_hidden_main").slideDown();
				$(".hide_service_hidden").slideDown();
				if($(".rzvy_addon_card_li").length<1){
					$(".hide_service_hidden_main").slideUp();
				}
				if($(".rzvy-services-radio-change").length>0 && $(".rzvy-services-container").css("display") != "none"){
					$(".hide_service_hidden").slideUp();
				}
			}
			
			$(".rzvy_nextstep_btn_bottom").slideDown();
			$.ajax({
				type: 'post',
				data: {
					'make_multi_and_single_qty_addons_selected': 1
				},
				url: ajax_url + "rzvy_front_stepview_ajax.php",
				success: function (res) {
					var detail = $.parseJSON(res);
					var iLoop; 
					for(iLoop = 0; iLoop < detail.length; iLoop++){
						$("#rzvy_plusminus_addon_card_input_"+detail[iLoop]["id"]).val(parseInt(detail[iLoop]["qty"])-1);
						$(".rzvy_plusminus_addon_card_plus_btn_"+detail[iLoop]["id"]).trigger("click");
						$("#rzvy-addon-card-singleqty-box-"+detail[iLoop]["id"]).trigger("click");
						
					}
				}
			});
		}
	});
});

/** JS to make addons qty inc & desc **/
$(document).on("click", ".rzvy_plusminus_addon_card_btns", function(){
	var ajax_url = generalObj.ajax_url;
	var id = $(this).data("id");
	var minlimit = $("#rzvy-addon-card-mnl-"+id).val();
	var maxlimit = $("#rzvy-addon-card-ml-"+id).val();
	var currentVal = parseInt($("#rzvy_plusminus_addon_card_input_"+id).val());
	if($(this).hasClass("rzvy_flag_minus")){
		currentVal -= 1;
		if (currentVal < minlimit) {
			currentVal = 0;
			$("#rzvy_plusminus_addon_card_input_"+id).val(currentVal);
		}
		if (currentVal >= 0) {
			$("#rzvy_plusminus_addon_card_input_"+id).val(currentVal);
		}
	} else if($(this).hasClass("rzvy_flag_plus")){
		currentVal += 1;
		if (currentVal < minlimit) {
			currentVal = minlimit;
		}
		$("#rzvy_plusminus_addon_card_input_"+id).val(currentVal);
	}else{}
	
	if(currentVal > 0){
		$('#rzvy-addon-card-multipleqty-box-'+id).addClass("list_active");
	}else{
		$('#rzvy-addon-card-multipleqty-box-'+id).removeClass("list_active");
	}
	if(currentVal <= 0){
		$(".rzvy_plusminus_addon_card_minus_btn_"+id).prop('disabled', true);
	}else{
		$(".rzvy_plusminus_addon_card_minus_btn_"+id).prop('disabled', false);
	}
	if(currentVal >= maxlimit){
		$(".rzvy_plusminus_addon_card_plus_btn_"+id).prop('disabled', true);
	}else{
		$(".rzvy_plusminus_addon_card_plus_btn_"+id).prop('disabled', false);
	}
	
	if(currentVal>0){
		if(!$(this).parent().parent().parent().parent().parent().hasClass('selected')){
			$(this).parent().parent().parent().parent().parent().addClass('selected');
		}
	}else{
		$(this).parent().parent().parent().parent().parent().removeClass('selected');
	}
	$(".rzvy_plusminus_addon_card_btns").parent().parent().parent().parent().removeClass('selected');
	$(this).parent().parent().parent().parent().addClass('selected');
	
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'qty': currentVal,
			'add_to_cart_item': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			/** Calculate addon duration **/
			$.ajax({
				type: 'post',
				data: { 'front_addon_duration': 1 },
				url: ajax_url + "rzvy_front_addon_duration.php",
				success: function (res) {}
			});
		}
	});
});

/** JS to trigger counter on click of multiple qty box **/
$(document).on("click", ".rzvy_addon_card_selection", function(){
	var id = $(this).data("id");
	$(this).parent().parent().parent().parent().addClass('selected');
	if($("#rzvy_plusminus_addon_card_input_"+id).val()==0){
		$(".rzvy_plusminus_addon_card_plus_btn_"+id).trigger("click");
	} else if($("#rzvy_plusminus_addon_card_input_"+id).val()==1){
		$(".rzvy_plusminus_addon_card_minus_btn_"+id).trigger("click");
	} else if($("#rzvy_plusminus_addon_card_input_"+id).val()==$("#rzvy-addon-card-mnl-"+id).val()){
		$(".rzvy_plusminus_addon_card_minus_btn_"+id).trigger("click");
	} else if($("#rzvy_plusminus_addon_card_input_"+id).val()>=$("#rzvy-addon-card-mnl-"+id).val()){
		$("#rzvy_plusminus_addon_card_input_"+id).val("1");
		$(".rzvy_plusminus_addon_card_minus_btn_"+id).trigger("click");
	} else {
		/** do nothing **/
	}
});

/** JS to add single qty addons **/
$(document).on("click", ".rzvy_addon_card_input", function(){
	var ajax_url = generalObj.ajax_url;
	var id = $(this).data("id");
	var minlimit = $("#rzvy-addon-card-mnl-"+id).val();
	if(minlimit === undefined){ var minlimit = 1; }
	if($(this).prop("checked") == true || !$(this).hasClass("list_active")){
		var qty = minlimit;
	}else{
		var qty = 0;
	}
	
	if($(this).hasClass("rzvy_make_multipleqty_addon_card_selected")){
		$("#rzvy_plusminus_addon_card_input_"+id).val(qty);
		if(qty <= 0){
			$('#rzvy-addon-card-multipleqty-box-'+id).removeClass("list_active");
		}else{
			$('#rzvy-addon-card-multipleqty-box-'+id).addClass("list_active");
		}
	}else{
		if(qty <= 0){
			$('#rzvy-addon-card-singleqty-box-'+id).removeClass("list_active");
		}else{
			$('#rzvy-addon-card-singleqty-box-'+id).addClass("list_active");
		}
	}
	if(qty>0){
		if(!$(this).parent().parent().parent().parent().parent().hasClass('selected')){
			$(this).parent().parent().parent().parent().parent().addClass('selected');
		}
	}else{
		$(this).parent().parent().parent().parent().parent().removeClass('selected');
	}
	$(".rzvy_addon_card_input").parent().parent().parent().parent().removeClass('selected');
	$(this).parent().parent().parent().parent().addClass('selected');
	
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'qty': qty,
			'add_to_cart_item': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			$(".rzvy-loader").remove();
			/** Calculate addon duration **/
			$.ajax({
				type: 'post',
				data: { 'front_addon_duration': 1 },
				url: ajax_url + "rzvy_front_addon_duration.php",
				success: function (res) {}
			});
		}
	});
});

/** JS to make addons qty inc & desc in cart **/
$(document).on("click", ".rzvy_plusminus_addon_card_btns_cart", function(){
	var ajax_url = generalObj.ajax_url;
	var id = $(this).data("id");
	var minlimit = $("#rzvy-addon-card-mnl-"+id).val();
	var maxlimit = $("#rzvy-addon-card-ml-"+id).val();
	var currentVal = parseInt($("#rzvy_plusminus_addon_card_cart_input_"+id).val());
	
	if($(this).hasClass("rzvy_flag_minus")){
		currentVal -= 1;
		if (currentVal < minlimit) {
			currentVal = 0;
			$("#rzvy_plusminus_addon_card_cart_input_"+id).val(currentVal);
		}
		if (currentVal >= 0) {
			$("#rzvy_plusminus_addon_card_cart_input_"+id).val(currentVal);
		}
	} else if($(this).hasClass("rzvy_flag_plus")){
		currentVal += 1;
		if (currentVal < minlimit) {
			currentVal = minlimit;
			$("#rzvy_plusminus_addon_card_cart_input_"+id).val(currentVal);
		}
		if (currentVal < maxlimit && currentVal > minlimit) {
			$("#rzvy_plusminus_addon_card_cart_input_"+id).val(currentVal);
		}
	}else{}
	
	if(currentVal>0){
		if(!$(this).parent().parent().parent().parent().parent().hasClass('selected')){
			$(this).parent().parent().parent().parent().parent().addClass('selected');
		}
	}else{
		$(this).parent().parent().parent().parent().parent().removeClass('selected');
	}
	$(".rzvy_plusminus_addon_card_btns_cart").parent().parent().parent().parent().removeClass('selected');
	$(this).parent().parent().parent().parent().addClass('selected');
	
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'qty': currentVal,
			'add_to_cart_item': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) { 
			/** Calculate addon duration **/
			$.ajax({
				type: 'post',
				data: { 'front_addon_duration': 1 },
				url: ajax_url + "rzvy_front_addon_duration.php",
				success: function (res) {
					$.ajax({
						type: 'post',
						data: {
							'cal_selection': "N",
							'add_selected_slot_withendslot': 1
						},
						url: ajax_url + "rzvy_front_stepview_ajax.php",
						success: function (res) {
							$("#rzvy_bookingsummary").html("<label>"+langObj.please_wait_summary_loading+"</label>");
							$.ajax({
								type: 'post',
								async:true,
								data: {
									'user': $(".rzvy-user-selection:checked").val(),
									'refresh_cart_sidebar': 1
								},
								url: ajax_url + "rzvy_front_stepview_ajax.php",
								success: function (response) {
									$("#rzvy_bookingsummary").html(response);
									rzvy_sv_payment_method_refresh_func();
								}
							});
						}
					});
				}
			});
		}
	});
});

/** JS to get Second box with frequently discount, staff & calendar **/
$(document).on("click", "#rzvy-get-second-next-box-btn", function(){
	var ajax_url = generalObj.ajax_url;
	/* if($(".rzvy-categories-radio-change:checked").val() === undefined && generalObj.booking_first_selection_as == "category"){ */
	if(!(Number($(".rzvy-categories-radio-change.list_active").data("id"))>0) && generalObj.booking_first_selection_as == "category"){
		swal(langObj.please_add_item_in_your_cart, "", "error");
	}else if(!(Number($(".rzvy-services-radio-change.list_active").data("id"))>0)){
		swal(langObj.please_add_item_in_your_cart, "", "error");
	}else{
		$(this).append(rzvy_loader);
	
		$.ajax({
			type: 'post',
			data: {
				'check_cart_amount': 1
			},
			url: ajax_url + "rzvy_front_stepview_ajax.php",
			success: function (ress_cartamount) {
				if(ress_cartamount == "sufficient"){
					$.ajax({
						type: 'post',
						data: {
							'get_second_step_box': 1
						},
						url: ajax_url + "rzvy_front_stepview_ajax.php",
						success: function (res) {
							$(".rzvy-loader").remove();
							/** To Enable Disable Menus START **/
							$('#rzvy_main_wizard a[data-toggle="tab"]').removeClass('active');
							$('#rzvy_main_wizard a[data-toggle="tab"]').addClass('disabled');
							$('.rzvy-second-step-tab').removeClass('disabled');
							$('.rzvy-second-step-tab').addClass('active');
							$(".rzvy-steps").removeClass("active");
							$(".rzvy-steps").removeClass("fade");
							$(".rzvy-steps").removeClass("show");
							$("#rzvy-second-step").addClass("fade show active");
							/** To Enable Disable Menus END **/
							
							$("#rzvy-second-step").html(res);
							if($(".rzvy-staff-change").length==1 && generalObj.single_staff_showhide_status != "Y"){
								$(".rzvy-staff-change").trigger("click");
								$(".rzvy-staff-container").hide();
								$(".rzvy-staff-container-hr").hide();
							}else if($(".rzvy-staff-change").length==1 && generalObj.single_staff_showhide_status == "Y"){
								$(".rzvy-staff-change").trigger("click");
								$(".rzvy-staff-container").slideDown();
								$(".rzvy-staff-container-hr").slideDown();
							}else if($(".rzvy-staff-change").length==0){
								$(".rzvy-staff-container").hide();
								$(".rzvy-staff-container-hr").hide();
								$.ajax({
									type: 'post',
									data: {
										'id': "",
										'set_staff_according_service': 1
									},
									url: ajax_url + "rzvy_front_stepview_ajax.php",
									success: function (res) {
										if(generalObj.book_with_datetime=="Y"){
											$(".rzvy-calendar-slots-container").html(res);
											$.ajax({
												type: 'post',
												data: {
													'selected_date': generalObj.rzvy_todate,
													'get_slots': 1
												},
												url: ajax_url + "rzvy_front_stepview_ajax.php",
												success: function(resslots) {
													if(resslots.indexOf('rzvy_time_slots_selection')<0){
														$('.rzvy_todate').removeClass('full_day_available');
														$('.rzvy_todate').removeClass('rzvy_date_selection');
														$('.rzvy_todate').addClass('previous_date');
													}	
												}	
											});											
										}
									}
								});
							}else{
								$('.rzvy-staff-change.list_active').trigger('click');
							}
						}
					});
				}else{
					$(".rzvy-loader").remove();
					swal(langObj.opps_minimum_cart_value_should_be+" "+ress_cartamount+". "+langObj.please_add_more_item_into_cart, "", "error");
					$("#rzvy-get-first-next-box-btn").trigger("click");
				}
			}
		});
	}
});

/** JS to get Third box with booking summary **/
$(document).on("click", "#rzvy-get-third-next-box-btn", function(){
	
	if ($(".rzvy_time_slots_selection:checked").val() === undefined && generalObj.book_with_datetime == "Y") {
		swal(langObj.please_select_appointment_slot, "", "error");
	}else{
		$(this).append(rzvy_loader);
	
		var ajax_url = generalObj.ajax_url;
		$.ajax({
			type: 'post',
			data: {
				'get_third_step_box': 1
			},
			url: ajax_url + "rzvy_front_stepview_ajax.php",
			success: function (res) {
				$(".rzvy-loader").remove();
				/** To Enable Disable Menus START **/
				$('#rzvy_main_wizard a[data-toggle="tab"]').removeClass('active');
				$('#rzvy_main_wizard a[data-toggle="tab"]').addClass('disabled');
				$('.rzvy-third-step-tab').removeClass('disabled');
				$('.rzvy-third-step-tab').addClass('active');
				$(".rzvy-steps").removeClass("active");
				$(".rzvy-steps").removeClass("fade");
				$(".rzvy-steps").removeClass("show");
				$("#rzvy-third-step").addClass("fade show active");
				/** To Enable Disable Menus END **/
				
				$("#rzvy-third-step").html(res);
				
				var rzvy_user_type =  $('input[name="rzvy-user-selection"]:checked').val();
				if(rzvy_user_type!=undefined || rzvy_user_type!=null || rzvy_user_type!=''){
					var dataToggle = 'rzvy-existing-user';
					$('.rzvy_referral_code_divf').slideDown(1000);
					if(rzvy_user_type=='gc'){
						$('.rzvy_referral_code_divf').slideUp(1000);
						dataToggle = 'rzvy-guest-user';
					}else if(rzvy_user_type=='nc'){
						dataToggle = 'rzvy-new-user';
					}else if(rzvy_user_type=='fp'){
						dataToggle = 'rzvy-user-forget-password';
					}else if($('.rzvy_loggedin_name').text()!=undefined && $('.rzvy_loggedin_name').text()!=null && $('.rzvy_loggedin_name').text()!=''){
						dataToggle = 'rzvy-new-user';
					}
					var $dataForm = $('[data-form="'+dataToggle+'"]');
					$dataForm.slideDown();
					$('[data-form]').not($dataForm).slideUp();  
				}
			}
		});
	}
});

/** JS to make frequently discount selected **/
$(document).on("click", ".rzvy-frequently-discount-change", function(){
	var id = $(this).val();
	var ajax_url = generalObj.ajax_url;
	$(this).parent().append(rzvy_loader);
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'update_frequently_discount': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			$(".rzvy-loader").remove();
		}
	});
});

/** JS to set staff on service selection **/
$(document).on("click", ".rzvy-staff-change", function(){
	/* var id = $(this).val(); */
	var id = $(this).data("id");
	$(".rzvy-staff-change").removeClass("staff_active");
	$("#rzvy-staff-change-id-"+id).addClass("staff_active");
	$('.rzvy-staff-container .owl-stage-outer .owl-item').each(function(){
		$(this).removeClass('selected');
	});  
	$('.rzvy-staff-change-tt').each(function(){
		$(this).removeClass('selected');
	});  
	
	var ajax_url = generalObj.ajax_url;
	
	
	if(id=='today' || id=='tomorrow'){
		$(this).append(rzvy_loader);
		$(this).toggleClass('selected');
		var staff_ids = [];
		$(".rzvy-staff-change").each(function(){
			var staffid = $(this).data("id");
			if(staffid!='today' && staffid!='tomorrow'){
				staff_ids.push(staffid);
			}
		});
		var seldate = $(this).data("sdate");
		$("#rzvy_time_slots_selection_date").val(seldate);
		$.ajax({
			type: 'post',
			data: {
				'selected_date': seldate,
				'staff_ids':staff_ids,
				'slots_of':id,
				'get_slots_any_staff': 1
			},
			url: ajax_url + "rzvy_front_stepview_ajax.php",
			success: function(resanyslots){
				$(".rzvy-loader").remove();
				$(".rzvy_hide_calendar_before_staff_selection").slideDown();
				$(".rzvy-calendar-slots-container").html(resanyslots);
				
			}	
		});	
		return false;
	}
	$(this).parent().parent().parent().parent().toggleClass('selected');
	$(this).parent().parent().append(rzvy_loader);
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'set_staff_according_service': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			if(generalObj.book_with_datetime=="Y"){
				$(".rzvy-calendar-slots-container").html(res);
				$.ajax({
					type: 'post',
					data: {
						'selected_date': generalObj.rzvy_todate,
						'get_slots': 1
					},
					url: ajax_url + "rzvy_front_stepview_ajax.php",
					success: function(resslots) {
						$(".rzvy-loader").remove();
						$('.rzvy-calendar-slots').html(resslots);
						if(resslots.indexOf('rzvy_time_slots_selection')<0){
							$('.rzvy_todate').removeClass('full_day_available');
							$('.rzvy_todate').removeClass('rzvy_date_selection');
							$('.rzvy_todate').addClass('previous_date');
						}	
					}	
				});
			}
		}
	});
});

/** Get available slots JS **/
$(document).on("click", ".rzvy_date_selection", function(){
	var selected_date = $(this).data("day");
	if (selected_date.length>0) {
		$(".rzvy_available_slots_block").html("");
		$(this).append(rzvy_loader);
	
		var ajax_url = generalObj.ajax_url;
		$(".rzvy_date_selection").removeClass("active_selected_date");
		$(this).addClass("active_selected_date");
		$.ajax({
			type: 'post',
			data: {
				'selected_date': selected_date,
				'get_slots': 1
			},
			url: ajax_url + "rzvy_front_stepview_ajax.php",
			success: function (res) {
				$(".rzvy-loader").remove();
				$(".rzvy-calendar-slots").html(res);
				/* $("#rzvy_start_slot").html(res);
				$('#rzvy_start_slot').selectpicker('refresh');
				$('#rzvy_start_slot').selectpicker('val', $(".rzvy_time_slots_selection:first").val());
				$("#rzvy_start_slot").trigger("change");
				$("#rzvy_end_slot").html("");
				$('#rzvy_end_slot').selectpicker('refresh');
				$(".rzvy_end_slot_div").hide(); */
			}
		});
	}
});

/** JS to get end time slots **/
$(document).on("change", "#rzvy_start_slot", function(){
	var ajax_url = generalObj.ajax_url;
	var check_endslot_status = generalObj.endslot_status;
	var selected_slot = $(this).val();
	var selected_date = $(".rzvy_date_selection.active_selected_date").data("day");
	$(this).append(rzvy_loader);
	
	if(selected_slot != "" && selected_date != "" && check_endslot_status == "Y"){
		$.ajax({
			type: 'post',
			data: {
				'selected_date': selected_date,
				'selected_slot': selected_slot,
				'get_endtime_slots': 1
			},
			url: ajax_url + "rzvy_front_stepview_ajax.php",
			success: function (res) {
				$(".rzvy-loader").remove();
				$("#rzvy_end_slot").html(res);
				$('#rzvy_end_slot').selectpicker('refresh');
				$('#rzvy_end_slot').selectpicker('val', $(".rzvy_endtime_slots_selection:first").val());
				$("#rzvy_end_slot").trigger("change");
				$(".rzvy_end_slot_div").slideDown();
			}
		});
	}else{
		$.ajax({
			type: 'post',
			data: {
				'selected_date': selected_date,
				'selected_startslot': selected_slot,
				'cal_selection': "Y",
				'add_selected_slot_withendslot': 1
			},
			url: ajax_url + "rzvy_front_stepview_ajax.php",
			success: function (res) {
				$(".rzvy-loader").remove();
			}
		});
	}
});

/** JS to add slots **/
$(document).on("change", "#rzvy_end_slot", function(){
	var ajax_url = generalObj.ajax_url;
	var selected_endslot = $(this).val();
	var selected_startslot = $("#rzvy_start_slot").val();
	var selected_date = $(".rzvy_date_selection.active_selected_date").data("day");
	if(selected_endslot != "" && selected_startslot != "" && selected_date != ""){
		$.ajax({
			type: 'post',
			data: {
				'selected_date': selected_date,
				'selected_startslot': selected_startslot,
				'selected_endslot': selected_endslot,
				'add_selected_slot': 1
			},
			url: ajax_url + "rzvy_front_stepview_ajax.php",
			success: function (res) {
			}
		});
	}
});

/** Check location JS **/
$(document).on('click', '#rzvy_location_check_btn', function(){
	var ajaxurl = generalObj.ajax_url;
	var siteurl = generalObj.site_url;
	var zipcode = $("#rzvy_ls_input_keyword").val();

	if(zipcode != "" && zipcode !== null){
			$(this).append(rzvy_loader);
	
			$(".rzvy_main_loader").removeClass("rzvy_hide_loader");
			$.ajax({
				type: 'post',
				data: {
					'zipcode': zipcode,
					'check_zip_location': 1
				},
				url: ajaxurl + "rzvy_location_selector_ajax.php",
				success: function (res) {
					$(".rzvy-loader").remove();
					$(".rzvy_main_loader").addClass("rzvy_hide_loader");
					$('.rzvy-categories-radio-change').trigger('click');
					if(res=="available"){
						$("#rzvy_user_zip").val(zipcode);
						$("#rzvy_guest_zip").val(zipcode);
						swal(langObj.available_our_service_available_at_your_location, "", "success");
						$("#rzvy-location-selector-modal").modal("hide");
					}else{
						swal(langObj.opps_we_are_not_available_at_your_location, "", "error");
					}
				}
			});
	}else{
		swal(langObj.please_enter_valid_zipcode, "", "error");
	}
});
/** JS to apply coupons **/
$(document).on("click", "#rzvy_apply_coupon_code_btn", function(){
	var ajax_url = generalObj.ajax_url;
	var coupon_code = $('#rzvy_coupon_code').val();
	$('#rzvy-coupon-empty-error').addClass('d-none');
	if(coupon_code==''){
		$('#rzvy-coupon-empty-error').removeClass('d-none');
		return false;
	}
	$(this).append(rzvy_loader);
	$.ajax({
		type: 'post',
		data: {
			'id': coupon_code,
			'apply_input': 1,
			'apply_coupon': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			$(".rzvy-loader").remove();
			if(res=="available"){
				$("#rzvy-available-coupons-modal").modal("hide");
				$(".rzvy_remove_applied_coupon").attr('data-id', coupon_code);
				$(".rzvy_applied_coupon_badge").html('<i class="fa fa-ticket"></i> '+coupon_code);
				$(".rzvy_remove_applied_coupon").slideDown();
				$(".rzvy_applied_coupon_div").slideDown();
				swal(langObj.applied_promo_applied_successfully, "", "success");
				$("#rzvy_bookingsummary").html("<label>"+langObj.please_wait_summary_loading+"</label>");
				$.ajax({ 
					type: 'post',
					async:true,
					data: {
						'user': $(".rzvy-user-selection:checked").val(),
						'is_booking_summary': 1,
						'refresh_cart_sidebar': 1
					},
					url: ajax_url + "rzvy_front_stepview_ajax.php",
					success: function (response) {
						$("#rzvy_bookingsummary").html(response);
						$(".rzvy-partial-deposit-control-input").trigger("change");
						rzvy_sv_payment_method_refresh_func();
					}
				});
			}else{
				swal(langObj.opps_something_went_wrong_please_try_again, "", "error");
			}
		}
	});
});

/** JS to show available coupons **/
$(document).on("click", ".rzvy-coupon-radio", function(){
	var ajax_url = generalObj.ajax_url;
	var id = $(this).val();
	var coupon_code = $(this).data("promo");
	$(".rzvy-available-coupons-list").removeClass("rzvy-coupon-radio-checked");
	$("#rzvy-coupon-radio-"+id).parent().addClass("rzvy-coupon-radio-checked");
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'apply_coupon': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			if(res=="available"){
				$("#rzvy-available-coupons-modal").modal("hide");
				$(".rzvy_remove_applied_coupon").attr('data-id', id);
				$(".rzvy_applied_coupon_badge").html('<i class="fa fa-ticket"></i> '+coupon_code);
				$(".rzvy_remove_applied_coupon").slideDown();
				$(".rzvy_applied_coupon_div").slideDown();
				swal(langObj.applied_promo_applied_successfully, "", "success");
				$("#rzvy_bookingsummary").html("<label>"+langObj.please_wait_summary_loading+"</label>");
				$.ajax({ 
					type: 'post',
					async:true,
					data: {
						'user': $(".rzvy-user-selection:checked").val(),
						'is_booking_summary': 1,
						'refresh_cart_sidebar': 1
					},
					url: ajax_url + "rzvy_front_stepview_ajax.php",
					success: function (response) {
						$("#rzvy_bookingsummary").html(response);
						$(".rzvy-partial-deposit-control-input").trigger("change");
						rzvy_sv_payment_method_refresh_func();
					}
				});
			}else{
				swal(langObj.opps_something_went_wrong_please_try_again, "", "error");
			}
		}
	});
});

/** JS to revert coupon **/
$(document).on("click", ".rzvy_remove_applied_coupon", function(){
	var ajax_url = generalObj.ajax_url;
	var id = $(this).data("id");
	if(id!=""){
		$.ajax({
			type: 'post',
			data: {
				'id': id,
				'remove_applied_coupon': 1
			},
			url: ajax_url + "rzvy_front_stepview_ajax.php",
			success: function (res) {
				$('#rzvy_coupon_code').val('');
				$(".rzvy_remove_applied_coupon").attr('data-id', "");
				$(".rzvy_applied_coupon_badge").html('');
				$(".rzvy-available-coupons-list").removeClass("rzvy-coupon-radio-checked");
				$(".rzvy-coupon-radio").prop("checked", false);
				$(".rzvy_remove_applied_coupon").slideUp();
				$(".rzvy_applied_coupon_div").slideUp();
				$("#rzvy_bookingsummary").html("<label>"+langObj.please_wait_summary_loading+"</label>");
				$.ajax({
					type: 'post',
					async:true,
					data: {
						'user': $(".rzvy-user-selection:checked").val(),
						'is_booking_summary': 1,
						'refresh_cart_sidebar': 1
					},
					url: ajax_url + "rzvy_front_stepview_ajax.php",
					success: function (response) {
						$("#rzvy_bookingsummary").html(response);
						$(".rzvy-partial-deposit-control-input").trigger("change");
						rzvy_sv_payment_method_refresh_func();
					}
				});
			}
		});
	}
});

/** JS to show available coupons in modal **/
$(document).on("click", "#rzvy-available-coupons-open-modal", function(){
	var ajax_url = generalObj.ajax_url;
	if($("#rzvy-guest-user").prop("checked") || $("#rzvy-user-forget-password").prop("checked")){
		swal(langObj.opps_please_book_your_appointment_as_new_or_existing_customer, "", "error");
	}else{
		$.ajax({
			type: 'post',
			data: {
				'get_available_coupons': 1
			},
			url: ajax_url + "rzvy_front_ajax.php",
			success: function (res) {
				if(res != "none_of_coupons_available"){
					$("#rzvy-available-coupons-open-modal").slideDown();
					$(".rzvy_avail_promo_modal_body").html(res);
					$("#rzvy-available-coupons-modal").modal("show");
				}else{
					$("#rzvy-available-coupons-open-modal").slideUp();
				}
			}
		});
	}
});

/** swal to apply referral discount coupon **/
$(document).on("click", "#rzvy_apply_referral_coupon", function(){
	var ajax_url = generalObj.ajax_url;
	var ajax_url = generalObj.ajax_url;
	$(this).append(rzvy_loader);
	
	if($(".rzvy-user-selection:checked").val() == "ec"){
		if($("#rzvy_login_btn").is(":visible")){
			$(".rzvy-loader").remove();
			$("#rzvy_login_btn").trigger("click");
			swal(langObj.please_login_to_apply_referral_discount_coupon, "", "error");
		}else{
			var ref_discount_coupon = $("#rzvy_referral_discount_coupon_code").val();
			if(ref_discount_coupon != ""){
				ref_discount_coupon = ref_discount_coupon.toUpperCase();
				$.ajax({
					type: 'post',
					data: {
						'ref_discount_coupon': ref_discount_coupon,
						'apply_referral_discount': 1
					},
					url: ajax_url + "rzvy_front_stepview_ajax.php",
					success: function (res) {
						$(".rzvy-loader").remove();
						if(res == "notexist"){
							$(".rzvy_applied_referral_coupon_code").html("");
							$(".rzvy_applied_referral_coupon_div_text").slideUp();
							$(".rzvy_apply_referral_coupon_div").slideDown();
							swal(langObj.please_enter_valid_referral_discount_coupon, "", "error");
						}else if(res == "used"){
							$(".rzvy_applied_referral_coupon_code").html("");
							$(".rzvy_applied_referral_coupon_div_text").slideUp();
							$(".rzvy_apply_referral_coupon_div").slideDown();
							swal(langObj.referral_discount_coupon_already_used, "", "error");
						}else if(res == "applied"){
							$(".rzvy_applied_referral_coupon_code").html(ref_discount_coupon);
							$(".rzvy_applied_referral_coupon_div_text").slideDown();
							$(".rzvy_apply_referral_coupon_div").slideUp();
							swal(langObj.applied_referral_discount_coupon_applied_successfully, "", "success");
							$("#rzvy_bookingsummary").html("<label>"+langObj.please_wait_summary_loading+"</label>");
							$.ajax({
								type: 'post',
								async:true,
								data: {
									'user': $(".rzvy-user-selection:checked").val(),
									'is_booking_summary': 1,
									'refresh_cart_sidebar': 1
								},
								url: ajax_url + "rzvy_front_stepview_ajax.php",
								success: function (response) {
									$("#rzvy_bookingsummary").html(response);
									$(".rzvy-partial-deposit-control-input").trigger("change");
									rzvy_sv_payment_method_refresh_func();
								}
							});
						}else {
							$(".rzvy_applied_referral_coupon_code").html("");
							$(".rzvy_applied_referral_coupon_div_text").slideUp();
							$(".rzvy_apply_referral_coupon_div").slideDown();
							swal(langObj.opps_something_went_wrong_please_try_again, "", "error");
						}
					}
				});
			}else{
				$(".rzvy-loader").remove();
				swal(langObj.please_enter_referral_discount_coupon_code, "", "error");
			}
		}
	}else{
		$(".rzvy-loader").remove();
		swal(langObj.please_login_to_apply_referral_discount_coupon, "", "error");
	}
});

/** JS to get Fourth box with customer detail **/
$(document).on("click", "#rzvy-get-fourth-next-box-btn", function(){
	var ajax_url = generalObj.ajax_url;
	var is_all_check = false;
	var user_selection = $(".rzvy-user-selection:checked").val();
	$(this).append(rzvy_loader);
	
	if(user_selection == "ec"){
		if($("#rzvy_login_btn").is(":visible")){
			$("#rzvy_login_btn").trigger("click");
			swal(langObj.please_login_to_book_an_appointment, "", "error");
		}else{
			if($("#rzvy_user_detail_form").valid()){
				is_all_check = true;
			}
		}
	} else if(user_selection == "nc"){
		if($("#rzvy_user_detail_form").valid()){
			is_all_check = true;
		}
	} else if(user_selection == "gc"){
		if($("#rzvy_guestuser_detail_form").valid()){
			is_all_check = true;
		}
	}else{
		$(".rzvy-loader").remove();
		swal(langObj.please_login_to_book_an_appointment, "", "error");
	}
	
	if(is_all_check){
		$.ajax({
			type: 'post',
			data: {
				'user': user_selection,
				'get_fourth_step_box': 1
			},
			url: ajax_url + "rzvy_front_stepview_ajax.php",
			success: function (res) {
				$(".rzvy-loader").remove();
				// ** To Enable Disable Menus START **
				$('#rzvy_main_wizard a[data-toggle="tab"]').removeClass('active');
				$('#rzvy_main_wizard a[data-toggle="tab"]').addClass('disabled');
				$('.rzvy-fourth-step-tab').removeClass('disabled');
				$('.rzvy-fourth-step-tab').addClass('active');
				$(".rzvy-steps").removeClass("active");
				$(".rzvy-steps").removeClass("fade");
				$(".rzvy-steps").removeClass("show");
				$("#rzvy-fourth-step").addClass("fade show active");
				// ** To Enable Disable Menus END **
				
				$("#rzvy-fourth-step").html(res); 
				$("#rzvy_bookingsummary").html("<label>"+langObj.please_wait_summary_loading+"</label>");
				$.ajax({
					type: 'post',
					async:true,
					data: {
						'user': $(".rzvy-user-selection:checked").val(),
						'is_booking_summary': 1,
						'refresh_cart_sidebar': 1
					},
					url: ajax_url + "rzvy_front_stepview_ajax.php",
					success: function (response) {
						$("#rzvy_bookingsummary").html(response);
					}
				});
				
				$("#rzvy_apply_referral_code_btn").trigger("click");
				rzvy_sv_payment_method_refresh_func();
			}
		});
	}else{
		$(".rzvy-loader").remove();
	}
});

/** JS to apply referral code on frontend **/
$(document).on("click", "#rzvy_apply_referral_code_btn", function(){
	var ajax_url = generalObj.ajax_url;
	var referral_code = $("#rzvy_referral_code").val().toUpperCase();
	var email = $("#rzvy_user_email").val();
	/* var gemail = $("#rzvy_guest_email").val(); */
	$(".referralcode_applied_msg").slideUp();
	
	$(".referralcode_applied_msg").slideUp();
	
	if(referral_code==undefined || referral_code==null || referral_code==''){
		$('#rzvy_referral_code').addClass('error'); 
		$('#rzvy-referral-empty-error').removeClass('d-none'); 
		return false;
	}
	
	if(referral_code.length==8){
		$(this).append(rzvy_loader);
		$('#rzvy_referral_code').removeClass('error'); 
		$('#rzvy-referral-empty-error').addClass('d-none'); 
		if((email != "" && ($(".rzvy-user-selection:checked").val() == "ec" || $(".rzvy-user-selection:checked").val() == "nc"))/*  || (gemail != "" && $(".rzvy-user-selection:checked").val() == "gc") */){
			$.ajax({
				type: 'post',
				data: {
					'email': email,
					'gemail': "", /* gemail */
					'user': $(".rzvy-user-selection:checked").val(),
					'referral_code': referral_code,
					'apply_referral_code': 1
				},
				url: ajax_url + "rzvy_front_ajax.php",
				success: function (res) {
					$(".rzvy-loader").remove();
					if(res == "applied"){
						$(".rzvy_referral_code_div").slideUp();
						$(".rzvy_referral_code_applied_div").slideDown();
						$(".rzvy_referral_code_applied_text").html(referral_code);
						swal(langObj.referral_code_applied_successfully, "", "success");
					}else if(res == "owncode"){
						$(".rzvy_referral_code_div").slideDown();
						$(".rzvy_referral_code_applied_div").slideUp();
						$(".rzvy_referral_code_applied_text").html("");
						swal(langObj.you_cannot_use_your_own_referral_code, "", "error");
						$(".referralcode_applied_msg").slideDown();
						$(".referralcode_applied_msg").html(langObj.you_cannot_use_your_own_referral_code);
					}else if(res == "onfirstbookingonly"){
						$(".rzvy_referral_code_div").slideDown();
						$(".rzvy_referral_code_applied_div").slideUp();
						$(".rzvy_referral_code_applied_text").html("");
						$(".referralcode_applied_msg").slideDown();
						$(".referralcode_applied_msg").html(langObj.you_can_apply_referral_code_only_on_first_booking);
						swal(langObj.you_can_apply_referral_code_only_on_first_booking, "", "error");
					}else if(res == "notexist"){
						$(".rzvy_referral_code_div").slideDown();
						$(".rzvy_referral_code_applied_div").slideUp();
						$(".rzvy_referral_code_applied_text").html("");
						swal(langObj.opps_youve_entered_incorrect_referral_code, "", "error");
					}else{
						$(".rzvy_referral_code_div").slideDown();
						$(".rzvy_referral_code_applied_div").slideUp();
						$(".rzvy_referral_code_applied_text").html("");
						swal(langObj.opps_something_went_wrong_please_try_again, "", "error");
					}
					$("#rzvy_bookingsummary").html("<label>"+langObj.please_wait_summary_loading+"</label>");
					$.ajax({
						type: 'post',
						async:true,
						data: {
							'user': $(".rzvy-user-selection:checked").val(),
							'is_booking_summary': 1,
							'refresh_cart_sidebar': 1
						},
						url: ajax_url + "rzvy_front_stepview_ajax.php",
						success: function (response) {
							$("#rzvy_bookingsummary").html(response);
							$(".rzvy-partial-deposit-control-input").trigger("change");
							rzvy_sv_payment_method_refresh_func();
						}
					});
				}
			});
		}else{
			$.ajax({
				type: 'post',
				data: {
					'remove_referral_code': 1
				},
				url: ajax_url + "rzvy_front_ajax.php",
				success: function (res) {
					$(".rzvy-loader").remove();
					$(".rzvy_referral_code_div").slideDown();
					$(".rzvy_referral_code_applied_div").slideUp();
					$(".rzvy_referral_code_applied_text").html("");
					swal(langObj.please_register_or_login_to_use_referral_code_feature, "", "error");
					$("#rzvy_bookingsummary").html("<label>"+langObj.please_wait_summary_loading+"</label>");
					$.ajax({
						type: 'post',
						async:true,
						data: {
							'user': $(".rzvy-user-selection:checked").val(),
							'is_booking_summary': 1,
							'refresh_cart_sidebar': 1
						},
						url: ajax_url + "rzvy_front_stepview_ajax.php",
						success: function (response) {
							$("#rzvy_bookingsummary").html(response);
							$(".rzvy-partial-deposit-control-input").trigger("change");
							rzvy_sv_payment_method_refresh_func();
						}
					});
				}
			});
		}
	}else if(referral_code.length>1){
		$('#rzvy_referral_code').removeClass('error'); 
		$('#rzvy-referral-empty-error').addClass('d-none'); 
		swal(langObj.please_enter_8_characters_long_referral_code, "", "error");
	}
});

/** JS to book an appointment **/
$(document).on("click", "#rzvy_book_appointment_btn", function(){
	var ajax_url = generalObj.ajax_url;
	var ty_page = generalObj.ty_link;
	$(".rzvy_main_loader").removeClass("rzvy_hide_loader");
	$(this).append(rzvy_loader);
	
	/** Check for location **/
	var location_zipcode = "";
	if($(".rzvy-user-selection:checked").val() == "ec" || $(".rzvy-user-selection:checked").val() == "nc"){
		location_zipcode = $("#rzvy_user_zip").val();
	}else if($(".rzvy-user-selection:checked").val() == "gc"){
		location_zipcode = $("#rzvy_guest_zip").val();
	}
	if(location_zipcode != ""){
		$.ajax({
			type: 'post',
			data: {
				'final_check': 1,
				'zipcode': location_zipcode,
				'check_zip_location': 1
			},
			url: ajax_url + "rzvy_location_selector_ajax.php",
			success: function (res) {				
				if(res=="addintocart"){
					$(".rzvy-loader").remove();
					swal(langObj.please_add_item_in_your_cart, "", "error");
					$("#rzvy-get-first-next-box-btn").trigger("click");
				}else if(res=="choosedatetime" && generalObj.book_with_datetime == "Y"){
					$(".rzvy-loader").remove();
					swal(langObj.please_select_appointment_slot, "", "error");
					$("#rzvy-get-second-next-box-btn").trigger("click");
				}else if(res=="alreadybooked"){
					$(".rzvy-loader").remove();
					swal(langObj.please_select_another_appointment_slot, "", "error");
					$("#rzvy-get-second-next-box-btn").trigger("click");
				}else if(res!="available"){
					$(".rzvy-loader").remove();
					swal(langObj.opps_we_are_not_available_at_your_location, "", "error");
					$("#rzvy-location-selector-modal").modal("show");
				}else{
					$.ajax({
						type: 'post',
						data: {
							'check_cart_amount': 1
						},
						url: ajax_url + "rzvy_front_stepview_ajax.php",
						success: function (ress_cartamount) {
							if(ress_cartamount == "sufficient"){ 
								/*** Booking code START ***/
								var user_selection = $(".rzvy-user-selection:checked").val();
								if(user_selection == "ec"){
									if($(".rzvy-tc-control-input").prop("checked")){
										
										/** book existing customer appointment **/
										var email = $("#rzvy_user_email").val();
										var password = $("#rzvy_user_password").val();
										var firstname = $("#rzvy_user_firstname").val();
										var lastname = $("#rzvy_user_lastname").val();
										var zip = $("#rzvy_user_zip").val();
										if(formfieldsObj.en_ff_phone_status == "Y"){
											var phone = $("#rzvy_user_phone").intlTelInput("getNumber");
										}else{
											var phone = $("#rzvy_user_phone").val();
										}
										var address = $("#rzvy_user_address").val();
										var city = $("#rzvy_user_city").val();
										var state = $("#rzvy_user_state").val();
										var country = $("#rzvy_user_country").val();
										var dob = $("#rzvy_user_dob").val();
										var notes = $("#rzvy_user_notes").val();
										var payment_method = $(".rzvy-payment-method-check:checked").val();
			
										if(payment_method == "paypal"){
											rzvy_paypal_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, dob, notes);
										}else if(payment_method == "stripe"){
											var stripe_pkey = generalObj.stripe_pkey;
											if(stripe_pkey != ""){
												rzvy_stripe.createToken(rzvy_stripe_plan_card).then(function(result) {
													if (result.error) {
														/* Inform the user if there was an error. */
														$(".rzvy-loader").remove();
														$("#rzvy_stripe_plan_card_errors").html(result.error.message);
													} else {
														/* Send the token via ajax */
														var token = result.token.id;
														rzvy_stripe_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token, dob, notes);
													}
												});
											}else{
												$(".rzvy-loader").remove();
												swal(langObj.opps, langObj.please_contact_business_admin_to_set_payment_accounts_credentials, "error");
											}
										}else if(payment_method == "authorize.net"){
											var cardnumber = $("#rzvy-cardnumber").val();
											var cardcvv = $("#rzvy-cardcvv").val();
											var cardexmonth = $("#rzvy-cardexmonth").val();
											var cardexyear = $("#rzvy-cardexyear").val();
											var cardholdername = $("#rzvy-cardholdername").val();
											
											var cdetail_valid = $.payment.validateCardNumber(cardnumber);
											if (!cdetail_valid) {
												$(".rzvy-loader").remove();
												swal(langObj.opps_your_card_number_is_not_valid, "", "error");
												return false;
											}else{
												var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
												if (!ymdetail_valid) {
													$(".rzvy-loader").remove();
													swal(langObj.opps_your_card_expiry_is_not_valid, "", "error");
													return false;
												}else{
													var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
													if (!cvvdetail_valid) {
														$(".rzvy-loader").remove();
														swal(langObj.opps_your_cvv_is_not_valid, "", "error");
														return false;
													}else{
														if(cardholdername == ""){
															$(".rzvy-loader").remove();
															swal(langObj.please_enter_card_holder_name, "", "error");
															return false;
														}
													}
												}
											}
											cardnumber = cardnumber.replace(/\s+/g, '');
											
											rzvy_authorizenet_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername, dob, notes);
										
										}else if(payment_method == "2checkout"){
											
											var cardnumber = $("#rzvy-cardnumber").val();
											var cardcvv = $("#rzvy-cardcvv").val();
											var cardexmonth = $("#rzvy-cardexmonth").val();
											var cardexyear = $("#rzvy-cardexyear").val();
											var cardholdername = $("#rzvy-cardholdername").val();
											
											var cdetail_valid = $.payment.validateCardNumber(cardnumber);
											if (!cdetail_valid) {
												$(".rzvy-loader").remove();
												swal(langObj.opps_your_card_number_is_not_valid, "", "error");
												return false;
											}else{
												var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
												if (!ymdetail_valid) {
													$(".rzvy-loader").remove();
													swal(langObj.opps_your_card_expiry_is_not_valid, "", "error");
													return false;
												}else{
													var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
													if (!cvvdetail_valid) {
														$(".rzvy-loader").remove();
														swal(langObj.opps_your_cvv_is_not_valid, "", "error");
														return false;
													}else{
														if(cardholdername == ""){
															$(".rzvy-loader").remove();
															swal(langObj.please_enter_card_holder_name, "", "error");
															return false;
														}
													}
												}
											}
											cardnumber = cardnumber.replace(/\s+/g, '');
											
											var twocheckout_sid = generalObj.twocheckout_sid;
											var twocheckout_pkey = generalObj.twocheckout_pkey;
											/*  Called when token created successfully. */
											function successCallback(data) {
												/* Set the token as the value for the token input */
												var token = data.response.token.token;
												rzvy_2checkout_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token, dob, notes);
											};

											/*  Called when token creation fails. */
											function errorCallback(data) {
												if (data.errorCode === 200) {
													$(".rzvy-loader").remove();
													tokenRequest();
												} else {
													$(".rzvy-loader").remove();
													swal(data.errorMsg, "", "error");
												}
											};

											function tokenRequest() {
												/* Setup token request arguments */
												var args = {
													sellerId: twocheckout_sid,
													publishableKey: twocheckout_pkey,
													ccNo: $("#rzvy-cardnumber").val(),
													cvv: $("#rzvy-cardcvv").val(),
													expMonth: $("#rzvy-cardexmonth").val(),
													expYear: $("#rzvy-cardexyear").val()
												};
												/* Make the token request */
												TCO.requestToken(successCallback, errorCallback, args);
											};

											tokenRequest();
										}else if(payment_method == "bank transfer"){
											rzvy_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, dob, notes);
										}else{
											payment_method = "pay-at-venue";
											rzvy_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, dob, notes);
										}
									}else{
										$(".rzvy-loader").remove();
										swal(langObj.please_accept_our_terms_conditions, "", "error");
									}
								} else if(user_selection == "nc"){
									if($(".rzvy-tc-control-input").prop("checked")){
										/** book new customer appointment **/
										var email = $("#rzvy_user_email").val();
										var password = $("#rzvy_user_password").val();
										var firstname = $("#rzvy_user_firstname").val();
										var lastname = $("#rzvy_user_lastname").val();
										var zip = $("#rzvy_user_zip").val();
										if(formfieldsObj.en_ff_phone_status == "Y"){
											var phone = $("#rzvy_user_phone").intlTelInput("getNumber");
										}else{
											var phone = $("#rzvy_user_phone").val();
										}
										var address = $("#rzvy_user_address").val();
										var city = $("#rzvy_user_city").val();
										var state = $("#rzvy_user_state").val();
										var country = $("#rzvy_user_country").val();
										var dob = $("#rzvy_user_dob").val();
										var notes = $("#rzvy_user_notes").val();
										var payment_method = $(".rzvy-payment-method-check:checked").val();
										
										if(payment_method == "paypal"){
											rzvy_paypal_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, dob, notes);
										}else if(payment_method == "stripe"){
											var stripe_pkey = generalObj.stripe_pkey;
											if(stripe_pkey != ""){
												rzvy_stripe.createToken(rzvy_stripe_plan_card).then(function(result) {
													if (result.error) {
														/* Inform the user if there was an error. */
														$(".rzvy-loader").remove();
														$("#rzvy_stripe_plan_card_errors").html(result.error.message);
													} else {
														/* Send the token via ajax */
														var token = result.token.id;
														rzvy_stripe_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token, dob, notes);
													}
												});
											}else{
												$(".rzvy-loader").remove();
												swal(langObj.opps, langObj.please_contact_business_admin_to_set_payment_accounts_credentials, "error");
											}
										}else if(payment_method == "authorize.net"){
											var cardnumber = $("#rzvy-cardnumber").val();
											var cardcvv = $("#rzvy-cardcvv").val();
											var cardexmonth = $("#rzvy-cardexmonth").val();
											var cardexyear = $("#rzvy-cardexyear").val();
											var cardholdername = $("#rzvy-cardholdername").val();
											
											var cdetail_valid = $.payment.validateCardNumber(cardnumber);
											if (!cdetail_valid) {
												$(".rzvy-loader").remove();
												swal(langObj.opps_your_card_number_is_not_valid, "", "error");
												return false;
											}else{
												var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
												if (!ymdetail_valid) {
													$(".rzvy-loader").remove();
													swal(langObj.opps_your_card_expiry_is_not_valid, "", "error");
													return false;
												}else{
													var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
													if (!cvvdetail_valid) {
														$(".rzvy-loader").remove();
														swal(langObj.opps_your_cvv_is_not_valid, "", "error");
														return false;
													}else{
														if(cardholdername == ""){
															$(".rzvy-loader").remove();
															swal(langObj.please_enter_card_holder_name, "", "error");
															return false;
														}
													}
												}
											}
											cardnumber = cardnumber.replace(/\s+/g, '');
											
											rzvy_authorizenet_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername, dob, notes);
										}else if(payment_method == "2checkout"){
											
											var cardnumber = $("#rzvy-cardnumber").val();
											var cardcvv = $("#rzvy-cardcvv").val();
											var cardexmonth = $("#rzvy-cardexmonth").val();
											var cardexyear = $("#rzvy-cardexyear").val();
											var cardholdername = $("#rzvy-cardholdername").val();
											
											var cdetail_valid = $.payment.validateCardNumber(cardnumber);
											if (!cdetail_valid) {
												$(".rzvy-loader").remove();
												swal(langObj.opps_your_card_number_is_not_valid, "", "error");
												return false;
											}else{
												var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
												if (!ymdetail_valid) {
													$(".rzvy-loader").remove();
													swal(langObj.opps_your_card_expiry_is_not_valid, "", "error");
													return false;
												}else{
													var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
													if (!cvvdetail_valid) {
														$(".rzvy-loader").remove();
														swal(langObj.opps_your_cvv_is_not_valid, "", "error");
														return false;
													}else{
														if(cardholdername == ""){
															$(".rzvy-loader").remove();
															swal(langObj.please_enter_card_holder_name, "", "error");
															return false;
														}
													}
												}
											}
											cardnumber = cardnumber.replace(/\s+/g, '');
											
											var twocheckout_sid = generalObj.twocheckout_sid;
											var twocheckout_pkey = generalObj.twocheckout_pkey;
											/*  Called when token created successfully. */
											function successCallback(data) {
												/* Set the token as the value for the token input */
												var token = data.response.token.token;
												rzvy_2checkout_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token, dob, notes);
											};

											/*  Called when token creation fails. */
											function errorCallback(data) {
												if (data.errorCode === 200) {
													$(".rzvy-loader").remove();
													tokenRequest();
												} else {
													$(".rzvy-loader").remove();
													swal(data.errorMsg, "", "error");
												}
											};

											function tokenRequest() {
												/* Setup token request arguments */
												var args = {
													sellerId: twocheckout_sid,
													publishableKey: twocheckout_pkey,
													ccNo: $("#rzvy-cardnumber").val(),
													cvv: $("#rzvy-cardcvv").val(),
													expMonth: $("#rzvy-cardexmonth").val(),
													expYear: $("#rzvy-cardexyear").val()
												};
												/* Make the token request */
												TCO.requestToken(successCallback, errorCallback, args);
											};

											tokenRequest();
										}else if(payment_method == "bank transfer"){
											rzvy_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, dob, notes);
										}else{
											payment_method = "pay-at-venue";
											rzvy_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, dob, notes);
										}
									}else{
										$(".rzvy-loader").remove();
										swal(langObj.please_accept_our_terms_conditions, "", "error");
									}
								} else if(user_selection == "gc"){
									if($(".rzvy-tc-control-input").prop("checked")){
										/** book guest customer appointment **/
										var email = $("#rzvy_guest_email").val();
										var password = "";
										var firstname = $("#rzvy_guest_firstname").val();
										var lastname = $("#rzvy_guest_lastname").val();
										var zip = $("#rzvy_guest_zip").val();
										if(formfieldsObj.g_ff_phone_status == "Y"){
											var phone = $("#rzvy_guest_phone").intlTelInput("getNumber");
										}else{
											var phone = $("#rzvy_guest_phone").val();
										}
										var address = $("#rzvy_guest_address").val();
										var city = $("#rzvy_guest_city").val();
										var state = $("#rzvy_guest_state").val();
										var country = $("#rzvy_guest_country").val();
										var dob = $("#rzvy_guest_dob").val();
										var notes = $("#rzvy_guest_notes").val();
										var payment_method = $(".rzvy-payment-method-check:checked").val();

										if(payment_method == "paypal"){
											rzvy_paypal_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, dob, notes);
										}else if(payment_method == "stripe"){
											var stripe_pkey = generalObj.stripe_pkey;
											if(stripe_pkey != ""){
												rzvy_stripe.createToken(rzvy_stripe_plan_card).then(function(result) {
													if (result.error) {
														/* Inform the user if there was an error. */
														$(".rzvy-loader").remove();
														$("#rzvy_stripe_plan_card_errors").html(result.error.message);
													} else {
														/* Send the token via ajax */
														var token = result.token.id;
														rzvy_stripe_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token, dob, notes);
													}
												});
											}else{
												$(".rzvy-loader").remove();
												swal(langObj.opps, langObj.please_contact_business_admin_to_set_payment_accounts_credentials, "error");
											}
										}else if(payment_method == "authorize.net"){
											var cardnumber = $("#rzvy-cardnumber").val();
											var cardcvv = $("#rzvy-cardcvv").val();
											var cardexmonth = $("#rzvy-cardexmonth").val();
											var cardexyear = $("#rzvy-cardexyear").val();
											var cardholdername = $("#rzvy-cardholdername").val();
											
											var cdetail_valid = $.payment.validateCardNumber(cardnumber);
											if (!cdetail_valid) {
												$(".rzvy-loader").remove();
												swal(langObj.opps_your_card_number_is_not_valid, "", "error");
												return false;
											}else{
												var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
												if (!ymdetail_valid) {
													$(".rzvy-loader").remove();
													swal(langObj.opps_your_card_expiry_is_not_valid, "", "error");
													return false;
												}else{
													var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
													if (!cvvdetail_valid) {
														$(".rzvy-loader").remove();
														swal(langObj.opps_your_cvv_is_not_valid, "", "error");
														return false;
													}else{
														if(cardholdername == ""){
															$(".rzvy-loader").remove();
															swal(langObj.please_enter_card_holder_name, "", "error");
															return false;
														}
													}
												}
											}
											cardnumber = cardnumber.replace(/\s+/g, '');
											
											rzvy_authorizenet_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername, dob, notes);

										}else if(payment_method == "2checkout"){
											
											var cardnumber = $("#rzvy-cardnumber").val();
											var cardcvv = $("#rzvy-cardcvv").val();
											var cardexmonth = $("#rzvy-cardexmonth").val();
											var cardexyear = $("#rzvy-cardexyear").val();
											var cardholdername = $("#rzvy-cardholdername").val();
											
											var cdetail_valid = $.payment.validateCardNumber(cardnumber);
											if (!cdetail_valid) {
												$(".rzvy-loader").remove();
												swal(langObj.opps_your_card_number_is_not_valid, "", "error");
												return false;
											}else{
												var ymdetail_valid = $.payment.validateCardExpiry(cardexmonth, cardexyear);
												if (!ymdetail_valid) {
													$(".rzvy-loader").remove();
													swal(langObj.opps_your_card_expiry_is_not_valid, "", "error");
													return false;
												}else{
													var cvvdetail_valid = $.payment.validateCardCVC(cardcvv);
													if (!cvvdetail_valid) {
														$(".rzvy-loader").remove();
														swal(langObj.opps_your_cvv_is_not_valid, "", "error");
														return false;
													}else{
														if(cardholdername == ""){
															$(".rzvy-loader").remove();
															swal(langObj.please_enter_card_holder_name, "", "error");
															return false;
														}
													}
												}
											}
											cardnumber = cardnumber.replace(/\s+/g, '');
											
											var twocheckout_sid = generalObj.twocheckout_sid;
											var twocheckout_pkey = generalObj.twocheckout_pkey;
											/*  Called when token created successfully. */
											function successCallback(data) {
												/* Set the token as the value for the token input */
												var token = data.response.token.token;
												rzvy_2checkout_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token, dob, notes);
											};

											/*  Called when token creation fails. */
											function errorCallback(data) {
												if (data.errorCode === 200) {
													$(".rzvy-loader").remove();
													tokenRequest();
												} else {
													$(".rzvy-loader").remove();
													swal(data.errorMsg, "", "error");
												}
											};

											function tokenRequest() {
												/* Setup token request arguments */
												var args = {
													sellerId: twocheckout_sid,
													publishableKey: twocheckout_pkey,
													ccNo: $("#rzvy-cardnumber").val(),
													cvv: $("#rzvy-cardcvv").val(),
													expMonth: $("#rzvy-cardexmonth").val(),
													expYear: $("#rzvy-cardexyear").val()
												};
												/* Make the token request */
												TCO.requestToken(successCallback, errorCallback, args);
											};

											tokenRequest();
										}else if(payment_method == "bank transfer"){
											rzvy_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, dob, notes);
										}else{
											payment_method = "pay-at-venue";
											rzvy_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, dob, notes);
										}
									}else{
										$(".rzvy-loader").remove();
										swal(langObj.please_accept_our_terms_conditions, "", "error");
									}
								}
								/*** Booking code END ***/
							}else{
								$(".rzvy-loader").remove();
								swal(langObj.opps_minimum_cart_value_should_be+" "+ress_cartamount+". "+langObj.please_add_more_item_into_cart, "", "error");
								$("#rzvy-get-first-next-box-btn").trigger("click");
							}
						}
					});
				}
			}
		});
	}else{
		$(".rzvy-loader").remove();
		swal(langObj.opps_please_check_for_services_available_at_your_location_or_not, "", "error");
		$("#rzvy-location-selector-modal").modal("show");
		return false;
	}
});

function rzvy_pay_at_venue_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, dob, notes){
	$(".rzvy_main_loader").removeClass("rzvy_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'email': email,
			'password': password,
			'firstname': firstname,
			'lastname': lastname,
			'zip': zip,
			'phone': phone,
			'address': address,
			'city': city,
			'state': state,
			'country': country,
			'dob': dob,
			'notes': notes,
			'payment_method': payment_method,
			'type': user_selection,
			'pay_at_venue_appointment': 1
		},
		url: ajax_url + "rzvy_front_checkout_ajax.php",
		success: function (response) {
			
			if(response.indexOf("BOOKED")>=0){
				$(".rzvy-loader").remove();
				var responseinfo = response.split('###');
				swal({
						title: "<h4 style='margin-top:10px;line-height:45px;'>"+langObj.thankyou_booking_complete+"<h4>",
						type: 'success',
						text: responseinfo[1],
						html: true,
						showCancelButton: false,
						closeOnConfirm: true,
						confirmButtonText: langObj.finish,
						cancelButtonText: 'Cancel',
					}, function (bookingdone) {
						if(ty_page != ""){
							window.location=ty_page; 
						}else{
							/** To Enable Disable Menus START **/
							$('#rzvy_main_wizard a[data-toggle="tab"]').removeClass('active');
							$('#rzvy_main_wizard a[data-toggle="tab"]').addClass('disabled');
							$('.rzvy-fifth-step-tab').removeClass('disabled');
							$('.rzvy-fifth-step-tab').addClass('active');
							$(".rzvy-steps").removeClass("active");
							$(".rzvy-steps").removeClass("fade");
							$(".rzvy-steps").removeClass("show");
							$("#rzvy-fifth-step").addClass("fade show active");
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-wizard").offset().top)
							}, 800);
							/** To Enable Disable Menus END **/
						}
					});
			}
		}
	});
}

function rzvy_paypal_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, dob, notes){
	$(".rzvy_main_loader").removeClass("rzvy_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'email': email,
			'password': password,
			'firstname': firstname,
			'lastname': lastname,
			'zip': zip,
			'phone': phone,
			'address': address,
			'city': city,
			'state': state,
			'country': country,
			'dob': dob,
			'notes': notes,
			'payment_method': payment_method,
			'type': user_selection,
			'paypal_appointment': 1
		},
		url: ajax_url + "rzvy_front_checkout_ajax.php",
		success: function (res) { 
			$(".rzvy-loader").remove();
			var response_detail = $.parseJSON(res);
			if(response_detail.status=='success'){
				window.location.href = response_detail.value; 
			}
			if(response_detail.status=='error'){
				$(".rzvy_main_loader").addClass("rzvy_hide_loader");
				swal(response_detail.value, "", "error");
			}
		}
	});
}

function rzvy_authorizenet_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, cardnumber, cardcvv, cardexmonth, cardexyear, cardholdername, dob, notes){
	$(".rzvy_main_loader").removeClass("rzvy_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'email': email,
			'password': password,
			'firstname': firstname,
			'lastname': lastname,
			'zip': zip,
			'phone': phone,
			'address': address,
			'city': city,
			'state': state,
			'country': country,
			'dob': dob,
			'notes': notes,
			'payment_method': payment_method,
			'type': user_selection,
			'cardnumber': cardnumber,
			'cardcvv': cardcvv,
			'cardexmonth': cardexmonth,
			'cardexyear': cardexyear,
			'cardholdername': cardholdername,
			'authorizenet_appointment': 1
		},
		url: ajax_url + "rzvy_front_checkout_ajax.php",
		success: function (res) {
			var response_detail = $.parseJSON(res);
			if(response_detail.status==false){
				$(".rzvy-loader").remove();
				swal(response_detail.error, "", "error");
			} else {
				 $.ajax({
					type: "POST",
					url: ajax_url + "rzvy_front_appt_process_ajax.php",
					success:function(response){
						if(response.indexOf("BOOKED")>=0){
							$(".rzvy-loader").remove();
							var responseinfo = response.split('###');
							swal({
									title: "<h4 style='margin-top:10px;line-height:45px;'>"+langObj.thankyou_booking_complete+"<h4>",
									type: 'success',
									text: responseinfo[1],
									html: true,
									showCancelButton: false,
									closeOnConfirm: true,
									confirmButtonText: langObj.finish,
									cancelButtonText: 'Cancel',
								}, function (bookingdone) {
									if(ty_page != ""){
										window.location=ty_page; 
									}else{
										/** To Enable Disable Menus START **/
										$('#rzvy_main_wizard a[data-toggle="tab"]').removeClass('active');
										$('#rzvy_main_wizard a[data-toggle="tab"]').addClass('disabled');
										$('.rzvy-fifth-step-tab').removeClass('disabled');
										$('.rzvy-fifth-step-tab').addClass('active');
										$(".rzvy-steps").removeClass("active");
										$(".rzvy-steps").removeClass("fade");
										$(".rzvy-steps").removeClass("show");
										$("#rzvy-fifth-step").addClass("fade show active");
										$('html, body').animate({
											scrollTop: parseInt($(".rzvy-wizard").offset().top)
										}, 800);
										/** To Enable Disable Menus END **/
									}
								});
						}
					}
				});
			}
		}
	});
}

function rzvy_2checkout_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token, dob, notes){
	$(".rzvy_main_loader").removeClass("rzvy_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'email': email,
			'password': password,
			'firstname': firstname,
			'lastname': lastname,
			'zip': zip,
			'phone': phone,
			'address': address,
			'city': city,
			'state': state,
			'country': country,
			'dob': dob,
			'notes': notes,
			'payment_method': payment_method,
			'type': user_selection,
			'token': token,
			'2checkout_appointment': 1
		},
		url: ajax_url + "rzvy_front_checkout_ajax.php",
		success: function (res) { 
			var response_detail = $.parseJSON(res);
			if(response_detail.status==false){
				$(".rzvy-loader").remove();
				swal(response_detail.error, "", "error");
			} else {
				 $.ajax({
					type: "POST",
					url: ajax_url + "rzvy_front_appt_process_ajax.php",
					success:function(response){
						if(response.indexOf("BOOKED")>=0){
							$(".rzvy-loader").remove();
							var responseinfo = response.split('###');
							swal({
									title: "<h4 style='margin-top:10px;line-height:45px;'>"+langObj.thankyou_booking_complete+"<h4>",
									type: 'success',
									text: responseinfo[1],
									html: true,
									showCancelButton: false,
									closeOnConfirm: true,
									confirmButtonText: langObj.finish,
									cancelButtonText: 'Cancel',
								}, function (bookingdone) {
									if(ty_page != ""){
										window.location=ty_page; 
									}else{
										/** To Enable Disable Menus START **/
										$('#rzvy_main_wizard a[data-toggle="tab"]').removeClass('active');
										$('#rzvy_main_wizard a[data-toggle="tab"]').addClass('disabled');
										$('.rzvy-fifth-step-tab').removeClass('disabled');
										$('.rzvy-fifth-step-tab').addClass('active');
										$(".rzvy-steps").removeClass("active");
										$(".rzvy-steps").removeClass("fade");
										$(".rzvy-steps").removeClass("show");
										$("#rzvy-fifth-step").addClass("fade show active");
										$('html, body').animate({
											scrollTop: parseInt($(".rzvy-wizard").offset().top)
										}, 800);
										/** To Enable Disable Menus END **/
									}
								});
						}
					}
				});
			}
		}
	});
}

function rzvy_stripe_appointment(email, password, firstname, lastname, zip, phone, address, city, state, country, payment_method, user_selection, ajax_url, ty_page, token, dob, notes){
	$(".rzvy_main_loader").removeClass("rzvy_hide_loader");
	$.ajax({
		type: 'post',
		data: {
			'email': email,
			'password': password,
			'firstname': firstname,
			'lastname': lastname,
			'zip': zip,
			'phone': phone,
			'address': address,
			'city': city,
			'state': state,
			'country': country,
			'dob': dob,
			'notes': notes,
			'payment_method': payment_method,
			'type': user_selection,
			'token': token,
			'stripe_appointment': 1
		},
		url: ajax_url + "rzvy_front_checkout_ajax.php",
		success: function (res) { 
			var response_detail = $.parseJSON(res);
			if(response_detail.status==false){
				$(".rzvy-loader").remove();
				swal(response_detail.error, "", "error");
			} else {
				 $.ajax({
					type: "POST",
					url: ajax_url + "rzvy_front_appt_process_ajax.php",
					success:function(response){
						if(response.indexOf("BOOKED")>=0){
							$(".rzvy-loader").remove();
							var responseinfo = response.split('###');
							swal({
									title: "<h4 style='margin-top:10px;line-height:45px;'>"+langObj.thankyou_booking_complete+"<h4>",
									type: 'success',
									text: responseinfo[1],
									html: true,
									showCancelButton: false,
									closeOnConfirm: true,
									confirmButtonText: langObj.finish,
									cancelButtonText: 'Cancel',
								}, function (bookingdone) {
									if(ty_page != ""){
										window.location=ty_page; 
									}else{
										/** To Enable Disable Menus START **/
										$('#rzvy_main_wizard a[data-toggle="tab"]').removeClass('active');
										$('#rzvy_main_wizard a[data-toggle="tab"]').addClass('disabled');
										$('.rzvy-fifth-step-tab').removeClass('disabled');
										$('.rzvy-fifth-step-tab').addClass('active');
										$(".rzvy-steps").removeClass("active");
										$(".rzvy-steps").removeClass("fade");
										$(".rzvy-steps").removeClass("show");
										$("#rzvy-fifth-step").addClass("fade show active");
										$('html, body').animate({
											scrollTop: parseInt($(".rzvy-wizard").offset().top)
										}, 800);
										/** To Enable Disable Menus END **/
									}
								});
						}
					}
				});
			}
		}
	});
}

/** Show hide card payemnt box JS **/
$(document).on("change", ".rzvy-payment-method-check", function(){
	if($(this).val() == "stripe" || $(this).val() == "2checkout" || $(this).val() == "authorize.net"){
		$(".rzvy-card-detail-box").slideDown(2000);
	}else{
		$(".rzvy-card-detail-box").slideUp(1000);
	}
	if($(this).val() == "bank transfer"){
		$(".rzvy-bank-transfer-detail-box").slideDown(2000);
	}else{
		$(".rzvy-bank-transfer-detail-box").slideUp(1000);
	}
	var ajax_url = generalObj.ajax_url;
	$.ajax({
		type: 'post',
		async:true,
		data: {
			'user': $(".rzvy-user-selection:checked").val(),
			'is_partial': $(".rzvy-partial-deposit-control-input").prop("checked"),
			'payment_method': $(this).val(),
			'refresh_cart_sidebar': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (response) {
			if(response>0){
				$(".rzvy_update_partial_amount").html(response);
				$(".rzvy-cart-partial-deposite-main").slideDown();
			}else{
				$(".rzvy_update_partial_amount").html("0");
				$(".rzvy-cart-partial-deposite-main").slideUp();
			}
		}
	});
});

$(document).on("change", ".rzvy-partial-deposit-control-input", function(){
	var ajax_url = generalObj.ajax_url;
	
	$.ajax({
		type: 'post',
		async:true,
		data: {
			'user': $(".rzvy-user-selection:checked").val(),
			'is_partial': $(".rzvy-partial-deposit-control-input").prop("checked"),
			'payment_method': $(".rzvy-payment-method-check:checked").val(),
			'refresh_cart_sidebar': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (response) {
			if($(".rzvy-partial-deposit-control-input").prop("checked")){
				if(response>0){
					$(".rzvy_update_partial_amount").html(response);
				}else{
					$(".rzvy_update_partial_amount").html("0");
				}
				$(".rzvy-cart-partial-deposite-main").slideDown();
			}else{
				$(".rzvy_update_partial_amount").html("0");
				$(".rzvy-cart-partial-deposite-main").slideUp();
			}
			rzvy_sv_payment_method_refresh_func();
		}
	});
});

/** Show hide customer detail box according selection JS **/
$(document).on("change", ".rzvy-user-selection", function(){
	if($(this).attr("id") == "rzvy-new-user"){
		$("#rzvy-guest-user-box").removeClass("rzvy_show");
		$("#rzvy-guest-user-box").slideUp(1000);
		$("#rzvy-existing-user-box").slideUp(1000);
		$("#rzvy-user-forget-password-box").slideUp(1000);
		$("#rzvy-new-user-box").slideDown(2000);
	}else if($(this).attr("id") == "rzvy-guest-user"){
		$("#rzvy-guest-user-box").removeClass("rzvy_show");
		$("#rzvy-existing-user-box").slideUp(1000);
		$("#rzvy-user-forget-password-box").slideUp(1000);
		$("#rzvy-new-user-box").slideUp(1000);
		$("#rzvy-guest-user-box").slideDown(2000);
	}else if($(this).attr("id") == "rzvy-user-forget-password"){
		$("#rzvy_apply_referral_code_btn").trigger("click");
		$("#rzvy_remove_applied_coupon").trigger("click");
		$("#rzvy-guest-user-box").removeClass("rzvy_show");
		$("#rzvy-guest-user-box").slideUp(1000);
		$("#rzvy-existing-user-box").slideUp(1000);
		$("#rzvy-new-user-box").slideUp(2000);
		$("#rzvy-user-forget-password-box").slideDown(1000);
	}else{
		$("#rzvy_remove_applied_coupon").trigger("click");
		$("#rzvy-guest-user-box").removeClass("rzvy_show");
		$("#rzvy-guest-user-box").slideUp(1000);
		$("#rzvy-new-user-box").slideUp(1000);
		$("#rzvy-user-forget-password-box").slideUp(1000);
		$("#rzvy-existing-user-box").slideDown(2000);
	}
});

/** JS to make login on frontend **/
$(document).on("click", "#rzvy_login_btn", function(e){
	e.preventDefault();
	var ajax_url = generalObj.ajax_url;
	var email = $("#rzvy_login_email").val();
	var password = $("#rzvy_login_password").val();
	if($("#rzvy_login_form").valid()){
		$(this).append(rzvy_loader);		
		$.ajax({
			type: 'post',
			data: {
				'email': email,
				'password': password,
				'front_login': 1
			},
			url: ajax_url + "rzvy_front_ajax.php",
			success: function (res) {
				$(".rzvy-loader").remove();
				var detail = $.parseJSON(res);
				if(detail['status'] == "success"){
					$(".rzvy_loggedin_name").html(detail['email']);
					$("#rzvy_user_email").val(detail['email']);
					$("#rzvy_user_password").val(detail['password']);
					$("#rzvy_user_firstname").val(detail['firstname']);
					$("#rzvy_user_lastname").val(detail['lastname']);
					$("#rzvy_user_zip").val(detail['zip']);
					if(formfieldsObj.en_ff_phone_status == "Y"){
						$("#rzvy_user_phone").intlTelInput("setNumber", detail['phone']);
					}else{
						$("#rzvy_user_phone").val(detail['phone']);
					}
					$("#rzvy_user_address").val(detail['address']);
					$("#rzvy_user_city").val(detail['city']);
					$("#rzvy_user_state").val(detail['state']);
					$("#rzvy_user_country").val(detail['country']);
					$("#rzvy_user_dob").val(detail['dob']);
					
					$("#rzvy-existing-user-box").slideUp();
					$(".rzvy-users-selection-div").slideUp();
					$(".rzvy_hide_after_login").slideUp();
					$(".rzvy-logout-div").slideDown();
					$("#rzvy-new-user-box").slideDown();
					
					$(".rzvy-lpoint-control-input").trigger("change");
					$(".rzvy_remove_applied_coupon").trigger("click");
					$("#rzvy_apply_referral_code_btn").trigger("click");
					$(".rzvy_applied_referral_coupon_code").html("");
					$(".rzvy_applied_referral_coupon_div_text").slideUp();
					$(".rzvy_apply_referral_coupon_div").slideDown();
					
					var dataToggle = 'rzvy-new-user';
					var $dataForm = $('[data-form="'+dataToggle+'"]');
					$dataForm.slideDown();
					$('[data-form]').not($dataForm).slideUp();
				}else{
					$(".rzvy-loader").remove();
					swal(langObj.opps_your_entered_email_not_registered_please_book_an_appointment_as_new_customer, "", "error");
				}
			}
		});
	}
});

/** JS to make logout on frontend **/
$(document).on("click", "#rzvy_logout_btn", function(){
	var ajax_url = generalObj.ajax_url;	
	$.ajax({
		type: 'post',
		data: {
			'front_logout': 1
		},
		url: ajax_url + "rzvy_front_ajax.php",
		success: function (res) {
			
			$(".rzvy_loggedin_name").html("");
			$("#rzvy_user_email").val("");
			$("#rzvy_user_password").val("");
			$("#rzvy_user_firstname").val("");
			$("#rzvy_user_lastname").val("");
			$("#rzvy_user_zip").val("");
			if(formfieldsObj.en_ff_phone_status == "Y"){
				$("#rzvy_user_phone").intlTelInput("setNumber", "");
			}else{
				$("#rzvy_user_phone").val("");
			}
			$("#rzvy_user_address").val("");
			$("#rzvy_user_city").val("");
			$("#rzvy_user_state").val("");
			$("#rzvy_user_country").val("");
			$("#rzvy_login_email").val("");
			$("#rzvy_login_password").val("");
			$("#rzvy_user_dob").val("");
			
			$("#rzvy-existing-user-box").slideDown();
			$(".rzvy-users-selection-div").slideDown();
			$(".rzvy_hide_after_login").slideDown();
			$(".rzvy-logout-div").slideUp();
			$("#rzvy-new-user-box").slideUp();
			
			$(".referralcode_applied_msg").slideUp();
			
			$(".rzvy-lpoint-control-input").trigger("change");
			$(".rzvy_remove_applied_coupon").trigger("click");
			$("#rzvy_apply_referral_code_btn").trigger("click");
			$(".rzvy_applied_referral_coupon_code").html("");
			$(".rzvy_applied_referral_coupon_div_text").slideUp();
			$(".rzvy_apply_referral_coupon_div").slideDown();
			
			var dataToggle = 'rzvy-existing-user';
			var $dataForm = $('[data-form="'+dataToggle+'"]');
			$dataForm.slideDown();
			$('[data-form]').not($dataForm).slideUp();
		}
	});
});

$(document).on("click", ".rzvy_cal_prev_month, .rzvy_cal_next_month", function(){
	var ajax_url = generalObj.ajax_url;
	var selected_month = $(this).data("month");
	if(generalObj.book_with_datetime == "Y"){
	$.ajax({
		type: 'post',
		data: {
			'online': "Y",
			'selected_month': selected_month,
			'get_calendar_on_next_prev': 1
		},
		url: ajax_url + "rzvy_calendar_ajax.php",
		success: function (res) {
			$(".rzvy-inline-calendar-container").html(res);
			$.ajax({
				type: 'post',
				data: {
					'selected_date': generalObj.rzvy_todate,
					'get_slots': 1
				},
				url: ajax_url + "rzvy_front_stepview_ajax.php",
				success: function(resslots) {
					if(resslots.indexOf('rzvy_time_slots_selection')<0){
						$('.rzvy_todate').removeClass('full_day_available');
						$('.rzvy_todate').removeClass('rzvy_date_selection');
						$('.rzvy_todate').addClass('previous_date');
					}	
				}	
			});	
		}
	});
	}
});

/** JS to add single qty addons **/
$(document).on("click", ".rzvy_remove_addon_from_cart", function(){
	var ajax_url = generalObj.ajax_url;
	var id = $(this).data("id");
	var qty = 0;
	
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'qty': qty,
			'add_to_cart_item': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			$("#rzvy_addon_tr_"+id).remove();
			if($(".rzvy_count_addon_tr").length == 0){
				$("#rzvy_remove_addon_head").remove();
			}
			$.ajax({
				type: 'post',
				async:true,
				data: {
					'user': $(".rzvy-user-selection:checked").val(),
					'refresh_cart_sidebar': 1
				},
				url: ajax_url + "rzvy_front_stepview_ajax.php",
				success: function (response) {
					$("#rzvy_bookingsummary").html(response);
					rzvy_sv_payment_method_refresh_func();
				}
			});
		}
	});
});

/** Forget password JS **/
$(document).on('click','#rzvy_forgot_password_btn',function(e){
	e.preventDefault();
	var email = $('#rzvy_forgot_password_email').val();
	var site_url = generalObj.site_url;
	var ajax_url = generalObj.ajax_url;
	if ($('#rzvy_forgot_password_form').valid()){
		
		$(this).append(rzvy_loader);	
		$.ajax({
			type: 'post',
			data: {
				'email': email,
				'forgot_password': 1
			},
			url: ajax_url + "rzvy_login_ajax.php",
			success: function (res) {
				$(".rzvy-loader").remove();
				$(".rzvy_main_loader").addClass("rzvy_hide_loader");
				if(res.trim() == "mailsent"){
					swal(langObj.reset_password_link_sent_successfully_at_your_registered_email_address, "", "success");
				}else if(res.trim() == "tryagain"){
					swal(langObj.oops_error_occurred_please_try_again, "", "error");
				}else{
					swal(langObj.invalid_email_address, "", "error");
				}
			}
		});
	}
});

/** JS to get First box with category, Service and Addons Selection **/
$(document).on("click", "#rzvy-get-first-next-box-btn", function(){
	$(this).append(rzvy_loader);	
	var ajax_url = generalObj.ajax_url;
	$.ajax({
		type: 'post',
		data: {
			'get_first_step_box': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			$(".rzvy-loader").remove();
			/** To Enable Disable Menus START **/
			$('#rzvy_main_wizard a[data-toggle="tab"]').removeClass('active');
			$('#rzvy_main_wizard a[data-toggle="tab"]').addClass('disabled');
			$('.rzvy-first-step-tab').removeClass('disabled');
			$('.rzvy-first-step-tab').addClass('active');
			$(".rzvy-steps").removeClass("active");
			$(".rzvy-steps").removeClass("fade");
			$(".rzvy-steps").removeClass("show");
			$("#rzvy-first-step").addClass("fade show active");
			/** To Enable Disable Menus END **/
			
			$("#rzvy-first-step").html(res);
			
			/** To Check If category Selected **/
			if($('.rzvy-pcategories-selection').length>0){
				$('.rzvy-pcategories-selection.list_active').trigger('click');
			}else{
				var single_category_status = generalObj.single_category_status;
				if(generalObj.booking_first_selection_as == "category"){
					if(single_category_status == "Y"){
						if($('.rzvy-categories-radio-change').length==1){
							$('.rzvy-categories-radio-change').trigger('click');	
							$('.rzvy-category-container').slideUp();
						}else{
							$('.rzvy-categories-radio-change.list_active').trigger('click');
							$('.rzvy-category-container').slideDown();
						}
					}else{
						$('.rzvy-categories-radio-change.list_active').trigger('click');
						$('.rzvy-category-container').slideDown();
					}
				}else{
					/** Auto Trigger Service Check **/
					var single_service_status = generalObj.single_service_status;
					if(single_service_status == "Y"){
						if($('.rzvy-services-radio-change').length==1){
							$('.rzvy-services-radio-change').trigger('click');	
							$('.rzvy-services-container').slideUp();
						}else{
							$('.rzvy-services-radio-change.list_active').trigger('click');
							$('.rzvy-services-container').slideDown();
						}
					}
					/** To Check If Service Selected on prev **/
					else{
						$('.rzvy-services-radio-change.list_active').trigger('click');
						$('.rzvy-services-container').slideDown();
					}
				}
			}
		}
	});
});

/******************** FeedBack ***************************/

/** JS to mark rating stars **/
function rzvy_add_star_rating(ths,sno){
	for (var i=1;i<=5;i++){
		var cur=document.getElementById("rzvy-sidebar-feedback-star"+i)
		cur.className="fa fa-star-o rzvy-sidebar-feedback-star"
	}

	for (var i=1;i<=sno;i++){
		var cur=document.getElementById("rzvy-sidebar-feedback-star"+i)
		if(cur.className=="fa fa-star-o rzvy-sidebar-feedback-star")
		{
			cur.className="fa fa-star rzvy-sidebar-feedback-star rzvy-sidebar-feedback-star-checked"
		}
	}
	$("#rzvy_fb_rating").val(sno);
}

/** JS to submit feedback **/
$(document).on("click", "#rzvy_submit_feedback_btn", function(e){
	e.preventDefault();
	var ajax_url = generalObj.ajax_url;
	if($('#rzvy_feedback_form').valid()){
		var name = $("#rzvy_fb_name").val();
		var email = $("#rzvy_fb_email").val();
		var review = $("#rzvy_fb_review").val();
		var rating = $("#rzvy_fb_rating").val();
		$(this).append(rzvy_loader);
		$.ajax({
			type: 'post',
			data: {
				'email': email,
				'check_feedback_exist': 1
			},
			url: ajax_url + "rzvy_front_ajax.php",
			success: function (res) {
				$(".rzvy-loader").remove();
				if(res=="exist"){
					swal("Oops! Your review already exist", "", "error");
				}else{	
					$.ajax({
						type: 'post',
						data: {
							'name': name,
							'email': email,
							'review': review,
							'rating': rating,
							'add_feedback': 1
						},
						url: ajax_url + "rzvy_front_ajax.php",
						success: function (res) {
							if(res=="added"){
								swal(langObj.submitted_your_review_submitted_successfully, "", "success");
								location.reload();
							}else{
								swal(langObj.opps_something_went_wrong_please_try_again, "", "error");
							}
							
						}
					});
				}
			}
		});	
	}
});
/* Rezervy Sroll auto to next element */
if(generalObj.auto_scroll_each_module_status == "Y"){
	$(document).ajaxComplete(function(event,xhr,options){
		if(options.url!==undefined || options.url!==undefined!==null){
			var rzvy_currajax_xhr = options.url;
			if(rzvy_currajax_xhr.indexOf('rzvy_front_stepview_ajax.php')>=0){
				if(options.data!==undefined || options.data!==undefined!==null){
					var rzvy_currajax_xhrdata = options.data;
					if(rzvy_currajax_xhrdata.indexOf('on_pageload')>=0){
						if($(".rzvy-pcategory-container").is(':visible') && !$(".rzvy-pcategory-container").is(':hidden')){							
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-pcategory-container").offset().top)
							}, 800);
						}else if($(".rzvy-category-container").is(':visible') && !$(".rzvy-category-container").is(':hidden')){							
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-category-container").offset().top)
							}, 800);
						}else if($(".rzvy-services-container").is(':visible') && !$(".rzvy-services-container").is(':hidden')){							
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-services-container").offset().top)
							}, 800);
						}
					}
					if(rzvy_currajax_xhrdata.indexOf('get_subcat_by_pcid')>=0){
						if($(".rzvy-category-container").is(':visible') && !$(".rzvy-category-container").is(':hidden')){							
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-category-container").offset().top)
							}, 800);
						}
					}
					if(rzvy_currajax_xhrdata.indexOf('get_services_by_cat_id')>=0){
						if($(".rzvy-services-container").is(':visible') && !$(".rzvy-services-container").is(':hidden')){							
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-services-container").offset().top)
							}, 800);
						}
					}
					if(rzvy_currajax_xhrdata.indexOf('get_multi_and_single_qty_addons_content')>=0){
						if($(".rzvy-addons-container").is(':visible') && !$(".rzvy-addons-container").is(':hidden')){
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-addons-container").offset().top)
							}, 800);
						}
					}
					if(rzvy_currajax_xhrdata.indexOf('get_staff_according_service')>=0){
						if($(".rzvy-staff-container").is(':visible') && !$(".rzvy-staff-container").is(':hidden')){
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-staff-container").offset().top)
							}, 800);
						}else{
							if($(".rzvy-calendar-slots-container").is(':visible') && !$(".rzvy-calendar-slots-container").is(':hidden')){
								$('html, body').animate({
									scrollTop: parseInt($(".rzvy-calendar-slots-container").offset().top)
								}, 800);
							}
						}
					}
					if(rzvy_currajax_xhrdata.indexOf('update_frequently_discount')>=0){
						if($(".rzvy-staff-container").is(':visible') && !$(".rzvy-staff-container").is(':hidden')){
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-staff-container").offset().top)
							}, 800);
						}else{
							if($(".rzvy-calendar-slots-container").is(':visible') && !$(".rzvy-calendar-slots-container").is(':hidden')){
								$('html, body').animate({
									scrollTop: parseInt($(".rzvy-calendar-slots-container").offset().top)
								}, 800);
							}
						}
					}
					/* Staff Selected Manual */
					if(rzvy_currajax_xhrdata.indexOf('set_staff_according_service_manual')>=0 || rzvy_currajax_xhrdata.indexOf('get_slots_any_staff')>=0){
						if($(".rzvy-calendar-slots-container").is(':visible') && !$(".rzvy-calendar-slots-container").is(':hidden')){
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-calendar-slots-container").offset().top)
							}, 800);
						}
					}
					/** Staff triggered **/
					else if(rzvy_currajax_xhrdata.indexOf('set_staff_according_service')>=0){
						/* if($(".rzvy-freqdisc-container").is(':visible') && !$(".rzvy-freqdisc-container").is(':hidden')){
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-freqdisc-container").offset().top)
							}, 800);
						} */
						if($(".rzvy-calendar-slots-container").is(':visible') && !$(".rzvy-calendar-slots-container").is(':hidden')){
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-calendar-slots-container").offset().top)
							}, 800);
						}
					}else{}
					
					/* Date Trigger Selected */
					if(rzvy_currajax_xhrdata.indexOf('get_slots')>=0){						
						setTimeout(function(){								
						if($(".rzvy-calendar-slots").is(':visible') && !$(".rzvy-calendar-slots").is(':hidden')){
								$('html, body').animate({
									scrollTop: parseInt($(".rzvy-calendar-slots").offset().top)
								}, 800);
							}
						},1000);
					}
					
					
					
					if(rzvy_currajax_xhrdata.indexOf('get_first_step_box')>=0 || rzvy_currajax_xhrdata.indexOf('get_second_step_box')>=0 || rzvy_currajax_xhrdata.indexOf('get_third_step_box')>=0 || rzvy_currajax_xhrdata.indexOf('get_fourth_step_box')>=0){
						if($(".rzvy-wizard").is(':visible') && !$(".rzvy-wizard").is(':hidden')){
							$('html, body').animate({
								scrollTop: parseInt($(".rzvy-wizard").offset().top)
							}, 800);
						}
					}
				}				
			}			
		}
	});	
}

$(document).on("change", ".rzvy-lpoint-control-input", function(){
	$("#rzvy_bookingsummary").html("<label>"+langObj.please_wait_summary_loading+"</label>");
	var ajax_url = generalObj.ajax_url;
	$.ajax({
		type: 'post',
		data: {
			'lpoint_check': $(this).prop("checked"),
			'apply_loyalty_point': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (response) {
			$.ajax({
				type: 'post',
				async:true,
				data: {
					'user': $(".rzvy-user-selection:checked").val(),
					'is_booking_summary': 1,
					'refresh_cart_sidebar': 1
				},
				url: ajax_url + "rzvy_front_stepview_ajax.php",
				success: function (response) {
					$("#rzvy_bookingsummary").html(response);
					if($(".rzvy-lpoint-control-input").prop("checked")){
						$(".rzvy_update_lpoint_count").html($(".rzvy_cart_lp_amount").data("lpointtotal"));
						if(Number($(".rzvy_cart_lp_amount").val())>0){
							$(".rzvy_update_lpoint_amount").html($(".rzvy_cart_lp_amount").val());
						}else{
							$(".rzvy_update_lpoint_count").html("0");
							$(".rzvy_update_lpoint_amount").html("0");
						}
						$(".rzvy-cart-lpoint-main").slideDown();
					}else{
						$(".rzvy_update_lpoint_count").html("0");
						$(".rzvy_update_lpoint_amount").html("0");
						$(".rzvy-cart-lpoint-main").slideUp();
					}
					$(".rzvy-partial-deposit-control-input").trigger("change");
					rzvy_sv_payment_method_refresh_func();
				}
			});
		}
	});
});


/** JS to get end time slots **/
$(document).on("click", ".rzvy_time_slots_selection", function(){
	var ajax_url = generalObj.ajax_url;
	var check_endslot_status = generalObj.endslot_status;
	var selected_slot = $(this).val();
	var selected_date = $(".rzvy_date_selection.active_selected_date").data("day");
	
	$.ajax({
		type: 'post',
		data: {
			'selected_date': selected_date,
			'selected_startslot': selected_slot,
			'cal_selection': "Y",
			'add_selected_slot_withendslot': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			$(".rzvy_selected_slot_detail").html(res);
			$(".rzvy_selected_slot_detail").slideDown();
			/* $(".rzvy_back_to_calendar").trigger("click"); */
			/* $("#rzvy_fdate").val(selected_date);
			$("#rzvy_fstime").val(selected_slot); */
			/* $("#rzvy_fetime").val(selected_endslot); */
			$("#rzvy_time_slots_selection_date").val(selected_date);
			$("#rzvy_time_slots_selection_starttime").val(selected_slot);
			/* $("#rzvy_time_slots_selection_endtime").val(selected_endslot); */
		}
	});
});

/** JS to make login on frontend **/
$(document).on("click", "#rzvy_login_btn_s3", function(e){
	e.preventDefault();
	var ajax_url = generalObj.ajax_url;
	var email = $("#rzvy_login_email_s3").val();
	var password = $("#rzvy_login_password_s3").val();
	if($("#rzvy_login_form_s3").valid()){
		$(this).append(rzvy_loader);
				
		$.ajax({
			type: 'post',
			data: {
				'email': email,
				'password': password,
				'front_login': 1
			},
			url: ajax_url + "rzvy_front_ajax.php",
			success: function (res) {
				$(".rzvy-loader").remove();
				var detail = $.parseJSON(res);
				if(detail['status'] == "success"){
					$(".rzvy_loggedin_name_s3").html(detail['email']);
					$("#rzvy-existing-user-box-s3").slideUp();
					$(".rzvy-logout-div-s3").slideDown();
					$(".rzvy-lpoint-control-input").trigger("change");
					$(".rzvy_remove_applied_coupon").trigger("click");
				}else{
					swal(langObj.opps_your_entered_email_not_registered_please_book_an_appointment_as_new_customer, "", "error");
					$("#rzvy-existing-user-box-s3").slideDown();
					$(".rzvy-logout-div-s3").slideUp();
				}
			}
		});
	}
});

/** JS to show available rcoupon **/
$(document).on("click", ".rzvy-rcoupon-radio", function(){
	var ajax_url = generalObj.ajax_url;
	var id = $(this).val();
	var coupon_code = $(this).data("promo");
	$(".rzvy-available-rcoupon-list").removeClass("rzvy-rcoupon-radio-checked");
	$("#rzvy-rcoupon-radio-"+id).parent().addClass("rzvy-rcoupon-radio-checked");
	
	var ref_discount_coupon = coupon_code.toUpperCase();
	$.ajax({
		type: 'post',
		data: {
			'ref_discount_coupon': ref_discount_coupon,
			'apply_referral_discount': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			$("#rzvy-available-coupons-modal").modal("hide");
			if(res == "notexist"){
				$(".rzvy_remove_applied_rcoupon").attr('data-id','');
				$(".rzvy_applied_referral_coupon_code").html("");
				$(".rzvy_applied_referral_coupon_div_text").slideUp();
				$(".rzvy_apply_referral_coupon_div").slideDown();
				swal(langObj.please_enter_valid_referral_discount_coupon, "", "error");
			}else if(res == "used"){
				$(".rzvy_remove_applied_rcoupon").attr('data-id','');
				$(".rzvy_applied_referral_coupon_code").html("");
				$(".rzvy_applied_referral_coupon_div_text").slideUp();
				$(".rzvy_apply_referral_coupon_div").slideDown();
				swal(langObj.referral_discount_coupon_already_used, "", "error");
			}else if(res == "applied"){
				$(".rzvy_remove_applied_rcoupon").attr('data-id',id);
				$(".rzvy_applied_referral_coupon_code").html(ref_discount_coupon);
				$(".rzvy_applied_referral_coupon_div_text").slideDown();
				$(".rzvy_apply_referral_coupon_div").slideUp();
				swal(langObj.applied_referral_discount_coupon_applied_successfully, "", "success");
				$("#rzvy_bookingsummary").html("<label>"+langObj.please_wait_summary_loading+"</label>");
				$.ajax({
					type: 'post',
					async:true,
					data: {
						'user': $(".rzvy-user-selection:checked").val(),
						'is_booking_summary': 1,
						'refresh_cart_sidebar': 1
					},
					url: ajax_url + "rzvy_front_stepview_ajax.php",
					success: function (response) {
						$("#rzvy_bookingsummary").html(response);
						$(".rzvy-partial-deposit-control-input").trigger("change");
						rzvy_sv_payment_method_refresh_func();
					}
				});
			}else {
				$(".rzvy_remove_applied_rcoupon").attr('data-id','');
				$(".rzvy_applied_referral_coupon_code").html("");
				$(".rzvy_applied_referral_coupon_div_text").slideUp();
				$(".rzvy_apply_referral_coupon_div").slideDown();
				swal(langObj.opps_something_went_wrong_please_try_again, "", "error");
			}
		}
	});
});
/** JS to revert coupon **/
$(document).on("click", ".rzvy_remove_applied_rcoupon", function(){
	var ajax_url = generalObj.ajax_url;
	$.ajax({
		type: 'post',
		data: {
			'remove_applied_rcoupon': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			$(".rzvy_applied_referral_coupon_div_text").slideUp(1000);
			$(".rzvy-rcoupon-radio").prop("checked", false);
			$.ajax({
				type: 'post',
				async:true,
				data: {
					'user': $(".rzvy-user-selection:checked").val(),
					'is_booking_summary': 1,
					'refresh_cart_sidebar': 1
				},
				url: ajax_url + "rzvy_front_stepview_ajax.php",
				success: function (response) {
					$("#rzvy_bookingsummary").html(response);
					$(".rzvy-partial-deposit-control-input").trigger("change");
					rzvy_sv_payment_method_refresh_func();
				}
			});
		}
	});
});

/** JS to show sub categories according parent category selection **/
$(document).on('click', ".rzvy-pcategories-selection", function(){
	$(".rzvy-category-container").html("");
	$(".rzvy-services-container").html("");
	$(".rzvy-addons-container").html("");
	var ajax_url = generalObj.ajax_url;
	var id = $(this).data("id");
	$(".rzvy-pcategories-selection").removeClass("list_active");
	$("#rzvy-pcategories-selection-"+id).addClass("list_active");
	
	$(this).parent().parent().append(rzvy_loader);
	$.ajax({
		type: 'post',
		data: {
			'id': id,
			'get_subcat_by_pcid': 1
		},
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			$(".rzvy-loader").remove();
			$(".rzvy-category-container").html(res);
			$(".rzvy-category-container").slideDown();
			var single_category_status = generalObj.single_category_status;
			if(generalObj.booking_first_selection_as == "category"){
				if(single_category_status == "Y"){
					if($('.rzvy-categories-radio-change').length==1){
						$('.rzvy-categories-radio-change').trigger('click');	
						$('.rzvy-category-container').slideUp();
					}else{
						$('.rzvy-categories-radio-change.list_active').trigger('click');
						$('.rzvy-category-container').slideDown();
					}
				}else{
					$('.rzvy-categories-radio-change.list_active').trigger('click');
					$('.rzvy-category-container').slideDown();
				}
			}else{
				/** Auto Trigger Service Check **/
				var single_service_status = generalObj.single_service_status;
				if(single_service_status == "Y"){
					if($('.rzvy-services-radio-change').length==1){
						$('.rzvy-services-radio-change').trigger('click');	
						$('.rzvy-services-container').slideUp();
					}else{
						$('.rzvy-services-radio-change.list_active').trigger('click');
						$('.rzvy-services-container').slideDown();
					}
				}
				/** To Check If Service Selected on prev **/
				else{
					$('.rzvy-services-radio-change.list_active').trigger('click');
					$('.rzvy-services-container').slideDown();
				}
			}
		}
	});
});

/* iCal file Download */
$(document).on("click", "#rzvy_ical_booking_info_download", function(){
	var rzvy_blob = new Blob([$('#rzvy_ical_booking_info').text()], { type: 'text/calendar' });
	var rzvy_icslink = document.createElement('a');
	rzvy_icslink.href = window.URL.createObjectURL(rzvy_blob);
	rzvy_icslink.download = 'booking.ics';
	rzvy_icslink.click();
});

/* Set Staff Id In Today/Tomorrow All Slots */
$(document).on("change", ".rzvy_anystaff_selection", function(event){
	var ajax_url = generalObj.ajax_url;
	var id = $(this).data("staffid");
	$(this).parent().parent().append(rzvy_loader);
	
	var dataset = {
		'id': id,
		'set_staff_according_any': 1,
	}
	$.ajax({
		type: 'post',
		data: dataset,
		url: ajax_url + "rzvy_front_stepview_ajax.php",
		success: function (res) {
			$(".rzvy-loader").remove();
			$.ajax({
				type: 'post',
				async:true,
				data: {
					'user': $(".rzvy-user-selection:checked").val(),
					'is_booking_summary': 1,
					'refresh_cart_sidebar': 1
				},
				url: ajax_url + "rzvy_front_stepview_ajax.php",
				success: function (response) {
					$("#rzvy_bookingsummary").html(response);
					$(".rzvy-partial-deposit-control-input").trigger("change");
					rzvy_sv_payment_method_refresh_func();
				}
			});
		}
	});
});	

/** JS to make logout on frontend **/
$(document).on("click", "#rzvy_logout_header_btn", function(){
	var ajax_url = generalObj.ajax_url;	
	$(this).append(rzvy_loader);	
	$.ajax({
		type: 'post',
		data: {
			'front_logout': 1
		},
		url: ajax_url + "rzvy_front_ajax.php",
		success: function (res) {
			$(".rzvy-loader").remove();
			location.reload();
		}
	});
});