<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lesson_plan_media_m extends MY_Model
{

	protected $_table_name = 'lesson_medias';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id desc";

	function __construct()
	{
		parent::__construct();
	}

	function get_lessonmedia($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_lessonmedia($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_lessonmedia($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	function insert_lessonmedia($array)
	{
		$id = parent::insert($array);
		return $id;
	}

	function insert_batch_lessonmedia($array)
	{
		$insert = $this->db->insert_batch($this->_table_name, $array);
		return $insert ? true : false;
	}

	function update_lessonmedia($data, $id = NULL)
	{
		parent::update($data, $id);
		return $id;
	}

	function delete_lessonmedia($id)
	{
		parent::delete($id);
	}

	function update_batchlessonmedia($data, $id = NULL)
	{
		parent::update_batch($data, $id);
		return TRUE;
	}

	// function get_lesson_media_with_version_lesson($lesson_id,)
	// {
	// 	$this->db->select('m.*');
	// 	$this->db->from('lesson_medias m');

	// 	$this->db->join('lesson_plan_versions v','v.id=m.lesson_plan_version_id','left');
	// 	$this->db->where('');
	// }

	public function get_recent_lesson_medias($lesson_id, $limit = '', $start = '')
	{
		$this->db->select("*");
		$this->db->from($this->_table_name);
		$this->db->order_by('create_date', 'desc');
		// if($lesson_id){
		$this->db->where('lesson_medias.lesson_plan_id', $lesson_id);
		$this->db->join('lesson_plan_versions v', 'v.id=lesson_medias.lesson_plan_version_id', 'left');
		// }
		if ($limit)
			$this->db->limit($limit, $start);
		$result =	$this->db->get()->result();


		return $result;
	}
}
