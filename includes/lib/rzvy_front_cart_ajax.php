<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
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

$rzvy_book_with_datetime = $obj_settings->get_option('rzvy_book_with_datetime');
$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');
$rzvy_price_display = $obj_settings->get_option("rzvy_price_display");



/* add to cart item ajax */
if(isset($_POST['add_to_cart_item'])){
	$id = $_POST['id'];
	$qty = $_POST['qty'];
	
	$rzvy_tax_status = $obj_settings->get_option('rzvy_tax_status');
	$rzvy_tax_type = $obj_settings->get_option('rzvy_tax_type');
	$rzvy_tax_value = $obj_settings->get_option('rzvy_tax_value');
	
	if($_POST['qty']>0){
		/** Add and update item into cart **/
		$obj_frontend->addon_id = $id;
		$addon_rate = $obj_frontend->get_addon_rate();
		$rate = ($addon_rate['rate']*$qty);
		$duration = ($addon_rate['duration']*$qty);
		
		$item_arr = array();
		$item_arr['id'] = $id;
		$item_arr['qty'] = $qty;
		$item_arr['rate'] = $rate;
		$item_arr['duration'] = $duration;
		
		$cart_item_key = $obj_frontend->rzvy_check_existing_cart_item($_SESSION['rzvy_cart_items'], $id);
		if(is_numeric($cart_item_key)){
			$_SESSION['rzvy_cart_items'][$cart_item_key] = $item_arr;
			$_SESSION['rzvy_cart_items'] = array_values($_SESSION['rzvy_cart_items']);
		}else{
			array_push($_SESSION['rzvy_cart_items'], $item_arr);
			$_SESSION['rzvy_cart_items'] = array_values($_SESSION['rzvy_cart_items']);
		}
		
		$subtotal = $_SESSION['rzvy_cart_service_price'];
		foreach($_SESSION['rzvy_cart_items'] as $val){ 
			$subtotal = $subtotal+$val['rate'];
		}
				
		$rzvy_referral_discount_type = $obj_settings->get_option('rzvy_referral_discount_type');
		$rzvy_referral_discount_value = $obj_settings->get_option('rzvy_referral_discount_value');
		$obj_frontend->rzvy_cart_item_calculation($subtotal, $rzvy_tax_status, $rzvy_tax_type, $rzvy_tax_value, $rzvy_referral_discount_type, $rzvy_referral_discount_value);
	}else{
		/** remove item from cart **/	
		$subtotal = $_SESSION['rzvy_cart_service_price'];
		foreach($_SESSION['rzvy_cart_items'] as $val){ 
			$subtotal = $subtotal+$val['rate'];
		} 
		$cart_item_key = $obj_frontend->rzvy_check_existing_cart_item($_SESSION['rzvy_cart_items'], $id);
		if(is_numeric($cart_item_key)){
			$subtotal = $subtotal-$_SESSION['rzvy_cart_items'][$cart_item_key]['rate'];
			$rzvy_referral_discount_type = $obj_settings->get_option('rzvy_referral_discount_type');
			$rzvy_referral_discount_value = $obj_settings->get_option('rzvy_referral_discount_value');
			$obj_frontend->rzvy_cart_item_calculation($subtotal, $rzvy_tax_status, $rzvy_tax_type, $rzvy_tax_value, $rzvy_referral_discount_type, $rzvy_referral_discount_value);
			unset($_SESSION['rzvy_cart_items'][$cart_item_key]);
			$_SESSION['rzvy_cart_items'] = array_values($_SESSION['rzvy_cart_items']);
		}
	}
}

