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
}else if($_SESSION['login_type'] == "admin") { 
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/dashboard.php";
	</script>
	<?php  
	exit;
}else{}
if(!isset($_SESSION['staff_id'])) { 
	
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/";
	</script>
	<?php  
	exit;
}

$obj_database->check_admin_setup_detail($conn);

include(dirname(dirname(__FILE__))."/classes/class_coupons.php");include(dirname(dirname(__FILE__))."/classes/class_settings.php");
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
$obj_settings->staff_id = $_SESSION['staff_id'];

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
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 's-apptlist.php') != false) { ?>
	<link href="<?php echo SITE_URL; ?>includes/vendor/datatables/datatable.responsive.min.css?<?php echo time(); ?>" rel="stylesheet">
	<link href="<?php echo SITE_URL; ?>includes/vendor/datatables/buttons.dataTables.min.css?<?php echo time(); ?>" rel="stylesheet">
	<?php } ?>
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'location-selector.php') != false) { ?>
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/bootstrap-tagsinput.css?<?php echo time(); ?>" />
	<?php } ?>
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'location-selector.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'refund.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'email-sms-templates.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'settings.php') != false) { ?>
		<!-- include text editor -->
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/text-editor/text-editor.css">
	<?php } ?>
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/css/intlTelInput.css?<?php echo time(); ?>">
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 's-dashboard.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'advance-schedule.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'advance-schedule-breaks.php') != false) { ?>
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/daterangepicker.css?<?php echo time(); ?>" >
	<?php } ?>
	
	<link href="<?php echo SITE_URL; ?>includes/css/rzvy-menu.css?<?php echo time(); ?>" rel="stylesheet">
	
	<?php $Rzvy_Hooks->staffHeaderIncludes(); ?>
	<?php include("dashboard_css.php"); ?>
	
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

