<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Daily_plan_m extends MY_Model
{

	protected $_table_name = 'daily_plans';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id desc";

	function __construct()
	{
		parent::__construct();
	}

	public function get_daily_plan($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_daily_plan($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_daily_plan($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function insert($array)
	{
		$id = parent::insert($array);
		return $id;
	}

	public function update($data, $id = NULL)
	{
		parent::update($data, $id);
		return $id;
	}

	public function delete($id)
	{
		parent::delete($id);
	}

	function insert_batch($array)
	{
		$insert = $this->db->insert_batch($this->_table_name, $array);
		return $insert ? true : false;
	}

	public function get_daily_plan_by_create_date($course_id = '', $usertypeid = '')
	{
		$this->db->select('a.*, u.unit_name as unit,c.chapter_name');
		$this->db->from('daily_plans a');
		$this->db->join('zzz_4_units u', 'u.id = a.unit_id', 'LEFT');
        $this->db->join('zzz_1_chapters c', 'c.id = a.chapter_id', 'LEFT');
		$this->db->where('a.course_id', $course_id);
		$this->db->order_by('a.create_date', "desc");
		if ($usertypeid == 3 || $usertypeid == 4) {
			// $this->db->where('a.published', 1);
		}
		$query = $this->db->get()->result();
		return $query;
	}

	public function get_all_daily_plans($teacherID = '',$usertypeid = '',$filters = [])
	{

		$this->db->select('a.*, u.unit_name as unit,c.chapter_name,cr.coursename,cl.classes as classes_name,sub.subject as subject_name,
		lp.title as lession_title');
		$this->db->from('daily_plans a');
		$this->db->join('courses cr', 'cr.id = a.course_id', 'LEFT');
		$this->db->join('subject sub', 'sub.subjectID = a.subject_id', 'LEFT');
		$this->db->join('classes cl', 'cl.classesID  = a.classesID', 'LEFT');
		$this->db->join('lesson_plans lp', 'lp.id = a.lesson_id', 'LEFT');
		$this->db->join('zzz_4_units u', 'u.id = a.unit_id', 'LEFT');
        $this->db->join('zzz_1_chapters c', 'c.id = a.chapter_id', 'LEFT');
		
		if ($usertypeid == 2) {
			$this->db->where('a.teacherID', $teacherID);
		}elseif($usertypeid == 3){
			$classID = $this->session->userdata('classesID');
			$this->db->where('a.classesID', $classID);
		}
		if ($usertypeid == 3 || $usertypeid == 4) {
			// $this->db->where('a.published', 1);
		}
		if(isset($filters['date']) && $filters['date'] != ''){
			$this->db->where('a.create_date >=', $filters['date'].' 00:00:00');
            $this->db->where('a.create_date <=', $filters['date'].' 23:59:59');
		}
		$this->db->order_by('a.create_date', "desc");
		return $query = $this->db->get()->result();
		// $error = $this->db->error();
	    // dd($error);

	}

	public function get_all_daily_plans_api($teacherID = '',$usertypeid = '',$filters = [])
	{


		$this->db->select('a.*, u.unit_name as unit,c.chapter_name,cr.coursename,cl.classes as classes_name,sub.subject as subject_name,
		lp.title as lession_title');
		$this->db->from('daily_plans a');
		$this->db->join('courses cr', 'cr.id = a.course_id', 'LEFT');
		$this->db->join('subject sub', 'sub.subjectID = a.subject_id', 'LEFT');
		$this->db->join('classes cl', 'cl.classesID  = a.classesID', 'LEFT');
		$this->db->join('lesson_plans lp', 'lp.id = a.lesson_id', 'LEFT');
		$this->db->join('zzz_4_units u', 'u.id = a.unit_id', 'LEFT');
        $this->db->join('zzz_1_chapters c', 'c.id = a.chapter_id', 'LEFT');
		
		if ($usertypeid == 2) {
			$this->db->where('a.teacherID', $teacherID);
		}elseif($usertypeid == 3){
			if(isset($filters['class_id']) && $filters['class_id'] != ''){
			    $this->db->where('a.classesID', $filters['class_id']);
			}
		}
		if ($usertypeid == 3 || $usertypeid == 4) {
			// $this->db->where('a.published', 1);
		}
		if(isset($filters['date']) && $filters['date'] != ''){
			$this->db->where('a.create_date >=', $filters['date'].' 00:00:00');
            $this->db->where('a.create_date <=', $filters['date'].' 23:59:59');
		}
		$this->db->order_by('a.create_date', "desc");
		return $query = $this->db->get()->result();
		// $error = $this->db->error();
	    // dd($error);

	}

	public function get_single_daily_plan_detail($id)
	{
		$this->db->select('a.*, u.unit_name as unit,c.chapter_name,cr.coursename,cl.classes as classes_name,sub.subject as subject_name,
		lp.title as lession_title');
		$this->db->from('daily_plans a');
		$this->db->join('courses cr', 'cr.id = a.course_id', 'LEFT');
		$this->db->join('subject sub', 'sub.subjectID = a.subject_id', 'LEFT');
		$this->db->join('classes cl', 'cl.classesID  = a.classesID', 'LEFT');
		$this->db->join('lesson_plans lp', 'lp.id = a.lesson_id', 'LEFT');
		$this->db->join('zzz_4_units u', 'u.id = a.unit_id', 'LEFT');
        $this->db->join('zzz_1_chapters c', 'c.id = a.chapter_id', 'LEFT');
		$this->db->where('a.id', $id);
		
		return $query = $this->db->get()->row();

	}

	
}
