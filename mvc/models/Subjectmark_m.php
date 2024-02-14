<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subjectmark_m extends MY_Model {

	protected $_table_name = 'subject_marks';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "fullmark asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_subject_mark($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_subject_mark($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}


	public function get_order_by_subject_marks($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_subject_mark($array) 
	{
		$error = parent::insert($array);

		return TRUE;
	}

	public function insert_batch_subject_mark($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_subject_mark($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	function update_batch_subject_mark($data, $id = NULL) {
        parent::update_batch($this->_table_name,$data, $id);
        return TRUE;
    }

	public function delete_subject_mark($id)
	{
		parent::delete($id);
	}

	public function get_subjects_by_class_exam_id($class_id='',$exam_id='') {
		$this->db->select('subject.subjectID,subject.subject,subject.coscholatics,subject_marks.*')
				->from('subject')
				->join('subject_marks','subject.subjectID = subject_marks.subject_id')
				 ->where('subject_marks.class_id', $class_id,'LEFT')
				 ->where('subject_marks.exam_id', $exam_id,'LEFT')
				 ->order_by('subject_marks.subject_id asc');
		$result = $this->db->get()->result();
		return $result;
	}

	public function get_enabled_coscholastic($array) {
		$this->db->select('subject.*, subject_marks.class_id, subject_marks.no_coscholastic');
		$this->db->from('subject');
		$this->db->join('subject_marks', 'subject_marks.subject_id = subject.subjectID', 'LEFT');
		$this->db->where('classesID', $array['classesID']);
		$this->db->where('type', $array['type']);
		$this->db->where('subject.coscholatics', 1);
		$this->db->where('subject_marks.exam_id', $array['examID']);
		$this->db->where('subject_marks.no_coscholastic !=', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_subjects_by_class_exam_and_subject_id($class_id='',$subject_id,$exam_id) {
		$this->db->select('*')
				->from('subject_marks')
				 ->where('subject_marks.class_id', $class_id)
				 ->where('subject_marks.subject_id', $subject_id)
				 ->where('subject_marks.exam_id', $exam_id);
		$result = $this->db->get()->row();
		return $result;
	}

	

}