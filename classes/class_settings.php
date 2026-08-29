<?php 
if (!class_exists('rzvy_settings')){
class rzvy_settings{
	public $conn;
	public $id;
	public $option_name;
	public $option_value;
	public $staff_id;
	public $rzvy_settings = 'rzvy_settings';
	public $rzvy_staff_settings = 'rzvy_staff_settings';
	public $rzvy_categories = 'rzvy_categories';
	public $rzvy_services = 'rzvy_services';
	public $rzvy_schedule = 'rzvy_schedule';
	public $rzvy_addons = 'rzvy_addons';
	
	/* Function to add default settings while register as admin */
	public function add_default_settings($siteurl, $companyname, $companyemail, $companyphone, $companyaddress, $companycity, $companystate, $companyzip, $companycountry, $rzvy_cs_admin_dash ,$rzvy_cs_admin_dash_primary_color ,$rzvy_cs_admin_dash_secondary_color ,$rzvy_cs_admin_dash_background_color ,$rzvy_cs_admin_dash_text_color ,$rzvy_cs_bfls ,$rzvy_cs_bfls_primary_color ,$rzvy_cs_bfls_secondary_color ,$rzvy_cs_bfls_background_color ,$rzvy_cs_bfls_text_color){
		$query = "INSERT INTO `".$this->rzvy_settings."` (`id`, `option_name`, `option_value`) VALUES
				(NULL, 'rzvy_currency', 'USD'),
				(NULL, 'rzvy_currency_symbol', '$'),
				(NULL, 'rzvy_date_format', 'd-m-Y'),
				(NULL, 'rzvy_timeslot_interval', '30'),
				(NULL, 'rzvy_time_format', 'H:i'),
				(NULL, 'rzvy_maximum_advance_booking_time', '3'),
				(NULL, 'rzvy_company_name', '".$companyname."'),
				(NULL, 'rzvy_company_email', '".$companyemail."'),
				(NULL, 'rzvy_company_phone', '".$companyphone."'),
				(NULL, 'rzvy_company_address', '".$companyaddress."'),
				(NULL, 'rzvy_company_city', '".$companycity."'),
				(NULL, 'rzvy_company_state', '".$companystate."'),
				(NULL, 'rzvy_company_zip', '".$companyzip."'),
				(NULL, 'rzvy_company_country', '".$companycountry."'),
				(NULL, 'rzvy_company_logo', ''),
				(NULL, 'rzvy_thankyou_page_url', '".$siteurl."thankyou.php'),
				(NULL, 'rzvy_auto_confirm_appointment', 'N'),
				(NULL, 'rzvy_tax_status', 'N'),
				(NULL, 'rzvy_tax_type', 'percentage'),
				(NULL, 'rzvy_tax_value', ''),
				(NULL, 'rzvy_minimum_advance_booking_time', '60'),
				(NULL, 'rzvy_cancellation_buffer_time', '60'),
				(NULL, 'rzvy_reschedule_buffer_time', '60'),
				(NULL, 'rzvy_show_frontend_rightside_feedback_list', 'Y'),
				(NULL, 'rzvy_show_frontend_rightside_feedback_form', 'Y'),
				(NULL, 'rzvy_show_guest_user_checkout', 'N'),
				(NULL, 'rzvy_show_existing_new_user_checkout', 'Y'),
				(NULL, 'rzvy_hide_already_booked_slots_from_frontend_calendar', 'Y'),
				(NULL, 'rzvy_terms_and_condition_link', '".$siteurl."'),
				(NULL, 'rzvy_admin_email_notification_status', 'N'),
				(NULL, 'rzvy_customer_email_notification_status', 'N'),
				(NULL, 'rzvy_email_sender_name', '".$companyname."'),
				(NULL, 'rzvy_email_sender_email', '".$companyemail."'),
				(NULL, 'rzvy_email_smtp_hostname', ''),
				(NULL, 'rzvy_email_smtp_username', ''),
				(NULL, 'rzvy_email_smtp_password', ''),
				(NULL, 'rzvy_email_smtp_port', ''),
				(NULL, 'rzvy_email_encryption_type', 'tls'),
				(NULL, 'rzvy_email_smtp_authentication', 'true'),
				(NULL, 'rzvy_send_email_with', 'MAIL'),
				(NULL, 'rzvy_admin_sms_notification_status', 'N'),
				(NULL, 'rzvy_customer_sms_notification_status', 'N'),
				(NULL, 'rzvy_seo_ga_code', ''),
				(NULL, 'rzvy_seo_meta_tag', ''),
				(NULL, 'rzvy_seo_meta_description', ''),
				(NULL, 'rzvy_seo_og_meta_tag', ''),
				(NULL, 'rzvy_seo_og_tag_type', ''),
				(NULL, 'rzvy_seo_og_tag_url', ''),
				(NULL, 'rzvy_seo_og_tag_image', ''),
				(NULL, 'rzvy_paypal_payment_status', 'N'),
				(NULL, 'rzvy_paypal_guest_payment', 'N'),
				(NULL, 'rzvy_paypal_api_username', ''),
				(NULL, 'rzvy_paypal_api_password', ''),
				(NULL, 'rzvy_paypal_signature', ''),
				(NULL, 'rzvy_stripe_payment_status', 'N'),
				(NULL, 'rzvy_stripe_secret_key', ''),
				(NULL, 'rzvy_stripe_publishable_key', ''),
				(NULL, 'rzvy_authorizenet_payment_status', 'N'),
				(NULL, 'rzvy_authorizenet_api_login_id', ''),
				(NULL, 'rzvy_authorizenet_transaction_key', ''),
				(NULL, 'rzvy_twocheckout_payment_status', 'N'),
				(NULL, 'rzvy_twocheckout_publishable_key', ''),
				(NULL, 'rzvy_twocheckout_private_key', ''),
				(NULL, 'rzvy_twocheckout_seller_id', ''),
				
				(NULL, 'rzvy_timezone', '".date_default_timezone_get()."'),
				(NULL, 'rzvy_location_selector', '".$companyzip."'),
				(NULL, 'rzvy_refund_status', 'N'),
				(NULL, 'rzvy_refund_type', 'percentage'),
				(NULL, 'rzvy_refund_value', ''),
				(NULL, 'rzvy_refund_request_buffer_time', '120'),
				(NULL, 'rzvy_refund_summary', ''),
				(NULL, 'rzvy_referral_discount_type', 'percentage'),
				(NULL, 'rzvy_referral_discount_value', '5'),
				(NULL, 'rzvy_reminder_buffer_time', '60'),
				
				(NULL, 'rzvy_location_selector_status', 'Y'),
				
				(NULL, 'rzvy_maximum_endtimeslot_limit', '60'),
				(NULL, 'rzvy_endtimeslot_selection_status', 'N'),
				(NULL, 'rzvy_referral_discount_status', 'Y'),
				(NULL, 'rzvy_pay_at_venue_status', 'Y'),
				(NULL, 'rzvy_welcome_message_status', 'N'),
				(NULL, 'rzvy_welcome_as_more_info_status', 'N'),
				(NULL, 'rzvy_welcome_message_container', ''),
				(NULL, 'rzvy_bookingform_bg', 'default'),
				(NULL, 'rzvy_bookingform_bg_type', 'color'),
				(NULL, 'rzvy_bookingform_bg_color', '#000000'),
				(NULL, 'rzvy_bookingform_bg_image', ''),
				(NULL, 'rzvy_minimum_booking_amount', '10'),
				
				(NULL, 'rzvy_single_category_autotrigger_status', 'N'),
				(NULL, 'rzvy_single_service_autotrigger_status', 'N'),
				(NULL, 'rzvy_auto_scroll_each_module_status', 'Y'),
				
				(NULL, 'rzvy_en_ff_firstname_status', 'Y'),
				(NULL, 'rzvy_en_ff_lastname_status', 'Y'),
				(NULL, 'rzvy_en_ff_phone_status', 'Y'),
				(NULL, 'rzvy_en_ff_address_status', 'Y'),
				(NULL, 'rzvy_en_ff_city_status', 'Y'),
				(NULL, 'rzvy_en_ff_state_status', 'Y'),
				(NULL, 'rzvy_en_ff_country_status', 'Y'),
				(NULL, 'rzvy_g_ff_firstname_status', 'Y'),
				(NULL, 'rzvy_g_ff_lastname_status', 'Y'),
				(NULL, 'rzvy_g_ff_phone_status', 'Y'),
				(NULL, 'rzvy_g_ff_address_status', 'Y'),
				(NULL, 'rzvy_g_ff_city_status', 'Y'),
				(NULL, 'rzvy_g_ff_state_status', 'Y'),
				(NULL, 'rzvy_g_ff_country_status', 'Y'),
				(NULL, 'rzvy_en_ff_firstname_optional', 'Y'),
				(NULL, 'rzvy_en_ff_lastname_optional', 'Y'),
				(NULL, 'rzvy_en_ff_phone_optional', 'Y'),
				(NULL, 'rzvy_en_ff_address_optional', 'Y'),
				(NULL, 'rzvy_en_ff_city_optional', 'Y'),
				(NULL, 'rzvy_en_ff_state_optional', 'Y'),
				(NULL, 'rzvy_en_ff_country_optional', 'Y'),
				(NULL, 'rzvy_g_ff_firstname_optional', 'Y'),
				(NULL, 'rzvy_g_ff_lastname_optional', 'Y'),
				(NULL, 'rzvy_g_ff_phone_optional', 'Y'),
				(NULL, 'rzvy_g_ff_address_optional', 'Y'),
				(NULL, 'rzvy_g_ff_city_optional', 'Y'),
				(NULL, 'rzvy_g_ff_state_optional', 'Y'),
				(NULL, 'rzvy_g_ff_country_optional', 'Y'),
				
				(NULL, 'rzvy_rzvy_show_dropdown_languages', ''),
				(NULL, 'rzvy_cs_admin_dash', '".$rzvy_cs_admin_dash."'),
				(NULL, 'rzvy_cs_admin_dash_primary_color', '".$rzvy_cs_admin_dash_primary_color."'),
				(NULL, 'rzvy_cs_admin_dash_secondary_color', '".$rzvy_cs_admin_dash_secondary_color."'),
				(NULL, 'rzvy_cs_admin_dash_background_color', '".$rzvy_cs_admin_dash_background_color."'),
				(NULL, 'rzvy_cs_admin_dash_text_color', '".$rzvy_cs_admin_dash_text_color."'),
				(NULL, 'rzvy_cs_bfls', '".$rzvy_cs_bfls."'),
				(NULL, 'rzvy_cs_bfls_primary_color', '".$rzvy_cs_bfls_primary_color."'),
				(NULL, 'rzvy_cs_bfls_secondary_color', '".$rzvy_cs_bfls_secondary_color."'),
				(NULL, 'rzvy_cs_bfls_background_color', '".$rzvy_cs_bfls_background_color."'),
				(NULL, 'rzvy_cs_bfls_text_color', '".$rzvy_cs_bfls_text_color."'),
				
				(NULL, 'rzvy_welcome_as_more_info_status', 'N'),
				(NULL, 'rzvy_staff_email_notification_status', 'N'),
				(NULL, 'rzvy_staff_sms_notification_status', 'N'),
				
				(NULL, 'rzvy_frontend', 'stepview'),
				(NULL, 'rzvy_default_language_on_page_load', ''),
				(NULL, 'rzvy_default_country_code', ''),
				(NULL, 'rzvy_gc_calendarid', 'primary'),
				(NULL, 'rzvy_gc_accesstoken', ''),
				(NULL, 'rzvy_gc_clientid', ''),
				(NULL, 'rzvy_gc_clientsecret', ''),
				(NULL, 'rzvy_gc_status', 'N'),
				(NULL, 'rzvy_gc_twowaysync', 'N'),
				
				(NULL, 'rzvy_booking_first_selection_as', 'allcategories'),
				(NULL, 'rzvy_minmum_cart_value_to_pay', '0'),
				(NULL, 'rzvy_bank_transfer_payment_status', 'N'),
				(NULL, 'rzvy_bank_transfer_bank_name', ''),
				(NULL, 'rzvy_bank_transfer_account_name', ''),
				(NULL, 'rzvy_bank_transfer_account_number', ''),
				(NULL, 'rzvy_bank_transfer_branch_code', ''),
				(NULL, 'rzvy_bank_transfer_ifsc_code', ''),
				(NULL, 'rzvy_partial_deposite_status', 'N'),
				(NULL, 'rzvy_partial_deposite_type', 'flat'),
				(NULL, 'rzvy_partial_deposite_value', '0'),
				
				(NULL, 'rzvy_multiservice_status', 'N'),
				(NULL, 'rzvy_allow_loyalty_points_status', 'N'),
				(NULL, 'rzvy_perbooking_loyalty_points', '0'),
				(NULL, 'rzvy_perbooking_loyalty_point_value', '0'),
				
				(NULL, 'rzvy_timeslots_display_method', 'S'),
				(NULL, 'rzvy_loyalty_points_reward_method', 'F'),
				(NULL, 'rzvy_loyalty_points_per_spend_based', '0.1'),
				
				(NULL, 'rzvy_hotjar_tracking_code', ''),
				(NULL, 'rzvy_fbpixel_tracking_code', ''),
				(NULL, 'rzvy_ga_tracking_code', ''),
				
				(NULL, 'rzvy_en_ff_dob_status', 'Y'),
				(NULL, 'rzvy_en_ff_dob_optional', 'Y'),
				(NULL, 'rzvy_en_ff_notes_status', 'Y'),
				(NULL, 'rzvy_en_ff_notes_optional', 'Y'),
				(NULL, 'rzvy_g_ff_dob_status', 'Y'),
				(NULL, 'rzvy_g_ff_dob_optional', 'Y'),
				(NULL, 'rzvy_g_ff_notes_status', 'Y'),
				(NULL, 'rzvy_g_ff_notes_optional', 'N'),
				(NULL, 'rzvy_reminder_on', 'buffer_time'),
				(NULL, 'rzvy_no_of_loyalty_point_as_birthday_gift', '20'),
				
				(NULL, 'rzvy_single_staff_autotrigger_status', 'N'),
				(NULL, 'rzvy_stepview_alignment', 'left'),
				(NULL, 'rzvy_show_cancelled_appointments', 'Y'),
				(NULL, 'rzvy_show_parentcategory_image', 'Y'),
				(NULL, 'rzvy_show_category_image', 'Y'),
				(NULL, 'rzvy_show_service_image', 'Y'),
				(NULL, 'rzvy_show_addon_image', 'Y'),
				(NULL, 'rzvy_show_staff_image', 'Y'),
				(NULL, 'rzvy_show_package_image', 'Y'),
				(NULL, 'rzvy_services_listing_view', 'G'),
				(NULL, 'rzvy_g_ff_email_status', 'Y'),
				(NULL, 'rzvy_g_ff_email_optional', 'Y'),
				(NULL, 'rzvy_parent_category', 'N'),
				(NULL, 'rzvy_cookiesconcent_status', 'Y'),
				
				(NULL, 'rzvy_privacy_and_policy_link', ''),
				(NULL, 'rzvy_showhide_pay_at_venue', 'Y'),
				(NULL, 'rzvy_book_with_datetime', 'Y'),
				
				(NULL, 'rzvy_pos_payment_types', 'a:0:{}'),
				(NULL, 'rzvy_birthdate_with_year', 'N'),
				
				(NULL, 'rzvy_cancel_feedback_sms_shortlink', '".$siteurl."'),
				(NULL, 'rzvy_fontfamily', 'Raleway'),
				(NULL, 'rzvy_custom_css_bookingform', ''),
				(NULL, 'rzvy_price_display', 'Y'),
				(NULL, 'rzvy_success_modal_booking', 'Y'),
				(NULL, 'rzvy_customer_calendars', 'Y'),
				(NULL, 'rzvy_staffs_time_today', 'N'),
				(NULL, 'rzvy_staffs_time_tomorrow', 'N'),
				(NULL, 'rzvy_coupon_input_status', 'N'),
				(NULL, 'rzvy_currency_position', 'B'),
				(NULL, 'rzvy_referral_channels', 'facebook,twitter,googleplus,whatsapp'),
				(NULL, 'rzvy_customer_enable_calendars', 'google,yahoo,outlook,ical'),
				(NULL, 'rzvy_cancel_success_message', 'PGgyIGNsYXNzPSIgZnMtMyBwYi1zbS00IHB5LTIgdGV4dC1zdWNjZXMgbXgtYXV0byByenZ5X2NhbmNlbF9kYXRhX21zZyI+PGkgY2xhc3M9ImZhIGZhLWNoZWNrLWNpcmNsZS1vIHRleHQtc3VjY2VzcyIgYXJpYS1oaWRkZW49InRydWUiPjwvaT4gWW91ciBhcHBvaW50bWVudCBpcyBjYW5jZWxsZWQgc3VjY2Vzc2Z1bGx5ITwvaDQ+'),
				
				(NULL, 'rzvy_version', '5.1')
				";
		$result=mysqli_query($this->conn,$query);
		return $result;
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
	
	/* Function to update option value in settings table */
	public function update_option($option_name,$option_value){
		$query = "select `option_value` from `".$this->rzvy_settings."` where `option_name`='".$option_name."'";
		$result=mysqli_query($this->conn,$query);
		if($option_name != "rzvy_apptstatus_colorscheme" && $option_name != "rzvy_apptstatus_text_colorscheme"){
			$option_value = htmlspecialchars($option_value, ENT_QUOTES);
		}
		if(mysqli_num_rows($result)>0){
			$query = "update `".$this->rzvy_settings."` set `option_value`='".$option_value."' where `option_name`='".$option_name."'";
		}else{
			$query = "INSERT INTO `".$this->rzvy_settings."` (`id`, `option_name`, `option_value`) VALUES (NULL, '".$option_name."', '".$option_value."');";
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to check for setup instruction modal */
	public function check_for_setup_instruction_modal(){
		$query = "select * from `".$this->rzvy_categories."`";
		$result=mysqli_query($this->conn,$query);
		$count=mysqli_num_rows($result);
		if($count==0){
			return "Y";
		}else{
			$query = "select * from `".$this->rzvy_services."`";
			$result=mysqli_query($this->conn,$query);
			$count=mysqli_num_rows($result);
			if($count==0){
				return "Y";
			}else{
				$query = "select * from `".$this->rzvy_addons."`";
				$result=mysqli_query($this->conn,$query);
				$count=mysqli_num_rows($result);
				if($count==0){
					return "Y";
				}else{
					$query = "select * from `".$this->rzvy_schedule."`";
					$result=mysqli_query($this->conn,$query);
					$count=mysqli_num_rows($result);
					if($count==0){
						return "Y";
					}else{
						return "N";
					}
				}
			}
		}
	}
	
	/** Convert Base64 string to an image file **/
	public function rzvy_base64_to_jpeg($base64_string, $output_filepath, $output_filename) {
		$data = explode( ',', $base64_string );
		$image_parts = explode(";base64,", $data[0]);
		$image_type_aux = explode("image/", $image_parts[0]);
		$image_type = explode(";", $image_type_aux[1]);
		$output_filetype = $image_type[0];
		$output_filename = $output_filename.".".$output_filetype;
		$output_file = $output_filepath.$output_filename;
		$ifp = fopen( $output_file, 'wb' );
		fwrite( $ifp, base64_decode( $data[1] ) );
		fclose( $ifp );
		return $output_filename; 
	}
	
	/** Get time according to saved timezone **/
	public function get_current_time_according_selected_timezone($remote_tz, $origin_tz = null) {
		if($origin_tz === null) {
			if(!is_string($origin_tz = date_default_timezone_get())) {
				return false; /* A UTC timestamp was returned -- bail out! */
			}
		}
		if(isset($origin_tz) && $origin_tz!=''){
			$origin_dtz = new DateTimeZone($origin_tz);
			$remote_dtz = new DateTimeZone($remote_tz);
			$origin_dt = new DateTime("now", $origin_dtz);
			$remote_dt = new DateTime("now", $remote_dtz);
			$offset = $origin_dtz->getOffset($remote_dt) - $remote_dtz->getOffset($origin_dt);
			$timezonediff = $offset/3600;  
		}else{
			$timezonediff =0;
		}
		if(is_numeric(strpos($timezonediff,'-'))){
			$timediffmis = str_replace('-','',$timezonediff)*60;
			$currDateTime_withTZ= strtotime("-".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
		}else{
			$timediffmis = str_replace('+','',$timezonediff)*60;
			$currDateTime_withTZ = strtotime("+".$timediffmis." minutes",strtotime(date('Y-m-d H:i:s')));
		}
		return $currDateTime_withTZ;
	}
	
	/* Function to get option value from settings table */
	public function get_staff_option($option_name){
		$query = "select `option_value` from `".$this->rzvy_staff_settings."` where `option_name`='".$option_name."' and `staff_id` = '".$this->staff_id."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_array($result);
			return $value['option_value'];
		}else{
			return "";
		}
	}
	
	/* Function to update option value in settings table */
	public function update_staff_option($option_name,$option_value){
		$query = "select `option_value` from `".$this->rzvy_staff_settings."` where `option_name`='".$option_name."' and `staff_id` = '".$this->staff_id."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$option_value = htmlspecialchars(htmlentities($option_value), ENT_QUOTES);
			$query = "update `".$this->rzvy_staff_settings."` set `option_value`='".$option_value."' where `option_name`='".$option_name."' and `staff_id` = '".$this->staff_id."'";
		}else{
			$option_value = htmlspecialchars(htmlentities($option_value), ENT_QUOTES);
			$query = "INSERT INTO `".$this->rzvy_staff_settings."` (`id`, `option_name`, `option_value`, `staff_id`) VALUES (NULL, '".$option_name."', '".$option_value."', '".$this->staff_id."');";
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* Function to update option value in settings table */
	public function update_allstaff_option($option_name,$option_value){
		$query = "select `option_value` from `".$this->rzvy_staff_settings."` where `option_name`='".$option_name."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$option_value = htmlspecialchars(htmlentities($option_value), ENT_QUOTES);
			$query = "update `".$this->rzvy_staff_settings."` set `option_value`='".$option_value."' where `option_name`='".$option_name."'";
		}else{
			$option_value = htmlspecialchars(htmlentities($option_value), ENT_QUOTES);
			$query = "INSERT INTO `".$this->rzvy_staff_settings."` (`id`, `option_name`, `option_value`) VALUES (NULL, '".$option_name."', '".$option_value."');";
		}
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	public function get_staffid_from_orderid($order_id){
		$query = "SELECT * FROM `rzvy_bookings` where `order_id`='".$order_id."'";
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_array($result);
			return $value['staff_id'];
		}else{
			return "";
		}
	}
	
	public function get_invoice_data($orderid){
		$query = "SELECT * FROM `rzvy_bookings` as `b`, `rzvy_customer_orderinfo` as `o`, `rzvy_payments` as `p` where `b`.`order_id`=`o`.`order_id` and `b`.`order_id`=`p`.`order_id` and `b`.`order_id`='".$orderid."' limit 1";
 		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	public function get_service_category($service_id){
		$query = "SELECT `s`.`title`, `c`.`cat_name` FROM `rzvy_categories` as `c`, `rzvy_services` as `s` where `c`.`id`=`s`.`cat_id` and `s`.`id`='".$service_id."'";
 		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	public function get_addon_name($addonid){
		$query = "select `title` from `rzvy_addons` where `id`='".$addonid."'";
		$result=mysqli_query($this->conn,$query);
		$value=mysqli_fetch_array($result);
		return $value['title'];
	}
	
	public function get_staff_name($id){
		$query = "select * from `rzvy_staff` where `id`='".$id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
		
	public function get_customer_name($id){
		$query = "select * from `rzvy_customers` where `id`='".$id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	public function get_admin_name($id){
		$query = "select * from `rzvy_admins` where `id`='".$id."'";
		$result=mysqli_query($this->conn,$query);
		return $result;
	}
	
	/* public function check_datetime_already_not_booked($startdatetime, $enddatetime, $staff_id){
		if($staff_id!='0' && is_numeric($staff_id)){
			$query="select `order_id`, `booking_datetime`, `booking_end_datetime` from `".$this->rzvy_bookings."` where `booking_datetime`>='".$startdatetime."' and `booking_end_datetime`<='".$enddatetime."' and (`booking_status`='pending' OR `booking_status`='confirmed' OR `booking_status`='confirmed_by_staff' OR `booking_status`='rescheduled_by_you' OR `booking_status`='rescheduled_by_customer' OR `booking_status`='rescheduled_by_staff') and `staff_id`='".$staff_id."' group by `order_id`,`booking_datetime`, `booking_end_datetime`";
		}else{
			$query="select `order_id`, `booking_datetime`, `booking_end_datetime` from `".$this->rzvy_bookings."` where `booking_datetime`>='".$startdatetime."' and `booking_end_datetime`<='".$enddatetime."' and (`booking_status`='pending' OR `booking_status`='confirmed' OR `booking_status`='confirmed_by_staff' OR `booking_status`='rescheduled_by_you' OR `booking_status`='rescheduled_by_customer' OR `booking_status`='rescheduled_by_staff') group by `order_id`, `booking_datetime`, `booking_end_datetime`";
		}
		$result=mysqli_query($this->conn,$query);
		if(mysqli_num_rows($result)>0){
			return false;
		}else{
			return true;
		}
	} */
	public function rzvy_currency_position($rzvy_symbol,$rzvy_position,$rzvy_rate){
		if($rzvy_position=='A'){
			return $rzvy_rate.' '.$rzvy_symbol;
		}else{
			return $rzvy_symbol.' '.$rzvy_rate;
		}
	}
}
}
?>