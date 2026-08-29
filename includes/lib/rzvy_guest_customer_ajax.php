<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_customers.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_customers = new rzvy_customers();
$obj_customers->conn = $conn;
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

$deletecustomer ='N';
if(isset($rzvy_rolepermissions['rzvy_customers_delete']) || $rzvy_loginutype=='admin'){
	$deletecustomer ='Y';
}
$updatecustomer ='N';
if(isset($rzvy_rolepermissions['rzvy_customers_guest_update']) || $rzvy_loginutype=='admin'){
	$updatecustomer ='Y';
}

/* Refresh Guest customers ajax */
if(isset($_REQUEST['refresh_gc_detail'])){
	$all_rc_detail = $obj_customers->get_all_gc_detail($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$customers = array();
	$customers["draw"] = $_POST['draw'];
	$count_all_payments = $obj_customers->count_all_gc($_POST['search']['value']);
	if($count_all_payments == "" || $count_all_payments == null){
		$count_all_payments = 0;
	}
	$customers["recordsTotal"] = $count_all_payments;
	$customers["recordsFiltered"] = $count_all_payments;
	$customers['data'] =array();
	if(mysqli_num_rows($all_rc_detail)>0){
		$i=$_POST['start'];
		while($rc = mysqli_fetch_assoc($all_rc_detail)){
			$i++;
			$total_appointments = $obj_customers->count_all_gc_booked_appt($rc['order_id']);
			$addr = "";
			if($rc['c_address'] != ""){
				$addr .= ucwords($rc['c_address']);
			}
			if($rc['c_city'] != ""){
				$addr .= ', '.ucwords($rc['c_city']);
			}
			if($rc['c_state'] != ""){
				$addr .= ', '.ucwords($rc['c_state']);
			}
			if($rc['c_country'] != ""){
				$addr .= ', '.ucwords($rc['c_country']);
			}
			if($rc['c_zip'] != "" && $rc['c_zip'] != "N/A"){
				$addr .= '-'.ucwords($rc['c_zip']);
			}
			
			if(isset($rzvy_translangArr['booked_appointments'])){ 			
				$appointments_trans = $rzvy_translangArr['appointments']; 			
			}else{ 			
				$appointments_trans =  $rzvy_defaultlang['appointments'];			
			}
			if(isset($rzvy_translangArr['booked_appointments'])){ 			
				$editdetails_trans = $rzvy_translangArr['edit_details']; 			
			}else{ 			
				$editdetails_trans =  $rzvy_defaultlang['edit_details'];			
			}
			
			$deletecustomerbtn ='';
			if($deletecustomer=='Y'){
				$deletecustomerbtn ='&nbsp;&nbsp;<a data-id="'.$rc['order_id'].'" data-type="gc" class="btn btn-danger rzvy_delete_customer_btn"><i class="fa fa-trash" aria-hidden="true"></i></a>';
			}
			
			$updatecustomerbtn ='';
			if($updatecustomer=='Y'){
				$updatecustomerbtn ='&nbsp;&nbsp;<a data-id="'.$rc['order_id'].'" data-toggle="modal" data-target="#rzvy_gcustomer_edit_modal" class="btn btn-outline-secondary rzvy_gcustomer_edit_btn"><i class="fa fa-pencil" aria-hidden="true"></i> '.$editdetails_trans.'</a>';
			}
			
			
			$rc_arr = array();
			array_push($rc_arr, ucwords($rc['c_firstname'].' '.$rc['c_lastname']));
			array_push($rc_arr, $rc['c_email']);
			array_push($rc_arr, $rc['c_phone']);
			array_push($rc_arr, $addr);
			array_push($rc_arr, '<a data-ctype="G" data-id="'.$rc['order_id'].'" class="btn btn-outline-secondary rzvy_customer_appointments_btn"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> '.$appointments_trans.' <span class="badge badge-success">'.$total_appointments.'</span></a>'.$updatecustomerbtn.$deletecustomerbtn);
			array_push($customers['data'], $rc_arr);
		}
	}
	echo json_encode($customers);
}
/* Get Edit Customer Detail */
else if(isset($_POST['get_editcustomer_detail'],$_POST['cid']) && $_POST['get_editcustomer_detail']!='' & $_POST['cid']!=''){
    $obj_customers->id = $_POST['cid'];
	$customer = $obj_customers->readone_guest_customer();
	?>
	<link rel="stylesheet" href="<?php echo SITE_URL; ?>includes/front/css/datepicker.min.css?<?php echo time(); ?>" />
	<?php 
	$rzvy_birthdate_with_year = $obj_settings->get_option('rzvy_birthdate_with_year');
	if($rzvy_birthdate_with_year == "Y"){ ?>
		<script src="<?php echo SITE_URL; ?>includes/front/js/datepicker.year.min.js?<?php echo time(); ?>"></script>
		<script>
		$('#rzvy_update_customerdob').datepicker({
			format: "d MM yyyy"
		});
		</script>
	<?php }else{ ?>
		<script src="<?php echo SITE_URL; ?>includes/front/js/datepicker.min.js?<?php echo time(); ?>"></script>
		<script>
		$('#rzvy_update_customerdob').datepicker({
			format: "d MM",
			maxViewMode: 1,
			defaultViewDate: {year: 2020}
		});
		</script>
	<?php } ?>
	<form name="rzvy_update_customer_form" id="rzvy_gupdate_customer_form" method="post">
	    <input type="hidden" value="<?php echo $customer['id'] ?>" id="rzvy_update_customerid_hidden" />
	    <div class="row">
		  <div class="form-group col-md-4">
			<label for="rzvy_update_customerfname"><?php if(isset($rzvy_translangArr['first_name'])){ echo $rzvy_translangArr['first_name']; }else{ echo $rzvy_defaultlang['first_name']; } ?></label>
			<input class="form-control" id="rzvy_update_customerfname" name="rzvy_update_customerfname" type="text" value="<?php echo $customer['c_firstname']; ?>" />
		  </div>
		  <div class="form-group col-md-4">
			<label for="rzvy_update_customerlname"><?php if(isset($rzvy_translangArr['last_name'])){ echo $rzvy_translangArr['last_name']; }else{ echo $rzvy_defaultlang['last_name']; } ?></label>
			<input class="form-control" id="rzvy_update_customerlname" name="rzvy_update_customerlname" type="text" value="<?php echo $customer['c_lastname']; ?>" />
		  </div>
		  <div class="form-group col-md-4">
			<label for="rzvy_update_customeremail"><?php if(isset($rzvy_translangArr['email'])){ echo $rzvy_translangArr['email']; }else{ echo $rzvy_defaultlang['email']; } ?></label>
			<input class="form-control" id="rzvy_update_customeremail" name="rzvy_update_customeremail" type="text" value="<?php echo $customer['c_email']; ?>" />
		  </div>
	    </div> 
		<div class="row">
		  <div class="form-group col-md-4">
			<label for="rzvy_update_customerphone"><?php if(isset($rzvy_translangArr['phone'])){ echo $rzvy_translangArr['phone']; }else{ echo $rzvy_defaultlang['phone']; } ?></label>
			<input class="form-control" id="rzvy_update_customerphone" name="rzvy_update_customerphone" type="text" value="<?php echo $customer['c_phone']; ?>" />
			<label for="rzvy_update_customerphone" generated="true" class="error"></label>
		  </div>
		  <div class="form-group col-md-4">
			<label for="rzvy_update_customeraddress"><?php if(isset($rzvy_translangArr['address'])){ echo $rzvy_translangArr['address']; }else{ echo $rzvy_defaultlang['address']; } ?></label>
			<textarea class="form-control" id="rzvy_update_customeraddress" name="rzvy_update_customeraddress"><?php echo $customer['c_address']; ?></textarea>
		  </div>
		  <div class="form-group col-md-4">
			<label for="rzvy_update_customercity"><?php if(isset($rzvy_translangArr['city'])){ echo $rzvy_translangArr['city']; }else{ echo $rzvy_defaultlang['city']; } ?></label>
			<input class="form-control" id="rzvy_update_customercity" name="rzvy_update_customercity" type="text" value="<?php echo $customer['c_city']; ?>" />
		  </div>
	  </div>
	  <div class="row">
		  <div class="form-group col-md-4">
			<label for="rzvy_update_customerstate"><?php if(isset($rzvy_translangArr['state'])){ echo $rzvy_translangArr['state']; }else{ echo $rzvy_defaultlang['state']; } ?></label>
			<input class="form-control" id="rzvy_update_customerstate" name="rzvy_update_customerstate" type="text" value="<?php echo $customer['c_state']; ?>" />
		  </div>
		  <div class="form-group col-md-4">
			<label for="rzvy_update_customerzip"><?php if(isset($rzvy_translangArr['zip'])){ echo $rzvy_translangArr['zip']; }else{ echo $rzvy_defaultlang['zip']; } ?></label>
			<input class="form-control" id="rzvy_update_customerstate" name="rzvy_update_customerzip" type="text" value="<?php echo $customer['c_zip']; ?>" />
		  </div>
		  <div class="form-group col-md-4">
			<label for="rzvy_update_customercountry"><?php if(isset($rzvy_translangArr['country'])){ echo $rzvy_translangArr['country']; }else{ echo $rzvy_defaultlang['country']; } ?></label>
			<input class="form-control" id="rzvy_update_customercountry" name="rzvy_update_customercountry" type="text" value="<?php echo $customer['c_country']; ?>" />
		  </div>
	  </div>
	  <div class="row">
		  <div class="form-group col-md-4">
			<label for="rzvy_update_customerdob"><?php if(isset($rzvy_translangArr['birthdate'])){ echo $rzvy_translangArr['birthdate']; }else{ echo $rzvy_defaultlang['birthdate']; } ?></label>
			  <div class="input-group">
				<input class="form-control" id="rzvy_update_customerdob" name="rzvy_update_customerdob" type="text" value="<?php if($obj_settings->get_option('rzvy_birthdate_with_year') == "Y"){ echo date("j F Y", strtotime($customer['c_dob'])); }else{ echo date("j F", strtotime($customer['c_dob'])); } ?>" onfocus="this.blur()" />
				<div class="input-group-append">
				  <span class="input-group-text"><i class="fa fa-calendar"></i></span>
				</div>
			  </div>
				<label for="rzvy_update_customerdob" generated="true" class="error"></label>
		  </div>
		  <div class="form-group col-md-8">
			<label for="rzvy_update_customerinternal_notes"><?php if(isset($rzvy_translangArr['notes'])){ echo $rzvy_translangArr['notes']; }else{ echo $rzvy_defaultlang['notes']; } ?></label>
			<textarea class="form-control" id="rzvy_update_customerinternal_notes" name="rzvy_update_customerinternal_notes"><?php echo $customer['c_notes']; ?></textarea>
		  </div>
	  </div>
	</form>
	<?php 
}
/* Update customer ajax */
else if(isset($_POST['update_customer'])){
	$obj_customers->id = $_POST['id'];
	$obj_customers->firstname = $_POST['firstname'];
	$obj_customers->lastname = $_POST['lastname'];
	$obj_customers->email = $_POST['email'];
	$obj_customers->phone = $_POST['phone'];
	$obj_customers->address = htmlspecialchars(htmlentities($_POST['address']), ENT_QUOTES);
	$obj_customers->city = $_POST['city'];
	$obj_customers->state = $_POST['state'];
	$obj_customers->zip = $_POST['zip'];
	$obj_customers->country = $_POST['country'];
	$obj_customers->internal_notes = htmlspecialchars(htmlentities($_POST['internal_notes']), ENT_QUOTES);
	
	if($obj_settings->get_option('rzvy_birthdate_with_year') == "Y"){
		$obj_customers->dob = date("Y-m-d", strtotime($_POST['dob']));
	}else{
		$obj_customers->dob = date("Y-m-d", strtotime($_POST['dob']." 2020"));
	}	
	$updated = $obj_customers->update_guest_customer();
	if($updated){
		echo "updated";
	}else{
		echo "failed";
	}
}