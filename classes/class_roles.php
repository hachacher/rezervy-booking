<?php 
if (!class_exists('rzvy_roles')){
class rzvy_roles{
	public $conn;
	public $id;
	public $role;
	public $staff;
	public $permission;
	public $status;
	public $rzvy_roles = 'rzvy_roles';
	
	/* Function to get all roles */
	public function get_all_roles(){
		$query = "select * from `".$this->rzvy_roles."`";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to add role */
	public function add_role(){
		$query = "INSERT INTO `".$this->rzvy_roles."`(`id`, `role`, `staff`, `permission`, `status`) VALUES (NULL,'".$this->role."','','a:0:{}','".$this->status."')";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}

	/* Function to change role status */
	public function change_role_status(){
		$query = "update `".$this->rzvy_roles."` set `status`='".$this->status."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}

	/* Function to update role permission */
	public function update_role_permission(){
		$query = "update `".$this->rzvy_roles."` set `permission`='".$this->permission."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to assign staff role */
	public function assign_staff_to_role(){
		$query = "update `".$this->rzvy_roles."` set `staff`='".$this->staff."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update role */
	public function update_role(){
		$query = "update `".$this->rzvy_roles."` set `role`='".$this->role."' where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to delete role */
	public function delete_role(){
		$query = "delete from `".$this->rzvy_roles."` where `id`='".$this->id."' and `staff`=''";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to read one role */
	public function readone_role(){
		$query = "select * from `".$this->rzvy_roles."` where `id`='".$this->id."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value;
	}
}
}
?>