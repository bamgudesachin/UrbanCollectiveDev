<?php 
	
$config['notification_add_user_in_tribe'] = array('firstName has shared a search "searchName" with you',"add_tribes");

$config['notification_delete_user_from_tribe'] = array('firstName deleted you from "searchName"',"delete_tribes");

//$config['notification_for_accept_invitation'] = array("name accepted invitation for searchName","accept_invitation");
$config['notification_for_accept_invitation'] = array('name has been added to the search "searchName"',"accept_invitation");

$config['notification_for_decline_invitation'] = array('name has declined invitation for "searchName"',"decline_invitation");

$config['notification_add_comment_on_shortlisted_property'] = array("firstName added a comment to propertyName","shortlisted_property_list");


$config['notification_add_chat_on_search'] = array('firstName sent message on "searchName"',"search_detail_chats");

$config['notification_for_rate_on_property'] = array('name has rated the property "propertyName" in "searchName"',"shortlisted_property_list");

$config['notification_for_shortlist_property'] = array('name has shortlisted the following property-"propertyName"',"shortlisted_property_list");

$config['notification_for_delete_property'] = array('name has been deleted the property "propertyName"',"shortlisted_property_list");





$config['notification_for_attend_event'] = array("name is attending eventName event'","attend_event"); 
$config['notification_for_canceled_event'] = array("name won't be attend","canceled_event"); 

$config['notification_for_post_comment'] = array("name commented on your event","comment_event"); 
$config['notification_for_comment_reply'] = array("name has replied to your comment","comment_event"); 

$config['notification_for_invite_friends'] = array("name has sent you an invite to eventName event","my_invitation_event_list"); 

$config['notification_add_event_in_favourite_category'] = array("name is hosting a new event","fav_event_created"); 


$config['notification_facebook_invitation'] = array("name has sent you an invite to eventName event","invitation_event_list"); 

$config['notification_for_organizer_event_canceled'] = array("The event is canceled by organizer","event_list"); 

////////////////////////////////////////////////////

$gender = array(  ''  => 'Please Select',
					  'Male'    => 'Male',
					  'Female'    => 'Female'
					);
$config['gender'] = $gender; 

/* For studio status*/
$active = array(  ''  => 'Please Select',
                 'Yes'    => 'Yes',
                 'No'    => 'No'
               );

$config['active'] = $active;

$autorenew = array(  ''  => 'Please Select',
                 'No'    => 'No',
                 'Yes'    => 'Yes'
               );
$config['autorenew'] = $autorenew;

/* Add Teacher form validation */
$config['add_teacher'] = array(
               array(
                     'field'   => 'firstname',
                     'label'   => 'First Name',
                     'rules'   => 'trim|required|alpha'
                  ),
               array(
                     'field'   => 'lastname',
                     'label'   => 'Last Name',
                     'rules'   => 'trim|required|alpha'
                  ),   
               array(
                     'field'   => 'email',
                     'label'   => 'Email',
                     'rules'   => 'trim|required|valid_email'
                  ),
               array(
                     'field'   => 'dob',
                     'label'   => 'Date of birth',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'gender',
                     'label'   => 'Gender',
                     'rules'   => 'trim|required|alpha'
                  ),
              /* array(
                     'field'   => 'address_1',
                     'label'   => 'Address 1',
                     'rules'   => 'trim|required|xss_clean|strip_tags'
                  ),
               array(
                     'field'   => 'address_2',
                     'label'   => 'Address 2',
                     'rules'   => 'trim|required|xss_clean|strip_tags'
                  ),
               array(
                     'field'   => 'city',
                     'label'   => 'City',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'state_code',
                     'label'   => 'State code',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'zipcode',
                     'label'   => 'Zipcode',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'country_code',
                     'label'   => 'Country code',
                     'rules'   => 'trim|required'
                  ),
                  */
               array(
                     'field'   => 'phone_no',
                     'label'   => 'Phone no.',
                     'rules'   => 'trim|required|integer|min_length[10]|max_length[10]'
                  ),
               array(
                     'field'   => 'summary',
                     'label'   => 'Summary',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'about',
                     'label'   => 'About',
                     'rules'   => 'trim|required|xss_clean|strip_tags'
                  )
              /* array(
                     'field'   => 'username',
                     'label'   => 'Username',
                     'rules'   => 'trim|required|is_unique[users.username]'
                  ),
               array(
                     'field'   => 'password',
                     'label'   => 'Password',
                     'rules'   => 'trim|required|min_length[6]'
                  ),
               array(
                     'field'   => 'repPassword',
                     'label'   => 'Password Confirmation',
                     'rules'   => 'trim|required|matches[password]|min_length[6]'
                  )
                */
            ); 

