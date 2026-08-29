<?php include 'header.php'; 
if(!isset($rzvy_rolepermissions['rzvy_customers']) && $rzvy_loginutype=='staff'){ ?>
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
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['customers'])){ echo $rzvy_translangArr['customers']; }else{ echo $rzvy_defaultlang['customers']; } ?></li>
      </ol>
      <div class="mb-3">
		<div class="rzvy-tabbable-panel">
			<div class="rzvy-tabbable-line">
				<ul class="nav nav-tabs">
					<?php if(isset($rzvy_rolepermissions['rzvy_customers_register']) || $rzvy_loginutype=='admin'){ ?>
						  <li class="nav-item custom-nav-item <?php if(isset($rzvy_rolepermissions['rzvy_customers_register']) || $rzvy_loginutype=='admin'){ echo 'active'; } ?>">
							<a class="nav-link custom-nav-link rzvy_tab_view_nav_link" data-tabno="0" data-toggle="tab" href="#rzvy_registered_customers"><i class="fa fa-user-plus"></i> <?php if(isset($rzvy_translangArr['registered_customers'])){ echo $rzvy_translangArr['registered_customers']; }else{ echo $rzvy_defaultlang['registered_customers']; } ?></a>
						  </li>
					<?php } if(isset($rzvy_rolepermissions['rzvy_customers_guest']) || $rzvy_loginutype=='admin'){ ?>  
						  <li class="nav-item custom-nav-item <?php if(!isset($rzvy_rolepermissions['rzvy_customers_register']) && isset($rzvy_rolepermissions['rzvy_customers_guest'])){ echo 'active'; } ?>">
							<a class="nav-link custom-nav-link rzvy_tab_view_nav_link" data-tabno="1" data-toggle="tab" href="#rzvy_guest_customers"><i class="fa fa-user"></i> <?php if(isset($rzvy_translangArr['guest_customers'])){ echo $rzvy_translangArr['guest_customers']; }else{ echo $rzvy_defaultlang['guest_customers']; } ?></a>
						  </li>
					<?php } ?>	  
				</ul>
				<div class="tab-content">
					<div class="tab-pane container <?php if(isset($rzvy_rolepermissions['rzvy_customers_register']) || $rzvy_loginutype=='admin'){ echo 'active'; }else{ echo 'fade'; } ?>" id="rzvy_registered_customers">
					  <div class="row">
						<div class="col-md-12">
						  <div class="table-responsive">
							<table class="display responsive nowrap" width="100%" cellspacing="0" id="rzvy_registered_customers_detail">
							  <thead>
								<tr>
								  <th><?php if(isset($rzvy_translangArr['customer_name'])){ echo $rzvy_translangArr['customer_name']; }else{ echo $rzvy_defaultlang['customer_name']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['email'])){ echo $rzvy_translangArr['email']; }else{ echo $rzvy_defaultlang['email']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['phone'])){ echo $rzvy_translangArr['phone']; }else{ echo $rzvy_defaultlang['phone']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['address'])){ echo $rzvy_translangArr['address']; }else{ echo $rzvy_defaultlang['address']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['referral_code'])){ echo $rzvy_translangArr['referral_code']; }else{ echo $rzvy_defaultlang['referral_code']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['booked_appointments'])){ echo $rzvy_translangArr['booked_appointments']; }else{ echo $rzvy_defaultlang['booked_appointments']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
								</tr>
							  </thead>
							  <tbody>
							  </tbody>
							</table>
						  </div>
						</div>
					  </div>
					</div>
					<div class="tab-pane container <?php if(!isset($rzvy_rolepermissions['rzvy_customers_register']) && isset($rzvy_rolepermissions['rzvy_customers_guest'])){ echo 'active'; }else{ echo 'fade'; } ?>" id="rzvy_guest_customers">
					  <br/>
					  <div class="row">
						<div class="col-md-12">
						  <div class="table-responsive">
							<table class="display responsive nowrap" width="100%" cellspacing="0" id="rzvy_guest_customers_detail">
							  <thead>
								<tr>
								  <th><?php if(isset($rzvy_translangArr['customer_name'])){ echo $rzvy_translangArr['customer_name']; }else{ echo $rzvy_defaultlang['customer_name']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['email'])){ echo $rzvy_translangArr['email']; }else{ echo $rzvy_defaultlang['email']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['phone'])){ echo $rzvy_translangArr['phone']; }else{ echo $rzvy_defaultlang['phone']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['address'])){ echo $rzvy_translangArr['address']; }else{ echo $rzvy_defaultlang['address']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['booked_appointments'])){ echo $rzvy_translangArr['booked_appointments']; }else{ echo $rzvy_defaultlang['booked_appointments']; } ?></th>
								</tr>
							  </thead>
							  <tbody>
							  </tbody>
							</table>
						  </div>
						</div>
					  </div>
					</div>
			  </div>
			</div>
		</div>
	 </div>
	 <!-- Appointments Modal-->
	<div class="modal fade" id="rzvy_customer_appointment_modal" tabindex="-1" role="dialog" aria-labelledby="rzvy_customer_appointment_modal_label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy_customer_appointment_modal_label"><?php if(isset($rzvy_translangArr['booked_appointments'])){ echo $rzvy_translangArr['booked_appointments']; }else{ echo $rzvy_defaultlang['booked_appointments']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy_customer_appointment_modal_body">
			<div class="table-responsive">
				<table class="display responsive nowrap" width="100%" cellspacing="0" id="rzvy_customer_appointments_listing">
				  <thead>
					<tr>
					  <th><?php if(isset($rzvy_translangArr['order_id'])){ echo $rzvy_translangArr['order_id']; }else{ echo $rzvy_defaultlang['order_id']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['category'])){ echo $rzvy_translangArr['category']; }else{ echo $rzvy_defaultlang['category']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['service'])){ echo $rzvy_translangArr['service']; }else{ echo $rzvy_defaultlang['service']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['addons'])){ echo $rzvy_translangArr['addons']; }else{ echo $rzvy_defaultlang['addons']; } ?></th>
					  <?php if($rzvy_book_with_datetime == "Y"){ ?><th><?php if(isset($rzvy_translangArr['booking_datetime'])){ echo $rzvy_translangArr['booking_datetime']; }else{ echo $rzvy_defaultlang['booking_datetime']; } ?></th><?php } ?>
					  <th><?php if(isset($rzvy_translangArr['booking_status'])){ echo $rzvy_translangArr['booking_status']; }else{ echo $rzvy_defaultlang['booking_status']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['payment_method'])){ echo $rzvy_translangArr['payment_method']; }else{ echo $rzvy_defaultlang['payment_method']; } ?></th>
					</tr>
				  </thead>
				  <tbody>
				  </tbody>
				</table>
			  </div>
		  </div>
		  <div class="modal-footer"> </div>
		</div>
	  </div>
	</div>
	 <!-- Customer Modal-->
	<div class="modal fade" id="rzvy_customer_edit_modal" tabindex="-1" role="dialog" aria-labelledby="rzvy_customer_edit_modal_label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy_customer_edit_modal_label"><?php if(isset($rzvy_translangArr['update_customer_detail'])){ echo $rzvy_translangArr['update_customer_detail']; }else{ echo $rzvy_defaultlang['update_customer_detail']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy_customer_edit_modal_body">
			  <?php if(isset($rzvy_translangArr['please_wait_summary_loading'])){ echo $rzvy_translangArr['please_wait_summary_loading']; }else{ echo $rzvy_defaultlang['please_wait_summary_loading']; } ?>
		  </div>
		  <div class="modal-footer">
		        <button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_update_customer_btn" data-id="" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['update_customer_detail'])){ echo $rzvy_translangArr['update_customer_detail']; }else{ echo $rzvy_defaultlang['update_customer_detail']; } ?></a>    
		  </div>
		</div>
	  </div>
	</div>
	<!-- Guest Customer Modal-->
	<div class="modal fade" id="rzvy_gcustomer_edit_modal" tabindex="-1" role="dialog" aria-labelledby="rzvy_gcustomer_edit_modal_label" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="rzvy_gcustomer_edit_modal_label"><?php if(isset($rzvy_translangArr['update_customer_detail'])){ echo $rzvy_translangArr['update_customer_detail']; }else{ echo $rzvy_defaultlang['update_customer_detail']; } ?></h5>
			<button class="close" type="button" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
			</button>
		  </div>
		  <div class="modal-body rzvy_gcustomer_edit_modal_body">
			  <?php if(isset($rzvy_translangArr['please_wait_summary_loading'])){ echo $rzvy_translangArr['please_wait_summary_loading']; }else{ echo $rzvy_defaultlang['please_wait_summary_loading']; } ?>
		  </div>
		  <div class="modal-footer">
		        <button class="btn btn-secondary" type="button" data-dismiss="modal"><?php if(isset($rzvy_translangArr['cancel'])){ echo $rzvy_translangArr['cancel']; }else{ echo $rzvy_defaultlang['cancel']; } ?></button>
			<a id="rzvy_update_gcustomer_btn" data-id="" class="btn btn-primary" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['update_customer_detail'])){ echo $rzvy_translangArr['update_customer_detail']; }else{ echo $rzvy_defaultlang['update_customer_detail']; } ?></a>    
		  </div>
		</div>
	  </div>
	</div>
<?php include 'footer.php'; ?>