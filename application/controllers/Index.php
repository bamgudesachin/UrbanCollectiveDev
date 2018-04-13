<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once('./application/libraries/REST_Controller.php');

class index extends CI_Controller {

	public function __construct()
	{
	    parent::__construct();
	    $this->config->load('my_constants');	   
	}

	public function index()
	{   
		echo "welcome !";
		//$this->load->view('customer/main_view');
	}


}?>