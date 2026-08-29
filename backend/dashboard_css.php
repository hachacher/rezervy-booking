<?php 
$cs_asash = $obj_settings->get_option("rzvy_cs_admin_dash"); 
$cs_asash_pcolor = $obj_settings->get_option("rzvy_cs_admin_dash_primary_color"); 
$cs_asash_scolor = $obj_settings->get_option("rzvy_cs_admin_dash_secondary_color"); 
$cs_asash_bgcolor = $obj_settings->get_option("rzvy_cs_admin_dash_background_color"); 
$cs_asash_tcolor = $obj_settings->get_option("rzvy_cs_admin_dash_text_color"); 
 
if($cs_asash == "custom"){
	?>
	<style>
	.rzvy{ background-color: <?php echo $cs_asash_bgcolor; ?> !important; } 
	.rzvy .breadcrumb{ background-color: <?php echo $cs_asash_scolor; ?> !important; } 
	.rzvy .rzvy__main__sub__container, .rzvy .rzvy-content-wrapper{ background-color: <?php echo $cs_asash_bgcolor; ?> !important; color: <?php echo $cs_asash_tcolor; ?> !important; } 
	.rzvy #rzvy-mainnav{ background-color: <?php echo $cs_asash_pcolor; ?> !important; } 
	.rzvy #rzvy-mainnav.navbar-light .navbar-collapse .navbar-sidenav{ background: <?php echo $cs_asash_pcolor; ?> !important; } 
	
	.rzvy .rzvy_menubar{ background-color: <?php echo $cs_asash_pcolor; ?> !important; } 
	.rzvy .rzvy__main__container nav.navbar{ background: <?php echo $cs_asash_pcolor; ?> !important; } 
	.rzvy .rzvy_running_version, .rzvy #rzvy_menubar_toggle, .rzvy .rzvy_menubar_logo, .rzvy .rzvy_menubar_logo i { color: <?php echo $cs_asash_tcolor; ?> !important; } 
	
	.rzvy .rzvy_refer_box,
	.rzvy .btn-info:hover,
	.rzvy .btn-info,
	.rzvy .btn-warning:hover,
	.rzvy .btn-warning,
	.rzvy .btn-secondary:hover,
	.rzvy .btn-secondary,
	.rzvy .btn-primary:hover,
	.rzvy .btn-primary,
	.rzvy .btn-success:hover,
	.rzvy .btn-success,
	.rzvy .btn-outline-secondary:hover,
	.rzvy .btn-outline-secondary,
	.rzvy .btn-light,
	.rzvy .fc-list-table .fc-widget-header,
	.rzvy .rzvy-booking-detail-main,
	.rzvy-staff-selection,
	.rzvy .bg-light,
	.rzvy #rzvy-mainnav.navbar-light .navbar-collapse .navbar-sidenav{ background: <?php echo $cs_asash_pcolor; ?> !important; } 
	
	.rzvy #rzvy-mainnav a.navbar-brand,
	.rzvy #rzvy-mainnav.navbar-light .navbar-collapse .navbar-sidenav > .nav-item > .nav-link:hover,
	.rzvy #rzvy-mainnav.navbar-light .navbar-collapse .navbar-sidenav > .nav-item > .nav-link{ color: <?php echo $cs_asash_tcolor; ?> !important; }
	
	#rzvy-mainnav .navbar-collapse .navbar-nav .nav-item .nav-link{ color: <?php echo $cs_asash_tcolor; ?> !important; }
	
	
	.rzvy_menubar_badge, .rzvy_menubar ul li:hover .rzvy_menubar_nav_tooltip, .rzvy .rzvy__main__container .rzvy_menubar_nav_list li.rzvy_menubar_nav_active a, .rzvy .rzvy__main__container .rzvy_menubar_nav_list li a:hover{ color: <?php echo $cs_asash_tcolor; ?> !important; background: <?php echo $cs_asash_scolor; ?> !important; }
	
	.rzvy .card-header,
	.rzvy .card-body,
	.rzvy .dataTable tbody th,
	.rzvy .dataTable tbody tr,
	.rzvy .rzvy_staff_active,
	.rzvy .btn-light:hover,
	.rzvy .tab-content,
	.rzvy .modal-content,
	.rzvy .fc-list-table .fc-list-item,
	.rzvy .fc-list-item:hover td,
	#rzvy-mainnav .navbar-collapse .navbar-nav .nav-item .nav-link.rzy_active,
	.rzvy_manager_active, 
	#rzvy-manager-list li.active, 
	#rzvy-manager-list li:hover, 
	.rzvy_staff_active, 
	#rzvy-staff-list li.active, 
	#rzvy-staff-list li:hover,
	#rzvy-mainnav .navbar-collapse .navbar-nav .nav-item .nav-link:hover,
	#rzvy-mainnav .navbar-collapse .navbar-nav .nav-item.rzy_active .nav-link{ background: <?php echo $cs_asash_scolor; ?> !important; } 
	
	#rzvy-mainnav .navbar-collapse .navbar-nav .nav-item .nav-link{ color: <?php echo $cs_asash_tcolor; ?> !important; }
	
	#rzvy-mainnav .navbar-collapse .navbar-nav .nav-item .nav-link:hover,
	#rzvy-mainnav .navbar-collapse .navbar-nav .nav-item .nav-link.rzy_active{ color: <?php echo $cs_asash_tcolor; ?> !important; } 
	.rzvy .breadcrumb .breadcrumb-item + .breadcrumb-item::before,
	.rzvy .breadcrumb .breadcrumb-item.active,
	.rzvy .breadcrumb .breadcrumb-item a{ color: <?php echo $cs_asash_tcolor; ?> !important; } 
	
	.dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover,
	.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate,
	.dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active,
	.table-responsive label,
	.table-responsive{
		 color: <?php echo $cs_asash_tcolor; ?> !important; 
	}
	
	.rzvy select.form-control:not([size]):not([multiple]),
	.rzvy .card,
	.rzvy .modal-dialog,
	.rzvy .modal-dialog .modal-title,
	.form-control::placeholder{ color: <?php echo $cs_asash_tcolor; ?> !important; }
	
	.rzvy-tabbable-panel{
		background: <?php echo $cs_asash_scolor; ?> !important;
		border-color: <?php echo $cs_asash_scolor; ?> !important;
	}
	.rzvy-tabbable-line > .nav-tabs > li.open, 
	.rzvy-tabbable-line > .nav-tabs > li:hover,
	.rzvy-tabbable-line > .nav-tabs > li.active,
	.rzvy-tabbable-line > .nav-tabs > li.active > a > i,
	.rzvy-tabbable-line > .nav-tabs > li.active > a{
		background: <?php echo $cs_asash_pcolor; ?> !important;
		border-color: <?php echo $cs_asash_scolor; ?> !important;
		color: <?php echo $cs_asash_tcolor; ?> !important;
	}
	
	.rzvy-tabbable-line > .nav-tabs > li > a > i,
	.rzvy-tabbable-line > .nav-tabs > li > a{
		color: <?php echo $cs_asash_tcolor; ?> !important;
	}
	
	.rzvy-boxshadow.card{
		box-shadow: unset !important;
		background: <?php echo $cs_asash_scolor; ?> !important;
		border-color: <?php echo $cs_asash_pcolor; ?> !important;
	}
	
	.rzvy_menubar_lngmenu_link_dd,
	.rzvy_menubar_usermenu_link_dd,
	.rzvy_menubar_usermenu_link_dd a,
	.rzvy_set_language_atag,
	.rzvy .dropdown-menu-titles,
	#rzvy-supportticket-dropdown-content,
	#rzvy-refund-dropdown-content,
	#rzvy-notification-dropdown-content{
		color: <?php echo $cs_asash_tcolor; ?> !important;
		background: <?php echo $cs_asash_scolor; ?> !important;
	}
	
	.rzvy_menubar_usermenu_link_dd a:hover,
	a.rzvy_set_language_atag.bg-dark,
	a.rzvy_set_language_atag.bg-dark:hover,
	.rzvy_set_language_atag:hover{
		color: <?php echo $cs_asash_tcolor; ?> !important;
		background: <?php echo $cs_asash_pcolor; ?> !important;
	}
	
	.rzvy_menubar_usermenu_link_title,
	#rzvy-mainnav .navbar-collapse .navbar-nav > .nav-item.dropdown > .nav-link .indicator,
	.rzvy-notification-appointment-modal-link .rzvy_noti_deatil,
	.rzvy-notification-refundrequest-modal-link .rzvy_noti_deatil,
	.rzvy-notification-appointment-modal-link strong,
	.rzvy-notification-refundrequest-modal-link strong{
		color: <?php echo $cs_asash_tcolor; ?> !important;
	}
	.rzvy-subscription-pricing-table{
		background: <?php echo $cs_asash_pcolor; ?> !important;
	}
	
	.rzvy-subscription-pricing-table .rzvy-subscription-pricing-table-button{
		border-color: <?php echo $cs_asash_scolor; ?> !important;
	}
	.rzvy-subscription-pricing-table .rzvy-subscription-pricing-table-button::after{
		border-color: transparent <?php echo $cs_asash_scolor; ?> transparent transparent !important;
	}
	.rzvy-subscription-pricing-table .rzvy-subscription-pricing-table-button::before{
		border-color: transparent transparent transparent <?php echo $cs_asash_scolor; ?> !important;
	}
	
	.rzvy .rzvy_refer_box input,
	.rzvy .rzvy_refer_box h3,
	.rzvy .rzvy_refer_box i, 
	.rzvy .text-info,
	.rzvy a,
	.rzvy code,
	.rzvy .text-muted,
	.rzvy .close,
	.rzvy .breadcrumb .breadcrumb-item,
	.rzvy .breadcrumb a,
	.rzvy .card-header,
	.rzvy .card-body,
	.rzvy .apptlist_open_detail_modal,
	.rzvy .rzvy-upcoming-appointment-modal-link strong,
	.rzvy .rzvy-upcoming-appointment-modal-link span,
	.rzvy-subscription-pricing-table .rzvy-subscription-pricing-table-button,
	.rzvy-subscription-pricing-table .rzvy-white,
	.rzvy-subscription-pricing-table .rzvy-subscription-pricing-table-title,
	.rzvy-subscription-pricing-table .rzvy-subscription-pricing-table-plan-name{
		color: <?php echo $cs_asash_tcolor; ?> !important;
	}
	
		.rzvy .btn,
			box-shadow: unset !important;
		}
		.sweet-alert .sa-button-container .btn:focus,
		.sweet-alert .sa-button-container .btn:hover,
		.sweet-alert .sa-button-container .btn{
			border-color: <?php echo $cs_asash_scolor;?> !important;
			background-color: <?php echo $cs_asash_scolor;?> !important;
			color: <?php echo $cs_asash_tcolor;?> !important;
		}
		.sweet-alert .sa-icon span{
			background-color:<?php echo $cs_asash_tcolor;?> !important;
		}
		.sweet-alert .sa-icon{
			border-color: <?php echo $cs_asash_tcolor;?> !important;
		}
		.sweet-alert h2{
			color: <?php echo $cs_asash_tcolor;?> !important;
		}
		.sweet-alert .sa-icon.sa-success::before, .sweet-alert .sa-icon.sa-success::after{
			background: <?php echo $cs_asash_pcolor;?> !important;
		}
		.sweet-alert .sa-icon .sa-fix,
		.sweet-alert{
			background-color: <?php echo $cs_asash_pcolor;?> !important;
		}
		.bootstrap-select .dropdown-menu li a,
		.fc-view-container{
			color: <?php echo $cs_asash_scolor;?> !important;
		}
	</style>
	<?php 
} 
?>