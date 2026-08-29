<?php 
if (!class_exists('rzvy_set_lang')){
class rzvy_set_lang{
	public $conn;
	public $id;
	public $option_name;
	public $option_value;
	public $rzvy_settings = 'rzvy_settings';
	
	/* Function to get option value from settings table */
	public function get_option($option_name){
		$query = "select `option_value` from `".$this->rzvy_settings."` where `option_name`='".$option_name."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_array($result);
			return $value['option_value'];
		}else{
			return "";
		}
	}
}
}