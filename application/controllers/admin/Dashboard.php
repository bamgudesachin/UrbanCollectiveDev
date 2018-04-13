<?php

Class Dashboard Extends MY_Controller{

	function __construct()
	{
		parent::__construct();	
		$this->load->model('admin_api/Dashboard_model');
        
        //date_default_timezone_set("Asia/Kolkata");
        //date_default_timezone_get();		
	}

  
	public function index()
	{	
        $data['active_dashboard'] = 'open';
		$data['title'] = 'Dashboard';	
		// $data['active_doctor']=$this->Dashboard_model->get_all_active_doctors();
		// $data['inactive_doctor']=$this->Dashboard_model->get_all_deactive_doctors();
		// $data['active_patient']=$this->Dashboard_model->get_all_active_patients();	
		// $data['inactive_patient']=$this->Dashboard_model->get_all_deactive_patients();

         
    
   // print_r($data['main_role']);exit();
	$data['main_content'] = 'admin/Dashboard_view';
    $this->load->view('admin/includes/template', $data);			
	}

	



}?>