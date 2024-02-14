<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Kreait\Firebase\Messaging\Http\Request\SendMessages;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;

/**
 * Class to handle job execution
 */
class Mobilejobs extends Frontend_Controller
{
	/**
	 * Constructs the class
	 */
	public function __construct()
	{
        parent::__construct();
		$this->load->model("site_m");
		$this->load->model("job_m");
		$this->load->model("feed_m");
		$this->load->model("notice_m");
		$this->load->model("event_m");
		$this->load->model("holiday_m");
		$this->load->model("liveclass_m");
		$this->load->model("fcmtoken_m");
		$this->load->model("mobile_job_m");
		$this->load->model("mobile_pushdelivery_m");
		$this->load->model('smssettings_m');
		$this->db->cache_off();
	}

	public function course_enable_push_notification() {

		$jobs = $this->mobile_job_m->get_order_by_jobs(['name' => 'sendCourseNotification', 'status' => 'queued']);

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
						$this->mobile_pushdelivery_m->insert_push_delivery($push_delivery);
					}
				}
			}
			$this->mobile_job_m->update_job(['status' => 'running'], $job->id);
		}
	}

	public function liveclassReminder(){

		$defaultschoolyearID          = $this->site_m->getDefaultSchoolYear();
		$schoolYear = $defaultschoolyearID?$defaultschoolyearID ->value:'';

		$liveclass = $this->liveclass_m->get_todays_liveclass($schoolYear);
		
		foreach($liveclass as $liveclassa){

		    $t2 = strtotime($liveclassa->date);
		    $t1 = strtotime(date('Y-m-d H:i:s'));
			
			$delta_T = ($t2 - $t1);
			$minutes = round(((($delta_T % 604800) % 86400) % 3600) / 60); 

			if($minutes == $liveclassa->reminder){

				if($liveclassa->section_id){
					$condition = [
						'student.classesID' => $liveclassa->classes_id,
						'student.sectionID' => $liveclassa->section_id,
						'student.schoolyearID' => $liveclassa->school_year_id
					];
				}else{
					$condition = [
						'student.classesID' => $liveclassa->classes_id,
						'student.schoolyearID' => $liveclassa->school_year_id
					];
				}

                $students = $this->student_m->get_allstudents($condition);
				$all_users = [];
				if(customCompute($students)){
					foreach($students as $student){
						$all_users[] = $student->id.'3';
						$parent_id = $student->parentsID;
						if ($parent_id) {
							$all_users[] = $student->parentsID . '4';
						}
					}
				}

				$title = 'Online Class - ' . $liveclassa->subjectname;
				$notice = $liveclassa->subjectname . ' class going to be started.';


				$array = array(
					"title"             => $title,
					"notice"            => $notice,
					"schoolyearID"      => $schoolYear,
					"users"             => serialize($all_users),
					"date"              => date('Y-m-d'),
					"create_date"       => date('Y-m-d H:i:s'),
					"create_userID"     => $liveclassa->user_id,
					"create_usertypeID" => $liveclassa->usertype_id,
					"show_to_creator"   => 0
				);
				$this->notice_m->insert_notice($array);
				$insert_id = $this->db->insert_id();

				if ($insert_id) {   
					  $this->feed_m->insert_feed(
						array(
							'itemID'            => $insert_id,
							'userID'            => $liveclassa->user_id,
							'usertypeID'        => $liveclassa->usertype_id,
							'itemname'          => 'notice',
							'schoolyearID'      => $schoolYear,
							'published'         => 1,
							'published_date'    => date("Y-m-d"),
							"show_to_creator"   => 0
							)
					);
					$feedID = $this->db->insert_id();

				    if(customCompute($all_users)){
						$noticeUsers = [];
						$feedUsers   = [];
                        foreach($all_users as $all_user){
							$user_id1 = substr($all_user, 0, -1);
						    $user_type1 = substr($all_user, -1);

							// insert users
							$noticeUsers[] = [
								'notice_id'  => $insert_id,
								'user_id'    => $user_id1,
								'usertypeID' => $user_type1
							];
					
							$feedUsers[] = [
								'feed_id'    => $feedID,
								'user_id'    => $user_id1,
								'usertypeID' => $user_type1
							];

						}

						$this->notice_m->insert_batch_notice_user($noticeUsers);
						$this->feed_m->insert_batch_feed_user($feedUsers);	

					}
					
				}


				$this->job_m->insert_job([
					'name' => 'sendNotice',
					'itemID' => $insert_id,
					'payload' => json_encode([
						'title' => 'Online Class - ' . $liveclassa->subjectname . ' going to be started.', // title is compulsary
						'users' => serialize($all_users),
					]),
				]);
		
				$this->mobile_job_m->insert_job([
					'name' => 'sendNotice',
					'itemID' => $insert_id,
					'payload' => json_encode([
						'title' => 'Online Class - ' . $liveclassa->subjectname . ' going to be started.', // title is compulsary
						'users' => serialize($all_users),
						'message' => ''
					]),
				]);

				$this->liveclass_m->update_liveclass(['added_to_job' => 1],$liveclassa->id);
		
			}
			
		}

	}


	public function add_event_holiday_in_job(){

		$defaultschoolyearID          = $this->site_m->getDefaultSchoolYear();
		$schoolYearID = $defaultschoolyearID?$defaultschoolyearID ->value:'';

		$current_date = date('Y-m-d');

		$events = $this->event_m->getEventsForJob($schoolYearID,$current_date);
		$holidays = $this->holiday_m->getHolidaysForJob($schoolYearID,$current_date);

		$teachers = $this->teacher_m->getAllActiveTeachers(['active' => 1]);
        $students = $this->student_m->getAllActiveStudents(['active' => 1]);
        $parents = $this->parents_m->getAllActiveParents(['active' => 1]);
        $systemadmins = $this->systemadmin_m->getAllActiveSystemadmins(['active' => 1]);
        $users = $this->user_m->getAllActiveUsers(['active' => 1]);
        $all_users = array_merge($teachers,$students,$parents,$systemadmins,$users);
		
		$newUsers = [];
		foreach($all_users as $all_user){
			$newUsers[] = $all_user['ID'].$all_user['usertypeID'];
		}

		if(customCompute($events)){
		  foreach($events as $event){

			if($event->status == 'public'){
				$all_users = $newUsers;
			}else{
				$event_users = $this->event_m->get_event_users_by_id($event->eventID);
				foreach ($event_users as $event_user) {
					$newUsers[] = $event_user['user_id'] . $event_user['usertypeID'];
				}
				$all_users = $newUsers;
			}

			$sall_users = serialize($all_users);

			$this->job_m->insert_job([
				'name' => 'sendEvent',
				'itemID' => $event->eventID,
				'payload' => json_encode([
					'title' => 'Event ' . $event->title . ' has been added.', // title is compulsary
					'users' => $sall_users,
				]),
			]);
	
			$this->mobile_job_m->insert_job([
				'name' => 'sendEvent',
				'itemID' => $event->eventID,
				'payload' => json_encode([
					'title' => 'Event ' . $event->title . ' has been added.', // title is compulsary
					'users' => $sall_users,
					'message' => $event->details
				]),
			]);

			$this->event_m->update_event(['published'=> 1,'added_to_job' => 1],$event->eventID);
		}
	}

	if(customCompute($holidays)){

		foreach($holidays as $holiday){
			$hsall_users = serialize($newUsers);	
			$this->mobile_job_m->insert_job([
				'name' => 'sendHoliday',
				'itemID' => $holiday->holidayID,
				'payload' => json_encode([
					'title' => 'Holiday ' . $holiday->title . ' has been added.', // title is compulsary
					'users' => $hsall_users,
					'message' => $holiday->details
				]),
			]);

			$this->holiday_m->update_holiday(['published'=> 1,'added_to_job' => 1],$holiday->holidayID);

	    }

    }
         
	}

	public function send_holiday() {

		$jobs = $this->mobile_job_m->get_order_by_jobs(['name' => 'sendHoliday', 'status' => 'queued']);

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
						$this->mobile_pushdelivery_m->insert_push_delivery($push_delivery);
					}
				}
			}
			$this->mobile_job_m->update_job(['status' => 'running'], $job->id);
		}
	}

	public function send_notice() {

		$jobs = $this->mobile_job_m->get_order_by_jobs(['name' => 'sendNotice', 'status' => 'queued']);

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
							'user_id' => (int)$user_id,
							'user_type' => (int)$user_type,
							'job_id' => (int)$job->id,
							'status' => 'queued',
							'message' => " "
						];
						$this->mobile_pushdelivery_m->insert_push_delivery($push_delivery);
					}
				}
			}
			$this->mobile_job_m->update_job(['status' => 'running'], $job->id);
		}
	}

	public function send_event() {
		$jobs = $this->mobile_job_m->get_order_by_jobs(['name' => 'sendEvent', 'status' => 'queued']);
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
							$this->mobile_pushdelivery_m->insert_push_delivery($push_delivery);
						}
					}
				}
			}
			$this->mobile_job_m->update_job(['status' => 'running'], $job->id);
		}
	}

	public function send_comment() {
		$jobs = $this->mobile_job_m->get_order_by_jobs(['name' => 'sendComment', 'status' => 'queued']);

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
						$this->mobile_pushdelivery_m->insert_push_delivery($push_delivery);
					}
				}
			}
			$this->mobile_job_m->update_job(['status' => 'running'], $job->id);
		}
	}

	public function send_conversation_msg() {

		$jobs = $this->mobile_job_m->get_order_by_jobs(['name' => 'sendConversationMsg', 'status' => 'queued']);
		
		foreach($jobs as $job) {
			$payload = json_decode($job->payload);
			if($payload->users != "" && $payload->users != "N;"){
				$push_delivery = [];
				$users = $payload->users;
				foreach($users as $user) {
					$user_id = $user->ID;
					$user_type = $user->usertypeID;
					$data = [
						'user_id' => $user_id,
						'user_type' => $user_type,
						'job_id' => $job->id,
						'status' => 'queued',
						'message' => " "
					];
					array_push($push_delivery, $data); 
				}
				$this->mobile_pushdelivery_m->batch_insert_push_delivery($push_delivery);
			}
			$this->mobile_job_m->update_job(['status' => 'running'], $job->id);
		}
	}

	public function mobile_queued_jobs(){

		
		$queued_jobs = $this->mobile_pushdelivery_m->get_order_by_push_deliveries(['status' => 'queued']);
       
		$new_queued_jobs = [];
		if(customCompute($queued_jobs)){
			foreach($queued_jobs as $queued_job){
				$new_queued_jobs[$queued_job->job_id][] = $queued_job;
			}
		}  

		if(customCompute($new_queued_jobs)){
			foreach($new_queued_jobs as $key=>$new_queued_job){
				$jobDetail = $this->mobile_job_m->get_single_job(['id' => $key]);
				$jobName = $jobDetail->name;
				$payload = json_decode($jobDetail->payload);

				if($jobName == "sendNotice" || $jobName == "sendCourseNotification" || $jobName == "sendComment"){
                    $message = 'notice';
				}elseif($jobName == "sendEvent"){
					$message = 'event';
				}elseif($jobName == "sendHoliday"){
					$message = 'holiday';
				}elseif($jobName == "sendConversationMsg"){
					$message = 'message';
				}else{
					$message = 'message';
				}

				$push_message['data'] = [
					'message' => $payload->message,
					'title' => $payload->title,
					'action' => $message,
				];
				if(isset($payload->conversationID)){
					$push_message['data']['id'] = $payload->conversationID; 
				}
				$push_users = [];
				foreach($new_queued_job as $job){
					$push_users[] = pluck($this->fcmtoken_m->get_order_by_fcm_token(['create_userID' => $job->user_id, 'create_usertypeID' => $job->user_type ]), 'fcm_token');
				    $push_delivery = [
						'status' => 'done',
						'message' => "[v] Message sent successfully."
					];
					$this->mobile_pushdelivery_m->update_push_delivery($push_delivery, $job->id);
			
				}
				$push_users = array_merge(...$push_users);
				
				chunk_push_notification($push_users,$push_message);

				if($payload->mobiles){
					

					$mobileNumber =  array_filter(unserialize($payload->mobiles));
					$smsContent = $payload->title.' '.$payload->message;

					$credits = $this->smssettings_m->get_single_sparrow_sms_credits();

					if($credits > 0){
						$response = sendSMS($mobileNumber,$smsContent);
						if($response->response_code == 200){
							$remainingCredits = $credits  -  $response->count;
							$this->smssettings_m->update_sparrow_credits($remainingCredits);
						}
					}
			    }
				
			}
		}
				
	}
	
}
