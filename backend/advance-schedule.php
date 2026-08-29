<?php 
if(!session_id()) { @session_start(); }
if($_SESSION['login_type']=='staff' && !isset($_SESSION['role_permissions']['rzvy_staff_as'])) {
	include 'staff-header.php';
	if(isset($_GET["id"]) && is_numeric($_GET["id"]) && $_SESSION['staff_id'] != $_GET["id"]){
		?>
		<script>
		window.location.href = "<?php echo SITE_URL; ?>backend/staff-profile.php";
		</script>
		<?php 
		exit;
	}
}else{
	include 'header.php';
}
if(!isset($rzvy_rolepermissions['rzvy_staff_as']) && $rzvy_loginutype=='staff' && isset($_SESSION['staff_id']) && $_SESSION['staff_id'] != $_GET["id"]){ ?>
	<div class="container mt-12">
		  <div class="row mt-5"><div class="col-md-12">&nbsp;</div></div>
          <div class="row mt-5">
               <div class="col-md-2 text-center mt-5">
                  <i class="fa fa-exclamation-triangle fa-5x"></i>
               </div>
               <div class="col-md-10 mt-5">
                   <p><?php if(isset($rzvy_translangArr['permission_error_message'])){ echo $rzvy_translangArr['permission_error_message']; }else{ echo $rzvy_defaultlang['permission_error_message']; } ?></p>                    
               </div>
          </div>
     </div>		
<?php die(); }
if(!isset($_GET["id"])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/staff.php";
	</script>
	<?php 
	exit;
}else if(!is_numeric($_GET["id"])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/staff.php";
	</script>
	<?php 
	exit;
}else{}
$staffid = $_GET["id"];
$obj_staff->id = $staffid;
$is_staff_exist = $obj_staff->check_staff_exist(); 
if(!$is_staff_exist){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/staff.php";
	</script>
	<?php 
	exit;
}

$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');
$time_interval = $obj_settings->get_option('rzvy_timeslot_interval');
$schedule_starttime = "09:00:00";
$schedule_endtime = "18:00:00";
$slot_options = $obj_staff->generate_slot_dropdown_options($time_interval, $rzvy_time_format, $schedule_starttime, $schedule_endtime);

