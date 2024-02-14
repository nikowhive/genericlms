<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Subject_mark extends Admin_Controller
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
    function __construct()
    {
        parent::__construct();
        $this->load->model("mark_m");
        $this->load->model("grade_m");
        $this->load->model("classes_m");
        $this->load->model("exam_m");
        $this->load->model("subject_m");
        $this->load->model("section_m");
        $this->load->model("student_m");
        $this->load->model("subjectmark_m");
        $this->load->model("markrelation_m");
        $this->load->model("markpercentage_m");
        $this->load->model('studentrelation_m');
        $this->load->model('marksetting_m');
        $this->db->cache_off();

        $language = $this->session->userdata('lang');
        $this->lang->load('subject_mark', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'examID',
                'label' => $this->lang->line("mark_exam"),
                'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_examID'
            ),
            array(
                'field' => 'classesID',
                'label' => $this->lang->line("mark_classes"),
                'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classesID'
            ),
        );
        return $rules;
    }

    public function unique_examID()
    {
        if ($this->input->post('examID') == 0) {
            $this->form_validation->set_message("unique_examID", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_classesID()
    {
        if ($this->input->post('classesID') == 0) {
            $this->form_validation->set_message("unique_classesID", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function index()
    {
        // if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID'))) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css'
                ),
                'js' => array(
                    'assets/select2/select2.js'
                )
            );
            $this->data['students']           = [];
            $this->data['settingmarktypeID']  = $this->data['siteinfos']->marktypeID;
            $graduateclass                    = $this->data['siteinfos']->ex_class;

            $this->data['set_exam']    = 0;
            $this->data['set_classes'] = 0;
            $this->data['set_section'] = 0;
            $this->data['set_subject'] = 0;

            $this->data['sendExam']    = [];
            $this->data['sendSubject'] = [];
            $this->data['sendClasses'] = [];
            $this->data['sendSection'] = [];
            $this->data['exams']       = [];

            $this->data['grades'] = $this->grade_m->get_order_by_grade();

            $classesID = $this->input->post("classesID");
            if ((int)$classesID) {
                $this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID);
                $this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $classesID));
            } else {
                $this->data['subjects']     = [];
                $this->data['sections'] = [];
            }

            $this->data['classes']  = $this->classes_m->get_order_by_classes(['classesID !=' => $graduateclass]);

            if ($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);

                if ($this->form_validation->run() == FALSE) {

                    $this->data["subview"] = "subject_mark/add_bulk_subject";
                    $this->load->view('_layout_main', $this->data);
                } else {

                    $examID        = $this->data['set_exam']    = $this->input->post('examID');
                    $classesID     =    $this->data['set_classes']  = $this->input->post('classesID');
                    $exam       = $this->data['sendExam']      = $this->exam_m->get_single_exam(array('examID' => $examID));

                    $classes    =    $this->data['sendClasses']      = $this->classes_m->get_single_classes(array('classesID' => $classesID));

                    $subjects = $this->subject_m->get_subjects_name_by_class_id($classesID);


                    $markPluck   = pluck($this->subjectmark_m->get_order_by_subject_marks(array("exam_id" => $examID, "class_id" => $classesID,)), 'obj', 'subject_id');

                    $subArray = [];
                    if (customCompute($subjects)) {
                        foreach ($subjects as $key => $value) {


                            if (!isset($markPluck[$value->subjectID])) {
                                $subArray[] = [
                                    'subject_id' => $value->subjectID,
                                    'exam_id' => $examID,
                                    'class_id' => $classesID,
                                    'fullmark' => $value->finalmark,
                                    'passmark' => $value->passmark,
                                    'order_no' => $value->order_no,
                                    'no_coscholastic' => 0,
                                ];
                            }
                        }

                        if (customCompute($subArray)) {
                            $res = $this->subjectmark_m->insert_batch_subject_mark($subArray);

                            if ($res) {
                                $this->data['sendSubject'] = $this->subjectmark_m->get_subjects_by_class_exam_id($classesID, $examID);
                            }
                        } else {
                            $this->data['sendSubject'] = $this->subjectmark_m->get_subjects_by_class_exam_id($classesID, $examID);
                        }
                    }


                    $this->data["subview"] = "subject_mark/add_bulk_subject";
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = "subject_mark/add_bulk_subject";
                $this->load->view('_layout_main', $this->data);
            }
        // } else {
        //     $this->data["subview"] = "error";
        //     $this->load->view('_layout_main', $this->data);
        // }
    }

    public function update_bulk_subject_mark(Type $var = null)
    {
        $sub = $this->input->post('subject');
        $exam_id = $this->input->post('exam_id');
        $class_id = $this->input->post('class_id');
        $id = $this->input->post('id');

        $order = [];
        $subArray = array();
        foreach ($sub as $key => $v) {
            array_push($order, $v[2]);
        }
        foreach ($sub as $key => $v) {
            $subArray = [
                'subject_id' => $key,
                'exam_id' => $exam_id,
                'class_id' => $class_id,
                'fullmark' => isset($v[0]) ? $v[0] : 'NULL',
                'passmark' => isset($v[1]) ? $v[1] : 'NULL',
                'order_no' => isset($v[2]) ? $v[2] : 0,
                'no_coscholastic' => isset($v[3]) ? $v[3] : 1,
            ];

            $this->data['sendSubject'] = $this->subjectmark_m->get_subjects_by_class_exam_and_subject_id($class_id, $key, $exam_id);
            if ($this->data['sendSubject']) {
                $res =  $this->subjectmark_m->update_subject_mark($subArray, $this->data['sendSubject']->id);
            } else {
                $res = $this->subjectmark_m->insert_subject_mark($subArray);
            }
        }
        if ($res) {
            $retArray['status'] = true;
            $retArray['render'] = 'Success';
            $retArray['message'] = 'Successful';
            echo json_encode($retArray);
            exit;
        }
    }
}