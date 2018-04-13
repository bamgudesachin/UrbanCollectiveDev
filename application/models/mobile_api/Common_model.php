<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

date_default_timezone_set('UTC');

class Common_model extends CI_Model
{
    
    /*function for make sure the  student id exists*/
    public function user_check($userId)
    {
        $sql1 = "SELECT userId FROM users WHERE userId ='".$userId."'";
        $record = $this->db->query($sql1);
        if ($record->num_rows()>0) {
            return true;
        }   
    }  
	
	
	/*function for make sure the  login user in tribe*/
    public function user_in_tribe_check($loginUserId,$searchId)
    {        
        /*If search is created by login user then dont Check login user is in tribe */
        $searchCheck = "SELECT * FROM searchcriteria s WHERE s.searchId = $searchId AND s.userId = $loginUserId";
        $result = $this->db->query($searchCheck);
        if ($result->num_rows()>0) {
            return true;
        }else{
            $sql1 = "SELECT * from tribe t where t.searchId = $searchId AND (t.touserId =$loginUserId OR t.fromuserId =$loginUserId) AND t.status = 'accept' AND t.deleteFlag != 1";
            //echo $sql1;exit();
            $record = $this->db->query($sql1);
            if ($record->num_rows()>0) {
                return true;
            } 
        }
    } 

    /*function for make sure the  search criteria exists*/
    public function search_criteria_check($searchId)
    {
        $sql1 = "SELECT searchId FROM searchcriteria WHERE searchId ='".$searchId."'";
        $record = $this->db->query($sql1);
        if ($record->num_rows()>0) {
            return true;
        }   
    }  

    /*function for make sure the  shortlisted property id exists*/
    public function shortlisted_property_check($shortlistedId)
    {
        $sql1 = "SELECT shortlistedId FROM shortlistedproperty WHERE shortlistedId ='".$shortlistedId."'";
        $record = $this->db->query($sql1);
        if ($record->num_rows()>0) {
            return true;
        }   
    }  

