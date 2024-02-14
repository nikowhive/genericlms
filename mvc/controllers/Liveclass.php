<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Liveclass extends Admin_Controller {
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
		$this->load->model("liveclass_m");
		$this->load->model("section_m");
		$this->load->model("subject_m");
		$this->load->model("zoomsettings_m");
		$this->load->library('zoom');
		$language = $this->session->userdata('lang');
		$this->lang->load('liveclass', $language);
	}

	protected function rules() {
		$rules = array(
				array(
					'field' => 'title', 
					'label' => $this->lang->line("liveclass_title"), 
					'rules' => 'trim|required|xss_clean|max_length[255]'
				),
				array(
					'field' => 'date',
					'label' => $this->lang->line("liveclass_date"), 
					'rules' => 'trim|required|xss_clean|max_length[19]|callback_date|callback_past_date'
				),
				array(
					'field' => 'duration', 
					'label' => $this->lang->line("liveclass_duration"), 
					'rules' => 'trim|required|xss_clean|max_length[10]|numeric'
				),
				array(
					'field' => 'classesId', 
					'label' => $this->lang->line("liveclass_classes"), 
					'rules' => 'trim|required|numeric|xss_clean|max_length[11]|callback_unique_classesId'
				),
				array(
					'field' => 'sectionId', 
					'label' => $this->lang->line("liveclass_section"), 
					'rules' => 'trim|required|numeric|xss_clean|max_length[11]'
				),
				array(
					'field' => 'subjectId', 
					'label' => $this->lang->line("liveclass_subject"), 
					'rules' => 'trim|required|numeric|xss_clean|max_length[11]'
				),
				array(
					'field' => 'teacher_join_url', 
					'label' => $this->lang->line("liveclass_teacher_join_url"), 
					'rules' => 'trim|required|xss_clean|callback_valid_url'
				)
			);
		return $rules;
	}

	public function index() 
	{
		$usertypeID   = $this->session->userdata("usertypeID");
		$this->data['class'] 		= pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
		$this->data['section'] 		= pluck($this->section_m->general_get_section(), 'section', 'sectionID');
		$this->data['subject'] 	 	= pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
		if($usertypeID == 2){
			$this->data['liveclass'] 	= $this->liveclass_m->get_liveclass_with_condition($this->session->userdata('defaultschoolyearID'));
		}else{
			$this->data['liveclass'] 	= $this->liveclass_m->get_liveclass_with_condition1($this->session->userdata('defaultschoolyearID'));
		}
		$this->data["subview"] = "liveclass/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function refreshliveclass(){

		$searchValue = $this->input->get('searchValue');

        $usertypeID   = $this->session->userdata("usertypeID");
		$this->data['class'] 		= pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
		$this->data['section'] 		= pluck($this->section_m->general_get_section(), 'section', 'sectionID');
		$this->data['subject'] 	 	= pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
		if($usertypeID == 2){
			$this->data['liveclass'] 	= $this->liveclass_m->get_liveclass_with_condition($this->session->userdata('defaultschoolyearID'),$searchValue);
		}else{
			$this->data['liveclass'] 	= $this->liveclass_m->get_liveclass_with_condition1($this->session->userdata('defaultschoolyearID'),$searchValue);
		}
		echo $this->load->view('liveclass/liveclass_refresh', $this->data, true);
		exit;
	}

	public function add()
	{

		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
					'assets/datetimepicker/datetimepicker.css',
				),
				'js' => array(
					'assets/select2/select2.js',
					'assets/liveclass/js/add.js',
					'assets/datetimepicker/moment.js',
                    'assets/datetimepicker/datetimepicker.js',
				)
			);

			$this->data['classes'] = $this->classes_m->get_classes();
			$classesId = $this->input->post("classesId");
			
			if($classesId) {
				$this->data['subjects'] = $this->subject_m->get_order_by_subject(array('classesID' => $classesId));
				$this->data['sections'] = $this->section_m->get_order_by_section(array("classesID" => $classesId));
			} else {
				$this->data['subjects'] = [];
				$this->data['sections'] = [];
			}

			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) { 
					$this->data["subview"] = "liveclass/add";
					$this->load->view('_layout_main', $this->data);			
				} else {
					$array = array(
						"title" 		=> $this->input->post("title"),
						'date' 			=> date("Y-m-d H:i:s", strtotime($this->input->post("date"))),
						'duration' 		=> $this->input->post('duration'),
						"classes_id" 	=> $this->input->post("classesId"),
						"section_id" 	=> $this->input->post("sectionId"),
						'subject_id' 	=> $this->input->post('subjectId'),
						"usertype_id" 	=> $this->session->userdata('usertypeID'),
						"user_id"		=> $this->session->userdata('loginuserID'),
						"created_at" 	=> date("Y-m-d H:i:s"),
						"updated_at" 	=> date("Y-m-d H:i:s"),
						'creator_id' 	=> $this->session->userdata('usertypeID'),
						'editor_id' 	=> $this->session->userdata('loginuserID'),
						'school_year_id' => $this->session->userdata('defaultschoolyearID'),
						"teacher_join_url" => $this->input->post("teacher_join_url"),
						"reminder" => $this->input->post("reminder")
					);

					$zoomSetting 	= $this->zoomsettings_m->get_zoomsettings(1);
					$tokenInfo 		= json_decode($zoomSetting->data, true);

					$refreshToken 	= $this->zoom->refreshToken($zoomSetting->client_id, $zoomSetting->client_secret, $tokenInfo['refresh_token']);

					if($refreshToken->status) {
						if(isset($refreshToken->data)) {
							$this->zoomsettings_m->update_zoomsettings(['data' => json_encode($refreshToken->data)], 1);
						}

						$zoomSetting 	= $this->zoomsettings_m->get_zoomsettings(1);
						$tokenInfo 		= json_decode($zoomSetting->data, true);
						$response 		= $this->zoom->createMetting($zoomSetting->client_id, $zoomSetting->client_secret, $tokenInfo, $array);

						if($response->status) {
							if(isset($response->update_token)) {
								$this->zoomsettings_m->update_zoomsettings(['data' => json_encode($response->update_token)], 1);
							}
							if($response->data) {
								$array['join_url'] = $response->data['join_url'];
								$array['password'] = $response->data['password'];
								$array['metting_id'] = $response->data['metting_id'];
							}
							$this->liveclass_m->insert_liveclass($array);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						} else {
							$this->session->set_flashdata('error', $response->message);
						}
					} else {
						$this->session->set_flashdata('error', $refreshToken->message);
					}

					redirect(base_url("liveclass/index"));
				}
			} else {
				$this->data["subview"] = "liveclass/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit()
	{
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
					'assets/datetimepicker/datetimepicker.css',
				),
				'js' => array(
					'assets/select2/select2.js',
					'assets/liveclass/js/edit.js',
					'assets/datetimepicker/moment.js',
                    'assets/datetimepicker/datetimepicker.js',
				)
			);

			$id     		= htmlentities(escapeString($this->uri->segment(3)));
			$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
			if((int) $id) {
				$this->data['liveclass'] = $this->liveclass_m->get_single_liveclass(['id' => $id, 'school_year_id' => $schoolyearID]);
				if(is_object($this->data['liveclass'])) {
					$this->data['classes'] = $this->classes_m->get_classes();
					
					if(isset($this->data['liveclass']->classes_id)) {
						$classesId = $this->data['liveclass']->classes_id;
					} else {
						$classesId = $this->input->post("classesId");
					}

					if($classesId) {
						$this->data['subjects'] = $this->subject_m->get_order_by_subject(array('classesID' => $classesId));
						$this->data['sections'] = $this->section_m->get_order_by_section(array("classesID" => $classesId));
					} else {
						$this->data['subjects'] = [];
						$this->data['sections'] = [];
					}

					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) { 
							$this->data["subview"] = "liveclass/edit";
							$this->load->view('_layout_main', $this->data);			
						} else {

							if($this->data['liveclass']->date != date("Y-m-d H:i:s", strtotime($this->input->post("date")))){
                               $added_to_job = 0;
							}else{
								$added_to_job = 1;
							}
							
							$array = array(
								"title" 		=> $this->input->post("title"),
								'date' 			=> date("Y-m-d H:i:s", strtotime($this->input->post("date"))),
								'duration' 		=> $this->input->post('duration'),
								"classes_id" 	=> $this->input->post("classesId"),
								"section_id" 	=> $this->input->post("sectionId"),
								'subject_id' 	=> $this->input->post('subjectId'),
								"usertype_id" 	=> $this->session->userdata('usertypeID'),
								"user_id"		=> $this->session->userdata('loginuserID'),
								"created_at" 	=> date("Y-m-d H:i:s"),
								"updated_at" 	=> date("Y-m-d H:i:s"),
								'creator_id' 	=> $this->session->userdata('usertypeID'),
								'editor_id' 	=> $this->session->userdata('loginuserID'),
								"teacher_join_url" => $this->input->post("teacher_join_url"),
								"reminder"         => $this->input->post("reminder"),
								"added_to_job" 	   => $added_to_job
							);


							$zoomSetting 	= $this->zoomsettings_m->get_zoomsettings(1);
							$tokenInfo 		= json_decode($zoomSetting->data, true);
							$refreshToken 	= $this->zoom->refreshToken($zoomSetting->client_id, $zoomSetting->client_secret, $tokenInfo['refresh_token']);

							if($refreshToken->status) {

								if(isset($refreshToken->data)) {
									$this->zoomsettings_m->update_zoomsettings(['data' => json_encode($refreshToken->data)], 1);
								}

								$zoomSetting 	= $this->zoomsettings_m->get_zoomsettings(1);
								$tokenInfo 		= json_decode($zoomSetting->data, true);
								$response 		= $this->zoom->deleteMetting($zoomSetting->client_id, $zoomSetting->client_secret, $tokenInfo, $this->data['liveclass']->metting_id);

								if($response->status) {
									if(isset($response->update_token)) {
										$this->zoomsettings_m->update_zoomsettings(['data' => json_encode($response->update_token)], 1);
									}
								} else {
									$this->session->set_flashdata('error', $response->message);
								}

								$response = $this->zoom->createMetting($zoomSetting->client_id, $zoomSetting->client_secret, $tokenInfo, $array);

								if($response->status) {
									if(isset($response->update_token)) {
										$this->zoomsettings_m->update_zoomsettings(['data' => json_encode($response->update_token)], 1);
									}

									if($response->data) {
										$array['join_url'] = $response->data['join_url'];
										$array['password'] = $response->data['password'];
										$array['metting_id'] = $response->data['metting_id'];
									}
									$this->liveclass_m->update_liveclass($array, $id);
									$this->session->set_flashdata('success', $this->lang->line('menu_success'));
								} else {
									$this->session->set_flashdata('error', $response->message);
								}
							}

							redirect(base_url("liveclass/index"));
						}
					} else {
						$this->data["subview"] = "liveclass/edit";
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

	public function view()
	{
		$this->data['headerassets'] = array(
			'css' => array(
				'https://source.zoom.us/1.7.8/css/bootstrap.css',
				'https://source.zoom.us/1.7.8/css/react-select.css',
			),
			'js' => array(
				'https://source.zoom.us/1.7.8/lib/vendor/react.min.js',
				'https://source.zoom.us/1.7.8/lib/vendor/react-dom.min.js',
				'https://source.zoom.us/1.7.8/lib/vendor/redux.min.js',
                'https://source.zoom.us/1.7.8/lib/vendor/redux-thunk.min.js',
                'https://source.zoom.us/1.7.8/lib/vendor/jquery.min.js',
                'https://source.zoom.us/1.7.8/lib/vendor/lodash.min.js',
                'https://source.zoom.us/zoom-meeting-1.7.8.min.js',
			)
		);

		$id 			= htmlentities(escapeString($this->uri->segment(3)));
		$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
		if((int)$id) {
			$liveClass = $this->liveclass_m->get_single_liveclass(['id' => $id, 'school_year_id' => $schoolyearID]);
			if(is_object($liveClass)) {
				$this->data['zoomsetting'] 	= $this->zoomsettings_m->get_single_zoomsettings(['id' => 1]);
				$this->data['liveclass'] 	= $liveClass;
				$this->data["subview"] = "liveclass/view";
				$this->load->view($this->data["subview"], $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete()
	{
		$id 			= htmlentities(escapeString($this->uri->segment(3)));
		$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
		if((int)$id) {

			$liveClass = $this->liveclass_m->get_single_liveclass(['id' => $id, 'school_year_id' => $schoolyearID]);
			if(is_object($liveClass)) {
				$zoomSetting 	= $this->zoomsettings_m->get_zoomsettings(1);
				$tokenInfo 		= json_decode($zoomSetting->data, true);

				$refreshToken 	= $this->zoom->refreshToken($zoomSetting->client_id, $zoomSetting->client_secret, $tokenInfo['refresh_token']);
				if($refreshToken->status) {
					if(isset($refreshToken->data)) {
						$this->zoomsettings_m->update_zoomsettings(['data' => json_encode($refreshToken->data)], 1);
					}

					$zoomSetting 	= $this->zoomsettings_m->get_zoomsettings(1);
					$tokenInfo 		= json_decode($zoomSetting->data, true);
					$response 		= $this->zoom->deleteMetting($zoomSetting->client_id, $zoomSetting->client_secret, $tokenInfo, $liveClass->metting_id);

					if($response->status) {
						if(isset($response->update_token)) {
							$this->zoomsettings_m->update_zoomsettings(['data' => json_encode($response->update_token)], 1);
						}
					}

					$this->liveclass_m->delete_liveclass($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				} else {
					$this->session->set_flashdata('error', $response->message);
				}
				
				redirect(base_url("liveclass/index"));
			} else {
				redirect(base_url("liveclass/index"));
			}
		} else {
			redirect(base_url("liveclass/index"));
		}
	}

	public function date($date) 
	{
		if(!empty($date)) {
			if(strlen($date) < 19) {
				$this->form_validation->set_message("date", "%s is invalid");
		     	return false;
			} else {
				$format = 'd-m-Y h:i A';
			    $dateTime = DateTime::createFromFormat($format, $date);
			    if ($dateTime instanceof DateTime && $dateTime->format('d-m-Y h:i A') == $date) {
			    	return true;
			    } else {
			    	$this->form_validation->set_message("date", "%s is invalid");
			    	return false;
			    }
		    } 
		} else {
			$this->form_validation->set_message("date", "The %s field is required.");
		    return false;
		}
	}

	public function past_date($date)
	{
		if(!empty($date)) {
            if(strtotime(date('d-m-Y h:i A', strtotime('-5 minutes', strtotime(date('d-m-Y h:i A'))))) >= strtotime($date)) {
                $this->form_validation->set_message("past_date", "The %s field must be greater than the present time");
                return false;
            }
	        return true;
		}
	}

	public function subjectcall() 
	{
		$usertypeID		= $this->session->userdata('usertypeID');
		$userID		= $this->session->userdata('loginuserID');
		$classId = $this->input->post('id');

		if($usertypeID == 1){
				$allclasses = $this->subject_m->get_order_by_subject(array('classesID' => $classId));
				echo "<option value='0'>", $this->lang->line("liveclass_select_subject"),"</option>";
				foreach ($allclasses as $value) {
					echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
				}
		}else{
			$class = $this->classes_m->getClassByID(['classesID' => $classId ]);
           	if($class->teacherID == $userID){
					$allclasses = $this->subject_m->get_order_by_subject(array('classesID' => $classId));
					echo "<option value='0'>", $this->lang->line("liveclass_select_subject"),"</option>";
					foreach ($allclasses as $value) {
						echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
					}
			}else{
				$sections = $this->section_m->getSectionByTeacher(['teacherID' => $userID,'classesID' => $classId ]);
				if(customCompute($sections)){
					$allclasses = $this->subject_m->get_order_by_subject(array('classesID' => $classId));
					echo "<option value='0'>", $this->lang->line("liveclass_select_subject"),"</option>";
					foreach ($allclasses as $value) {
						echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
					}
				}else{
					$allclasses = $this->subject_m->getSubjectsByTeacherID(['teacherID' => $userID,'classesID' => $classId ]);
					echo "<option value='0'>", $this->lang->line("liveclass_select_subject"),"</option>";
					foreach ($allclasses as $value) {
						echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
					}
				}
			}

		}
	}

	public function sectioncall() 
	{
		$usertypeID		= $this->session->userdata('usertypeID');
		$userID		= $this->session->userdata('loginuserID');
		$classId = $this->input->post('id');
		
	if($usertypeID == 1){
			$allsection = $this->section_m->get_order_by_section(array("classesID" => $classId));
			echo "<option value='0'>", $this->lang->line("liveclass_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
	}else{
		$class = $this->classes_m->getClassByID(['classesID' => $classId ]);
		   if($class->teacherID == $userID){
			$allsection = $this->section_m->get_order_by_section(array("classesID" => $classId));
			echo "<option value='0'>", $this->lang->line("liveclass_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
		}else{
			$allsection = $this->section_m->getSectionByTeacher(['teacherID' => $userID,'classesID' => $classId ]);
			if(customCompute($allsection)){
				echo "<option value='0'>", $this->lang->line("liveclass_select_section"),"</option>";
				foreach ($allsection as $value) {
					echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
				}
			}else{
				$allsection = $this->section_m->get_order_by_section(array("classesID" => $classId));
				echo "<option value='0'>", $this->lang->line("liveclass_select_section"),"</option>";
				foreach ($allsection as $value) {
					echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
				}
			}
		}

	}
	}

	public function unique_classesId() 
	{
		$examID = $this->input->post('classesId');
		if($examID === '0') {
			$this->form_validation->set_message("unique_classesId", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	function valid_url(){
		$url = $this->input->post('teacher_join_url');
		if(filter_var($url, FILTER_VALIDATE_URL)){
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

}