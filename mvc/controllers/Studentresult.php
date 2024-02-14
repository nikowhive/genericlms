<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Studentresult extends Admin_Controller {
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
        $this->load->model("exam_m");
        $this->load->model("grade_m");
        $this->load->model("section_m");
        $this->load->model("studentremark_m");
        $this->load->model("studentgroup_m");
        $this->load->model("mark_m");
        $this->load->model("subject_m");
        $this->load->model("student_m");
        $this->load->model("marksetting_m");
        $this->load->model("sattendance_m");
        $this->load->model("studentrelation_m");
        $this->load->model("subjectattendance_m");
		$this->load->helper('nepali_calendar_helper');
        $this->load->model("subjectmark_m");
        $this->load->model("examtermsetting_m");

        $language = $this->session->userdata('lang');
        $this->lang->load('exam', $language);
    }

    public function index() {
        $this->db->cache_off();
        $this->data['exams']   = $this->exam_m->get_published_order_by_exam();
        $this->data["subview"] = "result/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function finalterms() {
        $this->db->cache_off();
        $this->data['exams']   = $this->exam_m->get_final_term_exam();
        $this->data["subview"] = "result/finalterms";
        $this->load->view('_layout_main', $this->data);
    }

    public function students() {
        $this->db->cache_off();
        $loginuserID = $this->session->userdata('loginuserID');
        $examID = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['examID'] = $examID;
        $this->data['students'] = $this->student_m->general_get_order_by_student(array('parentID'=>$loginuserID));
        $this->data["subview"] = "result/students";
        $this->load->view('_layout_main', $this->data);
    }

    public function finaltermstudents() {
        $this->db->cache_off();
        $loginuserID = $this->session->userdata('loginuserID');
        $examID = htmlentities(escapeString($this->uri->segment(3)));
        $this->data['examID'] = $examID;
        $this->data['students'] = $this->student_m->general_get_order_by_student(array('parentID'=>$loginuserID));
        $this->data["subview"] = "result/finaltermstudents";
        $this->load->view('_layout_main', $this->data);
    }

    public function view() {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if($this->session->userdata('usertypeID') == 3) {
            $studentID = $this->session->userdata('loginuserID');
            $student_data = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID), TRUE);
            $classesID = $student_data->srclassesID;
            $sectionID = $student_data->srsectionID;
        } else if ($this->session->userdata('usertypeID') == 4) {
            $studentID = htmlentities(escapeString($this->uri->segment(4)));
            $student = $this->student_m->general_get_single_student(['studentID' => $studentID]);
            $student_data = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID), TRUE);
            $classesID = $student_data->srclassesID;
            $sectionID = $student_data->srsectionID;
        } else {
            $this->data["subview"] = "errorpermission";
        	$this->load->view('_layout_main', $this->data);
        }
        $examID = htmlentities(escapeString($this->uri->segment(3)));

        $this->data['setting'] = $this->setting_m->get_setting();
        $this->data['examID']     = $examID;
        $this->data['classesID']  = $classesID;
        $this->data['sectionID']  = $sectionID;
        $this->data['studentIDD'] = $studentID;

        $queryArray        = [];
        $studentQueryArray = [];
        $queryArray['schoolyearID']          = $schoolyearID;
        $studentQueryArray['srschoolyearID'] = $schoolyearID;

        if((int)$examID > 0) {
            $queryArray['examID'] = $examID;
        } 
        if((int)$classesID > 0) {
            $queryArray['classesID'] = $classesID;
            $studentQueryArray['srclassesID'] = $classesID;
        } 
        if((int)$sectionID > 0) {
            $queryArray['sectionID'] = $sectionID;
            $studentQueryArray['srsectionID'] = $sectionID;
        }
        if((int)$studentID > 0) {
            $studentQueryArray['srstudentID'] = $studentID;
        }

        $class = $this->classes_m->get_single_classes(['classesID' => $classesID]);
        $exam      = $this->exam_m->get_single_exam(['examID'=> $examID]);

        $this->data['class'] = $class;
        $this->data['exam'] = $exam;
        $this->data['exam']->date_in_nepali = $this->convertDateToNepaliInEnglish($exam->date);
		$this->data['exam']->issue_date_in_english = $this->convertDateToEnglishInNepali($exam->issue_date);
        $this->data['examName']     = $exam->exam;
        $this->data['grades']       = $this->grade_m->get_grade();
        $this->data['classes']      = pluck($this->classes_m->general_get_classes(),'classes','classesID');
        $this->data['sections']     = pluck($this->section_m->general_get_section(),'section','sectionID');
		$this->data['class_teacher']= pluck($this->section_m->get_join_sections(),'name','sectionID');
        $this->data['groups']       = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
        $this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);
        $this->data['remarks'] 		= pluck($this->studentremark_m->get_order_by_studentremark(['examID' => $examID, 'classID' => $classesID]), 'remarks', 'studentID');
        $students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
        $marks                  = $this->mark_m->student_all_mark_array($queryArray);
        $mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
        $coscholasticSubjects      = $this->subject_m->get_subject_only_coscholastic(array('classesID' => $classesID, 'type' => 1));
        
        $this->data['mandatorySubjects'] = $mandatorySubjects;
        $this->data['coscholasticSubjects'] = $coscholasticSubjects;

        $this->subject_m->order('type DESC');
        $this->data['subjects'] = $this->subject_m->get_by_class_id($classesID);
        $this->data['subject_marks'] = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID,'class_id' => $classesID]), 'fullmark', 'subject_id');

        
        $settingmarktypeID      = $this->data['siteinfos']->marktypeID;
        $settingmarktypeID1      = $this->data['siteinfos']->marktypeID;
        $markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
        $markpercentagesmainArr1 = $this->marksetting_m->get_marksetting_markpercentages();
        $markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
        $markpercentagesArr1     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
        $this->data['markpercentagesArr']  = $markpercentagesArr;
        $this->data['settingmarktypeID']   = $settingmarktypeID;

        $this->data['markpercentagesArr1']  = $markpercentagesArr;
        $this->data['settingmarktypeID1']   = $settingmarktypeID;

        $retMark = [];
        if(customCompute($marks)) {
            foreach ($marks as $mark) {
                $retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
            }
        }

        $retMark1 = [];
        if(customCompute($marks)) {
            foreach ($marks as $mark) {
                $retMark1[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
            }
        }

        
        $highestMarks    = [];
        foreach ($marks as $value) {
            if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
                $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
            }
            $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
        }

        $this->data['highestmarks']      = $highestMarks;

        $studentPosition             = [];
        $studentChecker              = [];
        $studentClassPositionArray   = [];
        $studentSubjectPositionArray = [];
        $markpercentagesCount        = 0;

        $studentPosition1             = [];
        $studentChecker1              = [];
        $studentClassPositionArray1   = [];
        $studentSubjectPositionArray1 = [];
        $markpercentagesCount1        = 0;

        if(customCompute($this->data['studentLists'])) {
            foreach($this->data['studentLists'] as $student) {
                $student->dob_in_bs = $this->convertDateToNepaliInEnglish($student->dob);
            }
        }

        if(customCompute($students)) {
            foreach ($students as $student) {
                $opuniquepercentageArr = [];
                $anotheropuniquepercentageArr = [];
                if($student->sroptionalsubjectID > 0) {
                    $opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
                }

                if($student->sranotheroptionalsubjectID > 0) {
                    $anotheropuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
                }

                $opuniquepercentageArr1 = [];
                if($student->sroptionalsubjectID > 0) {
                    $opuniquepercentageArr1 = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
                }

                $anotheropuniquepercentageArr1 = [];
                if($student->sranotheroptionalsubjectID > 0) {
                    $anotheropuniquepercentageArr1 = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
                }

                $studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;
                
                $studentPosition1[$student->srstudentID]['totalSubjectMark'] = 0;
                
                if(customCompute($mandatorySubjects)) {
                    foreach ($mandatorySubjects as $mandatorySubject) {
                        $uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

                        $markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                        $markpercentagesCount = customCompute($markpercentages);
                        if(customCompute($markpercentages)) {
                            foreach ($markpercentages as $markpercentageID) {
                                $f = false;
                                if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                    $f = true;
                                }

                                if(isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
                                    if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
                                        $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
                                    } else {
                                        $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
                                    }
                                } else {
                                    if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
                                        $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
                                    } else {
                                        $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
                                    }
                                }

                                if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
                                    $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

                                    if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                        $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
                                    } else {
                                        $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];

                                    }
                                }

                                $f = false;
                                if(customCompute($opuniquepercentageArr)) {
                                    if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
                                        $f = true;
                                    }
                                }
                                if(customCompute($anotheropuniquepercentageArr)) {
                                    if(isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
                                        $f = true;
                                    }
                                }

                                if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
                                    if($student->sroptionalsubjectID != 0) {
                                        if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
                                            if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                            } else {
                                                $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
                                            }
                                        } else {
                                            if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                            } else {
                                                $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
                                            }
                                        }

                                        if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                            $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

                                            if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                                $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                            } else {
                                                if($f) {
                                                    $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                                }
                                            }

                                        }
                                    }
                                    if($student->sranotheroptionalsubjectID != 0) {
                                        if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
                                            if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
                                            } else {
                                                $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
                                            }
                                        } else {
                                            if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
                                            } else {
                                                $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
                                            }
                                        }

                                        if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                            $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

                                            if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                                $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
                                            } else {
                                                if($f) {
                                                    $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                }
                                            }

                                        }
                                    }
                                    $studentChecker['subject'][$student->srstudentID][$markpercentageID] = TRUE;
                                }
                            }
                        }

                        $studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];

                        if(!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
                            if($student->sroptionalsubjectID != 0) {
                                $studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
                            }
                            if($student->sranotheroptionalsubjectID != 0) {
                                $studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
                            }
                            $studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
                        }

                        $studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
                        if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
                            if($student->sroptionalsubjectID != 0) {
                                $studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
                            }
                            if($student->sranotheroptionalsubjectID != 0) {
                                $studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
                            }
                        }
                    }
                }	

                if(customCompute($coscholasticSubjects)) {
                    foreach ($coscholasticSubjects as $coscholasticSubject) {
                        $uniquepercentageArr1 = isset($markpercentagesArr1[$coscholasticSubject->subjectID]) ? $markpercentagesArr1[$coscholasticSubject->subjectID] : [];

                        $markpercentages1 = $uniquepercentageArr1[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                        $markpercentagesCount1 = customCompute($markpercentages);
                        if(customCompute($markpercentages1)) {
                            foreach ($markpercentages1 as $markpercentageID) {
                                $f = false;
                                if(isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
                                    $f = true;
                                }

                                if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID])) {
                                    if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
                                        $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
                                    } else {
                                        $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += 0;
                                    }
                                } else {
                                    if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
                                        $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
                                    } else {
                                        $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = 0;
                                    }
                                }

                                if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
                                    $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];

                                    if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                        $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];
                                    } else {
                                        $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];

                                    }
                                }

                                $f = false;
                                if(customCompute($opuniquepercentageArr1)) {
                                    if(isset($opuniquepercentageArr1['own']) && in_array($markpercentageID, $opuniquepercentageArr1['own'])) {
                                        $f = true;
                                    }
                                }

                                if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
                                    if($student->sroptionalsubjectID != 0) {
                                        if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
                                            if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                            } else {
                                                $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
                                            }
                                        } else {
                                            if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                            } else {
                                                $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
                                            }
                                        }

                                        if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                            $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

                                            if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                                $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                            } else {
                                                if($f) {
                                                    $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                                }
                                            }

                                        }
                                    }

                                    if($student->sranotheroptionalsubjectID != 0) {
                                        if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
                                            if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
                                            } else {
                                                $studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
                                            }
                                        } else {
                                            if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
                                            } else {
                                                $studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
                                            }
                                        }

                                        if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                            $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

                                            if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                                $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
                                            } else {
                                                if($f) {
                                                    $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                }
                                            }

                                        }
                                    }

                                    $studentChecker1['subject'][$student->srstudentID][$markpercentageID] = TRUE;
                                }
                            }
                        }

                        $studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];

                        if(!isset($studentChecker1['totalSubjectMark'][$student->srstudentID])) {
                            // if($student->sroptionalsubjectID != 0) {
                            // 	$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
                            // }
                            $studentChecker1['totalSubjectMark'][$student->srstudentID] = TRUE;
                        }

                        $studentSubjectPositionArray1[$coscholasticSubject->subjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];
                        if(!isset($studentChecker1['studentSubjectPositionArray'][$student->srstudentID])) {
                            // if($student->sroptionalsubjectID != 0) {
                            // 	$studentSubjectPositionArray1[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
                            // }
                        }
                    }
                }

                $studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
                $studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

                if(isset($studentPosition['totalStudentMarkAverage'])) {
                    $studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
                } else {
                    $studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
                }

                if(count($coscholasticSubjects) > 0) {
                    $studentPosition1[$student->srstudentID]['classPositionMark'] = ($studentPosition1[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition1[$student->srstudentID]['subjectMark']));
                    $studentClassPositionArray1[$student->srstudentID]             = $studentPosition1[$student->srstudentID]['classPositionMark'];	
                
                    if(isset($studentPosition1['totalStudentMarkAverage'])) {
                        $studentPosition1['totalStudentMarkAverage'] += $studentPosition1[$student->srstudentID]['classPositionMark'];
                    } else {
                        $studentPosition1['totalStudentMarkAverage']  = $studentPosition1[$student->srstudentID]['classPositionMark'];
                    }
                }
            }
        }

        arsort($studentClassPositionArray);
        $studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
        if(customCompute($studentSubjectPositionArray)) {
            foreach($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
                arsort($studentSubjectPositionMark);
                $studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
            }
        }
        if((int)$studentID > 0) {
            $queryArray['studentID'] = $studentID;
        }

        arsort($studentClassPositionArray1);
        $studentPosition1['studentClassPositionArray'] = $studentClassPositionArray1;
        if(customCompute($studentSubjectPositionArray1)) {
            foreach($studentSubjectPositionArray1 as $subjectID => $studentSubjectPositionMark1) {
                arsort($studentSubjectPositionMark1);
                $studentPosition1['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark1;
            }
        }
        if((int)$studentID > 0) {
            $queryArray['studentID'] = $studentID;
        }

        $this->data['col']             = 5 + $markpercentagesCount;
        $this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
        $this->data['studentPosition'] = $studentPosition;
        $this->data['percentageArr']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

        $this->data['col1']             = 5 + $markpercentagesCount1;
        $this->data['studentPosition1'] = $studentPosition1;
        $this->data['percentageArr1']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

        // if($class->classes_numeric == 1 || $class->classes_numeric == 2 || $class->classes_numeric == 3) {
        //     $this->data["subview"] = "result/primary";
        // } else if($class->classes_numeric == 11 || $class->classes_numeric == 12) {
        //     $this->data["subview"] = "result/view_11_12";
        // } else {
            // $this->data["subview"] = "result/view";
        // }

        
        if($class->classes_numeric >= 1 && $class->classes_numeric <= 7){
            $this->data["subview"] = "result/view_1_to_7";
        }elseif(strpos($class->classes, 'Nursery') !== false  || strpos($class->classes, 'KG') !== false){
            $this->data["subview"] = "report/terminal2/TerminalReport_kindergarden";
        }else{
            if($examID == 4 || $examID == 5){
                $this->data["subview"] = "result/view_finalterm";
             }else{
                $this->data["subview"] = "result/view";
             }
        }
        $this->load->view('_layout_main', $this->data);
    }

    public function pdf() {
        $examID = htmlentities(escapeString($this->uri->segment(3)));
        $classesID  = htmlentities(escapeString($this->uri->segment(4)));
        $sectionID  = htmlentities(escapeString($this->uri->segment(5)));
        $studentID  = htmlentities(escapeString($this->uri->segment(6)));
        $date  = htmlentities(escapeString($this->uri->segment(7)));
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if((int)$examID && (int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
            $this->data['examID']     = $examID;
            $this->data['classesID']  = $classesID;
            $this->data['sectionID']  = $sectionID;
            $this->data['studentIDD'] = $studentID;
            $this->data['date'] = urldecode($date);

            $queryArray        = [];
            $studentQueryArray = [];
            $queryArray['schoolyearID']          = $schoolyearID;
            $studentQueryArray['srschoolyearID'] = $schoolyearID;

            if((int)$examID > 0) {
                $queryArray['examID'] = $examID;
            } 
            if((int)$classesID > 0) {
                $queryArray['classesID'] = $classesID;
                $studentQueryArray['srclassesID'] = $classesID;
            }
            if((int)$sectionID > 0) {
                $queryArray['sectionID'] = $sectionID;
                $studentQueryArray['srsectionID'] = $sectionID;
            }
            if((int)$studentID > 0) {
                $studentQueryArray['srstudentID'] = $studentID;
            }

			$class = $this->classes_m->get_single_classes(['classesID' => $classesID]);
            $exam      = $this->exam_m->get_single_exam(['examID'=> $examID]);

            $this->data['class'] = $class;
            $this->data['exam'] = $exam;
            $this->data['exam']->date_in_nepali = $this->convertDateToNepaliInEnglish($exam->date);
            $this->data['exam']->issue_date_in_english = $this->convertDateToEnglishInNepali($exam->issue_date);
            $this->data['examName']     = $exam->exam;
            $this->data['grades']       = $this->grade_m->get_grade();
            $this->data['classes']      = pluck($this->classes_m->general_get_classes(),'classes','classesID');
            $this->data['sections']     = pluck($this->section_m->general_get_section(),'section','sectionID');
			$this->data['class_teacher']= pluck($this->section_m->get_join_sections(),'name','sectionID');
            $this->data['groups']       = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
            $this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);
            $this->data['remarks'] 		= pluck($this->studentremark_m->get_order_by_studentremark(['examID' => $examID, 'classID' => $classesID]), 'remarks', 'studentID');
            $students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
            $marks                  = $this->mark_m->student_all_mark_array($queryArray);
            $mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
			$coscholasticSubjects      = $this->subject_m->get_subject_only_coscholastic(array('classesID' => $classesID, 'type' => 1));
            
            $this->data['mandatorySubjects'] = $mandatorySubjects;
			$this->data['coscholasticSubjects'] = $coscholasticSubjects;
            
            $this->subject_m->order('type DESC');
            $this->data['subjects'] = $this->subject_m->get_by_class_id($classesID);
            $this->data['subject_marks'] = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID,'class_id' => $classesID]), 'fullmark', 'subject_id');

            $settingmarktypeID      = $this->data['siteinfos']->marktypeID;
			$settingmarktypeID1      = $this->data['siteinfos']->marktypeID;
            $markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
			$markpercentagesmainArr1 = $this->marksetting_m->get_marksetting_markpercentages();
            $markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
			$markpercentagesArr1     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
            $this->data['markpercentagesArr']  = $markpercentagesArr;
            $this->data['settingmarktypeID']   = $settingmarktypeID;

            $this->data['markpercentagesArr1']  = $markpercentagesArr;
			$this->data['settingmarktypeID1']   = $settingmarktypeID;

            $retMark = [];
            if(customCompute($marks)) {
                foreach ($marks as $mark) {
                    $retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
                }
            }

            $retMark1 = [];
            if(customCompute($marks)) {
                foreach ($marks as $mark) {
                    $retMark1[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
                }
            }

            $highestMarks    = [];
            foreach ($marks as $value) {
                if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
                    $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
                }
                $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
            }

            $this->data['highestmarks']      = $highestMarks;

            $studentPosition             = [];
            $studentChecker              = [];
            $studentClassPositionArray   = [];
            $studentSubjectPositionArray = [];
            $markpercentagesCount        = 0;

            $studentPosition1             = [];
            $studentChecker1              = [];
            $studentClassPositionArray1   = [];
            $studentSubjectPositionArray1 = [];
            $markpercentagesCount1        = 0;

            if(customCompute($this->data['studentLists'])) {
                foreach($this->data['studentLists'] as $student) {
                    $student->dob_in_bs = $this->convertDateToNepaliInEnglish($student->dob);
                }
            }

            if(customCompute($students)) {
                foreach ($students as $student) {
                    $opuniquepercentageArr = [];
					$anotheropuniquepercentageArr = [];
                    if($student->sroptionalsubjectID > 0) {
                        $opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
                    }

                    if($student->sranotheroptionalsubjectID > 0) {
                        $anotheropuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
                    }

                    $opuniquepercentageArr1 = [];
                    $anotheropuniquepercentageArr1 = [];
                    if($student->sroptionalsubjectID > 0) {
                        $opuniquepercentageArr1 = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
                    }

                    if($student->sranotheroptionalsubjectID > 0) {
                        $anotheropuniquepercentageArr1 = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
                    }

                    $studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;

					$studentPosition1[$student->srstudentID]['totalSubjectMark'] = 0;

                    if(customCompute($mandatorySubjects)) {
                        foreach ($mandatorySubjects as $mandatorySubject) {
                            $uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

                            $markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                            $markpercentagesCount = customCompute($markpercentages);
                            if(customCompute($markpercentages)) {
                                foreach ($markpercentages as $markpercentageID) {
                                    $f = false;
                                    if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                        $f = true;
                                    }

                                    if(isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
                                        if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
                                            $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
                                        } else {
                                            $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
                                        }
                                    } else {
                                        if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
                                            $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
                                        } else {
                                            $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
                                        }
                                    }

                                    if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
                                        $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

                                        if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                            $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
                                        } else {
                                            $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];

                                        }
                                    }

                                    $f = false;
                                    if(customCompute($opuniquepercentageArr)) {
                                        if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
                                            $f = true;
                                        }
                                    }
                                    if(customCompute($anotheropuniquepercentageArr)) {
                                        if(isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
                                            $f = true;
                                        }
                                    }

                                    if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
                                        if($student->sroptionalsubjectID != 0) {
                                            if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
                                                if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                    $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
                                                }
                                            } else {
                                                if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                    $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
                                                }
                                            }

                                            if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

                                                if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                                    $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    if($f) {
                                                        $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                                    }
                                                }

                                            }
                                        }
                                        if($student->sranotheroptionalsubjectID != 0) {
                                            if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
                                                if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                    $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
                                                }
                                            } else {
                                                if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                    $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
                                                }
                                            }

                                            if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

                                                if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                                    $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    if($f) {
                                                        $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                    }
                                                }

                                            }
                                        }
                                        $studentChecker['subject'][$student->srstudentID][$markpercentageID] = TRUE;
                                    }
                                }
                            }

                            $studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];

                            if(!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
                                if($student->sroptionalsubjectID != 0) {
                                    $studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
                                }
                                if($student->sranotheroptionalsubjectID != 0) {
                                    $studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
                                }
                                $studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
                            }

                            $studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
                            if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
                                if($student->sroptionalsubjectID != 0) {
                                    $studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
                                }
                                if($student->sranotheroptionalsubjectID != 0) {
                                    $studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
                                }
                            }
                        }
                    }	

                    if(customCompute($coscholasticSubjects)) {
                        foreach ($coscholasticSubjects as $coscholasticSubject) {
                            $uniquepercentageArr1 = isset($markpercentagesArr1[$coscholasticSubject->subjectID]) ? $markpercentagesArr1[$coscholasticSubject->subjectID] : [];

                            $markpercentages1 = $uniquepercentageArr1[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                            $markpercentagesCount1 = customCompute($markpercentages);
                            if(customCompute($markpercentages1)) {
                                foreach ($markpercentages1 as $markpercentageID) {
                                    $f = false;
                                    if(isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
                                        $f = true;
                                    }

                                    if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID])) {
                                        if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
                                            $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
                                        } else {
                                            $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += 0;
                                        }
                                    } else {
                                        if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
                                            $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
                                        } else {
                                            $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = 0;
                                        }
                                    }

                                    if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
                                        $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];

                                        if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                            $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];
                                        } else {
                                            $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];

                                        }
                                    }

                                    $f = false;
                                    if(customCompute($opuniquepercentageArr1)) {
                                        if(isset($opuniquepercentageArr1['own']) && in_array($markpercentageID, $opuniquepercentageArr1['own'])) {
                                            $f = true;
                                        }
                                    }

                                    if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
                                        if($student->sroptionalsubjectID != 0) {
                                            if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
                                                if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                    $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
                                                }
                                            } else {
                                                if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                    $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
                                                }
                                            }

                                            if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

                                                if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                                    $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    if($f) {
                                                        $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                                    }
                                                }

                                            }
                                        }

                                        if($student->sranotheroptionalsubjectID != 0) {
                                            if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
                                                if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                    $studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
                                                }
                                            } else {
                                                if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                    $studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
                                                }
                                            }

                                            if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

                                                if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                                    $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    if($f) {
                                                        $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                    }
                                                }

                                            }
                                        }

                                        $studentChecker1['subject'][$student->srstudentID][$markpercentageID] = TRUE;
                                    }
                                }
                            }

                            $studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];

                            if(!isset($studentChecker1['totalSubjectMark'][$student->srstudentID])) {
                                // if($student->sroptionalsubjectID != 0) {
                                // 	$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
                                // }
                                $studentChecker1['totalSubjectMark'][$student->srstudentID] = TRUE;
                            }

                            $studentSubjectPositionArray1[$coscholasticSubject->subjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];
                            if(!isset($studentChecker1['studentSubjectPositionArray'][$student->srstudentID])) {
                                // if($student->sroptionalsubjectID != 0) {
                                // 	$studentSubjectPositionArray1[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
                                // }
                            }
                        }
                    }

                    $studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
                    $studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

                    if(isset($studentPosition['totalStudentMarkAverage'])) {
                        $studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
                    } else {
                        $studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
                    }

                    if(count($coscholasticSubjects) > 0) {
                        $studentPosition1[$student->srstudentID]['classPositionMark'] = ($studentPosition1[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition1[$student->srstudentID]['subjectMark']));
                        $studentClassPositionArray1[$student->srstudentID]             = $studentPosition1[$student->srstudentID]['classPositionMark'];	
                    
                        if(isset($studentPosition1['totalStudentMarkAverage'])) {
                            $studentPosition1['totalStudentMarkAverage'] += $studentPosition1[$student->srstudentID]['classPositionMark'];
                        } else {
                            $studentPosition1['totalStudentMarkAverage']  = $studentPosition1[$student->srstudentID]['classPositionMark'];
                        }
                    }
                }
            }

            arsort($studentClassPositionArray);
            $studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
            if(customCompute($studentSubjectPositionArray)) {
                foreach($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
                    arsort($studentSubjectPositionMark);
                    $studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
                }
            }
            if((int)$studentID > 0) {
                $queryArray['studentID'] = $studentID;
            }

            arsort($studentClassPositionArray1);
            $studentPosition1['studentClassPositionArray'] = $studentClassPositionArray1;
            if(customCompute($studentSubjectPositionArray1)) {
                foreach($studentSubjectPositionArray1 as $subjectID => $studentSubjectPositionMark1) {
                    arsort($studentSubjectPositionMark1);
                    $studentPosition1['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark1;
                }
            }
            if((int)$studentID > 0) {
                $queryArray['studentID'] = $studentID;
            }

            $this->data['col']             = 5 + $markpercentagesCount;
            $this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
            $this->data['studentPosition'] = $studentPosition;
            $this->data['percentageArr']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
            
            $this->data['col1']             = 5 + $markpercentagesCount1;
            $this->data['studentPosition1'] = $studentPosition1;
            $this->data['percentageArr1']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

            // if($class->classes_numeric == 1 || $class->classes_numeric == 2 || $class->classes_numeric == 3) {
            //     $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/PrimaryTerminalReportPDF', 'view', 'a4', 'portrait');
            // } else if($class->classes_numeric == 11 || $class->classes_numeric == 12) {
            //     $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF_11_12', 'view', 'a4', 'portrait');
            // } else {
                // $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF', 'view', 'a4', 'portrait');
            // }
            

            if ($class->classes_numeric >= 1 && $class->classes_numeric <= 7) {
                $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF_1_to_7', 'view', 'a4', 'landscape', 'custom');
            } elseif(strpos($class->classes, 'Nursery') !== false  || strpos($class->classes, 'KG') !== false){
                $this->reportPDF('terminalreport2.css', $this->data, 'report/terminal2/TerminalReportPDF', 'view', 'a4', 'landscape');
            } else {
                if ($examID == 4 || $examID == 5) {
                    $this->reportPDF('terminalreport_final.css', $this->data, 'report/terminal1/TerminalReportPDF_finalterm', 'view', 'a4', 'landscape', 'custom');
                } else {
                    $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF', 'view', 'a4', 'landscape');
                }
            }

        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function finalreport () {

        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if($this->session->userdata('usertypeID') == 3) {
            $studentID = $this->session->userdata('loginuserID');
            $student_data = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID), TRUE);
            $classesID = $student_data->srclassesID;
            $sectionID = $student_data->srsectionID;
        } else if ($this->session->userdata('usertypeID') == 4) {
            $studentID = htmlentities(escapeString($this->uri->segment(4)));
            $student = $this->student_m->general_get_single_student(['studentID' => $studentID]);
            $student_data = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID), TRUE);
            $classesID = $student_data->srclassesID;
            $sectionID = $student_data->srsectionID;
        } else {
            $this->data["subview"] = "errorpermission";
        	$this->load->view('_layout_main', $this->data);
        }
        $finaltermexamID = htmlentities(escapeString($this->uri->segment(3)));
		
				   
			
					$this->data['setting'] = $this->setting_m->get_setting();
					$this->data['examID']     = $finaltermexamID;
					$this->data['classesID']  = $classesID;
					$this->data['sectionID']  = $sectionID;
					$this->data['studentIDD'] = $studentID;
					$this->data['date'] 	  = '';
					$this->data['verified_by'] = '';
					$this->data['school_days'] = '';

					$queryArray        = [];
					$studentQueryArray = [];
					$queryArray['schoolyearID']          = $schoolyearID;
					$studentQueryArray['srschoolyearID'] = $schoolyearID;

					if((int)$classesID > 0) {
						$queryArray['classesID'] = $classesID;
						$studentQueryArray['srclassesID'] = $classesID;
					} 
					if((int)$sectionID > 0) {
						$queryArray['sectionID'] = $sectionID;
						$studentQueryArray['srsectionID'] = $sectionID;
					}
					if((int)$studentID > 0) {
						$studentQueryArray['srstudentID'] = $studentID;
					}

					$class = $this->classes_m->get_single_classes(['classesID' => $classesID]);
					$this->data['class'] = $class;
					$exam      = $this->exam_m->get_single_exam(['examID'=> $finaltermexamID]);
					$this->data['exam']         = $exam;
					$this->data['exam']->date_in_nepali = $this->convertDateToNepaliInEnglish($exam->date);
					$this->data['exam']->issue_date_in_english = $this->convertDateToEnglishInNepali($exam->issue_date);
					$this->data['examName']     = $exam->exam;
					$this->data['grades']       = $this->grade_m->get_grade();
					$this->data['classes']      = pluck($this->classes_m->general_get_classes(),'classes','classesID');
					$this->data['sections']     = pluck($this->section_m->general_get_section(),'section','sectionID');
					$this->data['class_teacher']= pluck($this->section_m->get_join_sections(),'name','sectionID');
					$this->data['groups']       = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
					$this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);
					$this->data['remarks'] 		= pluck($this->studentremark_m->get_order_by_studentremark(['examID' => $finaltermexamID, 'classID' => $classesID]), 'remarks', 'studentID');
					$students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
				
					$mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
					$coscholasticSubjects      = $this->subject_m->get_subject_only_coscholastic(array('classesID' => $classesID, 'type' => 1));
					
					$this->data['mandatorySubjects'] = $mandatorySubjects;
					$this->data['coscholasticSubjects'] = $coscholasticSubjects;

					$this->subject_m->order('type DESC');
					$this->data['subjects'] = $this->subject_m->get_by_class_id($classesID);
					$this->data['subject_marks'] = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $finaltermexamID,'class_id' => $classesID]), 'fullmark', 'subject_id');
		
					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$settingmarktypeID1      = $this->data['siteinfos']->marktypeID;
					$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
					$markpercentagesmainArr1 = $this->marksetting_m->get_marksetting_markpercentages();

					$examtermSettings = $this->examtermsetting_m->get_examtermsetting_with_examtermsettingrelation2([
						'classesID' => $classesID,
						'schoolyearID' => $schoolyearID,
						'finaltermexamID' => $finaltermexamID
					]);

                    if(!customCompute($examtermSettings)){
                        $this->session->set_flashdata('error', 'Final term setting not available.');
                        redirect(base_url('result/finalterms'));
                    }
					
			        $newstudentPositionarray = [];
			        $newstudentPositionarray1 = [];
					$newExamwiseSubjectMark = [];
			        if(customCompute($examtermSettings)){

			         	$this->data['examtermSettings'] = $examtermSettings;

                        foreach($examtermSettings as $examtermSetting){
							$examID = $examtermSetting->examID;
							if((int)$examID > 0) {
								$queryArray['examID'] = $examID;
							} 

							$examwiseSubjectMark = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID,'class_id' => $classesID]), 'fullmark', 'subject_id');
				
					        $marks                  = $this->mark_m->student_all_mark_array($queryArray);
					        $accmarkpercentagesArr[$examID] = $markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
					        $accmarkpercentagesArr1[$examID] = $markpercentagesArr1     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
					
							if(!$markpercentagesArr){
								$retArray['status'] = FALSE;
								$retArray['errorMessage'] = "Mismatch setting! please check mark setting and final term setting.";
								echo json_encode($retArray);
								exit;
							}
							$this->data['markpercentagesArr']  = $accmarkpercentagesArr;
							$this->data['settingmarktypeID']   = $settingmarktypeID;

							$this->data['markpercentagesArr1']  = $accmarkpercentagesArr1;
							$this->data['settingmarktypeID1']   = $settingmarktypeID;

							$retMark = [];
							if(customCompute($marks)) {
								foreach ($marks as $mark) {
									$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
								}
							}

							$retMark1 = [];
							if(customCompute($marks)) {
								foreach ($marks as $mark) {
									$retMark1[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
								}
							}

							$highestMarks    = [];
							foreach ($marks as $value) {
								if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
									$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
								}
								$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
							}

					        $this->data['highestmarks']      = $highestMarks;

							$studentPosition             = [];
							$studentChecker              = [];
							$studentClassPositionArray   = [];
							$studentSubjectPositionArray = [];
							$markpercentagesCount        = 0;

							$studentPosition1             = [];
							$studentChecker1              = [];
							$studentClassPositionArray1   = [];
							$studentSubjectPositionArray1 = [];
							$markpercentagesCount1        = 0;

							if(customCompute($this->data['studentLists'])) {
								foreach($this->data['studentLists'] as $student) {
									$student->dob_in_bs = $this->convertDateToNepaliInEnglish($student->dob);
								}
							}

							if(customCompute($students)) {
								foreach ($students as $student) {
									$opuniquepercentageArr = [];
									$anotheropuniquepercentageArr = [];
									if($student->sroptionalsubjectID > 0) {
										$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
									}

									if($student->sranotheroptionalsubjectID > 0) {
										$anotheropuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
									}

									$opuniquepercentageArr1 = [];
									if($student->sroptionalsubjectID > 0) {
										$opuniquepercentageArr1 = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
									}

									$anotheropuniquepercentageArr1 = [];
									if($student->sranotheroptionalsubjectID > 0) {
										$anotheropuniquepercentageArr1 = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
									}

									$studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;

									$studentPosition1[$student->srstudentID]['totalSubjectMark'] = 0;

									if(customCompute($mandatorySubjects)) {
										foreach ($mandatorySubjects as $mandatorySubject) {
											$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];
											$markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
											$markpercentagesCount = customCompute($markpercentages);
											if(customCompute($markpercentages)) {
												foreach ($markpercentages as $markpercentageID) {
													
													$f = false;
													if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
														$f = true;
													}

													if(isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
														if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
															$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
														}
													} else {
														if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
															$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
														}
													}

													if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
														$studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

														if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];

														}
													}

													$f = false;
													if(customCompute($opuniquepercentageArr)) {
														if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
															$f = true;
														}
													}
													if(customCompute($anotheropuniquepercentageArr)) {
														if(isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
															$f = true;
														}
													}

													if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
														if($student->sroptionalsubjectID != 0) {
															if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
																if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
																}
															} else {
																if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
																}
															}

															if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

																if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
																} else {
																	if($f) {
																		$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
																	}
																}

															}
														}
														if($student->sranotheroptionalsubjectID != 0) {
															if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
																if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
																}
															} else {
																if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
																}
															}

															if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

																if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																	$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
																} else {
																	if($f) {
																		$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
																	}
																}

															}
														}
														$studentChecker['subject'][$student->srstudentID][$markpercentageID] = TRUE;
													}
												}
											}

											$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];

											if(!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
												if($student->sroptionalsubjectID != 0) {
													$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
												}
												if($student->sranotheroptionalsubjectID != 0) {
													$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
												}
												$studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
											}

											$studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
											if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
												if($student->sroptionalsubjectID != 0) {
													$studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
												}
												if($student->sranotheroptionalsubjectID != 0) {
													$studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
												}
											}
										}
									}	

									if(customCompute($coscholasticSubjects)) {
										foreach ($coscholasticSubjects as $coscholasticSubject) {
											$uniquepercentageArr1 = isset($markpercentagesArr1[$coscholasticSubject->subjectID]) ? $markpercentagesArr1[$coscholasticSubject->subjectID] : [];

											$markpercentages1 = $uniquepercentageArr1[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
											$markpercentagesCount1 = customCompute($markpercentages);
											if(customCompute($markpercentages1)) {
												foreach ($markpercentages1 as $markpercentageID) {
													$f = false;
													if(isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
														$f = true;
													}

													if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID])) {
														if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
															$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += 0;
														}
													} else {
														if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
															$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = 0;
														}
													}

													if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
														$studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];

														if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];

														}
													}

													$f = false;
													if(customCompute($opuniquepercentageArr1)) {
														if(isset($opuniquepercentageArr1['own']) && in_array($markpercentageID, $opuniquepercentageArr1['own'])) {
															$f = true;
														}
													}

													if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
														if($student->sroptionalsubjectID != 0) {
															if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
																if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
																}
															} else {
																if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
																}
															}

															if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																$studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

																if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
																} else {
																	if($f) {
																		$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
																	}
																}

															}
														}

														if($student->sranotheroptionalsubjectID != 0) {
															if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
																if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
																}
															} else {
																if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
																} else {
																	$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
																}
															}

															if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
																$studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

																if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
																	$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
																} else {
																	if($f) {
																		$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
																	}
																}

															}
														}

														$studentChecker1['subject'][$student->srstudentID][$markpercentageID] = TRUE;
													}
												}
											}

											$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];

											if(!isset($studentChecker1['totalSubjectMark'][$student->srstudentID])) {
												// if($student->sroptionalsubjectID != 0) {
												// 	$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
												// }
												$studentChecker1['totalSubjectMark'][$student->srstudentID] = TRUE;
											}

											$studentSubjectPositionArray1[$coscholasticSubject->subjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];
											if(!isset($studentChecker1['studentSubjectPositionArray'][$student->srstudentID])) {
												// if($student->sroptionalsubjectID != 0) {
												// 	$studentSubjectPositionArray1[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
												// }
											}
										}
									}


									$studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
									$studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

									if(isset($studentPosition['totalStudentMarkAverage'])) {
										$studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
									} else {
										$studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
									}

									if(count($coscholasticSubjects) > 0) {
										$studentPosition1[$student->srstudentID]['classPositionMark'] = ($studentPosition1[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition1[$student->srstudentID]['subjectMark']));
										$studentClassPositionArray1[$student->srstudentID]             = $studentPosition1[$student->srstudentID]['classPositionMark'];	
									
										if(isset($studentPosition1['totalStudentMarkAverage'])) {
											$studentPosition1['totalStudentMarkAverage'] += $studentPosition1[$student->srstudentID]['classPositionMark'];
										} else {
											$studentPosition1['totalStudentMarkAverage']  = $studentPosition1[$student->srstudentID]['classPositionMark'];
										}
									}
									
									
								}
							}

							arsort($studentClassPositionArray);
							$studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
							if(customCompute($studentSubjectPositionArray)) {
								foreach($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
									arsort($studentSubjectPositionMark);
									$studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
								}
							}
							if((int)$studentID > 0) {
								$queryArray['studentID'] = $studentID;
							}

							arsort($studentClassPositionArray1);
							$studentPosition1['studentClassPositionArray'] = $studentClassPositionArray1;
							if(customCompute($studentSubjectPositionArray1)) {
								foreach($studentSubjectPositionArray1 as $subjectID => $studentSubjectPositionMark1) {
									arsort($studentSubjectPositionMark1);
									$studentPosition1['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark1;
								}
							}
							if((int)$studentID > 0) {
								$queryArray['studentID'] = $studentID;
							}

							$newstudentPositionarray[$examID] = $studentPosition;
							$newstudentPositionarray1[$examID] = $studentPosition1;
							$newExamwiseSubjectMark[$examID]   = $examwiseSubjectMark; 

					}}


					$this->data['examwise_subject_marks']   = $newExamwiseSubjectMark;
					$this->data['col']             = 5 + $markpercentagesCount;
					$this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
					$this->data['studentPosition'] = $newstudentPositionarray;
					$this->data['percentageArr']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

					$this->data['col1']             = 5 + $markpercentagesCount1;
					$this->data['studentPosition1'] = $newstudentPositionarray1;
					$this->data['percentageArr1']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
                    

					//  $retArray['render'] = $this->load->view('report/finalterminal/TerminalReport',$this->data,true);
					 
                    $this->data["subview"] = "report/finalterminal/TerminalReport";
                    
                    $this->load->view('_layout_main', $this->data);
			
			
		
	}
    
    public function finaltermpdf() {

			$examID = htmlentities(escapeString($this->uri->segment(3)));
			$classesID  = htmlentities(escapeString($this->uri->segment(4)));
			$sectionID  = htmlentities(escapeString($this->uri->segment(5)));
			$studentID  = htmlentities(escapeString($this->uri->segment(6)));
			$date  = '';
			$verified_by  = '';
			$school_days  = '';
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if((int)$examID && (int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
				$this->data['examID']      = $examID;
				$this->data['classesID']   = $classesID;
				$this->data['sectionID']   = $sectionID;
				$this->data['studentIDD']  = $studentID;
				$this->data['date']        = $date;
				$this->data['verified_by'] = $verified_by;
				$this->data['school_days'] = $school_days;

				$queryArray        = [];
				$studentQueryArray = [];
				$queryArray['schoolyearID']          = $schoolyearID;
				$studentQueryArray['srschoolyearID'] = $schoolyearID;

				if((int)$examID > 0) {
					$queryArray['examID'] = $examID;
				} 
				if((int)$classesID > 0) {
					$queryArray['classesID'] = $classesID;
					$studentQueryArray['srclassesID'] = $classesID;
				} 
				if((int)$sectionID > 0) {
					$queryArray['sectionID'] = $sectionID;
					$studentQueryArray['srsectionID'] = $sectionID;
				}
				if((int)$studentID > 0) {
					$studentQueryArray['srstudentID'] = $studentID;
				}

				$class = $this->classes_m->get_single_classes(['classesID' => $classesID]);
				$exam      = $this->exam_m->get_single_exam(['examID'=> $examID]);
				$this->data['class'] = $class;
				$this->data['exam'] = $exam;
				$this->data['exam']->date_in_nepali = $this->convertDateToNepaliInEnglish($exam->date);
				$this->data['exam']->issue_date_in_english = $this->convertDateToEnglishInNepali($exam->issue_date);
				$this->data['examName']     = $exam->exam;
				$this->data['grades']       = $this->grade_m->get_grade();
				$this->data['classes']      = pluck($this->classes_m->general_get_classes(),'classes','classesID');
				$this->data['sections']     = pluck($this->section_m->general_get_section(),'section','sectionID');
				$this->data['class_teacher']= pluck($this->section_m->get_join_sections(),'name','sectionID');
				$this->data['groups']       = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
				$this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);
				$this->data['remarks'] 		= pluck($this->studentremark_m->get_order_by_studentremark(['examID' => $examID, 'classID' => $classesID]), 'remarks', 'studentID');
				$students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
				$marks                  = $this->mark_m->student_all_mark_array($queryArray);
				$mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
				$coscholasticSubjects      = $this->subject_m->get_subject_only_coscholastic(array('classesID' => $classesID, 'type' => 1));
				
				$this->data['mandatorySubjects'] = $mandatorySubjects;
				$this->data['coscholasticSubjects'] = $coscholasticSubjects;

				$this->subject_m->order('type DESC');
				$this->data['subjects'] = $this->subject_m->get_by_class_id($classesID);
				$this->data['subject_marks'] = pluck($this->subjectmark_m->get_order_by_subject_marks(['exam_id' => $examID,'class_id' => $classesID]), 'fullmark', 'subject_id');


				$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
				$settingmarktypeID1      = $this->data['siteinfos']->marktypeID;
				$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
				$markpercentagesmainArr1 = $this->marksetting_m->get_marksetting_markpercentages();
				$markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
				$markpercentagesArr1     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
				$this->data['markpercentagesArr']  = $markpercentagesArr;
				$this->data['settingmarktypeID']   = $settingmarktypeID;

				$this->data['markpercentagesArr1']  = $markpercentagesArr;
				$this->data['settingmarktypeID1']   = $settingmarktypeID;

				$retMark = [];
				if(customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
					}
				}

				$retMark1 = [];
				if(customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark1[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark?$mark->mark:0;
					}
				}

				$highestMarks    = [];
				foreach ($marks as $value) {
					if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
						$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
					}
					$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
				}

				$this->data['highestmarks']      = $highestMarks;

				$studentPosition             = [];
				$studentChecker              = [];
				$studentClassPositionArray   = [];
				$studentSubjectPositionArray = [];
				$markpercentagesCount        = 0;

				$studentPosition1             = [];
				$studentChecker1              = [];
				$studentClassPositionArray1   = [];
				$studentSubjectPositionArray1 = [];
				$markpercentagesCount1        = 0;

				if(customCompute($this->data['studentLists'])) {
					foreach($this->data['studentLists'] as $student) {
						$student->dob_in_bs = $this->convertDateToNepaliInEnglish($student->dob);
					}
				}
					
					if(customCompute($students)) {
						foreach ($students as $student) {
							$opuniquepercentageArr = [];
							$anotheropuniquepercentageArr = [];
							if($student->sroptionalsubjectID > 0) {
								$opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
							}

							if($student->sranotheroptionalsubjectID > 0) {
								$anotheropuniquepercentageArr = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
							}

							$opuniquepercentageArr1 = [];
							$anotheropuniquepercentageArr1 = [];
							if($student->sroptionalsubjectID > 0) {
								$opuniquepercentageArr1 = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
							}

							if($student->sranotheroptionalsubjectID > 0) {
								$anotheropuniquepercentageArr1 = isset($markpercentagesArr[$student->sranotheroptionalsubjectID]) ? $markpercentagesArr[$student->sranotheroptionalsubjectID] : [];
							}

							$studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;

							$studentPosition1[$student->srstudentID]['totalSubjectMark'] = 0;

							if(customCompute($mandatorySubjects)) {
								foreach ($mandatorySubjects as $mandatorySubject) {
									$uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

									$markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
									$markpercentagesCount = customCompute($markpercentages);
									if(customCompute($markpercentages)) {
										foreach ($markpercentages as $markpercentageID) {
											$f = false;
                                            if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                                $f = true;
                                            }

											if(isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
												if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
												}
											} else {
												if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
												}
											}

											if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

												if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
													$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
												} else {
													$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];

												}
											}

											$f = false;
											if(customCompute($opuniquepercentageArr)) {
	                                            if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
	                                                $f = true;
	                                            }
											}
											if(customCompute($anotheropuniquepercentageArr)) {
	                                            if(isset($anotheropuniquepercentageArr['own']) && in_array($markpercentageID, $anotheropuniquepercentageArr['own'])) {
	                                                $f = true;
	                                            }
											}

											if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
												if($student->sroptionalsubjectID != 0) {
													if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
														if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
														}
													} else {
														if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
														}
													}

													if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

														if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															if($f) {
																$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
															}
														}

													}
												}
												if($student->sranotheroptionalsubjectID != 0) {
													if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
														if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
														}
													} else {
														if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
														}
													}

													if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

														if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															if($f) {
																$studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
															}
														}

													}
												}
												$studentChecker['subject'][$student->srstudentID][$markpercentageID] = TRUE;
											}
										}
									}

									$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];

									if(!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
										if($student->sroptionalsubjectID != 0) {
											$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
										}
										if($student->sranotheroptionalsubjectID != 0) {
											$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
										}
										$studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
									}

									$studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
									if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
										if($student->sroptionalsubjectID != 0) {
											$studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
										}
										if($student->sranotheroptionalsubjectID != 0) {
											$studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID];
										}
									}
								}
							}	

							if(customCompute($coscholasticSubjects)) {
								foreach ($coscholasticSubjects as $coscholasticSubject) {
									$uniquepercentageArr1 = isset($markpercentagesArr1[$coscholasticSubject->subjectID]) ? $markpercentagesArr1[$coscholasticSubject->subjectID] : [];

									$markpercentages1 = $uniquepercentageArr1[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
									$markpercentagesCount1 = customCompute($markpercentages);
									if(customCompute($markpercentages1)) {
										foreach ($markpercentages1 as $markpercentageID) {
											$f = false;
                                            if(isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
                                                $f = true;
                                            }

											if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID])) {
												if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
													$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
												} else {
													$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += 0;
												}
											} else {
												if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
													$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
												} else {
													$studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = 0;
												}
											}

											if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
												$studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];

												if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
													$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];
												} else {
													$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];

												}
											}

											$f = false;
											if(customCompute($opuniquepercentageArr1)) {
	                                            if(isset($opuniquepercentageArr1['own']) && in_array($markpercentageID, $opuniquepercentageArr1['own'])) {
	                                                $f = true;
	                                            }
											}

											if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
												if($student->sroptionalsubjectID != 0) {
													if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
														if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
														}
													} else {
														if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
														}
													}

													if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
														$studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

														if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
														} else {
															if($f) {
																$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
															}
														}

													}
												}

												if($student->sranotheroptionalsubjectID != 0) {
													if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID])) {
														if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] += 0;
														}
													} else {
														if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															$studentPosition1[$student->srstudentID]['subjectMark'][$student->sranotheroptionalsubjectID] = 0;
														}
													}

													if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
														$studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

														if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
															$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
														} else {
															if($f) {
																$studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];
															}
														}

													}
												}

												$studentChecker1['subject'][$student->srstudentID][$markpercentageID] = TRUE;
											}
										}
									}

									$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];

									if(!isset($studentChecker1['totalSubjectMark'][$student->srstudentID])) {
										// if($student->sroptionalsubjectID != 0) {
										// 	$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
										// }
										$studentChecker1['totalSubjectMark'][$student->srstudentID] = TRUE;
									}

									$studentSubjectPositionArray1[$coscholasticSubject->subjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];
									if(!isset($studentChecker1['studentSubjectPositionArray'][$student->srstudentID])) {
										// if($student->sroptionalsubjectID != 0) {
										// 	$studentSubjectPositionArray1[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
										// }
									}
								}
							}


							$studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
							$studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

							if(isset($studentPosition['totalStudentMarkAverage'])) {
								$studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
							} else {
								$studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
							}

							if(count($coscholasticSubjects) > 0) {
								$studentPosition1[$student->srstudentID]['classPositionMark'] = ($studentPosition1[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition1[$student->srstudentID]['subjectMark']));
								$studentClassPositionArray1[$student->srstudentID]             = $studentPosition1[$student->srstudentID]['classPositionMark'];	
							
								if(isset($studentPosition1['totalStudentMarkAverage'])) {
									$studentPosition1['totalStudentMarkAverage'] += $studentPosition1[$student->srstudentID]['classPositionMark'];
								} else {
									$studentPosition1['totalStudentMarkAverage']  = $studentPosition1[$student->srstudentID]['classPositionMark'];
								}
							}
							
							
						}
					}

					arsort($studentClassPositionArray);
					$studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
					if(customCompute($studentSubjectPositionArray)) {
						foreach($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
							arsort($studentSubjectPositionMark);
							$studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
						}
					}
					if((int)$studentID > 0) {
						$queryArray['studentID'] = $studentID;
					}

					arsort($studentClassPositionArray1);
					$studentPosition1['studentClassPositionArray'] = $studentClassPositionArray1;
					if(customCompute($studentSubjectPositionArray1)) {
						foreach($studentSubjectPositionArray1 as $subjectID => $studentSubjectPositionMark1) {
							arsort($studentSubjectPositionMark1);
							$studentPosition1['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark1;
						}
					}
					if((int)$studentID > 0) {
						$queryArray['studentID'] = $studentID;
					}

					$this->data['col']             = 5 + $markpercentagesCount;
					$this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
					$this->data['studentPosition'] = $studentPosition;
					$this->data['percentageArr']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

					

					$this->data['col1']             = 5 + $markpercentagesCount1;
					$this->data['studentPosition1'] = $studentPosition1;
					$this->data['percentageArr1']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

					// if($class->classes_numeric == 1 || $class->classes_numeric == 2 || $class->classes_numeric == 3) {
						// $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/PrimaryTerminalReportPDF', 'view', 'a4', 'portrait');
					// } else if($class->classes_numeric == 11 || $class->classes_numeric == 12) {	
					// 	$this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF_11_12', 'view', 'a4', 'portrait');
					// } else {
						$this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF', 'view', 'a4', 'portrait');
					// }
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			
	}

    public function kindergartenpdf() {
        $examID = htmlentities(escapeString($this->uri->segment(3)));
        $classesID  = htmlentities(escapeString($this->uri->segment(4)));
        $sectionID  = htmlentities(escapeString($this->uri->segment(5)));
        $studentID  = htmlentities(escapeString($this->uri->segment(6)));
        $date  = htmlentities(escapeString($this->uri->segment(7)));
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if((int)$examID && (int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
            $this->data['examID']     = $examID;
            $this->data['classesID']  = $classesID;
            $this->data['sectionID']  = $sectionID;
            $this->data['studentIDD'] = $studentID;
            $this->data['date'] = urldecode($date);

            $queryArray        = [];
            $studentQueryArray = [];
            $queryArray['schoolyearID']          = $schoolyearID;
            $studentQueryArray['srschoolyearID'] = $schoolyearID;

            if((int)$examID > 0) {
                $queryArray['examID'] = $examID;
            } 
            if((int)$classesID > 0) {
                $queryArray['classesID'] = $classesID;
                $studentQueryArray['srclassesID'] = $classesID;
            }
            if((int)$sectionID > 0) {
                $queryArray['sectionID'] = $sectionID;
                $studentQueryArray['srsectionID'] = $sectionID;
            }
            if((int)$studentID > 0) {
                $studentQueryArray['srstudentID'] = $studentID;
            }

            $exam      = $this->exam_m->get_single_exam(['examID'=> $examID]);
            $this->data['examName']     = $exam->exam;
            $this->data['grades']       = $this->grade_m->get_grade();
            $this->data['classes']      = pluck($this->classes_m->general_get_classes(),'classes','classesID');
            $this->data['sections']     = pluck($this->section_m->general_get_section(),'section','sectionID');
            $this->data['groups']       = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
            $this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);


            $students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
            $marks                  = $this->mark_m->student_all_mark_array($queryArray);
            $mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
            $coscholasticSubjects      = $this->subject_m->get_subject_only_coscholastic(array('classesID' => $classesID, 'type' => 1));
            
			      $this->data['mandatorySubjects'] = $mandatorySubjects;
            $this->data['coscholasticSubjects'] = $coscholasticSubjects;

            $this->subject_m->order('type DESC');
            $this->data['subjects'] = $this->subject_m->get_by_class_id($classesID);

            $settingmarktypeID      = $this->data['siteinfos']->marktypeID;
            $settingmarktypeID1      = $this->data['siteinfos']->marktypeID;
            $markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
            $markpercentagesmainArr1 = $this->marksetting_m->get_marksetting_markpercentages();
            $markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
            $markpercentagesArr1     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
            $this->data['markpercentagesArr']  = $markpercentagesArr;
            $this->data['settingmarktypeID']   = $settingmarktypeID;

            $this->data['markpercentagesArr1']  = $markpercentagesArr;
            $this->data['settingmarktypeID1']   = $settingmarktypeID;

            $retMark = [];
            if(customCompute($marks)) {
                foreach ($marks as $mark) {
                    $retMark[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
                }
            }

            $retMark1 = [];
            if(customCompute($marks)) {
                foreach ($marks as $mark) {
                    $retMark1[$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
                }
            }

            $studentPosition             = [];
            $studentChecker              = [];
            $studentClassPositionArray   = [];
            $studentSubjectPositionArray = [];
            $markpercentagesCount        = 0;

            
            $studentPosition1             = [];
            $studentChecker1              = [];
            $studentClassPositionArray1   = [];
            $studentSubjectPositionArray1 = [];
            $markpercentagesCount1        = 0;

            
            if(customCompute($students)) {
                foreach ($students as $student) {
                    $opuniquepercentageArr = [];
                    if($student->sroptionalsubjectID > 0) {
                        $opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
                    }

                    $opuniquepercentageArr1 = [];
                    if($student->sroptionalsubjectID > 0) {
                        $opuniquepercentageArr1 = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];
                    }

                    $studentPosition[$student->srstudentID]['totalSubjectMark'] = 0;
                    
                    $studentPosition1[$student->srstudentID]['totalSubjectMark'] = 0;
                    
                    if(customCompute($mandatorySubjects)) {
                        foreach ($mandatorySubjects as $mandatorySubject) {
                            $uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

                            $markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                            $markpercentagesCount = customCompute($markpercentages);
                            if(customCompute($markpercentages)) {
                                foreach ($markpercentages as $markpercentageID) {
                                    $f = false;
                                    if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                        $f = true;
                                    }

                                    if(isset($studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID])) {
                                        if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
                                            $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
                                        } else {
                                            $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] += 0;
                                        }
                                    } else {
                                        if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
                                            $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
                                        } else {
                                            $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID] = 0;
                                        }
                                    }

                                    if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
                                        $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

                                        if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                            $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
                                        } else {
                                            $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];

                                        }
                                    }

                                    $f = false;
                                    if(customCompute($opuniquepercentageArr)) {
                                        if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
                                            $f = true;
                                        }
                                    }

                                    if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
                                        if($student->sroptionalsubjectID != 0) {
                                            if(isset($studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
                                                if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                    $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
                                                }
                                            } else {
                                                if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                    $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
                                                }
                                            }

                                            if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

                                                if(isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                                    $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    if($f) {
                                                        $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                                    }
                                                }

                                            }
                                        }
                                        $studentChecker['subject'][$student->srstudentID][$markpercentageID] = TRUE;
                                    }
                                }
                            }

                            $studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];

                            if(!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
                                if($student->sroptionalsubjectID != 0) {
                                    $studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
                                }
                                $studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
                            }

                            $studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$mandatorySubject->subjectID];
                            if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
                                if($student->sroptionalsubjectID != 0) {
                                    $studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
                                }
                            }
                        }
                    }	

                    if(customCompute($coscholasticSubjects)) {
                        foreach ($coscholasticSubjects as $coscholasticSubject) {
                            $uniquepercentageArr1 = isset($markpercentagesArr1[$coscholasticSubject->subjectID]) ? $markpercentagesArr1[$coscholasticSubject->subjectID] : [];

                            $markpercentages1 = $uniquepercentageArr1[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                            $markpercentagesCount1 = customCompute($markpercentages);
                            if(customCompute($markpercentages1)) {
                                foreach ($markpercentages1 as $markpercentageID) {
                                    $f = false;
                                    if(isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
                                        $f = true;
                                    }

                                    if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID])) {
                                        if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
                                            $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
                                        } else {
                                            $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] += 0;
                                        }
                                    } else {
                                        if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
                                            $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
                                        } else {
                                            $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID] = 0;
                                        }
                                    }

                                    if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
                                        $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];

                                        if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                            $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];
                                        } else {
                                            $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$coscholasticSubject->subjectID][$markpercentageID];

                                        }
                                    }

                                    $f = false;
                                    if(customCompute($opuniquepercentageArr1)) {
                                        if(isset($opuniquepercentageArr1['own']) && in_array($markpercentageID, $opuniquepercentageArr1['own'])) {
                                            $f = true;
                                        }
                                    }

                                    if(!isset($studentChecker['subject'][$student->srstudentID][$markpercentageID]) && $f) {
                                        if($student->sroptionalsubjectID != 0) {
                                            if(isset($studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID])) {
                                                if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                    $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] += 0;
                                                }
                                            } else {
                                                if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                    $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID] = 0;
                                                }
                                            }

                                            if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

                                                if(isset($studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID])) {
                                                    $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] += $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                                } else {
                                                    if($f) {
                                                        $studentPosition1[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] = $studentPosition1[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                                    }
                                                }

                                            }
                                        }
                                        $studentChecker1['subject'][$student->srstudentID][$markpercentageID] = TRUE;
                                    }
                                }
                            }

                            $studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];

                            if(!isset($studentChecker1['totalSubjectMark'][$student->srstudentID])) {
                                if($student->sroptionalsubjectID != 0) {
                                    $studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
                                }
                                $studentChecker1['totalSubjectMark'][$student->srstudentID] = TRUE;
                            }

                            $studentSubjectPositionArray1[$coscholasticSubject->subjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$coscholasticSubject->subjectID];
                            if(!isset($studentChecker1['studentSubjectPositionArray'][$student->srstudentID])) {
                                if($student->sroptionalsubjectID != 0) {
                                    $studentSubjectPositionArray1[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['subjectMark'][$student->sroptionalsubjectID];
                                }
                            }
                        }
                    }


                    $studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['subjectMark']));
                    $studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

                    if(isset($studentPosition['totalStudentMarkAverage'])) {
                        $studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
                    } else {
                        $studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
                    }

                    
                    $studentPosition1[$student->srstudentID]['classPositionMark'] = ($studentPosition1[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition1[$student->srstudentID]['subjectMark']));
                    $studentClassPositionArray1[$student->srstudentID]             = $studentPosition1[$student->srstudentID]['classPositionMark'];

                    if(isset($studentPosition1['totalStudentMarkAverage'])) {
                        $studentPosition1['totalStudentMarkAverage'] += $studentPosition1[$student->srstudentID]['classPositionMark'];
                    } else {
                        $studentPosition1['totalStudentMarkAverage']  = $studentPosition1[$student->srstudentID]['classPositionMark'];
                    }
                }
            }

            arsort($studentClassPositionArray);
            $studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
            if(customCompute($studentSubjectPositionArray)) {
                foreach($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
                    arsort($studentSubjectPositionMark);
                    $studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
                }
            }
            if((int)$studentID > 0) {
                $queryArray['studentID'] = $studentID;
            }

            arsort($studentClassPositionArray1);
            $studentPosition1['studentClassPositionArray'] = $studentClassPositionArray1;
            if(customCompute($studentSubjectPositionArray1)) {
                foreach($studentSubjectPositionArray1 as $subjectID => $studentSubjectPositionMark1) {
                    arsort($studentSubjectPositionMark1);
                    $studentPosition1['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark1;
                }
            }
            if((int)$studentID > 0) {
                $queryArray['studentID'] = $studentID;
            }


            $this->data['col']             = 5 + $markpercentagesCount;
            $this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
            $this->data['studentPosition'] = $studentPosition;
            $this->data['percentageArr']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
            

            $this->data['col1']             = 5 + $markpercentagesCount1;
            $this->data['studentPosition1'] = $studentPosition1;
            $this->data['percentageArr1']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

            $this->reportPDF('terminalreport2.css', $this->data, 'report/terminal2/TerminalReportPDF', 'view', 'a4', 'landscape');
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }


    private function get_student_attendance($queryArray, $subjects, $studentlists) {
		unset($queryArray['examID']);
		$newArray = [];
		$attendanceArray = [];
		$getWeekendDay = $this->getWeekendDays();
		$getHoliday    = explode('","', $this->getHolidays());

		if($this->data['siteinfos']->attendance == 'subject') {
			$attendances   = $this->subjectattendance_m->get_order_by_sub_attendance($queryArray);

			if(customCompute($attendances)) {
				foreach ($attendances as $attendance) {
					$monthyearArray = explode('-', $attendance->monthyear);
					$monthDay = date('t', mktime(0, 0, 0, $monthyearArray['0'], 1, $monthyearArray['1'])); 
					for($i=1; $i<=$monthDay; $i++) {
						$currentDate = sprintf("%02d", $i).'-'.$attendance->monthyear;
						if(in_array($currentDate, $getHoliday)) {
							continue;
						} elseif(in_array($currentDate, $getWeekendDay)) {
							continue;
						} else {
							$day = 'a'.$i;
							if($attendance->$day == 'P' || $attendance->$day == 'L' || $attendance->$day == 'LE') {
								if(!isset($newArray[$attendance->studentID][$attendance->subjectID]['pCount'])) {
									$newArray[$attendance->studentID][$attendance->subjectID]['pCount'] = 1;
								} else {
									$newArray[$attendance->studentID][$attendance->subjectID]['pCount'] += 1;
								}
							} else {
								if(!isset($newArray[$attendance->studentID][$attendance->subjectID]['aCount'])) {
									$newArray[$attendance->studentID][$attendance->subjectID]['aCount'] = 1;
								} else {
									$newArray[$attendance->studentID][$attendance->subjectID]['aCount'] += 1;
								}
							}
							if(!isset($newArray[$attendance->studentID][$attendance->subjectID]['tCount'])) {
								$newArray[$attendance->studentID][$attendance->subjectID]['tCount'] = 1;
							} else {
								$newArray[$attendance->studentID][$attendance->subjectID]['tCount'] += 1;
							}
						}
					}
				}

				$studentlistsArray = pluck($studentlists,'sroptionalsubjectID','srstudentID');
				$subjects  = pluck($subjects,'obj','subjectID');

				if(customCompute($newArray)) {
					foreach($newArray as $studentID => $array) {
						$str = '';
						if(customCompute($subjects)) {
							foreach ($subjects as $subjectID => $subject) {
								if($subject->type == '1') {
									$pCount = isset($array[$subjectID]['pCount']) ? $array[$subjectID]['pCount'] : '0';
									$tCount = isset($array[$subjectID]['tCount']) ? $array[$subjectID]['tCount'] : '0';
									$str .= $subjects[$subjectID]->subject .":".$pCount."/".$tCount.',';
								}
							}
						}

						if(isset($studentlistsArray[$studentID]) && $studentlistsArray[$studentID] != '0' ) {
							$pCount = isset($newArray[$studentID][$studentlistsArray[$studentID]]['pCount']) ? $newArray[$studentID][$studentlistsArray[$studentID]]['pCount'] : '0';
							$tCount = isset($newArray[$studentID][$studentlistsArray[$studentID]]['tCount']) ? $newArray[$studentID][$studentlistsArray[$studentID]]['tCount'] : '0';
							$str .= $subjects[$subjectID]->subject .":".$pCount."/".$tCount.',';
						}

						$attendanceArray[$studentID] = $str;
					}
				}
			}
		} else {
			$attendances   = $this->sattendance_m->get_order_by_attendance($queryArray);
			if(customCompute($attendances)) {
				foreach($attendances as $attendance) {
					$monthyearArray = explode('-', $attendance->monthyear);
					$monthDay = date('t', mktime(0, 0, 0, $monthyearArray['0'], 1, $monthyearArray['1'])); 
					for($i=1; $i<=$monthDay; $i++) {
						$currentDate = sprintf("%02d", $i).'-'.$attendance->monthyear;
						if(in_array($currentDate, $getHoliday)) {
							continue;
						} elseif(in_array($currentDate, $getWeekendDay)) {
							continue;
						} else {
							$day = 'a'.$i;
							if($attendance->$day == 'P' || $attendance->$day == 'L' || $attendance->$day == 'LE') {
								if(!isset($newArray[$attendance->studentID]['pCount'])) {
									$newArray[$attendance->studentID]['pCount'] = 1;
								} else {
									$newArray[$attendance->studentID]['pCount'] += 1;
								}
							} else {
								if(!isset($newArray[$attendance->studentID]['aCount'])) {
									$newArray[$attendance->studentID]['aCount'] = 1;
								} else {
									$newArray[$attendance->studentID]['aCount'] += 1;
								}
							}
							if(!isset($newArray[$attendance->studentID]['tCount'])) {
								$newArray[$attendance->studentID]['tCount'] = 1;
							} else {
								$newArray[$attendance->studentID]['tCount'] += 1;
							}
						}
					}
					$pCount = isset($newArray[$attendance->studentID]['pCount']) ? $newArray[$attendance->studentID]['pCount'] : '0';
					$tCount = isset($newArray[$attendance->studentID]['tCount']) ? $newArray[$attendance->studentID]['tCount'] : '0';
					$attendanceArray[$attendance->studentID] = $pCount."/".$tCount;
				}
			}
		}
		return $attendanceArray;
    }
    
    public function convertDateToNepaliInEnglish($date)
    {
        $dateObj = new NepaliCalenderHelper();
		$nepaliDate= $dateObj->convertDateToNepaliInEnglish($date);
        return $nepaliDate['year'] . '-' . $nepaliDate['month'] . '-' . $nepaliDate['date'];
    }
    
    public function convertDateToEnglishInNepali($date)
    {
		$date = explode('-', $date);
		$yy = $date[0];
		$mm = $date[1];
		$dd = $date[2];
        $dateObj = new NepaliCalenderHelper();
		$engDate= $dateObj->nep_to_eng($yy, $mm, $dd);
        return $engDate['year'] . '-' . $engDate['month'] . '-' . $engDate['date'];
	}
    
}