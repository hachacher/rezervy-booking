<?php 
include 'header.php';
if(!isset($rzvy_rolepermissions['rzvy_blockoff']) && $rzvy_loginutype=='staff'){ ?>
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
$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');
$rzvy_datetime_format = $rzvy_date_format." ".$rzvy_time_format; 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/schedule.php"><?php if(isset($rzvy_translangArr['schedule'])){ echo $rzvy_translangArr['schedule']; }else{ echo $rzvy_defaultlang['schedule']; } ?></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['manage_block_off'])){ echo $rzvy_translangArr['manage_block_off']; }else{ echo $rzvy_defaultlang['manage_block_off']; } ?></li>
      </ol>
	  <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-calendar-o"></i> <?php if(isset($rzvy_translangArr['block_off_list'])){ echo $rzvy_translangArr['block_off_list']; }else{ echo $rzvy_defaultlang['block_off_list']; } ?>
		  <?php if(isset($rzvy_rolepermissions['rzvy_blockoff_add']) || $rzvy_loginutype=='admin'){ ?>
				<a class="btn btn-primary btn-sm rzvy-white pull-right" data-toggle="modal" data-target="#rzvy-add-blockoff-modal"><i class="fa fa-plus"></i> <?php if(isset($rzvy_translangArr['add_block_off'])){ echo $rzvy_translangArr['add_block_off']; }else{ echo $rzvy_defaultlang['add_block_off']; } ?></a>
		  <?php } ?>	
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="display responsive nowrap" width="100%" id="rzvy_blockoff_list_table" cellspacing="0">
              <thead>
				<tr>
				  <th><?php if(isset($rzvy_translangArr['hash_rzy_translation'])){ echo $rzvy_translangArr['hash_rzy_translation']; }else{ echo $rzvy_defaultlang['hash_rzy_translation']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['title'])){ echo $rzvy_translangArr['title']; }else{ echo $rzvy_defaultlang['title']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['date_period'])){ echo $rzvy_translangArr['date_period']; }else{ echo $rzvy_defaultlang['date_period']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['time_period'])){ echo $rzvy_translangArr['time_period']; }else{ echo $rzvy_defaultlang['time_period']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['status'])){ echo $rzvy_translangArr['status']; }else{ echo $rzvy_defaultlang['status']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				$all_block_off = $obj_block_off->readall_block_off();
				$i = 1;
				if(mysqli_num_rows($all_block_off)>0){
					while($block_off = mysqli_fetch_array($all_block_off)){ 
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td><?php if(strlen($block_off['title']) < 30){ echo ucfirst($block_off['title']); }else{ echo substr(ucfirst($block_off['title']), 0, 30)."..."; } ?></td>
							<td><?php echo "From ".date($rzvy_date_format, strtotime($block_off['from_date']))." to ".date($rzvy_date_format, strtotime($block_off['to_date'])); ?></td>
							<td><?php if($block_off['blockoff_type'] == "custom"){ echo "From ".date($rzvy_time_format, strtotime($block_off['from_time']))." to ".date($rzvy_time_format, strtotime($block_off['to_time'])); }else{ echo "FullDay"; } ?></td>
							<td>
								<?php if(isset($rzvy_rolepermissions['rzvy_blockoff_status']) || $rzvy_loginutype=='admin'){ ?>
									<label class="rzvy-toggle-switch">
									  <input type="checkbox" data-id="<?php echo $block_off['id']; ?>" class="rzvy-toggle-switch-input rzvy_change_blockoff_status" <?php if($block_off['status'] == "Y"){ echo "checked"; } ?> />
									  <span class="rzvy-toggle-switch-slider"></span>
									</label>
								<?php }elseif(!isset($rzvy_rolepermissions['rzvy_addon_multiqtystatus']) && $rzvy_loginutype=='staff'){ 
										if($block_off['status'] == "Y"){
											if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; }
										}else{
											if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; }
										}
									} ?>
							</td>
							<td>
								<?php if(isset($rzvy_rolepermissions['rzvy_blockoff_edit']) || $rzvy_loginutype=='admin'){ ?>
									<a href="javascript:void(0);" class="btn btn-primary rzvy-white btn-sm rzvy-update-blockoffmodal" data-id="<?php echo $block_off['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a> &nbsp; 
								<?php } if(isset($rzvy_rolepermissions['rzvy_blockoff_delete']) || $rzvy_loginutype=='admin'){ ?>
									<a href="javascript:void(0);" class="btn btn-danger rzvy-white btn-sm rzvy_delete_blockoff_btn" data-id="<?php echo $block_off['id']; ?>"><i class="fa fa-fw fa-trash"></i></a>
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
	<?php if(isset($rzvy_rolepermissions['rzvy_blockoff_add']) || $rzvy_loginutype=='admin'){ ?>  
	 <!-- Add Modal-->
	<div class="modal fade" id="rzvy-add-blockoff-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-add-blockoff-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-add-blockoff-modal-label"><?php if(isset($rzvy_translangArr['add_block_off'])){ echo $rzvy_translangArr['add_block_off']; }else{ echo $rzvy_defaultlang['add_block_off']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="rzvy_add_blockoff_form" id="rzvy_add_blockoff_form" method="post">
				<div class="row">
				  <div class="form-group col-md-12">
					<label for="rzvy_blockofftitle"><?php if(isset($rzvy_translangArr['block_off_title'])){ echo $rzvy_translangArr['block_off_title']; }else{ echo $rzvy_defaultlang['block_off_title']; } ?></label>
					<input class="form-control" id="rzvy_blockofftitle" name="rzvy_blockofftitle" type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_block_off_title'])){ echo $rzvy_translangArr['enter_block_off_title']; }else{ echo $rzvy_defaultlang['enter_block_off_title']; } ?>" />
				  </div>
				</div>
				<div class="row">
				  <div class="form-group col-md-6">
					<label for="rzvy_blockoff_fromdate"><?php if(isset($rzvy_translangArr['from_date'])){ echo $rzvy_translangArr['from_date']; }else{ echo $rzvy_defaultlang['from_date']; } ?></label>
					<input class="form-control" id="rzvy_blockoff_fromdate" name="rzvy_blockoff_fromdate" type="date" />
				  </div>
				  <div class="form-group col-md-6">
					<label for="rzvy_blockoff_todate"><?php if(isset($rzvy_translangArr['to_date'])){ echo $rzvy_translangArr['to_date']; }else{ echo $rzvy_defaultlang['to_date']; } ?></label>
					<input class="form-control" id="rzvy_blockoff_todate" name="rzvy_blockoff_todate" type="date" />
				  </div>
				</div>
				<div class="row">
				  <div class="form-group col-md-12">
					<label for="rzvy_blockoff_type"><?php if(isset($rzvy_translangArr['block_off_type'])){ echo $rzvy_translangArr['block_off_type']; }else{ echo $rzvy_defaultlang['block_off_type']; } ?></label>
					<div>
						<label><input type="radio" class="rzvy_blockoff_type" name="rzvy_blockoff_type" value="fullday" checked> <?php if(isset($rzvy_translangArr['fullday'])){ echo $rzvy_translangArr['fullday']; }else{ echo $rzvy_defaultlang['fullday']; } ?></label> &nbsp; <label><input type="radio" class="rzvy_blockoff_type" name="rzvy_blockoff_type" value="custom"> <?php if(isset($rzvy_translangArr['custom'])){ echo $rzvy_translangArr['custom']; }else{ echo $rzvy_defaultlang['custom']; } ?></label>
					</div>
				  </div>
				</div>
				<div class="rzvy_hide_blockoff_custom_box">
					<div class="row">
					  <div class="form-group col-md-6">
						<label for="rzvy_blockoff_fromtime"><?php if(isset($rzvy_translangArr['from_time'])){ echo $rzvy_translangArr['from_time']; }else{ echo $rzvy_defaultlang['from_time']; } ?></label>
						<input class="form-control" id="rzvy_blockoff_fromtime" name="rzvy_blockoff_fromtime" type="time" />
					  </div>
					  <div class="form-group col-md-6">
						<label for="rzvy_blockoff_totime"><?php if(isset($rzvy_translangArr['to_time'])){ echo $rzvy_translangArr['to_time']; }else{ echo $rzvy_defaultlang['to_time']; } ?></label>
						<input class="form-control" id="rzvy_blockoff_totime" name="rzvy_blockoff_totime" type="time" />
					  </div>
					</div>
				</div>
				<div class="row">
				  <div class="form-group col-md-12">
					<label for="rzvy_blockoff_status"><?php if(isset($rzvy_translangArr['block_off_status'])){ echo $rzvy_translangArr['block_off_status']; }else{ echo $rzvy_defaultlang['block_off_status']; } ?></label>
					<div>
						<label class="text-success"><input type="radio" class="rzvy_blockoff_status" name="rzvy_blockoff_status" value="Y" checked> <?php if(isset($rzvy_translangArr['activate'])){ echo $rzvy_translangArr['activate']; }else{ echo $rzvy_defaultlang['activate']; } ?></label> &nbsp; <label class="text-danger"><input type="radio" class="rzvy_blockoff_status" name="rzvy_blockoff_status" value="N"> <?php if(isset($rzvy_translangArr['deactivate'])){ echo $rzvy_translangArr['deactivate']; }else{ echo $rzvy_defaultlang['deactivate']; } ?></label>
					</div>
				  </div>
				</div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_add_blockoff_btn" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['add'])){ echo $rzvy_translangArr['add']; }else{ echo $rzvy_defaultlang['add']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } if(isset($rzvy_rolepermissions['rzvy_blockoff_edit']) || $rzvy_loginutype=='admin'){ ?>
	 <!-- Update Modal-->
	<div class="modal fade" id="rzvy-update-blockoff-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-update-blockoff-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-update-blockoff-modal-label"><?php if(isset($rzvy_translangArr['update_block_off'])){ echo $rzvy_translangArr['update_block_off']; }else{ echo $rzvy_defaultlang['update_block_off']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy-update-blockoff-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_update_blockoff_btn" data-id="" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['update'])){ echo $rzvy_translangArr['update']; }else{ echo $rzvy_defaultlang['update']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } ?>
<?php include 'footer.php'; ?>