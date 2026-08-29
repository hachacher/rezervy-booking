<?php 
include(dirname(__FILE__)."/constants.php"); 
$obj_database->check_admin_setup_detail($conn);

/* Include class files */
include(dirname(__FILE__)."/classes/class_settings.php");
/* Create object of classes */
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

/* Include class files */
include(dirname(__FILE__)."/classes/class_frontend.php");
/* Create object of classes */
$obj_frontend = new rzvy_frontend();
$obj_frontend->conn = $conn; 

$saiframe = '';
if(isset($_GET['if'])){
	$saiframe = '?if=y';  
}

$show_location_selector = "N";
if(!isset($_SESSION["rzvy_location_selector_zipcode"])){
	$show_location_selector = "Y";
}else if($_SESSION["rzvy_location_selector_zipcode"] == ""){
	$show_location_selector = "Y";
}

$rzvy_frontend = $obj_settings->get_option("rzvy_frontend");

$_SESSION['rzvy_customer_detail'] = array();
$_SESSION['rzvy_cart_items'] = array();
$_SESSION['add_to_cart_package'] = array();
$_SESSION['rzvy_cart_parent_category_id'] = "";
$_SESSION['rzvy_cart_category_id'] = "";
$_SESSION['rzvy_cart_service_id'] = "";
$_SESSION['rzvy_cart_service_price'] = 0;
$_SESSION['rzvy_cart_datetime'] = "";
$_SESSION['rzvy_cart_end_datetime'] = "";
$_SESSION['rzvy_cart_freqdiscount_label'] = "";
$_SESSION['rzvy_cart_freqdiscount_key'] = "";
$_SESSION['rzvy_cart_freqdiscount_id'] = "";
$_SESSION['rzvy_cart_subtotal'] = 0;
$_SESSION['rzvy_cart_freqdiscount'] = 0;
$_SESSION['rzvy_cart_coupondiscount'] = 0;
$_SESSION['rzvy_cart_couponid'] = "";
$_SESSION['rzvy_cart_tax'] = 0;
$_SESSION['rzvy_cart_nettotal'] = 0;
$_SESSION['rzvy_referral_discount_amount'] = 0;
$_SESSION['rzvy_applied_ref_customer_id'] = "";
$_SESSION['rzvy_ref_customer_id'] = "";
$_SESSION['rzvy_staff_id'] = "";
$_SESSION['rzvy_cart_partial_deposite'] = 0;
$_SESSION['rzvy_cart_total_addon_duration'] = 0;
$_SESSION['rzvy_lpoint_used'] = 0;
$_SESSION['rzvy_cart_lpoint'] = 0;
$_SESSION['rzvy_lpoint_total'] = 0;
$_SESSION['rzvy_lpoint_left'] = 0;
$_SESSION['rzvy_lpoint_price'] = 0;
$_SESSION['rzvy_lpoint_value'] = 0;
$_SESSION['rzvy_referred_discount_amount'] = 0;
$_SESSION["referralcode_applied"] = "N";
$_SESSION["rzvy_applied_refcode"] = "";
$_SESSION['rzvy_lpoint_checked'] = false;
if(isset($_SESSION['rzvy_package_service'])){ unset($_SESSION['rzvy_package_service']); }
if(isset($_SESSION['rzvy_package_addons'])){ unset($_SESSION['rzvy_package_addons']); }
if(isset($_SESSION['rzvy_package_discount'])){ unset($_SESSION['rzvy_package_discount']); }
if(isset($_SESSION['rzvy_package_credituse'])){ unset($_SESSION['rzvy_package_credituse']); }
if(isset($_SESSION['rzvy_package_credituse_info'])){ unset($_SESSION['rzvy_package_credituse_info']); }
if(isset($_SESSION['rzvy_activepackage_credituse_info'])){ unset($_SESSION['rzvy_activepackage_credituse_info']); }

/* check location selector status */
$rzvy_location_selector_status = $obj_settings->get_option("rzvy_location_selector_status"); 
if($rzvy_location_selector_status == "N" || $rzvy_location_selector_status == ""){ 
	$show_location_selector = "N";
	$_SESSION['rzvy_location_selector_zipcode'] = "N/A";
} 
if(isset($_SESSION["rzvy_location_selector_zipcode"])){
	if($rzvy_location_selector_status == "Y" && ($_SESSION["rzvy_location_selector_zipcode"]=="" && $_SESSION["rzvy_location_selector_zipcode"]!="N/A")){
		$show_location_selector = "Y";
		$_SESSION['rzvy_location_selector_zipcode'] = "";
	}
}

