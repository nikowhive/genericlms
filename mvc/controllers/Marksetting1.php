<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marksetting1 extends Admin_Controller {
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
		$this->load->model("classes_m");
		$this->load->model("subject_m");
		$this->load->model("setting_m");
		$this->load->model("marksetting_m");
		$this->load->model("markpercentage_m");
		$this->load->model("marksettingrelation_m");


		$language = $this->session->userdata('lang');
		$this->lang->load('marksetting', $language);	
	}

	protected function rules() {
		$marktypeID = $this->input->post('marktypeID');
		$rules = array(
			array(
				'field' => 'marktypeID', 
				'label' => $this->lang->line("marksetting_mark_type"), 
				'rules' => 'trim|required|xss_clean|callback_required_marktype'
			),
			array(
				'field' => 'markpercentages[]', 
				'label' => $this->lang->line("marksetting_mark_percentage"), 
				'rules' => 'trim|required|xss_clean|callback_required_markpercentages|callback_check_markpercentage'
			)
		);
		if(($marktypeID == 0) || ($marktypeID == 1) || ($marktypeID == 2)) {
			$rules[] = array(
				'field' => 'exams[]', 
				'label' => $this->lang->line("marksetting_exam"), 
				'rules' => 'trim|required|xss_clean|callback_required_exams'
			);
		}
		return $rules;
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

		$siteInfo          = $this->site_m->get_site();
		$mtype = $siteInfo->marktypeID;
		
		$ex_class                      = $this->data['siteinfos']->ex_class;
		$this->data['classes']         = $this->classes_m->general_get_order_by_classes(['classesID !='=> $ex_class]);
		$this->data['exams']           = $this->exam_m->get_exam();
		$this->data['subjects']        = pluck_multi_array($this->subject_m->general_get_subject(), 'obj', 'classesID');
		$this->data['markpercentages'] = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
        
		if($mtype == 1){
			$this->data['currentSavedClass'] = $this->marksetting_m->get_latest_marksetting(['marktypeID' => 1]);
		}else{
			$this->data['currentSavedClass'] = '';
		}
		if($mtype == 2){
			$this->data['currentSavedExam'] = $this->marksetting_m->get_latest_marksetting(['marktypeID' => 2]);
		}else{
			$this->data['currentSavedExam'] = '';
		}

		if($mtype == 4){
			$this->data['currentSavedClassSubjectWise'] = $this->marksetting_m->get_latest_marksetting(['marktypeID' => 4]);
         }else{
			$this->data['currentSavedClassSubjectWise'] = '';
		}

		if($mtype == 5){
			$this->data['currentSavedClassExamWise'] = $this->marksetting_m->get_latest_marksetting(['marktypeID' => 5]);
         }else{
			$this->data['currentSavedClassExamWise'] = '';
		}

		if($mtype == 6){
			$this->data['currentSavedClassExamSubjectWise'] = $this->marksetting_m->get_latest_marksetting(['marktypeID' => 6]);
         }else{
			$this->data['currentSavedClassExamSubjectWise'] = '';
		}
		
		$marksetting                     = $this->marksetting_m->get_marksetting_with_marksettingrelation();
		$examArr                       = [];
		$markpercentageArr             = [];
		$classpercentageArr            = [];
		$exampercentageArr             = [];
		$subjectpercentageArr          = [];
		$classexampercentageArr        = [];
		$classexamsubjectpercentageArr = [];
		if(customCompute($marksetting)) {
			foreach ($marksetting as $marksett) {
				$examArr[$marksett->marktypeID][]            = $marksett->examID;
				$markpercentageArr[$marksett->marktypeID][]  = $marksett->markpercentageID;
				$classpercentageArr[$marksett->marktypeID][$marksett->classesID][]  = $marksett->markpercentageID;
				$exampercentageArr[$marksett->marktypeID][$marksett->examID][]      = $marksett->markpercentageID;
				$subjectpercentageArr[$marksett->marktypeID][$marksett->classesID][$marksett->subjectID][]  = $marksett->markpercentageID;
				$classexampercentageArr[$marksett->marktypeID][$marksett->classesID][$marksett->examID][]   = $marksett->markpercentageID;
				$classexamsubjectpercentageArr[$marksett->marktypeID][$marksett->classesID][$marksett->examID][$marksett->subjectID][]  = $marksett->markpercentageID;
			}
		}
		$this->data['examArr']                       = $examArr;
		$this->data['markpercentageArr']             = $markpercentageArr;
		$this->data['classpercentageArr']            = $classpercentageArr;
		$this->data['exampercentageArr']             = $exampercentageArr;
		$this->data['subjectpercentageArr']          = $subjectpercentageArr;
		$this->data['classexampercentageArr']        = $classexampercentageArr;
		$this->data['classexamsubjectpercentageArr'] = $classexamsubjectpercentageArr;

		$this->data["subview"]          = "marksettingindividual/index";
		$this->load->view('_layout_main', $this->data);
		
	}

	public function saveMarkSetting(){
		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$errors  = $this->form_validation->error_array();
				$message = '';
				if(customCompute($errors)) {
					foreach ($errors as $error) {
						$message .= $error.'<br/>';
					}
				}
				echo json_encode(['status'=>false,'message' => $message]);
			} else {
				$marktypeID = $this->input->post('marktypeID');
				$this->setting_m->insertorupdate(['marktypeID'=> $marktypeID]);

				$marksettingArr         = [];
				$marksettingRelationArr = [];
				if($marktypeID == 0) {
					$exams = $this->input->post('exams');
					if(customCompute($exams)) {
						$i = 0;
						foreach ($exams as $exam) {
							$examArr = explode('_', $exam);
							$examID  = isset($examArr[1]) ? $examArr[1] : 0;
							$marksettingArr[$i]['examID']     = $examID;
							$marksettingArr[$i]['classesID']  = 0;
							$marksettingArr[$i]['subjectID']  = 0;
							$marksettingArr[$i]['marktypeID'] = 0;
							$i++;
						}
					}

					$this->marksetting_m->delete_marksetting_by_array(['marktypeID'=> 0]);
					$marksettingCount  = ((customCompute($marksettingArr) > 0) ? customCompute($marksettingArr) : 0);
					$marksettingID     = 0;
					if($marksettingCount > 0) {
						$marksettingID = $this->marksetting_m->insert_batch_marksetting($marksettingArr);
					}

					$markpercentages   = $this->input->post('markpercentages');
					$i = 0; $j = 0;
					while ($j < $marksettingCount) {
						if(customCompute($markpercentages)) {
							foreach ($markpercentages as $markpercentage) {
								$markpercentage    = explode('_', $markpercentage);
								$markpercentageID  = isset($markpercentage[1]) ? $markpercentage[1] : 0;
								
								$marksettingRelationArr[$i]['marktypeID']       = 0;
								$marksettingRelationArr[$i]['marksettingID']    = $marksettingID;
								$marksettingRelationArr[$i]['markpercentageID'] = $markpercentageID;
								$i++;
							}
						}
						$j++; $marksettingID++;
					}

					$this->marksettingrelation_m->delete_marksettingrelation_by_array(['marktypeID'=> 0]);
					if(customCompute($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				} elseif($marktypeID == 1) {
					$markpercentageArr = [];
					$markpercentages   = $this->input->post('markpercentages');
		
					if(customCompute($markpercentages)) {
						foreach ($markpercentages as $markpercentage) {
							$markpercentage    = explode('_', $markpercentage);
							$classesID         = isset($markpercentage[1]) ? $markpercentage[1] : 0;
							$markpercentageID  = isset($markpercentage[2]) ? $markpercentage[2] : 0;

							$markpercentageArr[$classesID][$markpercentageID] = $markpercentageID; 
						}
					}

					// delete if exist
                      $this->deletePreviousMarkpercentages(['marktypeID'=> 1,'classesID' => $classesID]);
					 // end 

					$exams = $this->input->post('exams');
					if(customCompute($exams)) {
						$j=0;
						foreach ($exams as $exam) {
							$examArr = explode('_', $exam);
							$examID  = isset($examArr[1]) ? $examArr[1] : 0;
							if(customCompute($markpercentageArr)) {
								foreach ($markpercentageArr as $classesID => $markpercentages) {
									$marksettingArr['examID']     = $examID;
									$marksettingArr['classesID']  = $classesID;
									$marksettingArr['subjectID']  = 0;
									$marksettingArr['marktypeID'] = 1;

									$marksettingID = $this->marksetting_m->insert_marksetting($marksettingArr);
									if(customCompute($markpercentages)) {
										foreach ($markpercentages as $markpercentage) {
											$marksettingRelationArr[$j]['marktypeID']       = 1;
											$marksettingRelationArr[$j]['marksettingID']    = $marksettingID;
											$marksettingRelationArr[$j]['markpercentageID'] = $markpercentage;
											$j++;
										}
									}
								}
							}
						}
					}

					if(customCompute($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				} elseif($marktypeID == 2) {
					$markpercentageArr = [];
					$markpercentages   = $this->input->post('markpercentages');
					if(customCompute($markpercentages)) {
						foreach ($markpercentages as $markpercentage) {
							$markpercentage    = explode('_', $markpercentage);
							$examID            = isset($markpercentage[1]) ? $markpercentage[1] : 0;
							$markpercentageID  = isset($markpercentage[2]) ? $markpercentage[2] : 0;

							$markpercentageArr[$examID][$markpercentageID] = $markpercentageID; 
						}
					}

					// delete if exist
					$this->deletePreviousMarkpercentages(['marktypeID'=> 2,'examID' => $examID]);
					// end

					$j = 0;
					if(customCompute($markpercentageArr)) {
						foreach ($markpercentageArr as $examID => $markpercentages) {
							$marksettingArr['examID']     = $examID;
							$marksettingArr['classesID']  = 0;
							$marksettingArr['subjectID']  = 0;
							$marksettingArr['marktypeID'] = 2;

							$marksettingID = $this->marksetting_m->insert_marksetting($marksettingArr);
							if(customCompute($markpercentages)) {
								foreach ($markpercentages as $markpercentage) {
									$marksettingRelationArr[$j]['marktypeID']       = 2;
									$marksettingRelationArr[$j]['marksettingID']    = $marksettingID;
									$marksettingRelationArr[$j]['markpercentageID'] = $markpercentage;
									$j++;
								}
							}
						}
					}

					//$this->marksettingrelation_m->delete_marksettingrelation_by_array(['marktypeID'=> 2]);
					if(customCompute($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				} elseif($marktypeID == 4) {
					$markpercentageArr = [];
					$markpercentages   = $this->input->post('markpercentages');
					if(customCompute($markpercentages)) {
						foreach ($markpercentages as $markpercentage) {
							$markpercentage    = explode('_', $markpercentage);
							$classesID         = isset($markpercentage[1]) ? $markpercentage[1] : 0;
							$subjectID         = isset($markpercentage[2]) ? $markpercentage[2] : 0;
							$markpercentageID  = isset($markpercentage[3]) ? $markpercentage[3] : 0;
							$markpercentageArr[$classesID][$subjectID][$markpercentageID] = $markpercentageID; 
						}
					}

					//delete if exist
					$this->deletePreviousMarkpercentages(['marktypeID' => 4,'classesID' => $classesID,'subjectID' => $subjectID ]);
                    // end

					$i = 0;
					if(customCompute($markpercentageArr)) {
						foreach ($markpercentageArr as $classesID => $subjectmarkpercentageArr) {
							if(customCompute($subjectmarkpercentageArr)) {
								foreach ($subjectmarkpercentageArr as $subjectID=> $markpercentages) {
									$marksettingArr['examID']     = 0;
									$marksettingArr['classesID']  = $classesID;
									$marksettingArr['subjectID']  = $subjectID;
									$marksettingArr['marktypeID'] = 4;
									$marksettingID = $this->marksetting_m->insert_marksetting($marksettingArr);

									if(customCompute($markpercentages)) {
										foreach ($markpercentages as $markpercentage) {
											$marksettingRelationArr[$i]['marktypeID']       = 4;
											$marksettingRelationArr[$i]['marksettingID']    = $marksettingID;
											$marksettingRelationArr[$i]['markpercentageID'] = $markpercentage;
											$i++;
										}
									}

								}
							}
						}
					}
					//$this->marksettingrelation_m->delete_marksettingrelation_by_array(['marktypeID'=> 4]);
					if(customCompute($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				} elseif($marktypeID == 5) {
					$markpercentageArr = [];
					$markpercentages   = $this->input->post('markpercentages');
					if(customCompute($markpercentages)) {
						foreach ($markpercentages as $markpercentage) {
							$markpercentage    = explode('_', $markpercentage);
							$classesID         = isset($markpercentage[1]) ? $markpercentage[1] : 0;
							$examID            = isset($markpercentage[2]) ? $markpercentage[2] : 0;
							$markpercentageID  = isset($markpercentage[3]) ? $markpercentage[3] : 0;

							$markpercentageArr[$classesID][$examID][$markpercentageID] = $markpercentageID;
						}
					}

					// delete if exist
					$this->deletePreviousMarkpercentages(['marktypeID'=> 5,'classesID' => $classesID,'examID' => $examID ]);
					// end

					$j=0;
					if(customCompute($markpercentageArr)) {
						foreach ($markpercentageArr as $classesID => $exammarkpercentageArr) {
							if(customCompute($exammarkpercentageArr)) {
								foreach($exammarkpercentageArr as $examID=> $markpercentages) {
									$marksettingArr['examID']     = $examID;
									$marksettingArr['classesID']  = $classesID;
									$marksettingArr['subjectID']  = 0;
									$marksettingArr['marktypeID'] = 5;

									$marksettingID = $this->marksetting_m->insert_marksetting($marksettingArr);
									if(customCompute($markpercentages)) {
										foreach ($markpercentages as $markpercentage) {
											$marksettingRelationArr[$j]['marktypeID']       = 5;
											$marksettingRelationArr[$j]['marksettingID']    = $marksettingID;
											$marksettingRelationArr[$j]['markpercentageID'] = $markpercentage;
											$j++;
										}
									}
								}
							}

						}
					}

				 if(customCompute($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				} elseif($marktypeID == 6) {
					$markpercentageArr = [];
					$markpercentages   = $this->input->post('markpercentages');
					if(customCompute($markpercentages)) {
						foreach ($markpercentages as $markpercentage) {
							$markpercentage    = explode('_', $markpercentage);
							$classesID         = isset($markpercentage[1]) ? $markpercentage[1] : 0;
							$examID            = isset($markpercentage[2]) ? $markpercentage[2] : 0;
							$subjectID         = isset($markpercentage[3]) ? $markpercentage[3] : 0;
							$markpercentageID  = isset($markpercentage[4]) ? $markpercentage[4] : 0;

							$markpercentageArr[$classesID][$examID][$subjectID][$markpercentageID] = $markpercentageID;
						}
					}

					// delete if exist
                        $this->deletePreviousMarkpercentages(['marktypeID' => 6,'classesID' => $classesID,'subjectID' => $subjectID,'examID' => $examID]);
					// end

					$j=0;
					if(customCompute($markpercentageArr)) {
						foreach ($markpercentageArr as $classesID => $examsubjectmarkpercentageArr) {
							if(customCompute($examsubjectmarkpercentageArr)) {
								foreach($examsubjectmarkpercentageArr as $examID=> $subjectmarkpercentages) {
									if(customCompute($subjectmarkpercentages)) {
										foreach ($subjectmarkpercentages as $subjectID => $markpercentages) {
											$marksettingArr['examID']     = $examID;
											$marksettingArr['classesID']  = $classesID;
											$marksettingArr['subjectID']  = $subjectID;
											$marksettingArr['marktypeID'] = 6;
											
											$marksettingID = $this->marksetting_m->insert_marksetting($marksettingArr);
											if(customCompute($markpercentages)) {
												foreach ($markpercentages as $markpercentage) {
													$marksettingRelationArr[$j]['marktypeID']       = 6;
													$marksettingRelationArr[$j]['marksettingID']    = $marksettingID;
													$marksettingRelationArr[$j]['markpercentageID'] = $markpercentage;
													$j++;
												}
											}
										}
									}

								}
							}
						}
					}

					if(customCompute($marksettingRelationArr)) {
						$this->marksettingrelation_m->insert_batch_marksettingrelation($marksettingRelationArr);
					}
				}
			
				echo json_encode(['status'=>true,'message' => 'Success']);
			}
		} 
	}

	public function deletePreviousMarkpercentages($array = []){

		$prevMarkSettings = $this->marksetting_m->get_order_by_marksetting($array);
					if(customCompute($prevMarkSettings)){
						foreach($prevMarkSettings as $prevMarkSetting){
							$prevMarkSettingRelations = $this->marksettingrelation_m->get_order_by_marksettingrelation(['marksettingID' => $prevMarkSetting->marksettingID]);
						    if(customCompute($prevMarkSettingRelations)){
								foreach($prevMarkSettingRelations as $prevMarkSettingRelation){
								   $this->marksettingrelation_m->delete_marksettingrelation($prevMarkSettingRelation->marksettingrelationID);
								}
							}
						}
					}
		$this->marksetting_m->delete_marksetting_by_array($array);
	}

	public function required_markpercentages() {
		if($_POST) {
			$markpercentages = $this->input->post('markpercentages');
			if(customCompute($markpercentages)) {
				return TRUE;
			} else {
				$this->form_validation->set_message("required_markpercentages", "The %s field is required.");
				return FALSE;
			}
		} else {
			$this->form_validation->set_message("required_markpercentages", "The %s field is required.");
			return FALSE;
		}
	} 

	public function required_exams() {
		if($_POST) {
			$exams = $this->input->post('exams');
			if(customCompute($exams)) {
				return TRUE;
			} else {
				$this->form_validation->set_message("required_exams", "The %s field is required.");
				return FALSE;
			}
		} else {
			$this->form_validation->set_message("required_exams", "The %s field is required.");
			return FALSE;
		}
	} 

	public function required_marktype($marktypeID) {
		if($marktypeID == '') {
			$this->form_validation->set_message('required_marktype', 'The %s field is required.');
			return FALSE;
		}
		return TRUE;
	}

	public function check_markpercentage() {
		$marktypeID        = $this->input->post('marktypeID');
		$markpercentages   = $this->input->post('markpercentages');
		$markpercentageArr = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
		if($marktypeID == 0) {
			// Global
			$totalmark     = 0;
			if(customCompute($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$markpercentage    = explode('_', $markpercentage);
					$markpercentageID  = isset($markpercentage[1]) ? $markpercentage[1] : 0;
					$totalmark += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
				}
			}
			if($totalmark != 100) {
				$this->form_validation->set_message('check_markpercentage', 'Select mark percentage of 100 percent.');
				return FALSE;
			}
			return TRUE;
		} elseif($marktypeID == 1) {
			
			$markArr = [];
			if(customCompute($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$exampercentageArr = explode('_', $markpercentage);
					$classesID         = isset($exampercentageArr[1]) ? $exampercentageArr[1] : 0;
					$markpercentageID  = isset($exampercentageArr[2]) ? $exampercentageArr[2] : 0;

					if(!isset($markArr[$classesID])) {
						$markArr[$classesID]  = 0;
					}
					$markArr[$classesID] += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
				}
			}

			$message    = "";
			$classobj = $this->classes_m->general_get_single_classes(['classesID ' => $classesID]);
			if(customCompute($classobj)) {
					$totalmark = isset($markArr[$classobj->classesID]) ? $markArr[$classobj->classesID] : 0;
					if($totalmark != 100) {
						$message .= "Select mark percentage in 100 percent of class $classobj->classes .<br/>";
					}
			}
			if(strlen($message) > 0) {
				$this->form_validation->set_message('check_markpercentage', $message);
				return FALSE;
			}
			return TRUE;
		} elseif($marktypeID == 2) {
			//Exam Wise
			$exams      = pluck($this->exam_m->get_exam(), 'exam', 'examID');
			$inputexams = $this->input->post('exams');

			$totalmark = 0;
			if(customCompute($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$exampercentageArr = explode('_', $markpercentage);
					$markpercentageID  = isset($exampercentageArr[2]) ? $exampercentageArr[2] : 0;
					$totalmark   += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
				}
			}

			$message    = "";
			if($totalmark != 100) {
				$message .= "Select mark percentage in 100 percent of all exam .<br/>";
			}

			if(strlen($message) > 0) {
				$this->form_validation->set_message('check_markpercentage', $message);
				return FALSE;
			}
			return TRUE;
		} elseif($marktypeID == 3) {
			//Exam Wise Individual
			$exams      = pluck($this->exam_m->get_exam(), 'exam', 'examID');
			$inputexams = $this->input->post('exams');

			$markArr = [];
			if(customCompute($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$exampercentageArr = explode('_', $markpercentage);
					$examID            = isset($exampercentageArr[1]) ? $exampercentageArr[1] : 0;
					$markpercentageID  = isset($exampercentageArr[2]) ? $exampercentageArr[2] : 0;

					if(!isset($markArr[$examID])) {
						$markArr[$examID]  = 0;
					}
					$markArr[$examID] += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
				}
			}

			$message    = "";
			if(customCompute($inputexams)) {
				foreach($inputexams as $exam) {
					$examArr   = explode('_', $exam);
					$examID    = isset($examArr[1]) ? $examArr[1] : 0;

					$totalmark = isset($markArr[$examID]) ? $markArr[$examID] : 0;
					if($totalmark != 100) {
						$exam = isset($exams[$examID]) ? $exams[$examID] : '';
						$message .= "Select mark percentage in 100 percent of exam $exam .<br/>";
					}
				}
			}
			if(strlen($message) > 0) {
				$this->form_validation->set_message('check_markpercentage', $message);
				return FALSE;
			}
			return TRUE;
		} elseif($marktypeID == 4) {

			// Subject Wise
			$totalmark = 0;
			if(customCompute($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$exampercentageArr = explode('_', $markpercentage);
					$classesID         = isset($exampercentageArr[1]) ? $exampercentageArr[1] : 0;
					$subjectID         = isset($exampercentageArr[2]) ? $exampercentageArr[2] : 0;
					$markpercentageID  = isset($exampercentageArr[3]) ? $exampercentageArr[3] : 0;
					$totalmark+= (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
				}
			}

			$message    = "";
			if($totalmark != 100) {
				$message .= "Select mark percentage in 100 percent.<br/>";
			}
						
			if(strlen($message) > 0) {
				$this->form_validation->set_message('check_markpercentage', $message);
				return FALSE;
			}
			return TRUE;
		} elseif($marktypeID == 5) {

			$totalmark = 0;
			if(customCompute($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$exampercentageArr = explode('_', $markpercentage);
					$classesID         = isset($exampercentageArr[1]) ? $exampercentageArr[1] : 0;
					$examID            = isset($exampercentageArr[2]) ? $exampercentageArr[2] : 0;
					$markpercentageID  = isset($exampercentageArr[3]) ? $exampercentageArr[3] : 0;
					
					$totalmark += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
					
				}
			}

			$message    = "";
			if($totalmark != 100) {
				$message .= "Select mark percentage in 100 percent of class exam.";
			}
						
			if(strlen($message) > 0) {
				$this->form_validation->set_message('check_markpercentage', $message);
				return FALSE;
			}
			return TRUE;
		} elseif($marktypeID == 6) {
			// Class Exam Wise
			$totalmark = 0;
			if(customCompute($markpercentages)) {
				foreach ($markpercentages as $markpercentage) {
					$exampercentageArr = explode('_', $markpercentage);
					$classesID         = isset($exampercentageArr[1]) ? $exampercentageArr[1] : 0;
					$examID            = isset($exampercentageArr[2]) ? $exampercentageArr[2] : 0;
					$subjectID         = isset($exampercentageArr[3]) ? $exampercentageArr[3] : 0;
					$markpercentageID  = isset($exampercentageArr[4]) ? $exampercentageArr[4] : 0;

					$totalmark += (isset($markpercentageArr[$markpercentageID]) ? $markpercentageArr[$markpercentageID]->percentage : 0);
					
				}
			}

			$message    = "";
			if($totalmark != 100) {
				$message .= "Select mark percentage in 100 percent.<br/>";
			}                       
								
			if(strlen($message) > 0) {
				$this->form_validation->set_message('check_markpercentage', $message);
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	public function getMarkPercentageByClasswise(){
		$classesID = $this->input->post('classesID');
		$marktypeID = $this->input->post('marktypeID');
		$markpercentages = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
		$condition = [
			'marksetting.classesID' => $classesID,
			'marksetting.marktypeID' => $marktypeID,
		];
		$marksetting                     = $this->marksetting_m->get_marksetting_with_marksettingrelation1($condition);
		$classpercentageArr            = [];
		if(customCompute($marksetting)) {
			foreach ($marksetting as $marksett) {
				$classpercentageArr[]  = $marksett->markpercentageID;
			}
		}
		$this->data['markpercentages'] = $markpercentages;
		$this->data['classpercentageArr'] = $classpercentageArr;
		$this->data['classesID'] = $classesID;
		$template = $this->load->view('marksettingindividual/markpercentage',$this->data,TRUE);
		echo $template;
	}

	public function getMarkPercentageByExamwise(){
		$examID = $this->input->post('examID');
		$marktypeID = $this->input->post('marktypeID');
		$markpercentages = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
		$condition = [
			'marksetting.examID' => $examID,
			'marksetting.marktypeID' => $marktypeID,
		];
		$marksetting                     = $this->marksetting_m->get_marksetting_with_marksettingrelation1($condition);
		$exampercentageArr            = [];
		if(customCompute($marksetting)) {
			foreach ($marksetting as $marksett) {
				$exampercentageArr[]  = $marksett->markpercentageID;
			}
		}
		$this->data['markpercentages'] = $markpercentages;
		$this->data['exampercentageArr'] = $exampercentageArr;
		$this->data['examID'] = $examID;
		$template = $this->load->view('marksettingindividual/examwisemarkpercentage',$this->data,TRUE);
		echo $template;
	}

	public function getMarkPercentageByClassExamwise(){

		$classesID = $this->input->post('classesID');
		$examID = $this->input->post('examID');
		$marktypeID = $this->input->post('marktypeID');
		$markpercentages = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
		$condition = [
			'marksetting.classesID' => $classesID,
			'marksetting.marktypeID' => $marktypeID,
			'marksetting.examID' => $examID
		];
		$marksetting                     = $this->marksetting_m->get_marksetting_with_marksettingrelation1($condition);
		$classexampercentageArr            = [];
		if(customCompute($marksetting)) {
			foreach ($marksetting as $marksett) {
				$classexampercentageArr[]   = $marksett->markpercentageID;
			}
		}
		$this->data['markpercentages'] = $markpercentages;
		$this->data['classexampercentageArr'] = $classexampercentageArr;
		$this->data['classesID'] = $classesID;
		$this->data['examID'] = $examID;
		$template = $this->load->view('marksettingindividual/classexamwisemarkpercentage',$this->data,TRUE);
		echo $template;
	}

	public function getMarkPercentageByClassSubjectwise(){

		$classesID = $this->input->post('classesID');
		$subjectID = $this->input->post('subjectID');
		$marktypeID = $this->input->post('marktypeID');
		$markpercentages = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
		$condition = [
			'marksetting.classesID' => $classesID,
			'marksetting.marktypeID' => $marktypeID,
			'marksetting.subjectID' => $subjectID,
		];
		$marksetting                     = $this->marksetting_m->get_marksetting_with_marksettingrelation1($condition);
		$classsubjectpercentageArr            = [];
		if(customCompute($marksetting)) {
			foreach ($marksetting as $marksett) {
				$classsubjectpercentageArr[]   = $marksett->markpercentageID;
			}
		}
		$this->data['markpercentages'] = $markpercentages;
		$this->data['classsubjectpercentageArr'] = $classsubjectpercentageArr;
		$this->data['classesID'] = $classesID;
		$this->data['subjectID'] = $subjectID;
		$template = $this->load->view('marksettingindividual/classsubjectwisemarkpercentage',$this->data,TRUE);
		echo $template;
	}

	public function getMarkPercentageByClassExamSubjectwise(){

		$classesID = $this->input->post('classesID');
		$subjectID = $this->input->post('subjectID');
		$marktypeID = $this->input->post('marktypeID');
		$examID = $this->input->post('examID');
		$markpercentages = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');
		$condition = [
			'marksetting.classesID' => $classesID,
			'marksetting.marktypeID' => $marktypeID,
			'marksetting.subjectID' => $subjectID,
			'marksetting.examID' => $examID,
		];
		$marksetting                     = $this->marksetting_m->get_marksetting_with_marksettingrelation1($condition);
		$classexamsubjectpercentageArr            = [];
		if(customCompute($marksetting)) {
			foreach ($marksetting as $marksett) {
				$classexamsubjectpercentageArr[]   = $marksett->markpercentageID;
			}
		}
		$this->data['markpercentages'] = $markpercentages;
		$this->data['classexamsubjectpercentageArr'] = $classexamsubjectpercentageArr;
		$this->data['classesID'] = $classesID;
		$this->data['subjectID'] = $subjectID;
		$this->data['examID'] = $examID;
		$template = $this->load->view('marksettingindividual/classexamsubjectwisemarkpercentage',$this->data,TRUE);
		echo $template;
	}

	public function getSubject() {
		$classesID = $this->input->post('classesID');
		echo "<option value=''>- Select Subject -</option>";
		if((int)$classesID) {
			$subjects    = pluck($this->subject_m->get_by_class_id($classesID), 'obj', 'subjectID');
			if(customCompute($subjects)) {
				foreach ($subjects as $subject) {
					echo "<option value=".$subject->subjectID.">".$subject->subject."</option>";
				}
			}
		}
	}


}

