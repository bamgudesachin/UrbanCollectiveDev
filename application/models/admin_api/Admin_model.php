<?php

class Admin_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    public function get_all_users($check = NULL){
        
        $this->db->select('*');
        $this->db->from('users');
        $query = $this->db->get();
        if($check){
            return $query->num_rows();
            
        } else {
            return $query->result_array();
        }
        
    }

}

?>