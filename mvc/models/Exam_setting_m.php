<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Classes_m.php";

class Exam_setting_m extends MY_Model {

	protected $_table_name = 'zzz_2_exam_settings';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id DESC";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_join_exam_settings($id) 
	{
		$table_name = $this->_table_name;

		$this->db->select($table_name.'.*, subject.subject');
		$this->db->from($table_name);
		$this->db->join('subject', 'subject.subjectID = '.$table_name.'.subject_id', 'LEFT');
		$this->db->where('subject.ClassesID', $id);
		$query = $this->db->get();
		return $query->result();
	}


	public function get_join_where_subject($id) 
	{
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->join('classes', 'classes.ClassesID = subject.classesID', 'LEFT');
		$this->db->where("subject.classesID", $id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_subject_from_chapter_id($chapter_id) {
		$table_name = $this->_table_name;
		$this->db->select('*')
				->from($table_name)
				->where('id', $chapter_id);

		$result = $this->db->get()->row();

		return $result ? $result->subject_id : NULL;
	}

	public function get_exam_setting($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;	
	}

	public function get_subject($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_subject($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_subject($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_setting($array) 
	{
		$error = parent::insert($array);

		return TRUE;
	}

	public function update_setting($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_setting($id)
	{
		parent::delete($id);
	}
}