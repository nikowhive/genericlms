<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;

class Courses extends Admin_Controller
{
    /*
| -----------------------------------------------------
| PRODUCT NAME:     INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:            INILABS TEAM
| -----------------------------------------------------
| EMAIL:            info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:        RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:            http://inilabs.net
| -----------------------------------------------------
 */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("job_m");
        $this->load->model("feed_m");
        $this->load->model("mobile_job_m");
        $this->load->model("classes_m");
        $this->load->model("subject_m");
        $this->load->model("section_m");
        $this->load->model("courses_m");
        $this->load->model("chapter_m");
        $this->load->model("question_bank_m");
        $this->load->model("question_group_m");
        $this->load->model("question_level_m");
        $this->load->model("question_type_m");
        $this->load->model("student_m");
        $this->load->model("coursequiz_m");
        $this->load->model("coursefiles_m");
        $this->load->model("courselink_m");
        $this->load->model("notice_m");
        $this->load->model("question_option_m");
        $this->load->model("question_answer_m");
        $this->load->model("online_exam_user_answer_option_m");
        $this->load->model("homework_media_m");
        $this->load->model("classwork_media_m");

        $this->load->model("coursecontent_m");
        $this->load->model("coursesstudent_progress_m");
        $this->load->model("unit_m");
        $this->load->model("assignment_m");
        $this->load->model("homework_m");
        $this->load->model("classwork_m");
        $this->load->model("pushsubscription_m");
        $this->load->model("subjectattendance_m");
        $this->load->model("studentrelation_m");
        $this->load->model("annual_plan_m");
        $this->load->model("annual_plan_media_m");
        $this->load->model("lesson_plan_m");
        $this->load->model("lesson_plan_version_m");
        $this->load->model("lesson_plan_media_m");
        $this->load->model("assignment_media_m");
        $this->load->model("assignmentanswer_m");
        $this->load->model("homeworkanswer_m");
        $this->load->model("classworkanswer_m");
        $this->load->model("log_m");
        $this->load->model("daily_plan_m");
        $this->load->model("daily_plan_media_m");
        $this->load->library('session');
        $language = $this->session->userdata('lang');
        $this->lang->load('question_bank', $language);
        $this->lang->load('assignment', $language);
        $this->lang->load('homework', $language);
        $this->lang->load('classwork', $language);
        $this->lang->load('courses', $language);
    }

    protected function rules()
    {
        $rules = array(

            array(
                'field' => 'class_id',
                'label' => $this->lang->line("classes_name"),
                'rules' => 'trim|required|numeric|max_length[11]|xss_clean',
            ),
            array(
                'field' => 'subject_id',
                'label' => $this->lang->line("subject_name"),
                'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_alreadyexists',
            ),
        );
        return $rules;
    }

    protected function rulesforunit()
    {
        $rules = array(
            array(
                'field' => 'unit_id',
                'label' => "Unit",
                'rules' => 'trim|required|xss_clean|max_length[11]',
            ),
            array(
                'field' => 'chapter_id',
                'label' => "Chapter",
                'rules' => 'trim|required|xss_clean|max_length[11]',
            ),
        );
        return $rules;
    }

    protected function rulesforquiz()
    {
        $rules = array(
            array(
                'field' => 'percentage_coverage',
                'label' => "Percentage Coverage",
                'rules' => 'trim|required|xss_clean|max_length[3]|numeric|greater_than[-1]',
            ),
            array(
                'field' => 'quiz_name',
                'label' => "Quiz Name",
                'rules' => 'trim|required|xss_clean|callback_quiz_name',
            ),
        );
        return $rules;
    }

    protected function rulesforresource()
    {
        $rules = array(
            array(
                'field' => 'content_title',
                'label' => 'Title',
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'chapter_content',
                'label' => 'Content',
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'percentage_coverage',
                'label' => 'Percentage Coverage',
                'rules' => 'trim|required|xss_clean|numeric|max_length[3]|greater_than[-1]',
            ),
        );
        return $rules;
    }

    public function quiz_name()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$id) {
            $quiz = $this->coursequiz_m->general_get_order_by_quiz(array(
                "id !=" => $id,
                "quiz_name" => $this->input->post("quiz_name")
                // "coursechapter_id" => $this->input->post("chapter_id")
            ));
            if (customCompute($quiz)) {
                $this->form_validation->set_message("quiz_name", "%s already exists");
                return FALSE;
            }
            return TRUE;
        } else {
            $quiz = $this->coursequiz_m->general_get_order_by_quiz(array(
                // "coursechapter_id" => $this->input->post("chapter_id"),
                "quiz_name" => $this->input->post("quiz_name")
            ));

            if (customCompute($quiz)) {
                $this->form_validation->set_message("quiz_name", "%s already exists");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function quiz_title()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$id) {
            $quiz = $this->coursequiz_m->general_get_order_by_quiz(
                array(
                    "id !=" => $id,
                    "quiz_name" => $this->input->post("quiz_title")
                )
            );
            if (customCompute($quiz)) {
                $this->form_validation->set_message("quiz_title", "%s already exists");
                return FALSE;
            }
            return TRUE;
        } else {
            $quiz = $this->coursequiz_m->general_get_order_by_quiz(array(
                // "coursechapter_id" => $this->input->post("chapter_id"),
                "quiz_name" => $this->input->post("quiz_title")
            ));

            if (customCompute($quiz)) {
                $this->form_validation->set_message("quiz_title", "%s already exists");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function index()
    {
        $usertypeID = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');
        $this->data['classes'] = $this->classes_m->get_classes();
        $this->data['subjects'] = [];

        $this->data['usertypeID'] = $usertypeID;
        $this->data['loginuserID'] = $loginuserID;

        if (permissionChecker('courses_view')) {
            if ($usertypeID == '2') {
                foreach ($this->data['classes'] as $index => $class) {
                    $this->data['classes'][$index]->sections = $this->section_m->general_get_order_by_section([
                        'classesID' => $class->classesID,
                    ]);
                    $this->data['classes'][$index]->courses = $this->courses_m->get_join_courses_based_on_teacher_id($class->classesID, $loginuserID);
                    foreach ($this->data['classes'][$index]->courses as $courseIndex => $course) {
                        $this->data['classes'][$index]->courses[$courseIndex]->units = $this->unit_m->get_units_count_by_subject_id($course->subject_id)->count;
                        $this->data['classes'][$index]->courses[$courseIndex]->chapters = $this->chapter_m->get_chapters_count_by_subject_id($course->subject_id)->count;
                    }
                }
            } elseif ($usertypeID == '3') {

                // log start
                $event = 'courses list';
                $remarks = 'visited course list by '.$this->session->userdata("name");
                createCourseLog($event,$remarks);
                // log end

                $this->data['student'] = $this->student_m->get_single_student([]);
                $this->data['classes'] = $this->classes_m->general_get_order_by_classes(['classesID' => $this->data['student']->classesID]);
                if (customCompute($this->data['student'])) {
                    foreach ($this->data['classes'] as $index => $class) {
                        $this->data['classes'][$index]->courses = $this->courses_m->get_join_courses_based_on_class_id($class->classesID);
                        foreach ($this->data['classes'][$index]->courses as $courseIndex => $course) {
                            $this->data['classes'][$index]->courses[$courseIndex]->units = $this->unit_m->get_units_count_by_subject_id($course->subject_id)->count;
                            $this->data['classes'][$index]->courses[$courseIndex]->chapters = $this->chapter_m->get_chapters_count_by_subject_id($course->subject_id)->count;
                        }
                    }
                }
            } elseif ($usertypeID == '4') {
                $this->data['students'] = $this->student_m->general_get_order_by_student(array('parentID' => $loginuserID));
                if (customCompute($this->data['students'])) {
                    foreach ($this->data['students'] as $i => $child) {
                        $this->data['students'][$i]->courses = $this->courses_m->get_join_courses_based_on_class_id($child->classesID);
                        foreach ($this->data['students'][$i]->courses as $index => $course) {
                            $this->data['students'][$i]->courses[$index]->units = $this->unit_m->get_units_count_by_subject_id($course->subject_id)->count;
                            $this->data['students'][$i]->courses[$index]->chapters = $this->chapter_m->get_chapters_count_by_subject_id($course->subject_id)->count;
                        }
                    }
                }
            } else {

                foreach ($this->data['classes'] as $index => $class) {
                    $this->data['classes'][$index]->sections = $this->section_m->general_get_order_by_section([
                        'classesID' => $class->classesID,
                    ]);
                    $this->data['classes'][$index]->courses = $this->courses_m->get_join_courses($class->classesID);
                    foreach ($this->data['classes'][$index]->courses as $courseIndex => $course) {
                        $this->data['classes'][$index]->courses[$courseIndex]->units = $this->unit_m->get_units_count_by_subject_id($course->subject_id)->count;
                        $this->data['classes'][$index]->courses[$courseIndex]->chapters = $this->chapter_m->get_chapters_count_by_subject_id($course->subject_id)->count;
                    }
                }
            }
            $this->data["subview"] = "courses/index";
            $this->load->view('_layout_main', $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function show()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($id);
        $this->data['units'] = $this->courses_m->get_course_unit_by_course($id);
        $this->data['annual_id'] = $this->annual_plan_m->get_single_annual_plan(["course_id" => $id]);
        $this->data['unit_count'] = count($this->data['units']);
        $this->data['chapters'] = [];
        $this->data['chapter_ids'] = [];
        $classesID = $this->data['course']->class_id;
        $this->data['set'] = $classesID;
        $this->data['classes'] = $this->classes_m->general_get_single_classes(['classesID' => $this->data['course']->class_id]);
        $this->data['subjects'] = $this->subject_m->general_get_single_subject(['subjectID' => $this->data['course']->subject_id]);

        foreach ($this->data['units'] as $index => $unit) {
            $this->data['units'][$index]->chapters = $this->chapter_m->get_chapter_from_unit_id($unit->id);
            $this->data['units'][$index]->chapter_count = count($this->data['units'][$index]->chapters);
            foreach ($this->data['units'][$index]->chapters as $x => $chapter) {

                $this->data['units'][$index]->chapters[$x]->contents = $this->courses_m->get_content($chapter->id);
                $this->data['units'][$index]->chapters[$x]->attachments = $this->courses_m->get_attachment($chapter->id);
                $this->data['units'][$index]->chapters[$x]->links = $this->courses_m->get_link($chapter->id);
                $this->data['units'][$index]->chapters[$x]->quizzes = $this->courses_m->get_quizzes($chapter->id);
                $this->data['units'][$index]->chapters[$x]->assignments =
                    $this->assignment_m->get_order_by_assignment([
                        'unit_id' => $unit->id,
                        'chapter_id' => $chapter->id,
                        'schoolyearID' => $schoolyearID,
                    ]);
                $this->data['units'][$index]->chapters[$x]->homeworks =
                    $this->homework_m->get_order_by_homework([
                        'unit_id' => $unit->id,
                        'chapter_id' => $chapter->id,
                        'schoolyearID' => $schoolyearID,
                    ]);
                $this->data['units'][$index]->chapters[$x]->classworks =
                    $this->classwork_m->get_order_by_classwork([
                        'unit_id' => $unit->id,
                        'chapter_id' => $chapter->id,
                        'schoolyearID' => $schoolyearID,
                    ]);

                array_push($this->data['chapter_ids'], $chapter->id);
            }
        }

        foreach ($this->data['units'] as $index => $unit) {
            if ($unit->chapters) {
                foreach ($unit->chapters as $x => $chapter) {
                    $lists = [];

                    if ($chapter->classworks) {
                        if (is_array($chapter->classworks)) {
                            foreach ($chapter->classworks as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->classworks);
                        }
                    }
                    if ($chapter->homeworks) {
                        if (is_array($chapter->homeworks)) {
                            foreach ($chapter->homeworks as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->homeworks);
                        }
                    }
                    if ($chapter->assignments) {
                        if (is_array($chapter->assignments)) {
                            foreach ($chapter->assignments as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->assignments);
                        }
                    }
                    if ($chapter->contents) {
                        if (is_array($chapter->contents)) {
                            foreach ($chapter->contents as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->contents);
                        }
                    }
                    if ($chapter->attachments) {
                        if (is_array($chapter->attachments)) {
                            foreach ($chapter->attachments as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->attachments);
                        }
                    }
                    if ($chapter->quizzes) {
                        if (is_array($chapter->quizzes)) {
                            foreach ($chapter->quizzes as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->quizzes);
                        }
                    }
                    if ($chapter->links) {
                        if (is_array($chapter->links)) {
                            foreach ($chapter->links as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->links);
                        }
                    }
                    usort($lists, function ($a, $b) {
                        if (isset($a->order) && isset($b->order)) {
                            return $a->order > $b->order;
                        }
                    });
                    $this->data['units'][$index]->chapters[$x]->lists = $lists;
                }
            }
        }

        $this->data["subview"] = "courses/show";
        $this->load->view('_layout_course', $this->data);
    }

    public function edit_attachment($value = '')
    {
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $GLOBALS['msg'] = '';
        $GLOBALS['msglink'] = '';
        $link = isset($_GET['link']) ? $_GET['link'] : '';

        if ((int) $id) {
            $this->data['attachments'] = $this->coursefiles_m->get_attachment_by_id($id);
            $content = $this->coursecontent_m->get_content($id);

            if ($_POST) {
                $this->form_validation->set_rules("file_name", 'File Name', 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {

                    $this->data['form_validation'] = validation_errors();
                    $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
                    $this->data['usertypeID'] = $this->session->userdata('usertypeID');
                    $this->data["subview"] = "courses/editattachment";

                    $this->load->view('_layout_main', $this->data);
                } else {
                    $array = array(
                        "coursechapter_id" => $this->data['attachments']->coursechapter_id,
                        "file_name" => $_POST["file_name"],
                    );


                    if ($_FILES) {
                        $filen = $_FILES["item_file"]['name']; //file name
                        $_FILES['attach']['tmp_name'] = $_FILES['item_file']['tmp_name'];
						$image_info = getimagesize($_FILES['item_file']['tmp_name']);
                        $random = rand(1, 10000000000000000);
                        $makeRandom = hash('sha512', $random . $this->input->post('title') . config_item("encryption_key"));
                        $file_name_rename = $makeRandom;
                        $explode = explode('.', $filen);
                        $target_dir = "./uploads/images/";
                        $path = './uploads/images/' . $file_name_rename . '.' . end($explode); //generate the destination path
                        if (move_uploaded_file($_FILES["item_file"]['tmp_name'], $path)) {
                            $image_width = $image_info[0];
							$image_height = $image_info[1];

                            resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height);

                            //upload the file
                            //Success message
                            $array['attachment'] = $file_name_rename . '.' . end($explode);
                        }
                    }

                    $res = $this->courses_m->update_attachment($array, $id);

                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    if ($link == 'attachments') {
                        redirect(base_url("courses/attachments/" . $course));
                    } else {
                        redirect(base_url("courses/show/" . $course));
                    }
                }
            } else {
                $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
                $this->data['usertypeID'] = $this->session->userdata('usertypeID');
                $this->data["subview"] = "courses/editattachment";

                $this->load->view('_layout_course', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function edit_link($value = '')
    {
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $link = isset($_GET['link']) ? $_GET['link'] : '';
        $id = htmlentities(escapeString($this->uri->segment(3)));

        if ((int) $id) {
            $this->data['link'] = $this->courselink_m->get($id);
            $content = $this->coursecontent_m->get_content($id);
            if ($_POST) {
                $array = array(
                    "id" => $id,
                    "coursechapter_id" => $this->data['link']->coursechapter_id,
                    "courselink" => $this->input->post('item_link'),
                    "type" => $this->input->post('type'),
                    "published" => $this->data['link']->published,
                    "order" => $this->data['link']->order,
                );
                $res = $this->courselink_m->update($array, $id);

                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                if ($link == 'links') {
                    redirect(base_url("courses/links/" . $course));
                } else {
                    redirect(base_url("courses/show/" . $course));
                }
            } else {
                $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
                $this->data['usertypeID'] = $this->session->userdata('usertypeID');
                $this->data["subview"] = "courses/editlink";

                $this->load->view('_layout_course', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function annual()
    {
        $course_id  = isset($_GET['course']) ? $_GET['course'] :  htmlentities(escapeString($this->uri->segment(3)));

        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course_id);
        $this->data['annuals'] = $this->annual_plan_m->get_annual_by_course($course_id, $this->data['usertypeID']);

        if ($this->data['annuals']) {
            $this->data['annuals']->medias = $this->annual_plan_media_m->get_order_by_annual_plan_media(["annual_plan_id" => $this->data['annuals']->id]);
        }
        if ($course_id) {
            $this->data["subview"] = "courses/annual/index";
            $this->load->view('_layout_course', $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function lesson()
    {
        $course_id  = isset($_GET['course']) ? $_GET['course'] :  htmlentities(escapeString($this->uri->segment(3)));

        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course_id);
        $this->data['units'] = $this->courses_m->get_course_unit_by_course($course_id);
        $this->data['chapters'] = [];

        $this->data['lessons'] = $this->lesson_plan_m->getLessonWithUnitAndChapter($course_id, $this->data['usertypeID']);

        foreach ($this->data['lessons'] as $index => $v) {
            $this->data['lessons'][$index]->version = $this->lesson_plan_version_m->get_order_by_lesson_plan_version(["lesson_plan_id" => $v->id, "finalized_id" => 1]);
            foreach ($this->data['lessons'][$index]->version as $index1 => $v2) {

                $this->data['lessons'][$index]->version[$index1]->medias = $this->lesson_plan_media_m->get_order_by_lessonmedia(["lesson_plan_version_id" => $v2->id,]);
            }
        }

        if ($course_id) {
            $this->data["subview"] = "courses/lesson/index";
            $this->load->view('_layout_course', $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function daily()
    {
        $course_id  = isset($_GET['course']) ? $_GET['course'] :  htmlentities(escapeString($this->uri->segment(3)));

        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course_id);
        $classesID = $this->data['course']->class_id;
        $this->data['units'] = $this->courses_m->get_course_unit_by_course($course_id);
        $this->data['sections'] = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
        $this->data['chapters'] = [];
        $this->data['dailys'] = $this->daily_plan_m->get_daily_plan_by_create_date($course_id, $this->data['usertypeID']);

        foreach ($this->data['dailys'] as $index => $v) {
            $this->data['dailys'][$index]->medias = $this->daily_plan_media_m->get_order_by_daily_plan_media(["daily_plan_id" => $v->id]);
        }

        if ($this->data['usertypeID'] == 1 || $this->data['usertypeID'] == 2) {
            if ($course_id) {
                $this->data["subview"] = "courses/daily/index";
                $this->load->view('_layout_course', $this->data);
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_course', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }



    public function contents()
    {
        $course_id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course_id);
        $this->data['units'] = $this->courses_m->get_course_unit_by_course($course_id);
        // $this->data['unit_count'] = count($this->data['units']);
        $this->data['chapters'] = [];
        $this->data['contents'] = $this->coursecontent_m->getContentWithUnitAndChapter($course_id, $this->data['usertypeID']);


        // log start
        $event = 'course content page';
        $remarks = 'visited course content of '.$this->data['course']->classes . ' - ' . $this->data['course']->subject. ' by '.$this->session->userdata("name");
        createCourseLog($event,$remarks);
        // log end


        $this->data["subview"] = "courses/contents";
        $this->load->view('_layout_course', $this->data);
    }

    public function attachments()
    {
        $course_id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course_id);
        $this->data['units'] = $this->courses_m->get_course_unit_by_course($course_id);
        $this->data['chapters'] = [];
        $this->data['attachments'] = $this->coursefiles_m->get_attachments_by_course($course_id, $this->data['usertypeID']);


        // log start
        $event = 'courses attachment';
        $remarks = 'visited '.$this->data['course']->classes . ' - ' . $this->data['course']->subject. ' attachment list by '.$this->session->userdata("name");
        createCourseLog($event,$remarks);
        // log end

        $this->data["subview"] = "courses/attachments";
        $this->load->view('_layout_course', $this->data);
    }

    public function links()
    {

        $course_id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course_id);
        $this->data['units'] = $this->courses_m->get_course_unit_by_course($course_id);
        $this->data['chapters'] = [];
        $this->data['unit_count'] = count($this->data['units']);

        $this->data['links'] = $this->courselink_m->get_links_by_course($course_id, $this->data['usertypeID']);

        if(is_numeric($course_id)){
            // log start
            $event = 'courses links';
            $remarks = 'visited '.$this->data['course']->classes . ' - ' . $this->data['course']->subject. ' link list by '.$this->session->userdata("name");
            createCourseLog($event,$remarks);
            // log end
        }

        $this->data["subview"] = "courses/links";
        $this->load->view('_layout_course', $this->data);
    }

    public function quizzes()
    {
        $course_id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course_id);
        $this->data['units'] = $this->courses_m->get_course_unit_by_course($course_id);
        $this->data['unit_count'] = count($this->data['units']);
        $this->data['chapters'] = [];
        $this->data['quizzes_id'] = [];

        $this->data['quizzes'] = $this->courses_m->get_quizzes_by_course($course_id, $this->data['usertypeID']);


        foreach ($this->data['quizzes'] as $y => $quiz) {
            $this->data['quizzes'][$y]->questions = $this->courses_m->get_order_by_quiz_question($quiz->id);
            array_push($this->data['quizzes_id'],  $quiz->id);
        }  

        // foreach ($this->data['units'] as $index => $unit) {
        //     $this->data['units'][$index]->chapters = $this->chapter_m->get_chapter_from_unit_id($unit->id);
        //     $this->data['units'][$index]->chapter_count = count($this->data['units'][$index]->chapters);
        //     foreach ($this->data['units'][$index]->chapters as $x => $chapter) {
        //         if ($this->data['usertypeID'] == 1 || $this->data['usertypeID'] == 2) {
        //             $this->data['units'][$index]->chapters[$x]->quizzes = $this->courses_m->get_quizzes($chapter->id);
        //         } else {
        //             $this->data['units'][$index]->chapters[$x]->quizzes = $this->courses_m->get_published_quizzes($chapter->id);
        //         }
        //         foreach ($this->data['units'][$index]->chapters[$x]->quizzes as $y => $quiz) {
        //             $this->data['units'][$index]->chapters[$x]->quizzes[$y]->questions = $this->courses_m->get_order_by_quiz_question($quiz->id);
        //             array_push($this->data['quizzes_id'],  $quiz->id);
        //         }
        //     }
        // }

        // log start
        $event = 'courses quizzes';
        $remarks = 'visited '.$this->data['course']->classes . ' - ' . $this->data['course']->subject. ' quizzes list by '.$this->session->userdata("name");
        createCourseLog($event,$remarks);
        // log end


        $this->data["subview"] = "courses/quizzes";
        $this->load->view('_layout_course', $this->data);
    }

    public function student_view()
    {
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['loginuserID'] = $this->session->userdata('loginuserID');
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['course'] = $course = $this->courses_m->get_all_join_courses_based_on_course_id($id);
        $this->data['units'] = $this->courses_m->get_published_course_unit_by_course($id);
        $this->data['unit_count'] = count($this->data['units']);
        $this->data['chapters'] = [];
        $this->data['chapter_ids'] = [];
        $classesID = $this->data['course']->class_id;
        $this->data['set'] = $classesID;

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        // log start
        $event = 'courses list';
        $remarks = 'visited '.$course->classes . ' - ' . $course->subject. ' page by '.$this->session->userdata("name");
        createCourseLog($event,$remarks);
        // log end

        foreach ($this->data['units'] as $index => $unit) {
            $this->data['units'][$index]->chapters = $this->courses_m->get_published_course_unit_chapter($unit->id);

            if ($this->data['usertypeID'] == 4) {
                $student_id = htmlentities(escapeString($this->uri->segment(4)));
                if (!$student_id) {
                    $student_id = $this->student_m->general_get_single_student(['parentID' => $this->data['loginuserID']])->studentID;
                }
            } else {
                $student_id = $this->data['loginuserID'];
            }

            $this->data['units'][$index]->chapter_count = count($this->data['units'][$index]->chapters);
            foreach ($this->data['units'][$index]->chapters as $x => $chapter) {
                if ($this->courses_m->get_coursecontent($chapter->id)) {
                    $coverage = 0;
                    $covered = 0;
                    $contents = $this->courses_m->get_contents($chapter->id);
                    foreach ($contents as $key => $content) {
                        $array = [
                            'student_id' => $student_id,
                            'content_id' => (int) $content->id,
                            'chapter_id' => $chapter->id,
                        ];
                        $exists = $this->coursesstudent_progress_m->get_order_by_courses_student_progress($array);
                        $contents[$key]->exists = $exists ? true : false;
                        $covered += $exists ? $content->percentage_coverage : 0;
                        $coverage += $content->percentage_coverage;
                    }

                    $this->data['units'][$index]->chapters[$x]->content_exists = true;
                    if ($this->data['usertypeID'] == 3 || $this->data['usertypeID'] == 4) {
                        $this->data['units'][$index]->chapters[$x]->total_coverage = $coverage;
                        $this->data['units'][$index]->chapters[$x]->covered = $covered;
                    } else {
                        $this->data['units'][$index]->chapters[$x]->total_coverage = 3;
                        $this->data['units'][$index]->chapters[$x]->covered = rand(0, 3);
                    }
                } else {
                    $this->data['units'][$index]->chapters[$x]->content_exists = false;
                }
                $this->data['units'][$index]->chapters[$x]->contents = $this->courses_m->get_contents($chapter->id);
                $this->data['units'][$index]->chapters[$x]->attachments = $this->courses_m->get_published_attachment($chapter->id);
                $this->data['units'][$index]->chapters[$x]->links = $this->courses_m->get_published_link($chapter->id);
                $this->data['units'][$index]->chapters[$x]->quizzes = $this->courses_m->get_published_quizzes($chapter->id);
                $this->data['units'][$index]->chapters[$x]->assignments =
                    $this->assignment_m->get_order_by_published_assignment([
                        'unit_id' => $unit->id,
                        'chapter_id' => $chapter->id,
                        'schoolyearID' => $schoolyearID,
                    ]);
                $this->data['units'][$index]->chapters[$x]->homeworks =
                    $this->homework_m->get_order_by_published_homework([
                        'unit_id' => $unit->id,
                        'chapter_id' => $chapter->id,
                        'schoolyearID' => $schoolyearID,
                    ]);
                $this->data['units'][$index]->chapters[$x]->classworks =
                    $this->classwork_m->get_order_by_published_classwork([
                        'unit_id' => $unit->id,
                        'chapter_id' => $chapter->id,
                        'schoolyearID' => $schoolyearID,
                    ]);
                array_push($this->data['chapter_ids'], $chapter->id);
            }
        }
        foreach ($this->data['units'] as $index => $unit) {
            if ($unit->chapters) {
                foreach ($unit->chapters as $x => $chapter) {
                    $lists = [];

                    if ($chapter->classworks) {
                        if (is_array($chapter->classworks)) {
                            foreach ($chapter->classworks as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->classworks);
                        }
                    }
                    if ($chapter->homeworks) {
                        if (is_array($chapter->homeworks)) {
                            foreach ($chapter->homeworks as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->homeworks);
                        }
                    }
                    if ($chapter->assignments) {
                        if (is_array($chapter->assignments)) {
                            foreach ($chapter->assignments as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->assignments);
                        }
                    }
                    if ($chapter->contents) {
                        if (is_array($chapter->contents)) {
                            foreach ($chapter->contents as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->contents);
                        }
                    }
                    if ($chapter->attachments) {
                        if (is_array($chapter->attachments)) {
                            foreach ($chapter->attachments as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->attachments);
                        }
                    }
                    if ($chapter->quizzes) {
                        if (is_array($chapter->quizzes)) {
                            foreach ($chapter->quizzes as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->quizzes);
                        }
                    }
                    if ($chapter->links) {
                        if (is_array($chapter->links)) {
                            foreach ($chapter->links as $data) {
                                array_push($lists, $data);
                            }
                        } else {
                            array_push($lists, $chapter->links);
                        }
                    }
                    usort($lists, function ($a, $b) {
                        if (isset($a->order) && isset($b->order)) {
                            return $a->order > $b->order;
                        }
                    });
                    $this->data['units'][$index]->chapters[$x]->lists = $lists;
                }
            }
        }
        $this->data["subview"] = "courses/student_view";
        $this->load->view('_layout_course', $this->data);
    }

    public function assignment()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
            ),
        );

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $usertypeID   = $this->session->userdata('usertypeID');

        $course_id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course_id);
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');

        $subjectID = $this->data['course']->subject_id;
        $classesID = $this->data['course']->class_id;
        $this->data['set'] = $classesID;
        $this->data['sections'] = pluck($this->section_m->general_get_order_by_section(array('classesID' => $classesID)), 'section', 'sectionID');

        $this->data['student'] = [];
        $this->data['opsubjects'] = [];

        $loginuserID = $this->session->userdata('loginuserID');
        $this->data['opsubjects'] = pluck($this->subject_m->get_order_by_subject(['classesID' => $classesID, 'type' => 0]), 'subjectID', 'subjectID');
        $this->data['student'] = $this->studentrelation_m->get_single_studentrelation(['srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID]);
        $this->data['assignments'] = $this->assignment_m->getAssignmentWithUnitAndChapter($course_id, $schoolyearID, $this->data['usertypeID']);

        if ($usertypeID == 3 || $usertypeID == 4) {
            if (customCompute($this->data['assignments'])) {
                foreach ($this->data['assignments'] as $index => $k) {
                    $answers = $this->assignmentanswer_m->get_single_assignmentanswer(["assignmentID" => $k->assignmentID]);
                    // $this->data['assignments'][$index]->status =  isset($answers->status) ? $answers->status : '';

                    $assign_ans_status = $answers ? $answers->status : '';
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
                    $this->data['assignments'][$index]->status_label = $assign_status;
                    $this->data['assignments'][$index]->status_title = $assign_status_title;
                }
            }
        }


        // log start
        $event = 'courses assignments';
        $remarks = 'visited '.$this->data['course']->classes . ' - ' . $this->data['course']->subject. ' assignment list by '.$this->session->userdata("name");
        createCourseLog($event,$remarks);
        // log end

        $this->data["subview"]           = "courses/assignment";
        $this->load->view('_layout_course', $this->data);
    }

    public function homework()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
            ),
        );

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $usertypeID   = $this->session->userdata('usertypeID');

        $id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($id);
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');

        $subjectID = $this->data['course']->subject_id;
        $classesID = $this->data['course']->class_id;

        $this->data['student'] = [];
        $this->data['opsubjects'] = [];

        $loginuserID = $this->session->userdata('loginuserID');
        $this->data['opsubjects'] = pluck($this->subject_m->get_order_by_subject(['classesID' => $classesID, 'type' => 0]), 'subjectID', 'subjectID');
        $this->data['student'] = $this->studentrelation_m->get_single_studentrelation(['srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID]);

        $this->data['set'] = $classesID;
        $this->data['sections'] = pluck($this->section_m->general_get_order_by_section(array('classesID' => $classesID)), 'section', 'sectionID');

        $this->data['homeworks'] = $this->homework_m->getHomeworkWithUnitAndChapter($id, $schoolyearID, $this->data['usertypeID']);

        if ($usertypeID == 3 || $usertypeID = 4) {
            if (customCompute($this->data['homeworks'])) {
                foreach ($this->data['homeworks'] as $index => $k) {
                    $answers = $this->homeworkanswer_m->get_single_homeworkanswer(["homeworkID" => $k->homeworkID]);
                    // $this->data['homeworks'][$index]->status =  isset($answers->status) ? $answers->status : '';

                    $homework_ans_status = $answers ? $answers->status : '';
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
                    $this->data['homeworks'][$index]->status_label = $homework_status;
                    $this->data['homeworks'][$index]->status_title = $homework_status_title;
                }
            }
        }


        // log start
        $event = 'courses homeworks';
        $remarks = 'visited '.$this->data['course']->classes . ' - ' . $this->data['course']->subject. ' homework list by '.$this->session->userdata("name");
        createCourseLog($event,$remarks);
        // log end


        $this->data["subview"]           = "courses/homework";
        $this->load->view('_layout_course', $this->data);
    }

    public function classwork()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
            ),
        );

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $usertypeID   = $this->session->userdata('usertypeID');

        $id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($id);
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');

        $subjectID = $this->data['course']->subject_id;
        $classesID = $this->data['course']->class_id;

        $this->data['student'] = [];
        $this->data['opsubjects'] = [];

        $loginuserID = $this->session->userdata('loginuserID');
        $this->data['opsubjects'] = pluck($this->subject_m->get_order_by_subject(['classesID' => $classesID, 'type' => 0]), 'subjectID', 'subjectID');
        $this->data['student'] = $this->studentrelation_m->get_single_studentrelation(['srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID]);

        $this->data['set'] = $classesID;
        $this->data['sections'] = pluck($this->section_m->general_get_order_by_section(array('classesID' => $classesID)), 'section', 'sectionID');

        $this->data['classworks'] = $this->classwork_m->getClassworkWithUnitAndChapter($id, $schoolyearID, $this->data['usertypeID']);
        if ($usertypeID == 3 || $usertypeID = 4) {
            if (customCompute($this->data['classworks'])) {
                foreach ($this->data['classworks'] as $index => $k) {
                    $answers = $this->classworkanswer_m->get_single_classworkanswer(["classworkID" => $k->classworkID]);

                    $classwork_ans_status = $answers ? $answers->status : '';
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
                    $this->data['classworks'][$index]->status_label = $classwork_status;
                    $this->data['classworks'][$index]->status_title = $classwork_status_title;
                }
            }
        }


        // log start
        $event = 'courses classworks';
        $remarks = 'visited '.$this->data['course']->classes . ' - ' . $this->data['course']->subject. ' classwork list by '.$this->session->userdata("name");
        createCourseLog($event,$remarks);
        // log end


        $this->data["subview"] = "courses/classwork";
        $this->load->view('_layout_course', $this->data);
    }

    public function attachmentupload()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));

        $content = array();
        if ((int) $id) {
            $content = $this->courses_m->get_content($id);
        }

        $new_file = "content.png";
        if ($_FILES["attachment"]['name'] != "") {

            $file_name = $_FILES["attachment"]['name'];
            $random = rand(1, 10000000000000000);
            $makeRandom = hash('sha512', $random . $this->input->post('title') . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if (customCompute($explode) >= 2) {
                $new_file = $file_name_rename . '.' . end($explode);
                $_FILES['attach']['tmp_name'] = $_FILES['photo']['tmp_name'];
                $image_info = getimagesize($_FILES['photo']['tmp_name']);
                $image_width = $image_info[0];
				$image_height = $image_info[1];
                $config['upload_path'] = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png";
                $config['file_name'] = $new_file;
                // $config['max_size'] = '5120';
                // $config['max_width'] = '3000';
                // $config['max_height'] = '3000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("attachment")) {

                    $this->form_validation->set_message("attachmentupload", $this->upload->display_errors());
                    return false;
                } else {
                    $fileData = $this->upload->data();
                    
                    resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height);

                    $this->upload_data['file'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("attachmentupload", "Invalid file");
                return false;
            }
        } else {
            if (customCompute($content)) {
                $this->upload_data['file'] = array('file_name' => $content->attachment);
                return true;
            } else {
                $this->upload_data['file'] = array('file_name' => $new_file);
                return true;
            }
        }
    }

    public function add()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
            ),
        );

        $this->data['classes'] = $this->classes_m->get_order_by_numeric_classes();
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "courses/add";
                $this->load->view('_layout_course', $this->data);
            } else {
                $array = array(
                    "class_id" => $this->input->post("class_id"),
                    "subject_id" => $this->input->post("subject_id"),
                );

                $insert_id = $this->courses_m->insert_courses($array);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("courses/unitlist/" . $insert_id));
            }
        } else {
            $this->data["subview"] = "courses/add";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function ajaxAdd()
    {
        if ($_POST) {

            $condition = array(
                "class_id" => $this->input->post("class_id"),
                "subject_id" => $this->input->post("subject_id"),
            );
            
            $array = array(
                "class_id" => $this->input->post("class_id"),
                "subject_id" => $this->input->post("subject_id"),
                "description" => $this->input->post("description"),
                "duration" => $this->input->post("duration"),
                "hour_per_week" => $this->input->post("hour_per_week")
            );
            $exists = $this->courses_m->get_single_courses($condition);
            if (!$exists) {
                $this->courses_m->insert_courses($array);
                echo true;
            } else {
                echo false;
            }
        }
    }

    public function addlink()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
            ),
        );

        $id = htmlentities(escapeString($this->uri->segment(3)));

        if ((int) $id) {

            $this->data['courses'] = $this->courses_m->get_courses($id);
            if ($this->data['courses']) {
                if ($_POST) {

                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);

                    $array = array(
                        "bbb_url" => $this->input->post("bbb_url"),
                    );

                    $this->courses_m->update_courses($array, $id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));

                    redirect(base_url("courses/index"));
                } else {
                    $this->data["subview"] = "courses/link_url";
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



    public function unitlist()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['loginuserID'] = $this->session->userdata('loginuserID');
        if ($this->data['usertypeID'] == '1' || $this->data['usertypeID'] == '2') {
            $this->data['course_units'] = $this->courses_m->get_course_unit_by_course($id);
        } else if ($this->data['usertypeID'] == '3') {
            $this->data['course_units'] = $this->courses_m->get_published_course_unit_by_course($id);
            foreach ($this->data['course_units'] as $index => $units) {
                $this->data['course_units'][$index]->chapters = $this->courses_m->get_published_course_unit_chapter($units->id);
                foreach ($this->data['course_units'][$index]->chapters as $indexy => $chapters) {
                    if ($this->courses_m->get_coursecontent($chapters->id)) {
                        $coverage = 0;
                        $covered = 0;
                        $contents = $this->courses_m->get_contents($chapters->id);
                        foreach ($contents as $key => $content) {
                            $array = [
                                'student_id' => (int) $this->data['loginuserID'],
                                'content_id' => (int) $content->id,
                                'chapter_id' => $chapters->id,
                            ];
                            $exists = $this->coursesstudent_progress_m->get_order_by_courses_student_progress($array);
                            $contents[$key]->exists = $exists ? true : false;
                            $covered += $exists ? $content->percentage_coverage : 0;
                            $coverage += $content->percentage_coverage;
                        }
                        $this->data['course_units'][$index]->chapters[$indexy]->content_exists = true;
                        $this->data['course_units'][$index]->chapters[$indexy]->total_coverage = $coverage;
                        $this->data['course_units'][$index]->chapters[$indexy]->covered = $covered;
                    } else {
                        $this->data['course_units'][$index]->chapters[$indexy]->content_exists = false;
                    }
                }
            }
        } else if ($this->data['usertypeID'] == '4') {
            $student_id = htmlentities(escapeString($this->uri->segment(4)));
            $this->data['course_units'] = $this->courses_m->get_published_course_unit_by_course($id);
            foreach ($this->data['course_units'] as $index => $units) {
                $this->data['course_units'][$index]->chapters = $this->courses_m->get_published_course_unit_chapter($units->id);
                foreach ($this->data['course_units'][$index]->chapters as $indexy => $chapters) {
                    if ($this->courses_m->get_coursecontent($chapters->id)) {
                        $coverage = 0;
                        $covered = 0;
                        $contents = $this->courses_m->get_contents($chapters->id);
                        foreach ($contents as $key => $content) {
                            $array = [
                                'student_id' => (int) $student_id,
                                'content_id' => (int) $content->id,
                                'chapter_id' => $chapters->id,
                            ];
                            $exists = $this->coursesstudent_progress_m->get_order_by_courses_student_progress($array);
                            $contents[$key]->exists = $exists ? true : false;
                            $covered += $exists ? $content->percentage_coverage : 0;
                            $coverage += $content->percentage_coverage;
                        }
                        $this->data['course_units'][$index]->chapters[$indexy]->content_exists = true;
                        $this->data['course_units'][$index]->chapters[$indexy]->total_coverage = $coverage;
                        $this->data['course_units'][$index]->chapters[$indexy]->covered = $covered;
                    } else {
                        $this->data['course_units'][$index]->chapters[$indexy]->content_exists = false;
                    }
                }
            }
        }

        $this->data['course_unitid'] = $id;
        $this->data["subview"] = "courses/unitlist";
        $this->load->view('_layout_course', $this->data);
    }

    public function chapterlist()
    {
        $courseunit_id = htmlentities(escapeString($this->uri->segment(3)));
        $this->session->set_userdata('courseunit_id', $courseunit_id);
        $this->data['courseunitchapters'] = $this->courses_m->get_course_unit_chapter($courseunit_id);
        $this->data["subview"] = "courses/chapterlist";
        $this->load->view('_layout_course', $this->data);
    }

    public function addcontent()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js',
            ),
        );
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $coursechapter_id = htmlentities(escapeString($this->uri->segment(3)));
        $link = isset($_GET['link']) ? $_GET['link'] : '';

        if ($course) {
            $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        }
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');

        if ($_POST) {
            $rules = $this->rulesforresource();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "courses/addresource";
                $this->load->view('_layout_course', $this->data);
            } else {
                $array = array(
                    "coursechapter_id" => $coursechapter_id,
                    "course_id" => $course,
                    "content_title" => $this->input->post('content_title'),
                    "chapter_content" => $this->input->post('chapter_content'),
                    "percentage_coverage" => $this->input->post('percentage_coverage'),
                );

                $insert_id = $this->courses_m->insert_resource($array);

                $this->session->set_flashdata('success', $this->lang->line('menu_success'));

                if ($course == '') {

                    redirect(base_url("courses/chapterlist/" . $this->session->userdata('courseunit_id')));
                } else {
                    if ($link == 'contents') {
                        redirect(base_url("courses/contents/" . $course));
                    } else {
                        redirect(base_url("courses/show/" . $course));
                    }
                }
            }
        } else {
            $this->data["subview"] = "courses/addresource";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function content()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/checkbox/checkbox.css',
                'assets/inilabs/form/fuelux.min.css',
            ),
        );
        $this->data['footerassets'] = array(
            'js' => array(
                'assets/inilabs/form/fuelux.min.js',
            ),
        );

        $coursechapter_id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['course_id'] = $_GET['course_id'];
        $viewType = isset($_GET['view_type'])?$_GET['view_type']:2;
        
        $lists = [];
        $this->data['chapters'] = array();
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');

        $this->data['chapters'] = $this->chapter_m->get_single_chapter(['id' => $coursechapter_id]);

        if($this->session->userdata('usertypeID') == 3){

            // log start
            $event = 'chapter content page';
            $remarks = 'visited chapter: '.$this->data['chapters']->chapter_name.' content by '.$this->session->userdata("name");
            createCourseLog($event,$remarks);
            // $this->log_m->last_record($this->data['loginuserID']);

        }

        

        if($viewType == 2){
            $this->data['contents'] = $this->courses_m->get_all_contents($coursechapter_id);
            $this->data['chapters']->attachments = $this->data['attachment'] = $this->courses_m->get_attachment($coursechapter_id);
            $this->data['chapters']->contents = $this->courses_m->get_all_contents($coursechapter_id);
            $this->data['chapters']->links =  $this->data['link'] = $this->courses_m->get_link($coursechapter_id);
            $this->data['chapters']->quizzes = $this->courses_m->get_quizzes($coursechapter_id);
            $this->data['chapters']->homeworks = $this->homework_m->get_order_by_all_homework([
                'unit_id' => $this->data['chapters']->unit_id,
                'chapter_id' => $coursechapter_id,
                'schoolyearID' => $schoolyearID,
            ]);
            $this->data['chapters']->assignments = $this->assignment_m->get_order_by_all_assignment([
                'unit_id' => $this->data['chapters']->unit_id,
                'chapter_id' => $coursechapter_id,
                'schoolyearID' => $schoolyearID,
            ]);
            $this->data['chapters']->classworks = $this->classwork_m->get_order_by_all_classwork([
                'unit_id' => $this->data['chapters']->unit_id,
                'chapter_id' => $coursechapter_id,
                'schoolyearID' => $schoolyearID,
            ]);


        }else{
            $this->data['contents'] = $this->courses_m->get_contents($coursechapter_id);
            $this->data['chapters']->attachments = $this->data['attachment'] = $this->courses_m->get_published_attachment($coursechapter_id);
            $this->data['chapters']->contents = $this->courses_m->get_contents($coursechapter_id);
            $this->data['chapters']->links =  $this->data['link'] = $this->courses_m->get_published_link($coursechapter_id);
            $this->data['chapters']->quizzes = $this->courses_m->get_published_quizzes($coursechapter_id);
            $this->data['chapters']->homeworks = $this->homework_m->get_order_by_published_homework([
                'unit_id' => $this->data['chapters']->unit_id,
                'chapter_id' => $coursechapter_id,
                'schoolyearID' => $schoolyearID,
            ]);
            $this->data['chapters']->assignments = $this->assignment_m->get_order_by_published_assignment([
                'unit_id' => $this->data['chapters']->unit_id,
                'chapter_id' => $coursechapter_id,
                'schoolyearID' => $schoolyearID,
            ]);
            $this->data['chapters']->classworks = $this->classwork_m->get_order_by_published_classwork([
                'unit_id' => $this->data['chapters']->unit_id,
                'chapter_id' => $coursechapter_id,
                'schoolyearID' => $schoolyearID,
            ]);
        }
        
        
        if ($this->data['chapters']->classworks) {
            if (is_array($this->data['chapters']->classworks)) {
                foreach ($this->data['chapters']->classworks as $data) {
                    array_push($lists, $data);
                }
            } else {
                array_push($lists, $this->data['chapters']->classworks);
            }
        }
        if ($this->data['chapters']->homeworks) {
            if (is_array($this->data['chapters']->homeworks)) {
                foreach ($this->data['chapters']->homeworks as $data) {
                    // $data->homework_medias = $this->homework_media_m->get_order_by_homework_media(['homeworkID' => $data->homeworkID]);
                    array_push($lists, $data);
                }
            } else {
                array_push($lists, $this->data['chapters']->homeworks);
            }
        }
       
        if ($this->data['chapters']->assignments) {
            if (is_array($this->data['chapters']->assignments)) {
                foreach ($this->data['chapters']->assignments as $data) {
                    array_push($lists, $data);
                }
            } else {
                array_push($lists, $this->data['chapters']->assignments);
            }
        }
        if ($this->data['chapters']->contents) {
            if (is_array($this->data['chapters']->contents)) {
                foreach ($this->data['chapters']->contents as $data) {
                    array_push($lists, $data);
                }
            } else {
                array_push($lists, $this->data['chapters']->contents);
            }
        }
        if ($this->data['chapters']->attachments) {
            if (is_array($this->data['chapters']->attachments)) {
                foreach ($this->data['chapters']->attachments as $data) {
                    array_push($lists, $data);
                }
            } else {
                array_push($lists, $this->data['chapters']->attachments);
            }
        }
        if ($this->data['chapters']->quizzes) {
            if (is_array($this->data['chapters']->quizzes)) {
                foreach ($this->data['chapters']->quizzes as $data) {
                    array_push($lists, $data);
                }
            } else {
                array_push($lists, $this->data['chapters']->quizzes);
            }
        }
        if ($this->data['chapters']->links) {
            if (is_array($this->data['chapters']->links)) {
                foreach ($this->data['chapters']->links as $data) {
                    array_push($lists, $data);
                }
            } else {
                array_push($lists, $this->data['chapters']->links);
            }
        }

        usort($lists, function ($a, $b) {
            if (isset($a->order) && isset($b->order)) {
                return $a->order > $b->order;
            }
        });
        $this->data['chapters']->lists = $lists;
       
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['loginuserID'] = $this->session->userdata('loginuserID');

        if($viewType == 2){
            $this->data['quizzes'] = $this->courses_m->get_quizzes($coursechapter_id);
        }else{
            $this->data['quizzes'] = $this->courses_m->get_published_quizzes($coursechapter_id);
        }
        $this->data['chapter_id'] = $coursechapter_id;

        $coverage = 0;
        $covered = 0;
        foreach ($this->data['contents'] as $index => $content) {
            $array = [
                'student_id' => (int) $this->data['loginuserID'],
                'content_id' => (int) $content->id,
                'chapter_id' => $coursechapter_id,
            ];
            $exists = $this->coursesstudent_progress_m->get_order_by_courses_student_progress($array);
            $this->data['contents'][$index]->exists = $exists ? true : false;
            $covered += $exists ? $content->percentage_coverage : 0;
            $coverage += $content->percentage_coverage;
        }
        $this->data['totalCoverage'] = $coverage;
        $this->data['covered'] = $covered;
        $array = [
            'student_id' => (int) $this->data['loginuserID'],
            'chapter_id' => $coursechapter_id,
        ];
        $last_covered = $this->coursesstudent_progress_m->get_last_covered_content($array);

        if ($last_covered) {
            $this->data['last_covered'] = $this->coursecontent_m->get_content($last_covered->content_id);
        }

        

        $this->data["subview"] = "courses/content";
        $this->load->view('_layout_main', $this->data);
    }

    public function getContent()
    {
        if ($_POST) {
            $chapter_id = $this->input->post('chapter_id');
            $student_id = $this->input->post('student_id');

            $html = '<tr><th>Title</th><th>Covered %</th></tr>';

            $contents = $this->courses_m->get_contents($chapter_id);

            if ($contents) {
                foreach ($contents as $index => $content) {
                    $array = [
                        'student_id' => (int) $student_id,
                        'content_id' => (int) $content->id,
                        'chapter_id' => $chapter_id,
                    ];
                    $exists = $this->coursesstudent_progress_m->get_order_by_courses_student_progress($array);
                    $content->exists = $exists ? $content->percentage_coverage : 0;

                    $html .= '<tr><td>' . $content->content_title . '</td><td>' . $content->exists . ' out of ' . $content->percentage_coverage . '</td></tr>';
                }
                echo $html;
            } else {
                $html .= '<tr><th>No Data.</th><th></th></tr>';
                echo $html;
            }
        }
    }

    public function getQuiz()
    {
        if ($_POST) {
            $chapter_id = $this->input->post('chapter_id');
            $student_id = $this->input->post('student_id');

            $html = '<tr><th>Quiz Name</th><th>Percentage Coverage</th><th>Scored</th></tr>';

            $quizzes = $this->courses_m->get_published_quizzes($chapter_id);

            if ($quizzes) {
                foreach ($quizzes as $index => $quiz) {
                    $quiz_result = $this->courses_m->get_quiz_report($student_id, $quiz->id);
                    $html .= '<tr><th>' . $quiz->quiz_name . '</th><th>' . $quiz->percentage_coverage . '</th><th>' . ((isset($quiz_result) ? $quiz_result->total_percentage : 0) / 100) * $quiz->percentage_coverage . ' out of ' . $quiz->percentage_coverage . '</th></tr>';
                }
                echo $html;
            } else {
                $html .= '<tr><th>No Data.</th><th></th><th></th></tr>';
                echo $html;
            }
        }
    }

    public function getHomework()
    {
        if ($_POST) {
            $chapter_id = $this->input->post('chapter_id');
            $student_id = $this->input->post('student_id');
            $unit_id = $this->input->post('unit_id');

            $html = '<tr><th>Title</th><th>Description</th><th>Deadline Date</th></tr>';

            $homeworks = $this->homework_m->get_order_by_published_homework([
                'unit_id'       => $unit_id,
                'chapter_id'    => $chapter_id,
                'schoolyearID'  => $this->session->userdata('defaultschoolyearID')
            ]);

            if ($homeworks) {
                foreach ($homeworks as $index => $homework) {
                    $html .= '<tr><th>' . $homework->title . '</th><th>' . $homework->description . '</th><th>' . $homework->deadlinedate . '</th></tr>';
                }
                echo $html;
            } else {
                $html .= '<tr><th>No Data.</th><th></th><th></th></tr>';
                echo $html;
            }
        }
    }

    public function getClasswork()
    {
        if ($_POST) {
            $chapter_id = $this->input->post('chapter_id');
            $student_id = $this->input->post('student_id');
            $unit_id = $this->input->post('unit_id');

            $html = '<tr><th>Title</th><th>Description</th><th>Deadline Date</th></tr>';

            $classworks = $this->classwork_m->get_order_by_published_classwork([
                'unit_id'       => $unit_id,
                'chapter_id'    => $chapter_id,
                'schoolyearID'  => $this->session->userdata('defaultschoolyearID')
            ]);

            if ($classworks) {
                foreach ($classworks as $index => $classwork) {
                    $html .= '<tr><th>' . $classwork->title . '</th><th>' . $classwork->description . '</th><th>' . $classwork->deadlinedate . '</th></tr>';
                }
                echo $html;
            } else {
                $html .= '<tr><th>No Data.</th><th></th><th></th></tr>';
                echo $html;
            }
        }
    }

    public function getAssignment()
    {
        if ($_POST) {
            $chapter_id = $this->input->post('chapter_id');
            $student_id = $this->input->post('student_id');
            $unit_id = $this->input->post('unit_id');

            $html = '<tr><th>Title</th><th>Description</th><th>Deadline Date</th></tr>';

            $assignments = $this->assignment_m->get_order_by_published_assignment([
                'unit_id'       => $unit_id,
                'chapter_id'    => $chapter_id,
                'schoolyearID'  => $this->session->userdata('defaultschoolyearID')
            ]);

            if ($assignments) {
                foreach ($assignments as $index => $assignment) {
                    $html .= '<tr><th>' . $assignment->title . '</th><th>' . $assignment->description . '</th><th>' . $assignment->deadlinedate . '</th></tr>';
                }
                echo $html;
            } else {
                $html .= '<tr><th>No Data.</th><th></th><th></th></tr>';
                echo $html;
            }
        }
    }

    public function trackContentProgress()
    {

        $usertypeID = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');

        if ($usertypeID == '3') {
            $array = [
                'student_id' => (int) $loginuserID,
                'content_id' => (int) $_POST['id'],
                'chapter_id' => (int) $_POST['chapter_id'],
            ];
            $exists = $this->coursesstudent_progress_m->get_order_by_courses_student_progress($array);
    
            if (!$exists) {
                $result = $this->coursesstudent_progress_m->insert_courses_student_progress($array);
                echo $result;
            }
        }
    }

    public function resourcelist()
    {
        $coursechapter_id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['coursechapterresources'] = $this->courses_m->get_course_unit_chapter_resource($coursechapter_id);
        $this->data["subview"] = "courses/resourcelist";
        $this->load->view('_layout_course', $this->data);
    }
    public function check_link(Type $var = null)
    {
        $this->form_validation->set_rules('item_link[]', 'Item Link', 'trim|required|xss_clean|required');

        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => true,
                'item_link_error' => form_error('item_link[]'),

            );
            echo json_encode($array);
        } else {
            $array['success'] = true;
            echo json_encode($array);
        }
    }

    public function check_attachment()
    {
        $file_name = $this->input->post('file_name');
        $this->form_validation->set_rules('file_name[]', 'Filename', 'trim|required|xss_clean|required');
        $this->form_validation->set_rules('file_name1[]', 'File', 'required');
        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => true,
                'file_name_error' => form_error('file_name[]'),
                'file_error' => form_error('file_name1[]'),
            );

            echo json_encode($array);
        } else {
            $array['success'] = true;
            echo json_encode($array);
        }
    }

    public function addFiles()
    {

        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $coursechapter_id = htmlentities(escapeString($this->uri->segment(3)));
        $GLOBALS['msg'] = '';
        $GLOBALS['msglink'] = '';
        $link = isset($_GET['link']) ? $_GET['link'] : '';

        if ($_POST) {
            if ($_FILES) {
                if (count($_FILES["item_file"]['name']) > 0) {
                    //check if any file uploaded
                    $GLOBALS['msg'] = ""; //initiate the global message
                    for ($j = 0; $j < count($_FILES["item_file"]['name']); $j++) {


                        //loop the uploaded file array
                        $filen = $_FILES["item_file"]['name']["$j"]; //file name
                        $_FILES['attach']['tmp_name'] = $_FILES['item_file']['tmp_name'][$j];
						$image_info = getimagesize($_FILES['item_file']['tmp_name'][$j]);
                        $random = rand(1, 10000000000000000);
                        $makeRandom = hash('md5', $random . $this->input->post('title') . config_item("encryption_key"));
                        $file_name_rename = $makeRandom;
                        $explode = explode('.', $filen);
                        $path = './uploads/images/' . $file_name_rename . '.' . end($explode); //generate the destination path
                        $target_dir = "./uploads/images/";
                        if (move_uploaded_file($_FILES["item_file"]['tmp_name']["$j"], $path)) {
                            //upload the file
                            $image_width = $image_info[0];
							$image_height = $image_info[1];
                            
                            resizeImageDifferentSize($file_name_rename . '.' . end($explode),$target_dir,$image_width,$image_height);

                            $GLOBALS['msg'] .= "File# " . ($j + 1) . " ($filen) uploaded successfully<br>"; //Success message
                            $array = array(
                                "coursechapter_id" => $coursechapter_id,
                                "course_id" => $course,
                                "attachment" => $file_name_rename . '.' . end($explode),
                                "file_name" => $_POST["file_name"][$j],
                            );

                            $insert_id = $this->courses_m->insert_attachment($array);
                        }
                    }
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));

                    if ($course == '') {
                        redirect(base_url("courses/chapterlist/" . $this->session->userdata('courseunit_id')));
                    } else {
                        if ($link == 'attachments') {
                            redirect(base_url("courses/attachments/" . $course));
                        } else {
                            redirect(base_url("courses/show/" . $course));
                        }
                    }
                } else {
                    $GLOBALS['msg'] = "No files found to upload"; //Failed message
                }
            }
        }



        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data["subview"] = "courses/addfiles";
        $this->load->view('_layout_course', $this->data);
    }

    public function addLinks()
    {
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $coursechapter_id = htmlentities(escapeString($this->uri->segment(3)));
        $GLOBALS['msg'] = '';
        $GLOBALS['msglink'] = '';
        $link = isset($_GET['link']) ? $_GET['link'] : '';
        if ($_POST) {
            if (isset($_POST["item_link"])) {
                $GLOBALS['msglink'] = "";
                $index = 0;
                foreach ($_POST["item_link"] as $item_link) {
                    $array = array(
                        "coursechapter_id" => $coursechapter_id,
                        "course_id" => $course,
                        "courselink" => $item_link,
                        "type" => $_POST["type"][$index],
                    );

                    $insert_id = $this->courses_m->insert_link($array);
                    $index++;
                }
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                if ($course == '') {
                    redirect(base_url("courses/chapterlist/" . $this->session->userdata('courseunit_id')));
                } else {
                    if ($link === 'links') {
                        redirect(base_url("courses/links/" . $course));
                    } else {
                        redirect(base_url("courses/show/" . $course));
                    }
                }
            } else {
                $GLOBALS['msglink'] = "No Link found to Add"; //Failed message
            }
        }
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data["subview"] = "courses/addlinks";
        $this->load->view('_layout_course', $this->data);
    }

    public function addquizzes()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js',
            ),
        );

        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $coursechapter_id = htmlentities(escapeString($this->uri->segment(3)));

        $this->data['course_id'] = $course;
        $this->data['coursechapter_id'] = $coursechapter_id;

        if ($_POST) {
            $rules = $this->rulesforquiz();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "courses/addquizzes";
                $this->load->view('_layout_course', $this->data);
            } else {
                $array = array(
                    "course_id" => $course,
                    "coursechapter_id" => $coursechapter_id,
                    "quiz_name" => $this->input->post('quiz_name'),
                    "percentage_coverage" => $this->input->post('percentage_coverage'),
                    "published" => 2,
                );

                $insert_id = $this->courses_m->insert_quizzes($array);

                $this->session->set_flashdata('success', $this->lang->line('menu_success'));

                // if ($course == '') {
                //     redirect(base_url("courses/chapterlist/" . $this->session->userdata('courseunit_id')));
                // } else {
                //     redirect(base_url("courses/addquestion/" . $insert_id . "/" . $coursechapter_id . '?course=' . $course));
                // }
                redirect(base_url("courses/new_quiz_ui/" . $insert_id . "/" . $coursechapter_id . '?course=' . $course));
            }
        } else {
            $this->data["subview"] = "courses/addquizzes";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function ajax_addquizzes()
    {
        if ($_POST) {
            $rules = $this->rulesforquiz();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['form_validation'] = validation_errors();
                $retArray['status'] = true;
                // $retArray['error'] = validation_errors();

                $arr = array(
                    'quiz_name' => form_error('quiz_name'),
                    'percentage_coverage' => form_error('percentage_coverage'),
                );
                $retArray['error'] = $arr;

                echo json_encode($retArray);
                exit;
            } else {
                $array = array(
                    "coursechapter_id" => $this->input->post('chapter_id'),
                    "course_id" => $this->input->post('course_id'),
                    "quiz_name" => $this->input->post('quiz_name'),
                    "percentage_coverage" => $this->input->post('percentage_coverage'),
                    "published" => 2,
                );
                $insert_id = $this->courses_m->insert_quizzes($array);
                $retArray['status'] = true;
                $retArray['render'] = 'success';
                $retArray['id'] = $insert_id;
                $retArray['message'] = $this->lang->line('menu_success');
                echo json_encode($retArray);
                exit;
            }
        }
    }

    public function new_quiz_ui()
    {
        $quiz_id = htmlentities(escapeString($this->uri->segment(3)));
        $chapter_id = htmlentities(escapeString($this->uri->segment(4)));
        $usertypeID = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');
        $course = isset($_GET['course']) ? $_GET['course'] : '';

        $this->data['usertypeID'] = $usertypeID;
        $this->data['loginuserID'] = $loginuserID;
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        $this->data['course_id'] = $course;
        $this->data['quiz'] = $this->courses_m->get_quiz($quiz_id);
        $this->data['groups'] = $this->question_group_m->get_order_by_question_group();
        $this->data['levels'] = $this->question_level_m->get_order_by_question_level();
        $this->data['types'] = pluck($this->question_type_m->get_order_by_question_type_except_subjective(), 'obj', 'questionTypeID');
        $this->data['chapter_quizzes']  = $this->question_bank_m->get_objective_question_bank_from_chapter_ids(array('chapter_ids' => $chapter_id));
        $this->data['quiz_id'] = $quiz_id;
        $this->data['select_options'] = array();
        $this->data['select_answers'] = array();
        $this->data['typeID'] = 0;
        $this->data['totalOptionID'] = 0;

        if ($chapter_id) {
            $objects = $this->question_bank_m->get_classes_from_chapter($chapter_id);
            $this->data['set_class'] = $objects->classesID;
            $this->data['set_subject'] = $objects->subjectID;
            $this->data['set_unit'] = $objects->unit_id;
            $this->data['set_chapter'] = $chapter_id;
            $this->data['chapters'] = $this->chapter_m->get_chapter_from_subject_id($this->data['set_subject']);
            $this->data['subjects'] = $this->chapter_m->get_subject_from_class($this->data['set_class']);
            $this->data['units'] = $this->unit_m->get_units_by_subject_id($this->data['set_subject']);
        } elseif ($course) {
            $objects = $this->question_bank_m->get_classes_from_course($course);
            $this->data['set_class'] = $objects->classesID;
            $this->data['set_subject'] = $objects->subjectID;
            $this->data['set_unit'] = 0;
            $this->data['set_chapter'] = 0;
            $this->data['chapters'] = $this->chapter_m->get_chapter_from_subject_id($this->data['set_subject']);
            $this->data['subjects'] = $this->chapter_m->get_subject_from_class($this->data['set_class']);
            $this->data['units'] = $this->unit_m->get_units_by_subject_id($this->data['set_subject']);
        } else {
            $this->data['subjects'] = [];
            $this->data['chapters'] = [];
            $this->data['units'] = [];
        }
        $this->data['classes'] = $this->classes_m->get_classes();
        $this->data["subview"] = "courses/new_quiz_ui";
        $this->load->view('_layout_course', $this->data);
    }

    public function insert_quiz_question()
    {
        $quiz_id = $this->input->post('quiz_id');
        $chapter_id = $this->input->post('chapter_id');
        $usertypeID = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');
        $course = isset($_GET['course']) ? $_GET['course'] : 0;
        $course_id = $this->input->post('course_id');
        $this->form_validation->set_rules('quizzes[]', 'Quizzes', 'trim|xss_clean');
        // $this->form_validation->set_rules('quiz_title', 'quiz_title', 'trim|required|xss_clean|max_length[100]|callback_quiz_title');
        // $this->form_validation->set_rules('quiz_percentage', 'quiz_percentage', 'trim|required|xss_clean|max_length[3]|numeric|greater_than[0]');
        if ($this->form_validation->run() == false) {
            // $retArray['status'] = true;
            // $retArray['error'] = validation_errors();
            $retArray = array(
                'status' => true,
                'error' => true,
                // 'quiz_title' => form_error('quiz_title'),
                // 'quiz_percentage' => form_error('quiz_percentage'),
            );
            echo json_encode($retArray);
            exit;
        } else {
            // $quiz_title = $this->input->post('quiz_title');
            // $quiz_percentage = $this->input->post('quiz_percentage');
            $quizzes = $this->input->post('quizzes');
            // $this->coursequiz_m->update(
            //     [
            //     'course_id' => $course_id,
            //     'coursechapter_id' => $chapter_id,
            //     'quiz_name' => $quiz_title,
            //     'percentage_coverage' => $quiz_percentage
            //     ], $quiz_id);


            $this->coursequiz_m->deletequizzes($quiz_id);

            if (isset($quizzes) && !empty($quizzes)) {
                $insert_id = $this->courses_m->insert_questions($chapter_id, $quiz_id, $quizzes);
            }
            $retArray['status'] = true;
            $retArray['render'] = 'success';
            $retArray['message'] = $this->lang->line('menu_success');
            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
            echo json_encode($retArray);
            exit;
        }
    }

    public function getMoreQuizQuestionData()
    {
        $this->__quiz_question_list_extend();
        $count = 0;
        $retArray['count'] = count($this->data['count_quizzes']);
        $retArray['render'] = $this->load->view('courses/quiz_question_list', $this->data, true);
        $retArray['status'] = true;
        echo json_encode($retArray);
        exit();
    }

    public function add_single_question()
    {
        $quiz_id = $this->input->post('quiz_id');
        $chapter_id = $this->input->post('chapter_id');
        $question_id = $this->input->post('question_id');
        $question_ids = [];
        array_push($question_ids, $question_id);
        $this->courses_m->insert_questions($chapter_id, $quiz_id, $question_ids);
    }

    public function remove_single_question()
    {
        $quiz_id = $this->input->post('quiz_id');
        $chapter_id = $this->input->post('chapter_id');
        $question_id = $this->input->post('question_id');
        $this->courses_m->delete_questions($chapter_id, $quiz_id, $question_id);
        echo $question_id;
    }

    private function __quiz_question_list_extend($page = '')
    {
        $quiz_id = isset($_GET['quiz_id']) ? $_GET['quiz_id'] : $this->input->post('quiz_id');
        $chapter_id = isset($_GET['chapter_id']) ? $_GET['chapter_id'] : $this->input->post('chapter_id');
        $course = isset($_GET['course_id']) ? $_GET['course_id'] : $this->input->post('course_id');
        

        if($chapter_id){
            $array = [
                'chapter_ids' => $chapter_id,
            ];
        }else{        
            $chapters = pluck($this->courses_m->get_course_chapters($course),'id');
            $array = [
                'chapter_ids' => $chapters,
            ];

        }
        $usertypeID = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');
        $course = isset($_GET['course_id']) ? $_GET['course_id'] : $this->input->post('course_id');
        if($chapter_id){
            $array = [
                'chapter_ids' => $chapter_id,
            ];
        }else{        
            $chapters = pluck($this->courses_m->get_course_chapters($course),'id');
            $array = [
                'chapter_ids' => $chapters,
            ];

        }

        $group = isset($_GET['group_id']) ? $_GET['group_id'] : $this->input->post('group_id');
        $level = isset($_GET['level_id']) ? $_GET['level_id'] : $this->input->post('level_id');
        $question_type_id = isset($_GET['type_id']) ? $_GET['type_id'] : $this->input->post('type_id');
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $this->data['quiz_id'] = $quiz_id;
        $this->data['quiz'] = $this->courses_m->get_quiz($quiz_id);
        $this->data['groups'] = $this->question_group_m->get_order_by_question_group();
        $this->data['levels'] = $this->question_level_m->get_order_by_question_level();
        $this->data['types'] = pluck($this->question_type_m->get_order_by_question_type_except_subjective(), 'obj', 'questionTypeID');

        if ($level != 0) {
            $array['levelID'] = $level;
        }
        if ($group != 0) {
            $array['groupID'] = $group;
        }
        if ($question_type_id != 0) {
            $array['type_id'] = $question_type_id;
        }
        $this->data['bulk_array'] = $array;
        $this->data['count_quizzes'] = $this->question_bank_m->get_objective_question_bank_from_chapter_ids('', '', $array);
        $this->data['chapter_quizzes'] = $this->question_bank_m->get_objective_question_bank_from_chapter_ids(10, $page, $array);
        $this->data['questions'] = pluck($this->question_bank_m->getRecentQuestion('', $page, ''), 'obj', 'questionBankID');
        $allOptions = $this->question_option_m->get_order_by_question_option();

        $options = [];
        foreach ($allOptions as $option) {
            if ($option->name == "" && $option->img == "") {
                continue;
            }

            $options[$option->questionID][] = $option;
        }
        $this->data['options'] = $options;
        $this->data['quiz_id'] = $quiz_id;

        $allAnswers = $this->question_answer_m->get_order_by_question_answer();
        $answers = [];
        foreach ($allAnswers as $answer) {
            $answers[$answer->questionID][] = $answer;
        }

        $this->data['answers'] = $answers;
    }

    public function quiz_question_list()
    {
        $this->data['row'] = isset($_GET['row']) ? $_GET['row'] : $this->input->post('row');

        $this->__quiz_question_list_extend();
        $count = 0;

        $retArray['count'] = count($this->data['count_quizzes']);
        $retArray['render'] = $this->load->view('courses/quiz_question_list', $this->data, true);
        $retArray['status'] = true;
        $retArray['row'] = $this->data['row'];

        echo json_encode($retArray);
        exit();
    }

    public function quiz_question_list_doc_load()
    {

        $this->__quiz_question_list_extend();
        $this->data['chapter_quizzes'] = $this->question_bank_m->get_objective_question_bank_from_chapter_ids('', '', $this->data['bulk_array']);
        $count = 0;
        foreach ($this->data['chapter_quizzes'] as $index => $quiz) {
            if ($this->coursequiz_m->get_bycoursechapter($quiz->questionBankID, $this->data['quiz_id']) !== null) {
                $count++;
            }
        }
        $retArray['count'] = $count;
        $retArray['render'] = $this->load->view('courses/quiz_question_list_doc_load', $this->data, true);
        $retArray['status'] = true;

        echo json_encode($retArray);
        exit();
    }

    public function create_question()
    {
        if ($_POST) {
            $usertypeID = $this->session->userdata('usertypeID');
            $loginuserID = $this->session->userdata('loginuserID');

            $postOption = $this->input->post("option");
            $imageUpload = array();
            $question_bank = array(
                "groupID" => $this->input->post("group"),
                "levelID" => $this->input->post("level"),
                "question" => $this->input->post("question"),
                "explanation" => $this->input->post("form_explanation"),
                "hints" => $this->input->post("form_hint"),
                "mark" => empty($this->input->post('mark')) ? null : $this->input->post('mark'),
                "typeNumber" => $this->input->post("type"),
                "totalOption" => $this->input->post("totalOption"),
                "create_date" => date("Y-m-d H:i:s"),
                "modify_date" => date("Y-m-d H:i:s"),
                "create_userID" => $usertypeID,
                "create_usertypeID" => $loginuserID,
                "chapter_id" => $this->input->post("chapter_id"),
                'type_id' => $this->input->post('type_id'),
            );
            $imageval = $_FILES['photos']['name'];
            $imagename = '';
            if (!empty($imageval)) {
                $acceptable = array("doc", "docx", "pdf", "gif", "jpeg", "jpg", "png");
                $target_dir = "./uploads/images/";
                $totalcount = count($_FILES['photos']['name']);

                $filesname = '';
                for ($i = 0; $i < $totalcount; $i++) {
                    $extension = end(explode(".", $_FILES["photos"]["name"][$i]));
                    if (in_array($extension, $acceptable)) {
                        $new_file = $_FILES['photos']['name'][$i];
                        $temp = explode(".", $new_file);
                        $newfilename = time() . '_' . $i . '.' . end($temp);
                        $new_file = $newfilename;
                        $target_file = $target_dir . $newfilename;
                        $filesname .= ',' . $new_file;
                        move_uploaded_file($_FILES["photos"]["tmp_name"][$i], $target_file);
                    }
                }
                $filesname = substr($filesname, 1);
            }
            $question_bank['upload'] = $filesname;
            $options = $this->input->post("option");
            $answers = $this->input->post("answer");

            $questionInsertID = $this->question_bank_m->insert_question_bank($question_bank);

            if ($this->input->post("type") == 1 || $this->input->post("type") == 2) {

                $imgArray = array();
                if ($this->input->post("totalOption") > 0) {
                    for ($imgi = 1; $imgi <= $this->input->post("totalOption"); $imgi++) {
                        if ($_FILES['image' . $imgi]['name'] != "") {
                            $imgArray[$imgi] = 'image' . $imgi;
                        }
                    }
                }

                if (customCompute($imgArray)) {
                    $imageUpload = $this->imageUpload($imgArray);
                }

                $getQuestionOptions = pluck($this->question_option_m->get_order_by_question_option(['questionID' => $questionInsertID]), 'optionID');

                if (!customCompute($getQuestionOptions)) {
                    foreach (range(1, 10) as $optionID) {
                        $data = [
                            'name' => '',
                            'questionID' => $questionInsertID,
                        ];
                        $getQuestionOptions[] = $this->question_option_m->insert_question_option($data);
                    }
                }

                $totalOption = $this->input->post("totalOption");
                foreach ($options as $key => $option) {
                    if ($option == '' && !isset($imageUpload['success'][$key + 1])) {
                        $totalOption--;
                        continue;
                    }

                    $data = [
                        'name' => $option,
                        'img' => isset($imageUpload['success'][$key + 1]) ? $imageUpload['success'][$key + 1] : '',
                    ];
                    $this->question_option_m->update_question_option($data, $getQuestionOptions[$key]);
                    if (in_array($key + 1, $answers)) {
                        $ansData = [
                            'questionID' => $questionInsertID,
                            'optionID' => $getQuestionOptions[$key],
                            'typeNumber' => $this->input->post("type"),
                        ];
                        $this->question_answer_m->insert_question_answer($ansData);
                    }
                }

                if ($totalOption != $this->input->post("totalOption")) {
                    $this->question_bank_m->update_question_bank(['totalOption' => $totalOption], $questionInsertID);
                }
            } elseif ($this->input->post("type") == 3) {
                $totalOption = $this->input->post("totalOption");
                foreach ($answers as $answer) {
                    if ($answer == '') {
                        $totalOption--;
                        continue;
                    }
                    $ansData = [
                        'questionID' => $questionInsertID,
                        'text' => $answer,
                        'typeNumber' => $this->input->post("type"),
                    ];
                    $this->question_answer_m->insert_question_answer($ansData);
                }
                if ($totalOption != $this->input->post("totalOption")) {
                    $this->question_bank_m->update_question_bank(['totalOption' => $totalOption], $questionInsertID);
                }
            } elseif ($this->input->post("type") == 4) {
                $totalOption = $this->input->post("totalOption");
                $ansData = [
                    'questionID' => $questionInsertID,
                    'text' => $totalOption,
                    'typeNumber' => $this->input->post("type"),
                ];
                $this->question_answer_m->insert_question_answer($ansData);

                if ($totalOption != $this->input->post("totalOption")) { {
                        $this->question_bank_m->update_question_bank(['totalOption' => $totalOption], $questionInsertID);
                    }
                }
            } elseif ($this->input->post("type") == 5) {
                $totalOption = $this->input->post("totalOption");
                $ansData = [
                    'questionID' => $questionInsertID,
                    'text' => 'accumulative',
                    'typeNumber' => $this->input->post("type"),
                ];
                $this->question_answer_m->insert_question_answer($ansData);
                if ($totalOption != $this->input->post("totalOption")) { {
                        $this->question_bank_m->update_question_bank(['totalOption' => $totalOption], $questionInsertID);
                    }
                }
            }
            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
        }
    }

    public function imageUpload($imgArrays)
    {
        $returnArray = array();
        $error = '';

        if (customCompute($imgArrays)) {
            foreach ($imgArrays as $imgkKey => $imgValue) {
                $new_file = '';
                if ($_FILES[$imgValue]['name'] != "") {
                    $file_name = $_FILES[$imgValue]['name'];
                    $random = random19();
                    $makeRandom = $new_file = hash('sha512', $random . $_FILES[$imgValue]['name'] . date('Y-M-d-H:i:s') . config_item("encryption_key"));
                    $file_name_rename = $makeRandom;
                    $explode = explode('.', $file_name);
                    if (customCompute($explode) >= 2) {
                        $new_file = $file_name_rename . '.' . end($explode);
                        $config['upload_path'] = "./uploads/images";
                        $config['allowed_types'] = "gif|jpg|png";
                        $config['file_name'] = $new_file;
                        $_FILES['attach']['tmp_name'] = $_FILES[$imgValue]['tmp_name'];
                        $image_info = getimagesize($_FILES[$imgValue]['tmp_name']);
                        $image_width = $image_info[0];
                        $image_height = $image_info[1];
                        // $config['max_size'] = (1024 * 2);
                        // $config['max_width'] = '3000';
                        // $config['max_height'] = '3000';
                        $this->load->library('upload');
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload($imgValue)) {
                            preg_match_all('!\d+!', $imgValue, $matches);
                            $returnArray['error'][$this->upload->display_errors()][$imgkKey] = 'image ' . $matches[0][0];
                        } else {
                            if($image_width > 1800 || $image_height > 1800){
                                resizeImage($new_file,$config['upload_path']);
                             }
                            $returnArray['success'][$imgkKey] = $new_file;
                        }
                    }
                }
            }
        }

        return $returnArray;
    }

    public function addquestion()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js',
            ),
        );

        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $quiz_id = htmlentities(escapeString($this->uri->segment(3)));
        $coursechapter_id = htmlentities(escapeString($this->uri->segment(4)));
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);

        $this->data['groups'] = pluck($this->question_group_m->get_order_by_question_group(), 'obj', 'questionGroupID');
        $this->data['levels'] = pluck($this->question_level_m->get_order_by_question_level(), 'obj', 'questionLevelID');
        $this->data['types'] = pluck($this->question_type_m->get_order_by_question_type(), 'obj', 'questionTypeID');
        $this->data['chapter_quizzes'] = $this->question_bank_m->get_objective_question_bank_from_chapter_ids(array('chapter_ids' => $coursechapter_id));
        $this->data['coursechapter_id'] = $coursechapter_id;
        $this->data['quiz_id'] = $quiz_id;
        $this->data['course_id'] = $course;

        if ($_POST) {
            $this->form_validation->set_rules('quizzes[]', 'Quizzes', 'trim|required|xss_clean');
            if ($this->form_validation->run() == false) {

                $this->data["subview"] = "courses/addquestion";
                $this->load->view('_layout_course', $this->data);
            } else {

                $coursechapter_id_array = array(
                    'coursechapter_id' => $coursechapter_id,
                );
                $quiz_id_array = array(
                    'quiz_id' => $quiz_id,
                );
                $quizzes = $this->input->post('quizzes');

                $this->coursequiz_m->deletequizzes($quiz_id);
                $insert_id = $this->courses_m->insert_questions($coursechapter_id, $quiz_id, $quizzes);

                $this->session->set_flashdata('success', $this->lang->line('menu_success'));

                if ($course == '') {
                    redirect(base_url("courses/chapterdetails/" . $coursechapter_id));
                } else {
                    redirect(base_url("courses/quizzes/" . $course));
                }
            }
        } else {
            $this->data["subview"] = "courses/addquestion";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function getChapters()
    {
        $id = $this->input->post('id');
        if ((int) $id) {
            $allChapter = $this->chapter_m->get_chapters_from_unit($id);

            echo "<option value='0'>", "Select Chapter", "</option>";

            foreach ($allChapter as $value) {
                echo "<option value=\"$value->id\">", $value->chapter_name, "</option>";
            }
        }
    }

    public function alreadyexists()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {
            $subject_id = $this->courses_m->get_order_by_courses(array("subject_id" => $this->input->post("subject_id"), "id !=" => $id));
            if (customCompute($subject_id)) {
                $this->form_validation->set_message("alreadyexists", "%s already exists");
                return false;
            }
            return true;
        } else {
            $subject_id = $this->courses_m->get_order_by_courses(array("subject_id" => $this->input->post("subject_id")));

            if (customCompute($subject_id)) {
                $this->form_validation->set_message("alreadyexists", "%s already exists");
                return false;
            }
            return true;
        }
    }

    public function chapterdetails()
    {
        $coursechapter_id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['content'] = $this->coursecontent_m->get_contentbycoursechapter($coursechapter_id);
        $this->data['attachment'] = $this->courses_m->get_attachment($coursechapter_id);
        $this->data['quizzes'] = $this->courses_m->get_quizzes($coursechapter_id);
        $this->data['links'] = $this->courses_m->get_link($coursechapter_id);
        $this->data["subview"] = "courses/chapterdetails";
        $this->load->view('_layout_course', $this->data);
    }

    public function randAssociativeArray($array, $number = 0)
    {
        $returnArray = [];
        $countArray = customCompute($array);
        if ($number > $countArray || $number == 0) {
            $number = $countArray;
        }

        if ($countArray == 1) {
            $randomKey[] = 0;
        } else {
            if (customCompute($array)) {
                $randomKey = array_rand($array, $number);
            } else {
                $randomKey = [];
            }
        }

        if (is_array($randomKey)) {
            shuffle($randomKey);
        }

        if (customCompute($randomKey)) {
            foreach ($randomKey as $key) {
                $returnArray[] = $array[$key];
            }
            return $returnArray;
        } else {
            return $array;
        }
    }

    public function postChangeCourseStatus($id)
    {
        $published = $this->input->post('published');
        $published = is_null($published) ? 1 : $published;
        $array = [
            'published' => $published,
        ];

        if ($this->courses_m->update_courses($array, $id) && $published == 1) {
            $record = $this->courses_m->get_join_courses_based_on_course_id($id);
            $title = 'Courses Published';
            $notice = "Courses for subject " . $record->subject . " for class " . $record->classes . " has been published";
            $this->notification($title, $notice, $record->classesID);
        }
        redirect(base_url("courses/"));
    }

    public function ajaxChangeCourseStatus($id)
    {
        $course = $this->courses_m->get_single_courses(['id' => $id]);
        $array = [
            'published' => $course->published == 2 ? 1 : 2,
        ];
        if ($this->courses_m->update_courses($array, $id) && $array['published'] == 1) {
            $record = $this->courses_m->get_join_courses_based_on_course_id($id);
            $title = 'Courses Published';
            $notice = "Courses for subject " . $record->subject . " for class " . $record->classes . " has been published";
            $this->notification($title, $notice, $record->classesID);
        }
    }

    public function ajaxChangeContentStatus($id)
    {
        $content = $this->coursecontent_m->get_coursecontent($id);
        $array = [
            'published' => $content->published == 2 ? 1 : 2,
        ];
        if ($this->coursecontent_m->update_content($array, $id) && $array['published'] == 1) {
            $title = 'Course Chapter Content Published';
            $notice = "Content: " . $content->content_title . " of Chapter " . $content->chapter_name . " of Unit " . $content->unit . " for subject " . $content->subject . " has been published";
            $this->notification($title, $notice, $content->classesID);
        }
    }


    public function ajaxChangeUnitStatus($id)
    {
        $unit = $this->unit_m->get_units($id);
        $array = [
            'published' => $unit->published == 2 ? 1 : 2,
        ];

        if ($this->unit_m->update_unit($array, $id) && $array['published'] == 1) {
            $title = 'Unit Published';
            $notice = "Unit " . $unit->unit_name . " for subject " . $unit->subject . " has been published";
            $this->notification($title, $notice, $unit->classesID);
        }
    }

    public function ajaxChangeChapterStatus($id)
    {
        $chapter = $this->chapter_m->get_chapters($id);
        $array = [
            'published' => $chapter->published == 2 ? 1 : 2,
        ];
        if ($this->chapter_m->update_chapter($array, $id) && $array['published'] == 1) {
            $title = 'Chapter Published';
            $notice = "Chapters " . $chapter->chapter_name . " of Unit " . $chapter->unit . " for subject " . $chapter->subject . " has been published";
            $this->notification($title, $notice, $chapter->classesID);
        }
    }

    public function ajaxChangeFileStatus($id)
    {
        $coursefiles = $this->coursefiles_m->get_coursefile($id);
        $array = [
            'published' => $coursefiles->published == 2 ? 1 : 2,
        ];
        if ($this->coursefiles_m->update($array, $id) && $array['published'] == 1) {
            $title = 'Course Chapter attachment Published';
            $notice = "Attachement: " . $coursefiles->file_name . " of Chapters " . $coursefiles->chapter_name . " of Unit " . $coursefiles->unit . " for subject " . $coursefiles->subject . " has been published";
            $this->notification($title, $notice, $coursefiles->classesID);
        }
    }

    public function ajaxChangeLinkStatus($id)
    {
        $courselink = $this->courselink_m->get_courselink($id);
        $array = [
            'published' => $courselink->published == 2 ? 1 : 2,
        ];
        if ($this->courselink_m->update($array, $id) && $array['published'] == 1) {
            $title = 'Course Chapter Link Published';
            $notice = "Course link of Chapters " . $courselink->chapter_name . " of Unit " . $courselink->unit . " for subject " . $courselink->subject . " has been published";
            $this->notification($title, $notice, $courselink->classesID);
        }
    }

    public function ajaxChangeQuizStatus($id)
    {
        $coursequiz = $this->coursequiz_m->get_coursequiz($id);
        $array = [
            'published' => $coursequiz->published == 2 ? 1 : 2,
        ];
        if ($this->coursequiz_m->update($array, $id) && $array['published'] == 1) {
            $title = 'Course Chapter Quiz Published';
            $notice = "Course quiz of Chapters " . $coursequiz->chapter_name . " of Unit " . $coursequiz->unit . " for subject " . $coursequiz->subject . " has been published";
            $this->notification($title, $notice, $coursequiz->classesID);
        }
    }

    public function contentlist()
    {
        $coursechapter_id = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['coursechapter_id'] = $coursechapter_id;
        $this->data['coursecontent'] = $this->courses_m->get_coursecontent($coursechapter_id);
        $this->data["subview"] = "courses/coursecontent";
        $this->load->view('_layout_course', $this->data);
    }

    public function editcontent()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js',
            ),
        );
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $link = isset($_GET['link']) ? $_GET['link'] : '';
        if ((int) $id) {
            $this->data['content'] = $this->coursecontent_m->get_content($id);
            $content = $this->coursecontent_m->get_content($id);

            if ($_POST) {
                $rules = $this->rulesforresource();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $this->data["subview"] = "courses/editcontent";
                    $this->load->view('_layout_course', $this->data);
                } else {
                    $array = array(
                        "content_title" => $this->input->post("content_title"),
                        "chapter_content" => $this->input->post("chapter_content"),
                        "percentage_coverage" => $this->input->post('percentage_coverage'),
                    );

                    $this->coursecontent_m->update_content($array, $id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    if ($course == '') {
                        redirect(base_url("courses/contentlist/" . $content->coursechapter_id));
                    } else {
                        if ($link == 'contents') {
                            redirect(base_url("courses/contents/" . $course));
                        } else {
                            redirect(base_url("courses/show/" . $course));
                        }
                    }
                }
            } else {
                $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
                $this->data['usertypeID'] = $this->session->userdata('usertypeID');
                $this->data["subview"] = "courses/editcontent";
                $this->load->view('_layout_course', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function postChangeContentStatus($id)
    {
        $published = $this->input->post('published');
        $published = is_null($published) ? 1 : $published;
        $content = $this->coursecontent_m->get_coursecontent($id);

        $array = [
            'published' => $published,
        ];
        if ($this->coursecontent_m->update_content($array, $id) && $array['published'] == 1) {
            $title = 'Course Chapter Content Published';
            $notice = "Content: " . $content->content_title . " of Chapter " . $content->chapter_name . " of Unit " . $content->unit . " for subject " . $content->subject . " has been published";
            $this->notification($title, $notice, $content->classesID);
        }
        $this->session->set_flashdata('success', 'status successfully changed');
        redirect(base_url("courses/contentlist/" . $content->coursechapter_id));
    }

    public function postChangeUnitStatus($id, $courseunitid)
    {

        $published = $this->input->post('published');
        $published = is_null($published) ? 1 : $published;
        $units = $this->courses_m->get_course_unit_by_unit($id);

        $array = [
            'published' => $published,
        ];

        if ($this->unit_m->update_unit($array, $id) && $published == 1) {
            $record = $this->unit_m->get_units($id);

            $title = 'Unit Published';
            $notice = "Unit " . $record->unit_name . " for subject " . $record->subject . " has been published";
            $this->notification($title, $notice, $record->classesID);
        }
        $this->session->set_flashdata('success', 'status successfully changed');
        redirect(base_url("courses/unitlist/" . $courseunitid));
    }

    public function postChangeChapterStatus($id)
    {

        $published = $this->input->post('published');
        $published = is_null($published) ? 1 : $published;

        $array = [
            'published' => $published,
        ];
        if ($this->chapter_m->update_chapter($array, $id) && $published == 1) {
            $record = $this->chapter_m->get_chapters($id);
            $title = 'Chapter Published';
            $notice = "Chapters " . $record->chapter_name . " of Unit " . $record->unit . " for subject " . $record->subject . " has been published";
            $this->notification($title, $notice, $record->classesID);
        }
        $this->session->set_flashdata('success', 'status successfully changed');
        redirect(base_url("courses/chapterlist/" . $this->session->userdata('courseunit_id')));
    }

    public function postChangeFileStatus($id, $coursechapter_id)
    {

        $coursefiles = $this->coursefiles_m->get_coursefile($id);

        $published = $this->input->post('published');
        $published = is_null($published) ? 1 : $published;

        $array = [
            'published' => $published,
        ];

        if ($this->coursefiles_m->update($array, $id) && $array['published'] == 1) {
            $title = 'Course Chapter attachment Published';
            $notice = "Attachement: " . $coursefiles->file_name . " of Chapters " . $coursefiles->chapter_name . " of Unit " . $coursefiles->unit . " for subject " . $coursefiles->subject . " has been published";
            $this->notification($title, $notice, $coursefiles->classesID);
        }

        $this->session->set_flashdata('success', 'status successfully changed');
        redirect(base_url("courses/chapterdetails/" . $coursechapter_id));
    }

    public function postChangeQuizStatus($id, $coursechapter_id)
    {

        $coursequiz = $this->coursequiz_m->get_coursequiz($id);
        $published = $this->input->post('published');
        $published = is_null($published) ? 1 : $published;

        $array = [
            'published' => $published,
        ];

        if ($this->coursequiz_m->update($array, $id) && $array['published'] == 1) {
            $title = 'Course Chapter Quiz Published';
            $notice = "Course quiz of Chapters " . $coursequiz->chapter_name . " of Unit " . $coursequiz->unit . " for subject " . $coursequiz->subject . " has been published";
            $this->notification($title, $notice, $coursequiz->classesID);
        }
        $this->session->set_flashdata('success', 'status successfully changed');
        redirect(base_url("courses/chapterdetails/" . $coursechapter_id));
    }

    public function editquiz()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js',
            ),
        );
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $coursechapter_id = htmlentities(escapeString($this->uri->segment(4)));

        if ((int) $id) {
            $this->data['quiz_name'] = $this->coursequiz_m->get($id)->quiz_name;
            $this->data['percentage_coverage'] = $this->coursequiz_m->get($id)->percentage_coverage;
            $this->data['coursechapter_id'] = $coursechapter_id;

            if ($_POST) {
                $rules = $this->rulesforquiz();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $this->data["subview"] = "courses/editquiz/" . $id;
                    $this->load->view('_layout_course', $this->data);
                } else {
                    $array = array(
                        "coursechapter_id" => $coursechapter_id,
                        "percentage_coverage" => $this->input->post('percentage_coverage'),
                        "quiz_name" => $this->input->post('quiz_name'),
                    );

                    $this->coursequiz_m->update($array, $id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("courses/chapterdetails/" . $coursechapter_id));
                }
            } else {
                $this->data["subview"] = "courses/editquiz";
                $this->load->view('_layout_course', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function updateQuiz()
    {
        $quiz_id = $this->input->post('quiz_id');
        $quiz_title = $this->input->post('quiz_title');
        $quiz_percentage = $this->input->post('quiz_percentage');

        $this->form_validation->set_rules('quiz_title', 'quiz_title', 'trim|required|xss_clean|callback_quiz_title');
        $this->form_validation->set_rules('quiz_percentage', 'quiz_percentage', 'trim|required|xss_clean|max_length[3]|numeric|greater_than[0]');
        if ($this->form_validation->run() == false) {
            $retArray = array(
                'status' => false,
                'quiz_title' => form_error('quiz_title'),
                'quiz_percentage' => form_error('quiz_percentage'),
            );
            echo json_encode($retArray);
            exit;
        } else {
            $quiz_title = $this->input->post('quiz_title');
            $quiz_percentage = $this->input->post('quiz_percentage');
            $this->coursequiz_m->update(
                [
                    'quiz_name' => $quiz_title,
                    'percentage_coverage' => $quiz_percentage
                ],
                $quiz_id
            );

            $retArray = array(
                'status' => true,
            );
            echo json_encode($retArray);
            exit;
        }
    }

    public function deletecontent()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $content = $this->coursecontent_m->get_content($id);
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $link = isset($_GET['link']) ? $_GET['link'] : '';
        if ((int) $id) {
            $this->coursecontent_m->delete_content($id);
            $this->session->set_flashdata('success', $this->lang->line('menu_success'));

            if ($link == 'contents') {
                redirect(base_url("courses/contents/" . $course));
            } else {
                redirect(base_url("courses/show/" . $course));
            }
        } else {
            redirect(base_url("courses/contentlist/" . $content->coursechapter_id));
        }
    }

    public function deletefile()
    {

        $id = htmlentities(escapeString($this->uri->segment(3)));
        $coursechapter_id = htmlentities(escapeString($this->uri->segment(4)));
        $attachment = $this->coursefiles_m->get($id);
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $link = isset($_GET['link']) ? $_GET['link'] : '';
        if ((int) $id) {
            $this->coursefiles_m->delete($id);
            $this->session->set_flashdata('success', $this->lang->line('menu_success'));

            if ($link == 'attachments') {
                redirect(base_url("courses/attachments/" . $course));
            } else {
                redirect(base_url("courses/show/" . $course));
            }
        } else {
            redirect(base_url("courses/attachments/" . $course));
        }
    }

    public function deletelink()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $coursechapter_id = htmlentities(escapeString($this->uri->segment(4)));
        $link = $this->courselink_m->get($id);
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $link = isset($_GET['link']) ? $_GET['link'] : '';
        if ((int) $id) {
            $this->courselink_m->delete($id);
            $this->session->set_flashdata('success', $this->lang->line('menu_success'));

            if ($link == 'links') {
                redirect(base_url("courses/links/" . $course));
            } else {
                redirect(base_url("courses/show/" . $course));
            }
        } else {
            redirect(base_url("courses/links/" . $course));
        }
    }

    public function deletequiz()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $coursechapter_id = htmlentities(escapeString($this->uri->segment(4)));
        $attachment = $this->coursefiles_m->get($id);
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $link = isset($_GET['link']) ? $_GET['link'] : '';
        if ((int) $id) {
            $this->coursequiz_m->delete($id);
            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
            if ($link == 'quizzes') {
                redirect(base_url("courses/quizzes/" . $course));
            } else {
                redirect(base_url("courses/show/" . $course));
            }
        } else {
            redirect(base_url("courses/quizzes/" . $course));
        }
    }

    public function quiz()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/checkbox/checkbox.css',
                'assets/inilabs/form/fuelux.min.css',
            ),
        );
        $this->data['footerassets'] = array(
            'js' => array(
                'assets/inilabs/form/fuelux.min.js',
            ),
        );

        $userID = $this->session->userdata("loginuserID");
        $usertypeID = $this->session->userdata("usertypeID");
        $this->data['usertypeID'] = $usertypeID;
        $quiz_id = htmlentities(escapeString($this->uri->segment(3)));

        if ($usertypeID == 3) {
            $this->data['previous_quizzes'] = $this->courses_m->get_quiz_result($userID, $quiz_id);
        }

        if ((int) $quiz_id) {
            $this->data['quiz_id'] = $quiz_id;

            $quiz = $this->courses_m->get_quiz($quiz_id);

            
            
             // log start
            $event = 'quiz start';
            $remarks = 'quiz: '.$quiz->quiz_name.' start by '.$this->session->userdata("name");
            createCourseLog($event,$remarks);
            // log end

           
            if (customCompute($quiz)) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');

                if ($usertypeID == 3) {
                    $this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $userID, 'srschoolyearID' => $schoolyearID));
                    if (customCompute($this->data['student'])) {
                        $array['classesID'] = $this->data['student']->srclassesID;
                        $array['sectionID'] = $this->data['student']->srsectionID;
                        $array['studentgroupID'] = $this->data['student']->srstudentgroupID;
                        $array['quiz_id'] = $quiz_id;
                        $array['schoolYearID'] = $schoolyearID;
                    }

                    if (customCompute($this->data['student'])) {
                        $this->data['class'] = $this->classes_m->get_single_classes(array('classesID' => $this->data['student']->srclassesID));
                        $this->data['section'] = $this->section_m->get_single_section(array('sectionID' => $this->data['student']->srsectionID));
                    } else {
                        $this->data['class'] = [];
                        $this->data['section'] = [];
                    }
                }

                $this->data['quiz'] = $quiz;
                $onlineExamQuestions = $this->courses_m->get_order_by_quiz_question($quiz_id);

                $allOnlineExamQuestions = $onlineExamQuestions;

                $this->data['onlineExamQuestions'] = $onlineExamQuestions;
                $onlineExamQuestions = pluck($onlineExamQuestions, 'obj', 'question_id');
                $questionsBank = pluck($this->question_bank_m->get_order_by_question_bank(), 'obj', 'questionBankID');

                $this->data['questions'] = $questionsBank;

                $options = [];
                $answers = [];
                $allOptions = [];
                $allAnswers = [];

                if (customCompute($allOnlineExamQuestions)) {
                    $pluckOnlineExamQuestions = pluck($allOnlineExamQuestions, 'question_id');
                    $allOptions = $this->question_option_m->get_where_in_question_option($pluckOnlineExamQuestions, 'questionID');
                    foreach ($allOptions as $option) {
                        if ($option->name == "" && $option->img == "") {
                            continue;
                        }

                        $options[$option->questionID][] = $option;
                    }
                    $allAnswers = $this->question_answer_m->get_where_in_question_answer($pluckOnlineExamQuestions, 'questionID');
                    foreach ($allAnswers as $answer) {
                        $answers[$answer->questionID][] = $answer;
                    }

                    $opts = [];
                    foreach ($options as $index => $option) {
                        shuffle($option);
                        $opts[$index] = $option;
                    }
                    $options = $opts;
                    $this->data['options'] = $options;
                    $this->data['answers'] = $answers;
                } else {
                    $this->data['options'] = $options;
                    $this->data['answers'] = $answers;
                }

                if ($_POST) {

                    $time = date("Y-m-d H:i:s");
                    $mainQuestionAnswer = [];
                    $userAnswer = $this->input->post('answer');

                    foreach ($allAnswers as $answer) {
                        if ($answer->typeNumber == 3) {
                            $mainQuestionAnswer[$answer->typeNumber][$answer->questionID][$answer->answerID] = $answer->text;
                        } else {
                            $mainQuestionAnswer[$answer->typeNumber][$answer->questionID][] = $answer->optionID;
                        }
                    }

                    $questionStatus = [];
                    $correctAnswer = 0;
                    $totalQuestionMark = 0;
                    $totalCorrectMark = 0;
                    $visited = [];

                    $totalAnswer = 0;
                    if (customCompute($userAnswer)) {
                        foreach ($userAnswer as $userAnswerKey => $uA) {
                            $totalAnswer += customCompute($uA);
                        }
                    }

                    if (customCompute($allOnlineExamQuestions)) {
                        foreach ($allOnlineExamQuestions as $aoeq) {
                            if (isset($questionsBank[$aoeq->question_id])) {
                                $totalQuestionMark += $questionsBank[$aoeq->question_id]->mark;
                            }
                        }
                    }

                    $f = 0;
                    foreach ($mainQuestionAnswer as $typeID => $questions) {
                        if (!isset($userAnswer[$typeID])) {
                            continue;
                        }

                        foreach ($questions as $questionID => $options) {
                            if (isset($userAnswer[$typeID][$questionID])) {
                                $totalCorrectMark += isset($questionsBank[$questionID]) ? $questionsBank[$questionID]->mark : 0;

                                $questionStatus[$questionID] = 1;
                                $correctAnswer++;
                                $f = 1;
                                if ($typeID == 3) {
                                    foreach ($options as $answerID => $answer) {
                                        $takeAnswer = strtolower($answer);
                                        $getAnswer = isset($userAnswer[$typeID][$questionID][$answerID]) ? strtolower($userAnswer[$typeID][$questionID][$answerID]) : '';
                                        $takeAns = str_replace(' ','',$takeAnswer);
                                        $getAns = str_replace(' ','',$getAnswer);
                                        if ($getAns != $takeAns) {
                                            $f = 0;
                                        }
                                    }
                                } elseif ($typeID == 1 || $typeID == 2) {
                                    if (customCompute($options) != customCompute($userAnswer[$typeID][$questionID])) {
                                        $f = 0;
                                    } else {
                                        if (!isset($visited[$typeID][$questionID])) {
                                            $visited[$typeID][$questionID] = 1;
                                        }
                                        foreach ($options as $answerID => $answer) {
                                            if (!in_array($answer, $userAnswer[$typeID][$questionID])) {
                                                $f = 0;
                                                break;
                                            }
                                        }
                                    }
                                }

                                if (!$f) {
                                    $questionStatus[$questionID] = 0;
                                    $correctAnswer--;
                                    $totalCorrectMark -= $questionsBank[$questionID]->mark;
                                }
                            }
                        }
                    }

                    $this->courses_m->insert_quiz_result([
                        'quiz_id' => $quiz_id,
                        'user_id' => $userID,
                        'total_question' => customCompute($onlineExamQuestions),
                        'total_answer' => $totalAnswer,
                        'correct_answer' => $correctAnswer,
                        'total_mark' => $totalQuestionMark,
                        'total_obtained_mark' => $totalCorrectMark,
                        'total_percentage' => sprintf("%.2f", (($totalCorrectMark > 0 && $totalQuestionMark > 0) ? (($totalCorrectMark / $totalQuestionMark) * 100) : 0)),
                        'time' => $time,
                    ]);

                    $this->session->set_flashdata('success', 'You had ' . $correctAnswer . ' correct answers.');

                    $this->data['previous_quizzes'] = $this->courses_m->get_quiz_result($userID, $quiz_id);

                    $this->data["subview"] = "courses/quiz";
                    return $this->load->view('_layout_course', $this->data);
                }

                $this->data["subview"] = "courses/quiz";
                return $this->load->view('_layout_course', $this->data);
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_course', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function notification($title, $notice, $class, $sectionID = '')
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $students = pluck($this->student_m->get_order_by_student_with_section1($class, $schoolyearID, $sectionID), 'studentID');

        $array = array(
            "title"             => $title,
            "notice"            => $notice,
            "schoolyearID"      => $schoolyearID,
            "users"             => '',
            "date"              => date('Y-m-d'),
            "create_date"       => date('Y-m-d H:i:s'),
            "create_userID"     => $this->session->userdata('loginuserID'),
            "create_usertypeID" => $this->session->userdata('usertypeID'),
            "show_to_creator"   => 0
        );
        $this->notice_m->insert_notice($array);
        $insert_id = $this->db->insert_id();

        if($insert_id){
        // insert feed
		$this->feed_m->insert_feed(
			array(
				'itemID'            => $insert_id,
				'userID'            => $this->session->userdata("loginuserID"),
				'usertypeID'        => $this->session->userdata('usertypeID'),
				'itemname'          => 'notice',
				'schoolyearID'      => $this->session->userdata('defaultschoolyearID'),
				'published'         => 1,
				'published_date'    => date("Y-m-d"),
                "show_to_creator"   => 0
			)
		);
		$feedID = $this->db->insert_id();
       
            if(customCompute($students)){
                $noticeUsers = [];
                $student_ids = [];
                foreach($students as $student){  
                        $noticeUsers[] = [
                              'notice_id'  => $insert_id,
                              'user_id'    => $student,
                              'usertypeID' => 3
                        ];
                        $feedUsers[] = [
                            'feed_id'    => $feedID,
                            'user_id'    => $student,
                            'usertypeID' => 3
                        ];
                        array_push($student_ids, $student . '3');
                }
                $this->notice_m->insert_batch_notice_user($noticeUsers);
                $this->feed_m->insert_batch_feed_user($feedUsers);	
            }
            $this->mobPushNotification($array,$student_ids);
        }
        if ($class) {
            $this->pushNotification($notice, $class);
        }
    }

    public function mobPushNotification($array,$student_ids)
    {

        $sall_users = serialize($student_ids);

        $this->mobile_job_m->insert_job([
            'name' => 'sendCourseNotification',
            'payload' => json_encode([
                'users' => $sall_users,
                'title' => $array['title'], // title is compulsary
                'message' => $array['notice']
            ]),
        ]);
    }

    public function pushNotification($title, $class = null)
    {
        $this->job_m->insert_job([
            'name' => 'sendCourseNotification',
            'payload' => json_encode([
                'class' => $class,
                'title' => $title, // title is compulsary
            ]),
        ]);
    }

    public function start()
    {
        $usertypeID = $this->session->userdata('usertypeID');
        if ($usertypeID == '1' || $usertypeID == '2') {
            if ($_POST) {
                $course = $this->courses_m->get_join_course($_POST['id']);

                $meetingID = $course->classes . "-" . $course->subject . "-" . $course->id;
                $bbb = new BigBlueButton();
                $meetingName = $course->classes . " " . $course->subject;
                $name = $this->session->userdata('name');
                $moderator_password = 'test';

                $createMeetingParams = new CreateMeetingParameters($meetingID, $meetingName);
                $createMeetingParams->setModeratorPassword($moderator_password);

                $response = $bbb->createMeeting($createMeetingParams);

                if ($response->getReturnCode() == 'SUCCESS' || $response->getReturnCode() == 'FAILED' && $response->getMessageKey() == 'idNotUnique') {
                    $joinMeetingParams = new JoinMeetingParameters($meetingID, $name, 'test');
                    $joinMeetingParams->setRedirect(true);
                    $url = $bbb->getJoinMeetingURL($joinMeetingParams);
                    echo $url;
                }
            }
        }
    }

    public function join()
    {
        $usertypeID = $this->session->userdata('usertypeID');
        if ($usertypeID == '3') {
            if ($_POST) {
                $course = $this->courses_m->get_join_course($_POST['id']);

                $meetingID = $course->classes . "-" . $course->subject . "-" . $course->id;
                $bbb = new BigBlueButton();
                $name = $this->session->userdata('name');
                $password = 'test';

                $joinMeetingParams = new JoinMeetingParameters($meetingID, $name, $password);
                $joinMeetingParams->setRedirect(true);
                $url = $bbb->getJoinMeetingURL($joinMeetingParams);
                echo $url;
            }
        }
    }

    public function checkMeeting($courseId)
    {
        $usertypeID = $this->session->userdata('usertypeID');
        if ($usertypeID == '3') {
            if ($courseId) {
                $course = $this->courses_m->get_join_course($courseId);
                if ($courseId) {
                    $bbb = new BigBlueButton();
                    $password = 'test';
                    $meetingID = $course->classes . "-" . $course->subject . "-" . $course->id;
                    $getMeetingInfoParams = new GetMeetingInfoParameters($meetingID, $password);
                    $response = $bbb->getMeetingInfo($getMeetingInfoParams);
                    if ($response->getReturnCode() == 'FAILED') {
                        showBadRequest(400, 'Meeting is failed.');
                    } else {
                        $meetingResponse = json_decode(json_encode($response->getRawXml()));
                        if ($meetingResponse && $meetingResponse->attendees) {
                            if ($meetingResponse->attendees->attendee) {
                                foreach ($meetingResponse->attendees->attendee as $attendee) {
                                    if ($attendee->fullName == $this->session->userdata('name')) {
                                        $this->doAttendance($course);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function doAttendance($course)
    {
        $date = 'a' . date('d');
        $where = [
            $date => 'P',
            'studentID' => $this->session->userdata('loginuserID'),
            'subjectID' => $course->subject_id,
            'classesID' => $course->class_id,
            'monthyear' => date('m-Y'),
        ];
        $isAlreadyPresent = $this->subjectattendance_m->get_single_sub_attendance($where);
        if (!$isAlreadyPresent) {
            $where[$date] = null;
            $existingStudentAttendance = $this->subjectattendance_m->get_single_sub_attendance($where);
            if ($existingStudentAttendance) {
                $this->subjectattendance_m->update_sub_attendance([
                    $date => 'P',
                ], $existingStudentAttendance->attendanceID);
            } else {
                $data = [
                    $date => 'P',
                    'monthyear' => date('m-Y'),
                    'subjectID' => $course->subject_id,
                    'classesID' => $course->class_id,
                    'studentID' => $this->session->userdata('loginuserID'),
                    'schoolyearID' => $this->session->userdata('defaultschoolyearID'),
                    'sectionID' => $this->session->userdata('sectionID'),
                    'usertype' => 'Student',
                    'userID' => $this->session->userdata('loginuserID'),
                ];
                $this->subjectattendance_m->insert_sub_attendance($data);
            }
        }
    }

    public function end()
    {
        $usertypeID = $this->session->userdata('usertypeID');
        if ($usertypeID == '1' || $usertypeID == '2') {
            if ($_POST) {
                $course = $this->courses_m->get_join_course($_POST['id']);

                $meetingID = $course->classes . "-" . $course->subject . "-" . $course->id;
                $bbb = new BigBlueButton();
                $moderator_password = 'test';

                $endMeetingParams = new EndMeetingParameters($meetingID, $moderator_password);
                $response = $bbb->endMeeting($endMeetingParams);
                echo $response->getMessage();
            }
        }
    }

    public function ajaxCheckIfMeetingRunning()
    {
        if ($_POST) {
            $course = $this->courses_m->get_join_course($_POST['id']);

            if ($course->published == 1) {
                $meetingID = $course->classes . "-" . $course->subject . "-" . $course->id;

                $meetingName = $this->session->userdata('name');
                $bbb = new BigBlueButton();

                $createMeetingParams = new CreateMeetingParameters($meetingID, $meetingName);
                $isMeetingRunning = $bbb->isMeetingRunning($createMeetingParams);
                echo $isMeetingRunning->isRunning();
            } else {
                echo 2;
            }
        }
    }

    public function getCourses()
    {
        $classesID = $this->input->post('classesID');
        $subjectID = $this->input->post('subjectID');

        $array = [];
        if ((int) $classesID && $classesID > 0) {
            $array['class_id'] = $classesID;
        }

        if ((int) $subjectID && $subjectID > 0) {
            $array['subject_id'] = $subjectID;
        }

        $courses = $this->courses_m->get_order_by_courses($array);
        $class = $this->classes_m->general_get_single_classes(['classesID' => $classesID]);
        $subject = $this->subject_m->general_get_single_subject(['subjectID' => $subjectID]);

        echo "<option value='0'>", $this->lang->line("select_course"), "</option>";
        foreach ($courses as $course) {
            echo "<option value=" . $course->id . ">" . $class->classes . ' ' . $subject->subject . "</option>";
        }
    }

    public function getUnits()
    {
        $subject_id = $this->input->post('subjectID');

        $units = $this->unit_m->get_units_by_subject_id($subject_id);
        echo "<option value='0'>", $this->lang->line("select_course"), "</option>";
        foreach ($units as $unit) {
            echo "<option value=" . $unit->id . ">" . $unit->unit_name . "</option>";
        }
    }

    public function getSubject()
    {
        $classesID = $this->input->post('classesID');
        if ((int) $classesID) {
            $subjects = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
            echo "<option value='0'>", $this->lang->line("select_subjects"), "</option>";
            foreach ($subjects as $subject) {
                echo "<option value=\"$subject->subjectID\">" . $subject->subject . "</option>";
            }
        }
    }

    public function assignmentStatus($assignmentId)
    {
        $record = $this->assignment_m->get_single_assignment(['assignmentID' => $assignmentId]);
        if ($record) {
            if ($this->assignment_m->update_assignment(['is_published' => !$record->is_published], $assignmentId) && $record->is_published == 0) {
                $subject = $this->subject_m->general_get_single_subject(['subjectID' => $record->subjectID]);
                $sectionID = $record->sectionID ? json_decode($record->sectionID) : '';
                $title = 'Assignment Published';
                $notice = "Assignment " . $record->title . " for subject " . $subject->subject . " has been published";

                $course_id = $record->course_id;
                $publish_status = false;
                if ($course_id != 0) {
                    $course = $this->courses_m->get_single_courses(['id' => $course_id]);
                    if ($course->published == 1) {
                        $publish_status = true;
                        if ($record->unit_id) {
                            $dbUnit = $this->unit_m->get_single_subject(['id' => $record->unit_id]);
                            if ($dbUnit->published == 1) {
                                $publish_status = true;
                            } else {
                                $publish_status = false;
                            }
                        }
                        if ($publish_status) {
                            if ($record->chapter_id) {
                                $dbChapter = $this->chapter_m->get_single_subject(['id' => $record->chapter_id]);
                                if ($dbChapter->published == 1) {
                                    $publish_status = true;
                                } else {
                                    $publish_status = false;
                                }
                            }
                        }
                    }
                }
                if ($publish_status) {
                    $this->notification($title, $notice, $record->classesID, $sectionID);
                }
            }
        } else {
            showBadRequest(400, 'Record not found for id ' . $assignmentId);
        }
    }

    public function classworkStatus($classworkId)
    {
        $record = $this->classwork_m->get_single_classwork(['classworkID' => $classworkId]);
        if ($record) {
            if ($this->classwork_m->update_classwork(['is_published' => !$record->is_published], $classworkId) && $record->is_published == 0) {
                $subject = $this->subject_m->general_get_single_subject(['subjectID' => $record->subjectID]);
                $sectionID = $record->sectionID ? json_decode($record->sectionID) : '';
                $title = 'Classwork Published';
                $notice = "Classwork " . $record->title . " for subject " . $subject->subject . " has been published";

                $course_id = $record->course_id;
                $publish_status = false;
                $course_id = $record->course_id;
                if ($course_id != 0) {
                    $course = $this->courses_m->get_single_courses(['id' => $course_id]);
                    if ($course->published == 1) {
                        $publish_status = true;
                        if ($record->unit_id) {
                            $dbUnit = $this->unit_m->get_single_subject(['id' => $record->unit_id]);
                            if ($dbUnit->published == 1) {
                                $publish_status = true;
                            } else {
                                $publish_status = false;
                            }
                        }
                        if ($publish_status) {
                            if ($record->chapter_id) {
                                $dbChapter = $this->chapter_m->get_single_subject(['id' => $record->chapter_id]);
                                if ($dbChapter->published == 1) {
                                    $publish_status = true;
                                } else {
                                    $publish_status = false;
                                }
                            }
                            if ($publish_status) {
                                if ($record->chapter_id) {
                                    $dbChapter = $this->chapter_m->get_single_subject(['id' => $record->chapter_id]);
                                    if ($dbChapter->published == 1) {
                                        $publish_status = true;
                                    } else {
                                        $publish_status = false;
                                    }
                                }
                            }
                        }
                    }
                    if ($publish_status) {
                        $this->notification($title, $notice, $record->classesID, $sectionID);
                    }
                }
            } else {
                showBadRequest(400, 'Record not found for id ' . $classworkId);
            }
        }
    }

    public function homeworkStatus($homeworkId)
    {
        $record = $this->homework_m->get_single_homework(['homeworkID' => $homeworkId]);
        if ($record) {
            if ($this->homework_m->update_homework(['is_published' => !$record->is_published], $homeworkId) && $record->is_published == 0) {
                $subject = $this->subject_m->general_get_single_subject(['subjectID' => $record->subjectID]);
                $sectionID = $record->sectionID ? json_decode($record->sectionID) : '';
                $title = 'Homework Published';
                $notice = "Homework " . $record->title . " for subject " . $subject->subject . " has been published";

                $course_id = $record->course_id;
                $publish_status = false;
                if ($course_id != 0) {
                    $course = $this->courses_m->get_single_courses(['id' => $course_id]);
                    if ($course->published == 1) {
                        $publish_status = true;
                        if ($record->unit_id) {
                            $dbUnit = $this->unit_m->get_single_subject(['id' => $record->unit_id]);
                            if ($dbUnit->published == 1) {
                                $publish_status = true;
                            } else {
                                $publish_status = false;
                            }
                        }
                        if ($publish_status) {
                            if ($record->chapter_id) {
                                $dbChapter = $this->chapter_m->get_single_subject(['id' => $record->chapter_id]);
                                if ($dbChapter->published == 1) {
                                    $publish_status = true;
                                } else {
                                    $publish_status = false;
                                }
                            }
                        }
                    }
                }
                if ($publish_status) {
                    $this->notification($title, $notice, $record->classesID, $sectionID);
                }
            }
        } else {
            showBadRequest(400, 'Record not found for id ' . $homeworkId);
        }
    }

    public function changeQuizQuestionOrder()
    {
        if (isset($_POST['positions'])) {

            $positions = json_decode($_POST['positions']);
            foreach ($positions as $index => $position) {

                $this->courses_m->updateQuizQuestionOrder($position->rowId, $index, $position->quizzId);
                echo $this->db->last_query() . '<br>';
            }
        } else {
            showBadRequest();
        }
    }

    public function changeOrder()
    {
        if (isset($_POST['positions'])) {
            $positions = json_decode($_POST['positions']);
            foreach ($positions as $index => $position) {
                switch ($position->type) {
                    case 'content':
                        $this->courses_m->updateContentOrder($position->rowId, $index);
                        echo $this->db->last_query() . '<br>';
                        break;
                    case 'attachment':
                        $this->courses_m->updateAttachmentOrder($position->rowId, $index);
                        echo $this->db->last_query() . '<br>';
                        break;
                    case 'link':
                        $this->courses_m->updateLinkOrder($position->rowId, $index);
                        echo $this->db->last_query() . '<br>';
                        break;
                    case 'quiz':
                        $this->courses_m->updateQuizOrder($position->rowId, $index);
                        echo $this->db->last_query() . '<br>';
                        break;
                    case 'classwork':
                        $this->classwork_m->update_classwork(['order' => $index], $position->rowId);
                        echo $this->db->last_query() . '<br>';
                        break;
                    case 'homework':
                        $this->homework_m->update_homework(['order' => $index], $position->rowId);
                        echo $this->db->last_query() . '<br>';
                        break;
                    case 'assignment':
                        $this->assignment_m->update_assignment(['order' => $index], $position->rowId);
                        echo $this->db->last_query() . '<br>';
                        break;
                    default:
                        return;
                }
            }
        } else {
            showBadRequest();
        }
    }

    public function Uploadimages()
    {

        /***************************************************
         * Only these origins are allowed to upload images *
         ***************************************************/
        $accepted_origins = array("http://localhost", "http://192.168.1.1", "https://erp.eduwise.com.np");

        /*********************************************
         * Change this line to set the upload folder *
         *********************************************/
        $imageFolder = "uploads/tinymceimage/";

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // same-origin requests won't set an origin. If the origin is set, it must be valid.
            if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            } else {
                header("HTTP/1.1 403 Origin Denied");
                return;
            }
        }

        // Don't attempt to process the upload on an OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header("Access-Control-Allow-Methods: POST, OPTIONS");
            return;
        }

        reset($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])) {
            /*
            If your script needs to receive cookies, set images_upload_credentials : true in
            the configuration and enable the following two headers.
             */
            // header('Access-Control-Allow-Credentials: true');
            // header('P3P: CP="There is no P3P policy."');

            // Sanitize input
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.1 400 Invalid file name.");
                return;
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return;
            }

            // Accept upload if there was no origin, or if it is an accepted origin
            $filetowrite = $imageFolder . $temp['name'];
            move_uploaded_file($temp['tmp_name'], $filetowrite);

            // Determine the base URL
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? "https://" : "http://";
            //$baseurl = $protocol . $_SERVER["HTTP_HOST"] . rtrim(dirname($_SERVER['REQUEST_URI']), "/") . "/";
            $baseurl = '../../';

            // Respond to the successful upload with JSON.
            // Use a location key to specify the path to the saved image resource.
            // { location : '/your/uploaded/image/file'}
            echo json_encode(array('location' => $baseurl . $filetowrite));
        } else {
            // Notify editor that the upload failed
            header("HTTP/1.1 500 Server Error");
        }
    }

    public function getContentByAjax($value = '')
    {
        $course = $this->input->post('course');
        $id = $this->input->post('id');
        $this->data['content'] = $this->coursecontent_m->get_content($id);
        $chapter_id = isset($this->data['content']->coursechapter_id) ? $this->data['content']->coursechapter_id : 0;
        $this->data['chapter'] = $this->chapter_m->get_chapter($chapter_id, true);
        $is_student_view = $this->input->post('is_student_view');

        $published_content = $this->data['content']->published;
        if ($published_content == 1 && $chapter_id != 0) {
            $this->data['view_url'] = '<a  class="btn btn-primary" target="_blank" href="' . base_url('courses/content/') . '' . $this->data['chapter']->id . '?course_id=' . $course . '&view_type='.$is_student_view.'#content' . $id . '">View Content</a>';
        }

        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);

        // log start
        $event = 'course content detail in popup';
        $remarks = 'visited content: '.$this->data['content']->content_title .' of  '.$this->data['course']->classes . ' - ' . $this->data['course']->subject. ' by '.$this->session->userdata("name");
        createCourseLog($event,$remarks);
        // log end

        echo $this->load->view('courses/contentview', $this->data, true);
        exit;
    }

    public function getAttachmentByAjax($value = '')
    {
        $course = $this->input->post('course');
        $id = $this->input->post('id');
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        $this->data['attachments'] = $this->coursefiles_m->get_attachment_by_id($id);
        $chapter_id = isset($this->data['attachments']->coursechapter_id) ? $this->data['attachments']->coursechapter_id : 0;
        $this->data['chapter'] = $this->chapter_m->get_chapter($chapter_id, true);

         // log start
         $event = 'courses attachment';
         $remarks = 'seen attachment: '.$this->data['attachments']->file_name. ' of '.$this->data['course']->classes . ' - '
                     . $this->data['course']->subject. ' chapter: '.$this->data['chapter']->chapter_name.' by '
                    .$this->session->userdata("name");
         createCourseLog($event,$remarks);
         // log end

        echo $this->load->view('courses/attachmentview', $this->data, true);
        exit;
    }

    public function getLinkByAjax(Type $var = null)
    {
        $course = $this->input->post('course');
        $id = $this->input->post('id');
        $html  = '';
        $this->data['link'] = $this->courselink_m->get($id);
        $chapter_id = isset($this->data['link']->coursechapter_id) ? $this->data['link']->coursechapter_id : 0;
        $this->data['chapter'] = $this->chapter_m->get_chapter($chapter_id, true);

        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);

        $url = $this->data['link']->courselink;
        $this->data['url1'] = (strncasecmp('http://', $url, 7) && strncasecmp('https://', $url, 8) ? 'http://' : '') . $url;

        if ($this->data['link']->type == 'Youtube') {
            $this->data['url1'] = getEmbedUrl($url);
        }

         // log start
         $event = 'courses link';
         $remarks = 'seen course link: '. $this->data['link']->courselink. ' of '.$this->data['course']->classes . ' - '
                     . $this->data['course']->subject. ' chapter: '.$this->data['chapter']->chapter_name.' by '
                    .$this->session->userdata("name");
         createCourseLog($event,$remarks);
         // log end

        echo $this->load->view('courses/linkview', $this->data, true);
        exit;
    }

    public function getAssignmentByAjax($value = '')
    {
        $course = $this->input->post('course');
        $id = $this->input->post('id');
        $url = $this->input->post('set');
        $html = $file = $btn = '';
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        $classesID = $this->data['course']->class_id;
        $this->data['subjectID'] = $this->data['course']->subject_id;

        $this->data['usertypeID'] = $this->session->userdata('usertypeID');

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $this->data['assignment'] = $this->assignment_m->get_single_assignment(array('assignmentID' => $id, 'schoolyearID' => $schoolyearID));
        $this->data['chapter'] = $this->chapter_m->get_chapter($this->data['assignment']->chapter_id, true);
        $this->data['assignment_medias'] = $this->assignment_media_m->get_order_by_assignment_media(['assignmentID' => $id]);


         // log start
         $event = 'courses assignment';
         $remarks = 'seen assignment: '.$this->data['assignment']->title. ' of '.$this->data['course']->classes . ' - '
                     . $this->data['course']->subject. ' chapter: '.$this->data['chapter']->chapter_name.' by '
                    .$this->session->userdata("name");
         createCourseLog($event,$remarks);
         // log end

        echo $this->load->view('courses/assignmentview', $this->data, true);
        exit;
    }

    public function getHomeworkByAjax(Type $var = null)
    {
        $course = $this->input->post('course');
        $id = $this->input->post('id');
        $url = $this->input->post('set');

        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        $classesID = $this->data['course']->class_id;
        $this->data['subjectID'] = $this->data['course']->subject_id;

        $this->data['usertypeID'] = $this->session->userdata('usertypeID');

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $this->data['homework'] = $this->homework_m->get_single_homework(array('homeworkID' => $id, 'schoolyearID' => $schoolyearID));
        $this->data['unit'] = $this->unit_m->get_units($this->data['homework']->unit_id);
        $this->data['chapter'] = $this->chapter_m->get_chapter($this->data['homework']->chapter_id, true);
        $this->data['homework_medias'] = $this->homework_media_m->get_order_by_homework_media(['homeworkID' => $id]);

         // log start
         $event = 'courses homework';
         $remarks = 'seen homework: '.$this->data['homework']->title. ' of '.$this->data['course']->classes . ' - '
                     . $this->data['course']->subject. ' chapter: '.$this->data['chapter']->chapter_name.' by '
                    .$this->session->userdata("name");
         createCourseLog($event,$remarks);
         // log end

        echo $this->load->view('courses/homeworkview', $this->data, true);
        exit;
    }

    public function getClassworkByAjax(Type $var = null)
    {
        $course = $this->input->post('course');
        $id = $this->input->post('id');
        $url = $this->input->post('set');

        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        $classesID = $this->data['course']->class_id;
        $this->data['subjectID'] = $this->data['course']->subject_id;

        $this->data['usertypeID'] = $this->session->userdata('usertypeID');

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $this->data['classwork'] = $this->classwork_m->get_single_classwork(array('classworkID' => $id, 'schoolyearID' => $schoolyearID));
        $this->data['unit'] = $this->unit_m->get_units($this->data['classwork']->unit_id);
        $this->data['chapter'] = $this->chapter_m->get_chapter($this->data['classwork']->chapter_id, true);
        $this->data['classwork_medias'] = $this->classwork_media_m->get_order_by_classwork_media(['classworkID' => $id]);


        // log start
        $event = 'courses classwork';
        $remarks = 'seen classwork: '.$this->data['classwork']->title. ' of '.$this->data['course']->classes . ' - '
                    . $this->data['course']->subject. ' chapter: '.$this->data['chapter']->chapter_name.' by '
                   .$this->session->userdata("name");
        createCourseLog($event,$remarks);
        // log end


        echo $this->load->view('courses/classworkview', $this->data, true);
        exit;
    }

    public function getQuizByAjax(Type $var = null)
    {
        $course = $this->input->post('course');
        $id = $this->input->post('id');
        $this->data['url'] = $this->input->post('set');
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['quiz'] = $this->coursequiz_m->get($id);
        $objects = $this->question_bank_m->get_classes_from_chapter($this->data['quiz']->coursechapter_id);
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);

        $this->data['chapter'] = $this->chapter_m->get_chapter($this->data['quiz']->coursechapter_id, true);
        // $this->data['unit'] = $this->unit_m->get_units($this->data['chapter']->unit_id);
        $this->data['questions'] = $this->courses_m->get_order_by_quiz_question($id);


        // log start
        $event = 'courses quizzes';
        $remarks = 'visited quiz: '.$this->data['quiz']->quiz_name .' of '.$this->data['course']->classes . ' - ' . $this->data['course']->subject. ' by '.$this->session->userdata("name");
        createCourseLog($event,$remarks);
        // log end

        echo $this->load->view('courses/quizview', $this->data, true);
        exit;
    }
}
