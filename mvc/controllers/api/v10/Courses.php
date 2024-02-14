<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Courses extends Api_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model("job_m");
        $this->load->model("mobile_job_m");
        $this->load->model("classes_m");
        $this->load->model("subject_m");
        $this->load->model("section_m");
        $this->load->model("courses_m");
        $this->load->model("chapter_m");
        $this->load->model("question_bank_m");
        $this->load->model("question_group_m");
        $this->load->model("question_level_m");
        $this->load->model("question_type_m");
        $this->load->model("student_m");
        $this->load->model("coursequiz_m");
        $this->load->model("coursefiles_m");
        $this->load->model("courselink_m");
        $this->load->model("notice_m");
        $this->load->model("question_option_m");
        $this->load->model("question_answer_m");
        $this->load->model("online_exam_user_answer_option_m");
        $this->load->model("homework_media_m");
        $this->load->model("classwork_media_m");

        $this->load->model("coursecontent_m");
        $this->load->model("coursesstudent_progress_m");
        $this->load->model("unit_m");
        $this->load->model("assignment_m");
        $this->load->model("homework_m");
        $this->load->model("classwork_m");
        $this->load->model("pushsubscription_m");
        $this->load->model("subjectattendance_m");
        $this->load->model("studentrelation_m");
        $this->load->model("annual_plan_m");
        $this->load->model("annual_plan_media_m");
        $this->load->model("lesson_plan_m");
        $this->load->model("lesson_plan_version_m");
        $this->load->model("lesson_plan_media_m");
        $this->load->model("assignment_media_m");
        $this->load->model("daily_plan_m");
        $this->load->model("daily_plan_media_m");
    }  
    
    public function index_get(){

        $usertypeID = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');
        $this->retdata['classes'] = $this->classes_m->get_classes();

        if ($usertypeID == '1') {
            foreach ($this->retdata['classes'] as $index => $class) {
                $this->retdata['classes'][$index]->sections = $this->section_m->general_get_order_by_section([
                    'classesID' => $class->classesID,
                ]);
                $this->retdata['classes'][$index]->courses = $this->courses_m->get_join_courses($class->classesID);
                foreach ($this->retdata['classes'][$index]->courses as $courseIndex => $course) {
                    $this->retdata['classes'][$index]->courses[$courseIndex]->units = $this->unit_m->get_units_count_by_subject_id($course->subject_id)->count;
                    $this->retdata['classes'][$index]->courses[$courseIndex]->chapters = $this->chapter_m->get_chapters_count_by_subject_id($course->subject_id)->count;
                }
            }
        } elseif ($usertypeID == '2') {
            foreach ($this->retdata['classes'] as $index => $class) {
                $this->retdata['classes'][$index]->sections = $this->section_m->general_get_order_by_section([
                    'classesID' => $class->classesID,
                ]);
                $this->retdata['classes'][$index]->courses = $this->courses_m->get_join_courses_based_on_teacher_id($class->classesID, $loginuserID);
                foreach ($this->retdata['classes'][$index]->courses as $courseIndex => $course) {
                    $this->retdata['classes'][$index]->courses[$courseIndex]->units = $this->unit_m->get_units_count_by_subject_id($course->subject_id)->count;
                    $this->retdata['classes'][$index]->courses[$courseIndex]->chapters = $this->chapter_m->get_chapters_count_by_subject_id($course->subject_id)->count;
                }
            }
        } elseif ($usertypeID == '3') {
            $this->retdata['student'] = $this->student_m->get_single_student(array('studentID' => $loginuserID));
            $this->retdata['classes'] = $this->classes_m->general_get_order_by_classes(['classesID' => $this->retdata['student']->classesID]);
            if (customCompute($this->retdata['student'])) {
                foreach ($this->retdata['classes'] as $index => $class) {
                    $this->retdata['classes'][$index]->courses = $this->courses_m->get_join_courses_based_on_class_id($class->classesID);
                    foreach ($this->retdata['classes'][$index]->courses as $courseIndex => $course) {
                        $this->retdata['classes'][$index]->courses[$courseIndex]->units = $this->unit_m->get_units_count_by_subject_id($course->subject_id)->count;
                        $this->retdata['classes'][$index]->courses[$courseIndex]->chapters = $this->chapter_m->get_chapters_count_by_subject_id($course->subject_id)->count;
                    }
                }
            }
        } elseif ($usertypeID == '4') {
            $this->retdata['students'] = $this->student_m->general_get_order_by_student(array('parentID' => $loginuserID));
            if (customCompute($this->retdata['students'])) {
                foreach ($this->retdata['students'] as $i => $child) {
                    $this->retdata['students'][$i]->courses = $this->courses_m->get_join_courses_based_on_class_id($child->classesID);
                    foreach ($this->retdata['students'][$i]->courses as $index => $course) {
                        $this->retdata['students'][$i]->courses[$index]->units = $this->unit_m->get_units_count_by_subject_id($course->subject_id)->count;
                        $this->retdata['students'][$i]->courses[$index]->chapters = $this->chapter_m->get_chapters_count_by_subject_id($course->subject_id)->count;
                    }
                }
            }
        }

        $this->response([
			'status'    => true,
			'message'   => 'Success',
			'data'      => $this->retdata,
			
  		], REST_Controller::HTTP_OK);

    }

    public function show_get($id = '')
    {

        if(!$id){
            $this->response([
                'status'    => false,
                'message'   => 'Course id empty.',
                'data'      => $this->retdata,
                
              ], REST_Controller::HTTP_OK);
        }
        

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $usertypeID = $this->session->userdata('usertypeID');
        $this->retdata['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($id);
        $this->retdata['units'] = $this->courses_m->get_course_unit_by_course($id);
        // $this->retdata['annual_id'] = $this->annual_plan_m->get_single_annual_plan(["course_id" => $id]);
        // $this->retdata['unit_count'] = count($this->retdata['units']);
        $this->retdata['chapters'] = [];
        $this->retdata['chapter_ids'] = [];
        $classesID = $this->retdata['course']->class_id;
        $this->retdata['set'] = $classesID;
        $this->retdata['classes'] = $this->classes_m->general_get_single_classes(['classesID' => $this->retdata['course']->class_id]);
        $this->retdata['subjects'] = $this->subject_m->general_get_single_subject(['subjectID' => $this->retdata['course']->subject_id]);
       
        foreach ($this->retdata['units'] as $index => $unit) {
            $this->retdata['units'][$index]->chapters = $this->chapter_m->get_chapter_from_unit_id($unit->id);
            $this->retdata['units'][$index]->chapter_count = count($this->retdata['units'][$index]->chapters);
            foreach ($this->retdata['units'][$index]->chapters as $x => $chapter) {

                $this->retdata['units'][$index]->chapters[$x]->contents = $this->courses_m->get_content($chapter->id);
                $this->retdata['units'][$index]->chapters[$x]->attachments = $this->courses_m->get_attachment($chapter->id);
                $this->retdata['units'][$index]->chapters[$x]->links = $this->courses_m->get_link($chapter->id);
                $this->retdata['units'][$index]->chapters[$x]->quizzes = $this->courses_m->get_quizzes($chapter->id);
                $this->retdata['units'][$index]->chapters[$x]->assignments =
                    $this->assignment_m->get_order_by_assignment([
                        'unit_id' => $unit->id,
                        'chapter_id' => $chapter->id,
                        'schoolyearID' => $schoolyearID,
                    ]);
                $this->retdata['units'][$index]->chapters[$x]->homeworks =
                    $this->homework_m->get_order_by_homework([
                        'unit_id' => $unit->id,
                        'chapter_id' => $chapter->id,
                        'schoolyearID' => $schoolyearID,
                    ]);
                $this->retdata['units'][$index]->chapters[$x]->classworks =
                    $this->classwork_m->get_order_by_classwork([
                        'unit_id' => $unit->id,
                        'chapter_id' => $chapter->id,
                        'schoolyearID' => $schoolyearID,
                    ]);

                array_push($this->retdata['chapter_ids'], $chapter->id);
            }
        }

        foreach ($this->retdata['units'] as $index => $unit) {
            if ($unit->chapters) {
                foreach ($unit->chapters as $x => $chapter) {
                    $lists = [];

                    if ($chapter->classworks) {
                        if (is_array($chapter->classworks)) {
                            foreach ($chapter->classworks as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->classworks);
                        }
                    }
                    if ($chapter->homeworks) {
                        if (is_array($chapter->homeworks)) {
                            foreach ($chapter->homeworks as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->homeworks);
                        }
                    }
                    if ($chapter->assignments) {
                        if (is_array($chapter->assignments)) {
                            foreach ($chapter->assignments as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->assignments);
                        }
                    }
                    if ($chapter->contents) {
                        if (is_array($chapter->contents)) {
                            foreach ($chapter->contents as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->contents);
                        }
                    }
                    if ($chapter->attachments) {
                        if (is_array($chapter->attachments)) {
                            foreach ($chapter->attachments as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->attachments);
                        }
                    }
                    if ($chapter->quizzes) {
                        if (is_array($chapter->quizzes)) {
                            foreach ($chapter->quizzes as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->quizzes);
                        }
                    }
                    if ($chapter->links) {
                        if (is_array($chapter->links)) {
                            foreach ($chapter->links as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->links);
                        }
                    }
                    usort($lists, function ($a, $b) {
                        if (isset($a->order) && isset($b->order)) {
                            return $a->order > $b->order;
                        }
                    });
                    $this->retdata['units'][$index]->chapters[$x]->lists = $lists;
                }
            }
        }

        $this->response([
			'status'    => true,
			'message'   => 'Success',
			'data'      => $this->retdata,
			
  		], REST_Controller::HTTP_OK);

       
    }

    public function units_get($subject_id = ''){
        
            if(!$subject_id){
                $this->response([
                    'status'    => false,
                    'message'   => 'subject id empty.',
                    'data'      => [],
                    
                  ], REST_Controller::HTTP_BAD_REQUEST);
            }

            $units = $this->unit_m->get_units_by_subject_id_api($subject_id);

            foreach($units as $key=>$unit){
                  $unit->chapters = $this->chapter_m->get_chapters_from_unit_api($unit->id);
            }

            $this->retdata['units'] = $units;

            $this->response([
                'status'    => true,
                'message'   => 'Success',
                'data'      => $this->retdata,
                
            ], REST_Controller::HTTP_OK);

    }

}