<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parents_m extends MY_Model {

	protected $_table_name = 'parents';
	protected $_primary_key = 'parentsID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "parentsID asc";

	function __construct() {
		parent::__construct();
	}

	public function get_username($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	public function get_parents($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_parents($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_individual_parents($parentsID) {
		$table_name = $this->_table_name;
		$query = $this->db->select('*')
				->from($table_name)
				->where('parentsID', $parentsID);
		return $query->get()->row();;
	}

	public function get_order_by_parents($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_select_parents($select = NULL, $array=[]) {
		if($select == NULL) {
			$select = 'parentsID, name, photo';
		}

		$this->db->select($select);
		$this->db->from($this->_table_name);

		if(customCompute($array)) {
			$this->db->where($array);
		}

		$query = $this->db->get();
		return $query->result();
	}

	public function insert_parents($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_parents($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_parents($id){
		parent::delete($id);
		return TRUE;
	}

	public function hash($string) {
		return parent::hash($string);
	}

	public function searchParents($text,$limit,$start){

		$text = trim($text);
		$this->db->select('parents.name,parents.parentsID AS ID,parents.photo,parents.usertypeID');
		$this->db->from('parents');
		if($text != ''){
		  $this->db->like('name', $text);
		}
		if($limit)
            $this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result_array();

	}

	public function searchParentsExport($text,$limit = '',$start= ''){

		$text = trim($text);
		$this->db->select('parents.parentsID,parents.name,parents.usertypeID,
		parents.email,parents.phone,parents.address,parents.photo');
		$this->db->from('parents');
		if($text != ''){
		  $this->db->like('name', $text);
		}
		if($limit){
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result();

	}

	public function getAllActiveParents($array,$name=''){

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");
		
		$this->db->select('parents.parentsID AS ID,parents.usertypeID');
		$this->db->from('parents');
		$this->db->where($array);
		$this->db->where("(parentsID != '$userID' OR usertypeID != '$userTypeID')");
		if($name){
			$this->db->like('name',$name);
		}
		$query = $this->db->get();
		return $query->result_array();

	}

	public function getAllActiveParentsDetails($classesIds){

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");
		
		$this->db->select('parents.parentsID AS ID,parents.usertypeID');
		$this->db->from('student');
		$this->db->join('parents', 'parents.parentsID = student.parentID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = student.classesID', 'LEFT');
		$this->db->where('student.active',1);
		$this->db->where("(student.studentID != '$userID' OR student.usertypeID != '$userTypeID')");
		$this->db->where('student.schoolyearID', $this->session->userdata('defaultschoolyearID'));
		$this->db->where_in('student.classesID',$classesIds);
		$this->db->where('parents.active',1);
		$this->db->where('student.parentID !=', '');
		$query = $this->db->get();
		return $result = $query->result_array();

	}

}