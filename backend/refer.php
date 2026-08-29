<?php 
include 'c_header.php';
include(dirname(dirname(__FILE__))."/classes/class_customers.php");
include(dirname(dirname(__FILE__))."/classes/class_settings.php");
$obj_customers = new rzvy_customers();
$obj_customers->conn = $conn;

$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

$obj_customers->id = $_SESSION['customer_id'];
$profile_data = $obj_customers->readone_customer();
?>
<!-- Breadcrumbs-->
<ol class="breadcrumb">
	<li class="breadcrumb-item">
		<a href="<?php echo SITE_URL; ?>backend/my-appointments.php"><i class="fa fa-home"></i></a>
	</li>
	<li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['refer_a_friend'])){ echo $rzvy_translangArr['refer_a_friend']; }else{ echo $rzvy_defaultlang['refer_a_friend']; } ?></li>
</ol>
<div class="card mb-3">
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
				<?php 
				if($profile_data["refferral_code"] != ""){ 
					?>
					<div class="p-3">
						<div class="mb-3 p-3 border_double rzvy_refer_box">
							<div class="text-center pb-3"><i class="fa fa-gift fa-5x text-primary"></i></div>
							<h3 class="text-center text-dark"><?php if(isset($rzvy_translangArr['refer_to_your_friends'])){ echo $rzvy_translangArr['refer_to_your_friends']; }else{ echo $rzvy_defaultlang['refer_to_your_friends']; } ?></h3>
							<p class="text-center text-muted"><?php if(isset($rzvy_translangArr['ask_your_friends'])){ echo $rzvy_translangArr['ask_your_friends']; }else{ echo $rzvy_defaultlang['ask_your_friends']; } ?></p>
							<div class="rzvy_refer_input form-inline">
								<a class="btn btn-secondary mb-2" style="width: 100%;" id="rzvy_copyto_clipboard_code" href="javascript:void(0)"><i class="fa fa-clipboard"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['copy_to_clipboard'])){ echo $rzvy_translangArr['copy_to_clipboard']; }else{ echo $rzvy_defaultlang['copy_to_clipboard']; } ?></a>
								<input id="rzvy_copyto_clipboard_code_input" class="text-secondary form-control" type="text" readonly="readonly" style="width: 100%;" value="<?php echo $profile_data["refferral_code"]; ?>"/>
								
							</div>
							<div class="p-3 text-muted">
								<h3><?php if(isset($rzvy_translangArr['step_to_refer'])){ echo $rzvy_translangArr['step_to_refer']; }else{ echo $rzvy_defaultlang['step_to_refer']; } ?> </h3>
								<p><?php if(isset($rzvy_translangArr['refer_step_1'])){ echo $rzvy_translangArr['refer_step_1']; }else{ echo $rzvy_defaultlang['refer_step_1']; } ?></p>
								<p><?php if(isset($rzvy_translangArr['refer_step_2'])){ echo $rzvy_translangArr['refer_step_2']; }else{ echo $rzvy_defaultlang['refer_step_2']; } ?></p>
								<p><?php if(isset($rzvy_translangArr['refer_step_3'])){ echo $rzvy_translangArr['refer_step_3']; }else{ echo $rzvy_defaultlang['refer_step_3']; } ?></p>
							</div>
							<hr />
						    <small><b>[e.g. <?php if(isset($rzvy_translangArr['hi_use_my_refferal_code'])){ echo $rzvy_translangArr['hi_use_my_refferal_code']; }else{ echo $rzvy_defaultlang['hi_use_my_refferal_code']; } ?> <?php echo $profile_data["refferral_code"]; ?> <?php if(isset($rzvy_translangArr['to_book_appointments_here_ad'])){ echo $rzvy_translangArr['to_book_appointments_here_ad']; }else{ echo $rzvy_defaultlang['to_book_appointments_here_ad']; } ?> <?php echo SITE_URL.'?ref='.$profile_data["refferral_code"]; ?> ]</b></small>
						    <br /><br />
						    
						    <?php 
						    if(isset($rzvy_translangArr['hi_use_my_refferal_code'])){ $hi_use_my_refferal_code = $rzvy_translangArr['hi_use_my_refferal_code']; }else{ $hi_use_my_refferal_code = $rzvy_defaultlang['hi_use_my_refferal_code']; }
						    
						    if(isset($rzvy_translangArr['to_book_appointments_here_ad'])){ $to_book_appointments_here_ad = $rzvy_translangArr['to_book_appointments_here_ad']; }else{ $to_book_appointments_here_ad = $rzvy_defaultlang['to_book_appointments_here_ad']; }
						    
						    $referralContent = $hi_use_my_refferal_code." ".$profile_data["refferral_code"]." ".$to_book_appointments_here_ad." ".SITE_URL."?ref=".$profile_data["refferral_code"];
							
							
							$rzvy_refferal_channels = $obj_settings->get_option('rzvy_referral_channels');
							$rzvy_refferal_channel = array();
							if($rzvy_refferal_channels!=''){
								$rzvy_refferal_channel = explode(',',$rzvy_refferal_channels);
							}

						    ?>						    
							<div class="text-center">
								<div class="rzvy_refer_input mb-2 form-inline">
									<a class="btn btn-secondary mb-2" id="rzvy_copyto_clipboard" href="javascript:void(0)" style="width: 100%;"><i class="fa fa-clipboard"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['copy_to_clipboard'])){ echo $rzvy_translangArr['copy_to_clipboard']; }else{ echo $rzvy_defaultlang['copy_to_clipboard']; } ?></a>
									<textarea id="referral_social_share_input" class="form-control" style="width: 100%;" placeholder="<?php if(isset($rzvy_translangArr['enter_your_referral_share_message'])){ echo $rzvy_translangArr['enter_your_referral_share_message']; }else{ echo $rzvy_defaultlang['enter_your_referral_share_message']; } ?>"><?php echo $referralContent; ?></textarea>
								    <label class="error err_referral_share_message" style="display:none;"><?php if(isset($rzvy_translangArr['please_enter_your_referral_share_message'])){ echo $rzvy_translangArr['please_enter_your_referral_share_message']; }else{ echo $rzvy_defaultlang['please_enter_your_referral_share_message']; } ?></label>
								</div>
								<?php if(sizeof($rzvy_refferal_channel)>0){ ?>
									<h6 class="mt-4"><?php if(isset($rzvy_translangArr['quick_refer_using'])){ echo $rzvy_translangArr['quick_refer_using']; }else{ echo $rzvy_defaultlang['quick_refer_using']; } ?></h6>								
									<?php foreach($rzvy_refferal_channel as $rzvy_refferal_chann){ ?>
									<?php if($rzvy_refferal_chann=='facebook'){ ?>
									<!-- Facebook -->
									<a class="btn btn-primary rzvy_fb_share" href="http://www.facebook.com/sharer.php?u=<?php echo SITE_URL; ?>&quote=<?php echo $referralContent; ?>" target="_blank"><i class="fa fa-facebook"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['facebook'])){ echo $rzvy_translangArr['facebook']; }else{ echo $rzvy_defaultlang['facebook']; } ?></a>
									<?php } if($rzvy_refferal_chann=='twitter'){ ?>
									<!-- Twitter -->
									<a class="btn btn-info rzvy_twitter_share" href="http://twitter.com/share?url=<?php echo SITE_URL; ?>&hashtags=referral_code&text=<?php echo $referralContent; ?>" target="_blank"><i class="fa fa-twitter"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['twitter'])){ echo $rzvy_translangArr['twitter']; }else{ echo $rzvy_defaultlang['twitter']; } ?></a>
									<?php } if($rzvy_refferal_chann=='googleplus'){ ?>
									<!-- Google+ -->
									<a class="btn btn-danger rzvy_gplus_share" href="https://plus.google.com/share?url=<?php echo SITE_URL; ?>&prefilltext=<?php echo $referralContent; ?>" target="_blank"><i class="fa fa-google-plus"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['googleplus'])){ echo $rzvy_translangArr['googleplus']; }else{ echo $rzvy_defaultlang['googleplus']; } ?></a>
									<?php } if($rzvy_refferal_chann=='whatsapp'){ ?>
									<!-- Whatsapp -->
									<a class="btn btn-success rzvy_whatssapp_share" href="https://wa.me/?text=<?php echo $referralContent; ?>" target="_blank" data-action="share/whatsapp/share"><i class="fa fa-whatsapp"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['whatsapp'])){ echo $rzvy_translangArr['whatsapp']; }else{ echo $rzvy_defaultlang['whatsapp']; } ?></a>
									<?php } ?>
									<?php } ?>
								<?php } ?>
								
							</div>
						</div>
					</div>
					<?php 
				}else{ 
					?>
					<div class="p-3">
						<div class="mb-3 p-3 border_double rzvy_refer_box">
							<div class="text-center pb-3"><i class="fa fa-gift fa-5x text-danger"></i></div>
							<h3 class="text-center text-dark pb-3"><?php if(isset($rzvy_translangArr['opps_not_eligible_to_refer'])){ echo $rzvy_translangArr['opps_not_eligible_to_refer']; }else{ echo $rzvy_defaultlang['opps_not_eligible_to_refer']; } ?></h3>
						</div>
					</div>
					<?php 
				} 
				?>
			</div>
		</div>
	</div>
</div>	 
<?php include 'c_footer.php'; ?>