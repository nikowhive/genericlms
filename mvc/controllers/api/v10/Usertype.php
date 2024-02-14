<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class usertype extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('user_m');
        $this->load->model('teacher_m');
        $this->load->model('systemadmin_m');
        $this->load->model('usertype_m');
    }

    public function index_get() 
    {
        $this->retdata['usertypes'] = $this->usertype_m->get_order_by_usertype('usertypeID != 3 and usertypeID != 4');
        foreach($this->retdata['usertypes'] as $index =>$usertype) {
            if($usertype->usertypeID == 1) {
                $this->retdata['usertypes'][$index]->user_count = $this->systemadmin_m->systemadmin_count()->count;
            } else if ($usertype->usertypeID == 2) {
                $this->retdata['usertypes'][$index]->user_count = $this->teacher_m->teacher_count()->count;
            } else {
                $this->retdata['usertypes'][$index]->user_count = $this->user_m->user_count($usertype->usertypeID)->count;
            }
        }
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
