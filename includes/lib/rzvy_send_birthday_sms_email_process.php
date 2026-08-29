<?php 
include(dirname(dirname(dirname(__FILE__))).'/includes/sms/twilio/autoload.php');
use Twilio\Rest\Client;
			
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include(dirname(dirname(dirname(__FILE__))).'/includes/sms/plivo/rzvy_plivo.php');
include(dirname(dirname(dirname(__FILE__))).'/includes/sms/nexmo/rzvy_nexmo.php');
include(dirname(dirname(dirname(__FILE__))).'/includes/sms/textlocal/rzvy_textlocal.php');
include(dirname(dirname(dirname(__FILE__))).'/includes/sms/web2sms/rzvy_w2s.php');
				
/*send SMS & Email code start **/
$rzvy_customer_email_notification_status = $obj_settings->get_option('rzvy_customer_email_notification_status');
$rzvy_customer_sms_notification_status = $obj_settings->get_option('rzvy_customer_sms_notification_status');

if(isset($es_r_template)){
	if($es_r_template == "birthday" && ($rzvy_customer_email_notification_status == "Y" || $rzvy_customer_sms_notification_status == "Y")){
		$customer_email_sms_template_data = $obj_es_information->get_email_template($es_r_template, "customer");
		$customer_email_sms_template_row_count = mysqli_num_rows($customer_email_sms_template_data);
		
		if($customer_email_sms_template_row_count>0){
			
			/** Get detail for email **/
			$customer_name = ucwords(htmlentities($es_r_firstname)." ".htmlentities($es_r_lastname));
			$customer_email = $es_r_email;
			$customer_phone = $es_r_phone;
			$company_name = $obj_settings->get_option('rzvy_company_name');
			$company_email = $obj_settings->get_option('rzvy_company_email');
			$company_phone = $obj_settings->get_option('rzvy_company_phone');
			$company_address = $obj_settings->get_option('rzvy_company_address').", ".$obj_settings->get_option('rzvy_company_city').", ".$obj_settings->get_option('rzvy_company_state').", ".$obj_settings->get_option('rzvy_company_country')." - ".$obj_settings->get_option('rzvy_company_zip');
			$company_logo = $obj_settings->get_option('rzvy_company_logo');
			
			if($company_logo != "" && file_exists(dirname(dirname(dirname(__FILE__)))."/uploads/images/".$company_logo)){
				$company_logo = SITE_URL.'uploads/images/'.$company_logo;
			}else{
				$company_logo = "";
			}
			
			$tags_array = array('{{{customer_name}}}', '{{{company_name}}}', '{{{company_email}}}', '{{{company_phone}}}', '{{{company_address}}}', '{{{company_logo}}}');

			$replace_array = array($customer_name, $company_name, $company_email, $company_phone, $company_address, $company_logo);
		
			$rzvy_email_sender_name = $obj_settings->get_option('rzvy_email_sender_name');
			$rzvy_email_sender_email = $obj_settings->get_option('rzvy_email_sender_email');
			$rzvy_email_smtp_hostname = $obj_settings->get_option("rzvy_email_smtp_hostname");
			$rzvy_email_smtp_username = $obj_settings->get_option("rzvy_email_smtp_username");
			$rzvy_email_smtp_password = $obj_settings->get_option("rzvy_email_smtp_password");
			$rzvy_email_smtp_port = $obj_settings->get_option("rzvy_email_smtp_port");
			$rzvy_email_encryption_type = $obj_settings->get_option("rzvy_email_encryption_type");
			$rzvy_email_smtp_authentication = $obj_settings->get_option("rzvy_email_smtp_authentication");
			$rzvy_send_email_with = $obj_settings->get_option("rzvy_send_email_with");
			
			$mail_SMTPAuth = $rzvy_email_smtp_authentication;
			
			/********************* Customer Email & SMS ************************/
			if($customer_email_sms_template_row_count>0){
				$customer_email_sms_template = mysqli_fetch_array($customer_email_sms_template_data);
				/* Customer Email */
				if($rzvy_customer_email_notification_status == "Y" && $customer_email_sms_template["email_status"] == "Y"){ 
					$rzvy_customer_email_template = $customer_email_sms_template["email_content"];
					$customer_template = base64_decode($rzvy_customer_email_template);
					$customer_email_body = str_replace($tags_array,$replace_array,$customer_template);
					$customer_email_subject = str_replace($tags_array,$replace_array,$customer_email_sms_template["subject"]);
					
					/* Send Mail code start here */
					try {
						$cmail = new PHPMailer(true, $conn);
						if($rzvy_send_email_with=='SMTP' &&  $rzvy_email_smtp_hostname != '' && $rzvy_email_sender_name != '' && $rzvy_email_sender_email != '' && $rzvy_email_smtp_username != '' && $rzvy_email_smtp_password != '' && $rzvy_email_smtp_port != ''){
							$cmail->isSMTP();
							$cmail->Host = $rzvy_email_smtp_hostname;
							$cmail->SMTPAuth = $mail_SMTPAuth;
							$cmail->Username = $rzvy_email_smtp_username;
							$cmail->Password = $rzvy_email_smtp_password;
							$cmail->SMTPSecure = $rzvy_email_encryption_type;
							$cmail->Port = $rzvy_email_smtp_port;
						}else{
							$cmail->isMail();
						}
						$cmail->isHTML(true);
						$cmail->SMTPDebug  = 0;
						$cmail->CharSet = "UTF-8";
						$cmail->setFrom($rzvy_email_sender_email, $rzvy_email_sender_name);
						$cmail->Subject = $customer_email_subject;
						$cmail->Body = $customer_email_body;
						$cmail->addAddress($customer_email, $customer_name);
						$cmail->send();
					} catch (Exception $e) { }
				}
				
				/* Customer SMS */
				if($rzvy_customer_sms_notification_status == "Y" && $customer_email_sms_template["sms_status"] == "Y"){
					$rzvy_customer_sms_template = $customer_email_sms_template["sms_content"];
					$customer_smstemplate = base64_decode($rzvy_customer_sms_template);
					$customer_sms_body = str_replace($tags_array,$replace_array,$customer_smstemplate);
					
					/** Send SMS using Twilio **/
					if($obj_settings->get_option("rzvy_twilio_sms_status") == "Y"){
						$rzvy_twilio_account_SID = $obj_settings->get_option("rzvy_twilio_account_SID");
						$rzvy_twilio_auth_token = $obj_settings->get_option("rzvy_twilio_auth_token");
						$rzvy_twilio_sender_number = $obj_settings->get_option("rzvy_twilio_sender_number");
						
						if($rzvy_twilio_account_SID != "" && $rzvy_twilio_auth_token != "" && $rzvy_twilio_sender_number != ""){
							try {
								$twilio_obj = new Client($rzvy_twilio_account_SID, $rzvy_twilio_auth_token);
								$response = $twilio_obj->messages->create($customer_phone,
								   array(
									   "from" => $rzvy_twilio_sender_number,
									   "body" => $customer_sms_body
								   )
								);
							} catch (Exception $e) { }
						}
					}
					
					/** Send SMS using Plivo **/
					if($obj_settings->get_option("rzvy_plivo_sms_status") == "Y"){
						$rzvy_plivo_account_SID = $obj_settings->get_option("rzvy_plivo_account_SID");
						$rzvy_plivo_auth_token = $obj_settings->get_option("rzvy_plivo_auth_token");
						$rzvy_plivo_sender_number = $obj_settings->get_option("rzvy_plivo_sender_number");
						
						if($rzvy_plivo_account_SID != "" && $rzvy_plivo_auth_token != "" && $rzvy_plivo_sender_number != ""){
							try {
								$plivo_obj = new rzvy_Plivo\RestAPI($rzvy_plivo_account_SID, $rzvy_plivo_auth_token, '', '');
								$params = array(
									'src' => $rzvy_plivo_sender_number,
									'dst' => $customer_phone,
									'text' => $customer_sms_body,
									'method' => 'POST'
								);
								$response = $plivo_obj->send_message($params);
							} catch (Exception $e) { }
						}
					}
					
					/** Send SMS using Nexmo **/
					if($obj_settings->get_option("rzvy_nexmo_sms_status") == "Y"){
						$rzvy_nexmo_api_key = $obj_settings->get_option("rzvy_nexmo_api_key");
						$rzvy_nexmo_api_secret = $obj_settings->get_option("rzvy_nexmo_api_secret");
						$rzvy_nexmo_from = $obj_settings->get_option("rzvy_nexmo_from");
						
						if($rzvy_nexmo_api_key != "" && $rzvy_nexmo_api_secret != "" && $rzvy_nexmo_from != ""){
							try {
								$nexmo_obj = new rzvy_nexmo();
								$response = $nexmo_obj->rzvy_send_nexmo_sms($customer_phone,$customer_sms_body,$rzvy_nexmo_api_key,$rzvy_nexmo_api_secret,$rzvy_nexmo_from);
							} catch (Exception $e) { }
						}
					}
					
					/** Send SMS using TextLocal **/
					if($obj_settings->get_option("rzvy_textlocal_sms_status") == "Y"){
						$rzvy_textlocal_api_key = $obj_settings->get_option("rzvy_textlocal_api_key");
						$rzvy_textlocal_sender = $obj_settings->get_option("rzvy_textlocal_sender");
						$rzvy_textlocal_country = $obj_settings->get_option("rzvy_textlocal_country");
						
						if($rzvy_textlocal_api_key != "" && $rzvy_nexmo_api_secret != "" && $rzvy_textlocal_sender != ""){
							try {
								$textlocal_obj = new rzvy_textlocal();
								$response = $textlocal_obj->rzvy_send_textlocal_sms($customer_phone,$customer_sms_body,$rzvy_textlocal_api_key,$rzvy_textlocal_country,$rzvy_textlocal_sender);
							} catch (Exception $e) { }
						}
					}
					
					
					/** Send SMS using Web2SMS **/
					if($obj_settings->get_option("rzvy_w2s_sms_status") == "Y"){
						$rzvy_w2s_api_key = $obj_settings->get_option("rzvy_w2s_api_key");
						$rzvy_w2s_api_secret = $obj_settings->get_option("rzvy_w2s_api_secret");
						$rzvy_w2s_sender = $obj_settings->get_option("rzvy_w2s_sender");
						
						if($rzvy_w2s_api_key != "" && $rzvy_w2s_api_secret != "" && $rzvy_w2s_sender != ""){
							try {
								$w2s_obj = new rzvy_w2s();
								$response = $w2s_obj->rzvy_send_web2_sms($customer_phone,$customer_sms_body,$rzvy_w2s_api_key,$rzvy_w2s_api_secret,$rzvy_w2s_sender);
							} catch (Exception $e) { }
						}
					}
				}
			}
		}
	}
}