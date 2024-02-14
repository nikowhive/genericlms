<?php

use \PhpOffice\PhpSpreadsheet\Spreadsheet;
use \PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\DataValidation;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Question_bank extends Admin_Controller
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
        $this->load->model("question_bank_m");
        $this->load->model("unit_m");
        $this->load->model("classes_m");
        $this->load->model("subject_m");
        $this->load->model("chapter_m");
        $this->load->model("question_group_m");
        $this->load->model("question_level_m");
        $this->load->model("question_type_m");
        $this->load->model("question_answer_m");
        $this->load->model("question_option_m");
        $this->load->model("online_exam_question_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('question_bank', $language);
    }

    public function index()
    {
        $this->data['groups']  = pluck($this->question_group_m->get_order_by_question_group(), 'obj', 'questionGroupID');
        $this->data['levels']  = pluck($this->question_level_m->get_order_by_question_level(), 'obj', 'questionLevelID');
        $this->data['types']   = pluck($this->question_type_m->get_order_by_question_type(), 'obj', 'questionTypeID');
        $this->data['question_banks'] = $this->question_bank_m->get_order_by_question_bank();
        $this->data['classes'] = $this->classes_m->get_classes();
        $this->data['subjects'] = [];
        $this->data["subview"] = "question/bank/index";
        $this->load->view('_layout_main', $this->data);
    }

    protected function rules($postOption)
    {
        $rules = array(
            array(
                'field' => 'unit_id',
                'label' => $this->lang->line("question_bank_unit"),
                'rules' => 'trim|numeric|required|xss_clean|greater_than[0]'
            ),
            array(
                'field' => 'chapter_id',
                'label' => $this->lang->line("question_bank_chapter"),
                'rules' => 'trim|numeric|required|xss_clean|greater_than[0]'
            ),
            array(
                'field' => 'group',
                'label' => $this->lang->line("question_bank_group"),
                'rules' => 'trim|numeric|required|xss_clean|callback_unique_group'
            ),
            array(
                'field' => 'level',
                'label' => $this->lang->line("question_bank_level"),
                'rules' => 'trim|numeric|required|xss_clean|callback_unique_level'
            ),
            array(
                'field' => 'question',
                'label' => $this->lang->line("question_bank_question"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'explanation',
                'label' => $this->lang->line("question_bank_explanation"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'photo',
                'label' => $this->lang->line("question_bank_image"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
            ),
            array(
                'field' => 'hints',
                'label' => $this->lang->line("question_bank_hints"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'mark',
                'label' => $this->lang->line("question_bank_mark"),
                'rules' => 'trim|xss_clean|numeric|required|greater_than[0]'
            ),
            array(
                'field' => 'type',
                'label' => $this->lang->line("question_bank_type"),
                'rules' => 'trim|required|xss_clean|callback_unique_type'
            )
        );

        if (customCompute($postOption) > 0) {
            $postOption = customCompute($postOption);
            $ruleForAns = 'trim|xss_clean';

            $j = 9;
            for ($i = 1; $i <= $postOption; $i++) {
                $rules[$j] = array(
                    'field' => 'option' . $i,
                    'label' => $this->lang->line("question_bank_option") . ' ' . $i,
                    'rules' => 'trim|xss_clean'
                );
                $j++;

                if ($i == 1) {
                    $ruleForAns = 'trim|xss_clean|callback_unique_answer|callback_valid_answer';
                } else {
                    $ruleForAns = 'trim|xss_clean';
                }

                $rules[$j] = array(
                    'field' => 'answer' . $i,
                    'label' => 'Answer' . ' ' . $i,
                    'rules' => $ruleForAns
                );
                $j++;
            }
        }

        return $rules;
    }

    protected function upload_rules($postOption)
    {
        $rules = array(
            array(
                'field' => 'chapter_id',
                'label' => $this->lang->line("question_bank_chapter"),
                'rules' => 'trim|numeric|required|xss_clean|greater_than[0]'
            ),
            array(
                'field' => 'group',
                'label' => $this->lang->line("question_bank_group"),
                'rules' => 'trim|numeric|required|xss_clean|callback_unique_group'
            ),
            array(
                'field' => 'level',
                'label' => $this->lang->line("question_bank_level"),
                'rules' => 'trim|numeric|required|xss_clean|callback_unique_level'
            ),
            array(
                'field' => 'question',
                'label' => $this->lang->line("question_bank_question"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'explanation',
                'label' => $this->lang->line("question_bank_explanation"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'photo',
                'label' => $this->lang->line("question_bank_image"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
            ),
            array(
                'field' => 'hints',
                'label' => $this->lang->line("question_bank_hints"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'mark',
                'label' => $this->lang->line("question_bank_mark"),
                'rules' => 'trim|xss_clean|numeric'
            ),
            array(
                'field' => 'type',
                'label' => $this->lang->line("question_bank_type"),
                'rules' => 'trim|required|xss_clean|callback_unique_type'
            )
        );

        if (customCompute($postOption) > 0) {
            $postOption = customCompute($postOption);
            $ruleForAns = 'trim|xss_clean';

            $j = 9;
            for ($i = 1; $i <= $postOption; $i++) {
                $rules[$j] = array(
                    'field' => 'option' . $i,
                    'label' => $this->lang->line("question_bank_option") . ' ' . $i,
                    'rules' => 'trim|xss_clean'
                );
                $j++;

                if ($i == 1) {
                    $ruleForAns = 'trim|xss_clean|callback_unique_answer|callback_valid_answer';
                } else {
                    $ruleForAns = 'trim|xss_clean';
                }

                $rules[$j] = array(
                    'field' => 'answer' . $i,
                    'label' => 'Answer' . ' ' . $i,
                    'rules' => $ruleForAns
                );
                $j++;
            }
        }

        return $rules;
    }

    public function send_mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("question_bank_to"),
                'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("question_bank_subject"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("question_bank_message"),
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'questionBankID',
                'label' => $this->lang->line("question_bank_questionBankID"),
                'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
            )
        );
        return $rules;
    }

    public function unique_data($data)
    {
        if ($data != '') {
            if ($data == '0') {
                $this->form_validation->set_message('unique_data', 'The %s field is required.');
                return FALSE;
            }
        }
        return TRUE;
    }

    public function photoupload()  
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        $question_bank = array();
        if ((int)$id) {
            $question_bank = $this->question_bank_m->get_question_bank($id);
        }

        $new_file = "default.png";
        if ($_FILES["photo"]['name'] != "") {
            $file_name = $_FILES["photo"]['name'];
            $uploadPath = 'uploads/images';
            $random = random19();
            $makeRandom = hash('sha512', $random . $_FILES["photo"]['name'] . date('Y-M-d-H:i:s') . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if (customCompute($explode) >= 2) {
                $new_file = $file_name_rename . '.' . end($explode);
                $config['upload_path'] = "./uploads/images";
                $config['allowed_types'] = "gif|jpg|jpeg|png";
                $config['file_name'] = $new_file;
                $_FILES['attach']['tmp_name'] = $_FILES['photo']['tmp_name'];
                $image_info = getimagesize($_FILES['photo']['tmp_name']);
				$image_width = $image_info[0];
				$image_height = $image_info[1];
                // $config['max_size'] = (1024 * 20);
                // $config['max_width'] = '3000';
                // $config['max_height'] = '3000';
                $this->load->library('upload');
                $this->upload->initialize($config);
                if (!$this->upload->do_upload("photo")) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $fileData = $this->upload->data();

                        resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height);

                    $this->upload_data['file'] =  $this->upload->data();
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return FALSE;
            }
        } else {
            if (customCompute($question_bank)) {
                $this->upload_data['file'] = array('file_name' => $question_bank->upload);
                return TRUE;
            } else {
                $this->upload_data['file'] = array('file_name' => '');
                return TRUE;
            }
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
                    $uploadPath = 'uploads/images';
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

                            resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height);
                            
                            $returnArray['success'][$imgkKey] = $new_file;
                        }
                    }
                }
            }
        }

        return $returnArray;
    }

    public function ajaxGetQuestionTypeNumberFromQuestionTypeId()
    {
        $question_type_id = $this->input->get('question_type_id');

        $type = $this->question_type_m->get_question_type_from_question_id($question_type_id);
        echo $type;
        return $type;
    }

    public function add()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                // 'assets/ckeditor/contents.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js'
            )
        );

        $quiz_id = htmlentities(escapeString($this->uri->segment(3)));
        $chapter_id = htmlentities(escapeString($this->uri->segment(4)));
        $usertypeID = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');
        $course = isset($_GET['course']) ? $_GET['course'] : '';

        $this->data['groups']  = $this->question_group_m->get_order_by_question_group();
        $this->data['levels']  = $this->question_level_m->get_order_by_question_level();
        $this->data['types']  = $this->question_type_m->get_order_by_question_type();
        $this->data['options'] = array();
        $this->data['answers'] = array();
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
        } else {
            $this->data['subjects'] = [];
            $this->data['chapters'] = [];
            $this->data['units'] = [];
        }
        $this->data['classes'] = $this->classes_m->get_classes();

        if ($_POST) {
            $postOption = $this->input->post("option");
            $rules = $this->rules($postOption);
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = $this->form_validation->error_array();
                $this->data['typeID'] = $this->input->post("type");
                $this->data['totalOptionID'] = $this->input->post("totalOption");
                $this->data['options'] = $this->input->post("option");
                $this->data['answers'] = $this->input->post("answer");
                $this->data["subview"] = "question/bank/add";
                $this->load->view('_layout_main', $this->data);
            } else {
                $imageUpload = array();
                $question_bank = array(
                    "groupID" => $this->input->post("group"),
                    "levelID" => $this->input->post("level"),
                    "question" => $this->input->post("question"),
                    "explanation" => $this->input->post("explanation"),
                    "hints" => $this->input->post("hints"),
                    "mark" => empty($this->input->post('mark')) ? NULL : $this->input->post('mark'),
                    "typeNumber" => $this->input->post("type"),
                    "totalOption" => $this->input->post("totalOption"),
                    "create_date" => date("Y-m-d H:i:s"),
                    "modify_date" => date("Y-m-d H:i:s"),
                    "create_userID" => $usertypeID,
                    "create_usertypeID" => $loginuserID,
                    "chapter_id" =>  $this->input->post("chapter_id"),
                    'type_id'   =>  $this->input->post('type_id')
                );
                // $imageval = $_FILES['photos']['name'];
                // $imagename = '';
                // if (!empty($imageval)) {
                //     $acceptable = array("doc", "docx", "pdf", "gif", "jpeg", "jpg", "png");
                //     $target_dir = "./uploads/images/";
                //     $totalcount = count($_FILES['photos']['name']);
                //     $filesname = '';
                //     for ($i = 0; $i < $totalcount; $i++) {
                //         $ext = explode(".", $_FILES["photos"]["name"][$i]);
                //         $extension = end($ext);
                //         if (in_array($extension, $acceptable)) {
                //             $new_file = $_FILES['photos']['name'][$i];
                            
                //             $temp = explode(".", $new_file);
                //             $newfilename = time() . '_' . $i . '.' . end($temp);
                //             $new_file = $newfilename;
                //             $target_file = $target_dir . $newfilename;
                //             $filesname .= ',' . $new_file;
                //             move_uploaded_file($_FILES["photos"]["tmp_name"][$i], $target_file);
                //         }
                //     }
                //     $filesname =  substr($filesname, 1);
                // }
                //echo $imagename;
                //die();
                //$question_bank['upload'] = $this->upload_data['file']['file_name'];
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
                                'questionID' => $questionInsertID
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
                            'img' => isset($imageUpload['success'][$key + 1]) ? $imageUpload['success'][$key + 1] : ''
                        ];
                        $this->question_option_m->update_question_option($data, $getQuestionOptions[$key]);
                        if (in_array($key + 1, $answers)) {
                            $ansData = [
                                'questionID' => $questionInsertID,
                                'optionID' => $getQuestionOptions[$key],
                                'typeNumber' => $this->input->post("type")
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
                            'typeNumber' => $this->input->post("type")
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
                        'typeNumber' => $this->input->post("type")
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
                        'typeNumber' => $this->input->post("type")
                    ];
                    $this->question_answer_m->insert_question_answer($ansData);
                    if ($totalOption != $this->input->post("totalOption")) { {
                            $this->question_bank_m->update_question_bank(['totalOption' => $totalOption], $questionInsertID);
                        }
                    }
                }


                if (isset($imageUpload['error'])) {
                    if (customCompute($imageUpload['error'])) {
                        $errorData = '';
                        foreach ($imageUpload['error'] as $imgErrorKey => $imgErrorValue) {
                            $optionErrors = implode(',', $imgErrorValue);
                            $errorData .= $imgErrorKey . ' : ' . $optionErrors . '<br/>';
                        }
                        $this->session->set_flashdata('error', $errorData);
                        redirect(base_url("question_bank/edit/$questionInsertID"));
                    } else {
                        if ($chapter_id) {
                            if ($quiz_id) {
                                redirect(base_url("courses/addquestion/" . $quiz_id . '/' . $chapter_id . '?course=' . $course));
                            }
                            redirect(base_url("courses/chapterdetails/" . $chapter_id));
                        } else {
                            redirect(base_url("question_bank/index"));
                        }
                    }
                } else {
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    if ($chapter_id) {
                        if ($quiz_id) {
                            redirect(base_url("courses/addquestion/" . $quiz_id . '/' . $chapter_id . '?course=' . $course));
                        }
                        redirect(base_url("courses/chapterdetails/" . $chapter_id));
                    } else {
                        redirect(base_url("question_bank/index"));
                    }
                }
            }
        } else {
            $this->data["subview"] = "question/bank/add";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function addafterchapter($chapter_id)
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js'
            )
        );
        $chapter_id = htmlentities(escapeString($this->uri->segment(3)));
        $usertypeID   = $this->session->userdata('usertypeID');
        $loginuserID  = $this->session->userdata('loginuserID');

        $this->data['groups']    = $this->question_group_m->get_order_by_question_group();
        $this->data['levels']    = $this->question_level_m->get_order_by_question_level();
        $this->data['types']     = $this->question_type_m->get_order_by_question_type();
        $this->data['options']   = [];
        $this->data['answers']   = [];
        $this->data['typeID']    = 0;
        $this->data['totalOptionID'] = 0;

        $objects = $this->question_bank_m->get_classes_from_chapter($chapter_id);

        $this->data['classes'] = $this->classes_m->get_classes();

        $this->data['set_class'] = $objects->classesID;
        $this->data['set_subject'] = $objects->subjectID;
        $this->data['set_chapter'] = $chapter_id;
        $this->data['chapters'] = $this->chapter_m->get_chapter_from_subject_id($this->data['set_subject']);
        $this->data['subjects'] = $this->subject_m->get_subjects_by_class_id($this->data['set_class']);




        if ($_POST) {

            $postOption = customCompute($this->input->post("option"));
            $rules = $this->rules($postOption);
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data['form_validation'] = $this->form_validation->error_array();
                $this->data['typeID']  = $this->input->post("type");
                $this->data['totalOptionID']   = $this->input->post("totalOption");
                $this->data['options'] = []; //$this->input->post("option");
                $this->data['answers'] = []; //$this->input->post("answer");
                $this->data["subview"] = "question/bank/addafterchapter";
                $this->load->view('_layout_main', $this->data);
            } else {
                $imageUpload = [];
                $question_bank = array(
                    "groupID" => $this->input->post("group"),
                    "levelID" => $this->input->post("level"),
                    "question" => $this->input->post("question"),
                    "explanation" => $this->input->post("explanation"),
                    "hints" => $this->input->post("hints"),
                    "mark" => empty($this->input->post('mark')) ? NULL : $this->input->post('mark'),
                    "typeNumber" => $this->input->post("type"),
                    "totalOption" => $this->input->post("totalOption"),
                    "create_date" => date("Y-m-d H:i:s"),
                    "modify_date" => date("Y-m-d H:i:s"),
                    "create_userID" => $usertypeID,
                    "create_usertypeID" => $loginuserID,
                    "chapter_id" =>  $this->input->post("chapter_id"),
                    'type_id'   =>  $this->input->post('type_id')
                );

                $question_bank['upload'] = $this->upload_data['file']['file_name'];

                $options = $this->input->post("option");
                $answers = $this->input->post("answer");
                $questionInsertID = $this->question_bank_m->insert_question_bank($question_bank);
                //echo $this->db->last_query();
                //die();
                if ($this->input->post("type") == 1 || $this->input->post("type") == 2) {
                    $imgArray = [];
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
                                'questionID' => $questionInsertID
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
                            'img' => isset($imageUpload['success'][$key + 1]) ? $imageUpload['success'][$key + 1] : ''
                        ];

                        $this->question_option_m->update_question_option($data, $getQuestionOptions[$key]);
                        if (in_array($key + 1, $answers)) {
                            $ansData = [
                                'questionID' => $questionInsertID,
                                'optionID' => $getQuestionOptions[$key],
                                'typeNumber' => $this->input->post("type")
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
                        if (empty($answer)) {
                            $totalOption--;
                            continue;
                        }
                        $ansData = [
                            'questionID' => $questionInsertID,
                            'text' => $answer,
                            'typeNumber' => $this->input->post("type")
                        ];
                        $this->question_answer_m->insert_question_answer($ansData);
                    }
                    if ($totalOption != $this->input->post("totalOption")) {
                        $this->question_bank_m->update_question_bank(['totalOption' => $totalOption], $questionInsertID);
                    }
                } elseif ($this->input->post("type") == 4) {
                    $totalOption = $this->input->post("totalOption");
                    // echo $questionInsertID;
                    // die();
                    $ansData = [
                        'questionID' => $questionInsertID,
                        'text' => $totalOption,
                        'typeNumber' => $this->input->post("type")
                    ];
                    $this->question_answer_m->insert_question_answer($ansData);


                    if ($totalOption != $this->input->post("totalOption")) { {
                            $this->question_bank_m->update_question_bank(['totalOption' => $totalOption], $questionInsertID);
                        }
                        //die();
                    }
                }
                if (isset($imageUpload['error'])) {
                    if (customCompute($imageUpload['error'])) {
                        $errorData = '';
                        foreach ($imageUpload['error'] as $imgErrorKey => $imgErrorValue) {
                            $optionErrors = implode(',', $imgErrorValue);
                            $errorData .= $imgErrorKey . ' : ' . $optionErrors . '<br/>';
                        }
                        $this->session->set_flashdata('error', $errorData);
                        redirect(base_url("question_bank/edit/$questionInsertID"));
                    } else {
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        redirect(base_url("courses/addquizzes/" . $chapter_id));
                    }
                } else {
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("courses/addquizzes/" . $chapter_id));
                }
            }
        } else {
            $this->data["subview"] = "question/bank/addafterchapter";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function download_excel()
    {
        $subject_id = $this->input->get('subject_id');

        $subject_id = (int) $subject_id;
        if ($subject_id == 0) {
            $this->session->set_flashdata('error', 'Subject is not selected');
            redirect(base_url("question_bank/index"));
        }
        $chapters = $this->chapter_m->get_chapter_from_subject_id($subject_id);
        if(!customCompute($chapters)){
            $this->session->set_flashdata('error', 'Data not found.');
            redirect(base_url("question_bank/index"));
        }

        $chapter_names = [];
        $chapter_ids = [];
        $max_row = 0;
        foreach ($chapters as $index => $c) {
            $chapter_names[] = str_replace(',','', $c->chapter_name);
            $chapter_ids[] = $c->id;
        }
        $max_row = $index > $max_row ? $index : $max_row;

        $question_groups = $this->question_group_m->get_question_group();
        $question_group_id = [];
        $question_group_title = [];
        foreach ($question_groups as $index => $q) {
            $question_group_id[] = $q->questionGroupID;
            $question_group_title[] = str_replace(',','', $q->title);
        }
        $max_row = $index > $max_row ? $index : $max_row;

        $question_levels = $this->question_level_m->get_question_level();
        $question_level_id = [];
        $difficulty_level = [];
        foreach ($question_levels as $index => $q) {
            $question_level_id[] = $q->questionLevelID;
            $difficulty_level[] = str_replace(',','', $q->name);
        }
        $max_row = $index > $max_row ? $index : $max_row;

        $question_types = $this->question_type_m->get_question_type();
        $question_type_id = [];
        $question_type_number = [];
        $question_type_names = [];
        foreach ($question_types as $index => $q) {
            $question_type_id[] = $q->questionTypeID;
            $question_type_number[] = $q->typeNumber;
            $question_type_names[] = str_replace(',','', $q->name);
        }
        $max_row = $index > $max_row ? $index : $max_row;

        $rows[] = ['chapter_id', 'Chapter', 'questionGroupID', 'Question Group',  'questionLevelID', 'Difficulty Level',    'questionTypeID',  'Question Type',  'Question Type Number'];

        for ($i = 0; $i <= $max_row; $i++) {
            $row[] = isset($chapter_ids[$i]) ? $chapter_ids[$i] : NULL;
            $row[] = isset($chapter_names[$i]) ? $chapter_names[$i] : NULL;
            $row[] = isset($question_group_id[$i]) ? $question_group_id[$i] : NULL;
            $row[] = isset($question_group_title[$i]) ? $question_group_title[$i] : NULL;
            $row[] = isset($question_level_id[$i]) ? $question_level_id[$i] : NULL;
            $row[] = isset($difficulty_level[$i]) ? $difficulty_level[$i] : NULL;
            $row[] = isset($question_type_id[$i]) ? $question_type_id[$i] : NULL;
            $row[] = isset($question_type_names[$i]) ? $question_type_names[$i] : NULL;
            $row[] = isset($question_type_number[$i]) ? $question_type_number[$i] : NULL;
            $rows[] = $row;
            $row = [];
        }

        //$online_exam_question_m
        $return = [
            [
                'title' =>  'Rule',
                'data'  =>  $rows
            ],
            [
                'title' =>  'Data',
                'data'  =>  [

                    ['SN', 'Chapter', 'Question Group',  'Difficulty Level',    'Question',    'Explanation', 'Hints',   'Mark',    'Question Type',   'Option 1',    'Option 2',    'Option 3',    'Option 4',    'Option 5',    'Correct Answer']
                ]
            ]
        ];

        $dropdowns = [
            [
                'fromCell' => 'B',
                'cell' => 'B',
                'start' =>  2,
                'end'  =>  100,
                'data'  =>  $chapter_names,
                'count' => count($chapter_names) + 1
            ],

            [
                'fromCell' => 'D',
                'cell' => 'C',
                'start' =>  2,
                'end'  =>  100,
                'data'  =>  $question_group_title,
                'count' => count($question_group_title) + 1
            ],

            [
                'fromCell' => 'F',
                'cell' => 'D',
                'start' =>  2,
                'end'  =>  100,
                'data'  =>  $difficulty_level,
                'count' => count($difficulty_level) + 1
            ],

            [
                'fromCell' => 'H',
                'cell' => 'I',
                'start' =>  2,
                'end'  =>  100,
                'data'  =>  $question_type_names,
                'count' => count($question_type_names) + 1
            ],
        ];

        $this->apiDownloadExcel($return, $filename = 'excel', $dropdowns);
    }

    public function upload_excel()
    {
        $return = [];
        $file_ext = pathinfo($_FILES['excel']['name'], PATHINFO_EXTENSION);

        if ($file_ext == 'xls' || $file_ext == 'xlsx') {
            try {
                $spreadsheet = IOFactory::load($_FILES['excel']['tmp_name']);
            } catch (Exception $e) {
            }

            $sheets = $spreadsheet->getSheetNames();
            foreach ($sheets as $sheetname) {
                $worksheet = $spreadsheet->getSheetByName($sheetname);
                $no_of_rows = $worksheet->getHighestDataRow();
                $no_of_columns = $worksheet->getHighestDataColumn();
                $no_of_columns = Coordinate::columnIndexFromString($no_of_columns);
                $data = [];

                for ($currentRow = 1; $currentRow <= $no_of_rows; $currentRow++) {
                    for ($currentCol = 1; $currentCol <= $no_of_columns; $currentCol++) {
                        $data[$currentRow - 1][$currentCol - 1] = $worksheet->getCellByColumnAndRow($currentCol, $currentRow)->getCalculatedValue();
                    }
                }

                $data = $this->convertRow($data, true);
                $return[$sheetname] = $data;
            }

            $chapters = [];
            $question_groups = [];
            $difficulty_level = [];
            $question_types = [];
         
            foreach ($return['Rule'] as $index => $row) {

                if (strlen($row['Chapter']) && strlen($row['chapter_id'])) {
                    $chapters[strtolower($row['Chapter'])] = $row['chapter_id'];
                }

                if (strlen($row['Question Group']) && strlen($row['questionGroupID'])) {
                    $question_groups[strtolower($row['Question Group'])] = $row['questionGroupID'];
                }

                if (strlen($row['Difficulty Level']) && strlen($row['questionLevelID'])) {
                    $difficulty_level[strtolower($row['Difficulty Level'])] = $row['questionLevelID'];
                }

                if (strlen($row['Question Type']) && strlen($row['Question Type Number']) && strlen($row['questionTypeID'])) {
                    $question_types[strtolower($row['Question Type'])] = [
                        'type_id'   =>  $row['questionTypeID'],
                        'type'  =>  $row['Question Type Number']
                    ];
                }
            }

            $data_to_store = [];
            foreach ($return['Data'] as $index => $row) {
                $data = $row;
                $data['chapter_id'] = isset($chapters[strtolower($row['Chapter'])]) ? $chapters[strtolower($row['Chapter'])] : NULL;
                $data['group'] = isset($question_groups[strtolower($row['Question Group'])]) ? $question_groups[strtolower($row['Question Group'])] : NULL;
                $data['level'] = isset($difficulty_level[strtolower($row['Difficulty Level'])]) ? $difficulty_level[strtolower($row['Difficulty Level'])] : NULL;
                $data['type'] = isset($question_types[strtolower($row['Question Type'])]) ? $question_types[strtolower($row['Question Type'])]['type'] : NULL;
                $data['type_id'] = isset($question_types[strtolower($row['Question Type'])]) ? $question_types[strtolower($row['Question Type'])]['type_id'] : NULL;
                $data['question'] = $row['Question'];
                $data['explanation'] = $row['Explanation'];
                $data['hints'] = $row['Hints'];
                $data['mark'] = $row['Mark'];

                $data = $this->convertRowToData($data);
                if (strlen($data['question'])) {
                    $data_to_store[] = $data;
                }
            }

            $this->db->trans_begin();

            $errors = '';
            $prabal_error = "";
            foreach ($data_to_store as $index => $data) {
                $message = $this->apiAdd($data);
                if ($message['status'] == false) {
                    $errors = $message['message'];
                    $prabal_error = 'Error in row ' . ($index + 2) . "<br>" . $message['data'];
                    break;
                }
            }

            if ($this->db->trans_status() === FALSE || $errors) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }

            if (strlen($errors)) {
                // $errors = iniArrayToString($errors);
                $this->session->set_flashdata('error', $errors);
                if ($prabal_error) {
                    $this->session->set_flashdata('prabal_error', $prabal_error);
                }
            } else {
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
            }

            redirect(base_url("question_bank/index"));
        } else {

            // $this->form_validation->set_message('upload_excel', 'Please upload only excel file.');
            $this->session->set_flashdata('error', 'Please upload only excel file.');
            redirect(base_url("question_bank/index"));
        }
    }

    public function apiAdd($post_data)
    {

        $usertypeID   = $this->session->userdata('usertypeID');
        $loginuserID  = $this->session->userdata('loginuserID');
        $postOption = isset($post_data['option'])?customCompute($post_data['option']):0;
        $rules = $this->upload_rules($postOption);
        unset($rules[5]);

        $this->form_validation->set_rules($rules);

        foreach ($post_data as $index => $value) {
            $_POST[$index] = $value;
        }

        $this->form_validation->set_data($post_data);
        if ($this->form_validation->run() == FALSE) {
            $this->data['form_validation'] = $this->form_validation->error_array();

            return $response = [
                'status'    =>  false,
                'message'   =>  'There are some validation errors in row',
                'data'  =>  implode("<br>", $this->data['form_validation'])
            ];
        } else {
            $imageUpload = [];
            $question_bank = array(
                "groupID" => $post_data['group'],
                "levelID" => $post_data['level'],
                "question" => $post_data['question'],
                "explanation" => $post_data['explanation'],
                "hints" => $post_data['hints'],
                "mark" => $post_data['mark'],
                "typeNumber" => $post_data['type'],
                "totalOption" => isset($post_data['totalOption'])?$post_data['totalOption']:null,
                "create_date" => date("Y-m-d H:i:s"),
                "modify_date" => date("Y-m-d H:i:s"),
                "create_userID" => $usertypeID,
                "create_usertypeID" => $loginuserID,
                "chapter_id" =>  $post_data['chapter_id'],
                'type_id'   =>  $post_data['type_id']
            );

            $options = $post_data['option'];
            $answers = $post_data['answer'];
            $questionInsertID = $this->question_bank_m->insert_question_bank($question_bank);

            if ($post_data['type'] == 1 || $post_data['type'] == 2) {

                $getQuestionOptions = pluck($this->question_option_m->get_order_by_question_option(['questionID' => $questionInsertID]), 'optionID');

                if (!customCompute($getQuestionOptions)) {
                    foreach (range(1, 10) as $optionID) {

                        $data = [
                            'name' => '',
                            'questionID' => $questionInsertID
                        ];
                        $getQuestionOptions[] = $this->question_option_m->insert_question_option($data);
                    }
                }

                $totalOption = $post_data['totalOption'];
                foreach ($options as $key => $option) {
                    if ($option == '') {
                        $totalOption--;
                        continue;
                    }

                    $data = [
                        'name' => $option,
                        'img' =>  ''
                    ];

                    $this->question_option_m->update_question_option($data, $getQuestionOptions[$key]);
                    if (in_array($key + 1, $answers)) {
                        $ansData = [
                            'questionID' => $questionInsertID,
                            'optionID' => $getQuestionOptions[$key],
                            'typeNumber' => $post_data['type']
                        ];
                        $this->question_answer_m->insert_question_answer($ansData);
                    }
                }


                if ($totalOption != $post_data['totalOption']) {
                    $this->question_bank_m->update_question_bank(['totalOption' => $totalOption], $questionInsertID);
                }
            } elseif ($post_data['type'] == 3) {
                $totalOption = $post_data['totalOption'];
                foreach ($answers as $answer) {
                    if (empty($answer)) {
                        $totalOption--;
                        continue;
                    }
                    $ansData = [
                        'questionID' => $questionInsertID,
                        'text' => $answer,
                        'typeNumber' => $post_data['type']
                    ];
                    $this->question_answer_m->insert_question_answer($ansData);
                }
                if ($totalOption != $post_data['totalOption']) {
                    $this->question_bank_m->update_question_bank(['totalOption' => $totalOption], $questionInsertID);
                }
            }elseif ($this->input->post("type") == 4) {
                $totalOption = 'long';
                $ansData = [
                    'questionID' => $questionInsertID,
                    'text' => $totalOption,
                    'typeNumber' => $post_data['type']
                ];
                $this->question_answer_m->insert_question_answer($ansData);

            } elseif ($this->input->post("type") == 5) {
                $ansData = [
                    'questionID' => $questionInsertID,
                    'text' => 'accumulative',
                    'typeNumber' => $post_data['type']
                ];
                $this->question_answer_m->insert_question_answer($ansData);
            }

            return $response = [
                'status'    =>  true,
                'message'   =>  'Successfully added question',
                'data'  =>  []
            ];
        }
    }

    public function edit()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/datepicker.css',
                'assets/editor/jquery-te-1.4.0.css',
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/editor/jquery-te-1.4.0.min.js',
                'assets/datepicker/datepicker.js',
                'assets/select2/select2.js'
            )
        );
        $questionID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$questionID) {
            $this->data['question_bank'] = $this->question_bank_m->get_single_question_bank(array('questionBankID' => $questionID));
            if ($this->data['question_bank']) {
                $this->data['typeID'] = $this->data['question_bank']->typeNumber;
                $this->data['totalOptionID'] = $this->input->post("totalOption");
                $this->data['dbTotalOptionID'] = pluck($this->question_option_m->get_order_by_question_option(['questionID' => $questionID, 'name !=' => '']), 'name', 'optionID');

                $this->data['groups']  = $this->question_group_m->get_order_by_question_group();
                $this->data['levels']  = $this->question_level_m->get_order_by_question_level();
                $this->data['types']   = $this->question_type_m->get_order_by_question_type();

                $this->data['classes'] = $this->classes_m->get_classes();
                $this->data['set_chapter'] = $this->data['question_bank']->chapter_id;
                $this->data['set_unit'] = $this->chapter_m->get_unit_from_chapter_id($this->data['question_bank']->chapter_id);
                $this->data['set_subject'] = $this->chapter_m->get_subject_from_chapter_id($this->data['question_bank']->chapter_id);
                $this->data['set_class'] = $this->subject_m->get_class_id_from_subject($this->data['set_subject']);

                $this->data['chapters'] = $this->chapter_m->get_chapter_from_subject_id($this->data['set_subject']);
                $this->data['units'] = $this->unit_m->get_units_from_chapter_id($this->data['set_unit']);
                $this->data['subjects'] = $this->subject_m->get_subjects_by_class_id($this->data['set_class']);

                $this->data['options'] = pluck($this->question_option_m->get_order_by_question_option(['questionID' => $questionID]), 'name', 'optionID');

                if ($this->data['question_bank']->typeNumber == 1 || $this->data['question_bank']->typeNumber == 2) {
                    $this->data['answers'] = pluck($this->question_answer_m->get_order_by_question_answer(['questionID' => $questionID]), 'optionID');
                } elseif ($this->data['question_bank']->typeNumber == 3) {
                    $this->data['answers'] = pluck($this->question_answer_m->get_order_by_question_answer(['questionID' => $questionID]), 'text');
                }

                $this->data['f'] = 0;
                if ($_POST) {
                    $postOption = $this->input->post("option");
                    $rules = $this->rules($postOption = 0);
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data['typeID']   = $this->input->post("type");
                        $this->data['postData'] = 1;
                        $this->data['options']  = $this->input->post("option");
                        $this->data['answers']  = $this->input->post("answer");
                        $this->data['totalOptionID'] = $this->input->post("totalOption");
                        $this->data['f'] = 1;

                        $this->session->set_flashdata('error', iniArrayToString($this->form_validation->error_array()));
                        $this->data["subview"] = "question/bank/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $imageUpload = [];
                        $question_bank = array(
                            "groupID" => $this->input->post("group"),
                            "levelID" => $this->input->post("level"),
                            "question" => $this->input->post("question"),
                            "explanation" => $this->input->post("explanation"),
                            "hints" => $this->input->post("hints"),
                            "mark" => empty($this->input->post('mark')) ? NULL : $this->input->post('mark'),
                            "typeNumber" => $this->input->post("type"),
                            "totalOption" => $this->input->post("totalOption"),
                            "modify_date" => date("Y-m-d H:i:s"),
                            'chapter_id'    =>  $this->input->post('chapter_id'),
                            'type_id'   =>  $this->input->post('type_id')
                        );
                        // $imageval = $_FILES['photos']['name'];
                        // $imagename = '';
                        // if (!empty($imageval)) {

                        //     $acceptable = array("doc", "docx", "pdf", "gif", "jpeg", "jpg", "png");
                        //     $target_dir = "./uploads/images/";
                        //     $totalcount = count($_FILES['photos']['name']);
                        //     $filesname = '';
                        //     for ($i = 0; $i < $totalcount; $i++) {

                        //         $ext = explode(".", $_FILES["photos"]["name"][$i]);
                        //         $extension = end($ext);
                        //         if (in_array($extension, $acceptable)) {
                        //             $new_file = $_FILES['photos']['name'][$i];
                        //             $temp = explode(".", $new_file);
                        //             $newfilename = time() . '_' . $i . '.' . end($temp);
                        //             $new_file = $newfilename;
                        //             $target_file = $target_dir . $newfilename;
                        //             $filesname .= ',' . $new_file;
                        //             move_uploaded_file($_FILES["photos"]["tmp_name"][$i], $target_file);
                        //         }
                        //     }
                        //     $filesname =  substr($filesname, 1);
                        // }
                        // //$question_bank['upload'] = $this->upload_data['file']['file_name'];
                        $question_bank['upload'] = $filesname;
                        $options = $this->input->post("option");
                        $answers = $this->input->post("answer");

                        if ($this->input->post("type") == 1 || $this->input->post("type") == 2) {

                            $imgArray = [];
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
                            $questionOptionModel = $this->question_option_m->get_order_by_question_option(['questionID' => $questionID]);

                            $getQuestionOptions = pluck($questionOptionModel, 'optionID');
                            $getQuestionAnswers = pluck($this->question_answer_m->get_order_by_question_answer(['questionID' => $questionID]), 'optionID', 'answerID');

                            $getQuestionOptionsImages = pluck($questionOptionModel, 'img');
                            $totalOption     = $this->input->post("totalOption");
                            $corrcetAnswer   = [];
                            $questionOptions = [];

                            if (customCompute($questionOptionModel)) {
                                $countOption = customCompute($options);
                                $k = 1;
                                foreach ($questionOptionModel as $key => $questionOption) {
                                    if ($countOption < $k) {
                                        $this->question_option_m->update_question_option(array('name' => '', 'img' => NULL), $questionOption->optionID);
                                    }
                                    $k++;
                                }
                            }

                            if (!customCompute($getQuestionOptions)) {
                                $this->question_answer_m->delete_question_answer_by_questionID($questionID);
                            }


                            foreach ($options as $key => $option) {
                                if (($option == '') && (array_key_exists($key, $getQuestionOptionsImages) && ($getQuestionOptionsImages[$key] == '' || $getQuestionOptionsImages[$key] === null) && !customCompute($imageUpload))) {
                                    $totalOption--;
                                    continue;
                                }

                                $data = [
                                    'name' => $option,
                                ];

                                if (isset($imageUpload['success'][$key + 1])) {
                                    $data['img'] = isset($imageUpload['success'][$key + 1]) ? $imageUpload['success'][$key + 1] : '';
                                }

                                if (isset($getQuestionOptions[$key])) {
                                    $this->question_option_m->update_question_option($data, $getQuestionOptions[$key]);
                                } else {
                                    $data['questionID'] = $questionID;
                                    $questionOptions[] =  $this->question_option_m->insert_question_option($data);
                                }

                                if (in_array($key + 1, $answers)) {
                                    if (customCompute($getQuestionOptions)) {
                                        $corrcetAnswer[] = $getQuestionOptions[$key];
                                    } else {
                                        $ansData = [
                                            'questionID' => $questionID,
                                            'optionID' => $questionOptions[$key],
                                            'typeNumber' => $this->input->post("type")
                                        ];
                                        $this->question_answer_m->insert_question_answer($ansData);
                                    }
                                }
                            }

                            if ($totalOption != $this->input->post("totalOption")) {
                                $question_bank['totalOption'] = $totalOption;
                            }
                            $this->question_bank_m->update_question_bank($question_bank, $questionID);

                            if (customCompute($getQuestionOptions)) {
                                $i = 0;
                                foreach ($getQuestionAnswers as $answerID => $optionID) {
                                    if (isset($corrcetAnswer[$i])) {
                                        $this->question_answer_m->update_question_answer(['optionID' => $corrcetAnswer[$i]], $answerID);
                                    } else {
                                        $this->question_answer_m->delete_question_answer($answerID);
                                    }
                                    $i++;
                                }
                                $countOfCorrectAnswer = customCompute($corrcetAnswer);
                                for ($j = $i; $j < $countOfCorrectAnswer; $j++) {
                                    $ansData = [
                                        'questionID' => $questionID,
                                        'optionID' => $getQuestionOptions[$j],
                                        'typeNumber' => $this->input->post("type")
                                    ];
                                    $this->question_answer_m->insert_question_answer($ansData);
                                }
                            }
                        } elseif ($this->input->post("type") == 3) {
                            $getQuestionAnswers = pluck($this->question_answer_m->get_order_by_question_answer(['questionID' => $questionID]), 'text', 'answerID');

                            if (customCompute($this->data['options'])) {
                                $optionsArray = [];
                                foreach ($this->data['options'] as $optionKey => $option) {
                                    $optionsArray[] =  $optionKey;
                                }
                                if (customCompute($optionsArray)) {
                                    $this->question_option_m->delete_batch_option($optionsArray);
                                }
                            }


                            $i = 0;
                            $totalOption = 0;
                            foreach ($getQuestionAnswers as $answerID => $text) {
                                if (isset($answers[$i]) && $answers[$i] != '') {
                                    $totalOption++;
                                    $this->question_answer_m->update_question_answer(['optionID' => NULL, 'typeNumber' => $this->input->post("type"), 'text' => $answers[$i]], $answerID);
                                } else {
                                    $this->question_answer_m->delete_question_answer($answerID);
                                }
                                $i++;
                            }

                            for ($j = $i; $j < customCompute($answers); $j++) {
                                $ansData = [
                                    'questionID' => $questionID,
                                    'text' => $answers[$j],
                                    'typeNumber' => $this->input->post("type")
                                ];
                                $this->question_answer_m->insert_question_answer($ansData);
                                $totalOption++;
                            }

                            if ($totalOption != $this->input->post("totalOption")) {
                                $question_bank['totalOption'] = $totalOption;
                            }
                            $this->question_bank_m->update_question_bank($question_bank, $questionID);
                        } elseif ($this->input->post("type") == 4) {
                            $getQuestionAnswers = pluck($this->question_answer_m->get_order_by_question_answer(['questionID' => $questionID]), 'text', 'answerID');
                            if (!empty($getQuestionAnswers)) {
                                foreach ($getQuestionAnswers as $answerID => $text) {
                                    if ($text != '') {
                                        $this->question_answer_m->update_question_answer(['optionID' => NULL, 'typeNumber' => $this->input->post("type"), 'text' => $_POST['totalOption']], $answerID);
                                    } else {
                                        $this->question_answer_m->delete_question_answer($answerID);
                                        $ansData = [
                                            'questionID' => $questionID,
                                            'text' => $this->input->post("totalOption"),
                                            'typeNumber' => $this->input->post("type")
                                        ];
                                        $this->question_answer_m->insert_question_answer($ansData);
                                    }
                                }
                            } else {
                                $ansData = [
                                    'questionID' => $questionID,
                                    'text' => $this->input->post("totalOption"),
                                    'typeNumber' => $this->input->post("type")
                                ];
                                $this->question_answer_m->insert_question_answer($ansData);
                            }
                            $question_bank['totalOption'] = $_POST['totalOption'];
                            $this->question_bank_m->update_question_bank($question_bank, $questionID);
                        }

                        if (isset($imageUpload['error'])) {
                            if (customCompute($imageUpload['error'])) {
                                $errorData = '';
                                foreach ($imageUpload['error'] as $imgErrorKey => $imgErrorValue) {
                                    $optionErrors = implode(',', $imgErrorValue);
                                    $errorData .= $imgErrorKey . ' : ' . $optionErrors . '<br/>';
                                }
                                $this->session->set_flashdata('error', $errorData);
                                redirect(base_url("question_bank/edit/$questionID"));
                            } else {
                                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                                redirect(base_url("question_bank/index"));
                            }
                        } else {
                            $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                            redirect(base_url("question_bank/index"));
                        }
                    }
                } else {
                    $this->data["subview"] = "question/bank/edit";
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

    public function view()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/checkbox/checkbox.css',
            )
        );
        $questionID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$questionID) {
            $questionBank = $this->question_bank_m->get_single_question_bank(array('questionBankID' => $questionID));
            $this->data['question'] =  $questionBank;
            if (customCompute($questionBank)) {
                $allOptions = $this->question_option_m->get_order_by_question_option(array('questionID' => $questionBank->questionBankID));
                $options = [];
                $oc = 1;
                $ocOption = $questionBank->totalOption;
                foreach ($allOptions as $option) {
                    if ($option->name == "" && $option->img == "") continue;
                    if ($ocOption >= $oc) {
                        $options[$option->questionID][] = $option;
                        $oc++;
                    }
                }
                $this->data['options'] = $options;
                $allAnswers = $this->question_answer_m->get_order_by_question_answer(array('questionID' => $questionID));
                $answers = [];
                foreach ($allAnswers as $answer) {
                    $answers[$answer->questionID][] = $answer;
                }
                $this->data['answers'] = $answers;
                $this->data["subview"] = "question/bank/view";
                $this->load->view('_layout_main', $this->data);
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
        if ((int)$id) {
            $this->data['question_bank'] = $this->question_bank_m->get_single_question_bank(array('questionBankID' => $id));
            if (customCompute($this->data['question_bank'])) {
                $this->question_bank_m->delete_question_bank($id);
                $onlineExamQuestions = $this->online_exam_question_m->get_order_by_online_exam_question(array('questionID' => $id));

                if (customCompute($onlineExamQuestions)) {
                    foreach ($onlineExamQuestions as $onlineExamQuestion) {
                        $this->online_exam_question_m->delete_online_exam_question($onlineExamQuestion->onlineExamQuestionID);
                    }
                }

                $questionOptions = pluck($this->question_option_m->get_order_by_question_option(array('questionID' => $id)), 'optionID');
                $questionAnswers = pluck($this->question_answer_m->get_order_by_question_answer(array('questionID' => $id)), 'answerID');

                if (customCompute($questionOptions)) {
                    $this->question_option_m->delete_batch_option($questionOptions);
                }

                if (customCompute($questionAnswers)) {
                    $this->question_answer_m->delete_batch_question_answer($questionAnswers);
                }

                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url("question_bank/index"));
            } else {
                redirect(base_url("question_bank/index"));
            }
        } else {
            redirect(base_url("question_bank/index"));
        }
    }

    public function print_preview()
    {
        if (permissionChecker('question_bank_view')) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if ((int)$id) {
                $questionBank = $this->question_bank_m->get_single_question_bank(array('questionBankID' => $id));
                $this->data['question'] =  $questionBank;
                if (customCompute($questionBank)) {
                    $allOptions = $this->question_option_m->get_order_by_question_option(array('questionID' => $questionBank->questionBankID));
                    $options = [];
                    $oc = 1;
                    $ocOption = $questionBank->totalOption;
                    foreach ($allOptions as $option) {
                        if ($option->name == "" && $option->img == "") continue;
                        if ($ocOption >= $oc) {
                            $options[$option->questionID][] = $option;
                            $oc++;
                        }
                    }
                    $this->data['options'] = $options;
                    $allAnswers = $this->question_answer_m->get_order_by_question_answer(array('questionID' => $id));
                    $answers = [];
                    foreach ($allAnswers as $answer) {
                        $answers[$answer->questionID][] = $answer;
                    }

                    $this->data['answers'] = $answers;
                    $this->data['panel_title'] = $this->lang->line('panel_title');
                    $this->reportPDF('questionbankmodule.css', $this->data, 'question/bank/print_preview');
                } else {
                    $this->data["subview"] = "error";
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

    public function send_mail()
    {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if (permissionChecker('question_bank_view')) {
            if ($_POST) {
                $rules = $this->send_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $id = $this->input->post('questionBankID');
                    if ((int)$id) {
                        $questionBank = $this->question_bank_m->get_single_question_bank(array('questionBankID' => $id));
                        $this->data['question'] =  $questionBank;
                        if (customCompute($questionBank)) {
                            $allOptions = $this->question_option_m->get_order_by_question_option(array('questionID' => $questionBank->questionBankID));
                            $options = [];
                            $oc = 1;
                            $ocOption = $questionBank->totalOption;
                            foreach ($allOptions as $option) {
                                if ($option->name == "" && $option->img == "") continue;
                                if ($ocOption >= $oc) {
                                    $options[$option->questionID][] = $option;
                                    $oc++;
                                }
                            }
                            $this->data['options'] = $options;
                            $allAnswers = $this->question_answer_m->get_order_by_question_answer(array('questionID' => $id));
                            $answers = [];
                            foreach ($allAnswers as $answer) {
                                $answers[$answer->questionID][] = $answer;
                            }
                            $this->data['answers'] = $answers;

                            $this->data['panel_title'] = $this->lang->line('panel_title');
                            $email = $this->input->post('to');
                            $subject = $this->input->post('subject');
                            $message = $this->input->post('message');

                            $this->reportSendToMail('questionbankmodule.css', $this->data, 'question/bank/print_preview', $email, $subject, $message);
                            $retArray['message'] = "Message";
                            $retArray['status'] = TRUE;
                            echo json_encode($retArray);
                            exit;
                        } else {
                            $retArray['message'] = $this->lang->line('question_bank_data_not_found');
                            echo json_encode($retArray);
                            exit;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('question_bank_data_not_found');
                        echo json_encode($retArray);
                        exit;
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('question_bank_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('question_bank_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    public function unique_group()
    {
        if ($this->input->post('group') == 0) {
            $this->form_validation->set_message("unique_group", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_level()
    {
        if ($this->input->post('level') == 0) {
            $this->form_validation->set_message("unique_level", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_type()
    {
        if ($this->input->post('type') == 0) {
            $this->form_validation->set_message("unique_type", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_total_option()
    {
        if ($this->input->post('totalOption') == 0) {
            $this->form_validation->set_message("unique_total_option", "The %s field is required");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_answer()
    {
        if ($this->input->post('type') == 3) {
            $f = 0;
            if (customCompute($this->input->post("answer"))) {
                foreach ($this->input->post("answer") as $value) {
                    if ($value != '') {
                        $f = 1;
                    }
                }
            }
            if ($f != 1) {
                $this->form_validation->set_message("unique_answer", "Please Select Atleast one Answer");
                return FALSE;
            }
            return TRUE;
        } else {
            if (customCompute($this->input->post('answer')) <= 0) {
                $this->form_validation->set_message("unique_answer", "Please Select Atleast one Answer");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function valid_answer()
    {
        $type     = $this->input->post('type');
        $answers  = $this->input->post('answer');
        $options  = $this->input->post('option');

        $retArr   = [];
        if ($type != 3) {
            $questionID = htmlentities(escapeString($this->uri->segment(3)));
            $optionsDB  = [];
            if ((int)$questionID) {
                $optionsDB  = $this->question_option_m->get_order_by_question_option(['questionID' => $questionID]);
            }


            if (customCompute($options)) {
                foreach ($options as $key => $option) {
                    $key++;
                    if ($option != '') {
                        $retArr[$key] = $key;
                    }

                    if (isset($_FILES['image' . $key]['name']) && $_FILES['image' . $key]['name'] != '') {
                        $retArr[$key] = $key;
                    }

                    if (customCompute($optionsDB)) {
                        $dbKey = $key;
                        $dbKey--;
                        if (isset($optionsDB[$dbKey])) {
                            if ($optionsDB[$dbKey]->img != '') {
                                $retArr[$key] = $key;
                            }
                        }
                    }
                }
            }
            if (customCompute($answers) && customCompute($retArr)) {
                $f = 0;
                foreach ($answers as $answer) {
                    if (in_array($answer, $retArr)) {
                        $f = 1;
                    }
                }
                if (!$f) {
                    $this->form_validation->set_message("valid_answer", "Please Select Atleast one valid Answer");
                    return FALSE;
                }
            } else {
                $this->form_validation->set_message("valid_answer", "Please Select Atleast one valid Answer");
                return FALSE;
            }
        }
        return TRUE;
    }

    private function convertRowToData($row)
    {
        if ($row['type_id'] == 1 || $row['type_id'] == 2) {
            $row['option'] = [];
            for ($i = 1; $i <= 5; $i++) {
                if (strlen($row['Option ' . $i])) {
                    $row['option'][] = $row['Option ' . $i];
                }
            }

            $answers = explode(';', $row['Correct Answer']);
            $row['answer'] = [];
            foreach ($answers as $answer) {
                $row['answer'][] = $answer;
            }
            $row['totalOption'] = count($row['option']);
        } elseif ($row['type_id'] == 3) {
            $row['answer'] = explode(';', $row['Correct Answer']);
            $row['totalOption'] = count($row['answer']);
            $row['option'] = [];
        } elseif ($row['type_id'] == 4) {
            $row['totalOption'] = 2;
            $row['option'] = ['True', 'False'];
            $row['Correct Answer'] = strtolower($row['Correct Answer']) == 't' ? 'True' : 'False';
            $row['answer'][] = array_search(ucfirst(strtolower($row['Correct Answer'])), $row['option']) + 1;
        }

        $keys_to_keep = [
            'chapter_id',
            'group',
            'level',
            'question',
            'explanation',
            'hints',
            'mark',
            'type_id',
            'type',
            'totalOption',
            'option',
            'answer',
            'photo'
        ];

        foreach ($row as $index => $r) {
            if (!in_array($index, $keys_to_keep)) {
                unset($row[$index]);
            }
        }

        $row['photo'] = NULL;

        return $row;
    }

    private function convertRow($data, $should_convert)
    {
        $return = [];
        $header = [];

        foreach ($data as $index => $row) {
            foreach ($row as $i => $r) {
                $row[$i] = trim($r);
            }

            if (!$should_convert) {
                $return[] = $row;
            } else {
                if ($index == 0) {
                    $header = $row;
                    continue;
                }

                foreach ($row as $i => $d) {
                    $temp[$header[$i]] = strlen($d) ? $d : NULL;
                }
                $return[] = $temp;
            }
        }

        return $return;
    }

    private function getNameFromNumber($num)
    {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval($num / 26);
        if ($num2 > 0) {
            return $this->getNameFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }

        //https://stackoverflow.com/questions/58393315/how-to-add-borders-to-phpspreadsheet-generated-excel-file
    }

    private function  apiDownloadExcel($data = [['title' => '', 'data' => [],]], $filename = 'excel', $dropdowns = [])
    {
        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()->setCreator('PhpOffice')
            ->setLastModifiedBy('PhpOffice')
            ->setTitle('Excel File')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('PhpOffice')
            ->setKeywords('PhpOffice')
            ->setCategory('PhpOffice');

        foreach ($data as $index => $d) {
            if ($index) {
                $spreadsheet->createSheet();
            }

            $spreadsheet->setActiveSheetIndex($index)->fromArray($d['data']);

            $spreadsheet->getActiveSheet()->setTitle($d['title']);

            $sheet = $spreadsheet->getActiveSheet();

            if ($d['title'] == 'Data') {
                foreach ($dropdowns as $drop) {
                    for ($row = $drop['start']; $row <= $drop['end']; $row++) {
                        $objValidation = $sheet->getCell($drop['cell'].$row)->getDataValidation();
                        $objValidation->setType(DataValidation::TYPE_LIST);
                        $objValidation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                        $objValidation->setAllowBlank(false);
                        $objValidation->setShowInputMessage(true);
                        $objValidation->setShowErrorMessage(true);
                        $objValidation->setShowDropDown(true);
                        $objValidation->setErrorTitle('Input error');
                        $objValidation->setError('Value is not in list.');
                        //$objValidation->setFormula1('Rule!$B$2:$B$6');
                        // $objValidation->setFormula1('"' . implode(',', $drop['data']) . '"');
                       $objValidation->setFormula1('Rule!$'.$drop['fromCell'].'$'.$drop['start'].':$'.$drop['fromCell'].'$'.$drop['count']);
                         //sheet name and column/row of dropdown values on other sheet (or same sheet)
                    }
                }
            }
        }

        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
