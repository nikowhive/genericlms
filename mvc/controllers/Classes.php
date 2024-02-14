<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classes extends Admin_Controller {
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
		$this->load->model("classes_m");
		$this->load->model("teacher_m");
		$this->load->model('studentrelation_m');
		$this->load->model('classgroup_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('classes', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'classes', 
				'label' => $this->lang->line("classes_name"), 
				'rules' => 'trim|required|xss_clean|max_length[120]|callback_unique_classes'
			), 
			array(
				'field' => 'classes_numeric', 
				'label' => $this->lang->line("classes_numeric"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_classes_numeric|callback_unique_valid_number'
			), 
			array(
				'field' => 'teacherID', 
				'label' => $this->lang->line("teacher_name"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_teacher'
			),
			array(
				'field' => 'classgroupID', 
				'label' => $this->lang->line("class_group"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean'
			),
		
			array(
				'field' => 'note', 
				'label' => $this->lang->line("classes_note"), 
				'rules' => 'trim|max_length[200]|xss_clean'
			)
		);


		if(htmlentities(escapeString($this->uri->segment(3))) == ''){
             $rules[] = 	array(
                'field' => 'photo',
                'label' => $this->lang->line("class_photo"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			 );
 		}else{
			$rules[] = 	array(
                'field' => 'photo',
                'label' => $this->lang->line("class_photo"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
			 );
		 }

		return $rules;
	}

	public function photoupload()
    {
        $id   = htmlentities(escapeString($this->uri->segment(3)));
        $user = [];
        if ( (int) $id ) {
            // $user = $this->classgroup_m->get_single_classgroup([ 'classgroupID' => $id ]);
			$user = $this->classes_m->get_single_classes(array('classesID' => $id));
        }
        $new_file = "";
        if ( $_FILES["photo"]['name'] != "" ) {
            $file_name        = $_FILES["photo"]['name'];
			$uploadPath = 'uploads/images';
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
				$_FILES['attach']['tmp_name'] = $_FILES['photo']['tmp_name'];
                $image_info = getimagesize($_FILES['photo']['tmp_name']);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
                // $config['max_size']      = '5120';
                // $config['max_width']     = '3000';
                // $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("photo") ) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return false;
                } else {
					$fileData = $this->upload->data();

                    	resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height);

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

	public function index() {
		$this->data['teachers'] = pluck($this->teacher_m->get_teacher(), 'name', 'teacherID');
		$this->data['classes'] = $this->classes_m->get_classes();
		$this->data["subview"] = "classes/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);

		$this->data['teachers'] = $this->teacher_m->get_teacher();
		$this->data['classgroups'] = $this->classgroup_m->get_classgroup();
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) { 
				$this->data["subview"] = "classes/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"classes" => $this->input->post("classes"),
					"classes_numeric" => $this->input->post("classes_numeric"),
					'classgroupID'=>$this->input->post("classgroupID"),
					"teacherID" => $this->input->post("teacherID"),
					"studentmaxID" => 999999999,
					"note" => $this->input->post("note"),
					"create_date" => date("Y-m-d h:i:s"),
					"modify_date" => date("Y-m-d h:i:s"),
					"create_userID" => $this->session->userdata('loginuserID'),
					"create_username" => $this->session->userdata('username'),
					"create_usertype" => $this->session->userdata('usertype')
				);

				if ($_FILES['photo']['name'] != '') {
                    $array["photo"] = $this->upload_data['file']['file_name'];
                }

				$this->classes_m->insert_classes($array);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("classes/index"));
			}
		} else {
			$this->data["subview"] = "classes/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['teachers'] = $this->teacher_m->get_teacher();
			$this->data['classes'] = $this->classes_m->get_single_classes(array('classesID' => $id));
			$this->data['classgroups'] = $this->classgroup_m->get_classgroup();
			if(customCompute($this->data['classes'])) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "classes/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$array = array(
							"classes" => $this->input->post("classes"),
							"classes_numeric" => $this->input->post("classes_numeric"),
							"teacherID" => $this->input->post("teacherID"),
							'classgroupID'=>$this->input->post("classgroupID"),
							"studentmaxID" => 999999999,
							"note" => $this->input->post("note"),
							"modify_date" => date("Y-m-d h:i:s")
						);

						if ($_FILES['photo']['name'] != '') {
                            $array["photo"] = $this->upload_data['file']['file_name'];
                        }

						$this->studentrelation_m->update_studentrelation_with_multicondition(array('srclasses' => $this->input->post("classes")), array('srclassesID' => $id));

						$this->classes_m->update_classes($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("classes/index"));
					}
				} else {
					$this->data["subview"] = "classes/edit";
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

	// Todo: Don't let user delete if there is question bank, subjects
	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$classes = $this->classes_m->get_single_classes(array('classesID' => $id));
			if(customCompute($classes)) {
				$this->classes_m->delete_classes($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("classes/index"));
			} else {
				redirect(base_url("classes/index"));
			}
		} else {
			redirect(base_url("classes/index"));
		}
	}

	public function unique_classes() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$classes = $this->classes_m->get_order_by_classes(array("classes" => $this->input->post("classes"), "classesID !=" => $id));
			if(customCompute($classes)) {
				$this->form_validation->set_message("unique_classes", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$classes = $this->classes_m->get_order_by_classes(array("classes" => $this->input->post("classes")));
			if(customCompute($classes)) {
				$this->form_validation->set_message("unique_classes", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}	
	}

	public function unique_classes_numeric() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$classes_numeric = $this->classes_m->get_order_by_classes(array("classes_numeric" => $this->input->post("classes_numeric"), "classesID !=" => $id));
			if(customCompute($classes_numeric)) {
				$this->form_validation->set_message("unique_classes_numeric", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$classes_numeric = $this->classes_m->get_order_by_classes(array("classes_numeric" => $this->input->post("classes_numeric")));
			if(customCompute($classes_numeric)) {
				$this->form_validation->set_message("unique_classes_numeric", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}	
	}

	public function unique_teacher() {
		if($this->input->post('teacherID') == 0) {
			$this->form_validation->set_message("unique_teacher", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_valid_number() {
		if($this->input->post('classes_numeric') < 0) {
			$this->form_validation->set_message("unique_valid_number", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}
}