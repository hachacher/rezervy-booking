<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_staff.php");

/* Create object of classes */
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;
$obj_staff = new rzvy_staff();
$obj_staff->conn = $conn;

/* add staff schedule Ajax */
if(isset($_POST["add_advanceschedule"])){
	$id = $_POST['id'];
	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];
	$starttime = $_POST['starttime'];
	$endtime = $_POST['endtime'];
	$noofbooking = $_POST['noofbooking'];
	$status = $_POST['status'];
	$added = $obj_staff->add_staff_advanceschedule($id, $startdate, $enddate, $starttime, $endtime, $noofbooking, $status);
	if($added){
		echo "added";
	}
}

/* update staff schedule Ajax */
else if(isset($_POST["update_advanceschedule"])){
	$id = $_POST['id'];
	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];
	$starttime = $_POST['starttime'];
	$endtime = $_POST['endtime'];
	$noofbooking = $_POST['noofbooking'];
	$updated = $obj_staff->update_advanceschedule($id, $startdate, $enddate, $starttime, $endtime, $noofbooking);
	if($updated){
		echo "updated";
	}
}

/* update staff schedule status Ajax */
else if(isset($_POST["change_advanceschedule_status"])){
	$id = $_POST['id'];
	$status = $_POST['status'];
	$changed = $obj_staff->change_advanceschedule_status($id, $status);
	if($changed){
		echo "changed";
	}
}

/* delete staff schedule Ajax */
else if(isset($_POST["delete_advanceschedule"])){
	$id = $_POST['id'];
	$deleted = $obj_staff->delete_advanceschedule($id);
	if($deleted){
		echo "deleted";
	}
}

/* clone staff schedule Ajax */
else if(isset($_POST["clone_advanceschedule"])){
	$id = $_POST['id'];
	$cloned = $obj_staff->clone_advanceschedule($id);
	if($cloned){
		echo "cloned";
	}
}

/* get staff schedule Ajax */
else if(isset($_POST["get_advanceschedule"])){
	$id = $_POST['id'];
	$schedule = $obj_staff->get_advanceschedule($id);
	
	$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
	$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');
	$time_interval = $obj_settings->get_option('rzvy_timeslot_interval');
	$schedule_starttime = $schedule["starttime"];
	$schedule_endtime = $schedule["endtime"];
	$slot_options = $obj_staff->generate_slot_dropdown_options($time_interval, $rzvy_time_format, $schedule_starttime, $schedule_endtime); 
	
	$singleDatePicker = "false";
	if(strtotime($schedule["startdate"]) == strtotime($schedule["enddate"])){
		$singleDatePicker = "true";
	} 
	?>
	<script>
		$(function() {
			$('#rzvy_u_advanceschedule_daterange').daterangepicker({ minDate: moment(), startDate: moment(new Date("<?php echo date('Y/m/d', strtotime($schedule['startdate'])); ?>")), endDate: moment(new Date("<?php echo date('Y/m/d', strtotime($schedule['enddate'])); ?>")), singleDatePicker: <?php echo $singleDatePicker; ?> });
		});
		$('#rzvy_u_advanceschedule_daterange_choice').selectpicker();
		$('#rzvy_u_advanceschedule_starttime').selectpicker();
		$('#rzvy_u_advanceschedule_endtime').selectpicker();
	</script>
	<form name="rzvy_update_advanceschedule_form" id="rzvy_update_advanceschedule_form" method="post">
		<input id="rzvy_u_advanceschedule_id" name="rzvy_u_advanceschedule_id" type="hidden" value="<?php echo $schedule["id"]; ?>" />
		<div class="row">
			<div class="form-group col-md-12">
				<label><?php if(isset($rzvy_translangArr['advance_schedule_type'])){ echo $rzvy_translangArr['advance_schedule_type']; }else{ echo $rzvy_defaultlang['advance_schedule_type']; } ?></label>
				<select class="form-control selectpicker" id="rzvy_u_advanceschedule_daterange_choice" name="rzvy_u_advanceschedule_daterange_choice" data-startdate="<?php echo date('Y/m/d', strtotime($schedule['startdate'])); ?>" data-enddate="<?php echo date('Y/m/d', strtotime($schedule['enddate'])); ?>">
					<option value="date" <?php if($singleDatePicker == "true"){ echo "selected"; } ?>><?php echo (isset($rzvy_translangArr['date']))?$rzvy_translangArr['date']:$rzvy_defaultlang['date']; ?></option>
					<option value="daterange" <?php if($singleDatePicker == "false"){ echo "selected"; } ?>><?php echo (isset($rzvy_translangArr['daterange']))?$rzvy_translangArr['daterange']:$rzvy_defaultlang['daterange']; ?></option>
				</select>
			</div>
		</div>
		<div class="row">
		  <div class="form-group col-md-12">
			<label for="rzvy_u_advanceschedule_daterange"><?php if(isset($rzvy_translangArr['date_period'])){ echo $rzvy_translangArr['date_period']; }else{ echo $rzvy_defaultlang['date_period']; } ?></label>
			<input class="form-control" id="rzvy_u_advanceschedule_daterange" name="rzvy_u_advanceschedule_daterange" type="text" onfocus="this.blur()" />
		  </div>
		</div>
		<div class="row">
			<div class="form-group col-md-6">
				<label><?php if(isset($rzvy_translangArr['start_time'])){ echo $rzvy_translangArr['start_time']; }else{ echo $rzvy_defaultlang['start_time']; } ?></label>
				<select class="form-control selectpicker" id="rzvy_u_advanceschedule_starttime" name="rzvy_u_advanceschedule_starttime">
					<?php echo $slot_options["slot_starttime"]; ?>
				</select>
				<label for="rzvy_u_advanceschedule_starttime" generated="true" class="error"></label>
			</div>
			<div class="form-group col-md-6">
				<label><?php if(isset($rzvy_translangArr['end_time'])){ echo $rzvy_translangArr['end_time']; }else{ echo $rzvy_defaultlang['end_time']; } ?></label>
				<select class="form-control selectpicker" id="rzvy_u_advanceschedule_endtime" name="rzvy_u_advanceschedule_endtime">
					<?php echo $slot_options["slot_endtime"]; ?>
				</select>
				<label for="rzvy_u_advanceschedule_endtime" generated="true" class="error"></label>
			</div>
		</div>
		<div class="row">
		  <div class="form-group col-md-12">
			<label for="rzvy_u_advanceschedule_noofbooking"><?php if(isset($rzvy_translangArr['no_of_bookings'])){ echo $rzvy_translangArr['no_of_bookings']; }else{ echo $rzvy_defaultlang['no_of_bookings']; } ?></label>
			<input class="form-control" id="rzvy_u_advanceschedule_noofbooking" name="rzvy_u_advanceschedule_noofbooking" type="number" min="0" value="<?php echo $schedule["no_of_booking"]; ?>" />
		  </div>
		</div>
	</form>
	<?php 
}

