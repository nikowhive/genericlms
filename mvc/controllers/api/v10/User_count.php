<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class User_count extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("systemadmin_m");
        $this->load->model("event_m");
        $this->load->model("eventcounter_m");
        $this->load->model("pushsubscription_m");
        $this->load->model("usertype_m");
        $this->load->model("student_m");
        $this->load->model("studentrelation_m");
        $this->load->model('parents_m');
        $this->load->model('teacher_m');
        $this->load->model('fcmtoken_m');
        $this->load->model('user_m');
        $this->load->model('systemadmin_m');
        $language = $this->session->userdata('lang');
        $this->lang->load('event', $language);
    }

    public function index_get()
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $loginuserID  = $this->session->userdata('loginuserID');

        $students    = $this->studentrelation_m->get_order_by_student(['srschoolyearID' => $schoolyearID]);
        $teachers    = $this->teacher_m->get_teacher();
        $parents     = $this->parents_m->get_parents();
        $sadmin    = $this->systemadmin_m->get_systemadmin();
        $users = $this->user_m->get_user();


        $this->retdata['students'] = count($students);
        $this->retdata['teachers'] = count($teachers);
        $this->retdata['parents'] = count($parents);
        $this->retdata['sadmin'] = count($sadmin);
        $this->retdata['users'] = count($users);;

        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $this->retdata,
        ], REST_Controller::HTTP_OK);
    }
}