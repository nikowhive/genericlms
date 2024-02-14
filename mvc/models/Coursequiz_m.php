<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coursequiz_m extends MY_Model {

	protected $_table_name = 'coursechapter_quiz';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id asc";

	public function __construct() 
	{
		parent::__construct();
		//$this->load->model('Unit_m');
	}
    
    public function get($id=NULL, $signal=false) 
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

	public function get_bycoursechapter($question_id, $quiz_id){
        $this->db->select('coursechapterquiz_question.*');
        $this->db->from('coursechapterquiz_question');
        $this->db->where('coursechapterquiz_question.question_id', $question_id);
        $this->db->where('coursechapterquiz_question.quiz_id', $quiz_id);
        $query = $this->db->get();
            return $query->row(); 
	}

	public function get_bycoursequiz($quiz_id){
        $this->db->select('coursechapterquiz_question.*,question_bank.question,question_bank.explanation');
        $this->db->from('coursechapterquiz_question');
        $this->db->join('question_bank', 'question_bank.questionBankID = coursechapterquiz_question.question_id', 'LEFT');
        $this->db->where('coursechapterquiz_question.quiz_id', $quiz_id);
        $query = $this->db->get();
        return $query->result(); 
	}

	public function deletequizzes($quiz_id){
        $this->db->where_in('quiz_id', $quiz_id );
        $this->db->delete('coursechapterquiz_question');
	}

	public function get_coursequiz($id)
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

	public function general_get_order_by_quiz($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

}	
