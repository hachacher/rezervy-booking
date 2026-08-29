<?php $companyname = $obj_settings->get_option("rzvy_company_name"); ?>
<?php $companylogo = $obj_settings->get_option("rzvy_company_logo"); ?>
<!-- Rzvy Header -->
<header class="rzvy-header"> 
  <div class="container">
    <a href="<?php echo SITE_URL; ?>" class="rzvy-brand"><?php if(!isset($_GET['if'])){ ?><?php if($companylogo != "" && file_exists(ROOT_PATH."/uploads/images/".$companylogo)){ ?><img src="<?php echo SITE_URL; ?>uploads/images/<?php echo $companylogo; ?>" /> <?php }else{ ?><b class="rzvy-companytitle"><?php echo $companyname; ?></b><?php } ?><?php } ?></a>
    <a href="javascript:void(0);" class="hamburger" onclick="$('.rzvy-header > .container > ul').slideToggle();"><span></span></a>
    <ul>
	  <?php if($lang_j>1){ ?>
      <li>
		  <div class="country-selectd">
		      <a href="javascript:void(0);"><?php echo $isSelectedlanguage; ?></a>
			 <ul class="selectpickerd" data-style="btn-primary">
			    <?php echo $langOptions; ?>
			 </ul>
		   </div>
	  </li>
	  <?php } ?>
      <li><?php if($rzvy_location_selector_status == "Y"){ ?> <a data-toggle="modal" data-target="#rzvy-location-selector-modal" href="javascript:void(0)"><i class="fa fa-fw fa-map-marker"></i><?php if(isset($rzvy_translangArr['book_at_another_location'])){ echo $rzvy_translangArr['book_at_another_location']; }else{ echo $rzvy_defaultlang['book_at_another_location']; } ?></a><?php } ?></li>
      <li><a href="<?php echo SITE_URL; ?>backend/my-appointments.php<?php echo $saiframe; ?>"><i class="fa  fa-fw fa-user-circle-o" aria-hidden="true"></i><?php if(isset($rzvy_translangArr['my_account'])){ echo $rzvy_translangArr['my_account']; }else{ echo $rzvy_defaultlang['my_account']; } ?></a></li>
	  <?php  if(isset($_SESSION['login_type'])) {
				$rzvy_namestring = '';
				if(isset($_SESSION['customer_id'])){
					$get_customer_name = $obj_settings->get_customer_name($_SESSION["customer_id"]);  
					if(mysqli_num_rows($get_customer_name)>0){ 
					$__customername = mysqli_fetch_array($get_customer_name); 
					$rzvy_namestring = substr($__customername["firstname"],0,1).substr($__customername["lastname"],0,1);
					}
				}	
				if(isset($_SESSION['staff_id'])){
					$get_staff_name = $obj_settings->get_staff_name($_SESSION["staff_id"]);  
					if(mysqli_num_rows($get_staff_name)>0){ 
					$__staff_name = mysqli_fetch_array($get_staff_name); 
					$rzvy_namestring = substr($__staff_name["firstname"],0,1).substr($__staff_name["lastname"],0,1);
					}
				}
				if(isset($_SESSION['admin_id'])){
					$get_admin_name = $obj_settings->get_admin_name($_SESSION["admin_id"]);  
					if(mysqli_num_rows($get_admin_name)>0){ 
					$__admin_name = mysqli_fetch_array($get_admin_name); 
					$rzvy_namestring = substr($__admin_name["firstname"],0,1).substr($__admin_name["lastname"],0,1);
					}
				}
			if($rzvy_namestring!=''){  ?>
			<li><span class="user-avatar"><?php echo $rzvy_namestring; ?></span></li>
			<?php } ?>
      <li class="logout"><a href="javascript:void(0)" id="rzvy_logout_header_btn"><i class="fa fa-fw fa-sign-out" aria-hidden="true"></i> <?php if(isset($rzvy_translangArr['logout'])){ echo $rzvy_translangArr['logout']; }else{ echo $rzvy_defaultlang['logout']; } ?></a></li>
	  <?php } ?>
    </ul>
  </div>
  <?php if($obj_settings->get_option('rzvy_welcome_message_status')=='Y'){ ?>
	<div class="container rzvy_welcome_main">
		<div class="row">
			<div class="col-12 col-lg-12 col-xl-12 offset-xl-1 rzvy_welcome_content">
				<?php echo base64_decode($obj_settings->get_option('rzvy_welcome_message_container'));?>
			</div>
		</div>
	</div>
	<?php } ?>
</header>