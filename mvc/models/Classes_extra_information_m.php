<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Classes_extra_information_m extends MY_Model
{

    protected $_table_name     = 'classes_extra_information';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_classes_extra_information($array = null, $signal = false)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_classes_extra_information($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_classes_extra_information($array = null)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_classes_extra_information($array)
    {
        parent::insert($array);
        return true;
    }

    public function update_classes_extra_information($data, $id = null)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_classes_extra_information($id)
    {
        parent::delete($id);

    }

    public function delete_all_classes_extra_information($array)
    {
        $this->db->where($array);
        $this->db->delete($this->_table_name);
    }

    

}