$staff_advance_schedule = $obj_staff->getall_advance_schedule(); 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <?php if($_SESSION['login_type'] == "staff"){ ?>
			<li class="breadcrumb-item">
			  <a href="<?php echo SITE_URL; ?>backend/s-dashboard.php"><i class="fa fa-home"></i></a>
			</li>
			<li class="breadcrumb-item">
			  <a href="<?php echo SITE_URL; ?>backend/staff-profile.php"><?php if(isset($rzvy_translangArr['profile'])){ echo $rzvy_translangArr['profile']; }else{ echo $rzvy_defaultlang['profile']; } ?></a>
			</li>
        <?php }else{ ?>
			<li class="breadcrumb-item">
			  <a href="<?php echo SITE_URL; ?>backend/dashboard.php"><i class="fa fa-home"></i></a>
			</li>
			<li class="breadcrumb-item">
			  <a href="<?php echo SITE_URL; ?>backend/staff.php"><?php if(isset($rzvy_translangArr['staff'])){ echo $rzvy_translangArr['staff']; }else{ echo $rzvy_defaultlang['staff']; } ?></a>
			</li>
        <?php } ?>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['manage_advance_schedule'])){ echo $rzvy_translangArr['manage_advance_schedule']; }else{ echo $rzvy_defaultlang['manage_advance_schedule']; } ?></li>
      </ol>
	  <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-calendar-plus-o"></i> <?php if(isset($rzvy_translangArr['staff_advance_schedule'])){ echo $rzvy_translangArr['staff_advance_schedule']; }else{ echo $rzvy_defaultlang['staff_advance_schedule']; } ?>
		  <?php if(isset($rzvy_rolepermissions['rzvy_staff_as_add']) || $rzvy_loginutype=='admin' || (isset($_SESSION['staff_id']) && $_SESSION['staff_id'] == $_GET["id"])){ ?>
				<a class="btn btn-primary btn-sm rzvy-white pull-right" data-toggle="modal" data-target="#rzvy-add-advanceschedule-modal"><i class="fa fa-plus"></i> <?php if(isset($rzvy_translangArr['add_advance_schedule'])){ echo $rzvy_translangArr['add_advance_schedule']; }else{ echo $rzvy_defaultlang['add_advance_schedule']; } ?></a>
		  <?php } ?>	
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="rzvy_advanceschedule_list_table" class="display responsive nowrap" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th><?php if(isset($rzvy_translangArr['hash_rzy_translation'])){ echo $rzvy_translangArr['hash_rzy_translation']; }else{ echo $rzvy_defaultlang['hash_rzy_translation']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['date_period'])){ echo $rzvy_translangArr['date_period']; }else{ echo $rzvy_defaultlang['date_period']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['time_period'])){ echo $rzvy_translangArr['time_period']; }else{ echo $rzvy_defaultlang['time_period']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['no_of_bookings'])){ echo $rzvy_translangArr['no_of_bookings']; }else{ echo $rzvy_defaultlang['no_of_bookings']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['status'])){ echo $rzvy_translangArr['status']; }else{ echo $rzvy_defaultlang['status']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				$i = 1;
				if(mysqli_num_rows($staff_advance_schedule)>0){
					while($schedule = mysqli_fetch_array($staff_advance_schedule)){ 
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php 
							if(strtotime($schedule["startdate"]) == strtotime($schedule["enddate"])){
								echo date($rzvy_date_format, strtotime($schedule['startdate']));
							}else{
								echo date($rzvy_date_format, strtotime($schedule['startdate']))." ".((isset($rzvy_translangArr['to']))?$rzvy_translangArr['to']:$rzvy_defaultlang['to'])." ".date($rzvy_date_format, strtotime($schedule['enddate']));
							}
							?></td>
							<td><?php echo date($rzvy_time_format, strtotime($schedule['starttime']))." ".((isset($rzvy_translangArr['to']))?$rzvy_translangArr['to']:$rzvy_defaultlang['to'])." ".date($rzvy_time_format, strtotime($schedule['endtime'])); ?></td>
							<td><?php echo ($schedule['no_of_booking']>0)?$schedule['no_of_booking']:((isset($rzvy_translangArr['unlimited']))?$rzvy_translangArr['unlimited']:$rzvy_defaultlang['unlimited']); ?></td>
							<td>
								<?php if(isset($rzvy_rolepermissions['rzvy_staff_as_status']) || $rzvy_loginutype=='admin'  || (isset($_SESSION['staff_id']) && $_SESSION['staff_id'] == $_GET["id"])){ ?>
								<label class="rzvy-toggle-switch">
								  <input type="checkbox" data-id="<?php echo $schedule['id']; ?>" class="rzvy-toggle-switch-input rzvy_change_advanceschedule_status" <?php if($schedule['status'] == "Y"){ echo "checked"; } ?> />
								  <span class="rzvy-toggle-switch-slider"></span>
								</label>
								<?php }elseif(!isset($rzvy_rolepermissions['rzvy_staff_as_status']) && $rzvy_loginutype=='staff'  && isset($_SESSION['staff_id']) && $_SESSION['staff_id'] != $_GET["id"]){ 
										if($schedule['status'] == "Y"){
											if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; }
										}else{
											if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; }
										}
									} ?>
								
							</td>
							<td>
								<?php if(isset($rzvy_rolepermissions['rzvy_staff_as_edit']) || $rzvy_loginutype=='admin'  || (isset($_SESSION['staff_id']) && $_SESSION['staff_id'] == $_GET["id"])){ ?>
									<a href="javascript:void(0);" class="m-1 btn btn-primary rzvy-white btn-sm rzvy-update-advanceschedulemodal" data-id="<?php echo $schedule['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a>
								<?php } if(isset($rzvy_rolepermissions['rzvy_staff_as_breaks']) || $rzvy_loginutype=='admin'  || (isset($_SESSION['staff_id']) && $_SESSION['staff_id'] == $_GET["id"])){ ?>	
									<a href="<?php echo SITE_URL; ?>backend/advance-schedule-breaks.php?id=<?php echo $schedule['id']; ?>&sid=<?php echo $schedule['staff_id']; ?>" class="m-1 btn btn-primary rzvy-white btn-sm" title="<?php echo ((isset($rzvy_translangArr['breaks']))?$rzvy_translangArr['breaks']:$rzvy_defaultlang['breaks']); ?>"><i class="fa fa-fw fa-coffee"></i></a>
									<?php /* <a href="javascript:void(0);" class="m-1 btn btn-primary rzvy-white btn-sm rzvy_clone_advanceschedule_btn" data-id="<?php echo $schedule['id']; ?>"><i class="fa fa-clone" aria-hidden="true"></i></a> */ ?>
								<?php } if(isset($rzvy_rolepermissions['rzvy_staff_as_delete']) || $rzvy_loginutype=='admin'  || (isset($_SESSION['staff_id']) && $_SESSION['staff_id'] == $_GET["id"])){ ?>	
									<a href="javascript:void(0);" class="m-1 btn btn-danger rzvy-white btn-sm rzvy_delete_advanceschedule_btn" data-id="<?php echo $schedule['id']; ?>"><i class="fa fa-fw fa-trash"></i></a>
								<?php } ?>
							</td>
						</tr>
						<?php 
						$i++;
					} 
				} 
				?>
			  </tbody>
           </table>
          </div>
        </div>
      </div>
	 <!-- Add Modal-->
	 <?php if(isset($rzvy_rolepermissions['rzvy_staff_as_add']) || $rzvy_loginutype=='admin'  || (isset($_SESSION['staff_id']) && $_SESSION['staff_id'] == $_GET["id"])){ ?>
	<div class="modal fade" id="rzvy-add-advanceschedule-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-add-advanceschedule-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-add-advanceschedule-modal-label"><?php if(isset($rzvy_translangArr['staff_advance_schedule'])){ echo $rzvy_translangArr['staff_advance_schedule']; }else{ echo $rzvy_defaultlang['staff_advance_schedule']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times"></i></span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="rzvy_add_advanceschedule_form" id="rzvy_add_advanceschedule_form" method="post">
				<input id="rzvy_advanceschedule_id" name="rzvy_advanceschedule_id" type="hidden" value="<?php echo $staffid; ?>" />
				<div class="row">
					<div class="form-group col-md-12">
						<label><?php if(isset($rzvy_translangArr['advance_schedule_type'])){ echo $rzvy_translangArr['advance_schedule_type']; }else{ echo $rzvy_defaultlang['advance_schedule_type']; } ?></label>
						<select class="form-control selectpicker" id="rzvy_advanceschedule_daterange_choice" name="rzvy_advanceschedule_daterange_choice">
							<option value="date" selected><?php echo (isset($rzvy_translangArr['date']))?$rzvy_translangArr['date']:$rzvy_defaultlang['date']; ?></option>
							<option value="daterange"><?php echo (isset($rzvy_translangArr['daterange']))?$rzvy_translangArr['daterange']:$rzvy_defaultlang['daterange']; ?></option>
						</select>
					</div>
				</div>
				<div class="row">
				  <div class="form-group col-md-12">
					<label for="rzvy_advanceschedule_daterange"><?php if(isset($rzvy_translangArr['date_period'])){ echo $rzvy_translangArr['date_period']; }else{ echo $rzvy_defaultlang['date_period']; } ?></label>
					<input class="form-control" id="rzvy_advanceschedule_daterange" name="rzvy_advanceschedule_daterange" type="text" onfocus="this.blur()" />
				  </div>
				</div>
				<div class="row">
					<div class="form-group col-md-6">
						<label><?php if(isset($rzvy_translangArr['start_time'])){ echo $rzvy_translangArr['start_time']; }else{ echo $rzvy_defaultlang['start_time']; } ?></label>
						<select class="form-control selectpicker" id="rzvy_advanceschedule_starttime" name="rzvy_advanceschedule_starttime">
							<?php echo $slot_options["slot_starttime"]; ?>
						</select>
						<label for="rzvy_advanceschedule_starttime" generated="true" class="error"></label>
					</div>
					<div class="form-group col-md-6">
						<label><?php if(isset($rzvy_translangArr['end_time'])){ echo $rzvy_translangArr['end_time']; }else{ echo $rzvy_defaultlang['end_time']; } ?></label>
						<select class="form-control selectpicker" id="rzvy_advanceschedule_endtime" name="rzvy_advanceschedule_endtime">
							<?php echo $slot_options["slot_endtime"]; ?>
						</select>
						<label for="rzvy_advanceschedule_endtime" generated="true" class="error"></label>
					</div>
				</div>
				<div class="row">
				  <div class="form-group col-md-6">
					<label for="rzvy_advanceschedule_noofbooking"><?php if(isset($rzvy_translangArr['no_of_bookings'])){ echo $rzvy_translangArr['no_of_bookings']; }else{ echo $rzvy_defaultlang['no_of_bookings']; } ?></label>
					<input class="form-control" id="rzvy_advanceschedule_noofbooking" name="rzvy_advanceschedule_noofbooking" type="number" min="0" value="1" />
				  </div>
				  <div class="form-group col-md-6">
					<label for="rzvy_advanceschedule_status"><?php if(isset($rzvy_translangArr['status'])){ echo $rzvy_translangArr['status']; }else{ echo $rzvy_defaultlang['status']; } ?></label>
					<div>
						<label class="text-success"><input type="radio" class="rzvy_advanceschedule_status" name="rzvy_advanceschedule_status" value="Y" checked> <?php if(isset($rzvy_translangArr['activate'])){ echo $rzvy_translangArr['activate']; }else{ echo $rzvy_defaultlang['activate']; } ?></label> &nbsp; <label class="text-danger"><input type="radio" class="rzvy_advanceschedule_status" name="rzvy_advanceschedule_status" value="N"> <?php if(isset($rzvy_translangArr['deactivate'])){ echo $rzvy_translangArr['deactivate']; }else{ echo $rzvy_defaultlang['deactivate']; } ?></label>
					</div>
				  </div>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<a id="rzvy_add_advanceschedule_btn" class="btn btn-primary w-100" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['add'])){ echo $rzvy_translangArr['add']; }else{ echo $rzvy_defaultlang['add']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	 <?php } ?>
	 <?php if(isset($rzvy_rolepermissions['rzvy_staff_as_edit']) || $rzvy_loginutype=='admin'  || (isset($_SESSION['staff_id']) && $_SESSION['staff_id'] == $_GET["id"])){ ?>
	 <!-- Update Modal-->
		<div class="modal fade" id="rzvy-update-advanceschedule-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-update-advanceschedule-modal-label" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="rzvy-update-advanceschedule-modal-label"><?php if(isset($rzvy_translangArr['staff_advance_schedule'])){ echo $rzvy_translangArr['staff_advance_schedule']; }else{ echo $rzvy_defaultlang['staff_advance_schedule']; } ?></h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true"><i class="fa fa-times"></i></span>
				</button>
			  </div>
			  <div class="modal-body rzvy-update-advanceschedule-modal-body">
				
			  </div>
			  <div class="modal-footer">
				<a id="rzvy_update_advanceschedule_btn" class="btn btn-primary w-100" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['update'])){ echo $rzvy_translangArr['update']; }else{ echo $rzvy_defaultlang['update']; } ?></a>
			  </div>
			</div>
		  </div>
		</div>
	 <?php } ?>
<?php 
if($_SESSION['login_type'] == "staff") {
	include 'staff-footer.php'; 
}else{
	include 'footer.php'; 
}
?>