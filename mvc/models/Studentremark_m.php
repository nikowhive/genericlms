<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentremark_m extends MY_Model {

	protected $_table_name = 'studentremark';
	protected $_primary_key = 'studentremarkID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "studentremarkID asc";


	function __construct() {
		parent::__construct();
	}

	public function get_studentremark($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_studentremark($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_studentremark($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_studentremark($array) {
		$error = parent::insert($array);
		return $error;
	}

	public function update_studentremark($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_studentremark($id){
		parent::delete($id);
    }
    
    public function checkRecord($array){
        $query = parent::get_single($array);
		return $query;
    }
}