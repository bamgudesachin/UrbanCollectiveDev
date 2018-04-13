<?php

Class Property Extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('admin_api/Property_model');
        $this->config->load('my_constants');
        //date_default_timezone_set("Asia/Kolkata");
        //date_default_timezone_get();		
    }
    public function properties_list(){
        $config = array();
        $this->load->library('pagination');
        $data['page'] = ($this->uri->segment(8)) ? $this->uri->segment(8) : 1;
        //print_r($this->uri->segment(4));exit();
        $config["per_page"] = $this->uri->segment('4');
        $searchtext = $this->uri->segment('5');
        $sortby = $this->uri->segment('6');
        $order = $this->uri->segment('7');
        
        $config["base_url"] = site_url() . "admin/Property/properties_list/" . $config["per_page"] . "/" . $searchtext . "/" . $sortby . "/" . $order . "/";

        $total_row = $this->Property_model->get_property_list_count($config["per_page"], $searchtext, $sortby, $order, $data['page']);
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
        $config['cur_tag_open'] = '<li class="active"><a href="' . site_url() . 'admin/Property/properties_list/' . $config["per_page"] . '/' . $searchtext . '/' . $sortby . '/' . $order . '">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        $data['properties'] = $this->Property_model->get_property_list($config["per_page"], $searchtext, $sortby, $order, $data['page']);
        $data['links'] = $this->pagination->create_links();
        $data['title'] = 'Users';
        $data['main_content'] = 'admin/property_list';
        $this->load->view('admin/includes/template', $data);
    }
    
    public function add_property(){
        $data['title'] = 'Add Property';
        $data['main_content'] = 'admin/add_property';
        $this->load->view('admin/includes/template', $data);
    }
}
?>