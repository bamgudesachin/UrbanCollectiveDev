<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Upload extends CI_Controller {

    public function __construct()
    {   
            parent::__construct();
           
            include APPPATH . 'libraries/classes/class.phpmailer.php';
            $this->config->load('my_constants');
            $this->load->model('mobile_api/Common_model');
            $this->load->model('mobile_api/Auth_model');
            $this->load->helper('security');
            $this->load->helper('string');
            $this->load->helper(array('form', 'url'));
    }
    
	public function test_video_upload(){
            
		$this->load->view('admin/test_upload_video');   
	}
	
	/*Check the login user is present and also in tribe*/
    public function user_in_tribe_check($userId = null,$searchId = null)
    {   
        $status = $this->Common_model->user_in_tribe_check($userId,$searchId);  
        if ($status){
            return true;            
        }else{
            $this->form_validation->set_message('user_in_tribe_check', '{field} does not exists in the tribe');
            return false;
        }
    }

       
        
    public function add_comments(){
           // print_r($_FILES);exit();
           // $path = $_FILES['commentFile']['name'];
            //$key = 'commentFile';
          //  $path2 = $_FILES['video_thumbnail']['name'];
            $post_data = $this->input->post();
			//print_r($post_data);exit();
            $this->form_validation->set_data($post_data);
            $this->form_validation->set_error_delimiters('', '');
            //print_r($post_data['searchId']);exit();
            $userId = array_key_exists("userId",$post_data);
            if($userId){ 
                $userId =  $post_data['userId']; 
               // print_r($userId);exit();
                    $this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_in_tribe_check['.$post_data['searchId'].']'); 
            }

            if ($this->form_validation->run() === false) {
                $response = array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>FALSE,'Status' =>400);
                echo json_encode($response);           
            }else{

                $fileType =  trim($this->input->post('fileType'));
                if(!$fileType){
                    $fileType = NULL;
                }
                
                $files = $_FILES;
                if($fileType == 'video'){
                    $video_path = $this->upload_file('commentFile',$_FILES,$fileType,1);
                    $image_path = $this->upload_file('video_thumbnail',$_FILES,$fileType,2);
                    if (!$video_path) {
                        $response = array(
                            'ResponseCode' => 0,
                            'ResponseMessage' => 'FAILURE', 
                            'Comments' => $_POST['ERROR'],
                            'Result' =>false,
                            'Status' =>400
                         );
                         echo json_encode($response);
                    }else{
                        $url = $_POST['url'];
                        if($url){
                            $url = $url;
                        }else{
                            $url = NULL;
                        }
                    }

                    if (!$image_path) {
                        $response = array(
                            'ResponseCode' => 0,
                            'ResponseMessage' => 'FAILURE', 
                            'Comments' => $_POST['ERROR'],
                            'Result' =>false,
                            'Status' =>400
                         );
                         echo json_encode($response);
                    }else{
                        $thumbnail_url = $_POST['thumbnail_url'];
                        if($thumbnail_url){
                            $thumbnail_url = $thumbnail_url;
                        }else{
                            $thumbnail_url = NULL;
                        }
                    }
                    $commentMessage = "Video uploaded successfully";
                }
                if($fileType == 'image' || $fileType == 'audio'){
                    
                    $video_path = $this->upload_file('commentFile',$_FILES,$fileType,1);
                    if (!$video_path) {                 
                        $response = array(
                            'ResponseCode' => 0,
                            'ResponseMessage' => 'FAILURE', 
                            'Comments' => $_POST['ERROR'],
                            'Result' =>false,
                            'Status' =>400
                         );
                         echo json_encode($response);
                    }else{
                        $url = $_POST['url'];                   
                        if($url){
                            $url = $url;
                        }else{
                            $url = NULL;                        
                        }
                    }
                    $thumbnail_url = NULL;
                    $commentMessage = $fileType.' uploaded successfully';
                }            
                
                if(!$fileType){
                    $url = NULL;
                    $thumbnail_url = NULL;
                    $commentMessage = "Comment added successfully";
                    
                }
               // if($url && $thumbnail_url){
                    $data = array(
                        'commentFile' => $url,
                        'video_thumbnail' => $thumbnail_url,
                        'commentText' =>  $this->input->post('commentText'),
                        'userId' =>  $this->input->post('userId'),
                        'searchId' =>  $this->input->post('searchId'),
                        'shortlistedId' =>  $this->input->post('shortlistedId'),
                        'roomType' => $this->input->post('roomType'),
                        'fileType' => $fileType//$_FILES['commentFile']['type']
                    ); 
                
                    $is_insert =  $this->Common_model->uploadData($data);
                    if($is_insert){
						ob_start();
                        $response = array(
                            'ResponseCode' => 1,
                            'ResponseMessage' => 'SUCCESS', 
                            'Comments' => $commentMessage,
                            'Result' =>$is_insert,
                            'Status' =>200
                         );
                        echo json_encode($response); 
                        // Get the size of the output.
                        $size = ob_get_length();
                        // Disable compression (in case content length is compressed).
                        header("Content-Encoding: none");
                        // Set the content length of the response.
                        header("Content-Length: {$size}");
                        // Close the connection.
                        header("Connection: close");
                        // Flush all output.
                        ob_end_flush();
                        ob_flush();
                        flush();
                        
                        $post_data = array();
                        $post_data['userId'] = $this->input->post('userId');
                        $post_data['searchId'] = $this->input->post('searchId');
                        $post_data['shortlistedId'] = $this->input->post('shortlistedId');
                        /* Send notification to exist members */
                        /* get the login user info and shortlisted property info for push */                    
                        $user_info = $this->Common_model->get_user_property_info($post_data);
                        if($user_info){
                            $user_name = trim($user_info[0]['firstName']);
                            $propertyName = trim($user_info[0]['propertyName']);
                            $propertyId = $user_info[0]['propertyId'];
                            $userId = $user_info[0]['userId'];

                            /* Send notification to my tribe members */
                            $get_tribes_deviceToken = $this->Common_model->get_tribes_deviceToken($post_data['userId'],$post_data['searchId']);
                            
                            if($get_tribes_deviceToken){

                                $deviceToken = ARRAY();
                                $androidTribeDeviceTokens = ARRAY();

                                foreach ($get_tribes_deviceToken as $row) {
                                    if($row["deviceToken"]){
                                        if($row["deviceType"] == "android"){
                                            $androidTribeDeviceTokens[] = $row["deviceToken"];
                                        }else{
                                            $deviceToken[] = $row["deviceToken"];
                                        }                                      
                                    }                                                             
                                }

                                $message = $this->config->item('notification_add_comment_on_shortlisted_property');
                                $message_to_push = str_replace("firstName",$user_name, $message[0]);                       
                                $message_to_push_event = str_replace("propertyName", $propertyName, $message_to_push);
                                $message_to_push_notification = $message_to_push_event;

                                if(sizeof($deviceToken)>0){
                                    $payload = json_encode([
                                        'aps' => [
                                            'alert' => $message_to_push_notification,
                                            'sound' => 'cat.caf',
                                            'badge' => 1, 
                                            'content-available' => 1,
                                            'category' => 'CATEGORY_COMMENT'
                                        ],
                                        "push_type"=> $message[1],
                                        "userId"=> $userId,
                                        "searchId"=> $this->input->post('searchId'),
                                        "shortlistedId"=> $this->input->post('shortlistedId')
                                    ]); 
                                   // print_r($payload);exit();
                                    $this->send_multiple_user_notification_ios($deviceToken, $payload);
                                }

                                if(sizeof($androidTribeDeviceTokens)>0){
                                    $payload = array("message" => $message_to_push_notification,
                                                    "push_type"=> $message[1],
                                                    "userId"=> $userId,
                                                    "searchId"=> $this->input->post('searchId'),
                                                    "shortlistedId"=> $this->input->post('shortlistedId'));
                                              //print_r($payload);exit;
                                    $this->send_notification_android($androidTribeDeviceTokens, $payload);
                                }
                            }
                        }

                      /*  $response = array(
                            'ResponseCode' => 1,
                            'ResponseMessage' => 'SUCCESS', 
                            'Comments' => $commentMessage,
                            'Result' =>$is_insert,
                            'Status' =>200
                         );
						 */
                    } else {
                        unlink($url);
                        $response = array(
                            'ResponseCode' => 0,
                            'ResponseMessage' => 'FAILURE', 
                            'Comments' => 'Could not upload '.$fileType.' ',
                            'Result' =>$is_insert,
                            'Status' =>400
                         );
						 echo json_encode($response);
                    }
                // }else{
                //     $response = array(
                //             'ResponseCode' => 0,
                //             'ResponseMessage' => 'FAILURE', 
                //             'Comments' => 'File you are trying to upload is not valid',
                //             'Result' =>false,
                //             'Status' =>400
                //          );
                // }
                
                
                //echo json_encode($response);
            }
    }


    public function upload_file($key,$files,$fileType,$state){
			
            //print_r($files);exit();
            //$path = $_FILES['commentFile']['name'];
            //$path = $_FILES['commentFile']['name'];
            $path = $_FILES[$key]['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $video_name = random_string('numeric', 5);
           // $configVideo['file_name'] = $video_name.".".$ext;
            $file_path = $video_name.".".$ext;
			
            /* upload video */  
            if($fileType == 'image'){
                $configVideo['upload_path'] = 'uploads/comment/image';
                $configVideo['allowed_types'] = 'gif|jpg|jpeg|png';
            } 
            if($fileType == 'video'){
                $configVideo['upload_path'] = 'uploads/comment/video';
                $configVideo['allowed_types'] = 'wmv|mp4|avi|mov|png|jpg|gif|jpeg';
            }      

            if($fileType == 'audio'){
                $configVideo['upload_path'] = 'uploads/comment/audio';
                $configVideo['allowed_types'] = '*';
            }    
            
           // $configVideo['allowed_types'] = 'wmv|mp4|avi|mov|png|jpg|gif|jpeg';
            $configVideo['max_size'] = '1000000';
            $configVideo['overwrite'] = FALSE;
            $configVideo['remove_spaces'] = TRUE;
            $configVideo['file_name'] = $file_path;
           
		   

            $this->load->library('upload', $configVideo);
            $this->upload->initialize($configVideo);

            if (!$this->upload->do_upload($key)) {
				//echo $fileType;exit();
                $_POST['ERROR'] =strip_tags($this->upload->display_errors());
				
               /* $response = array(
                    'ResponseCode' => 0,
                    'ResponseMessage' => 'FAILURE', 
                    'Comments' => strip_tags($this->upload->display_errors()),
                    'Result' =>false,
                    'Status' =>400
                 );
                 echo json_encode($response);*/
                 return false;
            } else {
                 /*if($_FILES['commentFile']['type'] == 'video/mp4' || $_FILES['commentFile']['type'] == 'video/wmv' || $_FILES['commentFile']['type'] == 'video/avi' || $_FILES['commentFile']['type'] == 'video/mov' || $_FILES['commentFile']['type'] == 'video/quicktime'){

                    
                    $url = 'uploads/comment/video/'.$configVideo['file_name'];
                }*/
                if($state==1){
					
                   $_POST['url'] = 'uploads/comment/'.$fileType.'/'.$configVideo['file_name'];
				   
                } else {
                   $_POST['thumbnail_url'] = 'uploads/comment/video/'.$configVideo['file_name'];
                }
                
                return true;
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

        $ctx = stream_context_create();
        //stream_context_set_option($ctx, 'ssl', 'local_cert','Production_Final.pem');
		stream_context_set_option($ctx, 'ssl', 'local_cert','UC_FINAL.pem');
        //stream_context_set_option($ctx, 'ssl', 'local_cert','Satori_Dev_Final.pem');
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