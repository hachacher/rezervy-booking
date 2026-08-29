<?php 
if (!class_exists('rzvy_addons')){
class rzvy_addons{
	public $conn;
	public $id;
	public $category_id;
	public $service_id = 0;
	public $title;
	public $rate;
	public $image;
	public $multiple_qty;
	public $status;
	public $description;
	public $max_limit;
	public $min_limit;
	public $position = 0;
	public $duration = 0;
	public $badge;
	public $badge_text;
	public $rzvy_addons = 'rzvy_addons';
	public $rzvy_categories = 'rzvy_categories';
	public $rzvy_services = 'rzvy_services';
	public $rzvy_bookings = 'rzvy_bookings';
	public $rzvy_service_addons = 'rzvy_service_addons';
	
	/* Function to get all categories */
	public function get_all_categories_addons(){
		$query = "select `c`.`id`, `c`.`cat_name` 
		from `".$this->rzvy_categories."` as `c`, 
		`".$this->rzvy_services."` as `s`
		where 
		`c`.`status` = 'Y'
		and `s`.`status` = 'Y' 
		and `s`.`cat_id` = `c`.`id` 
		group by `c`.`id`, `c`.`cat_name` ORDER BY `c`.`id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* Function to get services by category id */
	public function get_services_by_cat_id_addons(){
		$query = "select * from `".$this->rzvy_services."` where `cat_id`='".$this->category_id."' and `status` = 'Y' ORDER BY `id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	/* Function to get addon name */
	public function get_addon_name(){
		$query = "select `title` from `".$this->rzvy_addons."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['title'];
	}
	
	/* Function to get addon detail */
	public function readone_addon(){
		$query = "select * from `".$this->rzvy_addons."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value;
	}
	
	/* Function to get all addons title */
	public function get_all_addons_title(){
		$query = "SELECT `id`, `title` FROM `".$this->rzvy_addons."`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all addons according service id selection */
	public function get_all_addons(){
		$query = "SELECT * FROM `".$this->rzvy_addons."`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all addons according service id selection */
	public function get_all_addons_according_service_id(){
		$query = "select `a`.`id`, `a`.`title` 
		from 
		`".$this->rzvy_addons."` as `a`,
		`".$this->rzvy_service_addons."` as `sa`
		where 
		`sa`.`service_id`='".$this->service_id."' 
		and `a`.`id` = `sa`.`addon_id`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to count all addons according service id */
	public function count_all_addons_by_service_id(){
		$query = "select count(`id`) from `".$this->rzvy_addons."` where `service_id`='".$this->service_id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value[0];
	}
	
	/* Function to change addons status */
	public function change_addon_status(){
		$query = "update `".$this->rzvy_addons."` set `status` = '".$this->status."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to change addons multiple qty status */
	public function change_addon_multiple_qty_status(){
		$query = "update `".$this->rzvy_addons."` set `multiple_qty` = '".$this->multiple_qty."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update addons */
	public function update_addon(){
		$query = "UPDATE `".$this->rzvy_addons."` SET `title` = '".$this->title."', `rate` = '".$this->rate."', `description` = '".$this->description."', `image` = '".$this->image."', `max_limit` = '".$this->max_limit."', `min_limit` = '".$this->min_limit."', `duration` = '".$this->duration."', `badge` = '".$this->badge."', `badge_text` = '".$this->badge_text."' WHERE `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to add addons */
	public function add_addon(){
		$query = "INSERT INTO `".$this->rzvy_addons."`(`id`, `service_id`, `title`, `rate`, `image`, `multiple_qty`, `status`, `description`, `max_limit`, `min_limit`, `duration`, `badge`, `badge_text`) VALUES (NULL, '".$this->service_id."', '".$this->title."', '".$this->rate."', '".$this->image."', '".$this->multiple_qty."', '".$this->status."', '".$this->description."', '".$this->max_limit."', '".$this->min_limit."', '".$this->duration."', '".$this->badge."', '".$this->badge_text."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to delete addons */
	public function delete_addon(){
		$query = "delete from `".$this->rzvy_addons."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to check appointments before delete addons */
	public function check_appointments_before_delete_addon(){
		$query = "select `addons` from `".$this->rzvy_bookings."`";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			while($value=mysqli_fetch_assoc($result)){
				$unserialized_addons = unserialize($value['addons']);
				foreach($unserialized_addons as $addon){
					if($this->id == $addon['id']){
						return "appointmentexist";
					}
				}
			}
			return "noappointmentexist";
		}else{
			return "noappointmentexist";
		}
	}
	
	/* Function to get all services title */
	public function get_ser_title($id){
		$query = "select `title` from `".$this->rzvy_services."` where `id`='".$id."'";
		$result=mysqli_query($this->conn,$query);
		$val=mysqli_fetch_array($result);
		return ucwords($val['title']);
	}
	
	
	/* Function to get all active services */
	public function get_all_active_services(){
		$query = "select * from `".$this->rzvy_services."` where `status`='Y' order by `id` DESC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all linked addons */
	public function get_linked_services($addon_id, $service_id){
		$query = "select * from `".$this->rzvy_service_addons."` where `addon_id` = '".$addon_id."' and `service_id` = '".$service_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}

	/** function to unlink all staff for selected service */
	public function unlink_all_services_for_selected_addon(){
		$query = "delete from `".$this->rzvy_service_addons."` where `addon_id` = '".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}

	public function link_service_to_selected_addon($service_id){
		$query = "INSERT INTO `".$this->rzvy_service_addons."`(`id`, `addon_id`, `service_id`, `position`) VALUES (NULL, '".$this->id."', '".$service_id."', '".$this->position."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get addons by service id */
	public function get_all_addons_by_service_id(){
		$query = "select `a`.* 
		from 
		`".$this->rzvy_addons."` as `a`,
		`".$this->rzvy_service_addons."` as `sa`
		where 
		`sa`.`service_id`='".$this->service_id."' 
		and `a`.`id` = `sa`.`addon_id`
		and `a`.`status` = 'Y' 
		ORDER BY `sa`.`position` ASC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	public function update_addon_position($service_id, $addon_id, $position){
		$query = "UPDATE `".$this->rzvy_service_addons."` SET `position` = '".$position."' where `service_id` = '".$service_id."' and `addon_id` = '".$addon_id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}
}
?>