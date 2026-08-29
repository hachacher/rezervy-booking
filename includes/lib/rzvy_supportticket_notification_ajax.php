<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_bookings.php");

/* Create object of classes */
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;
$obj_bookings = new rzvy_bookings();
$obj_bookings->conn = $conn;

$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');
$datetime_format = $rzvy_date_format." ".$rzvy_time_format;

if(isset($rzvy_translangArr['by'])){ $label_by = $rzvy_translangArr['by']; }else{ $label_by = $rzvy_defaultlang['by']; }
if(isset($rzvy_translangArr['on'])){ $label_on = $rzvy_translangArr['on']; }else{ $label_on = $rzvy_defaultlang['on']; }

if(isset($_POST['get_notification_detail'])){
	?>
	<center><h5 class="dropdown-menu-titles"><?php if(isset($rzvy_translangArr['support_tickets'])){ $label_completed = $rzvy_translangArr['support_tickets']; }else{ $label_completed = $rzvy_defaultlang['support_tickets']; } ?></h5></center>
	<div class="dropdown-divider"></div>
	<?php
	$all_tickets = $obj_bookings->get_all_latest_unread_supporttickets();
	if(sizeof($all_tickets)>0){
		foreach($all_tickets as $ticketid){
			$ticketdata = $obj_bookings->readone_supportticket($ticketid);
			$customerdata = $obj_bookings->readone_supportticket_customer($ticketdata["generated_by_id"]);
			$customer_name = ucwords($customerdata['firstname']." ".$customerdata['lastname']);
			$event_title = "<b>".ucwords($ticketdata['ticket_title'])."</b> ".$label_by." <b>".$customer_name."</b> ".$label_on." <b>".date($datetime_format, strtotime($ticketdata['generated_on']))."</b>"; 
			?>
			<div class="btn markasread_all_support_ticket_reply" data-id="<?php echo $ticketdata['id']; ?>">
				<div class="row">
					<div class="col-md-12">
						<span class="rzvy_noti_deatil"><?php echo $event_title; ?></span>
					</div>
				</div>
			</div>
			<div class="dropdown-divider"></div>
			<?php
		}
	}else{
		?>
		<center><?php if(isset($rzvy_translangArr['opps_you_have_no_unread_notifications'])){ echo $rzvy_translangArr['opps_you_have_no_unread_notifications']; }else{ echo $rzvy_defaultlang['opps_you_have_no_unread_notifications']; } ?></center>
		<div class="dropdown-divider"></div>
		<?php
	}
}