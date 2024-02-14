<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Daily_plan_version_m extends MY_Model
{

	protected $_table_name = 'daily_plan_versions';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id desc";

	function __construct()
	{
		parent::__construct();
	}

	public function get_daily_plan_version($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_daily_plan_version($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_daily_plan_version($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function insert($array)
	{
		$id = parent::insert($array);
		return $id;
	}

	public function update($data, $id = NULL)
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete($id)
	{
		parent::delete($id);
	}

	function insert_batch($array) {
        $insert = $this->db->insert_batch($this->_table_name, $array);
        return $insert ? true:false;
    }

}
