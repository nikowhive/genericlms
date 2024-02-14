<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_bank_m extends MY_Model {

    protected $_table_name = 'question_bank';
    protected $_primary_key = 'questionBankID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "questionBankID desc";

    function __construct() {
        parent::__construct();
    }

    function getTable() {
        return $this->_table_name;
    }

    function get_question_bank($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_question_bank($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_question_bank($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_question_bank($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_question_bank($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_question_bank($id){
        parent::delete($id);
    }

    public function get_question_bank_questionArray($array=NULL, $key=FALSE) {
        $query = parent::get_where_in($array, $key);
        return $query;
    }

    public function get_question_bank_from_chapter_ids($array=NULL) 
    {
        $table_name = $this->_table_name;
	    $query = $this->db->select('*')
                ->from($table_name);
		if(isset($array['levelID'])) {
                	$query->where('levelID', $array['levelID']);
		}
		if(isset($array['groupID'])) {
                	$query->where('groupID', $array['groupID']);
		}
		if(isset($array['chapter_ids'])) {
			$query->where_in('chapter_id', $array['chapter_ids']);
		}
		$result = $query->get()->result();

		return $result;
    }


    public function get_objective_question_bank_from_chapter_ids($limit='', $start='', $array=NULL) 
    {
        $table_name = $this->_table_name;
	    $query = $this->db->select('*')
                ->from($table_name);
		if(isset($array['levelID'])) {
                	$query->where('levelID', $array['levelID']);
		}
		if(isset($array['groupID'])) {
                	$query->where('groupID', $array['groupID']);
		}
		if(isset($array['chapter_ids'])) {
			$query->where_in('chapter_id', $array['chapter_ids']);
        }
        if(isset($array['type_id'])) {
			$query->where('type_id', $array['type_id']);
		}
        $query->where_in('typeNumber', [1,2,3]);
        if ($limit)
            $this->db->limit($limit, $start);
		$result = $query->get()->result();

		return $result;
    }


    public function get_classes_from_chapter($id){
        $this->db->select('zzz_1_chapters.*,zzz_4_units.id as unit_id,zzz_4_units.unit_name,classes.classesID,classes.classes,subject.subjectID,subject.subject');
        $this->db->from('zzz_1_chapters');
        $this->db->join('zzz_4_units', 'zzz_4_units.id = zzz_1_chapters.unit_id', 'LEFT');
        $this->db->join('subject', 'zzz_1_chapters.subject_id = subject.subjectID', 'LEFT');
        $this->db->join('classes', 'classes.classesID = subject.classesID');
        $this->db->where('zzz_1_chapters.id',$id);
        $this->db->order_by('zzz_1_chapters.id asc');
        $query = $this->db->get();
        return $query->row();
    
    }

    public function get_classes_from_course($id){
        $this->db->select('classes.classesID,classes.classes,subject.subjectID,subject.subject');
        $this->db->from('courses');
        $this->db->join('subject', 'courses.subject_id = subject.subjectID', 'LEFT');
        $this->db->join('classes', 'classes.classesID = subject.classesID');
        $this->db->where('courses.id',$id);
        $this->db->order_by('courses.id asc');
        $query = $this->db->get();
        return $query->row();
    
    }

    public function getRecentQuestion($limit, $start, $schoolYearID = null)
    {
        $this->db->select("*");
        $this->db->from($this->_table_name);
        $this->db->order_by('create_date', 'desc');
        
        if ($schoolYearID)
            $this->db->where('schoolYearID', $schoolYearID);
		
        if ($limit)
            $this->db->limit($limit, $start);
        $result = $this->db->get()->result();
        
        return $result;
    }

}
