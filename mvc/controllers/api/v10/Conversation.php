<?php 
use Restserver\Libraries\REST_Controller;

defined('BASEPATH') OR exit('No direct script access allowed');

class Conversation extends Api_Controller
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
    public function __construct()
    {
        parent::__construct();
        $this->load->model('conversation_m');
        $this->load->model('student_m');
        $this->load->model('parents_m');
        $this->load->model('teacher_m');
        $this->load->model('systemadmin_m');
        $this->load->model('user_m');
        $this->load->model('alert_m');
		$this->load->model("fcmtoken_m");
        $this->load->library('form_validation');
        $this->load->model('job_m');
        $this->load->model('mobile_job_m');
    }

    protected function rules($reply = false,$edit = false)
    {
        $rules = [
            [
                'field' => 'message',
                'label' => 'message',
                'rules' => 'trim|required|xss_clean|max_length[500]'
            ],
            [
                'field' => 'subject',
                'label' => 'subject',
                'rules' => 'trim|required|xss_clean|max_length[250]'
            ],
            [
                'field' => 'users[]',
                'label' => $this->lang->line("notice_users"),
                'rules' => 'trim|required|xss_clean',
                'errors' => array(
                    'required' => 'You must provide users.',
                ),
            ],
        ];

        if(!empty($_FILES) && isset($_FILES["attachment"]) && $_FILES["attachment"]['name'] != "") {
            $rules[] = [
                'field' => 'attachment',
                'label' => $this->lang->line("attachment"),
                'rules' => 'trim|xss_clean|max_length[500]|callback_fileUpload'
            ];
        }

        if(!empty($_FILES) && isset($_FILES["group_photo"]) && $_FILES["group_photo"]['name'] != "") {
            $rules[] = [
                'field' => 'group_photo',
                'label' => $this->lang->line("group_photo"),
                'rules' => 'trim|xss_clean|max_length[500]|callback_photoUpload'
            ];
        }

        if($reply) {
            unset($rules[1], $rules[2]);
            if(!$edit){
                $rules[] = [
                    'field' => 'conversation_id',
                    'label' => 'conversation id',
                    'rules' => 'trim|required|xss_clean|max_length[500]'
                ];
            }
            
        }
        return $rules;
    }

    protected function update_rules()
    {
        $rules = [
            [
                'field' => 'group_name',
                'label' => 'Group Name',
                'rules' => 'trim|required|xss_clean|max_length[500]'
            ],
            [
                'field' => 'subject',
                'label' => 'subject',
                'rules' => 'trim|required|xss_clean|max_length[250]'
            ]
        ];

        if(!empty($_FILES) && isset($_FILES["group_photo"]) && $_FILES["group_photo"]['name'] != "") {
            $rules[] = [
                'field' => 'group_photo',
                'label' => $this->lang->line("group_photo"),
                'rules' => 'trim|xss_clean|max_length[500]|callback_photoUpload'
            ];
        }
        return $rules;
    }

    protected function bulk_rules($reply = false)
    {
        $rules = [
            [
                'field' => 'message',
                'label' => 'message',
                'rules' => 'trim|required|xss_clean|max_length[500]'
            ],
            [
                'field' => 'subject',
                'label' => 'subject',
                'rules' => 'trim|required|xss_clean|max_length[250]'
            ]
        ];

        if(!empty($_FILES) && isset($_FILES["attachment"]) && $_FILES["attachment"]['name'] != "") {
            $rules[] = [
                'field' => 'attachment',
                'label' => $this->lang->line("attachment"),
                'rules' => 'trim|xss_clean|max_length[500]|callback_fileUpload'
            ];
        }

        if(!empty($_FILES) && isset($_FILES["group_photo"]) && $_FILES["group_photo"]['name'] != "") {
            $rules[] = [
                'field' => 'group_photo',
                'label' => $this->lang->line("group_photo"),
                'rules' => 'trim|xss_clean|max_length[500]|callback_photoUpload'
            ];
        }

        if($reply) {
            unset($rules[1], $rules[2]);
            $rules[] = [
                'field' => 'conversation_id',
                'label' => 'conversation id',
                'rules' => 'trim|required|xss_clean|max_length[500]'
            ];
        }
        return $rules;
    }

    public function conversation_get()
    {
        $conversations = $this->conversation_m->get_my_conversations();
        foreach($conversations as $index => $conversation) {
            $conversation_user = $this->conversation_m->get_latest_users_by_id($conversation->conversation_id);
            $conversations[$index]->msg = $conversation_user[0]->msg;
            $conversations[$index]->senders = $conversation_user;
        }
        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $conversations,
        ], REST_Controller::HTTP_OK);
    }

    public function draft_get()
    {
        $conversations = $this->conversation_m->get_my_conversations_draft();
        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $conversations,
        ], REST_Controller::HTTP_OK);
    }

    public function sent_get()
    {
        $conversations = $this->conversation_m->get_my_conversations_sent();
        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $conversations,
        ], REST_Controller::HTTP_OK);
    }

    public function trash_get()
    {
        $conversations = $this->conversation_m->get_my_conversations_trash();
        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $conversations,
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($conversationID = null)
    {
        $userTypeID     = $this->session->userdata("usertypeID");
        $userID         = $this->session->userdata("loginuserID");
        if ( (int) $conversationID ) {
            $conversationUser = $this->conversation_m->user_check($conversationID, $userID, $userTypeID);
            if ( customCompute($conversationUser) && $conversationUser->trash != 2 ) {
                $conversations = $this->conversation_m->get_conversation_msg_by_id($conversationID);
                foreach($conversations as $conversation) {
                    if($conversation->usertypeID == 1) {
                        $user = $this->systemadmin_m->get_individual_systemadmin($conversation->user_id);
                    } else if($conversation->usertypeID == 2) {
                        $user = $this->teacher_m->get_individual_teacher($conversation->user_id);
                    } else if($conversation->usertypeID == 3) {
                        $user = $this->student_m->get_individual_student($conversation->user_id);
                    } else if($conversation->usertypeID == 4) {
                        $user = $this->parents_m->get_individual_parents($conversation->user_id);
                    } else {
                        $user = $this->user_m->get_individual_user($conversation->user_id);
                    }
                    $conversation->name = $user->name;
                    $conversation->photo = $user->photo;
                }
                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => $conversations,
                ], REST_Controller::HTTP_OK);
            }
        }
        $this->response([
            'status' => false,
            'message' => 'Error 404',
            'data' => [],
        ], REST_Controller::HTTP_NOT_FOUND);
    }

    public function create_post()
    {
        $userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");
        if ( $_POST ) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->response([
                    'status' => false,
                    'message' => $this->form_validation->error_array(),
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            } else {
                $conversation = [
                    'create_date' => date("Y-m-d H:i:s"),
                    'modify_date' => date("Y-m-d H:i:s"),
                    'draft'       => ( ( $this->input->post('draft') == true ) ? 1 : 0 )
                ];
                $conversationID = $this->conversation_m->insert_conversation($conversation);

                $conversationUser = [
                    'conversation_id' => $conversationID,
                    'user_id'         => $userID,
                    'usertypeID'      => $userTypeID,
                    'is_sender'       => 1,
                ];
                $this->conversation_m->insert_conversation_user($conversationUser);

                $conversationMessage = [
                    'user_id'          => $userID,
                    'usertypeID'       => $userTypeID,
                    'group_name'       => $this->input->post('group_name'),
                    'subject'          => $this->input->post('subject'),
                    'msg'              => $this->input->post('message'),
                    'enable_reply'     => $this->input->post('enable_reply') == 0?0:1,
                    'create_date'      => date("Y-m-d H:i:s"),
                    'modify_date'      => date("Y-m-d H:i:s"),
                    'start'            => 1,
                    
                ];

                if(!empty($_FILES) && isset($_FILES["attachment"]) && $_FILES["attachment"]['name'] != "") {
                    $conversationMessage['attach'] = $this->upload_data['file']['attach'];
                    $conversationMessage['attach_file_name'] = $this->upload_data['file']['attach_file_name'];
                }

                if(!empty($_FILES) && isset($_FILES["group_photo"]) && $_FILES["group_photo"]['name'] != "") {
                    $conversationMessage['group_photo'] = $this->upload_data['file']['group_file_name'];
                }

                $this->_messageCreatefor($conversationID, $conversationMessage);
                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => [
                        'conversation_id' => $conversationID
                    ],
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'No fields values',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function bulk_post()
    {
        $userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");
        if ( $_POST ) {
            $rules = $this->bulk_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->response([
                    'status' => false,
                    'message' => $this->form_validation->error_array(),
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            } else {
                $conversation = [
                    'create_date' => date("Y-m-d H:i:s"),
                    'modify_date' => date("Y-m-d H:i:s"),
                    'draft'       => ( ( $this->input->post('draft') == true ) ? 1 : 0 )
                ];
                $conversationID = $this->conversation_m->insert_conversation($conversation);

                $conversationUser = [
                    'conversation_id' => $conversationID,
                    'user_id'         => $userID,
                    'usertypeID'      => $userTypeID,
                    'is_sender'       => 1,
                ];
                $this->conversation_m->insert_conversation_user($conversationUser);

                $conversationMessage = [
                    'user_id'          => $userID,
                    'usertypeID'       => $userTypeID,
                    'group_name'       => $this->input->post('group_name'),
                    'subject'          => $this->input->post('subject'),
                    'msg'              => $this->input->post('message'),
                    'enable_reply'     => $this->input->post('enable_reply') == 0?0:1,
                    'create_date'      => date("Y-m-d H:i:s"),
                    'modify_date'      => date("Y-m-d H:i:s"),
                    'start'            => 1,
                    
                ];

                if(!empty($_FILES) && $_FILES["attachment"]['name'] != "") {
                    $conversationMessage['attach'] = $this->upload_data['file']['attach'];
                    $conversationMessage['attach_file_name'] = $this->upload_data['file']['attach_file_name'];
                }

                if(!empty($_FILES) && isset($_FILES["group_photo"]) && $_FILES["group_photo"]['name'] != "") {
                    $conversationMessage['group_photo'] = $this->upload_data['file']['group_file_name'];
                }

                $this->bulk_messageCreatefor($conversationID, $conversationMessage,'all');
                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => [
                        'conversation_id' => $conversationID
                    ],
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'No fields values',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function bulk_student_post()
    {
        $userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");
        if ( $_POST ) {
            $rules = $this->bulk_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->response([
                    'status' => false,
                    'message' => $this->form_validation->error_array(),
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            } else {
                $conversation = [
                    'create_date' => date("Y-m-d H:i:s"),
                    'modify_date' => date("Y-m-d H:i:s"),
                    'draft'       => ( ( $this->input->post('draft') == true ) ? 1 : 0 )
                ];
                $conversationID = $this->conversation_m->insert_conversation($conversation);

                $conversationUser = [
                    'conversation_id' => $conversationID,
                    'user_id'         => $userID,
                    'usertypeID'      => $userTypeID,
                    'is_sender'       => 1,
                ];
                $this->conversation_m->insert_conversation_user($conversationUser);

                $conversationMessage = [
                    'user_id'          => $userID,
                    'usertypeID'       => $userTypeID,
                    'group_name'       => $this->input->post('group_name'),
                    'subject'          => $this->input->post('subject'),
                    'msg'              => $this->input->post('message'),
                    'enable_reply'     => $this->input->post('enable_reply') == 0?0:1,
                    'create_date'      => date("Y-m-d H:i:s"),
                    'modify_date'      => date("Y-m-d H:i:s"),
                    'start'            => 1,
                    
                ];

                if(!empty($_FILES) && $_FILES["attachment"]['name'] != "") {
                    $conversationMessage['attach'] = $this->upload_data['file']['attach'];
                    $conversationMessage['attach_file_name'] = $this->upload_data['file']['attach_file_name'];
                }

                if(!empty($_FILES) && isset($_FILES["group_photo"]) && $_FILES["group_photo"]['name'] != "") {
                    $conversationMessage['group_photo'] = $this->upload_data['file']['group_file_name'];
                }

                $this->bulk_messageCreatefor($conversationID, $conversationMessage,'student');
                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => [
                        'conversation_id' => $conversationID
                    ],
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'No fields values',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function bulk_parent_post()
    {
        $userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");
        if ( $_POST ) {
            $rules = $this->bulk_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->response([
                    'status' => false,
                    'message' => $this->form_validation->error_array(),
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            } else {
                $conversation = [
                    'create_date' => date("Y-m-d H:i:s"),
                    'modify_date' => date("Y-m-d H:i:s"),
                    'draft'       => ( ( $this->input->post('draft') == true ) ? 1 : 0 )
                ];
                $conversationID = $this->conversation_m->insert_conversation($conversation);

                $conversationUser = [
                    'conversation_id' => $conversationID,
                    'user_id'         => $userID,
                    'usertypeID'      => $userTypeID,
                    'is_sender'       => 1,
                ];
                $this->conversation_m->insert_conversation_user($conversationUser);

                $conversationMessage = [
                    'user_id'          => $userID,
                    'usertypeID'       => $userTypeID,
                    'group_name'       => $this->input->post('group_name'),
                    'subject'          => $this->input->post('subject'),
                    'msg'              => $this->input->post('message'),
                    'enable_reply'     => $this->input->post('enable_reply') == 0?0:1,
                    'create_date'      => date("Y-m-d H:i:s"),
                    'modify_date'      => date("Y-m-d H:i:s"),
                    'start'            => 1,
                    
                ];

                if(!empty($_FILES) && $_FILES["attachment"]['name'] != "") {
                    $conversationMessage['attach'] = $this->upload_data['file']['attach'];
                    $conversationMessage['attach_file_name'] = $this->upload_data['file']['attach_file_name'];
                }

                if(!empty($_FILES) && isset($_FILES["group_photo"]) && $_FILES["group_photo"]['name'] != "") {
                    $conversationMessage['group_photo'] = $this->upload_data['file']['group_file_name'];
                }

                $this->bulk_messageCreatefor($conversationID, $conversationMessage,'parent');
                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => [
                        'conversation_id' => $conversationID
                    ],
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'No fields values',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function bulk_employee_post()
    {
        $userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");
        if ( $_POST ) {
            $rules = $this->bulk_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->response([
                    'status' => false,
                    'message' => $this->form_validation->error_array(),
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            } else {
                $conversation = [
                    'create_date' => date("Y-m-d H:i:s"),
                    'modify_date' => date("Y-m-d H:i:s"),
                    'draft'       => ( ( $this->input->post('draft') == true ) ? 1 : 0 )
                ];
                $conversationID = $this->conversation_m->insert_conversation($conversation);

                $conversationUser = [
                    'conversation_id' => $conversationID,
                    'user_id'         => $userID,
                    'usertypeID'      => $userTypeID,
                    'is_sender'       => 1,
                ];
                $this->conversation_m->insert_conversation_user($conversationUser);

                $conversationMessage = [
                    'user_id'          => $userID,
                    'usertypeID'       => $userTypeID,
                    'group_name'       => $this->input->post('group_name'),
                    'subject'          => $this->input->post('subject'),
                    'msg'              => $this->input->post('message'),
                    'enable_reply'     => $this->input->post('enable_reply') == 0?0:1,
                    'create_date'      => date("Y-m-d H:i:s"),
                    'modify_date'      => date("Y-m-d H:i:s"),
                    'start'            => 1,
                    
                ];

                if(!empty($_FILES) && $_FILES["attachment"]['name'] != "") {
                    $conversationMessage['attach'] = $this->upload_data['file']['attach'];
                    $conversationMessage['attach_file_name'] = $this->upload_data['file']['attach_file_name'];
                }

                if(!empty($_FILES) && isset($_FILES["group_photo"]) && $_FILES["group_photo"]['name'] != "") {
                    $conversationMessage['group_photo'] = $this->upload_data['file']['group_file_name'];
                }

                $this->bulk_messageCreatefor($conversationID, $conversationMessage,'employee');
                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => [
                        'conversation_id' => $conversationID
                    ],
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'No fields values',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function reply_post()
    {
        $userTypeID                 = $this->session->userdata("usertypeID");
        $userID                     = $this->session->userdata("loginuserID");
        if ( $_POST ) {
            $rules = $this->rules(true);
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->response([
                    'status' => false,
                    'message' => $this->form_validation->error_array(),
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            } else {
                $conversationID = $this->input->post('conversation_id');
                $conversationMessage = [
                    'conversation_id'  => $conversationID,
                    'user_id'          => $userID,
                    'usertypeID'       => $userTypeID,
                    'msg'              => $this->input->post('message'),
                    'create_date'      => date("Y-m-d H:i:s"),
                    'modify_date'      => date("Y-m-d H:i:s"),
                ];

                if(!empty($_FILES) && $_FILES["attachment"]['name'] != "") {
                    $conversationMessage['attach'] = $this->upload_data['file']['attach'];
                    $conversationMessage['attach_file_name'] = $this->upload_data['file']['attach_file_name'];
                }

                $messageID = $this->conversation_m->insert_conversation_msg($conversationMessage);
                $this->conversation_m->update_conversation(['modify_date' => date("Y-m-d H:i:s")], $conversationID);
                $conversation = $this->conversation_m->get_single_conversation_msg(['conversation_id' => $conversationID]);

                $conversation_users = $this->conversation_m->get_conversation_users_by_id($conversationID);

                $all_users = [];
                foreach($conversation_users as $conversation_user){
                if($conversation_user->user_id != $this->session->userdata("loginuserID") || $conversation_user->usertypeID != $this->session->userdata("usertypeID") ) {
                    $all_users[] = [
                        'ID' => $conversation_user->user_id,
                        'usertypeID' => $conversation_user->usertypeID
                    ];
                }
                }

                $msg = count($all_users) == 1?$conversationMessage['msg']:$this->session->userdata('name').' : '.$conversationMessage['msg'];
                
                $array = [
                    'subject' => $conversation->group_name?$conversation->group_name:$conversation->subject,
                    'msg' => $msg,
                    'users' => $all_users,
                    'conversationID' => $conversationID
                ];

                $this->addToCronJob($array);


                // $registered_ids = [];
                // foreach($conversation_users as $conversation) {
                //     if($conversation->user_id != $this->session->userdata("loginuserID") || $conversation->usertypeID != $this->session->userdata("usertypeID") ) {
                //         $push_users = pluck($this->fcmtoken_m->get_order_by_fcm_token(['create_userID' => $conversation->user_id, 'create_usertypeID' => $conversation->usertypeID]), 'fcm_token');
                //         if($push_users) {
                //             $registered_ids = array_merge($registered_ids, $push_users);
                //         } 
                //     }
                // }
                // $push_message['data'] = [
                //     'message' => $this->session->userdata('name').' : '.$conversationMessage['msg'],
                //     'title' => $this->session->userdata('name'),
                //     'action' => 'message',
                //     'id' => $conversationID
                // ];

                // sendNotification($registered_ids, $push_message);

                // if ( $messageID > 0 ) {
                //     $this->alert_m->insert_alert([
                //         'itemID'     => $messageID,
                //         "userID"     => $this->session->userdata("loginuserID"),
                //         'usertypeID' => $this->session->userdata('usertypeID'),
                //         'itemname'   => 'message'
                //     ]);
                // }

                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => [
                        'conversation_id' => $conversationID
                    ],
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'No fields values',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function conversation_users_list_get($conversationID, $page = 1)
    {
        $page  = 20 * ($page - 1);
        $conversation_user = $this->conversation_m->get_all_users_by_id($conversationID, $page);
        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $conversation_user
        ], REST_Controller::HTTP_OK);
    }


    public function users_get() {
        $userTypeID = $this->session->userdata("usertypeID");
        if($userTypeID == 1) {
            $admins = $this->student_m->get_admin();
            $students = $this->student_m->get_allstudents();
            $parents = $this->student_m->get_parents_with_child();
            $teachers = $this->student_m->get_teachers();
            $accountants = $this->student_m->get_accountant();
            $librarians = $this->student_m->get_librarian();
            $receptionists = $this->student_m->get_receptionist();
            $moderators = $this->student_m->get_moderator();

            $all_users = array_merge($students, $parents, $teachers, $admins, $accountants, $librarians, $receptionists, $moderators);
        } elseif($userTypeID == 2) {
            $admins = $this->student_m->get_admin();
            $teachers = $this->student_m->get_teachers();
            $students = $this->student_m->get_allstudents();
            $parents = $this->student_m->get_parents_with_child();

            $all_users = array_merge($students, $parents, $teachers, $admins);
        } elseif($userTypeID == 3) {
            $teachers = $this->student_m->get_teachers();

            $all_users = $teachers;
        } elseif($userTypeID == 4) {
            $teachers = $this->student_m->get_teachers();

            $all_users = $teachers;
        } elseif($userTypeID == 5 || $userTypeID == 6 || $userTypeID == 7 || $userTypeID == 8) {
            $admins = $this->student_m->get_admin();

            $all_users = $admins;
        };
        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $all_users,
        ], REST_Controller::HTTP_OK);
    }

    private function _alertPost( $conversationID = 0 )
    {
        $pluckMessage = pluck($this->alert_m->get_order_by_alert([
            "userID"     => $this->session->userdata("loginuserID"),
            'usertypeID' => $this->session->userdata('usertypeID'),
            'itemname'   => 'message'
        ]), 'itemname', 'itemID');

        $messages = $this->conversation_m->get_conversation_msg_by_id($conversationID);
        if ( customCompute($messages) ) {
            foreach ( $messages as $message ) {
                if ( !isset($pluckMessage[ $message->msg_id ]) ) {
                    $this->alert_m->insert_alert([
                        'itemID'     => $message->msg_id,
                        "userID"     => $this->session->userdata("loginuserID"),
                        'usertypeID' => $this->session->userdata('usertypeID'),
                        'itemname'   => 'message'
                    ]);
                }
            }
        }
    }

    private function _messageCreatefor( $conversationID = 0, $message = [] )
    {
        $users = $_POST['users'];
        $all_users = [];
        if ( customCompute($users) ) {
            $conversation = [];
        	$registered_ids = [];
            foreach ( $users as $user ) {
                $uarray = explode('/',$user);

                if($this->session->userdata("loginuserID") != $uarray[0] || $this->session->userdata("usertypeID") != $uarray[1])
                    { 
                        $all_users[] = [
                            'ID' => $uarray[0],
                            'usertypeID' => $uarray[1]
                        ];
                    }

                $conversation[] = [
                    'conversation_id' => $conversationID,
                    "user_id"         => $uarray[0],
                    "usertypeID"      => $uarray[1],
                    'is_sender'       => 0
                ];
                //$push_users = pluck($this->fcmtoken_m->get_order_by_fcm_token(['create_userID' => $uarray[0], 'create_usertypeID' => $uarray[1]]), 'fcm_token');
                //if($push_users) {
                  //  $registered_ids = array_merge($registered_ids, $push_users);
                //}
                // $push_message['data'] = [
                //     'message' => $this->session->userdata('name').' : '.$message['msg'],
                //     'title' => $message['subject'],
                //     'action' => 'message',
                //     'id' => $conversationID
                // ];
            } 

            $msg = count($all_users) == 1?$message['msg']:$this->session->userdata('name').' : '.$message['msg'];
                
            $array = [
                'subject' => $message['group_name']?$message['group_name']:$message['subject'],
                'msg' => $msg,
                'users' => $all_users,
                'conversationID' => $conversationID
            ];

            $this->addToCronJob($array);
           // chunk_push_notification($registered_ids, $push_message);
            $message['conversation_id'] = $conversationID;

            $this->conversation_m->batch_insert_conversation_user($conversation);
            $messageID = $this->conversation_m->insert_conversation_msg($message);

            // if ( $messageID > 0 ) {
            //     $this->alert_m->insert_alert([
            //         'itemID'     => $messageID,
            //         "userID"     => $this->session->userdata("loginuserID"),
            //         'usertypeID' => $this->session->userdata('usertypeID'),
            //         'itemname'   => 'message'
            //     ]);
            // }
        }
    }

    private function bulk_messageCreatefor( $conversationID = 0, $message = [],$type )
    {
               
        if($type == 'student'){

            $students = $this->student_m->getAllActiveStudents(['active' => 1]);
            $all_users = $students;
       
        }elseif($type == 'parent'){

            $parents = $this->parents_m->getAllActiveParents(['active' => 1]);
            $all_users = $parents;
       
        }elseif($type == 'employee'){
           
            $teachers = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
            $systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
            $users = $this->user_m->getAllActiveUsers(['active' => 1]);
            $all_users = array_merge( $teachers,$systemadmins,$users);
       
        }else{
            $teachers = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
            $students = $this->student_m->getAllActiveStudents(['active' => 1]);
            $parents = $this->parents_m->getAllActiveParents(['active' => 1]);
            $systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
            $users = $this->user_m->getAllActiveUsers(['active' => 1]);
            $all_users = array_merge($teachers,$students,$parents,$systemadmins,$users);
        }

        if ( customCompute($all_users) ) {
            
            $conversation = [];
        	
            foreach ( $all_users as $user ) {

                $conversation[] = [
                    'conversation_id' => $conversationID,
                    "user_id"         => $user['ID'],
                    "usertypeID"      => $user['usertypeID'],
                    'is_sender'       => 0
                ];
            } 


            $msg = count($all_users) == 1?$message['msg']:$this->session->userdata('name').' : '.$message['msg'];
              
            $array = [
                'subject' => $message['group_name']?$message['group_name']:$message['subject'],
                'msg' => $msg,
                'users' => $all_users,
                'conversationID' => $conversationID
            ];

            // sendNotification($registered_ids, $push_message);
            $this->addToCronJob($array);
            $message['conversation_id'] = $conversationID;

            $this->conversation_m->batch_insert_conversation_user($conversation);
            $messageID = $this->conversation_m->insert_conversation_msg($message);

            // if ( $messageID > 0 ) {
            //     $this->alert_m->insert_alert([
            //         'itemID'     => $messageID,
            //         "userID"     => $this->session->userdata("loginuserID"),
            //         'usertypeID' => $this->session->userdata('usertypeID'),
            //         'itemname'   => 'message'
            //     ]);
            // }
        }
    }

    function addToCronJob($array)
	{
		$this->mobile_job_m->insert_job([
			'name' => 'sendConversationMsg',
			'payload' => json_encode([
				'title' => $array['subject'],
                'message' => $array['msg'],
				'users' => $array['users'],
                'conversationID' => $array['conversationID']
			]),
		]);
	}

  

    public function unique_message($message)
    {
        if ( $message == '' && $_FILES["attachment"]['name'] == "") {
            $this->form_validation->set_message("unique_message", "The %s field is required");
            return false;
        }
        return true;
    }

    public function fileUpload()
    {
        if ( $_FILES["attachment"]['name'] != "" ) {
            $file_name        = $_FILES["attachment"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512',
                $random . $this->session->userdata('username') . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if ( customCompute($explode) >= 2 ) {
                if ( preg_match('/\s/', $file_name) ) {
                    $file_name = str_replace(' ', '_', $file_name);
                }
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/attach";
                $config['allowed_types'] = "gif|jpg|png|pdf|doc|csv|docx|xlsx|xl";
                $config['file_name']     = $new_file;
                // $config['max_size']      = '5120';
                // $config['max_width']     = '3000';
                // $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("attachment") ) {
                    $this->form_validation->set_message("fileUpload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['file'] = [ 'attach' => $file_name, 'attach_file_name' => $new_file ];
                    return true;
                }
            } else {
                $this->form_validation->set_message("fileUpload", "Invalid file");
                return false;
            }
        } else {
            $this->upload_data['file'] = [ 'attach' => NULL, 'attach_file_name' => NULL ];
            return true;
        }
    }

    public function photoUpload()
    {
        if ( $_FILES["group_photo"]['name'] != "" ) {
            $file_name        = $_FILES["group_photo"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512',
                $random . $this->session->userdata('username') . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if ( customCompute($explode) >= 2 ) {
                if ( preg_match('/\s/', $file_name) ) {
                    $file_name = str_replace(' ', '_', $file_name);
                }
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/attach";
                $config['allowed_types'] = "gif|jpg|png|jpeg";
                $config['file_name']     = $new_file;
                $_FILES['attach']['tmp_name'] = $_FILES['group_photo']['tmp_name'];
                $image_info = getimagesize($_FILES['group_photo']['tmp_name']);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
                // $config['max_size']      = '5120';
                // $config['max_width']     = '3000';
                // $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("group_photo") ) {
                    $this->form_validation->set_message("photoUpload", $this->upload->display_errors());
                    return false;
                } else {
                    $fileData = $this->upload->data();
                    if($image_width > 1800 || $image_height > 1800){
                        resizeImage($fileData['file_name'],$config['upload_path']);
                     }
                    $this->upload_data['file'] = [ 'group_file_name' => $new_file ];
                    return true;
                }
            } else {
                $this->form_validation->set_message("photoUpload", "Invalid file");
                return false;
            }
        } else {
            $this->upload_data['file'] = ['group_file_name' => NULL ];
            return true;
        }
    }

    public function delete_message_get($msgID = null){

        if(is_null($msgID)){
            $this->response([
                'status' => false,
                'message' => 'Message ID empty.',
            ], REST_Controller::HTTP_NOT_FOUND);
    
        }
        
        $message = $this->conversation_m->get_single_conversation_msg(['msg_id' => $msgID]);
        if(!$message){
            $this->response([
                'status' => false,
                'message' => 'Message not found.',
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        $this->conversation_m->soft_delete_conversation_msg(['deleted' => 1],$message->msg_id);

        $this->response([
            'status' => true,
            'message' => 'Message deleted successfully.',
        ], REST_Controller::HTTP_OK);

    }

    public function edit_message_post($msgID = null){
        
        if(is_null($msgID)){
            $this->response([
                'status' => false,
                'message' => 'Message ID empty.',
            ], REST_Controller::HTTP_NOT_FOUND);
    
        }

        if ( $_POST ) {
            $rules = $this->rules(true,true);
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->response([
                    'status' => false,
                    'message' => $this->form_validation->error_array(),
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            } else {
                $message = $this->conversation_m->get_single_conversation_msg(['msg_id' => $msgID]);
                if(!$message){
                    $this->response([
                        'status' => false,
                        'message' => 'Message not found.',
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
        
                $conversationMessage = [
                    'edited' => 1,
                    'msg' => $this->input->post('message')
                ];
        
                if(!empty($_FILES) && $_FILES["attachment"]['name'] != "") {
                    $conversationMessage['attach'] = $this->upload_data['file']['attach'];
                    $conversationMessage['attach_file_name'] = $this->upload_data['file']['attach_file_name'];
                }
        
                $this->conversation_m->edit_conversation_msg($conversationMessage,$message->msg_id);
        
                $this->response([
                    'status' => true,
                    'message' => 'Message edited successfully.',
                ], REST_Controller::HTTP_OK);

            }
        }else{
            $this->response([
                'status' => false,
                'message' => 'No fields values',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }    
      

    }

    public function update_message_post($conversationID = null){
        
        if(is_null($conversationID)){
            $this->response([
                'status' => false,
                'message' => 'Conversation ID is empty.',
            ], REST_Controller::HTTP_NOT_FOUND);
    
        }

        if ( $_POST ) {
            $rules = $this->update_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->response([
                    'status' => false,
                    'message' => $this->form_validation->error_array(),
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            } else {
                $message = $this->conversation_m->get_single_conversation_msg(['conversation_id' => $conversationID,'start' => 1]);
                if(!$message){
                    $this->response([
                        'status' => false,
                        'message' => 'Message not found.',
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
        
                $conversationMessage = [
                    'group_name' => $this->input->post('group_name'),
                    'subject' => $this->input->post('subject'),
                    'enable_reply'     => $this->input->post('enable_reply') == 0?0:1,
                ];
                
                $photo = '';
                if(!empty($_FILES) && isset($_FILES["group_photo"]) && $_FILES["group_photo"]['name'] != "") {
                    $conversationMessage['group_photo'] = $photo = $this->upload_data['file']['group_file_name'];
                }
        
                $this->conversation_m->edit_conversation_msg($conversationMessage,$message->msg_id);
        
                $this->response([
                    'status' => true,
                    'message' => 'Message edited successfully.',
                    'group_photo' => $photo
                ], REST_Controller::HTTP_OK);

            }
        }else{
            $this->response([
                'status' => false,
                'message' => 'No fields values',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }    
      

    }

    public function message_readunread_get($conversationID = null)
    {
        $userTypeID     = $this->session->userdata("usertypeID");
        $userID         = $this->session->userdata("loginuserID");
        if ( (int) $conversationID ) {
            $conversationUser = $this->conversation_m->user_check($conversationID, $userID, $userTypeID);
            if($conversationUser){
                $this->conversation_m->update_conversation_user($conversationID, $userID, $userTypeID);
                $this->response([
                    'status' => true,
                    'message' => 'Message Seen',
                    'data'    => []
                ], REST_Controller::HTTP_OK);
            }else{
                $this->response([
                    'status' => false,
                    'message' => 'User not found.',
                ], REST_Controller::HTTP_OK);
            }
        }
    }

}
