<?php 
if (!class_exists('rzvy_login')){
class rzvy_login{
	public $conn;
	public $email;
	public $password;
	public $remember_me;
	public $rzvy_customers = "rzvy_customers";
	public $rzvy_admins = "rzvy_admins";
	public $rzvy_staff = "rzvy_staff";
	
	/* Function to check login details */
	public function login_process(){
		/* Check email address and password are correct or not in customers table */
		$query = "select * from `".$this->rzvy_customers."` where `email`='".$this->email."' and `password`='".md5($this->password)."' and `status`='Y'";
		$result=mysqli_query($this->conn,$query);
		
		/* To check user exist or not */
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_assoc($result);
		
			/* Set session values for logged in customer */
			unset($_SESSION['staff_id']);
			unset($_SESSION['admin_id']);
			$_SESSION['customer_id'] = $value['id'];
			$_SESSION['login_type'] = "customer";
			$_SESSION['role_permissions'] = array();
			
			/* Set cookie if remember me is checked */
			if($this->remember_me == "Y"){
				setcookie('rzvy_email',$this->email, time() + (86400 * 30), "/");
				setcookie('rzvy_password',$this->password, time() + (86400 * 30), "/");
				setcookie('rzvy_remember_me',"checked", time() + (86400 * 30), "/");
			}else{
				unset($_COOKIE['rzvy_email']);
				unset($_COOKIE['rzvy_password']);
				unset($_COOKIE['rzvy_remember_me']);
				setcookie('rzvy_email',null, -1, '/');
				setcookie('rzvy_password',null, -1, '/');
				setcookie('rzvy_remember_me',null, -1, '/');
			}
            echo "customer";
        }else{
			/* Check email address and password are correct or not in staff table */
            $query = "select * from `".$this->rzvy_staff."` where `email`='".$this->email."' and `password`='".md5($this->password)."' and `status`='Y'";
            $result=mysqli_query($this->conn,$query);
			
			/* To check staff exist or not */
			if(mysqli_num_rows($result)>0){
				$value=mysqli_fetch_assoc($result);
			
				/* Set session values for logged in user */
				unset($_SESSION['admin_id']);
				unset($_SESSION['customer_id']);
				$_SESSION['staff_id'] = $value['id'];
				$_SESSION['login_type'] = "staff";
				
				/* Roles */
				$Role_name = array();
				$roleArr = array();
				$roleRes=mysqli_query($this->conn,"SELECT * FROM `rzvy_roles` WHERE FIND_IN_SET('".$value['id']."', staff);");
				if(mysqli_num_rows($roleRes)>0){
					while($role = mysqli_fetch_assoc($roleRes)){
						array_push($Role_name, $role["role"]);
						$permission = unserialize($role["permission"]);
						if ($permission !== false) {
							foreach($permission as $prmsn => $prmsnval){
									$roleArr[$prmsn] = $prmsnval;
							}
						}
					}
					$_SESSION['role_permissions'] = $roleArr;
				}else{
					$_SESSION['role_permissions'] = $roleArr;
				}
				
				$_SESSION['staff_role_name'] = implode(", ", $Role_name);
				
				/* Set cookie if remember me is checked */
				if($this->remember_me == "Y"){
					setcookie('rzvy_email',$this->email, time() + (86400 * 30), "/");
					setcookie('rzvy_password',$this->password, time() + (86400 * 30), "/");
					setcookie('rzvy_remember_me',"checked", time() + (86400 * 30), "/");
				}else{
					unset($_COOKIE['rzvy_email']);
					unset($_COOKIE['rzvy_password']);
					unset($_COOKIE['rzvy_remember_me']);
					setcookie('rzvy_email',null, -1, '/');
					setcookie('rzvy_password',null, -1, '/');
					setcookie('rzvy_remember_me',null, -1, '/');
				}
				echo 'staff';
            }else{
				/* Check email address and password are correct or not in admins table */
				$hash = md5($this->password);
				$query = "select * from `".$this->rzvy_admins."` where `email`='".$this->email."' and `password`='".$hash."' and `status`='Y'";
				$result=mysqli_query($this->conn,$query);
				
				/* To check admin exist or not */
				if(mysqli_num_rows($result)>0){
					$value=mysqli_fetch_assoc($result);
				
					/* Set session values for logged in user */
					unset($_SESSION['staff_id']);
					unset($_SESSION['customer_id']);
					$_SESSION['admin_id'] = $value['id'];
					$_SESSION['login_type'] = "admin";
					$_SESSION['role_permissions'] = array();
					
					/* Set cookie if remember me is checked */
					if($this->remember_me == "Y"){
						setcookie('rzvy_email',$this->email, time() + (86400 * 30), "/");
						setcookie('rzvy_password',$this->password, time() + (86400 * 30), "/");
						setcookie('rzvy_remember_me',"checked", time() + (86400 * 30), "/");
					}else{
						unset($_COOKIE['rzvy_email']);
						unset($_COOKIE['rzvy_password']);
						unset($_COOKIE['rzvy_remember_me']);
						setcookie('rzvy_email',null, -1, '/');
						setcookie('rzvy_password',null, -1, '/');
						setcookie('rzvy_remember_me',null, -1, '/');
					}
					echo 'admin';
				}else{
					echo 'no';
				}
			}
        }
	}
	
	/*** Function to check existing email ***/
	public function check_email_exist(){
		/* Check email address correct or not in customers table */
		$query = "select * from `".$this->rzvy_customers."` where `email`='".$this->email."'";
		$result=mysqli_query($this->conn,$query);
		
		/* To check user exist or not */
		if(mysqli_num_rows($result)>0){
			return false;
        }else{
			/* Check email address correct or not in staff table */
            $query = "select * from `".$this->rzvy_staff."` where `email`='".$this->email."'";
            $result=mysqli_query($this->conn,$query);
			
			/* To check staff exist or not */
			if(mysqli_num_rows($result)>0){
				return false;
            }else{
				/* Check email address correct or not in admins table */
				$query = "select * from `".$this->rzvy_admins."` where `email`='".$this->email."'";
				$result=mysqli_query($this->conn,$query);
				
				/* To check admin exist or not */
				if(mysqli_num_rows($result)>0){
					return false;
				}else{
					return true;
				}
			}
        }
	}
	
	/*** Function to check existing email ***/
	public function existing_email_check(){
		/* Check email address correct or not in customers table */
		$query = "select * from `".$this->rzvy_customers."` where `email`='".$this->email."'";
		$result=mysqli_query($this->conn,$query);
		
		/* To check user exist or not */
		if(mysqli_num_rows($result)>0){
			return $result;
        }else{
			/* Check email address correct or not in staff table */
            $query = "select * from `".$this->rzvy_staff."` where `email`='".$this->email."'";
            $result=mysqli_query($this->conn,$query);
			
			/* To check staff exist or not */
			if(mysqli_num_rows($result)>0){
				return $result;
            }else{
				/* Check email address correct or not in admins table */
				$query = "select * from `".$this->rzvy_admins."` where `email`='".$this->email."'";
				$result=mysqli_query($this->conn,$query);
				return $result;
			}
        }
	}
	
	/*** Function to reset password ***/
	public function reset_password(){
		/* Check email address correct or not in customers table */
		$query = "select `id` from `".$this->rzvy_customers."` where `email`='".$this->email."'";
		$result=mysqli_query($this->conn,$query);
		
		/* To check user exist or not */
		if(mysqli_num_rows($result)>0){
			$res = mysqli_query($this->conn,"update `".$this->rzvy_customers."` set `password`='".$this->password."' where `email`='".$this->email."'");
			return $res;
        }else{
			/* Check email address correct or not in staff table */
            $query = "select `id` from `".$this->rzvy_staff."` where `email`='".$this->email."'";
            $result=mysqli_query($this->conn,$query);
			
			/* To check staff exist or not */
			if(mysqli_num_rows($result)>0){
				$res = mysqli_query($this->conn,"update `".$this->rzvy_staff."` set `password`='".$this->password."' where `email`='".$this->email."'");
				return $res;
            }else{
				/* Check email address correct or not in admins table */
				$query = "select `id` from `".$this->rzvy_admins."` where `email`='".$this->email."'";
				$result=mysqli_query($this->conn,$query);
				
				/* To check admin exist or not */
				if(mysqli_num_rows($result)>0){
					$res = mysqli_query($this->conn,"update `".$this->rzvy_admins."` set `password`='".$this->password."' where `email`='".$this->email."'");
					return $res;
				}else{
					return false;
				}
			}
        }
	}
}
}
?>