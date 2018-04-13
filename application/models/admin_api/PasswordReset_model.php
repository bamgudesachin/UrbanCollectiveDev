<?php
class PasswordReset_model extends CI_Model {
	function __construct()
	{
		parent::__construct();

	}

	public function update_password()
	{
		$post_data = $this->input->post();
		$forgotToken = $post_data['token'];		
		$password = $post_data['password'];
		/* encrypt the password using sha1 algorithm */
		$password = do_hash($password);

		$sql = "SELECT userId from users where forgotToken='".$forgotToken."'";
		$record = $this->db->query($sql);
        if ($record->num_rows()>0) {
                $data  = array('password' =>$password,'forgotToken'=>"");
                $this->db->where('forgotToken', $forgotToken);
                return $this->db->update('users',$data);   

        }else{
                return false;
            }
	}


}