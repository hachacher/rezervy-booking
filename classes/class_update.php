<?php 
if (!class_exists('rzvy_update')){
class rzvy_update{
	/* Function for update version option value */
	public function rzvy_update_version_option($version,$conn){
		$res = mysqli_query($conn, "select `option_value` from `rzvy_settings` where `option_name` = 'rzvy_version'");
		if($res){
			if(mysqli_num_rows($res)>0){
				$result = mysqli_query($conn, "update `rzvy_settings` set `option_value`='".$version."' where `option_name` = 'rzvy_version'");
			}else{
				$result = mysqli_query($conn, "insert into `rzvy_settings` (`id`, `option_name`, `option_value`) VALUES (NULL, 'rzvy_version', '".$version."')");
			}
		}else{
			$result=mysqli_query($conn, "insert into `rzvy_settings` (`id`, `option_name`, `option_value`) VALUES (NULL, 'rzvy_version', '".$version."')");
		}
		return $result;
    }
	
	/* Function for get version option value */
	public function rzvy_get_version_option($conn){
		$query = "select `option_value` from `rzvy_settings` where `option_name` = 'rzvy_version'";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0){
			$settings_data = mysqli_fetch_array($result);
			return $settings_data["option_value"];
		}else{
			return "1.0";
		}
    }
    
    /* Function to get option value from settings table */
	public function rzvy_get_option_update($conn,$option_name){
		$query = "select `option_value` from `rzvy_settings` where `option_name`='".$option_name."'";
		$result=mysqli_query($conn,$query);
		if(mysqli_num_rows($result)>0){
			$value=mysqli_fetch_array($result);
			return $value['option_value'];
		}else{
			return "";
		}
	}
	
	/* Function for version update */
	public function rzvy_version_update($conn){
		$current_version = $this->rzvy_get_version_option($conn);
		
		/** Version 1.1 */
		if($current_version<1.1){
			
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_cs_bfls"=>"default",
								"rzvy_bookingform_bg"=>"default",
								"rzvy_cs_admin_dash"=>"default",
							  );
			$this->update_admin_setting_options($conn, $settings_array);
			
			/* Execute version update query */
			$this->rzvy_update_version_option("1.1",$conn);
		}
		
		/** Version 1.2 */
		if($current_version<1.2){
			
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_default_language_on_page_load"=>"",
								"rzvy_default_country_code"=>"",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);
			
			/* Execute version update query */
			$this->rzvy_update_version_option("1.2",$conn);
		}
		/** Version 1.3 */
		if($current_version<1.3){
			try { mysqli_query($conn, "ALTER TABLE `rzvy_categories` ADD `image` VARCHAR(255) NOT NULL;"); } catch (Exception $e) {}
			try { mysqli_query($conn, "ALTER TABLE `rzvy_services` ADD `locations` LONGTEXT NOT NULL;"); } catch (Exception $e) {}
						
			/* Execute version update query */
			$this->rzvy_update_version_option("1.3",$conn);
		}
		/** Version 1.4 */
		if($current_version<1.4){		
			mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rzvy_gc_bookingdetail` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `order_id` int(11) NOT NULL,
								  `gc_eventid` varchar(2000) NOT NULL,
								  PRIMARY KEY (`id`)
								)");	
			mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rzvy_service_addons` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `addon_id` int(11) NOT NULL,
								  `service_id` int(11) NOT NULL,
								  PRIMARY KEY (`id`)
								)");
			/** Update into table queries below **/
			$update_array = array(
								"rzvy_endtimeslot_selection_status"=>"N",
							  );
			$settings_array = array(
								"rzvy_gc_status"=>"N",
								"rzvy_gc_twowaysync"=>"N",
								"rzvy_gc_clientid"=>"",
								"rzvy_gc_clientsecret"=>"",
								"rzvy_gc_accesstoken"=>"",
								"rzvy_gc_calendarid"=>"primary",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);
			$this->update_admin_setting_options($conn, $update_array);
			
			/* Execute version update query */
			$this->rzvy_update_version_option("1.4",$conn);
		}
		/** Version 1.5 */
		if($current_version<1.5){
			/* Execute version update query */
			$this->rzvy_update_version_option("1.5",$conn);
		}
		/** Version 1.6 */
		if($current_version<1.6){
			try { mysqli_query($conn, "ALTER TABLE `rzvy_addons` ADD `max_limit` INT NOT NULL DEFAULT '1' AFTER `description`;"); } catch (Exception $e) {}
			/* Execute version update query */
			$this->rzvy_update_version_option("1.6",$conn);
		}
		/** Version 1.7 */
		if($current_version<1.7){
			mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rzvy_staff_settings` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `option_name` text NOT NULL,
								  `option_value` longtext NOT NULL,
								  `staff_id` int(11) NOT NULL,
								  PRIMARY KEY (`id`)
								);");
			mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rzvy_staff_gc_bookingdetail` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `order_id` int(11) NOT NULL,
								  `gc_eventid` varchar(2000) NOT NULL,
								  PRIMARY KEY (`id`)
								);");
								
			$settings_array = array(
								"rzvy_booking_first_selection_as"=>"allcategories",
								"rzvy_minmum_cart_value_to_pay"=>"0",
								
								"rzvy_bank_transfer_payment_status"=>"N",
								"rzvy_bank_transfer_bank_name"=>"",
								"rzvy_bank_transfer_account_name"=>"",
								"rzvy_bank_transfer_account_number"=>"",
								"rzvy_bank_transfer_branch_code"=>"",
								"rzvy_bank_transfer_ifsc_code"=>"",
								
								"rzvy_partial_deposite_status"=>"N",
								"rzvy_partial_deposite_type"=>"flat",
								"rzvy_partial_deposite_value"=>"0",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);				
			
			mysqli_query($conn, "ALTER TABLE `rzvy_bookings` CHANGE `staff_id` `staff_id` VARCHAR(500) NOT NULL;");			
			mysqli_query($conn, "ALTER TABLE `rzvy_addons` ADD `min_limit` INT NOT NULL DEFAULT '1' AFTER `max_limit`;");			
			mysqli_query($conn, "ALTER TABLE `rzvy_services` ADD `badge` ENUM('Y','N') NOT NULL DEFAULT 'N' AFTER `locations`, ADD `badge_text` VARCHAR(10) NOT NULL AFTER `badge`;");	
			mysqli_query($conn, "ALTER TABLE `rzvy_payments` ADD `partial_deposite` DOUBLE NOT NULL AFTER `refer_discount_id`;");	
			
			/* Execute version update query */
			$this->rzvy_update_version_option("1.7",$conn);
		}
		
		/** Version 1.8 */
		if($current_version<1.8){
			/* Execute version update query */
			$this->rzvy_update_version_option("1.8",$conn);
		}
		
		/** Version 1.9 */
		if($current_version<1.9){
			/* Execute version update query */
			$this->rzvy_update_version_option("1.9",$conn);
		}
		
		/** Version 2.0 */
		if($current_version<2.0){
			mysqli_query($conn, "ALTER TABLE `rzvy_categories` ADD `position` INT NOT NULL AFTER `image`;");	
			mysqli_query($conn, "ALTER TABLE `rzvy_services` ADD `pos_accordingcat` INT NOT NULL AFTER `badge_text`;");	
			mysqli_query($conn, "ALTER TABLE `rzvy_services` ADD `pos_accordingser` INT NOT NULL AFTER `pos_accordingcat`;");	
			mysqli_query($conn, "ALTER TABLE `rzvy_service_addons` ADD `position` INT NOT NULL AFTER `service_id`;");	
			
			/* Execute version update query */
			$this->rzvy_update_version_option("2.0",$conn);
		}
		
		/** Version 2.1 */
		if($current_version<2.1){
			/* Execute version update query */
			$this->rzvy_update_version_option("2.1",$conn);
		}
		
		/** Version 2.2 */
		if($current_version<2.2){
			/* Execute version update query */
			$this->rzvy_update_version_option("2.2",$conn);
		}
		
		/** Version 2.3 */
		if($current_version<2.3){
			
			if(file_exists(dirname(dirname(__FILE__)).'/uploads/images/1615478128.png')){
				unlink(dirname(dirname(__FILE__)).'/uploads/images/1615478128.png');
			}
			if(file_exists(dirname(dirname(__FILE__)).'/uploads/images/1615478307.jpeg')){
				unlink(dirname(dirname(__FILE__)).'/uploads/images/1615478307.jpeg');
			}
			if(file_exists(dirname(dirname(__FILE__)).'/uploads/images/1615478324.jpeg')){
				unlink(dirname(dirname(__FILE__)).'/uploads/images/1615478324.jpeg');
			}
			
			/* Execute version update query */
			$this->rzvy_update_version_option("2.3",$conn);
		}
		
		/** Version 2.4 */
		if($current_version<2.4){
			/* Execute version update query */
			$this->rzvy_update_version_option("2.4",$conn);
		}
		
		/** Version 2.5 */
		if($current_version<2.5){
			mysqli_query($conn, "ALTER TABLE `rzvy_addons` ADD `duration` INT NOT NULL AFTER `min_limit`; ");
			mysqli_query($conn, "ALTER TABLE `rzvy_addons` ADD `badge` ENUM('Y','N') NOT NULL DEFAULT 'N' AFTER `duration`, ADD `badge_text` VARCHAR(10) NOT NULL AFTER `badge`;");	
			mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rzvy_loyalty_points` (
								  `id` int(11) NOT NULL AUTO_INCREMENT,
								  `order_id` int(11) NOT NULL,
                                  `customer_id` int(11) NOT NULL,
                                  `status` enum('U','A') NOT NULL COMMENT 'U=Used for,A=Added for',
                                  `points` double NOT NULL,
                                  `available_points` double NOT NULL,
                                  `lastmodify` timestamp NOT NULL DEFAULT current_timestamp(),
								  PRIMARY KEY (`id`)
								);");
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_multiservice_status"=>"N",
								"rzvy_allow_loyalty_points_status"=>"N",
								"rzvy_perbooking_loyalty_points"=>"0",
								"rzvy_perbooking_loyalty_point_value"=>"0",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);

			/* Execute version update query */
			$this->rzvy_update_version_option("2.5",$conn);
		}
		/** Version 2.6 */
		if($current_version<2.6){
			
			/* Execute version update query */
			$this->rzvy_update_version_option("2.6",$conn);
		}
		/** Version 2.7 */
		if($current_version<2.7){
			
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_auto_scroll_each_module_status"=>"Y",
								"rzvy_timeslots_display_method"=>"S",
								"rzvy_loyalty_points_reward_method"=>"F",
								"rzvy_loyalty_points_per_spend_based"=>"0.1",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);

			/* Execute version update query */
			$this->rzvy_update_version_option("2.7",$conn);
		}
		/** Version 2.8 */
		if($current_version<2.8){
			$timeslots_display_method = $this->rzvy_get_option_update($conn,'timeslots_display_method');
			$loyalty_points_rewardtype = $this->rzvy_get_option_update($conn,'loyalty_points_rewardtype');
			$loyalty_points_per_spend_based = $this->rzvy_get_option_update($conn,'loyalty_points_per_spend_based');
			$allow_loyalty_points_status = $this->rzvy_get_option_update($conn,'allow_loyalty_points_status');
			$perbooking_loyalty_points = $this->rzvy_get_option_update($conn,'perbooking_loyalty_points');
			$perbooking_loyalty_point_value = $this->rzvy_get_option_update($conn,'perbooking_loyalty_point_value');
			$minmum_cart_value_to_pay = $this->rzvy_get_option_update($conn,'minmum_cart_value_to_pay');
			$bank_transfer_payment_status = $this->rzvy_get_option_update($conn,'bank_transfer_payment_status');
			
			/** Update/insert into table queries below **/
			$settings_array = array(
								"rzvy_timeslots_display_method"=>$timeslots_display_method,
								"rzvy_loyalty_points_reward_method"=>$loyalty_points_rewardtype,
								"rzvy_loyalty_points_per_spend_based"=>$loyalty_points_per_spend_based,
								"rzvy_allow_loyalty_points_status"=>$allow_loyalty_points_status,
								"rzvy_perbooking_loyalty_points"=>$perbooking_loyalty_points,
								"rzvy_perbooking_loyalty_point_value"=>$perbooking_loyalty_point_value,
								"rzvy_minmum_cart_value_to_pay"=>$minmum_cart_value_to_pay,
								"rzvy_bank_transfer_payment_status"=>$bank_transfer_payment_status,
								"rzvy_hotjar_tracking_code"=>"",
								"rzvy_fbpixel_tracking_code"=>"",
								"rzvy_ga_tracking_code"=>"",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);

			/* Execute version update query */
			$this->rzvy_update_version_option("2.8",$conn);
		}
		/** Version 2.9 */
		if($current_version<2.9){
			mysqli_query($conn, "ALTER TABLE `rzvy_customers` ADD `dob` DATE NOT NULL DEFAULT '2020-01-01' AFTER `refferral_code`;");	
			mysqli_query($conn, "ALTER TABLE `rzvy_customers` ADD `internal_notes` TEXT NOT NULL AFTER `dob`;");	
			mysqli_query($conn, "ALTER TABLE `rzvy_customer_orderinfo` ADD `c_dob` DATE NOT NULL DEFAULT '2020-01-01' AFTER `c_zip`;");	
			mysqli_query($conn, "ALTER TABLE `rzvy_customer_orderinfo` ADD `c_notes` TEXT NOT NULL AFTER `c_dob`;");		
			
			/** Add templates queries below **/
			$this->add_referral_template($conn);
			$this->add_birthday_template($conn);
			
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_en_ff_dob_status"=>"Y",
								"rzvy_en_ff_dob_optional"=>"Y",
								"rzvy_en_ff_notes_status"=>"Y",
								"rzvy_en_ff_notes_optional"=>"Y",
								
								"rzvy_g_ff_dob_status"=>"Y",
								"rzvy_g_ff_dob_optional"=>"Y",
								"rzvy_g_ff_notes_status"=>"Y",
								"rzvy_g_ff_notes_optional"=>"N",
								
								"rzvy_reminder_on"=>"buffer_time",
								"rzvy_no_of_loyalty_point_as_birthday_gift"=>"20",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);
			
			/* Execute version update query */
			$this->rzvy_update_version_option("2.9",$conn);
		}
		/** Version 2.91 */
		if($current_version<2.91){
			
			/* Execute version update query */
			$this->rzvy_update_version_option("2.91",$conn);
		}
		/** Version 3.0 */
		if($current_version<3.0){
			mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rzvy_staff_advance_schedule` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `startdate` date NOT NULL,
			  `enddate` date NOT NULL,
			  `starttime` time NOT NULL,
			  `endtime` time NOT NULL,
			  `status` enum('Y','N') NOT NULL,
			  `staff_id` int(11) NOT NULL,
			  `no_of_booking` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			);");
			mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rzvy_staff_advance_schedule_breaks` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `schedule_id` int(11) NOT NULL,
			  `staff_id` int(11) NOT NULL,
			  `startdate` date NOT NULL,
			  `enddate` date NOT NULL,
			  `break_start` time NOT NULL,
			  `break_end` time NOT NULL,
			  PRIMARY KEY (`id`)
			);");
			mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rzvy_parent_categories` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `cat_name` varchar(255) NOT NULL,
			  `status` enum('Y','N') NOT NULL,
			  `image` varchar(255) NOT NULL,
			  `position` int(11) NOT NULL,
			  `linked_subcat` text NOT NULL,
			  PRIMARY KEY (`id`)
			);");	
			mysqli_query($conn, "ALTER TABLE `rzvy_coupons` ADD `front_status` ENUM('Y','N') NOT NULL DEFAULT 'Y' AFTER `status`;");	
			
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_show_parentcategory_image"=>"Y",
								"rzvy_show_category_image"=>"Y",
								"rzvy_show_service_image"=>"Y",
								"rzvy_show_addon_image"=>"Y",
								"rzvy_show_staff_image"=>"Y",
								"rzvy_show_cancelled_appointments"=>"Y",
								"rzvy_single_staff_autotrigger_status"=>"N",
								"rzvy_stepview_alignment"=>"left",
								"rzvy_services_listing_view"=>"G",
								"rzvy_g_ff_email_status"=>"Y",
								"rzvy_g_ff_email_optional"=>"Y",
								"rzvy_parent_category"=>"N",
								"rzvy_cookiesconcent_status"=>"Y",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);
			
			/* Execute version update query */
			$this->rzvy_update_version_option("3.0",$conn);
		}
		/** Version 3.1 */
		if($current_version<3.1){
			
			/* Execute version update query */
			$this->rzvy_update_version_option("3.1",$conn);
		}
		/** Version 3.2 */
		if($current_version<3.2){
			mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rzvy_breaks` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `break_date` date NOT NULL,
			  `starttime` time NOT NULL,
			  `endtime` time NOT NULL,
			  `staff_id` int(11) NOT NULL,
			  PRIMARY KEY (`id`)
			);");	
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_privacy_and_policy_link"=>"",
								"rzvy_showhide_pay_at_venue"=>"Y",
								"rzvy_book_with_datetime"=>"Y",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);
			
			/* Execute version update query */
			$this->rzvy_update_version_option("3.2",$conn);
		}
		/** Version 3.3 */
		if($current_version<3.3){
			mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rzvy_pos_bookings` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `parent_orderid` int(11) NOT NULL,
			  `booking_datetime` datetime NOT NULL,
			  `booking_end_datetime` datetime NOT NULL,
			  `order_date` date NOT NULL,
			  `cat_id` int(11) NOT NULL,
			  `service_id` int(11) NOT NULL,
			  `addons` longtext NOT NULL,
			  `staff_id` int(11) NOT NULL,
			  `service_rate` double NOT NULL,
			  `discount` double NOT NULL,
			  PRIMARY KEY (`id`)
			);");
			mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rzvy_pos_payments` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `parent_orderid` int(11) NOT NULL,
			  `pdeposite` double NOT NULL,
			  `sub_total` double NOT NULL,
			  `discount` double NOT NULL,
			  `cdiscount` double NOT NULL,
			  `rdiscount` double NOT NULL,
			  `lpdiscount` double NOT NULL,
			  `tax` double NOT NULL,
			  `net_total` double NOT NULL,
			  `payment_methods` longtext NOT NULL,
			  `last_modify` datetime NOT NULL,
			  PRIMARY KEY (`id`)
			);");
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_pos_payment_types"=>'a:2:{i:0;s:7:"Voucher";i:1;s:9:"Gift Card";}',
								"rzvy_w2s_sms_status"=>'N',
								"rzvy_w2s_api_key"=>'',
								"rzvy_w2s_api_secret"=>'',
								"rzvy_w2s_sender"=>'',
							  );
			$this->insert_admin_setting_options($conn, $settings_array);
			
			/* Execute version update query */
			$this->rzvy_update_version_option("3.3",$conn);
		}
		/** Version 3.4 */
		if($current_version<3.4){
			
			/* Execute version update query */
			$this->rzvy_update_version_option("3.4",$conn);
		}
		/** Version 3.5 */
		if($current_version<3.5){
			
			mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rzvy_roles` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `role` varchar(2000) NOT NULL,
			  `staff` varchar(2000) NOT NULL,
			  `permission` text NOT NULL,
			  `status` enum('Y','N') NOT NULL,
			  PRIMARY KEY (`id`)
			);");
			
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_birthdate_with_year"=>'N',
							  );
			$this->insert_admin_setting_options($conn, $settings_array);
			
			/* Execute version update query */
			$this->rzvy_update_version_option("3.5",$conn);
		}
		/** Version 3.6 */
		if($current_version<3.6){
			
			/* Execute version update query */
			$this->rzvy_update_version_option("3.6",$conn);
		}
		/** Version 3.7 */
		if($current_version<3.7){
			
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_cancel_feedback_sms_shortlink"=>SITE_URL,
								"rzvy_fontfamily"=>"Raleway",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);
			
			/* Execute version update query */
			$this->rzvy_update_version_option("3.7",$conn);
		}
		/** Version 3.8 */
		if($current_version<3.8){
			/* Execute version update query */
			$this->rzvy_update_version_option("3.8",$conn);
		}
		/* Version 3.9 */
		if($current_version<3.9){
			mysqli_query($conn,"INSERT INTO `rzvy_pos_payments` (`id`, `parent_orderid`, `pdeposite`, `sub_total`, `discount`, `cdiscount`, `rdiscount`, `lpdiscount`, `tax`, `net_total`, `payment_methods`, `last_modify`) VALUES (NULL, '0', '0', '0', '0', '0', '0', '0', '0', '0', '', '2021-09-22 09:00:00');");
			
			mysqli_query($conn,"CREATE TABLE IF NOT EXISTS `rzvy_services_packages` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `title` varchar(500) NOT NULL,
					  `image` varchar(500) NOT NULL,
					  `description` text NOT NULL,
					  `status` enum('Y','N') NOT NULL,
					  `duration` int(11) NOT NULL,
					  `rate` double NOT NULL,
					  `badge` enum('Y','N') NOT NULL,
					  `badge_text` varchar(10) NOT NULL,
					  `services` text NOT NULL,
					  `services_limit` longtext NOT NULL,
					  `addons_limit` longtext NOT NULL,
					  PRIMARY KEY (`id`)
					);");
			mysqli_query($conn,"CREATE TABLE IF NOT EXISTS `rzvy_sp_transactions` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `customer_id` int(11) NOT NULL,
					  `package_id` int(11) NOT NULL,
					  `parent_id` int(11) NOT NULL,
					  `order_id` int(11) NOT NULL,
					  `purchase_date` datetime NOT NULL DEFAULT current_timestamp(),
					  `expiry_date` datetime NOT NULL DEFAULT current_timestamp(),
					  `services` text NOT NULL,
					  `services_limit` longtext NOT NULL,
					  `addons_limit` longtext NOT NULL,
					  `services_remain` text NOT NULL,
					  `services_limit_remain` longtext NOT NULL,
					  `addons_limit_remain` longtext NOT NULL,
					  `sp_discount` double NOT NULL DEFAULT 0,
					  `status` enum('Y','N') NOT NULL,
					  PRIMARY KEY (`id`)
					);");
					
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_price_display"=>"Y",
								"rzvy_show_package_image"=>"Y",
								"rzvy_custom_css_bookingform"=>"",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);		
			/* Execute version update query */
			$this->rzvy_update_version_option("3.9",$conn);
		}

		/* Version 4.0 */
		if($current_version<4.0){					
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_success_modal_booking"=>"Y",
								"rzvy_customer_calendars"=>"Y",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);		
			/* Execute version update query */
			$this->rzvy_update_version_option("4.0",$conn);
		}
		
		/* Version 4.1 */
		if($current_version<4.1){					
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_staffs_time_today"=>"N",
								"rzvy_staffs_time_tomorrow"=>"N",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);		
			/* Execute version update query */
			$this->rzvy_update_version_option("4.1",$conn);			
		}
		/** Version 5.0 */
		if($current_version<5.0){
			/* Execute version update query */
			$this->rzvy_update_version_option("5.0",$conn);
			
		}
		
		/* Version 5.1 */
		if($current_version<5.1){	
			mysqli_query($conn, "ALTER TABLE `rzvy_coupons` ADD `coupon_start` DATE NOT NULL AFTER `front_status`;");
				
			/** Update into table queries below **/
			$settings_array = array(
								"rzvy_coupon_input_status"=>"N",
								"rzvy_currency_position"=>"B",
								"rzvy_referral_channels"=>"facebook,twitter,googleplus,whatsapp",
								"rzvy_customer_enable_calendars"=>"google,yahoo,outlook,ical",
								"rzvy_cancel_success_message"=>"PGgyIGNsYXNzPSIgZnMtMyBwYi1zbS00IHB5LTIgdGV4dC1zdWNjZXMgbXgtYXV0byByenZ5X2NhbmNlbF9kYXRhX21zZyI+PGkgY2xhc3M9ImZhIGZhLWNoZWNrLWNpcmNsZS1vIHRleHQtc3VjY2VzcyIgYXJpYS1oaWRkZW49InRydWUiPjwvaT4gWW91ciBhcHBvaW50bWVudCBpcyBjYW5jZWxsZWQgc3VjY2Vzc2Z1bGx5ITwvaDQ+",
							  );
			$this->insert_admin_setting_options($conn, $settings_array);		
			/* Execute version update query */
			$this->rzvy_update_version_option("5.1",$conn);
			echo '<div class="alert alert-success text-center" role="alert"><i class="fa fa-check-circle-o"></i>&nbsp;&nbsp;Version updated successfully, Please refresh page once.</div>';
		}
    }
	
	public function insert_admin_setting_options($conn, $settings_array){
		foreach($settings_array as $key => $value){
			/** Insert into table queries below **/
			$result=mysqli_query($conn, "select `option_value` from `rzvy_settings` where `option_name` = '".$key."'");
			if(mysqli_num_rows($result)==0){
				mysqli_query($conn, "insert into `rzvy_settings` (`id`, `option_name`, `option_value`) VALUES (NULL, '".$key."', '".$value."')");
			}
		}
	}
	
	public function insert_staff_setting_options($conn, $settings_array){
		$res=mysqli_query($conn, "select `id` from `rzvy_staff`");
		if(mysqli_num_rows($res)>0){
			while($val = mysqli_fetch_array($res)){
				$staffid = $val["id"];
				foreach($settings_array as $key => $value){
					/** Insert into table queries below **/
					$result=mysqli_query($conn, "select `option_value` from `rzvy_staff_settings` where `option_name` = '".$key."' and `staff_id`='".$staffid."'");
					if(mysqli_num_rows($result)==0){
						mysqli_query($conn, "insert into `rzvy_staff_settings` (`id`, `option_name`, `option_value`, `staff_id`) VALUES (NULL, '".$key."', '".$value."', '".$staffid."')");
					}
				}
			}
		}
	}
	
	public function update_admin_setting_options($conn, $settings_array){
		foreach($settings_array as $key => $value){
			mysqli_query($conn, "update `rzvy_settings` set `option_value` = '".$value."'  where `option_name` = '".$key."'");
		}
	}
	
	public function add_referral_template($conn){
		$result=mysqli_query($conn, "select `template` from `rzvy_templates` where `template` = 'referral'");
		if(mysqli_num_rows($result)==0){
			mysqli_query($conn, "INSERT INTO `rzvy_templates` (`id`, `template`, `subject`, `email_content`, `sms_content`, `template_for`, `email_status`, `sms_status`) VALUES (NULL, 'referral', 'Referral gift added to your account!', 'PGRpdiBzdHlsZT0ibWFyZ2luOiAwO3BhZGRpbmc6IDA7Zm9udC1mYW1pbHk6IEhlbHZldGljYSBOZXVlLCBIZWx2ZXRpY2EsIEhlbHZldGljYSwgQXJpYWwsIHNhbnMtc2VyaWY7Zm9udC1zaXplOiAxMDAlO2xpbmUtaGVpZ2h0OiAxLjY7Ym94LXNpemluZzogYm9yZGVyLWJveDsiPgkKCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGJsb2NrICFpbXBvcnRhbnQ7bWF4LXdpZHRoOiA2MDBweCAhaW1wb3J0YW50O21hcmdpbjogMCBhdXRvICFpbXBvcnRhbnQ7Y2xlYXI6IGJvdGggIWltcG9ydGFudDsiPgoJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4KCQkJPHRib2R5PgoJCQkJPHRyIHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPgoJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+CgkJCQkJCTxkaXYgc3R5bGU9InZlcnRpY2FsLWFsaWduOiB0b3A7ZmxvYXQ6IGxlZnQ7cGFkZGluZzoxNXB4O3dpZHRoOiAxMDAlO2JveC1zaXppbmc6IGJvcmRlci1ib3g7LXdlYmtpdC1ib3gtc2l6aW5nOiBib3JkZXItYm94O2NsZWFyOiBsZWZ0O3RleHQtYWxpZ246IGxlZnQ7Zm9udC1mYW1pbHk6IE1vbnRzZXJyYXQsIHNhbnMtc2VyaWY7Ij4KCQkJCQkJCXt7e2NvbXBhbnlfbmFtZX19fTxicj57e3tjb21wYW55X2FkZHJlc3N9fX08YnI+e3t7Y29tcGFueV9waG9uZX19fTxicj57e3tjb21wYW55X2VtYWlsfX19CgkJCQkJCTwvZGl2PgoJCQkJCTwvdGQ+CgkJCQkJPHRkIHN0eWxlPSJ3aWR0aDogNDAlO3ZlcnRpY2FsLWFsaWduOiB0b3A7ZmxvYXQ6IGxlZnQ7Ij4KCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4KCQkJCQkJCTxkaXYgc3R5bGU9IndpZHRoOiAxMzBweDtoZWlnaHQ6IDEwMCU7dmVydGljYWwtYWxpZ246IHRvcDttYXJnaW46IDBweCBhdXRvOyI+CgkJCQkJCQkJPGltZyBzdHlsZT0id2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7ZGlzcGxheTogaW5saW5lLWJsb2NrO2hlaWdodDogMTAwJTsiIHNyYz0ie3t7Y29tcGFueV9sb2dvfX19Ij4KCQkJCQkJCTwvZGl2PgoJCQkJCQk8L2Rpdj4KCQkJCQk8L3RkPgoJCQkJPC90cj4KCQkJCTx0cj4KCQkJCQk8dGQ+CgkJCQkJCTxkaXYgc3R5bGU9InBhZGRpbmc6IDI1cHggMzBweDtiYWNrZ3JvdW5kOiAjZmZmO2Zsb2F0OiBsZWZ0O3dpZHRoOiA5MCU7ZGlzcGxheTogYmxvY2s7Ij4KCQkJCQkJCTxkaXYgc3R5bGU9ImJvcmRlci1ib3R0b206IDFweCBzb2xpZCAjZTZlNmU2O2Zsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrOyI+CgkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7e2N1c3RvbWVyX25hbWV9fX0sPC9oNj48cCBzdHlsZT0ibWFyZ2luOiAxMHB4IDBweCAxNXB4OyI+PGZvbnQgY29sb3I9IiM2MDYwNjAiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Ij5Zb3VyIHJlZmVycmFsIGRpc2NvdW50IGNvdXBvbiBjcmVkaXQgdG8geW91ciBhY2NvdW50LiBZb3VyIGZyaWVuZCBqdXN0IGxlZnQgb3VyIGxvY2F0aW9uLiBUaGFuayB5b3Ugc28gbXVjaC4gWW91IGNhbiByZWZlciBtb3JlIHlvdXIgY29udGFjdHMgdG8gZ2V0IG1vcmUgZGlzY291bnQgY291cG9ucy4gVGhhbmtzPC9zcGFuPjwvZm9udD48L3A+PC9kaXY+CgkJCQkJCTwvZGl2PgoJCQkJCTwvdGQ+CgkJCQk8L3RyPgoJCQk8L3Rib2R5PgoJCTwvdGFibGU+Cgk8L2Rpdj4KPC9kaXY+', 'RGVhciB7e3tjdXN0b21lcl9uYW1lfX19LApZb3VyIHJlZmVycmFsIGRpc2NvdW50IGNvdXBvbiBjcmVkaXQgdG8geW91ciBhY2NvdW50LiBZb3VyIGZyaWVuZCBqdXN0IGxlZnQgb3VyIGxvY2F0aW9uLiBZb3UgY2FuIHJlZmVyIG1vcmUgeW91ciBjb250YWN0cyB0byBnZXQgbW9yZSBkaXNjb3VudCBjb3Vwb25zLiBUaGFua3M=', 'customer', 'Y', 'Y');");
		}
	}
	
	public function add_birthday_template($conn){
		$result=mysqli_query($conn, "select `template` from `rzvy_templates` where `template` = 'birthday'");
		if(mysqli_num_rows($result)==0){
			mysqli_query($conn, "INSERT INTO `rzvy_templates` (`id`, `template`, `subject`, `email_content`, `sms_content`, `template_for`, `email_status`, `sms_status`) VALUES
			(NULL, 'birthday', 'Happy Birthday!', 'PGRpdiBzdHlsZT0ibWFyZ2luOiAwO3BhZGRpbmc6IDA7Zm9udC1mYW1pbHk6IEhlbHZldGljYSBOZXVlLCBIZWx2ZXRpY2EsIEhlbHZldGljYSwgQXJpYWwsIHNhbnMtc2VyaWY7Zm9udC1zaXplOiAxMDAlO2xpbmUtaGVpZ2h0OiAxLjY7Ym94LXNpemluZzogYm9yZGVyLWJveDsiPgkKCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGJsb2NrICFpbXBvcnRhbnQ7bWF4LXdpZHRoOiA2MDBweCAhaW1wb3J0YW50O21hcmdpbjogMCBhdXRvICFpbXBvcnRhbnQ7Y2xlYXI6IGJvdGggIWltcG9ydGFudDsiPgoJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4KCQkJPHRib2R5PgoJCQkJPHRyIHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPgoJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+CgkJCQkJCTxkaXYgc3R5bGU9InZlcnRpY2FsLWFsaWduOiB0b3A7ZmxvYXQ6IGxlZnQ7cGFkZGluZzoxNXB4O3dpZHRoOiAxMDAlO2JveC1zaXppbmc6IGJvcmRlci1ib3g7LXdlYmtpdC1ib3gtc2l6aW5nOiBib3JkZXItYm94O2NsZWFyOiBsZWZ0O3RleHQtYWxpZ246IGxlZnQ7Zm9udC1mYW1pbHk6IE1vbnRzZXJyYXQsIHNhbnMtc2VyaWY7Ij4KCQkJCQkJCXt7e2NvbXBhbnlfbmFtZX19fTxicj57e3tjb21wYW55X2FkZHJlc3N9fX08YnI+e3t7Y29tcGFueV9waG9uZX19fTxicj57e3tjb21wYW55X2VtYWlsfX19CgkJCQkJCTwvZGl2PgoJCQkJCTwvdGQ+CgkJCQkJPHRkIHN0eWxlPSJ3aWR0aDogNDAlO3ZlcnRpY2FsLWFsaWduOiB0b3A7ZmxvYXQ6IGxlZnQ7Ij4KCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4KCQkJCQkJCTxkaXYgc3R5bGU9IndpZHRoOiAxMzBweDtoZWlnaHQ6IDEwMCU7dmVydGljYWwtYWxpZ246IHRvcDttYXJnaW46IDBweCBhdXRvOyI+CgkJCQkJCQkJPGltZyBzdHlsZT0id2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7ZGlzcGxheTogaW5saW5lLWJsb2NrO2hlaWdodDogMTAwJTsiIHNyYz0ie3t7Y29tcGFueV9sb2dvfX19Ij4KCQkJCQkJCTwvZGl2PgoJCQkJCQk8L2Rpdj4KCQkJCQk8L3RkPgoJCQkJPC90cj4KCQkJCTx0cj4KCQkJCQk8dGQ+CgkJCQkJCTxkaXYgc3R5bGU9InBhZGRpbmc6IDI1cHggMzBweDtiYWNrZ3JvdW5kOiAjZmZmO2Zsb2F0OiBsZWZ0O3dpZHRoOiA5MCU7ZGlzcGxheTogYmxvY2s7Ij4KCQkJCQkJCTxkaXYgc3R5bGU9ImJvcmRlci1ib3R0b206IDFweCBzb2xpZCAjZTZlNmU2O2Zsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrOyI+CgkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij48Zm9udCBjb2xvcj0iIzYwNjA2MCI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDsiPkhhcHB5IEJpcnRoZGF5IDwvc3Bhbj48L2ZvbnQ+e3t7Y3VzdG9tZXJfbmFtZX19fSE8L2g2PjxwIHN0eWxlPSJjb2xvcjogcmdiKDk2LCA5NiwgOTYpOyBmb250LXNpemU6IDE1cHg7IG1hcmdpbjogMTBweCAwcHg7IGZvbnQtd2VpZ2h0OiA2MDA7Ij48YnI+PC9wPjxmb250IGNvbG9yPSIjNjA2MDYwIj48c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4OyI+SXQncyB5b3VyIGJpcnRoZGF5LCB7e3tjdXN0b21lcl9uYW1lfX19ISBBbGwgb2YgdXMgYXQge3t7Y29tcGFueV9uYW1lfX19IGhvcGUgeW91IGhhdmUgYW4gaW5jcmVkaWJsZSB5ZWFyISBXZSByZXdhcmQgeW91IDIwIGxveWFsdHkgcG9pbnRzIGFzIGEgcHJlc2VudC4gPC9zcGFuPjwvZm9udD48YnI+PGZvbnQgY29sb3I9IiM2MDYwNjAiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Ij48L3NwYW4+PC9mb250PjxwIHN0eWxlPSJtYXJnaW46IDEwcHggMHB4IDE1cHg7Ij48Zm9udCBjb2xvcj0iIzYwNjA2MCI+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDsiPjxicj48L3NwYW4+PC9mb250PjwvcD48L2Rpdj4KCQkJCQkJPC9kaXY+CgkJCQkJPC90ZD4KCQkJCTwvdHI+CgkJCTwvdGJvZHk+CgkJPC90YWJsZT4KCTwvZGl2Pgo8L2Rpdj4=', 'SGFwcHkgQmlydGhkYXkge3t7Y3VzdG9tZXJfbmFtZX19fSEKSXQncyB5b3VyIGJpcnRoZGF5LCB7e3tjdXN0b21lcl9uYW1lfX19ISBBbGwgb2YgdXMgYXQge3t7Y29tcGFueV9uYW1lfX19IGhvcGUgeW91IGhhdmUgYW4gaW5jcmVkaWJsZSB5ZWFyISBXZSByZXdhcmQgeW91IDIwIGxveWFsdHkgcG9pbnRzIGFzIGEgcHJlc2VudC4g', 'customer', 'Y', 'Y');");
		}
	}
}
}
?>