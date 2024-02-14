<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends Admin_Controller {
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
        $this->load->model("faq_m");
		$this->load->model("classes_m");
		$this->load->model("teacher_m");
		$this->load->model('studentrelation_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('faq', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'question', 
				'label' => $this->lang->line("faq_question"), 
				'rules' => 'trim|required|xss_clean'
			), 
            array(
				'field' => 'answer', 
				'label' => $this->lang->line("faq_answer"), 
				'rules' => 'trim|required|xss_clean'
			), 
			array(
				'field' => 'classesID', 
				'label' => $this->lang->line("faq_class"),
				'rules' => 'trim|xss_clean'
			)
		);
		return $rules;
	}

	public function index() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['set'] = $id;
			$this->data['classes'] = $this->classes_m->get_classes();
			$fetchClass = pluck($this->data['classes'], 'classesID', 'classesID');
			if(isset($fetchClass[$id])) {
				$this->data['faq'] = $this->faq_m->get_faq_by_class($fetchClass[$id]);
				$this->data['classesID'] =$fetchClass[$id] ;
				$this->data["subview"] = "faq/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['set'] = 0;
				$this->data['classesID'] = [];
				$this->data['faq'] = [];
				$this->data['classes'] = $this->classes_m->get_classes();
				// dd($this->data);
				$this->data["subview"] = "faq/index";
				$this->load->view('_layout_main', $this->data);
			}
		}
		else{
			$this->data['set'] = '0';
			$this->data['classesID'] = [];
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['faq'] = $this->faq_m->get_faq();
			$this->data["subview"] = "faq/index";
			$this->load->view('_layout_main', $this->data);
		}
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
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) { 
				$this->data["subview"] = "faq/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"question" => $this->input->post("question"),
                    "answer" => $this->input->post("answer"),
					"create_date" => date("Y-m-d h:i:s"),
					"modify_date" => date("Y-m-d h:i:s"),
					"create_userID" => $this->session->userdata('loginuserID'),
					"create_usertypeID" => $this->session->userdata('usertypeID')
				);
                $classID = $this->input->post('classesID');
				if($this->faq_m->insert_faq($array)){
                    $faqID = $this->db->insert_id();
                    
				$classArray = [];
				if($classID) {
					foreach ($classID as $classesID) {
						$classArray = [
							'classes_id' => $classesID,
                            'faq_id'     => $faqID,	
						];
                        $this->faq_m->insert_relation($classArray);
					}
				}
                }

                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("faq/index"));
			}
		} else {
			$this->data["subview"] = "faq/add";
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
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['faq'] = $this->faq_m->get_single_faq(array('id' => $id));
            $faq_class = pluck($this->faq_m->get_faq_classes($id),'classes_id');
            $this->data['faq_class'] = $faq_class;
			if(customCompute($this->data['faq'])) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "faq/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$array = array(
							"question" => $this->input->post("question"),
                            "answer" => $this->input->post("answer"),
                            "create_date" => date("Y-m-d h:i:s"),
                            "modify_date" => date("Y-m-d h:i:s"),
                            "create_userID" => $this->session->userdata('loginuserID'),
                            "create_usertypeID" => $this->session->userdata('usertypeID')
						);
                        $classID = $this->input->post('classesID');
                        if($this->faq_m->update_faq($array, $id)){
                            foreach($faq_class as $class){
                            $this->faq_m->delete_faq_relation_by_class_id($id,$class);    
                            }
                        
                            
                            $classArray = [];
                            if($classID) {
                                foreach ($classID as $classesID) {
                                    $classArray = [
                                        'classes_id' => $classesID,
                                        'faq_id'     => $id,	
                                    ];
                                    $this->faq_m->insert_relation($classArray);
                                }
                            }
                        }
						
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("faq/index"));
					}
				} else {
					$this->data["subview"] = "faq/edit";
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

	public function faq_list() { 
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("faq/index/$classID");
			echo $string;
		} else {
			redirect(base_url("faq/index"));
		}
	}

	// Todo: Don't let user delete if there is question bank, subjects
	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$faq = $this->faq_m->get_single_faq(array('id' => $id));
			if(customCompute($faq)) {
				$this->faq_m->delete_faq($id);
				$this->faq_m->delete_faq_relation_byid($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("faq/index"));
			} else {
				redirect(base_url("faq/index"));
			}
		} else {
			redirect(base_url("faq/index"));
		}
	}

	public function unique_class() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_class", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function ajaxSavefaq(){

		$classesID = $this->input->post("classes_id");

		$array = array(
			"question" => $this->input->post("question"),
			"answer" => $this->input->post("answer"),
			"create_date" => date("Y-m-d h:i:s"),
			"create_userID" => $this->session->userdata('loginuserID'),
			"create_usertypeID" => $this->session->userdata('usertypeID')
		);
		if($this->faq_m->insert_faq($array)){
			$faqID = $this->db->insert_id();
			
			$classArray = [
				'classes_id' => $classesID,
				'faq_id'     => $faqID,	
			];
			$this->faq_m->insert_relation($classArray);

			$retArray['status'] = true;
            $retArray['id'] = $faqID;
            echo json_encode($retArray);
            exit;
		}else{
			$retArray['status'] = false;
            echo json_encode($retArray);
            exit;
		}
	}
	

}