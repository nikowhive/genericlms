<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mobile_pushdelivery_m extends MY_Model {

	protected $_table_name = 'mobile_push_deliveries';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_single_push_delivery($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}

    public function get_push_deliveries($id=NULL, $signal=false) 
	{
		$query = parent::get($id, $signal);
		return $query;
	}

	public function get_order_by_push_deliveries($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function update_push_delivery($data, $id = NULL) 
	{
		parent::update($data, $id);
		return $id;
	}

	public function insert_push_delivery($data) 
	{	
		parent::insert($data);
		return true;
	}

	public function batch_insert_push_delivery( $array )
	{
		$this->db->insert_batch('mobile_push_deliveries', $array);
		$id = $this->db->insert_id();
		return $id;
	}
}	
