<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Annual_plan_m extends MY_Model
{

	protected $_table_name = 'annual_plan';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id desc";

	function __construct()
	{
		parent::__construct();
	}

	public function get_annual_plan($array = NULL, $signal = FALSE)
	{
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_order_by_annual_plan($array = NULL)
	{
		$query = parent::get_order_by($array);
		return $query;
	}

	public function get_single_annual_plan($array = NULL)
	{
		$query = parent::get_single($array);
		return $query;
	}

	public function insert_annual_plan($array)
	{
		$id = parent::insert($array);
		return $id;
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

	function insert_batch_annual($array) {
        $insert = $this->db->insert_batch($this->_table_name, $array);
        return $insert ? true:false;
    }

	public function get_annual_by_course($course_id = '', $usertypeid = '')
	{
		$this->db->select('a.*');
		$this->db->from('annual_plan a');
		// $this->db->join('annual_plan_media l', 'a.id = l.annual_plan_id', 'LEFT');
		// $this->db->join('zzz_1_chapters c', 'c.id = cw.coursechapter_id', 'LEFT');
		// $this->db->join('courses co', 'co.subject_id = c.subject_id', 'LEFT');
		$this->db->where('a.course_id', $course_id);
		// $this->db->or_where('cw.course_id', $course_id);
		 if($usertypeid == 3 || $usertypeid == 4 ) {
			$this->db->where('a.published', 1);
		}
		$query = $this->db->get()->row();
		return $query;
		
		
	}

	public function getRows($id = ''){ 
		$this->galleryTbl='annual_plan';
		$this->imgTbl='annual_plan_media';
        $this->db->select("*, (SELECT file FROM ".$this->imgTbl." WHERE annual_plan_id = ".$this->galleryTbl.".id ORDER BY id DESC LIMIT 1) as default_image"); 
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

?>