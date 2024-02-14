<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Liveclass extends Api_Controller {
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
		$this->load->model("classes_m");
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
					'rules' => 'trim|xss_clean|callback_valid_url'
				)
			);
		return $rules;
	}

	public function index_get() 
	{
		
		$usertypeID   = $this->session->userdata("usertypeID");
		if($usertypeID == 2){
			$liveClasses	= $this->liveclass_m->get_liveclass_with_condition($this->session->userdata('defaultschoolyearID'));
		}else{
			$liveClasses 	= $this->liveclass_m->get_liveclass_with_condition1($this->session->userdata('defaultschoolyearID'));
		}
		
		if(customCompute($liveClasses)){
			foreach($liveClasses as $key=>$liveClass){
				$liveClasses[$key]->join_url = $liveClass->teacher_join_url;
			}
		}

		$this->retdata['liveclass']  = $liveClasses;

		$this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
	}

	public function add_post()
	{

		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {

			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) { 

					$this->response([
                        'status' => false,
                        'message' => validation_errors(),
                    ], REST_Controller::HTTP_NOT_FOUND);
							
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
						"teacher_join_url" => $this->input->post("teacher_join_url")
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
						    $this->response([
								'status'    => true,
								'message'   => 'Success',
							], REST_Controller::HTTP_OK);
						} else {
							$this->response([
								'status' => false,
								'message' => $response->message,
							], REST_Controller::HTTP_NOT_FOUND);
						}
					} else {
						$this->response([
							'status' => false,
							'message' => $refreshToken->message,
						], REST_Controller::HTTP_NOT_FOUND);
					}

				}
			} 
		} else {
			$this->response([
				'status' => false,
				'message' => 'Not allowed',
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function edit_post($id)
	{
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
			
				$this->data['liveclass'] = $this->liveclass_m->get_single_liveclass(['id' => $id, 'school_year_id' => $schoolyearID]);
				
					if($_POST) {
						$rules = $this->rules();
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == FALSE) { 
							$this->response([
								'status' => false,
								'message' => validation_errors(),
							], REST_Controller::HTTP_NOT_FOUND);		
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
								"teacher_join_url" => $this->input->post("teacher_join_url")
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
									$this->response([
										'status' => false,
										'message' => $response->message,
									], REST_Controller::HTTP_NOT_FOUND);
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
									$this->response([
										'status'    => true,
										'message'   => 'Success',
									], REST_Controller::HTTP_OK);
								} else {
									$this->response([
										'status' => false,
										'message' => $response->message,
									], REST_Controller::HTTP_NOT_FOUND);
								}
							}else {
								$this->response([
									'status' => false,
									'message' => $refreshToken->message,
								], REST_Controller::HTTP_NOT_FOUND);
							}
							
						}
					} else {
						$this->data["subview"] = "liveclass/edit";
						$this->load->view('_layout_main', $this->data);
					}
				
			
		} else {
			$this->response([
				'status' => false,
				'message' => 'Not allowed',
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function view_get($id)
	{

		$result = [];
		$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
		if((int)$id) {
			$liveClass = $this->liveclass_m->get_single_liveclass(['id' => $id, 'school_year_id' => $schoolyearID]);
			if(is_object($liveClass)) {
				$zoomsetting 	= $this->zoomsettings_m->get_single_zoomsettings(['id' => 1]);
				$liveclass 	= $liveClass;
                
				$result = [
					'zoomsetting' => $zoomsetting,
					'liveclass'   => $liveclass
				];

				$this->response([
					'status'    => true,
					'message'   => 'Success',
					'data'      => $result
				], REST_Controller::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Not found.',
				], REST_Controller::HTTP_NOT_FOUND);
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'Invalid id.',
			], REST_Controller::HTTP_NOT_FOUND);
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