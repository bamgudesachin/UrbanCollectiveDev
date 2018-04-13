<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class Login Extends CI_Controller{

	function __construct()
	{
		parent::__construct();	
		$this->config->load('my_constants');		
        $this->load->helper('security');
		$this->load->model('admin_api/Login_model');
		//$this->config->load('my_constants');			
	}

	public function index()
	{	
		$logged_in=$this->session->userdata('logged_in');
		if (!empty($logged_in)) {			
		    redirect('admin/Dashboard');
	 	}else{			
		    $data['title'] = 'Login';
			$this->load->view('admin/Login_view',$data);
		}
		
	}

	/* check the user is valid or not */
	public function validate_user()
	{   
                
		//$login_data = $this->config->item('login_data');
		if($q=$this->Login_model->validate_user()){
			redirect('admin/Dashboard');
		}else{
			$this->session->set_flashdata('login_failure','Username Or Password Incorrect.');
			redirect('login/index','refresh');
		}
	}

	public function forgot_password()
	{	
		$logged_in=$this->session->userdata('logged_in');
		if (!empty($logged_in)) {			
		    redirect('admin/Dashboard');
	 	}else{			
		    $data['title'] = 'Forgot Password';
			$this->load->view('admin/ForgotPassword_view',$data);
		}
		
	}



}?>