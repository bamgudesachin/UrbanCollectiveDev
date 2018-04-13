<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');
require_once('./application/libraries/REST_Controller.php');

class MY_Controller1 extends REST_Controller
{
    public $data = array();
    
    function __construct ()
    {
        parent::__construct();
        $post_data = $this->post();
        $token = $post_data['token'];
        $_POST['token'] = $post_data['token'];
        $token_expiry = $this->token_expiry($token);
        if ($token_expiry){
            $status = $this->is_token_active($token_expiry);            
            if($status){      
                $this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Token expired','Result'=>''), 401);   
            }            
        }else{
            $this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Token Mismatch','Result'=>''), 401);
        }
    }

    public function token_expiry($token)
    {
        $sql = "SELECT tokenExpiry FROM users WHERE token='".$token."'";
        $record = $this->db->query($sql);
        if ($record->num_rows()>0) {
            return $record->row('tokenExpiry');
        }
    }


    function is_token_active($ts)
    {	if ($ts <= time()) {
            return true;
        } else {
            return false;
        }
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
			sleep(3);
		}
		catch (Exception $ex) {
			//socket_close($fp);
			fclose($fp);			
			sleep(3);
		}
    }
	
	

	public function send_multiple_user_notification_ios($deviceToken, $payload)
    {
		//print_r($deviceToken);exit();
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

        $ctx = stream_context_create();
		//stream_context_set_option($ctx, 'ssl', 'local_cert','Production_Final.pem');
		stream_context_set_option($ctx, 'ssl', 'local_cert','UC_FINAL.pem');     
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        //stream_context_set_option($ctx, 'ssl', 'cafile', 'entrust_2048_ca.cer');

        // Open a connection to the APNS server
        // for 
		$oldErrorReporting = error_reporting(); // save error reporting level
		error_reporting($oldErrorReporting ^ E_WARNING); // disable warnings
	
        $fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx); 
		
		
       /* $fp = stream_socket_client(
                'ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx); 
		*/
		
		error_reporting($oldErrorReporting); // restore error reporting level
		//stream_set_blocking ($fp, 0);
        if (!$fp){
			 exit("Failed to connect: $err $errstr" . PHP_EOL);
			 //echo "Failed to connect: $err $errstr" . PHP_EOL;exit;
		}
           
			

        foreach($deviceToken as $token)
        {
            $msg = chr(0) . pack('n',32) . pack('H*', $token) . pack('n',strlen($payload)) . $payload;
            // Build the binary notification
            //$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
        }
		usleep(500000);
        
        if (!$result){
            return false;
            //echo '<br>Message not delivered' . PHP_EOL . print_r($result);exit();
        }            
        else{
            return true;
            //echo '<br>Message successfully delivered' . PHP_EOL . print_r($result);exit();
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