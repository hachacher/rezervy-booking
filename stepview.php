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
		<link href="<?php echo SITE_URL; ?>includes/stepview/rzvy-stepview.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" media="all"/>
		<link href="<?php echo SITE_URL; ?>includes/css/rzvy-thankyou.css" rel="stylesheet" type="text/css" media="all">
		
		
		<!-- Bootstrap core JavaScript and Page level plugin JavaScript-->
		<script type="text/javascript" src="<?php echo SITE_URL; ?>includes/front/js/jquery-3.2.1.min.js?<?php echo time(); ?>"></script>
		<script type="text/javascript" src="<?php echo SITE_URL; ?>includes/front/js/popper.min.js?<?php echo time(); ?>"></script>
		<script type="text/javascript" src="<?php echo SITE_URL; ?>includes/front/js/bootstrap.min.js?<?php echo time(); ?>"></script>
		<script type="text/javascript" src="<?php echo SITE_URL; ?>includes/vendor/bootstrap/js/bootstrap-select.min.js?<?php echo time(); ?>"></script>
		<script type="text/javascript" src="<?php echo SITE_URL; ?>includes/front/js/slick.min.js?<?php echo time(); ?>"></script>
	
		<script src="<?php echo SITE_URL; ?>includes/js/bootstrap.bundle.min.js"></script>
		<script src="<?php echo SITE_URL; ?>includes/js/owl.carousel.min.js"></script>
		<script src="<?php echo SITE_URL; ?>includes/js/rzvy-common-front-stepview.js?<?php echo time(); ?>"></script>
		<?php 
		$rzvy_birthdate_with_year = $obj_settings->get_option('rzvy_birthdate_with_year');
		if($rzvy_birthdate_with_year == "Y"){ ?>
			<script src="<?php echo SITE_URL; ?>includes/js/jquery.datetimepicker.full.min.js"></script>
			<script>		
			$(document).bind("ready ajaxComplete", function(){
				$('.datepicker').datetimepicker ({
					 timepicker: false,
					 format: "d/m/Y"
				});
				$.datetimepicker.setLocale('<?php echo $selectedlangcode; ?>');
			});
			</script>
		<?php }else{ ?>
			<script src="<?php echo SITE_URL; ?>includes/js/jquery.datetimepicker.full.min.js"></script>
			<script>
			$(document).bind("ready ajaxComplete", function(){
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
		
		<script type="text/javascript" src="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.js?<?php echo time(); ?>"></script>
		<script type="text/javascript" src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.validate.min.js?<?php echo time(); ?>"></script>
		
		<?php if(file_exists(dirname(__FILE__)."/includes/vendor/rzvyconcent/config.php")) { include(dirname(__FILE__)."/includes/vendor/rzvyconcent/config.php"); } ?>
		
		<?php 
		include(dirname(__FILE__)."/includes/lib/rzvy_lang_objects.php");
		if($obj_settings->get_option("rzvy_authorizenet_payment_status") == "Y" || $obj_settings->get_option("rzvy_twocheckout_payment_status") == "Y"){ ?>
			<script type="text/javascript" src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.payment.min.js?<?php echo time(); ?>"></script>
		<?php } ?>
		<?php if($obj_settings->get_option('rzvy_twocheckout_payment_status') == 'Y'){ ?>
			<script type="text/javascript" src="https://www.2checkout.com/checkout/api/2co.min.js"></script>	
		<?php } ?>
		<?php 
		$rzvy_ty_link = ""; 
		$rzvy_thankyou_page_url = $obj_settings->get_option('rzvy_thankyou_page_url'); 
		if($rzvy_thankyou_page_url != ""){
			$rzvy_ty_link = $rzvy_thankyou_page_url.$saiframe;
		}
		?>
		<!-- Custom scripts -->
		<script type="text/javascript">
			var generalObj = { 'site_url' : '<?php echo SITE_URL; ?>', 'ajax_url' : '<?php echo AJAX_URL; ?>', 'ty_link' : '<?php echo $rzvy_ty_link; ?>', 'twocheckout_status' : '<?php echo $obj_settings->get_option('rzvy_twocheckout_payment_status'); ?>', 'authorizenet_status' : '<?php echo $obj_settings->get_option('rzvy_authorizenet_payment_status'); ?>', 'twocheckout_sid' : '<?php echo $obj_settings->get_option('rzvy_twocheckout_seller_id'); ?>', 'twocheckout_pkey' : '<?php echo $obj_settings->get_option('rzvy_twocheckout_publishable_key'); ?>', 'stripe_status' : '<?php echo $obj_settings->get_option('rzvy_stripe_payment_status'); ?>', 'stripe_pkey' : '<?php echo $obj_settings->get_option('rzvy_stripe_publishable_key'); ?>', 'location_selector' : '<?php echo $show_location_selector; ?>', 'minimum_booking_amount':'<?php echo $obj_settings->get_option('rzvy_minimum_booking_amount');?>', 'endslot_status' : '<?php echo $obj_settings->get_option('rzvy_endtimeslot_selection_status'); ?>', 'single_category_status' : '<?php echo $obj_settings->get_option('rzvy_single_category_autotrigger_status'); ?>', 'defaultCountryCode' : '<?php $rzvy_default_country_code = $obj_settings->get_option("rzvy_default_country_code"); if($rzvy_default_country_code != ""){ echo $rzvy_default_country_code; }else{ echo "us"; } ?>', 'single_service_status' : '<?php echo $obj_settings->get_option('rzvy_single_service_autotrigger_status'); ?>', 'booking_first_selection_as' : '<?php if($rzvy_booking_first_selection_as == "allservices"){ echo "service"; }else{ echo "category";} ?>', 'auto_scroll_each_module_status' : '<?php echo $obj_settings->get_option('rzvy_auto_scroll_each_module_status'); ?>', 'single_staff_showhide_status' : '<?php echo $obj_settings->get_option('rzvy_single_staff_showhide_status'); ?>', 'book_with_datetime' : '<?php echo $rzvy_book_with_datetime; ?>', 'rzvy_success_modal_booking' : '<?php echo $rzvy_success_modal_booking; ?>', 'rzvy_customer_calendars' : '<?php echo $rzvy_customer_calendars; ?>','rzvy_todate':'<?php echo date("Y-m-d"); ?>' };
			
			var formfieldsObj = { 'en_ff_phone_status' : '<?php echo $rzvy_en_ff_phone_status; ?>', 'g_ff_phone_status' : '<?php echo $rzvy_g_ff_phone_status; ?>', 'en_ff_firstname' : '<?php echo $rzvy_en_ff_firstname_optional; ?>', 'en_ff_lastname' : '<?php echo $rzvy_en_ff_lastname_optional; ?>', 'en_ff_phone' : '<?php echo $rzvy_en_ff_phone_optional; ?>', 'en_ff_address' : '<?php echo $rzvy_en_ff_address_optional; ?>', 'en_ff_city' : '<?php echo $rzvy_en_ff_city_optional; ?>', 'en_ff_state' : '<?php echo $rzvy_en_ff_state_optional; ?>', 'en_ff_country' : '<?php echo $rzvy_en_ff_country_optional; ?>', 'g_ff_firstname' : '<?php echo $rzvy_g_ff_firstname_optional; ?>', 'g_ff_lastname' : '<?php echo $rzvy_g_ff_lastname_optional; ?>', 'g_ff_phone' : '<?php echo $rzvy_g_ff_phone_optional; ?>', 'g_ff_address' : '<?php echo $rzvy_g_ff_address_optional; ?>', 'g_ff_city' : '<?php echo $rzvy_g_ff_city_optional; ?>', 'g_ff_state' : '<?php echo $rzvy_g_ff_state_optional; ?>', 'g_ff_country' : '<?php echo $rzvy_g_ff_country_optional; ?>',
			'en_ff_dob' : '<?php echo $rzvy_en_ff_dob_optional; ?>',
			'en_ff_notes' : '<?php echo $rzvy_en_ff_notes_optional; ?>',
			'g_ff_dob' : '<?php echo $rzvy_g_ff_dob_optional; ?>',
			'g_ff_notes' : '<?php echo $rzvy_g_ff_notes_optional; ?>',
			'g_ff_email' : '<?php echo $rzvy_g_ff_email_optional; ?>' }; 
		</script>
		<script type="text/javascript" src="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/js/intlTelInput.js?<?php echo time(); ?>"></script>
		<?php $Rzvy_Hooks->stepviewHeaderIncludes(); ?>
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
	<body class="rzvy rzvy-booking-detail-body" onscroll="parent.postMessage(document.body.scrollHeight, '*');" onload="parent.postMessage(document.body.scrollHeight, '*');">
		<?php include(dirname(__FILE__)."/header2.php"); ?>	
		<div class="container my-3 rzvy-wizard">
			<main>
				<div class="container">
					<fieldset>
						<div class="tab-pane step-item show active rzvy-steps" id="rzvy-first-step">
						
							<!-- First box with Category, Service & Addons -->
						</div>						
					</fieldset>
					<fieldset>						
						<div class="tab-pane step-item fade rzvy-steps" id="rzvy-second-step">
							<!-- Second box with frequently discount, staff & calendar -->
						</div>
					</fieldset>
					<fieldset>
						<div class="tab-pane step-item fade rzvy-steps" id="rzvy-third-step">
							<!-- Booking Summary will goes here -->
						</div>
					</fieldset>
					<fieldset>						
						<div class="tab-pane step-item fade rzvy-steps" id="rzvy-fourth-step">
							<!-- Customer Detail will goes here -->
						</div>
					</fieldset>
					<fieldset>						
						<div class="tab-pane step-item fade rzvy-steps" id="rzvy-fifth-step">
							<center class="mx-lg-0 mx-4">
							<!-- Thank you page content start -->
							<div class="jumbotron text-xs-center col-md-8 whitebox">
							  <i class="fa fa-calendar-check-o fa-5x" aria-hidden="true"></i>
							  <h1 class="fs-3 pb-sm-4 display-3 mt-2"><?php if(isset($rzvy_translangArr['thank_you'])){ echo $rzvy_translangArr['thank_you']; }else{ echo $rzvy_defaultlang['thank_you']; } ?></h1>
							  <h2 class=" fs-3 pb-sm-4 py-2"><i class="fa fa-check-square-o" aria-hidden="true"></i> <?php if(isset($rzvy_translangArr['your_appointment_has_been_booked'])){ echo $rzvy_translangArr['your_appointment_has_been_booked']; }else{ echo $rzvy_defaultlang['your_appointment_has_been_booked']; } ?></h4>
								<?php 
								if(isset($_GET['cals']) && $_GET['cals']!=''){
									echo html_entity_decode(base64_decode($_GET['cals']));
								}				
								?>
								<p class="py-2"><?php if(isset($rzvy_translangArr['if_you_have_any_questions_about_this_appointment_please_generate_ticket_related_your_issue_from'])){ echo $rzvy_translangArr['if_you_have_any_questions_about_this_appointment_please_generate_ticket_related_your_issue_from']; }else{ echo $rzvy_defaultlang['if_you_have_any_questions_about_this_appointment_please_generate_ticket_related_your_issue_from']; } ?> <a href="<?php echo SITE_URL; ?>backend/c-support-tickets.php<?php echo $saiframe; ?>"><?php if(isset($rzvy_translangArr['support_tickets'])){ echo $rzvy_translangArr['support_tickets']; }else{ echo $rzvy_defaultlang['support_tickets']; } ?></a></p>
								<p class="py-2"><?php if(isset($rzvy_translangArr['to_check_your_booking_or_to_make_a_cancellation_visit'])){ echo $rzvy_translangArr['to_check_your_booking_or_to_make_a_cancellation_visit']; }else{ echo $rzvy_defaultlang['to_check_your_booking_or_to_make_a_cancellation_visit']; } ?> <a href="<?php echo SITE_URL; ?>backend/my-appointments.php<?php echo $saiframe; ?>"><?php if(isset($rzvy_translangArr['my_appointments'])){ echo $rzvy_translangArr['my_appointments']; }else{ echo $rzvy_defaultlang['my_appointments']; } ?></a></p>
								<p class="py-2"><?php if(isset($rzvy_translangArr['to_book_more_appointment'])){ echo $rzvy_translangArr['to_book_more_appointment']; }else{ echo $rzvy_defaultlang['to_book_more_appointment']; } ?> <a href="<?php echo SITE_URL.$saiframe; ?>"><?php if(isset($rzvy_translangArr['continue_booking'])){ echo $rzvy_translangArr['continue_booking']; }else{ echo $rzvy_defaultlang['continue_booking']; } ?></a></p>
							</div>
							<!-- Thank you page content end -->
						</center>
						</div>
					</fieldset>
					<div class="step-item testimonials">
						<?php 
						$all_feedbacks = $obj_frontend->get_all_feedbacks();
						$total_feedbacks = mysqli_num_rows($all_feedbacks);
						$rzvy_show_frontend_rightside_feedback_list = $obj_settings->get_option("rzvy_show_frontend_rightside_feedback_list");
						if($rzvy_show_frontend_rightside_feedback_list == "Y"){
							if($total_feedbacks>0){ 
								?>
									<h4 class="mb-4 <?php echo $labelAlignmentClassName; ?>"><?php if(isset($rzvy_translangArr['our_happy_customers'])){ echo $rzvy_translangArr['our_happy_customers']; }else{ echo $rzvy_defaultlang['our_happy_customers']; } ?></h4>
									<div class="rzvy-sidebar-block-content owl-carousel" data-items="4" data-items-lg="4" data-items-md="2" data-items-sm="1" data-items-ssm="1" data-margin="24" data-nav="true" data-dots="true" data-loop="true" data-autoplay="true" data-autoplay-timeout="5000">
										<?php 
										while($feedback = mysqli_fetch_array($all_feedbacks)){ 	?>
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
										<?php } ?>
								</div>
								<?php 
							}  
						}  
						?>
					</div>
				</div>
			</main>					
		</div>	
		<?php 
		if($obj_settings->get_option("rzvy_show_frontend_rightside_feedback_form") == "Y"){ ?>
		<div class="rzvy-review <?php echo $inputAlignment; ?>">
			<a href="javascript:void(0);" class="review-link"><img src="<?php echo SITE_URL; ?>includes/images/review.svg" alt="Review" width="25"></a>
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
<?php 	}  ?>
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
		
		<!-- Custom Scripts -->
		<?php if($obj_settings->get_option('rzvy_stripe_payment_status') == 'Y'){ ?>
			<script src="https://js.stripe.com/v3/"></script>
		<?php } ?>
		<?php $Rzvy_Hooks->stepviewFooterIncludes(); ?>
		<script type="text/javascript" src="<?php echo SITE_URL; ?>includes/js/rzvy-set-languages.js?<?php echo time(); ?>"></script>
		<script type="text/javascript" src="<?php echo SITE_URL; ?>includes/stepview/rzvy-stepview.js?<?php echo time(); ?>"></script>
	</body>
</html>