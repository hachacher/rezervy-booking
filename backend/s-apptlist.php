<?php include 'staff-header.php';  ?>

      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/s-appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['appointments'])){ echo $rzvy_translangArr['appointments']; }else{ echo $rzvy_defaultlang['appointments']; } ?></li>
		<div class="col-md-12">
			<a class="btn btn-sm pull-right" href="<?php echo SITE_URL; ?>backend/s-appointments.php"><i class="fa fa-list"></i> <?php if(isset($rzvy_translangArr['calendar_view'])){ echo $rzvy_translangArr['calendar_view']; }else{ echo $rzvy_defaultlang['calendar_view']; } ?></a>
		</div>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-list"></i> <?php if(isset($rzvy_translangArr['appointment_list'])){ echo $rzvy_translangArr['appointment_list']; }else{ echo $rzvy_defaultlang['appointment_list']; } ?>
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="display responsive nowrap" width="100%" id="rzvy_appt_list_table">
              <thead>
				<tr>
				  <th><?php if(isset($rzvy_translangArr['customer_name'])){ echo $rzvy_translangArr['customer_name']; }else{ echo $rzvy_defaultlang['customer_name']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['customer_email'])){ echo $rzvy_translangArr['customer_email']; }else{ echo $rzvy_defaultlang['customer_email']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['service'])){ echo $rzvy_translangArr['service']; }else{ echo $rzvy_defaultlang['service']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['addons'])){ echo $rzvy_translangArr['addons']; }else{ echo $rzvy_defaultlang['addons']; } ?></th>
				  <?php if($rzvy_book_with_datetime=="Y"){ ?><th><?php if(isset($rzvy_translangArr['booking_datetime'])){ echo $rzvy_translangArr['booking_datetime']; }else{ echo $rzvy_defaultlang['booking_datetime']; } ?></th><?php } ?>
				  <th><?php if(isset($rzvy_translangArr['edit_booking'])){ echo $rzvy_translangArr['edit_booking']; }else{ echo $rzvy_defaultlang['edit_booking']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['download_invoice'])){ echo $rzvy_translangArr['download_invoice']; }else{ echo $rzvy_defaultlang['download_invoice']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['address'])){ echo $rzvy_translangArr['address']; }else{ echo $rzvy_defaultlang['address']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['booking_status'])){ echo $rzvy_translangArr['booking_status']; }else{ echo $rzvy_defaultlang['booking_status']; } ?></th>
				  
				  <th><?php if(isset($rzvy_translangArr['order_id'])){ echo $rzvy_translangArr['order_id']; }else{ echo $rzvy_defaultlang['order_id']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['category'])){ echo $rzvy_translangArr['category']; }else{ echo $rzvy_defaultlang['category']; } ?></th>				  
				  <th><?php if(isset($rzvy_translangArr['customer_phone'])){ echo $rzvy_translangArr['customer_phone']; }else{ echo $rzvy_defaultlang['customer_phone']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['payment_method_nc'])){ echo $rzvy_translangArr['payment_method_nc']; }else{ echo $rzvy_defaultlang['payment_method_nc']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['payment_date_a'])){ echo $rzvy_translangArr['payment_date_a']; }else{ echo $rzvy_defaultlang['payment_date_a']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['transaction_id'])){ echo $rzvy_translangArr['transaction_id']; }else{ echo $rzvy_defaultlang['transaction_id']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['sub_total_nc'])){ echo $rzvy_translangArr['sub_total_nc']; }else{ echo $rzvy_defaultlang['sub_total_nc']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['discount'])){ echo $rzvy_translangArr['discount']; }else{ echo $rzvy_defaultlang['discount']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['referral_discount_a'])){ echo $rzvy_translangArr['referral_discount_a']; }else{ echo $rzvy_defaultlang['referral_discount_a']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['tax_nc'])){ echo $rzvy_translangArr['tax_nc']; }else{ echo $rzvy_defaultlang['tax_nc']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['partial_deposite'])){ echo $rzvy_translangArr['partial_deposite']; }else{ echo $rzvy_defaultlang['partial_deposite']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['net_total_nc'])){ echo $rzvy_translangArr['net_total_nc']; }else{ echo $rzvy_defaultlang['net_total_nc']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['frequently_discount_nc'])){ echo $rzvy_translangArr['frequently_discount_nc']; }else{ echo $rzvy_defaultlang['frequently_discount_nc']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['frequently_discount_amount'])){ echo $rzvy_translangArr['frequently_discount_amount']; }else{ echo $rzvy_defaultlang['frequently_discount_amount']; } ?></th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				if(isset($rzvy_translangArr['pending'])){ $label_pending = $rzvy_translangArr['pending']; }else{ $label_pending = $rzvy_defaultlang['pending']; }
				if(isset($rzvy_translangArr['confirmed_by_you'])){ $label_confirmed_by_you = $rzvy_translangArr['confirmed_by_you']; }else{ $label_confirmed_by_you = $rzvy_defaultlang['confirmed_by_you']; }
				if(isset($rzvy_translangArr['confirmed_by_staff'])){ $label_confirmed_by_staff = $rzvy_translangArr['confirmed_by_staff']; }else{ $label_confirmed_by_staff = $rzvy_defaultlang['confirmed_by_staff']; }
				if(isset($rzvy_translangArr['rescheduled_by_customer'])){ $label_rescheduled_by_customer = $rzvy_translangArr['rescheduled_by_customer']; }else{ $label_rescheduled_by_customer = $rzvy_defaultlang['rescheduled_by_customer']; }
				if(isset($rzvy_translangArr['rescheduled_by_you'])){ $label_rescheduled_by_you = $rzvy_translangArr['rescheduled_by_you']; }else{ $label_rescheduled_by_you = $rzvy_defaultlang['rescheduled_by_you']; }
				if(isset($rzvy_translangArr['rescheduled_by_staff'])){ $label_rescheduled_by_staff = $rzvy_translangArr['rescheduled_by_staff']; }else{ $label_rescheduled_by_staff = $rzvy_defaultlang['rescheduled_by_staff']; }
				if(isset($rzvy_translangArr['cancelled_by_customer'])){ $label_cancelled_by_customer = $rzvy_translangArr['cancelled_by_customer']; }else{ $label_cancelled_by_customer = $rzvy_defaultlang['cancelled_by_customer']; }
				if(isset($rzvy_translangArr['rejected_by_you'])){ $label_rejected_by_you = $rzvy_translangArr['rejected_by_you']; }else{ $label_rejected_by_you = $rzvy_defaultlang['rejected_by_you']; }
				if(isset($rzvy_translangArr['rejected_by_staff'])){ $label_rejected_by_staff = $rzvy_translangArr['rejected_by_staff']; }else{ $label_rejected_by_staff = $rzvy_defaultlang['rejected_by_staff']; }
				if(isset($rzvy_translangArr['completed'])){ $label_completed = $rzvy_translangArr['completed']; }else{ $label_completed = $rzvy_defaultlang['completed']; }
				if(isset($rzvy_translangArr['mark_as_noshow'])){ $label_noshow = $rzvy_translangArr['mark_as_noshow']; }else{ $label_noshow = $rzvy_defaultlang['mark_as_noshow']; }
								
				$ascolor = array(
					"pending" => "#1589FF",
					"confirmed" => "#008000",
					"confirmed_by_staff" => "#008000",
					"rescheduled_by_customer" => "#04B4AE",
					"rescheduled_by_you" => "#6960EC",
					"rescheduled_by_staff" => "#6960EC",
					"cancelled_by_customer" => "#FF4500",
					"rejected_by_you" => "#F70D1A",
					"rejected_by_staff" => "#F70D1A",
					"completed" => "#b7950b",
					"mark_as_noshow" => "#F70D1A",
				);
				$rzvy_apptstatus_colorscheme = $obj_settings->get_option("rzvy_apptstatus_colorscheme");
				if($rzvy_apptstatus_colorscheme != ""){
					$astatuscscheme = json_decode(htmlspecialchars_decode($rzvy_apptstatus_colorscheme));
					if ($astatuscscheme !== false) {
						if(isset($astatuscscheme->pending) && $astatuscscheme->pending != ""){ $ascolor["pending"] = $astatuscscheme->pending; }
						if(isset($astatuscscheme->confirmed) && $astatuscscheme->confirmed != ""){ $ascolor["confirmed"] = $astatuscscheme->confirmed; }
						if(isset($astatuscscheme->confirmed_by_staff) && $astatuscscheme->confirmed_by_staff != ""){ $ascolor["confirmed_by_staff"] = $astatuscscheme->confirmed_by_staff; }
						if(isset($astatuscscheme->rescheduled_by_customer) && $astatuscscheme->rescheduled_by_customer != ""){ $ascolor["rescheduled_by_customer"] = $astatuscscheme->rescheduled_by_customer; }
						if(isset($astatuscscheme->rescheduled_by_you) && $astatuscscheme->rescheduled_by_you != ""){ $ascolor["rescheduled_by_you"] = $astatuscscheme->rescheduled_by_you; }
						if(isset($astatuscscheme->rescheduled_by_staff) && $astatuscscheme->rescheduled_by_staff != ""){ $ascolor["rescheduled_by_staff"] = $astatuscscheme->rescheduled_by_staff; }
						if(isset($astatuscscheme->cancelled_by_customer) && $astatuscscheme->cancelled_by_customer != ""){ $ascolor["cancelled_by_customer"] = $astatuscscheme->cancelled_by_customer; }
						if(isset($astatuscscheme->rejected_by_you) && $astatuscscheme->rejected_by_you != ""){ $ascolor["rejected_by_you"] = $astatuscscheme->rejected_by_you; }
						if(isset($astatuscscheme->rejected_by_staff) && $astatuscscheme->rejected_by_staff != ""){ $ascolor["rejected_by_staff"] = $astatuscscheme->rejected_by_staff; }
						if(isset($astatuscscheme->completed) && $astatuscscheme->completed != ""){ $ascolor["completed"] = $astatuscscheme->completed; }
						if(isset($astatuscscheme->mark_as_noshow) && $astatuscscheme->mark_as_noshow != ""){ $ascolor["mark_as_noshow"] = $astatuscscheme->mark_as_noshow; }
					}
				} 
				
				$status_array = array(
					'pending' => '<label style="color: '.$ascolor["pending"].'"><i class="fa fa-info-circle"></i> '.$label_pending.'</label>',
					'confirmed' => '<label style="color: '.$ascolor["confirmed"].'"><i class="fa fa-check"></i> '.$label_confirmed_by_you.'</label>',
					'confirmed_by_staff' => '<label style="color: '.$ascolor["confirmed_by_staff"].'"><i class="fa fa-check"></i> '.$label_confirmed_by_staff.'</label>',
					'rescheduled_by_customer' => '<label style="color: '.$ascolor["rescheduled_by_customer"].'"><i class="fa fa-refresh"></i> '.$label_rescheduled_by_customer.'</label>',
					'rescheduled_by_you' => '<label style="color: '.$ascolor["rescheduled_by_you"].'"><i class="fa fa-repeat"></i> '.$label_rescheduled_by_you.'</label>',
					'rescheduled_by_staff' => '<label style="color: '.$ascolor["rescheduled_by_staff"].'"><i class="fa fa-repeat"></i> '.$label_rescheduled_by_staff.'</label>',
					'cancelled_by_customer' => '<label style="color: '.$ascolor["cancelled_by_customer"].'"><i class="fa fa-close"></i> '.$label_cancelled_by_customer.'</label>',
					'rejected_by_you' => '<label style="color: '.$ascolor["rejected_by_you"].'"><i class="fa fa-ban"></i> '.$label_rejected_by_you.'</label>',
					'rejected_by_staff' => '<label style="color: '.$ascolor["rejected_by_staff"].'"><i class="fa fa-ban"></i> '.$label_rejected_by_staff.'</label>',
					'completed' => '<label style="color: '.$ascolor["completed"].'"><i class="fa fa-calendar-check-o"></i> '.$label_completed.'</label>',
					'mark_as_noshow' => '<label style="color: '.$ascolor["mark_as_noshow"].'"><i class="fa fa-calendar-check-o"></i> '.$label_noshow.'</label>'
				);
				$obj_bookings->staff_id = 'none';
				if(isset($_SESSION['staff_id'])){
					$obj_bookings->staff_id = $_SESSION['staff_id'];
				}
				$all_bookings_list = $obj_bookings->get_all_appointments_listview_by_staff();
				$rzvy_date_format = $obj_settings->get_option("rzvy_date_format");
				$rzvy_time_format = $obj_settings->get_option("rzvy_time_format");
				$rzvy_currency_symbol = $obj_settings->get_option('rzvy_currency_symbol');
				$rzvy_currency_position = $obj_settings->get_option('rzvy_currency_position');
				if(mysqli_num_rows($all_bookings_list)>0){
					while($booking = mysqli_fetch_assoc($all_bookings_list)){
						$flag = true;
						$addons_detail = '';
						$unserialized_addons = unserialize($booking['addons']);
						foreach($unserialized_addons as $addon){
							$obj_addons->id = $addon['id'];
							$addon_name = $obj_addons->get_addon_name();
							if($flag){
								$addons_detail .= $addon['qty']." ".$addon_name." of ".$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$addon['rate']);
								$flag = false;
							}else{
								$addons_detail .= "<hr class='rzvy_hr' />".$addon['qty']." ".$addon_name." of ".$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$addon['rate']);
							}
						}
						
						$staff_name = "";
						if($booking['staff_id']>0){
							$obj_staff->id = $booking['staff_id'];
							$staff_name = $obj_staff->get_staff_name();
						}
						
						$service_detail = ucwords($booking['title'])." of ".$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$booking['service_rate']);
						$booking_datetime = date($rzvy_date_format, strtotime($booking['booking_datetime']))." ".date($rzvy_time_format, strtotime($booking['booking_datetime']));
						
						$payment_method = ucwords($booking['payment_method']);
						if($payment_method==ucwords('pay-at-venue')){
							if(isset($rzvy_translangArr['pay_at_venue'])){ $payment_method = $rzvy_translangArr['pay_at_venue']; }else{ $payment_method = $rzvy_defaultlang['pay_at_venue']; } 
						}
						?>
						<tr>
						  <td><?php echo $booking['c_firstname']." ".$booking['c_lastname']; ?></td>	
						  <td><?php echo $booking['c_email']; ?></td>
						  <td><?php echo $service_detail; ?></td>
						  <td><?php if($addons_detail != ""){ echo $addons_detail; }else{ echo '<center><i class="fa fa-minus"></i></center>'; } ?></td>
						  <?php if($rzvy_book_with_datetime=="Y"){ ?><td><?php echo $booking_datetime; ?></td><?php } ?>
						  <td><a href="javascript:void(0)" class="apptlist_open_detail_modal" data-oid="<?php echo $booking['order_id']; ?>"><i class="fa fa-edit fa-2x"></i></a></td>
						  <td><a class="btn btn-link" href="<?php $invoice_uid = base64_encode($booking["customer_id"]); $invoice_oid = base64_encode($booking['order_id']); echo SITE_URL."/invoice/?invoice=".base64_encode("###_____".$invoice_uid."_____&&&_____".$invoice_oid); ?>" target="_blank"><i class="fa fa-files-o fa-2x"></i></a></td>
						  <td><?php echo $booking['c_address']; ?></td>
						  <td><?php echo $status_array[$booking['booking_status']]; ?></td>						  
						  <td><?php echo $booking['order_id']; ?></td>
						  <td><?php echo ucwords($booking['cat_name']); ?></td>						  
						  <td><?php echo $booking['c_phone']; ?></td>
						  <td><?php echo $payment_method; ?></td>
						  <td><?php echo date($rzvy_date_format, strtotime($booking['payment_date'])); ?></td>
						  <td><?php if($booking['transaction_id'] != ""){ echo $booking['transaction_id']; }else{ echo '<i class="fa fa-minus"></i>'; } ?></td>
						  <td><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$booking['sub_total']); ?></td>
						  <td><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$booking['discount']); ?></td>
						  <td><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$booking['refer_discount']); ?></td>
						  <td><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$booking['tax']); ?></td>
						  <td><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$booking['partial_deposite']); ?></td>
						  <td><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$booking['net_total']); ?></td>
						  <td><?php if($booking['fd_key'] != ""){ if($booking['fd_key']=="weekly"){ echo $fq_weekly_label; }else if($booking['fd_key']=="bi weekly"){ echo $fq_biweekly_label; }else if($booking['fd_key']=="monthly"){ echo $fq_monthly_label; }else{ echo $fq_one_time_label; } }else{ echo '<center><i class="fa fa-minus"></i></center>'; } ?></td>
						  <td><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$booking['fd_amount']); ?></td>
						</tr>
						<?php 
					}
				} 
				?>
			</tbody>
           </table>
          </div>
        </div>
      </div>
<?php include 'staff-footer.php'; ?>