<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_appointment_cron.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_loyalty_points.php");

/* Create object of classes */
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;
$obj_cron = new rzvy_appointment_cron();
$obj_cron->conn = $conn;
$obj_es_information = new rzvy_es_information();
$obj_es_information->conn = $conn;
$obj_loyalty_points = new rzvy_loyalty_points();
$obj_loyalty_points->conn = $conn;

$rzvy_settings_timezone = $obj_settings->get_option("rzvy_timezone");
$rzvy_server_timezone = date_default_timezone_get();
$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($rzvy_server_timezone,$rzvy_settings_timezone); 

$month = date("m", $currDateTime_withTZ);
$day = date("d", $currDateTime_withTZ);

$all_customers = $obj_cron->get_all_customers($month, $day);

/********************** Send SMS & Email code start ***************************/
if(mysqli_num_rows($all_customers)>0){
	while($cust = mysqli_fetch_assoc($all_customers)){
		$es_r_template = "birthday";
		$es_r_firstname = $cust['firstname'];
		$es_r_lastname = $cust['lastname'];
		$es_r_email = $cust['email'];
		$es_r_phone = $cust['phone'];
		include(dirname(dirname(__FILE__))."/lib/rzvy_send_birthday_sms_email_process.php");
		
		/* Loyalty Points */
		$rzvy_allow_loyalty_points_status = $obj_settings->get_option('allow_loyalty_points_status');
		if(isset($rzvy_allow_loyalty_points_status) && $rzvy_allow_loyalty_points_status=='Y'){
			$rewardedpoints = $obj_settings->get_option('rzvy_no_of_loyalty_point_as_birthday_gift');
			$newavailablepoints = $rewardedpoints;
			
			$availablepoints = $obj_loyalty_points->get_available_points_customer($cust['id']);
			
			if(isset($availablepoints) && $availablepoints!=''){
				$newavailablepoints += $availablepoints;
			}
			$obj_loyalty_points->add_loyalty_points_record("0", $cust['id'], 'A', $rewardedpoints, $newavailablepoints);
		}
	}
}
/********************** Send SMS & Email code END ****************************/