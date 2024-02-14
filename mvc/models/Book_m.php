<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Book_m extends MY_Model {

	protected $_table_name = 'book';
	protected $_primary_key = 'bookID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "bookID desc";

	function __construct() {
		parent::__construct();
		// Set table name
        $this->table = 'book';
        // Set orderable column fields
        $this->column_order = array(null, 'book','author','subject_code','price','quantity','rack');
        // Set searchable column fields
        $this->column_search = array('book','author','subject_code');
        // Set default order
        $this->order = array('book' => 'asc');
	}

	function get_book($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_addtional_book_detail($id) {
		$this->db->select('*');
		$this->db->from('book_addtional_fields');
		$this->db->where('bookID', $id);
		$query = $this->db->get();
		return $query->row();
	}

	function get_order_by_book($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_book($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_book($array) {
		$error = parent::insert($array);
		$insert_id = $this->db->insert_id();
        return  $insert_id;
	}

	function update_book($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_book($id){
		parent::delete($id);
	}

	function allbook($book) {
		$query = $this->db->query("SELECT * FROM book WHERE book LIKE '$book%'");
		return $query->result();
	}

	function allauthor($author) {
		$query = $this->db->query("SELECT * FROM book WHERE author LIKE '$author%'");
		return $query->result();
	}

	public function insert_addtionalBookDetails($data) {
		return $this->db->insert('book_addtional_fields', $data);
	}
	public function update_addtionalBookDetails($data,$id) {
		return $this->db->update('book_addtional_fields', $data,$id);
	}

	public function insert_bookKeywords($data) {
		return $this->db->insert_batch('book_keywords', $data);
	}

	function allKeywords($bookID) {
		$this->db->select('id,keyword');
		$this->db->from('book_keywords');
		$this->db->where('bookID', $bookID);
		$query = $this->db->get();
		return $query->result();
	}

	public function delete_bookKeywords($data) {
		return $this->db->delete('book_keywords', $data);
	}

	  /*
     * Fetch members data from the database
     * @param $_POST filter data based on the posted parameters
     */
    public function getRows($postData){
        $this->_get_datatables_query($postData);
        if($postData['length'] != -1){
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

	/*
     * Count all records
     */
    public function countAll(){
		$this->db->from($this->table);
        return $this->db->count_all_results();
    }
    
     /*
     * Count records based on the filter params
     * @param $_POST filter data based on the posted parameters
     */
    public function countFiltered($postData){
        $this->_get_datatables_query($postData);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    /*
     * Perform the SQL queries needed for an server-side processing requested
     * @param $_POST filter data based on the posted parameters
     */
    private function _get_datatables_query($postData){
         
        $this->db->from($this->table);
 
        $i = 0;
        // loop searchable columns 
        foreach($this->column_search as $item){
            // if datatable send POST for search
            if($postData['search']['value']){
                // first loop
                if($i===0){
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                }else{
                    $this->db->or_like($item, $postData['search']['value']);
                }
                
                // last loop
                if(count($this->column_search) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
         
        if(isset($postData['order'])){
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($this->order)){
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }


}