/* Update teacher */

$config['update_teacher'] = array(
               array(
                     'field'   => 'teacher_id',
                     'label'   => '',
                     'rules'   => 'trim|required|integer'
                  ),
               array(
                     'field'   => 'firstname',
                     'label'   => 'First Name',
                     'rules'   => 'trim|required|alpha'
                  ),
               array(
                     'field'   => 'lastname',
                     'label'   => 'Last Name',
                     'rules'   => 'trim|required|alpha'
                  ),   
               array(
                     'field'   => 'email',
                     'label'   => 'Email',
                     'rules'   => 'trim|required|valid_email'
                  ),
               array(
                     'field'   => 'dob',
                     'label'   => 'Date of birth',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'gender',
                     'label'   => 'Gender',
                     'rules'   => 'trim|required|alpha'
                  ),
              /* array(
                     'field'   => 'address_1',
                     'label'   => 'Address 1',
                     'rules'   => 'trim|required|xss_clean|strip_tags'
                  ),
               array(
                     'field'   => 'address_2',
                     'label'   => 'Address 2',
                     'rules'   => 'trim|required|xss_clean|strip_tags'
                  ),
               array(
                     'field'   => 'city',
                     'label'   => 'City',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'state_code',
                     'label'   => 'State code',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'zipcode',
                     'label'   => 'Zipcode',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'country_code',
                     'label'   => 'Country code',
                     'rules'   => 'trim|required'
                  ),
                  */
               array(
                     'field'   => 'phone_no',
                     'label'   => 'Phone no.',
                     'rules'   => 'trim|required|integer|min_length[10]|max_length[10]'
                  ),
               array(
                     'field'   => 'summary',
                     'label'   => 'Summary',
                     'rules'   => 'trim|required|min_length[100]|max_length[250]'
                  ),
               array(
                     'field'   => 'about',
                     'label'   => 'About',
                     'rules'   => 'trim|required|xss_clean|strip_tags|min_length[500]|max_length[2200]'
                  )
              /* array(
                     'field'   => 'username',
                     'label'   => 'Username',
                     'rules'   => 'trim|required|is_unique[users.username]'
                  ),
               array(
                     'field'   => 'password',
                     'label'   => 'Password',
                     'rules'   => 'trim|required|min_length[6]'
                  ),
               array(
                     'field'   => 'repPassword',
                     'label'   => 'Password Confirmation',
                     'rules'   => 'trim|required|matches[password]|min_length[6]'
                  )
                */
            ); 

/* Add class form validation */
$config['add_class'] = array(
               array(
                     'field'   => 'level_id',
                     'label'   => 'Level',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'teacher_id',
                     'label'   => 'Teacher',
                     'rules'   => 'required'
                  ),   
               array(
                     'field'   => 'style_id',
                     'label'   => 'Style',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'location_id',
                     'label'   => 'Location',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'date',
                     'label'   => 'Date',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'start_time',
                     'label'   => 'Start time',
                     'rules'   => 'trim|required|xss_clean|strip_tags'
                  ),
               array(
                     'field'   => 'duration',
                     'label'   => 'Duration',
                     'rules'   => 'required'
                  )              
            ); 

