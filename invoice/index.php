<?php 
include(dirname(dirname(__FILE__))."/constants.php"); 
/* Include class files */
include(dirname(dirname(__FILE__))."/classes/class_settings.php");
include(dirname(dirname(__FILE__))."/classes/class_loyalty_points.php");

/* Create object of classes */	
$obj_settings = new rzvy_settings();
$obj_settings->conn = $conn;

$obj_lpoint = new rzvy_loyalty_points();
$obj_lpoint->conn = $conn;

if(!isset($_SESSION['login_type'])) {
	if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
}else if(!isset($_GET['invoice'])){
	if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
}else{
	$decoded_invoice = base64_decode($_GET['invoice'], true);
	if ($decoded_invoice) {
		if (strpos($decoded_invoice, '_____') !== false) {
			$exploded_invoice = explode("_____",$decoded_invoice);
			if(sizeof($exploded_invoice) == 4){
				$decoded_userid = base64_decode($exploded_invoice[1], true);
				if ($decoded_userid >=0) {
					if($_SESSION['login_type'] == "customer") {
						if(isset($_SESSION['customer_id'])) {
							if($decoded_userid == $_SESSION['customer_id']){
								$decoded_orderid = base64_decode($exploded_invoice[3], true);
								if ($decoded_orderid) {
									$get_invoice_data = $obj_settings->get_invoice_data($decoded_orderid);
									if(mysqli_num_rows($get_invoice_data)>0){
										$invoice_data = mysqli_fetch_assoc($get_invoice_data);
									}else{
										if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
									}
								}else{
									if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
								}
							}else{
								if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
							}
						}else{
							if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
						}
					}else if($_SESSION['login_type'] == "admin" || $_SESSION['login_type'] == "staff") {
						$decoded_orderid = base64_decode($exploded_invoice[3], true);
						if ($decoded_orderid) {
							$get_invoice_data = $obj_settings->get_invoice_data($decoded_orderid);
							if(mysqli_num_rows($get_invoice_data)>0){
								$invoice_data = mysqli_fetch_assoc($get_invoice_data);
							}else{
								if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
							}
						}else{
							if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
						}
					}else{
						if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
					}
				}else{
					if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
				}
			}else{
				if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
			}
		}else{
			if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
		}
	}else{
		if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
	}
}

$get_service_category = $obj_settings->get_service_category($invoice_data["service_id"]);
if(mysqli_num_rows($get_service_category)>0){
	$service_category_data = mysqli_fetch_assoc($get_service_category);
	$category_name = ucwords($service_category_data["cat_name"]);
	$service_name = ucwords($service_category_data["title"]);
}else{
	if(isset($rzvy_translangArr['inv_auth_failed'])){ echo $rzvy_translangArr['inv_auth_failed']; }else{ echo $rzvy_defaultlang['inv_auth_failed']; } exit;
}
$rzvy_seo_meta_tag = $obj_settings->get_option('rzvy_seo_meta_tag');
$companyname = $obj_settings->get_option("rzvy_company_name"); 
$rzy_company_logo = $obj_settings->get_option("rzvy_company_logo");
$rzvy_date_format = $obj_settings->get_option("rzvy_date_format");
$rzvy_currency_symbol = $obj_settings->get_option('rzvy_currency_symbol');
$rzvy_currency_position = $obj_settings->get_option('rzvy_currency_position');
$rzvy_company_email = $obj_settings->get_option("rzvy_company_email");
$rzvy_company_phone = $obj_settings->get_option("rzvy_company_phone");
$rzvy_company_address = $obj_settings->get_option("rzvy_company_address");
$rzvy_company_city = $obj_settings->get_option("rzvy_company_city");
$rzvy_company_state = $obj_settings->get_option("rzvy_company_state");
$rzvy_company_zip = $obj_settings->get_option("rzvy_company_zip");
$rzvy_company_country = $obj_settings->get_option("rzvy_company_country");

