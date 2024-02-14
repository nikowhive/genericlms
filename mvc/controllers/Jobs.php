<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Kreait\Firebase\Messaging\Http\Request\SendMessages;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;

/**
 * Class to handle job execution
 */
class Jobs extends Frontend_Controller
{
	/**
	 * Constructs the class
	 */
	public function __construct()
	{
        parent::__construct();
		$this->load->model("job_m");
		$this->load->model("site_m");
		$this->load->model("fcmtoken_m");
		$this->load->model("student_m");
		$this->load->model("classes_m");
		$this->load->model("mobile_job_m");
		$this->load->model("pushdelivery_m");
		$this->load->model("pushsubscription_m");
		$this->load->model('online_exam_question_m');
		$this->load->model('question_answer_m');
		$this->load->model('question_bank_m');
		$this->load->model('tempanswer_m');
		$this->load->model('online_exam_m');
		$this->load->model('studentrelation_m');
		$this->load->model('classes_m');
		$this->load->model('section_m');
		$this->load->model('online_exam_user_answer_option_m');
		$this->load->model('online_exam_user_status_m');
		// $this->load->model('online_exam_payment_m');
		$this->db->cache_off();
	}

	public function course_enable_push_notification() {
		$jobs = $this->job_m->get_order_by_jobs(['name' => 'sendCourseNotification', 'status' => 'queued']);

		foreach($jobs as $job) {
			$this->job_m->update_job(['status' => 'running'], $job->id);
			$payload = json_decode($job->payload);

			if($this->classes_m->get_single_classes(['classes' => $payload->class])) {
				$class_id = $this->classes_m->get_single_classes(['classes' => $payload->class])->classesID;
				$students = $this->student_m->get_students($class_id);

				foreach($students as $student) {
					
					$parent_id = $student->parentID;
					$user_id = $student->studentID;

					$student = [
						'user_id' => $user_id,
						'user_type' => 3,
						'job_id' => $job->id,
						'status' => 'queued',
						'message' => ""
					];
					if($parent_id) {
						$parent = [
							'user_id' => $parent_id,
							'user_type' => 4,
							'job_id' => $job->id,
							'status' => 'queued',
							'message' => ""
						];
						$this->pushdelivery_m->insert_push_delivery($parent);
					}
					$this->pushdelivery_m->insert_push_delivery($student);
				}
			}
		}
	}

	public function send_holiday() {

		$jobs = $this->job_m->get_order_by_jobs(['name' => 'sendHoliday', 'status' => 'queued']);

		foreach($jobs as $job) {
			$payload = json_decode($job->payload);
			if($payload->users != "" && $payload->users != "N;"){
				$users = unserialize($payload->users);
				foreach($users as $user) {
					$array = str_split($user);
					if(count($array) != 0) {
						$user_id = substr($user, 0, -1);
						$user_type = substr($user, -1);
						$push_delivery = [
							'user_id' => $user_id,
							'user_type' => $user_type,
							'job_id' => $job->id,
							'status' => 'queued',
							'message' => " "
						];
						$this->pushdelivery_m->insert_push_delivery($push_delivery);
					}
				}
			}
			$this->job_m->update_job(['status' => 'running'], $job->id);
		}
	}

	public function send_notice() {
		$jobs = $this->job_m->get_order_by_jobs(['name' => 'sendNotice', 'status' => 'queued']);

		foreach($jobs as $job) {
			$payload = json_decode($job->payload);

			if($payload->users != "" && $payload->users != "N;"){
				$users = unserialize($payload->users);
				foreach($users as $user) {
					$array = str_split($user);
					if(count($array) != 0) {
						$user_id = substr($user, 0, -1);
						$user_type = substr($user, -1);
						$push_delivery = [
							'user_id' => $user_id,
							'user_type' => $user_type,
							'job_id' => $job->id,
							'status' => 'queued',
							'message' => " "
						];
						$this->pushdelivery_m->insert_push_delivery($push_delivery);
					}
				}
			}
			$this->job_m->update_job(['status' => 'running'], $job->id);
		}
	}

