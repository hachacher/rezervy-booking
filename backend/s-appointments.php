<?php 
include 'staff-header.php'; 
$_SESSION["rzvy_staff_calendar"] = $_SESSION["staff_id"]; 
$ascolor = array(
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
		if(isset($astatuscscheme->pending) && $astatuscscheme->pending != ""){ $ascolor["pending"] = $astatuscscheme->pending; }
		if(isset($astatuscscheme->confirmed) && $astatuscscheme->confirmed != ""){ $ascolor["confirmed"] = $astatuscscheme->confirmed; }
		if(isset($astatuscscheme->confirmed_by_staff) && $astatuscscheme->confirmed_by_staff != ""){ $ascolor["confirmed_by_staff"] = $astatuscscheme->confirmed_by_staff; }
		if(isset($astatuscscheme->rescheduled_by_customer) && $astatuscscheme->rescheduled_by_customer != ""){ $ascolor["rescheduled_by_customer"] = $astatuscscheme->rescheduled_by_customer; }
		if(isset($astatuscscheme->rescheduled_by_you) && $astatuscscheme->rescheduled_by_you != ""){ $ascolor["rescheduled_by_you"] = $astatuscscheme->rescheduled_by_you; }
		if(isset($astatuscscheme->rescheduled_by_staff) && $astatuscscheme->rescheduled_by_staff != ""){ $ascolor["rescheduled_by_staff"] = $astatuscscheme->rescheduled_by_staff; }
		if(isset($astatuscscheme->cancelled_by_customer) && $astatuscscheme->cancelled_by_customer != ""){ $ascolor["cancelled_by_customer"] = $astatuscscheme->cancelled_by_customer; }
		if(isset($astatuscscheme->rejected_by_you) && $astatuscscheme->rejected_by_you != ""){ $ascolor["rejected_by_you"] = $astatuscscheme->rejected_by_you; }
		if(isset($astatuscscheme->rejected_by_staff) && $astatuscscheme->rejected_by_staff != ""){ $ascolor["rejected_by_staff"] = $astatuscscheme->rejected_by_staff; }
		if(isset($astatuscscheme->completed) && $astatuscscheme->completed != ""){ $ascolor["completed"] = $astatuscscheme->completed; }
		if(isset($astatuscscheme->mark_as_noshow) && $astatuscscheme->mark_as_noshow != ""){ $ascolor["mark_as_noshow"] = $astatuscscheme->mark_as_noshow; }
	}
} 
?>
<style>
	<?php 
	foreach($ascolor as $key=>$value){
		echo ".rzvy_color_".$key."{ color: ".$value."; } .rzvy_color_".$key." span{ border: 1px solid ".$value."; background-color: ".$value."; }";
	}
	?>
</style>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/s-appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['appointments'])){ echo $rzvy_translangArr['appointments']; }else{ echo $rzvy_defaultlang['appointments']; } ?></li>
		<div class="col-md-12">
			<a class="btn btn-sm pull-right" href="javascript:void(0)" data-toggle="modal" data-target="#rzvy_appointments_legends_modal"><i class="fa fa-info-circle"></i> <?php if(isset($rzvy_translangArr['legend'])){ echo $rzvy_translangArr['legend']; }else{ echo $rzvy_defaultlang['legend']; } ?></a>
		</div>
      </ol>
	<div class="rzvy-hide">
		<div id="rzvy-staff-calendar">
			<?php 
			$obj_staff->id = $_SESSION["staff_id"];
			$staff = $obj_staff->readone_staff(); 
			?>
			<a class="rzvy_staff_active" data-id="<?php echo $staff["id"]; ?>"><?php echo ucwords($staff["firstname"]." ".$staff["lastname"]); ?></a>
		</div>
	</div>
	<div class="row mb-3 mx-4" id="rzvy_cal_lag_list">
		
	</div>
	<div class="mb-3">
		<div id='rzvy-appointments-calendar'></div>
	</div>
	
	 <!-- Legend modal -->
	 <div class="modal fade" id="rzvy_appointments_legends_modal" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title"><?php if(isset($rzvy_translangArr['legend'])){ echo $rzvy_translangArr['legend']; }else{ echo $rzvy_defaultlang['legend']; } ?></h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<div class="row">
						<ul class="rzvy-legend">
							<li class="rzvy_color_pending float-none"><span></span> <?php if(isset($rzvy_translangArr['pending'])){ echo $rzvy_translangArr['pending']; }else{ echo $rzvy_defaultlang['pending']; } ?></li>
							<li class="rzvy_color_confirmed float-none"><span></span> <?php if(isset($rzvy_translangArr['confirmed_by_admin'])){ echo $rzvy_translangArr['confirmed_by_admin']; }else{ echo $rzvy_defaultlang['confirmed_by_admin']; } ?></li>
							<li class="rzvy_color_confirmed_by_staff float-none"><span></span> <?php if(isset($rzvy_translangArr['confirmed_by_you'])){ echo $rzvy_translangArr['confirmed_by_you']; }else{ echo $rzvy_defaultlang['confirmed_by_you']; } ?></li>
							<li class="rzvy_color_rescheduled_by_customer float-none"><span></span> <?php if(isset($rzvy_translangArr['rescheduled_by_customer'])){ echo $rzvy_translangArr['rescheduled_by_customer']; }else{ echo $rzvy_defaultlang['rescheduled_by_customer']; } ?></li>
							<li class="rzvy_color_cancelled_by_customer float-none"><span></span> <?php if(isset($rzvy_translangArr['cancelled_by_customer'])){ echo $rzvy_translangArr['cancelled_by_customer']; }else{ echo $rzvy_defaultlang['cancelled_by_customer']; } ?></li>
							<li class="rzvy_color_rescheduled_by_you float-none"><span></span> <?php if(isset($rzvy_translangArr['rescheduled_by_admin'])){ echo $rzvy_translangArr['rescheduled_by_admin']; }else{ echo $rzvy_defaultlang['rescheduled_by_admin']; } ?></li>
							<li class="rzvy_color_rejected_by_you float-none"><span></span> <?php if(isset($rzvy_translangArr['rejected_by_admin'])){ echo $rzvy_translangArr['rejected_by_admin']; }else{ echo $rzvy_defaultlang['rejected_by_admin']; } ?></li>
							<li class="rzvy_color_rescheduled_by_staff float-none"><span></span> <?php if(isset($rzvy_translangArr['rescheduled_by_you'])){ echo $rzvy_translangArr['rescheduled_by_you']; }else{ echo $rzvy_defaultlang['rescheduled_by_you']; } ?></li>
							<li class="rzvy_color_rejected_by_staff float-none"><span></span> <?php if(isset($rzvy_translangArr['rejected_by_you'])){ echo $rzvy_translangArr['rejected_by_you']; }else{ echo $rzvy_defaultlang['rejected_by_you']; } ?></li>
							<li class="rzvy_color_completed float-none"><span></span> <?php if(isset($rzvy_translangArr['completed'])){ echo $rzvy_translangArr['completed']; }else{ echo $rzvy_defaultlang['completed']; } ?></li>
							<li class="rzvy_color_rejected_by_you float-none"><span></span> <?php if(isset($rzvy_translangArr['mark_as_noshow'])){ echo $rzvy_translangArr['mark_as_noshow']; }else{ echo $rzvy_defaultlang['mark_as_noshow']; } ?></li>
							
						</ul>
					 </div>
				</div>
			</div>
		</div>
	</div>
<?php include 'staff-footer.php'; ?>