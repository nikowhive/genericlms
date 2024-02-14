<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Section extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->model('teacher_m');
        $this->load->model('section_m');
        $this->load->model('student_m');
        $this->load->model('studentrelation_m');
    }

    public function index_get($id = null)
    {
        if ($this->session->userdata('usertypeID') == 3) {
            $id = $this->data['myclass'];
        }

        if ((int)$id) {
            $schoolyearID = $this->session->userdata("defaultschoolyearID");
            $this->retdata['classesID']     = $id;
            $this->retdata['classes']       = $this->classes_m->general_get_order_by_classes();

            foreach($this->retdata['classes'] as $index => $class) {
                $student_count = count($this->student_m->get_students($class->classesID));
                $this->retdata['classes'][$index]->student_count = $student_count;
            }
            
            $fetchClass = pluck($this->retdata['classes'], 'classesID', 'classesID');
            if (isset($fetchClass[$id])) {
                $this->retdata['teachers'] = pluck($this->teacher_m->general_get_teacher(), 'name', 'teacherID');
                $this->retdata['sections'] = $this->section_m->general_get_order_by_section(array('classesID' => $id));

                foreach($this->retdata['sections'] as $index => $section) {
                    $student_count = count($this->student_m->get_students_from_section_id($section->sectionID));
                    $this->retdata['sections'][$index]->student_count = $student_count;
                }
            } else {
                $this->retdata['teacher']  = [];
                $this->retdata['sections'] = [];
                $this->retdata['students'] = [];
            }
        } else {
            $this->retdata['classesID'] = 0;
            $this->retdata['classes']   = $this->classes_m->get_classes();
            $this->retdata['sections']  = [];
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
