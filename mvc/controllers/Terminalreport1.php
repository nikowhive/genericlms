<?php

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use \PhpOffice\PhpSpreadsheet\Style\Border;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Terminalreport1 extends Admin_Controller
{
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
	function __construct()
	{
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
		$this->load->helper('nepali_calendar_helper');

		$language = $this->session->userdata('lang');
		$this->lang->load('terminalreport', $language);
	}

	protected function rules()
	{
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

	protected function send_pdf_to_mail_rules()
	{
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

	public function index()
	{
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

		$this->data["subview"]  = "report/terminal1/TerminalReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getTerminalreport()
	{
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if (permissionChecker('terminalreport')) {
			if ($_POST) {
				$date       = $this->input->post('date');
				// $verified_by  = $this->input->post('verified_by');
				// $school_days  = $this->input->post('school_days');
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
					$array['class_teacher']    = $this->upload_data['class_teacher']['file_name'];
					$array['incharge']    = $this->upload_data['incharge']['file_name'];
					$this->setting_m->insertorupdate($array);

					$this->data['setting'] = $this->setting_m->get_setting();
					$this->data['examID']     = $examID;
					$this->data['classesID']  = $classesID;
					$this->data['sectionID']  = $sectionID;
					$this->data['studentIDD'] = $studentID;
					$this->data['date'] 	  = $date;
					// $this->data['verified_by'] = $verified_by;
					// $this->data['school_days'] = $school_days;

					$queryArray        = [];
					$studentQueryArray = [];
					$queryArray['schoolyearID']          = $schoolyearID;
					$studentQueryArray['srschoolyearID'] = $schoolyearID;

					if ((int)$examID > 0) {
						$queryArray['examID'] = $examID;
					}
					if ((int)$classesID > 0) {
						$queryArray['classesID'] = $classesID;
						$studentQueryArray['srclassesID'] = $classesID;
					}
					if ((int)$sectionID > 0) {
						$queryArray['sectionID'] = $sectionID;
						$studentQueryArray['srsectionID'] = $sectionID;
					}
					if ((int)$studentID > 0) {
						$studentQueryArray['srstudentID'] = $studentID;
					}

					$class = $this->classes_m->get_single_classes(['classesID' => $classesID]);
					$this->data['class'] = $class;
					$exam      = $this->exam_m->get_single_exam(['examID' => $examID]);
					$this->data['exam']         = $exam;
					$this->data['exam']->date_in_nepali = $this->convertDateToNepaliInEnglish($exam->date);
					$this->data['exam']->issue_date_in_english = $this->convertDateToEnglishInNepali($exam->issue_date);
					$this->data['examName']     = $exam->exam;
					$this->data['grades']       = $this->grade_m->get_grade();
					$this->data['classes']      = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['sections']     = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
					$this->data['class_teacher'] = pluck($this->section_m->get_join_sections(), 'name', 'sectionID');
					$this->data['groups']       = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
					$this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);
					$this->data['remarks'] 		= pluck($this->studentremark_m->get_order_by_studentremark(['examID' => $examID, 'classID' => $classesID]), 'remarks', 'studentID');
					$students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
					$marks                  = $this->mark_m->student_all_mark_array($queryArray);
					$mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
					$coscholasticSubjects      = $this->subjectmark_m->get_enabled_coscholastic(array('classesID' => $classesID, 'examID' => $examID, 'type' => 1));
				
					
					$this->data['mandatorySubjects'] = $mandatorySubjects;
					$this->data['coscholasticSubjects'] = $coscholasticSubjects;

					$this->subject_m->order('type DESC');
					$this->data['subjects'] = $this->subject_m->get_by_class_id($classesID);
					$this->data['subject_marks'] = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID, 'class_id' => $classesID]), 'fullmark', 'subject_id');

					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$settingmarktypeID1      = $this->data['siteinfos']->marktypeID;
					$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
					$markpercentagesmainArr1 = $this->marksetting_m->get_marksetting_markpercentages();
					$markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
					$markpercentagesArr1     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
					$this->data['markpercentagesArr']  = $markpercentagesArr;
					$this->data['settingmarktypeID']   = $settingmarktypeID;

					$this->data['markpercentagesArr1']  = $markpercentagesArr;
					$this->data['settingmarktypeID1']   = $settingmarktypeID;

					$retMark = [];
					if (customCompute($marks)) {
						foreach ($marks as $mark) {
							$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark ? $mark->mark : 0;
						}
					}

					$retMark1 = [];
					if (customCompute($marks)) {
						foreach ($marks as $mark) {
							$retMark1[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark ? $mark->mark : 0;
						}
					}

					$highestMarks    = [];
					foreach ($marks as $value) {
						if (!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
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

					// if(customCompute($this->data['studentLists'])) {
					// 	foreach($this->data['studentLists'] as $student) {
					// 		$student->dob_in_bs = $this->convertDateToNepaliInEnglish($student->dob);
					// 	}
					// }

					if (customCompute($students)) {
						foreach ($students as $student) {
							$opuniquepercentageArr = [];
							$anotheropuniquepercentageArr = [];
							if ($student->sroptionalsubjectID > 0) {
								$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
							}

							if ($student->sranotheroptionalsubjectID > 0) {
								$anotheropuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
							}

							$opuniquepercentageArr1 = [];
							if ($student->sroptionalsubjectID > 0) {
								$opuniquepercentageArr1 = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
							}

							$anotheropuniquepercentageArr1 = [];
							if ($student->sranotheroptionalsubjectID > 0) {
								$anotheropuniquepercentageArr1 = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
							}

							$studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;

							$studentPosition1[$student->srstudentID]['totalSubjectMark'] = 0;

							if (customCompute($mandatorySubjects)) {
								foreach ($mandatorySubjects as $mandatorySubject) {
									$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];
									$markpercentages = $uniquepercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'];
									$markpercentagesCount = customCompute($markpercentages);
									if (customCompute($markpercentages)) {


										foreach ($markpercentages as $markpercentageID) {
											$f = false;
											if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
												$f = true;
											}

											if (isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
												if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
												}
											} else {
												if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
												}
											}

											if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

												if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
												}
											}

											$f = false;
											if (customCompute($opuniquepercentageArr)) {
												if (isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
													$f = true;
												}
											}
											if (customCompute($anotheropuniquepercentageArr)) {
												if (isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
													$f = true;
												}
											}

											if (!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
												if ($student->sroptionalsubjectID != 0) {
													if (isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
														if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
														}
													} else {
														if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
														}
													}

													if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

														if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															if ($f) {
																$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
															}
														}
													}
												}
												if ($student->sranotheroptionalsubjectID != 0) {
													if (isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
														if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
														}
													} else {
														if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
														}
													}

													if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

														if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															if ($f) {
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

									if (!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
										if ($student->sroptionalsubjectID != 0) {
											$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
										}
										if ($student->sranotheroptionalsubjectID != 0) {
											$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
										}
										$studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
									}

									$studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
									if (!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
										if ($student->sroptionalsubjectID != 0) {
											$studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
										}
										if ($student->sranotheroptionalsubjectID != 0) {
											$studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
										}
									}
								}
							}

							if (customCompute($coscholasticSubjects)) {
								foreach ($coscholasticSubjects as $coscholasticSubject) {
									$uniquepercentageArr1 = isset($markpercentagesArr1[$coscholasticSubject->subjectID]) ? $markpercentagesArr1[$coscholasticSubject->subjectID] : [];

									$markpercentages1 = $uniquepercentageArr1[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'];
									$markpercentagesCount1 = customCompute($markpercentages);
									if (customCompute($markpercentages1)) {
										foreach ($markpercentages1 as $markpercentageID) {
											$f = false;
											if (isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
												$f = true;
											}

											if (isset($studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID])) {
												if (isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
													$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
												} else {
													$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += 0;
												}
											} else {
												if (isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
													$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
												} else {
													$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = 0;
												}
											}

											if (isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];

												if (isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
													$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];
												} else {
													$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];
												}
											}

											$f = false;
											if (customCompute($opuniquepercentageArr1)) {
												if (isset($opuniquepercentageArr1['own']) && in_array($markpercentageID, $opuniquepercentageArr1['own'])) {
													$f = true;
												}
											}

											if (!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
												if ($student->sroptionalsubjectID != 0) {
													if (isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
														if (isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
														}
													} else {
														if (isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
														}
													}

													if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

														if (isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															if ($f) {
																$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
															}
														}
													}
												}

												if ($student->sranotheroptionalsubjectID != 0) {
													if (isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
														if (isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
														}
													} else {
														if (isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
														}
													}

													if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

														if (isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															if ($f) {
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

									if (!isset($studentChecker1['totalSubjectMark'][$student->srstudentID])) {
										// if($student->sroptionalsubjectID != 0) {
										// 	$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
										// }
										$studentChecker1['totalSubjectMark'][$student->srstudentID] = TRUE;
									}

									$studentSubjectPositionArray1[$coscholasticSubject->subjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];
									if (!isset($studentChecker1['studentSubjectPositionArray'][$student->srstudentID])) {
										// if($student->sroptionalsubjectID != 0) {
										// 	$studentSubjectPositionArray1[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
										// }
									}
								}
							}


							$studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
							$studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

							if (isset($studentPosition['totalStudentMarkAverage'])) {
								$studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
							} else {
								$studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
							}

							if (count($coscholasticSubjects) > 0) {
								$studentPosition1[$student->srstudentID]['classPositionMark'] = ($studentPosition1[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition1[$student->srstudentID]['subjectMark']));
								$studentClassPositionArray1[$student->srstudentID]             = $studentPosition1[$student->srstudentID]['classPositionMark'];

								if (isset($studentPosition1['totalStudentMarkAverage'])) {
									$studentPosition1['totalStudentMarkAverage'] += $studentPosition1[$student->srstudentID]['classPositionMark'];
								} else {
									$studentPosition1['totalStudentMarkAverage']  = $studentPosition1[$student->srstudentID]['classPositionMark'];
								}
							}
						}
					}

					arsort($studentClassPositionArray);
					$studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
					if (customCompute($studentSubjectPositionArray)) {
						foreach ($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
							arsort($studentSubjectPositionMark);
							$studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
						}
					}
					if ((int)$studentID > 0) {
						$queryArray['studentID'] = $studentID;
					}

					arsort($studentClassPositionArray1);
					$studentPosition1['studentClassPositionArray'] = $studentClassPositionArray1;
					if (customCompute($studentSubjectPositionArray1)) {
						foreach ($studentSubjectPositionArray1 as $subjectID => $studentSubjectPositionMark1) {
							arsort($studentSubjectPositionMark1);
							$studentPosition1['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark1;
						}
					}
					if ((int)$studentID > 0) {
						$queryArray['studentID'] = $studentID;
					}

					$this->data['col']             = 5 + $markpercentagesCount;
					$this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
					$this->data['studentPosition'] = $studentPosition;
					$this->data['percentageArr']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');



					$this->data['col1']             = 5 + $markpercentagesCount1;
					$this->data['studentPosition1'] = $studentPosition1;
					$this->data['percentageArr1']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

					// if($class->classes_numeric == 1 || $class->classes_numeric == 2 || $class->classes_numeric == 3) {
					// $retArray['render'] = $this->load->view('report/terminal1/PrimaryTerminalReport',$this->data,true);
					// } else if($class->classes_numeric == 11 || $class->classes_numeric == 12) {	
					// 	$retArray['render'] = $this->load->view('report/terminal1/TerminalReport_11_12',$this->data,true);
					// } else {
					//$retArray['render'] = $this->load->view('report/terminal1/TerminalReport',$this->data,true);
					// }

					if ($class->classes_numeric >= 1 && $class->classes_numeric <= 7) {
						if($examID == 9){
							$retArray['render'] = $this->load->view('report/terminal1/TerminalReport_finalterm', $this->data, true);
						}if($examID == 10){
							$retArray['render'] = $this->load->view('report/terminal1/TerminalReport_1_to_7_annual_exam_2078', $this->data, true);
						}else{
							$retArray['render'] = $this->load->view('report/terminal1/TerminalReport_1_to_7', $this->data, true);
						}
					} else {
						if ($examID == 4 || $examID == 5 || $examID == 6 || $examID == 9) {
							$retArray['render'] = $this->load->view('report/terminal1/TerminalReport_finalterm', $this->data, true);
						} else if ($examID == 8) {
							$retArray['render'] = $this->load->view('report/terminal1/TerminalReport_new', $this->data, true);
						}elseif($examID == 10){
							$retArray['render'] = $this->load->view('report/terminal1/TerminalReport_1_to_7_annual_exam_2078', $this->data, true);
						} else {
							$retArray['render'] = $this->load->view('report/terminal1/TerminalReport', $this->data, true);
						}
					}
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

	private function get_student_attendance($queryArray, $subjects, $studentlists)
	{
		unset($queryArray['examID']);
		$newArray = [];
		$attendanceArray = [];
		$getWeekendDay = $this->getWeekendDays();
		$getHoliday    = explode('","', $this->getHolidays());

		if ($this->data['siteinfos']->attendance == 'subject') {
			$attendances   = $this->subjectattendance_m->get_order_by_sub_attendance($queryArray);

			if (customCompute($attendances)) {
				foreach ($attendances as $attendance) {
					$monthyearArray = explode('-', $attendance->monthyear);
					$monthDay = date('t', mktime(0, 0, 0, $monthyearArray['0'], 1, $monthyearArray['1']));
					for ($i = 1; $i <= $monthDay; $i++) {
						$currentDate = sprintf("%02d", $i) . '-' . $attendance->monthyear;
						if (in_array($currentDate, $getHoliday)) {
							continue;
						} elseif (in_array($currentDate, $getWeekendDay)) {
							continue;
						} else {
							$day = 'a' . $i;
							if ($attendance->$day == 'P' || $attendance->$day == 'L' || $attendance->$day == 'LE') {
								if (!isset($newArray[$attendance->studentID][$attendance->subjectID]['pCount'])) {
									$newArray[$attendance->studentID][$attendance->subjectID]['pCount'] = 1;
								} else {
									$newArray[$attendance->studentID][$attendance->subjectID]['pCount'] += 1;
								}
							} else {
								if (!isset($newArray[$attendance->studentID][$attendance->subjectID]['aCount'])) {
									$newArray[$attendance->studentID][$attendance->subjectID]['aCount'] = 1;
								} else {
									$newArray[$attendance->studentID][$attendance->subjectID]['aCount'] += 1;
								}
							}
							if (!isset($newArray[$attendance->studentID][$attendance->subjectID]['tCount'])) {
								$newArray[$attendance->studentID][$attendance->subjectID]['tCount'] = 1;
							} else {
								$newArray[$attendance->studentID][$attendance->subjectID]['tCount'] += 1;
							}
						}
					}
				}

				$studentlistsArray = pluck($studentlists, 'sroptionalsubjectID', 'srstudentID');
				$subjects  = pluck($subjects, 'obj', 'subjectID');

				if (customCompute($newArray)) {
					foreach ($newArray as $studentID => $array) {
						$str = '';
						if (customCompute($subjects)) {
							foreach ($subjects as $subjectID => $subject) {
								if ($subject->type == '1') {
									$pCount = isset($array[$subjectID]['pCount']) ? $array[$subjectID]['pCount'] : '0';
									$tCount = isset($array[$subjectID]['tCount']) ? $array[$subjectID]['tCount'] : '0';
									$str .= $subjects[$subjectID]->subject . ":" . $pCount . "/" . $tCount . ',';
								}
							}
						}

						if (isset($studentlistsArray[$studentID]) && $studentlistsArray[$studentID] != '0') {
							$pCount = isset($newArray[$studentID][$studentlistsArray[$studentID]]['pCount']) ? $newArray[$studentID][$studentlistsArray[$studentID]]['pCount'] : '0';
							$tCount = isset($newArray[$studentID][$studentlistsArray[$studentID]]['tCount']) ? $newArray[$studentID][$studentlistsArray[$studentID]]['tCount'] : '0';
							$str .= $subjects[$subjectID]->subject . ":" . $pCount . "/" . $tCount . ',';
						}

						$attendanceArray[$studentID] = $str;
					}
				}
			}
		} else {
			$attendances   = $this->sattendance_m->get_order_by_attendance($queryArray);
			if (customCompute($attendances)) {
				foreach ($attendances as $attendance) {
					$monthyearArray = explode('-', $attendance->monthyear);
					$monthDay = date('t', mktime(0, 0, 0, $monthyearArray['0'], 1, $monthyearArray['1']));
					for ($i = 1; $i <= $monthDay; $i++) {
						$currentDate = sprintf("%02d", $i) . '-' . $attendance->monthyear;
						if (in_array($currentDate, $getHoliday)) {
							continue;
						} elseif (in_array($currentDate, $getWeekendDay)) {
							continue;
						} else {
							$day = 'a' . $i;
							if ($attendance->$day == 'P' || $attendance->$day == 'L' || $attendance->$day == 'LE') {
								if (!isset($newArray[$attendance->studentID]['pCount'])) {
									$newArray[$attendance->studentID]['pCount'] = 1;
								} else {
									$newArray[$attendance->studentID]['pCount'] += 1;
								}
							} else {
								if (!isset($newArray[$attendance->studentID]['aCount'])) {
									$newArray[$attendance->studentID]['aCount'] = 1;
								} else {
									$newArray[$attendance->studentID]['aCount'] += 1;
								}
							}
							if (!isset($newArray[$attendance->studentID]['tCount'])) {
								$newArray[$attendance->studentID]['tCount'] = 1;
							} else {
								$newArray[$attendance->studentID]['tCount'] += 1;
							}
						}
					}
					$pCount = isset($newArray[$attendance->studentID]['pCount']) ? $newArray[$attendance->studentID]['pCount'] : '0';
					$tCount = isset($newArray[$attendance->studentID]['tCount']) ? $newArray[$attendance->studentID]['tCount'] : '0';
					$attendanceArray[$attendance->studentID] = $pCount . "/" . $tCount;
				}
			}
		}
		return $attendanceArray;
	}

	public function pdf()
	{
		if (permissionChecker('terminalreport')) {
			$examID = htmlentities(escapeString($this->uri->segment(3)));
			$classesID  = htmlentities(escapeString($this->uri->segment(4)));
			$sectionID  = htmlentities(escapeString($this->uri->segment(5)));
			$studentID  = htmlentities(escapeString($this->uri->segment(6)));
			$date  = htmlentities(escapeString($this->uri->segment(7)));
			// $verified_by  = htmlentities(escapeString($this->uri->segment(8)));
			// $school_days  = htmlentities(escapeString($this->uri->segment(9)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if ((int)$examID && (int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
				$this->data['examID']     = $examID;
				$this->data['classesID']  = $classesID;
				$this->data['sectionID']  = $sectionID;
				$this->data['studentIDD'] = $studentID;
				$this->data['date'] = urldecode($date);
				// $this->data['verified_by'] = $verified_by;
				// $this->data['school_days'] = $school_days;

				$queryArray        = [];
				$studentQueryArray = [];
				$queryArray['schoolyearID']          = $schoolyearID;
				$studentQueryArray['srschoolyearID'] = $schoolyearID;

				if ((int)$examID > 0) {
					$queryArray['examID'] = $examID;
				}
				if ((int)$classesID > 0) {
					$queryArray['classesID'] = $classesID;
					$studentQueryArray['srclassesID'] = $classesID;
				}
				if ((int)$sectionID > 0) {
					$queryArray['sectionID'] = $sectionID;
					$studentQueryArray['srsectionID'] = $sectionID;
				}
				if ((int)$studentID > 0) {
					$studentQueryArray['srstudentID'] = $studentID;
				}

				$class = $this->classes_m->get_single_classes(['classesID' => $classesID]);
				$exam      = $this->exam_m->get_single_exam(['examID' => $examID]);
				$this->data['class'] = $class;
				$this->data['exam'] = $exam;
				$this->data['exam']->date_in_nepali = $this->convertDateToNepaliInEnglish($exam->date);
				$this->data['exam']->issue_date_in_english = $this->convertDateToEnglishInNepali($exam->issue_date);
				$this->data['examName']     = $exam->exam;
				$this->data['grades']       = $this->grade_m->get_grade();
				$this->data['classes']      = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
				$this->data['sections']     = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
				$this->data['class_teacher'] = pluck($this->section_m->get_join_sections(), 'name', 'sectionID');
				$this->data['groups']       = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
				$this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);
				$this->data['remarks'] 		= pluck($this->studentremark_m->get_order_by_studentremark(['examID' => $examID, 'classID' => $classesID]), 'remarks', 'studentID');
				$students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
				$marks                  = $this->mark_m->student_all_mark_array($queryArray);
				$mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
				$coscholasticSubjects      = $this->subjectmark_m->get_enabled_coscholastic(array('classesID' => $classesID, 'examID' => $examID, 'type' => 1));

				$this->data['mandatorySubjects'] = $mandatorySubjects;
				$this->data['coscholasticSubjects'] = $coscholasticSubjects;

				$this->subject_m->order('type DESC');
				$this->data['subjects'] = $this->subject_m->get_by_class_id($classesID);
				$this->data['subject_marks'] = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID, 'class_id' => $classesID]), 'fullmark', 'subject_id');


				$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
				$settingmarktypeID1      = $this->data['siteinfos']->marktypeID;
				$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
				$markpercentagesmainArr1 = $this->marksetting_m->get_marksetting_markpercentages();
				$markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
				$markpercentagesArr1     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
				$this->data['markpercentagesArr']  = $markpercentagesArr;
				$this->data['settingmarktypeID']   = $settingmarktypeID;

				$this->data['markpercentagesArr1']  = $markpercentagesArr;
				$this->data['settingmarktypeID1']   = $settingmarktypeID;

				$retMark = [];
				if (customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark ? $mark->mark : 0;
					}
				}

				$retMark1 = [];
				if (customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark1[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark ? $mark->mark : 0;
					}
				}

				$highestMarks    = [];
				foreach ($marks as $value) {
					if (!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
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

				// if(customCompute($this->data['studentLists'])) {
				// 	foreach($this->data['studentLists'] as $student) {
				// 		$student->dob_in_bs = $this->convertDateToNepaliInEnglish($student->dob);
				// 	}
				// }

				if (customCompute($students)) {
					foreach ($students as $student) {
						$opuniquepercentageArr = [];
						$anotheropuniquepercentageArr = [];
						if ($student->sroptionalsubjectID > 0) {
							$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
						}

						if ($student->sranotheroptionalsubjectID > 0) {
							$anotheropuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
						}

						$opuniquepercentageArr1 = [];
						$anotheropuniquepercentageArr1 = [];
						if ($student->sroptionalsubjectID > 0) {
							$opuniquepercentageArr1 = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
						}

						if ($student->sranotheroptionalsubjectID > 0) {
							$anotheropuniquepercentageArr1 = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
						}

						$studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;

						$studentPosition1[$student->srstudentID]['totalSubjectMark'] = 0;

						if (customCompute($mandatorySubjects)) {
							foreach ($mandatorySubjects as $mandatorySubject) {
								$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

								$markpercentages = $uniquepercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'];
								$markpercentagesCount = customCompute($markpercentages);
								if (customCompute($markpercentages)) {
									foreach ($markpercentages as $markpercentageID) {
										$f = false;
										if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
											$f = true;
										}

										if (isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
											if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
											} else {
												$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
											}
										} else {
											if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
											} else {
												$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
											}
										}

										if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
											$studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

											if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
												$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
											} else {
												$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
											}
										}

										$f = false;
										if (customCompute($opuniquepercentageArr)) {
											if (isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
												$f = true;
											}
										}
										if (customCompute($anotheropuniquepercentageArr)) {
											if (isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
												$f = true;
											}
										}

										if (!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
											if ($student->sroptionalsubjectID != 0) {
												if (isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
													if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
													}
												} else {
													if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
													}
												}

												if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

													if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
													} else {
														if ($f) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
														}
													}
												}
											}
											if ($student->sranotheroptionalsubjectID != 0) {
												if (isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
													if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
													}
												} else {
													if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
													}
												}

												if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

													if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
													} else {
														if ($f) {
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

								if (!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
									if ($student->sroptionalsubjectID != 0) {
										$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
									}
									if ($student->sranotheroptionalsubjectID != 0) {
										$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
									}
									$studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
								}

								$studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
								if (!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
									if ($student->sroptionalsubjectID != 0) {
										$studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
									}
									if ($student->sranotheroptionalsubjectID != 0) {
										$studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
									}
								}
							}
						}

						if (customCompute($coscholasticSubjects)) {
							foreach ($coscholasticSubjects as $coscholasticSubject) {
								$uniquepercentageArr1 = isset($markpercentagesArr1[$coscholasticSubject->subjectID]) ? $markpercentagesArr1[$coscholasticSubject->subjectID] : [];

								$markpercentages1 = $uniquepercentageArr1[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'];
								$markpercentagesCount1 = customCompute($markpercentages);
								if (customCompute($markpercentages1)) {
									foreach ($markpercentages1 as $markpercentageID) {
										$f = false;
										if (isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
											$f = true;
										}

										if (isset($studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID])) {
											if (isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
											} else {
												$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += 0;
											}
										} else {
											if (isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
											} else {
												$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = 0;
											}
										}

										if (isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
											$studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];

											if (isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
												$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];
											} else {
												$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];
											}
										}

										$f = false;
										if (customCompute($opuniquepercentageArr1)) {
											if (isset($opuniquepercentageArr1['own']) && in_array($markpercentageID, $opuniquepercentageArr1['own'])) {
												$f = true;
											}
										}

										if (!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
											if ($student->sroptionalsubjectID != 0) {
												if (isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
													if (isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
													}
												} else {
													if (isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
													}
												}

												if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
													$studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

													if (isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
														$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
													} else {
														if ($f) {
															$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
														}
													}
												}
											}

											if ($student->sranotheroptionalsubjectID != 0) {
												if (isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
													if (isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
													}
												} else {
													if (isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
													} else {
														$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
													}
												}

												if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
													$studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

													if (isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
														$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
													} else {
														if ($f) {
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

								if (!isset($studentChecker1['totalSubjectMark'][$student->srstudentID])) {
									// if($student->sroptionalsubjectID != 0) {
									// 	$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
									// }
									$studentChecker1['totalSubjectMark'][$student->srstudentID] = TRUE;
								}

								$studentSubjectPositionArray1[$coscholasticSubject->subjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];
								if (!isset($studentChecker1['studentSubjectPositionArray'][$student->srstudentID])) {
									// if($student->sroptionalsubjectID != 0) {
									// 	$studentSubjectPositionArray1[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
									// }
								}
							}
						}


						$studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
						$studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

						if (isset($studentPosition['totalStudentMarkAverage'])) {
							$studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
						} else {
							$studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
						}

						if (count($coscholasticSubjects) > 0) {
							$studentPosition1[$student->srstudentID]['classPositionMark'] = ($studentPosition1[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition1[$student->srstudentID]['subjectMark']));
							$studentClassPositionArray1[$student->srstudentID]             = $studentPosition1[$student->srstudentID]['classPositionMark'];

							if (isset($studentPosition1['totalStudentMarkAverage'])) {
								$studentPosition1['totalStudentMarkAverage'] += $studentPosition1[$student->srstudentID]['classPositionMark'];
							} else {
								$studentPosition1['totalStudentMarkAverage']  = $studentPosition1[$student->srstudentID]['classPositionMark'];
							}
						}
					}
				}

				arsort($studentClassPositionArray);
				$studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
				if (customCompute($studentSubjectPositionArray)) {
					foreach ($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
						arsort($studentSubjectPositionMark);
						$studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
					}
				}
				if ((int)$studentID > 0) {
					$queryArray['studentID'] = $studentID;
				}

				arsort($studentClassPositionArray1);
				$studentPosition1['studentClassPositionArray'] = $studentClassPositionArray1;
				if (customCompute($studentSubjectPositionArray1)) {
					foreach ($studentSubjectPositionArray1 as $subjectID => $studentSubjectPositionMark1) {
						arsort($studentSubjectPositionMark1);
						$studentPosition1['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark1;
					}
				}
				if ((int)$studentID > 0) {
					$queryArray['studentID'] = $studentID;
				}

				$this->data['col']             = 5 + $markpercentagesCount;
				$this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
				$this->data['studentPosition'] = $studentPosition;
				$this->data['percentageArr']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');


				$this->data['col1']             = 5 + $markpercentagesCount1;
				$this->data['studentPosition1'] = $studentPosition1;
				$this->data['percentageArr1']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

				// if($class->classes_numeric == 1 || $class->classes_numeric == 2 || $class->classes_numeric == 3) {
				// $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/PrimaryTerminalReportPDF', 'view', 'a4', 'portrait');
				// } else if($class->classes_numeric == 11 || $class->classes_numeric == 12) {	
				// 	$this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF_11_12', 'view', 'a4', 'portrait');
				// } else {
				// $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF', 'view', 'a4', 'landscape', 'custom');
				// }
				if ($class->classes_numeric >= 1 && $class->classes_numeric <= 7) {
					 if($examID == 9){
						$this->reportPDF('terminalreport_final.css', $this->data, 'report/terminal1/TerminalReportPDF_finalterm', 'view', 'a4', 'landscape', 'custom');
					}elseif($examID == 10){
						$this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF_1_to_7_annual_exam_2078', 'view', 'a4', 'landscape', 'custom');
					}else{
						$this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF_1_to_7', 'view', 'a4', 'landscape', 'custom');
					}
				} else {
					if ($examID == 4 || $examID == 5 || $examID == 6 || $examID == 9) {
						$this->reportPDF('terminalreport_final.css', $this->data, 'report/terminal1/TerminalReportPDF_finalterm', 'view', 'a4', 'landscape', 'custom');
					} else if ($examID == 8) {
						$this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF_new', 'view', 'a4', 'landscape', 'custom');
					}elseif($examID == 10){
						$this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF_1_to_7_annual_exam_2078', 'view', 'a4', 'landscape', 'custom');
					} else {
						$this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF', 'view', 'a4', 'landscape');
					}
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function download_excel()
	{
		$examID = htmlentities(escapeString($this->uri->segment(3)));
		$classesID  = htmlentities(escapeString($this->uri->segment(4)));
		$sectionID  = htmlentities(escapeString($this->uri->segment(5)));
		$studentID  = htmlentities(escapeString($this->uri->segment(6)));
		$date  = htmlentities(escapeString($this->uri->segment(7)));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if ((int)$examID && (int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
			$this->data['examID']     = $examID;
			$this->data['classesID']  = $classesID;
			$this->data['sectionID']  = $sectionID;
			$this->data['studentIDD'] = $studentID;
			$this->data['date'] = urldecode($date);

			$queryArray        = [];
			$studentQueryArray = [];
			$queryArray['schoolyearID']          = $schoolyearID;
			$studentQueryArray['srschoolyearID'] = $schoolyearID;

			if ((int)$examID > 0) {
				$queryArray['examID'] = $examID;
			}
			if ((int)$classesID > 0) {
				$queryArray['classesID'] = $classesID;
				$studentQueryArray['srclassesID'] = $classesID;
			}
			if ((int)$sectionID > 0) {
				$queryArray['sectionID'] = $sectionID;
				$studentQueryArray['srsectionID'] = $sectionID;
			}
			if ((int)$studentID > 0) {
				$studentQueryArray['srstudentID'] = $studentID;
			}

			$exam      = $this->exam_m->get_single_exam(['examID' => $examID]);
			$exam_name     = $exam->exam;
			$grades       = $this->grade_m->get_grade();
			$classes      = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
			$sections     = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
			$this->data['class_teacher'] = pluck($this->section_m->get_join_sections(), 'name', 'sectionID');
			$groups       = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
			$studentLists = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);
			$classsections = $this->section_m->get_join_section($classesID);

			$students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
			$marks                  = $this->mark_m->student_all_mark_array($queryArray);
			$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 1));

			$this->subject_m->order('type DESC');
			$subjects = $this->subject_m->get_by_class_id($classesID);
			$this->data['subject_marks'] = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID, 'class_id' => $classesID]), 'fullmark', 'subject_id');


			$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
			$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
			$markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
			$this->data['markpercentagesArr']  = $markpercentagesArr;
			$this->data['settingmarktypeID']   = $settingmarktypeID;

			$retMark = [];
			if (customCompute($marks)) {
				foreach ($marks as $mark) {
					$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark ? $mark->mark : 0;
				}
			}

			$studentPosition             = [];
			$studentChecker              = [];
			$studentClassPositionArray   = [];
			$studentSubjectPositionArray = [];
			$markpercentagesCount        = 0;
			if (customCompute($students)) {
				foreach ($students as $student) {

					$opuniquepercentageArr = [];
					if ($student->sroptionalsubjectID > 0) {
						$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
					}

					$anotheropuniquepercentageArr = [];
					if ($student->sranotheroptionalsubjectID > 0) {
						$anotheropuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
					}

					$studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;
					if (customCompute($mandatorySubjects)) {
						foreach ($mandatorySubjects as $mandatorySubject) {
							$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

							$markpercentages = $uniquepercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'];
							$markpercentagesCount = customCompute($markpercentages);
							if (customCompute($markpercentages)) {
								foreach ($markpercentages as $markpercentageID) {
									$f = false;
									if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
										$f = true;
									}

									if (isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
										if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
											$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
										} else {
											$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
										}
									} else {
										if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
											$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
										} else {
											$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
										}
									}

									if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
										$studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

										if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
											$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
										} else {
											$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
										}
									}

									$f = false;
									if (customCompute($opuniquepercentageArr)) {
										if (isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
											$f = true;
										}
									}
									if (customCompute($anotheropuniquepercentageArr)) {
										if (isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
											$f = true;
										}
									}

									if (!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {

										if ($student->sroptionalsubjectID != 0) {
											if (isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
												if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
												}
											} else {
												if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
												}
											}

											if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
												$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

												if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
												} else {
													if ($f) {
														$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
													}
												}
											}
										}

										if ($student->sranotheroptionalsubjectID != 0) {


											if (isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
												if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
												}
											} else {
												if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
												}
											}

											if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
												$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

												if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
												} else {
													if ($f) {
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

							if (!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
								if ($student->sroptionalsubjectID != 0) {
									$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
								}

								if ($student->sranotheroptionalsubjectID != 0) {
									$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
								}
								$studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
							}

							$studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
							if (!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
								if ($student->sroptionalsubjectID != 0) {
									$studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
								}
							}
						}
					}


					$studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
					$studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

					if (isset($studentPosition['totalStudentMarkAverage'])) {
						$studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
					} else {
						$studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
					}
				}
			}

			arsort($studentClassPositionArray);
			$studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
			if (customCompute($studentSubjectPositionArray)) {
				foreach ($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
					arsort($studentSubjectPositionMark);
					$studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
				}
			}
			if ((int)$studentID > 0) {
				$queryArray['studentID'] = $studentID;
			}

			$col             = 5 + $markpercentagesCount;
			$attendance      = $this->get_student_attendance($queryArray, $subjects, $studentLists);
			$studentPosition = $studentPosition;
			$percentageArr   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
			$class = $this->classes_m->get_single_classes(['classesID' => $classesID]);

			$rows[] = ['', ''];
			$rows[] = ['', isset($classes[$student->srclassesID]) ? 'Class ' . $classes[$student->srclassesID] : ''];
			$rows[] = ['S No.', 'Student Name '];

			if (customCompute($subjects)) {
				foreach ($subjects as $index => $subject) {
					array_push($rows[0], $subject->subject);
					array_push($rows[1], 'Term Marks');
					array_push($rows[1], 'CAS');
					for ($i = 0; $i < count($markpercentages) + 1; $i++) {
						array_push($rows[0], '');
						if ($i != count($markpercentages)) {
							array_push($rows[1], '');
						}
					}
					foreach ($markpercentages as $markpercentageID) {
						$markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
						if ($markpercentage == 'theory' || $markpercentage == 'Theory') {
							array_push($rows[2], $markpercentage . ' (' . $percentageArr[$markpercentageID]->percentage . ')');
						}
					}

					foreach ($markpercentages as $markpercentageID) {
						$markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
						if ($markpercentage != 'theory' && $markpercentage != 'Theory') {
							array_push($rows[2], $markpercentage . ' (' . $percentageArr[$markpercentageID]->percentage . ')');
						}
					}
					array_push($rows[2], 'Grand Total');
					array_push($rows[2], 'Total Grade');
				}
			}
			array_push($rows[0], 'Grand Total');
			array_push($rows[0], 'Total Grade');

			if (customCompute($studentLists)) {
				foreach ($studentLists as $index => $student) {
					$row[] = $index + 1;
					$row[] = $student->name;

					$total_subject_mark = 0;
					$subject_count = 0;
					foreach ($subjects as $index => $subject) {
						$percentageMark  = 0;
						$subject_count = $index + 1;

						foreach ($markpercentages as $markpercentageID) {
							$f = false;
							if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
								$f = true;
								$percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
							}
							$markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
							if ($markpercentage == 'theory' || $markpercentage == 'Theory') {
								if (isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {
									$row[] = $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID];
								} else {
									if ($f) {
										$row[] = '*';
									}
								}
							}
						}
						foreach ($markpercentages as $markpercentageID) {
							$markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
							if ($markpercentage != 'theory' && $markpercentage != 'Theory') {
								if (isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {
									$row[] = $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID];
								} else {
									if ($f) {
										$row[] = '*';
									}
								}
							}
						}
						$subjectMark = isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';
						$subjectMark = markCalculationView($subjectMark, $subject->finalmark, $percentageMark);

						$total_subject_mark += $subjectMark;
						$mark_exist = false;
						if (customCompute($grades)) {
							foreach ($grades as $grade) {
								if (($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark)) {
									$mark_exist = true;
									$row[] = $grade->point;
									$row[] = $grade->grade;
								}
							}
						}
						if (!$mark_exist) {
							$row[] = '';
							$row[] = '';
						}
					}

					$total_subject_mark = $total_subject_mark / $subject_count;
					if (isset($studentPosition[$student->srstudentID]['classPositionMark']) && $studentPosition[$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition['totalStudentMarkAverage']) && $total_subject_mark > 1) {
						if (customCompute($grades)) {
							foreach ($grades as $grade) {
								if (($grade->gradefrom <= $total_subject_mark) && ($grade->gradeupto >= $total_subject_mark)) {
									$row[] = $grade->point;
									$row[] = $grade->grade;
								}
							}
						}
					} else {
						$row[] = '';
						$row[] = '';
					}

					$rows[] = $row;
					$row = [];
				}
			}

			$return = [
				[
					'title' =>  'Result',
					'data'  =>  $rows
				]
			];

			$this->apiDownloadExcel($return, $filename = 'excel', count($markpercentages), $subject_count);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}


	public function download_excel_mark()
	{
		$examID = htmlentities(escapeString($this->uri->segment(3)));
		$classesID  = htmlentities(escapeString($this->uri->segment(4)));
		$sectionID  = htmlentities(escapeString($this->uri->segment(5)));
		$studentID  = htmlentities(escapeString($this->uri->segment(6)));
		$date  = htmlentities(escapeString($this->uri->segment(7)));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if ((int)$examID && (int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
			$this->data['examID']     = $examID;
			$this->data['classesID']  = $classesID;
			$this->data['sectionID']  = $sectionID;
			$this->data['studentIDD'] = $studentID;
			$this->data['date'] = urldecode($date);

			$queryArray        = [];
			$studentQueryArray = [];
			$queryArray['schoolyearID']          = $schoolyearID;
			$studentQueryArray['srschoolyearID'] = $schoolyearID;

			if ((int)$examID > 0) {
				$queryArray['examID'] = $examID;
			}
			if ((int)$classesID > 0) {
				$queryArray['classesID'] = $classesID;
				$studentQueryArray['srclassesID'] = $classesID;
			}
			if ((int)$sectionID > 0) {
				$queryArray['sectionID'] = $sectionID;
				$studentQueryArray['srsectionID'] = $sectionID;
			}
			if ((int)$studentID > 0) {
				$studentQueryArray['srstudentID'] = $studentID;
			}

			$exam      = $this->exam_m->get_single_exam(['examID' => $examID]);
			$exam_name     = $exam->exam;
			$grades       = $this->grade_m->get_grade();
			$classes      = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
			$sections     = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
			$this->data['class_teacher'] = pluck($this->section_m->get_join_sections(), 'name', 'sectionID');
			$groups       = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
			$studentLists = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);
			$classsections = $this->section_m->get_join_section($classesID);

			$students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
			$marks                  = $this->mark_m->student_all_mark_array($queryArray);
			$mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
			$all_except_deportment  = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID));

			$this->subject_m->order('type DESC');
			$subjects = $this->subject_m->get_by_class_id($classesID);
			$subject_marks = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID]), 'fullmark', 'subject_id');

			$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
			$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
			$markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
			$this->data['markpercentagesArr']  = $markpercentagesArr;
			$this->data['settingmarktypeID']   = $settingmarktypeID;

			$retMark = [];
			if (customCompute($marks)) {
				foreach ($marks as $mark) {
					$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark ? $mark->mark : 0;
				}
			}

			$studentPosition             = [];
			$studentChecker              = [];
			$studentClassPositionArray   = [];
			$studentSubjectPositionArray = [];
			$markpercentagesCount        = 0;
			if (customCompute($students)) {
				foreach ($students as $student) {

					$opuniquepercentageArr = [];
					if ($student->sroptionalsubjectID > 0) {
						$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
					}

					$anotheropuniquepercentageArr = [];
					if ($student->sranotheroptionalsubjectID > 0) {
						$anotheropuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
					}

					$studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;
					if (customCompute($mandatorySubjects)) {
						foreach ($mandatorySubjects as $mandatorySubject) {
							$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

							$markpercentages = $uniquepercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'];
							$markpercentagesCount = customCompute($markpercentages);
							if (customCompute($markpercentages)) {
								foreach ($markpercentages as $markpercentageID) {
									$f = false;
									if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
										$f = true;
									}

									if (isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
										if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
											$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
										} else {
											$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
										}
									} else {
										if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
											$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
										} else {
											$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
										}
									}

									if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
										$studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

										if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
											$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
										} else {
											$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
										}
									}

									$f = false;
									if (customCompute($opuniquepercentageArr)) {
										if (isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
											$f = true;
										}
									}
									if (customCompute($anotheropuniquepercentageArr)) {
										if (isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
											$f = true;
										}
									}

									if (!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {

										if ($student->sroptionalsubjectID != 0) {
											if (isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
												if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
												}
											} else {
												if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
												}
											}

											if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
												$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

												if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
												} else {
													if ($f) {
														$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
													}
												}
											}
										}

										if ($student->sranotheroptionalsubjectID != 0) {


											if (isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
												if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
												}
											} else {
												if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
												}
											}

											if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
												$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

												if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
												} else {
													if ($f) {
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

							if (!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
								if ($student->sroptionalsubjectID != 0) {
									$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
								}

								if ($student->sranotheroptionalsubjectID != 0) {
									$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
								}
								$studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
							}

							$studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
							if (!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
								if ($student->sroptionalsubjectID != 0) {
									$studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
								}
							}
							if (!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
								if ($student->sranotheroptionalsubjectID != 0) {
									$studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
								}
							}
						}
					}


					$studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
					$studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

					if (isset($studentPosition['totalStudentMarkAverage'])) {
						$studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
					} else {
						$studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
					}
				}
			}

			arsort($studentClassPositionArray);
			$studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
			if (customCompute($studentSubjectPositionArray)) {
				foreach ($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
					arsort($studentSubjectPositionMark);
					$studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
				}
			}
			if ((int)$studentID > 0) {
				$queryArray['studentID'] = $studentID;
			}


			


			$col             = 5 + $markpercentagesCount;
			$attendance      = $this->get_student_attendance($queryArray, $subjects, $studentLists);
			$studentPosition = $studentPosition;
			$percentageArr   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
			$class = $this->classes_m->get_single_classes(['classesID' => $classesID]);


			$rows[] = ['', 'class', isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : '', ''];
			$rows[] = ['S No.', 'Student Name ', 'Roll Number', 'Section'];


			$count_markpercentages1 = count($markpercentages);
                                                         
            if(customCompute($markpercentages)) {
                foreach($markpercentages as $markpercentageID) {
                        $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                        if($markpercentage == 'Co-scholastic') {
                            $count_markpercentages1 -= 1;
                        }
				}
            }


			$total_subject = count($all_except_deportment);
			if (customCompute($all_except_deportment)) {
				foreach ($all_except_deportment as $index => $subject) {
					if (isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID])) {
                                        array_push($rows[0], $subject->subject);

					// array_push($rows[0], '');
					// array_push($rows[0], '');

					foreach ($markpercentages as $markpercentageID) {
						$markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
						if($markpercentage != 'Co-scholastic') {
							array_push($rows[0], ' ');
						}
						
					//	if($markpercentage == 'Co-scholastic') {
					//		$markpercentagesCount -= 1;
					//	}
					}
				
					array_push($rows[0], '');
					array_push($rows[0], '');

					// if($class->classes_numeric == 1 || $class->classes_numeric == 2 || $class->classes_numeric == 3) {
					// 	array_push($rows[1], 'Continuous Assessment System');
					// 	array_push($rows[1], 'Periodic Assessment');

					// } else {
					// array_push($rows[1], 'Theory');
					// array_push($rows[1], 'Practical');
					// }

					foreach ($markpercentages as $markpercentageID) {
						$markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
						if($markpercentage != 'Co-scholastic') {
							array_push($rows[1], $markpercentage);
						}
					}

					array_push($rows[1], 'Total');
					array_push($rows[1], 'SGrade');
					array_push($rows[1], 'SGPA');
				}}
			}
			array_push($rows[0], 'Total SGPA');
			array_push($rows[0], 'Total SGrade');
			array_push($rows[1], '-');
			array_push($rows[1], '-');


			if (customCompute($studentLists)) {
				foreach ($studentLists as $index => $student) {
					$row[] = $index + 1;
					$row[] = $student->name;
					$row[] = $student->roll;

					foreach ($sections as $index => $section) {
						if ($index == $student->sectionID) {
							$row[] = $section;
						}
					}


					$total_subject_mark = 0;
					$total_grade_point = 0;
					$subject_count = 0;
					$total_credit_hours = 0;
					$credit_hours_x_grade_point = 0;
					foreach ($all_except_deportment as $index => $subject) {

						$percentageMark  = 0;
						$f = true;
						$mark_exist = false;
						$fullmark = $subject->finalmark;
						if (isset($subject_marks[$subject->subjectID])) {
							$fullmark = $subject_marks[$subject->subjectID];
						}

						if (isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID])) {

							$subject_count = $subject_count + 1;
							$fullmark = $subject->finalmark;
							if (isset($subject_marks[$subject->subjectID]) && $subject_marks[$subject->subjectID] != '') {
								$fullmark = $subject_marks[$subject->subjectID];
							}

							$uniquepercentageArr =  isset($markpercentagesArr[$subject->subjectID]) ? $markpercentagesArr[$subject->subjectID] : [];

							if ($subject->credit != '') {
								$total_credit_hours += $subject->credit;
							}

							// if($class->classes_numeric == 1 || $class->classes_numeric == 2 || $class->classes_numeric == 3) {

							// 	foreach($markpercentages as $markpercentageID) {
							// 		$f = false;
							// 		if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
							// 			$f = true;
							// 		} 
							// 		if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {

							// 			$markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
							// 			if($markpercentage == 'Continuous Assessment System' || $markpercentage == 'continuous assessment system') {
							// 				if($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != 0) {
							// 					$mark_exist = true;
							// 					$row[] = $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID];
							// 				} else {
							// 					$mark_exist = false;
							// 				}
							// 			}
							// 		}
							// 	} 
							// 	if($mark_exist == false) {
							// 		$row[] = '-';
							// 	}
							// 	$mark_exist = false;
							// 	foreach($markpercentages as $markpercentageID) {
							// 		$f = false;
							// 		if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
							// 			$f = true;
							// 		} 
							// 		if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {

							// 			$markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
							// 			if($markpercentage == 'Periodic Assessment' || $markpercentage == 'periodic assessment') {
							// 				if($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != 0) {
							// 					$mark_exist = true;
							// 					$row[] = $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID];
							// 				} else {
							// 					$mark_exist = false;
							// 				}
							// 			}
							// 		}
							// 	}
							// 	if($mark_exist == false) {
							// 		$row[] = '-';
							// 	}

							// } else {

							

							foreach ($markpercentages as $markpercentageID) {
								$f = false;
								if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
									$f = true;
								}
								if (isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {

									$markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
									if ($markpercentage == 'th' || $markpercentage == 'Th' || $markpercentage == 'Theory' || $markpercentage == 'theory') {
										if ($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != 0) {
											$mark_exist = true;
											$row[] = $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID];
										} else {
											$mark_exist = false;
										//	$row[] = 'th-';

										}
									}
								}
//else{
                                   //    if($f) {
  //$row[] = 'th'; }
//}
							}


							if ($mark_exist == false) {
								$row[] = '-';
					}
							

						     $mark_exist = false;
							foreach ($markpercentages as $markpercentageID) {
								$f = false;
								if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
									$f = true;
								}
								if (isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {

									$markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
									if ($markpercentage != 'th' && $markpercentage != 'Th' && $markpercentage != 'theory' && $markpercentage != 'Theory' && $markpercentage != 'Co-scholastic') {
										if ($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID] != 0) {
											$mark_exist = true;
											$row[] = $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID];
										} else {
											$mark_exist = false;
											// if ($mark_exist == false) {
												$row[] = '-';
											// }
										}
									}
							}
