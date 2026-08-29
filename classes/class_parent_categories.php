<?php 
if (!class_exists('rzvy_parent_categories')){
class rzvy_parent_categories{
	public $conn;
	public $id;
	public $cat_name;
	public $status;
	public $image;
	public $position=0;
	public $linked_subcat = "";
	public $rzvy_categories = 'rzvy_categories';
	public $rzvy_parent_categories = 'rzvy_parent_categories';
	public $rzvy_bookings = 'rzvy_bookings';
	
	/* Function to add categories */
	public function add_category(){
		$query = "INSERT INTO `".$this->rzvy_parent_categories."` (`id`, `cat_name`, `status`, `image`, `position`, `linked_subcat`) VALUES (NULL, '".$this->cat_name."', '".$this->status."', '".$this->image."', '".$this->position."', '".$this->linked_subcat."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	/* Function to get all categories */
	public function get_all_subcategories(){
		$query = "select * from `".$this->rzvy_categories."` ORDER BY `position` ASC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all categories */
	public function get_all_parent_categories(){
		$query = "select * from `".$this->rzvy_parent_categories."` ORDER BY `position` ASC";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get all categories name */
	public function get_all_parent_categories_name(){
		$query = "select `id`, `cat_name` from `".$this->rzvy_parent_categories."`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to change category status */
	public function change_category_status(){
		$query = "update `".$this->rzvy_parent_categories."` set `status`='".$this->status."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update category */
	public function update_category(){
		$query = "update `".$this->rzvy_parent_categories."` set `cat_name`='".$this->cat_name."', `image`='".$this->image."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update category */
	public function assign_subcategory($id, $linked_subcat){
		$query = "update `".$this->rzvy_parent_categories."` set `linked_subcat`='".$linked_subcat."' where `id`='".$id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update category */
	public function link_subcategory($id, $scid){
		$query = "select * from `".$this->rzvy_parent_categories."` where `id`='".$id."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_assoc($result);
			$linked_subcat = array();
			if($value['linked_subcat'] != ""){
				$linked_subcat = explode(",",$value['linked_subcat']);
			}
			array_push($linked_subcat,$scid);
			$new_linked_subcat = implode(",",$linked_subcat);
			$qry = "update `".$this->rzvy_parent_categories."` set `linked_subcat`='".$new_linked_subcat."' where `id`='".$id."'";
			return mysqli_query($this->conn,$qry);
		}
	}
	
	/* Function to delete category */
	public function delete_category(){
		$query = "delete from `".$this->rzvy_parent_categories."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to get one categories name */
	public function readone_category_name(){
		$query = "select `cat_name` from `".$this->rzvy_parent_categories."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value['cat_name'];
	}
	
	/* Function to read one category */
	public function readone_category(){
		$query = "select * from `".$this->rzvy_parent_categories."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_assoc($result);
		return $value;
	}
	
	/* Function to update category position */
	public function update_category_position($id, $position){
		$query = "update `".$this->rzvy_parent_categories."` set `position`='".$position."' where `id`='".$id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
}
}
?>