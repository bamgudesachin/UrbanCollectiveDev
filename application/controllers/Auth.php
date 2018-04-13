<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once('./application/libraries/REST_Controller.php');


class Auth extends REST_Controller {

	public function __construct()
	{	parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		$method = $_SERVER['REQUEST_METHOD'];
		if($method == "OPTIONS") {
			die();
		}
		
		include APPPATH . 'libraries/classes/class.phpmailer.php';
		$this->config->load('my_constants');
		$this->load->model('mobile_api/Common_model');
		$this->load->model('mobile_api/Auth_model');
		$this->load->helper('security');
		include APPPATH .'libraries/ApnsPHP/ApnsPHP/Autoload.php';
		$this->load->library('MailChimp');
	}

	public function test_mailchimp_post(){
		
		$list_id = 'cb33c194ca' ; //your mailchimp list id   
		$emailAddress = 'shoukat@rapidera.com';
		$merge_vars = array('FNAME'=>'shoukat', 'LNAME'=>'rapidera');
		//$interest = '734695d398';
		//$memberHash = md5($emailAddress);
		$result = $this->mailchimp->post("lists/$list_id/members", 
			[ 'email_address' => $emailAddress, 
			'merge_fields' => $merge_vars, 
			'status' => 'subscribed', 
			'interests' => array(
            	"28de0608d8" => false, //VL Campaign
    			"699273ca50" => false, //User
    			"734695d398" => true //Android
    			)
			]);
		

		// $result = $this->mailchimp->get("lists/$list_id/interest-categories/0bf665e16d");

		//$memberHash

		if ($result) {
			print_r($result);exit();
			echo "success";
			//print_r($result);	
		} else {
			echo "error";
		}

	}

