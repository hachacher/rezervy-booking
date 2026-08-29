<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_frontend.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_slots.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_calendar.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_loyalty_points.php");

/* Create object of classes */
$obj_frontend = new rzvy_frontend();
$obj_frontend->conn = $conn;

$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

$obj_slots = new rzvy_slots();
$obj_slots->conn = $conn;

$obj_calendar = new rzvy_calendar();
$obj_calendar->conn = $conn;

$obj_slots = new rzvy_slots();
$obj_slots->conn = $conn;

$obj_lpoint = new rzvy_loyalty_points();
$obj_lpoint->conn = $conn;

if(isset($_SESSION['rzvy_staff_id'])){
	$obj_slots->staff_id = $_SESSION['rzvy_staff_id'];
}
$time_interval = $obj_settings->get_option('rzvy_timeslot_interval');
$rzvy_currency_symbol = $obj_settings->get_option('rzvy_currency_symbol');
$rzvy_currency_position = $obj_settings->get_option('rzvy_currency_position');
$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');
$advance_bookingtime = $obj_settings->get_option('rzvy_maximum_advance_booking_time');
$rzvy_location_selector_status = $obj_settings->get_option("rzvy_location_selector_status"); 
$rzvy_show_category_image = $obj_settings->get_option("rzvy_show_category_image"); 
$rzvy_show_service_image = $obj_settings->get_option("rzvy_show_service_image"); 
$rzvy_show_addon_image = $obj_settings->get_option("rzvy_show_addon_image"); 
$rzvy_show_staff_image = $obj_settings->get_option("rzvy_show_staff_image"); 
$rzvy_show_parentcategory_image = $obj_settings->get_option("rzvy_show_parentcategory_image"); 
$rzvy_parent_category = $obj_settings->get_option("rzvy_parent_category"); 
$rzvy_services_listing_view = $obj_settings->get_option("rzvy_services_listing_view"); 
$rzvy_image_type = $obj_settings->get_option("rzvy_image_type"); 
$rzvy_book_with_datetime = $obj_settings->get_option("rzvy_book_with_datetime"); 
$rzvy_price_display = $obj_settings->get_option("rzvy_price_display");

$currencyB = $rzvy_currency_symbol.' ';
$currencyA = '';
if($rzvy_currency_position=='A'){
	$currencyB = '';
	$currencyA= ' '.$rzvy_currency_symbol;
}
		
$rzvyrounded = '';
if($rzvy_image_type=='rounded-circle'){
	$rzvyrounded = 'rounded';
}


$insideContentAlignment = "text-center";
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

/* get services by category id ajax */
if(isset($_POST['get_services_by_cat_id'])){
	$obj_frontend->category_id = $_POST['id'];
	$all_services = $obj_frontend->get_services_by_cat_id();
	$nonlocation_services = 0;
	?>
	<?php if($rzvy_services_listing_view == "L"){ ?>
	<div class="rzvy-listview">
	<?php } ?>
	<?php 
	if(mysqli_num_rows($all_services)>0){
		if($_SESSION['rzvy_cart_category_id'] == $_POST['id']){
			$_SESSION['rzvy_cart_category_id'] = $_POST['id'];
		}else{
			$_SESSION['rzvy_cart_category_id'] = $_POST['id'];
			$_SESSION['rzvy_cart_items'] = array();
			$_SESSION['rzvy_cart_total_addon_duration'] = 0;
			$_SESSION['rzvy_cart_service_id'] = "";
		}
		?>
		<h4 class="mt-4 <?php echo $labelAlignmentClassName; ?>" ><?php if(isset($rzvy_translangArr['tell_us_about_your_service'])){ echo $rzvy_translangArr['tell_us_about_your_service']; }else{ echo $rzvy_defaultlang['tell_us_about_your_service']; } ?></h4>
		<div class="services <?php echo $rzvyrounded;?>">
            <div class="owl-carousel" data-items="4" data-items-lg="4" data-items-md="2" data-items-sm="2" data-items-ssm="1" data-margin="24" data-dots="true" data-nav="true" autoplay="false" data-loop="false">
		<?php 
		$rzvy_currency_symbol = $obj_settings->get_option('rzvy_currency_symbol');
		while($service = mysqli_fetch_array($all_services)){
			if(isset($service['locations']) && $service['locations']!='' && $rzvy_location_selector_status=='Y'){
				$service_locations = explode(',',$service['locations']);
				if(isset($_SESSION['rzvy_location_selector_zipcode']) && !in_array($_SESSION['rzvy_location_selector_zipcode'],$service_locations)){ $nonlocation_services++; continue; }
				
			}
			   
			if($rzvy_services_listing_view == "L"){  
				?>
				<div id="rzvy-services-radio-<?php echo $service["id"]; ?>" class="rzvy-listview-list my-1 rzvy-services-radio-change <?php if($_SESSION['rzvy_cart_service_id'] == $service['id']){ echo "list_active";} ?>" data-id="<?php echo $service["id"]; ?>">
					<div class="rzvy-listview-list-data">
						<?php 
						if($rzvy_show_service_image == "Y"){
							?>
							<div class="rzvy-listview-list-image">
								<img style="width: inherit;" src="<?php if($service['image'] != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$service['image'])){ echo SITE_URL."uploads/images/".$service['image']; }else{ echo SITE_URL."includes/images/noimage.png"; } ?>" />
							</div>
							<?php 
						}
						?>
						<div class="rzvy-listview-list-info px-1">
							<div class="rzvy-listview-list-title">
								<?php 
								echo $service['title']." ";
								if($service['duration']>0){
									?><span class="rzvy-listview-list-price"><i class="fa fa-clock-o"></i> <?php echo $service['duration']." Min."; ?></span><?php 
								} if($rzvy_price_display=='Y'){ 
								?><span class="rzvy-listview-list-price"><i class="fa fa-tag"></i> <?php 
									if($service['rate']>0){ 
										echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$service['rate']);
									}else{ 
										echo (isset($rzvy_translangArr['free']))?$rzvy_translangArr['free']:$rzvy_defaultlang['free']; 
								} ?></span><?php } ?>
							</div>
							<div class="rzvy-listview-list-sub-info">
								<div><?php echo $service['description']; ?></div>
							</div>
						</div>
						<div class="rzvy-listview-list-badge-main">
							<?php if($service['badge']=="Y"){ ?>
								<div class="rzvy-listview-list-badge"><?php echo $service['badge_text']; ?></div>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php 
			} else {
				?>
				<div class="item">
                  <figure>
					<?php $rzvyptclass = ''; if($rzvy_show_service_image == "Y"){ ?><img src="<?php if($service['image'] != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$service['image'])){ echo SITE_URL."uploads/images/".$service['image']; }else{ echo SITE_URL."includes/images/noimage.png"; } ?>"><?php }else{ $rzvyptclass = 'rzvy-pt-figcaption'; } ?>
                    <figcaption class="<?php echo $rzvyptclass; ?>">
					<?php if($service['badge']=="Y"){ ?>
						<span class="tag"><?php echo $service['badge_text']; ?></span>
					<?php } ?>                   
                      <h3><?php echo ucwords($service["title"]); ?></h3>
                      <?php if($service['description']!=""){ ?>
						<p><?php if(strlen($service['description'])<=45){ echo $service['description']."..."; }else{ echo substr(ucwords($service['description']), 0, 45)."..."; } ?></p>
					<?php } ?>
                      <div class="service-meta">
                        <?php if($service['duration']>0){ ?><span class="<?php echo ($service['rate']>0 && $rzvy_price_display=='Y')?"pull-left":"text-center"; ?>"><i class="fa fa-clock-o"></i> <?php echo $service['duration']." Min."; ?></span><?php } if($rzvy_price_display=='Y'){ ?><span class="<?php echo ($service['duration']>0)?"pull-right":"text-center"; ?>"><i class="fa fa-tag"></i> <?php if($service['rate']>0){ echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$service['rate']); }else{ if(isset($rzvy_translangArr['free'])){ echo $rzvy_translangArr['free']; }else{ echo $rzvy_defaultlang['free']; } } ?></span><?php } ?>
                      </div>
                      <input type="radio" name="rzvy-services-radio" class="<?php echo $inputAlignment; ?> rzvy-services-radio-change" id="rzvy-services-radio-<?php echo $service["id"]; ?>" data-id="<?php echo $service["id"]; ?>">
                      <a href="javascript:void(0);" class="read-more" data-bs-toggle="offcanvas" data-bs-target="#rzvy-view-service-modal-<?php echo $service['id']; ?>" aria-controls="<?php echo ucwords($service["title"]); ?>"><?php if(isset($rzvy_translangArr['read_more'])){ echo $rzvy_translangArr['read_more']; }else{ echo $rzvy_defaultlang['read_more']; } ?></a>
                    </figcaption>
                  </figure>
                </div>
				<?php 
			}
		} ?></div>
		</div><?php 
		$obj_frontend->category_id = $_POST['id'];
		$all_services = $obj_frontend->get_services_by_cat_id();
		$nonlocation_services = 0;
		if(mysqli_num_rows($all_services)>0){
			while($service = mysqli_fetch_array($all_services)){
				if(isset($service['locations']) && $service['locations']!='' && $rzvy_location_selector_status=='Y'){
					$service_locations = explode(',',$service['locations']);
					if(isset($_SESSION['rzvy_location_selector_zipcode']) && !in_array($_SESSION['rzvy_location_selector_zipcode'],$service_locations)){ $nonlocation_services++; continue; }
					
				}
		?>
					<div class="offcanvas offcanvas-end" tabindex="-1" id="rzvy-view-service-modal-<?php echo $service["id"]; ?>">
					  <div class="offcanvas-header">
						<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
					  </div>
					  <div class="offcanvas-body">
						<?php 
						$service_image = $service['image'];
						if($service_image != '' && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$service_image)){
							$serviceimage = SITE_URL."uploads/images/".$service_image;
						}else{
							$serviceimage = SITE_URL."includes/images/noimage.png";
						}
						$otherdetailpart = "12";
						if($rzvy_show_service_image == "Y"){
							$otherdetailpart = "9";
							?>
							<div class="rzvy-image">
								<img src="<?php echo $serviceimage; ?>"/>
							</div>
							<?php
						}
						?>
						<h2><?php echo $service['title']; ?></h2>
						<p><?php echo ucfirst($service['description']); ?></p>
						<div class="service-meta">
							<?php if($rzvy_price_display=='Y'){ ?> <span><i class="fa fa-fw fa-money"></i>&nbsp;&nbsp;<strong><?php if(isset($rzvy_translangArr['rate_ad'])){ echo $rzvy_translangArr['rate_ad']; }else{ echo $rzvy_defaultlang['rate_ad']; } ?></strong>&nbsp;<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$service['rate']); ?>	</span><?php } ?>
							<span><i class="fa fa-fw fa-clock-o"></i>&nbsp;&nbsp;<strong><?php if(isset($rzvy_translangArr['duration_ad'])){ echo $rzvy_translangArr['duration_ad']; }else{ echo $rzvy_defaultlang['duration_ad']; } ?></strong>&nbsp;<?php echo $service['duration']." Minutes"; ?></span>
							<span><i class="fa fa-fw fa-map-marker"></i>&nbsp;&nbsp;<strong><?php if(isset($rzvy_translangArr['service_locations'])){ echo $rzvy_translangArr['service_locations']; }else{ echo $rzvy_defaultlang['service_locations']; } ?> </strong>&nbsp;<?php if(isset($service['locations']) && $service['locations']!=''){ echo $service['locations']; }else{ if(isset($rzvy_translangArr['all_over'])){ echo $rzvy_translangArr['all_over']; }else{ echo $rzvy_defaultlang['all_over']; } } ?></span>	
						</div>
					  </div>
					</div>
					<?php 
				}	
			}
		
		if($nonlocation_services==mysqli_num_rows($all_services)){
			?>
			<h5 class="step-title"><?php if(isset($rzvy_translangArr['there_is_no_services_for_this_location'])){ echo $rzvy_translangArr['there_is_no_services_for_this_location']; }else{ echo $rzvy_defaultlang['there_is_no_services_for_this_location']; } ?></h5>
			<?php
		}
	}else{
		?>
		<h5 class="step-title"><?php if(isset($rzvy_translangArr['there_is_no_services_for_this_category'])){ echo $rzvy_translangArr['there_is_no_services_for_this_category']; }else{ echo $rzvy_defaultlang['there_is_no_services_for_this_category']; } ?></h5>
		<?php
	}
	?>
	<?php if($rzvy_services_listing_view == "L"){ ?>
	</div>
	<?php }
}

/* get addons by service id ajax */
else if(isset($_POST['get_multi_and_single_qty_addons_content'])){
	$obj_frontend->service_id = $_POST['id'];
	$service = $obj_frontend->readone_service(); 
	$all_mq_addons = $obj_frontend->get_all_addons_by_service_id(); 
	/* $all_mq_addons = $obj_frontend->get_multiple_qty_addons_by_service_id(); 
	$all_sq_addons = $obj_frontend->get_single_qty_addons_by_service_id();  */
	$maddons_count = mysqli_num_rows($all_mq_addons);

	$rzvy_booking_first_selection_as = $obj_settings->get_option("rzvy_booking_first_selection_as");
	if($rzvy_booking_first_selection_as == "allservices"){
		$_SESSION['rzvy_cart_category_id'] = $service['cat_id'];
	}
	
	if($_SESSION['rzvy_cart_service_id'] != $_POST['id']){
		$_SESSION['rzvy_cart_service_id'] = $_POST['id'];
		$_SESSION['rzvy_cart_items'] = array(); 
		$_SESSION['rzvy_cart_total_addon_duration'] = 0;
	}
	$_SESSION['rzvy_cart_service_price'] = $service['rate'];
	$_SESSION['rzvy_cart_subtotal'] = $service['rate'];
	$_SESSION['rzvy_cart_nettotal'] = $service['rate'];
	$_SESSION['rzvy_cart_freqdiscount'] = 0;
	$_SESSION['rzvy_cart_coupondiscount'] = 0;
	$_SESSION['rzvy_cart_couponid'] = "";
	$_SESSION['rzvy_referral_discount_amount'] = 0;
	$_SESSION['rzvy_cart_tax'] = 0;
	?>
	<div class="mt-4">
		<?php  if($maddons_count>0){  ?>
			<div class="hide_service_hidden_main mt-4">
				<h4 <?php echo $labelAlignmentClass; ?> ><?php if(isset($rzvy_translangArr['select_additional_services'])){ echo $rzvy_translangArr['select_additional_services']; }else{ echo $rzvy_defaultlang['select_additional_services']; } ?></h4>
				<div class="services <?php echo $alignmentClass; ?> <?php echo $rzvyrounded;?>">
					<div class="owl-carousel" data-items="4" data-items-lg="4" data-items-md="2" data-items-sm="2" data-items-ssm="1" data-margin="24" data-dots="true" data-nav="true" autoplay="false" data-loop="false">					
				<?php 
				while($addon = mysqli_fetch_array($all_mq_addons)){ 
					?>
					<div class="rzvy_addon_card_li">
						<div class="item">
							<input id="rzvy-addon-card-mnl-<?php echo $addon['id']; ?>" type="hidden" value="<?php if($addon["multiple_qty"] == "N" || $addon["max_limit"] == 1){ echo "1"; }else{ echo $addon['min_limit']; } ?>" />
							<input id="rzvy-addon-card-ml-<?php echo $addon['id']; ?>" type="hidden" value="<?php if($addon["multiple_qty"] == "N" || $addon["max_limit"] == 1){ echo "1"; }else{ echo $addon['max_limit']; } ?>" />
						  <figure>
							<?php $rzvyptclass = ''; if($rzvy_show_addon_image == "Y"){ ?><img src="<?php if($addon['image'] != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$addon['image'])){ echo SITE_URL."uploads/images/".$addon['image']; }else{ echo SITE_URL."includes/images/noimage.png"; } ?>" class="<?php if($addon["multiple_qty"] == "Y" && $addon["max_limit"] > 1){ echo " rzvy_make_multipleqty_addon_card_selected"; } ?>" data-id="<?php echo $addon["id"]; ?>"><?php }else{ $rzvyptclass = 'rzvy-pt-figcaption'; } ?>
							<figcaption class="<?php echo $rzvyptclass; ?>">
							  <?php if($addon['badge']=="Y"){ ?>
									<span class="tag <?php if($addon["multiple_qty"] == "Y" && $addon["max_limit"] > 1){ echo "rzvy_make_multipleqty_addon_card_selected"; } ?>" data-id="<?php echo $addon["id"]; ?>"><?php echo $addon['badge_text']; ?></span>
								<?php } ?>
							  <h3 <?php if($addon["multiple_qty"] == "Y" && $addon["max_limit"] > 1){ echo " rzvy_make_multipleqty_addon_card_selected"; } ?>><?php echo ucwords($addon["title"]); ?></h3>
							  <?php if($addon['description']!=""){ ?>
								<p class="<?php if($addon["multiple_qty"] == "Y" && $addon["max_limit"] > 1){ echo " rzvy_make_multipleqty_addon_card_selected"; } ?>" data-id="<?php echo $addon["id"]; ?>"><?php if(strlen($addon['description'])<=45){ echo $addon['description']."..."; }else{ echo substr(ucwords($addon['description']), 0, 45)."..."; } ?></p>
							<?php } ?>
							  <div class="service-meta <?php if($addon["multiple_qty"] == "Y" && $addon["max_limit"] > 1){ echo " rzvy_make_multipleqty_addon_card_selected"; } ?>" data-id="<?php echo $addon["id"]; ?>">
								<?php if($addon['duration']>0){ ?><span class="<?php echo ($addon['rate']>0 && $rzvy_price_display=='Y')?"pull-left":"text-center"; ?>"><i class="fa fa-clock-o"></i> <?php echo $addon['duration']." Min."; ?></span><?php } if($rzvy_price_display=='Y'){ ?><span class="<?php echo ($addon['duration']>0)?"pull-right":"text-center"; ?>"><i class="fa fa-tag"></i> <?php if($addon['rate']>0){ echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$addon['rate']); }else{ if(isset($rzvy_translangArr['free'])){ echo $rzvy_translangArr['free']; }else{ echo $rzvy_defaultlang['free']; } } ?></span><?php } ?>
							  </div>
							  <input type="checkbox" data-aid="<?php echo $addon["id"]; ?>" name="rzvy-addon-cb<?php echo $addon["id"]; ?>"  class="rzvy_addons_mltqty_cb <?php echo $inputAlignment; ?> 
								<?php if($addon["multiple_qty"] == "N" || $addon["max_limit"] == 1){ ?>
								rzvy-addon-card-singleqty-unit-selection rzvy_addon_card_input
							<?php }else{ ?>
								rzvy-addon-card-multipleqty-unit-selection-<?php echo $addon["id"]; ?> rzvy_make_multipleqty_addon_card_selected 
							<?php } ?>" data-id="<?php echo $addon["id"]; ?>" <?php if($addon["multiple_qty"] == "N" || $addon["max_limit"] == 1){
								?>id="rzvy-addon-card-singleqty-box-<?php echo $addon["id"]; ?>"<?php
							}else{
								?>id="rzvy-addon-card-multipleqty-box-<?php echo $addon["id"]; ?>"<?php 
							} ?>>
							  <?php if($addon["multiple_qty"] == "Y"){ ?>
							  <div class="quantity">
								<input class="form-control rzvy_plusminus_addon_card_input rzvy-addon-card-multipleqty-js-counter-value rzvy-addon-card-multipleqty-unit-<?php echo $addon['id']; ?>" id="rzvy_plusminus_addon_card_input_<?php echo $addon['id']; ?>"  type="number" data-id="<?php echo $addon['id']; ?>" value="0" disabled="disabled" tabindex="-1" min="0" max="10" required />
								
								<a href="javascript:void(0)"  id="rzvy-addon-card-multipleqty-minus-js-counter-btn-<?php echo $addon['id']; ?>" class="quantity-controler down rzvy_plusminus_addon_card_btns rzvy_plusminus_addon_card_minus_btn_<?php echo $addon['id']; ?> rzvy_flag_minus" data-action="minus" data-id="<?php echo $addon['id']; ?>"><i class="fa fa-minus"></i></a>
								
								<a href="javascript:void(0)"  class="quantity-controler up rzvy_plusminus_addon_card_btns rzvy_plusminus_addon_card_plus_btn_<?php echo $addon['id']; ?> rzvy_flag_plus" id="rzvy-addon-card-multipleqty-plus-js-counter-btn-<?php echo $addon['id']; ?>" data-action="plus" data-id="<?php echo $addon['id']; ?>"><i class="fa fa-plus"></i></a>
							  </div>
							  <?php } ?>
							  <a href="javascript:void(0);" class="read-more" data-bs-toggle="offcanvas" data-bs-target="#rzvy-view-addon-modal-<?php echo $addon['id']; ?>" aria-controls="<?php echo ucwords($addon["title"]); ?>"><?php if(isset($rzvy_translangArr['read_more'])){ echo $rzvy_translangArr['read_more']; }else{ echo $rzvy_defaultlang['read_more']; } ?></a>
							</figcaption>
						  </figure>
						</div>
					</div>
					<?php 
					} 
					?>
					</div>
				</div>
			</div>
			<?php 
		} 
		$obj_frontend->service_id = $_POST['id'];
		$all_maddons = $obj_frontend->get_all_addons_by_service_id(); 
		if($maddons_count>0){
			while($addon = mysqli_fetch_array($all_maddons)){ ?>
				<div class="offcanvas offcanvas-end" tabindex="-1" id="rzvy-view-addon-modal-<?php echo $addon["id"]; ?>">
				  <div class="offcanvas-header">
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				  </div>
				  <div class="offcanvas-body">
					<?php if($rzvy_show_addon_image == "Y"){ ?><img src="<?php if($addon['image'] != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$addon['image'])){ echo SITE_URL."uploads/images/".$addon['image']; }else{ echo SITE_URL."includes/images/noimage.png"; } ?>"><?php } ?>
					
					<h2><?php echo $addon['title']; ?></h2>
					<p><?php echo ucfirst($addon['description']); ?></p>
					<div class="service-meta">
						<?php if($rzvy_price_display=='Y'){ ?><span><i class="fa fa-fw fa-money"></i>&nbsp;&nbsp;<strong><?php if(isset($rzvy_translangArr['rate_ad'])){ echo $rzvy_translangArr['rate_ad']; }else{ echo $rzvy_defaultlang['rate_ad']; } ?> </strong><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$addon['rate']); ?></span><?php } ?>
						
						<span><i class="fa fa-fw fa-clock-o"></i>&nbsp;&nbsp;<strong><?php if(isset($rzvy_translangArr['duration_ad'])){ echo $rzvy_translangArr['duration_ad']; }else{ echo $rzvy_defaultlang['duration_ad']; } ?> </strong><?php echo $addon['duration']." Minutes"; ?></span>
					</div>
				  </div>
				</div>
			<?php }
		} 	
		?>
	</div>
	<?php 
}

