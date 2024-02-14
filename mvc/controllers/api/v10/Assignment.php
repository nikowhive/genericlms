<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Assignment extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("job_m");
        $this->load->model("feed_m");
        $this->load->model("mobile_job_m");
        $this->load->model("unit_m");
        $this->load->model('section_m');
        $this->load->model('classes_m');
        $this->load->model('teacher_m');
        $this->load->model("subject_m");
        $this->load->model("student_m");
        $this->load->model("courses_m");
        $this->load->model("chapter_m");
        $this->load->model('notice_m');
        $this->load->model("studentrelation_m");
        $this->load->model("assignment_m");
        $this->load->model('subjectteacher_m');
        $this->load->model('assignment_media_m');
        $this->load->model("assignmentanswer_m");
        $this->load->model("assignment_answer_media_m");
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("assignment_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]',
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("assignment_description"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'classesID',
                'label' => $this->lang->line("assignment_classes"),
                'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_classes',
            ),
            array(
                'field' => 'deadlinedate',
                'label' => $this->lang->line("assignment_deadlinedate"),
                'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid|callback_pastdate_check',
            ),
            array(
                'field' => 'subjectID',
                'label' => $this->lang->line("assignment_subject"),
                'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_subject',
            ),
            array(
                'field' => 'sectionID',
                'label' => $this->lang->line("assignment_section"),
                'rules' => 'xss_clean|callback_unique_section',
            ),
            array(
                'field' => 'photos[]',
                'label' => $this->lang->line("assignment_file"),
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
                'label' => $this->lang->line("assignment_file"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_multiplephotoupload'
            ),
            array(
                'field' => 'content',
                'label' => $this->lang->line("assignment_content"),
                'rules' => 'trim|xss_clean',
            )
        );
        return $rules;
    }
    protected function rules_content()
    {
        $rules = array(
            array(
                'field' => 'content',
                'label' => $this->lang->line("assignment_content"),
                'rules' => 'trim|xss_clean|required',
            ),
        );
        return $rules;
    }

    /**
     * List Assignment
     */
    public function index_get($id = null)
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $loginuserID = $this->session->userdata('loginuserID');
        if ($this->session->userdata('usertypeID') == 2) {
            $this->retdata['classesID'] = 0;
            $this->retdata['teacher'] = $this->teacher_m->general_get_teacher($this->session->userdata('loginuserID'));
            $teacher_subjects_id = pluck($this->subjectteacher_m->get_order_by_subjectteacher(['teacherID' =>  $this->session->userdata('loginuserID')]), 'subjectID');

            $assignments =  $this->assignment_m->get_assignment_from_subject($schoolyearID, $teacher_subjects_id);
            if (customCompute($assignments)) {
                foreach ($assignments as $key => $assignment) {
                    $assignmentMedias = $this->assignment_media_m->get_order_by_assignment_media(['assignmentID' => $assignment->assignmentID]);
                    if (customCompute($assignmentMedias)) {
                        $assignments[$key]->files = $assignmentMedias;
                    } else {
                        $assignments[$key]->files = [];
                    }
                    $assignments[$key]->submitStudentAssignment = count($this->assignmentanswer_m->join_get_assignmentanswer($assignment->assignmentID, $schoolyearID, ''));
                    $assignments[$key]->totalStudentAssignment= $this->student_m->get_student_feed(array('classesID' => $assignment->classesID, 'schoolyearID' => $schoolyearID), TRUE);
                   
                }
            }
            $this->retdata['assignments'] = $assignments;
          
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
                    $assignments =  $this->assignment_m->join_get_assignment($id, $schoolyearID);
                    if (customCompute($assignments)) {
                        foreach ($assignments as $key => $assignment) {
                            $assignmentMedias = $this->assignment_media_m->get_order_by_assignment_media(['assignmentID' => $assignment->assignmentID]);
                            if (customCompute($assignmentMedias)) {
                                $assignments[$key]->files = $assignmentMedias;
                            } else {
                                $assignments[$key]->files = [];
                            }

                            if ($this->session->userdata('usertypeID') == 3) {
                                $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('uploaderID' => $this->session->userdata('loginuserID'), 'uploadertypeID' => $this->session->userdata('usertypeID'), 'schoolyearID' => $schoolyearID, 'assignmentID' => $assignment->assignmentID));
                            } elseif ($this->session->userdata('usertypeID') == 4) {
                                $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('uploaderID' => $student->studentID, 'uploadertypeID' => 3, 'schoolyearID' => $schoolyearID, 'assignmentID' => $assignment->assignmentID));
                            } else {
                                $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('assignmentID' => $assignment->assignmentID));
                            }

                            $assign_ans_status = $assignmentanswer ? $assignmentanswer->status : '';
                            // if ($assign_ans_status == "pending") {
                            //     $assign_status_title = 'submitted';
                            // } elseif ($assign_ans_status == "checked") {
                            //     $assign_status_title = 'checked';
                            // } elseif ($assign_ans_status == "viewed") {
                            //     $assign_status_title = 'viewed';
                            // } else {
                            //     $assign_status_title = 'pending';
                            // }

                            $assignments[$key]->answerstatus = $assign_ans_status;
                        }
                    }

                    $this->retdata['assignments'] = $assignments;
                } else {
                    $this->retdata['classesID'] = 0;
                    $this->retdata['assignments'] = [];
                }
            } else {
                $this->retdata['classesID'] = 0;
                $this->retdata['assignments'] = [];
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
                $this->assignment_m->insert_assignment($array);
                $assignmentID = $this->db->insert_id();

                if ($assignmentID) {
                    $photos = $this->upload_data['files'];
                    if (customCompute($photos)) {
                        foreach ($photos as $key => $photo) {
                            $photos[$key]['assignmentID'] = $assignmentID;
                        }

                        $this->assignment_media_m->insert_batch_assignment_media($photos);
                    }
                    $this->response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => ['assignmentID' => $assignmentID],
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

    public function edit_form_get($assignmentID = '')
    {

        if (!$assignmentID) {
            $this->response([
                'status' => false,
                'message' => 'Assignment id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $assignment = $this->assignment_m->get_single_assignment(['assignmentID' => $assignmentID]);
        if (!$assignment) {
            $this->response([
                'status' => false,
                'message' => 'Assignment not found.',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $this->retdata['assignment'] = $assignment;

        $course   = $this->courses_m->get_all_join_courses_based_on_course_id($assignment->course_id);
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

    public function edit_post($assignmentID = '')
    {
        if (!$assignmentID) {
            $this->response([
                'status'  => false,
                'message' => 'Assignment id is empty',
                'data'    => [],
            ], REST_Controller::HTTP_OK);
        }

        $assignment = $this->assignment_m->get_single_assignment(['assignmentID' => $assignmentID]);
        if (!$assignment) {
            $this->response([
                'status'  => false,
                'message' => 'Assignment not found.',
                'data'    => [],
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
                $this->assignment_m->update_assignment($array, $assignmentID);

                $photos = $this->upload_data['files'];
                if (customCompute($photos)) {
                    foreach ($photos as $key => $photo) {
                        $photos[$key]['assignmentID'] = $assignmentID;
                    }

                    $this->assignment_media_m->insert_batch_assignment_media($photos);
                }

                $this->db->trans_complete();
                if ($this->db->trans_status() == TRUE) {

                    $assignmentMedias = $this->assignment_media_m->get_order_by_assignment_media(['assignmentID' => $assignment->assignmentID]);

                    $this->response([
                        'status'  => true,
                        'message' => 'Success',
                        'data'   => $assignmentMedias
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

    /**
     * List of assignment answer by assignmentID and classesID
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
        // $usertypeID = '';
        if ((int)$id && (int)($url)) {
            $this->retdata['classesID'] = $url;
            $fetchClasses = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
            if (isset($fetchClasses[$url])) {
                $assignment = $this->assignment_m->get_single_assignment(array('assignmentID' => $id, 'classesID' => $url, 'schoolyearID' => $schoolyearID));
                if (customCompute($assignment)) {
                    $assignmentanswers = $this->assignmentanswer_m->join_get_assignmentanswer($id, $schoolyearID, $studentID);
                    if (customCompute($assignmentanswers)) {
                        foreach ($assignmentanswers as $key => $assignmentanswer) {
                            $assignmentAnswerMedias = $this->assignment_answer_media_m->get_order_by_assignment_answer_media(['assignmentanswerID' => $assignmentanswer->assignmentanswerID]);
                            if (customCompute($assignmentAnswerMedias)) {
                                $assignmentanswers[$key]->files = $assignmentAnswerMedias;
                            } else {
                                $assignmentanswers[$key]->files = [];
                            }
                        }
                    }
                    $this->retdata['assignmentanswers'] = $assignmentanswers;
                } else {
                    $this->retdata['assignmentanswers'] = [];
                }
            } else {
                $this->retdata['assignmentanswers'] = [];
            }
        } else {
            $this->retdata['classesID'] = $url;
            $this->retdata['assignmentanswers'] = [];
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

        if ($this->assignment_media_m->delete_assignment_media($id)) {
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

    public function delete_assignment_get($assignmentID = '')
    {

        if (!$assignmentID) {
            $this->response([
                'status' => false,
                'message' => 'Id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
        $assignment = $this->assignment_m->get_single_assignment(array('assignmentID' => $assignmentID));
        if (customCompute($assignment)) {
            $this->assignment_m->delete_assignment($assignmentID);
            $assignmentMedias = $this->assignment_media_m->get_order_by_assignment_media(['assignmentID' => $assignmentID]);

            if (customCompute($assignmentMedias)) {
                foreach ($assignmentMedias as $assignmentMedia) {
                    if ($this->assignment_media_m->delete_assignment_media($assignmentMedia->id)) {
                        if ($assignmentMedia->attachment != '') {
                            if (file_exists(FCPATH . 'uploads/images/' . $assignmentMedia->attachment)) {
                                unlink(FCPATH . 'uploads/images/' . $assignmentMedia->attachment);
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
                'message' => 'Assignment not found.',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }
    }

    public function assignmentStatus_get($assignmentId = '')
    {

        if (!$assignmentId) {
            $this->response([
                'status' => false,
                'message' => 'Assignment id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $record = $this->assignment_m->get_single_assignment(['assignmentID' => $assignmentId]);
        if ($record) {
            if ($this->assignment_m->update_assignment(['is_published' => !$record->is_published], $assignmentId) && $record->is_published == 0) {
                $subject = $this->subject_m->general_get_single_subject(['subjectID' => $record->subjectID]);
                $sectionID = $record->sectionID ? json_decode($record->sectionID) : '';
                $title = 'Assignment Published';
                $notice = "Assignment " . $record->title . " for subject " . $subject->subject . " has been published";

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
                $msg = 'Assignment published';
            } else {
                $msg = 'Assignment unpublished';
            }
            $this->response([
                'status'    => true,
                'message'   => $msg
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Record not found for id ' . $assignmentId,
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
     * View Assignment
     */
    public function assignmentdetail_get($assignmentID = '')
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        if (!$assignmentID) {
            $this->response([
                'status'  => false,
                'message' => 'Assignment Id is empty',
                'data'    => [],
            ], REST_Controller::HTTP_OK);
        }

        $assignment = $this->assignment_m->get_single_assignment(array('assignmentID' => $assignmentID, 'schoolyearID' => $schoolyearID));

        if (!$assignment) {
            $this->response([
                'status'  => false,
                'message' => 'Assignment not found',
                'data'    => [],
            ], REST_Controller::HTTP_OK);
        }
        $this->retdata['assignment'] = $assignment;
        if ($assignment) {
            $this->retdata['assignment_medias'] = $this->assignment_media_m->get_order_by_assignment_media(['assignmentID' => $assignment->assignmentID]);
        } else {
            $this->retdata['assignment_medias'] = [];
        }

        $this->response([
            'status'  => true,
            'message' => 'Success',
            'data'    => $this->retdata,
        ], REST_Controller::HTTP_OK);
    }


    /**
     * Assignment answer details
     */
    public function assignmentanswer_get()
    {

        $usertypeID   = $this->session->userdata('usertypeID');
        $userID       = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $assignmentID = $this->input->get('assignmentID');
        $assignmentanswerID = $this->input->get('assignmentanswerID');
        $studentID = $this->input->get('studentID');

        if ($usertypeID == 2) {
            if (!$assignmentanswerID) {
                $this->response([
                    'status' => false,
                    'message' => 'Assignment answer Id is empty',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('assignmentanswerID' => $assignmentanswerID));
            if (!$assignmentanswer) {
                $this->response([
                    'status' => false,
                    'message' => 'Assignment answer not found',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $this->retdata['assignmentanswer'] = $assignmentanswer;
            if ($assignmentanswer) {
                $this->retdata['assignment_answer_medias'] = $this->assignment_answer_media_m->get_order_by_assignment_answer_media(['assignmentanswerID' => $assignmentanswer->assignmentanswerID]);
            } else {
                $this->retdata['assignment_answer_medias'] = [];
            }

            if ($assignmentanswer->status == 'pending') {

                $assignment = $this->assignment_m->get_single_assignment(array('assignmentID' => $assignmentanswer->assignmentID, 'schoolyearID' => $schoolyearID));

                $data = [
                    'status' => 'viewed'
                ];
                if ($this->assignmentanswer_m->update_assignmentanswer($data, $assignmentanswerID)) {
                    $userID = $assignmentanswer->uploaderID;
                    $usertypeID = $assignmentanswer->uploadertypeID;
                    $title = 'Assignment submission seen.';
                    $notice = 'Your assignment: ' . $assignment->title . ' submission has been seen';

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
            if (!$assignmentID) {
                $this->response([
                    'status' => false,
                    'message' => 'Assignment Id is empty',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('uploaderID' => $userID, 'uploadertypeID' => $usertypeID, 'schoolyearID' => $schoolyearID, 'assignmentID' => $assignmentID));
            if (!$assignmentanswer) {
                $this->response([
                    'status' => false,
                    'message' => 'Assignment answer not found',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $this->retdata['assignmentanswer'] = $assignmentanswer;
            if ($assignmentanswer) {
                $this->retdata['assignment_answer_medias'] = $this->assignment_answer_media_m->get_order_by_assignment_answer_media(['assignmentanswerID' => $assignmentanswer->assignmentanswerID]);
            } else {
                $this->retdata['assignment_answer_medias'] = [];
            }
        }

        if ($usertypeID == 4) {
            if ($assignmentID == '' || $studentID == '') {
                $this->response([
                    'status' => false,
                    'message' => 'Assignment Id or student id  is empty',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('uploaderID' => $studentID, 'uploadertypeID' => 3, 'schoolyearID' => $schoolyearID, 'assignmentID' => $assignmentID));
            if (!$assignmentanswer) {
                $this->response([
                    'status' => false,
                    'message' => 'Assignment answer not found',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            $this->retdata['assignmentanswer'] = $assignmentanswer;
            if ($assignmentanswer) {
                $this->retdata['assignment_answer_medias'] = $this->assignment_answer_media_m->get_order_by_assignment_answer_media(['assignmentanswerID' => $assignmentanswer->assignmentanswerID]);
            } else {
                $this->retdata['assignment_answer_medias'] = [];
            }
        }

        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $this->retdata,
        ], REST_Controller::HTTP_OK);
    }

    public function assignmentanswer_post($assignmentID = '')
    {

        $usertypeID   = $this->session->userdata('usertypeID');
        $userID       = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        // $student = $this->student_m->get_single_stud(['studentID' =>$userID]);
        $student    = $this->studentrelation_m->get_single_studentrelation(['srstudentID' => $userID, 'srschoolyearID' => $schoolyearID]);


        if (!$assignmentID) {
            $this->response([
                'status' => false,
                'message' => 'Assignment Id is empty',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        $assignment = $this->assignment_m->get_single_assignment(array('assignmentID' => $assignmentID, 'schoolyearID' => $schoolyearID));

        if (!$assignment) {
            $this->response([
                'status' => false,
                'message' => 'Assignment not found.',
                'data' => [],
            ], REST_Controller::HTTP_OK);
        }

        if (strtotime($assignment->deadlinedate) < strtotime(date('Y-m-d'))) {
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

                $array['content']            = $this->input->post('content');
                $array['answerfileoriginal'] = '';
                $array['answerfile']         = '';
                $array['assignmentID']       = $assignmentID;
                $array['schoolyearID']       = $this->data['siteinfos']->school_year;
                $array['uploaderID']         = $this->session->userdata('loginuserID');
                $array['uploadertypeID']     = $usertypeID;
                $array['answerdate']         = date('Y-m-d');
                $array['status']             = 'pending';

                $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('uploaderID' => $userID, 'uploadertypeID' => $usertypeID, 'schoolyearID' => $schoolyearID, 'assignmentID' => $assignmentID));

                if (customCompute($assignmentanswer)) {
                    $this->assignmentanswer_m->update_assignmentanswer($array, $assignmentanswer->assignmentanswerID);

                    $photos = $this->upload_data['files'];
                    if (customCompute($photos)) {
                        foreach ($photos as $key => $photo) {
                            $photos[$key]['assignmentanswerID'] = $assignmentanswer->assignmentanswerID;
                        }

                        $this->assignment_answer_media_m->insert_batch_assignment_answer_media($photos);
                    }

                    $this->response([
                        'status' => true,
                        'message' => 'Success',
                        'data' => [],
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->assignmentanswer_m->insert_assignmentanswer($array);
                    $assignmentanswerID = $this->db->insert_id();

                    if ($assignmentanswerID) {
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        $photos = $this->upload_data['files'];
                        if (customCompute($photos)) {
                            foreach ($photos as $key => $photo) {
                                $photos[$key]['assignmentanswerID'] = $assignmentanswerID;
                            }

                            $this->assignment_answer_media_m->insert_batch_assignment_answer_media($photos);
                        }

                        $userID = $assignment->userID;
                        $usertypeID = $assignment->usertypeID;
                        $title = 'Assignment submission';
                        $notice = 'Assignment: ' . $assignment->title . ' is submitted by ' . $student->srname;

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

        $delete = $this->assignment_answer_media_m->delete_assignment_answer_media($id);
        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => [],
        ], REST_Controller::HTTP_OK);
    }

    public function addremarks_post($assignmentanswerID = '')
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        if (!$assignmentanswerID) {
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
        if ($this->assignmentanswer_m->update_assignmentanswer($data, $assignmentanswerID)) {

            $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('assignmentanswerID' => $assignmentanswerID));
            $assignment = $this->assignment_m->get_single_assignment(['assignmentID' => $assignmentanswer->assignmentID]);

            $title = 'Assignment submission remark.';
            $notice = 'Remarks has been added on your assignment: ' . $assignment->title . ' by teacher.';
            $userID = $assignmentanswer->uploaderID;
            $usertypeID = $assignmentanswer->uploadertypeID;
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

    public function update_assignmentanswer_status_post()
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $ids = $this->input->post('ids');
        $idArray = explode(',', $ids);
        $u = [];
        foreach ($idArray as $id) {
            $data = [
                'status' => 'checked'
            ];
            if ($this->assignmentanswer_m->update_assignmentanswer($data, $id)) {
                $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('assignmentanswerID' => $id));
                $userID = $assignmentanswer->uploaderID;
                $usertypeID = $assignmentanswer->uploadertypeID;
                $u[] = $userID . $usertypeID;
            }
        }

        $assignment = $this->assignment_m->get_single_assignment(['assignmentID' => $assignmentanswer->assignmentID]);

        $title = 'Assignment submission checked.';
        $notice = 'Your assignment: ' . $assignment->title . ' has been checked';


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
                            iresizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height);
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

    public function mobPushNotification($array)
    {
        $this->mobile_job_m->insert_job([
            'name'        => 'sendCourseNotification',
            'payload'     => json_encode([
                'users'   => $array['users'],
                'title'   => $array['title'], // title is compulsary
                'message' => $array['notice']
            ]),
        ]);
    }

}
