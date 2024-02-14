<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Online_exam_question_m extends MY_Model {

    protected $_table_name = 'online_exam_question';
    protected $_primary_key = 'onlineExamQuestionID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "onlineExamQuestionID asc";

    function __construct() {
        parent::__construct();
    }

    function get_online_exam_question($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_question_ids_from_exam_ids($exam_id) {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->where('onlineExamId', $exam_id);
        $query = $this->db->get();
        $data = $query->result();
        $return = [];
        foreach($data as $d){
            $return[] = $d->questionID;
        }

        return $return;
    }

    function get_single_online_exam_question($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_online_exam_question($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_online_exam_question($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_online_exam_question($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_online_exam_question($id){
        parent::delete($id);
    }

    public function getexamtype($id)
    {
        $query = "select typeNumber from question_bank as B  join online_exam_question OQ ON B.questionBankID = OQ.questionID where OQ.onlineExamID = ".$id."";
        $result = $this->db->query($query);
        return $result->row()->typeNumber;
    }
}
