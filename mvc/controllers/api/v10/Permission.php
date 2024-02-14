<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
    }

    public function index_get() 
    {
        $usertypeID = $this->session->userdata('usertypeID');
        $permissions   = $this->permission_m->get_modules_with_permission($usertypeID);

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $permissions
        ], REST_Controller::HTTP_OK);
    }
}
