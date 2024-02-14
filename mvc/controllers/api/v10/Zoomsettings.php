<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Zoomsettings extends Api_Controller {
    /*
    | -----------------------------------------------------
    | PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
    | -----------------------------------------------------
    | AUTHOR:			INILABS TEAM
    | -----------------------------------------------------
    | EMAIL:			info@inilabs.net
    | -----------------------------------------------------
    | COPYRIGHT:		RESERVED BY INILABS IT
    | -----------------------------------------------------
    | WEBSITE:			http://inilabs.net
    | -----------------------------------------------------
    */
    public function __construct() 
    {
        parent::__construct();
        $this->load->library('zoom');
        $this->load->model("zoomsettings_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('zoomsettings', $language);
    }

    protected function rules() {
        $rules = [
            [
                'field' => 'client_id',
                'label' => $this->lang->line("zoomsettings_client_id"),
                'rules' => 'trim|xss_clean|max_length[255]'
            ],
            [
                'field' => 'client_secret',
                'label' => $this->lang->line("zoomsettings_client_secret"),
                'rules' => 'trim|xss_clean|max_length[255]'
            ],
            [
                'field' => 'api_key',
                'label' => $this->lang->line("zoomsettings_api_key"),
                'rules' => 'trim|xss_clean|max_length[255]'
            ],
            [
                'field' => 'api_secret',
                'label' => $this->lang->line("zoomsettings_api_secret"),
                'rules' => 'trim|xss_clean|max_length[255]'
            ],
        ];
        return $rules;
    }

    public function index_get() {

        $this->retdata['activationLink'] = '';
        $this->retdata['zoomsetting'] = $this->zoomsettings_m->get_zoomsettings(1);
        if($this->retdata['zoomsetting']){
            $this->retdata['activationLink'] = $this->zoom->auth($this->retdata['zoomsetting']->client_id, base_url('zoomsettings/authorize'));
        }
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function update_post() {

        $this->retdata['zoomsetting'] = $this->zoomsettings_m->get_zoomsettings(1);

        if($this->retdata['zoomsetting']) {
            if($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->response([
                        'status' => false,
                        'message' => 'validation error',
                        'data' => []
                    ], REST_Controller::HTTP_NOT_FOUND);
                } else {
                    $array = array(
                        'client_id'     => $this->input->post('client_id'),
                        'client_secret' => $this->input->post('client_secret'),
                        'api_key'       => $this->input->post('api_key'),
                        'api_secret'    => $this->input->post('api_secret')
                    );

                    $this->zoomsettings_m->update_zoomsettings($array, 1);
                    $this->response([
                        'status'    => true,
                        'message'   => 'Success',
                        'data'      => $this->retdata
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Error 404',
                    'data' => []
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error 404',
                'data' => []
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function authorize()
    {
        $code = $this->input->get('code');
        if(!empty($code)) {
            $zoom = $this->zoomsettings_m->get_zoomsettings(1);
            $response = $this->zoom->token(
                $zoom->client_id, 
                $zoom->client_secret, 
                $code, 
                base_url('zoomsettings/authorize')
            );
            if(isset($response->status) && $response->status) {
                $this->response([
                    'status'    => true,
                    'message'   => 'Success',
                ], REST_Controller::HTTP_OK);
            } else {
               
                $this->response([
                    'status' => false,
                    'message' => $response->message,
                    'data' => []
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error',
            ], REST_Controller::HTTP_NOT_FOUND);
         }
       
    }
}
