<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_roles.php");

/* Create object of classes */
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;
$obj_roles = new rzvy_roles();
$obj_roles->conn = $conn;

/* Add role ajax */
if(isset($_POST['rzvy_add_role'])){
    	$obj_roles->role = htmlspecialchars(($_POST['role_name']), ENT_QUOTES);
    	$obj_roles->status =$_POST['role_status'];
    	$added = $obj_roles->add_role();
    	if($added){
    		echo "added";
    	}else{
    		echo "failed";
    	}
}

/* Update role ajax */
if(isset($_POST['rzvy_update_role'])){
    	$obj_roles->role = htmlspecialchars(($_POST['role_name']), ENT_QUOTES);
    	$obj_roles->id =$_POST['id'];
    	$updated = $obj_roles->update_role();
    	if($updated){
    		echo "updated";
    	}else{
    		echo "failed";
    	}
}

/* Update role Status ajax */
if(isset($_POST['rzvy_update_role_status'])){
    	$obj_roles->status = $_POST['role_status'];
    	$obj_roles->id =$_POST['id'];
    	$updated = $obj_roles->change_role_status();
    	if($updated){
    		echo "changed";
    	}else{
    		echo "failed";
    	}
}

/* Delete role ajax */
if(isset($_POST['delete_role'])){
    	$obj_roles->id =$_POST['id'];
        $roleinfo = $obj_roles->readone_role();
        if(isset($roleinfo['staff']) && $roleinfo['staff']==''){
        	$deleted = $obj_roles->delete_role();
        	if($deleted){
        		echo "deleted";
        	}else{
        		echo "failed";
        	}
        }else{
            echo "failed";
        }
}

/* Assign role to staff ajax */
if(isset($_POST['assign_role_to_staff'])){
        if(isset($_POST['staffids']) && $_POST['staffids']!='' && is_array($_POST['staffids'])){
            $obj_roles->staff = implode(',',$_POST['staffids']);
        }else{
            $obj_roles->staff = $_POST['staffids'];
        }
    	
    	$obj_roles->id =$_POST['id'];
    	$updated = $obj_roles->assign_staff_to_role();
    	if($updated){
    		echo "changed";
    	}else{
    		echo "failed";
    	}
}

/* Update role modal detail ajax */
if(isset($_POST['update_role_modal_detail'])){
    
    $obj_roles->id =$_POST['id'];
    $roleinfo = $obj_roles->readone_role();
    
    
	?>
	<form name="rzvy_update_role_form" id="rzvy_update_role_form" method="post">
		<input type="hidden" value="<?php echo $roleinfo['id']; ?>" id="rzvy_update_roleid_hidden" />
		<div class="form-group">
			<label for="rzvy_role"><?php if(isset($rzvy_translangArr['role'])){ echo $rzvy_translangArr['role']; }else{ echo $rzvy_defaultlang['role']; } ?></label>
			<input class="form-control" id="rzvy_urole" name="rzvy_urole" type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_role'])){ echo $rzvy_translangArr['enter_role']; }else{ echo $rzvy_defaultlang['enter_role']; } ?>" value="<?php if(isset($roleinfo['role'])){ echo $roleinfo['role']; }  ?>" />
			<label for="rzvy_urole" generated="true" class="error rzvy-hide rzvy_urole_err"><?php if(isset($rzvy_translangArr['enter_role_name'])){ echo $rzvy_translangArr['enter_role_name']; }else{ echo $rzvy_defaultlang['enter_role_name']; } ?></label>
		</div>
	</form>
	<?php
}