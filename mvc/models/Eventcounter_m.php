<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Eventcounter_m extends MY_Model {

	protected $_table_name = 'eventcounter';
	protected $_primary_key = 'eventcounterID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "name asc";

	function __construct() {
		parent::__construct();
	}

	function get_eventcounter($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_eventcounter($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_eventcounter($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_eventcounter($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_eventcounter($id){
		parent::delete($id);
	}

	public function getEventCount($eventId, $status) {
        $this->db->select("count(*) as event_count");
        $this->db->from($this->_table_name);
        $this->db->where("eventID", $eventId);
        $this->db->where("status", $status);
        return $this->db->get()->row()->event_count;
    }
    public function getEventCountByRow($eventId, $username) {
        $this->db->from($this->_table_name);
        $this->db->where("eventID", $eventId);
        $this->db->where("username", $username);
        return $this->db->get()->row();
    }

    public function getCount($eventId) {
	    $this->db->select("SUM(CASE status WHEN 0 THEN 1 ELSE 0 END) AS not_going,
	     SUM(CASE status WHEN 1 THEN 1 ELSE 0 END) AS going");
        $this->db->from($this->_table_name);
        $this->db->where('eventID', $eventId);
        return $this->db->get()->row();

    }

}

/* End of file holiday_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/holiday_m.php */