/** Get Second box with frequently discount, staff & calendar **/
else if(isset($_POST['get_second_step_box'])){ 
	$all_frequently_discount = $obj_frontend->get_all_frequently_discount(); 
	if(mysqli_num_rows($all_frequently_discount)>0){ 
		?>
		<h4 <?php echo $labelAlignmentClass; ?> ><?php if(isset($rzvy_translangArr['how_often_would_you_like_service'])){ echo $rzvy_translangArr['how_often_would_you_like_service']; }else{ echo $rzvy_defaultlang['how_often_would_you_like_service']; } ?></h4>
		<div class="custom-controls">
            <div class="rzvy-container">
               <div class="row justify-content-center <?php echo $alignmentClass; ?>">
				<?php 
				while($fd_discount = mysqli_fetch_array($all_frequently_discount)){ 
					?>
					<div class="col-md-3 col-sm-6">
						<div class="form-check custom">
							<input type="radio" id="rzvy-frequently-discount-<?php echo $fd_discount['id']; ?>" name="rzvy-frequently-discount" class="rzvy-frequently-discount-change form-check-input" value="<?php echo $fd_discount['id']; ?>" />
							<label class="form-check-label" for="rzvy-frequently-discount-<?php echo $fd_discount['id']; ?>" <?php if($fd_discount['fd_description'] != ""){ echo ' data-toggle="tooltip" data-placement="bottom" title="'.$fd_discount['fd_description'].'"'; } ?>><?php if($fd_discount['fd_key']=="weekly"){ echo $fq_weekly_label; }else if($fd_discount['fd_key']=="bi weekly"){ echo $fq_biweekly_label; }else if($fd_discount['fd_key']=="monthly"){ echo $fq_monthly_label; }else{ echo $fq_one_time_label; } ?></label>
						</div>
					</div>
					<?php 
				} ?>
				</div>
			</div>
		</div>
		<?php 
	}else{
		$_SESSION['rzvy_cart_freqdiscount_id'] = "";
		$_SESSION['rzvy_cart_freqdiscount'] = 0;
		$_SESSION['rzvy_cart_freqdiscount_label'] = "";
		$_SESSION['rzvy_cart_freqdiscount_key'] = "";
	} 
	
	$service_id = $_SESSION['rzvy_cart_service_id'];
	$getall_service_staffid = $obj_frontend->getall_service_staff($service_id);
	$servicelinkedstaffs = mysqli_num_rows($getall_service_staffid);
	
	if($servicelinkedstaffs>0){ 
		?>
		<div class="rzvy-staff-container mt-5">
			<div class="step-item">
				<h4 class="<?php echo $inputAlignment; ?>"><?php if(isset($rzvy_translangArr['choose_staff_member'])){ echo $rzvy_translangArr['choose_staff_member']; }else{ echo $rzvy_defaultlang['choose_staff_member']; } ?></h4>
					<?php if($servicelinkedstaffs>1){
						$rzvy_staffs_time_today = $obj_settings->get_option('rzvy_staffs_time_today');
						$rzvy_staffs_time_tomorrow = $obj_settings->get_option('rzvy_staffs_time_tomorrow');
						
						$rzvy_colmdclass = '12';
						if($rzvy_staffs_time_today=='Y' && $rzvy_staffs_time_tomorrow=='Y'){
							$rzvy_colmdclass = '6';
						}
						if($rzvy_staffs_time_today=='Y'){
						?>
					<div class="row">				
						<div class="col-md-<?php echo $rzvy_colmdclass;?> py-2">
							<div class="card rzvy_card_common  <?php echo $inputAlignment; ?> rzvy-staff-change rzvy-staff-change-tt" id="rzvy-staff-change-id-today" data-id="today" data-sdate="<?php echo date('Y-m-d');?>">
								<div class="card-body p-1 <?php echo $insideContentAlignment; ?>">
									<h5 class="card-text pb-3 pt-3"><?php if(isset($rzvy_translangArr['available_today'])){ echo $rzvy_translangArr['available_today']; }else{ echo $rzvy_defaultlang['available_today']; } ?></h5>
								</div>
							</div>
						</div>
				<?php
					if($rzvy_staffs_time_tomorrow!='Y'){ ?>
					</div>	
					<?php }
					}
					if($rzvy_staffs_time_tomorrow=='Y'){
						if($rzvy_staffs_time_today!='Y'){ ?>
						<div class="row">
						<?php } ?>
							<div class="col-md-<?php echo $rzvy_colmdclass;?> py-2">
								<div class="card rzvy_card_common  <?php echo $inputAlignment; ?> rzvy-staff-change rzvy-staff-change-tt" id="rzvy-staff-change-id-tomorrow" data-id="tomorrow" data-sdate="<?php echo date('Y-m-d',strtotime('+1 days'));?>">
									<div class="card-body p-1 <?php echo $insideContentAlignment; ?>">
										<h5 class="card-text pb-3 pt-3"><?php if(isset($rzvy_translangArr['available_tomorrow'])){ echo $rzvy_translangArr['available_tomorrow']; }else{ echo $rzvy_defaultlang['available_tomorrow']; } ?></h5>
									</div>
								</div>
							</div>
						</div>	
					<?php } 			
				} ?>
				<div class="services team <?php echo $alignmentClass; ?> <?php echo $rzvyrounded;?>">
					<div class="owl-carousel" data-items="4" data-items-lg="4" data-items-md="3" data-items-sm="2" data-items-ssm="1" data-margin="24" data-nav="true" data-dots="true" autoplay="false" data-loop="false">
		
				<?php 
					while($staffid = mysqli_fetch_array($getall_service_staffid)){ 
						$staffdata = $obj_frontend->get_staff($staffid["staff_id"]);
						$staff_ratinginfo = $obj_frontend->get_staff_rating($staffid["staff_id"]);
						$staff_rating = $staff_ratinginfo['average_review'];
						$totalstaff_rating = $staff_ratinginfo['number_of_reviews'];
						$job_completed = $obj_frontend->get_staff_job_completed($staffid["staff_id"]);
						
						$obj_settings->staff_id = $staffid["staff_id"];
						$show_ratings_on_booking_form = $obj_settings->get_staff_option("show_ratings_on_booking_form");
						$show_completed_jobs_on_booking_form = $obj_settings->get_staff_option("show_completed_jobs_on_booking_form");
						$show_email_on_booking_form = $obj_settings->get_staff_option("show_email_on_booking_form");
						$show_phone_on_booking_form = $obj_settings->get_staff_option("show_phone_on_booking_form");
						
						$reviewlabel = '';
						if(isset($rzvy_translangArr['reviews'])){ $reviewlabel =  $rzvy_translangArr['reviews']; }else{ $reviewlabel =  $rzvy_defaultlang['reviews']; }
				
						if($show_ratings_on_booking_form == "Y"){
							$ratings = "";
							$ratingsinfo = "";
							if($staff_rating>0){
								$filledcounter = 0;
								for($star_i=0;$star_i<$staff_rating;$star_i++){ 
									if($staff_rating <= $star_i.'.5' && round($staff_rating)>$star_i){
										$ratings .= '<i class="fa fa-star-half-o" aria-hidden="true"></i>';
									}else{
										$ratings .= '<i class="fa fa-star" aria-hidden="true"></i>';
									}	
									$filledcounter++;
								} 
								for($star_j=0;$star_j<(5-$filledcounter);$star_j++){ 
									$ratings .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
								} 
							}else{ 
								$ratings .= '<i class="fa fa-star-o" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i> <i class="fa fa-star-o" aria-hidden="true"></i>';
							} 
							$ratingsinfo .= '<p class="rzvy_ratinginfo"><strong>'.$staff_rating.'</strong><span> ('.$totalstaff_rating.' '.$reviewlabel.')</span></p>';
						} 
						?>
						<div class="item">
						  <figure>
							<?php $rzvyptclass = ''; if($rzvy_show_staff_image == "Y"){ ?><img src="<?php if($staffdata['image'] != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$staffdata['image'])){ echo SITE_URL."uploads/images/".$staffdata['image']; }else{ echo SITE_URL."includes/images/noimage.png"; } ?>" ><?php }else{ $rzvyptclass = 'rzvy-pt-figcaption'; } ?>
							<figcaption class="<?php echo $rzvyptclass; ?>">
							  <h3><?php echo ucwords($staffdata["firstname"]." ".$staffdata["lastname"]); ?></h3>
							  <?php if($show_ratings_on_booking_form == "Y"){ ?><div class="rating">
								<?php echo $ratings; ?>
								<?php echo $ratingsinfo; ?>
							  </div><?php } ?>
							  <div class="service-meta">
								<?php if($show_completed_jobs_on_booking_form == "Y"){ ?><span><i class="fa fa-fw fa-check-square-o" aria-hidden="true"></i> <?php if(isset($rzvy_translangArr['completed_jobs'])){ echo $rzvy_translangArr['completed_jobs']; }else{ echo $rzvy_defaultlang['completed_jobs']; } ?> : <?php echo $job_completed; ?></span><?php } ?>
								<?php if($show_email_on_booking_form == "Y"){ ?><span><i class="fa fa-fw fa-envelope-o" aria-hidden="true"></i> <a href="mailto:<?php echo $staffdata["email"]; ?>"><?php echo $staffdata["email"]; ?></a></span><?php } ?>	
								<?php if($show_phone_on_booking_form == "Y"){ ?><span><i class="fa fa-fw fa-phone"></i> <?php echo $staffdata["phone"]; ?></span><?php } ?>
							  </div>
							  <input type="radio" name="rzvy-staff-radio" class="<?php echo $inputAlignment; ?> rzvy-staff-change" id="rzvy-staff-change-id-<?php echo $staffdata["id"]; ?>" data-id="<?php echo $staffdata["id"]; ?>">
							</figcaption>
						  </figure>
						</div>
						<?php 
					}
					?>
			</div>
		</div>
	</div>
		<?php
	}
	?>
	<?php if($rzvy_book_with_datetime=="Y"){ ?>
	<div class="rzvy-calendar-slots-container mt-5">
		
	</div>
	<?php } ?>
	<div class="row py-2 mt-4">
		<div class="col-md-12">
			<div class="rzvy-steps-btn">
					<a href="javascript:void(0)" id="rzvy-get-first-next-box-btn" class="btn btn-success next-step next-button rzvy_previousstep_btn"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['prev'])){ echo $rzvy_translangArr['prev']; }else{ echo $rzvy_defaultlang['prev']; } ?></a>
					<a href="javascript:void(0)" id="rzvy-get-third-next-box-btn" class="btn btn-success next-step next-button rzvy_nextstep_btn pull-right"><?php if(isset($rzvy_translangArr['next'])){ echo $rzvy_translangArr['next']; }else{ echo $rzvy_defaultlang['next']; } ?>&nbsp;&nbsp; <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
			</div>
		</div>
	</div>
	<?php 
}

/* on change update frequently discount */
else if(isset($_POST['update_frequently_discount'])){
	$obj_frontend->frequently_discount_id = $_POST["id"];
	$fd_discount = $obj_frontend->readone_frequently_discount(); 
	if(is_array($fd_discount)){
		$_SESSION['rzvy_cart_freqdiscount_id'] = $_POST["id"];
		$_SESSION['rzvy_cart_freqdiscount_label'] = $fd_discount["fd_label"];
		$_SESSION['rzvy_cart_freqdiscount_key'] = $fd_discount["fd_key"];
	}
}

/** Set staff id in session on selection */
else if(isset($_POST["set_staff_according_service"])){ 
	$_SESSION['rzvy_staff_id'] = $_POST["id"];
	if($rzvy_book_with_datetime=="Y"){
		?>
		<h4 <?php echo $labelAlignmentClass; ?> for="rzvy-custom-radio-main"><?php if(isset($rzvy_translangArr['choose_your_appointment_slot'])){ echo $rzvy_translangArr['choose_your_appointment_slot']; }else{ echo $rzvy_defaultlang['choose_your_appointment_slot']; } ?></h4>
		<div class="row">
			<div class="rzvy-inline-calendar mt-4 col-md-8 mx-auto">
				<div class="rzvy-inline-calendar-container rzvy-inline-calendar-container-boxshadow">
					<?php 
					include "stepview_calendar.php"; 
					?>
				</div>
			</div>
		</div>
		<div class="col-md-8 pt-3 rzvy-calendar-slots mt-4 mx-auto whitebox">
			<?php /* <div class="row py-3 px-3"> 
				<label class="col-md-12 pt-2" for="rzvy_start_slot"><?php if(isset($rzvy_translangArr['choose_your_slot'])){ echo $rzvy_translangArr['choose_your_slot']; }else{ echo $rzvy_defaultlang['choose_your_slot']; } ?></label>
				<select id="rzvy_start_slot" class="col-md-12 form-control selectpicker" title="<?php if(isset($rzvy_translangArr['choose_your_slot'])){ echo $rzvy_translangArr['choose_your_slot']; }else{ echo $rzvy_defaultlang['choose_your_slot']; } ?>"></select>
			</div>
			<div class="row py-3 px-3 rzvy_end_slot_div" style="display:none;">
				<label class="col-md-12 pt-2" for="rzvy_end_slot"><?php if(isset($rzvy_translangArr['choose_your_end_slot'])){ echo $rzvy_translangArr['choose_your_end_slot']; }else{ echo $rzvy_defaultlang['choose_your_end_slot']; } ?></label>
				<select id="rzvy_end_slot" class="col-md-12 form-control selectpicker" title="<?php if(isset($rzvy_translangArr['choose_your_end_slot'])){ echo $rzvy_translangArr['choose_your_end_slot']; }else{ echo $rzvy_defaultlang['choose_your_end_slot']; } ?>"></select>
			</div> */ ?>
		</div>
		</div>
		<?php 
	}
}

