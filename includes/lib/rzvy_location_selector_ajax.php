<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

$rzvy_book_with_datetime = $obj_settings->get_option("rzvy_book_with_datetime");

/** check zip location Ajax **/
if(isset($_POST['check_zip_location'])){
	if(isset($_POST['final_check'])){
		if(isset($_SESSION['rzvy_cart_category_id']) && $_SESSION['rzvy_cart_category_id']>0){
			if(isset($_SESSION['rzvy_cart_service_id']) && $_SESSION['rzvy_cart_service_id']>0){
				/* if(isset($_SESSION['rzvy_staff_id'])){ */
					if((isset($_SESSION['rzvy_cart_datetime']) && $_SESSION['rzvy_cart_datetime']!="") && (isset($_SESSION['rzvy_cart_end_datetime']) && $_SESSION['rzvy_cart_end_datetime']!="")){
						/* $check_datetime_already_not_booked = $obj_settings->check_datetime_already_not_booked($_SESSION['rzvy_cart_datetime'], $_SESSION['rzvy_cart_end_datetime'], $_SESSION['rzvy_staff_id']);
						if($check_datetime_already_not_booked){ */
							/* check location selector status */
							$rzvy_location_selector_status = $obj_settings->get_option("rzvy_location_selector_status"); 
							if($rzvy_location_selector_status == "N" || $rzvy_location_selector_status == ""){ 
								$_SESSION['rzvy_location_selector_zipcode'] = "N/A";
								echo "available";
							}else{
								$zipcode = str_replace(' ', '', $_POST["zipcode"]);
								$rzvy_location_selector = strtolower($obj_settings->get_option('rzvy_location_selector'));
								$exploded_rzvy_location_selector = explode(",", $rzvy_location_selector);
								
								$j=0;
								for($i=0;$i<sizeof($exploded_rzvy_location_selector);$i++){
									if(strtolower($exploded_rzvy_location_selector[$i]) == strtolower($zipcode)){
										$j++;
									}
								}
								if($j>0){
									$_SESSION['rzvy_location_selector_zipcode'] = $zipcode;
									echo "available";
								}
							}
						/* }else{
							echo "alreadybooked";
						} */
					}else if($rzvy_book_with_datetime != "Y"){
						$rzvy_location_selector_status = $obj_settings->get_option("rzvy_location_selector_status"); 
						if($rzvy_location_selector_status == "N" || $rzvy_location_selector_status == ""){ 
							$_SESSION['rzvy_location_selector_zipcode'] = "N/A";
							echo "available";
						}else{
							$zipcode = str_replace(' ', '', $_POST["zipcode"]);
							$rzvy_location_selector = strtolower($obj_settings->get_option('rzvy_location_selector'));
							$exploded_rzvy_location_selector = explode(",", $rzvy_location_selector);
							
							$j=0;
							for($i=0;$i<sizeof($exploded_rzvy_location_selector);$i++){
								if(strtolower($exploded_rzvy_location_selector[$i]) == strtolower($zipcode)){
									$j++;
								}
							}
							if($j>0){
								$_SESSION['rzvy_location_selector_zipcode'] = $zipcode;
								echo "available";
							}
						}
					}else{
						echo "choosedatetime";
					}
				/* }else{
					echo "choosedatetime";
				} */
			}else{
				echo "addintocart";
			}
		}else{
			echo "addintocart";
		}
	}else{
		/* check location selector status */
		$rzvy_location_selector_status = $obj_settings->get_option("rzvy_location_selector_status"); 
		if($rzvy_location_selector_status == "N" || $rzvy_location_selector_status == ""){ 
			$_SESSION['rzvy_location_selector_zipcode'] = "N/A";
			echo "available";
		}else{
			$zipcode = str_replace(' ', '', $_POST["zipcode"]);
			$rzvy_location_selector = strtolower($obj_settings->get_option('rzvy_location_selector'));
			$exploded_rzvy_location_selector = explode(",", $rzvy_location_selector);
			
			$j=0;
			for($i=0;$i<sizeof($exploded_rzvy_location_selector);$i++){
				if(strtolower($exploded_rzvy_location_selector[$i]) == strtolower($zipcode)){
					$j++;
				}
			}
			if($j>0){
				$_SESSION['rzvy_location_selector_zipcode'] = $zipcode;
				echo "available";
			}
		}
	}
}
