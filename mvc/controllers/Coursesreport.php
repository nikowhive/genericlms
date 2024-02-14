<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use \PhpOffice\PhpSpreadsheet\Style\Border;

class Coursesreport extends Admin_Controller {
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
        $this->load->model("courses_m");
		$this->load->model("student_m");
		$this->load->model("section_m");
		$this->load->model("homework_m");
		$this->load->model("classwork_m");
		$this->load->model("assignment_m");
		$this->load->model("homeworkanswer_m");
		$this->load->model("classworkanswer_m");
		$this->load->model("assignmentanswer_m");
        $this->load->model("coursesstudent_progress_m");
        $this->load->library('session');
        $language = $this->session->userdata('lang');
        $this->lang->load('courses', $language);

    }

    protected function rules() {
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line('onlineexamreport_classes'),
				'rules' => 'trim|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line('onlineexamreport_section'),
				'rules' => 'trim|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line('onlineexamreport_student'),
				'rules' => 'trim|xss_clean|numeric|callback_unique_data'
			)
		);

		return $rules;
	}

    function index() {
        $this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$this->data['classes'] 		= $this->classes_m->general_get_classes();
		$this->data["subview"] 		= "report/courses/CourseReportView";
		$this->load->view('_layout_main', $this->data);
    }

    public function unique_data() {
		$classesID = $this->input->post('classesID');
		if($classesID === '0') {
			$this->form_validation->set_message('unique_data', 'The %s field is required.');
			return FALSE;
		}
		return TRUE;
    }
    
    public function getCourseList() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';

		$schoolyearID = $this->session->userdata('defaultschoolyearID');
        
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run() == FALSE) {
				$retArray           = $this->form_validation->error_array();
				$retArray['status'] = FALSE;
			    echo json_encode($retArray);
			    exit;
			} else {
				$classesID 		= $this->input->post('classesID');
				$subjectID 		= $this->input->post('subjectID');
				$courseID 		= $this->input->post('courseID');
				$studentID 		= $this->input->post('studentID');

				$this->data['classesID'] = $classesID;
				$this->data['subjectID'] = $subjectID;
				$this->data['courseID']  = $courseID;
				$this->data['studentID'] = $studentID;
				
				$queryArray = [];
				$courseArray = [];
				if((int)$classesID && $classesID > 0) {
					$queryArray['class_id']  = $classesID;
					$courseArray['class_id'] = $classesID;
                }
                if((int)$subjectID && $subjectID > 0) {
					$queryArray['subject_id']  = $subjectID;
					$courseArray['subject_id'] = $subjectID;
                }
                if((int)$courseID && $courseID > 0) {
					$queryArray['course_id']  = $courseID;
					$courseArray['course_id'] = $courseID;
				}
				if((int)$studentID && $studentID > 0) {
					$queryArray['student_id']  = $studentID;
					$courseArray['student_id'] = $studentID;
                }
                

                $this->data['course_units'] = $this->courses_m->get_published_course_unit_by_course($courseID);

                foreach($this->data['course_units'] as $index => $units) {
                    $this->data['course_units'][$index]->chapters = $this->courses_m->get_published_course_unit_chapter($units->id);
                    foreach($this->data['course_units'][$index]->chapters as $indexy => $chapter) {
                        if($this->courses_m->get_coursecontent($chapter->id)) {
                            $coverage = 0;
                            $covered = 0;
                            $contents = $this->courses_m->get_contents($chapter->id);
							$contentArray = [];
                            foreach($contents as $key => $content) {
                                $array = [
                                    'student_id' => (int)$studentID,
                                    'content_id' => (int)$content->id,
                                    'chapter_id' => $chapter->id
                                ];
                                $exists = $this->coursesstudent_progress_m->get_order_by_courses_student_progress($array);
                                $contents[$key]->exists = $exists ? true: false;
                                $covered += $exists ? $content->percentage_coverage: 0;
                                $coverage += $content->percentage_coverage;

								$contentArray[$key]['content_title']       = $content->content_title;
						        $contentArray[$key]['exists']              = $exists ? $content->percentage_coverage : 0;
						        $contentArray[$key]['percentage_coverage'] = $content->percentage_coverage;

                            }
                            $this->data['course_units'][$index]->chapters[$indexy]->content_exists = true;
                            $this->data['course_units'][$index]->chapters[$indexy]->total_coverage = $coverage;
                            $this->data['course_units'][$index]->chapters[$indexy]->covered        = $covered;
							$this->data['course_units'][$index]->chapters[$indexy]->content_count  = count($contentArray);
					        $this->data['course_units'][$index]->chapters[$indexy]->contents       = $contentArray;
						
                        } else {
                            $this->data['course_units'][$index]->chapters[$indexy]->content_exists = false;
							$this->data['course_units'][$index]->chapters[$indexy]->content_count  = 0;
					        $this->data['course_units'][$index]->chapters[$indexy]->contents       = [];
                        }

						//quizzes
						$quizzes = $this->courses_m->get_published_quizzes($chapter->id);
						$quizzArray = [];
						if ($quizzes) {
							foreach ($quizzes as $k => $quiz) {
								$quiz_result = $this->courses_m->get_quiz_report($studentID, $quiz->id);
								$quizzArray[$k]['quiz_name']           = $quiz->quiz_name;
								$quizzArray[$k]['percentage_coverage'] = $quiz->percentage_coverage;
								$quizzArray[$k]['scored']              = ((isset($quiz_result)&& $quiz_result != ''  ? $quiz_result->total_percentage : 0) / 100) * $quiz->percentage_coverage . ' out of ' . $quiz->percentage_coverage;
							}
						} 
						$this->data['course_units'][$index]->chapters[$indexy]->quizz_count       = count($quizzArray);
						$this->data['course_units'][$index]->chapters[$indexy]->quizzes        = $quizzArray;
		
		
						// homeworks
						$homeworks = $this->homework_m->get_order_by_published_homework([
							'unit_id'       => $units->id,
							'chapter_id'    => $chapter->id,
							'schoolyearID'  => $schoolyearID
						]);
						if(customCompute($homeworks)){
							foreach($homeworks as $h=>$homework){
								$homeworkanswer                      = $this->homeworkanswer_m->get_homeworkanswer_by_student($homework->homeworkID,$schoolyearID,$studentID);
							    list($statusTitle, $statusLabel)     = $this->answerStatus($homeworkanswer?$homeworkanswer->status:'');
								$homeworks[$h]->answer_status        = $statusTitle;
								$homeworks[$h]->answer_status_label  = $statusLabel;
								$homeworks[$h]->date                 = $homeworkanswer?'Submitted Date <br> '.$homeworkanswer->answerdate:'Dateline Date <br> '.$homework->deadlinedate;
							}
						}
		
						$this->data['course_units'][$index]->chapters[$indexy]->homework_count   = count($homeworks);
						$this->data['course_units'][$index]->chapters[$indexy]->homeworks        = $homeworks;
		
						// classworks
						$classworks = $this->classwork_m->get_order_by_published_classwork([
							'unit_id'       => $units->id,
							'chapter_id'    => $chapter->id,
							'schoolyearID'  => $this->session->userdata('defaultschoolyearID')
						]);
						if(customCompute($classworks)){
							foreach($classworks as $c=>$classwork){
								$classworkanswer                      = $this->classworkanswer_m->get_classworkanswer_by_student($classwork->classworkID,$schoolyearID,$studentID);
								list($statusTitle, $statusLabel)      = $this->answerStatus($classworkanswer?$classworkanswer->status:'');
								$classworks[$c]->answer_status        = $statusTitle;
								$classworks[$c]->answer_status_label  = $statusLabel;
								$classworks[$c]->date                 = $classworkanswer?'Submitted Date <br> '.$classworkanswer->answerdate:'Dateline Date <br> '.$classwork->deadlinedate;
							}
						}
		
						$this->data['course_units'][$index]->chapters[$indexy]->classwork_count   = count($classworks);
						$this->data['course_units'][$index]->chapters[$indexy]->classworks        = $classworks;
		
						// assignments
						$assignments = $this->assignment_m->get_order_by_published_assignment([
							'unit_id'       => $units->id,
							'chapter_id'    => $chapter->id,
							'schoolyearID'  => $this->session->userdata('defaultschoolyearID')
						]);

						

						if(customCompute($assignments)){
							foreach($assignments as $a=>$assignment){
								$assignmentanswer                      = $this->assignmentanswer_m->get_assignmentanswer_by_student($assignment->assignmentID,$schoolyearID,$studentID);
								list($statusTitle, $statusLabel)       = $this->answerStatus($assignmentanswer?$assignmentanswer->status:'');
								$assignments[$a]->answer_status        = $statusTitle;
								$assignments[$a]->answer_status_label  = $statusLabel;
								$assignments[$a]->date                 = $assignmentanswer?'Submitted Date <br> '.$assignmentanswer->answerdate:'Dateline Date <br> '.$assignment->deadlinedate;
							}
						}
		
						$this->data['course_units'][$index]->chapters[$indexy]->assignment_count   = count($assignments);
						$this->data['course_units'][$index]->chapters[$indexy]->assignments        = $assignments;

                    }
                }
                
				$retArray['render'] = $this->load->view('report/courses/CourseReport', $this->data, true);
				$retArray['status'] = TRUE;
				echo json_encode($retArray);
			    exit;
			}
		}
    }

	public function PDF()
	{

		$classesID 		= $this->input->get('classesID');
		$subjectID 		= $this->input->get('subjectID');
		$courseID 		= $this->input->get('courseID');
		$studentID 		= $this->input->get('studentID');

		$queryArray = [];
		$courseArray = [];
		if((int)$classesID && $classesID > 0) {
			$queryArray['class_id']  = $classesID;
			$courseArray['class_id'] = $classesID;
		}
		if((int)$subjectID && $subjectID > 0) {
			$queryArray['subject_id']  = $subjectID;
			$courseArray['subject_id'] = $subjectID;
		}
		if((int)$courseID && $courseID > 0) {
			$queryArray['course_id']  = $courseID;
			$courseArray['course_id'] = $courseID;
		}
		if((int)$studentID && $studentID > 0) {
			$queryArray['student_id']  = $studentID;
			$courseArray['student_id'] = $studentID;
		}

		$this->data['course_units'] = $this->courses_m->get_published_course_unit_by_course($courseID);

		foreach($this->data['course_units'] as $index => $units) {
			$this->data['course_units'][$index]->chapters = $this->courses_m->get_published_course_unit_chapter($units->id);
			foreach($this->data['course_units'][$index]->chapters as $indexy => $chapters) {
				if($this->courses_m->get_coursecontent($chapters->id)) {
					$coverage = 0;
					$covered  = 0;
					$contents = $this->courses_m->get_contents($chapters->id);
					$quizzes  = $this->courses_m->get_published_quizzes($chapters->id);


					$contentArray = [];
					foreach($contents as $key => $content) {
						$array = [
							'student_id' => (int)$studentID,
							'content_id' => (int)$content->id,
							'chapter_id' => $chapters->id
						];
						$exists = $this->coursesstudent_progress_m->get_order_by_courses_student_progress($array);

						$contents[$key]->exists = $exists ? true: false;
						$covered += $exists ? $content->percentage_coverage: 0;
						$coverage += $content->percentage_coverage;

						$contentArray[$key]['content_title']       = $content->content_title;
						$contentArray[$key]['exists']              = $exists ? $content->percentage_coverage : 0;
						$contentArray[$key]['percentage_coverage'] = $content->percentage_coverage;

					}

					$quizzArray = [];
					if ($quizzes) {
						foreach ($quizzes as $k => $quiz) {
							$quiz_result = $this->courses_m->get_quiz_report($studentID, $quiz->id);
							$quizzArray[$k]['quiz_name']           = $quiz->quiz_name;
							$quizzArray[$k]['percentage_coverage'] = $quiz->percentage_coverage;
							$quizzArray[$k]['scored']              = ((isset($quiz_result) ? $quiz_result->total_percentage : 0) / 100) * $quiz->percentage_coverage . ' out of ' . $quiz->percentage_coverage;
						}
					}

					$this->data['course_units'][$index]->chapters[$indexy]->content_exists = true;
					$this->data['course_units'][$index]->chapters[$indexy]->total_coverage = $coverage;
					$this->data['course_units'][$index]->chapters[$indexy]->covered        = $covered;
					$this->data['course_units'][$index]->chapters[$indexy]->contents       = $contentArray;
					$this->data['course_units'][$index]->chapters[$indexy]->quizzes        = $quizzArray;
				} else {
					$this->data['course_units'][$index]->chapters[$indexy]->content_exists = false;
				}
			}
		}


		if($studentID != ''){
			$this->data['student']  = $student =  $this->student_m->general_get_single_student(['studentID' => $studentID]);
			$this->data['class']    = $class   =  $this->classes_m->general_get_single_classes(['classesID' => $student->classesID]);
			$this->data['section']  = $section =  $this->section_m->general_get_single_section(['sectionID' => $student->classesID]);
			
		    $this->data['filename'] = $student->name.'-course-report';
		}else{
			$this->data['filename'] = 'student-course-report';
		}

		$template = 'report/courses/CourseReportPDF';
		
		$this->resultPDF('studentpdfresult.css', $this->data, $template);
	}

	public function resultPDF($stylesheet = NULL, $data = NULL, $viewpath = NULL, $mode = 'view', $pagesize = 'a4', $pagetype = 'portrait')
	{

		$designType = 'LTR';
		$this->data['panel_title'] = $this->lang->line('panel_title');
		$html = $this->load->view($viewpath, $this->data, true);

		$this->load->library('mhtml2pdf');

		$this->mhtml2pdf->folder('uploads/student/');
		$this->mhtml2pdf->filename($this->data['filename']);
		$this->mhtml2pdf->paper($pagesize, $pagetype);
		$this->mhtml2pdf->html($html);

		if (!empty($stylesheet)) {
			$stylesheet = file_get_contents(base_url('assets/pdf/' . $designType . '/' . $stylesheet));
			return $this->mhtml2pdf->create($mode, $this->data['panel_title'], $stylesheet);
		} else {
			return $this->mhtml2pdf->create($mode, $this->data['panel_title']);
		}
	}

	public function exportExcel()
	{

		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		$classesID 		= $this->input->get('classesID');
		$subjectID 		= $this->input->get('subjectID');
		$courseID 		= $this->input->get('courseID');
		$studentID 		= $this->input->get('studentID');

		$queryArray = [];
		$courseArray = [];
		if((int)$classesID && $classesID > 0) {
			$queryArray['class_id']  = $classesID;
			$courseArray['class_id'] = $classesID;
		}
		if((int)$subjectID && $subjectID > 0) {
			$queryArray['subject_id']  = $subjectID;
			$courseArray['subject_id'] = $subjectID;
		}
		if((int)$courseID && $courseID > 0) {
			$queryArray['course_id']  = $courseID;
			$courseArray['course_id'] = $courseID;
		}
		if((int)$studentID && $studentID > 0) {
			$queryArray['student_id']  = $studentID;
			$courseArray['student_id'] = $studentID;
		}

		$this->data['course_units'] = $this->courses_m->get_published_course_unit_by_course($courseID);

		foreach($this->data['course_units'] as $index => $units) {
			$this->data['course_units'][$index]->chapters = $this->courses_m->get_published_course_unit_chapter($units->id);
			
			foreach($this->data['course_units'][$index]->chapters as $indexy => $chapter) {
			
				// contents
				if($this->courses_m->get_coursecontent($chapter->id)) {
					$coverage = 0;
					$covered = 0;

					$contents = $this->courses_m->get_contents($chapter->id);

					$contentArray = [];
					foreach($contents as $key => $content) {
						$array = [
							'student_id' => (int)$studentID,
							'content_id' => (int)$content->id,
							'chapter_id' => $chapter->id
						];
						$exists = $this->coursesstudent_progress_m->get_order_by_courses_student_progress($array);

						$contents[$key]->exists = $exists ? true: false;
						$covered += $exists ? $content->percentage_coverage: 0;
						$coverage += $content->percentage_coverage;

						$contentArray[$key]['content_title']       = $content->content_title;
						$contentArray[$key]['exists']              = $exists ? $content->percentage_coverage : 0;
						$contentArray[$key]['percentage_coverage'] = $content->percentage_coverage;

					}

					$this->data['course_units'][$index]->chapters[$indexy]->content_exists = true;
					$this->data['course_units'][$index]->chapters[$indexy]->total_coverage = $coverage;
					$this->data['course_units'][$index]->chapters[$indexy]->covered        = $covered;
					$this->data['course_units'][$index]->chapters[$indexy]->content_count  = count($contentArray);
					$this->data['course_units'][$index]->chapters[$indexy]->contents       = $contentArray;
					
					
				} else {
					$this->data['course_units'][$index]->chapters[$indexy]->content_exists = false;
					$this->data['course_units'][$index]->chapters[$indexy]->content_count  = 0;
					$this->data['course_units'][$index]->chapters[$indexy]->contents       = [];

				}

				//quizzes
                $quizzes = $this->courses_m->get_published_quizzes($chapter->id);
				$quizzArray = [];
				if ($quizzes) {
					foreach ($quizzes as $k => $quiz) {
						$quiz_result = $this->courses_m->get_quiz_report($studentID, $quiz->id);
						$quizzArray[$k]['quiz_name']           = $quiz->quiz_name;
						$quizzArray[$k]['percentage_coverage'] = $quiz->percentage_coverage;
						$quizzArray[$k]['scored']              = ((isset($quiz_result)&& $quiz_result != ''  ? $quiz_result->total_percentage : 0) / 100) * $quiz->percentage_coverage . ' out of ' . $quiz->percentage_coverage;
					}
				} 
				$this->data['course_units'][$index]->chapters[$indexy]->quizz_count       = count($quizzArray);
				$this->data['course_units'][$index]->chapters[$indexy]->quizzes        = $quizzArray;


				// homeworks
				$homeworks = $this->homework_m->get_order_by_published_homework([
					'unit_id'       => $units->id,
					'chapter_id'    => $chapter->id,
					'schoolyearID'  => $schoolyearID
				]);
				if(customCompute($homeworks)){
					foreach($homeworks as $h=>$homework){
						$homeworkanswer               = $this->homeworkanswer_m->get_homeworkanswer_by_student($homework->homeworkID,$schoolyearID,$studentID);
						$homeworks[$h]->answer_status = $homeworkanswer?$homeworkanswer->status:'Not Submitted';
						$homeworks[$h]->date          = $homeworkanswer?$homeworkanswer->answerdate:$homework->deadlinedate;
					}
				}

				$this->data['course_units'][$index]->chapters[$indexy]->homework_count   = count($homeworks);
				$this->data['course_units'][$index]->chapters[$indexy]->homeworks        = $homeworks;

				// classworks
				$classworks = $this->classwork_m->get_order_by_published_classwork([
					'unit_id'       => $units->id,
					'chapter_id'    => $chapter->id,
					'schoolyearID'  => $schoolyearID
				]);
				if(customCompute($classworks)){
					foreach($classworks as $c=>$classwork){
						$classworkanswer                = $this->classworkanswer_m->get_classworkanswer_by_student($classwork->classworkID,$schoolyearID,$studentID);
						$classworks[$c]->answer_status  = $classworkanswer?$classworkanswer->status:'Not Submitted';
						$classworks[$c]->date           = $classworkanswer?$classworkanswer->answerdate:$classwork->deadlinedate;
					}
				}

				$this->data['course_units'][$index]->chapters[$indexy]->classwork_count   = count($classworks);
				$this->data['course_units'][$index]->chapters[$indexy]->classworks        = $classworks;

				// assignments
				$assignments = $this->assignment_m->get_order_by_published_assignment([
					'unit_id'       => $units->id,
					'chapter_id'    => $chapter->id,
					'schoolyearID'  => $schoolyearID
				]);
				
				if(customCompute($assignments)){
					foreach($assignments as $a=>$assignment){
						$assignmentanswer               = $this->assignmentanswer_m->get_assignmentanswer_by_student($assignment->assignmentID,$schoolyearID,$studentID);
						$assignments[$a]->answer_status = $assignmentanswer?$assignmentanswer->status:'Not Submitted';
						$assignments[$a]->date          = $assignmentanswer?$assignmentanswer->answerdate:$assignment->deadlinedate;
					}
				}

				$this->data['course_units'][$index]->chapters[$indexy]->assignment_count   = count($assignments);
				$this->data['course_units'][$index]->chapters[$indexy]->assignments        = $assignments;


			}
		}

		if($studentID != ''){
			$student  =  $this->student_m->general_get_single_student(['studentID' => $studentID]);
			$class    =  $this->classes_m->general_get_single_classes(['classesID' => $student->classesID]);
			$section  =  $this->section_m->general_get_single_section(['sectionID' => $student->classesID]);
		    $filename = $student->name.'-course-report';
		}else{
			$filename = 'student-course-report';
		}

		return $this->generateXML($this->data['course_units'], $filename);
		
	}


	private function generateXML($data, $filename = 'filename')
	{

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		        $i = 0;
				$sheet = $spreadsheet->createSheet($i); //Setting index when creating

				$sheet->getColumnDimension('A')->setWidth(5);
				$sheet->getColumnDimension('B')->setWidth(20);
				$sheet->getColumnDimension('C')->setWidth(30);
				$sheet->getColumnDimension('D')->setWidth(20);
				$sheet->getColumnDimension('E')->setWidth(20);
				$sheet->getColumnDimension('F')->setWidth(20);
				$sheet->getColumnDimension('G')->setWidth(50);
				$sheet->getColumnDimension('H')->setWidth(20);
				$sheet->getColumnDimension('I')->setWidth(50);
				$sheet->getColumnDimension('J')->setWidth(20);
				$sheet->getColumnDimension('K')->setWidth(50);
				$sheet->getColumnDimension('L')->setWidth(20);
				$sheet->getColumnDimension('M')->setWidth(50);
				$sheet->getColumnDimension('N')->setWidth(20);
				$sheet->getColumnDimension('O')->setWidth(50);

			
				$sheet->getRowDimension('1')->setRowHeight(30);
				$sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setBold(true);

				$styleArray = array(
					'borders' => array(
						'top' => array(
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
							'color' => array('argb' => '999999'),
						),
					),
				);

				$outlinestyleArray = array(
					'borders' => array(
						'top' => array(
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
							'color' => array('argb' => '999999'),
						),
						'bottom' => array(
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
							'color' => array('argb' => '999999'),
						)
					),
				);

				$sheet ->getStyle('A1:J1')->applyFromArray($outlinestyleArray);
			

					$sheet->setCellValue('A1', 'S.N');
					$sheet->setCellValue('B1', 'Unit Name');
					$sheet->setCellValue('C1', 'Chapter Name');
					$sheet->setCellValue('D1', 'Total Coverage');
					$sheet->setCellValue('E1', 'Covered');
					$sheet->setCellValue('F1', 'Total Contents');
					$sheet->setCellValue('G1', 'Contents');
					$sheet->setCellValue('H1', 'Total Quizzes');
					$sheet->setCellValue('I1', 'Quizzes');
					$sheet->setCellValue('J1', 'Total Homeworks');
					$sheet->setCellValue('K1', 'Homeworks');
					$sheet->setCellValue('L1', 'Total Classworks');
					$sheet->setCellValue('M1', 'Classworks');
					$sheet->setCellValue('N1', 'Total Assignments');
					$sheet->setCellValue('O1', 'Assignments');

					$rows = 2;
					$j = 1;
					foreach($data as $course_unit){
						$contenthtml = '';
						$quizzeshtml = '';

						$lenght = 0;
						foreach($course_unit->chapters as $chapter) {

							$lenght = max($chapter->content_count,$chapter->quizz_count,$chapter->homework_count);

							$sheet->setCellValue('A' . $rows, $j);
							$sheet->setCellValue('B' . $rows, $chapter->unit);
							$sheet->setCellValue('C' . $rows, $chapter->chapter_name);
							$sheet->setCellValue('D' . $rows, isset($chapter->total_coverage) ? $chapter->total_coverage: 0 );
							$sheet->setCellValue('E' . $rows, isset($chapter->covered) ? $chapter->covered: 0);
							$sheet->setCellValue('F' . $rows, $chapter->content_count?$chapter->content_count:'');
						
								$k = $rows;
								if($chapter->content_count > 0){
									foreach($chapter->contents as $content){ 
										$contenthtml = $content['content_title'].' - '.$content['exists'] . ' out of ' . $content['percentage_coverage'];
										$sheet->setCellValue('G' . $k, $contenthtml);
										$k++;
									}
							    }else{
									$sheet->setCellValue('G' . $k, '-');
								}

								$sheet->setCellValue('H' . $rows, $chapter->quizz_count?$chapter->quizz_count:'');
							
								$l = $rows;
								if($chapter->quizz_count > 0){
									foreach($chapter->quizzes as $quizz){ 
                                        $quizzeshtml = $quizz['quiz_name'].' - '.$quizz['percentage_coverage'].'% - '.$quizz['scored'];
                                        $sheet->setCellValue('I' .$l, $quizzeshtml);
										$l++;
									}
							    }else{
									$sheet->setCellValue('I' . $l, '-');
								}

								$sheet->setCellValue('J' . $rows, $chapter->homework_count?$chapter->homework_count:'');

							    $m = $rows;
								if($chapter->homework_count > 0){
									foreach($chapter->homeworks as $homework){ 
                                        $homeworkhtml = $homework->title.' - '.$homework->date .' - '. $homework->answer_status;
                                        $sheet->setCellValue('K' .$m, $homeworkhtml);
										$m++;
									}
							    }else{
									$sheet->setCellValue('K' . $m, '-');
								}

								$sheet->setCellValue('L' . $rows, $chapter->classwork_count?$chapter->classwork_count:'');

								$n = $rows;
								if($chapter->classwork_count > 0){
									foreach($chapter->classworks as $classwork){ 
                                        $classworkhtml = $classwork->title.' - '.$classwork->date.' - '. $classwork->answer_status;
                                        $sheet->setCellValue('M' .$n, $classworkhtml);
										$n++;
									}
							    }else{
									$sheet->setCellValue('M' . $n, '-');
								}


								$sheet->setCellValue('N' . $rows, $chapter->assignment_count?$chapter->assignment_count:'');

								$o = $rows;
								if($chapter->assignment_count > 0){
									foreach($chapter->assignments as $assignment){ 
                                        $assignmenthtml = $assignment->title.' - '.$assignment->date.' - '. $assignment->answer_status;
                                        $sheet->setCellValue('O' .$o, $assignmenthtml);
										$o++;
									}
							    }else{
									$sheet->setCellValue('O' . $o, '-');
								}
							
						   	$rows = $rows + $lenght;
						
							$sheet ->getStyle('A'.$rows.':O'.$rows)->applyFromArray($styleArray);
							$j++;
							
					}}
						
			

				$sheet->setTitle('Coursereport');
				$i++;

		$spreadsheet->setActiveSheetIndex(0);
		$writer = new Xlsx($spreadsheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function answerStatus($ans_status){
		
		if ($ans_status == "pending") {
			$title = 'submitted';
			$status = 'label-info';
		} elseif ($ans_status == "checked") {
			$title = 'checked';
			$status = 'label-success';
		} elseif ($ans_status == "viewed") {
			$title = 'viewed';
			$status = 'label-primary';
		} else {
			$title = 'not-submitted';
			$status = 'label-default';
		}

		return [$title,$status];

	}


}