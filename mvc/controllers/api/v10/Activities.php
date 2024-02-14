<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Activities extends Api_Controller 
{
	public function __construct() 
    {
        parent::__construct();
		$this->load->model("activities_m");
		$this->load->model("activitiescategory_m");
		$this->load->model("activitiesmedia_m");
		$this->load->model("activitiescomment_m");
        $this->load->model("job_m");
        $this->load->model("mobile_job_m");
	}

	public function index_get() 
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->retdata['user'] = getAllSelectUser();
        $this->retdata['userID'] = $this->session->userdata('loginuserID');
        $this->retdata['usertypeID'] = $this->session->userdata('usertypeID');
        $this->retdata['activitiescategories'] = pluck($this->activitiescategory_m->get_activitiescategory(), 'obj', 'activitiescategoryID');
        $this->retdata['activities'] = $this->activities_m->get_order_by_activities(array('schoolyearID' => $schoolyearID));
        $this->retdata['activitiesmedia'] = pluck_multi_array($this->activitiesmedia_m->get_activitiesmedia(), 'obj', 'activitiesID');
        $this->retdata['activitiescomments'] = pluck_multi_array($this->activitiescomment_m->get_order_by_activitiescomment(array('schoolyearID' => $schoolyearID)), 'obj', 'activitiesID');

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
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
                    'status' => true,
                    'message' => 'Success',
                    'data' => $comment,
                ], REST_Controller::HTTP_OK);
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
            'status'  => true,
            'message' => 'Success',
            'data'    => $comment,
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


}