	public function send_event() {
		$jobs = $this->job_m->get_order_by_jobs(['name' => 'sendEvent', 'status' => 'queued']);
		foreach($jobs as $job) {
			if($job->status == 'queued') {
				$payload = json_decode($job->payload);

				if($payload->users != "" && $payload->users != "N;"){

					$users = unserialize($payload->users);
					foreach($users as $user) {
						$array = str_split($user);
						if(count($array) != 0) {
							$user_id = substr($user, 0, -1);
							$user_type = substr($user, -1);

							$push_delivery = [
								'user_id' => $user_id,
								'user_type' => $user_type,
								'job_id' => $job->id,
								'status' => 'queued',
								'message' => " "
							];
							$this->pushdelivery_m->insert_push_delivery($push_delivery);
						}
					}
				}
			}
			$this->job_m->update_job(['status' => 'running'], $job->id);
		}
	}

	public function send_comment() {
		$jobs = $this->job_m->get_order_by_jobs(['name' => 'sendComment', 'status' => 'queued']);

		foreach($jobs as $job) {
			$payload = json_decode($job->payload);

			if($payload->users != "" && $payload->users != "N;"){
				$users = unserialize($payload->users);
				foreach($users as $user) {
					$array = str_split($user);
					if(count($array) != 0) {
						$user_id = substr($user, 0, -1);
						$user_type = substr($user, -1);
						$push_delivery = [
							'user_id' => $user_id,
							'user_type' => $user_type,
							'job_id' => $job->id,
							'status' => 'queued',
							'message' => " "
						];
						$this->pushdelivery_m->insert_push_delivery($push_delivery);
					}
				}
			}
			$this->job_m->update_job(['status' => 'running'], $job->id);
		}
	}

	public function retry_jobs() {
		$auth = array(
			'VAPID' => array(
				'subject' => 'Test',
				'publicKey' => 'BGzgJIO4uc4b5z1T8imf7HN7btMDtxeuhM4xe8nS6NPxZIJoq1068tzKYgVh87s_g80o4_N87vowwub9taf6M64', // don't forget that your public key also lives in app.js
				'privateKey' => 'eHaSsE62467MSkl5JQMuLCUQRVCHD4QbW1JLcsHGbnU', // in the real world, this would be in a secret file
			),
		);
		$webPush = new WebPush($auth);

		$error_jobs = $this->pushdelivery_m->get_order_by_push_deliveries(['status' => 'error']);

		foreach($error_jobs as $job) {
			$payload = json_decode($this->job_m->get_single_job(['id' => $job->job_id])->payload);
			$this->notify($job, $payload->title, $webPush);
		}
	}

	public function queued_jobs() {
		$auth = array(
			'VAPID' => array(
				'subject' => 'Test',
				'publicKey' => 'BGzgJIO4uc4b5z1T8imf7HN7btMDtxeuhM4xe8nS6NPxZIJoq1068tzKYgVh87s_g80o4_N87vowwub9taf6M64', // don't forget that your public key also lives in app.js
				'privateKey' => 'eHaSsE62467MSkl5JQMuLCUQRVCHD4QbW1JLcsHGbnU', // in the real world, this would be in a secret file
			),
		);
		$webPush = new WebPush($auth);

		$queued_jobs = $this->pushdelivery_m->get_order_by_push_deliveries(['status' => 'queued']);

		foreach($queued_jobs as $job) {
			$payload = json_decode($this->job_m->get_single_job(['id' => $job->job_id])->payload);
			$this->notify($job, $payload->title, $webPush);
		}		
	}
	

