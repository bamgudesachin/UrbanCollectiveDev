<?php

Class Logout Extends CI_Controller{

	public function index()
	{
		$this->session->sess_destroy();
		redirect('Login/index','refresh');
	}
}?>