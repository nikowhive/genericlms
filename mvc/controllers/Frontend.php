<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Frontend extends Frontend_Controller
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

    protected $_pageName;
    protected $_templateName;
    protected $_homepage;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pages_m');
        $this->load->model('media_gallery_m');
        $this->load->model('slider_m');
        $this->load->model('notice_m');
        $this->load->model('event_m');
        $this->load->model('feed_m');
        $this->load->model('holiday_m');
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
        $this->load->model('popupimages_m');
        $this->load->model('section_m');
        $this->load->model('classgroup_m');
        $this->load->model('courses_m');
        $this->load->model('unit_m');
        $this->load->model('chapter_m');
        $this->load->model("faq_m");
        $this->load->model("classes_m");
        $this->load->model("classes_block_type_m");
        $this->load->model("classes_content_blocks_m");
        $this->load->model("classes_extra_information_m");
        $this->load->model("classes_detail_content_m");
        $this->load->model("enrollment_m");
    }

    public function index()
    {

        redirect(base_url('signin/index'));
        $type = htmlentities(escapeString($this->uri->segment(3)));
        $url  = htmlentities(escapeString($this->uri->segment(4)));

        if ($type && $url) {
            redirect(base_url('frontend/' . $type . '/' . $url));
        } else {
            if (customCompute($this->data['homepage'])) {

                if (isset($this->data['homepage']->pagesID)) {
                    $this->page($this->data['homepage']->url);
                } elseif (isset($this->data['homepage']->postsID)) {
                    $this->post($this->data['homepage']->url);
                } else {
                    $this->home();
                }
            } else {
                $this->home();
            }
        }
    }

    public function page($url)
    {
        if ($url) {
            if ($url == 'login') {
                redirect(base_url('signin/index'));
            }

            $featured_image = [];
            $page           = $this->pages_m->get_single_pages(['url' => $url]);
            if (customCompute($page)) {
                $this->_pageName     = $page->title;
                $this->_templateName = $page->template;
                $sliders             = $this->slider_m->get_slider_join_with_media_gallery($page->pagesID);

                if (!empty($page->featured_image)) {
                    $featured_image = $this->media_gallery_m->get_single_media_gallery(['media_galleryID' => $page->featured_image]);
                }

                if ($page->template == 'none') {
                    $this->bladeView->render('views/templates/none', compact('page', 'featured_image', 'sliders'));
                } elseif ($page->template == 'blog') {
                    $featured_image = [];
                    $posts          = $this->posts_m->get_order_by_posts(['status' => 1]);
                    if (customCompute($posts)) {
                        $featured_image = pluck(
                            $this->media_gallery_m->get_order_by_media_gallery(['media_gallery_type' => 1]),
                            'obj',
                            'media_galleryID'
                        );
                    }
                    $this->bladeView->render(
                        'views/templates/' . $this->_templateName,
                        compact('page', 'posts', 'featured_image', 'sliders')
                    );
                } elseif ($page->template == 'notice') {
                    $notices = $this->notice_m->get_order_by_notice(['status' => 'public']);
                    $this->bladeView->render(
                        'views/templates/' . $this->_templateName,
                        compact('page', 'featured_image', 'sliders', 'notices')
                    );
                } elseif ($page->template == 'event') {
                    $events = $this->event_m->get_order_by_event(['status' => 'public']);
                    foreach ($events as $index => $value) {
                        $medias = $this->event_media_m->get_single_event_media(["eventID" => $value->eventID]);
                        $events[$index]->photo = isset($medias->attachment) ? $medias->attachment : '';
                    }
                    $this->bladeView->render(
                        'views/templates/' . $this->_templateName,
                        compact('page', 'featured_image', 'sliders', 'events')
                    );
                } elseif ($page->template == 'home') {
                    $popupImages = $this->popupimages_m->get_active_images();

                    $this->data['load_feeds'] = $this->loadFeed();
                    $classgroups = $this->class_group();
                    $userType = $this->session->userdata('usertypeID');
                    $feeds = $this->data['load_feeds']['feeds'];
                    // $comments = $this->data['load_feeds']['comments'];
                    $noticesMedia = $this->data['load_feeds']['noticesMedia'];
                    $eventsMedia = $this->data['load_feeds']['eventsMedia'];
                    $holidaysMedia =  $this->data['load_feeds']['holidaysMedia'];
                    $activitiesMedia = $this->data['load_feeds']['activitiesMedia'];
                    $user = getAllSelectUser();


                    $this->bladeView->render(
                        'views/templates/' . $this->_templateName,
                        compact('page', 'featured_image', 'user', 'sliders', 'classgroups', 'feeds', 'userType', 'noticesMedia', 'eventsMedia', 'holidaysMedia', 'activitiesMedia', 'popupImages')
                    );
                } elseif ($page->template == 'courses') {
                    $classgroups = $this->classgroup_m->get_classgroup();
                    foreach ($classgroups as $index => $class) {
                        $classgroups[$index]->classes = $this->classes_m->general_get_order_by_classes(["classgroupID" => $class->classgroupID,'status' => 'published']); 
                        foreach($classgroups[$index]->classes as $i =>$class){
                            $classgroups[$index]->classes[$i]->extra = $this->classes_extra_information_m->get_single_classes_extra_information(['classes_id' => $class->classesID]);
                        }
                    }
                    $this->bladeView->render(
                        'views/templates/' . $this->_templateName,
                        compact('page', 'featured_image', 'sliders', 'classgroups')
                    );
                } elseif ($page->template == 'coursedetail') { 
                    $classID = htmlentities(escapeString($this->uri->segment(4)));
                    $class = $this->classes_m->get_single_classes(array('classesID' => $classID));
                    $courses = $this->courses_m->get_join_courses_subject( $classID);
                    foreach($courses as $index=> $course){
                        // $courses[$index]->units = $this->courses_m->get_course_unit_by_course($course->id);
                        // foreach($courses[$index]->units as $i => $unit){
                        //     $courses[$index]->units[$i]->chapters = $this->chapter_m->get_chapter_from_unit_id($unit->id);
                        //     foreach($courses[$index]->units[$i]->chapters as $k => $chapter){
                        //         $courses[$index]->units[$i]->chapters[$k]->contents = $this->courses_m->get_content($chapter->id); 
                        //     }
                        // }
                        $courses[$index]->unit = $this->unit_m->get_units_count_by_subject_id($course->subject_id)->count;
                        $courses[$index]->chapter = $this->chapter_m->get_chapters_count_by_subject_id($course->subject_id)->count;
                        $courses[$index]->content = count($this->courses_m->get_content_by_course($course->id));
                        
                    }
                    $enrollments = $this->enrollment_m->get_enrollment_by_class($classID);
                    $contentBlocks = $this->classes_content_blocks_m->get_order_by_content_blocks(['classes_id' => $classID]);
                    $extraInformations = $this->classes_extra_information_m->get_single_classes_extra_information(['classes_id' => $classID]);

                    $newContentBlocks = [];
                    if(customCompute($contentBlocks)){
                        foreach($contentBlocks as $contentBlock){
                            if($contentBlock->type_id == 1){
                                $detail = $this->classes_detail_content_m->get_single_detail_content(['classes_content_blocks_id'=> $contentBlock->id]);
                                $newContentBlocks[] = [
                                    'blockID'        => $contentBlock->id,
                                    'type_id'        => $contentBlock->type_id,
                                    'order'          => $contentBlock->order,
                                    'title'          => $detail->title,
                                    'description'    => $detail->body,
                                    'image'          => $detail->image,
                                ];
                            }elseif($contentBlock->type_id == 3){
                                $faqs = $this->faq_m->get_faq_by_class($contentBlock->classes_id);
                                $newContentBlocks[] = [
                                    'blockID'        => $contentBlock->id,
                                    'type_id'        => $contentBlock->type_id,
                                    'order'          => $contentBlock->order,
                                    'faq'            => $faqs,
                                ];
                            }
                            else{
                                $newContentBlocks[] = [
                                    'blockID'        => $contentBlock->id,
                                    'type_id'        => $contentBlock->type_id,
                                    'order'          => $contentBlock->order,
                                ];
                            }
                        }
                    }
                    $this->bladeView->render(
                        'views/templates/' . $this->_templateName,
                        compact('page', 'featured_image', 'sliders','class','courses','enrollments','newContentBlocks','extraInformations')
                    );
                }
                 else {

                    $this->bladeView->render(
                        'views/templates/' . $this->_templateName,
                        compact('page', 'featured_image', 'sliders')
                    );
                }
            } else {
                $this->_templateName = 'page404';
                $this->bladeView->render('views/templates/' . $this->_templateName);
            }
        } else {
            $this->_templateName = 'page404';
            $this->bladeView->render('views/templates/' . $this->_templateName);
        }
    }

    public function class_group()
    {
        $this->data['classgroups'] = $this->classgroup_m->get_class_group_with_classes(3, 0);
        foreach ($this->data['classgroups'] as $index => $class) {
            $this->data['classgroups'][$index]->classes = $this->classes_m->get_limit_classes($class->classgroupID, 2, 0);
            $this->data['classgroups'][$index]->count_classes = count($this->classes_m->general_get_order_by_classes(["classgroupID" => $class->classgroupID]));
        }
        return $this->data['classgroups'];
    }

    public function loadFeed()
    {

        $this->data['feed_type'] = 'feed';
        $this->data['userType'] = $this->session->userdata('usertypeID');

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $isNotAdmin = $this->data['userType'] != 1;
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

        $this->data['feeds'] = $feeds;
        // $this->data['comments'] = $comments;
        $this->data['noticesMedia'] = $noticesMedia;
        $this->data['eventsMedia'] = $eventsMedia;
        $this->data['holidaysMedia'] = $holidaysMedia;
        $this->data['activitiesMedia'] = $activitiesMedia;
        $this->data['user'] = getAllSelectUser();

        return $this->data;
    }

    public function post($url)
    {
        if ($url) {
            if ($url == 'login') {
                redirect(base_url('signin/index'));
            }

            $featured_image = [];
            $post           = $this->posts_m->get_single_posts(['url' => $url]);
            if (customCompute($post)) {
                $this->_pageName     = $post->title;
                $this->_templateName = 'postnone';
                $posts               = $this->posts_m->get_order_by_posts(['status' => 1]);
                if (!empty($post->featured_image)) {
                    $featured_image = $this->media_gallery_m->get_single_media_gallery(['media_galleryID' => $post->featured_image]);
                }

                $this->bladeView->render(
                    'views/templates/' . $this->_templateName,
                    compact('post', 'posts', 'featured_image')
                );
            } else {
                $this->_templateName = 'page404';
                $this->bladeView->render('views/templates/' . $this->_templateName);
            }
        } else {
            $this->_templateName = 'page404';
            $this->bladeView->render('views/templates/' . $this->_templateName);
        }
    }

    public function home()
    {
        $this->bladeView->render('views/templates/homeempty');
    }

    public function homewithpopup($data)
    {
        $this->bladeView->render('views/templates/homeempty', $data);
    }

    public function event()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {
            $eventView = $this->event_m->get_single_event(['eventID' => $id]);
            $eventView->medias = $this->event_media_m->get_order_by_event_media(["eventID" => $id]);

            if (count($eventView->medias) == 1) {
                foreach ($eventView->medias as $index => $value) {
                    $eventView->photo = isset($value->attachment) ? $value->attachment : '';
                }
            }

            if (customCompute($eventView)) {
                $this->bladeView->render('views/templates/eventview', compact('eventView'));
            } else {
                $this->_templateName = 'page404';
                $this->bladeView->render('views/templates/' . $this->_templateName);
            }
        } else {
            $this->_templateName = 'page404';
            $this->bladeView->render('views/templates/' . $this->_templateName);
        }
    }

    public function eventGoing()
    {
        $status = false;
        $id     = htmlentities(escapeString($this->input->post('id')));
        if ((int) $id) {
            if ($this->session->userdata('loggedin')) {
                $event = $this->event_m->get_single_event(['eventID' => $id]);
                if (customCompute($event)) {
                    $username = $this->session->userdata("username");
                    $usertype = $this->session->userdata("usertype");
                    $photo    = $this->session->userdata("photo");
                    $name     = $this->session->userdata("name");

                    $this->load->model('eventcounter_m');
                    $have = $this->eventcounter_m->get_order_by_eventcounter([
                        "eventID"  => $id,
                        "username" => $username,
                        "type"     => $usertype
                    ], true);

                    if (customCompute($have)) {
                        $array = ['status' => 1];
                        $this->eventcounter_m->update($array, $have[0]->eventcounterID);
                        $status  = true;
                        $message = 'You are add this event';
                    } else {
                        $array = [
                            'eventID'  => $id,
                            'username' => $username,
                            'type'     => $usertype,
                            'photo'    => $photo,
                            'name'     => $name,
                            'status'   => 1
                        ];
                        $this->eventcounter_m->insert($array);
                        $status  = true;
                        $message = 'You are add this event';
                    }
                } else {
                    $message = 'Event id does not found';
                }
            } else {
                $message = 'Please login';
            }
        } else {
            $message = 'ID is not int';
        }

        $json = [
            "message" => $message,
            'status'  => $status,
        ];
        header("Content-Type: application/json", true);
        echo json_encode($json);
        exit;
    }

    public function notice()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {
            $noticeView = $this->notice_m->get_single_notice(['noticeID' => $id]);
            if (customCompute($noticeView)) {
                $this->bladeView->render('views/templates/noticeview', compact('noticeView'));
            } else {
                $this->_templateName = 'page404';
                $this->bladeView->render('views/templates/' . $this->_templateName);
            }
        } else {
            $this->_templateName = 'page404';
            $this->bladeView->render('views/templates/' . $this->_templateName);
        }
    }

    public function contactMailSend()
    {
        $name    = $this->input->post('name');
        $email   = $this->input->post('email');
        $subject = $this->input->post('subject');
        $message = $this->input->post('message');
        if ($name && $email && $subject && $message) {
            $this->load->library('email');
            $this->email->set_mailtype("html");
            if (frontendData::get_backend('email')) {
                $this->email->from($email, frontendData::get_backend('sname'));
                $this->email->to(frontendData::get_backend('email'));
                $this->email->subject($subject);
                $this->email->message($message);
                $this->email->send();
                $this->session->set_flashdata('success', 'Email send successfully!');
                echo 'success';
            } else {
                $this->session->set_flashdata('error', 'Set your email in general setting');
            }
        } else {
            $this->session->set_flashdata('error', 'oops! Email not send!');
        }
    }
}
