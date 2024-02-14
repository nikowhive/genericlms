<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Attendance extends Admin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("student_m");
		$this->load->model("parents_m");
		$this->load->model("sattendance_m");
		$this->load->model("attendance_note_m");
		$this->load->model("teacher_m");
		$this->load->model("teachersubject_m");
		$this->load->model("teachersection_m");
		$this->load->model("classes_m");
		$this->load->model("conversation_m");
		$this->load->model("fcmtoken_m");
		$this->load->model("notice_m");
		$this->load->model("feed_m");
		$this->load->model("alert_m");
		$this->load->model("job_m");
		$this->load->model("mobile_job_m");
		$this->load->model("user_m");
		$this->load->model("usertype_m");
		$this->load->model("section_m");
		$this->load->model("setting_m");
		$this->load->model('studentgroup_m');
		$this->load->model('subject_m');
		$this->load->model('schoolyear_m');
		$this->load->model('subjectteacher_m');
		$this->load->model('permission_m');
		$this->load->model('systemadmin_m');
		$this->load->model('studentrelation_m');
		$this->data['setting'] = $this->setting_m->get_setting();

		if ($this->data['setting']->attendance == "subject") {
			$this->load->model("subjectattendance_m");
		}
		$language = $this->session->userdata('lang');
		$this->lang->load('sattendance', $language);
	}

	protected function rules()
	{
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("attendance_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_check_classes'
			),
			array(
				'field' => 'date',
				'label' => $this->lang->line("attendance_date"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_date_valid|callback_valid_future_date|callback_check_holiday|callback_check_weekendday|callback_check_session_year_date'
			)
		);
		return $rules;
	}

	public function index()
	{


		if ($this->session->userdata('usertypeID') == 1 || $this->session->userdata('usertypeID') == 2) {
			$settings    = $this->setting_m->get_setting(); 
			if($settings->attendance == 'subject'){
				$this->data['headerassets'] = array(
					'css' => array(
						'assets/select2/css/select2.css',
						'assets/select2/css/select2-bootstrap.css',
						'assets/datepicker/datepicker.css'
					),
					'js' => array(
						'assets/select2/select2.js',
						'assets/datepicker/datepicker.js'
					)
				);
		
				$this->data['holidays'] = $this->getHolidaysano();

				// $this->data['startingtime'] = date('Y,m,d', strtotime($this->data['schoolyearsessionobj']->startingdate));
				// $this->data['endingtime'] = date('Y,m,d', strtotime($this->data['schoolyearsessionobj']->endingdate));
				
				$starttime = strtotime($this->data['schoolyearsessionobj']->startingdate);
				$endtime = strtotime($this->data['schoolyearsessionobj']->endingdate);
				$this->data['startingtime'] = date('Y,m,d', strtotime("-1 months", $starttime));
				$this->data['endingtime'] = date('Y,m,d', strtotime("-1 months", $endtime));
				
				$this->data['userType'] = $this->session->userdata('usertypeID');
		
				if (strpos($this->data['siteinfos']->weekends, ',')) {
					$weekends = explode(',', $this->data['siteinfos']->weekends);
					foreach ($weekends as $weekend) {
						$weekarray[] = $weekend + 1;
					}
					$weekarray = implode(',', $weekarray);
				} else {
					$weekarray = (int)$this->data['siteinfos']->weekends + 1;
				}
				$this->data['weekarray'] = $weekarray;
		
		
				if (!$this->uri->segment(6) && !$this->uri->segment(5) && !$this->uri->segment(3) && !$this->uri->segment(4)) {
		
		
					if ($this->session->userdata('usertypeID') == 2) {
						$teacherobjs = $this->classes_m->get_classes();
						$this->data['sectionobj']  = $this->section_m->get_single_section(array(''));
					} else {
						$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
					}
					$this->data['teacherobjs'] = $teacherobjs;
					$classesID = $this->input->post("classesID");
					$this->data['subjectID'] = '';
					if ($classesID != 0) {
						$this->data['subjects'] = $this->subject_m->get_order_by_subject(array("classesID" => $classesID));
					} else {
						$this->data['subjects'] = [];
					}
		
					$this->data["subview"] = "sub_attendance/studentlistadmin";
				} else if ($this->uri->segment(3) && $this->uri->segment(4) && $this->uri->segment(5) && !$this->uri->segment(6)) {
					$this->data['classesID'] = $this->uri->segment(3);
					$this->data['attdate'] = $this->uri->segment(4);
		
					$this->data['subjectID'] = $this->uri->segment(5);
					if ($this->session->userdata('usertypeID') == 2) {
						$teacherobjs = $this->classes_m->get_classes();
						$this->data['subjects'] = $this->subject_m->getSubjectsByTeacherID(array('teacherID' => $this->session->userdata('loginuserID'), "classesID" => $this->data['classesID']));
						$this->data['sectionobj']  = $this->section_m->get_single_section(array(''));
					} else {
						$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
						$this->data['subjects']  = $this->subject_m->get_order_by_subject(array("classesID" => $this->data['classesID']));
					}
					$this->data['teacherobjs'] = $teacherobjs;
		
					$this->data["subview"] = "sub_attendance/studentlistadmin";
				} else {
					$this->data['classesID'] = $this->uri->segment(3);
					$this->data['attdate'] = $this->uri->segment(4);
					$this->data['sectionID'] = $this->uri->segment(6);
					$this->data['subjectID'] = $this->uri->segment(5);
		
					if ($this->session->userdata('usertypeID') == 2) {
						$teacherobjs = $this->classes_m->get_classes();
						$this->data['subjects'] = $this->subject_m->getSubjectsByTeacherID(array('teacherID' => $this->session->userdata('loginuserID'), "classesID" => $this->data['classesID']));
						$this->data['sectionobj']  = $this->section_m->get_single_section(array('sectionID' => $this->data['sectionID']));
					} else {
						$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
						$this->data['subjects']  = $this->subject_m->get_order_by_subject(array("classesID" => $this->data['classesID']));
					}
		
					$this->data['teacherobjs'] = $teacherobjs;
					$this->data["subview"] = "sub_attendance/studentlist";
				}
		
				$this->load->view('_layout_main', $this->data);
			}else{
				$this->data['holidays'] = $this->getHolidaysano();
				
				// $this->data['startingtime'] = date('Y,m,d', strtotime($this->data['schoolyearsessionobj']->startingdate));
				// $this->data['endingtime'] = date('Y,m,d', strtotime($this->data['schoolyearsessionobj']->endingdate));
				
				$starttime = strtotime($this->data['schoolyearsessionobj']->startingdate);
				$endtime = strtotime($this->data['schoolyearsessionobj']->endingdate);
				$this->data['startingtime'] = date('Y,m,d', strtotime("-1 months", $starttime));
				$this->data['endingtime'] = date('Y,m,d', strtotime("-1 months", $endtime));
				
				$this->data['userType'] = $this->session->userdata('usertypeID');

			if (strpos($this->data['siteinfos']->weekends, ',')) {
				$weekends = explode(',', $this->data['siteinfos']->weekends);
				foreach ($weekends as $weekend) {
					$weekarray[] = $weekend + 1;
				}
				$weekarray = implode(',', $weekarray);
			} else {
				$weekarray = (int)$this->data['siteinfos']->weekends + 1;
			}
			$this->data['weekarray'] = $weekarray;
			if (!$this->uri->segment(5) && !$this->uri->segment(3) && !$this->uri->segment(4)) {
				if ($this->session->userdata('usertypeID') == 2) {
					$teacherobjs = $this->classes_m->get_classes_by_teacher();
					$this->data['sectionobj']  = $this->section_m->get_single_section(array(''));
				} else {
					$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				}
				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "attendance/studentlistadmin";
			} else if ($this->uri->segment(3) && $this->uri->segment(4) && !$this->uri->segment(5)) {
				$this->data['classesID'] = $this->uri->segment(3);
				$this->data['attdate'] = $this->uri->segment(4);

				if ($this->session->userdata('usertypeID') == 2) {
					$teacherobjs = $this->classes_m->get_classes_by_teacher();
					$this->data['sectionobj']  = $this->section_m->get_single_section(array(''));
				} else {
					$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				}

				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "attendance/studentlistadmin";
			} else {
				$this->data['classesID'] = $this->uri->segment(3);
				$this->data['attdate'] = $this->uri->segment(4);
				$this->data['sectionID'] = $this->uri->segment(5);

				if ($this->session->userdata('usertypeID') == 2) {
					$teacherobjs = $this->classes_m->get_classes_by_teacher();
					$this->data['sectionobj']  = $this->section_m->get_single_section(array('sectionID' => $this->data['sectionID']));
				} else {
					$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				}

				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "attendance/studentlist";
			}

			$this->load->view('_layout_main', $this->data);
			} 
		}else{
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function studentlist()
	{
		if ($this->session->userdata('usertypeID') == 1 || $this->session->userdata('usertypeID') == 2) {
			$this->data['holidays'] = $this->getHolidaysano();
			$this->data['startingtime'] = date('Y,m,d', strtotime($this->data['schoolyearsessionobj']->startingdate));
			$this->data['endingtime'] = date('Y,m,d', strtotime($this->data['schoolyearsessionobj']->endingdate));
			$this->data['userType'] = $this->session->userdata('usertypeID');

			if (strpos($this->data['siteinfos']->weekends, ',')) {
				$weekends = explode(',', $this->data['siteinfos']->weekends);
				foreach ($weekends as $weekend) {
					$weekarray[] = $weekend + 1;
				}
				$weekarray = implode(',', $weekarray);
			} else {
				$weekarray = (int)$this->data['siteinfos']->weekends + 1;
			}
			$this->data['weekarray'] = $weekarray;
			if (!$this->uri->segment(5) && !$this->uri->segment(3) && !$this->uri->segment(4)) {
				if ($this->session->userdata('usertypeID') == 2) {
					$teacherobjs = $this->classes_m->get_classes_by_teacher();
					$this->data['sectionobj']  = $this->section_m->get_single_section(array(''));
				} else {
					$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				}
				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "attendance/studentlistadmin";
			} else if ($this->uri->segment(3) && $this->uri->segment(4) && !$this->uri->segment(5)) {
				$this->data['classesID'] = $this->uri->segment(3);
				$this->data['attdate'] = $this->uri->segment(4);

				if ($this->session->userdata('usertypeID') == 2) {
					$teacherobjs = $this->classes_m->get_classes_by_teacher();
					$this->data['sectionobj']  = $this->section_m->get_single_section(array(''));
				} else {
					$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				}

				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "attendance/studentlistadmin";
			} else {
				$this->data['classesID'] = $this->uri->segment(3);
				$this->data['attdate'] = $this->uri->segment(4);
				$this->data['sectionID'] = $this->uri->segment(5);

				if ($this->session->userdata('usertypeID') == 2) {
					$teacherobjs = $this->classes_m->get_classes_by_teacher();
					$this->data['sectionobj']  = $this->section_m->get_single_section(array('sectionID' => $this->data['sectionID']));
				} else {
					$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				}

				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "attendance/studentlist";
			}

			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	// public function classview(){
	// 	$this->data['headerassets'] = array(
	// 		'css' => array(
	// 			'assets/select2/css/select2.css',
	// 			'assets/select2/css/select2-bootstrap.css',
	// 			'assets/custom-scrollbar/jquery.mCustomScrollbar.css',
	// 			'assets/jqueryUI/jqueryui.css'
	// 		),
	// 		'js' => array(
	// 			'assets/select2/select2.js',
	// 			'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
	// 			'assets/jqueryUI/jqueryui.min.js'
	// 		)
	// 	);
	// 	$this->data['students'] = $this->student_m->get_allstudentsjson();
	//     $this->data['userType'] = $this->session->userdata('usertypeID');
	// 	if($this->data['userType'] == 2){
	// 		$this->data['classesID'] = $this->classes_m->get_class_by_teacher();
	// 	}
	// 	$this->data['classes'] = $this->classes_m->get_classes();

	// 	$this->data["subview"] = "attendance/classview";
	// 	$this->load->view('_layout_main', $this->data);
	// }

	public function studentviewlist()
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
		$this->data['students'] = $this->student_m->get_allstudentsjson();
		if ($this->session->userdata('usertypeID') == 2) {
			$this->data['classesID'] = $classobj->classesID;
			$this->data['sectionobj']  = $this->section_m->get_single_section(array());
			$teacherobjs = $this->subjectteacher_m->get_classes_by_teacher();
			$this->data['teacherobjs'] = $teacherobjs;
			//print_r($this->data['sectionobj']);die();
			$this->data["subview"] = "attendance/studentviewlist";
		} else {
			if (!$this->uri->segment(3) && !$this->uri->segment(4)) {
				$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "attendance/studentviewlistadmin";
			} else if ($this->uri->segment(3) && !$this->uri->segment(4)) {
				$this->data['classesID'] = $this->uri->segment(3);
				//$this->data['attdate'] = $this->uri->segment(4);
				$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "attendance/studentviewlistadmin";
			} else {
				$this->data['classesID'] = $this->uri->segment(3);
				$this->data['sectionID'] = $this->uri->segment(4);
				$teacherobjs = $this->classes_m->get_order_by_numeric_classes();
				$this->data['teacherobjs'] = $teacherobjs;
				$this->data["subview"] = "attendance/studentviewlist";
			}
		}

		$this->load->view('_layout_main', $this->data);
	}

	public function take_attendance()
	{

		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$classesID = $this->input->post('id');
		$attendancedate = $this->input->post('attdate');
		$sectionID = $this->input->post('idsection');
		$today = date('j');
		$monthyear = date('m-Y', strtotime($attendancedate));
		$dateday = date('j', strtotime($attendancedate));
		$subject = $this->teachersubject_m->get_subject_by_teacher();
		$classes = $this->classes_m->get_classes($classesID)->classes;

		// $studentno = $this->student_m->get_student_count_by_status($sectionID);
		// $studentno = $this->student_m->get_student_number($sectionID);
		// $students = $this->student_m->get_order_by_student_with_section_for_attendance($classesID, $this->session->userdata('defaultschoolyearID'), $sectionID);
	
		$students = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $classesID, "srsectionID" => $sectionID,'srschoolyearID' => $schoolyearID));
		// active students
		$studentno = count($students);
		// active inactive students
		$total_students = count($this->studentrelation_m->get_order_by_all_student(array('srclassesID' => $classesID, "srsectionID" => $sectionID,'srschoolyearID' => $schoolyearID)));
		
		$teacherprofile = $this->teachersection_m->get_sectionteacher($sectionID);
		$sectionname = $this->section_m->getSectionByID($sectionID);
		//print_r($teacherprofile);die();
		$insert_id = [];
		foreach ($students as $student) {
			$this->db->select('*');
			$this->db->from('attendance');
			$this->db->where(array('classesID' => $classesID, 'sectionID' => $sectionID, 'monthyear' => $monthyear, 'studentID' => $student->studentID, 'schoolyearID' => $this->session->userdata('defaultschoolyearID')));
			$query = $this->db->get();
			$records = $query->result_array();
			if (!$records) {
				$arraydb['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
				$arraydb['studentID'] = $student->studentID;
				$arraydb['classesID'] = $classesID;
				$arraydb['sectionID'] = $sectionID;
				$arraydb['userID'] = $this->session->userdata('loginuserID');
				$arraydb['userType'] = ($this->session->userdata('usertypeID') == '2') ? 'Teacher' : 'Admin';
				$arraydb['monthyear'] = $monthyear;
				for ($i = 1; $i < 32; $i++) {
					$arraydb['a' . $i] = 'P';
				}
				if ($this->db->insert('attendance', $arraydb)) {
					$insert_id[] = $this->db->insert_id();
				}
			} else {
				if ($today > $dateday && ($records[0]['a' . $dateday] == NULL)) {
					$updateData = array('a' . $dateday => 'A');
					$this->db->where('attendanceID', $records[0]['attendanceID']);
					$this->db->update("attendance", $updateData);
				}
				$insert_id[] = $records[0]['attendanceID'];
			}
			$this->db->select('*');
			$this->db->from('attendance');
			$this->db->where(array('classesID' => $classesID, 'sectionID' => $sectionID, 'monthyear' => $monthyear, 'studentID' => $student->studentID, 'schoolyearID' => $this->session->userdata('defaultschoolyearID')));
			$query = $this->db->get();
			$records = $query->result_array();
			$recordarray[] = $records;
		}
		$attendancep = $this->sattendance_m->getAttendancep($classesID, $sectionID, $attendancedate);
		$attendancel = $this->sattendance_m->getAttendancel($classesID, $sectionID, $attendancedate);
		$attendancea = $this->sattendance_m->getAttendancea($classesID, $sectionID, $attendancedate);
		$attendanceIDs = $insert_id;
		//print_r($attendancep);die();
		echo '<h4><b>Class Attendance</b></h4>
        <div class="card mt-3 card--attendance">
            <div class="card-header">
                <div class="row row-md-flex">
                    <div class="col-md-5">
					<div class="media-block media-block-alignCenter">
					
						<figure class="avatar__figure">
						<span class="avatar__image">
						<img
	                        src="' . base_url() . '/uploads/images/' . $teacherprofile->photo . '"
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
                            <span class="text-success" id="successno">' . $attendancep . '</span><span class="text-danger" id="dangerno"> ' . $attendancea . ' </span>
                        </div>
                        <div class="col-md-3 attendance-action">
                        <time>' . $attendancedate . '</time>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="attendee-lists">
                    <div hidden data-value="' . $total_students . '" id="hiddenchron"></div>';

		$i = 0;
		foreach ($students as $student) {
			if (!empty($recordarray)) {
				$this->db->select('a' . $dateday);
				$this->db->from('attendance_note');
				$this->db->where(array('attendanceID' => $attendanceIDs[$i]));
				$query = $this->db->get();
				$notesarray = $query->result_array();
				$notes = isset($notesarray[0]['a' . $dateday]) ? $notesarray[0]['a' . $dateday] : '';
				//echo $notes;
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
                          <b class="">' . $student->name . '</b>
                          <em class="rollnumber">Roll # <b>' . $student->roll . '</b></em>
                          </h4>
                      <div hidden data-value="' . $recordarray[$i][0]['a' . $dateday] . '" id="hiddendiv' . $i . '" attendance-id="' . $attendanceIDs[$i] . '" ></div>

                          <span class="pill pill--sm bg-danger infofication" id="a' . $attendanceIDs[$i] . '" data-key="' . $attendanceIDs[$i] . '" data-value="A" date-value="' . $dateday . '" student_pemail="' . $student->pemail . '" student_id = "' . $student->studentID . '" student_pid = "' . $student->parentID . '" student_name = "' . $student->name . '">Absent</span>
	                      <span class="pill pill--sm bg-success infofication" id="p' . $attendanceIDs[$i] . '" data-key="' . $attendanceIDs[$i] . '" data-value="P" date-value="' . $dateday . '" student_pemail="' . $student->pemail . '" student_id = "' . $student->studentID . '" student_pid = "' . $student->parentID . '" student_name = "' . $student->name . '">Present</span>
	                      <div id="absentnote' . $attendanceIDs[$i] . '">' . $notes . '</div>
                    
                      </div>
                      <div class="action">
	                      <label
	                        class="switch"
	                        data-toggle="tooltip"
	                        data-placement="bottom"
	                        title="Toggle Prsent/Absent"
	                        style="display:none;"
	                      >
	                      <input
	                          type="checkbox"
	                          class="switch__input"
	                          name="toggleap"
	                          id="toggleap"
	                          checked
                            
	                      />
                          <span class="switch--unchecked" id="ucid' . $attendanceIDs[$i] . '" onclick="togglestatus(this)" data-key="' . $attendanceIDs[$i] . '" data-value="P" date-value="' . $dateday . '">
                          <i class="fa fa-ban"></i>
                          </span>
                          <span class="switch--checked" id="cid' . $attendanceIDs[$i] . '" onclick="togglestatus(this)" data-key="' . $attendanceIDs[$i] . '" data-value="A" date-value="' . $dateday . '">
                          <i class="fa fa-check-circle"></i>
                          </span>
                          </label>
						  
						  <button name="attendance' . $attendanceIDs[$i] . '"
						  class="btn btn-xs btn-danger"
						  id="attendances' . $attendanceIDs[$i] . '"
						  ' . (($recordarray[$i][0]['a' . $dateday] == 'P' || $recordarray[$i][0]['a' . $dateday] == '') ? 'checked' : '') . ' onclick="showModal(this)"  data-value="' . $recordarray[$i][0]['a' . $dateday] . '" data-key="' . $attendanceIDs[$i] . '" date-value="' . $dateday . '" data-target="#addNote">
						  Add Absent Note</button>
						  &nbsp;&nbsp;
                            <div class="onoffswitch-small">
	                            <input type="checkbox" name="attendance' . $attendanceIDs[$i] . '"
	                                   class="onoffswitch-small-checkbox"
	                                   id="attendance' . $attendanceIDs[$i] . '"
	                                   ' . (($recordarray[$i][0]['a' . $dateday] == 'P' || $recordarray[$i][0]['a' . $dateday] == '') ? 'checked' : '') . ' onclick="changestatus(this)"  data-value="' . $recordarray[$i][0]['a' . $dateday] . '" data-key="' . $attendanceIDs[$i] . '" date-value="' . $dateday . '" data-target="#addNote1">
	                            <label class="onoffswitch-small-label switch"
	                                   for="attendance' . $attendanceIDs[$i] . '"
	                                   >
	                                <span class="onoffswitch-small-inner" id="inner_switch' . $attendanceIDs[$i] . '" ></span>
	                                <span class="onoffswitch-small-switch"></span>
	                            </label>
	                        </div>
                          
                      <div class="dropdown" style="display:none;">
                          <a href="#" class=" " data-toggle="dropdown">
                            Action <i class="fa fa-caret-down"></i
                          ></a>
                          <ul class="dropdown-menu right" aria-labelledby="drop5">
                              <li>
                                  <a href="javascript:void(0)" onclick="checkStatus(this)" id="absent' . $attendanceIDs[$i] . '" data-key="' . $attendanceIDs[$i] . '" data-value="A" date-value="' . $dateday . '" data-toggle="modal"  data-target="#addNote">Absent </a>
                              </li>
                              <li>
                                  <a href="javascript:void(0)" onclick="checkStatus(this)" id="presentstatuswithlate' . $attendanceIDs[$i] . '" data-key="' . $attendanceIDs[$i] . '" data-value="L" date-value="' . $dateday . '">Late Present</a>
                              </li>
                              <li>
                                  <a href="javascript:void(0)" onclick="checkStatus(this)" id="presentstatus' . $attendanceIDs[$i] . '" data-key="' . $attendanceIDs[$i] . '" data-value="P" date-value="' . $dateday . '">Present </a>
                                  <input type="hidden" value="P" data-key = ' . $attendanceIDs[$i] . '>
                              </li>
                          </ul>
                      </div>
                      </div>
                      </div>
                      </div>
                  </div>';
				$i++;
			}
		}
		echo '<span style="margin-top: 20px;" class="btn btn-success pull-right save_attendance">Save Attendance
                </span>';
		echo '</div>
              </div>
              </div>
              </div>';
	}

	public function take_studentview()
	{
		$classesID = $this->input->post('id');
		$sectionID = $this->input->post('idsection');
		//$attendancedate = $this->input->post('attdate');
		//$subject = $this->teachersubject_m->get_subject_by_teacher();
		$students = $this->student_m->get_order_by_student_with_section($classesID, $this->session->userdata('defaultschoolyearID'), $sectionID);
		$classes = $this->classes_m->get_classes($classesID)->classes;
		$studentno = $this->student_m->get_student_numberclass($classesID);
		$teacherprofile = $this->teachersection_m->get_sectionteacher($sectionID);
		$sectionname = $this->section_m->getSectionByID($sectionID);
		//print_r($attendancep);die();
		echo '<h4><b>Class Student List</b></h4>
        <div class="card mt-3 card--attendance">
            <div class="card-header">
                <div class="row row-md-flex">
                    <div class="col-md-2 attendance-action">
                        <img
	                        src="' . base_url() . '/uploads/images/' . $teacherprofile->photo . '"
	                        alt=""
	                      />
                    </div>
                    <div class="col-md-3">
                        <h3 class="card-title mb-3 mb-lg-0">
                        ' . $classes . ' ' . $sectionname . '</h3>
                        <div class="mt-2">' . $teacherprofile->name . '</div>
                    </div>
                    <div class="col-md-4 attendance-stats">
                        <div>' . $studentno . ' Students</div>
                            
                        </div>
                        <div class="col-md-3 attendance-action">
                        
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="attendee-lists">
                    <div hidden data-value="' . $studentno . '" id="hiddenchron"></div>';

		$i = 0;
		foreach ($students as $student) {
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

	public function getpresent()
	{
		$classesID = $this->input->post('id');
		$sectionID = $this->input->post('sectionid');
		$attendancedate = $this->input->post('attdate');
		echo $this->sattendance_m->getAttendancep($classesID, $sectionID, $attendancedate);
	}

	public function getlateexcuse()
	{
		$classesID = $this->input->post('id');
		$attendancedate = $this->input->post('attdate');
		echo $this->sattendance_m->getAttendancele($classesID, $attendancedate);
	}

	public function getlate()
	{
		$sectionID = $this->input->post('sectionid');
		$classesID = $this->input->post('id');
		$attendancedate = $this->input->post('attdate');
		echo $this->sattendance_m->getAttendancel($classesID, $sectionID, $attendancedate);
	}

	public function getabsent()
	{
		$sectionID = $this->input->post('sectionid');
		$classesID = $this->input->post('id');
		$attendancedate = $this->input->post('attdate');
		echo $this->sattendance_m->getAttendancea($classesID, $sectionID, $attendancedate);
	}

	public function getattendancenote()
	{
		$classesID = $this->input->post('id');
		$attendancedate = $this->input->post('attdate');
		echo $this->sattendance_m->getAttendancean($classesID, $attendancedate);
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

	public function classsectionall()
	{
		$id = $this->input->post('id');

		if ((int)$id) {
			$sections = $this->section_m->get_order_by_section(array('classesID' => $id));

			$classes = $this->classes_m->get_classes($id)->classes;
			echo '<h4><b>Class ' . $classes . ' Attendance</b></h4>';
			foreach ($sections as $section) {
				$teacherprofile = $this->teachersection_m->get_sectionteacher($section->sectionID);
				//print_r($this->section_m->get_section($section->sectionID));die();
				$attdate = $this->input->post('attdate');
				$studentno = $this->student_m->get_student_number($section->sectionID);

				echo   '<div class="card mt-3 card--attendance">
					        <div class="card-header ">
					            <div class="row row-md-flex">
					                <div class="col-md-8">
									<div class ="media-block mb-3 mb-lg-0 media-block-alignCenter">
									<figure class="avatar__figure">
									<span class="avatar__image">
									<img
										src="' . base_url() . '/uploads/images/' . $teacherprofile->photo . '"
										alt="" class="avatar-img"
									/>
									</span>
									
									
									</figure>
										 
										<div class="media-block-body">
										<h3 class="card-title mb-3 mb-lg-0">' . $classes . '<span class="pill pill--flat pill--sm">' . $section->section .
					'</span></h3>
										<div class="mt-2">' . $teacherprofile->name . '</div>
										</div>
									</div>
				                      
					                      
				                    </div>
					               
					                <div class="col-md-4 attendance-action">
					                    <a href="' . base_url() . 'attendance/index/' . $id . '/' . $attdate . '/' . $section->sectionID . '"  class="btn-link btn">
					                        Take Attendance 
					                        <i class="fa fa-2x fa-angle-right ml-3"></i>
					                    </a>
					                </div>
					            </div>
					        </div>
					    </div>';
			}
		}
	}

	public function classsectionview()
	{
		$id = $this->input->post('id');

		if ((int)$id) {
			$sections = $this->section_m->get_order_by_section(array('classesID' => $id));

			$classes = $this->classes_m->get_classes($id)->classes;
			echo '<h4><b>Class ' . $classes . '</b></h4>';
			foreach ($sections as $section) {
				$teacherprofile = $this->teachersection_m->get_sectionteacher($section->sectionID);
				//print_r($this->section_m->get_section($section->sectionID));die();
				$attdate = $this->input->post('attdate');
				$studentno = $this->student_m->get_student_number($section->sectionID);

				echo   '<div class="card mt-3 card--attendance">
					        <div class="card-header ">
					            <div class="row row-md-flex">
					                <div class="col-md-1">
				                        <img
					                        src="' . base_url() . '/uploads/images/' . $teacherprofile->photo . '"
					                        alt=""
					                      /><br/>
					                      <div>' . $teacherprofile->name . '</div>
				                    </div>
					                <div class="col-md-2 ">
					                    <h3 class="card-title mb-3 mb-lg-0">' . $classes . '<span class="pill pill--flat pill--sm">' . $section->section .
					'</span></h3>
					                    
					                </div>
					                <div class="col-md-4 attendance-action">
					                    <a href="' . base_url() . 'attendance/studentviewlist/' . $id . '/' . $section->sectionID . '"  class="btn-link btn">
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

	public function check_classes()
	{
		if ($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("check_classes", "The %s field is required");
			return FALSE;
		}
		return TRUE;
	}

	public function check_holiday($date)
	{
		$getHolidays = $this->getHolidays();
		$getHolidaysArray = explode('","', $getHolidays);

		if (customCompute($getHolidaysArray)) {
			if (in_array($date, $getHolidaysArray)) {
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
		$getWeekendDays = $this->getWeekendDays();
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
		$date = strtotime($this->input->post('date'));
		$startingdate = strtotime($this->data['schoolyearsessionobj']->startingdate);
		$endingdate   = strtotime($this->data['schoolyearsessionobj']->endingdate);

		if ($date < $startingdate || $date > $endingdate) {
			$this->form_validation->set_message('check_session_year_date', 'The %s field given not exits.');
			return FALSE;
		}
		return TRUE;
	}

	public function ajaxChangeattend()
	{
		$datevalue = $this->input->post('datevalue');
		$id = $this->input->post('id');
		$value = $this->input->post('status');

		$array = [
			'a' . $datevalue => $value
		];
		$this->db->where('attendanceID', $id);
		if ($this->db->update('attendance', $array)) {
			echo $value;
		}
	}

	public function addnote()
	{
		$datevalue = $this->input->post('datevalue');
		$attID = $this->input->post('attID');
		$notes = $this->input->post('notes');
		$array = [
			'attendanceID' => $attID
		];
		$arrayi = [
			'attendanceID' => $attID,
			'a' . $datevalue => $notes
		];
		$this->db->select('*');
		$this->db->from('attendance_note');
		$this->db->where($array);
		$query = $this->db->get();
		$records = $query->result_array();
		if (empty($records)) {
			if ($this->db->insert('attendance_note', $arrayi)) {
				echo $notes;
			}
		} else {
			$array = [
				'a' . $datevalue => $notes
			];
			$this->db->where('attendanceID', $attID);
			if ($this->db->update('attendance_note', $array)) {
				echo $notes;
			}
		}
	}

	public function ajaxBulkattend()
	{
		
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		$datas = [];
		$absentdata = [];
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body);
		$datevalue = $data->datevalue;
		$dateday = date('j', strtotime($datevalue));

		$statuses = $data->statuses;
		$attids = $data->attid;
		$parents = $data->studentpid;
		$students = $data->studentid;
		$studentno = count($statuses);
		$classesid = $data->classesid;

		$totalAbsent = count($students);
		$totalPresent = $studentno - $totalAbsent;

		for ($i = 0; $i < $studentno; $i++) {
			$datas[] = [
				'attendanceID' => (int)$attids[$i],
				'a' . $dateday => $statuses[$i]
			];
			if ($statuses[$i] == 'A') {
				$absentdata[] = $attids[$i];
			}
		}
		

		if ($this->db->update_batch('attendance', $datas, 'attendanceID')) {
			
			
			$userTypeID                 = $this->session->userdata("usertypeID");
			$userID                     = $this->session->userdata("loginuserID");
			$arrays = [];
			$index = 0;

			

			foreach ($students as $student) {
				$notes = $this->attendance_note_m->getNotesbyattid($absentdata[$index], $dateday);

				if (isset($notes['a' . $dateday]) && !empty($notes['a' . $dateday])) {
					$reason =  " due to " . $notes['a' . $dateday];
				} else {
					$reason = '.';
				}

				$studentname = $this->student_m->get_student_by_id($student);
				$studentarray = [$student . '3'];

				$arrayData = [
					"title" => 'Absent Notice',
					"users" => serialize($studentarray),
					"notice" => "Dear " . $studentname->name . ",<br/>" . " You have been absent on " . $datevalue . "" . $reason . "<br/>" . "Thank you " . "<br/>" . $this->session->userdata("name"),
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


					$noticeUser = [
						'notice_id'   => $noticeID,
						'user_id'     => $student,
						'usertypeID'  => 3
					];
					$this->notice_m->insert_notice_user($noticeUser);

					$feedUser = [
						'feed_id'     => $feedID,
						'user_id'     => $student,
						'usertypeID'  => 3
					];
					$this->feed_m->insert_feed_user($feedUser);


					$arrays[] = [
						"title"  => 'Absent Notice',
						"users" => serialize($studentarray),
						"notice" => "Dear " . $studentname->name . ",<br/>" . " You have been absent on " . $datevalue . "" . $reason . "<br/>" . "Thank you " . "<br/>" . $this->session->userdata("name"),
						"userID" =>  $student,
						"usertypeID" => 3
					];
				}
				$studentarray = [];
				$index++;
			}


			// if($this->db->insert_batch('notice',$arrays)){ 
			if (!empty($students)) {
				$this->sendFcmNotification($arrays);
				$this->pushNotification($arrays);
			}

			// }

			$arrayp = [];
			$indexp = 0;
			foreach ($parents as $parent) {
				$notes = $this->attendance_note_m->getNotesbyattid($absentdata[$indexp], $dateday);
				$studentname = $this->student_m->get_student_by_id($students[$indexp]);
				$parentname = $this->student_m->get_parent_by_id($parent);
				if (isset($notes['a' . $dateday]) && !empty($notes['a' . $dateday])) {
					$reason =  "due to " . $notes['a' . $dateday];
				} else {
					$reason = '.';
				}

				

				$parentarray = [$parent . '4'];
				$arraypData = [
					"title" => 'Absent Notice',
					"users" => serialize($parentarray),
					"notice" => "Dear " . $parentname->name . ",<br/> Your Child " . $studentname->name . " has been absent on date " . $data->attdate . " " . $reason . "<br/>" . "Thank you " . "<br/>" . $this->session->userdata("name"),
					"status" => 'private',
					'schoolyearID' =>  $this->session->userdata('defaultschoolyearID'),
					"date" => date("Y-m-d"),
					"create_date" => date("Y-m-d H:i:s"),
					"create_userID" => $this->session->userdata('loginuserID'),
					"create_usertypeID" => $this->session->userdata('usertypeID'),
					"show_to_creator"   => 0
				];
				$this->notice_m->insert_notice($arraypData);
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
						'user_id'     => $parent,
						'usertypeID'  => 4
					];
					$this->notice_m->insert_notice_user($noticeUser);

					$feedUser = [
						'feed_id'    => $feedID,
						'user_id'    => $parent,
						'usertypeID' => 4
					];
					$this->feed_m->insert_feed_user($feedUser);

					$arrayp[] = [
						"title"  => 'Absent Notice',
						"users" => serialize($parentarray),
						"notice" => "Dear " . $parentname->name . ",<br/> Your Child " . $studentname->name . " has been absent on date " . $data->attdate . " " . $reason . "<br/>" . "Thank you " . "<br/>" . $this->session->userdata("name"),
						"userID" =>  $parent,
						"usertypeID" => '4'
					];
				}
				$parentarray = [];
				$indexp++;
			}

			// if($this->db->insert_batch('notice',$arrayp)){ 
			if (!empty($parents)) {
				$this->sendFcmNotification($arrayp);
				$this->pushNotification($arrayp);
			}

			// }


			$this->sendNotificationToAdmins($classesid,$datevalue,$totalAbsent,$totalPresent);

			$retArray['status'] = TRUE;
			$retArray['message'] = 'Success';
			echo json_encode($retArray);
			exit;
		}
	}

	function pushNotification($arrays)
	{
		foreach ($arrays as $array) {
			$records[] = ['name' => 'sendNotice', 'payload' => json_encode([
				'title' => $array['title'],
				'users' => $array['users']
			])];
		}
		$this->db->insert_batch('jobs', $records);
		
	}

	function sendFcmNotification($data)
	{
		
		$index = 0;
		foreach ($data as $d) {
			$registered_ids = [];
			$user_id = $d['userID'];
			$user_type = $d['usertypeID'];
			$push_users = pluck($this->fcmtoken_m->get_order_by_fcm_token(['create_userID' => $user_id, 'create_usertypeID' => $user_type]), 'fcm_token');
			if ($push_users) {
				$registered_ids = array_merge($registered_ids, $push_users);
			}
			$message['data'] = [
				'message' => $d['notice'],
				'title' => $d['title'],
				'action' => 'attendance'
			];
			sendNotification($registered_ids, $message);
			$index++;
			// $registered_ids = [];
		}
	}


     public function sendNotificationToAdmins($classesid,$datevalue,$totalAbsent,$totalPresent){
             
		   $permission = $this->permission_m->general_get_single_permission(['name' => 'attendance_notification']);
		   if($permission){ 
			     $userTypes = $this->permission_m->usertypeByPermissionID($permission->permissionID);
				 if(count($userTypes) > 0){

					 $className = $this->classes_m->getClassByID(['classesID' => $classesid]);

                      $title = 'Attendance of class '.$className->classes.' on '.$datevalue;
					  $notice = 'Attendance of class '.$className->classes.' on '.$datevalue.' has been taken. Total present student is '.$totalPresent.' and total absent student is: '.$totalAbsent;
					   
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

}
