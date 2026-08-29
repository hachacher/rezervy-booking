<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_categories.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_services.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_addons.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */

$obj_categories = new rzvy_categories();
$obj_categories->conn = $conn;

$obj_services = new rzvy_services();
$obj_services->conn = $conn;

$obj_addons = new rzvy_addons();
$obj_addons->conn = $conn;

$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

$image_upload_path = SITE_URL."/uploads/images/";
$image_upload_abs_path = dirname(dirname(dirname(__FILE__)))."/uploads/images/";

/* Add addon ajax */
if(isset($_POST['add_addon'])){
	$obj_addons->title = htmlspecialchars(($_POST['title']), ENT_QUOTES);
	$obj_addons->rate = $_POST['rate'];
	$obj_addons->description = htmlspecialchars(($_POST['description']), ENT_QUOTES);
	$obj_addons->multiple_qty = $_POST['multiple_qty'];
	$obj_addons->status = $_POST['status'];
	$obj_addons->max_limit = $_POST['max_limit'];
	$obj_addons->min_limit = $_POST['min_limit'];
	$obj_addons->duration = $_POST['duration'];
	$obj_addons->badge = $_POST['badge'];
	$obj_addons->badge_text = $_POST['badge_text'];
	if($_POST['uploaded_file'] != ""){
		$new_filename = time();
		$uploaded_filename = $obj_settings->rzvy_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_addons->image = $uploaded_filename;
	}else{
		$obj_addons->image = "";
	}
	$added = $obj_addons->add_addon();
	if($added){
		echo "added";
	}else{
		echo "failed";
	}
}

/* Update addon modal detail ajax */
else if(isset($_POST['update_addon_modal_detail'])){
	$obj_addons->id = $_POST['id'];
	$addon = $obj_addons->readone_addon();
	?>
	<form name="rzvy_update_addon_form" id="rzvy_update_addon_form" method="post">
		<input type="hidden" value="<?php echo $addon['id'] ?>" id="rzvy_update_addonid_hidden" />
		<div class="row">
		  <div class="form-group col-md-4">
			<label for="rzvy_update_addonname"><?php if(isset($rzvy_translangArr['addon_name'])){ echo $rzvy_translangArr['addon_name']; }else{ echo $rzvy_defaultlang['addon_name']; } ?></label>
			<input class="form-control" id="rzvy_update_addonname" name="rzvy_update_addonname" type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_addon_name'])){ echo $rzvy_translangArr['enter_addon_name']; }else{ echo $rzvy_defaultlang['enter_addon_name']; } ?>" value="<?php echo $addon['title'] ?>" />
		  </div>
		  <div class="form-group col-md-4">
			<label for="rzvy_update_addonminlimit"><?php if(isset($rzvy_translangArr['addon_minlimit'])){ echo $rzvy_translangArr['addon_minlimit']; }else{ echo $rzvy_defaultlang['addon_minlimit']; } ?></label>
			<input class="form-control" id="rzvy_update_addonminlimit" name="rzvy_update_addonminlimit" type="number" value="<?php echo $addon['min_limit'] ?>" min="1" />
		  </div>
		  <div class="form-group col-md-4">
			<label for="rzvy_update_addonmaxlimit"><?php if(isset($rzvy_translangArr['addon_maxlimit'])){ echo $rzvy_translangArr['addon_maxlimit']; }else{ echo $rzvy_defaultlang['addon_maxlimit']; } ?></label>
			<input class="form-control" id="rzvy_update_addonmaxlimit" name="rzvy_update_addonmaxlimit" type="number" value="<?php echo $addon['max_limit'] ?>" min="1" />
		  </div>
		  <div class="form-group col-md-6">
			<label for="rzvy_update_addonrate"><?php if(isset($rzvy_translangArr['addon_rate'])){ echo $rzvy_translangArr['addon_rate']; }else{ echo $rzvy_defaultlang['addon_rate']; } ?></label>
			<input class="form-control" id="rzvy_update_addonrate" name="rzvy_update_addonrate" type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_addon_rate'])){ echo $rzvy_translangArr['enter_addon_rate']; }else{ echo $rzvy_defaultlang['enter_addon_rate']; } ?>" value="<?php echo $addon['rate'] ?>" />
		  </div>
		  <div class="form-group col-md-6">
			<label for="rzvy_update_addonduration"><?php if(isset($rzvy_translangArr['addon_duration'])){ echo $rzvy_translangArr['addon_duration']; }else{ echo $rzvy_defaultlang['addon_duration']; } ?></label>
			<input class="form-control" id="rzvy_update_addonduration" name="rzvy_update_addonduration" type="number" value="0" min="0" placeholder="<?php if(isset($rzvy_translangArr['enter_addon_duration'])){ echo $rzvy_translangArr['enter_addon_duration']; }else{ echo $rzvy_defaultlang['enter_addon_duration']; } ?>" value="<?php echo $addon['duration'] ?>" />
		  </div>
		  <div class="form-group col-md-6">
			<label for="rzvy_update_addondescription"><?php if(isset($rzvy_translangArr['addon_description'])){ echo $rzvy_translangArr['addon_description']; }else{ echo $rzvy_defaultlang['addon_description']; } ?></label>
			<textarea class="form-control" id="rzvy_update_addondescription" name="rzvy_update_addondescription" placeholder="<?php if(isset($rzvy_translangArr['enter_addon_description'])){ echo $rzvy_translangArr['enter_addon_description']; }else{ echo $rzvy_defaultlang['enter_addon_description']; } ?>"><?php echo $addon['description'] ?></textarea>
		  </div>
		  <div class="form-group col-md-3">
				<label for="rzvy_update_addon_badge"><?php if(isset($rzvy_translangArr['show_badge'])){ echo $rzvy_translangArr['show_badge']; }else{ echo $rzvy_defaultlang['show_badge']; } ?></label>
				<select class="form-control" id="rzvy_update_addon_badge" name="rzvy_update_addon_badge">
					<option value="Y" <?php if($addon['badge'] == "Y"){ echo "selected"; } ?>><?php if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; } ?></option>
					<option <?php if($addon['badge'] != "Y"){ echo "selected"; } ?> value="N"><?php if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; } ?></option>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label for="rzvy_update_addon_badge_text"><?php if(isset($rzvy_translangArr['badge_content'])){ echo $rzvy_translangArr['badge_content']; }else{ echo $rzvy_defaultlang['badge_content']; } ?></label>
				<input class="form-control" id="rzvy_update_addon_badge_text" name="rzvy_update_addon_badge_text" type="text" placeholder="<?php if(isset($rzvy_translangArr['trending'])){ echo $rzvy_translangArr['trending']; }else{ echo $rzvy_defaultlang['trending']; } ?>" maxlength="10" value="<?php echo $addon['badge_text']; ?>" />
			</div>
		  <div class="form-group col-md-6">
			<label for="rzvy_update_addonimage"><?php if(isset($rzvy_translangArr['addon_image'])){ echo $rzvy_translangArr['addon_image']; }else{ echo $rzvy_defaultlang['addon_image']; } ?></label>
			<div class="rzvy-image-upload">
				<div class="rzvy-image-edit-icon">
					<input type='hidden' id="rzvy-update-image-upload-file-hidden" name="rzvy-update-image-upload-file-hidden" />
					<input type='file' id="rzvy-update-image-upload-file" accept=".png, .jpg, .jpeg" />
					<label for="rzvy-update-image-upload-file"></label>
				</div>
				<div class="rzvy-image-preview">
					<div id="rzvy-update-image-upload-file-preview" style="<?php $addon_image = $addon['image']; if($addon_image != '' && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$addon_image)){ echo "background-image: url(".SITE_URL."uploads/images/".$addon_image.");"; }else{ echo "background-image: url(".SITE_URL."includes/images/default-service.png);"; } ?>">
					</div>
				</div>
			</div>
		  </div>
		</div>
	</form>
	<?php
}

