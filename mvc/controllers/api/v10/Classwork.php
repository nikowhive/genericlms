<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class classwork extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("job_m");
        $this->load->model("feed_m");
        $this->load->model("mobile_job_m");
        $this->load->model('section_m');
        $this->load->model('classes_m');
        $this->load->model('classwork_m');
        $this->load->model('classworkanswer_m');
        $this->load->model('teacher_m');
        $this->load->model('subjectteacher_m');
        $this->load->model("subject_m");
        $this->load->model("student_m");
        $this->load->model("courses_m");
        $this->load->model("unit_m");
        $this->load->model("studentrelation_m");
        $this->load->model("notice_m");
        $this->load->model("chapter_m");
        $this->load->model("classwork_media_m");
        $this->load->model("classwork_answer_media_m");
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("classwork_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]',
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("classwork_description"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'classesID',
                'label' => $this->lang->line("classwork_classes"),
                'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_classes',
            ),
            array(
                'field' => 'deadlinedate',
                'label' => $this->lang->line("classwork_deadlinedate"),
                'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid|callback_pastdate_check',
            ),
            array(
                'field' => 'subjectID',
                'label' => $this->lang->line("classwork_subject"),
                'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_subject',
            ),
            array(
                'field' => 'sectionID',
                'label' => $this->lang->line("classwork_section"),
                'rules' => 'xss_clean|callback_unique_section',
            ),
            array(
                'field' => 'photos[]',
                'label' => $this->lang->line("classwork_file"),
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
                'label' => $this->lang->line("classwork_file"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_multiplephotoupload'
            ),
            array(
                'field' => 'content',
                'label' => $this->lang->line("classwork_content"),
                'rules' => 'trim|xss_clean',
            )
        );
        return $rules;
    }

    public function index_get($id = null)
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if ($this->session->userdata('usertypeID') == 2) {
            $this->retdata['teacher'] = $this->teacher_m->general_get_teacher($this->session->userdata('loginuserID'));
            $teacher_subjects_id = pluck($this->subjectteacher_m->get_order_by_subjectteacher(['teacherID' =>  $this->session->userdata('loginuserID')]), 'subjectID');

            $classworks =  $this->classwork_m->get_classwork_from_subject($schoolyearID,  $teacher_subjects_id);
            if (customCompute($classworks)) {
                foreach ($classworks as $key => $classwork) {
                    $classworkMedias = $this->classwork_media_m->get_order_by_classwork_media(['classworkID' => $classwork->classworkID]);
                    if (customCompute($classworkMedias)) {
                        $classworks[$key]->files = $classworkMedias;
                    } else {
                        $classworks[$key]->files = [];
                    }
                    $classworks[$key]->submitStudentClasswork = count($this->classworkanswer_m->join_get_classworkanswer($classwork->classworkID, $schoolyearID, ''));
                    $classworks[$key]->totalStudentClasswork= $this->student_m->get_student_feed(array('classesID' => $classwork->classesID, 'schoolyearID' => $schoolyearID), TRUE);
                }
            }
            $this->retdata['classworks'] = $classworks;
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

                    $classworks =  $this->classwork_m->join_get_classwork($id, $schoolyearID);
                    if (customCompute($classworks)) {
                        foreach ($classworks as $key => $classwork) {
                            $classworkMedias = $this->classwork_media_m->get_order_by_classwork_media(['classworkID' => $classwork->classworkID]);
                            if (customCompute($classworkMedias)) {
                                $classworks[$key]->files = $classworkMedias;
                            } else {
                                $classworks[$key]->files = [];
                            }

                            if ($this->session->userdata('usertypeID') == 3) {
                                $classworkanswer = $this->classworkanswer_m->get_single_classworkanswer(array('uploaderID' => $this->session->userdata('loginuserID'), 'uploadertypeID' => $this->session->userdata('usertypeID'), 'schoolyearID' => $schoolyearID, 'classworkID' => $classwork->classworkID));
                            } elseif ($this->session->userdata('usertypeID') == 4) {
                                $classworkanswer = $this->classworkanswer_m->get_single_classworkanswer(array('uploaderID' => $student->studentID, 'uploadertypeID' => 3, 'schoolyearID' => $schoolyearID, 'classworkID' => $classwork->classworkID));
                            } else {
                                $classworkanswer = $this->classworkanswer_m->get_single_classworkanswer(array('classworkID' => $classwork->classworkID));
                            }

                            $classwork_ans_status = $classworkanswer ? $classworkanswer->status : '';
                            // if ($classwork_ans_status == "pending") {
                            //     $classwork_status_title = 'submitted';
                            // } elseif ($classwork_ans_status == "checked") {
                            //     $classwork_status_title = 'checked';
                            // } elseif ($classwork_ans_status == "viewed") {
                            //     $classwork_status_title = 'viewed';
                            // } else {
                            //     $classwork_status_title = 'pending';
                            // }

                            $classworks[$key]->answerstatus = $classwork_ans_status;
                        }
                    }
                    $this->retdata['classworks'] = $classworks;
                } else {
                    $this->retdata['classesID'] = 0;
                    $this->retdata['classworks'] = [];
                }
            } else {
                $this->retdata['classesID'] = 0;
                $this->retdata['classworks'] = [];
            }
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    /**
     * Classwork answer list
     */
    public function view_get($id = 0, $url = 0)
    {
        $studentID = '';
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $usertypeID   = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');
        if ($usertypeID == 3) {
            $studentID = $loginuserID;
        }
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if ((int)$id && (int)($url)) {
            $this->retdata['classesID'] = $url;
            $fetchClasses = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
            if (isset($fetchClasses[$url])) {
                $classwork = $this->classwork_m->get_single_classwork(array('classworkID' => $id, 'classesID' => $url, 'schoolyearID' => $schoolyearID));
                if (customCompute($classwork)) {
                    $classworkanswers = $this->classworkanswer_m->join_get_classworkanswer($id, $schoolyearID, $studentID);
                    if (customCompute($classworkanswers)) {
                        foreach ($classworkanswers as $key => $classworkanswer) {
                            $classworkAnswerMedias = $this->classwork_answer_media_m->get_order_by_classwork_answer_media(['classworkanswerID' => $classworkanswer->classworkanswerID]);
                            if (customCompute($classworkAnswerMedias)) {
                                $classworkanswers[$key]->files = $classworkAnswerMedias;
                            } else {
                                $classworkanswers[$key]->files = [];
                            }
                        }
                    }
                    $this->retdata['classworkanswers'] = $classworkanswers;
                } else {
                    $this->retdata['classworkanswers'] = [];
                }
            } else {
                $this->retdata['classworkanswers'] = [];
            }
        } else {
            $this->retdata['classesID'] = $url;
            $this->retdata['classworkanswers'] = [];
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
                $this->classwork_m->insert_classwork($array);
                $classworkID = $this->db->insert_id();

                if ($classworkID) {
                    $photos = $this->upload_data['files'];
                    if (customCompute($photos)) {
                        foreach ($photos as $key => $photo) {
                            $photos[$key]['classworkID'] = $classworkID;
                        }

                        $this->classwork_media_m->insert_batch_classwork_media($photos);
                    }
                    $this->response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => ['classworkID' => $classworkID],
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

    public function edit_form_get($classworkID = '')
    {

        if (!$classworkID) {
            $this->response([
                'status' => false,
                'message' => 'Classwork id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $classwork = $this->classwork_m->get_single_classwork(['classworkID' => $classworkID]);
        if (!$classwork) {
            $this->response([
                'status' => false,
                'message' => 'Classwork not found.',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $this->retdata['classwork'] = $classwork;

        $course   = $this->courses_m->get_all_join_courses_based_on_course_id($classwork->course_id);
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

    public function edit_post($classworkID = '')
    {
        if (!$classworkID) {
            $this->response([
                'status' => false,
                'message' => 'Classwork id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $classwork = $this->classwork_m->get_single_classwork(['classworkID' => $classworkID]);
        if (!$classwork) {
            $this->response([
                'status' => false,
                'message' => 'Classwork not found.',
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
                $this->classwork_m->update_classwork($array, $classworkID);

                $photos = $this->upload_data['files'];
                if (customCompute($photos)) {
                    foreach ($photos as $key => $photo) {
                        $photos[$key]['classworkID'] = $classworkID;
                    }

                    $this->classwork_media_m->insert_batch_classwork_media($photos);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() == TRUE) {
                    $ClassworkMedias = $this->Classwork_media_m->get_order_by_Classwork_media(['ClassworkID' => $classwork->ClassworkID]);
                    $this->response([
                        'status'  => true,
                        'message' => 'Success',
                        'data'   => $ClassworkMedias
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

    public function delete_file_get($id = '')
    {
        if (!$id) {
            $this->response([
                'status' => false,
                'message' => 'Id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        if ($this->classwork_media_m->delete_classwork_media($id)) {
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

    public function delete_classwork_get($classworkID = '')
    {

        if (!$classworkID) {
            $this->response([
                'status' => false,
                'message' => 'Id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
        $classwork = $this->classwork_m->get_single_classwork(array('classworkID' => $classworkID));
        if (customCompute($classwork)) {
            $this->classwork_m->delete_classwork($classworkID);
            $classworkMedias = $this->classwork_media_m->get_order_by_classwork_media(['classworkID' => $classworkID]);

            if (customCompute($classworkMedias)) {
                foreach ($classworkMedias as $classworkMedia) {
                    if ($this->classwork_media_m->delete_classwork_media($classworkMedia->id)) {
                        if ($classworkMedia->attachment != '') {
                            if (file_exists(FCPATH . 'uploads/images/' . $classworkMedia->attachment)) {
                                unlink(FCPATH . 'uploads/images/' . $classworkMedia->attachment);
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
                'message' => 'Classwork not found.',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function addremarks_post($classworkanswerID = '')
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        if (!$classworkanswerID) {
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
        if ($this->classworkanswer_m->update_classworkanswer($data, $classworkanswerID)) {

            $classworkanswer = $this->classworkanswer_m->get_single_classworkanswer(array('classworkanswerID' => $classworkanswerID));
            $classwork = $this->classwork_m->get_single_classwork(['classworkID' => $classworkanswer->classworkID]);

            $title = 'Classwork submission remark.';
            $notice = 'Remarks has been added on your classwork: ' . $classwork->title . ' by teacher.';
            $userID = $classworkanswer->uploaderID;
            $usertypeID = $classworkanswer->uploadertypeID;
            $u = [$userID . $usertypeID];

            $users = serialize($u);

            $array = array(
                "title" => $title,
                "notice" => $notice,
                "schoolyearID" => $schoolyearID,
                "users" => $users,
                "date" => date('Y-m-d'),
                "create_date" => date('Y-m-d H:i:s'),
                "create_userID" => $this->session->userdata('loginuserID'),
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

    public function update_classworkanswer_status_post()
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $ids = $this->input->post('ids');
        $idArray = explode(',', $ids);
        $u = [];
        foreach ($idArray as $id) {
            $data = [
                'status' => 'checked'
            ];
            if ($this->classworkanswer_m->update_classworkanswer($data, $id)) {
                $classworkanswer = $this->classworkanswer_m->get_single_classworkanswer(array('classworkanswerID' => $id));
                $userID = $classworkanswer->uploaderID;
                $usertypeID = $classworkanswer->uploadertypeID;
                $u[] = $userID . $usertypeID;
            }
        }

        $classwork = $this->classwork_m->get_single_classwork(['classworkID' => $classworkanswer->classworkID]);

        $title = 'Classwork submission checked.';
        $notice = 'Your classwork: ' . $classwork->title . ' has been checked';


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
     * Classwork answer details
     */
    public function classworkanswer_get()
    {

        $usertypeID   = $this->session->userdata('usertypeID');
        $userID       = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $classworkID = $this->input->get('classworkID');
        $classworkanswerID = $this->input->get('classworkanswerID');
        $studentID = $this->input->get('studentID');

        if ($usertypeID == 2) {
            if (!$classworkanswerID) {
                $this->response([
                    'status' => false,
                    'message' => 'Classwork answer Id is empty',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $classworkanswer = $this->classworkanswer_m->get_single_classworkanswer(array('classworkanswerID' => $classworkanswerID));
            if (!$classworkanswer) {
                $this->response([
                    'status' => false,
                    'message' => 'Classwork answer not found',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $this->retdata['classworkanswer'] = $classworkanswer;
            if ($classworkanswer) {
                $this->retdata['classwork_answer_medias'] = $this->classwork_answer_media_m->get_order_by_classwork_answer_media(['classworkanswerID' => $classworkanswer->classworkanswerID]);
            } else {
                $this->retdata['classwork_answer_medias'] = [];
            }

            if ($classworkanswer->status == 'pending') {

                $classwork = $this->classwork_m->get_single_classwork(array('classworkID' => $classworkanswer->classworkID, 'schoolyearID' => $schoolyearID));

                $data = [
                    'status' => 'viewed'
                ];
                if ($this->classworkanswer_m->update_classworkanswer($data, $classworkanswerID)) {
                    $userID = $classworkanswer->uploaderID;
                    $usertypeID = $classworkanswer->uploadertypeID;
                    $title = 'Classwork submission seen.';
                    $notice = 'Your classwork: ' . $classwork->title . ' submission has been seen';

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
            if (!$classworkID) {
                $this->response([
                    'status' => false,
                    'message' => 'Classwork Id is empty',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $classworkanswer = $this->classworkanswer_m->get_single_classworkanswer(array('uploaderID' => $userID, 'uploadertypeID' => $usertypeID, 'schoolyearID' => $schoolyearID, 'classworkID' => $classworkID));
            if (!$classworkanswer) {
                $this->response([
                    'status' => false,
                    'message' => 'Classwork answer not found',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $this->retdata['classworkanswer'] = $classworkanswer;
            if ($classworkanswer) {
                $this->retdata['classwork_answer_medias'] = $this->classwork_answer_media_m->get_order_by_classwork_answer_media(['classworkanswerID' => $classworkanswer->classworkanswerID]);
            } else {
                $this->retdata['classwork_answer_medias'] = [];
            }
        }

        if ($usertypeID == 4) {
            if ($classworkID == '' || $studentID == '') {
                $this->response([
                    'status' => false,
                    'message' => 'Classwork Id or student id  is empty',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $classworkanswer = $this->classworkanswer_m->get_single_classworkanswer(array('uploaderID' => $studentID, 'uploadertypeID' => 3, 'schoolyearID' => $schoolyearID, 'classworkID' => $classworkID));
            if (!$classworkanswer) {
                $this->response([
                    'status' => false,
                    'message' => 'Classwork answer not found',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $this->retdata['classworkanswer'] = $classworkanswer;
            if ($classworkanswer) {
                $this->retdata['classwork_answer_medias'] = $this->classwork_answer_media_m->get_order_by_classwork_answer_media(['classworkanswerID' => $classworkanswer->classworkanswerID]);
            } else {
                $this->retdata['classwork_answer_medias'] = [];
            }
        }

        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $this->retdata,
        ], REST_Controller::HTTP_OK);
    }

    public function classworkanswer_post($classworkID = '')
    {
        if (!$classworkID) {
            $this->response([
                'status' => false,
                'message' => 'Classwork Id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $usertypeID   = $this->session->userdata('usertypeID');
        $userID       = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        // $student = $this->student_m->get_single_stud(['studentID' =>$userID]);
        $student     = $this->studentrelation_m->get_single_studentrelation(['srstudentID' => $userID, 'srschoolyearID' => $schoolyearID]);


        $classwork = $this->classwork_m->get_single_classwork(array('classworkID' => $classworkID, 'schoolyearID' => $schoolyearID));

        if (!$classwork) {
            $this->response([
                'status' => false,
                'message' => 'Classwork not found.',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        if (strtotime($classwork->deadlinedate) < strtotime(date('Y-m-d'))) {
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
                $array['classworkID']         = $classworkID;
                $array['schoolyearID']       = $this->data['siteinfos']->school_year;
                $array['uploaderID']         = $this->session->userdata('loginuserID');
                $array['uploadertypeID']     = $usertypeID;
                $array['answerdate']         = date('Y-m-d');
                $array['status']             = 'pending';

                $classworkanswer = $this->classworkanswer_m->get_single_classworkanswer(array('uploaderID' => $userID, 'uploadertypeID' => $usertypeID, 'schoolyearID' => $schoolyearID, 'classworkID' => $classworkID));

                if (customCompute($classworkanswer)) {
                    $this->classworkanswer_m->update_classworkanswer($array, $classworkanswer->classworkanswerID);

                    $photos = $this->upload_data['files'];
                    if (customCompute($photos)) {
                        foreach ($photos as $key => $photo) {
                            $photos[$key]['classworkanswerID'] = $classworkanswer->classworkanswerID;
                        }

                        $this->classwork_answer_media_m->insert_batch_classwork_answer_media($photos);
                    }

                    $userID = $classwork->userID;
                    $usertypeID = $classwork->usertypeID;
                    $title = 'Classwork submission';
                    $notice = 'Classwork: ' . $classwork->title . ' is submitted by ' . $student->srname;

                    $u = array($userID . $usertypeID);
                    $users = serialize($u);
                    $array = array(
                        "title" => $title,
                        "notice" => $notice,
                        "schoolyearID" => $schoolyearID,
                        "users" => $users,
                        "date" => date('Y-m-d'),
                        "create_date" => date('Y-m-d H:i:s'),
                        "create_userID" => $this->session->userdata('loginuserID'),
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
                    $this->classworkanswer_m->insert_classworkanswer($array);
                    $classworkanswerID = $this->db->insert_id();

                    if ($classworkanswerID) {
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        $photos = $this->upload_data['files'];
                        if (customCompute($photos)) {
                            foreach ($photos as $key => $photo) {
                                $photos[$key]['classworkanswerID'] = $classworkanswerID;
                            }

                            $this->classwork_answer_media_m->insert_batch_classwork_answer_media($photos);
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

        $delete = $this->classwork_answer_media_m->delete_classwork_answer_media($id);
        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => [],
        ], REST_Controller::HTTP_OK);
    }

    public function classworkStatus_get($classworkId)
    {
        $record = $this->classwork_m->get_single_classwork(['classworkID' => $classworkId]);

        if ($record) {
            if ($this->classwork_m->update_classwork(['is_published' => !$record->is_published], $classworkId) && $record->is_published == 0) {
                $subject = $this->subject_m->general_get_single_subject(['subjectID' => $record->subjectID]);
                $sectionID = $record->sectionID ? json_decode($record->sectionID) : '';
                $title = 'Classwork Published';
                if ($subject) {
                    $notice = "Classwork " . $record->title . " for subject " . $subject->subject . " has been published";
                } else {
                    $notice = "Classwork " . $record->title . " has been published";
                }

                $publish_status = false;
                $course_id = $record->course_id;
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
                $msg = 'Classwork published';
            } else {
                $msg = 'Classwork unpublished';
            }
            $this->response([
                'status'    => true,
                'message'   => $msg
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Record not found for id ' . $classworkId,
            ], REST_Controller::HTTP_OK);
        }
    }

    /**
     * View Classwork
     */
    public function classworkdetail_get($classworkID = '')
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        if (!$classworkID) {
            $this->response([
                'status' => false,
                'message' => 'Classwork Id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $classwork = $this->classwork_m->get_single_classwork(array('classworkID' => $classworkID, 'schoolyearID' => $schoolyearID));

        if (!$classwork) {
            $this->response([
                'status' => false,
                'message' => 'Classwork not found',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
        $this->retdata['classwork'] = $classwork;
        if ($classwork) {
            $this->retdata['classwork_medias'] = $this->classwork_media_m->get_order_by_classwork_media(['classworkID' => $classwork->classworkID]);
        } else {
            $this->retdata['classwork_medias'] = [];
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

                    $image_info = getimagesize($_FILES['photos']['tmp_name'][$i]);
					$image_width = $image_info[0];
					$image_height = $image_info[1];

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('attach')) {
                        $fileData = $this->upload->data();
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
