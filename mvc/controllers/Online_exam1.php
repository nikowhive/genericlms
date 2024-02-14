<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Online_exam1 extends Admin_Controller
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
        $this->load->model("online_exam_m");
        $this->load->model("classes_m");
        $this->load->model("section_m");
        $this->load->model("subject_m");
        $this->load->model("studentgroup_m");
        $this->load->model("usertype_m");
        $this->load->model("exam_type_m");
        $this->load->model("question_bank_m");
        $this->load->model("question_level_m");
        $this->load->model("question_group_m");
        $this->load->model("question_type_m");
        $this->load->model("question_option_m");
        $this->load->model("question_answer_m");
        $this->load->model("online_exam_question_m");
        $this->load->model("student_m");
        $this->load->model('chapter_m');
        $this->load->model('exam_setting_m');
        $this->load->model("instruction_m");
        $this->load->model("studentrelation_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('online_exam', $language);
    }

    public function index()
    {
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
        $this->data['online_exams'] = $this->online_exam_m->get_order_by_online_exam(array('schoolYearID' => $schoolyearID));
        foreach ($this->data['online_exams'] as $index => $exam) {
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
        }

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

        $this->data["subview"] = "online_exam/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function postChangeExamStatus($id)
    {
        $published = $this->online_exam_m->get($id);

        $array = [
            'published' => $published->published == 1 ? 2 : 1,
        ];


        $this->online_exam_m->update_online_exam($array, $id);
        echo 1;
    }

    public function generateRandomQuestions()
    {
        if ($_POST) {
            $data = $this->input->post();
            $setting = $this->exam_setting_m->get_exam_setting($data['exam_setting_id']);
            $details = json_decode($setting->details);
            $parameters = [];
            foreach ($details as $d) {
                $parameters[] = [
                    //'subject_id'    =>  $setting->subject_id,
                    'unit'  =>  $d->unit,
                    'question_group' => $d->question_group,
                    'level' =>  $d->level,
                    'question_type' =>  $d->question_type,
                    'mark'  =>  $d->mark,
                    'no_of_questions'   =>  $d->no_of_questions
                ];
            }

            $question_ids = $this->getQuestions($parameters, $data['exam_id']);
            $count = count($question_ids);

            if ($count == 0) {
                $this->session->set_flashdata('error', 'Questions of matching criteria could not be found');
            }

            $this->db->trans_begin();
            foreach ($question_ids as $question_id) {
                $this->apiAddQuestion($data['exam_id'], $question_id);
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('error', 'Could not generate question. Something went wrong');
                // redirect(base_url("online_exam/index"));
            } else {
                $this->db->trans_commit();
                //$this->session->set_flashdata('success', $count.' questions added');
                if ($count > 0) {
                    $this->session->set_flashdata('success', $count . ' questions added');
                    redirect(base_url("online_exam/addquestion/" . $data['exam_id']));
                }
            }
            redirect(base_url("online_exam/index"));
        } else {
            $this->session->set_flashdata('error', 'You cannot access this url');
            redirect(base_url("online_exam/index"));
        }
    }

    public function generateAllQuestions()
    {
        $online_exam_id = $_GET['online_exam'];

        $where = [];
        if ($_GET['subject'] != 0) {
            $chapter_ids = $this->chapter_m->get_ids_from_subject_id($_GET['subject']);

            if (!empty($chapter_ids)) {
                $where['chapter_ids'] = $chapter_ids;
            }
        }
        $questions = $this->question_bank_m->get_question_bank_from_chapter_ids($where);

        $question_ids = [];
        foreach ($questions as $question) {
            array_push($question_ids, $question->questionBankID);
        }
        $count = count($question_ids);

        if ($count == 0) {
            $this->session->set_flashdata('error', 'Questions of matching criteria could not be found or Already all questions has been added');
        }
        $this->db->trans_begin();
        foreach ($question_ids as $question_id) {
            $this->apiAddQuestion($online_exam_id, $question_id);
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Could not generate question. Something went wrong');
        } else {
            $this->db->trans_commit();
            if ($count > 0) {
                $this->session->set_flashdata('success', $count . ' questions added');
            }
        }
        redirect(base_url("online_exam/addquestion/" . $online_exam_id));
    }

    private function getQuestions($parameters, $onlineExamID)
    {
        $return = [];
        $existing_questions = $this->online_exam_question_m->get_question_ids_from_exam_ids($onlineExamID);

        $question_table = $this->question_bank_m->getTable();
        $chapter_table = $this->chapter_m->getTable();

        foreach ($parameters as $parameter) {
            $this->db->select($question_table . '.*');
            $this->db->from($question_table);
            $this->db->join($chapter_table, $chapter_table . '.id = ' . $question_table . '.chapter_id', 'LEFT');

            $this->db->where($chapter_table . '.unit_id', $parameter['unit']);
            $this->db->where($question_table . '.levelID', $parameter['level']);
            $this->db->where($question_table . '.type_id', $parameter['question_type']);
            $this->db->where($question_table . '.groupID', $parameter['question_group']);
            $this->db->where($question_table . '.mark', $parameter['mark']);
            if ($existing_questions) {
                $this->db->where_not_in('questionBankID', $existing_questions);
            }

            $this->db->order_by($question_table . '.id', 'RANDOM');
            $this->db->limit($parameter['no_of_questions']);
            $query = $this->db->get();

            $result = $query->result();
            foreach ($result as $r) {
                $return[] = $r->questionBankID;
            }
        }

        //$return = [102, 101];

        return $return;
    }

    private function apiAddQuestion($onlineExamID, $questionID)
    {
        $haveExamQuestion = $this->online_exam_question_m->get_order_by_online_exam_question([
            'onlineExamID' => $onlineExamID,
            'questionID' => $questionID
        ]);

        if (!customCompute($haveExamQuestion)) {
            $this->online_exam_question_m->insert_online_exam_question([
                'onlineExamID' => $onlineExamID,
                'questionID' => $questionID
            ]);
        }
    }

    protected function rules($type = 2)
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("online_exam_name"),
                'rules' => 'trim|required|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("online_exam_description"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'classes',
                'label' => $this->lang->line("online_exam_class"),
                'rules' => 'trim|xss_clean|required|numeric'
            ),
            array(
                'field' => 'section',
                'label' => $this->lang->line("online_exam_section"),
                'rules' => 'trim|xss_clean|required|numeric|callback_unique_section'
            ),
            array(
                'field' => 'studentGroup',
                'label' => $this->lang->line("online_exam_studentGroup"),
                'rules' => 'trim|xss_clean|required|numeric'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("online_exam_subject"),
                'rules' => 'trim|xss_clean|numeric'
            ),
            array(
                'field' => 'instruction',
                'label' => $this->lang->line("online_exam_instruction"),
                'rules' => 'trim|xss_clean|required|numeric'
            ),
            array(
                'field' => 'examStatus',
                'label' => $this->lang->line("online_exam_examStatus"),
                'rules' => 'trim|xss_clean|required|numeric|callback_unique_data'
            ),
            array(
                'field' => 'type',
                'label' => $this->lang->line("online_exam_type"),
                'rules' => 'trim|xss_clean|required|numeric|callback_unique_type'
            ),
            array(
                'field' => 'duration',
                'label' => $this->lang->line("online_exam_duration"),
                'rules' => 'trim|xss_clean|numeric|greater_than[0]'
            ),
            array(
                'field' => 'markType',
                'label' => $this->lang->line("online_exam_markType"),
                'rules' => 'trim|required|xss_clean|numeric|callback_unique_markType'
            ),
            array(
                'field' => 'negativeMark',
                'label' => $this->lang->line("online_exam_negativeMark"),
                'rules' => 'trim|xss_clean|numeric'
            ),
            array(
                'field' => 'percentage',
                'label' => $this->lang->line("online_exam_passValue"),
                'rules' => 'trim|required|xss_clean|numeric|greater_than[0]'
            ),
            array(
                'field' => 'random',
                'label' => $this->lang->line("online_exam_random"),
                'rules' => 'trim|xss_clean|numeric'
            ),
            array(
                'field' => 'published',
                'label' => $this->lang->line("online_exam_published"),
                'rules' => 'trim|xss_clean|required|numeric|callback_unique_data|callback_check_exam_question'
            )
        );

        if ($type == 4) {
            $rules[] = array(
                'field' => 'startdate',
                'label' => $this->lang->line("online_exam_startdatetime"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_date'
            );
            $rules[] = array(
                'field' => 'enddate',
                'label' => $this->lang->line("online_exam_enddatetime"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_date'
            );
        } elseif ($type == 5) {
            $rules[] = array(
                'field' => 'startdatetime',
                'label' => $this->lang->line("online_exam_startdatetime"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_date_time'
            );
            $rules[] = array(
                'field' => 'enddatetime',
                'label' => $this->lang->line("online_exam_enddatetime"),
                'rules' => 'trim|required|xss_clean|max_length[128]|callback_unique_date_time'
            );
        }
        return $rules;
    }

    public function unique_data($data)
    {
        if ($data != "") {
            if ($data === "0") {
                $this->form_validation->set_message('unique_data', 'The %s field is required.');
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }

    public function unique_date()
    {
        $startdate = $this->input->post('startdate');
        $enddate   = $this->input->post('enddate');
        if ($startdate != '' && $enddate != '') {
            if (strtotime($startdate) > strtotime($enddate)) {
                $this->form_validation->set_message("unique_date", "The start date can not be upper than enddate.");
                return FALSE;
            }
        }
        return TRUE;
    }

    public function unique_date_time()
    {
        $startdatetime = $this->input->post('startdatetime');
        $enddatetime   = $this->input->post('enddatetime');
        if ($startdatetime != '' && $enddatetime != '') {
            if (strtotime($startdatetime) > strtotime($enddatetime)) {
                $this->form_validation->set_message("unique_date_time", "The start date time can not be upper than end date time.");
                return FALSE;
            }
        }
        return TRUE;
    }

    public function add()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/datetimepicker/datetimepicker.css',
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css'
                ),
                'js' => array(
                    'assets/datetimepicker/moment.js',
                    'assets/datetimepicker/datetimepicker.js',
                    'assets/select2/select2.js'
                )
            );

            $usertypeID = $this->session->userdata('usertypeID');
            $loginuserID = $this->session->userdata('loginuserID');
            $schoolYearID = $this->session->userdata('defaultschoolyearID');

            $this->data['classes'] = $this->classes_m->general_get_classes();
            $this->data['usertypes'] = $this->usertype_m->get_usertype();
            $this->data['instructions'] = $this->instruction_m->get_order_by_instruction();
            $this->data['types'] = $this->exam_type_m->get_order_by_exam_type(['status' => 1]);
            $this->data['groups'] = $this->studentgroup_m->get_order_by_studentgroup();
            $this->data['userTypeID'] = 3;
            $this->data['sections'] = [];
            $this->data['subjects'] = [];

            if ($_POST) {
                $this->data['sections'] = $this->section_m->general_get_order_by_section(array('classesID' => $this->input->post('classes')));
                $this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array('classesID' => $this->input->post('classes')));
                $this->data['posttype'] = $this->input->post('type');
                $rules = $this->rules($this->data['posttype']);
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "online_exam/add";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $inputs = $this->input->post();
                    $databasePair = [
                        'name' => 'name',
                        'description' => 'description',
                        'usertype' => 'userTypeID',
                        'classes' => 'classID',
                        'section' => 'sectionID',
                        'studentGroup' => 'studentGroupID',
                        'subject' => 'subjectID',
                        'instruction' => 'instructionID',
                        'duration' => 'duration',
                        'type' => 'examTypeNumber',
                        'random' => 'random',
                        'markType' => 'markType',
                        'negativeMark' => 'negativeMark',
                        'percentage' => 'percentage',
                        'ispaid' => 'paid',
                        'validDays' => 'validDays',
                        'cost' => 'cost',
                        'judge' => 'judge'
                    ];

                    if ($inputs['type'] == 4) {
                        $databasePair['startdate'] = 'startDateTime';
                        $databasePair['enddate'] = 'endDateTime';
                    } elseif ($inputs['type'] == 5) {
                        $databasePair['startdatetime'] = 'startDateTime';
                        $databasePair['enddatetime'] = 'endDateTime';
                    }

                    $array = [];
                    foreach ($databasePair as $key => $database) {
                        if ($inputs[$key] != "") {
                            if ($database == 'startDateTime' || $database == 'endDateTime') {
                                $array[$database] = date('Y-m-d H:i:s', strtotime($inputs[$key]));
                            } else {
                                $array[$database] = $inputs[$key];
                            }
                        }
                    }
                    $array['examStatus'] = $this->input->post('examStatus');
                    $array['published']   = $this->input->post('published');
                    $array['create_date'] = date("Y-m-d H:i:s");
                    $array['modify_date'] = date("Y-m-d H:i:s");
                    $array['create_userID'] = $loginuserID;
                    $array['create_usertypeID'] = $usertypeID;
                    $array['schoolYearID'] = $schoolYearID;
                    $this->online_exam_m->insert_online_exam($array);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("online_exam/index"));
                }
            } else {
                $this->data['posttype'] = $this->input->post('type');
                $this->data["subview"] = "online_exam/add";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/datetimepicker/datetimepicker.css',
                    'assets/editor/jquery-te-1.4.0.css',
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css'
                ),
                'js' => array(
                    'assets/editor/jquery-te-1.4.0.min.js',
                    'assets/datetimepicker/moment.js',
                    'assets/datetimepicker/datetimepicker.js',
                    'assets/select2/select2.js'
                )
            );
            $id = htmlentities(escapeString($this->uri->segment(3)));
            $schoolYearID = $this->session->userdata('defaultschoolyearID');
            if ((int)$id) {
                $this->data['online_exam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $id, 'schoolYearID' => $schoolYearID));
                if ($this->data['online_exam']) {
                    $this->data['classes'] = $this->classes_m->general_get_classes();
                    $this->data['usertypes'] = $this->usertype_m->get_usertype();
                    $this->data['instructions'] = $this->instruction_m->get_order_by_instruction();
                    $this->data['types'] = $this->exam_type_m->get_order_by_exam_type(['status' => 1]);
                    $this->data['groups'] = $this->studentgroup_m->get_order_by_studentgroup();
                    $this->data['sections'] = [];
                    $this->data['subjects'] = [];
                    if (isset($this->data['online_exam']->classID)) {
                        $this->data['sections'] = $this->section_m->general_get_order_by_section(array('classesID' => $this->data['online_exam']->classID));
                        $this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array('classesID' => $this->data['online_exam']->classID));
                    }
                    $this->data['userTypeID'] = 3;
                    if ($_POST) {
                        $this->data['sections'] = $this->section_m->general_get_order_by_section(array('classesID' => $this->input->post('classes')));
                        $this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array('classesID' => $this->input->post('classes')));
                        $this->data['posttype'] = $this->input->post('type');
                        $rules = $this->rules($this->data['posttype']);
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                            $this->data["subview"] = "/online_exam/edit";
                            $this->load->view('_layout_main', $this->data);
                        } else {
                            $inputs = $this->input->post();
                            $databasePair = [
                                'name' => 'name',
                                'description' => 'description',
                                'usertype' => 'userTypeID',
                                'classes' => 'classID',
                                'section' => 'sectionID',
                                'studentGroup' => 'studentGroupID',
                                'subject' => 'subjectID',
                                'instruction' => 'instructionID',
                                'duration' => 'duration',
                                'type' => 'examTypeNumber',
                                'random' => 'random',
                                'markType' => 'markType',
                                'negativeMark' => 'negativeMark',
                                'percentage' => 'percentage',
                                'ispaid' => 'paid',
                                'validDays' => 'validDays',
                                'cost' => 'cost',
                                'judge' => 'judge'
                            ];
                            if ($inputs['type'] == 4) {
                                $databasePair['startdate'] = 'startDateTime';
                                $databasePair['enddate'] = 'endDateTime';
                            } elseif ($inputs['type'] == 5) {
                                $databasePair['startdatetime'] = 'startDateTime';
                                $databasePair['enddatetime'] = 'endDateTime';
                            }

                            $array = [];
                            $f = 1;
                            foreach ($databasePair as $key => $database) {
                                if ($inputs[$key] != "") {
                                    if ($database == 'startDateTime' || $database == 'endDateTime') {
                                        $f = 0;
                                        $array[$database] = date('Y-m-d H:i:s', strtotime($inputs[$key]));
                                    } else {
                                        $array[$database] = $inputs[$key];
                                    }
                                }
                            }
                            if ($f) {
                                $array['startDateTime'] = NULL;
                                $array['endDateTime'] = NULL;
                            }
                            $array['modify_date'] = date("Y-m-d H:i:s");
                            $array['examStatus']  = $this->input->post('examStatus');
                            $array['published']   = $this->input->post('published');

                            $this->online_exam_m->update_online_exam($array, $id);
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("online_exam/index"));
                        }
                    } else {
                        $this->data['posttype'] = $this->data['online_exam']->examTypeNumber;
                        $this->data["subview"]  = "online_exam/edit";
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

    public function delete()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if ((int)$id) {
                $schoolYearID = $this->session->userdata('defaultschoolyearID');
                $this->data['online_exam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $id, 'schoolYearID' => $schoolYearID));
                if (customCompute($this->data['online_exam'])) {
                    $this->online_exam_m->delete_online_exam($id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("online_exam/index"));
                } else {
                    redirect(base_url("online_exam/index"));
                }
            } else {
                redirect(base_url("online_exam/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function addquestion()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/checkbox/checkbox.css',
            )
        );

        if (permissionChecker('online_exam_add')) {
            $onlineExamID = htmlentities(escapeString($this->uri->segment(3)));
            if ((int)$onlineExamID) {
                $onlineExam = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $onlineExamID]);
                if (customCompute($onlineExam)) {
                    $this->data['onlineExamID'] = $onlineExamID;
                    $this->addQuestionDatabase(true, $onlineExamID);
                    $this->data['levels'] = $this->question_level_m->get_order_by_question_level();
                    $this->data['groups'] = $this->question_group_m->get_order_by_question_group();
                    if (!is_null($onlineExam)) {
                        $this->data['class'] = $this->classes_m->general_get_classes($onlineExam->classID);
                        $this->data['section'] = $this->section_m->general_get_section($onlineExam->sectionID);
                        $this->data['studentGroup'] = $this->studentgroup_m->get_studentgroup($onlineExam->studentGroupID);
                        $this->data['instruction'] = $this->instruction_m->get_instruction($onlineExam->instructionID);
                        $this->data['examType'] = $this->exam_type_m->get_single_exam_type(['examTypeNumber' => $onlineExam->examTypeNumber]);
                        $this->data['subject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $onlineExam->subjectID));
                    }
                    $this->data['onlineExam'] = $onlineExam;
                    $this->data["subview"] = "/online_exam/addquestion";
                    $this->load->view('_layout_main', $this->data);
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

    public function showQuestions()
    {
        $inputs = $this->input->post();
        $where = [];
        if ($inputs['levelID']) {
            $where['levelID'] = $inputs['levelID'];
        }
        if ($inputs['groupID']) {
            $where['groupID'] = $inputs['groupID'];
        }
        if ($inputs['subjectID'] != 0) {
            $where['chapter_ids'] = $this->chapter_m->get_ids_from_subject_id($inputs['subjectID']);
        } else if ($inputs['classID'] != 0) {
            $subject_ids = $this->subject_m->get_subject_ids_from_class_id($inputs['classID']);
            $where['chapter_ids'] = $this->chapter_m->get_ids_from_subject_ids($subject_ids);
        }
        $this->data['questions'] = $this->question_bank_m->get_question_bank_from_chapter_ids($where);
        $this->data['types'] = pluck($this->question_type_m->get_order_by_question_type(), 'obj', 'questionTypeID');

        echo $this->load->view('/online_exam/questionList', $this->data, true);
    }

    public function addQuestionDatabase($initial = false, $onlineExamID = 0, $questionID = 0)
    {
        if (!$initial) {
            $onlineExamID = $this->input->post('onlineExamID');
            $questionID = $this->input->post('questionID');
            $haveExamQuestion = $this->online_exam_question_m->get_order_by_online_exam_question([
                'onlineExamID' => $onlineExamID,
                'questionID' => $questionID
            ]);
            if (!customCompute($haveExamQuestion)) {
                $this->online_exam_question_m->insert_online_exam_question([
                    'onlineExamID' => $onlineExamID,
                    'questionID' => $questionID
                ]);
            }
        }

        $this->data['onlineExamQuestions'] = $this->online_exam_question_m->get_order_by_online_exam_question([
            'onlineExamID' => $onlineExamID
        ]);
        $this->data['questions'] = pluck($this->question_bank_m->get_order_by_question_bank(), 'obj', 'questionBankID');
        $allOptions = $this->question_option_m->get_order_by_question_option();
        $options = [];
        foreach ($allOptions as $option) {
            if ($option->name == "" && $option->img == "") continue;
            $options[$option->questionID][] = $option;
        }
        $this->data['options'] = $options;
        $allAnswers = $this->question_answer_m->get_order_by_question_answer();
        $answers = [];
        foreach ($allAnswers as $answer) {
            $answers[$answer->questionID][] = $answer;
        }
        $this->data['answers'] = $answers;
        $showArray['associateQuestionList'] = $this->load->view('online_exam/associateQuestionList', $this->data, true);
        $showArray['questionSummary'] = $this->load->view('online_exam/questionSummary', $this->data, true);
        $this->data['associateQuestionList'] = $showArray['associateQuestionList'];
        $this->data['questionSummary'] = $showArray['questionSummary'];
        $this->data['updateView'] = $showArray;
        if (!$initial) {
            echo json_encode($showArray);
        }
    }

    public function removeQuestionDatabase()
    {
        $onlineExamQuestionID = $this->input->post('onlineExamQuestionID');
        $onlineExamID = $this->input->post('onlineExamID');
        $this->online_exam_question_m->delete_online_exam_question($onlineExamQuestionID);
        $this->addQuestionDatabase(true, $onlineExamID);
        echo json_encode($this->data['updateView']);
    }

    public function getSection()
    {
        $id = $this->input->post('id');
        if ((int)$id) {
            $allSection = $this->section_m->general_get_order_by_section(array('classesID' => $id));
            echo "<option value='0'>", $this->lang->line("online_exam_select"), "</option>";
            foreach ($allSection as $value) {
                echo "<option value=\"$value->sectionID\">", $value->section, "</option>";
            }
        }
    }

    public function getSections()
    {
        $id = $this->input->post('id');
        if ((int)$id) {
            $allSection = $this->section_m->get_order_by_section(array('classesID' => $id));
            foreach ($allSection as $value) {
                echo "Section <button type=\"button\" class=\"btn btn-default btn-lg\">
                        <span class=\"glyphicon glyphicon-star\" aria-hidden=\"true\"></span>" . $value->section . "
                    </button>";
            }
        }
    }

    public function getSubject()
    {
        $classID = $this->input->post('classID');
        if ((int)$classID) {
            $allSubject = $this->subject_m->general_get_order_by_subject(array('classesID' => $classID));
            echo "<option value=''>", $this->lang->line("online_exam_select"), "</option>";
            foreach ($allSubject as $value) {
                echo "<option value=\"$value->subjectID\">", $value->subject, "</option>";
            }
        }
    }

    public function unique_type()
    {
        if ($this->input->post('type') == 0) {
            $this->form_validation->set_message("unique_type", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_markType()
    {
        if ($this->input->post('markType') == 0) {
            $this->form_validation->set_message("unique_markType", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_section()
    {
        if ($this->input->post('classes')) {
            if ($this->input->post('section') == '') {
                $this->form_validation->set_message("unique_section", "The %s field is required");
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }

    public function check_exam_question()
    {
        $onlineexamID = htmlentities(escapeString($this->uri->segment(3)));
        $published = $this->input->post('published');
        if ((int)$onlineexamID) {
            $online_exam_questions = $this->online_exam_question_m->get_order_by_online_exam_question(array('onlineExamID' => $onlineexamID));
            if ((customCompute($online_exam_questions) == 0) && ($published == 1)) {
                $this->form_validation->set_message("check_exam_question", "Please add some question and publish this exam.");
                return FALSE;
            }
            return TRUE;
        } else {
            if ($published == 1) {
                $this->form_validation->set_message("check_exam_question", "Please add some question and publish this exam.");
                return FALSE;
            }
            return TRUE;
        }
    }

    // Todo: Need to refine code
    public function publishresult($id)
    {
        if ($id > 0) {
            $this->db->where('onlineExamID', $id)->update('online_exam', ['result_published' => true]);
            $this->db->where('onlineExamID', $id)->update('online_exam_user_status', ['status' => 1]);
            $this->session->set_flashdata('success', 'Result successfully published');
        }
        redirect(base_url("exam/index"));
    }

    public function togglepublish($id)
    {
        if ($id > 0) {
            $this->db->from('online_exam');
            $online_exam = $this->db->where('onlineExamID', $id);
            if ($online_exam->get()->row()->auto_published == true) {
                $this->db->where('onlineExamID', $id)->update('online_exam', ['auto_published' => false]);
            } else {
                $this->db->where('onlineExamID', $id)->update('online_exam', ['auto_published' => true]);
            }
            $this->session->set_flashdata('success', 'Changed Auto Publish');
        }

        redirect(base_url("exam/index"));
    }
}
