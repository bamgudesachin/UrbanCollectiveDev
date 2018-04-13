<?php

Class User Extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('admin_api/User_model');
        $this->config->load('my_constants');
        //date_default_timezone_set("Asia/Kolkata");
        //date_default_timezone_get();		
    }

    public function user_list() {
        /* For pagination */
        $config = array();
        $this->load->library('pagination');

        //$data['page'] = ($this->uri->segment(8)) ? $this->uri->segment(8) : 1;
        $data['page'] = ($this->uri->segment(11)) ? $this->uri->segment(11) : 1;
        //print_r($this->uri->segment(4));exit();
        $config["per_page"] = $this->uri->segment('4');
        $searchtext = $this->uri->segment('5');

        $startDate = $this->uri->segment('6');
        $endDate = $this->uri->segment('7');
        $criteria = $this->uri->segment('8');

        $sortby = $this->uri->segment('9');
        $order = $this->uri->segment('10');
        
        $config["base_url"] = site_url() . "admin/User/user_list/" . $config["per_page"] . "/" . $searchtext . "/" . $startDate . "/". $endDate . "/". $criteria . "/". $sortby . "/" . $order . "/";

        $total_row = $this->User_model->get_user_list_count($config["per_page"], $searchtext,$startDate,$endDate,$criteria, $sortby, $order, $data['page']);
        //print_r($total_row);exit();
        $config["total_rows"] = intval($total_row);
        //$config["per_page"] = 2;
        $config['use_page_numbers'] = TRUE;
        $config["num_links"] = intval($total_row); //floor($choice);
        //config for bootstrap pagination class integration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="' . site_url() . 'admin/User/user_list/' . $config["per_page"] . '/' . $searchtext . '/'. $startDate . '/'. $endDate . '/'. $criteria . '/' . $sortby . '/' . $order . '">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $data['users'] = $this->User_model->get_user_list($config["per_page"], $searchtext,$startDate,$endDate,$criteria, $sortby, $order, $data['page']);
        $data['links'] = $this->pagination->create_links();
        $data['title'] = 'Users';
        $data['main_content'] = 'admin/user_list';
        
        if($_GET){
            if($_GET['type'] == 'delete'){
                $data['message'] = 'Users deleted successfully';
            }
            if($_GET['type'] == 'testUser'){
                $data['message'] = 'Test user created successfully';
            }
            if($_GET['type'] == 'error'){
                $data['message'] = 'Something went wrong, Please try again later.';
            }

            if($_GET['type'] == 'realUser'){
                $data['message'] = 'Real user created successfully';
            }
        }
        $this->load->view('admin/includes/template', $data);
    }
    
    public function add_user(){
        $data['title'] = 'Add Users';
        $data['main_content'] = 'admin/add_user';
        $this->load->view('admin/includes/template', $data);
    }

    public function insert_user() {
        
        $this->form_validation->set_rules($this->config->item('add_user'));

        if ($this->form_validation->run() == FALSE) {
           $this->add_user(); 
            
        } else {
            $data = array(
                'firstName' => $this->input->post('firstName'),
                'lastName' => $this->input->post('lastName'),
                'email' => $this->input->post('email'),
                'password' => do_hash($this->input->post('password')),
                'age' => $this->input->post('age'),
                'gender' => $this->input->post('gender'),
                'workEducation' => $this->input->post('workEducation'),
                'city' => $this->input->post('city'),
            );
            $is_insert = $this->User_model->insert_user($data);
            if($is_insert){
                $this->session->set_flashdata('success_message', 'User Added successfully!');
		        $startDate = date("Y-m-d"); 
                $endDate = date('Y-m-d',strtotime("-1 days"));
                redirect('admin/User/user_list/100/nosearch/'. $endDate.'/'.$startDate.'/lastLogin/createdAt/desc/1','refresh');
            } else {
                $this->session->set_flashdata('success_message', 'Could not insert user.');
		        $startDate = date("Y-m-d"); 
                $endDate = date('Y-m-d',strtotime("-1 days"));
                redirect('admin/User/user_list/100/nosearch/'. $endDate.'/'.$startDate.'/lastLogin/createdAt/desc/1','refresh');
            }
        }
    }
    public function edit_user($userId){
        $data['user'] = $this->User_model->get_user($userId);   
        $data['title'] = 'Edit Users';
        $data['main_content'] = 'admin/edit_user';
        $this->load->view('admin/includes/template', $data);
    }
    public function update_user() {

        $userId = $this->input->post('userId');
        $email = $this->input->post('email');
        $is_unique_email = $this->User_model->is_email_unique($email,$userId);
        if($is_unique_email){
            $this->form_validation->set_rules($this->config->item('update_user'));
            if ($this->form_validation->run() == FALSE) {
               $this->edit_user($userId); 
            } else {
                $data = array(
                    'firstName' => $this->input->post('firstName'),
                    'lastName' => $this->input->post('lastName'),
                    'email' => $this->input->post('email'),
                    'age' => $this->input->post('age'),
                    'gender' => $this->input->post('gender'),
                    'workEducation' => $this->input->post('workEducation'),
                    'city' => $this->input->post('city'),
                );
                $is_update = $this->User_model->update_user($data);
                if($is_update){
                    $this->session->set_flashdata('success_message', 'User updated successfully!');
                    $startDate = date("Y-m-d"); 
                    $endDate = date('Y-m-d',strtotime("-1 days"));
                    redirect('admin/User/user_list/100/nosearch/'. $endDate.'/'.$startDate.'/lastLogin/createdAt/desc/1','refresh');
                } else {
                    $this->session->set_flashdata('success_message', 'Could not update user.');
                    $startDate = date("Y-m-d"); 
                    $endDate = date('Y-m-d',strtotime("-1 days"));
                    redirect('admin/User/user_list/100/nosearch/'. $endDate.'/'.$startDate.'/lastLogin/createdAt/desc/1','refresh');
                }
            }
       } else {
           
           $this->session->set_flashdata('failure_message', 'Email already exist.');
           redirect('admin/User/edit_user/'.$userId,'refresh');
       }
    }
    
    public function download_user_data() {
        //$config["per_page"] = $this->uri->segment('4');
        $record_per_page = $this->uri->segment('4');
        $searchtext = $this->uri->segment('5');

        $startDate = $this->uri->segment('6');
        //echo $startDate;exit();
        $endDate = $this->uri->segment('7');
        $criteria = $this->uri->segment('8');

        $sortby = $this->uri->segment('9');
        $order = $this->uri->segment('10');

        $data['newuser'] = $this->User_model->download_user_data($record_per_page,$searchtext,$startDate,$endDate,$criteria,$sortby,$order);
        //print_r($data['newuser']);exit();

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=users-list.xls");
        $this->load->view('admin/user_report', $data);
    }
    public function download_user_sherpa_interest() {
        $record_per_page = $this->uri->segment('4');
        $searchtext = $this->uri->segment('5');

        $startDate = $this->uri->segment('6');
        //echo $startDate;exit();
        $endDate = $this->uri->segment('7');
        $criteria = $this->uri->segment('8');

        $sortby = $this->uri->segment('9');
        $order = $this->uri->segment('10');

        $data['newuser'] = $this->User_model->download_user_sherpa_interest($record_per_page,$searchtext,$startDate,$endDate,$criteria,$sortby,$order);
       // print_r($data);exit();
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=sherpa-users-list.xls");
        $this->load->view('admin/user_report', $data);
    }
    
    public function sherpa_notification(){
        $data['title'] = 'Sherpa Notification';
        $data['main_content'] = 'admin/notification_view';
        $this->load->view('admin/includes/template', $data);
        //$data['newuser'] = $this->user_model->get_sherpa_interested_users();
    }
	
    public function send_sherpa_notification()
    {        
        $msg = $this->input->post('notificationText');        
        $tokens = array();
        $androidTokens = array();
        $deviceTokens = $this->User_model->get_sherpa_interested_users();		
        foreach($deviceTokens as $deviceToken){
			if($deviceToken['deviceToken']){
				if($deviceToken['deviceType'] == "android"){
					$androidTokens[] = $deviceToken['deviceToken'];
				}else{
					$tokens[] = $deviceToken['deviceToken'];
				}  
			}                      
        }
        //print_r($tokens);exit;
        if(count($tokens)>0){
                $payload = json_encode([
                    'aps' => [
                            'alert' => 'Admin has send you '.$msg,
                            'sound' => 'cat.caf',
                            'badge' => 1,
                            'content-available' => 1
                    ],
                    "push_type"=> 'sherpa_invitation'//redirect url
            ]); 
            //print_r($payload);exit(); 
            $this->send_multiple_user_notification_ios($tokens, $payload);             
        }

        if(count($androidTokens)>0){
            /*Send push to android app users*/
            $payload = array("message" => $msg,
                             "push_type"=> 'sherpa_invitation'
                            );
              //print_r($payload);exit;
            $this->send_notification_android($androidTokens, $payload); 
        }
        if($tokens || $androidTokens){			
            //$this->send_multiple_user_notification_ios($tokens, $payload);
            $this->session->set_flashdata('success_message', 'Notification send successfully');   
        } else {
            $this->session->set_flashdata('success_message', 'No one is connected to sherpa.');   
        }

        $startDate = date("Y-m-d"); 
        $endDate = date('Y-m-d',strtotime("-1 days"));
        redirect('admin/User/user_list/100/nosearch/'. $endDate.'/'.$startDate.'/lastLogin/createdAt/desc/1','refresh');
         //print_r($tokens);exit;
        
    }
	
    // public function update_test_flag($userId){
    //     $data['user'] = $this->User_model->update_test_flag($userId);   
    //     //$data['title'] = 'Edit Users';
    //    // $data['main_content'] = 'admin/edit_user';
    //    // $this->load->view('admin/includes/template', $data);

    //     redirect('admin/User/user_list/100/nosearch/createdAt/asc/1','refresh');
    // }

    public function update_test_flag($userId=NULL)
    {
        if(!empty($userId))
        {
            $userId=$userId;
        }
        else
        {
            $userId=$this->uri->segment(4);
        }
        //echo $userId;exit();
        $record_per_page = $this->uri->segment('5');
        $searchtext = $this->uri->segment('6');

        $startDate = $this->uri->segment('7');
        //echo $startDate;exit();
        $endDate = $this->uri->segment('8');
        $criteria = $this->uri->segment('9');

        $sortby = $this->uri->segment('10');
        $order = $this->uri->segment('11');
            //print_r($class_id);exit();
            $query=$this->User_model->update_test_flag($userId);
            if ($query == "Test User") {
                $this->session->set_flashdata('success_message', 'User is Test User now!');               
                redirect('admin/User/user_list/'.$record_per_page.'/'.$searchtext.'/'. $startDate.'/'.$endDate.'/'.$criteria.'/'.$sortby.'/'.$order.'/1','refresh');
            }else{
                if($query == 'Normal User'){
                    $this->session->set_flashdata('success_message', 'User is Normal User now!');
                    //$startDate = date("Y-m-d"); 
                   // $endDate = date('Y-m-d',strtotime("-1 days"));
                   redirect('admin/User/user_list/'.$record_per_page.'/'.$searchtext.'/'. $startDate.'/'.$endDate.'/'.$criteria.'/'.$sortby.'/'.$order.'/1','refresh');
                }                           
            }
    }

    /* Delete the user and associated data */
    public function delete_user($userId=NULL)
    {
        if(!empty($userId))
        {
            $userId=$userId;
        }
        else
        {
            $userId=$this->uri->segment(4);
        }
        
        $record_per_page = $this->uri->segment('5');
        $searchtext = $this->uri->segment('6');

        $startDate = $this->uri->segment('7');
        //echo $startDate;exit();
        $endDate = $this->uri->segment('8');
        $criteria = $this->uri->segment('9');

        $sortby = $this->uri->segment('10');
        $order = $this->uri->segment('11');

            $query=$this->User_model->delete_user($userId);
            if ($query) {
                $this->session->set_flashdata('success_message', 'User deleted successfully');
               // $startDate = date("Y-m-d"); 
               // $endDate = date('Y-m-d',strtotime("-1 days"));
                
                redirect('admin/User/user_list/'.$record_per_page.'/'.$searchtext.'/'. $startDate.'/'.$endDate.'/'.$criteria.'/'.$sortby.'/'.$order.'/1','refresh');
            }else{                
                    $this->session->set_flashdata('failure_message', 'Fail to delete user');
                    redirect('admin/User/user_list/'.$record_per_page.'/'.$searchtext.'/'. $startDate.'/'.$endDate.'/'.$criteria.'/'.$sortby.'/'.$order.'/1','refresh');                                        
            }
    }


    /* Delete multiple users */
    public function deleteMultipleUsers(){
        $data = $this->input->post('data');   
       // print_r($data);exit();    
        $query = $this->User_model->deleteMultipleUsers($data);
        if ($query) {
            $response = array(
                            'ResponseCode' => 1,
                            'ResponseMessage' => 'SUCCESS', 
                            'Comments' => 'User deleted successfully.',
                            'Result' =>true,
                            'Status' =>200
                         );
            echo json_encode($response);
        }else{
            $response = array(
                            'ResponseCode' => 0,
                            'ResponseMessage' => 'FAILURE', 
                            'Comments' => 'Fail to delete user',
                            'Result' =>false,
                            'Status' =>400
                         );
            echo json_encode($response);
        }
    }

    /* Make test or normal multiple users */
    public function testMultipleUsers(){
        $data = $this->input->post('data');   
       // print_r($data);exit();    
        $query = $this->User_model->testMultipleUsers($data);
        if ($query) {
            $response = array(
                            'ResponseCode' => 1,
                            'ResponseMessage' => 'SUCCESS', 
                            'Comments' => 'User status changed successfully.',
                            'Result' =>true,
                            'Status' =>200
                         );
            echo json_encode($response);
        }else{
            $response = array(
                            'ResponseCode' => 0,
                            'ResponseMessage' => 'FAILURE', 
                            'Comments' => 'Fail to change user status',
                            'Result' =>false,
                            'Status' =>400
                         );
            echo json_encode($response);
        }
    }

    /* Make test or normal multiple users */
    public function realMultipleUsers(){
        $data = $this->input->post('data');   
       // print_r($data);exit();    
        $query = $this->User_model->realMultipleUsers($data);
        if ($query) {
            $response = array(
                            'ResponseCode' => 1,
                            'ResponseMessage' => 'SUCCESS', 
                            'Comments' => 'User status changed successfully.',
                            'Result' =>true,
                            'Status' =>200
                         );
            echo json_encode($response);
        }else{
            $response = array(
                            'ResponseCode' => 0,
                            'ResponseMessage' => 'FAILURE', 
                            'Comments' => 'Fail to change user status',
                            'Result' =>false,
                            'Status' =>400
                         );
            echo json_encode($response);
        }
    }


    
}
?>