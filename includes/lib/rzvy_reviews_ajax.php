<?php 
/* Include class files */
include(dirname(dirname(dirname(__FILE__)))."/constants.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_feedback.php");
include(dirname(dirname(dirname(__FILE__)))."/classes/class_settings.php");

/* Create object of classes */
$obj_feedback = new rzvy_feedback();
$obj_feedback->conn = $conn;
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

/* Refresh Guest customers ajax */
if(isset($_REQUEST['refresh_reviews_detail'])){
	$rzvy_date_format = $obj_settings->get_option('rzvy_date_format');
	$rzvy_time_format = $obj_settings->get_option('rzvy_time_format');
	$all_feedbacks_detail = $obj_feedback->get_all_reviews($_POST['start'],($_POST['start']+$_POST['length']), $_POST['search']['value'],$_POST['order'][0]['column'],$_POST['order'][0]['dir'],$_POST['draw']);
	$customers = array();
	$customers["draw"] = $_POST['draw'];
	$count_all_reviews = $obj_feedback->count_all_reviews($_POST['search']['value']);
	if($count_all_reviews == "" || $count_all_reviews == null){
		$count_all_reviews = 0;
	}
	$customers["recordsTotal"] = $count_all_reviews;
	$customers["recordsFiltered"] = $count_all_reviews;
	$customers['data'] =array();
	$rating_star_array = array(
		"0" => '<i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i>',
		"1" => '<i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i>',
		"2" => '<i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i>',
		"3" => '<i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star-o text-warning"></i><i class="fa fa-lg fa-star-o text-warning"></i>',
		"4" => '<i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star-o text-warning"></i>',
		"5" => '<i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i><i class="fa fa-lg fa-star"></i>',
	);
	
	
	
	if(mysqli_num_rows($all_feedbacks_detail)>0){
		$i=$_POST['start'];
		while($rc = mysqli_fetch_assoc($all_feedbacks_detail)){
			$i++;
			$rc_arr = array();
			$ratingaction = '';
			if(isset($rzvy_rolepermissions['rzvy_reviewmange_delete']) || $rzvy_loginutype=='admin'){
				$ratingaction = '<a class="btn btn-danger rzvy-white btn-sm rzvy_delete_rating_btn m-1" data-id="'.$rc["id"].'"><i class="fa fa-fw fa-trash"></i></a>';
			}
			
			array_push($rc_arr, ucwords($rc['c_firstname'].' '.$rc['c_lastname']));
			array_push($rc_arr, $rc['c_email']);
			array_push($rc_arr, $rating_star_array[$rc['rating']]);
			array_push($rc_arr, $rc['review']);
			array_push($rc_arr, date($rzvy_date_format." ".$rzvy_time_format, strtotime($rc['review_datetime'])));
			array_push($rc_arr,$ratingaction);
			array_push($customers['data'], $rc_arr);
		}
	}
	echo json_encode($customers);
}
if(isset($_POST['delete_rating'],$_POST['id'])){
	$obj_feedback->id = $_POST['id'];
	$feedback_deleted = $obj_feedback->delete_rating();
	if($feedback_deleted){
		echo "deleted";
	}else{
		echo "failed";
	}
}