/* Update addon ajax */
else if(isset($_POST['update_addon'])){
	$obj_addons->id = $_POST['id'];
	$obj_addons->title = htmlspecialchars(($_POST['title']), ENT_QUOTES);
	$obj_addons->rate = $_POST['rate'];
	$obj_addons->max_limit = $_POST['max_limit'];
	$obj_addons->min_limit = $_POST['min_limit'];
	$obj_addons->duration = $_POST['duration'];
	$obj_addons->description = htmlspecialchars(($_POST['description']), ENT_QUOTES);
	$obj_addons->badge = $_POST['badge'];
	$obj_addons->badge_text = $_POST['badge_text'];
	$addon = $obj_addons->readone_addon();
	$old_image = $addon['image'];
	if($_POST['uploaded_file'] != ""){
		if($old_image != ""){
			if(file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$old_image)){
				unlink(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$old_image);
			}
		}
		$new_filename = time();
		$uploaded_filename = $obj_settings->rzvy_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_addons->image = $uploaded_filename;
	}else{
		$obj_addons->image = $old_image;
	}
	$updated = $obj_addons->update_addon();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}

/* View addon modal detail ajax */
else if(isset($_POST['view_addon_modal_detail'])){
	$obj_addons->id = $_POST['id'];
	$addon = $obj_addons->readone_addon();
	?>
	<div class="row">
		<div class="col-md-8">
			<div class="content-heading"><h3><?php echo ucwords($addon['title']); ?></h3></div>
			<p><?php if(isset($rzvy_translangArr['rate_ad'])){ echo $rzvy_translangArr['rate_ad']; }else{ echo $rzvy_defaultlang['rate_ad']; } ?> <?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$addon['rate']); ?></p>
			<p><?php if(isset($rzvy_translangArr['min_limit_ad'])){ echo $rzvy_translangArr['min_limit_ad']; }else{ echo $rzvy_defaultlang['min_limit_ad']; } ?> <?php echo $addon['min_limit']; ?></p>
			<p><?php if(isset($rzvy_translangArr['max_limit_ad'])){ echo $rzvy_translangArr['max_limit_ad']; }else{ echo $rzvy_defaultlang['max_limit_ad']; } ?> <?php echo $addon['max_limit']; ?></p>
			<p><?php if(isset($rzvy_translangArr['duration_ad'])){ echo $rzvy_translangArr['duration_ad']; }else{ echo $rzvy_defaultlang['duration_ad']; } ?> <?php echo $addon['duration']." Minutes"; ?></p>
			<p><?php if(isset($rzvy_translangArr['multiple_qty_ad'])){ echo $rzvy_translangArr['multiple_qty_ad']; }else{ echo $rzvy_defaultlang['multiple_qty_ad']; } ?> <?php if($addon['multiple_qty'] == "Y"){ ?><label class="text-success"><?php if(isset($rzvy_translangArr['activated'])){ echo $rzvy_translangArr['activated']; }else{ echo $rzvy_defaultlang['activated']; } ?></label><?php }else{ ?><label class="text-danger"><?php if(isset($rzvy_translangArr['deactivated'])){ echo $rzvy_translangArr['deactivated']; }else{ echo $rzvy_defaultlang['deactivated']; } ?></label><?php } ?></p>
			<p><?php if(isset($rzvy_translangArr['status_ad'])){ echo $rzvy_translangArr['status_ad']; }else{ echo $rzvy_defaultlang['status_ad']; } ?> <?php if($addon['status'] == "Y"){ ?><label class="text-success"><?php if(isset($rzvy_translangArr['activated'])){ echo $rzvy_translangArr['activated']; }else{ echo $rzvy_defaultlang['activated']; } ?></label><?php }else{ ?><label class="text-danger"><?php if(isset($rzvy_translangArr['deactivated'])){ echo $rzvy_translangArr['deactivated']; }else{ echo $rzvy_defaultlang['deactivated']; } ?></label><?php } ?></p>
		</div>
		<div class="col-md-2">
			<img class="rzvy-view-addon-modal-image" src="<?php $addon_image = $addon['image']; if($addon_image != '' && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$addon_image)){ echo SITE_URL."uploads/images/".$addon_image; }else{ echo SITE_URL."includes/images/default-service.png"; } ?>"/>
		</div>
		<div class="col-md-12">
			<p><?php echo $addon['description']; ?></p>
		</div>
	</div>
	<?php
}

