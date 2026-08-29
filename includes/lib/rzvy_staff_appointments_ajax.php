<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_bookings.php");

/* Create object of classes */
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;
$obj_bookings = new rzvy_bookings();
$obj_bookings->conn = $conn;

$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');
$rzvy_book_with_datetime = $obj_settings->get_option("rzvy_book_with_datetime");

$appointments = array();
$obj_bookings->staff_id = $_SESSION["staff_id"];
$all_appointments = $obj_bookings->get_all_staff_appointments();

if(isset($rzvy_translangArr['pending'])){ $label_pending = $rzvy_translangArr['pending']; }else{ $label_pending = $rzvy_defaultlang['pending']; }
if(isset($rzvy_translangArr['confirmed_by_you'])){ $label_confirmed_by_you = $rzvy_translangArr['confirmed_by_you']; }else{ $label_confirmed_by_you = $rzvy_defaultlang['confirmed_by_you']; }
if(isset($rzvy_translangArr['confirmed_by_admin'])){ $label_confirmed_by_admin = $rzvy_translangArr['confirmed_by_admin']; }else{ $label_confirmed_by_admin = $rzvy_defaultlang['confirmed_by_admin']; }
if(isset($rzvy_translangArr['rescheduled_by_customer'])){ $label_rescheduled_by_customer = $rzvy_translangArr['rescheduled_by_customer']; }else{ $label_rescheduled_by_customer = $rzvy_defaultlang['rescheduled_by_customer']; }
if(isset($rzvy_translangArr['rescheduled_by_you'])){ $label_rescheduled_by_you = $rzvy_translangArr['rescheduled_by_you']; }else{ $label_rescheduled_by_you = $rzvy_defaultlang['rescheduled_by_you']; }
if(isset($rzvy_translangArr['rescheduled_by_admin'])){ $label_rescheduled_by_admin = $rzvy_translangArr['rescheduled_by_admin']; }else{ $label_rescheduled_by_admin = $rzvy_defaultlang['rescheduled_by_admin']; }
if(isset($rzvy_translangArr['cancelled_by_customer'])){ $label_cancelled_by_customer = $rzvy_translangArr['cancelled_by_customer']; }else{ $label_cancelled_by_customer = $rzvy_defaultlang['cancelled_by_customer']; }
if(isset($rzvy_translangArr['rejected_by_you'])){ $label_rejected_by_you = $rzvy_translangArr['rejected_by_you']; }else{ $label_rejected_by_you = $rzvy_defaultlang['rejected_by_you']; }
if(isset($rzvy_translangArr['rejected_by_admin'])){ $label_rejected_by_admin = $rzvy_translangArr['rejected_by_admin']; }else{ $label_rejected_by_admin = $rzvy_defaultlang['rejected_by_admin']; }
if(isset($rzvy_translangArr['completed'])){ $label_completed = $rzvy_translangArr['completed']; }else{ $label_completed = $rzvy_defaultlang['completed']; }
if(isset($rzvy_translangArr['mark_as_noshow'])){ $label_noshow = $rzvy_translangArr['mark_as_noshow']; }else{ $label_noshow = $rzvy_defaultlang['mark_as_noshow']; }
if(isset($rzvy_translangArr['rating_pending'])){ $label_rating_pending = $rzvy_translangArr['rating_pending']; }else{ $label_rating_pending = $rzvy_defaultlang['rating_pending']; }
if(isset($rzvy_translangArr['with'])){ $label_with = $rzvy_translangArr['with']; }else{ $label_with = $rzvy_defaultlang['with']; }
if(isset($rzvy_translangArr['on'])){ $label_on = $rzvy_translangArr['on']; }else{ $label_on = $rzvy_defaultlang['on']; }
if(isset($rzvy_translangArr['to'])){ $label_to = $rzvy_translangArr['to']; }else{ $label_to = $rzvy_defaultlang['to']; }
if(isset($rzvy_translangArr['for'])){ $label_for = $rzvy_translangArr['for']; }else{ $label_for = $rzvy_defaultlang['for']; }

