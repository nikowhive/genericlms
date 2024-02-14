<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Assignment extends Admin_Controller
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
        $this->load->model("unit_m");
        $this->load->model("notice_m");
        $this->load->model("section_m");
        $this->load->model("subject_m");
        $this->load->model("student_m");
        $this->load->model("courses_m");
        $this->load->model("chapter_m");
        $this->load->model("mobile_job_m");
        $this->load->model("assignment_m");
        $this->load->model("studentrelation_m");
        $this->load->model("assignmentanswer_m");
        $this->load->model("assignment_media_m");
        $this->load->model("assignment_answer_media_m");

        $language = $this->session->userdata('lang');
        $this->lang->load('assignment', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("assignment_title"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("assignment_description"),
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'classesID',
                'label' => $this->lang->line("assignment_classes"),
                'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_classes',
            ),
            array(
                'field' => 'deadlinedate',
                'label' => $this->lang->line("assignment_deadlinedate"),
                'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid|callback_pastdate_check',
            ),
            array(
                'field' => 'subjectID',
                'label' => $this->lang->line("assignment_subject"),
                'rules' => 'trim|required|numeric|max_length[11]|xss_clean|callback_unique_subject',
            ),
            array(
                'field' => 'sectionID',
                'label' => $this->lang->line("assignment_section"),
                'rules' => 'xss_clean|callback_unique_section',
            ),
            array(
                'field' => 'photos[]',
                'label' => $this->lang->line("assignment_file"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_multiplephotoupload'
            )
        );
        return $rules;
    }

    protected function rules_fileupload()
    {
        $rules = array(
            array(
                'field' => 'photos[]',
                'label' => $this->lang->line("assignment_file"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_multiplephotoupload'
            )
        );
        return $rules;
    }
    protected function rules_content()
    {
        $rules = array(
            array(
                'field' => 'content',
                'label' => $this->lang->line("assignment_content"),
                'rules' => 'trim|xss_clean|required',
            ),
        );
        return $rules;
    }

    public function fileuploadans()
    {
        $new_file           = "";
        $original_file_name = '';
        if ($_FILES["file"]['name'] != "") {
            $file_name          = $_FILES["file"]['name'];
            $original_file_name = $file_name;
            $random             = random19();
            $makeRandom         = hash('md5', $random . $this->input->post('title') . config_item("encryption_key"));
            $file_name_rename   = $makeRandom;
            $explode            = explode('.', $file_name);
            if (customCompute($explode) >= 2) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv|XLS|XLSX|TXT|PPT|CSV";
                $config['file_name']     = $new_file;
                $_FILES['attach']['tmp_name'] = $_FILES['file']['tmp_name'];
                $image_info = getimagesize($_FILES['file']['tmp_name'][$i]);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("file")) {
                    $this->form_validation->set_message("fileuploadans", $this->upload->display_errors());
                    return false;
                } else {
                    $fileData = $this->upload->data();
                    if($image_width > 1800 || $image_height > 1800){
                        resizeImage($fileData['file_name'],$uploadPath);
                     } 
                    $this->upload_data['file']                       = $this->upload->data();
                    $this->upload_data['file']['original_file_name'] = $original_file_name;
                    return true;
                }
            } else {
                $this->form_validation->set_message("fileuploadans", "Invalid file");
                return false;
            }
        } else {
            $this->form_validation->set_message("fileuploadans", "The %s field is required");
            return false;
        }
    }

    public function fileupload()
    {
        $id         = htmlentities(escapeString($this->uri->segment(3)));
        $assignment = [];
        if ((int) $id) {
            $assignment = $this->assignment_m->get_single_assignment(array('assignmentID' => $id));
        }

        $new_file           = "";
        $original_file_name = '';
        if ($_FILES["file"]['name'] != "") {
            $file_name          = $_FILES["file"]['name'];
            $original_file_name = $file_name;
            $random             = random19();
            $makeRandom         = hash('md5', $random . $this->input->post('title') . config_item("encryption_key"));
            $file_name_rename   = $makeRandom;
            $explode            = explode('.', $file_name);
            if (customCompute($explode) >= 2) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name']     = $new_file;
                $_FILES['attach']['tmp_name'] = $_FILES['file']['tmp_name'];
                $image_info = getimagesize($_FILES['file']['tmp_name'][$i]);
				$image_width = $image_info[0];
				$image_height = $image_info[1];

                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("file")) {
                    $this->form_validation->set_message("fileupload", $this->upload->display_errors());
                    return false;
                } else {
                    $fileData = $this->upload->data();
                    if($image_width > 1800 || $image_height > 1800){
                        resizeImage($fileData['file_name'],$uploadPath);
                     } 
                    $this->upload_data['file']                       = $this->upload->data();
                    $this->upload_data['file']['original_file_name'] = $original_file_name;
                    return true;
                }
            } else {
                $this->form_validation->set_message("fileupload", "Invalid file");
                return false;
            }
        } else {
            if (customCompute($assignment)) {
                $this->upload_data['file']                       = array('file_name' => $assignment->file);
                $this->upload_data['file']['original_file_name'] = $assignment->originalfile;
                return true;
            } else {
                $this->upload_data['file']                       = array('file_name' => $new_file);
                $this->upload_data['file']['original_file_name'] = $original_file_name;
                return true;
            }
        }
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js'  => array(
                'assets/select2/select2.js',
            ),
        );

        $schoolyearID             = $this->session->userdata('defaultschoolyearID');
        $this->data['student']    = [];
        $this->data['opsubjects'] = [];
        if ($this->session->userdata('usertypeID') == 3) {
            $classesID                = (int) $this->data['myclass'];
            $loginuserID              = $this->session->userdata('loginuserID');
            $this->data['opsubjects'] = pluck($this->subject_m->get_order_by_subject(['classesID' => $classesID, 'type' => 0]), 'subjectID', 'subjectID');
            $this->data['student']    = $this->studentrelation_m->get_single_studentrelation(['srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID]);
        } else {
            $classesID = htmlentities(escapeString($this->uri->segment(3)));
        }

        $this->data['classes'] = $this->classes_m->get_classes();
        $fetchClasses          = pluck($this->data['classes'], 'classesID', 'classesID');
        if ((int) $classesID && isset($fetchClasses[$classesID])) {
            $this->data['set']         = $classesID;
            $this->data['sections']    = pluck($this->section_m->general_get_order_by_section(array('classesID' => $classesID)), 'section', 'sectionID');
            $this->data['assignments'] = $this->assignment_m->join_get_assignment($classesID, $schoolyearID);

            $this->data["subview"] = "assignment/index";
            $this->load->view('_layout_course', $this->data);
        } else {
            $this->data['set']         = 0;
            $this->data['sections']    = [];
            $this->data['assignments'] = [];

            $this->data["subview"] = "assignment/index";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function add()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
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


            $classesID = 0;

            if (isset($_GET['course'])) {
                $course                  = $_GET['course'];
                $this->data['course']    = $this->courses_m->get_all_join_courses_based_on_course_id($course);
                $classesID               = $this->data['course']->class_id;
                $this->data['subjectID'] = $this->data['course']->subject_id;
                $this->data['units']     = $this->unit_m->get_units_by_subject_id($this->data['subjectID']);

                if (isset($_GET['unit'])) {
                    $this->data['chapters']  = $this->chapter_m->get_chapter_from_unit_id($_GET['unit']);
                }
            }
            $this->data['usertypeID'] = $this->session->userdata('usertypeID');

            $this->data['classes'] = $this->classes_m->get_classes();

            if (isset($_POST['course'])) {
                $classesID = $this->input->post("classesID");
            }

            if ($classesID != 0) {
                $this->data['classesID'] = $classesID;
                $this->data['subjects']  = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
                $this->data['sections']  = $this->section_m->general_get_order_by_section(array("classesID" => $classesID));
            } else {
                $this->data['classesID'] = 0;
                $this->data['subjects']  = [];
                $this->data['sections']  = [];
                $this->data['subjectID'] = 0;
            }

            if ($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $this->data["subview"] = "assignment/add";
                    $this->load->view('_layout_course', $this->data);
                } else {
                    $array = array(
                        "title"            => $this->input->post("title"),
                        "description"      => $this->input->post("description"),
                        "deadlinedate"     => date("Y-m-d", strtotime($this->input->post("deadlinedate"))),
                        'subjectID'        => $this->input->post('subjectID'),
                        "usertypeID"       => $this->session->userdata('usertypeID'),
                        "userID"           => $this->session->userdata('loginuserID'),
                        "classesID"        => $this->input->post("classesID"),
                        "schoolyearID"     => $this->session->userdata('defaultschoolyearID'),
                        "unit_id"          => $this->input->post('unitId'),
                        "chapter_id"       => $this->input->post('chapterId'),
                        "course_id"       => $course,
                        'assignusertypeID' => 0,
                        'assignuserID'     => 0,
                    );

                    // $array['originalfile'] = $this->upload_data['file']['original_file_name'];
                    // $array['file']         = $this->upload_data['file']['file_name'];
                    $array['sectionID']    = json_encode($this->input->post('sectionID'));
                    $link                  = isset($_GET['link']) ? $_GET['link'] : '';

                    $subject = $this->subject_m->general_get_single_subject(['subjectID' => $this->input->post('subjectID')])->subject;
                    $class   = $this->classes_m->general_get_single_classes(['classesID' => $this->input->post('classesID')])->classes;
                    $this->assignment_m->insert_assignment($array);
                    $assignmentID = $this->db->insert_id();

                    if ($assignmentID) {
                        $photos = $this->upload_data['files'];
                        if (customCompute($photos)) {
                            foreach ($photos as $key => $photo) {
                                $photos[$key]['assignmentID'] = $assignmentID;
                            }

                            $this->assignment_media_m->insert_batch_assignment_media($photos);
                        }
                    }

                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));

                    if ($course == '') {
                        redirect(base_url("assignment/index"));
                    } else {
                        if ($link == 'assignment') {
                            redirect(base_url("courses/assignment/" . $course));
                        } else {
                            redirect(base_url("courses/show/" . $course));
                        }
                    }
                }
            } else {
                $this->data["subview"] = "assignment/add";
                $this->load->view('_layout_course', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function pushNotification($title, $class = null)
    {
        $this->job_m->insert_job([
            'name'    => 'sendCourseNotification',
            'payload' => json_encode([
                'class' => $class,
                'title' => $title, // title is compulsary
            ]),
        ]);
    }

    public function edit()
    {
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

        if (isset($_GET['course'])) {
            $course                  = $_GET['course'];
            $link                    = isset($_GET['link']) ? $_GET['link'] : '';
            $this->data['course']    = $this->courses_m->get_all_join_courses_based_on_course_id($course);
            $classesID               = $this->data['course']->class_id;
            $this->data['subjectID'] = $this->data['course']->subject_id;
            $this->data['units']     = $this->unit_m->get_units_by_subject_id($this->data['subjectID']);
            $this->data['chapters']  = $this->chapter_m->get_chapter_from_subject_id($this->data['subjectID']);
        }

        $this->data['usertypeID'] = $this->session->userdata('usertypeID');

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $id           = htmlentities(escapeString($this->uri->segment(3)));
        $url          = htmlentities(escapeString($this->uri->segment(4)));
        if ((int) $id && (int) $url) {
            if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
                $this->data['classes'] = $this->classes_m->get_classes();

                $fetchClasses = pluck($this->data['classes'], 'classesID', 'classesID');
                if (isset($fetchClasses[$url])) {
                    $this->data['assignment'] = $this->assignment_m->get_single_assignment(array('assignmentID' => $id, 'schoolyearID' => $schoolyearID));
                    $this->data['assignment_medias'] = $this->assignment_media_m->get_order_by_assignment_media(['assignmentID' => $id]);

                    if ($this->data['assignment']) {
                        $this->data['sectionID'] = json_decode($this->data['assignment']->sectionID);

                        if ($this->input->post('classesID')) {
                            $classesID = $this->input->post('classesID');
                        } else {
                            $classesID = $this->data['assignment']->classesID;
                        }

                        $this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
                        $this->data['sections'] = $this->section_m->general_get_order_by_section(array("classesID" => $classesID));
                        $this->data['unit']     = $this->unit_m->get_units($this->data['assignment']->unit_id);
                        $this->data['chapter']  = $this->chapter_m->get_chapter($this->data['assignment']->chapter_id, true);

                        if ($_POST) {
                            $rules = $this->rules();
                            $this->form_validation->set_rules($rules);
                            if ($this->form_validation->run() == false) {
                                $this->data["subview"] = "assignment/edit";
                                $this->load->view('_layout_course', $this->data);
                            } else {
                                $array = array(
                                    "title"            => $this->input->post("title"),
                                    "description"      => $this->input->post("description"),
                                    "deadlinedate"     => date("Y-m-d", strtotime($this->input->post("deadlinedate"))),
                                    'subjectID'        => $this->input->post('subjectID'),
                                    "usertypeID"       => $this->session->userdata('usertypeID'),
                                    "userID"           => $this->session->userdata('loginuserID'),
                                    "classesID"        => $this->input->post("classesID"),
                                    'assignusertypeID' => 0,
                                    'assignuserID'     => 0,
                                    "unit_id"          => $this->input->post("unitId"),
                                    "chapter_id"       => $this->input->post("chapterId"),
                                );

                                // $array['originalfile'] = $this->upload_data['file']['original_file_name'];
                                // $array['file']         = $this->upload_data['file']['file_name'];

                                $array['sectionID'] = json_encode($this->input->post('sectionID'));

                                $this->db->trans_start();
                                $this->assignment_m->update_assignment($array, $id);
                                $photos = $this->upload_data['files'];
                                if (customCompute($photos)) {
                                    foreach ($photos as $key => $photo) {
                                        $photos[$key]['assignmentID'] = $id;
                                    }
                                    $this->assignment_media_m->insert_batch_assignment_media($photos);
                                }

                                $this->db->trans_complete();
                                if ($this->db->trans_status() == TRUE) {
                                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                                } else {
                                    $this->session->set_flashdata('error', $this->lang->line('menu_error'));
                                }

                                if ($course == '') {
                                    redirect(base_url("assignment/index/$url"));
                                } else {
                                    if ($link == 'assignment') {
                                        redirect(base_url("courses/assignment/" . $course));
                                    } else {
                                        redirect(base_url("courses/show/" . $course));
                                    }
                                }
                            }
                        } else {
                            $this->data["subview"] = "assignment/edit";
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
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_course', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }


    public function deleteAnswerImage()
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
            $delete = $this->assignment_answer_media_m->delete_assignment_answer_media($id);
            $retArray['status'] = true;
            $retArray['message'] = $this->lang->line('menu_success');
            echo json_encode($retArray);
            exit;
        }
    }

    public function deleteImage()
    {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
            $delete = $this->assignment_media_m->delete_assignment_media($id);
            $retArray['status'] = true;
            $retArray['message'] = $this->lang->line('menu_success');
            echo json_encode($retArray);
            exit;
        }
    }

    public function delete()
    {

        $course       = isset($_GET['course']) ? $_GET['course'] : '';
        $link         = isset($_GET['link']) ? $_GET['link'] : '';
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $id           = htmlentities(escapeString($this->uri->segment(3)));
        $url          = htmlentities(escapeString($this->uri->segment(4)));
        if ((int) $id && (int) $url) {
            if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
                $fetchClasses = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
                if (isset($fetchClasses[$url])) {
                    $assignment = $this->assignment_m->get_single_assignment(array('assignmentID' => $id, 'classesID' => $url, 'schoolyearID' => $schoolyearID));
                    if (customCompute($assignment)) {
                        if (config_item('demo') == false) {
                            if ($assignment->file != '') {
                                if (file_exists(FCPATH . 'uploads/images/' . $assignment->file)) {
                                    unlink(FCPATH . 'uploads/images/' . $assignment->file);
                                }
                            }
                        }
                        $this->assignment_m->delete_assignment($id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        if ($course == '') {
                            redirect(base_url("assignment/index/$url"));
                        } else {
                            if ($link == 'assignment') {
                                redirect(base_url("courses/assignment/" . $course));
                            } else {
                                redirect(base_url("courses/show/" . $course));
                            }
                        }
                    } else {
                        redirect(base_url("assignment/index"));
                    }
                } else {
                    redirect(base_url("assignment/index"));
                }
            } else {
                redirect(base_url("assignment/index"));
            }
        } else {
            redirect(base_url("assignment/index"));
        }
    }

    public function view()
    {
        $assignmentID = htmlentities(escapeString($this->uri->segment(3)));
        $classesID    = htmlentities(escapeString($this->uri->segment(4)));
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $usertypeID   = $this->session->userdata('usertypeID');

        if (isset($_GET['course'])) {
            $course               = $_GET['course'];
            $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        }

        $this->data['usertypeID'] = $this->session->userdata('usertypeID');

        if ((int) $assignmentID && (int) ($classesID)) {

            $fetchClasses = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
            if (isset($fetchClasses[$classesID])) {
                $this->data['viewclass'] = $classesID;
                $assignment              = $this->assignment_m->get_single_assignment(array('assignmentID' => $assignmentID, 'classesID' => $classesID, 'schoolyearID' => $schoolyearID));

                $student     = [];
                $opsubjects  = [];
                $loginuserID = null;
                if ($usertypeID == 3) {
                    $classesID   = (int) $this->data['myclass'];
                    $loginuserID = $this->session->userdata('loginuserID');
                    $opsubjects  = pluck($this->subject_m->get_order_by_subject(['classesID' => $classesID, 'type' => 0]), 'subjectID', 'subjectID');
                    $student     = $this->studentrelation_m->get_single_studentrelation(['srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID]);
                }

                $f = true;
                if (($usertypeID == 3 || $usertypeID == 4) && customCompute($student) && in_array($assignment->subjectID, $opsubjects) && ($student->sroptionalsubjectID != $assignment->subjectID)) {
                    $f = false;
                }

                // log start
                $event = 'assignment submission list';
                $remarks = 'visited assignment: '.$assignment->title .' submission of  '.$this->data['course']->classes . ' - ' . $this->data['course']->subject. ' by '.$this->session->userdata("name");
                createCourseLog($event,$remarks);
                // log end

                if (customCompute($assignment) && $f) {
                    $this->data['assignmentanswers'] = $this->assignmentanswer_m->join_get_assignmentanswer($assignmentID, $schoolyearID, $loginuserID);
                    $this->data['assignment_answer_medias'] = pluck_multi_array($this->assignment_answer_media_m->getAllAsignmentAnswerFiles(), 'obj', 'assignmentanswerID');
                    $this->data["subview"]           = "assignment/view";
                    $this->load->view('_layout_course', $this->data);
                } else {
                    $this->data["subview"] = "error";
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

    public function assignmentanswer()
    {
        $id           = htmlentities(escapeString($this->uri->segment(3)));
        $url          = htmlentities(escapeString($this->uri->segment(4)));
        $usertypeID   = $this->session->userdata('usertypeID');
        $userID       = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $this->data['usertypeID'] = $usertypeID;
        if (isset($_GET['course'])) {
            $course               = $_GET['course'];
            $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        }
        if ($usertypeID == 3) {
            if ((int) $id && (int) ($url)) {
                $fetchClasses = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
                if (isset($fetchClasses[$url])) {
                    if ($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) {
                        $assignment = $this->assignment_m->get_single_assignment(array('assignmentID' => $id, 'schoolyearID' => $schoolyearID));

                        $student    = [];
                        $opsubjects = [];
                        if ($usertypeID == 3) {
                            $classesID  = (int) $this->data['myclass'];
                            $opsubjects = pluck($this->subject_m->get_order_by_subject(['classesID' => $url, 'type' => 0]), 'subjectID', 'subjectID');
                            $student    = $this->studentrelation_m->get_single_studentrelation(['srstudentID' => $userID, 'srschoolyearID' => $schoolyearID]);
                        }

                        $f = true;
                        if (($usertypeID == 3) && customCompute($student) && in_array($assignment->subjectID, $opsubjects) && ($student->sroptionalsubjectID != $assignment->subjectID)) {
                            $f = false;
                        }

                        if (customCompute($assignment) && $f) {
                            if (strtotime($assignment->deadlinedate) >= strtotime(date('Y-m-d'))) {
                                $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('uploaderID' => $userID, 'uploadertypeID' => $usertypeID, 'schoolyearID' => $schoolyearID, 'assignmentID' => $id));
                                $this->data['content'] = $assignmentanswer ? $assignmentanswer->content : '';
                                if ($assignmentanswer) {
                                    $this->data['assignment_answer_medias'] = $this->assignment_answer_media_m->get_order_by_assignment_answer_media(['assignmentanswerID' => $assignmentanswer->assignmentanswerID]);
                                } else {
                                    $this->data['assignment_answer_medias'] = [];
                                }

                                if ($_POST) {
                                    $content = $this->input->post('content');
                                    if ($content and $_FILES['photos']['name'][0] != "") {
                                        $rules = $this->rules_fileupload();
                                    } else if ($_FILES["photos"]['name'][0] != "") {
                                        $rules = $this->rules_fileupload();
                                    } else if ($content) {
                                        $rules = $this->rules_content();
                                    } else {
                                        $rules = $this->rules_content();
                                    }

                                    $this->form_validation->set_rules($rules);
                                    if ($this->form_validation->run() == false) {
                                        $this->data["subview"] = "assignment/addanswer";
                                        $this->load->view('_layout_course', $this->data);
                                    } else {

                                        $array['content']            = $this->input->post('content');
                                        $array['answerfileoriginal'] = '';
                                        $array['answerfile']         = '';
                                        $array['assignmentID']       = $id;
                                        $array['schoolyearID']       = $this->data['siteinfos']->school_year;
                                        $array['uploaderID']         = $this->session->userdata('loginuserID');
                                        $array['uploadertypeID']     = $usertypeID;
                                        $array['answerdate']         = date('Y-m-d');
                                        $array['status']             = 'pending';


                                        if (customCompute($assignmentanswer)) {
                                            $this->assignmentanswer_m->update_assignmentanswer($array, $assignmentanswer->assignmentanswerID);

                                            $photos = $this->upload_data['files'];
                                            if (customCompute($photos)) {
                                                foreach ($photos as $key => $photo) {
                                                    $photos[$key]['assignmentanswerID'] = $assignmentanswer->assignmentanswerID;
                                                }

                                                $this->assignment_answer_media_m->insert_batch_assignment_answer_media($photos);
                                            }

                                            // log start
                                            $event = 'assignment answer update';
                                            $remarks = 'assignment: '.$assignment->title.' answer submitted by '.$this->session->userdata("name");
                                            createCourseLog($event,$remarks);
                                            // log end

                                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                                            if ($course == '') {
                                                redirect(base_url("assignment/index/$url"));
                                            } else {
                                                redirect(base_url("courses/assignment/" . $course));
                                            }
                                        } else {
                                            $this->assignmentanswer_m->insert_assignmentanswer($array);
                                            $assignmentanswerID = $this->db->insert_id();

                                            if ($assignmentanswerID) {


                                                // log start
                                                $event = 'assignment answer submission';
                                                $remarks = 'assignment: '.$assignment->title.' answer updated by '.$this->session->userdata("name");
                                                createCourseLog($event,$remarks);
                                                // log end

                                                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                                                $photos = $this->upload_data['files'];
                                                if (customCompute($photos)) {
                                                    foreach ($photos as $key => $photo) {
                                                        $photos[$key]['assignmentanswerID'] = $assignmentanswerID;
                                                    }

                                                    $this->assignment_answer_media_m->insert_batch_assignment_answer_media($photos);
                                                }

                                                $userID = $assignment->userID;
                                                $usertypeID = $assignment->usertypeID;
                                                $title = 'Assignment submission';
                                                $notice = 'Assignment: ' . $assignment->title . ' is submitted by ' . $student->srname;

                                                $u = array($userID . $usertypeID);

                                                $users = serialize($u);

                                                $array = array(
                                                    "title"             => $title,
                                                    "notice"            => $notice,
                                                    "schoolyearID"      => $schoolyearID,
                                                    "users"             => $users,
                                                    "date"              => date('Y-m-d'),
                                                    "create_date"       => date('Y-m-d H:i:s'),
                                                    "create_userID"     => $this->session->userdata('loginuserID'),
                                                    "create_usertypeID" => $this->session->userdata('usertypeID'),
                                                    "show_to_creator"   => 0
                                                );
                                                $this->notice_m->insert_notice($array);
                                                $insert_id = $this->db->insert_id();

                                                if ($insert_id) {
                                                    $this->insertFeed($insert_id,$userID,$usertypeID);
                                                    $this->addtojob($title, $notice, $users);
                                                }

                                            } else {
                                                $this->session->set_flashdata('error', 'Something went wrong.');
                                            }

                                            if ($course == '') {
                                                redirect(base_url("assignment/index/$url"));
                                            } else {
                                                redirect(base_url("courses/assignment/" . $course));
                                            }
                                        }
                                    }
                                } else {
                                    $this->data["subview"] = "assignment/addanswer";
                                    $this->load->view('_layout_course', $this->data);
                                }
                            } else {
                                $this->session->set_flashdata('error', 'Submition close');
                                if ($course == '') {
                                    redirect(base_url("assignment/index"));
                                } else {
                                    redirect(base_url("courses/assignment/" . $course));
                                }
                            }
                        } else {
                            $this->data["subview"] = "error";
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
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_course', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function viewAssignmentAnswerByAjax()
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $id = $this->input->post('id');
        $this->data['assignmentanswer'] = $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('assignmentanswerID' => $id));
        $this->data['assignment_answer_medias'] = $this->assignment_answer_media_m->get_order_by_assignment_answer_media(['assignmentanswerID' => $assignmentanswer->assignmentanswerID]);
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        if ($this->session->userdata('usertypeID') == 2) {
            if ($assignmentanswer->status == 'pending') {

                $assignment = $this->assignment_m->get_single_assignment(array('assignmentID' => $assignmentanswer->assignmentID, 'schoolyearID' => $schoolyearID));

                $data = [
                    'status' => 'viewed'
                ];
                if ($this->assignmentanswer_m->update_assignmentanswer($data, $id)) {
                    $userID = $assignmentanswer->uploaderID;
                    $usertypeID = $assignmentanswer->uploadertypeID;
                    $title = 'Assignment submission seen.';
                    $notice = 'Your assignment: ' . $assignment->title . ' submission has been seen';

                    $u = array($userID . $usertypeID);
                    $users = serialize($u);
                    $array = array(
                        "title"             => $title,
                        "notice"            => $notice,
                        "schoolyearID"      => $schoolyearID,
                        "users"             => $users,
                        "date"              => date('Y-m-d'),
                        "create_date"       => date('Y-m-d H:i:s'),
                        "create_userID"     => $this->session->userdata('loginuserID'),
                        "create_usertypeID" => $this->session->userdata('usertypeID'),
                        "show_to_creator"   => 0
                    );
                    $this->notice_m->insert_notice($array);
                    $insert_id = $this->db->insert_id();

                    if ($insert_id) {
                        $this->insertFeed($insert_id,$userID,$usertypeID);
                        $this->addtojob($title, $notice, $users);
                    }
                }
            }
        }

        $assignment = $this->assignment_m->get_single_assignment(array('assignmentID' => $assignmentanswer->assignmentID, 'schoolyearID' => $schoolyearID));

        // log start
            $event = 'assignment submission files check';
            $remarks = 'seen assignment: '.$assignment->title .' submission files by '.$this->session->userdata("name");
            createCourseLog($event,$remarks);
        // log end

        echo $this->load->view('courses/assignmentanswerview', $this->data, true);
        exit;
    }

    public function loadRemarkForm()
    {

        $id = $this->input->post('id');
        $this->data['assignmentanswer'] = $this->assignmentanswer_m->get_single_assignmentanswer(array('assignmentanswerID' => $id));
        echo $this->load->view('assignment/addAnswerRemark', $this->data, true);
        exit;
    }

    public function addRemarks()
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $id = $this->input->post('assignmentanswerID');
        $comment = $this->input->post('comment');
        $data = [
            'remarks' => $comment
        ];
        if ($this->assignmentanswer_m->update_assignmentanswer($data, $id)) {
            $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('assignmentanswerID' => $id));
            $assignment = $this->assignment_m->get_single_assignment(['assignmentID' => $assignmentanswer->assignmentID]);

            $title = 'Assignment submission remark.';
            $notice = 'Remarks has been added on your assignment: ' . $assignment->title . ' by teacher.';
            $userID = $assignmentanswer->uploaderID;
            $usertypeID = $assignmentanswer->uploadertypeID;
            $u = [$userID . $usertypeID];

            $users = serialize($u);

            $array = array(
                "title"             => $title,
                "notice"            => $notice,
                "schoolyearID"      => $schoolyearID,
                "users"             => $users,
                "date"              => date('Y-m-d'),
                "create_date"       => date('Y-m-d H:i:s'),
                "create_userID"     => $this->session->userdata('loginuserID'),
                "create_usertypeID" => $this->session->userdata('usertypeID'),
                "show_to_creator"   => 0
            );
            $this->notice_m->insert_notice($array);
            $insert_id = $this->db->insert_id();

            if ($insert_id) {
                $this->insertFeed($insert_id,$userID,$usertypeID);
                $this->addtojob($title, $notice, $users);
            }
            echo true;
        } else {
            echo false;
        }
        exit;
    }

    public function updateAssignmentStatus()
    {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $ids = $this->input->get('ids');
        $idArray = explode(',', $ids);
        $returnArrays = [];
        $u = [];
        foreach ($idArray as $id) {
            $data = [
                'status' => 'checked'
            ];
            if ($returnid = $this->assignmentanswer_m->update_assignmentanswer($data, $id)) {
                $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('assignmentanswerID' => $id));
                $userID = $assignmentanswer->uploaderID;
                $usertypeID = $assignmentanswer->uploadertypeID;
                $u[] = $userID . $usertypeID;
                array_push($returnArrays, $returnid);

            }
        }
        $assignment = $this->assignment_m->get_single_assignment(['assignmentID' => $assignmentanswer->assignmentID]);

        $title = 'Assignment submission checked.';
        $notice = 'Your assignment: ' . $assignment->title . ' submission has been checked';

        $users = serialize($u);

        $array = array(
            "title"             => $title,
            "notice"            => $notice,
            "schoolyearID"      => $schoolyearID,
            "users"             => $users,
            "date"              => date('Y-m-d'),
            "create_date"       => date('Y-m-d H:i:s'),
            "create_userID"     => $this->session->userdata('loginuserID'),
            "create_usertypeID" => $this->session->userdata('usertypeID'),
            "show_to_creator"   => 0
        );
        $this->notice_m->insert_notice($array);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {

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
            

            if(customCompute($u)){
                foreach($u as $nu){  
                    
                    $user_id = substr($nu, 0, -1);
					$user_type = substr($nu, -1);
                    // insert users
                    $noticeUsers[] = [
                        'notice_id'  => $insert_id,
                        'user_id'    => $user_id,
                        'usertypeID' => $user_type
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

            $this->addtojob($title, $notice, $users);
        }
        echo  implode(',', $returnArrays);
    }

    public function updateSingleAssignmentStatus()
    {

        $id = $this->input->get('id');
        $answer = $this->assignmentanswer_m->get_assignmentanswer($id);
        $userID = $answer->uploaderID;
        $usertypeID = $answer->uploadertypeID;
        $array = [
            'status' => $answer->status == "pending" || $answer->status == "viewed" ? "checked" : "pending",
        ];
       
        $returnid = $this->assignmentanswer_m->update_assignmentanswer($array, $id);
        if ($returnid) {

            $assignment = $this->assignment_m->get_single_assignment(['assignmentID' => $answer->assignmentID]);
            $title = 'Assignment submission checked.';
            $notice = 'Your assignment: ' . $assignment->title . ' has been checked';


            $array1 = array(
                "title"             => $title,
                "notice"            => $notice,
                "schoolyearID"      => $this->session->userdata('defaultschoolyearID'),
                "users"             => '',
                "date"              => date('Y-m-d'),
                "create_date"       => date('Y-m-d H:i:s'),
                "create_userID"     => $this->session->userdata('loginuserID'),
                "create_usertypeID" => $this->session->userdata('usertypeID'),
                "show_to_creator"   => 0
            );
            $this->notice_m->insert_notice($array1);
            $insert_id = $this->db->insert_id();

            if($insert_id){
                $this->insertFeed($insert_id,$userID,$usertypeID);
                $u = array($userID . $usertypeID);
                $users = serialize($u);
                $this->addtojob($title, $notice, $users);
            }
            // dd($array['status']);
            $this->data["status"] = $array['status'];
            $retArray['status'] =  $this->data["status"];
            echo json_encode($retArray);
            exit;
        }
    }

    public function unique_classes()
    {
        if ($this->input->post('classesID') == 0) {
            $this->form_validation->set_message("unique_classes", "The %s field is required");
            return false;
        }
        return true;
    }

    public function unique_section()
    {
        $count     = 0;
        $sections  = $this->input->post('sectionID');
        $classesID = $this->input->post('classesID');
        if (customCompute($sections) && $sections != false && $classesID) {
            foreach ($sections as $sectionkey => $section) {
                $setSection   = $section;
                $getDBSection = $this->section_m->general_get_single_section(array('sectionID' => $section, 'classesID' => $classesID));
                if (!customCompute($getDBSection)) {
                    $count++;
                }
            }

            if ($count == 0) {
                return true;
            } else {
                $this->form_validation->set_message("unique_section", "The %s is not match in class");
                return false;
            }
        }
        return true;
    }

    public function date_valid($date)
    {
        if (strlen($date) < 10) {
            $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
            return false;
        } else {
            $arr  = explode("-", $date);
            $dd   = $arr[0];
            $mm   = $arr[1];
            $yyyy = $arr[2];
            if (checkdate($mm, $dd, $yyyy)) {
                return true;
            } else {
                $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                return false;
            }
        }
    }

    public function pastdate_check()
    {
        $date     = strtotime($this->input->post("deadlinedate"));
        $now_date = strtotime(date("d-m-Y"));
        if ($date) {
            if ($date < $now_date) {
                $this->form_validation->set_message("pastdate_check", "The %s field is past date");
                return false;
            }
            return true;
        }
        return true;
    }

    public function unique_subject()
    {
        if ($this->input->post('subjectID') == 0) {
            $this->form_validation->set_message("unique_subject", "The %s field is required");
            return false;
        }
        return true;
    }

    public function unique_unit()
    {
        if ($this->input->post('unitId') == 0) {
            $this->form_validation->set_message("unique_unit", "The %s field is required");
            return false;
        }
        return true;
    }

    public function unique_chapter()
    {
        if ($this->input->post('chapterId') == 0) {
            $this->form_validation->set_message("unique_chapter", "The %s field is required");
            return false;
        }
        return true;
    }

    public function subjectcall()
    {
        $classID = $this->input->post('id');
        if ((int) $classID) {
            $allclasses = $this->subject_m->general_get_order_by_subject(array('classesID' => $classID));
            echo "<option value='0'>", $this->lang->line("assignment_select_subject"), "</option>";
            foreach ($allclasses as $value) {
                echo "<option value=\"$value->subjectID\">", $value->subject, "</option>";
            }
        }
    }

    public function sectioncall()
    {
        $classID = $this->input->post('id');
        if ((int) $classID) {
            $allsection = $this->section_m->general_get_order_by_section(array("classesID" => $classID));
            foreach ($allsection as $value) {
                echo "<option value=\"$value->sectionID\">", $value->section, "</option>";
            }
        }
    }

    public function student_list()
    {
        $classID = $this->input->post('id');
        if ((int) $classID) {
            $string = base_url("assignment/index/$classID");
            echo $string;
        } else {
            redirect(base_url("assignment/index"));
        }
    }

    public function download()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $assignment   = $this->assignment_m->get_single_assignment(array('assignmentID' => $id, 'schoolyearID' => $schoolyearID));
            if (customCompute($assignment)) {
                $file         = realpath('uploads/images/' . $assignment->file);
                $originalname = $assignment->originalfile;
                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($originalname) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                } else {
                    redirect(base_url('assignment/index'));
                }
            } else {
                redirect(base_url('assignment/index'));
            }
        } else {
            redirect(base_url('assignment/index'));
        }
    }

    public function answerdownload()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {
            $schoolyearID     = $this->session->userdata('defaultschoolyearID');
            $assignmentanswer = $this->assignmentanswer_m->get_single_assignmentanswer(array('assignmentanswerID' => $id, 'schoolyearID' => $schoolyearID));
            if (customCompute($assignmentanswer)) {
                $file         = realpath('uploads/images/' . $assignmentanswer->answerfile);
                $originalname = $assignmentanswer->answerfileoriginal;
                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($originalname) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                } else {
                    redirect(base_url('assignment/index'));
                }
            } else {
                redirect(base_url('assignment/index'));
            }
        } else {
            redirect(base_url('assignment/index'));
        }
    }

    public function answerdownloadFiles()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {

            $assignmentanswerFile = $this->assignment_answer_media_m->get_single_assignment_answer_media(array('id' => $id));

            if (customCompute($assignmentanswerFile)) {
                $file         = realpath('uploads/images/' . $assignmentanswerFile->attachment);
                $originalname = $assignmentanswerFile->attachment;
                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($originalname) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                } else {
                    redirect(base_url('assignment/index'));
                }
            } else {
                redirect(base_url('assignment/index'));
            }
        } else {
            redirect(base_url('assignment/index'));
        }
    }

    public function assignmentdownloadFiles()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {

            $assignmentFile = $this->assignment_media_m->get_single_assignment_media(array('id' => $id));

            if (customCompute($assignmentFile)) {
                $file         = realpath('uploads/images/' . $assignmentFile->attachment);
                $originalname = $assignmentFile->attachment;
                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . basename($originalname) . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                } else {
                    redirect(base_url('assignment/index'));
                }
            } else {
                redirect(base_url('assignment/index'));
            }
        } else {
            redirect(base_url('assignment/index'));
        }
    }

    public function getChapters($unitId)
    {
        if ($unitId) {
            $chapters  = $this->chapter_m->get_chapter_from_unit_id($unitId);
            $array[""] = $this->lang->line("assignment_select_chapter");
            foreach ($chapters as $chapter) {
                $array[$chapter->id] = $chapter->chapter_name;
            }
            echo form_dropdown(
                "subject_id",
                $array,
                set_value("subject_id"),
                "id='subject_id' class='form-control select2' required"
            );
        } else {
            showBadRequest(400, 'Invalid request!');
        }
    }

    public function multiplephotoupload()
    {
        if ($_FILES) {
            if ($_FILES['photos']['name'][0] !== "") {
                if (empty(array_filter($_POST['caption']))) {
                    $this->form_validation->set_message("multiplephotoupload", 'The %s caption field is required.');
                    return FALSE;
                }

                $filesCount = customCompute($_FILES['photos']['name']);
                $uploadData = array();
                $uploadPath = 'uploads/images';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                for ($i = 0; $i < $filesCount; $i++) {
                    $_FILES['attach']['name'] = $_FILES['photos']['name'][$i];
                    $_FILES['attach']['type'] = $_FILES['photos']['type'][$i];
                    $_FILES['attach']['tmp_name'] = $_FILES['photos']['tmp_name'][$i];
                    $_FILES['attach']['error'] = $_FILES['photos']['error'][$i];
                    $_FILES['attach']['size'] = $_FILES['photos']['size'][$i];

                    $config['upload_path']   = "./uploads/images";
                    $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('attach')) {
                        $fileData = $this->upload->data();
                        if($fileData['is_image'] == '1')
                        {
                            $image_width = $fileData['image_width'];
					        $image_height = $fileData['image_height'];
                            
						    resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height);
 
                        }
                        $uploadData[$i]['attachment'] = $fileData['file_name'];
                        $uploadData[$i]['caption'] = $_POST['caption'][$i];
                        $uploadData[$i]['create_date'] = date("Y-m-d H:i:s");
                    } else {
                        $this->form_validation->set_message("multiplephotoupload", "%s" . $this->upload->display_errors());
                        return FALSE;
                    }
                }

                $this->upload_data['files'] =  $uploadData;
                return TRUE;
            } else {
                $this->upload_data['files'] =  [];
                return TRUE;
            }
        } else {
            $this->upload_data['files'] =  [];
            return TRUE;
        }
    }

   public function insertFeed($insert_id,$userID,$usertypeID){

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

        // insert users
        $noticeUser = [
            'notice_id'  => $insert_id,
            'user_id'    => $userID,
            'usertypeID' => $usertypeID
        ];

        $feedUser = [
            'feed_id'    => $feedID,
            'user_id'    => $userID,
            'usertypeID' => $usertypeID
        ];

        $this->notice_m->insert_notice_user($noticeUser);
        $this->feed_m->insert_feed_user($feedUser);	
   }

    public function addtojob($title, $notice, $users)
    {

        $this->job_m->insert_job([
            'name'          => 'sendNotice',
            'payload'       => json_encode([
                'users'     => $users,
                'title'     => $title, // title is compulsary
                'message'   => $notice
            ]),
        ]);

        $this->mobile_job_m->insert_job([
            'name'          => 'sendNotice',
            'payload'       => json_encode([
                'users'     => $users,
                'title'     => $title, // title is compulsary
                'message'   => $notice
            ]),
        ]);
    }

    /**
     * web import(answer files)
     */
    public function importAnswer(){
          $assignmentanswers = $this->assignmentanswer_m->get_order_by_assignmentanswer([
              'schoolyearID' => $this->session->userdata('defaultschoolyearID')
          ]);
         
          if(customCompute($assignmentanswers)){
              foreach($assignmentanswers as $assignmentanswer){
                  if($assignmentanswer->answerfile != ''){
                       $assignmentanswerID = $assignmentanswer->assignmentanswerID;
                       $assignmentanswerMedia = $this->assignment_answer_media_m->get_order_by_assignment_answer_media(['assignmentanswerID'=> $assignmentanswerID]);
                       if(!customCompute($assignmentanswerMedia)){
                           $data = [
                               'assignmentanswerID' => $assignmentanswerID,
                               'attachment'         => $assignmentanswer->answerfile,
                               'caption'            => 'caption',
                               'create_date'       => date('Y-m-d H:i:s')
                           ];
                           if($this->assignment_answer_media_m->insert_assignment_answer_media($data)){
                               echo 'success';
                           }else{
                               echo 'error';
                           }
                       }

                  } 
              }
          }
    }
}
