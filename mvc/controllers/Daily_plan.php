<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Daily_plan extends Admin_Controller
{
    function __construct()
    {
        
        parent::__construct();
        $this->load->model("job_m");
        $this->load->model("mobile_job_m");
        $this->load->model("daily_plan_m");
        $this->load->model("daily_plan_version_m");
        $this->load->model("daily_plan_comment_m");
        $this->load->model("daily_plan_media_m");
        $this->load->model("student_m");
        $this->load->model("notice_m");
        $this->load->model("feed_m");
        $this->load->model("courses_m");
        $this->load->model("unit_m");
        $this->load->model("chapter_m");
        $this->load->model("subject_m");
        $this->load->model("section_m");
        $this->load->model("lesson_plan_m");
        $this->load->library('session');
        $this->load->model("subjectattendance_m");

        //load form validation library
        $this->load->library('form_validation');
        //load file helper
        $this->load->helper('file');
        $language = $this->session->userdata('lang');
        $this->lang->load('dailyplan', $language);
        $this->load->helper('date');
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("daily_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]',
            ),
            array(
                'field' => 'daily_date',
                'label' => $this->lang->line("daily_date"),
                'rules' => 'trim|required|xss_clean|max_length[128]',
            ),
            array(
                'field' => 'activities',
                'label' => $this->lang->line("activities"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'assignment',
                'label' => $this->lang->line("assignment"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'lesson_id',
                'label' => $this->lang->line("daily_select_lesson"),
                'rules' => 'trim|xss_clean|max_length[11]',
                // 'errors' => array('greater_than' => 'You must select a lesson.')
            ),
            array(
                'field' => 'caption[]',
                'label' => $this->lang->line("daily_caption"),
                'rules' => 'trim|xss_clean|max_length[128]',
            ),

            array(
                'field' => 'upload_Files[]',
                'label' => $this->lang->line("daily_file"),
                'rules' => 'trim|max_length[512]|xss_clean|callback_fileupload_multiple',
            ),
        );
        return $rules;
    }

    function fileupload_multiple()
    {
        $id         = htmlentities(escapeString($this->uri->segment(3)));
        $course = isset($_GET['course']) ? $_GET['course'] : '';

        if ((int) $id) {
            $this->data['versions'] =   $this->daily_plan_version_m->get_order_by_daily_plan_version(["daily_plan_id" => $id, "finalized_id" => 1]);
            foreach ($this->data['versions'] as $index1 => $v2) {
                $this->data['versions'][$index1]->media = $this->daily_plan_media_m->get_order_by_daily_plan_media(["daily_plan_version_id" => $v2->id,]);
            }
        }

        if (!empty($_FILES['upload_Files']['name'])) {
            $filesCount = count($_FILES['upload_Files']['name']);

            for ($i = 0; $i < $filesCount; $i++) {
                $file_name          = $_FILES["upload_Files"]['name'][$i];
                $original_file_name = $file_name;
                $random             = random19();
                $makeRandom         = hash('md5', $random . $_POST['caption'][$i] . config_item("encryption_key"));
                $file_name_rename   = $makeRandom;
                $explode            = explode('.', $file_name);
                $_FILES['upload_File']['name'] = $_FILES['upload_Files']['name'][$i];
                $_FILES['upload_File']['type'] = $_FILES['upload_Files']['type'][$i];
                $_FILES['upload_File']['tmp_name'] = $_FILES['upload_Files']['tmp_name'][$i];
                $_FILES['upload_File']['error'] = $_FILES['upload_Files']['error'][$i];
                $_FILES['upload_File']['size'] = $_FILES['upload_Files']['size'][$i];
                $uploadPath = './uploads/images/';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'pdf|doc|docx|csv|PDF|DOC|XML|DOCX|xls|xlsx|gif|jpg|png|jpeg';
                $this->load->library('upload', $config);
                // $this->upload->initialize($config);
                if (!$this->upload->do_upload("upload_File")) {
                    $this->form_validation->set_message("fileupload_multiple", $this->upload->display_errors());
                    return false;
                } else {
                    $fileData = $this->upload->data();

                    $this->uploadData[$i]['file'] = $fileData['file_name'];
                    $this->uploadData[$i]['caption'] = $_POST['caption'][$i];
                }
            }

            if (!$this->uploadData) {
                //Insert file information into the database
                return true;
            }
        } else {
            // return true;
            if (customCompute($this->data['versions'][$index1]->media)) {

                $array1 = array(
                    "title" => $this->input->post("title"),
                    "unit_id" => $this->input->post("unitId"),
                    "chapter_id" => $this->input->post("chapterId"),
                    "teacherID" => $this->data['usertypeID'],
                    "lesson_id" => $this->input->post('lesson_id'),
                    "absent_student_count" => $this->input->post('absent_student_count'),
                    "absent_students" => $this->input->post('absent_students'),
                    "assignments" => $this->input->post('assignment'),
                    "feedback" => $this->input->post('feedback'),
                    "activities" => $this->input->post('activities'),
                    "remarks" => $this->input->post('remark'),
                    "create_date" => date("Y-m-d H:i:s", strtotime($this->input->post("daily_date").' '.date('H:i:s'))),
                );

                $this->daily_plan_m->update($array1, $id);


                $this->daily_plan_media_m->update_batch($this->data['versions'][$index1]->media, $this->data['versions']->id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("courses/daily/" . $course));
                return true;
            }
            $this->uploadData = [];
        }
    }

    public function add()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 2)) {

            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/datepicker/datepicker.css',
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                ),
                'js'  => array(
                    'assets/datepicker/datepicker.js',
                    'assets/select2/select2.js',

                ),
            );
            $this->data['usertypeID'] = $this->session->userdata('usertypeID');
            $this->data['usertype'] = $this->session->userdata('usertype');
            $course = htmlentities(escapeString($this->uri->segment(3)));
            $chapter_id = isset($_GET['chapter']) ? $_GET['chapter'] : '';
            $unit = isset($_GET['unit']) ? $_GET['unit'] : '';
            $section_id = isset($_GET['section']) ? $_GET['section'] : '';

            $classesID = 0;

            if (isset($course)) {
                $this->data['course']    = $this->courses_m->get_all_join_courses_based_on_course_id($course);
                $classesID               = $this->data['course']->class_id;
                $this->data['subjectID'] = $this->data['course']->subject_id;
                $this->data['units']     = $this->unit_m->get_units_by_subject_id($this->data['subjectID']);

                if (isset($_GET['unit'])) {
                    $this->data['chapters']  = $this->chapter_m->get_chapter_from_unit_id($_GET['unit']);
                }
            }

            $this->data['classes'] = $this->classes_m->get_classes();

            if (isset($_POST['course'])) {
                $classesID = $this->input->post("classesID");
            }

            if ($classesID != 0) {
                $this->data['classesID'] = $classesID;
                $this->data['subjects']  = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
                $this->data['sections']  = $this->section_m->general_get_order_by_section(array("classesID" => $classesID));
                $this->data['lessons'] = $this->lesson_plan_m->get_order_by_lesson_plan(["course_id" => $course]);
                // $this->data['students']  = count($this->student_m->get_students($classesID));
                $this->data['students']  = count($this->student_m->get_students_from_section_id($section_id));
                
            } else {
                $this->data['classesID'] = 0;
                $this->data['subjects']  = [];
                $this->data['sections']  = [];
                $this->data['subjectID'] = 0;
                $this->data['lessons'] = 0;
                $this->data['students'] = [];
            }
            $array = [];
            $date = date('m/d/Y h:i:s a', time());
            $std_atd = $this->data['absent_student'] = $this->subjectattendance_m->getAttendancea($this->data['classesID'], $date, $this->data['subjectID'], $f = true);

            if ($_POST) {
                // dd($_POST);
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $this->data["subview"] = "courses/daily/add";
                    $this->load->view('_layout_course', $this->data);
                } else {

                    

                    $array = array(
                        "course_id"             => $course,
                        "unit_id"               => $unit,
                        "chapter_id"            => $chapter_id,
                        "classesID"             => $this->data['classesID'],
                        "sectionID"             => $section_id,
                        "teacherID"             => $this->data['usertypeID'],
                        "subject_id"            => $this->data['subjectID'],
                        "lesson_id"             => $_POST['lesson_id'],
                        "absent_student_count"  => $_POST['absent_student_count'],
                        "present_student_count" => $_POST['present_student'],
                        "absent_students"       => $_POST['absent_students'],
                        "assignments"           => $_POST['assignment'],
                        "feedback"              => $_POST['feedback'],
                        "activities"            => $_POST['activities'],
                        "remarks"               => $_POST['remark'],
                        "title"                 => $_POST['title'],
                        "user_id"               => $this->data['usertypeID'],
                        "user_type"             => $this->data['usertype'],
                        "create_date"           => date("Y-m-d H:i:s", strtotime($this->input->post("daily_date").' '.date('H:i:s'))),
                        "schoolyearID"          => $this->session->userdata('defaultschoolyearID'),
                        "published"             => 1
                    );

                    
                   
                    $daily_plan_id = $this->daily_plan_m->insert($array);

                    if ($daily_plan_id) {

                         $record = $this->courses_m->get_join_courses_based_on_course_id($course);

                         $students =  $this->student_m->get_students_from_section_id($section_id);
                         if(count($students) > 0){
                              $studentarray = [];
                              foreach($students as $student){
                                 $studentarray[] = $student->studentID.'3';
                              }

                              $arrayData = [
                                "title" => 'Virtual diary of subject '.$record->subject.' added.',
                                "users" => serialize($studentarray),
                                "notice" => "Dear Students, <br/>" . " Virtual diary: ".$array['title']." has been added on " . $array['create_date'] . "<br/>" . " Thank you " . "<br/>" . $this->session->userdata("name"),
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
            
            
                                if(customCompute($studentarray)){
                                    $users = $studentarray;
                                    $noticeUsers = [];
                                    $feedUsers = [];
                                    if(customCompute($users)){
                                        foreach($users as $user){  
                                                $a = str_split($user);
                                                $user_id = substr($user, 0, -1);
                                                $user_type = substr($user, -1);
                                                $noticeUsers[] = [
                                                    'notice_id'  => $noticeID,
                                                    'user_id'    => (int)$user_id,
                                                    'usertypeID' => (int)$user_type
                                                ];
                                                $feedUsers[] = [
                                                    'feed_id'    => $feedID,
                                                    'user_id'    => $user_id,
                                                    'usertypeID' => $user_type
                                                ];
                                            }
                                            $this->notice_m->insert_batch_notice_user($noticeUsers);
                                            $this->feed_m->insert_batch_feed_user($feedUsers);	
                                        }
                                    }
            
                                    $this->pushNotification1($arrayData, $studentarray,$noticeID);
                                    $this->sendNotificationToAdmins($array);
                               
                            }

                         }


                        $version = array(
                            "daily_plan_id" => $daily_plan_id,
                            "user_id" => $this->data['usertypeID'],
                            "user_type" => $this->data['usertype'],
                            "finalized_id" => 1
                        );

                        $version_id = $this->daily_plan_version_m->insert($version);

                        $lists = [];

                        if($this->uploadData){
                            foreach ($this->uploadData as $v) {
                                $v['daily_plan_id'] = $daily_plan_id;
                                $v['daily_plan_version_id'] = $version_id;
                                $v['create_date'] = date('Y:m:d H:i:s');
                                array_push($lists, $v);
                            }
                        }

                        $this->daily_plan_media_m->insert_batch($lists);

                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("courses/daily?course=" . $course));
                    }
                }
            } else {
                $this->data["subview"] = "courses/daily/add";
                $this->load->view('_layout_course', $this->data);
            }
        }
    }

    public function edit()
    {
        if (isset($_GET['course'])) {

            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/datepicker/datepicker.css',
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',

                ),
                'js'  => array(
                    'assets/datepicker/datepicker.js',
                    'assets/select2/select2.js',

                ),
            );

            $course = isset($_GET['course']) ? $_GET['course'] : '';

            $this->data['course']    = $this->courses_m->get_all_join_courses_based_on_course_id($course);
            $classesID               = $this->data['course']->class_id;
            $this->data['subjectID'] = $this->data['course']->subject_id;
            $this->data['units']     = $this->unit_m->get_units_by_subject_id($this->data['subjectID']);
            $this->data['chapters']  = $this->chapter_m->get_chapter_from_subject_id($this->data['subjectID']);
        }

        $daily_id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['usertype'] = $this->session->userdata('usertype');
        $check_daily = $this->data['daily'] = $this->daily_plan_m->get_daily_plan($daily_id);

        $this->data['versions'] = $this->daily_plan_version_m->get_order_by_daily_plan_version(["daily_plan_id" => $daily_id, "finalized_id" => 1]);
        foreach ($this->data['versions'] as $index1 => $v2) {
            $this->data['versions'][$index1]->media = $this->daily_plan_media_m->get_order_by_daily_plan_media(["daily_plan_version_id" => $v2->id]);
        }

        if ((int) $daily_id) {
            if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID'))) {
                $this->data['classes'] = $this->classes_m->get_classes();
                $this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
                $section_id = $check_daily->sectionID;
                
                // $this->data['students']  = count($this->student_m->get_students($classesID));
                $this->data['students']  = count($this->student_m->get_students_from_section_id($section_id));
                $this->data['lessons'] = $this->lesson_plan_m->get_order_by_lesson_plan(["course_id" => $course]);
                if ($_POST) {

                    if ($this->input->post('title') == '') {
                        $rules = $this->form_validation->set_rules("title", 'title', 'trim|required|xss_clean');
                    } else {
                        $rules = $this->rules();
                    }
                    // dd($rules);
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "courses/daily/edit";
                        $this->load->view('_layout_course', $this->data);
                    } else {

                        $array1 = array(
                            "title" => $this->input->post("title"),
                            "unit_id" => $this->input->post("unitId"),
                            "chapter_id" => $this->input->post("chapterId"),
                            "teacherID" => $this->data['usertypeID'],
                            "lesson_id" => $_POST['lesson_id'],
                            "absent_student_count" => $_POST['absent_student_count'],
                            "present_student_count" => $_POST['present_student'],
                            "absent_students" => $_POST['absent_students'],
                            "assignments" => $_POST['assignment'],
                            "feedback" => $_POST['feedback'],
                            "activities" => $_POST['activities'],
                            "remarks" => $_POST['remark'],
                            "create_date" => date("Y-m-d H:i:s", strtotime($this->input->post("daily_date").' '.date('H:i:s'))),
                        );


                        $this->daily_plan_m->update($array1, $daily_id);
                        $daily_plan_id = $check_daily->id;

                        if ($daily_plan_id) {
                            $check_version = $this->daily_plan_version_m->get_order_by_daily_plan_version(["daily_plan_id" => $daily_plan_id]);
                            if ($check_version) {
                                $array[] = array(
                                    "finalized_id" => 0,
                                    "daily_plan_id" => $daily_plan_id
                                );
                                $this->daily_plan_version_m->update_batch($array, "daily_plan_id");
                            }
                            $version = array(
                                "daily_plan_id" => $daily_plan_id,
                                "user_id" => $this->data['usertypeID'],
                                "user_type" => $this->data['usertype'],
                                "finalized_id" => 1
                            );

                            $version_id = $this->daily_plan_version_m->insert($version);

                            $lists = [];

                            if($this->uploadData){
                                foreach ($this->uploadData as $v) {
                                    $v['daily_plan_id'] = $daily_plan_id;
                                    $v['daily_plan_version_id'] = $version_id;
                                    $v['create_date'] = date('Y:m:d H:i:s');
                                    array_push($lists, $v);
                                }
                            }
                            $this->daily_plan_media_m->insert_batch($lists);

                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("courses/daily?course=" . $course));
                        }
                    }
                } else {
                    $this->data['course']    = $this->courses_m->get_all_join_courses_based_on_course_id($course);
                    $this->data["subview"] = "courses/daily/edit";
                    $this->load->view('_layout_course', $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_course', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function view()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['usertype'] = $this->session->userdata('usertype');
        $this->data['course']    = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        $this->data['user'] = getAllSelectUser();
        if ((int) $id) {
            if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 3) || ($this->session->userdata('usertypeID') == 4)) {
                $this->data['dailys'] = $this->daily_plan_m->get_single_daily_plan(['id' => $id]);
                $this->data['daily_comments'] = pluck_multi_array($this->daily_plan_comment_m->get_order_by_daily_plan_comment(array('course_id' => $course)), 'obj', 'daily_plan_id');
                $this->data['versions'] = $this->daily_plan_version_m->get_order_by_daily_plan_version(["daily_plan_id" => $id]);
                foreach ($this->data['versions'] as $index1 => $v2) {
                    $this->data['versions'][$index1]->media = $this->daily_plan_media_m->get_order_by_daily_plan_media(["daily_plan_version_id" => $v2->id,]);
                }
                $this->data['chapter'] = $this->chapter_m->get_chapter($this->data['dailys']->chapter_id, true);

                $this->data["subview"] = "courses/daily/view";
                $this->load->view('_layout_main', $this->data);
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_course', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function studentview()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['usertype'] = $this->session->userdata('usertype');
        $this->data['course']    = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        $this->data['user'] = getAllSelectUser();
        if ((int) $id) {
            $this->data['dailys'] = $this->daily_plan_m->get_single_daily_plan(['id' => $id]);
            $this->data['versions'] = $this->daily_plan_version_m->get_order_by_daily_plan_version(["daily_plan_id" => $id, "finalized_id" => 1]);
            foreach ($this->data['versions'] as $index1 => $v2) {
                $this->data['versions'][$index1]->media = $this->daily_plan_media_m->get_order_by_daily_plan_media(["daily_plan_version_id" => $v2->id,]);
            }

            $this->data["subview"] = "courses/daily/student_view";
            $this->load->view('_layout_main', $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function get_more_version($rowno = '')
    {
        $daily_id = $_GET['daily'];
        $version_id = $_GET['version'];
        $version = $this->daily_plan_version_m->get_single_daily_plan_version(['id' => $version_id]);
        $users_record = $this->daily_plan_media_m->get_order_by_daily_plan_media(["daily_plan_version_id" => $version_id]);
        $data['result'] = $users_record;
        $data['version'] = $version;
        $data['row'] = $rowno;
        echo json_encode($data);
    }

    public function comment()
    {
        if ($_POST) {
            $array = array(
                "daily_plan_id" => $this->input->post('daily_id'),
                "course_id" => $this->input->post('course'),
                "user_id" => $this->session->userdata("loginuserID"),
                "user_type_id" => $this->session->userdata("usertypeID"),
                "comment" => $this->input->post('comment'),
                "create_date" => date("Y-m-d H:i:s")

            );

            $data = $this->daily_plan_comment_m->insert($array);
            echo $data;
        }
    }

    public function delete_comment()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertype') == 'Admin')) {

            $id = $this->input->post('id');
            $usertype = $this->session->userdata('usertype');
            $userID = $this->session->userdata('loginuserID');

            if ((int)$id) {
                $comment = $this->daily_plan_comment_m->get_daily_plan_comment($id);
                $daily_plan = $this->daily_plan_m->get_daily_plan($comment->daily_plan_id);
                if (($usertype == $daily_plan->user_type && $userID == $daily_plan->user_id) || ($usertype == 'Admin')) {
                    $this->daily_plan_comment_m->delete($id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                }

                $retArray['status'] = TRUE;;
                $retArray['message'] = $this->lang->line('menu_success');
                echo json_encode($retArray);
                exit;
            } else {
                redirect(base_url("daily_plan/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }


    public function ajaxChangeFileStatus($id)
    {
        $daily = $this->daily_plan_m->get_daily_plan($id);
        $array = [
            'published' => $daily->published == 2 ? 1 : 2,
        ];
        if ($this->daily_plan_m->update($array, $id) && $array['published'] == 1) {
            $record = $this->courses_m->get_join_courses_based_on_course_id($daily->course_id);
            $title = 'Daily Plan Published';
            $notice = "Daily Plan " . $daily->title . " for class " . $record->classes . " of " . $record->subject . " has been published";
            $this->notification($title, $notice, $record->classesID);
        }
    }

    public function ajaxChangeVersionStatus($id)
    {
        $version = $this->daily_plan_version_m->get_daily_plan_version($id);
        $array[] = array(
            "finalized_id" => 0,
            "daily_plan_id" => $version->daily_plan_id
        );
        $res = $this->daily_plan_version_m->update_batch($array, "daily_plan_id");

        $array1 = array(
            "finalized_id" =>  1,
        );
        $this->daily_plan_version_m->update($array1, $id);
    }

    public function pushNotification($title, $class = null)
    {
        $this->job_m->insert_job([
            'name' => 'sendDailyPlanNotification',
            'payload' => json_encode([
                'class' => $class,
                'title' => $title, // title is compulsary
            ]),
        ]);
    }

    public function mobPushNotification($array)
    {
        $this->mobile_job_m->insert_job([
            'name' => 'sendDailyPlanNotification',
            'payload' => json_encode([
                'users' => $array['users'],
                'title' => $array['title'], // title is compulsary
                'message' => $array['notice']
            ]),
        ]);
    }

    public function notification($title, $notice, $class, $sectionID = '')
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $students = pluck($this->student_m->get_order_by_student_with_section1($class, $schoolyearID, $sectionID), 'studentID');
        foreach ($students as $index => $student) {
            $students[$index] = $student . '3';
        }
        $students = serialize($students);
        $array = array(
            "title" => $title,
            "notice" => $notice,
            "schoolyearID" => $schoolyearID,
            "users" => $students,
            "date" => date('Y-m-d'),
            "create_date" => date('Y-m-d H:i:s'),
            "create_userID" => $this->session->userdata('loginuserID'),
            "create_usertypeID" => $this->session->userdata('usertypeID'),

        );
        $insert_id = $this->notice_m->insert_notice($array);
        if ($class) {

            $this->pushNotification($notice, $class);
            $this->mobPushNotification($array);
        }
    }

    public function getDailyByAjax(Type $var = null)
    {
        $course = $this->input->post('course');
        $id = $this->input->post('id');

        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        $classesID = $this->data['course']->class_id;
        $this->data['subjectID'] = $this->data['course']->subject_id;
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $this->data['daily'] = $this->daily_plan_m->get_single_daily_plan(array('id' => $id));
        $this->data['daily_medias'] = $this->daily_plan_media_m->getfinaliziedVersion($id);
        $this->data['unit'] = $this->unit_m->get_units($this->data['daily']->unit_id);
        $this->data['chapter'] = $this->chapter_m->get_chapter($this->data['daily']->chapter_id, true);

        echo $this->load->view('courses/daily/getdailyreport', $this->data, true);
        exit;
    }

    public function sendNotificationToAdmins($array){
             
        $permission = $this->permission_m->general_get_single_permission(['name' => 'daily_plan_notification']);
        if($permission){ 
              $userTypes = $this->permission_m->usertypeByPermissionID($permission->permissionID);
              if(count($userTypes) > 0){

                  $record = $this->courses_m->get_join_courses_based_on_course_id($array['course_id']);   

                  $title = 'Daily Plan of subject '. $record->subject.' added';
                  $notice = "Daily Plan " . $array['title'] . " for class " . $record->classes . " of " . $record->subject . " has been added";

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


  function pushNotification1($array, $postusers,$noticeID)
  {

      $sall_users = serialize($postusers);

      $this->job_m->insert_job([
          'name'      => 'sendNotice',
          'itemID'    => $noticeID,
          'payload'   => json_encode([
              'title' => $array['title'],  // title is necessary
              'users' => $sall_users,
          ]),
      ]);

      $this->mobile_job_m->insert_job([
          'name'        => 'sendNotice',
          'itemID'      => $noticeID,
          'payload'     => json_encode([
              'title'   => $array['title'],  // title is necessary
              'users'   => $sall_users,
              'message' => $array['notice']
          ]),
      ]);
  }




}
