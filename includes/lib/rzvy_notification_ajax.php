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
$datetime_format = $rzvy_date_format." ".$rzvy_time_format;
$rzvy_book_with_datetime = $obj_settings->get_option("rzvy_book_with_datetime");

if(isset($rzvy_translangArr['pending'])){ $label_pending = $rzvy_translangArr['pending']; }else{ $label_pending = $rzvy_defaultlang['pending']; }
if(isset($rzvy_translangArr['confirmed_by_you'])){ $label_confirmed = $rzvy_translangArr['confirmed_by_you']; }else{ $label_confirmed = $rzvy_defaultlang['confirmed_by_you']; }
if(isset($rzvy_translangArr['confirmed_by_staff'])){ $label_confirmed_by_staff = $rzvy_translangArr['confirmed_by_staff']; }else{ $label_confirmed_by_staff = $rzvy_defaultlang['confirmed_by_staff']; }
if(isset($rzvy_translangArr['rescheduled_by_customer'])){ $label_rescheduled_by_customer = $rzvy_translangArr['rescheduled_by_customer']; }else{ $label_rescheduled_by_customer = $rzvy_defaultlang['rescheduled_by_customer']; }
if(isset($rzvy_translangArr['rescheduled_by_you'])){ $label_rescheduled_by_you = $rzvy_translangArr['rescheduled_by_you']; }else{ $label_rescheduled_by_you = $rzvy_defaultlang['rescheduled_by_you']; }
if(isset($rzvy_translangArr['rescheduled_by_staff'])){ $label_rescheduled_by_staff = $rzvy_translangArr['rescheduled_by_staff']; }else{ $label_rescheduled_by_staff = $rzvy_defaultlang['rescheduled_by_staff']; }
if(isset($rzvy_translangArr['cancelled_by_customer'])){ $label_cancelled_by_customer = $rzvy_translangArr['cancelled_by_customer']; }else{ $label_cancelled_by_customer = $rzvy_defaultlang['cancelled_by_customer']; }
if(isset($rzvy_translangArr['rejected_by_you'])){ $label_rejected_by_you = $rzvy_translangArr['rejected_by_you']; }else{ $label_rejected_by_you = $rzvy_defaultlang['rejected_by_you']; }
if(isset($rzvy_translangArr['rejected_by_staff'])){ $label_rejected_by_staff = $rzvy_translangArr['rejected_by_staff']; }else{ $label_rejected_by_staff = $rzvy_defaultlang['rejected_by_staff']; }
if(isset($rzvy_translangArr['completed'])){ $label_completed = $rzvy_translangArr['completed']; }else{ $label_completed = $rzvy_defaultlang['completed']; }
if(isset($rzvy_translangArr['mark_as_noshow'])){ $label_noshow = $rzvy_translangArr['mark_as_noshow']; }else{ $label_noshow = $rzvy_defaultlang['mark_as_noshow']; }
if(isset($rzvy_translangArr['with'])){ $label_with = $rzvy_translangArr['with']; }else{ $label_with = $rzvy_defaultlang['with']; }
if(isset($rzvy_translangArr['on'])){ $label_on = $rzvy_translangArr['on']; }else{ $label_on = $rzvy_defaultlang['on']; }

