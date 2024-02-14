<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classwork_media_m extends MY_Model {

	protected $_table_name = 'classwork_media';
	protected $_primary_key = 'ID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classworkID asc";

	function __construct() {
		parent::__construct();
	}

	function get_classwork_media($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_classwork_media($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_classwork_media($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_classwork_media($array) {
		$id = parent::insert($array);
		return $id;
	}

	function insert_batch_classwork_media($array) {
        $insert = $this->db->insert_batch($this->_table_name, $array);
        return $insert ? true:false;
    }

	function update_classwork_media($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_classwork_media($id){
		parent::delete($id);
		return true;
	}
	public function delete_batch_classwork_media($array)
	{
        $this->db->where($array);
        $this->db->delete($this->_table_name);
	}
}

