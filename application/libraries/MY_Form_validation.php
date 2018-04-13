<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * Additional validations for URL testing.
 *
 * @package      Module Creator
 * @subpackage  ThirdParty
 * @category    Libraries
 * @author  Brian Antonelli <brian.antonelli@autotrader.com>
 * @created 11/19/2010
 */
 
class MY_Form_validation extends CI_Form_validation{
  
    function alpha_dash_space($str)
    {
     $this->CI->form_validation->set_message('alpha_dash_space','The %s should contain only characters and spaces.'); 
     //return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
     if(!preg_match("/^([-a-z-A-Z_ ])+$/i", $str)){
       return FALSE;
     }
      return TRUE; 
    }                                
                         
    /**
     * Validate URL format
     *
     * @access  public
     * @param   string
     * @return  string
     */
    function valid_url_format($str){
        $pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
        if (!preg_match($pattern, $str)){
            $this->set_message('valid_url_format', 'The URL you entered is not correctly formatted.');
            return FALSE;
        }
 
        return TRUE;
    }       
 
    // --------------------------------------------------------------------
     
 
    /**
     * Validates that a URL is accessible. Also takes ports into consideration. 
     * Note: If you see "php_network_getaddresses: getaddrinfo failed: nodename nor servname provided, or not known" 
     *          then you are having DNS resolution issues and need to fix Apache
     *
     * @access  public
     * @param   string
     * @return  string
     */
    function url_exists($url){                                   
        $url_data = parse_url($url); // scheme, host, port, path, query
        if(!fsockopen($url_data['host'], isset($url_data['port']) ? $url_data['port'] : 80)){
            $this->set_message('url_exists', 'The URL you entered is not accessible.');
            return FALSE;
        }               
         
        return TRUE;
    }  

    function isValidLatitude($latitude){
        if (!preg_match("/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/", $latitude)) {
            $this->set_message('isValidLatitude', 'The Latitude is not valid.');
            return FALSE;
        }
        
        return TRUE;        
    }

    function isValidLongitude($longitude){
        if(!preg_match("/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,6}$/",
          $longitude)) {
            $this->set_message('isValidLongitude', 'The Longitude is not valid.');
            return FALSE;
        }
        
        return TRUE;
    }
}
?>