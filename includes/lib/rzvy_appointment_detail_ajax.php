<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_bookings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_addons.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_slots.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_payments.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_loyalty_points.php");

/* Create object of classes */
$obj_bookings = new rzvy_bookings();
$obj_bookings->conn = $conn;
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;
$obj_addons = new rzvy_addons();
$obj_addons->conn = $conn;
$obj_slots = new rzvy_slots();
$obj_slots->conn = $conn;
$obj_loyalty_points = new rzvy_loyalty_points();
$obj_loyalty_points->conn = $conn;
$obj_rzvy_payments = new rzvy_payments();
$obj_rzvy_payments->conn = $conn;

$rzvy_book_with_datetime = $obj_settings->get_option("rzvy_book_with_datetime");
$time_interval = $obj_settings->get_option('rzvy_timeslot_interval');
$rzvy_currency_symbol = $obj_settings->get_option('rzvy_currency_symbol');
$rzvy_currency_position = $obj_settings->get_option('rzvy_currency_position');
$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');
$advance_bookingtime = $obj_settings->get_option('rzvy_maximum_advance_booking_time');
$rzvy_allow_loyalty_points_status = $obj_settings->get_option('rzvy_allow_loyalty_points_status');
$rzvy_perbooking_loyalty_points = $obj_settings->get_option('rzvy_perbooking_loyalty_points');
$rzvy_perbooking_loyalty_point_value = $obj_settings->get_option('rzvy_perbooking_loyalty_point_value');

$activeExtAddons = array();
$counterAext = 0;
$rzvy_activated_addons = $obj_settings->get_option("rzvy_activated_addons");
if($rzvy_activated_addons != ""){
	$unserialized_activated_addons = unserialize($rzvy_activated_addons);
	$activeExtAddons = $unserialized_activated_addons;
	if(sizeof($activeExtAddons)>0){
		for($i=0;$i<sizeof($activeExtAddons);$i++){
			if($activeExtAddons[$i]["addon"] == 'pos' && $activeExtAddons[$i]["verified"] == "Y"){
				$counterAext++;
			}
		}
	}
}

/* Get appointment detail from order id ajax */
if(isset($_POST['get_appointment_detail'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$appointment_status = $obj_bookings->get_appointment_status(); 
	$appointment_details = $obj_bookings->get_appointment_status_and_datetime(); 
	$posbooking = $obj_bookings->check_isposbooking($order_id); 
	$counter_i = 0;
	?>
	<div class="row">
    	<div class="col-md-12">
    	    <a class="btn btn-link pull-right" href="<?php $invoice_uid = base64_encode($appointment_details["customer_id"]); $invoice_oid = base64_encode($order_id); echo SITE_URL."/invoice/?invoice=".base64_encode("###_____".$invoice_uid."_____&&&_____".$invoice_oid); ?>" target="_blank"><i class="fa fa-files-o"></i> <?php if(isset($rzvy_translangArr['download_invoice'])){ echo $rzvy_translangArr['download_invoice']; }else{ echo $rzvy_defaultlang['download_invoice']; } ?></a>
			<?php if($counterAext>0 && ($appointment_status != "rejected_by_you" && $appointment_status != "cancelled_by_customer" && $appointment_status != "rejected_by_staff" && $appointment_status != "completed")){ ?>
				<a class="btn btn-link pull-right" href="<?php echo SITE_URL."/addons/pos/pos.php?oid=".$order_id; ?>"><i class="fa fa-calendar-check-o"></i> <?php if(isset($rzvy_translangArr['edit_complete_appointment'])){ echo $rzvy_translangArr['edit_complete_appointment']; }else{ echo $rzvy_defaultlang['edit_complete_appointment']; } ?></a>
			<?php } ?>
    	</div>
	</div>
	<div class="rzvy-tabbable-panel">
		<div class="rzvy-tabbable-line">
			<ul class="nav nav-tabs">
			  <li class="nav-item active custom-nav-item">
				<a class="nav-link custom-nav-link rzvy_tab_view_nav_link rzvy_appointment_detail_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-calendar-check-o"></i> <?php if(isset($rzvy_translangArr['appointment_detail'])){ echo $rzvy_translangArr['appointment_detail']; }else{ echo $rzvy_defaultlang['appointment_detail']; } ?></a>
			  </li>
			  <?php $counter_i++; ?>
			  <?php if($appointment_details['sub_total']>0){ ?>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link rzvy_tab_view_nav_link rzvy_payment_detail_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-money"></i> <?php if(isset($rzvy_translangArr['payment_detail'])){ echo $rzvy_translangArr['payment_detail']; }else{ echo $rzvy_defaultlang['payment_detail']; } ?></a>
				  </li>
				  <?php $counter_i++; ?>
			  <?php } ?>
			  <li class="nav-item custom-nav-item">
				<a class="nav-link custom-nav-link rzvy_tab_view_nav_link rzvy_customer_detail_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-user"></i> <?php if(isset($rzvy_translangArr['customer_detail'])){ echo $rzvy_translangArr['customer_detail']; }else{ echo $rzvy_defaultlang['customer_detail']; } ?></a>
			  </li>
			  <?php $counter_i++; ?>
			  <li class="nav-item custom-nav-item <?php if($appointment_status == "confirmed" || $appointment_status == "confirmed_by_staff" || $appointment_status == "rejected_by_you" || $appointment_status == "rejected_by_staff" || $appointment_status == "cancelled_by_customer" || $appointment_status == "rescheduled_by_you" || $appointment_status == "rescheduled_by_staff" || $appointment_status == "completed"  || $appointment_status == "mark_as_noshow"){ echo "rzvy-hide"; } ?>">
				<a class="nav-link custom-nav-link rzvy_tab_view_nav_link rzvy_confirm_appointment_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-check"></i> <?php if(isset($rzvy_translangArr['confirm_appointment'])){ echo $rzvy_translangArr['confirm_appointment']; }else{ echo $rzvy_defaultlang['confirm_appointment']; } ?></a>
			  </li>
			  <?php $counter_i++; ?>
			  <li class="nav-item custom-nav-item <?php if($appointment_status == "pending" || $appointment_status == "rejected_by_you" || $appointment_status == "cancelled_by_customer" || $appointment_status == "rescheduled_by_you" || $appointment_status == "completed" || $appointment_status == "rescheduled_by_staff" || $appointment_status == "rejected_by_staff" || $appointment_status == "mark_as_noshow"){ echo "rzvy-hide"; } ?>">
				<a class="nav-link custom-nav-link rzvy_tab_view_nav_link rzvy_pending_appointment_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-info-circle"></i> <?php if(isset($rzvy_translangArr['mark_as_pending'])){ echo $rzvy_translangArr['mark_as_pending']; }else{ echo $rzvy_defaultlang['mark_as_pending']; } ?></a>
			  </li>
			  <?php $counter_i++; 
			  if($rzvy_book_with_datetime == "Y"){
			  ?>
			  <li class="nav-item custom-nav-item <?php if($appointment_status == "rejected_by_you" || $appointment_status == "cancelled_by_customer" || $appointment_status == "rejected_by_staff" || $appointment_status == "completed"  || $appointment_status == "mark_as_noshow"){ echo "rzvy-hide"; } ?>">
				<a class="nav-link custom-nav-link rzvy_tab_view_nav_link rzvy_reschedule_appointment_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-pencil"></i> <?php if(isset($rzvy_translangArr['reschedule_appointment'])){ echo $rzvy_translangArr['reschedule_appointment']; }else{ echo $rzvy_defaultlang['reschedule_appointment']; } ?></a>
			  </li>
			  <?php $counter_i++; } ?>
			  <li class="nav-item custom-nav-item <?php if($appointment_status == "rejected_by_you" || $appointment_status == "rejected_by_staff" || $appointment_status == "cancelled_by_customer" || $appointment_status == "completed"  || $appointment_status == "mark_as_noshow"){ echo "rzvy-hide"; } ?>">
				<a class="nav-link custom-nav-link rzvy_tab_view_nav_link rzvy_reject_appointment_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-ban"></i> <?php if(isset($rzvy_translangArr['reject_appointment'])){ echo $rzvy_translangArr['reject_appointment']; }else{ echo $rzvy_defaultlang['reject_appointment']; } ?></a>
			  </li>
			  <?php $counter_i++; 
			  
			  if($counterAext==0){
				  ?>
				  <li class="nav-item custom-nav-item <?php if($appointment_status == "rejected_by_you" || $appointment_status == "cancelled_by_customer" || $appointment_status == "rejected_by_staff" || $appointment_status == "completed"  || $appointment_status == "mark_as_noshow"){ echo "rzvy-hide"; } ?>">
					<a class="nav-link custom-nav-link rzvy_tab_view_nav_link rzvy_complete_appointment_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-calendar-check-o"></i> <?php if(isset($rzvy_translangArr['mark_as_completed'])){ echo $rzvy_translangArr['mark_as_completed']; }else{ echo $rzvy_defaultlang['mark_as_completed']; } ?></a>
				  </li>
				  <?php
			  }
			  $counter_i++; 
				  ?>
				  <li class="nav-item custom-nav-item <?php if((strtotime($appointment_details['booking_datetime'])<strtotime(date('Y-m-d H:i:s')) && ($appointment_status == "completed" || $appointment_status == "mark_as_noshow")) || ($appointment_status == "completed" || $appointment_status == "mark_as_noshow")){ echo "rzvy-hide"; } ?>">
					<a class="nav-link custom-nav-link rzvy_tab_view_nav_link rzvy_noshow_appointment_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-calendar-check-o"></i> <?php if(isset($rzvy_translangArr['mark_as_noshow'])){ echo $rzvy_translangArr['mark_as_noshow']; }else{ echo $rzvy_defaultlang['mark_as_noshow']; } ?></a>
				  </li>
				  <?php
			  $counter_i++;			  
			  ?>
			  <li class="nav-item custom-nav-item">
				<a class="nav-link custom-nav-link rzvy_tab_view_nav_link rzvy_feedback_appointment_link" data-tabno="<?php echo $counter_i; ?>" data-toggle="tab" data-id="<?php echo $order_id; ?>" href="javascript:void(0)"><i class="fa fa-star"></i> <?php if(isset($rzvy_translangArr['rating_and_review'])){ echo $rzvy_translangArr['rating_and_review']; }else{ echo $rzvy_defaultlang['rating_and_review']; } ?></a>
			  </li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane container" id="rzvy_appointment_detail">
				  
				</div>
				<div class="tab-pane container" id="rzvy_payment_detail">
				  
				</div>
				<div class="tab-pane container" id="rzvy_customer_detail">
				  
				</div>
				<div class="tab-pane container" id="rzvy_reschedule_appointment">
				  
				</div>
				<div class="tab-pane container" id="rzvy_reject_appointment">
				  
				</div>
				<div class="tab-pane container" id="rzvy_feedback_appointment">
				  
				</div>
		  </div>
		</div>
	</div>
	<?php
}

/* Get Appointment Detail tab */
else if(isset($_POST['appointment_detail_tab'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$all_detail = $obj_bookings->get_appointment_detail_tab();
	if(mysqli_num_rows($all_detail)>0){
		while($appt = mysqli_fetch_assoc($all_detail)){			
			$flag = true;
			$addons_detail = '';
			$unserialized_addons = unserialize($appt['addons']);
			foreach($unserialized_addons as $addon){
				$obj_addons->id = $addon['id'];
				$addon_name = $obj_addons->get_addon_name();
				if($flag){
					$addons_detail .= $addon['qty']." ".$addon_name." of ".$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$addon['rate']);
					$flag = false;
				}else{
					$addons_detail .= "<br/>".$addon['qty']." ".$addon_name." of ".$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$addon['rate']);
				}
			}
			
			$booking_datetime = date($rzvy_date_format, strtotime($appt['booking_datetime']))." ".date($rzvy_time_format, strtotime($appt['booking_datetime']));
			
			$booking_end_datetime = date($rzvy_date_format, strtotime($appt['booking_end_datetime']))." ".date($rzvy_time_format, strtotime($appt['booking_end_datetime']));
			
			$category_name = ucwords($appt['cat_name']);
			$service_name = ucwords($appt['title'])." of ".$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt['service_rate']);
			if(isset($rzvy_translangArr[$appt['booking_status']])){ $booking_status = $rzvy_translangArr[$appt['booking_status']]; }else{ $booking_status = $rzvy_defaultlang[$appt['booking_status']]; };
			
			$staff_name = "-";
			if($appt['staff_id']>0){
				$obj_bookings->staff_id = $appt['staff_id'];
				$staffdata = $obj_bookings->readone_staff();
				$staff_name = ucwords($staffdata['firstname']." ".$staffdata["lastname"]); 
			} 
			if($rzvy_book_with_datetime == "Y"){
				?>
			  <div class="row rzvy-mb-5">
				<div class="col-md-3">
					<b><?php if(isset($rzvy_translangArr['appointment_starts'])){ echo $rzvy_translangArr['appointment_starts']; }else{ echo $rzvy_defaultlang['appointment_starts']; } ?></b>
				</div>
				<div class="col-md-9">
					<?php echo $booking_datetime; ?>
				</div>
			  </div>
			  <div class="row rzvy-mb-5">
				<div class="col-md-3">
					<b><?php if(isset($rzvy_translangArr['appointment_ends'])){ echo $rzvy_translangArr['appointment_ends']; }else{ echo $rzvy_defaultlang['appointment_ends']; } ?></b>
				</div>
				<div class="col-md-9">
					<?php echo $booking_end_datetime; ?>
				</div>
			  </div>
				<?php 
				}
				?>
			  <div class="row rzvy-mb-5">
				<div class="col-md-3">
					<b><?php if(isset($rzvy_translangArr['status_ad'])){ echo $rzvy_translangArr['status_ad']; }else{ echo $rzvy_defaultlang['status_ad']; } ?></b>
				</div>
				<div class="col-md-9">
					<?php echo $booking_status; ?>
				</div>
			  </div>
			  <?php if($staff_name != "-"){ ?>
			  <div class="row rzvy-mb-5">
				<div class="col-md-3">
					<b><?php if(isset($rzvy_translangArr['staff_ad'])){ echo $rzvy_translangArr['staff_ad']; }else{ echo $rzvy_defaultlang['staff_ad']; } ?></b>
				</div>
				<div class="col-md-9">
					<?php echo $staff_name; ?>
				</div>
			  </div>
			  <?php } ?>
			  <div class="row rzvy-mb-5">
				<div class="col-md-3">
					<b><?php if(isset($rzvy_translangArr['category_ad'])){ echo $rzvy_translangArr['category_ad']; }else{ echo $rzvy_defaultlang['category_ad']; } ?></b>
				</div>
				<div class="col-md-9">
					<?php echo $category_name; ?>
				</div>
			  </div>
			  <div class="row rzvy-mb-5">
				<div class="col-md-3">
					<b><?php if(isset($rzvy_translangArr['service_ad'])){ echo $rzvy_translangArr['service_ad']; }else{ echo $rzvy_defaultlang['service_ad']; } ?></b>
				</div>
				<div class="col-md-9">
					<?php echo $service_name; ?>
				</div>
			  </div>
			  <?php if($addons_detail!=""){ ?>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['addons_ad'])){ echo $rzvy_translangArr['addons_ad']; }else{ echo $rzvy_defaultlang['addons_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $addons_detail; ?>
					</div>
				  </div>
			  <?php }
			  if($appt['c_notes']!=""){ ?>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['notes_ad'])){ echo $rzvy_translangArr['notes_ad']; }else{ echo $rzvy_defaultlang['notes_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $appt['c_notes']; ?>
					</div>
				  </div>
					<?php
			  }
				/* Services Package Purchases */
					$Rzvy_Hooks->do_action('services_package_purchase_appdetail', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang,$rzvy_currency_symbol,$order_id,$rzvy_currency_position));
				/* Services Package Purchases End */
			  
		}
		
		$posbooking = $obj_bookings->check_isposbooking($order_id);
		if(mysqli_num_rows($posbooking)>0){
			$fetch_posbookings = $obj_bookings->fetch_posbookings($order_id);
			if(mysqli_num_rows($fetch_posbookings)>0){
				while($pos = mysqli_fetch_array($fetch_posbookings)){
				
					$booking_datetime = date($rzvy_date_format, strtotime($pos['booking_datetime']))." ".date($rzvy_time_format, strtotime($pos['booking_datetime']));
					
					$booking_end_datetime = date($rzvy_date_format, strtotime($pos['booking_end_datetime']))." ".date($rzvy_time_format, strtotime($pos['booking_end_datetime']));
					
					$flag = true;
					$addons_detail = '';
					$unserialized_addons = unserialize($pos['addons']);
					foreach($unserialized_addons as $addon){
						$obj_addons->id = $addon['id'];
						$addon_name = $obj_addons->get_addon_name();
						if($flag){
							$addons_detail .= $addon['qty']." ".$addon_name." of ".$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$addon['rate']);
							$flag = false;
						}else{
							$addons_detail .= "<br/>".$addon['qty']." ".$addon_name." of ".$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$addon['rate']);
						}
					}
					$category_name = ucwords($pos['cat_name']);
					$service_name = ucwords($pos['title'])." of ".$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$pos['service_rate']);
					
					if (strpos($pos['booking_status'], 'you') !== false) {
						$booking_status = strtoupper(str_replace('you', 'customer', str_replace('_', ' ', $pos['booking_status'])));
					}else if (strpos($pos['booking_status'], 'customer') !== false) {
						$booking_status = strtoupper(str_replace('customer', 'you', str_replace('_', ' ', $pos['booking_status'])));
					}else {
						$booking_status = strtoupper(str_replace('_', ' ', $pos['booking_status']));
					} 
					$staff_name = "-";
					if($pos['staff_id']>0){
						$obj_bookings->staff_id = $pos['staff_id'];
						$staffdata = $obj_bookings->readone_staff();
						$staff_name = ucwords($staffdata['firstname']." ".$staffdata["lastname"]); 
					}
					?>
					<hr />
						<div class="row rzvy-mb-5">
						<div class="col-md-3">
							<b><?php if(isset($rzvy_translangArr['service_ad'])){ echo $rzvy_translangArr['service_ad']; }else{ echo $rzvy_defaultlang['service_ad']; } ?></b>
						</div>
						<div class="col-md-9">
							<?php echo $category_name." - ".$service_name; ?>
						</div>
					  </div>
					  <?php if($addons_detail!=""){ ?>
						  <div class="row rzvy-mb-5">
							<div class="col-md-3">
								<b><?php if(isset($rzvy_translangArr['addons_ad'])){ echo $rzvy_translangArr['addons_ad']; }else{ echo $rzvy_defaultlang['addons_ad']; } ?></b>
							</div>
							<div class="col-md-9">
								<?php echo $addons_detail; ?>
							</div>
						  </div>
					  <?php 
					  
						if($rzvy_book_with_datetime == "Y"){		  
							?>
							<div class="row rzvy-mb-5">
								<div class="col-md-3">
									<b><?php if(isset($rzvy_translangArr['appointment_starts'])){ echo $rzvy_translangArr['appointment_starts']; }else{ echo $rzvy_defaultlang['appointment_starts']; } ?></b>
								</div>
								<div class="col-md-9">
									<?php echo $booking_datetime; ?>
								</div>
							  </div>
							  <div class="row rzvy-mb-5">
								<div class="col-md-3">
									<b><?php if(isset($rzvy_translangArr['appointment_ends'])){ echo $rzvy_translangArr['appointment_ends']; }else{ echo $rzvy_defaultlang['appointment_ends']; } ?></b>
								</div>
								<div class="col-md-9">
									<?php echo $booking_end_datetime; ?>
								</div>
							  </div>
						<?php }
					  }
					  if($pos["sdisc"]>0){
						  ?>
							<div class="row rzvy-mb-5">
							<div class="col-md-3">
								<b><?php if(isset($rzvy_translangArr['special_discount_of'])){ echo $rzvy_translangArr['special_discount_of']; }else{ echo $rzvy_defaultlang['special_discount_of']; } ?></b>
							</div>
							<div class="col-md-9">
								<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$pos["sdisc"]); ?>
							</div>
						  </div>
						  <?php 
					  }
					  ?>
					<?php if($staff_name != "-"){ ?>
						  <div class="row rzvy-mb-5">
							<div class="col-md-3">
								<b><?php if(isset($rzvy_translangArr['staff_ad'])){ echo $rzvy_translangArr['staff_ad']; }else{ echo $rzvy_defaultlang['staff_ad']; } ?></b>
							</div>
							<div class="col-md-9">
								<?php echo $staff_name; ?>
							</div>
						  </div>
						  <?php } ?>
					  <?php 
				}
			}
		}
	}
}

