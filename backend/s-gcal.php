<?php 
include 'staff-header.php'; 
$rzvy_gc_status = $obj_settings->get_staff_option('rzvy_gc_status');
$rzvy_gc_twowaysync = $obj_settings->get_staff_option('rzvy_gc_twowaysync');
$rzvy_gc_calendarid = (($obj_settings->get_staff_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_staff_option('rzvy_gc_calendarid'):'primary');
$rzvy_gc_clientid = $obj_settings->get_option('rzvy_gc_clientid');
$rzvy_gc_clientsecret = $obj_settings->get_option('rzvy_gc_clientsecret');
$rzvy_gc_accesstoken = $obj_settings->get_staff_option('rzvy_gc_accesstoken');
$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);

$sanitized_auth_url = "javascript:void(0)";
if($rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken == ""){
	include(dirname(dirname(__FILE__))."/includes/google-calendar/vendor/autoload.php");
	$client = new Google_Client();
	$client->setClientId($rzvy_gc_clientid);
	$client->setClientSecret($rzvy_gc_clientsecret);
	$client->setRedirectUri(SITE_URL."backend/s-gcal.php");
	$client->addScope(array(Google_Service_Calendar::CALENDAR,Google_Service_Calendar::CALENDAR_EVENTS));
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
	if(isset($_GET['code'])) {
		$accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
		$obj_settings->update_staff_option('rzvy_gc_accesstoken',base64_encode(serialize($accessToken)));
		?><script> window.location.href = "<?php echo SITE_URL."backend/s-gcal.php"; ?>"; </script><?php 
	} else {
		$auth_url = $client->createAuthUrl();
		$sanitized_auth_url = $auth_url;
	}
}