	function notify($job, $title, $webPush) {
		$subscriptions = $this->pushsubscription_m->get_order_by_push_subscriptions(['user_type' => $job->user_type, 'user_id' => $job->user_id]);
		// todo: need subscription_ids so that error handling should be done based on subscription ids
		if($job->retry_count < 2) {
			if($subscriptions) {
				foreach($subscriptions as $subscription) {
					$notifications = [
						[
							'subscription' => Subscription::create([
							'endpoint' => $subscription->endpoint, // Firefox 43+,
							'publicKey' => $subscription->public_key, // base 64 encoded, should be 88 chars
							'authToken' => $subscription->auth_token, // base 64 encoded, should be 24 chars
						]),
						'payload' => $title,
						]
					];

					foreach ($notifications as $notification) {
						$webPush->queueNotification(
							$notification['subscription'],
							$notification['payload'] // optional (defaults null)
						);
					}
					foreach ($webPush->flush() as $report) {
						$endpoint = $report->getRequest()->getUri()->__toString();
						if ($report->isSuccess()) {
							echo "[v] Message sent successfully for subscription {$endpoint}.";
							$push_delivery = [
								'status' => 'done',
								'message' => "[v] Message sent successfully for subscription {$endpoint}."
							];
							$this->pushdelivery_m->update_push_delivery($push_delivery, $job->id);
						} else {
							echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
							$push_delivery = [
								'status' => 'error',
								'retry_count' => $job->retry_count + 1,
							];
							$this->pushdelivery_m->update_push_delivery($push_delivery, $job->id);
						}
					}
				}
			} else {
				$push_delivery = [
					'status' => 'no subscription',
					'retry_count' => $job->retry_count + 1,
				];
				$this->pushdelivery_m->update_push_delivery($push_delivery, $job->id);
			}
		}
	}

	public function web_push_subscribe() {
		$subscription = json_decode(file_get_contents('php://input'), true);

		if (!isset($subscription['endpoint'])) {
			echo 'Error: not a subscription';
			return;
		}

		$method = $_SERVER['REQUEST_METHOD'];

		$push_subscription = [];

		$push_subscription['user_type'] = $this->session->userdata('usertypeID');
		$push_subscription['user_id'] = $this->session->userdata('loginuserID');
		$push_subscription['public_key'] = $subscription['publicKey'];
		$push_subscription['auth_token'] = $subscription['authToken'];
		$push_subscription['content_encoding'] = $subscription['contentEncoding'];
		$push_subscription['updated_at'] = date("Y-m-d H:i:s");

		switch ($method) {
			case 'POST':
				// create a new subscription entry in your database (endpoint is unique)
				$push_subscription['endpoint'] = $subscription['endpoint'];
				$push_subscription['created_at'] = date("Y-m-d H:i:s");
				$this->pushsubscription_m->insert_push_subscriptions($push_subscription);
				break;
			case 'PUT':
				$already_exists = $this->pushsubscription_m->get_single_push_subscriptions(['endpoint' => $subscription['endpoint']]);

				if($already_exists) {
					// update the key and token of subscription corresponding to the endpoint
					$this->pushsubscription_m->update_push_subscriptions_from_endpoint($push_subscription, $subscription['endpoint']);
				} else {
					$push_subscription['endpoint'] = $subscription['endpoint'];
					$push_subscription['created_at'] = date("Y-m-d H:i:s");
					$this->pushsubscription_m->insert_push_subscriptions($push_subscription);
				}
				break;
			case 'DELETE':
				// delete the subscription corresponding to the endpoint
				$this->pushsubscription_m->delete_push_subscriptions_from_endpoint($subscription['endpoint']);
				break;
			default:
				echo "Error: method not handled";
				return;
		}
	}
	

