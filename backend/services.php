<?php 
include 'header.php';
if(!isset($rzvy_rolepermissions['rzvy_servcies']) && $rzvy_loginutype=='staff'){ ?>
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
if(!isset($_GET['cid'])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/category.php";
	</script>
	<?php 
	exit;
} else if(!is_numeric($_GET['cid'])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/category.php";
	</script>
	<?php 
	exit;
 }
 $rzvy_location_selector_status = $obj_settings->get_option("rzvy_location_selector_status");
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/category.php"><?php if(isset($rzvy_translangArr['category'])){ echo $rzvy_translangArr['category']; }else{ echo $rzvy_defaultlang['category']; } ?></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['services'])){ echo $rzvy_translangArr['services']; }else{ echo $rzvy_defaultlang['services']; } ?></li>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-book"></i> <?php if(isset($rzvy_translangArr['service_list'])){ echo $rzvy_translangArr['service_list']; }else{ echo $rzvy_defaultlang['service_list']; } ?>
		  <?php if(isset($rzvy_rolepermissions['rzvy_addons']) || $rzvy_loginutype=='admin'){ ?>
				<a class="btn btn-primary btn-sm rzvy-white pull-right mx-1" href="<?php echo SITE_URL; ?>backend/addons.php"><i class="fa fa-fw fa-puzzle-piece"></i> <?php if(isset($rzvy_translangArr['manage_addons'])){ echo $rzvy_translangArr['manage_addons']; }else{ echo $rzvy_defaultlang['manage_addons']; } ?></a>
		  <?php } if(isset($rzvy_rolepermissions['rzvy_service_reorder']) || $rzvy_loginutype=='admin'){ ?>
				<a class="btn btn-primary btn-sm rzvy-white pull-right mx-1" data-toggle="modal" data-target="#rzvy-reorder-modal"><i class="fa fa-sort"></i> <?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?> <?php if(isset($rzvy_translangArr['services'])){ echo $rzvy_translangArr['services']; }else{ echo $rzvy_defaultlang['services']; } ?></a>
		  <?php } if(isset($rzvy_rolepermissions['rzvy_service_add']) || $rzvy_loginutype=='admin'){ ?>
				<a class="btn btn-primary btn-sm rzvy-white pull-right" data-toggle="modal" data-target="#rzvy-add-service-modal"><i class="fa fa-plus"></i> <?php if(isset($rzvy_translangArr['add_service'])){ echo $rzvy_translangArr['add_service']; }else{ echo $rzvy_defaultlang['add_service']; } ?></a>
		  <?php } ?>
		  </div>
        <div class="card-body">
		  <input type="hidden" id="service_cat_id" value="<?php echo $_GET["cid"]; ?>" />
          <div class="table-responsive">
            <table class="display responsive nowrap" width="100%" id="rzvy_services_list_table" cellspacing="0">
              <thead>
				<tr>
				  <th><?php if(isset($rzvy_translangArr['hash_rzy_translation'])){ echo $rzvy_translangArr['hash_rzy_translation']; }else{ echo $rzvy_defaultlang['hash_rzy_translation']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['title'])){ echo $rzvy_translangArr['title']; }else{ echo $rzvy_defaultlang['title']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['category'])){ echo $rzvy_translangArr['category']; }else{ echo $rzvy_defaultlang['category']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['rate'])){ echo $rzvy_translangArr['rate']; }else{ echo $rzvy_defaultlang['rate']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['status'])){ echo $rzvy_translangArr['status']; }else{ echo $rzvy_defaultlang['status']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['staff'])){ echo $rzvy_translangArr['staff']; }else{ echo $rzvy_defaultlang['staff']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				$obj_services->cat_id = $_GET['cid'];
				$all_services = $obj_services->get_all_services();
				if(mysqli_num_rows($all_services)>0){
					$i=0;
					$rzvy_currency_symbol = $obj_settings->get_option('rzvy_currency_symbol');
					$rzvy_currency_position = $obj_settings->get_option('rzvy_currency_position');
					while($service = mysqli_fetch_assoc($all_services)){
						$i++;
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php echo ucwords($service['title']); ?></td>
							<td><?php echo $obj_services->get_cat_title($service['cat_id']); ?></td>
							<td><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$service['rate']); ?></td>
							<td>
								<?php $checked = ''; if($service['status'] == "Y"){ $checked = "checked"; } ?>
								<?php if(isset($rzvy_rolepermissions['rzvy_service_status']) || $rzvy_loginutype=='admin'){ ?>
									<label class="rzvy-toggle-switch">
									  <input type="checkbox" data-id="<?php echo $service['id']; ?>" class="rzvy-toggle-switch-input rzvy_change_service_status" <?php echo $checked; ?> />
									  <span class="rzvy-toggle-switch-slider"></span>
									</label>
								<?php }elseif(!isset($rzvy_rolepermissions['rzvy_service_status']) && $rzvy_loginutype=='staff'){ 
										if($service['status'] == "Y"){
											if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; }
										}else{
											if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; }
										}
								 } ?>	
							</td>
							<td>
								<select <?php if(isset($rzvy_rolepermissions['rzvy_service_assignstaff']) || $rzvy_loginutype=='admin'){ ?> data-id="<?php echo $service['id']; ?>" name="rzvy_assign_staff" class="rzvy_assign_staff form-control rzvy_assign_staff_dd" multiple <?php }else{ echo 'class="rzvy_assign_staff_dd form-control"'; } ?> >
									<?php 									
									$get_all_active_staff = $obj_services->get_all_active_staff();
									while($staff_data = mysqli_fetch_array($get_all_active_staff)){
										$is_staff_assigned = $obj_services->get_linked_staff($staff_data["id"], $service['id']);
										$selected = '';
										if(mysqli_num_rows($is_staff_assigned)>0){ $selected = "selected"; }
										echo '<option value="'.$staff_data["id"].'" '.$selected.'>'.ucwords($staff_data["firstname"].' '.$staff_data["lastname"]).'</option>';
									} 
									?>
								</select>
							</td>
							<td>
								<?php if(isset($rzvy_rolepermissions['rzvy_service_edit']) || $rzvy_loginutype=='admin'){ ?>	
									<a class="m-1 btn btn-primary rzvy-white btn-sm rzvy-update-servicemodal" data-id="<?php echo $service['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a>
								<?php } if(isset($rzvy_rolepermissions['rzvy_service_delete']) || $rzvy_loginutype=='admin'){ ?>
									<a class="m-1 btn btn-danger rzvy-white btn-sm rzvy_delete_service_btn" data-id="<?php echo $service['id']; ?>"><i class="fa fa-fw fa-trash"></i></a>
								<?php } ?>	
									<a class="m-1 btn btn-warning btn-sm rzvy-view-servicemodal" data-id="<?php echo $service['id']; ?>"><i class="fa fa-fw fa-eye"></i></a>
								<?php if(isset($rzvy_rolepermissions['rzvy_service_schedule']) || $rzvy_loginutype=='admin'){ ?>	
									<a class="m-1 btn btn-info rzvy-white btn-sm rzvy-schedule-servicemodal" data-id="<?php echo $service['id']; ?>"><i class="fa fa-fw fa-calendar"></i></a>
								<?php } if(isset($rzvy_rolepermissions['rzvy_service_reorderaddon']) || $rzvy_loginutype=='admin'){ ?>	
									<a class="m-1 btn btn-success rzvy-white btn-sm" href="<?php echo SITE_URL."backend/reorder-addons.php?sid=".$service['id']; ?>"><i class="fa fa-fw fa-sort"></i> <?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?> <?php if(isset($rzvy_translangArr['addons'])){ echo $rzvy_translangArr['addons']; }else{ echo $rzvy_defaultlang['addons']; } ?></a>
								<?php } ?>	
							</td>
						</tr>
						<?php 
					}
				}
				?>
			  </tbody>
           </table>
          </div>
        </div>
      </div>
	<?php if(isset($rzvy_rolepermissions['rzvy_service_add']) || $rzvy_loginutype=='admin'){ ?>  
	 <!-- Add Modal-->
	<div class="modal fade" id="rzvy-add-service-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-add-service-modal-label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-add-service-modal-label"><?php if(isset($rzvy_translangArr['add_service'])){ echo $rzvy_translangArr['add_service']; }else{ echo $rzvy_defaultlang['add_service']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="rzvy_add_service_form" id="rzvy_add_service_form" method="post">
				<div class="row"> 
					<div class="form-group col-md-6">
						<label for="rzvy_servicetitle"><?php if(isset($rzvy_translangArr['service_title'])){ echo $rzvy_translangArr['service_title']; }else{ echo $rzvy_defaultlang['service_title']; } ?></label>
						<input class="form-control" id="rzvy_servicetitle" name="rzvy_servicetitle" type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_service_title'])){ echo $rzvy_translangArr['enter_service_title']; }else{ echo $rzvy_defaultlang['enter_service_title']; } ?>" />
					</div>
					<div class="form-group col-md-6">
						<label for="rzvy_servicerate"><?php if(isset($rzvy_translangArr['rate'])){ echo $rzvy_translangArr['rate']; }else{ echo $rzvy_defaultlang['rate']; } ?></label>
						<input class="form-control" id="rzvy_servicerate" name="rzvy_servicerate" type="text" placeholder="99.99" />
					</div>
					<div class="form-group col-md-6">
						<label for="rzvy_serviceduration"><?php if(isset($rzvy_translangArr['service_duration'])){ echo $rzvy_translangArr['service_duration']; }else{ echo $rzvy_defaultlang['service_duration']; } ?></label>
						<input class="form-control" id="rzvy_serviceduration" name="rzvy_serviceduration" type="number" placeholder="<?php if(isset($rzvy_translangArr['enter_service_duration'])){ echo $rzvy_translangArr['enter_service_duration']; }else{ echo $rzvy_defaultlang['enter_service_duration']; } ?>" />
					</div>
					<div class="form-group col-md-6">
						<label for="rzvy_servicepbefore"><?php if(isset($rzvy_translangArr['service_padding_before'])){ echo $rzvy_translangArr['service_padding_before']; }else{ echo $rzvy_defaultlang['service_padding_before']; } ?></label>
						<input class="form-control" id="rzvy_servicepbefore" name="rzvy_servicepbefore" type="number" placeholder="<?php if(isset($rzvy_translangArr['enter_service_padding_before'])){ echo $rzvy_translangArr['enter_service_padding_before']; }else{ echo $rzvy_defaultlang['enter_service_padding_before']; } ?>" />
					</div>
					<div class="form-group col-md-6">
						<label for="rzvy_servicepafter"><?php if(isset($rzvy_translangArr['service_padding_after'])){ echo $rzvy_translangArr['service_padding_after']; }else{ echo $rzvy_defaultlang['service_padding_after']; } ?></label>
						<input class="form-control" id="rzvy_servicepafter" name="rzvy_servicepafter" type="number" placeholder="<?php if(isset($rzvy_translangArr['enter_service_padding_after'])){ echo $rzvy_translangArr['enter_service_padding_after']; }else{ echo $rzvy_defaultlang['enter_service_padding_after']; } ?>" />
					</div>
					
					<div class="form-group col-md-6">
						<label for="rzvy_servicestatus"><?php if(isset($rzvy_translangArr['service_status'])){ echo $rzvy_translangArr['service_status']; }else{ echo $rzvy_defaultlang['service_status']; } ?></label>
						<div>
							<label class="text-success"><input type="radio" name="rzvy_servicestatus" value="Y" checked> <?php if(isset($rzvy_translangArr['activate'])){ echo $rzvy_translangArr['activate']; }else{ echo $rzvy_defaultlang['activate']; } ?></label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="rzvy_servicestatus" value="N"> <?php if(isset($rzvy_translangArr['deactivate'])){ echo $rzvy_translangArr['deactivate']; }else{ echo $rzvy_defaultlang['deactivate']; } ?></label>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label for="rzvy_servicedescription"><?php if(isset($rzvy_translangArr['service_description'])){ echo $rzvy_translangArr['service_description']; }else{ echo $rzvy_defaultlang['service_description']; } ?></label>
						<textarea class="form-control" id="rzvy_servicedescription" name="rzvy_servicedescription" placeholder="<?php if(isset($rzvy_translangArr['enter_service_description'])){ echo $rzvy_translangArr['enter_service_description']; }else{ echo $rzvy_defaultlang['enter_service_description']; } ?>"></textarea>
					</div>
					
					<div class="form-group col-md-6">
						<label for="rzvy_serviceimage"><?php if(isset($rzvy_translangArr['service_image'])){ echo $rzvy_translangArr['service_image']; }else{ echo $rzvy_defaultlang['service_image']; } ?></label>
						<div class="rzvy-image-upload">
							<div class="rzvy-image-edit-icon">
								<input type='hidden' id="rzvy-image-upload-file-hidden" name="rzvy-image-upload-file-hidden" />
								<input type='file' id="rzvy-image-upload-file" accept=".png, .jpg, .jpeg" />
								<label for="rzvy-image-upload-file"></label>
							</div>
							<div class="rzvy-image-preview">
								<div id="rzvy-image-upload-file-preview" style="background-image: url(<?php echo SITE_URL; ?>includes/images/default-service.png);">
								</div>
							</div>
						</div>
					</div>
					<?php if($rzvy_location_selector_status == "Y"){ 
							$rzvy_location_selector = $obj_settings->get_option('rzvy_location_selector');
							$exploded_rzvy_location_selector = explode(",", $rzvy_location_selector);
							$j=0; ?>
					<div class="form-group col-md-6">
						<label for="rzvy_service_locations"><?php if(isset($rzvy_translangArr['service_locations'])){ echo $rzvy_translangArr['service_locations']; }else{ echo $rzvy_defaultlang['service_locations']; } ?></label>
						<select multiple data-live-search="true" class="form-control selectpicker" id="rzvy_service_locations" name="rzvy_service_locations">
							<option selected="selected" value="all"><?php if(isset($rzvy_translangArr['all_locations'])){ echo $rzvy_translangArr['all_locations']; }else{ echo $rzvy_defaultlang['all_locations']; } ?></option>
							<?php 
								if(sizeof($exploded_rzvy_location_selector)>0){ 								
									for($i=0;$i<sizeof($exploded_rzvy_location_selector);$i++){
									?><option value="<?php  echo $exploded_rzvy_location_selector[$i];  ?>"><?php  echo $exploded_rzvy_location_selector[$i];  ?></option><?php 
									}
								} ?>
						</select>
					</div> <?php }else{ ?> 
					<div class="form-group col-md-6">
						<select multiple data-live-search="true" class="form-control selectpicker fade" id="rzvy_service_locations" name="rzvy_service_locations">
							<option selected="selected" value="all"><?php if(isset($rzvy_translangArr['all_locations'])){ echo $rzvy_translangArr['all_locations']; }else{ echo $rzvy_defaultlang['all_locations']; } ?></option>
						</select>					
					</div>
					<?php } ?>
					<div class="form-group col-md-3">
						<label for="rzvy_service_badge"><?php if(isset($rzvy_translangArr['show_badge'])){ echo $rzvy_translangArr['show_badge']; }else{ echo $rzvy_defaultlang['show_badge']; } ?></label>
						<select class="form-control" id="rzvy_service_badge" name="rzvy_service_badge">
							<option value="Y"><?php if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; } ?></option>
							<option selected value="N"><?php if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; } ?></option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="rzvy_service_badge_text"><?php if(isset($rzvy_translangArr['badge_content'])){ echo $rzvy_translangArr['badge_content']; }else{ echo $rzvy_defaultlang['badge_content']; } ?></label>
						<input class="form-control" id="rzvy_service_badge_text" name="rzvy_service_badge_text" type="text" placeholder="<?php if(isset($rzvy_translangArr['trending'])){ echo $rzvy_translangArr['trending']; }else{ echo $rzvy_defaultlang['trending']; } ?>" maxlength="10"/>
					</div>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_add_service_btn" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['add'])){ echo $rzvy_translangArr['add']; }else{ echo $rzvy_defaultlang['add']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } if(isset($rzvy_rolepermissions['rzvy_service_edit']) || $rzvy_loginutype=='admin'){ ?>
	 <!-- Update Modal-->
	<div class="modal fade" id="rzvy-update-service-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-update-service-modal-label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-update-service-modal-label"><?php if(isset($rzvy_translangArr['update_service'])){ echo $rzvy_translangArr['update_service']; }else{ echo $rzvy_defaultlang['update_service']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy-update-service-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_update_service_btn" data-id="" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['update'])){ echo $rzvy_translangArr['update']; }else{ echo $rzvy_defaultlang['update']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } ?>
	 <!-- View Modal-->
	<div class="modal fade" id="rzvy-view-service-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-view-service-modal-label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-view-service-modal-label"><?php if(isset($rzvy_translangArr['service_detail'])){ echo $rzvy_translangArr['service_detail']; }else{ echo $rzvy_defaultlang['service_detail']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy-view-service-modal-body">
		  </div>
		  <div class="modal-footer">
		  </div>
		</div>
	  </div>
	</div>
	<?php if(isset($rzvy_rolepermissions['rzvy_service_schedule']) || $rzvy_loginutype=='admin'){ ?>
	 <!-- Schedule Modal-->
	<div class="modal fade" id="rzvy-schedule-service-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-schedule-service-modal-label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-schedule-service-modal-label"><?php if(isset($rzvy_translangArr['service_schedule'])){ echo $rzvy_translangArr['service_schedule']; }else{ echo $rzvy_defaultlang['service_schedule']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy-schedule-service-modal-body">
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_update_service_schedule_btn" data-id="" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['update_service_schedule'])){ echo $rzvy_translangArr['update_service_schedule']; }else{ echo $rzvy_defaultlang['update_service_schedule']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } if(isset($rzvy_rolepermissions['rzvy_service_reorder']) || $rzvy_loginutype=='admin'){ ?>
	<!-- Reorder Modal-->
	<div class="modal fade" id="rzvy-reorder-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-reorder-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-reorder-modal-label"><?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?> <?php if(isset($rzvy_translangArr['service'])){ echo $rzvy_translangArr['service']; }else{ echo $rzvy_defaultlang['service']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body">
			<ul class="ser_reorder rzvy_reorder_ul m-1 row">
				<?php 
				$obj_services->cat_id = $_GET['cid'];
				$all_services = $obj_services->get_all_services();
				if(mysqli_num_rows($all_services)>0){
					$i=0;
					while($service = mysqli_fetch_assoc($all_services)){
						?>
						<li class="p-3 m-1 bg-light ui-state-default col-md-12" data-id="<?php echo $service['id']; ?>"><i class="fa fa-arrows pr-2"></i> <?php echo ucwords($service['title']); ?></li>
						<?php 
						$i++;
					}
				} 
				?>
			</ul>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_service_reorder_btn" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } ?>
<?php include 'footer.php'; ?>