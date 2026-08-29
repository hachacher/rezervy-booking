<?php 
/* Include class files */
include(dirname(dirname(__FILE__))."/constants.php");

if(!isset($_SESSION['login_type'])) { 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/";
	</script>
	<?php  
	exit;
}else if($_SESSION['login_type'] == "customer") { 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/c-dashboard.php";
	</script>
	<?php  
	exit;
}else if($_SESSION['login_type'] == "staff") { 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/staff-profile.php";
	</script>
	<?php  
	exit;
}else{}
if(!isset($_SESSION['admin_id'])) { 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/";
	</script>
	<?php  
	exit;
}

$obj_database->check_admin_setup_detail($conn);

include(dirname(dirname(__FILE__))."/classes/class_coupons.php");
include(dirname(dirname(__FILE__))."/classes/class_settings.php");
include(dirname(dirname(__FILE__))."/classes/class_frequently_discount.php");
include(dirname(dirname(__FILE__))."/classes/class_schedule.php");
include(dirname(dirname(__FILE__))."/classes/class_bookings.php");
include(dirname(dirname(__FILE__))."/classes/class_categories.php");
include(dirname(dirname(__FILE__))."/classes/class_parent_categories.php");
include(dirname(dirname(__FILE__))."/classes/class_services.php");
include(dirname(dirname(__FILE__))."/classes/class_addons.php");
include(dirname(dirname(__FILE__))."/classes/class_admins.php");
include(dirname(dirname(__FILE__))."/classes/class_block_off.php");
include(dirname(dirname(__FILE__))."/classes/class_refund_request.php");
include(dirname(dirname(__FILE__))."/classes/class_staff.php");
include(dirname(dirname(__FILE__))."/classes/class_roles.php");

/* Create object of classes */
$obj_coupons = new rzvy_coupons();
$obj_coupons->conn = $conn;

$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

$obj_refund_request = new rzvy_refund_request();
$obj_refund_request->conn = $conn;

$obj_frequently_discount = new rzvy_frequently_discount();
$obj_frequently_discount->conn = $conn;

$obj_schedule = new rzvy_schedule();
$obj_schedule->conn = $conn;

$obj_bookings = new rzvy_bookings();
$obj_bookings->conn = $conn;

$obj_categories = new rzvy_categories();
$obj_categories->conn = $conn;

$obj_pcategories = new rzvy_parent_categories();
$obj_pcategories->conn = $conn;

$obj_services = new rzvy_services();
$obj_services->conn = $conn;

$obj_addons = new rzvy_addons();
$obj_addons->conn = $conn;

$obj_admins = new rzvy_admins();
$obj_admins->conn = $conn;

$obj_staff = new rzvy_staff();
$obj_staff->conn = $conn;

$obj_block_off = new rzvy_block_off();
$obj_block_off->conn = $conn;

$obj_roles = new rzvy_roles();
$obj_roles->conn = $conn;


$rzvy_book_with_datetime = $obj_settings->get_option("rzvy_book_with_datetime");
$rzvy_settings_timezone = $obj_settings->get_option("rzvy_timezone");
$rzvy_parent_category = $obj_settings->get_option("rzvy_parent_category");
$rzvy_show_category_image = $obj_settings->get_option("rzvy_show_category_image"); 
$rzvy_show_service_image = $obj_settings->get_option("rzvy_show_service_image"); 
$rzvy_show_addon_image = $obj_settings->get_option("rzvy_show_addon_image"); 
$rzvy_server_timezone = date_default_timezone_get();
$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($rzvy_server_timezone,$rzvy_settings_timezone);

