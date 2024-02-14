<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classwork_m extends MY_Model {

	protected $_table_name = 'classwork';
	protected $_primary_key = 'classworkID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "deadlinedate desc";

	function __construct() {
		parent::__construct();
	}

	function join_get_classwork_feed($arr)
	{
		$this->db->select('*');
		$this->db->from('classwork');
		$this->db->join('subject', 'subject.subjectID = classwork.subjectID AND subject.classesID = classwork.classesID', 'LEFT');
		if ($arr) {
			$this->db->where($arr);
		}
		$this->db->order_by('classwork.classworkID','desc');
		$query = $this->db->get();
		$qry = $this->db->last_query();
		return $query->result();
	}

	function join_get_classwork_feed_multiple_classes($arr, $classes)
	{
		$this->db->select('*');
		$this->db->from('classwork');
		$this->db->join('subject', 'subject.subjectID = classwork.subjectID AND subject.classesID = classwork.classesID', 'LEFT');
		if ($arr) {
			$this->db->where($arr);
		}
		if($classes) {
			$this->db->where_in('classwork.classesID', $classes);
		}
		$this->db->order_by('classwork.classworkID','desc');
		$query = $this->db->get();
		$qry = $this->db->last_query();
		return $query->result();
	}

	function join_get_classwork($classesID, $schoolyearID) {
		$this->db->select('subject.subjectID, classwork.*,u.unit_name,c.chapter_name, subjectteacher.classesID, teacher.teacherID, teacher.name as teacher_name, teacher.photo as teacher_photo');
		$this->db->from('classwork');
		$this->db->join('subject', 'subject.subjectID = classwork.subjectID AND subject.classesID = classwork.classesID', 'LEFT');
		$this->db->join('subjectteacher', 'classwork.subjectID = subjectteacher.subjectID', 'LEFT');
		$this->db->join('teacher', 'subjectteacher.teacherID = teacher.teacherID', 'LEFT');
		$this->db->join('zzz_4_units u', 'u.id = classwork.unit_id', 'LEFT');
        $this->db->join('zzz_1_chapters c', 'c.id = classwork.chapter_id', 'LEFT');
		$this->db->where('classwork.schoolyearID', $schoolyearID);
		$this->db->where('classwork.classesID', $classesID);
		$query = $this->db->get();
		return $query->result();
	}

	function get_classwork_from_subject($schoolyearID,$subjectId='') {
		$this->db->select('a.*,subject.subject,u.unit_name,c.chapter_name, classes.classesID,classes.classes as class_name');
		$this->db->from('classwork a');
		$this->db->join('subject', 'subject.subjectID = a.subjectID AND subject.classesID = a.classesID', 'LEFT');
		$this->db->join('zzz_4_units u', 'u.id = a.unit_id', 'LEFT');
		$this->db->join('zzz_1_chapters c', 'c.id = a.chapter_id', 'LEFT');
		$this->db->join('classes', 'classes.classesID = a.classesID', 'LEFT');
		$this->db->where('a.schoolyearID', $schoolyearID);
		$this->db->where_in('a.subjectID', $subjectId);
		$query = $this->db->get();
		return $query->result();
	}

	function get_classwork($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_classwork($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_classwork($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_order_by_published_classwork($array=NULL) {
		$this->db->select('*');
        $this->db->from('classwork');
		$this->db->where('is_published', 1);
		$this->db->where('unit_id', $array['unit_id']);
		$this->db->where('chapter_id', $array['chapter_id']);
		$this->db->where('schoolyearID', $array['schoolyearID']);
		$query = $this->db->get();
        return $query->result();
	}

	function get_order_by_all_classwork($array=NULL) {
		$this->db->select('*');
        $this->db->from('classwork');
		// $this->db->where('is_published', 1);
		$this->db->where('unit_id', $array['unit_id']);
		$this->db->where('chapter_id', $array['chapter_id']);
		$this->db->where('schoolyearID', $array['schoolyearID']);
		$query = $this->db->get();
        return $query->result();
	}

	function insert_classwork($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_classwork($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_classwork($id){
		parent::delete($id);
	}

    public function changeStatus($id){
        $result = parent::get_single(['classworkID'=>$id]);
        if($result) {
            $this->update_classwork(['is_published' => !$result->is_published], $id);
        } else {
            showBadRequest(400, 'Record not found for id '.$id);
        }
    }

    public function getUnitBasedClassworks($subjectId, $classId) {
        $this->db->select('u.unit_name, c.unit_id as id, u.id as unit_id, u.published');
        $this->db->from('classwork c');
        $this->db->join('zzz_4_units u', 'u.id = c.unit_id', 'LEFT');
        $this->db->where('c.unit_id IS NOT NULL');
        $this->db->where('c.subjectId', $subjectId);
        $this->db->where('c.classesId', $classId);
        $this->db->group_by('c.unit_id');
        $query = $this->db->get();
        return $query->result();
	}
	
	public function getClassworkWithUnitAndChapter($course_id, $schoolyearID, $usertype = false) {
        $this->db->select('cw.*, u.unit_name, c.chapter_name');
        $this->db->from('classwork cw');
        $this->db->join('zzz_4_units u', 'u.id = cw.unit_id', 'LEFT');
        $this->db->join('zzz_1_chapters c', 'c.id = cw.chapter_id', 'LEFT');
		// $this->db->join('courses co', 'co.subject_id = c.subject_id', 'LEFT');
		// $this->db->where('co.id', $course_id);
        $this->db->or_where('cw.course_id', $course_id);
        // $this->db->where('cw.classesId', $classId);
		$this->db->where('cw.schoolyearID', $schoolyearID);
        if($usertype==3 || $usertype==4) {
            $this->db->where('cw.is_published', true);
			// $this->db->where('u.published', true);
			// $this->db->where('c.published', true);
        }
		$this->db->order_by('cw.classworkID','desc');
        $query = $this->db->get();
        return $query->result();
    }

}
