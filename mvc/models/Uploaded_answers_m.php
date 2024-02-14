<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Classes_m.php";

class Uploaded_answers_m extends MY_Model {

	protected $_table_name = 'zzz_3_uploaded_answers';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id DESC";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_single_uploaded_answer($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function get_uploaded_answers($exam_id, $class_id, $section_id, $student_id) {
		$table_name = $this->_table_name;
		$student_table = 'student';
		$this->db->select('*');
		$this->db->join($student_table, $student_table.'.studentID = '.$table_name.'.student_id', 'INNER');;
		$this->db->from($table_name);

		if($exam_id) {
			$this->db->where('exam_id', $exam_id);
		}

		if($class_id) {
			$this->db->where('classesID', $class_id);
		}

		if($section_id) {
			$this->db->where('sectionID', $section_id);
		}

		if($student_id) {
			$this->db->where('student_id', $student_id);
		}
				

		$result = $this->db->get()->result();

		return $result;
	}

	public function insert_answer($array) 
	{
		$error = parent::insert($array);

		return TRUE;
	}

	public function update_answer($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_answer($id)
	{
		parent::delete($id);
	}
}