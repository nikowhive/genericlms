<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Homework_m extends MY_Model
{

	protected $_table_name = 'homework';
	protected $_primary_key = 'homeworkID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "deadlinedate desc";

	function __construct()
	{
		parent::__construct();
	}

	function join_get_homework($classesID, $schoolyearID)
	{
		$this->db->select('subject.subjectID, homework.*,u.unit_name,c.chapter_name, subjectteacher.classesID, teacher.teacherID, teacher.name as teacher_name, teacher.photo as teacher_photo');
		$this->db->from('homework');
		$this->db->join('subject', 'subject.subjectID = homework.subjectID AND subject.classesID = homework.classesID', 'LEFT');
		$this->db->join('subjectteacher', 'homework.subjectID = subjectteacher.subjectID', 'LEFT');
		$this->db->join('teacher', 'subjectteacher.teacherID = teacher.teacherID', 'LEFT');
		$this->db->join('zzz_4_units u', 'u.id = homework.unit_id', 'LEFT');
		$this->db->join('zzz_1_chapters c', 'c.id = homework.chapter_id', 'LEFT');
		$this->db->where('homework.schoolyearID', $schoolyearID);
		$this->db->where('homework.classesID', $classesID);
		$query = $this->db->get();
		return $query->result();
	}
	function join_get_homework_feed($arr)
	{
		$this->db->select('*');
		$this->db->from('homework');
		$this->db->join('subject', 'subject.subjectID = homework.subjectID AND subject.classesID = homework.classesID', 'LEFT');
		if ($arr) {
			$this->db->where($arr);
		}
		$this->db->order_by('homework.homeworkID','desc');
		$query = $this->db->get();
		$qry = $this->db->last_query();
		return $query->result();
	}

	function join_get_homework_feed_multiple_classes($arr, $classes)
	{
		$this->db->select('*');
		$this->db->from('homework');
		$this->db->join('subject', 'subject.subjectID = homework.subjectID AND subject.classesID = homework.classesID', 'LEFT');
		if ($arr) {
			$this->db->where($arr);
		}
		if($classes) {
			$this->db->where_in('homework.classesID', $classes);
		}
		$this->db->order_by('homework.homeworkID','desc');
		$query = $this->db->get();
		$qry = $this->db->last_query();
		return $query->result();
	}

	function get_homework_from_subject($schoolyearID, $subjectId = '')
	{
		$this->db->select('a.*,subject.subject,u.unit_name,c.chapter_name, classes.classesID,classes.classes as class_name');
		$this->db->from('homework a');
		$this->db->join('subject', 'subject.subjectID = a.subjectID AND subject.classesID = a.classesID', 'LEFT');
		$this->db->join('zzz_4_units u', 'u.id = a.unit_id', 'LEFT');
		$this->db->join('zzz_1_chapters c', 'c.id = a.chapter_id', 'LEFT');
		$this->db->join('classes', 'classes.classesID = a.classesID', 'LEFT');
		$this->db->where('a.schoolyearID', $schoolyearID);
		$this->db->where_in('a.subjectID', $subjectId);
		$query = $this->db->get();
		return $query->result();
	}

	function get_homework($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_homework($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_homework($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_order_by_published_homework($array = NULL)
	{
		$this->db->select('*');
		$this->db->from('homework');
		$this->db->where('is_published', 1);
		$this->db->where('unit_id', $array['unit_id']);
		$this->db->where('chapter_id', $array['chapter_id']);
		$this->db->where('schoolyearID', $array['schoolyearID']);
		$query = $this->db->get();
		return $query->result();
	}

	function get_order_by_all_homework($array = NULL)
	{
		$this->db->select('*');
		$this->db->from('homework');
		// $this->db->where('is_published', 1);
		$this->db->where('unit_id', $array['unit_id']);
		$this->db->where('chapter_id', $array['chapter_id']);
		$this->db->where('schoolyearID', $array['schoolyearID']);
		$query = $this->db->get();
		return $query->result();
	}

	function insert_homework($array)
	{
		$error = parent::insert($array);
		return TRUE;
	}

	function update_homework($data, $id = NULL)
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete_homework($id)
	{
		parent::delete($id);
	}

	public function changeStatus($id)
	{
		$result = parent::get_single(['homeworkID' => $id]);
		if ($result) {
			$this->update_homework(['is_published' => !$result->is_published], $id);
		} else {
			showBadRequest(400, 'Record not found for id ' . $id);
		}
	}

	public function getUnitBasedHomeworks($subjectId, $classId)
	{
		$this->db->select('u.unit_name, h.unit_id as id, u.id as unit_id, u.published');
		$this->db->from('homework h');
		$this->db->join('zzz_4_units u', 'u.id = h.unit_id', 'LEFT');
		$this->db->where('h.unit_id IS NOT NULL');
		$this->db->where('h.subjectId', $subjectId);
		$this->db->where('h.classesId', $classId);
		$this->db->group_by('h.unit_id');
		$query = $this->db->get();
		return $query->result();
	}

	public function getHomeworkWithUnitAndChapter($course_id, $schoolyearID, $usertype = false)
	{
		$this->db->select('h.*, u.unit_name, c.chapter_name');
		$this->db->from('homework h');
		$this->db->join('zzz_4_units u', 'u.id = h.unit_id', 'LEFT');
		$this->db->join('zzz_1_chapters c', 'c.id = h.chapter_id', 'LEFT');
		// $this->db->where('h.subjectId', $subjectId);
		// $this->db->join('courses co', 'co.subject_id = c.subject_id', 'LEFT');
		// $this->db->where('co.id', $course_id);
		$this->db->or_where('h.course_id', $course_id);
		$this->db->where('h.schoolyearID', $schoolyearID);
		if ($usertype ==3 || $usertype ==4) {
			$this->db->where('h.is_published', true);
			// $this->db->where('u.published', true);
			// $this->db->where('c.published', true);
		}
		$this->db->order_by('h.homeworkID');
		$query = $this->db->get();
		return $query->result();
	}
}