/* Get available slots ajax */
else if(isset($_POST['get_slots'])){ 
	$rzvy_settings_timezone = $obj_settings->get_option("rzvy_timezone");
	$rzvy_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($rzvy_server_timezone,$rzvy_settings_timezone); 

	$selected_date = date("Y-m-d", strtotime($_POST['selected_date']));
	$selected_date = date($selected_date, $currDateTime_withTZ);
	
	$isEndTime = false;
	$available_slots = $obj_slots->generate_available_slots_dropdown($time_interval, $rzvy_time_format, $selected_date, $advance_bookingtime, $currDateTime_withTZ, $isEndTime, $_SESSION['rzvy_cart_service_id'], $_SESSION['rzvy_cart_total_addon_duration']);
	
	$no_booking = $available_slots['no_booking'];
	if($available_slots['no_booking']<0){
		$no_booking = 0;
	}
	$rzvy_hide_already_booked_slots_from_frontend_calendar = $obj_settings->get_option('rzvy_hide_already_booked_slots_from_frontend_calendar');
	$rzvy_minimum_advance_booking_time = $obj_settings->get_option('rzvy_minimum_advance_booking_time');
	$rzvy_maximum_advance_booking_time = $obj_settings->get_option('rzvy_maximum_advance_booking_time');

	/** check for maximum advance booking time **/
	$current_datetime = strtotime(date("Y-m-d H:i:s", $currDateTime_withTZ));
	$maximum_date = date("Y-m-d", strtotime('+'.$rzvy_maximum_advance_booking_time.' months', $current_datetime));
	$maximum_date = date($maximum_date, $currDateTime_withTZ);

	/** check for minimum advance booking time **/
	$minimum_date = date("Y-m-d H:i:s", strtotime("+".$rzvy_minimum_advance_booking_time." minutes", $current_datetime));  

	/** check for GC bookings START **/
	$gc_twowaysync_eventsArr = array();
	$rzvy_gc_status = $obj_settings->get_option('rzvy_gc_status');
	$rzvy_gc_twowaysync = $obj_settings->get_option('rzvy_gc_twowaysync');
	$rzvy_gc_clientid = $obj_settings->get_option('rzvy_gc_clientid');
	$rzvy_gc_clientsecret = $obj_settings->get_option('rzvy_gc_clientsecret');
	$rzvy_gc_accesstoken = $obj_settings->get_option('rzvy_gc_accesstoken');
	$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
	
	if($rzvy_gc_status == "Y" && $rzvy_gc_twowaysync == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != ""){
		$getNewTime = new \DateTime('now', new DateTimeZone($rzvy_settings_timezone));
		$timezoneOffset = $getNewTime->format('P');
		
		include(dirname(dirname(dirname(__FILE__)))."/includes/google-calendar/vendor/autoload.php");
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
			$obj_settings->update_option('rzvy_gc_accesstoken',base64_encode(serialize($newAccessToken)));
		}
		$service = new Google_Service_Calendar($client);

		$calendarId = (($obj_settings->get_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_option('rzvy_gc_calendarid'):'primary');
		$optParams = array(
		  'orderBy' => 'startTime',
		  'singleEvents' => true,
		  'timeZone' => $rzvy_settings_timezone,
		  'timeMin' => $selected_date.'T00:00:00'.$timezoneOffset,
		  'timeMax' => $selected_date.'T23:59:59'.$timezoneOffset,
		);
		$results = $service->events->listEvents($calendarId, $optParams);
		$events = $results->getItems();

		if (!empty($events)) {
			foreach ($events as $event) {
				if(!isset($event->transparency) || (isset($event->transparency) && $event->transparency!='transparent')){			
					$EventStartTime = substr($event->start->dateTime, 0, 19);
					$EventEndTime = substr($event->end->dateTime, 0, 19);
					$gcEventArr = array();
					$gcEventArr['start'] = date("Y-m-d H:i", strtotime(str_replace("T"," ",$EventStartTime)));
					$gcEventArr['end'] = date("Y-m-d H:i", strtotime(str_replace("T"," ",$EventEndTime)));
					array_push($gc_twowaysync_eventsArr, $gcEventArr);
				}
			}
		}
	}
	/** check for GC bookings END **/
	
	/** check for staff GC bookings START **/
	if($_SESSION['rzvy_staff_id']>0){
		$obj_settings->staff_id = $_SESSION['rzvy_staff_id'];
		$rzvy_gc_status = $obj_settings->get_staff_option('rzvy_gc_status');
		$rzvy_gc_twowaysync = $obj_settings->get_staff_option('rzvy_gc_twowaysync');
		$rzvy_gc_accesstoken = $obj_settings->get_staff_option('rzvy_gc_accesstoken');
		$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
		
		if($rzvy_gc_status == "Y" && $rzvy_gc_twowaysync == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != ""){
			$getNewTime = new \DateTime('now', new DateTimeZone($rzvy_settings_timezone));
			$timezoneOffset = $getNewTime->format('P');
			
			include(dirname(dirname(dirname(__FILE__)))."/includes/google-calendar/vendor/autoload.php");
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

			$calendarId = (($obj_settings->get_staff_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_staff_option('rzvy_gc_calendarid'):'primary');
			$optParams = array(
			  'orderBy' => 'startTime',
			  'singleEvents' => true,
			  'timeZone' => $rzvy_settings_timezone,
			  'timeMin' => $selected_date.'T00:00:00'.$timezoneOffset,
			  'timeMax' => $selected_date.'T23:59:59'.$timezoneOffset,
			);
			$results = $service->events->listEvents($calendarId, $optParams);
			$events = $results->getItems();

			if (!empty($events)) {
				foreach ($events as $event) {
					if(!isset($event->transparency) || (isset($event->transparency) && $event->transparency!='transparent')){			
						$EventStartTime = substr($event->start->dateTime, 0, 19);
						$EventEndTime = substr($event->end->dateTime, 0, 19);
						$gcEventArr = array();
						$gcEventArr['start'] = date("Y-m-d H:i", strtotime(str_replace("T"," ",$EventStartTime)));
						$gcEventArr['end'] = date("Y-m-d H:i", strtotime(str_replace("T"," ",$EventEndTime)));
						array_push($gc_twowaysync_eventsArr, $gcEventArr);
					}
				}
			}
		}
	}
	
	?>
	<div class="pt-1 pb-1 pl-4 pr-4">
		<div class="row">
			<div class="col-md-12 rzvy-sm-box mb-2 text-center pt-3">
				<h6><i class="fa fa-calendar"></i> <?php echo date($rzvy_date_format, strtotime($selected_date)); ?></h6>
			</div>
		</div>
		<div class="row slot_refresh_div">
			<?php 
			/** maximum date check **/		
			if(strtotime($selected_date)>strtotime($maximum_date)){ 
				?>
				<div class="col-md-12 rzvy-sm-box rzvy_slot_new mb-2 text-center pt-3">
					<h6><?php if(isset($rzvy_translangArr['you_cannot_book_appointment_on'])){ echo $rzvy_translangArr['you_cannot_book_appointment_on']; }else{ echo $rzvy_defaultlang['you_cannot_book_appointment_on']; } ?> <?php echo date($rzvy_date_format, strtotime($selected_date)); ?>. <?php if(isset($rzvy_translangArr['our_maximum_advance_booking_period_is'])){ echo $rzvy_translangArr['our_maximum_advance_booking_period_is']; }else{ echo $rzvy_defaultlang['our_maximum_advance_booking_period_is']; } ?> <?php 
						if($rzvy_maximum_advance_booking_time == "1"){ echo "1 Month"; }
						else if($rzvy_maximum_advance_booking_time == "3"){ echo "3 Month"; }
						else if($rzvy_maximum_advance_booking_time == "6"){ echo "6 Month"; }
						else if($rzvy_maximum_advance_booking_time == "9"){ echo "9 Month"; }
						else if($rzvy_maximum_advance_booking_time == "12"){ echo "1 Year"; }
						else if($rzvy_maximum_advance_booking_time == "18"){ echo "1.5 Year"; }
						else if($rzvy_maximum_advance_booking_time == "24"){ echo "2 Year"; } 
					?>. <?php if(isset($rzvy_translangArr['so_you_can_book_appointment_till'])){ echo $rzvy_translangArr['so_you_can_book_appointment_till']; }else{ echo $rzvy_defaultlang['so_you_can_book_appointment_till']; } ?> <?php echo $maximum_date; ?></h6>
				</div>
				<?php 
			}
			/** time slots **/
			else if(isset($available_slots['slots']) && sizeof($available_slots['slots'])>0){
				$i = 1;
				$j = 0;
				foreach($available_slots['slots'] as $slot){
					$no_curr_boookings = $obj_slots->get_slot_bookings($selected_date." ".$slot,$_SESSION['rzvy_cart_service_id']);
					$bookings_blocks = $obj_slots->get_bookings_blocks($selected_date, $slot, $available_slots["serviceaddonduration"]);
					if(strtotime($selected_date." ".$slot)<strtotime($minimum_date)){
						continue;
					}else if(!$bookings_blocks){
						continue;
					}else{
						$booked_slot_exist = false;
						foreach($gc_twowaysync_eventsArr as $event){
							if(strtotime($event["start"]) <= strtotime($selected_date." ".$slot) && strtotime($event["end"]) > strtotime($selected_date." ".$slot)){
								$no_curr_boookings += 1;
							}
							if(strtotime($event["start"]) <= strtotime($selected_date." ".$slot) && strtotime($event["end"]) > strtotime($selected_date." ".$slot) && $no_booking==0){
								$booked_slot_exist = true;
								continue;
							} 
							if(strtotime($event["start"]) <= strtotime($selected_date." ".$slot) && strtotime($event["end"]) > strtotime($selected_date." ".$slot) && $no_booking!=0 && $no_curr_boookings>=$no_booking){
								$booked_slot_exist = true;
								continue;
							} 
						}
							
						$new_endtime_timestamp = strtotime("+".$available_slots["serv_timeinterval"]." minutes", strtotime($selected_date." ".$slot));
						$new_starttime_timestamp = strtotime($selected_date." ".$slot);
											
						foreach($available_slots['booked'] as $bslot){
							if($bslot["start_time"] <= strtotime($selected_date." ".$slot) && $bslot["end_time"] > strtotime($selected_date." ".$slot) && $no_booking==0){
								$booked_slot_exist = true;
								continue;
							} 
							if($bslot["start_time"] <= strtotime($selected_date." ".$slot) && $bslot["end_time"] > strtotime($selected_date." ".$slot) && $no_booking!=0 && $no_curr_boookings>=$no_booking){
								$booked_slot_exist = true;
								continue;
							} 
							
							if($new_starttime_timestamp <= $bslot["start_time"] && $new_endtime_timestamp > $bslot["start_time"] && $no_booking==0){
								$booked_slot_exist = true;
								continue;
							}
							
							if($new_starttime_timestamp <= $bslot["start_time"] && $new_endtime_timestamp > $bslot["start_time"] && $no_booking!=0){
							    $no_curr_boookings = $no_curr_boookings+1;
							    if($no_curr_boookings>=$no_booking){
    								$booked_slot_exist = true;
    								continue;
							    }
							} 
							if($new_starttime_timestamp < $bslot["end_time"] && $new_endtime_timestamp > $bslot["end_time"] && $no_booking==0){
								$booked_slot_exist = true;
								continue;
							}
							
							if($new_starttime_timestamp < $bslot["end_time"] && $new_endtime_timestamp > $bslot["end_time"] && $no_booking!=0){
							    $no_curr_boookings = $no_curr_boookings+1;
							    if($no_curr_boookings>=$no_booking){
    								$booked_slot_exist = true;
    								continue;
							    }
							} 
						}
						
						if($booked_slot_exist && $rzvy_hide_already_booked_slots_from_frontend_calendar == "Y"){
							continue;
						}else if($booked_slot_exist && $rzvy_hide_already_booked_slots_from_frontend_calendar == "N" && $no_booking==0){ 
							$blockoff_exist = false;
							if(sizeof($available_slots['block_off'])>0){
								foreach($available_slots['block_off'] as $block_off){
									if((strtotime($selected_date." ".$block_off["start_time"]) <= strtotime($selected_date." ".$slot)) && (strtotime($selected_date." ".$block_off["end_time"]) > strtotime($selected_date." ".$slot))){
										$blockoff_exist = true;
										continue;
									} 
								}
							} 
							if($blockoff_exist){
								continue;
							} 
							?>
							<div class="col-md-3 rzvy-sm-box rzvy_slot_new mb-2">
								<div class="rzvy-styled-radio-second rzvy-styled-radio-disable form-check custom">
									<input type="radio" id="rzvy-booked-time-slot-<?php echo $i; ?>" name="rzvy-booked-time-slots" class="rzvy-styled-radio-disable"  disabled>
									<label for="rzvy-booked-time-slot-<?php echo $i; ?>" disabled><?php echo date($rzvy_time_format,strtotime($selected_date." ".$slot)); ?></label>
								</div>
							</div>
							<?php 
							$j++;
						}else if($booked_slot_exist && $rzvy_hide_already_booked_slots_from_frontend_calendar == "N" && $no_booking!=0 && $no_curr_boookings>=$no_booking){ 
							$blockoff_exist = false;
							if(sizeof($available_slots['block_off'])>0){
								foreach($available_slots['block_off'] as $block_off){
									if((strtotime($selected_date." ".$block_off["start_time"]) <= strtotime($selected_date." ".$slot)) && (strtotime($selected_date." ".$block_off["end_time"]) > strtotime($selected_date." ".$slot))){
										$blockoff_exist = true;
										continue;
									} 
								}
							} 
							if($blockoff_exist){
								continue;
							} 
							?>
							<div class="col-md-3 rzvy-sm-box rzvy_slot_new mb-2">
								<div class="rzvy-styled-radio-second rzvy-styled-radio-disable form-check custom">
									<input type="radio" id="rzvy-booked-time-slot-<?php echo $i; ?>" name="rzvy-booked-time-slots" class="rzvy-styled-radio-disable" disabled>
									<label for="rzvy-booked-time-slot-<?php echo $i; ?>" disabled><?php echo date($rzvy_time_format,strtotime($selected_date." ".$slot)); ?></label>
								</div>
							</div>
							<?php 
							$j++;
						}else{ 
							$blockoff_exist = false;
							if(sizeof($available_slots['block_off'])>0){
								foreach($available_slots['block_off'] as $block_off){
									if((strtotime($selected_date." ".$block_off["start_time"]) <= strtotime($selected_date." ".$slot)) && (strtotime($selected_date." ".$block_off["end_time"]) > strtotime($selected_date." ".$slot))){
										$blockoff_exist = true;
										continue;
									} 
								}
							} 
							if($blockoff_exist){
								continue;
							} 
							?>
							<div class="col-md-3 rzvy-sm-box rzvy_slot_new mb-2">
								<div class="rzvy-styled-radio rzvy-styled-radio-second form-check custom">
									<input type="radio" class="rzvy_time_slots_selection" id="rzvy-time-slot-<?php echo $i; ?>" name="rzvy-time-slots" value="<?php echo $slot; ?>">
									<label for="rzvy-time-slot-<?php echo $i; ?>"><?php echo date($rzvy_time_format, strtotime($selected_date." ".$slot)); ?></label>
								</div>
							</div>
							<?php 
							$j++;
						}
						$i++;
					}
				}
				if($j == 0){ 
					?>
					<div class="col-md-12 rzvy-sm-box rzvy_slot_new col-md-12 rzvy-sm-box mb-2 text-center pt-3">
						<h6><?php if(isset($rzvy_translangArr['none_of_slots_available_on'])){ echo $rzvy_translangArr['none_of_slots_available_on']; }else{ echo $rzvy_defaultlang['none_of_slots_available_on']; } ?> <?php echo date($rzvy_date_format, strtotime($selected_date)); ?></h6>
					</div>
					<?php 
				}
			}else{ 
				?>
				<div class="col-md-12 rzvy-sm-box rzvy_slot_new col-md-12 rzvy-sm-box mb-2 text-center pt-3">
					<h6><?php if(isset($rzvy_translangArr['none_of_slots_available_on'])){ echo $rzvy_translangArr['none_of_slots_available_on']; }else{ echo $rzvy_defaultlang['none_of_slots_available_on']; } ?> <?php echo date($rzvy_date_format, strtotime($selected_date)); ?></h6>
				</div>
				<?php 
			} 
			?>
		</div>
	</div>
	<?php 
}

/* Endtime available slots ajax */
else if(isset($_POST['get_endtime_slots'])){ 
	$rzvy_settings_timezone = $obj_settings->get_option("rzvy_timezone");
	$rzvy_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($rzvy_server_timezone,$rzvy_settings_timezone); 

	$selected_date = date("Y-m-d", strtotime($_POST['selected_date']));
	$selected_date = date($selected_date, $currDateTime_withTZ);

	$isEndTime = true;
	$available_slots = $obj_slots->generate_available_slots_dropdown($time_interval, $rzvy_time_format, $selected_date, $advance_bookingtime, $currDateTime_withTZ, $isEndTime, $_SESSION['rzvy_cart_service_id'], $_SESSION['rzvy_cart_total_addon_duration']);
	
	$no_booking = $available_slots['no_booking'];
	if($available_slots['no_booking']<0){
		$no_booking = 0;
	}
	
	$rzvy_hide_already_booked_slots_from_frontend_calendar = $obj_settings->get_option('rzvy_hide_already_booked_slots_from_frontend_calendar');
	$rzvy_minimum_advance_booking_time = $obj_settings->get_option('rzvy_minimum_advance_booking_time');
	$rzvy_maximum_advance_booking_time = $obj_settings->get_option('rzvy_maximum_advance_booking_time');
	
	/** check for maximum advance booking time **/
	$current_datetime = strtotime(date("Y-m-d H:i:s", $currDateTime_withTZ));
	$maximum_date = date("Y-m-d", strtotime('+'.$rzvy_maximum_advance_booking_time.' months', $current_datetime));
	$maximum_date = date($maximum_date, $currDateTime_withTZ);

	/** check for minimum advance booking time **/
	$minimum_date = date("Y-m-d H:i:s", strtotime("+".$rzvy_minimum_advance_booking_time." minutes", $current_datetime));  
	
	/** check for maximum end time slot limit **/
	$rzvy_maximum_endtimeslot_limit = $obj_settings->get_option('rzvy_maximum_endtimeslot_limit');
	$selected_slot_check = strtotime($selected_date." ".$_POST["selected_slot"]);
	$maximum_endslot_limit = date("Y-m-d H:i:s", strtotime("+".$rzvy_maximum_endtimeslot_limit." minutes", $selected_slot_check));  
	
	/** maximum date check **/		
	if(strtotime($selected_date)>strtotime($maximum_date)){ }
	/** time slots **/
	else if(isset($available_slots['slots']) && sizeof($available_slots['slots'])>0){
		$i = 1;
		$j = 0;
		foreach($available_slots['slots'] as $slot){
			if(strtotime($selected_date." ".$slot)<strtotime($minimum_date)){
				continue;
			}else{
				if(strtotime($selected_date." ".$slot) <= strtotime($selected_date." ".$_POST["selected_slot"])){
					continue;
				}elseif(strtotime($selected_date." ".$slot) > strtotime($maximum_endslot_limit)){
					continue;
				}else{
					$booked_slot_exist = false;
					foreach($available_slots['booked'] as $bslot){
						if($bslot["start_time"] <= strtotime($selected_date." ".$slot) && $bslot["end_time"] > strtotime($selected_date." ".$slot)){
							$booked_slot_exist = true;
							continue;
						} 
					}
					if($booked_slot_exist){
						break;
					}else{ 
						$blockoff_exist = false;
						if(sizeof($available_slots['block_off'])>0){
							foreach($available_slots['block_off'] as $block_off){
								if((strtotime($selected_date." ".$block_off["start_time"]) <= strtotime($selected_date." ".$slot)) && (strtotime($selected_date." ".$block_off["end_time"]) > strtotime($selected_date." ".$slot))){
									$blockoff_exist = true;
									continue;
								} 
							}
						} 
						$no_curr_boookings = $obj_slots->get_slot_bookings($selected_date." ".$slot,$_SESSION['rzvy_cart_service_id']);
						if($no_booking!=0 && $no_curr_boookings>=$no_booking){
							continue;
						}
						if($blockoff_exist){
							break;
						} 
						?>
						<option class="rzvy_endtime_slots_selection" value="<?php echo $slot; ?>"><?php echo date($rzvy_time_format,strtotime($selected_date." ".$slot)); ?></option>
						<?php 
						$j++;
					}
				}
				$i++;
			}
		}
		if($j == 0){ 
			if(is_numeric($_SESSION['rzvy_cart_service_id']) && $_SESSION['rzvy_cart_service_id'] != "0"){
				$time_interval=$obj_slots->get_service_time_interval($_SESSION['rzvy_cart_service_id'],$time_interval);
			}
			$sdate_stime = strtotime($selected_date." ".$_POST["selected_slot"]);
			$sdate_etime = date("Y-m-d H:i:s", strtotime("+".$time_interval." minutes", $sdate_stime));
			$sdate_estime = date("H:i:s", strtotime($sdate_etime));
			$no_curr_boookings = $obj_slots->get_slot_bookings($sdate_etime,$_SESSION['rzvy_cart_service_id']);
			if($no_booking!=0 && $no_curr_boookings>=$no_booking){ }else{
				?>
				<option class="rzvy_endtime_slots_selection" value="<?php echo $sdate_estime; ?>"><?php echo date($rzvy_time_format,strtotime($sdate_etime)); ?></option>
				<?php 
			}
		}
	}else{ } 
}

/* Add selected slot to session ajax */
else if(isset($_POST['add_selected_slot'])){ 
	$selected_startdatetime = date("Y-m-d H:i:s", strtotime($_POST['selected_date']." ".$_POST['selected_startslot']));
	$selected_enddatetime = date("Y-m-d H:i:s", strtotime($_POST['selected_date']." ".$_POST['selected_endslot']));
	$_SESSION['rzvy_cart_datetime'] = $selected_startdatetime;
	$_SESSION['rzvy_cart_end_datetime'] = $selected_enddatetime;
	
	$rzvy_cart_date = date($rzvy_date_format, strtotime($_SESSION['rzvy_cart_datetime'])); 
	$rzvy_cart_starttime = date($rzvy_time_format, strtotime($_SESSION['rzvy_cart_datetime'])); 
	$rzvy_cart_endtime = date($rzvy_time_format, strtotime($_SESSION['rzvy_cart_end_datetime'])); 
}

/* Add selected slot to session ajax */
else if(isset($_POST['add_selected_slot_withendslot'])){ 
	if(is_numeric($_SESSION['rzvy_cart_service_id']) && $_SESSION['rzvy_cart_service_id'] != "0"){
		$time_interval=$obj_slots->get_service_time_interval($_SESSION['rzvy_cart_service_id'],$time_interval);
		if($_SESSION['rzvy_cart_total_addon_duration']>0){
			$time_interval = ($time_interval+$_SESSION['rzvy_cart_total_addon_duration']);
		}
	}
	if($_POST["cal_selection"] == "Y"){
		$selected_startdatetime = date("Y-m-d H:i:s", strtotime($_POST['selected_date']." ".$_POST['selected_startslot']));
	}else{
		$selected_startdatetime = date("Y-m-d H:i:s", strtotime($_SESSION['rzvy_cart_datetime']));
	}
	
	$selected_enddatetime = date("Y-m-d H:i:s", strtotime("+".$time_interval." minutes", strtotime($selected_startdatetime)));
	if($_POST["cal_selection"] == "Y"){
		$_SESSION['rzvy_cart_datetime'] = $selected_startdatetime;
	}
	$_SESSION['rzvy_cart_end_datetime'] = $selected_enddatetime;
	
	$rzvy_cart_date = date($rzvy_date_format, strtotime($_SESSION['rzvy_cart_datetime'])); 
	$rzvy_cart_starttime = date($rzvy_time_format, strtotime($_SESSION['rzvy_cart_datetime'])); 
	$rzvy_cart_endtime = date($rzvy_time_format, strtotime($_SESSION['rzvy_cart_end_datetime'])); 
}

/** Get Third box having booking summary **/
else if(isset($_POST['get_third_step_box'])){ 
	$obj_frontend->category_id = $_SESSION['rzvy_cart_category_id'];
	$obj_frontend->service_id = $_SESSION['rzvy_cart_service_id'];
	$category_name = $obj_frontend->readone_category_name();
	$servicedata = $obj_frontend->readone_service(); 
	$service_name = $servicedata['title']; 
	$service_rate = $servicedata['rate'];  
	?>
	<div class="my-2">
		<?php 
		/** get form fields options **/
		$rzvy_en_ff_firstname_status = $obj_settings->get_option('rzvy_en_ff_firstname_status');
		$rzvy_en_ff_lastname_status = $obj_settings->get_option('rzvy_en_ff_lastname_status');
		$rzvy_en_ff_phone_status = $obj_settings->get_option('rzvy_en_ff_phone_status');
		$rzvy_en_ff_address_status = $obj_settings->get_option('rzvy_en_ff_address_status');
		$rzvy_en_ff_city_status = $obj_settings->get_option('rzvy_en_ff_city_status');
		$rzvy_en_ff_state_status = $obj_settings->get_option('rzvy_en_ff_state_status');
		$rzvy_en_ff_country_status = $obj_settings->get_option('rzvy_en_ff_country_status');
		$rzvy_en_ff_dob_status = $obj_settings->get_option('rzvy_en_ff_dob_status');
		$rzvy_en_ff_notes_status = $obj_settings->get_option('rzvy_en_ff_notes_status');

		$rzvy_g_ff_firstname_status = $obj_settings->get_option('rzvy_g_ff_firstname_status');
		$rzvy_g_ff_lastname_status = $obj_settings->get_option('rzvy_g_ff_lastname_status');
		$rzvy_g_ff_email_status = $obj_settings->get_option('rzvy_g_ff_email_status');
		$rzvy_g_ff_phone_status = $obj_settings->get_option('rzvy_g_ff_phone_status');
		$rzvy_g_ff_address_status = $obj_settings->get_option('rzvy_g_ff_address_status');
		$rzvy_g_ff_city_status = $obj_settings->get_option('rzvy_g_ff_city_status');
		$rzvy_g_ff_state_status = $obj_settings->get_option('rzvy_g_ff_state_status');
		$rzvy_g_ff_country_status = $obj_settings->get_option('rzvy_g_ff_country_status');
		$rzvy_g_ff_dob_status = $obj_settings->get_option('rzvy_g_ff_dob_status');
		$rzvy_g_ff_notes_status = $obj_settings->get_option('rzvy_g_ff_notes_status');

		/** get form fields required options **/
		/* $rzvy_en_ff_firstname_optional = $obj_settings->get_option('rzvy_en_ff_firstname_optional');
		$rzvy_en_ff_lastname_optional = $obj_settings->get_option('rzvy_en_ff_lastname_optional');
		$rzvy_en_ff_phone_optional = $obj_settings->get_option('rzvy_en_ff_phone_optional');
		$rzvy_en_ff_address_optional = $obj_settings->get_option('rzvy_en_ff_address_optional');
		$rzvy_en_ff_city_optional = $obj_settings->get_option('rzvy_en_ff_city_optional');
		$rzvy_en_ff_state_optional = $obj_settings->get_option('rzvy_en_ff_state_optional');
		$rzvy_en_ff_country_optional = $obj_settings->get_option('rzvy_en_ff_country_optional');
		$rzvy_en_ff_dob_optional = $obj_settings->get_option('rzvy_en_ff_dob_optional');
		$rzvy_en_ff_notes_optional = $obj_settings->get_option('rzvy_en_ff_notes_optional');

		$rzvy_g_ff_firstname_optional = $obj_settings->get_option('rzvy_g_ff_firstname_optional');
		$rzvy_g_ff_lastname_optional = $obj_settings->get_option('rzvy_g_ff_lastname_optional');
		$rzvy_g_ff_email_optional = $obj_settings->get_option('rzvy_g_ff_email_optional');
		$rzvy_g_ff_phone_optional = $obj_settings->get_option('rzvy_g_ff_phone_optional');
		$rzvy_g_ff_address_optional = $obj_settings->get_option('rzvy_g_ff_address_optional');
		$rzvy_g_ff_city_optional = $obj_settings->get_option('rzvy_g_ff_city_optional');
		$rzvy_g_ff_state_optional = $obj_settings->get_option('rzvy_g_ff_state_optional');
		$rzvy_g_ff_country_optional = $obj_settings->get_option('rzvy_g_ff_country_optional'); 
		$rzvy_g_ff_dob_optional = $obj_settings->get_option('rzvy_g_ff_dob_optional'); 
		$rzvy_g_ff_notes_optional = $obj_settings->get_option('rzvy_g_ff_notes_optional');  */

		/** Customer detail **/
		$useremail = "";
		$userpassword = "";
		$userfirstname = "";
		$userlastname = "";
		if(isset($_SESSION['rzvy_location_selector_zipcode'])){
			$userzip = $_SESSION['rzvy_location_selector_zipcode'];
		}else{
			$userzip = "";
		}
		$userphone = "";
		$useraddress = "";
		$usercity = "";
		$userstate = "";
		$usercountry = "";
		$userdob = "";
		if(isset($_SESSION['customer_id'])){
			$obj_frontend->customer_id = $_SESSION['customer_id'];
			$customer_detail = $obj_frontend->readone_customer();
			$useremail = $customer_detail['email'];
			$userpassword = $customer_detail['password'];
			$userfirstname = ucwords($customer_detail['firstname']);
			$userlastname = ucwords($customer_detail['lastname']);
			if(isset($_SESSION['rzvy_location_selector_zipcode'])){
				$userzip = $_SESSION['rzvy_location_selector_zipcode'];
			}else{
				$userzip = "";
			}
			$userphone = $customer_detail['phone'];
			$useraddress = $customer_detail['address'];
			$usercity = $customer_detail['city'];
			$userstate = $customer_detail['state'];
			$usercountry = $customer_detail['country'];
			$userdob = $customer_detail['dob'];
			
		}
		$rzvy_birthdate_with_year = $obj_settings->get_option('rzvy_birthdate_with_year');
		/* check location selector status */
		$rzvy_location_selector_status = $obj_settings->get_option("rzvy_location_selector_status"); 
		if($rzvy_location_selector_status == "N" || $rzvy_location_selector_status == ""){ 
			$show_location_selector = "N";
			$_SESSION['rzvy_location_selector_zipcode'] = "N/A";
		} 
		if(isset($_SESSION["rzvy_location_selector_zipcode"])){
			if($rzvy_location_selector_status == "Y" && ($_SESSION["rzvy_location_selector_zipcode"]=="" && $_SESSION["rzvy_location_selector_zipcode"]!="N/A")){
				$show_location_selector = "Y";
				$_SESSION['rzvy_location_selector_zipcode'] = "";
			}
		}
		?>
		<div class="step-item <?php echo $inputAlignment; ?> mt-5">
			<h4 class="pb-2 <?php echo $inputAlignment; ?>"><?php if(isset($rzvy_translangArr['personal_information'])){ echo $rzvy_translangArr['personal_information']; }else{ echo $rzvy_defaultlang['personal_information']; } ?></h4>
			<div class="rzvy-container pl-0">
			  <div class="custom-controls rzvy-users-selection-div" <?php if(isset($_SESSION['customer_id'])){ echo "style='display:none;'"; } ?>>
				<div class="row justify-content-center">
				  <?php 
					$rzvy_show_existing_new_user_checkout = $obj_settings->get_option("rzvy_show_existing_new_user_checkout");
					$rzvy_show_guest_user_checkout = $obj_settings->get_option("rzvy_show_guest_user_checkout");
					if($rzvy_show_existing_new_user_checkout == "Y"){ 
						?>
						<div class="col-lg-3 col-sm-6">
							<div class="form-check custom inline">
								<input type="radio" class="form-check-input rzvy-user-selection" id="rzvy-existing-user" name="rzvy-user-selection" checked value="ec" data-toggle="rzvy_login_form"/>
								<label class="form-check-label rzvy-user-selection-label" for="rzvy-existing-user"><?php if(isset($rzvy_translangArr['existing_customer'])){ echo $rzvy_translangArr['existing_customer']; }else{ echo $rzvy_defaultlang['existing_customer']; } ?></label>
							</div>
						</div>		
						<div class="col-lg-3 col-sm-6">
							<div class="form-check custom inline">
								<input type="radio" class="form-check-input rzvy-user-selection" id="rzvy-new-user" name="rzvy-user-selection" value="nc" data-toggle="rzvy_new_form"/>
								<label class="form-check-label rzvy-user-selection-label" for="rzvy-new-user"><?php if(isset($rzvy_translangArr['new_customer'])){ echo $rzvy_translangArr['new_customer']; }else{ echo $rzvy_defaultlang['new_customer']; } ?></label>
							</div>
						</div>		
					
					<?php 
					}
					
					if($rzvy_show_guest_user_checkout == "Y"){ 
						$customcss = "";
						if($rzvy_show_existing_new_user_checkout == "N" && $rzvy_show_guest_user_checkout == "Y"){ 
							$customcss = "style='display:none'";
						}
						?>
						<div class="col-lg-3 col-sm-6">
							<div class="form-check custom inline">
								<input <?php echo $customcss; ?> type="radio" class="form-check-input rzvy-user-selection" id="rzvy-guest-user" name="rzvy-user-selection" <?php if($rzvy_show_existing_new_user_checkout == "N"){ echo "checked"; } ?> value="gc" data-toggle="rzvy_guest_form"/>
								<label <?php echo $customcss; ?> class="form-check-label rzvy-user-selection-label" for="rzvy-guest-user"><?php if(isset($rzvy_translangArr['guest_customer'])){ echo $rzvy_translangArr['guest_customer']; }else{ echo $rzvy_defaultlang['guest_customer']; } ?></label>
							</div>
						</div>		
						<?php 
					} 
					
					if($rzvy_show_existing_new_user_checkout == "Y"){ 
						?>
						<div class="col-lg-3 col-sm-6">
							<div class="form-check custom inline">
								<input type="radio" class="form-check-input rzvy-user-selection" id="rzvy-user-forget-password" name="rzvy-user-selection" value="fp" data-toggle="rzvy_password_form"/>
								<label class="form-check-label rzvy-user-selection-label" for="rzvy-user-forget-password"><?php if(isset($rzvy_translangArr['forget_password'])){ echo $rzvy_translangArr['forget_password']; }else{ echo $rzvy_defaultlang['forget_password']; } ?></label>
							</div>
						</div>	
						<?php 
					} 
					?>
				</div>
			  </div>
			  <div class="rzvy-logout-div mt-2" <?php if(isset($_SESSION['customer_id'])){ echo "style='display:block;'"; } ?> >
					<label><?php if(isset($rzvy_translangArr['you_are_logged_in_as'])){ echo $rzvy_translangArr['you_are_logged_in_as']; }else{ echo $rzvy_defaultlang['you_are_logged_in_as']; } ?> <b class="rzvy_loggedin_name"><?php echo $useremail; ?></b>. <a href="javascript:void(0)" id="rzvy_logout_btn"><?php if(isset($rzvy_translangArr['logout'])){ echo $rzvy_translangArr['logout']; }else{ echo $rzvy_defaultlang['logout']; } ?></a></label>
				</div>
			</div>
		</div>	
		<div class="forms">
		<?php 
		if($rzvy_show_existing_new_user_checkout == "Y"){ 
			?>
			<div class="form-item" data-form="rzvy-existing-user">
				<form method="post" name="rzvy_login_form" id="rzvy_login_form">
					<div class="rzvy-radio-group-block mt24  <?php echo $inputAlignment; ?>" <?php if(isset($_SESSION['customer_id'])){ echo "style='display:none;'"; } ?> id="rzvy-existing-user-box">
						<div class="form-group">
							<div class="rzvy-input-class-div">
								<input type="email" name="rzvy_login_email" id="rzvy_login_email" placeholder="<?php if(isset($rzvy_translangArr['email_address'])){ echo $rzvy_translangArr['email_address']; }else{ echo $rzvy_defaultlang['email_address']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
							</div>
						</div>
						<div class="form-group">
							<div class="rzvy-input-class-div">
								<input type="password" name="rzvy_login_password" id="rzvy_login_password" placeholder="<?php if(isset($rzvy_translangArr['password'])){ echo $rzvy_translangArr['password']; }else{ echo $rzvy_defaultlang['password']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
							</div>
						</div>
						<button id="rzvy_login_btn" class="btn btn-success w-100 mt-2" type="submit"><i class="fa fa-lock"></i> &nbsp;&nbsp;<?php if(isset($rzvy_translangArr['login'])){ echo $rzvy_translangArr['login']; }else{ echo $rzvy_defaultlang['login']; } ?></button>
					</div>
				</form>
			</div>
		<div class="form-item" data-form="rzvy-new-user" <?php if(isset($_SESSION['customer_id'])){ echo "style='display:block;'"; } ?>>
			<form method="post" name="rzvy_user_detail_form" id="rzvy_user_detail_form">
				<div class="rzvy-radio-group-block mt24  <?php echo $inputAlignment; ?>" <?php if(isset($_SESSION['customer_id'])){ echo "style='display:block;'"; } ?> id="rzvy-new-user-box">
					<div class="row rzvy_hide_after_login" <?php if(isset($_SESSION['customer_id'])){ echo "style='display:none;'"; } ?>>
						<div class="col-sm-6">
							<div class="form-group">
								<input type="email" id="rzvy_user_email" name="rzvy_user_email" placeholder="<?php if(isset($rzvy_translangArr['email_address'])){ echo $rzvy_translangArr['email_address']; }else{ echo $rzvy_defaultlang['email_address']; } ?>" value="<?php echo $useremail; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<input type="password" id="rzvy_user_password" name="rzvy_user_password" placeholder="<?php if(isset($rzvy_translangArr['password'])){ echo $rzvy_translangArr['password']; }else{ echo $rzvy_defaultlang['password']; } ?>" value="<?php echo $userpassword; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
							</div>
						</div>
					</div>
					<div class="row">
						<?php 
						if($rzvy_en_ff_firstname_status == "Y"){ 
							?>
							<div class="col-sm-6">
								<div class="form-group">
									<input type="text" id="rzvy_user_firstname" name="rzvy_user_firstname" placeholder="<?php if(isset($rzvy_translangArr['first_name'])){ echo $rzvy_translangArr['first_name']; }else{ echo $rzvy_defaultlang['first_name']; } ?>" value="<?php echo $userfirstname; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
								</div>
							</div>
							<?php  
						}else{ 
							?>
							<input type="hidden" id="rzvy_user_firstname" name="rzvy_user_firstname" value="<?php echo $userfirstname; ?>" />
							<?php 
						} 
						
						if($rzvy_en_ff_lastname_status == "Y"){ 
							?>
							<div class="col-sm-6">
								<div class="form-group">
									<input type="text" id="rzvy_user_lastname" name="rzvy_user_lastname" placeholder="<?php if(isset($rzvy_translangArr['last_name'])){ echo $rzvy_translangArr['last_name']; }else{ echo $rzvy_defaultlang['last_name']; } ?>" value="<?php echo $userlastname; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
								</div>
							</div>
							<?php  
						}else{ 
							?>
							<input type="hidden" id="rzvy_user_lastname" name="rzvy_user_lastname" value="<?php echo $userlastname; ?>" />
							<?php 
						} 
						
						if($rzvy_en_ff_phone_status == "Y"){ 
							?>
							<div class="col-sm-6">
								<div class="form-group">
									<input type="text" id="rzvy_user_phone" name="rzvy_user_phone" placeholder="<?php if(isset($rzvy_translangArr['phone_number'])){ echo $rzvy_translangArr['phone_number']; }else{ echo $rzvy_defaultlang['phone_number']; } ?>" value="<?php echo $userphone; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
									<label for="rzvy_user_phone" generated="true" class="error"></label>
								</div>
							</div>
							<?php  
						}else{ 
							?>
							<input type="hidden" id="rzvy_user_phone" name="rzvy_user_phone" value="<?php echo $userphone; ?>" />
							<?php 
						} 
						
						$show_zip_input = "";
						if($rzvy_location_selector_status == "N" || $rzvy_location_selector_status == ""){ 
							$show_zip_input= "rzvy_hide";
						}
						?>
						<div class="col-sm-6 <?php echo $show_zip_input; ?>">
							<div class="form-group">
								<input type="text" id="rzvy_user_zip" name="rzvy_user_zip" placeholder="<?php if(isset($rzvy_translangArr['zip'])){ echo $rzvy_translangArr['zip']; }else{ echo $rzvy_defaultlang['zip']; } ?>" disabled value="<?php echo $userzip; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
							</div>
						</div>
						
						<?php  
						if($rzvy_en_ff_address_status == "Y"){ 
							?>
							<div class="col-sm-12">
								<div class="form-group">
									<input type="text" id="rzvy_user_address" name="rzvy_user_address" placeholder="<?php if(isset($rzvy_translangArr['address'])){ echo $rzvy_translangArr['address']; }else{ echo $rzvy_defaultlang['address']; } ?>" value="<?php echo $useraddress; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
								</div>
							</div>
							<?php  
						}else{ 
							?>
							<input type="hidden" id="rzvy_user_address" name="rzvy_user_address" value="<?php echo $useraddress; ?>" />
							<?php 
						} 
						
						if($rzvy_en_ff_city_status == "Y"){ 
							?>
							<div class="col-sm-6">
								<div class="form-group">
									<input type="text" id="rzvy_user_city" name="rzvy_user_city" placeholder="<?php if(isset($rzvy_translangArr['city'])){ echo $rzvy_translangArr['city']; }else{ echo $rzvy_defaultlang['city']; } ?>" value="<?php echo $usercity; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
								</div>
							</div>
							<?php  
						}else{ 
							?>
							<input type="hidden" id="rzvy_user_city" name="rzvy_user_city" value="<?php echo $usercity; ?>" />
							<?php 
						} 
						
						if($rzvy_en_ff_state_status == "Y"){ 
							?>
							<div class="col-sm-6">
								<div class="form-group">
									<input type="text" id="rzvy_user_state" name="rzvy_user_state" placeholder="<?php if(isset($rzvy_translangArr['state'])){ echo $rzvy_translangArr['state']; }else{ echo $rzvy_defaultlang['state']; } ?>" value="<?php echo $userstate; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
								</div>
							</div>
							<?php  
						}else{ 
							?>
							<input type="hidden" id="rzvy_user_state" name="rzvy_user_state" value="<?php echo $userstate; ?>" />
							<?php 
						} 
						
						if($rzvy_en_ff_country_status == "Y"){ 
							?>
							<div class="col-sm-6">
								<div class="form-group">
									<input type="text" id="rzvy_user_country" name="rzvy_user_country" placeholder="<?php if(isset($rzvy_translangArr['country'])){ echo $rzvy_translangArr['country']; }else{ echo $rzvy_defaultlang['country']; } ?>" value="<?php echo $usercountry; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
								</div>
							</div>
							<?php  
						}else{ 
							?>
							<input type="hidden" id="rzvy_user_country" name="rzvy_user_country" value="<?php echo $usercountry; ?>" />
							<?php 
						} 
						
						if($rzvy_en_ff_dob_status == "Y"){ 
							?>
							<div class="col-sm-6">
								<div class="form-group">
									<input type="text" id="rzvy_user_dob" name="rzvy_user_dob" placeholder="<?php if(isset($rzvy_translangArr['birthdate'])){ echo $rzvy_translangArr['birthdate']; }else{ echo $rzvy_defaultlang['birthdate']; } ?>" value="<?php if($userdob != ""){ if($rzvy_birthdate_with_year == "Y"){ echo date("j F Y", strtotime($userdob)); }else{ echo date("j F", strtotime($userdob)); } } ?>" class="form-control datepicker rzvy-input-class <?php echo $inputAlignment; ?>">
									<label for="rzvy_user_dob" generated="true" class="error"></label>
								</div>
							</div>
							<?php  
						}else{ 
							?>
							<input type="hidden" id="rzvy_user_dob" name="rzvy_user_dob" value="<?php if($userdob != ""){ if($rzvy_birthdate_with_year == "Y"){ echo date("j F Y", strtotime($userdob)); }else{ echo date("j F", strtotime($userdob)); } } ?>" />
							<?php 
						} 
						
						if($rzvy_en_ff_notes_status == "Y"){ 
							?>
							<div class="col-sm-12">
								<div class="form-group">
									<input type="text" id="rzvy_user_notes" name="rzvy_user_notes" placeholder="<?php if(isset($rzvy_translangArr['notes'])){ echo $rzvy_translangArr['notes']; }else{ echo $rzvy_defaultlang['notes']; } ?>" value="" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
								</div>
							</div>
							<?php  
						}else{ 
							?>
							<input type="hidden" id="rzvy_user_notes" name="rzvy_user_notes" value="" />
							<?php 
						} 
						?>
					</div>
				</div>
			</form>
		</div>
		<?php } ?>
		<?php if($rzvy_show_guest_user_checkout == "Y"){ ?>
			<div class="form-item" data-form="rzvy-guest-user">
				<form method="post" name="rzvy_guestuser_detail_form" id="rzvy_guestuser_detail_form">
					<div class="rzvy-radio-group-block mt24  <?php echo $inputAlignment; ?>" <?php if($rzvy_show_existing_new_user_checkout == "N"){ echo "style='display:block;'"; } ?> id="rzvy-guest-user-box">
						<div class="row">
							<?php 
							if($rzvy_g_ff_firstname_status == "Y"){ 
								?>
								<div class="col-sm-6">
									<div class="form-group">
										<input type="text" id="rzvy_guest_firstname" name="rzvy_guest_firstname" placeholder="<?php if(isset($rzvy_translangArr['first_name'])){ echo $rzvy_translangArr['first_name']; }else{ echo $rzvy_defaultlang['first_name']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
									</div>
								</div>
								<?php 
							}else{ 
								?>
								<input type="hidden" id="rzvy_guest_firstname" name="rzvy_guest_firstname" />
								<?php 
							} 
							
							if($rzvy_g_ff_lastname_status == "Y"){ 
								?>
								<div class="col-sm-6">
									<div class="form-group">
										<input type="text" id="rzvy_guest_lastname" name="rzvy_guest_lastname" placeholder="<?php if(isset($rzvy_translangArr['last_name'])){ echo $rzvy_translangArr['last_name']; }else{ echo $rzvy_defaultlang['last_name']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
									</div>
								</div>
								<?php 
							}else{ 
								?>
								<input type="hidden" id="rzvy_guest_lastname" name="rzvy_guest_lastname" />
								<?php 
							} 
							
							if($rzvy_g_ff_email_status == "Y"){ 
								?>
								<div class="col-sm-6">
									<div class="form-group">
										<input type="text" id="rzvy_guest_email" name="rzvy_guest_email" placeholder="<?php if(isset($rzvy_translangArr['email_address'])){ echo $rzvy_translangArr['email_address']; }else{ echo $rzvy_defaultlang['email_address']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
									</div>
								</div>
								<?php 
							}else{ 
								?>
								<input type="hidden" id="rzvy_guest_email" name="rzvy_guest_email" />
								<?php 
							} 
							
							if($rzvy_g_ff_phone_status == "Y"){ 
								?>
								<div class="col-sm-6">
									<div class="form-group">
										<input type="text" id="rzvy_guest_phone" name="rzvy_guest_phone" placeholder="<?php if(isset($rzvy_translangArr['phone_number'])){ echo $rzvy_translangArr['phone_number']; }else{ echo $rzvy_defaultlang['phone_number']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
										<label for="rzvy_guest_phone" generated="true" class="error"></label>
									</div>
								</div>
								<?php 
							}else{ 
								?>
								<input type="hidden" id="rzvy_guest_phone" name="rzvy_guest_phone" />
								<?php 
							}
							
							$show_gzip_input = "";
							if($rzvy_location_selector_status == "N" || $rzvy_location_selector_status == ""){ 
								$show_gzip_input= "rzvy_hide";
							}
							
							if($rzvy_g_ff_address_status == "Y"){ 
								?>
								<div class="col-sm-12">
									<div class="form-group">
										<input type="text" id="rzvy_guest_address" name="rzvy_guest_address" placeholder="<?php if(isset($rzvy_translangArr['address'])){ echo $rzvy_translangArr['address']; }else{ echo $rzvy_defaultlang['address']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
									</div>
								</div>
								<?php 
							}else{ 
								?>
								<input type="hidden" id="rzvy_guest_address" name="rzvy_guest_address" />
								<?php 
							} 
							?>
							<div class="col-sm-6 <?php echo $show_gzip_input; ?>">
								<div class="form-group">
									<input type="text" id="rzvy_guest_zip" name="rzvy_guest_zip" placeholder="<?php if(isset($rzvy_translangArr['zip'])){ echo $rzvy_translangArr['zip']; }else{ echo $rzvy_defaultlang['zip']; } ?>" disabled value="<?php echo $userzip; ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
								</div>
							</div>
							<?php 
							if($rzvy_g_ff_city_status == "Y"){ 
								?>
								<div class="col-sm-6">
									<div class="form-group">
										<input type="text" id="rzvy_guest_city" name="rzvy_guest_city" placeholder="<?php if(isset($rzvy_translangArr['city'])){ echo $rzvy_translangArr['city']; }else{ echo $rzvy_defaultlang['city']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
									</div>
								</div>
								<?php 
							}else{ 
								?>
								<input type="hidden" id="rzvy_guest_city" name="rzvy_guest_city" />
								<?php 
							} 
							if($rzvy_g_ff_state_status == "Y"){ 
								?>
								<div class="col-sm-6">
									<div class="form-group">
										<input type="text" id="rzvy_guest_state" name="rzvy_guest_state" placeholder="<?php if(isset($rzvy_translangArr['state'])){ echo $rzvy_translangArr['state']; }else{ echo $rzvy_defaultlang['state']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
									</div>
								</div>
								<?php 
							}else{ 
								?>
								<input type="hidden" id="rzvy_guest_state" name="rzvy_guest_state" />
								<?php 
							} 
							if($rzvy_g_ff_country_status == "Y"){ 
								?>
								<div class="col-sm-6">
									<div class="form-group">
										<input type="text" id="rzvy_guest_country" name="rzvy_guest_country" placeholder="<?php if(isset($rzvy_translangArr['country'])){ echo $rzvy_translangArr['country']; }else{ echo $rzvy_defaultlang['country']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
									</div>
								</div>
								<?php 
							}else{ 
								?>
								<input type="hidden" id="rzvy_guest_country" name="rzvy_guest_country" />
								<?php 
							} 
							if($rzvy_g_ff_dob_status == "Y"){ 
								?>
								<div class="col-sm-6">
									<div class="form-group">
										<input type="text" id="rzvy_guest_dob" name="rzvy_guest_dob" placeholder="<?php if(isset($rzvy_translangArr['birthdate'])){ echo $rzvy_translangArr['birthdate']; }else{ echo $rzvy_defaultlang['birthdate']; } ?>" value="" class="datepicker form-control rzvy-input-class <?php echo $inputAlignment; ?>">
										<label for="rzvy_guest_dob" generated="true" class="error"></label>
									</div>
								</div>
								<?php 
							}else{ 
								?>
								<input type="hidden" id="rzvy_guest_dob" name="rzvy_guest_dob" value=""/>
								<?php 
							} 
							if($rzvy_g_ff_notes_status == "Y"){ 
								?>
								<div class="col-sm-12">
									<div class="form-group">
										<input type="text" id="rzvy_guest_notes" name="rzvy_guest_notes" placeholder="<?php if(isset($rzvy_translangArr['notes'])){ echo $rzvy_translangArr['notes']; }else{ echo $rzvy_defaultlang['notes']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>">
									</div>
								</div>
								<?php 
							}else{ 
								?>
								<input type="hidden" id="rzvy_guest_notes" name="rzvy_guest_notes" />
								<?php 
							} 
							?>
						</div>
					</div>
				</form>
			</div>	
		<?php } ?>
		<?php if($rzvy_show_existing_new_user_checkout == "Y"){ ?>
			<div class="form-item" data-form="rzvy-user-forget-password">
				<form id="rzvy_forgot_password_form" name="rzvy_forgot_password_form">
					<div class="form-group <?php echo $inputAlignment; ?>" id="rzvy-user-forget-password-box">
						<label class="form-label" for="rzvy_forgot_password_email"><?php if(isset($rzvy_translangArr['your_registered_email_address'])){ echo $rzvy_translangArr['your_registered_email_address']; }else{ echo $rzvy_defaultlang['your_registered_email_address']; } ?></label>
						<input type="email" id="rzvy_forgot_password_email" name="rzvy_forgot_password_email" placeholder="<?php if(isset($rzvy_translangArr['your_registered_email_address'])){ echo $rzvy_translangArr['your_registered_email_address']; }else{ echo $rzvy_defaultlang['your_registered_email_address']; } ?>" class="form-control <?php echo $inputAlignment; ?>">
					</div>
					<button class="btn btn-success w-100 mt-2" id="rzvy_forgot_password_btn" type="submit"><i class="fa fa-envelope"></i> &nbsp;&nbsp;<?php if(isset($rzvy_translangArr['send_mail'])){ echo $rzvy_translangArr['send_mail']; }else{ echo $rzvy_defaultlang['send_mail']; } ?></button>
				</form>
			</div>	
		<?php } ?>
	</div>
</div>
	<div class="row py-2 mt-4">
		<div class="col-md-12">
			<div class="rzvy-steps-btn">
				<a href="javascript:void(0)" id="rzvy-get-second-next-box-btn" class="btn btn-success next-step next-button rzvy_nextstep_btn"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['prev'])){ echo $rzvy_translangArr['prev']; }else{ echo $rzvy_defaultlang['prev']; } ?></a>
				<a href="javascript:void(0)" id="rzvy-get-fourth-next-box-btn" class="btn btn-success next-step next-button rzvy_nextstep_btn pull-right"><?php if(isset($rzvy_translangArr['next'])){ echo $rzvy_translangArr['next']; }else{ echo $rzvy_defaultlang['next']; } ?>&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
			</div>
		</div>
	</div>
	<?php 
}

