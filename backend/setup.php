<?php 
include "header.php";
if(!isset($rzvy_rolepermissions['rzvy_setup']) && $rzvy_loginutype=='staff'){ ?>
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
<div class="row mt-2">
	<div class="col-md-4">
		<!-- Settings Card -->
		<div class="card my-4" style="box-shadow: 0 0 10px #d2d2d2;">
			<div class="card-body p-1">
				<table class="table table-borderless" cellspacing="0">
					<tbody>
						<tr>
							<td class="border-top-0">
								<strong class="h4 font-weight-bold"><?php if(isset($rzvy_translangArr['settings'])){ echo $rzvy_translangArr['settings']; }else{ echo $rzvy_defaultlang['settings']; } ?></strong>
							</td>
						</tr>
						<?php if(isset($rzvy_rolepermissions['rzvy_settings_company']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/settings.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['company_settings'])){ echo $rzvy_translangArr['company_settings']; }else{ echo $rzvy_defaultlang['company_settings']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_company_settings_desc'])){ echo $rzvy_translangArr['setup_company_settings_desc']; }else{ echo $rzvy_defaultlang['setup_company_settings_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_settings_payment']) || $rzvy_loginutype=='admin'){ ?>	
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/settings.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['payment_settings'])){ echo $rzvy_translangArr['payment_settings']; }else{ echo $rzvy_defaultlang['payment_settings']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_payment_settings_desc'])){ echo $rzvy_translangArr['setup_payment_settings_desc']; }else{ echo $rzvy_defaultlang['setup_payment_settings_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_settings_email']) || $rzvy_loginutype=='admin'){ ?>  
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/settings.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['email_settings'])){ echo $rzvy_translangArr['email_settings']; }else{ echo $rzvy_defaultlang['email_settings']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_email_settings_desc'])){ echo $rzvy_translangArr['setup_email_settings_desc']; }else{ echo $rzvy_defaultlang['setup_email_settings_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_settings_sms']) || $rzvy_loginutype=='admin'){ ?>  
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/settings.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['sms_settings'])){ echo $rzvy_translangArr['sms_settings']; }else{ echo $rzvy_defaultlang['sms_settings']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_sms_settings_desc'])){ echo $rzvy_translangArr['setup_sms_settings_desc']; }else{ echo $rzvy_defaultlang['setup_sms_settings_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_settings_seo']) || $rzvy_loginutype=='admin'){ ?>  
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/settings.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['seo_settings'])){ echo $rzvy_translangArr['seo_settings']; }else{ echo $rzvy_defaultlang['seo_settings']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_seo_settings_desc'])){ echo $rzvy_translangArr['setup_seo_settings_desc']; }else{ echo $rzvy_defaultlang['setup_seo_settings_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_settings_wc']) || $rzvy_loginutype=='admin'){ ?>  
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/settings.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['welcome_message'])){ echo $rzvy_translangArr['welcome_message']; }else{ echo $rzvy_defaultlang['welcome_message']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_welcome_settings_desc'])){ echo $rzvy_translangArr['setup_welcome_settings_desc']; }else{ echo $rzvy_defaultlang['setup_welcome_settings_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_settings_booking']) || $rzvy_loginutype=='admin'){ ?>  
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/settings.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['booking_form'])){ echo $rzvy_translangArr['booking_form']; }else{ echo $rzvy_defaultlang['booking_form']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_bookingform_settings_desc'])){ echo $rzvy_translangArr['setup_bookingform_settings_desc']; }else{ echo $rzvy_defaultlang['setup_bookingform_settings_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } ?> 
					</tbody>
				</table>
			</div>
		</div>
		<!-- Discounts Card -->
		<div class="card my-4" style="box-shadow: 0 0 10px #d2d2d2;">
			<div class="card-body p-1">
				<table class="table table-borderless" cellspacing="0">
					<tbody>
						<tr>
							<td class="border-top-0">
								<strong class="h4 font-weight-bold"><?php if(isset($rzvy_translangArr['discounts'])){ echo $rzvy_translangArr['discounts']; }else{ echo $rzvy_defaultlang['discounts']; } ?></strong>
							</td>
						</tr>
						<?php if(isset($rzvy_rolepermissions['rzvy_coupons']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/coupons.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['coupons'])){ echo $rzvy_translangArr['coupons']; }else{ echo $rzvy_defaultlang['coupons']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_coupons_desc'])){ echo $rzvy_translangArr['setup_coupons_desc']; }else{ echo $rzvy_defaultlang['setup_coupons_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_fd']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/frequently-discount.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['frequently_discount_noad'])){ echo $rzvy_translangArr['frequently_discount_noad']; }else{ echo $rzvy_defaultlang['frequently_discount_noad']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_frequently_discount_desc'])){ echo $rzvy_translangArr['setup_frequently_discount_desc']; }else{ echo $rzvy_defaultlang['setup_frequently_discount_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_referral']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/referral-setting.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['referral_settings'])){ echo $rzvy_translangArr['referral_settings']; }else{ echo $rzvy_defaultlang['referral_settings']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_referral_settings_desc'])){ echo $rzvy_translangArr['setup_referral_settings_desc']; }else{ echo $rzvy_defaultlang['setup_referral_settings_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } ?>	
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<!-- Appearance Card -->
		<div class="card my-4" style="box-shadow: 0 0 10px #d2d2d2;">
			<div class="card-body p-1">
				<table class="table table-borderless" cellspacing="0">
					<tbody>
						<tr>
							<td class="border-top-0">
								<strong class="h4 font-weight-bold"><?php if(isset($rzvy_translangArr['appearance'])){ echo $rzvy_translangArr['appearance']; }else{ echo $rzvy_defaultlang['appearance']; } ?></strong>
							</td>
						</tr>
						<?php if(isset($rzvy_rolepermissions['rzvy_colors']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/color-scheme.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['color_scheme'])){ echo $rzvy_translangArr['color_scheme']; }else{ echo $rzvy_defaultlang['color_scheme']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_color_scheme_desc'])){ echo $rzvy_translangArr['setup_color_scheme_desc']; }else{ echo $rzvy_defaultlang['setup_color_scheme_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_bform']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/form-fields.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['manage_booking_form'])){ echo $rzvy_translangArr['manage_booking_form']; }else{ echo $rzvy_defaultlang['manage_booking_form']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_bform_desc'])){ echo $rzvy_translangArr['setup_bform_desc']; }else{ echo $rzvy_defaultlang['setup_bform_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_locations']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/location-selector.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['location_selector'])){ echo $rzvy_translangArr['location_selector']; }else{ echo $rzvy_defaultlang['location_selector']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_location_selector_desc'])){ echo $rzvy_translangArr['setup_location_selector_desc']; }else{ echo $rzvy_defaultlang['setup_location_selector_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_feedback']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/feedback.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['feedback'])){ echo $rzvy_translangArr['feedback']; }else{ echo $rzvy_defaultlang['feedback']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_feedback_desc'])){ echo $rzvy_translangArr['setup_feedback_desc']; }else{ echo $rzvy_defaultlang['setup_feedback_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_embed']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/embed.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['embed_frontend'])){ echo $rzvy_translangArr['embed_frontend']; }else{ echo $rzvy_defaultlang['embed_frontend']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_embed_desc'])){ echo $rzvy_translangArr['setup_embed_desc']; }else{ echo $rzvy_defaultlang['setup_embed_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_language']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/languages.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['languages'])){ echo $rzvy_translangArr['languages']; }else{ echo $rzvy_defaultlang['languages']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_languages_desc'])){ echo $rzvy_translangArr['setup_languages_desc']; }else{ echo $rzvy_defaultlang['setup_languages_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
		<!-- Notifications Card -->
		<div class="card my-4" style="box-shadow: 0 0 10px #d2d2d2;">
			<div class="card-body p-1">
				<table class="table table-borderless" cellspacing="0">
					<tbody>
						<tr>
							<td class="border-top-0">
								<strong class="h4 font-weight-bold"><?php if(isset($rzvy_translangArr['notifications'])){ echo $rzvy_translangArr['notifications']; }else{ echo $rzvy_defaultlang['notifications']; } ?></strong>
							</td>
						</tr>
						<?php if(isset($rzvy_rolepermissions['rzvy_estemplates']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/email-sms-templates.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['email_and_sms_templates'])){ echo $rzvy_translangArr['email_and_sms_templates']; }else{ echo $rzvy_defaultlang['email_and_sms_templates']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_email_and_sms_templates_desc'])){ echo $rzvy_translangArr['setup_email_and_sms_templates_desc']; }else{ echo $rzvy_defaultlang['setup_email_and_sms_templates_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_support']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/support-tickets.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['support_tickets'])){ echo $rzvy_translangArr['support_tickets']; }else{ echo $rzvy_defaultlang['support_tickets']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_support_tickets_desc'])){ echo $rzvy_translangArr['setup_support_tickets_desc']; }else{ echo $rzvy_defaultlang['setup_support_tickets_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_refundr']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/refund.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['refund_request'])){ echo $rzvy_translangArr['refund_request']; }else{ echo $rzvy_defaultlang['refund_request']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_refund_request_desc'])){ echo $rzvy_translangArr['setup_refund_request_desc']; }else{ echo $rzvy_defaultlang['setup_refund_request_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_reminder']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/reminder.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['reminder'])){ echo $rzvy_translangArr['reminder']; }else{ echo $rzvy_defaultlang['reminder']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_reminder_desc'])){ echo $rzvy_translangArr['setup_reminder_desc']; }else{ echo $rzvy_defaultlang['setup_reminder_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<!-- Additional Card -->
		<div class="card my-4" style="box-shadow: 0 0 10px #d2d2d2;">
			<div class="card-body p-1">
				<table class="table table-borderless" cellspacing="0">
					<tbody>
						<tr>
							<td class="border-top-0">
								<strong class="h4 font-weight-bold"><?php if(isset($rzvy_translangArr['additional'])){ echo $rzvy_translangArr['additional']; }else{ echo $rzvy_defaultlang['additional']; } ?></strong>
							</td>
						</tr>
						<?php if(isset($rzvy_rolepermissions['rzvy_blockoff']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/manage-blockoff.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['manage_block_off'])){ echo $rzvy_translangArr['manage_block_off']; }else{ echo $rzvy_defaultlang['manage_block_off']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_manage_block_off_desc'])){ echo $rzvy_translangArr['setup_manage_block_off_desc']; }else{ echo $rzvy_defaultlang['setup_manage_block_off_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/<?php if(isset($rzvy_rolepermissions['rzvy_gc']) || $rzvy_loginutype=='admin'){ echo "gcal.php"; }else{ echo "s-gcal.php"; } ?>" class="text-primary"><strong><?php if(isset($rzvy_translangArr['google_calendar'])){ echo $rzvy_translangArr['google_calendar']; }else{ echo $rzvy_defaultlang['google_calendar']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_google_calendar_desc'])){ echo $rzvy_translangArr['setup_google_calendar_desc']; }else{ echo $rzvy_defaultlang['setup_google_calendar_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php if(isset($rzvy_rolepermissions['rzvy_rolepermission']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/role-permissions.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['role_permissions'])){ echo $rzvy_translangArr['role_permissions']; }else{ echo $rzvy_defaultlang['role_permissions']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_role_permissions_desc'])){ echo $rzvy_translangArr['setup_role_permissions_desc']; }else{ echo $rzvy_defaultlang['setup_role_permissions_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_exportp']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="<?php echo SITE_URL; ?>backend/export.php" class="text-primary"><strong><?php if(isset($rzvy_translangArr['export'])){ echo $rzvy_translangArr['export']; }else{ echo $rzvy_defaultlang['export']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_export_desc'])){ echo $rzvy_translangArr['setup_export_desc']; }else{ echo $rzvy_defaultlang['setup_export_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } if(isset($rzvy_rolepermissions['rzvy_docs']) || $rzvy_loginutype=='admin'){ ?>
						<tr>
							<td class="border-bottom-1 border-top-0">
								<div class="row">
									<div class="col-md-12">
										<a href="http://rezervy.perfecky.com/documentation/" class="text-primary"><strong><?php if(isset($rzvy_translangArr['documentation'])){ echo $rzvy_translangArr['documentation']; }else{ echo $rzvy_defaultlang['documentation']; } ?></strong></a>
										<br />
										<span class="text-muted"><?php if(isset($rzvy_translangArr['setup_documentation_desc'])){ echo $rzvy_translangArr['setup_documentation_desc']; }else{ echo $rzvy_defaultlang['setup_documentation_desc']; } ?></span>
									</div>
								</div>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php include "footer.php"; ?>