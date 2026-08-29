<?php 
if (!class_exists('rzvy_slots')){
class rzvy_slots{
	public $conn;
	public $staff_id;
	public $is_advance_schedule = "N";
	public $advance_scheduleid = 0;
	public $rzvy_schedule = 'rzvy_schedule';
	public $rzvy_bookings = 'rzvy_bookings';
	public $rzvy_block_off = 'rzvy_block_off';
	public $rzvy_services = 'rzvy_services';
	public $rzvy_staff_daysoff = 'rzvy_staff_daysoff';
	public $rzvy_staff_schedule = 'rzvy_staff_schedule';
	public $rzvy_staff_breaks = 'rzvy_staff_breaks';
	public $rzvy_breaks = 'rzvy_breaks';
	public $rzvy_settings = 'rzvy_settings';
	public $rzvy_staff_advance_schedule_breaks = 'rzvy_staff_advance_schedule_breaks';
	public $rzvy_staff_advance_schedule = 'rzvy_staff_advance_schedule';
	public $rzvy_staff_settings = 'rzvy_staff_settings';
	public $rzvy_staff = 'rzvy_staff';
	
	/* Function to get already booked slots */
	public function get_already_booked_slots($selected_date,$cur_time_interval,$service_id=0, $service_padding_before, $service_padding_after){
		$return_arr = array();
		/* if($service_id!='0' && is_numeric($service_id)){
			$query="select `order_id`, `booking_datetime`, `booking_end_datetime` from `".$this->rzvy_bookings."` where CAST(`booking_datetime` as date)='".$selected_date."' and (`booking_status`='pending' OR `booking_status`='confirmed' OR `booking_status`='confirmed_by_staff' OR `booking_status`='rescheduled_by_you' OR `booking_status`='rescheduled_by_customer' OR `booking_status`='rescheduled_by_staff') and service_id='".$service_id."' group by `order_id`,`booking_datetime`, `booking_end_datetime`";
		}else{ */
		if($this->staff_id!='0' && is_numeric($this->staff_id)){
			$query="select `order_id`, `booking_datetime`, `booking_end_datetime` from `".$this->rzvy_bookings."` where CAST(`booking_datetime` as date)='".$selected_date."' and (`booking_status`='pending' OR `booking_status`='confirmed' OR `booking_status`='confirmed_by_staff' OR `booking_status`='rescheduled_by_you' OR `booking_status`='rescheduled_by_customer' OR `booking_status`='rescheduled_by_staff' OR `booking_status`='completed') and `staff_id`='".$this->staff_id."' group by `order_id`,`booking_datetime`, `booking_end_datetime`";
		}else{
			$query="select `order_id`, `booking_datetime`, `booking_end_datetime` from `".$this->rzvy_bookings."` where CAST(`booking_datetime` as date)='".$selected_date."' and (`booking_status`='pending' OR `booking_status`='confirmed' OR `booking_status`='confirmed_by_staff' OR `booking_status`='rescheduled_by_you' OR `booking_status`='rescheduled_by_customer' OR `booking_status`='rescheduled_by_staff' OR `booking_status`='completed') group by `order_id`, `booking_datetime`, `booking_end_datetime`";
		}
		/* } */
		$value=mysqli_query($this->conn,$query);
		while($row=mysqli_fetch_array($value)){
			$newarr = array();
			if ($service_padding_before>0) {
				$newarr["start_time"] = strtotime("-$service_padding_before minutes", strtotime($row['booking_datetime']));	
			}else{
				$newarr["start_time"] = strtotime($row['booking_datetime']);
			}
			
			if ($service_padding_after>0) {
				$newarr["end_time"] = strtotime("+$service_padding_after minutes", strtotime($row['booking_end_datetime']));
			}else{
				$newarr["end_time"] = strtotime($row['booking_end_datetime']);
			}
			array_push($return_arr, $newarr);
		}
		return $return_arr;
	}
	
	/* Function to get block off */
	public function get_block_off($selected_date){
		$return_arr = array();
		if($this->staff_id!='0' && is_numeric($this->staff_id)){
			/** staff days off process */
			$query="select * from `".$this->rzvy_staff_daysoff."` where `off_date` = '".$selected_date."' and `staff_id`='".$this->staff_id."'";
			$value=mysqli_query($this->conn,$query);
			if(mysqli_num_rows($value)>0){
				$arr = array();
				$arr["start_time"] = "00:00:00";
				$arr["end_time"] = "23:59:59";
				array_push($return_arr, $arr);
			}
			if($this->is_advance_schedule == "Y"){
				/** staff breaks process */
				$query="select * from `".$this->rzvy_staff_advance_schedule_breaks."` where ('".$selected_date."' between `startdate` and `enddate`) and `schedule_id`='".$this->advance_scheduleid."' and `staff_id`='".$this->staff_id."'";
				$value=mysqli_query($this->conn,$query);
				if(mysqli_num_rows($value)>0){
					while($row=mysqli_fetch_array($value)){
						$arr = array();
						$arr["start_time"] = $row['break_start'];
						$arr["end_time"] = $row['break_end'];
						array_push($return_arr, $arr);
					}
				}				
			}else{
				/** staff breaks process */
				$day_of_the_week = date("N", strtotime($selected_date));
				$query="select * from `".$this->rzvy_staff_breaks."` where `weekday_id` = '".$day_of_the_week."' and `staff_id`='".$this->staff_id."'";
				$value=mysqli_query($this->conn,$query);
				if(mysqli_num_rows($value)>0){
					while($row=mysqli_fetch_array($value)){
						$arr = array();
						$arr["start_time"] = $row['break_start'];
						$arr["end_time"] = $row['break_end'];
						array_push($return_arr, $arr);
					}
				}
			}
			
			$query="select * from `".$this->rzvy_breaks."` where `break_date` = '".$selected_date."' and staff_id = '".$this->staff_id."'";
			$value=mysqli_query($this->conn,$query);
			if($value && mysqli_num_rows($value)>0){
				while($row=mysqli_fetch_array($value)){
					$arr = array();
					$arr["start_time"] = $row['starttime'];
					$arr["end_time"] = $row['endtime'];
					array_push($return_arr, $arr);
				}
			}
		}else{
			$query="select * from `".$this->rzvy_breaks."` where `break_date` = '".$selected_date."' and staff_id = '0'";
			$value=mysqli_query($this->conn,$query);
			if($value && mysqli_num_rows($value)>0){
				while($row=mysqli_fetch_array($value)){
					$arr = array();
					$arr["start_time"] = $row['starttime'];
					$arr["end_time"] = $row['endtime'];
					array_push($return_arr, $arr);
				}
			}
		}
		
		$query="select * from `".$this->rzvy_block_off."` where '".$selected_date."' between `from_date` and `to_date` and `status`='Y'";
		$value=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($value)>0){
			while($row=mysqli_fetch_array($value)){
			    if ($row['blockoff_type'] == "fullday") {
					$arr = array();
					$arr["start_time"] = "00:00:00";
					$arr["end_time"] = "23:59:59";
				}else{
					$arr = array();
					$arr["start_time"] = $row['from_time'];
					$arr["end_time"] = $row['to_time'];
				}
				array_push($return_arr, $arr);
			}
		}
		return $return_arr;
	}
	
	/* Function to get day start time and day end time */
	public function get_time_slots($day_id, $week_id ,$service_id){
		$dayid=$day_id;
		$weekid=$week_id;
        $results = array();
		/** staff schedule */
		if($this->staff_id!='0' && is_numeric($this->staff_id)){
			if($this->is_advance_schedule == "Y"){
				$query="SELECT `starttime`,`endtime`,`no_of_booking` FROM `".$this->rzvy_staff_advance_schedule."` WHERE `id`='".$this->advance_scheduleid."' AND `staff_id`='".$this->staff_id."'";
			}else{
				$query="SELECT `starttime`,`endtime`,`no_of_booking` FROM `".$this->rzvy_staff_schedule."` WHERE `weekday_id`='" .$dayid . "' AND `offday`='N' AND `week_id`='".$weekid."' AND  `staff_id`='".$this->staff_id."'";
			}
			$result=mysqli_query($this->conn,$query);
		}
		/*** default schedule */
		else{
			if($service_id!='0' && is_numeric($service_id)){
				$qry="SELECT `starttime`,`endtime`,`no_of_booking` FROM `".$this->rzvy_schedule."` WHERE `weekday_id`='" .$dayid . "' AND `offday`='N' AND `week_id`='".$weekid."' AND  `service_id`='".$service_id."'";
				$result=mysqli_query($this->conn,$qry);
				/* $res=mysqli_query($this->conn,$qry);
				if(mysqli_num_rows($res)>0){
					$result=$res;
				}else{
					$query="SELECT `starttime`,`endtime`,`no_of_booking` FROM `".$this->rzvy_schedule."` WHERE `weekday_id`='" .$dayid . "' AND `offday`='N' AND `week_id`='".$weekid."' AND `service_id`='default'";
					$result=mysqli_query($this->conn,$query);
				} */
			}else{
				$query="SELECT `starttime`,`endtime`,`no_of_booking` FROM `".$this->rzvy_schedule."` WHERE `weekday_id`='" .$dayid . "' AND `offday`='N' AND `week_id`='".$weekid."' AND `service_id`='default'";
				$result=mysqli_query($this->conn,$query);
			}
		}
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_row($result);
			$results['daystart_time'] = $value[0];
			$results['dayend_time']   = $value[1];
			$results['no_booking']   = $value[2];
		}else{
			$value=mysqli_fetch_row($result);
			$results['daystart_time'] = "00:00:01";
			$results['dayend_time']   = "00:00:02";
			$results['no_booking']   = "0";
		}
        return $results;	
    }
	
	/* Function to get service time interval */
    public function get_service_time_interval($service_id, $time_interval){
		$ser_value=mysqli_query($this->conn, "select * from `".$this->rzvy_services."` where `id`='".$service_id."'");
		if(mysqli_num_rows($ser_value)>0){
			$ser_row=mysqli_fetch_array($ser_value);
			$time_interval=$ser_row['duration'];
		}
		return $time_interval;
    }
	
	/* Function to Slot Bookings */
	public function get_slot_bookings($slot,$service_id){
		if($this->staff_id!='0' && is_numeric($this->staff_id)){
			$staff_qry = " AND `staff_id`='".$this->staff_id."'";
		}else{
			$staff_qry = "";
		}
		/*if($service_id!='0' && is_numeric($service_id)){
			$query="SELECT `id` FROM `".$this->rzvy_bookings."` WHERE `booking_datetime` <= '" .$slot . "' AND `booking_end_datetime` > '".$slot."' and (`booking_status`='pending' OR `booking_status`='confirmed' OR `booking_status`='confirmed_by_staff' OR `booking_status`='rescheduled_by_you' OR `booking_status`='rescheduled_by_customer' OR `booking_status`='rescheduled_by_staff' OR `booking_status`='completed') AND `service_id`='".$service_id."'".$staff_qry;
		}else{*/
			$query="SELECT `id` FROM `".$this->rzvy_bookings."` WHERE `booking_datetime` <= '" .$slot . "' AND `booking_end_datetime` > '".$slot."' and (`booking_status`='pending' OR `booking_status`='confirmed' OR `booking_status`='confirmed_by_staff' OR `booking_status`='rescheduled_by_you' OR `booking_status`='rescheduled_by_customer' OR `booking_status`='rescheduled_by_staff' OR `booking_status`='completed')".$staff_qry;
		/*}*/
		
		$result=mysqli_query($this->conn,$query);
		$count=mysqli_num_rows($result);
		return $count;
    }
	
	/* Function to check staff advance schedule */
	public function check_is_advance_schedule($start_date, $staff_id){
		$query = "select * from `".$this->rzvy_staff_advance_schedule."` where ('".$start_date."' between `startdate` and `enddate`) and `staff_id`='".$staff_id."' and `status`='Y' order by `id` desc";
		$result=mysqli_query($this->conn, $query);
		if(mysqli_num_rows($result)>0){
			$row=mysqli_fetch_array($result);
			$this->advance_scheduleid = $row['id'];
			$this->is_advance_schedule = "Y";
		}else{
			$this->advance_scheduleid = 0;
			$this->is_advance_schedule = "N";
		}
	}
	
	/* Function to generate slot dropdown options */
    public function generate_available_slots_dropdown($time_interval, $rzvy_time_format, $start_date, $advance_bookingtime, $currDateTime_withTZ, $isEndTime = false, $service_id = 0, $addon_duration = 0){
		if(is_numeric($service_id) && $service_id != "0"){
			$ser_value=mysqli_query($this->conn, "select * from `".$this->rzvy_services."` where `id`='".$service_id."'");
			if(mysqli_num_rows($ser_value)>0){
				$ser_row=mysqli_fetch_array($ser_value);
				if($addon_duration>0){
					$time_interval=($ser_row['duration']+$addon_duration);
				}else{
					$time_interval=$ser_row['duration'];
				}
				$service_padding_before=$ser_row['padding_before'];
				$service_padding_after=$ser_row['padding_after'];
			}else{
				$service_padding_before=0;
				$service_padding_after=0;
			}
		}else{ 
			$service_padding_before=0;
			$service_padding_after=0;
        }
        $new_serv_time_interval = $time_interval;
		$day_slots = array();
        $week_id = 1;
    
		/* if calendar starting date is missing then it will take starting date to current date */
        if ($start_date == '') {
            $day_id = date('N', $currDateTime_withTZ);
			/*  add Date as heading of the day column */
            $day_slots['date'] = date('Y-m-d', $currDateTime_withTZ);
        } else {
            $day_id = date('N', strtotime($start_date));
			/* add Date as heading of the day column */
            $day_slots['date'] =date('Y-m-d', strtotime($start_date));
        }
        
		/*************************************STAFF advance schedule start****************************************/
		if($this->staff_id!='0' && is_numeric($this->staff_id)){
			$this->check_is_advance_schedule($start_date, $this->staff_id);
		}
		/*************************************STAFF advance schedule enddd****************************************/
		
        $available_slots = $this->get_time_slots($day_id, $week_id, $service_id);
		$day_slots['no_booking'] = $available_slots['no_booking'];
		
		/* calculating starting and end time of day into mintues */				
		if(isset($available_slots['daystart_time'],$available_slots['dayend_time'])){		
			$min_day_start_time        = (date('G', strtotime($available_slots['daystart_time'])) * 60) + date('i',strtotime($available_slots['daystart_time']));
			$min_day_end_time          = (date('G', strtotime($available_slots['dayend_time'])) * 60) + date('i',strtotime($available_slots['dayend_time']));
			
			$min_allow_advance='Y';
			$advance_minutes='N';
			if($advance_bookingtime>=1440){
				$advance_minutes='Y';
				$currdatestr = strtotime(date('Y-m-d H:i:s', $currDateTime_withTZ));					
				$withadncebooktime = strtotime("+$advance_bookingtime minutes", $currdatestr);
				$withadncebookdate = date('Y-m-d',strtotime("+$advance_bookingtime minutes", $currdatestr));
				$daystarttimeofdate = strtotime(date($withadncebookdate.' '.$available_slots['daystart_time']));
				$with_advance_time = date('H:i:s',$withadncebooktime);
				
				if(strtotime($start_date)>strtotime($withadncebookdate)){
					$with_advance_time = $available_slots['daystart_time'];
				}
				
				if(strtotime($start_date)>=strtotime($withadncebookdate)){
					if($withadncebooktime<$daystarttimeofdate){
						$min_day_start_time = (date('G', strtotime($available_slots['daystart_time'])) * 60) + date('i',strtotime($available_slots['daystart_time']));								
							$min_allow_advance='Y';					
					}else{
					
						$min_day_start_time = (date('G', strtotime($with_advance_time)) * 60) + date('i',strtotime($with_advance_time));						
						if($min_day_start_time%$time_interval!=0){
							$extraminsadd =  $time_interval-($min_day_start_time%$time_interval);
							$min_day_start_time = $min_day_start_time+$extraminsadd;
						}
					
						$min_allow_advance='Y';
					}
				}else{
					$min_allow_advance='N';
				}
			}
			
			$starting_min = $min_day_start_time;
			
			/* Adding Service Before Padding Time For First Slot */
			if ($service_padding_before>0) {
				  $starting_min =  $starting_min+$service_padding_before;
			} 
			
			/* check if selected date is today  if yes calculate current time's min to avoid past booking */
			$today                     = false;
			$conditional_min_mins      = 0;
			
			if (strtotime($day_slots['date']) == strtotime(date('Y-m-d', $currDateTime_withTZ)) && $advance_minutes=='N') {
				$today                = true;
				/* total mins of current time */
			   $conditional_min_mins = date('G',strtotime(date('Y-m-d H:i:s', $currDateTime_withTZ))) * 60 + date('i',strtotime(date('Y-m-d H:i:s', $currDateTime_withTZ))) ;
			} else {
				$today = false;
			}
		   
		   /* add minimum advance booking mins with starting mins for slots */
			 if($advance_bookingtime<1440){
					$conditional_min_mins += $advance_bookingtime;
			}
			
					
			/* check already booked timeslots */
			$day_slots['booked'] = $this->get_already_booked_slots($start_date,$time_interval,$service_id,$service_padding_before,$service_padding_after);
			/* $day_slots['block_off'] = $this->get_block_off($start_date); */
			$day_slots['block_off'] = array();
			$day_slots['serviceaddonduration'] = $time_interval;
			$day_slots['timeinterval'] = $time_interval;
			/* Converting time into slots based on given daystart time and dayend time */
			if ($available_slots['daystart_time'] != '' && $available_slots['dayend_time'] != '' && $min_allow_advance=='Y') {
				$new_time_interval = $time_interval;
				$isEqualTo = "N";
				$get_timeslots_display_method = $this->get_option("rzvy_timeslots_display_method");
				if($get_timeslots_display_method == "T"){
					$get_timeslot_interval = $this->get_option("rzvy_timeslot_interval");
					if($get_timeslot_interval>0){
						$new_time_interval = $get_timeslot_interval;
						$new_available_slots_dayend_time = date("H:i:s", strtotime('-'.$time_interval.' minutes', strtotime($available_slots['dayend_time'])));
						$min_day_end_time = (date('G', strtotime($new_available_slots_dayend_time)) * 60) + date('i',strtotime($new_available_slots_dayend_time));
						$isEqualTo = "Y";
					}
				}
				$day_slots['timeinterval'] = $new_time_interval;
				$day_slots['serv_timeinterval'] = $new_serv_time_interval;
				if($isEndTime || $isEqualTo == "Y"){
					while ($starting_min <= $min_day_end_time) {
						if ($today) {
							if ($starting_min > $conditional_min_mins) {						
								$day_slots['slots'][] = date('G:i:s', mktime(0, $starting_min, 0, 1, 1, date('Y')));
							}
						} else {
							$day_slots['slots'][] = date('G:i:s', mktime(0, $starting_min, 0, 1, 1, date('Y')));
						}
						$starting_min = $starting_min + $new_time_interval;
					}
				}else{
					while ($starting_min < $min_day_end_time) {
						if ($today) {
							if ($starting_min > $conditional_min_mins) {						
								$day_slots['slots'][] = date('G:i:s', mktime(0, $starting_min, 0, 1, 1, date('Y')));
							}
						} else {
							$day_slots['slots'][] = date('G:i:s', mktime(0, $starting_min, 0, 1, 1, date('Y')));
						}
						$starting_min = $starting_min + $new_time_interval;
					}
				}
			} else {
				$day_slots['slots'] = array();
			}
		}
        return $day_slots;		
		
    }
	
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
	
	/* Function to get block off */
	public function isDayOffCheck($selected_date, $service_id, $staff_id){
		/*************************************STAFF advance schedule start****************************************/
		if($staff_id!='0' && is_numeric($staff_id)){
			$this->check_is_advance_schedule($selected_date, $staff_id);
		}
		/*************************************STAFF advance schedule enddd****************************************/

		if($staff_id!='0' && is_numeric($staff_id)){
			/** staff days off process */
			$query="select * from `".$this->rzvy_staff_daysoff."` where `off_date` = '".$selected_date."' and `staff_id`='".$staff_id."'";
			$result=mysqli_query($this->conn,$query);
			if(mysqli_num_rows($result)>0){
				return true;
			}
		}
		
		$query="select * from `".$this->rzvy_block_off."` where '".$selected_date."' between `from_date` and `to_date` and `status`='Y' and `blockoff_type`='fullday'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			return true;
		}
		
		$weekid = "1";
		$dayid = date('N', strtotime($selected_date));
		/** staff schedule */
		if($staff_id!='0' && is_numeric($staff_id) && $this->is_advance_schedule != "Y"){
			$query="SELECT `starttime`,`endtime`,`no_of_booking` FROM `".$this->rzvy_staff_schedule."` WHERE `weekday_id`='" .$dayid . "' AND `offday`='Y' AND `week_id`='".$weekid."' AND  `staff_id`='".$staff_id."'";
			$result=mysqli_query($this->conn,$query);
			if(mysqli_num_rows($result)>0){
				return true;
			}
		}
		/*** default schedule */
		else{
			if($service_id!='0' && is_numeric($service_id)){
				$qry="SELECT `starttime`,`endtime`,`no_of_booking` FROM `".$this->rzvy_schedule."` WHERE `weekday_id`='" .$dayid . "' AND `offday`='Y' AND `week_id`='".$weekid."' AND  `service_id`='".$service_id."'";
				$result=mysqli_query($this->conn,$qry);
				if(mysqli_num_rows($result)>0){
					return true;
				}
			}else{
				$query="SELECT `starttime`,`endtime`,`no_of_booking` FROM `".$this->rzvy_schedule."` WHERE `weekday_id`='" .$dayid . "' AND `offday`='Y' AND `week_id`='".$weekid."' AND `service_id`='default'";
				$result=mysqli_query($this->conn,$query);
				if(mysqli_num_rows($result)>0){
					return true;
				}
			}
		}
		return false;
	}
	
	/* Function to get block off */
	public function fetch_addon_duration($addons){
		$addon_duration = 0;
		$unserialized_addons = unserialize($addons);
		foreach($unserialized_addons as $addon){
			if(isset($addon['duration'])){
				$addon_duration = ($addon_duration+$addon['duration']);
			}
		}
		return $addon_duration;
	}
	
	/* Function to check_staff_available_on_new_datetime */
	public function check_staff_available_on_new_datetime($staff_id, $start_datetime, $end_datetime, $service_id){
		$selected_date = date("Y-m-d", strtotime($start_datetime));
		$checkIfServiceAssigned = mysqli_query($this->conn, "SELECT * FROM `rzvy_staff_services` where `service_id`='".$service_id."' and `staff_id`='".$staff_id."'");
		if(mysqli_num_rows($checkIfServiceAssigned)==0){
			return false;
		}else{
			if($staff_id!='0' && is_numeric($staff_id)){
				$checkIfStaffBooked=mysqli_query($this->conn, "select `order_id`, `booking_datetime`, `booking_end_datetime` from `".$this->rzvy_bookings."` where CAST(`booking_datetime` as date)='".$selected_date."' and (`booking_status`='pending' OR `booking_status`='confirmed' OR `booking_status`='confirmed_by_staff' OR `booking_status`='rescheduled_by_you' OR `booking_status`='rescheduled_by_customer' OR `booking_status`='rescheduled_by_staff' OR `booking_status`='completed') and `staff_id`='".$staff_id."' group by `order_id`,`booking_datetime`, `booking_end_datetime`");
			}else{
				$checkIfStaffBooked=mysqli_query($this->conn, "select `order_id`, `booking_datetime`, `booking_end_datetime` from `".$this->rzvy_bookings."` where CAST(`booking_datetime` as date)='".$selected_date."' and (`booking_status`='pending' OR `booking_status`='confirmed' OR `booking_status`='confirmed_by_staff' OR `booking_status`='rescheduled_by_you' OR `booking_status`='rescheduled_by_customer' OR `booking_status`='rescheduled_by_staff' OR `booking_status`='completed') group by `order_id`, `booking_datetime`, `booking_end_datetime`");
			}
			if(mysqli_num_rows($checkIfStaffBooked)==0){
				return false;
			}else{
				$weekid = "1";
				$dayid = date('N', strtotime($selected_date));
				$starttime = date('H:i:s', strtotime($start_datetime));
				$endtime = date('H:i:s', strtotime($end_datetime));
				
				if($staff_id!='0' && is_numeric($staff_id)){
					$query="select * from `".$this->rzvy_breaks."` where `break_date` = '".$selected_date."' and staff_id = '".$staff_id."' and ((`starttime` between '".$starttime."' and '".$endtime."') or (`endtime` between '".$starttime."' and '".$endtime."'))";
				}else{
					$query="select * from `".$this->rzvy_breaks."` where `break_date` = '".$selected_date."' and staff_id = '0' and ((`starttime` between '".$starttime."' and '".$endtime."') or (`endtime` between '".$starttime."' and '".$endtime."'))";
				}
				$value=mysqli_query($this->conn,$query);
				if($value && mysqli_num_rows($value)>0){
					return false;
				}else{
					$this->check_is_advance_schedule($selected_date, $staff_id);
					if($this->is_advance_schedule == "Y"){
						$query="SELECT `starttime`,`endtime`,`no_of_booking` FROM `".$this->rzvy_staff_advance_schedule."` WHERE `id`='".$this->advance_scheduleid."' AND `staff_id`='".$staff_id."' and (('".$starttime."' between `starttime` and `endtime`) or '".$endtime."' between `starttime` and `endtime`)";
					}else{
						$query="SELECT `starttime`,`endtime`,`no_of_booking` FROM `".$this->rzvy_staff_schedule."` WHERE `weekday_id`='" .$dayid . "' AND `offday`='N' AND `week_id`='".$weekid."' AND  `staff_id`='".$staff_id."' and (('".$starttime."' between `starttime` and `endtime`) or '".$endtime."' between `starttime` and `endtime`)";
					}
					$value=mysqli_query($this->conn,$query);
					if(mysqli_num_rows($value)==0){
						return false;
					}else{
						$scheduleData = mysqli_fetch_array($value);
						$no_of_booking = $scheduleData["no_of_booking"];
						$qry1="select `order_id` from `".$this->rzvy_bookings."` where ((`booking_datetime` between '".$start_datetime."' and '".$end_datetime."') or (`booking_end_datetime` between '".$start_datetime."' and '".$end_datetime."')) and (`booking_status`='pending' OR `booking_status`='confirmed' OR `booking_status`='confirmed_by_staff' OR `booking_status`='rescheduled_by_you' OR `booking_status`='rescheduled_by_customer' OR `booking_status`='rescheduled_by_staff' OR `booking_status`='completed') and `staff_id`='".$staff_id."' group by `order_id`";
						
						$val1=mysqli_query($this->conn,$qry1);
						$totalBookedAppt = mysqli_num_rows($val1);
						if($totalBookedAppt>0 && $no_of_booking!=0 && $no_of_booking>$totalBookedAppt){
							return false;
						}else{
							/** staff days off process */
							$query="select * from `".$this->rzvy_staff_daysoff."` where `off_date` = '".$selected_date."' and `staff_id`='".$staff_id."'";
							$result=mysqli_query($this->conn,$query);
							if(mysqli_num_rows($result)>0){
								return false;
							}
							
							/** company default block off process */
							$query="select * from `".$this->rzvy_block_off."` where ('".$selected_date."' between `from_date` and `to_date` and `status`='Y' and `blockoff_type`='fullday') or ('".$selected_date."' between `from_date` and `to_date` and `status`='Y' and `blockoff_type`<>'fullday' and (('".$starttime."' between `from_time` and `to_time`) or '".$endtime."' between `from_time` and `to_time`))";
							$value=mysqli_query($this->conn,$query);
							if(mysqli_num_rows($value)>0){
								return false;
							}
							
							if($this->is_advance_schedule == "Y"){
								/** staff advance breaks process */
								$query="select * from `".$this->rzvy_staff_advance_schedule_breaks."` where ('".$selected_date."' between `startdate` and `enddate`) and `schedule_id`='".$this->advance_scheduleid."' and `staff_id`='".$staff_id."' and (('".$starttime."' between `break_start` and `break_end`) or '".$endtime."' between `break_start` and `break_end`)";
								$value=mysqli_query($this->conn,$query);
								if(mysqli_num_rows($value)>0){
									return false;
								}				
							}else{
								/** staff breaks process */
								$day_of_the_week = date("N", strtotime($selected_date));
								$query="select * from `".$this->rzvy_staff_breaks."` where `weekday_id` = '".$day_of_the_week."' and `staff_id`='".$staff_id."' and (('".$starttime."' between `break_start` and `break_end`) or '".$endtime."' between `break_start` and `break_end`)";
								$value=mysqli_query($this->conn,$query);
								if(mysqli_num_rows($value)>0){
									return false;
								}
							}
						}
					}
				}
			}
		}
		return true;
	}
	
	/* Function to get block off */
	public function get_bookings_blocks($selected_date, $slot, $timeinterval){
		$endslot = date("H:i:00", strtotime("+".$timeinterval." minutes", strtotime($selected_date." ".$slot)));
		if($this->staff_id!='0' && is_numeric($this->staff_id)){
			/** staff days off process */
			$query="select * from `".$this->rzvy_staff_daysoff."` where `off_date` = '".$selected_date."' and `staff_id`='".$this->staff_id."'";
			$value=mysqli_query($this->conn,$query);
			if(mysqli_num_rows($value)>0){
				return false;
			}
			if($this->is_advance_schedule == "Y"){
				/** staff breaks process */
				$query="select * from `".$this->rzvy_staff_advance_schedule_breaks."` where ('".$selected_date."' between `startdate` and `enddate`) and (('".$slot."' <= `break_start` && '".$endslot."' > `break_start`) or ('".$slot."' > `break_start` and '".$slot."' <= `break_end`) or ('".$slot."' < `break_end` and '".$endslot."' >= `break_end`)) and `schedule_id`='".$this->advance_scheduleid."' and `staff_id`='".$this->staff_id."'";
				$value=mysqli_query($this->conn,$query);
				if(mysqli_num_rows($value)>0){
					return false;
				}				
			}else{
				/** staff breaks process */
				$day_of_the_week = date("N", strtotime($selected_date));
				$query="select * from `".$this->rzvy_staff_breaks."` where `weekday_id` = '".$day_of_the_week."' and `staff_id`='".$this->staff_id."' and (('".$slot."' <= `break_start` && '".$endslot."' > `break_start`) or ('".$slot."' > `break_start` and '".$slot."' <= `break_end`) or ('".$slot."' < `break_end` and '".$endslot."' >= `break_end`))";
				$value=mysqli_query($this->conn,$query);
				if(mysqli_num_rows($value)>0){
					return false;
				}
			}
			
			$query="select * from `".$this->rzvy_breaks."` where `break_date` = '".$selected_date."' and staff_id = '".$this->staff_id."' and (('".$slot."' <= `starttime` && '".$endslot."' > `starttime`) or ('".$slot."' > `starttime` and '".$slot."' <= `endtime`) or ('".$slot."' < `endtime` and '".$endslot."' >= `endtime`))";
			$value=mysqli_query($this->conn,$query);
			if($value && mysqli_num_rows($value)>0){
				return false;
			}
		}else{
			$query="select * from `".$this->rzvy_breaks."` where `break_date` = '".$selected_date."' and staff_id = '0' and (('".$slot."' <= `starttime` && '".$endslot."' > `starttime`) or ('".$slot."' > `starttime` and '".$slot."' <= `endtime`) or ('".$slot."' < `endtime` and '".$endslot."' >= `endtime`))";
			$value=mysqli_query($this->conn,$query);
			if($value && mysqli_num_rows($value)>0){
				return false;
			}
		}
		
		$query="select * from `".$this->rzvy_block_off."` where '".$selected_date."' between `from_date` and `to_date` and `status`='Y' and ((`blockoff_type` = 'fullday') or (`blockoff_type` <> 'fullday' and (('".$slot."' <= `from_time` && '".$endslot."' > `from_time`) or ('".$slot."' > `from_time` and '".$slot."' <= `to_time`) or ('".$slot."' < `to_time` and '".$endslot."' >= `to_time`))))";
		$value=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($value)>0){
			return false;
		}
		return true;
	}
		
	/* Function to get option value from settings table */
	public function get_staff_option($option_name, $staff_id){
		$query = "select `option_value` from `".$this->rzvy_staff_settings."` where `option_name`='".$option_name."' and `staff_id` = '".$staff_id."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_array($result);
			return $value['option_value'];
		}else{
			return "";
		}
	}
	
	/* Function to get block off */
	public function working_today_staff($selected_date){
		$return_arr = array();
		$val=mysqli_query($this->conn, "select *  from `".$this->rzvy_staff."` where `status` = 'Y' order by `position` ASC");
		if(mysqli_num_rows($val)>0){
			while($staff = mysqli_fetch_array($val)){
				if($this->get_staff_option("show_staff_on_calendar", $staff['id']) == "Y"){
				    
				    
			    	$query = "select * from `rzvy_staff_advance_schedule` where ('".$selected_date."' between `startdate` and `enddate`) and `staff_id`='".$staff['id']."' and `status`='Y' order by `id` desc;";
        			$result=mysqli_query($this->conn, $query);
        			if(mysqli_num_rows($result)>0){
        				$row=mysqli_fetch_array($result);
        				$advance_scheduleid = $row['id'];
        				$is_advance_schedule = "Y";
        			}else{
        				$advance_scheduleid = 0;
        				$is_advance_schedule = "N";
        			}
        			$weekid = 1;
        			$dayid = date('N', strtotime($selected_date));
        			
        			/** staff schedule */
    				if($is_advance_schedule == "Y"){
    					$query="SELECT `starttime`,`endtime`,`no_of_booking` FROM `rzvy_staff_advance_schedule` WHERE `id`='".$advance_scheduleid."' AND `staff_id`='".$staff['id']."'";
    				}else{
    					$query="SELECT `starttime`,`endtime`,`no_of_booking` FROM `rzvy_staff_schedule` WHERE `weekday_id`='" .$dayid . "' AND `offday`='N' AND `week_id`='".$weekid."' AND  `staff_id`='".$staff['id']."';";
    				}
        			$result3=mysqli_query($this->conn,$query);
			        if(mysqli_num_rows($result3)>0){
			            /** staff days off process */
    					$query="select * from `".$this->rzvy_staff_daysoff."` where `off_date` = '".$selected_date."' and `staff_id`='".$staff['id']."'";
    					$value=mysqli_query($this->conn,$query);
    					if(mysqli_num_rows($value)==0){
    						array_push($return_arr, $staff['id']);
    					}
			        }
				}
			}
		}
		return implode(",",$return_arr);
	}
}
}
?>