/* add to cart item ajax */
if(isset($_POST['add_to_cart_item'])){
	$id = $_POST['id'];
	$qty = $_POST['qty'];
	
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
		$_SESSION['rzvy_cart_subtotal'] = $subtotal;
		$_SESSION['rzvy_cart_nettotal'] = $subtotal;
	}else{
		/** remove item from cart **/	
		$subtotal = $_SESSION['rzvy_cart_service_price'];
		foreach($_SESSION['rzvy_cart_items'] as $val){ 
			$subtotal = $subtotal+$val['rate'];
		} 
		$cart_item_key = $obj_frontend->rzvy_check_existing_cart_item($_SESSION['rzvy_cart_items'], $id);
		if(is_numeric($cart_item_key)){
			$subtotal = $subtotal-$_SESSION['rzvy_cart_items'][$cart_item_key]['rate'];
			unset($_SESSION['rzvy_cart_items'][$cart_item_key]);
			$_SESSION['rzvy_cart_items'] = array_values($_SESSION['rzvy_cart_items']);			
			$_SESSION['rzvy_cart_subtotal'] = $subtotal;
			$_SESSION['rzvy_cart_nettotal'] = $subtotal;
		}
	}
}

/* Check and apply coupon ajax */
else if(isset($_POST['apply_coupon'])){
	/** calculate coupon discount **/
	if(isset($_POST["apply_input"])){
		$obj_frontend->coupon_id = $_POST["id"];
		$couponid =  $obj_frontend->get_coupon_id_by_couponcode();
		if($couponid>0){
			$_SESSION['rzvy_cart_couponid'] = $couponid;
			echo "available";				
		}			
	}else{
		$_SESSION['rzvy_cart_couponid'] = $_POST["id"];
		echo "available";
	}	
}

/* remove applied coupon ajax */
else if(isset($_POST['remove_applied_coupon'])){
	$_SESSION['rzvy_cart_couponid'] = "";
	$_SESSION['rzvy_cart_coupondiscount'] = 0;
}

