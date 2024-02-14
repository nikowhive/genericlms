<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Unit extends Admin_Controller
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
    public $units;

    public function __construct()
    {
        parent::__construct();

        $this->load->model("unit_m");
        $this->load->model("chapter_m");
        $this->load->model("classes_m");
        $this->load->model("subject_m");
        $this->load->model("parents_m");
        $this->load->model("courses_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('unit', $language);
    }

    public function index()
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
        $usertypeID = $this->session->userdata("usertypeID");
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {
            $this->data['set'] = $id;
            $this->data['classes'] = $this->classes_m->get_classes();
            $this->data['units'] = $this->unit_m->get_join_units($id);
            $this->data["subview"] = "unit/index";
            $this->load->view('_layout_main', $this->data);
        } else {
            $this->data['classes'] = $this->classes_m->get_classes();
            $this->data["subview"] = "unit/search";
            $this->load->view('_layout_main', $this->data);
        }
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'classesID',
                'label' => $this->lang->line("subject_class_name"),
                'rules' => 'trim|numeric|required|xss_clean|max_length[11]|callback_allclasses',
            ),
            array(
                'field' => 'subject_id',
                'label' => $this->lang->line("subject_name"),
                'rules' => 'trim|numeric|required|xss_clean|callback_unique_subject',
            ),
            array(
                'field' => 'unit_name',
                'label' => $this->lang->line("unit_name"),
                'rules' => 'trim|required|xss_clean|callback_unit_name',
            ),
            array(
                'field' => 'unit_code',
                'label' => $this->lang->line("unit_code"),
                'rules' => 'trim|xss_clean',
            ),
        );
        return $rules;
    }

    public function unit_name()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$id) {
            $subject = $this->unit_m->general_get_order_by_unit(array("subject_id" => $this->input->post("subject_id"), "id !=" => $id, "unit_name" => $this->input->post("unit_name")));
            if (customCompute($subject)) {
                $this->form_validation->set_message("unit_name", "%s already exists");
                return FALSE;
            }
            return TRUE;
        } else {
            $unit = $this->unit_m->general_get_order_by_unit(array("subject_id" => $this->input->post("subject_id"), "unit_name" => $this->input->post("unit_name")));
            if (customCompute($unit)) {
                $this->form_validation->set_message("unit_name", "%s already exists");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function unique_subject()
    {
        if ($this->input->post('subject_id') == 0) {
            $this->form_validation->set_message("unique_subject", "The %s field is required");
            return FALSE;
        }
        return TRUE;
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

        $this->data['classes'] = $this->classes_m->get_classes();

        if (isset($_GET['course'])) {
            $course = $_GET['course'];
            $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
            $classesID = $this->data['course']->class_id;
            $this->data['subjectID'] = $this->data['course']->subject_id;
            $this->data['classesID'] = $this->data['course']->class_id;
            $this->data['subjects'] = $this->subject_m->get_subjects_by_class_id($this->data['classesID']);
        } else {
            $this->data['subjectID'] = 0;
            $this->data['classesID'] = 0;
            $this->data['subjects'] = [];
        }

        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['classesID'] = $this->input->post('classesID');
                $this->data['subjects'] = $this->subject_m->get_subjects_by_class_id($this->data['classesID']);

                $this->data["subview"] = "unit/add";
                $this->load->view('_layout_main', $this->data);
            } else {

                $input = [
                    'unit_name' => $this->input->post('unit_name'),
                    'unit_code' => $this->input->post('unit_code'),
                    'subject_id' => $this->input->post('subject_id'),
                ];

                $this->unit_m->insert_unit($input);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                $class_id = $this->input->post('classesID');

                if ($course == '') {
                    redirect(base_url("unit/index/" . $class_id));
                } else {
                    redirect(base_url("courses/show/" . $course));
                }
            }
        } else {
            $this->data["subview"] = "unit/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit()
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
        if (isset($_GET['course'])) {
            $course = $_GET['course'];
            $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        }
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {
            $this->data['set_class'] = null;
            $this->data['subjects'] = [];
            $this->data['classes'] = $this->classes_m->get_classes();
            $this->data['unit'] = $this->unit_m->get_unit($id);

            if ($this->data['unit']) {
                $url = $this->data['set_class'] = $this->subject_m->get_class_id_from_subject($this->data['unit']->subject_id);
                $this->data['subjects'] = $this->subject_m->get_subjects_by_class_id($this->data['set_class']);
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data['form_validation'] = validation_errors();
                        $this->data["subview"] = "unit/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "unit_name" => $this->input->post("unit_name"),
                            "subject_id" => $this->input->post("subject_id"),
                            'unit_code' => $this->input->post('unit_code')
                        );
                        $this->unit_m->update_unit($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));

                        if ($course == '') {
                            redirect(base_url("unit/index/" . $url));
                        } else {
                            redirect(base_url("courses/show/" . $course));
                        }
                    }
                } else {
                    $this->data["subview"] = "unit/edit";
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }



    public function delete()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {
            $unit = $this->unit_m->get_unit($id);
            if (customCompute($unit)) {
                $url = $this->subject_m->get_class_id_from_subject($unit->subject_id);
                if ($this->chapter_m->get_chapters_from_unit_id($id) == null) {
                    $this->unit_m->delete_unit($id);
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                } else {
                    $this->session->set_flashdata('error', "Please delete related chapters first");
                }
                redirect(base_url("unit/index/$url"));
            } else {
                redirect(base_url("unit/index"));
            }
        } else {
            redirect(base_url("unit/index"));
        }
    }

    public function unit_list()
    {
        $classID = $this->input->post('id');
        if ((int) $classID) {
            $string = base_url("unit/index/$classID");
            echo $string;
        } else {
            redirect(base_url("unit/index"));
        }
    }

    public function allclasses()
    {
        if ($this->input->post('classesID') == 0) {
            $this->form_validation->set_message("allclasses", "The %s field is required");
            return false;
        }
        return true;
    }

    public function ajaxGetUnitsFromSubjectId()
    {
        $subject_id = $this->input->get('subject_id');
        $units = $this->unit_m->get_unit_from_subject($subject_id);
        $array = [];
        $array[""] = $this->lang->line("select_unit");
        foreach ($units as $unit) {
            $array[$unit->id] = $unit->unit_name;
        }
        echo form_dropdown("unit_id", $array, set_value("unit_id"), "id='unit_id' class='form-control select2' required");
    }

    public function ajaxEditUnit()
    {
        if (isset($_GET['course'])) {
            $course = $_GET['course'];
            $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        }
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {
            $this->data['set_class'] = null;
            $this->data['subjects'] = [];
            $this->data['classes'] = $this->classes_m->get_classes();
            $this->data['unit'] = $this->unit_m->get_unit($id);

            if ($this->data['unit']) {
                $url = $this->data['set_class'] = $this->subject_m->get_class_id_from_subject($this->data['unit']->subject_id);
                $this->data['subjects'] = $this->subject_m->get_subjects_by_class_id($this->data['set_class']);
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        // $this->data['form_validation'] = validation_errors();
                        $retArray['status'] = true;
                        $retArray['unit_error'] = form_error('unit_name');
                        $retArray['unit_code_error'] = form_error('unit_code');
                        echo json_encode($retArray);
                        exit;
                    } else {
                        $array = array(
                            "unit_name" => $this->input->post("unit_name"),
                            "subject_id" => $this->input->post("subject_id"),
                            'unit_code' => $this->input->post('unit_code')
                        );
                        $this->unit_m->update_unit($array, $id);
                        $retArray['status'] = true;
                        $retArray['render'] = 'success';
                        $retArray['message'] = $this->lang->line('menu_success');
                        echo json_encode($retArray);
                        exit;
                    }
                } 
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
        
    }

    public function ajaxInsertUnit($value = '')
    {
        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['form_validation'] = validation_errors();
                $retArray['status'] = true;
                $retArray['unit_error'] = form_error('unit_name');
                $retArray['unit_code_error'] = form_error('unit_code');
                echo json_encode($retArray);
                exit;
            } else {
                $input = [
                    'unit_name' => $this->input->post('unit_name'),
                    'subject_id' => $this->input->post('subject_id'),
                    'unit_code' => $this->input->post('unit_code'),
                ];

                $this->unit_m->insert_unit($input);
                $retArray['status'] = true;
                $retArray['render'] = 'success';
                $retArray['message'] = $this->lang->line('menu_success');
                echo json_encode($retArray);
                exit;
            }
        }
    }

    public function getUnitByAjax(Type $var = null)
    {
        $id = $this->input->post('id');
        $course =  $this->data['course_id']=$this->input->post('course');
        $classes = $this->classes_m->get_classes();
        $this->data['unit'] = $this->unit_m->get_unit($id);
        $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
        $this->data['classes'] = $this->classes_m->general_get_single_classes(['classesID' => $this->data['course']->class_id]);
        $this->data['subjects'] = $this->subject_m->general_get_single_subject(['subjectID' => $this->data['unit']->subject_id]);
        echo $this->load->view('unit/view_unit', $this->data, true);
        exit;
    }
}
