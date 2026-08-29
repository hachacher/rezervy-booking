<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_customers.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_loyalty_points.php");

/* Create object of classes */
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;
$obj_customers = new rzvy_customers();
$obj_customers->conn = $conn;
$obj_loyalty_points = new rzvy_loyalty_points();
$obj_loyalty_points->conn = $conn;

$image_upload_path = SITE_URL."/uploads/images/";
$image_upload_abs_path = dirname(dirname(dirname(__FILE__)))."/uploads/images/";

$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');

/** Change Password Ajax **/
if(isset($_POST['change_customer_password'])){
	$obj_customers->id = $_POST['customer_id'];
	$obj_customers->password = md5($_POST['old_password']);
	$check_old_password = $obj_customers->check_old_password();
	if($check_old_password){
		$obj_customers->id = $_POST['customer_id'];
		$obj_customers->password = md5($_POST['new_password']);
		$change_password = $obj_customers->change_password();
		if($change_password){
			echo "changed";
		}
	}else{
		echo "wrong";
	}
}

/** Update Profile Ajax **/
else if(isset($_POST['update_profile'])){
	$obj_customers->id = $_POST['id'];
	$obj_customers->firstname = htmlentities($_POST['firstname']);
	$obj_customers->lastname = htmlentities($_POST['lastname']);
	$obj_customers->phone = $_POST['phone'];
	$obj_customers->address = htmlentities($_POST['address']);
	$obj_customers->city = htmlentities($_POST['city']);
	$obj_customers->state = htmlentities($_POST['state']);
	$obj_customers->country = htmlentities($_POST['country']);
	$obj_customers->zip = htmlentities($_POST['zip']);
	
	if($obj_settings->get_option('rzvy_birthdate_with_year') == "Y"){
		$obj_customers->dob = date("Y-m-d", strtotime($_POST['dob']));
	}else{
		$obj_customers->dob = date("Y-m-d", strtotime($_POST['dob']." 2020"));
	}
	
	
	if($_POST['uploaded_file'] != ""){
		$old_image = $obj_customers->get_image_name_of_customer();
		if($old_image != ""){
			if(file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$old_image)){
				unlink(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$old_image);
			}
		}
		$new_filename = "cust_".time();
		$uploaded_filename = $obj_settings->rzvy_base64_to_jpeg($_POST['uploaded_file'], $image_upload_abs_path, $new_filename);
		$obj_customers->image = $uploaded_filename;
		$updated = $obj_customers->update_profile_with_image();
		if($updated){
			echo "updated";
		}
	}else{
		$updated = $obj_customers->update_profile_without_image();
		if($updated){
			echo "updated";
		}
	}
}

