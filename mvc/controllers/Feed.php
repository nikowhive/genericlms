<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feed extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('job_m');
        $this->load->model('log_m');
        $this->load->model('note_m');
        $this->load->model('feed_m');
        $this->load->model('event_m');
        $this->load->model('notice_m');
        $this->load->model('holiday_m');
        $this->load->model('daily_plan_m');
        $this->load->model('daily_plan_media_m');
        $this->load->library("pagination");
        $this->load->model('activities_m');
        $this->load->model('event_media_m');
        $this->load->model('notice_media_m');
        $this->load->model('eventcounter_m');
        $this->load->model('event_comment_m');
        $this->load->model('holiday_media_m');
        $this->load->model('holiday_comment_m');
        $this->load->model('notice_comment_m');
        $this->load->model('activitiesmedia_m');
        $this->load->model("activitiescomment_m");
        $language = $this->session->userdata('lang');
        $this->db->cache_off();
        $this->lang->load('feed', $language);
    }

    public function index()
    {

        $hw_assg = $this->_get_hw_assignment_feed();
        $this->data['homeworks'] = $hw_assg['homeworks'];
        $this->data['totalStudentHomework'] = $hw_assg['totalStudentHomework'];
        $this->data['totalStudentAssignment'] = $hw_assg['totalStudentAssignment'];
        $this->data['assignments'] = $hw_assg['assignments'];
        $this->data['totalStudentClasswork'] = $hw_assg['totalStudentClasswork'];
        $this->data['classworks'] = $hw_assg['classworks'];

        $userID = $this->session->userdata('loginuserID');
        $notes = $this->note_m->get_query_note(array('userID' => $userID));

        $this->data['notes'] = isset($notes) ? $notes : array();

        $usertypeID= $this->session->userdata('usertypeID');
        $filters['date'] = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
        $this->data['dailys'] = $this->daily_plan_m->get_all_daily_plans($userID, $usertypeID,$filters);

        foreach ($this->data['dailys'] as $index => $v) {
            $this->data['dailys'][$index]->medias = $this->daily_plan_media_m->get_order_by_daily_plan_media(["daily_plan_id" => $v->id]);
            
            if($v->user_type == 'Teacher'){
                $user = $this->teacher_m->get_single_teacher(['teacherID' => $v->user_id]);
            }else{
                $user = '';
            }
            $this->data['dailys'][$index]->user_image = $user?$user->photo:'';
        }


        $this->data['subview'] = 'feed/index';
        $this->load->view('_layout_main', $this->data);
    }

    public function loadFeed()
    {

        $this->data['feed_type'] = 'feed';
        $this->data['userType'] = $this->session->userdata('usertypeID');

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $isNotAdmin = $this->data['userType'] != 1;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $feeds = $this->feed_m->get_my_feeds(20,$page,$schoolyearID,$isNotAdmin ? $this->session->userdata('username') : null);
        
        // dd($feeds);

        $username = $isNotAdmin ? $this->session->userdata('username') : null;

        $comments = [];
        $noticesMedia  = [];
        $eventsMedia  = [];
        $holidaysMedia  = [];
        $activitiesMedia  = [];
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

                $notice_comments_count = count($this->notice_comment_m->paginatedNoticeComments('','',['noticeID' => $noticeID]));
			    $feeds[$key]->comment_count = $notice_comments_count;

                $notice_comments = $this->notice_comment_m->paginatedNoticeComments(5,0,['noticeID' => $noticeID]);
                if(customCompute($notice_comments)){
                    $reverse = array_reverse($notice_comments);
                    $comments['notice'][$noticeID] = $reverse;
                }

                // for gallery
                if(customCompute($notice_media)){
                    $noticesMedia[$noticeID] = $notice_media;
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

                $event_comments_count = count($this->event_comment_m->paginatedEventComments('','',['eventID' => $eventID]));
			    $feeds[$key]->comment_count = $event_comments_count;

                $event_comments = $this->event_comment_m->paginatedEventComments(5,0,['eventID' => $eventID]);
                if(customCompute($event_comments)){
                    $reverse = array_reverse($event_comments);
                    $comments['event'][$eventID] = $reverse;
                }

                // for gallery
                if(customCompute($event_media)){
                    $eventsMedia[$eventID] = $event_media;
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

                $holiday_comments_count = count($this->holiday_comment_m->paginatedHolidayComments('','',['holidayID' => $holidayID]));
			    $feeds[$key]->comment_count = $holiday_comments_count;

                $holiday_comments = $this->holiday_comment_m->paginatedHolidayComments(5,0,['holidayID' => $holidayID]);
                
                if(customCompute($holiday_comments)){
                    $reverse = array_reverse($holiday_comments);
                    $comments['holiday'][$holidayID] = $reverse;
                }
                
                // for gallery
                if(customCompute($holiday_media)){
                    $holidaysMedia[$holidayID] = $holiday_media;
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

                $activity_comments_count = count($this->activitiescomment_m->paginatedActivityComments('','',['activitiesID' => $activityID]));
			    $feeds[$key]->comment_count = $activity_comments_count;

                $activity_comments = $this->activitiescomment_m->paginatedActivityComments(5,0,['activitiesID' => $activityID]);
                
                if(customCompute($activity_comments)){
                    $reverse = array_reverse($activity_comments);
                    $comments['activity'][$activityID] = $reverse;
                }
                
                // for gallery
                if(customCompute($activity_media)){
                    $activitiesMedia[$activityID] = $activity_media;
                }
            }
           
        }
        
        $this->data['feeds'] = $feeds; 
        $this->data['comments'] = $comments;
        $this->data['noticesMedia'] = $noticesMedia;
        $this->data['eventsMedia'] = $eventsMedia;
        $this->data['holidaysMedia'] = $holidaysMedia;
        $this->data['activitiesMedia'] = $activitiesMedia;
        $this->data['user'] = getAllSelectUser();

        if ($this->data['feeds']) {
            echo $this->load->view('feed/index_page_feed', $this->data, true);
            exit;
        } else {
            echo '';
        }
    }

    private function _get_hw_assignment_feed()
    {
        $this->load->model('studentrelation_m');
        $this->load->model('student_m');
        $this->load->model('assignment_m');
        $this->load->model('classwork_m');
        $this->load->model('homework_m');
        $this->load->model('assignmentanswer_m');
        $this->load->model('homeworkanswer_m');
        $this->load->model('classworkanswer_m');
        $this->load->model('section_m');
        $this->load->model('subjectteacher_m');
        $userdata = $this->session->userdata();
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $loginuserID = $this->session->userdata('loginuserID');
        $usertypeID = $this->session->userdata('usertypeID');

        $student = [];
        $opsubjects = [];
        $assignments = [];
        $homeworks = [];
        $classworks = [];
        $totalStudentAssignment = [];
        $totalStudentHomework = [];
        $totalStudentClasswork = [];

        /*
            usertype (3) => student || usertypeID (2) => Teacher
        */
        if ($usertypeID == 3 or $usertypeID == 2 or $usertypeID == 4) {

            if ($usertypeID == 3) {
                $student = $this->studentrelation_m->get_single_studentrelation(['srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID]);
                $classesID = $student->srclassesID;
                $assignment_query = array('assignment.classesID' => $classesID, 'assignment.schoolyearID' => $schoolyearID, 'assignment.is_published' => 1, 'assignment.deadlinedate >=' => date("Y-m-d"));
                $assignments = $this->assignment_m->join_get_assignment_feed($assignment_query);
            } else if ($usertypeID == 2) {
                // $assignment_query = array('assignment.schoolyearID' => $schoolyearID, 'assignment.is_published' => 1, 'assignment.usertypeID' => $usertypeID, 'assignment.userID' => $loginuserID);
                $teacher_subject = pluck($this->subjectteacher_m->get_order_by_subjectteacher(["teacherID" => $loginuserID]), 'subjectID');
                $sub=$teacher_subject?$teacher_subject:'';
                $assignments = $this->assignment_m->get_assignment_from_subject($schoolyearID, $sub);
               
            } else if ($usertypeID == 4) {
                $classes = pluck($this->student_m->get_student(), 'classesID');
                $assignment_query = array('assignment.schoolyearID' => $schoolyearID, 'assignment.is_published' => 1, 'assignment.deadlinedate >=' => date("Y-m-d"));
                $assignments = $this->assignment_m->join_get_assignment_feed_multiple_classes($assignment_query, $classes);
            }

            $count_assignment_submit = 0;
            $count_assignment_submit_not = 0;
           
            foreach ($assignments as $key => $assignment) {
                $class = $this->classes_m->general_get_single_classes(array('classesID' => $assignment->classesID));
                $totalStudentAssignment[$assignment->classesID] = $this->student_m->get_student_feed(array('classesID' => $assignment->classesID, 'schoolyearID' => $schoolyearID), TRUE);

                $assignments[$key]->class_name = ($class) ? $class->classes : '';

                $sec = json_decode($assignment->sectionID);

                $section = $this->section_m->get_single_section(array('sectionID' => isset($sec[0]) ? $sec[0] : ''));
                $assignments[$key]->section_name = ($section) ? $section->section : '';

                $assignment_answer = $this->assignmentanswer_m->get_assignmentanswer_feed(array('assignmentID' => $assignment->assignmentID, 'uploadertypeID' => 3, 'schoolyearID' => $schoolyearID), TRUE);

                $assignments[$key]->count_assignment_submit = $assignment_answer;

                if ($assignment->usertypeID == 1) {
                    $user = $this->systemadmin_m->get_single_systemadmin(['systemadminID' => $assignment->userID]);
                } else if ($assignment->usertypeID == 2) {
                    $user = $this->teacher_m->get_single_teacher(['teacherID' => $assignment->userID]);
                } else if ($assignment->usertypeID == 4) {
                    $user = $this->parent_m->get_single_parents(['parentsID' => $assignment->userID]);
                } else {
                    $user = $this->user_m->get_single_user(['userID' => $assignment->userID]);
                }
                $assignments[$key]->created_by = $user?$user->name:'';
                if ($usertypeID == 4) {
                    $user = $this->student_m->get_individual_student($assignment->userID);
                }
                $assignments[$key]->user_image = $user?$user->photo:'';

                $deadlinedate = $assignment->deadlinedate;
                $today = date("Y-m-d");
                $s_today = strtotime($today);
                $s_deadlinedate = strtotime($deadlinedate);
                if (is_array($assignment) or is_object($assignment)) {
                    if ($s_today > $s_deadlinedate) {
                        $deadline_s = 'text-danger';
                    } else if ($s_today <= $s_deadlinedate) {
                        if ($s_deadlinedate == strtotime($today . ' +1 day') or $s_deadlinedate == strtotime($today . ' +2 day') or $s_deadlinedate == strtotime($today . ' +3 day')) {
                            $deadline_s = 'text-warning';
                        } else if ($s_today == $s_deadlinedate) {
                            $deadline_s = 'text-warning';
                        } else {
                            $deadline_s = 'text-success';
                        }
                    } else {
                        $deadline_s = 'text-success';
                    }
                    $assignments[$key]->assignment_status = $deadline_s;
                }
                if ($usertypeID == 3 or $usertypeID == 4) {

                    /*
                    assignment/assignmentanswer() assignmentid, classid
                    */
                    $link = site_url('assignment/assignmentanswer/' . $assignment->assignmentID . '/' . $assignment->classesID . '?course=' . $assignment->course_id);
                    $link_title = "";
                    $view_link = site_url('assignment/view/' . $assignment->assignmentID . '/' . $assignment->classesID . '?course=' . $assignment->course_id);


                    if ($s_today > $s_deadlinedate) {
                        $link = "#";
                        $link_title = "Deadline Crossed";
                    }
                    $assignments[$key]->link = $link;
                    $assignments[$key]->link_title = $link_title;
                    $assignments[$key]->view_link = $view_link;

                    if ($usertypeID == 3) {
                        $assignment_ans = $this->assignmentanswer_m->get_single_assignmentanswer(array('uploadertypeID' => 3, 'uploaderID' => $loginuserID, ' schoolyearID' => $schoolyearID, 'assignmentID' => $assignment->assignmentID));
                    } else {
                        $assignment_ans = $this->assignmentanswer_m->get_single_assignmentanswer(array('uploadertypeID' => 3, ' schoolyearID' => $schoolyearID, 'assignmentID' => $assignment->assignmentID));
                    }

                    $assign_ans_status = $assignment_ans ? $assignment_ans->status : '';
                    if ($assign_ans_status == "pending") {
                        $assign_status_title = 'submitted';
                        $assign_status = 'label-warning';
                    } elseif ($assign_ans_status == "checked") {
                        $assign_status_title = 'checked';
                        $assign_status = 'label-primary';
                    } elseif ($assign_ans_status == "viewed") {
                        $assign_status_title = 'viewed';
                        $assign_status = 'label-success';
                    } else {
                        $assign_status_title = 'pending';
                        $assign_status = 'label-danger';
                    }
                    $assignments[$key]->status_label = $assign_status;
                    $assignments[$key]->status_label_title = $assign_status_title;
                    // if($s_today > $s_deadlinedate)
                    // {
                    //     unset($assignments[$key]);
                    // }
                    // if (is_array($assignment_ans) or is_object($assignment_ans)) {
                    //     // unset($assignments[$key]);
                    // }
                }
                if ($usertypeID == 2) {
                    /*
                    assignment/assignmentanswer() assignmentid, classid
                    */
                    $view_link = site_url('assignment/view/' . $assignment->assignmentID . '/' . $assignment->classesID . '?course=' . $assignment->course_id);
                    $assignments[$key]->view_link = $view_link;
                    $title = '';
                    if ($s_today > $s_deadlinedate) {
                        $title = 'Deadline Crossed';
                    }
                    $assignments[$key]->link_title = $title;
                    $assignments[$key]->link = '';
                    $assignments[$key]->status_label = '';
                    $assignments[$key]->status_label_title = '';

                    if ($s_today > $s_deadlinedate) {
                        if (strtotime($deadlinedate . ' +3 days') < $s_today) {
                            unset($assignments[$key]);
                        }
                    }
                }
            }

            if ($usertypeID == 3) {
                $hw_query = array('homework.classesID' => $classesID, 'homework.schoolyearID' => $schoolyearID, 'homework.is_published' => 1, 'homework.deadlinedate >=' => date("Y-m-d"));
                $homeworks = $this->homework_m->join_get_homework_feed($hw_query);
            } else if ($usertypeID == 2) {
                $teacher_subject = pluck($this->subjectteacher_m->get_order_by_subjectteacher(["teacherID" => $loginuserID]), 'subjectID');
                $sub=$teacher_subject?$teacher_subject:'';
                $homeworks = $this->homework_m->get_homework_from_subject($schoolyearID, $sub);
            } else if ($usertypeID == 4) {
                $classes = pluck($this->student_m->get_student(), 'classesID');
                $hw_query = array('homework.schoolyearID' => $schoolyearID, 'homework.is_published' => 1, 'homework.deadlinedate >=' => date("Y-m-d"));
                $homeworks = $this->homework_m->join_get_homework_feed_multiple_classes($hw_query, $classes);
            }

            $count_hw_submit = 0;
            $count_hw_submit_not = 0;
           
            foreach ($homeworks as $key => $homework) {
                $class = $this->classes_m->general_get_single_classes(array('classesID' => $homework->classesID));
                $totalStudentHomework[$homework->classesID] = $this->student_m->get_student_feed(array('classesID' => $homework->classesID, 'schoolyearID' => $schoolyearID), TRUE);

                $homeworks[$key]->class_name = ($class) ? $class->classes : '';
                $sec = json_decode($homework->sectionID);
                $section = $this->section_m->get_single_section(array('sectionID' => isset($sec[0]) ? $sec[0] : ''));
                $homeworks[$key]->section_name = ($section) ? $section->section : '';

                $hw_answer = $this->homeworkanswer_m->get_homeworkanswer_feed(array('homeworkID' => $homework->homeworkID, 'uploadertypeID' => 3, 'schoolyearID' => $schoolyearID), TRUE);
                $homeworks[$key]->count_hw_submit = $hw_answer;

                if ($homework->usertypeID == 1) {
                    $user = $this->systemadmin_m->get_single_systemadmin(['systemadminID' => $homework->userID]);
                } else if ($homework->usertypeID == 2) {
                    $user = $this->teacher_m->get_single_teacher(['teacherID' => $homework->userID]);
                } else {
                    $user = $this->user_m->get_single_user(['userID' => $homework->userID]);
                }
                $homeworks[$key]->created_by = $user?$user->name:'';
                $homeworks[$key]->user_image = $user?$user->photo:'';

                $hw_d_s = $homework->deadlinedate;
                $today = date("Y-m-d");
                $s_today = strtotime($today);
                $s_hw_d_s = strtotime($hw_d_s);
                if (is_array($homework) or is_object($homework)) {
                    if ($s_today > $s_hw_d_s) {
                        $deadline_s = 'text-danger';
                    } else if ($s_today <= $s_hw_d_s) {
                        if ($s_hw_d_s == strtotime($today . ' +1 day') or $s_hw_d_s == strtotime($today . ' +2 day') or $s_hw_d_s == strtotime($today . ' +3 day')) {
                            $deadline_s = 'text-warning';
                        } else if ($s_today == $s_hw_d_s) {
                            $deadline_s = 'text-warning';
                        } else {
                            $deadline_s = 'text-success';
                        }
                    } else {
                        $deadline_s = 'text-success';
                    }
                    $homeworks[$key]->hw_status = $deadline_s;
                }
                if ($usertypeID == 3  or $usertypeID == 4) {
                    /*
                    homework/homeworkanswer() homeworkid, classid
                    */
                    $link = site_url('homework/homeworkanswer/' . $homework->homeworkID . '/' . $homework->classesID . '?course=' . $homework->course_id);
                    $link_title = "";

                    $view_link = site_url('homework/view/' . $homework->homeworkID . '/' . $homework->classesID . '?course=' . $homework->course_id);

                    if ($s_today > $s_hw_d_s) {
                        $link = "#";
                        $link_title = "Deadline Crossed";
                    }
                    $homeworks[$key]->link = $link;
                    $homeworks[$key]->link_title = $link_title;
                    $homeworks[$key]->view_link = $view_link;

                    if ($usertypeID == 3) {
                        $hw_ans = $this->homeworkanswer_m->get_single_homeworkanswer(array('homeworkID' => $homework->homeworkID, 'uploadertypeID' => 3, 'uploaderID' => $loginuserID, 'schoolyearID' => $schoolyearID));
                    } else {
                        $hw_ans = $this->homeworkanswer_m->get_single_homeworkanswer(array('homeworkID' => $homework->homeworkID, 'uploadertypeID' => 3, 'schoolyearID' => $schoolyearID));
                    }
                    // $homeworks[$key]->status = $hw_ans ? $hw_ans->status : 'Pending';
                    $deadline_s = '';

                    $homework_ans_status = $hw_ans ? $hw_ans->status : '';
                    if ($homework_ans_status == "pending") {
                        $homework_status_title = 'submitted';
                        $homework_status = 'label-warning';
                    } elseif ($homework_ans_status == "checked") {
                        $homework_status_title = 'checked';
                        $homework_status = 'label-primary';
                    } elseif ($homework_ans_status == "viewed") {
                        $homework_status_title = 'viewed';
                        $homework_status = 'label-success';
                    } else {
                        $homework_status_title = 'pending';
                        $homework_status = 'label-danger';
                    }
                    $homeworks[$key]->status_label = $homework_status;
                    $homeworks[$key]->status_label_title = $homework_status_title;
                    // if($s_today > $s_hw_d_s)
                    // {
                    //     unset($homeworks[$key]);
                    // }

                    // if (is_array($hw_ans) or is_object($hw_ans)) {
                    //     unset($homeworks[$key]);
                    // }
                }
                if ($usertypeID == 2) {
                    /*
                    homework/homeworkanswer() homeworkid, classid
                    */
                    $view_link = site_url('homework/view/' . $homework->homeworkID . '/' . $homework->classesID . '?course=' . $homework->course_id);
                    $link_title = "";

                    if ($s_today > $s_hw_d_s) {
                        $link_title = "Deadline Crossed";
                    }

                    $homeworks[$key]->view_link = $view_link;
                    $homeworks[$key]->link_title = $link_title;
                    $homeworks[$key]->link = '';
                    $homeworks[$key]->status = '';
                    $homeworks[$key]->status_label = '';
                    $homeworks[$key]->status_label_title = '';


                    if ($s_today > $s_hw_d_s) {
                        if (strtotime($hw_d_s . ' +3 days') < $s_today) {
                            unset($homeworks[$key]);
                        }
                    }
                }
            }

            if ($usertypeID == 3) {
                $cw_query = array('classwork.classesID' => $classesID, 'classwork.schoolyearID' => $schoolyearID, 'classwork.is_published' => 1, 'classwork.deadlinedate >=' => date("Y-m-d"));
                $classworks = $this->classwork_m->join_get_classwork_feed($cw_query);
            } else if ($usertypeID == 2) {
                $teacher_subject = pluck($this->subjectteacher_m->get_order_by_subjectteacher(["teacherID" => $loginuserID]), 'subjectID');
                $sub=$teacher_subject?$teacher_subject:'';
                $classworks = $this->classwork_m->get_classwork_from_subject($schoolyearID, $sub);
            } else if ($usertypeID == 4) {
                $classes = pluck($this->student_m->get_student(), 'classesID');
                $cw_query = array('classwork.schoolyearID' => $schoolyearID, 'classwork.is_published' => 1, 'classwork.deadlinedate >=' => date("Y-m-d"));
                $classworks = $this->classwork_m->join_get_classwork_feed_multiple_classes($cw_query, $classes);
            }

            $count_cw_submit = 0;
            $count_cw_submit_not = 0;
           
            foreach ($classworks as $key => $classwork) {
                $class = $this->classes_m->general_get_single_classes(array('classesID' => $classwork->classesID));
                $totalStudentHomework[$classwork->classesID] = $this->student_m->get_student_feed(array('classesID' => $classwork->classesID, 'schoolyearID' => $schoolyearID), TRUE);

                $classworks[$key]->class_name = ($class) ? $class->classes : '';
                $sec = json_decode($classwork->sectionID);
                $section = $this->section_m->get_single_section(array('sectionID' => isset($sec[0]) ? $sec[0] : ''));
                $classworks[$key]->section_name = ($section) ? $section->section : '';

                $cw_answer = $this->classworkanswer_m->get_classworkanswer_feed(array('classworkID' => $classwork->classworkID, 'uploadertypeID' => 3, 'schoolyearID' => $schoolyearID), TRUE);
                $classworks[$key]->count_cw_submit = $cw_answer;

                if ($classwork->usertypeID == 1) {
                    $user = $this->systemadmin_m->get_single_systemadmin(['systemadminID' => $classwork->userID]);
                } else if ($classwork->usertypeID == 2) {
                    $user = $this->teacher_m->get_single_teacher(['teacherID' => $classwork->userID]);
                } else {
                    $user = $this->user_m->get_single_user(['userID' => $classwork->userID]);
                }
                $classworks[$key]->created_by = $user?$user->name:'';
                $classworks[$key]->user_image = $user?$user->photo:'';

                $cw_d_s = $classwork->deadlinedate;
                $today = date("Y-m-d");
                $s_today = strtotime($today);
                $s_cw_d_s = strtotime($cw_d_s);
                if (is_array($classwork) or is_object($classwork)) {
                    if ($s_today > $s_cw_d_s) {
                        $deadline_s = 'text-danger';
                    } else if ($s_today <= $s_cw_d_s) {
                        if ($s_cw_d_s == strtotime($today . ' +1 day') or $s_cw_d_s == strtotime($today . ' +2 day') or $s_cw_d_s == strtotime($today . ' +3 day')) {
                            $deadline_s = 'text-warning';
                        } else if ($s_today == $s_cw_d_s) {
                            $deadline_s = 'text-warning';
                        } else {
                            $deadline_s = 'text-success';
                        }
                    } else {
                        $deadline_s = 'text-success';
                    }
                    $classworks[$key]->cw_status = $deadline_s;
                }
                if ($usertypeID == 3  or $usertypeID == 4) {
                    /*
                    classwork/classworkanswer() classworkid, classid
                    */
                    $link = site_url('classwork/classworkanswer/' . $classwork->classworkID . '/' . $classwork->classesID . '?course=' . $classwork->course_id);
                    $link_title = "";

                    $view_link = site_url('classwork/view/' . $classwork->classworkID . '/' . $classwork->classesID . '?course=' . $classwork->course_id);

                    if ($s_today > $s_cw_d_s) {
                        $link = "#";
                        $link_title = "Deadline Crossed";
                    }
                    $classworks[$key]->link = $link;
                    $classworks[$key]->link_title = $link_title;
                    $classworks[$key]->view_link = $view_link;

                    if ($usertypeID == 3) {
                        $cw_ans = $this->classworkanswer_m->get_single_classworkanswer(array('classworkID' => $classwork->classworkID, 'uploadertypeID' => 3, 'uploaderID' => $loginuserID, 'schoolyearID' => $schoolyearID));
                    } else {
                        $cw_ans = $this->classworkanswer_m->get_single_classworkanswer(array('classworkID' => $classwork->classworkID, 'uploadertypeID' => 3, 'schoolyearID' => $schoolyearID));
                    }
                    // $classworks[$key]->status = $cw_ans ? $cw_ans->status : 'Pending';
                    $deadline_s = '';

                    $classwork_ans_status = $cw_ans ? $cw_ans->status : '';
                    if ($classwork_ans_status == "pending") {
                        $classwork_status_title = 'submitted';
                        $classwork_status = 'label-warning';
                    } elseif ($classwork_ans_status == "checked") {
                        $classwork_status_title = 'checked';
                        $classwork_status = 'label-primary';
                    } elseif ($classwork_ans_status == "viewed") {
                        $classwork_status_title = 'viewed';
                        $classwork_status = 'label-success';
                    } else {
                        $classwork_status_title = 'pending';
                        $classwork_status = 'label-danger';
                    }
                    $classworks[$key]->status_label = $classwork_status;
                    $classworks[$key]->status_label_title = $classwork_status_title;
                    // if($s_today > $s_cw_d_s)
                    // {
                    //     unset($classworks[$key]);
                    // }

                    // if (is_array($cw_ans) or is_object($cw_ans)) {
                    //     unset($classworks[$key]);
                    // }
                }
                if ($usertypeID == 2) {
                    /*
                    classwork/classworkanswer() classworkid, classid
                    */
                    $view_link = site_url('classwork/view/' . $classwork->classworkID . '/' . $classwork->classesID . '?course=' . $classwork->course_id);
                    $link_title = "";

                    if ($s_today > $s_cw_d_s) {
                        $link_title = "Deadline Crossed";
                    }

                    $classworks[$key]->view_link = $view_link;
                    $classworks[$key]->link_title = $link_title;
                    $classworks[$key]->link = '';
                    $classworks[$key]->status = '';
                    $classworks[$key]->status_label = '';
                    $classworks[$key]->status_label_title = '';


                    if ($s_today > $s_cw_d_s) {
                        if (strtotime($cw_d_s . ' +3 days') < $s_today) {
                            unset($classworks[$key]);
                        }
                    }
                }
            }
        }

        $data['totalStudentHomework'] = $totalStudentHomework;
        $data['totalStudentAssignment'] = $totalStudentAssignment;
        $data['totalStudentClasswork'] = $totalStudentClasswork;
        $data['classworks'] = $classworks;
        $data['assignments'] = $assignments;
        $data['homeworks'] = $homeworks;
        return $data;
    }

    public function getMoreFeedData()
    {

        $this->data['feed_type'] = 'feed';
        $this->data['userType'] = $this->session->userdata('usertypeID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $isNotAdmin = $this->data['userType'] != 1;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $feeds = $this->feed_m->get_my_feeds(20,$page,$schoolyearID,$isNotAdmin ? $this->session->userdata('username') : null);

        $username = $isNotAdmin ? $this->session->userdata('username') : null;

        $comments = [];
        $noticesMedia = [];
        $eventsMedia = [];
        $holidaysMedia = [];
        $activitiesMedia = [];
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

                $notice_comments_count = count($this->notice_comment_m->paginatedNoticeComments('','',['noticeID' => $noticeID]));
                $feeds[$key]->comment_count = $notice_comments_count;

                $notice_comments = $this->notice_comment_m->paginatedNoticeComments(5,0,['noticeID' => $noticeID]);
                if(customCompute($notice_comments)){
                    $reverse = array_reverse($notice_comments);
                    $comments['notice'][$noticeID] = $reverse;
                }

                // for gallery
                if(customCompute($notice_media)){
                    $noticesMedia[$noticeID] = $notice_media;
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

                $event_comments_count = count($this->event_comment_m->paginatedEventComments('','',['eventID' => $eventID]));
                $feeds[$key]->comment_count = $event_comments_count;

                $event_comments = $this->event_comment_m->paginatedEventComments(5,0,['eventID' => $eventID]);
                if(customCompute($event_comments)){
                    $reverse = array_reverse($event_comments);
                    $comments['event'][$eventID] = $reverse;
                }

                // for gallery
                if(customCompute($event_media)){
                    $eventsMedia[$eventID] = $event_media;
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

                $holiday_comments_count = count($this->holiday_comment_m->paginatedHolidayComments('','',['holidayID' => $holidayID]));
                $feeds[$key]->comment_count = $holiday_comments_count;

                $holiday_comments = $this->holiday_comment_m->paginatedHolidayComments(5,0,['holidayID' => $holidayID]);
                
                if(customCompute($holiday_comments)){
                    $reverse = array_reverse($holiday_comments);
                    $comments['holiday'][$holidayID] = $reverse;
                }

                // for gallery
                if(customCompute($holiday_media)){
                    $holidaysMedia[$holidayID] = $holiday_media;
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

                $activity_comments_count = count($this->activitiescomment_m->paginatedActivityComments('','',['activitiesID' => $activityID]));
                $feeds[$key]->comment_count = $activity_comments_count;

                $activity_comments = $this->activitiescomment_m->paginatedActivityComments(5,0,['activitiesID' => $activityID]);
                
                if(customCompute($activity_comments)){
                    $reverse = array_reverse($activity_comments);
                    $comments['activity'][$activityID] = $reverse;
                }

                // for gallery
                if(customCompute($activity_media)){
                    $activitiesMedia[$activityID] = $activity_media;
                }
            }
        }

        $this->data['feeds'] = $feeds;
        $this->data['comments'] = $comments;
        $this->data['noticesMedia'] = $noticesMedia;
        $this->data['eventsMedia'] = $eventsMedia;
        $this->data['holidaysMedia'] = $holidaysMedia;
        $this->data['activitiesMedia'] = $activitiesMedia;
        $this->data['user'] = getAllSelectUser();

        if ($this->data['feeds']) {
            echo $this->load->view('feed/autoload_feeds', $this->data, true);
            exit;
        } else {
            showBadRequest(null, "No data.");
        }
    }

    public function migrations(){

        // notices migrations
        $notices = $this->notice_m->forMigrations();
        if(customCompute($notices)){
            foreach($notices as $notice){
                $noticeID = $notice->noticeID;
                $feed = $this->feed_m->get_single_feed(
                    array(
                        'itemID' => $noticeID,
                        'itemname' => 'notice'
                    )
                );
			    if(!customCompute($feed)){
                    $this->feed_m->insert_feed(
                        array(
                            'itemID'         => $noticeID,
                            'userID'         => $notice->create_userID,
                            'usertypeID'     => $notice->create_usertypeID,
                            'itemname'       => 'notice',
                            'schoolyearID'   => $notice->schoolyearID,
                            'published'      => 1,
                            'published_date' => $notice->date,
                            'status'         => $notice->status,
                        )
                    );

                    if($notice->status == 'private'){
                        $feedID = $this->db->insert_id();
                        if($notice->users != "" && $notice->users != "N;"){
                            $users = unserialize($notice->users);
                            if(customCompute($users)){
                                $noticeUsers = [];
                                $feedUsers = [];
                                foreach($users as $user){  
                                    $array = str_split($user);
                                    if(count($array) != 0) {
                                            $user_id = substr($user, 0, -1);
                                            $user_type = substr($user, -1);
                                            $noticeUsers[] = [
                                                'notice_id'  => (int)$noticeID,
                                                'user_id'    => (int)$user_id,
                                                'usertypeID' => (int)$user_type
                                            ];
                                            $feedUsers[] = [
                                                'feed_id'    => (int) $feedID,
                                                'user_id'    => (int)$user_id,
                                                'usertypeID' => (int)$user_type
                                            ];
                                        }
                                }
                                $this->notice_m->insert_batch_notice_user($noticeUsers);
                                $this->feed_m->insert_batch_feed_user($feedUsers);	
                            }
                        }
					}
                } 
            } 
        }

        // event migrations
        $events = $this->event_m->forMigrations();
        if(customCompute($events)){
            foreach($events as $event){
                $eventID = $event->eventID;
                $feed = $this->feed_m->get_single_feed(
                    array('itemID' => $eventID,'itemname' => 'event'));
			    if(!customCompute($feed)){
                    $this->feed_m->insert_feed(
                        array(
                            'itemID'         => $eventID,
                            'userID'         => $event->create_userID,
                            'usertypeID'     => $event->create_usertypeID,
                            'itemname'       => 'event',
                            'schoolyearID'   => $event->schoolyearID,
                            'published'      => $event->published,
                            'published_date' => $event->published_date,
                            'status'         => $event->status,
                        )
                    );

                    if($event->status == 'private'){
                        $feedID = $this->db->insert_id();
                        if($event->users != "" && $event->users != "N;"){
                           $users = unserialize($event->users);
                            if(customCompute($users)){
                                $eventUsers = [];
                                $feedUsers = [];
                                foreach($users as $user){ 
                                        $array = str_split($user);
                                        if(count($array) != 0) { 
                                            $user_id = substr($user, 0, -1);
                                            $user_type = substr($user, -1);
                                            $eventUsers[] = [
                                                'event_id'   => (int)$eventID,
                                                'user_id'    => (int)$user_id,
                                                'usertypeID' => (int)$user_type
                                            ];
                                            $feedUsers[] = [
                                                'feed_id'    => (int)$feedID,
                                                'user_id'    => (int)$user_id,
                                                'usertypeID' => (int)$user_type
                                            ];
                                        }    
                                }
                                $this->event_m->insert_batch_event_user($eventUsers);
                                $this->feed_m->insert_batch_feed_user($feedUsers);	
                            }
                        }
					}
                        
                } 
            } 
        }

        // holiday migrations
        $holidays = $this->holiday_m->forMigrations();
        if(customCompute($holidays)){
            foreach($holidays as $holiday){
                $holidayID = $holiday->holidayID;
                $feed = $this->feed_m->get_single_feed(array(
                    'itemID' => $holidayID, 
                    'itemname' => 'holiday'
                ));
                if(!customCompute($feed)){
                    $this->feed_m->insert_feed(
                        array(
                            'itemID'         => $holidayID,
                            'userID'         => $holiday->create_userID,
                            'usertypeID'     => $holiday->create_usertypeID,
                            'itemname'       => 'holiday',
                            'schoolyearID'   => $holiday->schoolyearID,
                            'published'      => $holiday->published,
                            'published_date' => $holiday->published_date,
                            'status'         => 'public',
                        )
                    );
                }  			    
            }
        }

         // activities migrations
         $activities = $this->activities_m->forMigrations();
         if(customCompute($activities)){
             foreach($activities as $activity){
                $activityID = $activity->activitiesID;
                $feed = $this->feed_m->get_single_feed(array(
                     'itemID' => $activityID, 
                     'itemname' => 'activity'
                    ));
                if(!customCompute($feed)){
                    $this->feed_m->insert_feed(
                        array(
                            'itemID'         => $activityID,
                            'userID'         => $activity->userID,
                            'usertypeID'     => $activity->usertypeID,
                            'itemname'       => 'activity',
                            'schoolyearID'   => $activity->schoolyearID,
                            'published'      => 1,
                            'published_date' => $activity->create_date,
                            'status'         => 'public',
                        )
                    );
                }  	
             }
         }


    }
}