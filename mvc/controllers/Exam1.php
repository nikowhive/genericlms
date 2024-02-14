<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Exam1 extends Admin_Controller
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
        $this->load->model("exam_m");
        $this->load->model("online_exam_m");
        $this->load->model("subject_m");
        $this->load->model('exam_setting_m');
        $this->load->model("alert_m");
        $this->load->model("parents_m");
        $this->load->model("online_exam_question_m");
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

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $usertypeID     = $this->session->userdata('usertypeID');
        $loginuserID    = $this->session->userdata('loginuserID');
        $schoolyearID   = $this->session->userdata('defaultschoolyearID');
        $this->data['student']   = [];
        $this->data['opsubject'] = [];
        if ($usertypeID == '3') {
            $this->data['student']   = $this->studentrelation_m->get_single_student(array('srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID));
            $this->data['opsubject'] = pluck($this->subject_m->get_order_by_subject(['type' => 0]), 'obj', 'subjectID');
        }

        $this->data['usertypeID']   = $usertypeID;
        $this->data['online_exams'] = $this->online_exam_m->getRecentOnlineExams(7, $page, array('schoolYearID' => $schoolyearID));
        $online_exams = [];
        foreach ($this->data['online_exams'] as $index => $exam) {

            $exam->date = $exam->startDateTime?$exam->startDateTime:$exam->create_date;
            $this->data['online_exams'][$index]->class = $this->classes_m->get_classes($exam->classID);
            $this->data['online_exams'][$index]->subject = $this->subject_m->get_subject($exam->subjectID);
            if ($exam->examTypeNumber == 5) {
                $format = 'd M, Y g:i A';
                $this->data['days_remaining'][] = convertDays($exam->startDateTime, $exam->endDateTime);
                $this->data['online_exams'][$index]->startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data['online_exams'][$index]->startDateTime)->format($format);
                $this->data['online_exams'][$index]->endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data['online_exams'][$index]->endDateTime)->format($format);
            } else if ($exam->examTypeNumber == 4) {
                $format = 'd M, Y';
                $this->data['days_remaining'][] = convertDays($exam->startDateTime, $exam->endDateTime);
                $this->data['online_exams'][$index]->startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data['online_exams'][$index]->startDateTime)->format($format);
                $this->data['online_exams'][$index]->endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data['online_exams'][$index]->endDateTime)->format($format);
            } else {
                $this->data['days_remaining'][] = '';
            }
            array_push($online_exams, $exam);
        }
       
        $this->data['exams']   = $this->exam_m->getRecentExams(7, $page, $schoolyearID);
        $this->data['exams'] =  array_merge($this->data['exams'], $online_exams);
        usort($this->data['exams'], 'sortDateArrayExam');

        $this->data['has_questions'] = [];
        $this->data['exam_settings'] = [];

        $_exam_settings = $this->exam_setting_m->get_exam_setting();
        foreach ($_exam_settings as $setting) {
            $this->data['exam_settings'][$setting->subject_id][] = ['id' => $setting->id, 'setting_name' => $setting->setting_name];
        }
        $_has_questions = $this->online_exam_question_m->get_online_exam_question();
        foreach ($_has_questions as $question) {
            $this->data['has_questions'][$question->onlineExamID] = true;
        }


        $this->data["subview"] = "exam1/index";
        $this->load->view('_layout_main', $this->data);
    }

    

    public function getMoreFeedData()
    {

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $usertypeID     = $this->session->userdata('usertypeID');
        $loginuserID    = $this->session->userdata('loginuserID');
        $schoolyearID   = $this->session->userdata('defaultschoolyearID');
        $this->data['student']   = [];
        $this->data['opsubject'] = [];
        if ($usertypeID == '3') {
            $this->data['student']   = $this->studentrelation_m->get_single_student(array('srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID));
            $this->data['opsubject'] = pluck($this->subject_m->get_order_by_subject(['type' => 0]), 'obj', 'subjectID');
        }

        $this->data['usertypeID']   = $usertypeID;
        $this->data['online_exams'] = $this->online_exam_m->getRecentOnlineExams(7, $page, array('schoolYearID' => $schoolyearID));
        $online_exams = [];
        foreach ($this->data['online_exams'] as $index => $exam) {
            $exam->type = "Online";
            $this->data['online_exams'][$index]->class = $this->classes_m->get_classes($exam->classID);
            $this->data['online_exams'][$index]->subject = $this->subject_m->get_subject($exam->subjectID);
            if ($exam->examTypeNumber == 5) {
                $format = 'd M, Y g:i A';
                $this->data['days_remaining'][] = convertDays($exam->startDateTime, $exam->endDateTime);
                $this->data['online_exams'][$index]->startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data['online_exams'][$index]->startDateTime)->format($format);
                $this->data['online_exams'][$index]->endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data['online_exams'][$index]->endDateTime)->format($format);
            } else if ($exam->examTypeNumber == 4) {
                $format = 'd M, Y';
                $this->data['days_remaining'][] = convertDays($exam->startDateTime, $exam->endDateTime);
                $this->data['online_exams'][$index]->startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data['online_exams'][$index]->startDateTime)->format($format);
                $this->data['online_exams'][$index]->endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data['online_exams'][$index]->endDateTime)->format($format);
            } else {
                $this->data['days_remaining'][] = '';
            }
            array_push($online_exams, $exam);
        }
        $this->data['exams']   = $this->exam_m->getRecentExams(7, $page, $schoolyearID);

        $this->data['exams'] =  array_merge($this->data['exams'], $online_exams);
        usort($this->data['exams'], 'sortDateArray');


        $this->data['has_questions'] = [];
        $this->data['exam_settings'] = [];

        $_exam_settings = $this->exam_setting_m->get_exam_setting();
        foreach ($_exam_settings as $setting) {
            $this->data['exam_settings'][$setting->subject_id][] = ['id' => $setting->id, 'setting_name' => $setting->setting_name];
        }
        $_has_questions = $this->online_exam_question_m->get_online_exam_question();
        foreach ($_has_questions as $question) {
            $this->data['has_questions'][$question->onlineExamID] = true;
        }
        if ($this->data['exams']) {
            $this->data["subview"] = "exam1/autoload_exam";
            $this->load->view('_layout_main', $this->data);
        } else {
            showBadRequest(null, "No data.");
        }
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
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['form_validation'] = validation_errors();
                if ($this->input->is_ajax_request()) {
                    $retArray['status'] = true;
                    $retArray['error'] =  validation_errors();
                    echo json_encode($retArray);
                    exit;
                }
                $this->data["subview"]         = "exam1/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                // if (config_item('demo') == false) {
                //     $updateValidation = $this->updatechecker->verifyValidUser();
                //     if ($updateValidation->status == false) {
                //         $this->session->set_flashdata('error', $updateValidation->message);
                //         redirect(base_url('exam1/add'));
                //     }
                // }

                $array["exam"] = $this->input->post("exam");
                $array["is_final_term"] = $this->input->post("is_final_term") ? 1 : 2;
                $array["date"] = date("Y-m-d", strtotime($this->input->post("date")));
                $array["note"] = $this->input->post("note");
                $array["issue_date"] = $this->input->post("issue_date");
                $array["order_no"] = $this->input->post("order_no");
                

                $this->exam_m->insert_exam($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                if ($this->input->is_ajax_request()) {
                    $retArray['status'] = true;
                    $retArray['render'] = 'success';
                    $retArray['message'] = $this->lang->line('menu_success');
                    echo json_encode($retArray);
                    exit;
                } else {
                    redirect(base_url("exam1/index"));
                }
            }
        } else {
            $this->data["subview"] = "exam1/add";
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
                'rules' => 'trim|max_length[200]|xss_clean'
            ]
        ];
        return $rules;
    }

    public function getExamByAjax(Type $var = null)
    {
        $id = $this->input->post('id');
        $this->data['exam'] = $this->exam_m->get_exam($id);
        echo json_encode($this->data['exam']);
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

        if ((int) $examID) {
            $this->data['exam'] = $this->exam_m->get_exam($examID);


            if ($this->data['exam']) {
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        if ($this->input->is_ajax_request()) {
                            $retArray['status'] = true;
                            $retArray['error'] =  validation_errors();
                            echo json_encode($retArray);
                            exit;
                        }
                        $this->data["subview"] = "exam1/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array["is_final_term"] = $this->input->post("is_final_term") ? 1 : 2;
                        $array["exam"] = $this->input->post("exam");
                        $array["date"] = date("Y-m-d", strtotime($this->input->post("date")));
                        $array["note"] = $this->input->post("note");
                        $array["issue_date"] = $this->input->post("issue_date");
                        $array["order_no"] = $this->input->post("order_no");
                       
                        $this->exam_m->update_exam($array, $examID);
                        if ($this->input->is_ajax_request()) {
                            $retArray['status'] = true;
                            $retArray['render'] = 'success';
                            $retArray['message'] = $this->lang->line('menu_success');
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("exam1/index"));
                        }
                    }
                } else {
                    $this->data["subview"] = "exam1/edit";
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

    public function delete()
    {
        $examID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$examID && !in_array($examID, $this->notdeleteArray)) {
            $this->exam_m->delete_exam($examID);
            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
            redirect(base_url("exam1/index"));
        } else {
            redirect(base_url("exam1/index"));
        }
    }

    public function unique_exam()
    {
        $examID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $examID) {
            $exam = $this->exam_m->get_order_by_exam(['examID' => $examID, 'examID !=' => $examID]);
            if (customCompute($exam)) {
                $this->form_validation->set_message("unique_exam", "The %s already exists");
                return false;
            }
        } else {
            $exam = $this->exam_m->get_order_by_exam(['examID' => $examID]);
            if (customCompute($exam)) {
                $this->form_validation->set_message("unique_exam", "The %s already exists");
                return false;
            }
        }
        return true;
    }

    public function date_valid($date)
    {
        if (strlen($date) < 10) {
            $this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy");
            return false;
        } else {
            $arr  = explode("-", $date);
            $dd   = $arr[0];
            $mm   = $arr[1];
            $yyyy = $arr[2];
            if (checkdate($mm, $dd, $yyyy)) {
                return true;
            } else {
                $this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy");
                return false;
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
        }
        return true;
    }

    public function postChangeExamStatus($id)
    {

        $published = $this->exam_m->get($id);
        
        $array = [
            'published' => $published->published == 1 ? 2 : 1,
        ];

        if ($this->exam_m->update_exam($array, $id) && $published->published == 2) {
            $record = $this->exam_m->get_single_exam(['examID' => $id]);
            $title = 'The exam result ' . $record->exam . ' has been published';
            $notice = "Terminal exam " . $record->exam . " has been published";
            $this->notification($title, $notice);
        }
       
    }

    public function notification($title, $notice, $class = null)
    {
        $users = pluck($this->student_m->general_get_order_by_student(), 'obj', 'studentID');
        $parents = pluck($this->parents_m->get_order_by_parents(), 'obj', 'parentsID');
        $user_ids = [];
        foreach ($users as $key => $value) {
            array_push($user_ids, $key . '3');
        }
        foreach ($parents as $key => $value) {
            array_push($user_ids, $key . '4');
        }
        $array = array(
            "title" => $title,
            "users" => serialize($user_ids),
            "notice" => $notice,
            "schoolyearID" => $this->session->userdata('defaultschoolyearID'),
            "date" => date('Y-m-d'),
            "create_date" => date('Y-m-d H:i:s'),
            "create_userID" => $this->session->userdata('loginuserID'),
            "create_usertypeID" => $this->session->userdata('usertypeID')
        );
        $this->notice_m->insert_notice($array);

        $noticeID = $this->db->insert_id();
        if (!empty($noticeID)) {
            $this->pushNotification($array);
            $this->alert_m->insert_alert(array('itemID' => $noticeID, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'notice'));
        }
    }

    function pushNotification($array)
    {
        $this->job_m->insert_job([
            'name' => 'sendNotice',
            'payload' => json_encode([
                'title' => $array['title'],  // title is necessary
                'users' => $array['users'],
            ]),
        ]);
    }


}