	public function transferDataFromTempToMainDB(){

		
		$defaultschoolyearID          = $this->site_m->getDefaultSchoolYear();
		$schoolyearID = $defaultschoolyearID?$defaultschoolyearID ->value:'';
		
		$onlineExams = $this->online_exam_m->getTodaysOnlineExams($schoolyearID);
		
        if(customCompute($onlineExams)){
           foreach($onlineExams as $onlineExam){
               $examEndDateTime = $onlineExam->endDateTime;
			   $newtimestamp    = strtotime($examEndDateTime.' + 5 minute');
               $endDate    =  date('Y-m-d H:i:s', $newtimestamp);
			   $currentDateTime = date('Y-m-d H:i:s');
			   $endDate1        = '2021-08-25 19:05:00';

			   if($endDate == $currentDateTime){

				    // start
				   
				    $onlineExamID             = $onlineExam->onlineExamID;
					$onlineExamQuestions      = $this->online_exam_question_m->get_order_by_online_exam_question(['onlineExamID' => $onlineExamID]);
					$allOnlineExamQuestions   = $onlineExamQuestions;
					$pluckOnlineExamQuestions = pluck($allOnlineExamQuestions, 'questionID');
					$allAnswers               = $this->question_answer_m->get_where_in_question_answer($pluckOnlineExamQuestions, 'questionID');
					$questionsBank            = pluck($this->question_bank_m->get_order_by_question_bank(), 'obj', 'questionBankID');
               
			        $mainQuestionAnswer = [];
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
					$totalAnswer = 0;
					if(customCompute($allOnlineExamQuestions)) {
						foreach ($allOnlineExamQuestions as $aoeq) {    
							if(isset($questionsBank[$aoeq->questionID])) {
								$totalQuestionMark += $questionsBank[$aoeq->questionID]->mark; 
							}
						}
					}

					$tempDatas = pluck_multi_array($this->tempanswer_m->get_user_temp_answers(['exam_id' => $onlineExamID ]),'obj','user_id');
					$tempSubjectiveDatas = pluck_multi_array($this->tempanswer_m->get_alluser_temp_subjective_answers(['exam_id' => $onlineExamID ]),'obj','user_id');
					
					$newTempDatas = [];
					if(customCompute($tempDatas)){
						foreach($tempDatas as $userid=>$tempData){
								foreach($tempData as $tempd){
									if($tempd->typeNumber == 1 || $tempd->typeNumber == 2){
										for($i=1;$i<5;$i++){
											$opid = 'optionid'.$i;
											if($tempd->$opid != null){
											$newTempDatas[$userid][$tempd->typeNumber][$tempd->question_id][] = $tempd->$opid;
											}
										}
									}elseif($tempd->typeNumber == 3){
										$fillinthebkanksAnswers = $this->question_answer_m->get_order_by_question_answer(['questionID'=>$tempd->question_id]);
										if(customCompute($fillinthebkanksAnswers)){
											$j = 1; 
											$opID = 'option'.$j;
											foreach($fillinthebkanksAnswers as $fillinthebkanksAnswer){
											$opID = 'option'.$j;
											$newTempDatas[$userid][$tempd->typeNumber][$tempd->question_id][$fillinthebkanksAnswer->answerID] = $tempd->$opID;
											$j++;
											}
										}
									}
								}
						}
					}
					
					if(customCompute($tempSubjectiveDatas)){
						foreach($tempSubjectiveDatas as $userid=>$tempSubjectiveData){
							foreach($tempSubjectiveData as $tempsubdata){
								$newTempDatas[$userid][$tempsubdata->typeNumber][$tempsubdata->question_id] = $tempsubdata->answer;
							}
						}
					}

					$this->data['onlineExam'] = $onlineExam;

					if(customCompute($newTempDatas)) {
						foreach($newTempDatas as $user_id=>$userAnswer){
			
						    $this->data['student'] = $this->studentrelation_m->get_single_student(array('srstudentID' => $user_id, 'srschoolyearID' => $schoolyearID));
						    $this->data['class'] = $this->classes_m->get_classes($this->data['student']->classesID);
							if(customCompute($this->data['student'])) {
								$this->data['section'] = $this->section_m->get_section($this->data['student']->sectionID);
							} else {
								$this->data['section'] = array();
							} 
						
							$time = date("Y-m-d h:i:s");
							$mainQuestionAnswer = [];
							$uniqid = $this->generateUniqueNumber();
							$status = 1;
							$attend = 0;
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
							$userID = $user_id;
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
												$getAnswer = isset($userAnswer[$typeID][$questionID][$answerID]) ? strtolower($userAnswer[$typeID][$questionID][$answerID]) : '';
												
												if($getAnswer != $takeAnswer) {
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
													'questionID'             => $questionID,
													'typeID'                 => $typeID,
													'text'                   => $getAnswer,
													'time'                   => $time,
													'user_id'                => $user_id,
													'ans_status'             => $ans_status,
													'obtained_mark'          => $obtained_mark,
													'full_mark'              => $qsdetails->mark,
													'onlineExamQuestionID'   => $onlineExamQuestionID,
													'onlineExamUserAnswerID' => $uniqid,
													'correct_ans'            => trim($takeAnswer),
													'attend'                 => $attend,
													'examID'                 => $this->data['onlineExam']->onlineExamID
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
																'questionID' 			 => $questionID,
																'optionID' 				 => $userOption,
																'typeID' 				 => $typeID,
																'time' 					 => $time,
																'user_id'				 => $user_id,
																'ans_status'		     => $ans_status,
																'obtained_mark'			 => $obtained_mark,
																'full_mark'				 => $qsdetails->mark,
																'onlineExamQuestionID'   => $onlineExamQuestionID,
																'onlineExamUserAnswerID' => $uniqid,
																'attend'				 => $attend,
																'examID'				 => $this->data['onlineExam']->onlineExamID
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
												'exam_id'     => $onlineExamID,
												'question_id' => $questionID,
												'user_id'     => $user_id,
												'is_subjective' => 1
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
															'questionID' 			 => $questionID,
															'text' 					 => $userAnswer[$typeID][$questionID],
															'typeID' 				 => $typeID,
															'time' 					 => $time,
															'user_id'                => $user_id,
															'ans_status'             => 0,
															'obtained_mark'          => 0,
															'full_mark'				 => $qsdetails->mark,
															'onlineExamQuestionID'   => $onlineExamQuestionID,
															'onlineExamUserAnswerID' => $uniqid,
															'subimg'				 => $filesname,
															'attend'				 => $attend,
															'examID'				 => $this->data['onlineExam']->onlineExamID
														]);
											}            
														
										} 
										elseif($typeID == 5) {
											$f = 0;
											$new_file = '';
											$status = 0;
											$subjectiveAnswerFiles = pluck($this->tempanswer_m->get_user_temp_subjective_files([
												'exam_id'     => $onlineExamID,
												'question_id' => $questionID,
												'user_id'     => $user_id,
												'is_subjective' => 0
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
															'questionID' 			 => $questionID,
															'text' 					 => $userAnswer[$typeID][$questionID],
															'typeID' 				 => $typeID,
															'time' 					 => $time,
															'user_id'                => $user_id,
															'ans_status'             => 0,
															'obtained_mark'          => 0,
															'full_mark'				 => $qsdetails->mark,
															'onlineExamQuestionID'   => $onlineExamQuestionID,
															'onlineExamUserAnswerID' => $uniqid,
															'subimg'				 => $filesname,
															'attend'				 => $attend,
															'examID'				 => $this->data['onlineExam']->onlineExamID
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
														$temp = explode(".", $new_file);
														if(in_array(end($temp), $acceptable))
														{
															$newfilename = round(microtime(true)).'_'.$questionID.'_'.$i.'.' . end($temp);
															$new_file = $newfilename;
															$target_file = $target_dir.$newfilename;
															$filesname .=','.$new_file;
															move_uploaded_file($_FILES["image"]["tmp_name"][$typeID][$questionID][$i], $target_file); 
														}       
												}
												$filesname =  substr($filesname,1);
											
											}
											$this->online_exam_user_answer_option_m->insert([
															'questionID' 			 => $questionID,
															'text'     				 => $userAnswer[$typeID][$questionID],
															'typeID' 				 => $typeID,
															'time' 					 => $time,
															'user_id'				 => $user_id,
															'ans_status'			 => 0,
															'obtained_mark'			 => 0,
															'full_mark'   			 => $qsdetails->mark,
															'onlineExamQuestionID'   => $onlineExamQuestionID,
															'onlineExamUserAnswerID' => $uniqid,
															'subimg'				 => $filesname,
															'attend'				 => $attend,
															'examID'				 => $this->data['onlineExam']->onlineExamID
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
									'onlineExamID'		 		=> $this->data['onlineExam']->onlineExamID,
									'time'				 		=> $time,
									'totalQuestion'		 		=> customCompute($onlineExamQuestions),
									'totalAnswer'		 		=> $totalAnswer,
									'nagetiveMark'		 		=> $this->data['onlineExam']->negativeMark,
									'duration'			 		=> $this->data['onlineExam']->duration,
									'score'				 		=> $correctAnswer,
									'userID'			 		=> $userID,
									'classesID' 		 		=> customCompute($this->data['class']) ? $this->data['class']->classesID : 0,
									'sectionID' 		 		=> customCompute($this->data['section']) ? $this->data['section']->sectionID : 0,
									'examtimeID' 		 		=> $examTimeCounter,
									'totalCurrectAnswer' 		=> $correctAnswer,
									'totalMark'			 		=> $totalQuestionMark,
									'totalObtainedMark' 		=> $totalCorrectMark,
									'totalPercentage'	 		=> (($totalCorrectMark > 0 && $totalQuestionMark > 0) ? round(($totalCorrectMark/$totalQuestionMark)*100,2) : 0),
									'statusID'			 		=> $statusID,
									'status'			 		=> ($this->data['onlineExam']->result_published == 1) ? 1 : 0,
									'onlineExamUserAnswerID'	=> $uniqid,
									'indirect' 					=> 1
								]);
			
								if($insID) {
									$this->tempanswer_m->delete_temp_answer($user_id, $onlineExamID);
									$this->tempanswer_m->delete_temp_subjective_answer($user_id, $onlineExamID);
									$this->tempanswer_m->delete_temp_subjective_files($user_id, $onlineExamID);
								}

								echo 'Success';
					}

					}else{
						echo 'No Data';
					}

					exit;

					//  end
				}
		   }

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
	 
	public function generatethumbnails(){
        $allpaths = ['uploads/images/','uploads/attach/','uploads/notice/','uploads/events/','uploads/holiday/','uploads/gallery/','uploads/activities/'];
        $support = ['jpg','jpeg','png','gif'];
        $resolutions  = ['1500','1024','768','512','256','128','56'];
        foreach($allpaths as $path){
            if (file_exists($path)) {
                if ($handle = opendir($path)) {
                    $filesArray = [];
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != "..") {
                            if(is_file($path.$entry)){
                                $explode = explode('.', $entry);
                                $ext = end($explode);
                                if(in_array($ext,$support)){
                                    $filesArray[] = $entry;
                                }	
                            }
                        }
                    }
                    if(customCompute($filesArray)){
                        foreach($filesArray as $fileArray){
                            $sourcePath = $path.$fileArray;
                            list($width, $height, $type, $attr) = getimagesize($sourcePath);
                            foreach($resolutions as $resolution){
                                if($width > $resolution || $height > $resolution){
                                    $filepath = $path.$resolution.'/'.$fileArray;
                                    $targetPath = $path.$resolution;
                                    if (!file_exists($targetPath)) {
                                        mkdir($targetPath, 0777, true);
                                    }
                                        // different resolution
                                    if(!file_exists($filepath)){
                                        resizeImageFromFolder($sourcePath,$targetPath,$resolution);
                                    }
                                }
                            }
                        }
                    }
                    closedir($handle);
                }
            }
        }
        
    }

	public function add_notice_users(){

		$notices = $this->notice_m->get_order_by_notice(['status' => 'private']);
		foreach($notices as $notice) {
			$noticeID = $notice->noticeID;
			$nUsers = $this->notice_m->get_notice_users($notice->noticeID);
		
			if(!customCompute($nUsers)){
				$users = $notice->users?unserialize($notice->users):[];
				if(customCompute($users)){
					$noticeUsers = [];
					foreach($users as $user){  
						$a = str_split($user);
						$user_id = substr($user, 0, -1);
						$user_type = substr($user, -1);
						$noticeUsers[] = [
							'notice_id'  => $noticeID,
							'user_id'    => (int)$user_id,
							'usertypeID' => (int)$user_type
						];
					}
					$this->notice_m->insert_batch_notice_user($noticeUsers);
					$noticeUsers = [];
		    	}
			}
		}
	}


}
