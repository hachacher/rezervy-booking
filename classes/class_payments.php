<?php 
if (!class_exists('rzvy_payments')){
class rzvy_payments{
	public $conn;
	public $id;
	public $order_id;
	public $payment_method;
	public $payment_date;
	public $transaction_id;
	public $sub_total;
	public $discount;
	public $tax;
	public $net_total;
	public $fd_key;
	public $fd_amount;
	public $lastmodified;
	public $rzvy_payments = 'rzvy_payments';
	public $rzvy_customers = 'rzvy_customers';
	public $rzvy_bookings = 'rzvy_bookings';
	public $rzvy_customer_orderinfo = 'rzvy_customer_orderinfo';
	public $rzvy_categories = 'rzvy_categories';
	public $rzvy_services = 'rzvy_services';
	public $rzvy_sp_transactions = 'rzvy_sp_transactions';
	
	/* Function to count all registered customers payments */
	public function count_all_rc_payments($search){
		if($search != ''){
			$query = "select count(`p`.`id`) from `".$this->rzvy_payments."` as `p`, `".$this->rzvy_customers."` as `c`, `".$this->rzvy_bookings."` as `b`, `rzvy_pos_payments` as `pp` where ((`c`.`firstname` like '%".$search."%' or `c`.`lastname` like '%".$search."%') or `p`.`payment_method` like '%".$search."%' or `p`.`payment_date` like '%".$search."%' or `p`.`transaction_id` like '%".$search."%' or `p`.`sub_total` like '%".$search."%' or `p`.`discount` like '%".$search."%' or `p`.`tax` like '%".$search."%' or `p`.`net_total` like '%".$search."%' or `p`.`fd_key` like '%".$search."%' or `p`.`fd_amount` like '%".$search."%') and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id` = `c`.`id` and `p`.`order_id` not in (SELECT `parent_orderid` FROM `rzvy_pos_payments`) group by `p`.`order_id`";
		}else{
			$query = "select count(`p`.`id`) from `".$this->rzvy_payments."` as `p`, `".$this->rzvy_customers."` as `c`, `".$this->rzvy_bookings."` as `b`, `rzvy_pos_payments` as `pp` where `b`.`order_id` = `p`.`order_id` and `b`.`customer_id` = `c`.`id` and `p`.`order_id` not in (SELECT `parent_orderid` FROM `rzvy_pos_payments`) group by `p`.`order_id`";
		}
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_array($result);
			return $value[0];
		}else{
			return 0;
		}
	}
	
	/* Function to get all registered customers payment detail */
	public function get_all_rc_payment_detail($start, $end, $search, $column,$direction, $draw){
		$order_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `p`.`order_id` DESC';
		}else if($column == 0){
			$order_by_qry = 'order by `c`.`firstname` '.$direction;
		}else if($column == 1){
			$order_by_qry = 'order by `p`.`payment_method` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `p`.`payment_date` '.$direction;
		}else if($column == 3){
			$order_by_qry = 'order by `p`.`transaction_id` '.$direction;
		}else if($column == 4){
			$order_by_qry = 'order by `p`.`sub_total` '.$direction;
		}else if($column == 5){
			$order_by_qry = 'order by `p`.`discount` '.$direction;
		}else if($column == 6){
			$order_by_qry = 'order by `p`.`tax` '.$direction;
		}else if($column == 7){
			$order_by_qry = 'order by `p`.`partial_deposite` '.$direction;
		}else if($column == 8){
			$order_by_qry = 'order by `p`.`net_total` '.$direction;
		}else if($column == 9){
			$order_by_qry = 'order by `p`.`fd_key` '.$direction;
		}else if($column == 10){
			$order_by_qry = 'order by `p`.`fd_amount` '.$direction;
		}else{
			$order_by_qry = 'order by `p`.`order_id` '.$direction;
		}
		if($search != ''){
			$query = "select `c`.`firstname`, `c`.`lastname`, `p`.`order_id`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `p`.`refer_discount`, `p`.`partial_deposite` from `".$this->rzvy_payments."` as `p`, `".$this->rzvy_customers."` as `c`, `".$this->rzvy_bookings."` as `b`, `rzvy_pos_payments` as `pp` where ((`c`.`firstname` like '%".$search."%' or `c`.`lastname` like '%".$search."%') or `p`.`payment_method` like '%".$search."%' or `p`.`payment_date` like '%".$search."%' or `p`.`transaction_id` like '%".$search."%' or `p`.`sub_total` like '%".$search."%' or `p`.`discount` like '%".$search."%' or `p`.`tax` like '%".$search."%' or `p`.`net_total` like '%".$search."%' or `p`.`fd_key` like '%".$search."%' or `p`.`fd_amount` like '%".$search."%' or `p`.`order_id` like '%".$search."%') and `b`.`order_id` = `p`.`order_id` and `p`.`order_id` not in (SELECT `parent_orderid` FROM `rzvy_pos_payments`) and `b`.`customer_id` = `c`.`id` group by `p`.`order_id` ".$order_by_qry." limit ".$start.", ".$end;
		}else{
			$query = "select `c`.`firstname`, `c`.`lastname`, `p`.`order_id`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `p`.`refer_discount`, `p`.`partial_deposite` from `".$this->rzvy_payments."` as `p`, `".$this->rzvy_customers."` as `c`, `".$this->rzvy_bookings."` as `b`, `rzvy_pos_payments` as `pp` where `b`.`order_id` = `p`.`order_id` and `p`.`order_id` not in (SELECT `parent_orderid` FROM `rzvy_pos_payments`) and `b`.`customer_id` = `c`.`id` group by `p`.`order_id` ".$order_by_qry." limit ".$start.", ".$end;
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to count all guest customers payments */
	public function count_all_gc_payments($search){
		if($search != ''){
			$query = "select count(`p`.`id`) from `".$this->rzvy_payments."` as `p`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_bookings."` as `b`, `rzvy_pos_payments` as `pp` where ((`o`.`c_firstname` like '%".$search."%' or `o`.`c_lastname` like '%".$search."%') or `p`.`payment_method` like '%".$search."%' or `p`.`payment_date` like '%".$search."%' or `p`.`transaction_id` like '%".$search."%' or `p`.`sub_total` like '%".$search."%' or `p`.`discount` like '%".$search."%' or `p`.`tax` like '%".$search."%' or `p`.`net_total` like '%".$search."%' or `p`.`fd_key` like '%".$search."%' or `p`.`fd_amount` like '%".$search."%' or `p`.`order_id` like '%".$search."%') and `b`.`order_id` = `p`.`order_id` and `b`.`order_id` = `o`.`order_id` and `b`.`customer_id` = '0' and `p`.`order_id` not in (SELECT `parent_orderid` FROM `rzvy_pos_payments`) group by `p`.`order_id`";
		}else{
			$query = "select count(`p`.`id`) from `".$this->rzvy_payments."` as `p`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_bookings."` as `b`, `rzvy_pos_payments` as `pp` where `b`.`order_id` = `p`.`order_id` and `b`.`order_id` = `o`.`order_id` and `b`.`customer_id` = '0' and `p`.`order_id` not in (SELECT `parent_orderid` FROM `rzvy_pos_payments`) group by `p`.`order_id`";
		}
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_array($result);
			return $value[0];
		}else{
			return 0;
		}
	}
	
	/* Function to get all guest customers payment detail */
	public function get_all_gc_payment_detail($start, $end, $search, $column,$direction, $draw){
		$order_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `p`.`order_id` DESC';
		}else if($column == 0){
			$order_by_qry = 'order by `o`.`c_firstname` '.$direction;
		}else if($column == 1){
			$order_by_qry = 'order by `p`.`payment_method` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `p`.`payment_date` '.$direction;
		}else if($column == 3){
			$order_by_qry = 'order by `p`.`transaction_id` '.$direction;
		}else if($column == 4){
			$order_by_qry = 'order by `p`.`sub_total` '.$direction;
		}else if($column == 5){
			$order_by_qry = 'order by `p`.`discount` '.$direction;
		}else if($column == 6){
			$order_by_qry = 'order by `p`.`tax` '.$direction;
		}else if($column == 7){
			$order_by_qry = 'order by `p`.`partial_deposite` '.$direction;
		}else if($column == 8){
			$order_by_qry = 'order by `p`.`net_total` '.$direction;
		}else if($column == 9){
			$order_by_qry = 'order by `p`.`fd_key` '.$direction;
		}else if($column == 10){
			$order_by_qry = 'order by `p`.`fd_amount` '.$direction;
		}else{
			$order_by_qry = 'order by `p`.`order_id` '.$direction;
		}
		if($search != ''){
			$query = "select `o`.`c_firstname`, `o`.`c_lastname`, `p`.`order_id`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `p`.`partial_deposite` from `".$this->rzvy_payments."` as `p`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_bookings."` as `b`, `rzvy_pos_payments` as `pp` where ((`o`.`c_firstname` like '%".$search."%' or `o`.`c_lastname` like '%".$search."%') or `p`.`payment_method` like '%".$search."%' or `p`.`payment_date` like '%".$search."%' or `p`.`transaction_id` like '%".$search."%' or `p`.`sub_total` like '%".$search."%' or `p`.`discount` like '%".$search."%' or `p`.`tax` like '%".$search."%' or `p`.`net_total` like '%".$search."%' or `p`.`fd_key` like '%".$search."%' or `p`.`fd_amount` like '%".$search."%' or `p`.`order_id` like '%".$search."%') and `b`.`order_id` = `p`.`order_id` and `b`.`order_id` = `o`.`order_id` and `p`.`order_id` not in (SELECT `parent_orderid` FROM `rzvy_pos_payments`) and `b`.`customer_id` = '0' group by `p`.`order_id` ".$order_by_qry." limit ".$start.", ".$end;
		}else{
			$query = "select `o`.`c_firstname`, `o`.`c_lastname`, `p`.`order_id`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `p`.`partial_deposite` from `".$this->rzvy_payments."` as `p`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_bookings."` as `b`, `rzvy_pos_payments` as `pp` where `b`.`order_id` = `p`.`order_id` and `b`.`order_id` = `o`.`order_id` and `p`.`order_id` not in (SELECT `parent_orderid` FROM `rzvy_pos_payments`) and `b`.`customer_id` = '0' group by `p`.`order_id` ".$order_by_qry." limit ".$start.", ".$end;
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all customers Payments details for export */
	public function get_all_customers_payments($start, $end){
		$selected_fields = '`b`.`order_id`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `c`.`cat_name`, `s`.`title`, `b`.`service_rate`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`customer_id`';
		
		$from_qry = "`".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_payments."` as `p`";
		
		$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and date(`b`.`order_date`) BETWEEN '".$start."' and '".$end."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all only registered customers Payments details for export */
	public function all_registered_customers_payments($start, $end){
		$selected_fields = '`b`.`order_id`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `c`.`cat_name`, `s`.`title`, `b`.`service_rate`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`customer_id`';
		
		$from_qry = "`".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_payments."` as `p`";
		
		$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id` <> '0' and date(`b`.`order_date`) BETWEEN '".$start."' and '".$end."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all only guest customers Payments details for export */
	public function all_guest_customers_payments($start, $end){
		$selected_fields = '`b`.`order_id`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `c`.`cat_name`, `s`.`title`, `b`.`service_rate`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`customer_id`';
		
		$from_qry = "`".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_payments."` as `p`";
		
		$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id` = '0' and date(`b`.`order_date`) BETWEEN '".$start."' and '".$end."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get net_total by order id */
	public function get_order_net_total(){
	    $query = "select `net_total` from `".$this->rzvy_payments."` where `order_id`='".$this->order_id."'";
			
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_array($result);
			return $value['net_total'];
		}else{
			return '0';
		}
	}
	
	/* Function to get package discount by order id */
	public function get_package_discount_by_orderid($order_id){
	    $query = "select sum(`sp_discount`) as `package_discount` from `".$this->rzvy_sp_transactions."` where `order_id`='".$order_id."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_array($result);
			return $value['package_discount'];
		}else{	
			return '0';
		}
	}
}
}
?>