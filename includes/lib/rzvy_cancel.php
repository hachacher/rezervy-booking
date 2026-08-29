<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_bookings.php");

$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

$obj_bookings = new rzvy_bookings();
$obj_bookings->conn = $conn;

$order_idundecoded = $_GET['bid'];
$order_id = base64_decode(substr($order_idundecoded,4));

$orderid_missing_nsg = 'N';
$rzvy_successmsg = 'N';
$rzvy_errormsg = 'N';

if(!isset($order_id) || (isset($order_id) && !is_numeric($order_id))){
	$orderid_missing_nsg = 'Y';
    
}

if($orderid_missing_nsg == 'N'){
	$obj_bookings->order_id = $order_id;
	$appointment_detail = $obj_bookings->get_appointment_status_and_datetime();
	
	$appointment_datetime = $appointment_detail['booking_datetime'];
	$appointment_status = $appointment_detail['booking_status'];
	
	$rzvy_cancellation_buffer_time = $obj_settings->get_option("rzvy_cancellation_buffer_time");
	
	$rzvy_settings_timezone = $obj_settings->get_option("rzvy_timezone");
	$rzvy_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($rzvy_server_timezone,$rzvy_settings_timezone); 
	$current_time = strtotime(date("Y-m-d H:i:s", $currDateTime_withTZ));

	$cancellation_time = strtotime(date("Y-m-d H:i:s", strtotime("+".$rzvy_cancellation_buffer_time." minutes", strtotime($appointment_datetime))));
	$is_cancellation = "Y";
	if($current_time > $cancellation_time){
		$is_cancellation = "N";
	}
    
    if($appointment_status == "rejected_by_you" || $appointment_status == "cancelled_by_customer" || $appointment_status == "rejected_by_staff" || $appointment_status == "completed" || $appointment_status == "mark_as_noshow"){ 
        $rzvy_successmsg = 'Y';
    }

    if($is_cancellation=='N'){
		$rzvy_errormsg = 'Y';
    }
}   ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" type="image/png" href="<?php  echo SITE_URL; ?>includes/images/favicon.ico" />
	<?php 
	$rzvy_seo_ga_code = $obj_settings->get_option('rzvy_seo_ga_code');
	$rzvy_seo_meta_tag = $obj_settings->get_option('rzvy_seo_meta_tag');
	$rzvy_seo_meta_description = $obj_settings->get_option('rzvy_seo_meta_description');
	?>
	<title><?php if($rzvy_seo_meta_tag != ""){ echo $rzvy_seo_meta_tag; }else{ echo $obj_settings->get_option("rzvy_company_name"); } ?></title>
	<?php 
	if($rzvy_seo_meta_description != ''){ 
		?>
		<meta name="description" content="<?php echo $rzvy_seo_meta_description; ?>">
		<?php 
	} 
	?>
	<!-- Bootstrap core CSS-->
	<link href="<?php echo SITE_URL; ?>includes/css/bootstrap.min.css?<?php echo time(); ?>" rel="stylesheet">
	<link href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/bootstrap-select.min.css?<?php echo time(); ?>" rel="stylesheet">
	<!-- Custom fonts for this template-->
	<link href="<?php echo SITE_URL; ?>includes/vendor/font-awesome/css/font-awesome.min.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<!-- Page level plugin CSS-->
	<link href="<?php echo SITE_URL; ?>includes/vendor/datatables/datatables.min.css?<?php echo time(); ?>" rel="stylesheet">
	<!-- Include all css file for calendar -->
	<link href='<?php echo SITE_URL; ?>includes/vendor/calendar/fullcalendar.min.css?<?php echo time(); ?>' rel='stylesheet' />
	<!-- Custom styles for this template-->
	<link href="<?php echo SITE_URL; ?>includes/css/rzvy-customer.css?<?php echo time(); ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/css/intlTelInput.css?<?php echo time(); ?>">
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'c_profile.php') != false) { ?>
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/front/css/datepicker.min.css?<?php echo time(); ?>" />
	<?php } ?>
	
	<link href="<?php echo SITE_URL; ?>includes/css/rzvy-menu.css?<?php echo time(); ?>" rel="stylesheet">
	
	<?php $Rzvy_Hooks->customerHeaderIncludes(); ?>
	<?php include(dirname(dirname(dirname(__FILE__)))."/backend/dashboard_css.php"); ?>
