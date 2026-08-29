<?php include 'header.php'; 
if(!isset($rzvy_rolepermissions['rzvy_colors']) && $rzvy_loginutype=='staff'){ ?>
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
<?php die(); } ?>
<!-- Breadcrumbs-->
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a></li>
	<li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['color_scheme'])){ echo $rzvy_translangArr['color_scheme']; }else{ echo $rzvy_defaultlang['color_scheme']; } ?></li>
</ol>
<div class="row">
	<?php if(isset($rzvy_rolepermissions['rzvy_colors_dashboard']) || $rzvy_loginutype=='admin'){ ?>
	<div class="col-md-6 my-2">
		<div class="card rzvy-boxshadow mt-1 mr-1" id="rzvy_cs_admin_dashboard">
			<div class="card-body text-primary text-center">
				<?php if(isset($rzvy_translangArr['dashboard'])){ echo $rzvy_translangArr['dashboard']; }else{ echo $rzvy_defaultlang['dashboard']; } ?>
			</div>
		</div>
	</div>
	<?php } if(isset($rzvy_rolepermissions['rzvy_colors_booking']) || $rzvy_loginutype=='admin'){ ?>
	<div class="col-md-6 my-2">
		<div class="card rzvy-boxshadow mt-1 mr-1" id="rzvy_cs_bf_and_ls_page">
			<div class="card-body text-primary text-center">
				<?php if(isset($rzvy_translangArr['booking_form_and_location_selector_page'])){ echo $rzvy_translangArr['booking_form_and_location_selector_page']; }else{ echo $rzvy_defaultlang['booking_form_and_location_selector_page']; } ?>
			</div>
		</div>
	</div>
	<?php } ?>
