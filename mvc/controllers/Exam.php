<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

    class Exam extends Admin_Controller
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
        public $notdeleteArray = [1];

        public function __construct()
        {
            parent::__construct();
            $this->load->model("job_m");
            $this->load->model("feed_m");
            $this->load->model("mobile_job_m");
            $this->load->model("exam_m");
            $this->load->model("alert_m");
            $this->load->model("parents_m");
            $this->load->model("notice_m");
            $this->load->model("student_m");
            $this->load->library('updatechecker');
            $this->db->cache_off();
            $this->data['notdeleteArray'] = $this->notdeleteArray;
            $language = $this->session->userdata('lang');
            $this->lang->load('exam', $language);
        }

        public function index()
        {
            $this->data['exams']   = $this->exam_m->get_order_by_exam();
            $this->data["subview"] = "exam/index";
            $this->load->view('_layout_main', $this->data);
        }

        public function add()
        {
            $this->data['headerassets'] = [
                'css' => [
                    'assets/datepicker/datepicker.css',
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css'
                ],
                'js'  => [
                    'assets/datepicker/datepicker.js',
                    'assets/select2/select2.js'
                ]
            ];
            if ( $_POST ) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $this->data['form_validation'] = validation_errors();
                    $this->data["subview"]         = "exam/add";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    // if ( config_item('demo') == false ) {
                    //     $updateValidation = $this->updatechecker->verifyValidUser();
                    //     if ( $updateValidation->status == false ) {
                    //         $this->session->set_flashdata('error', $updateValidation->message);
                    //         redirect(base_url('exam/add'));
                    //     }
                    // }

                    $array["exam"] = $this->input->post("exam");
                    $array["is_final_term"] = $this->input->post("is_final_term")?1:2;
                    $array["date"] = date("Y-m-d", strtotime($this->input->post("date")));
                    $array["note"] = $this->input->post("note");
                    $array["issue_date"] = $this->input->post("issue_date");
                    $array["order_no"] = $this->input->post("order_no");

                    $this->exam_m->insert_exam($array);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("exam/index"));
                }
            } else {
                $this->data["subview"] = "exam/add";
                $this->load->view('_layout_main', $this->data);
            }
        }

        protected function rules()
        {
            $rules = [
                [
                    'field' => 'exam',
                    'label' => $this->lang->line("exam_name"),
                    'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_exam'
                ],
                [
                    'field' => 'date',
                    'label' => $this->lang->line("exam_date"),
                    'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid'
                ],
                [
                    'field' => 'note',
                    'label' => $this->lang->line("exam_note"),
                    'rules' => 'trim|max_length[200]|xss_clean'
                ],
                [
                    'field' => 'issue_date',
                    'label' => $this->lang->line("exam_issue_date"),
                    'rules' => 'trim|required|max_length[200]|xss_clean'
                ],
                [
                    'field' => 'order_no',
                    'label' => $this->lang->line("exam_order_number"),
                    'rules' => 'trim|required|max_length[3]|xss_clean'
                ]
            ];
            return $rules;
        }

        public function edit()
        {
            $this->data['headerassets'] = [
                'css' => [
                    'assets/datepicker/datepicker.css',
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css'
                ],
                'js'  => [
                    'assets/datepicker/datepicker.js',
                    'assets/select2/select2.js'
                ]
            ];

            $examID = htmlentities(escapeString($this->uri->segment(3)));
            if ( (int) $examID ) {
                $this->data['exam'] = $this->exam_m->get_exam($examID);
                if ( $this->data['exam'] ) {
                    if ( $_POST ) {
                        $rules = $this->rules();
                        $this->form_validation->set_rules($rules);
                        if ( $this->form_validation->run() == false ) {
                            $this->data["subview"] = "exam/edit";
                            $this->load->view('_layout_main', $this->data);
                        } else {
                            $array["is_final_term"] = $this->input->post("is_final_term")?1:2;
                            $array["exam"] = $this->input->post("exam");
                            $array["date"] = date("Y-m-d", strtotime($this->input->post("date")));
                            $array["note"] = $this->input->post("note");
                            $array["issue_date"] = $this->input->post("issue_date");
                            $array["order_no"] = $this->input->post("order_no");

                            $this->exam_m->update_exam($array, $examID);
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("exam/index"));
                        }
                    } else {
                        $this->data["subview"] = "exam/edit";
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

	public function delete() {
		$examID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$examID && !in_array($examID, $this->notdeleteArray)) {
			$this->exam_m->delete_exam($examID);
			$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			redirect(base_url("exam/index"));
		} else {
			redirect(base_url("exam/index"));
		}
	}

        public function unique_exam()
        {
            $examID = htmlentities(escapeString($this->uri->segment(3)));
            if ( (int) $examID ) {
                $exam = $this->exam_m->get_order_by_exam([ 'examID' => $examID, 'examID !=' => $examID ]);
                if ( customCompute($exam) ) {
                    $this->form_validation->set_message("unique_exam", "The %s already exists");
                    return false;
                }
            } else {
                $exam = $this->exam_m->get_order_by_exam([ 'examID' => $examID ]);
                if ( customCompute($exam) ) {
                    $this->form_validation->set_message("unique_exam", "The %s already exists");
                    return false;
                }
            }
            return true;
        }

        public function date_valid( $date )
        {
            if ( strlen($date) < 10 ) {
                $this->form_validation->set_message("date_valid", "The %s field is required");
                return false;
            } else {
                $arr  = explode("-", $date);
                $dd   = $arr[0];
                $mm   = $arr[1];
                $yyyy = $arr[2];
                if ( checkdate($mm, $dd, $yyyy) ) {
                    return true;
                } else {
                    $this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy");
                    return false;
                }
            }

        }

        public function unique_data( $data )
        {
            if ( $data != '' ) {
                if ( $data == '0' ) {
                    $this->form_validation->set_message('unique_data', 'The %s field is required.');
                    return false;
                }
            }
            return true;
        }

        public function postChangeExamStatus($id) {
            $published = $this->input->post('published');
            $published = is_null($published) ? 1 : $published;
            $array = [
                'published' => $published
            ];
    
            $this->exam_m->update_exam($array, $id);
            if($this->exam_m->update_exam($array, $id) && $published ==1){
                $record = $this->exam_m->get_single_exam(['examID' => $id]);
                $title = 'The exam result '.$record->exam.' has been published';
                $notice = "Terminal exam ".$record->exam." has been published";
                $this->notification($title,$notice); 
            } 
            $this->session->set_flashdata('success', 'status successfully changed');    
            redirect(base_url("exam/index"));
        }

        public function notification($title, $notice, $class = null){
            $users = pluck($this->student_m->general_get_order_by_student(), 'obj', 'studentID');
            $parents = pluck($this->parents_m->get_order_by_parents(), 'obj', 'parentsID');
          
            $array = array(
                "title" => $title,
                "users" => '',
                "notice" => $notice,
                "schoolyearID" => $this->session->userdata('defaultschoolyearID'),
                "date" => date('Y-m-d'),
                "create_date" => date('Y-m-d H:i:s'),
                "create_userID" => $this->session->userdata('loginuserID'),
                "create_usertypeID" => $this->session->userdata('usertypeID')
            );
            $this->notice_m->insert_notice($array);
            $noticeID = $this->db->insert_id();

            if($noticeID){
            // insert feed
            $this->feed_m->insert_feed(
                array(
                    'itemID'         => $noticeID,
                    'userID'         => $this->session->userdata("loginuserID"),
                    'usertypeID'     => $this->session->userdata('usertypeID'),
                    'itemname'       => 'notice',
                    'schoolyearID'   => $this->session->userdata('defaultschoolyearID'),
                    'published'      => 1,
                    'published_date' => date("Y-m-d"),
                )
            );
		    $feedID = $this->db->insert_id();

           

                $user_ids = [];
                $noticeUsers = [];
                foreach($users as $key => $value) {
                    $noticeUsers[] = [
                        'notice_id'  => $noticeID,
                        'user_id'    => $key,
                        'usertypeID' => 3
                  ];
                    $feedUsers[] = [
                        'feed_id'    => $feedID,
                        'user_id'    => $key,
                        'usertypeID' => 3
                    ];
                  array_push($user_ids, $key . '3');
                }

                foreach($parents as $key => $value) {
                    $noticeUsers[] = [
                        'notice_id'  => $noticeID,
                        'user_id'    => $key,
                        'usertypeID' => 4
                    ];
                    $feedUsers[] = [
                        'feed_id'    => $feedID,
                        'user_id'    => $key,
                        'usertypeID' => 4
                    ];
                    array_push($user_ids, $key . '4');
                }
                
                $this->notice_m->insert_batch_notice_user($noticeUsers);
                $this->feed_m->insert_batch_feed_user($feedUsers);		
                
            }

            if(!empty($noticeID)) {
                $this->pushNotification($array,$user_ids);
                $this->alert_m->insert_alert(array('itemID' => $noticeID, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'notice'));
            }
        }

        function pushNotification($array,$user_ids) {
            $this->job_m->insert_job([
                'name' => 'sendNotice',
                'payload' => json_encode([
                    'title' => $array['title'],  // title is necessary
                    'users' => serialize($user_ids),
                ]),
            ]);
            $this->mobile_job_m->insert_job([
                'name' => 'sendNotice',
                'payload' => json_encode([
                    'title'  => $array['title'],  // title is necessary
                    'users'  => serialize($user_ids),
                    'message' => $array['notice'],
                ]),
            ]);
        }    
    }