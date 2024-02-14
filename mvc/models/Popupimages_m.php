<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Popupimages_m extends MY_Model {

	protected $_table_name = 'popup_images';
	protected $_primary_key = 'imageID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "imageID desc";

	public function __construct() {
		parent::__construct();
	}

	public function get_images($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_image($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_image($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_image($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_image($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_image($id){
		parent::delete($id);
	}

	public function get_active_images() {
		$this->db->select('*');
        $this->db->from('popup_images');
		$this->db->where('disabled',0);
        $query = $this->db->get();
        return $query->result();
	}
    

}