<?php 
include 'header.php';
if(!isset($rzvy_rolepermissions['rzvy_addons']) && $rzvy_loginutype=='staff'){ ?>
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
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['addons'])){ echo $rzvy_translangArr['addons']; }else{ echo $rzvy_defaultlang['addons']; } ?></li>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
		<div class="card-header">
          <i class="fa fa-fw fa-book"></i> <?php if(isset($rzvy_translangArr['addons_list'])){ echo $rzvy_translangArr['addons_list']; }else{ echo $rzvy_defaultlang['addons_list']; } ?>
		  <?php if(isset($rzvy_rolepermissions['rzvy_addon_add']) || $rzvy_loginutype=='admin'){ ?>
				<a class="btn btn-primary btn-sm rzvy-white pull-right" data-toggle="modal" data-target="#rzvy-add-addon-modal"><i class="fa fa-plus"></i> <?php if(isset($rzvy_translangArr['add_addon'])){ echo $rzvy_translangArr['add_addon']; }else{ echo $rzvy_defaultlang['add_addon']; } ?></a>
		  <?php } ?>	
		  </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="display responsive nowrap" width="100%" id="rzvy_addons_list_table" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th><?php if(isset($rzvy_translangArr['hash_rzy_translation'])){ echo $rzvy_translangArr['hash_rzy_translation']; }else{ echo $rzvy_defaultlang['hash_rzy_translation']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['name'])){ echo $rzvy_translangArr['name']; }else{ echo $rzvy_defaultlang['name']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['rate'])){ echo $rzvy_translangArr['rate']; }else{ echo $rzvy_defaultlang['rate']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['multiple_qty'])){ echo $rzvy_translangArr['multiple_qty']; }else{ echo $rzvy_defaultlang['multiple_qty']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['status'])){ echo $rzvy_translangArr['status']; }else{ echo $rzvy_defaultlang['status']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['services'])){ echo $rzvy_translangArr['services']; }else{ echo $rzvy_defaultlang['services']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
				</tr>
			  </thead>
				<tbody>
					<?php 
					$all_addons = $obj_addons->get_all_addons();
					if(mysqli_num_rows($all_addons)>0){
						$i = 0;
						$rzvy_currency_symbol = $obj_settings->get_option('rzvy_currency_symbol');
						$rzvy_currency_position = $obj_settings->get_option('rzvy_currency_position');
						while($addon = mysqli_fetch_assoc($all_addons)){
							$i++;
							?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo ucwords($addon['title']); ?></td>
								<td><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$addon['rate']); ?></td>
								<td>
									<?php $multi_checked = ''; if($addon['multiple_qty'] == "Y"){ $multi_checked = "checked"; }; ?>
									<?php if(isset($rzvy_rolepermissions['rzvy_addon_multiqtystatus']) || $rzvy_loginutype=='admin'){ ?>
										<label class="rzvy-toggle-switch">
										  <input type="checkbox" data-id="<?php echo $addon['id']; ?>" class="rzvy-toggle-switch-input rzvy_change_addon_multiple_qty_status" <?php echo $multi_checked; ?> />
										  <span class="rzvy-toggle-switch-slider"></span>
										</label>
									<?php }elseif(!isset($rzvy_rolepermissions['rzvy_addon_multiqtystatus']) && $rzvy_loginutype=='staff'){ 
										if($addon['multiple_qty'] == "Y"){
											if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; }
										}else{
											if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; }
										}
									} ?>
								</td>
								<td>
									<?php $checked = ''; if($addon['status'] == "Y"){ $checked = "checked"; }; ?>
									<?php if(isset($rzvy_rolepermissions['rzvy_addon_status']) || $rzvy_loginutype=='admin'){ ?>
										<label class="rzvy-toggle-switch">
										  <input type="checkbox" data-id="<?php echo $addon['id']; ?>" class="rzvy-toggle-switch-input rzvy_change_addon_status" <?php echo $checked; ?> />
										  <span class="rzvy-toggle-switch-slider"></span>
										</label>
									<?php }elseif(!isset($rzvy_rolepermissions['rzvy_addon_status']) && $rzvy_loginutype=='staff'){ 
										if($addon['status'] == "Y"){
											if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; }
										}else{
											if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; }
										}
									} ?>	
								</td>
								<td>
									<select <?php if(isset($rzvy_rolepermissions['rzvy_service_assignstaff']) || $rzvy_loginutype=='admin'){ ?> data-id="<?php echo $addon['id']; ?>" name="rzvy_assign_services" onchange="rzvy_assign_services_func(this, '<?php echo AJAX_URL; ?>')" <?php } ?> class="rzvy_assign_services selectpicker" multiple data-live-search="true" data-actions-box="true" >
										<?php 
										$allcategories = $obj_addons->get_all_categories_addons(); 
										if(mysqli_num_rows($allcategories)>0){
											while($cat = mysqli_fetch_array($allcategories)){ 
												$obj_addons->category_id = $cat["id"];
												$get_all_active_services = $obj_addons->get_services_by_cat_id_addons();
												if(mysqli_num_rows($get_all_active_services)>0){ ?>
												<optgroup label="<?php echo ucwords($cat["cat_name"]); ?>">
													<?php 
													while($data = mysqli_fetch_array($get_all_active_services)){
														$is_addon_assigned = $obj_addons->get_linked_services($addon["id"], $data['id']);
														$selected = '';
														if(mysqli_num_rows($is_addon_assigned)>0){ $selected = "selected"; }
														?>
														<option value="<?php echo $data["id"]; ?>" <?php echo $selected; ?>><?php echo ucwords($data["title"]); ?></option>
														<?php 
													}
													?>
												</optgroup>
												<?php	
												}
											}
										}
										?>
									</select>
								</td>
								<td>
									<?php if(isset($rzvy_rolepermissions['rzvy_addon_edit']) || $rzvy_loginutype=='admin'){ ?>
										<a class="m-1 btn btn-primary rzvy-white btn-sm rzvy-update-addonmodal" data-id="<?php echo $addon['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a>
									<?php } if(isset($rzvy_rolepermissions['rzvy_addon_delete']) || $rzvy_loginutype=='admin'){ ?>
										<a class="m-1 btn btn-danger rzvy-white btn-sm rzvy_delete_addon_btn" data-id="<?php echo $addon['id']; ?>"><i class="fa fa-fw fa-trash"></i></a>
									<?php } ?>
										<a class="m-1 btn btn-warning btn-sm rzvy-view-addonmodal" data-id="<?php echo $addon['id']; ?>"><i class="fa fa-fw fa-eye"></i></a>
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
	<?php if(isset($rzvy_rolepermissions['rzvy_addon_add']) || $rzvy_loginutype=='admin'){ ?>  
	 <!-- Add Modal-->
	<div class="modal fade" id="rzvy-add-addon-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-add-addon-modal-label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-add-addon-modal-label"><?php if(isset($rzvy_translangArr['add_addon'])){ echo $rzvy_translangArr['add_addon']; }else{ echo $rzvy_defaultlang['add_addon']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="rzvy_add_addon_form" id="rzvy_add_addon_form" method="post">
			  <div class="row">
				  <div class="form-group col-md-4">
					<label for="rzvy_addonname"><?php if(isset($rzvy_translangArr['addon_name'])){ echo $rzvy_translangArr['addon_name']; }else{ echo $rzvy_defaultlang['addon_name']; } ?></label>
					<input class="form-control" id="rzvy_addonname" name="rzvy_addonname" type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_addon_name'])){ echo $rzvy_translangArr['enter_addon_name']; }else{ echo $rzvy_defaultlang['enter_addon_name']; } ?>" />
				  </div>
				  <div class="form-group col-md-4">
					<label for="rzvy_addonminlimit"><?php if(isset($rzvy_translangArr['addon_minlimit'])){ echo $rzvy_translangArr['addon_minlimit']; }else{ echo $rzvy_defaultlang['addon_minlimit']; } ?></label>
					<input class="form-control" id="rzvy_addonminlimit" name="rzvy_addonminlimit" type="number" value="1" min="1" />
				  </div>
				  <div class="form-group col-md-4">
					<label for="rzvy_addonmaxlimit"><?php if(isset($rzvy_translangArr['addon_maxlimit'])){ echo $rzvy_translangArr['addon_maxlimit']; }else{ echo $rzvy_defaultlang['addon_maxlimit']; } ?></label>
					<input class="form-control" id="rzvy_addonmaxlimit" name="rzvy_addonmaxlimit" type="number" value="1" min="1" />
				  </div>
				  <div class="form-group col-md-6">
					<label for="rzvy_addonrate"><?php if(isset($rzvy_translangArr['addon_rate'])){ echo $rzvy_translangArr['addon_rate']; }else{ echo $rzvy_defaultlang['addon_rate']; } ?></label>
					<input class="form-control" id="rzvy_addonrate" name="rzvy_addonrate" type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_addon_rate'])){ echo $rzvy_translangArr['enter_addon_rate']; }else{ echo $rzvy_defaultlang['enter_addon_rate']; } ?>" />
				  </div>
				  <div class="form-group col-md-6">
					<label for="rzvy_addonduration"><?php if(isset($rzvy_translangArr['addon_duration'])){ echo $rzvy_translangArr['addon_duration']; }else{ echo $rzvy_defaultlang['addon_duration']; } ?></label>
					<input class="form-control" id="rzvy_addonduration" name="rzvy_addonduration" type="number" value="0" min="0" placeholder="<?php if(isset($rzvy_translangArr['enter_addon_duration'])){ echo $rzvy_translangArr['enter_addon_duration']; }else{ echo $rzvy_defaultlang['enter_addon_duration']; } ?>" />
				  </div>
				  <div class="form-group col-md-6">
					<label for="rzvy_addondescription"><?php if(isset($rzvy_translangArr['addon_description'])){ echo $rzvy_translangArr['addon_description']; }else{ echo $rzvy_defaultlang['addon_description']; } ?></label>
					<textarea class="form-control" id="rzvy_addondescription" name="rzvy_addondescription" placeholder="<?php if(isset($rzvy_translangArr['enter_addon_description'])){ echo $rzvy_translangArr['enter_addon_description']; }else{ echo $rzvy_defaultlang['enter_addon_description']; } ?>"></textarea>
				  </div>
				 
				  <div class="form-group col-md-3">
						<label for="rzvy_addon_badge"><?php if(isset($rzvy_translangArr['show_badge'])){ echo $rzvy_translangArr['show_badge']; }else{ echo $rzvy_defaultlang['show_badge']; } ?></label>
						<select class="form-control" id="rzvy_addon_badge" name="rzvy_addon_badge">
							<option value="Y"><?php if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; } ?></option>
							<option selected value="N"><?php if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; } ?></option>
						</select>
				</div>
				<div class="form-group col-md-3">
						<label for="rzvy_addon_badge_text"><?php if(isset($rzvy_translangArr['badge_content'])){ echo $rzvy_translangArr['badge_content']; }else{ echo $rzvy_defaultlang['badge_content']; } ?></label>
						<input class="form-control" id="rzvy_addon_badge_text" name="rzvy_addon_badge_text" type="text" placeholder="<?php if(isset($rzvy_translangArr['trending'])){ echo $rzvy_translangArr['trending']; }else{ echo $rzvy_defaultlang['trending']; } ?>" maxlength="10"/>
				</div>
				  <div class="form-group col-md-6">
					<label for="rzvy_addonmultipleqty"><?php if(isset($rzvy_translangArr['multiple_qty'])){ echo $rzvy_translangArr['multiple_qty']; }else{ echo $rzvy_defaultlang['multiple_qty']; } ?></label>
					<div>
						<label class="text-success"><input type="radio" name="rzvy_addonmultipleqty" value="Y" checked> <?php if(isset($rzvy_translangArr['activate'])){ echo $rzvy_translangArr['activate']; }else{ echo $rzvy_defaultlang['activate']; } ?></label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="rzvy_addonmultipleqty" value="N"> <?php if(isset($rzvy_translangArr['deactivate'])){ echo $rzvy_translangArr['deactivate']; }else{ echo $rzvy_defaultlang['deactivate']; } ?></label>
					</div>
				  </div>
				  <div class="form-group col-md-6">
					<label for="rzvy_addonstatus"><?php if(isset($rzvy_translangArr['addon_status'])){ echo $rzvy_translangArr['addon_status']; }else{ echo $rzvy_defaultlang['addon_status']; } ?></label>
					<div>
						<label class="text-success"><input type="radio" name="rzvy_addonstatus" value="Y" checked> <?php if(isset($rzvy_translangArr['activate'])){ echo $rzvy_translangArr['activate']; }else{ echo $rzvy_defaultlang['activate']; } ?></label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="rzvy_addonstatus" value="N"> <?php if(isset($rzvy_translangArr['deactivate'])){ echo $rzvy_translangArr['deactivate']; }else{ echo $rzvy_defaultlang['deactivate']; } ?></label>
					</div>
				  </div>
				  
				  
				  <div class="form-group col-md-6">
					<label for="rzvy_addonimage"><?php if(isset($rzvy_translangArr['addon_image'])){ echo $rzvy_translangArr['addon_image']; }else{ echo $rzvy_defaultlang['addon_image']; } ?></label>
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
			  </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_add_addon_btn" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['add'])){ echo $rzvy_translangArr['add']; }else{ echo $rzvy_defaultlang['add']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } if(isset($rzvy_rolepermissions['rzvy_addon_edit']) || $rzvy_loginutype=='admin'){ ?>
	 <!-- Update Modal-->
	<div class="modal fade" id="rzvy-update-addon-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-update-addon-modal-label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-update-addon-modal-label"><?php if(isset($rzvy_translangArr['update_addon'])){ echo $rzvy_translangArr['update_addon']; }else{ echo $rzvy_defaultlang['update_addon']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy-update-addon-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_update_addon_btn" data-id="" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['update'])){ echo $rzvy_translangArr['update']; }else{ echo $rzvy_defaultlang['update']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } ?>
	 <!-- View Modal-->
	<div class="modal fade" id="rzvy-view-addon-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-view-addon-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-view-addon-modal-label"><?php if(isset($rzvy_translangArr['addon_detail'])){ echo $rzvy_translangArr['addon_detail']; }else{ echo $rzvy_defaultlang['addon_detail']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy-view-addon-modal-body">
			
		  </div>
		  <div class="modal-footer">
		  </div>
		</div>
	  </div>
	</div>
<?php include 'footer.php'; ?>