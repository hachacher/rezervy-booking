<?php 
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
/* book appointment process */
if(sizeof($_SESSION['rzvy_mb_customer_detail'])>0){
	
	/* Include class files */
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_manual_booking.php");
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

	/* Create object of classes */

	$obj_frontend = new rzvy_manual_booking();
	$obj_frontend->conn = $conn;

	$obj_settings = new rzvy_settings();
	$obj_settings->conn = $conn;

	$rzvy_timeslot_interval = $obj_settings->get_option('rzvy_timeslot_interval');
	$rzvy_auto_confirm_appointment = $obj_settings->get_option('rzvy_auto_confirm_appointment');
	if($rzvy_auto_confirm_appointment == "Y"){
		$booking_status = "confirmed";
	}else{
		$booking_status = "pending";
	}
	
	$rzvy_mb_customer_detail = $_SESSION['rzvy_mb_customer_detail'];
	$order_id = $obj_frontend->get_order_id();
	
	$rzvy_settings_timezone = $obj_settings->get_option("rzvy_timezone");
	$rzvy_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($rzvy_server_timezone,$rzvy_settings_timezone); 
	
	/** Pass values to the public variable in class file **/
	$obj_frontend->order_id = $order_id;
	$obj_frontend->booking_datetime = $_SESSION['rzvy_mb_cart_datetime'];
	$obj_frontend->booking_end_datetime = $_SESSION['rzvy_mb_cart_end_datetime'];
	$obj_frontend->staff_id = $_SESSION['rzvy_mb_staff_id'];
	
	$obj_frontend->order_date = date("Y-m-d", $currDateTime_withTZ);
	$obj_frontend->category_id = $_SESSION['rzvy_mb_cart_category_id'];
	$obj_frontend->service_id = $_SESSION['rzvy_mb_cart_service_id'];
	$obj_frontend->service_rate = $_SESSION['rzvy_mb_cart_service_price'];
	$obj_frontend->addons = serialize($_SESSION['rzvy_mb_cart_items']);
	$obj_frontend->booking_status = $booking_status;
	$obj_frontend->lastmodified = date("Y-m-d H:i:s");

	$obj_frontend->email = trim(strip_tags(mysqli_real_escape_string($conn, $rzvy_mb_customer_detail['email'])));
	$obj_frontend->password = md5($rzvy_mb_customer_detail['password']);
	$obj_frontend->firstname = htmlentities($rzvy_mb_customer_detail['firstname']);
	$obj_frontend->lastname = htmlentities($rzvy_mb_customer_detail['lastname']);
	$obj_frontend->phone = $rzvy_mb_customer_detail['phone'];
	$obj_frontend->address = htmlentities($rzvy_mb_customer_detail['address']);
	$obj_frontend->city = htmlentities($rzvy_mb_customer_detail['city']);
	$obj_frontend->state = htmlentities($rzvy_mb_customer_detail['state']);
	$obj_frontend->country = htmlentities($rzvy_mb_customer_detail['country']);
	$obj_frontend->zip = htmlentities($rzvy_mb_customer_detail['zip']);

	$obj_frontend->payment_method = $rzvy_mb_customer_detail['payment_method'];
	$obj_frontend->payment_date = date("Y-m-d", $currDateTime_withTZ);
	$obj_frontend->transaction_id = $_SESSION['mb_transaction_id'];
	$obj_frontend->sub_total = $_SESSION['rzvy_mb_cart_subtotal'];
	$obj_frontend->discount = $_SESSION['rzvy_mb_cart_coupondiscount'];
	$obj_frontend->tax = $_SESSION['rzvy_mb_cart_tax'];
	$obj_frontend->net_total = $_SESSION['rzvy_mb_cart_nettotal'];
	$obj_frontend->fd_key = $_SESSION['rzvy_mb_cart_freqdiscount_key'];
	$obj_frontend->fd_amount = $_SESSION['rzvy_mb_cart_freqdiscount'];

	$obj_frontend->coupon_id = $_SESSION['rzvy_mb_cart_couponid'];
	$obj_frontend->is_expired = "Y";
	$obj_frontend->used_on = date("Y-m-d", $currDateTime_withTZ);

	$obj_frontend->fd_id = $_SESSION['rzvy_mb_cart_freqdiscount_id'];
		
	/** check customer type **/
	if($rzvy_mb_customer_detail['customertype'] == "ec"){
		$customer_id = $_SESSION['mb_customer_id'];
		if(is_numeric($customer_id)){
			$obj_frontend->customer_id = $customer_id;
			
			/** add appointment detail into effective tables **/
			$appointment_added = $obj_frontend->add_bookings();
			if($appointment_added){
				/** add customer order information **/
				$customer_orderinfo_added = $obj_frontend->add_customer_orderinfo();
				if($customer_orderinfo_added){
					/** add payment information **/
					$payment_added = $obj_frontend->add_payments();
				}
			}
			
		}
	}else if($rzvy_mb_customer_detail['customertype'] == "gc" || $rzvy_mb_customer_detail['customertype'] == "wc"){
		$customer_id = 0;
		if(is_numeric($customer_id)){
			$obj_frontend->customer_id = $customer_id;
			
			/** add appointment detail into effective tables **/
			$appointment_added = $obj_frontend->add_bookings();
			if($appointment_added){
				/** add customer order information **/
				$customer_orderinfo_added = $obj_frontend->add_customer_orderinfo();
				if($customer_orderinfo_added){
					/** add payment information **/
					$payment_added = $obj_frontend->add_payments();
				}
			}
		}
	} else {
		$customer_id = $obj_frontend->add_customers();
		if(is_numeric($customer_id)){
			$obj_frontend->customer_id = $customer_id;			
			/** add appointment detail into effective tables **/
			$appointment_added = $obj_frontend->add_bookings();
			if($appointment_added){
				/** add customer order information **/
				$customer_orderinfo_added = $obj_frontend->add_customer_orderinfo();
				if($customer_orderinfo_added){
					/** add payment information **/
					$payment_added = $obj_frontend->add_payments();
				}
			}
		}
	}
	
	/**** Common GC Data start **/
	$rzvy_gc_clientid = $obj_settings->get_option('rzvy_gc_clientid');
	$rzvy_gc_clientsecret = $obj_settings->get_option('rzvy_gc_clientsecret');
	
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
	$obj_es_information = new rzvy_es_information();
	$obj_es_information->conn = $conn;
	
	$rzvy_time_format = $obj_settings->get_option("rzvy_time_format");
	$rzvy_timezone = $obj_settings->get_option("rzvy_timezone");
	
	$obj_es_information->category_id = $_SESSION['rzvy_mb_cart_category_id'];
	$obj_es_information->service_id = $_SESSION['rzvy_mb_cart_service_id'];

	$category_title = $obj_es_information->readone_category_name();
	$readone_service = $obj_es_information->readone_service();
	$service_title = $readone_service['title'];
	
	$getNewTime = new \DateTime('now', new DateTimeZone($rzvy_timezone));
	$timezoneOffset = $getNewTime->format('P');
	
	$gc_StartTime = str_replace(" ", "T", $_SESSION['rzvy_mb_cart_datetime']).$timezoneOffset;
	$gc_EndTime = str_replace(" ", "T", $_SESSION['rzvy_mb_cart_end_datetime']).$timezoneOffset;
	$gcDescription = "Appointment with ".$rzvy_mb_customer_detail['firstname']." ".$rzvy_mb_customer_detail['lastname']." on ".date($rzvy_time_format, strtotime($_SESSION['rzvy_mb_cart_datetime']))." - ".date($rzvy_time_format, strtotime($_SESSION['rzvy_mb_cart_end_datetime']))." for ".$category_title." - ".$service_title;
	$gcSummary = $category_title." - ".$service_title;
	/**** Common GC Data end **/
	
	/**** Add to GC start **/
	$rzvy_gc_status = $obj_settings->get_option('rzvy_gc_status');
	$rzvy_gc_accesstoken = $obj_settings->get_option('rzvy_gc_accesstoken');
	$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
	if($rzvy_gc_status == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != ""){
		include(dirname(dirname(dirname(__FILE__)))."/includes/google-calendar/vendor/autoload.php");
		$client = new Google_Client();
		$client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
		$client->setClientId($rzvy_gc_clientid);
		$client->setClientSecret($rzvy_gc_clientsecret);
		$client->setAccessType('offline');
		$client->setPrompt('select_account consent');

		$accessToken = unserialize($rzvy_gc_accesstoken);
		$client->setAccessToken($accessToken);
		if ($client->isAccessTokenExpired()) {
			$newAccessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
			$obj_settings->update_option('rzvy_gc_accesstoken',base64_encode(serialize($newAccessToken)));
		}
		$service = new Google_Service_Calendar($client);
		$event = new Google_Service_Calendar_Event(array(
		  'summary' => $gcSummary,
		  'description' => $gcDescription,
		  'start' => array(
			'dateTime' => $gc_StartTime,
			'timeZone' => $rzvy_timezone,
		  ),
		  'end' => array(
			'dateTime' => $gc_EndTime,
			'timeZone' => $rzvy_timezone,
		  )
		));
		$calendarId = (($obj_settings->get_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_option('rzvy_gc_calendarid'):'primary');
		$addedEvent = $service->events->insert($calendarId, $event);
		$obj_es_information->insert_appt_gc_eventid($order_id, $addedEvent->id);
	}
	/*** Add to GC end **/
	
	/**** Add to Staff GC start **/
	if($_SESSION['rzvy_mb_staff_id']>0){
		$obj_settings->staff_id = $_SESSION['rzvy_mb_staff_id'];
		$rzvy_gc_status = $obj_settings->get_staff_option('rzvy_gc_status');
		$rzvy_gc_accesstoken = $obj_settings->get_staff_option('rzvy_gc_accesstoken');
		$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
		if($rzvy_gc_status == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != ""){
			include(dirname(dirname(dirname(__FILE__)))."/includes/google-calendar/vendor/autoload.php");
			$client = new Google_Client();
			$client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
			$client->setClientId($rzvy_gc_clientid);
			$client->setClientSecret($rzvy_gc_clientsecret);
			$client->setAccessType('offline');
			$client->setPrompt('select_account consent');

			$accessToken = unserialize($rzvy_gc_accesstoken);
			$client->setAccessToken($accessToken);
			if ($client->isAccessTokenExpired()) {
				$newAccessToken = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
				$obj_settings->update_staff_option('rzvy_gc_accesstoken',base64_encode(serialize($newAccessToken)));
			}
			$service = new Google_Service_Calendar($client);
			$event = new Google_Service_Calendar_Event(array(
			  'summary' => $gcSummary,
			  'description' => $gcDescription,
			  'start' => array(
				'dateTime' => $gc_StartTime,
				'timeZone' => $rzvy_timezone,
			  ),
			  'end' => array(
				'dateTime' => $gc_EndTime,
				'timeZone' => $rzvy_timezone,
			  )
			));
			$calendarId = (($obj_settings->get_staff_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_staff_option('rzvy_gc_calendarid'):'primary');
			$addedEvent = $service->events->insert($calendarId, $event);
			$obj_es_information->insert_appt_gc_eventid_staff($order_id, $addedEvent->id);
		}
	}
	/*** Add to Staff GC end **/
	
	/** Unset related sessions **/
	$_SESSION['rzvy_mb_customer_detail'] = array();
	$_SESSION['rzvy_mb_cart_items'] = array();
	$_SESSION['mb_customer_id'] = "";
	$_SESSION['rzvy_mb_cart_category_id'] = "";
	$_SESSION['rzvy_mb_cart_service_id'] = "";
	$_SESSION['rzvy_mb_cart_datetime'] = "";
	$_SESSION['rzvy_mb_cart_freqdiscount_label'] = "";
	$_SESSION['rzvy_mb_cart_freqdiscount_key'] = "";
	$_SESSION['rzvy_mb_cart_freqdiscount_id'] = "";
	$_SESSION['rzvy_mb_cart_subtotal'] = 0;
	$_SESSION['rzvy_mb_cart_freqdiscount'] = 0;
	$_SESSION['rzvy_mb_cart_coupondiscount'] = 0;
	$_SESSION['rzvy_mb_cart_couponid'] = "";
	$_SESSION['rzvy_mb_cart_tax'] = 0;
	$_SESSION['rzvy_mb_cart_nettotal'] = 0;
	echo "BOOKED";
	die;
}