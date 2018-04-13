<?php defined('BASEPATH') OR exit('No direct script access allowed');

//require_once('./application/libraries/REST_Controller.php');
//require_once('./application/libraries/new_stripe/vendor/autoload.php');
//require_once APPPATH.'';
//require_once('./application/libraries/new_stripe/vendor/autoload.php');  


class Common extends MY_Controller1 {

	public function __construct()
	{
	    parent::__construct();
        $this->config->load('my_constants');
		$this->load->model('mobile_api/Common_model');
		$this->load->model('mobile_api/Auth_model');
		$this->load->helper('security');	
		include APPPATH . 'libraries/classes/class.phpmailer.php';
		
	}

	
	/*Check the user present or not*/
	public function user_check($userId)
    {	
    	$status = $this->Common_model->user_check($userId);	
    	if ($status){
        	return true;            
        }else{
            $this->form_validation->set_message('user_check', '{field} is do not exists');
            return false;
        }
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

    /*Check the user present or not*/
	public function search_criteria_check($searchId)
    {	
    	$status = $this->Common_model->search_criteria_check($searchId);	
    	if ($status){
        	return true;            
        }else{
            $this->form_validation->set_message('search_criteria_check', '{field} is do not exists');
            return false;
        }
    }

    /*Function for upload profile pic */
    public function handle_profile_image_upload() {
		
		$image = $this->post('profile_picture');
		
	    $temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage'); // might not work on some systems, specify your temp path if system temp dir is not writeable
		file_put_contents($temp_file_path, base64_decode($image));
		
		$image_info = getimagesize($temp_file_path); 
		$_FILES['userfile'] = array(
		     'name' => uniqid().'.'.preg_replace('!\w+/!', '', $image_info['mime']),
		     'tmp_name' => $temp_file_path,
		     'size'  => filesize($temp_file_path),
		     'error' => UPLOAD_ERR_OK,
		     'type'  => $image_info['mime'],
		);
		
		$config['upload_path'] = './uploads/user/student';	    
	    $config['allowed_types'] = 'gif|jpg|jpeg|png';
	    $config['max_size'] = '2048';
	    $config['remove_spaces'] = TRUE;
	    $config['encrypt_name'] = TRUE;


	    $this->load->library('upload', $config);
	    $this->upload->initialize($config);
	    if($this->upload->do_upload_rest('userfile', true)) {
	        $arr_image_info = $this->upload->data();
	        $_POST['profile_picture1'] = '/uploads/user/student/'.$arr_image_info['file_name'];
		    
	        return true;
	    }else{
	       	$error = $this->upload->display_errors('', '');
	        $this->form_validation->set_message('handle_profile_image_upload', $error);
			return false;
	    }
	}

	/* Student Profile details */
	public function profileDetails_post()
	{
		$post_data = $this->post();
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{

			$profile_detail = $this->Common_model->profile_detail($post_data);
			if ($profile_detail) {
				$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Profile detail fetched successfully','Result'=>$profile_detail), 200);
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No Data Found','Result'=>'No Data Found'), 400);
			}	
		}
	}

	/*Function for update user profile*/
    public function updateProfile_post()
    {
    	$post_data = $this->post();
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');
		
		$email = array_key_exists("email",$post_data);
        if($email){ 
           $this->form_validation->set_rules('email','Email','required|valid_email|callback_update_email_check');

        }
		// $this->form_validation->set_rules('password','Password','required');
		// $this->form_validation->set_rules('newPassword','New password','required');
		// if(!empty($profile_picture)){			
		// 	$this->form_validation->set_rules('profile_picture', 'User profile image', 'required|callback_handle_profile_image_upload');		
		// }else{
		// 	unset($post_data['profile_picture']);
		// }

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{

			$data = $this->Common_model->updateProfile($post_data);
			//print_r($data);exit();
			if ($data) {
				$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Profile updated successfully.','Result'=>$data), 200);
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => $_POST['message'],'Result'=>false), 400);
			}		
		}
    }
	
	/* To check the email is exist */
	public function update_email_check($email)
    {	
    	$post_data = $this->post();
    	$userId = $post_data['userId'];
    	$status = $this->Common_model->update_email_check($email,$userId);    	
		if ($status){
            $this->form_validation->set_message('update_email_check', 'An account already exists with this email ID');
            return false;
        }else{
            return true;
        }    	      
    }

    /***************************************************************************************/
    /***************************************************************************************/
    /***************************************************************************************/


    /* Function for add and update Basic serarch criteria */
    public function save_search_criteria_post(){
    	$post_data = $this->post();
    	$userId = $post_data['userId'];
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('userId','User Id','required');		

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$save_search_criteria = $this->Common_model->save_search_criteria($post_data);
			if($save_search_criteria){
				/* Comment for fast response on 7/9/2017 */
				//$data = $this->search_criteria_detail_post($userId,$save_search_criteria);
				
            		$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => $_POST['message'], 'Result' =>array('searchId'=> $save_search_criteria)), 200);	            
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'We are experiencing some technical difficulties, please try again.', 'Result' => $save_search_criteria), 400);
			}

		}
    }

    /* Function for Delete Search criteria */
    public function delete_search_criteria_post(){
    	$post_data = $this->post();
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');			
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');	
		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$delete_search_criteria = $this->Common_model->delete_search_criteria($post_data);
			if($delete_search_criteria){				
            		$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Search criteria deleted successfully', 'Result' =>true), 200);	           
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'We are experiencing some technical difficulties, please try again.', 'Result' => $delete_search_criteria), 400);
			}

		}
    }

    /* Function for get list of Basic serarch criteria and my tribe search */
    public function search_criteria_list_post(){
    	$post_data = $this->post();
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('userId','User Id','required');		

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			//$data['count'] = $this->Common_model->count_search_criteria_list($post_data);    
			//print_r($data['count'])	;exit();
	    	//if($data['count']){
				$data['list'] = $this->Common_model->search_criteria_list($post_data);
				if ($data) {
					$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Search criteria fetched successfully','Result'=>$data), 200);
				}else{
					$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found','Result'=>'No Data Found'), 400);
				}
			// }else{
			// 	$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found','Result'=>'No Data Found'), 400);
			// }	
		}
    }
	
	
    /* Search criteria detail for sending back the details when create basic search criteria */
    public function search_criteria_detail_post($userId,$searchId){  	
		$data['pref'] = $this->Common_model->get_preference_detail($userId,$searchId);
		//print_r($data['pref']);exit();
			if($data['pref']){
				$data['userId'] = 	$data['pref']['userId'];
				$data['searchId'] = 	$data['pref']['searchId'];
				$data['searchName'] = 	$data['pref']['searchName'];
				$data['idealMovingDate'] = 	$data['pref']['idealMovingDate'];
				$data['houseType'] = 	$data['pref']['houseType'];
				$data['minPrice'] = 	$data['pref']['minPrice'];
				$data['maxPrice'] = 	$data['pref']['maxPrice'];
				$data['minBedroom'] = 	$data['pref']['minBedroom'];
				$data['maxBedroom'] = 	$data['pref']['maxBedroom'];

				if($data['pref']['keywords']){
					$data['keywords'] = 	explode(",",$data['pref']['keywords']);
				}else{
					$data['keywords'] =  [];
				}
				
				$data['travelApiData'] = 	$data['pref']['travelApiData'];
				
				/* preference object */
				// $proximity = $data['pref']['proximity'];
				// $data['preferences']['proximity'] = explode(",",$proximity);
				// $avoid_proximity = $data['pref']['avoid_proximity'];
				// $data['preferences']['avoid_proximity'] = explode(",",$avoid_proximity);
				
				/* advanceSearch object */
				//$data['advanceSearch']['postcode'] = $data['pref']['area'];
				if($data['pref']['area']){
					$data['advanceSearch']['postcode'] = $data['pref']['area'];
				}else{
					$data['advanceSearch']['postcode'] = '';
				}
				
				$proximity = $data['pref']['proximity'];
				if($proximity){
					$data['advanceSearch']['location_preferences'] = explode(",",$proximity);
				}else{
					$data['advanceSearch']['location_preferences'] = [];
				}
				//$keywords = $data['pref']['keywords'];
				//$data['advanceSearch']['keywords'] = explode(",",$keywords);
				
				//$data['advanceSearch']['avoid_location'] = $data['pref']['avoid_area'];
				if($data['pref']['avoid_area']){
					$data['advanceSearch']['avoid_location'] = $data['pref']['avoid_area'];
				}else{
					$data['advanceSearch']['avoid_location'] = '';
				}
				$avoid_proximity = $data['pref']['avoid_proximity'];
				if($avoid_proximity){
					$data['advanceSearch']['avoid_location_preferences'] = explode(",",$avoid_proximity);
				}else{
					$data['advanceSearch']['avoid_location_preferences'] = [];
				}
				//$avoid_keywords = $data['pref']['avoid_keywords'];
				//$data['advanceSearch']['avoid_keywords'] = explode(",",$avoid_keywords);

				$data['topSearch']['criteria1'] = $data['pref']['criteria1'];
				$data['topSearch']['criteria2'] = $data['pref']['criteria2'];
				$data['topSearch']['criteria3'] = $data['pref']['criteria3'];
				
				unset($data['pref']);
			}

			$data['commute'] = $this->Common_model->get_commute_detail($userId,$searchId);
			
			//print_r($data);exit();
			if ($data) {
				$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => $_POST['message'],'Result'=>$data), 200);
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'We are experiencing some technical difficulties, please try again.','Result'=>false), 400);
			}
    }
	
	
     /* Add user in my tribe and send notifications to exists users and email to non-existing users */
    public function save_tribe_post(){
    	$post_data = $this->post();
    	$userId = $post_data['userId'];
    	$searchId = $post_data['searchId'];
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');	
		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{

			$save_tribe = $this->Common_model->save_tribe($post_data);	
			//print_r($save_tribe);exit();
			$send_email = false;	
			$tribeMemberCount = 0;
			$get_exists_users = $save_tribe[1];
			//print_r($get_exists_users);exit();
			$get_not_exist_emails = $save_tribe[2];
			if($save_tribe[0]){
				//echo "saVE";exit();
				// Buffer all upcoming output...
				ob_start();
				// Send your response.
				/* get the count of tribe members who accepted invitation */
				$tribeMemberCount = $this->Common_model->count_my_tribe_member($post_data);
				
				 $response = array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => $_POST['tribe'],'tribeMemberCount' => $tribeMemberCount,'Result'=>true,'Status' =>200);
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
				//sleep(60);				
				//$searchId = $save_tribe;
				/* Get the user information and search criteria info. */
				
				$user_info = $this->Common_model->get_user_info($userId,$searchId);
				//print_r($user_info);exit;
				if($user_info){
					
					//print_r($user_info);exit();
					$user_name = $user_info[0]['firstName'];
					$searchName = $user_info[0]['searchName'];
					$deviceType = $user_info[0]['deviceType'];
					/* get the users who exists in database and send push notification */					
					if(sizeof($get_exists_users)>0)	{

						//print_r($get_exists_users);exit();
						//send notification
						$deviceToken = ARRAY();
						$users_from_tribe = ARRAY();
						$android_users_from_tribe = ARRAY();
						$deleted_users_emails = ARRAY();
						$deleted_users_device_tokens = ARRAY();
						$android_deleted_users_device_tokens = ARRAY();
						//print_r($get_exists_users);exit();
						foreach ($get_exists_users as $row) {
							//print_r($row);exit();
							$deviceToken[] = $row["deviceToken"];
							if($row["deleteFlag"] == 0){
								if($row["deviceToken"]){
									if($row["deviceType"] == "android"){
										$android_users_from_tribe[] = $row["deviceToken"];
									}else{
										$users_from_tribe[] = $row["deviceToken"];
									}	
								}															
							}

							if($row["deleteFlag"] == 1){
								$deleted_users_emails[] = $row["email"];
								if($row["deviceToken"]){
									if($row["deviceType"] == "android"){
										$android_deleted_users_device_tokens[] = $row["deviceToken"];
									}else{
										$deleted_users_device_tokens[] = $row["deviceToken"];
									}
								}								
							}
						}
						
						//print_r($deleted_users_device_tokens);exit();

						/* send notification to existing IOS user */
						if(count($users_from_tribe)>0){
							$message = $this->config->item('notification_add_user_in_tribe');
							$message_to_push = str_replace("firstName", $user_name, $message[0]);						
							$message_to_push_event = str_replace("searchName", $searchName, $message_to_push);
							$message_to_push_notification = $message_to_push_event;

							/*Send push to ios app users*/							
							$payload = json_encode([
								'aps' => [
									'alert' => $message_to_push_notification,
									'sound' => 'cat.caf',
									'badge' => 1,
									'content-available' => 1,
									'category' => 'CATEGORY_INVITATION'
								],
								"push_type"=> $message[1],
								"userId"=> $userId,
								"searchId"=> $searchId
							]);	
							//print_r($users_from_tribe);exit();
							$this->send_multiple_user_notification_ios($users_from_tribe, $payload);
						}

						/* send notification to existing android user */
						if(count($android_users_from_tribe)>0){
							$message = $this->config->item('notification_add_user_in_tribe');
							$message_to_push = str_replace("firstName", $user_name, $message[0]);						
							$message_to_push_event = str_replace("searchName", $searchName, $message_to_push);
							$message_to_push_notification = $message_to_push_event;

							/*Send push to android app users*/
						    $payload = array("message" => $message_to_push_notification,
						           "push_type"=> $message[1],
						           "userId"=> $userId,
								   "searchId"=> $searchId);
						      //print_r($payload);exit;
						     $this->send_notification_android($android_users_from_tribe, $payload);	
						}
						

						/* Send push notification and email to deleted users */
						if(count($deleted_users_emails)>0){						
							/* send notification to ios existing user for deleted from tribe */
							if(count($deleted_users_device_tokens)>0){							
								$message = $this->config->item('notification_delete_user_from_tribe');
								$message_to_push = str_replace("firstName", $user_name, $message[0]);						
								$message_to_push_event = str_replace("searchName", $searchName, $message_to_push);
								$message_to_push_notification = $message_to_push_event;

								/*Send push to ios app users*/
								$payload = json_encode([
									'aps' => [
										'alert' => $message_to_push_notification,
										'sound' => 'cat.caf',
										'badge' => 1,
										'content-available' => 1,
										'category' => 'CATEGORY_INVITATION'
									],
									"push_type"=> $message[1],
									"userId"=> $userId,
									"searchId"=> $searchId
								]);	
								//print_r($payload);exit();
								/*$file = fopen("test-push-tokens.txt","w");
								$tokens = json_encode($deleted_users_device_tokens);
								fwrite($file,$tokens);
								fclose($file);*/
								$this->send_multiple_user_notification_ios($deleted_users_device_tokens, $payload);
							}

							/* send notification to android existing user for deleted from tribe */
							if(count($android_deleted_users_device_tokens)>0){							
								$message = $this->config->item('notification_delete_user_from_tribe');
								$message_to_push = str_replace("firstName", $user_name, $message[0]);						
								$message_to_push_event = str_replace("searchName", $searchName, $message_to_push);
								$message_to_push_notification = $message_to_push_event;

								/*Send push to android app users*/	
							    $payload = array("message" => $message_to_push_notification,
							           "push_type"=> $message[1],
							           "userId"=> $userId,
									   "searchId"=> $searchId);
							      //print_r($payload);exit;
							    $this->send_notification_android($android_deleted_users_device_tokens, $payload);
							}
							
							$link = "";	
							$status = "deleted";
							$send_email = $this->sendMail($user_name,$searchName,$deleted_users_emails,$link,$status);
						}
					}

					/* get the users who dont exists in database and send email to them for registration */

					//$get_not_exist_emails = $this->Common_model->get_not_exist_emails($post_data);
					//print_r($get_not_exist_emails);exit();
					if(sizeof($get_not_exist_emails)>0)	{
						$users_emails = $get_not_exist_emails;
						/* send email to non existing users */
						$link = "https://itunes.apple.com/us/app/urbanco/id1250754314?ls=1&mt=8";//App link to download app
						$status = "added";
						$send_email = $this->sendMail($user_name,$searchName,$users_emails,$link,$status);
					}
					
					/*
					if($send_email){
						$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Invitation for tribe send successfully.','tribeMemberCount' => $tribeMemberCount,'Result'=>$send_email), 200);
					}else{
						$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => $_POST['tribe'],'tribeMemberCount' => $tribeMemberCount,'Result'=>true), 200);
					}*/
					

				} else {
					$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'We are experiencing some technical difficulties, please try again.','Result'=>false), 400);	
				}

			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Fail to make invitation for tribe','Result'=>false), 400);
			}
		}

    }
	
	/* Get My Tribe members list that user already added in tribe */
    public function get_my_tribe_member_list_post(){
    	$post_data = $this->post();
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');					
		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');		
		$this->form_validation->set_rules('loginUserId','User Id','required|numeric|callback_user_check');

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$data['count'] = $this->Common_model->count_get_my_tribe_member_list($post_data);
			if($data['count']){
				$data['list'] = $this->Common_model->get_my_tribe_member_list($post_data);
				if($data['list']){
					$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'My tribe members fetched successfully','Result'=>$data), 200);
				}else{
					$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found','Result'=>'No Data Found'), 400);
				}
			}else{
					$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found','Result'=>'No Data Found'), 400);
				}
		}
    }
   
    /* function for sending email while cancle the class by admin */
  	function sendMail($user_name,$searchName,$users_emails,$link,$status)
    {
      //echo $message;exit();
    	if($status == 'added'){
    		$msg= '<html>
                  <head></head>
                    <body style="color: black;">
                      <p>Dear User <br><br></p>
                      <p>'.$user_name.' has added you to '.$searchName.' <br><br>
                      </p>
                      <p>Please click below to download app.<br><br></p>
		                	<strong><a href="'.$link.'" style="text-decoration: none !important;">Download</a><br><br></strong>
                      <p>Sincerely,<br>Urban Collective customer service<br>
                      </p>         
                    <body>
            </html> '; 
    	}	

    	if($status == 'deleted'){
    		$msg= '<html>
                  <head></head>
                    <body style="color: black;">
                      <p>Dear User <br><br></p>
                      <p>'.$user_name.' has deleted you from '.$searchName.' <br><br>
                      </p>
                      <p>Sincerely,<br>Urban Collective customer service<br>
                      </p>         
                    <body>
            </html> '; 
    	}

       
      //echo $msg;exit();
      // include the class name        
          $mail = new PHPMailer(true); // create a new object
          $mail->IsSMTP(); 
        try{
	          $mail->IsHTML(true);      
	          $mail->SMTPDebug = 1;                                                        
	          $mail->Host = "smtp.googlemail.com"; //Hostname of the mail server  ssl://smtp.googlemail.com
	          $mail->Port = "587"; //Port of the SMTP like to be 25, 80, 465 or 587  ////465
	          $mail->SMTPAuth = true; //Whether to use SMTP authentication
	          $mail->Username = "urbancotech@gmail.com"; //Username for SMTP authentication any valid email created in your domain  bamgude.sachin@gmail.com
	          $mail->Password = "TheUrbanCollective"; //Password for SMTP authentication 
	          $mail->SMTPSecure  = 'tls'; 
	          $mail->SetFrom("urbancotech@gmail.com",'TheUrbanCollective');
	            //    $mail->SetFrom("bamgude.sachin@gmail.com");
	          if($status == "deleted"){
	          	$mail->Subject = "Deleted from tribe";
	          }
	          if($status == "added"){
	          	 $mail->Subject = "Invitation for tribe";
	          }
	         
	          $mail->Body = $msg;         
	          foreach($users_emails as $email1){
	          	$mail->AddAddress($email1);//To
	            //$mail->AddBCC($email1);//whom to send mail  
	          }
	          // $mail->AddCC("");
	          $send = $mail->Send(); //Send the mails
	         //echo $mail->ErrorInfo;exit();
	          //print_r($mail->ErrorInfo);exit();
	          if($send){
	              return true;
	          }else{
	              return false;
	            
	          }
          } catch (phpmailerException $e) {
						// $this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'MAIL SENDING FAIL','Result'=>$send), 400);
          			return false;
					
			} catch (Exception $e) {
			  	// $this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'MAIL SENDING FAIL','Result'=>$send), 400);
			  	return false;
			}
    }

	
    /* Function for accept or decline tribe invitation */
  /*  public function accept_decline_invitation_post(){
    	$post_data = $this->post();
    	$userId = $post_data['userId'];
    	$searchId = $post_data['searchId'];
    	$status = $post_data['status'];
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');	
		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');
		$this->form_validation->set_rules('status','Status','required');	

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$accept_decline_invitation = $this->Common_model->accept_decline_invitation($post_data);			
			if($accept_decline_invitation){
				
				ob_start();
                $response = array(
                    'ResponseCode' => 1,
                    'ResponseMessage' => 'SUCCESS', 
                    'Comments' => $_POST['message_invite'],
                    'Result' =>$accept_decline_invitation,
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


				// get the user info and search criteria information for send notification to search owner 
				$user_serach_info = $this->Common_model->get_user_serach_info($searchId);
				if($user_serach_info){
					//print_r($user_serach_info);exit();
					$login_user_info = $this->Common_model->login_user_info($userId);
					if($login_user_info){
						$user_name = trim($login_user_info[0]['firstName']);
					}else{
						$user_name = "";
					}

					$device_token = $user_serach_info[0]['deviceToken'];
					$searchName = trim($user_serach_info[0]['searchName']);

					//*Get the device tokens of tribe members for sending push 
					$tribe_users_device_tokens = $this->Common_model->tribe_users_device_tokens($post_data);
					if(sizeof($tribe_users_device_tokens)>0){
						//print_r($get_exists_users);exit();
						//send notification
						$tribeDeviceToken = ARRAY();	
						foreach ($tribe_users_device_tokens as $row) {
							if($row["deviceToken"]){
								$tribeDeviceToken[] = $row["deviceToken"];
							}							
						}

						if($status == 'accept')
						{
							$message = $this->config->item('notification_for_accept_invitation');
							$category = "CATEGORY_ACCEPT";

							$message_to_push = str_replace("name", $user_name, $message[0]);						
							$message_to_push_event = str_replace("searchName", $searchName, $message_to_push);
							$message_to_push_notification = $message_to_push_event;
							$payload = json_encode([
								'aps' => [
									'alert' => $message_to_push_notification,
									'sound' => 'cat.caf',
									'badge' => 1,
									'content-available' => 1,
									'category' => $category
								],
								"push_type"=> $message[1],
								"userId"=> $userId,
								"searchId"=> $searchId,
								"status"=> $status
							]);	
							//print_r($tribeDeviceToken);exit();
							$this->send_multiple_user_notification_ios($tribeDeviceToken, $payload);
						}						
					}


					if($status == 'decline')
					{
						$message = $this->config->item('notification_for_decline_invitation');
						$category = "CATEGORY_DECLINE";

						$message_to_push = str_replace("name", $user_name, $message[0]);						
						$message_to_push_event = str_replace("searchName", $searchName, $message_to_push);
						$message_to_push_notification = $message_to_push_event;
						$payload = json_encode([
							'aps' => [
								'alert' => $message_to_push_notification,
								'sound' => 'cat.caf',
								'badge' => 1,
								'content-available' => 1,
								'category' => $category
							],
							"push_type"=> $message[1],
							"userId"=> $userId,
							"searchId"=> $searchId,
							"status"=> $status
						]);	
						//print_r($payload);exit();
						$this->send_notification_ios($device_token, $payload);
					}
					
				}
				
				// $this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => $_POST['message_invite'],'Result'=>$accept_decline_invitation), 200); 
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'We are experiencing some technical difficulties, please try again.','Result'=>'We are experiencing some technical difficulties, please try again.'), 400);
			}
		}
    }
*/
	
	/* Function for accept or decline tribe invitation */
    public function accept_decline_invitation_post(){
    	$post_data = $this->post();
    	$userId = $post_data['userId'];
    	$searchId = $post_data['searchId'];
    	$status = $post_data['status'];
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');	
		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');
		$this->form_validation->set_rules('status','Status','required');	

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			//print_r($post_data);exit();

			$accept_decline_invitation = $this->Common_model->accept_decline_invitation($post_data);
			//print_r($accept_decline_invitation);exit();
			$tribe_users_device_tokens = $accept_decline_invitation[1];	

			if($accept_decline_invitation[0]){
				ob_start();
                $response = array(
                    'ResponseCode' => 1,
                    'ResponseMessage' => 'SUCCESS', 
                    'Comments' => $_POST['message_invite'],
                    'Result' =>$accept_decline_invitation[0],
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
			
				/* get the user info and search criteria information for send notification to search owner */
				$user_serach_info = $this->Common_model->get_user_serach_info($searchId);
				if($user_serach_info){
					//print_r($user_serach_info);exit();
					$login_user_info = $this->Common_model->login_user_info($userId);
					if($login_user_info){
						$user_name = $login_user_info[0]['firstName'];
					}else{
						$user_name = "";
					}

					$device_token = $user_serach_info[0]['deviceToken'];
					$search_user_device_type = $user_serach_info[0]['deviceType'];
					$searchName = $user_serach_info[0]['searchName'];

					/*Get the device tokens of tribe members for sending push */
					//$tribe_users_device_tokens = $this->Common_model->tribe_users_device_tokens($post_data);

					if(sizeof($tribe_users_device_tokens)>0){
						//print_r($tribe_users_device_tokens);exit();
						//send notification
						$tribeDeviceToken = ARRAY();
						$androidTribeDeviceToken = ARRAY();

						foreach ($tribe_users_device_tokens as $row) {
							if($row["deviceToken"]){
								if($row["deviceType"] == 'android'){
									$androidTribeDeviceToken[] = $row["deviceToken"];
								}else{
									$tribeDeviceToken[] = $row["deviceToken"];
								}	
							}												
						}

						//print_r($tribeDeviceToken);exit();

						if($status == 'accept')
						{
							$message = $this->config->item('notification_for_accept_invitation');
							$category = "CATEGORY_ACCEPT";

							$message_to_push = str_replace("name", $user_name, $message[0]);						
							$message_to_push_event = str_replace("searchName", $searchName, $message_to_push);
							$message_to_push_notification = $message_to_push_event;

							/*Send push to android app users*/
							if(sizeof($androidTribeDeviceToken)>0){
								//$device_token = array($device_token);							      
							    $payload = array("message" => $message_to_push_notification,
							           "push_type"=> $message[1],
							           "userId"=> $userId,
									   "searchId"=> $searchId,
									   "status"=> $status);
							      //print_r($payload);exit;
							    $this->send_notification_android($androidTribeDeviceToken, $payload);
							}

							/*Send push to IOS app users*/
							if(sizeof($tribeDeviceToken)>0){								
								$payload = json_encode([
									'aps' => [
										'alert' => $message_to_push_notification,
										'sound' => 'cat.caf',
										'badge' => 1,
										'content-available' => 1,
										'category' => $category
									],
									"push_type"=> $message[1],
									"userId"=> $userId,
									"searchId"=> $searchId,
									"status"=> $status
								]);	
								//print_r($payload);exit();
								$this->send_multiple_user_notification_ios($tribeDeviceToken, $payload);
								//$this->push_notification($tribeDeviceToken, $payload);
							}
						}						
					}


					if($status == 'decline')
					{
						$message = $this->config->item('notification_for_decline_invitation');
						$category = "CATEGORY_DECLINE";

						$message_to_push = str_replace("name", $user_name, $message[0]);						
						$message_to_push_event = str_replace("searchName", $searchName, $message_to_push);
						$message_to_push_notification = $message_to_push_event;

						/*Send push to android app users*/
						if($search_user_device_type == "android"){
							//$device_token = array($device_token);							      
						    $payload = array("message" => $message_to_push_notification,
						           "push_type"=> $message[1],
						           "userId"=> $userId,
								   "searchId"=> $searchId,
								   "status"=> $status);
						      //print_r($payload);exit;
						    $this->send_notification_android($device_token, $payload);
						}else{
							$payload = json_encode([
								'aps' => [
									'alert' => $message_to_push_notification,
									'sound' => 'cat.caf',
									'badge' => 1,
									'content-available' => 1,
									'category' => $category
								],
								"push_type"=> $message[1],
								"userId"=> $userId,
								"searchId"=> $searchId,
								"status"=> $status
							]);	
							//print_r($device_token);exit();
							$this->send_notification_ios($device_token, $payload);
						}
					}					
				}
				// $this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => $_POST['message_invite'],'Result'=>$accept_decline_invitation), 200);
				
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'We are experiencing some technical difficulties, please try again.','Result'=>'We are experiencing some technical difficulties, please try again.'), 400);
			}
		}
    }

	 /* Function for shortlist the property and rate */
    public function shortlist_property_post(){
    	$post_data = $this->post();
    	$userId = $post_data['userId'];
    	$loginUserId = $post_data['loginUserId'];
    	$searchId = $post_data['searchId'];
    	//$propertyUrl = $post_data['propertyUrl'];

		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');	
		if($userId != $loginUserId){
			$this->form_validation->set_rules('loginUserId','User Id','required|numeric|callback_user_in_tribe_check['.$post_data['searchId'].']');	
		}

		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');	

		/*if(!empty($propertyUrl)){			
			$this->form_validation->set_rules('propertyUrl', 'Property image', 'required|callback_handle_property_image_upload');		
		}else{
			unset($post_data['propertyUrl']);
		}
		*/

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$shortlist_property = $this->Common_model->shortlist_property($post_data);
			//print_r($shortlist_property);exit();
			if($shortlist_property){
				$shortlistedId = $shortlist_property['shortlistedId'];
				$shortlist_property['loginUserId'] = $loginUserId;				
				if ($shortlist_property) {
					ob_start();			
					$response = array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => $_POST['message'],'Result'=>$shortlist_property,'Status' =>200);
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
					//send push to tribe members when rated
					if($_POST['message'] == 'Property rated successfully' || $_POST['message'] == 'Property rate updated successfully' || $_POST['message'] == 'Property shortlisted and rated successfully' || $_POST['message'] == 'Property shortlisted successfully')
					{
						$shortlistedId = $shortlistedId;//shortlisted property id
						$get_user_serach_property_info = $this->Common_model->get_user_serach_property_info($searchId,$shortlistedId);
						if($get_user_serach_property_info){
							//print_r($user_serach_info);exit();
							$login_user_info = $this->Common_model->login_user_info($loginUserId);
							if($login_user_info){
								$user_name = $login_user_info[0]['firstName'];
							}else{
								$user_name = "";
							}

							//$device_token = $get_user_serach_property_info[0]['deviceToken'];
							$searchName = $get_user_serach_property_info[0]['searchName'];
							$propertyName = $get_user_serach_property_info[0]['propertyName'];
							/*Get the device tokens of tribe members for sending push */
							$rate_tribe_users_device_tokens = $this->Common_model->rate_tribe_users_device_tokens($post_data);
							if(sizeof($rate_tribe_users_device_tokens)>0){
								//print_r($get_exists_users);exit();
								//send notification
								$tribeDeviceToken = ARRAY();	
								$androidTribeDeviceTokens = ARRAY();
								foreach ($rate_tribe_users_device_tokens as $row) {
									if($row["deviceToken"]){
										if($row["deviceType"] == "android"){
											$androidTribeDeviceTokens[] = $row["deviceToken"];
										}else{
											$tribeDeviceToken[] = $row["deviceToken"];
										}
									}					
								}

								if($_POST['message'] == 'Property rated successfully' || $_POST['message'] == 'Property rate updated successfully')
								{
									$message = $this->config->item('notification_for_rate_on_property');
									$category = "CATEGORY_RATE";
								}

								if($_POST['message'] == 'Property shortlisted and rated successfully' || $_POST['message'] == 'Property shortlisted successfully')
								{
									$message = $this->config->item('notification_for_shortlist_property');
									$category = "CATEGORY_SHORTLIST_PROPERTY";
								}

									$message_to_push = str_replace("name", $user_name, $message[0]);						
									$message_to_push_event = str_replace("propertyName", $propertyName, $message_to_push);
									$message_to_push_search = str_replace("searchName", $searchName, $message_to_push_event);
									$message_to_push_notification = $message_to_push_search;

									/*send push to ios users*/
									if(sizeof($tribeDeviceToken)>0){
										$payload = json_encode([
											'aps' => [
												'alert' => $message_to_push_notification,
												'sound' => 'cat.caf',
												'badge' => 1,
												'content-available' => 1,
												'category' => $category
											],
											"push_type"=> $message[1],
											"loginUserId"=> $loginUserId,
											"userId"=> $userId,
											"searchId"=> $searchId,
											"shortlistedId"=>$shortlistedId
										]);	
										//print_r($payload);exit();
										$this->send_multiple_user_notification_ios($tribeDeviceToken, $payload);
									}

									/*send push to android users*/
									if(sizeof($androidTribeDeviceTokens)>0){
										$payload = array("message" => $message_to_push_notification,
										            "push_type"=> $message[1],
										            "loginUserId"=> $loginUserId,
													"userId"=> $userId,
													"searchId"=> $searchId,
													"shortlistedId"=>$shortlistedId);
										      //print_r($payload);exit;
										$this->send_notification_android($androidTribeDeviceTokens, $payload);
									}
							}

						}//get_user_serach_property_info
					}
				}
				/*$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => $_POST['message'],'Result'=>true), 200);
				*/
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Fail to shortlist property','Result'=>false), 400);
			}
		}
    }
	
	/*Function for upload shortlisted property image */
    public function handle_property_image_upload() {
		
		$image = $this->post('propertyUrl');
		
	    $temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage'); // might not work on some systems, specify your temp path if system temp dir is not writeable
		file_put_contents($temp_file_path, base64_decode($image));
		
		$image_info = getimagesize($temp_file_path); 
		$_FILES['userfile'] = array(
		     'name' => uniqid().'.'.preg_replace('!\w+/!', '', $image_info['mime']),
		     'tmp_name' => $temp_file_path,
		     'size'  => filesize($temp_file_path),
		     'error' => UPLOAD_ERR_OK,
		     'type'  => $image_info['mime'],
		);
		
		$config['upload_path'] = './uploads/property';	    
	    $config['allowed_types'] = 'gif|jpg|jpeg|png';
	    $config['max_size'] = '2048';
	    $config['remove_spaces'] = TRUE;
	    $config['encrypt_name'] = TRUE;


	    $this->load->library('upload', $config);
	    $this->upload->initialize($config);
	    if($this->upload->do_upload_rest('userfile', true)) {
	        $arr_image_info = $this->upload->data();
	        $_POST['propertyUrl1'] = '/uploads/property/'.$arr_image_info['file_name'];
		    
	        return true;
	    }else{
	       	$error = $this->upload->display_errors('', '');
	        $this->form_validation->set_message('handle_property_image_upload', $error);
			return false;
	    }
	}

	/* Function for get list of shortlisted property */
    public function shortlisted_property_list_post(){		
    	$post_data = $this->post();
    	$userId = $post_data['userId'];
    	$loginUserId = $post_data['loginUserId']; 
    	$searchId = $post_data['searchId'];
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');	

		if($userId != $loginUserId){
			$this->form_validation->set_rules('loginUserId','User Id','required|numeric|callback_user_in_tribe_check['.$post_data['searchId'].']');	
		}

		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');	

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			
			$data['count'] = $this->Common_model->count_shortlisted_property_list($post_data);    
			//print_r($data['count'])	;exit();
	    	if($data['count']){
				$data['list'] = $this->Common_model->shortlisted_property_list($post_data);
				if ($data) {
					$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Shortlisted properties fetched successfully','Result'=>$data), 200);
				}else{
					$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Refresh search results by clicking on “REFRESH” button','Result'=>'Refresh search results by clicking on “REFRESH” button'), 400);
				}
			}else{
				
				/*$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Refresh search results by clicking on "..." symbol and "Edit search criteria"','Result'=>'refresh search results by clicking on "..." symbol and "Edit search criteria"'), 400);*/
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Refresh search results by clicking on “REFRESH” button','Result'=>'Refresh search results by clicking on “REFRESH” button'), 400);
			}	
		}
    }
	
   /* Function for Delete Shortlisted property */
    public function delete_shortlisted_property_post(){
    	$post_data = $this->post();
    	$userId = $post_data['userId'];
    	$searchId = $post_data['searchId'];
    	$shortlistedId = $post_data['shortlistedId'];
		
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');			
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');	
		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');
		$this->form_validation->set_rules('shortlistedId','Shortlisted property id','required|numeric|callback_shortlisted_property_check');

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$delete_shortlisted_property = $this->Common_model->delete_shortlisted_property($post_data);
			if($delete_shortlisted_property){	
				$get_user_serach_property_info = $this->Common_model->get_user_serach_property_info($searchId,$shortlistedId);
					if($get_user_serach_property_info){
						$firstName = $get_user_serach_property_info[0]['firstName'];
						if($firstName){
							$firstName = $firstName;
						}else{
							$firstName = "";
						}
						$searchName = $get_user_serach_property_info[0]['searchName'];
						$propertyName = $get_user_serach_property_info[0]['propertyName'];
						/*Get the device tokens of tribe members for sending push */
						$tribe_users_device_tokens = $this->Common_model->tribe_users_device_tokens($post_data);
						if(sizeof($tribe_users_device_tokens)>0){
							//print_r($get_exists_users);exit();
							//send notification
							$tribeDeviceToken = ARRAY();
							$androidTribeDeviceToken = ARRAY();

							foreach ($tribe_users_device_tokens as $row) {
								if($row["deviceToken"]){
									if($row["deviceToken"] == "android"){
										$androidTribeDeviceToken[] = $row["deviceToken"];
									}else{
										$tribeDeviceToken[] = $row["deviceToken"];
									}									
								}							
							}

							$message = $this->config->item('notification_for_delete_property');
							$category = "CATEGORY_DELETE_PROPERTY";							

							$message_to_push = str_replace("name", $firstName, $message[0]);						
							$message_to_push_event = str_replace("propertyName", $propertyName, $message_to_push);
							$message_to_push_notification = $message_to_push_event;

							/*send push to ios users*/
							if(sizeof($tribeDeviceToken)>0){
								$payload = json_encode([
									'aps' => [
										'alert' => $message_to_push_notification,
										'sound' => 'cat.caf',
										'badge' => 1,
										'content-available' => 1,
										'category' => $category
									],
									"push_type"=> $message[1],
									"userId"=> $userId,
									"searchId"=> $searchId,
									"shortlistedId"=>$shortlistedId
								]);	
								//print_r($tribeDeviceToken);exit();
								$this->send_multiple_user_notification_ios($tribeDeviceToken, $payload);
							}

							/*send push to android users*/
							if(sizeof($androidTribeDeviceToken)>0){
								$payload = array("message" => $message_to_push_notification,
								            "push_type"=> $message[1],
								            "userId"=> $userId,
											"searchId"=> $searchId,
											"shortlistedId"=>$shortlistedId);
								      //print_r($payload);exit;
								$this->send_notification_android($androidTribeDeviceToken, $payload);
							}
						}

				}//get_user_serach_property_info
				
            	$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Shortlisted property deleted successfully', 'Result' =>$delete_shortlisted_property), 200);	           
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Fail to delete shortlisted property', 'Result' => $delete_shortlisted_property), 400);
			}

		}
    }
	
    /*Check the shortlisted property present or not*/
	public function shortlisted_property_check($shortlistedId)
    {	
    	$status = $this->Common_model->shortlisted_property_check($shortlistedId);	
    	if ($status){
        	return true;            
        }else{
            $this->form_validation->set_message('shortlisted_property_check', '{field} is do not exists');
            return false;
        }
    }

      /* Function for add comment in Shortlisted property */
    public function add_comments_post(){
    	$post_data = $this->post();
    	
    	//$fileType = $post_data['fileType'];
    	//$commentFile = $post_data['commentFile'];
    	$comment = $post_data['comment'];
    	//print_r($comment);exit();
    	$userId = $post_data['userId'];
    	$searchId = $post_data['searchId'];
    	$shortlistedId = $post_data['shortlistedId'];

		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');			
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');	
		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');
		$this->form_validation->set_rules('shortlistedId','Shortlisted property id','required|numeric|callback_shortlisted_property_check');		

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			if($comment){	
				$check_upload = $this->handle_commentFile_upload($comment);		
				if(!$check_upload){
					$error = $this->upload->display_errors('', '');
			       	$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => $error, 'Result' => false), 400);
				} 
			}else{
				unset($post_data['comment']);
			}
			
			$add_comments = $this->Common_model->add_comments($post_data);
			if($add_comments){
				/* get the login user info and shortlisted property info for push */
				$user_info = $this->Common_model->get_user_property_info($post_data);
				if($user_info){
					$user_name = trim($user_info[0]['firstName']);
					$propertyName = trim($user_info[0]['propertyName']);
					$propertyId = trim($user_info[0]['propertyId']);

					/* Send notification to my tribe members */
					$get_tribes_deviceToken = $this->Common_model->get_tribes_deviceToken($userId,$searchId);
					if($get_tribes_deviceToken){

						$deviceToken = ARRAY();
						foreach ($get_tribes_deviceToken as $row) {
							$deviceToken[] = $row["deviceToken"];							
						}

						$message = $this->config->item('notification_add_comment_on_shortlisted_property');
						$message_to_push = str_replace("firstName", $user_name, $message[0]);						
						$message_to_push_event = str_replace("propertyName", $propertyName, $message_to_push);
						$message_to_push_notification = $message_to_push_event;

						$payload = json_encode([
							'aps' => [
								'alert' => $message_to_push_notification,
								'sound' => 'cat.caf',
								'badge' => 1,
								'content-available' => 1,
								"category" => "CATEGORY_COMMENT"
							],
							"push_type"=> $message[1],
							"userId"=> $userId,
							"searchId"=> $searchId,
							"shortlistedId"=> $shortlistedId
						]);	
						//print_r($deviceToken);exit();
						$this->send_multiple_user_notification_ios($deviceToken, $payload);
					}
				}
					$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Comment added successfully', 'Result' =>$add_comments), 200);	           
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Fail to add comment', 'Result' => $add_comments), 400);
			}
		}
	}


	/*Function for upload shortlisted property image */
    public function handle_commentFile_upload($comment) {

		$image = array();
		$fileType = array();
		foreach ($comment as $key) {
			$image[] = $key['commentFile'];
			$fileType[] = $key['fileType'];
		}

		
		for($i=0;$i<sizeof($image);$i++)
		{
			if($image[$i])
			{
			
			    $temp_file_path = tempnam(sys_get_temp_dir(), 'androidtempimage'); // might not work on some systems, specify your temp path if system temp dir is not writeable
				file_put_contents($temp_file_path, base64_decode($image[$i]));

				if ($fileType[$i] == 'image') {			
					$image_info = getimagesize($temp_file_path);		
					$ext = preg_replace('!\w+/!', '', $image_info['mime']);//extension of file object
				}else{				
					$finfo = new finfo(FILEINFO_MIME);
					//print_r($finfo);exit;
					$ext = $finfo->buffer(base64_decode($image[$i])) . "\n";			
					$ext = preg_replace('!\w+/!', '', $ext);//extension of file object
					//echo $ext;exit;
					$ext = substr($ext, 0, strpos($ext, ";"));//extension of file object
				}

				$_FILES['userfile'] = array(
				     //'name' => preg_replace('!\w+/!', '', $image_info['mime']),
				     'name' =>  $this->clean(do_hash(rand() . time() . rand(). uniqid())).'.'.$ext,
				     'tmp_name' => $temp_file_path,
				     'size'  => filesize($temp_file_path),
				     'error' => UPLOAD_ERR_OK,
				     'type'  =>  $ext,
				);

				if($fileType[$i] == 'image'){
					$config['upload_path'] = './uploads/comment/image';	    
			    	$config['allowed_types'] = 'gif|jpg|jpeg|png';
			    	$folder_path = "image"; 
				}

				if($fileType[$i] == 'audio'){
					$config['upload_path'] = './uploads/comment/audio';	   
					$config['allowed_types'] = '*';
					//$config['allowed_types'] = 'mp3|audio/mpeg|audio/x-wav|audio/x-aiff|application/ogg|application/octet-stream'; 
			    	//$config['allowed_types'] = 'audio/mpeg|audio/x-wav|audio/x-aiff|audio/wav|audio/vnd.dlna.adts|application/ogg|application/octet-stream|application/force-download';
			    	$folder_path = "audio";
				}
				
			    $config['max_size'] = '1000000';
			    $config['remove_spaces'] = TRUE;
			    $config['encrypt_name'] = TRUE;

			    $this->load->library('upload', $config);
			    $this->upload->initialize($config);
			    if($this->upload->do_upload_rest('userfile', true)) {
			        $arr_image_info = $this->upload->data();

			        if (!isset($_POST['commentFile1'.$i])) {
			        	$_POST['commentFile1'.$i] = '/uploads/comment/'.$folder_path.'/'.$arr_image_info['file_name'];
			        }			    
			        //return true;
			    }else{
					return false;
			    }
		   }else{
		   		return true;
		   }
		}//for
		return true;
	}



	/*
		function to clean the token
	*/
	function clean($string)
	{
	    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

	    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}

	
	/* Get shortlisted property detail */
	public function shortlisted_property_detail_post(){
		$post_data = $this->post();
		$userId = $post_data['userId'];
        $searchId = $post_data['searchId'];

		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');			
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');

		$loginUserId = array_key_exists("loginUserId",$post_data);
        if($loginUserId){ 
            $loginUserId =  $post_data['loginUserId'];             
            if($userId != $loginUserId){
				$this->form_validation->set_rules('loginUserId','User Id','required|numeric|callback_user_in_tribe_check['.$post_data['searchId'].']');	
			}
        }
		

		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');
		$this->form_validation->set_rules('shortlistedId','Shortlisted property id','required|numeric|callback_shortlisted_property_check');

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			//print_r($post_data);exit();
			$data['shortlisted_property_detail'] = $this->Common_model->shortlisted_property_detail($post_data);
			//print_r($data['shortlisted_property_detail']);exit();
			if($data['shortlisted_property_detail']){
				$data['shortlistedId'] = $data['shortlisted_property_detail']['shortlistedId'];
				$data['searchId'] = $data['shortlisted_property_detail']['searchId'];
				$data['userId'] = $data['shortlisted_property_detail']['userId'];
				$data['propertyId'] = $data['shortlisted_property_detail']['propertyId'];
				$data['propertyName'] = $data['shortlisted_property_detail']['propertyName'];
				$data['propertyUrl'] = $data['shortlisted_property_detail']['propertyUrl'];
				$data['imageUrl'] = $data['shortlisted_property_detail']['imageUrl'];
				$data['commuteTime'] = $data['shortlisted_property_detail']['commuteTime'];
				$data['description'] = $data['shortlisted_property_detail']['description'];
				$data['price'] = $data['shortlisted_property_detail']['price'];
				$data['availableDate'] = $data['shortlisted_property_detail']['availableDate'];
				$data['criteria1'] = $data['shortlisted_property_detail']['criteria1'];
				$data['criteria2'] = $data['shortlisted_property_detail']['criteria2'];
				$data['criteria3'] = $data['shortlisted_property_detail']['criteria3'];
				$data['avgPropertyRating'] = $data['shortlisted_property_detail']['avgPropertyRating'];
				$data['avgRateCriteria1'] = $data['shortlisted_property_detail']['avgRateCriteria1'];
				$data['avgRateCriteria2'] = $data['shortlisted_property_detail']['avgRateCriteria2'];
				$data['avgRateCriteria3'] = $data['shortlisted_property_detail']['avgRateCriteria3'];
				$data['deleteFlag'] = $data['shortlisted_property_detail']['deleteFlag'];
				$data['createdAt'] = $data['shortlisted_property_detail']['createdAt'];				
				
				if($data['shortlisted_property_detail']['rateCriteria1']){
					$data['rateCriteria1'] = $data['shortlisted_property_detail']['rateCriteria1'];
				}else{
					$data['rateCriteria1'] = '';
				}
				
				if($data['shortlisted_property_detail']['rateCriteria2']){
					$data['rateCriteria2'] = $data['shortlisted_property_detail']['rateCriteria2'];
				}else{
					$data['rateCriteria2'] = '';
				}
				
				if($data['shortlisted_property_detail']['rateCriteria3']){
					$data['rateCriteria3'] = $data['shortlisted_property_detail']['rateCriteria3'];
				}else{
					$data['rateCriteria3'] = '';
				}				
				
				unset($data['shortlisted_property_detail']);
				//print_r($data);exit();				
			}
			
			/* Get the images,video,audio from comments of shortlisted property */
			$data['commentsUrl'] = $this->Common_model->shortlisted_property_comments_file($post_data);
			/* Get the total comments count of shortlisted property */
			$data['totalCommentCount'] = $this->Common_model->shortlisted_property_total_comment_count($post_data);
			if($data['totalCommentCount'] > 0){
				$data['totalCommentCount'] = "".$data['totalCommentCount']."";
			}else{
				$data['totalCommentCount'] = "0";
			}
			if ($data) {
				$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Property detail fetch successfully','Result'=>$data), 200);
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found.','Result'=>false), 400);
			}
		}
	}
	
	
	/* Fetch the comments with room type and maintain unread comments count */
	public function comments_roomType_list_post(){
		$post_data = $this->post();
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');			
		$this->form_validation->set_rules('loginUserId','User Id','required|numeric|callback_user_check');
            
		$this->form_validation->set_rules('loginUserId','User Id','required|numeric|callback_user_in_tribe_check['.$post_data['searchId'].']');	

		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');
		$this->form_validation->set_rules('shortlistedId','Shortlisted property id','required|numeric|callback_shortlisted_property_check');

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$result = array();
			$data = $this->Common_model->comments_roomType_list($post_data);
			if($data){
				/*Get the unread count and total count for roomType all */
				$coomentsRoomTypeAll = $this->Common_model->comments_roomType_all($post_data);
				$result = array_merge($data,$coomentsRoomTypeAll);
				$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'comments fetch successfully','Result'=>$result), 200);
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found.','Result'=>false), 400);
			}
		}
	}
	
	
	/* Get Shortlisted property comments */
	public function shortlisted_property_comments_post(){
		$post_data = $this->post();
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');					
		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');
		$loginUserId = array_key_exists("loginUserId",$post_data);
        if($loginUserId){ 
            $loginUserId =  $post_data['loginUserId'];             
			$this->form_validation->set_rules('loginUserId','User Id','required|numeric|callback_user_in_tribe_check['.$post_data['searchId'].']');				
        }

		$this->form_validation->set_rules('shortlistedId','Shortlisted property id','required|numeric|callback_shortlisted_property_check');
		$this->form_validation->set_rules('roomType','Room type is required','required');

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$unread_comment_count = $this->Common_model->get_unread_comment_count($post_data);
			$unread_comment_room_type_count = $this->Common_model->unread_comment_room_type_count($post_data);
			/* Get total comment count */
			$total_comment_count = $this->Common_model->total_comment_count($post_data);
			$data['count'] = $this->Common_model->count_shortlisted_property_comments($post_data);
			if($data['count']){
				$data['list'] = $this->Common_model->shortlisted_property_comments($post_data);
				if($data['list']){	
					$insert_readComment = $this->Common_model->shortlisted_property_comments_read($post_data);
					$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'comments fetch successfully','unreadCommentCount'=>$unread_comment_count,'unreadCommentRoomTypeCount'=>$unread_comment_room_type_count,
						'totalCommentsCount' => $total_comment_count,'Result'=>$data), 200);
				}else{
					$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found.','Result'=>false), 400);
				}
			}else{
					$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found.','Result'=>false), 400);
				}			
		}
	}
	
	
	
	
	/* Get search comments */
	public function add_chat_post(){
		$post_data = $this->post();
		$userId = $post_data['loginUserId'];
    	$searchId = $post_data['searchId'];
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');					
		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');
		$this->form_validation->set_rules('loginUserId','User Id','required|numeric|callback_user_check');	

		$loginUserId = array_key_exists("loginUserId",$post_data);
        if($loginUserId){ 
            $loginUserId =  $post_data['loginUserId'];             
			$this->form_validation->set_rules('loginUserId','User Id','required|numeric|callback_user_in_tribe_check['.$post_data['searchId'].']');				
        }	
		$this->form_validation->set_rules('chatText','Text','required');

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			
			$is_insert = $this->Common_model->add_chat($post_data);
			if($is_insert){	
				ob_start();
                $response = array(
                    'ResponseCode' => 1,
                    'ResponseMessage' => 'SUCCESS', 
                    'Comments' => 'Chat message successfully',
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
				/* Send notifications to tribe members */	
				/* get the login user info and shortlisted property info for push */
				$user_info = $this->Common_model->get_user_search_info($userId,$searchId);
				if($user_info){					
					$user_name = trim($user_info[0]['firstName']);
					$searchName = trim($user_info[0]['searchName']);

					/* Send notification to my tribe members */
					$get_tribes_deviceToken = $this->Common_model->get_tribes_deviceToken($userId,$searchId);
					if($get_tribes_deviceToken){

						$deviceToken = ARRAY();
						foreach ($get_tribes_deviceToken as $row) {
							$deviceToken[] = $row["deviceToken"];							
						}

						$message = $this->config->item('notification_add_chat_on_search');
						$message_to_push = str_replace("firstName", $user_name, $message[0]);						
						$message_to_push_event = str_replace("searchName", $searchName, $message_to_push);
						$message_to_push_notification = $message_to_push_event;

						$payload = json_encode([
							'aps' => [
								'alert' => $message_to_push_notification,
								'sound' => 'cat.caf',
								'badge' => 1,
								'content-available' => 1,
							    'category' => 'CATEGORY_CHAT'
							],
							"push_type"=> $message[1],
							"userId"=> $userId,
							"searchId"=> $searchId
						]);	
						//print_r($payload);exit();
						$this->send_multiple_user_notification_ios($deviceToken, $payload);
					}
				}

			  /*	$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Chat message successfully','Result'=>$is_insert), 200); */
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Unable to post message','Result'=>false), 400);
			}
		}
	}
	
	
	/* Fetch the details of perticular search's chat messages*/
	public function search_detail_chats_post(){
		$post_data = $this->post();
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');	
		$loginUserId = array_key_exists("loginUserId",$post_data);
        if($loginUserId){ 
            $loginUserId =  $post_data['loginUserId'];             
			$this->form_validation->set_rules('loginUserId','User Id','required|numeric|callback_user_in_tribe_check['.$post_data['searchId'].']');				
        }					
		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');		
		

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$data['count'] = $this->Common_model->count_search_detail_chats($post_data);
			if($data['count']){
				$data['list']= $this->Common_model->search_detail_chats($post_data);
				if($data['list']){				
					$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Chat detail fetch successfully','Result'=>$data), 200);
				}else{
					$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found','Result'=>false), 400);
				}
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found','Result'=>false), 400);
			}
		}
	}
	
	/* Get the list of my search and my tribe search with latest chat */
	public function search_list_latest_chats_post(){
		$post_data = $this->post();
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('loginUserId','User Id','required|numeric|callback_user_check');		

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$data['count'] = $this->Common_model->count_search_list_latest_chats($post_data);    
			//print_r($data['count'])	;exit();
	    	if($data['count']){
				$data['list'] = $this->Common_model->search_list_latest_chats($post_data);
				if ($data) {
					$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Search chats fetched successfully','Result'=>$data), 200);
				}else{
					$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found','Result'=>'No Data Found'), 400);
				}
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found','Result'=>'No Data Found'), 400);
			}	
		}
	}

	/* Connect to my Sherpha */
	public function connect_to_my_sherpa_post(){
		$post_data = $this->post();
		$loginUserId = $post_data['loginUserId'];
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('loginUserId','User Id','required|numeric|callback_user_check');		

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$data = $this->Common_model->connect_to_my_sherpa($post_data);
			if($data){				
				ob_start();
                $response = array(
                    'ResponseCode' => 1,
                    'ResponseMessage' => 'SUCCESS', 
                    'Comments' => 'Connect to sherpa successfully',
                    'Result' =>$data,
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
			
                /*get the user info and its latest search*/
                $user_data = $this->Common_model->get_user_latest_search_info($loginUserId);
                if($user_data){
                	//print_r($user_data);exit();
                	$user_name = $user_data[0]['firstName']." ".$user_data[0]['lastName'];
                	$email = $user_data[0]['email'];
                	if($user_data[0]['idealMovingDate']){
                		$idealMovingDate = date('d M Y',strtotime($user_data[0]['idealMovingDate']));
                	}else{
                		$idealMovingDate = "";
                	}
                	if((empty($user_data[0]['minPrice']) || $user_data[0]['minPrice']) && $user_data[0]['maxPrice']){						
                		$budget = $user_data[0]['minPrice']."-".$user_data[0]['maxPrice'];
                	}else{
                		$budget = "";
                	}
					if($user_data[0]['minBedroom'] && $user_data[0]['maxBedroom']){
                		$noOfBedroom = $user_data[0]['minBedroom']."-".$user_data[0]['maxBedroom'];
                	}else{
                		$noOfBedroom = "";
                	} 
                	
                	$query_submmited = date('d M Y',strtotime($user_data[0]['sherpaSignedup']));

                	$send_email = $this->sherpaConnectMail($user_name,$email,$idealMovingDate,$noOfBedroom,$budget,$query_submmited);

                }                

				//$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Connect to sherpa successfully','Result'=>$data), 200);
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Fail to connect sherpa','Result'=>'Fail to connect shepa'), 400);
			}
		}
	}
	

	/* Get my tribe invitation list */
	public function getMyInvitationList_post(){
		$post_data = $this->post();
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');		
		$this->form_validation->set_rules('limit','Limit','required');
		$this->form_validation->set_rules('offset','Offset','required');

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$data['count'] = $this->Common_model->count_MyInvitationList($post_data);    
			//print_r($data['count'])	;exit();
	    	if($data['count']){
				$data['list'] = $this->Common_model->MyInvitationList($post_data);
				if ($data) {
					$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Invitation list fetched successfully','Result'=>$data), 200);
				}else{
					$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found','Result'=>'No Data Found'), 400);
				}
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found','Result'=>'No Data Found'), 400);
			}	
		}
	}

	
	/* Get the search criteria details */
    public function get_search_criteria_post(){
        $post_data = $this->post();
    	$userId = $post_data['userId'];
    	$searchId = $post_data['searchId'];
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');	
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');
		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');

		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$data['pref'] = $this->Common_model->get_preference_detail($userId,$searchId);
			if($data['pref']){
				$data['userId'] = 	$data['pref']['userId'];
				$data['searchId'] = 	$data['pref']['searchId'];
				$data['searchName'] = 	$data['pref']['searchName'];
				$data['idealMovingDate'] = 	$data['pref']['idealMovingDate'];
				$data['houseType'] = 	$data['pref']['houseType'];
				$data['minPrice'] = 	$data['pref']['minPrice'];
				$data['maxPrice'] = 	$data['pref']['maxPrice'];
				$data['minBedroom'] = 	$data['pref']['minBedroom'];
				$data['maxBedroom'] = 	$data['pref']['maxBedroom'];

				//$data['keywords'] = 	explode(",",$data['pref']['keywords']);
				if($data['pref']['keywords']){
					$data['keywords'] = 	explode(",",$data['pref']['keywords']);
				}else{
					$data['keywords'] =  [];
				}
				$data['travelApiData'] = 	$data['pref']['travelApiData'];
				/* preference object */
				// $proximity = $data['pref']['proximity'];
				// $data['preferences']['proximity'] = explode(",",$proximity);
				// $avoid_proximity = $data['pref']['avoid_proximity'];
				// $data['preferences']['avoid_proximity'] = explode(",",$avoid_proximity);
				
				/* advanceSearch object */
				//$data['advanceSearch']['postcode'] = $data['pref']['area'];
				if($data['pref']['area']){
					$data['advanceSearch']['postcode'] = $data['pref']['area'];
				}else{
					$data['advanceSearch']['postcode'] = '';
				}
				$proximity = $data['pref']['proximity'];
				//$data['advanceSearch']['location_preferences'] = explode(",",$proximity);
				if($proximity){
					$data['advanceSearch']['location_preferences'] = explode(",",$proximity);
				}else{
					$data['advanceSearch']['location_preferences'] = [];
				}
				//$keywords = $data['pref']['keywords'];
				//$data['advanceSearch']['keywords'] = explode(",",$keywords);
				//$data['advanceSearch']['avoid_location'] = $data['pref']['avoid_area'];
				if($data['pref']['avoid_area']){
					$data['advanceSearch']['avoid_location'] = $data['pref']['avoid_area'];
				}else{
					$data['advanceSearch']['avoid_location'] = '';
				}
				
				$avoid_proximity = $data['pref']['avoid_proximity'];
				//$data['advanceSearch']['avoid_location_preferences'] = explode(",",$avoid_proximity);
				if($avoid_proximity){
					$data['advanceSearch']['avoid_location_preferences'] = explode(",",$avoid_proximity);
				}else{
					$data['advanceSearch']['avoid_location_preferences'] = [];
				}
				//$avoid_keywords = $data['pref']['avoid_keywords'];
				//$data['advanceSearch']['avoid_keywords'] = explode(",",$avoid_keywords);

				$data['topSearch']['criteria1'] = $data['pref']['criteria1'];
				$data['topSearch']['criteria2'] = $data['pref']['criteria2'];
				$data['topSearch']['criteria3'] = $data['pref']['criteria3'];
				
				unset($data['pref']);
			}

			$data['commute'] = $this->Common_model->get_commute_detail($userId,$searchId);
			
			//print_r($data);exit();
			if ($data) {
				$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => "Search criteria fetch successfully",'Result'=>$data), 200);
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'No data found','Result'=>false), 400);
			}
			
		}
    }
	
	/* function for sending email while user connect to sherpa */
  	function sherpaConnectMail($user_name,$email,$idealMovingDate,$noOfBedroom,$budget,$query_submmited)
    {      
		$msg= '<html>
              <head></head>
                <body style="color: black;">
                  <p>The following app user has requested Sherpa consultation:<br></p>
                  <p> Name: '.$user_name.'</p>
                  <p> Email address: '.$email.'</p>
                  <p> Moving Date: '.$idealMovingDate.'</p>
				  <p> Number of Bedrooms: '.$noOfBedroom.'</p> 
                  <p> Budget: '.$budget.'</p>
                  <p> Query submitted on: '.$query_submmited.'</p><br><br>
                  <p>Cheers,<br>UrbanCo Tech Team<br>
                  </p>         
                <body>
        </html> '; 
    	
       
      //echo $msg;exit();
      // include the class name        
          $mail = new PHPMailer(true); // create a new object
          $mail->IsSMTP(); 
        try{
	          $mail->IsHTML(true);      
	          $mail->SMTPDebug = 1;                                                        
	          $mail->Host = "smtp.googlemail.com"; //Hostname of the mail server  ssl://smtp.googlemail.com
	          $mail->Port = "587"; //Port of the SMTP like to be 25, 80, 465 or 587  ////465
	          $mail->SMTPAuth = true; //Whether to use SMTP authentication
	          $mail->Username = "urbancotech@gmail.com"; //urbancotech@gmail.com Username for SMTP authentication any valid email created in your domain  bamgude.sachin@gmail.com
	          $mail->Password = "TheUrbanCollective"; // TheUrbanCollective Password for SMTP authentication 
	          $mail->SMTPSecure  = 'tls'; 
	          $mail->SetFrom("urbancotech@gmail.com",'TheUrbanCollective'); //urbancotech@gmail.com
	            //    $mail->SetFrom("bamgude.sachin@gmail.com");
	          $mail->Subject = "Sherpa request via mobile app";
	         
	          $mail->Body = $msg;         
	          
	          $mail->AddAddress('mayank.mathur@theurbancollective.io');// To 			  
	          //$mail->AddBCC('mayank.mathur@theurbancollective.io');//whom to send mail  	         
	          // $mail->AddCC("");
	          $send = $mail->Send(); //Send the mails
	         //echo $mail->ErrorInfo;exit();
	          //print_r($mail->ErrorInfo);exit();
	          if($send){
	              return true;
	          }else{
	              return false;
	            
	          }
          } catch (phpmailerException $e) {
						// $this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'MAIL SENDING FAIL','Result'=>$send), 400);
          			return false;					
			} catch (Exception $e) {
			  	// $this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'MAIL SENDING FAIL','Result'=>$send), 400);
			  	return false;
			}
    }




}?>