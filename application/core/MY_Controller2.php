<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');
require_once('./application/libraries/REST_Controller.php');
class MY_Controller2 extends REST_Controller
{
    public $data = array();
    
    function __construct ()
    {
        parent::__construct();
        $post_data = $this->post();
        //$role = $post_data['role'];
        print_r($post_data['token_expiry']);exit;
    }
}?>