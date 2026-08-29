<?php include 'header.php'; 
if(!isset($rzvy_rolepermissions['rzvy_rolepermission']) && $rzvy_loginutype=='staff'){ ?>
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
          <a href="<?php echo SITE_URL; ?>backend/dashboard.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['role_permissions'])){ echo $rzvy_translangArr['role_permissions']; }else{ echo $rzvy_defaultlang['role_permissions']; } ?></li>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-book"></i> <?php if(isset($rzvy_translangArr['roles_list'])){ echo $rzvy_translangArr['roles_list']; }else{ echo $rzvy_defaultlang['roles_list']; } ?>
		  <?php if(isset($rzvy_rolepermissions['rzvy_rolepermission_add']) || $rzvy_loginutype=='admin'){ ?>
				<a class="btn btn-primary btn-sm rzvy-white pull-right mx-1" data-toggle="modal" data-target="#rzvy-add-role-modal"><i class="fa fa-plus"></i> <?php if(isset($rzvy_translangArr['add_role'])){ echo $rzvy_translangArr['add_role']; }else{ echo $rzvy_defaultlang['add_role']; } ?></a>
		  <?php } ?>
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="display responsive nowrap" width="100%" id="rzvy_role_list_table" cellspacing="0">
              <thead>
				<tr>
				  <th><?php if(isset($rzvy_translangArr['hash_rzy_translation'])){ echo $rzvy_translangArr['hash_rzy_translation']; }else{ echo $rzvy_defaultlang['hash_rzy_translation']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['role'])){ echo $rzvy_translangArr['role']; }else{ echo $rzvy_defaultlang['role']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['assign_staff'])){ echo $rzvy_translangArr['assign_staff']; }else{ echo $rzvy_defaultlang['assign_staff']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['status'])){ echo $rzvy_translangArr['status']; }else{ echo $rzvy_defaultlang['status']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
				</tr>
			  </thead>
			  <tbody>
			 <?php 
				$get_all_roles = $obj_roles->get_all_roles();
				if(mysqli_num_rows($get_all_roles)>0){
				    while($role_data = mysqli_fetch_array($get_all_roles)){ 
				    $rolestaffids =  explode(',',$role_data['staff']);
				?>
				<tr>
				    
					<td><?php echo $role_data['id']; ?></td>
					<td><?php echo $role_data['role']; ?></td>
					<td>
						<select  data-id="<?php echo $role_data['id']; ?>" onchange="rzvy_assign_staffrole_func(this, '<?php echo AJAX_URL; ?>')" class="form-control selectpicker rzvy_assign_staff_role" data-live-search="true" multiple data-placeholder="<?php if(isset($rzvy_translangArr['assign_staff'])){ echo $rzvy_translangArr['assign_staff']; }else{ echo $rzvy_defaultlang['assign_staff']; } ?>">
							<?php 
							$get_all_active_staff = $obj_services->get_all_active_staff();
							if(mysqli_num_rows($get_all_active_staff)>0){
    							while($staff_data = mysqli_fetch_array($get_all_active_staff)){
    							    $selected = "";
    							    if(is_array($rolestaffids) && sizeof($rolestaffids)>0){
    							        if(in_array($staff_data["id"],$rolestaffids)){
    							             $selected = 'selected="selected"';
    							        }
    							    }
    								
    								echo '<option data-id="'.$role_data['id'].'" value="'.$staff_data["id"].'" '.$selected.'>'.ucwords($staff_data["firstname"].' '.$staff_data["lastname"]).'</option>';
    							} 
							}
							?>
						</select>
					</td>
					<td>
						<?php if(isset($rzvy_rolepermissions['rzvy_rolepermission_status']) || $rzvy_loginutype=='admin'){ ?>
							<label class="rzvy-toggle-switch">
								<input data-id="<?php echo $role_data['id']; ?>" type="checkbox" class="rzvy-toggle-switch-input rzvy_change_role_status" <?php if($role_data['status']=='Y'){ echo 'checked="checked"'; } ?> />
								<span class="rzvy-toggle-switch-slider"></span>
							</label>
						<?php }elseif(!isset($rzvy_rolepermissions['rzvy_rolepermission_status']) && $rzvy_loginutype=='staff'){ 
								if($role_data['status'] == "Y"){
									if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; }
								}else{
									if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; }
								}
							} ?>	
					</td>
					<td>
						<?php if(isset($rzvy_rolepermissions['rzvy_rolepermission_view']) || $rzvy_loginutype=='admin'){ ?>
							<a class="btn btn-primary rzvy-white btn-sm rzvy-permissions-rolemodal m-1" href="permissions.php?rid=<?php echo $role_data['id']; ?>" data-id=""><i class="fa fa-fw fa-lock"></i> <?php if(isset($rzvy_translangArr['manage_permissions'])){ echo $rzvy_translangArr['manage_permissions']; }else{ echo $rzvy_defaultlang['manage_permissions']; } ?></a>
						<?php } if(isset($rzvy_rolepermissions['rzvy_rolepermission_edit']) || $rzvy_loginutype=='admin'){ ?>
							<a data-id="<?php echo $role_data['id']; ?>" class="btn btn-primary rzvy-white btn-sm rzvy-update-rolemodal m-1" data-id=""><i class="fa fa-fw fa-pencil"></i></a> 
						<?php } if(isset($rzvy_rolepermissions['rzvy_rolepermission_delete']) || $rzvy_loginutype=='admin'){ ?>
							<a data-id="<?php echo $role_data['id']; ?>" class="btn btn-danger rzvy-white btn-sm rzvy_delete_role_btn m-1" data-id=""><i class="fa fa-fw fa-trash"></i></a>
						<?php } ?>
					</td>
				</tr>
			<?php } } ?>
			</tbody>
           </table>
          </div>
        </div>
      </div>
	<?php if(isset($rzvy_rolepermissions['rzvy_rolepermission_add']) || $rzvy_loginutype=='admin'){ ?>  
	<!-- Add Modal-->
	<div class="modal fade" id="rzvy-add-role-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-add-role-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-add-role-modal-label"><?php if(isset($rzvy_translangArr['add_role'])){ echo $rzvy_translangArr['add_role']; }else{ echo $rzvy_defaultlang['add_role']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="rzvy_add_pcategory_form" id="rzvy_add_pcategory_form" method="post">
				<div class="form-group">
					<label for="rzvy_role"><?php if(isset($rzvy_translangArr['role'])){ echo $rzvy_translangArr['role']; }else{ echo $rzvy_defaultlang['role']; } ?></label>
					<input class="form-control" id="rzvy_role" name="rzvy_role" type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_role'])){ echo $rzvy_translangArr['enter_role']; }else{ echo $rzvy_defaultlang['enter_role']; } ?>" />
					<label for="rzvy_role" generated="true" class="error rzvy-hide rzvy_role_err"><?php if(isset($rzvy_translangArr['enter_role_name'])){ echo $rzvy_translangArr['enter_role_name']; }else{ echo $rzvy_defaultlang['enter_role_name']; } ?></label>
				</div>
			  
				<div class="form-group">
					<label for="rzvy_rolestatus"><?php if(isset($rzvy_translangArr['status'])){ echo $rzvy_translangArr['status']; }else{ echo $rzvy_defaultlang['status']; } ?></label>
					<div>
						<label class="text-success"><input type="radio" name="rzvy_rolestatus" value="Y" checked> <?php if(isset($rzvy_translangArr['activate'])){ echo $rzvy_translangArr['activate']; }else{ echo $rzvy_defaultlang['activate']; } ?></label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="rzvy_rolestatus" value="N"> <?php if(isset($rzvy_translangArr['deactivate'])){ echo $rzvy_translangArr['deactivate']; }else{ echo $rzvy_defaultlang['deactivate']; } ?></label>
					</div>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_add_role_btn" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['add'])){ echo $rzvy_translangArr['add']; }else{ echo $rzvy_defaultlang['add']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } ?>
	<?php if(isset($rzvy_rolepermissions['rzvy_rolepermission_edit']) || $rzvy_loginutype=='admin'){ ?>
	<!-- Update Modal-->
	<div class="modal fade" id="rzvy-update-role-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-update-role-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-update-role-modal-label"><?php if(isset($rzvy_translangArr['update_role'])){ echo $rzvy_translangArr['update_role']; }else{ echo $rzvy_defaultlang['update_role']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy-update-role-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_update_role_btn" data-id="" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['update'])){ echo $rzvy_translangArr['update']; }else{ echo $rzvy_defaultlang['update']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } ?>
<?php include 'footer.php'; ?>