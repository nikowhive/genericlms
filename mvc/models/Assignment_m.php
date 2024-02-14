<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assignment_m extends MY_Model
{

	protected $_table_name = 'assignment';
	protected $_primary_key = 'assignmentID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "deadlinedate desc";

	function __construct()
	{
		parent::__construct();
	}

	function join_get_assignment($classesID, $schoolyearID)
	{
		$this->db->select('subject.subjectID, assignment.*,u.unit_name,c.chapter_name, subjectteacher.classesID, teacher.teacherID, teacher.name as teacher_name, teacher.photo as teacher_photo');
		$this->db->from('assignment');
		$this->db->join('subject', 'subject.subjectID = assignment.subjectID AND subject.classesID = assignment.classesID', 'LEFT');
		$this->db->join('subjectteacher', 'assignment.subjectID = subjectteacher.subjectID', 'LEFT');
		$this->db->join('teacher', 'subjectteacher.teacherID = teacher.teacherID', 'LEFT');
		$this->db->join('zzz_4_units u', 'u.id = assignment.unit_id', 'LEFT');
		$this->db->join('zzz_1_chapters c', 'c.id = assignment.chapter_id', 'LEFT');
		$this->db->where('assignment.schoolyearID', $schoolyearID);
		$this->db->where('assignment.classesID', $classesID);
		$query = $this->db->get();
		return $query->result();
	}

	function get_assignment_from_subject($schoolyearID, $subjectId = '')
	{
		$this->db->select('a.*,subject.subject,u.unit_name,c.chapter_name, classes.classesID,classes.classes as class_name');
		$this->db->from('assignment a');
		$this->db->join('subject', 'subject.subjectID = a.subjectID AND subject.classesID = a.classesID', 'LEFT');
		$this->db->join('zzz_4_units u', 'u.id = a.unit_id', 'LEFT');
		$this->db->join('zzz_1_chapters c', 'c.id = a.chapter_id', 'LEFT');
		$this->db->join('classes', 'classes.classesID = a.classesID', 'LEFT');
		$this->db->where('a.schoolyearID', $schoolyearID);
		$this->db->where_in('a.subjectID', $subjectId);
		$query = $this->db->get();
		return $query->result();
	}


	function join_get_assignment_feed($arr)
	{
		$this->db->select('*');
		$this->db->from('assignment');
		$this->db->join('subject', 'subject.subjectID = assignment.subjectID AND subject.classesID = assignment.classesID', 'LEFT');
		if ($arr) {
			$this->db->where($arr);
		}
		$this->db->order_by('assignment.assignmentID', 'desc');
		$query = $this->db->get();
		$qry = $this->db->last_query();
		return $query->result();
	}

	
	function join_get_assignment_feed_multiple_classes($arr, $classes)
	{
		$this->db->select('*');
		$this->db->from('assignment');
		$this->db->join('subject', 'subject.subjectID = assignment.subjectID AND subject.classesID = assignment.classesID', 'LEFT');
		if ($arr) {
			$this->db->where($arr);
		}
		if ($classes) {
			$this->db->where_in('assignment.classesID',$classes);
		}
		$this->db->order_by('assignment.assignmentID', 'desc');
		$query = $this->db->get();
		$qry = $this->db->last_query();
		return $query->result();
	}

	function get_assignment($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_assignment($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_assignment($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_order_by_published_assignment($array = NULL)
	{
		$this->db->select('*');
		$this->db->from('assignment');
		$this->db->where('is_published', 1);
		$this->db->where('unit_id', $array['unit_id']);
		$this->db->where('chapter_id', $array['chapter_id']);
		$this->db->where('schoolyearID', $array['schoolyearID']);
		$query = $this->db->get();
		return $query->result();
	}

	function get_order_by_all_assignment($array = NULL)
	{
		$this->db->select('*');
		$this->db->from('assignment');
		// $this->db->where('is_published', 1);
		$this->db->where('unit_id', $array['unit_id']);
		$this->db->where('chapter_id', $array['chapter_id']);
		$this->db->where('schoolyearID', $array['schoolyearID']);
		$query = $this->db->get();
		return $query->result();
	}

	function insert_assignment($array)
	{
		$error = parent::insert($array);
		return TRUE;
	}

	function update_assignment($data, $id = NULL)
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_assignment($id)
	{
		parent::delete($id);
	}

	public function changeStatus($id)
	{
		$result = parent::get_single(['assignmentID' => $id]);
		if ($result) {
			$this->update_assignment(['is_published' => !$result->is_published], $id);
		} else {
			showBadRequest(400, 'Record not found for id ' . $id);
		}
	}

	public function getUnitBasedAssignments($subjectId, $classId)
	{
		$this->db->select('u.unit_name, a.unit_id as id, u.id as unit_id, u.published');
		$this->db->from('assignment a');
		$this->db->join('zzz_4_units u', 'u.id = a.unit_id', 'LEFT');
		$this->db->where('a.unit_id IS NOT NULL');
		$this->db->where('a.subjectId', $subjectId);
		$this->db->where('a.classesId', $classId);
		$this->db->group_by('a.unit_id');
		$query = $this->db->get();
		return $query->result();
	}

	public function getAssignmentWithUnitAndChapter($course_id, $schoolyearID, $usertype = false)
	{
		$this->db->select('a.*, u.unit_name,u.published as unit_published, c.chapter_name, c.published as chapter_published');
		$this->db->from('assignment a');
		$this->db->join('zzz_4_units u', 'u.id = a.unit_id', 'LEFT');
		$this->db->join('zzz_1_chapters c', 'c.id = a.chapter_id', 'LEFT');
		// $this->db->join('courses co', 'co.subject_id = c.subject_id', 'LEFT');
		// $this->db->where('co.id', $course_id);
		$this->db->or_where('a.course_id', $course_id);
		$this->db->where('a.schoolyearID', $schoolyearID);
		if ($usertype == 3 || $usertype == 4) {
			$this->db->where('a.is_published', true);
			// $this->db->where('u.published', true);
			// $this->db->where('c.published', true);
		}
		$this->db->order_by('a.assignmentID', 'desc');
		$query = $this->db->get();
		return $query->result();
	}
}