/** Add Staff offday AJAX */
else if(isset($_POST["add_advanceschedule_daysoff"])){
    $obj_staff->schedule_id = $_POST['id'];
    $obj_staff->id = $_POST['sid'];
    $obj_staff->off_date = $_POST['off_date'];
    $added = $obj_staff->add_advanceschedule_daysoff();
    if($added){
        echo "added";
    }
}

/** Delete Staff offday AJAX */
else if(isset($_POST["delete_advanceschedule_daysoff"])){
    $obj_staff->id = $_POST['id'];
    $deleted = $obj_staff->delete_advanceschedule_daysoff();
    if($deleted){
        echo "deleted";
    }
}

/** Add Staff break AJAX */
else if(isset($_POST["add_advanceschedule_break"])){
    $obj_staff->schedule_id = $_POST['id'];
    $obj_staff->id = $_POST['sid'];
    $obj_staff->startdate = $_POST['startdate'];
    $obj_staff->enddate = $_POST['enddate'];
    $obj_staff->break_start = $_POST['break_start'];
    $obj_staff->break_end = $_POST['break_end'];
    $added = $obj_staff->add_advanceschedule_break();
    if($added){
        echo "added";
    }
}
/** Delete Staff break AJAX */
else if(isset($_POST["delete_advanceschedule_break"])){
    $obj_staff->id = $_POST['id'];
    $deleted = $obj_staff->delete_advanceschedule_break();
    if($deleted){
        echo "deleted";
    }
}

/*****************************************************/
/* add staff breaks Ajax */
else if(isset($_POST["add_staff_breaks"])){
	$obj_staff->id = $_POST['id'];
	$obj_staff->weekday_id = $_POST['dayid'];
	$obj_staff->break_start = $_POST['break_start'];
    $obj_staff->break_end = $_POST['break_end'];
    $added = $obj_staff->add_staff_breaks();
    if($added){
        echo "added";
    }
}

/*** Add Staff Days off Ajax */
else if(isset($_POST["add_staff_daysoff"])){
    $obj_staff->id = $_POST['id'];
    $obj_staff->off_date = $_POST["off_date"];
    $added = $obj_staff->add_staff_daysoff();
    if($added){
        echo "added";
    }
}

/** Delete Staff daysoff AJAX */
else if(isset($_POST["delete_staffdaysoff"])){
    $obj_staff->id = $_POST['id'];
    $deleted = $obj_staff->delete_staffdaysoff();
    if($deleted){
        echo "deleted";
    }
}

/** Set staff calendar */
else if(isset($_POST["set_staff_calendar"])){
    $_SESSION["rzvy_staff_calendar"] = $_POST["id"];
}

else if(isset($_POST["update_break_data_content"])){ 
    $rzvy_time_format = $obj_settings->get_option('rzvy_time_format');
    $time_interval = $obj_settings->get_option('rzvy_timeslot_interval');
	$weekday_id = $_POST["weekday_id"];
	$schedule_starttime = $_POST['start_time'];
	$schedule_endtime = $_POST['end_time'];
	$slot_options = $obj_staff->generate_break_dropdown_options($time_interval, $rzvy_time_format, $schedule_starttime, $schedule_endtime); 

	?>
	<div class="col-md-12"> 
		<div class="form-group row"> 
			<div class="col-md-6"> 
				<label class="control-label"><?php if(isset($rzvy_translangArr['break_start'])){ echo $rzvy_translangArr['break_start']; }else{ echo $rzvy_defaultlang['break_start']; } ?></label> 
				<select class="form-control" id="rzvy_addbreak_starttime_<?php echo $weekday_id; ?>" name="rzvy_addbreak_starttime_<?php echo $weekday_id; ?>">
					<?php echo $slot_options["break_starttime"]; ?>
				</select> 
			</div> 
			<div class="col-md-6">
				<label class="control-label"><?php if(isset($rzvy_translangArr['break_end'])){ echo $rzvy_translangArr['break_end']; }else{ echo $rzvy_defaultlang['break_end']; } ?></label> 
				<select class="form-control" id="rzvy_addbreak_endtime_<?php echo $weekday_id; ?>" name="rzvy_addbreak_endtime_<?php echo $weekday_id; ?>">
					<?php echo $slot_options["break_endtime"]; ?>
				</select> 
			</div> 
		</div> 
	</div>
	<?php  
}