//else{
								//	if($f) {
									//	$row[] = 'pp-';
								//	}
							//	}
//else{
							//		$row[] = '-';
							//	}
							}

							$row[] = isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID]) ?$studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';

							$subjectMark = isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';
							$subjectMark = markCalculationView($subjectMark, $fullmark, $percentageMark);

							$total_subject_mark += $subjectMark;
							$mark_exist = false;
							if (customCompute($grades)) {
								foreach ($grades as $grade) {
									if (($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark)) {
										$mark_exist = true;
										$total_grade_point += $grade->point;
										if ($subject->credit != '') {
											$credit_hours_x_grade_point += ($subject->credit * $grade->point);
										} else {
											$credit_hours_x_grade_point += 0;
										}
										$row[] = $grade->grade;
										$row[] = $grade->point;
									}
								}
							}
							if (!$mark_exist) {
								$row[] = '-';
								$row[] = '-';
							}
						} 
						// else {
							//$row[] = '';
						//	$row[] = '';
							//$row[] = '';
							//$row[] = '';
							//$row[] = '';
                            // $t =  $count_markpercentages1;
                            // for($i = 0;$i< $t;$i++){
							// 	$row[] = '-';
							// }
							// $row[] = '-';
							// $row[] = '-';
							// $row[] = '-';
						// }
					}

					$total_subject_mark = round($total_subject_mark / $subject_count);
					if (isset($studentPosition[$student->srstudentID]['classPositionMark']) && $studentPosition[$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition['totalStudentMarkAverage']) && $total_subject_mark > 1) {
						if ($class->classes_numeric != 12) {
							$point = number_format($total_grade_point / $subject_count, 2);
						} else {
							$point = number_format($credit_hours_x_grade_point / $total_credit_hours, 2);
						}
						$row[] = $point;
						$row[] = getGradeFromPoint($point);
					} else {
						$row[] = '';
						$row[] = '';
					}

					$rows[] = $row;
					$row = [];
				}
			}

		// dd($rows);
			// echo $markpercentagesCount;
			// exit;

			$sectionsArray = [];
			foreach ($classsections as $classsection) {
				$sectionsArray[] = [
					'section' => $classsection->section,
					'teacher_name' => $classsection->name
				];
			}

			$setting = $this->setting_m->get_setting();

			$headerArray = [
				'school' => $this->data['siteinfos']->sname,
				'exam' => $exam->exam
			];

			$footerArray = [
				'issue_date' => $exam->issue_date,
				'coordinator' => getVerifiedBy1($class->classes_numeric),
				'vice_principal' => $setting->principal_name ? $setting->vice_principal_name : '',
				'principal' => $setting->principal_name ? $setting->principal_name : ''

			];

			$return = [
				[
					'title' =>  'Result',
					'data'  =>  $rows,
					'sections' =>  $sectionsArray,
					'headers' => $headerArray,
					'footers' => $footerArray
				]
			];

			$section_name = '';
			foreach ($sections as $index => $section) {
				if ($index == $sectionID) {
					$section_name = '_' . $section;
				}
			}

			$filename = 'student_ledger_' . $class->classes . $section_name;
			$this->apiDownloadExcel1($return, $filename, $count_markpercentages1 + 2, $total_subject, count($studentLists));
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}


	private function  apiDownloadExcel1($data = [['title' => '', 'data' => [],]], $filename = 'excel', $mark_percentages_count, $total_subject, $total_student)
	{
		$spreadsheet = new Spreadsheet();

		$spreadsheet->getProperties()->setCreator('PhpOffice')
			->setLastModifiedBy('PhpOffice')
			->setTitle('Excel File')
			->setSubject('Office 2007 XLSX Test Document')
			->setDescription('PhpOffice')
			->setKeywords('PhpOffice')
			->setCategory('PhpOffice');

		foreach ($data as $index => $d) {

			if ($index) {
				$spreadsheet->createSheet();
			}

			$spreadsheet->setActiveSheetIndex($index)->fromArray($d['data'], '', 'A4');
			$sheet = $spreadsheet->getActiveSheet();

			$sheet->setCellValue('A1', $d['headers']['school']);
			$sheet->setCellValue('A2', $d['headers']['exam']);
			$style = array(
				'alignment' => array(
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
				),
				'font' => [
					'size' => 16
				]
			);
			$sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setBold(true);
			$sheet->getStyle('A2:' . $sheet->getHighestColumn() . '2')->getFont()->setBold(true);

			$sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray($style);
			$sheet->mergeCells('A1:' . $sheet->getHighestColumn() . '1');
			$sheet->getStyle('A2:' . $sheet->getHighestColumn() . '2')->applyFromArray($style);
			$sheet->mergeCells('A2:' . $sheet->getHighestColumn() . '2');
			$sheet->getRowDimension('1')->setRowHeight(20);
			$sheet->getRowDimension('2')->setRowHeight(20);

			$sheet->setTitle($d['title']);
			$sheet->getColumnDimension('B')->setAutoSize(true);
			$sheet->getStyle('A4:' . $sheet->getHighestColumn() . '4')->getFont()->setBold(true);
			$sheet->getStyle('A5:' . $sheet->getHighestColumn() . '5')->getFont()->setBold(true);

			if ($d['title'] == 'Result') {
				$sheet->getRowDimension('5')->setRowHeight(80);
				$start = 5;
				$end = $start + $mark_percentages_count;

				for ($i = 0; $i < $total_subject; $i++) {
					$start_column = $this->getNameFromNumber($start);
					$start_column_2 = $this->getNameFromNumber($start + 1);
					$end_column = $this->getNameFromNumber($end);
					$column_range = $start_column . '3:' . $end_column . '3';
					$column_range_2 = $start_column . '4:' . $end_column . '4';
					$column_range_3 = $start_column . '5:' . $end_column . '5';
					$sheet->mergeCells($column_range);
					$sheet->mergeCells($column_range_2);
					$student_rows  = $total_student + 5;

					$styleArray = array(
						'borders' => array(
							'outline' => array(
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
								'color' => array('argb' => '000000'),
							),
						),
					);

					$sheet->getStyle($start_column . '4:' . $end_column . $student_rows)->applyFromArray($styleArray);
					$sheet->getStyle('A4:' . $sheet->getHighestColumn() . $student_rows)->applyFromArray($styleArray);

					$sheet->getStyle($column_range)
						->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					$sheet->getStyle($column_range_2)
						->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

					$sheet->getStyle($column_range_3)->getAlignment()->setTextRotation(90);

					for ($j = $start; $j < $end; $j++) {
						$s = $this->getNameFromNumber($j + 1);
						$sheet->getColumnDimension($s)->setWidth(4);
					}
					$sheet->getColumnDimension($start_column)->setAutoSize(true);


					$start = $end + 1;
					$end = $end + $mark_percentages_count + 1;
				}

				$footerLineStart = $sheet->getHighestRow() + 3;
				$this->getFooter($sheet, $footerLineStart, $d['sections'], $d['footers']);
			} else {
				$start = 5;
				$end = $start + 2;

				for ($i = 0; $i < $total_subject; $i++) {
					$start_column = $this->getNameFromNumber($start);
					$start_column_2 = $this->getNameFromNumber($start + 1);
					$end_column = $this->getNameFromNumber($end);
					$column_range = $start_column . '0:' . $end_column . '0';
					$column_range_2 = $start_column . '1:' . $end_column . '1';
					$column_range_3 = $start_column . '2:' . $end_column . '2';
					$sheet->mergeCells($column_range);
					$sheet->mergeCells($column_range_2);
					$student_rows  = $total_student + 5;

					$styleArray = array(
						'borders' => array(
							'outline' => array(
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
								'color' => array('argb' => '000000'),
							),
						),
					);
					$sheet->getStyle($start_column . '4:' . $end_column . $student_rows)->applyFromArray($styleArray);
					$sheet->getStyle($start_column . ($student_rows + 1) . ':' . $end_column . ($student_rows + 9))->applyFromArray($styleArray);
					$sheet->getStyle('A4:' . $sheet->getHighestColumn() . $student_rows)->applyFromArray($styleArray);
					$sheet->getStyle('A' . ($student_rows + 1) . ':' . $sheet->getHighestColumn() . ($student_rows + 9))->applyFromArray($styleArray);


					$sheet->getStyle($column_range)
						->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					$sheet->getStyle($column_range_2)
						->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

					for ($j = $start; $j < $end; $j++) {
						$s = $this->getNameFromNumber($j + 1);
						$sheet->getColumnDimension($s)->setWidth(4);
					}
					$sheet->getColumnDimension($start_column)->setAutoSize(true);


					$start = $end + 1;
					$end = $end + 3;
				}

				$footerLineStart = $sheet->getHighestRow() + 3;
				$this->getFooter($sheet, $footerLineStart, $d['sections'], $d['footers']);
			}
		}

		$spreadsheet->setActiveSheetIndex(0);
		$writer = new Xlsx($spreadsheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}



	private function  apiDownloadExcel($data = [['title' => '', 'data' => [],]], $filename = 'excel', $mark_percentages_count, $subject_count)
	{
		$spreadsheet = new Spreadsheet();

		$spreadsheet->getProperties()->setCreator('PhpOffice')
			->setLastModifiedBy('PhpOffice')
			->setTitle('Excel File')
			->setSubject('Office 2007 XLSX Test Document')
			->setDescription('PhpOffice')
			->setKeywords('PhpOffice')
			->setCategory('PhpOffice');

		foreach ($data as $index => $d) {
			if ($index) {
				$spreadsheet->createSheet();
			}

			$spreadsheet->setActiveSheetIndex($index)->fromArray($d['data']);
			$sheet = $spreadsheet->getActiveSheet();

			$start = 3;
			$end = $start + $mark_percentages_count + 1;

			for ($i = 0; $i < $subject_count; $i++) {
				$start_column = $this->getNameFromNumber($start);
				$start_column_2 = $this->getNameFromNumber($start + 1);
				$end_column = $this->getNameFromNumber($end);
				$column_range = $start_column . '1:' . $end_column . '1';
				$column_range_2 = $start_column_2 . '2:' . $end_column . '2';
				$column_range_3 = $start_column_2 . '3:' . $end_column . '3';
				$sheet->mergeCells($column_range);
				$sheet->mergeCells($column_range_2);

				$sheet->getStyle($column_range)
					->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				$sheet->getStyle($column_range_2)
					->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$sheet->getStyle($column_range_3)->getAlignment()->setTextRotation(90);

				for ($j = $start; $j < $end; $j++) {
					$s = $this->getNameFromNumber($j + 1);
					$sheet->getColumnDimension($s)->setWidth(4);
				}
				$sheet->getColumnDimension($start_column)->setAutoSize(true);


				$start = $end + 1;
				$end = $end + $mark_percentages_count + 2;
			}
			$sheet->setTitle($d['title']);
			$sheet->getColumnDimension('B')->setAutoSize(true);
			$sheet->getRowDimension('3')->setRowHeight(100);
			$sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setBold(true);
			$sheet->getStyle('A2:' . $sheet->getHighestColumn() . '2')->getFont()->setBold(true);
			$sheet->getStyle('A3:B3')->getFont()->setBold(true);
		}

		$spreadsheet->setActiveSheetIndex(0);
		$writer = new Xlsx($spreadsheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	function getNameFromNumber($num)
	{
		$numeric = ($num - 1) % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval(($num - 1) / 26);
		if ($num2 > 0) {
			return $this->getNameFromNumber($num2) . $letter;
		} else {
			return $letter;
		}
	}

	public function send_pdf_to_mail()
	{
		$retArray['status'] = FALSE;
		$retArray['message'] = '';

		if (permissionChecker('terminalreport')) {
			if ($_POST) {
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

					if ((int)$examID > 0) {
						$queryArray['examID'] = $examID;
					}
					if ((int)$classesID > 0) {
						$queryArray['classesID'] = $classesID;
						$studentQueryArray['srclassesID'] = $classesID;
					}
					if ((int)$sectionID > 0) {
						$queryArray['sectionID'] = $sectionID;
						$studentQueryArray['srsectionID'] = $sectionID;
					}
					if ((int)$studentID > 0) {
						$studentQueryArray['srstudentID'] = $studentID;
					}

					$exam      = $this->exam_m->get_single_exam(['examID' => $examID]);
					$this->data['examName']     = $exam->exam;
					$this->data['grades']       = $this->grade_m->get_grade();
					$this->data['classes']      = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['sections']     = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
					$this->data['class_teacher'] = pluck($this->section_m->get_join_sections(), 'name', 'sectionID');
					$this->data['groups']       = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
					// $this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student($studentQueryArray);
					$this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);


					$students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
					$marks                  = $this->mark_m->student_all_mark_array($queryArray);
					$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 1));

					$this->subject_m->order('type DESC');
					$this->data['subjects'] = $this->subject_m->get_by_class_id($classesID);
					$this->data['subject_marks'] = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID, 'class_id' => $classesID]), 'fullmark', 'subject_id');


					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
					$markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
					$this->data['markpercentagesArr']  = $markpercentagesArr;
					$this->data['settingmarktypeID']   = $settingmarktypeID;

					$retMark = [];
					if (customCompute($marks)) {
						foreach ($marks as $mark) {
							$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark ? $mark->mark : 0;
						}
					}

					$studentPosition             = [];
					$studentChecker              = [];
					$studentClassPositionArray   = [];
					$studentSubjectPositionArray = [];
					$markpercentagesCount        = 0;
					if (customCompute($students)) {
						foreach ($students as $student) {
							$opuniquepercentageArr = [];
							if ($student->sroptionalsubjectID > 0) {
								$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
							}

							$anotheropuniquepercentageArr = [];
							if ($student->sranotheroptionalsubjectID > 0) {
								$opuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
							}

							$studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;
							if (customCompute($mandatorySubjects)) {
								foreach ($mandatorySubjects as $mandatorySubject) {
									$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

									$markpercentages = $uniquepercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'];
									$markpercentagesCount = customCompute($markpercentages);
									if (customCompute($markpercentages)) {
										foreach ($markpercentages as $markpercentageID) {
											$f = false;
											if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
												$f = true;
											}

											if (isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
												if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
												}
											} else {
												if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
												}
											}

											if (isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

												if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
												}
											}

											$f = false;
											if (customCompute($opuniquepercentageArr)) {
												if (isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
													$f = true;
												}
											}
											if (customCompute($anotheropuniquepercentageArr)) {
												if (isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
													$f = true;
												}
											}

											if (!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
												if ($student->sroptionalsubjectID != 0) {
													if (isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
														if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
														}
													} else {
														if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
														}
													}

													if (isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

														if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															if ($f) {
																$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
															}
														}
													}
												}
												if ($student->sranotheroptionalsubjectID != 0) {
													if (isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
														if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
														}
													} else {
														if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
														}
													}

													if (isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

														if (isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															if ($f) {
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

									if (!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
										if ($student->sroptionalsubjectID != 0) {
											$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
										}
										if ($student->sranotheroptionalsubjectID != 0) {
											$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
										}
										$studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
									}

									$studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
									if (!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
										if ($student->sroptionalsubjectID != 0) {
											$studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
										}
									}
									if (!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
										if ($student->sranotheroptionalsubjectID != 0) {
											$studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
										}
									}
								}
							}


							$studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
							$studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

							if (isset($studentPosition['totalStudentMarkAverage'])) {
								$studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
							} else {
								$studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
							}
						}
					}

					arsort($studentClassPositionArray);
					$studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
					if (customCompute($studentSubjectPositionArray)) {
						foreach ($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
							arsort($studentSubjectPositionMark);
							$studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
						}
					}
					if ((int)$studentID > 0) {
						$queryArray['studentID'] = $studentID;
					}

					$this->data['col']             = 5 + $markpercentagesCount;
					$this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
					$this->data['studentPosition'] = $studentPosition;
					$this->data['percentageArr']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

					$this->reportSendToMail('terminalreport.css', $this->data, 'report/terminal1/TerminalReportPDF', $to, $subject, $message);
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

	public function getExam()
	{
		$classesID = $this->input->post('classesID');
		echo "<option value='0'>", $this->lang->line("terminalreport_please_select"), "</option>";
		if ((int)$classesID) {
			$exams    = pluck($this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID), 'obj', 'examID');
			if (customCompute($exams)) {
				foreach ($exams as $exam) {
					echo "<option value=" . $exam->examID . ">" . $exam->exam . "</option>";
				}
			}
		}
	}

	public function getSection()
	{
		$classesID = $this->input->post('classesID');
		if ((int)$classesID) {
			$sections = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("terminalreport_please_select"), "</option>";
			if (customCompute($sections)) {
				foreach ($sections as $section) {
					echo "<option value=\"$section->sectionID\">" . $section->section . "</option>";
				}
			}
		}
	}

	public function getStudent()
	{
		$classesID = $this->input->post('classesID');
		$sectionID = $this->input->post('sectionID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if ((int)$classesID && (int)$sectionID) {
			$students = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID));
			if (customCompute($students)) {
				echo "<option value='0'>" . $this->lang->line("terminalreport_please_select") . "</option>";
				foreach ($students as $student) {
					echo "<option value=\"$student->srstudentID\">" . $student->srname . "</option>";
				}
			}
		}
	}

	public function unique_data($data)
	{
		if ($data != "") {
			if ($data === "0") {
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
		if (!empty($_FILES) && isset($_FILES["class_teacher"]) && $_FILES["class_teacher"]['name'] != "") {
			$file_name        = $_FILES["class_teacher"]['name'];
			$random           = random19();
			$makeRandom       = hash('sha512', $random . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
			$explode          = explode('.', $file_name);
			if (customCompute($explode) >= 2) {
				$new_file                = $file_name_rename . '.' . end($explode);
				$config['upload_path']   = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png";
				$config['file_name']     = $new_file;
				$_FILES['attach']['tmp_name'] = $_FILES['class_teacher']['tmp_name'];
                $image_info = getimagesize($_FILES['class_teacher']['tmp_name']);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
				// $config['max_size']      = '5120';
				// $config['max_width']     = '3000';
				// $config['max_height']    = '3000';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload("class_teacher")) {
					$this->form_validation->set_message("photoupload", $this->upload->display_errors());
					return false;
				} else {
					$fileData = $this->upload->data();
                    if($image_width > 1800 || $image_height > 1800){
                        resizeImage($fileData['file_name'],$config['upload_path']);
                     }
					$this->upload_data['class_teacher'] = $this->upload->data();
					return true;
				}
			} else {
				$this->form_validation->set_message("photoupload", "Invalid file");
				return false;
			}
		} else {
			$this->upload_data['class_teacher'] = ['file_name' => $new_file];
			return true;
		}
	}

	public function incharge_upload()
	{
		$setting  = $this->setting_m->get_setting();
		$new_file = "site.png";
		if (!empty($_FILES) && isset($_FILES["incharge"]) && $_FILES["incharge"]['name'] != "") {
			$file_name        = $_FILES["incharge"]['name'];
			$random           = random19();
			$makeRandom       = hash('sha512', $random . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
			$explode          = explode('.', $file_name);
			if (customCompute($explode) >= 2) {
				$new_file                = $file_name_rename . '.' . end($explode);
				$config['upload_path']   = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png";
				$config['file_name']     = $new_file;
				$_FILES['attach']['tmp_name'] = $_FILES['incharge']['tmp_name'];
                $image_info = getimagesize($_FILES['incharge']['tmp_name']);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
				// $config['max_size']      = '5120';
				// $config['max_width']     = '3000';
				// $config['max_height']    = '3000';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload("incharge")) {
					$this->form_validation->set_message("photoupload", $this->upload->display_errors());
					return false;
				} else {
					$fileData = $this->upload->data();
                    if($image_width > 1800 || $image_height > 1800){
                        resizeImage($fileData['file_name'],$config['upload_path']);
                     }
					$this->upload_data['incharge'] = $this->upload->data();
					return true;
				}
			} else {
				$this->form_validation->set_message("photoupload", "Invalid file");
				return false;
			}
		} else {
			$this->upload_data['incharge'] = ['file_name' => $new_file];
			return true;
		}
	}


	public function getFooter($sheet, $start, $sections, $footers)
	{

		$start1 = $start + 1;
		$start2 = $start + 2;
		$startColnum = 2;
		$startCol =  $this->getNameFromNumber($startColnum);

		//issue date
		$sheet->getStyle($startCol . $start1)->applyFromArray([
			'borders' => [
				'bottom' => [
					'borderStyle' => Border::BORDER_THIN,
				]
			],
		]);
		$sheet->setCellValue($startCol . $start, "");
		$sheet->setCellValue($startCol . $start1, $footers['issue_date']);
		$sheet->setCellValue($startCol . $start2, "Issue Date");

		//class teacher
		if ($sections) {
			$a = $startColnum + 3;
			foreach ($sections as $section) {
				$b = $this->getNameFromNumber($a);
				$sheet->getStyle($b . $start)->applyFromArray([
					'borders' => [
						'bottom' => [
							'borderStyle' => Border::BORDER_THIN,
						]
					],
				]);
				$sheet->setCellValue($b . $start, "");
				$sheet->setCellValue($b . $start1, 'Class Teacher ' . "'" . $section['section'] . "'");
				$sheet->setCellValue($b . $start2, $section['teacher_name']);
				$a = $a + 8;
			}
		} else {
			$a = $startColnum + 3;
		}

		//coordinator
		$c = $a + 3;
		$d = $this->getNameFromNumber($c);
		$sheet->getStyle($d . $start)->applyFromArray([
			'borders' => [
				'bottom' => [
					'borderStyle' => Border::BORDER_THIN,
				]
			],
		]);
		$sheet->setCellValue($d . $start, "");
		$sheet->setCellValue($d . $start1, "Co-ordinator");
		$sheet->setCellValue($d . $start2, $footers['coordinator']);

		//vice principal
		$e = $c + 10;
		$f = $this->getNameFromNumber($e);
		$sheet->getStyle($f . $start)->applyFromArray([
			'borders' => [
				'bottom' => [
					'borderStyle' => Border::BORDER_THIN,
				]
			],
		]);
		$sheet->setCellValue($f . $start, "");
		$sheet->setCellValue($f . $start1, "Vice Principal");
		$sheet->setCellValue($f . $start2, $footers['vice_principal']);

		//priciple
		$g = $e + 10;
		$h = $this->getNameFromNumber($g);
		$sheet->getStyle($h . $start)->applyFromArray([
			'borders' => [
				'bottom' => [
					'borderStyle' => Border::BORDER_THIN,
				]
			],
		]);
		$sheet->setCellValue($h . $start, "");
		$sheet->setCellValue($h . $start1, "Principal");
		$sheet->setCellValue($h . $start2, $footers['principal']);
	}

	public function convertDateToNepaliInEnglish($date)
	{
		if ($date) {
			$dateObj = new NepaliCalenderHelper();
			$nepaliDate = $dateObj->convertDateToNepaliInEnglish($date);
			$date = strlen($nepaliDate['date']) == 1 ? '0' . $nepaliDate['date'] : $nepaliDate['date'];
			$month = strlen($nepaliDate['month']) == 1 ? '0' . $nepaliDate['month'] : $nepaliDate['month'];
			return $nepaliDate['year'] . '-' . $month . '-' . $date;
		} else {
			return '';
		}
	}

	public function convertDateToEnglishInNepali($date)
	{
		if ($date) {
			$date = explode('-', $date);
			$yy = $date[0];
			$mm = $date[1];
			$dd = $date[2];
			$dateObj = new NepaliCalenderHelper();
			$engDate = $dateObj->nep_to_eng($yy, $mm, $dd);
			$date = strlen($engDate['date']) == 1 ? '0' . $engDate['date'] : $engDate['date'];
			$month = strlen($engDate['month']) == 1 ? '0' . $engDate['month'] : $engDate['month'];
			return $engDate['year'] . '-' . $month . '-' . $date;
		} else {
			return '';
		}
	}
}
