<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examtermsettingrelation_m extends MY_Model {

	protected $_table_name     = 'examtermsettingrelation';
	protected $_primary_key    = 'examtermsettingrelationID';
	protected $_primary_filter = 'intval';
	protected $_order_by       = "examtermsettingrelationID";

	function __construct() {
		parent::__construct();
	}

	public function get_examtermsettingrelation($array=NULL, $single=FALSE) {
		return parent::get($array, $single);
	}

	public function get_order_by_examtermsettingrelation($array=NULL) {
		return parent::get_order_by($array);
	}

	public function get_single_examtermsettingrelation($array=NULL) {
		return parent::get_single($array);
	}

	public function insert_examtermsettingrelation($array) {
		return parent::insert($array);
	}

	public function insert_batch_examtermsettingrelation($array) {
		return parent::insert_batch($array);
	}

	public function update_examtermsettingrelation($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_examtermsettingrelation($id){
		return parent::delete($id);
	}

	public function delete_examtermsettingrelation_by_array($array=[]) {
		if(customCompute($array)) {
			$this->db->where($array);
			return $this->db->delete($this->_table_name);
		} 
		return FALSE;
	}

}