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
}else if($_SESSION['login_type'] == "staff") {
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/staff-profile.php";
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
if(!isset($_SESSION['customer_id'])) {
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/";
	</script>
	<?php 
	exit;
}
include(dirname(dirname(__FILE__))."/classes/class_settings.php");
include(dirname(dirname(__FILE__))."/classes/class_refund_request.php");

/* Create object of classes */
$obj_database->check_admin_setup_detail($conn);

$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

$obj_refund_request = new rzvy_refund_request();
$obj_refund_request->conn = $conn;

$dash_title = $obj_settings->get_option("rzvy_company_name");
$rzvy_allow_loyalty_points_status = $obj_settings->get_option('rzvy_allow_loyalty_points_status');

if($dash_title == ""){
	if(isset($rzvy_translangArr['dashboard'])){ $dash_title = $rzvy_translangArr['dashboard']; }else{ $dash_title = $rzvy_defaultlang['dashboard']; } 
}   

$saiframe = '';
if(isset($_GET['if'])){
  	$saiframe = '?if=y';  
} 
?>
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
	<link href="<?php echo SITE_URL; ?>includes/css/rzvy-customer.css?<?php echo time(); ?>" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/vendor/intl-tel-input/css/intlTelInput.css?<?php echo time(); ?>">
	<?php if (strpos($_SERVER['SCRIPT_NAME'], 'c_profile.php') != false) { ?>
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/front/css/datepicker.min.css?<?php echo time(); ?>" />
	<?php } ?>
	
	<link href="<?php echo SITE_URL; ?>includes/css/rzvy-menu.css?<?php echo time(); ?>" rel="stylesheet">
	
	<?php $Rzvy_Hooks->customerHeaderIncludes(); ?>
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
			<div></div>
			<ul class="navbar-nav">
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
					<a class="nav-link rzvy_menubar_usermenu_link" href="javascript:void(0)" role="button" data-toggle="dropdown" aria-expanded="false">
						<img src="<?php echo SITE_URL; ?>includes/images/staff-lg.png" />
					</a>
					<div class="dropdown-menu dropdown-menu-right rzvy_menubar_usermenu_link_dd">
						<span class="text-black px-2 rzvy_menubar_usermenu_link_title"><strong><?php $get_customer_name = $obj_settings->get_customer_name($_SESSION["customer_id"]);  if(mysqli_num_rows($get_customer_name)>0){ $__customername = mysqli_fetch_array($get_customer_name); echo ucwords($__customername["firstname"]." ".$__customername["lastname"]); } ?></strong> <small></small></span>
						<div class="dropdown-divider"></div>
						<a href="<?php echo SITE_URL; ?>backend/c_profile.php" class="dropdown-item ai-icon">
							<i class="fa fa-fw fa-user"></i>
							<span class="ml-2"><?php if(isset($rzvy_translangArr['profile'])){ echo $rzvy_translangArr['profile']; }else{ echo $rzvy_defaultlang['profile']; } ?></span>
						</a>
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
	
		<!-- Dashboard -->
		<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'c-dashboard.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
			<a href="<?php echo SITE_URL; ?>backend/c-dashboard.php<?php echo $saiframe; ?>">
			  <i class="fa fa-fw fa-tachometer"></i>
			  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['dashboard'])){ echo $rzvy_translangArr['dashboard']; }else{ echo $rzvy_defaultlang['dashboard']; } ?></span>
			</a>
			<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['dashboard'])){ echo $rzvy_translangArr['dashboard']; }else{ echo $rzvy_defaultlang['dashboard']; } ?></span>
		</li>
		
		<!-- My Appointments -->
		<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'my-appointments.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
			<a href="<?php echo SITE_URL; ?>backend/my-appointments.php<?php echo $saiframe; ?>">
			  <i class="fa fa-fw fa-calendar"></i>
			  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['my_appointments'])){ echo $rzvy_translangArr['my_appointments']; }else{ echo $rzvy_defaultlang['my_appointments']; } ?></span>
			</a>
			<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['my_appointments'])){ echo $rzvy_translangArr['my_appointments']; }else{ echo $rzvy_defaultlang['my_appointments']; } ?></span>
		</li>
		
		<!-- Refund -->
		<?php if($obj_settings->get_option('rzvy_refund_status') == "Y"){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'c_refund.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/c_refund.php">
				  <i class="fa fa-fw fa-exchange"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['refund'])){ echo $rzvy_translangArr['refund']; }else{ echo $rzvy_defaultlang['refund']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['refund'])){ echo $rzvy_translangArr['refund']; }else{ echo $rzvy_defaultlang['refund']; } ?></span>
			</li>
		<?php } ?>
		
		<!-- Support Tickets -->
		<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'c-support-tickets.php') != false || strpos($_SERVER['SCRIPT_NAME'], 'c-ticket-discussion.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
			<a href="<?php echo SITE_URL; ?>backend/c-support-tickets.php<?php echo $saiframe; ?>">
			  <i class="fa fa-fw fa-comments-o"></i>
			  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['support_tickets'])){ echo $rzvy_translangArr['support_tickets']; }else{ echo $rzvy_defaultlang['support_tickets']; } ?></span>
			</a>
			<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['support_tickets'])){ echo $rzvy_translangArr['support_tickets']; }else{ echo $rzvy_defaultlang['support_tickets']; } ?></span>
		</li>
				
		<!-- Loyalty Points -->
		<?php if($rzvy_allow_loyalty_points_status=='Y'){ ?>
			<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'c-loyalty-points.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
				<a href="<?php echo SITE_URL; ?>backend/c-loyalty-points.php<?php echo $saiframe; ?>">
				  <i class="fa fa-fw fa-money"></i>
				  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['loyalty_points'])){ echo $rzvy_translangArr['loyalty_points']; }else{ echo $rzvy_defaultlang['loyalty_points']; } ?></span>
				</a>
				<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['loyalty_points'])){ echo $rzvy_translangArr['loyalty_points']; }else{ echo $rzvy_defaultlang['loyalty_points']; } ?></span>
			</li>
		<?php } ?>
		
		<!-- Services Package -->
		<?php $Rzvy_Hooks->do_action('services_package_menu', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang)); ?>
		
		<?php if($obj_settings->get_option("rzvy_referral_discount_status")=='Y'){ ?>
		<!-- Referral Coupons -->
		<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'referral-coupons.php') != false && strpos($_SERVER['SCRIPT_NAME'], 'refer.php') == false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
			<a href="<?php echo SITE_URL; ?>backend/referral-coupons.php<?php echo $saiframe; ?>">
			  <i class="fa fa-fw fa-ticket"></i>
			  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['referral_coupons'])){ echo $rzvy_translangArr['referral_coupons']; }else{ echo $rzvy_defaultlang['referral_coupons']; } ?></span>
			</a>
			<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['referral_coupons'])){ echo $rzvy_translangArr['referral_coupons']; }else{ echo $rzvy_defaultlang['referral_coupons']; } ?></span>
		</li>
		
		<!-- Refer -->
		<li <?php if (strpos($_SERVER['SCRIPT_NAME'], 'refer.php') != false) { echo 'class="rzvy_menubar_nav_active"'; } ?>>
			<a href="<?php echo SITE_URL; ?>backend/refer.php<?php echo $saiframe; ?>">
			  <i class="fa fa-fw fa-gift"></i>
			  <span class="rzvy_menubar_nav_name"><?php if(isset($rzvy_translangArr['refer_a_friend'])){ echo $rzvy_translangArr['refer_a_friend']; }else{ echo $rzvy_defaultlang['refer_a_friend']; } ?></span>
			</a>
			<span class="rzvy_menubar_nav_tooltip"><?php if(isset($rzvy_translangArr['refer_a_friend'])){ echo $rzvy_translangArr['refer_a_friend']; }else{ echo $rzvy_defaultlang['refer_a_friend']; } ?></span>
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