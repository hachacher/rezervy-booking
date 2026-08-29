<?php 
$companyname = $obj_settings->get_option("rzvy_company_name"); 
$rzy_company_logo = $obj_settings->get_option("rzvy_company_logo"); 
?>
<!-- Rzvy Header -->
<header class="rzvy-header"> 
  <div class="container">
    <a href="<?php echo SITE_URL; ?>" class="rzvy-brand"><?php if(!isset($_GET['if'])){ ?><?php if($rzy_company_logo != "" && file_exists(ROOT_PATH."/uploads/images/".$rzy_company_logo)){ ?><img src="<?php echo SITE_URL; ?>uploads/images/<?php echo $rzy_company_logo; ?>" /> <?php }else{ ?><b class="rzvy-companytitle"><?php echo $companyname; ?></b><?php } ?><?php } ?></a>
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
	  <?php } if (strpos($_SERVER['SCRIPT_NAME'], 'register.php') == false) { ?>
		<li>
			<a href="<?php echo SITE_URL; ?>backend/register.php"><i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;&nbsp;<?php if(isset($rzvy_translangArr['register_now'])){ echo $rzvy_translangArr['register_now']; }else{ echo $rzvy_defaultlang['register_now']; } ?></a>
		</li>
		<?php } if (strpos($_SERVER['SCRIPT_NAME'], 'index.php') == false) { ?>
		<li>
			<a href="<?php echo SITE_URL; ?>backend"><i class="fa fa-sign-in" aria-hidden="true"></i> &nbsp;&nbsp;<?php if(isset($rzvy_translangArr['log_in'])){ echo $rzvy_translangArr['log_in']; }else{ echo $rzvy_defaultlang['log_in']; } ?></a>
		</li>
		<?php } ?>
		<li>
			<a href="<?php echo SITE_URL; ?>"><i class="fa fa-calendar" aria-hidden="true"></i> &nbsp;&nbsp;<?php if(isset($rzvy_translangArr['book_now_header'])){ echo $rzvy_translangArr['book_now_header']; }else{ echo $rzvy_defaultlang['book_now_header']; } ?></a>
		</li>
    </ul>
  </div>
</header>	
	