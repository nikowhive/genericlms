<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Sattendance extends Api_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("notice_m");
		$this->load->model("fcmtoken_m");
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model("sattendance_m");
		$this->load->model("attendance_note_m");
		$this->load->model("classes_m");
		$this->load->model("section_m");
		$this->load->model('studentgroup_m');
		$this->load->model('subject_m');
		$this->load->model('mailandsmstemplate_m');
		$this->load->model('mailandsmstemplatetag_m');
		$this->load->model('emailsetting_m');
		$this->load->model('setting_m');
		$this->load->model('exam_m');
		$this->load->model('studentrelation_m');
		$this->load->model('leaveapplication_m');
		$this->load->library("email");
		$this->load->library('clickatell');
		$this->load->library('twilio');
		$this->load->library('bulk');
		$this->load->library('msg91');
		$this->load->model("subjectattendance_m");
		$this->load->model("sub_attend_note_m");
		$this->load->model('permission_m');
		$this->load->model('systemadmin_m');
		$this->load->model("teacher_m");
		$this->load->model("user_m");
		$this->load->model("job_m");
		$this->load->model("mobile_job_m");
		$this->load->model("feed_m");

		$this->lang->load('sattendance', $this->data['language']);
	}

	public function index_get($id = null)
	{
		$myProfile = false;
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if ($this->session->userdata('usertypeID') == 3) {
			$id = $this->data['myclass'];
			if (!permissionChecker('sattendance_view')) {
				$myProfile = true;
			}
		}

		if ($this->session->userdata('usertypeID') == 3 && $myProfile) {
			$url = $id;
			$id = $this->session->userdata('loginuserID');
			$this->view_get($id, $url);
		} else {
			if ((int)$id) {
				$this->retdata['classesID'] = $id;
				$this->retdata['classes'] = $this->classes_m->get_classes();
				$this->retdata['students'] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, 'srschoolyearID' => $schoolyearID));

				$fetchClass = pluck($this->retdata['classes'], 'classesID', 'classesID');
				if (isset($fetchClass[$id])) {
					if (customCompute($this->retdata['students'])) {
						$sections = $this->section_m->general_get_order_by_section(array("classesID" => $id));
						$this->retdata['sections'] = $sections;
						foreach ($sections as $key => $section) {
							$this->retdata['allsection'][$section->section] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, "srsectionID" => $section->sectionID, 'srschoolyearID' => $schoolyearID));
						}
					} else {
						$this->retdata['students'] = [];
					}
				} else {
					$this->retdata['classesID'] = 0;
					$this->retdata['students'] = [];
					$this->retdata['classes'] = $this->classes_m->get_classes();
				}
			} else {
				$this->retdata['classesID'] = 0;
				$this->retdata['students'] = [];
				$this->retdata['classes'] = $this->classes_m->get_classes();
			}
		}


		$this->response([
			'status'    => true,
			'message'   => 'Success',
			'data'      => $this->retdata
		], REST_Controller::HTTP_OK);
	}

	private function subjectattendance($id = null, $url = null)
	{
		$schoolyearID 		= $this->session->userdata('defaultschoolyearID');
		$attendances 		= $this->subjectattendance_m->get_order_by_sub_attendance(array("studentID" => $id, "classesID" => $url, 'schoolyearID' => $schoolyearID));
		$schoolYearMonths 	= $this->schoolYearMonth($this->data['schoolyearsessionobj']);
		$holidays 			= $this->getHolidaysSession();
		$weekends 			= $this->getWeekendDaysSession();
		$leaves 			= $this->leaveApplicationsDateListByUser($id, $schoolyearID);
		
		$attendance_notes = array();
		foreach ($attendances as $index =>$att) {
			$attendance_note = $this->sub_attend_note_m->get_order_by_sub_attendance_with_note(array("sub_attendance_note.attendanceID" => $att->attendanceID, "sub_attendance_note.subjectID" => $att->subjectID));
			array_push($attendance_notes, $attendance_note);
		}
		
		$attendances 		= pluck_multi_array_key($attendances, 'obj', 'subjectID', 'monthyear');
		$attendance_notes = pluck_multi_array_key($attendance_notes, 'obj', 'subjectID','monthyear');	
		
		$student           = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID));
		$mandatorySubjects  = $this->subject_m->general_get_order_by_subject(array('type' => 1, 'classesID' => $url));
		if (customCompute($student)) {
			if ($student->sroptionalsubjectID > 0) {
				$optionalSubject = $this->subject_m->general_get_order_by_subject(array('type' => 0, 'classesID' => $url, 'subjectID' => $student->sroptionalsubjectID));
				if (customCompute($optionalSubject)) {
					$mandatorySubjects[] = (object) $optionalSubject[0];
				}
			}
		}
		
		$attendacneArray = [];
		$totalDayCount   = [];
		$attendanceNoteArray = [];
		if (customCompute($mandatorySubjects)) {
			foreach ($mandatorySubjects as $mandatorySubject) {
				if (customCompute($schoolYearMonths)) {
					foreach ($schoolYearMonths as $schoolYearMonth) {
						for ($i = 1; $i <= 31; $i++) {
							$d = sprintf('%02d', $i);
							$date = $d . "-" . $schoolYearMonth;

							if (!isset($totalDayCount[$mandatorySubject->subjectID]['totalholiday'])) {
								$totalDayCount[$mandatorySubject->subjectID]['totalholiday'] = 0;
							}

							if (!isset($totalDayCount[$mandatorySubject->subjectID]['totalweekend'])) {
								$totalDayCount[$mandatorySubject->subjectID]['totalweekend'] = 0;
							}

							if (!isset($totalDayCount[$mandatorySubject->subjectID]['totalleave'])) {
								$totalDayCount[$mandatorySubject->subjectID]['totalleave'] = 0;
							}

							if (!isset($totalDayCount[$mandatorySubject->subjectID]['totalpresent'])) {
								$totalDayCount[$mandatorySubject->subjectID]['totalpresent'] = 0;
							}

							if (!isset($totalDayCount[$mandatorySubject->subjectID]['totallatewithexcuse'])) {
								$totalDayCount[$mandatorySubject->subjectID]['totallatewithexcuse'] = 0;
							}

							if (!isset($totalDayCount[$mandatorySubject->subjectID]['totallate'])) {
								$totalDayCount[$mandatorySubject->subjectID]['totallate'] = 0;
							}

							if (!isset($totalDayCount[$mandatorySubject->subjectID]['totalabsent'])) {
								$totalDayCount[$mandatorySubject->subjectID]['totalabsent'] = 0;
							}

							if (in_array($date, $holidays)) {
								$attendacneArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] = 'H';
								$attendanceNoteArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] ="H";
								$totalDayCount[$mandatorySubject->subjectID]['totalholiday']++;
							} elseif (in_array($date, $weekends)) {
								$attendacneArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] = 'W';
								$attendanceNoteArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] ="W";
								$totalDayCount[$mandatorySubject->subjectID]['totalweekend']++;
							} elseif (in_array($date, $leaves)) {
								$attendacneArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] = 'LA';
								$attendanceNoteArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] ="LA";
								$totalDayCount[$mandatorySubject->subjectID]['totalleave']++;
							} else {
								$a = 'a' . $i;
							
								if (isset($attendances[$mandatorySubject->subjectID][$schoolYearMonth]) && $attendances[$mandatorySubject->subjectID][$schoolYearMonth]->$a != null) {
									if(isset($attendance_notes[$mandatorySubject->subjectID][$schoolYearMonth]) ){
										$attendanceNoteArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] = $attendance_notes[$mandatorySubject->subjectID][$schoolYearMonth]->$a;
									}
									$attendacneArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] = $attendances[$mandatorySubject->subjectID][$schoolYearMonth]->$a;
									
									if ($attendances[$mandatorySubject->subjectID][$schoolYearMonth]->$a == 'P') {
										$totalDayCount[$mandatorySubject->subjectID]['totalpresent']++;
									} elseif ($attendances[$mandatorySubject->subjectID][$schoolYearMonth]->$a == 'LE') {
										$totalDayCount[$mandatorySubject->subjectID]['totallatewithexcuse']++;
									} elseif ($attendances[$mandatorySubject->subjectID][$schoolYearMonth]->$a == 'L') {
										$totalDayCount[$mandatorySubject->subjectID]['totallate']++;
									} elseif ($attendances[$mandatorySubject->subjectID][$schoolYearMonth]->$a == 'A') {
										$totalDayCount[$mandatorySubject->subjectID]['totalabsent']++;
									}
								} else {
									$attendacneArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] = 'N/A';
									$attendanceNoteArray[$mandatorySubject->subjectID][$schoolYearMonth][$i] = 'N/A';
								}
								
							};
						}
					}
				}
			}
		}

		$retArray = ['attendance' => $attendacneArray,'attendance_sub_note' => $attendanceNoteArray, 'totalcount' => $totalDayCount, 'subjects' => $mandatorySubjects];
		return $retArray;
	}

	private function dayattendance($id = null, $url = null)
	{
		$schoolyearID 		= $this->session->userdata('defaultschoolyearID');
		$attendances 		= $this->sattendance_m->get_order_by_attendance(array("studentID" => $id, "classesID" => $url, 'schoolyearID' => $schoolyearID));

		$attendance_notes = [];
		foreach ($attendances as $att) {
			$attendance_note = $this->sattendance_m->get_order_by_attendance_with_note(array("attendanceID" => $att->attendanceID));
			array_push($attendance_notes, $attendance_note);
		}

		$attendances 		= pluck($attendances, 'obj', 'monthyear');
		$attendance_notes = pluck($attendance_notes, 'obj', 'monthyear');
		
		$schoolYearMonths 	= $this->schoolYearMonth($this->data['schoolyearsessionobj']);
		$holidays 			= $this->getHolidaysSession();
		$weekends 			= $this->getWeekendDaysSession();
		$leaves 			= $this->leaveApplicationsDateListByUser($id, $schoolyearID);

		$attendacneArray = [];
		$attendanceNoteArray = [];
		if (customCompute($schoolYearMonths)) {
			foreach ($schoolYearMonths as $schoolYearMonth) {
				for ($i = 1; $i <= 31; $i++) {
					$d = sprintf('%02d', $i);
					$date = $d . "-" . $schoolYearMonth;

					if (!isset($totalDayCount['totalholiday'])) {
						$totalDayCount['totalholiday'] = 0;
					}

					if (!isset($totalDayCount['totalweekend'])) {
						$totalDayCount['totalweekend'] = 0;
					}

					if (!isset($totalDayCount['totalleave'])) {
						$totalDayCount['totalleave'] = 0;
					}

					if (!isset($totalDayCount['totalpresent'])) {
						$totalDayCount['totalpresent'] = 0;
					}

					if (!isset($totalDayCount['totallatewithexcuse'])) {
						$totalDayCount['totallatewithexcuse'] = 0;
					}

					if (!isset($totalDayCount['totallate'])) {
						$totalDayCount['totallate'] = 0;
					}

					if (!isset($totalDayCount['totalabsent'])) {
						$totalDayCount['totalabsent'] = 0;
					}

					if (in_array($date, $holidays)) {
						$attendacneArray[$schoolYearMonth][$i] = 'H';
						$totalDayCount['totalholiday']++;
					} elseif (in_array($date, $weekends)) {
						$attendacneArray[$schoolYearMonth][$i] = 'W';
						$totalDayCount['totalweekend']++;
					} elseif (in_array($date, $leaves)) {
						$attendacneArray[$schoolYearMonth][$i] = 'LA';
						$totalDayCount['totalleave']++;
					} else {
						$a = 'a' . $i;
						if (isset($attendances[$schoolYearMonth]) && $attendances[$schoolYearMonth]->$a != null) {
							$attendacneArray[$schoolYearMonth][$i] = $attendances[$schoolYearMonth]->$a;

							if ($attendances[$schoolYearMonth]->$a == 'P') {
								$totalDayCount['totalpresent']++;
							} elseif ($attendances[$schoolYearMonth]->$a == 'LE') {
								$totalDayCount['totallatewithexcuse']++;
							} elseif ($attendances[$schoolYearMonth]->$a == 'L') {
								$totalDayCount['totallate']++;
							} elseif ($attendances[$schoolYearMonth]->$a == 'A' || $attendances[$schoolYearMonth]->$a == 'AN') {
								$totalDayCount['totalabsent']++;
							}
						} else {
							$attendacneArray[$schoolYearMonth][$i] = 'N/A';
						}
					};

					if (in_array($date, $holidays)) {
						foreach ($holidays as $holiday) {
							$converted_date = date('Y-m-d', strtotime($holiday));
							$day = date('d', strtotime($holiday));
							if ($day == $i) {
								$h = $this->holiday_m->get_holiday_by_date($converted_date);
								if (isset($h)) $attendanceNoteArray[$schoolYearMonth][$i] = $h->title;
							}
						}
					} else {
						$a = 'a' . $i;
						if (isset($attendance_notes[$schoolYearMonth]) && $attendance_notes[$schoolYearMonth]->$a != null) {
							$attendanceNoteArray[$schoolYearMonth][$i] = $attendance_notes[$schoolYearMonth]->$a;
						} else {
							$attendanceNoteArray[$schoolYearMonth][$i] = 'N/A';
						}
					}
					// dd($totalDayCount);
				}
			}
		}

		$retArray = ['attendance' => $attendacneArray, 'attendance_note' => $attendanceNoteArray, 'totalcount' => $totalDayCount];
		return $retArray;
	}

	private function schoolYearMonth($schoolYear, $keyExist = false)
	{
		$dateArray = [];
		$startDate    = (new DateTime($schoolYear->startingdate))->modify('first day of this month');
		$endDate      = (new DateTime($schoolYear->endingdate))->modify('last day of this month');
		$dateInterval = DateInterval::createFromDateString('1 month');
		$datePeriods   = new DatePeriod($startDate, $dateInterval, $endDate);

		if (customCompute($datePeriods)) {
			foreach ($datePeriods as $datePeriod) {
				if ($keyExist) {
					$dateArray[] = ['monthkey' => $datePeriod->format("m") . '-' . $datePeriod->format("Y"), 'monthname' => $datePeriod->format("M")];
				} else {
					$dateArray[] = $datePeriod->format("m-Y");
				}
			}
		}
		return $dateArray;
	}

	private function leaveApplicationsDateListByUser($studentID, $schoolyearID)
	{
		$leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication(array('create_userID' => $studentID, 'create_usertypeID' => 3, 'schoolyearID' => $schoolyearID, 'status' => 1));

		$retArray = [];
		if (customCompute($leaveapplications)) {
			$oneday    = 60 * 60 * 24;
			foreach ($leaveapplications as $leaveapplication) {
				for ($i = strtotime($leaveapplication->from_date); $i <= strtotime($leaveapplication->to_date); $i = ($i + $oneday)) {
					$retArray[] = date('d-m-Y', $i);
				}
			}
		}
		return $retArray;
	}

	public function view_get($id = null, $url = null)
	{
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if ((int)$id && (int)$url) {
			$this->retdata['attendanceType'] = 'day';
			if ($this->data['siteinfos']->attendance == "subject") {
				$this->retdata['attendanceType'] = 'subject';
			}

			$this->retdata["student"] = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID));
			$this->retdata["classes"] = $this->classes_m->get_single_classes(array('classesID' => $url));
			if (customCompute($this->retdata["student"]) && customCompute($this->retdata["classes"])) {
				$this->retdata['classesID'] = $url;
				$this->retdata["section"] 	= $this->section_m->general_get_single_section(array('sectionID' => $this->retdata['student']->srsectionID));
				$this->retdata["usertype"] 	= $this->usertype_m->get_single_usertype(array('usertypeID' => $this->retdata["student"]->usertypeID));
				$this->retdata['attendancesmonths'] = $this->schoolYearMonth($this->data['schoolyearsessionobj'], true);

				if ($this->data['siteinfos']->attendance == "subject") {
					$attendance = $this->subjectattendance($id, $url);
					$this->retdata['attendance'] = $attendance['attendance'];
					$this->retdata['attendance_note'] = $attendance['attendance_sub_note'];
					$this->retdata['totalcount'] = $attendance['totalcount'];
					$this->retdata['subjects']   = $attendance['subjects'];
				} else {
					$attendance = $this->dayattendance($id, $url);
					$this->retdata['attendance'] = $attendance['attendance'];
					$this->retdata['attendance_note'] = $attendance['attendance_note'];
					$this->retdata['totalcount'] = $attendance['totalcount'];
					$this->retdata['school_days'] = $this->setting_m->get_setting()->schoolDays;
				}
				
				$this->response([
					'status' => true,
					'message' => 'Success',
					'data' => $this->retdata
				], REST_Controller::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Error 404',
					'data' => []
				], REST_Controller::HTTP_NOT_FOUND);
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'Error 404',
				'data' => []
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function add_post()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1 || $this->session->userdata('usertypeID') == 2)) {
			$this->retdata['sattendanceinfo'] = array();
			$this->retdata['classesID'] = 0;
			$this->retdata['date'] = date("d-m-Y");
			$this->retdata['day'] = 0;
			$this->retdata['monthyear'] = 0;

			$this->retdata['attendanceType'] = 'day';
			if ($this->data['siteinfos']->attendance == "subject") {
				$this->retdata['attendanceType'] = 'subject';
			}

			$this->retdata['classes'] = $this->classes_m->get_classes();
			$this->retdata['students'] = [];
			$classesID = inputCall("classesID");


			if ($classesID != 0 && $this->data['siteinfos']->attendance == "subject") {
				$this->retdata['subjects'] = $this->subject_m->general_get_order_by_subject(array("classesID" => $classesID));
			} else {
				$this->retdata['subjects'] = [];
			}


			if ($classesID != 0 && $classesID != '') {
				$this->retdata['sections'] = $this->section_m->general_get_order_by_section(array("classesID" => $classesID));
			} else {
				$this->retdata['sections'] = [];
			}


			$this->retdata['calenderdisableweekdays'] 	= (($this->data['siteinfos']->weekends != '') ? explode(',', $this->data['siteinfos']->weekends) : []);;
			$this->retdata['calenderfromdate'] 			= date('Y-m-d', strtotime($this->data['schoolyearsessionobj']->startingdate));
			$this->retdata['calendertodate'] 			= date('Y-m-d', strtotime($this->data['schoolyearsessionobj']->endingdate));
			$this->retdata['calenderdisabledates'] 		= $this->getHolidayssession(false);


			$this->retdata['subjectID'] = 0;
			$this->retdata['sectionID'] = 0;

			if (inputCall()) {
				if ($this->data['siteinfos']->attendance == "subject") {
					$rules = $this->subject_rules();
				} else {
					$rules = $this->rules();
				}

				$_POST = inputCall();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->retdata2['validation'] = $this->form_validation->error_array();
					$this->response([
						'status' => false,
						'message' => 'Error 404',
						'data' => $this->retdata2,
					], REST_Controller::HTTP_NOT_FOUND);
				} else {
					$classesID = inputCall("classesID");
					$sectionID = inputCall("sectionID");
					$subjectID = inputCall("subjectID");
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$userID = $this->session->userdata('loginuserID');
					$usertype = $this->session->userdata('usertype');

					if ($this->data['siteinfos']->attendance == "subject") {
						$subjectID = inputCall("subjectID");
						$this->retdata['subjectID'] = $subjectID;
						$subjectInfo =  $this->subject_m->get_subject($subjectID);
						$this->retdata['sattendanceinfo']['subject'] = $subjectInfo->subject;
					}

					if ($sectionID != 0) {
						$this->retdata['sectionID'] = $sectionID;
					}

					$date = inputCall("date");
					$this->retdata['classesID'] = $classesID;
					$this->retdata['date'] = $date;
					$explode_date = explode("-", $date);
					$monthyear = $explode_date[1] . "-" . $explode_date[2];

					$studentQuery = ['srschoolyearID' => $schoolyearID, "srclassesID" => $classesID, 'srsectionID' => $sectionID];
					if ($this->data['siteinfos']->attendance == "subject") {
						if ($subjectInfo->type === '0') {
							$studentQuery['sroptionalsubjectID'] = $subjectID;
						}
					}

					$students = $this->studentrelation_m->general_get_order_by_student($studentQuery);
					$studentArray = [];
					$this->retdata['attendances'] = [];
					if (customCompute($students)) {
						if ($this->data['siteinfos']->attendance == "subject") {
							$attendance_monthyear = pluck($this->subjectattendance_m->get_order_by_sub_attendance(array('schoolyearID' => $schoolyearID, "classesID" => $classesID, 'sectionID' => $sectionID, "subjectID" => $subjectID, "monthyear" => $monthyear)), 'obj', 'studentID');
						} else {
							$attendance_monthyear = pluck($this->sattendance_m->get_order_by_attendance(array('schoolyearID' => $schoolyearID, "classesID" => $classesID, 'sectionID' => $sectionID, "monthyear" => $monthyear)), 'obj', 'studentID');
						}

						foreach ($students as $student) {
							if (!isset($attendance_monthyear[$student->studentID])) {
								if ($this->data['siteinfos']->attendance == "subject") {
									if ($subjectInfo->type === '1') {
										$studentArray[] = array(
											"studentID" => $student->studentID,
											'schoolyearID' => $schoolyearID,
											"classesID" => $classesID,
											'sectionID' => $sectionID,
											"subjectID" => $subjectID,
											"userID" => $userID,
											"usertype" => $usertype,
											"monthyear" => $monthyear
										);
									} else {
										if ($student->sroptionalsubjectID == inputCall("subjectID")) {
											$studentArray[] = array(
												"studentID" => $student->studentID,
												'schoolyearID' => $schoolyearID,
												"classesID" => $classesID,
												'sectionID' => $sectionID,
												"subjectID" => $subjectID,
												"userID" => $userID,
												"usertype" => $usertype,
												"monthyear" => $monthyear
											);
										}
									}
								} else {
									$studentArray[] = array(
										"studentID" => $student->studentID,
										'schoolyearID' => $schoolyearID,
										"classesID" => $classesID,
										'sectionID' => $sectionID,
										"userID" => $userID,
										"usertype" => $usertype,
										"monthyear" => $monthyear
									);
								}
							}
						}

						if (customCompute($studentArray)) {
							if ($this->data['siteinfos']->attendance == "subject") {
								$this->subjectattendance_m->insert_batch_sub_attendance($studentArray);
							} else {
								$this->sattendance_m->insert_batch_attendance($studentArray);
							}
						}

						if ($this->data['siteinfos']->attendance == "subject") {
							$this->retdata['attendances'] = pluck($this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classesID, 'sectionID' => $sectionID, 'subjectID' => $subjectID, 'schoolyearID' => $schoolyearID, 'monthyear' => $monthyear)), 'obj', 'studentID');
						} else {
							$attendances = $this->sattendance_m->get_order_by_attendance(array('classesID' => $classesID, 'sectionID' => $sectionID, 'schoolyearID' => $schoolyearID, 'monthyear' => $monthyear));
							$attendance_notes = [];
							foreach ($attendances as $attendance) {
								$attendance_note = $this->attendance_note_m->get_single_attendance_note(['attendanceID' => $attendance->attendanceID]);
								if ($attendance_note) {
									$attendance_notes[$attendance->studentID] = $attendance_note;
								}
							}
							$this->retdata['attendances'] = pluck($attendances, 'obj', 'studentID');
							$this->retdata['attendance_notes'] = $attendance_notes;
						}
					}

					$this->retdata['students'] = $students;

					$this->retdata['monthyear'] = $monthyear;
					$this->retdata['day'] = (string)(int)$explode_date[0];
					$this->retdata['sattendanceinfo']['class'] = $this->classes_m->general_get_classes($classesID)->classes;
					$this->retdata['sattendanceinfo']['section'] = $this->section_m->general_get_section($sectionID)->section;
					$this->retdata['sattendanceinfo']['day'] = date('l', strtotime($date));
					$this->retdata['sattendanceinfo']['date'] = date('jS F Y', strtotime($date));

					$this->response([
						'status' => true,
						'message' => 'Success',
						'data' => $this->retdata
					], REST_Controller::HTTP_OK);
				}
			} else {
				$this->response([
					'status' => true,
					'message' => 'Success',
					'data' => $this->retdata
				], REST_Controller::HTTP_OK);
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'Error 404',
				'data' => []
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function yearly_post()
	{
		if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1 || $this->session->userdata('usertypeID') == 2)) {
			$this->retdata['sattendanceinfo'] = array();
			$this->retdata['classesID'] = 0;
			$this->retdata['date'] = date("d-m-Y");

			$this->retdata['attendanceType'] = 'day';
			if ($this->data['siteinfos']->attendance == "subject") {
				$this->retdata['attendanceType'] = 'subject';
			}

			$this->retdata['classes'] = $this->classes_m->get_classes();
			$this->retdata['students'] = [];
			$classesID = inputCall("classesID");

			if ($classesID != 0 && $this->data['siteinfos']->attendance == "subject") {
				$this->retdata['subjects'] = $this->subject_m->get_order_by_subject(array("classesID" => $classesID));
			} else {
				$this->retdata['subjects'] = [];
			}

			if ($classesID != 0 && $classesID != '') {
				$this->retdata['sections'] = $this->section_m->get_order_by_section(array("classesID" => $classesID));
			} else {
				$this->retdata['sections'] = [];
			}


			$this->retdata['calenderdisableweekdays'] 	= (($this->data['siteinfos']->weekends != '') ? explode(',', $this->data['siteinfos']->weekends) : []);;
			$this->retdata['calenderfromdate'] 			= date('Y-m-d', strtotime($this->data['schoolyearsessionobj']->startingdate));
			$this->retdata['calendertodate'] 			= date('Y-m-d', strtotime($this->data['schoolyearsessionobj']->endingdate));
			$this->retdata['calenderdisabledates'] 		= $this->getHolidayssession(false);


			$this->retdata['subjectID'] = 0;
			$this->retdata['sectionID'] = 0;

			if (inputCall()) {
				if ($this->data['siteinfos']->attendance == "subject") {
					$rules = $this->subject_rules();
				} else {
					$rules = $this->annual_rules();
				}

				$_POST = inputCall();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->retdata2['validation'] = $this->form_validation->error_array();
					$this->response([
						'status' => false,
						'message' => 'Error 404',
						'data' => $this->retdata2,
					], REST_Controller::HTTP_NOT_FOUND);
				} else {
					$classesID = inputCall("classesID");
					$sectionID = inputCall("sectionID");
					$subjectID = inputCall("subjectID");
					$schoolyearID = $this->session->userdata('defaultschoolyearID');
					$userID = $this->session->userdata('loginuserID');
					$usertype = $this->session->userdata('usertype');

					if ($this->data['siteinfos']->attendance == "subject") {
						$subjectID = inputCall("subjectID");
						$this->retdata['subjectID'] = $subjectID;
						$subjectInfo =  $this->subject_m->get_subject($subjectID);
						$this->retdata['sattendanceinfo']['subject'] = $subjectInfo->subject;
					}

					if ($sectionID != 0) {
						$this->retdata['sectionID'] = $sectionID;
					}

					$this->retdata['classesID'] = $classesID;

					$studentQuery = ['srschoolyearID' => $schoolyearID, "srclassesID" => $classesID, 'srsectionID' => $sectionID];
					if ($this->data['siteinfos']->attendance == "subject") {
						if ($subjectInfo->type === '0') {
							$studentQuery['sroptionalsubjectID'] = $subjectID;
						}
					}

					$students = $this->studentrelation_m->get_order_by_student($studentQuery);
					$attendances = $this->sattendance_m->get_order_by_attendance(array('classesID' => $classesID, 'sectionID' => $sectionID, 'schoolyearID' => $schoolyearID));
					$attendance_notes = [];
					foreach ($attendances as $attendance) {
						$attendance_note = $this->attendance_note_m->get_single_attendance_note(['attendanceID' => $attendance->attendanceID]);
						if ($attendance_note) {
							array_push($attendance_notes, $attendance_note);
						}
					}
					$this->retdata['attendances'] = $attendances;
					$this->retdata['attendance_notes'] = $attendance_notes;

					$this->retdata['students'] = $students;
					$this->retdata['sattendanceinfo']['class'] = $this->classes_m->get_classes($classesID)->classes;
					$this->retdata['sattendanceinfo']['section'] = $this->section_m->get_section($sectionID)->section;

					$this->response([
						'status' => true,
						'message' => 'Success',
						'data' => $this->retdata
					], REST_Controller::HTTP_OK);
				}
			} else {
				$this->response([
					'status' => true,
					'message' => 'Success',
					'data' => $this->retdata
				], REST_Controller::HTTP_OK);
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'Error 404',
				'data' => []
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function saveattendance_post()
	{
		if (inputCall()) {

			$day = inputCall('day');
			$classesID = inputCall('classesID');
			$sectionID = inputCall('sectionID');
			$subjectID = inputCall('subjectID');
			$monthyear = inputCall('monthyear');
			$attendance = inputCall('attendance');
			$attendance_notes = inputCall('attendance_notes');
			$schoolyearID = $this->session->userdata('defaultschoolyearID');

			$_POST = inputCall();

			$rules = $this->attendance_rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == false) {
				$this->retdata2['validation'] = $this->form_validation->error_array();
				$this->response([
					'status' => false,
					'message' => 'Validation Error',
					'data' => $this->retdata2,
				], REST_Controller::HTTP_NOT_FOUND);
			} else {
				$messageType = 'none';
				$f = true;

				if ($this->data['siteinfos']->attendance_notification == 'email') {
					$messageType = 'email';
					$f = true;
				} elseif ($this->data['siteinfos']->attendance_notification == 'sms') {
					$messageType = 'sms';
					$f = true;
				}

				$updateArray = [];
				$attendance = json_decode($attendance, true);
				$attendance_notes = json_decode($attendance_notes, true);

				$totalAbsent = 0;
		        $totalPresent = 0;
				foreach($attendance as $key=>$value){
                    if($value == 'P'){
                        $totalPresent = $totalPresent + 1;
					}
					if($value == 'A'){
                        $totalAbsent = $totalAbsent + 1;
					}
				}

				

				if (is_array($attendance) && customCompute($attendance)) {
					foreach ($attendance as $key => $singleAttendance) {
						$studentID = str_replace("student", "", $key);

						if ((int) $subjectID > 0) {
							$conditions = [
								'schoolyearID' => $schoolyearID,
								'studentID'    => $studentID,
								'classesID'    => $classesID,
								'sectionID'    => $sectionID,
								'subjectID'    => $subjectID,
								'monthyear'    => $monthyear,
							];
							$prevAttendance = $this->subjectattendance_m->get_single_sub_attendance($conditions);
							if ($prevAttendance) {
								$attendanceID = $prevAttendance->attendanceID;
							} else {
								$createAttendance = [
									'schoolyearID' => $schoolyearID,
									'studentID'    => $studentID,
									'classesID'    => $classesID,
									'sectionID'    => $sectionID,
									'monthyear'    => $monthyear,
									'subjectID'    => $subjectID,
									'userID'       => $this->session->userdata("loginuserID"),
									'usertype'     => $this->session->userdata("usertypeID"),
									'a' . abs($day)  => $singleAttendance
								];
								$attendanceID = $this->subjectattendance_m->insert_sub_attendance($createAttendance);
							}
						} else {
							$conditions = [
								'schoolyearID' => $schoolyearID,
								'studentID'    => $studentID,
								'classesID'    => $classesID,
								'sectionID'    => $sectionID,
								'monthyear'    => $monthyear,
							];
							$prevAttendance = $this->sattendance_m->get_attendanceByMonth($conditions);
							if ($prevAttendance) {
								$attendanceID = $prevAttendance->attendanceID;
							} else {
								$createAttendance = [
									'schoolyearID' => $schoolyearID,
									'studentID'    => $studentID,
									'classesID'    => $classesID,
									'sectionID'    => $sectionID,
									'monthyear'    => $monthyear,
									'userID'       => $this->session->userdata("loginuserID"),
									'usertype'     => $this->session->userdata("usertypeID"),
									'a' . abs($day)  => $singleAttendance
								];
								$attendanceID = $this->sattendance_m->insert_attendance1($createAttendance);
							}
						}

						$studentID = '';
						$updateArray[] = array(
							'attendanceID' 	=> $attendanceID,
							'a' . abs($day) 	=> $singleAttendance
						);
					}
				}

				if (is_array($attendance_notes) && customCompute($attendance_notes)) {
					foreach ($attendance_notes as $key => $attendance_note) {
						$studentID = str_replace("student", "", $key);

						if ((int) $subjectID > 0) {
							$conditions = [
								'schoolyearID' => $schoolyearID,
								'studentID'    => $studentID,
								'classesID'    => $classesID,
								'sectionID'    => $sectionID,
								'subjectID'    => $subjectID,
								'monthyear'    => $monthyear,
							];

							$prevAttendance = $this->subjectattendance_m->get_single_sub_attendance($conditions);

							$updateNoteArray = array(
								'attendanceID' 	=> $prevAttendance->attendanceID,
								'subjectID'    => $subjectID,
								'a' . abs($day) 	=> $attendance_note
							);

							$attendance_notes = $this->sub_attend_note_m->get_single_sub_attendance_note(['attendanceID' => $prevAttendance->attendanceID]);

							if ($attendance_notes) {
								$this->sub_attend_note_m->update_sub_attendance_note($updateNoteArray, $attendance_notes->subattnoteID);
							} else {
								$this->sub_attend_note_m->insert_sub_attendance_note($updateNoteArray);
							}
						} else {
							$conditions = [
								'schoolyearID' => $schoolyearID,
								'studentID'    => $studentID,
								'classesID'    => $classesID,
								'sectionID'    => $sectionID,
								'monthyear'    => $monthyear,
							];
							$prevAttendance = $this->sattendance_m->get_attendanceByMonth($conditions);

							$updateNoteArray = array(
								'attendanceID' 	=> $prevAttendance->attendanceID,
								'a' . abs($day) 	=> $attendance_note
							);

							$attendance_notes = $this->attendance_note_m->get_single_attendance_note(['attendanceID' => $prevAttendance->attendanceID]);
							if ($attendance_notes) {
								$this->attendance_note_m->update_attendance_note($updateNoteArray, $attendance_notes->attnoteID);
							} else {
								$this->attendance_note_m->insert_attendance_note($updateNoteArray);
							}
						}
					}
				}

				$updateStatus = false;
				if (customCompute($updateArray)) {
					if ($this->data['siteinfos']->attendance == "subject") {
						//$this->subjectattendance_m->update_batch_sub_attendance($updateArray, 'attendanceID');
						if ($this->db->update_batch('sub_attendance', $updateArray, 'attendanceID')) {
							$updateStatus = true;
						}
						
					} else {
						if ($this->db->update_batch('attendance', $updateArray, 'attendanceID')) {
							$updateStatus = true;
						}
						// $this->sattendance_m->update_batch_attendance($updateArray, 'attendanceID');
						
					}
				}


				// if ($f) {
					if ($this->data['siteinfos']->attendance == "subject") {
						$data = array('a' . abs($day) => 'A', 'schoolyearID' => $schoolyearID, 'classesID' => $classesID, 'sectionID' => $sectionID, 'monthyear' => $monthyear, 'subjectID' => $subjectID);
						$students = $this->subjectattendance_m->get_order_by_sub_attendance($data);
					} else {
						$data = array('a' . abs($day) => 'A', 'schoolyearID' => $schoolyearID, 'classesID' => $classesID, 'sectionID' => $sectionID, 'monthyear' => $monthyear);
						$students = $this->sattendance_m->get_order_by_attendance($data);
					}

					if (customCompute($students)) {
						// if ($messageType == 'email') {
						// 	$this->sendAbsentEmail($students, $schoolyearID, $classesID, $sectionID);
						// } elseif ($messageType == 'sms') {
						// 	$this->sendAbsentSMS($students, $schoolyearID, $classesID, $sectionID);
						// }
						// $this->sendPushNotification($students, $day, $schoolyearID,$subjectID);
					}
				// }

				if ($updateStatus) {
				
					$this->sendPushNotification($students, $day, $schoolyearID,$subjectID);

					
					$datevalue = $day.'-'.$monthyear;
					$this->sendNotificationToAdmins($classesID,$datevalue,$subjectID,$totalAbsent,$totalPresent);

					$this->response([
						'status' => true,
						'message' => 'Success',
						'data' => new stdClass()
					], REST_Controller::HTTP_OK);
				} else {
					$this->response([
						'status' => false,
						'message' => 'Attendance data does not found',
						'data' => []
					], REST_Controller::HTTP_NOT_FOUND);
				}
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'The post method does not found',
				'data' => []
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}



	public function sendNotificationToAdmins($classesid,$datevalue,$subjectID,$totalAbsent,$totalPresent){
             
		$permission = $this->permission_m->general_get_single_permission(['name' => 'attendance_notification']);
		if($permission){ 
			  $userTypes = $this->permission_m->usertypeByPermissionID($permission->permissionID);
			  if(count($userTypes) > 0){

				  $className = $this->classes_m->getClassByID(['classesID' => $classesid]);


				  if($this->data['siteinfos']->attendance == "subject"){
					$subjectName = $this->subject_m->general_get_single_subject(['subjectID' => $subjectID]);
					$title = 'Attendance of class '.$className->classes.'of subject '.$subjectName->subject.' on '.$datevalue;
					$notice = 'Attendance of class '.$className->classes.' of subject '.$subjectName->subject. ' on '.$datevalue.' has been taken. Total present student is '.$totalPresent.' and total absent student is: '.$totalAbsent;
				  }else{
					$title = 'Attendance of class '.$className->classes.' on '.$datevalue;
					$notice = 'Attendance of class '.$className->classes.' on '.$datevalue.' has been taken. Total present student is '.$totalPresent.' and total absent student is: '.$totalAbsent;
				  }

				   
				  
					 $admins = [];
					 $teachers = [];
					 $users = [];
					 $usersArray = [];
					 foreach($userTypes as $userType){
					   if($userType->usertype_id == 1){
							  $admins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
							  if(count($admins) > 0){
								 foreach ($admins as $admin) {
									 $usersArray[] = $admin['ID'] . $admin['usertypeID'];
								 }
							  }
					   }elseif($userType->usertype_id == 2){
							  $teachers = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
							  if(count($teachers) > 0){
								 foreach ($teachers as $teacher) {
									 $usersArray[] = $teacher['ID'] . $teacher['usertypeID'];
								 }
							  }
					   }else{
							  $users = $this->user_m->getAllActiveUsers(['active' => 1,'usertypeID' => $userType->usertype_id]);
							  if(count($users) > 0){
								 foreach ($users as $user) {
									 $usersArray[] = $user['ID'] . $user['usertypeID'];
								 }
							  }
					   }
					 }
			 if(customCompute($usersArray)){

				  $sall_users = serialize($usersArray);

				 // start
				 $arrayData = [
					 "title" => $title,
					 "users" => $sall_users,
					 "notice" => $notice,
					 "status" => 'private',
					 'schoolyearID' =>  $this->session->userdata('defaultschoolyearID'),
					 "date" => date("Y-m-d"),
					 "create_date" => date("Y-m-d H:i:s"),
					 "create_userID" => $this->session->userdata('loginuserID'),
					 "create_usertypeID" => $this->session->userdata('usertypeID'),
					 "show_to_creator"   => 0
				 ];
				 $this->notice_m->insert_notice($arrayData);
				 $noticeID = $this->db->insert_id();
 
				 if ($noticeID) {
					 // insert feed
					 $this->feed_m->insert_feed(
						 array(
							 'itemID'            => $noticeID,
							 'userID'            => $this->session->userdata("loginuserID"),
							 'usertypeID'        => $this->session->userdata('usertypeID'),
							 'itemname'          => 'notice',
							 'schoolyearID'      => $this->session->userdata('defaultschoolyearID'),
							 'published'         => 1,
							 'published_date'    => date("Y-m-d"),
							 'status'            => 'private',
							 "show_to_creator"   => 0
						 )
					 );
					 $feedID = $this->db->insert_id();


					 $noticeUsers = [];
					 $feedUsers = [];
					 foreach ($usersArray as $all_user) {
						 $user_id = substr($all_user, 0, -1);
						 $user_type = substr($all_user, -1);
						 $noticeUsers[] = [
							 'notice_id'  => $noticeID,
							 'user_id'    => (int)$user_id,
							 'usertypeID' => (int)$user_type
						 ];
						 $feedUsers[] = [
							 'feed_id'    => $feedID,
							 'user_id'    => (int)$user_id,
							 'usertypeID' => (int)$user_type
						 ];
					 }

					 $this->notice_m->insert_batch_notice_user($noticeUsers);
					 $this->feed_m->insert_batch_feed_user($feedUsers);	

				 }

				 // end


				  $this->job_m->insert_job([
					 'name'      => 'sendNotice',
					 'itemID'    => $noticeID,
					 'payload'   => json_encode([
						 'title' => $title,  // title is necessary
						 'users' => $sall_users,
					 ]),
				 ]);
		 
				 $this->mobile_job_m->insert_job([
					 'name'        => 'sendNotice',
					 'itemID'      => $noticeID,
					 'payload'     => json_encode([
						 'title'   => $title,  // title is necessary
						 'users'   => $sall_users,
						 'message' => $notice
					 ]),
				 ]);

			  }	

			  }
		}

  }

	protected function rules()
	{
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => 'class',
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classes'
			),
			array(
				'field' => 'sectionID',
				'label' => 'section',
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_section'
			),
			array(
				'field' => 'date',
				'label' => 'date',
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid|callback_valid_future_date|callback_check_holiday|callback_check_weekendday|callback_check_session_year_date'
			)
		);
		return $rules;
	}

	protected function annual_rules()
	{
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => 'class',
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classes'
			),
			array(
				'field' => 'sectionID',
				'label' => 'section',
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_section'
			),
		);
		return $rules;
	}

	protected function subject_rules()
	{
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => 'class',
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classes'
			),
			array(
				'field' => 'sectionID',
				'label' => 'section',
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_section'
			),
			array(
				'field' => 'subjectID',
				'label' => 'subject',
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_subject'
			),
			array(
				'field' => 'date',
				'label' => 'date',
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid|callback_valid_future_date|callback_check_holiday|callback_check_weekendday'
			)
		);
		return $rules;
	}

	protected function attendance_rules()
	{
		$rules = array(
			array(
				'field' => 'day',
				'label' => $this->lang->line("sattendance_day"),
				'rules' => 'trim|required|numeric|xss_clean|max_length[11]'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("sattendance_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("sattendance_section"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'subjectID',
				'label' => $this->lang->line("sattendance_subject"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'monthyear',
				'label' => $this->lang->line("sattendance_monthyear"),
				'rules' => 'trim|required|max_length[10]|xss_clean'
			),
			array(
				'field' => 'attendance',
				'label' => $this->lang->line("sattendance_attendance"),
				'rules' => 'trim|required|xss_clean'
			)
		);
		return $rules;
	}

	public function unique_classes()
	{
		if (inputCall('classesID') == 0) {
			$this->form_validation->set_message("unique_classes", "The %s field is required");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_section()
	{
		if (inputCall('sectionID') == 0) {
			$this->form_validation->set_message("unique_section", "The %s field is required");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_subject()
	{
		if (inputCall('subjectID') == 0) {
			$this->form_validation->set_message("unique_subject", "The %s field is required");
			return FALSE;
		}
		return TRUE;
	}

	public function date_valid($date)
	{
		if (strlen($date) < 10) {
			$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
			return FALSE;
		} else {
			$arr = explode("-", $date);
			$dd = $arr[0];
			$mm = $arr[1];
			$yyyy = $arr[2];
			if (checkdate($mm, $dd, $yyyy)) {
				return TRUE;
			} else {
				$this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
				return FALSE;
			}
		}
	}

	public function valid_future_date($date)
	{
		$presentdate = date('Y-m-d');
		$date = date("Y-m-d", strtotime($date));
		if ($date > $presentdate) {
			$this->form_validation->set_message('valid_future_date', 'The %s field does not given future date.');
			return FALSE;
		}
		return TRUE;
	}

	public function check_holiday($date)
	{
		$getHolidays = $this->getHolidaysSession();
		if (customCompute($getHolidays)) {
			if (in_array($date, $getHolidays)) {
				$this->form_validation->set_message('check_holiday', 'The %s field given holiday.');
				return FALSE;
			} else {
				return TRUE;
			}
		}
		return TRUE;
	}

	public function check_weekendday($date)
	{
		$getWeekendDays = $this->getWeekendDaysSession();
		if (customCompute($getWeekendDays)) {
			if (in_array($date, $getWeekendDays)) {
				$this->form_validation->set_message('check_weekendday', 'The %s field given weekenday.');
				return FALSE;
			} else {
				return TRUE;
			}
		}
		return TRUE;
	}

	public function check_session_year_date()
	{
		$date = strtotime(inputCall('date'));
		$startingdate = strtotime($this->data['schoolyearsessionobj']->startingdate);
		$endingdate   = strtotime($this->data['schoolyearsessionobj']->endingdate);

		if ($date < $startingdate || $date > $endingdate) {
			$this->form_validation->set_message('check_session_year_date', 'The %s field given not exits.');
			return FALSE;
		}
		return TRUE;
	}

	private function sendAbsentEmail($students, $schoolyearID, $classesID, $sectionID)  // Complete
	{
		$templateID = $this->data['siteinfos']->attendance_notification_template;
		$mailandsmstemplate = $this->mailandsmstemplate_m->get_mailandsmstemplate($templateID);
		$objStudents = pluck($this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID, 'srclassesID' => $classesID, 'srsectionID' => $sectionID), TRUE), 'obj', 'srstudentID');

		$parents = pluck($this->parents_m->get_parents(), 'email', 'parentsID');

		foreach ($students as $student) {
			$studentID = $student->studentID;
			$user = isset($objStudents[$studentID]) ? $objStudents[$studentID] : [];
			$parentsID = isset($objStudents[$studentID]) ? $objStudents[$studentID]->parentID : 0;
			$parentsEmail = isset($parents[$parentsID]) ? $parents[$parentsID] : '';

			if (customCompute($user) && $parentsID > 0 && $parentsEmail != '') {
				$user->email = $parentsEmail;
				$message = $mailandsmstemplate->template;
				$this->userConfigEmail($message, $user, 3, $schoolyearID);
			}
		}
	}

	private function sendAbsentSMS($students, $schoolyearID, $classesID, $sectionID) // Complete
	{
		$attendance_smsgateway = $this->data['siteinfos']->attendance_smsgateway;
		$templateID = $this->data['siteinfos']->attendance_notification_template;
		$mailandsmstemplate = $this->mailandsmstemplate_m->get_mailandsmstemplate($templateID);
		$objStudents = pluck($this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID, 'srclassesID' => $classesID, 'srsectionID' => $sectionID), TRUE), 'obj', 'srstudentID');

		$parents = pluck($this->parents_m->get_parents(), 'phone', 'parentsID');

		foreach ($students as $student) {
			$studentID = $student->studentID;
			$user = isset($objStudents[$studentID]) ? $objStudents[$studentID] : [];
			$parentsID = isset($objStudents[$studentID]) ? $objStudents[$studentID]->parentID : 0;
			$parentsPhonenumber = isset($parents[$parentsID]) ? $parents[$parentsID] : '';
			if (customCompute($user) && $parentsID > 0 && $parentsPhonenumber != '') {
				$user->phone = $parentsPhonenumber;
				$message = $mailandsmstemplate->template;
				$this->userConfigSMS($message, $user, 3, $attendance_smsgateway, $schoolyearID);
			}
		}
	}

	public function sendPushNotification($students, $day, $schoolyearID,$subjectID)
	{
	
		// $users = [];
		foreach ($students as $student) {
			$registered_ids = [];
			$pregistered_ids = [];
			
			// array_push($users, $student->studentID . '3');
			$users = $student->studentID . '3';

			$student_detail = $this->student_m->get_single_student(['studentID' => $student->studentID]);
			// if($student_detail->parentID != 0) {
			// 	array_push($users, $student_detail->parentID.'4');
			// }
			if (isset($student_detail) && !empty($student_detail)) {

				$notes = $this->data['siteinfos']->attendance == "subject" ? $this->sub_attend_note_m->getSubNotesbyattid($student->attendanceID, $day) : $this->attendance_note_m->getNotesbyattid($student->attendanceID, $day);
				
				if($this->data['siteinfos']->attendance == "subject"){
					$subjectName = $this->subject_m->general_get_single_subject(['subjectID' => $subjectID]);
					$noticeMsg = "Dear " . $student_detail->name . ",<br/>" . " You have been absent on " . $day . "-" . $student->monthyear . " on ".$subjectName->subject." class due to " . $notes['a' . $day] . ".<br/>" . "Thank you " . "<br/>" . $this->session->userdata("name");
				}else{
					$noticeMsg = "Dear " . $student_detail->name . ",<br/>" . " You have been absent on " . $day . "-" . $student->monthyear . " due to " . $notes['a' . $day] . ".<br/>" . "Thank you " . "<br/>" . $this->session->userdata("name");
				}
                

				$notice = [
					"title" => 'Absent Notice',
					"users" => serialize($users),
					"notice" => $noticeMsg,
					"status" => 'private',
					'schoolyearID' =>  $this->session->userdata('defaultschoolyearID'),
					"date" => date("Y-m-d"),
					"create_date" => date("Y-m-d H:i:s"),
					"create_userID" => $this->session->userdata('loginuserID'),
					"create_usertypeID" => $this->session->userdata('usertypeID'),
					"show_to_creator"   => 0
				];

				$push_student = pluck($this->fcmtoken_m->get_order_by_fcm_token(['create_userID' => $student->studentID, 'create_usertypeID' => 3]), 'fcm_token');
				if ($push_student) {
					$registered_ids = array_merge($registered_ids, $push_student);
				}

				if ($this->notice_m->insert_notice($notice)) {

					$noticeID = $this->db->insert_id();
					if ($noticeID) {
						// insert feed
						$this->feed_m->insert_feed(
							array(
								'itemID'            => $noticeID,
								'userID'            => $this->session->userdata("loginuserID"),
								'usertypeID'        => $this->session->userdata('usertypeID'),
								'itemname'          => 'notice',
								'schoolyearID'      => $this->session->userdata('defaultschoolyearID'),
								'published'         => 1,
								'published_date'    => date("Y-m-d"),
								'status'            => 'private',
								"show_to_creator"   => 0
							)
						);
						$feedID = $this->db->insert_id();
	
	
						$noticeUser = [
							'notice_id'   => $noticeID,
							'user_id'     => $student->studentID,
							'usertypeID'  => 3
						];
						$this->notice_m->insert_notice_user($noticeUser);
	
						$feedUser = [
							'feed_id'     => $feedID,
							'user_id'     => $student->studentID,
							'usertypeID'  => 3
						];
						$this->feed_m->insert_feed_user($feedUser);
						
					}



					$push_message['data'] = [
						'message' => $notice['notice'],
						'title' => $notice['title'],
						'action' => 'attendance'
					];
					sendNotification($registered_ids, $push_message);

				}

                // notification to parents

				if ($student_detail->parentID != 0) {
					$users =  $student_detail->parentID . '4';
					$push_parent = pluck($this->fcmtoken_m->get_order_by_fcm_token(['create_userID' => $student_detail->parentID, 'create_usertypeID' => 4]), 'fcm_token');
					if ($push_parent) {
						$pregistered_ids = array_merge($pregistered_ids, $push_parent);
					}
					$parentname = $this->student_m->get_parent_by_id($student_detail->parentID);


					if($this->data['siteinfos']->attendance == "subject"){
						$pnoticeMsg = "Dear " . $parentname->name . ",<br/> Your Child " . $student_detail->name . "  has been absent on date " . $day . "-" . $student->monthyear . " on ".$subjectName->subject." class due to " . $notes['a' . $day] . ".<br/>" . "Thank you " . "<br/>" . $this->session->userdata("name");
					}else{
						$pnoticeMsg = "Dear " . $parentname->name . ",<br/> Your Child " . $student_detail->name . "  has been absent on date " . $day . "-" . $student->monthyear . " due to " . $notes['a' . $day] . ".<br/>" . "Thank you " . "<br/>" . $this->session->userdata("name");
					}

					$noticep = [
						"title" => 'Absent Notice',
						"users" => serialize($users),
						"notice" => $pnoticeMsg,
						"status" => 'private',
						'schoolyearID' =>  $this->session->userdata('defaultschoolyearID'),
						"date" => date("Y-m-d"),
						"create_date" => date("Y-m-d H:i:s"),
						"create_userID" => $this->session->userdata('loginuserID'),
						"create_usertypeID" => $this->session->userdata('usertypeID'),
						"show_to_creator"   => 0
					];

					$this->notice_m->insert_notice($noticep);
					$noticeID = $this->db->insert_id();


					if ($noticeID) {
						// insert feed
						$this->feed_m->insert_feed(
							array(
								'itemID'            => $noticeID,
								'userID'            => $this->session->userdata("loginuserID"),
								'usertypeID'        => $this->session->userdata('usertypeID'),
								'itemname'          => 'notice',
								'schoolyearID'      => $this->session->userdata('defaultschoolyearID'),
								'published'         => 1,
								'published_date'    => date("Y-m-d"),
								'status'            => 'private',
								"show_to_creator"   => 0
							)
						);
						$feedID = $this->db->insert_id();
	
						$noticeUser = [
							'notice_id'   => $noticeID,
							'user_id'     => $student_detail->parentID,
							'usertypeID'  => 4
						];
						$this->notice_m->insert_notice_user($noticeUser);
	
						$feedUser = [
							'feed_id'    => $feedID,
							'user_id'    => $student_detail->parentID,
							'usertypeID' => 4
						];
						$this->feed_m->insert_feed_user($feedUser);
						
					}

					$push_message['data'] = [
						'message' => $noticep['notice'],
						'title' => $noticep['title'],
						'action' => 'attendance'
					];
					// dd($registered_ids);
					sendNotification($pregistered_ids, $push_message);
				}

			} else {
				$this->response([
					'status' => false,
					'message' => 'Student data does not found',
					'data' => []
				], REST_Controller::HTTP_NOT_FOUND);
			}
		}
		
	}

	private function userConfigEmail($message, $user, $usertypeID, $schoolyearID)  // Complete
	{
		if ($user && $usertypeID) {
			$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => $usertypeID));

			if ($usertypeID == 2) {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 2));
			} elseif ($usertypeID == 3) {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 3));
			} elseif ($usertypeID == 4) {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 4));
			} else {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 1));
			}


			$message = $this->tagConvertor($userTags, $user, $message, 'email', $schoolyearID);

			if ($user->email) {
				$subject = inputCall('email_subject');
				$email = $user->email;
				$emailsetting = $this->emailsetting_m->get_emailsetting();
				$this->email->set_mailtype("html");
				if (customCompute($emailsetting)) {
					if ($emailsetting->email_engine == 'smtp') {
						$config = array(
							'protocol'  => 'smtp',
							'smtp_host' => $emailsetting->smtp_server,
							'smtp_port' => $emailsetting->smtp_port,
							'smtp_user' => $emailsetting->smtp_username,
							'smtp_pass' => $emailsetting->smtp_password,
							'mailtype'  => 'html',
							'charset'   => 'utf-8'
						);
						$this->email->initialize($config);
						$this->email->set_newline("\r\n");
					}

					$this->email->to($email);
					$this->email->from($this->data['siteinfos']->email, $this->data['siteinfos']->sname);
					$this->email->subject($subject);
					$this->email->message($message);
					$this->email->send();
				}
			}
		}
	}

	private function userConfigSMS($message, $user, $usertypeID, $getway, $schoolyearID = 1) // Complete
	{
		if ($user && $usertypeID) {
			$userTags = [];
			if ($usertypeID == 2) {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 2));
			} elseif ($usertypeID == 3) {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 3));
			} elseif ($usertypeID == 4) {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 4));
			} else {
				$userTags = $this->mailandsmstemplatetag_m->get_order_by_mailandsmstemplatetag(array('usertypeID' => 1));
			}

			$message = $this->tagConvertor($userTags, $user, $message, 'SMS', $schoolyearID);

			if ($user->phone) {
				$send = $this->allgetway_send_message($getway, $user->phone, $message);
				return $send;
			} else {
				$send = array('check' => TRUE);
				return $send;
			}
		}
	}

	private function tagConvertor($userTags, $user, $message, $sendType, $schoolyearID) // Complete
	{
		if (customCompute($userTags)) {
			foreach ($userTags as $key => $userTag) {
				if ($userTag->tagname == '[name]') {
					if ($user->name) {
						$message = str_replace('[name]', $user->name, $message);
					} else {
						$message = str_replace('[name]', ' ', $message);
					}
				} elseif ($userTag->tagname == '[designation]') {
					if ($user->designation) {
						$message = str_replace('[designation]', $user->designation, $message);
					} else {
						$message = str_replace('[designation]', ' ', $message);
					}
				} elseif ($userTag->tagname == '[dob]') {
					if ($user->dob) {
						$dob =  date("d M Y", strtotime($user->dob));
						$message = str_replace('[dob]', $dob, $message);
					} else {
						$message = str_replace('[dob]', ' ', $message);
					}
				} elseif ($userTag->tagname == '[gender]') {
					if ($user->sex) {
						$message = str_replace('[gender]', $user->sex, $message);
					} else {
						$message = str_replace('[gender]', ' ', $message);
					}
				} elseif ($userTag->tagname == '[religion]') {
					if ($user->religion) {
						$message = str_replace('[religion]', $user->religion, $message);
					} else {
						$message = str_replace('[religion]', ' ', $message);
					}
				} elseif ($userTag->tagname == '[email]') {
					if ($user->email) {
						$message = str_replace('[email]', $user->email, $message);
					} else {
						$message = str_replace('[email]', ' ', $message);
					}
				} elseif ($userTag->tagname == '[phone]') {
					if ($user->phone) {
						$message = str_replace('[phone]', $user->phone, $message);
					} else {
						$message = str_replace('[phone]', ' ', $message);
					}
				} elseif ($userTag->tagname == '[address]') {
					if ($user->address) {
						$message = str_replace('[address]', $user->address, $message);
					} else {
						$message = str_replace('[address]', ' ', $message);
					}
				} elseif ($userTag->tagname == '[jod]') {
					if ($user->jod) {
						$jod =  date("d M Y", strtotime($user->jod));
						$message = str_replace('[jod]', $jod, $message);
					} else {
						$message = str_replace('[jod]', ' ', $message);
					}
				} elseif ($userTag->tagname == '[username]') {
					if ($user->username) {
						$message = str_replace('[username]', $user->username, $message);
					} else {
						$message = str_replace('[username]', ' ', $message);
					}
				} elseif ($userTag->tagname == "[father's_name]") {
					if ($user->father_name) {
						$message = str_replace("[father's_name]", $user->father_name, $message);
					} else {
						$message = str_replace("[father's_name]", ' ', $message);
					}
				} elseif ($userTag->tagname == "[mother's_name]") {
					if ($user->mother_name) {
						$message = str_replace("[mother's_name]", $user->mother_name, $message);
					} else {
						$message = str_replace("[mother's_name]", ' ', $message);
					}
				} elseif ($userTag->tagname == "[father's_profession]") {
					if ($user->father_profession) {
						$message = str_replace("[father's_profession]", $user->father_profession, $message);
					} else {
						$message = str_replace("[father's_profession]", ' ', $message);
					}
				} elseif ($userTag->tagname == "[mother's_profession]") {
					if ($user->mother_profession) {
						$message = str_replace("[mother's_profession]", $user->mother_profession, $message);
					} else {
						$message = str_replace("[mother's_profession]", ' ', $message);
					}
				} elseif ($userTag->tagname == '[class]') {
					$classes = $this->classes_m->get_classes($user->srclassesID);
					if (customCompute($classes)) {
						$message = str_replace('[class]', $classes->classes, $message);
					} else {
						$message = str_replace('[class]', ' ', $message);
					}
				} elseif ($userTag->tagname == '[roll]') {
					if ($user->srroll) {
						$message = str_replace("[roll]", $user->srroll, $message);
					} else {
						$message = str_replace("[roll]", ' ', $message);
					}
				} elseif ($userTag->tagname == '[country]') {
					if ($user->country) {
						$message = str_replace("[country]", $this->data['allcountry'][$user->country], $message);
					} else {
						$message = str_replace("[country]", ' ', $message);
					}
				} elseif ($userTag->tagname == '[state]') {
					if ($user->state) {
						$message = str_replace("[state]", $user->state, $message);
					} else {
						$message = str_replace("[state]", ' ', $message);
					}
				} elseif ($userTag->tagname == '[register_no]') {
					if ($user->srregisterNO) {
						$message = str_replace("[register_no]", $user->srregisterNO, $message);
					} else {
						$message = str_replace("[register_no]", ' ', $message);
					}
				} elseif ($userTag->tagname == '[section]') {
					if ($user->srsectionID) {
						$section = $this->section_m->get_section($user->srsectionID);
						if (customCompute($section)) {
							$message = str_replace('[section]', $section->section, $message);
						} else {
							$message = str_replace('[section]', ' ', $message);
						}
					} else {
						$message = str_replace("[section]", ' ', $message);
					}
				} elseif ($userTag->tagname == '[blood_group]') {
					if ($user->bloodgroup && $user->bloodgroup != '0') {
						$message = str_replace("[blood_group]", $user->bloodgroup, $message);
					} else {
						$message = str_replace("[blood_group]", ' ', $message);
					}
				} elseif ($userTag->tagname == '[group]') {
					if ($user->srstudentgroupID && $user->srstudentgroupID != 0) {
						$group = $this->studentgroup_m->get_studentgroup($user->srstudentgroupID);
						if (customCompute($group)) {
							$message = str_replace('[group]', $group->group, $message);
						} else {
							$message = str_replace('[group]', ' ', $message);
						}
					} else {
						$message = str_replace('[group]', ' ', $message);
					}
				} elseif ($userTag->tagname == '[optional_subject]') {
					if ($user->sroptionalsubjectID && $user->sroptionalsubjectID != 0) {
						$subject = $this->subject_m->get_single_subject(array('subjectID' => $user->sroptionalsubjectID));
						if (customCompute($subject)) {
							$message = str_replace('[optional_subject]', $subject->subject, $message);
						} else {
							$message = str_replace('[optional_subject]', ' ', $message);
						}
					} else {
						$message = str_replace('[optional_subject]', ' ', $message);
					}
				} elseif ($userTag->tagname == '[extra_curricular_activities]') {
					if ($user->extracurricularactivities) {
						$message = str_replace("[extra_curricular_activities]", $user->extracurricularactivities, $message);
					} else {
						$message = str_replace("[extra_curricular_activities]", ' ', $message);
					}
				} elseif ($userTag->tagname == '[remarks]') {
					if ($user->remarks) {
						$message = str_replace("[remarks]", $user->remarks, $message);
					} else {
						$message = str_replace("[remarks]", ' ', $message);
					}
				} elseif ($userTag->tagname == '[date]') {
					$message = str_replace("[date]", date('d M Y'), $message);
				} elseif ($userTag->tagname == '[result_table]') {
					if ($sendType == 'email') {
						if ($user->usertypeID == 3) {
							$result = 'Result is disable for attendance template';
						} else {
							$result = '';
						}
						$message = str_replace("[result_table]", $result, $message);
					} elseif ($sendType == 'SMS') {
						if ($user->usertypeID == 3) {
							$result = 'Result is disable for attendance template';
						} else {
							$result = '';
						}
						$message = str_replace("[result_table]", $result, $message);
					}
				}
			}
		}
		return $message;
	}

	private function allgetway_send_message($getway, $to, $message) // Complete
	{
		$result = [];
		if ($getway == "clickatell") {
			if ($to) {
				$this->clickatell->send_message($to, $message);
				$result['check'] = TRUE;
				return $result;
			}
		} elseif ($getway == 'twilio') {
			$get = $this->twilio->get_twilio();
			$from = $get['number'];
			if ($to) {
				$response = $this->twilio->sms($from, $to, $message);
				if ($response->IsError) {
					$result['check'] = FALSE;
					$result['message'] = $response->ErrorMessage;
					return $result;
				} else {
					$result['check'] = TRUE;
					return $result;
				}
			}
		} elseif ($getway == 'bulk') {
			if ($to) {
				if ($this->bulk->send($to, $message) == TRUE) {
					$result['check'] = TRUE;
					return $result;
				} else {
					$result['check'] = FALSE;
					$result['message'] = "Check your bulk account";
					return $result;
				}
			}
		} elseif ($getway == 'msg91') {
			if ($to) {
				if ($this->msg91->send($to, $message) == TRUE) {
					$result['check'] = TRUE;
					return $result;
				} else {
					$result['check'] = FALSE;
					$result['message'] = "Check your msg91 account";
					return $result;
				}
			}
		}
	}


	public function all_class_attendance_report_post(){

		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if (permissionChecker('attendancereport')) {

			if ($_POST) {

				$attendancetype	= $this->input->post('attendancetype')?$this->input->post('attendancetype'):1;
				$date		    = explode('-', $this->input->post('date'));
				$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
				$this->data['classes']  = $classes = $this->classes_m->general_get_classes();

				$attendancetypeArray = array("P","A","LA");

				$day = 'a' . (int)$date[0];
				$monthyear = $date[1] . '-' . $date[2];

			
				$results = [];
				$totalStudents = 0;
				$totalAbsents = 0;
				$totalPresents = 0;
				$totalLaeves = 0;
				if(customCompute($classes)){
					foreach($classes as $class){
						$classObj 		= $this->classes_m->general_get_single_classes(array('classesID' => $class->classesID));
						$sections 	= $this->section_m->general_get_order_by_section(array('classesID' => $class->classesID));
		
						if(customCompute($sections)){
							foreach($sections as $section){
								$sectionObj 		= $this->section_m->general_get_single_section(array('sectionID' => $section->sectionID));
								$students = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $class->classesID, 'srsectionID' => $section->sectionID, 'srschoolyearID' => $schoolyearID));
								$totalStudents = $totalStudents + count($students);
								if($attendancetype == 1){
							          foreach($attendancetypeArray as $attendancetypeArr){
										 $attendances = $this->sattendance_m->get_order_by_attendance(array(
											 'classesID'    => $class->classesID,
											 'sectionID'    => $section->sectionID,
											 'monthyear'    => $monthyear,
											 'schoolyearID' => $schoolyearID,
											  $day       => $attendancetypeArr
											));
											$sum = $attendances?count($attendances):0;
											$type = $this->getAttendanceTypeFullname($attendancetypeArr);
											$results[$classObj->classes][$sectionObj->section][$type] = [
												'value'        => $sum,
												'totalStudent' => $students?count($students):0
											];

											if($attendancetypeArr == 'P'){
                                                  $totalPresents = $totalPresents + $sum;
											}
											if($attendancetypeArr == 'A'){
												$totalAbsents = $totalAbsents + $sum;
											}
											if($attendancetypeArr == 'LA'){
												$totalLaeves = $totalLaeves + $sum;
											}

									  }
								}else{
									$attendances = $this->sattendance_m->get_order_by_attendance(array(
										'classesID'    => $class->classesID,
										'sectionID'    => $section->sectionID,
										'monthyear'    => $monthyear,
										'schoolyearID' => $schoolyearID,
										$day       => $attendancetype
									   ));
									    $sum = $attendances?count($attendances):0;
									    $type = $this->getAttendanceTypeFullname($attendancetype);
										$results[$classObj->classes][$sectionObj->section][$type] = [
											'value'        => $sum,
											'totalStudent' => $students?count($students):0
										];
										if($attendancetype == 'P'){
										   $totalPresents = $totalPresents + $sum;
										}
										if($attendancetype == 'A'){
											$totalAbsents = $totalAbsents + $sum;
										}
										if($attendancetype == 'LA'){
											$totalLaeves = $totalLaeves + $sum;
										}
								}
							}
						}
					}
				}


				$this->retdata['typeSortForm']  = $attendancetype;
				$this->retdata['date']          = $this->input->post('date');
				$this->retdata['totalStudent']  = $totalStudents;
				$this->retdata['totalAbsents']  = $totalAbsents;
				$this->retdata['totalPresents'] = $totalPresents;
				$this->retdata['totalLeaves']   = $totalLaeves;
				$this->retdata['results']       = $results;


				$this->response([
					'status'    => true,
					'message'   => 'Success',
					'data'      => $this->retdata
				], REST_Controller::HTTP_OK);

				
				
				
			} else {
				$this->response([
                    'status'  => false,
                    'message' => 'No fields values',
                    'data'    => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response([
				'status'    => false,
				'message'   => 'User not allowed.',
				'data'      => $this->retdata
			], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
		}

	}

	public function getAttendanceTypeFullname($key){
		$attendancetypeArray = array(
			'A' => 'Absent',
			'P' => 'Present',
			'LA' => 'Leave'
		);

		return $attendancetypeArray[$key];
	}
}
