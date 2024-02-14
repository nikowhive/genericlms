<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lesson_plan_m extends MY_Model
{

	protected $_table_name = 'lesson_plans';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id desc";

	function __construct()
	{
		parent::__construct();
	}

	public function get_lesson_plan($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_lesson_plan($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_lesson_plan($array = NULL)
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

	public function getLessonWithUnitAndChapter($course_id = '', $usertypeid = '')
	{
		$this->db->select('a.*, u.unit_name as unit,c.chapter_name');
		$this->db->from('lesson_plans a');

		$this->db->join('zzz_4_units u', 'u.id = a.unit_id', 'LEFT');
        $this->db->join('zzz_1_chapters c', 'c.id = a.chapter_id', 'LEFT');
		// $this->db->or_where('a.course_id', $course_id);
		$this->db->where('a.course_id', $course_id);
		
		 if($usertypeid == 3 || $usertypeid == 4 ){
			$this->db->where('a.published', 1);
		}
		$query = $this->db->get()->result();
		return $query;

	}



}
