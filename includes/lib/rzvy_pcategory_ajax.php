<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_parent_categories.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */

$obj_pcategories = new rzvy_parent_categories();
$obj_pcategories->conn = $conn;

$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

$image_upload_path = SITE_URL."/uploads/images/";
$image_upload_abs_path = dirname(dirname(dirname(__FILE__)))."/uploads/images/";

/* Change pcategory status ajax */
if(isset($_POST['change_pcategory_status'])){
	$obj_pcategories->id = $_POST['id'];
	$obj_pcategories->status = $_POST['pcategory_status'];
	$status_changed = $obj_pcategories->change_category_status();
	if($status_changed){
		echo "changed";
	}else{
		echo "failed";
	}
}

/* Delete category ajax */
else if(isset($_POST['delete_pcategory'])){
	$obj_pcategories->id = $_POST['id'];
	$deleted = $obj_pcategories->delete_category();
	if($deleted){
		echo "deleted";
	}else{
		echo "failed";
	}
}

/* Add pcategory ajax */
else if(isset($_POST['add_pcategory'])){
	if($_POST['uploaded_file'] != ""){
		$new_filename = time();
		$uploaded_filename = $obj_settings->rzvy_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_pcategories->image = $uploaded_filename;
	}else{
		$obj_pcategories->image = "";
	}
	$obj_pcategories->cat_name = htmlspecialchars($_POST['pcat_name'], ENT_QUOTES);
	$obj_pcategories->status = $_POST['status'];
	$added = $obj_pcategories->add_category();
	if($added){
		echo "added";
	}else{
		echo "failed";
	}
}

/* Update pcategory ajax */
else if(isset($_POST['update_pcategory'])){
	$obj_pcategories->id = $_POST['id'];
	$obj_pcategories->cat_name = htmlspecialchars($_POST['pcat_name'], ENT_QUOTES);
	
	$pcategory = $obj_pcategories->readone_category();
	$old_image = $pcategory['image'];
	if($_POST['uploaded_file'] != ""){
		if($old_image != ""){
			if(file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$old_image)){
				unlink(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$old_image);
			}
		}
		$new_filename = time();
		$uploaded_filename = $obj_settings->rzvy_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_pcategories->image = $uploaded_filename;
	}else{
		$obj_pcategories->image = $old_image;
	}
	
	$updated = $obj_pcategories->update_category();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}

/* Update pcategory modal detail ajax */
else if(isset($_POST['update_pcategory_modal_detail'])){
	$obj_pcategories->id = $_POST['id'];
	$pcategory = $obj_pcategories->readone_category(); 
	?>
	<form name="rzvy_update_pcategory_form" id="rzvy_update_pcategory_form" method="post">
	<input type="hidden" value="<?php echo $pcategory['id'] ?>" id="rzvy_update_pcategoryid_hidden" />
	  <div class="form-group">
		<label for="rzvy_update_pcategoryname"><?php if(isset($rzvy_translangArr['name'])){ echo $rzvy_translangArr['name']; }else{ echo $rzvy_defaultlang['name']; } ?></label>
		<input class="form-control" id="rzvy_update_pcategoryname" name="rzvy_update_pcategoryname" type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_name'])){ echo $rzvy_translangArr['enter_name']; }else{ echo $rzvy_defaultlang['enter_name']; } ?>" value="<?php echo $pcategory['cat_name']; ?>" />
	  </div>
	  <div class="form-group col-md-6">
		<label for="rzvy_update_pcategoryimage"><?php if(isset($rzvy_translangArr['image'])){ echo $rzvy_translangArr['image']; }else{ echo $rzvy_defaultlang['image']; } ?></label>
		<div class="rzvy-image-upload">
			<div class="rzvy-image-edit-icon">
				<input type='hidden' id="rzvy-update-image-upload-file-hidden" name="rzvy-update-image-upload-file-hidden" />
				<input type='file' id="rzvy-update-image-upload-file" accept=".png, .jpg, .jpeg" />
				<label for="rzvy-update-image-upload-file"></label>
			</div>
			<div class="rzvy-image-preview">
				<div id="rzvy-update-image-upload-file-preview" style="<?php $pcategory_image = $pcategory['image']; if($pcategory_image != '' && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$pcategory_image)){ echo "background-image: url(".SITE_URL."uploads/images/".$pcategory_image.");"; }else{ echo "background-image: url(".SITE_URL."includes/images/default-service.png);"; } ?>">
				</div>
			</div>
		</div>
	  </div>
	</form>
	<?php
}

/* Update pcategory order ajax */
else if(isset($_POST['update_pcatorder'])){
	$reorder = $_POST['reorder'];
	if(sizeof($reorder)>0){
		foreach($reorder as $order){
			$obj_pcategories->update_category_position($order["id"], $order["order"]);
		}
		echo "success";
	}
}

else if(isset($_POST['assign_subcategory'])){
	$linked_subcat = "";
	if(isset($_POST['scids'])){
		if(sizeof($_POST['scids'])>0){
			$linked_subcat = implode(",",$_POST['scids']);
		}
	}
	$obj_pcategories->assign_subcategory($_POST['pcid'], $linked_subcat);
}