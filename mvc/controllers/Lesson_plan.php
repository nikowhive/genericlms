<?php
class Lesson_plan extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("job_m");
        $this->load->model("mobile_job_m");
        $this->load->model("lesson_plan_m");
        $this->load->model("lesson_plan_media_m");
        $this->load->model("lesson_plan_version_m");
        $this->load->model("lesson_plan_comment_m");
        $this->load->model("student_m");
        $this->load->model("unit_m");
        $this->load->model("chapter_m");
        $this->load->model("notice_m");
        $this->load->model("courses_m");
        $this->load->library('session');
        $this->load->library('pagination');

        $this->load->library('form_validation');
        $this->load->helper('file');
        $language = $this->session->userdata('lang');
        $this->lang->load('lessonplan', $language);
        $this->load->helper('date');
    }

    public function rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("lesson_title"),
                'rules' => 'trim|required|xss_clean|max_length[128]',
            ),
            array(
                'field' => 'caption[]',
                'label' => $this->lang->line("lesson_caption"),
                'rules' => 'trim|required|xss_clean|max_length[128]',
            ),

            array(
                'field' => 'upload_Files[]',
                'label' => $this->lang->line("lesson_file"),
                'rules' => 'trim|max_length[512]|xss_clean|callback_fileupload',
            ),
        );
        return $rules;
    }

    public function unique_title()
    {
        if ($this->input->post('title') == '') {
            $this->form_validation->set_message("unique_title", "The %s field is required");
            return false;
        }
        return true;
    }

    public function add()
    {
        $chapter_id = isset($_GET['chapter']) ? $_GET['chapter'] : '';
        $unit = isset($_GET['unit']) ? $_GET['unit'] : '';
        $course = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['course']    = $this->courses_m->get_all_join_courses_based_on_course_id($course);

        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['usertype'] = $this->session->userdata('usertype');

        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                $this->data["subview"] = "courses/lesson/add";
                $this->load->view('_layout_course', $this->data);
            } else {

                $array = array(
                    "course_id" => $course,
                    "unit_id" => $unit,
                    "chapter_id" => $chapter_id,
                    "title" => $this->input->post('title'),
                    "user_id" => $this->data['usertypeID'],
                    "user_type" => $this->data['usertype'],
                    "schoolyearID"  => $this->session->userdata('defaultschoolyearID')
                );

                $lesson_plan_id = $this->lesson_plan_m->insert($array);

                if ($lesson_plan_id) {

                    $version = array(
                        "lesson_plan_id" => $lesson_plan_id,
                        "user_id" => $this->data['usertypeID'],
                        "user_type" => $this->data['usertype'],
                        "finalized_id" => 1
                    );

                    $version_id = $this->lesson_plan_version_m->insert_lesson_plan_version($version);

                    $lists = [];

                    foreach ($this->uploadData as $v) {
                        $v['lesson_plan_id'] = $lesson_plan_id;
                        $v['lesson_plan_version_id'] = $version_id;
                        $v['create_date'] = date('Y:m:d H:i:s');
                        array_push($lists, $v);
                    }
                    $this->lesson_plan_media_m->insert_batch_lessonmedia($lists);

                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("courses/lesson?course=" . $course));
                }
            }
        } else {

            $this->data["subview"] = "courses/lesson/add";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function edit()
    {
        if (isset($_GET['course'])) {
            $course = isset($_GET['course']) ? $_GET['course'] : '';

            $this->data['course']    = $this->courses_m->get_all_join_courses_based_on_course_id($course);
            $classesID               = $this->data['course']->class_id;
            $this->data['subjectID'] = $this->data['course']->subject_id;
            $this->data['units']     = $this->unit_m->get_units_by_subject_id($this->data['subjectID']);
            $this->data['chapters']  = $this->chapter_m->get_chapter_from_subject_id($this->data['subjectID']);
        }

        $lesson_id = htmlentities(escapeString($this->uri->segment(3)));


        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['usertype'] = $this->session->userdata('usertype');
        $check_lesson = $this->data['lesson'] = $this->lesson_plan_m->get_lesson_plan($lesson_id);

        $this->data['versions'] = $this->lesson_plan_version_m->get_order_by_lesson_plan_version(["lesson_plan_id" => $lesson_id, "finalized_id" => 1]);
        foreach ($this->data['versions'] as $index1 => $v2) {
            $this->data['versions'][$index1]->media = $this->lesson_plan_media_m->get_order_by_lessonmedia(["lesson_plan_version_id" => $v2->id,]);
        }

        if ((int) $lesson_id) {
            if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
                if ($_POST) {

                    if ($this->input->post('title') == '') {
                        $rules = $this->form_validation->set_rules("title", 'title', 'trim|required|xss_clean');
                    } else {
                        $rules = $this->rules();
                    }
                    $this->form_validation->set_rules($rules);

                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = "courses/lesson/edit";
                        $this->load->view('_layout_course', $this->data);
                    } else {

                        $array1 = array(
                            "title" => $this->input->post("title"),
                            "unit_id" => $this->input->post("unitId"),
                            "chapter_id" => $this->input->post("chapterId"),
                        );

                        $this->lesson_plan_m->update($array1, $lesson_id);
                        $lesson_plan_id = $check_lesson->id;

                        if ($lesson_plan_id) {
                            $check_version = $this->lesson_plan_version_m->get_order_by_lesson_plan_version(["lesson_plan_id" => $lesson_plan_id]);
                            if ($check_version) {
                                $array[] = array(
                                    "finalized_id" => 0,
                                    "lesson_plan_id" => $lesson_plan_id
                                );
                                $this->lesson_plan_version_m->update_batchlesson_plan_version($array, "lesson_plan_id");
                            }
                            $version = array(
                                "lesson_plan_id" => $lesson_plan_id,
                                "user_id" => $this->data['usertypeID'],
                                "user_type" => $this->data['usertype'],
                                "finalized_id" => 1
                            );

                            $version_id = $this->lesson_plan_version_m->insert_lesson_plan_version($version);

                            $lists = [];

                            foreach ($this->uploadData as $v) {
                                $v['lesson_plan_id'] = $lesson_plan_id;
                                $v['lesson_plan_version_id'] = $version_id;
                                $v['create_date'] = date('Y:m:d H:i:s');
                                array_push($lists, $v);
                            }
                            $this->lesson_plan_media_m->insert_batch_lessonmedia($lists);

                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("courses/lesson?course=" . $course));
                        }
                    }
                } else {
                    $this->data['course']    = $this->courses_m->get_all_join_courses_based_on_course_id($course);
                    $this->data["subview"] = "courses/lesson/edit";
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
                $this->data['lessons'] = $this->lesson_plan_m->get_single_lesson_plan(['id' => $id]);
                $this->data['lesson_comments'] = pluck_multi_array($this->lesson_plan_comment_m->get_order_by_lesson_plan_comments(array('course_id' => $course)), 'obj', 'lesson_plan_id');
                $this->data['versions'] = $this->lesson_plan_version_m->get_order_by_lesson_plan_version(["lesson_plan_id" => $id]);
                foreach ($this->data['versions'] as $index1 => $v2) {
                    $this->data['versions'][$index1]->media = $this->lesson_plan_media_m->get_order_by_lessonmedia(["lesson_plan_version_id" => $v2->id,]);
                }

                $this->data["subview"] = "courses/lesson/view";
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
            $this->data['lessons'] = $this->lesson_plan_m->get_single_lesson_plan(['id' => $id]);
            // $this->data['lesson_comments'] = pluck_multi_array($this->lesson_plan_comment_m->get_order_by_lesson_plan_comments(array('course_id' => $course)), 'obj', 'lesson_plan_id');
            $this->data['versions'] = $this->lesson_plan_version_m->get_order_by_lesson_plan_version(["lesson_plan_id" => $id, "finalized_id" => 1]);

            foreach ($this->data['versions'] as $index1 => $v2) {
                $this->data['versions'][$index1]->media = $this->lesson_plan_media_m->get_order_by_lessonmedia(["lesson_plan_version_id" => $v2->id,]);
            }


            $this->data["subview"] = "courses/lesson/student_view";
            $this->load->view('_layout_main', $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function get_more_version($rowno = '')
    {
        $lesson_id = $_GET['lesson'];
        $version_id = $_GET['version'];
        $version = $this->lesson_plan_version_m->get_single_lesson_plan_version(['id' => $version_id]);
        $users_record = $this->lesson_plan_media_m->get_order_by_lessonmedia(["lesson_plan_version_id" => $version_id]);
        $data['result'] = $users_record;
        $data['version'] = $version;
        $data['row'] = $rowno;
        echo json_encode($data);
    }

    public function comment()
    {

        if ($_POST) {
            $array = array(
                "lesson_plan_id" => $this->input->post('lesson_id'),
                "course_id" => $this->input->post('course'),
                "user_id" => $this->session->userdata("loginuserID"),
                "user_type_id" => $this->session->userdata("usertypeID"),
                "comment" => $this->input->post('comment'),
                "create_date" => date("Y-m-d H:i:s")

            );

            $data = $this->lesson_plan_comment_m->insert_lesson_plan_comments($array);
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
                $comment = $this->lesson_plan_comment_m->get_lesson_plan_comments($id);
                $lesson_plan = $this->lesson_plan_m->get_lesson_plan($comment->lesson_plan_id);
                if (($usertype == $lesson_plan->user_type && $userID == $lesson_plan->user_id) || ($usertype == 'Admin')) {
                    $this->lesson_plan_comment_m->delete_lesson_plan_comments($id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                }

                $retArray['status'] = TRUE;;
                $retArray['message'] = $this->lang->line('menu_success');
                echo json_encode($retArray);
                exit;
            } else {
                redirect(base_url("lesson_plan/index"));
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }



    public function delete()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $link = isset($_GET['link']) ? $_GET['link'] : '';
        if ((int) $id) {
            $this->lesson_plan_m->delete($id);
            $this->session->set_flashdata('success', $this->lang->line('menu_success'));

            redirect(base_url("courses/lesson?course=" . $course));
        } else {
            redirect(base_url("courses/lesson?course=" . $course));
        }
    }

    function fileupload()
    {
        $id         = htmlentities(escapeString($this->uri->segment(3)));
        $course = isset($_GET['course']) ? $_GET['course'] : '';

        if ((int) $id) {
            $this->data['versions'] =   $this->lesson_plan_version_m->get_order_by_lesson_plan_version(["lesson_plan_id" => $id, "finalized_id" => 1]);
            foreach ($this->data['versions'] as $index1 => $v2) {
                $this->data['versions'][$index1]->media = $this->lesson_plan_media_m->get_order_by_lessonmedia(["lesson_plan_version_id" => $v2->id,]);
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
                    $this->form_validation->set_message("fileupload", $this->upload->display_errors());
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

            if (customCompute($this->data['versions'][$index1]->media)) {

                $array1 = array(
                    "title" => $this->input->post("title"),
                    "unit_id" => $this->input->post("unitId"),
                    "chapter_id" => $this->input->post("chapterId"),
                );

                $this->lesson_plan_m->update($array1, $id);

                $this->lesson_plan_media_m->update_batchlessonmedia($this->data['versions'][$index1]->media, $this->data['versions']->id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("courses/lesson/" . $course));
                return true;
            }
        }
    }


    public function ajaxChangeFileStatus($id)
    {
        $lesson = $this->lesson_plan_m->get_lesson_plan($id);
        $array = [
            'published' => $lesson->published == 2 ? 1 : 2,
        ];
        if ($this->lesson_plan_m->update($array, $id) && $array['published'] == 1) {
            $record = $this->courses_m->get_join_courses_based_on_course_id($lesson->course_id);
            $title = 'Lesson Plan Published';
            $notice = "Lesson Plan ".$lesson->title." for class " . $record->classes . " of " . $record->subject . " has been published";
            $this->notification($title, $notice,$record->classesID);
        }
    }

    public function ajaxChangeVersionStatus($id)
    {
        $version = $this->lesson_plan_version_m->get_lesson_plan_version($id);
        $array[] = array(   
            "finalized_id" => 0,
            "lesson_plan_id" => $version->lesson_plan_id
        );
        $res = $this->lesson_plan_version_m->update_batchlesson_plan_version($array, "lesson_plan_id");

        $array1 = array(
            "finalized_id" =>  1,
        );

        $this->lesson_plan_version_m->update_lesson_plan_version($array1, $id);
        // $title = 'Lesson Plan Version Published';
        // $notice = "Lesson Plan Version has been published";
        // $this->notification($title, $notice, '');

    }

    public function pushNotification($title, $class = null)
    {
        $this->job_m->insert_job([
            'name' => 'sendLessonPlanNotification',
            'payload' => json_encode([
                'class' => $class,
                'title' => $title, // title is compulsary
            ]),
        ]);
    }

    public function mobPushNotification($array)
    {
        $this->mobile_job_m->insert_job([
            'name' => 'sendLessonPlanNotification',
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
}