$order_date = date($rzvy_date_format, strtotime($invoice_data["order_date"]));
$customer_name = ucwords($invoice_data["c_firstname"]." ".$invoice_data["c_lastname"]);
$customer_address = $invoice_data["c_address"];
$customer_phone = $invoice_data["c_phone"];
$customer_city = $invoice_data["c_city"];
$customer_state = $invoice_data["c_state"];
$customer_country = $invoice_data["c_country"];
$customer_zip = $invoice_data["c_zip"];
$payment_method = ucwords($invoice_data["payment_method"]);
if($payment_method==ucwords('pay-at-venue')){
	if(isset($rzvy_translangArr['pay_at_venue'])){ $payment_method = $rzvy_translangArr['pay_at_venue']; }else{ $payment_method = $rzvy_defaultlang['pay_at_venue']; } 
}
$transaction_id = $invoice_data["transaction_id"];
$sub_total = $invoice_data["sub_total"];
$discount = $invoice_data["discount"];
$tax = $invoice_data["tax"];
$frequently_discount = $invoice_data["fd_amount"];
$refer_discount = $invoice_data["refer_discount"];
$net_total = $invoice_data["net_total"];
$partial_deposite = $invoice_data["partial_deposite"];
$service_rate = $invoice_data["service_rate"]; 

/* Loyalty Points If Used */
if(isset($rzvy_translangArr['points'])){ $pointslabel =  $rzvy_translangArr['points']; }else{ $pointslabel = $rzvy_defaultlang['points']; }
$rzvy_allow_loyalty_points_status = $obj_settings->get_option('rzvy_allow_loyalty_points_status');
$rzvy_perbooking_loyalty_point_value = $obj_settings->get_option('rzvy_perbooking_loyalty_point_value');
$loyalty_points_used =0;
$loyalty_points_usedval =0;
if($rzvy_allow_loyalty_points_status=='Y'){
	$usedpoints = $obj_lpoint->get_used_points_customer_by_order_id($invoice_data['order_id']);
	if(isset($usedpoints) && $usedpoints!=''){
	    $loyalty_points_used = $usedpoints;
	    $loyalty_points_usedval = $rzvy_perbooking_loyalty_point_value*$usedpoints;
	}
}
$loyalty_points_amt = $loyalty_points_usedval;
$loyalty_points_amount = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$loyalty_points_usedval).'('.$loyalty_points_used.' '.$pointslabel.')';
if($loyalty_points_used==0){
    $loyalty_points_amount = $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$loyalty_points_usedval);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php if($rzvy_seo_meta_tag != ""){ echo $rzvy_seo_meta_tag; }else{ echo $companyname; } ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo SITE_URL; ?>includes/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?php echo SITE_URL; ?>includes/css/rzvy-invoice.css" rel="stylesheet">
