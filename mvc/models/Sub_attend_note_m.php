<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sub_attend_note_m extends MY_Model {

	protected $_table_name = 'sub_attendance_note';
	protected $_primary_key = 'subattnoteID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "attendanceID asc";

	function __construct() {
		parent::__construct();
	}

	public function get_sub_attendance_note($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_sub_attendance_note($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}
	
	function get_single_sub_attendance_note($array) {
        $query = parent::get_single($array);
        return $query;
    }

	public function insert_sub_attendance_note($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_sub_attendance_note1($array) {
		$id = parent::insert($array);
		return $id;
	}

	public function insert_batch_sub_attendance_note($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_sub_attendance_note($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_batch_sub_attendance_note($data, $id = NULL) {
        parent::update_batch($data, $id);
        return TRUE;
    }

	public function delete_sub_attendance_note($id){
		parent::delete($id);
	}

	public function getSubNotesbyattid($attid=NULL,$dateday){
        $this->db->select('a'.$dateday);
        $this->db->from('sub_attendance_note');
        $this->db->where(array('attendanceID'=>$attid));
        $query = $this->db->get();
        return $query->row_array();
	}

	public function get_order_by_sub_attendance_with_note($array=NULL) {
		$this->db->select('*');
		$this->db->from('sub_attendance');
		$this->db->join('sub_attendance_note', 'sub_attendance_note.attendanceID = sub_attendance.attendanceID', 'LEFT');
		// $this->db->where(array('sub_attendance.attendanceID' => $array['attendanceID'],'sub_attendance.subjectID' => $array['subjectID'] ));
		$this->db->where_in($array);
		$query = $this->db->get();
		return $query->row();
	}


}