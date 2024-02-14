<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Classes_m.php";

class Mobile_job_m extends MY_Model {

	protected $_table_name = 'mobile_jobs';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "name asc";
	// public $units = [];

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_job($array=NULL, $signal=FALSE) 
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_job($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}


	public function get_order_by_jobs($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_job($array) 
	{
		$error = parent::insert($array);

		return TRUE;
	}

	public function update_job($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_job($id)
	{
		parent::delete($id);
	}
}