/*** Fetch Appointment Status color START ***/
$defaultApptStatusColorScheme = array(
	"pending" => "#1589FF",
	"confirmed" => "#008000",
	"confirmed_by_staff" => "#008000",
	"rescheduled_by_customer" => "#04B4AE",
	"rescheduled_by_you" => "#6960EC",
	"rescheduled_by_staff" => "#6960EC",
	"cancelled_by_customer" => "#FF4500",
	"rejected_by_you" => "#F70D1A",
	"rejected_by_staff" => "#F70D1A",
	"completed" => "#b7950b",
	"mark_as_noshow" => "#6960EC",
);
$rzvy_apptstatus_colorscheme = $obj_settings->get_option("rzvy_apptstatus_colorscheme");
if($rzvy_apptstatus_colorscheme != ""){
	$astatuscscheme = json_decode(htmlspecialchars_decode($rzvy_apptstatus_colorscheme));
	if ($astatuscscheme !== false) {
		if(isset($astatuscscheme->pending) && $astatuscscheme->pending != ""){ $defaultApptStatusColorScheme["pending"] = $astatuscscheme->pending; }
		if(isset($astatuscscheme->confirmed) && $astatuscscheme->confirmed != ""){ $defaultApptStatusColorScheme["confirmed"] = $astatuscscheme->confirmed; }
		if(isset($astatuscscheme->confirmed_by_staff) && $astatuscscheme->confirmed_by_staff != ""){ $defaultApptStatusColorScheme["confirmed_by_staff"] = $astatuscscheme->confirmed_by_staff; }
		if(isset($astatuscscheme->rescheduled_by_customer) && $astatuscscheme->rescheduled_by_customer != ""){ $defaultApptStatusColorScheme["rescheduled_by_customer"] = $astatuscscheme->rescheduled_by_customer; }
		if(isset($astatuscscheme->rescheduled_by_you) && $astatuscscheme->rescheduled_by_you != ""){ $defaultApptStatusColorScheme["rescheduled_by_you"] = $astatuscscheme->rescheduled_by_you; }
		if(isset($astatuscscheme->rescheduled_by_staff) && $astatuscscheme->rescheduled_by_staff != ""){ $defaultApptStatusColorScheme["rescheduled_by_staff"] = $astatuscscheme->rescheduled_by_staff; }
		if(isset($astatuscscheme->cancelled_by_customer) && $astatuscscheme->cancelled_by_customer != ""){ $defaultApptStatusColorScheme["cancelled_by_customer"] = $astatuscscheme->cancelled_by_customer; }
		if(isset($astatuscscheme->rejected_by_you) && $astatuscscheme->rejected_by_you != ""){ $defaultApptStatusColorScheme["rejected_by_you"] = $astatuscscheme->rejected_by_you; }
		if(isset($astatuscscheme->rejected_by_staff) && $astatuscscheme->rejected_by_staff != ""){ $defaultApptStatusColorScheme["rejected_by_staff"] = $astatuscscheme->rejected_by_staff; }
		if(isset($astatuscscheme->completed) && $astatuscscheme->completed != ""){ $defaultApptStatusColorScheme["completed"] = $astatuscscheme->completed; }
		if(isset($astatuscscheme->mark_as_noshow) && $astatuscscheme->mark_as_noshow != ""){ $defaultApptStatusColorScheme["mark_as_noshow"] = $astatuscscheme->mark_as_noshow; }
	}
}
$defaultApptStatusTextColorScheme = array(
	"pending" => "#FFFFFF",
	"confirmed" => "#FFFFFF",
	"confirmed_by_staff" => "#FFFFFF",
	"rescheduled_by_customer" => "#FFFFFF",
	"rescheduled_by_you" => "#FFFFFF",
	"rescheduled_by_staff" => "#FFFFFF",
	"cancelled_by_customer" => "#FFFFFF",
	"rejected_by_you" => "#FFFFFF",
	"rejected_by_staff" => "#FFFFFF",
	"completed" => "#FFFFFF",
	"mark_as_noshow" => "#FFFFFF",
);
$rzvy_apptstatus_text_colorscheme = $obj_settings->get_option("rzvy_apptstatus_text_colorscheme");
if($rzvy_apptstatus_text_colorscheme != ""){
	$astatutscscheme = json_decode(htmlspecialchars_decode($rzvy_apptstatus_text_colorscheme));
	if ($astatutscscheme !== false) {
		if(isset($astatutscscheme->pending) && $astatutscscheme->pending != ""){ $defaultApptStatusTextColorScheme["pending"] = $astatutscscheme->pending; }
		if(isset($astatutscscheme->confirmed) && $astatutscscheme->confirmed != ""){ $defaultApptStatusTextColorScheme["confirmed"] = $astatutscscheme->confirmed; }
		if(isset($astatutscscheme->confirmed_by_staff) && $astatutscscheme->confirmed_by_staff != ""){ $defaultApptStatusTextColorScheme["confirmed_by_staff"] = $astatutscscheme->confirmed_by_staff; }
		if(isset($astatutscscheme->rescheduled_by_customer) && $astatutscscheme->rescheduled_by_customer != ""){ $defaultApptStatusTextColorScheme["rescheduled_by_customer"] = $astatutscscheme->rescheduled_by_customer; }
		if(isset($astatutscscheme->rescheduled_by_you) && $astatutscscheme->rescheduled_by_you != ""){ $defaultApptStatusTextColorScheme["rescheduled_by_you"] = $astatutscscheme->rescheduled_by_you; }
		if(isset($astatutscscheme->rescheduled_by_staff) && $astatutscscheme->rescheduled_by_staff != ""){ $defaultApptStatusTextColorScheme["rescheduled_by_staff"] = $astatutscscheme->rescheduled_by_staff; }
		if(isset($astatutscscheme->cancelled_by_customer) && $astatutscscheme->cancelled_by_customer != ""){ $defaultApptStatusTextColorScheme["cancelled_by_customer"] = $astatutscscheme->cancelled_by_customer; }
		if(isset($astatutscscheme->rejected_by_you) && $astatutscscheme->rejected_by_you != ""){ $defaultApptStatusTextColorScheme["rejected_by_you"] = $astatutscscheme->rejected_by_you; }
		if(isset($astatutscscheme->rejected_by_staff) && $astatutscscheme->rejected_by_staff != ""){ $defaultApptStatusTextColorScheme["rejected_by_staff"] = $astatutscscheme->rejected_by_staff; }
		if(isset($astatutscscheme->completed) && $astatutscscheme->completed != ""){ $defaultApptStatusTextColorScheme["completed"] = $astatutscscheme->completed; }
		if(isset($astatutscscheme->mark_as_noshow) && $astatutscscheme->mark_as_noshow != ""){ $defaultApptStatusTextColorScheme["mark_as_noshow"] = $astatutscscheme->mark_as_noshow; }
	}
}
/*** Fetch Appointment Status color END ***/

