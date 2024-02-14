<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Coursefiles_m extends MY_Model
{

	protected $_table_name = 'coursefiles_resources';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id asc";

	public function __construct()
	{
		parent::__construct();
	}

	public function get($id = NULL, $signal = false)
	{
		$query = parent::get($id, $signal);
		return $query;
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

	public function get_attachment_by_id($id = '')
	{
		$query = parent::get($id);
		return $query;
	}

	public function get_coursefile($id)
	{
		$table_name = $this->_table_name;

		$this->db->select($table_name . '.*, subject.subject, classes.classesID, classes.classes,zzz_1_chapters.chapter_name,zzz_1_chapters.unit');
		$this->db->from($table_name);
		$this->db->join('zzz_1_chapters', 'zzz_1_chapters.id = ' . $table_name . '.coursechapter_id', 'LEFT');
		$this->db->join('subject', 'subject.subjectID = zzz_1_chapters.subject_id', 'LEFT');
		$this->db->join('classes', 'classes.classesID = subject.classesID', 'LEFT');
		$this->db->where($table_name . '.id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_attachments_by_course($course_id = '', $usertypeid = '')
	{
		$this->db->select('cw.* , c.id As coursechapter_id, c.unit, c.unit_id,c.chapter_name');
		$this->db->from('coursefiles_resources cw');
		$this->db->join('zzz_1_chapters c', 'c.id = cw.coursechapter_id', 'LEFT');
		// $this->db->join('courses co', 'co.subject_id = c.subject_id', 'LEFT');
		// $this->db->where('co.id', $course_id)
		$this->db->or_where('cw.course_id', $course_id);
		if ($usertypeid == 3 || $usertypeid == 4) {
			$this->db->where('cw.published', 1);
		} 



		$query = $this->db->get()->result();
		return $query;
	}
}