/** zipcode checker **/
if(isset($_SESSION["rzvy_location_selector_zipcode"])){
	if($_SESSION['rzvy_location_selector_zipcode'] != "N/A"){
		$selector_zipcode = $_SESSION["rzvy_location_selector_zipcode"];
		$rzvy_location_selector = $obj_settings->get_option('rzvy_location_selector');
		$exploded_rzvy_location_selector = explode(",", $rzvy_location_selector);
		
		$j=0;
		for($i=0;$i<sizeof($exploded_rzvy_location_selector);$i++){
			if(strtolower($exploded_rzvy_location_selector[$i]) == strtolower($selector_zipcode)){
				$j++;
			}
		}
		if($j==0){
			$show_location_selector = "Y";
		}
	}
}

/** get form fields options **/
$rzvy_en_ff_firstname_status = $obj_settings->get_option('rzvy_en_ff_firstname_status');
$rzvy_en_ff_lastname_status = $obj_settings->get_option('rzvy_en_ff_lastname_status');
$rzvy_en_ff_phone_status = $obj_settings->get_option('rzvy_en_ff_phone_status');
$rzvy_en_ff_address_status = $obj_settings->get_option('rzvy_en_ff_address_status');
$rzvy_en_ff_city_status = $obj_settings->get_option('rzvy_en_ff_city_status');
$rzvy_en_ff_state_status = $obj_settings->get_option('rzvy_en_ff_state_status');
$rzvy_en_ff_country_status = $obj_settings->get_option('rzvy_en_ff_country_status');
$rzvy_en_ff_dob_status = $obj_settings->get_option('rzvy_en_ff_dob_status');
$rzvy_en_ff_notes_status = $obj_settings->get_option('rzvy_en_ff_notes_status');

$rzvy_g_ff_firstname_status = $obj_settings->get_option('rzvy_g_ff_firstname_status');
$rzvy_g_ff_lastname_status = $obj_settings->get_option('rzvy_g_ff_lastname_status');
$rzvy_g_ff_email_status = $obj_settings->get_option('rzvy_g_ff_email_status');
$rzvy_g_ff_phone_status = $obj_settings->get_option('rzvy_g_ff_phone_status');
$rzvy_g_ff_address_status = $obj_settings->get_option('rzvy_g_ff_address_status');
$rzvy_g_ff_city_status = $obj_settings->get_option('rzvy_g_ff_city_status');
$rzvy_g_ff_state_status = $obj_settings->get_option('rzvy_g_ff_state_status');
$rzvy_g_ff_country_status = $obj_settings->get_option('rzvy_g_ff_country_status');
$rzvy_g_ff_dob_status = $obj_settings->get_option('rzvy_g_ff_dob_status');
$rzvy_g_ff_notes_status = $obj_settings->get_option('rzvy_g_ff_notes_status');

/** get form fields required options **/
$rzvy_en_ff_firstname_optional = $obj_settings->get_option('rzvy_en_ff_firstname_optional');
$rzvy_en_ff_lastname_optional = $obj_settings->get_option('rzvy_en_ff_lastname_optional');
$rzvy_en_ff_phone_optional = $obj_settings->get_option('rzvy_en_ff_phone_optional');
$rzvy_en_ff_address_optional = $obj_settings->get_option('rzvy_en_ff_address_optional');
$rzvy_en_ff_city_optional = $obj_settings->get_option('rzvy_en_ff_city_optional');
$rzvy_en_ff_state_optional = $obj_settings->get_option('rzvy_en_ff_state_optional');
$rzvy_en_ff_country_optional = $obj_settings->get_option('rzvy_en_ff_country_optional');
$rzvy_en_ff_dob_optional = $obj_settings->get_option('rzvy_en_ff_dob_optional');
$rzvy_en_ff_notes_optional = $obj_settings->get_option('rzvy_en_ff_notes_optional');

$rzvy_g_ff_firstname_optional = $obj_settings->get_option('rzvy_g_ff_firstname_optional');
$rzvy_g_ff_lastname_optional = $obj_settings->get_option('rzvy_g_ff_lastname_optional');
$rzvy_g_ff_email_optional = $obj_settings->get_option('rzvy_g_ff_email_optional');
$rzvy_g_ff_phone_optional = $obj_settings->get_option('rzvy_g_ff_phone_optional');
$rzvy_g_ff_address_optional = $obj_settings->get_option('rzvy_g_ff_address_optional');
$rzvy_g_ff_city_optional = $obj_settings->get_option('rzvy_g_ff_city_optional');
$rzvy_g_ff_state_optional = $obj_settings->get_option('rzvy_g_ff_state_optional');
$rzvy_g_ff_country_optional = $obj_settings->get_option('rzvy_g_ff_country_optional'); 
$rzvy_g_ff_dob_optional = $obj_settings->get_option('rzvy_g_ff_dob_optional'); 
$rzvy_g_ff_notes_optional = $obj_settings->get_option('rzvy_g_ff_notes_optional'); 

