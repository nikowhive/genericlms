<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Schoolinformation extends Admin_Controller {
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
        $this->load->model("schoolinformation_m");
        $this->load->library('updatechecker');
        $language = $this->session->userdata('lang');
        $this->lang->load('schoolinformation', $language);
    }

    protected function rules()
    {
        $rules = [
            [
                'field' => 'principal_image',
                'label' => $this->lang->line("schoolinformation_principal_image"),
                'rules' => 'trim|xss_clean|callback_principalphotoupload'
            ],
            [
                'field' => 'principal_message',
                'label' => $this->lang->line("schoolinformation_principal_message"),
                'rules' => 'trim|required|xss_clean'
            ],
            [
                'field' => 'principal_name',
                'label' => $this->lang->line("schoolinformation_principal_name"),
                'rules' => 'trim|required|xss_clean'
            ],
            [
                'field' => 'school_address',
                'label' => $this->lang->line("schoolinformation_school_address"),
                'rules' => 'trim|required|xss_clean'
            ],
            [
                'field' => 'school_banner',
                'label' => $this->lang->line("schoolinformation_school_banner"),
                'rules' => 'trim|xss_clean|callback_bannerphotoupload'
            ],
            [
                'field' => 'school_description',
                'label' => $this->lang->line("schoolinformation_school_description"),
                'rules' => 'trim|required|xss_clean'
            ],
            [
                'field' => 'school_email',
                'label' => $this->lang->line("schoolinformation_school_email"),
                'rules' => 'trim|required|xss_clean'
            ],
            [
                'field' => 'school_logo',
                'label' => $this->lang->line("schoolinformation_school_logo"),
                'rules' => 'trim|xss_clean|callback_logophotoupload'
            ],
            [
                'field' => 'school_name',
                'label' => $this->lang->line("schoolinformation_school_name"),
                'rules' => 'trim|required|xss_clean'
            ],
            [
                'field' => 'school_phone',
                'label' => $this->lang->line("schoolinformation_school_phone"),
                'rules' => 'trim|required|xss_clean'
            ],
        ];

        return $rules;
    }

    public function index()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ],
            'js'  => [
                'assets/select2/select2.js'
            ]
        ];

        $this->data['schoolinformation']      = $this->schoolinformation_m->get_schoolinformation();

        if ( $this->data['schoolinformation'] ) {
            if ( $_POST ) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $this->data["subview"] = "schoolinformation/index";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    // if ( config_item('demo') == false ) {
                    //     $updateValidation = $this->updatechecker->verifyValidUser();
                    //     if ($updateValidation->status  == false ) {
                    //         $this->session->set_flashdata('error', $updateValidation->message);
                    //         redirect(base_url('schoolinformation/index'));
                    //     }
                    // }

                    $array = [];
                    for ( $i = 0; $i < customCompute($rules); $i++ ) {
                        if ( $this->input->post($rules[ $i ]['field']) == false ) {
                            $array[ $rules[ $i ]['field'] ] = 0;
                        } else {
                            $array[ $rules[ $i ]['field'] ] = $this->input->post($rules[ $i ]['field']);
                        }
                    }
                    $array['liaison_photo']            = $this->upload_data['liaison_photo']['file_name'];
                    $array['school_logo']              = $this->upload_data['school_logo']['file_name'];
                    $array['school_banner']            = $this->upload_data['school_banner']['file_name'];
                    $array['principal_image']          = $this->upload_data['principal_image']['file_name'];

                    $this->schoolinformation_m->insertorupdate($array);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("schoolinformation/index"));
                }
            } else {
                $this->data["subview"] = "schoolinformation/index";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function liaisonphotoupload()
    {
        $schoolinformation  = $this->schoolinformation_m->get_schoolinformation();
        $new_file = "site.png";
        if ( $_FILES["liaison_photo"]['name'] != "" ) {
            $file_name        = $_FILES["liaison_photo"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512', $random . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if ( customCompute($explode) >= 2 ) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name']     = $new_file;
                $_FILES['attach']['tmp_name'] = $_FILES['liaison_photo']['tmp_name'];
                $image_info = getimagesize($_FILES['liaison_photo']['tmp_name']);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
                // $config['max_size']      = '5120';
                // $config['max_width']     = '3000';
                // $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("liaison_photo") ) {
                    $this->form_validation->set_message("liaison_photo", $this->upload->display_errors());
                    return false;
                } else {
                    $fileData = $this->upload->data();
                    if($image_width > 1800 || $image_height > 1800){
                        resizeImage($fileData['file_name'],$config['upload_path']);
                     }
                    $this->upload_data['liaison_photo'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("liaison_photo", "Invalid file");
                return false;
            }
        } else {
            if ( customCompute($schoolinformation) ) {
                $this->upload_data['liaison_photo'] = [ 'file_name' => $schoolinformation->liaison_photo ];
                return true;
            } else {
                $this->upload_data['file'] = [ 'file_name' => $new_file ];
                return true;
            }
        }
    }

    public function logophotoupload()
    {
        $schoolinformation  = $this->schoolinformation_m->get_schoolinformation();
        $new_file = "site.png";
        if ( $_FILES["school_logo"]['name'] != "" ) {
            $file_name        = $_FILES["school_logo"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512', $random . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if ( customCompute($explode) >= 2 ) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name']     = $new_file;
                $_FILES['attach']['tmp_name'] = $_FILES['school_logo']['tmp_name'];
                $image_info = getimagesize($_FILES['school_logo']['tmp_name']);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
                // $config['max_size']      = '5120';
                // $config['max_width']     = '3000';
                // $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("school_logo") ) {
                    $this->form_validation->set_message("school_logo", $this->upload->display_errors());
                    return false;
                } else {
                    $fileData = $this->upload->data();
                    if($image_width > 1800 || $image_height > 1800){
                        resizeImage($fileData['file_name'],$config['upload_path']);
                     }
                    $this->upload_data['school_logo'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("school_logo", "Invalid file");
                return false;
            }
        } else {
            if ( customCompute($schoolinformation) ) {
                $this->upload_data['school_logo'] = [ 'file_name' => $schoolinformation->school_logo ];
                return true;
            } else {
                $this->upload_data['school_logo'] = [ 'file_name' => $new_file ];
                return true;
            }
        }
    }

    public function bannerphotoupload()
    {
        $schoolinformation  = $this->schoolinformation_m->get_schoolinformation();
        $new_file = "site.png";
        if ( $_FILES["school_banner"]['name'] != "" ) {
            $file_name        = $_FILES["school_banner"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512', $random . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if ( customCompute($explode) >= 2 ) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name']     = $new_file;
                $_FILES['attach']['tmp_name'] = $_FILES['school_banner']['tmp_name'];
                $image_info = getimagesize($_FILES['school_banner']['tmp_name']);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
                // $config['max_size']      = '5120';
                // $config['max_width']     = '3000';
                // $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("school_banner") ) {
                    $this->form_validation->set_message("school_banner", $this->upload->display_errors());
                    return false;
                } else {
                    $fileData = $this->upload->data();
                    if($image_width > 1800 || $image_height > 1800){
                        resizeImage($fileData['file_name'],$config['upload_path']);
                     }
                    $this->upload_data['school_banner'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("school_banner", "Invalid file");
                return false;
            }
        } else {
            if ( customCompute($schoolinformation) ) {
                $this->upload_data['school_banner'] = [ 'file_name' => $schoolinformation->school_banner ];
                return true;
            } else {
                $this->upload_data['school_banner'] = [ 'file_name' => $new_file ];
                return true;
            }
        }
    }

    public function principalphotoupload()
    {
        $schoolinformation  = $this->schoolinformation_m->get_schoolinformation();
        $new_file = "site.png";
        if ( $_FILES["principal_image"]['name'] != "" ) {
            $file_name        = $_FILES["principal_image"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512', $random . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if ( customCompute($explode) >= 2 ) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name']     = $new_file;
                $_FILES['attach']['tmp_name'] = $_FILES['principal_image']['tmp_name'];
                $image_info = getimagesize($_FILES['principal_image']['tmp_name']);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
                // $config['max_size']      = '5120';
                // $config['max_width']     = '3000';
                // $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("principal_image") ) {
                    $this->form_validation->set_message("principal_image", $this->upload->display_errors());
                    return false;
                } else {
                    $fileData = $this->upload->data();
                    if($image_width > 1800 || $image_height > 1800){
                        resizeImage($fileData['file_name'],$config['upload_path']);
                     }
                    $this->upload_data['principal_image'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("principal_image", "Invalid file");
                return false;
            }
        } else {
            if ( customCompute($schoolinformation) ) {
                $this->upload_data['principal_image'] = [ 'file_name' => $schoolinformation->principal_image ];
                return true;
            } else {
                $this->upload_data['principal_image'] = [ 'file_name' => $new_file ];
                return true;
            }
        }
    }

}