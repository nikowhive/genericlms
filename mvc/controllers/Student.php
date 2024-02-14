<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use \PhpOffice\PhpSpreadsheet\Style\Border;

class Student extends Admin_Controller
{
	/*
| -----------------------------------------------------
| PRODUCT NAME:     INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:            INILABS TEAM
| -----------------------------------------------------
| EMAIL:            info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:        RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:            http://inilabs.net
| -----------------------------------------------------
 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model("section_m");
		$this->load->model("classes_m");
		$this->load->model("setting_m");
		$this->load->model('studentrelation_m');
		$this->load->model('studentgroup_m');
		$this->load->model('studentextend_m');
		$this->load->model('subject_m');
		$this->load->model('routine_m');
		$this->load->model('teacher_m');
		$this->load->model("teachersection_m");
		$this->load->model('subjectattendance_m');
		$this->load->model('sattendance_m');
		$this->load->model('invoice_m');
		$this->load->model('payment_m');
		$this->load->model('weaverandfine_m');
		$this->load->model('feetypes_m');
		$this->load->model('exam_m');
		$this->load->model('grade_m');
		$this->load->model('markpercentage_m');
		$this->load->model('markrelation_m');
		$this->load->model('mark_m');
		$this->load->model('document_m');
		$this->load->model('leaveapplication_m');
		$this->load->model('marksetting_m');
		$this->load->model('schoolyear_m');
		$this->load->helper('nepali_calendar_helper');
		$this->load->model('subjectteacher_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('student', $language);
	}

	public function send_mail_rules()
	{
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("student_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean',
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("student_subject"),
				'rules' => 'trim|required|xss_clean',
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("student_message"),
				'rules' => 'trim|xss_clean',
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("student_studentID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data',
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("student_classesID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data',
			),
		);
		return $rules;
	}

	private function getView($id, $url)
	{
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		$fetchClasses = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
		if (isset($fetchClasses[$url])) {
			if ((int) $id && (int) $url) {
				$studentInfo = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID), true);

				$this->pluckInfo();
				$this->basicInfo($studentInfo);
				$this->parentInfo($studentInfo);
				$this->routineInfo($studentInfo);
				$this->attendanceInfo($studentInfo);
				$this->markInfo($studentInfo);
				$this->invoiceInfo($studentInfo);
				$this->paymentInfo($studentInfo);
				$this->documentInfo($studentInfo);

				if (customCompute($studentInfo)) {
					$this->data['set'] = $url;
					$this->data['leaveapplications'] = $this->leave_applications_date_list_by_user_and_schoolyear($id, $schoolyearID, $studentInfo->usertypeID);
					$this->data["subview"] = "student/getView";
					$this->load->view('_layout_main', $this->data);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	private function allPaymentByInvoice($payments)
	{
		$retPaymentArr = [];
		if ($payments) {
			foreach ($payments as $payment) {
				if (isset($retPaymentArr[$payment->invoiceID])) {
					$retPaymentArr[$payment->invoiceID] += $payment->paymentamount;
				} else {
					$retPaymentArr[$payment->invoiceID] = $payment->paymentamount;
				}
			}
		}
		return $retPaymentArr;
	}

	private function allWeaverAndFineByInvoice($weaverandfines)
	{
		$retWeaverAndFineArr = [];
		if ($weaverandfines) {
			foreach ($weaverandfines as $weaverandfine) {
				if (isset($retWeaverAndFineArr[$weaverandfine->invoiceID]['weaver'])) {
					$retWeaverAndFineArr[$weaverandfine->invoiceID]['weaver'] += $weaverandfine->weaver;
				} else {
					$retWeaverAndFineArr[$weaverandfine->invoiceID]['weaver'] = $weaverandfine->weaver;
				}

				if (isset($retWeaverAndFineArr[$weaverandfine->invoiceID]['fine'])) {
					$retWeaverAndFineArr[$weaverandfine->invoiceID]['fine'] += $weaverandfine->fine;
				} else {
					$retWeaverAndFineArr[$weaverandfine->invoiceID]['fine'] = $weaverandfine->fine;
				}
			}
		}
		return $retWeaverAndFineArr;
	}

	private function getMark($studentID, $classesID)
	{
		if ((int) $studentID && (int) $classesID) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
			$classes = $this->classes_m->get_single_classes(array('classesID' => $classesID));

			if (customCompute($student) && customCompute($classes)) {
				$queryArray = [
					'classesID' => $student->srclassesID,
					'sectionID' => $student->srsectionID,
					'studentID' => $student->srstudentID,
					'schoolyearID' => $schoolyearID,
				];

				$exams = pluck($this->exam_m->get_exam(), 'exam', 'examID');
				$grades = $this->grade_m->get_grade();
				$marks = $this->mark_m->student_all_mark_array($queryArray);
				$markpercentages = $this->markpercentage_m->get_markpercentage();

				$subjects = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
				$subjectArr = [];
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
				$highestMarks = [];
				foreach ($allStudentMarks as $value) {
					if (!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
						$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
					}
					$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
				}
				$marksettings = $this->marksetting_m->get_marksetting_markpercentages();

				$this->data['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;
				$this->data['subjects'] = $subjectArr;
				$this->data['exams'] = $exams;
				$this->data['grades'] = $grades;
				$this->data['markpercentages'] = pluck($markpercentages, 'obj', 'markpercentageID');
				$this->data['optionalsubjectArr'] = $optionalsubjectArr;
				$this->data['marks'] = $retMark;
				$this->data['highestmarks'] = $highestMarks;
				$this->data['marksettings'] = isset($marksettings[$classesID]) ? $marksettings[$classesID] : [];
			} else {
				$this->data['settingmarktypeID'] = 0;
				$this->data['subjects'] = [];
				$this->data['exams'] = [];
				$this->data['grades'] = [];
				$this->data['markpercentages'] = [];
				$this->data['optionalsubjectArr'] = [];
				$this->data['marks'] = [];
				$this->data['highestmarks'] = [];
				$this->data['marksettings'] = [];
			}
		} else {
			$this->data['settingmarktypeID'] = 0;
			$this->data['subjects'] = [];
			$this->data['exams'] = [];
			$this->data['grades'] = [];
			$this->data['markpercentages'] = [];
			$this->data['optionalsubjectArr'] = [];
			$this->data['marks'] = [];
			$this->data['highestmarks'] = [];
			$this->data['marksettings'] = [];
		}
	}

	private function pluckInfo()
	{
		$this->data['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
		$this->data['teachers'] = pluck($this->teacher_m->get_teacher(), 'name', 'teacherID');
		$this->data['feetypes'] = pluck($this->feetypes_m->get_feetypes(), 'feetypes', 'feetypesID');
	}

	private function basicInfo($studentInfo)
	{
		if (customCompute($studentInfo)) {
			$this->data['profile'] = $studentInfo;
			$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => 3));
			$this->data['class'] = $this->classes_m->get_single_classes(array('classesID' => $studentInfo->srclassesID));
			$this->data['section'] = $this->section_m->general_get_single_section(array('sectionID' => $studentInfo->srsectionID));
			$this->data['group'] = $this->studentgroup_m->get_single_studentgroup(array('studentgroupID' => $studentInfo->srstudentgroupID));
			$this->data['optionalsubject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $studentInfo->sroptionalsubjectID));
			$this->data['anotheroptionalsubject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $studentInfo->sranotheroptionalsubjectID));
		} else {
			$this->data['profile'] = [];
		}
	}

	private function parentInfo($studentInfo)
	{
		if (customCompute($studentInfo)) {
			$this->data['parents'] = $this->parents_m->get_single_parents(array('parentsID' => $studentInfo->parentID));
		} else {
			$this->data['parents'] = [];
		}
	}

	private function routineInfo($studentInfo)
	{
		$settingWeekends = [];
		if ($this->data['siteinfos']->weekends != '') {
			$settingWeekends = explode(',', $this->data['siteinfos']->weekends);
		}
		$this->data['routineweekends'] = $settingWeekends;

		$this->data['routines'] = [];
		if (customCompute($studentInfo)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['routines'] = pluck_multi_array($this->routine_m->get_order_by_routine(array('classesID' => $studentInfo->srclassesID, 'sectionID' => $studentInfo->srsectionID, 'schoolyearID' => $schoolyearID)), 'obj', 'day');
		}
	}

	private function attendanceInfo($studentInfo)
	{
		$this->data['holidays'] = $this->getHolidaysSession();
		$this->data['getWeekendDays'] = $this->getWeekendDaysSession();
		if (customCompute($studentInfo)) {
			$this->data['setting'] = $this->setting_m->get_setting();
			if ($this->data['setting']->attendance == "subject") {
				$this->data["attendancesubjects"] = $this->subject_m->general_get_order_by_subject(array("classesID" => $studentInfo->srclassesID));
			}

			if ($this->data['setting']->attendance == "subject") {
				$attendances = $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $studentInfo->srstudentID, "classesID" => $studentInfo->srclassesID));
				$this->data['attendances_subjectwisess'] = pluck_multi_array_key($attendances, 'obj', 'subjectID', 'monthyear');
			} else {
				$attendances = $this->sattendance_m->get_order_by_attendance(array("studentID" => $studentInfo->srstudentID, "classesID" => $studentInfo->srclassesID));
				$this->data['attendancesArray'] = pluck($attendances, 'obj', 'monthyear');
			}
		} else {
			$this->data['setting'] = [];
			$this->data['attendancesubjects'] = [];
			$this->data['attendances_subjectwisess'] = [];
			$this->data['attendancesArray'] = [];
		}
	}

	private function markInfo($studentInfo)
	{
		if (customCompute($studentInfo)) {
			$this->getMark($studentInfo->srstudentID, $studentInfo->srclassesID);
		} else {
			$this->data['set'] = [];
			$this->data["exams"] = [];
			$this->data["grades"] = [];
			$this->data['markpercentages'] = [];
			$this->data['validExam'] = [];
			$this->data['separatedMarks'] = [];
			$this->data["highestMarks"] = [];
			$this->data["section"] = [];
		}
	}

	private function invoiceInfo($studentInfo)
	{
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if (customCompute($studentInfo)) {
			$this->data['invoices'] = $this->invoice_m->get_order_by_invoice(array('schoolyearID' => $schoolyearID, 'studentID' => $studentInfo->srstudentID, 'deleted_at' => 1));

			$payments = $this->payment_m->get_order_by_payment(array('schoolyearID' => $schoolyearID, 'studentID' => $studentInfo->srstudentID));
			$weaverandfines = $this->weaverandfine_m->get_order_by_weaverandfine(array('schoolyearID' => $schoolyearID, 'studentID' => $studentInfo->srstudentID));

			$this->data['allpaymentbyinvoice'] = $this->allPaymentByInvoice($payments);
			$this->data['allweaverandpaymentbyinvoice'] = $this->allWeaverAndFineByInvoice($weaverandfines);
		} else {
			$this->data['invoices'] = [];
			$this->data['allpaymentbyinvoice'] = [];
			$this->data['allweaverandpaymentbyinvoice'] = [];
		}
	}

	private function paymentInfo($studentInfo)
	{
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if (customCompute($studentInfo)) {
			$this->data['payments'] = $this->payment_m->get_payment_with_studentrelation_by_studentID_and_schoolyearID($studentInfo->srstudentID, $schoolyearID);
		} else {
			$this->data['payments'] = [];
		}
	}

	protected function rules_documentupload()
	{
		$rules = array(
			array(
				'field' => 'title',
				'label' => $this->lang->line("student_name"),
				'rules' => 'trim|required|xss_clean|max_length[128]',
			),
			array(
				'field' => 'file',
				'label' => $this->lang->line("student_file"),
				'rules' => 'trim|xss_clean|max_length[200]|callback_unique_document_upload',
			),
		);

		return $rules;
	}

	public function unique_document_upload()
	{
		$new_file = '';
		if ($_FILES["file"]['name'] != "") {
			$file_name = $_FILES["file"]['name'];
			$random = random19();
			$makeRandom = hash('sha512', $random . (strtotime(date('Y-m-d H:i:s'))) . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
			$explode = explode('.', $file_name);
			if (customCompute($explode) >= 2) {
				$new_file = $file_name_rename . '.' . end($explode);
				$config['upload_path'] = "./uploads/documents";
				$config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
				$config['file_name'] = $new_file;
				// $config['max_size'] = '5120';
				// $config['max_width'] = '10000';
				// $config['max_height'] = '10000';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload("file")) {
					$this->form_validation->set_message("unique_document_upload", $this->upload->display_errors());
					return false;
				} else {
					$this->upload_data['file'] = $this->upload->data();
					return true;
				}
			} else {
				$this->form_validation->set_message("unique_document_upload", "Invalid file");
				return false;
			}
		} else {
			$this->form_validation->set_message("unique_document_upload", "The file is required.");
			return false;
		}
	}

	public function documentUpload()
	{
		$retArray['status'] = false;
		$retArray['render'] = '';

		if (permissionChecker('student_add')) {
			if ($_POST) {
				$rules = $this->rules_documentupload();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == false) {
					$retArray['errors'] = $this->form_validation->error_array();
					$retArray['status'] = false;
					echo json_encode($retArray);
					exit;
				} else {
					$title = $this->input->post('title');
					$file = $this->upload_data['file']['file_name'];
					$userID = $this->input->post('studentID');

					$array = array(
						'title' => $title,
						'file' => $file,
						'userID' => $userID,
						'usertypeID' => 3,
						"create_date" => date("Y-m-d H:i:s"),
						"create_userID" => $this->session->userdata('loginuserID'),
						"create_usertypeID" => $this->session->userdata('usertypeID'),
					);

					$this->document_m->insert_document($array);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));

					$retArray['status'] = true;
					$retArray['render'] = 'Success';
					echo json_encode($retArray);
					exit;
				}
			} else {
				$retArray['status'] = false;
				$retArray['render'] = 'Error';
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['status'] = false;
			$retArray['render'] = 'Permission Denay.';
			echo json_encode($retArray);
			exit;
		}
	}

	private function documentInfo($studentInfo)
	{
		if (customCompute($studentInfo)) {
			$this->data['documents'] = $this->document_m->get_order_by_document(array('usertypeID' => 3, 'userID' => $studentInfo->srstudentID));
		} else {
			$this->data['documents'] = [];
		}
	}

	public function download_document()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$studentID = htmlentities(escapeString($this->uri->segment(4)));
		$classesID = htmlentities(escapeString($this->uri->segment(5)));
		if ((int) $id && (int) $studentID && (int) $classesID) {
			if ((permissionChecker('student_add') && permissionChecker('student_delete')) || ($this->session->userdata('usertypeID') == 3 && $this->session->userdata('loginuserID') == $studentID)) {
				$document = $this->document_m->get_single_document(array('documentID' => $id));
				if (customCompute($document)) {
					$file = realpath('uploads/documents/' . $document->file);
					if (file_exists($file)) {
						$expFileName = explode('.', $file);
						$originalname = ($document->title) . '.' . end($expFileName);
						header('Content-Description: File Transfer');
						header('Content-Type: application/octet-stream');
						header('Content-Disposition: attachment; filename="' . basename($originalname) . '"');
						header('Expires: 0');
						header('Cache-Control: must-revalidate');
						header('Pragma: public');
						header('Content-Length: ' . filesize($file));
						readfile($file);
						exit;
					} else {
						redirect(base_url('student/view/' . $studentID . '/' . $classesID));
					}
				} else {
					redirect(base_url('student/view/' . $studentID . '/' . $classesID));
				}
			} else {
				redirect(base_url('student/view/' . $studentID . '/' . $classesID));
			}
		} else {
			redirect(base_url('student/student'));
		}
	}

	public function delete_document()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$studentID = htmlentities(escapeString($this->uri->segment(4)));
		$classesID = htmlentities(escapeString($this->uri->segment(5)));
		if ((int) $id && (int) $studentID && (int) $classesID) {
			if (permissionChecker('student_add') && permissionChecker('student_delete')) {
				$document = $this->document_m->get_single_document(array('documentID' => $id));
				if (customCompute($document)) {
					if (config_item('demo') == false) {
						if (file_exists(FCPATH . 'uploads/document/' . $document->file)) {
							unlink(FCPATH . 'uploads/document/' . $document->file);
						}
					}

					$this->document_m->delete_document($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url('student/view/' . $studentID . '/' . $classesID));
				} else {
					redirect(base_url('student/view/' . $studentID . '/' . $classesID));
				}
			} else {
				redirect(base_url('student/view/' . $studentID . '/' . $classesID));
			}
		} else {
			redirect(base_url('student/student'));
		}
	}

	protected function rules()
	{
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("student_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]',
			),
			array(
				'field' => 'dob',
				'label' => $this->lang->line("student_dob"),
				'rules' => 'trim|max_length[10]|callback_date_valid|xss_clean',
			),
			array(
				'field' => 'sex',
				'label' => $this->lang->line("student_sex"),
				'rules' => 'trim|required|max_length[10]|xss_clean',
			),
			array(
				'field' => 'bloodgroup',
				'label' => $this->lang->line("student_bloodgroup"),
				'rules' => 'trim|max_length[5]|xss_clean',
			),
			array(
				'field' => 'religion',
				'label' => $this->lang->line("student_religion"),
				'rules' => 'trim|max_length[25]|xss_clean',
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("student_email"),
				'rules' => 'trim|max_length[40]|valid_email|xss_clean|callback_unique_email',
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("student_phone"),
				'rules' => 'trim|max_length[25]|min_length[5]|xss_clean',
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("student_address"),
				'rules' => 'trim|max_length[200]|xss_clean',
			),
			array(
				'field' => 'state',
				'label' => $this->lang->line("student_state"),
				'rules' => 'trim|max_length[128]|xss_clean',
			),
			array(
				'field' => 'country',
				'label' => $this->lang->line("student_country"),
				'rules' => 'trim|max_length[128]|xss_clean',
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("student_classes"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_classesID',
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("student_section"),
				'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_sectionID|callback_unique_capacity',
			),
			array(
				'field' => 'registerNO',
				'label' => $this->lang->line("student_registerNO"),
				'rules' => 'trim|required|max_length[40]|callback_unique_registerNO|xss_clean',
			),
			array(
				'field' => 'roll',
				'label' => $this->lang->line("student_roll"),
				'rules' => 'trim|required|max_length[11]|numeric|callback_unique_roll|xss_clean',
			),
			array(
				'field' => 'guargianID',
				'label' => $this->lang->line("student_guargian"),
				'rules' => 'trim|required|max_length[11]|xss_clean|numeric',
			),
			array(
				'field' => 'photo',
				'label' => $this->lang->line("student_photo"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload',
			),

			array(
				'field' => 'studentGroupID',
				'label' => $this->lang->line("student_studentgroup"),
				'rules' => 'trim|max_length[11]|xss_clean|numeric',
			),

			array(
				'field' => 'optionalSubjectID',
				'label' => $this->lang->line("student_optionalsubject"),
				'rules' => 'trim|max_length[11]|xss_clean|numeric|callback_unique_optionalSubjectID',
			),

			array(
				'field' => 'anotheroptionalSubjectID',
				'label' => $this->lang->line("student_anotheroptionalsubject"),
				'rules' => 'trim|max_length[11]|xss_clean|numeric|callback_unique_anotheroptionalSubjectID',
			),

			array(
				'field' => 'extraCurricularActivities',
				'label' => $this->lang->line("student_extracurricularactivities"),
				'rules' => 'trim|max_length[128]|xss_clean',
			),

			array(
				'field' => 'remarks',
				'label' => $this->lang->line("student_remarks"),
				'rules' => 'trim|max_length[128]|xss_clean',
			),

			array(
				'field' => 'username',
				'label' => $this->lang->line("student_username"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean|callback_lol_username',
			),
			array(
				'field' => 'password',
				'label' => $this->lang->line("student_password"),
				'rules' => 'trim|required|min_length[4]|max_length[40]|xss_clean',
			),
		);
		return $rules;
	}

	public function photoupload()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$student = array();
		if ((int) $id) {
			$student = $this->student_m->general_get_single_student(array('studentID' => $id));
		}

		$new_file = "default.png";
		if ($_FILES["photo"]['name'] != "") {
			$file_name = $_FILES["photo"]['name'];
			$uploadPath = 'uploads/images';
			$random = random19();
			$makeRandom = hash('sha512', $random . $this->input->post('username') . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
			$explode = explode('.', $file_name);
			if (customCompute($explode) >= 2) {
				$new_file = $file_name_rename . '.' . end($explode);
				$config['upload_path'] = "./uploads/images";
				$config['allowed_types'] = "gif|jpg|png";
				$config['file_name'] = $new_file;
				$_FILES['attach']['tmp_name'] = $_FILES['photo']['tmp_name'];
				$image_info = getimagesize($_FILES['photo']['tmp_name']);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
				// $config['max_size'] = '5120';
				// $config['max_width'] = '3000';
				// $config['max_height'] = '3000';
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload("photo")) {
					$this->form_validation->set_message("photoupload", $this->upload->display_errors());
					return false;
				} else {
					$fileData = $this->upload->data();
					
					resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height);
  
					$this->upload_data['file'] = $this->upload->data();

					return true;
				}
			} else {
				$this->form_validation->set_message("photoupload", "Invalid file");
				return false;
			}
		} else {
			if (customCompute($student)) {
				$this->upload_data['file'] = array('file_name' => $student->photo);
				return true;
			} else {
				$this->upload_data['file'] = array('file_name' => $new_file);
				return true;
			}
		}
	}

	public function student()
	{
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
			),
			'js' => array(
				'assets/select2/select2.js',
			),
		);

		$myProfile = false;
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if ($this->session->userdata('usertypeID') == 3) {
			$id = $this->data['myclass'];
			if (!permissionChecker('student_view')) {
				$myProfile = true;
			}
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
		}

		if ($this->session->userdata('usertypeID') == 3 && $myProfile) {
			$url = $id;
			$id = $this->session->userdata('loginuserID');
			$this->getView($id, $url);
		} else {
			$this->data['set'] = $id;
			$this->data['classes'] = $this->classes_m->get_classes();

			if ((int) $id) {
				$this->data['students'] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, 'srschoolyearID' => $schoolyearID));
				if (customCompute($this->data['students'])) {
					$sections = $this->section_m->general_get_order_by_section(array("classesID" => $id));
					$this->data['sections'] = $sections;
					foreach ($sections as $key => $section) {
						$this->data['allsection'][$section->sectionID] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, "srsectionID" => $section->sectionID, 'srschoolyearID' => $schoolyearID));
					}
				} else {
					$this->data['students'] = [];
				}
			} else {
				$this->data['students'] = [];
			}
			$this->data["subview"] = "student/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function convertAdToBs($date)
	{
		$dateObj = new NepaliCalenderHelper();
		$nepaliDate = $dateObj->convertDateToNepaliInEnglish($date);
		return $nepaliDate['year'];
	}

	public function getStudentRegisterNumber($schoolYear)
	{

		$number = $this->student_m->get_yearwise_total_students($schoolYear->schoolyearID);
		$endingyear =  date('Y-m-d', strtotime($schoolYear->endingdate));
		$endingyearNepali = $this->convertAdToBs($endingyear);
		if ($number == 0) {
			return $endingyearNepali . '00001';
		} else {
			return $this->checkRegisterNumber($number, $endingyearNepali, $schoolYear->schoolyearID);
		}
	}

	public function checkRegisterNumber($number, $endingyearNepali, $schoolyearID)
	{
		$number = $number + 1;
		$n = sprintf("%05d", $number);
		$rn = $endingyearNepali . $n;
		$student = $this->student_m->get_student_by_register_number(['registerNO' => $rn, 'schoolyearID' => $schoolyearID]);
		if ($student) {
			return $this->checkRegisterNumber($number, $endingyearNepali, $schoolyearID);
		}
		return $rn;
	}

	public function checkRollNumber($number, $array)
	{
		$number = $number + 1;
		$array['roll'] = $number;
		$student = $this->student_m->get_student_by_roll_number($array);
		if ($student) {
			return $this->checkRollNumber($number, $array);
		}
		return $number;
	}


	public function getUniqueClassAndYearwiseStudentRollNUmber($schoolYear, $sectionID)
	{
		$array = [
			'schoolyearID' => $schoolYear->schoolyearID,
			'sectionID'    => $sectionID,
		];
		$number = $this->student_m->get_yearwiseandsectionwise_total_students($array);
		if ($number == 0) {
			return 1;
		} else {
			return $this->checkRollNumber($number, $array);
		}
	}


	public function autoIncrementRoll()
	{
		$sectionID = $this->input->post('id');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$schoolYear = $this->schoolyear_m->get_obj_schoolyear($schoolyearID);
		$roll = $this->getUniqueClassAndYearwiseStudentRollNUmber($schoolYear, $sectionID);
		echo  $roll;
	}


	public function add()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
				),
				'js' => array(
					'assets/datepicker/datepicker.js',
					'assets/select2/select2.js',
				),
			);

			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$schoolYear = $this->schoolyear_m->get_obj_schoolyear($schoolyearID);
			$this->data['registerNumber'] = $this->getStudentRegisterNumber($schoolYear);
			$this->data['students'] = $this->studentrelation_m->general_get_student();
			$this->data['classes'] = $this->classes_m->get_classes();
			$this->data['sections'] = $this->section_m->general_get_section();
			$this->data['parents'] = $this->parents_m->get_parents();
			$this->data['studentgroups'] = $this->studentgroup_m->get_studentgroup();

			$classesID = $this->input->post("classesID");

			if ($classesID > 0) {
				$this->data['sections'] = $this->section_m->general_get_order_by_section(array("classesID" => $classesID));
				$this->data['optionalSubjects'] = $this->subject_m->general_get_order_by_subject(array("classesID" => $classesID, 'type' => 0));
			} else {
				$this->data['sections'] = [];
				$this->data['optionalSubjects'] = [];
			}

			$this->data['sectionID'] = $this->input->post("sectionID");
			$this->data['optionalSubjectID'] = 0;
			$this->data['anotheroptionalSubjectID'] = 0;

			if ($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == false) {
					$this->data["subview"] = "student/add";
					$this->load->view('_layout_main', $this->data);
				} else {

					$sectionID = $this->input->post("sectionID");
					if ($sectionID == 0) {
						$this->data['sectionID'] = 0;
					} else {
						$this->data['sections'] = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
						$this->data['sectionID'] = $this->input->post("sectionID");
					}

					if ($this->input->post('optionalSubjectID')) {
						$this->data['optionalSubjectID'] = $this->input->post('optionalSubjectID');
					} else {
						$this->data['optionalSubjectID'] = 0;
					}

					if ($this->input->post('anotheroptionalSubjectID')) {
						$this->data['anotheroptionalSubjectID'] = $this->input->post('anotheroptionalSubjectID');
					} else {
						$this->data['anotheroptionalSubjectID'] = 0;
					}

					$array["name"] = $this->input->post("name");
					$array["sex"] = $this->input->post("sex");
					$array["religion"] = $this->input->post("religion");
					$array["email"] = $this->input->post("email");
					$array["phone"] = $this->input->post("phone");
					$array["address"] = $this->input->post("address");
					$array["classesID"] = $this->input->post("classesID");
					$array["sectionID"] = $this->input->post("sectionID");
					$array["roll"] = $this->input->post("roll");
					$array["bloodgroup"] = $this->input->post("bloodgroup");
					$array["state"] = $this->input->post("state");
					$array["country"] = $this->input->post("country");
					$array["registerNO"] = $this->input->post("registerNO");
					$array["username"] = $this->input->post("username");
					$array['password'] = $this->student_m->hash($this->input->post("password"));
					$array['usertypeID'] = 3;
					$array['parentID'] = $this->input->post('guargianID');
					$array['library'] = 0;
					$array['hostel'] = 0;
					$array['transport'] = 0;
					$array['createschoolyearID'] = $schoolyearID;
					$array['schoolyearID'] = $schoolyearID;
					$array["create_date"] = date("Y-m-d H:i:s");
					$array["modify_date"] = date("Y-m-d H:i:s");
					$array["create_userID"] = $this->session->userdata('loginuserID');
					$array["create_username"] = $this->session->userdata('username');
					$array["create_usertype"] = $this->session->userdata('usertype');
					$array["active"] = 1;

					if ($this->input->post('dob')) {
						$array["dob"] = date("Y-m-d", strtotime($this->input->post("dob")));
					}
					$array['photo'] = $this->upload_data['file']['file_name'];
					@$this->usercreatemail($this->input->post('email'), $this->input->post('username'), $this->input->post('password'));

					$this->student_m->insert_student($array);
					$studentID = $this->db->insert_id();

					$section = $this->section_m->general_get_section($this->input->post("sectionID"));
					$classes = $this->classes_m->get_classes($this->input->post("classesID"));

					if (customCompute($classes)) {
						$setClasses = $classes->classes;
					} else {
						$setClasses = null;
					}

					if (customCompute($section)) {
						$setSection = $section->section;
					} else {
						$setSection = null;
					}

					$arrayStudentRelation = array(
						'srstudentID' => $studentID,
						'srname' => $this->input->post("name"),
						'srclassesID' => $this->input->post("classesID"),
						'srclasses' => $setClasses,
						'srroll' => $this->input->post("roll"),
						'srregisterNO' => $this->input->post("registerNO"),
						'srsectionID' => $this->input->post("sectionID"),
						'srsection' => $setSection,
						'srstudentgroupID' => $this->input->post('studentGroupID'),
						'sroptionalsubjectID' => $this->input->post('optionalSubjectID'),
						'sranotheroptionalsubjectID' => $this->input->post('anotheroptionalSubjectID'),
						'srschoolyearID' => $schoolyearID,
					);

					$studentExtendArray = array(
						'studentID' => $studentID,
						'studentgroupID' => $this->input->post('studentGroupID'),
						'optionalsubjectID' => $this->input->post('optionalSubjectID'),
						'optionalsubjectID' => $this->input->post('anotheroptionalSubjectID'),
						'extracurricularactivities' => $this->input->post('extraCurricularActivities'),
						'remarks' => $this->input->post('remarks'),
					);

					$this->studentextend_m->insert_studentextend($studentExtendArray);
					$this->studentrelation_m->insert_studentrelation($arrayStudentRelation);

					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("student/index/" . $this->input->post("classesID") . '/' . $this->input->post("sectionID")));
				}
			} else {
				$this->data["subview"] = "student/add";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}


	public function edit()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/datepicker/datepicker.css',
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css',
				),
				'js' => array(
					'assets/datepicker/datepicker.js',
					'assets/select2/select2.js',
				),
			);
			$usertype = $this->session->userdata("usertype");
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentID = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			if ((int) $studentID && (int) $url) {
				$this->data['classes'] = $this->classes_m->get_classes();
				$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID), true);

				$this->data['parents'] = $this->parents_m->get_parents();
				$this->data['studentgroups'] = $this->studentgroup_m->get_studentgroup();

				if (customCompute($this->data['student'])) {
					$classesID = $this->data['student']->srclassesID;
					$sectionID = $this->data['student']->srsectionID;
					$this->data['sections'] = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
					$this->data['optionalSubjects'] = $this->subject_m->general_get_order_by_subject(array("classesID" => $classesID, 'type' => 0));
					if ($this->input->post('optionalSubjectID')) {
						$this->data['optionalSubjectID'] = $this->input->post('optionalSubjectID');
					} else {
						$this->data['optionalSubjectID'] = 0;
					}

					if ($this->input->post('anotheroptionalSubjectID')) {
						$this->data['anotheroptionalSubjectID'] = $this->input->post('anotheroptionalSubjectID');
					} else {
						$this->data['anotheroptionalSubjectID'] = 0;
					}
				}

				$this->data['set'] = $url;
				if (customCompute($this->data['student'])) {
					if ($_POST) {
						$rules = $this->rules();
						unset($rules[22]);
						$this->form_validation->set_rules($rules);
						if ($this->form_validation->run() == false) {
							$this->data["subview"] = "student/edit";
							$this->load->view('_layout_main', $this->data);
						} else {
							$array = array();
							$array["name"] = $this->input->post("name");
							$array["sex"] = $this->input->post("sex");
							$array["religion"] = $this->input->post("religion");
							$array["email"] = $this->input->post("email");
							$array["phone"] = $this->input->post("phone");
							$array["address"] = $this->input->post("address");
							$array["classesID"] = $this->input->post("classesID");
							$array["sectionID"] = $this->input->post("sectionID");
							$array["roll"] = $this->input->post("roll");
							$array["bloodgroup"] = $this->input->post("bloodgroup");
							$array["state"] = $this->input->post("state");
							$array["country"] = $this->input->post("country");
							$array["registerNO"] = $this->input->post("registerNO");
							$array["parentID"] = $this->input->post("guargianID");
							$array["username"] = $this->input->post("username");
							$array["modify_date"] = date("Y-m-d H:i:s");
							$array['photo'] = $this->upload_data['file']['file_name'];

							if ($this->input->post('dob')) {
								$array["dob"] = date("Y-m-d", strtotime($this->input->post("dob")));
							} else {
								$array["dob"] = null;
							}

							$studentReletion = $this->studentrelation_m->general_get_order_by_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID));
							$section = $this->section_m->general_get_section($this->input->post("sectionID"));
							$classes = $this->classes_m->get_classes($this->input->post("classesID"));

							if (customCompute($classes)) {
								$setClasses = $classes->classes;
							} else {
								$setClasses = null;
							}

							if (customCompute($section)) {
								$setSection = $section->section;
							} else {
								$setSection = null;
							}

							if (!customCompute($studentReletion)) {
								$arrayStudentRelation = array(
									'srstudentID' => $studentID,
									'srname' => $this->input->post("name"),
									'srclassesID' => $this->input->post("classesID"),
									'srclasses' => $setClasses,
									'srroll' => $this->input->post("roll"),
									'srregisterNO' => $this->input->post("registerNO"),
									'srsectionID' => $this->input->post("sectionID"),
									'srsection' => $setSection,
									'srstudentgroupID' => $this->input->post("studentGroupID"),
									'sroptionalsubjectID' => $this->input->post("optionalSubjectID"),
									'sranotheroptionalsubjectID' => $this->input->post("anotheroptionalSubjectID"),
									'srschoolyearID' => $schoolyearID,
								);
								$this->studentrelation_m->insert_studentrelation($arrayStudentRelation);
							} else {
								$arrayStudentRelation = array(
									'srname' => $this->input->post("name"),
									'srclassesID' => $this->input->post("classesID"),
									'srclasses' => $setClasses,
									'srroll' => $this->input->post("roll"),
									'srregisterNO' => $this->input->post("registerNO"),
									'srsectionID' => $this->input->post("sectionID"),
									'srsection' => $setSection,
									'srstudentgroupID' => $this->input->post("studentGroupID"),
									'sroptionalsubjectID' => $this->input->post("optionalSubjectID"),
									'sranotheroptionalsubjectID' => $this->input->post("anotheroptionalSubjectID"),
								);
								$this->studentrelation_m->update_studentrelation_with_multicondition($arrayStudentRelation, array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID));
							}

							$studentExtendArray = array(
								'studentgroupID' => $this->input->post('studentGroupID'),
								'optionalsubjectID' => $this->input->post('optionalSubjectID'),
								'anotheroptionalsubjectID' => $this->input->post('anotheroptionalSubjectID'),
								'extracurricularactivities' => $this->input->post('extraCurricularActivities'),
								'remarks' => $this->input->post('remarks'),
							);

							$this->studentextend_m->update_studentextend_by_studentID($studentExtendArray, $studentID);
							$this->student_m->update_student($array, $studentID);
							$this->session->set_flashdata('success', $this->lang->line('menu_success'));
							redirect(base_url("student/index/" . $this->input->post("classesID") . '/' . $this->input->post("sectionID")));
						}
					} else {
						$this->data["subview"] = "student/edit";
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

	public function view()
	{
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css',
			),
			'js' => array(
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
			),
		);

		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$url = htmlentities(escapeString($this->uri->segment(4)));
		$this->getView($id, $url);
	}

	public function print_preview()
	{
		if (permissionChecker('student_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('student') && ($this->session->userdata('loginuserID') == htmlentities(escapeString($this->uri->segment(3)))))) {
			$usertypeID = $this->session->userdata('usertypeID');
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
			$this->data['optionalSubjects'] = pluck($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			if ((int) $id && (int) $url) {
				$this->data["student"] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID), true);
				if (customCompute($this->data["student"])) {
					$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['student']->usertypeID));
					$this->data["class"] = $this->classes_m->general_get_classes($this->data['student']->srclassesID);
					$this->data["section"] = $this->section_m->general_get_section($this->data['student']->srsectionID);
					$this->reportPDF('studentmodule.css', $this->data, 'student/print_preview');
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

	public function send_mail()
	{
		$retArray['status'] = false;
		$retArray['message'] = '';
		if (permissionChecker('student_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('student') && ($this->session->userdata('loginuserID') == $this->input->post('studentID')))) {
			if ($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == false) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = false;
					echo json_encode($retArray);
					exit;
				} else {
					$this->data['studentgroups'] = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');
					$this->data['optionalSubjects'] = pluck($this->subject_m->general_get_order_by_subject(array('type' => 0)), 'subject', 'subjectID');
					$id = $this->input->post('studentID');
					$url = $this->input->post('classesID');
					if ((int) $id && (int) $url) {
						$schoolyearID = $this->session->userdata('defaultschoolyearID');
						$this->data["student"] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID), true);
						if (customCompute($this->data["student"])) {
							$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $this->data['student']->usertypeID));
							$this->data["class"] = $this->classes_m->general_get_single_classes(array('classesID' => $this->data['student']->srclassesID));
							$this->data["section"] = $this->section_m->general_get_single_section(array('sectionID' => $this->data['student']->srsectionID));
							$email = $this->input->post('to');
							$subject = $this->input->post('subject');
							$message = $this->input->post('message');
							$this->reportSendToMail('studentmodule.css', $this->data, 'student/print_preview', $email, $subject, $message);
							$retArray['message'] = "Message";
							$retArray['status'] = true;
							echo json_encode($retArray);
							exit;
						} else {
							$retArray['message'] = $this->lang->line('student_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('student_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('student_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('student_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function delete()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$id = htmlentities(escapeString($this->uri->segment(3)));
			$url = htmlentities(escapeString($this->uri->segment(4)));
			if ((int) $id && (int) $url) {
				$this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));

				if (customCompute($this->data['student'])) {
					if (config_item('demo') == false) {
						if ($this->data['student']->photo != 'default.png' && $this->data['student']->photo != 'defualt.png') {
							if (file_exists(FCPATH . 'uploads/images/' . $this->data['student']->photo)) {
								unlink(FCPATH . 'uploads/images/' . $this->data['student']->photo);
							}
						}
					}
					$this->student_m->delete_student($id);
					$this->studentextend_m->delete_studentextend_by_studentID($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					redirect(base_url("student/index/" . $this->data['student']->srclassesID . '/' . $this->data['student']->srsectionID));
				} else {
					redirect(base_url("student/index"));
				}
			} else {
				redirect(base_url("student/index/" . $this->data['student']->srclassesID . '/' . $this->data['student']->srsectionID));
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function unique_roll()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if ((int) $id) {
			$student = $this->studentrelation_m->general_get_order_by_student(array("srroll" => $this->input->post("roll"), "srstudentID !=" => $id, "srsectionID" => $this->input->post('sectionID'), 'srschoolyearID' => $schoolyearID));
			if (customCompute($student)) {
				$this->form_validation->set_message("unique_roll", "The %s is already exists.");
				return false;
			}
			return true;
		} else {
			$student = $this->studentrelation_m->general_get_order_by_student(
				array(
					"srroll" => $this->input->post("roll"),
					"srsectionID" => $this->input->post('sectionID'),
					'srschoolyearID' => $schoolyearID
				)
			);

			if (customCompute($student)) {
				$this->form_validation->set_message("unique_roll", "The %s is already exists.");
				return false;
			}
			return true;
		}
	}

	public function lol_username()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int) $id) {
			$student = $this->student_m->general_get_single_student(array('studentID' => $id));
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_username($table, array("username" => $this->input->post('username'), "username !=" => $student->username));
				if (customCompute($user)) {
					$this->form_validation->set_message("lol_username", "%s already exists");
					$array['permition'][$i] = 'no';
				} else {
					$array['permition'][$i] = 'yes';
				}
				$i++;
			}
			if (in_array('no', $array['permition'])) {
				return false;
			} else {
				return true;
			}
		} else {
			$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
			$array = array();
			$i = 0;
			foreach ($tables as $table) {
				$user = $this->student_m->get_username($table, array("username" => $this->input->post('username')));
				if (customCompute($user)) {
					$this->form_validation->set_message("lol_username", "%s already exists");
					$array['permition'][$i] = 'no';
				} else {
					$array['permition'][$i] = 'yes';
				}
				$i++;
			}

			if (in_array('no', $array['permition'])) {
				return false;
			} else {
				return true;
			}
		}
	}

	public function date_valid($date)
	{
		if ($date) {
			if (strlen($date) < 10) {
				$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
				return false;
			} else {
				$arr = explode("-", $date);
				$dd = $arr[0];
				$mm = $arr[1];
				$yyyy = $arr[2];
				if (checkdate($mm, $dd, $yyyy)) {
					return true;
				} else {
					$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
					return false;
				}
			}
		}
		return true;
	}

	public function unique_classesID()
	{
		if ($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classesID", "The %s field is required");
			return false;
		}
		return true;
	}

	public function unique_sectionID()
	{
		if ($this->input->post('sectionID') == 0) {
			$this->form_validation->set_message("unique_sectionID", "The %s field is required");
			return false;
		}
		return true;
	}

	public function student_list()
	{
		$classID = $this->input->post('id');
		if ((int) $classID) {
			$string = base_url("student/student/$classID");
			echo $string;
		} else {
			redirect(base_url("student/student"));
		}
	}

	public function unique_email()
	{
		if ($this->input->post('email')) {
			$id = htmlentities(escapeString($this->uri->segment(3)));
			if ((int) $id) {
				$student_info = $this->student_m->general_get_single_student(array('studentID' => $id));
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email'), 'username !=' => $student_info->username));
					if (customCompute($user)) {
						$this->form_validation->set_message("unique_email", "%s already exists");
						$array['permition'][$i] = 'no';
					} else {
						$array['permition'][$i] = 'yes';
					}
					$i++;
				}
				if (in_array('no', $array['permition'])) {
					return false;
				} else {
					return true;
				}
			} else {
				$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');
				$array = array();
				$i = 0;
				foreach ($tables as $table) {
					$user = $this->student_m->get_username($table, array("email" => $this->input->post('email')));
					if (customCompute($user)) {
						$this->form_validation->set_message("unique_email", "%s already exists");
						$array['permition'][$i] = 'no';
					} else {
						$array['permition'][$i] = 'yes';
					}
					$i++;
				}

				if (in_array('no', $array['permition'])) {
					return false;
				} else {
					return true;
				}
			}
		}
		return true;
	}

	public function sectioncall()
	{
		$classesID = $this->input->post('id');
		if ((int) $classesID) {
			$allsection = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("student_select_section"), "</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">", $value->section, "</option>";
			}
		}
	}

	public function optionalsubjectcall()
	{
		$classesID = $this->input->post('id');
		if ((int) $classesID) {
			$allOptionalSubjects = $this->subject_m->general_get_order_by_subject(array("classesID" => $classesID, 'type' => 0));
			echo "<option value='0'>", $this->lang->line("student_select_optionalsubject"), "</option>";
			foreach ($allOptionalSubjects as $value) {
				echo "<option value=\"$value->subjectID\">", $value->subject, "</option>";
			}
		}
	}

	public function unique_capacity()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int) $id) {
			if ($this->input->post('sectionID')) {
				$sectionID = $this->input->post('sectionID');
				$classesID = $this->input->post('classesID');
				$schoolyearID = $this->data['siteinfos']->school_year;
				$section = $this->section_m->general_get_section($this->input->post('sectionID'));
				$student = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID, 'srstudentID !=' => $id));
				if (customCompute($student) >= $section->capacity) {
					$this->form_validation->set_message("unique_capacity", "The %s capacity is full.");
					return false;
				}
				return true;
			} else {
				$this->form_validation->set_message("unique_capacity", "The %s field is required.");
				return false;
			}
		} else {
			if ($this->input->post('sectionID')) {
				$sectionID = $this->input->post('sectionID');
				$classesID = $this->input->post('classesID');
				$schoolyearID = $this->data['siteinfos']->school_year;
				$section = $this->section_m->general_get_section($this->input->post('sectionID'));
				$student = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srsectionID' => $sectionID, 'srschoolyearID' => $schoolyearID));
				if (customCompute($student) >= $section->capacity) {
					$this->form_validation->set_message("unique_capacity", "The %s capacity is full.");
					return false;
				}
				return true;
			} else {
				$this->form_validation->set_message("unique_capacity", "The %s field is required.");
				return false;
			}
		}
	}

	public function unique_registerNO()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int) $id) {
			$student = $this->studentrelation_m->general_get_single_student(array("srregisterNO" => $this->input->post("registerNO"), "srstudentID !=" => $id));
			if (customCompute($student)) {
				$this->form_validation->set_message("unique_registerNO", "The %s is already exists.");
				return false;
			}
			return true;
		} else {
			$student = $this->studentrelation_m->general_get_single_student(array("srregisterNO" => $this->input->post("registerNO")));
			if (customCompute($student)) {
				$this->form_validation->set_message("unique_registerNO", "The %s is already exists.");
				return false;
			}
			return true;
		}
	}

	public function active()
	{
		if (permissionChecker('student_edit')) {
			$id = $this->input->post('id');
			$status = $this->input->post('status');
			if ($id != '' && $status != '') {
				if ((int) $id) {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$student = $this->studentrelation_m->get_single_studentrelation(array('srstudentID' => $id, 'srschoolyearID' => $schoolyearID));
					$studentID = $student->srstudentID;
					$mainStudent = $this->student_m->get_single_stud(['studentID' => $studentID]);
					$parentsID = $mainStudent->parentID;

					if (customCompute($student)) {
						if ($status == 'chacked') {
							$this->student_m->update_student(array('active' => 1), $id);
							if ($parentsID) {
								$parent = $this->parents_m->get_single_parents(['parentsID' => $parentsID]);
								if ($parent) {
									$this->parents_m->update_parents(array('active' => 1), $parent->parentsID);
								}
							}
							echo 'Success';
						} elseif ($status == 'unchacked') {
							$this->student_m->update_student(array('active' => 0), $id);
							if ($parentsID) {
								$parent = $this->parents_m->get_single_parents(['parentsID' => $parentsID]);
								if ($parent) {
									$this->parents_m->update_parents(array('active' => 0), $parent->parentsID);
								}
							}
							echo 'Success';
						} else {
							echo "Error";
						}
					} else {
						echo 'Error';
					}
				} else {
					echo "Error";
				}
			} else {
				echo "Error";
			}
		} else {
			echo "Error";
		}
	}

	private function leave_applications_date_list_by_user_and_schoolyear($userID, $schoolyearID, $usertypeID)
	{
		$leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication(array('create_userID' => $userID, 'create_usertypeID' => $usertypeID, 'schoolyearID' => $schoolyearID, 'status' => 1));

		$retArray = [];
		if (customCompute($leaveapplications)) {
			$oneday = 60 * 60 * 24;
			foreach ($leaveapplications as $leaveapplication) {
				for ($i = strtotime($leaveapplication->from_date); $i <= strtotime($leaveapplication->to_date); $i = $i + $oneday) {
					$retArray[] = date('d-m-Y', $i);
				}
			}
		}
		return $retArray;
	}

	public function unique_data($data)
	{
		if ($data != '') {
			if ($data == '0') {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return false;
			}
		}
		return true;
	}

	public function unique_optionalSubjectID()
	{
		if ($this->input->post('optionalSubjectID') != 0 && $this->input->post('anotheroptionalSubjectID') != 0) {
			if ($this->input->post('optionalSubjectID') == $this->input->post('anotheroptionalSubjectID')) {
				$this->form_validation->set_message("unique_optionalSubjectID", "The %s field match with Another Optional Subject");
				return false;
			} else {
				return true;
			}
		}
		return true;
	}

	public function unique_anotheroptionalSubjectID()
	{
		if ($this->input->post('optionalSubjectID') != 0 && $this->input->post('anotheroptionalSubjectID') != 0) {
			if ($this->input->post('optionalSubjectID') == $this->input->post('anotheroptionalSubjectID')) {
				$this->form_validation->set_message("unique_anotheroptionalSubjectID", "The %s field match with Optional Subject");
				return false;
			} else {
				return true;
			}
		}
		return true;
	}

	public function index()
	{

		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css',
				'assets/jqueryUI/jqueryui.css'
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
				'assets/jqueryUI/jqueryui.min.js'
			)
		);

		$this->data['holidays'] = $this->getHolidaysano();
		$this->data['startingtime'] = date('Y,m,d', strtotime($this->data['schoolyearsessionobj']->startingdate));
		$this->data['endingtime'] = date('Y,m,d', strtotime($this->data['schoolyearsessionobj']->endingdate));
		$this->data['userType'] = $this->session->userdata('usertypeID');
		$classobj = $this->classes_m->get_class_by_teacher();
		// $this->data['students'] = $this->student_m->get_allstudentsjson();
		if ($this->session->userdata('usertypeID') == 2) {

			$this->data['classesID'] = $classobj->classesID;
			$this->data['sectionobj']  = $this->section_m->get_single_section(array('teacherID' => $this->session->userdata('loginuserID'), 'classesID' => $classobj->classesID));
			$teacherobjs = $this->subjectteacher_m->get_classes_by_teacher();
			$this->data['teacherobjs'] = $teacherobjs;

			$this->data["subview"] = "student/studentviewlist";
		} else {
			if (!$this->uri->segment(3) && !$this->uri->segment(4)) {
				$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				$this->data['teacherobjs'] = $teacherobjs;
				if($this->session->userdata('usertypeID') == 1){
					$this->data["subview"] = "student/studentviewlistadmin";
				}elseif($this->session->userdata('usertypeID') == 3){
					redirect(base_url('/profile/index'));
				}elseif($this->session->userdata('usertypeID') == 4){
					redirect(base_url('/profile/index'));
				}
				if (permissionChecker('student_view')) {
					$this->data["subview"] = "student/studentviewlistadmin";
				}else{
					redirect(base_url('/feed/index'));
				}


			} else if ($this->uri->segment(3) && !$this->uri->segment(4)) {
				$this->data['classesID'] = $this->uri->segment(3);

				$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "student/studentviewlistadmin";
			} else {

				$this->data['classesID'] = $this->uri->segment(3);
				$this->data['sectionID'] = $this->uri->segment(4);
				$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "student/studentviewlist";
			}
		}

		$this->load->view('_layout_main', $this->data);
	}

	public function take_studentview()
	{

		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$classesID = $this->input->post('id');
		$sectionID = $this->input->post('idsection');
		$classes = $this->classes_m->get_classes($classesID)->classes;

	   //	$studentno = $this->student_m->get_student_count_by_status($sectionID);
       // $students = $this->student_m->get_order_by_student_with_section($classesID, $this->session->userdata('defaultschoolyearID'), $sectionID);
       // $studentno = $this->student_m->get_student_number($sectionID);		
  
            $students = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $classesID, "srsectionID" => $sectionID,'srschoolyearID' => $schoolyearID));
		    $allStudents = $this->studentrelation_m->get_order_by_all_student(array('srclassesID' => $classesID, "srsectionID" => $sectionID,'srschoolyearID' => $schoolyearID));
			$studentno = count($students);
			$total_students = count($allStudents);
			

		$teacherprofile = $this->teachersection_m->get_sectionteacher($sectionID);
		$sectionname = $this->section_m->getSectionByID($sectionID);
		$image = imagelink1($teacherprofile->photo,56);

		echo  '<div class="mt-4 mb-4 pb-3">
		<div class="leftContent" style="float: left;">
	   <h4><b>Class Student List</b></h4>
	</div>
	<div class="rightContent" style="float: right;">
	   <a class="btn btn-sm btn-default waves-effect waves-light" target="_blank" href="' . base_url() . 'student/exportExcel/' . $classesID . '/' . $sectionID . '" title="Excel"><i class="fa fa-file-excel-o"></i> XLSX</a>
	   <a class="btn btn-sm btn-default waves-effect waves-light" target="_blank" href="' . base_url() . 'student/PDF/' . $classesID . '/' . $sectionID . '" title="PDF"><i class="fa fa-file-pdf-o"> PDF Preview</i></a>
	</div>
		</div>
        <div class="card mt-3 card--attendance">
            <div class="card-header">
                <div class="row row-md-flex">
                    <div class="col-md-5">
					<div class="media-block media-block-alignCenter">
					
						<figure class="avatar__figure">
						<span class="avatar__image">
						<img
	                        src="' . $image. '"
	                        alt=""  
	                      />
						</span>
						
						
						</figure>
						<div class="media-block-body">
						<h3 class="card-title mb-3 mb-lg-0">
                        ' . $classes . ' <span class="pill pill--flat pill--sm">' . $sectionname . '</span></h3>
                        <div class="mt-2 ">' . $teacherprofile->name . '</div>
						</div>
					</div>
                        
                    </div>
                    <div class="col-md-4 attendance-stats">
                        <div>Total Students: ' . $total_students.'</div>
						<div>Active Students: ' . $studentno.'</div>
                            
                        </div>
                        <div class="col-md-3 attendance-action">
                        
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="attendee-lists">
                    <div hidden data-value="' . $studentno. '" id="hiddenchron"></div>';

		$i = 0;
		foreach ($allStudents as $student) {

			$statushtml = '';
			if (permissionChecker('parents_edit')) {
				$statushtml .= '<div class="onoffswitch-small" id="' . $student->studentID . '">
					  <input type="checkbox" id="myonoffswitch' . $student->studentID . '" class="onoffswitch-small-checkbox" name="parents_status"';
				if ($student->active === '1') {
					$statushtml .= "checked='checked'";
				}
				$statushtml .= '>
					  <label for="myonoffswitch' . $student->studentID . '" class="onoffswitch-small-label">
						  <span class="onoffswitch-small-inner"></span>
						  <span class="onoffswitch-small-switch"></span>
					  </label>
				  </div>';
			}

			$html = '';
			if (permissionChecker('student_edit') || permissionChecker('student_delete')) {
				$html .= '<div class="sortable-actions"><div class="dropdown">
					  <a href="#" class="" data-toggle="dropdown">⋮</a>
							<ul class="dropdown-menu">
							<li class=""><a href="' . base_url('student/edit/') . $student->studentID . '/' . $student->classesID . '">Edit</a></li>
							<li class=""><a  class="delete" href="' . base_url('student/delete/') . $student->studentID . '/' . $student->classesID . '" >Delete</a></li>
							</ul>
					  </div></div>';
			}

			echo '<div class="attendee-lists-item">
                      <div class="media-block">
                          <figure class="avatar__figure">
                          <span class="avatar__image">
	                      <img
	                        src="' . base_url() . '/uploads/images/' . $student->photo . '"
	                        alt=""
	                      />
                          </span>
                          </figure>
                      <div class="media-block-body">
                          <div class="media-content">
                          <h4 class="title">
                          <a href="' . base_url() . 'student/view/' . $student->studentID . '/' . $student->classesID . '" >' . $student->name . '</a>
                          <em class="rollnumber">Roll # <b>' . $student->roll . '</b></em>
                          </h4>
                      </div>
					  	' . $statushtml . $html . '
					  </div>
                  </div>
                  </div>';
			$i++;
		}
		echo '</div>
              </div>
              </div>
              </div></div>';
	}

	public function sectionall()
	{
		$classesID = $this->input->post('id');
		if ((int)$classesID) {
			$sections = $this->section_m->get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("attendance_select_section"), "</option>";
			if (customCompute($sections)) {
				foreach ($sections as $key => $section) {
					echo "<option value=\"$section->sectionID\">", $section->section, "</option>";
				}
			}
		}
	}

	public function classsectionview()
	{
		$id = $this->input->post('id');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		if ((int)$id) {
			$sections = $this->section_m->get_order_by_section(array('classesID' => $id));

			$classesObj = $this->classes_m->get_classes($id);
			$classes = $classesObj->classes;
			$classesID = $classesObj->classesID;

			echo    '<div class="mt-4 mb-4 pb-3" style="margin-bottom:10px;">
						<div class="col-md-8 leftContent">
							<h4><b>Class ' . $classes . '</b></h4>
						</div>
						<div class="col-md-4 rightContent">
							<a class="btn btn-sm btn-default waves-effect waves-light" target="_blank" href="' . base_url() . 'student/exportExcel/' . $classesID . '" title="Excel"><i class="fa fa-file-excel-o"></i> XLSX</a>
							<a class="btn btn-sm btn-default waves-effect waves-light" target="_blank" href="' . base_url() . 'student/PDF/' . $classesID . '" title="PDF"><i class="fa fa-file-pdf-o"> PDF Preview</i></a>
						</div>
			      </div><br>';
			foreach ($sections as $section) {
				$teacherprofile = $this->teachersection_m->get_sectionteacher($section->sectionID);
				$attdate = $this->input->post('attdate');
				$image = imagelink1($teacherprofile->photo,56);

				$students = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, "srsectionID" => $section->sectionID,'srschoolyearID' => $schoolyearID));
		       // active students
			   $studentno = count($students);
			   // active inactive students
			   $total_students = count($this->studentrelation_m->get_order_by_all_student(array('srclassesID' => $id, "srsectionID" => $section->sectionID,'srschoolyearID' => $schoolyearID)));

				echo   '<div class="card mt-3 card--attendance">
					        <div class="card-header ">
						
					            <div class="row row-md-flex">
					                <div class="col-md-5">
									<div class ="media-block mb-3 mb-lg-0 media-block-alignCenter">
									<figure class="avatar__figure">
									<span class="avatar__image">
									<img
										src="'.$image.'"
										alt="" class="avatar-img"
									/>
									</span>
									
									</figure>
										 
										<div class="media-block-body">
										
										<div class="mt-2">
										<span class="pill pill--flat pill--sm">'.$section->section.'</span><br>' . $teacherprofile->name . '</div>
										</div>
									</div>
				                      
					                      
				                    </div>
				                    <div class="col-md-4 attendance-stats">
									<div>Total Students: ' . $total_students.'</div>
									<div>Active Students: ' . $studentno.'</div>
				                            
				                    </div>
			                        <div class="col-md-3 attendance-action">
			                            <a href="' . base_url() . 'student/index/' . $id . '/' . $section->sectionID . '"  class="btn-link btn">
				                        View Student 
				                        <i class="fa fa-2x fa-angle-right ml-3"></i>
				                    </a>
			                        </div>
					            </div>
					        </div>
					    </div>';
			}
		}
	}

	public function PDF()
	{

		$filters['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
		$filters['classesID'] = $classesID = $this->uri->segment(3);
		$filters['sectionID'] = $sectionID  =  $this->uri->segment(4);

		$classes = $this->classes_m->general_get_classes($classesID);

		if ($classesID != '') {
			$this->data['filename'] = 'studentpdf-class-' . $classes->classes;
		}
		if ($classesID != '' && $sectionID != '') {
			$section = $this->section_m->general_get_section($sectionID);
			$this->data['filename'] = 'studentpdf-class-' . $classes->classes . '-sec-' . $section->section;
		}

		$students = $this->student_m->studentsExport($filters);
		$this->data['students'] = $students;

		$this->resultPDF('studentpdfresult.css', $this->data, 'student/studentPDF');
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

		$filters['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
		$filters['classesID'] = $classesID = $this->uri->segment(3);
		$filters['sectionID'] = $sectionID  =  $this->uri->segment(4);

		$classes = $this->classes_m->general_get_classes($classesID);

		$students = $this->student_m->studentsExport($filters);
		$this->data['students'] = $students;

		$newArray = [];
		if ($students) {
			$newArray['students'] = $students;
		}

		if ($classesID != '') {
			$filename = 'students-class-' . $classes->classes;
		}
		if ($classesID != '' && $sectionID != '') {
			$section = $this->section_m->general_get_section($sectionID);
			$filename = 'students-class-' . $classes->classes . '-sec-' . $section->section;
		}

		return $this->generateXML($newArray, $filename);
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

				if ($key == 'students') {
					$sheet->setCellValue('A1', 'S.N');
					$sheet->setCellValue('B1', 'Name');
					$sheet->setCellValue('C1', 'RegisterNO');
					$sheet->setCellValue('D1', 'Class');
					$sheet->setCellValue('E1', 'Section');
					$sheet->setCellValue('F1', 'Roll');
					$sheet->setCellValue('G1', 'Blood Group');
					$sheet->setCellValue('H1', 'Country');
					$sheet->setCellValue('I1', 'DOB');
					$sheet->setCellValue('J1', 'Sex');
					$sheet->setCellValue('K1', 'Email');
					$sheet->setCellValue('L1', 'Phone');
					$sheet->setCellValue('M1', 'Address');
					$rows = 2;
					$j = 1;
					foreach ($value as $val) {
						$sheet->setCellValue('A' . $rows, $j);
						$sheet->setCellValue('B' . $rows, $val->name);
						$sheet->setCellValue('C' . $rows, $val->registerNO);
						$sheet->setCellValue('D' . $rows, $val->classes);
						$sheet->setCellValue('E' . $rows, $val->section);
						$sheet->setCellValue('F' . $rows, $val->roll);
						$sheet->setCellValue('G' . $rows, $val->bloodgroup);
						$sheet->setCellValue('H' . $rows, $val->country);
						$sheet->setCellValue('I' . $rows, $val->dob);
						$sheet->setCellValue('J' . $rows, $val->sex);
						$sheet->setCellValue('K' . $rows, $val->email);
						$sheet->setCellValue('L' . $rows, $val->phone);
						$sheet->setCellValue('M' . $rows, $val->address);
						$rows++;
						$j++;
					}
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

}
