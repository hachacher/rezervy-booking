<?php include 'header.php'; 
if(!isset($rzvy_rolepermissions['rzvy_locations']) && $rzvy_loginutype=='staff'){ ?>
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
	  <style> .saaappoint-bootstrap-tagsinput input:focus, .saaappoint-bootstrap-tagsinput input { border-bottom: 1px solid lightgray !important; margin-top: 4% !important; }  .saaappoint-bootstrap-tagsinput { border: unset !important; } </style>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['location_selector'])){ echo $rzvy_translangArr['location_selector']; }else{ echo $rzvy_defaultlang['location_selector']; } ?></li>
      </ol>
      <!-- Coupon DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-map-marker"></i> <?php if(isset($rzvy_translangArr['location_list'])){ echo $rzvy_translangArr['location_list']; }else{ echo $rzvy_defaultlang['location_list']; } ?>
		  </div>
        <div class="card-body">
			<div class="row mb-3 pl-3">
				<label class="col-md-3"><?php if(isset($rzvy_translangArr['location_selector_status'])){ echo $rzvy_translangArr['location_selector_status']; }else{ echo $rzvy_defaultlang['location_selector_status']; } ?></label>
				<label class="rzvy-toggle-switch">
					<input type="checkbox" name="rzvy_location_selector_status" id="rzvy_location_selector_status" class="rzvy-toggle-switch-input" <?php if($obj_settings->get_option("rzvy_location_selector_status")=="Y"){ echo "checked"; } ?> />
					<span class="rzvy-toggle-switch-slider"></span>
				</label>
			</div>
			<div class="col-md-12 border-0">
				<input id="rzvy_location_selector" type="text" class="w-100" value="<?php echo $obj_settings->get_option("rzvy_location_selector"); ?>" data-role="tagsinput" placeholder="<?php if(isset($rzvy_translangArr['enter_location'])){ echo $rzvy_translangArr['enter_location']; }else{ echo $rzvy_defaultlang['enter_location']; } ?>" />
			</div>
			<?php if(isset($rzvy_rolepermissions['rzvy_locations_manage']) || $rzvy_loginutype=='admin'){ ?>
				<div class="col-md-12 mt-4">
					<a id="save_location_selector_settings_btn" class="btn btn-primary btn-block" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['save_location_selector_settings'])){ echo $rzvy_translangArr['save_location_selector_settings']; }else{ echo $rzvy_defaultlang['save_location_selector_settings']; } ?></a>
				</div>
			<?php } ?>
        </div>
        <div class="card-footer small text-muted"></div>
      </div>
<?php include 'footer.php'; ?>