if(isset($_POST['get_notification_appointment_detail'])){	
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
		"mark_as_noshow" => "#F70D1A",
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
	/*** Fetch Appointment Status color END ***/ 
	?>
	<center><h5 class="dropdown-menu-titles"><?php if(isset($rzvy_translangArr['new_appointments'])){ echo $rzvy_translangArr['new_appointments']; }else{ echo $rzvy_defaultlang['new_appointments']; } ?></h5><a href="javascript:void(0)" class="rzvy_clear_notifications_link"><?php if(isset($rzvy_translangArr['clear_all'])){ echo $rzvy_translangArr['clear_all']; }else{ echo $rzvy_defaultlang['clear_all']; } ?></a></center>
	<div class="dropdown-divider"></div>
	<?php
	$all_appointments = $obj_bookings->get_all_latest_unread_appointments();
	$status_array = array(
		'pending' => array(
			"status" => $label_pending,
			"icon" => '<i class="fa fa-info-circle" title="'.$label_pending.'"></i> ',
			"color" => $defaultApptStatusColorScheme["pending"],
		),
		'confirmed' => array(
			"status" => $label_confirmed,
			"icon" => '<i class="fa fa-check" title="'.$label_confirmed.'"></i> ',
			"color" => $defaultApptStatusColorScheme["confirmed"],
		),
		'confirmed_by_staff' => array(
			"status" => $label_confirmed_by_staff,
			"icon" => '<i class="fa fa-check" title="'.$label_confirmed_by_staff.'"></i> ',
			"color" => $defaultApptStatusColorScheme["confirmed_by_staff"],
		),
		'rescheduled_by_customer' => array(
			"status" => $label_rescheduled_by_customer,
			"icon" => '<i class="fa fa-refresh" title="'.$label_rescheduled_by_customer.'"></i> ',
			"color" => $defaultApptStatusColorScheme["rescheduled_by_customer"],
		),
		'rescheduled_by_you' => array(
			"status" => $label_rescheduled_by_you,
			"icon" => '<i class="fa fa-repeat" title="'.$label_rescheduled_by_you.'"></i> ',
			"color" => $defaultApptStatusColorScheme["rescheduled_by_you"],
		),
		'rescheduled_by_staff' => array(
			"status" => $label_rescheduled_by_staff,
			"icon" => '<i class="fa fa-repeat" title="'.$label_rescheduled_by_staff.'"></i> ',
			"color" => $defaultApptStatusColorScheme["rescheduled_by_staff"],
		),
		'cancelled_by_customer' => array(
			"status" => $label_cancelled_by_customer,
			"icon" => '<i class="fa fa-close" title="'.$label_cancelled_by_customer.'"></i> ',
			"color" => $defaultApptStatusColorScheme["cancelled_by_customer"],
		),
		'rejected_by_you' => array(
			"status" => $label_rejected_by_you,
			"icon" => '<i class="fa fa-ban" title="'.$label_rejected_by_you.'"></i> ',
			"color" => $defaultApptStatusColorScheme["rejected_by_you"],
		),
		'rejected_by_staff' => array(
			"status" => $label_rejected_by_staff,
			"icon" => '<i class="fa fa-ban" title="'.$label_rejected_by_staff.'"></i> ',
			"color" => $defaultApptStatusColorScheme["rejected_by_staff"],
		),
		'completed' => array(
			"status" => $label_completed,
			"icon" => '<i class="fa fa-calendar-check-o" title="'.$label_completed.'"></i> ',
			"color" => $defaultApptStatusColorScheme["completed"],
		),
		'mark_as_noshow' => array(
			"status" => $label_noshow,
			"icon" => '<i class="fa fa-calendar-check-o" title="'.$label_noshow.'"></i> ',
			"color" => $defaultApptStatusColorScheme["mark_as_noshow"],
		)
	);
	if(mysqli_num_rows($all_appointments)>0){
		while($appointment = mysqli_fetch_array($all_appointments)){
			$customer_name = ucwords($appointment['c_firstname']." ".$appointment['c_lastname']);
			if($rzvy_book_with_datetime == "Y"){
				$event_title = "<b>".$appointment['cat_name'].":</b> ".$appointment['title']." ".$label_with." ".$customer_name." ".$label_on." <b>".date($datetime_format, strtotime($appointment['booking_datetime']))."</b>";
			}else{
				$event_title = "<b>".$appointment['cat_name'].":</b> ".$appointment['title']." ".$label_with." ".$customer_name;
			}
			?>
			<div class="rzvy-notification-appointment-modal-link" data-id="<?php echo $appointment['order_id']; ?>">
				<div class="row">
					<div class="col-md-12">
						<strong style="color: <?php echo $status_array[$appointment['booking_status']]['color']; ?>"><?php echo $status_array[$appointment['booking_status']]['icon']; echo $status_array[$appointment['booking_status']]['status']; ?></strong>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<span class="rzvy_noti_deatil"><?php echo $event_title; ?></span>
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			<?php
		}
	}else{
		?>
		<center><?php if(isset($rzvy_translangArr['opps_you_have_no_unread_notifications'])){ echo $rzvy_translangArr['opps_you_have_no_unread_notifications']; }else{ echo $rzvy_defaultlang['opps_you_have_no_unread_notifications']; } ?></center>
		<div class="dropdown-divider"></div>
		<?php
	}
}
else if(isset($_POST['mark_appointment_as_read'])){
	$obj_bookings->order_id = $_POST['order_id'];
	$updated = $obj_bookings->mark_appointment_as_read();
	if($updated){
		echo "updated";
	}
}