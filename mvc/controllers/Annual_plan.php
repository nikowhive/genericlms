<?php
class Annual_plan extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("job_m");
        $this->load->model("mobile_job_m");
        $this->load->model("annual_plan_m");
        $this->load->model("annual_plan_media_m");
        $this->load->model("student_m");
        $this->load->model("notice_m");
        $this->load->model("courses_m");
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->helper('file');
        $language = $this->session->userdata('lang');
        $this->lang->load('annualplan', $language);
        $this->load->helper('date');
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'caption[]',
                'label' => $this->lang->line("annual_caption"),
                'rules' => 'trim|required|xss_clean|max_length[128]',
            ),

            array(
                'field' => 'upload_Files[]',
                'label' => $this->lang->line("annual_file"),
                'rules' => 'trim|max_length[512]|xss_clean|callback_fileupload_multiple',
            ),
        );
        return $rules;
    }



    public function add()
    {
        $course = isset($_GET['course']) ? $_GET['course'] : '';
        $link = isset($_GET['link']) ? $_GET['link'] : '';
        $this->data['usertypeID'] = $this->session->userdata('usertypeID');
        $this->data['course']    = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        $array = [];
        if ($_POST) {

            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data["subview"] = "courses/annual/add";
                $this->load->view('_layout_course', $this->data);
            } else {
                $check_annual = $this->annual_plan_m->get_single_annual_plan(['course_id' => $course]);
                if ($check_annual) {
                    redirect(base_url("annual_plan/edit/" . $check_annual->id));
                } else {
                    $array = array(
                        'course_id' => $course,
                        'published' => 2
                    );

                    $last_id = $this->annual_plan_m->insert($array);
                    if ($last_id) {
                        $lists = [];
                        foreach ($this->uploadData as $v) {
                            $v['annual_plan_id'] = $last_id;
                            array_push($lists, $v);
                        }
                        $this->annual_plan_media_m->insert_batch_annual_plan_media($lists);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("courses/annual?course=" . $course));
                    }
                }
            }
        } else {
            $this->data["subview"] = "courses/annual/add";
            $this->load->view('_layout_course', $this->data);
        }
    }

    public function edit()
    {
        if (isset($_GET['course'])) {
            $course = isset($_GET['course']) ? $_GET['course'] : '';

            $this->data['course']    = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        }

        $this->data['usertypeID'] = $this->session->userdata('usertypeID');

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->data['annual']  = $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {

            $this->data['annual_medias'] = $this->annual_plan_media_m->get_order_by_annual_plan_media(array('annual_plan_id' => $id));

            if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {

                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {

                        $this->data["subview"] = "courses/annual/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {

                        $lists = [];
                        foreach ($this->uploadData as $v) {
                            $v['annual_plan_id'] = $id;
                            array_push($lists, $v);
                        }
                        $this->annual_plan_media_m->insert_batch_annual_plan_media($lists);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("courses/annual/" . $course));
                    }
                } else {
                    $this->data["subview"] = "courses/annual/edit";
                    $this->load->view('_layout_main', $this->data);
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

    public function delete()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        // $this->data['course'] = $this->annual_plan_m->get_single_annual_plan(["id" => $id]);
        $annual = isset($_GET['annual']) ? $_GET['annual'] : '';
        $course = isset($_GET['course']) ? $_GET['course'] : '';

        if ((int) $id) {
            $this->annual_plan_media_m->delete($id);
            $this->session->set_flashdata('success', $this->lang->line('menu_success'));

            redirect(base_url('annual_plan/edit/' . $annual . '?course=' . $course));
        } else {
            redirect(base_url('annual_plan/edit/' . $annual . '?course=' . $course));
        }
    }

    public function ajaxChangeAnnualStatus($id)
    {
        $annual = $this->annual_plan_m->get_single_annual_plan(array('id' => $id));
        $array = [
            'published' => $annual->published == 2 ? 1 : 2,
        ];
        if ($this->annual_plan_m->update($array, $id) && $array['published'] == 1) {
            $record = $this->courses_m->get_join_courses_based_on_course_id($annual->course_id);
            $title = 'Annual Plan Published';
            $notice = "Annual Plan for class" . $record->classes . " of " . $record->subject . "  has been published";
            $this->notification($title, $notice, $record->classesID);
        }
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

    public function pushNotification($title, $class = null)
    {
        $this->job_m->insert_job([
            'name' => 'sendAnnualPlanNotification',
            'payload' => json_encode([
                'class' => $class,
                'title' => $title, // title is compulsary
            ]),
        ]);
    }

    public function mobPushNotification($array)
    {
        $this->mobile_job_m->insert_job([
            'name' => 'sendAnnualPlanNotification',
            'payload' => json_encode([
                'users' => $array['users'],
                'title' => $array['title'], // title is compulsary
                'message' => $array['notice']
            ]),
        ]);
    }

    function fileupload_multiple()
    {
        $id         = htmlentities(escapeString($this->uri->segment(3)));
        $annual_media = [];
        if ((int) $id) {
            $this->data["annual_media"] = $this->annual_plan_m->getRows($id);
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

            if (customCompute($this->data["annual_media"])) {
                $this->annual_plan_media_m->update_annual_plan_media($this->data["annual_media"], $id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("courses/annual/" . $this->data["annual_media"]['course_id']));
                return true;
            }
        }
    }

    public function fileupload_single()
    {
        $id         = htmlentities(escapeString($this->uri->segment(3)));
        $annual = [];
        if ((int) $id) {
            $annual = $this->annual_plan_m->get_single_annual_plan(array('id' => $id));
        }

        $new_file           = "";
        $original_file_name = '';
        if ($_FILES["file"]['name'] != "") {
            $file_name          = $_FILES["file"]['name'];
            $original_file_name = $file_name;
            $random             = random19();
            $makeRandom         = hash('sha512', $random . $this->input->post('caption') . config_item("encryption_key"));
            $file_name_rename   = $makeRandom;
            $explode            = explode('.', $file_name);
            if (customCompute($explode) >= 2) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/images";
                $config['allowed_types'] = "doc|docx|DOC|DOCX|xls|xlsx|ppt|csv";
                $config['file_name']     = $new_file;
                $config['max_size']      = '100024';
                $config['max_width']     = '3000';
                $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("file")) {
                    $this->form_validation->set_message("fileupload_single", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['file']                       = $this->upload->data();
                    $this->upload_data['file']['original_file_name'] = $original_file_name;
                    return true;
                }
            } else {
                $this->form_validation->set_message("fileupload_single", "Invalid file");
                return false;
            }
        } else {
            if (customCompute($annual)) {
                $this->upload_data['file']                       = array('file_name' => $annual->file);
                // $this->upload_data['file']['original_file_name'] = $annual->originalfile;
                return true;
            } else {
                $this->upload_data['file']                       = array('file_name' => $new_file);
                $this->upload_data['file']['original_file_name'] = $original_file_name;
                return true;
            }
        }
    }
}