$config['update_class'] = array(
               array(
                     'field'   => 'class_id',
                     'label'   => '',
                     'rules'   => 'trim|required|integer'
                  ),
               array(
                     'field'   => 'level_id',
                     'label'   => 'Level',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'teacher_id',
                     'label'   => 'Teacher',
                     'rules'   => 'required'
                  ),   
               array(
                     'field'   => 'style_id',
                     'label'   => 'Style',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'location_id',
                     'label'   => 'Location',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'date',
                     'label'   => 'Date',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'start_time',
                     'label'   => 'Start time',
                     'rules'   => 'trim|required|xss_clean|strip_tags'
                  ),
               array(
                     'field'   => 'duration',
                     'label'   => 'Duration',
                     'rules'   => 'required'
                  )              
            ); 

/* Add Locations */
$config['add_location'] = array(
               array(
                     'field'   => 'name1',
                     'label'   => 'Name 1',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'name2',
                     'label'   => 'Name 2',
                     'rules'   => 'trim'
                  ), 
               array(
                     'field'   => 'address_1',
                     'label'   => 'Address 1',
                     'rules'   => 'trim|required|xss_clean|strip_tags|callback_check_latLong'
                  ),
               array(
                     'field'   => 'address_2',
                     'label'   => 'Address 2',
                     'rules'   => 'trim|xss_clean|strip_tags'
                  ),
               array(
                     'field'   => 'city',
                     'label'   => 'City',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'state_code',
                     'label'   => 'State',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'zipcode',
                     'label'   => 'Zipcode',
                     'rules'   => 'trim|required'
                  ),
               /*array(
                     'field'   => 'country_code',
                     'label'   => 'Country code',
                     'rules'   => 'trim|required'
                  ), 
                  */                 
               array(
                     'field'   => 'capacity',
                     'label'   => 'Capacity',
                     'rules'   => 'trim|required|integer'
                  )
            ); 

/* Update teacher */

$config['update_location'] = array(
               array(
                     'field'   => 'location_id',
                     'label'   => '',
                     'rules'   => 'trim|required|integer'
                  ),
               array(
                     'field'   => 'name1',
                     'label'   => 'Name 1',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'name2',
                     'label'   => 'Name 2',
                     'rules'   => 'trim'
                  ), 
               array(
                     'field'   => 'address_1',
                     'label'   => 'Address 1',
                     'rules'   => 'trim|required|xss_clean|strip_tags|callback_check_latLong'
                  ),
               array(
                     'field'   => 'address_2',
                     'label'   => 'Address 2',
                     'rules'   => 'trim|xss_clean|strip_tags'
                  ),
               array(
                     'field'   => 'city',
                     'label'   => 'City',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'state_code',
                     'label'   => 'State',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'zipcode',
                     'label'   => 'Zipcode',
                     'rules'   => 'trim|required'
                  ),
               /*array(
                     'field'   => 'country_code',
                     'label'   => 'Country code',
                     'rules'   => 'trim|required'
                  ), 
                  */                 
               array(
                     'field'   => 'capacity',
                     'label'   => 'Capacity',
                     'rules'   => 'trim|required|integer'
                  )
            ); 

/* Add Style */
$config['add_style'] = array(
               array(
                     'field'   => 'name',
                     'label'   => 'Name',
                     'rules'   => 'trim|required'
                  )
            ); 

/* Update Style */

$config['update_style'] = array(
               array(
                     'field'   => 'style_id',
                     'label'   => '',
                     'rules'   => 'trim|required|integer'
                  ),
               array(
                     'field'   => 'name',
                     'label'   => 'Name',
                     'rules'   => 'trim|required'
                  )
            ); 

/* Add Level */
$config['add_level'] = array(
               array(
                     'field'   => 'name',
                     'label'   => 'Name',
                     'rules'   => 'trim|required'
                  )
            ); 

