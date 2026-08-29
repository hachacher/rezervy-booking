CREATE TABLE IF NOT EXISTS `rzvy_addons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `rate` double NOT NULL,
  `image` varchar(255) NOT NULL,
  `multiple_qty` enum('Y','N') NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `description` text NOT NULL,
  `max_limit` int(11) NOT NULL DEFAULT '1',
  `min_limit` int(11) NOT NULL DEFAULT '1',
  `duration` int(11) NOT NULL,
  `badge` enum('Y','N') NOT NULL DEFAULT 'N',
  `badge_text` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `country` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_appointment_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `rating` varchar(255) NOT NULL,
  `review` text NOT NULL,
  `review_datetime` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_block_off` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `pattern` varchar(255) NOT NULL,
  `blockoff_type` enum('fullday','custom') NOT NULL,
  `from_time` time NOT NULL,
  `to_time` time NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `booking_datetime` datetime NOT NULL,
  `booking_end_datetime` datetime NOT NULL,
  `order_date` date NOT NULL,
  `cat_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `addons` text NOT NULL,
  `booking_status` varchar(500) NOT NULL COMMENT 'pending, confirmed, confirmed_by_staff, rescheduled_by_customer, rescheduled_by_you, rescheduled_by_staff, cancelled_by_customer, rejected_by_you, rejected_by_staff, completed',
  `reschedule_reason` text NOT NULL,
  `reject_reason` text NOT NULL,
  `cancel_reason` text NOT NULL,
  `reminder_status` enum('Y','N') NOT NULL,
  `read_status` enum('R','U') NOT NULL,
  `lastmodified` datetime NOT NULL,
  `staff_id` varchar(500) NOT NULL,
  `service_rate` double NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `image` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(255) NOT NULL,
  `coupon_type` enum('percentage','flat') NOT NULL,
  `coupon_value` double NOT NULL,
  `coupon_expiry` date NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `front_status` enum('Y','N') NOT NULL DEFAULT 'Y',
  `coupon_start` date NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `country` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `refferral_code` varchar(2000) NOT NULL,
  `dob` DATE NOT NULL DEFAULT '2020-01-01',
  `internal_notes` TEXT NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_customer_orderinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `c_firstname` varchar(255) NOT NULL,
  `c_lastname` varchar(255) NOT NULL,
  `c_email` varchar(255) NOT NULL,
  `c_phone` varchar(20) NOT NULL,
  `c_address` text NOT NULL,
  `c_city` varchar(255) NOT NULL,
  `c_state` varchar(255) NOT NULL,
  `c_country` varchar(255) NOT NULL,
  `c_zip` varchar(20) NOT NULL,
  `c_dob` DATE NOT NULL DEFAULT '2020-01-01',
  `c_notes` TEXT NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_customer_referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `ref_customer_id` int(11) NOT NULL,
  `coupon` varchar(255) NOT NULL,
  `discount` double NOT NULL,
  `discount_type` varchar(255) NOT NULL,
  `used` enum('Y','N') NOT NULL,
  `completed` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `rating` varchar(10) NOT NULL,
  `review` text NOT NULL,
  `review_datetime` datetime NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_frequently_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fd_key` varchar(255) NOT NULL,
  `fd_label` varchar(255) NOT NULL,
  `fd_type` enum('percentage','flat') NOT NULL,
  `fd_value` double NOT NULL,
  `fd_description` text NOT NULL,
  `fd_status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_gc_bookingdetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `gc_eventid` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `payment_date` varchar(255) NOT NULL,
  `transaction_id` varchar(500) NOT NULL,
  `sub_total` double NOT NULL,
  `discount` double NOT NULL,
  `tax` double NOT NULL,
  `net_total` double NOT NULL,
  `fd_key` varchar(255) NOT NULL,
  `fd_amount` double NOT NULL,
  `lastmodified` datetime NOT NULL,
  `refer_discount` double NOT NULL,
  `refer_discount_id` varchar(255) NOT NULL,
  `partial_deposite` DOUBLE NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_refund_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `requested_on` datetime NOT NULL,
  `status` varchar(500) NOT NULL,
  `read_status` enum('U','R') NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `week_id` int(11) NOT NULL,
  `weekday_id` int(11) NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `offday` enum('Y','N') NOT NULL,
  `service_id` varchar(255) NOT NULL,
  `no_of_booking` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `duration` varchar(255) NOT NULL,
  `padding_before` varchar(255) NOT NULL,
  `padding_after` varchar(255) NOT NULL,
  `rate` double NOT NULL,
  `locations` longtext NOT NULL,
  `badge` ENUM('Y','N') NOT NULL DEFAULT 'N',
  `badge_text` VARCHAR(10) NOT NULL,
  `pos_accordingcat` int(11) NOT NULL,
  `pos_accordingser` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_service_addons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addon_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` text NOT NULL,
  `option_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `country` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_staff_breaks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `week_id` int(11) NOT NULL,
  `weekday_id` int(11) NOT NULL,
  `break_start` time NOT NULL,
  `break_end` time NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_staff_daysoff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `off_date` date NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_staff_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `week_id` int(11) NOT NULL,
  `weekday_id` int(11) NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `offday` enum('Y','N') NOT NULL,
  `staff_id` int(11) NOT NULL,
  `no_of_booking` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_staff_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_support_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `generated_by_id` int(11) NOT NULL,
  `ticket_title` varchar(1000) NOT NULL,
  `description` longtext NOT NULL,
  `generated_on` datetime NOT NULL,
  `generated_by` enum('admin','customer') NOT NULL,
  `status` enum('active','completed') NOT NULL,
  `read_status` enum('U','R') NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_support_ticket_discussions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `replied_by_id` int(11) NOT NULL,
  `reply` longtext NOT NULL,
  `replied_on` datetime NOT NULL,
  `replied_by` enum('admin','customer') NOT NULL,
  `read_status` enum('U','R') NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `template` varchar(255) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `email_content` longtext NOT NULL,
  `sms_content` longtext NOT NULL,
  `template_for` varchar(255) NOT NULL,
  `email_status` enum('Y','N') NOT NULL,
  `sms_status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_used_coupons_by_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `is_expired` enum('Y','N') NOT NULL,
  `used_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_staff_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` text NOT NULL,
  `option_value` longtext NOT NULL,
  `staff_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_staff_gc_bookingdetail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `gc_eventid` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_parent_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `image` varchar(255) NOT NULL,
  `position` int(11) NOT NULL,
  `linked_subcat` text NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_staff_advance_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  `status` enum('Y','N') NOT NULL,
  `staff_id` int(11) NOT NULL,
  `no_of_booking` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_staff_advance_schedule_breaks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `startdate` date NOT NULL,
  `enddate` date NOT NULL,
  `break_start` time NOT NULL,
  `break_end` time NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_breaks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `break_date` date NOT NULL,
  `starttime` time NOT NULL,
  `endtime` time NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_pos_bookings` (
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
);

CREATE TABLE IF NOT EXISTS `rzvy_pos_payments` (
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
);

CREATE TABLE IF NOT EXISTS `rzvy_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(2000) NOT NULL,
  `staff` varchar(2000) NOT NULL,
  `permission` text NOT NULL,
  `status` enum('Y','N') NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `rzvy_services_packages` (
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
);

CREATE TABLE IF NOT EXISTS `rzvy_sp_transactions` (
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
);