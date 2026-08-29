<?php 
include 'header.php';
if(!isset($rzvy_rolepermissions['rzvy_subcategories']) && $rzvy_loginutype=='staff'){ ?>
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
$pcid = 0;
$all_categories1 = array();
$all_categories2 = array();
if(isset($_GET['pcid'])){
	if($rzvy_parent_category == "Y"){
		if($_GET['pcid']>0){
			$all_categories = $obj_categories->get_all_categories_by_pcid($_GET['pcid']);
			if(mysqli_num_rows($all_categories)>0){
				while($category = mysqli_fetch_assoc($all_categories)){
					array_push($all_categories1,$category);
					array_push($all_categories2,$category);
				}
			}
		}
		$pcid = $_GET['pcid'];
	}else{
		?>
		<script>
		window.location.href = "<?php echo SITE_URL; ?>backend/category.php";
		</script>
		<?php 
		exit;
	}
}else{
	$all_categories = $obj_categories->get_all_categories();
	if(mysqli_num_rows($all_categories)>0){
		while($category = mysqli_fetch_assoc($all_categories)){
			array_push($all_categories1,$category);
			array_push($all_categories2,$category);
		}
	}
} 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
		<?php if(isset($_GET['pcid']) && $rzvy_parent_category == "Y"){ ?>
			<li class="breadcrumb-item">
			  <a href="<?php echo SITE_URL; ?>backend/p-category.php"><?php if(isset($rzvy_translangArr['parent_categories'])){ echo $rzvy_translangArr['parent_categories']; }else{ echo $rzvy_defaultlang['parent_categories']; } ?></a>
			</li>
			<li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['sub_categories'])){ echo $rzvy_translangArr['sub_categories']; }else{ echo $rzvy_defaultlang['sub_categories']; } ?></li>
		<?php }else{ ?>
			<li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['categories'])){ echo $rzvy_translangArr['categories']; }else{ echo $rzvy_defaultlang['categories']; } ?></li>
		<?php } ?>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-book"></i> <?php if(isset($rzvy_translangArr['category_list'])){ echo $rzvy_translangArr['category_list']; }else{ echo $rzvy_defaultlang['category_list']; } ?>
		   <?php if(isset($rzvy_rolepermissions['rzvy_subcategories_reorder_allservices']) || $rzvy_loginutype=='admin'){ ?>
				<a class="btn btn-primary btn-sm rzvy-white pull-right mx-1" data-toggle="modal" data-target="#rzvy-serreorder-modal"><i class="fa fa-sort"></i> <?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?> <?php if(isset($rzvy_translangArr['all_services'])){ echo $rzvy_translangArr['all_services']; }else{ echo $rzvy_defaultlang['all_services']; } ?></a>
		  <?php } if(isset($rzvy_rolepermissions['rzvy_subcategories_reorder']) || $rzvy_loginutype=='admin'){ ?>		
				<a class="btn btn-primary btn-sm rzvy-white pull-right mx-1" data-toggle="modal" data-target="#rzvy-reorder-modal"><i class="fa fa-sort"></i> <?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?> <?php if(isset($rzvy_translangArr['categories'])){ echo $rzvy_translangArr['categories']; }else{ echo $rzvy_defaultlang['categories']; } ?></a>
		  <?php } if(isset($rzvy_rolepermissions['rzvy_subcategories_add']) || $rzvy_loginutype=='admin'){ ?>
				<a class="btn btn-primary btn-sm rzvy-white pull-right mx-1" data-toggle="modal" data-target="#rzvy-add-category-modal"><i class="fa fa-plus"></i> <?php if(isset($rzvy_translangArr['add_category'])){ echo $rzvy_translangArr['add_category']; }else{ echo $rzvy_defaultlang['add_category']; } ?></a>
		  <?php } ?>
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="display responsive nowrap" width="100%" id="rzvy_categories_list_table" cellspacing="0">
              <thead>
				<tr>
				  <th><?php if(isset($rzvy_translangArr['hash_rzy_translation'])){ echo $rzvy_translangArr['hash_rzy_translation']; }else{ echo $rzvy_defaultlang['hash_rzy_translation']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['category_name'])){ echo $rzvy_translangArr['category_name']; }else{ echo $rzvy_defaultlang['category_name']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['category_status'])){ echo $rzvy_translangArr['category_status']; }else{ echo $rzvy_defaultlang['category_status']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				if(sizeof($all_categories1)>0){
					$i = 1;
					foreach($all_categories1 as $category){
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
								<?php $checked = ''; if($category['status'] == "Y"){ $checked = "checked"; } ?> 
								<?php if(isset($rzvy_rolepermissions['rzvy_subcategories_status']) || $rzvy_loginutype=='admin'){ ?>
										<label class="rzvy-toggle-switch">
											<input type="checkbox" data-id="<?php echo $category['id']; ?>" class="rzvy-toggle-switch-input rzvy_change_category_status" <?php echo $checked; ?> />
											<span class="rzvy-toggle-switch-slider"></span>
										</label>
								<?php }elseif(!isset($rzvy_rolepermissions['rzvy_subcategories_status']) && $rzvy_loginutype=='staff'){ 
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
								if(isset($rzvy_translangArr['services'])){ 
									$services_trans = $rzvy_translangArr['services']; 
								}else{ 
									$services_trans = $rzvy_defaultlang['services']; 
								} 
								?>
								<?php if(isset($rzvy_rolepermissions['rzvy_subcategories_view_allservices']) || $rzvy_loginutype=='admin'){ ?>
									<a class="btn btn-secondary btn-sm m-1" href="<?php echo SITE_URL; ?>backend/services.php?cid=<?php echo $category["id"]; ?>" data-id="<?php echo $category['id']; ?>"><i class="fa fa-fw fa-th-list"></i> <?php echo $services_trans; ?> <span class="badge badge-light"><?php echo $total_services; ?></span></a>
								<?php } if(isset($rzvy_rolepermissions['rzvy_subcategories_edit']) || $rzvy_loginutype=='admin'){ ?>
									<a class="btn btn-primary rzvy-white btn-sm rzvy-update-categorymodal m-1" data-id="<?php echo $category['id']; ?>"><i class="fa fa-fw fa-pencil"></i></a> 
								<?php } if(isset($rzvy_rolepermissions['rzvy_subcategories_delete']) || $rzvy_loginutype=='admin'){ ?>	
								<a class="btn btn-danger rzvy-white btn-sm rzvy_delete_category_btn m-1" data-id="<?php echo $category['id']; ?>"><i class="fa fa-fw fa-trash"></i></a>
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
	<?php if(isset($rzvy_rolepermissions['rzvy_subcategories_add']) || $rzvy_loginutype=='admin'){ ?>   
	<!-- Add Modal-->
	<div class="modal fade" id="rzvy-add-category-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-add-category-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-add-category-modal-label"><?php if(isset($rzvy_translangArr['add_category'])){ echo $rzvy_translangArr['add_category']; }else{ echo $rzvy_defaultlang['add_category']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body">
			<form name="rzvy_add_category_form" id="rzvy_add_category_form" method="post">
				<div class="form-group">
					<input id="rzvy_pcid" name="rzvy_pcid" type="hidden" value="<?php echo $pcid; ?>" />
					<label for="rzvy_categoryname"><?php if(isset($rzvy_translangArr['category_name'])){ echo $rzvy_translangArr['category_name']; }else{ echo $rzvy_defaultlang['category_name']; } ?></label>
					<input class="form-control" id="rzvy_categoryname" name="rzvy_categoryname" type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_category_name'])){ echo $rzvy_translangArr['enter_category_name']; }else{ echo $rzvy_defaultlang['enter_category_name']; } ?>" />
				</div>
			  
				<div class="form-group">
					<label for="rzvy_categoryimage"><?php if(isset($rzvy_translangArr['category_image'])){ echo $rzvy_translangArr['category_image']; }else{ echo $rzvy_defaultlang['category_image']; } ?></label>
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
				<label for="rzvy_categorystatus"><?php if(isset($rzvy_translangArr['category_status'])){ echo $rzvy_translangArr['category_status']; }else{ echo $rzvy_defaultlang['category_status']; } ?></label>
				<div>
					<label class="text-success"><input type="radio" name="rzvy_categorystatus" value="Y" checked> <?php if(isset($rzvy_translangArr['activate'])){ echo $rzvy_translangArr['activate']; }else{ echo $rzvy_defaultlang['activate']; } ?></label> &nbsp; &nbsp;<label class="text-danger"><input type="radio" name="rzvy_categorystatus" value="N"> <?php if(isset($rzvy_translangArr['deactivate'])){ echo $rzvy_translangArr['deactivate']; }else{ echo $rzvy_defaultlang['deactivate']; } ?></label>
				</div>
			  </div>
			</form>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_add_category_btn" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['add'])){ echo $rzvy_translangArr['add']; }else{ echo $rzvy_defaultlang['add']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } if(isset($rzvy_rolepermissions['rzvy_subcategories_edit']) || $rzvy_loginutype=='admin'){ ?> 
	<!-- Update Modal-->
	<div class="modal fade" id="rzvy-update-category-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-update-category-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-update-category-modal-label"><?php if(isset($rzvy_translangArr['update_category'])){ echo $rzvy_translangArr['update_category']; }else{ echo $rzvy_defaultlang['update_category']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy-update-category-modal-body">
			
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_update_category_btn" data-id="" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['update'])){ echo $rzvy_translangArr['update']; }else{ echo $rzvy_defaultlang['update']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } if(isset($rzvy_rolepermissions['rzvy_subcategories_reorder']) || $rzvy_loginutype=='admin'){ ?> 
	<!-- Reorder Modal-->
	<div class="modal fade" id="rzvy-reorder-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-reorder-modal-label" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-reorder-modal-label"><?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?> <?php if(isset($rzvy_translangArr['categories'])){ echo $rzvy_translangArr['categories']; }else{ echo $rzvy_defaultlang['categories']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body">
			<ul class="cat_reorder rzvy_reorder_ul m-1 row">
				<?php 
				if(sizeof($all_categories2)>0){
					$i = 1;
					foreach($all_categories2 as $category){
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
			<a id="rzvy_category_reorder_btn" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } if(isset($rzvy_rolepermissions['rzvy_subcategories_reorder_allservices']) || $rzvy_loginutype=='admin'){ ?> 
	<!-- Reorder Service Modal-->
	<div class="modal fade" id="rzvy-serreorder-modal" tabindex="-1" role="dialog" aria-labelledby="rzvy-serreorder-modal-label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy-serreorder-modal-label"><?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?> <?php if(isset($rzvy_translangArr['all_services'])){ echo $rzvy_translangArr['all_services']; }else{ echo $rzvy_defaultlang['all_services']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body">
			<ul class="allser_reorder rzvy_reorder_ul m-1 row">
				<?php 
				$all_services = $obj_services->get_all_services_without_Cat_id();
				if(mysqli_num_rows($all_services)>0){
					$i=1;
					while($service = mysqli_fetch_assoc($all_services)){
						?>
						<li class="m-1 p-3 bg-light ui-state-default col-md-5" data-id="<?php echo $service['id']; ?>"><span class="col-md-1" style="border-right: 1px solid #cecece;"><i class="fa fa-arrows"></i> <?php echo $i; ?></span><span class="col-md-4"><?php echo ucwords($service['title']); ?></span></li>
						<?php 
						$i++;
					}
				} 
				?>
			</ul>
		  </div>
		  <div class="modal-footer">
			<button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_allser_reorder_btn" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?></a>
		  </div>
		</div>
	  </div>
	</div>
	<?php } ?>
<?php include 'footer.php'; ?>