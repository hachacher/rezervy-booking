<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_payments.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_payments = new rzvy_payments();
$obj_payments->conn = $conn;
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

$rzvy_currency_symbol = $obj_settings->get_option('rzvy_currency_symbol');
$rzvy_currency_position = $obj_settings->get_option('rzvy_currency_position');
$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');

/* Refresh Guest customers payment ajax */
if(isset($_REQUEST['refresh_gc_payments'])){
	$all_payments = $obj_payments->get_all_gc_payment_detail($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$payments = array();
	$payments["draw"] = $_POST['draw'];
	$count_all_payments = $obj_payments->count_all_gc_payments($_POST['search']['value']);
	$payments["recordsTotal"] = $count_all_payments;
	$payments["recordsFiltered"] = $count_all_payments;
	$payments['data'] =array();
	
	if(mysqli_num_rows($all_payments)>0){
		$i=$_POST['start'];
		while($payment = mysqli_fetch_assoc($all_payments)){
			$i++;
			$payment_arr = array();
			
			if($payment['fd_key']!=""){ if($payment['fd_key']=="weekly"){ $paymentfdlabel = $fq_weekly_label; }else if($payment['fd_key']=="bi weekly"){ $paymentfdlabel = $fq_biweekly_label; }else if($payment['fd_key']=="monthly"){ $paymentfdlabel = $fq_monthly_label; }else{ $paymentfdlabel = $fq_one_time_label; } }else{ $paymentfdlabel = ""; }
			
			array_push($payment_arr, $payment['order_id']);
			array_push($payment_arr, ucwords($payment['c_firstname'].' '.$payment['c_lastname']));
			array_push($payment_arr, ucwords($payment['payment_method']));
			array_push($payment_arr, date($rzvy_date_format, strtotime($payment['payment_date'])));
			array_push($payment_arr, $payment['transaction_id']);
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['sub_total']));
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['discount']));
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['tax']));
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['partial_deposite']));
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['net_total']));
			array_push($payment_arr, $paymentfdlabel);
			array_push($payment_arr, $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$payment['fd_amount']));
			array_push($payments['data'], $payment_arr);
		}
	}
	echo json_encode($payments);
}
