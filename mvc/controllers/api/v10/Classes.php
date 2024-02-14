<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Classes extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('teacher_m');
        $this->load->model('subject_m');
        $this->load->model('student_m');
        $this->load->model('studentrelation_m');
        $this->load->model('courses_m');
    }

    public function index_get()
    {
        $schoolyearID = $this->session->userdata("defaultschoolyearID");
        $this->retdata['teachers'] = pluck($this->teacher_m->get_teacher(), 'name', 'teacherID');
        $this->retdata['classes']  = $this->classes_m->general_get_order_by_classes();

        foreach ($this->retdata['classes'] as $index => $class) {
            $student_count = count($this->student_m->get_students($class->classesID));
            $this->retdata['classes'][$index]->student_count = $student_count;
        }
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function teacher_specific_get()
    {
        if ($this->session->userdata("usertypeID") == 1) {
            $this->retdata['classes'] = $this->classes_m->general_get_order_by_classes();
            $this->retdata['sections'] = $this->section_m->general_get_order_by_section();
            $this->retdata['subjects']= $this->subject_m->get_subject();
            $this->retdata['courses'] = $this->courses_m->get_order_by_courses(); 
            $this->response([
                'status'    => true,
                'message'   => 'Success',
                'data'      => $this->retdata
            ], REST_Controller::HTTP_OK);
        } else if ($this->session->userdata("usertypeID") == 2) {
            // $class_teachers = $this->classes_m->general_get_order_by_classes(['teacherID' => $this->session->userdata("loginuserID")]);
            // $section_teachers = $this->section_m->general_get_order_by_section(['teacherID' => $this->session->userdata("loginuserID")]);
            // $classes = $this->classes_m->general_get_order_by_classes();
            // $sections = $this->section_m->general_get_order_by_section();

            // $this->retdata['classes'] = [];
            // $this->retdata['sections'] = [];
            // foreach($classes as $class) {
            //     foreach($section_teachers as $section) {
            //         if($class->classesID == $section->classesID) {
            //             array_push($this->retdata['classes'], $class);
            //             array_push($this->retdata['sections'], $section);
            //         }
            //     }
            // }

            // foreach($class_teachers as $teacher_class) {
            //     array_push($this->retdata['classes'], $teacher_class);
            // }

            // foreach($sections as $section) {
            //     foreach($class_teachers as $teacher_class) {
            //         if($teacher_class->classesID == $section->classesID) {
            //             array_push($this->retdata['sections'], $section);
            //         }
            //     }

            // }

            // $this->retdata['classes'] = array_values(array_unique($this->retdata['classes'], SORT_REGULAR));
            // $this->retdata['sections'] = array_values(array_unique($this->retdata['sections'], SORT_REGULAR));

            $classes = $this->classes_m->get_classes();
            $subjects = $this->subject_m->get_subject();
            $sections = $this->section_m->get_order_by_section();
            $section_ids = pluck($sections, 'sectionID');

            foreach ($classes as $index => $class) {
                $courses[] = $this->courses_m->get_join_courses_based_on_teacher_id($class->classesID, $this->session->userdata('loginuserID'));
            }

            $courses = array_merge(...$courses);
           
            $this->retdata['classes'] = $classes;
            $this->retdata['sections'] = $sections;
            $this->retdata['subjects'] = $subjects;
            $this->retdata['courses'] = $courses;
            $this->retdata['students'] = $this->student_m->get_students_from_section_id($section_ids);
            

            $this->response([
                'status'    => true,
                'message'   => 'Success',
                'data'      => $this->retdata
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Wrong usertype',
                'data'      => []
            ], REST_Controller::HTTP_OK);
        }
    }
}
