<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class homework extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("job_m");
        $this->load->model("feed_m");
        $this->load->model("mobile_job_m");
        $this->load->model('section_m');
        $this->load->model('classes_m');
        $this->load->model('homework_m');
        $this->load->model('teacher_m');
        $this->load->model("subject_m");
        $this->load->model("student_m");
        $this->load->model("parents_m");
        $this->load->model("courses_m");
        $this->load->model("unit_m");
        $this->load->model("chapter_m");
        $this->load->model("notice_m");
        $this->load->model("studentrelation_m");
        $this->load->model('homework_media_m');
        $this->load->model('subjectteacher_m');
        $this->load->model('homeworkanswer_m');
        $this->load->model("homework_answer_media_m");
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("homework_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]',
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("homework_description"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'classesID',
                'label' => $this->lang->line("homework_classes"),
                'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_classes',
            ),
            array(
                'field' => 'deadlinedate',
                'label' => $this->lang->line("homework_deadlinedate"),
                'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid|callback_pastdate_check',
            ),
            array(
                'field' => 'subjectID',
                'label' => $this->lang->line("homework_subject"),
                'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_subject',
            ),
            array(
                'field' => 'sectionID',
                'label' => $this->lang->line("homework_section"),
                'rules' => 'xss_clean|callback_unique_section',
            ),
            array(
                'field' => 'photos[]',
                'label' => $this->lang->line("homework_file"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_multiplephotoupload'
            )
        );
        return $rules;
    }

    protected function rules_fileupload()
    {
        $rules = array(
            array(
                'field' => 'photos[]',
                'label' => $this->lang->line("homework_file"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_multiplephotoupload'
            ),
            array(
                'field' => 'content',
                'label' => $this->lang->line("homework_content"),
                'rules' => 'trim|xss_clean',
            )
        );
        return $rules;
    }

    public function index_get($id = null)
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if ($this->session->userdata('usertypeID') == 2) {
            $this->retdata['classesID'] = 0;
            $this->retdata['teacher'] = $this->teacher_m->general_get_teacher($this->session->userdata('loginuserID'));
            $teacher_subjects_id = pluck($this->subjectteacher_m->get_order_by_subjectteacher(['teacherID' =>  $this->session->userdata('loginuserID')]), 'subjectID');
           
            if(count($teacher_subjects_id) == 0){
                $this->response([
                    'status' => false,
                    'message' => 'You are not a subject teacher.',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $homeworks =  $this->homework_m->get_homework_from_subject($schoolyearID, $teacher_subjects_id);
       
            if (customCompute($homeworks)) {
                foreach ($homeworks as $key => $homework) {
                    $homeworkMedias = $this->homework_media_m->get_order_by_homework_media(['homeworkID' => $homework->homeworkID]);
                    if (customCompute($homeworkMedias)) {
                        $homeworks[$key]->files = $homeworkMedias;
                    } else {
                        $homeworks[$key]->files = [];
                    }
                    $homeworks[$key]->submitStudentHomework = count($this->homeworkanswer_m->join_get_homeworkanswer($homework->homeworkID, $schoolyearID, ''));
                    $homeworks[$key]->totalStudentHomework= $this->student_m->get_student_feed(array('classesID' => $homework->classesID, 'schoolyearID' => $schoolyearID), TRUE);
                }
            }
            $this->retdata['homeworks'] = $homeworks;
        } else {
            if ($this->session->userdata('usertypeID') == 3) {
                $id = $this->data['myclass'];
		$usertypeID   = $this->session->userdata('usertypeID');
        	$userID       = $this->session->userdata('loginuserID');
            }
            if ($this->session->userdata('usertypeID') == 4) {
                $student = $this->student_m->get_single_stud(['parentID' => $this->session->userdata('loginuserID')]);
                if ($student) {
                    $id = $student->classesID;
                } else {
                    $id = '';
                }
            }

            $this->retdata['classes'] = $this->classes_m->get_classes();
            if ((int)$id) {
                $fetchClasses = pluck($this->retdata['classes'], 'classesID', 'classesID');
                if (isset($fetchClasses[$id])) {
                    $this->retdata['classesID'] = $id;
                    $this->retdata['sections'] = pluck($this->section_m->general_get_order_by_section(array('classesID' => $id)), 'section', 'sectionID');

                    $homeworks =  $this->homework_m->join_get_homework($id, $schoolyearID);
                    if (customCompute($homeworks)) {
                        foreach ($homeworks as $key => $homework) {
                            $homeworkMedias = $this->homework_media_m->get_order_by_homework_media(['homeworkID' => $homework->homeworkID]);
                            if (customCompute($homeworkMedias)) {
                                $homeworks[$key]->files = $homeworkMedias;
                            } else {
                                $homeworks[$key]->files = [];
                            }

                            if ($this->session->userdata('usertypeID') == 3) {
                                $homeworkanswer = $this->homeworkanswer_m->get_single_homeworkanswer(array('uploaderID' => $this->session->userdata('loginuserID'), 'uploadertypeID' => $this->session->userdata('usertypeID'), 'schoolyearID' => $schoolyearID, 'homeworkID' => $homework->homeworkID));
                            } elseif ($this->session->userdata('usertypeID') == 4) {
                                $homeworkanswer = $this->homeworkanswer_m->get_single_homeworkanswer(array('uploaderID' => $student->studentID, 'uploadertypeID' => 3, 'schoolyearID' => $schoolyearID, 'homeworkID' => $homework->homeworkID));
                            } else {
                                $homeworkanswer = $this->homeworkanswer_m->get_single_homeworkanswer(array('homeworkID' => $homework->homeworkID));
                            }

                            $homework_status_title = $homeworkanswer ? $homeworkanswer->status : '';
                            // if ($homework_ans_status == "pending") {
                            //     $homework_status_title = 'submitted';
                            // } elseif ($homework_ans_status == "checked") {
                            //     $homework_status_title = 'checked';
                            // } elseif ($homework_ans_status == "viewed") {
                            //     $homework_status_title = 'viewed';
                            // } else {
                            //     $homework_status_title = 'pending';
                            // }

                            $homeworks[$key]->answerstatus = $homework_status_title;
                        }
                    }
                    $this->retdata['homeworks'] = $homeworks;
                } else {
                    $this->retdata['classesID'] = 0;
                    $this->retdata['homeworks'] = [];
                }
            } else {
                $this->retdata['classesID'] = 0;
                $this->retdata['homeworks'] = [];
            }
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function create_form_get($courseID = '')
    {

        if (!$courseID) {
            $this->response([
                'status' => false,
                'message' => 'Course id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $course   = $this->courses_m->get_all_join_courses_based_on_course_id($courseID);
        $classesID = $course->class_id;
        $subjectID = $course->subject_id;

        $this->retdata['classesID'] = $classesID;
        $this->retdata['subjectID'] = $course->subject_id;

        $this->retdata['classes']   = pluck($this->classes_m->get_classes(), 'classes', 'classesID');
        $this->retdata['sections']  = pluck($this->section_m->general_get_order_by_section(array("classesID" => $classesID)), 'section', 'sectionID');
        $this->retdata['subjects']  = pluck($this->subject_m->general_get_order_by_subject(array('classesID' => $classesID)), 'subject', 'subjectID');
        $this->retdata['units']     = pluck($this->unit_m->get_units_by_subject_id($subjectID), 'unit_name', 'id');


        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function create_post()
    {
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
                $array = array(
                    "title"            => $this->input->post("title"),
                    "description"      => $this->input->post("description"),
                    "deadlinedate"     => date("Y-m-d", strtotime($this->input->post("deadlinedate"))),
                    'subjectID'        => $this->input->post('subjectID'),
                    "usertypeID"       => $this->session->userdata('usertypeID'),
                    "userID"           => $this->session->userdata('loginuserID'),
                    "classesID"        => $this->input->post("classesID"),
                    "schoolyearID"     => $this->session->userdata('defaultschoolyearID'),
                    "unit_id"          => $this->input->post('unit_id'),
                    "chapter_id"       => $this->input->post('chapter_id'),
                    "course_id"        => $this->input->post('course_id'),
                    'assignusertypeID' => 0,
                    'assignuserID'     => 0,
                );

                $array['sectionID']    = json_encode($this->input->post('sectionID'));
                $this->homework_m->insert_homework($array);
                $homeworkID = $this->db->insert_id();

                if ($homeworkID) {
                    $photos = $this->upload_data['files'];
                    if (customCompute($photos)) {
                        foreach ($photos as $key => $photo) {
                            $photos[$key]['homeworkID'] = $homeworkID;
                        }

                        $this->homework_media_m->insert_batch_homework_media($photos);
                    }
                    $this->response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => ['homeworkID' => $homeworkID],
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Something went wrong.',
                        'data' => [],
                    ], REST_Controller::HTTP_OK);
                }
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'No fields values',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function edit_form_get($homeworkID = '')
    {

        if (!$homeworkID) {
            $this->response([
                'status' => false,
                'message' => 'Homework id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $homework = $this->homework_m->get_single_homework(['homeworkID' => $homeworkID]);
        if (!$homework) {
            $this->response([
                'status' => false,
                'message' => 'Homework not found.',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $this->retdata['homework'] = $homework;

        $course   = $this->courses_m->get_all_join_courses_based_on_course_id($homework->course_id);
        $classesID = $course->class_id;
        $subjectID = $course->subject_id;

        $this->retdata['classesID'] = $classesID;
        $this->retdata['subjectID'] = $course->subject_id;

        $this->retdata['classes']   = pluck($this->classes_m->get_classes(), 'classes', 'classesID');
        $this->retdata['sections']  = pluck($this->section_m->general_get_order_by_section(array("classesID" => $classesID)), 'section', 'sectionID');
        $this->retdata['subjects']  = pluck($this->subject_m->general_get_order_by_subject(array('classesID' => $classesID)), 'subject', 'subjectID');
        $this->retdata['units']     = pluck($this->unit_m->get_units_by_subject_id($subjectID), 'unit_name', 'id');


        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function edit_post($homeworkID = '')
    {
        if (!$homeworkID) {
            $this->response([
                'status' => false,
                'message' => 'Homework id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $homework = $this->homework_m->get_single_homework(['homeworkID' => $homeworkID]);
        if (!$homework) {
            $this->response([
                'status' => false,
                'message' => 'Homework not found.',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

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
                $array = array(
                    "title"            => $this->input->post("title"),
                    "description"      => $this->input->post("description"),
                    "deadlinedate"     => date("Y-m-d", strtotime($this->input->post("deadlinedate"))),
                    'subjectID'        => $this->input->post('subjectID'),
                    "classesID"        => $this->input->post("classesID"),
                    'assignusertypeID' => 0,
                    'assignuserID'     => 0,
                    "unit_id"          => $this->input->post("unit_id"),
                    "chapter_id"       => $this->input->post("chapter_id"),
                );

                $array['sectionID']    = json_encode($this->input->post('sectionID'));

                $this->db->trans_start();
                $this->homework_m->update_homework($array, $homeworkID);

                $photos = $this->upload_data['files'];
                if (customCompute($photos)) {
                    foreach ($photos as $key => $photo) {
                        $photos[$key]['homeworkID'] = $homeworkID;
                    }

                    $this->homework_media_m->insert_batch_homework_media($photos);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() == TRUE) {
                    $homeworkMedias = $this->homework_media_m->get_order_by_homework_media(['homeworkID' => $homework->homeworkID]);
                    $this->response([
                        'status'  => true,
                        'message' => 'Success',
                        'data'   => $homeworkMedias
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Something went wrong.',
                        'data' => [],
                    ], REST_Controller::HTTP_OK);
                }
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'No fields values',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function view_get($id = 0, $url = 0)
    {
        $studentID = '';
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $usertypeID   = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');
        if ($usertypeID == 3) {
            $studentID = $loginuserID;
        }
        if ((int)$id && (int)($url)) {
            $this->retdata['classesID'] = $url;
            $fetchClasses = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
            if (isset($fetchClasses[$url])) {
                $homework = $this->homework_m->get_single_homework(array('homeworkID' => $id, 'classesID' => $url, 'schoolyearID' => $schoolyearID));
                if (customCompute($homework)) {
                    $homeworkanswers = $this->homeworkanswer_m->join_get_homeworkanswer($id, $schoolyearID, $studentID);
                    if (customCompute($homeworkanswers)) {
                        foreach ($homeworkanswers as $key => $homeworkanswer) {
                            $homeworkAnswerMedias = $this->homework_answer_media_m->get_order_by_homework_answer_media(['homeworkanswerID' => $homeworkanswer->homeworkanswerID]);
                            if (customCompute($homeworkAnswerMedias)) {
                                $homeworkanswers[$key]->files = $homeworkAnswerMedias;
                            } else {
                                $homeworkanswers[$key]->files = [];
                            }
                        }
                    }
                    $this->retdata['homeworkanswers'] = $homeworkanswers;
                } else {
                    $this->retdata['homeworkanswers'] = [];
                }
            } else {
                $this->retdata['homeworkanswers'] = [];
            }
        } else {
            $this->retdata['classesID'] = $url;
            $this->retdata['homeworkanswers'] = [];
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function delete_file_get($id = '')
    {
        if (!$id) {
            $this->response([
                'status' => false,
                'message' => 'Id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        if ($this->homework_media_m->delete_homework_media($id)) {
            $this->response([
                'status'    => true,
                'message'   => 'Success',
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Fail',
            ], REST_Controller::HTTP_OK);
        }
    }

    public function delete_homework_get($homeworkID = '')
    {

        if (!$homeworkID) {
            $this->response([
                'status' => false,
                'message' => 'Id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
        $homework = $this->homework_m->get_single_homework(array('homeworkID' => $homeworkID));
        if (customCompute($homework)) {
            $this->homework_m->delete_homework($homeworkID);
            $homeworkMedias = $this->homework_media_m->get_order_by_homework_media(['homeworkID' => $homeworkID]);

            if (customCompute($homeworkMedias)) {
                foreach ($homeworkMedias as $homeworkMedia) {
                    if ($this->homework_media_m->delete_homework_media($homeworkMedia->id)) {
                        if ($homeworkMedia->attachment != '') {
                            if (file_exists(FCPATH . 'uploads/images/' . $homeworkMedia->attachment)) {
                                unlink(FCPATH . 'uploads/images/' . $homeworkMedia->attachment);
                            }
                        }
                    }
                }
            }

            $this->response([
                'status' => true,
                'message' => 'Success',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Homework not found.',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function chapters_get($unitId = '')
    {
        if (!$unitId) {
            $this->response([
                'status'    => false,
                'message'   => 'Empty unit id.',
                'data'      => []
            ], REST_Controller::HTTP_OK);
        }

        $chapters  = $this->chapter_m->get_chapter_from_unit_id($unitId);
        $array[""] = 'Select Chapter';
        foreach ($chapters as $chapter) {
            $array[$chapter->id] = $chapter->chapter_name;
        }
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $array
        ], REST_Controller::HTTP_OK);
    }

    /**
     * Homework answer details
     */
    public function homeworkanswer_get()
    {

        $usertypeID   = $this->session->userdata('usertypeID');
        $userID       = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $homeworkID = $this->input->get('homeworkID');
        $homeworkanswerID = $this->input->get('homeworkanswerID');
        $studentID = $this->input->get('studentID');

        if ($usertypeID == 2) {
            if (!$homeworkanswerID) {
                $this->response([
                    'status' => false,
                    'message' => 'Homework answer Id is empty',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $homeworkanswer = $this->homeworkanswer_m->get_single_homeworkanswer(array('homeworkanswerID' => $homeworkanswerID));
            if (!$homeworkanswer) {
                $this->response([
                    'status' => false,
                    'message' => 'Homework answer not found',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $this->retdata['homeworkanswer'] = $homeworkanswer;
            if ($homeworkanswer) {
                $this->retdata['homework_answer_medias'] = $this->homework_answer_media_m->get_order_by_homework_answer_media(['homeworkanswerID' => $homeworkanswer->homeworkanswerID]);
            } else {
                $this->retdata['homework_answer_medias'] = [];
            }

            if ($homeworkanswer->status == 'pending') {

                $homework = $this->homework_m->get_single_homework(array('homeworkID' => $homeworkanswer->homeworkID, 'schoolyearID' => $schoolyearID));

                $data = [
                    'status' => 'viewed'
                ];
                if ($this->homeworkanswer_m->update_homeworkanswer($data, $homeworkanswerID)) {
                    $userID = $homeworkanswer->uploaderID;
                    $usertypeID = $homeworkanswer->uploadertypeID;
                    $title = 'Homework submission seen.';
                    $notice = 'Your homework: ' . $homework->title . ' submission has been seen';

                    $u = array($userID . $usertypeID);
                    $users = serialize($u);
                    $array = array(
                        "title"             => $title,
                        "notice"            => $notice,
                        "schoolyearID"      => $schoolyearID,
                        "users"             => $users,
                        "date"              => date('Y-m-d'),
                        "create_date"       => date('Y-m-d H:i:s'),
                        "create_userID"     => $this->session->userdata('loginuserID'),
                        "create_usertypeID" => $this->session->userdata('usertypeID'),
                        "show_to_creator"   => 0
                    );
                    $this->notice_m->insert_notice($array);
                    $insert_id = $this->db->insert_id();

                    if ($insert_id) {
                        $this->insertFeed($insert_id, $userID, $usertypeID);
                        $this->addtojob($title, $notice, $users);
                    }
                }
            }
        }

        if ($usertypeID == 3) {
            if (!$homeworkID) {
                $this->response([
                    'status' => false,
                    'message' => 'Homework Id is empty',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $homeworkanswer = $this->homeworkanswer_m->get_single_homeworkanswer(array('uploaderID' => $userID, 'uploadertypeID' => $usertypeID, 'schoolyearID' => $schoolyearID, 'homeworkID' => $homeworkID));
            if (!$homeworkanswer) {
                $this->response([
                    'status' => false,
                    'message' => 'Homework answer not found',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $this->retdata['homeworkanswer'] = $homeworkanswer;
            if ($homeworkanswer) {
                $this->retdata['homework_answer_medias'] = $this->homework_answer_media_m->get_order_by_homework_answer_media(['homeworkanswerID' => $homeworkanswer->homeworkanswerID]);
            } else {
                $this->retdata['homework_answer_medias'] = [];
            }
        }

        if ($usertypeID == 4) {
            if ($homeworkID == '' || $studentID == '') {
                $this->response([
                    'status' => false,
                    'message' => 'Homework Id or student id  is empty',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $homeworkanswer = $this->homeworkanswer_m->get_single_homeworkanswer(array('uploaderID' => $studentID, 'uploadertypeID' => 3, 'schoolyearID' => $schoolyearID, 'homeworkID' => $homeworkID));
            if (!$homeworkanswer) {
                $this->response([
                    'status' => false,
                    'message' => 'Homework answer not found',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $this->retdata['homeworkanswer'] = $homeworkanswer;
            if ($homeworkanswer) {
                $this->retdata['homework_answer_medias'] = $this->homework_answer_media_m->get_order_by_homework_answer_media(['homeworkanswerID' => $homeworkanswer->homeworkanswerID]);
            } else {
                $this->retdata['homework_answer_medias'] = [];
            }
        }

        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $this->retdata,
        ], REST_Controller::HTTP_OK);
    }

    public function homeworkanswer_post($homeworkID = '')
    {
        if (!$homeworkID) {
            $this->response([
                'status' => false,
                'message' => 'Homework Id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $usertypeID   = $this->session->userdata('usertypeID');
        $userID       = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        // $student = $this->student_m->get_single_stud(['studentID' =>$userID]);
        $student     = $this->studentrelation_m->get_single_studentrelation(['srstudentID' => $userID, 'srschoolyearID' => $schoolyearID]);


        $homework = $this->homework_m->get_single_homework(array('homeworkID' => $homeworkID, 'schoolyearID' => $schoolyearID));

        if (!$homework) {
            $this->response([
                'status' => false,
                'message' => 'Homework not found.',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        if (strtotime($homework->deadlinedate) < strtotime(date('Y-m-d'))) {
            $this->response([
                'status' => false,
                'message' => 'Submition close.',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        if ($usertypeID != 3) {
            $this->response([
                'status' => false,
                'message' => 'Not allow.',
            ], REST_Controller::HTTP_OK);
        }

        if ($_POST) {
            $content = $this->input->post('content');
            if ($content == "" and $_FILES['photos']['name'][0] == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Please fill any Field',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $rules = $this->rules_fileupload();

            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->response([
                    'status' => false,
                    'message' => $this->form_validation->error_array(),
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            } else {

                $array['content'] = $this->input->post('content');
                $array['answerfileoriginal'] = '';
                $array['answerfile']         = '';
                $array['homeworkID']         = $homeworkID;
                $array['schoolyearID']       = $this->data['siteinfos']->school_year;
                $array['uploaderID']         = $this->session->userdata('loginuserID');
                $array['uploadertypeID']     = $usertypeID;
                $array['answerdate']         = date('Y-m-d');
                $array['status']             = "pending";

                $homeworkanswer = $this->homeworkanswer_m->get_single_homeworkanswer(array('uploaderID' => $userID, 'uploadertypeID' => $usertypeID, 'schoolyearID' => $schoolyearID, 'homeworkID' => $homeworkID));

                if (customCompute($homeworkanswer)) {
                    $this->homeworkanswer_m->update_homeworkanswer($array, $homeworkanswer->homeworkanswerID);

                    $photos = $this->upload_data['files'];
                    if (customCompute($photos)) {
                        foreach ($photos as $key => $photo) {
                            $photos[$key]['homeworkanswerID'] = $homeworkanswer->homeworkanswerID;
                        }

                        $this->homework_answer_media_m->insert_batch_homework_answer_media($photos);
                    }

                    $this->response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => [],
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->homeworkanswer_m->insert_homeworkanswer($array);
                    $homeworkanswerID = $this->db->insert_id();

                    if ($homeworkanswerID) {
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        $photos = $this->upload_data['files'];
                        if (customCompute($photos)) {
                            foreach ($photos as $key => $photo) {
                                $photos[$key]['homeworkanswerID'] = $homeworkanswerID;
                            }

                            $this->homework_answer_media_m->insert_batch_homework_answer_media($photos);
                        }

                        $userID = $homework->userID;
                        $usertypeID = $homework->usertypeID;
                        $title = 'Homework submission';
                        $notice = 'Homework: ' . $homework->title . ' is submitted by ' . $student->srname;

                        $u = array($userID . $usertypeID);
                        $users = serialize($u);
                        $array = array(
                            "title"             => $title,
                            "notice"            => $notice,
                            "schoolyearID"      => $schoolyearID,
                            "users"             => $users,
                            "date"              => date('Y-m-d'),
                            "create_date"       => date('Y-m-d H:i:s'),
                            "create_userID"     => $this->session->userdata('loginuserID'),
                            "create_usertypeID" => $this->session->userdata('usertypeID'),
                            "show_to_creator"   => 0
                        );
                        $this->notice_m->insert_notice($array);
                        $insert_id = $this->db->insert_id();

                        if ($insert_id) {
                            $this->insertFeed($insert_id, $userID, $usertypeID);
                            $this->addtojob($title, $notice, $users);
                        }
                    } else {
                        $this->response([
                            'status' => false,
                            'message' => 'Something went wrong.',
                            'data' => [],
                        ], REST_Controller::HTTP_OK);
                    }

                    $this->response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => [],
                    ], REST_Controller::HTTP_OK);
                }
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'No fields values',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function deleteanswerfile_get($id = '')
    {

        if (!$id) {
            $this->response([
                'status' => false,
                'message' => 'Id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $delete = $this->homework_answer_media_m->delete_homework_answer_media($id);
        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => [],
        ], REST_Controller::HTTP_OK);
    }

    public function homeworkStatus_get($homeworkId)
    {
        $record = $this->homework_m->get_single_homework(['homeworkID' => $homeworkId]);
        if ($record) {
            if ($this->homework_m->update_homework(['is_published' => !$record->is_published], $homeworkId) && $record->is_published == 0) {
                $subject = $this->subject_m->general_get_single_subject(['subjectID' => $record->subjectID]);
                $sectionID = $record->sectionID ? json_decode($record->sectionID) : '';
                $title = 'Homework Published';
                if ($subject) {
                    $notice = "Homework " . $record->title . " for subject " . $subject ? $subject->subject : '' . " has been published";
                } else {
                    $notice = "Homework " . $record->title . " has been published";
                }

                $course_id = $record->course_id;
                $publish_status = false;
                if ($course_id != 0) {
                    $course = $this->courses_m->get_single_courses(['id' => $course_id]);
                    if ($course->published == 1) {
                        $publish_status = true;
                        if ($record->unit_id) {
                            $dbUnit = $this->unit_m->get_single_subject(['id' => $record->unit_id]);
                            if ($dbUnit->published == 1) {
                                $publish_status = true;
                            } else {
                                $publish_status = false;
                            }
                        }
                        if ($publish_status) {
                            if ($record->chapter_id) {
                                $dbChapter = $this->chapter_m->get_single_subject(['id' => $record->chapter_id]);
                                if ($dbChapter->published == 1) {
                                    $publish_status = true;
                                } else {
                                    $publish_status = false;
                                }
                            }
                        }
                    }
                }
                if ($publish_status) {
                    $this->notification($title, $notice, $record->classesID, $sectionID);
                }
            }
            if ($record->is_published == 0) {
                $msg = 'Homework published';
            } else {
                $msg = 'Homework unpublished';
            }
            $this->response([
                'status'    => true,
                'message'   => $msg
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Record not found for id ' . $homeworkId,
            ], REST_Controller::HTTP_OK);
        }
    }

    public function addremarks_post($homeworkanswerID = '')
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        if (!$homeworkanswerID) {
            $this->response([
                'status' => false,
                'message' => 'Id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $comment = $this->input->post('comment');
        $data = [
            'remarks' => $comment
        ];
        if ($this->homeworkanswer_m->update_homeworkanswer($data, $homeworkanswerID)) {

            $homeworkanswer = $this->homeworkanswer_m->get_single_homeworkanswer(array('homeworkanswerID' => $homeworkanswerID));
            $homework = $this->homework_m->get_single_homework(['homeworkID' => $homeworkanswer->homeworkID]);

            $title = 'Homework submission remark.';
            $notice = 'Remarks has been added on your homework: ' . $homework->title . ' by teacher.';
            $userID = $homeworkanswer->uploaderID;
            $usertypeID = $homeworkanswer->uploadertypeID;
            $u = [$userID . $usertypeID];

            $users = serialize($u);

            $array = array(
                "title"             => $title,
                "notice"            => $notice,
                "schoolyearID"      => $schoolyearID,
                "users"             => $users,
                "date"              => date('Y-m-d'),
                "create_date"       => date('Y-m-d H:i:s'),
                "create_userID"     => $this->session->userdata('loginuserID'),
                "create_usertypeID" => $this->session->userdata('usertypeID'),
                "show_to_creator"   => 0
            );
            $this->notice_m->insert_notice($array);
            $insert_id = $this->db->insert_id();

            if ($insert_id) {
                $this->insertFeed($insert_id, $userID, $usertypeID);
                $this->addtojob($title, $notice, $users);
            }

            $this->response([
                'status' => true,
                'message' => 'Success',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Fail',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function update_homeworkanswer_status_post()
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $ids = $this->input->post('ids');
        $idArray = explode(',', $ids);
        $u = [];
        foreach ($idArray as $id) {
            $data = [
                'status' => 'checked'
            ];
            if ($this->homeworkanswer_m->update_homeworkanswer($data, $id)) {
                $homeworkanswer = $this->homeworkanswer_m->get_single_homeworkanswer(array('homeworkanswerID' => $id));
                $userID = $homeworkanswer->uploaderID;
                $usertypeID = $homeworkanswer->uploadertypeID;
                $u[] = $userID . $usertypeID;
            }
        }

        $homework = $this->homework_m->get_single_homework(['homeworkID' => $homeworkanswer->homeworkID]);

        $title = 'Homework submission checked.';
        $notice = 'Your homework: ' . $homework->title . ' has been checked';

        $users = serialize($u);

        $array = array(
            "title"             => $title,
            "notice"            => $notice,
            "schoolyearID"      => $schoolyearID,
            "users"             => $users,
            "date"              => date('Y-m-d'),
            "create_date"       => date('Y-m-d H:i:s'),
            "create_userID"     => $this->session->userdata('loginuserID'),
            "create_usertypeID" => $this->session->userdata('usertypeID'),
            "show_to_creator"   => 0
        );
        $this->notice_m->insert_notice($array);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {

            // insert feed
            $this->feed_m->insert_feed(
                array(
                    'itemID'            => $insert_id,
                    'userID'            => $this->session->userdata("loginuserID"),
                    'usertypeID'        => $this->session->userdata('usertypeID'),
                    'itemname'          => 'notice',
                    'schoolyearID'      => $this->session->userdata('defaultschoolyearID'),
                    'published'         => 1,
                    'published_date'    => date("Y-m-d"),
                    "show_to_creator"   => 0
                )
            );
            $feedID = $this->db->insert_id();

            if (customCompute($u)) {
                foreach ($u as $nu) {

                    $user_id = substr($nu, 0, -1);
                    $user_type = substr($nu, -1);
                    // insert users
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
            $this->addtojob($title, $notice, $users);
        }

        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => [],
        ], REST_Controller::HTTP_OK);
    }

    /**
     * View Homework
     */
    public function homeworkdetail_get($homeworkID = '')
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        if (!$homeworkID) {
            $this->response([
                'status' => false,
                'message' => 'Homework Id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $homework = $this->homework_m->get_single_homework(array('homeworkID' => $homeworkID, 'schoolyearID' => $schoolyearID));

        if (!$homework) {
            $this->response([
                'status' => false,
                'message' => 'Homework not found',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
        $this->retdata['homework'] = $homework;
        if ($homework) {
            $this->retdata['homework_medias'] = $this->homework_media_m->get_order_by_homework_media(['homeworkID' => $homework->homeworkID]);
        } else {
            $this->retdata['homework_medias'] = [];
        }

        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $this->retdata,
        ], REST_Controller::HTTP_OK);
    }

    public function pastdate_check()
    {
        $date     = strtotime($this->input->post("deadlinedate"));
        $now_date = strtotime(date("d-m-Y"));
        if ($date) {
            if ($date < $now_date) {
                $this->form_validation->set_message("pastdate_check", "The %s field is past date");
                return false;
            }
            return true;
        }
        return true;
    }

    public function unique_classes()
    {
        if ($this->input->post('classesID') == 0) {
            $this->form_validation->set_message("unique_classes", "The %s field is required");
            return false;
        }
        return true;
    }

    public function unique_section()
    {
        $count     = 0;
        $sections  = $this->input->post('sectionID');
        $classesID = $this->input->post('classesID');
        if (customCompute($sections) && $sections != false && $classesID) {
            foreach ($sections as $sectionkey => $section) {
                $setSection   = $section;
                $getDBSection = $this->section_m->general_get_single_section(array('sectionID' => $section, 'classesID' => $classesID));
                if (!customCompute($getDBSection)) {
                    $count++;
                }
            }

            if ($count == 0) {
                return true;
            } else {
                $this->form_validation->set_message("unique_section", "The %s is not match in class");
                return false;
            }
        }
        return true;
    }

    public function date_valid($date)
    {
        if (strlen($date) < 10) {
            $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
            return false;
        } else {
            $arr  = explode("-", $date);
            $dd   = $arr[0];
            $mm   = $arr[1];
            $yyyy = $arr[2];
            if (checkdate($mm, $dd, $yyyy)) {
                return true;
            } else {
                $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                return false;
            }
        }
    }

    public function unique_subject()
    {
        if ($this->input->post('subjectID') == 0) {
            $this->form_validation->set_message("unique_subject", "The %s field is required");
            return false;
        }
        return true;
    }

    public function unique_unit()
    {
        if ($this->input->post('unitId') == 0) {
            $this->form_validation->set_message("unique_unit", "The %s field is required");
            return false;
        }
        return true;
    }

    public function unique_chapter()
    {
        if ($this->input->post('chapterId') == 0) {
            $this->form_validation->set_message("unique_chapter", "The %s field is required");
            return false;
        }
        return true;
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
                $uploadPath = 'uploads/images';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                for ($i = 0; $i < $filesCount; $i++) {
                    $_FILES['attach']['name'] = $_FILES['photos']['name'][$i];
                    $_FILES['attach']['type'] = $_FILES['photos']['type'][$i];
                    $_FILES['attach']['tmp_name'] = $_FILES['photos']['tmp_name'][$i];
                    $_FILES['attach']['error'] = $_FILES['photos']['error'][$i];
                    $_FILES['attach']['size'] = $_FILES['photos']['size'][$i];

                    $config['upload_path']   = "./uploads/images";
                    $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('attach')) {
                        $fileData = $this->upload->data();
                        $image_width = $fileData['image_width'];
					    $image_height = $fileData['image_height'];
                        if($fileData['is_image'] == '1')
                        {
                            resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height); 
                        }
                        $uploadData[$i]['attachment'] = $fileData['file_name'];
                        $uploadData[$i]['caption'] = $_POST['caption'][$i];
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

    public function notification($title, $notice, $class, $sectionID = '')
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $students = $this->student_m->get_order_by_student_with_section1($class, $schoolyearID, $sectionID);
        $newData = [];
        foreach ($students as $index => $student) {
            $newData[] = $student->studentID . '3';
            $parent_id = $student->parentID;
            if ($parent_id) {
                $newData[] = $student->parentID . '4';
            }
        }

        $sstudents = serialize($newData);
        $array = array(
            "title"             => $title,
            "notice"            => $notice,
            "schoolyearID"      => $schoolyearID,
            "users"             => $sstudents,
            "date"              => date('Y-m-d'),
            "create_date"       => date('Y-m-d H:i:s'),
            "create_userID"     => $this->session->userdata('loginuserID'),
            "create_usertypeID" => $this->session->userdata('usertypeID'),
            "show_to_creator"   => 0
        );
        $this->notice_m->insert_notice($array);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            // insert feed
            $this->feed_m->insert_feed(
                array(
                    'itemID'            => $insert_id,
                    'userID'            => $this->session->userdata("loginuserID"),
                    'usertypeID'        => $this->session->userdata('usertypeID'),
                    'itemname'          => 'notice',
                    'schoolyearID'      => $this->session->userdata('defaultschoolyearID'),
                    'published'         => 1,
                    'published_date'    => date("Y-m-d"),
                    "show_to_creator"   => 0
                )
            );
            $feedID = $this->db->insert_id();

            if (customCompute($newData)) {
                foreach ($newData as $newD) {
                    $user_id = substr($newD, 0, -1);
                    $user_type = substr($newD, -1);
                    // insert users
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
        if ($class) {

            $this->pushNotification($notice, $class);
            $this->mobPushNotification($array);
        }
    }

    public function pushNotification($title, $class = null)
    {
        $this->job_m->insert_job([
            'name' => 'sendCourseNotification',
            'payload' => json_encode([
                'class' => $class,
                'title' => $title, // title is compulsary
            ]),
        ]);
    }

    public function mobPushNotification($array)
    {
        $this->mobile_job_m->insert_job([
            'name' => 'sendCourseNotification',
            'payload' => json_encode([
                'users' => $array['users'],
                'title' => $array['title'], // title is compulsary
                'message' => $array['notice']
            ]),
        ]);
    }

    public function insertFeed($insert_id, $userID, $usertypeID)
    {

        $this->feed_m->insert_feed(
            array(
                'itemID'            => $insert_id,
                'userID'            => $this->session->userdata("loginuserID"),
                'usertypeID'        => $this->session->userdata('usertypeID'),
                'itemname'          => 'notice',
                'schoolyearID'      => $this->session->userdata('defaultschoolyearID'),
                'published'         => 1,
                'published_date'    => date("Y-m-d"),
                "show_to_creator"   => 0
            )
        );
        $feedID = $this->db->insert_id();

        // insert users
        $noticeUser = [
            'notice_id'  => $insert_id,
            'user_id'    => $userID,
            'usertypeID' => $usertypeID
        ];

        $feedUser = [
            'feed_id'    => $feedID,
            'user_id'    => $userID,
            'usertypeID' => $usertypeID
        ];

        $this->notice_m->insert_notice_user($noticeUser);
        $this->feed_m->insert_feed_user($feedUser);
    }

    public function addtojob($title, $notice, $users)
    {

        $this->job_m->insert_job([
            'name' => 'sendNotice',
            'payload' => json_encode([
                'users' => $users,
                'title' => $title, // title is compulsary
                'message' => $notice
            ]),
        ]);

        $this->mobile_job_m->insert_job([
            'name' => 'sendNotice',
            'payload' => json_encode([
                'users' => $users,
                'title' => $title, // title is compulsary
                'message' => $notice
            ]),
        ]);
    }
}