</head>
<body id="rzvy_invoice_container">
<div class="page-content container">
    <div class="page-header text-blue-d2">
        <h1 class="page-title text-secondary-d1">
            <?php if(isset($rzvy_translangArr['invoice_id'])){ echo $rzvy_translangArr['invoice_id']; }else{ echo $rzvy_defaultlang['invoice_id']; } ?>
            <small class="page-info">
                <i class="fa fa-angle-double-right text-80"></i>
                 #<?php echo "000000".$decoded_orderid; ?>
            </small>
        </h1>
        <div class="page-tools">
            <div class="action-buttons">
                <a class="mx-1px text-95 btn btn-outline-primary" onclick="window.print()" href="javascript:void(0)" data-title="<?php if(isset($rzvy_translangArr['print_btn'])){ echo $rzvy_translangArr['print_btn']; }else{ echo $rzvy_defaultlang['print_btn']; } ?>">
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                    <?php if(isset($rzvy_translangArr['print_btn'])){ echo $rzvy_translangArr['print_btn']; }else{ echo $rzvy_defaultlang['print_btn']; } ?>
                </a>
            </div>
        </div>
    </div>

    <div class="container px-0">
        <div class="row mt-4">
            <div class="col-12 col-lg-10 offset-lg-1">
                <div class="row">
                    <div class="col-12">
                        <div class="text-center text-150">
                            <i class="fa fa-book fa-2x text-success-m2 mr-1"></i>
                            <span class="text-default-d3"><a class="navbar-brand" href="<?php echo SITE_URL; ?>"><?php if($rzy_company_logo != "" && file_exists("uploads/images/".$rzy_company_logo)){ ?><img height="90px" width="90px;" class="rzvy-sacompanylogo" src="<?php echo SITE_URL; ?>uploads/images/<?php echo $rzy_company_logo; ?>" /> <?php }else{ echo ucwords($companyname); } ?></a></span>
                        </div>
                        <div class="text-center text-grey-m2">
							<?php if($rzvy_company_address != ""){ ?>
								<div class="my-1"><?php echo $rzvy_company_address; ?></div>
							<?php } ?>
							<?php if($rzvy_company_city != "" && $rzvy_company_state == ""){ ?>
								<div class="my-1"><?php echo $rzvy_company_city; ?></div>
							<?php } ?>
							<?php if($rzvy_company_state != ""){ ?>
								<div class="my-1"><?php if($rzvy_company_city != ""){ echo $rzvy_company_city.", ".$rzvy_company_state; }else{ echo $rzvy_company_state; } ?></div>
							<?php } ?>
							<?php if($rzvy_company_zip != "" && $rzvy_company_country == ""){ ?>
								<div class="my-1"><?php echo $rzvy_company_zip; ?></div>
							<?php } ?>
							<?php if($rzvy_company_country != ""){ ?>
								<div class="my-1"><?php if($rzvy_company_zip != ""){ echo $rzvy_company_country." - ".$rzvy_company_zip; }else{ echo $rzvy_company_country; } ?></div>
							<?php } ?>
                            <div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary"></i> <b class="text-600"><?php echo $rzvy_company_email; ?></b></div>
                            <div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary"></i> <b class="text-600"><?php echo $rzvy_company_phone; ?></b></div>
                        </div>
                    </div>
                </div>
                <!-- .row -->

                <hr class="row brc-default-l1 mx-n1 mb-4" />

                <div class="row">
                    <div class="col-sm-6">
                        <div>
                            <span class="text-sm text-grey-m2 align-middle"><?php if(isset($rzvy_translangArr['to_colon'])){ echo $rzvy_translangArr['to_colon']; }else{ echo $rzvy_defaultlang['to_colon']; } ?></span>
                            <span class="text-600 text-110 text-blue align-middle"><?php echo $customer_name; ?></span>
                        </div>
                        <div class="text-grey-m2">
							<?php if($customer_address != ""){ ?>
								<div class="my-1"><?php echo $customer_address; ?></div>
							<?php } ?>
							<?php if($customer_city != "" && $customer_state == ""){ ?>
								<div class="my-1"><?php echo $customer_city; ?></div>
							<?php } ?>
							<?php if($customer_state != ""){ ?>
								<div class="my-1"><?php if($customer_city != ""){ echo $customer_city.", ".$customer_state; }else{ echo $customer_state; } ?></div>
							<?php } ?>
							<?php if($customer_zip != "" && $customer_country == ""){ ?>
								<div class="my-1"><?php echo $customer_zip; ?></div>
							<?php } ?>
							<?php if($customer_country != ""){ ?>
								<div class="my-1"><?php if($customer_zip != ""){ echo $customer_country." - ".$customer_zip; }else{ echo $customer_country; } ?></div>
							<?php } ?>
                            <div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary"></i> <b class="text-600"><?php echo $customer_phone; ?></b></div>
                        </div>
                    </div>
                    <!-- /.col -->

                    <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                        <hr class="d-sm-none" />
                        <div class="text-grey-m2">
                           <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90"><?php if(isset($rzvy_translangArr['invoice_id'])){ echo $rzvy_translangArr['invoice_id']; }else{ echo $rzvy_defaultlang['invoice_id']; } ?></span> #<?php echo "000000".$decoded_orderid; ?></div>

                            <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90"><?php if(isset($rzvy_translangArr['payment_date_colon'])){ echo $rzvy_translangArr['payment_date_colon']; }else{ echo $rzvy_defaultlang['payment_date_colon']; } ?></span> <?php echo $order_date; ?></div>

                            <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90"><?php if(isset($rzvy_translangArr['payment_method_colon'])){ echo $rzvy_translangArr['payment_method_colon']; }else{ echo $rzvy_defaultlang['payment_method_colon']; } ?></span> <span class="badge badge-warning badge-pill px-25"><?php echo $payment_method; ?></span></div>
                            <?php if($transaction_id!=""){ ?>
                            <div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90"><?php if(isset($rzvy_translangArr['transaction_id_ad'])){ echo $rzvy_translangArr['transaction_id_ad']; }else{ echo $rzvy_defaultlang['transaction_id_ad']; } ?></span> <b><?php echo $transaction_id; ?></b></div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>

                <div class="mt-4">
                    <div class="row text-600 text-white bgc-default-tp1 py-25">
                        <div class="col-10 col-sm-10"><?php if(isset($rzvy_translangArr['description'])){ echo $rzvy_translangArr['description']; }else{ echo $rzvy_defaultlang['description']; } ?></div>
                        <div class="col-2"><?php if(isset($rzvy_translangArr['amount'])){ echo $rzvy_translangArr['amount']; }else{ echo $rzvy_defaultlang['amount']; } ?></div>
                    </div>

                    <div class="text-95 text-secondary-d3">
                        <div class="row mb-2 mb-sm-0 py-25">
                            <div class="col-10 col-sm-10"><?php echo $category_name." - ".$service_name; ?></div>
                            <div class="col-2 text-secondary-d2"><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$service_rate); ?></div>
                        </div>
						<?php 
						$unserialized_addons = unserialize($invoice_data["addons"]);
						if(sizeof($unserialized_addons)>0){
							?>
							<div class="row text-600 text-white bgc-default-tp1 py-25">
								<div class="d-none d-sm-block col-12"><?php if(isset($rzvy_translangArr['addons'])){ echo $rzvy_translangArr['addons']; }else{ echo $rzvy_defaultlang['addons']; } ?></div>
							</div>
							<?php 
							$i = 1;
							foreach($unserialized_addons as $addon){
								$addon_name = $obj_settings->get_addon_name($addon['id']);
								?>
								<div class="row mb-2 mb-sm-0 py-25 <?php echo ($i%2==0)?"bgc-default-l4":""; ?>">
									<div class="col-10 col-sm-10"><?php echo $addon['qty']." ".$addon_name; ?></div>
									<div class="col-2 text-secondary-d2"><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$addon['rate']); ?></div>
								</div>
								<?php 
								$i++;
							}
						}
						?>
						<?php 
						/* Services Package Purchases */
							$Rzvy_Hooks->do_action('services_package_purchase_invoice', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang,$rzvy_currency_symbol,$invoice_data['order_id'],$rzvy_currency_position));
						/* Services Package Purchases End */
						?>
                    </div>

                    <div class="row border-b-2 brc-default-l2"></div>
					
                    <div class="row mt-3">
                        <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                            <?php if(isset($rzvy_translangArr['invoice_extra_note'])){ echo $rzvy_translangArr['invoice_extra_note']; }else{ echo $rzvy_defaultlang['invoice_extra_note']; } ?>
                        </div>
						
                        <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
							<?php 
							/* Services Package Listing */
								$Rzvy_Hooks->do_action('services_package_discount_invoice', array((isset($rzvy_translangArr)?$rzvy_translangArr:array()), $rzvy_defaultlang,$rzvy_currency_symbol,$invoice_data['order_id'],$rzvy_currency_position));
							/* Services Package Listing End */
							?>
							<?php if($sub_total>0){ ?>
                            <div class="row my-2">
                                <div class="col-7 text-right">
                                    <?php if(isset($rzvy_translangArr['sub_total'])){ echo $rzvy_translangArr['sub_total']; }else{ echo $rzvy_defaultlang['sub_total']; } ?>
                                </div>
                                <div class="col-5">
                                    <span class="text-120 text-secondary-d1"><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$sub_total); ?></span>
                                </div>
                            </div>
							<?php } ?>
							
							<?php if($loyalty_points_amt>0){ ?>
							<div class="row my-2">
                                <div class="col-7 text-right">
                                    <?php if(isset($rzvy_translangArr['loyalty_points'])){ echo $rzvy_translangArr['loyalty_points']; }else{ echo $rzvy_defaultlang['loyalty_points']; } ?>:
                                </div>
                                <div class="col-5">
                                    <span class="text-120 text-secondary-d1">-<?php echo $loyalty_points_amount; ?></span>
                                </div>
                            </div>
							<?php } ?>
							<?php if($frequently_discount>0){ ?>
							<div class="row my-2">
                                <div class="col-7 text-right">
                                    <?php if(isset($rzvy_translangArr['frequently_discount'])){ echo $rzvy_translangArr['frequently_discount']; }else{ echo $rzvy_defaultlang['frequently_discount']; } ?>
                                </div>
                                <div class="col-5">
                                    <span class="text-120 text-secondary-d1">-<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$frequently_discount); ?></span>
                                </div>
                            </div>
							<?php } ?>
							<?php if($discount>0){ ?>
							<div class="row my-2">
                                <div class="col-7 text-right">
                                    <?php if(isset($rzvy_translangArr['coupon_discount'])){ echo $rzvy_translangArr['coupon_discount']; }else{ echo $rzvy_defaultlang['coupon_discount']; } ?>
                                </div>
                                <div class="col-5">
                                    <span class="text-120 text-secondary-d1">-<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$discount); ?></span>
                                </div>
                            </div>
							<?php } ?>
							<?php if($refer_discount>0){ ?>
							<div class="row my-2">
                                <div class="col-7 text-right">
                                    <?php if(isset($rzvy_translangArr['referral_coupon_discount'])){ echo $rzvy_translangArr['referral_coupon_discount']; }else{ echo $rzvy_defaultlang['referral_coupon_discount']; } ?>
                                </div>
                                <div class="col-5">
                                    <span class="text-120 text-secondary-d1">-<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$refer_discount); ?></span>
                                </div>
                            </div>
							<?php } ?>
							<?php if($tax>0){ ?>
                            <div class="row my-2">
                                <div class="col-7 text-right">
                                    <?php if(isset($rzvy_translangArr['tax'])){ echo $rzvy_translangArr['tax']; }else{ echo $rzvy_defaultlang['tax']; } ?>
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">+<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$tax); ?></span>
                                </div>
                            </div> 
							<?php } ?>
							<?php if($partial_deposite>0){ ?>
                            <div class="row my-2">
                                <div class="col-7 text-right">
                                    <?php if(isset($rzvy_translangArr['partial_deposite'])){ echo $rzvy_translangArr['partial_deposite']; }else{ echo $rzvy_defaultlang['partial_deposite']; } ?>
                                </div>
                                <div class="col-5">
                                    <span class="text-110 text-secondary-d1">-<?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$partial_deposite); ?></span>
                                </div>
                            </div> 
							<?php } ?>
							<?php if($net_total>0){ ?>
                            <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                <div class="col-7 text-right">
                                    <?php if(isset($rzvy_translangArr['net_total'])){ echo $rzvy_translangArr['net_total']; }else{ echo $rzvy_defaultlang['net_total']; } ?>
                                </div>
                                <div class="col-5">
                                    <span class="text-150 text-success-d3 opacity-2"><?php echo $obj_settings->rzvy_currency_position($rzvy_currency_symbol,$rzvy_currency_position,$net_total); ?></span>
                                </div>
                            </div>
							<?php } ?>
                        </div>
                    </div>

                    <hr />

                    <div>
                        <span class="text-secondary-d1 text-105"><?php if(isset($rzvy_translangArr['invoice_thanks'])){ echo $rzvy_translangArr['invoice_thanks']; }else{ echo $rzvy_defaultlang['invoice_thanks']; } ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo SITE_URL; ?>includes/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo SITE_URL; ?>includes/vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>