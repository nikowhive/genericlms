<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once 'Teacherclasses_m.php';
require_once 'Studentparentclasses_m.php';

class Classes_m extends MY_Model
{

	protected $_table_name = 'classes';
	protected $_primary_key = 'classesID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "classes_numeric asc";

	function __construct()
	{
		parent::__construct();
	}

	public function getClassByID($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function get_join_classes()
	{
		$usertypeID = $this->session->userdata('usertypeID');
		if ($usertypeID == 2) {
			$teacherclass = new Teacherclasses_m;
			return $teacherclass->get_teacher_with_class();
		} elseif ($usertypeID == 3 || $usertypeID == 4) {
			$studentparentclasses = new Studentparentclasses_m;
			return $studentparentclasses->get_studentparent_with_class();
		} else {
			$this->db->select('*');
			$this->db->from('classes');
			$this->db->join('teacher', 'classes.teacherID = teacher.teacherID', 'LEFT');
			$this->db->order_by('classes_numeric asc');
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function general_get_classes($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function general_get_order_by_classes($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function general_get_single_classes($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function get_classes($id = NULL, $signal = false)
	{
		$usertypeID = $this->session->userdata('usertypeID');
		if ($usertypeID == 2) {
			$teacherclass = new Teacherclasses_m;
			return $teacherclass->get_teacher_class($id, $signal);
		} elseif ($usertypeID == 3 || $usertypeID == 4) {
			$studentparentclasses = new Studentparentclasses_m;
			return $studentparentclasses->get_studentparent_class($id, $signal);
		} else {
			$query = parent::get($id, $signal);
			return $query;
		}
	}

	public function get_limit_classes($classgroupID = NULL, $limit = '', $start = '')
	{
		$usertypeID = $this->session->userdata('usertypeID');
		$this->db->select('c.*');
		$this->db->from('classes c');
		$this->db->join('classgroup cg', 'c.classgroupID = cg.classgroupID', 'LEFT');
		$this->db->where('c.classgroupID', $classgroupID);
		$this->db->where('c.status', 'published');
		// $this->db->order_by('classes.classesID desc');
		if ($limit)
			$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_classes_with_extra_information()
	{
		
		// $usertypeID = $this->session->userdata('usertypeID');
		$this->db->select('*');
		$this->db->from('classes c');
		$this->db->join('classes_extra_information ci', 'c.classesID = ci.classes_id', 'LEFT');
		// $this->db->where('c.classgroupID', $classgroupID);
		$this->db->where('c.status', 'published');
		// $this->db->order_by('classes.classesID desc');

		$query = $this->db->get();
		// dd($query->result());
		return $query->result();
	}


	public function get_classes_enrollments()
	{
		$this->db->select('c.classes,e.*,cg.group,cg.classgroupID');
		$this->db->from('class_enrollments_relation cer');
		$this->db->join('enrollment e', 'e.id = cer.enrollment_id', 'LEFT');
		$this->db->join('classes c', 'c.classesID = cer.class_id', 'LEFT');
		$this->db->join('classgroup cg', 'c.classgroupID = cg.classgroupID', 'LEFT');
		$this->db->where('c.status', 'published');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_single_classes($array = NULL)
	{
		$usertypeID = $this->session->userdata('usertypeID');
		if ($usertypeID == 2) {
			$teacherclass = new Teacherclasses_m;
			return $teacherclass->get_single_teacher_class($array);
		} elseif ($usertypeID == 3 || $usertypeID == 4) {
			$studentparentclasses = new Studentparentclasses_m;
			return $studentparentclasses->get_single_studentparent_class($array);
		} else {
			$query = parent::get_single($array);
			return $query;
		}
	}

	public function get_order_by_classes($array = NULL)
	{
		$usertypeID = $this->session->userdata('usertypeID');
		if ($usertypeID == 2) {
			$teacherclass = new Teacherclasses_m;
			return $teacherclass->get_order_by_teacher_class($array);
		} elseif ($usertypeID == 3 || $usertypeID == 4) {
			$studentparentclasses = new Studentparentclasses_m;
			return $studentparentclasses->get_order_by_studentparent_class($array);
		} else {
			$query = parent::get_order_by($array);
			return $query;
		}
	}

	public function get_order_by_classes_only_kg($array = NULL)
	{
		$usertypeID = $this->session->userdata('usertypeID');
		if ($usertypeID == 2) {
			$teacherclass = new Teacherclasses_m;
			$type = 'primary';
			return $teacherclass->get_order_by_teacher_class($array, $type);
		} elseif ($usertypeID == 3 || $usertypeID == 4) {
			$studentparentclasses = new Studentparentclasses_m;
			return $studentparentclasses->get_order_by_studentparent_class($array);
		} else {
			$this->db->from($this->_table_name)->like('classes', 'kg')->or_like('classes', 'nursery')->order_by($this->_order_by);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function get_order_by_classes_except_kg($array = NULL)
	{
		$usertypeID = $this->session->userdata('usertypeID');
		if ($usertypeID == 2) {
			$teacherclass = new Teacherclasses_m;
			$type = 'not_primary';
			return $teacherclass->get_order_by_teacher_class($array, $type);
		} elseif ($usertypeID == 3 || $usertypeID == 4) {
			$studentparentclasses = new Studentparentclasses_m;
			return $studentparentclasses->get_order_by_studentparent_class($array);
		} else {
			$this->db->from($this->_table_name)->not_like('classes', 'kg')->not_like('classes', 'nursery')->order_by($this->_order_by);
			$query = $this->db->get();
			return $query->result();
		}
	}

	public function check_kg($class_id)
	{
		$this->db->select('*')->from('classes')->where('classesID', $class_id);
		$query = $this->db->get();
		$row = $query->row();
		if (stristr($row->classes, 'kg') || stristr($row->classes, 'nursery')) {
			return true;
		}
		return false;
	}

	public function insert_classes($array)
	{
		$error = parent::insert($array);
		return TRUE;
	}

	public function update_classes($data, $id = NULL)
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_classes($id)
	{
		parent::delete($id);
	}

	public function get_order_by_numeric_classes()
	{
		$this->db->select('*')->from('classes')->order_by('classes_numeric asc');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_class_by_teacher()
	{
		$this->db->select('*');
		$this->db->from('classes');
		if ($this->session->userdata('usertypeID') == 2) {
			$this->db->where(array('teacherID' => $this->session->userdata('loginuserID')));
		}
		$query = $this->db->get();
		return $query->row();
	}

	public function get_section_by_teacher()
	{
		$this->db->select('*');
		$this->db->from('section');
		$this->db->where(array('teacherID' => $this->session->userdata('loginuserID')));
		$query = $this->db->get();
		return $query->row();
	}

	public function get_classes_by_teacher(){
		$this->db->select('*');
		$this->db->from('classes');
		if($this->session->userdata('usertypeID') == 2){
		    $this->db->where(array('teacherID'=>$this->session->userdata('loginuserID')));
	    }
		$query = $this->db->get();
		return $query->result();
	}
}
