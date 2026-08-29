<?php 
include(dirname(__FILE__)."/constants.php"); 

$obj_database->check_admin_setup_detail($conn);

/* Include class files */
include(dirname(__FILE__)."/classes/class_frontend.php");
include(dirname(__FILE__)."/classes/class_settings.php");

/* Create object of classes */
$obj_frontend = new rzvy_frontend();
$obj_frontend->conn = $conn; 

$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;
 
$saiframe = '';
if(isset($_GET['if'])){
	$saiframe = '?if=y';  
}
?>
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
		?>
		<title><?php if($rzvy_seo_meta_tag != ""){ echo $rzvy_seo_meta_tag; }else{ echo $obj_settings->get_option("rzvy_company_name"); } ?></title>
		<?php 
		if($rzvy_seo_meta_description != ''){ 
			?>
			<meta name="description" content="<?php echo $rzvy_seo_meta_description; ?>">
			<?php 
		} 
		?>
		<?php include("backend/bf_css.php"); ?>
		<link href="<?php echo SITE_URL; ?>includes/css/rzvy-thankyou.css" rel="stylesheet" type="text/css" media="all">
		<link href="<?php echo SITE_URL; ?>includes/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">		
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/front/css/font-awesome.min.css?<?php echo time(); ?>" />
		<script src="<?php echo SITE_URL; ?>includes/front/js/jquery-3.2.1.min.js?<?php echo time(); ?>"></script>
		<style>
			.rzvy .jumbotron{
				background-color: #ffffff;
			}
		</style>
		<?php if(!isset($_GET['cals'])){ ?>
		<!-- Custom scripts -->
		<script type="text/javascript">
			var timer = 5; /* seconds */
			frontpage = '<?php echo SITE_URL.$saiframe; ?>';
			function delayer() {
				window.location = frontpage;
			}
			setTimeout('delayer()', 1000 * timer);
		</script>
		<?php }else{ ?>
		<script>
		$(document).on("click", "#rzvy_ical_booking_info_download", function(){
			window.open( "data:text/calendar;charset=utf8," + escape($('#rzvy_ical_booking_info').text()));
		});
		</script>
		<?php } ?>
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
	<body class="rzvy">
		<center id="rzvy-fifth-step" class="mx-lg-0 mx-4">
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
	</body>
</html>