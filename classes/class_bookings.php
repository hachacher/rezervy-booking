<?php 
if (!class_exists('rzvy_bookings')){
class rzvy_bookings{
	public $conn;
	public $id;
	public $order_id;
	public $customer_id;
	public $booking_datetime;
	public $booking_end_datetime;
	public $order_date;
	public $cat_id;
	public $service_id;
	public $addons;
	public $booking_status;
	public $reschedule_reason;
	public $reject_reason;
	public $cancel_reason;
	public $reminder_status;
	public $read_status;
	public $lastmodified;
	public $staff_id;
	public $rzvy_bookings = 'rzvy_bookings';
	public $rzvy_customers = 'rzvy_customers';
	public $rzvy_customer_orderinfo = 'rzvy_customer_orderinfo';
	public $rzvy_categories = 'rzvy_categories';
	public $rzvy_services = 'rzvy_services';
	public $rzvy_addons = 'rzvy_addons';
	public $rzvy_payments = 'rzvy_payments';
	public $rzvy_appointment_feedback = 'rzvy_appointment_feedback';
	public $rzvy_customer_referrals = 'rzvy_customer_referrals';
	public $rzvy_staff = 'rzvy_staff';
	public $rzvy_support_tickets = 'rzvy_support_tickets';
	public $rzvy_support_ticket_discussions = 'rzvy_support_ticket_discussions';
	public $rzvy_pos_bookings = 'rzvy_pos_bookings';
	public $rzvy_pos_payments = 'rzvy_pos_payments';
	public $rzvy_sp_transactions = 'rzvy_sp_transactions';
	
	/* Function to delete appointments detail */
	public function delete_appointment(){		
		$result = mysqli_query($this->conn,"delete from `".$this->rzvy_bookings."` where `order_id`='".$this->order_id."'");
		$result = mysqli_query($this->conn,"delete from `".$this->rzvy_customer_orderinfo."` where `order_id`='".$this->order_id."'");
		$result = mysqli_query($this->conn,"delete from `".$this->rzvy_payments."` where `order_id`='".$this->order_id."'");
		$result = mysqli_query($this->conn,"delete from `".$this->rzvy_pos_bookings."` where `parent_orderid`='".$this->order_id."'");
		$result = mysqli_query($this->conn,"delete from `".$this->rzvy_pos_payments."` where `parent_orderid`='".$this->order_id."'");
		$result = mysqli_query($this->conn,"update `".$this->rzvy_sp_transactions."` set  `order_id`='0',`sp_discount`='0' where `order_id`='".$this->order_id."'");
		return $result;
	}
	
	/* Function to get count of latest unread appointments detail */
	public function get_count_of_latest_unread_appointments(){
		$query = "select count(`order_id`) as `total_unread_appointments` from `".$this->rzvy_bookings."` where `read_status` = 'U'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['total_unread_appointments'];
	}
	
	/* Function to get all latest unread appointments detail */
	public function get_all_latest_unread_appointments(){
				
		$order_by_qry = 'order by `b`.`lastmodified` DESC';
		
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`lastmodified`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email` from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`read_status` = 'U' ".$group_by_qry." ".$order_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all appointments detail */
	public function get_all_appointments(){
				
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`staff_id`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`addons`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`staff_id` from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` ".$group_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all appointments without cancelled detail */
	public function get_all_appointments_without_cancelled(){
				
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`staff_id`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`addons`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`staff_id` from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o` where `b`.`booking_status` <> 'cancelled_by_customer' and `b`.`booking_status` <> 'rejected_by_you' and `b`.`booking_status` <> 'rejected_by_staff' and `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` ".$group_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to get all customer appointments detail */
	public function get_all_customer_appointments(){
				
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`staff_id`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`staff_id` from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`customer_id`='".$this->customer_id."' ".$group_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to appointment detail from order id */
	public function get_appointment_detail(){
		$selected_fields = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`, `b`.`addons`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`';
		
		$from_qry = "`".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_payments."` as `p`";
		
		$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`order_id`='".$this->order_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get reschedule appointment tab detail */
	public function get_reschedule_appointment_detail(){
		$selected_fields = '`b`.`order_id`, `b`.`booking_datetime`, `b`.`reschedule_reason`, `b`.`booking_end_datetime`, `b`.`service_id`, `b`.`staff_id`';
		
		$from_qry = "`".$this->rzvy_bookings."` as `b`";
		
		$where_qry = "`b`.`order_id`='".$this->order_id."'";
		
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* Function to get reject appointment tab detail */
	public function get_reject_appointment_detail(){
		$selected_fields = '`b`.`order_id`, `b`.`reject_reason`, `b`.`booking_datetime`, `p`.`net_total`';
		
		$from_qry = "`".$this->rzvy_bookings."` as `b`, `".$this->rzvy_payments."` as `p`";
		
		$where_qry = "`b`.`order_id` = `p`.`order_id` and `b`.`order_id`='".$this->order_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get appointment detail tab detail */
	public function get_appointment_detail_tab(){
		$selected_fields = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`addons`, `b`.`service_rate`, `b`.`staff_id`, `o`.`c_notes`';
		
		$from_qry = "`".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`";
		
		$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id`='".$this->order_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get payment detail tab detail */
	public function get_payment_detail_tab(){
		$selected_fields = '`b`.`order_id`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `p`.`refer_discount`,`p`.`refer_discount_id`, `p`.`partial_deposite`';
		
		$from_qry = "`".$this->rzvy_bookings."` as `b`, `".$this->rzvy_payments."` as `p`";
		
		$where_qry = "`b`.`order_id` = `p`.`order_id` and `b`.`order_id`='".$this->order_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get customer detail tab detail */
	public function get_customer_detail_tab(){
		$selected_fields = '`b`.`order_id`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`, `o`.`c_dob`, `o`.`c_notes`, `b`.`customer_id`';
		
		$from_qry = "`".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`";
		
		$where_qry = "`b`.`order_id` = `o`.`order_id` and `b`.`order_id`='".$this->order_id."'";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to reschedule appointment detail */
	public function reschedule_appointment(){
		$update_fields = "`booking_status` = '".$this->booking_status."', `reschedule_reason` = '".$this->reschedule_reason."', `booking_datetime` = '".$this->booking_datetime."', `booking_end_datetime` = '".$this->booking_end_datetime."', `lastmodified` = '".date("Y-m-d H:i:s")."', `read_status` = 'U'";
		
		$where_qry = "`order_id`='".$this->order_id."'";
		
		$query = "update `".$this->rzvy_bookings."` set ".$update_fields." where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to reschedule appointment detail */
	public function reschedule_appointment_through_addon(){
		$update_fields = "`booking_status` = '".$this->booking_status."', `reschedule_reason` = '".$this->reschedule_reason."', `booking_datetime` = '".$this->booking_datetime."', `booking_end_datetime` = '".$this->booking_end_datetime."', `lastmodified` = '".date("Y-m-d H:i:s")."', `read_status` = 'U', `staff_id` = '".$this->staff_id."'";
		
		$where_qry = "`order_id`='".$this->order_id."'";
		
		$query = "update `".$this->rzvy_bookings."` set ".$update_fields." where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to reschedule appointment detail */
	public function adminstaff_reschedule_appointment(){
		$update_fields = "`booking_status` = '".$this->booking_status."', `reschedule_reason` = '".$this->reschedule_reason."', `booking_datetime` = '".$this->booking_datetime."', `booking_end_datetime` = '".$this->booking_end_datetime."', `lastmodified` = '".date("Y-m-d H:i:s")."', `read_status` = 'U', `staff_id` = '".$this->staff_id."'";
		
		$where_qry = "`order_id`='".$this->order_id."'";
		
		$query = "update `".$this->rzvy_bookings."` set ".$update_fields." where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to reject appointment detail */
	public function reject_appointment(){
		$update_fields = "`booking_status` = '".$this->booking_status."', `reject_reason` = '".$this->reject_reason."', `lastmodified` = '".date("Y-m-d H:i:s")."', `read_status` = 'U'";
		
		$where_qry = "`order_id`='".$this->order_id."'";
		
		$query = "update `".$this->rzvy_bookings."` set ".$update_fields." where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to confirm appointment detail */
	public function change_appointment_status(){
		if($this->booking_status == 'completed'){
			mysqli_query($this->conn, "update `".$this->rzvy_customer_referrals."` set `completed`='Y' where `order_id`='".$this->order_id."'");
		}
		$update_fields = "`booking_status` = '".$this->booking_status."', `lastmodified` = '".date("Y-m-d H:i:s")."', `read_status` = 'U'";
		
		$where_qry = "`order_id`='".$this->order_id."'";
		
		$query = "update `".$this->rzvy_bookings."` set ".$update_fields." where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to Mark appointment as read */
	public function mark_appointment_as_read(){
		$update_fields = "`read_status` = 'R'";
		
		$where_qry = "`order_id`='".$this->order_id."'";
		if($this->order_id=='all'){
			$where_qry = "`read_status`='U'";
		}		
		$query = "update `".$this->rzvy_bookings."` set ".$update_fields." where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get appointment status detail */
	public function get_appointment_status(){
		$where_qry = "`order_id`='".$this->order_id."'";
		$query = "select `booking_status` from `".$this->rzvy_bookings."` where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['booking_status'];
	}
	
	
	/* Function to get appointment status detail */
	public function get_appointment_status_and_datetime(){
		$query = "select `b`.`booking_status`, `b`.`booking_datetime`, `p`.`sub_total`, `b`.`service_id`, `b`.`customer_id`, `b`.`addons` from `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_payments."` as `p` where `b`.`order_id` = `p`.`order_id` and `b`.`order_id`='".$this->order_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	
	/* Function to get all customers appointment details for export */
	public function get_all_customers_appointments($start, $end){
		$selected_fields = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`service_rate`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`reschedule_reason`, `b`.`reject_reason`, `b`.`cancel_reason`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `b`.`order_date`, `b`.`customer_id`';
		
		$from_qry = "`".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_payments."` as `p`";
		
		if($start == $end){
			$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and date(`b`.`order_date`) = '".$start."'";
		}else{
			$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and date(`b`.`order_date`) BETWEEN '".$start."' and '".$end."'";
		}
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all only registered customers appointment details for export */
	public function all_registered_customers_appointments($start, $end){
		$selected_fields = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`service_rate`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`reschedule_reason`, `b`.`reject_reason`, `b`.`cancel_reason`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `b`.`order_date`, `b`.`customer_id`';
		
		$from_qry = "`".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_payments."` as `p`";
		
		if($start == $end){
			$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id` <> '0' and date(`b`.`order_date`) = '".$start."'";
		}else{
			$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id` <> '0' and date(`b`.`order_date`) BETWEEN '".$start."' and '".$end."'";
		}
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all only guest customers appointment details for export */
	public function all_guest_customers_appointments($start, $end){
		$selected_fields = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`service_rate`, `b`.`addons`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`reschedule_reason`, `b`.`reject_reason`, `b`.`cancel_reason`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `o`.`c_address`, `o`.`c_city`, `o`.`c_state`, `o`.`c_country`, `o`.`c_zip`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `b`.`order_date`, `b`.`customer_id`';
		
		$from_qry = "`".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_payments."` as `p`";
		
		if($start == $end){
			$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id` = '0' and date(`b`.`order_date`) = '".$start."'";
		}else{
			$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` and `b`.`customer_id` = '0' and date(`b`.`order_date`) BETWEEN '".$start."' and '".$end."'";
		}
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/** function to get appointment feedback ratings **/
	public function get_appointment_rating($order_id){
		$query = "select * from `".$this->rzvy_appointment_feedback."` where `order_id` = '".$order_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/** function to get appointment feedback ratings **/
	public function add_appointment_feedback($order_id, $rating, $review){
		$query = "INSERT INTO `".$this->rzvy_appointment_feedback."` (`id`, `order_id`, `rating`, `review`, `review_datetime`, `status`) VALUES (NULL, '".$order_id."', '".$rating."', '".$review."', '".date("Y-m-d H:i:s")."', 'Y')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all appointments detail */
	public function get_all_staff_appointments(){
		$group_by_qry = 'group by `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`staff_id`';
		
		$query = "select `b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`addons`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`staff_id` from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`staff_id`='".$this->staff_id."' ".$group_by_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to read particular staff's data */
	public function readone_staff(){
		$query = "select * from `".$this->rzvy_staff."` where `id`='".$this->staff_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
	
	/* Function to get all appointments detail */
	public function get_all_appointments_listview(){
		$selection_qry = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`staff_id`, `b`.`addons`, `b`.`service_rate`, `o`.`c_address`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `p`.`refer_discount`, `p`.`partial_deposite`, `b`.`customer_id`';
		$query = "select ".$selection_qry." from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_payments."` as `p` where `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` group by ".$selection_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all appointments detail by Staff */
	public function get_all_appointments_listview_by_staff(){
		$selection_qry = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `o`.`c_firstname`, `o`.`c_lastname`, `o`.`c_phone`, `o`.`c_email`, `b`.`staff_id`, `b`.`addons`, `b`.`service_rate`, `o`.`c_address`, `p`.`payment_method`, `p`.`payment_date`, `p`.`transaction_id`, `p`.`sub_total`, `p`.`discount`, `p`.`tax`, `p`.`net_total`, `p`.`fd_key`, `p`.`fd_amount`, `p`.`refer_discount`, `p`.`partial_deposite`, `b`.`customer_id`';
		$query = "select ".$selection_qry." from `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`, `".$this->rzvy_payments."` as `p` where `b`.`staff_id` = '".$this->staff_id."' and  `b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id` = `p`.`order_id` group by ".$selection_qry;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all active staff */
	public function get_all_active_staff(){
		$query = "select * from `".$this->rzvy_staff."` where `status`='Y' group by `id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get customer id detail */
	public function get_customer_id(){
		$where_qry = "`order_id`='".$this->order_id."'";
		$query = "select `customer_id` from `".$this->rzvy_bookings."` where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['customer_id'];
	}
	
	public function get_count_of_latest_unread_supporttickets(){
		$count = 0;
		$query = "select * from `".$this->rzvy_support_tickets."` where `generated_by` = 'customer'";
		$result=mysqli_query($this->conn,$query);
		while($val=mysqli_fetch_array($result)){
			if($val["read_status"] == "U"){
				$count++;
			}else{
				$qry = "select `id` from `".$this->rzvy_support_ticket_discussions."` where `read_status` = 'U' and `replied_by` = 'customer' and `ticket_id` = '".$val["id"]."' group by `ticket_id`";
				$res=mysqli_query($this->conn,$qry);
				if(mysqli_num_rows($res)>0){
					$count++;
				}
			}
		}
		return $count;
	}
	
	public function get_all_latest_unread_supporttickets(){
		$arr = array();
		$query = "select * from `".$this->rzvy_support_tickets."` where `generated_by` = 'customer'";
		$result=mysqli_query($this->conn,$query);
		while($val=mysqli_fetch_array($result)){
			if($val["read_status"] == "U"){
				array_push($arr, $val["id"]);
			}else{
				$qry = "select * from `".$this->rzvy_support_ticket_discussions."` where `read_status` = 'U' and `replied_by` = 'customer' and `ticket_id` = '".$val["id"]."' group by `ticket_id`";
				$res=mysqli_query($this->conn,$qry);
				if(mysqli_num_rows($res)>0){
					array_push($arr, $val["id"]);
				}
			}
		}
		return $arr;
	}
	
	public function readone_supportticket($id){
		$query = "select * from `".$this->rzvy_support_tickets."` where `id` = '".$id."'";
		$result=mysqli_query($this->conn,$query);
		$val=mysqli_fetch_array($result);
		return $val;
	}
	
	public function readone_supportticket_customer($id){
		$query = "select * from `".$this->rzvy_customers."` where `id` = '".$id."'";
		$result=mysqli_query($this->conn,$query);
		$val=mysqli_fetch_array($result);
		return $val;
	}
	/* Function to get refferal code by id */
	public function get_referral_code_by_id($referral_id){
		$where_qry = "`id`='".$referral_id."'";
		$query = "select `coupon` from `".$this->rzvy_customer_referrals."` where ".$where_qry;
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['coupon'];
	}
	/* Function to get customer's profile internal Notes */
	public function get_internal_note_of_customer(){
		$query = "select `c`.`internal_notes` from `".$this->rzvy_customers."` as `c`,`".$this->rzvy_bookings."` as `b` where `b`.`order_id`='".$this->order_id."' and `c`.`id`=`b`.`customer_id`";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_array($result);
			return $value['internal_notes'];
		}else{
			return "";
		}
	}
	public function check_isposbooking($order_id){
		
		$selected_fields = '`b`.`order_id`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`booking_status`, `b`.`addons`, `b`.`service_rate`, `b`.`staff_id`, `o`.`c_notes`';
		
		$from_qry = "`".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `".$this->rzvy_bookings."` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`, `rzvy_pos_payments` as `p`";
		
		$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`order_id` = `o`.`order_id` and `b`.`order_id`='".$order_id."' and `b`.`order_id` = `p`.`parent_orderid`";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$selected_fields;
		
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function fetch_posbookings($order_id){
		
		$selected_fields = '`b`.`id`, `b`.`parent_orderid`, `c`.`cat_name`, `s`.`title`, `b`.`booking_datetime`, `b`.`booking_end_datetime`, `b`.`addons`, `b`.`service_rate`, `b`.`discount` as `sdisc`, `b`.`staff_id`, `o`.`c_notes`, `p`.`pdeposite`, `p`.`sub_total`, `p`.`discount`, `p`.`cdiscount`, `p`.`rdiscount`, `p`.`lpdiscount`, `p`.`tax`, `p`.`net_total`, `p`.`payment_methods`, `p`.`last_modify`, `o`.`c_firstname`, `o`.`c_lastname`, `pb`.`customer_id`, `pb`.`booking_status`, `b`.`cat_id`, `b`.`service_id`';
    		
		$groupby_fields = '`b`.`id`';
		
		$from_qry = "`".$this->rzvy_bookings."` as `pb`, `".$this->rzvy_categories."` as `c`, `".$this->rzvy_services."` as `s`, `rzvy_pos_bookings` as `b`, `".$this->rzvy_customer_orderinfo."` as `o`, `rzvy_pos_payments` as `p`";
		
		$where_qry = "`b`.`cat_id` = `c`.`id` and `b`.`service_id` = `s`.`id` and `b`.`parent_orderid` = `o`.`order_id` and `b`.`parent_orderid`='".$order_id."' and `b`.`parent_orderid` = `p`.`parent_orderid`";
				
		$query = "select ".$selected_fields." from ".$from_qry." where ".$where_qry." group by ".$groupby_fields;
		
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get payment detail tab detail */
	public function fetch_pospayments($order_id){
		$query = "select * from `rzvy_pos_payments` where `parent_orderid`='".$order_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read particular Addon's data */
	public function readone_addon_title(){
		$query = "select `title` from `".$this->rzvy_addons."` where `id`='".$this->addons."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['title'];
	}
	
}  
}  
?>