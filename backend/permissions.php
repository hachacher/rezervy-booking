<?php include 'header.php'; 
if(!isset($rzvy_rolepermissions['rzvy_rolepermission_view']) && $rzvy_loginutype=='staff'){ ?>
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
$rzvyroleiderrr = '';
$rzvyrolename = '';
if(isset($rzvy_translangArr['opps'])){ $rzvyoops =  $rzvy_translangArr['opps']; }else{ $rzvyoops =  $rzvy_defaultlang['opps']; }
if(isset($rzvy_translangArr['role_id_missing_incorrect'])){ $rzvyroleiderr =  $rzvy_translangArr['role_id_missing_incorrect']; }else{ $rzvyroleiderr =  $rzvy_defaultlang['role_id_missing_incorrect']; }

if(isset($_GET['rid']) && $_GET['rid']!='' && is_numeric($_GET['rid'])){
    $obj_roles->id =$_GET['rid'];
}else{
    $rzvyroleiderrr = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>'.$rzvyoops.'</strong> '.$rzvyroleiderr.'<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span></button></div>';
}
$permissionsavemsg = '';
if(isset($_POST['rzvy_rolepermissions_save'])){
  if(isset($rzvy_translangArr['success'])){ $rzvysuccess =  $rzvy_translangArr['success']; }else{ $rzvysuccess =  $rzvy_defaultlang['success']; }
 if(isset($rzvy_translangArr['role_permissions_saved'])){ $rzvypermissionsucess =  $rzvy_translangArr['role_permissions_saved']; }else{ $rzvypermissionsucess =  $rzvy_defaultlang['role_permissions_saved']; }  
  if(isset($rzvy_translangArr['role_permissions_not_saved'])){ $rzvypermissionerror =  $rzvy_translangArr['role_permissions_not_saved']; }else{ $rzvypermissionerror =  $rzvy_defaultlang['role_permissions_not_saved']; }  
   
   $rolepermissionsarr = array();
   if(isset($_POST['rzvy_rolepermissions_cb']) && $_POST['rzvy_rolepermissions_cb']!='' && is_array($_POST['rzvy_rolepermissions_cb'])){
       foreach($_POST['rzvy_rolepermissions_cb'] as $rolepermission){
           $rolepermissionsarr[$rolepermission] = 'Y';
       }
   }
    $obj_roles->id = $_GET['rid'];
    $obj_roles->permission = serialize($rolepermissionsarr);
    $permissionsaved = $obj_roles->update_role_permission();
    
    if($permissionsaved){
        $permissionsavemsg = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'.$rzvysuccess.'</strong> '.$rzvypermissionsucess.'<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span></button></div>';
    }else{
        $permissionsavemsg = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>'.$rzvyoops.'</strong> '.$rzvypermissionerror.'<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span></button></div>';
    }
    
}
$rzvyroleoermissions = array();
$roleinfo = $obj_roles->readone_role();
if(isset($roleinfo['role'])){
    $rzvyrolename = $roleinfo['role'];
    $rzvyroleoermissions = unserialize($roleinfo['permission']);
}

$j = 1;
?>
<div class="col-12">
<?php if($rzvyroleiderrr!=''){ echo $rzvyroleiderrr; die(); } echo $permissionsavemsg; ?>
</div>
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="<?php echo SITE_URL; ?>backend/dashboard.php"><i class="fa fa-home"></i></a>
        </li>
        <li class="breadcrumb-item active"><?php echo $rzvyrolename; ?> <?php if(isset($rzvy_translangArr['permissions'])){ echo $rzvy_translangArr['permissions']; }else{ echo $rzvy_defaultlang['permissions']; } ?></li>
      </ol>
<div class="my-4 mx-2">
	<div class="card-header" style="text-align:right;">
		<div class="custom-control custom-checkbox d-inline">
			<input type="checkbox" class="custom-control-input" id="rzvy_rolepermissions_cball" name="rzvy_rolepermissions_cball">
			<label class="custom-control-label" for="rzvy_rolepermissions_cball">Check/Uncheck All Permissions</label>
		</div>
	</div>
	<?php if(isset($rzvy_rolepermissions['rzvy_rolepermission_manage']) || $rzvy_loginutype=='admin'){ ?><form method="post" action="" name="rzvy_save_role_permissions"><?php } ?>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_extaddons" name="rzvy_rolepermissions_cb[]" value="rzvy_extaddons"  <?php if(isset($rzvyroleoermissions['rzvy_extaddons'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_extaddons">External Addons</label>
				</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_dashboard_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_dashboard" name="rzvy_rolepermissions_cb[]" value="rzvy_dashboard"  <?php if(isset($rzvyroleoermissions['rzvy_dashboard'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_dashboard">Dashboard</label>
				</div>
			</div>
			<div id="rzvy_dashboard_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_dashboard'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_dashboard_dateranger_filter" name="rzvy_rolepermissions_cb[]" value="rzvy_dashboard_dateranger_filter"  <?php if(isset($rzvyroleoermissions['rzvy_dashboard_dateranger_filter'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_dashboard_dateranger_filter">Dashboard Daterange Filter</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_appointments_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_appointments" name="rzvy_rolepermissions_cb[]" value="rzvy_appointments"  <?php if(isset($rzvyroleoermissions['rzvy_appointments'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_appointments">Appointments</label>
				</div>
			</div>
			<div id="rzvy_appointments_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_appointments'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_manual_booking" name="rzvy_rolepermissions_cb[]" value="rzvy_manual_booking"  <?php if(isset($rzvyroleoermissions['rzvy_manual_booking'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_manual_booking">Manual Booking</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_list_view" name="rzvy_rolepermissions_cb[]" value="rzvy_list_view"  <?php if(isset($rzvyroleoermissions['rzvy_list_view'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_list_view">List View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_appointment_acwh" name="rzvy_rolepermissions_cb[]" value="rzvy_appointment_acwh"  <?php if(isset($rzvyroleoermissions['rzvy_appointment_acwh'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_appointment_acwh">Manage Working Hours on Calendar</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_add_sale" name="rzvy_rolepermissions_cb[]" value="rzvy_add_sale"  <?php if(isset($rzvyroleoermissions['rzvy_add_sale'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_add_sale">Add New Sale</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_add_break" name="rzvy_rolepermissions_cb[]" value="rzvy_add_break"  <?php if(isset($rzvyroleoermissions['rzvy_add_break'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_add_break">Add Break</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_delete_booking" name="rzvy_rolepermissions_cb[]" value="rzvy_delete_booking"  <?php if(isset($rzvyroleoermissions['rzvy_delete_booking'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_delete_booking">Delete Appointment</label>
					</div>
			</div>
		</div>
		
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_servcies_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_servcies" name="rzvy_rolepermissions_cb[]" value="rzvy_servcies"  <?php if(isset($rzvyroleoermissions['rzvy_servcies'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_servcies">Services</label>
				</div>
			</div>
			<div id="rzvy_servcies_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_servcies'])){ echo 'show'; } ?>">
					<div class="card my-2">
						<div class="card-header collapsed" >
							<div class="custom-control custom-checkbox d-inline" href="#rzvy_pcategories_permissions" data-toggle="collapse" aria-expanded="false">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pcategories" name="rzvy_rolepermissions_cb[]" value="rzvy_pcategories"  <?php if(isset($rzvyroleoermissions['rzvy_pcategories'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_pcategories">Parent Categories</label>
							</div>
						</div>
						<div id="rzvy_pcategories_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_pcategories'])){ echo 'show'; } ?>">
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pcategories_add" name="rzvy_rolepermissions_cb[]" value="rzvy_pcategories_add"  <?php if(isset($rzvyroleoermissions['rzvy_pcategories_add'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_pcategories_add">Add</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pcategories_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_pcategories_edit"  <?php if(isset($rzvyroleoermissions['rzvy_pcategories_edit'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_pcategories_edit">Edit</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pcategories_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_pcategories_delete"  <?php if(isset($rzvyroleoermissions['rzvy_pcategories_delete'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_pcategories_delete">Delete</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pcategories_status" name="rzvy_rolepermissions_cb[]" value="rzvy_pcategories_status"  <?php if(isset($rzvyroleoermissions['rzvy_pcategories_status'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_pcategories_status">Status</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pcategories_reorder" name="rzvy_rolepermissions_cb[]" value="rzvy_pcategories_reorder"  <?php if(isset($rzvyroleoermissions['rzvy_pcategories_reorder'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_pcategories_reorder">Re-Order</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pcategories_assign_subcate" name="rzvy_rolepermissions_cb[]" value="rzvy_pcategories_assign_subcate"  <?php if(isset($rzvyroleoermissions['rzvy_pcategories_assign_subcate'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_pcategories_assign_subcate">Assign Sub-Categories</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pcategories_view_subcate" name="rzvy_rolepermissions_cb[]" value="rzvy_pcategories_view_subcate"  <?php if(isset($rzvyroleoermissions['rzvy_pcategories_view_subcate'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_pcategories_view_subcate">View All Sub-Categories</label>
								</div>
						</div>
					</div>
					
					<div class="card my-2">
						<div class="card-header collapsed" >
							<div class="custom-control custom-checkbox d-inline" href="#rzvy_subcategories_permissions" data-toggle="collapse" aria-expanded="false">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_subcategories" name="rzvy_rolepermissions_cb[]" value="rzvy_subcategories"  <?php if(isset($rzvyroleoermissions['rzvy_subcategories'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_subcategories">Sub Categories</label>
							</div>
						</div>
						<div id="rzvy_subcategories_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_subcategories'])){ echo 'show'; } ?>">
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_subcategories_add" name="rzvy_rolepermissions_cb[]" value="rzvy_subcategories_add"  <?php if(isset($rzvyroleoermissions['rzvy_subcategories_add'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_subcategories_add">Add</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_subcategories_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_subcategories_edit"  <?php if(isset($rzvyroleoermissions['rzvy_subcategories_edit'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_subcategories_edit">Edit</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_subcategories_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_subcategories_delete"  <?php if(isset($rzvyroleoermissions['rzvy_subcategories_delete'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_subcategories_delete">Delete</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_subcategories_status" name="rzvy_rolepermissions_cb[]" value="rzvy_subcategories_status"  <?php if(isset($rzvyroleoermissions['rzvy_subcategories_status'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_subcategories_status">Status</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_subcategories_reorder" name="rzvy_rolepermissions_cb[]" value="rzvy_subcategories_reorder"  <?php if(isset($rzvyroleoermissions['rzvy_subcategories_reorder'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_subcategories_reorder">Re-Order</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_subcategories_view_allservices" name="rzvy_rolepermissions_cb[]" value="rzvy_subcategories_view_allservices"  <?php if(isset($rzvyroleoermissions['rzvy_subcategories_view_allservices'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_subcategories_view_allservices">View Services</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_subcategories_reorder_allservices" name="rzvy_rolepermissions_cb[]" value="rzvy_subcategories_reorder_allservices"  <?php if(isset($rzvyroleoermissions['rzvy_subcategories_reorder_allservices'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_subcategories_reorder_allservices">Re-Order All Services</label>
								</div>
						</div>
					</div>
					<div class="card my-2">
						<div class="card-header collapsed">
							<div class="custom-control custom-checkbox d-inline"  href="#rzvy_list_servcies_permissions" data-toggle="collapse" aria-expanded="false">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_view_allservices" name="rzvy_rolepermissions_cb[]" value="rzvy_subcategories_view_allservices"  <?php if(isset($rzvyroleoermissions['rzvy_subcategories_view_allservices'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_view_allservices">Services</label>
							</div>
						</div>
						<div id="rzvy_list_servcies_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_subcategories_view_allservices'])){ echo 'show'; } ?>">
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_service_add" name="rzvy_rolepermissions_cb[]" value="rzvy_service_add"  <?php if(isset($rzvyroleoermissions['rzvy_service_add'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_service_add">Add</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_service_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_service_edit"  <?php if(isset($rzvyroleoermissions['rzvy_service_edit'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_service_edit">Edit</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_service_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_service_delete"  <?php if(isset($rzvyroleoermissions['rzvy_service_delete'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_service_delete">Delete</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_service_status" name="rzvy_rolepermissions_cb[]" value="rzvy_service_status"  <?php if(isset($rzvyroleoermissions['rzvy_service_status'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_service_status">Status</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_service_reorder" name="rzvy_rolepermissions_cb[]" value="rzvy_service_reorder"  <?php if(isset($rzvyroleoermissions['rzvy_service_reorder'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_service_reorder">Re-Order</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_service_schedule" name="rzvy_rolepermissions_cb[]" value="rzvy_service_schedule"  <?php if(isset($rzvyroleoermissions['rzvy_service_schedule'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_service_schedule">Service Schedule</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_service_assignstaff" name="rzvy_rolepermissions_cb[]" value="rzvy_service_assignstaff"  <?php if(isset($rzvyroleoermissions['rzvy_service_assignstaff'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_service_assignstaff">Assign Staff</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_service_reorderaddon" name="rzvy_rolepermissions_cb[]" value="rzvy_service_reorderaddon"  <?php if(isset($rzvyroleoermissions['rzvy_service_reorderaddon'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_service_reorderaddon">Re-Order All Service Addons</label>
								</div>
						</div>
					</div>
			</div>
		</div>		
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_addon_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_addons" name="rzvy_rolepermissions_cb[]" value="rzvy_addons"  <?php if(isset($rzvyroleoermissions['rzvy_addons'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_addons">Addons</label>
				</div>
			</div>
			<div id="rzvy_addon_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_addons'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_addon_add" name="rzvy_rolepermissions_cb[]" value="rzvy_addon_add"  <?php if(isset($rzvyroleoermissions['rzvy_addon_add'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_addon_add">Add</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_addon_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_addon_edit"  <?php if(isset($rzvyroleoermissions['rzvy_addon_edit'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_addon_edit">Edit</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_addon_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_addon_delete"  <?php if(isset($rzvyroleoermissions['rzvy_addon_delete'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_addon_delete">Delete</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_addon_status" name="rzvy_rolepermissions_cb[]" value="rzvy_addon_status"  <?php if(isset($rzvyroleoermissions['rzvy_addon_status'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_addon_status">Status</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_addon_multiqtystatus" name="rzvy_rolepermissions_cb[]" value="rzvy_addon_multiqtystatus"  <?php if(isset($rzvyroleoermissions['rzvy_addon_multiqtystatus'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_addon_multiqtystatus">Multi Qty Status </label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_addon_serviceassign" name="rzvy_rolepermissions_cb[]" value="rzvy_addon_serviceassign"  <?php if(isset($rzvyroleoermissions['rzvy_addon_serviceassign'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_addon_serviceassign">Service Assign</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_pos_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pos" name="rzvy_rolepermissions_cb[]" value="rzvy_pos"  <?php if(isset($rzvyroleoermissions['rzvy_pos'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_pos">POS</label>
				</div>
			</div>
			<div id="rzvy_pos_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_pos'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pos_addservice" name="rzvy_rolepermissions_cb[]" value="rzvy_pos_addservice"  <?php if(isset($rzvyroleoermissions['rzvy_pos_addservice'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_pos_addservice">Add Service</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pos_settingsview" name="rzvy_rolepermissions_cb[]" value="rzvy_pos_settingsview"  <?php if(isset($rzvyroleoermissions['rzvy_pos_settingsview'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_pos_settingsview">POS Settings View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pos_settingsmanage" name="rzvy_rolepermissions_cb[]" value="rzvy_pos_settingsmanage"  <?php if(isset($rzvyroleoermissions['rzvy_pos_settingsmanage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_pos_settingsmanage">POS Settings Manage</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_pos_addcustomer" name="rzvy_rolepermissions_cb[]" value="rzvy_pos_addcustomer"  <?php if(isset($rzvyroleoermissions['rzvy_pos_addcustomer'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_pos_addcustomer">Add New Customer</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_customers_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_customers" name="rzvy_rolepermissions_cb[]" value="rzvy_customers"  <?php if(isset($rzvyroleoermissions['rzvy_customers'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_customers">Customers</label>
				</div>
			</div>
			<div id="rzvy_customers_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_customers'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_customers_register" name="rzvy_rolepermissions_cb[]" value="rzvy_customers_register"  <?php if(isset($rzvyroleoermissions['rzvy_customers_register'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_customers_register">View Registered Customers</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_customers_register_update" name="rzvy_rolepermissions_cb[]" value="rzvy_customers_register_update"  <?php if(isset($rzvyroleoermissions['rzvy_customers_register_update'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_customers_register_update">Update Registered Customer</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_customers_guest" name="rzvy_rolepermissions_cb[]" value="rzvy_customers_guest"  <?php if(isset($rzvyroleoermissions['rzvy_customers_guest'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_customers_guest">View Guest Customers</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_customers_guest_update" name="rzvy_rolepermissions_cb[]" value="rzvy_customers_guest_update"  <?php if(isset($rzvyroleoermissions['rzvy_customers_guest_update'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_customers_guest_update">Update Guest Customer</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_customers_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_customers_delete"  <?php if(isset($rzvyroleoermissions['rzvy_customers_delete'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_customers_delete">Delete Customers</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_payments_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_payments" name="rzvy_rolepermissions_cb[]" value="rzvy_payments"  <?php if(isset($rzvyroleoermissions['rzvy_payments'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_payments">Payments</label>
				</div>
			</div>
			<div id="rzvy_payments_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_payments'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_payments_register" name="rzvy_rolepermissions_cb[]" value="rzvy_payments_register"  <?php if(isset($rzvyroleoermissions['rzvy_payments_register'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_payments_register">View Registered Payments</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_payments_guest" name="rzvy_rolepermissions_cb[]" value="rzvy_payments_guest"  <?php if(isset($rzvyroleoermissions['rzvy_payments_guest'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_payments_guest">View Guest Payments</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_payments_pos" name="rzvy_rolepermissions_cb[]" value="rzvy_payments_pos"  <?php if(isset($rzvyroleoermissions['rzvy_payments_pos'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_payments_pos">View POS Payments</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline"  href="#rzvy_staff_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff" name="rzvy_rolepermissions_cb[]" value="rzvy_staff"  <?php if(isset($rzvyroleoermissions['rzvy_staff'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_staff">Staff</label>
				</div>
			</div>
				<div id="rzvy_staff_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_staff'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff_add" name="rzvy_rolepermissions_cb[]" value="rzvy_staff_add"  <?php if(isset($rzvyroleoermissions['rzvy_staff_add'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_staff_add">Add</label>
					</div>					
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff_reorder" name="rzvy_rolepermissions_cb[]" value="rzvy_staff_reorder"  <?php if(isset($rzvyroleoermissions['rzvy_staff_reorder'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_staff_reorder">Re-Order</label>
					</div>
					<div class="card my-2">
						<div class="card-header collapsed">
							<div class="custom-control custom-checkbox d-inline"  href="#rzvy_staffdetail_permissions" data-toggle="collapse" aria-expanded="false">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staffdetail" name="rzvy_rolepermissions_cb[]" value="rzvy_staffdetail"  <?php if(isset($rzvyroleoermissions['rzvy_staffdetail'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_staffdetail">Staff Detail</label>
							</div>
						</div>
						<div id="rzvy_staffdetail_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_staffdetail'])){ echo 'show'; } ?>">							
						<div class="custom-control custom-checkbox d-inline float-left col-12">
							<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_staff_edit"  <?php if(isset($rzvyroleoermissions['rzvy_staff_edit'])){ echo 'checked="checked"'; } ?> >
							<label class="custom-control-label" for="rzvy_staff_edit">Edit</label>
						</div>
						<div class="custom-control custom-checkbox d-inline float-left col-12">
							<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_staff_delete"  <?php if(isset($rzvyroleoermissions['rzvy_staff_delete'])){ echo 'checked="checked"'; } ?> >
							<label class="custom-control-label" for="rzvy_staff_delete">Delete</label>
						</div>
					</div>
				</div>
				<div class="card my-2">
						<div class="card-header collapsed">
							<div class="custom-control custom-checkbox d-inline"  href="#rzvy_staffservices_permissions" data-toggle="collapse" aria-expanded="false">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staffservices" name="rzvy_rolepermissions_cb[]" value="rzvy_staffservices"  <?php if(isset($rzvyroleoermissions['rzvy_staffservices'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_staffservices">Services</label>
							</div>
						</div>
						<div id="rzvy_staffservices_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_staffservices'])){ echo 'show'; } ?>">							
						<div class="custom-control custom-checkbox d-inline float-left col-12">
							<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staffservices_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_staffservices_edit"  <?php if(isset($rzvyroleoermissions['rzvy_staffservices_edit'])){ echo 'checked="checked"'; } ?> >
							<label class="custom-control-label" for="rzvy_staffservices_edit">Edit</label>
						</div>
					</div>
				</div>
				<div class="card my-2">
						<div class="card-header collapsed">
							<div class="custom-control custom-checkbox d-inline"  href="#rzvy_staffschedule_permissions" data-toggle="collapse" aria-expanded="false">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staffschedule" name="rzvy_rolepermissions_cb[]" value="rzvy_staffschedule"  <?php if(isset($rzvyroleoermissions['rzvy_staffschedule'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_staffschedule">Schedule</label>
							</div>
						</div>
						<div id="rzvy_staffschedule_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_staffschedule'])){ echo 'show'; } ?>">							
						<div class="custom-control custom-checkbox d-inline float-left col-12">
							<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staffschedule_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_staffschedule_edit"  <?php if(isset($rzvyroleoermissions['rzvy_staffschedule_edit'])){ echo 'checked="checked"'; } ?> >
							<label class="custom-control-label" for="rzvy_staffschedule_edit">Edit</label>
						</div>
						<div class="custom-control custom-checkbox d-inline float-left col-12">
							<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staffschedule_break" name="rzvy_rolepermissions_cb[]" value="rzvy_staffschedule_break"  <?php if(isset($rzvyroleoermissions['rzvy_staffschedule_break'])){ echo 'checked="checked"'; } ?> >
							<label class="custom-control-label" for="rzvy_staffschedule_break">Manage Breaks</label>
						</div>
						<div class="card my-2">
							<div class="card-header collapsed">
								<div class="custom-control custom-checkbox d-inline"  href="#rzvy_staff_as_permissions" data-toggle="collapse" aria-expanded="false">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff_as" name="rzvy_rolepermissions_cb[]" value="rzvy_staff_as"  <?php if(isset($rzvyroleoermissions['rzvy_staff_as'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_staff_as">Advance Schedule</label>
								</div>
							</div>
							<div id="rzvy_staff_as_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_staff_as'])){ echo 'show'; } ?>">
							<div class="custom-control custom-checkbox d-inline float-left col-12">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff_as_add" name="rzvy_rolepermissions_cb[]" value="rzvy_staff_as_add"  <?php if(isset($rzvyroleoermissions['rzvy_staff_as_add'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_staff_as_add">Add</label>
							</div>
							<div class="custom-control custom-checkbox d-inline float-left col-12">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff_as_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_staff_as_edit"  <?php if(isset($rzvyroleoermissions['rzvy_staff_as_edit'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_staff_as_edit">Edit</label>
							</div>
							<div class="custom-control custom-checkbox d-inline float-left col-12">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff_as_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_staff_as_delete"  <?php if(isset($rzvyroleoermissions['rzvy_staff_as_delete'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_staff_as_delete">Delete</label>
							</div>
							<div class="custom-control custom-checkbox d-inline float-left col-12">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff_as_status" name="rzvy_rolepermissions_cb[]" value="rzvy_staff_as_status"  <?php if(isset($rzvyroleoermissions['rzvy_staff_as_status'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_staff_as_status">Status</label>
							</div>
							<div class="custom-control custom-checkbox d-inline float-left col-12">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff_as_breaks" name="rzvy_rolepermissions_cb[]" value="rzvy_staff_as_breaks"  <?php if(isset($rzvyroleoermissions['rzvy_staff_as_breaks'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_staff_as_breaks">View Advance Schedule Breaks</label>
							</div>
							<div class="custom-control custom-checkbox d-inline float-left col-12">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff_as_breaksadd" name="rzvy_rolepermissions_cb[]" value="rzvy_staff_as_breaksadd"  <?php if(isset($rzvyroleoermissions['rzvy_staff_as_breaksadd'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_staff_as_breaksadd">Breaks Add</label>
							</div>
							<div class="custom-control custom-checkbox d-inline float-left col-12">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staff_as_breaksdelete" name="rzvy_rolepermissions_cb[]" value="rzvy_staff_as_breaksdelete"  <?php if(isset($rzvyroleoermissions['rzvy_staff_as_breaksdelete'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_staff_as_breaksdelete">Breaks Delete</label>
							</div>
						</div>
					</div>
					</div>
				</div>
				<div class="card my-2">
						<div class="card-header collapsed">
							<div class="custom-control custom-checkbox d-inline"  href="#rzvy_staffdaysoff_permissions" data-toggle="collapse" aria-expanded="false">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staffdaysoff" name="rzvy_rolepermissions_cb[]" value="rzvy_staffdaysoff"  <?php if(isset($rzvyroleoermissions['rzvy_staffdaysoff'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_staffdaysoff">Days Off</label>
							</div>
						</div>
						<div id="rzvy_staffdaysoff_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_staffdaysoff'])){ echo 'show'; } ?>">
						<div class="custom-control custom-checkbox d-inline float-left col-12">
							<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staffdaysoff_add" name="rzvy_rolepermissions_cb[]" value="rzvy_staffdaysoff_add"  <?php if(isset($rzvyroleoermissions['rzvy_staffdaysoff_add'])){ echo 'checked="checked"'; } ?> >
							<label class="custom-control-label" for="rzvy_staffdaysoff_add">Add</label>
						</div>
						<div class="custom-control custom-checkbox d-inline float-left col-12">
							<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_staffdaysoff_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_staffdaysoff_delete"  <?php if(isset($rzvyroleoermissions['rzvy_staffdaysoff_delete'])){ echo 'checked="checked"'; } ?> >
							<label class="custom-control-label" for="rzvy_staffdaysoff_delete">Delete</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_rolepermission_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_rolepermission" name="rzvy_rolepermissions_cb[]" value="rzvy_rolepermission"  <?php if(isset($rzvyroleoermissions['rzvy_rolepermission'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_rolepermission">Roles & Permissions</label>
				</div>
			</div>
			<div id="rzvy_rolepermission_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_rolepermission'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_rolepermission_add" name="rzvy_rolepermissions_cb[]" value="rzvy_rolepermission_add"  <?php if(isset($rzvyroleoermissions['rzvy_rolepermission_add'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_rolepermission_add">Add</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_rolepermission_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_rolepermission_edit"  <?php if(isset($rzvyroleoermissions['rzvy_rolepermission_edit'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_rolepermission_edit">Edit</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_rolepermission_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_rolepermission_delete"  <?php if(isset($rzvyroleoermissions['rzvy_rolepermission_delete'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_rolepermission_delete">Delete</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_rolepermission_status" name="rzvy_rolepermissions_cb[]" value="rzvy_rolepermission_status"  <?php if(isset($rzvyroleoermissions['rzvy_rolepermission_status'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_rolepermission_status">Status</label>
					</div>
					<div class="card my-2">
						<div class="card-header collapsed">
							<div class="custom-control custom-checkbox d-inline" href="#rzvy_rolepermission_view_permissions" data-toggle="collapse" aria-expanded="false">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_rolepermission_view" name="rzvy_rolepermissions_cb[]" value="rzvy_rolepermission_view"  <?php if(isset($rzvyroleoermissions['rzvy_rolepermission_view'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_rolepermission_view">View Role Permissions</label>
							</div>
						</div>
						<div id="rzvy_rolepermission_view_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_rolepermission_view'])){ echo 'show'; } ?>">
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_rolepermission_manage" name="rzvy_rolepermissions_cb[]" value="rzvy_rolepermission_manage"  <?php if(isset($rzvyroleoermissions['rzvy_rolepermission_manage'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_rolepermission_manage">Manage Role Permissions</label>
								</div>
						</div>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_gc_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_gc" name="rzvy_rolepermissions_cb[]" value="rzvy_gc"  <?php if(isset($rzvyroleoermissions['rzvy_gc'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_gc">Google Calendar</label>
				</div>
			</div>
			<div id="rzvy_gc_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_gc'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_gc_manage" name="rzvy_rolepermissions_cb[]" value="rzvy_gc_manage"  <?php if(isset($rzvyroleoermissions['rzvy_gc_manage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_gc_manage">Manage</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_blockoff_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_blockoff" name="rzvy_rolepermissions_cb[]" value="rzvy_blockoff"  <?php if(isset($rzvyroleoermissions['rzvy_blockoff'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_blockoff">Block Off</label>
				</div>
			</div>
			<div id="rzvy_blockoff_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_blockoff'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_blockoff_add" name="rzvy_rolepermissions_cb[]" value="rzvy_blockoff_add"  <?php if(isset($rzvyroleoermissions['rzvy_blockoff_add'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_blockoff_add">Add</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_blockoff_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_blockoff_edit"  <?php if(isset($rzvyroleoermissions['rzvy_blockoff_edit'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_blockoff_edit">Edit</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_blockoff_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_blockoff_delete"  <?php if(isset($rzvyroleoermissions['rzvy_blockoff_delete'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_blockoff_delete">Delete</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_blockoff_status" name="rzvy_rolepermissions_cb[]" value="rzvy_blockoff_status"  <?php if(isset($rzvyroleoermissions['rzvy_blockoff_status'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_blockoff_status">Status</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_locations_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_locations" name="rzvy_rolepermissions_cb[]" value="rzvy_locations"  <?php if(isset($rzvyroleoermissions['rzvy_locations'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_locations">Location Selector</label>
				</div>
			</div>
			<div id="rzvy_locations_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_locations'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_locations_manage" name="rzvy_rolepermissions_cb[]" value="rzvy_locations_manage"  <?php if(isset($rzvyroleoermissions['rzvy_locations_manage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_locations_manage">Manage</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_refundr_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_refundr" name="rzvy_rolepermissions_cb[]" value="rzvy_refundr"  <?php if(isset($rzvyroleoermissions['rzvy_refundr'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_refundr">Refund Requests</label>
				</div>
			</div>
			<div id="rzvy_refundr_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_refundr'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_refundr_cancel" name="rzvy_rolepermissions_cb[]" value="rzvy_refundr_cancel"  <?php if(isset($rzvyroleoermissions['rzvy_refundr_cancel'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_refundr_cancel">Cancel</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_refundr_approve" name="rzvy_rolepermissions_cb[]" value="rzvy_refundr_approve"  <?php if(isset($rzvyroleoermissions['rzvy_refundr_approve'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_refundr_approve">Mark As Completed</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_refundr_settings" name="rzvy_rolepermissions_cb[]" value="rzvy_refundr_settings"  <?php if(isset($rzvyroleoermissions['rzvy_refundr_settings'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_refundr_settings">Refund Settings View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_refundr_settingsmanage" name="rzvy_rolepermissions_cb[]" value="rzvy_refundr_settingsmanage"  <?php if(isset($rzvyroleoermissions['rzvy_refundr_settingsmanage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_refundr_settingsmanage">Refund Settings Manage</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_coupons_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_coupons" name="rzvy_rolepermissions_cb[]" value="rzvy_coupons"  <?php if(isset($rzvyroleoermissions['rzvy_coupons'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_coupons">Coupons</label>
				</div>
			</div>
			<div id="rzvy_coupons_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_coupons'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_coupons_add" name="rzvy_rolepermissions_cb[]" value="rzvy_coupons_add"  <?php if(isset($rzvyroleoermissions['rzvy_coupons_add'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_coupons_add">Add</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_coupons_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_coupons_edit"  <?php if(isset($rzvyroleoermissions['rzvy_coupons_edit'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_coupons_edit">Edit</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_coupons_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_coupons_delete"  <?php if(isset($rzvyroleoermissions['rzvy_coupons_delete'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_coupons_delete">Delete</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_referral_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_referral" name="rzvy_rolepermissions_cb[]" value="rzvy_referral"  <?php if(isset($rzvyroleoermissions['rzvy_referral'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_referral">Referral Settings</label>
				</div>
			</div>
			<div id="rzvy_referral_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_referral'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_referral_manage" name="rzvy_rolepermissions_cb[]" value="rzvy_referral_manage"  <?php if(isset($rzvyroleoermissions['rzvy_referral_manage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_referral_manage">Manage</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_fd_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_fd" name="rzvy_rolepermissions_cb[]" value="rzvy_fd"  <?php if(isset($rzvyroleoermissions['rzvy_fd'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_fd">Frequently Discount</label>
				</div>
			</div>
			<div id="rzvy_fd_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_fd'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_fd_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_fd_edit"  <?php if(isset($rzvyroleoermissions['rzvy_fd_edit'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_fd_edit">Edit</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_fd_status" name="rzvy_rolepermissions_cb[]" value="rzvy_fd_status"  <?php if(isset($rzvyroleoermissions['rzvy_fd_status'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_fd_status">Status</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_estemplates_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_estemplates" name="rzvy_rolepermissions_cb[]" value="rzvy_estemplates"  <?php if(isset($rzvyroleoermissions['rzvy_estemplates'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_estemplates">Email & SMS Templates</label>
				</div>
			</div>
			<div id="rzvy_estemplates_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_estemplates'])){ echo 'show'; } ?>">
					<div class="card my-2">
						<div class="card-header collapsed">
							<div class="custom-control custom-checkbox d-inline" href="#rzvy_etemplates_permissions" data-toggle="collapse" aria-expanded="false">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_etemplates" name="rzvy_rolepermissions_cb[]" value="rzvy_etemplates"  <?php if(isset($rzvyroleoermissions['rzvy_etemplates'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_etemplates">Email Templates</label>
							</div>
						</div>
						<div id="rzvy_etemplates_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_etemplates'])){ echo 'show'; } ?>">
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_etemplates_clientview" name="rzvy_rolepermissions_cb[]" value="rzvy_etemplates_clientview"  <?php if(isset($rzvyroleoermissions['rzvy_etemplates_clientview'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_etemplates_clientview">Client Templates View</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_etemplates_adminview" name="rzvy_rolepermissions_cb[]" value="rzvy_etemplates_adminview"  <?php if(isset($rzvyroleoermissions['rzvy_etemplates_adminview'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_etemplates_adminview">Admin Templates View</label>
								</div>								
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_etemplates_staffview" name="rzvy_rolepermissions_cb[]" value="rzvy_etemplates_staffview"  <?php if(isset($rzvyroleoermissions['rzvy_etemplates_staffview'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_etemplates_staffview">Staff Templates View</label>
								</div>								
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_etemplates_extraview" name="rzvy_rolepermissions_cb[]" value="rzvy_etemplates_extraview"  <?php if(isset($rzvyroleoermissions['rzvy_etemplates_extraview'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_etemplates_extraview">Extra Templates View</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_etemplates_manage" name="rzvy_rolepermissions_cb[]" value="rzvy_etemplates_manage"  <?php if(isset($rzvyroleoermissions['rzvy_etemplates_manage'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_etemplates_manage">Email Templates Manage</label>
								</div>
						</div>
					</div>
					<div class="card my-2">
						<div class="card-header collapsed">
							<div class="custom-control custom-checkbox d-inline" href="#rzvy_stemplates_permissions" data-toggle="collapse" aria-expanded="false">
								<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_stemplates" name="rzvy_rolepermissions_cb[]" value="rzvy_stemplates"  <?php if(isset($rzvyroleoermissions['rzvy_stemplates'])){ echo 'checked="checked"'; } ?> >
								<label class="custom-control-label" for="rzvy_stemplates">SMS Templates</label>
							</div>
						</div>
						<div id="rzvy_stemplates_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_stemplates'])){ echo 'show'; } ?>">
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_stemplates_clientview" name="rzvy_rolepermissions_cb[]" value="rzvy_stemplates_clientview"  <?php if(isset($rzvyroleoermissions['rzvy_stemplates_clientview'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_stemplates_clientview">Client Templates View</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_stemplates_adminview" name="rzvy_rolepermissions_cb[]" value="rzvy_stemplates_adminview"  <?php if(isset($rzvyroleoermissions['rzvy_stemplates_adminview'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_stemplates_adminview">Admin Templates View</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_stemplates_staffview" name="rzvy_rolepermissions_cb[]" value="rzvy_stemplates_staffview"  <?php if(isset($rzvyroleoermissions['rzvy_stemplates_staffview'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_stemplates_staffview">Staff Templates View</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_stemplates_extraview" name="rzvy_rolepermissions_cb[]" value="rzvy_stemplates_extraview"  <?php if(isset($rzvyroleoermissions['rzvy_stemplates_extraview'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_stemplates_extraview">Extra Templates View</label>
								</div>
								<div class="custom-control custom-checkbox d-inline float-left col-12">
									<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_stemplates_manage" name="rzvy_rolepermissions_cb[]" value="rzvy_stemplates_manage"  <?php if(isset($rzvyroleoermissions['rzvy_stemplates_manage'])){ echo 'checked="checked"'; } ?> >
									<label class="custom-control-label" for="rzvy_stemplates_manage">Extra Templates Manage</label>
								</div>
						</div>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_reminder_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_reminder" name="rzvy_rolepermissions_cb[]" value="rzvy_reminder"  <?php if(isset($rzvyroleoermissions['rzvy_reminder'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_reminder">Reminder</label>
				</div>
			</div>
			<div id="rzvy_reminder_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_reminder'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_reminder_manage" name="rzvy_rolepermissions_cb[]" value="rzvy_reminder_manage"  <?php if(isset($rzvyroleoermissions['rzvy_reminder_manage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_reminder_manage">Manage Settings</label>
					</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_bform_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_bform" name="rzvy_rolepermissions_cb[]" value="rzvy_bform"  <?php if(isset($rzvyroleoermissions['rzvy_bform'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_bform">Manage Booking Form</label>
				</div>
			</div>
			<div id="rzvy_bform_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_bform'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_bform_status" name="rzvy_rolepermissions_cb[]" value="rzvy_bform_status"  <?php if(isset($rzvyroleoermissions['rzvy_bform_status'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_bform_status">Field Enable/Disable</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_bform_rstatus" name="rzvy_rolepermissions_cb[]" value="rzvy_bform_rstatus"  <?php if(isset($rzvyroleoermissions['rzvy_bform_rstatus'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_bform_rstatus">Required Enable/Disable</label>
					</div>
			</div>			
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_language_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_language" name="rzvy_rolepermissions_cb[]" value="rzvy_language"  <?php if(isset($rzvyroleoermissions['rzvy_language'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_language">Languages</label>
				</div>
			</div>
			<div id="rzvy_language_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_language'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_language_default" name="rzvy_rolepermissions_cb[]" value="rzvy_language_default"  <?php if(isset($rzvyroleoermissions['rzvy_language_default'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_language_default">Manage Default Language</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_language_ddlang" name="rzvy_rolepermissions_cb[]" value="rzvy_language_ddlang"  <?php if(isset($rzvyroleoermissions['rzvy_language_ddlang'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_language_ddlang">Manage Dropdown Languages</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_language_translation" name="rzvy_rolepermissions_cb[]" value="rzvy_language_translation"  <?php if(isset($rzvyroleoermissions['rzvy_language_translation'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_language_translation">Manage Language Translation</label>
					</div>
			</div>			
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_colors_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_colors" name="rzvy_rolepermissions_cb[]" value="rzvy_colors"  <?php if(isset($rzvyroleoermissions['rzvy_colors'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_colors">Color Scheme</label>
				</div>
			</div>
			<div id="rzvy_colors_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_colors'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_colors_dashboard" name="rzvy_rolepermissions_cb[]" value="rzvy_colors_dashboard"  <?php if(isset($rzvyroleoermissions['rzvy_colors_dashboard'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_colors_dashboard">Manage Dashboard Colors</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_colors_booking" name="rzvy_rolepermissions_cb[]" value="rzvy_colors_booking"  <?php if(isset($rzvyroleoermissions['rzvy_colors_booking'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_colors_booking">Manage Booking Form Colors</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_colors_calendar" name="rzvy_rolepermissions_cb[]" value="rzvy_colors_calendar"  <?php if(isset($rzvyroleoermissions['rzvy_colors_calendar'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_colors_calendar">Manage Calendar Legend Colors</label>
					</div>
			</div>			
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_embed" name="rzvy_rolepermissions_cb[]" value="rzvy_embed"  <?php if(isset($rzvyroleoermissions['rzvy_embed'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_embed">Embed Frontend</label>
				</div>
			</div>			
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_support_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_support" name="rzvy_rolepermissions_cb[]" value="rzvy_support"  <?php if(isset($rzvyroleoermissions['rzvy_support'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_support">Support Tickets</label>
				</div>
			</div>
			<div id="rzvy_support_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_support'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_support_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_support_delete"  <?php if(isset($rzvyroleoermissions['rzvy_support_delete'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_support_delete">Delete</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_support_complete" name="rzvy_rolepermissions_cb[]" value="rzvy_support_complete"  <?php if(isset($rzvyroleoermissions['rzvy_support_complete'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_support_complete">Mark Completed</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_support_discussion" name="rzvy_rolepermissions_cb[]" value="rzvy_support_discussion"  <?php if(isset($rzvyroleoermissions['rzvy_support_discussion'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_support_discussion">Read Ticket Thread</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_support_reply" name="rzvy_rolepermissions_cb[]" value="rzvy_support_reply"  <?php if(isset($rzvyroleoermissions['rzvy_support_reply'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_support_reply">Reply In Ticket Thread</label>
					</div>
			</div>			
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_export_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_exportp" name="rzvy_rolepermissions_cb[]" value="rzvy_exportp"  <?php if(isset($rzvyroleoermissions['rzvy_exportp'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_exportp">Export</label>
				</div>
			</div>
			<div id="rzvy_export_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_exportp'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_exportp_services" name="rzvy_rolepermissions_cb[]" value="rzvy_exportp_services"  <?php if(isset($rzvyroleoermissions['rzvy_exportp_services'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_exportp_services">Services</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_exportp_appointments" name="rzvy_rolepermissions_cb[]" value="rzvy_exportp_appointments"  <?php if(isset($rzvyroleoermissions['rzvy_exportp_appointments'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_exportp_appointments">Appointments</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_exportp_payments" name="rzvy_rolepermissions_cb[]" value="rzvy_exportp_payments"  <?php if(isset($rzvyroleoermissions['rzvy_exportp_payments'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_exportp_payments">Payments</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_exportp_customers" name="rzvy_rolepermissions_cb[]" value="rzvy_exportp_customers"  <?php if(isset($rzvyroleoermissions['rzvy_exportp_customers'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_exportp_customers">Customers</label>
					</div>
			</div>			
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_feedback_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_feedback" name="rzvy_rolepermissions_cb[]" value="rzvy_feedback"  <?php if(isset($rzvyroleoermissions['rzvy_feedback'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_feedback">Feedback</label>
				</div>
			</div>
			<div id="rzvy_feedback_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_feedback'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_feedback_status" name="rzvy_rolepermissions_cb[]" value="rzvy_feedback_status"  <?php if(isset($rzvyroleoermissions['rzvy_feedback_status'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_feedback_status">Status</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_feedback_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_feedback_delete"  <?php if(isset($rzvyroleoermissions['rzvy_feedback_delete'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_feedback_delete">Delete</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_reviewsmanage" name="rzvy_rolepermissions_cb[]" value="rzvy_reviewsmanage"  <?php if(isset($rzvyroleoermissions['rzvy_reviewsmanage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_reviewsmanage">Ratings View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_reviewmange_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_reviewmange_delete"  <?php if(isset($rzvyroleoermissions['rzvy_reviewmange_delete'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_reviewmange_delete">Ratings Delete</label>
					</div>
			</div>			
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_docs" name="rzvy_rolepermissions_cb[]" value="rzvy_docs"  <?php if(isset($rzvyroleoermissions['rzvy_docs'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_docs">Documentation</label>
				</div>
			</div>
		</div>	
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_settings_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings" name="rzvy_rolepermissions_cb[]" value="rzvy_settings"  <?php if(isset($rzvyroleoermissions['rzvy_settings'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_settings">Settings</label>
				</div>
			</div>
			<div id="rzvy_settings_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_settings'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_company" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_company"  <?php if(isset($rzvyroleoermissions['rzvy_settings_company'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_company">Company Settings View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_companymanage" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_companymanage"  <?php if(isset($rzvyroleoermissions['rzvy_settings_companymanage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_companymanage">Company Settings Manage</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_appear" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_appear"  <?php if(isset($rzvyroleoermissions['rzvy_settings_appear'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_appear">Appearance Settings View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_appearmanage" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_appearmanage"  <?php if(isset($rzvyroleoermissions['rzvy_settings_appearmanage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_appearmanage">Appearance Settings Manage</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_payment" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_payment"  <?php if(isset($rzvyroleoermissions['rzvy_settings_payment'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_payment">Payment Settings View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_paymentmanage" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_paymentmanage"  <?php if(isset($rzvyroleoermissions['rzvy_settings_paymentmanage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_paymentmanage">Payment Settings Manage</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_email" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_email"  <?php if(isset($rzvyroleoermissions['rzvy_settings_email'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_email">Email Settings View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_emailmanage" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_emailmanage"  <?php if(isset($rzvyroleoermissions['rzvy_settings_emailmanage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_emailmanage">Email Settings Manage</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_sms" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_sms"  <?php if(isset($rzvyroleoermissions['rzvy_settings_sms'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_sms">SMS Settings View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_smsmanage" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_smsmanage"  <?php if(isset($rzvyroleoermissions['rzvy_settings_smsmanage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_smsmanage">SMS Settings Manage</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_seo" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_seo"  <?php if(isset($rzvyroleoermissions['rzvy_settings_seo'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_seo">SEO Settings View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_seomanage" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_seomanage"  <?php if(isset($rzvyroleoermissions['rzvy_settings_seomanage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_seomanage">SEO Settings Manage</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_wc" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_wc"  <?php if(isset($rzvyroleoermissions['rzvy_settings_wc'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_wc">Welcome Message Settings View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_wcmanage" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_wcmanage"  <?php if(isset($rzvyroleoermissions['rzvy_settings_wcmanage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_wcmanage">Welcome Message Settings Manage</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_booking" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_booking"  <?php if(isset($rzvyroleoermissions['rzvy_settings_booking'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_booking">Booking Form Settings View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_settings_bookingmanage" name="rzvy_rolepermissions_cb[]" value="rzvy_settings_bookingmanage"  <?php if(isset($rzvyroleoermissions['rzvy_settings_bookingmanage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_settings_bookingmanage">Booking Form Settings Manage</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_custom_messages" name="rzvy_rolepermissions_cb[]" value="rzvy_custom_messages"  <?php if(isset($rzvyroleoermissions['rzvy_custom_messages'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_custom_messages">Custom Messages Settings View</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_custom_messages_manage" name="rzvy_rolepermissions_cb[]" value="rzvy_custom_messages_manage"  <?php if(isset($rzvyroleoermissions['rzvy_custom_messages_manage'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_custom_messages_manage">Custom Messages Settings Manage</label>
					</div>
			</div>			
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_barbooking" name="rzvy_rolepermissions_cb[]" value="rzvy_barbooking"  <?php if(isset($rzvyroleoermissions['rzvy_barbooking'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_barbooking">Booking Notification In Top Bar</label>
				</div>
			</div>
		</div>	
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_barrefund" name="rzvy_rolepermissions_cb[]" value="rzvy_barrefund"  <?php if(isset($rzvyroleoermissions['rzvy_barrefund'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_barrefund">Refund Notification In Top Bar</label>
				</div>
			</div>
		</div>	
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_barsupport" name="rzvy_rolepermissions_cb[]" value="rzvy_barsupport"  <?php if(isset($rzvyroleoermissions['rzvy_barsupport'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_barsupport">Support Ticket Notification In Top Bar</label>
				</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_currverdis" name="rzvy_rolepermissions_cb[]" value="rzvy_currverdis"  <?php if(isset($rzvyroleoermissions['rzvy_currverdis'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_currverdis">Current Version Display</label>
				</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_setup" name="rzvy_rolepermissions_cb[]" value="rzvy_setup"  <?php if(isset($rzvyroleoermissions['rzvy_setup'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_setup">Setup</label>
				</div>
			</div>
		</div>
		<div class="card my-2">
			<div class="card-header collapsed">
				<div class="custom-control custom-checkbox d-inline" href="#rzvy_servicespackage_permissions" data-toggle="collapse" aria-expanded="false">
					<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_servciespackage" name="rzvy_rolepermissions_cb[]" value="rzvy_servciespackage"  <?php if(isset($rzvyroleoermissions['rzvy_servciespackage'])){ echo 'checked="checked"'; } ?> >
					<label class="custom-control-label" for="rzvy_servciespackage">Services Package</label>
				</div>
			</div>
			<div id="rzvy_servicespackage_permissions" class="border px-4 py-1 collapse <?php if(isset($rzvyroleoermissions['rzvy_servciespackage'])){ echo 'show'; } ?>">
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_servicespackage_add" name="rzvy_rolepermissions_cb[]" value="rzvy_servicespackage_add"  <?php if(isset($rzvyroleoermissions['rzvy_servicespackage_add'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_servicespackage_add">Add Package</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_servicespackage_edit" name="rzvy_rolepermissions_cb[]" value="rzvy_servicespackage_edit"  <?php if(isset($rzvyroleoermissions['rzvy_servicespackage_edit'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_servicespackage_edit">Edit Package</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_servicespackage_delete" name="rzvy_rolepermissions_cb[]" value="rzvy_servicespackage_delete"  <?php if(isset($rzvyroleoermissions['rzvy_servicespackage_delete'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_servicespackage_delete">Delete Package</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_servicespackage_status" name="rzvy_rolepermissions_cb[]" value="rzvy_servicespackage_status"  <?php if(isset($rzvyroleoermissions['rzvy_servicespackage_status'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_servicespackage_status">Manage Package Status</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_servicespackage_link" name="rzvy_rolepermissions_cb[]" value="rzvy_servicespackage_link"  <?php if(isset($rzvyroleoermissions['rzvy_servicespackage_link'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_servicespackage_link">Manage Package Service Link</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_servicespackage_limit" name="rzvy_rolepermissions_cb[]" value="rzvy_servicespackage_limit"  <?php if(isset($rzvyroleoermissions['rzvy_servicespackage_limit'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_servicespackage_limit">Manage Package Limits</label>
					</div>
					<div class="custom-control custom-checkbox d-inline float-left col-12">
						<input type="checkbox" class="custom-control-input rzvy_rolepermissions_cb" id="rzvy_servicespackage_purchases" name="rzvy_rolepermissions_cb[]" value="rzvy_servicespackage_purchases"  <?php if(isset($rzvyroleoermissions['rzvy_servicespackage_purchases'])){ echo 'checked="checked"'; } ?> >
						<label class="custom-control-label" for="rzvy_servicespackage_purchases">Packages Purchases View</label>
					</div>
			</div>
		</div>
		
		<div class="form-group">
			<?php if(isset($rzvy_rolepermissions['rzvy_rolepermission_manage']) || $rzvy_loginutype=='admin'){ ?>
				<button type="submit" class="btn btn-primary col-12" name="rzvy_rolepermissions_save"><i class="fa fa-floppy-o" aria-hidden="true"></i> <?php if(isset($rzvy_translangArr['save_role_permissions'])){ echo $rzvy_translangArr['save_role_permissions']; }else{ echo $rzvy_defaultlang['save_role_permissions']; } ?></button>
			<?php } ?>
		</div>
	<?php if(isset($rzvy_rolepermissions['rzvy_rolepermission_manage']) || $rzvy_loginutype=='admin'){ ?></form><?php } ?>
</div>	
<?php include 'footer.php'; ?>