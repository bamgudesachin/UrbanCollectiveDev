<?php

Class Admin Extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('admin_api/Admin_model');
        $this->load->library('pagination');
        $config['per_page'] = 1;
    }

    public function users() {
        $config['total_rows'] = $this->Admin_model->get_all_users(1);
        $data['users'] = $this->Admin_model->get_all_users();
        
        //echo '<pre>',print_r($data),'</pre>';exit;
        $data['main_content'] = 'admin/user_list';
        $this->load->view('admin/includes/template', $data);
    }

}

?>