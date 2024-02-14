<?php 
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\DataValidation;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentremark extends Admin_Controller {
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
		$this->load->model("studentremark_m");
		$this->db->cache_off();
		$language = $this->session->userdata('lang');
		$this->lang->load('studentremark', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("studentremark_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_examID'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("studentremark_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classesID'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("studentremark_section"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_sectionID'
			),
		
		);
		return $rules;
	}

	protected function markRules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("studentremark_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_examID'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("studentremark_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classesID'
			),
			array(
				'field' => 'inputs',
				'label' => $this->lang->line("studentremark_subject"),
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
			$graduateclass                    = $this->data['siteinfos']->ex_class;

	        $this->data['set_exam']    = 0;
	        $this->data['set_classes'] = 0;
	        $this->data['set_section'] = 0;
	      

	        $this->data['sendExam']    = [];
	        $this->data['sendClasses'] = [];
	        $this->data['sendSection'] = [];
	        $this->data['exams']       = [];


	        $classesID = $this->input->post("classesID");
	        if((int)$classesID) {
	        	$this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID);
	            $this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $classesID));
	        } else {
	            $this->data['subjects'] = [];
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
					$newsendStudent = $this->arrangeStudent($sendStudent,$checkArray);
					$this->data['students']         = $newsendStudent;
					
					$this->data["subview"] = "studentremark/add";
	                $this->load->view('_layout_main', $this->data);
	            }
	        } else {
	            $this->data["subview"] = "studentremark/add";
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
			echo "<option value='0'>", $this->lang->line("studentremark_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
		} else {
			echo "<option value='0'>", $this->lang->line("studentremark_select_section"),"</option>";
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
			echo "<option value='0'>", $this->lang->line("studentremark_select_exam"),"</option>";
			if(customCompute($exams)) {
				foreach ($exams as $exam) {
					echo "<option value=".$exam->examID.">".$exam->exam."</option>";
				}
			}
		} else {
			echo "<option value='0'>", $this->lang->line("studentremark_select_exam"),"</option>";
		}
	}


	public function remark_send() {
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
				$examID 		= $this->input->post("examID");
				$classesID		= $this->input->post("classesID");
				$sectionID 		= $this->input->post("sectionID");
				$inputs 		= $this->input->post("inputs");
				$schoolyearID 	= $this->data['siteinfos']->school_year;

				$remarkRelationArray = [];
				if(customCompute($inputs)) {
					foreach ($inputs as $key => $value) {
						$data = explode('-', $value['id']);
						if(!empty($value['value']) || $value['value'] != "") {
							$remarkRelationArray[] = [
								'studentID' => $data[1],
								'remark' => $value['value']
							];
						} else {
							$remarkRelationArray[] = [
								'studentID' => $data[1],
								'remark' => NULL
							];
						}
					}
				}

				if(customCompute($remarkRelationArray)) {
					$newArray = [];
					foreach($remarkRelationArray as $a){

						$checkArray = [
							'classID' => $classesID,
							'sectionID' => $sectionID,
							'examID' => $examID,
							'studentID' => $a['studentID'],
						];	
						$newArray = [
							'classID' => $classesID,
							'sectionID' => $sectionID,
							'examID' => $examID,
							'studentID' => $a['studentID'],
							'remarks' => $a['remark']
						];		
                      $checkRecordExist = $this->studentremark_m->checkRecord($checkArray);
					  if(customCompute($checkRecordExist)){
						$this->studentremark_m->update_studentremark($newArray,$checkRecordExist->studentremarkID);
					  }else{
						$this->studentremark_m->insert_studentremark($newArray);
					  }
					}
				}
				$retArray['status'] = TRUE;;
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

	public function arrangeStudent($students,$checkArray){

		$arrangeStudents = [];
        if($students){
			foreach($students as $student){
				$mergeArray = array_merge($checkArray,['studentID' => $student->studentID]);
				$checkRecordExist = $this->studentremark_m->checkRecord($mergeArray);
				$arrangeStudents[] = [
					   'studentID' => $student->studentID,
					   'photo' => $student->photo,
					   'name' => $student->name,
					   'roll' => $student->roll,
					   'remarks' => $checkRecordExist?$checkRecordExist->remarks:''

				];
			}
		}
		return $arrangeStudents;
	}


}