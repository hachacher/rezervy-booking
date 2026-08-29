<?php 
include 'header.php'; 
if(!isset($rzvy_rolepermissions['rzvy_feedback']) && $rzvy_loginutype=='staff'){ ?>
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
include(dirname(dirname(__FILE__))."/classes/class_feedback.php");
/* Create object of classes */
$obj_feedback = new rzvy_feedback();
$obj_feedback->conn = $conn; 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['feedbacks'])){ echo $rzvy_translangArr['feedbacks']; }else{ echo $rzvy_defaultlang['feedbacks']; } ?></li>
      </ol>
      <!-- Feedback DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-book"></i> <?php if(isset($rzvy_translangArr['feedback_list'])){ echo $rzvy_translangArr['feedback_list']; }else{ echo $rzvy_defaultlang['feedback_list']; } ?>
		  </div>
        <div class="card-body">
          <div class="table-responsive">
			<ul class="nav nav-tabs">
				<?php if(isset($rzvy_rolepermissions['rzvy_feedback']) || $rzvy_loginutype=='admin'){ ?>
					  <li class="nav-item custom-nav-item <?php if(isset($rzvy_rolepermissions['rzvy_feedback']) || $rzvy_loginutype=='admin'){ echo 'active'; } ?>">
						<a class="nav-link custom-nav-link rzvy_tab_view_nav_link" data-tabno="0" data-toggle="tab" href="#rzvy_feedback_list"><i class="fa fa-book"></i> <?php if(isset($rzvy_translangArr['feedbacks'])){ echo $rzvy_translangArr['feedbacks']; }else{ echo $rzvy_defaultlang['feedbacks']; } ?></a>
					  </li>
				<?php } if(isset($rzvy_rolepermissions['rzvy_reviewsmanage']) || $rzvy_loginutype=='admin'){ ?>  
					  <li class="nav-item custom-nav-item <?php if(!isset($rzvy_rolepermissions['rzvy_reviewsmanage']) && isset($rzvy_rolepermissions['rzvy_reviewsmanage'])){ echo 'active'; } ?>">
						<a class="nav-link custom-nav-link rzvy_tab_view_nav_link" data-tabno="1" data-toggle="tab" href="#rzvy_reviews_list"><i class="fa fa-star"></i> <?php if(isset($rzvy_translangArr['reviews_list'])){ echo $rzvy_translangArr['reviews_list']; }else{ echo $rzvy_defaultlang['reviews_list']; } ?></a>
					  </li>
				<?php } ?>	  
			</ul>
		<div class="tab-content">  
			<div class="tab-pane container <?php if(isset($rzvy_rolepermissions['rzvy_feedback']) || $rzvy_loginutype=='admin'){ echo 'active'; }else{ echo 'fade'; } ?>" id="rzvy_feedback_list">
				<table class="display responsive nowrap" width="100%" cellspacing="0" id="rzvy_feedback_list_table">
				  <thead>
					<tr>
					  <th><?php if(isset($rzvy_translangArr['name'])){ echo $rzvy_translangArr['name']; }else{ echo $rzvy_defaultlang['name']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['email'])){ echo $rzvy_translangArr['email']; }else{ echo $rzvy_defaultlang['email']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['rating'])){ echo $rzvy_translangArr['rating']; }else{ echo $rzvy_defaultlang['rating']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['review'])){ echo $rzvy_translangArr['review']; }else{ echo $rzvy_defaultlang['review']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['review_on'])){ echo $rzvy_translangArr['review_on']; }else{ echo $rzvy_defaultlang['review_on']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['status'])){ echo $rzvy_translangArr['status']; }else{ echo $rzvy_defaultlang['status']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
					</tr>
				  </thead>
				  <tbody>
					<?php 
					$all_feedbacks = $obj_feedback->get_all_feedbacks();
					$rating_star_array = array(
						"0" => '<i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i>',
						"1" => '<i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i>',
						"2" => '<i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i>',
						"3" => '<i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i>',
						"4" => '<i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star-o text-warning"></i>',
						"5" => '<i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i>',
					);
					if(mysqli_num_rows($all_feedbacks)>0){
						$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
						$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');
						
						while($feedback = mysqli_fetch_assoc($all_feedbacks)){
							?>
							<tr>
								<td><?php echo ucwords($feedback['name']); ?></td>
								<td><?php echo $feedback['email']; ?></td>
								<td><?php echo $rating_star_array[$feedback['rating']]; ?></td>
								<td><?php echo $feedback['review']; ?></td>
								<td><?php echo date($rzvy_date_format." ".$rzvy_time_format, strtotime($feedback['review_datetime'])); ?></td>
								<td>
									<?php $checked = ''; if($feedback['status'] == "Y"){ $checked = "checked"; } ?>
									<?php if(isset($rzvy_rolepermissions['rzvy_feedback_status']) || $rzvy_loginutype=='admin'){ ?>
										<label class="rzvy-toggle-switch">
										  <input type="checkbox" data-id="<?php echo $feedback['id']; ?>" class="rzvy-toggle-switch-input rzvy_change_feedback_status" <?php echo $checked; ?> />
										  <span class="rzvy-toggle-switch-slider"></span>
										</label>
									<?php }elseif(!isset($rzvy_rolepermissions['rzvy_feedback_status']) && $rzvy_loginutype=='staff'){ 
											if($feedback['status'] == "Y"){
												if(isset($rzvy_translangArr['enable'])){ echo $rzvy_translangArr['enable']; }else{ echo $rzvy_defaultlang['enable']; }
											}else{
												if(isset($rzvy_translangArr['disable'])){ echo $rzvy_translangArr['disable']; }else{ echo $rzvy_defaultlang['disable']; }
											}
										} ?>		
								</td>
								<td>
									<?php if(isset($rzvy_rolepermissions['rzvy_feedback_delete']) || $rzvy_loginutype=='admin'){ ?>
										<a class="btn btn-danger rzvy-white btn-sm rzvy_delete_feedback_btn m-1" data-id="<?php echo $feedback['id']; ?>"><i class="fa fa-fw fa-trash"></i></a>
									<?php } ?>
								</td>
							</tr>
							<?php 
						}
					}
					?>
				  </tbody>
			   </table>
			</div>
			<div class="tab-pane container <?php if(isset($rzvy_rolepermissions['rzvy_reviewsmanage']) && !isset($rzvy_rolepermissions['rzvy_feedback'])){ echo 'active'; }else{ echo 'fade'; } ?>" id="rzvy_reviews_list">
				<table class="display responsive nowrap" width="100%" cellspacing="0" id="rzvy_review_list_table">
				  <thead>
					<tr>
					  <th><?php if(isset($rzvy_translangArr['name'])){ echo $rzvy_translangArr['name']; }else{ echo $rzvy_defaultlang['name']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['email'])){ echo $rzvy_translangArr['email']; }else{ echo $rzvy_defaultlang['email']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['rating'])){ echo $rzvy_translangArr['rating']; }else{ echo $rzvy_defaultlang['rating']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['review'])){ echo $rzvy_translangArr['review']; }else{ echo $rzvy_defaultlang['review']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['review_on'])){ echo $rzvy_translangArr['review_on']; }else{ echo $rzvy_defaultlang['review_on']; } ?></th>
					  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
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
<?php include 'footer.php'; ?>