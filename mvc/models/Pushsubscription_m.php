<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pushsubscription_m extends MY_Model {

	protected $_table_name = 'push_subscriptions';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_single_push_subscriptions($array) 
	{
		$query = parent::get_single($array);
		return $query;
	}

    public function get_push_subscriptions($id=NULL, $signal=false) 
	{
		$query = parent::get($id, $signal);
		return $query;
	}

	public function get_order_by_push_subscriptions($array=NULL) 
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function update_push_subscriptions_from_endpoint($data, $endpoint) 
	{
		$this->db->where('endpoint', $endpoint);
		$this->db->update($this->_table_name, $data);
	}

	public function delete_push_subscriptions_from_endpoint($endpoint)
	{
		$this->db->where('endpoint', $endpoint);
		$this->db->delete($this->_table_name); 
	}

	public function insert_push_subscriptions($data) 
	{
		parent::insert($data);
		return true;
	}
}	