$dash_title = $obj_settings->get_option("rzvy_company_name");
if($dash_title == ""){
	if(isset($rzvy_translangArr['dashboard'])){ $dash_title = $rzvy_translangArr['dashboard']; }else{ $dash_title = $rzvy_defaultlang['dashboard']; } 
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" type="image/png" href="<?php echo SITE_URL; ?>includes/images/favicon.ico" />
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
	<link href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/bootstrap.min.css?<?php echo time(); ?>" rel="stylesheet">
	<link href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/bootstrap-select.min.css?<?php echo time(); ?>" rel="stylesheet">
	<!-- Custom fonts for this template-->
	<link href="<?php echo SITE_URL; ?>includes/vendor/font-awesome/css/font-awesome.min.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo SITE_URL; ?>includes/vendor/sweetalert/sweetalert.css?<?php echo time(); ?>" rel="stylesheet" type="text/css">
	<!-- Page level plugin CSS-->
	<link href="<?php echo SITE_URL; ?>includes/vendor/datatables/datatables.min.css?<?php echo time(); ?>" rel="stylesheet">
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'apptlist.php') != false) { ?>
	<link href="<?php echo SITE_URL; ?>includes/vendor/datatables/datatable.responsive.min.css?<?php echo time(); ?>" rel="stylesheet">
	<?php } ?>
	<!-- Include all css file for calendar -->
	<link href='<?php echo SITE_URL; ?>includes/vendor/calendar/fullcalendar.min.css?<?php echo time(); ?>' rel='stylesheet' />
	<!-- Custom styles for this template-->
	<link href="<?php echo SITE_URL; ?>includes/css/rzvy-admin.css?<?php echo time(); ?>" rel="stylesheet">
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'appointments.php') != false) { ?>
	<!-- Custom frontend CSS -->
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/manual-booking/css/pe-icon-7-stroke.css?<?php echo time(); ?>" />
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/manual-booking/css/datepicker.min.css?<?php echo time(); ?>" />
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/manual-booking/css/rzvy-mb-style.css?<?php echo time(); ?>">
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/manual-booking/css/rzvy-mb-calendar-style.css?<?php echo time(); ?>">
	<?php } ?>
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'category.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'services.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'reorder-addons.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'staff.php') != false) { ?>
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery-ui.min.css?<?php echo time(); ?>" />
	<?php } ?>
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'location-selector.php') != false) { ?>
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/bootstrap-tagsinput.css?<?php echo time(); ?>" />
	<?php } ?>
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'location-selector.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'refund.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'email-sms-templates.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'settings.php') != false) { ?>
		<!-- include text editor -->
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/text-editor/text-editor.css">
	<?php } ?>
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'dashboard.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'advance-schedule.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'advance-schedule-breaks.php') != false) { ?>
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/daterangepicker.css?<?php echo time(); ?>" >
	<?php } ?>
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/css/intlTelInput.css?<?php echo time(); ?>">
	<?php $Rzvy_Hooks->adminHeaderIncludes(); ?>
	<?php include("dashboard_css.php"); ?>
