<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public $table1 = 'users';    
  
     public function register($post_data)
    {          
        $lastdate = date("Y-m-d h:i:s");
        $post_data['lastLogin'] = $lastdate;
        $password = array_key_exists("password",$post_data);
        if($password){
           //$post_data['password'] = do_hash($password); 
           $post_data['password'] = $post_data['password'];
        }      

        $profile_picture = array_key_exists("profile_picture",$post_data);
        if($profile_picture){
            unset($post_data['profile_picture']);
            $post_data['profile_picture'] = $_POST['profile_picture1'];   
        }
        
        /* Update the facebookId if user try to login with fb with same email which is register by normal login */
        $facebookId = array_key_exists("facebookId",$post_data);
        if($facebookId){
            $post_data['facebookId'] = $post_data['facebookId'];   
        } 

        $dummyId = array_key_exists("dummyId",$post_data);
            if($dummyId){
                $dummyId = $post_data['dummyId'];  
                unset($post_data['dummyId']);
            } 
       
        $email = strtolower($post_data['email']);
        $email_exists = $this->email_check($email);
        if($email_exists){
            /* update facebookId according to email in user table */
            //$_POST['success'] = "Facebook account attached successfully";
            $data = array('facebookId' => $post_data['facebookId']);
            $this->db->where('email',$email);
            $is_update = $this->db->update('users',$data);
            if($is_update){                       
                        $is_update = true;
                    }
            if($dummyId){
                /*get userId */
                    $userId = "SELECT userId FROM users where email='".$email."'";
                    $user_result = $this->db->query($userId);
                    if($user_result->num_rows()>0){
                        $userId = $user_result->row('userId');
                    }

                    /*get dummy userId */
                    $dummy_userId = "SELECT userId FROM users where email='".$dummyId."'";
                    $result = $this->db->query($dummy_userId);
                    if($result->num_rows()>0){
                        $dummy_userId = $result->row('userId');

                        $query = "SELECT * FROM tribe WHERE inviteEmail='".$dummyId."'";
                        $record = $this->db->query($query);
                        if($record->num_rows()>0){
                            /*Update the userId in tribe table*/
                            $touserId = array('touserId'=>$userId,
                                              'inviteEmail'=>$email);
                            $this->db->where('inviteEmail',$dummyId);
                            $is_update = $this->db->update('tribe',$touserId);
                            if($is_update){                                
                                $is_update = true;
                            }
                        }//tribe

                        /*update userId of dummy to real user in  searchcriteria and commute table*/
                        $query2 = "SELECT * FROM searchcriteria WHERE userId=$dummy_userId";
                        $record2 = $this->db->query($query2);
                        if($record2->num_rows()>0){
                            $search_userId = array('userId'=>$userId);
                            $this->db->where('userId',$dummy_userId);
                            $is_update = $this->db->update('searchcriteria',$search_userId);
                            if($is_update){                                
                                $is_update = true;
                            }
                        }//searchcriteria

                        $query3 = "SELECT * FROM commute WHERE userId=$dummy_userId";
                        $record3 = $this->db->query($query3);
                        if($record3->num_rows()>0){
                            $commute_userId = array('userId'=>$userId);
                            $this->db->where('userId',$dummy_userId);
                            $is_update = $this->db->update('commute',$commute_userId);
                            if($is_update){                               
                                $is_update = true;
                            }
                        }//commute

                        /* Delete dummy user*/
                        $this->db->where('userId', $dummy_userId);
                        $this->db->delete('users');
                    }//dummy userid check
                }//dummyid

            if($is_update){
                    $_POST['success'] = "Facebook account attached successfully";
                    return true;
                }

        }else{
            
            $post_data['email'] = strtolower($post_data['email']);

            $is_update = true;
            $is_register = $this->db->insert($this->table1, $post_data);
            if($is_register){
                $userId = $this->db->insert_id();
                $email = strtolower($post_data['email']);  
                /*Update the touserId if user exist in Tribe table */
                $sql = "SELECT * FROM tribe WHERE inviteEmail='".$email."'";
                $record = $this->db->query($sql);
                if($record->num_rows()>0){
                    /*Update the userId in tribe table*/
                    $touserId = array('touserId'=>$userId);
                    $this->db->where('inviteEmail',$email);
                    $is_update = $this->db->update('tribe',$touserId);
                    if($is_update){                       
                        $is_update = true;
                    }
                }

               /*Update the real data agains dummy user and delete thid user*/
                if($dummyId){
                    /*get dummy userId */
                    $dummy_userId = "SELECT userId FROM users where email='".$dummyId."'";
                    $result = $this->db->query($dummy_userId);
                    if($result->num_rows()>0){
                        $dummy_userId = $result->row('userId');

                        $query = "SELECT * FROM tribe WHERE inviteEmail='".$dummyId."'";
                        $record = $this->db->query($query);
                        if($record->num_rows()>0){
                            /*Update the userId in tribe table*/
                            $touserId = array('touserId'=>$userId,
                                              'inviteEmail'=>$email);
                            $this->db->where('inviteEmail',$dummyId);
                            $_POST['success'] = "Register successfully";
                            $is_update = $this->db->update('tribe',$touserId);
                            if($is_update){                                
                                $is_update = true;
                            }
                        }//tribe

                        /*update userId of dummy to real user in  searchcriteria and commute table*/
                        $query2 = "SELECT * FROM searchcriteria WHERE userId=$dummy_userId";
                        $record2 = $this->db->query($query2);
                        if($record2->num_rows()>0){
                            $search_userId = array('userId'=>$userId);
                            $this->db->where('userId',$dummy_userId);
                            $is_update = $this->db->update('searchcriteria',$search_userId);
                            if($is_update){                                
                                $is_update = true;
                            }
                        }//searchcriteria

                        $query3 = "SELECT * FROM commute WHERE userId=$dummy_userId";
                        $record3 = $this->db->query($query3);
                        if($record3->num_rows()>0){
                            $commute_userId = array('userId'=>$userId);
                            $this->db->where('userId',$dummy_userId);
                            $is_update = $this->db->update('commute',$commute_userId);
                            if($is_update){                               
                                $is_update = true;
                            }
                        }//commute

                        /* Delete dummy user*/
                        $this->db->where('userId', $dummy_userId);
                        $this->db->delete('users');
                    }//dummy userid check
                }//dummyid

                if($is_update){
                    $_POST['success'] = "Register successfully";
                    return true;
                } else {
                    $_POST['success'] = "Register successfully";
                    return true;
                }

            }//is_register
        } //else
        
    } 
	
    public function get_facebook_user($facebookId)
    {
        $sql1 = "SELECT userId,firstName,lastName,email,profile_picture,age,gender,workEducation,city,password,connectSherpaFlag,latitude,longitude,deviceToken,deviceType,facebookId,deleteFlag,lastLogin,createdAt FROM users WHERE facebookId = '".$facebookId."' AND deleteFlag !=1";
        $record1 = $this->db->query($sql1);
        if ($record1->num_rows()>0) {                
            return $record1->result_array();
        }else{
            return false;
        }        
    }
    
     public function email_check($email)
    {   $email = strtolower($email);
        $sql1 = "SELECT userId,deleteFlag FROM users WHERE email='".$email."'";
        $record1 = $this->db->query($sql1);
        if ($record1->num_rows()>0) {
            $result_data = $record1->result_array();
            $deleteFlag = $result_data[0]['deleteFlag'];            
            if($deleteFlag == 1){
                $data = array('deleteFlag' => 0);
                $this->db->where('email',$email);
                return $this->db->update('users',$data);
            }
            return true;
        }else{
            return false;
        }
    }

    public function login($post_data)
    {    
        $lastdate = date("Y-m-d h:i:s");
        $email = strtolower($post_data['email']);  
        $password = $post_data['password'];
        //$password = do_hash($password);
        $device_token = array_key_exists("deviceToken",$post_data);
        if($device_token){
            $device_token = $post_data['deviceToken'];
        }else{
            $device_token = '';
        }

        $device_type = array_key_exists("deviceType",$post_data);
        if($device_type){
            $device_type = $post_data['deviceType'];
        }else{
            $device_type = '';
        }

        /* check the email is register by facebbok or not, if yes the return error */
        $check_user_exist = "SELECT * FROM users WHERE email = '".$email."' AND deleteFlag !=1";
        $result = $this->db->query($check_user_exist);
        if ($result->num_rows()>0) {
            $data = $result->result_array();            
            $user_password = $data[0]['password'];
            $facebookId = $data[0]['facebookId'];
            $userId = $data[0]['userId'];
            if($user_password == ''  && $facebookId ){
                $_POST['login_error'] = "This email was used to register with facebook,Please use facebook login";  
                return false;  
            }else{
                if($password == $user_password) {
                    $lastdate_update = "UPDATE users SET lastLogin ='".$lastdate."',
                                        deviceToken ='".$device_token."',
                                        deviceType ='".$device_type."'
                                        WHERE email ='".$email."'";
                    $record1 = $this->db->query($lastdate_update);
                    $data[0]['lastLogin'] = $lastdate;
                    $data[0]['deviceToken'] = $device_token;
                    $data[0]['deviceType'] = $device_type;

                    /* update data against dummy user */
                    $dummyId = array_key_exists("dummyId",$post_data);
                    if($dummyId){
                        $dummyId = $post_data['dummyId'];  
                        unset($post_data['dummyId']);

                        if($dummyId){
                            /*get dummy userId */
                            $dummy_userId = "SELECT userId FROM users where email='".$dummyId."'";
                            $result = $this->db->query($dummy_userId);
                            if($result->num_rows()>0){
                                $dummy_userId = $result->row('userId');

                                $query = "SELECT * FROM tribe WHERE inviteEmail='".$dummyId."'";
                                $record = $this->db->query($query);
                                if($record->num_rows()>0){
                                    /*Update the userId in tribe table*/
                                    $touserId = array('touserId'=>$userId,
                                                      'inviteEmail'=>$email);
                                    $this->db->where('inviteEmail',$dummyId);
                                    
                                    $is_update = $this->db->update('tribe',$touserId);
                                    if($is_update){                  
                                        $is_update = true;
                                    }
                                }//tribe

                                /*update userId of dummy to real user in  searchcriteria and commute table*/
                                $query2 = "SELECT * FROM searchcriteria WHERE userId=$dummy_userId";
                                $record2 = $this->db->query($query2);
                                if($record2->num_rows()>0){
                                    $search_userId = array('userId'=>$userId);
                                    $this->db->where('userId',$dummy_userId);
                                    $is_update = $this->db->update('searchcriteria',$search_userId);
                                    if($is_update){                    
                                        $is_update = true;
                                    }
                                }//searchcriteria

                                $query3 = "SELECT * FROM commute WHERE userId=$dummy_userId";
                                $record3 = $this->db->query($query3);
                                if($record3->num_rows()>0){
                                    $commute_userId = array('userId'=>$userId);
                                    $this->db->where('userId',$dummy_userId);
                                    $is_update = $this->db->update('commute',$commute_userId);
                                    if($is_update){                   
                                        $is_update = true;
                                    }
                                }//commute

                                /* Delete dummy user*/
                                $this->db->where('userId', $dummy_userId);
                                $this->db->delete('users');
                            }//dummy userid check
                        }//dummyid
                    }

                    return $data;          
                }else{
                    $_POST['login_error'] = "Password not valid"; 
                    return false;   
                }
            }
        }else{
            $_POST['login_error'] = "Please enter a valid email and password"; 
            return false;
        }
    }

    public function fb_login_dummy_user($fb_data)
    {            
        $userId = $fb_data[0]['userId'];
        $lastdate = date("Y-m-d h:i:s");
        $email = strtolower($fb_data[0]['email']);  

        $device_token = array_key_exists("deviceToken",$fb_data[0]);
        if($device_token){
            $device_token = $fb_data[0]['deviceToken'];
        }else{
            $device_token = '';
        }

        $device_type = array_key_exists("deviceType",$fb_data[0]);
        if($device_type){
            $device_type = $fb_data[0]['deviceType'];
        }else{
            $device_type = '';
        }
        
        $data = $fb_data;   
        if($data){
            $lastdate_update = "UPDATE users SET lastLogin ='".$lastdate."',
                                deviceToken ='".$device_token."',
                                deviceType ='".$device_type."'
                                WHERE email ='".$email."'";            
            $record1 = $this->db->query($lastdate_update);
            $data[0]['lastLogin'] = $lastdate;
            $data[0]['deviceToken'] = $device_token;
            $data[0]['deviceType'] = $device_type;            
            
            /* update data against dummy user */
            $dummyId = array_key_exists("dummyId",$data[0]);
            if($dummyId){                
                $dummyId = $data[0]['dummyId']; 
                unset($data[0]['dummyId']); 
                              
                if($dummyId){
                    /*get dummy userId */
                    $dummy_userId = "SELECT userId FROM users where email='".$dummyId."'";
                    $result = $this->db->query($dummy_userId);
                    if($result->num_rows()>0){
                        $dummy_userId = $result->row('userId');

                        $query = "SELECT * FROM tribe WHERE inviteEmail='".$dummyId."'";
                        $record = $this->db->query($query);
                        if($record->num_rows()>0){
                            /*Update the userId in tribe table*/
                            $touserId = array('touserId'=>$userId,
                                              'inviteEmail'=>$email);
                            $this->db->where('inviteEmail',$dummyId);
                            
                            $is_update = $this->db->update('tribe',$touserId);
                            if($is_update){                  
                                $is_update = true;
                            }
                        }//tribe

                        /*update userId of dummy to real user in  searchcriteria and commute table*/
                        $query2 = "SELECT * FROM searchcriteria WHERE userId=$dummy_userId";
                        $record2 = $this->db->query($query2);
                        if($record2->num_rows()>0){
                            $search_userId = array('userId'=>$userId);
                            $this->db->where('userId',$dummy_userId);
                            $is_update = $this->db->update('searchcriteria',$search_userId);
                            if($is_update){                    
                                $is_update = true;
                            }
                        }//searchcriteria

                        $query3 = "SELECT * FROM commute WHERE userId=$dummy_userId";
                        $record3 = $this->db->query($query3);
                        if($record3->num_rows()>0){
                            $commute_userId = array('userId'=>$userId);
                            $this->db->where('userId',$dummy_userId);
                            $is_update = $this->db->update('commute',$commute_userId);
                            if($is_update){                   
                                $is_update = true;
                            }
                        }//commute

                        /* Delete dummy user*/
                        $this->db->where('userId', $dummy_userId);
                        $this->db->delete('users');
                    }//dummy userid check
                }//dummyid
            }                
            return $data;                    
        }        
    }


    public function logout($post_data)
    {
        $userId = $post_data['userId'];
        $sql1 = "SELECT userId FROM users WHERE userId = $userId";
        $record1 = $this->db->query($sql1);
        if ($record1->num_rows()>0) {
                /*$device_token_update = "UPDATE users SET deviceToken ='' WHERE userId=$userId";
                $result = $this->db->query($device_token_update);	
				*/
            return true;
        }else{
            return false;
        }
    }
	

    /*function for make sure the  student id exists*/
    public function user_check($userId)
    {
        $sql1 = "SELECT userId FROM users WHERE userId ='".$userId."'";
        $record = $this->db->query($sql1);
        if ($record->num_rows()>0) {
            return true;
        }   
    }  
  
    /* Save tokens */
    public function save_token_with_expiry($post_data,$email)
    {
        $lastdate = date("Y-m-d h:i:s");
        $post_data['lastLogin'] = $lastdate; 
        if (empty($post_data['latitude'])) {
            unset($post_data['latitude']);
        }
        if (empty($post_data['longitude'])) {
            unset($post_data['longitude']);
        }
        unset($post_data['email']);
        unset($post_data['password']);
        unset($post_data['profile_picture']);
        unset($post_data['dummyId']);
        return $this->db->where('email',$email)->update($this->table1, $post_data);
    }

    public function refresh_token_check($refresh_token)
    {
        $sql = "SELECT refreshTokenExpiry FROM users WHERE refreshToken='".$refresh_token."'";
        $record = $this->db->query($sql);        
        if ($record->num_rows()>0) {
            return $record->row('refreshTokenExpiry');            
        }
    }

    public function access_token($post_data)
    {
        if (empty($post_data['latitude'])) {
            unset($post_data['latitude']);
        }
        if (empty($post_data['longitude'])) {
            unset($post_data['longitude']);
        }

        return $this->db->where('refreshToken',$post_data['refreshToken'])->update($this->table1, $post_data);
    }
	
	
    /*****************************************************************************************/

    /*function for check email exists or not */
     public function email_exists($email)
    {   
        $sql1 = "SELECT userId FROM users WHERE email='".$email."'";
        $record1 = $this->db->query($sql1);        
        if ($record1->num_rows()>0) {
            return true;
        }else
        {
           return false;
        }
        
    }

    /* forgot password */
    public function fogotPassword($post_data)
    {
        $email = strtolower($post_data['email']);
        $sql = "SELECT userId,firstName,email,forgotToken FROM users WHERE email='".$email."' AND deleteFlag !=1";
        $record = $this->db->query($sql);
        if ($record->num_rows()>0) {
                //return $record->row('student_id');
                return $record->result_array();
        }else{
                return false;
            }
    }    
    

    /* Save forgot tokens */
    public function save_forgot_token_with_expiry($post_data,$email)
    {        
        unset($post_data['email']);
        
        return $this->db->where('email',$email)->update($this->table1, $post_data);
    }

    public function connect_to_my_sherpa($post_data){
        $userId = $post_data['loginUserId']; 
        $email = $post_data['email']; 
        $firstName = $post_data['firstName']; 
        $forgotToken = $post_data['forgotToken'];
        $forgotTokenExpiry = $post_data['forgotTokenExpiry'];
        $date = date("Y-m-d H:i:s");

        $query = "SELECT * FROM users WHERE email ='".$email."'";
        $result = $this->db->query($query);
        if ($result->num_rows()>0) {            
            $updateData = array('connectSherpaFlag'=> 1,
                                'sherpaSignedup'=>$date
                            );

            $this->db->where('userId',$userId);
            $record = $this->db->update('users',$updateData);
            if($record){  
                $_POST['isExist'] = "yes";              
                return true;
            }else{
                return false;
            }  
        }else{
            $data = array('firstName'=> $firstName,
                    'email'=> $email,
                    'connectSherpaFlag'=> 1,
                    'sherpaSignedup'=>$date,
                    'forgotToken'=>$forgotToken,
                    'forgotTokenExpiry'=>$forgotTokenExpiry
                );

            $this->db->where('userId',$userId);
            $record = $this->db->update('users',$data);
            if($record){
                $_POST['isExist'] = "no";
                return true;
            }else{
                return false;
            }  
        }
    }

    public function insert_search_criteria($post_data){
        $searchName = $post_data['searchName'];
        $idealMovingDate = date('Y-m-d',strtotime($post_data['idealMovingDate']));
        $minPrice = $post_data['minPrice'];
        $maxPrice = $post_data['maxPrice'];
        $minBedroom = $post_data['minBedroom'];
        $maxBedroom = $post_data['maxBedroom'];
        $userId = $post_data['loginUserId'];
       
        $data = array('userId'=> $userId,'searchName'=> $searchName,'minPrice'=> $minPrice,'maxPrice'=>$maxPrice,'minBedroom'=>$minBedroom,'maxBedroom'=>$maxBedroom,'idealMovingDate'=>$idealMovingDate);
        $record = $this->db->insert('searchcriteria',$data);
        if($record){
            $searchId = $this->db->insert_id();
            return $searchId;
        }else{
            return false;
        }        
    }
	
	public function update_search_criteria($post_data){
        $searchName = $post_data['searchName'];
        $idealMovingDate = date('Y-m-d',strtotime($post_data['idealMovingDate']));
        $minPrice = $post_data['minPrice'];
        $maxPrice = $post_data['maxPrice'];
        $minBedroom = $post_data['minBedroom'];
        $maxBedroom = $post_data['maxBedroom'];
        $userId = $post_data['loginUserId'];
        $searchId = $post_data['searchId'];
       
        $data = array('userId'=> $userId,
                    'searchName'=> $searchName,
                    'minPrice'=> $minPrice,
                    'maxPrice'=>$maxPrice,
                    'minBedroom'=>$minBedroom,
                    'maxBedroom'=>$maxBedroom,
                    'idealMovingDate'=>$idealMovingDate
                );

        $this->db->where('userId',$userId);
        $this->db->where('searchId',$searchId);
        $record = $this->db->update('searchcriteria',$data);
        if($record){            
            return true;
        }else{
            return false;
        }        
    }
	
	/* Function for view property and increament view count*/
    public function view_property($post_data){
        $property_shortlisted_flag = false;         
        
        $userId = $post_data['userId']; 
        $searchId = $post_data['searchId'];
       // $propertyId = $post_data['propertyId'];
        $propertyId = array_key_exists("propertyId",$post_data);
        if($propertyId){ 
            $propertyId =  $post_data['propertyId']; 
            unset($post_data['propertyId']);
        }else{
            $propertyId = NULL;
        }
        
        $propertyName = array_key_exists("propertyName",$post_data);
        if($propertyName){ 
            $propertyName =  $post_data['propertyName']; 
            unset($post_data['propertyName']);
        }else{
            $propertyName = NULL;
        }
        //$propertyName = $post_data['propertyName'];
        $propertyUrl = array_key_exists("propertyUrl",$post_data);
        if($propertyUrl){ 
            $propertyUrl =  $post_data['propertyUrl']; 
            unset($post_data['propertyUrl']);
        }else{
            $propertyUrl = NULL;
        }        
        
        $imageUrl = array_key_exists("imageUrl",$post_data);
        if($imageUrl){ 
            $imageUrl =  $post_data['imageUrl']; 
            unset($post_data['imageUrl']);
        }else{
            $imageUrl = NULL;
        }
        
        
        $commuteTime = array_key_exists("commuteTime",$post_data);
        if($commuteTime){ 
            $commuteTime =  $post_data['commuteTime']; 
            unset($post_data['commuteTime']);
        }else{
            $commuteTime = NULL;
        }
        //$commuteTime = $post_data['commuteTime'];
        $description = array_key_exists("description",$post_data);
        if($description){ 
            $description =  $post_data['description']; 
            unset($post_data['description']);
        }
        //$description = $post_data['description'];
        $price = array_key_exists("price",$post_data);
        if($price){ 
            $price =  $post_data['price']; 
            unset($post_data['price']);
        }
        //$price = $post_data['price'];
        $availableDate = array_key_exists("availableDate",$post_data);
        if($availableDate){ 
            $availableDate =  $post_data['availableDate']; 
            unset($post_data['availableDate']);
        }
       
        $view_property_details = array(
                                   'propertyId'=> $propertyId,
                                   'propertyName'=> $propertyName,
                                   'propertyUrl'=> $propertyUrl,
                                   'imageUrl'=> $imageUrl,
                                   'commuteTime'=> $commuteTime,
                                   'description'=> $description,
                                   'price'=> $price,
                                   'availableDate'=> $availableDate
                                );

        /*Check the property is exists*/
        $sql = "SELECT * FROM properties WHERE  propertyId = $propertyId";
        $check_property_exist = $this->db->query($sql);
        if($check_property_exist->num_rows()>0){  
            /*Check the same user have view the property for same search*/
            $sql = "SELECT * FROM viewproperties WHERE userId =$userId AND propertyId = $propertyId AND searchId = $searchId";
            $check_property_viewed = $this->db->query($sql);
            if($check_property_viewed->num_rows()>0){
                $check_property_viewed = $check_property_viewed->result_array();
                $viewCount = $check_property_viewed[0]['viewCount'];

                $increment_view_count = array('viewCount'=> $viewCount + 1);

                $this->db->where('propertyId', $propertyId);
                $this->db->where('searchId', $searchId);
                $this->db->where('userId', $userId);
                $update = $this->db->update('viewproperties',$increment_view_count);
                if($update){
                    return true;
                }else{
                    return false;
                }
            }else{
                $save_property_details = array(
                               'userId'=> $userId,
                               'searchId'=> $searchId,
                               'propertyId'=> $propertyId,                                   
                               'viewCount'=> 1
                            );

                $save = $this->db->insert('viewproperties', $save_property_details);
                if($save){
                    return true;
                }else{
                    return false;
                }
            } 
        }else{
            //insert data in properties table            
            $inserted = $this->db->insert('properties', $view_property_details);
            if($inserted){  
                /*Check the same user have view the property for same search*/
                $sql = "SELECT * FROM viewproperties WHERE userId =$userId AND propertyId = $propertyId AND searchId = $searchId";
                $check_property_viewed = $this->db->query($sql);
                if($check_property_viewed->num_rows()>0){
                    $check_property_viewed = $check_property_viewed->result_array();
                    $viewCount = $check_property_viewed[0]['viewCount'];

                    $increment_view_count = array('viewCount'=> $viewCount + 1);

                    $this->db->where('propertyId', $propertyId);
                    $this->db->where('searchId', $searchId);
                    $this->db->where('userId', $userId);
                    $update = $this->db->update('viewproperties',$increment_view_count);
                    if($update){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    $save_property_details = array(
                                   'userId'=> $userId,
                                   'searchId'=> $searchId,
                                   'propertyId'=> $propertyId,                                   
                                   'viewCount'=> 1
                                );

                    $save = $this->db->insert('viewproperties', $save_property_details);
                    if($save){
                        return true;
                    }else{
                        return false;
                    }
                }
            }else{
                return false;
            }
        }
    }
    
}?>