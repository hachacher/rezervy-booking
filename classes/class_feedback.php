<?php 
if (!class_exists('rzvy_feedback')){
	class rzvy_feedback{
		public $conn;
		public $id;
		public $name;
		public $email;
		public $rating;
		public $review;
		public $review_datetime;
		public $status;
		public $rzvy_feedback = 'rzvy_feedback';
		public $rzvy_appointment_feedback = 'rzvy_appointment_feedback';
		public $rzvy_customer_orderinfo = 'rzvy_customer_orderinfo';
			
		/* Function to get all feedbacks */
		public function get_all_feedbacks(){
			$query = "select * from `".$this->rzvy_feedback."`";
			$result=mysqli_query($this->conn,$query);
			return $result;
		}
		/* Function to delete feedbacks */
		public function delete_feedback(){
			$query = "delete from `".$this->rzvy_feedback."` where `id`='".$this->id."'";
			$result=mysqli_query($this->conn,$query);
			return $result;
		}
		
		/* Function to change feedback status */
		public function change_feedback_status(){
			$query = "update `".$this->rzvy_feedback."` set `status`='".$this->status."' where `id`='".$this->id."'";
			$result=mysqli_query($this->conn,$query);
			return $result;
		}
		
		/** function to get appointment feedback ratings **/
		public function get_all_reviews($start, $end, $search, $column,$direction, $draw){
		$order_by_qry = '';
		if($draw == 1){
			$order_by_qry = 'order by `c`.`order_id` DESC';
		}else if($column == 0){
			$order_by_qry = 'order by `c`.`c_firstname` '.$direction;
		}else if($column == 1){
			$order_by_qry = 'order by `c`.`c_email` '.$direction;
		}else if($column == 2){
			$order_by_qry = 'order by `af`.`rating` '.$direction;
		}else if($column == 3){
			$order_by_qry = 'order by `af`.`review` '.$direction;
		}else{
			$order_by_qry = 'order by `af`.`review_datetime` '.$direction;
		}
		$group_by_qry = 'group by `af`.`order_id`';
		if($search != ''){
			$query = "select `c`.`c_firstname`, `c`.`c_lastname`, `c`.`c_email`, `af`.`rating`, `af`.`review`, `af`.`review_datetime`, `af`.`id` from `".$this->rzvy_appointment_feedback."` as `af`,`".$this->rzvy_customer_orderinfo."` as `c` where ((`c`.`c_firstname` like '%".$search."%' or `c`.`c_lastname` like '%".$search."%') or `c`.`c_email` like '%".$search."%' or `af`.`rating` like '%".$search."%' or `af`.`review` like '%".$search."%' or `af`.`review_datetime` like '%".$search."%') and `c`.`order_id` = `af`.`order_id`  ".$group_by_qry." ".$order_by_qry." limit ".$start.", ".$end;
		}else{
			$query = "select `c`.`c_firstname`, `c`.`c_lastname`,`c`.`c_email`, `af`.`rating`, `af`.`review`, `af`.`review_datetime`, `af`.`id` from `".$this->rzvy_appointment_feedback."` as `af`,`".$this->rzvy_customer_orderinfo."` as `c` where `c`.`order_id` = `af`.`order_id` ".$group_by_qry." ".$order_by_qry." limit ".$start.", ".$end;
		}
		
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	public function count_all_reviews($search){
		/*$group_by_qry = 'group by `b`.`order_id`';*/
		$group_by_qry = '';
		if($search != ''){
			$query = "select count(`af`.`order_id`) from `".$this->rzvy_appointment_feedback."` as `af`, `".$this->rzvy_customer_orderinfo."` as `c` where ((`c`.`c_firstname` like '%".$search."%' or `c`.`c_lastname` like '%".$search."%') or `c`.`c_email` like '%".$search."%' or `af`.`rating` like '%".$search."%' or `af`.`review` like '%".$search."%' or `af`.`review_datetime` like '%".$search."%') and `c`.`order_id` = `af`.`order_id` ".$group_by_qry;
		}else{
			$query = "select count(`af`.`order_id`) from `".$this->rzvy_appointment_feedback."` as `af`, `".$this->rzvy_customer_orderinfo."` as `c` where `af`.`order_id` = `c`.`order_id` ".$group_by_qry;
		}
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
    		$value=mysqli_fetch_array($result);
    		return $value[0];
		}else{
		    return 0;
		}
	}
	/* Function to delete feedbacks */
	public function delete_rating(){
			$query = "delete from `".$this->rzvy_appointment_feedback."` where `id`='".$this->id."'";
			$result=mysqli_query($this->conn,$query);
			return $result;
	}
  }
}
?>