/* Get Appointment Feedback Detail tab */
else if(isset($_POST['rzvy_feedback_appointment_tab'])){
	$order_id = $_POST['order_id'];
	$feedback_detail = $obj_bookings->get_appointment_rating($order_id);
	
	if(mysqli_num_rows($feedback_detail)>0){
		while($feedback = mysqli_fetch_assoc($feedback_detail)){	
			?>
			<div class="m-3">
			  <div class="row rzvy-mb-5">
				<div class="col-md-2">
					<b><?php if(isset($rzvy_translangArr['rating_ad'])){ echo $rzvy_translangArr['rating_ad']; }else{ echo $rzvy_defaultlang['rating_ad']; } ?></b>
				</div>
				<div class="col-md-9">
					<?php 
					if($feedback['rating']>0){
						for($star_i=0;$star_i<$feedback['rating'];$star_i++){ 
							?>
							<i class="fa fa-star rzvy_feedback_star_list" aria-hidden="true"></i>
							<?php 
						} 
						for($star_j=0;$star_j<(5-$feedback['rating']);$star_j++){ 
							?>
							<i class="fa fa-star-o rzvy_feedback_star_list" aria-hidden="true"></i>
							<?php 
						} 
					}else{ 
						?>
						<i class="fa fa-star-o rzvy_feedback_star_list" aria-hidden="true"></i>
						<i class="fa fa-star-o rzvy_feedback_star_list" aria-hidden="true"></i>
						<i class="fa fa-star-o rzvy_feedback_star_list" aria-hidden="true"></i>
						<i class="fa fa-star-o rzvy_feedback_star_list" aria-hidden="true"></i>
						<i class="fa fa-star-o rzvy_feedback_star_list" aria-hidden="true"></i>
						<?php 
					} 
					?>
				</div>
			  </div>
			  <div class="row rzvy-mb-5 mt-3">
				<div class="col-md-2">
					<b><?php if(isset($rzvy_translangArr['review_ad'])){ echo $rzvy_translangArr['review_ad']; }else{ echo $rzvy_defaultlang['review_ad']; } ?></b>
				</div>
				<div class="col-md-9">
					<?php echo ucfirst($feedback['review']); ?>
				</div>
			  </div>
			</div>
			<?php 
		}
	}else{ 
		?>
		<div class="row m-5">
			<div class="col-md-12">
				<center><b><?php if(isset($rzvy_translangArr['no_rating_review_error'])){ echo $rzvy_translangArr['no_rating_review_error']; }else{ echo $rzvy_defaultlang['no_rating_review_error']; } ?>.</b></center>
			</div>
		  </div>
		<?php 
	}
}

