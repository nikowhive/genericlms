<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class StudentAttendance extends Admin_Controller {

    function __construct () {
		parent::__construct();
		$this->load->model("student_m");
		$this->load->model("classes_m");
		$this->load->model("section_m");
		$this->load->model('studentrelation_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('studentattendance', $language);
	}
	
	protected function rules() {
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("studentattendance_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("studentattendance_section"),
				'rules' => 'trim|required|xss_clean|max_length[11]'
			),
			
		);
		
		return $rules;
	}

    
  
	public function index() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);
			$this->data['students']           = [];
			$graduateclass                    = $this->data['siteinfos']->ex_class;
			
	        $this->data['set_classes'] = 0;
	        $this->data['set_section'] = 0;

	       
	        $this->data['sendClasses'] = [];
	        $this->data['sendSection'] = [];

	        $classesID = $this->input->post("classesID");
	        if((int)$classesID) {
	            $this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $classesID));
	        } else {
	            $this->data['sections'] = [];
	        }

			$this->data['classes']  = $this->classes_m->get_classes();
			// $this->classes_m->get_order_by_classes_except_kg(['classesID !='=> $graduateclass]);

			if($_POST) {
	            $rules = $this->rules();
	            $this->form_validation->set_rules($rules);
	            if ($this->form_validation->run() == FALSE) {
	                $this->data["subview"] = "studentattendance/add";
	                $this->load->view('_layout_main', $this->data);
	            } else {
	                $classesID       = $this->input->post('classesID');
					$sectionID       = $this->input->post('sectionID');
					
			        $this->data['set_classes'] = $classesID;
			        $this->data['set_section'] = $sectionID;

	                $classes         = $this->classes_m->get_single_classes(array('classesID'=> $classesID));
	                $section         = $this->section_m->get_single_section(array('sectionID'=> $sectionID));

	                $this->data['sendClasses']  = $classes;
	                $this->data['sendSection']  = $section;

	                $schoolyearID       = $this->session->userdata('defaultschoolyearID');
	                $studentArray = [
	                	'srclassesID'   => $classesID,
	                	'srsectionID'   => $sectionID,
	                	'srschoolyearID'=> $schoolyearID,
	                ];
	                $sendStudent = $this->studentrelation_m->get_order_by_student($studentArray);
					$this->data['students']         = $sendStudent;
					
					$this->data["subview"] = "studentattendance/add";
	                $this->load->view('_layout_main', $this->data);
	            }
	        } else {
	            $this->data["subview"] = "studentattendance/add";
	            $this->load->view('_layout_main', $this->data);
	        }
	      
		} else {
			$this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
		}
	}

	public function sectioncall() {
		$id = $this->input->post('id');
		if((int)$id) {
			$allsection = $this->section_m->get_order_by_section(array("classesID" => $id));
			echo "<option value='0'>", $this->lang->line("studentattendance_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
		} else {
			echo "<option value='0'>", $this->lang->line("studentattendance_select_section"),"</option>";
		}
	}


	protected function sattendanceRules() {
		$rules = array(
			
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("studentattendance_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]'
			),
			
			array(
				'field' => 'inputs',
				'label' => $this->lang->line("studentattendance_subject"),
				'rules' => 'trim|xss_clean|max_length[11]'
			)
		);
		return $rules;
	}


	public function studentAttendance_send() {
		$retArray['status'] = FALSE;
        $retArray['message'] = '';

        if($_POST) {
	        $rules = $this->sattendanceRules();
	        $this->form_validation->set_rules($rules);
	        if ($this->form_validation->run() == FALSE) {
	            $retArray = $this->form_validation->error_array();
	            $retArray['status'] = FALSE;
	            echo json_encode($retArray);
	            exit;
	        } else {
				$classesID		= $this->input->post("classesID");
				$inputs 		= $this->input->post("inputs");

				$studentArray = [];
				if(customCompute($inputs)) {
					foreach ($inputs as $key => $value) {
						$data = explode('-', $value['days']);
						if(!empty($value['value']) || $value['value'] != "") {
							$studentArray[] = [
								'studentID' => $data[1],
								'presentdays' => abs($value['value'])
							];
						} else {
							$studentArray[] = [
								'studentID' => $data[1],
								'presentdays' => NULL
							];
						}
					}
				}
				if(customCompute($studentArray)) {
					    foreach($studentArray as $s){
							$this->student_m->update_student($s, $s['studentID']);
						}
				}
				$retArray['status'] = TRUE;
				$retArray['message'] = 'Success';
				echo json_encode($retArray);
            	exit;
	        }
	    } else {
			$retArray['message'] = 'Something wrong';
            echo json_encode($retArray);
            exit;
	    }
	}


	

}