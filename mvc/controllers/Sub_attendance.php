<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sub_attendance extends Admin_Controller
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
    public function __construct()
    {
        parent::__construct();
        $this->load->model("student_m");
        $this->load->model("parents_m");
        $this->load->model("sattendance_m");
        $this->load->model("teacher_m");
        $this->load->model("classes_m");
        $this->load->model("user_m");
        $this->load->model("usertype_m");
        $this->load->model("section_m");
        $this->load->model("setting_m");
        $this->load->model('studentgroup_m');
        $this->load->model('subject_m');
        $this->load->model('schoolyear_m');
        $this->load->model('mailandsmstemplate_m');
        $this->load->model('mailandsmstemplatetag_m');
        $this->load->model('markpercentage_m');
        $this->load->model('mark_m');
        $this->load->model('grade_m');
        $this->load->model('exam_m');
        $this->load->model('studentrelation_m');
        $this->load->model('leaveapplication_m');
        $this->load->model('teachersection_m');
        $this->load->model('teachersubject_m');
        $this->load->model('sub_attendance_note_m');
        $this->load->model('fcmtoken_m');
        $this->load->model('subjectteacher_m');


        $this->load->library("email");
        $this->load->library('clickatell');
        $this->load->library('twilio');
        $this->load->library('bulk');
        $this->load->library('msg91');
        $this->load->model("subjectattendance_m");


        $this->data['setting'] = $this->setting_m->get_setting();

        if ($this->data['setting']->attendance == "subject") {
            $this->load->model("subjectattendance_m");
        }
        $language = $this->session->userdata('lang');
        $this->lang->load('sattendance', $language);
    }

    public function studentlist()
    {
        if ($this->session->userdata('usertypeID') == 1 || $this->session->userdata('usertypeID') == 2) {
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
    }
    else {
        $this->data["subview"] = "error";
        $this->load->view('_layout_main', $this->data);
    }
    }

    public function take_attendance()
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $classesID = $this->input->post('id');
        $attendancedate = $this->input->post('attdate');
        $sectionID = $this->input->post('idsection');
        $subjectID = $this->input->post('subjectID');
        $today = date('j');
        $monthyear = date('m-Y', strtotime($attendancedate));
        $dateday = date('j', strtotime($attendancedate));
        $classes = $this->classes_m->get_classes($classesID)->classes;


        // $students = $this->student_m->get_order_by_student_with_section_for_attendance($classesID, $this->session->userdata('defaultschoolyearID'), $sectionID);
        $students = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $classesID, "srsectionID" => $sectionID,'srschoolyearID' => $schoolyearID));
       
        $studentno = count($students);
        $total_students = count($this->studentrelation_m->get_order_by_all_student(array('srclassesID' => $classesID, "srsectionID" => $sectionID,'srschoolyearID' => $schoolyearID)));


        $sectionname = $this->section_m->getSectionByID($sectionID);
        $subject = $this->subject_m->general_get_subject($subjectID);
        $insert_id = [];

        foreach ($students as $student) {
            $records =  $this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classesID, 'subjectID' => $subjectID, 'sectionID' => $sectionID, 'monthyear' => $monthyear, 'studentID' => $student->studentID, 'schoolyearID' => $this->session->userdata('defaultschoolyearID')));
            $day = 'a' . $dateday;
            if (!$records) {
                $arraydb['schoolyearID'] = $this->session->userdata('defaultschoolyearID');
                $arraydb['studentID'] = $student->studentID;
                $arraydb['classesID'] = $classesID;
                $arraydb['sectionID'] = $sectionID;
                $arraydb['subjectID'] = $subjectID;
                $arraydb['userID'] = $this->session->userdata('loginuserID');
                $arraydb['userType'] = ($this->session->userdata('usertypeID') == '2') ? 'Teacher' : 'Admin';
                $arraydb['monthyear'] = $monthyear;
                for ($i = 1; $i < 32; $i++) {
                    $arraydb['a' . $i] = 'P';
                }
                if ($this->subjectattendance_m->insert_sub_attendance($arraydb)) {
                    $insert_id[] = $this->db->insert_id();
                }
            } else {
                if ($today > $dateday && ($records[0]->$day == NULL)) {
                    $updateData = array('a' . $dateday => 'A');
                    $this->db->where('attendanceID', $records[0]->attendanceID);
                    $this->db->update("sub_attendance", $updateData);
                }
                $insert_id[] = $records[0]->attendanceID;
            }
            $records =  $this->subjectattendance_m->get_order_by_sub_attendance(array('classesID' => $classesID, 'subjectID' => $subjectID, 'sectionID' => $sectionID, 'monthyear' => $monthyear, 'studentID' => $student->studentID, 'schoolyearID' => $this->session->userdata('defaultschoolyearID')));
            $recordarray[] = $records;
        }
        $attendancep = $this->subjectattendance_m->getAttendancep($classesID, $attendancedate, $subjectID);
        $attendancel = $this->subjectattendance_m->getAttendancel($classesID, $attendancedate, $subjectID);
        $attendancea = $this->subjectattendance_m->getAttendancea($classesID, $attendancedate, $subjectID);
        $attendanceIDs = $insert_id;

        echo '<h4><b>Class Attendance</b></h4>
        <div class="card mt-3 card--attendance">
            <div class="card-header">
                <div class="row row-md-flex">
                    <div class="col-md-5">
					<div class="media-block media-block-alignCenter">
					
						<figure class="avatar__figure">
						<span class="avatar__image">
						</span>
						
						
						</figure>
						<div class="media-block-body">
						<h3 class="card-title mb-3 mb-lg-0">
                        ' . $classes . ' <span class="pill pill--flat pill--sm">' . $sectionname . '</span></h3>
                        <div class="mt-2 ">' . $subject->subject . '</div>
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
                    <div hidden data-value="' . $studentno. '" id="hiddenchron"></div>';

        $i = 0;

        foreach ($students as $student) {
            if (!empty($recordarray) && customCompute($recordarray)) {
                $notesarray = $this->sub_attendance_note_m->getNotesbyattid($attendanceIDs[$i], $dateday);
                $notes = isset($notesarray[$day]) ? $notesarray[$day] : '';

                echo '<div class="attendee-lists-item">
                      <div class="media-block">
                          <figure class="avatar__figure">
                          <span class="avatar__image">
	                      <img
	                        src="' .imagelink($student->photo,56). '"
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
                      <div hidden data-value="' . $recordarray[$i][0]->$day . '" id="hiddendiv' . $i . '" attendance-id="' . $attendanceIDs[$i] . '" ></div>

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
                          onclick="showModal(this)"  data-value="' . $recordarray[$i][0]->$day . '" data-key="' . $attendanceIDs[$i] . '" date-value="' . $dateday . '" data-target="#addNote">Add Absent Note</button>
                          &nbsp;&nbsp;
                            <div class="onoffswitch-small">
                           
                            <input type="checkbox" name="attendance' . $attendanceIDs[$i] . '"
	                                   class="onoffswitch-small-checkbox"
	                                   id="attendance' . $attendanceIDs[$i] . '"
	                                   ' . (($recordarray[$i][0]->$day == 'P' || $recordarray[$i][0]->$day  == '') ? 'checked' : '') . ' onclick="changestatus(this)"  data-value="' . $recordarray[$i][0]->$day . '" data-key="' . $attendanceIDs[$i] . '" date-value="' . $dateday . '" data-target="#addNote1">
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

    public function addnote()
    {
        $datevalue = $this->input->post('datevalue');
        $attID = $this->input->post('attID');
        $subjectID = $this->input->post('subjectID');
        $notes = $this->input->post('notes');

        $array = [
            'attendanceID' => $attID,
            'subjectID' => $subjectID
        ];
        $arrayi = [
            'attendanceID' => $attID,
            'a' . $datevalue => $notes,
            'subjectID' => $subjectID
        ];
        $this->db->select('*');
        $this->db->from('sub_attendance_note');
        $this->db->where($array);
        $query = $this->db->get();
        $records = $query->result_array();

        if (empty($records)) {
            if ($this->db->insert('sub_attendance_note', $arrayi)) {
                echo $notes;
            }
        } else {
            $array = [
                'a' . $datevalue => $notes
            ];
            $this->db->where('attendanceID', $attID);
            if ($this->db->update('sub_attendance_note', $array)) {
                echo $notes;
            }
        }
    }

    public function subjectall()
    {
        $id = $this->input->post('id');
        if ((int)$id) {
            $allsubject = $this->subject_m->get_order_by_subject(array("classesID" => $id));
            echo "<option value='0'>", $this->lang->line("attendance_select_subject"), "</option>";
            foreach ($allsubject as $value) {
                echo "<option value=\"$value->subjectID\">", $value->subject, "</option>";
            }
        }
    }

    public function teacher_subjectall()
    {
        $id = $this->input->post('id');
        if ((int)$id) {
            $allsubject = $this->subject_m->get_subject_by_teacherID_classID($this->session->userdata('loginuserID'),$id);
            echo "<option value='0'>", $this->lang->line("attendance_select_subject"), "</option>";
            foreach ($allsubject as $value) {
                echo "<option value=\"$value->subjectID\">", $value->subject, "</option>";
            }
        }
    }

    public function classsectionall()
    {
        $id = $this->input->post('id');
        $subjectID = $this->input->post('subjectID');

        if ((int)$id) {
            $sections = $this->section_m->get_order_by_section(array('classesID' => $id));
            $subjectInfo =  $this->subject_m->get_subject($subjectID);

            if ($subjectInfo) {
                $this->data['sattendanceinfo']['subject'] = $subjectInfo->subject;
            } else {
                $this->data['sattendanceinfo']['subject'] = '';
            }

            $classes = $this->classes_m->get_classes($id)->classes;
            echo '<h4><b>Class ' . $classes . ' Attendance</b></h4>';
            foreach ($sections as $section) {

                $attdate = $this->input->post('attdate');
                $studentno = $this->student_m->get_student_number($section->sectionID);

                echo   '<div class="card mt-3 card--attendance">
					        <div class="card-header ">
					            <div class="row row-md-flex">
					                <div class="col-md-8">
									<div class ="media-block mb-3 mb-lg-0 media-block-alignCenter">
									<figure class="avatar__figure">
									<span class="avatar__image">
									
									</span>
									
									
									</figure>
										 
										<div class="media-block-body">
										<h3 class="card-title mb-3 mb-lg-0">' . $classes . '<span class="pill pill--flat pill--sm">' . $section->section .
                    '</span></h3>
										<div class="mt-2"><b>Subject:</b>' . $this->data['sattendanceinfo']['subject'] . '</div>
										</div>
									</div>
				                      
					                      
				                    </div>
					               
					                <div class="col-md-4 attendance-action">
					                    <a href="' . base_url() . 'attendance/index/' . $id . '/' . $attdate . '/' . $subjectID . '/' . $section->sectionID . '"  class="btn-link btn">
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
        $subjectID = $data->subjectid;

        $totalAbsent = count($students);
		$totalPresent = $studentno - $totalAbsent;


        for ($i = 0; $i < $studentno; $i++) {
            $datas[] = [
                'attendanceID' => $attids[$i],
                'a' . $dateday => $statuses[$i]
            ];
            if ($statuses[$i] == 'A') {
                $absentdata[] = $attids[$i];
            }
        }


        if ($this->db->update_batch('sub_attendance', $datas, 'attendanceID')) {
            $userTypeID                 = $this->session->userdata("usertypeID");
            $userID                     = $this->session->userdata("loginuserID");
            $arrays = [];
            $index = 0;

            foreach ($students as $student) {
                $notes = $this->sub_attendance_note_m->getNotesbyattid($absentdata[$index], $dateday);

                if (isset($notes['a' . $dateday]) && !empty($notes['a' . $dateday])) {
                    $reason =  "due to" . $notes['a' . $dateday];
                } else {
                    $reason = '.';
                }

                $studentname = $this->student_m->get_student_by_id($student);
                $studentarray = [$student . '3'];
                $arrays[] = [
                    "title" => 'Absent Notice',
                    "users" => serialize($studentarray),
                    "notice" => "Dear " . $studentname->name . ",<br/>" . " You have been absent on " . $datevalue . "" . $reason . "<br/>" . "Thank you" . "<br/>" . $this->session->userdata("name"),
                    "status" => 'private',
                    'schoolyearID' =>  $this->session->userdata('defaultschoolyearID'),
                    "date" => date("Y-m-d"),
                    "create_date" => date("Y-m-d H:i:s"),
                    "create_userID" => $this->session->userdata('loginuserID'),
                    "create_usertypeID" => $this->session->userdata('usertypeID'),
                    "show_to_creator"   => 0
                ];
                $studentarray = [];
                $index++;
            }


            if ($this->db->insert_batch('notice', $arrays)) {
                if (!empty($students)) {
                    $this->sendFcmNotification($students, 3, $arrays);
                }
                $this->pushNotification($arrays);
            }

            $arrayp = [];
            $indexp = 0;
            foreach ($parents as $parent) {
                $notes = $this->sub_attendance_note_m->getNotesbyattid($absentdata[$indexp], $dateday);
                $studentname = $this->student_m->get_student_by_id($students[$indexp]);
                $parentname = $this->student_m->get_parent_by_id($parent);
                if (isset($notes['a' . $dateday]) && !empty($notes['a' . $dateday])) {
                    $reason =  "due to " . $notes['a' . $dateday];
                } else {
                    $reason = '.';
                }

                $parentarray = [$parent . '4'];
                $arrayp[] = [
                    "title" => 'Absent Notice',
                    "users" => serialize($parentarray),
                    "notice" => "Dear " . $parentname->name . ",<br/> Your Child " . $studentname->name . " has been absent on date " . $data->attdate . " " . $reason . "<br/>" . "Thank you" . "<br/>" . $this->session->userdata("name"),
                    "status" => 'private',
                    'schoolyearID' =>  $this->session->userdata('defaultschoolyearID'),
                    "date" => date("Y-m-d"),
                    "create_date" => date("Y-m-d H:i:s"),
                    "create_userID" => $this->session->userdata('loginuserID'),
                    "create_usertypeID" => $this->session->userdata('usertypeID'),
                    "show_to_creator"   => 0
                ];
                $parentarray = [];
                $indexp++;
            }

            if ($this->db->insert_batch('notice', $arrayp)) {
                if (!empty($parents)) {
                    $this->sendFcmNotification($parents, 4, $arrayp);
                }
                $this->pushNotification($arrayp);
            }


            $this->sendNotificationToAdmins($classesid,$datevalue,$subjectID,$totalAbsent,$totalPresent);

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

    function sendFcmNotification($users, $usertype, $data)
    {
        
        $index = 0;
        foreach ($users as $user) {
            $registered_ids = [];
            $user_id = $user;
            $user_type = $usertype;
            $push_users = pluck($this->fcmtoken_m->get_order_by_fcm_token(['create_userID' => $user_id, 'create_usertypeID' => $user_type]), 'fcm_token');
            if ($push_users) {
                $registered_ids = array_merge($registered_ids, $push_users);
            }
            $message['data'] = [
                'message' => $data[$index]['notice'],
                'title' => $data[$index]['title'],
                'action' => 'attendance'
            ];
            sendNotification($registered_ids, $message);
            $index++;
        }
    }

    public function getpresent()
    {
        $classesID = $this->input->post('id');
        $sectionID = $this->input->post('sectionid');
        $attendancedate = $this->input->post('attdate');
        echo $this->subjectattendance_m->getAttendancep($classesID, $sectionID, $attendancedate);
    }

    public function getlateexcuse()
    {
        $classesID = $this->input->post('id');
        $attendancedate = $this->input->post('attdate');
        echo $this->subjectattendance_m->getAttendancele($classesID, $attendancedate);
    }

    public function getlate()
    {
        $sectionID = $this->input->post('sectionid');
        $classesID = $this->input->post('id');
        $attendancedate = $this->input->post('attdate');
        echo $this->subjectattendance_m->getAttendancel($classesID, $sectionID, $attendancedate);
    }

    public function getabsent()
    {
        $sectionID = $this->input->post('sectionid');
        $classesID = $this->input->post('id');
        $attendancedate = $this->input->post('attdate');
        echo $this->subjectattendance_m->getAttendancea($classesID, $sectionID, $attendancedate);
    }

    public function getattendancenote()
    {
        $classesID = $this->input->post('id');
        $attendancedate = $this->input->post('attdate');
        echo $this->subjectattendance_m->getAttendancean($classesID, $attendancedate);
    }

    public function sendNotificationToAdmins($classesid,$datevalue,$subjectID,$totalAbsent,$totalPresent){
             
        $permission = $this->permission_m->general_get_single_permission(['name' => 'attendance_notification']);
        if($permission){ 
              $userTypes = $this->permission_m->usertypeByPermissionID($permission->permissionID);
              if(count($userTypes) > 0){

                   $className = $this->classes_m->getClassByID(['classesID' => $classesid]);
                   $subjectName = $this->subject_m->general_get_single_subject(['subjectID' => $subjectID]);

                   $title = 'Attendance of class '.$className->classes.'of subject '.$subjectName->subject.' on '.$datevalue;
                   $notice = 'Attendance of class '.$className->classes.' of subject '.$subjectName->subject. ' on '.$datevalue.' has been taken. Total present student is '.$totalPresent.' and total absent student is: '.$totalAbsent;

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
}