/* Get Payment Detail tab */
else if(isset($_POST['payment_detail_tab'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	
	$pospayments = $obj_bookings->fetch_pospayments($order_id);
	if(mysqli_num_rows($pospayments)>0){
		$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
		while($appt = mysqli_fetch_assoc($pospayments)){
			
			
			$payment_date = date($rzvy_date_format, strtotime($appt['last_modify']));
			?>
			  
			  <div class="row rzvy-mb-5">
				<div class="col-md-5">
					<b><?php if(isset($rzvy_translangArr['payment_date'])){ echo $rzvy_translangArr['payment_date']; }else{ echo $rzvy_defaultlang['payment_date']; } ?></b>
				</div>
				<div class="col-md-7">
					<?php echo $payment_date; ?>
				</div>
			  </div>
			  <div class="row rzvy-mb-5">
				<div class="col-md-5">
					<b><?php if(isset($rzvy_translangArr['online_pending_payment'])){ echo $rzvy_translangArr['online_pending_payment']; }else{ echo $rzvy_defaultlang['online_pending_payment']; } ?></b>
				</div>
				<div class="col-md-7">
					<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt["pdeposite"]); ?>
				</div>
			  </div>
			  <div class="row rzvy-mb-5">
				<div class="col-md-5">
					<b><?php if(isset($rzvy_translangArr['sub_total'])){ echo $rzvy_translangArr['sub_total']; }else{ echo $rzvy_defaultlang['sub_total']; } ?></b>
				</div>
				<div class="col-md-7">
					<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,($appt["sub_total"]+$appt["pdeposite"])); ?>
				</div>
			  </div>
			  <div class="row rzvy-mb-5">
				<div class="col-md-5">
					<b><?php if(isset($rzvy_translangArr['discount'])){ echo $rzvy_translangArr['discount']; }else{ echo $rzvy_defaultlang['discount']; } ?></b>
				</div>
				<div class="col-md-7">
					<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt["discount"]); ?>
				</div>
			  </div>
			  <div class="row rzvy-mb-5">
				<div class="col-md-5">
					<b><?php if(isset($rzvy_translangArr['coupon_discount'])){ echo $rzvy_translangArr['coupon_discount']; }else{ echo $rzvy_defaultlang['coupon_discount']; } ?></b>
				</div>
				<div class="col-md-7">
					<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt["cdiscount"]); ?>
				</div>
			  </div>
			  <div class="row rzvy-mb-5">
				<div class="col-md-5">
					<b><?php if(isset($rzvy_translangArr['referral_discount'])){ echo $rzvy_translangArr['referral_discount']; }else{ echo $rzvy_defaultlang['referral_discount']; } ?></b>
				</div>
				<div class="col-md-7">
					<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt["rdiscount"]); ?>
				</div>
			  </div>
			  <div class="row rzvy-mb-5">
				<div class="col-md-5">
					<b><?php if(isset($rzvy_translangArr['loyalty_points_amount'])){ echo $rzvy_translangArr['loyalty_points_amount']; }else{ echo $rzvy_defaultlang['loyalty_points_amount']; } ?></b>
				</div>
				<div class="col-md-7">
					<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt["lpdiscount"]); ?>
				</div>
			  </div>
			  <?php 
			  /* Services Package Discount Display */
				$Rzvy_Hooks->do_action('services_package_discount_bookingdetail_pos', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang,$rzvy_currency_symbol,$order_id,$rzvy_currency_position));
			  /* Services Package Discount Display End */
			  ?>
			  <div class="row rzvy-mb-5">
				<div class="col-md-5">
					<b><?php if(isset($rzvy_translangArr['tax_vat_gst'])){ echo $rzvy_translangArr['tax_vat_gst']; }else{ echo $rzvy_defaultlang['tax_vat_gst']; } ?></b>
				</div>
				<div class="col-md-7">
					<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt["tax"]); ?>
				</div>
			  </div>
			  <div class="row rzvy-mb-5">
				<div class="col-md-5">
					<b><?php if(isset($rzvy_translangArr['net_total'])){ echo $rzvy_translangArr['net_total']; }else{ echo $rzvy_defaultlang['net_total']; } ?></b>
				</div>
				<div class="col-md-7">
					<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt["net_total"]); ?>
				</div>
			  </div>
			  <div class="row rzvy-mb-5">
				<div class="col-md-5">
					<b><?php if(isset($rzvy_translangArr['payment_collection'])){ echo $rzvy_translangArr['payment_collection']; }else{ echo $rzvy_defaultlang['payment_collection']; } ?></b>
				</div>
				<div class="col-md-7">
					<?php $pmethod = @unserialize($appt["payment_methods"]);
					if($pmethod !== false) {
						foreach($pmethod as $p){
							echo $p["method"].": ".$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$p["amount"]).(isset($p["transaction_id"])?" (".$p["transaction_id"].")":"")."<br />";
						}
					} ?>
				</div>
			  </div>
			<?php
		}
	}else{
		
		$all_detail = $obj_bookings->get_payment_detail_tab();
		
		if(isset($rzvy_translangArr['points'])){ $pointslabel =  $rzvy_translangArr['points']; }else{ $pointslabel = $rzvy_defaultlang['points']; }
		
		if(mysqli_num_rows($all_detail)>0){
			while($appt = mysqli_fetch_assoc($all_detail)){	
				$payment_method = ucwords($appt['payment_method']);
				if($payment_method==ucwords('pay-at-venue')){
					if(isset($rzvy_translangArr['pay_at_venue'])){ $payment_method = $rzvy_translangArr['pay_at_venue']; }else{ $payment_method = $rzvy_defaultlang['pay_at_venue']; } 
				}
				$transaction_id = $appt['transaction_id'];
				$payment_date = date($rzvy_date_format, strtotime($appt['payment_date']));
				$sub_total = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt['sub_total']);
				$discount = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt['discount']);
				$refer_discountwcs = $appt['refer_discount'];
				$refer_discount = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt['refer_discount']);
				$refer_discountid = $appt['refer_discount_id'];
				$tax = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt['tax']);
				$partial_deposite = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt['partial_deposite']);
				$net_total = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt['net_total']);
				$fd_key = strtoupper($appt['fd_key']);
				$fd_amount = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$appt['fd_amount']);
			
				$referral_code = '';
				if(!is_numeric($refer_discountid)){
					$referral_code = $refer_discountid;
				}else{
					if($refer_discountid>0){
						$referral_code = $obj_bookings->get_referral_code_by_id($refer_discountid);
					}
				}
				
			
				/* Loyalty Points If Used */
				$loyalty_points_used =0;
				$loyalty_points_usedval =0;
				if($rzvy_allow_loyalty_points_status=='Y'){
					$usedpoints = $obj_loyalty_points->get_used_points_customer_by_order_id($order_id);
					if(isset($usedpoints) && $usedpoints!=''){
						$loyalty_points_used = $usedpoints;
						$loyalty_points_usedval = $rzvy_perbooking_loyalty_point_value*$usedpoints;
					}
				}
				$loyalty_points_amount = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$loyalty_points_usedval).'('.$loyalty_points_used.' '.$pointslabel.')';
				if($loyalty_points_used==0){
					$loyalty_points_amount = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$loyalty_points_usedval);
				}
				?>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['payment_method_ad'])){ echo $rzvy_translangArr['payment_method_ad']; }else{ echo $rzvy_defaultlang['payment_method_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $payment_method; ?>
					</div>
				  </div>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['payment_date'])){ echo $rzvy_translangArr['payment_date']; }else{ echo $rzvy_defaultlang['payment_date']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $payment_date; ?>
					</div>
				  </div>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['transaction_id_ad'])){ echo $rzvy_translangArr['transaction_id_ad']; }else{ echo $rzvy_defaultlang['transaction_id_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php if($transaction_id != ""){ echo $transaction_id; }else{ echo "-"; } ?>
					</div>
				  </div>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['sub_total'])){ echo $rzvy_translangArr['sub_total']; }else{ echo $rzvy_defaultlang['sub_total']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $sub_total; ?>
					</div>
				  </div>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['coupon_discount'])){ echo $rzvy_translangArr['coupon_discount']; }else{ echo $rzvy_defaultlang['coupon_discount']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $discount; ?>
					</div>
				  </div>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(!is_numeric($refer_discountid)){ ?>
							<?php if(isset($rzvy_translangArr['referral_discount_a'])){ echo $rzvy_translangArr['referral_discount_a']; }else{ echo $rzvy_defaultlang['referral_discount_a']; } ?>
						<?php } else{ ?>
							<?php if(isset($rzvy_translangArr['referral_coupon_discount'])){ echo $rzvy_translangArr['referral_coupon_discount']; }else{ echo $rzvy_defaultlang['referral_coupon_discount']; } ?>
						<?php } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $refer_discount; ?>
					</div>
				  </div>
				  
				  <?php if($refer_discountwcs>0){ ?>
				 <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(!is_numeric($refer_discountid)){ ?>
							<?php if(isset($rzvy_translangArr['referred_code'])){ echo $rzvy_translangArr['referred_code']; }else{ echo $rzvy_defaultlang['referred_code']; } ?>
						<?php } else{ ?>
							<?php if(isset($rzvy_translangArr['referral_code'])){ echo $rzvy_translangArr['referral_code']; }else{ echo $rzvy_defaultlang['referral_code']; } ?>
						<?php } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $referral_code; ?>
					</div>
				  </div>
				  <?php } ?>
				  
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['frequently_discount'])){ echo $rzvy_translangArr['frequently_discount']; }else{ echo $rzvy_defaultlang['frequently_discount']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php if($fd_key != ""){ if($fd_key=="weekly"){ echo $fq_weekly_label; }else if($fd_key=="bi weekly"){ echo $fq_biweekly_label; }else if($fd_key=="monthly"){ echo $fq_monthly_label; }else{ echo $fq_one_time_label; } echo " - ".$fd_amount; }else{ echo "-"; } ?>
					</div>
				  </div>
				  
				  <?php 
				  /* Services Package Discount Display */
					$Rzvy_Hooks->do_action('services_package_discount_bookingdetail', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang,$rzvy_currency_symbol,$order_id,$rzvy_currency_position));
				  /* Services Package Discount Display End */
				  ?>
				  
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['tax'])){ echo $rzvy_translangArr['tax']; }else{ echo $rzvy_defaultlang['tax']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $tax; ?>
					</div>
				  </div>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['partial_deposite_ad'])){ echo $rzvy_translangArr['partial_deposite_ad']; }else{ echo $rzvy_defaultlang['partial_deposite_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $partial_deposite; ?>
					</div>
				  </div>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['loyalty_points_amount'])){ echo $rzvy_translangArr['loyalty_points_amount']; }else{ echo $rzvy_defaultlang['loyalty_points_amount']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $loyalty_points_amount; ?>
					</div>
				  </div>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['net_total'])){ echo $rzvy_translangArr['net_total']; }else{ echo $rzvy_defaultlang['net_total']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $net_total; ?>
					</div>
				  </div>
				<?php
			}
		}
	}
}

/* Get Customer Detail tab */
else if(isset($_POST['customer_detail_tab'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$all_detail = $obj_bookings->get_customer_detail_tab();
	if(mysqli_num_rows($all_detail)>0){
		while($appt = mysqli_fetch_assoc($all_detail)){	
			$customer_internal_notes = "";
			if($appt['customer_id']>0){
				$obj_bookings->order_id = $order_id;
				$customer_internal_notes = $obj_bookings->get_internal_note_of_customer();
			}
			$customer_name = ucwords($appt['c_firstname']." ".$appt['c_lastname']);
			$customer_phone = $appt['c_phone'];
			$customer_email = $appt['c_email'];
			$customer_address = $appt['c_address'];
			$customer_city = $appt['c_city'];
			$customer_state = $appt['c_state'];
			$customer_country = $appt['c_country'];
			$customer_zip = $appt['c_zip'];
			$customer_dob = $appt['c_dob'];

			if($appt['customer_id']>0){
				?>
				<div class="row">
					<div class="col-md-12">
						<a class="btn btn-link pull-right mt-0 pt-0" data-toggle="collapse" data-target="#rzvy_edit_internal_note_modal" href="javascript:void(0)"><i class="fa fa-pencil-square-o"></i> <?php if(isset($rzvy_translangArr['edit_internal_note'])){ echo $rzvy_translangArr['edit_internal_note']; }else{ echo $rzvy_defaultlang['edit_internal_note']; } ?></a>
						<div id="rzvy_edit_internal_note_modal" class="collapse">
							<div class="input-group mb-3">
								<input type="hidden" id="rzvy_edit_internal_note_cid" value="<?php echo $appt['customer_id']; ?>" />
								<textarea class="form-control" rows="1" id="rzvy_edit_internal_note" placeholder="<?php if(isset($rzvy_translangArr['edit_internal_note'])){ echo $rzvy_translangArr['edit_internal_note']; }else{ echo $rzvy_defaultlang['edit_internal_note']; } ?>"><?php echo $customer_internal_notes; ?></textarea>
								<div class="input-group-append">
								  <a class="btn btn-success text-white" id="rzvy_edit_internal_note_btn"><i class="fa fa-check fa-lg"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php 
			}
			if($customer_name != "" && $customer_name != " "){ 
				?>
				<div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['name_ad'])){ echo $rzvy_translangArr['name_ad']; }else{ echo $rzvy_defaultlang['name_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $customer_name; ?>
					</div>
				</div>
				<?php 
			}
			if($customer_email != ""){
				?>
				<div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['email_ad'])){ echo $rzvy_translangArr['email_ad']; }else{ echo $rzvy_defaultlang['email_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $customer_email; ?>
					</div>
				</div>
				<?php 
			}
			if($customer_phone != ""){ 
				?>
				<div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['phone_ad'])){ echo $rzvy_translangArr['phone_ad']; }else{ echo $rzvy_defaultlang['phone_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $customer_phone; ?>
					</div>
				</div>
				<?php 
			}
			if($customer_dob != ""){ 
				?>
				<div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['birthdate_ad'])){ echo $rzvy_translangArr['birthdate_ad']; }else{ echo $rzvy_defaultlang['birthdate_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php if($obj_settings->get_option('rzvy_birthdate_with_year') == "Y"){ echo date("j F Y", strtotime($customer_dob)); }else{ echo date("j F", strtotime($customer_dob)); } ?>
					</div>
				</div>
				<?php 
			}
			if($customer_address != ""){ 
				?>
				<div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['address_ad'])){ echo $rzvy_translangArr['address_ad']; }else{ echo $rzvy_defaultlang['address_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $customer_address; ?>
					</div>
				</div>
				<?php 
			}
			if($customer_city != ""){ 
				?>
				<div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['city_ad'])){ echo $rzvy_translangArr['city_ad']; }else{ echo $rzvy_defaultlang['city_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $customer_city; ?>
					</div>
				</div>
				<?php 
			} 
			if($customer_state != ""){ 
				?>
				<div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['state_ad'])){ echo $rzvy_translangArr['state_ad']; }else{ echo $rzvy_defaultlang['state_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $customer_state; ?>
					</div>
				</div>
				<?php 
			}
			if($customer_country != ""){ 
				?>
				<div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['country_ad'])){ echo $rzvy_translangArr['country_ad']; }else{ echo $rzvy_defaultlang['country_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $customer_country; ?>
					</div>
				</div>
				<?php 
			}
			if($customer_zip != "" && $customer_zip != "N/A"){ 
				?>
				<div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['zip_ad'])){ echo $rzvy_translangArr['zip_ad']; }else{ echo $rzvy_defaultlang['zip_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $customer_zip; ?>
					</div>
				</div>
			<?php 
			}
			if($customer_internal_notes != ""){ 
				?>
				<div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['internal_notes_ad'])){ echo $rzvy_translangArr['internal_notes_ad']; }else{ echo $rzvy_defaultlang['internal_notes_ad']; } ?></b>
					</div>
					<div class="col-md-9">
						<?php echo $customer_internal_notes; ?>
					</div>
				</div>
				<?php 
			}
		}
	}
}

