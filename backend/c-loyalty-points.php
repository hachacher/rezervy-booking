<?php 
include 'c_header.php'; 
include(dirname(dirname(__FILE__))."/classes/class_loyalty_points.php");

$obj_loyalty_points = new rzvy_loyalty_points();
$obj_loyalty_points->conn = $conn;

$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');

$rzvy_datetime_format = $rzvy_date_format." ".$rzvy_time_format; 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/my-appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['loyalty_points'])){ echo $rzvy_translangArr['loyalty_points']; }else{ echo $rzvy_defaultlang['loyalty_points']; } ?></li>
        
      </ol>
      <div class="mb-3">
		<div class="rzvy-tabbable-panel">
		    <div class="col-md-12"><span class="pull-right"><?php if(isset($rzvy_translangArr['loyalty_points_available_colon'])){ echo $rzvy_translangArr['loyalty_points_available_colon']; }else{ echo $rzvy_defaultlang['loyalty_points_available_colon']; } ?>   <?php echo $obj_loyalty_points->get_available_points_customer($_SESSION["customer_id"]);?></span></div>
			<div class="rzvy-tabbable-line">
				<ul class="nav nav-tabs w-100">
				  <li class="nav-item active custom-nav-item">
					<a class="nav-link custom-nav-link rzvy_tab_view_nav_link" data-tabno="0" data-toggle="tab" href="#rzvy_recieved_loyalty_points"><i class="fa fa-plus-square"></i> <?php if(isset($rzvy_translangArr['recieved_loyalty_points'])){ echo $rzvy_translangArr['recieved_loyalty_points']; }else{ echo $rzvy_defaultlang['recieved_loyalty_points']; } ?></a>
				  </li>
				  <li class="nav-item custom-nav-item">
					<a class="nav-link custom-nav-link rzvy_tab_view_nav_link" data-tabno="1" data-toggle="tab" href="#rzvy_used_loyalty_points"><i class="fa fa-minus-square"></i> <?php if(isset($rzvy_translangArr['used_loyalty_points'])){ echo $rzvy_translangArr['used_loyalty_points']; }else{ echo $rzvy_defaultlang['used_loyalty_points']; } ?></a>
				  </li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane container active" id="rzvy_recieved_loyalty_points">
					  <div class="row">
						<div class="col-md-12">
						  <div class="table-responsive">
							<table class="display responsive nowrap" width="100%" cellspacing="0" id="rzvy_recieved_loyalty_points_table">
							  <thead>
								<tr>
								  <th><?php if(isset($rzvy_translangArr['order_id'])){ echo $rzvy_translangArr['order_id']; }else{ echo $rzvy_defaultlang['order_id']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['points'])){ echo $rzvy_translangArr['points']; }else{ echo $rzvy_defaultlang['points']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['recieved_for'])){ echo $rzvy_translangArr['recieved_for']; }else{ echo $rzvy_defaultlang['recieved_for']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['loyalty_points_available'])){ echo $rzvy_translangArr['loyalty_points_available']; }else{ echo $rzvy_defaultlang['loyalty_points_available']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['lp_recieve_datetime'])){ echo $rzvy_translangArr['lp_recieve_datetime']; }else{ echo $rzvy_defaultlang['lp_recieve_datetime']; } ?></th>
								</tr>
							  </thead>
							  <tbody>
							  </tbody>
							</table>
						  </div>
						</div>
					  </div>
					</div>
					<div class="tab-pane container fade" id="rzvy_used_loyalty_points">
					  <br/>
					  <div class="row">
						<div class="col-md-12">
						  <div class="table-responsive">
							<table class="display responsive nowrap" width="100%" cellspacing="0" id="rzvy_used_loyalty_points_table">
							  <thead>
								<tr>
								  <th><?php if(isset($rzvy_translangArr['order_id'])){ echo $rzvy_translangArr['order_id']; }else{ echo $rzvy_defaultlang['order_id']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['points'])){ echo $rzvy_translangArr['points']; }else{ echo $rzvy_defaultlang['points']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['used_for'])){ echo $rzvy_translangArr['used_for']; }else{ echo $rzvy_defaultlang['used_for']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['loyalty_points_available'])){ echo $rzvy_translangArr['loyalty_points_available']; }else{ echo $rzvy_defaultlang['loyalty_points_available']; } ?></th>
								  <th><?php if(isset($rzvy_translangArr['lp_use_datetime'])){ echo $rzvy_translangArr['lp_use_datetime']; }else{ echo $rzvy_defaultlang['lp_use_datetime']; } ?></th>
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
<?php include 'c_footer.php'; ?>