</head>

<body class="rzvy bg-white rzvy_cancel_data_container" id="rzvy-page-top">
	<?php if($orderid_missing_nsg=='Y' || $rzvy_successmsg=='Y' || $rzvy_errormsg=='Y'){ ?>
	<div class="container my-3 rzvy-wizard rzvy_cancel_data_middle">
			<main>
				<div class="container">
					<fieldset>						
						<div class="tab-pane step-item rzvy-steps">
							<center class="mx-lg-0 mx-4">
							<div class="jumbotron text-xs-center col-md-12 whitebox">
							  <?php if($orderid_missing_nsg=='Y'){ ?>	
							  <h2 class=" fs-3 pb-sm-4 py-2 text-danger mx-auto rzvy_cancel_data_msg"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i> <?php if(isset($rzvy_translangArr['booking_id_wrong_or_missing'])){ echo $rzvy_translangArr['booking_id_wrong_or_missing']; }else{ echo $rzvy_defaultlang['booking_id_wrong_or_missing']; }     ?></h4>	<?php } ?>	
							  
							  <?php if($rzvy_errormsg=='Y'){ ?>	
							  <h2 class=" fs-3 pb-sm-4 py-2 text-danger mx-auto rzvy_cancel_data_msg"><i class="fa fa-times-circle text-danger" aria-hidden="true"></i> <?php if(isset($rzvy_translangArr['cancellation_not_allowed_now'])){ echo $rzvy_translangArr['cancellation_not_allowed_now']; }else{ echo $rzvy_defaultlang['cancellation_not_allowed_now']; }     ?></h4>	<?php } ?>
							  
							  <?php if($rzvy_successmsg=='Y'){ ?>	
							  <div class=" fs-3 pb-sm-4 py-2 mx-auto rzvy_cancel_data_msg"><?php echo base64_decode($obj_settings->get_option('rzvy_cancel_success_message'));?></div>	<?php } ?>
							  
							</div>
						</center>
						</div>
					</fieldset>
					<?php 
					echo '</div></main></div></div></html>';
						die();
					} ?>

  <div class="rzvy__main__container">
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
		
		<div class="container-fluid rzvy__main__sub__container pt-3 bg-white">
			<div class="row">
				<div class="col-md-2"></div>
					<div class="col-md-8 p-4 mt-5 border border-light" style="box-shadow: 0 0 10px #d2d2d2;">
					
					<div class="row rzvy-mb-5">
						<div class="col-md-12 text-center">
							<h4><?php echo $obj_settings->get_option('rzvy_company_name'); ?></h4>
						</div>
					</div>
					<hr />
					<div class="row rzvy-mb-5">
						<div class="col-md-3">
							<b><?php if(isset($rzvy_translangArr['cancellation_reason_ad'])){ echo $rzvy_translangArr['cancellation_reason_ad']; }else{ echo $rzvy_defaultlang['cancellation_reason_ad']; } ?></b>
						</div>
						<div class="col-md-9">
							<textarea class="form-control" id="rzvy_appt_reject_reason" name="rzvy_appt_reject_reason"></textarea>
						</div>
					  </div>
					  <div class="row rzvy-mt-20">
						<div class="col-md-12">
							<a href="javascript:void(0)" data-dl="Y"  data-id="<?php echo $order_id; ?>" class="btn btn-lg btn-danger rzvy-fullwidth rzvy_appt_reject_now_btn"><?php if(isset($rzvy_translangArr['cancel_now'])){ echo $rzvy_translangArr['cancel_now']; }else{ echo $rzvy_defaultlang['cancel_now']; } ?></a>
						</div>
					  </div>  
				</div>
				<div class="col-md-2"></div>
			</div>
		</div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    </div>
  </div>
