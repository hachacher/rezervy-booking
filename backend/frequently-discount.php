<?php include 'header.php'; 
if(!isset($rzvy_rolepermissions['rzvy_fd']) && $rzvy_loginutype=='staff'){ ?>
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
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['frequently_discount'])){ echo $rzvy_translangArr['frequently_discount']; }else{ echo $rzvy_defaultlang['frequently_discount']; } ?></li>
      </ol>
      <!-- Frequently Discount DataTables Card-->
      <div class="mb-3">
          <div class="table-responsive">
            <table class="table" width="100%" cellspacing="0">
              <thead>
				<tr>
				  <th><?php if(isset($rzvy_translangArr['label'])){ echo $rzvy_translangArr['label']; }else{ echo $rzvy_defaultlang['label']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['type'])){ echo $rzvy_translangArr['type']; }else{ echo $rzvy_defaultlang['type']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['value'])){ echo $rzvy_translangArr['value']; }else{ echo $rzvy_defaultlang['value']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['description'])){ echo $rzvy_translangArr['description']; }else{ echo $rzvy_defaultlang['description']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['status'])){ echo $rzvy_translangArr['status']; }else{ echo $rzvy_defaultlang['status']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
				</tr>
			  </thead>
			  <tbody class="rzvy_frequently_discount_tbody">
				<?php
				$all_frequently_discount = $obj_frequently_discount->get_all_frequently_discount();
				$rzvy_currency_symbol = $obj_settings->get_option('rzvy_currency_symbol');
				$rzvy_currency_position = $obj_settings->get_option('rzvy_currency_position');
				while($frequently_discount = mysqli_fetch_array($all_frequently_discount)){
					?>
					<tr>
					  <td><?php echo $frequently_discount['fd_label']; ?></td>
					  <td><?php echo ucwords($frequently_discount['fd_type']); ?></td>
					  <td><?php if($frequently_discount['fd_type'] == 'flat'){ echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$frequently_discount['fd_value']); }else{ echo $frequently_discount['fd_value'].'%'; } ?></td>
					  <td><?php echo $frequently_discount['fd_description']; ?></td>
					  <td>
						<?php if(isset($rzvy_rolepermissions['rzvy_fd_status']) || $rzvy_loginutype=='admin'){ ?>
							<label class="rzvy-toggle-switch">
							  <input type="checkbox" class="rzvy-toggle-switch-input rzvy_change_fd_status" data-id="<?php echo $frequently_discount['id']; ?>" <?php if($frequently_discount['fd_status'] == 'Y'){ echo 'checked'; } ?> />
							  <span class="rzvy-toggle-switch-slider"></span>
							</label>
						<?php }elseif(!isset($rzvy_rolepermissions['rzvy_fd_status']) && $rzvy_loginutype=='staff'){ 
								if($frequently_discount['fd_status'] == "Y"){
									if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; }
								}else{
									if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; }
								}
							} ?>
					  </td>
					  <td>
						<?php if(isset($rzvy_rolepermissions['rzvy_fd_edit']) || $rzvy_loginutype=='admin'){ ?>
							<a class="btn btn-primary rzvy-white btn-sm rzvy-update-fdmodal" data-id="<?php echo $frequently_discount['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a>
						<?php } ?>
					  </td>
					</tr>
					<?php
				}
				?>
			</tbody>
           </table>
          </div>
        </div>
	<?php if(isset($rzvy_rolepermissions['rzvy_fd_edit']) || $rzvy_loginutype=='admin'){ ?>	
	 <!-- Update Modal-->
	<div class="modal fade" id="rzvy_update_fd_modal" tabindex="-1" role="dialog" aria-labelledby="rzvy_update_fd_modal_label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy_update_fd_modal_label"><?php if(isset($rzvy_translangArr['update_frequently_discount'])){ echo $rzvy_translangArr['update_frequently_discount']; }else{ echo $rzvy_defaultlang['update_frequently_discount']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy_update_fd_modal_body">
			<h2><?php if(isset($rzvy_translangArr['please_wait'])){ echo $rzvy_translangArr['please_wait']; }else{ echo $rzvy_defaultlang['please_wait']; } ?></h2>
		  </div>
		  <div class="modal-footer"> </div>
		</div>
	  </div>
	</div>
	<?php } ?>
<?php include 'footer.php'; ?>