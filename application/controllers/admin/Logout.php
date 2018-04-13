<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class logout Extends CI_Controller{

	public function index()
	{
		$this->session->sess_destroy();
		redirect('login/index','refresh');
	}
}?>