/* Get Reschedule Appointment detail tab */
else if(isset($_POST['rzvy_reschedule_appointment_tab'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$all_detail = $obj_bookings->get_reschedule_appointment_detail();
	$rzvy_endtimeslot_selection_status = $obj_settings->get_option('rzvy_endtimeslot_selection_status');
	
	if(mysqli_num_rows($all_detail)>0){
		while($appt = mysqli_fetch_assoc($all_detail)){
			$booking_datetime = $appt['booking_datetime'];
			$staff_id = $appt['staff_id'];
			$service_id = $appt['service_id'];
			$booking_date = date("Y-m-d", strtotime($booking_datetime));
			$booking_time = date("H:i:s", strtotime($booking_datetime));
			$booking_end_datetime = $appt['booking_end_datetime'];
			$booking_enddate = date("Y-m-d", strtotime($booking_end_datetime));
			$booking_endtime = date("H:i:s", strtotime($booking_end_datetime));
			$reschedule_reason = $appt['reschedule_reason'];
			?>
			  <input type="hidden" name="rzvy_appt_rs_sid" id="rzvy_appt_rs_sid" value="<?php echo $service_id; ?>" />
			  <input type="hidden" name="rzvy_appt_rs_staffid" id="rzvy_appt_rs_staffid" value="<?php echo $staff_id; ?>" />
			  <?php if($_SESSION['login_type'] == "staff") { ?>
				  <select name="rzvy_appt_rs_staff" id="rzvy_appt_rs_staff" style="display:none;"><option value="<?php echo $staff_id; ?>" selected></option></select>
			  <?php }else{ ?>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['staff_ad'])){ echo $rzvy_translangArr['staff_ad']; }else{ echo $rzvy_defaultlang['staff_ad']; } ?></b>
					</div>
					<div class="col-md-4">
						<select name="rzvy_appt_rs_staff" id="rzvy_appt_rs_staff" class="form-control">
							<option value="" <?php echo ($staff_id>0)?"":"selected" ?>><?php if(isset($rzvy_translangArr['no_staff'])){ echo $rzvy_translangArr['no_staff']; }else{ echo $rzvy_defaultlang['no_staff']; } ?></option>
							<?php 
							$get_all_active_staff = $obj_bookings->get_all_active_staff();
							while($staff_data = mysqli_fetch_array($get_all_active_staff)){
								$selected = ($staff_id>0)?"selected":"";
								echo '<option value="'.$staff_data["id"].'" '.$selected.'>'.ucwords($staff_data["firstname"].' '.$staff_data["lastname"]).'</option>';
							} 
							?>
						</select>
					</div>
				  </div>
			  <?php } ?>
			  <div class="row rzvy-mb-5">
				<div class="col-md-3">
					<b><?php if(isset($rzvy_translangArr['date_ad'])){ echo $rzvy_translangArr['date_ad']; }else{ echo $rzvy_defaultlang['date_ad']; } ?></b>
				</div>
				<div class="col-md-4">
					<input class="form-control" id="rzvy_appt_rs_date" name="rzvy_appt_rs_date" type="date" data-datetime="<?php echo $booking_datetime; ?>" data-oid="<?php echo $order_id; ?>" value="<?php echo $booking_date; ?>" required />
				</div>
			  </div>
			  <div class="row rzvy-mb-5">
				<div class="col-md-3">
					<b><?php if(isset($rzvy_translangArr['start_time_ad'])){ echo $rzvy_translangArr['start_time_ad']; }else{ echo $rzvy_defaultlang['start_time_ad']; } ?></b>
				</div>
				<div class="col-md-4">
					<select class="form-control rzvy_appt_rs_timeslot <?php /* if($rzvy_endtimeslot_selection_status == "Y"){ echo "endslot_selection_exist"; } */ ?>">
					</select>
				</div>
			  </div>
			  <?php 
			  /* if($rzvy_endtimeslot_selection_status == "Y"){
				  ?>
				  <div class="row rzvy-mb-5">
					<div class="col-md-3">
						<b><?php if(isset($rzvy_translangArr['end_time_ad'])){ echo $rzvy_translangArr['end_time_ad']; }else{ echo $rzvy_defaultlang['end_time_ad']; } ?></b>
					</div>
					<div class="col-md-4">
						<select class="form-control rzvy_appt_rs_endtimeslot">
							
						</select>
					</div>
				  </div>
			  <?php 
			  } else{  */
				  ?>
				  <input type="hidden" name="rzvy_appt_rs_endtimeslot" class="rzvy_appt_rs_endtimeslot" value="<?php echo $booking_endtime; ?>" />
				  <?php 
			  /* }  */
			  ?>
			  <div class="row rzvy-mb-5">
				<div class="col-md-3">
					<b><?php if(isset($rzvy_translangArr['reschedule_reason_ad'])){ echo $rzvy_translangArr['reschedule_reason_ad']; }else{ echo $rzvy_defaultlang['reschedule_reason_ad']; } ?></b>
				</div>
				<div class="col-md-9">
					<textarea class="form-control" id="rzvy_appt_rs_reason" name="rzvy_appt_rs_reason" placeholder="<?php if(isset($rzvy_translangArr['enter_reschedule_reason'])){ echo $rzvy_translangArr['enter_reschedule_reason']; }else{ echo $rzvy_defaultlang['enter_reschedule_reason']; } ?>"><?php echo $reschedule_reason; ?></textarea>
				</div>
			  </div>
			  <div class="row rzvy-mt-20">
				<div class="col-md-12">
					<a href="javascript:void(0)" data-id="<?php echo $order_id; ?>" class="btn btn-primary rzvy-fullwidth rzvy_appt_rs_now_btn"><?php if(isset($rzvy_translangArr['reschedule_now'])){ echo $rzvy_translangArr['reschedule_now']; }else{ echo $rzvy_defaultlang['reschedule_now']; } ?></a>
				</div>
			  </div>
			<?php
		}
	}
}

/* Get Reject Appointment detail tab */
else if(isset($_POST['rzvy_reject_appointment_tab'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$all_detail = $obj_bookings->get_reject_appointment_detail();
	
	if(mysqli_num_rows($all_detail)>0){
		while($appt = mysqli_fetch_assoc($all_detail)){
			$reject_reason = $appt['reject_reason'];
			?>
			  <div class="row rzvy-mb-5">
				<div class="col-md-3">
					<b><?php if(isset($rzvy_translangArr['reject_reason_ad'])){ echo $rzvy_translangArr['reject_reason_ad']; }else{ echo $rzvy_defaultlang['reject_reason_ad']; } ?></b>
				</div>
				<div class="col-md-9">
					<textarea class="form-control" id="rzvy_appt_reject_reason" name="rzvy_appt_reject_reason" placeholder="<?php if(isset($rzvy_translangArr['enter_reject_reason'])){ echo $rzvy_translangArr['enter_reject_reason']; }else{ echo $rzvy_defaultlang['enter_reject_reason']; } ?>"><?php echo $reject_reason; ?></textarea>
				</div>
			  </div>
			  <div class="row rzvy-mt-20">
				<div class="col-md-12">
					<a href="javascript:void(0)" data-id="<?php echo $order_id; ?>" class="btn btn-danger rzvy-fullwidth rzvy_appt_reject_now_btn"><?php if(isset($rzvy_translangArr['reject_now'])){ echo $rzvy_translangArr['reject_now']; }else{ echo $rzvy_defaultlang['reject_now']; } ?></a>
				</div>
			  </div>
			  <?php 
			  if($obj_settings->get_option("rzvy_refund_status") == "Y"){ 
				$rzvy_refund_request_buffer_time = $obj_settings->get_option("rzvy_refund_request_buffer_time");
				$rzvy_settings_timezone = $obj_settings->get_option("rzvy_timezone");
				$rzvy_server_timezone = date_default_timezone_get();
				$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($rzvy_server_timezone,$rzvy_settings_timezone); 

				$cdate = date("Y-m-d H:i:s", $currDateTime_withTZ);
				$bdate = date("Y-m-d H:i:s", strtotime("-".$rzvy_refund_request_buffer_time." minutes", strtotime($appt['booking_datetime']))); 
				?>
				<hr />
                <div class="row mt-3">
					<div class="col-md-12">
						<?php 
						$rzvy_refund_summary = base64_decode($obj_settings->get_option("rzvy_refund_summary"));
						if(strtotime($cdate)<strtotime($bdate)){ 
							$rzvy_currency_symbol = $obj_settings->get_option("rzvy_currency_symbol");
							$rzvy_refund_type = $obj_settings->get_option("rzvy_refund_type");
							$rzvy_refund_value = $obj_settings->get_option("rzvy_refund_value");
							
							if($rzvy_refund_type == "percentage"){
								$ramount = ($appt['net_total']*$rzvy_refund_value/100);
							}else{
								$ramount = $rzvy_refund_value;
							}
							$ramount = number_format($ramount,2,".",'');
							
							if(isset($rzvy_translangArr['you_eligible_get_refund'])){ 							
								$you_eligible_get_refund = $rzvy_translangArr['you_eligible_get_refund']; 
							}else{ 
								$you_eligible_get_refund = $rzvy_defaultlang['you_eligible_get_refund']; 
							}
							if(isset($rzvy_translangArr['minimum_refund_warning'])){ 							
								$minimum_refund_warning = $rzvy_translangArr['minimum_refund_warning']; 
							}else{ 
								$minimum_refund_warning = $rzvy_defaultlang['minimum_refund_warning']; 
							}
							
							
							
							
							if($ramount < $appt['net_total']){
								echo "<h5><i class='fa fa-check-square-o text-success'></i> ".$you_eligible_get_refund." <b>".$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$ramount)."</b></h5>"; 
							}else{
								echo "<p><i class='fa fa-exclamation-triangle text-warning'></i> <span class='text-dark'>".$minimum_refund_warning." <b class='text-danger'>".$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$ramount)."</b></span></p>";
							} 
							if($rzvy_refund_summary != ""){ 
								?>
								<div id="rzvy-refund-policy-block" class="row">
									<div class="col-md-12">
										<?php echo $rzvy_refund_summary; ?>
									</div>
								</div>
								<?php 
							}
						}else{ 
							if($rzvy_refund_request_buffer_time<60){
								$hours = $rzvy_refund_request_buffer_time." minutes";
							}else if($rzvy_refund_request_buffer_time==60){
								$hours = "1 hour";
							}else{
								$hours = floor($rzvy_refund_request_buffer_time / 60)." hours";
							}
							
							
							if(isset($rzvy_translangArr['opps_not_eligible_get_refund'])){ 							
								$opps_not_eligible_get_refund = $rzvy_translangArr['opps_not_eligible_get_refund']; 
							}else{ 
								$opps_not_eligible_get_refund = $rzvy_defaultlang['opps_not_eligible_get_refund']; 
							}
							if(isset($rzvy_translangArr['you_can_receive_at'])){ 							
								$you_can_receive_at = $rzvy_translangArr['you_can_receive_at']; 
							}else{ 
								$you_can_receive_at = $rzvy_defaultlang['you_can_receive_at']; 
							}
							if(isset($rzvy_translangArr['before_appointment_time'])){ 							
								$before_appointment_time = $rzvy_translangArr['before_appointment_time']; 
							}else{ 
								$before_appointment_time = $rzvy_defaultlang['before_appointment_time']; 
							}
							
							
							echo "<p><i class='fa fa-exclamation-triangle text-warning'></i> <span class='text-dark'>".$opps_not_eligible_get_refund."</span></p>";
							echo "<p><i class='fa fa-info-circle text-dark'></i> <span class='text-dark'>".$you_can_receive_at." <b>".$hours."</b> ".$before_appointment_time."</span></p>"; 
							if($rzvy_refund_summary != ""){ 
								?>
								<div id="rzvy-refund-policy-block" class="row">
									<div class="col-md-12">
										<?php echo $rzvy_refund_summary; ?>
									</div>
								</div>
								<?php 
							}
						} 
						?>
						
					</div>
				</div>
				<?php 
			  }
		}
	}
}

