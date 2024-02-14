<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Enrollment_m extends MY_Model
{

	protected $_table_name = 'enrollment';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id";

	function __construct()
	{
		parent::__construct();
	}

	public function getEnrollmentByID($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function general_get_enrollment($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function general_get_order_by_enrollment($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function general_get_single_enrollment($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function get_enrollment($id = NULL, $signal = false)
	{
		$query = parent::get($id, $signal);
		return $query;
	}

	public function get_single_enrollment($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_enrollment($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_enrollment($array)
	{
		$error = parent::insert($array);
		return TRUE;
	}

    public function insert_enrollment_rel($array)
    {
        $this->db->insert("class_enrollments_relation", $array);
		$id = $this->db->insert_id();
		return $id;
    }
    public function get_enrollment_class($id){
        $this->db->select('*');
        $this->db->from('class_enrollments_relation as cls');
        $this->db->where('cls.enrollment_id',$id);
        $query = $this->db->get();
		return $query->result(); 
    }
	public function get_enrollment_by_class($id){
		$this->db->select('*');
		$this->db->join('enrollment', 'class_enrollments_relation.enrollment_id = enrollment.id', 'LEFT');
		$this->db->from('class_enrollments_relation');
		$this->db->where('class_id', $id);
		return $this->db->get()->result();
	}

	public function get_other_enrollment_by_class($ids = []){
		$this->db->select('*');
		$this->db->from('enrollment');
		if(customCompute($ids)){
			$this->db->where_not_in('id',$ids);
		}
		return $this->db->get()->result();
	}

    public function delete_enrollment_relation_byid($id)
	{
		$this->db->where(array('enrollment_id' => $id));
	  	$this->db->delete('class_enrollments_relation'); 
	  	return true;
	}

	public function update_enrollment($data, $id = NULL)
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_enrollment($id)
	{
		parent::delete($id);
	}

	public function get_single_enrollment_classes_relation($array){
		$this->db->select('*');
		$this->db->from('class_enrollments_relation');
		$this->db->where($array);
		return $this->db->get()->row();
	}

	public function insert_batch_enrollment_relation($array){
		$insert = $this->db->insert_batch('class_enrollments_relation', $array);
        return $insert ? true:false;
	}

	public function get_enrollments($ids = []){
		$this->db->select('*');
		$this->db->from('enrollment');
		if(customCompute($ids)){
			$this->db->where_in('id',$ids);
		}
		return $this->db->get()->result();
	}

	public function delete_enrollment_relation_by_class_id($id,$classID)
	{
		$this->db->where(array('enrollment_id' => $id));
		$this->db->where(array('class_id' => $classID));
	  	$this->db->delete('class_enrollments_relation'); 
	  	return true;
	}
}
