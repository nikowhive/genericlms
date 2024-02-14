<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Homeworkanswer_m extends MY_Model {

	protected $_table_name = 'homeworkanswer';
	protected $_primary_key = 'homeworkanswerID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "answerdate desc";

	function __construct() {
		parent::__construct();
	}

	public function join_get_homeworkanswer($homeworkID, $schoolyearID, $studentID=NULL) {
		$this->db->select('*');
		$this->db->from('homeworkanswer');
		$this->db->join('studentrelation', 'studentrelation.srstudentID = homeworkanswer.uploaderID', 'LEFT');
		$this->db->join('section', 'section.sectionID = studentrelation.srsectionID', 'LEFT');
		$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
		$this->db->where('homeworkanswer.homeworkID', $homeworkID);
		$this->db->where('homeworkanswer.schoolyearID', $schoolyearID);
		$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		
		if($studentID == NULL) {
			$query = $this->db->get();
			return $query->result();
		} else {
			$this->db->where('homeworkanswer.uploaderID', $studentID);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function get_homeworkanswer_by_student($homeworkID, $schoolyearID, $studentID=NULL) {
		$this->db->select('*');
		$this->db->from('homeworkanswer');
		$this->db->where('homeworkanswer.homeworkID', $homeworkID);
		$this->db->where('homeworkanswer.schoolyearID', $schoolyearID);
		
		$this->db->where('homeworkanswer.uploaderID', $studentID);
		$query = $this->db->get();
		return $query->row();
		
	}

	public function get_homeworkanswer($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_homeworkanswer($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_homeworkanswer($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_homeworkanswer($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_homeworkanswer($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_homeworkanswer($id){
		parent::delete($id);
	}
	
	public function get_homeworkanswer_feed($array=NULL, $opt=NULL) {
		$this->db->select('*');
		$this->db->from($this->_table_name);
		if($array){
			$this->db->where($array);
		}
		$query = $this->db->get();
		if($opt){
			return $qry = $query->num_rows();
		}else{
			return $query->result();
		}
	}
}