/* Check Zip Codes */
$rzvy_location_selector = $obj_settings->get_option('rzvy_location_selector');
$exploded_rzvy_location_selector = array();
if(isset($rzvy_location_selector) && trim($rzvy_location_selector)!=''){
	$exploded_rzvy_location_selector = explode(",", $rzvy_location_selector);
}

$rzvy_booking_first_selection_as = $obj_settings->get_option("rzvy_booking_first_selection_as");
$rzvy_price_display = $obj_settings->get_option("rzvy_price_display");
$rzvy_success_modal_booking = $obj_settings->get_option("rzvy_success_modal_booking");
$rzvy_customer_calendars = $obj_settings->get_option("rzvy_customer_calendars");

$rzvy_currency_symbol = $obj_settings->get_option('rzvy_currency_symbol');
$rzvy_currency_position = $obj_settings->get_option('rzvy_currency_position');
$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');

$currencyB = $rzvy_currency_symbol.' ';
$currencyA = '';
if($rzvy_currency_position=='A'){
	$currencyB = '';
	$currencyA= ' '.$rzvy_currency_symbol;
}


$urlrefcode = "";
if(isset($_GET['ref']) && $_GET['ref'] != ""){
	if(strlen($_GET['ref']) == 8){
		$check_referral_code = $obj_frontend->check_referral_code($_GET['ref']);
		if(mysqli_num_rows($check_referral_code)>0){
			$data = mysqli_fetch_array($check_referral_code);
			if(isset($_SESSION["customer_id"])){
				if($data["id"] == $_SESSION["customer_id"]){
					$_SESSION['rzvy_ref_customer_id'] = "";
					$_SESSION["referralcode_applied"] = "O";
					$_SESSION["rzvy_applied_ref_customer_id"] = "";
					$_SESSION["rzvy_applied_refcode"] = "";
				}else{
					/** check for first booking **/
					$check_referral_firstbooking = $obj_frontend->check_referral_firstbooking($_SESSION["customer_id"]);
					if(mysqli_num_rows($check_referral_firstbooking)==0){
						$_SESSION['rzvy_ref_customer_id'] = $data["id"];
						$_SESSION["referralcode_applied"] = "Y";
						$urlrefcode = $_GET['ref'];
						$_SESSION["rzvy_applied_ref_customer_id"] = $urlrefcode;
						$_SESSION["rzvy_applied_refcode"] = $urlrefcode;
					}else{
						$_SESSION['rzvy_ref_customer_id'] = "";
						$_SESSION["referralcode_applied"] = "F";
						$_SESSION["rzvy_applied_ref_customer_id"] = "";
						$_SESSION["rzvy_applied_refcode"] = "";
					}
				}
			}else{
				$_SESSION['rzvy_ref_customer_id'] = $data["id"];
				$_SESSION["referralcode_applied"] = "Y";
				$urlrefcode = $_GET['ref'];
				$_SESSION["rzvy_applied_ref_customer_id"] = $urlrefcode;
				$_SESSION["rzvy_applied_refcode"] = $urlrefcode;
			}
		}else{
			$_SESSION['rzvy_ref_customer_id'] = "";
			$_SESSION["referralcode_applied"] = "N";
			$_SESSION["rzvy_applied_ref_customer_id"] = "";
			$_SESSION["rzvy_applied_refcode"] = "";
		}
	}else{
		$_SESSION['rzvy_ref_customer_id'] = "";
		$_SESSION["referralcode_applied"] = "N";
		$_SESSION["rzvy_applied_ref_customer_id"] = "";
		$_SESSION["rzvy_applied_refcode"] = "";
	}
}
$rzvy_book_with_datetime = $obj_settings->get_option("rzvy_book_with_datetime"); 
if($rzvy_book_with_datetime != "Y"){
	$_SESSION['rzvy_cart_datetime'] = date("Y-m-d 09:00:00");
	$_SESSION['rzvy_cart_end_datetime'] = date("Y-m-d 10:00:00");
}

$rzvy_stepview_alignment = $obj_settings->get_option("rzvy_stepview_alignment"); 
if($rzvy_stepview_alignment == "center"){
	$alignmentClass = "justify-content-center";
	$labelAlignmentClass = "class='d-flex flex-wrap justify-content-center'";
	$labelAlignmentClassName = "d-flex flex-wrap justify-content-center";
	$inputAlignment = "text-center";
}else if($rzvy_stepview_alignment == "right"){
	$alignmentClass = "justify-content-end";
	$labelAlignmentClass = "class='d-flex flex-wrap justify-content-end'";
	$labelAlignmentClassName = "d-flex flex-wrap justify-content-end";
	$inputAlignment = "text-right";
}else{
	$alignmentClass = "";
	$labelAlignmentClass = "";
	$labelAlignmentClassName = "";
	$inputAlignment = "";
}

if($rzvy_frontend == "stepview"){
	include(dirname(__FILE__)."/stepview.php");
}else{
	include(dirname(__FILE__)."/onepage.php");
}