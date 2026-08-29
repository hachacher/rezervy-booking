<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_payments.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_loyalty_points.php");

/* Create object of classes */
$obj_payments = new rzvy_payments();
$obj_payments->conn = $conn;
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;
$obj_loyalty_points = new rzvy_loyalty_points();
$obj_loyalty_points->conn = $conn;

$rzvy_currency_symbol = $obj_settings->get_option('rzvy_currency_symbol');
$rzvy_currency_position = $obj_settings->get_option('rzvy_currency_position');
$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
$rzvy_allow_loyalty_points_status = $obj_settings->get_option('rzvy_allow_loyalty_points_status');
$rzvy_perbooking_loyalty_point_value = $obj_settings->get_option('rzvy_perbooking_loyalty_point_value');

if(isset($rzvy_translangArr['points'])){ $pointslabel =  $rzvy_translangArr['points']; }else{ $pointslabel = $rzvy_defaultlang['points']; }

/* Refresh Registered customers payment ajax */
if(isset($_REQUEST['refresh_rc_payments'])){
	$all_payments = $obj_payments->get_all_rc_payment_detail($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$payments = array();
	$payments["draw"] = $_POST['draw'];
	$count_all_payments = $obj_payments->count_all_rc_payments($_POST['search']['value']);
	$payments["recordsTotal"] = $count_all_payments;
	$payments["recordsFiltered"] = $count_all_payments;
	$payments['data'] =array();
	
	if(mysqli_num_rows($all_payments)>0){
		$i=$_POST['start'];
		while($payment = mysqli_fetch_assoc($all_payments)){
			$i++;

			$payment_arr = array();
			
			if($payment['fd_key']!=""){ if($payment['fd_key']=="weekly"){ $paymentfdlabel = $fq_weekly_label; }else if($payment['fd_key']=="bi weekly"){ $paymentfdlabel = $fq_biweekly_label; }else if($payment['fd_key']=="monthly"){ $paymentfdlabel = $fq_monthly_label; }else{ $paymentfdlabel = $fq_one_time_label; } }else{ $paymentfdlabel = ""; }
			
			$package_discount = $obj_payments->get_package_discount_by_orderid($payment['order_id']);
			if($package_discount==''){
				$package_discount = '0';
			}
			
			$payment_method = ucwords($payment['payment_method']);
			if($payment_method==ucwords('pay-at-venue')){
				if(isset($rzvy_translangArr['pay_at_venue'])){ $payment_method = $rzvy_translangArr['pay_at_venue']; }else{ $payment_method = $rzvy_defaultlang['pay_at_venue']; } 
			}
			
			array_push($payment_arr, $payment['order_id']);
			array_push($payment_arr, ucwords($payment['firstname'].' '.$payment['lastname']));
			array_push($payment_arr, $payment_method);
			array_push($payment_arr, date($rzvy_date_format, strtotime($payment['payment_date'])));
			array_push($payment_arr, $payment['transaction_id']);
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['sub_total']));
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['discount']));
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['refer_discount']));
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$package_discount));
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['tax']));
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['partial_deposite']));
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['net_total']));
			array_push($payment_arr, $paymentfdlabel);
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['fd_amount']));
			
			/* Loyalty Points If Used */
			$loyalty_points_used =0;
			$loyalty_points_usedval =0;
			if($rzvy_allow_loyalty_points_status=='Y'){
    			$usedpoints = $obj_loyalty_points->get_used_points_customer_by_order_id($payment['order_id']);
    			if(isset($usedpoints) && $usedpoints!=''){
    			    $loyalty_points_used = $usedpoints;
    			    $loyalty_points_usedval = $rzvy_perbooking_loyalty_point_value*$usedpoints;
    			}
			}
			$loyalty_points_amount = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$loyalty_points_usedval).'('.$loyalty_points_used.' '.$pointslabel.')';
			if($loyalty_points_used==0){
			    $loyalty_points_amount = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$loyalty_points_usedval);
			}
			array_push($payment_arr,$loyalty_points_amount);
			
			array_push($payments['data'], $payment_arr);
		}
	}
	echo json_encode($payments);
}
