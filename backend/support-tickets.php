<?php 
include 'header.php'; 
if(!isset($rzvy_rolepermissions['rzvy_support']) && $rzvy_loginutype=='staff'){ ?>
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
include(dirname(dirname(__FILE__))."/classes/class_support_tickets.php");
include(dirname(dirname(__FILE__))."/classes/class_support_ticket_discussions.php");
include(dirname(dirname(__FILE__))."/classes/class_customers.php");

$obj_support_tickets = new rzvy_support_tickets();
$obj_support_tickets->conn = $conn;

$obj_support_ticket_discussions = new rzvy_support_ticket_discussions();
$obj_support_ticket_discussions->conn = $conn;

$obj_customers = new rzvy_customers();
$obj_customers->conn = $conn;

$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');
$rzvy_datetime_format = $rzvy_date_format." ".$rzvy_time_format; 
?>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/appointments.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active"><?php if(isset($rzvy_translangArr['support_tickets'])){ echo $rzvy_translangArr['support_tickets']; }else{ echo $rzvy_defaultlang['support_tickets']; } ?></li>
      </ol>
      <!-- DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-fw fa-comments"></i> <?php if(isset($rzvy_translangArr['support_ticket_list'])){ echo $rzvy_translangArr['support_ticket_list']; }else{ echo $rzvy_defaultlang['support_ticket_list']; } ?>
		</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="display responsive nowrap" width="100%" id="rzvy_support_ticket_list_table" cellspacing="0">
              <thead>
				<tr>
				  <th><?php if(isset($rzvy_translangArr['hash_rzy_translation'])){ echo $rzvy_translangArr['hash_rzy_translation']; }else{ echo $rzvy_defaultlang['hash_rzy_translation']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['ticket_title'])){ echo $rzvy_translangArr['ticket_title']; }else{ echo $rzvy_defaultlang['ticket_title']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['generated_on'])){ echo $rzvy_translangArr['generated_on']; }else{ echo $rzvy_defaultlang['generated_on']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['generated_by'])){ echo $rzvy_translangArr['generated_by']; }else{ echo $rzvy_defaultlang['generated_by']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['status'])){ echo $rzvy_translangArr['status']; }else{ echo $rzvy_defaultlang['status']; } ?></th>
				  <th><?php if(isset($rzvy_translangArr['action'])){ echo $rzvy_translangArr['action']; }else{ echo $rzvy_defaultlang['action']; } ?></th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
				$all_support_tickets = $obj_support_tickets->get_all_support_tickets();
				$i = 1;
				while($support_ticket = mysqli_fetch_array($all_support_tickets)){ 
					?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php if(strlen($support_ticket['ticket_title']) < 30){ echo ucfirst($support_ticket['ticket_title']); }else{ echo substr(ucfirst($support_ticket['ticket_title']), 0, 30)."..."; } ?></td>
						<td><?php echo date($rzvy_datetime_format, strtotime($support_ticket['generated_on'])); ?></td>
						<td>
							<?php 
							if($support_ticket['generated_by'] == "admin"){
								if(isset($rzvy_translangArr['you'])){ echo $rzvy_translangArr['you']; }else{ echo $rzvy_defaultlang['you']; }
							}else{
								$obj_customers->id = $support_ticket['generated_by_id'];
								echo $obj_customers->get_customer_name();
							} 
							?>
						</td>
						<td><?php if($support_ticket['status'] == "active"){ ?><label class="text-primary"><?php echo ucwords($support_ticket['status']); ?></label><?php }else{ ?><label class="text-success"><?php echo ucwords($support_ticket['status']); ?></label><?php } ?></td>
						<td>
							<?php 
							$obj_support_ticket_discussions->ticket_id = $support_ticket['id'];
							$obj_support_ticket_discussions->replied_by = $_SESSION['login_type'];
							$unread_reply_count = $obj_support_ticket_discussions->count_all_unread_ticket_discussion_reply();
							?>
							<?php if(isset($rzvy_rolepermissions['rzvy_support_delete']) || $rzvy_loginutype=='admin'){ ?>
								<a href="javascript:void(0);" class="btn btn-danger rzvy-white btn-sm rzvy_delete_support_ticket_btn" data-id="<?php echo $support_ticket['id']; ?>"><i class="fa fa-fw fa-trash"></i></a> &nbsp; 
							<?php } if(isset($rzvy_rolepermissions['rzvy_support_discussion']) || $rzvy_loginutype=='admin'){ ?>
								<a class="btn btn-warning btn-sm markasread_all_support_ticket_reply" href="javascript:void(0)" data-id="<?php echo $support_ticket['id']; ?>"><i class="fa fa-fw fa-comments-o"></i> <?php if($unread_reply_count>0){ ?><span class="badge badge-success"><?php echo $unread_reply_count; ?></span><?php } ?></a> &nbsp; 
							<?php } if(isset($rzvy_rolepermissions['rzvy_support_complete']) || $rzvy_loginutype=='admin'){ ?>
								<?php 
								if($support_ticket['status'] == "active"){ 
								?>
								<a class="btn btn-success btn-sm rzvy_markascomplete_support_ticket_btn" href="javascript:void(0);" data-id="<?php echo $support_ticket['id']; ?>"><i class="fa fa-fw fa-check-square-o" aria-hidden="true"></i></a>
								<?php 
								} 
								?>
							<?php } ?>	
						</td>
					</tr>
					<?php 
					$i++;
				} 
				?>
			  </tbody>
           </table>
          </div>
        </div>
      </div>
<?php include 'footer.php'; ?>