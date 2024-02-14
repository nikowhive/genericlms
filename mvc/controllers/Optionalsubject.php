<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Optionalsubject extends Admin_Controller
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
        $this->load->model('grade_m');
        $this->load->model("section_m");
		$this->load->model("classes_m");
		$this->load->model("setting_m");
        $this->load->model('studentrelation_m');
		$this->load->model('studentgroup_m');
		$this->load->model('studentextend_m');
		$this->load->model('subject_m');
        $language = $this->session->userdata('lang');
		$this->lang->load('student', $language);
    }

    protected function class_section_rules()
	{
		$rules = array(

			array(
				'field' => 'classesID',
				'label' => $this->lang->line("student_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classesID'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("student_section"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_sectionID'
			),

		);
		return $rules;
	}

    public function unique_classesID()
	{
		if ($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classesID", "The %s field is required");
			return false;
		}
		return true;
	}

	public function unique_sectionID()
	{
		if ($this->input->post('sectionID') == 0) {
			$this->form_validation->set_message("unique_sectionID", "The %s field is required");
			return false;
		}
		return true;
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
        $this->data['students'] = [];
        $this->data['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;
        $graduateclass = $this->data['siteinfos']->ex_class;


        $this->data['set_classes'] = 0;
        $this->data['set_section'] = 0;

        $this->data['sendClasses'] = [];
        $this->data['sendSection'] = [];

        $this->data['grades'] = $this->grade_m->get_order_by_grade();

        $classesID = $this->input->post("classesID");

        if ((int) $classesID) {
            $this->data['sections'] = $this->section_m->get_order_by_section(array('classesID' => $classesID));
        } else {
            $this->data['sections'] = [];
        }

        $this->data['classes'] = $this->classes_m->get_order_by_classes_except_kg(['classesID !=' => $graduateclass]);

        if ($_POST) {

            $rules = $this->class_section_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == false) {

                $this->data["subview"] = "student/add_optional_subject";
                $this->load->view('_layout_main', $this->data);
            } else {

                $classesID = $this->data['set_classes'] = $this->input->post('classesID');
                $sectionID = $this->data['set_section'] = $this->input->post('sectionID');

                $classes = $this->classes_m->get_single_classes(array('classesID' => $classesID));
                $section = $this->section_m->get_single_section(array('sectionID' => $sectionID));

                $markpercentageArr['marktypeID'] = $this->data['siteinfos']->marktypeID;
                $markpercentageArr['classesID'] = $classesID;


                $this->data['sendClasses'] = $classes;
                $this->data['sendSection'] = $section;

                $this->data['optional_subjects'] = $this->subject_m->get_optional_subject(['classesID' => $classesID]);

                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $studentArray = [
                    'srclassesID' => $classesID,
                    'srsectionID' => $sectionID,
                    'srschoolyearID' => $schoolyearID,
                ];


                $optionalArray1 = [];

                $this->data['students'] = $sendStudent = $this->studentrelation_m->get_order_by_student($studentArray, '', $optionalArray1);
                $this->data["subview"] = "student/add_optional_subject";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "student/add_optional_subject";
            $this->load->view('_layout_main', $this->data);
        }
      
    }

    public function add_optional_subject(Type $var = null)
    {

        $inputs = $this->input->post("subjects");
        $classID = $this->input->post("class");
        $sectionID = $this->input->post("section");
        $optionalSubjects = $studentExtendArray = array();

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $studentArray = [
            'srclassesID' => $classID,
            'srsectionID' => $sectionID,
            'srschoolyearID' => $schoolyearID,
        ];
        $optionalArray1 = [];
        $res = '';
        $this->data['students'] = $this->studentrelation_m->get_order_by_student($studentArray, '', $optionalArray1);

        if ($this->data['students']) {
            foreach ($this->data['students'] as $key => $value) {

                if (customCompute($inputs)) {
                    if (isset($inputs[$value->studentID])) {
                        if (isset($inputs[$value->studentID][2])) {
                            $retArray['error'] = true;
                            $retArray['render'] = 'error';
                            $retArray['message'] = 'Please select atleast one or two subject';
                            echo json_encode($retArray);
                            exit;
                        }
                        $optionalsubjectID = $inputs[$value->studentID][0];
                        $anotheroptionalsubjectId = isset($inputs[$value->studentID][1]) ? $inputs[$value->studentID][1] : 0;
                    } else {
                        $optionalsubjectID = '';
                        $anotheroptionalsubjectId = '';
                    }
                } else {
                    $optionalsubjectID = '';
                    $anotheroptionalsubjectId = '';
                }
                $optionalSubjects = array(
                    'srstudentID' => $value->studentID,
                    'srname' => $value->name,
                    'sroptionalsubjectID' => $optionalsubjectID,
                    'sranotheroptionalsubjectID' => $anotheroptionalsubjectId,

                );

                $studentExtendArray = [
                    'studentID' => $value->studentID,
                    'optionalsubjectID' =>   $optionalsubjectID,
                    'anotheroptionalsubjectID' =>  $anotheroptionalsubjectId,
                ];

                $this->studentrelation_m->update_studentrelation_by_studentID($optionalSubjects, $value->studentID);
                $res = $this->studentextend_m->update_studentextend_by_studentID($studentExtendArray, $value->studentID);
            }
            if ($res) {
                $retArray['status'] = true;
                $retArray['render'] = 'Success';
                $retArray['message'] = 'Successful';
                echo json_encode($retArray);
                exit;
            }
        } else {
            echo ('No students');
        }
    }
}
