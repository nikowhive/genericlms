<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;

class Notice extends Admin_Controller
{
	/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://inilabs.net
| -----------------------------------------------------
*/
	function __construct()
	{
		parent::__construct();
		$this->load->model("job_m");
		$this->load->model("feed_m");
		$this->load->model("alert_m");
		$this->load->model("event_m");
		$this->load->model("notice_m");
		$this->load->model("student_m");
		$this->load->model("fcmtoken_m");
		$this->load->model("mobile_job_m");
		$this->load->model("notice_media_m");
		$this->load->model("notice_comment_m");
		$this->load->model("pushsubscription_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('notice', $language);
		$this->load->library("pagination");
		$this->db->cache_off();
	}

	public function index()
	{
		
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$config = array();
		$config["base_url"]    = base_url() . "notice/index";
		$config["total_rows"]  = $this->notice_m->getCount();
		$config["per_page"]    = 20;
		$config["uri_segment"] = 3;

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$this->data['userType'] = $this->session->userdata('usertypeID');
		$this->data['schoolyearID'] = $this->session->userdata("defaultschoolyearID");

        $dbNotices = $this->notice_m->getRecentNotices($config["per_page"], $page, $schoolyearID);

		$comments = [];
		$noticesMedia = [];
		foreach ($dbNotices as $key => $notice) {
			$noticeID = $notice->noticeID;
			$notice_media = $this->notice_media_m->get_order_by_notice_media(['noticeID' => $noticeID]);
			
			$n_e_media = array();
			foreach ($notice_media as $event) {
				$n_e_media[] = $event->attachment;
			}
			$dbNotices[$key]->media = $n_e_media;

			$notice_comments_count = count($this->notice_comment_m->paginatedNoticeComments('','',['noticeID' => $noticeID]));
			$dbNotices[$key]->comment_count = $notice_comments_count;

			$notice_comments = $this->notice_comment_m->paginatedNoticeComments(5,0,['noticeID' => $noticeID]);
			if(customCompute($notice_comments)){
				$reverse = array_reverse($notice_comments);
				$comments[$noticeID] = $reverse;
			}

			if(customCompute($notice_media)){
				$noticesMedia[$notice->noticeID] = $notice_media;
			}
		}
				
		$this->data['feeds']        = $dbNotices;
		$this->data['user']         = getAllSelectUser();
		$this->data['comments']     = $comments;
		$this->data['noticesMedia'] = $noticesMedia;
		$this->data["subview"]      = "notice/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function getMoreNoticeData()
	{
		$this->data['feed_type'] = 'feed';
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$this->data['userType'] = $this->session->userdata('usertypeID');
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$dbNotices = $this->notice_m->getRecentNotices(20, $page, $schoolyearID);

		$comments = [];
		$noticesMedia = [];
		foreach ($dbNotices as $key => $notice) {
			$noticeID = $notice->noticeID;
			$notice_media = $this->notice_media_m->get_order_by_notice_media(['noticeID' => $noticeID]);
			$n_e_media = array();
			foreach ($notice_media as $media) {
				$n_e_media[] = $media->attachment;
			}
			$dbNotices[$key]->media = $n_e_media;

			$notice_comments_count = count($this->notice_comment_m->paginatedNoticeComments('','',['noticeID' => $noticeID]));
			$dbNotices[$key]->comment_count = $notice_comments_count;

			$notice_comments = $this->notice_comment_m->paginatedNoticeComments(5,0,['noticeID' => $noticeID]);
			if(customCompute($notice_comments)){
				$reverse = array_reverse($notice_comments);
				$comments[$noticeID] = $reverse;

			}

			if(customCompute($notice_media)){
				$noticesMedia[$notice->noticeID] = $notice_media;
			}
		}

		$this->data['user']         = getAllSelectUser();
		$this->data['comments']     = $comments;
		$this->data['noticesMedia'] = $noticesMedia;
		$this->data['feeds']        = $dbNotices;

		if ($this->data['feeds']) {
			echo $this->load->view('notice/autoload_notice', $this->data, true);
			exit;
		} else {
			showBadRequest(null, "No data.");
		}
	}

	protected function rules()
	{
		$rules = array(
			array(
				'field' => 'title',
				'label' => $this->lang->line("notice_title"),
				'rules' => 'trim|required|xss_clean|max_length[128]'
			),
			array(
				'field' => 'date',
				'label' => $this->lang->line("notice_date"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid'
			),
			array(
				'field' => 'notice',
				'label' => $this->lang->line("notice_notice"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'photos[]',
				'label' => $this->lang->line("notice_album"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_multiplephotoupload'
			),
		);
		
		return $rules;
	}

	public function send_mail_rules()
	{
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("notice_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("notice_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("notice_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'noticeID',
				'label' => $this->lang->line("notice_noticeID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			)
		);
		return $rules;
	}

	public function unique_data($data)
	{
		if ($data != '') {
			if ($data == '0') {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	public function add()
	{

		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/datepicker/datepicker.js'
				)
			);

                $schoolyearID = $this->session->userdata('defaultschoolyearID');
				$totalStudent = $this->student_m->get_yearwise_total_students($schoolyearID);
				
				$this->data['totalStudent'] = $totalStudent;
				$this->data['classes']      = $classes = $this->student_m->get_student_count_by_classes($schoolyearID);
				$this->data['employees']    = $employees = $this->student_m->get_user_count_by_usertype();
				 
				$students = $this->student_m->getAllActiveStudents(['active' => 1]);
				$parents = $this->parents_m->getAllActiveParents(['active' => 1]);
		
				$teachers     = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
				$systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
				$users        = $this->user_m->getAllActiveUsers(['active' => 1]);
		
				$usersData = [
					   [
						  'count' => count($teachers) + count($students) + count($parents) + count($systemadmins) + count($users),
						  'name'  => 'All',
						  'type'  => 0
					   ],
					 
					   [
						'count' => count($students),
						'name'  => 'Student',
						'type'  => 3
					   ],
					   [
						'count' => count($parents),
						'name'  => 'Parent',
						'type'  => 4
					   ],
					   [
						'count' => count($systemadmins) + count($users) + count($teachers),
						'name'  => 'Employee',
						'type'  => 11
					   ]
					];
		
				$employeeData = [
					 [
						'no'          => count($teachers) + count($systemadmins) + count($users),
						'usertype'    => 'All',
						'usertypeID'  => 0
					 ],
					 [
					    'no'          => count($systemadmins),
					    'usertype'    => 'Admin',
					    'usertypeID'  => 1
					 ],
					 [
						'no'          => count($teachers),
						'usertype'    => 'Teacher',
						'usertypeID'  => 2
					 ]
				];
				$this->data['usersData'] = $usersData;
		        $this->data['employees'] = array_merge($employeeData,$employees);

			if ($_POST) {

				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {

					$this->data['form_validation'] = validation_errors();
					$this->data["subview"] = "notice/add";
					$this->load->view('_layout_main', $this->data);

				} else {

					$bulkEmployeeType  = $this->input->post('bulkEmployeeType');
					$bulkClass         = $this->input->post('bulkClass');
					$bulkEmployee      = $this->input->post('bulkEmployee');
					$search            = $this->input->post('search');
					$send_to_parents   = $this->input->post('send_to_parents');

					if(isset($_POST["status"])){
						$postUsers = null;
						$filterData = '';
					}else{
						$filters = [];
						if($bulkEmployeeType != ''){
							$filters['bulkEmployeeType'] = $bulkEmployeeType;
						}
						if($bulkClass != ''){
							$filters['bulkClass'] = $bulkClass;
						}
						if($bulkEmployee != ''){
							$filters['bulkEmployee'] = $bulkEmployee;
						}
						if($search != ''){
							$filters['search'] = $search;
						}
						if($send_to_parents != ''){
							$filters['send_to_parents'] = $send_to_parents;
						}
						$filterData = serialize($filters);
						$postUsers = $this->postUsers();
					}

					$array = array(
						"title"               => $this->input->post("title"),
						"users"               => $postUsers?serialize($postUsers):'',
						"filterData"          => $filterData,
						"notice"              => $this->input->post("notice"),
						"status"              => $this->input->post("status") ? $this->input->post("status") : 'private',
						"enable_comment"      => $this->input->post("enable_comment") ? $this->input->post("enable_comment") : 2,
						"schoolyearID"        =>  $this->session->userdata('defaultschoolyearID'),
						"date"                => date("Y-m-d", strtotime($this->input->post("date"))),
						"create_date"         => date("Y-m-d H:i:s"),
						"create_userID"       => $this->session->userdata('loginuserID'),
						"create_usertypeID"   => $this->session->userdata('usertypeID')
					);
					$this->notice_m->insert_notice($array);
					$noticeID = $this->db->insert_id();

					if ($noticeID) {

						// insert media
						$photos = $this->upload_data['files'];
						if (customCompute($photos)) {
							foreach ($photos as $key => $photo) {
								$photos[$key]['noticeID'] = $noticeID;
							}
							$this->notice_media_m->insert_batch_notice_media($photos);
						}

						// insert feed
						$this->feed_m->insert_feed(
							array(
								'itemID'         => $noticeID,
								'userID'         => $this->session->userdata("loginuserID"),
								'usertypeID'     => $this->session->userdata('usertypeID'),
								'itemname'       => 'notice',
								'schoolyearID'   => $this->session->userdata('defaultschoolyearID'),
								'published'      => 1,
								'published_date' => date("Y-m-d", strtotime($this->input->post("date"))),
							    'status'         => $this->input->post("status") ? $this->input->post("status") : 'private',
								)
						);
						$feedID = $this->db->insert_id();

                    // insert users
					if(customCompute($postUsers)){
                        $users = $postUsers;
						$noticeUsers = [];
						if(customCompute($users)){
							foreach($users as $user){  
								    $a = str_split($user);
									$user_id = substr($user, 0, -1);
									$user_type = substr($user, -1);
									$noticeUsers[] = [
										'notice_id'  => $noticeID,
                                        'user_id'    => (int)$user_id,
										'usertypeID' => (int)$user_type
									];
									$feedUsers[] = [
										'feed_id'    => $feedID,
										'user_id'    => $user_id,
										'usertypeID' => $user_type
								    ];
								}
								$this->notice_m->insert_batch_notice_user($noticeUsers);
								$this->feed_m->insert_batch_feed_user($feedUsers);	
							}
						}
					}	

					if (!empty($noticeID)) {
						$this->pushNotification($array, $postUsers,$noticeID);
						$this->alert_m->insert_alert(array('itemID' => $noticeID, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'notice'));
			     	}
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("notice/index"));
				}
			} else {
				$this->data["subview"] = "notice/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function multiplephotoupload()
	{
		if ($_FILES) {
			if ($_FILES['photos']['name'][0] !== "") {
				if (empty(array_filter($_POST['caption']))) {
					$this->form_validation->set_message("multiplephotoupload", 'The %s caption field is required.');
					return FALSE;
				} 

				$filesCount = customCompute($_FILES['photos']['name']);
				$uploadData = array();
				$uploadPath = 'uploads/notice';
				if (!file_exists($uploadPath)) {
					mkdir($uploadPath, 0777, true);
				}
						
				for ($i = 0; $i < $filesCount; $i++) {
					
					$_FILES['attach']['name']     = $_FILES['photos']['name'][$i];
					$_FILES['attach']['type']     = $_FILES['photos']['type'][$i];
					$_FILES['attach']['tmp_name'] = $_FILES['photos']['tmp_name'][$i];
					$_FILES['attach']['error']    = $_FILES['photos']['error'][$i];
					$_FILES['attach']['size']     = $_FILES['photos']['size'][$i];
					
					$config['upload_path'] = $uploadPath;
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					
					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('attach')) {
						
						$fileData = $this->upload->data();

						$image_width = $fileData['image_width'];
					    $image_height = $fileData['image_height'];

						resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height);
 
						$uploadData[$i]['attachment']  = $fileData['file_name'];
						$uploadData[$i]['caption']     = $_POST['caption'][$i];
		 				$uploadData[$i]['create_date'] = date("Y-m-d H:i:s");
					} else {

						$this->form_validation->set_message("multiplephotoupload", "%s" . $this->upload->display_errors());
						return FALSE;
					}
				}

				$this->upload_data['files'] =  $uploadData;
				return TRUE;
			} else {
				$this->upload_data['files'] =  [];
				return TRUE;
			}
		} else {
			$this->upload_data['files'] =  [];
			return TRUE;
		}
	}

	private function _upload_images()
	{
		if ($_FILES['photos']['name'][0] !== "") {
			$filesCount = customCompute($_FILES['photos']['name']);
			$uploadData = array();
			$uploadPath = 'uploads/notice';
			if (!file_exists($uploadPath)) {
				mkdir($uploadPath, 0777, true);
			}
			for ($i = 0; $i < $filesCount; $i++) {
				$_FILES['attach']['name']     = $_FILES['photos']['name'][$i];
				$_FILES['attach']['type']     = $_FILES['photos']['type'][$i];
				$_FILES['attach']['tmp_name'] = $_FILES['photos']['tmp_name'][$i];
				$_FILES['attach']['error']    = $_FILES['photos']['error'][$i];
				$_FILES['attach']['size']     = $_FILES['photos']['size'][$i];

				$config['upload_path'] = $uploadPath;
				$config['allowed_types'] = 'gif|jpg|png|jpeg';

				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if ($this->upload->do_upload('attach')) {
					$fileData = $this->upload->data();
					$uploadData[$i]['attachment'] = $fileData['file_name'];
					$uploadData[$i]['create_date'] = date("Y-m-d H:i:s");
				} else {
				}
			}
			return $uploadData;
		} else {
			return array();
		}
	}

	function pushNotification($array, $postusers,$noticeID)
	{
		if ($array['status'] == 'public') {

			$teachers     = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
			$students     = $this->student_m->getAllActiveStudents(['active' => 1]);
			$parents      = $this->parents_m->getAllActiveParents(['active' => 1]);
			$systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
			$users        = $this->user_m->getAllActiveUsers(['active' => 1]);
			$all_users    = array_merge($teachers, $students, $parents, $systemadmins, $users);

			$newUsers = [];
			foreach ($all_users as $all_user) {
				$newUsers[] = $all_user['ID'] . $all_user['usertypeID'];
			}
			$all_users = $newUsers;
		} else {
			$all_users = $postusers;
		}
		$sall_users = serialize($all_users);

		$this->job_m->insert_job([
			'name'      => 'sendNotice',
			'itemID'    => $noticeID,
			'payload'   => json_encode([
				'title' => $array['title'],  // title is necessary
				'users' => $sall_users,
			]),
		]);

		$this->mobile_job_m->insert_job([
			'name'        => 'sendNotice',
			'itemID'      => $noticeID,
			'payload'     => json_encode([
				'title'   => $array['title'],  // title is necessary
				'users'   => $sall_users,
				'message' => $array['notice']
			]),
		]);
	}

	function updatePushNotification($array, $postusers,$noticeID)
	{
		if ($array['status'] == 'public') {

			$teachers     = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
			$students     = $this->student_m->getAllActiveStudents(['active' => 1]);
			$parents      = $this->parents_m->getAllActiveParents(['active' => 1]);
			$systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
			$users        = $this->user_m->getAllActiveUsers(['active' => 1]);
			$all_users    = array_merge($teachers, $students, $parents, $systemadmins, $users);

			$newUsers = [];
			foreach ($all_users as $all_user) {
				$newUsers[] = $all_user['ID'] . $all_user['usertypeID'];
			}
			$all_users = $newUsers;
		} else {
			$all_users = $postusers;
		}
		$sall_users = serialize($all_users);

		$job = $this->job_m->get_single_job([
			'itemID' => $noticeID,
			'status' => 'queued',
			'name'   => 'sendNotice'
		]);

		if($job){
			$this->job_m->update_job([
				'payload'   => json_encode([
					'title' => $array['title'],  // title is necessary
					'users' => $sall_users,
				]),
			],$job->id);
		}

		$mobile_job = $this->mobile_job_m->get_single_job([
			'itemID' => $noticeID,
			'status' => 'queued',
			'name'   => 'sendNotice'
		]);

		if($mobile_job){
			$this->mobile_job_m->update_job([
				'name'        => 'sendNotice',
				'payload'     => json_encode([
					'title'   => $array['title'],  // title is necessary
					'users'   => $sall_users,
					'message' => $array['notice']
				]),
			],$mobile_job->id);
		}
	}

	function sendFcmNotification($users, $data)
	{
		$registered_ids = [];
		foreach ($users as $user) {
			$user_id = substr($user, 0, -1);
			$user_type = substr($user, -1);
			$push_users = pluck($this->fcmtoken_m->get_order_by_fcm_token(['create_userID' => $user_id, 'create_usertypeID' => $user_type]), 'fcm_token');
			if ($push_users) {
				$registered_ids = array_merge($registered_ids, $push_users);
			}
			$message['data'] = [
				'message' => $data['notice'],
				'title'   => $data['title'],
				'action'  => 'notice'
			];
		}
		chunk_push_notification($registered_ids, $message);
	}


	public function edit()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/datepicker/datepicker.js'
				)
			);

			    $schoolyearID = $this->session->userdata('defaultschoolyearID');
				$totalStudent = $this->student_m->get_yearwise_total_students($schoolyearID);
				
				$this->data['totalStudent'] = $totalStudent;
				$this->data['classes'] = $classes = $this->student_m->get_student_count_by_classes($schoolyearID);
				$this->data['employees'] = $employees = $this->student_m->get_user_count_by_usertype();
				 
				$students = $this->student_m->getAllActiveStudents(['active' => 1]);
				$parents = $this->parents_m->getAllActiveParents(['active' => 1]);
		
				$teachers = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
				$systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
				$users = $this->user_m->getAllActiveUsers(['active' => 1]);
		
				$usersData = [
					   [
						  'count' => count($teachers) + count($students) + count($parents) + count($systemadmins) + count($users),
						  'name'  => 'All',
						  'type'  => 0
					   ],
					 
					   [
						'count' => count($students),
						'name'  => 'Student',
						'type'  => 3
					   ],
					   [
						'count' => count($parents),
						'name'  => 'Parent',
						'type'  => 4
					   ],
					   [
						'count' => count($systemadmins) + count($users) + count($teachers),
						'name'  => 'Employee',
						'type'  => 11
					   ]
					];
		
				$employeeData = [
					[
						'no'          => count($teachers) + count($systemadmins) + count($users),
						'usertype'    => 'All',
						'usertypeID'  => 0
					 ],
					 [
					  'no'          => count($systemadmins),
					  'usertype'    => 'Admin',
					  'usertypeID'  => 1
					 ],
					 [
						'no'          => count($teachers),
						'usertype'    => 'Teacher',
						'usertypeID'  => 2
					]
				];
				$this->data['usersData'] = $usersData;
		        $this->data['employees'] = array_merge($employeeData,$employees);
				
				

			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$this->data['notice'] = $notice =  $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
				$this->data['notice_media'] = $this->notice_media_m->get_order_by_notice_media(['noticeID' => $id]);
                
				// filter data start
				if($notice->status == 'public'){
					$filterData = [];
				}else{
					$filterData = unserialize($notice->filterData);
				}
				$status  = $notice->status;
				
				$bulkEmployeeType = []; 
				$bulkEmployeeTypeValue = '';
				$bulkClass = [];
				$bulkClassValue = '';
				$bulkEmployee = [];
				$bulkEmployeeValue = '';
				$search = '';
				$send_to_parents = '';
				if($status == 'private'){
					$bulkEmployeeType = isset($filterData['bulkEmployeeType'])?explode(',',$filterData['bulkEmployeeType']):[];
					$bulkEmployeeTypeValue = isset($filterData['bulkEmployeeType'])?$filterData['bulkEmployeeType']:'';

					$bulkClass = isset($filterData['bulkClass'])?explode(',',$filterData['bulkClass']):[];
					$bulkClassValue = isset($filterData['bulkClass'])?$filterData['bulkClass']:'';
					
					$bulkEmployee = isset($filterData['bulkEmployee'])?explode(',',$filterData['bulkEmployee']):[];
					$bulkEmployeeValue = isset($filterData['bulkEmployee'])?$filterData['bulkEmployee']:'';
					
					$search = isset($filterData['search'])?$filterData['search']:'';
					$send_to_parents = isset($filterData['send_to_parents'])?'on':'';
				}
				$this->data['bulkEmployeeType']      = $bulkEmployeeType;
				$this->data['bulkEmployeeTypeValue'] = $bulkEmployeeTypeValue;
				$this->data['bulkClass']             = $bulkClass;
				$this->data['bulkClassValue']        = $bulkClassValue;
				$this->data['bulkEmployee']          = $bulkEmployee;
				$this->data['bulkEmployeeValue']     = $bulkEmployeeValue;
				$this->data['search']                = $search;
				$this->data['send_to_parents']       = $send_to_parents;

				// filter data end


				if ($this->data['notice']) {
					if ($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "notice/edit";
							$this->load->view('_layout_main', $this->data);
						} else {

							$bulkEmployeeType = $this->input->post('bulkEmployeeType');
							$bulkClass = $this->input->post('bulkClass');
							$bulkEmployee = $this->input->post('bulkEmployee');
							$search = $this->input->post('search');
							$send_to_parents = $this->input->post('send_to_parents');
		
							if(isset($_POST["status"])){
								$postUsers = null;
								$filterData = '';
							}else{
								$filters = [];
								if($bulkEmployeeType != ''){
									$filters['bulkEmployeeType'] = $bulkEmployeeType;
								}
								if($bulkClass != ''){
									$filters['bulkClass'] = $bulkClass;
								}
								if($bulkEmployee != ''){
									$filters['bulkEmployee'] = $bulkEmployee;
								}
								if($search != ''){
									$filters['search'] = $search;
								}
								if($send_to_parents != ''){
									$filters['send_to_parents'] = $send_to_parents;
								}
								$filterData = serialize($filters);
								$postUsers = $this->postUsers();
							}
						
							$array = array(
								"title"               => $this->input->post("title"),
								"users"               => $postUsers?serialize($postUsers):'',
								"filterData"          => $filterData,
								"notice"              => $this->input->post("notice"),
								"status"              => $this->input->post("status") ? $this->input->post("status") : 'private',
								"enable_comment"      => $this->input->post("enable_comment") ? $this->input->post("enable_comment") : 2,
								"date"                => date("Y-m-d", strtotime($this->input->post("date"))),
							);

							$this->db->trans_start();
							if($this->notice_m->update_notice($array, $id)){

								// insert media
								$photos = $this->upload_data['files'];
								if (customCompute($photos)) {
									foreach ($photos as $key => $photo) {
										$photos[$key]['noticeID'] = $id;
									}
									$this->notice_media_m->insert_batch_notice_media($photos);
								}

								$this->notice_m->delete_notice_users(['notice_id'=>$id]);
								$feed = $this->feed_m->get_single_feed(array('itemID' => $id,'itemname' => 'notice'));
							
								if(customCompute($feed)){
									$this->feed_m->delete_feed_users(['feed_id'=>$feed->feedID]);
									$this->feed_m->update_feed(
										array(
											'published_date' => date("Y-m-d", strtotime($this->input->post("date"))),
											'status'         => $this->input->post("status") ? $this->input->post("status") : 'private',
										),$feed->feedID
									);
						    	}

								if(customCompute($postUsers)){
									$users = $postUsers;
									$noticeUsers = [];
									if(customCompute($users)){
										foreach($users as $user){  
												$a = str_split($user);
												$user_id = substr($user, 0, -1);
												$user_type = substr($user, -1);
												$noticeUsers[] = [
													  'notice_id'  => $id,
													  'user_id'    => (int)$user_id,
													  'usertypeID' => (int)$user_type
												];
												$feedUsers[] = [
													'feed_id'    => $feed->feedID,
													'user_id'    => (int)$user_id,
													'usertypeID' => (int)$user_type
												];
											}
											$this->notice_m->insert_batch_notice_user($noticeUsers);
											$this->feed_m->insert_batch_feed_user($feedUsers);	
										}
									}

									// update job 
									$this->updatePushNotification($array, $postUsers,$id);
							}

							
							$this->db->trans_complete();
							if ($this->db->trans_status() == TRUE) {
								$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							} else {
								$this->session->set_flashdata('error', $this->lang->line('menu_error'));
							}
							redirect(base_url("notice/index"));
						}
					} else {
						$this->data["subview"] = "notice/edit";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function deleteImage()
	{
		if ($this->input->post('id')) {
			$id = $this->input->post('id');
			$noticeMedia = $this->notice_media_m->get_single_notice_media(array('id' => $id));
			if($this->notice_media_m->delete_notice_media($id)){
				if (file_exists(FCPATH . 'uploads/notice/' . $noticeMedia->attachment )) {
					unlink(FCPATH . 'uploads/notice/' . $noticeMedia->attachment );
				}
				$retArray['status'] = true;
				$retArray['message'] = $this->lang->line('menu_success');
				echo json_encode($retArray);
				exit;
			}
			
		}
	}

	public function notice_view($id, $schoolyearID)
	{
		$this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
		if ($this->data['notice']) {

			$array = array(
				"itemID"      => $id,
				"userID"      => $this->session->userdata("loginuserID"),
				"usertypeID"  => $this->session->userdata("usertypeID"),
				"itemname"    => 'notice',
			);

			$alert = $this->alert_m->get_single_alert(array('itemID' => $id, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'notice'));
			if (!customCompute($alert)) {
				$this->alert_m->insert_alert($array);
			}

			$feed = $this->feed_m->get_single_feed(array('itemID' => $id, 'itemname' => 'notice'));
			if (!customCompute($feed)) {
				$array['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
				$array['published'] = 1;
				$array['published_date'] = $this->data['notice']->date;
				$array['status'] = $this->data['notice']->status;
				if($this->feed_m->insert_feed($array)){
				   $feedID = $this->db->insert_id();
                   $noticeusers = $this->notice_m->get_notice_users($this->data['notice']->noticeID);
                   if(customCompute($noticeusers)){
					     $feedUsers = [];
                         foreach($noticeusers as $noticeuser){
							$feedUsers[] = [
								'feed_id'    => $feedID,
								'user_id'    => $noticeuser->user_id,
								'usertypeID' => $noticeuser->usertypeID
							];
						 }
						 $this->feed_m->insert_batch_feed_user($feedUsers);	
				   }
				}
			}

			$this->data["subview"] = "notice/view";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function view()
	{
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$notice = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));

		if ($notice->status == 'public') {
			$this->notice_view($id, $schoolyearID);
		} else {
			if ($notice->create_usertypeID ==  $this->session->userdata("usertypeID") && $notice->create_userID == $this->session->userdata('loginuserID')) {
				$this->notice_view($id, $schoolyearID);
			} elseif (($notice->users != "" && $notice->users != "N;")) {
				$users = unserialize($notice->users);
				if ((int)$id && in_array((int)$this->session->userdata('loginuserID') . $this->session->userdata('usertypeID'), $users)) {
					$this->notice_view($id, $schoolyearID);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		}
	}

	public function delete()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id));
				if ($this->data['notice']) {
					$this->notice_m->delete_notice($id);
					$this->notice_m->delete_notice_users(['notice_id'=>$id]);
					$feed = $this->feed_m->get_single_feed(array('itemID' => $id, 'itemname' => 'notice'));
					if($feed){
						$this->feed_m->delete_feed($feed->feedID);
						$this->feed_m->delete_feed_users(['feed_id'=>$feed->feedID]);
					}
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("notice/index"));
				} else {
					redirect(base_url("notice/index"));
				}
			} else {
				redirect(base_url("notice/index"));
			}
		} else {
			redirect(base_url("notice/index"));
		}
	}

	public function date_valid($date)
	{
		if (strlen($date) < 10) {
			$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
			return FALSE;
		} else {
			$arr = explode("-", $date);
			$dd = $arr[0];
			$mm = $arr[1];
			$yyyy = $arr[2];
			if (checkdate($mm, $dd, $yyyy)) {
				return TRUE;
			} else {
				$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
				return FALSE;
			}
		}
	}

	public function print_preview()
	{
		if (permissionChecker('notice_view')) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
				if (customCompute($this->data['notice'])) {
					$userID = $this->data['notice']->create_userID;
					$usertypeID = $this->data['notice']->create_usertypeID;
					$this->data['userName'] = getNameByUsertypeIDAndUserID($usertypeID, $userID);
					$usertype = $this->usertype_m->get_single_usertype(array('usertypeID' => $usertypeID));
					$this->data['usertype'] = $usertype->usertype;
					$this->data['panel_title'] = $this->lang->line('panel_title');
					$this->reportPDF('noticemodule.css', $this->data, 'notice/print_preview');
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	public function send_mail()
	{
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if (permissionChecker('notice_view')) {
			if ($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					echo json_encode($retArray);
					exit;
				} else {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$id = $this->input->post('noticeID');
					if ((int)$id) {
						$this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
						if (customCompute($this->data['notice'])) {
							$email      = $this->input->post('to');
							$subject    = $this->input->post('subject');
							$message    = $this->input->post('message');
							$userID     = $this->data['notice']->create_userID;
							$usertypeID = $this->data['notice']->create_usertypeID;
							$this->data['userName'] = getNameByUsertypeIDAndUserID($usertypeID, $userID);
							$usertype = $this->usertype_m->get_single_usertype(array('usertypeID' => $usertypeID));
							$this->data['usertype'] = $usertype->usertype;
							$this->reportSendToMail('noticemodule.css', $this->data['notice'], 'notice/print_preview', $email, $subject, $message);
							$retArray['message'] = "Message";
							$retArray['status'] = TRUE;
							echo json_encode($retArray);
							exit;
						} else {
							$retArray['message'] = $this->lang->line('student_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('student_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('notice_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('notice_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function media()
	{
		$notice_id = $this->input->get('notice_id');
		$notice_media = $this->notice_media_m->get_order_by_notice_media(['noticeID' => $notice_id]);
		$this->data['notice_media'] = (is_object($notice_media) or is_array($notice_media)) ? pluck_multi_array($notice_media, 'obj', 'noticeID') : $notice_media;
		
		echo json_encode($this->data['notice_media']);
	}

	public function comment()
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if ($_POST) {
            $array['noticeID']     = $this->input->post('activity_id');
            $array['comment']      = $this->input->post('comment');
            $array['schoolyearID'] = $schoolyearID;
            $array['userID']       = $this->session->userdata("loginuserID");
            $array['usertypeID']   = $this->session->userdata("usertypeID");
            $array['create_date']  = date("Y-m-d H:i:s");
            $data = $this->notice_comment_m->insert_notice_comment($array);
			if($data){
                $this->pushNotificationOfComment($array);
			}
            echo $data;
        }
    }

	public function delete_comment()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$id = $this->input->post('id');
			$usertypeID = $this->session->userdata('usertypeID');
			$userID = $this->session->userdata('loginuserID');

			if ((int)$id) {
				$comment = $this->notice_comment_m->get_notice_comment($id);
				$notice = $this->notice_m->get_notice($comment->noticeID);
				if (($usertypeID == $notice->create_usertypeID && $userID == $notice->create_userID) || ($usertypeID == 1)) {
					$this->notice_comment_m->delete_notice_comment($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				}

				$retArray['status'] = TRUE;;
				$retArray['message'] = $this->lang->line('menu_success');
				echo json_encode($retArray);
				exit;
			} else {
				redirect(base_url("notice/index"));
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function getLatestDate()
	{
		$notice = $this->notice_m->getLatestNotice();

		$dateArray = [];
		if ($notice) {
			$nDate = date('Y-m-d', strtotime($notice->date));
			$dateArray[] = $nDate;
		}

		if (customCompute($dateArray)) {
			$dateArray = $dateArray;
		} else {
			$dateArray = [date('Y-m-d')];
		}

		$latestdate =  max($dateArray);
		$startDate = date('Y-m-d', strtotime("-60 days", strtotime($latestdate)));
		$endDate = $latestdate;

        return [$startDate, $endDate];
    }

	function pushNotificationOfComment($array)
	{
		$noticeObj = $this->notice_m->get_single_notice([
			'noticeID' => $array['noticeID']
		]);

		$newUsers = [];
		if($noticeObj->status == 'public'){
			
			$teachers     = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
			$students     = $this->student_m->getAllActiveStudents(['active' => 1]);
			$parents      = $this->parents_m->getAllActiveParents(['active' => 1]);
			$systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
			$users        = $this->user_m->getAllActiveUsers(['active' => 1]);
			$all_users    = array_merge($teachers,$students,$parents,$systemadmins,$users);
			
			foreach($all_users as $all_user){
				$newUsers[] = $all_user['ID'].$all_user['usertypeID'];
			}
			
		}else{
			$notice_users = $this->notice_m->get_notice_users_by_id($noticeObj->noticeID);
			foreach ($notice_users as $notice_user) {
				$newUsers[] = $notice_user['user_id'] . $notice_user['usertypeID'];
			}
		}

		$all_users = $newUsers;

		// post author
		$postAuthor = $noticeObj->create_userID.$noticeObj->create_usertypeID;
		if($postAuthor != $array['userID'].$array['usertypeID']){
            array_push($all_users,$postAuthor);
		}
		
		$sall_users = serialize($all_users);
 
		$this->job_m->insert_job([
			'name'      => 'sendComment',
			'payload'   => json_encode([
				'title' => "Comment on ".$noticeObj->title,  // title is necessary
				'users' => $sall_users,
			]),
		]);

		$this->mobile_job_m->insert_job([
			'name'        => 'sendComment',
			'payload'     => json_encode([
				'title'   => "Comment on ".$noticeObj->title,  // title is necessary
				'users'   => $sall_users,
				'message' => $array['comment']
			]),
		]);

	}

	public function renderUsersTemplate(){
      
		$users            = $this->input->get('employeeType');
		$classes          = $this->input->get('classes');
		$employees        = $this->input->get('employees');
		$name             = $this->input->get('name');
		$selectedUsers    = $this->input->get('selectedUsers');
		$keyselectedUsers = $this->input->get('keyselectedUsers');
		$noticeID         = $this->input->get('noticeID');
		if($noticeID){
			$notice = $this->notice_m->get_single_notice(['noticeID' => $noticeID]);
			if($notice->status == 'private' && $notice->users != ''){
				$selectedUsers = unserialize($notice->users);
			}
		}

		$eventID = $this->input->get('eventID');
		if($eventID){
			$event = $this->event_m->get_single_event(['eventID' => $eventID]);
			if($event->status == 'private' && $event->users != ''){
				$selectedUsers = unserialize($event->users);
			}
		}
        $usertypes = explode(',',$users);
		$results = [];
		if(customCompute($usertypes)){
			foreach($usertypes as $usertype){
				// employee
                if($usertype == 11){
					if($employees != '' && $employees != 0){
						$employeesIds = explode(',',$employees);

						if(in_array('1',$employeesIds)){
	                       $systemadmins = $this->systemadmin_m->getAllActiveSystemadminsDetails($name);
							if(customCompute($systemadmins)){
								$results['Admin'] = $systemadmins;
							} 
						}

						if(in_array('2',$employeesIds)){
							$teachers = $this->teacher_m->getAllActiveTeachersDetails($name);
							if(customCompute($teachers)){
								$results['Teacher'] = $teachers;
							}
						}
												
						$users = $this->user_m->getAllActiveUsersDetails($employeesIds,$name);
						if(customCompute($users)){
							foreach($users as $key=>$user){
								$results[$key] = $user;
							}
						}
					
				}
				// student
				}elseif($usertype == 3){
					if($classes != '' && $classes != 0){
						$classesIds = explode(',',$classes);
						$students = $this->student_m->getAllActiveStudentsDetails($classesIds,$name);
						if(customCompute($students)){
							foreach($students as $key=>$student){
								$results[$key] = $student;
							}
						}
				    }
				// parents
				}elseif($usertype == 4){
					if($classes != '' && $classes != 0){
					$classesIds = explode(',',$classes);	
					$parents = $this->student_m->getAllActiveParentsDetails($classesIds,$name);
					if(customCompute($parents)){
						foreach($parents as $key=>$parent){
							$results[$key] = $parent;
						}
					}
				}
				}
			}
		}
		
		$this->data['results'] = $results;
		$this->data['selectedUsers'] = $selectedUsers?$selectedUsers:[];
		$this->data['keyselectedUsers'] = $keyselectedUsers?$keyselectedUsers:[];
		echo $this->load->view('notice/users_template', $this->data, true);
		exit;

	}

	

	public function postUsers(){

		    $classes         = $_POST["bulkClass"]; 
			$bulkEmployees   = $_POST["bulkEmployee"];
			$employeeType    = $_POST["bulkEmployeeType"]; 
			$formUsers       = isset($_POST["users"])?$_POST["users"]:[];
			$send_to_parents = isset($_POST["send_to_parents"])?$_POST["send_to_parents"]:'';

			if($employeeType == 0){

				$teachers     = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
				$students     = $this->student_m->getAllActiveStudents(['active' => 1]);
				$parents      = $this->parents_m->getAllActiveParents(['active' => 1]);
				$systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
				$users        = $this->user_m->getAllActiveUsers(['active' => 1]);
				$all_users    = array_merge($teachers, $students, $parents, $systemadmins, $users);

				$newUsers = [];
				foreach ($all_users as $all_user) {
					$newUsers[] = $all_user['ID'] . $all_user['usertypeID'];
				}
				$sall_users = $newUsers;

			}else{
				$dbStudents     = [];
				$dbParents      = [];
				$dbTeachers     = [];
				$dbSystemadmins = [];
				$dbUsers        = [];
                $employeeExplodes = explode(',',$employeeType);
				foreach($employeeExplodes as $employeeExplode){
					if($employeeExplode == 3 && $classes == 0){
						$dbStudents = $this->student_m->getAllActiveStudents(['active' => 1]);
					}
					if($employeeExplode == 4 && $classes == 0){
						$dbParents = $this->parents_m->getAllActiveParents(['active' => 1]);
					}
					if($employeeExplode == 11 && $bulkEmployees == 0){
						$dbTeachers = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
					    $dbSystemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
				        $dbUsers = $this->user_m->getAllActiveUsers(['active' => 1]);
					}
				}
				$all_users = array_merge($dbStudents, $dbParents, $dbTeachers, $dbSystemadmins, $dbUsers);
			
				$allPatents = [];
                if($send_to_parents){
					if(!customCompute($dbParents)){
                       if($classes == 0){
						  $newdbParents = $this->parents_m->getAllActiveParents(['active' => 1]);
					   }else{
						$explodedClasses = explode(',',$classes);
						$newdbParents = $this->parents_m->getAllActiveParentsDetails($explodedClasses);
					}
					if(customCompute($newdbParents)){
						foreach($newdbParents as $newdbParent){
						 $allPatents[] = $newdbParent['ID'] . $newdbParent['usertypeID'];
						}
					 }
					}
				}

			    $newUsers = [];
				foreach ($all_users as $all_user) {
					$newUsers[] = $all_user['ID'] . $all_user['usertypeID'];
				}
				$sall_users = array_merge($newUsers,$allPatents);
			}

			$finalUsers = array_unique(array_merge($formUsers,$sall_users));

			return $finalUsers;
	}

	public function getComment(){

           $commentID = $this->input->get('commentID');
		   $noticeID = $this->input->get('noticeID');
		   $notice_comment = $this->notice_comment_m->get_single_notice_comment(['commentID' => $commentID,'noticeID' => $noticeID]);
          
		   if($notice_comment){
				$this->data['comment'] = $notice_comment->comment;  
				$this->data['commentID'] = $commentID;
				echo $this->load->view('notice/comment_template', $this->data, true);
		   }else{
				$this->data['comment'] = ''; 
				$this->data['commentID'] = '';  
				echo $this->load->view('notice/comment_template', $this->data, true);
		   }

		   exit;

	}

	public function editComment(){
		
		$array['comment']      = $this->input->post('comment');
		$commentID      = $this->input->post('commentID');
		
		$data = $this->notice_comment_m->update_notice_comment($array,$commentID);
		if($data){
			echo $array['comment'];
		}else{
			echo false;
	    }
	}

	public function getMoreNoticeCommentData(){
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$noticeID = $this->input->get('noticeID');
		$notice_comments = $this->notice_comment_m->paginatedNoticeComments(5,$page,['noticeID' => $noticeID]);
		$reverse = array_reverse($notice_comments);
		$this->data['comments'] = $reverse;
		if ($notice_comments) {
			echo $this->load->view('notice/autoload_notice_comment', $this->data, true);
			exit;
		} else {
			showBadRequest(null, "No data.");
		}			
	}

}
