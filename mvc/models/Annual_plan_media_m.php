<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Annual_plan_media_m extends MY_Model {

	protected $_table_name = 'annual_plan_media';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id asc";

	function __construct() {
		parent::__construct();
	}

	function get_annual_plan_media($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_annual_plan_media($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_annual_plan_media($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_annual_plan_media($array) {
		$id = parent::insert($array);
		return $id;
	}

	function insert_batch_annual_plan_media($array) {
        $insert = $this->db->insert_batch($this->_table_name, $array);
        return $insert ? true:false;
    }

	function update_annual_plan_media($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	function delete_annual_plan_media($id){
		parent::delete($id);
	}

	function update_batch_annual_plan_media($data, $id = NULL) {
        parent::update_batch($data, $id);
        return TRUE;
    }

	

}
