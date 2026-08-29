<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="-1" />
		<link rel="shortcut icon" type="image/png" href="<?php  echo SITE_URL; ?>includes/images/favicon.ico" />
		
		<?php 
		$rzvy_seo_ga_code = $obj_settings->get_option('rzvy_seo_ga_code');
		$rzvy_seo_meta_tag = $obj_settings->get_option('rzvy_seo_meta_tag');
		$rzvy_seo_meta_description = $obj_settings->get_option('rzvy_seo_meta_description');
		$rzvy_seo_og_meta_tag = $obj_settings->get_option('rzvy_seo_og_meta_tag');
		$rzvy_seo_og_tag_type = $obj_settings->get_option('rzvy_seo_og_tag_type');
		$rzvy_seo_og_tag_url = $obj_settings->get_option('rzvy_seo_og_tag_url');
		$rzvy_seo_og_tag_image = $obj_settings->get_option('rzvy_seo_og_tag_image'); 
		?>
		
		<title><?php if($rzvy_seo_meta_tag != ""){ echo $rzvy_seo_meta_tag; }else{ echo $obj_settings->get_option("rzvy_company_name"); } ?></title>
		
		<?php 
		if($rzvy_seo_meta_description != ''){ 
			?>
			<meta name="description" content="<?php echo $rzvy_seo_meta_description; ?>">
			<?php 
		} 
		if($rzvy_seo_og_meta_tag != ''){ 
			?>
			<meta property="og:title" content="<?php  echo $rzvy_seo_og_meta_tag; ?>" />
			<?php 
		} 
		if($rzvy_seo_og_tag_type != ''){ 
			?>
			<meta property="og:type" content="<?php echo $rzvy_seo_og_tag_type; ?>" />
			<?php 
		} 
		if($rzvy_seo_og_tag_url != ''){ 
			?>
			<meta property="og:url" content="<?php echo $rzvy_seo_og_tag_url; ?>" />
			<?php 
		} 
		if($rzvy_seo_og_tag_image != '' && file_exists("uploads/images/".$rzvy_seo_og_tag_image)){ 
			?>
			<meta property="og:image" content="<?php  echo SITE_URL; ?>uploads/images/<?php echo $rzvy_seo_og_tag_image; ?>" />
			<?php 
		} 
		if($rzvy_seo_ga_code != ''){ 
			?>
			<script data-name="googleAnalytics" async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $rzvy_seo_ga_code; ?>"></script>
			<script data-name="googleAnalytics">
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date());
				gtag('config', '<?php echo $rzvy_seo_ga_code; ?>');
			</script>
			<?php  
		} 
		$rzvy_hotjar_tracking_code = $obj_settings->get_option('rzvy_hotjar_tracking_code');
		if($rzvy_hotjar_tracking_code != ''){ 
			?>
			<script data-name="hotjarAnalytics">
				(function(h,o,t,j,a,r){
					h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
					h._hjSettings={hjid:<?php echo $rzvy_hotjar_tracking_code; ?>,hjsv:6};
					a=o.getElementsByTagName('head')[0];
					r=o.createElement('script');r.async=1;
					r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
					a.appendChild(r);
				})(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
			</script>
			<?php 
		} 
		$rzvy_fbpixel_tracking_code = $obj_settings->get_option('rzvy_fbpixel_tracking_code');
		if($rzvy_fbpixel_tracking_code != ''){ 
			?>
			<!-- Facebook Pixel Code -->
			<script data-name="facebookPixelAnalytics">
			  !function(f,b,e,v,n,t,s)
			  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
			  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
			  n.queue=[];t=b.createElement(e);t.async=!0;
			  t.src=v;s=b.getElementsByTagName(e)[0];
			  s.parentNode.insertBefore(t,s)}(window, document,'script',
			  'https://connect.facebook.net/en_US/fbevents.js');
			  fbq('init', '<?php echo $rzvy_fbpixel_tracking_code; ?>');
			  fbq('track', 'PageView');
			</script>
			<noscript>
			  <img height="1" width="1" style="display:none" 
				   src="https://www.facebook.com/tr?id=<?php echo $rzvy_fbpixel_tracking_code; ?>&ev=PageView&noscript=1"/>
			</noscript>
			<!-- End Facebook Pixel Code -->
			<?php 
		}   
		$rzvy_custom_css_bookingform = $obj_settings->get_option("rzvy_custom_css_bookingform");
		if($rzvy_custom_css_bookingform!=''){
		 echo '<style>'.$rzvy_custom_css_bookingform.'</style>';
		} 
		?> 

		<link href="<?php echo SITE_URL; ?>includes/css/flags.min.css" rel="stylesheet" type="text/css" media="all">
		<link href="<?php echo SITE_URL; ?>includes/css/jquery.datetimepicker.min.css" rel="stylesheet" type="text/css" media="all">
		<link href="<?php echo SITE_URL; ?>includes/css/owl.carousel.min.css" rel="stylesheet" type="text/css" media="all">
		<link href="<?php echo SITE_URL; ?>includes/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
		<link href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/bootstrap-select.min.css"  rel="stylesheet" type="text/css" media="all" />
		<link href="<?php echo SITE_URL; ?>includes/front/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="<?php echo SITE_URL; ?>includes/front/css/pe-icon-7-stroke.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/css/intlTelInput.css" rel="stylesheet" type="text/css" media="all" />
		<link href="<?php echo SITE_URL; ?>includes/front/css/rzvy-front-calendar-style.css" rel="stylesheet" type="text/css" media="all"/>
		<link href="<?php echo SITE_URL; ?>includes/front/css/rzvy-front-style.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" media="all"/>

		
		<!-- Bootstrap core JavaScript and Page level plugin JavaScript-->
		<script src="<?php echo SITE_URL; ?>includes/front/js/jquery-3.2.1.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/front/js/popper.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/front/js/bootstrap.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/vendor/bootstrap/js/bootstrap-select.min.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/front/js/slick.min.js?<?php echo time(); ?>"></script>
				
		<script src="<?php echo SITE_URL; ?>includes/js/bootstrap.bundle.min.js"></script>
		<script src="<?php echo SITE_URL; ?>includes/js/owl.carousel.min.js"></script>
		
		<script src="<?php echo SITE_URL; ?>includes/js/rzvy-common-front.js?<?php echo time(); ?>"></script>
		<?php 
		$rzvy_birthdate_with_year = $obj_settings->get_option('rzvy_birthdate_with_year');
		if($rzvy_birthdate_with_year == "Y"){ ?>
			<script src="<?php echo SITE_URL; ?>includes/js/jquery.datetimepicker.full.min.js"></script>
			<script>
			$(document).ready(function(){				
				$('.datepicker').datetimepicker({
					 timepicker: false,
					 format: "d/m/Y"
				});
				$.datetimepicker.setLocale('<?php echo $selectedlangcode; ?>');
			});
			</script>
		<?php }else{ ?>
			<script src="<?php echo SITE_URL; ?>includes/js/jquery.datetimepicker.full.min.js"></script>
			<script>
			$(document).ready(function(){
				$('.datepicker').datetimepicker ({
					 timepicker: false,
					 format: "d/m",
					 maxViewMode: 1,
					 defaultViewDate: {year: <?php echo date('Y');?>}
				});
				$.datetimepicker.setLocale('<?php echo $selectedlangcode; ?>');
			});
			
			</script>
		<?php } ?>
		
		<?php /* ?><script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery-ui.js?<?php echo time(); ?>"></script><?php */ ?>
		<script src="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.validate.min.js?<?php echo time(); ?>"></script>
		
		<?php include(dirname(__FILE__)."/includes/vendor/rzvyconcent/config.php"); ?>
		
		<?php include(dirname(__FILE__)."/includes/lib/rzvy_lang_objects.php"); ?>
		
		<?php if($obj_settings->get_option("rzvy_authorizenet_payment_status") == "Y" || $obj_settings->get_option("rzvy_twocheckout_payment_status") == "Y"){ ?>
		<script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.payment.min.js?<?php echo time(); ?>" type="text/javascript"></script>
		<script>
		$(document).ready(function(){
			/** card payment validation **/
			$('#rzvy-cardnumber').payment('formatCardNumber');
			$('#rzvy-cardcvv').payment('formatCardCVC');
			$('#rzvy-cardexmonth').payment('restrictNumeric');
			$('#rzvy-cardexyear').payment('restrictNumeric');
		});
		</script>
		<?php } ?>
		
		<?php if($obj_settings->get_option('rzvy_twocheckout_payment_status') == 'Y'){ ?>
		<script src="https://www.2checkout.com/checkout/api/2co.min.js" type="text/javascript"></script>	
		<?php } ?>
		<?php 
		$rzvy_ty_link = ""; 
		$rzvy_thankyou_page_url = $obj_settings->get_option('rzvy_thankyou_page_url'); 
		if($rzvy_thankyou_page_url != ""){
			$rzvy_ty_link = $rzvy_thankyou_page_url.$saiframe;
		}else{
			$rzvy_ty_link = SITE_URL."thankyou.php".$saiframe;
		}
		?>
		<!-- Custom scripts -->
		<script>
			var generalObj = { 'site_url' : '<?php echo SITE_URL; ?>', 'ajax_url' : '<?php echo AJAX_URL; ?>', 'ty_link' : '<?php echo $rzvy_ty_link; ?>', 'twocheckout_status' : '<?php echo $obj_settings->get_option('rzvy_twocheckout_payment_status'); ?>', 'twocheckout_sid' : '<?php echo $obj_settings->get_option('rzvy_twocheckout_seller_id'); ?>', 'twocheckout_pkey' : '<?php echo $obj_settings->get_option('rzvy_twocheckout_publishable_key'); ?>', 'stripe_status' : '<?php echo $obj_settings->get_option('rzvy_stripe_payment_status'); ?>', 'stripe_pkey' : '<?php echo $obj_settings->get_option('rzvy_stripe_publishable_key'); ?>', 'location_selector' : '<?php echo $show_location_selector; ?>', 'minimum_booking_amount':'<?php echo $obj_settings->get_option('rzvy_minimum_booking_amount');?>', 'endslot_status' : '<?php echo $obj_settings->get_option('rzvy_endtimeslot_selection_status'); ?>', 'single_category_status' : '<?php echo $obj_settings->get_option('rzvy_single_category_autotrigger_status'); ?>', 'defaultCountryCode' : '<?php $rzvy_default_country_code = $obj_settings->get_option("rzvy_default_country_code"); if($rzvy_default_country_code != ""){ echo $rzvy_default_country_code; }else{ echo "us"; } ?>', 'single_service_status' : '<?php echo $obj_settings->get_option('rzvy_single_service_autotrigger_status'); ?>', 'booking_first_selection_as' : '<?php if($rzvy_booking_first_selection_as == "allservices"){ echo "service"; }else{ echo "category";} ?>', 'auto_scroll_each_module_status' : '<?php echo $obj_settings->get_option('rzvy_auto_scroll_each_module_status'); ?>', 'single_staff_showhide_status' : '<?php echo $obj_settings->get_option('rzvy_single_staff_showhide_status'); ?>', 'book_with_datetime' : '<?php echo $rzvy_book_with_datetime; ?>', 'rzvy_success_modal_booking' : '<?php echo $rzvy_success_modal_booking; ?>', 'rzvy_customer_calendars' : '<?php echo $rzvy_customer_calendars; ?>','rzvy_todate':'<?php echo date("Y-m-d"); ?>' };
			
			var formfieldsObj = { 'en_ff_phone_status' : '<?php echo $rzvy_en_ff_phone_status; ?>', 'g_ff_phone_status' : '<?php echo $rzvy_g_ff_phone_status; ?>', 'en_ff_firstname' : '<?php echo $rzvy_en_ff_firstname_optional; ?>', 'en_ff_lastname' : '<?php echo $rzvy_en_ff_lastname_optional; ?>', 'en_ff_phone' : '<?php echo $rzvy_en_ff_phone_optional; ?>', 'en_ff_address' : '<?php echo $rzvy_en_ff_address_optional; ?>', 'en_ff_city' : '<?php echo $rzvy_en_ff_city_optional; ?>', 'en_ff_state' : '<?php echo $rzvy_en_ff_state_optional; ?>', 'en_ff_country' : '<?php echo $rzvy_en_ff_country_optional; ?>', 'g_ff_firstname' : '<?php echo $rzvy_g_ff_firstname_optional; ?>', 'g_ff_lastname' : '<?php echo $rzvy_g_ff_lastname_optional; ?>', 'g_ff_phone' : '<?php echo $rzvy_g_ff_phone_optional; ?>', 'g_ff_address' : '<?php echo $rzvy_g_ff_address_optional; ?>', 'g_ff_city' : '<?php echo $rzvy_g_ff_city_optional; ?>', 'g_ff_state' : '<?php echo $rzvy_g_ff_state_optional; ?>', 'g_ff_country' : '<?php echo $rzvy_g_ff_country_optional; ?>',
			'en_ff_dob' : '<?php echo $rzvy_en_ff_dob_optional; ?>',
			'en_ff_notes' : '<?php echo $rzvy_en_ff_notes_optional; ?>',
			'g_ff_dob' : '<?php echo $rzvy_g_ff_dob_optional; ?>',
			'g_ff_notes' : '<?php echo $rzvy_g_ff_notes_optional; ?>',
			'g_ff_email' : '<?php echo $rzvy_g_ff_email_optional; ?>' }; 
		</script>
		<script src="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/js/intlTelInput.js?<?php echo time(); ?>"></script>
		<?php $Rzvy_Hooks->onepageHeaderIncludes(); ?>
		<?php include("backend/bf_css.php"); ?>
		
		<!--------------- FONTS START ----------------->
		<?php 
		$rzvy_fontfamily = $obj_settings->get_option("rzvy_fontfamily");
		if($rzvy_fontfamily == 'Molle'){
			?><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Molle:400i"><?php 
		}else if($rzvy_fontfamily == 'Coda Caption'){
			?><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Coda+Caption:800"><?php 
		}else{
			?><link rel="stylesheet" href="https://fonts.googleapis.com/css?family=<?php echo $rzvy_fontfamily; ?>:300,400,700"><?php 
		}
		?>
		<style>
		html {
			font-family: '<?php echo $rzvy_fontfamily; ?>', sans-serif !important;
		}
		body.rzvy {
			font-family: '<?php echo $rzvy_fontfamily; ?>', sans-serif !important;
		}
		</style>
		<!--------------- FONTS END ----------------->
	</head>
	<body class="rzvy"  onscroll="parent.postMessage(document.body.scrollHeight, '*');" onload="parent.postMessage(document.body.scrollHeight, '*');" >
		<?php include(dirname(__FILE__)."/header2.php"); ?>
		
		<div id="rzvy-loader-overlay" class="rzvy_main_loader rzvy_hide_loader">
			<div id="rzvy-loader" class="rzvy_main_loader rzvy_hide_loader">
				<div class="rzvy-loader-dot"></div>
				<div class="rzvy-loader-dot"></div>
				<div class="rzvy-loader-dot"></div>
				<div class="rzvy-loader-dot"></div>
				<div class="rzvy-loader-dot"></div>
				<div class="rzvy-loader-dot"></div>
				<div class="rzvy-loader-dot"></div>
				<div class="rzvy-loader-dot"></div>
				<div class="rzvy-loader-loading"></div>
			</div>
		</div>
		<main class="form-style-two rzvy-booking-detail-body">
			<div class="container rzvy-booking-detail-block rzvy-center-block rzvy-main-block-before">
				<div class="row">
					<div class="col-lg-8">
							<div class="rzvy-booking-detail-main">
								
								<!-- Services selection SECTION START -->
								<div id="rzvy-default-pageload-container">
								</div>
								<!-- Services selection SECTION END -->
								
								<!-- STAFF SECTION START -->
								<div id="rzvy-staff-main" class="<?php echo $inputAlignment; ?> pt-4 mt-4">
								</div>
								<!-- STAFF SECTION END -->
								<?php 
								if($rzvy_book_with_datetime == "Y"){
									?>
									<div class="row rzvy_hide_calendar_before_staff_selection mt-4">
										<div class="col-md-12">
											<div class="rzvy-radio-group-block-content rzvy-no-border-bottom mt-1 pt-2 pb-3  <?php echo $inputAlignment; ?> ">
												<h4><?php if(isset($rzvy_translangArr['choose_your_appointment_slot'])){ echo $rzvy_translangArr['choose_your_appointment_slot']; }else{ echo $rzvy_defaultlang['choose_your_appointment_slot']; } ?></h4>
											</div>
										</div>
									</div>
									<div class="row pt-0 rzvy_hide_calendar_before_staff_selection">
										<div class="col-md-12 ">
											<div class="rzvy-inline-calendar">
												<div class="rzvy-inline-calendar-container rzvy-inline-calendar-container-boxshadow">
													<center><h3><?php if(isset($rzvy_translangArr['please_wait'])){ echo $rzvy_translangArr['please_wait']; }else{ echo $rzvy_defaultlang['please_wait']; } ?></h3></center>
												</div>												
											</div>
											<input type="hidden" id="rzvy_time_slots_selection_date" value="" />
											<input type="hidden" id="rzvy_time_slots_selection_starttime" value="" />
											<input type="hidden" id="rzvy_time_slots_selection_endtime" value="" />
											<input type="hidden" id="rzvy_fdate" value="" />
											<input type="hidden" id="rzvy_fstime" value="" />
											<input type="hidden" id="rzvy_fetime" value="" />
										</div>
									</div>
									<?php 
								} 
								?>
								<div class="rzvy-radio-group-block mt-4 show_hide_frequently_discount ">
									<h4 class="pb-2 <?php echo $labelAlignmentClassName; ?>"><?php if(isset($rzvy_translangArr['how_often_would_you_like_service'])){ echo $rzvy_translangArr['how_often_would_you_like_service']; }else{ echo $rzvy_defaultlang['how_often_would_you_like_service']; } ?></h4>
									<div id="rzvy_frequently_discount_content" class="show_hide_frequently_discount">
										<!-- frequently discount will go here -->
									</div>
								</div>
								<?php 
								$useremail = "";
								$userpassword = "";
								$userfirstname = "";
								$userlastname = "";
								if(isset($_SESSION['rzvy_location_selector_zipcode'])){
									$userzip = $_SESSION['rzvy_location_selector_zipcode'];
								}else{
									$userzip = "";
								}
								$userphone = "";
								$useraddress = "";
								$usercity = "";
								$userstate = "";
								$usercountry = "";
								$userdob = "";
								if(isset($_SESSION['customer_id'])){
									$obj_frontend->customer_id = $_SESSION['customer_id'];
									$customer_detail = $obj_frontend->readone_customer();
									$useremail = $customer_detail['email'];
									$userpassword = $customer_detail['password'];
									$userfirstname = ucwords($customer_detail['firstname']);
									$userlastname = ucwords($customer_detail['lastname']);
									if(isset($_SESSION['rzvy_location_selector_zipcode'])){
										$userzip = $_SESSION['rzvy_location_selector_zipcode'];
									}else{
										$userzip = "";
									}
									$userphone = $customer_detail['phone'];
									$useraddress = $customer_detail['address'];
									$usercity = $customer_detail['city'];
									$userstate = $customer_detail['state'];
									$usercountry = $customer_detail['country'];
									$userdob = $customer_detail['dob'];
								} 
								?>
								<div class="step-item <?php echo $inputAlignment; ?> mt-5">
									<h4 class="pb-2 step-title <?php echo $inputAlignment; ?>"><?php if(isset($rzvy_translangArr['personal_information'])){ echo $rzvy_translangArr['personal_information']; }else{ echo $rzvy_defaultlang['personal_information']; } ?></h4>
									<div class="rzvy-container">
									  <div class="custom-controls rzvy-users-selection-div" <?php if(isset($_SESSION['customer_id'])){ echo "style='display:none;'"; } ?>>
										<div class="row justify-content-center">
										  <?php 
											$rzvy_show_existing_new_user_checkout = $obj_settings->get_option("rzvy_show_existing_new_user_checkout");
											$rzvy_show_guest_user_checkout = $obj_settings->get_option("rzvy_show_guest_user_checkout");
											if($rzvy_show_existing_new_user_checkout == "Y"){ 
												?>
												<div class="col-sm-6">
													<div class="form-check custom inline">
														<input type="radio" class="form-check-input rzvy-user-selection" id="rzvy-existing-user" name="rzvy-user-selection" checked value="ec" data-toggle="rzvy_login_form"/>
														<label class="form-check-label rzvy-user-selection-label" for="rzvy-existing-user"><?php if(isset($rzvy_translangArr['existing_customer'])){ echo $rzvy_translangArr['existing_customer']; }else{ echo $rzvy_defaultlang['existing_customer']; } ?></label>
													</div>
												</div>		
												<div class="col-sm-6">
													<div class="form-check custom inline">
														<input type="radio" class="form-check-input rzvy-user-selection" id="rzvy-new-user" name="rzvy-user-selection" value="nc" data-toggle="rzvy_new_form"/>
														<label class="form-check-label rzvy-user-selection-label" for="rzvy-new-user"><?php if(isset($rzvy_translangArr['new_customer'])){ echo $rzvy_translangArr['new_customer']; }else{ echo $rzvy_defaultlang['new_customer']; } ?></label>
													</div>
												</div>		
											
											<?php 
											}
											
											if($rzvy_show_guest_user_checkout == "Y"){ 
												$customcss = "";
												if($rzvy_show_existing_new_user_checkout == "N" && $rzvy_show_guest_user_checkout == "Y"){ 
													$customcss = "style='display:none'";
												}
												?>
												<div class="col-sm-6">
													<div class="form-check custom inline">
														<input <?php echo $customcss; ?> type="radio" class="form-check-input rzvy-user-selection" id="rzvy-guest-user" name="rzvy-user-selection" <?php if($rzvy_show_existing_new_user_checkout == "N"){ echo "checked"; } ?> value="gc" data-toggle="rzvy_guest_form"/>
														<label <?php echo $customcss; ?> class="form-check-label rzvy-user-selection-label" for="rzvy-guest-user"><?php if(isset($rzvy_translangArr['guest_customer'])){ echo $rzvy_translangArr['guest_customer']; }else{ echo $rzvy_defaultlang['guest_customer']; } ?></label>
													</div>
												</div>		
												<?php 
											} 
											
											if($rzvy_show_existing_new_user_checkout == "Y"){ 
												?>
												<div class="col-sm-6">
													<div class="form-check custom inline">
														<input type="radio" class="form-check-input rzvy-user-selection" id="rzvy-user-forget-password" name="rzvy-user-selection" value="fp" data-toggle="rzvy_password_form"/>
														<label class="form-check-label rzvy-user-selection-label" for="rzvy-user-forget-password"><?php if(isset($rzvy_translangArr['forget_password'])){ echo $rzvy_translangArr['forget_password']; }else{ echo $rzvy_defaultlang['forget_password']; } ?></label>
													</div>
												</div>	
												<?php 
											} 
											?>
										</div>
									  </div>
									  <div class="rzvy-logout-div mt-2" <?php if(isset($_SESSION['customer_id'])){ echo "style='display:block;'"; } ?> >
											<label><?php if(isset($rzvy_translangArr['you_are_logged_in_as'])){ echo $rzvy_translangArr['you_are_logged_in_as']; }else{ echo $rzvy_defaultlang['you_are_logged_in_as']; } ?> <b class="rzvy_loggedin_name"><?php echo $useremail; ?></b>. <a href="javascript:void(0)" id="rzvy_logout_btn"><?php if(isset($rzvy_translangArr['logout'])){ echo $rzvy_translangArr['logout']; }else{ echo $rzvy_defaultlang['logout']; } ?></a></label>
										</div>
									</div>
								</div>	
								
								<div class="forms">
								<?php 
								if($rzvy_show_existing_new_user_checkout == "Y"){ 
									?>
									<div class="form-item" data-form="rzvy-existing-user">
										<form method="post" name="rzvy_login_form" id="rzvy_login_form">
											<div class="rzvy-radio-group-block mt24  <?php echo $inputAlignment; ?>" <?php if(isset($_SESSION['customer_id'])){ echo "style='display:none;'"; } ?> id="rzvy-existing-user-box">
												<div class="form-group">
													<div class="rzvy-input-class-div">
														<input type="email" name="rzvy_login_email" id="rzvy_login_email" placeholder="<?php if(isset($rzvy_translangArr['email_address'])){ echo $rzvy_translangArr['email_address']; }else{ echo $rzvy_defaultlang['email_address']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
													</div>
												</div>
												<div class="form-group">
													<div class="rzvy-input-class-div">
														<input type="password" name="rzvy_login_password" id="rzvy_login_password" placeholder="<?php if(isset($rzvy_translangArr['password'])){ echo $rzvy_translangArr['password']; }else{ echo $rzvy_defaultlang['password']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
													</div>
												</div>
												<button id="rzvy_login_btn" class="btn btn-success w-100 mt-2" type="submit"><i class="fa fa-lock"></i> &nbsp;&nbsp;<?php if(isset($rzvy_translangArr['login'])){ echo $rzvy_translangArr['login']; }else{ echo $rzvy_defaultlang['login']; } ?></button>
											</div>
										</form>
									</div>
									<div class="form-item" data-form="rzvy-new-user" <?php if(isset($_SESSION['customer_id'])){ echo "style='display:block;'"; } ?>>
										<form method="post" name="rzvy_user_detail_form" id="rzvy_user_detail_form">
											<div class="rzvy-radio-group-block mt24  <?php echo $inputAlignment; ?>" <?php if(isset($_SESSION['customer_id'])){ echo "style='display:block;'"; } ?> id="rzvy-new-user-box">
												<div class="row rzvy_hide_after_login" <?php if(isset($_SESSION['customer_id'])){ echo "style='display:none;'"; } ?>>
													<div class="col-sm-6">
														<div class="form-group">
															<input type="email" id="rzvy_user_email" name="rzvy_user_email" placeholder="<?php if(isset($rzvy_translangArr['email_address'])){ echo $rzvy_translangArr['email_address']; }else{ echo $rzvy_defaultlang['email_address']; } ?>" value="<?php echo $useremail; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<input type="password" id="rzvy_user_password" name="rzvy_user_password" placeholder="<?php if(isset($rzvy_translangArr['password'])){ echo $rzvy_translangArr['password']; }else{ echo $rzvy_defaultlang['password']; } ?>" value="<?php echo $userpassword; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<?php 
													if($rzvy_en_ff_firstname_status == "Y"){ 
														?>
														<div class="col-sm-6">
															<div class="form-group">
																<input type="text" id="rzvy_user_firstname" name="rzvy_user_firstname" placeholder="<?php if(isset($rzvy_translangArr['first_name'])){ echo $rzvy_translangArr['first_name']; }else{ echo $rzvy_defaultlang['first_name']; } ?>" value="<?php echo $userfirstname; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
															</div>
														</div>
														<?php  
													}else{ 
														?>
														<input type="hidden" id="rzvy_user_firstname" name="rzvy_user_firstname" value="<?php echo $userfirstname; ?>" />
														<?php 
													} 
													
													if($rzvy_en_ff_lastname_status == "Y"){ 
														?>
														<div class="col-sm-6">
															<div class="form-group">
																<input type="text" id="rzvy_user_lastname" name="rzvy_user_lastname" placeholder="<?php if(isset($rzvy_translangArr['last_name'])){ echo $rzvy_translangArr['last_name']; }else{ echo $rzvy_defaultlang['last_name']; } ?>" value="<?php echo $userlastname; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
															</div>
														</div>
														<?php  
													}else{ 
														?>
														<input type="hidden" id="rzvy_user_lastname" name="rzvy_user_lastname" value="<?php echo $userlastname; ?>" />
														<?php 
													} 
													
													if($rzvy_en_ff_phone_status == "Y"){ 
														?>
														<div class="col-sm-6">
															<div class="form-group">
																<input type="text" id="rzvy_user_phone" name="rzvy_user_phone" placeholder="<?php if(isset($rzvy_translangArr['phone_number'])){ echo $rzvy_translangArr['phone_number']; }else{ echo $rzvy_defaultlang['phone_number']; } ?>" value="<?php echo $userphone; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
																<label for="rzvy_user_phone" generated="true" class="error"></label>
															</div>
														</div>
														<?php  
													}else{ 
														?>
														<input type="hidden" id="rzvy_user_phone" name="rzvy_user_phone" value="<?php echo $userphone; ?>" />
														<?php 
													} 
													
													$show_zip_input = "";
													if($rzvy_location_selector_status == "N" || $rzvy_location_selector_status == ""){ 
														$show_zip_input= "rzvy_hide";
													}
													?>
													<div class="col-sm-6 <?php echo $show_zip_input; ?>">
														<div class="form-group">
															<input type="text" id="rzvy_user_zip" name="rzvy_user_zip" placeholder="<?php if(isset($rzvy_translangArr['zip'])){ echo $rzvy_translangArr['zip']; }else{ echo $rzvy_defaultlang['zip']; } ?>" disabled value="<?php echo $userzip; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
														</div>
													</div>
													
													<?php  
													if($rzvy_en_ff_address_status == "Y"){ 
														?>
														<div class="col-sm-12">
															<div class="form-group">
																<input type="text" id="rzvy_user_address" name="rzvy_user_address" placeholder="<?php if(isset($rzvy_translangArr['address'])){ echo $rzvy_translangArr['address']; }else{ echo $rzvy_defaultlang['address']; } ?>" value="<?php echo $useraddress; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
															</div>
														</div>
														<?php  
													}else{ 
														?>
														<input type="hidden" id="rzvy_user_address" name="rzvy_user_address" value="<?php echo $useraddress; ?>" />
														<?php 
													} 
													
													if($rzvy_en_ff_city_status == "Y"){ 
														?>
														<div class="col-sm-6">
															<div class="form-group">
																<input type="text" id="rzvy_user_city" name="rzvy_user_city" placeholder="<?php if(isset($rzvy_translangArr['city'])){ echo $rzvy_translangArr['city']; }else{ echo $rzvy_defaultlang['city']; } ?>" value="<?php echo $usercity; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
															</div>
														</div>
														<?php  
													}else{ 
														?>
														<input type="hidden" id="rzvy_user_city" name="rzvy_user_city" value="<?php echo $usercity; ?>" />
														<?php 
													} 
													
													if($rzvy_en_ff_state_status == "Y"){ 
														?>
														<div class="col-sm-6">
															<div class="form-group">
																<input type="text" id="rzvy_user_state" name="rzvy_user_state" placeholder="<?php if(isset($rzvy_translangArr['state'])){ echo $rzvy_translangArr['state']; }else{ echo $rzvy_defaultlang['state']; } ?>" value="<?php echo $userstate; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
															</div>
														</div>
														<?php  
													}else{ 
														?>
														<input type="hidden" id="rzvy_user_state" name="rzvy_user_state" value="<?php echo $userstate; ?>" />
														<?php 
													} 
													
													if($rzvy_en_ff_country_status == "Y"){ 
														?>
														<div class="col-sm-6">
															<div class="form-group">
																<input type="text" id="rzvy_user_country" name="rzvy_user_country" placeholder="<?php if(isset($rzvy_translangArr['country'])){ echo $rzvy_translangArr['country']; }else{ echo $rzvy_defaultlang['country']; } ?>" value="<?php echo $usercountry; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
															</div>
														</div>
														<?php  
													}else{ 
														?>
														<input type="hidden" id="rzvy_user_country" name="rzvy_user_country" value="<?php echo $usercountry; ?>" />
														<?php 
													} 
													
													if($rzvy_en_ff_dob_status == "Y"){ 
														?>
														<div class="col-sm-6">
															<div class="form-group">
																<input type="text" id="rzvy_user_dob" name="rzvy_user_dob" placeholder="<?php if(isset($rzvy_translangArr['birthdate'])){ echo $rzvy_translangArr['birthdate']; }else{ echo $rzvy_defaultlang['birthdate']; } ?>" value="<?php if($userdob != ""){ if($rzvy_birthdate_with_year == "Y"){ echo date("j F Y", strtotime($userdob)); }else{ echo date("j F", strtotime($userdob)); } } ?>" class="form-control datepicker rzvy-input-class <?php echo $inputAlignment; ?>" onfocus="this.blur()">
																<label for="rzvy_user_dob" generated="true" class="error"></label>
															</div>
														</div>
														<?php  
													}else{ 
														?>
														<input type="hidden" id="rzvy_user_dob" name="rzvy_user_dob" value="<?php if($userdob != ""){ if($rzvy_birthdate_with_year == "Y"){ echo date("j F Y", strtotime($userdob)); }else{ echo date("j F", strtotime($userdob)); } } ?>" />
														<?php 
													} 
													
													if($rzvy_en_ff_notes_status == "Y"){ 
														?>
														<div class="col-sm-12">
															<div class="form-group">
																<input type="text" id="rzvy_user_notes" name="rzvy_user_notes" placeholder="<?php if(isset($rzvy_translangArr['notes'])){ echo $rzvy_translangArr['notes']; }else{ echo $rzvy_defaultlang['notes']; } ?>" value="" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
															</div>
														</div>
														<?php  
													}else{ 
														?>
														<input type="hidden" id="rzvy_user_notes" name="rzvy_user_notes" value="" />
														<?php 
													} 
													?>
												</div>
											</div>
										</form>
									</div>
								<?php } ?>
								
								<?php if($rzvy_show_guest_user_checkout == "Y"){ ?>
								<div class="form-item" data-form="rzvy-guest-user">
									<form method="post" name="rzvy_guestuser_detail_form" id="rzvy_guestuser_detail_form">
										<div class="rzvy-radio-group-block mt24  <?php echo $inputAlignment; ?>" <?php if($rzvy_show_existing_new_user_checkout == "N"){ echo "style='display:block;'"; } ?> id="rzvy-guest-user-box">
											<div class="row">
												<?php 
												if($rzvy_g_ff_firstname_status == "Y"){ 
													?>
													<div class="col-sm-6">
														<div class="form-group">
															<input type="text" id="rzvy_guest_firstname" name="rzvy_guest_firstname" placeholder="<?php if(isset($rzvy_translangArr['first_name'])){ echo $rzvy_translangArr['first_name']; }else{ echo $rzvy_defaultlang['first_name']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
														</div>
													</div>
													<?php 
												}else{ 
													?>
													<input type="hidden" id="rzvy_guest_firstname" name="rzvy_guest_firstname" />
													<?php 
												} 
												
												if($rzvy_g_ff_lastname_status == "Y"){ 
													?>
													<div class="col-sm-6">
														<div class="form-group">
															<input type="text" id="rzvy_guest_lastname" name="rzvy_guest_lastname" placeholder="<?php if(isset($rzvy_translangArr['last_name'])){ echo $rzvy_translangArr['last_name']; }else{ echo $rzvy_defaultlang['last_name']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
														</div>
													</div>
													<?php 
												}else{ 
													?>
													<input type="hidden" id="rzvy_guest_lastname" name="rzvy_guest_lastname" />
													<?php 
												} 
												
												if($rzvy_g_ff_email_status == "Y"){ 
													?>
													<div class="col-sm-6">
														<div class="form-group">
															<input type="text" id="rzvy_guest_email" name="rzvy_guest_email" placeholder="<?php if(isset($rzvy_translangArr['email_address'])){ echo $rzvy_translangArr['email_address']; }else{ echo $rzvy_defaultlang['email_address']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
														</div>
													</div>
													<?php 
												}else{ 
													?>
													<input type="hidden" id="rzvy_guest_email" name="rzvy_guest_email" />
													<?php 
												} 
												
												if($rzvy_g_ff_phone_status == "Y"){ 
													?>
													<div class="col-sm-6">
														<div class="form-group">
															<input type="text" id="rzvy_guest_phone" name="rzvy_guest_phone" placeholder="<?php if(isset($rzvy_translangArr['phone_number'])){ echo $rzvy_translangArr['phone_number']; }else{ echo $rzvy_defaultlang['phone_number']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
															<label for="rzvy_guest_phone" generated="true" class="error"></label>
														</div>
													</div>
													<?php 
												}else{ 
													?>
													<input type="hidden" id="rzvy_guest_phone" name="rzvy_guest_phone" />
													<?php 
												}
												
												$show_gzip_input = "";
												if($rzvy_location_selector_status == "N" || $rzvy_location_selector_status == ""){ 
													$show_gzip_input= "rzvy_hide";
												}
												
												if($rzvy_g_ff_address_status == "Y"){ 
													?>
													<div class="col-sm-12">
														<div class="form-group">
															<input type="text" id="rzvy_guest_address" name="rzvy_guest_address" placeholder="<?php if(isset($rzvy_translangArr['address'])){ echo $rzvy_translangArr['address']; }else{ echo $rzvy_defaultlang['address']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
														</div>
													</div>
													<?php 
												}else{ 
													?>
													<input type="hidden" id="rzvy_guest_address" name="rzvy_guest_address" />
													<?php 
												} 
												?>
												<div class="col-sm-6 <?php echo $show_gzip_input; ?>">
													<div class="form-group">
														<input type="text" id="rzvy_guest_zip" name="rzvy_guest_zip" placeholder="<?php if(isset($rzvy_translangArr['zip'])){ echo $rzvy_translangArr['zip']; }else{ echo $rzvy_defaultlang['zip']; } ?>" disabled value="<?php echo $userzip; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
													</div>
												</div>
												<?php 
												if($rzvy_g_ff_city_status == "Y"){ 
													?>
													<div class="col-sm-6">
														<div class="form-group">
															<input type="text" id="rzvy_guest_city" name="rzvy_guest_city" placeholder="<?php if(isset($rzvy_translangArr['city'])){ echo $rzvy_translangArr['city']; }else{ echo $rzvy_defaultlang['city']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
														</div>
													</div>
													<?php 
												}else{ 
													?>
													<input type="hidden" id="rzvy_guest_city" name="rzvy_guest_city" />
													<?php 
												} 
												if($rzvy_g_ff_state_status == "Y"){ 
													?>
													<div class="col-sm-6">
														<div class="form-group">
															<input type="text" id="rzvy_guest_state" name="rzvy_guest_state" placeholder="<?php if(isset($rzvy_translangArr['state'])){ echo $rzvy_translangArr['state']; }else{ echo $rzvy_defaultlang['state']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
														</div>
													</div>
													<?php 
												}else{ 
													?>
													<input type="hidden" id="rzvy_guest_state" name="rzvy_guest_state" />
													<?php 
												} 
												if($rzvy_g_ff_country_status == "Y"){ 
													?>
													<div class="col-sm-6">
														<div class="form-group">
															<input type="text" id="rzvy_guest_country" name="rzvy_guest_country" placeholder="<?php if(isset($rzvy_translangArr['country'])){ echo $rzvy_translangArr['country']; }else{ echo $rzvy_defaultlang['country']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
														</div>
													</div>
													<?php 
												}else{ 
													?>
													<input type="hidden" id="rzvy_guest_country" name="rzvy_guest_country" />
													<?php 
												} 
												if($rzvy_g_ff_dob_status == "Y"){ 
													?>
													<div class="col-sm-6">
														<div class="form-group">
															<input type="text" id="rzvy_guest_dob" name="rzvy_guest_dob" placeholder="<?php if(isset($rzvy_translangArr['birthdate'])){ echo $rzvy_translangArr['birthdate']; }else{ echo $rzvy_defaultlang['birthdate']; } ?>" value="" onfocus="this.blur()" class="datepicker form-control rzvy-input-class <?php echo $inputAlignment; ?>">
															<label for="rzvy_guest_dob" generated="true" class="error"></label>
														</div>
													</div>
													<?php 
												}else{ 
													?>
													<input type="hidden" id="rzvy_guest_dob" name="rzvy_guest_dob" value=""/>
													<?php 
												} 
												if($rzvy_g_ff_notes_status == "Y"){ 
													?>
													<div class="col-sm-12">
														<div class="form-group">
															<input type="text" id="rzvy_guest_notes" name="rzvy_guest_notes" placeholder="<?php if(isset($rzvy_translangArr['notes'])){ echo $rzvy_translangArr['notes']; }else{ echo $rzvy_defaultlang['notes']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
														</div>
													</div>
													<?php 
												}else{ 
													?>
													<input type="hidden" id="rzvy_guest_notes" name="rzvy_guest_notes" />
													<?php 
												} 
												?>
											</div>
										</div>
									</form>
								</div>	
								<?php } ?>
								<?php if($rzvy_show_existing_new_user_checkout == "Y" || $rzvy_show_guest_user_checkout == "Y"){ ?>
								<div class="form-item" data-form="rzvy-user-forget-password">
									<form id="rzvy_forgot_password_form" name="rzvy_forgot_password_form">
										<div class="form-group <?php echo $inputAlignment; ?>" id="rzvy-user-forget-password-box">
											<label class="form-label" for="rzvy_forgot_password_email"><?php if(isset($rzvy_translangArr['your_registered_email_address'])){ echo $rzvy_translangArr['your_registered_email_address']; }else{ echo $rzvy_defaultlang['your_registered_email_address']; } ?></label>
											<input type="email" id="rzvy_forgot_password_email" name="rzvy_forgot_password_email" placeholder="<?php if(isset($rzvy_translangArr['your_registered_email_address'])){ echo $rzvy_translangArr['your_registered_email_address']; }else{ echo $rzvy_defaultlang['your_registered_email_address']; } ?>" class="form-control <?php echo $inputAlignment; ?>">
										</div>
										<button class="btn btn-success w-100 mt-2" id="rzvy_forgot_password_btn" type="submit"><i class="fa fa-envelope"></i> &nbsp;&nbsp;<?php if(isset($rzvy_translangArr['send_mail'])){ echo $rzvy_translangArr['send_mail']; }else{ echo $rzvy_defaultlang['send_mail']; } ?></button>
									</form>
								</div>	
								<?php } ?>
								</div>
								<?php 
								/* Services Package Listing */
								$Rzvy_Hooks->do_action('services_package_listing', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang,$inputAlignment));
								/* Services Package Listing End */
								?>
								<div class="step-item remove_payment_according_services_showhide rzvy_hide_show_payment_according_services">
									<div class="rzvy-container">
										<div class="rzvy-radio-group-block-content step-item">
											<div class="whitebox">
											<?php 
											if($obj_settings->get_option("rzvy_allow_loyalty_points_status") == "Y" && $rzvy_show_existing_new_user_checkout == "Y"){ ?>
												<div class="mb-3 row align-items-center rzvy-cart-lpoint-div">
												<div class="col-md-6 rzvy-cart-lpoint-label-main">
												  <div class="form-check mb-0">
													<input class="form-check-input custom-control-input rzvy-lpoint-control-input" type="checkbox" name="loyalty-points" id="rzvy-loyalty-points" onclick="$('#rzvy-loyalty-points-info').slideToggle();">
													<label class="form-check-label" for="rzvy-loyalty-points"><?php if(isset($rzvy_translangArr['do_you_want_to_use_your_loyalty_points'])){ echo $rzvy_translangArr['do_you_want_to_use_your_loyalty_points']; }else{ echo $rzvy_defaultlang['do_you_want_to_use_your_loyalty_points']; } ?></label>
												  </div>
												</div>
												<div class="col-md-6 rzvy-cart-lpoint-main">
												  <div class="point-wrap" id="rzvy-loyalty-points-info"><i class="fa fa-fw fa-tag me-2" aria-hidden="true"></i><?php if(isset($rzvy_translangArr['your_loyalty_points_ad'])){ echo $rzvy_translangArr['your_loyalty_points_ad']; }else{ echo $rzvy_defaultlang['your_loyalty_points_ad']; } ?> <span class='rzvy_update_lpoint_count'>0</span> <?php if(isset($rzvy_translangArr['of'])){ echo $rzvy_translangArr['of']; }else{ echo $rzvy_defaultlang['of']; } ?> <?php echo $currencyB."<span class='rzvy_update_lpoint_amount'>".$_SESSION['rzvy_cart_lpoint']."</span>".$currencyA; ?></div>
												</div>
											  </div>											
											<?php }
											if($obj_settings->get_option("rzvy_partial_deposite_status") == "Y"){ ?>
												<div class="mb-3 row align-items-center">
													<div class="col-md-6 rzvy-cart-partial-deposite-label-main">
													  <div class="form-check mb-0">
														<input class="form-check-input rzvy-partial-deposit-control-input" type="checkbox" name="pay-partially" id="rzvy-partial-deposit" onclick="$('#rzvy-partial-deposit-info').slideToggle();">
														<label class="form-check-label" for="rzvy-partial-deposit"><?php if(isset($rzvy_translangArr['do_you_want_to_pay_partially'])){ echo $rzvy_translangArr['do_you_want_to_pay_partially']; }else{ echo $rzvy_defaultlang['do_you_want_to_pay_partially']; } ?></label>
													  </div>
													</div>
													<div class="col-md-6">
													  <div class="point-wrap rzvy-cart-partial-deposite-main" id="rzvy-partial-deposit-info"><i class="fa fa-fw fa-money me-2" aria-hidden="true"></i><?php if(isset($rzvy_translangArr['partial_deposite_ad'])){ echo $rzvy_translangArr['partial_deposite_ad']; }else{ echo $rzvy_defaultlang['partial_deposite_ad']; } ?><?php echo $currencyB."<span class='rzvy_update_partial_amount'>".$_SESSION['rzvy_cart_partial_deposite']."</span>".$currencyA; ?></div>
													</div>
												</div>
											<?php } ?>
											
											
											<?php 
											/** available discount count end **/												
											$available_coupons = $obj_frontend->get_available_coupons();
											$rzvy_couponcunt = 'N';
											if(mysqli_num_rows($available_coupons)>0){
												$rzvy_couponcunt = 'Y';
											?>
											<h2 class="fs-5 pt-3"><?php if(isset($rzvy_translangArr['select_a_promo_offer'])){ echo $rzvy_translangArr['select_a_promo_offer']; }else{ echo $rzvy_defaultlang['select_a_promo_offer']; } ?></h2>
											<div class="rzvy-table">
												<table class="table">
												  <thead>
													<tr>
													  <th><?php if(isset($rzvy_translangArr['coupon_value'])){ echo $rzvy_translangArr['coupon_value']; }else{ echo $rzvy_defaultlang['coupon_value']; } ?></th>
													  <th align="left"><?php if(isset($rzvy_translangArr['coupon_code'])){ echo $rzvy_translangArr['coupon_code']; }else{ echo $rzvy_defaultlang['coupon_code']; } ?></th>
													  <th align="left"><?php if(isset($rzvy_translangArr['expires'])){ echo $rzvy_translangArr['expires']; }else{ echo $rzvy_defaultlang['expires']; } ?></th>
													</tr>
												  </thead>
												  <tbody>
													<?php 
													while($coupon = mysqli_fetch_array($available_coupons)){ 
														if(isset($_SESSION['customer_id'])){
															$obj_frontend->customer_id = $_SESSION['customer_id'];
															$obj_frontend->coupon_id = $coupon['id'];
															$check_coupon = $obj_frontend->check_available_coupon_of_existing_customer();
															if($check_coupon=="used"){
																continue;
															}
														} ?>
															<tr>
															  <td>
																<div class="form-check">
																  <input class="form-check-input rzvy-coupon-radio" type="radio" id="rzvy-coupon-radio-<?php echo $coupon['id']; ?>" name="rzvy-coupon-radio" value="<?php echo $coupon['id']; ?>" data-promo="<?php echo $coupon['coupon_code']; ?>">
																  <label class="form-check-label" for="rzvy-coupon-radio-<?php echo $coupon['id']; ?>"><?php if($coupon['coupon_type']=="flat"){ ?>
																			<?php if(isset($rzvy_translangArr['flat'])){ echo $rzvy_translangArr['flat']; }else{ echo $rzvy_defaultlang['flat']; }  echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$coupon['coupon_value']).' '; 
																			if(isset($rzvy_translangArr['off_on_your_purchase'])){ echo $rzvy_translangArr['off_on_your_purchase']; }else{ echo $rzvy_defaultlang['off_on_your_purchase']; }
																			 }else{  echo $coupon['coupon_value']; ?>% <?php if(isset($rzvy_translangArr['off_on_your_purchase'])){ echo $rzvy_translangArr['off_on_your_purchase']; }else{ echo $rzvy_defaultlang['off_on_your_purchase']; }  
																			 } ?> </label>
																</div>
															  </td>
															  <td align="left"><?php echo $coupon['coupon_code']; ?></td>
															  <td align="left"><?php echo date($rzvy_date_format, strtotime($coupon['coupon_expiry'])); ?></td>
															</tr>
													<?php } ?>													
												  </tbody>
												</table>
												<div class="mt-3 mb-1 rzvy_applied_coupon_div valid-feedback">
													<i class="fa fa-ticket"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['applied_discount_coupon'])){ echo $rzvy_translangArr['applied_discount_coupon']; }else{ echo $rzvy_defaultlang['applied_discount_coupon']; } ?><span class="fa-border rzvy_applied_coupon_badge"></span><a href="javascript:void(0)" class="rzvy_remove_applied_coupon" data-id=""><i class="fa fa-times-circle-o fa-lg"></i></a>
												</div>												
											</div>	
											<?php } ?>
											
											<?php if($obj_settings->get_option("rzvy_coupon_input_status") == "Y"){ ?>							
											<div class="form-group mb-2 mt-5">
												<label class="form-label" for="rzvy_coupon_code"><?php if(isset($rzvy_translangArr['do_you_have_discount_coupon'])){ echo $rzvy_translangArr['do_you_have_discount_coupon']; }else{ echo $rzvy_defaultlang['do_you_have_discount_coupon']; } ?></label>
												<div class="form-input apply-code">
												  <input type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_coupon_code'])){ echo $rzvy_translangArr['enter_coupon_code']; }else{ echo $rzvy_defaultlang['enter_coupon_code']; } ?>" name="rzvy_coupon_code" class="form-control text-uppercase" id="rzvy_coupon_code"  value="">
												  <button type="submit" value="" class="btn btn-success" id="rzvy_apply_coupon_code_btn"><?php if(isset($rzvy_translangArr['apply'])){ echo $rzvy_translangArr['apply']; }else{ echo $rzvy_defaultlang['apply']; } ?></button>		  
												</div>
												
												<div id="rzvy-coupon-empty-error" class="d-none error"><?php if(isset($rzvy_translangArr['please_enter_coupon_code'])){ echo $rzvy_translangArr['please_enter_coupon_code']; }else{ echo $rzvy_defaultlang['please_enter_coupon_code']; } ?></div>
												<?php if ($rzvy_couponcunt=='N'){ ?>
												<div class="mt-3 mb-1 rzvy_applied_coupon_div valid-feedback">
													<i class="fa fa-ticket"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['applied_discount_coupon'])){ echo $rzvy_translangArr['applied_discount_coupon']; }else{ echo $rzvy_defaultlang['applied_discount_coupon']; } ?><span class="fa-border rzvy_applied_coupon_badge"></span><a href="javascript:void(0)" class="rzvy_remove_applied_coupon" data-id=""><i class="fa fa-times-circle-o fa-lg"></i></a>
												</div>
												<?php } ?>
											</div>
											<?php } ?> 
											
											<?php if($rzvy_show_existing_new_user_checkout == "Y"){ ?>
											<div class="mt-3" id="rzvy-customer-referral-coupons-container">
											<?php 
											/** Referral discount coupon list start **/
											if($obj_settings->get_option("rzvy_referral_discount_status") == "Y"){ 
												if(isset($_SESSION['customer_id'])){
													$available_rcoupons = $obj_frontend->get_all_referral_discount($_SESSION["customer_id"]);
													if(mysqli_num_rows($available_rcoupons)>0){  ?>
														<h2 class="fs-5 pt-3"><?php if(isset($rzvy_translangArr['select_a_referral_discount_coupon'])){ echo $rzvy_translangArr['select_a_referral_discount_coupon']; }else{ echo $rzvy_defaultlang['select_a_referral_discount_coupon']; } ?></h2>
														<div class="rzvy-table">
															<table class="table">
															  <thead>
																<tr>
																  <th><?php if(isset($rzvy_translangArr['coupon_value'])){ echo $rzvy_translangArr['coupon_value']; }else{ echo $rzvy_defaultlang['coupon_value']; } ?></th>
																  <th align="left"><?php if(isset($rzvy_translangArr['coupon_code'])){ echo $rzvy_translangArr['coupon_code']; }else{ echo $rzvy_defaultlang['coupon_code']; } ?></th>																  
																</tr>
															  </thead>
															  <tbody>
																<?php 
																	while($coupon = mysqli_fetch_array($available_rcoupons)){ 
																	?><tr>
																		  <td>
																			<div class="form-check">
																			  <input class="form-check-input rzvy-rcoupon-radio" type="radio" id="rzvy-rcoupon-radio-<?php echo $coupon['id']; ?>" name="rzvy-rcoupon-radio" value="<?php echo $coupon['id']; ?>" data-promo="<?php echo $coupon['coupon']; ?>">
																			  <label class="form-check-label" for="rzvy-rcoupon-radio-<?php echo $coupon['id']; ?>"><?php if($coupon['discount_type']=="flat"){ ?>
																						<?php if(isset($rzvy_translangArr['flat'])){ echo $rzvy_translangArr['flat']; }else{ echo $rzvy_defaultlang['flat']; }  echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$coupon['discount']); 
																						if(isset($rzvy_translangArr['off_on_your_purchase'])){ echo $rzvy_translangArr['off_on_your_purchase']; }else{ echo $rzvy_defaultlang['off_on_your_purchase']; }
																						 }else{  echo $coupon['discount']; ?>% <?php if(isset($rzvy_translangArr['off_on_your_purchase'])){ echo $rzvy_translangArr['off_on_your_purchase']; }else{ echo $rzvy_defaultlang['off_on_your_purchase']; }  
																						 } ?></label>
																			</div>
																		  </td>
																		  <td align="left"><?php echo $coupon['coupon']; ?></td>				  
																		</tr>																		
																<?php } ?>
															  </tbody>
															</table>
															<?php if($obj_settings->get_option("rzvy_referral_discount_status") == "Y"){ ?>
															<div class="mt-3 mb-1 valid-feedback rzvy_applied_referral_coupon_div_text">
																<span><i class="fa fa-gift"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['applied_referral_discount_coupon'])){ echo $rzvy_translangArr['applied_referral_discount_coupon']; }else{ echo $rzvy_defaultlang['applied_referral_discount_coupon']; } ?>: <span class="fa-border rzvy_applied_referral_coupon_code"></span><a href="javascript:void(0)" class="rzvy_remove_applied_rcoupon" data-id=""><i class="fa fa-times-circle-o fa-lg"></i></a></span>
															</div>
															<?php } ?>												
														</div><?php  	
														} 
													}
												} /** Referral discount coupon list end **/ ?>
											</div>
																															
											<?php if($obj_settings->get_option("rzvy_referral_discount_status") == "Y"){ ?>							
											<div class="form-group mb-2 mt-5 rzvy_referral_code_divf">
												<label class="form-label" for="rzvy_referral_code"><?php if(isset($rzvy_translangArr['do_you_have_referral_code'])){ echo $rzvy_translangArr['do_you_have_referral_code']; }else{ echo $rzvy_defaultlang['do_you_have_referral_code']; } ?></label>
												<div class="form-input apply-code">
												  <input type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_your_referral_code'])){ echo $rzvy_translangArr['enter_your_referral_code']; }else{ echo $rzvy_defaultlang['enter_your_referral_code']; } ?>" name="rzvy_referral_code" class="form-control text-uppercase" id="rzvy_referral_code" minlength="8" maxlength="8" value="<?php if(isset($_SESSION["referralcode_applied"]) && $_SESSION["referralcode_applied"] =="Y"){ echo $urlrefcode; } ?>">
												  <button type="submit" value="" class="btn btn-success" id="rzvy_apply_referral_code_btn"><?php if(isset($rzvy_translangArr['apply'])){ echo $rzvy_translangArr['apply']; }else{ echo $rzvy_defaultlang['apply']; } ?></button>		  
												</div>
												
												<div id="rzvy-referral-empty-error" class="d-none error"><?php if(isset($rzvy_translangArr['please_enter_referral_code'])){ echo $rzvy_translangArr['please_enter_referral_code']; }else{ echo $rzvy_defaultlang['please_enter_referral_code']; } ?></div>
												<div class="rzvy_referral_code_applied_div <?php echo $inputAlignment; ?>" <?php if(isset($_SESSION["referralcode_applied"]) && $_SESSION["referralcode_applied"] =="Y" && $urlrefcode != ""){ echo "style='display:block;'"; } ?>>
													<span class="valid-feedback d-block <?php echo $inputAlignment; ?>" <?php if(isset($_SESSION["referralcode_applied"]) && $_SESSION["referralcode_applied"] =="Y" && $urlrefcode != ""){ echo "style='display:block;'"; } ?>><?php if(isset($rzvy_translangArr['applied_referral_code'])){ echo $rzvy_translangArr['applied_referral_code']; }else{ echo $rzvy_defaultlang['applied_referral_code']; } ?>: <b class="rzvy_referral_code_applied_text"><?php if(isset($_SESSION["referralcode_applied"]) && $_SESSION["referralcode_applied"] =="Y"){ echo $urlrefcode; } ?></b></span>
												</div>
												<span class="referralcode_applied_msg error"><?php if(isset($_SESSION["referralcode_applied"]) && ($_SESSION["referralcode_applied"] =="O" || $_SESSION["referralcode_applied"] =="F")){
															if($_SESSION["referralcode_applied"] =="O"){
																if(isset($rzvy_translangArr['you_cannot_use_your_own_referral_code'])){ echo $rzvy_translangArr['you_cannot_use_your_own_referral_code']; }else{ echo $rzvy_defaultlang['you_cannot_use_your_own_referral_code']; }
															}
															if($_SESSION["referralcode_applied"] =="F"){
																if(isset($rzvy_translangArr['you_can_apply_referral_code_only_on_first_booking'])){ echo $rzvy_translangArr['you_can_apply_referral_code_only_on_first_booking']; }else{ echo $rzvy_defaultlang['you_can_apply_referral_code_only_on_first_booking']; } 
															}
														} ?>
												</span>
											</div>
											<?php } ?> 
										<?php } ?>	
										</div>
										
										<!-- Payment modes Start --->
										<div class="rzvy_payment_methods_container row step-item <?php echo $labelAlignmentClassName; ?>">
												<?php 
												if(($obj_settings->get_option("rzvy_pay_at_venue_status") == "Y" && $obj_settings->get_option("rzvy_showhide_pay_at_venue") == "Y") || $obj_settings->get_option("rzvy_paypal_payment_status") == "Y" || $obj_settings->get_option("rzvy_stripe_payment_status") == "Y" || $obj_settings->get_option("rzvy_authorizenet_payment_status") == "Y" || $obj_settings->get_option("rzvy_twocheckout_payment_status") == "Y" || $obj_settings->get_option("rzvy_bank_transfer_payment_status") == "Y"){ 
													?>
													<h4 class="pb-2  mt-3 <?php echo $inputAlignment; ?>"><?php if(isset($rzvy_translangArr['payment_method'])){ echo $rzvy_translangArr['payment_method']; }else{ echo $rzvy_defaultlang['payment_method']; } ?> </h5>
													<?php
												}
												$show_hide_payatvenue = "";
												if(
													$obj_settings->get_option("rzvy_stripe_payment_status") != "Y" && 
													$obj_settings->get_option("rzvy_authorizenet_payment_status") != "Y" && 
													$obj_settings->get_option("rzvy_twocheckout_payment_status") != "Y" && 
													$obj_settings->get_option("rzvy_bank_transfer_payment_status") != "Y" && 
													$obj_settings->get_option("rzvy_paypal_payment_status") != "Y" && 
													$obj_settings->get_option("rzvy_pay_at_venue_status") == "Y" && 
													$obj_settings->get_option("rzvy_showhide_pay_at_venue") != "Y"){ 
														$show_hide_payatvenue = "style='display:none;'";
												}
												
												?>
												<div class="custom-controls rzvy-payments" <?php echo $show_hide_payatvenue; ?>>
													<div class="row justify-content-center">
													<?php 
													if($obj_settings->get_option("rzvy_pay_at_venue_status") == "Y"){ 
														?>
														<div class="col-sm-6">
															<div class="form-check custom inline">
															<input type="radio" class="rzvy-payment-method-check form-check-input" id="rzvy-pay-at-venue" name="rzvy-payment-method-radio" value="pay-at-venue" checked />
															<label class="form-check-label" for="rzvy-pay-at-venue"><?php if(isset($rzvy_translangArr['pay_at_venue'])){ echo $rzvy_translangArr['pay_at_venue']; }else{ echo $rzvy_defaultlang['pay_at_venue']; } ?></label>
															</div>
														</div>
														<?php 
													} 
													if($obj_settings->get_option("rzvy_bank_transfer_payment_status") == "Y" && $rzvy_price_display=='Y'){ 
														?>
														<div class="col-sm-6">
															<div class="form-check custom inline">
															<input type="radio" class="rzvy-payment-method-check form-check-input" id="rzvy-bank-transfer" name="rzvy-payment-method-radio" value="bank transfer" <?php if($obj_settings->get_option("rzvy_pay_at_venue_status") != "Y"){ echo "checked"; } ?> data-toggle="rzvy-bank-payment"/>
															<label class="form-check-label" for="rzvy-bank-transfer"><?php if(isset($rzvy_translangArr['bank_transfer'])){ echo $rzvy_translangArr['bank_transfer']; }else{ echo $rzvy_defaultlang['bank_transfer']; } ?></label>
															</div>
														</div><?php 
													} 
													if($obj_settings->get_option("rzvy_paypal_payment_status") == "Y"  && $rzvy_price_display=='Y'){ 
														?>
														<div class="col-sm-6">
															<div class="form-check custom inline">
															<input type="radio" class="rzvy-payment-method-check form-check-input" id="rzvy-paypal-payment" name="rzvy-payment-method-radio" value="paypal" />
															<label class="form-check-label" for="rzvy-paypal-payment"><?php if(isset($rzvy_translangArr['paypal'])){ echo $rzvy_translangArr['paypal']; }else{ echo $rzvy_defaultlang['paypal']; } ?></label>
															</div>
														</div><?php 
													} 
													if($obj_settings->get_option("rzvy_stripe_payment_status") == "Y" && $obj_settings->get_option("rzvy_authorizenet_payment_status") == "N" && $obj_settings->get_option("rzvy_twocheckout_payment_status") == "N"  && $rzvy_price_display=='Y'){ 
														$payment_method = "stripe";
													} else if($obj_settings->get_option("rzvy_stripe_payment_status") == "N" && $obj_settings->get_option("rzvy_authorizenet_payment_status") == "Y" && $obj_settings->get_option("rzvy_twocheckout_payment_status") == "N"  && $rzvy_price_display=='Y'){ 
														$payment_method = "authorize.net";
													}  else if($obj_settings->get_option("rzvy_stripe_payment_status") == "N" && $obj_settings->get_option("rzvy_authorizenet_payment_status") == "N" && $obj_settings->get_option("rzvy_twocheckout_payment_status") == "Y"  && $rzvy_price_display=='Y'){ 
														$payment_method = "2checkout";
													} else{
														$payment_method = "N";
													}
													if($payment_method != "N"){ 
														?>
														<div class="col-sm-6">
															<div class="form-check custom inline">
															<input type="radio" class="rzvy-payment-method-check form-check-input" id="rzvy-card-payment" name="rzvy-payment-method-radio" value="<?php echo $payment_method; ?>" data-toggle="rzvy-card-payment"/>
															<label class="form-check-label" for="rzvy-card-payment"><?php if(isset($rzvy_translangArr['card_payment'])){ echo $rzvy_translangArr['card_payment']; }else{ echo $rzvy_defaultlang['card_payment']; } ?></label>
															</div>
														</div>
														<?php 
													} 
													?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="forms rzvy-radio-group-block <?php echo $inputAlignment; ?>">
									<div data-form="rzvy-bank-payment" class="form-item rzvy-bank-transfer-detail-box remove_payment_according_services_showhide rzvy_hide_show_payment_according_services " <?php if($obj_settings->get_option("rzvy_pay_at_venue_status") == "Y" || $obj_settings->get_option("rzvy_bank_transfer_payment_status") != "Y"){ echo "style='display:none'"; } ?>>
										<h2 class="fs-5"><?php if(isset($rzvy_translangArr['bank_details'])){ echo $rzvy_translangArr['bank_details']; }else{ echo $rzvy_defaultlang['bank_details']; } ?></h2>
										<div class="rzvy-table">
										  <table class="table">
											<tbody>
											  <tr>
												<td><strong><?php if(isset($rzvy_translangArr['bank_name'])){ echo $rzvy_translangArr['bank_name']; }else{ echo $rzvy_defaultlang['bank_name']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_bank_name"); ?></td>
												<td align="left"><strong><?php if(isset($rzvy_translangArr['account_name'])){ echo $rzvy_translangArr['account_name']; }else{ echo $rzvy_defaultlang['account_name']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_account_name"); ?></td>
											  </tr>
											  <tr>
												<td><strong><?php if(isset($rzvy_translangArr['account_number'])){ echo $rzvy_translangArr['account_number']; }else{ echo $rzvy_defaultlang['account_number']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_account_number"); ?></td>
												<td align="left"><strong><?php if(isset($rzvy_translangArr['branch_code'])){ echo $rzvy_translangArr['branch_code']; }else{ echo $rzvy_defaultlang['branch_code']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_branch_code"); ?></td>
											  </tr>
											  <tr>
												<td align="left"><strong><?php if(isset($rzvy_translangArr['ifsc_code'])){ echo $rzvy_translangArr['ifsc_code']; }else{ echo $rzvy_defaultlang['branch_code']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_ifsc_code"); ?></td>
											  </tr>
											</tbody>
										  </table>
										</div>
									</div>
									<div class="form-item card-payment rzvy-card-detail-box remove_payment_according_services_showhide rzvy_hide_show_payment_according_services" data-form="rzvy-card-payment">
										<p><?php if(isset($rzvy_translangArr['credit_card_details'])){ echo $rzvy_translangArr['credit_card_details']; }else{ echo $rzvy_defaultlang['credit_card_details']; } ?></p>
										<?php 
										if($obj_settings->get_option("rzvy_stripe_payment_status") == "Y" && $obj_settings->get_option("rzvy_authorizenet_payment_status") == "N" && $obj_settings->get_option("rzvy_twocheckout_payment_status") == "N"){ 
											?>
											<div class="row">
											  <div class="col-xl-7 col-sm-12">
												<div class="form-group">
													<div id="rzvy_stripe_plan_card_element">
														<!-- A Stripe Element will be inserted here. -->
													</div>
													<!-- Used to display form errors. -->
													<div id="rzvy_stripe_plan_card_errors" class="error mt-2" role="alert"></div>
												</div>
											  </div>
											</div>
											<?php 
										}else{ 
											?>
											<div class="row">
											  <div class="col-xl-7 col-sm-12">
												<div class="form-group">
												  <label class="form-label" for="rzvy-cardnumber"><?php if(isset($rzvy_translangArr['card_number'])){ echo $rzvy_translangArr['card_number']; }else{ echo $rzvy_defaultlang['card_number']; } ?></label>
												 <input maxlength="20" size="20" type="tel" placeholder="<?php if(isset($rzvy_translangArr['card_number'])){ echo $rzvy_translangArr['card_number']; }else{ echo $rzvy_defaultlang['card_number']; } ?>" class="form-control rzvy-input-class rzvy-card-num <?php echo $inputAlignment; ?>" name="rzvy-cardnumber" id="rzvy-cardnumber" value="" />					  
												</div>
											  </div>
											  <div class="col-xl col-sm-6">
												<div class="form-group">
												  <label class="form-label" for="rzvy-cardexmonth"><?php if(isset($rzvy_translangArr['month'])){ echo $rzvy_translangArr['month']; }else{ echo $rzvy_defaultlang['month']; } ?></label>
												  <input maxlength="2" type="tel" placeholder="<?php if(isset($rzvy_translangArr['mm'])){ echo $rzvy_translangArr['mm']; }else{ echo $rzvy_defaultlang['mm']; } ?>" class="form-control  rzvy-input-class <?php echo $inputAlignment; ?>" name="rzvy-cardexmonth" id="rzvy-cardexmonth" value="" />
												</div>
											  </div>
											  <div class="col-xl col-sm-6">
												<div class="form-group">
												  <label class="form-label" for="rzvy-cardexyear"><?php if(isset($rzvy_translangArr['year'])){ echo $rzvy_translangArr['year']; }else{ echo $rzvy_defaultlang['year']; } ?></label>
												  <input maxlength="4" type="tel" placeholder="<?php if(isset($rzvy_translangArr['yyyy'])){ echo $rzvy_translangArr['yyyy']; }else{ echo $rzvy_defaultlang['yyyy']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>" name="rzvy-cardexyear" id="rzvy-cardexyear" value="" />
												</div>
											  </div>
											  <div class="col-xl-7 col-sm-12">
												<div class="form-group">
												  <label class="form-label" for="rzvy-cardholdername"><?php if(isset($rzvy_translangArr['name_as_on_card'])){ echo $rzvy_translangArr['name_as_on_card']; }else{ echo $rzvy_defaultlang['name_as_on_card']; } ?></label>
												  <input type="text" placeholder="<?php if(isset($rzvy_translangArr['name_as_on_card'])){ echo $rzvy_translangArr['name_as_on_card']; }else{ echo $rzvy_defaultlang['name_as_on_card']; } ?>" class="form-control  rzvy-input-class <?php echo $inputAlignment; ?>" name="rzvy-cardholdername" id="rzvy-cardholdername" value="" />
												</div>
											  </div>
											  <div class="col-xl col-sm-6">
												<div class="form-group">
												  <label class="form-label" for="rzvy-cardcvv"><?php if(isset($rzvy_translangArr['cvv'])){ echo $rzvy_translangArr['cvv']; }else{ echo $rzvy_defaultlang['cvv']; } ?></label>
												  <input type="password" maxlength="4" size="4" placeholder="<?php if(isset($rzvy_translangArr['cvv'])){ echo $rzvy_translangArr['cvv']; }else{ echo $rzvy_defaultlang['cvv']; } ?>" class="form-control  rzvy-input-class <?php echo $inputAlignment; ?>"  name="rzvy-cardcvv" id="rzvy-cardcvv" value="" />
												</div>
											  </div>
											</div>
										<?php 
										} 
										?>
									</div>
									
									<div class="form-check mt-5">
										<div class="rzvy-terms-and-condition <?php echo $labelAlignmentClassName; ?>">
										
										
											
											<label class="form-check-label" for="rzvy-tc-concent"><input class="form-check-input custom-control-input rzvy-tc-control-input" type="checkbox" name="read-and-agree" id="rzvy-tc-concent"><?php if(isset($rzvy_translangArr['i_read_and_agree_to_the'])){ echo $rzvy_translangArr['i_read_and_agree_to_the']; }else{ echo $rzvy_defaultlang['i_read_and_agree_to_the']; } ?> <a target="_blank" href="<?php $rzvy_terms_and_condition_link = $obj_settings->get_option("rzvy_terms_and_condition_link"); if($rzvy_terms_and_condition_link != ""){ echo $rzvy_terms_and_condition_link; }else{ echo "javascript:void(0)"; } ?>"><?php if(isset($rzvy_translangArr['terms_conditions'])){ echo $rzvy_translangArr['terms_conditions']; }else{ echo $rzvy_defaultlang['terms_conditions']; } ?></a><?php $rzvy_cookiesconcent_status = $obj_settings->get_option("rzvy_cookiesconcent_status"); if($rzvy_cookiesconcent_status == "Y"){ ?> <?php echo (isset($rzvy_translangArr['and']))?$rzvy_translangArr['and']:$rzvy_defaultlang['and']; ?> <a target="_blank" href="<?php $rzvy_privacy_and_policy_link = $obj_settings->get_option("rzvy_privacy_and_policy_link"); if($rzvy_privacy_and_policy_link != ""){ echo $rzvy_privacy_and_policy_link; }else{ echo "javascript:void(0)"; } ?>"><?php if(isset($rzvy_translangArr['privacy_and_policy'])){ echo $rzvy_translangArr['privacy_and_policy']; }else{ echo $rzvy_defaultlang['privacy_and_policy']; } ?></a><?php } ?></label>											
										</div>
									</div>									
									<button id="rzvy_book_appointment_btn" class="btn w-100 btn-success mt-md-5 mt-4" type="submit"><span class="fa fa-calendar-check-o"></span>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['book_now'])){ echo $rzvy_translangArr['book_now']; }else{ echo $rzvy_defaultlang['book_now']; } ?></button>									
								</div>
							</div>
						</div>
						<div class="col-lg-4 rzvy-sidebar rzvy_right_side_container">	
							<div class="rzvy-rightbar-heightcal">
							<?php 
							$rzvy_feedbackform = 'N';
							if($obj_settings->get_option("rzvy_show_frontend_rightside_feedback_form") == "Y"){
								$rzvy_feedbackform = 'Y';
							}
							$rzvy_feedbacks = 'N';
							if($obj_settings->get_option("rzvy_show_frontend_rightside_feedback_list") == "Y"){
								$all_feedbacks = $obj_frontend->get_all_feedbacks();
								$total_feedbacks = mysqli_num_rows($all_feedbacks);
								if($total_feedbacks>0){ 
									$rzvy_feedbacks = 'Y';
								}
							}
						
							if($rzvy_feedbackform == "Y"){ 
								?>
								<div class="rzvy-review <?php echo $inputAlignment; ?>">
									<div class="review-modal">
										<h2><?php if(isset($rzvy_translangArr['give_us_feedback'])){ echo $rzvy_translangArr['give_us_feedback']; }else{ echo $rzvy_defaultlang['give_us_feedback']; } ?></h2>
										<form method="post" name="rzvy_feedback_form" id="rzvy_feedback_form">
											<input type="hidden" id="rzvy_fb_rating" name="rzvy_fb_rating" value="0" />
												<div class="form-group">
													<label class="form-label" for="name"><?php if(isset($rzvy_translangArr['name'])){ echo $rzvy_translangArr['name']; }else{ echo $rzvy_defaultlang['name']; } ?></label>
													<input type="text" placeholder="<?php if(isset($rzvy_translangArr['your_name'])){ echo $rzvy_translangArr['your_name']; }else{ echo $rzvy_defaultlang['your_name']; } ?>" id="rzvy_fb_name" name="rzvy_fb_name" class="form-control <?php echo $inputAlignment; ?>">
												</div>
												<div class="form-group">
													<label class="form-label" for="name"><?php if(isset($rzvy_translangArr['email'])){ echo $rzvy_translangArr['email']; }else{ echo $rzvy_defaultlang['email']; } ?></label>
													<input type="email" placeholder="<?php if(isset($rzvy_translangArr['email_address'])){ echo $rzvy_translangArr['email_address']; }else{ echo $rzvy_defaultlang['email_address']; } ?>" id="rzvy_fb_email" name="rzvy_fb_email" class="form-control <?php echo $inputAlignment; ?>">
												</div>
												<div class="form-group">
													<label class="form-label" for="name"><?php if(isset($rzvy_translangArr['your_review'])){ echo $rzvy_translangArr['your_review']; }else{ echo $rzvy_defaultlang['your_review']; } ?></label>
													<textarea placeholder="<?php if(isset($rzvy_translangArr['your_review'])){ echo $rzvy_translangArr['your_review']; }else{ echo $rzvy_defaultlang['your_review']; } ?>" id="rzvy_fb_review" name="rzvy_fb_review" class="form-control <?php echo $inputAlignment; ?>"></textarea>
												</div>
												<div class="form-group">
													<div class="row"><label class="form-label" for="name"><?php if(isset($rzvy_translangArr['rating'])){ echo $rzvy_translangArr['rating']; }else{ echo $rzvy_defaultlang['rating']; } ?></label></div>
													<span class="fa fa-star-o rzvy-sidebar-feedback-star" id="rzvy-sidebar-feedback-star1" onclick="rzvy_add_star_rating(this,1)"></span>
													<span class="fa fa-star-o rzvy-sidebar-feedback-star" id="rzvy-sidebar-feedback-star2" onclick="rzvy_add_star_rating(this,2)"></span>
													<span class="fa fa-star-o rzvy-sidebar-feedback-star" id="rzvy-sidebar-feedback-star3" onclick="rzvy_add_star_rating(this,3)"></span>
													<span class="fa fa-star-o rzvy-sidebar-feedback-star" id="rzvy-sidebar-feedback-star4" onclick="rzvy_add_star_rating(this,4)"></span>
													<span class="fa fa-star-o rzvy-sidebar-feedback-star" id="rzvy-sidebar-feedback-star5" onclick="rzvy_add_star_rating(this,5)"></span>
												</div>
												<button class="btn btn-success w-100" id="rzvy_submit_feedback_btn" type="submit"><i class="fa fa-thumbs-up"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['submit_review'])){ echo $rzvy_translangArr['submit_review']; }else{ echo $rzvy_defaultlang['submit_review']; } ?></button>
										</form>
								</div>
							</div>
								<?php 
							}
							
							if($rzvy_feedbacks=='Y'){ 
									?>
									<div class="rzvy-feedbacks testimonials <?php echo $inputAlignment; ?>">
										<?php if(isset($rzvy_translangArr['our_happy_customers'])){ 
											echo '<h5 class="step-title">'.$rzvy_translangArr['our_happy_customers'].'</h5>';
										} ?>
										<div class="owl-carousel rzvy-sidebar-block-content" data-items="1" data-items-lg="1" data-items-md="1" data-items-sm="1" data-items-ssm="1" data-margin="24" data-dots="true" data-loop="true" data-autoplay="true" data-autoplay-timeout="5000">
											<?php 
											$fb_i=1;
											while($feedback = mysqli_fetch_array($all_feedbacks)){ ?>
												<div class="item">												 
												  <div class="testimonial-item">
													<div class="author-wrap">
													  <img src="<?php echo SITE_URL;?>includes/images/author.png" alt="<?php echo ucwords($feedback['name']); ?>">
													  <div>
														<h3><?php echo ucwords($feedback['name']); ?></h3>
														<div class="rating">
														  <?php 
															if($feedback['rating']>0){
																for($star_i=0;$star_i<$feedback['rating'];$star_i++){ 
																	?>
																	<i class="fa fa-star" aria-hidden="true"></i>
																	<?php 
																} 
																for($star_j=0;$star_j<(5-$feedback['rating']);$star_j++){ 
																	?>
																	<i class="fa fa-star-o" aria-hidden="true"></i>
																	<?php 
																} 
															}else{ 
																?>
																<i class="fa fa-star-o" aria-hidden="true"></i>
																<i class="fa fa-star-o" aria-hidden="true"></i>
																<i class="fa fa-star-o" aria-hidden="true"></i>
																<i class="fa fa-star-o" aria-hidden="true"></i>
																<i class="fa fa-star-o" aria-hidden="true"></i>
																<?php 
															} 
															?>
														</div>
													  </div>
													</div>
													<p><?php echo ucfirst($feedback['review']); ?></p>
												  </div>
												</div>
											<?php 
											} 
											?>
										</div>
									</div>
								<?php  }  ?>
								</div>
								
								<div class="rzvy_sticky_bottom_booking_summary whitebox rzvy-sticky <?php if($rzvy_feedbackform=='Y' || $rzvy_feedbacks=='Y'){ echo 'vsa_hide_show_booking_summary'; } ?>">
									<h4 class="widget-title rzvy-sidebar-block-title <?php echo $inputAlignment; ?>"><?php if(isset($rzvy_translangArr['booking_summary'])){ echo $rzvy_translangArr['booking_summary']; }else{ echo $rzvy_defaultlang['booking_summary']; } ?></h4>
									<div id="rzvy_refresh_cart" class="rzvy-sidebar-block-content rzvy-table">
										<label><?php if(isset($rzvy_translangArr['no_items_in_cart'])){ echo $rzvy_translangArr['no_items_in_cart']; }else{ echo $rzvy_defaultlang['no_items_in_cart']; } ?></label>
									</div>
								</div>
						</div>
					</div>
				</div>
			</main>
			<!-- Available Coupon Offers START -->
			<div class="modal" id="rzvy-available-coupons-modal">
				<div class="modal-dialog">
					<div class="modal-content">
						<!-- Modal Header -->
						<div class="modal-header <?php echo $alignmentClass; ?>">
							<h4 class="modal-title"><?php if(isset($rzvy_translangArr['select_a_promo_offer'])){ echo $rzvy_translangArr['select_a_promo_offer']; }else{ echo $rzvy_defaultlang['select_a_promo_offer']; } ?></h4>
						</div>
						<!-- Modal body -->
						<div class="modal-body rzvy_avail_promo_modal_body <?php echo $alignmentClass; ?>">
							
						</div>
						<!-- Modal footer -->
						<div class="modal-footer">
							
						</div>
					</div>
				</div>
			</div>
			<!-- Available Coupon Offers END -->		
			
			<!-- Location Selector Modal START -->
			<div class="modal location-modal fade " id="rzvy-location-selector-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
			  <div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">				  
				  <div class="modal-body text-center p-sm-5 p-4">
					<h2 class="pb-4"><?php if(isset($rzvy_translangArr['check_for_services_available_at_your_location'])){ echo $rzvy_translangArr['check_for_services_available_at_your_location']; }else{ echo $rzvy_defaultlang['check_for_services_available_at_your_location']; } ?></h2>
					<form>
					  <div class="form-group">
						<?php if(isset($exploded_rzvy_location_selector) && sizeof($exploded_rzvy_location_selector)>0){ ?>
							<select data-live-search="true" class="form-control selectpicker" id="rzvy_ls_input_keyword">
							<option value="" selected disabled><?php if(isset($rzvy_translangArr['enter_zip'])){ echo $rzvy_translangArr['enter_zip']; }else{ echo $rzvy_defaultlang['enter_zip']; } ?></option>
							<?php foreach($exploded_rzvy_location_selector as $exploded_rzvy_location_selectors){ ?> 
							<option value="<?php echo $exploded_rzvy_location_selectors; ?>"><?php echo $exploded_rzvy_location_selectors; ?></option>
							<?php } ?>
							</select>
						<?php }else{ 
								if(isset($rzvy_translangArr['zip_code_configure_err'])){ 
									echo $rzvy_translangArr['zip_code_configure_err']; 
								}else{ 
									echo $rzvy_defaultlang['zip_code_configure_err']; 
								}
							} ?>
						<?php if(isset($exploded_rzvy_location_selector) && sizeof($exploded_rzvy_location_selector)>0){ ?>
							<button id="rzvy_location_check_btn" class="btn btn-success" type="button"><i class="fa fa-map-marker"></i></button>
						<?php } ?>	
					  </div>
					</form>
				  </div>
				</div>
			  </div>
			</div>
		<!-- Location Selector Modal END -->
		<?php if($obj_settings->get_option('rzvy_stripe_payment_status') == 'Y'){ ?>
			<script src="https://js.stripe.com/v3/"></script>
		<?php } ?>
		<?php $Rzvy_Hooks->onepageFooterIncludes(); ?>
		<script src="<?php echo SITE_URL; ?>includes/front/js/rzvy-front-jquery.js?<?php echo time(); ?>"></script>
		<script src="<?php echo SITE_URL; ?>includes/js/rzvy-set-languages.js?<?php echo time(); ?>"></script>
	</body>
</html>