<body class="rzvy bg-white" id="rzvy-page-top">
  <div class="rzvy__main__container">
	<!-- Top Bar START -->
	<nav class="navbar navbar-expand rzvy_topbarbg pb-1 sticky-top">
		<div class="collapse navbar-collapse justify-content-between">
			<div class="rzvy_running_version text-white">
				<?php if(isset($rzvy_rolepermissions['rzvy_currverdis']) || $rzvy_loginutype=='admin'){ ?>
					<small><b>Running Version: <?php echo $obj_settings->get_option("rzvy_version"); ?></b></small>
				<?php } ?>
			</div>
			<ul class="navbar-nav">
				<?php if(isset($rzvy_rolepermissions['rzvy_barbooking']) || $rzvy_loginutype=='admin'){ ?>
					<li class="nav-item header-profile mr-4 rzvy-notification-dd">
						<a class="nav-link ai-icon rzvy-notification-dropdown-link text-white" href="javascript:void(0)" id="rzvy-notification-dropdown">
							<i class="fa fa-fw fa-bell fa-lg"></i>
							<span class="light text-white bg-primary rounded-circle rzvy_menubar_badge"><?php echo $obj_bookings->get_count_of_latest_unread_appointments(); ?></span>
						</a>
					</li>
				<?php } ?>
				
				<?php if(isset($rzvy_rolepermissions['rzvy_barrefund']) || $rzvy_loginutype=='admin'){ ?>
					<li class="nav-item header-profile mr-4 rzvy-refundrequest-dd">
						<a class="nav-link ai-icon rzvy-refund-dropdown-link text-white" id="rzvy-refund-dropdown" href="javascript:void(0)">
							<i class="fa fa-fw fa-exchange fa-lg"></i>
							<span class="light text-white bg-primary rounded-circle rzvy_menubar_badge"><?php echo $obj_refund_request->get_count_of_latest_unread_refund_requests(); ?></span>
						</a>
					</li>
				<?php } ?>
				
				<?php if(isset($rzvy_rolepermissions['rzvy_barsupport']) || $rzvy_loginutype=='admin'){ ?>
					<li class="nav-item header-profile mr-4 rzvy-supportticket-dd">
						<a class="nav-link ai-icon rzvy-supportticket-dropdown-link text-white" id="rzvy-supportticket-dropdown" href="javascript:void(0)">
							<i class="fa fa-fw fa-comments-o fa-lg"></i>
							<span class="light text-white bg-primary rounded-circle rzvy_menubar_badge"><?php echo $obj_bookings->get_count_of_latest_unread_supporttickets(); ?></span>
						</a>
					</li>
				<?php } ?>
				<?php 
				if($lang_j>1){ 
					?>
					<li class="nav-item dropdown header-profile mr-4">
						<a class="nav-link text-white rzvy_menubar_langmenu_link" href="javascript:void(0)" role="button" data-toggle="dropdown" aria-expanded="false">
							<i class="fa fa-fw fa-language fa-lg"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right rzvy_menubar_lngmenu_link_dd">
						<?php echo $langOptions_anchortag; ?>
						</div>
					</li>
					<?php 
				}  
				?>
				<li class="nav-item dropdown header-profile mr-2">
					<?php 
						$staffhdimage = SITE_URL.'includes/images/staff-lg.png'; 
						if($_SESSION['login_type'] == "staff"){ 
							$passLoggedID = $_SESSION["staff_id"]; 
							$get_staff_name = $obj_settings->get_staff_name($passLoggedID);
							if(mysqli_num_rows($get_staff_name)>0){ 
								$__staffname = mysqli_fetch_array($get_staff_name);
								if($__staffname["image"]!==''){
									$staffhdimage = SITE_URL.'uploads/images/'.$__staffname["image"];
								}
							}							
						}else{ 
							$passLoggedID = $_SESSION["admin_id"]; 
							$get_admin_name = $obj_settings->get_admin_name($passLoggedID); 
							if(mysqli_num_rows($get_admin_name)>0){ 
								$__adminname = mysqli_fetch_array($get_admin_name);
								if($__adminname["image"]!==''){
									$staffhdimage = SITE_URL.'uploads/images/'.$__adminname["image"];
								}
							}							
						} 
					?>
					<a class="nav-link rzvy_menubar_usermenu_link" href="javascript:void(0)" role="button" data-toggle="dropdown" aria-expanded="false">
						<img src="<?php echo $staffhdimage; ?>" />
					</a>
					<div class="dropdown-menu dropdown-menu-right rzvy_menubar_usermenu_link_dd">
						<span class="text-black px-2 rzvy_menubar_usermenu_link_title"><strong><?php 
							if($_SESSION['login_type'] == "staff"){ 
								if(mysqli_num_rows($get_staff_name)>0){ 
								    echo ucwords($__staffname["firstname"]." ".$__staffname["lastname"]); }
							}else{ 
								if(mysqli_num_rows($get_admin_name)>0){ 
								    echo ucwords($__adminname["firstname"]." ".$__adminname["lastname"]); }
							} 
							?></strong><?php if(isset($_SESSION["staff_role_name"]) && $_SESSION["staff_role_name"] != ""){ echo "<br /><small class='px-2'>(".ucwords($_SESSION["staff_role_name"]).")</small>"; } ?></span>
						<div class="dropdown-divider"></div>
						<?php if($rzvy_loginutype=='staff'){ ?>
							<a href="<?php echo SITE_URL; ?>backend/staff-profile.php" class="dropdown-item ai-icon">
								<i class="fa fa-fw fa-user"></i>
								<span class="ml-2"><?php if(isset($rzvy_translangArr['profile'])){ echo $rzvy_translangArr['profile']; }else{ echo $rzvy_defaultlang['profile']; } ?></span>
							</a>
						<?php }else{ ?>
							<a href="<?php echo SITE_URL; ?>backend/profile.php" class="dropdown-item ai-icon">
								<i class="fa fa-fw fa-user"></i>
								<span class="ml-2"><?php if(isset($rzvy_translangArr['profile'])){ echo $rzvy_translangArr['profile']; }else{ echo $rzvy_defaultlang['profile']; } ?></span>
							</a>
						<?php } ?>
						
						<?php if(isset($rzvy_rolepermissions['rzvy_settings']) || $rzvy_loginutype=='admin'){ ?>
							<a href="<?php echo SITE_URL; ?>backend/settings.php" class="dropdown-item ai-icon">
								<i class="fa fa-fw fa-cog"></i>
								<span class="ml-2"><?php if(isset($rzvy_translangArr['settings'])){ echo $rzvy_translangArr['settings']; }else{ echo $rzvy_defaultlang['settings']; } ?></span>
							</a>
						<?php } ?>
						<a href="javascript:void(0)" class="dropdown-item ai-icon" data-toggle="modal" data-target="#rzvy-change-password-modal">
							<i class="fa fa-fw fa-key"></i> 
							<span class="ml-2"><?php if(isset($rzvy_translangArr['change_password'])){ echo $rzvy_translangArr['change_password']; }else{ echo $rzvy_defaultlang['change_password']; } ?></a></span>
						</a>
						<a href="javascript:void(0)" class="dropdown-item ai-icon" data-toggle="modal" data-target="#rzvy-logout-modal">
							<i class="fa fa-fw fa-sign-out"></i> 
							<span class="ml-2"><?php if(isset($rzvy_translangArr['logout'])){ echo $rzvy_translangArr['logout']; }else{ echo $rzvy_defaultlang['logout']; } ?></span>
						</a>
					</div>
				</li>
			</ul>
			
			<div class="dropdown-menu dropdown-menu-right new-appointments-dropdown-menu" aria-labelledby="rzvy-notification-dropdown" id="rzvy-notification-dropdown-content"></div>
			<div class="dropdown-menu dropdown-menu-right new-refund-request-dropdown-menu" aria-labelledby="rzvy-refund-dropdown" id="rzvy-refund-dropdown-content"></div>
			<div class="dropdown-menu dropdown-menu-right new-supportticket-dropdown-menu" aria-labelledby="rzvy-supportticket-dropdown" id="rzvy-supportticket-dropdown-content"></div>
			
		</div>
	</nav>
	<!-- Top Bar END -->
  <div class="rzvy_menubar">
    <div class="rzvy_menubar_logo_main">
      <div class="rzvy_menubar_logo">
		<i class="fa fa-fw fa-calendar"></i>
        <div class="rzvy_menubar_logo_name"><?php echo $dash_title; ?></div>
      </div>
      <i class='fa fa-fw fa-bars' id="rzvy_menubar_toggle" ></i>
    </div>
    <ul class="rzvy_menubar_nav_list">
	
		<!-- External Addons -->
		<?php if(isset($rzvy_rolepermissions['rzvy_extaddons']) || $rzvy_loginutype=='admin'){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'external-addons.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/external-addons.php">
				  <i class="fa fa-fw fa-shopping-cart"></i>
				  <span class="rzvy_menubar_nav_name">External Addons</span>
				</a>
				<span class="rzvy_menubar_nav_tooltip">External Addons</span>
			</li>
		<?php } ?>
		
		<!-- Dashboard -->
		<?php if(isset($rzvy_rolepermissions['rzvy_dashboard']) || $rzvy_loginutype=='admin'){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'dashboard.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/dashboard.php?start=<?php echo date('Y-m-d'); ?>&end=<?php echo date('Y-m-d'); ?>">
				  <i class="fa fa-fw fa-tachometer"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['dashboard'])){ echo $rzvy_translangArr['dashboard']; }else{ echo $rzvy_defaultlang['dashboard']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['dashboard'])){ echo $rzvy_translangArr['dashboard']; }else{ echo $rzvy_defaultlang['dashboard']; } ?></span>
			</li>
		<?php }else{ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 's-dashboard.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/s-dashboard.php?start=<?php echo date('Y-m-d'); ?>&end=<?php echo date('Y-m-d'); ?>">
				  <i class="fa fa-fw fa-tachometer"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['dashboard'])){ echo $rzvy_translangArr['dashboard']; }else{ echo $rzvy_defaultlang['dashboard']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['dashboard'])){ echo $rzvy_translangArr['dashboard']; }else{ echo $rzvy_defaultlang['dashboard']; } ?></span>
			</li>
		<?php } ?>
		
		<!-- Calendar -->
		<?php if(isset($rzvy_rolepermissions['rzvy_appointments']) || $rzvy_loginutype=='admin'){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'appointments.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/appointments.php">
				  <i class="fa fa-fw fa-calendar"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['calendar'])){ echo $rzvy_translangArr['calendar']; }else{ echo $rzvy_defaultlang['calendar']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['calendar'])){ echo $rzvy_translangArr['calendar']; }else{ echo $rzvy_defaultlang['calendar']; } ?></span>
			</li>
		<?php }else{ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 's-appointments.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/s-appointments.php">
				  <i class="fa fa-fw fa-calendar"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['calendar'])){ echo $rzvy_translangArr['calendar']; }else{ echo $rzvy_defaultlang['calendar']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['calendar'])){ echo $rzvy_translangArr['calendar']; }else{ echo $rzvy_defaultlang['calendar']; } ?></span>
			</li>
		<?php } ?>
		<!-- Appointments -->
		<?php if(isset($rzvy_rolepermissions['rzvy_list_view']) || $rzvy_loginutype=='admin'){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'apptlist.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/apptlist.php">
				  <i class="fa fa-fw fa-calendar-check-o"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['appointments'])){ echo $rzvy_translangArr['appointments']; }else{ echo $rzvy_defaultlang['appointments']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['appointments'])){ echo $rzvy_translangArr['appointments']; }else{ echo $rzvy_defaultlang['appointments']; } ?></span>
			</li>
		<?php }else{ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 's-apptlist.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/s-apptlist.php">
				  <i class="fa fa-fw fa-calendar-check-o"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['appointments'])){ echo $rzvy_translangArr['appointments']; }else{ echo $rzvy_defaultlang['appointments']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['appointments'])){ echo $rzvy_translangArr['appointments']; }else{ echo $rzvy_defaultlang['appointments']; } ?></span>
			</li>
		<?php } ?>
		
		<!-- POS -->
		<?php if(isset($rzvy_rolepermissions['rzvy_pos']) || $rzvy_loginutype=='admin'){
			$Rzvy_Hooks->do_action('pos_menu', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang));
		} ?>
		<!-- Services Package -->
		<?php if(isset($rzvy_rolepermissions['rzvy_servciespackage']) || $rzvy_loginutype=='admin'){
			$Rzvy_Hooks->do_action('services_package_menu', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang));
		} ?>
		
		<!-- Appointments -->
		<?php if(isset($rzvy_rolepermissions['rzvy_list_view']) || $rzvy_loginutype=='admin'){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'apptlist.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/apptlist.php">
				  <i class="fa fa-fw fa-calendar-check-o"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['appointments'])){ echo $rzvy_translangArr['appointments']; }else{ echo $rzvy_defaultlang['appointments']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['appointments'])){ echo $rzvy_translangArr['appointments']; }else{ echo $rzvy_defaultlang['appointments']; } ?></span>
			</li>
		<?php } ?>
		
		<!-- Sales -->
		<?php if(isset($rzvy_rolepermissions['rzvy_payments']) || $rzvy_loginutype=='admin'){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'payments.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/payments.php">
				  <i class="fa fa-fw fa-money"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['sales'])){ echo $rzvy_translangArr['sales']; }else{ echo $rzvy_defaultlang['sales']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['sales'])){ echo $rzvy_translangArr['sales']; }else{ echo $rzvy_defaultlang['sales']; } ?></span>
			</li>
		<?php } ?>
		
		<!-- Customers -->
		<?php if(isset($rzvy_rolepermissions['rzvy_customers']) || $rzvy_loginutype=='admin'){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'customers.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/customers.php">
				  <i class="fa fa-fw fa-users"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['customers'])){ echo $rzvy_translangArr['customers']; }else{ echo $rzvy_defaultlang['customers']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['customers'])){ echo $rzvy_translangArr['customers']; }else{ echo $rzvy_defaultlang['customers']; } ?></span>
			</li>
		<?php } ?>
				
		<!-- Staff -->
		<?php if(isset($rzvy_rolepermissions['rzvy_staff']) || $rzvy_loginutype=='admin'){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'staff.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'advance-schedule.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'advance-schedule-breaks.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/staff.php">
				  <i class="fa fa-fw fa-user"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['staff'])){ echo $rzvy_translangArr['staff']; }else{ echo $rzvy_defaultlang['staff']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['staff'])){ echo $rzvy_translangArr['staff']; }else{ echo $rzvy_defaultlang['staff']; } ?></span>
			</li>
		<?php } ?>
		
		<!-- Services -->
		<?php if($rzvy_loginutype=='admin'){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'services.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'category.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'addons.php') != false && strpos($_SERVER['SCRIPT_NAME'], 'external-addons.php') == false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/<?php echo ($rzvy_parent_category == "Y")?'p-category.php':'category.php'; ?>">
				  <i class="fa fa-fw fa-th-list"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['services'])){ echo $rzvy_translangArr['services']; }else{ echo $rzvy_defaultlang['services']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['services'])){ echo $rzvy_translangArr['services']; }else{ echo $rzvy_defaultlang['services']; } ?></span>
			</li>
		<?php }else if(isset($rzvy_rolepermissions['rzvy_servcies'])){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'services.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'category.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'addons.php') != false && strpos($_SERVER['SCRIPT_NAME'], 'external-addons.php') == false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/<?php echo ($rzvy_parent_category == "Y")?'p-category.php':'category.php'; ?>">
				  <i class="fa fa-fw fa-th-list"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['services'])){ echo $rzvy_translangArr['services']; }else{ echo $rzvy_defaultlang['services']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['services'])){ echo $rzvy_translangArr['services']; }else{ echo $rzvy_defaultlang['services']; } ?></span>
			</li>
		<?php }else if(!isset($rzvy_rolepermissions['rzvy_servcies']) && isset($rzvy_rolepermissions['rzvy_addons'])){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'addons.php') != false && strpos($_SERVER['SCRIPT_NAME'], 'external-addons.php') == false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/addons.php">
				  <i class="fa fa-fw fa-th-list"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['services'])){ echo $rzvy_translangArr['services']; }else{ echo $rzvy_defaultlang['services']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['services'])){ echo $rzvy_translangArr['services']; }else{ echo $rzvy_defaultlang['services']; } ?></span>
			</li>
		<?php }else{} ?>
		<?php if($obj_settings->get_option('rzvy_gc_clientid')!='' && $obj_settings->get_option('rzvy_gc_clientsecret')!='' && $rzvy_loginutype=='staff'){ ?>
			<li class="<?php if (strpos($_SERVER['SCRIPT_NAME'], 's-gcal.php') != false) { echo 'rzvy_menubar_nav_active'; } ?>">
				<a href="<?php echo SITE_URL; ?>backend/s-gcal.php">
				<i class="fa fa-fw fa-calendar-plus-o"></i>
				<span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['google_calendar'])){ echo $rzvy_translangArr['google_calendar']; }else{ echo $rzvy_defaultlang['google_calendar']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['google_calendar'])){ echo $rzvy_translangArr['google_calendar']; }else{ echo $rzvy_defaultlang['google_calendar']; } ?></span>
			</li>
		<?php } ?>
		<!-- Setup -->
		<?php if(isset($rzvy_rolepermissions['rzvy_setup']) || $rzvy_loginutype=='admin'){ ?>
			<li <?php if ((strpos($_SERVER['SCRIPT_NAME'], 'setup.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'settings.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'coupons.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'frequently-discount.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'referral-setting.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'email-sms-templates.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'support-tickets.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'ticket-discussion.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'refund.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'reminder.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'manage-blockoff.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'gcal.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'role-permissions.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'export.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'color-scheme.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'form-fields.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'location-selector.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'feedback.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'embed.php') != false  || strpos($_SERVER['SCRIPT_NAME'], 'languages.php') != false)  && strpos($_SERVER['SCRIPT_NAME'], 's-gcal.php') == false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/setup.php">
				  <i class="fa fa-fw fa-cogs"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['setup'])){ echo $rzvy_translangArr['setup']; }else{ echo $rzvy_defaultlang['setup']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['setup'])){ echo $rzvy_translangArr['setup']; }else{ echo $rzvy_defaultlang['setup']; } ?></span>
			</li>
		<?php } ?>
    </ul>
  </div>
	<script>
	let rzvy_menubar_toggle = document.querySelector("#rzvy_menubar_toggle");
	let menubar = document.querySelector(".rzvy_menubar");
	rzvy_menubar_toggle.onclick = function() {
		menubar.classList.toggle("rzvy_menubar_active");
	}
	</script>
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