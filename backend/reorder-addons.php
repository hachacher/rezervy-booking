<?php 
include 'header.php';
if(!isset($rzvy_rolepermissions['rzvy_service_reorderaddon']) && $rzvy_loginutype=='staff'){ ?>
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
if(!isset($_GET['sid'])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/category.php";
	</script>
	<?php 
	exit;
} else if(!is_numeric($_GET['sid'])){
	?>
	<script>
	window.location.href = "<?php echo SITE_URL; ?>backend/category.php";
	</script>
	<?php 
	exit;
} 
$obj_addons->service_id = $_GET['sid'];
$all_service_addons = $obj_addons->get_all_addons_by_service_id();
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/category.php"><?php if(isset($rzvy_translangArr['category'])){ echo $rzvy_translangArr['category']; }else{ echo $rzvy_defaultlang['category']; } ?></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?> <?php if(isset($rzvy_translangArr['addons'])){ echo $rzvy_translangArr['addons']; }else{ echo $rzvy_defaultlang['addons']; } ?></li>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header"><i class="fa fa-fw fa-book"></i> <?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?> <?php if(isset($rzvy_translangArr['addons'])){ echo $rzvy_translangArr['addons']; }else{ echo $rzvy_defaultlang['addons']; } ?></div>
        <div class="card-body">
			<input type="hidden" id="reorder_service_id" value="<?php echo $_GET["sid"]; ?>" />
			<div class="row">
				<?php 
				if(mysqli_num_rows($all_service_addons)>0){ 
					?>
					<div class="col-md-12 border py-3">
						<ul class="addons_reorder rzvy_reorder_ul m-1 row">
							<?php 
							$i=0;
							while($addon = mysqli_fetch_assoc($all_service_addons)){
								?>
								<li class="p-3 m-1 bg-light ui-state-default col-md-12" data-id="<?php echo $addon['id']; ?>"><i class="fa fa-arrows pr-2"></i> <?php echo ucwords($addon['title']); ?></li>
								<?php 
								$i++;
							}
							?>
						</ul>
						<a id="addons_reorder_btn" class="btn btn-primary w-100 btn-lg" href="javascript:void(0);"><?php if(isset($rzvy_translangArr['reorder'])){ echo $rzvy_translangArr['reorder']; }else{ echo $rzvy_defaultlang['reorder']; } ?></a>
					</div>
					<?php 
				}
				?>
			</div>
        </div>
      </div>
<?php include 'footer.php'; ?>