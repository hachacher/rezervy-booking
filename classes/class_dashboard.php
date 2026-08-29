<?php 
if (!class_exists('rzvy_dashboard')){
class rzvy_dashboard{
	public $conn;
	public $startdate;
	public $enddate;
	public $samedate;
	public $rzvy_bookings = 'rzvy_bookings';
	public $rzvy_customers = 'rzvy_customers';
	public $rzvy_customer_orderinfo = 'rzvy_customer_orderinfo';
	public $rzvy_categories = 'rzvy_categories';
	public $rzvy_services = 'rzvy_services';
	public $rzvy_payments = 'rzvy_payments';
	public $rzvy_pos_payments = 'rzvy_pos_payments';
	public $rzvy_appointment_feedback = 'rzvy_appointment_feedback';
	public $rzvy_customer_referrals = 'rzvy_customer_referrals';
	public $rzvy_staff = 'rzvy_staff';
	
	public function get_count_of_all_registered_customers(){
		if($this->samedate == "Y"){
			$dateCheck = " and date(`b`.`booking_datetime`) = '".$this->startdate."'";
		}else{
			$dateCheck = " and date(`b`.`booking_datetime`) between '".$this->startdate."' and '".$this->enddate."'";
		}
		$query = "select `c`.`id` from `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customers."` as `c` where `c`.`status` = 'Y' and `b`.`customer_id`=`c`.`id` ".$dateCheck." group by `c`.`id` order by MAX(`b`.`order_id`) ASC";
		
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			return mysqli_num_rows($result);
		}else{
			return 0;
		}
	}
	
	public function get_count_of_all_guest_customers(){
		if($this->samedate == "Y"){
			$dateCheck = " and date(`b`.`booking_datetime`) = '".$this->startdate."'";
		}else{
			$dateCheck = " and date(`b`.`booking_datetime`) between '".$this->startdate."' and '".$this->enddate."'";
		}
		$query = "select `b`.`customer_id` from `".$this->rzvy_bookings."` as `b` where `b`.`customer_id`='0' ".$dateCheck." group by `b`.`order_id` order by `b`.`order_id` ASC";
		
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			return mysqli_num_rows($result);
		}else{
			return 0;
		}
	}
	
	public function get_count_of_total_revenue(){
		if($this->samedate == "Y"){
			$dateCheck = " and date(`b`.`booking_datetime`) = '".$this->startdate."'";
		}else{
			$dateCheck = " and date(`b`.`booking_datetime`) between '".$this->startdate."' and '".$this->enddate."'";
		}
	 	$query = "select sum(`p`.`net_total`) as `total_revenue` from `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_payments."` as `p` where `b`.`booking_status`<>'rejected_by_you' and `b`.`booking_status`<>'rejected_by_staff' and `b`.`booking_status`<>'cancelled_by_customer' and `b`.`order_id`=`p`.`order_id` and `p`.`order_id` not in (SELECT `parent_orderid` FROM `rzvy_pos_payments`) ".$dateCheck;
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		$totalrevenue = $value['total_revenue'];
		
		$query2 = "select sum(`p`.`net_total`) as `total_revenue` from `".$this->rzvy_bookings."` as `b`, `rzvy_pos_payments` as `p` where `b`.`order_id`=`p`.`parent_orderid` ".$dateCheck;
		$result2=mysqli_query($this->conn,$query2);
		$value2=mysqli_fetch_array($result2);
		$totalposrevenue = $value2['total_revenue'];
		
		return ($totalrevenue+$totalposrevenue);
	}
	
	public function get_staff_total_revenue($id){
		if($this->samedate == "Y"){
			$dateCheck = " and date(`b`.`booking_datetime`) = '".$this->startdate."'";
		}else{
			$dateCheck = " and date(`b`.`booking_datetime`) between '".$this->startdate."' and '".$this->enddate."'";
		}
		$query = "select sum(`p`.`net_total`) as `total_revenue` from `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_payments."` as `p` where `b`.`order_id`=`p`.`order_id`  and `b`.`booking_status`='completed' and `b`.`staff_id`='".$id."' and `p`.`order_id` not in (SELECT `parent_orderid` FROM `rzvy_pos_payments`) ".$dateCheck;
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		$totalrevenue = $value['total_revenue'];
		
		$query2 = "select sum(`p`.`net_total`) as `total_revenue` from `".$this->rzvy_bookings."` as `b`, `rzvy_pos_payments` as `p` where `b`.`order_id`=`p`.`parent_orderid`  and `b`.`booking_status`='completed' and `b`.`staff_id`='".$id."' ".$dateCheck;
		$result2=mysqli_query($this->conn,$query2);
		$value2=mysqli_fetch_array($result2);
		$totalposrevenue = $value2['total_revenue'];
		
		return ($totalrevenue+$totalposrevenue);
	}
	
	public function get_staff_total_appointment($id){
		if($this->samedate == "Y"){
			$dateCheck = " and date(`booking_datetime`) = '".$this->startdate."'";
		}else{
			$dateCheck = " and date(`booking_datetime`) between '".$this->startdate."' and '".$this->enddate."'";
		}
		$query = "select count(`order_id`) as `total_appointments` from `".$this->rzvy_bookings."` where `booking_status`='completed' and `staff_id`='".$id."'".$dateCheck;
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['total_appointments'];
	}
	
	public function get_count_of_all_appointments(){
		if($this->samedate == "Y"){
			$dateCheck = " where date(`booking_datetime`) = '".$this->startdate."'";
		}else{
			$dateCheck = " where date(`booking_datetime`) between '".$this->startdate."' and '".$this->enddate."'";
		}
		$query = "select count(`order_id`) as `total_appointments` from `".$this->rzvy_bookings."`".$dateCheck;
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['total_appointments'];
	}
	
	public function get_count_of_all_customer_appointments($cid){
		$query = "select count(`order_id`) as `total_appointments` from `".$this->rzvy_bookings."` where `customer_id` = '".$cid."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['total_appointments'];
	}
	
	public function get_all_upcoming_appointments($currdatetime){
		$order_by_qry = 'order by `b`.`lastmodified` DESC';
		
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`lastmodified`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email` from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`booking_datetime` > '".$currdatetime."' ".$group_by_qry." ".$order_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	public function get_all_upcoming_customer_appointments($currdatetime, $cid){
		$order_by_qry = 'order by `b`.`lastmodified` DESC';
		
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`lastmodified`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email` from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o` where `b`.`cat_id` = `c`.`id` and `b`.`customer_id` = '".$cid."' and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`booking_datetime` > '".$currdatetime."' ".$group_by_qry." ".$order_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	public function get_all_upcoming_staff_appointments($currdatetime, $sid){
		$order_by_qry = 'order by `b`.`lastmodified` DESC';
		
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`lastmodified`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email` from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o` where `b`.`cat_id` = `c`.`id` and `b`.`staff_id` = '".$sid."' and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`booking_datetime` > '".$currdatetime."' ".$group_by_qry." ".$order_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	public function get_all_today_upcoming_appointments($currdatetime, $currenddatetime){
		$order_by_qry = 'order by `b`.`lastmodified` DESC';
		
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`lastmodified`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email` from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`booking_datetime` > '".$currdatetime."' and `b`.`booking_datetime` < '".$currenddatetime."' ".$group_by_qry." ".$order_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	public function get_all_today_upcoming_customer_appointments($currdatetime, $currenddatetime, $cid){
		$order_by_qry = 'order by `b`.`lastmodified` DESC';
		
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`lastmodified`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email` from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`customer_id` = '".$cid."' and `b`.`booking_datetime` > '".$currdatetime."' and `b`.`booking_datetime` < '".$currenddatetime."' ".$group_by_qry." ".$order_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	public function get_all_today_upcoming_staff_appointments($currdatetime, $currenddatetime, $sid){
		$order_by_qry = 'order by `b`.`lastmodified` DESC';
		
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`lastmodified`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email` from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`staff_id` = '".$sid."' and `b`.`booking_datetime` > '".$currdatetime."' and `b`.`booking_datetime` < '".$currenddatetime."' ".$group_by_qry." ".$order_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	public function get_all_staff(){
		$query = "select `id`, `firstname`, `lastname`  from `".$this->rzvy_staff."`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}  
}  
?>