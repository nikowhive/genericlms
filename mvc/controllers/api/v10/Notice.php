<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Notice extends Api_Controller
{
    public function __construct()
    {
        
        parent::__construct();
        $this->load->model("job_m");
        $this->load->model('user_m');
        $this->load->model("alert_m");
        $this->load->model("feed_m");
        $this->load->model("notice_m");
        $this->load->model("student_m");
        $this->load->model('parents_m');
        $this->load->model('teacher_m');
        $this->load->model('fcmtoken_m');
        $this->load->model('smssettings_m');
        $this->load->model("mailandsms_m");
        $this->load->model("mobile_job_m");
        $this->load->model('systemadmin_m');
        $this->load->model('notice_media_m');
        $this->load->model('notice_comment_m');
        $this->load->library('form_validation');
        $this->load->model("pushsubscription_m");
        $this->load->library('session');
        $language = $this->session->userdata('lang');
        $this->lang->load('notice', $language);
    }

    public function index_get($page=1)
    {
        if (permissionChecker('notice_view')) {

            $this->data['userType'] = $this->session->userdata('usertypeID');
            $schoolyearID = $this->session->userdata("defaultschoolyearID");

            $page      = 20 * ($page - 1);
            $dbNotices = $this->notice_m->getRecentNotices(20, $page, $schoolyearID, '', '');
            $allusers  = getAllSelectUser();

            foreach($dbNotices as $key => $notice )
            {
                $noticeID = $notice->noticeID;
                $notice_medias = pluck($this->notice_media_m->get_order_by_notice_media(['noticeID' => $noticeID]),'obj');
                $dbNotices[$key]->media = $notice_medias;
               
                $comments = pluck($this->notice_comment_m->get_order_by_notice_comment(['noticeID' => $noticeID]),'obj');
                if(customCompute($comments)){
					foreach($comments as $k=>$comment){
						$comments[$k]->name = $allusers[$comment->usertypeID][$comment->userID]->name;
						$comments[$k]->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
					}
                    $dbNotices[$key]->comments = $comments;
                }else{
                    $dbNotices[$key]->comments  = [];
                }

            }
            $this->retdata['notices'] = $dbNotices; 

            $this->response([
                'status'  => true,
                'message' => 'Success',
                'count'   => count($dbNotices),
                'data'    => $dbNotices,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status'  => false,
                'message' => 'Error',
                'data'    => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function view_get($id = null)
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if ((int) $id) {
           
            $notice =  $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
            if (customCompute($notice)) {

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
    
                $feed = $this->feed_m->get_single_feed(array('itemID' => $id,'itemname' => 'notice'));
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

                $noticeID      = $notice->noticeID;
                $notice_medias = pluck($this->notice_media_m->get_order_by_notice_media(['noticeID' => $noticeID]),'obj');
                $notice->media = $notice_medias;
               
                $comments         = pluck($this->notice_comment_m->get_order_by_notice_comment(['noticeID' => $noticeID]),'obj');
                $notice->comments = $comments;

                $notice->users = $notice->users?unserialize($notice->users):'';
                $notice->filterData = $notice->filterData?unserialize($notice->filterData):'';

                $this->retdata['notice'] = $notice;

                $this->response([
                    'status'  => true,
                    'message' => 'Success',
                    'data'    => $this->retdata,
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status'  => false,
                    'message' => 'Error 404',
                    'data'    => [],
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status'  => false,
                'message' => 'Error 404',
                'data'    => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("notice_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]',
            ),
            array(
                'field' => 'date',
                'label' => $this->lang->line("notice_date"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid',
            ),
            array(
                'field' => 'notice',
                'label' => $this->lang->line("notice_notice"),
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

    protected function bulk_sms_rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("notice_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]',
            ),
            array(
                'field' => 'notice',
                'label' => $this->lang->line("notice_notice"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field'    => 'users[]',
                'label'    => 'users',
                'rules'    => 'trim|required|xss_clean',
                'errors'   => array(
                    'required' => 'You must provide users.',
                ),
            ),
           
        );
			
        return $rules;
    }

    protected function update_rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("notice_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]',
            ),
            array(
                'field' => 'date',
                'label' => $this->lang->line("notice_date"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid',
            ),
            array(
                'field' => 'notice',
                'label' => $this->lang->line("notice_notice"),
                'rules' => 'trim|required|xss_clean',
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
                'rules' => 'trim|required|max_length[60]|valid_email|xss_clean',
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("notice_subject"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("notice_message"),
                'rules' => 'trim|xss_clean',
            ),
            array(
                'field' => 'noticeID',
                'label' => $this->lang->line("notice_noticeID"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data',
            ),
        );
        return $rules;
    }

    public function date_valid($date)
    {
        if (strlen($date) < 10) {
            $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
            return FALSE;
        } else {
            $arr  = explode("-", $date);
            $dd   = $arr[0];
            $mm   = $arr[1];
            $yyyy = $arr[2];
            if (checkdate($mm, $dd, $yyyy)) {
                return TRUE;
            } else {
                $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                return FALSE;
            }
        }
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

    public function index_post()
    {
        if (($this->session->userdata('usertypeID') == 1) || permissionChecker('notice_add')) {

            if ($_POST) {
                $rules = $this->rules();

                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {

                    $this->response([
                        'status'  => false,
                        'message' => $this->form_validation->error_array(),
                        'data'    => [],
                    ], REST_Controller::HTTP_OK);
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
						$postUsers = $_POST['users'];
					}

                    $photo = $this->input->post('photo');
                    $array = array(
                        "title"             => $this->input->post("title"),
                        "users"             => $postUsers?serialize($postUsers):'',
                        "filterData"        => $filterData,
                        "notice"            => $this->input->post("notice"),
                        "status"            => $this->input->post("status") ? $this->input->post("status") : 'private',
                        "enable_comment"    => $this->input->post("enable_comment") ? $this->input->post("enable_comment") : 2,
                        'schoolyearID'      => $this->session->userdata('defaultschoolyearID'),
                        "date"              => date("Y-m-d", strtotime($this->input->post("date"))),
                        "create_date"       => date("Y-m-d H:i:s"),
                        "create_userID"     => $this->session->userdata('loginuserID'),
                        "create_usertypeID" => $this->session->userdata('usertypeID'),
                    );

                    $this->notice_m->insert_notice($array);
                    $insert_id = $this->db->insert_id();
                    
                    if($insert_id){

                        // save sms
                        if($smsFlag = $this->input->post("send_sms")){
                            $smsarray = array(
                                'users' => $postUsers?serialize($postUsers):'',
                                'type' => 'SMS',
                                'message' => $this->input->post("notice"),
                                'subject' => $this->input->post("title"),
                                'year' => date('Y'),
                                'senderusertypeID' => $this->session->userdata('usertypeID'),
                                'senderID' => $this->session->userdata('loginuserID')
                            );
                            $this->mailandsms_m->insert_mailandsms($smsarray);

                        }

                       // insert media
						$photos = $this->upload_data['files'];
						if (customCompute($photos)) {
							foreach ($photos as $key => $photo) {
								$photos[$key]['noticeID'] = $insert_id;
							}
							$this->notice_media_m->insert_batch_notice_media($photos);
						}

                        $this->feed_m->insert_feed(
                            array(
                                'itemID'         => $insert_id,
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
                      
                        if(customCompute($postUsers)){
                            $users = $postUsers;
                            $noticeUsers = [];
                            if(customCompute($users)){
                                foreach($users as $user){  
                                        $user_id   = substr($user, 0, -1);
                                        $user_type = substr($user, -1);
                                        $noticeUsers[] = [
                                              'notice_id'  => $insert_id,
                                              'user_id'    => $user_id,
                                              'usertypeID' => $user_type
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

                            $this->pushNotification($array,$insert_id,$smsFlag);
                            $this->alert_m->insert_alert(array('itemID' => $insert_id, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'notice'));
                                    
                            $array["id"] = $insert_id;
        
                            $this->response([
                                'status'  => true,
                                'message' => 'Success',
                                'data'    => $array,
                            ], REST_Controller::HTTP_OK);
                    
                    }
                   
                }
            } else {
                $this->response([
                    'status'  => false,
                    'message' => 'No fields values',
                    'data'    => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status'  => false,
                'message' => 'User not allowed',
                'data'    => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }


    function sendFcmNotification($users, $data)
    {
        $registered_ids = [];
        foreach ($users as $user) {
            $user_id    = substr($user, 0, -1);
            $user_type  = substr($user, -1);
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

    public function index_put()
    {
        if (($this->session->userdata('usertypeID') == 1) || permissionChecker('notice_edit')) {
            $input = json_decode(utf8_encode(file_get_contents('php://input')), true);
            if ($input == NULL) {
                $this->response([
                    'status'  => false,
                    'message' => 'No fields values',
                    'data'    => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $id = htmlentities(escapeString($this->uri->segment(5)));
            if ((int) $id) {
                $this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
                if ($this->data['notice']) {
                    $rules = $this->rules();
                    $this->form_validation->set_data($input);
                    $this->form_validation->set_rules($rules);

                    if ($this->form_validation->run() == false) {
                        $this->response([
                            'status'  => false,
                            'message' => $this->form_validation->error_array(),
                            'data'    => [],
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $array = array(
                            "title"  => inputCall("title"),
                            "notice" => inputCall("notice"),
                            "date"   => date("Y-m-d", strtotime(inputCall("date"))),
                        );

                        $this->notice_m->update_notice($array, $id);
                        $this->response([
                            'status'  => true,
                            'message' => 'Success',
                            'data'    => $array,
                        ], REST_Controller::HTTP_OK);
                    }
                }
            } else {
                $this->response([
                    'status'  => false,
                    'message' => 'Error',
                    'data'    => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status'  => false,
                'message' => 'User not allowed',
                'data'    => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function update_post()
    {
        if (($this->session->userdata('usertypeID') == 1) || permissionChecker('notice_edit')) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $id = htmlentities(escapeString($this->uri->segment(5)));
            if ((int) $id) {
                $this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
                if ($this->data['notice']) {
                    $rules = $this->rules();
                   
                   $this->form_validation->set_rules($rules);

                if ($this->form_validation->run() == false) {

                    $this->response([
                        'status'  => false,
                        'message' => $this->form_validation->error_array(),
                        'data'    => [],
                    ], REST_Controller::HTTP_OK);

                } else {

                    $array = array(
                        "title"               => $this->input->post("title"),
                        "notice"              => $this->input->post("notice"),
                        "enable_comment"      => $this->input->post("enable_comment") ? $this->input->post("enable_comment") : 2,
                        "date"                => date("Y-m-d", strtotime($this->input->post("date"))),
                    );

                    if($this->notice_m->update_notice($array, $id)){
                        $photos = $this->upload_data['files'];
                        if (customCompute($photos)) {
                            // $res = $this->notice_media_m->delete_batch_notice_media(array('noticeID' => $id));
                            foreach ($photos as $key => $photo) {
                                $photos[$key]['noticeID'] = $id;
                            }
                            $this->notice_media_m->insert_batch_notice_media($photos);
                        }

                        $this->response([
                            'status'  => true,
                            'message' => 'Success',
                            'data'    => $array,
                        ], REST_Controller::HTTP_OK);
                    }
                    }
                }
            } else {
                $this->response([
                    'status'  => false,
                    'message' => 'Error',
                    'data'    => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status'  => false,
                'message' => 'User not allowed',
                'data'    => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function update_old_post()
    {
        if (($this->session->userdata('usertypeID') == 1) || permissionChecker('notice_edit')) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $id = htmlentities(escapeString($this->uri->segment(5)));
            if ((int) $id) {
                $this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
                if ($this->data['notice']) {
                    $rules = $this->rules();
                   
                   $this->form_validation->set_rules($rules);

                if ($this->form_validation->run() == false) {

                    $this->response([
                        'status'  => false,
                        'message' => $this->form_validation->error_array(),
                        'data'    => [],
                    ], REST_Controller::HTTP_OK);

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
						$postUsers = $_POST['users'];
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

                    if($this->notice_m->update_notice($array, $id)){
                        $photos = $this->upload_data['files'];
                        if (customCompute($photos)) {
                            // $res = $this->notice_media_m->delete_batch_notice_media(array('noticeID' => $id));
                            foreach ($photos as $key => $photo) {
                                $photos[$key]['noticeID'] = $id;
                            }
                            $this->notice_media_m->insert_batch_notice_media($photos);
                        }

                                $this->notice_m->delete_notice_users(['notice_id'=>$id]);
								$feed = $this->feed_m->get_single_feed(array('itemID' => $id,'itemname' => 'notice'));
								$this->feed_m->delete_feed_users(['feed_id'=>$feed->feedID]);

								$this->feed_m->update_feed(
									array(
										'published_date' => date("Y-m-d", strtotime($this->input->post("date"))),
										'status'         => $this->input->post("status") ? $this->input->post("status") : 'private',
									),$feed->feedID
								);

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

                        $this->response([
                            'status'  => true,
                            'message' => 'Success',
                            'data'    => $array,
                        ], REST_Controller::HTTP_OK);
                    }
                    }
                }
            } else {
                $this->response([
                    'status'  => false,
                    'message' => 'Error',
                    'data'    => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status'  => false,
                'message' => 'User not allowed',
                'data'    => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function index_delete()
    {
        if (($this->session->userdata('usertypeID') == 1) || permissionChecker('notice_delete')) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $id = htmlentities(escapeString($this->uri->segment(5)));
            if ((int) $id) {
                $this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
                if ($this->data['notice']) {
                    $this->notice_m->delete_notice($id);
                    $this->notice_m->delete_notice_users(['notice_id'=>$id]);
                    $feed = $this->feed_m->get_single_feed(array('itemID' => $id,'itemname' => 'notice'));
					if($feed){
						$this->feed_m->delete_feed($feed->feedID);
                        $this->feed_m->delete_feed_users(['feed_id'=>$feed->feedID]);
					}
                    $this->response([
                        'status'     => true,
                        'message'    => 'Success',
                        'data'       => $id,
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    'status'  => false,
                    'message' => 'Error',
                    'data'    => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status'  => false,
                'message' => 'Error',
                'data'    => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

	function pushNotification($array,$noticeID,$smsFlag = false)
	{

        $newUsers = [];
        $mobileNumbers = [];
        if($array['status'] == 'public'){
			
			$teachers     = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
			$students     = $this->student_m->getAllActiveStudents(['active' => 1]);
			$parents      = $this->parents_m->getAllActiveParents(['active' => 1]);
			$systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
			$users        = $this->user_m->getAllActiveUsers(['active' => 1]);
			$all_users    = array_merge($teachers,$students,$parents,$systemadmins,$users);
			
			foreach($all_users as $all_user){

				$newUsers[] = $all_user['ID'].$all_user['usertypeID'];
                if($smsFlag){
                    $mobileNumbers[] = $this->getUserMobileNumbers($all_user['usertypeID'],$all_user['ID']);
                }
			}
			
		}else{
			$notice_users = $this->notice_m->get_notice_users_by_id($noticeID);
			foreach ($notice_users as $notice_user) {
				$newUsers[] = $notice_user['user_id'] . $notice_user['usertypeID'];
                if($smsFlag){
                    $mobileNumbers[] = $this->getUserMobileNumbers($notice_user['usertypeID'],$notice_user['user_id']);
                }
			}
		}

        $all_users = $newUsers;

        $uniqueMobileNumbers = serialize(array_unique($mobileNumbers));

		$sall_users = serialize($all_users);

        $this->job_m->insert_job([
			'name'        => 'sendNotice',
			'itemID'      => $noticeID,
			'payload'     => json_encode([
				'title'   => $array['title'],  // title is necessary
				'users'   => $sall_users,
                'mobiles' => $uniqueMobileNumbers
			]),
		]);

		$this->mobile_job_m->insert_job([
			'name'        => 'sendNotice',
            'itemID'      => $noticeID,
			'payload'     => json_encode([
				'title'   => $array['title'],  // title is necessary
				'users'   => $sall_users,
				'message' => $array['notice'],
                'mobiles' => $uniqueMobileNumbers
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
        $startDate = date('Y-m-d', strtotime("-14 days", strtotime($latestdate)));
        $endDate = $latestdate;


        return [$startDate, $endDate];
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

    public function photoupload()
    {
                
        if ($_FILES["photo"]['name'] != "") {
            $file_name        = $_FILES["photo"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512', $random . $this->input->post('title') . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if (customCompute($explode) >= 2) {
                $new_file = $file_name_rename . '.' . end($explode);
                $config['upload_path'] = "./uploads/notice";
                $config['allowed_types'] = "gif|jpg|png|jpeg";
                $config['file_name'] = $new_file;
                // $config['max_size'] = '5120';
                // $config['max_width'] = '3000';
                // $config['max_height'] = '3000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("photo")) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['file'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return false;
            }
        } 
    }

    public function comment_add_post($id = ''){
      
        if(!$id){
            $this->response([
                'status'  => false,
                'message' => 'Notice ID is empty',
                'data'    => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        $notice = $this->notice_m->get_single_notice(['noticeID' => $id]);
        if(!$notice){
            $this->response([
                'status'  => false,
                'message' => 'Notice not found.',
                'data'    => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        if($this->input->post('comment') == ''){
            $this->response([
                'status'  => false,
                'message' => 'Comment is empty.',
                'data'    => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        $array['noticeID']     = $id;
        $array['comment']      = $this->input->post('comment');
        $array['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
        $array['userID']       = $this->session->userdata("loginuserID");
        $array['usertypeID']   = $this->session->userdata("usertypeID");
        $array['create_date']  = date("Y-m-d H:i:s");
        $data = $this->notice_comment_m->insert_notice_comment($array);
        $insert_id = $this->db->insert_id();
        $comment = $this->notice_comment_m->get_notice_comment($insert_id);
        $allusers  = getAllSelectUser();
        $comment->name = $allusers[$comment->usertypeID][$comment->userID]->name;
		$comment->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
				
        if($data){
            $this->pushNotificationOfComment($array,$notice);
            $this->response([
                'status'  => true,
                'message' => 'Success',
                'data'    => $comment,
            ], REST_Controller::HTTP_OK);
        }
    }

    public function comment_edit_post($commentID = ''){
      
        if(!$commentID){
            $this->response([
                'status'  => false,
                'message' => 'Comment ID is empty',
                'data'    => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }
       
		
        if($this->input->post('comment') == ''){
            $this->response([
                'status'  => false,
                'message' => 'Comment is empty.',
                'data'    => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }
        
        $array['comment']      = $this->input->post('comment');
        $data = $this->notice_comment_m->update_notice_comment($array,$commentID);
        $comment = $this->notice_comment_m->get_notice_comment($commentID);

        $allusers  = getAllSelectUser();
        $comment->name = $allusers[$comment->usertypeID][$comment->userID]->name;
		$comment->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
        
        $this->response([
            'status'  => true,
            'message' => 'Success',
            'data'    => $comment,
        ], REST_Controller::HTTP_OK);


    }

    function pushNotificationOfComment($array,$noticeObj)
	{

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

    public function delete_comment_get($id = '')
	{
            if(!$id){
                $this->response([
                    'status'  => false,
                    'message' => 'Comment ID is empty',
                    'data'    => [],
                ], REST_Controller::HTTP_NOT_FOUND);
            }

			$usertypeID = $this->session->userdata('usertypeID');
			$userID = $this->session->userdata('loginuserID');

				$comment = $this->notice_comment_m->get_notice_comment($id);
                if(!$comment){
                    $this->response([
                        'status'  => false,
                        'message' => 'Comment not found.',
                        'data'    => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
				$notice = $this->notice_m->get_notice($comment->noticeID);
				if (($usertypeID == $notice->create_usertypeID && $userID == $notice->create_userID) || ($usertypeID == 1)) {
					$this->notice_comment_m->delete_notice_comment($id);
					$this->response([
                        'status'  => true,
                        'message' => 'Success',
                        'data'    => new stdClass(),
                    ], REST_Controller::HTTP_OK);
				}else{
                    $this->response([
                        'status'  => false,
                        'message' => 'Not allowed.',
                        'data'    => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
	}

    public function delete_media_get($id = '')
	{
            if(!$id){
                $this->response([
                    'status'  => false,
                    'message' => 'Media ID is empty',
                    'data'    => [],
                ], REST_Controller::HTTP_NOT_FOUND);
            }

			$usertypeID = $this->session->userdata('usertypeID');
			$userID = $this->session->userdata('loginuserID');

				$media = $this->notice_media_m->get_notice_media($id);
                if(is_null($media)){
                    $this->response([
                        'status'  => false,
                        'message' => 'Media not found.',
                        'data'    => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
				$notice = $this->notice_m->get_notice($media->noticeID);
				if (($usertypeID == $notice->create_usertypeID && $userID == $notice->create_userID) || ($usertypeID == 1)) {
					$this->notice_media_m->delete_notice_media($id);
					$this->response([
                        'status'  => true,
                        'message' => 'Success',
                        'data'    => new stdClass(),
                    ], REST_Controller::HTTP_OK);
				}else{
                    $this->response([
                        'status'  => false,
                        'message' => 'Not allowed.',
                        'data'    => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
	}

    public function getUserMobileNumbers($usertypeID,$userID){
        
        if($usertypeID == 2){
            $row = $this->teacher_m->general_get_teacher($userID);
            $mobileNumbers = $row->phone;
        }elseif($usertypeID == 3){
            $row = $this->student_m->get_single_stud(['studentID' => $userID]);
            $mobileNumbers = $row->phone;
        }elseif($usertypeID == 4){
            $row = $this->parents_m->get_single_parents(['parentsID' => $userID]);
            $mobileNumbers = $row->phone;
        }elseif($usertypeID == 1){
            $row = $this->systemadmin_m->get_single_systemadmin(['systemadminID' => $userID]);
            $mobileNumbers = $row->phone;
        }else{
            $row = $this->user_m->get_single_user(['userID' => $userID]);
            $mobileNumbers = $row->phone;
        }

        return $mobileNumbers;
    }


    public function bulk_sms_get($page = 1){

        if (permissionChecker('bulk_sms_view')) {

            $this->data['userType'] = $this->session->userdata('usertypeID');
            $schoolyearID = $this->session->userdata("defaultschoolyearID");

            $page      = 20 * ($page - 1);
            $dbNotices = $this->notice_m->getRecentSMSNotices(20, $page, $schoolyearID, '', '');
            
            $this->retdata['notices'] = $dbNotices; 

            $this->response([
                'status'  => true,
                'message' => 'Success',
                'count'   => count($dbNotices),
                'data'    => $dbNotices,
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status'  => false,
                'message' => 'User not allowed',
                'data'    => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }

        
    }

    public function bulk_sms_post()
    {
        if (permissionChecker('bulk_sms_add')) {

            if ($_POST) {

                $result = [];
                $rules = $this->bulk_sms_rules();

                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {

                    $this->response([
                        'status'  => false,
                        'message' => $this->form_validation->error_array(),
                        'data'    => [],
                    ], REST_Controller::HTTP_BAD_REQUEST);
                } else {

                    $credits = $this->smssettings_m->get_single_sparrow_sms_credits();
                    if($credits == 0){

                        $this->response([
                            'status'  => false,
                            'message' => 'No Credits Available.',
                            'data'    => [],
                        ], REST_Controller::HTTP_BAD_REQUEST);

                    }

                    $result = getSMSCredit();
                    if(isset($result->response_code) && $result->response_code != 200){
                        $this->response([
                            'status'  => false,
                            'message' => 'Something went wrong with sparrow sms.',
                            'data'    => [],
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }

                    if(isset($result->response_code) && $result->credits_available == 0){
                        $this->response([
                            'status'  => false,
                            'message' => 'No Credits Available.',
                            'data'    => [],
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }



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
						$postUsers = $_POST['users'];
					}

                    $mobileNumbers = [];
                    if(customCompute($postUsers)){
                            $users = $postUsers;
                            foreach($users as $user){  
                                    $user_id   = substr($user, 0, -1);
                                    $user_type = substr($user, -1);
                                    $mobileNumbers[] = $this->getUserMobileNumbers($user_type,$user_id);
                                    
                            }
                            $uniqueNumbers = array_unique($mobileNumbers);
                            $filtersNumbers = array_filter($uniqueNumbers);

                            if(count($filtersNumbers) > $credits){
                                $this->response([
                                    'status'  => false,
                                    'message' => 'Total number of sms has been exceeded than available credits .',
                                    'data'    => [],
                                ], REST_Controller::HTTP_BAD_REQUEST);
                            }

                            if(count($filtersNumbers) > $result->credits_available){
                                $this->response([
                                    'status'  => false,
                                    'message' => 'Total number of sms has been exceeded than available credits .',
                                    'data'    => [],
                                ], REST_Controller::HTTP_BAD_REQUEST);
                            }

                            
                            
                    }

                    // $photo = $this->input->post('photo');
                    $array = array(
                        "title"             => $this->input->post("title"),
                        "users"             => $postUsers?serialize($postUsers):'',
                        "filterData"        => $filterData,
                        "notice"            => $this->input->post("notice"),
                        "status"            => $this->input->post("status") ? $this->input->post("status") : 'private',
                        "enable_comment"    => $this->input->post("enable_comment") ? $this->input->post("enable_comment") : 2,
                        'schoolyearID'      => $this->session->userdata('defaultschoolyearID'),
                        "date"              => date("Y-m-d H:i:s"),
                        "create_date"       => date("Y-m-d H:i:s"),
                        "create_userID"     => $this->session->userdata('loginuserID'),
                        "create_usertypeID" => $this->session->userdata('usertypeID'),
                        "send_sms" => true
                    );

                    $this->notice_m->insert_notice($array);
                    $insert_id = $this->db->insert_id();
                    
                    if($insert_id){

                        // save sms
                       
                            $smsarray = array(
                                'users' => $postUsers?serialize($postUsers):'',
                                'type' => 'SMS',
                                'message' => $this->input->post("notice"),
                                'subject' => $this->input->post("title"),
                                'year' => date('Y'),
                                'senderusertypeID' => $this->session->userdata('usertypeID'),
                                'senderID' => $this->session->userdata('loginuserID')
                            );
                            $this->mailandsms_m->insert_mailandsms($smsarray);

                        $this->feed_m->insert_feed(
                            array(
                                'itemID'         => $insert_id,
                                'userID'         => $this->session->userdata("loginuserID"),
                                'usertypeID'     => $this->session->userdata('usertypeID'),
                                'itemname'       => 'notice',
                                'schoolyearID'   => $this->session->userdata('defaultschoolyearID'),
                                'published'      => 1,
                                'published_date' => date("Y-m-d"),
                                'status'         => $this->input->post("status") ? $this->input->post("status") : 'private',
                            )
                        );
                        $feedID = $this->db->insert_id();
                      
                        if(customCompute($postUsers)){
                            $users = $postUsers;
                            $noticeUsers = [];
                            if(customCompute($users)){
                                foreach($users as $user){  
                                        $user_id   = substr($user, 0, -1);
                                        $user_type = substr($user, -1);
                                        $noticeUsers[] = [
                                              'notice_id'  => $insert_id,
                                              'user_id'    => $user_id,
                                              'usertypeID' => $user_type
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

                            $this->pushNotification($array,$insert_id,true);
                            $this->alert_m->insert_alert(array('itemID' => $insert_id, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'notice'));
                                    
                            $array["id"] = $insert_id;

                            if($array['create_usertypeID'] == 1) {
                                $user = $this->systemadmin_m->get_individual_systemadmin($array['create_userID']);
                            } else if($array['create_usertypeID'] == 2) {
                                $user = $this->teacher_m->get_individual_teacher($array['create_userID']);
                            } else if($array['create_usertypeID'] == 3) {
                                $user = $this->student_m->get_individual_student($array['create_userID']);
                            } else if($array['create_usertypeID'] == 4) {
                                $user = $this->parents_m->get_individual_parents($array['create_userID']);
                            } else {
                                $user = $this->user_m->get_individual_user($array['create_userID']);
                            }

                            $array['created_by'] = $user->name;
        
                            $this->response([
                                'status'  => true,
                                'message' => 'Success',
                                'data'    => $array,
                            ], REST_Controller::HTTP_OK);
                    
                    }
                   
                }
            } else {
                $this->response([
                    'status'  => false,
                    'message' => 'No fields values',
                    'data'    => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status'  => false,
                'message' => 'User not allowed',
                'data'    => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }


}