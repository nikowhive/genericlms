<?php 
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use \PhpOffice\PhpSpreadsheet\Style\Border;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Finalterminalreport extends Admin_Controller {
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
		$this->load->model("studentremark_m");
		$this->load->model("studentrelation_m");
		$this->load->model("sattendance_m");
		$this->load->model("subjectattendance_m");
		$this->load->model("studentgroup_m");
		$this->load->model("marksetting_m");
		$this->load->model("subjectmark_m");
		$this->load->model("examtermsetting_m");
		$this->load->helper('nepali_calendar_helper');

		$language = $this->session->userdata('lang');
		$this->lang->load('terminalreport', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("terminalreport_exam"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("terminalreport_class"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("terminalreport_section"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("terminalreport_student"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'date',
				'label' => $this->lang->line("terminalreport_date"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'class_teacher',
				'label' => $this->lang->line("terminalreport_class_teacher"),
				'rules' => 'trim|xss_clean|callback_class_teacher_upload'
			),
			array(
				'field' => 'incharge',
				'label' => $this->lang->line("terminalreport_incharge"),
				'rules' => 'trim|xss_clean|callback_incharge_upload'
			),
		);
		return $rules;
	} 

	protected function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("terminalreport_exam"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("terminalreport_class"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("terminalreport_section"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("terminalreport_student"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line("terminalreport_to"),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("terminalreport_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("terminalreport_message"),
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
				'assets/datepicker/datepicker.css'
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
				'assets/datepicker/datepicker.js'
			)
		);
		
		$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
		$this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID);
		$this->data['classes']  = $this->classes_m->get_order_by_classes_except_kg();
		// $this->data['classes']  = $this->classes_m->get_classes();
		$this->data['settingmarktypeID'] = $settingmarktypeID;
		$this->data['date'] = date("d-m-Y");

		$this->data["subview"]  = "report/finalterminal/TerminalReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getTerminalreport () {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		
		if(permissionChecker('terminalreport')) {
			if($_POST) {
				$date       = $this->input->post('date');
				$verified_by  = $this->input->post('verified_by');
				$school_days  = $this->input->post('school_days');
				$finaltermexamID       = $this->input->post('examID');
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
					$array['class_teacher']    = $this->upload_data['class_teacher']['file_name'];	
					$array['incharge']    = $this->upload_data['incharge']['file_name'];
					$this->setting_m->insertorupdate($array);	

					$this->data['setting'] = $this->setting_m->get_setting();
					$this->data['examID']     = $finaltermexamID;
					$this->data['classesID']  = $classesID;
					$this->data['sectionID']  = $sectionID;
					$this->data['studentIDD'] = $studentID;
					$this->data['date'] 	  = $date;
					$this->data['verified_by'] = $verified_by;
					$this->data['school_days'] = $school_days;

					$queryArray        = [];
					$studentQueryArray = [];
					$queryArray['schoolyearID']          = $schoolyearID;
					$studentQueryArray['srschoolyearID'] = $schoolyearID;

					if((int)$classesID > 0) {
						$queryArray['classesID'] = $classesID;
						$studentQueryArray['srclassesID'] = $classesID;
					} 
					if((int)$sectionID > 0) {
						$queryArray['sectionID'] = $sectionID;
						$studentQueryArray['srsectionID'] = $sectionID;
					}
					if((int)$studentID > 0) {
						$studentQueryArray['srstudentID'] = $studentID;
					}

					$class = $this->classes_m->get_single_classes(['classesID' => $classesID]);
					$this->data['class'] = $class;
					$exam      = $this->exam_m->get_single_exam(['examID'=> $finaltermexamID]);
					$this->data['exam']         = $exam;
					$this->data['exam']->date_in_nepali = $this->convertDateToNepaliInEnglish($exam->date);
					$this->data['exam']->issue_date_in_english = $this->convertDateToEnglishInNepali($exam->issue_date);
					$this->data['examName']     = $exam->exam;
					$this->data['grades']       = $this->grade_m->get_grade();
					$this->data['classes']      = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections']     = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->data['class_teacher']= pluck($this->section_m->get_join_sections(),'name','sectionID');
					$this->data['groups']       = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
					$this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);
					$this->data['remarks'] 		= pluck($this->studentremark_m->get_order_by_studentremark(['examID' => $finaltermexamID, 'classID' => $classesID]), 'remarks', 'studentID');
					$students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
				
					$mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
					$coscholasticSubjects      = $this->subject_m->get_subject_only_coscholastic(array('classesID' => $classesID, 'type' => 1));
					
					$this->data['mandatorySubjects'] = $mandatorySubjects;
					$this->data['coscholasticSubjects'] = $coscholasticSubjects;

					$this->subject_m->order('type DESC');
					$this->data['subjects'] = $this->subject_m->get_by_class_id($classesID);
					$this->data['subject_marks'] = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $finaltermexamID,'class_id' => $classesID]), 'fullmark', 'subject_id');
		
					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$settingmarktypeID1      = $this->data['siteinfos']->marktypeID;
					$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
					$markpercentagesmainArr1 = $this->marksetting_m->get_marksetting_markpercentages();

					$examtermSettings = $this->examtermsetting_m->get_examtermsetting_with_examtermsettingrelation2([
						'classesID' => $classesID,
						'schoolyearID' => $schoolyearID,
						'finaltermexamID' => $finaltermexamID
					]);
					
			        $newstudentPositionarray = [];
			        $newstudentPositionarray1 = [];
					$newExamwiseSubjectMark = [];
			        if(customCompute($examtermSettings)){

			         	$this->data['examtermSettings'] = $examtermSettings;

                        foreach($examtermSettings as $examtermSetting){
							$examID = $examtermSetting->examID;
							if((int)$examID > 0) {
								$queryArray['examID'] = $examID;
							} 

							$examwiseSubjectMark = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID,'class_id' => $classesID]), 'fullmark', 'subject_id');
				
					        $marks                  = $this->mark_m->student_all_mark_array($queryArray);
					        $accmarkpercentagesArr[$examID] = $markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
					        $accmarkpercentagesArr1[$examID] = $markpercentagesArr1     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
					
							if(!$markpercentagesArr){
								$retArray['status'] = FALSE;
								$retArray['errorMessage'] = "Mismatch setting! please check mark setting and final term setting.";
								echo json_encode($retArray);
								exit;
							}
							$this->data['markpercentagesArr']  = $accmarkpercentagesArr;
							$this->data['settingmarktypeID']   = $settingmarktypeID;

							$this->data['markpercentagesArr1']  = $accmarkpercentagesArr1;
							$this->data['settingmarktypeID1']   = $settingmarktypeID;

							$retMark = [];
							if(customCompute($marks)) {
								foreach ($marks as $mark) {
									$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
								}
							}

							$retMark1 = [];
							if(customCompute($marks)) {
								foreach ($marks as $mark) {
									$retMark1[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
								}
							}

							$highestMarks    = [];
							foreach ($marks as $value) {
								if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
									$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
								}
								$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
							}

					        $this->data['highestmarks']      = $highestMarks;

							$studentPosition             = [];
							$studentChecker              = [];
							$studentClassPositionArray   = [];
							$studentSubjectPositionArray = [];
							$markpercentagesCount        = 0;

							$studentPosition1             = [];
							$studentChecker1              = [];
							$studentClassPositionArray1   = [];
							$studentSubjectPositionArray1 = [];
							$markpercentagesCount1        = 0;

							if(customCompute($this->data['studentLists'])) {
								foreach($this->data['studentLists'] as $student) {
									$student->dob_in_bs = $this->convertDateToNepaliInEnglish($student->dob);
								}
							}

							if(customCompute($students)) {
								foreach ($students as $student) {
									$opuniquepercentageArr = [];
									$anotheropuniquepercentageArr = [];
									if($student->sroptionalsubjectID > 0) {
										$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
									}

									if($student->sranotheroptionalsubjectID > 0) {
										$anotheropuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
									}

									$opuniquepercentageArr1 = [];
									if($student->sroptionalsubjectID > 0) {
										$opuniquepercentageArr1 = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
									}

									$anotheropuniquepercentageArr1 = [];
									if($student->sranotheroptionalsubjectID > 0) {
										$anotheropuniquepercentageArr1 = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
									}

									$studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;

									$studentPosition1[$student->srstudentID]['totalSubjectMark'] = 0;

									if(customCompute($mandatorySubjects)) {
										foreach ($mandatorySubjects as $mandatorySubject) {
											$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];
											$markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
											$markpercentagesCount = customCompute($markpercentages);
											if(customCompute($markpercentages)) {
												foreach ($markpercentages as $markpercentageID) {
													
													$f = false;
													if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
														$f = true;
													}

													if(isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
														if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
															$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
														}
													} else {
														if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
															$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
														}
													}

													if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
														$studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

														if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];

														}
													}

													$f = false;
													if(customCompute($opuniquepercentageArr)) {
														if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
															$f = true;
														}
													}
													if(customCompute($anotheropuniquepercentageArr)) {
														if(isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
															$f = true;
														}
													}

													if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
														if($student->sroptionalsubjectID != 0) {
															if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
																if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
																}
															} else {
																if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
																}
															}

															if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

																if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
																} else {
																	if($f) {
																		$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
																	}
																}

															}
														}
														if($student->sranotheroptionalsubjectID != 0) {
															if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
																if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
																}
															} else {
																if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
																}
															}

															if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

																if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
																} else {
																	if($f) {
																		$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
																	}
																}

															}
														}
														$studentChecker['subject'][$student->srstudentID][$markpercentageID] = TRUE;
													}
												}
											}

											$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];

											if(!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
												if($student->sroptionalsubjectID != 0) {
													$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
												}
												if($student->sranotheroptionalsubjectID != 0) {
													$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
												}
												$studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
											}

											$studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
											if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
												if($student->sroptionalsubjectID != 0) {
													$studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
												}
												if($student->sranotheroptionalsubjectID != 0) {
													$studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
												}
											}
										}
									}	

									if(customCompute($coscholasticSubjects)) {
										foreach ($coscholasticSubjects as $coscholasticSubject) {
											$uniquepercentageArr1 = isset($markpercentagesArr1[$coscholasticSubject->subjectID]) ? $markpercentagesArr1[$coscholasticSubject->subjectID] : [];

											$markpercentages1 = $uniquepercentageArr1[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
											$markpercentagesCount1 = customCompute($markpercentages);
											if(customCompute($markpercentages1)) {
												foreach ($markpercentages1 as $markpercentageID) {
													$f = false;
													if(isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
														$f = true;
													}

													if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID])) {
														if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
															$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += 0;
														}
													} else {
														if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
															$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = 0;
														}
													}

													if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
														$studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];

														if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];

														}
													}

													$f = false;
													if(customCompute($opuniquepercentageArr1)) {
														if(isset($opuniquepercentageArr1['own']) && in_array($markpercentageID, $opuniquepercentageArr1['own'])) {
															$f = true;
														}
													}

													if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
														if($student->sroptionalsubjectID != 0) {
															if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
																if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
																}
															} else {
																if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
																}
															}

															if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																$studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

																if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
																} else {
																	if($f) {
																		$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
																	}
																}

															}
														}

														if($student->sranotheroptionalsubjectID != 0) {
															if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
																if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
																}
															} else {
																if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
																}
															}

															if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																$studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

																if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
																} else {
																	if($f) {
																		$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
																	}
																}

															}
														}

														$studentChecker1['subject'][$student->srstudentID][$markpercentageID] = TRUE;
													}
												}
											}

											$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];

											if(!isset($studentChecker1['totalSubjectMark'][$student->srstudentID])) {
												// if($student->sroptionalsubjectID != 0) {
												// 	$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
												// }
												$studentChecker1['totalSubjectMark'][$student->srstudentID] = TRUE;
											}

											$studentSubjectPositionArray1[$coscholasticSubject->subjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];
											if(!isset($studentChecker1['studentSubjectPositionArray'][$student->srstudentID])) {
												// if($student->sroptionalsubjectID != 0) {
												// 	$studentSubjectPositionArray1[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
												// }
											}
										}
									}


									$studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
									$studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

									if(isset($studentPosition['totalStudentMarkAverage'])) {
										$studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
									} else {
										$studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
									}

									if(count($coscholasticSubjects) > 0) {
										$studentPosition1[$student->srstudentID]['classPositionMark'] = ($studentPosition1[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition1[$student->srstudentID]['subjectMark']));
										$studentClassPositionArray1[$student->srstudentID]             = $studentPosition1[$student->srstudentID]['classPositionMark'];	
									
										if(isset($studentPosition1['totalStudentMarkAverage'])) {
											$studentPosition1['totalStudentMarkAverage'] += $studentPosition1[$student->srstudentID]['classPositionMark'];
										} else {
											$studentPosition1['totalStudentMarkAverage']  = $studentPosition1[$student->srstudentID]['classPositionMark'];
										}
									}
									
									
								}
							}

							arsort($studentClassPositionArray);
							$studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
							if(customCompute($studentSubjectPositionArray)) {
								foreach($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
									arsort($studentSubjectPositionMark);
									$studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
								}
							}
							if((int)$studentID > 0) {
								$queryArray['studentID'] = $studentID;
							}

							arsort($studentClassPositionArray1);
							$studentPosition1['studentClassPositionArray'] = $studentClassPositionArray1;
							if(customCompute($studentSubjectPositionArray1)) {
								foreach($studentSubjectPositionArray1 as $subjectID => $studentSubjectPositionMark1) {
									arsort($studentSubjectPositionMark1);
									$studentPosition1['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark1;
								}
							}
							if((int)$studentID > 0) {
								$queryArray['studentID'] = $studentID;
							}

							$newstudentPositionarray[$examID] = $studentPosition;
							$newstudentPositionarray1[$examID] = $studentPosition1;
							$newExamwiseSubjectMark[$examID]   = $examwiseSubjectMark; 

					}}


					$this->data['examwise_subject_marks']   = $newExamwiseSubjectMark;
					$this->data['col']             = 5 + $markpercentagesCount;
					$this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
					$this->data['studentPosition'] = $newstudentPositionarray;
					$this->data['percentageArr']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

					$this->data['col1']             = 5 + $markpercentagesCount1;
					$this->data['studentPosition1'] = $newstudentPositionarray1;
					$this->data['percentageArr1']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
                    

					 $retArray['render'] = $this->load->view('report/finalterminal/TerminalReport',$this->data,true);
					 
					// if($class->classes_numeric == 1 || $class->classes_numeric == 2 || $class->classes_numeric == 3) {
						// $retArray['render'] = $this->load->view('report/terminal1/PrimaryTerminalReport',$this->data,true);
					// } else if($class->classes_numeric == 11 || $class->classes_numeric == 12) {	
					// 	$retArray['render'] = $this->load->view('report/terminal1/TerminalReport_11_12',$this->data,true);
					// } else {
							// }
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

	private function get_student_attendance($queryArray, $subjects, $studentlists) {
		unset($queryArray['examID']);
		$newArray = [];
		$attendanceArray = [];
		$getWeekendDay = $this->getWeekendDays();
		$getHoliday    = explode('","', $this->getHolidays());

		if($this->data['siteinfos']->attendance == 'subject') {
			$attendances   = $this->subjectattendance_m->get_order_by_sub_attendance($queryArray);

			if(customCompute($attendances)) {
				foreach ($attendances as $attendance) {
					$monthyearArray = explode('-', $attendance->monthyear);
					$monthDay = date('t', mktime(0, 0, 0, $monthyearArray['0'], 1, $monthyearArray['1'])); 
					for($i=1; $i<=$monthDay; $i++) {
						$currentDate = sprintf("%02d", $i).'-'.$attendance->monthyear;
						if(in_array($currentDate, $getHoliday)) {
							continue;
						} elseif(in_array($currentDate, $getWeekendDay)) {
							continue;
						} else {
							$day = 'a'.$i;
							if($attendance->$day == 'P' || $attendance->$day == 'L' || $attendance->$day == 'LE') {
								if(!isset($newArray[$attendance->studentID][$attendance->subjectID]['pCount'])) {
									$newArray[$attendance->studentID][$attendance->subjectID]['pCount'] = 1;
								} else {
									$newArray[$attendance->studentID][$attendance->subjectID]['pCount'] += 1;
								}
							} else {
								if(!isset($newArray[$attendance->studentID][$attendance->subjectID]['aCount'])) {
									$newArray[$attendance->studentID][$attendance->subjectID]['aCount'] = 1;
								} else {
									$newArray[$attendance->studentID][$attendance->subjectID]['aCount'] += 1;
								}
							}
							if(!isset($newArray[$attendance->studentID][$attendance->subjectID]['tCount'])) {
								$newArray[$attendance->studentID][$attendance->subjectID]['tCount'] = 1;
							} else {
								$newArray[$attendance->studentID][$attendance->subjectID]['tCount'] += 1;
							}
						}
					}
				}

				$studentlistsArray = pluck($studentlists,'sroptionalsubjectID','srstudentID');
				$subjects  = pluck($subjects,'obj','subjectID');

				if(customCompute($newArray)) {
					foreach($newArray as $studentID => $array) {
						$str = '';
						if(customCompute($subjects)) {
							foreach ($subjects as $subjectID => $subject) {
								if($subject->type == '1') {
									$pCount = isset($array[$subjectID]['pCount']) ? $array[$subjectID]['pCount'] : '0';
									$tCount = isset($array[$subjectID]['tCount']) ? $array[$subjectID]['tCount'] : '0';
									$str .= $subjects[$subjectID]->subject .":".$pCount."/".$tCount.',';
								}
							}
						}

						if(isset($studentlistsArray[$studentID]) && $studentlistsArray[$studentID] != '0' ) {
							$pCount = isset($newArray[$studentID][$studentlistsArray[$studentID]]['pCount']) ? $newArray[$studentID][$studentlistsArray[$studentID]]['pCount'] : '0';
							$tCount = isset($newArray[$studentID][$studentlistsArray[$studentID]]['tCount']) ? $newArray[$studentID][$studentlistsArray[$studentID]]['tCount'] : '0';
							$str .= $subjects[$subjectID]->subject .":".$pCount."/".$tCount.',';
						}

						$attendanceArray[$studentID] = $str;
					}
				}
			}
		} else {
			$attendances   = $this->sattendance_m->get_order_by_attendance($queryArray);
			if(customCompute($attendances)) {
				foreach($attendances as $attendance) {
					$monthyearArray = explode('-', $attendance->monthyear);
					$monthDay = date('t', mktime(0, 0, 0, $monthyearArray['0'], 1, $monthyearArray['1'])); 
					for($i=1; $i<=$monthDay; $i++) {
						$currentDate = sprintf("%02d", $i).'-'.$attendance->monthyear;
						if(in_array($currentDate, $getHoliday)) {
							continue;
						} elseif(in_array($currentDate, $getWeekendDay)) {
							continue;
						} else {
							$day = 'a'.$i;
							if($attendance->$day == 'P' || $attendance->$day == 'L' || $attendance->$day == 'LE') {
								if(!isset($newArray[$attendance->studentID]['pCount'])) {
									$newArray[$attendance->studentID]['pCount'] = 1;
								} else {
									$newArray[$attendance->studentID]['pCount'] += 1;
								}
							} else {
								if(!isset($newArray[$attendance->studentID]['aCount'])) {
									$newArray[$attendance->studentID]['aCount'] = 1;
								} else {
									$newArray[$attendance->studentID]['aCount'] += 1;
								}
							}
							if(!isset($newArray[$attendance->studentID]['tCount'])) {
								$newArray[$attendance->studentID]['tCount'] = 1;
							} else {
								$newArray[$attendance->studentID]['tCount'] += 1;
							}
						}
					}
					$pCount = isset($newArray[$attendance->studentID]['pCount']) ? $newArray[$attendance->studentID]['pCount'] : '0';
					$tCount = isset($newArray[$attendance->studentID]['tCount']) ? $newArray[$attendance->studentID]['tCount'] : '0';
					$attendanceArray[$attendance->studentID] = $pCount."/".$tCount;
				}
			}
		}
		return $attendanceArray;
	}

	public function pdf() {
		if(permissionChecker('terminalreport')) {
			   
			$finaltermexamID = htmlentities(escapeString($this->uri->segment(3)));
			$classesID  = htmlentities(escapeString($this->uri->segment(4)));
			$sectionID  = htmlentities(escapeString($this->uri->segment(5)));
			$studentID  = htmlentities(escapeString($this->uri->segment(6)));
			$date  = htmlentities(escapeString($this->uri->segment(7)));
			$verified_by  = htmlentities(escapeString($this->uri->segment(8)));
			$school_days  = htmlentities(escapeString($this->uri->segment(9)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$finaltermexamID && (int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
				$this->data['examID']     = $finaltermexamID;
				$this->data['classesID']  = $classesID;
				$this->data['sectionID']  = $sectionID;
				$this->data['studentIDD'] = $studentID;
				$this->data['date'] = urldecode($date);
				$this->data['verified_by'] = $verified_by;
				$this->data['school_days'] = $school_days;

				$queryArray        = [];
				$studentQueryArray = [];
				$queryArray['schoolyearID']          = $schoolyearID;
				$studentQueryArray['srschoolyearID'] = $schoolyearID;

				
				if((int)$classesID > 0) {
					$queryArray['classesID'] = $classesID;
					$studentQueryArray['srclassesID'] = $classesID;
				} 
				if((int)$sectionID > 0) {
					$queryArray['sectionID'] = $sectionID;
					$studentQueryArray['srsectionID'] = $sectionID;
				}
				if((int)$studentID > 0) {
					$studentQueryArray['srstudentID'] = $studentID;
				}

				$class = $this->classes_m->get_single_classes(['classesID' => $classesID]);
				$exam      = $this->exam_m->get_single_exam(['examID'=> $finaltermexamID]);
				$this->data['class'] = $class;
				$this->data['exam'] = $exam;
				$this->data['exam']->date_in_nepali = $this->convertDateToNepaliInEnglish($exam->date);
				$this->data['exam']->issue_date_in_english = $this->convertDateToEnglishInNepali($exam->issue_date);
				$this->data['examName']     = $exam->exam;
				$this->data['grades']       = $this->grade_m->get_grade();
				$this->data['classes']      = pluck($this->classes_m->general_get_classes(),'classes','classesID');
				$this->data['sections']     = pluck($this->section_m->general_get_section(),'section','sectionID');
				$this->data['class_teacher']= pluck($this->section_m->get_join_sections(),'name','sectionID');
				$this->data['groups']       = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
				$this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);
				$this->data['remarks'] 		= pluck($this->studentremark_m->get_order_by_studentremark(['examID' => $finaltermexamID, 'classID' => $classesID]), 'remarks', 'studentID');
				$students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
				
				$mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
				$coscholasticSubjects      = $this->subject_m->get_subject_only_coscholastic(array('classesID' => $classesID, 'type' => 1));
				
				$this->data['mandatorySubjects'] = $mandatorySubjects;
				$this->data['coscholasticSubjects'] = $coscholasticSubjects;

				$this->subject_m->order('type DESC');
				$this->data['subjects'] = $this->subject_m->get_by_class_id($classesID);
				$this->data['subject_marks'] = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $finaltermexamID,'class_id' => $classesID]), 'fullmark', 'subject_id');
							

				$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
				$settingmarktypeID1      = $this->data['siteinfos']->marktypeID;
				$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
				$markpercentagesmainArr1 = $this->marksetting_m->get_marksetting_markpercentages();
			
				
				$examtermSettings = $this->examtermsetting_m->get_examtermsetting_with_examtermsettingrelation2([
					'classesID' => $classesID,
					'schoolyearID' => $schoolyearID,
					'finaltermexamID' => $finaltermexamID
				]);

					$newstudentPositionarray = [];
					$newstudentPositionarray1 = [];
					$newExamwiseSubjectMark = [];
					if(customCompute($examtermSettings)){
						$this->data['examtermSettings'] = $examtermSettings;
						foreach($examtermSettings as $examtermSettings){

							$examID = $examtermSettings->examID;

							if((int)$examID > 0) {
								$queryArray['examID'] = $examID;
							} 

							$examwiseSubjectMark = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID,'class_id' => $classesID]), 'fullmark', 'subject_id');
							
							$marks                  = $this->mark_m->student_all_mark_array($queryArray);
							$accmarkpercentagesArr[$examID] = $markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
							$accmarkpercentagesArr1[$examID] = $markpercentagesArr1     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
							

							$this->data['markpercentagesArr']  = $accmarkpercentagesArr;
							$this->data['settingmarktypeID']   = $settingmarktypeID;

							$this->data['markpercentagesArr1']  = $accmarkpercentagesArr1;
							$this->data['settingmarktypeID1']   = $settingmarktypeID;

							$retMark = [];
							if(customCompute($marks)) {
								foreach ($marks as $mark) {
									$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
								}
							}

							$retMark1 = [];
							if(customCompute($marks)) {
								foreach ($marks as $mark) {
									$retMark1[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
								}
							}

							$highestMarks    = [];
							foreach ($marks as $value) {
								if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
									$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
								}
								$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
							}

							$this->data['highestmarks']      = $highestMarks;

							$studentPosition             = [];
							$studentChecker              = [];
							$studentClassPositionArray   = [];
							$studentSubjectPositionArray = [];
							$markpercentagesCount        = 0;

							$studentPosition1             = [];
							$studentChecker1              = [];
							$studentClassPositionArray1   = [];
							$studentSubjectPositionArray1 = [];
							$markpercentagesCount1        = 0;

							if(customCompute($this->data['studentLists'])) {
								foreach($this->data['studentLists'] as $student) {
									$student->dob_in_bs = $this->convertDateToNepaliInEnglish($student->dob);
								}
							}
								
								if(customCompute($students)) {
									foreach ($students as $student) {
										$opuniquepercentageArr = [];
										$anotheropuniquepercentageArr = [];
										if($student->sroptionalsubjectID > 0) {
											$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
										}

										if($student->sranotheroptionalsubjectID > 0) {
											$anotheropuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
										}

										$opuniquepercentageArr1 = [];
										$anotheropuniquepercentageArr1 = [];
										if($student->sroptionalsubjectID > 0) {
											$opuniquepercentageArr1 = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
										}

										if($student->sranotheroptionalsubjectID > 0) {
											$anotheropuniquepercentageArr1 = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
										}

										$studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;

										$studentPosition1[$student->srstudentID]['totalSubjectMark'] = 0;

										if(customCompute($mandatorySubjects)) {
											foreach ($mandatorySubjects as $mandatorySubject) {
												$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

												$markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
												$markpercentagesCount = customCompute($markpercentages);
												if(customCompute($markpercentages)) {
													foreach ($markpercentages as $markpercentageID) {
														$f = false;
														if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
															$f = true;
														}

														if(isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
															if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
																$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
															} else {
																$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
															}
														} else {
															if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
																$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
															} else {
																$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
															}
														}

														if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
															$studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

															if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
															} else {
																$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];

															}
														}

														$f = false;
														if(customCompute($opuniquepercentageArr)) {
															if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
																$f = true;
															}
														}
														if(customCompute($anotheropuniquepercentageArr)) {
															if(isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
																$f = true;
															}
														}

														if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
															if($student->sroptionalsubjectID != 0) {
																if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
																	if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																		$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
																	} else {
																		$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
																	}
																} else {
																	if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																		$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
																	} else {
																		$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
																	}
																}

																if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

																	if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																		$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
																	} else {
																		if($f) {
																			$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
																		}
																	}

																}
															}
															if($student->sranotheroptionalsubjectID != 0) {
																if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
																	if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																		$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
																	} else {
																		$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
																	}
																} else {
																	if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																		$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
																	} else {
																		$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
																	}
																}

																if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

																	if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																		$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
																	} else {
																		if($f) {
																			$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
																		}
																	}

																}
															}
															$studentChecker['subject'][$student->srstudentID][$markpercentageID] = TRUE;
														}
													}
												}

												$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];

												if(!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
													if($student->sroptionalsubjectID != 0) {
														$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
													}
													if($student->sranotheroptionalsubjectID != 0) {
														$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
													}
													$studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
												}

												$studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
												if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
													if($student->sroptionalsubjectID != 0) {
														$studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
													}
													if($student->sranotheroptionalsubjectID != 0) {
														$studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
													}
												}
											}
										}	

										if(customCompute($coscholasticSubjects)) {
											foreach ($coscholasticSubjects as $coscholasticSubject) {
												$uniquepercentageArr1 = isset($markpercentagesArr1[$coscholasticSubject->subjectID]) ? $markpercentagesArr1[$coscholasticSubject->subjectID] : [];

												$markpercentages1 = $uniquepercentageArr1[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
												$markpercentagesCount1 = customCompute($markpercentages);
												if(customCompute($markpercentages1)) {
													foreach ($markpercentages1 as $markpercentageID) {
														$f = false;
														if(isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
															$f = true;
														}

														if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID])) {
															if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
																$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
															} else {
																$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += 0;
															}
														} else {
															if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
																$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
															} else {
																$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = 0;
															}
														}

														if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
															$studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];

															if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];
															} else {
																$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];

															}
														}

														$f = false;
														if(customCompute($opuniquepercentageArr1)) {
															if(isset($opuniquepercentageArr1['own']) && in_array($markpercentageID, $opuniquepercentageArr1['own'])) {
																$f = true;
															}
														}

														if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
															if($student->sroptionalsubjectID != 0) {
																if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
																	if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																		$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
																	} else {
																		$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
																	}
																} else {
																	if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																		$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
																	} else {
																		$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
																	}
																}

																if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

																	if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																		$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
																	} else {
																		if($f) {
																			$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
																		}
																	}

																}
															}

															if($student->sranotheroptionalsubjectID != 0) {
																if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
																	if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																		$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
																	} else {
																		$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
																	}
																} else {
																	if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																		$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
																	} else {
																		$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
																	}
																}

																if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

																	if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																		$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
																	} else {
																		if($f) {
																			$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
																		}
																	}

																}
															}

															$studentChecker1['subject'][$student->srstudentID][$markpercentageID] = TRUE;
														}
													}
												}

												$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];

												if(!isset($studentChecker1['totalSubjectMark'][$student->srstudentID])) {
													// if($student->sroptionalsubjectID != 0) {
													// 	$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
													// }
													$studentChecker1['totalSubjectMark'][$student->srstudentID] = TRUE;
												}

												$studentSubjectPositionArray1[$coscholasticSubject->subjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];
												if(!isset($studentChecker1['studentSubjectPositionArray'][$student->srstudentID])) {
													// if($student->sroptionalsubjectID != 0) {
													// 	$studentSubjectPositionArray1[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
													// }
												}
											}
										}


										$studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
										$studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

										if(isset($studentPosition['totalStudentMarkAverage'])) {
											$studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
										} else {
											$studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
										}

										if(count($coscholasticSubjects) > 0) {
											$studentPosition1[$student->srstudentID]['classPositionMark'] = ($studentPosition1[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition1[$student->srstudentID]['subjectMark']));
											$studentClassPositionArray1[$student->srstudentID]             = $studentPosition1[$student->srstudentID]['classPositionMark'];	
										
											if(isset($studentPosition1['totalStudentMarkAverage'])) {
												$studentPosition1['totalStudentMarkAverage'] += $studentPosition1[$student->srstudentID]['classPositionMark'];
											} else {
												$studentPosition1['totalStudentMarkAverage']  = $studentPosition1[$student->srstudentID]['classPositionMark'];
											}
										}
										
										
									}
								}

								arsort($studentClassPositionArray);
								$studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
								if(customCompute($studentSubjectPositionArray)) {
									foreach($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
										arsort($studentSubjectPositionMark);
										$studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
									}
								}
								if((int)$studentID > 0) {
									$queryArray['studentID'] = $studentID;
								}

								arsort($studentClassPositionArray1);
								$studentPosition1['studentClassPositionArray'] = $studentClassPositionArray1;
								if(customCompute($studentSubjectPositionArray1)) {
									foreach($studentSubjectPositionArray1 as $subjectID => $studentSubjectPositionMark1) {
										arsort($studentSubjectPositionMark1);
										$studentPosition1['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark1;
									}
								}
								if((int)$studentID > 0) {
									$queryArray['studentID'] = $studentID;
								}

								$newstudentPositionarray[$examID] = $studentPosition;
								$newstudentPositionarray1[$examID] = $studentPosition1;
								$newExamwiseSubjectMark[$examID]   = $examwiseSubjectMark; 

							}}
		
					$this->data['examwise_subject_marks']   = $newExamwiseSubjectMark;
				    $this->data['col']             = 5 + $markpercentagesCount;
					$this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
					$this->data['studentPosition'] = $newstudentPositionarray;
					$this->data['percentageArr']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

					$this->data['col1']             = 5 + $markpercentagesCount1;
					$this->data['studentPosition1'] = $newstudentPositionarray1;
					$this->data['percentageArr1']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

					// if($class->classes_numeric == 1 || $class->classes_numeric == 2 || $class->classes_numeric == 3) {
						// $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/PrimaryTerminalReportPDF', 'view', 'a4', 'portrait');
					// } else if($class->classes_numeric == 11 || $class->classes_numeric == 12) {	
					// 	$this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF_11_12', 'view', 'a4', 'portrait');
					// } else {
						$this->reportPDF('terminalreport1.css', $this->data, 'report/finalterminal/TerminalReportPDF', 'view', 'a4', 'portrait');
					// }
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "errorpermission";
				$this->load->view('_layout_main', $this->data);
		}
	}


	public function download_excel() {
		$finaltermexamID = htmlentities(escapeString($this->uri->segment(3)));
		$classesID  = htmlentities(escapeString($this->uri->segment(4)));
		$sectionID  = htmlentities(escapeString($this->uri->segment(5)));
		$studentID  = htmlentities(escapeString($this->uri->segment(6)));
		$date  = htmlentities(escapeString($this->uri->segment(7)));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$finaltermexamID && (int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
											
			$this->data['examID']     = $finaltermexamID;
			$this->data['classesID']  = $classesID;
			$this->data['sectionID']  = $sectionID;
			$this->data['studentIDD'] = $studentID;
			$this->data['date'] = urldecode($date);

			$queryArray        = [];
			$studentQueryArray = [];
			$queryArray['schoolyearID']          = $schoolyearID;
			$studentQueryArray['srschoolyearID'] = $schoolyearID;

			
			if((int)$classesID > 0) {
				$queryArray['classesID'] = $classesID;
				$studentQueryArray['srclassesID'] = $classesID;
			}
			if((int)$sectionID > 0) {
				$queryArray['sectionID'] = $sectionID;
				$studentQueryArray['srsectionID'] = $sectionID;
			}
			if((int)$studentID > 0) {
				$studentQueryArray['srstudentID'] = $studentID;
			}
			
			$exam      = $this->exam_m->get_single_exam(['examID'=> $finaltermexamID]);
			$exam_name     = $exam->exam;
			$this->data['examName'] = $exam_name;
			$grades       = $this->grade_m->get_grade();
			$classes      = pluck($this->classes_m->general_get_classes(),'classes','classesID');
			$sections     = pluck($this->section_m->general_get_section(),'section','sectionID');
			$this->data['class_teacher']= pluck($this->section_m->get_join_sections(),'name','sectionID');
			$groups       = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
			$studentLists = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);
            $classsections = $this->section_m->get_join_section($classesID);

			$students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
			$mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
			$all_except_deportment  = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID));
			
			$this->subject_m->order('type DESC');
			$subjects = $this->subject_m->get_by_class_id($classesID);
			
			$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
			$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();


			$examtermSettings = $this->examtermsetting_m->get_examtermsetting_with_examtermsettingrelation2([
				'classesID' => $classesID,
				'schoolyearID' => $schoolyearID,
				'finaltermexamID' => $finaltermexamID
			]);

	        $newstudentPositionarray = [];
			$newExamwiseSubjectMark = [];
	        if(customCompute($examtermSettings)){
                foreach($examtermSettings as $examtermSetting){
                $examID = $examtermSetting->examID;
			    $weightage = $examtermSetting->value;

				if((int)$examID > 0) {
					$queryArray['examID'] = $examID;
				} 

				$examwiseSubjectMark = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID,'class_id' => $classesID]), 'fullmark', 'subject_id');


			    $marks                  = $this->mark_m->student_all_mark_array($queryArray);
			    $accmarkpercentagesArr[$examID] = $markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
					
				$retMark = [];
				if(customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
					}
				}

				$studentPosition             = [];
				$studentChecker              = [];
				$studentClassPositionArray   = [];
				$studentSubjectPositionArray = [];
				$markpercentagesCount        = 0;
				if(customCompute($students)) {
					foreach ($students as $student) {

						$opuniquepercentageArr = [];
						if($student->sroptionalsubjectID > 0) {
							$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
						}

						$anotheropuniquepercentageArr = [];
						if($student->sranotheroptionalsubjectID > 0) {
							$anotheropuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
						}

						$studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;
						if(customCompute($mandatorySubjects)) {
							foreach ($mandatorySubjects as $mandatorySubject) {
								$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

								$markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
								$markpercentagesCount = customCompute($markpercentages);
								if(customCompute($markpercentages)) {
									foreach ($markpercentages as $markpercentageID) {
										$f = false;
										if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
											$f = true;
										}

										if(isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
											if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
											} else {
												$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
											}
										} else {
											if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
											} else {
												$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
											}
										}

										if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
											$studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

											if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
												$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
											} else {
												$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];

											}
										}

										$f = false;
										if(customCompute($opuniquepercentageArr)) {
											if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
												$f = true;
											}
										}
										if(customCompute($anotheropuniquepercentageArr)) {
											if(isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
												$f = true;
											}
										}

										if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
										
											if($student->sroptionalsubjectID != 0) {
												if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
													if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
													}
												} else {
													if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
													}
												}

												if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

													if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
													} else {
														if($f) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
														}
													}

												}
											}

											if($student->sranotheroptionalsubjectID != 0) {

											
												if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
													if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
													}
												} else {
													if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
													}
												}

												if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

													if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
													} else {
														if($f) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
														}
													}

												}
											}

											$studentChecker['subject'][$student->srstudentID][$markpercentageID] = TRUE;
										}

					

									

									}
								}

								$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];

								if(!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
									if($student->sroptionalsubjectID != 0) {
										$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
									}

									if($student->sranotheroptionalsubjectID != 0) {
										$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
									}
									$studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
								}

								$studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
								if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
									if($student->sroptionalsubjectID != 0) {
										$studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
									}
								}
								if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
									if($student->sranotheroptionalsubjectID != 0) {
										$studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
									}
								}



							}
						}	


						$studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
						$studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

						if(isset($studentPosition['totalStudentMarkAverage'])) {
							$studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
						} else {
							$studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
						}
					}
				}

				arsort($studentClassPositionArray);
				$studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
				if(customCompute($studentSubjectPositionArray)) {
					foreach($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
						arsort($studentSubjectPositionMark);
						$studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
					}
				}
				if((int)$studentID > 0) {
					$queryArray['studentID'] = $studentID;
				}
			
			    $newstudentPositionarray[$examID] = $studentPosition;
			    $settingmarktypeID   = $settingmarktypeID;
				$newExamwiseSubjectMark[$examID] = $examwiseSubjectMark;
		
		}}

		    $examwise_subject_marks = $newExamwiseSubjectMark;
		    $markpercentagesArr = $accmarkpercentagesArr;
		    $studentPosition = $newstudentPositionarray;
			$percentageArr   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
			$class = $this->classes_m->get_single_classes(['classesID' => $classesID]);
		
			$rows[] = ['','Students','',''];
			$rows[] = ['S No.', 'Student Name ', 'Roll Number', 'Section'];
		
			$total_subject = count($all_except_deportment);
			if(customCompute($all_except_deportment)) {
				foreach($all_except_deportment as $index => $subject) {

					array_push($rows[0], $subject->subject);
					
					foreach($examtermSettings as $examtermSetting){
						array_push($rows[0], '');
						array_push($rows[1], $examtermSetting->exam);
					}

					array_push($rows[0], '');
					array_push($rows[1], 'Agg M');
					array_push($rows[1], 'Agg G');
									
				}
			}

			array_push($rows[0], 'Total');
			array_push($rows[0], 'GPA');
			array_push($rows[1], '');
			array_push($rows[1], '');

			// student marks calculation
			
			if(customCompute($studentLists)) { 
				$i = 2;
				$row = [];
				foreach($studentLists as $index => $student) {
				   unset($row);
				   $row[] = $index + 1;
				   $row[] = $student->name;
				   $row[] = $student->roll;

					foreach($sections as $index => $section) {
						if($index == $student->sectionID) {
							$row[] = $section;
						}
					}
				
                    $grand_total = 0;
                    $grand_total1 = 0;
                    $subject_count = 0;
					if(customCompute($all_except_deportment)) {
                            $loop = 1;  
                            foreach($all_except_deportment as $index => $subject){ 
								if(isset($studentPosition[$examtermSettings[0]->examID][$student->srstudentID]['subjectMark'][$subject->subjectID])) 
                                    {
                                        $subject_count = $subject_count + 1;
                                        $total_final_subject_mark = 0;
                                            foreach($examtermSettings as $examtermSetting){

                                                $examID = $examtermSetting->examID;

												$fullmark = $subject->finalmark;
												if(isset($examwise_subject_marks[$examID][$subject->subjectID]) && $examwise_subject_marks[$examID][$subject->subjectID] != '') {
													$fullmark = $examwise_subject_marks[$examID][$subject->subjectID];
												}

                                                $weightage = $examtermSetting->value;
                                                $weightage = ($weightage / 100) * $fullmark;

                                                $examwisemarkpercentagesArr = $markpercentagesArr[$examID];
                                                  
                                                reset($examwisemarkpercentagesArr);
                                                $firstindex          = key($examwisemarkpercentagesArr);
                                                $uniquepercentageArr = isset($examwisemarkpercentagesArr[$firstindex]) ? $examwisemarkpercentagesArr[$firstindex] : [];
                                                $markpercentages     = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                                                    
                                                $uniquepercentageArr =  isset($examwisemarkpercentagesArr[$subject->subjectID]) ? $examwisemarkpercentagesArr[$subject->subjectID] : [];
                                                     
                                                     $percentageMark  = 0;
                                                     if(customCompute($markpercentages)) {
														foreach($markpercentages as $markpercentageID) {
															$f = false;
															if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
																$f = true;
																$percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
															} 
                                                        }
                                                    }        

                                                     $subjectMark = isset($studentPosition[$examID][$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$examID][$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';
                                                     $subjectMark1 = markCalculationView($subjectMark, $fullmark, $percentageMark);
                                                    
                                                     $final_subject_mark = ($subjectMark / $fullmark) * $weightage;

                                                     $total_final_subject_mark  += $final_subject_mark;
                                                     
                                                     $row[] = $subjectMark;
                                                } 

                                                   $total_final_subject_mark1 = markCalculationView($total_final_subject_mark, $fullmark);
                                                   $final_mark_exist = false;
                                                    if(customCompute($grades)) { 
                                                        foreach($grades as $grade) {
                                                            if(($grade->gradefrom <= $total_final_subject_mark1) && ($grade->gradeupto >= $total_final_subject_mark1)) { 
                                                                $final_mark_exist = true;
                                                                
                                                                $row[] = $total_final_subject_mark;
                                                                $row[] = $grade->grade;
                                                                
                                                            }
                                                        } 
                                                    } 

                                                    if(!$final_mark_exist) {
                                                        $row[]=  '';
														$row[] = '';
                                                        
                                                    }

                                                    $grand_total += $total_final_subject_mark; 
                                                    $grand_total1 += $total_final_subject_mark1;  

                                            $loop++;
                                    }else{
										for($k=1;$k<=count($examtermSettings);$k++){
											$row[] = '-';
										}
										$row[] = '-';
										$row[] = '-';
									}
                            }  
                                               
                                                    $final_grand_total = round($grand_total1 / $subject_count);
													if(isset($studentPosition[$examID][$student->srstudentID]['classPositionMark']) && $studentPosition[$examID][$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition[$examID]['totalStudentMarkAverage']) && $final_grand_total > 1) {
                                                        if(customCompute($grades)) { 
                                                            foreach($grades as $grade) {
                                                                if(($grade->gradefrom <= $final_grand_total) && ($grade->gradeupto >= $final_grand_total)) { 
                                                                    $row[] = $final_grand_total;
														            $row[] = $grade->grade;
                                                                }
                                                            } 
                                                        }
													} else {
														$row[] = '';
														$row[] = '';
													}
				
			        }
			        $rows[$i] = $row;
			        $i++;
		        }
	        }

	        $this->data['class'] = $class;
			$this->data['rows'] = $rows;
			$this->data['subject_count'] = $total_subject;

			$return = [
				'title' => 'Result',
				'data' => $rows
			];

			$section_name = '';
			foreach($sections as $index => $section) {
				if($index == $sectionID) {
					$section_name = '_'.$section;
				}
			}

			$filename = 'final_terminal_student_ledger_'. $class->classes.$section_name;
			$this->apiDownloadExcel($return, $filename, count($examtermSettings), $total_subject, count($studentLists));
		

		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}

	}

	public function view() {
		$finaltermexamID = htmlentities(escapeString($this->uri->segment(3)));
		$classesID  = htmlentities(escapeString($this->uri->segment(4)));
		$sectionID  = htmlentities(escapeString($this->uri->segment(5)));
		$studentID  = htmlentities(escapeString($this->uri->segment(6)));
		$date  = htmlentities(escapeString($this->uri->segment(7)));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$finaltermexamID && (int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
											
			$this->data['examID']     = $finaltermexamID;
			$this->data['classesID']  = $classesID;
			$this->data['sectionID']  = $sectionID;
			$this->data['studentIDD'] = $studentID;
			$this->data['date'] = urldecode($date);

			$queryArray        = [];
			$studentQueryArray = [];
			$queryArray['schoolyearID']          = $schoolyearID;
			$studentQueryArray['srschoolyearID'] = $schoolyearID;

			
			if((int)$classesID > 0) {
				$queryArray['classesID'] = $classesID;
				$studentQueryArray['srclassesID'] = $classesID;
			}
			if((int)$sectionID > 0) {
				$queryArray['sectionID'] = $sectionID;
				$studentQueryArray['srsectionID'] = $sectionID;
			}
			if((int)$studentID > 0) {
				$studentQueryArray['srstudentID'] = $studentID;
			}
			
			$exam      = $this->exam_m->get_single_exam(['examID'=> $finaltermexamID]);
			$exam_name     = $exam->exam;
			$this->data['examName'] = $exam_name;
			$grades       = $this->grade_m->get_grade();
			$classes      = pluck($this->classes_m->general_get_classes(),'classes','classesID');
			$sections     = pluck($this->section_m->general_get_section(),'section','sectionID');
			$this->data['class_teacher']= pluck($this->section_m->get_join_sections(),'name','sectionID');
			$groups       = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
			$studentLists = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);
            $classsections = $this->section_m->get_join_section($classesID);

			$students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
			$mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
			$all_except_deportment  = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID));
			
			$this->subject_m->order('type DESC');
			$subjects = $this->subject_m->get_by_class_id($classesID);
			$subject_marks = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $finaltermexamID,'class_id' => $classesID]), 'fullmark', 'subject_id');
			
			$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
			$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();


			$examtermSettings = $this->examtermsetting_m->get_examtermsetting_with_examtermsettingrelation2([
				'classesID' => $classesID,
				'schoolyearID' => $schoolyearID,
				'finaltermexamID' => $finaltermexamID
			]);

	        $newstudentPositionarray = [];
			$newExamwiseSubjectMark = [];
	        if(customCompute($examtermSettings)){
                foreach($examtermSettings as $examtermSetting){
                $examID = $examtermSetting->examID;
			    $weightage = $examtermSetting->value;

				if((int)$examID > 0) {
					$queryArray['examID'] = $examID;
				} 

				$examwiseSubjectMark = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID,'class_id' => $classesID]), 'fullmark', 'subject_id');


			    $marks                  = $this->mark_m->student_all_mark_array($queryArray);
			    $accmarkpercentagesArr[$examID] = $markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
					
				$retMark = [];
				if(customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
					}
				}

				$studentPosition             = [];
				$studentChecker              = [];
				$studentClassPositionArray   = [];
				$studentSubjectPositionArray = [];
				$markpercentagesCount        = 0;
				if(customCompute($students)) {
					foreach ($students as $student) {

						$opuniquepercentageArr = [];
						if($student->sroptionalsubjectID > 0) {
							$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
						}

						$anotheropuniquepercentageArr = [];
						if($student->sranotheroptionalsubjectID > 0) {
							$anotheropuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
						}

						$studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;
						if(customCompute($mandatorySubjects)) {
							foreach ($mandatorySubjects as $mandatorySubject) {
								$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

								$markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
								$markpercentagesCount = customCompute($markpercentages);
								if(customCompute($markpercentages)) {
									foreach ($markpercentages as $markpercentageID) {
										$f = false;
										if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
											$f = true;
										}

										if(isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
											if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
											} else {
												$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
											}
										} else {
											if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
											} else {
												$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
											}
										}

										if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
											$studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

											if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
												$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
											} else {
												$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];

											}
										}

										$f = false;
										if(customCompute($opuniquepercentageArr)) {
											if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
												$f = true;
											}
										}
										if(customCompute($anotheropuniquepercentageArr)) {
											if(isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
												$f = true;
											}
										}

										if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
										
											if($student->sroptionalsubjectID != 0) {
												if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
													if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
													}
												} else {
													if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
													}
												}

												if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

													if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
													} else {
														if($f) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
														}
													}

												}
											}

											if($student->sranotheroptionalsubjectID != 0) {

											
												if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
													if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
													}
												} else {
													if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
													}
												}

												if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

													if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
													} else {
														if($f) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
														}
													}

												}
											}

											$studentChecker['subject'][$student->srstudentID][$markpercentageID] = TRUE;
										}

					

									

									}
								}

								$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];

								if(!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
									if($student->sroptionalsubjectID != 0) {
										$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
									}

									if($student->sranotheroptionalsubjectID != 0) {
										$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
									}
									$studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
								}

								$studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
								if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
									if($student->sroptionalsubjectID != 0) {
										$studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
									}
								}
								if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
									if($student->sranotheroptionalsubjectID != 0) {
										$studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
									}
								}



							}
						}	


						$studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
						$studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

						if(isset($studentPosition['totalStudentMarkAverage'])) {
							$studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
						} else {
							$studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
						}
					}
				}

				arsort($studentClassPositionArray);
				$studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
				if(customCompute($studentSubjectPositionArray)) {
					foreach($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
						arsort($studentSubjectPositionMark);
						$studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
					}
				}
				if((int)$studentID > 0) {
					$queryArray['studentID'] = $studentID;
				}
			
			    $newstudentPositionarray[$examID] = $studentPosition;
			    $settingmarktypeID   = $settingmarktypeID;
				$newExamwiseSubjectMark[$examID]   = $examwiseSubjectMark; 

			}}

	        $examwise_subject_marks   = $newExamwiseSubjectMark;
		    $markpercentagesArr = $accmarkpercentagesArr;
		    $studentPosition = $newstudentPositionarray;
			$percentageArr   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
			$class = $this->classes_m->get_single_classes(['classesID' => $classesID]);
		
			$rows[] = ['Students'];
			$rows[] = ['S No.', 'Student Name ', 'Roll Number', 'Section'];
		
			$total_subject = count($all_except_deportment);
			if(customCompute($all_except_deportment)) {
				foreach($all_except_deportment as $index => $subject) {

					array_push($rows[0], $subject->subject);
					
					foreach($examtermSettings as $examtermSetting){
						array_push($rows[1], $examtermSetting->exam);
					}

					array_push($rows[1], 'Agg M');
					array_push($rows[1], 'Agg G');
									
				}
			}

			array_push($rows[0], 'Total');
			array_push($rows[0], 'GPA');
			array_push($rows[1], '');
			array_push($rows[1], '');

			// student marks calculation
			
			if(customCompute($studentLists)) { 
				$i = 0;
				$row = [];
				$bodyrows = [];
				foreach($studentLists as $index => $student) {
				   unset($row);
				   $row[] = $index + 1;
				   $row[] = $student->name;
				   $row[] = $student->roll;

					foreach($sections as $index => $section) {
						if($index == $student->sectionID) {
							$row[] = $section;
						}
					}
				
                    $grand_total = 0;
                    $grand_total1 = 0;
                    $subject_count = 0;
					if(customCompute($all_except_deportment)) {
                            $loop = 1;  
                            foreach($all_except_deportment as $index => $subject){ 
								if(isset($studentPosition[$examtermSettings[0]->examID][$student->srstudentID]['subjectMark'][$subject->subjectID])) 
                                    {
                                        $subject_count = $subject_count + 1;
                                        $total_final_subject_mark = 0;
                                            foreach($examtermSettings as $examtermSetting){

                                                $examID = $examtermSetting->examID;
                                                $fullmark = $subject->finalmark;
                                                if(isset($examwise_subject_marks[$examID][$subject->subjectID]) && $examwise_subject_marks[$examID][$subject->subjectID] != '') {
                                                    $fullmark = $examwise_subject_marks[$examID][$subject->subjectID];
                                                }


                                                $weightage = $examtermSetting->value;
                                                $weightage = ($weightage / 100) * $fullmark;

                                                $examwisemarkpercentagesArr = $markpercentagesArr[$examID];
                                                  
                                                reset($examwisemarkpercentagesArr);
                                                $firstindex          = key($examwisemarkpercentagesArr);
                                                $uniquepercentageArr = isset($examwisemarkpercentagesArr[$firstindex]) ? $examwisemarkpercentagesArr[$firstindex] : [];
                                                $markpercentages     = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                                                    
                                                $uniquepercentageArr =  isset($examwisemarkpercentagesArr[$subject->subjectID]) ? $examwisemarkpercentagesArr[$subject->subjectID] : [];
                                                     
                                                     $percentageMark  = 0;
                                                     if(customCompute($markpercentages)) {
														foreach($markpercentages as $markpercentageID) {
															$f = false;
															if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
																$f = true;
																$percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
															} 
                                                        }
                                                    }        

                                                     $subjectMark = isset($studentPosition[$examID][$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$examID][$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';
                                                     $subjectMark1 = markCalculationView($subjectMark, $fullmark, $percentageMark);
                                                    
                                                     $final_subject_mark = ($subjectMark / $fullmark) * $weightage;

                                                     $total_final_subject_mark  += $final_subject_mark;
                                                     
                                                     $row[] = $subjectMark;
                                                } 

                                                   $total_final_subject_mark1 = markCalculationView($total_final_subject_mark, $fullmark);
                                                   $final_mark_exist = false;
                                                    if(customCompute($grades)) { 
                                                        foreach($grades as $grade) {
                                                            if(($grade->gradefrom <= $total_final_subject_mark1) && ($grade->gradeupto >= $total_final_subject_mark1)) { 
                                                                $final_mark_exist = true;
                                                                
                                                                $row[] = $total_final_subject_mark;
                                                                $row[] = $grade->grade;
                                                                
                                                            }
                                                        } 
                                                    } 

                                                    if(!$final_mark_exist) {
                                                        $row[]=  '';
														$row[] = '';
                                                        
                                                    }

                                                    $grand_total += $total_final_subject_mark; 
                                                    $grand_total1 += $total_final_subject_mark1;  

                                            $loop++;
                                    }else{
										for($k=1;$k<=count($examtermSettings);$k++){
											$row[] = '-';
										}
										$row[] = '-';
										$row[] = '-';
									} 
                            }  
                                               
                                                    $final_grand_total = round($grand_total1 / $subject_count);
													if(isset($studentPosition[$examID][$student->srstudentID]['classPositionMark']) && $studentPosition[$examID][$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition[$examID]['totalStudentMarkAverage']) && $final_grand_total > 1) {
                                                        if(customCompute($grades)) { 
                                                            foreach($grades as $grade) {
                                                                if(($grade->gradefrom <= $final_grand_total) && ($grade->gradeupto >= $final_grand_total)) { 
                                                                    $row[] = $final_grand_total;
														            $row[] = $grade->grade;
                                                                }
                                                            } 
                                                        }
													} else {
														$row[] = '';
														$row[] = '';
													}
				
			        }
			        $bodyrows[$i] = $row;
			        $i++;
		        }
	        }


	        $this->data['class'] = $class;
			$this->data['rows'] = $rows;
			$this->data['bodyrows'] = $bodyrows;
			$this->data['subject_count'] = $total_subject;
			$this->data['examCount'] = count($examtermSettings);
			$this->data["subview"] = "report/finalterminal/viewmark";
			$this->load->view('_layout_main', $this->data);

		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}

	}

	private function  apiDownloadExcel($data, $filename='excel', $examCount, $total_subject, $total_student) {
    
        $spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->createSheet();
		$sheet = $spreadsheet->getActiveSheet();

		$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
			),
			'font' => [
				'size' => 13
			]
		);
		
		
        foreach($data['data'] as $key=>$value){
            $row = $key + 1;
            for($i=0;$i<count($value);$i++){
				$j = $i + 1;
				$start_column = $this->getNameFromNumber($j);
                $sheet->setCellValue($start_column.$row, $value[$i]);
            } 
		}   

		$sheet->getStyle('A1:'.$sheet->getHighestColumn().'1')->getFont()->setBold( true );
		$sheet->getStyle('A2:'.$sheet->getHighestColumn().'2')->getFont()->setBold( true );
		
		$sheet->getRowDimension('1')->setRowHeight(20);
		$sheet->getRowDimension('2')->setRowHeight(20);

		foreach ($sheet->getColumnIterator() as $column) {
			$sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
		}
        //  $sheet->getColumnDimension('A')->setWidth(20);
		
         $sheet->setTitle($data['title']);
         $spreadsheet->setActiveSheetIndex(0);
         $writer = new Xlsx($spreadsheet);
         header('Content-Type: application/vnd.ms-excel');
         header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"');
         header('Cache-Control: max-age=0');
         $writer->save('php://output');
   
	}
	
	function getNameFromNumber($num) {
		$numeric = ($num - 1) % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval(($num - 1) / 26);
		if ($num2 > 0) {
			return $this->getNameFromNumber($num2) . $letter;
		} else {
			return $letter;
		}
	}

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';

		if(permissionChecker('terminalreport')) {
			if($_POST) {
				$to           = $this->input->post('to');
				$subject      = $this->input->post('subject');
				$message      = $this->input->post('message');
				$examID       = $this->input->post('examID');
				$classesID    = $this->input->post('classesID');
				$sectionID    = $this->input->post('sectionID');
				$studentID    = $this->input->post('studentID');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');

				$rules = $this->send_pdf_to_mail_rules();
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

					$queryArray        = [];
					$studentQueryArray = [];
					$queryArray['schoolyearID']            = $schoolyearID;
					$studentQueryArray['srschoolyearID']   = $schoolyearID;

					if((int)$examID > 0) {
						$queryArray['examID'] = $examID;
					} 
					if((int)$classesID > 0) {
						$queryArray['classesID'] = $classesID;
						$studentQueryArray['srclassesID'] = $classesID;
					} 
					if((int)$sectionID > 0) {
						$queryArray['sectionID'] = $sectionID;
						$studentQueryArray['srsectionID'] = $sectionID;
					}
					if((int)$studentID > 0) {
						$studentQueryArray['srstudentID'] = $studentID;
					}

					$exam      = $this->exam_m->get_single_exam(['examID'=> $examID]);
					$this->data['examName']     = $exam->exam;
					$this->data['grades']       = $this->grade_m->get_grade();
					$this->data['classes']      = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections']     = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->data['class_teacher']= pluck($this->section_m->get_join_sections(),'name','sectionID');
					$this->data['groups']       = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
					// $this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student($studentQueryArray);
					$this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);


					$students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
					$marks                  = $this->mark_m->student_all_mark_array($queryArray);
					$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 1));
					
					$this->subject_m->order('type DESC');
					$this->data['subjects'] = $this->subject_m->get_by_class_id($classesID);
					$this->data['subject_marks'] = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID,'class_id' => $classesID]), 'fullmark', 'subject_id');


					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
					$markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
					$this->data['markpercentagesArr']  = $markpercentagesArr;
					$this->data['settingmarktypeID']   = $settingmarktypeID;

					$retMark = [];
					if(customCompute($marks)) {
						foreach ($marks as $mark) {
							$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
						}
					}

					$studentPosition             = [];
					$studentChecker              = [];
					$studentClassPositionArray   = [];
					$studentSubjectPositionArray = [];
					$markpercentagesCount        = 0;
					if(customCompute($students)) {
						foreach ($students as $student) {
							$opuniquepercentageArr = [];
							if($student->sroptionalsubjectID > 0) {
								$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
							}

							$anotheropuniquepercentageArr = [];
							if($student->sranotheroptionalsubjectID > 0) {
								$opuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
							}

							$studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;
							if(customCompute($mandatorySubjects)) {
								foreach ($mandatorySubjects as $mandatorySubject) {
									$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

									$markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
									$markpercentagesCount = customCompute($markpercentages);
									if(customCompute($markpercentages)) {
										foreach ($markpercentages as $markpercentageID) {
											$f = false;
	                                        if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
	                                            $f = true;
	                                        }

											if(isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
												if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
												}
											} else {
												if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
												}
											}

											if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

												if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];

												}
											}

											$f = false;
											if(customCompute($opuniquepercentageArr)) {
	                                            if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
	                                                $f = true;
	                                            }
											}
											if(customCompute($anotheropuniquepercentageArr)) {
	                                            if(isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
	                                                $f = true;
	                                            }
											}

											if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
												if($student->sroptionalsubjectID != 0) {
													if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
														if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
														}
													} else {
														if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
														}
													}

													if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

														if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															if($f) {
																$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
															}
														}

													}
												}
												if($student->sranotheroptionalsubjectID != 0) {
													if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
														if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
														}
													} else {
														if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
														}
													}

													if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

														if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															if($f) {
																$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
															}
														}

													}
												}
												$studentChecker['subject'][$student->srstudentID][$markpercentageID] = TRUE;
											}
									  
										

             

										}
									}

									$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];

									if(!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
										if($student->sroptionalsubjectID != 0) {
											$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
										}
										if($student->sranotheroptionalsubjectID != 0) {
											$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
										}
										$studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
									}

									$studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
									if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
										if($student->sroptionalsubjectID != 0) {
											$studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
										}
									}
									if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
										if($student->sranotheroptionalsubjectID != 0) {
											$studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
										}
									}
								}
							}	


							$studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
							$studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

							if(isset($studentPosition['totalStudentMarkAverage'])) {
								$studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
							} else {
								$studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
							}
						}
					}

					arsort($studentClassPositionArray);
					$studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
					if(customCompute($studentSubjectPositionArray)) {
						foreach($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
							arsort($studentSubjectPositionMark);
							$studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
						}
					}
					if((int)$studentID > 0) {
						$queryArray['studentID'] = $studentID;
					}

					$this->data['col']             = 5 + $markpercentagesCount;
					$this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
					$this->data['studentPosition'] = $studentPosition;
					$this->data['percentageArr']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

					$this->reportSendToMail('terminalreport.css', $this->data, 'report/terminal1/TerminalReportPDF',$to, $subject,$message);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
    				exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('terminalreport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('terminalreport_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function getExam() {
		$classesID = $this->input->post('classesID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		echo "<option value='0'>", $this->lang->line("terminalreport_please_select"),"</option>";
		if((int)$classesID) {
			$exams    = pluck($this->examtermsetting_m->get_examtermsettingby_classes(['classesID' => $classesID,'schoolyearID' => $schoolyearID]), 'obj', 'finaltermexamID');
			if(customCompute($exams)) {
				foreach ($exams as $exam) {
					echo "<option value=".$exam->finaltermexamID.">".$exam->exam."</option>";
				}
			}
		}
	}

	public function getSection() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			$sections = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("terminalreport_please_select"),"</option>";
			if(customCompute($sections)) {
				foreach ($sections as $section) {
					echo "<option value=\"$section->sectionID\">".$section->section."</option>";
				}
			}
		}
	}

	public function getStudent() {
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if((int)$classesID && (int)$sectionID) {
			$students = $this->studentrelation_m->general_get_order_by_student(array('srclassesID'=>$classesID,'srsectionID'=>$sectionID,'srschoolyearID'=>$schoolyearID));
			if(customCompute($students)) {
				echo "<option value='0'>". $this->lang->line("terminalreport_please_select") ."</option>";
				foreach($students as $student) {
					echo "<option value=\"$student->srstudentID\">".$student->srname."</option>";
				}
			}
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

	public function class_teacher_upload()
    {
        $setting  = $this->setting_m->get_setting();
		$new_file = "site.png";
        if ( !empty($_FILES) && isset($_FILES["class_teacher"]) && $_FILES["class_teacher"]['name'] != "" ) {
            $file_name        = $_FILES["class_teacher"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512', $random . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if ( customCompute($explode) >= 2 ) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name']     = $new_file;
                // $config['max_size']      = '5120';
                // $config['max_width']     = '3000';
                // $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("class_teacher") ) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['class_teacher'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return false;
            }
        } else {
			$this->upload_data['class_teacher'] = [ 'file_name' => $new_file ];
			return true;
        }
	}
	
	public function incharge_upload()
    {
        $setting  = $this->setting_m->get_setting();
		$new_file = "site.png";
        if ( !empty($_FILES) && isset($_FILES["incharge"]) && $_FILES["incharge"]['name'] != "" ) {
            $file_name        = $_FILES["incharge"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512', $random . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if ( customCompute($explode) >= 2 ) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name']     = $new_file;
                // $config['max_size']      = '5120';
                // $config['max_width']     = '3000';
                // $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("incharge") ) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['incharge'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return false;
            }
        } else {
			$this->upload_data['incharge'] = [ 'file_name' => $new_file ];
			return true;
        }
	}
	

    public function getFooter($sheet,$start,$sections,$footers){
	
		$start1 = $start + 1;
		$start2 = $start + 2;
		$startColnum = 2;
		$startCol =  $this->getNameFromNumber($startColnum);

		//issue date
		$sheet->getStyle($startCol.$start1)->applyFromArray([
			'borders' => [
				'bottom' => [
					'borderStyle' => Border::BORDER_THIN,
				]
			],
	    ]);
		$sheet->setCellValue($startCol.$start, "");
		$sheet->setCellValue($startCol.$start1, $footers['issue_date']);
		$sheet->setCellValue($startCol.$start2, "Issue Date");

		//class teacher
		if($sections){
			$a = $startColnum + 3;
            foreach($sections as $section){
				$b = $this->getNameFromNumber($a);
				$sheet->getStyle($b.$start)->applyFromArray([
					'borders' => [
						'bottom' => [
							'borderStyle' => Border::BORDER_THIN,
						]
					],
				]);
				$sheet->setCellValue($b.$start, "");
				$sheet->setCellValue($b.$start1, 'Class Teacher '."'".$section['section']."'");
				$sheet->setCellValue($b.$start2, $section['teacher_name']);
				$a = $a + 8;
			}
		}else{
			$a = $startColnum + 3;
		}

		//coordinator
		$c = $a + 3;
		$d = $this->getNameFromNumber($c);
		$sheet->getStyle($d.$start)->applyFromArray([
			'borders' => [
				'bottom' => [
					'borderStyle' => Border::BORDER_THIN,
				]
			],
	    ]);
		$sheet->setCellValue($d.$start, "");
		$sheet->setCellValue($d.$start1, "Co-ordinator");
		$sheet->setCellValue($d.$start2, $footers['coordinator']);

		//vice principal
		$e = $c + 10;
		$f = $this->getNameFromNumber($e);
		$sheet->getStyle($f.$start)->applyFromArray([
			'borders' => [
				'bottom' => [
					'borderStyle' => Border::BORDER_THIN,
				]
			],
	    ]);
		$sheet->setCellValue($f.$start, "");
		$sheet->setCellValue($f.$start1, "Vice Principal");
		$sheet->setCellValue($f.$start2, $footers['vice_principal']);

		//priciple
		$g = $e + 10;
		$h = $this->getNameFromNumber($g);
		$sheet->getStyle($h.$start)->applyFromArray([
			'borders' => [
				'bottom' => [
					'borderStyle' => Border::BORDER_THIN,
				]
			],
	    ]);
		$sheet->setCellValue($h.$start, "");
		$sheet->setCellValue($h.$start1, "Principal");
		$sheet->setCellValue($h.$start2, $footers['principal']);
		
		}
	public function convertDateToNepaliInEnglish($date)
    {
		if($date){
			$dateObj = new NepaliCalenderHelper();
			$nepaliDate= $dateObj->convertDateToNepaliInEnglish($date);
			return $nepaliDate['year'] . '-' . $nepaliDate['month'] . '-' . $nepaliDate['date'];
			
		}else{
			return '';
		}
        
	}

	public function convertDateToEnglishInNepali($date)
    {
		if($date){
			$date = explode('-', $date);
			$yy = $date[0];
			$mm = $date[1];
			$dd = $date[2];
			$dateObj = new NepaliCalenderHelper();
			$engDate= $dateObj->nep_to_eng($yy, $mm, $dd);
			return $engDate['year'] . '-' . $engDate['month'] . '-' . $engDate['date'];
	
		}else{
			return '';
		}
	}

}