<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class Feed_m extends MY_Model
    {

        protected $_table_name = 'feed';
        protected $_primary_key = 'feedID';
        protected $_primary_filter = 'intval';
        protected $_order_by = "feedID asc";

        public function __construct()
        {
            parent::__construct();
        }

        public function get_feed( $array = null, $signal = false )
        {
            $query = parent::get($array, $signal);
            return $query;
        }

        function get_single_feed($array) {
            $query = parent::get_single($array);
            return $query;
        }


        public function get_order_by_feed( $array = null )
        {
            $query = parent::get_order_by($array);
            return $query;
        }

        public function insert_feed( $array )
        {
            parent::insert($array);
            return true;
        }

        public function insert_batch_feed( $array )
        {
            $id = parent::insert_batch($array);
            return $id;
        }

        public function update_feed( $data, $id = null )
        {
            parent::update($data, $id);
            return $id;
        }

        public function delete_feed($id)
        {
            parent::delete($id);
        }


        
        public function get_my_feeds($limit = '', $start = '',$schoolyearID,$username = null){

            $userTypeID                 = $this->session->userdata("usertypeID");
            $userID                     = $this->session->userdata("loginuserID");

            // $this->db->distinct();
            $this->db->select('feed.*,feed.itemname as type,notice.date,event.ftime,
            event.ttime,activities.time_from,activities.time_to,activities.time_at,
               COALESCE(notice.title, event.title, holiday.title, activities.title) as title,
               COALESCE(notice.users, event.users) as users,
               COALESCE(notice.schoolyearID, event.schoolyearID,holiday.schoolyearID, activities.schoolyearID) as schoolyearID,
               COALESCE(notice.enable_comment, event.enable_comment,holiday.enable_comment) as enable_comment,
               COALESCE(notice.status, event.status) as status,
               COALESCE(notice.create_date, event.create_date,holiday.create_date,activities.create_date) as create_date,
               COALESCE(event.photo, holiday.photo) as feedphoto,
               COALESCE(event.published, holiday.published) as published,
               COALESCE(event.fdate, holiday.fdate) as fdate,
               COALESCE(event.tdate, holiday.tdate) as tdate,
               COALESCE(event.published_date, holiday.published_date) as published_date,
               COALESCE(event.added_to_job, holiday.added_to_job) as added_to_job,
               COALESCE(notice.notice, event.details, holiday.details, activities.description) as details,
               ');
            $this->db->from('feed');
            $this->db->join('notice', 'notice.noticeID=feed.itemID AND feed.itemname = "notice"', 'left');
            $this->db->join('event', 'event.eventID=feed.itemID AND feed.itemname = "event"', 'left');
            $this->db->join('holiday', 'holiday.holidayID=feed.itemID AND feed.itemname = "holiday"', 'left');
            $this->db->join('activities', 'activities.activitiesID=feed.itemID AND feed.itemname = "activity"', 'left');
            $this->db->join('feed_user','feed_user.feed_id = feed.feedID','left');
            if ($limit)
            $this->db->limit($limit, $start);
            if($username){
                $this->db->where("feed.schoolyearID = '$schoolyearID' AND feed.published = '1' AND ( feed.status = 'public' OR (feed.userID = '$userID' AND feed.usertypeID = '$userTypeID' AND feed.show_to_creator = 1) OR (feed_user.user_id = '$userID' AND feed_user.usertypeID = '$userTypeID'))");
            }else{
                $this->db->where("feed.schoolyearID = '$schoolyearID' AND ( feed.status = 'public' OR (feed.userID = '$userID' AND feed.usertypeID = '$userTypeID' AND feed.show_to_creator = 1) OR (feed_user.user_id = '$userID' AND feed_user.usertypeID = '$userTypeID'))");
            }
            $this->db->group_by('feed.feedID');
            $this->db->order_by('feed.published_date desc,feed.feedID desc');
            
            $result = $this->db->get()->result();
            foreach ($result as $index => $data) {
                if($data->usertypeID == 1) {
                    $user = $this->systemadmin_m->get_individual_systemadmin($data->userID);
                } else if($data->usertypeID == 2) {
                    $user = $this->teacher_m->get_individual_teacher($data->userID);
                } else if($data->usertypeID == 3) {
                    $user = $this->student_m->get_individual_student($data->userID);
                } else if($data->usertypeID == 4) {
                    $user = $this->parents_m->get_individual_parents($data->userID);
                } else {
                    $user = $this->user_m->get_individual_user($data->userID);
                }
                $result[$index]->created_by = $user?$user->name:'';
                $result[$index]->user_image = $user?$user->photo:'';
                $result[$index]->name = $user?$user->name:'';
                $result[$index]->photo = $user?$user->photo:'';
            }
            return $result;
        }

       public function getUserNoticeAlerts($array = '',$schoolyearID){
            $this->db->select('feed.itemID,feed.itemname, notice.title,notice.notice,notice.create_date');
            $this->db->from('feed');
            $this->db->join('notice', 'notice.noticeID = feed.itemID', 'LEFT');
            $this->db->where($array);
            $this->db->where('notice.schoolyearID',$schoolyearID);
            $this->db->order_by('notice.create_date','desc');
            $query = $this->db->get();
            return $query->result_array();
       }

       public function getUserEventAlerts($array = '',$schoolyearID){
        $this->db->select('feed.itemID,feed.itemname,event.title,event.details,event.create_date');
        $this->db->from('feed');
        $this->db->join('event', 'event.eventID = feed.itemID', 'LEFT');
        $this->db->where($array);
        $this->db->where('event.schoolyearID',$schoolyearID);
        $this->db->order_by('event.create_date','desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getUserHolidayAlerts($array = '',$schoolyearID){
        $this->db->select('feed.itemID,feed.itemname,holiday.title,holiday.details,holiday.create_date');
        $this->db->from('feed');
        $this->db->join('holiday', 'holiday.holidayID = feed.itemID', 'LEFT');
        $this->db->where($array);
        $this->db->where('holiday.schoolyearID',$schoolyearID);
        $this->db->order_by('holiday.create_date','desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function insert_batch_feed_user($array){
		$insert = $this->db->insert_batch('feed_user', $array);
        return $insert ? true:false;
	}

    public function delete_feed_users($array)
	{
        $this->db->where($array);
        $this->db->delete('feed_user');
	}

    public function insert_feed_user($array){
		$insert = $this->db->insert('feed_user', $array);
        return $insert ? true:false;
	}

    public function get_public_feeds($limit = '', $start = '',$schoolyearID,$username = null){

        $userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");
        
        // $this->db->distinct();
        $this->db->select('feed.*,feed.itemname as type,notice.date,event.ftime,
        event.ttime,activities.time_from,activities.time_to,activities.time_at,
           COALESCE(notice.title, event.title, holiday.title, activities.title) as title,
           COALESCE(notice.users, event.users) as users,
           COALESCE(notice.schoolyearID, event.schoolyearID,holiday.schoolyearID, activities.schoolyearID) as schoolyearID,
           COALESCE(notice.enable_comment, event.enable_comment,holiday.enable_comment) as enable_comment,
           COALESCE(notice.status, event.status) as status,
           COALESCE(notice.create_date, event.create_date,holiday.create_date,activities.create_date) as create_date,
           COALESCE(event.photo, holiday.photo) as feedphoto,
           COALESCE(event.published, holiday.published) as published,
           COALESCE(event.fdate, holiday.fdate) as fdate,
           COALESCE(event.tdate, holiday.tdate) as tdate,
           COALESCE(event.published_date, holiday.published_date) as published_date,
           COALESCE(event.added_to_job, holiday.added_to_job) as added_to_job,
           COALESCE(notice.notice, event.details, holiday.details, activities.description) as details,
           ');
        $this->db->from('feed');
        $this->db->join('notice', 'notice.noticeID=feed.itemID AND feed.itemname = "notice"', 'left');
        $this->db->join('event', 'event.eventID=feed.itemID AND feed.itemname = "event"', 'left');
        $this->db->join('holiday', 'holiday.holidayID=feed.itemID AND feed.itemname = "holiday"', 'left');
        $this->db->join('activities', 'activities.activitiesID=feed.itemID AND feed.itemname = "activity"', 'left');
        $this->db->join('feed_user','feed_user.feed_id = feed.feedID','left');
        // $this->db->where("feed.schoolyearID = '$schoolyearID' AND ( feed.status = 'public' OR (feed.userID = '$userID' AND feed.usertypeID = '$userTypeID' AND feed.show_to_creator = 1) OR (feed_user.user_id = '$userID' AND feed_user.usertypeID = '$userTypeID'))");
    //    $this->db->where("feed.status = 'public' && (feed.userID = '$userID' AND feed.usertypeID = '$userTypeID' AND feed.show_to_creator = 1) OR (feed_user.user_id = '$userID' AND feed_user.usertypeID = '$userTypeID')");
        $this->db->where("feed.status = 'public' AND feed.show_to_creator = 1");
        if ($limit)
        $this->db->limit($limit, $start);
        
        $this->db->group_by('feed.feedID');
        $this->db->order_by('feed.published_date', 'desc');
        
        $result = $this->db->get()->result();
        foreach ($result as $index => $data) {
            if($data->usertypeID == 1) {
                $user = $this->systemadmin_m->get_individual_systemadmin($data->userID);
            } else if($data->usertypeID == 2) {
                $user = $this->teacher_m->get_individual_teacher($data->userID);
            } else if($data->usertypeID == 3) {
                $user = $this->student_m->get_individual_student($data->userID);
            } else if($data->usertypeID == 4) {
                $user = $this->parents_m->get_individual_parents($data->userID);
            } else {
                $user = $this->user_m->get_individual_user($data->userID);
            }
            $result[$index]->created_by = $user->name;
            $result[$index]->user_image = $user->photo;
            $result[$index]->name = $user->name;
            $result[$index]->photo = $user->photo;
        }
        return $result;
    }


    }