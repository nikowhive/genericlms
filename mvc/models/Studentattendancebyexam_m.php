<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentattendancebyexam_m extends MY_Model {

	protected $_table_name = ' studentattendancebyexam';
	protected $_primary_key = 'studentattendancebyexamID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "studentattendancebyexamID asc";


	function __construct() {
		parent::__construct();
	}
 
	public function insert_studentattendancebyExam($array) {
		parent::insert($array);
		$insert_id = $this->db->insert_id();
        return  $insert_id;
	}

	public function update_studentattendancebyExam($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}


	public function insert_studentattendancebyExamDetails($data) {
		return $this->db->insert_batch('studentattendancebyexamdetails', $data);
	}

	public function update_studentattendancebyExamDetails($data) {
		return $this->db->update_batch('studentattendancebyexamdetails', $data,'ID');
	}

    public function getStudentAttendanceByExam($data)
	{
		$query = parent::get_single($data);
		return $query;
	}

	public function getStudentAttendanceByExamDetail($data)
	{
		$this->db->select('studentattendancebyexamdetails.ID,studentattendancebyexamdetails.presentdays');
		$this->db->from('studentattendancebyexamdetails');
		$this->db->where('studentattendancebyexamdetails.studentattendancebyexamID', $data['id']);
		$this->db->where('studentattendancebyexamdetails.studentID', $data['studentID']);
		$query = $this->db->get();
		return $query->row();
	}
    
    public function checkRecord($array){
        $query = parent::get_single($array);
		return $query;
	}
	
}