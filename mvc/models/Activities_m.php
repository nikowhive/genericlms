<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activities_m extends MY_Model
{

	protected $_table_name = 'activities';
	protected $_primary_key = 'activitiesID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "activitiesID desc";

	function __construct()
	{
		parent::__construct();
		$this->load->model('systemadmin_m');
        $this->load->model('student_m');
        $this->load->model('teacher_m');
        $this->load->model('parents_m');
        $this->load->model('user_m');
	}

	public function get_activities($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_activities($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_activities($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_activities($array)
	{
		$id = parent::insert($array);
		return $id;
	}

	public function update_activities($data, $id = NULL)
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_activities($id)
	{
		parent::delete($id);
		$tables = array('activitiesmedia', 'activitiescomment', 'activitiesstudent');
		$this->db->where($this->_primary_key, $id);
		$this->db->delete($tables);
	}

	public function getRecentActivities($limit, $start, $schoolYearID, $activityCategoryID = '', $startDate = '', $endDate = '')
	{
		$this->db->select("*, 'Activities' AS type");
		$this->db->from($this->_table_name);
		$this->db->order_by('create_date', 'desc');
		if ($schoolYearID) {
		    $this->db->where('schoolYearID', $schoolYearID);
		}
		if ($activityCategoryID) {
			$this->db->where('activitiescategoryID', $activityCategoryID);
		}
		if ($startDate && $endDate) {
			$this->db->where('create_date >=', $startDate . ' 00:00:00');
			$this->db->where('create_date <=', $endDate . ' 23:59:59');
		}
		if ($limit)
			$this->db->limit($limit, $start);
		$result =	$this->db->get()->result();

		foreach ($result as $index => $data) {
			if($data->usertypeID == 1) {
				$user = $this->systemadmin_m->get_individual_systemadmin($data->userID);
			} else if($data->usertypeID == 2) {
				$user = $this->teacher_m->get_individual_teacher($data->userID);
			} else if($data->usertypeID == 3) {
				$user = $this->student_m->get_individual_student($data->userID);
			} else if($data->usertypeID == 4) {
				$user = $this->parents_m->get_individual_parents($data->userID);
			} else {
				$user = $this->user_m->get_individual_user($data->userID);
			}
			$result[$index]->created_by = $user->name;
			$result[$index]->user_image = $user->photo;
			$result[$index]->name = $user->name;
			$result[$index]->photo = $user->photo;
		}
		return $result;
	}

	public function getLatestActivity()
	{
		$this->db->select("create_date");
		$this->db->from($this->_table_name);
		$this->db->order_by('create_date', 'desc');
		return $this->db->get()->row();
	}

	public function getRows($id = ''){ 
		$this->galleryTbl='activities';
		$this->imgTbl='activitiesmedia';
        $this->db->select("*, (SELECT attachment FROM ".$this->imgTbl." WHERE activitiesID = ".$this->galleryTbl.".activitiesID ORDER BY activitiesmediaID DESC LIMIT 1) as default_image"); 
        $this->db->from($this->galleryTbl); 
        if($id){ 
            $this->db->where('activitiesID', $id); 
            $query  = $this->db->get(); 
            $result = ($query->num_rows() > 0)?$query->row_array():array(); 
             
            if(!empty($result)){ 
                $this->db->select('*'); 
                $this->db->from($this->imgTbl); 
                $this->db->where('activitiesID', $result['activitiesID']); 
                $this->db->order_by('activitiesmediaID', 'desc'); 
                $query  = $this->db->get(); 
                $result2 = ($query->num_rows() > 0)?$query->result_array():array(); 
                $result['images'] = $result2;  
            }  
        }else{ 
            $this->db->order_by('activitiesID', 'desc'); 
            $query  = $this->db->get(); 
            $result = ($query->num_rows() > 0)?$query->result_array():array(); 
        } 
         
        // return fetched data 
        return !empty($result)?$result:false; 
    } 


	public function forMigrations($limit = '', $start = '')
	{
		$this->db->select("*, 'Activities' AS type");
		$this->db->from($this->_table_name);
		$this->db->order_by('create_date', 'desc');
		
		if ($limit)
			$this->db->limit($limit, $start);
		$result =	$this->db->get()->result();

		foreach ($result as $index => $data) {
			if($data->usertypeID == 1) {
				$user = $this->systemadmin_m->get_individual_systemadmin($data->userID);
			} else if($data->usertypeID == 2) {
				$user = $this->teacher_m->get_individual_teacher($data->userID);
			} else if($data->usertypeID == 3) {
				$user = $this->student_m->get_individual_student($data->userID);
			} else if($data->usertypeID == 4) {
				$user = $this->parents_m->get_individual_parents($data->userID);
			} else {
				$user = $this->user_m->get_individual_user($data->userID);
			}
			$result[$index]->created_by = $user->name;
			$result[$index]->user_image = $user->photo;
			$result[$index]->name = $user->name;
			$result[$index]->photo = $user->photo;
		}
		return $result;
	}

}
