<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends Api_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->model('menu_m');
		$this->load->model("book_m");
		$this->load->model('event_m');
		$this->load->model("feed_m");
		$this->load->model("user_m");
		$this->load->model("notice_m");
		$this->load->model("student_m");
		$this->load->model("classes_m");
		$this->load->model("teacher_m");
		$this->load->model("parents_m");
		$this->load->model("subject_m");
		$this->load->model("feetypes_m");
		$this->load->model("lmember_m");
		$this->load->model('holiday_m');
		$this->load->model('section_m');
		$this->load->model('sattendance_m');
		$this->load->model('activities_m');
		$this->load->model('systemadmin_m');
		$this->load->model('visitorinfo_m');
		$this->load->model('eventcounter_m');
		$this->load->model('maininvoice_m');
		$this->load->model('notice_media_m');
		$this->load->model('event_media_m');
		$this->load->model('holiday_media_m');
        $this->load->model('event_comment_m');
		$this->load->model('holiday_comment_m');
        $this->load->model('notice_comment_m');
		$this->load->model('studentrelation_m');
		$this->load->model("activitiesmedia_m");
		$this->load->model("activitiescomment_m");

		$this->lang->load('dashboard', $this->data['language']);
		$this->lang->load('topbar_menu', $this->data['language']);
	}

	public function index_get($page = 1)
	{
        
		updateUserPermissions();

		$this->data['userType'] = $this->session->userdata('usertypeID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		$loginuserID  = $this->session->userdata('loginuserID');
		$students     = $this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID));

		$classes	= pluck($this->classes_m->get_classes(), 'obj', 'classesID');
		$teachers	= $this->teacher_m->get_teacher();
		$parents	= $this->parents_m->get_parents();
		$books		= $this->book_m->get_book();
		$feetypes	= $this->feetypes_m->get_feetypes();
		$lmembers	= $this->lmember_m->get_lmember();

		$visitors 	= $this->visitorinfo_m->get_order_by_visitorinfo(array('schoolyearID' => $schoolyearID));
		$mainMenu   = $this->menu_m->get_order_by_menu();
		$allmenu 	= pluck($mainMenu, 'icon', 'link');
		$allmenulang = pluck($mainMenu, 'menuName', 'link');

		$allusers     = getAllSelectUser();

        $isNotAdmin = $this->data['userType'] != 1;
		$username = $isNotAdmin ? $this->session->userdata('username') : null;
        $page = ($page - 1) * 20;
        $feeds = $this->feed_m->get_my_feeds(20,$page,$schoolyearID,$isNotAdmin ? $this->session->userdata('username') : null);
        
        $comments = [];
        foreach ($feeds as $key => $feed) {
            $feeds[$key]->unique_id = uniqid();
            if($feed->type == 'notice'){
                $noticeID = $feed->itemID;
                $notice_media = $this->notice_media_m->get_order_by_notice_media(['noticeID' => $noticeID]);
                $n_n_media = array();
                foreach ($notice_media as $media) {
                    $n_n_media[] = $media->attachment;
                }
                $feeds[$key]->media = $n_n_media;

                $notice_comments = $this->notice_comment_m->get_order_by_notice_comment(['noticeID' => $noticeID]);
				if(customCompute($notice_comments)){
					foreach($notice_comments as $k=>$comment){
						$notice_comments[$k]->name = $allusers[$comment->usertypeID][$comment->userID]->name;
						$notice_comments[$k]->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
					}
					$feeds[$key]->comments = $notice_comments;
                }else{
					$feeds[$key]->comments = [];
				}
            }

            if($feed->type == 'event'){
                $eventID = $feed->itemID;
                $event_media = $this->event_media_m->get_order_by_event_media(['eventID' => $eventID]);
                $n_e_media = array();
                foreach ($event_media as $event) {
                    $n_e_media[] = $event->attachment;
                }
                $feeds[$key]->media = $n_e_media;
                $feeds[$key]->going = $this->eventcounter_m->getEventCount($feed->itemID, 1);
                $feeds[$key]->not_going = $this->eventcounter_m->getEventCount($feed->itemID, 0);
               
				if($username) {
                    $eventCount = $this->eventcounter_m->getEventCountByRow($feed->itemID, $username);
                    if($eventCount)
                        $feeds[$key]->is_going = $eventCount->status ? 1 : 0;
                }

                $event_comments = $this->event_comment_m->get_order_by_event_comment(['eventID' => $eventID]);
                if(customCompute($event_comments)){
					foreach($event_comments as $ke=>$comment){
						$event_comments[$ke]->name = $allusers[$comment->usertypeID][$comment->userID]->name;
						$event_comments[$ke]->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
					}
					$feeds[$key]->comments = $event_comments;
                }else{
					$feeds[$key]->comments = [];
				}
            
            }
            if($feed->type == 'holiday'){
                $holidayID = $feed->itemID;
                $holiday_media = $this->holiday_media_m->get_order_by_holiday_media(['holidayID' => $holidayID]);
                $n_h_media = array();
                foreach ($holiday_media as $media) {
                    $n_h_media[] = $media->attachment;
                }
                $feeds[$key]->media = $n_h_media;

                $holiday_comments = $this->holiday_comment_m->get_order_by_holiday_comment(['holidayID' => $holidayID]);
                if(customCompute($holiday_comments)){
					foreach($holiday_comments as $kh=>$comment){
						$holiday_comments[$kh]->name = $allusers[$comment->usertypeID][$comment->userID]->name;
						$holiday_comments[$kh]->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
					}
                    $feeds[$key]->comments = $holiday_comments;
                }else{
					$feeds[$key]->comments = [];
				}
            }
            if($feed->type == 'activity'){
                $activityID = $feed->itemID;
                $activity_media = $this->activitiesmedia_m->get_order_by_activitiesmedia(['activitiesID' => $activityID]);
                $n_a_media = array();
                foreach ($activity_media as $activity) {
                    $n_a_media[] = $activity->attachment;
                }
                $feeds[$key]->media = $n_a_media;
                $feeds[$key]->enable_comment = 1;

                $activity_comments = $this->activitiescomment_m->get_order_by_activitiescomment(['activitiesID' => $activityID]);
                if(customCompute($activity_comments)){
					foreach($activity_comments as $ka=>$comment){
						$activity_comments[$ka]->name = $allusers[$comment->usertypeID][$comment->userID]->name;
						$activity_comments[$ka]->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
					}
					$feeds[$key]->comments = $activity_comments;
                }else{
					$feeds[$key]->comments = [];
				}
            }
           
        }

		$isAdmin = $this->session->userdata('usertypeID') == 1?true:false;
		$holidays	= $this->holiday_m->getRecentHolidays('', '', $schoolyearID,'','',$isAdmin);
		$dbEvents = $this->event_m->getRecentEvents('', '', $schoolyearID, $isNotAdmin ? $this->session->userdata('username') : null,'','');
		
		if ($this->session->userdata('usertypeID') == 3) {
			$getLoginStudent = $this->studentrelation_m->get_single_student(array('srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID));
			if (customCompute($getLoginStudent)) {
				$subjects	 = $this->subject_m->get_order_by_subject(array('classesID' => $getLoginStudent->srclassesID));
				$invoices	 = $this->maininvoice_m->get_order_by_maininvoice(array('maininvoicestudentID' => $getLoginStudent->srstudentID, 'maininvoiceschoolyearID' => $schoolyearID, 'maininvoicedeleted_at' => 1));
				$lmember     = $this->lmember_m->get_single_lmember(array('studentID' => $getLoginStudent->srstudentID));
			} else {
				$invoices = [];
				$subjects = [];
			}
		} else {
			$invoices	= $this->maininvoice_m->get_order_by_maininvoice(array('maininvoiceschoolyearID' => $schoolyearID, 'maininvoicedeleted_at' => 1));
			$subjects	= $this->subject_m->get_subject();
		}

		$widgetArray['dashboardWidget']['students']    = customCompute($students);
		$widgetArray['dashboardWidget']['classes']     = customCompute($classes);
		$widgetArray['dashboardWidget']['teachers']    = customCompute($teachers);
		$widgetArray['dashboardWidget']['parents'] 	   = customCompute($parents);
		$widgetArray['dashboardWidget']['subjects']    = customCompute($subjects);
		$widgetArray['dashboardWidget']['books'] 	   = customCompute($books);
		$widgetArray['dashboardWidget']['feetypes']    = customCompute($feetypes);
		$widgetArray['dashboardWidget']['lmembers']    = customCompute($lmembers);
		$widgetArray['dashboardWidget']['events'] 	   = customCompute($dbEvents);
		$widgetArray['dashboardWidget']['holidays']    = customCompute($holidays);
		$widgetArray['dashboardWidget']['invoices']    = customCompute($invoices);
		$widgetArray['dashboardWidget']['visitors']    = customCompute($visitors);
		$widgetArray['dashboardWidget']['allmenu'] 	   = $allmenu;
		$widgetArray['dashboardWidget']['allmenulang'] = $allmenulang;

		// todays attendance records of whole school
		$this->getAttendacneReportForAllClass();


		$userTypeID    = $this->session->userdata('usertypeID');
		$loginUserID   = $this->session->userdata('loginuserID');
		$this->retdata['usertype']   = $this->session->userdata('usertype');
		$this->retdata['usertypeID'] = $userTypeID;
		$this->retdata['sitename']   = $this->data['siteinfos']->sname;
		$this->retdata['sitephoto']  = $this->data['siteinfos']->photo;

		if ($userTypeID == 1) {
			$this->retdata['user'] = $this->systemadmin_m->get_single_systemadmin(array('systemadminID' => $loginUserID));
		} elseif ($userTypeID == 2) {
			$this->retdata['user'] = $this->teacher_m->get_single_teacher(array('teacherID' => $loginUserID));
		} elseif ($userTypeID == 3) {
			$this->retdata['user'] = $this->studentrelation_m->general_get_single_student(array('studentID' => $loginUserID));
		} elseif ($userTypeID == 4) {
			$this->retdata['user'] = $this->parents_m->get_single_parents(array('parentsID' => $loginUserID));
		} else {
			$this->retdata['user'] = $this->user_m->get_single_user(array('userID' => $loginUserID));
		}


		$this->dashboard_tiles($widgetArray);
		// 		$calenderArray['holidays'] = $holidays;
		// 		$calenderArray['events']   = $events;
		// 		$this->calender_info($calenderArray);

		$this->retdata['feeds'] = $feeds;

		if (empty($this->retdata['feeds'])) {
			$this->retdata['feeds'] = [];
		}

		$this->retdata['attendanceType'] = 'day';
		if ($this->data['siteinfos']->attendance == "subject") {
			$this->retdata['attendanceType'] = 'subject';
		}

		$this->response([
			'status'    => true,
			'message'   => 'Success',
			'count'     => count($this->retdata['feeds']),
			'data'      => $this->retdata,
			
  		], REST_Controller::HTTP_OK);
	}

	private function dashboard_tiles($array)
	{
		extract($array);

		$arrayColor = array(
			'bg-orange-dark',
			'bg-teal-light',
			'bg-pink-light',
			'bg-purple-light'
		);
		$userArray = array(
			'1' => array(
				'student' => $dashboardWidget['students'],
				'teacher' => $dashboardWidget['teachers'],
				'parents' => $dashboardWidget['parents'],
				'subject' => $dashboardWidget['subjects']
			),
			'2' => array(
				'student' => $dashboardWidget['students'],
				'teacher' => $dashboardWidget['teachers'],
				'classes' => $dashboardWidget['classes'],
				'subject' => $dashboardWidget['subjects'],
			),
			'3' => array(
				'teacher' => $dashboardWidget['teachers'],
				'subject' => $dashboardWidget['subjects'],
				'holiday' => $dashboardWidget['holidays'],
				'invoice' => $dashboardWidget['invoices'],
			),
			'4' => array(
				'teacher' => $dashboardWidget['teachers'],
				'book'    => $dashboardWidget['books'],
				'event'   => $dashboardWidget['events'],
				'holiday' => $dashboardWidget['holidays'],
			),
			'5' => array(
				'teacher' => $dashboardWidget['teachers'],
				'parents' => $dashboardWidget['parents'],
				'feetypes' => $dashboardWidget['feetypes'],
				'invoice' => $dashboardWidget['invoices'],
			),
			'6' => array(
				'teacher' => $dashboardWidget['teachers'],
				'lmember' => $dashboardWidget['lmembers'],
				'book'    => $dashboardWidget['books'],
				'holiday' => $dashboardWidget['holidays'],
			),
			'7' => array(
				'teacher'     => $dashboardWidget['teachers'],
				'event'       => $dashboardWidget['events'],
				'holiday'     => $dashboardWidget['holidays'],
				'visitorinfo' => $dashboardWidget['visitors'],
			),
		);

		$counter = 0;
		$getActiveUserID    = $this->session->userdata('usertypeID');
		$getAllSessionDatas = $this->session->userdata('master_permission_set');
		$generateBoxArray   = array();
		// dd($getAllSessionDatas);
		if (customCompute($getAllSessionDatas)) {
			foreach ($getAllSessionDatas as $getAllSessionDataKey => $getAllSessionData) {
				if ($getAllSessionData == 'yes') {
					if (isset($userArray[$getActiveUserID][$getAllSessionDataKey])) {
						if ($counter == 4) {
							break;
						}

						$dmenu = isset($dashboardWidget['allmenulang'][$getAllSessionDataKey])?$dashboardWidget['allmenulang'][$getAllSessionDataKey]:'';

						$generateBoxArray[$getAllSessionDataKey] = array(
							'icon' => isset($dashboardWidget['allmenu'][$getAllSessionDataKey])?$dashboardWidget['allmenu'][$getAllSessionDataKey]:'',
							'color' => $arrayColor[$counter],
							'link' => $getAllSessionDataKey,
							'count' => $userArray[$getActiveUserID][$getAllSessionDataKey],
							'menu' => $this->lang->line('menu_' . $dmenu),
						);
						$counter++;
					}
				}
			}
		}

		$icon = '';
		$menu = '';
		if ($counter < 4) {
			$userArray = $this->allModuleArray($getActiveUserID, $dashboardWidget);
			if (customCompute($getAllSessionDatas)) {
				foreach ($getAllSessionDatas as $getAllSessionDataKey => $getAllSessionData) {
					if ($getAllSessionData == 'yes') {
						if (isset($userArray[$getActiveUserID][$getAllSessionDataKey])) {
							if ($counter == 4) {
								break;
							}

							if (!isset($generateBoxArray[$getAllSessionDataKey])) {
								$generateBoxArray[$getAllSessionDataKey] = array(
									'icon'  => $dashboardWidget['allmenu'][$getAllSessionDataKey],
									'color' => $arrayColor[$counter],
									'link'  => $getAllSessionDataKey,
									'count' => $userArray[$getActiveUserID][$getAllSessionDataKey],
									'menu' => $this->lang->line('menu_' . $dashboardWidget['allmenulang'][$getAllSessionDataKey])
								);
								$counter++;
							}
						}
					}
				}
			}
		}
		$this->retdata['generateBoxs'] = $generateBoxArray;
	}

	private function calender_info($array)
	{
		extract($array);

		$retArray = '';
		if (customCompute($events)) {
			foreach ($events as $event) {
				$retArray .= '{';
				$retArray .= "title: '" . str_replace("'", "\'", $event->title) . "', ";
				$retArray .= "start: '" . $event->fdate . "T" . $event->ftime . "', ";
				$retArray .= "end: '" . $event->tdate . "T" . $event->ttime . "', ";
				$retArray .= "url:'" . base_url('event/view/' . $event->eventID) . "', ";
				$retArray .= "color  : '#5C6BC0'";
				$retArray .= '},';
			}
		}

		if (customCompute($holidays)) {
			foreach ($holidays as $holiday) {
				$retArray .= '{';
				$retArray .= "title: '" . str_replace("'", "\'", $holiday->title) . "', ";
				$retArray .= "start: '" . $holiday->fdate . "', ";
				$retArray .= "end: '" . $holiday->tdate . "', ";
				$retArray .= "url:'" . base_url('holiday/view/' . $holiday->holidayID) . "', ";
				$retArray .= "color  : '#C24984'";
				$retArray .= '},';
			}
		}
		$this->retdata['eventAndHolidays'] = $retArray;
	}

	private function allModuleArray($usertypeID = '1', $dashboardWidget)
	{
		$userAllModuleArray = array(
			$usertypeID => array(
				'student'   => $dashboardWidget['students'],
				'classes'   => $dashboardWidget['classes'],
				'teacher'   => $dashboardWidget['teachers'],
				'parents'   => $dashboardWidget['parents'],
				'subject'   => $dashboardWidget['subjects'],
				'book'      => $dashboardWidget['books'],
				'feetypes'  => $dashboardWidget['feetypes'],
				'lmember'   => $dashboardWidget['lmembers'],
				'event'     => $dashboardWidget['events'],
				'holiday'   => $dashboardWidget['holidays'],
				'invoice'   => $dashboardWidget['invoices'],
			)
		);
		return $userAllModuleArray;
	}

	public function getLatestDate()
    {
        $notice = $this->notice_m->getLatestNotice();
        $event = $this->event_m->getLatestEvent();
        $holiday = $this->holiday_m->getLatestHoliday();
        $activity = $this->activities_m->getLatestActivity();

        $dateArray = [];
        if ($notice) {
            $nDate = date('Y-m-d', strtotime($notice->date));
            $dateArray[] = $nDate;
        }
        if ($event) {
            $eDate = date('Y-m-d', strtotime($event->published_date));
            $dateArray[] = $eDate;
        }
        if ($holiday) {
            $hDate = date('Y-m-d', strtotime($holiday->published_date));
            $dateArray[] = $hDate;
        }
        if ($activity) {
            $aDate = date('Y-m-d', strtotime($activity->create_date));
            $dateArray[] = $aDate;
        }

        if (customCompute($dateArray)) {
            $dateArray = $dateArray;
        } else {
            $dateArray = [date('Y-m-d')];
        }

        $latestdate =  max($dateArray);
        $startDate = date('Y-m-d', strtotime("-14 days", strtotime($latestdate)));
        $endDate = $latestdate;


        return [$startDate, $endDate];
    }


	public function getAttendacneReportForAllClass()
	{
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if (permissionChecker('attendance_notification')) {

				
				$today          =  date('d-m-Y');  
				$date		    = explode('-', $today);
				$schoolyearID 	= $this->session->userdata('defaultschoolyearID');
				$this->data['classes']  = $classes = $this->classes_m->general_get_classes();

				$attendancetypeArray = array("P","A","LA");

				$day = 'a' . (int)$date[0];
				$monthyear = $date[1] . '-' . $date[2];

				
				$totalStudents = 0;
				$totalAbsents = 0;
				$totalPresents = 0;
				$totalLaeves = 0;
				if(customCompute($classes)){
					foreach($classes as $class){
						$sections 	= $this->section_m->general_get_order_by_section(array('classesID' => $class->classesID));
		
						if(customCompute($sections)){
							foreach($sections as $section){
								$students = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $class->classesID, 'srsectionID' => $section->sectionID, 'srschoolyearID' => $schoolyearID));
								$totalStudents = $totalStudents + count($students);
							
							          foreach($attendancetypeArray as $attendancetypeArr){
										 $attendances = $this->sattendance_m->get_order_by_attendance(array(
											 'classesID'    => $class->classesID,
											 'sectionID'    => $section->sectionID,
											 'monthyear'    => $monthyear,
											 'schoolyearID' => $schoolyearID,
											  $day       => $attendancetypeArr
											));
											$sum = $attendances?count($attendances):0;

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
							
							}
						}
					}
				}
				
				$this->retdata['attendance']['date']          = $today;
				$this->retdata['attendance']['totalStudent']  = $totalStudents;
				$this->retdata['attendance']['totalAbsents']  = $totalAbsents;
				$this->retdata['attendance']['totalPresents'] = $totalPresents;
				$this->retdata['attendance']['totalLeaves']   = $totalLaeves;
			
		} 
	}

	

}
