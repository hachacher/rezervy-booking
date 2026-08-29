<?php 
$cs_bfls = $obj_settings->get_option("rzvy_cs_bfls"); 
$cs_bfls_pcolor = $obj_settings->get_option("rzvy_cs_bfls_primary_color"); 
$cs_bfls_scolor = $obj_settings->get_option("rzvy_cs_bfls_secondary_color"); 
/* $cs_bfls_bgcolor = $obj_settings->get_option("rzvy_cs_bfls_background_color"); 
$cs_bfls_tcolor = $obj_settings->get_option("rzvy_cs_bfls_text_color");  */
$cs_bfls_bgcolor = $cs_bfls_pcolor;
$cs_bfls_tcolor = $cs_bfls_pcolor;

if($cs_bfls == "custom"){
	list($p_r,$p_g,$p_b) = sscanf($cs_bfls_pcolor, "#%02x%02x%02x");
	list($s_r,$s_g,$s_b) = sscanf($cs_bfls_scolor, "#%02x%02x%02x");
	?>
	<style>	
	.rzvy-header, .rzvy-header > .container > ul{
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
	}
	.rzvy-header a,.hamburger a,.rzvy-header .country-select.inside .selected-flag,.hamburger .country-select.inside .selected-flag{
		color:<?php echo $cs_bfls_scolor;?> !important;
	}	
	.rzvy-header .container > ul > li:not(.logout) a:hover,.hamburger .container > ul > li:not(.logout) a:hover{
		background-color:<?php echo $cs_bfls_scolor;?> !important;
		color:<?php echo $cs_bfls_bgcolor;?> !important;
	}
	.country-selectd a:after{
		border-left: 2px solid <?php echo $cs_bfls_scolor;?> !important;
		border-bottom: 2px solid <?php echo $cs_bfls_scolor;?> !important;
	}
	.rzvy-header .user-avatar,.hamburger .user-avatar{
		background-color: <?php echo $cs_bfls_bgcolor;?> !important;
		border: 1px solid #e2e2e2 !important;
		color: <?php echo $cs_bfls_scolor;?> !important;
	}

	.rzvy-booking-detail-body .owl-carousel .owl-nav button:hover{
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
		color:<?php echo $cs_bfls_scolor;?> !important;
	}
	.rzvy-booking-detail-body  .read-more:hover{
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
		color:<?php echo $cs_bfls_scolor;?> !important;
		
	}
	.service-types figure:after, .services figure:after, .services.rounded figcaption:after {
		background-color:<?php echo $cs_bfls_scolor;?> !important;
		color:<?php echo $cs_bfls_bgcolor;?> !important;
		background-image: url('data:image/svg+xml,%3csvg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%23<?php echo str_replace('#','',$cs_bfls_bgcolor); ?>" class="bi bi-check-circle-fill" viewBox="0 0 16 16"%3e%3cpath d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/%3e%3c/svg%3e');

	}
	
	.rzvy-booking-detail-body .services figure:before, .services.rounded figcaption:before, .service-types .selected figure, .rzvy-staff-change-tt.selected{
		border-color:<?php echo $cs_bfls_bgcolor;?> !important;
	}
	.services .item .service-meta .fa, .offcanvas .fa,.rzvy-booking-detail-body .services .read-more {
		color:<?php echo $cs_bfls_bgcolor;?>;
		
	}
	.services .item figcaption .tag{
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
		color:<?php echo $cs_bfls_scolor;?> !important;
	}
	.read-more:hover:after{
		background-image: url('data:image/svg+xml,%3csvg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%23<?php echo str_replace('#','',$cs_bfls_scolor); ?>" class="bi bi-arrow-right-short" viewBox="0 0 16 16"%3e%3cpath fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/%3e%3c/svg%3e');
		
	}
	.read-more:after{
		background-image: url('data:image/svg+xml,%3csvg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%23<?php echo str_replace('#','',$cs_bfls_bgcolor); ?>" class="bi bi-arrow-right-short" viewBox="0 0 16 16"%3e%3cpath fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/%3e%3c/svg%3e');
		filter: unset !important;
	}
	
	
	.quantity-controler{
		background-color:<?php echo $cs_bfls_scolor;?> !important;
		color:<?php echo $cs_bfls_bgcolor;?> !important;
		border: 1px solid <?php echo $cs_bfls_bgcolor;?> !important;
	}
	.quantity-controler .fa{
		color:<?php echo $cs_bfls_bgcolor;?> !important;
	}
	.quantity input[type="number"]{
		color:<?php echo $cs_bfls_bgcolor;?> !important;
	}
	.rzvy-inline-calendar-container-main-rowcel.full_day_available.active_selected_date{
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
		color:<?php echo $cs_bfls_scolor;?> !important;
	}
	.slot_refresh_div .rzvy-styled-radio:hover{
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
		color:<?php echo $cs_bfls_scolor;?> !important;
	}
	.rzvy_available_slots_block .selected .form-check.custom:not(.inline){
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
		color:<?php echo $cs_bfls_scolor;?> !important;
	}

	.selected .form-check.custom:not(.inline){
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
		color:<?php echo $cs_bfls_scolor;?> !important;	
	}
	.form-check-input{
		border: 2px solid <?php echo $cs_bfls_bgcolor;?> !important;	
	}
	.form-check-input:checked{
		border-color:<?php echo $cs_bfls_scolor;?> !important;
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;	
	}
	.owl-carousel button.owl-dot.active{
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
		color:<?php echo $cs_bfls_scolor;?> !important;	
	}
	.btn-success{
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
		color:<?php echo $cs_bfls_scolor;?> !important;		
	}
	.btn-success:hover{
		filter: brightness(80%);
	}
	#rzvy_book_appointment_btn.btn-success,#rzvy_apply_referral_code_btn.btn-success{		
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
		color:<?php echo $cs_bfls_scolor;?> !important;
	}	
	#rzvy_book_appointment_btn.btn-success:hover,#rzvy_apply_referral_code_btn.btn-success:hover{
		filter: brightness(80%);
	}	
	#rzvy_location_check_btn.btn-success{
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
		color:<?php echo $cs_bfls_scolor;?> !important;	
	}	
	.location-modal .bootstrap-select .btn-light{
		border: 1px  solid <?php echo $cs_bfls_bgcolor;?> !important;
	}
	main.rzvy-booking-detail-body{
		background-color:<?php echo $cs_bfls_scolor;?> !important;
	}
	.form-check a{
		color:<?php echo $cs_bfls_bgcolor;?> !important;	
	}
	.form-check-input:focus{
		box-shadow:0 0 0 0.25rem rgb(<?php echo $cs_bfls_scolor;?> / 25%)
	}
	.point-wrap .fa.fa-tag,.point-wrap .fa.fa-money{
		color:<?php echo $cs_bfls_bgcolor;?> !important;	
	}	
	.rzvy_applied_coupon_div , .rzvy_applied_referral_coupon_div_text,.card-payment, .rzvy-card-detail-box,{
		color:<?php echo $cs_bfls_scolor;?> !important;	
	}
	#rzvy_refresh_cart .fa:not(.fa-trash){
		color:<?php echo $cs_bfls_bgcolor;?> !important;	
	}
	.rzvy_remove_addon_icon.fa{
		color:red !important;
	}
	.rzvy-review a i:hover {
		color:<?php echo $cs_bfls_bgcolor;?> !important;
	}
	.sa-button-container .btn-primary{
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;
		color:<?php echo $cs_bfls_scolor;?> !important;	
	}
	.country-selectd a:hover:after {
		border-left: 2px solid <?php echo $cs_bfls_bgcolor;?> !important;	
		border-bottom: 2px solid <?php echo $cs_bfls_bgcolor;?> !important;	
		border-color:<?php echo $cs_bfls_bgcolor;?> !important;	
	}
	.hamburger.active span{
		background:<?php echo $cs_bfls_bgcolor;?> !important;	
	}
	.hamburger span:before,.hamburger span:after,.hamburger span{
		background:<?php echo $cs_bfls_scolor;?> !important;	
	}
	#rzvy-partial-deposit-info i,#rzvy-loyalty-points-info i , #rzvy_logout_btn, .rzvy_reset_slot_selection ,.rzvy_back_to_calendar i,.rzvy_back_to_calendar{
		color: <?php echo $cs_bfls_bgcolor;?> !important;	
	}
	.review-link{
		background:<?php echo $cs_bfls_bgcolor;?> !important;	
	}
	.review-link:hover{
		background:<?php echo $cs_bfls_bgcolor;?> !important;	
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;	
		opacity:0.8;
	}
	.xdsoft_datetimepicker .xdsoft_calendar td.xdsoft_current > div, .xdsoft_datetimepicker .xdsoft_calendar td:hover:not(.xdsoft_other_month) > div:hover , .xdsoft_datetimepicker .xdsoft_label > .xdsoft_select > div > .xdsoft_option.xdsoft_current{
		background-color:<?php echo $cs_bfls_bgcolor;?> !important;	
	}
	#rzvy-fifth-step .fa , #rzvy-fifth-step p a{
		color:<?php echo $cs_bfls_bgcolor;?> !important;	
	}
	#rzvy_login_form a:hover{
		color:<?php echo $cs_bfls_bgcolor;?> !important;	
	}
	.rzvy_cal_label{
		color:<?php echo $cs_bfls_bgcolor;?> !important;
	}
	</style>
	<?php 
} 
?>