    /* Get Profile details */
    public function profile_detail($post_data){
        $userId = $post_data['userId'];

        $sql = "SELECT userId,firstName,email,password,profile_picture,age,gender,workEducation,city,deviceToken,facebookId,token,tokenExpiry,refreshToken,refreshTokenExpiry,latitude,longitude,lastLogin,connectSherpaFlag FROM users WHERE userId = $userId";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();
        }

    }

    /* Update Profile details */
    public function updateProfile($post_data)
    {

        $userId = $post_data['userId'];

        $query = $this->db->where('userId',$userId)->update('users', $post_data);        
        if($query){
            $sql = "SELECT * FROM users WHERE userId = $userId";
            $record = $this->db->query($sql);
            if($record->num_rows()>0){
                return $record->result_array();
            }

        }else{
            return false;
        }
    }
 
 
    /* Check the email for user is exist */
    public function update_email_check($email,$userId)
    {
        $sql1 = "SELECT userId FROM users WHERE userId = $userId AND email='".$email."'";
        $record1 = $this->db->query($sql1);
        if ($record1->num_rows()==1) {
            return false;
        } else {
            return true;
        }
    }

     
   
   /////////////////////////////////////////////////////////////////////////////////////////

    /* Save Basic search criteria */
     public function save_search_criteria($post_data){
        unset($post_data['token']);
        // $userId = $post_data['userId'];
        //print_r($post_data);exit();
        $commute = array_key_exists("commute",$post_data);
        if($commute){ 
            $commute =  $post_data['commute']; 
            unset($post_data['commute']);

        }

        // $preferences = array_key_exists("preferences",$post_data);
        // if($preferences){            
        //     $preferences = $post_data['preferences']; 
        //     $post_data['proximity'] = implode(",",$preferences['proximity']);
        //     $post_data['avoid_proximity'] = implode(",",$preferences['avoid_proximity']);
        //     unset($post_data['preferences']);
        // } 

        $keywords = array_key_exists("keywords",$post_data);
        if($keywords){ 
            $post_data['keywords'] = implode(",",$post_data['keywords']);
        }

        $advanceSearch = array_key_exists("advanceSearch",$post_data);
        if($advanceSearch){            
            $advanceSearch = $post_data['advanceSearch'];     

           $postcode = array_key_exists("postcode",$advanceSearch);
            if($postcode){ 
                $post_data['area'] = $advanceSearch['postcode'];
                unset($advanceSearch['postcode']);
            }
            
            // $post_data['area'] = $advanceSearch['postcode'];            

            $location_preferences = array_key_exists("location_preferences",$advanceSearch);
            if($location_preferences){ 
                $post_data['proximity'] = implode(",",$advanceSearch['location_preferences']);
                unset($advanceSearch['location_preferences']);
            }
           
           // $post_data['keywords'] = implode(",",$advanceSearch['keywords']);
           // $post_data['proximity'] = implode(",",$advanceSearch['location_preferences']);
            $avoid_location_preferences = array_key_exists("avoid_location_preferences",$advanceSearch);
            if($avoid_location_preferences){ 
                $post_data['avoid_proximity'] = implode(",",$advanceSearch['avoid_location_preferences']);
                unset($advanceSearch['avoid_location_preferences']);
            }

           // $post_data['avoid_proximity'] = implode(",",$advanceSearch['avoid_location_preferences']);
            $avoid_location = array_key_exists("avoid_location",$advanceSearch);
            if($avoid_location){ 
                $post_data['avoid_area'] = $advanceSearch['avoid_location'];
                unset($advanceSearch['avoid_location']);
            }
			
           // $post_data['avoid_area'] = $advanceSearch['avoid_location'];
            //$post_data['avoid_keywords'] = implode(",",$advanceSearch['avoid_keywords']);  
            unset($post_data['advanceSearch']);
        } 

        
        $topSearch = array_key_exists("topSearch",$post_data);
        if($topSearch){
            $topSearch = $post_data['topSearch'];
            $criteria1 = array_key_exists("criteria1",$topSearch);
            if($criteria1){ 
                $post_data['criteria1'] = $topSearch['criteria1'];
                unset($topSearch['criteria1']);
            }
            //$post_data['criteria1'] = $topSearch['criteria1'];
            $criteria2 = array_key_exists("criteria2",$topSearch);
            if($criteria2){ 
                $post_data['criteria2'] = $topSearch['criteria2'];
                unset($topSearch['criteria2']);
            }
            //$post_data['criteria2'] = $topSearch['criteria2'];
            $criteria3 = array_key_exists("criteria3",$topSearch);
            if($criteria3){ 
                $post_data['criteria3'] = $topSearch['criteria3'];
                unset($topSearch['criteria3']);
            }
           // $post_data['criteria3'] = $topSearch['criteria3'];
            unset($post_data['topSearch']);
        }		
		
         
        // check searchId is present
		$searchId = array_key_exists("searchId",$post_data);
        if($searchId){
            $searchId = $post_data['searchId'];
		}
       // $searchId = $post_data['searchId'];
        if($searchId){
            $update = $this->db->where('searchId',$searchId)->update('searchcriteria', $post_data);
            if($update){   
                /* Delete the previous commute */
                $this->db->where('userId', $post_data['userId']);
                $this->db->where('searchId', $searchId);

                if($commute){
                    if($this->db->delete('commute')){
                        /* Insert data in commute table */
                        $commute_details = array();                    
                            foreach($commute as $key){  
							
                                    $commute_data = array(
                                        "userId"=>$post_data['userId'],
                                        "searchId" => $searchId,
                                        "commuteName"=> $key['commuteName'],
                                        "destination"=> $key['destination'],
                                        "timeofCommuting"=> array_key_exists("timeofCommuting",$key) ? $key['timeofCommuting'] : '',
                                        "modeOfCommute"=> array_key_exists("modeOfCommute",$key) ? $key['modeOfCommute'] : '',
                                        "maxCommuteTime"=> $key['maxCommuteTime'],
                                        "destLatitude"=> $key['destLatitude'],
                                        "destLongitude"=> $key['destLongitude'],
                                        "primaryCommute"=> $key['primaryCommute']
                                    );
                                    $commute_details[] =  $commute_data;
                                } 
                                           
                      // print_r($commute_details);exit();
                        $this->db->insert_batch('commute', $commute_details);  
                    }     
                }  //comute       
                 
                $_POST['message'] = "Search criteria updated successfully.";
                return $searchId;
            }

        }else{
            //print_r($post_data);exit();
            $insert =  $this->db->insert("searchcriteria", $post_data);
            if($insert){
                $searchId = $this->db->insert_id();
                /* Insert data in commute table */
               // print_r($post_data['commute']);exit();
                $commute_details = array();
                foreach($commute as $key){                
                        $commute_data = array(
                            "userId"=>$post_data['userId'],
                            "searchId" => $searchId,
                            "commuteName"=> $key['commuteName'],
                            "destination"=> $key['destination'],
                            "timeofCommuting"=> array_key_exists("timeofCommuting",$key) ? $key['timeofCommuting'] : '',
                            "modeOfCommute"=> array_key_exists("modeOfCommute",$key) ? $key['modeOfCommute'] : '',
                            "maxCommuteTime"=> $key['maxCommuteTime'],
                            "destLatitude"=> $key['destLatitude'],
                            "destLongitude"=> $key['destLongitude'],
                            "primaryCommute"=> $key['primaryCommute']
                        );
                        $commute_details[] =  $commute_data;
                    }
                        
                $this->db->insert_batch('commute', $commute_details); 
                $_POST['message'] = "Search criteria added successfully.";
                return $searchId;
            }
        }
    }
	
    /* Delete search criteria */
    public function delete_search_criteria($post_data){
        $userId = $post_data['userId'];
        $searchId = $post_data['searchId'];
        $data = array('deleteFlag' => 1);

        $this->db->where('userId',$userId);
        $this->db->where('searchId',$searchId);   
        $this->db->update('searchcriteria',$data); 
        if($this->db->affected_rows()>0){
            $this->db->where('userId',$userId);
            $this->db->where('searchId',$searchId); 
            $this->db->update('commute',$data);
           if($this->db->affected_rows()>0)
           {
             return true;
           }else{
             return false;
           }
        }
    }
   
   /* count search criteria list */
   public function count_search_criteria_list($post_data){
        $userId = $post_data['userId'];              
        $date = date("Y-m-d H:i:s");
        $keyword = $post_data['keyword'];
        $latitude = $post_data['latitude'];
        $longitude = $post_data['longitude'];
       // $filter = $post_data['filter']; //fav_teacher/all
       // $type =  $post_data['type']; // upcoming/past
        

        if(!empty($keyword)){
            $keyword = "AND (s.searchName LIKE '%$keyword%' OR s.idealMovingDate LIKE '%$keyword%' OR s.houseType LIKE '%$keyword%' OR s.priceRange LIKE '%$keyword%' OR s.bedroom LIKE '%$keyword%')";
        }

        if(!empty($latitude) && !empty($longitude))
        {
            $distance_in_km = "( 6371 * acos( cos( radians($latitude) ) * cos( radians( lc.latitude) ) 
                * cos( radians( lc.longitude ) - radians($longitude) ) + 
                    sin( radians($latitude) ) * sin( radians( lc.latitude ) ) ) ) 
                AS distance_in_km";
        }

        if (!empty($latitude) && !empty($longitude)) {

            $sql = "SELECT s.*,u.userId,u.firstName,u.lastName,((SELECT COUNT(*) from tribe t1 WHERE t1.searchId = s.searchId AND t1.status ='accept' AND t1.deleteFlag != 1) +1)  as participantCount,
                (SELECT COUNT(*) from shortlistedproperty sp WHERE sp.searchId = s.searchId AND sp.deleteFlag !=1) as properties
                 FROM searchcriteria s  
                    left join tribe t on s.searchId = t.searchId AND t.fromuserId = $userId
                    left join users u on s.userId = u.userId
                    left join shortlistedproperty p on s.searchId = p.searchId 
                    where s.searchId in (SELECT t.searchId from tribe t where (t.touserId =$userId OR t.fromuserId =$userId) AND t.status = 'accept' AND t.deleteFlag != 1) OR s.userId =$userId $keyword
                    GROUP BY s.searchId ORDER BY s.createdAt DESC";
                    
        }else{
             $sql = "SELECT s.*,u.userId,u.firstName,u.lastName,((SELECT COUNT(*) from tribe t1 WHERE t1.searchId = s.searchId AND t1.status ='accept' AND t1.deleteFlag != 1)+1) as participantCount,
                (SELECT COUNT(*) from shortlistedproperty sp WHERE sp.searchId = s.searchId AND sp.deleteFlag !=1) as properties
                 FROM searchcriteria s  
                    left join tribe t on s.searchId = t.searchId AND t.fromuserId = $userId
                    left join users u on s.userId = u.userId
                    left join shortlistedproperty p on s.searchId = p.searchId 
                    where s.searchId in (SELECT t.searchId from tribe t where (t.touserId =$userId OR t.fromuserId =$userId) AND t.status = 'accept' AND t.deleteFlag != 1) OR s.userId =$userId $keyword
                    GROUP BY s.searchId ORDER BY s.createdAt DESC";
                    
        }

        $record = $this->db->query($sql);
        return $record->num_rows();
   }

   /* Search criteria list */
   public function search_criteria_list($post_data){
        $userId = $post_data['userId'];              
        $date = date("Y-m-d H:i:s");
        $keyword = $post_data['keyword'];
        $latitude = $post_data['latitude'];
        $longitude = $post_data['longitude'];
        $limit = $post_data['limit'];
        $offset = $post_data['offset'];
       // $filter = $post_data['filter']; //fav_teacher/all
       // $type =  $post_data['type']; // upcoming/past
        

        if(!empty($keyword)){
            $keyword = "AND (s.searchName LIKE '%$keyword%' OR s.idealMovingDate LIKE '%$keyword%' OR s.houseType LIKE '%$keyword%' OR s.priceRange LIKE '%$keyword%' OR s.bedroom LIKE '%$keyword%')";
        }

        if(!empty($latitude) && !empty($longitude))
        {
            $distance_in_km = "( 6371 * acos( cos( radians($latitude) ) * cos( radians( lc.latitude) ) 
                * cos( radians( lc.longitude ) - radians($longitude) ) + 
                    sin( radians($latitude) ) * sin( radians( lc.latitude ) ) ) ) 
                AS distance_in_km";
        }

        if (!empty($latitude) && !empty($longitude)) {
                $sql = "SELECT s.searchId,s.userId,s.searchName,s.idealMovingDate,s.area,s.minPrice,s.maxPrice,s.minBedroom,s.maxBedroom,u.userId,u.firstName,u.lastName,((SELECT COUNT(*) from tribe t1 WHERE t1.searchId = s.searchId AND t1.status ='accept' AND t1.deleteFlag != 1)+1) as participantCount,
                (SELECT COUNT(*) from shortlistedproperty sp WHERE sp.searchId = s.searchId AND sp.deleteFlag !=1) as properties
                 FROM searchcriteria s  
                    left join tribe t on s.searchId = t.searchId AND t.fromuserId = $userId
                    left join users u on s.userId = u.userId
                    left join shortlistedproperty p on s.searchId = p.searchId 
                    where s.searchId in (SELECT t.searchId from tribe t where (t.touserId =$userId OR t.fromuserId =$userId) AND t.status = 'accept' AND t.deleteFlag != 1) OR s.userId =$userId $keyword
                    GROUP BY s.searchId ORDER BY s.createdAt DESC LIMIT $offset, $limit";
        }else{
                $sql = "SELECT s.searchId,s.userId,s.searchName,s.idealMovingDate,s.area,s.minPrice,s.maxPrice,s.minBedroom,s.maxBedroom,u.userId,u.firstName,u.lastName,((SELECT COUNT(*) from tribe t1 WHERE t1.searchId = s.searchId AND t1.status ='accept' AND t1.deleteFlag != 1)+1) as participantCount,
                (SELECT COUNT(*) from shortlistedproperty sp WHERE sp.searchId = s.searchId AND sp.deleteFlag !=1) as properties
                 FROM searchcriteria s  
                    left join tribe t on s.searchId = t.searchId AND t.fromuserId = $userId
                    left join users u on s.userId = u.userId
                    left join shortlistedproperty p on s.searchId = p.searchId 
                    where s.searchId in (SELECT t.searchId from tribe t where (t.touserId = $userId OR t.fromuserId =$userId) AND t.status = 'accept' AND t.deleteFlag != 1) OR s.userId =$userId  $keyword
                    GROUP BY s.searchId ORDER BY s.createdAt DESC LIMIT $offset, $limit";
        }

        //echo $sql;exit();
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();
        }        
   }

   /* function for search criteria details */
    public function get_commute_detail($userId,$searchId){
       // $userId = $post_data['userId'];
       // $searchId = $post_data['searchId'];

        $sql ="SELECT c.commuteId,c.commuteName,c.destination,c.timeofCommuting,c.modeOfCommute,c.maxCommuteTime,c.destLatitude,c.destLongitude,c.primaryCommute              
                FROM commute c,users u WHERE u.userId = c.userId  AND c.searchId = $searchId AND c.deleteFlag != 1 AND c.userId =$userId";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();            
        } 
   }


    /* function for search criteria details */
    public function get_preference_detail($userId,$searchId){
        //$userId = $post_data['userId'];
        //$searchId = $post_data['searchId'];

        $sql ="SELECT s.*,u.userId,u.firstName,u.lastName
                            
                FROM searchcriteria s,users u WHERE u.userId = s.userId  AND s.deleteFlag != 1  AND s.searchId = $searchId  AND s.userId =$userId";
        
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array()[0];
            //print_r($record->result_array());exit();
        } 
   }

  /* save tribe using email */
   public function save_tribe($post_data){
        //print_r($post_data);exit();
        unset($post_data['token']);
        $userId = $post_data['userId'];
        $searchId = $post_data['searchId'];
        //print_r($post_data);exit();
        $invitees = array_key_exists("invitees",$post_data);        
        if($invitees){ 
           // $invitees =  $post_data['invitees']; 
            $invitees =  $post_data['invitees']; 
            unset($post_data['invitees']);
        }

        $invitees_details = array();
        $exist_users_data = array();
        $non_exist_users_data = array();
        $check_flag = false;

        foreach($invitees as $key){
            $data = array();
            $email = strtolower($key['inviteEmail']);
            
            /*get the exist users data and push in array*/
            $exist_users = "SELECT u.userId,u.firstName,u.deviceToken,u.deviceType,u.email,t.deleteFlag,t.status   
                    FROM users u 
                    LEFT JOIN tribe t ON t.inviteEmail = u.email AND t.searchId = $searchId  AND t.fromuserId = $userId  
                    WHERE u.email = '".$email."' GROUP BY u.userId";  
            //echo $exist_users;exit();
            $record = $this->db->query($exist_users);
            //$data = $record->result_array();
            if($record->num_rows()>0){
                $data = $record->result_array();
                if(($data[0]["status"] != "accept") || ($data[0]["status"] == "accept" and $data[0]["deleteFlag"] != $key['deleteFlag'])){
                    $data[0]["deleteFlag"] = $key['deleteFlag'];
                    array_push($exist_users_data, $data[0]);
                }
                
            }else{
                $non_exist_users_data[] = $email; 
            }

            /* if delete flag is 1 then update record else insert */    
            if($key['deleteFlag'] == 1) {                
                $update_invite_data = array(
					"status"=> "",
                    "deleteFlag"=> 1
                );
                
                $this->db->where('fromuserId',$userId);
                $this->db->where('searchId',$searchId);
                $this->db->where('inviteEmail',strtolower($key['inviteEmail']));
                $is_update = $this->db->update('tribe',$update_invite_data);
                if($is_update){
                    $_POST['tribe'] = "Users deleted from tribe successfully !";
                    $is_update = true;
                }else {
                    $is_update = false;
                }
                
            }else if($key['deleteFlag'] == 0){
                $update_invite_data = array(
                        "deleteFlag"=> 0
                    );
                $success_msg = "Users added in tribe successfully !";   
                $email = strtolower($key['inviteEmail']);
                $sql = "SELECT * FROM tribe WHERE inviteEmail IN ('$email')  AND fromuserId = $userId AND searchId = $searchId";
                $data1 = $this->db->query($sql);
                //print_r($data->result_array());exit();
                if($data1->num_rows()>0){
                    //update previous record
                    $update_invite_data = array(
                        "deleteFlag"=> 0
                    );
                    
                    $this->db->where('fromuserId',$userId);
                    $this->db->where('searchId',$searchId);
                    $this->db->where('inviteEmail',strtolower($key['inviteEmail']));
                    $is_update = $this->db->update('tribe',$update_invite_data);
                    if($is_update){
                        $_POST['tribe'] = "Users added in tribe successfully !";
                        $is_update = true;
                    }else {
                        $is_update = false;
                    }
                   
                }else{
                    $invite_data1= array(
                                    "searchId"=>$searchId,
                                    "fromuserId"=>$userId,
                                    "touserId"=> sizeof($data)>0 ?$data[0]['userId']:0,
                                    "deleteFlag"=> 0,
                                    "inviteEmail"=> strtolower($key['inviteEmail'])
                                );
                    $invitees_details[] =  $invite_data1;
                }
               
            }//else if
            
        }//foreach delete

        if(sizeof($invitees_details)>0){
            $is_update = $this->db->insert_batch('tribe', $invitees_details); 
            if($is_update){
                $_POST['tribe'] = "Users added in tribe successfully !";
                return array($is_update,$exist_users_data,$non_exist_users_data);
            }
        }else{
            if($is_update){
                $is_update = true;
            } else {
                $is_update = false;
            }
        }
        return array($is_update,$exist_users_data,$non_exist_users_data);
       
   }


   /* Function for get the device token of exists users for push notification after invite in tribe*/
   public function get_exists_users($post_data){
        $userId = $post_data['userId'];
        $searchId = $post_data['searchId'];
        //print_r($post_data);exit();
        $invitees = array_key_exists("invitees",$post_data);
        if($invitees){ 
            $invitees =  $post_data['invitees']; 
            $emails = array();
            for($i=0;$i<sizeof($invitees);$i++){
                $emails[$i]['inviteEmail'] = strtolower($invitees[$i]['inviteEmail']);
            }
          // print_r($emails);exit();
            $str_email = implode(',', array_map(function($el){ return $el['inviteEmail']; }, $emails));
            $str = "'".$str_email."'";
            $email =  str_replace(",","','",$str);
			//print_r($email);exit();
            $userId = $post_data['userId'];
           // echo $email;exit();

           /* $sql = "SELECT userId,firstName,deviceToken,email FROM users 
                    WHERE email IN ($email)";
                    */
            $sql = "SELECT u.userId,u.firstName,u.deviceToken,u.email,t.deleteFlag,t.status   
                    FROM users u 
                    LEFT JOIN tribe t ON t.inviteEmail = u.email  
                    WHERE u.email IN ($email) AND t.status != 'accept' AND t.searchId = $searchId  AND t.fromuserId = $userId  GROUP BY u.userId"; 
			//echo $sql;exit();
            $record = $this->db->query($sql);
            if($record->num_rows()>0){
                //print_r($record->result_array());exit();
                $users =  $record->result_array();
                $ids = array();
                foreach ($users as $key) {
                    $users_id =  array('touserId' =>$key['userId']);
                    $email = strtolower($key['email']);
                   // print_r($users_id);exit();
                    $this->db->where('fromuserId',$userId);
                    $this->db->where('searchId',$searchId);
                    $this->db->where('inviteEmail',$email);
                    $this->db->update('tribe',$users_id);

                    // $query = $this->db->get_where('tribe', array('touserId' => $key['userId']));
                    // $row = $query->row();
                    // $name = $row->name;
                    // $firstName = array('firstName' =>$name);
                    // $this->db->where('userId',$key['userId']);
                    // $this->db->update('users',$firstName);

                }

                
                return $record->result_array();
                           // print_r($record->result_array());exit();
            }
        }
   }

   /* Function for get email of non exists users for send email after invite in tribe*/
   public function get_not_exist_emails($post_data){
        $userId = $post_data['userId'];
        $searchId = $post_data['searchId'];
        //print_r($post_data);exit();
        $invitees = array_key_exists("invitees",$post_data);
        if($invitees){ 
            $invitees =  $post_data['invitees']; 
            $emails = array();
            $nonExistingEmails = array();
            for($i=0;$i<sizeof($invitees);$i++){
                $email = strtolower($invitees[$i]['inviteEmail']);
                $query = $this->db->query("select email from users where email =  '$email'");
                if($query->num_rows()==0){
                    $emails[$i]['inviteEmail'] = strtolower($invitees[$i]['inviteEmail']); 
                    $nonExistingEmails[] = strtolower($emails[$i]['inviteEmail']);
                }

            }
            return $nonExistingEmails;
        }
   }
   
   /*function for fetch user information*/
    public function get_user_info($userId,$searchId){
        $sql = "SELECT u.firstName,s.searchName,u.deviceType from users u,searchcriteria s where u.userId = s.userId AND  u.userId =$userId AND s.searchId =$searchId AND s.deleteFlag !=1 AND u.deleteFlag !=1";
        $result = $this->db->query($sql);
        return $result->result_array(); 
    }


    /* Count the my tribe members list */
    public function count_get_my_tribe_member_list($post_data){
        $searchId = $post_data['searchId'];
        $loginUserId = $post_data['loginUserId']; 
        
        $sql = "SELECT * FROM tribe WHERE searchId= $searchId AND fromuserId= $loginUserId  AND deleteFlag !=1 AND status = 'accept'";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->num_rows();
        }       
    }
	
	/* Count the my tribe members list */
    public function count_my_tribe_member($post_data){
        $searchId = $post_data['searchId'];
        $userId = $post_data['userId'];
        
        $sql = "SELECT COUNT(*)+1 FROM tribe WHERE searchId= $searchId AND fromuserId= $userId  AND deleteFlag !=1 AND status = 'accept'";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            $count = $record->num_rows();
            $total_tribes = $count + 1;            
            return $total_tribes;
        }          
    }

    /* Count the my tribe members list */
    public function get_my_tribe_member_list($post_data){
        $searchId = $post_data['searchId'];
        $loginUserId = $post_data['loginUserId']; 
        
        $sql = "SELECT * FROM tribe WHERE searchId= $searchId AND fromuserId= $loginUserId  AND deleteFlag !=1 AND status = 'accept'";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();
        }       
    }


    /* function for acept or decline invitation */
	/*
    public function accept_decline_invitation($post_data){        
        $userId =  $post_data['userId'];
        $searchId =  $post_data['searchId'];
        $status =  $post_data['status'];

        $data  = array('touserId'=>$userId,
                        'searchId'=>$searchId,                       
                       'status' =>$status
                       );
        if($status == 'accept')
        {            
            $sql = "SELECT * FROM tribe WHERE touserId = $userId AND searchId = $searchId AND (status = 'decline' OR status = '' OR status IS NULL)";
            //echo $sql;exit();
			$record = $this->db->query($sql);           
            if($record->num_rows() > 0)
            {  
                $this->db->where('touserId',$userId);
                $this->db->where('searchId',$searchId);
                $this->db->update('tribe',$data);
                if($this->db->affected_rows()>0){
                    $_POST['message_invite'] = 'Invitation accepted successfully';
                    return true;
                }else{
                    return false;
                }
            }else{
                    return false;
                }            
        }
        if($status == 'decline')
        {  
            $sql = "SELECT * FROM tribe WHERE touserId = $userId AND searchId = $searchId AND (status = 'accept' OR status = '' OR status IS NULL)";
            $record = $this->db->query($sql);           
            if($record->num_rows() > 0)
            {  
                $this->db->where('touserId',$userId);
                $this->db->where('searchId',$searchId);
                $this->db->update('tribe',$data);
                if($this->db->affected_rows()>0){
                    $_POST['message_invite'] = 'Invitation declined successfully';
                    return true;
                }else{
                    return false;
                }
            }else{
                    return false;
                }            
        }
    }
	*/
	/* function for acept or decline invitation */
    public function accept_decline_invitation($post_data){        
        $userId =  $post_data['userId'];
        $searchId =  $post_data['searchId'];
        $status =  $post_data['status'];

        $tribe_data = array();

        $data  = array('touserId'=>$userId,
                        'searchId'=>$searchId,                       
                       'status' =>$status
                       );
        if($status == 'accept')
        {            
            $sql = "SELECT * FROM tribe WHERE touserId = $userId AND searchId = $searchId AND (status = 'decline' OR status = '' OR status IS NULL)";
            //echo $sql;exit();
            $record = $this->db->query($sql);           
            if($record->num_rows()>0)
            {  
                $this->db->where('touserId',$userId);
                $this->db->where('searchId',$searchId);
                $this->db->update('tribe',$data);
                if($this->db->affected_rows()>0){
                    $_POST['message_invite'] = 'Invitation accepted successfully';
                    $is_update = true;
                    $sql = "SELECT u.userId,u.firstName,u.email,u.deviceToken,u.deviceType,u.deleteFlag  
                            FROM users u where (u.userId in (SELECT t.touserId FROM tribe t where t.searchId=$searchId AND t.touserId !=$userId AND t.status = 'accept' AND t.deleteFlag !=1 )
                            OR u.userId in (SELECT t.fromuserId FROM tribe t where t.searchId=$searchId AND t.fromuserId!=$userId)) AND u.deleteFlag !=1";
                    $result = $this->db->query($sql);           
                    if($result->num_rows() > 0)
                    {
                        $is_update = true;
                        $tribe_data = $result->result_array();
                        return array($is_update,$tribe_data);
                    }

                    return array($is_update,$tribe_data);
                }else{
                    $is_update = false;
                    return array($is_update,$tribe_data);
                }
            }else{
                    $is_update = false;
                    return array($is_update,$tribe_data);
                }            
        }

        if($status == 'decline')
        {  
            $sql = "SELECT * FROM tribe WHERE touserId = $userId AND searchId = $searchId AND (status = 'accept' OR status = '' OR status IS NULL)";
            $record = $this->db->query($sql);           
            if($record->num_rows() > 0)
            {  
                $this->db->where('touserId',$userId);
                $this->db->where('searchId',$searchId);
                $this->db->update('tribe',$data);
                if($this->db->affected_rows()>0){
                    $_POST['message_invite'] = 'Invitation declined successfully';
                    //return true;
                    $is_update = true;                        
                    return array($is_update,$tribe_data);
                }else{
                    //return false;
                    $is_update = false;                        
                    return array($is_update,$tribe_data);
                }
            }else{
                    //return false;
                    $is_update = false;                        
                    return array($is_update,$tribe_data);
                }            
        }
    }
	

    /* function for get user and search information */
    public function get_user_serach_info($searchId){
        $sql = "SELECT s.searchName,u.firstName,u.deviceToken,u.deviceType FROM searchcriteria s,users u WHERE s.userId = u.userId AND s.searchId = $searchId";
        $record = $this->db->query($sql);           
        if($record->num_rows() > 0)
        {
             return $record->result_array();
        }
    }

    /*function for fetch user information*/
    public function login_user_info($userId){
        $sql = "SELECT firstName,deviceToken,deviceType FROM users WHERE userId = $userId";
        $result = $this->db->query($sql);
        if($result->num_rows() > 0){
            return $result->result_array(); 
        }else{
            return false;
        }
    }
	

    /* function for shortlist the property */
    public function shortlist_property($post_data){
        unset($post_data['token']);
        //print_r($post_data);exit();
        /*$propertyUrl = array_key_exists("propertyUrl",$post_data);
        if($propertyUrl){
            unset($post_data['propertyUrl']);
            //$post_data['propertyUrl'] = $_POST['propertyUrl1'];   
            $post_data['propertyUrl'] = $post_data['propertyUrl'];
        }else{
            $post_data['propertyUrl'] = "";
        } 
        */ 
        $property_shortlisted_flag = false; 
		/*New change on 22 Dec 217*/
		$isRatingCriteria = $post_data['isRatingCriteria'];
		
        $rateCriteria1 = array_key_exists("rateCriteria1",$post_data);
        if($rateCriteria1){ 
            $rateCriteria1 = $post_data['rateCriteria1'];
            $rateCriteria1  = empty($rateCriteria1) ? NULL : $rateCriteria1;
            unset($post_data['rateCriteria1']);
        }
        //$rateCriteria1 = $post_data['rateCriteria1'];
       // $rateCriteria1  = empty($rateCriteria1) ? NULL : $rateCriteria1;
        $rateCriteria2 = array_key_exists("rateCriteria2",$post_data);
        if($rateCriteria2){ 
            $rateCriteria2 = $post_data['rateCriteria2'];
            $rateCriteria2  = empty($rateCriteria2) ? NULL : $rateCriteria2;
            unset($post_data['rateCriteria2']);
        }
        //$rateCriteria2 = $post_data['rateCriteria2'];
        //$rateCriteria2  = empty($rateCriteria2) ? NULL : $rateCriteria2;
        $rateCriteria3 = array_key_exists("rateCriteria3",$post_data);
        if($rateCriteria3){ 
            $rateCriteria3 = $post_data['rateCriteria3'];
            $rateCriteria3  = empty($rateCriteria3) ? NULL : $rateCriteria3;
            unset($post_data['rateCriteria3']);
        }
        //$rateCriteria3 = $post_data['rateCriteria3'];
       // $rateCriteria3  = empty($rateCriteria3) ? NULL : $rateCriteria3;
	   

        $userId = $post_data['userId'];
        $loginUserId = $post_data['loginUserId'];
        if($userId == $loginUserId){
            $userId = $userId;
        }else{
            $userId = $loginUserId;
        }
		
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
        //$propertyUrl = $post_data['propertyUrl'];
		
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
       // $availableDate = $post_data['availableDate'];

        $shortlistedId = array_key_exists("shortlistedId",$post_data);
        if($shortlistedId){ 
            $shortlistedId =  $post_data['shortlistedId']; 
            unset($post_data['shortlistedId']);
        }

        //echo $shortlistedId;exit();

        $shortlist_details = array('searchId'=> $searchId,
                                   'userId'=> $userId ,
                                   'propertyId'=> $propertyId,
                                   'propertyName'=> $propertyName,
                                   'propertyUrl'=> $propertyUrl,
								   'imageUrl'=> $imageUrl,
                                   'commuteTime'=> $commuteTime,
                                   'description'=> $description,
                                   'price'=> $price,
                                   'availableDate'=> $availableDate
                                );
        //print_r($shortlist_details);exit();

        if($shortlistedId){
            $shortlistedId = $shortlistedId;
            //print_r($shortlistedId);exit();
        }else{
            //insert data in shortlistedproperty table
            
            $inserted = $this->db->insert('shortlistedproperty', $shortlist_details);
            if($inserted){
                $property_shortlisted_flag = true;
                $shortlistedId = $this->db->insert_id();
            }else{
                return false;
            }
        }

        
        if($shortlistedId){
			/* update the topsearch criteria in searchcriteria table on 30 OCT 2017 */
           
            $criteria1 = array_key_exists("criteria1",$post_data);
            if($criteria1){ 
                $criteria1 = $post_data['criteria1'];
            }else{
                $criteria1 = "";
            }

            $criteria2 = array_key_exists("criteria2",$post_data);
            if($criteria2){ 
                $criteria2 = $post_data['criteria2'];
            }else{
                $criteria2 = "";
            }

            $criteria3 = array_key_exists("criteria3",$post_data);
            if($criteria3){ 
                $criteria3 = $post_data['criteria3'];
            }else{
                $criteria3 = "";
            }
			

            /* update topcriteria in search criteria here */
            if($searchId && $shortlistedId && $isRatingCriteria == "yes"){
                $topSearch_details = array(
                                      'criteria1'=> $criteria1,
                                      'criteria2'=> $criteria2,
                                      'criteria3'=> $criteria3
                                    );

                $this->db->where('searchId', $searchId);
                $save = $this->db->update('searchcriteria',$topSearch_details);
            }
 
            //print_r($shortlistedId);exit();
            /* Check the rate available or not */
            if($rateCriteria1 || $rateCriteria2 || $rateCriteria3){
                $total_no_rates = 0;
                if($rateCriteria1){
                    $total_no_rates++;
                }
                if($rateCriteria2){
                    $total_no_rates++;
                }
                if($rateCriteria3){
                    $total_no_rates++;
                }

                $average = ($rateCriteria1 + $rateCriteria2 + $rateCriteria3)/$total_no_rates;
                //echo $rateCriteria1;exit();
                $rate_details = array('userId'=> $userId,
                                      'shortlistedId'=> $shortlistedId,
                                      'searchId'=> $searchId,
                                      'propertyName'=> $propertyName,
                                      'rateCriteria1'=> $rateCriteria1,
                                      'rateCriteria2'=> $rateCriteria2 ,
                                      'rateCriteria3'=> $rateCriteria3,
                                      'average' =>$average
                                );
                //print_r($rate_details);exit();
            }else{
                $rate_details = array();
            }

            if(sizeof($rate_details) > 0){
                /* Check user already rate for the shortlisted property */
               $sql = "SELECT * FROM rating WHERE userId =$userId AND shortlistedId = $shortlistedId AND searchId = $searchId";
               $check_property_rated = $this->db->query($sql);
               if($check_property_rated->num_rows()>0){
                    $this->db->where('shortlistedId', $shortlistedId);
                    $this->db->where('searchId', $searchId);
                    $this->db->where('userId', $userId);
                    $save = $this->db->update('rating',$rate_details);
                    $msg = "rate updated";
               }else{
                    $save = $this->db->insert('rating', $rate_details);
                    if(!$property_shortlisted_flag){
                        //send push to tribe members
                        $msg = "rated";
                    }else{
                        //dont send push
                        $msg = "shortlisted and rated";
                    }
                    
               }

               
               /* update average in shortlisted table */
               if($save){
                    $average_update = "SELECT AVG(r.average)as property_avg,AVG(r.rateCriteria1) as avg_rate_criteria1,AVG(r.rateCriteria2) as avg_rate_criteria2,AVG(r.rateCriteria3) as avg_rate_criteria3

                        FROM rating r WHERE r.shortlistedId = $shortlistedId and r.searchId = $searchId AND r.average IS NOT NULL";
                        //echo $average_update;exit();
                    $shortlisted_avg = $this->db->query($average_update);
                    if ($shortlisted_avg->num_rows()>0)
                    {
                       $shortlisted_data = $shortlisted_avg->result_array();
                       $avg = $shortlisted_data[0]['property_avg'];
                       $avgRateCriteria1 = $shortlisted_data[0]['avg_rate_criteria1'];
                       $avgRateCriteria2 = $shortlisted_data[0]['avg_rate_criteria2'];
                       $avgRateCriteria3 = $shortlisted_data[0]['avg_rate_criteria3'];
                       
                       $shortlist_update  = array(
                                                  'avgPropertyRating' =>$avg,
                                                  'avgRateCriteria1' =>$avgRateCriteria1,
                                                  'avgRateCriteria2' =>$avgRateCriteria2,
                                                  'avgRateCriteria3' =>$avgRateCriteria3
                                                );

                       $this->db->where('shortlistedId', $shortlistedId);
                       $this->db->where('searchId', $searchId);
                        if($this->db->update('shortlistedproperty',$shortlist_update))
                        {
                            $_POST['message'] = "Property ".$msg." successfully";
                           //new changes on 8/9/2017
						   	// new change on 30/10/2017 left join with searchcriteria
                            $id = $post_data['userId'];
                            $sql = "SELECT s.shortlistedId,s.searchId,s.userId,s.avgPropertyRating,s.avgRateCriteria1,s.avgRateCriteria2,s.avgRateCriteria3,r.rateCriteria1,r.rateCriteria2,r.rateCriteria3,sc.criteria1,sc.criteria2,sc.criteria3 
                                FROM shortlistedproperty s                  
                                LEFT JOIN rating r ON  r.searchId = s.searchId AND r.shortlistedId = s.shortlistedId AND r.userId = $loginUserId                                    
								LEFT JOIN searchcriteria sc ON  sc.searchId = s.searchId
                                    where  s.searchId= $searchId AND s.userId = $id  AND s.deleteFlag != 1 AND s.shortlistedId = $shortlistedId";
									
									
					
						
                            //echo $sql;exit();
                            $record = $this->db->query($sql);
                            if($record->num_rows()>0){
                               // print_r($record->result_array()[0]);exit();
                                return $record->result_array()[0];
                            } 
                            // end new changes on 8/9/2017
                           // return $shortlistedId;
                        }
                    }//shortlisted_avg
               }//insert
            }else{
                $_POST['message'] = "Property shortlisted successfully";
                //new changes on 8/9/2017
			   // new change on 30/10/2017 left join with searchcriteria
                $id = $post_data['userId'];
                 $sql = "SELECT s.shortlistedId,s.searchId,s.userId,s.avgPropertyRating,s.avgRateCriteria1,s.avgRateCriteria2,s.avgRateCriteria3,r.rateCriteria1,r.rateCriteria2,r.rateCriteria3 ,sc.criteria1,sc.criteria2,sc.criteria3
                    FROM shortlistedproperty s                  
                    LEFT JOIN rating r ON  r.searchId = s.searchId AND r.shortlistedId = s.shortlistedId AND r.userId = $loginUserId                        
					LEFT JOIN searchcriteria sc ON  sc.searchId = s.searchId
                                    where  s.searchId= $searchId AND s.userId = $id  AND s.deleteFlag != 1 AND s.shortlistedId = $shortlistedId";
                //echo $sql;exit();
                $record = $this->db->query($sql);
                if($record->num_rows()>0){
                  //  print_r($record->result_array()[0]);exit();
                    return $record->result_array()[0];
                } 
                //end new changes on 8/9/2017
               // return $shortlistedId;
            }
        }else{
                return false;
            }
    }
	
	
	/* count Shortlisted property list */
    public function count_shortlisted_property_list($post_data){
        $loginUserId = $post_data['loginUserId'];  
        $userId = $post_data['userId'];   
        $searchId = $post_data['searchId']; 
        $keyword = $post_data['keyword'];
        $latitude = $post_data['latitude'];
        $longitude = $post_data['longitude'];
        $sortBy = array_key_exists("sortBy",$post_data);
        if($sortBy){ 
            $sortBy =  $post_data['sortBy']; //rate/commuteTime/price
            unset($post_data['sortBy']);
        }else{
            $sortBy = "";
        }
        $orderBy = array_key_exists("orderBy",$post_data);
        if($orderBy){ 
            $orderBy =  $post_data['orderBy']; //ASC/DESC
            unset($post_data['orderBy']);
        }else{
            $orderBy = "DESC";
        }
       // $filter = $post_data['filter']; //fav_teacher/all
       // $type =  $post_data['type']; // upcoming/past
        

        if(!empty($keyword)){
            $keyword = "AND (s.searchId LIKE '%$keyword%' OR s.propertyId LIKE '%$keyword%' OR s.propertyName LIKE '%$keyword%' OR s.commuteTime LIKE '%$keyword%' OR s.price LIKE '%$keyword%' OR s.availableDate LIKE '%$keyword%')";
        }

        if(!empty($latitude) && !empty($longitude))
        {
            $distance_in_km = "( 6371 * acos( cos( radians($latitude) ) * cos( radians( lc.latitude) ) 
                * cos( radians( lc.longitude ) - radians($longitude) ) + 
                    sin( radians($latitude) ) * sin( radians( lc.latitude ) ) ) ) 
                AS distance_in_km";
        }

        if($sortBy == "rating"){            
            $sortBy = "ORDER BY s.avgPropertyRating $orderBy";
        }else if($sortBy == "commuteTime"){
            $sortBy = "ORDER BY s.commuteTime $orderBy";
        }else if($sortBy == "price"){
            $sortBy = "ORDER BY s.price $orderBy";
        }
        else{
            $sortBy = "ORDER BY  s.createdAt DESC";
        }


        if (!empty($latitude) && !empty($longitude)) {

            $sql ="SELECT s.*,u.userId,u.firstName,u.lastName,((SELECT COUNT(*) FROM comments c where c.shortlistedId = s.shortlistedId AND c.searchId = s.searchId AND c.deleteFlag !=1) - (SELECT COUNT(*) FROM commentsread r where r.shortlistedId = s.shortlistedId AND r.userId = $loginUserId)) as new_comment_count,
                (SELECT COUNT(*) FROM comments c where c.shortlistedId = s.shortlistedId AND c.searchId = s.searchId AND c.deleteFlag !=1) as total_comments_count
                FROM shortlistedproperty s   
                left join users u on s.userId = u.userId
                where s.searchId= $searchId AND s.userId = $userId AND s.deleteFlag != 1
                $keyword $sortBy"; 
                    
        }else{
             $sql = "SELECT s.*,u.userId,u.firstName,u.lastName,((SELECT COUNT(*) FROM comments c where c.shortlistedId = s.shortlistedId AND c.searchId = s.searchId AND c.deleteFlag !=1) - (SELECT COUNT(*) FROM commentsread r where r.shortlistedId = s.shortlistedId AND r.userId = $loginUserId)) as new_comment_count,
                (SELECT COUNT(*) FROM comments c where c.shortlistedId = s.shortlistedId AND c.searchId = s.searchId AND c.deleteFlag !=1) as total_comments_count
                FROM shortlistedproperty s   
                left join users u on s.userId = u.userId
                where s.searchId= $searchId AND s.userId = $userId AND s.deleteFlag != 1
                $keyword $sortBy";
                    
        }

        $record = $this->db->query($sql);
        return $record->num_rows();
    }

    /* Shortlisted property list */
    public function shortlisted_property_list($post_data){
        $loginUserId = $post_data['loginUserId'];
        $userId = $post_data['userId'];
        $searchId = $post_data['searchId'];
        $keyword = $post_data['keyword'];
        $latitude = $post_data['latitude'];
        $longitude = $post_data['longitude'];
        $limit = $post_data['limit'];
        $offset = $post_data['offset'];
        $sortBy = array_key_exists("sortBy",$post_data);
        if($sortBy){ 
            $sortBy =  $post_data['sortBy']; //rate/commuteTime/price
            unset($post_data['sortBy']);
        }else{
            $sortBy = "";
        }
        $orderBy = array_key_exists("orderBy",$post_data);
        if($orderBy){ 
            $orderBy =  $post_data['orderBy']; //ASC/DESC
            unset($post_data['orderBy']);
        }else{
            $orderBy = "DESC";
        }

        //$sortBy = $post_data['sortBy']; //rate/commuteTime/
       // $filter = $post_data['filter']; //fav_teacher/all
       // $type =  $post_data['type']; // upcoming/past
        

        if(!empty($keyword)){
            $keyword = "AND (s.searchName LIKE '%$keyword%' OR s.idealMovingDate LIKE '%$keyword%' OR s.houseType LIKE '%$keyword%' OR s.priceRange LIKE '%$keyword%' OR s.bedroom LIKE '%$keyword%')";
        }

        if(!empty($latitude) && !empty($longitude))
        {
            $distance_in_km = "( 6371 * acos( cos( radians($latitude) ) * cos( radians( lc.latitude) ) 
                * cos( radians( lc.longitude ) - radians($longitude) ) + 
                    sin( radians($latitude) ) * sin( radians( lc.latitude ) ) ) ) 
                AS distance_in_km";
        }

        if($sortBy == "rating"){            
            $sortBy = "ORDER BY s.avgPropertyRating $orderBy";
        }else if($sortBy == "commuteTime"){
            $sortBy = "ORDER BY s.commuteTime $orderBy";
        }else if($sortBy == "price"){
            $sortBy = "ORDER BY s.price $orderBy";
        }
        else{
            $sortBy = "ORDER BY  s.createdAt DESC";
        }

        if (!empty($latitude) && !empty($longitude)) {

            $sql = "SELECT s.*,u.userId,u.firstName,u.lastName,((SELECT COUNT(*) FROM comments c where c.shortlistedId = s.shortlistedId AND c.searchId = s.searchId AND c.deleteFlag !=1) - (SELECT COUNT(*) FROM commentsread r where r.shortlistedId = s.shortlistedId AND r.userId = $loginUserId)) as new_comment_count,
                (SELECT COUNT(*) FROM comments c where c.shortlistedId = s.shortlistedId AND c.searchId = s.searchId AND c.deleteFlag !=1) as total_comments_count
                FROM shortlistedproperty s   
                left join users u on s.userId = u.userId
                where s.searchId= $searchId AND s.userId = $userId AND s.deleteFlag != 1
                $keyword $sortBy LIMIT $offset, $limit";
        }else{

            $sql = "SELECT s.*,u.userId,u.firstName,u.lastName,((SELECT COUNT(*) FROM comments c where c.shortlistedId = s.shortlistedId AND c.searchId = s.searchId AND c.deleteFlag !=1) - (SELECT COUNT(*) FROM commentsread r where r.shortlistedId = s.shortlistedId AND r.userId = $loginUserId)) as new_comment_count,
                (SELECT COUNT(*) FROM comments c where c.shortlistedId = s.shortlistedId AND c.searchId = s.searchId AND c.deleteFlag !=1) as total_comments_count
                FROM shortlistedproperty s   
                left join users u on s.userId = u.userId
                where s.searchId= $searchId AND s.userId = $userId AND s.deleteFlag != 1
                $keyword $sortBy LIMIT $offset, $limit";
        }

        //echo $sql;exit();
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();
        }        
    }
	
	
    /* Delete shortlisted property */
    public function delete_shortlisted_property($post_data){
        $userId = $post_data['userId'];
        $searchId = $post_data['searchId'];
        $shortlistedId = $post_data['shortlistedId'];
        $data = array('deleteFlag' => 1);

        $this->db->where('userId',$userId);
        $this->db->where('searchId',$searchId);   
        $this->db->where('shortlistedId',$shortlistedId);
        $this->db->update('shortlistedproperty',$data); 
        if($this->db->affected_rows()>0){            
             return true;
        }else{
             return false;
           }
    }

    /*Add comments in shortlisted property*/
    public function add_comments($post_data){       
        unset($post_data['token']);
       // print_r($post_data);exit();
        $shortlistedId = $post_data['shortlistedId'] ;
        $userId = $post_data['userId'] ;
        $searchId = $post_data['searchId'] ;
        $comment =  $post_data['comment'];
        $commentFile = array_key_exists("comment",$post_data);        
        if($commentFile){  
            unset($post_data['comment']);          
            $data = array();
            for($i=0;$i<sizeof($comment);$i++)
            {
                $post_data['shortlistedId'] = $shortlistedId;
                $post_data['userId'] = $userId;
                $post_data['searchId'] = $searchId;
                $post_data['roomType'] = $comment[$i]['roomType'];
                $post_data['commentText'] = $comment[$i]['commentText'];
                $post_data['fileType'] = $comment[$i]['fileType'];
                $post_data['commentFile'] = $_POST['commentFile1'.$i];
                
                 $data[] = $post_data;
            }           
        }

        $is_insert = $this->db->insert_batch('comments',$data);
        if($is_insert){
            return true;
        } else {
            return false;
        }
    }

    /* Get login user info and shortlisted property info for push */
    public function get_user_property_info($post_data){
        $userId = $post_data['userId'];
        $shortlistedId = $post_data['shortlistedId'];

        $sql = "SELECT u.firstName,s.* FROM users u ,shortlistedproperty s WHERE u.userId = $userId AND s.shortlistedId = $shortlistedId AND s.deleteFlag !=1";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();
        } 
    }

    /* Get device token of my tribe members for send push notification */
     public function get_tribes_deviceToken($userId,$searchId){ 
        $sql = "SELECT * FROM tribe t  LEFT JOIN users u on t.fromuserId = u.userId OR t.touserId = u.userId WHERE t.searchId = $searchId AND t.status = 'accept' AND t.deleteFlag != 1 AND u.userId != $userId AND u.deviceToken != ''  GROUP BY u.userId";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();
        } 

    }


    /* for upload video in comment */
    public function uploadData($data){
        return $this->db->insert('comments',$data);
    }

    /* Get shortlisted property detail */
    public function shortlisted_property_detail($post_data){
        $userId = $post_data['userId'];
        $searchId = $post_data['searchId'];
        $shortlistedId = $post_data['shortlistedId'];
		$loginUserId =  $post_data['loginUserId']; 

        //$sql = "SELECT s.* FROM shortlistedproperty s where  s.searchId= $searchId AND s.userId = $userId AND s.deleteFlag != 1 AND s.shortlistedId = $shortlistedId";
		/*$sql = "SELECT s.*,sc.criteria1,sc.criteria2,sc.criteria3 FROM  
                 shortlistedproperty s 
                LEFT JOIN searchcriteria sc ON sc.searchId = s.searchId AND sc.userId = s.userID 
                where  s.searchId= $searchId AND s.userId = $userId AND s.deleteFlag != 1 AND s.shortlistedId = $shortlistedId";
		*/
		$sql = "SELECT s.*,sc.criteria1,sc.criteria2,sc.criteria3,r.rateCriteria1,r.rateCriteria2,r.rateCriteria3 FROM  
                 shortlistedproperty s 
                LEFT JOIN searchcriteria sc ON sc.searchId = s.searchId AND sc.userId = s.userID 
				LEFT JOIN rating r ON  r.searchId = s.searchId AND r.shortlistedId = s.shortlistedId AND r.userId = $loginUserId
                where  s.searchId= $searchId AND s.userId = $userId AND s.deleteFlag != 1 AND s.shortlistedId = $shortlistedId";
		//echo $sql;exit();
	   $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array()[0];
        } 
    }

	
    /* Get the shortlisted property comment files */
    public function shortlisted_property_comments_file($post_data){
        $searchId = $post_data['searchId'];
        $shortlistedId = $post_data['shortlistedId'];

        //$sql = "SELECT c.commentFile,c.fileType,c.video_thumbnail FROM comments c WHERE c.searchId = $searchId AND c.shortlistedId = $shortlistedId AND c.deleteFlag !=1 AND c.commentFile !='' AND c.fileType !=''";
        //$sql = "SELECT CONCAT('".site_url()."', c.commentFile) AS commentFile,c.fileType,CONCAT('".site_url()."', c.video_thumbnail) AS video_thumbnail FROM comments c WHERE c.searchId = $searchId AND c.shortlistedId = $shortlistedId AND c.deleteFlag !=1 AND c.commentFile !='' AND c.fileType !=''";
		 $sql = "SELECT
            CASE WHEN c.commentFile IS NOT NULL THEN CONCAT('".site_url()."', c.commentFile)
            ELSE '' END AS commentFile,
            c.fileType,
            CASE WHEN c.video_thumbnail IS NOT NULL THEN CONCAT('".site_url()."', c.video_thumbnail)
            ELSE '' END AS video_thumbnail
            FROM comments c WHERE c.searchId = $searchId AND c.shortlistedId = $shortlistedId AND c.deleteFlag !=1 AND c.commentFile !='' AND c.fileType !=''";
		$record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();
        }
    }
	
	/* Get the shortlisted property comment total count */
    public function shortlisted_property_total_comment_count($post_data){
        $userId = $post_data['userId'];
        $searchId = $post_data['searchId'];
        $shortlistedId = $post_data['shortlistedId'];
        
         $sql = "SELECT * FROM comments c WHERE  c.searchId= $searchId  AND c.deleteFlag != 1 AND c.shortlistedId = $shortlistedId";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->num_rows();
        }
    }

    /* Get the comments of roomtype with unread comments count */
    public function comments_roomType_list($post_data){
        $searchId = $post_data['searchId'];
        $shortlistedId = $post_data['shortlistedId'];
        $loginUserId = $post_data['loginUserId'];


        $sql = "SELECT c.searchId,c.shortlistedId,c.roomType,
                ((SELECT COUNT(*) FROM comments c1 where c1.shortlistedId = c.shortlistedId AND c1.searchId = c.searchId AND c1.deleteFlag !=1 AND c1.roomType = c.roomType) - (SELECT COUNT(*) FROM commentsread r where r.shortlistedId = c.shortlistedId AND r.userId = $loginUserId AND r.roomType = c.roomType)) as unread_comment_count,
				(SELECT COUNT(*) FROM comments c1 where c1.shortlistedId = c.shortlistedId AND c1.searchId = c.searchId AND c1.deleteFlag !=1 AND c1.roomType = c.roomType) as total_comment_count
                FROM comments c WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId GROUP BY c.roomType";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();
        }
    }
	
	/* Get the comments of roomtype all with unread comments count */
    public function comments_roomType_all($post_data){
        $searchId = $post_data['searchId'];
        $shortlistedId = $post_data['shortlistedId'];
        $loginUserId = $post_data['loginUserId'];


        $sql = "SELECT c.searchId,c.shortlistedId,'ALL' as roomType,
                ((SELECT COUNT(*) FROM comments c1 where c1.shortlistedId = c.shortlistedId AND c1.searchId = c.searchId AND c1.deleteFlag !=1) - (SELECT COUNT(*) FROM commentsread r where r.shortlistedId = c.shortlistedId AND r.userId = $loginUserId)) as unread_comment_count,(SELECT COUNT(*) FROM comments c1 where c1.shortlistedId = c.shortlistedId AND c1.searchId = c.searchId AND c1.deleteFlag !=1) as total_comment_count
                FROM comments c WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId GROUP BY c.shortlistedId";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();
        }
    }

    /* Count Get the comments of shortlisted property with perticular roomType*/
    public function count_shortlisted_property_comments($post_data){
        $searchId = $post_data['searchId'];
        $shortlistedId = $post_data['shortlistedId'];
        $roomType = $post_data['roomType'];
        //$roomType = "'".$roomType."'";
		$roomType = $roomType;
        $loginUserId = $post_data['loginUserId'];    
		//echo $roomType;exit();
        if($roomType == 'ALL'){
            $roomType = '';			
        } else{
            $roomType = 'AND c.roomType ="'.$roomType.'"';
        }   

       // $sql = "SELECT c.* FROM comments c WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId AND c.roomType ='".$roomType."' AND c.deleteFlag !=1";
        $sql = "SELECT c.*,cr.userId as readUserId,cr.commentId as readCommentId,cr.shortlistedId as readShortlistedId,cr.roomType as readRoomType,u.firstName,u.lastName FROM comments c 
            LEFT JOIN commentsread cr ON cr.shortlistedId = c.shortlistedId AND cr.userId = $loginUserId AND c.commentId = cr.commentId
            LEFT JOIN users u ON u.userId = c.userId

            WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId $roomType AND c.deleteFlag !=1 ORDER BY c.createdAt DESC";
        //echo $sql;exit();
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->num_rows();
        }
    }

    /* Insert  the comments of shortlisted property with perticular roomType into commentRead table*/
    public function shortlisted_property_comments_read($post_data){
        $searchId = $post_data['searchId'];
        $shortlistedId = $post_data['shortlistedId'];
        $roomType = $post_data['roomType'];
        //$roomType = "'".$roomType."'";
		$roomType = $roomType;
        $loginUserId = $post_data['loginUserId'];
        if($roomType == 'ALL'){
            $roomType = '';
        } else{
           $roomType = 'AND c.roomType ="'.$roomType.'"';
        } 

       // $sql = "SELECT c.* FROM comments c WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId AND c.roomType ='".$roomType."' AND c.deleteFlag !=1";
        $sql = "SELECT c.*,cr.userId as readUserId,cr.commentId as readCommentId,cr.shortlistedId as readShortlistedId,cr.roomType as readRoomType,u.firstName,u.lastName FROM comments c 
            LEFT JOIN commentsread cr ON cr.shortlistedId = c.shortlistedId AND cr.userId = $loginUserId AND c.commentId = cr.commentId
            LEFT JOIN users u ON u.userId = c.userId

            WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId $roomType AND c.deleteFlag !=1";
       // echo $sql;exit();
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            $data = $record->result_array();

            $insert_data = array();
            foreach ($data as $detail) {
                //print_r($detail);exit();
               if(!$detail['readUserId']){
                  $readdata = array(
                                    'userId' => $loginUserId,
                                    'commentId' => $detail['commentId'] ,
                                    'shortlistedId' => $detail['shortlistedId'],
                                    'roomType' => $detail['roomType'],
                                    'readFlag' => 1
                                );
                  $insert_data[] = $readdata;
               }
            }//foreach

            if(count($insert_data)>0){
               return $this->db->insert_batch('commentsread', $insert_data); 
            }else{
                return true;
            }
        }else{
                return false;
            }
    }

    /* Get the comments of shortlisted property with perticular roomType*/
    public function shortlisted_property_comments($post_data){
        $searchId = $post_data['searchId'];
        $shortlistedId = $post_data['shortlistedId'];
        $roomType = $post_data['roomType'];
       // $roomType = "'".$roomType."'";
	    $roomType = $roomType;
        $loginUserId = $post_data['loginUserId'];
        $limit = $post_data['limit'];
        $offset = $post_data['offset'];

        if($roomType == 'ALL'){
            $roomType = '';
        } else{
            $roomType = 'AND c.roomType ="'.$roomType.'"';
        } 

       // $sql = "SELECT c.* FROM comments c WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId AND c.roomType ='".$roomType."' AND c.deleteFlag !=1";
       /* $sql = "SELECT c.*,cr.userId as readUserId,cr.commentId as readCommentId,cr.shortlistedId as readShortlistedId,cr.roomType as readRoomType,u.firstName,u.lastName FROM comments c 
            LEFT JOIN commentsread cr ON cr.shortlistedId = c.shortlistedId AND cr.userId = $loginUserId AND c.commentId = cr.commentId
            LEFT JOIN users u ON u.userId = c.userId

            WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId AND c.roomType = $roomType AND c.deleteFlag !=1 ORDER BY c.createdAt DESC LIMIT $offset,$limit";
        */
        $sql = "SELECT c.commentId,c.searchId,c.userId,c.shortlistedId,c.roomType,c.commentText,c.fileType,
			((SELECT crd.readId FROM urban_database.commentsread crd WHERE crd.shortlistedId = c.shortlistedId AND crd.userId = $loginUserId AND crd.roomType = c.roomType AND crd.commentId = c.commentId)IS NOT NULL) AS comment_read_flag,

            CASE WHEN c.commentFile IS NOT NULL THEN CONCAT('".site_url()."', c.commentFile)
            ELSE '' END AS commentFile,
            CASE WHEN c.video_thumbnail IS NOT NULL THEN CONCAT('".site_url()."', c.video_thumbnail)
            ELSE '' END AS video_thumbnail,

            c.deleteFlag,c.createdAt,cr.userId as readUserId,cr.commentId as readCommentId,cr.shortlistedId as readShortlistedId,cr.roomType as readRoomType,u.firstName,u.lastName FROM comments c 
            LEFT JOIN commentsread cr ON cr.shortlistedId = c.shortlistedId AND cr.userId = $loginUserId AND c.commentId = cr.commentId
            LEFT JOIN users u ON u.userId = c.userId

            WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId $roomType AND c.deleteFlag !=1 ORDER BY c.createdAt DESC LIMIT $offset,$limit";
       
        //echo $sql;exit();
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return$record->result_array();
        }
    }



    /* Get the unread comment count of all room types*/
    public function get_unread_comment_count($post_data){
        $searchId = $post_data['searchId'];
        $shortlistedId = $post_data['shortlistedId'];
        $loginUserId = $post_data['loginUserId'];
        $roomType = $post_data['roomType'];
        $roomType = "'".$roomType."'";
        /*$sql = "SELECT 
                ((SELECT COUNT(*) FROM comments c1 where c1.shortlistedId = c.shortlistedId AND c1.searchId = c.searchId AND c1.deleteFlag !=1 AND c1.roomType = c.roomType) - (SELECT COUNT(*) FROM commentsread r where r.shortlistedId = c.shortlistedId AND r.userId = $loginUserId AND r.roomType = c.roomType)) as unread_comment_count
                FROM comments c WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId AND c.roomType = $roomType GROUP BY c.roomType";
        */
        $sql = "SELECT 
                ((SELECT COUNT(*) FROM comments c1 where c1.shortlistedId = c.shortlistedId AND c1.searchId = c.searchId AND c1.deleteFlag !=1) - (SELECT COUNT(*) FROM commentsread r where r.shortlistedId = c.shortlistedId AND r.userId = $loginUserId)) as unread_comment_count
                FROM comments c WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId GROUP BY c.shortlistedId";
               // echo $sql;exit();
        $record = $this->db->query($sql);        
            return $record->row('unread_comment_count');
    }

    /* Get the comment count of all room types*/
    public function total_comment_count($post_data){
        $searchId = $post_data['searchId'];
        $shortlistedId = $post_data['shortlistedId'];
        $loginUserId = $post_data['loginUserId'];
        $roomType = $post_data['roomType'];
        $roomType = "'".$roomType."'";
        
        $sql = "SELECT 
                (SELECT COUNT(*) FROM comments c1 where c1.shortlistedId = c.shortlistedId AND c1.searchId = c.searchId AND c1.deleteFlag !=1) as total_comment_count
                FROM comments c WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId GROUP BY c.shortlistedId";
               // echo $sql;exit();
        $record = $this->db->query($sql);        
            return $record->row('total_comment_count');
    }

    /* Get the unread comment count of perticular roomType*/
    public function unread_comment_room_type_count($post_data){
        $searchId = $post_data['searchId'];
        $shortlistedId = $post_data['shortlistedId'];
        $loginUserId = $post_data['loginUserId'];
        $roomType = $post_data['roomType'];
        $roomType = "'".$roomType."'";
        $sql = "SELECT 
                ((SELECT COUNT(*) FROM comments c1 where c1.shortlistedId = c.shortlistedId AND c1.searchId = c.searchId AND c1.deleteFlag !=1 AND c1.roomType = c.roomType) - (SELECT COUNT(*) FROM commentsread r where r.shortlistedId = c.shortlistedId AND r.userId = $loginUserId AND r.roomType = c.roomType)) as unread_comment_room_type_count
                FROM comments c WHERE c.shortlistedId = $shortlistedId AND c.searchId =$searchId AND c.roomType = $roomType GROUP BY c.roomType";
        
               // echo $sql;exit();
        $record = $this->db->query($sql);        
            return $record->row('unread_comment_room_type_count');
    }


	
    /* Insert chat message */
    public function add_chat($post_data){
        $searchId = $post_data['searchId'];
        $loginUserId = $post_data['loginUserId'];
        $chatText = $post_data['chatText'];
        $data  = array(
                        'userId'=> $loginUserId,
                        'searchId'=>$searchId,
                        'deleteFlag'=>0,
                        'chatText'=>$chatText
                    );
        return $this->db->insert('chat',$data);
    }

    /* Fetch the perticular serch's chat messages Count*/
    public function count_search_detail_chats($post_data){
        $searchId = $post_data['searchId'];

        $sql = "SELECT c.*,u.firstName,u.lastName FROM chat c,users u 
                WHERE  c.searchId = $searchId  AND c.deleteFlag !=1 AND c.userId = u.userId
                ORDER BY c.createdAt ASC";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->num_rows();
        }else{
            return false;
        }
    }

    /* Fetch the perticular serch's chat messages*/
    public function search_detail_chats($post_data){
        $searchId = $post_data['searchId'];
        $limit = $post_data['limit'];
        $offset = $post_data['offset'];

        $sql = "SELECT c.*,u.firstName,u.lastName FROM chat c,users u 
                WHERE  c.searchId = $searchId  AND c.deleteFlag !=1 AND c.userId = u.userId
                ORDER BY c.createdAt ASC
                LIMIT $offset,$limit";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();
        }else{
            return false;
        }
    }

   /* count my search criteria and tribe search with latest chat list */
   public function count_search_list_latest_chats($post_data){
        $userId = $post_data['loginUserId'];  
        $keyword = $post_data['keyword'];
        $latitude = $post_data['latitude'];
        $longitude = $post_data['longitude'];
       // $filter = $post_data['filter']; //fav_teacher/all
       // $type =  $post_data['type']; // upcoming/past
        

        if(!empty($keyword)){
            $keyword = "AND (s.searchName LIKE '%$keyword%' OR s.idealMovingDate LIKE '%$keyword%' OR s.houseType LIKE '%$keyword%' OR s.priceRange LIKE '%$keyword%' OR s.bedroom LIKE '%$keyword%')";
        }

        if(!empty($latitude) && !empty($longitude))
        {
            $distance_in_km = "( 6371 * acos( cos( radians($latitude) ) * cos( radians( lc.latitude) ) 
                * cos( radians( lc.longitude ) - radians($longitude) ) + 
                    sin( radians($latitude) ) * sin( radians( lc.latitude ) ) ) ) 
                AS distance_in_km";
        }

        if (!empty($latitude) && !empty($longitude)) {

            $sql = "SELECT s.*,c.chatId,c.userId as chatUserId,u.firstName,u.lastName,c.searchId as chatSearchId,c.chatText,c.deleteFlag,c.createdAt as chatDateTime
                 FROM searchcriteria s  
                 LEFT JOIN tribe t ON s.searchId = t.searchId AND t.fromuserId = $userId
                 LEFT JOIN shortlistedproperty p ON s.searchId = p.searchId 
                 LEFT JOIN chat c ON  s.searchId = c.searchId  AND c.createdAt = (SELECT MAX(createdAt) FROM chat WHERE chat.searchId = s.searchId) AND c.searchId IS NOT NULL AND c.deleteFlag !=1
                 LEFT JOIN users u ON c.userId = u.userId 
                 WHERE s.searchId IN (SELECT t.searchId FROM tribe t WHERE (t.touserId =$userId OR t.fromuserId =$userId) AND t.status = 'accept' AND t.deleteFlag != 1) OR s.userId =$userId 
                 $keyword GROUP BY s.searchId 
				 ORDER BY CASE WHEN c.createdAt IS NOT NULL THEN c.createdAt ELSE s.createdAt END DESC";
                    
        }else{
             $sql = "SELECT s.*,c.chatId,c.userId as chatUserId,u.firstName,u.lastName,c.searchId as chatSearchId,c.chatText,c.deleteFlag,c.createdAt as chatDateTime
                 FROM searchcriteria s  
                 LEFT JOIN tribe t ON s.searchId = t.searchId AND t.fromuserId = $userId
                 LEFT JOIN shortlistedproperty p ON s.searchId = p.searchId 
                 LEFT JOIN chat c ON  s.searchId = c.searchId  AND c.createdAt = (SELECT MAX(createdAt) FROM chat WHERE chat.searchId = s.searchId) AND c.searchId IS NOT NULL AND c.deleteFlag !=1
                 LEFT JOIN users u ON c.userId = u.userId 
                 WHERE s.searchId IN (SELECT t.searchId FROM tribe t WHERE (t.touserId =$userId OR t.fromuserId =$userId) AND t.status = 'accept' AND t.deleteFlag != 1) OR s.userId =$userId 
                 $keyword GROUP BY s.searchId 
				 ORDER BY CASE WHEN c.createdAt IS NOT NULL THEN c.createdAt ELSE s.createdAt END DESC";
                    
        }

        $record = $this->db->query($sql);
        return $record->num_rows();
   }

   /* count my search criteria and tribe search with latest chat list */
   public function search_list_latest_chats($post_data){
        $userId = $post_data['loginUserId'];
        $keyword = $post_data['keyword'];
        $latitude = $post_data['latitude'];
        $longitude = $post_data['longitude'];
        $limit = $post_data['limit'];
        $offset = $post_data['offset'];
       // $filter = $post_data['filter']; //fav_teacher/all
       // $type =  $post_data['type']; // upcoming/past
        

        if(!empty($keyword)){
            $keyword = "AND (s.searchName LIKE '%$keyword%' OR s.idealMovingDate LIKE '%$keyword%' OR s.houseType LIKE '%$keyword%' OR s.priceRange LIKE '%$keyword%' OR s.bedroom LIKE '%$keyword%')";
        }

        if(!empty($latitude) && !empty($longitude))
        {
            $distance_in_km = "( 6371 * acos( cos( radians($latitude) ) * cos( radians( lc.latitude) ) 
                * cos( radians( lc.longitude ) - radians($longitude) ) + 
                    sin( radians($latitude) ) * sin( radians( lc.latitude ) ) ) ) 
                AS distance_in_km";
        }

        if (!empty($latitude) && !empty($longitude)) {
                $sql = "SELECT s.*,c.chatId,c.userId as chatUserId,u.firstName,u.lastName,c.searchId as chatSearchId,c.chatText,c.deleteFlag,c.createdAt as chatDateTime
                     FROM searchcriteria s  
                     LEFT JOIN tribe t ON s.searchId = t.searchId AND t.fromuserId = $userId
                     LEFT JOIN shortlistedproperty p ON s.searchId = p.searchId 
                     LEFT JOIN chat c ON  s.searchId = c.searchId  AND c.createdAt = (SELECT MAX(createdAt) FROM chat WHERE chat.searchId = s.searchId) AND c.searchId IS NOT NULL AND c.deleteFlag !=1
                     LEFT JOIN users u ON c.userId = u.userId 
                     WHERE s.searchId IN (SELECT t.searchId FROM tribe t WHERE (t.touserId =$userId OR t.fromuserId =$userId) AND t.status = 'accept' AND t.deleteFlag != 1) OR s.userId =$userId 
                     $keyword GROUP BY s.searchId 
					 ORDER BY CASE WHEN c.createdAt IS NOT NULL THEN c.createdAt ELSE s.createdAt END DESC
					 LIMIT $offset, $limit";
        }else{

                $sql = "SELECT s.*,c.chatId,c.userId as chatUserId,u.firstName,u.lastName,c.searchId as chatSearchId,c.chatText,c.deleteFlag,c.createdAt as chatDateTime
                     FROM searchcriteria s  
                     LEFT JOIN tribe t ON s.searchId = t.searchId AND t.fromuserId = $userId
                     LEFT JOIN shortlistedproperty p ON s.searchId = p.searchId 
                     LEFT JOIN chat c ON  s.searchId = c.searchId  AND c.createdAt = (SELECT MAX(createdAt) FROM chat WHERE chat.searchId = s.searchId) AND c.searchId IS NOT NULL AND c.deleteFlag !=1
                     LEFT JOIN users u ON c.userId = u.userId 
                     WHERE s.searchId IN (SELECT t.searchId FROM tribe t WHERE (t.touserId =$userId OR t.fromuserId =$userId) AND t.status = 'accept' AND t.deleteFlag != 1) OR s.userId =$userId 
                     $keyword GROUP BY s.searchId 
					 ORDER BY CASE WHEN c.createdAt IS NOT NULL THEN c.createdAt ELSE s.createdAt END DESC
					 LIMIT $offset, $limit";
        }
        //echo $sql;exit();
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();
        }        
   }

   /* Connect to my sherpa */
   public function connect_to_my_sherpa($post_data){
        $userId = $post_data['loginUserId'];  
        $date = date("Y-m-d H:i:s");
        $data = array('connectSherpaFlag'=> 1,'sherpaSignedup'=>$date);
        $this->db->where('userId',$userId);
        $record = $this->db->update('users',$data);
        if($record){
            return true;
        }else{
            return false;
        }
   }

   /*function for fetch user and search information*/
    public function get_user_search_info($userId,$searchId){
        $sql = "SELECT u.firstName,s.searchName from users u,searchcriteria s where u.userId =$userId AND s.searchId =$searchId AND s.deleteFlag !=1 AND u.deleteFlag !=1 ";
        $result = $this->db->query($sql);
        return $result->result_array(); 
    }

    /* Get count of my invitation list */
    public function count_MyInvitationList($post_data){
        $userId = $post_data['userId'];
        $keyword = $post_data['keyword'];

        if(!empty($keyword)){
            $keyword = "AND (s.searchName LIKE '%$keyword%' OR u.firstName LIKE '%$keyword%' OR s.searchId LIKE '%$keyword%' OR u.userId LIKE '%$keyword%' OR t.inviteEmail LIKE '%$keyword%' OR u.email LIKE '%$keyword%')";
        }

        $sql = "SELECT t.*,u.userId,u.firstName,u.email,s.searchName FROM tribe t
                LEFT JOIN users u ON t.fromuserId = u.userId AND u.deleteFlag !=1
                LEFT JOIN searchcriteria s on t.searchId = s.searchId 
                WHERE  t.touserId = $userId $keyword AND t.deleteFlag !=1 AND (t.status = '' OR t.status IS NULL) AND  s.deleteFlag !=1 ORDER BY t.createdAt DESC";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->num_rows();
        }else{
            return false;
        }
    }

    /* Get my invitation list */    
    public function MyInvitationList($post_data){
        $userId = $post_data['userId'];
        $keyword = $post_data['keyword'];
        $limit = $post_data['limit'];
        $offset = $post_data['offset'];

        if(!empty($keyword)){
            $keyword = "AND (s.searchName LIKE '%$keyword%' OR u.firstName LIKE '%$keyword%' OR s.searchId LIKE '%$keyword%' OR u.userId LIKE '%$keyword%' OR t.inviteEmail LIKE '%$keyword%' OR u.email LIKE '%$keyword%')";
        }

        $sql = "SELECT t.*,u.userId,u.firstName,u.email,s.searchName FROM tribe t
                LEFT JOIN users u ON t.fromuserId = u.userId AND u.deleteFlag !=1
                LEFT JOIN searchcriteria s on t.searchId = s.searchId 
                WHERE  t.touserId = $userId $keyword AND t.deleteFlag !=1 AND (t.status = '' OR t.status IS NULL) AND  s.deleteFlag !=1 ORDER BY t.createdAt DESC LIMIT $offset,$limit";
        $record = $this->db->query($sql);
        if($record->num_rows()>0){
            return $record->result_array();
        }else{
            return false;
        }
    }
	
	/*Get the device token of tribe member which user accepted invitation*/
	public function tribe_users_device_tokens($post_data){
        $userId =  $post_data['userId'];
        $searchId =  $post_data['searchId'];
        //$status =  $post_data['status'];

        $sql = "SELECT u.userId,u.firstName,u.email,u.deviceToken,u.deviceType,u.deleteFlag  
                FROM users u where (u.userId in (SELECT t.touserId FROM tribe t where t.searchId=$searchId AND t.touserId !=$userId AND t.status = 'accept' AND t.deleteFlag !=1 )
                OR u.userId in (SELECT t.fromuserId FROM tribe t where t.searchId=$searchId AND t.fromuserId!=$userId)) AND u.deleteFlag !=1";
        $record = $this->db->query($sql);           
        if($record->num_rows() > 0)
        {
             return $record->result_array();
        }
    }
	
	/* for get the user,search and property information */    
    public function get_user_serach_property_info($searchId,$shortlistedId){
        $sql = "SELECT s.searchName,u.firstName,u.deviceToken,u.deviceType,sh.propertyName FROM searchcriteria s,users u,shortlistedproperty sh WHERE s.userId = u.userId AND s.searchId = $searchId AND s.searchId = sh.searchId AND sh.shortlistedId = $shortlistedId";
        $record = $this->db->query($sql);           
        if($record->num_rows() > 0)
        {
             return $record->result_array();
        }
    }

    /*Get the device token of tribe member which user accepted invitation*/
    public function rate_tribe_users_device_tokens($post_data){
        $userId =  $post_data['loginUserId'];
        $searchId =  $post_data['searchId'];

        /*$sql = "SELECT t.*,u.userId,u.firstName,u.deviceToken,u.email   
                FROM tribe t 
                LEFT JOIN users u  ON u.userId = t.fromuserId OR u.userId = t.touserId  
                WHERE  t.status = 'accept' AND t.touserId!= $userId AND t.deleteFlag !=1 AND u.deleteFlag !=1  AND t.searchId = $searchId GROUP BY t.touserId
                ";
        */
        $sql = "SELECT u.userId,u.firstName,u.email,u.deviceToken,u.deviceType,u.deleteFlag  
                FROM users u where (u.userId in (SELECT t.touserId FROM tribe t where t.searchId=$searchId AND t.touserId !=$userId AND t.status = 'accept' AND t.deleteFlag !=1 )
                OR u.userId in (SELECT t.fromuserId FROM tribe t where t.searchId=$searchId AND t.fromuserId!=$userId)) AND u.deleteFlag !=1";
        $record = $this->db->query($sql);           
        if($record->num_rows() > 0)
        {
             return $record->result_array();
        }

    }
	
	/*Get the latest search and user information */
    function get_user_latest_search_info($loginUserId){
        $sql = "SELECT u.userId,u.firstName,u.lastName,u.email,u.deleteFlag,u.sherpaSignedup,s.searchId,s.idealMovingDate,s.minPrice,s.maxPrice,s.minBedroom,s.maxBedroom  
            FROM users u 
            LEFT JOIN searchcriteria s ON u.userId = s.userId 
            WHERE u.userId = $loginUserId AND u.deleteFlag != 1 
            GROUP BY s.searchId  ORDER BY s.searchId DESC LIMIT 1;
             ";
        $result = $this->db->query($sql);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return false;
        }
             
    }
	


}?>