$status_array = array(
	'pending' => array(
		"status" => $label_pending,
		"icon" => '<i class="fa fa-info-circle" title="'.$label_pending.'"></i>',
		"color" => $defaultApptStatusColorScheme["pending"],
		"textcolor" => $defaultApptStatusTextColorScheme["pending"],
	),
	'confirmed' => array(
		"status" => $label_confirmed_by_admin,
		"icon" => '<i class="fa fa-check" title="'.$label_confirmed_by_admin.'"></i>',
		"color" => $defaultApptStatusColorScheme["confirmed"],
		"textcolor" => $defaultApptStatusTextColorScheme["confirmed"],
	),
	'confirmed_by_staff' => array(
		"status" => $label_confirmed_by_you,
		"icon" => '<i class="fa fa-check" title="'.$label_confirmed_by_you.'"></i>',
		"color" => $defaultApptStatusColorScheme["confirmed_by_staff"],
		"textcolor" => $defaultApptStatusTextColorScheme["confirmed_by_staff"],
	),
	'rescheduled_by_customer' => array(
		"status" => $label_rescheduled_by_customer,
		"icon" => '<i class="fa fa-refresh" title="'.$label_rescheduled_by_customer.'"></i>',
		"color" => $defaultApptStatusColorScheme["rescheduled_by_customer"],
		"textcolor" => $defaultApptStatusTextColorScheme["rescheduled_by_customer"],
	),
	'rescheduled_by_you' => array(
		"status" => $label_rescheduled_by_admin,
		"icon" => '<i class="fa fa-repeat" title="'.$label_rescheduled_by_admin.'"></i>',
		"color" => $defaultApptStatusColorScheme["rescheduled_by_you"],
		"textcolor" => $defaultApptStatusTextColorScheme["rescheduled_by_you"],
	),
	'rescheduled_by_staff' => array(
		"status" => $label_rescheduled_by_you,
		"icon" => '<i class="fa fa-repeat" title="'.$label_rescheduled_by_you.'"></i>',
		"color" => $defaultApptStatusColorScheme["rescheduled_by_staff"],
		"textcolor" => $defaultApptStatusTextColorScheme["rescheduled_by_staff"],
	),
	'cancelled_by_customer' => array(
		"status" => $label_cancelled_by_customer,
		"icon" => '<i class="fa fa-close" title="'.$label_cancelled_by_customer.'"></i>',
		"color" => $defaultApptStatusColorScheme["cancelled_by_customer"],
		"textcolor" => $defaultApptStatusTextColorScheme["cancelled_by_customer"],
	),
	'rejected_by_you' => array(
		"status" => $label_rejected_by_admin,
		"icon" => '<i class="fa fa-ban" title="'.$label_rejected_by_admin.'"></i>',
		"color" => $defaultApptStatusColorScheme["rejected_by_you"],
		"textcolor" => $defaultApptStatusTextColorScheme["rejected_by_you"],
	),
	'rejected_by_staff' => array(
		"status" => $label_rejected_by_you,
		"icon" => '<i class="fa fa-ban" title="'.$label_rejected_by_you.'"></i>',
		"color" => $defaultApptStatusColorScheme["rejected_by_staff"],
		"textcolor" => $defaultApptStatusTextColorScheme["rejected_by_staff"],
	),
	'completed' => array(
		"status" => $label_completed,
		"icon" => '<i class="fa fa-calendar-check-o" title="'.$label_completed.'"></i>',
		"color" => $defaultApptStatusColorScheme["completed"],
		"textcolor" => $defaultApptStatusTextColorScheme["completed"],
	),
	'mark_as_noshow' => array(
		"status" => $label_noshow,
		"icon" => '<i class="fa fa-calendar-check-o" title="'.$label_noshow.'"></i>',
		"color" => $defaultApptStatusColorScheme["mark_as_noshow"],
		"textcolor" => $defaultApptStatusTextColorScheme["mark_as_noshow"],
	)
);
while($appointment = mysqli_fetch_array($all_appointments)){
	$obj_bookings->staff_id = $appointment['staff_id'];
	$get_staffname = $obj_bookings->readone_staff();
	$staff_name = " ".$label_with." ".ucwords($get_staffname['firstname']." ".$get_staffname['lastname']);

	$customer_name = ucwords($appointment['c_firstname']." ".$appointment['c_lastname']);
	if($customer_name != "" && $customer_name != " "){
		$cnames = $customer_name." ".$label_for." ";
	}else{
		$cnames = "";
	}

	if($rzvy_book_with_datetime == "Y"){
		$event_title = $cnames.$appointment['title']." ".$staff_name." ".$label_on." ".date($rzvy_time_format, strtotime($appointment['booking_datetime']))." ".$label_to." ".date($rzvy_time_format, strtotime($appointment['booking_end_datetime']));
	}else{
		$event_title = $cnames.$appointment['title']." ".$staff_name;
	}
	
	$get_feedback = $obj_bookings->get_appointment_rating($appointment['order_id']);
	$ratings = "";
	if(mysqli_num_rows($get_feedback)>0){
		$feedback = mysqli_fetch_array($get_feedback);
		if($feedback['rating']>0){
			for($star_i=0;$star_i<$feedback['rating'];$star_i++){ 
				$ratings .= '<i class="fa fa-star" aria-hidden="true"></i>';
			} 
			for($star_j=0;$star_j<(5-$feedback['rating']);$star_j++){ 
				$ratings .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
			} 
		}else{ 
			$ratings .= '<i class="fa fa-star-o" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i>';
		} 
	}else{
		$ratings .= '<i class="fa fa-star-o" aria-hidden="true"></i> Rating pending';
	} 
	
	$appointment_array = array(
		  "id" => $appointment['order_id'],
		  "cat_name" => $appointment['cat_name'],
		  "title" => $event_title,
		  "start" => $appointment['booking_datetime'],
		  "end" => $appointment['booking_end_datetime'],
		  "customer_name" => $customer_name,
		  "customer_phone" => $appointment['c_phone'],
		  "customer_email" => $appointment['c_email'],
		  "event_status" => $status_array[$appointment['booking_status']]['status'],
		  "event_icon" => $status_array[$appointment['booking_status']]['icon'],
		  "color" => $status_array[$appointment['booking_status']]['color'],
		  "textColor" => $status_array[$appointment['booking_status']]['textcolor'],
		  "rating" => $ratings
	);
	array_push($appointments,$appointment_array);
}
echo json_encode($appointments);