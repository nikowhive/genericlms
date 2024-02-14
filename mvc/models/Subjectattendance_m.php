<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Subjectattendance_m extends MY_Model
{

	protected $_table_name = 'sub_attendance';
	protected $_primary_key = 'attendanceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "monthyear asc";

	function __construct()
	{
		parent::__construct();
	}

	public function get_sub_attendance($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_sub_attendance($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_sub_attendance($array)
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_sub_attendance($array)
	{
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_sub_attendance($data, $id = NULL)
	{
		parent::update($data, $id);
		return $id;
	}

	public function update_batch_sub_attendance($data, $id = NULL)
	{
		parent::update_batch($data, $id);
		return TRUE;
	}

	public function delete_sub_attendance($id)
	{
		parent::delete($id);
	}

	public function get_single_sub_attendance($array)
	{
		return $this->db->select("*")
			->from($this->_table_name)
			->where($array)
			->get()
			->row();
	}

	public function getAttendancewithSub($studentid=''){
		
		$this->db->select('sa.*,st.subject');
		$this->db->from('sub_attendance sa');
		$this->db->join('subject st','sa.subjectID = st.subjectID', 'LEFT');
		$this->db->where('sa.studentID',$studentid);
		return $this->db->get()->result();
	}

    public function getAttendancep($classesID = NULL, $attdate, $subjectID = NULL){
		$monthyear = date('m-Y',strtotime($attdate));
		$columnindex = date('j',strtotime($attdate));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		//print_r($columnindex);die();
		$this->db->select('*');
		$this->db->from('sub_attendance');
		$this->db->where(array('monthyear' => $monthyear, 'a' . $columnindex => 'P', 'subjectID' => $subjectID,'schoolyearID' => $schoolyearID));
		return $this->db->count_all_results() . ' Present';
	}

	public function getAttendancele($classesID = NULL, $attdate, $subjectID = NULL)
	{
		$monthyear = date('m-Y', strtotime($attdate));
		$columnindex = date('j', strtotime($attdate));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		//print_r($columnindex);die();
		$this->db->select('*');
		$this->db->from('sub_attendance');
		$this->db->where(array('monthyear' => $monthyear, 'a' . $columnindex => 'LE', 'subjectID' => $subjectID,'schoolyearID' => $schoolyearID));
		return $this->db->count_all_results() . ' Late with Excuse';
	}

	public function getAttendancel($classesID = NULL, $attdate, $subjectID = NULL)
	{
		$monthyear = date('m-Y', strtotime($attdate));
		$columnindex = date('j', strtotime($attdate));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		//print_r($columnindex);die();
		$this->db->select('*');
		$this->db->from('sub_attendance');
		$this->db->where(array('monthyear' => $monthyear, 'a' . $columnindex => 'L', 'subjectID' => $subjectID,'schoolyearID' => $schoolyearID));
		return $this->db->count_all_results() . ' Late';
	}

	public function getAttendancea($classesID = NULL, $attdate, $subjectID = NULL,$f=false)
	{
		$monthyear = date('m-Y', strtotime($attdate));
		$columnindex = date('j', strtotime($attdate));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		//print_r($columnindex);die();
		$this->db->select('*');
		$this->db->from('sub_attendance');
		$this->db->where(array('monthyear' => $monthyear, 'a' . $columnindex => 'A', 'subjectID' => $subjectID,'schoolyearID' => $schoolyearID));
		if ($f == true) {
			return $this->db->count_all_results();
		} else {
			return $this->db->count_all_results() . ' Absent';
		}
	}

	public function getAttendancean($classesID = NULL, $attdate, $subjectID = NULL)
	{
		$monthyear = date('m-Y', strtotime($attdate));
		$columnindex = date('j', strtotime($attdate));
		//print_r($columnindex);die();
		$this->db->select('*');
		$this->db->from('sub_attendance');
		$this->db->where(array('monthyear' => $monthyear, 'a' . $columnindex => 'AN', 'subjectID' => $subjectID));
		return $this->db->count_all_results() . ' Absent with note';
	}
}
