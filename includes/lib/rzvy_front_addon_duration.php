<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_frontend.php");

/* Create object of classes */
$obj_frontend = new rzvy_frontend();
$obj_frontend->conn = $conn;

/* calculate front booking addon duration */
if(isset($_POST['front_addon_duration'])){
	$total_addon_duration = 0;
	if(sizeof($_SESSION['rzvy_cart_items'])>0){
		foreach($_SESSION['rzvy_cart_items'] as $val){ 
			$total_addon_duration = ($total_addon_duration+$val['duration']);
		}
	}
	echo $total_addon_duration;											
	$_SESSION['rzvy_cart_total_addon_duration'] = $total_addon_duration;
}
/* calculate front booking addon duration */
else if(isset($_POST['mb_addon_duration'])){
	$total_addon_duration = 0;
	if(sizeof($_SESSION['rzvy_mb_cart_items'])>0){
		foreach($_SESSION['rzvy_mb_cart_items'] as $val){ 
			$total_addon_duration = ($total_addon_duration+$val['duration']);
		}
	}
	echo $total_addon_duration;
	$_SESSION['rzvy_mb_cart_total_addon_duration'] = $total_addon_duration;
}