	/*Register the User */
	public function register_post($fb_data = NULL)
	{   		
		$last_login = date('Y-m-d H:i:s');
		if (!$fb_data) {
			$post_data = $this->post();
		}else{
			$post_data = $fb_data;
		}

		$firstName = array_key_exists("firstName",$post_data);
		if($firstName){           
			$firstName = $post_data['firstName'];  
		}

		$firstname = array_key_exists("firstname",$post_data);
		if($firstname){           
			$firstName = $post_data['firstname'];  
		}
		
        /*$lastName = array_key_exists("lastName",$post_data);
        if($lastName){           
           $lastName = $post_data['lastName'];  
       }*/

       $lastname = array_key_exists("lastname",$post_data);
       if($lastname){           
       	$lastName = $post_data['lastname'];  
       }else{
       	$lastName = "";
       }
       

       $email = array_key_exists("email",$post_data);
       if($email){           
       	$email = strtolower($post_data['email']); 
       }else{
       	$email = "";
       } 

       
       $profile_picture = array_key_exists("profile_picture",$post_data);
       if($profile_picture){           
       	$profile_picture = $post_data['profile_picture'];  
       } 
		//$facebookId = $post_data['facebookId'];
       $lastLogin = $last_login;

       $facebookId = array_key_exists("facebookId",$post_data);
       if($facebookId){           
       	$facebookId = $post_data['facebookId'];   
       } 

       $this->form_validation->set_data($post_data);
       $this->form_validation->set_error_delimiters('', '');
       
       if (!$fb_data) {
       	$this->form_validation->set_rules('email','Email','required|valid_email|callback_email_check');
       	$this->form_validation->set_rules('password','Password','required');
       }
       
       if(!empty($profile_picture)){		
       	$this->form_validation->set_rules('profile_picture', 'Profile image', 'required|callback_handle_profile_image_upload');		
       }else{
       	unset($post_data['profile_picture']);
       }

       if ($this->form_validation->run() === false) {
       	$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>false), 400);
       }else{	
       	
       	$response = $this->Auth_model->register($post_data);
       	if ($response) {
       		if (!$fb_data) {
       			$data = $this->Auth_model->login($post_data);
       		}else{
       			$facebookId = $post_data['facebookId'];
       			$data = $this->Auth_model->get_facebook_user($facebookId);
       		}
       		
       		if ($data) {
       			$email = strtolower($data[0]['email']);
       			$post_data['token'] = $this->generate_token();
       			$post_data['tokenExpiry'] = $this->generate_token_expiry();
       			$post_data['refreshToken'] = $this->generate_token();
       			$post_data['refreshTokenExpiry'] = $this->generate_refresh_token_expiry();
       			
       			$token_data = $this->Auth_model->save_token_with_expiry($post_data,$email);
       			if ($token_data) {
       				$data[0]['token'] = $post_data['token'];
       				$data[0]['tokenExpiry'] = $post_data['tokenExpiry'];
       				$data[0]['refreshToken'] = $post_data['refreshToken'];
       				$data[0]['refreshTokenExpiry'] = $post_data['refreshTokenExpiry'];		

       				ob_start();
       				$response = array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => $_POST['success'],'Result'=>$data[0],'Status' =>200);
       				echo json_encode($response); 
						// Get the size of the output.

       				$size = ob_get_length();
       				header("Content-Encoding: none");
       				header("Content-Length: {$size}");
       				header("Connection: close");
       				ob_end_flush();
       				ob_flush();
       				flush();

       				/* Add member in the Mailchimp list */
						$list_id = 'cb33c194ca' ; //your mailchimp list id   
						$emailAddress = $email;
						$merge_vars = array('FNAME'=>$firstName, 'LNAME'=>$lastName);
						
						$result = $this->mailchimp->post("lists/$list_id/members", [ 'email_address' => $emailAddress, 'merge_fields' => $merge_vars, 
							'status' => 'subscribed',
							'interests' => array(
					            	"28de0608d8" => false, //VL Campaign
					    			"699273ca50" => true, //User
					    			"734695d398" => false //Android
					    			) 
							]);
						
						/*integrate HubSpot*/
						//$hubspot = $this->hubspot_create_contact($firstName,$lastName,$email);

						/* $this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => $_POST['success'],'Result'=>$data[0]), 200);
						*/
					}else{
						$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Server error','Result'=>''), 400);	
					}				
				}
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Unable to register user','Result'=>''), 400);
			}				
		}	
	}

	/* Mailchimp integrate for android */
	public function android_mailchimp_post(){		
		/* Add member in the Mailchimp list */
		$post_data = $this->post();		
		$firstName = $post_data['name'];
		$lastName = "";
		$emailAddress = $post_data['email'];
		$list_id = 'cb33c194ca' ; //your mailchimp list id   		
		$merge_vars = array('FNAME'=>$firstName, 'LNAME'=>$lastName);

		$result = $this->mailchimp->post("lists/$list_id/members", [ 'email_address' => $emailAddress, 'merge_fields' => $merge_vars, 
			'status' => 'subscribed',
			'interests' => array(
	            	"28de0608d8" => false, //VL Campaign
	    			"699273ca50" => false, //User
	    			"734695d398" => true //Android
	    			) 
			]);

		if($result){
			$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'User register in MailChimp successfully','Result'=>true), 200);
		}else{
			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Fail to register user','Result'=>false), 400);
		}

	}
	
	/* Signup and login with facebook */
	public function signup_with_facebook_post()
	{
		$this->load->helper('security');
		$post_data = $this->post();
		
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('facebookId','Facebook id','required');
		if ($this->form_validation->run() === false) {
			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>false), 400);
		}else{
			$facebookId = $post_data['facebookId'];
			$data = $this->Auth_model->get_facebook_user($facebookId);
			if ($data) {
				$dummyId = array_key_exists("dummyId",$post_data);
				if($dummyId){
					$data[0]['dummyId'] = $post_data['dummyId'];  
				}

				$device_token = array_key_exists("deviceToken",$post_data);
				if($device_token){
					$data[0]['deviceToken'] = $post_data['deviceToken'];
				}

				$device_type = array_key_exists("deviceType",$post_data);
				if($device_type){
					$data[0]['deviceType'] = $post_data['deviceType'];
				}
				
				$this->login_post($data);						
			}else{
				$this->register_post($post_data);
			}				
		}
	}


	/*Function for upload profile picture */
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
		    

		    $config['upload_path'] = './uploads/user';	    
		    $config['allowed_types'] = 'gif|jpg|jpeg|png';
		    $config['max_size'] = '2048';
		    $config['remove_spaces'] = TRUE;
		    $config['encrypt_name'] = TRUE;


		    $this->load->library('upload', $config);
		    $this->upload->initialize($config);
		    if($this->upload->do_upload_rest('userfile', true)) {
		    	$arr_image_info = $this->upload->data();
		        //$_POST['profile_picture1'] = '/uploads/user/'.$arr_image_info['file_name'];	
		    	$_POST['profile_picture1'] = $arr_image_info['file_name'];		    
		    	return true;
		    }else{
		    	$error = $this->upload->display_errors('', '');
		    	$this->form_validation->set_message('handle_profile_image_upload', $error);
		    	return false;
		    }
		}

		/* To check the email is exist */
		public function email_check($email)
		{	
			$status = $this->Auth_model->email_check($email);    	
			if ($status){
				$this->form_validation->set_message('email_check', 'An account already exists with this email ID');
				return false;
			}else{
				return true;
			}    	      
		}

		/*Check the user present or not*/
		public function user_check($userId)
		{	
			$status = $this->Auth_model->user_check($userId);	
			if ($status){
				return true;            
			}else{
				$this->form_validation->set_message('user_check', '{field} is do not exists');
				return false;
			}
		}

    /* login the user
     parameters email,password
     */
     public function login_post($fb_data = NULL)
     {
     	$this->load->helper('security');
     	if (!$fb_data) {
     		$post_data = $this->post();
     		$this->form_validation->set_data($post_data);
     		$this->form_validation->set_error_delimiters('', '');
     		$this->form_validation->set_rules('email','Email','required');
     		$this->form_validation->set_rules('password','Password','required');
     		if ($this->form_validation->run() === false) {
     			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>false), 400);
     		}else{
     			$data = $this->Auth_model->login($post_data);
     		}
     	}

     	if($fb_data){
     		$data = $this->Auth_model->fb_login_dummy_user($fb_data);
    		//print_r($data);exit();
     	}

     	if ($data) {
     		$email = strtolower($data[0]['email']);
     		$post_data['token'] = $this->generate_token();
     		$post_data['tokenExpiry'] = $this->generate_token_expiry();
     		$post_data['refreshToken'] = $this->generate_token();
     		$post_data['refreshTokenExpiry'] = $this->generate_refresh_token_expiry();
     		
     		$token_data = $this->Auth_model->save_token_with_expiry($post_data,$email);
     		if ($token_data) {
     			$data[0]['token'] = $post_data['token'];
     			$data[0]['tokenExpiry'] = $post_data['tokenExpiry'];
     			$data[0]['refreshToken'] = $post_data['refreshToken'];
     			$data[0]['refreshTokenExpiry'] = $post_data['refreshTokenExpiry'];						
     			$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Logged in successfully','Result'=>$data[0]), 200);
     		}else{
     			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Server error','Result'=>''), 400);	
     		}			
     	}else{
     		$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => $_POST['login_error'],'Result'=>''), 400);
     	}				
     }
     
     public function logout_post()
     {
     	$post_data = $this->post();
     	$this->form_validation->set_data($post_data);
     	$this->form_validation->set_error_delimiters('', '');
     	$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');
     	if ($this->form_validation->run() === false) {
     		$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>false), 400);    		
     	}else{
     		$data = $this->Auth_model->logout($post_data);			
     		if ($data) {			
     			$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Logout successfully','Result'=>$data), 200);
     		}else{
     			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Fail to logout','Result'=>$data), 400);	
     		}	
     	}
     }
     

     /* get New access token using refresh token */
     public function access_token_post()
     {
     	$post_data = $this->post();
     	$this->load->helper('security');
     	$this->form_validation->set_data($post_data);
     	$this->form_validation->set_error_delimiters('', '');
		//$this->form_validation->set_rules('user_id','User Id','required|numeric');
     	$this->form_validation->set_rules('refreshToken','Refresh token','required|callback_refresh_token_check');
		//$this->form_validation->set_rules('device_type','Device type','required');
		//$this->form_validation->set_rules('device_token','Device token','required');
     	if ($this->form_validation->run() === false) {
     		$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>false), 400);
     	}else{
     		$post_data['token'] = $this->generate_token();
     		$post_data['tokenExpiry'] = $this->generate_token_expiry();			
     		$access_token = $this->Auth_model->access_token($post_data);
     		if ($access_token) {
     			$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Refresh token verified successfully','Result'=> $post_data), 200);
     		}else{
     			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Refresh token is invalid','Result'=>''), 400);
     		}
     	}
     }

     public function refresh_token_check($refresh_token)
     {
     	$refresh_token_expiry = $this->Auth_model->refresh_token_check($refresh_token);
     	if ($refresh_token_expiry){
     		$status = $this->is_token_active($refresh_token_expiry);        	
     		if($status){        		
     			$this->form_validation->set_message('refresh_token_check', 'Refresh token expired.');
     			return false;	
     		}else{
     			return true;
     		}
     		
     	}else{
     		$this->form_validation->set_message('refresh_token_check', 'Refresh token mismatch.');
     		return false;
     	}
     }

    function clean($string)
    {
	    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

	    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}

	function generate_token()
	{
		return $this->clean(do_hash(rand() . time() . rand()));
	}

	function generate_token_expiry()
	{
		return strtotime('+1 day', time());
	    //return strtotime('+1 minutes', time());
	}

	function generate_refresh_token_expiry()
	{
		return strtotime('+30 day', time());
		//return strtotime('+2 minutes', time());
	}

	function is_token_active($ts)
	{
		if ($ts <= time()) {
			return true;
		} else {
			return false;
		}
	}

	function generate_forgot_password_token_expiry()
	{
	    //return strtotime('+1 day', time());
		return strtotime('+30 minutes', time());

	}
	
	
	/*******************************************************************************/	/**
     * URL - /Auth/fogotPassword
     * TYPE - POST
     * PARAMETERS - email
     * @return mixed
     */
	public function fogotPassword_post()
	{
		$post_data = $this->post();	
		$email = strtolower($post_data['email']);	    
		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('email','Email','required|valid_email|callback_email_exists');
		if ($this->form_validation->run() === false) {
			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>false), 400);    		
		}else
		{
			$post_data['forgotToken'] = $this->generate_token();
			$post_data['forgotTokenExpiry'] = $this->generate_forgot_password_token_expiry();				
			
			$forgotToken_data = $this->Auth_model->save_forgot_token_with_expiry($post_data,$email);
			if($forgotToken_data){
				$data = $this->Auth_model->fogotPassword($post_data);
				if ($data){
						//print_r($data);exit();	
					$userId = $data[0]['userId'];
					$firstname = $data[0]['firstName'];	
					$forgotToken = $data[0]['forgotToken'];				
					$link = "http://theurbancollective.io/passwordReset.php?token=".$forgotToken."&environment=".ENVIRONMENT;				
					
					$send_email = $this->sendMail($email,$link,$firstname);						
					
				}else{
					$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'User does not exist','Result' => false), 400);
				}	
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'We are experiencing some technical difficulties, please try again later.', 'Result' => false), 400);
			}

			
		}
	}
	

	/* function for sending email for forgot password */
	function sendMail($email,$link,$firstname,$dummyPassword = NULL)
	{	
		if(!$dummyPassword){
			$forgotPassword = '<p>If you did not request a password change, please disregard this message.<br><br></p>';
		}else{
			$forgotPassword = '';
		}
		$msg= '<html>
		<head></head>
		<body style="color: black;">
			<p>Dear User, <br>
				Isn’t it exciting searching for a new home? Well, reset password is a small part of that fun!  Please click on the link below to change your password to the UrbanCo app: The link is valid for 30 minutes only.
				<br><br></p>
				<strong><a href="'.$link.'" style="text-decoration: none !important;">Change Password</a><br><br></strong>'.$forgotPassword.'
				<p>Cheers,<br>UrbanCo Tech Team<br></p>
				<body>
					</html>	';		
					
					$msg = mb_convert_encoding($msg,"HTML-ENTITIES","UTF-8"); 	
		  //echo $msg;exit();
		  //include APPPATH . 'libraries/classes/class.phpmailer.php'; // include the class name				
					$mail = new PHPMailer(true); // create a new object
					$mail->CharSet = "UTF-8";
					$mail->IsSMTP(); 
					try{
						$mail->IsHTML(true);	    
						$mail->SMTPDebug = 1;                                                        
							$mail->Host = "smtp.googlemail.com"; //Hostname of the mail server  ssl://smtp.googlemail.com//smtpout.secureserver.net
							$mail->Port = "587"; //Port of the SMTP like to be 25, 80, 465 or 587  ////465
							$mail->SMTPAuth = true; //Whether to use SMTP authentication
							$mail->Username = "urbancotech@gmail.com"; //urbancotech@gmail.com Username for SMTP authentication any valid email created in your domain  bamgude.sachin@gmail.com
							$mail->Password = "TheUrbanCollective"; //97b-Th9-E7S-63c Password for SMTP authentication 
							$mail->SMTPSecure  = 'tls'; 
							$mail->SetFrom("urbancotech@gmail.com",'TheUrbanCollective');
							if($dummyPassword){
								$mail->Subject = "Set your UrbanCo password";
							}else{
								$mail->Subject = "Change your UrbanCo password";
							}
							
							$mail->Body = $msg;
							$mail->AddAddress($email);//whom to send mail
						   // $mail->AddCC("");
							$send = $mail->Send(); //Send the mails
							//echo $mail->ErrorInfo;exit();
							if($send){
								$this->response(array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'MAIL SENT SUCCESFULLY','Result'=>$send), 200);
							}else{
								$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'MAIL SENDING FAIL','Result'=>$send), 400);
							}
						} catch (phpmailerException $e) {
						  //echo $e->errorMessage();exit(); //Pretty error messages from PHPMailer
							$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'MAIL SENDING FAIL','Result'=>$send), 400);
							
						} catch (Exception $e) {
						  //echo $e->getMessage();exit(); //Boring error messages from anything else!
							$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'MAIL SENDING FAIL','Result'=>$send), 400);
						}
					}
					

					/* function for check the email id exists or not */
					public function email_exists($email)
					{	
						$status = $this->Auth_model->email_exists($email);	
						if (!$status){
							$this->form_validation->set_message('email_exists', 'This email is not registered with us');
							return false;
						}else{
							return true;
						}
					}
					
					/*---------------------------------------30Nov-------------------------- 08 Feb2018*/
					public function connect_to_my_sherpa_post() {
						$post_data = $this->post();                
						$loginUserId = $post_data['loginUserId'];
						$user_name = $post_data['firstName'];
						$email = $post_data['email'];
						$contactNumber = $post_data['contactNumber'];
						$deviceType = array_key_exists("deviceType",$post_data);
						if($deviceType){ 
							$deviceType = ucfirst($post_data['deviceType']);
						}else{
							$deviceType = 'iOS';
						}
						
						/*Check the email is exist or not, if yes then store the data against dummy id  and email to admin and provided email() */
						$loginUserId = $post_data['loginUserId'];
						$idealMovingDate = date('d M Y',strtotime($post_data['idealMovingDate']));
						$budget = $post_data['minPrice']."-".$post_data['maxPrice'];
						$noOfBedroom = $post_data['minBedroom']."-".$post_data['maxBedroom'];
						$post_data['forgotToken'] = $this->generate_token();
						$post_data['forgotTokenExpiry'] = $this->generate_forgot_password_token_expiry();
						$forgotToken = $post_data['forgotToken'];
						$link = "http://theurbancollective.io/passwordReset.php?token=".$forgotToken."&environment=".ENVIRONMENT;	

		//print_r($post_data);exit();
						/* If search is already available then update details otherwise create new search */
						$searchId = array_key_exists("searchId",$post_data);
						if($searchId){ 
							$searchId = $post_data['searchId'];
							$updated = $this->Auth_model->update_search_criteria($post_data);
						}else{
							$saveSearch = $this->Auth_model->insert_search_criteria($post_data);
        	$searchId = $saveSearch; // it return search id
        }
        
        if($searchId){
        	$connectSherpa = $this->Auth_model->connect_to_my_sherpa($post_data);
        	if($connectSherpa){			
        		ob_start();
        		$response = array(
        			'ResponseCode' => 1,
        			'ResponseMessage' => 'SUCCESS', 
        			'Comments' => 'Connect to sherpa successfully',
        			'Result' => $connectSherpa,
        			'searchId' => $searchId,
        			'isExist' => $_POST['isExist'],
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
        		$date = date("Y-m-d H:i:s");
        		$query_submmited = date('d M Y',strtotime($date));
        		/* Send email to admin person */
        		$send_email = $this->sherpaConnectMail($user_name,$email,$contactNumber,$idealMovingDate,$noOfBedroom,$budget,$query_submmited,$deviceType);
        		/*Send set password email to dummy user*/	               
        		if($_POST['isExist'] == 'no'){
        			$dummyPassword = true;
        			$send_dummiuseremail =  $this->sendMail($email,$link,$user_name,$dummyPassword);
        		}
        		
        		/*integrate HubSpot*/
				$monthly_rental_budget = $post_data['maxPrice'];//$maxPrice;
				$target_date_of_moving = $this->dateConversion($idealMovingDate);

				$hubspot = $this->hubspot_create_contact($user_name,$email,$contactNumber,$monthly_rental_budget,$target_date_of_moving);

			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Fail to connect sherpa','Result'=>'Fail to connect shepa'), 400);
			}
		}else{
			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Fail to connect sherpa','Result'=>'Fail to connect shepa'), 400);
		}
	}
	
	
	function sherpaConnectMail($user_name,$email,$contactNumber,$idealMovingDate,$noOfBedroom,$budget,$query_submmited,$deviceType)
	{      
		$msg= '<html>
		<head></head>
		<body style="color: black;">
			<p>The following app user has requested Sherpa consultation:<br></p>
			<p> Name: '.$user_name.'</p>
			<p> Email address: '.$email.'</p>
			<p> Contact Number: '.$contactNumber.'</p>
			<p> Moving Date: '.$idealMovingDate.'</p>
			<p> Number of Bedrooms: '.$noOfBedroom.'</p> 
			<p> Budget: '.$budget.'</p>
			<p> Query submitted on: '.$query_submmited.'</p>
			<p> Platform: '.$deviceType.'</p><br><br>
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
	          $mail->Username = "urbancotech@gmail.com"; //Username for SMTP authentication any valid email created in your domain  bamgude.sachin@gmail.com
	          $mail->Password = "TheUrbanCollective"; //Password for SMTP authentication 
	          $mail->SMTPSecure  = 'tls'; 
	          $mail->SetFrom("urbancotech@gmail.com",'TheUrbanCollective');
	            //    $mail->SetFrom("bamgude.sachin@gmail.com");
	          $mail->Subject = "Sherpa request via mobile app";

	          $mail->Body = $msg;
	          //$mail->AddAddress('mayank.mathur@theurbancollective.io');//To
	          //$mail->AddAddress('thakurbs0101@gmail.com');//To
			  $mail->AddBCC('mayank.mathur@theurbancollective.io');//mayank.mathur@theurbancollective.io whom to send mail	
			  
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


	  /* HUB SPOT API integration*/
	  function hubspot_create_contact($user_name,$email,$contactNumber,$monthly_rental_budget,$target_date_of_moving){
	  	$arr = array(
	  		'properties' => array(
	  			array(
	  				'property' => 'email',
	  				'value' => $email
	  				),
	  			array(
	  				'property' => 'firstname',
	  				'value' => $user_name
	  				),
	  			array(
	  				'property' => 'lastname',
	  				'value' => ''
	  				),
	  			array(
	  				'property' => 'mobilephone',
	  				'value' => $contactNumber
	  				),
	  			array(
	  				'property' => 'monthly_rental_budget_',
	  				'value' => $monthly_rental_budget
	  				),
	  			array(
	  				'property' => 'target_date_of_moving',
	  				'value' => $target_date_of_moving
	  				),
	  			array(
	  				'property' => 'app_referral',
	  				'value' => 'Yes'
	  				)
	  			)
	  		);

	  	$post_json = json_encode($arr);
	  	$hapikey = '9ee51b31-350e-4d1b-9233-32a7fae1318d';
	  	$endpoint = 'https://api.hubapi.com/contacts/v1/contact?hapikey=' . $hapikey;
	  	$data = $this->hubspot_api_call($endpoint,$post_json);
	  	if($data){
	  		$result =json_decode($data);
	  		$vid = $result->vid;        	
	  		$result = $this->hubspot_add_user_in_list($vid,$email);
	  		return $result;
	  	}
	  }

	  function hubspot_add_user_in_list($vid,$email){
	  	$vids = array($vid);
	  	$emails = array($email);

	  	$arr = array(
	  		'vids' => $vids,
	  		'emails' => $emails            
	  		);

	  	$post_json = json_encode($arr);
	  	$hapikey = '9ee51b31-350e-4d1b-9233-32a7fae1318d';
	  	/*ListId = 81 of App User list*/        
	  	$endpoint = "https://api.hubapi.com/contacts/v1/lists/81/add?hapikey=".$hapikey;

	  	$result = $this->hubspot_api_call($endpoint,$post_json);
	  	return $result;
	  }

	  function hubspot_api_call($endpoint,$post_json){
	  	$ch = @curl_init();
   		//$cert = site_url()."cacert.pem";   		
		//curl_setopt($ch, CURLOPT_CAINFO, $cert);

	  	@curl_setopt($ch, CURLOPT_POST, true);
	  	@curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
	  	@curl_setopt($ch, CURLOPT_URL, $endpoint);
	  	@curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	  	@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	  	@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  	$response = @curl_exec($ch);
	  	$status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
	  	$curl_errors = curl_error($ch);
	  	@curl_close($ch);
       // echo "curl Errors: " . $curl_errors;
        //echo "\nStatus code: " . $status_code;
       // echo "\nResponse: " . $response;
	  	return $response;
        //print_r($response);exit();
	  }

	  /*Convert date to UNIX timestamp in ms for HUB SPOT*/
	  function dateConversion($idealMovingDate){
		$stamp = strtotime($idealMovingDate); // get unix timestamp
		$time_in_ms = $stamp*1000;
		return $time_in_ms;
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

	/* Function for save the property and increament the view count*/ 
    public function view_property_post(){
    	$post_data = $this->post();
    	$userId = $post_data['userId'];
    	$searchId = $post_data['searchId'];
    	$propertyId = $post_data['propertyId'];

		$this->form_validation->set_data($post_data);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('userId','User Id','required|numeric|callback_user_check');
		$this->form_validation->set_rules('searchId','Search criteria id','required|numeric|callback_search_criteria_check');
		$this->form_validation->set_rules('propertyId','Property id','required');		

		
		if ($this->form_validation->run() === false) {
    			$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => validation_errors(),'Result'=>''), 400);    		
		}else{
			$viewed_property = $this->Auth_model->view_property($post_data);
			if($viewed_property){							
				
				ob_start();			
				$response = array('ResponseCode' => 1, 'ResponseMessage' => 'SUCCESS', 'Comments' => 'Property viewed successfully','Result'=>$viewed_property,'Status' =>200);
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
						
			}else{
				$this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Fail to viewed property','Result'=>false), 400);
			}
		}
    }
	
}?>