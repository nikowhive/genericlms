<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lesson_plan_version_m extends MY_Model {

	protected $_table_name = 'lesson_plan_versions';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id asc";

	function __construct() {
		parent::__construct();
	}

	function get_lesson_plan_version($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_lesson_plan_version($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_lesson_plan_version($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_lesson_plan_version($array) {
		$id = parent::insert($array);
		return $id;
	}

	function insert_batch_lesson_plan_version($array) {
        $insert = $this->db->insert_batch($this->_table_name, $array);
        return $insert ? true:false;
    }

	function update_lesson_plan_version($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	function delete_lesson_plan_version($id){
		parent::delete($id);
	}

	function update_batchlesson_plan_version($data, $id = NULL) {
        parent::update_batch($data, $id);
        return TRUE;
    }

	public function getRows($id = ''){ 
		$this->galleryTbl='lesson_plan_version';
		$this->imgTbl='lesson_plan_media';
        $this->db->select("*, (SELECT file FROM ".$this->imgTbl." WHERE lesson_plan_version_id = ".$this->galleryTbl.".id ORDER BY id DESC LIMIT 1) as default_image"); 
        $this->db->from($this->galleryTbl); 
        if($id){ 
            $this->db->where('id', $id); 
            $query  = $this->db->get(); 
            $result = ($query->num_rows() > 0)?$query->row_array():array(); 
            if(!empty($result)){ 
                $this->db->select('*'); 
                $this->db->from($this->imgTbl); 
                $this->db->where('annual_plan_id', $result['id']); 
                $this->db->order_by('id', 'desc'); 
                $query  = $this->db->get(); 
                $result2 = ($query->num_rows() > 0)?$query->result_array():array(); 
                $result['images'] = $result2;  
            }  
        }else{ 
            $this->db->order_by('id', 'desc'); 
            $query  = $this->db->get(); 
            $result = ($query->num_rows() > 0)?$query->result_array():array(); 
        } 
         
        // return fetched data 
        return !empty($result)?$result:false; 
    } 

	

}
