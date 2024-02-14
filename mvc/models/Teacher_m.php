<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Studentparentteacher_m.php';

class Teacher_m extends MY_Model {

	protected $_table_name = 'teacher';
	protected $_primary_key = 'teacherID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "name asc";

	function __construct() {
		parent::__construct();
	}

	public function get_username($table, $data=NULL) {
		$query = $this->db->get_where($table, $data);
		return $query->result();
	}

	public function get_where_in_teacher($array, $key=NULL) {
		$query = parent::get_where_in($array, $key);
		return $query;
	}

	public function general_get_teacher($id=NULL, $single=FALSE) {
		$query = parent::get($id, $single);
		return $query;
	}

	public function general_get_single_teacher($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function general_get_order_by_teacher($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_teacher($id=NULL, $single=FALSE) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 3 || $usertypeID == 4) {
			$studentparentteacher = new Studentparentteacher_m;
			return $studentparentteacher->get_studentparent_teacher($id, $single);
		} else {
			$query = parent::get($id, $single);
			return $query;
		}
	}

	public function getActiveTeachers(){

		$this->db->select('*');
		$this->db->from('teacher');
		$this->db->where('active',1);
		$query = $this->db->get();
        return $query->result();

	}

	public function get_teacher_list($user_id, $type) {
		$this->db->select('teacher.*, subjectteacher.teacherID, subject.subjectID');
        $this->db->from('teacher');
		$this->db->join('subjectteacher', 'subjectteacher.teacherID = teacher.teacherID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = subjectteacher.subjectID', 'LEFT');		
		$this->db->join('student', 'student.classesID = subject.classesID', 'LEFT');
		
		if($type == 3) {
			$this->db->where('student.studentID', $user_id);
		} elseif($type == 4) {
			$this->db->where('student.parentID', $user_id);
		}
		$this->db->group_by('teacher.teacherID');
		$query = $this->db->get();
        return $query->result();
	}

	public function get_single_teacher_from_id($teacher_id, $user_id, $type) {
		$this->db->select('teacher.*, subjectteacher.teacherID, subject.subjectID');
        $this->db->from('teacher');
		$this->db->join('subjectteacher', 'subjectteacher.teacherID = teacher.teacherID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = subjectteacher.subjectID', 'LEFT');		
		$this->db->join('student', 'student.classesID = subject.classesID', 'LEFT');
		
		if($type == 3) {
			$this->db->where('student.studentID', $user_id);
		} elseif($type == 4) {
			$this->db->where('student.parentID', $user_id);
		}
		$this->db->where('teacher.teacherID', $teacher_id);
		$query = $this->db->get();
        return $query->row();
	}

