<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Examtermsetting extends Admin_Controller {
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
		$this->load->model("setting_m");
		$this->load->model("marksetting_m");
		$this->load->model("examtermsetting_m");
		$this->load->model("examtermsettingrelation_m");

		$language = $this->session->userdata('lang');
		$this->lang->load('examtermsetting', $language);	
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
		
		$ex_class                      = $this->data['siteinfos']->ex_class;
		$this->data['classes']         = $this->classes_m->general_get_order_by_classes(['classesID !='=> $ex_class]);
		$this->data['finalexams']      = $this->exam_m->get_final_term_exam();
		
		$this->data["subview"]          = "examtermsetting/index";
		$this->load->view('_layout_main', $this->data);
		
	}

	public function saveMarkSetting(){
		if($_POST) {
			$classesID = $this->input->post('classesID');
			$finaltermExamID = $this->input->post('examID');
			$marks = $this->input->post('marks');

			$siteInfo          = $this->site_m->get_site();
		    $schoolyearID = $siteInfo->school_year;

			$markArray = [
				'classesID' => $classesID,
				'finaltermexamID'    => $finaltermExamID,
				'schoolyearID' => $schoolyearID
			];

			// delete if exaist
			$this->deletePreviousExamTermSetting($markArray);
			// end

			$examtermsettingID = $this->examtermsetting_m->insert_examtermsetting($markArray);
			
			$examtermrelationArray = [];
			if(customCompute($marks)){
				foreach($marks as $key =>$value){
					if($value != 0 && $value != ''){
						$examtermrelationArray[] = [
							'examtermsettingID' => $examtermsettingID,
							'examID'    => $key,
							'value' => $value
						];
				    }
				}

			   $this->examtermsettingrelation_m->insert_batch_examtermsettingrelation($examtermrelationArray);
			}

			echo json_encode(['status' => true,'message' => 'success']);
			exit;

		} 
	}

	public function deletePreviousExamTermSetting($array = []){

		$prevMarkSetting = $this->examtermsetting_m->get_single_examtermsetting($array);
		        if($prevMarkSetting){
							$prevMarkSettingRelations = $this->examtermsettingrelation_m->get_order_by_examtermsettingrelation(['examtermsettingID' => $prevMarkSetting->examtermsettingID]);
						    if(customCompute($prevMarkSettingRelations)){
								foreach($prevMarkSettingRelations as $prevMarkSettingRelation){
								   $this->examtermsettingrelation_m->delete_examtermsettingrelation($prevMarkSettingRelation->examtermsettingrelationID);
								}
							}
							$this->examtermsetting_m->delete_examtermsetting($prevMarkSetting->examtermsettingID);
					}
		
	}


	public function getTermWeightageSetting(){

		$classesID = $this->input->post('classesID');
		$examID = $this->input->post('examID');

		$siteInfo          = $this->site_m->get_site();
		$schoolyearID = $siteInfo->school_year;
		$marktypeID = $siteInfo->marktypeID;

		if($marktypeID != 4){
			$markSettings = pluck($this->marksetting_m->get_order_by_marksetting(['marktypeID' => $marktypeID]),'examID');
		}else{
			$markSettings = pluck($this->exam_m->get_exam(),'examID');
		}

		$exams = $this->exam_m->get_exam_excluding_final_term();


		$examtermsettings = $this->examtermsetting_m->get_examtermsetting_with_examtermsettingrelation1(
			[
				'examtermsetting.classesID' => $classesID,
			    'examtermsetting.finaltermexamID' => $examID,
				'examtermsetting.schoolyearID' => $schoolyearID
		    ]
	    );

		$checkexamtermsettingsArray = [];
		if(customCompute($examtermsettings)){
			foreach($examtermsettings as $examtermsetting){
				$checkexamtermsettingsArray[$examtermsetting->examID] = $examtermsetting->value;
			}
		}

		$this->data['exams'] = $exams;
		$this->data['checkexamtermsettingsArray'] = $checkexamtermsettingsArray;
		$this->data['classesID'] = $classesID;
		$this->data['examID'] = $examID;
		$examObj = $this->exam_m->get_single_exam(['examID' => $examID]);
		$this->data['examName'] = $examObj->exam;
		$this->data['markSettings'] = $markSettings;
		$template = $this->load->view('examtermsetting/examtermweightage',$this->data,TRUE);
		echo $template;
		
	}

	

	

}

