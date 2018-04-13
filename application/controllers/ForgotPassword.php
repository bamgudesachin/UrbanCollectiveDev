<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class ForgotPassword Extends CI_Controller{

	public function __construct() {
		parent::__construct();
		$this->load->model('admin_api/ForgotPassword_model');
		$this->config->load('my_constants');
	}

	

	public function email_check($email)
	{
		if ($this->register_model->email_check($email))
		{
			$this->form_validation->set_message('email_check', 'The %s field is already used.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function forgot_password()
	{
		$logged_in=$this->session->userdata('logged_in');
		if (!empty($logged_in)) {
		     redirect('admin/Dashboard');
	 	}else{
			$data['title'] = 'Forgot Password';
			$this->load->view('admin/Forgot_password_view',$data);
		}	
	}

	

	public function create()
	{
		$email = $_POST["email"];
		$user_info = $this->ForgotPassword_model->email_check($email);
		if ($user_info) {
			$token = $this->generate_token();
			$token_expiry = $this->generate_token_expiry();
			$update_token = $this->ForgotPassword_model->update_token($email,$token,$token_expiry);
			if ($update_token) {	
				
				$link = site_url()."admin/PasswordReset?user_id=".$student_id;	
				//echo $link;exit();

				$message = "<a href='".site_url()."register/reset_password/".$token."/".$token_expiry."'>reset</a>";

				$send_email = $this->sendMail($email,$link,$firstname);
				if($send){
						$this->session->set_flashdata('success_message', 'Password reset successfully.Check your registered email for new password');
						redirect('login/index','refresh');
				}else{
						$this->session->set_flashdata('failure_message', 'You are fail to reset password.');
						redirect('register/forgot_password','refresh');
				}
			}else{

			}
		}else{
			$this->session->set_flashdata('login_failure','Email is incorrect');
			redirect('ForgotPassword/forgot_password','refresh');
		}
		
	}

	function clean($string)
	{
	    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

	    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}

	function generate_token()
	{
		$this->load->helper('security');
	    return $this->clean(do_hash(rand() . time() . rand()));
	}

	function generate_token_expiry()
	{
	    return strtotime('+1 day', time());
	}

	function reset_password($token=NULL,$token_expiry=NULL,$email=NULL){
		if(empty($email)){
			$token = $this->uri->segment(3);
			$token_expiry = $this->uri->segment(4);
			$status = $this->is_token_active($token_expiry);            
	        if($status){   
				$email = $this->register_model->link_valid($token,$token_expiry);
				if ($email) {
					$data['email'] = $email;
					$this->load->view('reset_password',$data);
				}else{

				}
			}else{

			}
		}else{
			//echo $email;exit;
			$data['email'] = $email;
			$this->load->view('reset_password',$data);
		}		
	}

	function is_token_active($ts)
    {
        if ($ts >= time()) {
            return true;
        } else {
            return false;
        }
    }

    public function new_password()
    {
    	$this->load->library('form_validation');
		$this->form_validation->set_rules('password','Password','required|min_length[6]|max_length[15]');
		$this->form_validation->set_rules('cpassword','Confirm Password','required|min_length[6]|max_length[15]|matches[password]');
				
		if ($this->form_validation->run() == FALSE)
		{
			$email = $this->input->post('email');
			$this->reset_password($token=NULL,$token_expiry=NULL,$email);		
		}else{
			$query = $this->register_model->new_password();
			if($query){
				$this->session->set_flashdata('success_message', 'You registered successfully.');
				redirect('login/index','refresh');
			}else{
				$this->session->set_flashdata('failure_message', 'You are fail to register.');
				redirect('login/index','refresh');
			}
		}
    }

    /* function for sending email for setting password of studio */
	function sendMail($email,$name,$link)
		{
		  //echo $message;exit();
		  $message = '<html>
		              <head></head>
		                <body>
		                  <table cellpadding="5" cellspacing="5" width="95%"  bgcolor="#dfeff7">
		                    <tr bgcolor="#dfeff7">
		                      <td  style="padding:3px;"></td>
		                    </tr>
		                    <tr>
		                      <td bgcolor="#FFFFFF" style="font-family:Arial, Helvetica, sans-serif; color:#333333; font-size:12px;"><p><br />	                      	
		                        <strong> Set your password </strong> <br /><br />
		                          <table cellpadding="0" cellspacing="0" width="100%" style="border:1px solid #d8d8d8;">
		                            <tr>
		                              <td style="padding:5px;" valign="top">
		                                <table width="100%" height="100%" cellpadding="0" cellspacing="0">
		                                  <tr>
		                                    <td colspan="8" height="5px"><center><strong>Welcome in TheUrbanCollective </strong></center><br></td>
		                                  </tr>
		                                  <tr>
		                                    <td colspan="8" height="5px"><strong>Dear '.$name.' </strong><br></td>

		                                  </tr>
		                                  <tr>                     
		                                    <td colspan="8" height="5px"></td>
		                                  </tr>
		                                  <tr>
		                                    <td colspan="8" height="5px"><strong>Please click below to set your password.</strong><br></td>
		                                  </tr>
		                                  <tr>                     
		                                    <td colspan="8" height="5px"></td>
		                                  </tr>
		                                  <tr>
		                                    <td colspan="8" height="5px"><strong><a href="'.$link.'">Set Password</a> </strong><br></td>
		                                  </tr>
		                                  <tr>                     
		                                    <td colspan="8" height="5px"></td>
		                                  </tr>                           
		                                  
		                                  <tr>                     
		                                    <td colspan="8" height="5px"></td>
		                                  </tr>
		                                                             
		                                  <td colspan="8" height="5px"></td>

		                                  </tr>
		                            <tr>
		                            </center>
		                            <td colspan="8" height="5px">Sincerely,<br>
                                	TheUrbanCollective customer service</td>
                                	</tr>   
                                	<tr><td colspan="8" height="5px"></td>
                                	<td height="5px"></td></tr>                        
		                          </table>
		                        </td>
		                    </tr>
		                 </table>
				            </td>
				          </tr>
				            </table>
				      </body>
				    </html>';
		 // echo $message;exit();
		 
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
	                $mail->SMTPSecure  = 'ssl'; 
				    $mail->SetFrom("urbancotech@gmail.com",'TheUrbanCollective');
	          //    $mail->SetFrom("bamgude.sachin@gmail.com");
					$mail->Subject = "Set your Urban Collective password";
					$mail->Body = $message;
					$mail->AddAddress($email);//whom to send mail
	               // $mail->AddCC("");
					$send = $mail->Send(); //Send the mails
					//echo $mail->ErrorInfo;exit();
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