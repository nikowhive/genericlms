<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Classes_content_blocks_m extends MY_Model
{

    protected $_table_name     = 'classes_content_blocks';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "order asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_content_blocks($array = null, $signal = false)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_content_blocks($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_content_blocks($array = null)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_content_blocks($array)
    {
        parent::insert($array);
        return true;
    }

    public function update_content_blocks($data, $id = null)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_content_blocks($id)
    {
        parent::delete($id);
    }
    public function delete_all_content_blocks($array)
    {
        $this->db->where($array);
        $this->db->delete($this->_table_name);
    }

}