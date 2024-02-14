<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Event extends Admin_Controller
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
		$this->load->model("alert_m");
		$this->load->model("feed_m");
		$this->load->model("event_m");
		$this->load->model("user_m");
		$this->load->model("student_m");
		$this->load->model("teacher_m");
		$this->load->model("parents_m");
		$this->load->model("usertype_m");
		$this->load->model("fcmtoken_m");
		$this->load->model("systemadmin_m");
		$this->load->model("mobile_job_m");
		$this->load->model("event_media_m");
		$this->load->model("eventcounter_m");
		$this->load->model("eventcounter_m");
		$this->load->model("event_comment_m");
		$this->load->model("pushsubscription_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('event', $language);
		$this->load->library("pagination");
		$this->db->cache_off();
	}

	public function index()
	{

		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$config = array();
		$config["base_url"] = base_url() . "event/index";
		$config["total_rows"] = $this->event_m->getCount();
		$config["per_page"] = 20;
		$config["uri_segment"] = 3;

		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$this->data['schoolyearID'] = $this->session->userdata("defaultschoolyearID");
		$this->data['userType'] = $this->session->userdata('usertypeID');
		$userIdAndType = $this->session->userdata('loginuserID') . $this->data['userType'];
		$isNotAdmin = $this->data['userType'] != 1;
		
		$dbEvents = $this->event_m->getRecentEvents(20, $page, $schoolyearID, $isNotAdmin ? $this->session->userdata('username') : null, '', '');
		
		$comments = [];
		$eventsMedia = [];
		foreach ($dbEvents as $key => $event) {
			$eventID = $event->eventID;
			$event_media = $this->event_media_m->get_order_by_event_media(['eventID' => $eventID]);
			$n_e_media = array();
			foreach ($event_media as $event) {
				$n_e_media[] = $event->attachment;
			}
			$dbEvents[$key]->media = $n_e_media;

            $dbEvents[$key]->going = $this->eventcounter_m->getEventCount($eventID, 1);
			$dbEvents[$key]->not_going = $this->eventcounter_m->getEventCount($eventID, 0);

			$event_comments_count = count($this->event_comment_m->paginatedEventComments('','',['eventID' => $eventID]));
			$dbEvents[$key]->comment_count = $event_comments_count;

			$event_comments = $this->event_comment_m->paginatedEventComments(5,0,['eventID' => $eventID]);
			if(customCompute($event_comments)){
				$reverse = array_reverse($event_comments);
				$comments[$eventID] = $reverse;
			}

			if(customCompute($event_media)){
				$eventsMedia[$eventID] = $event_media;
			}
		}

		$this->data['user'] = getAllSelectUser();
        $this->data['comments'] = $comments;
		$this->data['eventsMedia'] = $eventsMedia;
		$this->data['feeds'] = $dbEvents;
		$this->data["subview"] = "event/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function getMoreEventData()
	{
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$this->data['userType'] = $this->session->userdata('usertypeID');
		$config["per_page"] = 20;
		$this->data["links"] = $this->pagination->create_links();
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$userIdAndType = $this->session->userdata('loginuserID') . $this->data['userType'];

		$isNotAdmin = $this->data['userType'] != 1;
		$dbEvents = $this->event_m->getRecentEvents(20, $page, $schoolyearID, $isNotAdmin ? $this->session->userdata('username') : null, '', '');

		$comments = [];
		$eventsMedia = [];
		foreach ($dbEvents as $key => $event) {
			$eventID = $event->eventID;
			$event_media = $this->event_media_m->get_order_by_event_media(['eventID' => $eventID]);
			$n_e_media = array();
			foreach ($event_media as $media) {
				$n_e_media[] = $media->attachment;
			}
			$dbEvents[$key]->media = $n_e_media;

			$dbEvents[$key]->going = $this->eventcounter_m->getEventCount($eventID, 1);
			$dbEvents[$key]->not_going = $this->eventcounter_m->getEventCount($eventID, 0);
		
			$event_comments_count = count($this->event_comment_m->paginatedEventComments('','',['eventID' => $eventID]));
			$dbEvents[$key]->comment_count = $event_comments_count;

			$event_comments = $this->event_comment_m->paginatedEventComments(5,0,['eventID' => $eventID]);
			if(customCompute($event_comments)){
				$reverse = array_reverse($event_comments);
				$comments[$eventID] = $reverse;
			}

			if(customCompute($event_media)){
				$eventsMedia[$eventID] = $event_media;
			}
		}
	
		$this->data['user'] = getAllSelectUser();
		$this->data['feeds'] = $dbEvents;
		$this->data['comments'] = $comments;
		$this->data['eventsMedia'] = $eventsMedia;

		if ($this->data['feeds']) {
			echo $this->load->view('event/autoload_event', $this->data, true);
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
				'label' => $this->lang->line("event_title"),
				'rules' => 'trim|required|xss_clean|max_length[75]|min_length[3]'
			),
			array(
				'field' => 'date',
				'label' => $this->lang->line("event_fdate"),
				'rules' => 'trim|required|xss_clean|max_length[41]'
			),
			array(
				'field' => 'published_date',
				'label' => $this->lang->line("event_published_date"),
				'rules' => 'trim|required|xss_clean|max_length[41]'
			),
			array(
				'field' => 'photos[]',
				'label' => $this->lang->line("event_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_multiplephotoupload'
			),
			array(
				'field' => 'event_details',
				'label' => $this->lang->line("event_details"),
				'rules' => 'trim|required|xss_clean|max_length[500]'
			)
		);

		return $rules;
	}

	public function send_mail_rules()
	{
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("event_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("event_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("event_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'eventID',
				'label' => $this->lang->line("event_eventID"),
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

	public function photoupload()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$event = array();
		if ((int)$id) {
			$event = $this->event_m->get_event($id);
		}

		$new_file = "holiday.png";
		if (!empty($_FILES['photo']['name'])) {
			$file_name = $_FILES["photo"]['name'];
			$random = random19();
			$makeRandom = hash('sha512', $random . $this->input->post('title') . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
			$explode = explode('.', $file_name);
			if (customCompute($explode) >= 2) {
				$new_file = $file_name_rename . '.' . end($explode);
				$config['upload_path'] = "./uploads/events";
				$config['allowed_types'] = "gif|jpg|png|jpeg";
				$config['file_name'] = $new_file;
				// $config['max_size'] = '5120';
				// $config['max_width'] = '3000';
				// $config['max_height'] = '3000';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload("photo")) {
					$this->form_validation->set_message("photoupload", $this->upload->display_errors());
					return FALSE;
				} else {
					$this->upload_data['file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("photoupload", "Invalid file");
				return FALSE;
			}
		} else {
			if (customCompute($event)) {
				$this->upload_data['file'] = array('file_name' => $event->photo);
				return TRUE;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
				return TRUE;
			}
		}
	}

	public function add()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/daterangepicker/daterangepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/daterangepicker/moment.min.js',
					'assets/daterangepicker/daterangepicker.js',
					'assets/datepicker/datepicker.js',
				)
			);


			    // for user templete start
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
						'no' => count($teachers) + count($systemadmins) + count($users),
						'usertype'  => 'All',
						'usertypeID'  => 0
					 ],
					 [
					  'no' => count($systemadmins),
					  'usertype'  => 'Admin',
					  'usertypeID'  => 1
					 ],
					 [
						'no' => count($teachers),
						'usertype'  => 'Teacher',
						'usertypeID'  => 2
					]
				];
				$this->data['usersData'] = $usersData;
		        $this->data['employees'] = array_merge($employeeData,$employees);

				// for user templete end
				

			if ($_POST) {

				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "event/add";
					$this->load->view('_layout_main', $this->data);
				} else {

					$bulkEmployeeType = $this->input->post('bulkEmployeeType');
					$bulkClass        = $this->input->post('bulkClass');
					$bulkEmployee     = $this->input->post('bulkEmployee');
					$search           = $this->input->post('search');
					$send_to_parents  = $this->input->post('send_to_parents');

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

					$currentDate = date('d-m-Y');
					$publishDate = $this->input->post("published_date");

					if ($publishDate <= $currentDate) {
						$array["published"] = 1;
					}else{
						$array["published"] = 2;
					}

					$array["title"]             = $this->input->post("title");
					$explode                    = explode('-', $this->input->post("date"));
					$array["fdate"]             = date("Y-m-d", strtotime($explode[0]));
					$array["users"]             = $postUsers?serialize($postUsers):'';
					$array["filterData"]        = $filterData;
					$array['ftime']             = date("H:i:s", strtotime($explode[0]));
					$array["tdate"]             = date("Y-m-d", strtotime($explode[1]));
					$array["ttime"]             = date("H:i:s", strtotime($explode[1]));
					$array["details"]           = $this->input->post("event_details");
					$array["status"]            = $this->input->post("status") ? $this->input->post("status") : 'private';
					$array["enable_comment"]    = $this->input->post("enable_comment") ? $this->input->post("enable_comment") : 2;
					$array['create_date']       = date('Y-m-d H:i:s');
					$array["published_date"]    = date("Y-m-d", strtotime($this->input->post("published_date")));
					$array['schoolyearID']      = $this->session->userdata('defaultschoolyearID');
					$array['create_userID']     = $this->session->userdata('loginuserID');
					$array['create_usertypeID'] = $this->session->userdata('usertypeID');
					$array['photo']             = '';

					$this->event_m->insert_event($array);
					$eventID = $this->db->insert_id();

					if (!empty($eventID)) {

						// media
						$photos = $this->upload_data['files'];
						if (customCompute($photos)) {
							foreach ($photos as $key => $photo) {
								$photos[$key]['eventID'] = $eventID;
							}
							$this->event_media_m->insert_batch_event_media($photos);
						}

                        // alert
						$this->alert_m->insert_alert(array('itemID' => $eventID, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'event'));
					   
						// feed
						$this->feed_m->insert_feed(
							array(
							  'itemID'         => $eventID,
							  'userID'         => $this->session->userdata("loginuserID"),
							  'usertypeID'     => $this->session->userdata('usertypeID'),
							  'itemname'       => 'event',
							  'schoolyearID'   => $this->session->userdata('defaultschoolyearID'),
							  'published'      => $array["published"], 
							  'published_date' => date("Y-m-d", strtotime($this->input->post("published_date"))),
							  'status'         => $this->input->post("status") ? $this->input->post("status") : 'private',
							)
						);
                        $feedID = $this->db->insert_id(); 
						
						// insert event user and feed user
						if(customCompute($postUsers)){
							$users = $postUsers;
							$eventUsers = [];
							if(customCompute($users)){
								foreach($users as $user){  
										$a = str_split($user);
										$user_id = substr($user, 0, -1);
										$user_type = substr($user, -1);
										$eventUsers[] = [
											'event_id'   => $eventID,
											'user_id'    => $user_id,
											'usertypeID' => $user_type
										];
										$feedUsers[] = [
											'feed_id'    => $feedID,
											'user_id'    => $user_id,
											'usertypeID' => $user_type
										];
									}
									$this->event_m->insert_batch_event_user($eventUsers);	
									$this->feed_m->insert_batch_feed_user($feedUsers);
								}
							}
					}

					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("event/index"));
				}
			} else {
				$this->data["subview"] = "event/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}


	public function multiplephotoupload()
	{
		if($_FILES){
			if ($_FILES['photos']['name'][0] !== "") {
				if (empty(array_filter($_POST['caption']))) {
					$this->form_validation->set_message("multiplephotoupload", 'The %s caption field is required.');
					return FALSE;
				}
				$filesCount = customCompute($_FILES['photos']['name']);
				$uploadData = array();
				$uploadPath = 'uploads/events';
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
		}else {
			$this->upload_data['files'] =  [];
			return TRUE;
		}
		
	}

	private function _upload_images()
	{
		if ($_FILES['photos']['name'][0] !== "") {
			$filesCount = customCompute($_FILES['photos']['name']);
			$uploadData = array();
			$uploadPath = 'uploads/events';
			if (!file_exists($uploadPath)) {
				mkdir($uploadPath, 0777, true);
			}
			for ($i = 0; $i < $filesCount; $i++) {
				$_FILES['attach']['name']     = $_FILES['photos']['name'][$i];
				$_FILES['attach']['type']     = $_FILES['photos']['type'][$i];
				$_FILES['attach']['tmp_name'] = $_FILES['photos']['tmp_name'][$i];
				$_FILES['attach']['error']    = $_FILES['photos']['error'][$i];
				$_FILES['attach']['size']     = $_FILES['photos']['size'][$i];

				$config['upload_path']   = $uploadPath;
				$config['allowed_types'] = 'gif|jpg|png|jpeg';

				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if ($this->upload->do_upload('attach')) {
					$fileData = $this->upload->data();
					$uploadData[$i]['attachment'] = $fileData['file_name'];
					$uploadData[$i]['create_date'] = date("Y-m-d H:i:s");
				}
			}
			return $uploadData;
		} else {
			return array();
		}
	}

	function pushNotification($array)
	{
		$this->job_m->insert_job([
			'name'      => 'sendEvent',
			'payload'   => json_encode([
				'title' => 'Event ' . $array['title'] . ' has been added.', // title is compulsary
				'users' => $array['users'],
			]),
		]);

		$this->mobile_job_m->insert_job([
			'name'        => 'sendEvent',
			'payload'     => json_encode([
				'title'   => 'Event ' . $array['title'] . ' has been added.', // title is compulsary
				'users'   => $array['users'],
				'message' => $array['details']
			]),
		]);
	}

	function updatePushNotification($array, $postusers,$eventID)
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


		// update job
		$job = $this->job_m->get_single_job([
			'itemID' =>  $eventID,
			'status' => 'queued',
			'name'   => 'sendEvent'
		]);

		if($job){
			$this->job_m->update_job([
				'payload'   => json_encode([
					'title' => 'Event ' . $array['title'] . ' has been added.', // title is compulsary
					'users' => $sall_users,
				]),
			],$job->id);
		}

		// update mobile job
		$mobile_job = $this->mobile_job_m->get_single_job([
			'itemID' => $eventID,
			'status' => 'queued',
			'name'   => 'sendEvent'
		]);

		if($mobile_job){
			$this->mobile_job_m->update_job([
				'name'        => 'sendEvent',
				'payload'     => json_encode([
					'title'   => 'Event ' . $array['title'] . ' has been added.', // title is compulsary
					'users'   => $sall_users,
					'message' => $array['details']
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
				'message' => $data['details'],
				'title'   => $data['title'],
				'photo'   => base_url('/uploads/events/' . $data['photo']),
				'action'  => 'event'
			];
		}
		chunk_push_notification($registered_ids, $message);
	}

	public function deleteImage()
	{
		if ($this->input->post('id')) {
			$id = $this->input->post('id');
			$eventMedia = $this->event_media_m->get_single_event_media(array('id' => $id));
			if($this->event_media_m->delete_event_media($id)){
				if (file_exists(FCPATH . 'uploads/event/' . $eventMedia->attachment )) {
					unlink(FCPATH . 'uploads/event/' . $eventMedia->attachment );
				}
				$retArray['status'] = true;
				$retArray['message'] = $this->lang->line('menu_success');
				echo json_encode($retArray);
				exit;
			}
			
		}
	}

	public function edit()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/daterangepicker/daterangepicker.css',
					'assets/editor/jquery-te-1.4.0.css'
				),
				'js' => array(
					'assets/editor/jquery-te-1.4.0.min.js',
					'assets/daterangepicker/moment.min.js',
					'assets/daterangepicker/daterangepicker.js',
					'assets/datepicker/datepicker.js',

				)
			);


			// user template
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
					'no' => count($teachers) + count($systemadmins) + count($users),
					'usertype'  => 'All',
					'usertypeID'  => 0
				 ],
				 [
				  'no' => count($systemadmins),
				  'usertype'  => 'Admin',
				  'usertypeID'  => 1
				 ],
				 [
					'no' => count($teachers),
					'usertype'  => 'Teacher',
					'usertypeID'  => 2
				]
			];
			$this->data['usersData'] = $usersData;
			$this->data['employees'] = array_merge($employeeData,$employees);

			// user template end

			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$schoolyearID              = $this->session->userdata("defaultschoolyearID");
				$this->data['event']       = $event =  $this->event_m->get_single_event(array('eventID' => $id, 'schoolyearID' => $schoolyearID));
				$this->data['event_media'] = $this->event_media_m->get_order_by_event_media(['eventID' => $id]);
				
				// filter data start
				if($event->status == 'public'){
					$filterData = [];
				}else{
					$filterData = unserialize($event->filterData);
				}
				$status  = $event->status;
				
				$bulkEmployeeType      = []; 
				$bulkEmployeeTypeValue = '';
				$bulkClass             = [];
				$bulkClassValue        = '';
				$bulkEmployee          = [];
				$bulkEmployeeValue     = '';
				$search                = '';
				$send_to_parents = '';
				if($status == 'private'){
					$bulkEmployeeType      = isset($filterData['bulkEmployeeType'])?explode(',',$filterData['bulkEmployeeType']):[];
					$bulkEmployeeTypeValue = isset($filterData['bulkEmployeeType'])?$filterData['bulkEmployeeType']:'';

					$bulkClass             = isset($filterData['bulkClass'])?explode(',',$filterData['bulkClass']):[];
					$bulkClassValue        = isset($filterData['bulkClass'])?$filterData['bulkClass']:'';
					
					$bulkEmployee          = isset($filterData['bulkEmployee'])?explode(',',$filterData['bulkEmployee']):[];
					$bulkEmployeeValue     = isset($filterData['bulkEmployee'])?$filterData['bulkEmployee']:'';
					
					$search                = isset($filterData['search'])?$filterData['search']:'';
					$send_to_parents       = isset($filterData['send_to_parents'])?'on':'';
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


				if (customCompute($this->data['event'])) {
					if ($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "event/edit";
							$this->load->view('_layout_main', $this->data);
						} else {

							$currentDate = date('d-m-Y');
							$publishDate = $this->input->post("published_date");

							if ($publishDate <= $currentDate) {
								$published = 1;
							} else {
								$published = 2;
							}

							$explode = explode('-', $this->input->post("date"));
							$fdate   = date("Y-m-d", strtotime($explode[0]));
							$ftime   = date("H:i:s", strtotime($explode[0]));
							$tdate   = date("Y-m-d", strtotime($explode[1]));
							$ttime   = date("H:i:s", strtotime($explode[1]));

                            $bulkEmployeeType = $this->input->post('bulkEmployeeType');
							$bulkClass        = $this->input->post('bulkClass');
							$bulkEmployee     = $this->input->post('bulkEmployee');
							$search       	  = $this->input->post('search');
							$send_to_parents  = $this->input->post('send_to_parents');
		
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
								"title"          => $this->input->post("title"),
								"details"        => $this->input->post("event_details"),
								"users"          => $postUsers?serialize($postUsers):'',
								"filterData"     => $filterData,
								"status"         => $this->input->post("status") ? $this->input->post("status") : 'private',
								"fdate"          => $fdate,
								"ftime"          => $ftime,
								"tdate"          => $tdate,
								"ttime"          => $ttime,
								"published"      => $published,
								"published_date" => date("Y-m-d", strtotime($this->input->post("published_date"))),
								"enable_comment" => $this->input->post("enable_comment") ? $this->input->post("enable_comment") : 2,

							);

							if($this->event_m->update_event($array, $id)){

								// insert media
								$photos = $this->upload_data['files'];
								if (customCompute($photos)) {
									foreach ($photos as $key => $photo) {
										$photos[$key]['eventID'] = $id;
									}
									$this->event_media_m->insert_batch_event_media($photos);
								}

								$this->event_m->delete_event_users(['event_id'=> $id]);
								
								$feed = $this->feed_m->get_single_feed(array('itemID' => $id,'itemname' => 'event'));
								$this->feed_m->delete_feed_users(['feed_id'=>$feed->feedID]);

								$this->feed_m->update_feed(
									array(
										"published"      => $published,
										'published_date' => date("Y-m-d", strtotime($this->input->post("published_date"))),
										'status'         => $this->input->post("status") ? $this->input->post("status") : 'private',
									),$feed->feedID
								);

								if(customCompute($postUsers)){
									$users = $postUsers;
									$eventUsers = [];
									if(customCompute($users)){
										foreach($users as $user){  
												$a = str_split($user);
												$user_id = substr($user, 0, -1);
												$user_type = substr($user, -1);
												$eventUsers[] = [
													'event_id'   => $id,
													'user_id'    => (int)$user_id,
													'usertypeID' => (int)$user_type
												];
												$feedUsers[] = [
													'feed_id'    => $feed->feedID,
													'user_id'    => (int)$user_id,
													'usertypeID' => (int)$user_type
												];
											}
											$this->event_m->insert_batch_event_user($eventUsers);
											$this->feed_m->insert_batch_feed_user($feedUsers);	
										}
									}

									$this->updatePushNotification($array,$postUsers,$id);

							}
						
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("event/index"));
						}
					} else {
						$this->data["subview"] = "event/edit";
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

	public function event_view($id, $schoolyearID)
	{
		$this->data['event']   = $this->event_m->get_single_event(array('eventID' => $id, 'schoolyearID' => $schoolyearID));
		$this->data['id'] 	   = $id;
		$this->data['goings']  = $this->eventcounter_m->get_order_by_eventcounter(array('eventID' => $id, 'status' => 1));
		$this->data['ignores'] = $this->eventcounter_m->get_order_by_eventcounter(array('eventID' => $id, 'status' => 0));
		if (customCompute($this->data['event'])) {
			$array = array(
				"itemID"     => $id,
				"userID"     => $this->session->userdata("loginuserID"),
				"usertypeID" => $this->session->userdata("usertypeID"),
				"itemname"   => 'event',
			);
			$alert = $this->alert_m->get_single_alert(array('itemID' => $id, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'event'));
			if (!customCompute($alert)) {
				$this->alert_m->insert_alert($array);
			}

			$feed = $this->feed_m->get_single_feed(array('itemID' => $id, 'itemname' => 'event'));
			if (!customCompute($feed)) {
				$array['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
				$array['published'] = $this->data['event']->published;
				$array['published_date'] = $this->data['event']->published_date;
				$array['status'] = $this->data['event']->status;
				if($this->feed_m->insert_feed($array)){
					$feedID = $this->db->insert_id();
					$eventusers = $this->event_m->get_event_users($this->data['event']->eventID);
					if(customCompute($eventusers)){
						  $feedUsers = [];
						  foreach($eventusers as $eventuser){
							 $feedUsers[] = [
								 'feed_id'    => $feedID,
								 'user_id'    => $eventuser->user_id,
								 'usertypeID' => $eventuser->usertypeID
							 ];
						  }
						  $this->feed_m->insert_batch_feed_user($feedUsers);	
					}
				 }
			}


			$this->data["subview"] = "event/view";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function view()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$schoolyearID = $this->session->userdata("defaultschoolyearID");
		$event = $this->event_m->get_single_event(array('eventID' => $id, 'schoolyearID' => $schoolyearID));
		if ($event->status == 'public') {
			$this->event_view($id, $schoolyearID);
		} else {
			if ($event->create_usertypeID == $this->session->userdata('usertypeID') && $event->create_userID == $this->session->userdata('loginuserID')) {
				$this->event_view($id, $schoolyearID);
			} elseif ($event->users != "" && $event->users != "N;") {
				$users = unserialize($event->users);
				if ((int)$id && in_array((int)$this->session->userdata('loginuserID') . $this->session->userdata('usertypeID'), $users)) {

					$this->event_view($id, $schoolyearID);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	public function delete()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$event = $this->event_m->get_event($id);
				$schoolyearID = $this->session->userdata("defaultschoolyearID");
				$event = $this->event_m->get_single_event(array('eventID' => $id, 'schoolyearID' => $schoolyearID));
				if (customCompute($event)) {
					if (config_item('demo') == FALSE) {
						if ($event->photo != 'holiday.png' && $event->photo != '') {
							if (file_exists(FCPATH . 'uploads/events/' . $event->photo)) {
								unlink(FCPATH . 'uploads/events/' . $event->photo);
							}
						}
					}
					$this->event_m->delete_event($id);
					$this->event_m->delete_event_users(['eventID'=> $id]);

					$feed = $this->feed_m->get_single_feed(array('itemID' => $id, 'itemname' => 'event'));
					if($feed){
						$this->feed_m->delete_feed($feed->feedID);
						$this->feed_m->delete_feed_users(['feed_id'=>$feed->feedID]);

					}
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("event/index"));
				} else {
					redirect(base_url("event/index"));
				}
			} else {
				redirect(base_url("event/index"));
			}
		} else {
			redirect(base_url("event/index"));
		}
	}

	public function print_preview()
	{
		if (permissionChecker('event_view')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int)$id) {
				$schoolyearID = $this->session->userdata("defaultschoolyearID");
				$this->data['event'] = $this->event_m->get_single_event(array('eventID' => $id, 'schoolyearID' => $schoolyearID));
				if (customCompute($this->data['event'])) {
					$userID     = $this->data['event']->create_userID;
					$usertypeID = $this->data['event']->create_usertypeID;
					$this->data['userName'] = getNameByUsertypeIDAndUserID($usertypeID, $userID);
					$usertype = $this->usertype_m->get_single_usertype(array('usertypeID' => $usertypeID));
					$this->data['usertype'] = $usertype->usertype;
					$this->reportPDF('eventmodule.css', $this->data, 'event/print_preview');
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function send_mail()
	{
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if (permissionChecker('event_view')) {
			if ($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					echo json_encode($retArray);
					exit;
				} else {
					$id = $this->input->post('eventID');
					if ((int)$id) {
						$schoolyearID = $this->session->userdata("defaultschoolyearID");
						$this->data['event'] = $this->event_m->get_single_event(array('eventID' => $id, 'schoolyearID' => $schoolyearID));
						if (customCompute($this->data['event'])) {
							$email   = $this->input->post('to');
							$subject = $this->input->post('subject');
							$message = $this->input->post('message');

							$userID = $this->data['event']->create_userID;
							$usertypeID = $this->data['event']->create_usertypeID;
							$this->data['userName'] = getNameByUsertypeIDAndUserID($usertypeID, $userID);
							$usertype = $this->usertype_m->get_single_usertype(array('usertypeID' => $usertypeID));
							$this->data['usertype'] = $usertype->usertype;
							$this->reportSendToMail('eventmodule.css', $this->data, 'event/print_preview', $email, $subject, $message);
							$retArray['message'] = "Message";
							$retArray['status'] = TRUE;
							echo json_encode($retArray);
							exit;
						} else {
							$retArray['message'] = $this->lang->line('event_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('event_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('event_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('event_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function eventcounter()
	{
		$username = $this->session->userdata("username");
		$usertype = $this->session->userdata("usertype");
		$photo    = $this->session->userdata("photo");
		$name     = $this->session->userdata("name");
		$eventID  = $this->input->post('id');
		$status   = $this->input->post('status');
		if ($eventID) {
			$have = $this->eventcounter_m->get_order_by_eventcounter(array("eventID" => $eventID, "username" => $username, "type" => $usertype), TRUE);
			if (customCompute($have)) {
				$array = array('status' => $status);
				$this->eventcounter_m->update($array, $have[0]->eventcounterID);
			} else {
				$array = array(
					'eventID'  => $eventID,
					'username' => $username,
					'type'     => $usertype,
					'photo'    => $photo,
					'name'     => $name,
					'status'   => $status
				);
				$this->eventcounter_m->insert($array);
			}
			echo json_encode($this->eventcounter_m->getCount($eventID));
		}
	}

	public function media()
	{
		$event_id = $this->input->get('event_id');
		$event_media = $this->event_media_m->get_order_by_event_media(['eventID' => $event_id]);
		$this->data['event_media'] = (is_object($event_media) or is_array($event_media)) ? pluck_multi_array($event_media, 'obj', 'eventID') : $event_media;
		echo json_encode($this->data['event_media']);
	}

	public function postChangeEventStatus($id)
	{

		$event = $this->event_m->get_single_event(['eventID' => $id]);
		$array = [
			'published' => $event->published == 2 ? 1 : 2,
			'published_date' => date('Y-m-d')
		];

		if($this->event_m->update_event($array, $id)){
			$feed = $this->feed_m->get_single_feed(array('itemID' => $id,'itemname' => 'event'));

			$feedarray = array(
				"itemID"     => $id,
				"userID"     => $this->session->userdata("loginuserID"),
				"usertypeID" => $this->session->userdata("usertypeID"),
				"itemname"   => 'event',
			);
			if (!customCompute($feed)) {
				$feedarray['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
				$feedarray['published'] = $array['published'];
				$feedarray['published_date'] = $array['published_date'];
				$feedarray['status'] = $event->status;
				if($this->feed_m->insert_feed($feedarray)){
					$feedID = $this->db->insert_id();
					$eventusers = $this->event_m->get_event_users($event->eventID);
					if(customCompute($eventusers)){
						  $feedUsers = [];
						  foreach($eventusers as $eventuser){
							 $feedUsers[] = [
								 'feed_id'    => $feedID,
								 'user_id'    => $eventuser->user_id,
								 'usertypeID' => $eventuser->usertypeID
							 ];
						  }
						  $this->feed_m->insert_batch_feed_user($feedUsers);	
					}
				}
			}else{
				$this->feed_m->update_feed(['published_date'=> $array['published_date'],'published' => $array['published']],$feed->feedID);
			}

			echo true;
	    }
	}

	public function comment()
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if ($_POST) {
            $array['eventID']      = $this->input->post('activity_id');
            $array['comment']      = $this->input->post('comment');
            $array['schoolyearID'] = $schoolyearID;
            $array['userID']       = $this->session->userdata("loginuserID");
            $array['usertypeID']   = $this->session->userdata("usertypeID");
            $array['create_date']  = date("Y-m-d H:i:s");
            $data = $this->event_comment_m->insert_event_comment($array);
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
				$comment = $this->event_comment_m->get_event_comment($id);
				$event = $this->event_m->get_event($comment->eventID);
				if (($usertypeID == $event->create_usertypeID && $userID == $event->create_userID) || ($usertypeID == 1)) {
					$this->event_comment_m->delete_event_comment($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				}

				$retArray['status'] = TRUE;;
				$retArray['message'] = $this->lang->line('menu_success');
				echo json_encode($retArray);
				exit;
			} else {
				redirect(base_url("event/index"));
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function getLatestDate()
    {
        
        $event = $this->event_m->getLatestEvent();

        $dateArray = [];
        
        if ($event) {
            $eDate = date('Y-m-d', strtotime($event->published_date));
            $dateArray[] = $eDate;
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
		$eventObj = $this->event_m->get_single_event([
			'eventID' => $array['eventID']
		]);

		$newUsers = [];
		if($eventObj->status == 'public'){
			
			$teachers = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
			$students = $this->student_m->getAllActiveStudents(['active' => 1]);
			$parents = $this->parents_m->getAllActiveParents(['active' => 1]);
			$systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
			$users = $this->user_m->getAllActiveUsers(['active' => 1]);
			$all_users = array_merge($teachers,$students,$parents,$systemadmins,$users);
			
			foreach($all_users as $all_user){
				$newUsers[] = $all_user['ID'].$all_user['usertypeID'];
			}
			
			
		}else{
			$event_users = $this->event_m->get_event_users_by_id($eventObj->eventID);
			foreach ($event_users as $event_user) {
				$newUsers[] = $event_user['user_id'] . $event_user['usertypeID'];
			}
		}

		$all_users = $newUsers;

		// post author
		$postAuthor = $eventObj->create_userID.$eventObj->create_usertypeID;
		if($postAuthor != $array['userID'].$array['usertypeID']){
            array_push($all_users,$postAuthor);
		}
		
		$sall_users = serialize($all_users);
 
		$this->job_m->insert_job([
			'name' => 'sendComment',
			'payload' => json_encode([
				'title' => "Comment on ".$eventObj->title,  // title is necessary
				'users' => $sall_users,
			]),
		]);

		$this->mobile_job_m->insert_job([
			'name' => 'sendComment',
			'payload' => json_encode([
				'title' => "Comment on ".$eventObj->title,  // title is necessary
				'users' => $sall_users,
				'message' => $array['comment']
			]),
		]);
	}
	
	public function postUsers(){

		$classes = $_POST["bulkClass"]; 
		$bulkEmployees = $_POST["bulkEmployee"];
		$employeeType = $_POST["bulkEmployeeType"]; 
		$formUsers = isset($_POST["users"])?$_POST["users"]:[];
		$send_to_parents = isset($_POST["send_to_parents"])?$_POST["send_to_parents"]:'';

		if($employeeType == 0){

			$teachers = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
			$students = $this->student_m->getAllActiveStudents(['active' => 1]);
			$parents = $this->parents_m->getAllActiveParents(['active' => 1]);
			$systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
			$users = $this->user_m->getAllActiveUsers(['active' => 1]);
			$all_users = array_merge($teachers, $students, $parents, $systemadmins, $users);

			$newUsers = [];
			foreach ($all_users as $all_user) {
				$newUsers[] = $all_user['ID'] . $all_user['usertypeID'];
			}
			$sall_users = $newUsers;

		}else{
			$dbStudents = [];
			$dbParents = [];
			$dbTeachers = [];
			$dbSystemadmins = [];
			$dbUsers = [];
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
		$eventID = $this->input->get('eventID');

		$event_comment = $this->event_comment_m->get_single_event_comment(['commentID' => $commentID,'eventID' => $eventID]);
		
		if($event_comment){
			$this->data['comment'] = $event_comment->comment;  
			$this->data['commentID'] = $commentID;
			echo $this->load->view('event/comment_template', $this->data, true);
		}else{
			$this->data['comment'] = ''; 
			$this->data['commentID'] = '';  
			echo $this->load->view('event/comment_template', $this->data, true);
		}

		exit;

	}

	public function editComment(){
	
		$array['comment']      = $this->input->post('comment');
		$commentID      = $this->input->post('commentID');
		
		$data = $this->event_comment_m->update_event_comment($array,$commentID);
		if($data){
			echo $array['comment'];
		}else{
			echo false;
		}
	}

	public function getMoreEventCommentData(){
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$eventID = $this->input->get('eventID');
		$event_comments = $this->event_comment_m->paginatedEventComments(5,$page,['eventID' => $eventID]);
		$reverse = array_reverse($event_comments);
		$this->data['comments'] = $reverse;
		if ($event_comments) {
			echo $this->load->view('event/autoload_event_comment', $this->data, true);
			exit;
		} else {
			showBadRequest(null, "No data.");
		}			
	}

}
