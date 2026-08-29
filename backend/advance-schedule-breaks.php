<?php 
if(!session_id()) { @session_start(); }
if($_SESSION['login_type'] == "staff"  && !isset($_SESSION['role_permissions']['rzvy_staff_as_breaks'])) {
	include 'staff-header.php';
	if(isset($_GET["sid"]) && is_numeric($_GET["sid"]) && $_SESSION['staff_id'] != $_GET["sid"]){
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
if(!isset($rzvy_rolepermissions['rzvy_staff_as_breaks']) && $rzvy_loginutype=='staff' && isset($_SESSION['staff_id']) && $_SESSION['staff_id'] != $_GET["sid"]){ ?>
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
if(!isset($_GET["id"],$_GET["sid"])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/staff.php";
	</script>
	<?php 
	exit;
}else if(!is_numeric($_GET["id"]) || !is_numeric($_GET["sid"])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/staff.php";
	</script>
	<?php 
	exit;
}else{}
$scheduleid = $_GET["id"];
$staffid = $_GET["sid"];
$obj_staff->id = $staffid;
$obj_staff->schedule_id = $scheduleid;
$is_staff_exist = $obj_staff->check_staff_exist(); 

$daterange_choice = "date";
$asstartdate = date('Y/m/d');
$asenddate = date('Y/m/d', strtotime("+2 days"));
$is_staff_advanceschedule_exist = false;
$staff_advanceschedule_exist = $obj_staff->check_staff_advanceschedule_exist(); 
if(mysqli_num_rows($staff_advanceschedule_exist)>0){
	$staff_advanceschedule_data = mysqli_fetch_assoc($staff_advanceschedule_exist);
	$is_staff_advanceschedule_exist = true;
	if(strtotime($staff_advanceschedule_data["startdate"]) == strtotime($staff_advanceschedule_data["enddate"])){
		$daterange_choice = "date";
		$asstartdate = date('Y/m/d', strtotime($staff_advanceschedule_data['startdate']));
		$asenddate = date('Y/m/d', strtotime($staff_advanceschedule_data['enddate']));
	}else{
		$daterange_choice = "daterange";
		$asstartdate = date('Y/m/d', strtotime($staff_advanceschedule_data['startdate']));
		$asenddate = date('Y/m/d', strtotime($staff_advanceschedule_data['enddate']));
	}
}
if(!$is_staff_exist || !$is_staff_advanceschedule_exist){
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
$schedule_starttime = "13:00:00";
$schedule_endtime = "14:00:00";
$slot_options = $obj_staff->generate_slot_dropdown_options($time_interval, $rzvy_time_format, $schedule_starttime, $schedule_endtime);

$staff_advance_schedule_breaks = $obj_staff->getall_advance_schedule_breaks(); 
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
		<li class="breadcrumb-item">			
			<a href="<?php echo SITE_URL; ?>backend/advance-schedule.php?id=<?php echo $staffid; ?>"><?php if(isset($rzvy_translangArr['manage_advance_schedule'])){ echo $rzvy_translangArr['manage_advance_schedule']; }else{ echo $rzvy_defaultlang['manage_advance_schedule']; } ?></a>			
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['breaks'])){ echo $rzvy_translangArr['breaks']; }else{ echo $rzvy_defaultlang['breaks']; } ?></li>
      </ol>
	  <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-coffee"></i> <?php if(isset($rzvy_translangArr['breaks'])){ echo $rzvy_translangArr['breaks']; }else{ echo $rzvy_defaultlang['breaks']; } ?>
		  <?php if(isset($rzvy_rolepermissions['rzvy_staff_as_breaksadd']) || $rzvy_loginutype=='admin' || (isset($_SESSION['staff_id']) && $_SESSION['staff_id'] == $_GET["sid"])){ ?>
				<a class="btn btn-primary btn-sm rzvy-white pull-right" data-toggle="modal" data-target="#rzvy-add-advanceschedule-modal"><i class="fa fa-plus"></i> <?php if(isset($rzvy_translangArr['add_break'])){ echo $rzvy_translangArr['add_break']; }else{ echo $rzvy_defaultlang['add_break']; } ?></a>
			<?php } ?>		
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="rzvy_advanceschedule_breaks_list_table" class="display responsive nowrap" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th><?php if(isset($rzvy_translangArr['hash_rzy_translation'])){ echo $rzvy_translangArr['hash_rzy_translation']; }else{ echo $rzvy_defaultlang['hash_rzy_translation']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['date_period'])){ echo $rzvy_translangArr['date_period']; }else{ echo $rzvy_defaultlang['date_period']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['time_period'])){ echo $rzvy_translangArr['time_period']; }else{ echo $rzvy_defaultlang['time_period']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				$i = 1;
				if(mysqli_num_rows($staff_advance_schedule_breaks)>0){
					while($break = mysqli_fetch_array($staff_advance_schedule_breaks)){ 
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php 
							if(strtotime($break["startdate"]) == strtotime($break["enddate"])){
								echo date($rzvy_date_format, strtotime($break['startdate']));
							}else{
								echo date($rzvy_date_format, strtotime($break['startdate']))." ".((isset($rzvy_translangArr['to']))?$rzvy_translangArr['to']:$rzvy_defaultlang['to'])." ".date($rzvy_date_format, strtotime($break['enddate']));
							}
							?></td>
							<td><?php echo date($rzvy_time_format, strtotime($break['break_start']))." ".((isset($rzvy_translangArr['to']))?$rzvy_translangArr['to']:$rzvy_defaultlang['to'])." ".date($rzvy_time_format, strtotime($break['break_end'])); ?></td>
							<td>
								<?php if(isset($rzvy_rolepermissions['rzvy_staff_as_breaksdelete']) || $rzvy_loginutype=='admin'  || (isset($_SESSION['staff_id']) && $_SESSION['staff_id'] == $_GET["sid"])){ ?>  
									<a href="javascript:void(0);" class="m-1 btn btn-danger rzvy-white btn-sm rzvy_delete_advanceschedule_break_btn" data-id="<?php echo $break['id']; ?>"><i class="fa fa-fw fa-trash"></i></a>
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
	<?php if(isset($rzvy_rolepermissions['rzvy_staff_as_breaksadd']) || $rzvy_loginutype=='admin'  || (isset($_SESSION['staff_id']) && $_SESSION['staff_id'] == $_GET["sid"])){ ?>  
	 <!-- Add Modal-->
	<div class="modal fade" id="rzvy-add-advanceschedule-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-add-advanceschedule-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-add-advanceschedule-modal-label"><?php if(isset($rzvy_translangArr['add_break'])){ echo $rzvy_translangArr['add_break']; }else{ echo $rzvy_defaultlang['add_break']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times"></i></span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="rzvy_add_advanceschedule_break_form" id="rzvy_add_advanceschedule_break_form" method="post">
				<input id="rzvy_advanceschedule_id" name="rzvy_advanceschedule_id" type="hidden" value="<?php echo $scheduleid; ?>" />
				<input id="rzvy_advanceschedule_sid" name="rzvy_advanceschedule_sid" type="hidden" value="<?php echo $staffid; ?>" />
				<div class="row">
					<div class="form-group col-md-12">
						<label><?php if(isset($rzvy_translangArr['advance_schedule_break_type'])){ echo $rzvy_translangArr['advance_schedule_break_type']; }else{ echo $rzvy_defaultlang['advance_schedule_break_type']; } ?></label>
						<select class="form-control selectpicker" id="rzvy_advanceschedule_break_daterange_choice" name="rzvy_advanceschedule_break_daterange_choice" data-startdate="<?php echo $asstartdate; ?>" data-enddate="<?php echo $asenddate; ?>">
							<option value="date" <?php if($daterange_choice == "date"){ echo "selected"; } ?>><?php echo (isset($rzvy_translangArr['date']))?$rzvy_translangArr['date']:$rzvy_defaultlang['date']; ?></option>
							<option value="daterange" <?php if($daterange_choice == "daterange"){ echo "selected"; } ?>><?php echo (isset($rzvy_translangArr['daterange']))?$rzvy_translangArr['daterange']:$rzvy_defaultlang['daterange']; ?></option>
						</select>
					</div>
				</div>
				<div class="row">
				  <div class="form-group col-md-12">
					<label for="rzvy_advanceschedule_break_daterange"><?php if(isset($rzvy_translangArr['date_period'])){ echo $rzvy_translangArr['date_period']; }else{ echo $rzvy_defaultlang['date_period']; } ?></label>
					<input class="form-control" id="rzvy_advanceschedule_break_daterange" name="rzvy_advanceschedule_break_daterange" type="text" onfocus="this.blur()" />
				  </div>
				</div>
				<div class="row">
					<div class="form-group col-md-6">
						<label><?php if(isset($rzvy_translangArr['break_start'])){ echo $rzvy_translangArr['break_start']; }else{ echo $rzvy_defaultlang['break_start']; } ?></label>
						<select class="form-control selectpicker" id="rzvy_advanceschedule_break_starttime" name="rzvy_advanceschedule_break_starttime">
							<?php echo $slot_options["slot_starttime"]; ?>
						</select>
						<label for="rzvy_advanceschedule_break_starttime" generated="true" class="error"></label>
					</div>
					<div class="form-group col-md-6">
						<label><?php if(isset($rzvy_translangArr['break_end'])){ echo $rzvy_translangArr['break_end']; }else{ echo $rzvy_defaultlang['break_end']; } ?></label>
						<select class="form-control selectpicker" id="rzvy_advanceschedule_break_endtime" name="rzvy_advanceschedule_break_endtime">
							<?php echo $slot_options["slot_endtime"]; ?>
						</select>
						<label for="rzvy_advanceschedule_break_endtime" generated="true" class="error"></label>
					</div>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<a id="rzvy_add_advanceschedule_break_btn" class="btn btn-primary w-100" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['add'])){ echo $rzvy_translangArr['add']; }else{ echo $rzvy_defaultlang['add']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } ?>
<?php 
if($rzvy_loginutype == "staff") {
	include 'staff-footer.php'; 
}else{
	include 'footer.php'; 
}
?>