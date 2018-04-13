<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class ForgotPassword_model extends CI_Model {
	function __construct()
	{
		parent::__construct();

	}
	public function save_user()
	{
		if (isset($_POST['facebook_id'])) {
			$facebook_id = $_POST['facebook_id'];
		}else{
			$facebook_id = '';
		}		
		$_POST['dob'] = date("Y-m-d", strtotime($_POST['dob']));
		$_POST['role'] = 3;
		unset($_POST['cpassword']);
		$user_data = $_POST;
		return $this->db->insert("user",$user_data);
	}

	public function email_check($email)
	{
        $sql1 = "SELECT admin_id FROM admin WHERE email='".$email."'";
        $record1 = $this->db->query($sql1);        
        if ($record1->num_rows()>0) {
            return true;
        }
	}

	public function email_check_update($str)
	{
		$user_id = $this->session->userdata('objectId');
		$query = ParseUser::query();
	    $query->equalto('username',$str);
	    $query->notEqualto('objectId',trim($user_id));
	    $results = $query->find();
	    if(count($results)>0){
			return true;
        }
	}

	public function update_token($email,$token,$token_expiry)
	{
		$data = array('token' => $token, 'token_expiry' =>$token_expiry);
		//return $this->db->where('email',$email)->update('admin', $data);
		$this->db->where('email', $email);
        if($this->db->update('admin',$data))
        {
        	$sql = "SELECT admin_id,name FROM admin WHERE token='".$token."' AND token_expiry='".$token_expiry."'";
	        $record = $this->db->query($sql);
	        if ($record->num_rows()>0) {
	                //return $record->row('student_id');
	                return $record->result_array()[0];
	        }else{
	                return false;
	            }
        }

	}

	public function link_valid($token,$token_expiry)
	{
		$sql1 = "SELECT email FROM user WHERE token='".$token."' AND token_expiry='".$token_expiry."'";
        $q = $this->db->query($sql1);        
        if ($q->num_rows()>0) {
            return $q->row('email');
        }
	}

	public function new_password()
	{
		$password = $this->input->post('password');
		$data = array('password' => $password);
		$email = $this->input->post('email');
		return $this->db->where('email',$email)->update('user', $data);
	}
}?>