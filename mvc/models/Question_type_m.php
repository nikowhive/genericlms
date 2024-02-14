<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_type_m extends MY_Model {

    protected $_table_name = 'question_type';
    protected $_primary_key = 'questionTypeID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "questionTypeID asc";

    function __construct() {
        parent::__construct();
    }

    function get_question_type_from_question_id($question_type_id) {
        $table_name = $this->_table_name;
        $this->db->select('*')
                ->from($table_name)
                ->where('questionTypeID', $question_type_id);

        $result = $this->db->get()->row();

        
        $result = $result ? $result->typeNumber : NULL;
        
        if(is_null($result)) {
            throw new Exception('Invalid question type id');
        }

        return $result;
    }

    function get_question_type($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_question_type($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_question_type($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function get_order_by_question_type_except_subjective($array=NULL) {
        $table_name = $this->_table_name;
        $this->db->select('*')
                ->from($table_name)
                ->where_not_in('typeNumber', [4, 5]);

        $result = $this->db->get()->result();
        return $result;
    }

    function insert_question_type($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_question_type($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_question_type($id){
        parent::delete($id);
    }
}
