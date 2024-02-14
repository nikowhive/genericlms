<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coursesstudent_progress_m extends MY_Model {

	protected $_table_name = 'coursestudent_progress';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id asc";

	public function __construct() 
	{
		parent::__construct();
	}
    
    public function get_courses_student_progress($id=NULL, $signal=false) 
	{
		$query = parent::get($id, $signal);
		return $query;
	}

	public function get_order_by_courses_student_progress($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_courses_student_progress($array) 
	{
		$query = parent::insert($array);
		return $query;
	}

	public function update_courses_student_progress($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function get_last_covered_content($array)
	{
		$table = $this->_table_name;
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($array);
		$this->db->order_by('content_id desc');
		$query = $this->db->get();
		return $query->row();
	}
}	
