<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice_m extends MY_Model {

	protected $_table_name = 'notice';
	protected $_primary_key = 'noticeID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "noticeID desc";

	function __construct() {
		parent::__construct();
        $this->load->model('systemadmin_m');
        $this->load->model('student_m');
        $this->load->model('teacher_m');
        $this->load->model('parents_m');
        $this->load->model('user_m');
	}

	function get_notice($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_notice($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_notice($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_notice($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_notice($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_notice($id){
		parent::delete($id);
		$tables = array('notice_media', 'notice_comment');
		$this->db->where($this->_primary_key, $id);
		$this->db->delete($tables);
	}

    public function getRecentNotices($limit, $start, $schoolYearID = null,$startDate = '',$endDate = '')
    {

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");

        $this->db->select("notice.*, 'Notice' AS type");
        $this->db->from($this->_table_name);
		$this->db->join('notice_user', 'notice_user.notice_id = notice.noticeID', 'LEFT');
		// $this->db->where('status','public');
		// $this->db->or_where("(create_userID = '$userID' AND create_usertypeID = '$userTypeID')");
        // $this->db->or_where("(notice_user.user_id = '$userID' AND notice_user.usertypeID = '$userTypeID')");
		$this->db->where("
		                notice.status = 'public'
						OR (notice.create_userID = '$userID' AND notice.create_usertypeID = '$userTypeID' AND notice.show_to_creator = 1)
						OR (notice_user.user_id = '$userID' AND notice_user.usertypeID = '$userTypeID')
					   ");
		if ($schoolYearID)
            $this->db->where('notice.schoolYearID', $schoolYearID);
		if($startDate && $endDate){
			// $this->db->where("notice.date > '$startDate' AND notice.date <= '$endDate'");
			$this->db->where('date >', $startDate );
			$this->db->where('date <=', $endDate);
		}	

        if ($limit)
            $this->db->limit($limit, $start);

		$this->db->group_by('notice.noticeID');	
		$this->db->order_by("notice.date desc,notice.noticeID desc");

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

    public function getCount()
    {
        $this->db->select("count(*) as count");
        $this->db->from($this->_table_name);
        return $this->db->get()->row()->count;
    }

	public function getLatestNotice(){

			$this->db->select("notice.date");
			$this->db->from($this->_table_name);
			$this->db->order_by('date', 'desc');
			return $this->db->get()->row();
	}

	public function insert_batch_notice_user($array){
		$insert = $this->db->insert_batch('notice_user', $array);
        return $insert ? true:false;
	}

	public function insert_notice_user($array){
		$insert = $this->db->insert('notice_user', $array);
        return $insert ? true:false;
	}

	public function get_notice_users_by_id($noticeID)
    {
			$userTypeID                 = $this->session->userdata("usertypeID");
            $userID                     = $this->session->userdata("loginuserID");

			$this->db->select('*');
		    $this->db->from('notice_user');
			$this->db->where([ 'notice_id' => $noticeID ]);
			$this->db->where("(user_id != '$userID' OR usertypeID != '$userTypeID')");
			$query = $this->db->get();
			return $query->result_array();
    }

	public function get_notice_users($noticeID)
    {
			$this->db->select('*');
		    $this->db->from('notice_user');
			$this->db->where([ 'notice_id' => $noticeID ]);
			$query = $this->db->get();
			return $query->result();
    }

	public function get_my_notices($schoolYearID){
		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");

        $this->db->select("notice.*, 'Notice' AS type");
        $this->db->from($this->_table_name);
		$this->db->join('notice_user', 'notice_user.notice_id = notice.noticeID', 'LEFT');
	    if( $userID != 1){
		   $this->db->where("notice.status = 'public' OR (notice_user.user_id = '$userID' AND notice_user.usertypeID = '$userTypeID')");
		}
		if ($schoolYearID)
        $this->db->where('notice.schoolYearID', $schoolYearID);
		$this->db->group_by('notice.noticeID');	
		$this->db->order_by('notice.date', 'desc');

        return $this->db->get()->result();
	}

	public function delete_notice_users($array)
	{
        $this->db->where($array);
        $this->db->delete('notice_user');
	}

	public function forMigrations($limit = '', $start = '')
    {

        $this->db->select("notice.*, 'Notice' AS type");
        $this->db->from($this->_table_name);
		
        if ($limit)
            $this->db->limit($limit, $start);

		$this->db->group_by('notice.noticeID');	
		$this->db->order_by('notice.date', 'desc');

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

	public function getRecentSMSNotices($limit, $start, $schoolYearID = null,$startDate = '',$endDate = '')
    {

		$userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");

        $this->db->select("notice.*, 'Notice' AS type");
        $this->db->from($this->_table_name);
		$this->db->join('notice_user', 'notice_user.notice_id = notice.noticeID', 'LEFT');
		
		$this->db->where("(notice.create_userID = '$userID' AND notice.create_usertypeID = '$userTypeID' AND notice.send_sms = 1 )
						OR (notice_user.user_id = '$userID' AND notice_user.usertypeID = '$userTypeID' AND notice.send_sms = 1)
					   ");
		if ($schoolYearID)
            $this->db->where('notice.schoolYearID', $schoolYearID);
		if($startDate && $endDate){
			$this->db->where('date >', $startDate );
			$this->db->where('date <=', $endDate);
		}	

        if ($limit)
            $this->db->limit($limit, $start);
	  
		$this->db->group_by('notice.noticeID');	
		$this->db->order_by('notice.date', 'desc');

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