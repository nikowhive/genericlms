<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Schoolinformation extends REST_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("schoolinformation_m");
    }

    public function index_get() 
    {
        $school_information      = $this->schoolinformation_m->get_schoolinformation();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $school_information
        ], REST_Controller::HTTP_OK);
    }
}
