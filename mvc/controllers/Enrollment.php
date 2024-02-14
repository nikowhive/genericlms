<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Enrollment extends Admin_Controller {
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
		$this->load->model("enrollment_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('enrollment', $language);	
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'classesID', 
				'label' => $this->lang->line("enrollment_class"), 
				'rules' => 'trim|xss_clean|callback_unique_classes'
			), 
            array(
				'field' => 'from_month', 
				'label' => $this->lang->line("enrollment_from_month"), 
				'rules' => 'trim|required|xss_clean|max_length[7]|callback_month_valid'
			), 
            array(
				'field' => 'to_month', 
				'label' => $this->lang->line("enrollment_to_month"), 
				'rules' => 'trim|required|xss_clean|max_length[7]|callback_month_valid'
			),
			array(
				'field' => 'start_date', 
				'label' => $this->lang->line("enrollment_start_date"), 
				'rules' => 'trim|required|xss_clean|max_length[10]'
			)
		);
		return $rules;
	}

	public function index() {
		$this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js'  => array(
                'assets/select2/select2.js',
            ),
        );
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['set'] = $id;
			$this->data['classes'] = $this->classes_m->get_classes();
			$fetchClass = pluck($this->data['classes'], 'classesID', 'classesID');
			if(isset($fetchClass[$id])) {
				$this->data['enrollment'] = $this->enrollment_m->get_enrollment_by_class($fetchClass[$id]);
				$this->data['classesID'] =$fetchClass[$id] ;
				$this->data["subview"] = "enrollment/index";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data['set'] = 0;
				$this->data['classesID'] = [];
				$this->data['enrollment'] = [];
				$this->data['classes'] = $this->classes_m->get_classes();
				// dd($this->data);
				$this->data["subview"] = "enrollment/index";
				$this->load->view('_layout_main', $this->data);
			}
		}
		else{
			$this->data['set'] = '0';
			$this->data['classesID'] = [];
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['enrollment'] = $this->enrollment_m->get_enrollment();
			$this->data["subview"] = "enrollment/index";
			$this->load->view('_layout_main', $this->data);
		}
		
	} 

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
                'assets/datepicker/datepicker.css',
			),
			'js' => array(
				'assets/select2/select2.js',
                'assets/datepicker/datepicker.js'
			)
		);

		$this->data['classes'] = $this->classes_m->get_classes();
        $this->data['classesID'] = 0;
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) { 
				$this->data["subview"] = "enrollment/add";
				$this->load->view('_layout_main', $this->data);			
			} else {
				$array = array(
					"title" => $this->input->post("title"),
					"from_month" => $this->input->post("from_month"),
                    "to_month" => $this->input->post("to_month"),
					"start_date" => $this->input->post("start_date"),
					"create_at" => date("Y-m-d h:i:s"),
					"updated_at" => date("Y-m-d h:i:s"),
					"create_userID" => $this->session->userdata('loginuserID'),
					"create_usertype" => $this->session->userdata('usertype')
				);

				if($this->enrollment_m->insert_enrollment($array)){
                    $enrollID = $this->db->insert_id();
                    $classids = $this->input->post("classesID");
                    foreach($classids as $class)
                    {
                        $relarray = array(
                            "class_id" => $class,
                            "enrollment_id" => $enrollID
                        );
                        $this->enrollment_m->insert_enrollment_rel($relarray);
                    }
                }
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("enrollment/index"));
			}
		} else {
			$this->data["subview"] = "enrollment/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/datepicker/datepicker.css',
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/datepicker/datepicker.js'
			)
		);
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
            $classes = pluck($this->enrollment_m->get_enrollment_class($id),'class_id');
            $this->data['classesID'] = $classes;
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['enrollment'] = $this->enrollment_m->get_single_enrollment(array('id' => $id));
			if(customCompute($this->data['enrollment'])) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "enrollment/edit";
						$this->load->view('_layout_main', $this->data);			
					} else {
						$array = array(
							"title" => $this->input->post("title"),
							"from_month" => $this->input->post("from_month"),
                            "to_month" => $this->input->post("to_month"),
							"start_date" => $this->input->post("start_date"),
                            "create_at" => date("Y-m-d h:i:s"),
                            "updated_at" => date("Y-m-d h:i:s"),
                            "create_userID" => $this->session->userdata('loginuserID'),
                            "create_usertype" => $this->session->userdata('usertype')
						);

						$this->enrollment_m->update_enrollment($array, $id);
                        $this->enrollment_m->delete_enrollment_relation_byid($id);
                        $classids = $this->input->post("classesID");
                        foreach($classids as $class)
                        {
                            $relarray = array(
                                "class_id" => $class,
                                "enrollment_id" => $id
                            );
                            $this->enrollment_m->insert_enrollment_rel($relarray);
                        }
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("enrollment/index"));
					}
				} else {
					$this->data["subview"] = "enrollment/edit";
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
			$enrollment = $this->enrollment_m->get_single_enrollment(array('id' => $id));
			if(customCompute($enrollment)) {
				$this->enrollment_m->delete_enrollment($id);
                $this->enrollment_m->delete_enrollment_relation_byid($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("enrollment/index"));
			} else {
				redirect(base_url("enrollment/index"));
			}
		} else {
			redirect(base_url("enrollment/index"));
		}
	}

	public function unique_classes() {
		$error = 0;
		$classes = $this->input->post('classesID');
		if(customCompute($classes)) {
			foreach($classes as $class) {
				$classID = $class;
				$class = $this->classes_m->general_get_single_classes(array('classesID' => $classID));
				if(!customCompute($class)) {
					$error++;
				}
			}

			if($error == 0) {
				return TRUE;
			} else {
				$this->form_validation->set_message("unique_classes", "The %s is required.");
	     		return FALSE;
			}
		} else {
			$this->form_validation->set_message("unique_classes", "The %s is required.");
	     	return FALSE;
		}
	}
    public function month_valid($date) {
        if($date) {
            if(strlen($date) <7) {
                $this->form_validation->set_message("month_valid", "%s is not valid mm-yyyy");
                return FALSE;
            } else {
                $arr = explode("-", $date);
                $mm = $arr[0];
                $yyyy = $arr[1];
                if(checkdate($mm, 11, $yyyy)) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message("month_valid", "%s is not valid mm-yyyy");
                    return FALSE;
                }
            }
        }
        return TRUE;
    }
	public function enrollment_list()
    {
        $classID = $this->input->post('id');
        if ((int) $classID) {
            $string = base_url("enrollment/index/$classID");
            echo $string;
        } else {
            redirect(base_url("enrollment/index"));
        }
    }

	public function ajaxSaveEnrollment(){

		$classesID = $this->input->post("classes_id");

		$array = array(
			"title" => $this->input->post("title"),
			"from_month" => $this->input->post("from_month"),
			"to_month" => $this->input->post("to_month"),
			"start_date" => $this->input->post("start_date"),
			"create_at" => date("Y-m-d h:i:s"),
			"create_userID" => $this->session->userdata('loginuserID'),
			"create_usertype" => $this->session->userdata('usertypeID')
		);
		
		if($this->enrollment_m->insert_enrollment($array)){
			$insert_id = $this->db->insert_id();
			
			$classArray = [
				'class_id' => $classesID,
				'enrollment_id'     => $insert_id,	
			];
			$this->enrollment_m->insert_enrollment_rel($classArray);

			$retArray['status'] = true;
            $retArray['id'] = $insert_id;
            echo json_encode($retArray);
            exit;
		}else{
			$retArray['status'] = false;
            echo json_encode($retArray);
            exit;
		}
	}
}