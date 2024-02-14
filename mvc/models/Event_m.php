<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_m extends MY_Model {

	protected $_table_name = 'event';
	protected $_primary_key = 'eventID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "fdate desc,ftime asc";

	function __construct() {
		parent::__construct();
        $this->load->model('systemadmin_m');
	}

	function get_event($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_order_by_event($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_event($array) {
        $query = parent::get_single($array);
        return $query;
    }

	function insert_event($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_event($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_event($id){
		parent::delete($id);
		$tables = array('event_media', 'event_comment');
		$this->db->where($this->_primary_key, $id);
		$this->db->delete($tables);
	}

	public function get_my_events($schoolYearID,$username){

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");
     
        $this->db->select("event.*, 'Event' AS type,event.photo as eventphoto");
        $this->db->from($this->_table_name);
		$this->db->join('event_user', 'event_user.event_id = event.eventID', 'LEFT');
		if( $userID != 1){
		   $this->db->where("event.status = 'public' OR (event_user.user_id = '$userID' AND event_user.usertypeID = '$userTypeID')");
		}
		if($username){
			$this->db->where('event.published', 1);
		}
        if($schoolYearID)
            $this->db->where('event.schoolYearID', $schoolYearID);

		$this->db->group_by('event.eventID');		
		$this->db->order_by('event.fdate', 'desc');	
        return $this->db->get()->result();
	}

    public function getRecentEvents($limit, $start, $schoolYearID, $username = null,
	$startDate = '',$endDate = '') {

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");
     
        $this->db->select("event.*, 'Event' AS type,event.photo as eventphoto");
        $this->db->from($this->_table_name);
		$this->db->join('event_user', 'event_user.event_id = event.eventID', 'LEFT');
		$this->db->where('status','public');
		$this->db->or_where("(create_userID = '$userID' AND create_usertypeID = '$userTypeID')");
        $this->db->or_where("(event_user.user_id = '$userID' AND event_user.usertypeID = '$userTypeID')");
		if($username){
			$this->db->where('published', 1);
		}
        if($schoolYearID)
            $this->db->where('schoolYearID', $schoolYearID);
		if($startDate && $endDate){
			$this->db->where('published_date >', $startDate);
			$this->db->where('published_date <=', $endDate);
		}	
        if($limit)
            $this->db->limit($limit, $start);
		$this->db->group_by('event.eventID');		
		$this->db->order_by('published_date', 'desc');	
		
        $result = $this->db->get()->result();
        foreach($result as $index => $data) {
            if($username) {
                $eventCount = $this->eventcounter_m->getEventCountByRow($data->eventID, $username);
                if($eventCount)
					$result[$index]->is_going = $eventCount->status ? 1 : 0;
            }
            if($data->create_usertypeID == 1) {
				$user = $this->systemadmin_m->get_individual_systemadmin($data->create_userID);
			} else if($data->create_usertypeID == 2) {
				$user = $this->teacher_m->get_individual_teacher($data->create_userID);
			} else if($data->create_usertypeID == 3) {
				$user = $this->student_m->get_individual_student($data->create_userID);
			} else if($data->create_usertypeID == 4) {
				$user = $this->parents_m->get_individual_parents($data->create_userID);
			} else {
				$user = $this->user_m->get_individual_user($data->create_userID);
			}
            $result[$index]->created_by = $user->name;
            $result[$index]->user_image = $user->photo;
			$result[$index]->name = $user->name;
            $result[$index]->photo = $user->photo;
        }
        return $result;
    }

    public function getCount() {
        $this->db->select("count(*) as count");
        $this->db->from($this->_table_name);
        return $this->db->get()->row()->count;
    }

    public function get_query_event($sel=NULL,$array=NULL, $rows=TRUE) {
        ($sel) ? $this->db->select($sel):$this->db->select();
        $this->db->from($this->_table_name);
        ($array) ? $this->db->where($array):'';
        $query = $this->db->get();
        return ($rows) ? $query->result():$query->row();
    }
	
	public function getLatestEvent(){
		$this->db->select("event.published_date");
		$this->db->from($this->_table_name);
		$this->db->order_by('published_date', 'desc');
		return $this->db->get()->row();
	}

	public function getEventsForJob($schoolYearID = '',$current_date = ''){
		$this->db->select("*");
		$this->db->from($this->_table_name);
		if($schoolYearID){
			$this->db->where('schoolYearID', $schoolYearID);
		}
		if($current_date){
			$this->db->where('published_date <=', $current_date);
		}
		$this->db->where('added_to_job',0);
		// $this->db->where('published',2);
		$this->db->order_by('create_date', 'asc');
		return $this->db->get()->result();
	}

	public function insert_batch_event_user($array){
		$insert = $this->db->insert_batch('event_user', $array);
        return $insert ? true:false;
	}

	public function get_event_users_by_id($eventID)
    {
			$userTypeID                 = $this->session->userdata("usertypeID");
            $userID                     = $this->session->userdata("loginuserID");

			$this->db->select('*');
		    $this->db->from('event_user');
			$this->db->where([ 'event_id' => $eventID ]);
			$this->db->where("(user_id != '$userID' OR usertypeID != '$userTypeID')");
			$query = $this->db->get();
			return $query->result_array();
    }

	public function get_event_users($eventID)
    {
			$this->db->select('*');
		    $this->db->from('event_user');
			$this->db->where([ 'event_id' => $eventID ]);
			$query = $this->db->get();
			return $query->result();
    }

	public function delete_event_users($array)
	{
        $this->db->where($array);
        $this->db->delete('event_user');
	}

	public function forMigrations($limit = '', $start = '')
    {

        $this->db->select("event.*, 'Event' AS type");
        $this->db->from($this->_table_name);
		
        if ($limit)
            $this->db->limit($limit, $start);

		$this->db->group_by('event.eventID');	
		$this->db->order_by('event.published_date', 'desc');

        $result = $this->db->get()->result();
        foreach ($result as $index => $data) {
			if($data->create_usertypeID == 1) {
				$user = $this->systemadmin_m->get_individual_systemadmin($data->create_userID);
			} else if($data->create_usertypeID == 2) {
				$user = $this->teacher_m->get_individual_teacher($data->create_userID);
			} else if($data->create_usertypeID == 3) {
				$user = $this->student_m->get_individual_student($data->create_userID);
			} else if($data->create_usertypeID == 4) {
				$user = $this->parents_m->get_individual_parents($data->create_userID);
			} else {
				$user = $this->user_m->get_individual_user($data->create_userID);
			}
            $result[$index]->created_by = $user->name;
            $result[$index]->user_image = $user->photo;
			$result[$index]->name = $user->name;
            $result[$index]->photo = $user->photo;
        }
        return $result;
    }


}