<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Classes_detail_content_m extends MY_Model
{

    protected $_table_name     = 'classes_detail_content';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_detail_content($array = null, $signal = false)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_detail_content($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_detail_content($array = null)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_detail_content($array)
    {
        parent::insert($array);
        return true;
    }

    public function update_detail_content($data, $id = null)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_detail_content($id)
    {
        parent::delete($id);

    }

    public function delete_all_detail_content($array)
    {
        $this->db->where($array);
        $this->db->delete($this->_table_name);
    }

    

}