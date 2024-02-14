<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_comment_m extends MY_Model {

	protected $_table_name = 'notice_comment';
	protected $_primary_key = 'commentID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "commentID asc";

	function __construct() {
		parent::__construct();
        $this->load->model('systemadmin_m');
        $this->load->model('student_m');
        $this->load->model('teacher_m');
        $this->load->model('parents_m');
        $this->load->model('user_m');
	}

	function get_notice_comment($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_notice_comment($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_notice_comment($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_notice_comment($array) {
		$id = parent::insert($array);
		return $id;
	}

	function update_notice_comment($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_notice_comment($id){
		parent::delete($id);
	}

	public function paginatedNoticeComments($limit = '', $start= '',$condition){
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

/* End of file Notice_comment_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/Notice_comment_m.php */