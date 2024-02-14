<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activitiescomment_m extends MY_Model {

	protected $_table_name = 'activitiescomment';
	protected $_primary_key = 'activitiescommentID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "activitiescommentID asc";

	function __construct() {
		parent::__construct();
		$this->load->model('systemadmin_m');
        $this->load->model('student_m');
        $this->load->model('teacher_m');
        $this->load->model('parents_m');
        $this->load->model('user_m');
	}

	function get_activitiescomment($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_activitiescomment($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_activitiescomment($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_activitiescomment($array) {
		$id = parent::insert($array);
		return $id;
	}

	function update_activitiescomment($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_activitiescomment($id){
		parent::delete($id);
	}

	public function paginatedActivityComments($limit = '', $start= '',$condition){
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$this->db->select("*");
        $this->db->from($this->_table_name);
		if($schoolyearID)
		$this->db->where('schoolYearID', $schoolyearID);
		$this->db->where($condition);
		if ($limit)
            $this->db->limit($limit, $start);
		$this->db->order_by('create_date', 'desc');	
		
			$result = $this->db->get()->result();
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
				
				$result[$index]->name = $user?$user->name:'';
				$result[$index]->photo = $user?$user->photo:'';
			}
			return $result;	
	}
}

/* End of file activitiescomment_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/activitiescomment_m.php */