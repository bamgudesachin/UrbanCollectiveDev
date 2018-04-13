<?php

class Property_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    public function get_property_list($record_per_page, $searchtext, $column_name, $order_by, $pageno) {
        //echo $record_per_page;exit;
        $offset = ($pageno - 1) * $record_per_page;
        if ($searchtext != 'nosearch' && $searchtext != '') {
            $keyword = "where p.propertyName LIKE '%$searchtext%' OR p.propertyUrl LIKE '%$searchtext%' OR p.description LIKE '%$searchtext%' OR p.price LIKE '%$searchtext%' OR p.createdAt LIKE '%$searchtext%' OR p.availableDate LIKE '%$searchtext%' OR p.availableDate LIKE '%$searchtext%'";
        } else {
            $keyword = "";
        }
        if ($order_by) {
            $sort = "order by $column_name $order_by";
        } else {
            $sort = "";
        }

        $query = "select * from shortlistedproperty p $keyword $sort LIMIT $offset, $record_per_page";
        $record = $this->db->query($query);
        if ($record->num_rows() > 0) {
            return $record->result_array();
        }
    }

    public function get_property_list_count($record_per_page, $searchtext, $column_name, $order_by, $pageno) {
        $offset = ($pageno - 1) * $record_per_page;
        if ($searchtext != 'nosearch' && $searchtext != '') {
            $keyword = "where p.propertyName LIKE '%$searchtext%' OR p.propertyUrl LIKE '%$searchtext%' OR p.description LIKE '%$searchtext%' OR p.price LIKE '%$searchtext%' OR p.createdAt LIKE '%$searchtext%' OR p.availableDate LIKE '%$searchtext%' OR p.availableDate LIKE '%$searchtext%'";
        } else {
            $keyword = "";
        }
        if ($order_by) {
            $sort = "order by $column_name $order_by";
        } else {
            $sort = "";
        }

        $query = "select * from shortlistedproperty p $keyword $sort";
        $record = $this->db->query($query);
        if ($record->num_rows() > 0) {
            return $record->num_rows();
        }
    }
    

}

?>