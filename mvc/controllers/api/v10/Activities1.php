<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Activities1 extends Api_Controller 
{
	public function __construct() 
    {
        parent::__construct();
		$this->load->model("feed_m");
        $this->load->model("job_m");
        $this->load->model("alert_m");
        $this->load->model("student_m");
        $this->load->model("classes_m");
        $this->load->model("activities_m");
        $this->load->model("mobile_job_m");
        $this->load->model("activitiesmedia_m");
        $this->load->model("activitiesstudent_m");
        $this->load->model("activitiescomment_m");
        $this->load->model("activitiescategory_m");
	}

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("activities_title"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("activities_description"),
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'time_from',
                'label' => $this->lang->line("activities_time_from"),
                'rules' => 'trim|max_length[10]|xss_clean'
            ),
            array(
                'field' => 'time_to',
                'label' => $this->lang->line("activities_time_to"),
                'rules' => 'trim|max_length[10]|xss_clean'
            ),
            array(
                'field' => 'time_at',
                'label' => $this->lang->line("activities_time_at"),
                'rules' => 'trim|max_length[10]|xss_clean'
            ),
            array(
				'field' => 'photos[]',
				'label' => $this->lang->line("activities_album"),
				'rules' => 'trim|max_length[200]|xss_clean|callback_multiplephotoupload'
			)
        );
        return $rules;
    }


    public function multiplephotoupload()
	{
		if ($_FILES) {
			if ($_FILES['photos']['name'][0] !== "") {
				if (empty(array_filter($_POST['caption']))) {
					$this->form_validation->set_message("multiplephotoupload", 'The %s caption field is required.');
					return FALSE;
				}

				$filesCount = customCompute($_FILES['photos']['name']);
				$uploadData = array();
				$uploadPath = 'uploads/activities';
				if (!file_exists($uploadPath)) {
					mkdir($uploadPath, 0777, true);
				}

				for ($i = 0; $i < $filesCount; $i++) {
					$_FILES['attach']['name']     = $_FILES['photos']['name'][$i];
					$_FILES['attach']['type']     = $_FILES['photos']['type'][$i];
					$_FILES['attach']['tmp_name'] = $_FILES['photos']['tmp_name'][$i];
					$_FILES['attach']['error']    = $_FILES['photos']['error'][$i];
					$_FILES['attach']['size']     = $_FILES['photos']['size'][$i];

					$config['upload_path'] = $uploadPath;
					$config['allowed_types'] = 'gif|jpg|png|jpeg';

					$this->load->library('upload', $config);
					$this->upload->initialize($config);
					if ($this->upload->do_upload('attach')) {
						$fileData = $this->upload->data();
                        $image_width = $fileData['image_width'];
					    $image_height = $fileData['image_height'];

                        resizeImageDifferentSize($fileData['file_name'],$uploadPath,$image_width,$image_height); 

						$uploadData[$i]['attachment']  = $fileData['file_name'];
						$uploadData[$i]['caption']     = $_POST['caption'][$i];
						$uploadData[$i]['create_date'] = date("Y-m-d H:i:s");
					} else {

						$this->form_validation->set_message("multiplephotoupload", "%s" . $this->upload->display_errors());
						return FALSE;
					}
				}

				$this->upload_data['files'] =  $uploadData;
				return TRUE;
			} else {
				$this->upload_data['files'] =  [];
				return TRUE;
			}
		} else {
			$this->upload_data['files'] =  [];
			return TRUE;
		}
	}

    public function index_get($activityCategoryID = '',$page=1)
    {

            $this->data['userType'] = $this->session->userdata('usertypeID');
            $schoolyearID = $this->session->userdata("defaultschoolyearID");

            $page  = 20 * ($page - 1);

            if ($activityCategoryID != "" || $activityCategoryID != 0) {
                $activities =  $this->activities_m->getRecentActivities(20, $page, $schoolyearID, $activityCategoryID);
            } else {
                $activities = $this->activities_m->getRecentActivities(20, $page, $schoolyearID);
            }
           
            $allusers     = getAllSelectUser();

            foreach ($activities as $key => $activity) {

                $activities[$key]->enable_comment = 1;
                
                $activitiesID = $activity->activitiesID;
                $activity_media = $this->activitiesmedia_m->get_order_by_activitiesmedia(['activitiesID' => $activitiesID]);
                $activities[$key]->media = $activity_media;

                $activity_comments = $this->activitiescomment_m->get_order_by_activitiescomment(['activitiesID' => $activitiesID]);
                if(customCompute($activity_comments)){
					foreach($activity_comments as $k=>$comment){
						$activity_comments[$k]->name = $allusers[$comment->usertypeID][$comment->userID]->name;
						$activity_comments[$k]->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
					}
					$activities[$key]->comments = $activity_comments;
                }else{
					$activities[$key]->comments = [];
				}
            }
            
            $this->response([
                'status'  => true,
                'message' => 'Success',
                'data'    => $activities,
            ], REST_Controller::HTTP_OK);
       
    }

    public function index_post()
    {
        if (($this->session->userdata('usertypeID') == 1) || permissionChecker('activities_add')) {

            if ($_POST) {

                $rules = $this->rules();

                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {

                    $this->response([
                        'status'  => false,
                        'message' => $this->form_validation->error_array(),
                        'data'    => [],
                    ], REST_Controller::HTTP_OK);
                } else {

                    $categoryID = htmlentities(escapeString($this->uri->segment(5)));
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');

                    $array = array(
                        "title" => $this->input->post("title"),
                        "description" => $this->input->post("description"),
                        "activitiescategoryID" => $categoryID,
                        "schoolyearID" => $schoolyearID,
                        "usertypeID" => $this->session->userdata('usertypeID'),
                        "userID" => $this->session->userdata('loginuserID'),
                    );
                    if ($this->input->post("time_to") != "0:00") {
                        $array["time_to"] = date('H:i:s', strtotime($this->input->post("time_to")));
                    }
                    if ($this->input->post("time_from") != "0:00") {
                        $array["time_from"] = date('H:i:s', strtotime($this->input->post("time_from")));
                    }
                    if ($this->input->post("time_at") != "0:00") {
                        $array["time_at"] = date('H:i:s', strtotime($this->input->post("time_at")));
                    }

                    $array["create_date"] = date("Y-m-d H:i:s");
                    $array["modify_date"] = date("Y-m-d H:i:s");

                    $insert_id = $this->activities_m->insert_activities($array);
                    
                    if($insert_id){
                        
                       // insert media
						$photos = $this->upload_data['files'];
						if (customCompute($photos)) {
							foreach ($photos as $key => $photo) {
								$photos[$key]['activitiesID'] = $insert_id;
							}
                            $this->activitiesmedia_m->insert_batch_activitiesmedia($photos);
						}

                        $this->feed_m->insert_feed(
                            array(
                                'itemID' => $insert_id,
                                'userID' => $this->session->userdata("loginuserID"),
                                'usertypeID' => $this->session->userdata('usertypeID'),
                                'itemname' => 'activity',
                                'schoolyearID' => $this->session->userdata('defaultschoolyearID'),
                                'published' => 1,
                                'published_date' => date('Y-m-d'),
                                'status' => 'public'
                            )
                        );
                      
       
                            $array["id"] = $insert_id;
        
                            $this->response([
                                'status'  => true,
                                'message' => 'Success',
                                'data'    => $array,
                            ], REST_Controller::HTTP_OK);
                    
                    }
                   
                }
            } else {
                $this->response([
                    'status'  => false,
                    'message' => 'No fields values',
                    'data'    => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status'  => false,
                'message' => 'User not allowed',
                'data'    => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function update_post()
    {
        if (($this->session->userdata('usertypeID') == 1) || permissionChecker('activities_edit')) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $id = htmlentities(escapeString($this->uri->segment(5)));
            if ((int) $id) {
                $this->data['activities'] = $this->activities_m->get_single_activities(array('activitiesID' => $id, 'schoolyearID' => $schoolyearID));
                if ($this->data['activities']) {

                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);

                    if ($this->form_validation->run() == false) {

                        $this->response([
                            'status'  => false,
                            'message' => $this->form_validation->error_array(),
                            'data'    => [],
                        ], REST_Controller::HTTP_OK);

                    } else {

                        $array = array(
                            "title"       => $this->input->post("title"),
                            "description" => $this->input->post("description"),
                        );
                        if ($this->input->post("time_to") != "0:00") {
                            $array["time_to"] = date('H:i:s', strtotime($this->input->post("time_to")));
                        }
                        if ($this->input->post("time_from") != "0:00") {
                            $array["time_from"] = date('H:i:s', strtotime($this->input->post("time_from")));
                        }
                        if ($this->input->post("time_at") != "0:00") {
                            $array["time_at"] = date('H:i:s', strtotime($this->input->post("time_at")));
                        }
                        $array["modify_date"] = date("Y-m-d H:i:s");
        
                        $id = $this->activities_m->update_activities($array, $id);

                            if($id){
                                // insert media
                                $photos = $this->upload_data['files'];
                                if (customCompute($photos)) {
                                    foreach ($photos as $key => $photo) {
                                        $photos[$key]['activitiesID'] = $id;
                                    }
                                    $this->activitiesmedia_m->insert_batch_activitiesmedia($photos);
                                }
                            }    

                            $this->response([
                                'status'  => true,
                                'message' => 'Success',
                                'data'    => $array,
                            ], REST_Controller::HTTP_OK);
                    }


                }
            } else {
                $this->response([
                    'status'  => false,
                    'message' => 'Error',
                    'data'    => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status'  => false,
                'message' => 'User not allowed',
                'data'    => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function index_delete()
    {
        if (($this->session->userdata('usertypeID') == 1) || permissionChecker('activities_delete')) {
            $schoolyearID = $this->session->userdata('defaultschoolyearID');
            $id = htmlentities(escapeString($this->uri->segment(5)));
            if ((int) $id) {
                $this->data['activities'] = $this->activities_m->get_single_activities(array('activitiesID' => $id, 'schoolyearID' => $schoolyearID));
                if ($this->data['activities']) {
                    $this->activities_m->delete_activities($id);
                    $feed = $this->feed_m->get_single_feed(array('itemID' => $id,'itemname' => 'activity'));
                    if(!is_null($feed)){
						$this->feed_m->delete_feed($feed->feedID);
					}
                    $this->response([
                        'status'     => true,
                        'message'    => 'Success',
                        'data'       => [],
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    'status'  => false,
                    'message' => 'Error',
                    'data'    => [],
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status'  => false,
                'message' => 'Error',
                'data'    => [],
            ], REST_Controller::HTTP_METHOD_NOT_ALLOWED);
        }
    }

    public function comment_add_post($id = '')
    {

        if(!$id){
            $this->response([
                'status' => false,
                'message' => 'Activities ID is empty',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        $activitiesObj = $this->activities_m->get_single_activities([
			'activitiesID' => $id
		]);

        if(!$activitiesObj){
            $this->response([
                'status' => false,
                'message' => 'Activities not found.',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        if($this->input->post('comment') == ''){
            $this->response([
                'status' => false,
                'message' => 'Comment is empty.',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }


        $schoolyearID = $this->session->userdata('defaultschoolyearID');
       
            $array['activitiesID'] = $id;
            $array['comment'] = $this->input->post('comment');
            $array['schoolyearID'] = $schoolyearID;
            $array['userID'] = $this->session->userdata("loginuserID");
            $array['usertypeID'] = $this->session->userdata("usertypeID");
            $array['create_date'] = date("Y-m-d H:i:s");
            $data = $this->activitiescomment_m->insert_activitiescomment($array);
            $insert_id = $this->db->insert_id();
            $comment = $this->activitiescomment_m->get_activitiescomment($insert_id);
            $allusers  = getAllSelectUser();
            $comment->name = $allusers[$comment->usertypeID][$comment->userID]->name;
            $comment->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
            if($data){
                $this->pushNotificationOfComment($array);
                $this->response([
                    'status'  => true,
                    'message' => 'Success',
                    'data'    => $comment,
                ], REST_Controller::HTTP_OK);
			}
    }

    public function comment_edit_post($commentID = ''){
      
        if(!$commentID){
            $this->response([
                'status' => false,
                'message' => 'Comment ID is empty',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        if($this->input->post('comment') == ''){
            $this->response([
                'status' => false,
                'message' => 'Comment is empty.',
                'data' => [],
            ], REST_Controller::HTTP_NOT_FOUND);
        }
        
        $array['comment']      = $this->input->post('comment');
        $data = $this->activitiescomment_m->update_activitiescomment($array,$commentID);
        $comment = $this->activitiescomment_m->get_activitiescomment($commentID);
        $allusers  = getAllSelectUser();
        $comment->name = $allusers[$comment->usertypeID][$comment->userID]->name;
        $comment->photo = $allusers[$comment->usertypeID][$comment->userID]->photo;
        $this->response([
            'status' => true,
            'message' => 'Success',
            'data' => $comment,
        ], REST_Controller::HTTP_OK);


    }

    public function delete_comment_get($id = '')
	{
            if(!$id){
                $this->response([
                    'status' => false,
                    'message' => 'Comment ID is empty',
                    'data' => [],
                ], REST_Controller::HTTP_NOT_FOUND);
            }

			$usertypeID = $this->session->userdata('usertypeID');
			$userID = $this->session->userdata('loginuserID');

				$comment = $this->activitiescomment_m->get_activitiescomment($id);
                if(!$comment){
                    $this->response([
                        'status' => false,
                        'message' => 'Comment not found.',
                        'data' => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
				$activities = $this->activities_m->get_activities($comment->activitiesID);
				if (($usertypeID == $activities->usertypeID && $userID == $activities->userID) || ($usertypeID == 1)) {
					$this->activitiescomment_m->delete_activitiescomment($id);
					$this->response([
                        'status'  => true,
                        'message' => 'Success',
                        'data'    => new stdClass(),
                    ], REST_Controller::HTTP_OK);
				}else{
                    $this->response([
                        'status' => false,
                        'message' => 'Not allowed.',
                        'data' => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
	}

    public function delete_media_get($id = '')
	{
            if(!$id){
                $this->response([
                    'status'  => false,
                    'message' => 'Media ID is empty',
                    'data'    => [],
                ], REST_Controller::HTTP_NOT_FOUND);
            }

			$usertypeID = $this->session->userdata('usertypeID');
			$userID = $this->session->userdata('loginuserID');

				$media = $this->activitiesmedia_m->get_activitiesmedia($id);
                if(is_null($media)){
                    $this->response([
                        'status'  => false,
                        'message' => 'Media not found.',
                        'data'    => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
				$activities = $this->activities_m->get_activities($media->activitiesID);
				if (($usertypeID == $activities->create_usertypeID && $userID == $activities->create_userID) || ($usertypeID == 1)) {
					$this->activitiesmedia_m->delete_activitiesmedia($id);
					$this->response([
                        'status'  => true,
                        'message' => 'Success',
                        'data'    => new stdClass(),
                    ], REST_Controller::HTTP_OK);
				}else{
                    $this->response([
                        'status'  => false,
                        'message' => 'Not allowed.',
                        'data'    => [],
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
	}

    function pushNotificationOfComment($array)
	{
		$activitiesObj = $this->activities_m->get_single_activities([
			'activitiesID' => $array['activitiesID']
		]);
			
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
		$all_users = $newUsers;

		// post author
		$postAuthor = $activitiesObj->userID.$activitiesObj->usertypeID;
		if($postAuthor != $array['userID'].$array['usertypeID']){
            array_push($all_users,$postAuthor);
		}
		
		$sall_users = serialize($all_users);
 
		$this->job_m->insert_job([
			'name' => 'sendComment',
			'payload' => json_encode([
				'title' => "Comment on ".$activitiesObj->title,  // title is necessary
				'users' => $sall_users,
			]),
		]);

		$this->mobile_job_m->insert_job([
			'name' => 'sendComment',
			'payload' => json_encode([
				'title' => "Comment on ".$activitiesObj->title,  // title is necessary
				'users' => $sall_users,
				'message' => $array['comment']
			]),
		]);
	}

}