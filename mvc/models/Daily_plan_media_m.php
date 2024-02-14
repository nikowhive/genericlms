<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Daily_plan_media_m extends MY_Model
{

	protected $_table_name = 'daily_plan_medias';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id desc";

	function __construct()
	{
		parent::__construct();
	}

	public function get_daily_plan_media($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_daily_plan_media($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_daily_plan_media($array = NULL)
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

	function update_batch($data, $id = NULL)
	{
		parent::update_batch($data, $id);
		return TRUE;
	}

	public function getfinaliziedVersion($id)
	{
		$this->db->select('*');
		$this->db->from('daily_plan_medias m');
		$this->db->join('daily_plan_versions v','m.daily_plan_version_id = v.id','left');
		$this->db->where('m.daily_plan_id',$id);
		$this->db->where('v.finalized_id',1);
		return $this->db->get()->result();



	}
}
