<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Subject extends Admin_Controller {
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
		$this->load->model("subject_m");
		$this->load->model("parents_m");
		$this->load->model("classes_m");
		$this->load->model("teacher_m");
		$this->load->model("student_m");
		$this->load->model("subjectteacher_m");
		$this->load->model("studentrelation_m");
        $this->load->model("courses_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('subject', $language);
	}

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);

		if($this->session->userdata('usertypeID') == 3) {
			$id = $this->data['myclass'];
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
		}

		if((int)$id) {
			$this->data['set'] = $id;
			$this->data['teachers'] = pluck($this->teacher_m->general_get_teacher(), 'name', 'teacherID');
			$this->data['classes'] = $this->student_m->get_classes();
			$fetchClass = pluck($this->data['classes'], 'classesID', 'classesID');
			if(isset($fetchClass[$id])) {
				$this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array('classesID' => $id));
				$this->data['subjectteachers'] = pluck_multi_array($this->subjectteacher_m->get_order_by_subjectteacher(array('classesID' => $id)), 'teacherID', 'subjectID');
				$this->data["subview"] = "subject/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['set'] = 0;
				$this->data['subjects'] = [];
				$this->data['subjectteachers'] = [];
				$this->data['classes'] = $this->student_m->get_classes();
				// dd($this->data);
				$this->data["subview"] = "subject/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data['set'] = 0;
			$this->data['subjects'] = [];
			$this->data['subjectteachers'] = [];
			$this->data['classes'] = $this->student_m->get_classes();
			$this->data["subview"] = "subject/index";
			
			$this->load->view('_layout_main', $this->data);
		}
	}

	protected function rules() {
		$rules = array(
				array(
					'field' => 'classesID',
					'label' => $this->lang->line("subject_class_name"),
					'rules' => 'trim|numeric|required|xss_clean|max_length[11]|callback_unique_classes'
				),
				array(
					'field' => 'teacherID',
					'label' => $this->lang->line("subject_teacher_name"),
					'rules' => 'trim|xss_clean|callback_unique_teacher'
				),
				array(
					'field' => 'type',
					'label' => $this->lang->line("subject_type"),
					'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_type'
				),
				array(
					'field' => 'passmark',
					'label' => $this->lang->line("subject_passmark"),
					'rules' => 'trim|required|xss_clean|max_length[11]|numeric|greater_than[0]'
				),
				array(
					'field' => 'finalmark',
					'label' => $this->lang->line("subject_finalmark"),
					'rules' => 'trim|required|xss_clean|max_length[11]|numeric|greater_than[0]'
				),
				array(
					'field' => 'subject',
					'label' => $this->lang->line("subject_name"),
					'rules' => 'trim|required|xss_clean|callback_unique_subject'
				),
				array(
					'field' => 'subject_author',
					'label' => $this->lang->line("subject_author"),
					'rules' => 'trim|xss_clean|max_length[100]'
				),
				array(
					'field' => 'subject_code',
					'label' => $this->lang->line("subject_code"),
					'rules' => 'trim|required|max_length[20]|xss_clean|callback_unique_subject_code'
				),
                array(
                    'field' => 'photo',
                    'label' => $this->lang->line("subject_photo"),
                    'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
                )
			);
		return $rules;
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
		$this->data['classes'] = $this->classes_m->get_classes();
		$this->data['teachers'] = $this->teacher_m->general_get_teacher();
        $this->data['teachersID'] = [];
        if($_POST) {
            $rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				if(customCompute($this->input->post('teacherID'))) {
					$this->data['teachersID'] = pluck($this->teacher_m->get_where_in_teacher($this->input->post('teacherID')), 'teacherID');
				}
				
				$this->data["subview"] = "subject/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				$array = array(
					"classesID" => $this->input->post("classesID"),
					"subject" => $this->input->post("subject"),
					'type' => $this->input->post('type'),
					'passmark' => $this->input->post('passmark'),
					'finalmark' => $this->input->post('finalmark'),
					"subject_author" => $this->input->post("subject_author"),
					"subject_code" => $this->input->post("subject_code"),
					"prerequisites" => $this->input->post("prerequisites"),
					"teacher_name" => '',
					"create_date" => date("Y-m-d h:i:s"),
					"modify_date" => date("Y-m-d h:i:s"),
					"create_userID" => $this->session->userdata('loginuserID'),
					"create_username" => $this->session->userdata('username'),
					"create_usertype" => $this->session->userdata('usertype'),
					"photo" => $this->upload_data['file']['file_name'],
					"coscholatics" => isset($_POST['coscholatics']) ? 1 : 0,
					"credit" => $this->input->post("credit")
				);
				$subjectID = $this->subject_m->insert_subject($array);

				if(isset($_POST['add_course'])) {
                    //TODO: insert new course here
                    $courseArray = [
                        'class_id' => $this->input->post("classesID"),
                        "subject_id" => $subjectID
                    ];
                    $this->courses_m->insert_courses($courseArray);
                }

				$teachers = $this->input->post('teacherID');
				$subjectteacherArray = [];
				if($teachers) {
					foreach ($teachers as $teacherID) {
						$subjectteacherArray[] = [
							'subjectID' => $subjectID,
							'teacherID' => $teacherID,
							'classesID' => $this->input->post("classesID"),
						];
					}
				}

				if(customCompute($subjectteacherArray)) {
					$this->subjectteacher_m->insert_batch_subjectteacher($subjectteacherArray);
				}

				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
                $redirect = $this->input->get('redirect');
                if(!is_null($redirect) && $redirect == 'courses') {
                    redirect(base_url("courses/index/"));
                }
				redirect(base_url("subject/index/".$this->input->post("classesID")));
			}
		} else {
			$this->data["subview"] = "subject/add";
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
		$url = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$id && (int)$url) {
			$this->data['classes'] = $this->classes_m->get_classes();
			$fetchClass = pluck($this->data['classes'], 'classesID', 'classesID');
			if(isset($fetchClass[$url])) {
				$this->data['teachers'] = $this->teacher_m->general_get_teacher();
				$this->data['subject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $id, 'classesID' => $url));
				$this->data['teachersID'] = pluck($this->subjectteacher_m->get_order_by_subjectteacher(array('subjectID' => $id)), 'teacherID');
				if(customCompute($this->data['subject'])) {
					$this->data['set'] = $url;
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) {
							if(customCompute($this->input->post('teacherID'))) {
								$this->data['teachersID'] = pluck($this->teacher_m->get_where_in_teacher($this->input->post('teacherID')), 'teacherID');
							}
							$this->data["subview"] = "subject/edit";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array = array(
								"classesID" => $this->input->post("classesID"),
								"subject" => $this->input->post("subject"),
								'type' => $this->input->post('type'),
								'passmark' => $this->input->post('passmark'),
								'finalmark' => $this->input->post('finalmark'),
								"subject_author" => $this->input->post("subject_author"),
								"subject_code" => $this->input->post("subject_code"),
								"prerequisites" => $this->input->post("prerequisites"),
								"modify_date" => date("Y-m-d h:i:s"),
								"coscholatics" => isset($_POST['coscholatics']) ? 1 : 0,
								"credit" => $this->input->post("credit")
							);
							if ($_FILES['photo']['name'] != '') {
                                $array["photo"] = $this->upload_data['file']['file_name'];
                            }

							$this->subject_m->update_subject($array, $id);
							$teachers = $this->input->post('teacherID');
							$subjectteacherArray = [];
							if($teachers) {
								foreach ($teachers as $teacherID) {
									$subjectteacherArray[] = [
										'subjectID' => $id,
										'teacherID' => $teacherID,
										'classesID' => $this->input->post("classesID"),
									];
								}
							}

							if(customCompute($subjectteacherArray)) {
								$this->subjectteacher_m->delete_subjectteacher_by_array(array('subjectID' => $id));
								$this->subjectteacher_m->insert_batch_subjectteacher($subjectteacherArray);
							}

							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("subject/index/$url"));
						}
					} else {
						$this->data["subview"] = "subject/edit";
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
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	// Todo: Don't let user delete if there is question bank, units
	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$id && (int)$url) {
			$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
			if(isset($fetchClass[$url])) {
				$subject = $this->subject_m->general_get_single_subject(array('subjectID' => $id, 'classesID' => $url));
				if(customCompute($subject)) {
					$this->subjectteacher_m->delete_subjectteacher_by_array(array('subjectID' => $id));
					$this->subject_m->delete_subject($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("subject/index/$url"));
				} else {
					redirect(base_url("subject/index"));
				}
			} else {
				redirect(base_url("subject/index"));
			}
		} else {
			redirect(base_url("subject/index"));
		}
	}

	public function unique_subject() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$subject = $this->subject_m->general_get_order_by_subject(array("subject" => $this->input->post("subject"), "subjectID !=" => $id, "classesID" => $this->input->post("classesID")));
			if(customCompute($subject)) {
				$this->form_validation->set_message("unique_subject", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$subject = $this->subject_m->general_get_order_by_subject(array("subject" => $this->input->post("subject"), "classesID" => $this->input->post("classesID"), "subject_code" => $this->input->post("subject_code")));

			if(customCompute($subject)) {
				$this->form_validation->set_message("unique_subject", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}
	}

	public function unique_subject_code() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$subject = $this->subject_m->general_get_order_by_subject(array("subject_code" => $this->input->post("subject_code"),'classesID' => $this->input->post("classesID"), "subjectID !=" => $id));
			if(customCompute($subject)) {
				$this->form_validation->set_message("unique_subject_code", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$subject = $this->subject_m->general_get_order_by_subject(array("subject_code" => $this->input->post("subject_code"),'classesID' => $this->input->post("classesID")));

			if(customCompute($subject)) {
				$this->form_validation->set_message("unique_subject_code", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}
	}

	public function subject_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("subject/index/$classID");
			echo $string;
		} else {
			redirect(base_url("subject/index"));
		}
	}

	public function unique_classes() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classes", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_teacher() {
		$error = 0;
		$teachers = $this->input->post('teacherID');
		if(customCompute($teachers)) {
			foreach($teachers as $teacher) {
				$teacherID = $teacher;
				$teacher = $this->teacher_m->general_get_single_teacher(array('teacherID' => $teacherID));
				if(!customCompute($teacher)) {
					$error++;
				}
			}

			if($error == 0) {
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_teacher", "The %s is required.");
	     		return FALSE;
			}
		} else {
			$this->form_validation->set_message("unique_teacher", "The %s is required.");
	     	return FALSE;
		}
	}

	public function unique_type() {
		if($this->input->post('type') == 'select') {
			$this->form_validation->set_message("unique_type", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function ajaxGetSubjectsFromClassId() {
		$usertypeID  = $this->session->userdata('usertypeID');
		$loginuserID = $this->session->userdata('loginuserID');
		$class_id = $this->input->get('class_id');
		if ($usertypeID == 2) {
			$subjects = $this->courses_m->get_join_courses_based_on_teacher_id($class_id, $loginuserID);
		} else {
			$subjects = $this->subject_m->get_subjects_by_class_id($class_id);
		}
		$array = [];
			$array[""] = $this->lang->line("select_subject");
			foreach ($subjects as $subject) {
				$array[$subject->subjectID] = $subject->subject;
			}
		$ajaxResponse = [
			'form' => form_dropdown("subject_id", $array, set_value("subject_id"),
				"id='subject_id' class='form-control select2' required"),
			'has_subject' => count($subjects) > 0
		];
		echo json_encode($ajaxResponse);
	}
	
	public function ajaxGetSubjectsFromClassIdTeacherId() {
		$usertypeID  = $this->session->userdata('usertypeID');
		$loginuserID = $this->session->userdata('loginuserID');
		$class_id = $this->input->get('class_id');
		if ($usertypeID == 2) {
			$subjects = $this->subject_m->get_subject_by_teacherID_classID($loginuserID, $class_id);
		} else {
			$subjects = $this->subject_m->get_subjects_by_class_id($class_id);
		}
		$array = [];
			$array[""] = $this->lang->line("select_subject");
			foreach ($subjects as $subject) {
				$array[$subject->subjectID] = $subject->subject;
			}
		$ajaxResponse = [
			'form' => form_dropdown("subject_id", $array, set_value("subject_id"),
				"id='subject_id' class='form-control select2' required"),
			'has_subject' => count($subjects) > 0
		];
		echo json_encode($ajaxResponse);
	}

	public function ajaxGetChaptersFromSubjectId() {
        $subject_id = $this->input->get('subject_id');
        $chapters = $this->chapter_m->get_chapter_from_subject_id($subject_id);
        $array = [];
        $array[0] = $this->lang->line("select_subject");
        foreach ($chapters as $chapter) {
            $array[$chapter->id] = $chapter->chapter_name;
        }
        echo form_dropdown("chapter_id", $array, set_value("chapter_id"), "id='chapter_id' class='form-control select2'");
	}

    public function photoupload()
    {
        $id   = htmlentities(escapeString($this->uri->segment(3)));
        $user = [];
        if ( (int) $id ) {
            $user = $this->teacher_m->get_single_teacher([ 'teacherID' => $id ]);
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
}
