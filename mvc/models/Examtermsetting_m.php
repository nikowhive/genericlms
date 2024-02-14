<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examtermsetting_m extends MY_Model {

	protected $_table_name     = 'examtermsetting';
	protected $_primary_key    = 'examtermsettingID';
	protected $_primary_filter = 'intval';
	protected $_order_by       = "examtermsettingID";

	function __construct() {
		parent::__construct();
		$this->load->model('exam_m');
		$this->load->model('classes_m');
		$this->load->model('subject_m');
		$this->load->model('markpercentage_m');
	}

	public function get_examtermsetting($array=NULL, $single=FALSE) {
		return parent::get($array, $single);
	}

	public function get_order_by_examtermsetting($array=NULL) {
		return parent::get_order_by($array);
	}

	public function get_single_examtermsetting($array=NULL) {
		return parent::get_single($array);
	}

	public function insert_examtermsetting($array) {
		return parent::insert($array);
	}

	public function insert_batch_examtermsetting($array) {
		return parent::insert_batch($array);
	}

	public function update_examtermsetting($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_examtermsetting($id){
		return parent::delete($id);
	}

	public function delete_examtermsetting_by_array($array=[]) {
		if(customCompute($array)) {
			$this->db->where($array);
			return $this->db->delete($this->_table_name);
		} 
		return FALSE;
	}

	public function get_latest_examtermsetting($array=[]) {
		$this->db->select('*');
		$this->db->from('examtermsetting');
		$this->db->order_by('examtermsetting.examtermsettingID DESC');
		$this->db->where($array);
		$query = $this->db->get();
		return $query->row();
	}

	public function get_examtermsettingby_classes($array=[]) {

		$this->db->select('examtermsetting.*, exam.exam');
		$this->db->from('examtermsetting');
		$this->db->join('exam', 'examtermsetting.finaltermexamID=exam.examID');
		$this->db->where($array);
		$query = $this->db->get();
		return $query->result();

	}

	

	public function get_examtermsetting_with_examtermsettingrelation1($condition = []) {
		$this->db->select('*');
		$this->db->from('examtermsetting');
		$this->db->join('examtermsettingrelation', 'examtermsetting.examtermsettingID=examtermsettingrelation.examtermsettingID');
		if(customCompute($condition)) {
				$this->db->where($condition);
		}
		$this->db->order_by('examtermsetting.examtermsettingID ASC');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_examtermsetting_with_examtermsettingrelation2($condition = []) {
		$this->db->select('examtermsetting.*, examtermsettingrelation.* , exam.exam');
		$this->db->from('examtermsetting');
		$this->db->join('examtermsettingrelation', 'examtermsetting.examtermsettingID=examtermsettingrelation.examtermsettingID');
		$this->db->join('exam', 'exam.examID=examtermsettingrelation.examID');
		if(customCompute($condition)) {
				$this->db->where($condition);
		}
		$this->db->order_by('exam.order_no ASC');
		$query = $this->db->get();
		return $query->result();
	}

}