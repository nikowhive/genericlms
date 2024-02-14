<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class Schoolinformation_m extends MY_Model
    {

        protected $_table_name = 'schoolinformation';
        protected $_primary_key = 'option';
        protected $_primary_filter = 'intval';
        protected $_order_by = "option asc";

        public function __construct()
        {
            parent::__construct();
        }

        public function get_schoolinformation( $id = 1 )
        {
            $compress = [];
            $query    = $this->db->get('schoolinformation');
            foreach ( $query->result() as $row ) {
                $compress[ $row->fieldoption ] = $row->value;
            }
            return (object) $compress;
        }

        public function get_schoolinformation_array()
        {
            $compress = [];
            $query    = $this->db->get('schoolinformation');
            foreach ( $query->result() as $row ) {
                $compress[ $row->fieldoption ] = $row->value;
            }
            return $compress;
        }

        public function get_schoolinformation_where( $data )
        {
            $this->db->where('fieldoption', $data);
            $query = $this->db->get('schoolinformation');
            return $query->row();
        }

        public function insertorupdate( $arrays )
        {
            foreach ( $arrays as $key => $array ) {
                $this->db->query("INSERT INTO schoolinformation (fieldoption, value) VALUES ('" . $key . "', '" . $array . "') ON DUPLICATE KEY UPDATE fieldoption='" . $key . "' , value='" . $array . "'");
            }
            return true;
        }

        public function delete_schoolinformation( $optionname )
        {
            $this->db->delete('schoolinformation', [ 'fieldoption' => $optionname ]);
            return true;
        }

        public function insert_schoolinformation( $array )
        {
            $this->db->insert('schoolinformation', $array);
            return true;
        }

        public function update_schoolinformation( $fieldoption, $value )
        {
            $array = [
                'value' => $value,
            ];

            $this->db->where('fieldoption', $fieldoption);
            $this->db->update($this->_table_name, $array);
            return true;
        }
    }