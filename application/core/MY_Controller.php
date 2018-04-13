<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $data = array();
    
    function __construct ()
    {
        parent::__construct();
       // date_default_timezone_set('Asia/Kolkata');
        if($this->session->userdata('logged_in')!=TRUE)
        {redirect('login/index');}
    	
    }
	
	 public function send_notification_ios($deviceToken, $payload)
    {		
		$passphrase = '12345'; // change this to your passphrase(password)

		$ctx = stream_context_create();
		//stream_context_set_option($ctx, 'ssl', 'local_cert','Production_Final.pem');
		stream_context_set_option($ctx, 'ssl', 'local_cert','UC_FINAL.pem');
		
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		//stream_context_set_option($ctx, 'ssl', 'cafile', 'entrust_2048_ca.cer');

		// Open a connection to the APNS server
		// for 
		//$oldErrorReporting = error_reporting(); // save error reporting level
		//error_reporting($oldErrorReporting ^ E_WARNING); // disable warnings
		
		$fp = stream_socket_client(
				'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx); 						 
		
		
	/*
		$fp = stream_socket_client(
				'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
	*/
		//error_reporting($oldErrorReporting); // restore error reporting level
		
        if (!$fp){ 
			return false;
		}	
                
        // Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

		// Send it to the server
		//$result = fwrite($fp, $msg, strlen($msg));
		try {                           
			$result = fwrite($fp, $msg, strlen($msg));
			//socket_close($fp);
			fclose($fp);			
			sleep(2);
		}
		catch (Exception $ex) {
			//socket_close($fp);
			fclose($fp);			
			sleep(2);
		}
    }
	

	public function send_multiple_user_notification_ios($deviceToken, $payload)
    {
		foreach($deviceToken as $token)
        {
			$this->send_notification_ios($token,$payload);			
        }
	}
	/*send notification for multiple users*/
    public function send_multiple_user_notification_iosold($deviceToken, $payload)
    {
        //print_r($deviceToken);exit();
        $passphrase = '12345'; // change this to your passphrase(password)
        //$cert = __DIR__ . '/UC_FINAL.pem';
		//echo $cert;exit;
        $ctx = stream_context_create();
		//stream_context_set_option($ctx, 'ssl', 'local_cert','Production_Final.pem');
		stream_context_set_option($ctx, 'ssl', 'local_cert','UC_FINAL.pem');
      
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        

        // Open a connection to the APNS server
        // for 
		$oldErrorReporting = error_reporting(); // save error reporting level
		error_reporting($oldErrorReporting ^ E_WARNING); // disable warnings
		
        $fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx); 
		
      /*  $fp = stream_socket_client(
                'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx); 
		*/
		error_reporting($oldErrorReporting); // restore error reporting level
		//stream_set_blocking ($fp, 0);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);

        foreach($deviceToken as $token)
        {
            $msg = chr(0) . pack('n',32) . pack('H*', $token) . pack('n',strlen($payload)) . $payload;
            // Build the binary notification
            //$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
        }
		usleep(500000);
        
		//print_r($result);exit;
        if (!$result){
            return false;
            //echo '<br>Message not delivered' . PHP_EOL . print_r($result);
        }            
        else{
            return true;
            //echo '<br>Message successfully delivered' . PHP_EOL . print_r($result);
        }
		fclose($fp);
    }
	
	public function send_notification_android($device_token, $message) {
        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send'; 

        $fields = array(
            'registration_ids' => $device_token,
            'data'=> $message,
        );

        $headers = array(
        'Authorization: key='. FCM_API_ACCESS_KEY, // FIREBASE_API_KEY_FOR_ANDROID_NOTIFICATION
        'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt( $ch,CURLOPT_URL, $url);
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        // Disabling SSL Certificate support temporarly
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        // Execute post
        $result = curl_exec($ch );
        // Close connection
        curl_close($ch);
        if($result === false){
            //die('Curl failed:' .curl_errno($ch));
            return false;
        }else{
            //echo "success";exit;
            return true;
        }
        // Close connection
        // curl_close( $ch );
        // return $result;
    }
}?>