	public function get_single_teacher($array) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 3 || $usertypeID == 4) {
			$studentparentteacher = new Studentparentteacher_m;
			return $studentparentteacher->get_single_studentparent_teacher($array);
		} else {
			$query = parent::get_single($array);
			return $query;
		}
	}

	public function get_individual_teacher($teacherID) {
		$table_name = $this->_table_name;
		$query = $this->db->select('*')
				->from($table_name)
				->where('teacherID', $teacherID);
		return $query->get()->row();;
	}

	public function get_order_by_teacher($array=NULL) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 3 || $usertypeID == 4) {
			$studentparentteacher = new Studentparentteacher_m;
			return $studentparentteacher->get_order_by_studentparent_teacher($array);
		} else {
			$query = parent::get_order_by($array);
			return $query;
		}
	}

	public function get_select_teacher($select = NULL, $array=[]) {
		if($select == NULL) {
			$select = 'teacherID, name, photo';
		}

		$this->db->select($select);
		$this->db->from($this->_table_name);

		if(customCompute($array)) {
			$this->db->where($array);
		}

		$query = $this->db->get();
		return $query->result();
	}

	public function insert_teacher($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_teacher($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_teacher($id){
		parent::delete($id);
	}

	public function hash($string) {
		return parent::hash($string);
	}

	public function searchTeachers($text,$limit,$start){

		$text = trim($text);
		$this->db->select('teacher.name,teacher.teacherID AS ID,teacher.photo,teacher.usertypeID,teacher.designation');
		$this->db->from('teacher');
		$this->db->join('subjectteacher', 'subjectteacher.teacherID = teacher.teacherID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = subjectteacher.subjectID', 'LEFT');		
		$this->db->join('classes', 'classes.classesID = subjectteacher.classesID', 'LEFT');		
		if($text != ''){
			$this->db->like('teacher.name', $text);
			$this->db->or_like('teacher.designation', $text);
			$this->db->or_like('subject.subject', $text);
			$this->db->or_like('classes.classes', $text);
		}
		$this->db->group_by('teacher.teacherID');
		if($limit)
            $this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result_array();

	}

	public function searchTeachersExport($text,$limit = '',$start= ''){

		$text = trim($text);
		$this->db->select('teacher.teacherID,teacher.name,teacher.designation,
		teacher.dob,teacher.photo,teacher.usertypeID,
		teacher.sex,teacher.email,teacher.phone,teacher.address');
		$this->db->from('teacher');
		$this->db->join('subjectteacher', 'subjectteacher.teacherID = teacher.teacherID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = subjectteacher.subjectID', 'LEFT');		
		$this->db->join('classes', 'classes.classesID = subjectteacher.classesID', 'LEFT');		
		if($text != ''){
			$this->db->like('teacher.name', $text);
			$this->db->or_like('teacher.designation', $text);
			$this->db->or_like('subject.subject', $text);
			$this->db->or_like('classes.classes', $text);
		}
		if($limit){
			$this->db->limit($limit, $start);
		}
		$this->db->group_by('teacher.teacherID');
		$query = $this->db->get();
		return $query->result();

	}

	public function teachersExport($filters = []){

		$this->db->select('teacher.teacherID,teacher.name,teacher.designation,
		teacher.dob,teacher.photo,teacher.usertypeID,
		teacher.sex,teacher.email,teacher.phone,teacher.address');
		$this->db->from('teacher');
		$this->db->join('subjectteacher', 'subjectteacher.teacherID = teacher.teacherID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = subjectteacher.subjectID', 'LEFT');		
		$this->db->join('classes', 'classes.classesID = subjectteacher.classesID', 'LEFT');		
	
		$this->db->group_by('teacher.teacherID');
		$query = $this->db->get();
		return $query->result();

	}

	public function getAllActiveTeachers($array){

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");

		$this->db->select('teacher.teacherID AS ID,teacher.usertypeID');
		$this->db->from('teacher');
		$this->db->where($array);
		$this->db->where("(teacherID != '$userID' OR usertypeID != '$userTypeID')");
		$query = $this->db->get();
		return $query->result_array();

	}

	public function getAllActiveTeachersDetails($name=''){

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");

		$this->db->select('teacher.teacherID AS ID,teacher.usertypeID,teacher.name,,teacher.designation,,teacher.email,,teacher.phone');
		$this->db->from('teacher');
		$this->db->where('active',1);
		$this->db->where("(teacherID != '$userID' OR usertypeID != '$userTypeID')");
		if($name){
			$this->db->like('name',$name);
		}
		$query = $this->db->get();
		return $query->result_array();

	}

	public function getAllTeachersContact($filters = [],$limit = '',$start = ''){

		$this->db->select('teacher.teacherID AS id ,teacher.name,teacher.designation,
		teacher.photo,teacher.usertypeID,
		teacher.email,teacher.phone,teacher.address');
		$this->db->from('teacher');
		$this->db->join('subjectteacher', 'subjectteacher.teacherID = teacher.teacherID', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = subjectteacher.subjectID', 'LEFT');		
		$this->db->join('classes', 'classes.teacherID = teacher.teacherID', 'LEFT');		
		$this->db->join('student', 'student.classesID = subject.classesID OR student.classesID = classes.classesID', 'LEFT');
		
		if(isset($filters['studentID'])){
          $this->db->where('student.studentID',$filters['studentID']);
		}
		if(isset($filters['teacherID'])){
			$this->db->where('teacher.teacherID != ', $filters['teacherID']);
		}

		if(isset($filters['studentIds'])){
			$this->db->where_in('student.studentID',$filters['studentIds']);
		}

		if($limit){
			$this->db->limit($limit, $start);
		}
		
		$this->db->group_by('teacher.teacherID');
		$query = $this->db->get();
		return $query->result_array();

	}

	public function teacher_count() {
		$this->db->select('count(*) as count');
		$this->db->from('teacher');
		$query = $this->db->get();
		return $query->row();
	}

}
