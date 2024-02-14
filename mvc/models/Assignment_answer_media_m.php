<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assignment_answer_media_m extends MY_Model {

	protected $_table_name = 'assignment_answer_media';
	protected $_primary_key = 'ID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "assignmentanswerID asc";

	function __construct() {
		parent::__construct();
	}

	function get_assignment_answer_media($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_assignment_answer_media($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_assignment_answer_media($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_assignment_answer_media($array) {
		$id = parent::insert($array);
		return $id;
	}

	function insert_batch_assignment_answer_media($array) {
        $insert = $this->db->insert_batch($this->_table_name, $array);
        return $insert ? true:false;
    }

	function update_assignment_answer_media($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_assignment_answer_media($id){
		parent::delete($id);
	}
	public function delete_batch_assignment_answer_media($array)
	{
        $this->db->where($array);
        $this->db->delete($this->_table_name);
	}

	public function getAllAsignmentAnswerFiles(){

		$userTypeID          = $this->session->userdata("usertypeID");
        $userID              = $this->session->userdata("loginuserID");
		$schoolyearID        = $this->session->userdata('defaultschoolyearID');

		$this->db->select('assignment_answer_media.*');
		$this->db->from($this->_table_name);
		$this->db->join('assignmentanswer', 'assignmentanswer.assignmentanswerID = assignment_answer_media.assignmentanswerID', 'LEFT');
        $this->db->where('assignmentanswer.schoolyearID',$schoolyearID);
		if($userID != 1 && $userTypeID != 1){
			$this->db->where('assignmentanswer.uploaderID',$userID);
			$this->db->where('assignmentanswer.uploadertypeID',$userTypeID);
		}
		$query = $this->db->get();
		return $result = $query->result();

	}
}