/* refresh cart sidebar ajax */
else if(isset($_POST['refresh_cart_sidebar'])){ 
	if($_SESSION['rzvy_cart_service_id']!="" && $_SESSION['rzvy_cart_service_id']>0){
		$rzvy_currency_symbol = $obj_settings->get_option('rzvy_currency_symbol');
		$rzvy_currency_position = $obj_settings->get_option('rzvy_currency_position');
		if(isset($_POST["payment_method"])){ 
			$obj_frontend->payment_method = "offline";
			if(isset($_POST["is_partial"])){
				if($_POST["is_partial"] == "true" || $_POST["is_partial"] == "on"){
					$obj_frontend->payment_method = $_POST['payment_method'];
				}
			}
			$subtotal = $_SESSION['rzvy_cart_subtotal'];
									
			$rzvy_tax_status = $obj_settings->get_option('rzvy_tax_status');
			$rzvy_tax_type = $obj_settings->get_option('rzvy_tax_type');
			$rzvy_tax_value = $obj_settings->get_option('rzvy_tax_value');
			$rzvy_referral_discount_type = $obj_settings->get_option('rzvy_referral_discount_type');
			$rzvy_referral_discount_value = $obj_settings->get_option('rzvy_referral_discount_value');
			$obj_frontend->rzvy_cart_item_calculation($subtotal, $rzvy_tax_status, $rzvy_tax_type, $rzvy_tax_value, $rzvy_referral_discount_type, $rzvy_referral_discount_value);
		}
		?>
		<table class="table">
		  <!--<thead>
			<tr>
			  <th><?php if(isset($rzvy_translangArr['services'])){ echo $rzvy_translangArr['services']; }else{ echo $rzvy_defaultlang['services']; } ?></th>
			  <th><?php if(isset($rzvy_translangArr['amount'])){ echo $rzvy_translangArr['amount']; }else{ echo $rzvy_defaultlang['amount']; } ?></th>
			</tr>
		  </thead>-->
		<tbody>
			<?php 
			if($_SESSION['rzvy_cart_freqdiscount_key'] != ""){ 
				?>
				<tr>
				  <td><i class="fa fa-refresh" aria-hidden="true"></i> &nbsp; <?php echo $_SESSION['rzvy_cart_freqdiscount_label']; ?></td>
				  <td></td>
				</tr>
			<?php 
			} ?>
			<tr>
			  <td><i class="fa fa-bookmark" aria-hidden="true"></i> &nbsp; <?php 
					$obj_frontend->category_id = $_SESSION['rzvy_cart_category_id'];
					$category_name = $obj_frontend->readone_category_name(); 
					echo ucwords($category_name); 
					?></td>
			  <td></td>
			</tr>
			<tr>
			  <td><i class="fa fa-paint-brush" aria-hidden="true"></i> &nbsp; <?php 
					$obj_frontend->service_id = $_SESSION['rzvy_cart_service_id'];
					$readone_service = $obj_frontend->readone_service(); 
					$service_name = ucwords($readone_service["title"]); 
					$service_rate = $readone_service['rate']; 
					echo ucwords($service_name); ?></td>
			  <td><?php if($rzvy_price_display=='Y'){ if($service_rate>0){ echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$service_rate); } } ?></td>
			</tr>

			<?php 
			if($_SESSION['rzvy_cart_datetime'] != "" && $_SESSION['rzvy_cart_end_datetime'] != "" && $rzvy_book_with_datetime == "Y"){
				$rzvy_cart_date = date($rzvy_date_format, strtotime($_SESSION['rzvy_cart_datetime'])); 
				$rzvy_cart_starttime = date($rzvy_time_format, strtotime($_SESSION['rzvy_cart_datetime'])); 
				$rzvy_cart_endtime = date($rzvy_time_format, strtotime($_SESSION['rzvy_cart_end_datetime'])); 
				?>
				<tr>
				  <td><i class="fa fa-calendar" aria-hidden="true"></i> &nbsp; <?php echo $rzvy_cart_date." ".$rzvy_cart_starttime." to ".$rzvy_cart_endtime; ?></td>
				  <td></td>
				</tr>
			<?php 
			} 
			?>
			<?php  
			if(sizeof($_SESSION['rzvy_cart_items'])>0){						
				foreach($_SESSION['rzvy_cart_items'] as $val){ 
					$seladdonid = $val['id'];
					$obj_frontend->addon_id = $seladdonid;
					$addon_name = $obj_frontend->readone_addon_name(); 
					?>
					<tr>
					  <td><a class="rzvy_remove_addon_from_cart" href="javascript:void(0)" data-id="<?php echo $val['id']; ?>"><img src="<?php echo SITE_URL;?>/includes/images/delete.svg" alt="Delete" width="15" class="rzvy_remove_addon_icon"></a> &nbsp; <?php echo ucwords($addon_name); if($val['qty']>1){ echo " - ".$val['qty']; } ?></td>
					  <td><?php if($rzvy_price_display=='Y'){ if($val['rate']>0){  echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$val['rate']); } } ?></td>
					</tr>
					<?php 
				} 
			}
			
			/* Services Package Booking Summary */
			$Rzvy_Hooks->do_action('services_package_cartsummary_onepage', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang,$rzvy_currency_symbol,$rzvy_currency_position));
			/* Services Package Booking Summary End */
									
			if(isset($_SESSION['rzvy_cart_subtotal']) && $rzvy_price_display=='Y'){
				?>
				<tr class="rzvy_subtotal_exit">
				  <th><i class="fa fa-money" aria-hidden="true"></i> &nbsp; <?php if(isset($rzvy_translangArr['sub_total'])){ echo $rzvy_translangArr['sub_total']; }else{ echo $rzvy_defaultlang['sub_total']; } ?></th>
				  <th><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$_SESSION['rzvy_cart_subtotal']); ?></th>
				</tr>
				<?php 
				if($_SESSION['rzvy_cart_lpoint_price']>0){ 
					?>
					<tr class="rzvy_subtotal_exit">
					  <th><i class="fa fa-percent" aria-hidden="true"></i> &nbsp; <?php if(isset($rzvy_translangArr['loyalty_points'])){ echo $rzvy_translangArr['loyalty_points']; }else{ echo $rzvy_defaultlang['loyalty_points']; } ?></th>
					  <th>-<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$_SESSION['rzvy_cart_lpoint_price']); ?></th>
					</tr>
				<?php 
				} 
				if($_SESSION['rzvy_cart_freqdiscount']>0){ 
					?>
					<tr class="rzvy_subtotal_exit">
					  <th><i class="fa fa-percent" aria-hidden="true"></i>  &nbsp; <?php if(isset($rzvy_translangArr['frequently_discount'])){ echo $rzvy_translangArr['frequently_discount']; }else{ echo $rzvy_defaultlang['frequently_discount']; } ?></th>
					  <th>-<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$_SESSION['rzvy_cart_freqdiscount']); ?></th>
					</tr>
				<?php 
				} 
				if($_SESSION['rzvy_cart_coupondiscount']>0){ 
					?>
					<tr class="rzvy_subtotal_exit">
					  <th><i class="fa fa-ticket" aria-hidden="true"></i>   &nbsp; <?php if(isset($rzvy_translangArr['coupon_discount'])){ echo $rzvy_translangArr['coupon_discount']; }else{ echo $rzvy_defaultlang['coupon_discount']; } ?></th>
					  <th>-<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$_SESSION['rzvy_cart_coupondiscount']); ?></th>
					</tr>
				<?php 
				} 
				if($_SESSION['rzvy_referral_discount_amount']>0){ 
					?>
					<tr class="rzvy_subtotal_exit">
					  <th><i class="fa fa-gift" aria-hidden="true"></i>   &nbsp; <?php if(isset($_SESSION["referralcode_applied"]) && $_SESSION["referralcode_applied"] =="Y"){ ?>
								<?php if(isset($rzvy_translangArr['referral_discount_a'])){ echo $rzvy_translangArr['referral_discount_a']; }else{ echo $rzvy_defaultlang['referral_discount_a']; } ?>
							<?php } else{ ?>
								<?php if(isset($rzvy_translangArr['referral_coupon_discount'])){ echo $rzvy_translangArr['referral_coupon_discount']; }else{ echo $rzvy_defaultlang['referral_coupon_discount']; } ?>
							<?php } ?></th>
					  <th>-<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$_SESSION['rzvy_referral_discount_amount']); ?></th>
					</tr>
				<?php 
				} 
				if($_SESSION['rzvy_cart_tax']>0){ 
					?>
					<tr class="rzvy_subtotal_exit">
					  <th><i class="fa fa-tags" aria-hidden="true"></i>    &nbsp; <?php if(isset($rzvy_translangArr['tax'])){ echo $rzvy_translangArr['tax']; }else{ echo $rzvy_defaultlang['tax']; } ?></th>
					  <th>+<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$_SESSION['rzvy_cart_tax']); ?></th>
					</tr>
					<?php 
				}				
			} 
			if($rzvy_price_display=='Y'){ ?>
					<tr class="rzvy_subtotal_exit">
					  <th><i class="fa fa-tags" aria-hidden="true"></i>    &nbsp; <?php if(isset($rzvy_translangArr['net_total'])){ echo $rzvy_translangArr['net_total']; }else{ echo $rzvy_defaultlang['net_total']; } ?></th>
					  <th id="rzvy_net_total_amount" data-amount="<?php echo $_SESSION['rzvy_cart_nettotal'];?>"><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$_SESSION['rzvy_cart_nettotal']); ?></th>
					</tr>
			<?php }
			?>
		</tbody>
       </table>
		<input type="hidden" class='rzvy_cart_pd_amount' value='<?php echo $_SESSION['rzvy_cart_partial_deposite']; ?>' />
		<input type="hidden" class='rzvy_cart_lp_amount' data-lpointleft="<?php echo $_SESSION['rzvy_lpoint_left']; ?>" data-lpointused="<?php echo $_SESSION['rzvy_lpoint_used']; ?>" data-lpointtotal="<?php echo $_SESSION['rzvy_lpoint_total']; ?>" data-lpointcart="<?php echo $_SESSION['rzvy_cart_lpoint']; ?>" value='<?php echo $_SESSION['rzvy_lpoint_price']; ?>' />
		<?php 
	}else{ 
		?>
		<label><?php if(isset($rzvy_translangArr['no_items_in_cart'])){ echo $rzvy_translangArr['no_items_in_cart']; }else{ echo $rzvy_defaultlang['no_items_in_cart']; } ?></label>
		<?php 
	}
}
/* Re-Fetch Payment Methods */
else if(isset($_POST['get_payment_methods'])){ 
	if($_SESSION['rzvy_cart_nettotal']>0){
		$rzvy_stepview_alignment = $obj_settings->get_option("rzvy_stepview_alignment"); 
		if($rzvy_stepview_alignment == "center"){
			$alignmentClass = "justify-content-center";
			$labelAlignmentClass = "class='d-flex flex-wrap justify-content-center'";
			$labelAlignmentClassName = "d-flex flex-wrap justify-content-center";
			$inputAlignment = "text-center";
		}else if($rzvy_stepview_alignment == "right"){
			$alignmentClass = "justify-content-end";
			$labelAlignmentClass = "class='d-flex flex-wrap justify-content-end'";
			$labelAlignmentClassName = "d-flex flex-wrap justify-content-end";
			$inputAlignment = "text-right";
		}else{
			$alignmentClass = "";
			$labelAlignmentClass = "";
			$labelAlignmentClassName = "";
			$inputAlignment = "";
		}
		if(($obj_settings->get_option("rzvy_pay_at_venue_status") == "Y" && $obj_settings->get_option("rzvy_showhide_pay_at_venue") == "Y") || $obj_settings->get_option("rzvy_paypal_payment_status") == "Y" || $obj_settings->get_option("rzvy_stripe_payment_status") == "Y" || $obj_settings->get_option("rzvy_authorizenet_payment_status") == "Y" || $obj_settings->get_option("rzvy_twocheckout_payment_status") == "Y" || $obj_settings->get_option("rzvy_bank_transfer_payment_status") == "Y"){ 
			?>
			<h4 class="pb-2  mt-3 <?php echo $inputAlignment; ?>"><?php if(isset($rzvy_translangArr['payment_method'])){ echo $rzvy_translangArr['payment_method']; }else{ echo $rzvy_defaultlang['payment_method']; } ?> </h5>
			<?php
		}
		$show_hide_payatvenue = "";
		if(
			$obj_settings->get_option("rzvy_stripe_payment_status") != "Y" && 
			$obj_settings->get_option("rzvy_authorizenet_payment_status") != "Y" && 
			$obj_settings->get_option("rzvy_twocheckout_payment_status") != "Y" && 
			$obj_settings->get_option("rzvy_bank_transfer_payment_status") != "Y" && 
			$obj_settings->get_option("rzvy_paypal_payment_status") != "Y" && 
			$obj_settings->get_option("rzvy_pay_at_venue_status") == "Y" && 
			$obj_settings->get_option("rzvy_showhide_pay_at_venue") != "Y"){ 
				$show_hide_payatvenue = "style='display:none;'";
		}
		
		?>
		<div class="custom-controls rzvy-payments" <?php echo $show_hide_payatvenue; ?>>
			<div class="row justify-content-center">
			<?php 
			if($obj_settings->get_option("rzvy_pay_at_venue_status") == "Y"){ 
				?>
				<div class="col-sm-6">
					<div class="form-check custom inline">
						<input type="radio" class="form-check-input rzvy-payment-method-check" id="rzvy-pay-at-venue" name="rzvy-payment-method-radio" value="pay-at-venue" checked />
						<label class="form-check-label" for="rzvy-pay-at-venue"><?php if(isset($rzvy_translangArr['pay_at_venue'])){ echo $rzvy_translangArr['pay_at_venue']; }else{ echo $rzvy_defaultlang['pay_at_venue']; } ?></label>
					</div>
				</div>	
				<?php 
			} 
			if($obj_settings->get_option("rzvy_bank_transfer_payment_status") == "Y" && $rzvy_price_display=='Y'){ 
				?>
				<div class="col-sm-6">
					<div class="form-check custom inline">
						<input type="radio" class="form-check-input rzvy-payment-method-check" id="rzvy-bank-transfer" name="rzvy-payment-method-radio" value="bank transfer" <?php if($obj_settings->get_option("rzvy_pay_at_venue_status") != "Y"){ echo "checked"; } ?> data-toggle="rzvy-bank-payment"/>
						<label class="form-check-label" for="rzvy-bank-transfer"><?php if(isset($rzvy_translangArr['bank_transfer'])){ echo $rzvy_translangArr['bank_transfer']; }else{ echo $rzvy_defaultlang['bank_transfer']; } ?></label>
					</div>
				</div>	
				<?php 
			} 
			if($obj_settings->get_option("rzvy_paypal_payment_status") == "Y"  && $rzvy_price_display=='Y'){ 
				?>
				<div class="col-sm-6">
					<div class="form-check custom inline">
						<input type="radio" class="form-check-input rzvy-payment-method-check" id="rzvy-paypal-payment" name="rzvy-payment-method-radio" value="paypal" />
						<label class="form-check-label" for="rzvy-paypal-payment"><?php if(isset($rzvy_translangArr['paypal'])){ echo $rzvy_translangArr['paypal']; }else{ echo $rzvy_defaultlang['paypal']; } ?></label>
					</div>
				</div>
				<?php 
			} 
			if($obj_settings->get_option("rzvy_stripe_payment_status") == "Y" && $obj_settings->get_option("rzvy_authorizenet_payment_status") == "N" && $obj_settings->get_option("rzvy_twocheckout_payment_status") == "N"  && $rzvy_price_display=='Y'){ 
				$payment_method = "stripe";
			} else if($obj_settings->get_option("rzvy_stripe_payment_status") == "N" && $obj_settings->get_option("rzvy_authorizenet_payment_status") == "Y" && $obj_settings->get_option("rzvy_twocheckout_payment_status") == "N"  && $rzvy_price_display=='Y'){ 
				$payment_method = "authorize.net";
			}  else if($obj_settings->get_option("rzvy_stripe_payment_status") == "N" && $obj_settings->get_option("rzvy_authorizenet_payment_status") == "N" && $obj_settings->get_option("rzvy_twocheckout_payment_status") == "Y"  && $rzvy_price_display=='Y'){ 
				$payment_method = "2checkout";
			} else{
				$payment_method = "N";
			}
			if($payment_method != "N"){ 
				?>
				<div class="col-sm-6">
					<div class="form-check custom inline">
						<input type="radio" class="form-check-input rzvy-payment-method-check" id="rzvy-card-payment" name="rzvy-payment-method-radio" value="<?php echo $payment_method; ?>"  data-toggle="rzvy-card-payment"/>
						<label class="form-check-label" for="rzvy-card-payment"><?php if(isset($rzvy_translangArr['card_payment'])){ echo $rzvy_translangArr['card_payment']; }else{ echo $rzvy_defaultlang['card_payment']; } ?></label>
					</div>
				</div>	
				<?php 
			} 
			?>
		</div> 
	</div><?php 
	}else{ ?>
		<div class="row mt-2">
			<div class="rzvy-payments ml-3">
				<input type="radio" class="rzvy-payment-method-check" id="rzvy-pay-at-venue" name="rzvy-payment-method-radio" value="pay-at-venue" checked style="display:none;" />
			</div>
		</div>
	<?php }
}
/* add to cart item ajax */
else if(isset($_POST['add_to_cart_package'])){
	$id = $_POST['id'];
	$qty = $_POST['qty'];
	
	$rzvy_tax_status = $obj_settings->get_option('rzvy_tax_status');
	$rzvy_tax_type = $obj_settings->get_option('rzvy_tax_type');
	$rzvy_tax_value = $obj_settings->get_option('rzvy_tax_value');
	
	if($_POST['qty']>0){
		/** Add and update item into cart **/
		$obj_frontend->package_id = $id;
		$package_rate = $obj_frontend->get_package_rate();
		$rate = ($package_rate['rate']*$qty);
		$duration = ($package_rate['duration']*$qty);
		
		$item_arr = array();
		$item_arr['id'] = $id;
		$item_arr['qty'] = $qty;
		$item_arr['rate'] = $rate;
		$item_arr['duration'] = $duration;
		if(isset($_SESSION['add_to_cart_package'])){
			$cart_item_key = $obj_frontend->rzvy_check_existing_cart_item($_SESSION['add_to_cart_package'], $id);
		}else{
			$cart_item_key = '';
		}
		
		if(is_numeric($cart_item_key)){
			$_SESSION['add_to_cart_package'][$cart_item_key] = $item_arr;
			$_SESSION['add_to_cart_package'] = array_values($_SESSION['add_to_cart_package']);
		}else{
			if(isset($_SESSION['add_to_cart_package'])){
				array_push($_SESSION['add_to_cart_package'], $item_arr);
			}else{
				$_SESSION['add_to_cart_package'] = array();
				array_push($_SESSION['add_to_cart_package'], $item_arr);
				$_SESSION['add_to_cart_package'] = array_values($_SESSION['add_to_cart_package']);
			}
		}
		
		$subtotal = $_SESSION['rzvy_cart_service_price'];
		foreach($_SESSION['add_to_cart_package'] as $val){ 
			$subtotal = $subtotal+$val['rate'];
		}
				
		$rzvy_referral_discount_type = $obj_settings->get_option('rzvy_referral_discount_type');
		$rzvy_referral_discount_value = $obj_settings->get_option('rzvy_referral_discount_value');
		$obj_frontend->rzvy_cart_item_calculation($subtotal, $rzvy_tax_status, $rzvy_tax_type, $rzvy_tax_value, $rzvy_referral_discount_type, $rzvy_referral_discount_value);
	}else{
		/** remove item from cart **/	
		$subtotal = $_SESSION['rzvy_cart_service_price'];
		foreach($_SESSION['add_to_cart_package'] as $val){ 
			$subtotal = $subtotal+$val['rate'];
		} 
		$cart_item_key = $obj_frontend->rzvy_check_existing_cart_item($_SESSION['add_to_cart_package'], $id);
		if(is_numeric($cart_item_key)){
			$subtotal = $subtotal-$_SESSION['add_to_cart_package'][$cart_item_key]['rate'];
			$rzvy_referral_discount_type = $obj_settings->get_option('rzvy_referral_discount_type');
			$rzvy_referral_discount_value = $obj_settings->get_option('rzvy_referral_discount_value');
			$obj_frontend->rzvy_cart_item_calculation($subtotal, $rzvy_tax_status, $rzvy_tax_type, $rzvy_tax_value, $rzvy_referral_discount_type, $rzvy_referral_discount_value);
			unset($_SESSION['add_to_cart_package'][$cart_item_key]);
			$_SESSION['add_to_cart_package'] = array_values($_SESSION['add_to_cart_package']);
		}
	}
}
/* add to cart item ajax */
if(isset($_POST['package_credit_use'])){
	if($_POST['package_credit_use']=='Y'){
		$selectedpackagesids = array();
		if(isset($_SESSION['add_to_cart_package']) && sizeof($_SESSION['add_to_cart_package'])>0){ 
			foreach($_SESSION['add_to_cart_package'] as $val){ 
				$selectedpackagesids[] = $val['id'];
			}
		}
		$selectedpackagesidsactive = array();
		if(isset($_SESSION['add_to_cart_package_active']) && sizeof($_SESSION['add_to_cart_package_active'])>0){ 			
			foreach($_SESSION['add_to_cart_package_active'] as $val){ 
				$selectedpackagesidsactive[] = $val['id'];
			}
		}
		if(sizeof($selectedpackagesids)>0 || sizeof($selectedpackagesidsactive)>0){
			$packages_info_rows = 0;
			$activepackages_info_rows = 0;
			if(sizeof($selectedpackagesids)>0){
				$obj_frontend->package_id = implode(',',$selectedpackagesids);
				$packages_info = $obj_frontend->read_package_by_ids();
				$packages_info_rows = mysqli_num_rows($packages_info);
			}
			
			if(sizeof($selectedpackagesidsactive)>0){
				$obj_frontend->package_id = implode(',',$selectedpackagesidsactive);
				$activepackages_info = $obj_frontend->read_package_by_transids();
				$activepackages_info_rows = mysqli_num_rows($activepackages_info);
			}
			
			if($packages_info_rows>0 || $activepackages_info_rows>0 ){
				$packagediscountaddons = array();
				$packagediscountservice = array();
				$packagediscountserviceprice = 0;
				$packagediscountcredtiuse = array();
				$packagediscountcredtiuseactive = array();
				/* Active Packages Discount Credit Usage */
				if($activepackages_info_rows>0){
					while($services_packagesactive = mysqli_fetch_array($activepackages_info)){
						$packagecreditusediscountactive = array();
						$packagediscontamountactive = 0;
						
						$servicesremainsarr = array();
						if($services_packagesactive['services_remain']!=''){
							$servicesremainsarr = explode(',',$services_packagesactive['services_remain']);
						}					
						if(in_array($_SESSION['rzvy_cart_service_id'],$servicesremainsarr) && $services_packagesactive['services_limit_remain']!=''){
							$serviceslimits = unserialize($services_packagesactive['services_limit_remain']);					
							foreach($serviceslimits as $servicesid => $serviceslimit){
								if($servicesid==$_SESSION['rzvy_cart_service_id'] && !in_array($servicesid,$packagediscountservice)){
									$packagediscountservice[] = $servicesid;
									$obj_frontend->service_id = $servicesid;
									$readone_service = $obj_frontend->readone_service();  
									$packagediscountserviceprice += $readone_service['rate'];
									$packagediscontamountactive += $readone_service['rate'];
									$packagecreditusediscountactive['service'] = $servicesid;
									$packagecreditusediscountactive['package_id'] = $services_packagesactive['id'];
								}
							}						
						}
						if(sizeof($_SESSION['rzvy_cart_items'])>0 && $services_packagesactive['addons_limit_remain']!=''){
							$addonslimits = unserialize($services_packagesactive['addons_limit_remain']);
							$packagecredituseaddons = array();
							foreach($addonslimits as $addonsid => $addonslimit){
								foreach($_SESSION['rzvy_cart_items'] as $val){ 
									if($val['id']==$addonsid && !isset($packagediscountaddons[$addonsid])){
										if($val['qty']>=$addonslimit && $addonslimit!='0'){
											$addonsingleqtyrate = $val['rate']/$val['qty'];
											$packagecreditaddondiscount = $addonsingleqtyrate*$addonslimit;
											$packagediscountserviceprice += $packagecreditaddondiscount;
											$packagediscontamountactive += $packagecreditaddondiscount;
											$packagediscountaddons[$addonsid] =$packagecreditaddondiscount;
											$packagecredituseaddons[$addonsid] =$addonslimit;
											$packagecreditusediscountactive['package_id'] = $services_packagesactive['id'];
										}
										if($val['qty']<$addonslimit){
											$packagediscountserviceprice += $val['rate'];
											$packagediscontamountactive += $val['rate'];
											$packagediscountaddons[$addonsid] =$val['rate'];
											$packagecredituseaddons[$addonsid] =$val['qty'];
											$packagecreditusediscountactive['package_id'] = $services_packagesactive['id'];
										}
										if($val['qty']>$addonslimit && $addonslimit=='0'){
											$packagediscountserviceprice += $val['rate'];
											$packagediscontamountactive += $val['rate'];
											$packagediscountaddons[$addonsid] =$val['rate'];
											$packagecredituseaddons[$addonsid] =$val['qty'];
											$packagecreditusediscountactive['package_id'] = $services_packagesactive['id'];
										}
									}
								}
							}
							if(sizeof($packagecredituseaddons)>0){
								$packagecreditusediscountactive['addons'] = $packagecredituseaddons;
							}
						}
						if($packagediscontamountactive>0){
							$packagecreditusediscountactive['discount'] =  $packagediscontamountactive;
							$packagediscountcredtiuseactive[] = $packagecreditusediscountactive;
						}
					}
				}
				/* New Purchase Packages Discount Credit Usage */
				if($packages_info_rows>0){
					while($services_packages = mysqli_fetch_array($packages_info)){
						$packagecreditusediscount = array();
						$packagediscontamount = 0;
						if($services_packages['services_limit']!=''){
							$serviceslimits = unserialize($services_packages['services_limit']);					
							foreach($serviceslimits as $servicesid => $serviceslimit){
								if($servicesid==$_SESSION['rzvy_cart_service_id'] && !in_array($servicesid,$packagediscountservice)){
									$packagediscountservice[] = $servicesid;
									$obj_frontend->service_id = $servicesid;
									$readone_service = $obj_frontend->readone_service();  
									$packagediscountserviceprice += $readone_service['rate'];
									$packagediscontamount += $readone_service['rate'];
									$packagecreditusediscount['service'] = $servicesid;
									$packagecreditusediscount['package_id'] = $services_packages['id'];
								}
							}						
						}
						if(sizeof($_SESSION['rzvy_cart_items'])>0 && $services_packages['addons_limit']!=''){
							$addonslimits = unserialize($services_packages['addons_limit']);
							$packagecredituseaddons = array();
							foreach($addonslimits as $addonsid => $addonslimit){
								foreach($_SESSION['rzvy_cart_items'] as $val){ 									
									if($val['id']==$addonsid && !isset($packagediscountaddons[$addonsid])){
										if($val['qty']>=$addonslimit && $addonslimit!='0'){
											$addonsingleqtyrate = $val['rate']/$val['qty'];
											$packagecreditaddondiscount = $addonsingleqtyrate*$addonslimit;
											$packagediscountserviceprice += $packagecreditaddondiscount;
											$packagediscontamount += $packagecreditaddondiscount;
											$packagediscountaddons[$addonsid] =$packagecreditaddondiscount;
											$packagecredituseaddons[$addonsid] =$addonslimit;
											$packagecreditusediscount['package_id'] = $services_packages['id'];
										}
										if($val['qty']<$addonslimit){
											$packagediscountserviceprice += $val['rate'];
											$packagediscontamount += $val['rate'];
											$packagediscountaddons[$addonsid] =$val['rate'];
											$packagecredituseaddons[$addonsid] =$val['qty'];
											$packagecreditusediscount['package_id'] = $services_packages['id'];
										}
										if($val['qty']>$addonslimit && $addonslimit=='0'){
											$packagediscountserviceprice += $val['rate'];
											$packagediscontamount += $val['rate'];
											$packagediscountaddons[$addonsid] =$val['rate'];
											$packagecredituseaddons[$addonsid] =$val['qty'];
											$packagecreditusediscount['package_id'] = $services_packages['id'];
										}
									}
								}
							}
							if(sizeof($packagecredituseaddons)>0){
								$packagecreditusediscount['addons'] = $packagecredituseaddons;
							}
						}
						if($packagediscontamount>0){
							$packagecreditusediscount['discount'] =  $packagediscontamount;
							$packagediscountcredtiuse[] = $packagecreditusediscount;
						}
					}
				}
				if(sizeof($packagediscountcredtiuse)>0){
					$_SESSION['rzvy_package_credituse_info'] = serialize($packagediscountcredtiuse);
				}
				if(sizeof($packagediscountcredtiuseactive)>0){
					$_SESSION['rzvy_activepackage_credituse_info'] = serialize($packagediscountcredtiuseactive);
				}
				if(sizeof($packagediscountservice)>0){
						$_SESSION['rzvy_package_service'] = implode(',',$packagediscountservice);
				}
				if(sizeof($packagediscountaddons)>0){
						$_SESSION['rzvy_package_addons'] = serialize($packagediscountaddons);
				}
				if($packagediscountserviceprice>0){
					$_SESSION['rzvy_package_discount'] = $packagediscountserviceprice;
					$_SESSION['rzvy_package_credituse'] = 'add';
				}
				

			}else{
				if(isset($_SESSION['rzvy_package_service'])){ unset($_SESSION['rzvy_package_service']); }
				if(isset($_SESSION['rzvy_package_addons'])){ unset($_SESSION['rzvy_package_addons']); }
				if(isset($_SESSION['rzvy_package_discount'])){ unset($_SESSION['rzvy_package_discount']); }
				if(isset($_SESSION['rzvy_package_credituse'])){ unset($_SESSION['rzvy_package_credituse']); }
				if(isset($_SESSION['rzvy_package_credituse_info'])){ unset($_SESSION['rzvy_package_credituse_info']); }
				if(isset($_SESSION['rzvy_activepackage_credituse_info'])){ unset($_SESSION['rzvy_activepackage_credituse_info']); }
			}
		}else{
			if(isset($_SESSION['rzvy_package_service'])){ unset($_SESSION['rzvy_package_service']); }
			if(isset($_SESSION['rzvy_package_addons'])){ unset($_SESSION['rzvy_package_addons']); }
			if(isset($_SESSION['rzvy_package_discount'])){ unset($_SESSION['rzvy_package_discount']); }
			if(isset($_SESSION['rzvy_package_credituse'])){ unset($_SESSION['rzvy_package_credituse']); }
			if(isset($_SESSION['rzvy_package_credituse_info'])){ unset($_SESSION['rzvy_package_credituse_info']); }
			if(isset($_SESSION['rzvy_activepackage_credituse_info'])){ unset($_SESSION['rzvy_activepackage_credituse_info']); }
		}
	}else{
		$_SESSION['rzvy_package_credituse'] = 'remove';
		if(isset($_SESSION['rzvy_package_service'])){ unset($_SESSION['rzvy_package_service']); }
		if(isset($_SESSION['rzvy_package_addons'])){ unset($_SESSION['rzvy_package_addons']); }
		if(isset($_SESSION['rzvy_package_discount'])){ unset($_SESSION['rzvy_package_discount']); }
		if(isset($_SESSION['rzvy_package_credituse'])){ unset($_SESSION['rzvy_package_credituse']); }
		if(isset($_SESSION['rzvy_package_credituse_info'])){ unset($_SESSION['rzvy_package_credituse_info']); }
		if(isset($_SESSION['rzvy_activepackage_credituse_info'])){ unset($_SESSION['rzvy_activepackage_credituse_info']); }
		if(isset($_POST['rzvy_package_reset']) && $_POST['rzvy_package_reset']=='Y'){
			if(isset($_SESSION['add_to_cart_package'])){ unset($_SESSION['add_to_cart_package']); }
		}
	}	
}