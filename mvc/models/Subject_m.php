<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "Teachersubject_m.php";
require_once "Studentparentsubject_m.php";

class Subject_m extends MY_Model {
	protected $_table_name = 'subject';
	protected $_primary_key = 'subjectID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classesID asc";

	function __construct() {
		parent::__construct();
	}

	public function get_join_subject($id) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teachersubject = new Teachersubject_m;
	    	return $teachersubject->get_subject_with_class($id);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentsubject = new Studentparentsubject_m;
	    	return $studentsubject->get_subject_with_class($id);
		} else {
			$this->db->select('subject.*, classes.classesID, classes.classes, classes.classes_numeric, classes.studentmaxID, classes.note');
			$this->db->from('subject');
			$this->db->join('classes', 'classes.classesID = subject.classesID', 'LEFT');
			$this->db->where('subject.classesID', $id);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function general_get_subject($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function general_get_single_subject($array) {
        $query = parent::get_single($array);
        return $query;
    }
    
	public function general_get_order_by_subject($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_optional_subject($array=NULL) {
		$query = $this->db->select('*')->from('subject')->where('classesID' , $array['classesID'])->where('type',0)->get()->result();
		return $query;
	}

	public function get_subject_except_coscholastic($array=NULL) {
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->where('classesID', $array['classesID']);
		if(isset($array['type'])) {
			$this->db->where('type', $array['type']);
		}
		$this->db->where('coscholatics !=', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_subject_only_coscholastic($array=NULL) {
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->where('classesID', $array['classesID']);
		$this->db->where('type', $array['type']);
		$this->db->where('coscholatics', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_by_class_id($class_id) {
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->where('classesID', $class_id);
		$this->db->order_by('finalmark DESC');
		$query = $this->db->order_by('order_no')->get();
		return $query->result();
	}

	public function get_subject($id=NULL, $single=FALSE) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teachersubject = new Teachersubject_m;
	    	return $teachersubject->get_teacher_subject($id, $single);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentsubject = new Studentparentsubject_m;
	    	return $studentsubject->get_studentparent_subject($id, $single);
		} else {
			$query = parent::get($id, $single);
			return $query;
		}
	}

	public function get_single_subject($array) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teachersubject = new Teachersubject_m;
	    	return $teachersubject->get_single_teacher_subject($array);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentsubject = new Studentparentsubject_m;
	    	return $studentsubject->get_single_studentparent_subject($array);
		} else {
			$query = parent::get_single($array);
        	return $query;
		}
    }
    
	public function get_order_by_subject($array=NULL) {
		$usertypeID = $this->session->userdata('usertypeID');
		if($usertypeID == 2) {
			$teachersubject = new Teachersubject_m;
	    	return $teachersubject->get_order_by_teacher_subject($array);
		} elseif($usertypeID == 3 || $usertypeID == 4) {
			$studentsubject = new Studentparentsubject_m;
	    	return $studentsubject->get_order_by_studentparent_subject($array);
		} else {
			$query = parent::get_order_by($array);
        	return $query;
		}
	}

	public function insert_subject($array) {
		return parent::insert($array);
	}

	public function update_subject($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_subject($id){
		parent::delete($id);
	}

	public function get_subjects_by_class_id($class_id) {
		$this->db->select('*')
				->from('subject')
				->where('classesID', $class_id);
		$result = $this->db->get()->result();
		return $result;
	}

	public function get_subjects_name_by_class_id($class_id) {
		$this->db->select('*')
				->from('subject s')
				->where('classesID', $class_id);
		$result = $this->db->get()->result();
		return $result;
	}

	public function get_class_id_from_subject($subject_id) {
		$this->db->select('*');
		$this->db->from('subject');
		$this->db->where('subjectID', $subject_id);
		$query = $this->db->get();
		$row = $query->row();
		return $row ? $row->classesID : NULL;
	}

	public function get_student_number($classesID = NULL){
		$this->db->select('subjectID');
		$this->db->from('subject');
		$this->db->where('subject.classesID', $classesID);
		return $this->db->count_all_results();
	}

	public function get_subject_not_in_subject_mark($array)
	{
		$this->db->select("*,finalmark As 'fullmark',order_no As 'order'");
		$this->db->from('subject');
		$this->db->where_not_in('subject.subjectID', $array);
		$result = $this->db->get()->result();
		return $result;
	}

	public function getSubjectsByTeacherID($array=[]){

		$this->db->select('subject.subjectID,subject.subject');
		$this->db->from('subjectteacher');
		$this->db->join('subject', 'subject.subjectID = subjectteacher.subjectID', 'LEFT');
		$this->db->where(array('subjectteacher.teacherID' => $array['teacherID']));
		$this->db->where(array('subjectteacher.classesID' => $array['classesID']));
		$this->db->order_by('subjectteacher.classesID');
		$subjectTeacherClassQuery = $this->db->get();
		$subjectTeacherClassResult = $subjectTeacherClassQuery->result();
		return $subjectTeacherClassResult;
	}
	
	public function get_subject_by_teacherID_classID($teacherID, $classesID) {

		$this->db->select("*");
		$this->db->from('subject');
		$this->db->join('subjectteacher', 'subject.subjectID = subjectteacher.subjectID', 'LEFT');
		$this->db->where('subjectteacher.teacherID ', $teacherID);
		$this->db->where('subjectteacher.classesID', $classesID);
		$result = $this->db->get()->result();
		return $result;
	}
}

