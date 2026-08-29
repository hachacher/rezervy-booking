<?php 
class rzvy_w2s{
	/* Send SMS using Web2 */
	public function rzvy_send_web2_sms($phone,$sms_body,$rzvy_w2s_api_key,$rzvy_w2s_api_secret,$rzvy_w2s_sender) {
		$apiKey = urlencode($rzvy_w2s_api_key);
        $nonce = time();
        $method = "POST";
        $url = "/prepaid/message";
        $sender = urlencode($rzvy_w2s_sender);
        $recipient = $phone;
        $message = $sms_body;
        $scheduleDate = '';
        $validityDate = '';
        $visibleMessage = '';
        $callbackUrl = '';
        $secret = urlencode($rzvy_w2s_api_secret);
        $string = $apiKey . $nonce . $method . $url . $sender .
               $recipient . $message . $visibleMessage . $scheduleDate .
               $validityDate . $callbackUrl . $secret;
        
        $signature = hash('sha512', $string);
        $data = array(
           "apiKey" => $apiKey,
           "sender" => $sender,
           "recipient" => $recipient,
           "message" => $message,
           "scheduleDatetime" => $scheduleDate,
           "validityDatetime" => $validityDate,
           "callbackUrl" => $callbackUrl,
           "userData" => "",
           "visibleMessage" => $visibleMessage,
           "nonce" => $nonce);
           
        $curlurl = 'https://www.web2sms.ro/prepaid/message';
        $ch = curl_init($curlurl);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":" . $signature);
        $header = array();
        $header[] = 'Content-type: application/json';
        $header[] = 'Content-length: ' . strlen(json_encode($data));
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
	}
}