/* Update Level */

$config['update_level'] = array(
               array(
                     'field'   => 'level_id',
                     'label'   => '',
                     'rules'   => 'trim|required|integer'
                  ),
               array(
                     'field'   => 'name',
                     'label'   => 'Name',
                     'rules'   => 'trim|required'
                  )
            ); 

/* Add Package form validation */
$config['add_package'] = array(
               array(
                     'field'   => 'classes',
                     'label'   => 'No. Classes',
                     'rules'   => 'trim|required|integer'
                  ),
               array(
                     'field'   => 'validity',
                     'label'   => 'Validity',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'autorenew',
                     'label'   => 'Autorenew',
                     'rules'   => 'trim|required|alpha'
                  ),
               array(
                     'field'   => 'price',
                     'label'   => 'price',
                     'rules'   => 'trim|required'
                  )
            ); 

/* Update Package */

$config['update_package'] = array(
               array(
                     'field'   => 'id',
                     'label'   => '',
                     'rules'   => 'trim|required|integer'
                  ),
                array(
                     'field'   => 'classes',
                     'label'   => 'No. Classes',
                     'rules'   => 'trim|required|integer'
                  ),
               array(
                     'field'   => 'validity',
                     'label'   => 'Validity',
                     'rules'   => 'trim|required'
                  ),
               array(
                     'field'   => 'autorenew',
                     'label'   => 'Autorenew',
                     'rules'   => 'trim|required|alpha'
                  ),
               array(
                     'field'   => 'price',
                     'label'   => 'price',
                     'rules'   => 'trim|required'
                  )
            ); 

   /* Add Studio */
   $config['add_studio'] = array(
               array(
                     'field'   => 'name',
                     'label'   => 'Name',
                     'rules'   => 'trim|required'
                  ),   
               array(
                     'field'   => 'email',
                     'label'   => 'Email',
                     'rules'   => 'trim|required|valid_email|callback_email_check'
                  )
              
            ); 

/* Update Studio */

$config['update_studio'] = array(
               array(
                     'field'   => 'admin_id',
                     'label'   => '',
                     'rules'   => 'trim|required|integer'
                  ),
               array(
                     'field'   => 'name',
                     'label'   => 'Name',
                     'rules'   => 'trim|required'
                  ),   
               array(
                     'field'   => 'email',
                     'label'   => 'Email',
                     'rules'   => 'trim|required|valid_email'
                  ),
               array(
                     'field'   => 'active',
                     'label'   => 'Active',
                     'rules'   => 'trim|required'
                  )
             
            ); 

/* Add users*/
$config['add_user'] = array(
               array(
                     'field'   => 'firstName',
                     'label'   => 'First Name',
                     'rules'   => 'required|min_length[3]'
                  ),
               array(
                     'field'   => 'lastName',
                     'label'   => 'Last Name',
                     'rules'   => 'required|min_length[3]'
                  ),   
               array(
                     'field'   => 'email',
                     'label'   => 'Email',
                     'rules'   => 'required|is_unique[users.email]'
                  ),
               array(
                     'field'   => 'password',
                     'label'   => 'Password',
                     'rules'   => 'required'
                  ),
               array(
                     'field'   => 'age',
                     'label'   => 'Age',
                     'rules'   => 'integer'
                  )            
            ); 

 $config['update_user'] = array(
               array(
                     'field'   => 'firstName',
                     'label'   => 'First Name',
                     'rules'   => 'required|min_length[3]'
                  ),
               array(
                     'field'   => 'lastName',
                     'label'   => 'Last Name',
                     'rules'   => 'required|min_length[3]'
                  ),   
               array(
                     'field'   => 'email',
                     'label'   => 'Email',
                     'rules'   => 'required'
                  ) ,
               array(
                     'field'   => 'age',
                     'label'   => 'Age',
                     'rules'   => 'integer'
                  )      
            ); 


?>