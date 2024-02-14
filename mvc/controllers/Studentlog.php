<?php 
use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\DataValidation;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentlog extends Admin_Controller {
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
		$this->load->model("log_m");
        $this->load->model("classes_m");
        $this->load->model("section_m");
        $this->load->model("studentrelation_m");
		$this->db->cache_off();
        $language = $this->session->userdata('lang');
		$this->lang->load('log', $language);
	
	}

    public function index(){

		$this->data['headerassets'] = array(
			'css' => array(
				'assets/datepicker/datepicker.css'
			),
			'js' => array(
				'assets/datepicker/datepicker.js',
			)
		);

	    if ($this->session->userdata('usertypeID') == 1) {
		       

			if ($_POST) {

				$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

				$filters                 = [];
				$filters['schollyearID'] = $this->session->userdata('defaultschoolyearID');
				$filters['classesID']    = $this->input->post('classesID')?$this->input->post('classesID'):'';
				$filters['studentID']    = $studentID =  $this->input->post('studentID')?$studentID =  $this->input->post('studentID'):'';
				$filters['eventID']      = $this->input->post('eventID')?$this->input->post('eventID'):'';
				$filters['startDate']    = $this->input->post('startDate')?date('Y-m-d',strtotime($this->input->post('startDate'))):'';
				$filters['endDate']      = $this->input->post('endDate')?date('Y-m-d',strtotime($this->input->post('endDate'))):'';

				$this->data['filters']   = $filters;
				$this->data['student']   = $student =  $this->student_m->general_get_single_student(['studentID' => $studentID]);
				$this->data['class']     = $this->classes_m->general_get_single_classes(['classesID' => $student->classesID]);
				$this->data['section']   = $this->section_m->general_get_single_section(['sectionID' => $student->sectionID]);
				$this->data['logs']      = $this->log_m->get_order_by_course_logs($filters,20,$page);

				$retArray['template']    = $this->load->view('studentlog/courselog', $this->data, true);
				$retArray['status']      = TRUE;
				echo json_encode($retArray);
				exit();

			}
		
			$classes               = $this->classes_m->get_classes();
			$this->data['classes'] = $classes;
			$this->data["subview"] = "studentlog/index";
			$this->load->view('_layout_main', $this->data);

		}else{

			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);

		}

    }

	public function getMoreStudentLogs(){

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $page         = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$filters['schoolyearID'] = $schoolyearID;
        $filters['classesID']    = $this->input->get('classesID')?$this->input->get('classesID'):'';
        $filters['studentID']    = $studentID =  $this->input->get('studentID')?$studentID =  $this->input->get('studentID'):'';
        $filters['eventID']      = $this->input->get('eventID')?$this->input->get('eventID'):'';
        $filters['startDate']    = $this->input->get('startDate')?date('Y-m-d',strtotime($this->input->get('startDate'))):'';
        $filters['endDate']      = $this->input->get('endDate')?date('Y-m-d',strtotime($this->input->get('endDate'))):'';

        $this->data['student']   = $student =  $this->student_m->general_get_single_student(['studentID' => $studentID, 'schoolyearID' => $schoolyearID ]);
        $this->data['class']     = $this->classes_m->general_get_single_classes(['classesID' => $student->classesID]);
        $this->data['section']   = $this->section_m->general_get_single_section(['sectionID' => $student->sectionID]);
        $this->data['logs']      = $this->log_m->get_order_by_course_logs($filters,20,$page);
		$this->data['page']      = $page;

        $retArray['template']    = $this->load->view('studentlog/morelogs', $this->data, true);
        $retArray['status']      = TRUE;
        echo json_encode($retArray);
        exit();

    }

	public function allStudentLogs(){

        $filters                 = [];
		$filters['studentID']    = '';
        $filters['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
		$filters['eventID']      = $this->input->post('eventID')?$this->input->post('eventID'):'';
		$filters['startDate']    = $this->input->post('startDate')?date('Y-m-d',strtotime($this->input->post('startDate'))):'';
		$filters['endDate']      = $this->input->post('endDate')?date('Y-m-d',strtotime($this->input->post('endDate'))):'';
	   
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$this->data['filters'] = $filters;
		$this->data['logs']    = $this->log_m->getSum($filters,20,$page);
		$retArray['template']  = $this->load->view('studentlog/allstudentlog', $this->data, true);
		$retArray['status']    = TRUE;
		echo json_encode($retArray);
		exit();

	}

	public function getMoreAllStudentLogs(){

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $page         = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

		$filters['schoolyearID'] = $schoolyearID;
        $filters['eventID']      = $this->input->get('eventID')?$this->input->get('eventID'):'';
        $filters['startDate']    = $this->input->get('startDate')?date('Y-m-d',strtotime($this->input->get('startDate'))):'';
        $filters['endDate']      = $this->input->get('endDate')?date('Y-m-d',strtotime($this->input->get('endDate'))):'';

        $this->data['logs']      = $this->log_m->getSum($filters,20,$page);
		$this->data['page']      = $page;

        $retArray['template']    = $this->load->view('studentlog/allstudentmorelog', $this->data, true);
        $retArray['status']      = TRUE;
        echo json_encode($retArray);
        exit();

    }

    public function getStudents(){

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $classesID    = $this->input->post('classesID');
       
		echo "<option value='0'>", "-- Select student --", "</option>";
		if ((int)$classesID) {
            $students = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $classesID,'srschoolyearID' => $schoolyearID));
            if (customCompute($students)) {
				foreach ($students as $student) {
                    // $selected = $studentID == $student->studentID?'selected':'';
					echo "<option value=" . $student->studentID . ">" . $student->name . "</option>";
				}
			}
		}

    }


    public function exportExcel()
	{

		$filters['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
		$filters['studentID']    = $this->input->get('studentID');
		$filters['eventID']      = $this->input->get('eventID');
        $filters['startDate']    = $this->input->get('startDate');
        $filters['endDate']      = $this->input->get('endDate');

		if($filters['studentID'] != ''){
			$this->data['student'] = $student =  $this->student_m->general_get_single_student(['studentID' => $filters['studentID']]);
			$this->data['class']   = $class   =  $this->classes_m->general_get_single_classes(['classesID' => $student->classesID]);
			$this->data['section'] = $section =  $this->section_m->general_get_single_section(['sectionID' => $student->sectionID]);
			$filename = $student->name.'-'.$class->classes.'-'.$section->section;
		}else{
			$filename              = 'student-log';
		}

		$newArray = [];
		if($filters['studentID'] == ''){
			$logs = $this->log_m->getSum($filters);
			if ($logs) {
				$newArray['logs'] = $logs;
			}
		    return $this->generateXMLForAllStudent($newArray, $filename);
		}else{
			$logs = $this->log_m->get_order_by_course_logs($filters);
			if ($logs) {
				$newArray['logs'] = $logs;
			}
		    return $this->generateXML($newArray, $filename);
		}

		
	}
	

	private function generateXMLForAllStudent($data, $filename = 'filename')
	{

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$length = count($data);
		$i = 0;
		foreach ($data as $key => $value) {

			if ($i < $length) {

				$sheet = $spreadsheet->createSheet($i); //Setting index when creating

				$sheet->getColumnDimension('A')->setWidth(5);
				$sheet->getColumnDimension('B')->setWidth(20);
				$sheet->getColumnDimension('C')->setWidth(50);
				$sheet->getColumnDimension('D')->setWidth(30);

				$sheet->getRowDimension('1')->setRowHeight(30);
				$sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setBold(true);


				if ($key == 'logs') {

					$sheet->setCellValue('A1', 'S.N');
					$sheet->setCellValue('B1', 'Student');
					$sheet->setCellValue('C1', 'Event');
					$sheet->setCellValue('D1', 'Second Spent');

					$rows = 2;
					$j = 1;
					$total = 0;
					foreach ($value as $values) {
						foreach ($values as $val) {
							$sheet->setCellValue('A' . $rows, $j);
							$sheet->setCellValue('B' . $rows, $val->name);
							$sheet->setCellValue('C' . $rows, $val->event);
							$sheet->setCellValue('D' . $rows, $val->second_spent);
							$rows++;
							$j++;
							$total = $total + $val->second_spent;
						}
					}
					$sheet->setCellValue('C' . $rows, 'Total');
					$sheet->setCellValue('D' . $rows, $total);
				}

				$sheet->setTitle($key);
				$i++;
			}
		}

		$spreadsheet->setActiveSheetIndex(0);
		$writer = new Xlsx($spreadsheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	private function generateXML($data, $filename = 'filename')
	{

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$length = count($data);
		$i = 0;
		foreach ($data as $key => $value) {

			if ($i < $length) {

				$sheet = $spreadsheet->createSheet($i); //Setting index when creating

				$sheet->getColumnDimension('A')->setWidth(5);
				$sheet->getColumnDimension('B')->setWidth(20);
				$sheet->getColumnDimension('C')->setWidth(30);
				$sheet->getColumnDimension('D')->setWidth(100);
				$sheet->getColumnDimension('E')->setWidth(20);
				$sheet->getColumnDimension('F')->setWidth(20);
				$sheet->getColumnDimension('G')->setWidth(40);
				$sheet->getColumnDimension('H')->setWidth(20);

				$sheet->getRowDimension('1')->setRowHeight(30);
				$sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->getFont()->setBold(true);


				if ($key == 'logs') {

					$sheet->setCellValue('A1', 'S.N');
					$sheet->setCellValue('B1', 'Name');
					$sheet->setCellValue('C1', 'Event');
					$sheet->setCellValue('D1', 'Remarks');
					$sheet->setCellValue('E1', 'Start Datetime');
					$sheet->setCellValue('F1', 'End Datetime');
					$sheet->setCellValue('G1', 'Total Time Spent');
					$sheet->setCellValue('H1', 'Second Spent');

					$rows = 2;
					$j = 1;
					$total = 0;
					foreach ($value as $val) {
						$sheet->setCellValue('A' . $rows, $j);
						$sheet->setCellValue('B' . $rows, $val->name);
						$sheet->setCellValue('C' . $rows, $val->event);
						$sheet->setCellValue('D' . $rows, $val->remarks);
						$sheet->setCellValue('E' . $rows, $val->start_datetime);
						$sheet->setCellValue('F' . $rows, $val->end_datetime);
						$sheet->setCellValue('G' . $rows, $val->time_spent);
						$sheet->setCellValue('H' . $rows, $val->second_spent);
						$rows++;
						$j++;
						$total = $total + $val->second_spent;
					}
						$sheet->setCellValue('G' . $rows, 'Total');
						$sheet->setCellValue('H' . $rows, $total);
				}

				$sheet->setTitle($key);
				$i++;
			}
		}

		$spreadsheet->setActiveSheetIndex(0);
		$writer = new Xlsx($spreadsheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}


	public function PDF()
	{

		$filters['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
		$filters['studentID']    = $this->input->get('studentID');
		$filters['eventID']      = $this->input->get('eventID');
        $filters['startDate']    = $this->input->get('startDate');
        $filters['endDate']      = $this->input->get('endDate');
		
		if($filters['studentID'] == ''){
			$logs = $this->log_m->getSum($filters);
			$template = 'studentlog/alllogpdf';
		}else{
			$logs = $this->log_m->get_order_by_course_logs($filters);
			$template = 'studentlog/logPDF';
		}

		if($filters['studentID'] != ''){
			$this->data['student']  = $student =  $this->student_m->general_get_single_student(['studentID' => $filters['studentID']]);
			$this->data['class']    = $class =  $this->classes_m->general_get_single_classes(['classesID' => $student->classesID]);
			$this->data['section']  = $section =  $this->section_m->general_get_single_section(['sectionID' => $student->sectionID]);
			$this->data['filename'] = $student->name.'-'.$class->classes.'-'.$section->section;
		}else{
			$this->data['filename'] = 'student-log';
		}

		$this->data['logs'] = $logs;

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


}