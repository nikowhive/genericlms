<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classgroup extends Admin_Controller {
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
    function __construct() {
        parent::__construct();
        $this->load->model("classgroup_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('classgroup', $language);
    }

    public function index() {
        $this->data['classgroups'] = $this->classgroup_m->get_order_by_classgroup();
        $this->data["subview"] = "/classgroup/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules() {
        $rules = array(
            array(
                'field' => 'group',
                'label' => $this->lang->line("classgroup_group"),
                'rules' => 'trim|required|xss_clean|max_length[50]|callback_unique_group'
            ),
            array(
                'field' => 'photo',
                'label' => $this->lang->line("classgroup_photo"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
            )
        );
        return $rules;
    }

    public function add() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = validation_errors();
                $this->data["subview"] = "classgroup/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = array(
                    "group"      => $this->input->post("group"),
                    "published"  => $this->input->post("published") ? $this->input->post("published") : 2,
                );
                if ($_FILES['photo']['name'] != '') {
                    $array["photo"] = $this->upload_data['file']['file_name'];
                }
                
                $this->classgroup_m->insert_classgroup($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("classgroup/index"));
            }
        } else {
            $this->data["subview"] = "/classgroup/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js'
            )
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['classgroup'] = $this->classgroup_m->get_single_classgroup(array('classgroupID' => $id));
            if($this->data['classgroup']) {
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "/classgroup/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "group" => $this->input->post("group"),
                            "published"  => $this->input->post("published") ? $this->input->post("published") : 2
                        );
                        if ($_FILES['photo']['name'] != '') {
                            $array["photo"] = $this->upload_data['file']['file_name'];
                        }

                        $this->classgroup_m->update_classgroup($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("classgroup/index"));
                    }
                } else {
                    $this->data["subview"] = "/classgroup/edit";
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function delete() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->data['classgroup'] = $this->classgroup_m->get_single_classgroup(array('classgroupID' => $id));
            if($this->data['classgroup']) {
                $this->classgroup_m->delete_classgroup($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("classgroup/index"));
            } else {
                redirect(base_url("classgroup/index"));
            }
        } else {
            redirect(base_url("classgroup/index"));
        }
    }

    public function unique_group() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $group = $this->classgroup_m->get_order_by_classgroup(array("group" => $this->input->post("group"), "classgroupID !=" => $id));
            if(customCompute($group)) {
                $this->form_validation->set_message("unique_group", "%s already exists");
                return FALSE;
            }
            return TRUE;
        } else {
            $group = $this->classgroup_m->get_order_by_classgroup(array("group" => $this->input->post("group")));

            if(customCompute($group)) {
                $this->form_validation->set_message("unique_group", "%s already exists");
                return FALSE;
            }
            return TRUE;
        }   
    }

    public function photoupload()
    {
        $id   = htmlentities(escapeString($this->uri->segment(3)));
        $user = [];
        if ( (int) $id ) {
            $user = $this->classgroup_m->get_single_classgroup([ 'classgroupID' => $id ]);
        }
        $new_file = "";
        if ( $_FILES["photo"]['name'] != "" ) {
            $file_name        = $_FILES["photo"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512',
                $random . $this->input->post('username') . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if ( customCompute($explode) >= 2 ) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png|jpeg";
                $config['file_name']     = $new_file;
                // $config['max_size']      = '5120';
                // $config['max_width']     = '3000';
                // $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("photo") ) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['file'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return false;
            }
        } else {
            if ( customCompute($user) ) {
                $this->upload_data['file'] = [ 'file_name' => $user->photo ];
                return true;
            } else {
                $this->upload_data['file'] = [ 'file_name' => $new_file ];
                return true;
            }
        }
    }
}