/* Get Slots On Date change */
else if(isset($_POST['rzvy_slots_on_date_change'])){
	$booking_datetime = $_POST['booking_datetime'];
	$staff_id = $_POST['staff_id'];
	$booked_time = date("H:i:s", strtotime($booking_datetime));
	$booking_time = date("H:i:s", strtotime($booking_datetime));
	
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$appointment_detail = $obj_bookings->get_appointment_status_and_datetime();

	$rzvy_settings_timezone = $obj_settings->get_option("rzvy_timezone");
	$rzvy_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($rzvy_server_timezone,$rzvy_settings_timezone); 
	
	$selected_date = date("Y-m-d", strtotime($_POST['selected_date']));
	$selected_date = date($selected_date, $currDateTime_withTZ);
	$current_date = date("Y-m-d", $currDateTime_withTZ);
	
	if(strtotime($selected_date)<strtotime($current_date)){
		$bdate = date("Y-m-d", strtotime($booking_datetime));
		if($bdate == $selected_date){ 
			?>
			<option value="<?php echo $booked_time; ?>" selected>
				<?php echo date($rzvy_time_format,strtotime($booking_datetime)); ?>
			</option>
			<?php 
		}else{
			/** No slots for previous dates booking **/
		}
	}else{
		$isEndTime = false;
		$obj_slots->staff_id = $staff_id;
		$addon_duration = $obj_slots->fetch_addon_duration($appointment_detail['addons']);
		$available_slots = $obj_slots->generate_available_slots_dropdown($time_interval, $rzvy_time_format, $selected_date, $advance_bookingtime, $currDateTime_withTZ, $isEndTime,$_POST["service_id"], $addon_duration);
			
		$no_booking = $available_slots['no_booking'];
		if($available_slots['no_booking']<0){
			$no_booking = 0;
		}
			
		/** check for GC bookings START **/
		$gc_twowaysync_eventsArr = array();
		$rzvy_gc_status = $obj_settings->get_option('rzvy_gc_status');
		$rzvy_gc_twowaysync = $obj_settings->get_option('rzvy_gc_twowaysync');
		$rzvy_gc_clientid = $obj_settings->get_option('rzvy_gc_clientid');
		$rzvy_gc_clientsecret = $obj_settings->get_option('rzvy_gc_clientsecret');
		$rzvy_gc_accesstoken = $obj_settings->get_option('rzvy_gc_accesstoken');
		$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
		
		if($rzvy_gc_status == "Y" && $rzvy_gc_twowaysync == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != ""){
			$getNewTime = new \DateTime('now', new DateTimeZone($rzvy_settings_timezone));
			$timezoneOffset = $getNewTime->format('P');
			
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

			$calendarId = (($obj_settings->get_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_option('rzvy_gc_calendarid'):'primary');
			$optParams = array(
			  'orderBy' => 'startTime',
			  'singleEvents' => true,
			  'timeZone' => $rzvy_settings_timezone,
			  'timeMin' => $selected_date.'T00:00:00'.$timezoneOffset,
			  'timeMax' => $selected_date.'T23:59:59'.$timezoneOffset,
			);
			$results = $service->events->listEvents($calendarId, $optParams);
			$events = $results->getItems();

			if (!empty($events)) {
				foreach ($events as $event) {
					if(!isset($event->transparency) || (isset($event->transparency) && $event->transparency!='transparent')){			
						$EventStartTime = substr($event->start->dateTime, 0, 19);
						$EventEndTime = substr($event->end->dateTime, 0, 19);
						$gcEventArr = array();
						$gcEventArr['start'] = date("Y-m-d H:i", strtotime(str_replace("T"," ",$EventStartTime)));
						$gcEventArr['end'] = date("Y-m-d H:i", strtotime(str_replace("T"," ",$EventEndTime)));
						array_push($gc_twowaysync_eventsArr, $gcEventArr);
					}
				}
			}
		}
		/** check for GC bookings END **/
		
		/** check for staff GC bookings START **/
		if($staff_id>0){
			$obj_settings->staff_id = $staff_id;
			$rzvy_gc_status = $obj_settings->get_staff_option('rzvy_gc_status');
			$rzvy_gc_twowaysync = $obj_settings->get_staff_option('rzvy_gc_twowaysync');
			$rzvy_gc_accesstoken = $obj_settings->get_staff_option('rzvy_gc_accesstoken');
			$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
			
			if($rzvy_gc_status == "Y" && $rzvy_gc_twowaysync == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != ""){
				$getNewTime = new \DateTime('now', new DateTimeZone($rzvy_settings_timezone));
				$timezoneOffset = $getNewTime->format('P');
				
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

				$calendarId = (($obj_settings->get_staff_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_staff_option('rzvy_gc_calendarid'):'primary');
				$optParams = array(
				  'orderBy' => 'startTime',
				  'singleEvents' => true,
				  'timeZone' => $rzvy_settings_timezone,
				  'timeMin' => $selected_date.'T00:00:00'.$timezoneOffset,
				  'timeMax' => $selected_date.'T23:59:59'.$timezoneOffset,
				);
				
				$results = $service->events->listEvents($calendarId, $optParams);
				$events = $results->getItems();

				if (!empty($events)) {
					foreach ($events as $event) {
						if(!isset($event->transparency) || (isset($event->transparency) && $event->transparency!='transparent')){			
							$EventStartTime = substr($event->start->dateTime, 0, 19);
							$EventEndTime = substr($event->end->dateTime, 0, 19);
							$gcEventArr = array();
							$gcEventArr['start'] = date("Y-m-d H:i", strtotime(str_replace("T"," ",$EventStartTime)));
							$gcEventArr['end'] = date("Y-m-d H:i", strtotime(str_replace("T"," ",$EventEndTime)));
							array_push($gc_twowaysync_eventsArr, $gcEventArr);
						}
					}
				}
			}
		}
		/** check for staff GC bookings END **/
		
		if(isset($available_slots['slots']) && sizeof($available_slots['slots'])>0)
		{
			foreach($available_slots['slots'] as $slot) 
			{
				$bookings_blocks = $obj_slots->get_bookings_blocks($selected_date, $slot, $available_slots["serviceaddonduration"]);
				if(!$bookings_blocks){
					continue;
				}
				$no_curr_boookings = $obj_slots->get_slot_bookings($selected_date." ".$slot,$appointment_detail['service_id']);
				$booked_slot_exist = false;
				
				foreach($gc_twowaysync_eventsArr as $event){
					if(strtotime($event["start"]) <= strtotime($selected_date." ".$slot) && strtotime($event["end"]) > strtotime($selected_date." ".$slot)){
						$no_curr_boookings += 1;
					}
					if(strtotime($event["start"]) <= strtotime($selected_date." ".$slot) && strtotime($event["end"]) > strtotime($selected_date." ".$slot) && $no_booking==0){
						$booked_slot_exist = true;
						continue;
					} 
					if(strtotime($event["start"]) <= strtotime($selected_date." ".$slot) && strtotime($event["end"]) > strtotime($selected_date." ".$slot) && $no_booking!=0 && $no_curr_boookings>=$no_booking){
						$booked_slot_exist = true;
						continue;
					} 
				}
				
				$new_endtime_timestamp = strtotime("+".$available_slots["serv_timeinterval"]." minutes", strtotime($selected_date." ".$slot));
				$new_starttime_timestamp = strtotime($selected_date." ".$slot);
				
				foreach($available_slots['booked'] as $bslot){
					if($bslot["start_time"] <= strtotime($selected_date." ".$slot) && $bslot["end_time"] > strtotime($selected_date." ".$slot) && $no_booking==0){
						$booked_slot_exist = true;
						continue;
					} 
					if($bslot["start_time"] <= strtotime($selected_date." ".$slot) && $bslot["end_time"] > strtotime($selected_date." ".$slot) && $no_booking!=0 && $no_curr_boookings>=$no_booking){
						$booked_slot_exist = true;
						continue;
					} 
					
					if($new_starttime_timestamp <= $bslot["start_time"] && $new_endtime_timestamp > $bslot["start_time"] && $no_booking==0){
						$booked_slot_exist = true;
						continue;
					}
					
					if($new_starttime_timestamp <= $bslot["start_time"] && $new_endtime_timestamp > $bslot["start_time"] && $no_booking!=0){
					    $no_curr_boookings = $no_curr_boookings+1;
					    if($no_curr_boookings>=$no_booking){
							$booked_slot_exist = true;
							continue;
					    }
					} 
					if($new_starttime_timestamp < $bslot["end_time"] && $new_endtime_timestamp > $bslot["end_time"] && $no_booking==0){
						$booked_slot_exist = true;
						continue;
					}
					
					if($new_starttime_timestamp < $bslot["end_time"] && $new_endtime_timestamp > $bslot["end_time"] && $no_booking!=0){
					    $no_curr_boookings = $no_curr_boookings+1;
					    if($no_curr_boookings>=$no_booking){
							$booked_slot_exist = true;
							continue;
					    }
					} 
				}
				
				if($booked_slot_exist){
					if(strtotime($booking_datetime) != strtotime($selected_date." ".$slot)){
						continue;
					}
				}
				$blockoff_exist = false;
				if(sizeof($available_slots['block_off'])>0){
					foreach($available_slots['block_off'] as $block_off){
						if((strtotime($selected_date." ".$block_off["start_time"]) <= strtotime($selected_date." ".$slot)) && (strtotime($selected_date." ".$block_off["end_time"]) > strtotime($selected_date." ".$slot))){
							if(strtotime($booking_datetime) != strtotime($selected_date." ".$slot)){
								$blockoff_exist = true;
								continue;
							}
						} 
					}
				} 
				if($blockoff_exist){
					continue;
				} 
				?>
				<option value="<?php echo $slot; ?>" <?php if(strtotime($booking_datetime) == strtotime($selected_date." ".$slot)){ echo "selected"; } ?>>
					<?php echo date($rzvy_time_format,strtotime($selected_date." ".$slot)); ?>
				</option>
				<?php 
			}
		}
	}
}

/* Delete Appointment Ajax */
else if(isset($_POST['delete_appointment'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$deleted = $obj_bookings->delete_appointment();
	if($deleted){
		
		/**** Delete to GC start **/
		include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
		$obj_es_information = new rzvy_es_information();
		$obj_es_information->conn = $conn;
		
		$rzvy_gc_status = $obj_settings->get_option('rzvy_gc_status');
		$rzvy_gc_clientid = $obj_settings->get_option('rzvy_gc_clientid');
		$rzvy_gc_clientsecret = $obj_settings->get_option('rzvy_gc_clientsecret');
		$rzvy_gc_accesstoken = $obj_settings->get_option('rzvy_gc_accesstoken');
		$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
		$eventId = $obj_es_information->get_appt_gc_eventid($order_id);
		if($rzvy_gc_status == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != "" && $eventId != ""){
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
			$calendarId = (($obj_settings->get_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_option('rzvy_gc_calendarid'):'primary');
			$service->events->delete($calendarId, $eventId);
			$obj_es_information->delete_appt_gc_eventid($order_id);
		}
		/*** Delete to GC end **/
		
		/**** Delete to staff GC start **/
		$gc_staff_id = $obj_settings->get_staffid_from_orderid($order_id);
		if($gc_staff_id>0){
			$obj_settings->staff_id = $gc_staff_id;
			$rzvy_gc_status = $obj_settings->get_staff_option('rzvy_gc_status');
			$rzvy_gc_accesstoken = $obj_settings->get_staff_option('rzvy_gc_accesstoken');
			$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
			$eventId = $obj_es_information->get_appt_gc_eventid_staff($order_id);
			if($rzvy_gc_status == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != "" && $eventId != ""){
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
				$calendarId = (($obj_settings->get_staff_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_staff_option('rzvy_gc_calendarid'):'primary');
				$service->events->delete($calendarId, $eventId);
				$obj_es_information->delete_appt_gc_eventid_staff($order_id);
			}
		}
		/*** Delete to GC end **/
		
		echo "deleted";
	}
}

/* Confirm Appointment Ajax */
else if(isset($_POST['confirm_appointment'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	if($_SESSION['login_type'] == "staff"){
		$obj_bookings->booking_status = "confirmed_by_staff";
	}else{
		$obj_bookings->booking_status = "confirmed";
	}
	$confirmed = $obj_bookings->change_appointment_status();
	if($confirmed){
		/********************** Send SMS & Email code start ***************************/
		include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
		$obj_es_information = new rzvy_es_information();
		$obj_es_information->conn = $conn;
		
		$get_es_appt_detail_by_order_id = $obj_es_information->get_es_appt_detail_by_order_id($order_id);
		if(mysqli_num_rows($get_es_appt_detail_by_order_id)>0){
			$es_appt_detail = mysqli_fetch_array($get_es_appt_detail_by_order_id);
			if($_SESSION['login_type'] == "staff"){
				$es_template = "confirms";
			}else{
				$es_template = "confirm";
			}
			$es_staff_id = $es_appt_detail['staff_id'];
			$es_category_id = $es_appt_detail['cat_id'];
			$es_service_id = $es_appt_detail['service_id'];
			$es_booking_datetime = $es_appt_detail['booking_datetime'];
			$es_transaction_id = $es_appt_detail['transaction_id'];
			$es_subtotal = $es_appt_detail['sub_total'];
			$es_coupondiscount = $es_appt_detail['discount'];
			$es_freqdiscount = $es_appt_detail['fd_amount'];
			$es_tax = $es_appt_detail['tax'];
			$es_nettotal = $es_appt_detail['net_total'];
			$es_payment_method = $es_appt_detail['payment_method'];
			if($es_payment_method==ucwords('pay-at-venue')){
				if(isset($rzvy_translangArr['pay_at_venue'])){ $es_payment_method = $rzvy_translangArr['pay_at_venue']; }else{ $es_payment_method = $rzvy_defaultlang['pay_at_venue']; } 
			}
			$es_firstname = $es_appt_detail['c_firstname'];
			$es_lastname = $es_appt_detail['c_lastname'];
			$es_email = $es_appt_detail['c_email'];
			$es_phone = $es_appt_detail['c_phone'];
			$es_address = $es_appt_detail['c_address'];
			$es_city = $es_appt_detail['c_city'];
			$es_state = $es_appt_detail['c_state'];
			$es_country = $es_appt_detail['c_country'];
			$es_zip = $es_appt_detail['c_zip'];
			$es_addons_items_arr = $es_appt_detail['addons'];
			$es_reschedule_reason = $es_appt_detail['reschedule_reason'];
			$es_reject_reason = $es_appt_detail['reject_reason'];
			$es_cancel_reason = $es_appt_detail['cancel_reason'];
			
			$rzvy_cancel_feedback_sms_shortlink = $obj_settings->get_option('rzvy_cancel_feedback_sms_shortlink');
			if($rzvy_cancel_feedback_sms_shortlink!=""){
				$shortlinkurl = $rzvy_cancel_feedback_sms_shortlink;
			}else{
				$shortlinkurl = SITE_URL;
			}
			
			$cancel_url = $shortlinkurl.'c/B'.rand(101,999).base64_encode($order_id);
			include("rzvy_send_sms_email_process.php");
		}
		@ob_clean(); ob_start();
		/********************** Send SMS & Email code END ****************************/
		echo "confirmed";
	}
}

/* Mark as pending Appointment Ajax */
else if(isset($_POST['mark_as_pending_appointment'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$obj_bookings->booking_status = "pending";
	$pending = $obj_bookings->change_appointment_status();
	if($pending){
		/********************** Send SMS & Email code start ***************************/
		include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
		$obj_es_information = new rzvy_es_information();
		$obj_es_information->conn = $conn;
		
		$get_es_appt_detail_by_order_id = $obj_es_information->get_es_appt_detail_by_order_id($order_id);
		if(mysqli_num_rows($get_es_appt_detail_by_order_id)>0){
			$es_appt_detail = mysqli_fetch_array($get_es_appt_detail_by_order_id);
			$es_template = "new";
			$es_staff_id = $es_appt_detail['staff_id'];
			$es_category_id = $es_appt_detail['cat_id'];
			$es_service_id = $es_appt_detail['service_id'];
			$es_booking_datetime = $es_appt_detail['booking_datetime'];
			$es_transaction_id = $es_appt_detail['transaction_id'];
			$es_subtotal = $es_appt_detail['sub_total'];
			$es_coupondiscount = $es_appt_detail['discount'];
			$es_freqdiscount = $es_appt_detail['fd_amount'];
			$es_tax = $es_appt_detail['tax'];
			$es_nettotal = $es_appt_detail['net_total'];
			$es_payment_method = $es_appt_detail['payment_method'];
			if($es_payment_method==ucwords('pay-at-venue')){
				if(isset($rzvy_translangArr['pay_at_venue'])){ $es_payment_method = $rzvy_translangArr['pay_at_venue']; }else{ $es_payment_method = $rzvy_defaultlang['pay_at_venue']; } 
			}
			$es_firstname = $es_appt_detail['c_firstname'];
			$es_lastname = $es_appt_detail['c_lastname'];
			$es_email = $es_appt_detail['c_email'];
			$es_phone = $es_appt_detail['c_phone'];
			$es_address = $es_appt_detail['c_address'];
			$es_city = $es_appt_detail['c_city'];
			$es_state = $es_appt_detail['c_state'];
			$es_country = $es_appt_detail['c_country'];
			$es_zip = $es_appt_detail['c_zip'];
			$es_addons_items_arr = $es_appt_detail['addons'];
			$es_reschedule_reason = $es_appt_detail['reschedule_reason'];
			$es_reject_reason = $es_appt_detail['reject_reason'];
			$es_cancel_reason = $es_appt_detail['cancel_reason'];
			include("rzvy_send_sms_email_process.php");
		}
		@ob_clean(); ob_start();
		/********************** Send SMS & Email code END ****************************/
		echo "pending";
	}
}

/* Mark as pending Appointment Ajax */
else if(isset($_POST['mark_as_completed_appointment'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$obj_bookings->booking_status = "completed";
	$completed = $obj_bookings->change_appointment_status();
	
	/* Loyalty Points */
	if(isset($rzvy_allow_loyalty_points_status) && $rzvy_allow_loyalty_points_status=='Y'){
	    $obj_bookings->order_id = $order_id;
	    $customer_id = $obj_bookings->get_customer_id();
	    /* If not Guest Customer */
	    if(isset($customer_id) && $customer_id>0){
	        $newavailablepoints = 0;
	        $loyalty_points_reward_method = $obj_settings->get_option('rzvy_loyalty_points_reward_method');
	        $rewardedpoints = 0;
	        if(isset($loyalty_points_reward_method) && $loyalty_points_reward_method=='A'){
	            $spendbasis_loyalty_point_value = $obj_settings->get_option('rzvy_loyalty_points_per_spend_based');
	            if(isset($spendbasis_loyalty_point_value) && $spendbasis_loyalty_point_value>0){
	                $obj_rzvy_payments->order_id = $order_id;
                	$ordernetamount = $obj_rzvy_payments->get_order_net_total();
    	            $rewardedpoints = round($ordernetamount*$spendbasis_loyalty_point_value);
    	           $newavailablepoints = $rewardedpoints;
	            }
	        }else{
	            if(isset($rzvy_perbooking_loyalty_points) && $rzvy_perbooking_loyalty_points>0){
	                $newavailablepoints = $rzvy_perbooking_loyalty_points;
	                $rewardedpoints = $rzvy_perbooking_loyalty_points;
	            }
	        }
	        
    	    $availablepoints = $obj_loyalty_points->get_available_points_customer($customer_id);
    	    
    	    if(isset($availablepoints) && $availablepoints!=''){
    	        $newavailablepoints += $availablepoints;
    	    }
    	    $obj_loyalty_points->add_loyalty_points_record($order_id, $customer_id, 'A', $rewardedpoints, $newavailablepoints);
	    }
	}
	
	if($completed){
		/********************** Send SMS & Email code start ***************************/
		include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
		$obj_es_information = new rzvy_es_information();
		$obj_es_information->conn = $conn;
				
		/** Send Referred customer thank you notification start **/
		$get_es_referred_customer_detail_by_order_id = $obj_es_information->get_es_referred_customer_detail_by_order_id($order_id);
		$get_es_appt_detail_by_order_id = $obj_es_information->get_es_appt_detail_by_order_id($order_id);
		
		if(mysqli_num_rows($get_es_referred_customer_detail_by_order_id)>0){
			$es_r_appt_detail = mysqli_fetch_array($get_es_referred_customer_detail_by_order_id);
			$es_r_template = "referral";
			$es_r_firstname = $es_r_appt_detail['firstname'];
			$es_r_lastname = $es_r_appt_detail['lastname'];
			$es_r_email = $es_r_appt_detail['email'];
			$es_r_phone = $es_r_appt_detail['phone'];
			/* if(mysqli_num_rows($get_es_appt_detail_by_order_id)==0){ */
			include("rzvy_send_sms_email_process.php");
			/* } */
		}
		/** Send Referred customer thank you notification end   **/
		
		/** Completed booking notification code start **/
		if(mysqli_num_rows($get_es_appt_detail_by_order_id)>0){
			$es_appt_detail = mysqli_fetch_array($get_es_appt_detail_by_order_id);
			$es_template = "complete";
			$es_staff_id = $es_appt_detail['staff_id'];
			$es_category_id = $es_appt_detail['cat_id'];
			$es_service_id = $es_appt_detail['service_id'];
			$es_booking_datetime = $es_appt_detail['booking_datetime'];
			$es_transaction_id = $es_appt_detail['transaction_id'];
			$es_subtotal = $es_appt_detail['sub_total'];
			$es_coupondiscount = $es_appt_detail['discount'];
			$es_freqdiscount = $es_appt_detail['fd_amount'];
			$es_tax = $es_appt_detail['tax'];
			$es_nettotal = $es_appt_detail['net_total'];
			$es_payment_method = $es_appt_detail['payment_method'];
			if($es_payment_method==ucwords('pay-at-venue')){
				if(isset($rzvy_translangArr['pay_at_venue'])){ $es_payment_method = $rzvy_translangArr['pay_at_venue']; }else{ $es_payment_method = $rzvy_defaultlang['pay_at_venue']; } 
			}
			$es_firstname = $es_appt_detail['c_firstname'];
			$es_lastname = $es_appt_detail['c_lastname'];
			$es_email = $es_appt_detail['c_email'];
			$es_phone = $es_appt_detail['c_phone'];
			$es_address = $es_appt_detail['c_address'];
			$es_city = $es_appt_detail['c_city'];
			$es_state = $es_appt_detail['c_state'];
			$es_country = $es_appt_detail['c_country'];
			$es_zip = $es_appt_detail['c_zip'];
			$es_addons_items_arr = $es_appt_detail['addons'];
			$es_reschedule_reason = $es_appt_detail['reschedule_reason'];
			$es_reject_reason = $es_appt_detail['reject_reason'];
			$es_cancel_reason = $es_appt_detail['cancel_reason'];
			
			$rzvy_cancel_feedback_sms_shortlink = $obj_settings->get_option('rzvy_cancel_feedback_sms_shortlink');
			if($rzvy_cancel_feedback_sms_shortlink!=""){
				$shortlinkurl = $rzvy_cancel_feedback_sms_shortlink;
			}else{
				$shortlinkurl = SITE_URL;
			}
			
			$feedback_url = $shortlinkurl.'f/B'.rand(101,999).base64_encode($order_id);
			include("rzvy_send_sms_email_process.php");
		}
		@ob_clean(); ob_start();
		/********************** Send SMS & Email code END ****************************/
		echo "completed";
	}
}
/* Mark as NoShow Appointment Ajax */
else if(isset($_POST['mark_as_noshow_appointment'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$obj_bookings->booking_status = "mark_as_noshow";
	$mark_as_noshow = $obj_bookings->change_appointment_status();
	if($mark_as_noshow){
		@ob_clean(); ob_start();
		echo "mark_as_noshow";
	}
}
/* Reschedule Appointment Ajax */
else if(isset($_POST['reschedule_appointment_detail'])){
	$order_id = $_POST['order_id'];
	$staff_id = $_POST['staff_id'];
	$reason = htmlentities($_POST['reason']);
	$booking_datetime = date("Y-m-d H:i:s", strtotime($_POST['date']." ".$_POST['slot']));
	$booking_end_datetime = date("Y-m-d H:i:s", strtotime($_POST['date']." ".$_POST['endslot']));
	/* $booking_end_datetime = date("Y-m-d H:i:s", strtotime('+'.$time_interval.' minutes',strtotime($booking_datetime))); */
	$obj_bookings->order_id = $order_id;
	if($_SESSION['login_type'] == "staff"){
		$obj_bookings->booking_status = "rescheduled_by_staff";
	}else{
		$obj_bookings->booking_status = "rescheduled_by_you";
	}
	$obj_bookings->reschedule_reason = $reason;
	$obj_bookings->booking_datetime = $booking_datetime;
	$obj_bookings->booking_end_datetime = $booking_end_datetime;
	$obj_bookings->staff_id = $staff_id;
	$updated = $obj_bookings->adminstaff_reschedule_appointment();
	if($updated){
		/********************** Send SMS & Email code start ***************************/
		include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
		$obj_es_information = new rzvy_es_information();
		$obj_es_information->conn = $conn;
		
		/**** Update to GC start **/
		$rzvy_gc_status = $obj_settings->get_option('rzvy_gc_status');
		$rzvy_gc_clientid = $obj_settings->get_option('rzvy_gc_clientid');
		$rzvy_gc_clientsecret = $obj_settings->get_option('rzvy_gc_clientsecret');
		$rzvy_gc_accesstoken = $obj_settings->get_option('rzvy_gc_accesstoken');
		$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
		$eventId = $obj_es_information->get_appt_gc_eventid($order_id);
		if($rzvy_gc_status == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != "" && $eventId != ""){
			$rzvy_time_format = $obj_settings->get_option("rzvy_time_format");
			$rzvy_timezone = $obj_settings->get_option("rzvy_timezone");
			
			$getNewTime = new \DateTime('now', new DateTimeZone($rzvy_timezone));
			$timezoneOffset = $getNewTime->format('P');
			
			$gc_StartTime = str_replace(" ", "T", $booking_datetime).$timezoneOffset;
			$gc_EndTime = str_replace(" ", "T", $booking_end_datetime).$timezoneOffset;
			
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
			$calendarId = (($obj_settings->get_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_option('rzvy_gc_calendarid'):'primary');
			$event = $service->events->get($calendarId, $eventId);
			$startdateobj = new Google_Service_Calendar_EventDateTime(array(
				'dateTime' => $gc_StartTime,
				'timeZone' => $rzvy_timezone
			));
			$enddateobj = new Google_Service_Calendar_EventDateTime(array(
				'dateTime' => $gc_EndTime,
				'timeZone' => $rzvy_timezone
			));
			$event->setStart($startdateobj);
			$event->setEnd($enddateobj);
			$service->events->update('primary', $event->getId(), $event);
		}
		/*** Update to GC end **/
		
		/**** Update to staff GC start **/
		if($staff_id>0){
			$obj_settings->staff_id = $staff_id;
			$rzvy_gc_status = $obj_settings->get_staff_option('rzvy_gc_status');
			$rzvy_gc_accesstoken = $obj_settings->get_staff_option('rzvy_gc_accesstoken');
			$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
			$eventId = $obj_es_information->get_appt_gc_eventid_staff($order_id);
			if($rzvy_gc_status == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != "" && $eventId != ""){
				$rzvy_time_format = $obj_settings->get_option("rzvy_time_format");
				$rzvy_timezone = $obj_settings->get_option("rzvy_timezone");
				
				$getNewTime = new \DateTime('now', new DateTimeZone($rzvy_timezone));
				$timezoneOffset = $getNewTime->format('P');
				
				$gc_StartTime = str_replace(" ", "T", $booking_datetime).$timezoneOffset;
				$gc_EndTime = str_replace(" ", "T", $booking_end_datetime).$timezoneOffset;
				
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
				$calendarId = (($obj_settings->get_staff_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_staff_option('rzvy_gc_calendarid'):'primary');
				$event = $service->events->get($calendarId, $eventId);
				$startdateobj = new Google_Service_Calendar_EventDateTime(array(
					'dateTime' => $gc_StartTime,
					'timeZone' => $rzvy_timezone
				));
				$enddateobj = new Google_Service_Calendar_EventDateTime(array(
					'dateTime' => $gc_EndTime,
					'timeZone' => $rzvy_timezone
				));
				$event->setStart($startdateobj);
				$event->setEnd($enddateobj);
				$service->events->update('primary', $event->getId(), $event);
			}
		}
		/*** Update to staff GC end **/
		
		$get_es_appt_detail_by_order_id = $obj_es_information->get_es_appt_detail_by_order_id($order_id);
		if(mysqli_num_rows($get_es_appt_detail_by_order_id)>0){
			$es_appt_detail = mysqli_fetch_array($get_es_appt_detail_by_order_id);
			if($_SESSION['login_type'] == "staff"){
				$es_template = "reschedules";
			}else{
				$es_template = "reschedulea";
			}
			$es_staff_id = $es_appt_detail['staff_id'];
			$es_category_id = $es_appt_detail['cat_id'];
			$es_service_id = $es_appt_detail['service_id'];
			$es_booking_datetime = $es_appt_detail['booking_datetime'];
			$es_transaction_id = $es_appt_detail['transaction_id'];
			$es_subtotal = $es_appt_detail['sub_total'];
			$es_coupondiscount = $es_appt_detail['discount'];
			$es_freqdiscount = $es_appt_detail['fd_amount'];
			$es_tax = $es_appt_detail['tax'];
			$es_nettotal = $es_appt_detail['net_total'];
			$es_payment_method = $es_appt_detail['payment_method'];
			if($es_payment_method==ucwords('pay-at-venue')){
				if(isset($rzvy_translangArr['pay_at_venue'])){ $es_payment_method = $rzvy_translangArr['pay_at_venue']; }else{ $es_payment_method = $rzvy_defaultlang['pay_at_venue']; } 
			}
			$es_firstname = $es_appt_detail['c_firstname'];
			$es_lastname = $es_appt_detail['c_lastname'];
			$es_email = $es_appt_detail['c_email'];
			$es_phone = $es_appt_detail['c_phone'];
			$es_address = $es_appt_detail['c_address'];
			$es_city = $es_appt_detail['c_city'];
			$es_state = $es_appt_detail['c_state'];
			$es_country = $es_appt_detail['c_country'];
			$es_zip = $es_appt_detail['c_zip'];
			$es_addons_items_arr = $es_appt_detail['addons'];
			$es_reschedule_reason = $es_appt_detail['reschedule_reason'];
			$es_reject_reason = $es_appt_detail['reject_reason'];
			$es_cancel_reason = $es_appt_detail['cancel_reason'];
			
			$rzvy_cancel_feedback_sms_shortlink = $obj_settings->get_option('rzvy_cancel_feedback_sms_shortlink');
			if($rzvy_cancel_feedback_sms_shortlink!=""){
				$shortlinkurl = $rzvy_cancel_feedback_sms_shortlink;
			}else{
				$shortlinkurl = SITE_URL;
			}
			
			$cancel_url = $shortlinkurl.'c/B'.rand(101,999).base64_encode($order_id);
			include("rzvy_send_sms_email_process.php");
		}
		@ob_clean(); ob_start();
		/********************** Send SMS & Email code END ****************************/
		echo "updated";
	}
}

/* Update Dragged Appointment Ajax */
else if(isset($_POST['update_dragged_appointment'])){
	$order_id = $_POST['order_id'];
	$reason = htmlentities($_POST['reason']);
	$booking_datetime = date("Y-m-d H:i:s", strtotime($_POST['selected_datetime']));
	$obj_bookings->order_id = $order_id;
	$reschedule_appointment_detail = $obj_bookings->get_reschedule_appointment_detail();
	if(mysqli_num_rows($reschedule_appointment_detail)>0){
		$rs_appt = mysqli_fetch_assoc($reschedule_appointment_detail);
		$service_id = $rs_appt['service_id'];
		if(is_numeric($rs_appt['service_id']) && $rs_appt['service_id'] != "0"){
			$time_interval=$obj_slots->get_service_time_interval($service_id,$time_interval);
		}
	}
	$booking_end_datetime = date("Y-m-d H:i:s", strtotime('+'.$time_interval.' minutes',strtotime($booking_datetime)));
	$obj_bookings->order_id = $order_id;
	if($_SESSION['login_type'] == "staff"){
		$obj_bookings->booking_status = "rescheduled_by_staff";
	}else{
		$obj_bookings->booking_status = "rescheduled_by_you";
	}
	$obj_bookings->reschedule_reason = $reason;
	$obj_bookings->booking_datetime = $booking_datetime;
	$obj_bookings->booking_end_datetime = $booking_end_datetime;
	$updated = $obj_bookings->reschedule_appointment();
	if($updated){
		/********************** Send SMS & Email code start ***************************/
		include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
		$obj_es_information = new rzvy_es_information();
		$obj_es_information->conn = $conn;
		
		/**** Update to GC start **/
		$rzvy_gc_status = $obj_settings->get_option('rzvy_gc_status');
		$rzvy_gc_clientid = $obj_settings->get_option('rzvy_gc_clientid');
		$rzvy_gc_clientsecret = $obj_settings->get_option('rzvy_gc_clientsecret');
		$rzvy_gc_accesstoken = $obj_settings->get_option('rzvy_gc_accesstoken');
		$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
		$eventId = $obj_es_information->get_appt_gc_eventid($order_id);
		if($rzvy_gc_status == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != "" && $eventId != ""){
			$rzvy_time_format = $obj_settings->get_option("rzvy_time_format");
			$rzvy_timezone = $obj_settings->get_option("rzvy_timezone");
			
			$getNewTime = new \DateTime('now', new DateTimeZone($rzvy_timezone));
			$timezoneOffset = $getNewTime->format('P');
			
			$gc_StartTime = str_replace(" ", "T", $booking_datetime).$timezoneOffset;
			$gc_EndTime = str_replace(" ", "T", $booking_end_datetime).$timezoneOffset;
			
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
			$calendarId = (($obj_settings->get_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_option('rzvy_gc_calendarid'):'primary');
			$event = $service->events->get($calendarId, $eventId);
			$startdateobj = new Google_Service_Calendar_EventDateTime(array(
				'dateTime' => $gc_StartTime,
				'timeZone' => $rzvy_timezone
			));
			$enddateobj = new Google_Service_Calendar_EventDateTime(array(
				'dateTime' => $gc_EndTime,
				'timeZone' => $rzvy_timezone
			));
			$event->setStart($startdateobj);
			$event->setEnd($enddateobj);
			$service->events->update('primary', $event->getId(), $event);
		}
		/*** Update to GC end **/
		
		/**** Update to staff GC start **/
		$gc_staff_id = $obj_settings->get_staffid_from_orderid($order_id);
		if($gc_staff_id>0){
			$obj_settings->staff_id = $gc_staff_id;
			$rzvy_gc_status = $obj_settings->get_staff_option('rzvy_gc_status');
			$rzvy_gc_accesstoken = $obj_settings->get_staff_option('rzvy_gc_accesstoken');
			$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
			$eventId = $obj_es_information->get_appt_gc_eventid_staff($order_id);
			if($rzvy_gc_status == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != "" && $eventId != ""){
				$rzvy_time_format = $obj_settings->get_option("rzvy_time_format");
				$rzvy_timezone = $obj_settings->get_option("rzvy_timezone");
				
				$getNewTime = new \DateTime('now', new DateTimeZone($rzvy_timezone));
				$timezoneOffset = $getNewTime->format('P');
				
				$gc_StartTime = str_replace(" ", "T", $booking_datetime).$timezoneOffset;
				$gc_EndTime = str_replace(" ", "T", $booking_end_datetime).$timezoneOffset;
				
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
				$calendarId = (($obj_settings->get_staff_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_staff_option('rzvy_gc_calendarid'):'primary');
				$event = $service->events->get($calendarId, $eventId);
				$startdateobj = new Google_Service_Calendar_EventDateTime(array(
					'dateTime' => $gc_StartTime,
					'timeZone' => $rzvy_timezone
				));
				$enddateobj = new Google_Service_Calendar_EventDateTime(array(
					'dateTime' => $gc_EndTime,
					'timeZone' => $rzvy_timezone
				));
				$event->setStart($startdateobj);
				$event->setEnd($enddateobj);
				$service->events->update('primary', $event->getId(), $event);
			}
		}
		/*** Update to staff GC end **/
		
		$get_es_appt_detail_by_order_id = $obj_es_information->get_es_appt_detail_by_order_id($order_id);
		if(mysqli_num_rows($get_es_appt_detail_by_order_id)>0){
			$es_appt_detail = mysqli_fetch_array($get_es_appt_detail_by_order_id);
			if($_SESSION['login_type'] == "staff"){
				$es_template = "reschedules";
			}else{
				$es_template = "reschedulea";
			}
			$es_staff_id = $es_appt_detail['staff_id'];
			$es_category_id = $es_appt_detail['cat_id'];
			$es_service_id = $es_appt_detail['service_id'];
			$es_booking_datetime = $es_appt_detail['booking_datetime'];
			$es_transaction_id = $es_appt_detail['transaction_id'];
			$es_subtotal = $es_appt_detail['sub_total'];
			$es_coupondiscount = $es_appt_detail['discount'];
			$es_freqdiscount = $es_appt_detail['fd_amount'];
			$es_tax = $es_appt_detail['tax'];
			$es_nettotal = $es_appt_detail['net_total'];
			$es_payment_method = $es_appt_detail['payment_method'];
			if($es_payment_method==ucwords('pay-at-venue')){
				if(isset($rzvy_translangArr['pay_at_venue'])){ $es_payment_method = $rzvy_translangArr['pay_at_venue']; }else{ $es_payment_method = $rzvy_defaultlang['pay_at_venue']; } 
			}
			$es_firstname = $es_appt_detail['c_firstname'];
			$es_lastname = $es_appt_detail['c_lastname'];
			$es_email = $es_appt_detail['c_email'];
			$es_phone = $es_appt_detail['c_phone'];
			$es_address = $es_appt_detail['c_address'];
			$es_city = $es_appt_detail['c_city'];
			$es_state = $es_appt_detail['c_state'];
			$es_country = $es_appt_detail['c_country'];
			$es_zip = $es_appt_detail['c_zip'];
			$es_addons_items_arr = $es_appt_detail['addons'];
			$es_reschedule_reason = $es_appt_detail['reschedule_reason'];
			$es_reject_reason = $es_appt_detail['reject_reason'];
			$es_cancel_reason = $es_appt_detail['cancel_reason'];
			include("rzvy_send_sms_email_process.php");
		}
		@ob_clean(); ob_start();
		/********************** Send SMS & Email code END ****************************/
		echo "updated";
	}
}

/* Reject Appointment Ajax */
else if(isset($_POST['reject_appointment_detail'])){
	$order_id = $_POST['order_id'];
	$obj_bookings->order_id = $order_id;
	$all_detail = $obj_bookings->get_reject_appointment_detail();
	if(mysqli_num_rows($all_detail)>0){
		while($appt = mysqli_fetch_assoc($all_detail)){
			if($obj_settings->get_option("rzvy_refund_status") == "Y"){ 
				$rzvy_refund_request_buffer_time = $obj_settings->get_option("rzvy_refund_request_buffer_time");
				$rzvy_settings_timezone = $obj_settings->get_option("rzvy_timezone");
				$rzvy_server_timezone = date_default_timezone_get();
				$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($rzvy_server_timezone,$rzvy_settings_timezone); 

				$cdate = date("Y-m-d H:i:s", $currDateTime_withTZ);
				$bdate = date("Y-m-d H:i:s", strtotime("-".$rzvy_refund_request_buffer_time." minutes", strtotime($appt['booking_datetime']))); 
			
				if(strtotime($cdate)<strtotime($bdate)){ 
					$rzvy_refund_type = $obj_settings->get_option("rzvy_refund_type");
					$rzvy_refund_value = $obj_settings->get_option("rzvy_refund_value");
					
					if($rzvy_refund_type == "percentage"){
						$ramount = ($appt['net_total']*$rzvy_refund_value/100);
					}else{
						$ramount = $rzvy_refund_value;
					}
					$ramount = number_format($ramount,2,".",'');
					if($ramount < $appt['net_total']){
						/** Insert refund request function **/
						include(dirname(dirname(dirname(__FILE__)))."/classes/class_refund_request.php");
						$obj_refund_request = new rzvy_refund_request();
						$obj_refund_request->conn = $conn;
						$obj_refund_request->order_id = $order_id;
						$obj_refund_request->amount = $ramount;
						$obj_refund_request->requested_on = $cdate;
						$obj_refund_request->status = "pending";
						$obj_refund_request->read_status = "U";
						$obj_refund_request->add_refund_request();
					}
				}
			}
		}
	}
	$reason = htmlentities($_POST['reason']);
	$obj_bookings->order_id = $order_id;
	if($_SESSION['login_type'] == "staff"){
		$obj_bookings->booking_status = "rejected_by_staff";
	}else{
		$obj_bookings->booking_status = "rejected_by_you";
	}
	$obj_bookings->reject_reason = $reason;
	$updated = $obj_bookings->reject_appointment();
	if($updated){
		/********************** Send SMS & Email code start ***************************/
		include(dirname(dirname(dirname(__FILE__)))."/classes/class_es_information.php");
		$obj_es_information = new rzvy_es_information();
		$obj_es_information->conn = $conn;
		
		/**** Delete to GC start **/
		$rzvy_gc_status = $obj_settings->get_option('rzvy_gc_status');
		$rzvy_gc_clientid = $obj_settings->get_option('rzvy_gc_clientid');
		$rzvy_gc_clientsecret = $obj_settings->get_option('rzvy_gc_clientsecret');
		$rzvy_gc_accesstoken = $obj_settings->get_option('rzvy_gc_accesstoken');
		$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
		$eventId = $obj_es_information->get_appt_gc_eventid($order_id);
		if($rzvy_gc_status == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != "" && $eventId != ""){
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
			$calendarId = (($obj_settings->get_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_option('rzvy_gc_calendarid'):'primary');
			$service->events->delete($calendarId, $eventId);
			$obj_es_information->delete_appt_gc_eventid($order_id);
		}
		/*** Delete to GC end **/
		
		/**** Delete to staff GC start **/
		$gc_staff_id = $obj_settings->get_staffid_from_orderid($order_id);
		if($gc_staff_id>0){
			$obj_settings->staff_id = $gc_staff_id;
			$rzvy_gc_status = $obj_settings->get_staff_option('rzvy_gc_status');
			$rzvy_gc_accesstoken = $obj_settings->get_staff_option('rzvy_gc_accesstoken');
			$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
			$eventId = $obj_es_information->get_appt_gc_eventid_staff($order_id);
			if($rzvy_gc_status == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != "" && $eventId != ""){
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
				$calendarId = (($obj_settings->get_staff_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_staff_option('rzvy_gc_calendarid'):'primary');
				$service->events->delete($calendarId, $eventId);
				$obj_es_information->delete_appt_gc_eventid_staff($order_id);
			}
		}
		/*** Delete to staff GC end **/
		
		$get_es_appt_detail_by_order_id = $obj_es_information->get_es_appt_detail_by_order_id($order_id);
		if(mysqli_num_rows($get_es_appt_detail_by_order_id)>0){
			$es_appt_detail = mysqli_fetch_array($get_es_appt_detail_by_order_id);
			if($_SESSION['login_type'] == "staff"){
				$es_template = "rejects";
			}else{
				$es_template = "rejecta";
			}
			$es_staff_id = $es_appt_detail['staff_id'];
			$es_category_id = $es_appt_detail['cat_id'];
			$es_service_id = $es_appt_detail['service_id'];
			$es_booking_datetime = $es_appt_detail['booking_datetime'];
			$es_transaction_id = $es_appt_detail['transaction_id'];
			$es_subtotal = $es_appt_detail['sub_total'];
			$es_coupondiscount = $es_appt_detail['discount'];
			$es_freqdiscount = $es_appt_detail['fd_amount'];
			$es_tax = $es_appt_detail['tax'];
			$es_nettotal = $es_appt_detail['net_total'];
			$es_payment_method = $es_appt_detail['payment_method'];
			if($es_payment_method==ucwords('pay-at-venue')){
				if(isset($rzvy_translangArr['pay_at_venue'])){ $es_payment_method = $rzvy_translangArr['pay_at_venue']; }else{ $es_payment_method = $rzvy_defaultlang['pay_at_venue']; } 
			}
			$es_firstname = $es_appt_detail['c_firstname'];
			$es_lastname = $es_appt_detail['c_lastname'];
			$es_email = $es_appt_detail['c_email'];
			$es_phone = $es_appt_detail['c_phone'];
			$es_address = $es_appt_detail['c_address'];
			$es_city = $es_appt_detail['c_city'];
			$es_state = $es_appt_detail['c_state'];
			$es_country = $es_appt_detail['c_country'];
			$es_zip = $es_appt_detail['c_zip'];
			$es_addons_items_arr = $es_appt_detail['addons'];
			$es_reschedule_reason = $es_appt_detail['reschedule_reason'];
			$es_reject_reason = $es_appt_detail['reject_reason'];
			$es_cancel_reason = $es_appt_detail['cancel_reason'];
			include("rzvy_send_sms_email_process.php");
		}
		@ob_clean(); ob_start();
		/********************** Send SMS & Email code END ****************************/
		echo "updated";
	}
}

/** get end time slot ajax for reschedule **/
else if(isset($_POST['get_endtimeslots'])){
	$staff_id = $_POST['staff_id'];
	$booking_end_datetime = date("Y-m-d H:i:s", strtotime($_POST['selected_date']." ".$_POST["selected_startslot"]));
	$booking_datetime = $booking_end_datetime;
	$booking_date = date("Y-m-d", strtotime($booking_datetime));
	$booking_time = date("H:i:s", strtotime($booking_datetime));
	
	$booking_enddate = date("Y-m-d", strtotime($booking_end_datetime));
	$booking_endtime = date("H:i:s", strtotime($booking_end_datetime));
	
	$rzvy_settings_timezone = $obj_settings->get_option("rzvy_timezone");
	$rzvy_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($rzvy_server_timezone,$rzvy_settings_timezone); 

	$selected_enddate = date("Y-m-d", strtotime($booking_enddate));
	$selected_enddate = date($selected_enddate, $currDateTime_withTZ);
	$current_date = date("Y-m-d", $currDateTime_withTZ);

	$isEndTime = true;
	$obj_slots->staff_id = $staff_id;
	$addon_duration = 0;
	$available_slots = $obj_slots->generate_available_slots_dropdown($time_interval, $rzvy_time_format, $selected_enddate, $advance_bookingtime, $currDateTime_withTZ, $isEndTime,$_POST['service_id'], $addon_duration);
	
	/** check for maximum end time slot limit **/
	$rzvy_maximum_endtimeslot_limit = $obj_settings->get_option('rzvy_maximum_endtimeslot_limit');
	$selected_slot_check = strtotime($booking_end_datetime);
	$maximum_endslot_limit = date("Y-m-d H:i:s", strtotime("+".$rzvy_maximum_endtimeslot_limit." minutes", $selected_slot_check)); 
	
	$j = 0;
	$i = 1;
	if(isset($available_slots['slots']) && sizeof($available_slots['slots'])>0){
		foreach($available_slots['slots'] as $slot){
			if(strtotime($selected_enddate." ".$slot) <= strtotime($selected_enddate." ".$booking_time)){
				continue;
			}elseif(strtotime($selected_enddate." ".$slot) > strtotime($maximum_endslot_limit)){
				continue;
			}else{
				$booked_slot_exist = false;
				foreach($available_slots['booked'] as $bslot){
					if($bslot["start_time"] <= strtotime($selected_enddate." ".$slot) && $bslot["end_time"] > strtotime($selected_enddate." ".$slot)){
						$booked_slot_exist = true;
						continue;
					} 
				}
				if($booked_slot_exist){
					break;
				}else{ 
					$blockoff_exist = false;
					if(sizeof($available_slots['block_off'])>0){
						foreach($available_slots['block_off'] as $block_off){
							if((strtotime($selected_enddate." ".$block_off["start_time"]) <= strtotime($selected_enddate." ".$slot)) && (strtotime($selected_enddate." ".$block_off["end_time"]) > strtotime($selected_enddate." ".$slot))){
								$blockoff_exist = true;
								continue;
							} 
						}
					} 
					if($blockoff_exist){
						break;
					} 
					?>
					<option value="<?php echo $slot; ?>" <?php if($booking_endtime == $slot){ echo "selected"; } ?>>
						<?php echo date($rzvy_time_format,strtotime($booking_enddate." ".$slot)); ?>
					</option>
					<?php
					$j++;
				}
			}
			$i++;
		}
	}
	if($j == 0){ 
		if(is_numeric($_POST['service_id']) && $_POST['service_id'] != "0"){
			$time_interval=$obj_slots->get_service_time_interval($_POST['service_id'],$time_interval);
		}
		
		$sdate_stime = strtotime($selected_enddate." ".$booking_time);
		$sdate_etime = date("Y-m-d H:i:s", strtotime("+".$time_interval." minutes", $sdate_stime));
		$sdate_estime = date("H:i:s", strtotime($sdate_etime)); 
		?>
		<option value="<?php echo $sdate_estime; ?>" selected>
			<?php echo date($rzvy_time_format,strtotime($sdate_etime)); ?>
		</option>
		<?php
	}
}

/** get without end time slot ajax for reschedule **/
else if(isset($_POST['get_without_endtimeslots'])){
	$booking_end_datetime = date("Y-m-d H:i:s", strtotime($_POST['selected_date']." ".$_POST["selected_startslot"]));
	$booking_endtime = date("H:i:s", strtotime($booking_end_datetime));
	$booking_enddate = date("Y-m-d", strtotime($booking_end_datetime));	
	if(is_numeric($_POST['service_id']) && $_POST['service_id'] != "0"){
		$time_interval=$obj_slots->get_service_time_interval($_POST['service_id'],$time_interval);
	}
	$sdate_stime = strtotime($booking_end_datetime);
	$sdate_etime = date("Y-m-d H:i:s", strtotime("+".$time_interval." minutes", $sdate_stime));
	$sdate_estime = date("H:i:s", strtotime($sdate_etime)); 
	echo $sdate_estime;
}