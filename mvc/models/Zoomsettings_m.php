<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zoomsettings_m extends MY_Model {

    protected $_table_name = 'zoomsettings';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "id asc";

    function __construct() {
        parent::__construct();
    }

   function get_zoomsettings($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_order_by_zoomsettings($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function get_single_zoomsettings($array=NULL) {
        $query = parent::get_single($array);
        return $query;
    }

    function insert_zoomsettings($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_zoomsettings($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_zoomsettings($id){
        parent::delete($id);
    }  
}