/* Delete addon ajax */
else if(isset($_POST['delete_addon'])){
	$obj_addons->id = $_POST['id'];
	$check_appointments = $obj_addons->check_appointments_before_delete_addon();
	if($check_appointments == "appointmentexist"){
		echo "appointments exist";
	}else if($check_appointments == "noappointmentexist"){
		$addon = $obj_addons->readone_addon();
		$old_image = $addon['image'];
		if($old_image != ""){
			if(file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$old_image)){
				unlink(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$old_image);
			}
		}
		$deleted = $obj_addons->delete_addon();
		if($deleted){
			echo "deleted";
		}else{
			echo "failed";
		}
	}
}

/* Change addon status ajax */
else if(isset($_POST['change_addon_status'])){
	$obj_addons->id = $_POST['id'];
	$obj_addons->status = $_POST['status'];
	$status_changed = $obj_addons->change_addon_status();
	if($status_changed){
		echo "changed";
	}else{
		echo "failed";
	}
}

/* Change addon multiple qty status ajax */
else if(isset($_POST['change_addon_multiple_qty_status'])){
	$obj_addons->id = $_POST['id'];
	$obj_addons->multiple_qty = $_POST['multiple_qty'];
	$status_changed = $obj_addons->change_addon_multiple_qty_status();
	if($status_changed){
		echo "changed";
	}else{
		echo "failed";
	}
}

else if(isset($_POST['assign_addon_services'])){
	$obj_addons->id = $_POST['id'];
	$obj_addons->unlink_all_services_for_selected_addon();
	if(isset($_POST['service_ids'])){
		$service_ids = $_POST['service_ids'];
		if(sizeof($service_ids)>0){
			for($i=0; $i<sizeof($service_ids);$i++){
				$obj_addons->link_service_to_selected_addon($service_ids[$i]);
			}
		}
	}
}

else if(isset($_POST['reorder_addons'])){
	$reorder = $_POST['reorder'];
	$service_id = $_POST['sid'];
	if(sizeof($reorder)>0){
		foreach($reorder as $order){
			$obj_addons->update_addon_position($service_id, $order["id"], $order["order"]);
		}
		echo "success";
	}
}