/** Change Email Ajax **/
else if(isset($_POST['change_email'])){
	$email = trim(strip_tags(mysqli_real_escape_string($conn, $_POST['email'])));
	$obj_customers->id = $_SESSION["customer_id"];
	$obj_customers->email = $email;
	$customer_email = $obj_customers->get_customer_email();

	if($email == $customer_email){
		echo "updated";
	}else{
		$is_available = $obj_customers->check_email_availability($customer_email);
		if($is_available){
			$updated = $obj_customers->update_email();
			if($updated){
				echo "updated";
			}
		}else{
			echo "exist";
		}
	}
}
/* Refresh loyalty points recieved log ajax */
else if(isset($_REQUEST['refresh_recieved_loyalty_points'])){
    
    $obj_loyalty_points->customer_id = $_SESSION["customer_id"];
	$all_recieved_lp = $obj_loyalty_points->get_added_points_customer($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$datarecieved_lp = array();
	$datarecieved_lp["draw"] = $_POST['draw'];
	$count_all_recieved_lp = $obj_loyalty_points->count_all_recieved_loyalty_points($_POST['search']['value']);
	$datarecieved_lp["recordsTotal"] = $count_all_recieved_lp;
	$datarecieved_lp["recordsFiltered"] = $count_all_recieved_lp;
	$datarecieved_lp['data'] =array();
	
	if(mysqli_num_rows($all_recieved_lp)>0){
		while($recieved_lp = mysqli_fetch_assoc($all_recieved_lp)){
			if($recieved_lp['order_id'] == 0){
				$orderid_display = "-";
				if(isset($rzvy_translangArr['birthday'])){ $label_received_for = $rzvy_translangArr['birthday']; }else{ $label_received_for = $rzvy_defaultlang['birthday']; }
			}else{
				$orderid_display = $recieved_lp['order_id'];
				if(isset($rzvy_translangArr['booking'])){ $label_received_for = $rzvy_translangArr['booking']; }else{ $label_received_for = $rzvy_defaultlang['booking']; }
			}

			$recieved_lp_arr = array();
			array_push($recieved_lp_arr, $orderid_display);
			array_push($recieved_lp_arr, $recieved_lp['points']);
			array_push($recieved_lp_arr, $label_received_for);
			array_push($recieved_lp_arr, $recieved_lp['available_points']);
			array_push($recieved_lp_arr, date($rzvy_date_format.' '.$rzvy_time_format, strtotime($recieved_lp['lastmodify'])));
			array_push($datarecieved_lp['data'], $recieved_lp_arr);
		}
	}
	echo json_encode($datarecieved_lp);
}
/* Refresh loyalty points used log ajax */
else if(isset($_REQUEST['refresh_used_loyalty_points'])){
    
    $obj_loyalty_points->customer_id = $_SESSION["customer_id"];
	$all_used_lp = $obj_loyalty_points->get_used_points_customer($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$dataused_lp = array();
	$dataused_lp["draw"] = $_POST['draw'];
	$count_all_recieved_lp = $obj_loyalty_points->count_all_used_loyalty_points($_POST['search']['value']);
	$dataused_lp["recordsTotal"] = $count_all_recieved_lp;
	$dataused_lp["recordsFiltered"] = $count_all_recieved_lp;
	$dataused_lp['data'] =array();
	
	if(isset($rzvy_translangArr['booking'])){ $label_received_for = $rzvy_translangArr['booking']; }else{ $label_received_for = $rzvy_defaultlang['booking']; }
	
	if(mysqli_num_rows($all_used_lp)>0){
		while($used_lp = mysqli_fetch_assoc($all_used_lp)){
			$used_lp_arr = array();
			array_push($used_lp_arr, $used_lp['order_id']);
			array_push($used_lp_arr, $used_lp['points']);
			array_push($used_lp_arr, $label_received_for);
			array_push($used_lp_arr, $used_lp['available_points']);
			array_push($used_lp_arr, date($rzvy_date_format.' '.$rzvy_time_format, strtotime($used_lp['lastmodify'])));
			array_push($dataused_lp['data'], $used_lp_arr);
		}
	}
	echo json_encode($dataused_lp);
}

/** Update internal_note Ajax **/
else if(isset($_POST['update_internal_note'])){
	$obj_customers->id = $_POST['id'];
	$obj_customers->internal_notes = htmlentities($_POST['internal_note']);
	$updated = $obj_customers->update_internal_note();
	if($updated){
		echo "updated";
	}
}
/** Delete Registered Customer Fully **/
else if(isset($_POST['delete_customer'],$_POST['id'],$_POST['type']) && $_POST['type']=='rc'){
	if($_POST['id']=='' || !is_numeric($_POST['id'])){
		echo "error";
	}else{
		$rcid = $_POST['id'];
		$query = "select `order_id` from `rzvy_bookings` where `customer_id` = '".$rcid."'";
		$orderidsinfo = mysqli_query($conn, $query);
		$customerorders = array();
		if(mysqli_num_rows($orderidsinfo)>0){
			while($orderids = mysqli_fetch_assoc($orderidsinfo)){
				array_push($customerorders, $orderids['order_id']);
			}
		}
		if(sizeof($customerorders)>0){
			mysqli_query($conn, "delete from `rzvy_refund_request` where `order_id` in (".implode(",",$customerorders).")");
			mysqli_query($conn, "delete from `rzvy_pos_payments` where `parent_orderid` in (".implode(",",$customerorders).")");
			mysqli_query($conn, "delete from `rzvy_pos_bookings` where `parent_orderid` in (".implode(",",$customerorders).")");
			mysqli_query($conn, "delete from `rzvy_payments` where `order_id` in (".implode(",",$customerorders).")");
			mysqli_query($conn, "delete from `rzvy_customer_orderinfo` where `order_id` in (".implode(",",$customerorders).")");
			mysqli_query($conn, "delete from `rzvy_appointment_feedback` where `order_id` in (".implode(",",$customerorders).")");
			mysqli_query($conn, "delete from `rzvy_gc_bookingdetail` where `order_id` in (".implode(",",$customerorders).")");
			mysqli_query($conn, "delete from `rzvy_staff_gc_bookingdetail` where `order_id` in (".implode(",",$customerorders).")");
			mysqli_query($conn, "delete from `rzvy_bookings` where `order_id` in (".implode(",",$customerorders).")");
		}
		mysqli_query($conn, "delete from `rzvy_customers` where `id` = '".$rcid."'");
		mysqli_query($conn, "delete from `rzvy_customer_referrals` where `customer_id` = '".$rcid."'");
		mysqli_query($conn, "delete from `rzvy_sp_transactions` where `customer_id` = '".$rcid."'");
		mysqli_query($conn, "delete from `rzvy_loyalty_points` where `customer_id` = '".$rcid."'");
		mysqli_query($conn, "delete from `rzvy_used_coupons_by_customer` where `customer_id` = '".$rcid."'");
		
		
		$ticketsidsinfo = mysqli_query($conn, "select `id` from `rzvy_support_tickets` where `generated_by_id` = '".$rcid."' and `generated_by`='customer'");
		$customertickets = array();
		if(mysqli_num_rows($ticketsidsinfo)>0){
			while($ticketids = mysqli_fetch_assoc($ticketsidsinfo)){
				array_push($customertickets, $ticketids['id']);
			}
		}
		if(sizeof($customertickets)>0){
			mysqli_query($conn, "delete from `rzvy_support_ticket_discussions` where `ticket_id` in (".implode(",",$customertickets).")");
		}
		mysqli_query($conn, "delete from `rzvy_support_tickets` where `generated_by_id` = '".$rcid."' and `st`.`generated_by`='customer'");

		echo "deleted_done";
	}
}

/** Delete Guest Customer Fully **/
else if(isset($_POST['delete_customer'],$_POST['id'],$_POST['type']) && $_POST['type']=='gc'){
	if($_POST['id']=='' || !is_numeric($_POST['id'])){
		echo "error";
	}else{
		$gcid = $_POST['id'];
		mysqli_query($conn, "delete from `rzvy_refund_request` where `order_id` = '".$gcid."'");
		mysqli_query($conn, "delete from `rzvy_pos_payments` where `parent_orderid` = '".$gcid."'");
		mysqli_query($conn, "delete from `rzvy_pos_bookings` where `parent_orderid` = '".$gcid."'");
		mysqli_query($conn, "delete from `rzvy_payments` where `order_id` = '".$gcid."'");
		mysqli_query($conn, "delete from `rzvy_customer_orderinfo` where `order_id` = '".$gcid."'");
		mysqli_query($conn, "delete from `rzvy_appointment_feedback` where `order_id` = '".$gcid."'");
		mysqli_query($conn, "delete from `rzvy_gc_bookingdetail` where `order_id` = '".$gcid."'");
		mysqli_query($conn, "delete from `rzvy_staff_gc_bookingdetail` where `order_id` = '".$gcid."'");
		mysqli_query($conn, "delete from `rzvy_bookings` where `order_id` = '".$gcid."'");
		mysqli_query($conn, "delete from `rzvy_customer_referrals` where `order_id` = '".$gcid."'");
		mysqli_query($conn, "delete from `rzvy_loyalty_points` where `order_id` = '".$gcid."'");
		echo "deleted_done";
	}
}