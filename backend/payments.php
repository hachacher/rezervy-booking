<?php 
include 'header.php';
if(!isset($rzvy_rolepermissions['rzvy_payments']) && $rzvy_loginutype=='staff'){ ?>
	<div class="container mt-12">
		  <div class="row mt-5"><div class="col-md-12">&nbsp;</div></div>
          <div class="row mt-5">
               <div class="col-md-2 text-center mt-5">
                  <i class="fa fa-exclamation-triangle fa-5x"></i>
               </div>
               <div class="col-md-10 mt-5">
                   <p><?php if(isset($rzvy_translangArr['permission_error_message'])){ echo $rzvy_translangArr['permission_error_message']; }else{ echo $rzvy_defaultlang['permission_error_message']; } ?></p>                    
               </div>
          </div>
     </div>		
<?php die(); } ?>	
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['payments'])){ echo $rzvy_translangArr['payments']; }else{ echo $rzvy_defaultlang['payments']; } ?></li>
      </ol>
      <div class="mb-3">
		<div class="rzvy-tabbable-panel">
			<div class="rzvy-tabbable-line">
				<ul class="nav nav-tabs">
				  <?php if(isset($rzvy_rolepermissions['rzvy_payments_register']) || $rzvy_loginutype=='admin'){ ?>
					  <li class="nav-item custom-nav-item <?php if(isset($rzvy_rolepermissions['rzvy_payments_register']) || $rzvy_loginutype=='admin'){ echo 'active'; } ?>">
						<a class="nav-link custom-nav-link rzvy_tab_view_nav_link" data-tabno="0" data-toggle="tab" href="#rzvy_registered_customers_payment"><i class="fa fa-credit-card"></i> <?php if(isset($rzvy_translangArr['registered_customers_payment'])){ echo $rzvy_translangArr['registered_customers_payment']; }else{ echo $rzvy_defaultlang['registered_customers_payment']; } ?></a>
					  </li>
				  <?php } if(isset($rzvy_rolepermissions['rzvy_payments_guest']) || $rzvy_loginutype=='admin'){ ?>
					  <li class="nav-item custom-nav-item <?php if(!isset($rzvy_rolepermissions['rzvy_payments_register']) && isset($rzvy_rolepermissions['rzvy_payments_guest'])){ echo 'active'; } ?>">
						<a class="nav-link custom-nav-link rzvy_tab_view_nav_link" data-tabno="1" data-toggle="tab" href="#rzvy_guest_customers_payment"><i class="fa fa-money"></i> <?php if(isset($rzvy_translangArr['guest_customers_payment'])){ echo $rzvy_translangArr['guest_customers_payment']; }else{ echo $rzvy_defaultlang['guest_customers_payment']; } ?></a>
					  </li>
				  <?php } if(isset($rzvy_rolepermissions['rzvy_payments_pos']) || $rzvy_loginutype=='admin'){ ?>
						<?php $Rzvy_Hooks->do_action('pos_payment_tab', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang,$rzvy_rolepermissions)); ?>
				  <?php } ?>	
				</ul>
				<div class="tab-content">
					<div class="tab-pane <?php if(isset($rzvy_rolepermissions['rzvy_payments_register']) || $rzvy_loginutype=='admin'){ echo 'active'; }else{ echo 'fade'; } ?>" id="rzvy_registered_customers_payment">
					  <div class="row">
						<div class="col-md-12">
						  <div class="table-responsive">
							<table class="display responsive nowrap" width="100%" cellspacing="0" id="rzvy_rc_payment_table">
							  <thead>
								<tr>
								  <th><?php if(isset($rzvy_translangArr['order_id'])){ echo $rzvy_translangArr['order_id']; }else{ echo $rzvy_defaultlang['order_id']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['customer_name'])){ echo $rzvy_translangArr['customer_name']; }else{ echo $rzvy_defaultlang['customer_name']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['payment_method'])){ echo $rzvy_translangArr['payment_method']; }else{ echo $rzvy_defaultlang['payment_method']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['payment_date_a'])){ echo $rzvy_translangArr['payment_date_a']; }else{ echo $rzvy_defaultlang['payment_date_a']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['transaction_id'])){ echo $rzvy_translangArr['transaction_id']; }else{ echo $rzvy_defaultlang['transaction_id']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['sub_total'])){ echo $rzvy_translangArr['sub_total']; }else{ echo $rzvy_defaultlang['sub_total']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['discount'])){ echo $rzvy_translangArr['discount']; }else{ echo $rzvy_defaultlang['discount']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['referral_discount_a'])){ echo $rzvy_translangArr['referral_discount_a']; }else{ echo $rzvy_defaultlang['referral_discount_a']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['packages_discount'])){ echo $rzvy_translangArr['packages_discount']; }else{ echo $rzvy_defaultlang['packages_discount']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['tax'])){ echo $rzvy_translangArr['tax']; }else{ echo $rzvy_defaultlang['tax']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['partial_deposite'])){ echo $rzvy_translangArr['partial_deposite']; }else{ echo $rzvy_defaultlang['partial_deposite']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['net_total'])){ echo $rzvy_translangArr['net_total']; }else{ echo $rzvy_defaultlang['net_total']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['frequently_discount'])){ echo $rzvy_translangArr['frequently_discount']; }else{ echo $rzvy_defaultlang['frequently_discount']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['frequently_discount_amount'])){ echo $rzvy_translangArr['frequently_discount_amount']; }else{ echo $rzvy_defaultlang['frequently_discount_amount']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['loyalty_points_amount'])){ echo $rzvy_translangArr['loyalty_points_amount']; }else{ echo $rzvy_defaultlang['loyalty_points_amount']; } ?></th>
								</tr>
							  </thead>
							  <tbody>
							  </tbody>
							</table>
						  </div>
						</div>
					  </div>
					</div>
					<div class="tab-pane <?php if(!isset($rzvy_rolepermissions['rzvy_payments_register']) && isset($rzvy_rolepermissions['rzvy_payments_guest'])){ echo 'active'; }else{ echo 'fade'; } ?>" id="rzvy_guest_customers_payment">
					  <br/>
					  <div class="row">
						<div class="col-md-12">
						  <div class="table-responsive">
							<table class="display responsive nowrap" width="100%" cellspacing="0" id="rzvy_gc_payment_table">
							  <thead>
								<tr>
								  <th><?php if(isset($rzvy_translangArr['order_id'])){ echo $rzvy_translangArr['order_id']; }else{ echo $rzvy_defaultlang['order_id']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['customer_name'])){ echo $rzvy_translangArr['customer_name']; }else{ echo $rzvy_defaultlang['customer_name']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['payment_method'])){ echo $rzvy_translangArr['payment_method']; }else{ echo $rzvy_defaultlang['payment_method']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['payment_date_a'])){ echo $rzvy_translangArr['payment_date_a']; }else{ echo $rzvy_defaultlang['payment_date_a']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['transaction_id'])){ echo $rzvy_translangArr['transaction_id']; }else{ echo $rzvy_defaultlang['transaction_id']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['sub_total'])){ echo $rzvy_translangArr['sub_total']; }else{ echo $rzvy_defaultlang['sub_total']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['discount'])){ echo $rzvy_translangArr['discount']; }else{ echo $rzvy_defaultlang['discount']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['tax'])){ echo $rzvy_translangArr['tax']; }else{ echo $rzvy_defaultlang['tax']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['partial_deposite'])){ echo $rzvy_translangArr['partial_deposite']; }else{ echo $rzvy_defaultlang['partial_deposite']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['net_total'])){ echo $rzvy_translangArr['net_total']; }else{ echo $rzvy_defaultlang['net_total']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['frequently_discount'])){ echo $rzvy_translangArr['frequently_discount']; }else{ echo $rzvy_defaultlang['frequently_discount']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['frequently_discount_amount'])){ echo $rzvy_translangArr['frequently_discount_amount']; }else{ echo $rzvy_defaultlang['frequently_discount_amount']; } ?></th>
								</tr>
							  </thead>
							  <tbody>
							  </tbody>
							</table>
						  </div>
						</div>
					  </div>
					</div>
					<?php if(isset($rzvy_rolepermissions['rzvy_payments_pos']) || $rzvy_loginutype=='admin'){
					$Rzvy_Hooks->do_action('pos_payment_tab_content', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang,$rzvy_rolepermissions)); } ?>
			  </div>
			</div>
		</div>
	 </div>
<?php include 'footer.php'; ?>