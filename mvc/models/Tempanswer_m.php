<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tempanswer_m extends MY_Model {

	protected $_table_name = 'zzz_6_temp_answer';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id asc";

	public function __construct() 
	{
		parent::__construct();
	}

	public function get_user_temp_answers($array) 
	{
		$this->db->select('zzz_6_temp_answer.*,question_bank.typeNumber')->from('zzz_6_temp_answer');
		$this->db->join('question_bank', 'question_bank.questionBankID = zzz_6_temp_answer.question_id', 'LEFT');
		$this->db->where($array);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_alluser_temp_subjective_answers($array) 
	{
		$this->db->select('zzz_6_temp_subjective_answer.*,question_bank.typeNumber')->from('zzz_6_temp_subjective_answer');
		$this->db->join('question_bank', 'question_bank.questionBankID = zzz_6_temp_subjective_answer.question_id', 'LEFT');
		$this->db->where($array);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_single_temp_answer($array) 
	{
		if($array != NULL) {
			$this->db->select()->from($this->_table_name)->where($array);
			$query = $this->db->get();
			return $query->row();
		} else {
			$this->db->select()->from($this->_table_name)->order_by($this->_order_by);
			$query = $this->db->get();
			return $query->result();
		}
		return $query;
	}


	public function get_single_temp_subjective_answer($array) 
	{
		if($array != NULL) {
			$this->db->select()->from('zzz_6_temp_subjective_answer')->where($array);
			$query = $this->db->get();
			return $query->row();
		} else {
			$this->db->select()->from('zzz_6_temp_subjective_answer')->order_by($this->_order_by);
			$query = $this->db->get();
			return $query->result();
		}
		return $query;
	}

	public function get_user_temp_subjective_answers($array) 
	{
		$this->db->select()->from('zzz_6_temp_subjective_answer')->where($array);
		$query = $this->db->get();
		return $query->result();
	}

	
	function insert_temp_answer($array) {
		$this->db->insert($this->_table_name, $array);
		$id = $this->db->insert_id();
		return TRUE;
	}

	public function update_temp_answer($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	function insert_temp_subjective_answer($array) {
		$this->db->insert('zzz_6_temp_subjective_answer', $array);
		$id = $this->db->insert_id();
		return TRUE;
	}

	public function get_user_temp_subjective_file($array) 
	{
		$this->db->select()->from('zzz_6_temp_subjective_files')->where($array);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_user_temp_subjective_files($array) 
	{
		$this->db->select()->from('zzz_6_temp_subjective_files')->where($array);
		$query = $this->db->get();
		return $query->result();
	}

	function insert_batch_temp_subjective_files($array) {
		$this->db->insert_batch('zzz_6_temp_subjective_files', $array);
		return $id = $this->db->insert_id();
	}

	function insert_temp_subjective_files($array) {
		$this->db->insert('zzz_6_temp_subjective_files', $array);
		$id = $this->db->insert_id();
		return $id;
	}

	public function update_temp_subjective_answer($data, $id = NULL) {
		$this->db->where('id', $id);
        $this->db->update('zzz_6_temp_subjective_answer', $data);
		return $id;
	}

	public function delete_temp_answer($user_id, $exam_id)
	{
		$this->db->where('exam_id', $exam_id)->where('user_id', $user_id);
        $this->db->delete($this->_table_name); 
        return TRUE;
	}

	public function delete_temp_subjective_answer($user_id, $exam_id)
	{
		$this->db->where('exam_id', $exam_id)->where('user_id', $user_id);
        $this->db->delete('zzz_6_temp_subjective_answer'); 
        return TRUE;
	}

	public function delete_temp_subjective_files($user_id, $exam_id)
	{
		$this->db->where('exam_id', $exam_id)->where('user_id', $user_id);
        $this->db->delete('zzz_6_temp_subjective_files'); 
        return TRUE;
	}

	public function delete_temp_subjective_file($id)
	{
		$this->db->where('id', $id);
        $this->db->delete('zzz_6_temp_subjective_files'); 
        return TRUE;
	}

	public function get_result($array=NULL)
	{
		$this->db->select('student.*, zzz_6_temp_answer.user_id');
		$this->db->from('student');
		$this->db->join('zzz_6_temp_answer', 'student.studentID = zzz_6_temp_answer.user_id');
		$this->db->group_by('student.studentID');
		$students = $this->db->get()->result();

		foreach($students as $student) {
			$this->db->select('*');
			$this->db->from('zzz_6_temp_answer');
			$this->db->where('report_calculated', false);
			$this->db->where('user_id', $student->studentID);
			$this->db->where('exam_id', $array['onlineexamID']);
			$query = $this->db->get();

			if($query->num_rows() > 1) {
				$temp_answers = $query->result();
				$mark = 0;
				$correctAnswer = 0;

				foreach($temp_answers as $ans) 
				{
					$optionid = null;
					if($ans->optionid1 != null) {
						$optionid = $ans->optionid1;
					} elseif($ans->optionid2 != null) {
						$optionid = $ans->optionid2;
					} elseif($ans->optionid3 != null) {
						$optionid = $ans->optionid3;
					} elseif($ans->optionid4 != null) {
						$optionid = $ans->optionid4;
					}

					$this->db->select('question_answer.*');
					$this->db->from('question_answer');
					$this->db->where('questionID', $ans->question_id);
					$this->db->where('optionID', $optionid);
					$temp_ans = $this->db->get()->row();
					if($temp_ans != null) {
						$this->db->select('question_bank.*');
						$this->db->from('question_bank');
						$this->db->where('questionBankID', $ans->question_id);
						$question_bank  = $this->db->get()->row();
						$mark += $question_bank->mark;
						$correctAnswer += 1;
					}
					$this->db->where('id', $ans->id);
					$this->db->update('zzz_6_temp_answer', ['report_calculated' => true]);
				}
				$array = [
					'online_exam_id' => $array['onlineexamID'],
					'total_answer' => count($temp_answers),
					'user_id' => $student->studentID,
					'class_id' => $student->classesID,
					'section_id' => $student->sectionID,
					'correct_answers' => $correctAnswer,
					'obtained_mark' => $mark,
				];
				$insert = $this->db->insert('zzz_7_temp_report', $array);
			}
		}

		$this->db->select('zzz_7_temp_report.*, studentextend.studentID, studentextend.studentgroupID, studentgroup.group, student.studentID, student.name, student.roll, student.registerNO, classes.classesID, classes.classes');
		$this->db->from('zzz_7_temp_report');
		$this->db->join('studentextend', 'studentextend.studentID = zzz_7_temp_report.user_id', 'LEFT');
		$this->db->join('studentgroup', 'studentextend.studentgroupID = studentgroup.studentgroupID', 'LEFT');
		$this->db->join('classes', 'classes.classesID = zzz_7_temp_report.class_id', 'LEFT');
		$this->db->join('student', 'student.studentID = zzz_7_temp_report.user_id', 'LEFT');
		if(isset($array['onlineexamID'])) {
			$this->db->where('zzz_7_temp_report.online_exam_id', $array['onlineexamID']);
		}
		if(isset($array['classesID'])) {
			$this->db->where('zzz_7_temp_report.class_id', $array['classesID']);
		}
		if(isset($array['sectionID'])) {
			$this->db->where('zzz_7_temp_report.section_id', $array['sectionID']);
		}
		if(isset($array['userID'])) {
			$this->db->where('zzz_7_temp_report.user_id', $array['userID']);
		}
		$query = $this->db->get();
		return $query->result_array();
	}
}	
