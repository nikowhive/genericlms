<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sattendance_m extends MY_Model {

	protected $_table_name = 'attendance';
	protected $_primary_key = 'attendanceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "monthyear asc";

	function __construct() {
		parent::__construct();
	}

	public function get_attendance($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_attendance($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_order_by_attendancerange($classesID, $sectionID, $monthyearfrom, $monthyearto, $schoolyearID){
        
        $query = $this->db->query("SELECT * FROM `attendance` WHERE (monthyear BETWEEN '".$monthyearfrom."' and '".$monthyearto."') and classesID = ".$classesID." and sectionID = ".$sectionID." and schoolyearID = ".$schoolyearID." ORDER BY studentID");
        if ( $query ) {
            return $query->result_array();
        } else {
            return $query;
        }
	}

	public function insert_attendance($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_attendance1($array) {
		$id = parent::insert($array);
		return $id;
	}

	public function insert_batch_attendance($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_attendance($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_batch_attendance($data, $id = NULL) {
        parent::update_batch($data, $id);
        return TRUE;
    }

	public function delete_attendance($id){
		parent::delete($id);
	}


	public function get_order_by_attendance_with_note($array=NULL) {
		$this->db->select('*');
		$this->db->from('attendance');
		$this->db->join('attendance_note', 'attendance_note.attendanceID = attendance.attendanceID', 'LEFT');
		$this->db->where(array('attendance.attendanceID' => $array['attendanceID']));
		$query = $this->db->get();
		return $query->row();
	}

	public function getAttendancep($classesID = NULL,$sectionID = NULL, $attdate){
		$monthyear = date('m-Y',strtotime($attdate));
		$columnindex = date('j',strtotime($attdate));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		// //print_r($columnindex);die();
		// $this->db->select('*');
		// $this->db->from('attendance');
		// //print_r('a'.$columnindex);die();
		// $this->db->where(array('monthyear1'=>$monthyear,'classesID'=>$classesID,'sectionID'=>$sectionID));
		// $this->db->where(array('a'.$columnindex=>'P'));
		// $this->db->or_where("a16 IS NULL");
		//return $this->db->count_all_results().' Present';
		$row = $this->db->query("select count(*) as count from attendance where monthyear = '".$monthyear."' AND classesID = '".$classesID."' AND schoolyearID = '".$schoolyearID."' AND sectionID = '".$sectionID."' AND (a".$columnindex." ='P' OR a16 IS NULL)");
		$count = $row->row();
		return $count->count.' Present';
	}

	public function getAttendancel($classesID = NULL,$sectionID = NULL, $attdate){
		$monthyear = date('m-Y',strtotime($attdate));
		$columnindex = date('j',strtotime($attdate));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		//print_r($columnindex);die();
		$this->db->select('*');
		$this->db->from('attendance');
		$this->db->where(array('monthyear'=>$monthyear,'a'.$columnindex=>'L','classesID'=>$classesID,'sectionID'=>$sectionID,'schoolyearID' => $schoolyearID));
		return $this->db->count_all_results().' Late';
	}

	public function getAttendancea($classesID = NULL,$sectionID = NULL, $attdate){
		$monthyear = date('m-Y',strtotime($attdate));
		$columnindex = date('j',strtotime($attdate));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		//print_r($columnindex);die();
		$this->db->select('*');
		$this->db->from('attendance');
		$this->db->where(array('monthyear'=>$monthyear,'a'.$columnindex=>'A','classesID'=>$classesID,'sectionID'=>$sectionID,'schoolyearID'=>$schoolyearID));
		return $this->db->count_all_results().' Absent';
	}

	public function getAttendancenote($attID,$attDate){
		//return $attDate;
		$this->db->select('*');
		$this->db->from('attendance_note');
		$this->db->where(array('attendanceID'=>$attID));
		$query = $this->db->get();
		$row = $query->result_array();
		//print_r($row[0]);
		return (!empty($row))?$row[0]['a'.$attDate]:'N/A'; 
	}

	public function getAttendanceSubjectnote($attID,$attDate){
		
		$this->db->select('*');
		$this->db->from('sub_attendance_note');
		$this->db->where(array('attendanceID'=>$attID));
		$query = $this->db->get();
		$row = $query->result_array();
		// print_r($row[0]);
		return (!empty($row))?$row[0]['a'.$attDate]:'N/A'; 
	}

	public function get_attendanceByMonth($array=NULL) {
		$this->db->select('*');
		$this->db->from('attendance');
		$this->db->where($array);
		$query = $this->db->get();
		return $query->row();
	}

	public function isMultipleOf31( $n)
	{
	    while ( $n > 0 )
        $n = $n - 31;
 
	    if ( $n == 0 )
	        return true;
	 
	    return false;
	}
}