<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends Api_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('subject_m');
        $this->load->model('exam_m');
        $this->load->model('grade_m');
        $this->load->model('mark_m');
        $this->load->model('markpercentage_m');
        $this->load->model('studentrelation_m');
        $this->load->model('marksetting_m');
        $this->load->model('studentgroup_m');
        $this->load->model('studentremark_m');
        $this->load->helper('nepali_calendar_helper');
        $this->load->library('form_validation');
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
    
    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'exam_id',
                'label' => 'Exam ID',
                'rules' => 'trim|required|xss_clean',
            ),
            array(
                'field' => 'schoolyear_id',
                'label' => 'School Year ID',
                'rules' => 'trim|required|xss_clean',
            ),
        );
        if($this->session->userdata('usertypeID') == 1 || $this->session->userdata('usertypeID') == 2) {
            $class_rule = array(
                'field' => 'class_id',
                'label' => 'Class ID',
                'rules' => 'trim|required|xss_clean',
            );
            array_push($rules, $class_rule);
        }
        return $rules;
    }

    public function index_post() 
    {
        $rules = $this->rules();
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            $this->response([
                'status' => false,
                'message' => $this->form_validation->error_array(),
                'data' => [],
            ], REST_Controller::HTTP_OK);
        } else {
            $examID     = $this->input->post('exam_id');
            $schoolyearID = $this->input->post('schoolyear_id');

            if($this->session->userdata('usertypeID') == 1 || $this->session->userdata('usertypeID') == 2 || $this->session->userdata('usertypeID') == 4) {
                $classesID  = $this->input->post('class_id');
                $sectionID  = $this->input->post('section_id');
                $studentID  = $this->input->post('student_id');
            } else if($this->session->userdata('usertypeID') == 3) {
                $studentID  = $this->session->userdata('loginuserID');
                $student_data = $this->studentrelation_m->general_get_single_student(['studentID' => $studentID, 'srschoolyearID' => $schoolyearID]);
                if(isset($student_data)) {
                    $classesID = $student_data->classesID;
                    $sectionID = $student_data->sectionID;
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'No data available',
                        'data' => [],
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No data available',
                    'data' => [],
                ], REST_Controller::HTTP_OK);
            }

            if((int)$examID && (int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
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
                // $exam      = $this->exam_m->get_single_exam(['examID'=> $examID]);
                // $this->data['class'] = $class;
                // $this->data['exam'] = $exam;
                // $this->data['exam']->date_in_nepali = $this->convertDateToNepaliInEnglish($exam->date);
                // $this->data['exam']->issue_date_in_english = $this->convertDateToEnglishInNepali($exam->issue_date);
                // $this->data['examName']     = $exam->exam;
                $grades   = $this->grade_m->get_grade();
                // $this->data['classes']      = pluck($this->classes_m->general_get_classes(),'classes','classesID');
                // $this->data['sections']     = pluck($this->section_m->general_get_section(),'section','sectionID');
                // $this->data['class_teacher']= pluck($this->section_m->get_join_sections(),'name','sectionID');
                // $this->data['groups']       = pluck($this->studentgroup_m->get_studentgroup(),'group','studentgroupID');
                $this->data['studentLists'] = $this->studentrelation_m->general_get_order_by_student_with_parent($studentQueryArray);

                // $this->data['remarks'] 		= pluck($this->studentremark_m->get_order_by_studentremark(['examID' => $examID, 'classID' => $classesID]), 'remarks', 'studentID');
                $students               = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
                $marks                  = $this->mark_m->student_all_mark_array($queryArray);
                $mandatorySubjects      = $this->subject_m->get_subject_except_coscholastic(array('classesID' => $classesID, 'type' => 1));
                $coscholasticSubjects      = $this->subject_m->get_subject_only_coscholastic(array('classesID' => $classesID, 'type' => 1));
                
                // $this->data['mandatorySubjects'] = $mandatorySubjects;
                // $this->data['coscholasticSubjects'] = $coscholasticSubjects;

                // $this->subject_m->order('type DESC');
                $this->data['subjects'] = $this->subject_m->get_by_class_id($classesID);

                $settingmarktypeID      = $this->data['siteinfos']->marktypeID;
                // $settingmarktypeID1      = $this->data['siteinfos']->marktypeID;
                $markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();

                // $markpercentagesmainArr1 = $this->marksetting_m->get_marksetting_markpercentages();
                $markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
                // $markpercentagesArr     = isset($markpercentagesmainArr[$classesID][$examID]) ? $markpercentagesmainArr[$classesID][$examID] : [];
                // $this->data['markpercentagesArr']  = $markpercentagesArr;
                // $this->data['settingmarktypeID']   = $settingmarktypeID;

                // $this->data['markpercentagesArr']  = $markpercentagesArr;
                // $this->data['settingmarktypeID1']   = $settingmarktypeID;


    			$percentageArr   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

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

                // $highestMarks    = [];
                // foreach ($marks as $value) {
                //     if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
                //         $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
                //     }
                //     $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
                // }

                // $this->data['highestmarks']      = $highestMarks;

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

                            $subject_count = 0;
                            $total_subject_mark = 0;
                            $total_grade_point = 0;
                            $total_credit_hours = 0;
                            $credit_hours_x_grade_point = 0;

                            $optionalSubject = $this->subject_m->get_single_subject(['subjectID' => $student->sroptionalsubjectID]);
                            $anotherOptionalSubject = $this->subject_m->get_single_subject(['subjectID' => $student->sranotheroptionalsubjectID]);

                            if(customCompute($mandatorySubjects)) {
                                foreach ($mandatorySubjects as $mandatorySubject) {
                                    $percentageMark  = 0;
                                    $uniquepercentageArr = isset($markpercentagesArr[$mandatorySubject->subjectID]) ? $markpercentagesArr[$mandatorySubject->subjectID] : [];

                                    $markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                                    $markpercentagesCount = customCompute($markpercentages);

                                    if(customCompute($markpercentages)) {
                                        foreach ($markpercentages as $markpercentageID) {
                                            $f = false;
                                            if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                                $f = true;
                                                $percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
                                            }

                                            if(isset($studentPosition[$student->srstudentID]['obtainedMark'][$mandatorySubject->subjectID])) {
                                                if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
                                                    $studentPosition[$student->srstudentID]['obtainedMark'][$mandatorySubject->subjectID] += $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition[$student->srstudentID]['obtainedMark'][$mandatorySubject->subjectID] += 0;
                                                }
                                            } else {
                                                if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
                                                    $studentPosition[$student->srstudentID]['obtainedMark'][$mandatorySubject->subjectID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition[$student->srstudentID]['obtainedMark'][$mandatorySubject->subjectID] = 0;
                                                }
                                            }

                                            if(isset($retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
                                                $studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];

                                                $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                                if($studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] != 0) {
                                                    $theory_part = ($percentageArr[$markpercentageID]->percentage/100) * $mandatorySubject->finalmark;
                                                    $percentage = floor(($studentPosition[$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID]/$theory_part)*100);
                                                    if(customCompute($grades)) {
                                                        foreach($grades as $grade) {
                                                            if(($grade->gradefrom <= $percentage) && ($grade->gradeupto >= $percentage)) {
                                                                $studentPosition[$student->srstudentID]['gradePointPercentage'][$mandatorySubject->subjectID][$markpercentageID] = $grade->point;
                                                                $studentPosition[$student->srstudentID]['gradePercentage'][$mandatorySubject->subjectID][$markpercentageID] = $grade->grade;
                                                            }
                                                        } 
                                                    } 
                                                }

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
                                                    if(isset($studentPosition[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID])) {
                                                        if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                            $studentPosition[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID] += $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                                        } else {
                                                            $studentPosition[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID] += 0;
                                                        }
                                                    } else {
                                                        if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                            $studentPosition[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                                        } else {
                                                            $studentPosition[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID] = 0;
                                                        }
                                                    }

                                                    if(isset($retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                        $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];

                                                        $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                                        if($studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] != 0) {
                                                            $theory_part = ($percentageArr[$markpercentageID]->percentage/100) * $mandatorySubject->finalmark;
                                                            $percentage = floor(($studentPosition[$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID]/$theory_part)*100);
                                                            if(customCompute($grades)) {
                                                                foreach($grades as $grade) {
                                                                    if(($grade->gradefrom <= $percentage) && ($grade->gradeupto >= $percentage)) {
                                                                        $studentPosition[$student->srstudentID]['gradePointPercentage'][$student->sroptionalsubjectID][$markpercentageID] = $grade->point;
                                                                        $studentPosition[$student->srstudentID]['gradePercentage'][$student->sroptionalsubjectID][$markpercentageID] = $grade->grade;
                                                                    }
                                                                } 
                                                            } 
                                                        }

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
                                                    if(isset($studentPosition[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID])) {
                                                        if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                            $studentPosition[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID] += $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                        } else {
                                                            $studentPosition[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID] += 0;
                                                        }
                                                    } else {
                                                        if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                            $studentPosition[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                        } else {
                                                            $studentPosition[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID] = 0;
                                                        }
                                                    }

                                                    if(isset($retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                        $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID] = $retMark[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];

                                                        $studentPosition[$student->srstudentID]['gradePercentage'][$student->sranotheroptionalsubjectID][$markpercentageID] = $studentPosition[$student->srstudentID]['markpercentageMark'][$student->sranotheroptionalsubjectID][$markpercentageID];

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

                                        $subjectMark = isset($studentPosition[$student->srstudentID]['obtainedMark'][$mandatorySubject->subjectID]) ? $studentPosition[$student->srstudentID]['obtainedMark'][$mandatorySubject->subjectID] : '0';                                        
                                        $subjectMark = markCalculationView($subjectMark, $mandatorySubject->finalmark, $percentageMark);

                                        $total_subject_mark += $subjectMark;
                                        $studentPosition[$student->srstudentID]['gradeMark'][$mandatorySubject->subjectID] = 'F';
                                        $studentPosition[$student->srstudentID]['gradePointMark'][$mandatorySubject->subjectID] = '0';
                                        
                                        if(customCompute($grades)) { 
                                            foreach($grades as $grade) {
                                                if(($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark)) { 
                                                    $studentPosition[$student->srstudentID]['gradeMark'][$mandatorySubject->subjectID] = $grade->grade;
                                                    $studentPosition[$student->srstudentID]['gradePointMark'][$mandatorySubject->subjectID] = $grade->point;

                                                    $total_grade_point += $grade->point;
                                                    if($mandatorySubject->credit != '') {
                                                        $credit_hours_x_grade_point += ($mandatorySubject->credit * $grade->point);
                                                    } else {
                                                        $credit_hours_x_grade_point += 0;
                                                    }

                                                    if(isset($grade_counts[$mandatorySubject->subjectID][$grade->grade])) {
                                                        $grade_counts[$mandatorySubject->subjectID][$grade->grade] += 1;
                                                    } else {
                                                        $grade_counts[$mandatorySubject->subjectID][$grade->grade] = 1;
                                                    }
                                                }
                                            } 
                                        } 

                                    }

                                    if(isset($studentPosition[$student->srstudentID]['obtainedMark'][$mandatorySubject->subjectID])) {
                                        $subject_count = $subject_count + 1;
                                        $total_credit_hours += (int) $mandatorySubject->credit; 
                                    }

									$studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['obtainedMark'][$mandatorySubject->subjectID];

                                    if(!isset($studentChecker['totalSubjectMark'][$student->srstudentID])) {
                                        if($student->sroptionalsubjectID != 0) {
                                            $studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID];
                                        }
                                        if($student->sranotheroptionalsubjectID != 0) {
                                            $studentPosition[$student->srstudentID]['totalSubjectMark'] += $studentPosition[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID];
                                        }
                                        $studentChecker['totalSubjectMark'][$student->srstudentID] = TRUE;
                                    }

                                    $studentSubjectPositionArray[$mandatorySubject->subjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['obtainedMark'][$mandatorySubject->subjectID];
                                    if(!isset($studentChecker['studentSubjectPositionArray'][$student->srstudentID])) {
                                        if($student->sroptionalsubjectID != 0) {
                                            $studentSubjectPositionArray[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID];
                                        }
                                        if($student->sranotheroptionalsubjectID != 0) {
                                            $studentSubjectPositionArray[$student->sranotheroptionalsubjectID][$student->srstudentID] = $studentPosition[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID];
                                        }
                                    }
                                }
                            }	

                            if($student->sroptionalsubjectID != 0) {
                                $optionalSubjectMark = isset($studentPosition[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID]) ? $studentPosition[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID] : '0';
                                            
                                $optionalSubjectMark = markCalculationView($optionalSubjectMark, $optionalSubject->finalmark, $percentageMark);

                                $studentPosition[$student->srstudentID]['gradeMark'][$student->sroptionalsubjectID] = 'F';
                                $studentPosition[$student->srstudentID]['gradePointMark'][$student->sroptionalsubjectID] = '0';

                                if(customCompute($grades)) { 
                                    foreach($grades as $grade) {
                                        if(($grade->gradefrom <= $optionalSubjectMark) && ($grade->gradeupto >= $optionalSubjectMark)) { 
                                            $studentPosition[$student->srstudentID]['gradeMark'][$student->sroptionalsubjectID] = $grade->grade;
                                            $studentPosition[$student->srstudentID]['gradePointMark'][$student->sroptionalsubjectID] = $grade->point;
                                            $total_grade_point += $grade->point;

                                            $subject_count = $subject_count + 1;

                                            $total_subject_mark += $optionalSubjectMark;
            
                                            if($optionalSubject->credit != '') {
                                                $credit_hours_x_grade_point += ($optionalSubject->credit * $grade->point);
                                            } else {
                                                $credit_hours_x_grade_point += 0;
                                            }
            
                                            if(isset($grade_counts[$optionalSubject->subjectID][$grade->grade])) {
                                                $grade_counts[$optionalSubject->subjectID][$grade->grade] += 1;
                                            } else {
                                                $grade_counts[$optionalSubject->subjectID][$grade->grade] = 1;
                                            }

                                        }
                                    } 
                                } 
                            }

                            if($student->sranotheroptionalsubjectID != 0) {
                                $anotherOptionalSubjectMark = isset($studentPosition[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID]) ? $studentPosition[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID] : '0';
                                            
                                $anotherOptionalSubjectMark = markCalculationView($anotherOptionalSubjectMark, $anotherOptionalSubject->finalmark, $percentageMark);

                                $studentPosition[$student->srstudentID]['gradeMark'][$student->sranotheroptionalsubjectID] = 'F';
                                $studentPosition[$student->srstudentID]['gradePointMark'][$student->sranotheroptionalsubjectID] = '0';

                                if(customCompute($grades)) { 
                                    foreach($grades as $grade) {
                                        if(($grade->gradefrom <= $anotherOptionalSubjectMark) && ($grade->gradeupto >= $anotherOptionalSubjectMark)) { 
                                            $studentPosition[$student->srstudentID]['gradeMark'][$student->sranotheroptionalsubjectID] = $grade->grade;
                                            $studentPosition[$student->srstudentID]['gradePointMark'][$student->sranotheroptionalsubjectID] = $grade->point;
                                            $total_grade_point += $grade->point;

                                            $subject_count = $subject_count + 1;

                                            $total_subject_mark += $anotherOptionalSubjectMark;
            
                                            if($anotherOptionalSubject->credit != '') {
                                                $credit_hours_x_grade_point += ($anotherOptionalSubject->credit * $grade->point);
                                            } else {
                                                $credit_hours_x_grade_point += 0;
                                            }
            
                                            if(isset($grade_counts[$anotherOptionalSubject->subjectID][$grade->grade])) {
                                                $grade_counts[$anotherOptionalSubject->subjectID][$grade->grade] += 1;
                                            } else {
                                                $grade_counts[$anotherOptionalSubject->subjectID][$grade->grade] = 1;
                                            }
                                        }
                                    } 
                                } 

                            }
                            $total_subject_mark = round($total_subject_mark / $subject_count);

                            if($class->classes_numeric != 11 && $class->classes_numeric != 12) {
                                if(customCompute($grades)) { 
                                    foreach($grades as $grade) {
                                        if(($grade->gradefrom <= $total_subject_mark) && ($grade->gradeupto >= $total_subject_mark)) { 
                                            $studentPosition[$student->srstudentID]['totalSubjectGrade'] = $grade->point;
                                            $studentPosition[$student->srstudentID]['totalSubjectGradePoint'] = $grade->grade;
                                        }
                                    } 
                                }
                            } else {
                                $point = number_format($credit_hours_x_grade_point / $total_credit_hours, 2);
                                $studentPosition[$student->srstudentID]['totalSubjectGrade'] = getGradeFromPoint($point);
                                $studentPosition[$student->srstudentID]['totalSubjectGradePoint'] = $point;
                            }

                            if(customCompute($coscholasticSubjects)) {
                                foreach ($coscholasticSubjects as $coscholasticSubject) {
                                    $uniquepercentageArr1 = isset($markpercentagesArr[$coscholasticSubject->subjectID]) ? $markpercentagesArr[$coscholasticSubject->subjectID] : [];

                                    $markpercentages1 = $uniquepercentageArr1[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                                    $markpercentagesCount1 = customCompute($markpercentages);
                                    if(customCompute($markpercentages1)) {
                                        foreach ($markpercentages1 as $markpercentageID) {
                                            $f = false;
                                            if(isset($uniquepercentageArr1['own']) && in_array($markpercentageID, $uniquepercentageArr1['own'])) {
                                                $f = true;
                                            }

                                            if(isset($studentPosition1[$student->srstudentID]['obtainedMark'][$coscholasticSubject->subjectID])) {
                                                if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
                                                    $studentPosition1[$student->srstudentID]['obtainedMark'][$coscholasticSubject->subjectID] += $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition1[$student->srstudentID]['obtainedMark'][$coscholasticSubject->subjectID] += 0;
                                                }
                                            } else {
                                                if(isset($retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID]) && $f) {
                                                    $studentPosition1[$student->srstudentID]['obtainedMark'][$coscholasticSubject->subjectID] = $retMark1[$student->srstudentID][$coscholasticSubject->subjectID][$markpercentageID];
                                                } else {
                                                    $studentPosition1[$student->srstudentID]['obtainedMark'][$coscholasticSubject->subjectID] = 0;
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
                                                    if(isset($studentPosition1[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID])) {
                                                        if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                            $studentPosition1[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                                        } else {
                                                            $studentPosition1[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID] += 0;
                                                        }
                                                    } else {
                                                        if(isset($retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                            $studentPosition1[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
                                                        } else {
                                                            $studentPosition1[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID] = 0;
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
                                                    if(isset($studentPosition1[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID])) {
                                                        if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                            $studentPosition1[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID] += $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                        } else {
                                                            $studentPosition1[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID] += 0;
                                                        }
                                                    } else {
                                                        if(isset($retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID])) {
                                                            $studentPosition1[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID] = $retMark1[$student->srstudentID][$student->sranotheroptionalsubjectID][$markpercentageID];
                                                        } else {
                                                            $studentPosition1[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID] = 0;
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

                                        $subjectMark = isset($studentPosition1[$student->srstudentID]['obtainedMark'][$coscholasticSubject->subjectID]) ? $studentPosition1[$student->srstudentID]['obtainedMark'][$coscholasticSubject->subjectID] : '0';                                        
                                        $subjectMark = markCalculationView($subjectMark, $coscholasticSubject->finalmark, $percentageMark);


                                        if(customCompute($grades)) { 
                                            foreach($grades as $grade) {
                                                if(($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark)) { 
                                                    $studentPosition1[$student->srstudentID]['gradeMark'][$coscholasticSubject->subjectID] = $grade->grade;
                                                }
                                            } 
                                        } 

                                        if($student->sroptionalsubjectID != 0) {
                                            $optionalSubjectMark = isset($studentPosition[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID]) ? $studentPosition[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID] : '0';
                                            $optionalSubjectMark = markCalculationView($optionalSubjectMark, $coscholasticSubject->finalmark, $percentageMark);

                                            if(customCompute($grades)) { 
                                                foreach($grades as $grade) {
                                                    if(($grade->gradefrom <= $optionalSubjectMark) && ($grade->gradeupto >= $optionalSubjectMark)) { 
                                                        $studentPosition1[$student->srstudentID]['gradeMark'][$student->sroptionalsubjectID] = $grade->grade;
                                                    }
                                                } 
                                            } 
                                        }
                                        if($student->sranotheroptionalsubjectID != 0) {
                                            $anotherOptionalSubjectMark = isset($studentPosition[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID]) ? $studentPosition[$student->srstudentID]['obtainedMark'][$student->sranotheroptionalsubjectID] : '0';
                                            $anotherOptionalSubjectMark = markCalculationView($anotherOptionalSubjectMark, $coscholasticSubject->finalmark, $percentageMark);

                                            if(customCompute($grades)) { 
                                                foreach($grades as $grade) {
                                                    if(($grade->gradefrom <= $anotherOptionalSubjectMark) && ($grade->gradeupto >= $anotherOptionalSubjectMark)) { 
                                                        $studentPosition1[$student->srstudentID]['gradeMark'][$student->sranotheroptionalsubjectID] = $grade->grade;
                                                    }
                                                } 
                                            } 
                                        }
                                    }

                                    $studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['obtainedMark'][$coscholasticSubject->subjectID];

                                    if(!isset($studentChecker1['totalSubjectMark'][$student->srstudentID])) {
                                        // if($student->sroptionalsubjectID != 0) {
                                        // 	$studentPosition1[$student->srstudentID]['totalSubjectMark'] += $studentPosition1[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID];
                                        // }
                                        $studentChecker1['totalSubjectMark'][$student->srstudentID] = TRUE;
                                    }

                                    $studentSubjectPositionArray1[$coscholasticSubject->subjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['obtainedMark'][$coscholasticSubject->subjectID];
                                    if(!isset($studentChecker1['studentSubjectPositionArray'][$student->srstudentID])) {
                                        // if($student->sroptionalsubjectID != 0) {
                                        // 	$studentSubjectPositionArray1[$student->sroptionalsubjectID][$student->srstudentID] = $studentPosition1[$student->srstudentID]['obtainedMark'][$student->sroptionalsubjectID];
                                        // }
                                    }
                                }
                            }


                            // $studentPosition[$student->srstudentID]['classPositionMark'] = ($studentPosition[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition[$student->srstudentID]['obtainedMark']));
                            // $studentClassPositionArray[$student->srstudentID]             = $studentPosition[$student->srstudentID]['classPositionMark'];

                            // if(isset($studentPosition['totalStudentMarkAverage'])) {
                            //     $studentPosition['totalStudentMarkAverage'] += $studentPosition[$student->srstudentID]['classPositionMark'];
                            // } else {
                            //     $studentPosition['totalStudentMarkAverage']  = $studentPosition[$student->srstudentID]['classPositionMark'];
                            // }

                            // if(count($coscholasticSubjects) > 0) {
                            //     $studentPosition1[$student->srstudentID]['classPositionMark'] = ($studentPosition1[$student->srstudentID]['totalSubjectMark'] / customCompute($studentPosition1[$student->srstudentID]['obtainedMark']));
                            //     $studentClassPositionArray1[$student->srstudentID]             = $studentPosition1[$student->srstudentID]['classPositionMark'];	
                            
                            //     if(isset($studentPosition1['totalStudentMarkAverage'])) {
                            //         $studentPosition1['totalStudentMarkAverage'] += $studentPosition1[$student->srstudentID]['classPositionMark'];
                            //     } else {
                            //         $studentPosition1['totalStudentMarkAverage']  = $studentPosition1[$student->srstudentID]['classPositionMark'];
                            //     }
                            // }
                            
                            
                        }
                    }

                    // arsort($studentClassPositionArray);
                    // $studentPosition['studentClassPositionArray'] = $studentClassPositionArray;
                    // if(customCompute($studentSubjectPositionArray)) {
                    //     foreach($studentSubjectPositionArray as $subjectID => $studentSubjectPositionMark) {
                    //         arsort($studentSubjectPositionMark);
                    //         $studentPosition['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark;
                    //     }
                    // }
                    if((int)$studentID > 0) {
                        $queryArray['studentID'] = $studentID;
                    }

                    // arsort($studentClassPositionArray1);
                    // $studentPosition1['studentClassPositionArray'] = $studentClassPositionArray1;
                    // if(customCompute($studentSubjectPositionArray1)) {
                    //     foreach($studentSubjectPositionArray1 as $subjectID => $studentSubjectPositionMark1) {
                    //         arsort($studentSubjectPositionMark1);
                    //         $studentPosition1['studentSubjectPositionMark'][$subjectID] = $studentSubjectPositionMark1;
                    //     }
                    // }
                    if((int)$studentID > 0) {
                        $queryArray['studentID'] = $studentID;
                    }

                    // $this->data['col']             = 5 + $markpercentagesCount;
                    // $this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
                    
                    // $this->data['toShow'] = ["obtainedMark","gradePointMark","gradeMark"];
                    $this->data['toShow'] = ["gradePointMark","gradeMark"];



                    $this->data['mandatory'] = $studentPosition;
                    $this->data['coscholastics'] = $studentPosition1;
                    $this->data['percentageArr']   = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

                $this->response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $this->data
                ], REST_Controller::HTTP_OK);
            }
        }
    }

	public function pdf_get() {
        $examID = htmlentities(escapeString($this->uri->segment(5)));
        $classesID  = htmlentities(escapeString($this->uri->segment(6)));
        $sectionID  = htmlentities(escapeString($this->uri->segment(7)));
        $studentID  = htmlentities(escapeString($this->uri->segment(8)));
        $date  = htmlentities(escapeString($this->uri->segment(9)));
        $verified_by  = htmlentities(escapeString($this->uri->segment(10)));
        $school_days  = htmlentities(escapeString($this->uri->segment(11)));
        $schoolyearID = htmlentities(escapeString($this->uri->segment(12)));


        if((int)$examID && (int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
            $this->data['examID']     = $examID;
            $this->data['classesID']  = $classesID;
            $this->data['sectionID']  = $sectionID;
            $this->data['studentIDD'] = $studentID;
            $this->data['date'] = urldecode($date);
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

            // if(customCompute($this->data['studentLists'])) {
            // 	foreach($this->data['studentLists'] as $student) {
            // 		$student->dob_in_bs = $this->convertDateToNepaliInEnglish($student->dob);
            // 	}
            // }
                
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
                // $this->data['attendance']      = $this->get_student_attendance($queryArray, $this->data['subjects'], $this->data['studentLists']);
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
                    // $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF', 'view', 'a4', 'landscape');
                // }

            if($class->classes_numeric >= 1 && $class->classes_numeric <= 7){
                $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF_1_to_7', 'view', 'a4', 'landscape');
            }elseif(strpos($class->classes, 'Nursery') !== false  || strpos($class->classes, 'KG') !== false){
                $this->reportPDF('terminalreport2.css', $this->data, 'report/terminal2/TerminalReportPDF', 'view', 'a4', 'landscape');
            }else{
                if($examID == 4 || $examID == 5){
                    $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF_finalterm', 'view', 'a4', 'landscape', 'custom');
                }else{
                    $this->reportPDF('terminalreport1.css', $this->data, 'report/terminal1/TerminalReportPDF', 'view', 'a4', 'landscape', 'custom');
                }
            }
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Not data',
                    'data' => '',
                ], REST_Controller::HTTP_OK);
            }
    }
}