</div>
<?php if(isset($rzvy_rolepermissions['rzvy_colors_calendar']) || $rzvy_loginutype=='admin'){ ?>
<div class="row">
	<div class="col-md-12 my-2">
		<div class="card">
			<div class="card-body">
				<h5><i class="fa fa-tint"></i> <?php if(isset($rzvy_translangArr['appointment_status_colorscheme'])){ echo $rzvy_translangArr['appointment_status_colorscheme']; }else{ echo $rzvy_defaultlang['appointment_status_colorscheme']; } ?></h5>
				<br />
				  <?php 
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
						"break" => "#c3c3c3",
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
							if(isset($astatuscscheme->break) && $astatuscscheme->break != ""){ $defaultApptStatusColorScheme["break"] = $astatuscscheme->break; }
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
						"break" => "#FFFFFF",
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
							if(isset($astatutscscheme->break) && $astatutscscheme->break != ""){ $defaultApptStatusTextColorScheme["break"] = $astatutscscheme->break; }
						}
					}
				  ?>
				  <div class="row">
					<div class="col-md-12 text-center bg-light py-3">
						<h4><?php if(isset($rzvy_translangArr['appointment_status_colorscheme'])){ echo $rzvy_translangArr['appointment_status_colorscheme']; }else{ echo $rzvy_defaultlang['appointment_status_colorscheme']; } ?></h4>
					</div>
				  </div>
				  <div class="form-group row">
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['pending'])){ echo $rzvy_translangArr['pending']; }else{ echo $rzvy_defaultlang['pending']; } ?></label>
						<input name="rzvy_apptstatus_colorscheme[]" data-apptstatus="pending" class="form-control rzvy_apptstatus_colorscheme" type="color" value="<?php echo $defaultApptStatusColorScheme["pending"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['confirmed_by_you'])){ echo $rzvy_translangArr['confirmed_by_you']; }else{ echo $rzvy_defaultlang['confirmed_by_you']; } ?></label>
						<input name="rzvy_apptstatus_colorscheme[]" data-apptstatus="confirmed" class="form-control rzvy_apptstatus_colorscheme" type="color" value="<?php echo $defaultApptStatusColorScheme["confirmed"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['confirmed_by_staff'])){ echo $rzvy_translangArr['confirmed_by_staff']; }else{ echo $rzvy_defaultlang['confirmed_by_staff']; } ?></label>
						<input name="rzvy_apptstatus_colorscheme[]" data-apptstatus="confirmed_by_staff" class="form-control rzvy_apptstatus_colorscheme" type="color" value="<?php echo $defaultApptStatusColorScheme["confirmed_by_staff"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['rescheduled_by_customer'])){ echo $rzvy_translangArr['rescheduled_by_customer']; }else{ echo $rzvy_defaultlang['rescheduled_by_customer']; } ?></label>
						<input name="rzvy_apptstatus_colorscheme[]" data-apptstatus="rescheduled_by_customer" class="form-control rzvy_apptstatus_colorscheme" type="color" value="<?php echo $defaultApptStatusColorScheme["rescheduled_by_customer"]; ?>" />
					</div>
				  </div>
				  <div class="form-group row">
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['rescheduled_by_you'])){ echo $rzvy_translangArr['rescheduled_by_you']; }else{ echo $rzvy_defaultlang['rescheduled_by_you']; } ?></label>
						<input name="rzvy_apptstatus_colorscheme[]" data-apptstatus="rescheduled_by_you" class="form-control rzvy_apptstatus_colorscheme" type="color" value="<?php echo $defaultApptStatusColorScheme["rescheduled_by_you"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['rescheduled_by_staff'])){ echo $rzvy_translangArr['rescheduled_by_staff']; }else{ echo $rzvy_defaultlang['rescheduled_by_staff']; } ?></label>
						<input name="rzvy_apptstatus_colorscheme[]" data-apptstatus="rescheduled_by_staff" class="form-control rzvy_apptstatus_colorscheme" type="color" value="<?php echo $defaultApptStatusColorScheme["rescheduled_by_staff"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['cancelled_by_customer'])){ echo $rzvy_translangArr['cancelled_by_customer']; }else{ echo $rzvy_defaultlang['cancelled_by_customer']; } ?></label>
						<input name="rzvy_apptstatus_colorscheme[]" data-apptstatus="cancelled_by_customer" class="form-control rzvy_apptstatus_colorscheme" type="color" value="<?php echo $defaultApptStatusColorScheme["cancelled_by_customer"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['rejected_by_you'])){ echo $rzvy_translangArr['rejected_by_you']; }else{ echo $rzvy_defaultlang['rejected_by_you']; } ?></label>
						<input name="rzvy_apptstatus_colorscheme[]" data-apptstatus="rejected_by_you" class="form-control rzvy_apptstatus_colorscheme" type="color" value="<?php echo $defaultApptStatusColorScheme["rejected_by_you"]; ?>" />
					</div>
				  </div>
				  <div class="form-group row">
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['rejected_by_staff'])){ echo $rzvy_translangArr['rejected_by_staff']; }else{ echo $rzvy_defaultlang['rejected_by_staff']; } ?></label>
						<input name="rzvy_apptstatus_colorscheme[]" data-apptstatus="rejected_by_staff" class="form-control rzvy_apptstatus_colorscheme" type="color" value="<?php echo $defaultApptStatusColorScheme["rejected_by_staff"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['completed'])){ echo $rzvy_translangArr['completed']; }else{ echo $rzvy_defaultlang['completed']; } ?></label>
						<input name="rzvy_apptstatus_colorscheme[]" data-apptstatus="completed" class="form-control rzvy_apptstatus_colorscheme" type="color" value="<?php echo $defaultApptStatusColorScheme["completed"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['mark_as_noshow'])){ echo $rzvy_translangArr['mark_as_noshow']; }else{ echo $rzvy_defaultlang['mark_as_noshow']; } ?></label>
						<input name="rzvy_apptstatus_colorscheme[]" data-apptstatus="mark_as_noshow" class="form-control rzvy_apptstatus_colorscheme" type="color" value="<?php echo $defaultApptStatusColorScheme["mark_as_noshow"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['break'])){ echo $rzvy_translangArr['break']; }else{ echo $rzvy_defaultlang['break']; } ?></label>
						<input name="rzvy_apptstatus_colorscheme[]" data-apptstatus="break" class="form-control rzvy_apptstatus_colorscheme" type="color" value="<?php echo $defaultApptStatusColorScheme["break"]; ?>" />
					</div>
				  </div>
				  
				  <div class="row">
					<div class="col-md-12 text-center bg-light py-3">
						<h4><?php if(isset($rzvy_translangArr['appointment_text_colorscheme_for_calendar'])){ echo $rzvy_translangArr['appointment_text_colorscheme_for_calendar']; }else{ echo $rzvy_defaultlang['appointment_text_colorscheme_for_calendar']; } ?></h4>
					</div>
				  </div>
				  <div class="form-group row">
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['pending'])){ echo $rzvy_translangArr['pending']; }else{ echo $rzvy_defaultlang['pending']; } ?></label>
						<input name="rzvy_apptstatus_text_colorscheme[]" data-apptstatus="pending" class="form-control rzvy_apptstatus_text_colorscheme" type="color" value="<?php echo $defaultApptStatusTextColorScheme["pending"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['confirmed_by_you'])){ echo $rzvy_translangArr['confirmed_by_you']; }else{ echo $rzvy_defaultlang['confirmed_by_you']; } ?></label>
						<input name="rzvy_apptstatus_text_colorscheme[]" data-apptstatus="confirmed" class="form-control rzvy_apptstatus_text_colorscheme" type="color" value="<?php echo $defaultApptStatusTextColorScheme["confirmed"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['confirmed_by_staff'])){ echo $rzvy_translangArr['confirmed_by_staff']; }else{ echo $rzvy_defaultlang['confirmed_by_staff']; } ?></label>
						<input name="rzvy_apptstatus_text_colorscheme[]" data-apptstatus="confirmed_by_staff" class="form-control rzvy_apptstatus_text_colorscheme" type="color" value="<?php echo $defaultApptStatusTextColorScheme["confirmed_by_staff"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['rescheduled_by_customer'])){ echo $rzvy_translangArr['rescheduled_by_customer']; }else{ echo $rzvy_defaultlang['rescheduled_by_customer']; } ?></label>
						<input name="rzvy_apptstatus_text_colorscheme[]" data-apptstatus="rescheduled_by_customer" class="form-control rzvy_apptstatus_text_colorscheme" type="color" value="<?php echo $defaultApptStatusTextColorScheme["rescheduled_by_customer"]; ?>" />
					</div>
				  </div>
				  <div class="form-group row">
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['rescheduled_by_you'])){ echo $rzvy_translangArr['rescheduled_by_you']; }else{ echo $rzvy_defaultlang['rescheduled_by_you']; } ?></label>
						<input name="rzvy_apptstatus_text_colorscheme[]" data-apptstatus="rescheduled_by_you" class="form-control rzvy_apptstatus_text_colorscheme" type="color" value="<?php echo $defaultApptStatusTextColorScheme["rescheduled_by_you"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['rescheduled_by_staff'])){ echo $rzvy_translangArr['rescheduled_by_staff']; }else{ echo $rzvy_defaultlang['rescheduled_by_staff']; } ?></label>
						<input name="rzvy_apptstatus_text_colorscheme[]" data-apptstatus="rescheduled_by_staff" class="form-control rzvy_apptstatus_text_colorscheme" type="color" value="<?php echo $defaultApptStatusTextColorScheme["rescheduled_by_staff"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['cancelled_by_customer'])){ echo $rzvy_translangArr['cancelled_by_customer']; }else{ echo $rzvy_defaultlang['cancelled_by_customer']; } ?></label>
						<input name="rzvy_apptstatus_text_colorscheme[]" data-apptstatus="cancelled_by_customer" class="form-control rzvy_apptstatus_text_colorscheme" type="color" value="<?php echo $defaultApptStatusTextColorScheme["cancelled_by_customer"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['rejected_by_you'])){ echo $rzvy_translangArr['rejected_by_you']; }else{ echo $rzvy_defaultlang['rejected_by_you']; } ?></label>
						<input name="rzvy_apptstatus_text_colorscheme[]" data-apptstatus="rejected_by_you" class="form-control rzvy_apptstatus_text_colorscheme" type="color" value="<?php echo $defaultApptStatusTextColorScheme["rejected_by_you"]; ?>" />
					</div>
				  </div>
				  <div class="form-group row">
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['rejected_by_staff'])){ echo $rzvy_translangArr['rejected_by_staff']; }else{ echo $rzvy_defaultlang['rejected_by_staff']; } ?></label>
						<input name="rzvy_apptstatus_text_colorscheme[]" data-apptstatus="rejected_by_staff" class="form-control rzvy_apptstatus_text_colorscheme" type="color" value="<?php echo $defaultApptStatusTextColorScheme["rejected_by_staff"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['completed'])){ echo $rzvy_translangArr['completed']; }else{ echo $rzvy_defaultlang['completed']; } ?></label>
						<input name="rzvy_apptstatus_text_colorscheme[]" data-apptstatus="completed" class="form-control rzvy_apptstatus_text_colorscheme" type="color" value="<?php echo $defaultApptStatusTextColorScheme["completed"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['mark_as_noshow'])){ echo $rzvy_translangArr['mark_as_noshow']; }else{ echo $rzvy_defaultlang['mark_as_noshow']; } ?></label>
						<input name="rzvy_apptstatus_text_colorscheme[]" data-apptstatus="mark_as_noshow" class="form-control rzvy_apptstatus_text_colorscheme" type="color" value="<?php echo $defaultApptStatusTextColorScheme["mark_as_noshow"]; ?>" />
					</div>
					<div class="col-md-3">
						<label class="control-label"><?php if(isset($rzvy_translangArr['break'])){ echo $rzvy_translangArr['break']; }else{ echo $rzvy_defaultlang['break']; } ?></label>
						<input name="rzvy_apptstatus_text_colorscheme[]" data-apptstatus="break" class="form-control rzvy_apptstatus_text_colorscheme" type="color" value="<?php echo $defaultApptStatusTextColorScheme["break"]; ?>" />
					</div>
				  </div>
				  <a id="update_apptstatus_colorscheme_settings_btn" class="btn btn-primary btn-block" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['update_settings'])){ echo $rzvy_translangArr['update_settings']; }else{ echo $rzvy_defaultlang['update_settings']; } ?></a>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<div class="modal fade" id="rzvy-color-scheme-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-color-scheme-modal-label" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="rzvy-color-scheme-modal-label"></h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span></button>
			</div>
			<div class="modal-body rzvy-color-scheme-modal-content mb-4 mx-3">

			</div>
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>