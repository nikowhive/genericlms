<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classgroup_m extends MY_Model {

    protected $_table_name = 'classgroup';
    protected $_primary_key = 'classgroupID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "classgroupID asc";

    function __construct() {
        parent::__construct();
    }

    public function get_classgroup($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_classgroup($array) {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_classgroup($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_classgroup($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_classgroup($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_classgroup($id){
        parent::delete($id);
    }

    public function get_class_group_with_classes($limit = '', $start = '') {
		$this->db->select('*');
		$this->db->from('classgroup');
		$this->db->order_by('classgroup.classgroupID desc');
		if ($limit)
            $this->db->limit($limit, $start);
			$query = $this->db->get();
		return $query->result();
		
	}


    public function get_all_published_class_groups() {
		$this->db->select('*');
		$this->db->from('classgroup');
		$this->db->order_by('classgroup.classgroupID asc');
        $this->db->where('published',1);
		$query = $this->db->get();
		return $query->result();
		
	}


}