</div>
	<!-- Bootstrap core JavaScript-->
    <script src='<?php echo SITE_URL; ?>includes/vendor/calendar/moment.min.js?<?php echo time(); ?>'></script>
	<script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.min.js?<?php echo time(); ?>"></script>
    <script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.validate.min.js?<?php echo time(); ?>"></script>
	<script src='<?php echo SITE_URL; ?>includes/vendor/calendar/fullcalendar.min.js?<?php echo time(); ?>'></script>
    <script src="<?php echo SITE_URL; ?>includes/vendor/bootstrap/js/bootstrap.bundle.min.js?<?php echo time(); ?>"></script>
    <script src="<?php echo SITE_URL; ?>includes/vendor/bootstrap/js/bootstrap-select.min.js?<?php echo time(); ?>"></script>
    <script src="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.js?<?php echo time(); ?>"></script>
    <!-- Core plugin JavaScript-->
    <script src="<?php echo SITE_URL; ?>includes/vendor/jquery-easing/jquery.easing.min.js?<?php echo time(); ?>"></script>
    <!-- Page level plugin JavaScript-->
    <script src="<?php echo SITE_URL; ?>includes/vendor/datatables/datatables.min.js?<?php echo time(); ?>"></script>
    <!-- Custom scripts for all pages-->
	<script>
		var generalObj = { 'site_url' : '<?php echo SITE_URL; ?>', 'ajax_url' : '<?php echo AJAX_URL; ?>', 'current_date' : '<?php echo date('Y-m-d'); ?>', 'defaultCountryCode' : '<?php $rzvy_default_country_code = $obj_settings->get_option("rzvy_default_country_code"); if($rzvy_default_country_code != ""){ echo $rzvy_default_country_code; }else{ echo "us"; } ?>',
			'please_enter_only_alphabets' : '<?php if(isset($rzvy_translangArr['please_enter_only_alphabets'])){ echo $rzvy_translangArr['please_enter_only_alphabets']; }else{ echo $rzvy_defaultlang['please_enter_only_alphabets']; } ?>',
			'please_enter_only_numerics' : '<?php if(isset($rzvy_translangArr['please_enter_only_numerics'])){ echo $rzvy_translangArr['please_enter_only_numerics']; }else{ echo $rzvy_defaultlang['please_enter_only_numerics']; } ?>',
			'please_enter_valid_phone_number_without_country_code' : '<?php if(isset($rzvy_translangArr['please_enter_valid_phone_number_without_country_code'])){ echo $rzvy_translangArr['please_enter_valid_phone_number_without_country_code']; }else{ echo $rzvy_defaultlang['please_enter_valid_phone_number_without_country_code']; } ?>',
			'please_enter_valid_zip' : '<?php if(isset($rzvy_translangArr['please_enter_valid_zip'])){ echo $rzvy_translangArr['please_enter_valid_zip']; }else{ echo $rzvy_defaultlang['please_enter_valid_zip']; } ?>',
			'opps' : '<?php if(isset($rzvy_translangArr['opps'])){ echo $rzvy_translangArr['opps']; }else{ echo $rzvy_defaultlang['opps']; } ?>',
			'something_went_wrong_please_try_again' : '<?php if(isset($rzvy_translangArr['something_went_wrong_please_try_again'])){ echo $rzvy_translangArr['something_went_wrong_please_try_again']; }else{ echo $rzvy_defaultlang['something_went_wrong_please_try_again']; } ?>',
			'maximum_file_upload_size_1_mb' : '<?php if(isset($rzvy_translangArr['maximum_file_upload_size_1_mb'])){ echo $rzvy_translangArr['maximum_file_upload_size_1_mb']; }else{ echo $rzvy_defaultlang['maximum_file_upload_size_1_mb']; } ?>',
			'please_select_a_valid_image_file' : '<?php if(isset($rzvy_translangArr['please_select_a_valid_image_file'])){ echo $rzvy_translangArr['please_select_a_valid_image_file']; }else{ echo $rzvy_defaultlang['please_select_a_valid_image_file']; } ?>',
			'rescheduled' : '<?php if(isset($rzvy_translangArr['rescheduled'])){ echo $rzvy_translangArr['rescheduled']; }else{ echo $rzvy_defaultlang['rescheduled']; } ?>',
			'appointment_rescheduled_successfully' : '<?php if(isset($rzvy_translangArr['appointment_rescheduled_successfully'])){ echo $rzvy_translangArr['appointment_rescheduled_successfully']; }else{ echo $rzvy_defaultlang['appointment_rescheduled_successfully']; } ?>',
			'cancelled' : '<?php if(isset($rzvy_translangArr['cancelled'])){ echo $rzvy_translangArr['cancelled']; }else{ echo $rzvy_defaultlang['cancelled']; } ?>',
			'appointment_cancelled_successfully' : '<?php if(isset($rzvy_translangArr['appointment_cancelled_successfully'])){ echo $rzvy_translangArr['appointment_cancelled_successfully']; }else{ echo $rzvy_defaultlang['appointment_cancelled_successfully']; } ?>',
			'please_enter_old_password' : '<?php if(isset($rzvy_translangArr['please_enter_old_password'])){ echo $rzvy_translangArr['please_enter_old_password']; }else{ echo $rzvy_defaultlang['please_enter_old_password']; } ?>',
			'please_enter_new_password' : '<?php if(isset($rzvy_translangArr['please_enter_new_password'])){ echo $rzvy_translangArr['please_enter_new_password']; }else{ echo $rzvy_defaultlang['please_enter_new_password']; } ?>',
			'please_enter_retype_new_password' : '<?php if(isset($rzvy_translangArr['please_enter_retype_new_password'])){ echo $rzvy_translangArr['please_enter_retype_new_password']; }else{ echo $rzvy_defaultlang['please_enter_retype_new_password']; } ?>',
			'please_enter_minimum_8_characters' : '<?php if(isset($rzvy_translangArr['please_enter_minimum_8_characters'])){ echo $rzvy_translangArr['please_enter_minimum_8_characters']; }else{ echo $rzvy_defaultlang['please_enter_minimum_8_characters']; } ?>',
			'please_enter_maximum_20_characters' : '<?php if(isset($rzvy_translangArr['please_enter_maximum_20_characters'])){ echo $rzvy_translangArr['please_enter_maximum_20_characters']; }else{ echo $rzvy_defaultlang['please_enter_maximum_20_characters']; } ?>',
			'new_password_and_retype_new_password_mismatch' : '<?php if(isset($rzvy_translangArr['new_password_and_retype_new_password_mismatch'])){ echo $rzvy_translangArr['new_password_and_retype_new_password_mismatch']; }else{ echo $rzvy_defaultlang['new_password_and_retype_new_password_mismatch']; } ?>',
			'your_password_changed_successfully' : '<?php if(isset($rzvy_translangArr['your_password_changed_successfully'])){ echo $rzvy_translangArr['your_password_changed_successfully']; }else{ echo $rzvy_defaultlang['your_password_changed_successfully']; } ?>',
			'incorrect_old_password' : '<?php if(isset($rzvy_translangArr['incorrect_old_password'])){ echo $rzvy_translangArr['incorrect_old_password']; }else{ echo $rzvy_defaultlang['incorrect_old_password']; } ?>',
			'changed' : '<?php if(isset($rzvy_translangArr['changed'])){ echo $rzvy_translangArr['changed']; }else{ echo $rzvy_defaultlang['changed']; } ?>',
			'please_enter_first_name' : '<?php if(isset($rzvy_translangArr['please_enter_first_name'])){ echo $rzvy_translangArr['please_enter_first_name']; }else{ echo $rzvy_defaultlang['please_enter_first_name']; } ?>',
			'please_enter_last_name' : '<?php if(isset($rzvy_translangArr['please_enter_last_name'])){ echo $rzvy_translangArr['please_enter_last_name']; }else{ echo $rzvy_defaultlang['please_enter_last_name']; } ?>',
			'please_enter_maximum_50_characters' : '<?php if(isset($rzvy_translangArr['please_enter_maximum_50_characters'])){ echo $rzvy_translangArr['please_enter_maximum_50_characters']; }else{ echo $rzvy_defaultlang['please_enter_maximum_50_characters']; } ?>',
			'please_enter_phone_number' : '<?php if(isset($rzvy_translangArr['please_enter_phone_number'])){ echo $rzvy_translangArr['please_enter_phone_number']; }else{ echo $rzvy_defaultlang['please_enter_phone_number']; } ?>',
			'please_enter_minimum_10_digits' : '<?php if(isset($rzvy_translangArr['please_enter_minimum_10_digits'])){ echo $rzvy_translangArr['please_enter_minimum_10_digits']; }else{ echo $rzvy_defaultlang['please_enter_minimum_10_digits']; } ?>',
			'please_enter_maximum_15_digits' : '<?php if(isset($rzvy_translangArr['please_enter_maximum_15_digits'])){ echo $rzvy_translangArr['please_enter_maximum_15_digits']; }else{ echo $rzvy_defaultlang['please_enter_maximum_15_digits']; } ?>',
			'please_enter_address' : '<?php if(isset($rzvy_translangArr['please_enter_address'])){ echo $rzvy_translangArr['please_enter_address']; }else{ echo $rzvy_defaultlang['please_enter_address']; } ?>',
			'please_enter_city' : '<?php if(isset($rzvy_translangArr['please_enter_city'])){ echo $rzvy_translangArr['please_enter_city']; }else{ echo $rzvy_defaultlang['please_enter_city']; } ?>',
			'please_enter_state' : '<?php if(isset($rzvy_translangArr['please_enter_state'])){ echo $rzvy_translangArr['please_enter_state']; }else{ echo $rzvy_defaultlang['please_enter_state']; } ?>',
			'please_enter_zip' : '<?php if(isset($rzvy_translangArr['please_enter_zip'])){ echo $rzvy_translangArr['please_enter_zip']; }else{ echo $rzvy_defaultlang['please_enter_zip']; } ?>',
			'please_enter_minimum_5_characters' : '<?php if(isset($rzvy_translangArr['please_enter_minimum_5_characters'])){ echo $rzvy_translangArr['please_enter_minimum_5_characters']; }else{ echo $rzvy_defaultlang['please_enter_minimum_5_characters']; } ?>',
			'please_enter_maximum_10_characters' : '<?php if(isset($rzvy_translangArr['please_enter_maximum_10_characters'])){ echo $rzvy_translangArr['please_enter_maximum_10_characters']; }else{ echo $rzvy_defaultlang['please_enter_maximum_10_characters']; } ?>',
			'please_enter_country' : '<?php if(isset($rzvy_translangArr['please_enter_country'])){ echo $rzvy_translangArr['please_enter_country']; }else{ echo $rzvy_defaultlang['please_enter_country']; } ?>',
			'your_profile_updated_successfully' : '<?php if(isset($rzvy_translangArr['your_profile_updated_successfully'])){ echo $rzvy_translangArr['your_profile_updated_successfully']; }else{ echo $rzvy_defaultlang['your_profile_updated_successfully']; } ?>',
			'updated' : '<?php if(isset($rzvy_translangArr['updated'])){ echo $rzvy_translangArr['updated']; }else{ echo $rzvy_defaultlang['updated']; } ?>',
			'please_enter_ticket_title' : '<?php if(isset($rzvy_translangArr['please_enter_ticket_title'])){ echo $rzvy_translangArr['please_enter_ticket_title']; }else{ echo $rzvy_defaultlang['please_enter_ticket_title']; } ?>',
			'please_enter_ticket_description' : '<?php if(isset($rzvy_translangArr['please_enter_ticket_description'])){ echo $rzvy_translangArr['please_enter_ticket_description']; }else{ echo $rzvy_defaultlang['please_enter_ticket_description']; } ?>',
			'added' : '<?php if(isset($rzvy_translangArr['added'])){ echo $rzvy_translangArr['added']; }else{ echo $rzvy_defaultlang['added']; } ?>',
			'support_ticket_generated_successfully' : '<?php if(isset($rzvy_translangArr['support_ticket_generated_successfully'])){ echo $rzvy_translangArr['support_ticket_generated_successfully']; }else{ echo $rzvy_defaultlang['support_ticket_generated_successfully']; } ?>',
			'support_ticket_updated_successfully' : '<?php if(isset($rzvy_translangArr['support_ticket_updated_successfully'])){ echo $rzvy_translangArr['support_ticket_updated_successfully']; }else{ echo $rzvy_defaultlang['support_ticket_updated_successfully']; } ?>',
			'you_want_to_delete_this_support_ticket' : '<?php if(isset($rzvy_translangArr['you_want_to_delete_this_support_ticket'])){ echo $rzvy_translangArr['you_want_to_delete_this_support_ticket']; }else{ echo $rzvy_defaultlang['you_want_to_delete_this_support_ticket']; } ?>',
			'you_cannot_delete_this_support_ticket_you_have_discussion_on_this_support_ticket' : '<?php if(isset($rzvy_translangArr['you_cannot_delete_this_support_ticket_you_have_discussion_on_this_support_ticket'])){ echo $rzvy_translangArr['you_cannot_delete_this_support_ticket_you_have_discussion_on_this_support_ticket']; }else{ echo $rzvy_defaultlang['you_cannot_delete_this_support_ticket_you_have_discussion_on_this_support_ticket']; } ?>',
			'support_ticket_deleted_successfully' : '<?php if(isset($rzvy_translangArr['support_ticket_deleted_successfully'])){ echo $rzvy_translangArr['support_ticket_deleted_successfully']; }else{ echo $rzvy_defaultlang['support_ticket_deleted_successfully']; } ?>',
			'deleted' : '<?php if(isset($rzvy_translangArr['deleted'])){ echo $rzvy_translangArr['deleted']; }else{ echo $rzvy_defaultlang['deleted']; } ?>',
			'are_you_sure' : '<?php if(isset($rzvy_translangArr['are_you_sure'])){ echo $rzvy_translangArr['are_you_sure']; }else{ echo $rzvy_defaultlang['are_you_sure']; } ?>',
			'yes_delete_it' : '<?php if(isset($rzvy_translangArr['yes_delete_it'])){ echo $rzvy_translangArr['yes_delete_it']; }else{ echo $rzvy_defaultlang['yes_delete_it']; } ?>',
			'yes_mark_it' : '<?php if(isset($rzvy_translangArr['yes_mark_it'])){ echo $rzvy_translangArr['yes_mark_it']; }else{ echo $rzvy_defaultlang['yes_mark_it']; } ?>',
			'marked_as_completed' : '<?php if(isset($rzvy_translangArr['marked_as_completed'])){ echo $rzvy_translangArr['marked_as_completed']; }else{ echo $rzvy_defaultlang['marked_as_completed']; } ?>',
			'you_want_to_mark_this_support_ticket_as_complete' : '<?php if(isset($rzvy_translangArr['you_want_to_mark_this_support_ticket_as_complete'])){ echo $rzvy_translangArr['you_want_to_mark_this_support_ticket_as_complete']; }else{ echo $rzvy_defaultlang['you_want_to_mark_this_support_ticket_as_complete']; } ?>',
			'marked_as_completed' : '<?php if(isset($rzvy_translangArr['marked_as_completed'])){ echo $rzvy_translangArr['marked_as_completed']; }else{ echo $rzvy_defaultlang['marked_as_completed']; } ?>',
			'support_ticket_marked_as_completed_successfully' : '<?php if(isset($rzvy_translangArr['support_ticket_marked_as_completed_successfully'])){ echo $rzvy_translangArr['support_ticket_marked_as_completed_successfully']; }else{ echo $rzvy_defaultlang['support_ticket_marked_as_completed_successfully']; } ?>',
			'please_enter_email' : '<?php if(isset($rzvy_translangArr['please_enter_email'])){ echo $rzvy_translangArr['please_enter_email']; }else{ echo $rzvy_defaultlang['please_enter_email']; } ?>',
			'please_enter_valid_email' : '<?php if(isset($rzvy_translangArr['please_enter_valid_email'])){ echo $rzvy_translangArr['please_enter_valid_email']; }else{ echo $rzvy_defaultlang['please_enter_valid_email']; } ?>',
			'your_email_changed_successfully' : '<?php if(isset($rzvy_translangArr['your_email_changed_successfully'])){ echo $rzvy_translangArr['your_email_changed_successfully']; }else{ echo $rzvy_defaultlang['your_email_changed_successfully']; } ?>',
			'exist' : '<?php if(isset($rzvy_translangArr['exist'])){ echo $rzvy_translangArr['exist']; }else{ echo $rzvy_defaultlang['exist']; } ?>',
			'email_already_exist_please_try_to_update_with_not_registered_email' : '<?php if(isset($rzvy_translangArr['email_already_exist_please_try_to_update_with_not_registered_email'])){ echo $rzvy_translangArr['email_already_exist_please_try_to_update_with_not_registered_email']; }else{ echo $rzvy_defaultlang['email_already_exist_please_try_to_update_with_not_registered_email']; } ?>',
			'please_enter_name' : '<?php if(isset($rzvy_translangArr['please_enter_name'])){ echo $rzvy_translangArr['please_enter_name']; }else{ echo $rzvy_defaultlang['please_enter_name']; } ?>',
			'please_enter_review' : '<?php if(isset($rzvy_translangArr['please_enter_review'])){ echo $rzvy_translangArr['please_enter_review']; }else{ echo $rzvy_defaultlang['please_enter_review']; } ?>',
			'submitted_your_review_submitted_successfully' : '<?php if(isset($rzvy_translangArr['submitted_your_review_submitted_successfully'])){ echo $rzvy_translangArr['submitted_your_review_submitted_successfully']; }else{ echo $rzvy_defaultlang['submitted_your_review_submitted_successfully']; } ?>',
			'today' : '<?php if(isset($rzvy_translangArr['today'])){ echo $rzvy_translangArr['today']; }else{ echo $rzvy_defaultlang['today']; } ?>',
			'calendar_view' : '<?php if(isset($rzvy_translangArr['calendar_view'])){ echo $rzvy_translangArr['calendar_view']; }else{ echo $rzvy_defaultlang['calendar_view']; } ?>',
			'month_list_view' : '<?php if(isset($rzvy_translangArr['month_list_view'])){ echo $rzvy_translangArr['month_list_view']; }else{ echo $rzvy_defaultlang['month_list_view']; } ?>',
			'year_list_view' : '<?php if(isset($rzvy_translangArr['year_list_view'])){ echo $rzvy_translangArr['year_list_view']; }else{ echo $rzvy_defaultlang['year_list_view']; } ?>',
		};
	</script>
	
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'c_profile.php') != false) { ?>
		<?php 
		$rzvy_birthdate_with_year = $obj_settings->get_option('rzvy_birthdate_with_year');
		if($rzvy_birthdate_with_year == "Y"){ ?>
			<script src="<?php echo SITE_URL; ?>includes/front/js/datepicker.year.min.js?<?php echo time(); ?>"></script>
			<script>
			$('#rzvy_profile_dob').datepicker({
				format: "d MM yyyy"
			});
			</script>
		<?php }else{ ?>
			<script src="<?php echo SITE_URL; ?>includes/front/js/datepicker.min.js?<?php echo time(); ?>"></script>
			<script>
			$('#rzvy_profile_dob').datepicker({
				format: "d MM",
				maxViewMode: 1,
				defaultViewDate: {year: 2020}
			});
			</script>
		<?php } ?>
	<?php } ?>
	<?php $Rzvy_Hooks->customerFooterIncludes(); ?>
	<script src="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/js/intlTelInput.js?<?php echo time(); ?>"></script>
	<script src="<?php echo SITE_URL; ?>includes/js/rzvy-customer.js?<?php echo time(); ?>"></script>
	<script src="<?php echo SITE_URL; ?>includes/js/rzvy-set-languages.js?<?php echo time(); ?>"></script>
  </div>
</body>
</html>  