/* refresh cart ajax */
else if(isset($_POST['refresh_cart_sidebar'])){
	$obj_frontend->category_id = $_SESSION['rzvy_cart_category_id'];
	$obj_frontend->service_id = $_SESSION['rzvy_cart_service_id'];
	$category_name = $obj_frontend->readone_category_name();
	$servicedata = $obj_frontend->readone_service(); 
	$service_name = $servicedata['title']; 
	$service_rate = $servicedata['rate'];  
	
	$show_coupon_div = "Y";
	if(isset($_POST["user"]) && $_POST["user"] == "gc"){
		$show_coupon_div = "N";
	} 
	$rzvy_tax_status = $obj_settings->get_option('rzvy_tax_status');
	$rzvy_tax_type = $obj_settings->get_option('rzvy_tax_type');
	$rzvy_tax_value = $obj_settings->get_option('rzvy_tax_value'); 
	
	$calc_subtotal = $_SESSION['rzvy_cart_service_price'];
	if(isset($_SESSION['add_to_cart_package']) && sizeof($_SESSION['add_to_cart_package'])>0){ 
		foreach($_SESSION['add_to_cart_package'] as $val){ 
			 $calc_subtotal = $calc_subtotal+$val['rate'];
		}
	}
	
	foreach($_SESSION['rzvy_cart_items'] as $val){ 
		$calc_subtotal = $calc_subtotal+$val['rate'];
	}
	
	if(isset($_SESSION['rzvy_package_credituse']) && $_SESSION['rzvy_package_credituse']=='add'){
		if(isset($_SESSION['rzvy_package_discount'])){
			$calc_subtotal = $calc_subtotal-$_SESSION['rzvy_package_discount'];
		}
	}
	if(isset($_SESSION['rzvy_package_credituse']) && $_SESSION['rzvy_package_credituse']=='remove'){
		if(isset($_SESSION['rzvy_package_discount'])){
			//$calc_subtotal = $calc_subtotal+$_SESSION['rzvy_package_discount'];
		}				
		if(isset($_SESSION['rzvy_package_discount'])){ unset($_SESSION['rzvy_package_discount']); }
		if(isset($_SESSION['rzvy_package_credituse'])){ unset($_SESSION['rzvy_package_credituse']); }
	}
	$_SESSION['rzvy_cart_subtotal'] = $calc_subtotal;
	
	$net_total = $_SESSION['rzvy_cart_subtotal'];
		
	/** calculate loyalty points **/
	if($_SESSION['rzvy_lpoint_total']>0 && ($_SESSION["rzvy_lpoint_checked"] == "true" || $_SESSION["rzvy_lpoint_checked"] == "on")){
		if($_SESSION['rzvy_cart_subtotal']>0){
			$calculate_lp = ($_SESSION['rzvy_lpoint_total']*$_SESSION['rzvy_lpoint_value']);
			$calculate_lp = number_format($calculate_lp,2,".",'');
			
			if($calculate_lp>0){
				if($_SESSION['rzvy_cart_subtotal']<$calculate_lp){
					$leftamount = ($calculate_lp-$_SESSION['rzvy_cart_subtotal']);
					$leftlpoints = ($leftamount/$_SESSION['rzvy_lpoint_value']);
					$leftlpoints = number_format($leftlpoints,0,".",'');
					$usedlpoints = ($_SESSION['rzvy_lpoint_total']-$leftlpoints);
					$_SESSION['rzvy_lpoint_used'] = $usedlpoints;
					$_SESSION['rzvy_cart_lpoint'] = $usedlpoints;
					$_SESSION['rzvy_cart_lpoint_price'] = $_SESSION['rzvy_cart_subtotal'];
					$_SESSION['rzvy_lpoint_left'] = $leftlpoints;
					$net_total = 0;
				}else{
					$net_total = ($_SESSION['rzvy_cart_subtotal']-$calculate_lp);
					$_SESSION['rzvy_lpoint_used'] = $_SESSION['rzvy_lpoint_total'];
					$_SESSION['rzvy_cart_lpoint'] = $_SESSION['rzvy_lpoint_total'];
					$_SESSION['rzvy_cart_lpoint_price'] = $calculate_lp;
					$_SESSION['rzvy_lpoint_left'] = 0;
				}
			}else{
				$_SESSION['rzvy_lpoint_used'] = 0;
				$_SESSION['rzvy_cart_lpoint'] = 0;
				$_SESSION['rzvy_cart_lpoint_price'] = 0;
				$_SESSION['rzvy_lpoint_left'] = $_SESSION['rzvy_lpoint_total'];
			}
		}else{
			$_SESSION['rzvy_lpoint_used'] = 0;
			$_SESSION['rzvy_cart_lpoint'] = 0;
			$_SESSION['rzvy_cart_lpoint_price'] = 0;
			$_SESSION['rzvy_lpoint_left'] = 0;
		}
	}else{
		$_SESSION['rzvy_lpoint_used'] = 0;
		$_SESSION['rzvy_cart_lpoint'] = 0;
		$_SESSION['rzvy_cart_lpoint_price'] = 0;
		$_SESSION['rzvy_lpoint_left'] = 0;
	}
	
	/** Calculate frequently-discount **/
	if(is_numeric($_SESSION['rzvy_cart_freqdiscount_id'])){
		$obj_frontend->frequently_discount_id = $_SESSION['rzvy_cart_freqdiscount_id'];
		$fd_discount = $obj_frontend->readone_frequently_discount(); 
		if(is_array($fd_discount)){
			if($net_total>0){
				if($fd_discount['fd_type'] == "percentage"){
					$cart_fd = ($net_total*$fd_discount["fd_value"]/100);
				}else{
					$cart_fd = $fd_discount["fd_value"];
				}
				$cart_fd = number_format($cart_fd,2,".",'');
				$net_total = ($net_total-$cart_fd);
				$_SESSION['rzvy_cart_freqdiscount'] = $cart_fd;
			}else{
				$_SESSION['rzvy_cart_freqdiscount'] = 0;
			}	
		} 
	}
	
	/** Calculate coupon-discount **/
	if(is_numeric($_SESSION['rzvy_cart_couponid'])){
		$obj_frontend->coupon_id = $_SESSION['rzvy_cart_couponid'];
		$coupon_detail = $obj_frontend->apply_coupon(); 
		if($net_total>0){
			if($coupon_detail['coupon_type'] == "percentage"){
				$cart_coupon = ($net_total*$coupon_detail["coupon_value"]/100);
			}else{
				$cart_coupon = $coupon_detail["coupon_value"];
			}
			$cart_coupon = number_format($cart_coupon,2,".",'');
			$net_total = ($net_total-$cart_coupon);
			$_SESSION['rzvy_cart_coupondiscount'] = $cart_coupon;
		}else{
			$_SESSION['rzvy_cart_coupondiscount'] = 0;
			$_SESSION['rzvy_cart_couponid'] = "";
		}
	}
	
	/** calculate referral-discount **/
	if($_SESSION['rzvy_applied_ref_customer_id'] != "" && is_numeric($_SESSION['rzvy_applied_ref_customer_id'])){
		if($net_total>0){
			$rzvy_referral_discount_type = $obj_settings->get_option('rzvy_referral_discount_type');
			$rzvy_referral_discount_value = $obj_settings->get_option('rzvy_referral_discount_value');
			if($rzvy_referral_discount_type == "percentage"){
				$cart_referral_coupon = ($net_total*$rzvy_referral_discount_value/100);
			}else{
				$cart_referral_coupon = $rzvy_referral_discount_value;
			}
			$cart_referral_coupon = number_format($cart_referral_coupon,2,".",'');
			$net_total = ($net_total-$cart_referral_coupon);
			$_SESSION['rzvy_referral_discount_amount'] = $cart_referral_coupon;
		}else{
			$_SESSION['rzvy_referral_discount_amount'] = 0;
			$_SESSION['rzvy_applied_ref_customer_id'] = "";
		}
	}else{
		/* $_SESSION['rzvy_referral_discount_amount'] = 0;
		$_SESSION['rzvy_applied_ref_customer_id'] = ""; */
		
		/** calculate referral discount for referred user **/
		if(isset($_SESSION["referralcode_applied"]) && $_SESSION["referralcode_applied"] =="Y" && $show_coupon_div =="Y"){
			if($net_total>0){
				$rzvy_referred_discount_type = $obj_settings->get_option("rzvy_referred_discount_type");
				$rzvy_referred_discount_value = $obj_settings->get_option("rzvy_referred_discount_value");
				if($rzvy_referred_discount_type == "percentage"){
					$cart_referred_coupon = ($net_total*$rzvy_referred_discount_value/100);
				}else{
					$cart_referred_coupon = $rzvy_referred_discount_value;
				}
				$cart_referred_coupon = number_format($cart_referred_coupon,2,".",'');
				$net_total = ($net_total-$cart_referred_coupon);
				$_SESSION['rzvy_applied_ref_customer_id'] = $_SESSION['rzvy_applied_refcode'];
				$_SESSION['rzvy_referral_discount_amount'] = $cart_referred_coupon;
			}else{
				$_SESSION['rzvy_referral_discount_amount'] = 0;
				$_SESSION['rzvy_applied_ref_customer_id'] = "";
			}
		}else{
			$_SESSION['rzvy_referral_discount_amount'] = 0;
			$_SESSION['rzvy_applied_ref_customer_id'] = "";
		}
	}
	
				
	/** calculate Tax/Vat/GST **/
	if($rzvy_tax_status == "Y"){
		if($net_total>0){
			if($rzvy_tax_type == "percentage"){
				$cart_tax = ($net_total*$rzvy_tax_value/100);
			}else{
				$cart_tax = $rzvy_tax_value;
			}
			$cart_tax = number_format($cart_tax,2,".",'');
			$_SESSION['rzvy_cart_tax'] = $cart_tax;
			$net_total = ($net_total+$cart_tax);
		}else{
			$_SESSION['rzvy_cart_tax'] = 0;
		}
	}else{
		$_SESSION['rzvy_cart_tax'] = 0;
	}
	
	/** partial deposite **/
	if(isset($_POST["payment_method"]) && $net_total>0){
		if(isset($_POST["is_partial"])){
			if($_POST["is_partial"] == "true" || $_POST["is_partial"] == "on"){
				if($_POST["payment_method"] == "paypal" || $_POST["payment_method"] == "stripe" || $_POST["payment_method"] == "authorize.net" || $_POST["payment_method"] == "2checkout"){	
					if($obj_settings->get_option("rzvy_paypal_payment_status") == "Y" || $obj_settings->get_option("rzvy_stripe_payment_status") == "Y" || $obj_settings->get_option("rzvy_authorizenet_payment_status") == "Y" || $obj_settings->get_option("rzvy_twocheckout_payment_status") == "Y"){ 
						
						$partial_deposite_status = $obj_settings->get_option("rzvy_partial_deposite_status");
						$partial_deposite_type = $obj_settings->get_option("rzvy_partial_deposite_type");
						$partial_deposite_value = $obj_settings->get_option("rzvy_partial_deposite_value");
						
						if($partial_deposite_status == "Y"){
							if($partial_deposite_type == "percentage"){
								$cart_partial_deposite = ($net_total*$partial_deposite_value/100);
							}else{
								$cart_partial_deposite = $partial_deposite_value;
							}
							$cart_partial_deposite = number_format($cart_partial_deposite,2,".",'');
							$net_total = ($net_total-$cart_partial_deposite);
							$_SESSION['rzvy_cart_partial_deposite'] = $cart_partial_deposite;
						}else{
							$_SESSION['rzvy_cart_partial_deposite'] = 0;
						}
					}else{
						$_SESSION['rzvy_cart_partial_deposite'] = 0;
					}
				}else{
					$_SESSION['rzvy_cart_partial_deposite'] = 0;
				}
			}else{
				$_SESSION['rzvy_cart_partial_deposite'] = 0;
			}
		}else{
			$_SESSION['rzvy_cart_partial_deposite'] = 0;
		}
	}else{
		$_SESSION['rzvy_cart_partial_deposite'] = 0;
	}
	
	$_SESSION['rzvy_cart_nettotal'] = $net_total;
	
	if(isset($_POST["payment_method"])){
		/* if(isset($_POST["is_partial"])){
			if($_POST["is_partial"] == "true" || $_POST["is_partial"] == "on"){
				if($_POST["payment_method"] == "paypal" || $_POST["payment_method"] == "stripe" || $_POST["payment_method"] == "authorize.net" || $_POST["payment_method"] == "2checkout"){
					echo $_SESSION['rzvy_cart_partial_deposite'];die;
				}
			}
		} */
		echo $_SESSION['rzvy_cart_partial_deposite'];die;
	}
	?>
	<input type="hidden" class='rzvy_cart_lp_amount' data-lpointleft="<?php echo $_SESSION['rzvy_lpoint_left']; ?>" data-lpointused="<?php echo $_SESSION['rzvy_lpoint_used']; ?>" data-lpointtotal="<?php echo $_SESSION['rzvy_lpoint_total']; ?>" data-lpointcart="<?php echo $_SESSION['rzvy_cart_lpoint']; ?>" value='<?php echo $_SESSION['rzvy_lpoint_price']; ?>' />
	<div class="whitebox">
		<div class="row justify-content-between <?php echo $inputAlignment; ?>">
		  <div class="col-md-auto"><i class="fa fa-cog"></i> <?php echo $category_name; ?><?php if($_SESSION['rzvy_cart_freqdiscount_label'] != ""){ echo " - ".$_SESSION['rzvy_cart_freqdiscount_label'];	} ?></div>
		  <?php if($rzvy_book_with_datetime == "Y"){ ?>
		  <div class="col-md-auto text-md-end"><i class="fa fa-calendar"></i> <?php echo date($rzvy_date_format." ".$rzvy_time_format, strtotime($_SESSION['rzvy_cart_datetime'])); if($_SESSION['rzvy_cart_end_datetime'] != ""){ echo " - ".date($rzvy_time_format, strtotime($_SESSION['rzvy_cart_end_datetime'])); } ?></div>
		  <?php } ?>
		</div>
		<div class="rzvy-table">
		<table class="table">
			<thead>
				<tr>
				  <th colspan="2"><?php if(isset($rzvy_translangArr['service'])){ echo $rzvy_translangArr['service']; }else{ echo $rzvy_defaultlang['service']; } ?></th>
				  <th colspan="2"><?php if($service_rate>0 && $rzvy_price_display=='Y'){ if(isset($rzvy_translangArr['subtotal'])){ echo $rzvy_translangArr['subtotal']; }else{ echo $rzvy_defaultlang['subtotal']; } } ?></th>
				</tr>
			</thead>
			<tbody class="<?php echo $inputAlignment; ?>">
				<tr class="<?php echo $inputAlignment; ?>">
					<?php if($servicedata['image'] != "" && $rzvy_show_service_image == "Y" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$servicedata['image'])){ ?>
						<td colspan="2"><img class="rzvy_addons_img me-2" width="50" height="50" src="<?php echo SITE_URL."uploads/images/".$servicedata['image']; ?>" /> <?php echo $service_name; ?></td>
					<?php }else{ ?>
						<td colspan="2"><?php echo $service_name; ?></td>
					<?php } ?>
					<td></td>
					<?php if($rzvy_price_display=='Y'){ ?><td><?php if($service_rate>0){ echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($service_rate,2,".",'')); } ?></td><?php } ?>
				</tr>
				<?php 
					if(sizeof($_SESSION['rzvy_cart_items'])>0){ 
						?>
						<tr id="rzvy_remove_addon_head">
							<th class="<?php echo $inputAlignment; ?>"><?php if(isset($rzvy_translangArr['additional_services'])){ echo $rzvy_translangArr['additional_services']; }else{ echo $rzvy_defaultlang['additional_services']; } ?></th>
							<th class="<?php echo $inputAlignment; ?>"><?php if(isset($rzvy_translangArr['qty_ad'])){ echo $rzvy_translangArr['qty_ad']; }else{ echo $rzvy_defaultlang['qty_ad']; } ?></th>
							<th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
							<?php if($rzvy_price_display=='Y'){  ?><th class="<?php echo $inputAlignment; ?>"><?php if($service_rate>0){ if(isset($rzvy_translangArr['subtotal'])){ echo $rzvy_translangArr['subtotal']; }else{ echo $rzvy_defaultlang['subtotal']; } } ?></th><?php }else{ echo '<th></th>';} ?>
						</tr>
						<?php 
					} 
				?>
		
				<?php 
				if(sizeof($_SESSION['rzvy_cart_items'])>0){ 
					foreach($_SESSION['rzvy_cart_items'] as $val){ 
						$obj_frontend->addon_id = $val['id'];
						$addon = $obj_frontend->readone_addon(); 
						?>
						<tr class="rzvy_count_addon_tr" id="rzvy_addon_tr_<?php echo $val['id']; ?>">
							<td><?php if($rzvy_show_addon_image == "Y" && $addon['image'] != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$addon['image'])){ ?><img class="rzvy_addons_img me-2" width="50" height="50" src="<?php echo SITE_URL."uploads/images/".$addon['image']; ?>" /> <?php } ?><?php echo ucwords($addon["title"]); ?></td>
							 <td>
								<?php 
								 if($addon["multiple_qty"] == "Y"){ 
									?>
									<div class="quantity rzvy_plusminus_addons_input_maindiv">
									   <input id="rzvy-addon-card-mnl-<?php echo $addon['id']; ?>" type="hidden" value="<?php echo $addon['min_limit']; ?>" />
										<input id="rzvy-addon-card-ml-<?php echo $addon['id']; ?>" type="hidden" value="<?php echo $addon['max_limit']; ?>" />
									  <input id="rzvy_plusminus_addon_card_cart_input_<?php echo $addon['id']; ?>" type="number" name="rzvy_plusminus_addon_card_cart_input_<?php echo $addon['id']; ?>" value="<?php echo $val['qty']; ?>" min="0" max="10" readonly="readonly" inputmode="numeric" class="form-control rzvy_plusminus_addon_card_input">
									  <span class="quantity-controler down rzvy_plusminus_addon_card_btns_cart rzvy_plusminus_addon_card_minus_btn_<?php echo $addon['id']; ?> rzvy_flag_minus" data-action="minus" data-id="<?php echo $addon['id']; ?>"><span class="fa fa-minus"></span></span>
									  <span class="quantity-controler up  rzvy_plusminus_addon_card_btns_cart rzvy_plusminus_addon_card_plus_btn_<?php echo $addon['id']; ?> rzvy_flag_plus" data-action="plus"  data-id="<?php echo $addon['id']; ?>"><span class="fa fa-plus"></span></span>
									</div>									
									<?php 
								}else{
									echo $val['qty'];
								} 
								?>
								
							</td>
							<td><a class="rzvy_remove_addon_from_cart" href="javascript:void(0)" data-id="<?php echo $val['id']; ?>"><img src="<?php echo SITE_URL;?>/includes/images/delete.svg" alt="Delete" width="15" class="rzvy_remove_addon_icon"></a></td>
							<td><?php if($rzvy_price_display=='Y'){ if($val['rate']>0){ echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($val['rate'],2,".",'')); } } ?></td>						
						</tr>
						<?php 
					}
				} 
				/* Services Package Cart Summary */
				$Rzvy_Hooks->do_action('services_package_cartsummary_stepview', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang,$rzvy_currency_symbol,$inputAlignment,$rzvy_currency_position));
				/* Services Package Cart Summary End */
				
				
				if(isset($_SESSION['rzvy_cart_subtotal']) && $rzvy_price_display=='Y'){
					?>
					<tr class="rzvy_cart_calculations">
						<th colspan="2"><?php if(isset($rzvy_translangArr['sub_total'])){ echo $rzvy_translangArr['sub_total']; }else{ echo $rzvy_defaultlang['sub_total']; } ?></th>
						<th colspan="2"><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($_SESSION['rzvy_cart_subtotal'],2,".",'')); ?></th>
					</tr>
					<?php
					if($_SESSION['rzvy_cart_lpoint_price']>0){  
						?>
						<tr class="rzvy_cart_calculations_no_border">
							<th colspan="2"><?php if(isset($rzvy_translangArr['loyalty_points'])){ echo $rzvy_translangArr['loyalty_points']; }else{ echo $rzvy_defaultlang['loyalty_points']; } ?></th>
							<th colspan="2">-<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($_SESSION['rzvy_cart_lpoint_price'],2,".",'')); ?></th>
						</tr>
						<?php 
					} 
					if($_SESSION['rzvy_cart_freqdiscount']>0){  
						?>
						<tr class="rzvy_cart_calculations_no_border">
							<th colspan="2"><?php if(isset($rzvy_translangArr['frequently_discount'])){ echo $rzvy_translangArr['frequently_discount']; }else{ echo $rzvy_defaultlang['frequently_discount']; } ?></th>
							<th colspan="2">-<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($_SESSION['rzvy_cart_freqdiscount'],2,".",'')); ?></th>
						</tr>
						<?php 
					} 
					
					if($_SESSION['rzvy_cart_coupondiscount']>0){ 
						?>
						<tr class="rzvy_cart_calculations_no_border">
							<th colspan="2"><?php if(isset($rzvy_translangArr['coupon_discount'])){ echo $rzvy_translangArr['coupon_discount']; }else{ echo $rzvy_defaultlang['coupon_discount']; } ?></th>
							<th colspan="2">-<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($_SESSION['rzvy_cart_coupondiscount'],2,".",'')); ?></th>
						</tr>
						<?php 
					} 
					if($_SESSION['rzvy_referral_discount_amount']>0){ 
						?>
						<tr class="rzvy_cart_calculations_no_border">
							<th colspan="2"><?php if(isset($_SESSION["referralcode_applied"]) && $_SESSION["referralcode_applied"] =="Y"){
								if(isset($rzvy_translangArr['referral_discount_a'])){ echo $rzvy_translangArr['referral_discount_a']; }else{ echo $rzvy_defaultlang['referral_discount_a']; }
							} else{
								if(isset($rzvy_translangArr['referral_coupon_discount'])){ echo $rzvy_translangArr['referral_coupon_discount']; }else{ echo $rzvy_defaultlang['referral_coupon_discount']; }
							} ?></th>
							<th colspan="2">-<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($_SESSION['rzvy_referral_discount_amount'],2,".",'')); ?></th>
						</tr>
						<?php 
					} 
					
					if($_SESSION['rzvy_cart_tax']>0){
						?>
						<tr class="rzvy_cart_calculations_no_border">
							<th colspan="2"><?php if(isset($rzvy_translangArr['tax'])){ echo $rzvy_translangArr['tax']; }else{ echo $rzvy_defaultlang['tax']; } ?></th>
							<th colspan="2">+<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($_SESSION['rzvy_cart_tax'],2,".",'')); ?></th>
						</tr>
						<?php 
					} 
					?>
					<tr>
						<th colspan="2" class="rzvy_cart_calculations_no_border_td" id="rzvy_net_total_amount" data-amount="<?php echo $_SESSION['rzvy_cart_nettotal'];?>"><?php if(isset($rzvy_translangArr['net_total'])){ echo $rzvy_translangArr['net_total']; }else{ echo $rzvy_defaultlang['net_total']; } ?></th>
						<th colspan="2" class="rzvy_cart_calculations"><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($_SESSION['rzvy_cart_nettotal'],2,".",'')); ?></th>
					</tr>
					<?php 
				} 
				?>
			</tbody>
		</table>
		</div>
	</div>
	<?php 
}

