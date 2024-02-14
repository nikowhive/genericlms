<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Note_m extends MY_Model {

	protected $_table_name = 'notes';
	protected $_primary_key = 'noteID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "noteID desc";

	function __construct() {
		parent::__construct();
        $this->load->model('systemadmin_m');
        $this->load->model('student_m');
        $this->load->model('teacher_m');
        $this->load->model('parents_m');
        $this->load->model('user_m');
	}

	function get_note($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_query_note($array) {
		$this->db->select();
        $this->db->from($this->_table_name);
        $this->db->order_by('create_date', 'desc');
        
        $this->db->where($array);
        $result = $this->db->get()->result();
		return $result;
	}

	function get_single_note($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_note($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_note($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_note($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_note($id){
		parent::delete($id);
	}

    public function getRecentNotes($limit, $start, $schoolYearID = null)
    {
        $this->db->select("*, 'Note' AS type");
        $this->db->from($this->_table_name);
        $this->db->order_by('create_date', 'desc');
        if ($schoolYearID)
            $this->db->where('schoolYearID', $schoolYearID);
        if ($limit)
            $this->db->limit($limit, $start);
        $result = $this->db->get()->result();
        foreach ($result as $index => $data) {
			if($data->usertypeID == 1) {
                $user = $this->systemadmin_m->get_single_systemadmin(['systemadminID' => $data->userID]);
            } else if($data->usertypeID == 2) {
                $user = $this->teacher_m->get_single_teacher(['teacherID' => $data->userID]);
            } else if($data->usertypeID == 3) {
                $user = $this->student_m->get_single_student(['studentID' => $data->userID]);
            } else if($data->usertypeID == 4) {
                $user = $this->parents_m->get_single_parents(['parentsID' => $data->userID]);
            } else {
                $user = $this->user_m->get_single_user(['userID' => $data->userID]);
            }
            $result[$index]->created_by = $user->name;
            $result[$index]->user_image = $user->photo;
        }
        return $result;
    }

    public function getCount()
    {
        $this->db->select("count(*) as count");
        $this->db->from($this->_table_name);
        return $this->db->get()->row()->count;
    }
}