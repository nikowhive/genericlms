<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Exam_setting extends Admin_Controller {
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
		$this->load->model("chapter_m");
		$this->load->model("question_group_m");
		$this->load->model("question_level_m");
		$this->load->model("question_type_m");
		$this->load->model("exam_setting_m");
		$this->load->model("classes_m");
		$this->load->model("unit_m");
		$this->load->model("subject_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('exam_setting', $language);	
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

		$usertypeID = $this->session->userdata("usertypeID");
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['set'] = $id;
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['exam_settings'] = $this->exam_setting_m->get_join_exam_settings($id);
			$this->data["subview"] = "exam_setting/index";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data["subview"] = "exam_setting/search";
			$this->load->view('_layout_main', $this->data);
		}
	}

	protected function rules() {
		$rules = array(
				array(
					'field' => 'classesID', 
					'label' => $this->lang->line("subject_class_name"), 
					'rules' => 'trim|numeric|required|xss_clean|max_length[11]|callback_allclasses'
				),
				array(
					'field' => 'subject_id', 
					'label' => $this->lang->line("subject_name"), 
					'rules' => 'trim|numeric|required|xss_clean|greater_than[0]'
				), 
				array(
					'field' => 'setting_name', 
					'label' => $this->lang->line("setting_name"), 
					'rules' => 'trim|required|xss_clean|max_length[60]'
				), 
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
		
		$this->data['subjects'] = [];

		$_question_groups = $this->question_group_m->get_question_group();
		$this->data['question_group'][""] = $this->lang->line('exam_setting_select');
		foreach($_question_groups as $q) {
			$this->data['question_group'][$q->questionGroupID] = $q->title;
		}
		
		$_units = $this->unit_m->get_unit();
		foreach($_units as $u) {
			$this->data['units'][$u->id] = $u->unit_name;
		}

		$_level = $this->question_level_m->get_question_level();
		$this->data['level'][""] = $this->lang->line('exam_setting_select');
		foreach($_level as $l) {
			$this->data['level'][$l->questionLevelID] = $l->name;
		}

		$_question_type = $this->question_type_m->get_question_type();
		$this->data['question_type'][""] = $this->lang->line('exam_setting_select');
		foreach($_question_type as $q) {
			$this->data['question_type'][$q->questionTypeID] = $q->name;
		}
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) { 
				$this->data["subview"] = "exam_setting/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$_details = $this->input->post('details'); 

				if(is_null($_details)) {
					$this->session->set_flashdata('error', 'Please add details');
					redirect(base_url("exam_setting/add"));	
				}

				$details = [];
				foreach($_details['unit'] as $index => $unit) {
					$details[] = [
						'unit'	=>	$unit,
						'question_group'	=>	$_details['question_group'][$index],
						'level'	=>	$_details['level'][$index],
						'question_type'	=>	$_details['question_type'][$index],
						'mark'	=>	$_details['mark'][$index],
						'no_of_questions'	=> $_details['no_of_questions'][$index]
					];
				}

				$input = [
					'setting_name' => $this->input->post('setting_name'),
					'subject_id'	=>	$this->input->post('subject_id'),
					'details'	=>	json_encode($details)
				];

				$this->exam_setting_m->insert_setting($input);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				$class_id = $this->input->post('classesID');
				redirect(base_url("exam_setting/index/".$class_id));
			}
		} else {
			$this->data["subview"] = "exam_setting/add";
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
			$this->data['set_class'] = NULL;
			
			$this->data['subjects'] = [];
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['exam_setting'] = $this->exam_setting_m->get_exam_setting($id);

			$_question_groups = $this->question_group_m->get_question_group();
			$this->data['question_group'][""] = $this->lang->line('exam_setting_select');
			foreach($_question_groups as $q) {
				$this->data['question_group'][$q->questionGroupID] = $q->title;
			}

			$_units = $this->unit_m->get_units_by_subject_id($this->data['exam_setting']->subject_id);
			foreach($_units as $u) {
				$this->data['units'][$u->id] = $u->unit_name;
			}

			$_level = $this->question_level_m->get_question_level();
			$this->data['level'][""] = $this->lang->line('exam_setting_select');
			foreach($_level as $l) {
				$this->data['level'][$l->questionLevelID] = $l->name;
			}

			$_question_type = $this->question_type_m->get_question_type();
			$this->data['question_type'][""] = $this->lang->line('exam_setting_select');
			foreach($_question_type as $q) {
				$this->data['question_type'][$q->questionTypeID] = $q->name;
			}

			if($this->data['exam_setting']) {
				$this->data['exam_setting']->details = json_decode($this->data['exam_setting']->details);
				$this->data['exam_setting']->details = $this->data['exam_setting']->details ? $this->data['exam_setting']->details : [];

				$url = $this->data['set_class'] = $this->subject_m->get_class_id_from_subject($this->data['exam_setting']->subject_id);
				$this->data['subjects'] = $this->subject_m->get_subjects_by_class_id($this->data['set_class']);				
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data['form_validation'] = validation_errors(); 
						$this->data["subview"] = "exam_setting/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$_details = $this->input->post('details'); 

						if(is_null($_details)) {
							$this->session->set_flashdata('error', 'Please add details');
							redirect(base_url("exam_setting/edit/".$id));	
						}

						$details = [];
						foreach($_details['unit'] as $index => $unit) {
							$details[] = [
								'unit'	=>	$unit,
								'question_group'	=>	$_details['question_group'][$index],
								'level'	=>	$_details['level'][$index],
								'question_type'	=>	$_details['question_type'][$index],
								'mark'	=>	$_details['mark'][$index],
								'no_of_questions'	=> $_details['no_of_questions'][$index]
							];
						}

						$array = array(
							"subject_id" => $this->input->post("subject_id"),
							"setting_name" => $this->input->post("setting_name"),
							'details'	=>	json_encode($details)
						);
						
						$this->exam_setting_m->update_setting($array, $id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("exam_setting/index/$url"));
					}
				} else {
					$this->data["subview"] = "exam_setting/edit";
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
			$exam_setting = $this->exam_setting_m->get_exam_setting($id);
			if(customCompute($exam_setting)) {
				$url = $this->subject_m->get_class_id_from_subject($exam_setting->subject_id);
				$this->exam_setting_m->delete_setting($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("exam_setting/index/$url"));
			} else {
				redirect(base_url("exam_setting/index"));
			}
		} else {
			redirect(base_url("exam_setting/index"));
		}
	}

	public function allclasses() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("allclasses", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function exam_setting_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("exam_setting/index/$classID");
			echo $string;
		} else {
			redirect(base_url("exam_setting/index"));
		}
	}
}