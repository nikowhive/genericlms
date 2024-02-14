<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentattendancebyexam extends Admin_Controller {
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
		$this->load->model("exam_m");
		$this->load->model("subject_m");
		$this->load->model("section_m");
		$this->load->model("student_m");
		$this->load->model('marksetting_m');
		$this->load->model('studentrelation_m');
		$this->load->model("studentattendancebyexam_m");
		$this->db->cache_off();
		$language = $this->session->userdata('lang');
		$this->lang->load('studentattendancebyexam', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("studentattendancebyexam_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_examID'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("studentattendancebyexam_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classesID'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("studentattendancebyexam_section"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_sectionID'
			),
		
		);
		return $rules;
	}

	protected function markRules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("studentattendancebyexam_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_examID'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("studentattendancebyexam_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classesID'
            ),
            array(
				'field' => 'sectionID',
				'label' => $this->lang->line("studentattendancebyexam_section"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_sectionID'
			),
			array(
				'field' => 'inputs',
				'label' => $this->lang->line("studentattendancebyexam_subject"),
				'rules' => 'trim|xss_clean|max_length[11]'
			)
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

	        $this->data['set_exam']    = 0;
	        $this->data['set_classes'] = 0;
	        $this->data['set_section'] = 0;
	      

	        $this->data['sendExam']    = [];
	        $this->data['sendClasses'] = [];
	        $this->data['sendSection'] = [];
            $this->data['exams']       = [];
            $this->data['sections']    = [];


	        $classesID = $this->input->post("classesID");
	        if((int)$classesID) {
	        	$this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID);
	            $this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $classesID));
	        } else {
	            $this->data['exams'] = [];
	            $this->data['sections'] = [];
	        }

	        $this->data['classes']  = $this->classes_m->get_classes();

			if($_POST) {
	            $rules = $this->rules();
	            $this->form_validation->set_rules($rules);
	            if ($this->form_validation->run() == FALSE) {
	                $this->data["subview"] = "studentattendancebyexam/add";
	                $this->load->view('_layout_main', $this->data);
	            } else {

					$examID          = $this->input->post('examID');
	                $classesID       = $this->input->post('classesID');
					$sectionID       = $this->input->post('sectionID');
					
					$this->data['set_exam']    = $examID;
			        $this->data['set_classes'] = $classesID;
			        $this->data['set_section'] = $sectionID;

					$exam            = $this->exam_m->get_single_exam(array('examID'=> $examID));
					$classes         = $this->classes_m->get_single_classes(array('classesID'=> $classesID));
	                $section         = $this->section_m->get_single_section(array('sectionID'=> $sectionID));

					$this->data['sendExam']     = $exam;
	                $this->data['sendClasses']  = $classes;
	                $this->data['sendSection']  = $section;

	                $schoolyearID       = $this->session->userdata('defaultschoolyearID');
	                $studentArray = [
	                	'srclassesID'   => $classesID,
	                	'srsectionID'   => $sectionID,
	                	'srschoolyearID'=> $schoolyearID,
                    ];
                    
                    	               
	                $sendStudent = $this->studentrelation_m->get_order_by_student($studentArray);
				    $checkArray = [
						'classID' => $classesID,
						'sectionID' => $sectionID,
						'examID' => $examID,
                    ];	

                    $studentAttendance = $this->studentattendancebyexam_m->getStudentAttendanceByExam($checkArray);
					$newsendStudent = $this->arrangeStudent($sendStudent,$checkArray,$studentAttendance);
                   
                    $this->data['students']         = $newsendStudent;
					$this->data['schoolDays'] = $studentAttendance?$studentAttendance->schooldays:'';
					$this->data["subview"] = "studentattendancebyexam/add";
	                $this->load->view('_layout_main', $this->data);
	            }
	        } else {
	            $this->data["subview"] = "studentattendancebyexam/add";
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
			echo "<option value='0'>", $this->lang->line("studentattendancebyexam_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
		} else {
			echo "<option value='0'>", $this->lang->line("studentattendancebyexam_select_section"),"</option>";
		}
	}

	public function unique_data($data) {
		if($data != '') {
			if($data == '0') {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	public function unique_examID() {
		if($this->input->post('examID') == 0) {
			$this->form_validation->set_message("unique_examID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_classesID() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classesID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_sectionID() {
		if($this->input->post('sectionID') == 0) {
			$this->form_validation->set_message("unique_sectionID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function examcall() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			$exams    = pluck($this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID), 'obj', 'examID');
			echo "<option value='0'>", $this->lang->line("studentattendancebyexam_select_exam"),"</option>";
			if(customCompute($exams)) {
				foreach ($exams as $exam) {
					echo "<option value=".$exam->examID.">".$exam->exam."</option>";
				}
			}
		} else {
			echo "<option value='0'>", $this->lang->line("studentattendancebyexam_select_exam"),"</option>";
		}
	}


	public function sendAttendance() {
		$retArray['status'] = FALSE;
        $retArray['message'] = '';

        if($_POST) {
	        $rules = $this->markRules();
	        $this->form_validation->set_rules($rules);
	        if ($this->form_validation->run() == FALSE) {
	            $retArray = $this->form_validation->error_array();
	            $retArray['status'] = FALSE;
	            echo json_encode($retArray);
	            exit;
	        } else {
                $schoolDays 		= $this->input->post("schooldays");
				$examID 		= $this->input->post("examID");
				$classesID		= $this->input->post("classesID");
				$sectionID 		= $this->input->post("sectionID");
                $inputs 		= $this->input->post("inputs");

                $checkArray = [
                    'classID' => $classesID,
                    'sectionID' => $sectionID,
                    'examID' => $examID,
                ];	

                $studentAttendance = $this->studentattendancebyexam_m->getStudentAttendanceByExam($checkArray);
                if($studentAttendance){
                       //update data
                        $id = $this->studentattendancebyexam_m->update_studentattendancebyExam([
                            'schoolDays' => $schoolDays
                        ],$studentAttendance->studentattendancebyexamID);

                }else{
                        //save data
                        $id = $this->studentattendancebyexam_m->insert_studentattendancebyExam([
                            'examID'    => $examID,
                            'classID' => $classesID,
                            'sectionID' => $sectionID,
                            'schoolDays' => $schoolDays
                        ]);
                }
               
			              
                
            if($studentAttendance){
                    $updateArray = [];
				if(customCompute($inputs)) {
					foreach ($inputs as $key => $value) {
                        $data = explode('-', $value['id']);
                        $prevAttendanceDetail = $this->studentattendancebyexam_m->getStudentAttendanceByExamDetail([
                            'id' => $id,
                            'studentID' => $data[1]
                        ]);
                       
						if(!empty($value['value']) || $value['value'] != "") {
							$updateArray[] = [
                                'ID' => $prevAttendanceDetail->ID,
                                'studentattendancebyexamID' => $id,
								'studentID' => $data[1],
								'presentDays' => $value['value']
							];
						} else {
							$updateArray[] = [
                                'ID' => $prevAttendanceDetail->ID,
                                'studentattendancebyexamID' => $id,
								'studentID' => $data[1],
                                'presentDays' => NULL,
							];
						}
					}
                }
                    if(customCompute($updateArray)) {
                        $this->studentattendancebyexam_m->update_studentattendancebyExamDetails($updateArray,'ID');
                    }
                }else{
                    $saArray = [];
				if(customCompute($inputs)) {
					foreach ($inputs as $key => $value) {
						$data = explode('-', $value['id']);
						if(!empty($value['value']) || $value['value'] != "") {
							$saArray[] = [
                                'ID' => '',
                                'studentattendancebyexamID' => $id,
								'studentID' => $data[1],
								'presentDays' => $value['value']
							];
						} else {
							$saArray[] = [
                                'studentattendancebyexamID' => $id,
								'studentID' => $data[1],
                                'presentDays' => NULL,
                                'ID' => '',
							];
						}
					}
                }
                    if(customCompute($saArray)) {
                        $this->studentattendancebyexam_m->insert_studentattendancebyExamDetails($saArray);
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

	public function arrangeStudent($students,$checkArray,$studentAttendance){

        
		$arrangeStudents = [];
        if($students){
			foreach($students as $student){
                if($studentAttendance){
                    $presentDays = $this->studentattendancebyexam_m->getStudentAttendanceByExamDetail([
                        'id' => $studentAttendance->studentattendancebyexamID,
                        'studentID' =>$student->studentID
                    ]);
                    $pDays = $presentDays->presentdays;
                }else{
                    $pDays = '';
                }

			    $arrangeStudents[] = [
					   'studentID' => $student->studentID,
					   'photo' => $student->photo,
					   'name' => $student->name,
					   'roll' => $student->roll,
					   'presentDays' => $pDays
				];
			}
		}
		return $arrangeStudents;
	}


}