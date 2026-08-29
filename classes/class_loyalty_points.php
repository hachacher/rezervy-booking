<?php 
if (!class_exists('rzvy_loyalty_points')){
class rzvy_loyalty_points{
	public $conn;
	public $id;
	public $order_id;
	public $customer_id;
	public $status;
	public $points;
	public $available_points;
	public $lastmodify;
	public $rzvy_loyalty_points = 'rzvy_loyalty_points';
	
	/** function to add loyalty points record **/
	public function add_loyalty_points_record($order_id, $customer_id, $status, $points, $available_points){
		$query = "INSERT INTO `".$this->rzvy_loyalty_points."` (`id`, `order_id`, `customer_id`, `status`, `points`, `available_points`) VALUES (NULL, '".$order_id."', '".$customer_id."', '".$status."', '".$points."', '".$available_points."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get available points customer */
	public function get_available_points_customer($customer_id){
		$query = "select `available_points` from `".$this->rzvy_loyalty_points."` where `customer_id` = '".$customer_id."' order by id DESC limit 0,1";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		if(isset($value['available_points']) && $value['available_points']>0){
			return $value['available_points'];
		}else{
			return 0;
		}
	}
	/* Function to get used points customer */
	public function get_used_points_customer($start, $end, $search, $column,$direction, $draw){
        $order_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `order_id` DESC';
		}else if($column == 1){
			$order_by_qry = 'order by `points` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `available_points` '.$direction;
		}else if($column == 4){
			$order_by_qry = 'order by `lastmodify` '.$direction;
		}else{
			$order_by_qry = 'order by `order_id` '.$direction;
		}	
	    $query = "select `order_id`, `points`, `available_points`, `lastmodify` from `".$this->rzvy_loyalty_points."` where `customer_id` = '".$this->customer_id."' and status='U' ".$order_by_qry." limit ".$start.", ".$end;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* Function to get used points customer order */
	public function get_used_points_customer_by_order_id($order_id){
		$query = "select  `points` from `".$this->rzvy_loyalty_points."` where `order_id` = '".$order_id."' and status='U' order by id DESC";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		if(isset($value['points']) && $value['points']>0){
			return $value['points'];
		}else{
			return 0;
		}
	}
	/* Function to get added points customer */
	public function get_added_points_customer($start, $end, $search, $column,$direction, $draw){
		if($draw == 1){
			$order_by_qry = 'order by `order_id` DESC';
		}else if($column == 1){
			$order_by_qry = 'order by `points` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `available_points` '.$direction;
		}else if($column == 4){
			$order_by_qry = 'order by `lastmodify` '.$direction;
		}else{
			$order_by_qry = 'order by `order_id` '.$direction;
		}	
	    $query = "select `order_id`, `points`, `available_points`, `lastmodify` from `".$this->rzvy_loyalty_points."` where `customer_id` = '".$this->customer_id."' and status='A' ".$order_by_qry." limit ".$start.", ".$end;
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to count all recieved loyalty points */
	public function count_all_recieved_loyalty_points($search){
		if($search != ''){
			$query = "select count(`id`) from `".$this->rzvy_loyalty_points."` where (`order_id` like '%".$search."%' or `points` like '%".$search."%') or `available_points` like '%".$search."%' or `lastmodify` like '%".$search."%') and `customer_id` = '".$this->customer_id."'  and status='A'";
		}else{
			$query = "select count(`id`) from `".$this->rzvy_loyalty_points."` where `customer_id` = '".$this->customer_id."' and status='A'";
		}
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		if(isset($value[0]) && $value[0]>0){
			return $value[0];
		}else{
			return 0;
		}
	}
	/* Function to count all used loyalty points */
	public function count_all_used_loyalty_points($search){
		if($search != ''){
			$query = "select count(`id`) from `".$this->rzvy_loyalty_points."` where (`order_id` like '%".$search."%' or `points` like '%".$search."%') or `available_points` like '%".$search."%' or `lastmodify` like '%".$search."%') and `customer_id` = '".$this->customer_id."'  and status='U'";
		}else{
			$query = "select count(`id`) from `".$this->rzvy_loyalty_points."` where `customer_id` = '".$this->customer_id."' and status='U'";
		}
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		if(isset($value[0]) && $value[0]>0){
			return $value[0];
		}else{
			return 0;
		}
	}
	/* Function to delete loyalty points */
	public function remove_loyalty_points($order_id,$customer_id){		
		$result = mysqli_query($this->conn,"delete from `".$this->rzvy_loyalty_points."` where `order_id`='".$order_id."' and `customer_id`='".$customer_id."'");
		return $result;
	}
}  
}  
?>