</head>
<body class="rzvy fixed-nav sticky-footer bg-light" id="rzvy-page-top">
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" id="rzvy-mainnav">
    <a class="navbar-brand" href="<?php echo SITE_URL; ?>backend/dashboard.php"><?php echo ucwords($dash_title); ?></a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#rzvy-navbarresponsive" aria-controls="rzvy-navbarresponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
	<div class="collapse navbar-collapse" id="rzvy-navbarresponsive">
	  <ul class="navbar-nav navbar-sidenav" id="rzvy-menu-accordion">
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'external-addons.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/external-addons.php">
			<i class="fa fa-fw fa-shopping-cart"></i>
			<span class="nav-link-text">External Addons</span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'dashboard.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/dashboard.php?start=<?php echo date('Y-m-d', strtotime('-29 days')); ?>&end=<?php echo date('Y-m-d'); ?>">
			<i class="fa fa-fw fa-tachometer"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['dashboard'])){ echo $rzvy_translangArr['dashboard']; }else{ echo $rzvy_defaultlang['dashboard']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'appointments.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'apptlist.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/appointments.php">
			<i class="fa fa-fw fa-calendar-check-o"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['appointments'])){ echo $rzvy_translangArr['appointments']; }else{ echo $rzvy_defaultlang['appointments']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'services.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'category.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/<?php echo ($rzvy_parent_category == "Y")?'p-category.php':'category.php'; ?>">
			<i class="fa fa-fw fa-th-list"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['services'])){ echo $rzvy_translangArr['services']; }else{ echo $rzvy_defaultlang['services']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'addons.php') != false && strpos($_SERVER['SCRIPT_NAME'], 'external-addons.php') == false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/addons.php">
			<i class="fa fa-fw fa-puzzle-piece"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['addons'])){ echo $rzvy_translangArr['addons']; }else{ echo $rzvy_defaultlang['addons']; } ?></span>
		  </a>
		</li>
		
		<?php $Rzvy_Hooks->do_action('pos_menu', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang)); ?>
		
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'customers.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/customers.php">
			<i class="fa fa-fw fa-users"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['customers'])){ echo $rzvy_translangArr['customers']; }else{ echo $rzvy_defaultlang['customers']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'payments.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/payments.php">
			<i class="fa fa-fw fa-money"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['payments'])){ echo $rzvy_translangArr['payments']; }else{ echo $rzvy_defaultlang['payments']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'staff.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'advance-schedule.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'advance-schedule-breaks.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/staff.php">
			<i class="fa fa-fw fa-user"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['staff'])){ echo $rzvy_translangArr['staff']; }else{ echo $rzvy_defaultlang['staff']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'role-permissions.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'permissions.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/role-permissions.php">
			<i class="fa fa-fw fa-unlock-alt"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['role_permissions'])){ echo $rzvy_translangArr['role_permissions']; }else{ echo $rzvy_defaultlang['role_permissions']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'gcal.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/gcal.php">
			<i class="fa fa-fw fa-calendar-plus-o"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['google_calendar'])){ echo $rzvy_translangArr['google_calendar']; }else{ echo $rzvy_defaultlang['google_calendar']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'manage-blockoff.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/manage-blockoff.php">
			<i class="fa fa-fw fa-calendar"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['manage_block_off'])){ echo $rzvy_translangArr['manage_block_off']; }else{ echo $rzvy_defaultlang['manage_block_off']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'location-selector.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/location-selector.php">
			<i class="fa fa-fw fa-map-marker"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['location_selector'])){ echo $rzvy_translangArr['location_selector']; }else{ echo $rzvy_defaultlang['location_selector']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'refund.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/refund.php">
			<i class="fa fa-fw fa-exchange"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['refund_request'])){ echo $rzvy_translangArr['refund_request']; }else{ echo $rzvy_defaultlang['refund_request']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'coupons.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/coupons.php">
			<i class="fa fa-fw fa-ticket"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['coupons'])){ echo $rzvy_translangArr['coupons']; }else{ echo $rzvy_defaultlang['coupons']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'referral-setting.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/referral-setting.php">
			<i class="fa fa-fw fa-gift"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['referral_settings'])){ echo $rzvy_translangArr['referral_settings']; }else{ echo $rzvy_defaultlang['referral_settings']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'frequently-discount.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/frequently-discount.php">
			<i class="fa fa-fw fa-percent"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['frequently_discount_noad'])){ echo $rzvy_translangArr['frequently_discount_noad']; }else{ echo $rzvy_defaultlang['frequently_discount_noad']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'email-sms-templates.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/email-sms-templates.php">
			<i class="fa fa-fw fa-columns"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['email_and_sms_templates'])){ echo $rzvy_translangArr['email_and_sms_templates']; }else{ echo $rzvy_defaultlang['email_and_sms_templates']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if(strpos($_SERVER['SCRIPT_NAME'], 'reminder.php') != false) { echo 'rzy_active'; } ?>">
			<a class="nav-link" href="<?php echo SITE_URL; ?>backend/reminder.php">
				<i class="fa fa-fw fa-bell-o"></i>
				<span class="nav-link-text"><?php if(isset($rzvy_translangArr['reminder'])){ echo $rzvy_translangArr['reminder']; }else{ echo $rzvy_defaultlang['reminder']; } ?></span>
			</a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'form-fields.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/form-fields.php">
			<i class="fa fa-fw fa-file-text-o"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['manage_booking_form'])){ echo $rzvy_translangArr['manage_booking_form']; }else{ echo $rzvy_defaultlang['manage_booking_form']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'languages.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/languages.php">
			<i class="fa fa-fw fa-language"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['languages'])){ echo $rzvy_translangArr['languages']; }else{ echo $rzvy_defaultlang['languages']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if(strpos($_SERVER['SCRIPT_NAME'], 'color-scheme.php') != false) { echo 'rzy_active'; } ?>">
			<a class="nav-link" href="<?php echo SITE_URL; ?>backend/color-scheme.php">
				<i class="fa fa-fw fa-tachometer"></i>
				<span class="nav-link-text"><?php if(isset($rzvy_translangArr['color_scheme'])){ echo $rzvy_translangArr['color_scheme']; }else{ echo $rzvy_defaultlang['color_scheme']; } ?></span>
			</a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'embed.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/embed.php">
			<i class="fa fa-fw fa-code"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['embed_frontend'])){ echo $rzvy_translangArr['embed_frontend']; }else{ echo $rzvy_defaultlang['embed_frontend']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'support-tickets.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'ticket-discussion.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/support-tickets.php">
			<i class="fa fa-fw fa-comments-o"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['support_tickets'])){ echo $rzvy_translangArr['support_tickets']; }else{ echo $rzvy_defaultlang['support_tickets']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'export.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/export.php">
			<i class="fa fa-fw fa-cloud-upload"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['export'])){ echo $rzvy_translangArr['export']; }else{ echo $rzvy_defaultlang['export']; } ?></span>
		  </a>
		</li>
		<li class="nav-item <?php if (strpos($_SERVER['SCRIPT_NAME'], 'feedback.php') != false) { echo 'rzy_active'; } ?>">
		  <a class="nav-link" href="<?php echo SITE_URL; ?>backend/feedback.php">
			<i class="fa fa-fw fa-comments"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['feedback'])){ echo $rzvy_translangArr['feedback']; }else{ echo $rzvy_defaultlang['feedback']; } ?></span>
		  </a>
		</li>
		<li class="nav-item pb-4">
			<a class="nav-link" href="http://rezervy.perfecky.com/documentation/"><i class="fa fa-fw fa-file" aria-hidden="true"></i> <span class="nav-link-text"><?php if(isset($rzvy_translangArr['documentation'])){ echo $rzvy_translangArr['documentation']; }else{ echo $rzvy_defaultlang['documentation']; } ?></span></a>
		</li>
		<li class="nav-item fixed-bottom">
			<div class="bg-primary text-white text-center">
                <div class="nav-link-text" style="font-size: 12px;">
					Current Version: <?php echo $obj_settings->get_option("rzvy_version"); ?>
                </div>
            </div>
		</li>
	  </ul>
	  <ul class="navbar-nav ml-auto">
		<li class="nav-item dropdown rzvy-notification-dd">
		  <a class="nav-link dropdown-toggle mr-lg-2 rzvy-notification-dropdown-link" id="rzvy-notification-dropdown" href="javascript:void(0)">
			<i class="fa fa-fw fa-bell"></i>
			<span class="indicator text-warning d-lg-block"><?php echo $obj_bookings->get_count_of_latest_unread_appointments(); ?></span>
		  </a>
		  <div class="dropdown-menu new-appointments-dropdown-menu" aria-labelledby="rzvy-notification-dropdown" id="rzvy-notification-dropdown-content">
			
		  </div>
		</li>
		<li class="nav-item dropdown rzvy-refundrequest-dd">
		  <a class="nav-link dropdown-toggle mr-lg-2 rzvy-refund-dropdown-link" id="rzvy-refund-dropdown" href="javascript:void(0)">
			<i class="fa fa-fw fa-exchange"></i>
			<span class="indicator text-warning d-lg-block"><?php echo $obj_refund_request->get_count_of_latest_unread_refund_requests(); ?></span>
		  </a>
		  <div class="dropdown-menu new-refund-request-dropdown-menu" aria-labelledby="rzvy-refund-dropdown" id="rzvy-refund-dropdown-content">
			
		  </div>
		</li>
		<li class="nav-item dropdown rzvy-supportticket-dd">
		  <a class="nav-link dropdown-toggle mr-lg-2 rzvy-supportticket-dropdown-link" id="rzvy-supportticket-dropdown" href="javascript:void(0)">
			<i class="fa fa-fw fa-comments-o"></i>
			<span class="indicator text-warning d-lg-block"><?php echo $obj_bookings->get_count_of_latest_unread_supporttickets(); ?></span>
		  </a>
		  <div class="dropdown-menu new-supportticket-dropdown-menu" aria-labelledby="rzvy-supportticket-dropdown" id="rzvy-supportticket-dropdown-content">
			
		  </div>
		</li>
		<li class="nav-item">
		  <a class="nav-link <?php if (strpos($_SERVER['SCRIPT_NAME'], 'settings.php') != false) { echo 'rzy_active'; } ?>" href="<?php echo SITE_URL; ?>backend/settings.php">
			<i class="fa fa-fw fa-cog"></i>
			<span class="nav-link-text"><?php if(isset($rzvy_translangArr['settings'])){ echo $rzvy_translangArr['settings']; }else{ echo $rzvy_defaultlang['settings']; } ?></span>
		  </a>
		</li>
		<li class="nav-item">
			<a class="nav-link <?php if (strpos($_SERVER['SCRIPT_NAME'], 'profile.php') != false) { echo 'rzy_active'; } ?>" href="<?php echo SITE_URL; ?>backend/profile.php"><i class="fa fa-fw fa-user-o" aria-hidden="true"></i> <?php if(isset($rzvy_translangArr['profile'])){ echo $rzvy_translangArr['profile']; }else{ echo $rzvy_defaultlang['profile']; } ?></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="modal" data-target="#rzvy-change-password-modal"><i class="fa fa-fw fa-key" aria-hidden="true"></i> <?php if(isset($rzvy_translangArr['change_password'])){ echo $rzvy_translangArr['change_password']; }else{ echo $rzvy_defaultlang['change_password']; } ?></a>
		</li>
		<?php 		
		if($lang_j>1){ 
			?>
			<li class="nav-item">
				<div class="nav-link nav-link-no-hover py-0">
					<div class="btn-primary px-2">
						<i class="fa fa-fw fa-language text-white" style="font-size:20px"></i>
						<select class="rzvy_set_language selectpicker" data-style="btn-primary">
							<?php echo $langOptions; ?>
						</select>
					</div>
				</div>
			</li>
			<?php 
		}  
		?>
		<li class="nav-item">
		  <a class="nav-link" data-toggle="modal" data-target="#rzvy-logout-modal">
			<i class="fa fa-fw fa-sign-out"></i> <?php if(isset($rzvy_translangArr['logout'])){ echo $rzvy_translangArr['logout']; }else{ echo $rzvy_defaultlang['logout']; } ?></a>
		</li>
	  </ul>
	</div>
  </nav>
  <div class="rzvy-content-wrapper">
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
    <div class="container-fluid">