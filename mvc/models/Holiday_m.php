<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holiday_m extends MY_Model {

	protected $_table_name = 'holiday';
	protected $_primary_key = 'holidayID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "fdate asc";

	function __construct() {
		parent::__construct();
        $this->load->model('systemadmin_m');
        $this->load->model('student_m');
        $this->load->model('teacher_m');
        $this->load->model('parents_m');
        $this->load->model('user_m');
	}

	function get_holiday($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_holiday($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_holiday($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_holiday($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_holiday($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_holiday($id){
		parent::delete($id);
		$tables = array('holiday_media', 'holiday_comment');
		$this->db->where($this->_primary_key, $id);
		$this->db->delete($tables);
	}

    public function getRecentHolidays($limit, $start, $schoolYearID,$startDate = '',$endDate = '',$isAdmin = false) {
        $this->db->select("*, 'Holiday' AS type, holiday.photo as holidayPhoto");
        $this->db->from($this->_table_name);
        
		if(!$isAdmin){
			$this->db->where('published', 1);
		}
        if($schoolYearID)
            $this->db->where('schoolYearID', $schoolYearID);
		if($startDate && $endDate){
			$this->db->where('published_date >', $startDate);
			$this->db->where('published_date <=', $endDate);
		}	
        if($limit)
            $this->db->limit($limit, $start);
		$this->db->order_by('published_date', 'desc');	
        $result = $this->db->get()->result();
        foreach($result as $index => $data) {
			if($data->create_usertypeID == 1) {
				$user = $this->systemadmin_m->get_individual_systemadmin($data->create_userID);
			} else if($data->create_usertypeID == 2) {
				$user = $this->teacher_m->get_individual_teacher($data->create_userID);
			} else if($data->create_usertypeID == 3) {
				$user = $this->student_m->get_individual_student($data->create_userID);
			} else if($data->create_usertypeID == 4) {
				$user = $this->parents_m->get_individual_parents($data->create_userID);
			} else {
				$user = $this->user_m->get_individual_user($data->create_userID);
			}
            $result[$index]->created_by = $user->name;
            $result[$index]->user_image = $user->photo;
			$result[$index]->user_photo = $user->photo;
			$result[$index]->name = $user->name;
            $result[$index]->photo = $user->photo;
        }
        return $result;
    }

	public function get_holiday_by_date($date) {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		$this->db->or_where('fdate', $date);
		$this->db->or_where('tdate', $date);
		return $this->db->get()->row();
	}

    public function getCount() {
        $this->db->select("count(*) as count");
        $this->db->from($this->_table_name);
        return $this->db->get()->row()->count;
    }

	public function getLatestHoliday(){

		$this->db->select("holiday.published_date");
		$this->db->from($this->_table_name);
		$this->db->order_by('published_date', 'desc');
		return $this->db->get()->row();
    }

    public function getHolidaysForJob($schoolYearID = '',$current_date = ''){
		$this->db->select("*");
		$this->db->from($this->_table_name);
		if($schoolYearID){
			$this->db->where('schoolYearID', $schoolYearID);
		}
		if($current_date){
			$this->db->where('published_date <=', $current_date);
		}
		$this->db->where('added_to_job',0);
		// $this->db->where('published',2);
		$this->db->order_by('create_date', 'asc');
		return $this->db->get()->result();
	}

	public function forMigrations($limit = '', $start = '')
    {

        $this->db->select("holiday.*, 'Holiday' AS type");
        $this->db->from($this->_table_name);
		
        if ($limit)
            $this->db->limit($limit, $start);

		$this->db->group_by('holiday.holidayID');	
		$this->db->order_by('holiday.published_date', 'desc');

        $result = $this->db->get()->result();
        foreach ($result as $index => $data) {
			if($data->create_usertypeID == 1) {
				$user = $this->systemadmin_m->get_individual_systemadmin($data->create_userID);
			} else if($data->create_usertypeID == 2) {
				$user = $this->teacher_m->get_individual_teacher($data->create_userID);
			} else if($data->create_usertypeID == 3) {
				$user = $this->student_m->get_individual_student($data->create_userID);
			} else if($data->create_usertypeID == 4) {
				$user = $this->parents_m->get_individual_parents($data->create_userID);
			} else {
				$user = $this->user_m->get_individual_user($data->create_userID);
			}
            $result[$index]->created_by = $user->name;
            $result[$index]->user_image = $user->photo;
			$result[$index]->name = $user->name;
            $result[$index]->photo = $user->photo;
        }
        return $result;
    }

}