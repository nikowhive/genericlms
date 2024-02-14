<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zoomsettings extends Admin_Controller {
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

    public function index() {
        $this->data['headerassets'] = [
            'css' => ['assets/settings/css/settings.css']
        ];

        $this->data['zoomsetting'] = $this->zoomsettings_m->get_zoomsettings(1);

        if($this->data['zoomsetting']) {
            $this->data['activationLink'] = $this->zoom->auth($this->data['zoomsetting']->client_id, base_url('zoomsettings/authorize'));
            if($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "zoomsettings/index";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $array = array(
                        'client_id'     => $this->input->post('client_id'),
                        'client_secret' => $this->input->post('client_secret'),
                        'api_key'       => $this->input->post('api_key'),
                        'api_secret'    => $this->input->post('api_secret')
                    );

                    $this->zoomsettings_m->update_zoomsettings($array, 1);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("zoomsettings/index"));
                }
            } else {
                $this->data["subview"] = "zoomsettings/index";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
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
                $this->zoomsettings_m->update_zoomsettings(['data' => json_encode($response->data)], 1);
                $this->session->set_flashdata('success', $this->lang->line('zoomsettings_client_Success'));
                
            } else {
                $this->session->set_flashdata('error', $response->message);
                redirect(base_url("zoomsettings/index"));
            }
        } else {
            $this->session->set_flashdata('error', $this->lang->line('zoomsettings_error'));
        }
        redirect(base_url("zoomsettings/index"));
    }
}
