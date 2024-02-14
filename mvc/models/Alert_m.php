<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class alert_m extends MY_Model
    {

        protected $_table_name = 'alert';
        protected $_primary_key = 'alertID';
        protected $_primary_filter = 'intval';
        protected $_order_by = "alertID asc";

        public function __construct()
        {
            parent::__construct();
        }

        public function get_alert( $array = null, $signal = false )
        {
            $query = parent::get($array, $signal);
            return $query;
        }

        public function get_single_alert( $array )
        {
            $query = parent::get_single($array);
            return $query;
        }

        public function get_order_by_alert( $array = null )
        {
            $query = parent::get_order_by($array);
            return $query;
        }

        public function insert_alert( $array )
        {
            parent::insert($array);
            return true;
        }

        public function insert_batch_alert( $array )
        {
            $id = parent::insert_batch($array);
            return $id;
        }

        public function update_alert( $data, $id = null )
        {
            parent::update($data, $id);
            return $id;
        }

        public function delete_alert( $id )
        {
            parent::delete($id);
        }

       public function getUserNoticeAlerts($array = '',$schoolyearID){
            $this->db->select('alert.itemID,alert.itemname, notice.title,notice.notice,notice.create_date');
            $this->db->from('alert');
            $this->db->join('notice', 'notice.noticeID = alert.itemID', 'LEFT');
            $this->db->where($array);
            $this->db->where('notice.schoolyearID',$schoolyearID);
            $this->db->order_by('notice.create_date','desc');
            $query = $this->db->get();
            return $query->result_array();
       }

       public function getUserEventAlerts($array = '',$schoolyearID){
        $this->db->select('alert.itemID,alert.itemname,event.title,event.details,event.create_date');
        $this->db->from('alert');
        $this->db->join('event', 'event.eventID = alert.itemID', 'LEFT');
        $this->db->where($array);
        $this->db->where('event.schoolyearID',$schoolyearID);
        $this->db->order_by('event.create_date','desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getUserHolidayAlerts($array = '',$schoolyearID){
        $this->db->select('alert.itemID,alert.itemname,holiday.title,holiday.details,holiday.create_date');
        $this->db->from('alert');
        $this->db->join('holiday', 'holiday.holidayID = alert.itemID', 'LEFT');
        $this->db->where($array);
        $this->db->where('holiday.schoolyearID',$schoolyearID);
        $this->db->order_by('holiday.create_date','desc');
        $query = $this->db->get();
        return $query->result_array();
    }


    }