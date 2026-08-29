<?php 
include 'header.php';
if(!isset($rzvy_rolepermissions['rzvy_extaddons']) && $rzvy_loginutype=='staff'){ ?>
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
/* Send the GET request with cURL */
$url = 'http://www.perfecky.com/addons-list.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$externalAddonsResponse = curl_exec($ch);

if($externalAddonsResponse!=''){
	$externalAddonsListing = json_decode($externalAddonsResponse,true); 
}else{
	$externalAddonsListing = array();
}

/* GET activated addons*/
$activeExtAddons = array();
$rzvy_activated_addons = $obj_settings->get_option("rzvy_activated_addons");
if($rzvy_activated_addons != ""){
	$unserialized_activated_addons = unserialize($rzvy_activated_addons);
	$activeExtAddons = $unserialized_activated_addons;
}
?>
<link href="<?php echo SITE_URL; ?>includes/css/rzvy-external-addons.css?<?php echo time(); ?>" rel="stylesheet" />
<!-- Breadcrumbs-->
<ol class="breadcrumb">
	<li class="breadcrumb-item">
		<a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
	</li>
	<li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['addons'])){ echo $rzvy_translangArr['addons']; }else{ echo $rzvy_defaultlang['addons']; } ?></li>
</ol>
<div class="mb-3">
	<div class="rzvy-listview row">
		<?php 
		$addonactivecheck = array();
		foreach($externalAddonsListing as $extaddon){
			?>
			<div class="rzvy-listview-list my-2 col-md-12">
				<div class="rzvy-listview-list-data">
					<div class="rzvy-listview-list-image">
						<img style="width: inherit;" src="<?php echo $extaddon['logo']; ?>" />
					</div>
					<?php 
					
					if(sizeof($activeExtAddons)>0){
						$counterA = 0;						
						for($i=0;$i<sizeof($activeExtAddons);$i++){	
							if(in_array($activeExtAddons[$i]["addon"],$addonactivecheck)){ continue; }							
							if($activeExtAddons[$i]["addon"] == $extaddon['addon'] && $activeExtAddons[$i]["verified"] == "Y" && file_exists(dirname(dirname(__FILE__))."/addons/".$extaddon['addon']."/functions.php")){
								?>
								<div class="rzvy-listview-list-info px-3">
									<div class="rzvy-listview-list-title"><?php echo $extaddon['title']; ?> <span class="rzvy-listview-list-verified"><i class="fa fa-clock-o"></i> Active</span></div>
									<div class="rzvy-listview-list-sub-info">
										<div><?php echo $extaddon['description']; ?></div>
									</div>
								</div>
								<div class="rzvy-listview-list-badge-main">
									<div class="rzvy-listview-list-badge-verified">Verified</div>
								</div>
								<?php
								$counterA++;
								$addonactivecheck[]= $activeExtAddons[$i]["addon"];
							}
						}
						if($counterA == 0){
							?>
							<div class="rzvy-listview-list-info px-3">
								<div class="rzvy-listview-list-title"><?php echo $extaddon['title']; ?> <span class="rzvy-listview-list-notverified"><i class="fa fa-clock-o"></i> Not Active</span></div>
								<div class="rzvy-listview-list-sub-info">
									<div><?php echo $extaddon['description']; ?></div>
								</div>
							</div>
							<div class="rzvy-listview-list-badge-main">
								<div class="rzvy-listview-list-badge-notverified" data-slug="<?php echo $extaddon['slug']; ?>" data-key="<?php echo $extaddon['addon']; ?>">Verify Now</div>
							</div>
							<?php
						}
					}else{
						?>
						<div class="rzvy-listview-list-info px-3">
							<div class="rzvy-listview-list-title"><?php echo $extaddon['title']; ?> <span class="rzvy-listview-list-notverified"><i class="fa fa-clock-o"></i> Not Active</span></div>
							<div class="rzvy-listview-list-sub-info">
								<div><?php echo $extaddon['description']; ?></div>
							</div>
						</div>
						<div class="rzvy-listview-list-badge-main">
							<div class="rzvy-listview-list-badge-notverified" data-slug="<?php echo $extaddon['slug']; ?>" data-key="<?php echo $extaddon['addon']; ?>">Verify Now</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
			<?php 
		}
		?>
	</div>
</div>
<?php include 'footer.php'; ?>