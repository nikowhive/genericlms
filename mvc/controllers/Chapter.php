<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Chapter extends Admin_Controller
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
        $this->load->model("subject_m");
        $this->load->model("chapter_m");
        $this->load->model("classes_m");
        $this->load->model("courses_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('chapter', $language);
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
            $this->data['chapters'] = $this->chapter_m->get_join_chapters($id);
            $this->data["subview"] = "chapter/index";
            $this->load->view('_layout_main', $this->data);
        } else {
            $this->data['classes'] = $this->classes_m->get_classes();
            $this->data["subview"] = "chapter/search";
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
                'rules' => 'trim|numeric|required|xss_clean|greater_than[0]',
                'errors' => array(
                    'greater_than' => 'The Sbject name is required.',
            ),
            ),
            array(
                'field' => 'unit_id',
                'label' => $this->lang->line("chapter_unit"),
                'rules' => 'numeric|trim|required|xss_clean|greater_than[0]',
                'errors' => array(
                    'greater_than' => 'The Unit name is required.',
            ),
            ),
            array(
                'field' => 'chapter_name',
                'label' => $this->lang->line("chapter_name"),
                'rules' => 'trim|required|xss_clean|callback_chapter_name',
            ),
            array(
                'field' => 'chapter_code',
                'label' => $this->lang->line("chapter_code"),
                'rules' => 'trim|max_length[5]|xss_clean',
            ),
        );
        return $rules;
    }

    public function chapter_name()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$id) {
            $chapter = $this->chapter_m->get_single_chapter(array("subject_id" => $this->input->post("subject_id"),  "unit_id" => $this->input->post("unit_id"), "id !=" => $id, "chapter_name" => $this->input->post("chapter_name")));
            if (customCompute($chapter)) {
                $this->form_validation->set_message("chapter_name", "%s already exists");
                return FALSE;
            }
            return TRUE;
        } else {
            $chapter = $this->chapter_m->get_single_chapter(array("subject_id" => $this->input->post("subject_id"), "unit_id" => $this->input->post("unit_id"), "chapter_name" => $this->input->post("chapter_name")));

            if (customCompute($chapter)) {
                $this->form_validation->set_message("chapter_name", "%s already exists");
                return FALSE;
            }
            return TRUE;
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

        $this->data['classes'] = $this->classes_m->get_classes();

        $this->data['unit_id'] = 0;

        if (isset($_GET['course'])) {
            $course = $_GET['course'];
            $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
            $classesID = $this->data['course']->class_id;
            $this->data['subjectID'] = $this->data['course']->subject_id;
            $this->data['classesID'] = $this->data['course']->class_id;
            $this->data['subjects'] = $this->subject_m->get_subjects_by_class_id($this->data['classesID']);
            $this->data['units'] = $this->unit_m->get_units_by_subject_id($this->data['subjectID']);

            if (isset($_GET['unit'])) {
                $this->data['unit_id'] = $_GET['unit'];
            }
        } else {
            $this->data['subjectID'] = 0;
            $this->data['classesID'] = 0;
            $this->data['subjects'] = [];
            $this->data['units'] = [];
        }

        if ($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $this->data['classesID'] = $this->input->post('classesID');
                $this->data['subjects'] = $this->subject_m->get_subjects_by_class_id($this->data['classesID']);
                $this->data['units'] = $this->unit_m->get_units_by_subject_id($_POST['subject_id']);
                if ($this->input->is_ajax_request()) {
                    $retArray['status'] = true;
                    $retArray['chapter_name_error'] = form_error('chapter_name');
                    $retArray['chapter_code_error'] = form_error('chapter_code');
                    echo json_encode($retArray);
                    exit;
                }

                $this->data["subview"] = "chapter/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $input = [
                    'chapter_name' => $this->input->post('chapter_name'),
                    'subject_id' => $this->input->post('subject_id'),
                    'chapter_code' => $this->input->post('chapter_code'),
                    'unit_id' => $this->input->post('unit_id'),
                    'unit' => $this->unit_m->get_unit($this->input->post('unit_id'))->unit_name,
                ];

                $this->chapter_m->insert_chapter($input);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                $class_id = $this->input->post('classesID');
                if ($this->input->is_ajax_request()) {
                    $retArray['status'] = true;
                    $retArray['render'] = 'success';
                    $retArray['message'] = $this->lang->line('menu_success');
                    echo json_encode($retArray);
                    exit;
                } else {
                    if ($course == '') {
                        redirect(base_url("chapter/index/" . $class_id));
                    } else {
                        redirect(base_url("courses/show/" . $course));
                    }
                }
            }
        } else {
            $this->data["subview"] = "chapter/add";
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
            $chapter = $this->chapter_m->get_chapter($id);
            $this->data['chapter'] = $chapter;
            $this->data['unit'] = $this->unit_m->get_unit($chapter->unit);

            if ($this->data['chapter']) {
                $url = $this->data['set_class'] = $this->subject_m->get_class_id_from_subject($this->data['chapter']->subject_id);
                $this->data['subjects'] = $this->subject_m->get_subjects_by_class_id($this->data['set_class']);
                $this->data['units'] = $this->unit_m->get_units_by_subject_id($this->data['chapter']->subject_id);
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data['form_validation'] = validation_errors();
                        if ($this->input->is_ajax_request()) {
                            $retArray['status'] = true;
                            $retArray['chapter_name_error'] = form_error('chapter_name');
                            $retArray['chapter_code_error'] = form_error('chapter_code');
                            echo json_encode($retArray);
                            exit;
                        }
                        $this->data["subview"] = "chapter/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "chapter_name" => $this->input->post("chapter_name"),
                            'chapter_code' => $this->input->post('chapter_code'),
                            "subject_id" => $this->input->post("subject_id"),
                            'unit_id' => $this->input->post('unit_id'),
                            'unit' => $this->unit_m->get_unit($this->input->post('unit_id'))->unit_name,
                        );

                        $this->chapter_m->update_chapter($array, $id);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        if ($this->input->is_ajax_request()) {
                            $retArray['status'] = true;
                            $retArray['render'] = 'success';
                            $retArray['message'] = $this->lang->line('menu_success');
                            echo json_encode($retArray);
                            exit;
                        }
                        if ($course == '') {
                            redirect(base_url("chapter/index/$url"));
                        } else {
                            redirect(base_url("courses/show/" . $course));
                        }
                    }
                } else {
                    $this->data["subview"] = "chapter/edit";
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

    // Todo: Don't let user delete if there is question bank
    public function delete()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {
            $chapter = $this->chapter_m->get_chapter($id);
            if (customCompute($chapter)) {
                $url = $this->subject_m->get_class_id_from_subject($chapter->subject_id);
                $this->chapter_m->delete_chapter($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("chapter/index/$url"));
            } else {
                redirect(base_url("chapter/index"));
            }
        } else {
            redirect(base_url("chapter/index"));
        }
    }

    public function chapter_list()
    {
        $classID = $this->input->post('id');
        if ((int) $classID) {
            $string = base_url("chapter/index/$classID");
            echo $string;
        } else {
            redirect(base_url("chapter/index"));
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

    public function ajaxTerminalFromClassId()
    {
        $class_id = $this->input->get('class_id');
        $subjects = $this->chapter_m->get_terminal_from_class($class_id);
        $returnval = '<ul style="list-style-type:none;">';
        $i = 1;
        if (!empty($subjects)) {
            foreach ($subjects as $subject) {
                $returnval .= '<li>' . $i . ') <input type="checkbox" id="sub' . $subject->onlineExamID . '"  name="subject[]" value="' . $subject->onlineExamID . '">
                <label for="sub' . $subject->onlineExamID . '">' . $subject->name . '</label></li>';

                $i++;
            }
        } else {
            $returnval .= '<li>Subject not found</li>';
        }
        $returnval .= '</ul>';
        echo $returnval;
    }

    public function ajaxGetChaptersFromUnitId()
    {
        $unit_id = $this->input->get('unit_id');
        $chapters = $this->chapter_m->get_chapters_from_unit($unit_id);
        $array = [];
        $array[""] = $this->lang->line("select_chapter");
        foreach ($chapters as $chapter) {
            $array[$chapter->id] = $chapter->chapter_name;
        }
        echo form_dropdown("chapter_id", $array, set_value("chapter_id"), "id='chapter_id' class='form-control select2' required");
    }

    public function ajaxInsertChapter($value = '')
    {
        if ($_POST) {

            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {
                $retArray['status'] = true;
                $retArray['unit_error'] = form_error('unit_id');
                $retArray['chapter_name_error'] = form_error('chapter_name');
                $retArray['chapter_code_error'] = form_error('chapter_code');
                echo json_encode($retArray);
                exit;
            } else {
                $input = [
                    'chapter_name' => $this->input->post('chapter_name'),
                    'subject_id' => $this->input->post('subject_id'),
                    'unit_id' => $this->input->post('unit_id'),
                    'unit' => $this->unit_m->get_unit($this->input->post('unit_id'))->unit_name,
                ];

                $this->chapter_m->insert_chapter($input);

                $retArray['status'] = true;
                $retArray['render'] = 'success';
                $retArray['message'] = $this->lang->line('menu_success');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $this->data["subview"] = "chapter/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function editChapterByAjax(Type $var = null)
    {
        $course = $this->input->post('course');
        $id = $this->input->post('id');
        $url = $this->input->post('set');
        $html = '';

        $this->data['chapter'] = $this->chapter_m->get_chapter($id);
        $this->data['subjects'] = $this->subject_m->general_get_single_subject(['subjectID' => $this->data['chapter']->subject_id]);
        $this->data['classes'] = $this->classes_m->general_get_single_classes(['classesID' => $this->data['subjects']->classesID]);


        $set_class = '';
        $array = array();
        $html .= '
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">' . $this->data['chapter']->chapter_name . '</h3>
    </div>
    <form class="" role="form" id="edit-chapter-with-unit" method="post" action="' . base_url('chapter/edit/' . $this->data['chapter']->id) . '?course=' . $course . '&unit=' . $this->data['chapter']->unit_id . '">
        <div class="modal-body">
    
    
            <div class="form-group ">
                <div class="md-form md-form--select">
                    <input type="text" class="form-control" id="classesID" name="classes_name" value="' . $this->data['classes']->classes . '" readOnly>
                    <input type="hidden" name="classesID" value="' . $this->data['classes']->classesID . '">
                    <label for="" class="mdb-main-label">Select Class</label>
                    <span class="text-danger error">
                        <p id="class-error"></p>
                    </span>
                </div>
            </div>
    
    
            <div class="form-group">
                <div class="md-form md-form--select">
                    <input type="text" id="" name="subject_name" value="' . $this->data['subjects']->subject . '" readOnly class="form-control">
                    <input type="hidden" name="subject_id" id="subject_id" value="' . $this->data['subjects']->subjectID . '">
                    <label for="" class="mdb-main-label">Select Subject</label>
                    <span class="text-danger error">
                        <p id="chapter-error"></p>
                    </span>
                </div>
            </div>
    
            <div class="form-group">
                <div class="md-form">
                    <label for="unit_name" class="active">Unit</label>
                    <input type="text" class="form-control" id="unit_name" name="unit_name" value="' . $this->data['chapter']->unit . '" readonly>
                    <input type="hidden" name="unit_id" value="' . $this->data['chapter']->unit_id . '">
                    <span class="text-danger error">
                        <p id="unit-error"></p>
                    </span>
                </div>
            </div>
    
            <div class="form-group">
                <div class="md-form">
                    <label for="chapter_name" class="active">Chapter name</label>
                    <input type="text" class="form-control" id="chapter_name" name="chapter_name" value="' . $this->data['chapter']->chapter_name . '">
                    <span class="text-danger error">
                        <p class="chapter-error"></p>
                    </span>
                </div>
            </div>
    
            <div class="form-group">
                <div class="md-form">
                    <label for="chapter_code" class="active">Chapter code</label>
                    <input type="text" class="form-control" id="chapter_code" name="chapter_code" value="' . $this->data['chapter']->chapter_code . '">
    
                    <span class="text-danger error">
                        <p class="chapter-code-error"></p>
                    </span>
                </div>
            </div>

            <input type="hidden" id="ajax-get-chapter-url" value="<?php echo base_url() ?>chapter/ajaxGetChaptersFromUnitId">
        </div>
    
        <div class="modal-footer">
            <input type="submit" id="" class="btn btn-primary" value="Update">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </form>
    </div>';
        echo $html;
    }

    public function addChapterWithUnit(Type $var = null)
    {
        $course = $this->input->post('course');
        $id = $this->input->post('unit_id');
        $html = '';

        if ($course) {
            $this->data['unit'] = $this->unit_m->get_unit($id);
            $this->data['course'] = $this->courses_m->get_all_join_courses_based_on_course_id($course);
            $this->data['classes'] = $this->classes_m->general_get_single_classes(['classesID' => $this->data['course']->class_id]);
            $this->data['subjects'] = $this->subject_m->general_get_single_subject(['subjectID' => $this->data['unit']->subject_id]);
        }

        $html .= '
        <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h3 class="modal-title">' . $this->data['unit']->unit_name . '</h3>
</div>
<form class="" role="form" id="add-chapter-with-unit" method="post" action="' . base_url('chapter/add/?course=' . $course . '&unit=' . $id)  . '?course=' . $course . '">
    <div class="modal-body">

        <div class="form-group ">
            <div class="md-form md-form--select">
                <input type="text" class="form-control" id="classesID" name="classes_name" placeholder="' . $this->data["classes"]->classes . '" value="" readOnly>
                <input type="hidden" name="classesID" value="' . $this->data["classes"]->classesID . '">
                <label for="" class="mdb-main-label">Select Class</label>
                <span class="text-danger error">
                    <p id="class-error"></p>
                </span>
            </div>
        </div>

        <div class="form-group">
            <div class="md-form md-form--select">
                <input type="text" id="" name="subject_name" value="' . $this->data['subjects']->subject . '" readOnly class="form-control">
                <input type="hidden" name="subject_id" id="subject_id" value="' . $this->data['subjects']->subjectID . '">
                <label for="" class="mdb-main-label">Select Subject</label>
                <span class="text-danger error">
                    <p id="chapter-error"></p>
                </span>
            </div>
        </div>

        <div class="form-group">
            <div class="md-form">
                <label for="unit_name" class="active">Unit name</label>
                <input type="text" class="form-control" id="unit_name" name="unit_name" value="' . $this->data['unit']->unit_name  . '" readonly>
                <input type="hidden" name="unit_id" value="' . $id . '">
                <span class="text-danger error">
                    <p id="unit-error"></p>
                </span>
            </div>
        </div>

        <div class="form-group">
            <div class="md-form">
                <label for="chapter_name" class="active">Chapter name</label>
                <input type="text" class="form-control" id="chapter_name" name="chapter_name" value="">

                <span class="text-danger error">
                    <p class="chapter-error"></p>
                </span>
            </div>
        </div>

        <div class="form-group">
        <div class="md-form">
            <label for="chapter_code" class="active">Chapter code</label>
            <input type="text" class="form-control" id="chapter_code" name="chapter_code" value="">

            <span class="text-danger error">
                <p class="chapter-code-error"></p>
            </span>
        </div>
    </div>

        <input type="hidden" id="ajax-get-chapter-url" value="<?php echo base_url() ?>chapter/ajaxGetChaptersFromUnitId">
    </div>

    <div class="modal-footer">
        <input type="submit" id="" class="btn btn-primary" value="Save">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</form>
</div>';
        echo $html;
    }
}
