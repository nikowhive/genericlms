<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Frontend extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('feed_m');
        $this->load->model('classes_m');
        $this->load->model('classgroup_m');
        $this->load->model('notice_media_m');
        $this->load->model('notice_comment_m');
        $this->load->model('event_media_m');
        $this->load->model('eventcounter_m');
        $this->load->model('event_comment_m');
        $this->load->model('activitiesmedia_m');
        $this->load->model('activitiescomment_m');
        $this->load->model('event_comment_m');
        $this->load->model('classes_extra_information_m');
        $this->load->library('session');
    }

    public function classgroup_get()
    {
        $this->retdata['classgroups']= $this->classgroup_m->get_classgroup();
        foreach ($this->retdata['classgroups'] as $index => $class) { 
            $this->retdata['classgroups'][$index]->classes = $this->classes_m->general_get_order_by_classes(["classgroupID" => $class->classgroupID,'status' => 'published']); 
            foreach($this->retdata['classgroups'][$index]->classes as $i =>$class){
                $this->retdata['classgroups'][$index]->classes[$i]->extra = $this->classes_extra_information_m->get_single_classes_extra_information(['classes_id' => $class->classesID]);
            }
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'retdata'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
    
    public function classes_extra_info_get(){
        $this->retdata['classes_info'] = $this->classes_m->get_classes_with_extra_information();
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'retdata'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function classes_enrollements_get(){

        $enrollments = $this->classes_m->get_classes_enrollments();
        $result = [];
        if(count($enrollments)){
           foreach($enrollments as $enrollment){
                $result[$enrollment->group][$enrollment->classes][] = $enrollment->title;
           }
        }

        $this->retdata['enrollments'] = $result;

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'retdata'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }


    public function published_classgroup_get()
    {
        $this->retdata['classgroups']= $this->classgroup_m->get_all_published_class_groups();
        foreach ($this->retdata['classgroups'] as $index => $class) {
            $this->retdata['classgroups'][$index]->classes = $this->classes_m->general_get_order_by_classes(["classgroupID" => $class->classgroupID,'status' => 'published']); 
            foreach($this->retdata['classgroups'][$index]->classes as $i =>$class){
                $this->retdata['classgroups'][$index]->classes[$i]->extra = $this->classes_extra_information_m->get_single_classes_extra_information(['classes_id' => $class->classesID]);
            }
        }

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'retdata'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }



    


    public function feed_get()
    {

        $this->retdata['feed_type'] = 'feed';
        $this->retdata['userType'] = $this->session->userdata('usertypeID');

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $isNotAdmin = $this->retdata['userType'] != 1;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $feeds = $this->feed_m->get_public_feeds(4, 0, $schoolyearID, $isNotAdmin ? $this->session->userdata('username') : null);

        $username = $isNotAdmin ? $this->session->userdata('username') : null;

        $comments = [];
        $noticesMedia  = [];
        $eventsMedia  = [];
        $holidaysMedia  = [];
        $activitiesMedia  = [];
        foreach ($feeds as $key => $feed) {
            $feeds[$key]->unique_id = uniqid();
            if ($feed->type == 'notice') {
                $noticeID = $feed->itemID;
                $notice_media = $this->notice_media_m->get_order_by_notice_media(['noticeID' => $noticeID]);
                $n_n_media = array();
                foreach ($notice_media as $media) {
                    $n_n_media[] = $media->attachment;
                }
                $feeds[$key]->media = $n_n_media;

                $notice_comments_count = count($this->notice_comment_m->paginatedNoticeComments('', '', ['noticeID' => $noticeID]));
                $feeds[$key]->comment_count = $notice_comments_count;

                $notice_comments = $this->notice_comment_m->paginatedNoticeComments(5, 0, ['noticeID' => $noticeID]);

                if (customCompute($notice_comments)) {
                    $reverse = array_reverse($notice_comments);
                    $comments['notice'][$noticeID] = $reverse;
                }

                // for gallery
                if (customCompute($notice_media)) {
                    $noticesMedia[$noticeID] = $notice_media;
                }
            }

            if ($feed->type == 'event') {
                $eventID = $feed->itemID;
                $event_media = $this->event_media_m->get_order_by_event_media(['eventID' => $eventID]);
                $n_e_media = array();
                foreach ($event_media as $event) {
                    $n_e_media[] = $event->attachment;
                }
                $feeds[$key]->media = $n_e_media;
                $feeds[$key]->going = $this->eventcounter_m->getEventCount($feed->itemID, 1);
                $feeds[$key]->not_going = $this->eventcounter_m->getEventCount($feed->itemID, 0);

                if ($username) {
                    $eventCount = $this->eventcounter_m->getEventCountByRow($feed->itemID, $username);
                    if ($eventCount)
                        $feeds[$key]->is_going = $eventCount->status ? 1 : 0;
                }

                $event_comments_count = count($this->event_comment_m->paginatedEventComments('', '', ['eventID' => $eventID]));
                $feeds[$key]->comment_count = $event_comments_count;

                $event_comments = $this->event_comment_m->paginatedEventComments(5, 0, ['eventID' => $eventID]);
                if (customCompute($event_comments)) {
                    $reverse = array_reverse($event_comments);
                    $comments['event'][$eventID] = $reverse;
                }

                // for gallery
                if (customCompute($event_media)) {
                    $eventsMedia[$eventID] = $event_media;
                }
            }

            if ($feed->type == 'holiday') {
                $holidayID = $feed->itemID;
                $holiday_media = $this->holiday_media_m->get_order_by_holiday_media(['holidayID' => $holidayID]);
                $n_h_media = array();
                foreach ($holiday_media as $media) {
                    $n_h_media[] = $media->attachment;
                }
                $feeds[$key]->media = $n_h_media;

                $holiday_comments_count = count($this->holiday_comment_m->paginatedHolidayComments('', '', ['holidayID' => $holidayID]));
                $feeds[$key]->comment_count = $holiday_comments_count;

                $holiday_comments = $this->holiday_comment_m->paginatedHolidayComments(5, 0, ['holidayID' => $holidayID]);

                if (customCompute($holiday_comments)) {
                    $reverse = array_reverse($holiday_comments);
                    $comments['holiday'][$holidayID] = $reverse;
                }

                // for gallery
                if (customCompute($holiday_media)) {
                    $holidaysMedia[$holidayID] = $holiday_media;
                }
            }
            if ($feed->type == 'activity') {
                $activityID = $feed->itemID;
                $activity_media = $this->activitiesmedia_m->get_order_by_activitiesmedia(['activitiesID' => $activityID]);
                $n_a_media = array();
                foreach ($activity_media as $activity) {
                    $n_a_media[] = $activity->attachment;
                }
                $feeds[$key]->media = $n_a_media;
                $feeds[$key]->enable_comment = 1;

                $activity_comments_count = count($this->activitiescomment_m->paginatedActivityComments('', '', ['activitiesID' => $activityID]));
                $feeds[$key]->comment_count = $activity_comments_count;

                $activity_comments = $this->activitiescomment_m->paginatedActivityComments(5, 0, ['activitiesID' => $activityID]);

                if (customCompute($activity_comments)) {
                    $reverse = array_reverse($activity_comments);
                    $comments['activity'][$activityID] = $reverse;
                }

                // for gallery
                if (customCompute($activity_media)) {
                    $activitiesMedia[$activityID] = $activity_media;
                }
            }
        }

        $this->retdata['feeds'] = $feeds;
        // $this->retdata['comments'] = $comments;
        $this->retdata['noticesMedia'] = $noticesMedia;
        $this->retdata['eventsMedia'] = $eventsMedia;
        $this->retdata['holidaysMedia'] = $holidaysMedia;
        $this->retdata['activitiesMedia'] = $activitiesMedia;
        // $this->retdata['user'] = getAllSelectUser();

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'retdata'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}
