<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Take_exam extends Admin_Controller {
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

    function __construct() {
        parent::__construct();
        $this->load->model('online_exam_m');
        $this->load->model('online_exam_question_m');
        $this->load->model('instruction_m');
        $this->load->model('question_bank_m');
        $this->load->model('question_option_m');
        $this->load->model('question_answer_m');
        $this->load->model('online_exam_user_answer_m');
        $this->load->model('online_exam_user_status_m');
        $this->load->model('online_exam_user_answer_option_m');
        $this->load->model('student_m');
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('subject_m');
        $this->load->model('tempanswer_m'); 
        $this->load->model('uploaded_answers_m');
        $this->load->model('studentrelation_m');

        $language = $this->session->userdata('lang');
        $this->lang->load('take_exam', $language);
    }

    public function index() {

        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js'
            )
        );

        $usertypeID   = $this->session->userdata('usertypeID');
        $loginuserID  = $this->session->userdata('loginuserID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->data['usertypeID']   = $usertypeID;
        $this->data['userSubjectPluck'] = pluck($this->subject_m->get_order_by_subject(array('type' => 1)), 'subjectID', 'subjectID');
        if($usertypeID == '3') {
            $this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $loginuserID, 'srschoolyearID' => $schoolyearID));
            $optionalSubject = $this->subject_m->get_single_subject(array('type' => 0, 'subjectID' => $this->data['student']->sroptionalsubjectID));
            if(customCompute($optionalSubject)) {
                $this->data['userSubjectPluck'][$optionalSubject->subjectID] = $optionalSubject->subjectID;
            }
        }

        $this->data['examStatus'] = pluck($this->online_exam_user_status_m->get_order_by_online_exam_user_status(array('userID'=>$loginuserID)),'obj','onlineExamID');
        $this->data['onlineExams'] = $this->online_exam_m->get_order_by_online_exam(array('schoolYearID' => $schoolyearID, 'usertypeID' => $usertypeID, 'published'=>1));
       
        $this->data['can_upload'] = [];
        
        foreach($this->data['onlineExams'] as $index => $exam) {
            $this->data['onlineExams'][$index]->class = $this->classes_m->get_classes($exam->classID);
            $this->data['onlineExams'][$index]->subject = $this->subject_m->get_subject($exam->subjectID);
            if($exam->examTypeNumber == 5) {
                $format = 'd M, Y g:i A';
                $this->data['days_remaining'][] = convertDays($exam->startDateTime, $exam->endDateTime);
                $this->data['onlineExams'][$index]->formattedStartDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data['onlineExams'][$index]->startDateTime)->format($format);
                $this->data['onlineExams'][$index]->formattedEndDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data['onlineExams'][$index]->endDateTime)->format($format);
                $this->data['onlineExams'][$index]->startDateTime = $exam->startDateTime;
                $this->data['onlineExams'][$index]->endDateTime = $exam->endDateTime;
            } else if ($exam->examTypeNumber == 4) {
                $format = 'd M, Y';
                $this->data['days_remaining'][] = convertDays($exam->startDateTime, $exam->endDateTime);
                $this->data['onlineExams'][$index]->formattedStartDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data['onlineExams'][$index]->startDateTime)->format($format);
                $this->data['onlineExams'][$index]->formattedEndDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $this->data['onlineExams'][$index]->endDateTime)->format($format);
                $this->data['onlineExams'][$index]->startDateTime = $exam->startDateTime;
                $this->data['onlineExams'][$index]->endDateTime = $exam->endDateTime;
            } else {
                $this->data['days_remaining'][] = '';
                $this->data['onlineExams'][$index]->formattedStartDateTime = '';
                $this->data['onlineExams'][$index]->formattedEndDateTime = '';
            }
           

            $this->data['can_upload'][] = $this->canUpload($exam->onlineExamID, $loginuserID);
        }
        $this->data['currentDate'] = date('Y-m-d H:i:s');
        $this->data["subview"] = "online_exam/take_exam/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function show() {
        
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/checkbox/checkbox.css',
                'assets/inilabs/form/fuelux.min.css'
            )
        );
        $this->data['footerassets'] = array(
            'js' => array(
                'assets/inilabs/form/fuelux.min.js'
            )
        );

        $this->data['currentDate'] = date('Y-m-d H:i:s');

        $userID = $this->session->userdata("loginuserID");
        $onlineExamID = htmlentities(escapeString($this->uri->segment(3)));

        $subjectiveAnswers = pluck($this->tempanswer_m->get_user_temp_subjective_answers(
            [
                'user_id' =>  $userID,
                'exam_id' =>  $onlineExamID
            ]
        ),'answer','question_id');

        $this->data['subjectiveAnswers'] = $subjectiveAnswers;

        $subjectiveFiles = pluck_multi_array($this->tempanswer_m->get_user_temp_subjective_files(
            [
                'user_id' =>  $userID,
                'exam_id' =>  $onlineExamID
            ]
        ),'obj','question_id');

        $this->data['subjectiveFiles'] = $subjectiveFiles;

        $examGivenStatus     = FALSE;
        $examGivenDataStatus = FALSE;
        $examExpireStatus    = FALSE;
        $examSubjectStatus   = FALSE;

        if((int) $onlineExamID) {
            $this->data['onlineExamID'] = $onlineExamID;

            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            // $this->data['student'] = $this->student_m->get_student($userID);
            $onlineExam = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $onlineExamID, 'schoolYearID' => $schoolyearID]);
            if(customCompute($onlineExamID)) {
                $this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $userID, 'srschoolyearID' => $schoolyearID));
                if(customCompute($this->data['student'])) {
                    $array['classesID'] = $this->data['student']->classesID;
                    $array['sectionID'] = $this->data['student']->sectionID;
                    $array['studentgroupID'] = $this->data['student']->srstudentgroupID;
                    $array['onlineExamID'] = $onlineExamID;
                    $array['schoolYearID'] = $schoolyearID;
                    $online_exam = $this->online_exam_m->get_online_exam_by_student($array);
                    $userExamCheck = $this->online_exam_user_status_m->get_order_by_online_exam_user_status(array('userID'=>$userID,'classesID'=>$array['classesID'],'sectionID'=>$array['sectionID'],'onlineExamID'=> $onlineExamID));
                    if(customCompute($online_exam)) {
                        $DDonlineExam = $online_exam;
                        $DDexamStatus = $userExamCheck;

                        $currentdate = 0;
                        if($DDonlineExam->examTypeNumber == '4') {
                            $presentDate = strtotime(date('Y-m-d'));
                            $examStartDate = strtotime($DDonlineExam->startDateTime);
                            $examEndDate = strtotime($DDonlineExam->endDateTime);
                        } elseif($DDonlineExam->examTypeNumber == '5') {
                            $presentDate = strtotime(date('Y-m-d H:i:s'));
                            $examStartDate = strtotime($DDonlineExam->startDateTime);
                            $examEndDate = strtotime($DDonlineExam->endDateTime);
                        }

                        if($DDonlineExam->examTypeNumber == '4' || $DDonlineExam->examTypeNumber == '5') {
                            if($presentDate >= $examStartDate && $presentDate <= $examEndDate) {
                                $examGivenStatus = TRUE;
                            } elseif($presentDate > $examStartDate && $presentDate > $examEndDate) {
                                $examExpireStatus = TRUE;
                            }
                        } else {
                            $examGivenStatus = TRUE;
                        }

                        if($examGivenStatus) {
                            $examGivenStatus = FALSE;
                            if($DDonlineExam->examStatus == 2) {
                                $examGivenStatus = TRUE;
                            } else {
                                $userExamCheck = pluck($userExamCheck,'obj','onlineExamID');
                                if(isset($userExamCheck[$DDonlineExam->onlineExamID])) {
                                    $examGivenDataStatus = TRUE;
                                } else {
                                    $examGivenStatus = TRUE;
                                }
                            }
                        }

                        if($examGivenStatus) {
                            if((int)$DDonlineExam->subjectID && (int)$DDonlineExam->classID) {
                                $examGivenStatus = FALSE;
                                $userSubjectPluck = pluck($this->subject_m->get_order_by_subject(array('type' => 1)), 'subjectID', 'subjectID');
                                $optionalSubject = $this->subject_m->get_single_subject(array('type' => 0, 'subjectID' => $this->data['student']->sroptionalsubjectID));
                                if(customCompute($optionalSubject)) {
                                    $userSubjectPluck[$optionalSubject->subjectID] = $optionalSubject->subjectID;
                                }

                                if(in_array($DDonlineExam->subjectID, $userSubjectPluck)) {
                                    $examGivenStatus = TRUE;
                                } else {
                                    $examSubjectStatus = FALSE;
                                }
                            } else {
                                $examSubjectStatus = TRUE;
                            }
                        } else {
                            $examSubjectStatus = TRUE;
                        }
                    }
                    $this->data['class'] = $this->classes_m->get_classes($this->data['student']->classesID);
                } else {
                    $this->data['class'] = array();
                }
            }

            if(customCompute($this->data['student'])) {
                $this->data['section'] = $this->section_m->get_section($this->data['student']->sectionID);
            } else {
                $this->data['section'] = array();
            }

            $this->data['onlineExam'] = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $onlineExamID]);
            if(customCompute($online_exam)) {
                $onlineExamQuestions = $this->online_exam_question_m->get_order_by_online_exam_question(['onlineExamID' => $onlineExamID]);
                $allOnlineExamQuestions = $onlineExamQuestions;
                if($this->data['onlineExam']->random != 0) {
                    $onlineExamQuestions = $this->randAssociativeArray($onlineExamQuestions, $this->data['onlineExam']->random);
                }

                $this->data['onlineExamQuestions'] = $onlineExamQuestions;
                $onlineExamQuestions = pluck($onlineExamQuestions, 'obj', 'questionID');
                $questionsBank = pluck($this->question_bank_m->get_order_by_question_bank(), 'obj', 'questionBankID');
                $this->data['questions'] = $questionsBank;


                $options = [];
                $answers = [];
                $allOptions = [];
                $allAnswers = [];
                if(customCompute($allOnlineExamQuestions)) {
                    $pluckOnlineExamQuestions = pluck($allOnlineExamQuestions, 'questionID');
                    $allOptions = $this->question_option_m->get_where_in_question_option($pluckOnlineExamQuestions, 'questionID');
                    foreach ($allOptions as $option) {

                        $option->prefill_data = null;
                        $option->prefill_data_exist = false;

                        $data = $this->tempanswer_m->get_single_temp_answer(['question_id' => $option->questionID, 'exam_id' =>$onlineExamID, 'user_id' => $this->session->userdata("loginuserID") ]);

                        if(isset($data)) {
                            $option->prefill_data_exist = true;
                            if($option->optionID == $data->optionid1) {
                                $option->prefill_data = $data->option1;
                            } elseif($option->optionID == $data->optionid2) {
                                $option->prefill_data = $data->option2;
                            } elseif($option->optionID == $data->optionid3) {
                                $option->prefill_data = $data->option3;
                            } elseif($option->optionID == $data->optionid4) {
                                $option->prefill_data = $data->option4;
                            } 
                        } 
                        if($option->name == "" && $option->img == "") continue;
                        $options[$option->questionID][] = $option;
                    }
                    $allAnswers = $this->question_answer_m->get_where_in_question_answer($pluckOnlineExamQuestions, 'questionID');
                    foreach ($allAnswers as $answer) {

                        $answer->prefill_data = null;
                        $answer->prefill_data_exist = false;
                        $answer->prefill_array = [];


                        $data = $this->tempanswer_m->get_single_temp_answer(['question_id' => $answer->questionID, 'exam_id' =>$onlineExamID, 'user_id' => $this->session->userdata("loginuserID") ]);

                        if(isset($data)) {
                            $answer->prefill_data_exist = true;
                            if($answer->typeNumber != 3) {
                                if($answer->optionID == $data->optionid1) {
                                    $answer->prefill_data = $data->option1;
                                } elseif($answer->optionID == $data->optionid2) {
                                    $answer->prefill_data = $data->option2;
                                } elseif($answer->optionID == $data->optionid3) {
                                    $answer->prefill_data = $data->option3;
                                } elseif($option->optionID == $data->optionid4) {
                                    $answer->prefill_data = $data->option4;
                                }
                            } else {
                                $answer->prefill_array = [];

                                if($data->option1 != null) {
                                    $answer->prefill_array[] = $data->option1;
                                } 
                                if($data->option2 != null) {
                                    $answer->prefill_array[] = $data->option2;
                                } 
                                if($data->option3 != null) {
                                    $answer->prefill_array[] = $data->option3;
                                } 
                                if($data->option4 != null) {
                                    $answer->prefill_array[] = $data->option4;
                                }
                                if($data->option5 != null) {
                                    $answer->prefill_array[] = $data->option5;
                                }
                            }
                        } 
                        $answers[$answer->questionID][] = $answer;
                    }

                    $opts = [];
                    foreach($options as $index => $option) {
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

                $onlineExamUserStatus = '';
                if($_POST) {

                    $notSaved = true;
                    if($this->data['onlineExam']->examStatus == 1){
                        $alreadySavedOrNot = $this->online_exam_user_status_m->get_single_online_exam_user_status([
                            'onlineExamID' => $onlineExamID,
                            'userID' => $this->session->userdata("loginuserID")
                        ]);
                        if(customCompute($alreadySavedOrNot)){
                            $notSaved = false;
                        }else{
                            $notSaved = true;
                        }
                    }    

                if($notSaved){
                    $time = date("Y-m-d h:i:s");
                    $mainQuestionAnswer = [];
                    $uniqid = $this->generateUniqueNumber();
                    $status = 1;
                    $attend = 0;
                    $userAnswer = $this->input->post('answer');
                  
                    foreach ($allAnswers as $answer) {
                        if($answer->typeNumber == 3) {
                            $mainQuestionAnswer[$answer->typeNumber][$answer->questionID][$answer->answerID] = $answer->text;
                        } elseif($answer->typeNumber == 4) 
                        {
                            $mainQuestionAnswer[$answer->typeNumber][$answer->questionID]= $answer->text;
                        }
                        else {
                            $mainQuestionAnswer[$answer->typeNumber][$answer->questionID][] = $answer->optionID;
                        }
                    }

                    $questionStatus = [];
                    $correctAnswer = 0;
                    $totalQuestionMark = 0;
                    $totalCorrectMark = 0;
                    $visited = [];
                    $totalAnswer = 0;
                   
                    if(customCompute($userAnswer)) {
                        foreach ($userAnswer as $userAnswerKey => $uA) {
                                if(!$this->input->post('nullanswer')) 
                                {
                                  if($userAnswerKey == 3){
                                      $fanswer = 0;
                                      foreach($uA as $u){
                                            foreach($u as $a){
                                                if($a != ''){
                                                    $fanswer = $fanswer + 1;
                                                }
                                            }
                                      }
                                      if($fanswer > 0){
                                        $totalAnswer += 1;
                                      }
                                  }elseif($userAnswerKey == 4){
                                       foreach($uA as $uuaa){
                                            if($uuaa != ''){
                                                $totalAnswer += 1;
                                            }
                                       }
                                  }else{
                                     $totalAnswer += customCompute($uA);
                                  }
                                }
                            
                        }
                    }
                   
                    if(customCompute($allOnlineExamQuestions)) {
                        foreach ($allOnlineExamQuestions as $aoeq) {    
                            if(isset($questionsBank[$aoeq->questionID])) {
                                $totalQuestionMark += $questionsBank[$aoeq->questionID]->mark; 
                            }
                        }
                    }
                    
                    $f = 0;
                    $onlineExamQuestionID=0;
                    foreach ($mainQuestionAnswer as $typeID => $questions) {
                     if(!isset($userAnswer[$typeID])) continue;
                        foreach ($questions as $questionID => $options) {
                            if(isset($onlineExamQuestions[$questionID])) {
                                $onlineExamQuestionID = $onlineExamQuestions[$questionID]->onlineExamQuestionID;
                                $onlineExamUserAnswerID = $this->online_exam_user_answer_m->insert([
                                    'onlineExamQuestionID' => $onlineExamQuestionID,
                                    'userID' => $userID
                                ]);
                            }
                       
                          
                            if(isset($userAnswer[$typeID][$questionID])) {
                                 $qsdetails = $this->question_bank_m->get_single_question_bank(['questionBankID'=>$questionID]);
                                    $totalCorrectMark += isset($questionsBank[$questionID]) ? $questionsBank[$questionID]->mark : 0;
                                    $questionStatus[$questionID] = 1;
                                    $correctAnswer++;
                               
                                $f = 1;
                                if($typeID == 3) {
                                    $obmarks = 0;
                                    $perAnswerMarks = round(($qsdetails->mark/count($options)),2);
                                    foreach ($options as $answerID => $answer) {
                                        $takeAnswer = strtolower($answer);
                                        $takeAns = str_replace(' ','',$takeAnswer);
                                        $getAnswer = isset($userAnswer[$typeID][$questionID][$answerID]) ? strtolower($userAnswer[$typeID][$questionID][$answerID]) : '';
                                        $getAns = str_replace(' ','',$getAnswer);
                                        if($getAns != $takeAns) {
                                            $ans_status = 0;
                                            $obtained_mark = 0;
                                            $f = 0;
                                        }
                                        else
                                        {
                                            $f = 0;
                                            $ans_status = 1;
                                            $obtained_mark = $perAnswerMarks;
                                            $obmarks = $obmarks + $perAnswerMarks;
                                        }
                                   if(isset($userAnswer[$typeID][$questionID][$answerID]) && $userAnswer[$typeID][$questionID][$answerID] != ''){
                                      
                                        $this->online_exam_user_answer_option_m->insert([
                                            'questionID' => $questionID,
                                            'typeID' => $typeID,
                                            'text' => $getAnswer,
                                            'time' => $time,
                                            'user_id'=>$this->session->userdata("loginuserID"),
                                            'ans_status'=>$ans_status,
                                            'obtained_mark'=>$obtained_mark,
                                            'full_mark'=>$qsdetails->mark,
                                            'onlineExamQuestionID'=>$onlineExamQuestionID,
                                            'onlineExamUserAnswerID'=>$uniqid,
                                            'correct_ans'=>trim($takeAnswer),
                                            'attend'=>$attend,
                                            'examID'=>$this->data['onlineExam']->onlineExamID
                                        ]);
                                    }
                                        
                                    }
                                    if($obmarks == $qsdetails->mark){
                                        $ans_status = 1;
                                        $obtained_mark = $obmarks;
                                    }else{
                                        $ans_status = 0;
                                        $obtained_mark = $obmarks;
                                    }
                                    
                                }
                                
                                elseif($typeID == 1 || $typeID == 2) {
                                        $ans_status = 0;
                                        $obtained_mark = 0;
                                         if($this->input->post('nullanswer'))
                                         {
                                            $f = 0;
                                            $ans_status = 0;
                                            $obtained_mark = 0;
                                            $attend = 1;   
                                         }
                                         else
                                         {
                                       
                                            if(!empty($options))
                                        {
                                                $obmarks = 0;
                                                $perAnswerMarks = round(($qsdetails->mark/count($options)),2);

                                                if(customCompute($userAnswer[$typeID][$questionID])){
                                                foreach ($userAnswer[$typeID][$questionID] as $userOption) {
                                              
                                                if(count($userAnswer[$typeID][$questionID]) > count($options)){
                                                    $obtained_mark = 0;
                                                    $ans_status = 0;
                                                    $f = 0;
                                                }else{
                                                    if(in_array($userOption, $options)) {
                                                        $obmarks = $obmarks +  (float)$perAnswerMarks;
                                                        $obtained_mark = $perAnswerMarks;
                                                        $ans_status = 1;
                                                    }else{
                                                        $obtained_mark = 0;
                                                        $ans_status = 0;
                                                    }  
                                                }
                                                    $this->online_exam_user_answer_option_m->insert([
                                                        'questionID' => $questionID,
                                                        'optionID' => $userOption,
                                                        'typeID' => $typeID,
                                                        'time' => $time,
                                                        'user_id'=>$this->session->userdata("loginuserID"),
                                                        'ans_status'=>$ans_status,
                                                        'obtained_mark'=>$obtained_mark,
                                                        'full_mark'=>$qsdetails->mark,
                                                        'onlineExamQuestionID'=>$onlineExamQuestionID,
                                                        'onlineExamUserAnswerID'=>$uniqid,
                                                        'attend'=>$attend,
                                                        'examID'=>$this->data['onlineExam']->onlineExamID
                                                    ]);
                                                } 

                                                if($obmarks == $qsdetails->mark){
                                                    if($typeID == 1){
                                                        $f = 1;
                                                    }else{
                                                        $f = 0;
                                                    }
                                                    $ans_status = 1;
                                                    $obtained_mark = $obmarks;
                                                }else{
                                                    if($typeID == 1){
                                                        $f = 0;
                                                    }else{
                                                        $f = 0;
                                                    }
                                                    $ans_status = 0;
                                                    $obtained_mark = $obmarks;
                                                }
                                            }
                                         }
                                         }
                                         
                                        if(!isset($visited[$typeID][$questionID])) {
                                            $visited[$typeID][$questionID] = 1;
                                        }
                                }
                                elseif($typeID == 4) {
                                    $f = 0;
                                    $new_file = '';
                                    $status = 0;
                                    $subjectiveAnswerFiles = pluck($this->tempanswer_m->get_user_temp_subjective_files([
                                        'exam_id' => $onlineExamID,
                                        'question_id' => $questionID,
                                        'is_subjective' => 1,
                                        'user_id' => $this->session->userdata("loginuserID")
                                    ]),'link');
                                    $filesname = '';
                                    if(customCompute($subjectiveAnswerFiles)){
                                        $filesname = implode(',',$subjectiveAnswerFiles);
                                    }

                                    if($userAnswer[$typeID][$questionID] == ''){
                                         if($filesname != ''){
                                             $totalAnswer += 1; 
                                         }
                                    }

                                    if($filesname != '' || $userAnswer[$typeID][$questionID] != ''){
                                    $this->online_exam_user_answer_option_m->insert([
                                                    'questionID' => $questionID,
                                                    'text' => $userAnswer[$typeID][$questionID],
                                                    'typeID' => $typeID,
                                                    'time' => $time,
                                                    'user_id'=>$this->session->userdata("loginuserID"),
                                                    'ans_status'=>0,
                                                    'obtained_mark'=>0,
                                                    'full_mark'=>$qsdetails->mark,
                                                    'onlineExamQuestionID'=>$onlineExamQuestionID,
                                                    'onlineExamUserAnswerID'=>$uniqid,
                                                    'subimg'=>$filesname,
                                                    'attend'=>$attend,
                                                    'examID'=>$this->data['onlineExam']->onlineExamID
                                                ]);
                                    }            
                                                
                                } 
                                elseif($typeID == 5) {
                                    $f = 0;
                                    $new_file = '';
                                    $status = 0;
                                    $subjectiveAnswerFiles = pluck($this->tempanswer_m->get_user_temp_subjective_files([
                                        'exam_id' => $onlineExamID,
                                        'question_id' => $questionID,
                                        'is_subjective' => 0,
                                        'user_id' => $this->session->userdata("loginuserID")
                                    ]),'link');
                                    $filesname = '';
                                    if(customCompute($subjectiveAnswerFiles)){
                                        $filesname = implode(',',$subjectiveAnswerFiles);
                                    }

                                    if($userAnswer[$typeID][$questionID] == ''){
                                         if($filesname != ''){
                                             $totalAnswer += 1; 
                                         }
                                    }

                                    if($filesname != '' || $userAnswer[$typeID][$questionID] != ''){
                                    $this->online_exam_user_answer_option_m->insert([
                                                    'questionID' => $questionID,
                                                    'text' => $userAnswer[$typeID][$questionID],
                                                    'typeID' => $typeID,
                                                    'time' => $time,
                                                    'user_id'=>$this->session->userdata("loginuserID"),
                                                    'ans_status'=>0,
                                                    'obtained_mark'=>0,
                                                    'full_mark'=>$qsdetails->mark,
                                                    'onlineExamQuestionID'=>$onlineExamQuestionID,
                                                    'onlineExamUserAnswerID'=>$uniqid,
                                                    'subimg'=>$filesname,
                                                    'attend'=>$attend,
                                                    'examID'=>$this->data['onlineExam']->onlineExamID
                                                ]);
                                    }            
                                                
                                } 
                                elseif($typeID == 55) {
                                    $f = 0;
                                    $new_file = '';
                                    $status = 0;
                                    if(!empty($_FILES['image']))
                                    {  
                                        $acceptable = array("doc", "docx", "pdf", "gif", "jpeg", "jpg", "png"); 
                                        $target_dir = "./uploads/images/";
                                        $totalcount = count($_FILES['image']['name'][$typeID][$questionID]);
                                        $filesname = '';
                                        for($i=0;$i<$totalcount;$i++)
                                        {
                                                $new_file = $_FILES['image']['name'][$typeID][$questionID][$i];
                                                $_FILES['attach']['tmp_name'] = $_FILES['image']['tmp_name'][$typeID][$questionID][$i];
                                                $image_info = getimagesize($_FILES['image']['tmp_name'][$typeID][$questionID][$i]);
                                                $temp = explode(".", $new_file);
                                                if(in_array(end($temp), $acceptable))
                                                {
                                                    $newfilename = round(microtime(true)).'_'.$questionID.'_'.$i.'.' . end($temp);
                                                    $new_file = $newfilename;
                                                    $target_file = $target_dir.$newfilename;
                                                    $filesname .=','.$new_file;
                                                    if(move_uploaded_file($_FILES["image"]["tmp_name"][$typeID][$questionID][$i], $target_file))
                                                    {
                                                        $image_width = $image_info[0];
                                                        $image_height = $image_info[1];
                                                        resizeImageDifferentSize($newfilename,$target_dir,$image_width,$image_height);

                                                    }

                                                }       
                                        }
                                        $filesname =  substr($filesname,1);
                                      
                                    }
                                    $this->online_exam_user_answer_option_m->insert([
                                                    'questionID' => $questionID,
                                                    'text' => $userAnswer[$typeID][$questionID],
                                                    'typeID' => $typeID,
                                                    'time' => $time,
                                                    'user_id'=>$this->session->userdata("loginuserID"),
                                                    'ans_status'=>0,
                                                    'obtained_mark'=>0,
                                                    'full_mark'=>$qsdetails->mark,
                                                    'onlineExamQuestionID'=>$onlineExamQuestionID,
                                                    'onlineExamUserAnswerID'=>$uniqid,
                                                    'subimg'=>$filesname,
                                                    'attend'=>$attend,
                                                    'examID'=>$this->data['onlineExam']->onlineExamID
                                                ]);
                                                
                                } 

                                if(!$f) {
                                    $questionStatus[$questionID] = 0;
                                    $correctAnswer--;
                                    $totalCorrectMark -= $questionsBank[$questionID]->mark;
                                }

                                if($typeID == 2 || $typeID == 3){

                                    if($ans_status == 1){
                                        $totalCorrectMark += $obtained_mark;
                                        $correctAnswer++;
                                    }else{
                                        $totalCorrectMark += $obtained_mark;
                                    }
                                }
                            }
                        }
                    }


                    $examtime = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('userID' => $userID, 'onlineExamID' => $onlineExamID));

                    $examTimeCounter = 1;
                    if(customCompute($examtime)) {
                        $examTimeCounter = $examtime->examtimeID;
                        $examTimeCounter++;
                    }


                    $statusID = 10;
                    if(customCompute($this->data['onlineExam'])) {
                        if($this->data['onlineExam']->markType == 5) {

                            $percentage = 0;
                            if($totalCorrectMark > 0 && $totalQuestionMark > 0) {
                                $percentage = round(($totalCorrectMark/$totalQuestionMark)*100,2);
                            } 

                            if($percentage >= $this->data['onlineExam']->percentage) {
                                $statusID = 5;
                            } else {
                                $statusID = 10;
                            }
                        } elseif($this->data['onlineExam']->markType == 10) {
                            if($totalCorrectMark >= $this->data['onlineExam']->percentage) {
                                $statusID = 5;
                            } else {
                                $statusID = 10;
                            }
                        }
                    }
                    $status = 0;
                
                   $insID = $this->online_exam_user_status_m->insert([
                        'onlineExamID' => $this->data['onlineExam']->onlineExamID,
                        'time' => $time,
                        'totalQuestion' => customCompute($onlineExamQuestions),
                        'totalAnswer' => $totalAnswer,
                        'nagetiveMark' => $this->data['onlineExam']->negativeMark,
                        'duration' => $this->data['onlineExam']->duration,
                        'score' => $correctAnswer,
                        'userID' => $userID,
                        'classesID' => customCompute($this->data['class']) ? $this->data['class']->classesID : 0,
                        'sectionID' => customCompute($this->data['section']) ? $this->data['section']->sectionID : 0,
                        'examtimeID' => $examTimeCounter,
                        'totalCurrectAnswer' => $correctAnswer,
                        'totalMark' => $totalQuestionMark,
                        'totalObtainedMark' => $totalCorrectMark,
                        'totalPercentage' => (($totalCorrectMark > 0 && $totalQuestionMark > 0) ? round(($totalCorrectMark/$totalQuestionMark)*100,2) : 0),
                        'statusID' => $statusID,
                        'status'=>($online_exam->result_published == 1) ? 1 : 0,
                        'onlineExamUserAnswerID'=>$uniqid
                    ]);

                    if($insID) {
                        $this->tempanswer_m->delete_temp_answer($this->session->userdata("loginuserID"), $onlineExamID);
                        $this->tempanswer_m->delete_temp_subjective_answer($this->session->userdata("loginuserID"), $onlineExamID);
                        $this->tempanswer_m->delete_temp_subjective_files($this->session->userdata("loginuserID"), $onlineExamID);
                   
                    }

                    if($this->data['onlineExam']->paid) {
                        $onlineExamPayments = $this->online_exam_payment_m->get_single_online_exam_payment_only_first_row(array('online_examID' => $this->data['onlineExam']->onlineExamID, 'status' => 0, 'usertypeID' => $this->session->userdata('usertypeID'), 'userID' => $this->session->userdata('loginuserID')));

                        if($onlineExamPayments->online_exam_paymentID != NULL) {
                            $onlineExamPaymentArray = [
                                'status' => 1
                            ];
                            $this->online_exam_payment_m->update_online_exam_payment($onlineExamPaymentArray, $onlineExamPayments->online_exam_paymentID);
                        }
                    }
                    $onlineExamUserStatus = $this->online_exam_user_status_m->get_single_online_exam_user_status(array('onlineExamID' => (int) $onlineExamID));
                   
                    $this->data['fail'] = $f;
                    $this->data['questionStatus'] = $questionStatus;
                    $this->data['totalAnswer'] = $totalAnswer;
                    $this->data['correctAnswer'] = $correctAnswer;
                    $this->data['totalCorrectMark'] = $totalCorrectMark;
                    $this->data['totalQuestionMark'] = $totalQuestionMark;
                    $this->data['userExamCheck'] = $userExamCheck;
                    $this->data['onlineExamUserStatus'] = $onlineExamUserStatus;
                    if($online_exam->auto_published == 1) {
                        $this->data["subview"] = "online_exam/take_exam/result";
                    } else {
                        $this->data["subview"] = "online_exam/take_exam/pending";
                    }
                    return $this->load->view('_layout_main', $this->data);
                }
            }

                if($examGivenStatus) {
                    $qbank = $this->online_exam_question_m->getexamtype($this->data['onlineExam']->onlineExamID);
                    $this->data["typeNumber"] = $qbank;
                    $this->data["subview"] = "online_exam/take_exam/question";
                    return $this->load->view('_layout_main', $this->data);
                } else {
                    if($examGivenDataStatus) {
                        $this->data['online_exam'] = $online_exam;
                        $userExamCheck = pluck($userExamCheck,'obj','onlineExamID');

                        $this->data['userExamCheck'] = isset($userExamCheck[$onlineExamID]) ? $userExamCheck[$onlineExamID] : [];

                        if($online_exam->auto_published == 1 || $online_exam->result_published == 1) {
                            $this->data["subview"] = "online_exam/take_exam/checkexam";
                        } else {
                            $this->data["subview"] = "online_exam/take_exam/pending";
                        }
                        return $this->load->view('_layout_main', $this->data);
                    } else {
                        if($examExpireStatus) {
                            $this->data['examsubjectstatus'] = $examSubjectStatus;
                            $this->data['expirestatus'] = $examExpireStatus;
                            $this->data['upcomingstatus'] = FALSE;
                            $this->data['online_exam'] = $online_exam;
                            $this->data["subview"] = "online_exam/take_exam/expireandupcoming";
                            return $this->load->view('_layout_main', $this->data);
                        } else {
                            $this->data['examsubjectstatus'] = $examSubjectStatus;
                            $this->data['expirestatus'] = $examExpireStatus;
                            $this->data['upcomingstatus'] = TRUE;
                            $this->data['online_exam'] = $online_exam;
                            $this->data["subview"] = "online_exam/take_exam/expireandupcoming";
                            return $this->load->view('_layout_main', $this->data);
                        }
                    }
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

    public function store_temp_answers()
    {
        if($_POST) {
            // only save if time has not expired

            $temp_data = $this->tempanswer_m->get_single_temp_answer(
                ['question_id' => $_POST['questionid'], 'exam_id' =>$_POST['examid'], 'user_id' => $this->session->userdata("loginuserID") ]
            );

            if($_POST['typenumber'] == 1 && $_POST['options']) {
                $array = [
                    'exam_id' => $_POST['examid'],
                    'question_id' => $_POST['questionid'],
                    'user_id' => $this->session->userdata('loginuserID'),
                    'optionid1' => $_POST['options'],
                    'option1' => 1
                ];

                if(!$temp_data) {
                    $this->tempanswer_m->insert_temp_answer($array);
                }else{
                    $this->tempanswer_m->update_temp_answer($array,$temp_data->id);
                }
            }

            if($_POST['typenumber'] == 2 && $_POST['options']) {
                $datas = explode(", ",$_POST['options']);
                $array = [
                    'exam_id' => $_POST['examid'],
                    'question_id' => $_POST['questionid'],
                    'user_id' => $this->session->userdata('loginuserID'),
                    'optionid1' => count($datas) >= 1 ? $datas[0]: null,
                    'optionid2' => count($datas) >= 2 ? $datas[1]: null,
                    'optionid3' => count($datas) >= 3 ? $datas[2]: null,
                    'optionid4' => count($datas) >= 4 ? $datas[3]: null,
                    'option1' => count($datas) >= 1 ? 1: null,
                    'option2' => count($datas) >= 2 ? 1: null,
                    'option3' => count($datas) >= 3 ? 1: null,
                    'option4' => count($datas) >= 4 ? 1: null,
                ];
                if(!$temp_data) {
                    $this->tempanswer_m->insert_temp_answer($array);
                }else{
                    $this->tempanswer_m->update_temp_answer($array,$temp_data->id);
                }
            }

            if($_POST['typenumber'] == 3 && $_POST['options']) {
                $datas = explode(", ",$_POST['options']);
                $array = [
                    'exam_id' => $_POST['examid'],
                    'question_id' => $_POST['questionid'],
                    'user_id' => $this->session->userdata('loginuserID'),
                    'optionid1' => null,
                    'optionid2' => null,
                    'optionid3' => null,
                    'optionid4' => null,
                    'option1' => count($datas) >= 1 ? $datas[0]: null,
                    'option2' => count($datas) >= 2 ? $datas[1]: null,
                    'option3' => count($datas) >= 3 ? $datas[2]: null,
                    'option4' => count($datas) >= 4 ? $datas[3]: null,
                    'option5' => count($datas) >= 5 ? $datas[4]: null,
                ];
                if(!$temp_data) {
                    $this->tempanswer_m->insert_temp_answer($array);
                }else{
                    $this->tempanswer_m->update_temp_answer($array,$temp_data->id);
                }
            }
        }
    }

    public function store_temp_subjective_answers()
    {
        if($_POST) {
            // only save if time has not expired

            $temp_data = $this->tempanswer_m->get_single_temp_subjective_answer(
                ['question_id' => $_POST['questionid'], 'exam_id' =>$_POST['examid'], 'user_id' => $this->session->userdata("loginuserID") ]
            );

            $array = [
                'exam_id' => $_POST['examid'],
                'question_id' => $_POST['questionid'],
                'user_id' => $this->session->userdata('loginuserID'),
                'answer' => $_POST['answer'],
                'is_subjective' => $_POST['typenumber'] == 4?1:0
            ];
            
            if(!$temp_data) {
                    $this->tempanswer_m->insert_temp_subjective_answer($array);
            }else{
                $this->tempanswer_m->update_temp_subjective_answer($array,$temp_data->id);
            }
        }
    }

    public function instruction()
    {
        $onlineExamID = htmlentities(escapeString($this->uri->segment(3)));
        if((int) $onlineExamID) {
            $instructions = pluck($this->instruction_m->get_order_by_instruction(), 'obj', 'instructionID');
            $onlineExam = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $onlineExamID]);
           
            $this->data['onlineExam'] = $onlineExam;
            if(!isset($instructions[$onlineExam->instructionID])) {
                redirect(base_url('take_exam/show/'.$onlineExamID));
            }
            $this->data['instruction'] = $instructions[$onlineExam->instructionID];
            $this->data["subview"] = "online_exam/take_exam/instruction";
            return $this->load->view('_layout_main', $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function randAssociativeArray($array, $number = 0) {
        $returnArray = [];
        $countArray = customCompute($array);
        $number = $countArray;

        if($countArray == 1) {
            $randomKey[] = 0;
        } else {
            if(customCompute($array)) {
                $randomKey = array_rand($array, $number);
            } else {
                $randomKey = [];
            }
        }

        if(is_array($randomKey)) {
            shuffle($randomKey);
        }

        if(customCompute($randomKey)) {
            foreach ($randomKey as $key) {
                $returnArray[] = $array[$key];
            }
            return $returnArray;
        } else {
            return $array;
        }
    }

    public function canUpload($exam_id, $user_id) {
        $exam = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $exam_id]);
        $status = true;
        if($exam->published != 1) {
            
                return [
                    'status' => false,
                    'message'   =>  'The exam is not published yet'
                ];
            
        }

        if($exam->examTypeNumber == 4 || $exam->examTypeNumber == 5) {
            $current_date = \Carbon\Carbon::now();
            $allow = $current_date->gte(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $exam->startDateTime)) && $current_date->lte(\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $exam->endDateTime)) ? true : false;
        }

        if(!$status) {
            return [
                'status' => false,
                'message'   =>  'The exam is not running. You cannot upload now'
            ];
        }

        if(count($this->uploaded_answers_m->get_uploaded_answers($exam_id, NULL, NULL, $user_id))) {
            return [
                'status'    =>  false,
                'message'   =>  'You have already uploaded'
            ];
        }

        return [
            'status'    =>  true
        ];
    }

    public function postUploadAnswer($exam_id) {
        $usertypeID  = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');
        $username = $this->session->userdata('name');
        $exam = $this->online_exam_m->get_single_online_exam(['onlineExamID' => $exam_id]);

        $current_date = \Carbon\Carbon::now();
        
        $can_upload = $this->canUpload($exam_id, $loginuserID);

        if(!$can_upload['status']) {
            $this->session->set_flashdata('error', $can_upload['message']);
            redirect(base_url("take_exam/index"));
        }

        $asset = $_FILES['asset'];
        $targetfolder = FCPATH."assets/answer-pdf/";
        $filename = $loginuserID.'-'.$username.'-'.\Carbon\Carbon::now()->format('Y-m-d-H-i-s');
        $file_type=$asset['type'];

        switch($file_type) {
            case 'image/jpg':
                $filename .= '.jpg';
                break;

            case 'image/png':
                $filename .= '.png';
                break;

            case 'image/jpeg':
                $filename .= '.jpeg';
                break;

            case 'application/pdf':
                $filename .= '.pdf';
                break;

            default:
                $this->session->set_flashdata('error', "Please upload in jpg/png/jpeg/pdf format");
                redirect(base_url("take_exam/index"));
                break;
        }

        
        if(move_uploaded_file($asset['tmp_name'], $targetfolder.$filename)) {
            $this->uploaded_answers_m->insert([
                'exam_id' => $exam_id,
                'student_id' => $loginuserID,
                'asset' =>  $filename,
                'uploaded_date' =>  $current_date->copy()->format('Y-m-d H:i:s')
            ]);
            $this->session->set_flashdata('success', "Answer successfully submitted");
            redirect(base_url("take_exam/index"));
        }

        else {
            die();
            $this->session->set_flashdata('error', "Could not upload file. Please inform the admin");
            redirect(base_url("take_exam/index"));
        }
        
    }

    public function imageUpload($imgArrays) {
        $returnArray = array();
        $error = '';
        $new_file = '';
        
        foreach ($imgArrays as $imgkKey => $imgValue) {
            if($_FILES[$imgValue]['name'] !="") {
                    $file_name = $_FILES[$imgValue]['name'];
                    $random = random19();
                    $makeRandom = $new_file = hash('sha512', $random. $_FILES[$imgValue]['name'] .date('Y-M-d-H:i:s') . config_item("encryption_key"));
                    $file_name_rename = $makeRandom;
                    $explode = explode('.', $file_name);
                    
                        $new_file = $file_name_rename.'.'.end($explode);
                        $config['upload_path'] = "./uploads/images";
                        $config['allowed_types'] = "gif|jpg|png|jpeg";
                        $config['file_name'] = $new_file;
                        // $config['max_size'] = (1024*2);
                        // $config['max_width'] = '3000';
                        // $config['max_height'] = '3000';
                        $this->load->library('upload');
                        $this->upload->initialize($config);
                        if(!$this->upload->do_upload($imgValue)) {
                            //preg_match_all('!\d+!', $imgValue, $matches);
                            $new_file = $this->upload->display_errors();
                        } else {
                            //$returnArray['success'][$imgkKey] = $new_file;
                        }

                
                }
            }
        
        return $new_file;
    }

    public function uploadImages(){      
    $outputs = [];
    $dbDatas = [];    
    if($_FILES["files"]["name"] != ''){
  
        $config['upload_path'] = "./uploads/images";
        $config["allowed_types"] = 'gif|jpg|png|jpeg';
        // $config['max_size'] = (1024*500);
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        for($count = 0; $count<count($_FILES["files"]["name"]); $count++)
        {
            $originalFilename = $_FILES["files"]["name"][$count];
            $explode = explode('.', $originalFilename);
            $file_name_rename = preg_replace('/\s+/', '_', $explode[0]);
            $file_name_rename = str_replace(",",'-',$file_name_rename);
            $newfilename = $file_name_rename.strtotime("now").'.'.end($explode);

            $_FILES["file"]["name"] = $newfilename;
            $_FILES["file"]["type"] = $_FILES["files"]["type"][$count];
            $_FILES["file"]["tmp_name"] = $_FILES["files"]["tmp_name"][$count];
            $_FILES["file"]["error"] = $_FILES["files"]["error"][$count];
            $_FILES["file"]["size"] = $_FILES["files"]["size"][$count];
            if($this->upload->do_upload('file'))
            {
                $data = $this->upload->data();
                $image_width = $data['image_width'];
				$image_height = $data['image_height'];

                resizeImageDifferentSize($data['file_name'],$config['upload_path'],$image_width,$image_height);
 
                $dbDatas = [
                    'exam_id' => $_POST['exam_id'],
                    'question_id' => $_POST['question'],
                    'link'       => $newfilename,
                    'user_id' => $this->session->userdata('loginuserID'),
                    'is_subjective' => $_POST['is_subjective'],
                ]; 
                $id = $this->tempanswer_m->insert_temp_subjective_files($dbDatas);
                if($id){
                    $url = base_url().'uploads/images/'.$newfilename;
                    $outputs[] = [
                        'id'   => $id,
                        'link' => $newfilename,
                        'url'  => $url
                    ];
                }
            }else{
                echo false;
            }
        }
    }
    if(customCompute($outputs)){
        echo json_encode($outputs);
    }else{
        echo false;
    }
   
       
  }

  public function deleteSubjectiveAnswerFile(){

    $id = $this->input->get('id');
    $file = $this->tempanswer_m->get_user_temp_subjective_file(['id' => $id]);
    if($file){
        $link = $file->link;
        if($this->tempanswer_m->delete_temp_subjective_file($id)){
            $path = FCPATH.'uploads/images/';
            $get_file = $path.$link;
            if(file_exists($get_file)){
               unlink($get_file);
            }
            echo true;
        }else{
            echo false;
        }
    }else{
        echo false;
    }
  }

  public function generateUniqueNumber()
	{
		$time = time();
        $check = $this->online_exam_user_status_m->get_single_online_exam_user_status(['onlineExamUserAnswerID' => $time]);
		if ($check) {
			return $this->generateUniqueNumber();
		}
		return $time;
	}    
}