/** Get Fourth box having customer detail **/
else if(isset($_POST['get_fourth_step_box'])){ 
	$show_coupon_div = "Y";
	if(isset($_POST["user"]) && $_POST["user"] == "gc"){
		$show_coupon_div = "N";
	} 
	$rzvy_show_existing_new_user_checkout = $obj_settings->get_option("rzvy_show_existing_new_user_checkout");
	$rzvy_show_guest_user_checkout = $obj_settings->get_option("rzvy_show_guest_user_checkout");
	?>
	<div class="rzvy-container">
		<?php 
		if($show_coupon_div=='Y'){
			/* Services Package Listing */
				$Rzvy_Hooks->do_action('services_package_listing', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang,$inputAlignment));		
			/* Services Package Listing End */
		}
		?>
		<div id="rzvy_bookingsummary"> 
			<p class="<?php echo $inputAlignment; ?>"><?php if(isset($rzvy_translangArr['please_wait_summary_loading'])){ echo $rzvy_translangArr['please_wait_summary_loading']; }else{ echo $rzvy_defaultlang['please_wait_summary_loading']; } ?></p>
		</div>
		<div class="step-item">
			<div class="whitebox">
			<?php 
				if($obj_settings->get_option("rzvy_allow_loyalty_points_status") == "Y" && $rzvy_show_existing_new_user_checkout == "Y"){ ?>
					<div class="mb-3 row align-items-center rzvy-cart-lpoint-div">
					<div class="col-md-6 rzvy-cart-lpoint-label-main">
					  <div class="form-check mb-0">
						<input class="form-check-input custom-control-input rzvy-lpoint-control-input" type="checkbox" name="loyalty-points" id="rzvy-loyalty-points" onclick="$('#rzvy-loyalty-points-info').slideToggle();">
						<label class="form-check-label" for="rzvy-loyalty-points"><?php if(isset($rzvy_translangArr['do_you_want_to_use_your_loyalty_points'])){ echo $rzvy_translangArr['do_you_want_to_use_your_loyalty_points']; }else{ echo $rzvy_defaultlang['do_you_want_to_use_your_loyalty_points']; } ?></label>
					  </div>
					</div>
					<div class="col-md-6 rzvy-cart-lpoint-main">
					  <div class="point-wrap" id="rzvy-loyalty-points-info"><i class="fa fa-fw fa-tag me-2" aria-hidden="true"></i><?php if(isset($rzvy_translangArr['your_loyalty_points_ad'])){ echo $rzvy_translangArr['your_loyalty_points_ad']; }else{ echo $rzvy_defaultlang['your_loyalty_points_ad']; } ?> <span class='rzvy_update_lpoint_count'>0</span> <?php if(isset($rzvy_translangArr['of'])){ echo $rzvy_translangArr['of']; }else{ echo $rzvy_defaultlang['of']; } ?> <?php echo $currencyB."<span class='rzvy_update_lpoint_amount'>".@number_format($_SESSION['rzvy_cart_lpoint'],2,".",'')."</span>".$currencyA; ?></div>
					</div>
				  </div>											
				<?php }
				if($obj_settings->get_option("rzvy_partial_deposite_status") == "Y"){ ?>
					<div class="mb-3 row align-items-center">
						<div class="col-md-6 rzvy-cart-partial-deposite-label-main">
						  <div class="form-check mb-0">
							<input class="form-check-input rzvy-partial-deposit-control-input" type="checkbox" name="pay-partially" id="rzvy-partial-deposit" onclick="$('#rzvy-partial-deposit-info').slideToggle();">
							<label class="form-check-label" for="rzvy-partial-deposit"><?php if(isset($rzvy_translangArr['do_you_want_to_pay_partially'])){ echo $rzvy_translangArr['do_you_want_to_pay_partially']; }else{ echo $rzvy_defaultlang['do_you_want_to_pay_partially']; } ?></label>
						  </div>
						</div>
						<div class="col-md-6">
						  <div class="point-wrap rzvy-cart-partial-deposite-main" id="rzvy-partial-deposit-info"><i class="fa fa-fw fa-money me-2" aria-hidden="true"></i><?php if(isset($rzvy_translangArr['partial_deposite_ad'])){ echo $rzvy_translangArr['partial_deposite_ad']; }else{ echo $rzvy_defaultlang['partial_deposite_ad']; } ?><?php echo $currencyB."<span class='rzvy_update_partial_amount'>".number_format($_SESSION['rzvy_cart_partial_deposite'],2,".",'')."</span>".$currencyA; ?></div>
						</div>
					</div>
				<?php } ?>
				<?php 
				/** available discount count end **/												
				$available_coupons = $obj_frontend->get_available_coupons();
				$rzvy_couponcunt='N';
				if(mysqli_num_rows($available_coupons)>0){
					$rzvy_couponcunt='Y';
				?>
				<h2 class="fs-5 pt-3"><?php if(isset($rzvy_translangArr['select_a_promo_offer'])){ echo $rzvy_translangArr['select_a_promo_offer']; }else{ echo $rzvy_defaultlang['select_a_promo_offer']; } ?></h2>
				<div class="rzvy-table">
					<table class="table">
					  <thead>
						<tr>
						  <th><?php if(isset($rzvy_translangArr['coupon_value'])){ echo $rzvy_translangArr['coupon_value']; }else{ echo $rzvy_defaultlang['coupon_value']; } ?></th>
						  <th align="left"><?php if(isset($rzvy_translangArr['coupon_code'])){ echo $rzvy_translangArr['coupon_code']; }else{ echo $rzvy_defaultlang['coupon_code']; } ?></th>
						  <th align="left"><?php if(isset($rzvy_translangArr['expires'])){ echo $rzvy_translangArr['expires']; }else{ echo $rzvy_defaultlang['expires']; } ?></th>
						</tr>
					  </thead>
					  <tbody>
						<?php 
						while($coupon = mysqli_fetch_array($available_coupons)){ 
							if(isset($_SESSION['customer_id'])){
								$obj_frontend->customer_id = $_SESSION['customer_id'];
								$obj_frontend->coupon_id = $coupon['id'];
								$check_coupon = $obj_frontend->check_available_coupon_of_existing_customer();
								if($check_coupon=="used"){
									continue;
								}
							} ?>
								<tr>
								  <td>
									<div class="form-check">
									  <input class="form-check-input rzvy-coupon-radio" type="radio" id="rzvy-coupon-radio-<?php echo $coupon['id']; ?>" name="rzvy-coupon-radio" value="<?php echo $coupon['id']; ?>" data-promo="<?php echo $coupon['coupon_code']; ?>">
									  <label class="form-check-label" for="rzvy-coupon-radio-<?php echo $coupon['id']; ?>"><?php if($coupon['coupon_type']=="flat"){ ?>
												<?php if(isset($rzvy_translangArr['flat'])){ echo $rzvy_translangArr['flat']; }else{ echo $rzvy_defaultlang['flat']; }  echo ' '.$obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($coupon['coupon_value'],2,".",'')).' '; 
												if(isset($rzvy_translangArr['off_on_your_purchase'])){ echo $rzvy_translangArr['off_on_your_purchase']; }else{ echo $rzvy_defaultlang['off_on_your_purchase']; }
												 }else{  echo $coupon['coupon_value']; ?>% <?php if(isset($rzvy_translangArr['off_on_your_purchase'])){ echo $rzvy_translangArr['off_on_your_purchase']; }else{ echo $rzvy_defaultlang['off_on_your_purchase']; }  
												 } ?> </label>
									</div>
								  </td>
								  <td align="left"><?php echo $coupon['coupon_code']; ?></td>
								  <td align="left"><?php echo date($rzvy_date_format, strtotime($coupon['coupon_expiry'])); ?></td>
								</tr>
						<?php } ?>													
					  </tbody>
					</table>
					<div class="mt-3 mb-1 rzvy_applied_coupon_div valid-feedback">
						<i class="fa fa-ticket"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['applied_discount_coupon'])){ echo $rzvy_translangArr['applied_discount_coupon']; }else{ echo $rzvy_defaultlang['applied_discount_coupon']; } ?><span class="fa-border rzvy_applied_coupon_badge"></span><a href="javascript:void(0)" class="rzvy_remove_applied_coupon" data-id=""><i class="fa fa-times-circle-o fa-lg"></i></a>
					</div>												
				</div>	
				<?php }  ?>
				<?php if($obj_settings->get_option("rzvy_coupon_input_status") == "Y"){ ?>							
				<div class="form-group mb-2 mt-5">
					<label class="form-label" for="rzvy_coupon_code"><?php if(isset($rzvy_translangArr['do_you_have_discount_coupon'])){ echo $rzvy_translangArr['do_you_have_discount_coupon']; }else{ echo $rzvy_defaultlang['do_you_have_discount_coupon']; } ?></label>
					<div class="form-input apply-code">
					  <input type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_coupon_code'])){ echo $rzvy_translangArr['enter_coupon_code']; }else{ echo $rzvy_defaultlang['enter_coupon_code']; } ?>" name="rzvy_coupon_code" class="form-control text-uppercase" id="rzvy_coupon_code"  value="">
					  <button type="submit" value="" class="btn btn-success" id="rzvy_apply_coupon_code_btn"><?php if(isset($rzvy_translangArr['apply'])){ echo $rzvy_translangArr['apply']; }else{ echo $rzvy_defaultlang['apply']; } ?></button>		  
					</div>
					
					<div id="rzvy-coupon-empty-error" class="d-none error"><?php if(isset($rzvy_translangArr['please_enter_coupon_code'])){ echo $rzvy_translangArr['please_enter_coupon_code']; }else{ echo $rzvy_defaultlang['please_enter_coupon_code']; } ?></div>
					<?php if ($rzvy_couponcunt=='N'){ ?>
					<div class="mt-3 mb-1 rzvy_applied_coupon_div valid-feedback">
						<i class="fa fa-ticket"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['applied_discount_coupon'])){ echo $rzvy_translangArr['applied_discount_coupon']; }else{ echo $rzvy_defaultlang['applied_discount_coupon']; } ?><span class="fa-border rzvy_applied_coupon_badge"></span><a href="javascript:void(0)" class="rzvy_remove_applied_coupon" data-id=""><i class="fa fa-times-circle-o fa-lg"></i></a>
					</div>
					<?php } ?>
				</div>
				<?php } ?> 
				
				<?php if($rzvy_show_existing_new_user_checkout == "Y"){ ?>
				<div class="mt-3" id="rzvy-customer-referral-coupons-container">
				<?php 
				/** Referral discount coupon list start **/
				if($obj_settings->get_option("rzvy_referral_discount_status") == "Y"){ 
					if(isset($_SESSION['customer_id'])){
						$available_rcoupons = $obj_frontend->get_all_referral_discount($_SESSION["customer_id"]);
						if(mysqli_num_rows($available_rcoupons)>0){  ?>
							<h2 class="fs-5 pt-3"><?php if(isset($rzvy_translangArr['select_a_referral_discount_coupon'])){ echo $rzvy_translangArr['select_a_referral_discount_coupon']; }else{ echo $rzvy_defaultlang['select_a_referral_discount_coupon']; } ?></h2>
							<div class="rzvy-table">
								<table class="table">
								  <thead>
									<tr>
									  <th><?php if(isset($rzvy_translangArr['coupon_value'])){ echo $rzvy_translangArr['coupon_value']; }else{ echo $rzvy_defaultlang['coupon_value']; } ?></th>
									  <th align="left"><?php if(isset($rzvy_translangArr['coupon_code'])){ echo $rzvy_translangArr['coupon_code']; }else{ echo $rzvy_defaultlang['coupon_code']; } ?></th>																  
									</tr>
								  </thead>
								  <tbody>
									<?php 
										while($coupon = mysqli_fetch_array($available_rcoupons)){ 
										?><tr>
											  <td>
												<div class="form-check">
												  <input class="form-check-input rzvy-rcoupon-radio" type="radio" id="rzvy-rcoupon-radio-<?php echo $coupon['id']; ?>" name="rzvy-rcoupon-radio" value="<?php echo $coupon['id']; ?>" data-promo="<?php echo $coupon['coupon']; ?>">
												  <label class="form-check-label" for="rzvy-rcoupon-radio-<?php echo $coupon['id']; ?>"><?php if($coupon['discount_type']=="flat"){ ?>
															<?php if(isset($rzvy_translangArr['flat'])){ echo $rzvy_translangArr['flat']; }else{ echo $rzvy_defaultlang['flat']; }  echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($coupon['discount'],2,".",'')); 
															if(isset($rzvy_translangArr['off_on_your_purchase'])){ echo $rzvy_translangArr['off_on_your_purchase']; }else{ echo $rzvy_defaultlang['off_on_your_purchase']; }
															 }else{  echo $coupon['discount']; ?>% <?php if(isset($rzvy_translangArr['off_on_your_purchase'])){ echo $rzvy_translangArr['off_on_your_purchase']; }else{ echo $rzvy_defaultlang['off_on_your_purchase']; }  
															 } ?></label>
												</div>
											  </td>
											  <td align="left"><?php echo $coupon['coupon']; ?></td>				  
											</tr>																		
									<?php } ?>
								  </tbody>
								</table>
								<?php if($obj_settings->get_option("rzvy_referral_discount_status") == "Y"){ ?>
								<div class="mt-3 mb-1 valid-feedback rzvy_applied_referral_coupon_div_text">
									<span><i class="fa fa-gift"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['applied_referral_discount_coupon'])){ echo $rzvy_translangArr['applied_referral_discount_coupon']; }else{ echo $rzvy_defaultlang['applied_referral_discount_coupon']; } ?>: <span class="fa-border rzvy_applied_referral_coupon_code"></span><a href="javascript:void(0)" class="rzvy_remove_applied_rcoupon" data-id=""><i class="fa fa-times-circle-o fa-lg"></i></a></span>
								</div>
								<?php } ?>												
							</div><?php  	
							} 
						}
					} /** Referral discount coupon list end **/ ?>
				</div>				
													
				<?php if($obj_settings->get_option("rzvy_referral_discount_status") == "Y"){ ?>							
				<div class="form-group mb-2 mt-5 rzvy_referral_code_divf">
					<label class="form-label" for="rzvy_referral_code"><?php if(isset($rzvy_translangArr['do_you_have_referral_code'])){ echo $rzvy_translangArr['do_you_have_referral_code']; }else{ echo $rzvy_defaultlang['do_you_have_referral_code']; } ?></label>
					<div class="form-input apply-code">
					  <input type="text" placeholder="<?php if(isset($rzvy_translangArr['enter_your_referral_code'])){ echo $rzvy_translangArr['enter_your_referral_code']; }else{ echo $rzvy_defaultlang['enter_your_referral_code']; } ?>" name="rzvy_referral_code" class="form-control text-uppercase" id="rzvy_referral_code" minlength="8" maxlength="8" value="<?php if(isset($_SESSION["referralcode_applied"]) && $_SESSION["referralcode_applied"] =="Y"){ echo $urlrefcode; } ?>">
					  <button type="submit" value="" class="btn btn-success" id="rzvy_apply_referral_code_btn"><?php if(isset($rzvy_translangArr['apply'])){ echo $rzvy_translangArr['apply']; }else{ echo $rzvy_defaultlang['apply']; } ?></button>		  
					</div>
					
					<div id="rzvy-referral-empty-error" class="d-none error"><?php if(isset($rzvy_translangArr['please_enter_referral_code'])){ echo $rzvy_translangArr['please_enter_referral_code']; }else{ echo $rzvy_defaultlang['please_enter_referral_code']; } ?></div>
					<div class="rzvy_referral_code_applied_div <?php echo $inputAlignment; ?>" <?php if(isset($_SESSION["referralcode_applied"]) && $_SESSION["referralcode_applied"] =="Y" && $urlrefcode != ""){ echo "style='display:block;'"; } ?>>
						<span class="valid-feedback d-block <?php echo $inputAlignment; ?>" <?php if(isset($_SESSION["referralcode_applied"]) && $_SESSION["referralcode_applied"] =="Y" && $urlrefcode != ""){ echo "style='display:block;'"; } ?>><?php if(isset($rzvy_translangArr['applied_referral_code'])){ echo $rzvy_translangArr['applied_referral_code']; }else{ echo $rzvy_defaultlang['applied_referral_code']; } ?>: <b class="rzvy_referral_code_applied_text"><?php if(isset($_SESSION["referralcode_applied"]) && $_SESSION["referralcode_applied"] =="Y"){ echo $urlrefcode; } ?></b></span>
					</div>
					<span class="referralcode_applied_msg error"><?php if(isset($_SESSION["referralcode_applied"]) && ($_SESSION["referralcode_applied"] =="O" || $_SESSION["referralcode_applied"] =="F")){
								if($_SESSION["referralcode_applied"] =="O"){
									if(isset($rzvy_translangArr['you_cannot_use_your_own_referral_code'])){ echo $rzvy_translangArr['you_cannot_use_your_own_referral_code']; }else{ echo $rzvy_defaultlang['you_cannot_use_your_own_referral_code']; }
								}
								if($_SESSION["referralcode_applied"] =="F"){
									if(isset($rzvy_translangArr['you_can_apply_referral_code_only_on_first_booking'])){ echo $rzvy_translangArr['you_can_apply_referral_code_only_on_first_booking']; }else{ echo $rzvy_defaultlang['you_can_apply_referral_code_only_on_first_booking']; } 
								}
							} ?>
					</span>
				</div>
				<?php } ?> 
			<?php } ?>	
			</div>
			<!-- Payment modes Start --->
			<?php if($_SESSION['rzvy_cart_nettotal']>0){		?>
			<div class="rzvy_payment_methods_container row step-item <?php echo $labelAlignmentClassName; ?>">
					<?php 
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
								<input type="radio" class="rzvy-payment-method-check form-check-input" id="rzvy-pay-at-venue" name="rzvy-payment-method-radio" value="pay-at-venue" checked />
								<label class="form-check-label" for="rzvy-pay-at-venue"><?php if(isset($rzvy_translangArr['pay_at_venue'])){ echo $rzvy_translangArr['pay_at_venue']; }else{ echo $rzvy_defaultlang['pay_at_venue']; } ?></label>
								</div>
							</div>
							<?php 
						} 
						if($obj_settings->get_option("rzvy_bank_transfer_payment_status") == "Y" && $rzvy_price_display=='Y'){ 
							?>
							<div class="col-sm-6">
								<div class="form-check custom inline">
								<input type="radio" class="rzvy-payment-method-check form-check-input" id="rzvy-bank-transfer" name="rzvy-payment-method-radio" value="bank transfer" <?php if($obj_settings->get_option("rzvy_pay_at_venue_status") != "Y"){ echo "checked"; } ?> data-toggle="rzvy-bank-payment"/>
								<label class="form-check-label" for="rzvy-bank-transfer"><?php if(isset($rzvy_translangArr['bank_transfer'])){ echo $rzvy_translangArr['bank_transfer']; }else{ echo $rzvy_defaultlang['bank_transfer']; } ?></label>
								</div>
							</div><?php 
						} 
						if($obj_settings->get_option("rzvy_paypal_payment_status") == "Y"  && $rzvy_price_display=='Y'){ 
							?>
							<div class="col-sm-6">
								<div class="form-check custom inline">
								<input type="radio" class="rzvy-payment-method-check form-check-input" id="rzvy-paypal-payment" name="rzvy-payment-method-radio" value="paypal" />
								<label class="form-check-label" for="rzvy-paypal-payment"><?php if(isset($rzvy_translangArr['paypal'])){ echo $rzvy_translangArr['paypal']; }else{ echo $rzvy_defaultlang['paypal']; } ?></label>
								</div>
							</div><?php 
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
								<input type="radio" class="rzvy-payment-method-check form-check-input" id="rzvy-card-payment" name="rzvy-payment-method-radio" value="<?php echo $payment_method; ?>" data-toggle="rzvy-card-payment"/>
								<label class="form-check-label" for="rzvy-card-payment"><?php if(isset($rzvy_translangArr['card_payment'])){ echo $rzvy_translangArr['card_payment']; }else{ echo $rzvy_defaultlang['card_payment']; } ?></label>
								</div>
							</div>
							<?php 
						} 
						?>
						</div>
					</div>
					<div class="forms rzvy-radio-group-block <?php echo $inputAlignment; ?>" style="display:none;">
						<div data-form="rzvy-bank-payment" class="form-item rzvy-bank-transfer-detail-box remove_payment_according_services_showhide rzvy_hide_show_payment_according_services " <?php if($obj_settings->get_option("rzvy_pay_at_venue_status") == "Y" || $obj_settings->get_option("rzvy_bank_transfer_payment_status") != "Y"){ echo "style='display:none'"; } ?>>
							<h2 class="fs-5"><?php if(isset($rzvy_translangArr['bank_details'])){ echo $rzvy_translangArr['bank_details']; }else{ echo $rzvy_defaultlang['bank_details']; } ?></h2>
							<div class="rzvy-table">
							  <table class="table">
								<tbody>
								  <tr>
									<td><strong><?php if(isset($rzvy_translangArr['bank_name'])){ echo $rzvy_translangArr['bank_name']; }else{ echo $rzvy_defaultlang['bank_name']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_bank_name"); ?></td>
									<td align="left"><strong><?php if(isset($rzvy_translangArr['account_name'])){ echo $rzvy_translangArr['account_name']; }else{ echo $rzvy_defaultlang['account_name']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_account_name"); ?></td>
								  </tr>
								  <tr>
									<td><strong><?php if(isset($rzvy_translangArr['account_number'])){ echo $rzvy_translangArr['account_number']; }else{ echo $rzvy_defaultlang['account_number']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_account_number"); ?></td>
									<td align="left"><strong><?php if(isset($rzvy_translangArr['branch_code'])){ echo $rzvy_translangArr['branch_code']; }else{ echo $rzvy_defaultlang['branch_code']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_branch_code"); ?></td>
								  </tr>
								  <tr>
									<td align="left"><strong><?php if(isset($rzvy_translangArr['ifsc_code'])){ echo $rzvy_translangArr['ifsc_code']; }else{ echo $rzvy_defaultlang['branch_code']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_ifsc_code"); ?></td>
								  </tr>
								</tbody>
							  </table>
							</div>
						</div>
						<div class="form-item card-payment rzvy-card-detail-box remove_payment_according_services_showhide rzvy_hide_show_payment_according_services" data-form="rzvy-card-payment">
							<p><?php if(isset($rzvy_translangArr['credit_card_details'])){ echo $rzvy_translangArr['credit_card_details']; }else{ echo $rzvy_defaultlang['credit_card_details']; } ?></p>
							<?php 
							if($obj_settings->get_option("rzvy_stripe_payment_status") == "Y" && $obj_settings->get_option("rzvy_authorizenet_payment_status") == "N" && $obj_settings->get_option("rzvy_twocheckout_payment_status") == "N"){ 
								?>
								<div class="row">
								  <div class="col-xl-7 col-sm-12">
									<div class="form-group">
										<div id="rzvy_stripe_plan_card_element">
											<!-- A Stripe Element will be inserted here. -->
										</div>
										<!-- Used to display form errors. -->
										<div id="rzvy_stripe_plan_card_errors" class="error mt-2" role="alert"></div>
									</div>
								  </div>
								</div>
								<?php 
							}else{ 
								?>
								<div class="row">
								  <div class="col-xl-7 col-sm-12">
									<div class="form-group">
									  <label class="form-label" for="rzvy-cardnumber"><?php if(isset($rzvy_translangArr['card_number'])){ echo $rzvy_translangArr['card_number']; }else{ echo $rzvy_defaultlang['card_number']; } ?></label>
									 <input maxlength="20" size="20" type="tel" placeholder="<?php if(isset($rzvy_translangArr['card_number'])){ echo $rzvy_translangArr['card_number']; }else{ echo $rzvy_defaultlang['card_number']; } ?>" class="form-control rzvy-input-class rzvy-card-num <?php echo $inputAlignment; ?>" name="rzvy-cardnumber" id="rzvy-cardnumber" value="" />					  
									</div>
								  </div>
								  <div class="col-xl col-sm-6">
									<div class="form-group">
									  <label class="form-label" for="rzvy-cardexmonth"><?php if(isset($rzvy_translangArr['month'])){ echo $rzvy_translangArr['month']; }else{ echo $rzvy_defaultlang['month']; } ?></label>
									  <input maxlength="2" type="tel" placeholder="<?php if(isset($rzvy_translangArr['mm'])){ echo $rzvy_translangArr['mm']; }else{ echo $rzvy_defaultlang['mm']; } ?>" class="form-control  rzvy-input-class <?php echo $inputAlignment; ?>" name="rzvy-cardexmonth" id="rzvy-cardexmonth" value="" />
									</div>
								  </div>
								  <div class="col-xl col-sm-6">
									<div class="form-group">
									  <label class="form-label" for="rzvy-cardexyear"><?php if(isset($rzvy_translangArr['year'])){ echo $rzvy_translangArr['year']; }else{ echo $rzvy_defaultlang['year']; } ?></label>
									  <input maxlength="4" type="tel" placeholder="<?php if(isset($rzvy_translangArr['yyyy'])){ echo $rzvy_translangArr['yyyy']; }else{ echo $rzvy_defaultlang['yyyy']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>" name="rzvy-cardexyear" id="rzvy-cardexyear" value="" />
									</div>
								  </div>
								  <div class="col-xl-7 col-sm-12">
									<div class="form-group">
									  <label class="form-label" for="rzvy-cardholdername"><?php if(isset($rzvy_translangArr['name_as_on_card'])){ echo $rzvy_translangArr['name_as_on_card']; }else{ echo $rzvy_defaultlang['name_as_on_card']; } ?></label>
									  <input type="text" placeholder="<?php if(isset($rzvy_translangArr['name_as_on_card'])){ echo $rzvy_translangArr['name_as_on_card']; }else{ echo $rzvy_defaultlang['name_as_on_card']; } ?>" class="form-control  rzvy-input-class <?php echo $inputAlignment; ?>" name="rzvy-cardholdername" id="rzvy-cardholdername" value="" />
									</div>
								  </div>
								  <div class="col-xl col-sm-6">
									<div class="form-group">
									  <label class="form-label" for="rzvy-cardcvv"><?php if(isset($rzvy_translangArr['cvv'])){ echo $rzvy_translangArr['cvv']; }else{ echo $rzvy_defaultlang['cvv']; } ?></label>
									  <input type="password" maxlength="4" size="4" placeholder="<?php if(isset($rzvy_translangArr['cvv'])){ echo $rzvy_translangArr['cvv']; }else{ echo $rzvy_defaultlang['cvv']; } ?>" class="form-control  rzvy-input-class <?php echo $inputAlignment; ?>"  name="rzvy-cardcvv" id="rzvy-cardcvv" value="" />
									</div>
								  </div>
								</div>
							<?php 
							} 
							?>
						</div>
						<?php 
						} else{
							?>
							<div class="rzvy_payment_methods_container row step-item <?php echo $labelAlignmentClassName; ?> mt-2">
								<div class="rzvy-payments ml-3">
									<input type="radio" class="rzvy-payment-method-check" id="rzvy-pay-at-venue" name="rzvy-payment-method-radio" value="pay-at-venue" checked style="display:none;" />
								</div>
							</div>
							<?php 
						}
					?></div>					
				</div>
			<div class="form-check mt-5">
				<div class="rzvy-terms-and-condition <?php echo $labelAlignmentClassName; ?>">				
				
					
					<label class="form-check-label" for="rzvy-tc-concent"><input class="form-check-input custom-control-input rzvy-tc-control-input" type="checkbox" name="read-and-agree" id="rzvy-tc-concent"><?php if(isset($rzvy_translangArr['i_read_and_agree_to_the'])){ echo $rzvy_translangArr['i_read_and_agree_to_the']; }else{ echo $rzvy_defaultlang['i_read_and_agree_to_the']; } ?> <a target="_blank" href="<?php $rzvy_terms_and_condition_link = $obj_settings->get_option("rzvy_terms_and_condition_link"); if($rzvy_terms_and_condition_link != ""){ echo $rzvy_terms_and_condition_link; }else{ echo "javascript:void(0)"; } ?>"><?php if(isset($rzvy_translangArr['terms_conditions'])){ echo $rzvy_translangArr['terms_conditions']; }else{ echo $rzvy_defaultlang['terms_conditions']; } ?></a><?php $rzvy_cookiesconcent_status = $obj_settings->get_option("rzvy_cookiesconcent_status"); if($rzvy_cookiesconcent_status == "Y"){ ?> <?php echo (isset($rzvy_translangArr['and']))?$rzvy_translangArr['and']:$rzvy_defaultlang['and']; ?> <a target="_blank" href="<?php $rzvy_privacy_and_policy_link = $obj_settings->get_option("rzvy_privacy_and_policy_link"); if($rzvy_privacy_and_policy_link != ""){ echo $rzvy_privacy_and_policy_link; }else{ echo "javascript:void(0)"; } ?>"><?php if(isset($rzvy_translangArr['privacy_and_policy'])){ echo $rzvy_translangArr['privacy_and_policy']; }else{ echo $rzvy_defaultlang['privacy_and_policy']; } ?></a><?php } ?></label>											
				</div>
			</div>														
		</div>
	</div>
	<div class="row my-2 mt-4">
		<div class="col-md-12">
			<div class="rzvy-steps-btn">				
				<a href="javascript:void(0)" id="rzvy-get-third-next-box-btn" class="btn btn-success next-step next-button rzvy_nextstep_btn pull-left"><i class="fa fa-angle-double-left" aria-hidden="true"></i>&nbsp;&nbsp;<?php if(isset($rzvy_translangArr['prev'])){ echo $rzvy_translangArr['prev']; }else{ echo $rzvy_defaultlang['prev']; } ?></a>
				<a id="rzvy_book_appointment_btn" href="javascript:void(0)" class="btn btn-success next-step next-button rzvy_nextstep_btn pull-right"><?php if(isset($rzvy_translangArr['proceed_to_checkout'])){ echo $rzvy_translangArr['proceed_to_checkout']; }else{ echo $rzvy_defaultlang['proceed_to_checkout']; } ?>&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
			</div>
		</div>
	</div>
	<?php 
}
/* Re-Fetch Payment Methods */
else if(isset($_POST['get_payment_methods'])){ 
	$show_hide_payatvenue = "";
	if($_SESSION['rzvy_cart_nettotal']>0){		?>
	<!-- Payment modes Start --->
	<div class="rzvy_payment_methods_container row step-item <?php echo $labelAlignmentClassName; ?>">
		<?php 
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
					<input type="radio" class="rzvy-payment-method-check form-check-input" id="rzvy-pay-at-venue" name="rzvy-payment-method-radio" value="pay-at-venue" checked />
					<label class="form-check-label" for="rzvy-pay-at-venue"><?php if(isset($rzvy_translangArr['pay_at_venue'])){ echo $rzvy_translangArr['pay_at_venue']; }else{ echo $rzvy_defaultlang['pay_at_venue']; } ?></label>
					</div>
				</div>
				<?php 
			} 
			if($obj_settings->get_option("rzvy_bank_transfer_payment_status") == "Y" && $rzvy_price_display=='Y'){ 
				?>
				<div class="col-sm-6">
					<div class="form-check custom inline">
					<input type="radio" class="rzvy-payment-method-check form-check-input" id="rzvy-bank-transfer" name="rzvy-payment-method-radio" value="bank transfer" <?php if($obj_settings->get_option("rzvy_pay_at_venue_status") != "Y"){ echo "checked"; } ?> data-toggle="rzvy-bank-payment"/>
					<label class="form-check-label" for="rzvy-bank-transfer"><?php if(isset($rzvy_translangArr['bank_transfer'])){ echo $rzvy_translangArr['bank_transfer']; }else{ echo $rzvy_defaultlang['bank_transfer']; } ?></label>
					</div>
				</div><?php 
			} 
			if($obj_settings->get_option("rzvy_paypal_payment_status") == "Y"  && $rzvy_price_display=='Y'){ 
				?>
				<div class="col-sm-6">
					<div class="form-check custom inline">
					<input type="radio" class="rzvy-payment-method-check form-check-input" id="rzvy-paypal-payment" name="rzvy-payment-method-radio" value="paypal" />
					<label class="form-check-label" for="rzvy-paypal-payment"><?php if(isset($rzvy_translangArr['paypal'])){ echo $rzvy_translangArr['paypal']; }else{ echo $rzvy_defaultlang['paypal']; } ?></label>
					</div>
				</div><?php 
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
					<input type="radio" class="rzvy-payment-method-check form-check-input" id="rzvy-card-payment" name="rzvy-payment-method-radio" value="<?php echo $payment_method; ?>" data-toggle="rzvy-card-payment"/>
					<label class="form-check-label" for="rzvy-card-payment"><?php if(isset($rzvy_translangArr['card_payment'])){ echo $rzvy_translangArr['card_payment']; }else{ echo $rzvy_defaultlang['card_payment']; } ?></label>
					</div>
				</div>
				<?php 
			} 
			?>
			</div>
		</div>
	</div>
	<div class="forms rzvy-radio-group-block <?php echo $inputAlignment; ?>">
		<div data-form="rzvy-bank-payment" class="form-item rzvy-bank-transfer-detail-box remove_payment_according_services_showhide rzvy_hide_show_payment_according_services " <?php if($obj_settings->get_option("rzvy_pay_at_venue_status") == "Y" || $obj_settings->get_option("rzvy_bank_transfer_payment_status") != "Y"){ echo "style='display:none'"; } ?>>
			<h2 class="fs-5"><?php if(isset($rzvy_translangArr['bank_details'])){ echo $rzvy_translangArr['bank_details']; }else{ echo $rzvy_defaultlang['bank_details']; } ?></h2>
			<div class="rzvy-table">
			  <table class="table">
				<tbody>
				  <tr>
					<td><strong><?php if(isset($rzvy_translangArr['bank_name'])){ echo $rzvy_translangArr['bank_name']; }else{ echo $rzvy_defaultlang['bank_name']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_bank_name"); ?></td>
					<td align="left"><strong><?php if(isset($rzvy_translangArr['account_name'])){ echo $rzvy_translangArr['account_name']; }else{ echo $rzvy_defaultlang['account_name']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_account_name"); ?></td>
				  </tr>
				  <tr>
					<td><strong><?php if(isset($rzvy_translangArr['account_number'])){ echo $rzvy_translangArr['account_number']; }else{ echo $rzvy_defaultlang['account_number']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_account_number"); ?></td>
					<td align="left"><strong><?php if(isset($rzvy_translangArr['branch_code'])){ echo $rzvy_translangArr['branch_code']; }else{ echo $rzvy_defaultlang['branch_code']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_branch_code"); ?></td>
				  </tr>
				  <tr>
					<td align="left"><strong><?php if(isset($rzvy_translangArr['ifsc_code'])){ echo $rzvy_translangArr['ifsc_code']; }else{ echo $rzvy_defaultlang['branch_code']; } ?>:</strong> <?php echo $obj_settings->get_option("rzvy_bank_transfer_ifsc_code"); ?></td>
				  </tr>
				</tbody>
			  </table>
			</div>
		</div>
		<div class="form-item card-payment rzvy-card-detail-box remove_payment_according_services_showhide rzvy_hide_show_payment_according_services" data-form="rzvy-card-payment">
			<p><?php if(isset($rzvy_translangArr['credit_card_details'])){ echo $rzvy_translangArr['credit_card_details']; }else{ echo $rzvy_defaultlang['credit_card_details']; } ?></p>
			<?php 
			if($obj_settings->get_option("rzvy_stripe_payment_status") == "Y" && $obj_settings->get_option("rzvy_authorizenet_payment_status") == "N" && $obj_settings->get_option("rzvy_twocheckout_payment_status") == "N"){ 
				?>
				<div class="row">
				  <div class="col-xl-7 col-sm-12">
					<div class="form-group">
						<div id="rzvy_stripe_plan_card_element">
							<!-- A Stripe Element will be inserted here. -->
						</div>
						<!-- Used to display form errors. -->
						<div id="rzvy_stripe_plan_card_errors" class="error mt-2" role="alert"></div>
					</div>
				  </div>
				</div>
				<?php 
			}else{ 
				?>
				<div class="row">
				  <div class="col-xl-7 col-sm-12">
					<div class="form-group">
					  <label class="form-label" for="rzvy-cardnumber"><?php if(isset($rzvy_translangArr['card_number'])){ echo $rzvy_translangArr['card_number']; }else{ echo $rzvy_defaultlang['card_number']; } ?></label>
					 <input maxlength="20" size="20" type="tel" placeholder="<?php if(isset($rzvy_translangArr['card_number'])){ echo $rzvy_translangArr['card_number']; }else{ echo $rzvy_defaultlang['card_number']; } ?>" class="form-control rzvy-input-class rzvy-card-num <?php echo $inputAlignment; ?>" name="rzvy-cardnumber" id="rzvy-cardnumber" value="" />					  
					</div>
				  </div>
				  <div class="col-xl col-sm-6">
					<div class="form-group">
					  <label class="form-label" for="rzvy-cardexmonth"><?php if(isset($rzvy_translangArr['month'])){ echo $rzvy_translangArr['month']; }else{ echo $rzvy_defaultlang['month']; } ?></label>
					  <input maxlength="2" type="tel" placeholder="<?php if(isset($rzvy_translangArr['mm'])){ echo $rzvy_translangArr['mm']; }else{ echo $rzvy_defaultlang['mm']; } ?>" class="form-control  rzvy-input-class <?php echo $inputAlignment; ?>" name="rzvy-cardexmonth" id="rzvy-cardexmonth" value="" />
					</div>
				  </div>
				  <div class="col-xl col-sm-6">
					<div class="form-group">
					  <label class="form-label" for="rzvy-cardexyear"><?php if(isset($rzvy_translangArr['year'])){ echo $rzvy_translangArr['year']; }else{ echo $rzvy_defaultlang['year']; } ?></label>
					  <input maxlength="4" type="tel" placeholder="<?php if(isset($rzvy_translangArr['yyyy'])){ echo $rzvy_translangArr['yyyy']; }else{ echo $rzvy_defaultlang['yyyy']; } ?>" class="form-control rzvy-input-class <?php echo $inputAlignment; ?>" name="rzvy-cardexyear" id="rzvy-cardexyear" value="" />
					</div>
				  </div>
				  <div class="col-xl-7 col-sm-12">
					<div class="form-group">
					  <label class="form-label" for="rzvy-cardholdername"><?php if(isset($rzvy_translangArr['name_as_on_card'])){ echo $rzvy_translangArr['name_as_on_card']; }else{ echo $rzvy_defaultlang['name_as_on_card']; } ?></label>
					  <input type="text" placeholder="<?php if(isset($rzvy_translangArr['name_as_on_card'])){ echo $rzvy_translangArr['name_as_on_card']; }else{ echo $rzvy_defaultlang['name_as_on_card']; } ?>" class="form-control  rzvy-input-class <?php echo $inputAlignment; ?>" name="rzvy-cardholdername" id="rzvy-cardholdername" value="" />
					</div>
				  </div>
				  <div class="col-xl col-sm-6">
					<div class="form-group">
					  <label class="form-label" for="rzvy-cardcvv"><?php if(isset($rzvy_translangArr['cvv'])){ echo $rzvy_translangArr['cvv']; }else{ echo $rzvy_defaultlang['cvv']; } ?></label>
					  <input type="password" maxlength="4" size="4" placeholder="<?php if(isset($rzvy_translangArr['cvv'])){ echo $rzvy_translangArr['cvv']; }else{ echo $rzvy_defaultlang['cvv']; } ?>" class="form-control  rzvy-input-class <?php echo $inputAlignment; ?>"  name="rzvy-cardcvv" id="rzvy-cardcvv" value="" />
					</div>
				  </div>
				</div>
			<?php 
			} 
			?>
		</div>
		<?php 
		} else{
			?>
			<div class="row mt-2">
				<div class="rzvy-payments ml-3">
					<input type="radio" class="rzvy-payment-method-check" id="rzvy-pay-at-venue" name="rzvy-payment-method-radio" value="pay-at-venue" checked style="display:none;" />
				</div>
			</div>
			<?php 
		}
	?></div><?php 	
}
/* cart: apply referral discount coupon */
else if(isset($_POST['apply_referral_discount'])){
	$check_referral_coupon_code_exist = $obj_frontend->check_referral_coupon_code_exist($_SESSION["customer_id"], $_POST["ref_discount_coupon"]);
	if(mysqli_num_rows($check_referral_coupon_code_exist)>0){
		$discount_value = mysqli_fetch_array($check_referral_coupon_code_exist);
		if($discount_value["used"] == "N"){
			$_SESSION["rzvy_applied_ref_customer_id"] = $discount_value["id"];
			echo "applied";
		}else{
			echo "used";
		}
	}else{
		echo "notexist";
	}
}

/** First step content from here */
else if(isset($_POST['get_first_step_box'])){
	?>
	<!--<div class="row mb-3">
		<div class="col-md-12">
			<div class="rzvy-steps-btn">
				<a href="javascript:void(0)" id="rzvy-get-second-next-box-btn" class="btn btn-common next-step next-button pull-right rzvy_nextstep_btn rounded-0" style="width:158.4px"><?php if(isset($rzvy_translangArr['next'])){ echo $rzvy_translangArr['next']; }else{ echo $rzvy_defaultlang['next']; } ?> <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
			</div>
		</div>
	</div>-->
	<?php 
	$rzvy_booking_first_selection_as = $obj_settings->get_option("rzvy_booking_first_selection_as"); 
	if($rzvy_booking_first_selection_as != "allservices"){
		if($rzvy_parent_category == "Y"){
			$all_categories = $obj_frontend->get_all_parent_categories(); 
			?>
			<div class="mt-4 rzvy-pcategory-container <?php echo $alignmentClass; ?>">
				<?php 
				$i=0;
				$total_cat = mysqli_num_rows($all_categories);
				if($total_cat>0){
					?>
					<h4 class="<?php echo $inputAlignment; ?>">
						<?php if(isset($rzvy_translangArr['choose_category_of_your_service'])){ echo $rzvy_translangArr['choose_category_of_your_service']; }else{ echo $rzvy_defaultlang['choose_category_of_your_service']; } ?>
					</h4>
					 <div class="services <?php echo $rzvyrounded;?>">
						<div class="owl-carousel" data-items="4" data-items-lg="4" data-items-md="3" data-items-sm="1" data-items-ssm="1" data-margin="24" data-dots="true" data-nav="true" autoplay="false" data-loop="false">
						<?php 
						while($category = mysqli_fetch_array($all_categories)){ 
							
							?>
							<div class="item">
								<figure class="<?php echo $insideContentAlignment; ?>">
									<?php $rzvyptclass = ''; if($rzvy_show_parentcategory_image == "Y"){ ?><img src="<?php if($category['image'] != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$category['image'])){ echo SITE_URL."uploads/images/".$category['image']; }else{ echo SITE_URL."includes/images/noimage.png"; } ?>" ><?php }else{ $rzvyptclass = 'rzvy-pt-figcaption'; } ?>
									<figcaption class="<?php echo $insideContentAlignment.' '.$rzvyptclass; ?>">
									  <h3 class="<?php echo $insideContentAlignment; ?>"><?php echo ucwords($category["cat_name"]); ?></h3>
									  <input type="radio"  name="rzvy-pcategories-radio" class="rzvy-pcategories-selection" id="rzvy-pcategories-selection-<?php echo $category["id"]; ?>" data-id="<?php echo $category["id"]; ?>">						 
									</figcaption>
								 </figure>						
							</div>
							<?php 
						} ?></div></div><?php 	
				}else{ 
					?>
					<h5 class="<?php echo $inputAlignment; ?>">
						<?php if(isset($rzvy_translangArr['please_configure_first_services_from_admin_area'])){ echo $rzvy_translangArr['please_configure_first_services_from_admin_area']; }else{ echo $rzvy_defaultlang['please_configure_first_services_from_admin_area']; } ?>
					</h5>
					<?php 
				} 
				?>
			</div>
			<div class="rzvy-category-container">
				
			</div>
			<?php 
		}else{
			$all_categories = $obj_frontend->get_all_categories(); 
			?>
			<div class="step-item <?php echo $alignmentClass; ?>  rzvy-category-container  mt-4">
				<h4 class="<?php echo $inputAlignment; ?> rzvy_categories_html_content_scroll">
					<?php if(isset($rzvy_translangArr['what_type_of_service'])){ echo $rzvy_translangArr['what_type_of_service']; }else{ echo $rzvy_defaultlang['what_type_of_service']; } ?>
				</h4>
				<div class="services <?php echo $rzvyrounded;?>">
					<div class="owl-carousel" data-items="4" data-items-lg="4" data-items-md="3" data-items-sm="1" data-items-ssm="1" data-margin="24" data-dots="true" data-nav="true" autoplay="false" data-loop="false">
						<?php 
						$i=0;
						$total_cat = mysqli_num_rows($all_categories);
						if($total_cat>0){
							while($category = mysqli_fetch_array($all_categories)){ 
								?>
								<div class="item">
									<figure class="<?php echo $insideContentAlignment; ?>">
										<?php $rzvyptclass = ''; if($rzvy_show_category_image == "Y"){ ?><img src="<?php if($category['image'] != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$category['image'])){ echo SITE_URL."uploads/images/".$category['image']; }else{ echo SITE_URL."includes/images/noimage.png"; } ?>" ><?php }else{ $rzvyptclass = 'rzvy-pt-figcaption'; } ?>
										<figcaption class="<?php echo $insideContentAlignment.' '.$rzvyptclass; ?>">
										  <h3 class="<?php echo $insideContentAlignment; ?>"><?php echo ucwords($category["cat_name"]); ?></h3>
										  <input type="radio" name="rzvy-categories-radio"  class="rzvy-categories-radio-change" id="rzvy-categories-radio-<?php echo $category["id"]; ?>" data-id="<?php echo $category["id"]; ?>">	
										</figcaption>
									</figure>	
								</div>
								<?php 
							} 
						}else{ 
							?>
							<div class="step-item <?php echo $alignmentClass; ?>">
								<h5 class="<?php echo $inputAlignment; ?>">
									<?php if(isset($rzvy_translangArr['please_configure_first_services_from_admin_area'])){ echo $rzvy_translangArr['please_configure_first_services_from_admin_area']; }else{ echo $rzvy_defaultlang['please_configure_first_services_from_admin_area']; } ?>
								</h5>
							</div>
							<?php 
						} 
						?>
					</div>
				</div>
			</div>
			<?php 
		}
		?>
		<div class="row rzvy-services-container <?php echo $alignmentClass; ?>">
			<!-- Services Goes Here -->
		</div>
		<?php 
	}else{ 
		?>
		<div class="step-item <?php echo $alignmentClass; ?> rzvy_show_hide_services mt-4" style="display:block">
			<h4 class="<?php echo $inputAlignment; ?>">
				<?php if(isset($rzvy_translangArr['tell_us_about_your_service'])){ echo $rzvy_translangArr['tell_us_about_your_service']; }else{ echo $rzvy_defaultlang['tell_us_about_your_service']; } ?>
			</h4>
		</div>
		<div class="rzvy-services-container <?php if($rzvy_services_listing_view != "L"){ echo $alignmentClass; } ?>">
			<?php if($rzvy_services_listing_view == "L"){ ?>
			<div class="rzvy-listview mb-3">
			<?php } ?>
			<div class="services <?php echo $rzvyrounded;?>">
			<?php 
			$all_services = $obj_frontend->get_services_without_cat_id();
			$nonlocation_services = 0; 
			if(mysqli_num_rows($all_services)>0){
				?>
				<div class="owl-carousel" data-items="4" data-items-lg="4" data-items-md="2" data-items-sm="2" data-items-ssm="1" data-margin="24" data-dots="true" data-nav="true" autoplay="false" data-loop="false"><?php 
				while($service = mysqli_fetch_array($all_services)){
					if(isset($service['locations']) && $service['locations']!='' && $rzvy_location_selector_status=='Y'){
						$service_locations = explode(',',$service['locations']);
						if(isset($_SESSION['rzvy_location_selector_zipcode']) && !in_array($_SESSION['rzvy_location_selector_zipcode'],$service_locations)){ $nonlocation_services++; continue; }
						
					}
					if($rzvy_services_listing_view == "L"){  
						?>
						<div id="rzvy-services-radio-<?php echo $service["id"]; ?>" class="rzvy-listview-list my-1 rzvy-services-radio-change <?php if($_SESSION['rzvy_cart_service_id'] == $service['id']){ echo "list_active";} ?>" data-id="<?php echo $service["id"]; ?>">
							<div class="rzvy-listview-list-data">
								<?php 
								if($rzvy_show_service_image == "Y"){
									?>
									<div class="rzvy-listview-list-image">
										<img style="width: inherit;" src="<?php if($service['image'] != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$service['image'])){ echo SITE_URL."uploads/images/".$service['image']; }else{ echo SITE_URL."includes/images/noimage.png"; } ?>" />
									</div>
									<?php 
								}
								?>
								<div class="rzvy-listview-list-info px-1">
									<div class="rzvy-listview-list-title">
										<?php 
										echo $service['title']." ";
										if($service['duration']>0){
											?><span class="rzvy-listview-list-price"><i class="fa fa-clock-o"></i> <?php echo $service['duration']." Min."; ?></span><?php 
										}
										if($rzvy_price_display=='Y'){ ?><span class="rzvy-listview-list-price"><i class="fa fa-tag"></i> <?php 
											if($service['rate']>0){ 
												echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($service['rate'],2,".",''));
											}else{ 
												echo (isset($rzvy_translangArr['free']))?$rzvy_translangArr['free']:$rzvy_defaultlang['free']; 
										} ?></span><?php } ?>
									</div>
									<div class="rzvy-listview-list-sub-info">
										<div><?php echo $service['description']; ?></div>
									</div>
								</div>
								<div class="rzvy-listview-list-badge-main">
									<?php if($service['badge']=="Y"){ ?>
										<div class="rzvy-listview-list-badge"><?php echo $service['badge_text']; ?></div>
									<?php } ?>
								</div>
							</div>
						</div>
						<?php 
					} else {
						?>
						<div class="item">
							<figure>
								<?php $rzvyptclass = '';  if($rzvy_show_service_image == "Y"){ ?><img src="<?php if($service['image'] != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$service['image'])){ echo SITE_URL."uploads/images/".$service['image']; }else{ echo SITE_URL."includes/images/noimage.png"; } ?>"><?php }else{ $rzvyptclass = 'rzvy-pt-figcaption'; } ?>	
								<figcaption class="<?php echo $rzvyptclass = 'rzvy-pt-figcaption'; ?>">
									<?php if($service['badge']=="Y"){ ?>
										<span class="tag"><?php echo $service['badge_text']; ?></span>
									<?php } ?>
									<h3><?php echo ucwords($service["title"]); ?></h3>
									<?php if($service['description']!=""){ ?>
										<p><?php if(strlen($service['description'])<=45){ echo $service['description']."..."; }else{ echo substr(ucwords($service['description']), 0, 45)."..."; } ?></p>
									<?php } ?>
									<div class="service-meta">
										<?php if($service['duration']>0){ ?><span class="<?php echo ($service['rate']>0 && $rzvy_price_display=='Y')?"pull-left":"text-center"; ?>"><i class="fa fa-clock-o"></i> <?php echo $service['duration']." Min."; ?></span><?php } if($rzvy_price_display=='Y'){ ?><span class="<?php echo ($service['duration']>0)?"pull-right":"text-center"; ?>"><i class="fa fa-tag"></i> <?php if($service['rate']>0){ echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($service['rate'],2,".",'')); }else{ if(isset($rzvy_translangArr['free'])){ echo $rzvy_translangArr['free']; }else{ echo $rzvy_defaultlang['free']; } } ?></span><?php } ?>
									 </div>
									<input type="radio" name="rzvy-services-radio" class="rzvy-services-radio-change" id="rzvy-services-radio-<?php echo $service["id"]; ?>" data-id="<?php echo $service["id"]; ?>">
									<a href="javascript:void(0);" class="read-more" data-bs-toggle="offcanvas" data-bs-target="#rzvy-view-service-modal-<?php echo $service['id']; ?>" aria-controls="<?php echo ucwords($service["title"]); ?>"><?php if(isset($rzvy_translangArr['read_more'])){ echo $rzvy_translangArr['read_more']; }else{ echo $rzvy_defaultlang['read_more']; } ?></a>
								</figcaption>
							</figure>
						</div>
						<?php 
					}
				} ?>
				</div>					
					<?php 
				if($nonlocation_services==mysqli_num_rows($all_services)){
					?>
					<h5 class="<?php echo $inputAlignment; ?>"><?php if(isset($rzvy_translangArr['there_is_no_services_for_this_location'])){ echo $rzvy_translangArr['there_is_no_services_for_this_location']; }else{ echo $rzvy_defaultlang['there_is_no_services_for_this_location']; } ?></h5>
					<?php
				}
			}else{
				?>
				<div class="step-item">
					<h5 class="<?php echo $inputAlignment; ?>">
						<?php if(isset($rzvy_translangArr['there_is_no_services_for_this_business'])){ echo $rzvy_translangArr['there_is_no_services_for_this_business']; }else{ echo $rzvy_defaultlang['there_is_no_services_for_this_business']; } ?>
					</h5>
				</div>
				<?php
			}
			?>
			<?php if($rzvy_services_listing_view == "L"){ ?>
			</div>
			<?php } ?>
		</div>
	</div> 
	<?php
		$all_services = $obj_frontend->get_services_without_cat_id();
			$nonlocation_services = 0; 
			if(mysqli_num_rows($all_services)>0){
				while($service = mysqli_fetch_array($all_services)){
				?>
				<div class="offcanvas offcanvas-end" tabindex="-1" id="rzvy-view-service-modal-<?php echo $service["id"]; ?>">
				  <div class="offcanvas-header">
					<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				  </div>
				  <div class="offcanvas-body">
					<?php 
					$service_image = $service['image'];
					if($service_image != '' && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$service_image)){
						$serviceimage = SITE_URL."uploads/images/".$service_image;
					}else{
						$serviceimage = SITE_URL."includes/images/noimage.png";
					}
					$otherdetailpart = "12";
					if($rzvy_show_service_image == "Y"){
						$otherdetailpart = "9";
						?>
						<div class="rzvy-image">
							<img src="<?php echo $serviceimage; ?>"/>
						</div>
						<?php
					}
					?>
					<h2><?php echo $service['title']; ?></h2>
					<p><?php echo ucfirst($service['description']); ?></p>
					<div class="service-meta">
						<?php if($rzvy_price_display=='Y'){ ?> <span><i class="fa fa-fw fa-money"></i>&nbsp;&nbsp;<strong><?php if(isset($rzvy_translangArr['rate_ad'])){ echo $rzvy_translangArr['rate_ad']; }else{ echo $rzvy_defaultlang['rate_ad']; } ?></strong>&nbsp;<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,number_format($service['rate'],2,".",'')); ?>	</span><?php } ?>
						<span><i class="fa fa-fw fa-clock-o"></i>&nbsp;&nbsp;<strong><?php if(isset($rzvy_translangArr['duration_ad'])){ echo $rzvy_translangArr['duration_ad']; }else{ echo $rzvy_defaultlang['duration_ad']; } ?></strong>&nbsp;<?php echo $service['duration']." Minutes"; ?></span>
						<span><i class="fa fa-fw fa-map-marker"></i>&nbsp;&nbsp;<strong><?php if(isset($rzvy_translangArr['service_locations'])){ echo $rzvy_translangArr['service_locations']; }else{ echo $rzvy_defaultlang['service_locations']; } ?> </strong>&nbsp;<?php if(isset($service['locations']) && $service['locations']!=''){ echo $service['locations']; }else{ if(isset($rzvy_translangArr['all_over'])){ echo $rzvy_translangArr['all_over']; }else{ echo $rzvy_defaultlang['all_over']; } } ?></span>	
					</div>
				  </div>
				</div>
				<?php 
			}	
		}
	}
	?>
	<div class="rzvy-addons-container">
		<!-- Service Detail & Addons Goes Here -->
	</div>
	<div class="form-controls">
		<div class="rzvy-steps-btn">
			<a href="javascript:void(0)" id="rzvy-get-second-next-box-btn" class="next-fieldset simple-form-button btn btn-success next-step next-button rzvy_nextstep_btn rzvy_nextstep_btn_bottom" style="display:none"><?php if(isset($rzvy_translangArr['next'])){ echo $rzvy_translangArr['next']; }else{ echo $rzvy_defaultlang['next']; } ?> &nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
		</div>
	</div>
	<?php 
}

elseif(isset($_POST["make_multi_and_single_qty_addons_selected"])){
	echo json_encode($_SESSION['rzvy_cart_items']);
}

else if(isset($_POST["check_cart_amount"])){ 
	$minmum_cart_value_to_pay = $obj_settings->get_option("rzvy_minmum_cart_value_to_pay");
	if($minmum_cart_value_to_pay>=0 && is_numeric($minmum_cart_value_to_pay)){
		/** Nothing to do here **/
	}else{
		$minmum_cart_value_to_pay = 0;
	}

	if($_SESSION['rzvy_cart_nettotal']>= $minmum_cart_value_to_pay){
		echo "sufficient";
	}else{
		if($_SESSION['rzvy_cart_subtotal']>= $minmum_cart_value_to_pay){
			echo "sufficient";
		}else{
			echo $minmum_cart_value_to_pay;
		}
	}
}
/* Get available coupons for customer ajax */
else if(isset($_POST['remove_applied_rcoupon'])){
	$_SESSION["rzvy_applied_ref_customer_id"] = '';
	$rzvy_tax_status = $obj_settings->get_option('rzvy_tax_status');
	$rzvy_tax_type = $obj_settings->get_option('rzvy_tax_type');
	$rzvy_tax_value = $obj_settings->get_option('rzvy_tax_value');
	$subtotal = $_SESSION['rzvy_cart_subtotal'];
	if($subtotal>0){
		$rzvy_referral_discount_type = $obj_settings->get_option('rzvy_referral_discount_type');
		$rzvy_referral_discount_value = $obj_settings->get_option('rzvy_referral_discount_value');
		$obj_frontend->rzvy_cart_item_calculation($subtotal, $rzvy_tax_status, $rzvy_tax_type, $rzvy_tax_value, $rzvy_referral_discount_type, $rzvy_referral_discount_value);
	}
}
/** Check Referal code Ajax **/
else if(isset($_POST["apply_loyalty_point"])){
	if(isset($_SESSION["login_type"])){ 
		if($_SESSION['login_type'] == "customer") { 
			$available_lpoints = $obj_lpoint->get_available_points_customer($_SESSION["customer_id"]);
			$lpoint_value = $obj_settings->get_option("rzvy_perbooking_loyalty_point_value");
			$calculatelp = ($available_lpoints*$lpoint_value);
			$calculatelp = number_format($calculatelp,2,".",'');
			$_SESSION['rzvy_lpoint_total'] = $available_lpoints;
			$_SESSION['rzvy_lpoint_price'] = $calculatelp;
			$_SESSION['rzvy_lpoint_value'] = $lpoint_value;
			$_SESSION['rzvy_lpoint_checked'] = $_POST['lpoint_check'];
		}else{
			$_SESSION['rzvy_lpoint_checked'] = false;
		}
	}else{
		$_SESSION['rzvy_lpoint_checked'] = false;
	}
}

else if(isset($_POST["get_subcat_by_pcid"])){
	$_SESSION['rzvy_cart_parent_category_id'] = $_POST['id'];
	$all_categories = $obj_frontend->get_all_categories_by_pcid($_POST['id']); 
	?>
	<div class="mt-4 <?php echo $alignmentClass; ?>">
		<h4 <?php echo $labelAlignmentClass; ?> ><?php if(isset($rzvy_translangArr['what_type_of_service'])){ echo $rzvy_translangArr['what_type_of_service']; }else{ echo $rzvy_defaultlang['what_type_of_service']; } ?></h4>
		<?php 
		$i=0;
		$total_cat = mysqli_num_rows($all_categories);
		if($total_cat>0){ ?>
			<div class="services <?php echo $rzvyrounded;?>">
              <div class="owl-carousel" data-items="4" data-items-lg="4" data-items-md="2" data-items-sm="2" data-items-ssm="1" data-margin="24" data-dots="true" data-nav="true" autoplay="false" data-loop="false">
			<?php 
			while($category = mysqli_fetch_array($all_categories)){ 
				?>
				<div class="item">
					  <figure>
					  <?php $rzvyptclass = '';  if($rzvy_show_category_image == "Y"){ ?><img src="<?php if($category['image'] != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$category['image'])){ echo SITE_URL."uploads/images/".$category['image']; }else{ echo SITE_URL."includes/images/noimage.png"; } ?>" ><?php }else{ $rzvyptclass = 'rzvy-pt-figcaption'; } ?>
					<figcaption class="<?php echo $insideContentAlignment.' '.$rzvyptclass; ?>">
						<h3><?php echo ucwords($category["cat_name"]); ?></h3>
						<input type="radio" name="rzvy-categories-radio" class="<?php echo $inputAlignment; ?> rzvy-categories-radio-change" id="rzvy-categories-radio-<?php echo $category["id"]; ?>" data-id="<?php echo $category["id"]; ?>">
					</figcaption>
					</figure>
				</div>
				<?php 
			} ?>
				</div>
			</div><?php 			
		}else{ 
			?>
			<h5 class="step-title">
				<?php if(isset($rzvy_translangArr['please_configure_first_services_from_admin_area'])){ echo $rzvy_translangArr['please_configure_first_services_from_admin_area']; }else{ echo $rzvy_defaultlang['please_configure_first_services_from_admin_area']; } ?>
			</h5>
			<?php 
		} 
		?>
	</div>
	<?php 
}
/** Set staff id in session on selection any slot */
else if(isset($_POST["set_staff_according_any"])){ 
	$_SESSION['rzvy_staff_id'] = $_POST["id"];
}
/* Get available slots ajax */
else if(isset($_POST['get_slots_any_staff'])){ 
	$rzvy_settings_timezone = $obj_settings->get_option("rzvy_timezone");
	$rzvy_server_timezone = date_default_timezone_get();
	$currDateTime_withTZ = $obj_settings->get_current_time_according_selected_timezone($rzvy_server_timezone,$rzvy_settings_timezone); 
	
	$slotof = $_POST['slots_of'];
	$selected_date = date("Y-m-d",$currDateTime_withTZ);
	if(isset($rzvy_translangArr['no_free_slots_today'])){ $noslot_message = $rzvy_translangArr['no_free_slots_today']; }else{ $noslot_message = $rzvy_defaultlang['no_free_slots_today']; }
	
	if($slotof=='tomorrow'){
		$selected_date = date("Y-m-d", strtotime('+1 days',strtotime($selected_date)));
		if(isset($rzvy_translangArr['no_free_slots_tomorrow'])){ $noslot_message = $rzvy_translangArr['no_free_slots_tomorrow']; }else{ $noslot_message = $rzvy_defaultlang['no_free_slots_tomorrow']; }
	}	
	$servicestaffs = array();
	foreach($_POST['staff_ids'] as $staffid){
		$isOffDay = $obj_slots->isDayOffCheck($selected_date,$_SESSION['rzvy_cart_service_id'], $staffid); 
		if($isOffDay){
			continue;
		}
		$servicestaffs[] = $staffid;
	}
	if(sizeof($servicestaffs)==0){ ?>
		<div class="pt-1 pb-1 pl-4 pr-4 col-md-8 mx-auto whitebox">
			<div class="row">
				<div class="col-md-12 rzvy-sm-box mb-3 pt-3 text-center <?php echo $labelAlignmentClassName; ?>">
					<h6><i class="fa fa-calendar"></i> <?php echo date($rzvy_date_format, strtotime($selected_date)); ?></h6>
				</div>
			</div>
			<div class="col-md-12 rzvy-sm-box rzvy_slot_new text-center">
				<h6><?php echo $noslot_message; ?></h6>
			</div>
		</div><?php 	die();	
	}
	
	$rzvy_slotsof_css = '';
	$rzvy_staff_noneslot_count = 0;
	
	$rzvy_hide_already_booked_slots_from_frontend_calendar = $obj_settings->get_option('rzvy_hide_already_booked_slots_from_frontend_calendar');
	$rzvy_minimum_advance_booking_time = $obj_settings->get_option('rzvy_minimum_advance_booking_time');
	$rzvy_maximum_advance_booking_time = $obj_settings->get_option('rzvy_maximum_advance_booking_time');
	
	$rzvy_gc_status = $obj_settings->get_option('rzvy_gc_status');
	$rzvy_gc_twowaysync = $obj_settings->get_option('rzvy_gc_twowaysync');
	$rzvy_gc_clientid = $obj_settings->get_option('rzvy_gc_clientid');
	$rzvy_gc_clientsecret = $obj_settings->get_option('rzvy_gc_clientsecret');
	$rzvy_gc_accesstoken = $obj_settings->get_option('rzvy_gc_accesstoken');
	$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
	
	/** check for GC bookings START **/
	$gc_twowaysync_eventsArr = array();	
	if($rzvy_gc_status == "Y" && $rzvy_gc_twowaysync == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != ""){
		$getNewTime = new \DateTime('now', new DateTimeZone($rzvy_settings_timezone));
		$timezoneOffset = $getNewTime->format('P');
		
		include(dirname(dirname(dirname(__FILE__)))."/includes/google-calendar/vendor/autoload.php");
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
			$obj_settings->update_option('rzvy_gc_accesstoken',base64_encode(serialize($newAccessToken)));
		}
		$service = new Google_Service_Calendar($client);

		$calendarId = (($obj_settings->get_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_option('rzvy_gc_calendarid'):'primary');
		$optParams = array(
		  'orderBy' => 'startTime',
		  'singleEvents' => true,
		  'timeZone' => $rzvy_settings_timezone,
		  'timeMin' => $selected_date.'T00:00:00'.$timezoneOffset,
		  'timeMax' => $selected_date.'T23:59:59'.$timezoneOffset,
		);
		$results = $service->events->listEvents($calendarId, $optParams);
		$events = $results->getItems();

		if (!empty($events)) {
			foreach ($events as $event) {
				if(!isset($event->transparency) || (isset($event->transparency) && $event->transparency!='transparent')){			
					$EventStartTime = substr($event->start->dateTime, 0, 19);
					$EventEndTime = substr($event->end->dateTime, 0, 19);
					$gcEventArr = array();
					$gcEventArr['start'] = date("Y-m-d H:i", strtotime(str_replace("T"," ",$EventStartTime)));
					$gcEventArr['end'] = date("Y-m-d H:i", strtotime(str_replace("T"," ",$EventEndTime)));
					array_push($gc_twowaysync_eventsArr, $gcEventArr);
				}
			}
		}
	}
	/** check for GC bookings END **/
	?>
	<div class="pt-1 pb-1 pl-4 pr-4 col-md-8 mx-auto whitebox">
		<div class="row col-md-8 mx-auto">
			<div class="col-md-12 rzvy-sm-box mb-2 text-center pt-3">
				<h6><i class="fa fa-calendar"></i> <?php echo date($rzvy_date_format, strtotime($selected_date)); ?></h6>
			</div>
		</div>
	<?php 	
	foreach($servicestaffs as $staffid){
		
		$obj_slots->staff_id = $staffid;
		$isEndTime = false;
		$available_slots = $obj_slots->generate_available_slots_dropdown($time_interval, $rzvy_time_format, $selected_date, $advance_bookingtime, $currDateTime_withTZ, $isEndTime, $_SESSION['rzvy_cart_service_id'], $_SESSION['rzvy_cart_total_addon_duration']);
		
		
		$no_booking = $available_slots['no_booking'];
		if($available_slots['no_booking']<0){
			$no_booking = 0;
		}

		
		/** check for staff GC bookings START **/
		$obj_settings->staff_id = $staffid;
		$rzvy_staff_nameinfo = $obj_settings->get_staff_name($staffid);
		
		if(mysqli_num_rows($rzvy_staff_nameinfo)>0){
			$staffinfovalue=mysqli_fetch_array($rzvy_staff_nameinfo);
			$rzvy_staff_name =  $staffinfovalue['firstname'].' '.$staffinfovalue['lastname'];
			
			if($staffinfovalue['image'] != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$staffinfovalue['image'])){
				$rzvy_staff_image = SITE_URL."uploads/images/".$staffinfovalue['image']; 
			}else{ 
				$rzvy_staff_image = SITE_URL."includes/images/noimage.png";
			}
		}else{
			$rzvy_staff_name =  '';
			$rzvy_staff_image = SITE_URL."includes/images/noimage.png";
		}
		if($rzvy_show_staff_image == "Y"){
			$rzvy_staff_image = '<img src="'.$rzvy_staff_image.'" width="35px" height="35px">';
		}else{
			$rzvy_staff_image = '<i class="fa fa-user"></i>';
		}
		
		
		
		
		$rzvy_gc_status = $obj_settings->get_staff_option('rzvy_gc_status');
		$rzvy_gc_twowaysync = $obj_settings->get_staff_option('rzvy_gc_twowaysync');
		$rzvy_gc_accesstoken = $obj_settings->get_staff_option('rzvy_gc_accesstoken');
		$rzvy_gc_accesstoken = base64_decode($rzvy_gc_accesstoken);
		
		if($rzvy_gc_status == "Y" && $rzvy_gc_twowaysync == "Y" && $rzvy_gc_clientid != "" && $rzvy_gc_clientsecret != "" && $rzvy_gc_accesstoken != ""){
			$getNewTime = new \DateTime('now', new DateTimeZone($rzvy_settings_timezone));
			$timezoneOffset = $getNewTime->format('P');
			
			include(dirname(dirname(dirname(__FILE__)))."/includes/google-calendar/vendor/autoload.php");
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

			$calendarId = (($obj_settings->get_staff_option('rzvy_gc_calendarid')!= "")?$obj_settings->get_staff_option('rzvy_gc_calendarid'):'primary');
			$optParams = array(
			  'orderBy' => 'startTime',
			  'singleEvents' => true,
			  'timeZone' => $rzvy_settings_timezone,
			  'timeMin' => $selected_date.'T00:00:00'.$timezoneOffset,
			  'timeMax' => $selected_date.'T23:59:59'.$timezoneOffset,
			);
			$results = $service->events->listEvents($calendarId, $optParams);
			$events = $results->getItems();

			if (!empty($events)) {
				foreach ($events as $event) {
					if(!isset($event->transparency) || (isset($event->transparency) && $event->transparency!='transparent')){			
						$EventStartTime = substr($event->start->dateTime, 0, 19);
						$EventEndTime = substr($event->end->dateTime, 0, 19);
						$gcEventArr = array();
						$gcEventArr['start'] = date("Y-m-d H:i", strtotime(str_replace("T"," ",$EventStartTime)));
						$gcEventArr['end'] = date("Y-m-d H:i", strtotime(str_replace("T"," ",$EventEndTime)));
						array_push($gc_twowaysync_eventsArr, $gcEventArr);
					}
				}
			}
		}
	?>
		<div class="row slot_refresh_div px-5 rzvy_staff_<?php echo $slotof.$staffid;?>">
			<div class="row col-md-12">
				<div class="col-md-12 rzvy-sm-box mb-1 py-2 <?php echo $inputAlignment;?>">
					<label><b><?php echo $rzvy_staff_image.' '.$rzvy_staff_name; ?></b></label>
				</div>
			</div>
			<?php 
			/** time slots **/
			if(isset($available_slots['slots']) && sizeof($available_slots['slots'])>0){
				$i = 1;
				$j = 0;
				foreach($available_slots['slots'] as $slot){
					$no_curr_boookings = $obj_slots->get_slot_bookings($selected_date." ".$slot,$_SESSION['rzvy_cart_service_id']);
					$bookings_blocks = $obj_slots->get_bookings_blocks($selected_date, $slot, $available_slots["serviceaddonduration"]);
					if(!$bookings_blocks){
						continue;
					}else{
						$booked_slot_exist = false;
						foreach($gc_twowaysync_eventsArr as $event){
							if(strtotime($event["start"]) <= strtotime($selected_date." ".$slot) && strtotime($event["end"]) > strtotime($selected_date." ".$slot)){
								$no_curr_boookings += 1;
							}
							if(strtotime($event["start"]) <= strtotime($selected_date." ".$slot) && strtotime($event["end"]) > strtotime($selected_date." ".$slot) && $no_booking==0){
								$booked_slot_exist = true;
								continue;
							} 
							if(strtotime($event["start"]) <= strtotime($selected_date." ".$slot) && strtotime($event["end"]) > strtotime($selected_date." ".$slot) && $no_booking!=0 && $no_curr_boookings>=$no_booking){
								$booked_slot_exist = true;
								continue;
							} 
						}
							
						$new_endtime_timestamp = strtotime("+".$available_slots["serv_timeinterval"]." minutes", strtotime($selected_date." ".$slot));
						$new_starttime_timestamp = strtotime($selected_date." ".$slot);
											
						foreach($available_slots['booked'] as $bslot){
							if($bslot["start_time"] <= strtotime($selected_date." ".$slot) && $bslot["end_time"] > strtotime($selected_date." ".$slot) && $no_booking==0){
								$booked_slot_exist = true;
								continue;
							} 
							if($bslot["start_time"] <= strtotime($selected_date." ".$slot) && $bslot["end_time"] > strtotime($selected_date." ".$slot) && $no_booking!=0 && $no_curr_boookings>=$no_booking){
								$booked_slot_exist = true;
								continue;
							} 
							
							if($new_starttime_timestamp <= $bslot["start_time"] && $new_endtime_timestamp > $bslot["start_time"] && $no_booking==0){
								$booked_slot_exist = true;
								continue;
							}
							
							if($new_starttime_timestamp <= $bslot["start_time"] && $new_endtime_timestamp > $bslot["start_time"] && $no_booking!=0){
							    $no_curr_boookings = $no_curr_boookings+1;
							    if($no_curr_boookings>=$no_booking){
    								$booked_slot_exist = true;
    								continue;
							    }
							} 
							if($new_starttime_timestamp < $bslot["end_time"] && $new_endtime_timestamp > $bslot["end_time"] && $no_booking==0){
								$booked_slot_exist = true;
								continue;
							}
							
							if($new_starttime_timestamp < $bslot["end_time"] && $new_endtime_timestamp > $bslot["end_time"] && $no_booking!=0){
							    $no_curr_boookings = $no_curr_boookings+1;
							    if($no_curr_boookings>=$no_booking){
    								$booked_slot_exist = true;
    								continue;
							    }
							} 
						}
						
						if($booked_slot_exist && $rzvy_hide_already_booked_slots_from_frontend_calendar == "Y"){
							continue;
						}else if($booked_slot_exist && $rzvy_hide_already_booked_slots_from_frontend_calendar == "N" && $no_booking==0){ 
							$blockoff_exist = false;
							if(sizeof($available_slots['block_off'])>0){
								foreach($available_slots['block_off'] as $block_off){
									if((strtotime($selected_date." ".$block_off["start_time"]) <= strtotime($selected_date." ".$slot)) && (strtotime($selected_date." ".$block_off["end_time"]) > strtotime($selected_date." ".$slot))){
										$blockoff_exist = true;
										continue;
									} 
								}
							} 
							if($blockoff_exist){
								continue;
							} 
							$j++;
						}else if($booked_slot_exist && $rzvy_hide_already_booked_slots_from_frontend_calendar == "N" && $no_booking!=0 && $no_curr_boookings>=$no_booking){ 
							$blockoff_exist = false;
							if(sizeof($available_slots['block_off'])>0){
								foreach($available_slots['block_off'] as $block_off){
									if((strtotime($selected_date." ".$block_off["start_time"]) <= strtotime($selected_date." ".$slot)) && (strtotime($selected_date." ".$block_off["end_time"]) > strtotime($selected_date." ".$slot))){
										$blockoff_exist = true;
										continue;
									} 
								}
							} 
							if($blockoff_exist){
								continue;
							} 
							$j++;
						}else{ 
							$blockoff_exist = false;
							if(sizeof($available_slots['block_off'])>0){
								foreach($available_slots['block_off'] as $block_off){
									if((strtotime($selected_date." ".$block_off["start_time"]) <= strtotime($selected_date." ".$slot)) && (strtotime($selected_date." ".$block_off["end_time"]) > strtotime($selected_date." ".$slot))){
										$blockoff_exist = true;
										continue;
									} 
								}
							} 
							if($blockoff_exist){
								continue;
							} 
							?>
							<div class="col-md-3 rzvy-sm-box rzvy_slot_new mb-2">
								<div class="rzvy-styled-radio rzvy-styled-radio-second form-check custom">
									<input type="radio" class="rzvy_time_slots_selection rzvy_anystaff_selection" data-staffid="<?php echo $staffid;?>" id="rzvy-time-slot-<?php echo $i.$staffid; ?>" name="rzvy-time-slots" value="<?php echo $slot; ?>">	
									<label for="rzvy-time-slot-<?php echo $i.$staffid; ?>"><?php echo date($rzvy_time_format, strtotime($selected_date." ".$slot)); ?></label>
								</div>
							</div>
							<?php 
							$j++;
						}
						$i++;
					}
				}
				if($j == 0){ 
					$rzvy_staff_noneslot_count++;
						$rzvy_slotsof_css .= '.rzvy_staff_'.$slotof.$staffid.'{display:none !important;}';
				}
			}else{ 
				$rzvy_staff_noneslot_count++;
				$rzvy_slotsof_css .= '.rzvy_staff_'.$slotof.$staffid.'{display:none !important;}';
			} 
			?>
		</div> <?php 
	}
	if($rzvy_staff_noneslot_count==sizeof($servicestaffs)){ ?>
		<div class="col-md-12 rzvy-sm-box rzvy_slot_new text-center">
			<h6><?php echo $noslot_message; ?></h6>
		</div>
	<?php }		
	?><div class="rzvy_date_selection active_selected_date" data-day="<?php echo date('Y-m-d',strtotime($selected_date)); ?>" ></div><style><?php echo $rzvy_slotsof_css; ?></style></div><?php
}