<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Faq_m extends MY_Model
{

	protected $_table_name = 'faq';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id asc";

	function __construct()
	{
		parent::__construct();
	}

	public function getFaqByID($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function general_get_faq($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function general_get_order_by_faq($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function general_get_single_faq($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function get_faq($id = NULL, $signal = false)
	{
		$query = parent::get($id, $signal);
		return $query;
		
	}

	public function get_single_faq($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_faq($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_faq($array)
	{
		$error = parent::insert($array);
		return TRUE;
	}
	public function insert_relation($array)
	{
		$this->db->insert("classes_faq_relation", $array);
		$id = $this->db->insert_id();
		return $id;
	}

	public function insert_batch_faq_relation($array){
		$insert = $this->db->insert_batch('classes_faq_relation', $array);
        return $insert ? true:false;
	}

	public function update_faq($data, $id = NULL)
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_faq($id)
	{
		parent::delete($id);
	}
	public function delete_faq_relation_byid($id)
	{
		$this->db->where(array('faq_id' => $id));
	  	$this->db->delete('classes_faq_relation'); 
	  	return true;
	}
	public function delete_faq_relation_by_class_id($id,$class)
	{
		$this->db->where(array('faq_id' => $id));
		$this->db->where(array('classes_id' => $class));
	  	$this->db->delete('classes_faq_relation'); 
	  	return true;
	}
	public function get_faq_classes($id){
		$this->db->select('*');
		$this->db->from('classes_faq_relation');
		$this->db->where('faq_id', $id);
		return $this->db->get()->result();
	}
	public function get_faq_by_class1($class){
		$this->db->select('*');
		$this->db->from('classes_faq_relation');
		$this->db->where('classes_id', $class);
		//$this->db->group_by('classes_faq_relation.faq_id');
		return $this->db->get()->result();
	}
	public function get_faq_by_class($class){
		$this->db->select('*');
		$this->db->from('classes_faq_relation');
		$this->db->join('faq', 'classes_faq_relation.faq_id = faq.id', 'LEFT');
		$this->db->where('classes_faq_relation.classes_id', $class);
		//$this->db->group_by('classes_faq_relation.faq_id');
		return $this->db->get()->result();
	}

	public function get_other_faq_by_class($faqids = []){
		$this->db->select('*');
		$this->db->from('faq');
		if(customCompute($faqids)){
			$this->db->where_not_in('id',$faqids);
		}
		return $this->db->get()->result();
	}

	public function get_faqs($faqids = []){
		$this->db->select('*');
		$this->db->from('faq');
		if(customCompute($faqids)){
			$this->db->where_in('id',$faqids);
		}
		return $this->db->get()->result();
	}

	public function get_single_faq_classes_relation($array){
		$this->db->select('*');
		$this->db->from('classes_faq_relation');
		$this->db->where($array);
		return $this->db->get()->row();
	}

}
