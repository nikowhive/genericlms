<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Event extends Api_Controller
{
    public function __construct()
    {
        
        parent::__construct();
        $this->load->model("job_m");
        $this->load->model("mobile_job_m");
        $this->load->model("event_m");
        $this->load->model("eventcounter_m");
        $this->load->model("pushsubscription_m");
        $this->load->model("usertype_m");
        $this->load->model("student_m");
        $this->load->model('parents_m');
        $this->load->model('teacher_m');
        $this->load->model('fcmtoken_m');
        $this->load->model('user_m');
        $this->load->model('alert_m');
        $this->load->model('feed_m');
        $this->load->model("event_media_m");
        $this->load->model('systemadmin_m');
        $this->load->model("event_comment_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('event', $language);
    }

    public function index_get($page=1)
    {
        if (permissionChecker('event_view')) {

            $this->data['userType'] = $this->session->userdata('usertypeID');
            $page  = 20 * ($page - 1);

            $schoolyearID = $this->session->userdata("defaultschoolyearID");
                    
            $allusers     = getAllSelectUser();

            $userIdAndType = $this->session->userdata('loginuserID') . $this->data['userType'];
		
            $isNotAdmin = $this->data['userType'] != 1;
            $dbEvents = $this->event_m->getRecentEvents(20, $page, $schoolyearID, $isNotAdmin ? $this->session->userdata('username') : null,'','');
            
            foreach($dbEvents as $key => $event )
            {

                $dbEvents[$key]->going = $this->eventcounter_m->getEventCount($event->eventID, 1);
                $dbEvents[$key]->not_going = $this->eventcounter_m->getEventCount($event->eventID, 0);
         
                $eventID = $event->eventID;
                $event_media = pluck($this->event_media_m->get_order_by_event_media(['eventID' => $eventID]),'obj');
                $dbEvents[$key]->media = $event_media;

                $comments = pluck($this->event_comment_m->get_order_by_event_comment(['eventID' => $eventID]),'obj');
                if(customCompute($comments)){
					foreach($comments as $k=>$comment){
						$comments[$k]->name = $allusers[$comment->usertypeID][$comment->userID]->name;
						$comments[$k]->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
					}
                    $dbEvents[$key]->comments = $comments;
                }else{
                    $dbEvents[$key]->comments  = [];
                }

            }

            $this->response([
                'status' => true,
                'message' => 'Success',
                'data' => $dbEvents,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'User not allowed',
                'data' => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function view_get($id = null)
    {
        if ((int) $id) {
            $schoolyearID = $this->session->userdata("defaultschoolyearID");
            $event = $this->event_m->get_single_event(array('eventID' => $id, 'schoolyearID' => $schoolyearID));
            $this->retdata['eventID'] = $id;
            $this->retdata['goings'] = $this->eventcounter_m->get_order_by_eventcounter(array('eventID' => $id, 'status' => 1));
            $this->retdata['ignores'] = $this->eventcounter_m->get_order_by_eventcounter(array('eventID' => $id, 'status' => 0));
           
            $eventID = $event->eventID;
            $event_medias = pluck($this->event_media_m->get_order_by_event_media(['eventID' => $eventID]),'obj');
            $event->media = $event_medias;
           
            $comments = pluck($this->event_comment_m->get_order_by_event_comment(['eventID' => $eventID]),'obj');
            $event->comments = $comments;

            $event->users = $event->users?unserialize($event->users):'';
            $event->filterData = $event->filterData?unserialize($event->filterData):'';

            $this->retdata['event'] = $event;

            if (customCompute($event)) {

                $array = array(
                    "itemID" => $id,
                    "userID" => $this->session->userdata("loginuserID"),
                    "usertypeID" => $this->session->userdata("usertypeID"),
                    "itemname" => 'event',
                );

                $alert = $this->alert_m->get_single_alert(array('itemID' => $id, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'event'));
                if (!customCompute($alert)) {
                    $this->alert_m->insert_alert($array);
                }

                $feed = $this->feed_m->get_single_feed(array('itemID' => $id,'itemname' => 'event'));
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
								 'feed_id'  => $feedID,
								 'user_id'    => $eventuser->user_id,
								 'usertypeID' => $eventuser->usertypeID
							 ];
						  }
						  $this->feed_m->insert_batch_feed_user($feedUsers);	
					}
				 }
                }

                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => $this->retdata,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Error 404',
                    'data' => [],
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error 404',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("event_title"),
                'rules' => 'trim|required|xss_clean|max_length[75]|min_length[3]',
            ),
            array(
                'field' => 'date',
                'label' => $this->lang->line("event_fdate"),
                'rules' => 'trim|required|xss_clean|max_length[41]',
            ),
            array(
				'field' => 'published_date',
				'label' => $this->lang->line("event_published_date"),
				'rules' => 'trim|required|xss_clean|max_length[41]'
			),
            array(
                'field' => 'event_details',
                'label' => $this->lang->line("event_details"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
				'field' => 'photos[]',
				'label' => $this->lang->line("notice_album"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_multiplephotoupload'
			),
        );

        $id = htmlentities(escapeString($this->uri->segment(5)));
        if(!$id){
				$rules[] = [
					'field'    => 'users[]',
					'label'    => 'users',
					'rules'    => 'trim|required|xss_clean',
                    'errors'   => array(
                        'required' => 'You must provide users.',
                    ),
				];
		}
        return $rules;
    }

    public function send_mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("event_to"),
                'rules' => 'trim|required|max_length[60]|valid_email|xss_clean',
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("event_subject"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("event_message"),
                'rules' => 'trim|xss_clean',
            ),
            array(
                'field' => 'eventID',
                'label' => $this->lang->line("event_eventID"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data',
            ),
        );
        return $rules;
    }

    public function unique_data($data)
    {
        if ($data != '') {
            if ($data == '0') {
                $this->form_validation->set_message('unique_data', 'The %s field is required.');
                return false;
            }
            return true;
        }
        return true;
    }

    public function photoupload()
    {
        $id = htmlentities(escapeString($this->uri->segment(5)));
        $event = array();
        if ((int) $id) {
            $event = $this->event_m->get_event($id);
        }

        $new_file = "holiday.png";
        if ($_FILES["photo"]['name'] != "") {
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
                $_FILES['attach']['tmp_name'] = $_FILES['photo']['tmp_name'];
                $image_info = getimagesize($_FILES['photo']['tmp_name']);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
                // $config['max_size'] = '5120';
                // $config['max_width'] = '3000';
                // $config['max_height'] = '3000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("photo")) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return false;
                } else {
                    $fileData = $this->upload->data();
                    if($image_width > 1800 || $image_height > 1800){
                        resizeImage($fileData['file_name'],$config['upload_path']);
                     }
                    $this->upload_data['file'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return false;
            }
        } else {
            if (customCompute($event)) {
                $this->upload_data['file'] = array('file_name' => $event->photo);
                return true;
            } else {
                $this->upload_data['file'] = array('file_name' => $new_file);
                return true;
            }
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
		} else {
			$this->upload_data['files'] =  [];
			return TRUE;
		}
	}

    public function index_post()
    {
        // if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') && $this->session->userdata('usertypeID') == 1)) {
        if ($this->session->userdata('usertypeID') == 1){

            if ($_POST) {
                $rules = $this->rules();
                
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {

                    $this->response([
                        'status' => false,
                        'message' => $this->form_validation->error_array(),
                        'data' => [],
                    ], REST_Controller::HTTP_OK);
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
						$postUsers = $_POST['users'];
					}

                    $currentDate = date('d-m-Y');
					$publishDate = $this->input->post("published_date");

					if ($publishDate <= $currentDate) {
						$array["published"] = 1;
					}else{
						$array["published"] = 2;
					}

                    $array["title"] = $this->input->post("title");
                    $explode = explode('-', $this->input->post("date"));
                    $array["fdate"] = date("Y-m-d", strtotime($explode[0]));
                    $array["users"] = $postUsers?serialize($postUsers):'';
                    $array["filterData"]  = $filterData;
                    $array['ftime'] = date("H:i:s", strtotime($explode[0]));
                    $array["tdate"] = date("Y-m-d", strtotime($explode[1]));
                    $array["ttime"] = date("H:i:s", strtotime($explode[1]));
                    $array["status"] = $this->input->post("status") ? $this->input->post("status") : 'private';
                    $array["enable_comment"] = $this->input->post("enable_comment") ? $this->input->post("enable_comment") : 2;
                    $array["published_date"] = date("Y-m-d", strtotime($this->input->post("published_date")));
                    $array["details"] = $this->input->post("event_details");
                    $array['create_date'] = date('Y-m-d H:i:s');
                    $array['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
                    $array['create_userID'] = $this->session->userdata('loginuserID');
                    $array['create_usertypeID'] = $this->session->userdata('usertypeID');
                   
                    $this->event_m->insert_event($array);
                    $insert_id = $this->db->insert_id();
                    if($insert_id){

                         // insert media
						$photos = $this->upload_data['files'];
						if (customCompute($photos)) {
							foreach ($photos as $key => $photo) {
								$photos[$key]['eventID'] = $insert_id;
							}
							$this->event_media_m->insert_batch_event_media($photos);
						}

                        $this->feed_m->insert_feed(
                            array(
                                'itemID'         => $insert_id,
                                "userID"         => $this->session->userdata("loginuserID"),
                                'usertypeID'     => $this->session->userdata('usertypeID'),
                                'itemname'       => 'event',
                                'schoolyearID'   => $this->session->userdata('defaultschoolyearID'),
                                'published'      => $array["published"], 
                                'published_date' => date("Y-m-d", strtotime($this->input->post("published_date"))),
                                'status'         => $this->input->post("status") ? $this->input->post("status") : 'private',
                            )
                        );
                        $feedID = $this->db->insert_id(); 
                        
                        if(customCompute($postUsers)){
							$users = $postUsers;
							$eventUsers = [];
							if(customCompute($users)){
								foreach($users as $user){  
										$a = str_split($user);
										$user_id = substr($user, 0, -1);
										$user_type = substr($user, -1);
										$eventUsers[] = [
											  'event_id'   => $insert_id,
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
                            $this->alert_m->insert_alert(array('itemID' => $insert_id, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'event'));
					        
                   
                            $array["id"] = $insert_id;

                            $this->response([
                                'status' => true,
                                'message' => 'Success',
                                'data' => $array,
                            ], REST_Controller::HTTP_OK);
                        }
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No fields values',
                    'data' => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'User not allowed',
                'data' => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
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
                'title' => $data['title'],
                'photo' => base_url('/uploads/events/' . $data['photo']),
                'action' => 'event'
            ];
        }
        chunk_push_notification($registered_ids, $message);
    }


    public function eventcounter_post()
    {
        $username = $this->session->userdata("username");
        $usertype = $this->session->userdata("usertype");
        $photo = $this->session->userdata("photo");
        $name = $this->session->userdata("name");
        $eventID = $this->input->post('id');
        $status = $this->input->post('status');
        if ($eventID) {
            $have = $this->eventcounter_m->get_order_by_eventcounter(array("eventID" => $eventID, "username" => $username, "type" => $usertype), TRUE);
            if (customCompute($have)) {
                $array = array('status' => $status);
                $this->eventcounter_m->update($array, $have[0]->eventcounterID);
                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => $array,
                ], REST_Controller::HTTP_OK);
            } else {
                $array = array(
                    'eventID' => $eventID,
                    'username' => $username,
                    'type' => $usertype,
                    'photo' => $photo,
                    'name' => $name,
                    'status' => $status
                );
                $this->eventcounter_m->insert($array);
                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => $array,
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error',
                'data' => [],
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function update_post()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') && $this->session->userdata('usertypeID') == 1)) {

            $id = htmlentities(escapeString($this->uri->segment(5)));
            if ((int) $id) {
                $schoolyearID = $this->session->userdata("defaultschoolyearID");
                $this->data['event'] = $this->event_m->get_single_event(array('eventID' => $id, 'schoolyearID' => $schoolyearID));
                if (customCompute($this->data['event'])) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->response([
                            'status' => false,
                            'message' => $this->form_validation->error_array(),
                            'data' => [],
                        ], REST_Controller::HTTP_OK);
                    } else {

                        $currentDate = date('d-m-Y');
						$publishDate = $this->input->post("published_date");

						if ($publishDate <= $currentDate) {
								$published = 1;
						} else {
								$published = 2;
						}
                    
                        $explode = explode('-', $this->input->post("date"));
                        $fdate = date("Y-m-d", strtotime($explode[0]));
                        $ftime = date("H:i:s", strtotime($explode[0]));
                        $tdate = date("Y-m-d", strtotime($explode[1]));
                        $ttime = date("H:i:s", strtotime($explode[1]));
                        $array = array(
                            "title"      => $this->input->post("title"),
                            "details"    => $this->input->post("event_details"),
                            "fdate"      => $fdate,
                            "ftime"      => $ftime,
                            "tdate"      => $tdate,
                            "ttime"      => $ttime,
                            "published"  => $published,
                            "published_date" => date("Y-m-d", strtotime($this->input->post("published_date"))),
                            "enable_comment" => $this->input->post("enable_comment") ? $this->input->post("enable_comment") : 2,
                        );
                        if($this->event_m->update_event($array, $id)){
                           
                            $photos = $this->upload_data['files'];
							if (customCompute($photos)) {
								foreach ($photos as $key => $photo) {
									$photos[$key]['eventID'] = $id;
								}
								$this->event_media_m->insert_batch_event_media($photos);
							}
								
                            $this->response([
                                'status' => true,
                                'message' => 'Success',
                                'data' => $array,
                            ], REST_Controller::HTTP_OK);

                        }
                        
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Error',
                        'data' => [],
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Error',
                    'data' => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'User not allowed',
                'data' => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function update_old_post()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') && $this->session->userdata('usertypeID') == 1)) {

            $id = htmlentities(escapeString($this->uri->segment(5)));
            if ((int) $id) {
                $schoolyearID = $this->session->userdata("defaultschoolyearID");
                $this->data['event'] = $this->event_m->get_single_event(array('eventID' => $id, 'schoolyearID' => $schoolyearID));
                if (customCompute($this->data['event'])) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->response([
                            'status' => false,
                            'message' => $this->form_validation->error_array(),
                            'data' => [],
                        ], REST_Controller::HTTP_OK);
                    } else {

                        $currentDate = date('d-m-Y');
						$publishDate = $this->input->post("published_date");

						if ($publishDate <= $currentDate) {
								$published = 1;
						} else {
								$published = 2;
						}

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
						$postUsers = $_POST['users'];
					}

                        $explode = explode('-', $this->input->post("date"));
                        $fdate = date("Y-m-d", strtotime($explode[0]));
                        $ftime = date("H:i:s", strtotime($explode[0]));
                        $tdate = date("Y-m-d", strtotime($explode[1]));
                        $ttime = date("H:i:s", strtotime($explode[1]));
                        $array = array(
                            "title"      => $this->input->post("title"),
                            "details"    => $this->input->post("event_details"),
                            "users"      => $postUsers?serialize($postUsers):'',
                            "filterData" => $filterData,
							"status"     => $this->input->post("status") ? $this->input->post("status") : 'private',
                            "fdate"      => $fdate,
                            "ftime"      => $ftime,
                            "tdate"      => $tdate,
                            "ttime"      => $ttime,
                            "published"  => $published,
                            "published_date" => date("Y-m-d", strtotime($this->input->post("published_date"))),
                            "enable_comment" => $this->input->post("enable_comment") ? $this->input->post("enable_comment") : 2,
                        );
                        if($this->event_m->update_event($array, $id)){
                           
                            $photos = $this->upload_data['files'];
							if (customCompute($photos)) {
								foreach ($photos as $key => $photo) {
									$photos[$key]['eventID'] = $id;
								}
								$this->event_media_m->insert_batch_event_media($photos);
							}

                                $this->event_m->delete_event_users(['event_id'=> $id]);
								$feed = $this->feed_m->get_single_feed(array('itemID' => $id, 'itemname' => 'event'));
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

                            $this->response([
                                'status' => true,
                                'message' => 'Success',
                                'data' => $array,
                            ], REST_Controller::HTTP_OK);

                        }
                        
                    }
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Error',
                        'data' => [],
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Error',
                    'data' => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'User not allowed',
                'data' => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function index_delete()
    {
        if ((($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1 || permissionChecker('event_delete')))) {
            $id = htmlentities(escapeString($this->uri->segment(5)));
            if ((int) $id) {
                $event = $this->event_m->get_event($id);
                $schoolyearID = $this->session->userdata("defaultschoolyearID");
                $event = $this->event_m->get_single_event(array('eventID' => $id, 'schoolyearID' => $schoolyearID));
                if (customCompute($event)) {
                    if (config_item('demo') == false) {

                        if ($event->photo != 'holiday.png' && $event->photo != '') {
                            if ($event->photo != "" && file_exists(FCPATH . 'uploads/images/' . $event->photo)) {
                                unlink(FCPATH . 'uploads/events/' . $event->photo);
                            }
                        }
                    }
                    $this->event_m->delete_event($id);
                    $this->event_m->delete_event_users(['event_id'=> $id]);
								
                    $feed = $this->feed_m->get_single_feed(array('itemID' => $id,'itemname' => 'event'));
					if($feed){
						$this->feed_m->delete_feed($feed->feedID);
                        $this->feed_m->delete_feed_users(['feed_id'=>$feed->feedID]);
					}
                    $this->response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => $id,
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Error',
                        'data' => [],
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Error',
                    'data' => [],
                ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error',
                'data' => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function comment_add_post($id = '')
    {
        if(!$id){
            $this->response([
                'status' => false,
                'message' => 'Event ID is empty',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        $event = $this->event_m->get_single_event(['eventID' => $id]);
        if(!$event){
            $this->response([
                'status' => false,
                'message' => 'Event not found.',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        if($this->input->post('comment') == ''){
            $this->response([
                'status' => false,
                'message' => 'Comment is empty.',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }

            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $array['eventID']      = $id;
            $array['comment']      = $this->input->post('comment');
            $array['schoolyearID'] = $schoolyearID;
            $array['userID']       = $this->session->userdata("loginuserID");
            $array['usertypeID']   = $this->session->userdata("usertypeID");
            $array['create_date']  = date("Y-m-d H:i:s");
            $data = $this->event_comment_m->insert_event_comment($array);
            $insert_id = $this->db->insert_id();
            $comment = $this->event_comment_m->get_event_comment($insert_id);
            $allusers  = getAllSelectUser();
            $comment->name = $allusers[$comment->usertypeID][$comment->userID]->name;
            $comment->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
            if($data){
                $this->pushNotificationOfComment($array);
                $this->response([
                    'status'  => true,
                    'message' => 'Success',
                    'data'    => $comment,
                ], REST_Controller::HTTP_OK);
			}
    }

    public function delete_comment_get($id = '')
	{
            if(!$id){
                $this->response([
                    'status' => false,
                    'message' => 'Comment ID is empty',
                    'data' => [],
                ], REST_Controller::HTTP_NOT_FOUND);
            }

			$usertypeID = $this->session->userdata('usertypeID');
			$userID = $this->session->userdata('loginuserID');

				$comment = $this->event_comment_m->get_event_comment($id);
                if(!$comment){
                    $this->response([
                        'status' => false,
                        'message' => 'Comment not found.',
                        'data' => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
				$event = $this->event_m->get_event($comment->eventID);
				if (($usertypeID == $event->create_usertypeID && $userID == $event->create_userID) || ($usertypeID == 1)) {
					$this->event_comment_m->delete_event_comment($id);
					$this->response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => new stdClass(),
                    ], REST_Controller::HTTP_OK);
				}else{
                    $this->response([
                        'status' => false,
                        'message' => 'Not allowed.',
                        'data' => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
	}

    public function comment_edit_post($commentID = ''){
      
        if(!$commentID){
            $this->response([
                'status' => false,
                'message' => 'Comment ID is empty',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        if($this->input->post('comment') == ''){
            $this->response([
                'status' => false,
                'message' => 'Comment is empty.',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }
        
        $array['comment']      = $this->input->post('comment');
        $data = $this->event_comment_m->update_event_comment($array,$commentID);
        $comment = $this->event_comment_m->get_event_comment($commentID);
        $allusers  = getAllSelectUser();
        $comment->name = $allusers[$comment->usertypeID][$comment->userID]->name;
		$comment->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $comment,
        ], REST_Controller::HTTP_OK);


    }

    public function delete_media_get($id = '')
	{
            if(!$id){
                $this->response([
                    'status' => false,
                    'message' => 'Media ID is empty',
                    'data' => [],
                ], REST_Controller::HTTP_NOT_FOUND);
            }

			$usertypeID = $this->session->userdata('usertypeID');
			$userID = $this->session->userdata('loginuserID');

				$media = $this->event_media_m->get_event_media($id);
                if(is_null($media)){
                    $this->response([
                        'status' => false,
                        'message' => 'Media not found.',
                        'data' => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
				$event = $this->event_m->get_event($media->eventID);
				if (($usertypeID == $event->create_usertypeID && $userID == $event->create_userID) || ($usertypeID == 1)) {
					$this->event_media_m->delete_event_media($id);
					$this->response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => new stdClass(),
                    ], REST_Controller::HTTP_OK);
				}else{
                    $this->response([
                        'status' => false,
                        'message' => 'Not allowed.',
                        'data' => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
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

    function pushNotification($array)
	{
		$this->mobile_job_m->insert_job([
			'name' => 'sendEvent',
			'payload' => json_encode([
				'title' => 'Event ' . $array['title'] . ' has been added.', // title is compulsary
				'users' => $array['users'],
				'message' => $array['details']
			]),
		]);
	}

    function updatePushNotification($array, $postusers,$eventID)
	{
		if ($array['status'] == 'public') {

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
			$all_users = $newUsers;
		} else {
			$all_users = $postusers;
		}
		$sall_users = serialize($all_users);


		// update job
		$job = $this->job_m->get_single_job([
			'itemID' => $eventID,
			'status' => 'queued',
			'name'   => 'sendEvent'
		]);

		if($job){
			$this->job_m->update_job([
				'payload' => json_encode([
					'title' => 'Event ' . $array['title'] . ' has been added.', // title is compulsary
					  // title is necessary
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
				'name' => 'sendEvent',
				'payload' => json_encode([
					'title' => 'Event ' . $array['title'] . ' has been added.', // title is compulsary
					'users' => $sall_users,
					'message' => $array['details']
				]),
			],$mobile_job->id);
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
        $startDate = date('Y-m-d', strtotime("-14 days", strtotime($latestdate)));
        $endDate = $latestdate;


        return [$startDate, $endDate];
    }

}
