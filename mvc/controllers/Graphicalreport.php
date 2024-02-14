<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Graphicalreport extends Admin_Controller {
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
		$this->load->model('section_m');
		$this->load->model("exam_m");
		$this->load->model("markpercentage_m");
		$this->load->model("subject_m");
		$this->load->model("setting_m");
		$this->load->model("mark_m");
		$this->load->model("grade_m");
		$this->load->model("studentrelation_m");
		$this->load->model("sattendance_m");
		$this->load->model("subjectattendance_m");
		$this->load->model("studentgroup_m");
		$this->load->model("marksetting_m");
		
		$language = $this->session->userdata('lang');
		$this->lang->load('graphicalreport', $language);
	}

	protected function rules() {
		$rules = array(
			
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("graphicalreport_class"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("graphicalreport_section"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("graphicalreport_student"),
				'rules' => 'trim|xss_clean'
			),
		);
		return $rules;
	} 

	public function index() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css',
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
			)
		);
		$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
		$this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID);
		$this->data['classes']  = $this->classes_m->general_get_classes();
		$this->data['settingmarktypeID'] = $settingmarktypeID;

		$this->data["subview"]  = "report/graphical/StudentSubjectView";
		$this->load->view('_layout_main', $this->data);
	}

	public function StudentSubjectview() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css',
				'assets/highchartjs/highchartssv.css',
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
				'assets/highchartjs/highchart.js',
				'assets/highchartjs/exporting.js',
				'assets/highchartjs/export-data.js',
				'assets/highchartjs/accessibility.js',
			)
		);
		$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
		$this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID);
		$this->data['classes']  = $this->classes_m->general_get_classes();
		$this->data['settingmarktypeID'] = $settingmarktypeID;

		$this->data["subview"]  = "report/graphical/StudentSubjectView";
		$this->load->view('_layout_main', $this->data);
	}

	public function StudentLineview() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css',
				'assets/highchartjs/highchartslv.css',
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
				'assets/highchartjs/highchart.js',
				'assets/highchartjs/series-label.js',
				'assets/highchartjs/exporting.js',
				'assets/highchartjs/export-data.js',
				'assets/highchartjs/accessibility.js',

			)
		);
		$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
		$this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID);
		$this->data['classes']  = $this->classes_m->general_get_classes();
		$this->data['settingmarktypeID'] = $settingmarktypeID;

		$this->data["subview"]  = "report/graphical/StudentLineView";
		$this->load->view('_layout_main', $this->data);
	}

	public function StudentClassview() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css',
				'assets/highchartjs/highchartssv.css',
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
				'assets/highchartjs/highchart.js',
				'assets/highchartjs/exporting.js',
				'assets/highchartjs/export-data.js',
				'assets/highchartjs/accessibility.js',
			)
		);
		$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
		$this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID);
		$this->data['classes']  = $this->classes_m->general_get_classes();
		$this->data['settingmarktypeID'] = $settingmarktypeID;

		$this->data["subview"]  = "report/graphical/StudentClassView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getStudentSubject () {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('terminalreport')) {
			if($_POST) {
				$examID       = $this->input->post('examID');
				$classesID    = $this->input->post('classesID');
				$sectionID    = $this->input->post('sectionID');
				$studentID    = $this->input->post('studentID');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->data['examID']     = $examID;
					$this->data['classesID']  = $classesID;
					$this->data['sectionID']  = $sectionID;
					$this->data['studentIDD'] = $studentID;
                    
                    

                    $this->db->select('subjectName,studentName,roll_no,classesName,sectionName');
                    $this->db->from('marksentries');
                    $this->db->where(['classesID'=>$classesID,'sectionID'=>$sectionID,'studentID'=>$studentID,'schoolyearID'=>$schoolyearID]);
                    $this->db->order_by('marksentriesID','ASC');
                    $this->db->group_by('subjectID');
                    $query = $this->db->get();
                    $outputs = $query->result();
                    $subject = [];
                    $results = [];
                    if($outputs){
	                    foreach($outputs as $output){
	                        $subject[] = $output->subjectName;
	                        
	                    }
	                    $this->data['subject'] = json_encode($subject);
	                    $this->data['studentName'] = $outputs[0]->studentName;
	                    $this->data['roll_no'] = $outputs[0]->roll_no;
	                    $this->data['classesName'] = $outputs[0]->classesName;
	                    $this->data['sectionName'] = $outputs[0]->sectionName;
	                    //print_r($outputs);die();
	                    $this->db->select('examName');
	                    $this->db->from('marksentries');
	                    $this->db->where(['classesID'=>$classesID,'sectionID'=>$sectionID,'studentID'=>$studentID,'schoolyearID'=>$schoolyearID]);
	                    $this->db->order_by('marksentriesID','ASC');
	                    $this->db->group_by('examID');
	                    $query = $this->db->get();
	                    $outputexams = $query->result();
	                    foreach($outputexams as $outputexam){
	                        $this->db->select('gpa');
		                    $this->db->from('marksentries');
		                    $this->db->where(['classesID'=>$classesID,'sectionID'=>$sectionID,'studentID'=>$studentID,'examName'=>$outputexam->examName,'schoolyearID'=>$schoolyearID]);
		                    $this->db->order_by('marksentriesID','ASC');
		                 
		                    $query = $this->db->get();
		                    $outputgpas = $query->result_array();
		                    
		                    foreach ($outputgpas as $value) {
							    $results[] = (float)$value['gpa'];//array_merge($results, $value);
							}
							
	                        $gpaarray[] = array(
	                        'name'=>$outputexam->examName,'data'=>$results
	                        );
		                    
	                    }

                    } else {

                    	$this->data['subject'] = json_encode($subject);
	                    $this->data['studentName'] = '';
	                    $this->data['roll_no'] = '';
	                    $this->data['classesName'] = '';
	                    $this->data['sectionName'] = '';
	                    $gpaarray[] = array(
	                        'name'=>'','data'=>$results
	                        );

                    }
                    
                    $this->data['gpaarray']=json_encode($gpaarray);
					$retArray['render'] = $this->load->view('report/graphical/StudentSubjectReport',$this->data,true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit();
				}
			} else {
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
			$retArray['status'] = TRUE;
			echo json_encode($retArray);
			exit;
		}
	}

	public function getStudentLine () {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('terminalreport')) {
			if($_POST) {
				$examID       = $this->input->post('examID');
				$classesID    = $this->input->post('classesID');
				$sectionID    = $this->input->post('sectionID');
				$studentID    = $this->input->post('studentID');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->data['examID']     = $examID;
					$this->data['classesID']  = $classesID;
					$this->data['sectionID']  = $sectionID;
					$this->data['studentIDD'] = $studentID;

                    $this->db->select('subjectName,studentName,roll_no,classesName,sectionName');
                    $this->db->from('marksentries');
                    $this->db->where(['classesID'=>$classesID,'sectionID'=>$sectionID,'studentID'=>$studentID,'schoolyearID'=>$schoolyearID]);
                    $this->db->order_by('marksentriesID','ASC');
                    $this->db->group_by('subjectID');
                    $query = $this->db->get();
                    $outputs = $query->result();
                    $exams = [];
                    $results1 = [];
                    if($outputs){
	                    $this->data['studentName'] = $outputs[0]->studentName;
	                    $this->data['roll_no'] = $outputs[0]->roll_no;
	                    $this->data['classesName'] = $outputs[0]->classesName;
	                    $this->data['sectionName'] = $outputs[0]->sectionName;
	                    
	                    $this->db->select('examName');
	                    $this->db->from('marksentries');
	                    $this->db->where(['classesID'=>$classesID,'sectionID'=>$sectionID,'studentID'=>$studentID,'schoolyearID'=>$schoolyearID]);
	                    $this->db->order_by('marksentriesID','ASC');
	                    $this->db->group_by('examID');
	                    $query = $this->db->get();
	                    $outputexams = $query->result();
	                    $results1 = [];
	                    foreach($outputexams as $outputexam){
	                    	
	                        $this->db->select('gpa');
		                    $this->db->from('marksentries');
		                    $this->db->where(['classesID'=>$classesID,'sectionID'=>$sectionID,'studentID'=>$studentID,'examName'=>$outputexam->examName]);
		                    $this->db->order_by('marksentriesID','ASC');
		                 
		                    $query = $this->db->get();
		                    $outputgpas = $query->result_array();
		                    $results = [];
		                    $i=1;
		                    foreach ($outputgpas as $value) {
							    $results[] = (float)$value['gpa'];//array_merge($results, $value);
							    $i++;
							}

							$exams[] = $outputexam->examName;
							$results1[] = round(array_sum($results)/$i,2);
							
	                        
		                    
	                    }
                    } else {
                    	$this->data['studentName'] = '';
	                    $this->data['roll_no'] = '';
	                    $this->data['classesName'] = '';
	                    $this->data['sectionName'] = '';
                    }
                    //print_r($results1);die();
                    $gpaarray[] = array(
                            'name'=>'CGPA','data'=>$results1
                        );
                    $this->data['exams'] = json_encode($exams);
                    $this->data['gpaarray']=json_encode($gpaarray);
					$retArray['render'] = $this->load->view('report/graphical/StudentLineReport',$this->data,true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit();
				}
			} else {
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
			$retArray['status'] = TRUE;
			echo json_encode($retArray);
			exit;
		}
	}

	public function getStudentClass () {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if(permissionChecker('terminalreport')) {
			if($_POST) {
				$examID       = $this->input->post('examID');
				$classesID    = $this->input->post('classesID');
				$sectionID    = $this->input->post('sectionID');
				
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$this->data['examID']     = $examID;
					$this->data['classesID']  = $classesID;
					$this->data['sectionID']  = $sectionID;
                    $this->db->select('studentID,studentName,classesName,sectionName,examName');
                    $this->db->from('marksentries');
                    $this->db->where(['classesID'=>$classesID,'sectionID'=>$sectionID,'examID'=>$examID,'schoolyearID'=>$schoolyearID]);
                    $this->db->order_by('marksentriesID','ASC');
                    $this->db->group_by('studentID');
                    $query = $this->db->get();
                    $outputs = $query->result();
                    $student = [];
                    $results1 = [];
                    if($outputs) {
	                    foreach($outputs as $output){
	                        $student[] = $output->studentName;
	                        $studentIDs[] = $output->studentID;
	                    }
	                    $this->data['student'] = json_encode($student);
	                    $this->data['examName'] = $outputs[0]->examName;
	                    $this->data['classesName'] = $outputs[0]->classesName;
	                    $this->data['sectionName'] = $outputs[0]->sectionName;

	                    foreach($studentIDs as $studentID){
	                        $this->db->select('gpa');
		                    $this->db->from('marksentries');
		                    $this->db->where(['classesID'=>$classesID,'sectionID'=>$sectionID,'examID'=>$examID,'studentID'=>$studentID,'schoolyearID'=>$schoolyearID]);
		                    $this->db->order_by('marksentriesID','ASC');
		                    //$this->db->group_by('examID');
		                    $query = $this->db->get();
		                    $outputexams = $query->result();
	                        $result = [];
	                        $i=0;
	                        foreach($outputexams as $outputexam){
	                        	$result[] = $outputexam->gpa;
	                        	$i++;
	                        }
	                        
	                        $results1[] = round(array_sum($result)/$i,2);
		                   	
	                    } 
                    } else {
                    	$this->data['student'] = json_encode($student);
	                    $this->data['examName'] = '';
	                    $this->data['classesName'] = '';
	                    $this->data['sectionName'] = '';
                    }
                    $cgpaarray[] = array(
                            'name'=>'CGPA','data'=>$results1
                        );
                    $this->data['gpaarray']=json_encode($cgpaarray);
					$retArray['render'] = $this->load->view('report/graphical/StudentClassReport',$this->data,true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit();
				}
			} else {
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
			$retArray['status'] = TRUE;
			echo json_encode($retArray);
			exit;
		}
	}

	public function unique_data($data) {
		if($data != "") {
			if($data === "0") {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
		} 
		return TRUE;
	}
}	