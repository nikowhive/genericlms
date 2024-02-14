<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Transport extends Api_Controller 
{

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('transport_m');
        $this->load->model('tmember_m');
    }

    public function index_get($id=null) 
    {
        if($id) {
            $this->retdata['transports'] = $this->transport_m->get_single_transport(['transportID' => $id]);
            $this->retdata['students'] = $this->tmember_m->get_order_by_tmember(['transportID' => $id]);
        } else {
            $this->retdata['transports'] = $this->transport_m->get_order_by_transport();
            $this->retdata['students'] = [];
        }

        
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
