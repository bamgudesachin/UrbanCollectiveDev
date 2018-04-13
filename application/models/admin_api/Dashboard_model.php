<?php
class Dashboard_model extends CI_Model {
	function __construct()
	{
		parent::__construct();

	}

	function change_user($user_id)
	{
		
		$this->db->where('user_id',$user_id);
		$this->db->where('status','active');
		//$this->db->join('patients', 'patients.username = users.username');	
		//$this->db->join('patients', 'patients.password = users.password');	
		$q=$this->db->get('users');
		
		if($q->num_rows>0)
		{
			$data=array(
				'id'=>$q->row('user_id'),
				//'patient_id'=>$q->row('patient_id'),
				'username'=>$q->row('username'),
				'role'=>$q->row('role'),
				'main_role'=>'admin',
				'name'=>$q->row('first_name').' '.$q->row('last_name'),				
				'logged_in'=>TRUE
				);
			$this->session->set_userdata($data);
     
			$role = $q->row('role');
			if ($role == 'patient') {
				$data1=array(				
				'patient_id'=>$q->row('patient_id')				
				);
			}else{
				$data1=array(				
				'patient_id'=>''			
				);
			}
			
			$this->session->set_userdata($data1);
			//print_r($this->session->userdata('patient_id'));exit();
			return true;
		}else{
			return false;
		}
	}

	public function get_all_active_doctors()
	{
		$this->db->count_all('users');
		$this->db->where('status','active');
		$this->db->where('role','doctor');
		$q=$this->db->get('users');
		return $q->num_rows();
	}

	public function get_all_deactive_doctors()
	{
		$this->db->count_all('users');
		$this->db->where('status','inactive');
		$this->db->where('role','doctor');
		$q=$this->db->get('users');
		return $q->num_rows();
	}

	public function get_all_active_patients()
	{
		$this->db->count_all('patients');
		$this->db->where('status','active');
		$this->db->where('role','patient');
		$q=$this->db->get('patients');
		return $q->num_rows();
	}
	public function get_all_deactive_patients()
	{
		$this->db->count_all('patients');
		$this->db->where('status','inactive');
		$this->db->where('role','patient');
		$q=$this->db->get('patients');
		return $q->num_rows();
	}

	function get_userchart_data($start_date, $end_date) { 
 		$sql = "SELECT  COUNT(*) total_users, DATE(created) day_date  FROM `patients` WHERE DATE(created) BETWEEN '".$start_date."' AND '".$end_date."' GROUP BY DATE(created) ORDER BY DATE(created) ASC";      
        $query = $this->db->query($sql);
        //print_r($query);exit();
        if ($query) {
        return $query->result();
        }else{
        return NULL;
        }
        
    }

    public function get_turnover_chart_data($start_date, $end_date)
{	


    $sql = "SELECT  COUNT(patients.patient_id) total_likes, DATE(created) day_date  FROM `patients` WHERE DATE(created) BETWEEN '".$start_date."' AND '".$end_date."' GROUP BY DATE(created) ORDER BY DATE(created) ASC";      
        $query = $this->db->query($sql);
        //print_r($query);exit();
        if ($query) {
        return $query->result();
        }else{
        return NULL;
        }
}  

function get_piechart(){

		$sql = 'SELECT count(patient_id) patient,first_name from patients where patient_id = `patient_id` GROUP BY patient_id';
		
        $q=$this->db->query($sql);
		if($q->num_rows()>0)
		{			
			foreach($q->result() as $row)
			{
				$data[]=$row;
			}
			return $data;   
		}
		else{
			return false;
		}	
	}

	function pie_chart()
{
	$query ='SELECT count(patient_id) patient,first_name from patients where patient_id = patient_id GROUP BY patient_id';  
    $q=$this->db->query($query);
    if($q->num_rows()>0)
		{			
			foreach($q->result() as $row)
			{
				$data[]=$row;
			}
			return $data;   
		}
		else{
			return false;
		}	
    
}


}?>