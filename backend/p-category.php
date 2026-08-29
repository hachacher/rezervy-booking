<?php include 'header.php';
if(!isset($rzvy_rolepermissions['rzvy_pcategories']) && $rzvy_loginutype=='staff'){ ?>
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
if($rzvy_parent_category != "Y"){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/category.php";
	</script>
	<?php  
	exit;
}
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['parent_categories'])){ echo $rzvy_translangArr['parent_categories']; }else{ echo $rzvy_defaultlang['parent_categories']; } ?></li>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-book"></i> <?php if(isset($rzvy_translangArr['parent_categories_list'])){ echo $rzvy_translangArr['parent_categories_list']; }else{ echo $rzvy_defaultlang['parent_categories_list']; } ?>
		  <?php if(isset($rzvy_rolepermissions['rzvy_pcategories_view_subcate']) || $rzvy_loginutype=='admin'){ ?>
			<a class="btn btn-primary btn-sm rzvy-white pull-right mx-1" href="<?php echo SITE_URL; ?>backend/category.php"><i class="fa fa-th-list"></i> <?php if(isset($rzvy_translangArr['all_sub_categories'])){ echo $rzvy_translangArr['all_sub_categories']; }else{ echo $rzvy_defaultlang['all_sub_categories']; } ?></a>
		 <?php } if(isset($rzvy_rolepermissions['rzvy_pcategories_reorder']) || $rzvy_loginutype=='admin'){ ?>
			<a class="btn btn-primary btn-sm rzvy-white pull-right mx-1" data-toggle="modal" data-target="#rzvy-pcatreorder-modal"><i class="fa fa-sort"></i> <?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?> <?php if(isset($rzvy_translangArr['parent_categories'])){ echo $rzvy_translangArr['parent_categories']; }else{ echo $rzvy_defaultlang['parent_categories']; } ?></a>
		  <?php } if(isset($rzvy_rolepermissions['rzvy_pcategories_add']) || $rzvy_loginutype=='admin'){ ?>
			<a class="btn btn-primary btn-sm rzvy-white pull-right mx-1" data-toggle="modal" data-target="#rzvy-add-pcategory-modal"><i class="fa fa-plus"></i> <?php if(isset($rzvy_translangArr['add_parent_category'])){ echo $rzvy_translangArr['add_parent_category']; }else{ echo $rzvy_defaultlang['add_parent_category']; } ?></a>
		  <?php } ?>
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="display responsive nowrap" width="100%" id="rzvy_pcategories_list_table" cellspacing="0">
              <thead>
				<tr>
				  <th><?php if(isset($rzvy_translangArr['hash_rzy_translation'])){ echo $rzvy_translangArr['hash_rzy_translation']; }else{ echo $rzvy_defaultlang['hash_rzy_translation']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['name'])){ echo $rzvy_translangArr['name']; }else{ echo $rzvy_defaultlang['name']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['assigned_sub_categories'])){ echo $rzvy_translangArr['assigned_sub_categories']; }else{ echo $rzvy_defaultlang['assigned_sub_categories']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['status'])){ echo $rzvy_translangArr['status']; }else{ echo $rzvy_defaultlang['status']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				$all_categories = $obj_pcategories->get_all_parent_categories();
				if(mysqli_num_rows($all_categories)>0){
					$i = 1;
					while($category = mysqli_fetch_assoc($all_categories)){
						?>
						<tr>
							<td><?php echo $i; ?></td>
							<td>
								<?php 
								$default_img = SITE_URL."includes/images/default-service.png";
								$uploaded_img = "";
								$category_image = $category['image']; 
								if($category_image != '' && file_exists(dirname(dirname(__FILE__))."/uploads/images/".$category_image)){ 
									$uploaded_img = SITE_URL."uploads/images/".$category_image; 
									?>
									<img src="<?php if($uploaded_img != ""){ echo $uploaded_img; }else{ echo $default_img; } ?>" alt="<?php echo $default_img; ?>" class="img-thumbnail" width="50"> 
									<?php 
								}
								echo ucwords($category['cat_name']); ?>
							</td>
							<td>
								<select data-live-search="true" <?php if(isset($rzvy_rolepermissions['rzvy_pcategories_assign_subcate']) || $rzvy_loginutype=='admin'){ ?> data-id="<?php echo $category['id']; ?>" name="rzvy_assign_category" class="rzvy_assign_category form-control rzvy_assign_category_dd" multiple <?php }else{ echo 'class="rzvy_assign_category_dd form-control"'; } ?>  >
									<?php 
									$linked_subcat = array();
									if($category['linked_subcat'] != ""){
										$linked_subcat = explode(",",$category['linked_subcat']);
									}
									$get_all_subcategories = $obj_pcategories->get_all_subcategories();
									while($data = mysqli_fetch_array($get_all_subcategories)){
										$selected = '';
										if(in_array($data["id"], $linked_subcat)){ $selected = "selected"; }
										echo '<option value="'.$data["id"].'" '.$selected.'>'.ucwords($data["cat_name"]).'</option>';
									} 
									?>
								</select>
							</td>
							<td>
								<?php $checked = ''; if($category['status'] == "Y"){ $checked = "checked"; } ?> 
								<?php if(isset($rzvy_rolepermissions['rzvy_pcategories_status']) || $rzvy_loginutype=='admin'){ ?>
									<label class="rzvy-toggle-switch">
										<input type="checkbox" data-id="<?php echo $category['id']; ?>" class="rzvy-toggle-switch-input rzvy_change_pcategory_status" <?php echo $checked; ?> />
										<span class="rzvy-toggle-switch-slider"></span>
									</label>
								<?php }elseif(!isset($rzvy_rolepermissions['rzvy_pcategories_status']) && $rzvy_loginutype=='staff'){ 
										if($category['status'] == "Y"){
											if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; }
										}else{
											if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; }
										}
								 } ?>
							</td>
							<td>
								<?php
								$obj_services->cat_id = $category['id'];
								$total_services = $obj_services->count_all_services_by_cat_id();
								if(isset($rzvy_translangArr['sub_categories'])){ 
									$sub_categories_trans = $rzvy_translangArr['sub_categories']; 
								}else{ 
									$sub_categories_trans = $rzvy_defaultlang['sub_categories']; 
								} 
								?>
								<?php if(isset($rzvy_rolepermissions['rzvy_pcategories_view_subcate']) || $rzvy_loginutype=='admin'){ ?>
									<a class="btn btn-secondary btn-sm m-1" href="<?php echo SITE_URL; ?>backend/category.php?pcid=<?php echo $category["id"]; ?>" data-id="<?php echo $category['id']; ?>"><i class="fa fa-fw fa-th-list"></i> <?php echo $sub_categories_trans; ?></a>
								<?php } if(isset($rzvy_rolepermissions['rzvy_pcategories_edit']) || $rzvy_loginutype=='admin'){ ?>
									<a class="btn btn-primary rzvy-white btn-sm rzvy-update-pcategorymodal m-1" data-id="<?php echo $category['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a>
								<?php } if(isset($rzvy_rolepermissions['rzvy_pcategories_delete']) || $rzvy_loginutype=='admin'){ ?>
									<a class="btn btn-danger rzvy-white btn-sm rzvy_delete_pcategory_btn m-1" data-id="<?php echo $category['id']; ?>"><i class="fa fa-fw fa-trash"></i></a>
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
	<?php if(isset($rzvy_rolepermissions['rzvy_pcategories_add']) || $rzvy_loginutype=='admin'){ ?>  
	<!-- Add Modal-->
	<div class="modal fade" id="rzvy-add-pcategory-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-add-pcategory-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-add-pcategory-modal-label"><?php if(isset($rzvy_translangArr['add_parent_category'])){ echo $rzvy_translangArr['add_parent_category']; }else{ echo $rzvy_defaultlang['add_parent_category']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="rzvy_add_pcategory_form" id="rzvy_add_pcategory_form" method="post">
				<div class="form-group">
					<label for="rzvy_pcategoryname"><?php if(isset($rzvy_translangArr['name'])){ echo $rzvy_translangArr['name']; }else{ echo $rzvy_defaultlang['name']; } ?></label>
					<input class="form-control" id="rzvy_pcategoryname" name="rzvy_pcategoryname" type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_name'])){ echo $rzvy_translangArr['enter_name']; }else{ echo $rzvy_defaultlang['enter_name']; } ?>" />
				</div>
			  
				<div class="form-group">
					<label for="rzvy_pcategoryimage"><?php if(isset($rzvy_translangArr['image'])){ echo $rzvy_translangArr['image']; }else{ echo $rzvy_defaultlang['image']; } ?></label>
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
			  <div class="form-group">
				<label for="rzvy_pcategorystatus"><?php if(isset($rzvy_translangArr['status'])){ echo $rzvy_translangArr['status']; }else{ echo $rzvy_defaultlang['status']; } ?></label>
				<div>
					<label class="text-success"><input type="radio" name="rzvy_pcategorystatus" value="Y" checked> <?php if(isset($rzvy_translangArr['activate'])){ echo $rzvy_translangArr['activate']; }else{ echo $rzvy_defaultlang['activate']; } ?></label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="rzvy_pcategorystatus" value="N"> <?php if(isset($rzvy_translangArr['deactivate'])){ echo $rzvy_translangArr['deactivate']; }else{ echo $rzvy_defaultlang['deactivate']; } ?></label>
				</div>
			  </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_add_pcategory_btn" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['add'])){ echo $rzvy_translangArr['add']; }else{ echo $rzvy_defaultlang['add']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } if(isset($rzvy_rolepermissions['rzvy_pcategories_edit']) || $rzvy_loginutype=='admin'){ ?>
	<!-- Update Modal-->
	<div class="modal fade" id="rzvy-update-pcategory-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-update-pcategory-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-update-pcategory-modal-label"><?php if(isset($rzvy_translangArr['update_parent_category'])){ echo $rzvy_translangArr['update_parent_category']; }else{ echo $rzvy_defaultlang['update_parent_category']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy-update-pcategory-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_update_pcategory_btn" data-id="" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['update'])){ echo $rzvy_translangArr['update']; }else{ echo $rzvy_defaultlang['update']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } if(isset($rzvy_rolepermissions['rzvy_pcategories_reorder']) || $rzvy_loginutype=='admin'){ ?>
	<!-- Reorder Modal-->
	<div class="modal fade" id="rzvy-pcatreorder-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-pcatreorder-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-pcatreorder-modal-label"><?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?> <?php if(isset($rzvy_translangArr['parent_categories'])){ echo $rzvy_translangArr['parent_categories']; }else{ echo $rzvy_defaultlang['parent_categories']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body">
			<ul class="pcat_reorder rzvy_reorder_ul m-1 row">
				<?php 
				$all_categories = $obj_pcategories->get_all_parent_categories();
				if(mysqli_num_rows($all_categories)>0){
					$i = 1;
					while($category = mysqli_fetch_assoc($all_categories)){
						?>
						<li class="p-3 m-1 bg-light ui-state-default col-md-12" data-id="<?php echo $category['id']; ?>"><i class="fa fa-arrows pr-2"></i> <?php echo ucwords($category['cat_name']); ?></li>
						<?php 
						$i++;
					}
				} 
				?>
			</ul>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_pcategory_reorder_btn" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } ?>
<?php include 'footer.php'; ?>