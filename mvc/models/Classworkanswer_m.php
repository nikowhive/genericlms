<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classworkanswer_m extends MY_Model {

	protected $_table_name = 'classworkanswer';
	protected $_primary_key = 'classworkanswerID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "answerdate desc";

	function __construct() {
		parent::__construct();
	}

	public function join_get_classworkanswer($classworkID, $schoolyearID, $studentID=NULL) {
		$this->db->select('*');
		$this->db->from('classworkanswer');
		$this->db->join('studentrelation', 'studentrelation.srstudentID = classworkanswer.uploaderID', 'LEFT');
		$this->db->join('section', 'section.sectionID = studentrelation.srsectionID', 'LEFT');
		$this->db->join('student', 'student.studentID = studentrelation.srstudentID', 'LEFT');
		$this->db->where('classworkanswer.classworkID', $classworkID);
		$this->db->where('classworkanswer.schoolyearID', $schoolyearID);
		$this->db->where('studentrelation.srschoolyearID', $schoolyearID);
		
		if($studentID == NULL) {
			$query = $this->db->get();
			return $query->result();
		} else {
			$this->db->where('classworkanswer.uploaderID', $studentID);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function get_classworkanswer_by_student($classworkID, $schoolyearID, $studentID=NULL) {
		$this->db->select('*');
		$this->db->from('classworkanswer');
		$this->db->where('classworkanswer.classworkID', $classworkID);
		$this->db->where('classworkanswer.schoolyearID', $schoolyearID);
		
		$this->db->where('classworkanswer.uploaderID', $studentID);
		$query = $this->db->get();
		return $query->row();
		
	}

	public function get_classworkanswer($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_classworkanswer($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_classworkanswer($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_classworkanswer($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_classworkanswer($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_classworkanswer($id){
		parent::delete($id);
	}

	public function get_classworkanswer_feed($array=NULL, $opt=NULL) {
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