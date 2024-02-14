<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once "Classes_m.php";

class Chapter_m extends MY_Model
{

    protected $_table_name = 'zzz_1_chapters';
    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by = "chapter_name asc";
    // public $units = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function getTable()
    {
        return $this->_table_name;
    }

    public function get_join_chapters($id)
    {
        $table_name = $this->_table_name;

        $this->db->select($table_name . '.*, subject.subject');
        $this->db->from($table_name);
        $this->db->join('subject', 'subject.subjectID = ' . $table_name . '.subject_id', 'LEFT');
        $this->db->where('subject.ClassesID', $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_chapters($id)
    {
        $table_name = $this->_table_name;

        $this->db->select($table_name . '.*, subject.subject, classes.classesID, classes.classes');
        $this->db->from($table_name);
        $this->db->join('subject', 'subject.subjectID = ' . $table_name . '.subject_id', 'LEFT');
        $this->db->join('classes', 'classes.classesID = subject.classesID', 'LEFT');
        $this->db->where($table_name . '.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_join_where_subject($id)
    {
        $this->db->select('*');
        $this->db->from('subject');
        $this->db->join('classes', 'classes.ClassesID = subject.classesID', 'LEFT');
        $this->db->where("subject.classesID", $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_subject_from_chapter_id($chapter_id)
    {
        $table_name = $this->_table_name;
        $this->db->select('*')
            ->from($table_name)
            ->where('id', $chapter_id);

        $result = $this->db->get()->row();

        return $result ? $result->subject_id : null;
    }

    public function get_subject_from_class($class_id)
    {
        $this->db->select('*')
            ->from('subject')
            ->where('classesID', $class_id);

        $result = $this->db->get()->result();

        return $result;
    }

    public function get_unit_from_chapter_id($chapter_id)
    {
        $table_name = $this->_table_name;
        $this->db->select('*')
            ->from($table_name)
            ->where('id', $chapter_id);

        $result = $this->db->get()->row();

        return $result ? $result->unit_id : null;
    }

    public function get_chapters_from_unit_id($unit_id)
    {
        $table_name = $this->_table_name;
        $this->db->select('*')
            ->from($table_name)
            ->where('unit_id', $unit_id);

        $result = $this->db->get()->row();

        return $result ? $result->unit_id : null;
    }

    public function get_chapters_from_unit($unit_id)
    {
        $table_name = $this->_table_name;
        $this->db->select('*')
            ->from($table_name)
            ->where('unit_id', $unit_id);

        $result = $this->db->get()->result();

        return $result;
    }

    public function get_chapters_from_unit_api($unit_id)
    {
        $table_name = $this->_table_name;
        $this->db->select('*')
            ->from($table_name)
            ->where('unit_id', $unit_id)
            ->where('published', 1);

        $result = $this->db->get()->result();

        return $result;
    }

    public function get_chapter($array = null, $signal = false)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_subject($array = null, $signal = false)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_subject($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_subject($array = null)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_chapter($array)
    {
        $error = parent::insert($array);

        return true;
    }

    public function update_chapter($data, $id = null)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_chapter($id)
    {
        parent::delete($id);
    }

    public function get_ids_from_subject_id($subject_id)
    {
        $table_name = $this->_table_name;
        $query = $this->db->select('id')
            ->from($table_name)
            ->where('subject_id', $subject_id);
        $array = $query->get()->result_array();
        $arr = array_column($array, "id");
        return $arr;
    }

    public function get_chapters_count_by_subject_id($subject_id)
    {
        $table_name = $this->_table_name;
        $this->db->select('count(*) as count')
            ->from($table_name)
            ->where('subject_id', $subject_id);

        $result = $this->db->get()->row();
        return $result;
    }

    public function get_ids_from_subject_ids($subject_ids)
    {
        $table_name = $this->_table_name;
        $query = $this->db->select('id')
            ->from($table_name)
            ->where_in('subject_id', $subject_ids);
        $array = $query->get()->result_array();
        $arr = array_column($array, "id");
        return $arr;
    }

    public function get_terminal_from_class($class_id)
    {
        $this->db->select('*,')
            ->from('online_exam')
            ->where('classID', $class_id);
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_subject_from_terminal($class_id)
    {
        $this->db->select('*')
            ->from('online_exam')
            ->where('classID', $class_id);
        $result = $this->db->get()->result();
        return $result;
    }

    public function marksheetdetails($mid)
    {
        $this->db->select('*')
            ->from('marksheet_details')
            ->where('marksheet_id', $mid);
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_chapter_from_subject_id($subject_id)
    {
        $table_name = $this->_table_name;
        $this->db->select('*')
            ->from($table_name)
            ->where('subject_id', $subject_id);
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_chapter_from_unit_id($unit_id)
    {
        $table_name = $this->_table_name;
        $this->db->select('*')
            ->from($table_name)
            ->where('unit_id', $unit_id);
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_single_chapter($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

}
