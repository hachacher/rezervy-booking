<?php 
include 'header.php';
if(!isset($rzvy_rolepermissions['rzvy_referral']) && $rzvy_loginutype=='staff'){ ?>
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
<?php die(); } 
include 'currency.php'; 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['referral_discount_settings'])){ echo $rzvy_translangArr['referral_discount_settings']; }else{ echo $rzvy_defaultlang['referral_discount_settings']; } ?></li>
      </ol>
	  <div class="mb-3">
		<div class="rzvy-tabbable-panel">
			<div class="rzvy-tabbable-line">
				<ul class="nav nav-tabs">
				  <li class="nav-item active custom-nav-item">
					<a class="nav-link custom-nav-link rzvy_tab_view_nav_link" data-tabno="0" data-toggle="tab" href="#rzvy_referral_settings"><i class="fa fa-gift"></i> <?php if(isset($rzvy_translangArr['referral_settings'])){ echo $rzvy_translangArr['referral_settings']; }else{ echo $rzvy_defaultlang['referral_settings']; } ?></a>
				  </li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane container active" id="rzvy_referral_settings">
					  <div class="row">
						<div class="col-md-12">
						  <form name="rzvy_referral_settings_form" id="rzvy_referral_settings_form" method="post">
							<div class="row my-3">
								<label class="col-md-3"><?php if(isset($rzvy_translangArr['referral_discount_status'])){ echo $rzvy_translangArr['referral_discount_status']; }else{ echo $rzvy_defaultlang['referral_discount_status']; } ?></label>
								<label class="rzvy-toggle-switch">
									<input type="checkbox" name="rzvy_referral_discount_status" id="rzvy_referral_discount_status" class="rzvy-toggle-switch-input" <?php if($obj_settings->get_option("rzvy_referral_discount_status")=="Y"){ echo "checked"; } ?> />
									<span class="rzvy-toggle-switch-slider"></span>
								</label>
							</div>
							  <div class="form-group row">
								<div class="col-md-6">
									<label class="control-label"><?php if(isset($rzvy_translangArr['referral_discount_type'])){ echo $rzvy_translangArr['referral_discount_type']; }else{ echo $rzvy_defaultlang['referral_discount_type']; } ?><?php if(isset($rzvy_translangArr['for_referrer'])){ echo $rzvy_translangArr['for_referrer']; }else{ echo $rzvy_defaultlang['for_referrer']; } ?></label>
									<?php $rzvy_referral_discount_type = $obj_settings->get_option("rzvy_referral_discount_type"); ?>
									<select name="rzvy_referral_discount_type" id="rzvy_referral_discount_type" class="form-control selectpicker">
									  <option value="percentage" <?php if($rzvy_referral_discount_type == "percentage"){ echo "selected"; } ?>><?php if(isset($rzvy_translangArr['percentage'])){ echo $rzvy_translangArr['percentage']; }else{ echo $rzvy_defaultlang['percentage']; } ?></option>
									  <option value="flat" <?php if($rzvy_referral_discount_type == "flat"){ echo "selected"; } ?>><?php if(isset($rzvy_translangArr['flat'])){ echo $rzvy_translangArr['flat']; }else{ echo $rzvy_defaultlang['flat']; } ?></option>
									</select>
								</div>
								<div class="col-md-6">
									<label class="control-label"><?php if(isset($rzvy_translangArr['referral_discount_value'])){ echo $rzvy_translangArr['referral_discount_value']; }else{ echo $rzvy_defaultlang['referral_discount_value']; } ?><?php if(isset($rzvy_translangArr['for_referrer'])){ echo $rzvy_translangArr['for_referrer']; }else{ echo $rzvy_defaultlang['for_referrer']; } ?></label>
									<input type="text" name="rzvy_referral_discount_value" id="rzvy_referral_discount_value" placeholder="<?php if(isset($rzvy_translangArr['e_g_5'])){ echo $rzvy_translangArr['e_g_5']; }else{ echo $rzvy_defaultlang['e_g_5']; } ?>" class="form-control" value="<?php echo $obj_settings->get_option("rzvy_referral_discount_value"); ?>" />
								</div>
							  </div>
							  <div class="form-group row">
								<div class="col-md-6">
									<label class="control-label"><?php if(isset($rzvy_translangArr['referred_customer_discount_type'])){ echo $rzvy_translangArr['referred_customer_discount_type']; }else{ echo $rzvy_defaultlang['referred_customer_discount_type']; } ?></label>
									<?php $rzvy_referred_discount_type = $obj_settings->get_option("rzvy_referred_discount_type"); ?>
									<select name="rzvy_referred_discount_type" id="rzvy_referred_discount_type" class="form-control selectpicker">
									  <option value="percentage" <?php if($rzvy_referred_discount_type == "percentage"){ echo "selected"; } ?>><?php if(isset($rzvy_translangArr['percentage'])){ echo $rzvy_translangArr['percentage']; }else{ echo $rzvy_defaultlang['percentage']; } ?></option>
									  <option value="flat" <?php if($rzvy_referred_discount_type == "flat"){ echo "selected"; } ?>><?php if(isset($rzvy_translangArr['flat'])){ echo $rzvy_translangArr['flat']; }else{ echo $rzvy_defaultlang['flat']; } ?></option>
									</select>
								</div>
								<div class="col-md-6">
									<label class="control-label"><?php if(isset($rzvy_translangArr['referral_discount_value_for_referred_customer'])){ echo $rzvy_translangArr['referral_discount_value_for_referred_customer']; }else{ echo $rzvy_defaultlang['referral_discount_value_for_referred_customer']; } ?></label>
									<input type="text" name="rzvy_referred_discount_value" id="rzvy_referred_discount_value" placeholder="<?php if(isset($rzvy_translangArr['e_g_5'])){ echo $rzvy_translangArr['e_g_5']; }else{ echo $rzvy_defaultlang['e_g_5']; } ?>" class="form-control" value="<?php echo $obj_settings->get_option("rzvy_referred_discount_value"); ?>" />
								</div>
							  </div>
							  <div class="form-group row">
								<div class="col-md-6">
									<label class="control-label"><?php if(isset($rzvy_translangArr['referral_sharing_channels'])){ echo $rzvy_translangArr['referral_sharing_channels']; }else{ echo $rzvy_defaultlang['referral_sharing_channels']; } ?></label>
									<br/><small><?php if(isset($rzvy_translangArr['please_select_in_order_you_want_to_display'])){ echo $rzvy_translangArr['please_select_in_order_you_want_to_display']; }else{ echo $rzvy_defaultlang['please_select_in_order_you_want_to_display']; } ?></small>
									<?php $rzvy_referral_sharing_channels = $obj_settings->get_option("rzvy_referral_channels"); 
									$rzvy_refferal_channel = array();
									if($rzvy_referral_sharing_channels!=''){
										$rzvy_refferal_channel = explode(',',$rzvy_referral_sharing_channels);
									}
									?>
									<input type="hidden" value="<?php echo $rzvy_referral_sharing_channels; ?>" id="rzvy_selected_referr_channel" />
									<select name="rzvy_referral_sharing_channels" id="rzvy_referral_sharing_channels" class="form-control selectpicker" multiple >
									  <option value="facebook" <?php if(in_array("facebook",$rzvy_refferal_channel)){ echo "selected"; } ?>><?php if(isset($rzvy_translangArr['facebook'])){ echo $rzvy_translangArr['facebook']; }else{ echo $rzvy_defaultlang['facebook']; } ?></option>
									  <option value="twitter" <?php if(in_array("twitter",$rzvy_refferal_channel)){ echo "selected"; } ?>><?php if(isset($rzvy_translangArr['twitter'])){ echo $rzvy_translangArr['twitter']; }else{ echo $rzvy_defaultlang['twitter']; } ?></option>									  								  
									  <option value="googleplus" <?php if(in_array("googleplus",$rzvy_refferal_channel)){ echo "selected"; } ?>><?php if(isset($rzvy_translangArr['googleplus'])){ echo $rzvy_translangArr['googleplus']; }else{ echo $rzvy_defaultlang['googleplus']; } ?></option>
									  <option value="whatsapp" <?php if(in_array("whatsapp",$rzvy_refferal_channel)){ echo "selected"; } ?>><?php if(isset($rzvy_translangArr['whatsapp'])){ echo $rzvy_translangArr['whatsapp']; }else{ echo $rzvy_defaultlang['whatsapp']; } ?></option>	
									</select>
									
								</div>
							  </div>
							  <?php if(isset($rzvy_rolepermissions['rzvy_referral_manage']) || $rzvy_loginutype=='admin'){ ?>
								<a id="update_referral_settings_btn" class="btn btn-primary btn-block" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['update_settings'])){ echo $rzvy_translangArr['update_settings']; }else{ echo $rzvy_defaultlang['update_settings']; } ?></a>
							  <?php } ?>
						 </form>
						</div>
					  </div>
					</div>
			  </div>
			</div>
		</div>
	 </div>
<?php include 'footer.php'; ?>