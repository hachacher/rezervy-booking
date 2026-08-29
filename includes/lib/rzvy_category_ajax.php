<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_categories.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_parent_categories.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_services.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */

$obj_categories = new rzvy_categories();
$obj_categories->conn = $conn;

$obj_pcategories = new rzvy_parent_categories();
$obj_pcategories->conn = $conn;

$obj_services = new rzvy_services();
$obj_services->conn = $conn;

$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

$image_upload_path = SITE_URL."/uploads/images/";
$image_upload_abs_path = dirname(dirname(dirname(__FILE__)))."/uploads/images/";

/* Change category status ajax */
if(isset($_POST['change_category_status'])){
	$obj_categories->id = $_POST['id'];
	$obj_categories->status = $_POST['category_status'];
	$status_changed = $obj_categories->change_category_status();
	if($status_changed){
		echo "changed";
	}else{
		echo "failed";
	}
}
/* Delete category ajax */
else if(isset($_POST['delete_category'])){
	$obj_categories->id = $_POST['id'];
	$check_appointments = $obj_categories->check_appointments_before_delete_category();
	if($check_appointments==0){
		$category = $obj_categories->readone_category();
		$old_image = $category['image'];
		if($old_image != ""){
			if(file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$old_image)){
				unlink(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$old_image);
			}
		}
		$deleted = $obj_categories->delete_category();
		if($deleted){
			echo "deleted";
		}else{
			echo "failed";
		}
	}else{
		echo "appointments exist";
	}
}
/* Add category ajax */
else if(isset($_POST['add_category'])){
	if($_POST['uploaded_file'] != ""){
		$new_filename = time();
		$uploaded_filename = $obj_settings->rzvy_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_categories->image = $uploaded_filename;
	}else{
		$obj_categories->image = "";
	}
	$obj_categories->cat_name = htmlspecialchars($_POST['cat_name'], ENT_QUOTES);
	$obj_categories->status = $_POST['status'];
	$category_id = $obj_categories->add_category();
	if(is_numeric($category_id)){
		if($_POST["pcid"]>0){
			$obj_pcategories->link_subcategory($_POST["pcid"], $category_id);
		}
		echo "added";
	}else{
		echo "failed";
	}
}
/* Update category ajax */
else if(isset($_POST['update_category'])){
	$obj_categories->id = $_POST['id'];
	$obj_categories->cat_name = htmlspecialchars($_POST['cat_name'], ENT_QUOTES);
	
	$category = $obj_categories->readone_category();
	$old_image = $category['image'];
	if($_POST['uploaded_file'] != ""){
		if($old_image != ""){
			if(file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$old_image)){
				unlink(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$old_image);
			}
		}
		$new_filename = time();
		$uploaded_filename = $obj_settings->rzvy_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_categories->image = $uploaded_filename;
	}else{
		$obj_categories->image = $old_image;
	}
	
	$updated = $obj_categories->update_category();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}
/* Update category modal detail ajax */
else if(isset($_POST['update_category_modal_detail'])){
	$obj_categories->id = $_POST['id'];
	$category = $obj_categories->readone_category(); 
	?>
	<form name="rzvy_update_category_form" id="rzvy_update_category_form" method="post">
	<input type="hidden" value="<?php echo $category['id'] ?>" id="rzvy_update_categoryid_hidden" />
	  <div class="form-group">
		<label for="rzvy_update_categoryname"><?php if(isset($rzvy_translangArr['category_name'])){ echo $rzvy_translangArr['category_name']; }else{ echo $rzvy_defaultlang['category_name']; } ?></label>
		<input class="form-control" id="rzvy_update_categoryname" name="rzvy_update_categoryname" type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_category_name'])){ echo $rzvy_translangArr['enter_category_name']; }else{ echo $rzvy_defaultlang['enter_category_name']; } ?>" value="<?php echo $category['cat_name']; ?>" />
	  </div>
	  <div class="form-group col-md-6">
		<label for="rzvy_update_categoryimage"><?php if(isset($rzvy_translangArr['category_image'])){ echo $rzvy_translangArr['category_image']; }else{ echo $rzvy_defaultlang['category_image']; } ?></label>
		<div class="rzvy-image-upload">
			<div class="rzvy-image-edit-icon">
				<input type='hidden' id="rzvy-update-image-upload-file-hidden" name="rzvy-update-image-upload-file-hidden" />
				<input type='file' id="rzvy-update-image-upload-file" accept=".png, .jpg, .jpeg" />
				<label for="rzvy-update-image-upload-file"></label>
			</div>
			<div class="rzvy-image-preview">
				<div id="rzvy-update-image-upload-file-preview" style="<?php $category_image = $category['image']; if($category_image != '' && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$category_image)){ echo "background-image: url(".SITE_URL."uploads/images/".$category_image.");"; }else{ echo "background-image: url(".SITE_URL."includes/images/default-service.png);"; } ?>">
				</div>
			</div>
		</div>
	  </div>
	</form>
	<?php
}
/* Update category order ajax */
else if(isset($_POST['update_catorder'])){
	$reorder = $_POST['reorder'];
	if(sizeof($reorder)>0){
		foreach($reorder as $order){
			$obj_categories->update_category_position($order["id"], $order["order"]);
		}
		echo "success";
	}
}