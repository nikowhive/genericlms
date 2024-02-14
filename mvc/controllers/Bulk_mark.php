<?php

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\DataValidation;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bulk_mark extends Admin_Controller
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
		$this->load->model("mark_m");
		$this->load->model("grade_m");
		$this->load->model("classes_m");
		$this->load->model("exam_m");
		$this->load->model("subject_m");
		$this->load->model("section_m");
		$this->load->model("student_m");
		$this->load->model("markrelation_m");
		$this->load->model("markpercentage_m");
		$this->load->model('studentrelation_m');
		$this->load->model('marksetting_m');
		$this->db->cache_off();

		$language = $this->session->userdata('lang');
		$this->lang->load('bulk_mark', $language);
	}

	protected function rules()
	{
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("mark_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_examID'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("mark_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classesID'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("mark_section"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_sectionID'
			),
		);
		return $rules;
	}

	protected function markRules()
	{
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("mark_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_examID'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("mark_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classesID'
			),
			array(
				'field' => 'inputs',
				'label' => $this->lang->line("mark_subject"),
				'rules' => 'trim|xss_clean|max_length[11]|callback_unique_inputs'
			)
		);
		return $rules;
	}

	public function send_mail_rules()
	{
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("mark_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("mark_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("mark_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'id',
				'label' => $this->lang->line("mark_studentID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'set',
				'label' => $this->lang->line("mark_classesID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			)
		);
		return $rules;
	}

	public function index()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
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
			$this->data['settingmarktypeID']  = $this->data['siteinfos']->marktypeID;
			$graduateclass                    = $this->data['siteinfos']->ex_class;

			$this->data['set_exam']    = 0;
			$this->data['set_classes'] = 0;
			$this->data['set_section'] = 0;

			$this->data['sendExam']    = [];
			$this->data['sendClasses'] = [];
			$this->data['sendSection'] = [];
			$this->data['exams']       = [];

			$this->data['grades'] = $this->grade_m->get_order_by_grade();

			$classesID = $this->input->post("classesID");
			if ((int)$classesID) {
				$this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID);
				$this->data['subjects'] = $subjects = $this->subject_m->get_order_by_subject(array('classesID' => $classesID));
				$this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $classesID));
			} else {
				$this->data['subjects'] = [];
				$this->data['sections'] = [];
			}

			$this->data['classes']  = $this->classes_m->get_order_by_classes(['classesID !=' => $graduateclass]);

			if ($_POST) {
				$rules = $this->rules();
				if (isset($_POST['bulk_import']) && $_FILES["excel"]['tmp_name'] == "") {
					$image_rule = array(
						'field' => 'excel',
						'label' => 'excel sheet',
						'rules' => 'trim|required|xss_clean',
						'errors' => array(
							'required' => 'You must provide a file.',
						),
					);
					array_push($rules, $image_rule);
				}
				$this->form_validation->set_rules($rules);

				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "bulk_mark/add";
					$this->load->view('_layout_main', $this->data);
				} else {
					$examID          = $this->input->post('examID');
					$classesID       = $this->input->post('classesID');
					$sectionID       = $this->input->post('sectionID');
					$this->data['set_exam']    = $examID;
					$this->data['set_classes'] = $classesID;
					$this->data['set_section'] = $sectionID;

					$exam            = $this->exam_m->get_single_exam(array('examID' => $examID));
					$classes         = $this->classes_m->get_single_classes(array('classesID' => $classesID));
					$section         = $this->section_m->get_single_section(array('sectionID' => $sectionID));
					$markpercentages = $this->markpercentage_m->get_markpercentage();

					$this->data['sendExam']     = $exam;
					$this->data['sendClasses']  = $classes;
					$this->data['sendSection']  = $section;

					$schoolyearID       = $this->session->userdata('defaultschoolyearID');
					$studentArray = [
						'srclassesID'   => $classesID,
						'srsectionID'   => $sectionID,
						'srschoolyearID' => $schoolyearID,
					];

					if (isset($_POST['download_template'])) {
						$return = [];

						foreach ($subjects as $index => $subject) {
							$students  = [];
							$optionalArray1 = [];
							if (customCompute($subject)) {
								if ($subject->type == 1) {
									$students = $this->studentrelation_m->get_order_by_student([
										"srclassesID"    	=> $classesID,
										'srschoolyearID' 	=> $schoolyearID
									]);
								} else {
									$optionalArray = [
										'subjectID' => $subject->subjectID,
									];

									$students = $this->studentrelation_m->get_order_by_student(array(
										"srclassesID" => $classesID,
										'srschoolyearID' => $schoolyearID,
									), '', $optionalArray);

									$optionalArray1['subjectID'] = $subject->subjectID;
								}
							}


							$markPluck   = pluck($this->mark_m->get_order_by_mark(array("examID" => $examID, "classesID" => $classesID, "subjectID" => $subject->subjectID, 'schoolyearID' => $schoolyearID)), 'obj', 'studentID');

							$array = [];
							if (customCompute($students)) {

								foreach ($students as $student) {
									if (!isset($markPluck[$student->studentID])) {
										$array[] = array(
											"examID"       => $examID,
											"schoolyearID" => $schoolyearID,
											"exam"         => $exam->exam,
											"studentID"    => $student->studentID,
											"classesID"    => $classesID,
											"subjectID"    => $subject->subjectID,
											"subject"      => $subject->subject,
											"year"         => date('Y'),
											"create_date"  => date("Y-m-d H:i:s"),
											'create_userID' => $this->session->userdata("loginuserID"),
											'create_usertypeID' => $this->session->userdata('usertypeID')
										);
									}
								}

								if (customCompute($array)) {
									$count = customCompute($array);

									$firstID = $this->mark_m->insert_batch_mark($array);
									$lastID = $firstID + ($count - 1);

									$markRelationArray = [];
									if ($lastID >= $firstID) {
										for ($i = $firstID; $i <= $lastID; $i++) {
											foreach ($markpercentages as $value) {
												$markRelationArray[] = [
													"markID" => $i,
													"markpercentageID" => $value->markpercentageID
												];
											}
										}
									}

									if (customCompute($markRelationArray)) {
										$this->markrelation_m->insert_batch_markrelation($markRelationArray);
									}
								}
							}

							if (customCompute($students)) {
								$missingmMarkRelationArray = [];
								$allMarkWithRelation = $this->markrelation_m->get_all_mark_with_relation(array('schoolyearID' => $schoolyearID, 'examID' => $examID, 'classesID' => $classesID, 'subjectID' => $subject->subjectID));


								$studentMarkPercentage = [];
								foreach ($allMarkWithRelation as $key => $value) {
									$studentMarkPercentage[$value->studentID][$value->examID][$value->subjectID]['markpercentage'][] = $value->markpercentageID;
									$studentMarkPercentage[$value->studentID][$value->examID]['markID'][$value->subjectID] = $value->markID;
								}

								$markpercentage_ids = pluck($markpercentages, 'markpercentageID');
								foreach ($students as $student) {
									$studentPercentage = isset($studentMarkPercentage[$student->studentID][$examID][$subject->subjectID]['markpercentage']) ? $studentMarkPercentage[$student->studentID][$examID][$subject->subjectID]['markpercentage'] : [];

									if (customCompute($studentPercentage)) {
										$diffMarkPercentage = array_diff($markpercentage_ids, $studentMarkPercentage[$student->studentID][$examID][$subject->subjectID]['markpercentage']);
										foreach ($diffMarkPercentage as $item) {
											$missingmMarkRelationArray[] = [
												"markID" => $studentMarkPercentage[$student->studentID][$examID]['markID'][$subject->subjectID],
												"markpercentageID" => $item
											];
										}
									}
								}

								if (customCompute($missingmMarkRelationArray)) {
									$this->markrelation_m->insert_batch_markrelation($missingmMarkRelationArray);
								}
							}
						}

						$coscholasticSubjects = [];
						$exceptcoscholasticSubjects = [];
						foreach($subjects as $subject){
							if($subject->coscholatics != 1) {
								$exceptcoscholasticSubjects[] = $subject;
							}else{
								$coscholasticSubjects[] = $subject;
							}
						}
                      
						foreach ($exceptcoscholasticSubjects as $index => $subject) {
							$students  = [];
							$optionalArray1 = [];
							if (customCompute($subject)) {
								if ($subject->type == 1) {
									$students = $this->studentrelation_m->get_order_by_student([
										"srclassesID"    	=> $classesID,
										'srschoolyearID' 	=> $schoolyearID
									]);
								} else {
									$optionalArray = [
										'subjectID' => $subject->subjectID,
									];

									$students = $this->studentrelation_m->get_order_by_student(array(
										"srclassesID" => $classesID,
										'srschoolyearID' => $schoolyearID,
									), '', $optionalArray);

									$optionalArray1['subjectID'] = $subject->subjectID;
								}
							}

							$sendStudent = $this->studentrelation_m->get_order_by_student($studentArray, '', $optionalArray1);


							$markpercentageArr['marktypeID'] = $this->data['siteinfos']->marktypeID;
							$markpercentageArr['classesID']  = $classesID;
							$markpercentageArr['examID']     = $examID;
							$markpercentageArr['subjectID']  = $subject->subjectID;
							$markpercentageArr['subject']    = $subject;


							$this->data['markpercentages']  = $this->marksetting_m->get_marksetting_markpercentages_add($markpercentageArr);

							$this->data['markRelations']    = $this->getMarkRelationArray($this->mark_m->student_all_mark_array(array('schoolyearID' => $schoolyearID, 'examID' => $examID, 'classesID' => $classesID, 'subjectID' => $subject->subjectID)));

							$rows = []; 
							$subject_name = ['Subject Name', $subject->subject];
							$rows[] = $subject_name;
							$first_row = [];

							$first_row[] = 'S No.';
							$first_row[] = 'Student Name ';
							$first_row[] = 'Roll';


							foreach ($this->data['markpercentages'] as $markpercentage) {
								$first_row[] = $markpercentage->markpercentagetype;
							}

							$rows[] = $first_row;

							if (customCompute($sendStudent)) {
								foreach ($sendStudent as $index => $student) {
									$row[] = $index + 1;
									$row[] = $student->name;
									$row[] = $student->roll;


									foreach ($this->data['markpercentages'] as $data) {
										// if($subject->coscholatics != 1) {
											$row[] = $this->data['markRelations'][$student->studentID][$data->markpercentageID];
										// } else {
											// $row[] = getGradeFromMark($this->data['markRelations'][$student->studentID][$data->markpercentageID]);
										//}
									}

									$rows[] = $row;
									$row = [];
								}
							}
							$data = [
								'title' =>  spClean(namesorting($subject->subject)),
								'data'  =>  $rows
							];
							array_push($return, $data);
						}

						if(customCompute($coscholasticSubjects)){
						    $coscholaticData = $this->bulk_mark_for_coscholatic_subjects($coscholasticSubjects,$classesID,$schoolyearID,$studentArray,$examID);
                            array_push($return, $coscholaticData);
						}

						$this->apiDownloadExcel($return, $filename = 'excel', $exam, $classes);
					} else if (isset($_POST['bulk_import'])) {

						try {
							$spreadsheet = IOFactory::load($_FILES['excel']['tmp_name']);
						} catch (Exception $e) {
						}

						$sheets = $spreadsheet->getSheetNames();


						foreach ($sheets as $sheetname) {
						if($sheetname != 'Coscholatics'){
							$worksheet = $spreadsheet->getSheetByName($sheetname);
							$no_of_rows = $worksheet->getHighestDataRow();
							$no_of_columns = $worksheet->getHighestDataColumn();
							$no_of_columns = Coordinate::columnIndexFromString($no_of_columns);
							$data = [];

							for ($currentRow = 2; $currentRow <= $no_of_rows; $currentRow++) {
								for ($currentCol = 1; $currentCol <= $no_of_columns; $currentCol++) {
									$data[$currentRow - 2][$currentCol - 1] = $worksheet->getCellByColumnAndRow($currentCol, $currentRow)->getCalculatedValue();

								}
							}

							$subject_name = $worksheet->getCellByColumnAndRow(2, 1)->getCalculatedValue();
							$data = $this->convertRow($data, true);
							$return[$subject_name] = $data;

						}else{
							$worksheet = $spreadsheet->getSheetByName($sheetname);
							$no_of_rows = $worksheet->getHighestDataRow();
							$no_of_columns = $worksheet->getHighestDataColumn();
							$no_of_columns = Coordinate::columnIndexFromString($no_of_columns);
							$data = [];

							$start_column_of_second_row = Coordinate::columnIndexFromString('D');
							$subjectArray = [];
							for($i=$start_column_of_second_row;$i<=$no_of_columns;$i++){
								if($worksheet->getCellByColumnAndRow($i, 1)->getCalculatedValue() != ''){
									$sub = $worksheet->getCellByColumnAndRow($i, 1)->getCalculatedValue();
									$subjectArray[$i] = $sub; 
								}
							}


							foreach($subjectArray as $key=>$subjectArr){
								for ($currentRow = 2; $currentRow <= $no_of_rows; $currentRow++) {
									for ($currentCol = 1; $currentCol <= $no_of_columns; $currentCol++) {
										if($currentCol < 4){
											$data[$subjectArr][$currentRow - 2][$currentCol - 1] = $worksheet->getCellByColumnAndRow($currentCol, $currentRow)->getCalculatedValue();
										}
										if($currentCol == $key){
											$data[$subjectArr][$currentRow - 2][$key - 1] = $worksheet->getCellByColumnAndRow($key, $currentRow)->getCalculatedValue();
										}
									}
								}
								$data = $this->convertRow($data[$subjectArr], true);
								$return[$subjectArr] = $data;
						    }
						}
						}

						foreach ($return as $index => $individual) {
							$markRelationArray = [];

							$subject         = $this->subject_m->get_single_subject(array('classesID' => $classesID, 'subject' => $index));

							$markpercentageArr['marktypeID'] = $this->data['siteinfos']->marktypeID;
							$markpercentageArr['classesID']  = $classesID;
							$markpercentageArr['examID']     = $examID;
							$markpercentageArr['subjectID']  = $subject->subjectID;
							$markpercentageArr['subject']    = $subject;

							$this->data['markpercentages']  = $this->marksetting_m->get_marksetting_markpercentages_add($markpercentageArr);

							foreach ($individual as $index => $row) {
								$student = $this->student_m->general_get_single_student(['classesID' => $classes->classesID, 'sectionID' => $section->sectionID, 'roll' => $row['Roll']]);

								foreach ($this->data['markpercentages'] as $markpercentage) {
								if(isset($row[$markpercentage->markpercentagetype])){
									if (0 > $row[$markpercentage->markpercentagetype] || $row[$markpercentage->markpercentagetype] > $markpercentage->percentage) {
										$this->session->set_flashdata('error', 'Mark can not cross max mark for '. $subject->subject);
										$this->data["subview"] = "bulk_mark/add";
										$this->load->view('_layout_main', $this->data);
										return false;
									} else if ($subject->coscholatics != 1 && !is_numeric($row[$markpercentage->markpercentagetype]) && $row[$markpercentage->markpercentagetype] != NULL) {
										$this->session->set_flashdata('error', 'Non numeric value for non coscholatics subject'. $subject->subject);
										$this->data["subview"] = "bulk_mark/add";
										$this->load->view('_layout_main', $this->data);
										return false;
									} else if ($subject->coscholatics == 1 && is_numeric($row[$markpercentage->markpercentagetype]) && $row[$markpercentage->markpercentagetype] != NULL) {
										$this->session->set_flashdata('error', 'Numeric value for coscholatics subject '. $subject->subject);
										$this->data["subview"] = "bulk_mark/add";
										$this->load->view('_layout_main', $this->data);
										return false;
									} else if ($subject->coscholatics == 1 && !checkIfFromGrade($row[$markpercentage->markpercentagetype])) {
										$this->session->set_flashdata('error', 'Coscholatics marks must be A, B, C, D, E, F for '. $subject->subject);
										$this->data["subview"] = "bulk_mark/add";
										$this->load->view('_layout_main', $this->data);
										return false;
									}
								}
								}
							}
						}

						foreach ($return as $index => $individual) {
							$markRelationArray = [];

							$subject         = $this->subject_m->get_single_subject(array('classesID' => $classesID, 'subject' => $index));

							$this->data['markRelations']    = $this->getMarkRelationArray($this->mark_m->student_all_mark_array(array('schoolyearID' => $schoolyearID, 'examID' => $examID, 'classesID' => $classesID, 'subjectID' => $subject->subjectID)));

							$markpercentageArr['marktypeID'] = $this->data['siteinfos']->marktypeID;
							$markpercentageArr['classesID']  = $classesID;
							$markpercentageArr['examID']     = $examID;
							$markpercentageArr['subjectID']  = $subject->subjectID;
							$markpercentageArr['subject']    = $subject;

							$this->data['markpercentages']  = $this->marksetting_m->get_marksetting_markpercentages_add($markpercentageArr);

							foreach ($individual as $index => $row) {
								$student = $this->student_m->general_get_single_student(['classesID' => $classes->classesID, 'sectionID' => $section->sectionID, 'roll' => $row['Roll']]);

								foreach ($this->data['markpercentages'] as $markpercentage) {
								if(isset($row[$markpercentage->markpercentagetype])){
									if (0 > $row[$markpercentage->markpercentagetype] || $row[$markpercentage->markpercentagetype] > $markpercentage->percentage) {
										$row[$markpercentage->markpercentagetype] = '';
									} else if ($subject->coscholatics != 1 && !is_numeric($row[$markpercentage->markpercentagetype]) && $row[$markpercentage->markpercentagetype] != NULL) {
										$row[$markpercentage->markpercentagetype] = '';
									} else {
										if ($row[$markpercentage->markpercentagetype] == NULL) {
											$row[$markpercentage->markpercentagetype] = '';
										}
									}
									if (isset($row[$markpercentage->markpercentagetype])) {
										$markrelationID = $this->data['markwr'][$student->studentID][$markpercentage->markpercentageID];

										if($subject->coscholatics != 1) {
											if (isset($markrelationID)) {
												$markRelationArray[] = [
													'markrelationID' => $markrelationID,
													'mark' => $row[$markpercentage->markpercentagetype]
												];
											}
										} else {
											if (isset($markrelationID)) {
												
												$markRelationArray[] = [
													'markrelationID' => $markrelationID,
													'mark' => getMarkFromGrade($row[$markpercentage->markpercentagetype])
												];
											}
										}
										
									}
								}
								}

								if (customCompute($markRelationArray)) {
									$this->markrelation_m->update_batch_markrelation($markRelationArray, 'markrelationID');
								}
							}
						}
						$this->session->set_flashdata('success', 'Bulk upload successful');

						$this->data["subview"] = "bulk_mark/add";
						$this->load->view('_layout_main', $this->data);
					}
				}
			} else {
				$this->data["subview"] = "bulk_mark/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function bulk_mark_for_coscholatic_subjects($coscholasticSubjects,$classesID,$schoolyearID,$studentArray,$examID){
		$optionalArray1 = [];
		$sendStudent = $this->studentrelation_m->get_order_by_student($studentArray, '', $optionalArray1);
        
		$rows = []; 
		$subject_row = ['', '',''];
		$rows[0] = $subject_row;
		
		$first_row = ['S No.','Student Name ','Roll'];
        $rows[1] = $first_row;

		$markpercentageArr['marktypeID'] = $this->data['siteinfos']->marktypeID;
		$markpercentageArr['classesID']  = $classesID;
		$markpercentageArr['examID']     = $examID;


if (customCompute($sendStudent)) {
	foreach ($sendStudent as $index => $student) {
		$row[] = $index + 1;
		$row[] = $student->name;
		$row[] = $student->roll;

		foreach ($coscholasticSubjects as $key => $subject) {
			
			$markpercentageArr['subjectID']  = $subject->subjectID;
			$markpercentageArr['subject']    = $subject;
			$this->data['markpercentages']  = $this->marksetting_m->get_marksetting_markpercentages_add($markpercentageArr);
			$this->data['markRelations']    = $this->getMarkRelationArray($this->mark_m->student_all_mark_array(array('schoolyearID' => $schoolyearID, 'examID' => $examID, 'classesID' => $classesID, 'subjectID' => $subject->subjectID)));

			if($index == 1){
				$loop = 1;
				foreach ($this->data['markpercentages'] as $markpercentage) {
					if($loop == 1){
						$subject_row[] = $subject->subject;
						$first_row[] = $markpercentage->markpercentagetype;
						}
					$loop++;
				}
				$rows[0] = $subject_row;
				$rows[1] = $first_row;  
			}

            $l = 1;
   			foreach ($this->data['markpercentages'] as $data) {
				if($l == 1){
				   $row[] = getGradeFromMark($this->data['markRelations'][$student->studentID][$data->markpercentageID]);
				}
			$l++;
			}
		}
		
		$rows[] = $row;
		$row = [];
	}
}	


		$data = [
			'title' =>  'Coscholatics',
			'data'  =>  $rows
		];
		
		return $data;
	}

	private function  apiDownloadExcel($data = [['title' => '', 'data' => [],]], $filename = 'excel', $exam, $classes)
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

			$spreadsheet->getActiveSheet()->setTitle($d['title']);

			$sheet = $spreadsheet->getActiveSheet();
		}

		$spreadsheet->setActiveSheetIndex(0);
		$writer = new Xlsx($spreadsheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $exam->exam . '-' . $classes->classes . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	private function convertRow($data, $should_convert)
	{
		$return = [];
		$header = [];

		foreach ($data as $index => $row) {
			foreach ($row as $i => $r) {
				$row[$i] = trim($r);
			}

			if (!$should_convert) {
				$return[] = $row;
			} else {
				if ($index == 0) {
					$header = $row;
					continue;
				}

				foreach ($row as $i => $d) {
					$temp[$header[$i]] = strlen($d) ? $d : NULL;
				}
				$return[] = $temp;
			}
		}

		return $return;
	}

	public function view($studentID = null, $classID = null)
	{
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css'
			),
			'js' => array(
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js'
			)
		);

		if ((int) $studentID && (int) $classID) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classID, 'srschoolyearID' => $schoolyearID));
			if (customCompute($student)) {
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if (isset($fetchClass[$classID])) {
					$this->getView($studentID, $classID);
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

	private function getMarkRelationArray($arrays = NULL)
	{
		$mark   = [];
		$markwr = [];
		if (customCompute($arrays)) {
			foreach ($arrays as $array) {
				$mark[$array->studentID][$array->markpercentageID]   = $array->mark;
				$markwr[$array->studentID][$array->markpercentageID] = $array->markrelationID;
			}
		}
		$this->data['markwr'] = $markwr;
		return $mark;
	}

	private function getView($id, $url)
	{
		if ((int)$id && (int)$url) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentInfo = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID));

			$this->pluckInfo();
			$this->basicInfo($studentInfo);
			$this->markInfo($studentInfo);

			if (customCompute($studentInfo)) {
				$this->data["subview"] = "mark/view";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	private function pluckInfo()
	{
		$this->data['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
	}

	private function basicInfo($studentInfo)
	{
		if (customCompute($studentInfo)) {
			$this->data['profile']  = $studentInfo;
			$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $studentInfo->usertypeID));
			$this->data['class']    = $this->classes_m->get_single_classes(array('classesID' => $studentInfo->srclassesID));
			$this->data['section']  = $this->section_m->general_get_single_section(array('sectionID' => $studentInfo->srsectionID));
		} else {
			$this->data['profile'] = [];
		}
	}

	private function markInfo($studentInfo)
	{
		if (customCompute($studentInfo)) {
			$this->getMark($studentInfo->studentID, $studentInfo->srclassesID);
		} else {
			$this->data['set'] 				= [];
			$this->data["exams"] 			= [];
			$this->data["grades"] 			= [];
			$this->data['markpercentages']	= [];
			$this->data['validExam'] 		= [];
			$this->data['separatedMarks'] 	= [];
			$this->data["highestMarks"] 	= [];
			$this->data["section"] 			= [];
		}
	}

	private function getMark($studentID, $classesID)
	{
		if ((int)$studentID && (int)$classesID) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$student      = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
			$classes      = $this->classes_m->get_single_classes(array('classesID' => $classesID));

			if (customCompute($student) && customCompute($classes)) {
				$queryArray = [
					'classesID'    => $student->srclassesID,
					'sectionID'    => $student->srsectionID,
					'studentID'    => $student->srstudentID,
					'schoolyearID' => $schoolyearID,
				];

				$exams             = pluck($this->exam_m->get_exam(), 'exam', 'examID');
				$grades            = $this->grade_m->get_grade();
				$marks             = $this->mark_m->student_all_mark_array($queryArray);
				$markpercentages   = $this->markpercentage_m->get_markpercentage();

				$subjects          = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
				$subjectArr        = [];
				$optionalsubjectArr = [];
				if (customCompute($subjects)) {
					foreach ($subjects as $subject) {
						if ($subject->type == 0) {
							$optionalsubjectArr[$subject->subjectID] = $subject->subjectID;
						}
						$subjectArr[$subject->subjectID] = $subject;
					}
				}

				$retMark = [];
				if (customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}

				$allStudentMarks = $this->mark_m->student_all_mark_array(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
				$highestMarks    = [];
				foreach ($allStudentMarks as $value) {
					if (!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
						$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
					}
					$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
				}
				$marksettings  = $this->marksetting_m->get_marksetting_markpercentages();

				$this->data['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;
				$this->data['subjects']          = $subjectArr;
				$this->data['exams']             = $exams;
				$this->data['grades']            = $grades;
				$this->data['markpercentages']   = pluck($markpercentages, 'obj', 'markpercentageID');
				$this->data['optionalsubjectArr'] = $optionalsubjectArr;
				$this->data['marks']             = $retMark;
				$this->data['highestmarks']      = $highestMarks;
				$this->data['marksettings']      = isset($marksettings[$classesID]) ? $marksettings[$classesID] : [];
			} else {
				$this->data['settingmarktypeID'] = 0;
				$this->data['subjects']          = [];
				$this->data['exams']             = [];
				$this->data['grades']            = [];
				$this->data['markpercentages']   = [];
				$this->data['optionalsubjectArr'] = [];
				$this->data['marks']             = [];
				$this->data['highestmarks']      = [];
				$this->data['marksettings']      = [];
			}
		} else {
			$this->data['settingmarktypeID'] = 0;
			$this->data['subjects']          = [];
			$this->data['exams']             = [];
			$this->data['grades']            = [];
			$this->data['markpercentages']   = [];
			$this->data['optionalsubjectArr'] = [];
			$this->data['marks']             = [];
			$this->data['highestmarks']      = [];
			$this->data['marksettings']      = [];
		}
	}

	public function mark_send()
	{
		$retArray['status'] = FALSE;
		$retArray['message'] = '';

		if ($_POST) {
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
				$subjectID 		= $this->input->post("subjectID");
				$inputs 		= $this->input->post("inputs");
				$schoolyearID 	= $this->data['siteinfos']->school_year;

				$markRelationArray = [];
				if (customCompute($inputs)) {
					foreach ($inputs as $key => $value) {
						$data = explode('-', $value['mark']);
						if (!empty($value['value']) || $value['value'] != "") {
							$markRelationArray[] = [
								'markrelationID' => $data[1],
								'mark' => abs($value['value'])
							];
						} else {
							$markRelationArray[] = [
								'markrelationID' => $data[1],
								'mark' => NULL
							];
						}
					}
				}

				if (customCompute($markRelationArray)) {
					$this->markrelation_m->update_batch_markrelation($markRelationArray, 'markrelationID');
				}

				$retArray['status'] = TRUE;;
				$retArray['message'] = $this->lang->line('mark_success');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = 'Something wrong';
			echo json_encode($retArray);
			exit;
		}
	}

	public function print_preview()
	{
		if (permissionChecker('mark_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('mark') && ($this->session->userdata('loginuserID') == htmlentities(escapeString($this->uri->segment(3)))))) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentID 	= htmlentities(escapeString($this->uri->segment(3)));
			$classID 	= htmlentities(escapeString($this->uri->segment(4)));

			if ((int)$studentID && (int)$classID) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classID, 'srschoolyearID' => $schoolyearID));
				if (customCompute($student)) {
					$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
					if (isset($fetchClass[$classID])) {
						$this->getMarkPrintPDF($studentID, $classID);
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
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	private function getMarkPrintPDF($studentID, $classesID)
	{
		if ((int)$studentID && (int)$classesID) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$student      = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID));
			$classes      = $this->classes_m->get_single_classes(array('classesID' => $classesID));

			if (customCompute($student) && customCompute($classes)) {
				$queryArray = [
					'classesID'    => $student->srclassesID,
					'sectionID'    => $student->srsectionID,
					'studentID'    => $student->srstudentID,
					'schoolyearID' => $schoolyearID,
				];

				$exams             = pluck($this->exam_m->get_exam(), 'exam', 'examID');
				$grades            = $this->grade_m->get_grade();
				$marks             = $this->mark_m->student_all_mark_array($queryArray);
				$markpercentages   = $this->markpercentage_m->get_markpercentage();

				$subjects          = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
				$subjectArr        = [];
				$optionalsubjectArr = [];
				if (customCompute($subjects)) {
					foreach ($subjects as $subject) {
						if ($subject->type == 0) {
							$optionalsubjectArr[$subject->subjectID] = $subject->subjectID;
						}
						$subjectArr[$subject->subjectID] = $subject;
					}
				}

				$retMark = [];
				if (customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}
				$usertype        = $this->usertype_m->get_single_usertype(array('usertypeID' => $student->usertypeID));
				$section         = $this->section_m->general_get_single_section(array('sectionID' => $student->srsectionID));

				$allStudentMarks = $this->mark_m->student_all_mark_array(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
				$highestMarks    = [];
				foreach ($allStudentMarks as $value) {
					if (!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
						$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
					}
					$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
				}
				$marksettings  = $this->marksetting_m->get_marksetting_markpercentages();

				$this->data['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;
				$this->data['subjects']          = $subjectArr;
				$this->data['exams']             = $exams;
				$this->data['grades']            = $grades;
				$this->data['markpercentages']   = pluck($markpercentages, 'obj', 'markpercentageID');
				$this->data['optionalsubjectArr'] = $optionalsubjectArr;
				$this->data['marks']             = $retMark;
				$this->data['highestmarks']      = $highestMarks;
				$this->data['marksettings']      = isset($marksettings[$classesID]) ? $marksettings[$classesID] : [];

				$this->data['student']           = $student;
				$this->data['classes']           = $classes;
				$this->data['section']           = $section;
				$this->data['usertype']          = $usertype;

				$this->reportPDF('markmodule.css', $this->data, 'mark/print_preview');
			} else {
				$this->data['settingmarktypeID'] = 0;
				$this->data['subjects']          = [];
				$this->data['exams']             = [];
				$this->data['grades']            = [];
				$this->data['markpercentages']   = [];
				$this->data['optionalsubjectArr'] = [];
				$this->data['marks']             = [];
				$this->data['highestmarks']      = [];
				$this->data['marksettings']      = [];

				$this->data['student']           = [];
				$this->data['classes']           = [];
				$this->data['section']           = [];
				$this->data['usertype']          = [];
			}
		} else {
			$this->data['settingmarktypeID'] = 0;
			$this->data['subjects']          = [];
			$this->data['exams']             = [];
			$this->data['grades']            = [];
			$this->data['markpercentages']   = [];
			$this->data['optionalsubjectArr'] = [];
			$this->data['marks']             = [];
			$this->data['highestmarks']      = [];
			$this->data['marksettings']      = [];

			$this->data['student']           = [];
			$this->data['classes']           = [];
			$this->data['section']           = [];
			$this->data['usertype']          = [];
		}
	}

	public function send_mail()
	{
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if (permissionChecker('mark_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('mark') && ($this->session->userdata('loginuserID') == $this->input->post('id')))) {
			if ($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					echo json_encode($retArray);
					exit;
				} else {
					$studentID = $this->input->post('id');
					$classesID = $this->input->post('set');

					if ((int)$studentID && (int)$classesID) {
						$schoolyearID = $this->session->userdata('defaultschoolyearID');
						$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
						$classes = $this->classes_m->get_single_classes(array('classesID' => $classesID));
						if (customCompute($student) && customCompute($classes)) {
							$email        = $this->input->post('to');
							$inputsubject = $this->input->post('subject');
							$message      = $this->input->post('message');

							$queryArray = [
								'classesID' => $student->srclassesID,
								'sectionID' => $student->srsectionID,
								'studentID' => $student->srstudentID,
								'schoolyearID' => $schoolyearID,
							];

							$exams             = pluck($this->exam_m->get_exam(), 'exam', 'examID');
							$grades            = $this->grade_m->get_grade();
							$marks             = $this->mark_m->student_all_mark_array($queryArray);
							$markpercentages   = $this->markpercentage_m->get_markpercentage();

							$subjects          = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
							$subjectArr        = [];
							$optionalsubjectArr = [];
							if (customCompute($subjects)) {
								foreach ($subjects as $subject) {
									if ($subject->type == 0) {
										$optionalsubjectArr[$subject->subjectID] = $subject->subjectID;
									}
									$subjectArr[$subject->subjectID] = $subject;
								}
							}

							$retMark = [];
							if (customCompute($marks)) {
								foreach ($marks as $mark) {
									$retMark[$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
								}
							}
							$usertype        = $this->usertype_m->get_single_usertype(array('usertypeID' => $student->usertypeID));
							$section         = $this->section_m->general_get_single_section(array('sectionID' => $student->srsectionID));

							$allStudentMarks = $this->mark_m->student_all_mark_array(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
							$highestMarks = [];
							foreach ($allStudentMarks as $value) {
								if (!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
									$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
								}
								$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
							}
							$marksettings  = $this->marksetting_m->get_marksetting_markpercentages();

							$this->data['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;
							$this->data['subjects']          = $subjectArr;
							$this->data['exams']             = $exams;
							$this->data['grades']            = $grades;
							$this->data['markpercentages']   = pluck($markpercentages, 'obj', 'markpercentageID');
							$this->data['optionalsubjectArr'] = $optionalsubjectArr;
							$this->data['marks']             = $retMark;
							$this->data['highestmarks']      = $highestMarks;
							$this->data['marksettings']      = isset($marksettings[$classesID]) ? $marksettings[$classesID] : [];

							$this->data['student']           = $student;
							$this->data['classes']           = $classes;
							$this->data['section']           = $section;
							$this->data['usertype']          = $usertype;

							$this->reportSendToMail('markmodule.css', $this->data, 'mark/print_preview', $email, $inputsubject, $message);
							$retArray['message'] = "Success";
							$retArray['status'] = TRUE;
							echo json_encode($retArray);
							exit;
						} else {
							$retArray['message'] = $this->lang->line('mark_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('mark_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('mark_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('mark_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function mark_list()
	{
		$classID = $this->input->post('id');
		if ((int)$classID) {
			$string = base_url("mark/index/$classID");
			echo $string;
		} else {
			redirect(base_url("mark/index"));
		}
	}

	public function examcall()
	{
		$classesID = $this->input->post('classesID');
		if ((int)$classesID) {
			$exams    = pluck($this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID), 'obj', 'examID');
			echo "<option value='0'>", $this->lang->line("mark_select_exam"), "</option>";
			if (customCompute($exams)) {
				foreach ($exams as $exam) {
					echo "<option value=" . $exam->examID . ">" . $exam->exam . "</option>";
				}
			}
		} else {
			echo "<option value='0'>", $this->lang->line("mark_select_exam"), "</option>";
		}
	}

	public function subjectcall()
	{
		$id = $this->input->post('id');
		if ((int)$id) {
			$allsubject = $this->subject_m->get_order_by_subject(array("classesID" => $id));
			echo "<option value='0'>", $this->lang->line("mark_select_subject"), "</option>";
			foreach ($allsubject as $value) {
				echo "<option value=\"$value->subjectID\">", $value->subject, "</option>";
			}
		} else {
			echo "<option value='0'>", $this->lang->line("mark_select_subject"), "</option>";
		}
	}

	public function sectioncall()
	{
		$id = $this->input->post('id');
		if ((int)$id) {
			$allsection = $this->section_m->get_order_by_section(array("classesID" => $id));
			echo "<option value='0'>", $this->lang->line("mark_select_section"), "</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">", $value->section, "</option>";
			}
		} else {
			echo "<option value='0'>", $this->lang->line("mark_select_section"), "</option>";
		}
	}

	public function unique_data($data)
	{
		if ($data != '') {
			if ($data == '0') {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	public function unique_examID()
	{
		if ($this->input->post('examID') == 0) {
			$this->form_validation->set_message("unique_examID", "The %s field is required");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_classesID()
	{
		if ($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classesID", "The %s field is required");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_sectionID()
	{
		if ($this->input->post('sectionID') == 0) {
			$this->form_validation->set_message("unique_sectionID", "The %s field is required");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_subjectID()
	{
		if ($this->input->post('subjectID') == 0) {
			$this->form_validation->set_message("unique_subjectID", "The %s field is required");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_inputs()
	{
		$inputs = $this->input->post('inputs');
		if (customCompute($inputs)) {
			$classesID       = $this->input->post('classesID');
			$examID          = $this->input->post('examID');
			$subjectID       = $this->input->post('subjectID');
			$subject         = $this->subject_m->get_single_subject(array('subjectID' => $subjectID));

			$markpercentageArr['marktypeID'] = $this->data['siteinfos']->marktypeID;
			$markpercentageArr['classesID']  = $classesID;
			$markpercentageArr['examID']     = $examID;
			$markpercentageArr['subjectID']  = $subjectID;
			$markpercentageArr['subject']    = $subject;

			$getMarkPercentage = $this->marksetting_m->get_marksetting_markpercentages_add($markpercentageArr);
			foreach ($inputs as $value) {
				$markpercentageID = $value['markpercentageid'];
				$markValue        = $value['value'];

				if (isset($getMarkPercentage[$markpercentageID])) {
					if (is_numeric($markValue)) {
						if (0 > $markValue || $markValue > $getMarkPercentage[$markpercentageID]->percentage) {
							$this->form_validation->set_message('unique_inputs', 'Mark can not cross max mark');
							return FALSE;
						}
					} else {
						if (is_string($markValue) && $markValue != '') {
							$this->form_validation->set_message('unique_inputs', 'String data is deniable');
							return FALSE;
						}
					}
				}
			}
		}
		return TRUE;
	}
}
