<?php 
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
/* book appointment process */


if(isset($rzvy_translangArr['appointment_with'])){ $label_appointmentwith = $rzvy_translangArr['appointment_with']; }else{ $label_appointmentwith = $rzvy_defaultlang['appointment_with']; }
if(isset($rzvy_translangArr['with'])){ $label_with = $rzvy_translangArr['with']; }else{ $label_with = $rzvy_defaultlang['with']; }
if(isset($rzvy_translangArr['on'])){ $label_on = $rzvy_translangArr['on']; }else{ $label_on = $rzvy_defaultlang['on']; }
if(isset($rzvy_translangArr['for'])){ $label_for = $rzvy_translangArr['for']; }else{ $label_for = $rzvy_defaultlang['for']; }
if(isset($rzvy_translangArr['addon_services'])){ $label_addon_services = $rzvy_translangArr['addon_services']; }else{ $label_addon_services = $rzvy_defaultlang['addon_services']; }
if(isset($rzvy_translangArr['add_to_calendar'])){ $label_add_to_calendar = $rzvy_translangArr['add_to_calendar']; }else{ $label_add_to_calendar = $rzvy_defaultlang['add_to_calendar']; }
if(isset($rzvy_translangArr['qty'])){ $label_qty = $rzvy_translangArr['qty']; }else{ $label_qty = $rzvy_defaultlang['qty']; }
if(isset($rzvy_translangArr['google'])){ $label_google = $rzvy_translangArr['google']; }else{ $label_google = $rzvy_defaultlang['google']; }
if(isset($rzvy_translangArr['yahoo'])){ $label_yahoo = $rzvy_translangArr['yahoo']; }else{ $label_yahoo = $rzvy_defaultlang['yahoo']; }
if(isset($rzvy_translangArr['outlook'])){ $label_outlook = $rzvy_translangArr['outlook']; }else{ $label_outlook = $rzvy_defaultlang['outlook']; }
if(isset($rzvy_translangArr['ical'])){ $label_ical = $rzvy_translangArr['ical']; }else{ $label_ical = $rzvy_defaultlang['ical']; }

 

