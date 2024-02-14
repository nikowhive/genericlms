<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class Fcmtoken_m extends MY_Model
    {

        protected $_table_name = 'fcm_token';
        protected $_primary_key = 'tokenID';
        protected $_primary_filter = 'intval';
        protected $_order_by = "tokenID asc";

        public function __construct()
        {
            parent::__construct();
        }

        public function get_fcm_token( $array = null, $signal = false )
        {
            $query = parent::get($array, $signal);
            return $query;
        }

        public function get_single_fcm_token( $array )
        {
            $query = parent::get_single($array);
            return $query;
        }

        public function get_order_by_fcm_token( $array = null )
        {
            $query = parent::get_order_by($array);
            return $query;
        }

        public function insert_fcm_token( $array )
        {
            parent::insert($array);
            return true;
        }

        public function insert_batch_fcm_token( $array )
        {
            $id = parent::insert_batch($array);
            return $id;
        }

        public function update_fcm_token( $data, $id = null )
        {
            parent::update($data, $id);
            return $id;
        }

        public function delete_fcm_token( $id )
        {
            parent::delete($id);
        }
    }