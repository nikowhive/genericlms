<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class systemadmin_m extends MY_Model {

	protected $_table_name = 'systemadmin';
	protected $_primary_key = 'systemadminID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "systemadminID";

	function __construct() {
		parent::__construct();
	}

	function get_systemadmin_by_usertype($systemadminID = null) {
		$this->db->select('*');
		$this->db->from('systemadmin');
		$this->db->join('usertype', 'usertype.usertypeID = systemadmin.usertypeID', 'LEFT');
		if($systemadminID) {
			$this->db->where(array('systemadminID' => $systemadminID));
			
			$query = $this->db->get();
			return $query->row();
		} else {
			$this->db->where(array('systemadmin.active' => 1));
			$query = $this->db->get();
			return $query->result();
		}
	}

	function get_username($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	function get_systemadmin($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_systemadmin($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_systemadmin($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_individual_systemadmin($adminID) {
		$table_name = $this->_table_name;
		$query = $this->db->select('*')
				->from($table_name)
				->where('systemadminID', $adminID);
		return $query->get()->row();;
	}

	public function get_select_systemadmin($select = NULL, $array=[]) {
		if($select == NULL) {
			$select = 'systemadminID, name, photo';
		}

		$this->db->select($select);
		$this->db->from($this->_table_name);

		if(customCompute($array)) {
			$this->db->where($array);
		}

		$query = $this->db->get();
		return $query->result();
	}

	function insert_systemadmin($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_systemadmin($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	function delete_systemadmin($id){
		parent::delete($id);
	}

	function hash($string) {
		return parent::hash($string);
	}	

	public function searchSystemAdmins($text,$limit,$start){

		$text = trim($text);
		$this->db->select('systemadmin.name,systemadmin.systemadminID AS ID,systemadmin.photo,systemadmin.usertypeID');
		$this->db->from('systemadmin');
		if($text != ''){
		  $this->db->like('name', $text);
		}
		if($limit)
            $this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result_array();

	}

	public function searchSystemAdminsExport($text,$limit = '',$start= ''){

		$text = trim($text);
		$this->db->select('systemadmin.systemadminID,systemadmin.usertypeID,
		systemadmin.photo,systemadmin.name,systemadmin.dob,systemadmin.sex,systemadmin.email,systemadmin.phone,systemadmin.address');
		$this->db->from('systemadmin');
		if($text != ''){
		  $this->db->like('name', $text);
		}
		if($limit){
			$this->db->limit($limit, $start);
		}
		$query = $this->db->get();
		return $query->result();

	}

    public function getAllActiveSystemadmins($array){

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");

		$this->db->select('systemadmin.systemadminID AS ID,systemadmin.usertypeID');
		$this->db->from('systemadmin');
		$this->db->where($array);
		$this->db->where("(systemadminID != '$userID' OR usertypeID != '$userTypeID')");
		$query = $this->db->get();
		return $query->result_array();

	}

	public function getAllActiveSystemadminsDetails($name = ''){

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");

		$this->db->select('systemadmin.systemadminID AS ID,systemadmin.usertypeID,systemadmin.name,systemadmin.email,systemadmin.phone');
		$this->db->from('systemadmin');
		$this->db->where('active',1);
		$this->db->where("(systemadminID != '$userID' OR usertypeID != '$userTypeID')");
		if($name){
			$this->db->like('name',$name);
		}
		$query = $this->db->get();
		return $query->result_array();

	}

	public function systemadmin_count() {
		$this->db->select('count(*) as count');
		$this->db->from('systemadmin');
		$query = $this->db->get();
		return $query->row();
	}
}
