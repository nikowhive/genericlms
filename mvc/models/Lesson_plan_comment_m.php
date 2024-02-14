<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lesson_plan_comment_m extends MY_Model {

	protected $_table_name = 'lesson_plan_comments';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id asc";

	function __construct() {
		parent::__construct();
	}

	function get_lesson_plan_comments($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_lesson_plan_comments($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_lesson_plan_comments($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_lesson_plan_comments($array) {
		$id = parent::insert($array);
		return $id;
	}

	function update_lesson_plan_comments($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_lesson_plan_comments($id){
		parent::delete($id);
	}
}
