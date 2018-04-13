<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class PasswordReset Extends CI_Controller{

	function __construct()
	{
		parent::__construct();	
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if($method == "OPTIONS") {
             die();
        }

		$this->load->model('admin_api/passwordReset_model');
        //date_default_timezone_set("Asia/Kolkata");
        //date_default_timezone_get();	
        $this->load->helper('security');	
	}

	public function index($token=NULL){
		$token =  $_GET['token'];
		$data['message'] = '';

		if(!isset($_GET['message'])){
			if (!empty($token)) {
				$token= $token;
			}else{
				$token= "";
				$data['message'] = 'User not valid!';
			}
		}else{
			$data['message'] = $_GET['message'];
		}	

		
		$data['title'] = 'Password reset';		
		$data['token'] = $token;
		
	    $this->load->view('admin/passwordReset_view',$data);
	}

	/* update the new password */
	public function updatePassword($token=NULL,$password=NULL,$cpassword=NULL){
		$forgotToken =  $_POST['token'];
		$password =  $_POST['password'];
		$cpassword =  $_POST['cpassword'];	
		$this->load->library('form_validation');
		$this->form_validation->set_rules('token','User token','required');
		$this->form_validation->set_rules('password','New password','required|min_length[6]');
		$this->form_validation->set_rules('cpassword','Confirm password','required|min_length[6]|matches[password]');

		if ($this->form_validation->run() == FALSE)
		{	
			//$token = $this->input->post('token');	
			$forgotToken = $_POST['token'];
			$data['forgotToken'] = $forgotToken;	
			$data['message'] = "";	
			//$this->load->view('admin/passwordReset_view',$data);		
		}else{				
			$query=$this->passwordReset_model->update_password();
			 if($query){
			 	$response = array(
                            'ResponseCode' => 1,
                            'ResponseMessage' => 'SUCCESS', 
                            'Comments' => "Password updated successfully",
                            'Code' => 1,
                            'Result' =>true,
                            'Status' =>200
                         );
			 	echo json_encode($response); exit();
				//redirect('admin/PasswordReset?user_id=&message=Password updated successfully!');
			}else{
				$response = array(
                            'ResponseCode' => 0,
                            'ResponseMessage' => 'FAILURE', 
                            'Comments' => 'Password can not be updated',
                            'Code' => 0,
                            'Result' =>false,
                            'Status' =>400
                         );
				//redirect('admin/PasswordReset?user_id=&message=Password can not be updated');
				echo json_encode($response); exit();
			}
		}
	}


	/* Check the token expiry is valid */
    public function check_user_token($token=NULL){
        $token =  $_POST['token'];
        if(!$token){
        	$response = array(
                            'ResponseCode' => 0,
                            'ResponseMessage' => 'FAILURE', 
                            'Comments' => 'This link is invalid.',
                            'Code' => 0,
                            'Result' =>false,
                            'Status' =>400
                         );
				echo json_encode($response);  exit();
        }
        //echo json_encode($token);exit();
        $forgotTokenExpiry = $this->forgotTokenExpiry($token);
        if ($forgotTokenExpiry){
            $status = $this->is_token_active($forgotTokenExpiry);            
            if($status){  
                $response = array(
                            'ResponseCode' => 0,
                            'ResponseMessage' => 'FAILURE', 
                            'Comments' => 'This link has been expired.',
                            'Code' => 0,
                            'Result' =>false,
                            'Status' =>400
                         );
				echo json_encode($response); exit(); 
            } else{
            	$response = array(
                            'ResponseCode' => 1,
                            'ResponseMessage' => 'SUCCESS', 
                            'Comments' => 'This link is valid.',
                            'Code' => 1,
                            'Result' =>true,
                            'Status' =>200
                         );
				echo json_encode($response);  exit();
            }           
        }else{
           // $this->response(array('ResponseCode' => 0, 'ResponseMessage' => 'FAILURE', 'Comments' => 'Token Mismatch','Result'=>''), 401);
             $response = array(
                            'ResponseCode' => 0,
                            'ResponseMessage' => 'FAILURE', 
                            'Comments' => 'This link is invalid.',
                            'Code' => 2,
                            'Result' =>false,
                            'Status' =>400
                         );
				echo json_encode($response); exit();
        }

    }

    public function forgotTokenExpiry($token)
    {
        $sql = "SELECT forgotTokenExpiry FROM users WHERE forgotToken='".$token."'";
        $record = $this->db->query($sql);
        if ($record->num_rows()>0) {
            return $record->row('forgotTokenExpiry');
        }
    }


    function is_token_active($ts)
    {
        if ($ts <= time()) {
            return true;
        } else {
            return false;
        }
    }

	
}

?>