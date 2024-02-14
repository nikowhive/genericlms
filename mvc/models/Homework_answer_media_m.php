<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Homework_answer_media_m extends MY_Model {

	protected $_table_name = 'homework_answer_media';
	protected $_primary_key = 'ID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "homeworkanswerID asc";

	function __construct() {
		parent::__construct();
	}

	function get_homework_answer_media($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_homework_answer_media($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_homework_answer_media($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_homework_answer_media($array) {
		$id = parent::insert($array);
		return $id;
	}

	function insert_batch_homework_answer_media($array) {
        $insert = $this->db->insert_batch($this->_table_name, $array);
        return $insert ? true:false;
    }

	function update_homework_answer_media($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_homework_answer_media($id){
		parent::delete($id);
	}
	public function delete_batch_homework_answer_media($array)
	{
        $this->db->where($array);
        $this->db->delete($this->_table_name);
	}
}