if(isset($_POST["update_gc_settings_btn"])){
	$obj_settings->update_staff_option('rzvy_gc_status',$_POST['rzvy_gc_status']);
	$obj_settings->update_staff_option('rzvy_gc_twowaysync',$_POST['rzvy_gc_twowaysync']);
	$obj_settings->update_staff_option('rzvy_gc_calendarid',$_POST['rzvy_gc_calendarid']);
	?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		<?php if(isset($rzvy_translangArr['credentials_updated_successfully'])){ echo $rzvy_translangArr['credentials_json_file_uploaded_successfully']; }else{ echo $rzvy_defaultlang['credentials_updated_successfully']; } ?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	</div>
	<script> window.location.href = "<?php echo SITE_URL."backend/s-gcal.php"; ?>"; </script><?php 
	exit;
}
?>
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
	<li class="breadcrumb-item">
	  <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
	</li>
	<li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['google_calendar'])){ echo $rzvy_translangArr['google_calendar']; }else{ echo $rzvy_defaultlang['google_calendar']; } ?></li>
  </ol>
  <div class="card mb-3">
	<div class="card-header">
	  <i class="fa fa-fw fa-gear"></i> <?php if(isset($rzvy_translangArr['google_calendar_settings'])){ echo $rzvy_translangArr['google_calendar_settings']; }else{ echo $rzvy_defaultlang['google_calendar_settings']; } ?>
	  </div>
	<div class="card-body">
	  <div class="row">
		<div class="col-md-8 mt-2">
		  <form name="rzvy_gc_settings_form" id="rzvy_gc_settings_form" method="post">
			<div class="form-group row">
				<div class="col-md-6">
					<label class="control-label"><?php if(isset($rzvy_translangArr['gcclientstatus'])){ echo $rzvy_translangArr['gcclientstatus']; }else{ echo $rzvy_defaultlang['gcclientstatus']; } ?></label>
					<select name="rzvy_gc_status" id="rzvy_gc_status" class="selectpicker form-control" required>
						<option <?php if($rzvy_gc_status == "Y"){ echo "selected"; } ?> value="Y"><?php if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; } ?></option>
						<option <?php if($rzvy_gc_status != "Y"){ echo "selected"; } ?> value="N"><?php if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; } ?></option>
					</select>
				</div>
				<div class="col-md-6">
					<label class="control-label"><?php if(isset($rzvy_translangArr['gcclienttwowaysync'])){ echo $rzvy_translangArr['gcclienttwowaysync']; }else{ echo $rzvy_defaultlang['gcclienttwowaysync']; } ?></label>
					<select name="rzvy_gc_twowaysync" id="rzvy_gc_twowaysync" class="selectpicker form-control" required>
						<option <?php if($rzvy_gc_twowaysync == "Y"){ echo "selected"; } ?> value="Y"><?php if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; } ?></option>
						<option <?php if($rzvy_gc_twowaysync != "Y"){ echo "selected"; } ?> value="N"><?php if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; } ?></option>
					</select>
				</div>
			</div>
			<?php 
			if($rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != ""){
				include(dirname(dirname(__FILE__))."/includes/google-calendar/vendor/autoload.php");
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
				$calendarList = $service->calendarList->listCalendarList();
				?>
				<div class="form-group row">
					<div class="col-md-12">
						<label class="control-label"><?php if(isset($rzvy_translangArr['gcselectcalendar'])){ echo $rzvy_translangArr['gcselectcalendar']; }else{ echo $rzvy_defaultlang['gcselectcalendar']; } ?></label>
						<select name="rzvy_gc_calendarid" id="rzvy_gc_calendarid" class="selectpicker form-control" required>
							<?php 
							$calOptionCount = 0;
							foreach ($calendarList->getItems() as $calendarListEntry) {
								if($calendarListEntry->getAccessRole() == "owner"){
									$calSelected = "";
									if($rzvy_gc_calendarid == "primary"){
										if($calendarListEntry->getPrimary()){
											$calSelected = "selected='selected'";
										}
									}else if($calendarListEntry->getId() == $rzvy_gc_calendarid){
										$calSelected = "selected='selected'";
									}
									?>
									<option <?php echo $calSelected; ?> value="<?php echo $calendarListEntry->getId(); ?>"><?php echo $calendarListEntry->getSummary(); ?></option>
									<?php 
									$calOptionCount++;
								}
							}
							if($calOptionCount==0){
								?>
								<option selected value="primary">Primary</option>
								<?php 
							}
							?>
						</select>
					</div>
				</div>
				<?php 
			}else{
				?>
				<input name="rzvy_gc_calendarid" id="rzvy_gc_calendarid" type="hidden" value="<?php echo "primary"; ?>" />
				<?php 
			}
			?>
			<input name="update_gc_settings_btn" class="btn btn-primary btn-block" type="submit" value="<?php if(isset($rzvy_translangArr['update_settings'])){ echo $rzvy_translangArr['update_settings']; }else{ echo $rzvy_defaultlang['update_settings']; } ?>" />
		 </form>
		</div>
		<?php if($rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != ""){ ?>
		  <div class="col-md-4 mt-2">
			  <a id="disconnect_gc_account_btn" class="btn btn-success btn-block" href="javascript:void(0)"><?php if(isset($rzvy_translangArr['disconnect_gc_account'])){ echo $rzvy_translangArr['disconnect_gc_account']; }else{ echo $rzvy_defaultlang['disconnect_gc_account']; } ?></a>
			  <br /><small class='text-info'>[<?php if(isset($rzvy_translangArr['note_after_upload_your_credentials_file_please_connect_your_google_account'])){ echo $rzvy_translangArr['note_after_upload_your_credentials_file_please_connect_your_google_account']; }else{ echo $rzvy_defaultlang['note_after_upload_your_credentials_file_please_connect_your_google_account']; } ?>]</small>
		  </div>
		<?php }else{ ?>
		  <div class="col-md-4 mt-2">
			  <a id="connect_gc_account_btn" class="btn btn-dark btn-block" href="<?php echo $sanitized_auth_url; ?>"><?php if(isset($rzvy_translangArr['connect_gc_account'])){ echo $rzvy_translangArr['connect_gc_account']; }else{ echo $rzvy_defaultlang['connect_gc_account']; } ?></a>
			  <br /><small class='text-info'>[<?php if(isset($rzvy_translangArr['note_after_upload_your_credentials_file_please_connect_your_google_account'])){ echo $rzvy_translangArr['note_after_upload_your_credentials_file_please_connect_your_google_account']; }else{ echo $rzvy_defaultlang['note_after_upload_your_credentials_file_please_connect_your_google_account']; } ?>]</small>
		  </div>
		<?php } ?>
	  </div>
	</div>
  </div>
<?php include 'staff-footer.php'; ?>