<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Classes_m.php";

class Unit_m extends MY_Model {

	protected $_table_name = 'zzz_4_units';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "unit_name asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function getTable() {
		return $this->_table_name;
	}

	public function get_unit_name_from_id($id) 
	{
		$table_name = $this->_table_name;

		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('id', $id);
		$query = $this->db->get();
		$row = $query->row();
		return $row ? $row->unit_name : NULL;

	}

	public function get_join_units($id) 
	{
		$table_name = $this->_table_name;

		$this->db->select($table_name.'.*, subject.subject');
		$this->db->from($table_name);
		$this->db->join('subject', 'subject.subjectID = '.$table_name.'.subject_id', 'LEFT');
		$this->db->where('subject.ClassesID', $id);
		$query = $this->db->get();
		return $query->result();
	}

    public function get_units($id) 
	{
		$this->db->select('zzz_4_units.*, subject.subject, subject.classesID, classes.classes');
		$this->db->from('zzz_4_units');
		$this->db->join('subject', 'subject.subjectID = zzz_4_units.subject_id', 'LEFT');
		$this->db->join('classes', 'classes.classesID = subject.classesID', 'LEFT');
		$this->db->where('zzz_4_units.id', $id);
		$query = $this->db->get();
		return $query->row();
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

	public function get_units_by_subject_id($subject_id) {
		$table_name = $this->_table_name;
		$this->db->select('*')
				->from($table_name)
				->where('subject_id', $subject_id);
		$result = $this->db->get()->result();

		return $result;
	}

	public function get_units_by_subject_id_api($subject_id) {
		$table_name = $this->_table_name;
		$this->db->select('*')
				->from($table_name)
				->where('subject_id', $subject_id)
				->where('published', 1);

		$result = $this->db->get()->result();

		return $result;
	}

	public function get_units_count_by_subject_id($subject_id) {
		$table_name = $this->_table_name;
		$this->db->select('count(*) as count')
				->from($table_name)
				->where('subject_id', $subject_id);

		$result = $this->db->get()->row();
		return $result;
	}

	public function get_subject_from_unit_id($unit_id) {
		$table_name = $this->_table_name;
		$this->db->select('*')
				->from($table_name)
				->where('id', $unit_id);

		$result = $this->db->get()->row();

		return $result ? $result->subject_id : NULL;
	}

	public function get_unit($array=NULL, $signal=FALSE) {
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

	public function general_get_order_by_unit($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_unit($array) 
	{
		$error = parent::insert($array);

		return TRUE;
	}

	public function update_unit($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_unit($id)
	{
		parent::delete($id);
	}

	public function get_ids_from_subject_id($subject_id) {
		$table_name = $this->_table_name;
		$query = $this->db->select('id')
				->from($table_name)
				->where('subject_id', $subject_id);
		$array = $query->get()->result_array();
		$arr = array_column($array, "id");
		return $arr;
	}

	public function get_ids_from_subject_ids($subject_ids) {
		$table_name = $this->_table_name;
		$query = $this->db->select('id')
				->from($table_name)
				->where_in('subject_id', $subject_ids);
		$array = $query->get()->result_array();
		$arr = array_column($array, "id");
		return $arr;
	}

	public function get_unit_from_subject($subject_id) {
		$table_name = $this->_table_name;
		$this->db->select('*')
				->from($table_name)
				->where('subject_id', $subject_id);
		$result = $this->db->get()->result();
		return $result;
	}

	public function get_units_from_chapter_id($unit_id) {
		$table_name = $this->_table_name;
		$this->db->select('*')
				->from($table_name)
				->where('id', $unit_id);
		$result = $this->db->get()->result();
		return $result;
	}

}