if(sizeof($_SESSION['rzvy_customer_detail'])>0){
	
	/* Include class files */
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_frontend.php");
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_loyalty_points.php");

	/* Create object of classes */
	$obj_frontend = new rzvy_frontend();
	$obj_frontend->conn = $conn;

	$obj_settings = new rzvy_settings();
	$obj_settings->conn = $conn;

	$obj_lpoint = new rzvy_loyalty_points();
	$obj_lpoint->conn = $conn;

	$rzvy_timeslot_interval = $obj_settings->get_option('rzvy_timeslot_interval');
	$rzvy_auto_confirm_appointment = $obj_settings->get_option('rzvy_auto_confirm_appointment');
	if($rzvy_auto_confirm_appointment == "Y"){
		$booking_status = "confirmed";
		$booking_email_status = "confirm";
	}else{
		$booking_status = "pending";
		$booking_email_status = "new";
	}
	
	$rzvy_customer_detail = $_SESSION['rzvy_customer_detail'];
	$order_id = $obj_frontend->get_order_id();
	
	$rzvy_settings_timezone = $obj_settings->get_option("rzvy_timezone");
	$rzvy_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($rzvy_server_timezone,$rzvy_settings_timezone); 
	
	/** Pass values to the public variable in class file **/
	$obj_frontend->order_id = $order_id;
	$obj_frontend->booking_datetime = $_SESSION['rzvy_cart_datetime'];
	$obj_frontend->booking_end_datetime = $_SESSION['rzvy_cart_end_datetime'];
	/* $obj_frontend->booking_end_datetime = date("Y-m-d H:i:s", strtotime("+".$rzvy_timeslot_interval." minutes", strtotime($_SESSION['rzvy_cart_datetime']))); */
	$obj_frontend->order_date = date("Y-m-d", $currDateTime_withTZ);
	$obj_frontend->category_id = $_SESSION['rzvy_cart_category_id'];
	$obj_frontend->service_id = $_SESSION['rzvy_cart_service_id'];
	$obj_frontend->service_rate = $_SESSION['rzvy_cart_service_price'];
	$obj_frontend->addons = serialize($_SESSION['rzvy_cart_items']);
	$obj_frontend->booking_status = $booking_status;
	$obj_frontend->lastmodified = date("Y-m-d H:i:s");

	$obj_frontend->email = trim(strip_tags(mysqli_real_escape_string($conn, $rzvy_customer_detail['email'])));
	$obj_frontend->password = md5($rzvy_customer_detail['password']);
	$obj_frontend->firstname = htmlentities($rzvy_customer_detail['firstname']);
	$obj_frontend->lastname = htmlentities($rzvy_customer_detail['lastname']);
	$obj_frontend->phone = $rzvy_customer_detail['phone'];
	$obj_frontend->address = htmlentities($rzvy_customer_detail['address']);
	$obj_frontend->city = htmlentities($rzvy_customer_detail['city']);
	$obj_frontend->state = htmlentities($rzvy_customer_detail['state']);
	$obj_frontend->country = htmlentities($rzvy_customer_detail['country']);

	if($obj_settings->get_option('rzvy_birthdate_with_year') == "Y"){
		if($rzvy_customer_detail['dob']!= ""){
			$obj_frontend->dob = date("Y-m-d", strtotime($rzvy_customer_detail['dob']));
		}else{
			$obj_frontend->dob = "2020-01-01";
		}
	}else{
		if($rzvy_customer_detail['dob']!= ""){
			$obj_frontend->dob = date("Y-m-d", strtotime($rzvy_customer_detail['dob']." 2020"));
		}else{
			$obj_frontend->dob = "2020-01-01";
		}
	}
	
	$obj_frontend->notes = htmlentities($rzvy_customer_detail['notes']);
	$obj_frontend->zip = htmlentities($rzvy_customer_detail['zip']);

	$obj_frontend->payment_method = $rzvy_customer_detail['payment_method'];
	$obj_frontend->payment_date = date("Y-m-d", $currDateTime_withTZ);
	$obj_frontend->transaction_id = $_SESSION['transaction_id'];
	$obj_frontend->sub_total = $_SESSION['rzvy_cart_subtotal'];
	$obj_frontend->discount = $_SESSION['rzvy_cart_coupondiscount'];
	$obj_frontend->tax = $_SESSION['rzvy_cart_tax'];
	$obj_frontend->net_total = $_SESSION['rzvy_cart_nettotal'];
	$obj_frontend->fd_key = $_SESSION['rzvy_cart_freqdiscount_key'];
	$obj_frontend->fd_amount = $_SESSION['rzvy_cart_freqdiscount'];
	$obj_frontend->refer_discount = $_SESSION['rzvy_referral_discount_amount'];
	$obj_frontend->refer_discount_id = $_SESSION['rzvy_applied_ref_customer_id'];
	$obj_frontend->partial_deposite = $_SESSION['rzvy_cart_partial_deposite'];

	$obj_frontend->coupon_id = $_SESSION['rzvy_cart_couponid'];
	$obj_frontend->is_expired = "Y";
	$obj_frontend->used_on = date("Y-m-d", $currDateTime_withTZ);

	$obj_frontend->fd_id = $_SESSION['rzvy_cart_freqdiscount_id'];
	$obj_frontend->staff_id = $_SESSION['rzvy_staff_id'];
	
	/** check customer type **/
	if($rzvy_customer_detail['customertype'] == "ec"){
		$customer_id = $_SESSION['customer_id'];
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
					
					/* Services Package Save On Checkout */
					$Rzvy_Hooks->do_action('services_package_checkout_save', array($order_id,$customer_id,$currDateTime_withTZ));
					/* Services Package Save On Checkout */
					if($payment_added){
						/** add used coupon detail **/
						if($_SESSION['rzvy_cart_couponid'] != ""){
							$used_coupons_by_customer_added = $obj_frontend->add_used_coupons_by_customer();
						}
						/** add referral discount detail **/
						if($_SESSION['rzvy_ref_customer_id'] != ""){
							$ref_discount_type = $obj_settings->get_option('rzvy_referral_discount_type');
							$ref_discount = $obj_settings->get_option('rzvy_referral_discount_value');
							$obj_frontend->ref_customer_id = $_SESSION['rzvy_ref_customer_id'];
							$obj_frontend->ref_discount = $ref_discount;
							$obj_frontend->ref_discount_type = $ref_discount_type;
							$customer_referrals_added = $obj_frontend->add_customer_referral();
						}
						/** update referral discount detail **/
						if($_SESSION['rzvy_applied_ref_customer_id'] != ""){
							$update_customer_referral_used = $obj_frontend->update_customer_referral_used($_SESSION['rzvy_applied_ref_customer_id']);
						}
						/** add used loyalty points detail **/
						if($_SESSION["rzvy_lpoint_checked"] == "true" || $_SESSION["rzvy_lpoint_checked"] == "on"){
							$obj_lpoint->add_loyalty_points_record($order_id, $customer_id, "U", $_SESSION['rzvy_lpoint_used'], $_SESSION['rzvy_lpoint_left']);
						}
					}
				}
			}
		}
	}else if($rzvy_customer_detail['customertype'] == "gc"){
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
					if($payment_added){
						
						/** add referral discount detail **/
						/* if($_SESSION['rzvy_ref_customer_id'] != ""){
							$ref_discount_type = $obj_settings->get_option('rzvy_referral_discount_type');
							$ref_discount = $obj_settings->get_option('rzvy_referral_discount_value');
							$obj_frontend->ref_customer_id = $_SESSION['rzvy_ref_customer_id'];
							$obj_frontend->ref_discount = $ref_discount;
							$obj_frontend->ref_discount_type = $ref_discount_type;
							$customer_referrals_added = $obj_frontend->add_customer_referral();
						} */
						
						/** add used coupon detail **/
						if($_SESSION['rzvy_cart_couponid'] != ""){
							$used_coupons_by_customer_added = $obj_frontend->add_used_coupons_by_customer();
						}
					}
				}
			}
		}
	} else {
		$customer_id = $obj_frontend->add_customers();
		if(is_numeric($customer_id)){
			
			/* Set session values for logged in customer */
			unset($_SESSION['staff_id']);
			unset($_SESSION['admin_id']);
			$_SESSION['customer_id'] = $customer_id;
			$_SESSION['login_type'] = "customer";
			
			$obj_frontend->customer_id = $customer_id;
			
			/** add appointment detail into effective tables **/
			$appointment_added = $obj_frontend->add_bookings();
			if($appointment_added){
				/** add customer order information **/
				$customer_orderinfo_added = $obj_frontend->add_customer_orderinfo();
				if($customer_orderinfo_added){
					/** add payment information **/
					$payment_added = $obj_frontend->add_payments();
					
					/* Services Package Save On Checkout */
					$Rzvy_Hooks->do_action('services_package_checkout_save', array($order_id,$customer_id,$currDateTime_withTZ));
					/* Services Package Save On Checkout */
					if($payment_added){
						/** add used coupon detail **/
						if($_SESSION['rzvy_cart_couponid'] != ""){
							$used_coupons_by_customer_added = $obj_frontend->add_used_coupons_by_customer();
						}						
						/** add referral discount detail **/
						if($_SESSION['rzvy_ref_customer_id'] != ""){
							$ref_discount_type = $obj_settings->get_option('rzvy_referral_discount_type');
							$ref_discount = $obj_settings->get_option('rzvy_referral_discount_value');
							$obj_frontend->ref_customer_id = $_SESSION['rzvy_ref_customer_id'];
							$obj_frontend->ref_discount = $ref_discount;
							$obj_frontend->ref_discount_type = $ref_discount_type;
							$customer_referrals_added = $obj_frontend->add_customer_referral();
						}
					}
				}
			}
		}
	}
	
	/********************** Send SMS & Email code start ***************************/
	include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
	$obj_es_information = new rzvy_es_information();
	$obj_es_information->conn = $conn;

	/**** Common GC start **/
	$rzvy_gc_clientid = $obj_settings->get_option('rzvy_gc_clientid');
	$rzvy_gc_clientsecret = $obj_settings->get_option('rzvy_gc_clientsecret');
	
	$rzvy_time_format = $obj_settings->get_option("rzvy_time_format");
	$rzvy_timezone = $obj_settings->get_option("rzvy_timezone");
	
	$obj_es_information->category_id = $_SESSION['rzvy_cart_category_id'];
	$obj_es_information->service_id = $_SESSION['rzvy_cart_service_id'];

	$category_title = $obj_es_information->readone_category_name();
	$readone_service = $obj_es_information->readone_service();
	$service_title = $readone_service['title'];
	$service_duration = $readone_service['duration'];
	
	$getNewTime = new \DateTime('now', new DateTimeZone($rzvy_timezone));
	$timezoneOffset = $getNewTime->format('P');
	
	$gc_StartTime = str_replace(" ", "T", $_SESSION['rzvy_cart_datetime']).$timezoneOffset;
	$gc_EndTime = str_replace(" ", "T", $_SESSION['rzvy_cart_end_datetime']).$timezoneOffset;
	$gcDescription = $label_appointmentwith.$rzvy_customer_detail['firstname']." ".$rzvy_customer_detail['lastname'].$label_on.date($rzvy_time_format, strtotime($_SESSION['rzvy_cart_datetime']))." - ".date($rzvy_time_format, strtotime($_SESSION['rzvy_cart_end_datetime'])).' '.$label_for.' '.$category_title." - ".$service_title;
	$gcSummary = $category_title." - ".$service_title;
	/**** Common GC end **/
	
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
	
	/*** Add to staff GC start **/
	if($_SESSION['rzvy_staff_id']>0){
		$obj_settings->staff_id = $_SESSION['rzvy_staff_id'];
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
	/*** Add to staff GC end **/

	$es_template = $booking_email_status;
	$es_staff_id = $_SESSION['rzvy_staff_id'];
	$es_category_id = $_SESSION['rzvy_cart_category_id'];
	$es_service_id = $_SESSION['rzvy_cart_service_id'];
	$es_booking_datetime = $_SESSION['rzvy_cart_datetime'];
	$es_transaction_id = $_SESSION['transaction_id'];
	$es_subtotal = $_SESSION['rzvy_cart_subtotal'];
	$es_coupondiscount = $_SESSION['rzvy_cart_coupondiscount'];
	$es_freqdiscount = $_SESSION['rzvy_cart_freqdiscount'];
	$es_tax = $_SESSION['rzvy_cart_tax'];
	$es_nettotal = $_SESSION['rzvy_cart_nettotal'];
	$es_payment_method = $rzvy_customer_detail['payment_method'];
	if($es_payment_method==ucwords('pay-at-venue')){
		if(isset($rzvy_translangArr['pay_at_venue'])){ $es_payment_method = $rzvy_translangArr['pay_at_venue']; }else{ $es_payment_method = $rzvy_defaultlang['pay_at_venue']; } 
	}
	$es_firstname = $rzvy_customer_detail['firstname'];
	$es_lastname = $rzvy_customer_detail['lastname'];
	$es_email = $rzvy_customer_detail['email'];
	$es_phone = $rzvy_customer_detail['phone'];
	$es_address = $rzvy_customer_detail['address'];
	$es_city = $rzvy_customer_detail['city'];
	$es_state = $rzvy_customer_detail['state'];
	$es_country = $rzvy_customer_detail['country'];
	$es_zip = $rzvy_customer_detail['zip'];
	$es_addons_items_arr = $_SESSION['rzvy_cart_items'];
	$es_reschedule_reason = "";
	$es_reject_reason = "";
	$es_cancel_reason = "";
	
	$rzvy_cancel_feedback_sms_shortlink = $obj_settings->get_option('rzvy_cancel_feedback_sms_shortlink');
	if($rzvy_cancel_feedback_sms_shortlink!=""){
		$shortlinkurl = $rzvy_cancel_feedback_sms_shortlink;
	}else{
		$shortlinkurl = SITE_URL;
	}
	$gcyahoooutlookbookingurl = '';
	if($obj_settings->get_option('rzvy_success_modal_booking')=='Y' && $obj_settings->get_option('rzvy_customer_calendars')=='Y'){
		
		if(isset($_SESSION['rzvy_staff_id']) && $_SESSION['rzvy_staff_id']!='' && $_SESSION['rzvy_staff_id']!=0){
			$obj_settings->staff_id = $_SESSION['rzvy_staff_id'];
			$staff_name = $obj_es_information->get_staff_name($_SESSION['rzvy_staff_id']);
			if($obj_settings->get_staff_option('show_email_on_booking_form')=='Y'){
				$staff_email = $obj_es_information->get_staff_email($_SESSION['rzvy_staff_id']);
			}else{
				$staff_email =  $obj_settings->get_option('rzvy_company_email');
			}
		}else{
			$staff_name =  $obj_settings->get_option('rzvy_company_name');
			$staff_email =  $obj_settings->get_option('rzvy_company_email');
		}
		$rzvy_baddons = '';
		if(isset($_SESSION['rzvy_cart_items']) && is_array($_SESSION['rzvy_cart_items']) && sizeof($_SESSION['rzvy_cart_items'])>0){
			$bflag = true;
			foreach($_SESSION['rzvy_cart_items'] as $addon){
				$obj_es_information->addon_id = $addon['id'];
				$baddon_name = $obj_es_information->get_addon_name();				
				if($bflag){
					$rzvy_baddons .= ' - '.$label_addon_services.' '.$baddon_name.' - '.$label_qty.' '.$addon['qty'];
					$bflag = false;
				}else{
					$rzvy_baddons .= "<br/>".$baddon_name.' - '.$label_qty.' '.$addon['qty'];
				}
				$service_duration += $addon['duration'];
			}
		}
		
		$rzvy_bdescription = $label_appointmentwith.$staff_name.' '.$label_for.' '.$category_title." -> ".$service_title.$rzvy_baddons;
		$rzvy_bstime = 	date('Y-m-d',strtotime($_SESSION['rzvy_cart_datetime'])).'T'.date('H:i:s',strtotime($_SESSION['rzvy_cart_datetime']));
		$rzvy_betime = 	date('Y-m-d',strtotime($_SESSION['rzvy_cart_end_datetime'])).'T'.date('H:i:s',strtotime($_SESSION['rzvy_cart_end_datetime']));
		$rzvy_btitle = 	$service_title .' '.$label_with.' '.$staff_name;	
		
		$rzvy_icalinfo = 'BEGIN:VCALENDAR
VERSION:2.0
METHOD:PUBLISH
X-MS-OLK-FORCEINSPECTOROPEN:TRUE
PRODID:'.SITE_URL.'
BEGIN:VEVENT
CLASS:PUBLIC
UID:'.uniqid().'
DTSTAMP:'.date('Ymd').'T'.date('His').'
ORGANIZER;CN="'.$staff_name.'":MAILTO:'.$staff_email.'
DTSTART:'.str_replace(array('-',':'),'',$rzvy_bstime).'
DTEND:'.str_replace(array('-',':'),'',$rzvy_betime).'
SUMMARY:'.$rzvy_btitle.'
DESCRIPTION:'.$rzvy_bdescription.'
END:VEVENT
END:VCALENDAR';
		
		
		$rzvy_customer_enable_calendars = $obj_settings->get_option("rzvy_customer_enable_calendars"); 
		$rzvy_customer_enable_calendar = array();
		if($rzvy_customer_enable_calendars!=''){
			$rzvy_customer_enable_calendar = explode(',',$rzvy_customer_enable_calendars);
		}
		if(sizeof($rzvy_customer_enable_calendar)>0){
			$gcyahoooutlookbookingurl .= '<div class="col-md-12"><div class="col-md-12"><h4>'.$label_add_to_calendar.'</h4></div><div class="col-md-12">';
			foreach($rzvy_customer_enable_calendar as $rzvy_customer_enable_cal){
				
				if($rzvy_customer_enable_cal=='google'){
					
					$gcyahoooutlookbookingurl .= '<a class="rzvy_cal_a" target="_blank" href="https://www.google.com/calendar/render?action=TEMPLATE&text='.$rzvy_btitle.'&details='.$rzvy_bdescription.'&dates='.str_replace(array('-',':'),'',$rzvy_bstime).'/'.str_replace(array('-',':'),'',$rzvy_betime).'&sf=1&output=xml"><img src="'.SITE_URL.'/includes/images/gcal.png" width="50px" height="50px"><label class="rzvy_cal_label">'.$label_google.
					'</label></a>';
					
				} if($rzvy_customer_enable_cal=='yahoo'){
					
					$gcyahoooutlookbookingurl .= '<a class="rzvy_cal_a" target="_blank" href="http://calendar.yahoo.com/?v=60&DUR='.$service_duration.'&TITLE='.$rzvy_btitle.'&ST='.str_replace(array('-',':'),'',$rzvy_bstime).'&ET='.str_replace(array('-',':'),'',$rzvy_betime).'"><img src="'.SITE_URL.'/includes/images/ycal.png" width="50px" height="50px"><label class="rzvy_cal_label">'.$label_yahoo.
					'</label></a>';
					
				} if($rzvy_customer_enable_cal=='outlook'){
					
					$gcyahoooutlookbookingurl .= '<a class="rzvy_cal_a" target="_blank" href="https://outlook.live.com/owa/?path=/calendar/view/Month&rru=addevent&startdt='.$rzvy_bstime.'&enddt='.$rzvy_betime.'&subject='.$rzvy_btitle.'&location=&body='.$rzvy_bdescription.'"><img src="'.SITE_URL.'/includes/images/ocal.png" width="50px" height="50px"><label class="rzvy_cal_label">'.$label_outlook.
					'</label></a>';
					
				} if($rzvy_customer_enable_cal=='ical'){
					
					$gcyahoooutlookbookingurl .= '<a class="rzvy_cal_a" id="rzvy_ical_booking_info_download" target="_blank" href="javascript:void(0)"><img src="'.SITE_URL.'/includes/images/ical.png" width="50px" height="50px"><label class="rzvy_cal_label">'.$label_ical.
					'</label></a><textarea style="display:none" id="rzvy_ical_booking_info">'.$rzvy_icalinfo.'</textarea>';
					
				}
			}
			$gcyahoooutlookbookingurl .= '</div></div>';
		}		
	}
	
	
	$feedback_url = $shortlinkurl.'f/B'.rand(101,999).base64_encode($order_id);
	$cancel_url = $shortlinkurl.'c/B'.rand(101,999).base64_encode($order_id);
	include("rzvy_send_sms_email_process.php");
	/********************** Send SMS & Email code END ****************************/
	
	
	/** Unset related sessions **/
	if($rzvy_customer_detail['payment_method'] == "paypal"){
		$_SESSION['rzvy_customer_detail'] = array();
		$_SESSION['rzvy_cart_items'] = array();
		$_SESSION['rzvy_cart_category_id'] = "";
		$_SESSION['rzvy_cart_service_id'] = "";
		$_SESSION['rzvy_cart_datetime'] = "";
		$_SESSION['rzvy_cart_end_datetime'] = "";
		$_SESSION['rzvy_cart_freqdiscount_label'] = "";
		$_SESSION['rzvy_cart_freqdiscount_key'] = "";
		$_SESSION['rzvy_cart_freqdiscount_id'] = "";
		$_SESSION['rzvy_cart_subtotal'] = 0;
		$_SESSION['rzvy_cart_freqdiscount'] = 0;
		$_SESSION['rzvy_cart_coupondiscount'] = 0;
		$_SESSION['rzvy_cart_couponid'] = "";
		$_SESSION['rzvy_cart_tax'] = 0;
		$_SESSION['rzvy_cart_nettotal'] = 0;
		header("location:".SITE_URL."thankyou.php&cals=".base64_encode(htmlentities($gcyahoooutlookbookingurl)));
		exit;
	}else{
		$_SESSION['rzvy_customer_detail'] = array();
		$_SESSION['rzvy_cart_items'] = array();
		$_SESSION['rzvy_cart_category_id'] = "";
		$_SESSION['rzvy_cart_service_id'] = "";
		$_SESSION['rzvy_cart_datetime'] = "";
		$_SESSION['rzvy_cart_end_datetime'] = "";
		$_SESSION['rzvy_cart_freqdiscount_label'] = "";
		$_SESSION['rzvy_cart_freqdiscount_key'] = "";
		$_SESSION['rzvy_cart_freqdiscount_id'] = "";
		$_SESSION['rzvy_cart_subtotal'] = 0;
		$_SESSION['rzvy_cart_freqdiscount'] = 0;
		$_SESSION['rzvy_cart_coupondiscount'] = 0;
		$_SESSION['rzvy_cart_couponid'] = "";
		$_SESSION['rzvy_cart_tax'] = 0;
		$_SESSION['rzvy_cart_nettotal'] = 0;
		@ob_clean(); ob_start();
		echo "BOOKED###".$gcyahoooutlookbookingurl;
		exit;
	}
}