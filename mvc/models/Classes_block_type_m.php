<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Classes_block_type_m extends MY_Model
{

    protected $_table_name     = 'classes_block_type';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_blocktype($array = null, $signal = false)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_blocktype($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_blocktype($array = null)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_blocktype($array)
    {
        parent::insert($array);
        return true;
    }

    public function update_blocktype($data, $id = null)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_blocktype($id)
    {
        parent::delete($id);
    }

}