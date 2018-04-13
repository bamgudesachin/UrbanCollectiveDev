<?php

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_user_list($record_per_page, $searchtext,$startDate,$endDate,$criteria, $column_name, $order_by, $pageno) {

        if(!empty($startDate) && !empty($endDate) &&!empty($criteria)){
            $startDate = $startDate." 00:00:00";
            $endDate = $endDate." 23:59:59";
            if($criteria == 'sherpaSignedup'){
                $new_criteria = "and (connectSherpaFlag = 1 and $criteria >= '$startDate' and $criteria <= '$endDate')";
            }else{
                $new_criteria = "and $criteria >= '$startDate' and $criteria <= '$endDate'";
            }
            
        }else{
            $new_criteria = "";
        }
        //echo $record_per_page;exit;
        if($column_name == 'minPrice' || $column_name == 'maxPrice' ||$column_name == 'idealMovingDate'){
            $column_name = "sr.$column_name";
        }else if($column_name == 'no_search' || $column_name == 'no_tribe'){
            $column_name = "$column_name";
        }
        else{
            $column_name = "u.$column_name";
        }

        $offset = ($pageno - 1) * $record_per_page;
        if ($searchtext != 'nosearch' && $searchtext != '') {
            $keyword = "where (u.userId LIKE '%$searchtext%' OR u.firstName LIKE '%$searchtext%' OR u.lastName LIKE '%$searchtext%' OR u.email LIKE '%$searchtext%' OR u.city LIKE '%$searchtext%' OR u.age LIKE '%$searchtext%' OR u.lastLogin LIKE '%$searchtext%' OR u.connectSherpaFlag LIKE '%$searchtext%')";
        } else {
            $keyword = "";
        }
        if ($order_by) {
            $sort = "order by $column_name $order_by";
        } else {
            $sort = "order by u.createdAt DESC";
        }

        if($keyword){
            $deleted = "and u.deleteFlag !=1";
        }else{
            $deleted = "where u.deleteFlag !=1";
        }

        $query = "SELECT u.*,sr.*,
                (SELECT COUNT(*) FROM searchcriteria s WHERE u.userId = s.userId AND s.deleteFlag !=1) as no_search,
                (SELECT COUNT(DISTINCT(inviteEmail)) FROM tribe t WHERE u.userId = t.fromuserId AND t.deleteFlag !=1 AND t.status = 'accept') as no_tribe    
                FROM users u 
                 LEFT JOIN  
                 (SELECT sc.userId as search_userId,sc.idealMovingDate,sc.minPrice,sc.maxPrice 
                    FROM searchcriteria sc 
                    WHERE sc.deleteFlag !=1 ORDER BY sc.createdAt DESC
                 ) AS sr ON  u.userId = search_userId
                 $keyword $deleted $new_criteria GROUP BY u.userId $sort LIMIT $offset, $record_per_page";

        /*$query = "select * from users u $keyword $deleted $sort LIMIT $offset, $record_per_page";
        */
       // echo $query;exit();
        $record = $this->db->query($query);
        if ($record->num_rows() > 0) {
            return $record->result_array();
            //print_r($record->result_array());exit();
        }
    }

    public function get_user_list_count($record_per_page, $searchtext,$startDate,$endDate,$criteria, $column_name, $order_by, $pageno) {

        if(!empty($startDate) && !empty($endDate) &&!empty($criteria)){
            $startDate = $startDate." 00:00:00";
            $endDate = $endDate." 23:59:59";
            if($criteria == 'sherpaSignedup'){
                $new_criteria = "and (connectSherpaFlag = 1 and $criteria >= '$startDate' and $criteria <= '$endDate')";
            }else{
                $new_criteria = "and $criteria >= '$startDate' and $criteria <= '$endDate'";
            }
        }else{
            $new_criteria = "";
        }


        if($column_name == 'minPrice' || $column_name == 'maxPrice' ||$column_name == 'idealMovingDate'){
            $column_name = "sr.$column_name";
        }else if($column_name == 'no_search' || $column_name == 'no_tribe'){
            $column_name = "$column_name";
        }else{
            $column_name = "u.$column_name";
        }

        $offset = ($pageno - 1) * $record_per_page;
        if ($searchtext != 'nosearch' && $searchtext != '') {
            $keyword = "where (u.userId LIKE '%$searchtext%' OR u.firstName LIKE '%$searchtext%' OR u.lastName LIKE '%$searchtext%' OR u.email LIKE '%$searchtext%' OR u.city LIKE '%$searchtext%' OR u.age LIKE '%$searchtext%' OR u.lastLogin LIKE '%$searchtext%' OR u.connectSherpaFlag LIKE '%$searchtext%')";
        } else {
            $keyword = "";
        }
        if ($order_by) {
            $sort = "order by $column_name $order_by";
        } else {
            $sort = "order by u.createdAt DESC";
        }

        if($keyword){
            $deleted = "and u.deleteFlag !=1";
        }else{
            $deleted = "where u.deleteFlag !=1";
        }
        
        $query = "SELECT u.*,sr.*,
                (SELECT COUNT(*) FROM searchcriteria s WHERE u.userId = s.userId AND s.deleteFlag !=1) as no_search,
                (SELECT COUNT(DISTINCT(inviteEmail)) FROM tribe t WHERE u.userId = t.fromuserId AND t.deleteFlag !=1 AND t.status = 'accept') as no_tribe    
                FROM users u 
                 LEFT JOIN  
                 (SELECT sc.userId as search_userId,sc.idealMovingDate,sc.minPrice,sc.maxPrice 
                    FROM searchcriteria sc 
                    WHERE sc.deleteFlag !=1 ORDER BY sc.createdAt DESC
                 ) AS sr ON  u.userId = search_userId
                 $keyword $deleted $new_criteria GROUP BY u.userId $sort";

       /* $query = "select * from users u $keyword $deleted $sort";*/
       //echo $query;exit();
        $record = $this->db->query($query);
        if ($record->num_rows() > 0) {
            return $record->num_rows();
        }
    }

    public function insert_user($data) {
        return $this->db->insert('users', $data);
    }

    public function get_user($userId) {
        $query = "SELECT * from users where userId=$userId and deleteFlag !=1";
        $record = $this->db->query($query);
        if ($record->num_rows() > 0) {
            return $record->result_array()[0];
        }
    }
    public function update_user($data) {
        $this->db->where('userId',  $this->input->post('userId'));        
        return $this->db->update('users', $data);        
    }
    public function is_email_unique($email,$userId){
        $query = "SELECT * from users where email='$email'";
        $record = $this->db->query($query);
        $data = $record->result_array();
        if($data){
            if($data[0]['userId']==$this->input->post('userId') && ($record->num_rows() == 1 || $record->num_rows()==0)) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    public function download_all_user_data(){
        $query = "SELECT * from users where deleteFlag !=1";
        $record = $this->db->query($query);
        return $record->result_array();
    }
  /*  public function download_user_sherpa_interest(){
        $query = "SELECT * from users where connectSherpaFlag=1 and deleteFlag !=1";
        $record = $this->db->query($query);
        return $record->result_array();
    }
  */
    public function get_sherpa_interested_users(){
        $query = "SELECT deviceToken,deviceType from users where connectSherpaFlag=1 and deleteFlag !=1";
        $record = $this->db->query($query);
        return $record->result_array();
    }

    public function download_user_data($record_per_page,$searchtext,$startDate,$endDate,$criteria,$column_name,$order_by) {

        if(!empty($startDate) && !empty($endDate) && !empty($criteria)){
            
            $startDate = $startDate." 00:00:00";
            $endDate = $endDate." 23:59:59";
            if($criteria == 'sherpaSignedup'){
                $new_criteria = "and (connectSherpaFlag = 1 and $criteria >= '$startDate' and $criteria <= '$endDate')";
            }else{
                $new_criteria = "and $criteria >= '$startDate' and $criteria <= '$endDate'";
            }
        }else{
            $new_criteria = "";
        }
        //echo $record_per_page;exit;
        if($column_name == 'minPrice' || $column_name == 'maxPrice' ||$column_name == 'idealMovingDate'){
            $column_name = "sr.$column_name";
        }else if($column_name == 'no_search' || $column_name == 'no_tribe'){
            $column_name = "$column_name";
        }
        else{
            $column_name = "u.$column_name";
        }

        //$offset = ($pageno - 1) * $record_per_page;
        if ($searchtext != 'nosearch' && $searchtext != '') {
            $keyword = "where (u.userId LIKE '%$searchtext%' OR u.firstName LIKE '%$searchtext%' OR u.lastName LIKE '%$searchtext%' OR u.email LIKE '%$searchtext%' OR u.city LIKE '%$searchtext%' OR u.age LIKE '%$searchtext%' OR u.lastLogin LIKE '%$searchtext%' OR u.connectSherpaFlag LIKE '%$searchtext%')";
        } else {
            $keyword = "";
        }
        if ($order_by) {
            $sort = "order by $column_name $order_by";
        } else {
            $sort = "order by u.createdAt DESC";
        }

        if($keyword){
            $deleted = "and u.deleteFlag !=1";
        }else{
            $deleted = "where u.deleteFlag !=1";
        }

        $query = "SELECT u.*,sr.*,
                (SELECT COUNT(*) FROM searchcriteria s WHERE u.userId = s.userId AND s.deleteFlag !=1) as no_search,
                (SELECT COUNT(DISTINCT(inviteEmail)) FROM tribe t WHERE u.userId = t.fromuserId AND t.deleteFlag !=1 AND t.status = 'accept') as no_tribe    
                FROM users u 
                 LEFT JOIN  
                 (SELECT sc.userId as search_userId,sc.idealMovingDate,sc.minPrice,sc.maxPrice 
                    FROM searchcriteria sc 
                    WHERE sc.deleteFlag !=1 ORDER BY sc.createdAt DESC
                 ) AS sr ON  u.userId = search_userId
                 $keyword $deleted $new_criteria GROUP BY u.userId $sort";

        /*$query = "select * from users u $keyword $deleted $sort LIMIT $offset, $record_per_page";
        */
        //echo $query;exit();
        $record = $this->db->query($query);
        if ($record->num_rows() > 0) {
            return $record->result_array();
            //print_r($record->result_array());exit();
        }
    }

    public function download_user_sherpa_interest($record_per_page,$searchtext,$startDate,$endDate,$criteria,$column_name,$order_by) {

        if(!empty($startDate) && !empty($endDate) && !empty($criteria)){
            
            $startDate = $startDate." 00:00:00";
            $endDate = $endDate." 23:59:59";
            if($criteria == 'sherpaSignedup'){
                $new_criteria = "and (connectSherpaFlag = 1 and $criteria >= '$startDate' and $criteria <= '$endDate')";
            }else{
                $new_criteria = "and $criteria >= '$startDate' and $criteria <= '$endDate'";
            }
        }else{
            $new_criteria = "";
        }
        //echo $record_per_page;exit;
        if($column_name == 'minPrice' || $column_name == 'maxPrice' ||$column_name == 'idealMovingDate'){
            $column_name = "sr.$column_name";
        }else if($column_name == 'no_search' || $column_name == 'no_tribe'){
            $column_name = "$column_name";
        }
        else{
            $column_name = "u.$column_name";
        }

        //$offset = ($pageno - 1) * $record_per_page;
        if ($searchtext != 'nosearch' && $searchtext != '') {
            $keyword = "where (u.userId LIKE '%$searchtext%' OR u.firstName LIKE '%$searchtext%' OR u.lastName LIKE '%$searchtext%' OR u.email LIKE '%$searchtext%' OR u.city LIKE '%$searchtext%' OR u.age LIKE '%$searchtext%' OR u.lastLogin LIKE '%$searchtext%' OR u.connectSherpaFlag LIKE '%$searchtext%')";
        } else {
            $keyword = "";
        }
        if ($order_by) {
            $sort = "order by $column_name $order_by";
        } else {
            $sort = "order by u.createdAt DESC";
        }

        if($keyword){
            $deleted = "and u.deleteFlag !=1 and u.connectSherpaFlag=1";
        }else{
            $deleted = "where u.deleteFlag !=1 and u.connectSherpaFlag=1";
        }

        $query = "SELECT u.*,sr.*,
                (SELECT COUNT(*) FROM searchcriteria s WHERE u.userId = s.userId AND s.deleteFlag !=1) as no_search,
                (SELECT COUNT(DISTINCT(inviteEmail)) FROM tribe t WHERE u.userId = t.fromuserId AND t.deleteFlag !=1 AND t.status = 'accept') as no_tribe    
                FROM users u 
                 LEFT JOIN  
                 (SELECT sc.userId as search_userId,sc.idealMovingDate,sc.minPrice,sc.maxPrice 
                    FROM searchcriteria sc 
                    WHERE sc.deleteFlag !=1 ORDER BY sc.createdAt DESC
                 ) AS sr ON  u.userId = search_userId
                 $keyword $deleted $new_criteria GROUP BY u.userId $sort";

        /*$query = "select * from users u $keyword $deleted $sort LIMIT $offset, $record_per_page";
        */
       // echo $query;exit();
        $record = $this->db->query($query);
        if ($record->num_rows() > 0) {
            return $record->result_array();
            //print_r($record->result_array());exit();
        }
    }

    public function update_test_flag($userId){
        //echo $userId;exit();
        $query = "SELECT userId,testFlag from users where userId=$userId";
        $record = $this->db->query($query);
        if($record->num_rows()>0){
            $testFlag = $record->row()->testFlag;
            if($testFlag == 'TestUser'){
                $data = array('testFlag'=>NULL);
                $this->db->where('userId',  $userId);        
                $this->db->update('users', $data);
                return "Normal User";
            }else{
                $data = array('testFlag'=>'TestUser');
                $this->db->where('userId',  $userId);        
                $this->db->update('users', $data);
                return "Test User";
            }
        }else{
            return false;
        }
        
    }

    /* Delete user */
    public function delete_user($userId){    
        //echo $userId;exit();    
        /* Get the search created by user */
        $search_ids = array();
        $searches = "SELECT GROUP_CONCAT(searchId) AS search_id FROM searchcriteria WHERE userId =$userId";
        $record = $this->db->query($searches);
        if($record->num_rows()>0)
        {
           foreach($record->result_array() as $row)
           {
            $search_ids[] = $row;
           }
          
           $str_search_id = implode(',', array_map(function($el){ return $el['search_id']; }, $search_ids));
            $str = "'".$str_search_id."'";
            $search_id =  str_replace(",","','",$str);
           
            /* Delete commute associated with users search*/
            $commute_delete = "DELETE FROM commute WHERE searchId IN($search_id) AND userId = $userId";            
            $record_commute = $this->db->query($commute_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete chats associated with users search */
            $chat_delete = "DELETE FROM chat WHERE searchId IN($search_id) AND userId = $userId";
            $record_chat = $this->db->query($chat_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete tribe associated with user search*/
            $tribe_delete = "DELETE FROM tribe WHERE searchId IN($search_id) AND fromuserId = $userId";
            $record_tribe = $this->db->query($tribe_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete shortlisted property associated with user search*/
            $shortlistedproperty_delete = "DELETE FROM shortlistedproperty WHERE searchId IN($search_id) AND userId = $userId";
            $record_property = $this->db->query($shortlistedproperty_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete comments associated with user search*/
            $comments_delete = "DELETE FROM comments WHERE searchId IN($search_id) AND userId = $userId";
            $record_comments = $this->db->query($comments_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete commentsread associated with user search*/
            $commentsread_delete = "DELETE FROM commentsread WHERE userId = $userId";
            $record_commentsread = $this->db->query($commentsread_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete rating associated with user search*/
            $rating_delete = "DELETE FROM rating WHERE searchId IN($search_id) AND userId = $userId";
            $record_rating = $this->db->query($rating_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete searchcriteria associated with user*/
            $searchcriteria_delete = "DELETE FROM searchcriteria WHERE  userId = $userId";
            $record_searchcriteria = $this->db->query($searchcriteria_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Dlete user */
            $users_delete = "DELETE FROM users WHERE  userId = $userId";
            $record_users = $this->db->query($users_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            return $deleted;
        }else{
            /* Dlete user */
            $users_delete = "DELETE FROM users WHERE  userId = $userId";
            $record_users = $this->db->query($users_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }
            return $deleted;
        }
        
    }


    /* Delete multiple users */
    public function deleteMultipleUsers($data)
    {
        $search_ids = array();
        $userId = implode(",",$data);
        $searches = "SELECT GROUP_CONCAT(searchId) AS search_id FROM searchcriteria WHERE userId IN($userId)";
        $record = $this->db->query($searches);
        if($record->num_rows()>0)
        {
           foreach($record->result_array() as $row)
           {
            $search_ids[] = $row;
           }
          
           $str_search_id = implode(',', array_map(function($el){ return $el['search_id']; }, $search_ids));
            $str = "'".$str_search_id."'";
            $search_id =  str_replace(",","','",$str);
           
            /* Delete commute associated with users search*/
            $commute_delete = "DELETE FROM commute WHERE searchId IN($search_id) AND userId IN($userId)";            
            $record_commute = $this->db->query($commute_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete chats associated with users search */
            $chat_delete = "DELETE FROM chat WHERE searchId IN($search_id) AND userId IN($userId)";
            $record_chat = $this->db->query($chat_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete tribe associated with user search*/
            $tribe_delete = "DELETE FROM tribe WHERE searchId IN($search_id) AND fromuserId IN($userId)";
            $record_tribe = $this->db->query($tribe_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete shortlisted property associated with user search*/
            $shortlistedproperty_delete = "DELETE FROM shortlistedproperty WHERE searchId IN($search_id) AND userId IN($userId)";
            $record_property = $this->db->query($shortlistedproperty_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete comments associated with user search*/
            $comments_delete = "DELETE FROM comments WHERE searchId IN($search_id) AND userId IN($userId)";
            $record_comments = $this->db->query($comments_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete commentsread associated with user search*/
            $commentsread_delete = "DELETE FROM commentsread WHERE userId IN($userId)";
            $record_commentsread = $this->db->query($commentsread_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete rating associated with user search*/
            $rating_delete = "DELETE FROM rating WHERE searchId IN($search_id) AND userId IN($userId)";
            $record_rating = $this->db->query($rating_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Delete searchcriteria associated with user*/
            $searchcriteria_delete = "DELETE FROM searchcriteria WHERE  userId IN($userId)";
            $record_searchcriteria = $this->db->query($searchcriteria_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            /* Dlete user */
            $users_delete = "DELETE FROM users WHERE  userId IN($userId)";
            $record_users = $this->db->query($users_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }

            return $deleted;
        }else{
            /* Dlete user */
            $users_delete = "DELETE FROM users WHERE  userId IN($userId)";
            $record_users = $this->db->query($users_delete);
            if($this->db->affected_rows()>0){
                $deleted = true;
            }else{
                $deleted = false;
            }
            return $deleted;
        }

    }//

    /* make user as test or normal users*/
    
    public function testMultipleUsers($data)
    {
        $search_ids = array();
        $userId = implode(",",$data);
        $query = "SELECT userId,testFlag from users where userId IN($userId)";        

        $record = $this->db->query($query);
        if($record->num_rows()>0){
            $result = "UPDATE users SET testFlag = 'TestUser' WHERE userId IN($userId)";
            $record_update = $this->db->query($result);
            if($this->db->affected_rows()>0){
                $updated = true;
            }else{
                $updated = false;
            }
            
            return $updated;
        }else{
            return false;
        }

    }//

    /* Make Real users */
    public function realMultipleUsers($data)
    {
        $search_ids = array();
        $userId = implode(",",$data);
        $query = "SELECT userId,testFlag from users where userId IN($userId)";        

        $record = $this->db->query($query);
        if($record->num_rows()>0){
            $result = "UPDATE users SET testFlag = NULL WHERE userId IN($userId)";
            $record_update = $this->db->query($result);
            if($this->db->affected_rows()>0){
                $updated = true;
            }else{
                $updated = false;
            }
            
            return $updated;
        }else{
            return false;
        }
    }//

}

?>