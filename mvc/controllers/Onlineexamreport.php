<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Onlineexamreport extends Admin_Controller {
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
		$this->load->model('tempanswer_m');
		$this->load->model('student_m');
		$this->load->model('classes_m');
		$this->load->model('section_m');
		$this->load->model('subject_m');
		$this->load->model('online_exam_m');
		$this->load->model('studentrelation_m');
		$this->load->model('online_exam_user_status_m');
		$this->load->model("chapter_m");
		$this->load->model("studentgroup_m");
		$this->load->model('uploaded_answers_m');
		$this->load->model('online_exam_question_m');
		$this->load->model('question_answer_m');
		$this->load->model('question_bank_m');
		$this->load->model('online_exam_user_answer_m');
		$this->load->model('online_exam_user_answer_option_m');
		$language = $this->session->userdata('lang');
		$this->lang->load('onlineexamreport', $language);
	}

	public function index() {

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
		$this->data['onlineexams'] 	= $this->online_exam_m->get_order_by_online_exam(array('schoolYearID' => $schoolyearID));
		$this->data['classes'] 		= $this->classes_m->general_get_classes();
		$this->data['student_groups'] = $this->studentgroup_m->get_studentgroup();
		$this->data["subview"] 		= "report/onlineexam/OnlineexamReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function uploaded_answers() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);

		$this->data['onlineexams'] 	= $this->online_exam_m->get_online_exam();
		$this->data['classes'] 		= $this->classes_m->get_classes();
		$this->data["subview"] = "report/onlineexam/uploadedanswerslist";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'onlineexamID',
				'label' => $this->lang->line('onlineexamreport_onlineexam'),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line('onlineexamreport_classes'),
				'rules' => 'trim|xss_clean|numeric|callback_unique_data'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line('onlineexamreport_section'),
				'rules' => 'trim|xss_clean|numeric'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line('onlineexamreport_student'),
				'rules' => 'trim|xss_clean|numeric'
			),
			array(
				'field' => 'statusID',
				'label' => $this->lang->line('onlineexamreport_status'),
				'rules' => 'trim|xss_clean|numeric'
			),
			array(
				'field' => 'studentgroupID',
				'label' => $this->lang->line('onlineexamreport_studentgroup'),
				'rules' => 'trim|xss_clean|numeric'
			)
		);

		return $rules;
	}

	protected function send_pdf_to_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line('onlineexamreport_to'),
				'rules' => 'trim|required|xss_clean|valid_email'
			),array(
				'field' => 'subject',
				'label' => $this->lang->line('onlineexamreport_subject'),
				'rules' => 'trim|required|xss_clean'
			),array(
				'field' => 'message',
				'label' => $this->lang->line('onlineexamreport_message'),
				'rules' => 'trim|xss_clean'
			),array(
				'field' => 'id',
				'label' => $this->lang->line('onlineexamreport_id'),
				'rules' => 'trim|numeric|required|xss_clean'
			),
		);
		return $rules;
	}

	public function unique_data() {
		$onlineexamID = $this->input->post('onlineexamID');
		$classesID = $this->input->post('classesID');

		if($onlineexamID === "0" && $classesID === '0') {
			$this->form_validation->set_message('unique_data', 'The %s field is required.');
			return FALSE;
		}
		return TRUE;
	}

	public function unique_status() {
		$statusID = $this->input->post('statusID');

		if($statusID === "0") {
			$this->form_validation->set_message('unique_status', 'The %s field is required.');
			return FALSE;
		}
		return TRUE;
	}

	public function getSection() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			$allSection = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));

			echo "<option value='0'>", $this->lang->line("onlineexamreport_please_select"),"</option>";
			foreach ($allSection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}

		}
	}

	public function getStudent() {
		$classesID  = $this->input->post('classesID');
		$sectionID  = $this->input->post('sectionID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');

		$array = [];
		$array['srschoolyearID'] = $schoolyearID;
		if((int)$classesID && $classesID > 0) {
			$array['srclassesID'] = $classesID;
		}

		if((int)$sectionID && $sectionID > 0) {
			$array['srsectionID'] = $sectionID;
		}
		$students = $this->studentrelation_m->general_get_order_by_student($array);
		echo "<option value='0'>", $this->lang->line("onlineexamreport_please_select"),"</option>";
		foreach ($students as $student) {
			echo "<option value=".$student->srstudentID.">".$student->srname."</option>";
		}
	}

	public function getUserList() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run() == FALSE) {
				$retArray = $this->form_validation->error_array();
				$retArray['status'] = FALSE;
			    echo json_encode($retArray);
			    exit;
			} else {
				$onlineexamID 	= $this->input->post('onlineexamID');
				$classesID 		= $this->input->post('classesID');
				$sectionID 		= $this->input->post('sectionID');
				$studentID 		= $this->input->post('studentID');
				$statusID 		= $this->input->post('statusID');
				$studentgroupID = $this->input->post('studentgroupID');
				$schoolyearID   = $this->session->userdata('defaultschoolyearID');
				
				$queryArray = [];
				$examArray = [];
				$queryArray['srschoolyearID'] = $schoolyearID;
				if((int)$onlineexamID && $onlineexamID > 0) {
					$examArray['onlineexamID'] = $onlineexamID;
				}
				if((int)$classesID && $classesID > 0) {
					$queryArray['srclassesID'] = $classesID;
					$examArray['classesID'] = $classesID;
				}
				if((int)$sectionID && $sectionID > 0) {
					$queryArray['srsectionID'] = $sectionID;
					$examArray['sectionID'] = $sectionID;
				}
				if((int)$studentID && $studentID > 0) {
					$queryArray['srstudentID'] = $studentID;
					$examArray['userID'] = $studentID;
				}
				if((int)$statusID) {
					$examArray['statusID'] = $statusID;
				}
				if((int)$studentgroupID && $studentgroupID > 0) {
					$examArray['studentgroupID'] = $studentgroupID;
				}

				$this->data['onlineexam_user_statuss'] = $this->online_exam_user_status_m->get_join_online_exam_user_status($examArray, $schoolyearID);
				$this->data['onlineexams'] = pluck($this->online_exam_m->get_order_by_online_exam(array('schoolYearID' => $schoolyearID)), 'obj', 'onlineExamID');
				$this->data['students'] = pluck($this->studentrelation_m->general_get_order_by_student($queryArray), 'obj', 'srstudentID');
				$this->data['classs'] 	= pluck($this->classes_m->general_get_classes(), 'obj', 'classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(), 'obj', 'sectionID');
				$this->data['subjects'] = pluck($this->subject_m->general_get_subject(), 'obj', 'subjectID');
				
				$this->data['onlineexamID'] = $onlineexamID;
				$this->data['classesID']	= $classesID;
				$this->data['sectionID'] 	= $sectionID;
				$this->data['studentID']	= $studentID;
				$retArray['render'] = $this->load->view('report/onlineexam/OnlineexamReport', $this->data, true);
				$retArray['status'] = TRUE;
				echo json_encode($retArray);
			    exit;
			}
		}
	}


	public function getUserListForExport() 
	{
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		$onlineexamID 	= $this->input->post('onlineexamID');
		$classesID 		= $this->input->post('classesID');
		$sectionID 		= $this->input->post('sectionID');
		$studentID 		= $this->input->post('studentID');
		$statusID 		= $this->input->post('statusID');
		$studentgroupID = $this->input->post('studentgroupID');

		if($_POST) {
			$rules = $this->rules();
			unset($rules[4]);
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run() == FALSE) {
				$retArray = $this->form_validation->error_array();
			    echo json_encode($retArray);
			    exit;
			} else {
				$queryArray = [];
				$this->getArray($queryArray, $this->input->post());
				if($queryArray['statusID']) {
					//do nothing
				} else {
					unset($queryArray['statusID']);
				}

				$onlineexam_user_data = $this->online_exam_user_status_m->get_all_exam_details($queryArray);
				$data[0] = array('Roll No.', 'Registered No.', 'Name', 'Class', 'Student Group', 'Total Marks', 'Obtained Marks', 'Total Answer', 'Total Correct Answer');
				
				foreach($onlineexam_user_data as $index => $user_data) {
					$data[$index + 1] = array($user_data['roll'], $user_data['registerNO'],$user_data['name'],$user_data['classes'], $user_data['group'], $user_data['totalMark'], $user_data['totalObtainedMark'] ,$user_data['totalAnswer'], $user_data['totalCurrectAnswer']);
				}
				$this->array_to_csv_download($data);
			    exit;
			}
		}
	}


	public function getUserListForTempUsers()
	{
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		$onlineexamID 	= $this->input->post('onlineexamID');
		$classesID 		= $this->input->post('classesID');
		$sectionID 		= $this->input->post('sectionID');
		$studentID 		= $this->input->post('studentID');
		$statusID 		= $this->input->post('statusID');
		$studentgroupID = $this->input->post('studentgroupID');

		if($_POST) {
			$rules = $this->rules();
			unset($rules[4]);
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run() == FALSE) {
				$retArray = $this->form_validation->error_array();
			    echo json_encode($retArray);
			    exit;
			} else {
				$queryArray = [];
				$this->getArray($queryArray, $this->input->post());
				if($queryArray['statusID']) {
					//do nothing
				} else {
					unset($queryArray['statusID']);
				}

				$onlineexam_user_data = $this->tempanswer_m->get_result($queryArray);
				$data[0] = array('Roll No.', 'Registered No.', 'Name', 'Class', 'Obtained Marks', 'Total Answers', 'Total Correct Answers');
				
				foreach($onlineexam_user_data as $index => $user_data) {
					$data[$index + 1] = array($user_data['roll'], $user_data['registerNO'], $user_data['name'], $user_data['classes'], $user_data['obtained_mark'] , $user_data['total_answer'], $user_data['correct_answers']);
				}
				$this->array_to_csv_download($data);
			    exit;
			}
		}
	}

	function array_to_csv_download($array, $filename = "export.csv", $delimiter=",") {
		header('Content-Type: application/csv');
		header('Content-Disposition: attachment; filename="'.$filename.'";');
	
		$f = fopen('php://output', 'w');
	
		foreach ($array as $line) {
			fputcsv($f, $line, $delimiter);
		}
	}  

	public function getUploadedAnswers() {
		$retArray['status'] = FALSE;
		$retArray['render'] = '';

		$onlineexamID 	= $this->input->post('onlineexamID');
		$classesID 		= $this->input->post('classesID');
		$sectionID 		= $this->input->post('sectionID');
		$studentID 		= $this->input->post('studentID');
		$statusID 		= $this->input->post('statusID');

		if($_POST) {
			$rules = $this->rules();
			unset($rules[4]);
			$this->form_validation->set_rules($rules);
			if($this->form_validation->run() == FALSE) {
				$retArray = $this->form_validation->error_array();
			    echo json_encode($retArray);
			    exit;
			} else {
				$queryArray = [];
				$this->getArray($queryArray, $this->input->post());

				$this->data['result'] = $this->uploaded_answers_m->get_uploaded_answers($onlineexamID, $classesID, $sectionID, $studentID);
				$this->data['onlineexams'] = pluck($this->online_exam_m->get_online_exam(), 'obj', 'onlineExamID');
				$this->data['students'] = pluck($this->student_m->get_student(), 'obj', 'studentID');
				$this->data['sections'] = pluck($this->section_m->get_section(), 'obj', 'sectionID');
				$this->data['subjects'] = pluck($this->subject_m->get_subject(), 'obj', 'subjectID');
				$retArray['render'] = $this->load->view('report/onlineexam/uploadedanswersresult', $this->data, true);
				$retArray['status'] = TRUE;

				echo json_encode($retArray);
			    exit;
			}
		}
	}

	private function getArray(&$queryArray, $post) {
		$onlineexamID 	= $post['onlineexamID'];
		$classesID 		= $post['classesID'];
		$sectionID 		= $post['sectionID'];
		$studentID 		= $post['studentID'];
		$statusID 		= $post['statusID'];
		$studentgroupID = $post['studentgroupID'];

		if(isset($post['onlineexamID']) && $post['onlineexamID'] != 0) {
			$queryArray['onlineexamID'] = $onlineexamID;
		}

		if(isset($post['classesID']) && $post['classesID'] != 0) {
			$queryArray['classesID'] = $classesID;
			$this->data['classes'] = $this->classes_m->get_single_classes(array('classesID' => $classesID));
		} else {
			$this->data['classes'] = array();
		}

		if(isset($post['sectionID']) && ($post['sectionID'] != '' && $post['sectionID'] != 0)) {
			$queryArray['sectionID'] = $sectionID;
			$this->data['section'] = $this->section_m->get_single_section(array('sectionID' => $sectionID));
		} else {
			$this->data['section'] = array();
		}

		if(isset($post['studentID']) && $post['studentID'] != 0) {
			$queryArray['userID'] = $studentID;
		}

		if(isset($post['studentgroupID']) && $post['studentgroupID'] != 0) {
			$queryArray['studentgroupID'] = $studentgroupID;
		}

		if(isset($post['statusID'])) {
			$queryArray['statusID'] = $statusID;
		}

		$this->data['onlineexamID'] = $onlineexamID;
		$this->data['classesID']	= $classesID;
		$this->data['sectionID'] 	= $sectionID;
		$this->data['studentID']	= $studentID;
		$this->data['statusID'] 	= $statusID;
	}

	public function result() {
		$onlineExamUserStatusID = htmlentities(escapeString($this->uri->segment(3)));
		if(permissionChecker('onlineexamreport')) {
			if((int)$onlineExamUserStatusID) {
				$onlineExamUserStatus = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('onlineExamUserStatus' => $onlineExamUserStatusID));
			    $correctAnswer = $onlineExamUserStatus->totalCurrectAnswer;
				$unqid = $onlineExamUserStatus->onlineExamUserAnswerID;
				
				$onlineExamval = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $onlineExamUserStatus->onlineExamID]);
				if(customCompute($onlineExamUserStatus)) {
					
					if($_POST) {
						$f = 0;
						$options = @$_POST['options'];
						$arrayobtainedmark = @$_POST['obtained_mark'];
						$uniqid = $this->input->post('main_id');
						if(!empty($options)  && is_array($options))
						{
							foreach($options as $key=> $optionsval)
							{ 
								$curans = strtolower($_POST['curans'][$key]);
								$oldans = strtolower($_POST['oldans'][$key]);
								$corans = str_replace(' ','',$optionsval);
								$curans = str_replace(' ','',$curans);
								if($curans!=strtolower($corans)) {
									$f = 0;
									$ans_status = 0;
									$obtained_mark = 0;
								}
								else
								{
									$f = 1;
									$ans_status = 1;
									$obtained_mark = $_POST['curansfullmark'][$key];
								}
								$data = ['text'=>$optionsval,'ans_status'=>$ans_status,'obtained_mark'=>$obtained_mark];
								$this->db->where('onlineExamUserAnswerOptionID', $key);
								$this->db->update('online_exam_user_answer_option', $data);
								//logs
								if($oldans!=$optionsval)
								{
									$logdata = ['ans_id'=>$key,
												'old_ans'=>$oldans,
												'updated_ans'=>$optionsval,
												'user_id'=>$this->session->userdata("loginuserID")
											];
									$this->db->insert('update_log',$logdata);		
								}
								//End logs
							}
							if($f==1)
							{
								echo $correctAnswer = $correctAnswer+1;
							}
						}
						
						
						if(!empty($arrayobtainedmark) && is_array($arrayobtainedmark))
						{
							foreach($arrayobtainedmark as $key=> $otval)
							{
								$data = ['obtained_mark'=>$otval];
								$this->db->where('onlineExamUserAnswerOptionID', $key);
								$this->db->update('online_exam_user_answer_option', $data);
								
							}
						}
						$totalCorrectMark = 0;
						$totalQuestionMark = $onlineExamUserStatus->totalMark;
						$getansval = $this->online_exam_user_status_m->optdetails($uniqid);
						if(!empty($getansval))
						{
							foreach($getansval as $getans)
							{
								$totalCorrectMark +=$getans->obtained_mark;
								// $totalQuestionMark +=$getans->full_mark;
							}
						}
						
						
						if(customCompute($onlineExamval)) {
							if($onlineExamval->markType == 5) {
								$percentage = 0;
								if($totalCorrectMark > 0 && $totalQuestionMark > 0) {
									$percentage = (($totalCorrectMark/$totalQuestionMark)*100);
								} 
	
								if($percentage >= $onlineExamval->percentage) {
									$statusID = 5;
								} else {
									$statusID = 10;
								}
							} elseif($onlineExamval->markType == 10) {
								if($totalCorrectMark >= $onlineExamval->percentage) {
									$statusID = 5;
								} else {
									$statusID = 10;
								}
							}
						 
						 //$totscore = $this->online_exam_user_status_m->totscore($unqid,3);
						// echo $totscore;
						// die();
						 //update query
						 $updatedata = [
								'totalMark' => $totalQuestionMark,
								'totalObtainedMark' => $totalCorrectMark,
								'totalPercentage' => (($totalCorrectMark > 0 && $totalQuestionMark > 0) ? (($totalCorrectMark/$totalQuestionMark)*100) : 0),
								'statusID' => $statusID,
								'score'=>$correctAnswer,
								'totalCurrectAnswer'=>$correctAnswer
							];
							$this->db->update('online_exam_user_status',$updatedata,array('onlineExamUserAnswerID' =>$uniqid));
							//end update
							$onlineExamUserStatus = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('onlineExamUserStatus' => $onlineExamUserStatusID));
						}
						header("Location: {$_SERVER['HTTP_REFERER']}");
						exit;
						//$updateobmark = $this->online_exam_user_status_m->getopts(3,$uniqid);
					}
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$this->data['onlineExamUserStatus'] = $onlineExamUserStatus;
					$this->data['onlineexam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $onlineExamUserStatus->onlineExamID));
					if((int)$this->data['onlineexam']->subjectID) {
						$this->data['subject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $this->data['onlineexam']->subjectID));
					} else {
						$this->data['subject'] = [];
					}
					$this->data['rank'] = $this->ranking($onlineExamUserStatus->onlineExamID,$onlineExamUserStatusID, $onlineExamUserStatus->userID);
					$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $onlineExamUserStatus->userID,'srschoolyearID'=>$schoolyearID));
					$this->data['ansoptions'] = $this->online_exam_user_status_m->get_online_exam_user_ans($unqid);
					$this->data['percent'] = $this->online_exam_user_status_m->getgpa(round($this->data['onlineExamUserStatus']->totalPercentage));
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['section'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
					$this->data["subview"] = "report/onlineexam/OnlineexamResult";
					$this->load->view('_layout_main', $this->data);
					
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

	private function ranking($onlineexamID, $onlineexamuserstatusID, $userID) {
		$onlineExamUserStatus = $this->online_exam_user_status_m->get_order_by_online_exam_user_status(array('onlineExamID' => $onlineexamID));
		$retArray = [];
		if(customCompute($onlineExamUserStatus)) {
			foreach($onlineExamUserStatus as $result) {
				$retArray[$result->onlineExamUserStatus] = $result->totalObtainedMark;
			}
		}
		arsort($retArray);
		$i = array_search($onlineexamuserstatusID, array_keys($retArray));
		return ++$i;
	}

	public function pdf() {
		$onlineExamUserStatusID = htmlentities(escapeString($this->uri->segment(3)));
		if(permissionChecker('onlineexamreport')) {
			if((int) $onlineExamUserStatusID) {
				$onlineExamUserStatus = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('onlineExamUserStatus' => $onlineExamUserStatusID));
				if(customCompute($onlineExamUserStatus)) {
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$this->data['onlineExamUserStatus'] = $onlineExamUserStatus;
					$this->data['onlineexam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $onlineExamUserStatus->onlineExamID));
					if((int)$this->data['onlineexam']->subjectID) {
						$this->data['subject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $this->data['onlineexam']->subjectID));
					} else {
						$this->data['subject'] = [];
					}
					$this->data['rank'] = $this->ranking($onlineExamUserStatus->onlineExamID,$onlineExamUserStatusID, $onlineExamUserStatus->userID);
					$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $onlineExamUserStatus->userID,'srschoolyearID'=>$schoolyearID));
					
					$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['section'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');

					$this->reportPDF('onlineexamreport.css', $this->data, 'report/onlineexam/OnlineexamResultPDF');
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

	public function send_pdf_to_mail() {
		$retArray['status'] = FALSE;
		$retArray['message']= '';
		if(permissionChecker('onlineexamreport')) {
			if($_POST) {
				$to           			= $this->input->post('to');
				$subject      			= $this->input->post('subject');
				$message 				= $this->input->post('message');
				$onlineExamUserStatusID	= $this->input->post('id');
				
				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run() == FALSE) {
					$retArray[] = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					if((int) $onlineExamUserStatusID) {
						$onlineExamUserStatus = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('onlineExamUserStatus' => $onlineExamUserStatusID));
						if(customCompute($onlineExamUserStatus)) {
							$schoolyearID = $this->session->userdata('defaultschoolyearID');
							$this->data['onlineExamUserStatus'] = $onlineExamUserStatus;
							$this->data['onlineexam'] = $this->online_exam_m->get_single_online_exam(array('onlineExamID' => $onlineExamUserStatus->onlineExamID));
							if((int)$this->data['onlineexam']->subjectID) {
								$this->data['subject'] = $this->subject_m->general_get_single_subject(array('subjectID' => $this->data['onlineexam']->subjectID));
							} else {
								$this->data['subject'] = [];
							}
							$this->data['rank'] = $this->ranking($onlineExamUserStatus->onlineExamID,$onlineExamUserStatusID, $onlineExamUserStatus->userID);
							$this->data['student'] = $this->studentrelation_m->general_get_single_student(array('srstudentID' => $onlineExamUserStatus->userID,'srschoolyearID'=>$schoolyearID));

							$this->data['classes'] = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
							$this->data['section'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');

							$this->reportSendToMail('onlineexamreport.css', $this->data, 'report/onlineexam/OnlineexamResultPDF', $to, $subject, $message);

							$retArray['status'] = TRUE;
							echo json_encode($retArray);
						    exit;
						} else {
							$retArray['message'] = $this->lang->line("onlineexamreport_onlineexam_found found");
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line("onlineexamreport_id_not found");
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line("onlineexamreport_permissionmethod");
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line("onlineexamreport_permission");
			echo json_encode($retArray);
			exit;
		}
	}

	public function marksheet()
	{
		$this->data['classes'] = $this->classes_m->get_classes();
		if($_POST)
		{
			$subject = $this->input->post('subject');
			if(!empty($subject) && $this->input->post('classesID') > 0)
			{
				$subvalue = '';
				$subvalue = substr($subvalue,1);
				$data = ['title'=>$this->input->post('title'),
						'class_id' => $this->input->post('classesID')
						];
				$this->db->insert('marksheet',$data);
				$insID = $this->db->insert_id();
				foreach($subject as $subval)
				{
					$this->db->insert('marksheet_details',array('marksheet_id'=>$insID,'terminal_id'=>$subval));
				}
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("onlineexamreport/marksheetlist"));	
			}
		}
		$this->data["subview"] = "report/onlineexam/marksheet";
		$this->load->view('_layout_main', $this->data);
	}

	public function marksheetlist()
	{
		$this->data['lists'] 	= $this->online_exam_m->get_examlist();
		$this->data["subview"] = "report/onlineexam/listmarksheet";
		$this->load->view('_layout_main', $this->data);
	}

	public function marksheetedit($id)
	{
		$this->data['lists'] = $this->online_exam_m->get_examlist();
		$this->data['classes'] = $this->classes_m->get_classes();
		$this->data['row'] = $this->online_exam_m->getmarksheet($id);
		$this->data['subjects'] = $this->chapter_m->get_terminal_from_class($this->data['row']->class_id);
		$this->data['mdetails'] = $this->chapter_m->marksheetdetails($id);
		if($_POST)
		{
			$mainID = $this->input->post('main_id');
			$subject = $this->input->post('subject');
			if(!empty($subject) && $this->input->post('classesID') > 0)
			{
				$data = ['title'=>$this->input->post('title'),
						'class_id' => $this->input->post('classesID')
						];
				$this->db->where('id', $mainID);
				$this->db->update('marksheet', $data);
				if(!empty($subject))
				{
					$this->db->where('marksheet_id', $mainID);
					$this->db->delete('marksheet_details');

					foreach($subject as $subval)
					{
						$this->db->insert('marksheet_details',array('marksheet_id'=>$mainID,'terminal_id'=>$subval));
					}
				}
				$this->session->set_flashdata('success','Marksheet has been successfully updated');
				redirect(base_url("onlineexamreport/marksheetlist"));	
			}
		}
		$this->data["subview"] = "report/onlineexam/editmarksheet";
		$this->load->view('_layout_main', $this->data);
	}

	public function generatemarksheet($id)
	{
		$this->data['rows'] = $this->online_exam_m->generatemarksheet($id);
		$this->reportPDF('generatemarksheet.css', $this->data, 'report/onlineexam/generatemarksheet');
		//$this->data["subview"] = "report/onlineexam/generatemarksheet";
		//$this->load->view('_layout_main', $this->data);
		
	}

	public function testgeneratemarksheet()
	{
		$this->data['rows'] = '';
		$this->reportPDF('onlineexamreport.css', $this->data, 'report/onlineexam/testgeneratemarksheet');
		//$this->data["subview"] = "report/onlineexam/generatemarksheet";
		//$this->load->view('_layout_main', $this->data);
		
	}

	public function marksheetdelete($id='')
	{
		if($id > 0)
		{
			$this->db->where('id', $id);
			$this->db->delete('marksheet');
			$this->db->where('marksheet_id', $id);
			$this->db->delete('marksheet_details');
			$this->session->set_flashdata('success','Marksheet has been successfully deleted');
			redirect(base_url("onlineexamreport/marksheetlist"));
		}
	}

	public function transferDataFromTempToMainDB(){

		$onlineExamID = htmlentities(escapeString($this->uri->segment(3)));
		$onlineExamQuestions = $this->online_exam_question_m->get_order_by_online_exam_question(['onlineExamID' => $onlineExamID]);
        $allOnlineExamQuestions = $onlineExamQuestions;
		$pluckOnlineExamQuestions = pluck($allOnlineExamQuestions, 'questionID');
		$allAnswers = $this->question_answer_m->get_where_in_question_answer($pluckOnlineExamQuestions, 'questionID');
		$questionsBank = pluck($this->question_bank_m->get_order_by_question_bank(), 'obj', 'questionBankID');
               
		$mainQuestionAnswer = [];
		foreach ($allAnswers as $answer) {
			if($answer->typeNumber == 3) {
				$mainQuestionAnswer[$answer->typeNumber][$answer->questionID][$answer->answerID] = $answer->text;
			} elseif($answer->typeNumber == 4) 
			{
				$mainQuestionAnswer[$answer->typeNumber][$answer->questionID]= $answer->text;
			}
			else {
				$mainQuestionAnswer[$answer->typeNumber][$answer->questionID][] = $answer->optionID;
			}
		} 
		
		$questionStatus = [];
        $correctAnswer = 0;
        $totalQuestionMark = 0;
        $totalCorrectMark = 0;
        $totalAnswer = 0;
		if(customCompute($allOnlineExamQuestions)) {
			foreach ($allOnlineExamQuestions as $aoeq) {    
				if(isset($questionsBank[$aoeq->questionID])) {
					$totalQuestionMark += $questionsBank[$aoeq->questionID]->mark; 
				}
			}
		}
		
		$tempDatas = pluck_multi_array($this->tempanswer_m->get_user_temp_answers(['exam_id' => $onlineExamID ]),'obj','user_id');
		$tempSubjectiveDatas = pluck_multi_array($this->tempanswer_m->get_alluser_temp_subjective_answers(['exam_id' => $onlineExamID ]),'obj','user_id');
        
		$newTempDatas = [];
		if(customCompute($tempDatas)){
			foreach($tempDatas as $userid=>$tempData){
				    foreach($tempData as $tempd){
						if($tempd->typeNumber == 1 || $tempd->typeNumber == 2){
							for($i=1;$i<5;$i++){
								$opid = 'optionid'.$i;
								if($tempd->$opid != null){
							      $newTempDatas[$userid][$tempd->typeNumber][$tempd->question_id][] = $tempd->$opid;
								}
							}
						}elseif($tempd->typeNumber == 3){
							$fillinthebkanksAnswers = $this->question_answer_m->get_order_by_question_answer(['questionID'=>$tempd->question_id]);
							if(customCompute($fillinthebkanksAnswers)){
                                $j = 1; 
								$opID = 'option'.$j;
								foreach($fillinthebkanksAnswers as $fillinthebkanksAnswer){
								$opID = 'option'.$j;
								 $newTempDatas[$userid][$tempd->typeNumber][$tempd->question_id][$fillinthebkanksAnswer->answerID] = $tempd->$opID;
							    $j++;
								}
							}
						}
					}
			}
		}
		
		if(customCompute($tempSubjectiveDatas)){
			foreach($tempSubjectiveDatas as $userid=>$tempSubjectiveData){
				foreach($tempSubjectiveData as $tempsubdata){
					$newTempDatas[$userid][$tempsubdata->typeNumber][$tempsubdata->question_id] = $tempsubdata->answer;
				}
			}
		}

		$this->data['onlineExam'] = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $onlineExamID]);

		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if(customCompute($newTempDatas)) {
			foreach($newTempDatas as $user_id=>$userAnswer){

		    $this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $user_id, 'srschoolyearID' => $schoolyearID));
			$this->data['class'] = $this->classes_m->get_classes($this->data['student']->classesID);
            if(customCompute($this->data['student'])) {
                $this->data['section'] = $this->section_m->get_section($this->data['student']->sectionID);
            } else {
                $this->data['section'] = array();
            } 
			
			$time = date("Y-m-d h:i:s");
			$mainQuestionAnswer = [];
			$uniqid = $this->generateUniqueNumber();
			$status = 1;
			$attend = 0;
			foreach ($allAnswers as $answer) {
				if($answer->typeNumber == 3) {
					$mainQuestionAnswer[$answer->typeNumber][$answer->questionID][$answer->answerID] = $answer->text;
				} elseif($answer->typeNumber == 4) 
				{
					$mainQuestionAnswer[$answer->typeNumber][$answer->questionID]= $answer->text;
				}
				else {
					$mainQuestionAnswer[$answer->typeNumber][$answer->questionID][] = $answer->optionID;
				}
			}

			$questionStatus = [];
			$correctAnswer = 0;
			$totalQuestionMark = 0;
			$totalCorrectMark = 0;
			$visited = [];
			$totalAnswer = 0;
		   
			if(customCompute($userAnswer)) {
				foreach ($userAnswer as $userAnswerKey => $uA) {
						if(!$this->input->post('nullanswer')) 
						{
						  if($userAnswerKey == 3){
							  $fanswer = 0;
							  foreach($uA as $u){
									foreach($u as $a){
										if($a != ''){
											$fanswer = $fanswer + 1;
										}
									}
							  }
							  if($fanswer > 0){
								$totalAnswer += 1;
							  }
						  }elseif($userAnswerKey == 4){
							   foreach($uA as $uuaa){
									if($uuaa != ''){
										$totalAnswer += 1;
									}
							   }
						  }else{
							 $totalAnswer += customCompute($uA);
						  }
						}
					
				}
			}
		   
			if(customCompute($allOnlineExamQuestions)) {
				foreach ($allOnlineExamQuestions as $aoeq) {    
					if(isset($questionsBank[$aoeq->questionID])) {
						$totalQuestionMark += $questionsBank[$aoeq->questionID]->mark; 
					}
				}
			}
			
			$f = 0;
			$onlineExamQuestionID=0;
			$userID = $user_id;
			foreach ($mainQuestionAnswer as $typeID => $questions) {
			 if(!isset($userAnswer[$typeID])) continue;
				foreach ($questions as $questionID => $options) {
					if(isset($onlineExamQuestions[$questionID])) {
						$onlineExamQuestionID = $onlineExamQuestions[$questionID]->onlineExamQuestionID;
						$onlineExamUserAnswerID = $this->online_exam_user_answer_m->insert([
							'onlineExamQuestionID' => $onlineExamQuestionID,
							'userID' => $userID
						]);
					}
			   
				  
					if(isset($userAnswer[$typeID][$questionID])) {
						 $qsdetails = $this->question_bank_m->get_single_question_bank(['questionBankID'=>$questionID]);
							$totalCorrectMark += isset($questionsBank[$questionID]) ? $questionsBank[$questionID]->mark : 0;
							$questionStatus[$questionID] = 1;
							$correctAnswer++;
					   
						$f = 1;
						if($typeID == 3) {
							$obmarks = 0;
							$perAnswerMarks = round(($qsdetails->mark/count($options)),2);
							foreach ($options as $answerID => $answer) {
								$takeAnswer = strtolower($answer);
								$getAnswer = isset($userAnswer[$typeID][$questionID][$answerID]) ? strtolower($userAnswer[$typeID][$questionID][$answerID]) : '';
								
								if($getAnswer != $takeAnswer) {
									$ans_status = 0;
									$obtained_mark = 0;
									$f = 0;
								}
								else
								{
									$f = 0;
									$ans_status = 1;
									$obtained_mark = $perAnswerMarks;
									$obmarks = $obmarks + $perAnswerMarks;
								}
						   if(isset($userAnswer[$typeID][$questionID][$answerID]) && $userAnswer[$typeID][$questionID][$answerID] != ''){
							  
								$this->online_exam_user_answer_option_m->insert([
									'questionID' => $questionID,
									'typeID' => $typeID,
									'text' => $getAnswer,
									'time' => $time,
									'user_id'=> $user_id,
									'ans_status'=> $ans_status,
									'obtained_mark'=> $obtained_mark,
									'full_mark'=> $qsdetails->mark,
									'onlineExamQuestionID'=> $onlineExamQuestionID,
									'onlineExamUserAnswerID'=> $uniqid,
									'correct_ans'=>trim($takeAnswer),
									'attend'=> $attend,
									'examID'=> $this->data['onlineExam']->onlineExamID
								]);
							}
								
							}
							if($obmarks == $qsdetails->mark){
								$ans_status = 1;
								$obtained_mark = $obmarks;
							}else{
								$ans_status = 0;
								$obtained_mark = $obmarks;
							}
							
						}
						
						elseif($typeID == 1 || $typeID == 2) {
								$ans_status = 0;
								$obtained_mark = 0;
								 if($this->input->post('nullanswer'))
								 {
									$f = 0;
									$ans_status = 0;
									$obtained_mark = 0;
									$attend = 1;   
								 }
								 else
								 {
							   
									if(!empty($options))
								{
										$obmarks = 0;
										$perAnswerMarks = round(($qsdetails->mark/count($options)),2);

										if(customCompute($userAnswer[$typeID][$questionID])){
										foreach ($userAnswer[$typeID][$questionID] as $userOption) {
									  
										if(count($userAnswer[$typeID][$questionID]) > count($options)){
											$obtained_mark = 0;
											$ans_status = 0;
											$f = 0;
										}else{
											if(in_array($userOption, $options)) {
												$obmarks = $obmarks +  (float)$perAnswerMarks;
												$obtained_mark = $perAnswerMarks;
												$ans_status = 1;
											}else{
												$obtained_mark = 0;
												$ans_status = 0;
											}  
										}
											$this->online_exam_user_answer_option_m->insert([
												'questionID' => $questionID,
												'optionID' => $userOption,
												'typeID' => $typeID,
												'time' => $time,
												'user_id'=> $user_id,
												'ans_status'=>$ans_status,
												'obtained_mark'=>$obtained_mark,
												'full_mark'=>$qsdetails->mark,
												'onlineExamQuestionID'=>$onlineExamQuestionID,
												'onlineExamUserAnswerID'=>$uniqid,
												'attend'=>$attend,
												'examID'=>$this->data['onlineExam']->onlineExamID
											]);
										} 

										if($obmarks == $qsdetails->mark){
											if($typeID == 1){
												$f = 1;
											}else{
												$f = 0;
											}
											$ans_status = 1;
											$obtained_mark = $obmarks;
										}else{
											if($typeID == 1){
												$f = 0;
											}else{
												$f = 0;
											}
											$ans_status = 0;
											$obtained_mark = $obmarks;
										}
									}
								 }
								 }
								 
								if(!isset($visited[$typeID][$questionID])) {
									$visited[$typeID][$questionID] = 1;
								}
						}
						elseif($typeID == 4) {
							$f = 0;
							$new_file = '';
							$status = 0;
							$subjectiveAnswerFiles = pluck($this->tempanswer_m->get_user_temp_subjective_files([
								'exam_id' => $onlineExamID,
								'question_id' => $questionID,
								'user_id' => $user_id,
								'is_subjective' => 1
							]),'link');
							$filesname = '';
							if(customCompute($subjectiveAnswerFiles)){
								$filesname = implode(',',$subjectiveAnswerFiles);
							}

							if($userAnswer[$typeID][$questionID] == ''){
								 if($filesname != ''){
									 $totalAnswer += 1; 
								 }
							}

							if($filesname != '' || $userAnswer[$typeID][$questionID] != ''){
							$this->online_exam_user_answer_option_m->insert([
											'questionID' => $questionID,
											'text' => $userAnswer[$typeID][$questionID],
											'typeID' => $typeID,
											'time' => $time,
											'user_id'=> $user_id,
											'ans_status'=>0,
											'obtained_mark'=>0,
											'full_mark'=>$qsdetails->mark,
											'onlineExamQuestionID'=>$onlineExamQuestionID,
											'onlineExamUserAnswerID'=>$uniqid,
											'subimg'=>$filesname,
											'attend'=>$attend,
											'examID'=>$this->data['onlineExam']->onlineExamID
										]);
							}            
										
						} 
						elseif($typeID == 5) {
							$f = 0;
							$new_file = '';
							$status = 0;
							$subjectiveAnswerFiles = pluck($this->tempanswer_m->get_user_temp_subjective_files([
								'exam_id' => $onlineExamID,
								'question_id' => $questionID,
								'user_id' => $user_id,
								'is_subjective' => 0
							]),'link');
							$filesname = '';
							if(customCompute($subjectiveAnswerFiles)){
								$filesname = implode(',',$subjectiveAnswerFiles);
							}

							if($userAnswer[$typeID][$questionID] == ''){
								 if($filesname != ''){
									 $totalAnswer += 1; 
								 }
							}

							if($filesname != '' || $userAnswer[$typeID][$questionID] != ''){
							$this->online_exam_user_answer_option_m->insert([
											'questionID' => $questionID,
											'text' => $userAnswer[$typeID][$questionID],
											'typeID' => $typeID,
											'time' => $time,
											'user_id'=> $user_id,
											'ans_status'=>0,
											'obtained_mark'=>0,
											'full_mark'=>$qsdetails->mark,
											'onlineExamQuestionID'=>$onlineExamQuestionID,
											'onlineExamUserAnswerID'=>$uniqid,
											'subimg'=>$filesname,
											'attend'=>$attend,
											'examID'=>$this->data['onlineExam']->onlineExamID
										]);
							}            
										
						} 
						elseif($typeID == 55) {
							$f = 0;
							$new_file = '';
							$status = 0;
							if(!empty($_FILES['image']))
							{  
								$acceptable = array("doc", "docx", "pdf", "gif", "jpeg", "jpg", "png"); 
								$target_dir = "./uploads/images/";
								$totalcount = count($_FILES['image']['name'][$typeID][$questionID]);
								$filesname = '';
								for($i=0;$i<$totalcount;$i++)
								{
										$new_file = $_FILES['image']['name'][$typeID][$questionID][$i];
										$_FILES['attach']['tmp_name'] = $_FILES['image']['tmp_name'][$typeID][$questionID][$i];
										$image_info = getimagesize($_FILES['image']['tmp_name'][$typeID][$questionID][$i]);
										$temp = explode(".", $new_file);
										if(in_array(end($temp), $acceptable))
										{
											$newfilename = round(microtime(true)).'_'.$questionID.'_'.$i.'.' . end($temp);
											$new_file = $newfilename;
											$target_file = $target_dir.$newfilename;
											$filesname .=','.$new_file;
											if(move_uploaded_file($_FILES["image"]["tmp_name"][$typeID][$questionID][$i], $target_file))
											{
												$image_width = $image_info[0];
												$image_height = $image_info[1];
												if($image_width > 1800 || $image_height > 1800){
													resizeImage($newfilename,$target_dir);
												}
											}										
										}       
								}
								$filesname =  substr($filesname,1);
							  
							}
							$this->online_exam_user_answer_option_m->insert([
											'questionID' => $questionID,
											'text' => $userAnswer[$typeID][$questionID],
											'typeID' => $typeID,
											'time' => $time,
											'user_id'=> $user_id,
											'ans_status'=>0,
											'obtained_mark'=>0,
											'full_mark'=>$qsdetails->mark,
											'onlineExamQuestionID'=>$onlineExamQuestionID,
											'onlineExamUserAnswerID'=>$uniqid,
											'subimg'=>$filesname,
											'attend'=>$attend,
											'examID'=>$this->data['onlineExam']->onlineExamID
										]);
										
						} 

						if(!$f) {
							$questionStatus[$questionID] = 0;
							$correctAnswer--;
							$totalCorrectMark -= $questionsBank[$questionID]->mark;
						}

						if($typeID == 2 || $typeID == 3){

							if($ans_status == 1){
								$totalCorrectMark += $obtained_mark;
								$correctAnswer++;
							}else{
								$totalCorrectMark += $obtained_mark;
							}
						}
					}
				}
			}


			$examtime = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('userID' => $userID, 'onlineExamID' => $onlineExamID));

			$examTimeCounter = 1;
			if(customCompute($examtime)) {
				$examTimeCounter = $examtime->examtimeID;
				$examTimeCounter++;
			}


			$statusID = 10;
			if(customCompute($this->data['onlineExam'])) {
				if($this->data['onlineExam']->markType == 5) {

					$percentage = 0;
					if($totalCorrectMark > 0 && $totalQuestionMark > 0) {
						$percentage = round(($totalCorrectMark/$totalQuestionMark)*100,2);
					} 

					if($percentage >= $this->data['onlineExam']->percentage) {
						$statusID = 5;
					} else {
						$statusID = 10;
					}
				} elseif($this->data['onlineExam']->markType == 10) {
					if($totalCorrectMark >= $this->data['onlineExam']->percentage) {
						$statusID = 5;
					} else {
						$statusID = 10;
					}
				}
			}
			$status = 0;
		
		   $insID = $this->online_exam_user_status_m->insert([
				'onlineExamID' => $this->data['onlineExam']->onlineExamID,
				'time' => $time,
				'totalQuestion' => customCompute($onlineExamQuestions),
				'totalAnswer' => $totalAnswer,
				'nagetiveMark' => $this->data['onlineExam']->negativeMark,
				'duration' => $this->data['onlineExam']->duration,
				'score' => $correctAnswer,
				'userID' => $userID,
				'classesID' => customCompute($this->data['class']) ? $this->data['class']->classesID : 0,
				'sectionID' => customCompute($this->data['section']) ? $this->data['section']->sectionID : 0,
				'examtimeID' => $examTimeCounter,
				'totalCurrectAnswer' => $correctAnswer,
				'totalMark' => $totalQuestionMark,
				'totalObtainedMark' => $totalCorrectMark,
				'totalPercentage' => (($totalCorrectMark > 0 && $totalQuestionMark > 0) ? round(($totalCorrectMark/$totalQuestionMark)*100,2) : 0),
				'statusID' => $statusID,
				'status'=>($this->data['onlineExam']->result_published == 1) ? 1 : 0,
				'onlineExamUserAnswerID'=>$uniqid,
				'indirect' => 1
			]);

			if($insID) {
				$this->tempanswer_m->delete_temp_answer($user_id, $onlineExamID);
				$this->tempanswer_m->delete_temp_subjective_answer($user_id, $onlineExamID);
				$this->tempanswer_m->delete_temp_subjective_files($user_id, $onlineExamID);
			}

			// if($this->data['onlineExam']->paid) {
			// 	$onlineExamPayments = $this->online_exam_payment_m->get_single_online_exam_payment_only_first_row(array('online_examID' => $this->data['onlineExam']->onlineExamID, 'status' => 0, 'usertypeID' => 3, 'userID' => $user_id));

			// 	if($onlineExamPayments->online_exam_paymentID != NULL) {
			// 		$onlineExamPaymentArray = [
			// 			'status' => 1
			// 		];
			// 		$this->online_exam_payment_m->update_online_exam_payment($onlineExamPaymentArray, $onlineExamPayments->online_exam_paymentID);
			// 	}
			// }
		}
		    $this->session->set_flashdata('success', 'Success');
			redirect(base_url("onlineexamreport/index"));   
		}else{
			$this->session->set_flashdata('error', 'Data not found.');
			redirect(base_url("onlineexamreport/index"));
		}
		   
			
		
	}

	public function generateUniqueNumber()
	{
		$time = time();
        $check = $this->online_exam_user_status_m->get_single_online_exam_user_status(['onlineExamUserAnswerID' => $time]);
		if ($check) {
			return $this->generateUniqueNumber();
		}
		return $time;
	}

}
