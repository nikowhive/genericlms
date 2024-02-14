<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class User_m extends MY_Model
    {

        protected $_table_name = 'user';
        protected $_primary_key = 'userID';
        protected $_primary_filter = 'intval';
        protected $_order_by = "usertypeID";

        public function __construct()
        {
            parent::__construct();
        }

        public function get_username( $table, $data = null )
        {
            $query = $this->db->get_where($table, $data);
            return $query->result();
        }

        public function get_user_by_usertype( $userID = null )
        {
            $this->db->select('*');
            $this->db->from('user');
            $this->db->join('usertype', 'usertype.usertypeID = user.usertypeID', 'LEFT');
            if ( $userID ) {
                $this->db->where([ 'userID' => $userID ]);
                $query = $this->db->get();
                return $query->row();
            } else {
                $query = $this->db->get();
                return $query->result();
            }
        }

        public function get_users_by_usertype( $usertypeID = null )
        {
            $this->db->select('*');
            $this->db->from('user');
            $this->db->join('usertype', 'usertype.usertypeID = user.usertypeID', 'LEFT');
            if ( $usertypeID ) {
                $this->db->where([ 'user.usertypeID' => $usertypeID,'user.active' => 1]);
                $query = $this->db->get();
                return $query->result();
            } else {
                $this->db->where(['user.active' => 1]);
                $query = $this->db->get();
                return $query->result();
            }
        }

        public function get_user( $array = null, $signal = false )
        {
            $query = parent::get($array, $signal);
            return $query;
        }

        public function get_order_by_user( $array = null )
        {
            $query = parent::get_order_by($array);
            return $query;
        }

        public function get_single_user( $array )
        {
            $query = parent::get_single($array);
            return $query;
        }

        public function get_individual_user($userID) {
            $table_name = $this->_table_name;
            $query = $this->db->select('*')
                    ->from($table_name)
                    ->where('userID', $userID);
            return $query->get()->row();;
        }

        public function get_select_user( $select = null, $array = [] )
        {
            if ( $select == null ) {
                $select = 'userID, usertypeID, name, photo';
            }

            $this->db->select($select);
            $this->db->from($this->_table_name);

            if ( customCompute($array) ) {
                $this->db->where($array);
            }

            $query = $this->db->get();
            return $query->result();
        }

        public function insert_user( $array )
        {
            parent::insert($array);
            return true;
        }

        public function update_user( $data, $id = null )
        {
            parent::update($data, $id);
            return $id;
        }

        public function delete_user( $id )
        {
            parent::delete($id);
        }

        public function hash( $string )
        {
            return parent::hash($string);
        }

        public function get_user_info($usertypeID, $userID)
        {
            if ( $usertypeID == 1 ) {
                $table = "systemadmin";
            } elseif ( $usertypeID == 2 ) {
                $table = "teacher";
            } elseif ( $usertypeID == 3 ) {
                $table = 'student';
            } elseif ( $usertypeID == 4 ) {
                $table = 'parents';
            } else {
                $table = 'user';
            }

            $query = $this->db->get_where($table, [ $table . 'ID' => $userID ]);
            return $query->row();
        }

        public function get_user_table($table, $username, $password)
        {
            $query = $this->db->get_where($table, [ 'username' => $username, 'password' => $this->hash($password) ]);
            return $query->row();
        }

        public function searchUsers($text,$limit,$start){

            $text = trim($text);
            $this->db->select('user.name,user.userID AS ID,user.photo,user.usertypeID');
            $this->db->from('user');
            if($text != ''){
             $this->db->like('name', $text);
            }
            if($limit)
            $this->db->limit($limit, $start);
            $query = $this->db->get();
            return $query->result_array();
    
        }

        public function searchUsersExport($text,$limit = '',$start= ''){

            $text = trim($text);
            $this->db->select('user.userID,user.name,user.dob,user.sex,
            user.photo,user.email,user.phone,user.address,user.usertypeID');
            $this->db->from('user');
            if($text != ''){
             $this->db->like('name', $text);
            }
            if($limit){
                $this->db->limit($limit, $start);
            }
            $query = $this->db->get();
            return $query->result();
    
        }

        public function getAllActiveUsers($array){

            $userTypeID                 = $this->session->userdata("usertypeID");
            $userID                     = $this->session->userdata("loginuserID");

            $this->db->select('user.userID AS ID,user.usertypeID');
            $this->db->from('user');
            $this->db->where($array);
            $this->db->where("(userID != '$userID' OR usertypeID != '$userTypeID')");
            $query = $this->db->get();
            return $query->result_array();
    
        }

        public function getAllActiveUsersDetails($employeesIds = [],$name=''){

            $userTypeID                 = $this->session->userdata("usertypeID");
            $userID                     = $this->session->userdata("loginuserID");

            $this->db->select('usertype.usertype,user.userID AS ID,user.usertypeID,user.name,user.email,user.phone');
            $this->db->from('user');
            $this->db->join('usertype', 'usertype.usertypeID = user.usertypeID', 'LEFT');
            $this->db->where('user.active',1);
            $this->db->where("(user.userID != '$userID' OR user.usertypeID != '$userTypeID')");
            $this->db->where_in('user.usertypeID',$employeesIds);
            if($name){
                $this->db->like('user.name',$name);
            }
            $query = $this->db->get();
            $result = $query->result_array();
            $newResults = [];
            if(customCompute($result)){
                foreach($result as $r){
                    $newResults[$r['usertype']][] = $r;
                }
            }
            return $newResults;
    
        }

        public function getAllUsersContact($filters = [],$limit = '', $start = ''){

            $this->db->select('user.userID AS id,user.usertypeID,user.name,
            user.email,user.address,user.photo');
            $this->db->from('user');
            if(isset($filters['userID'])){
                $this->db->where('userID != '.$filters['userID']);
            }
            if($limit){
                $this->db->limit($limit, $start);
            }
            $query = $this->db->get();
            return $query->result_array();
    
        }

        public function user_count($usertypeID) {
            $this->db->select('count(*) as count');
            $this->db->from('user');
            $this->db->where('usertypeID', $usertypeID);
            $query = $this->db->get();
            return $query->row();
        }
        


    }