<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Signin extends REST_Controller 
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('user_m');
        $this->load->model('site_m');
        $this->load->library('session');
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('setting_m');
        $this->load->model('usertype_m');
        $this->load->model('permission_m');
        $this->load->model('fcmtoken_m');
        $this->load->model("student_m");
        $this->load->model("teacher_m");
        $this->load->model("parents_m");
        $this->load->model("systemadmin_m");
        $this->load->library('form_validation');
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'username',
                'label' => $this->lang->line("signin_username"),
                'rules' => 'trim|required|xss_clean|max_length[128]',
            ),
            array(
                'field' => 'password',
                'label' => $this->lang->line("signin_password"),
                'rules' => 'trim|required|max_length[128]|xss_clean',
            ),
            array(
                'field' => 'fcm_token',
                'label' => $this->lang->line("signin_fcmtoken"),
                'rules' => 'trim|required|xss_clean',
            ),
        );
        return $rules;
    }

    public function index_post()
    {
    	$username 	= inputCall('username');
        $password 	= inputCall('password');
        $fcm_token  = inputCall('fcm_token');

        $rules = $this->rules();
        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() == false) {
            
            $this->response([
                'status' => false,
                'message' => $this->form_validation->error_array(),
                'data' => [],
            ], REST_Controller::HTTP_OK);
        } 
    	if ($username && $password) {
            $userInfo = $this->userInfo(inputCall());
    		if(is_array($userInfo)) {
                $tokenArray['iat']   	= time();
                $tokenArray['userdata']	= (array) $userInfo;
                $token                  = $this->jwt_encode($tokenArray);

                $this->retdata['token'] = $token;
                $this->retdata['profile'] = (array) $userInfo;

                $array = array(
                    "fcm_token" => $fcm_token,
                    "create_date" => date("Y-m-d H:i:s"),
                    "create_userID" => $userInfo['loginuserID'],
                    "create_usertypeID" => $userInfo['usertypeID'],
                );

                $existed_token = $this->fcmtoken_m->get_single_fcm_token(['fcm_token' => $fcm_token]);
                if($existed_token && $existed_token->create_userID != $userInfo['loginuserID'] && $existed_token->create_usertypeID != $userInfo['usertypeID']) {
                    $token = $this->fcmtoken_m->update_fcm_token($array, $existed_token->tokenID);
                } else if (!$existed_token) {
                    $token = $this->fcmtoken_m->insert_fcm_token($array);
                }

                $this->data["siteinfos"] = $this->site_m->get_site();

                $this->retdata['attendanceType'] = 'day';
                if ($this->data['siteinfos']->attendance == "subject") {
                    $this->retdata['attendanceType'] = 'subject';
                }

                $this->response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $this->retdata
                ], REST_Controller::HTTP_OK);
            } else {
    			$this->response([
                	'status' 	=> false,
	                'message' 	=> 'Invalid username or password'
	            ], REST_Controller::HTTP_UNAUTHORIZED);	
    		}
    	} else {
    		$this->response([
                'status' 	=> false,
                'message' 	=> 'Invalid username or password'
            ], REST_Controller::HTTP_UNAUTHORIZED);
    	}
    }

    private function userInfo($array)
    {
    	$username = $array['username'];
    	$password = $this->user_m->hash($array['password']);
    	$tables   = [
            'student'     => 'student',
            'parents'     => 'parents',
            'teacher'     => 'teacher',
            'user'        => 'user',
            'systemadmin' => 'systemadmin',
        ];

        $setting 		= $this->setting_m->get_setting();
       	$userFoundInfo 	= [];
       	$tableID 		= 0;

       	foreach ($tables as $table) {
            $user 				= $this->db->get_where($table, ["username" => $username, "password" => $password, 'active' => 1]);
            $userInfo 			= $user->row();
            if(customCompute($userInfo)) {
            	$tableID 		= $table . 'ID';
            	$userFoundInfo 	= $userInfo; 
            }
        }

        if(customCompute($userFoundInfo)) {
        	$usertype 		= $this->usertype_m->get_single_usertype(array('usertypeID' => $userFoundInfo->usertypeID));
        	$sessionArray 	= [
                'loginuserID'         	=> $userFoundInfo->$tableID,
                'name'                	=> $userFoundInfo->name,
                'email'               	=> $userFoundInfo->email,
                'usertypeID'          	=> $userFoundInfo->usertypeID,
                'usertype'            	=> $usertype->usertype,
                'username'              => $userFoundInfo->username,
                'password'           	=> $password,
                'photo'               	=> $userFoundInfo->photo,
                'lang'               	=> $setting->language,
                'defaultschoolyearID' 	=> $setting->school_year,
                "loggedin"            	=> true,
                "varifyvaliduser"       => true,
                'phone'                 => $userFoundInfo->phone,
                'address'               => $userFoundInfo->address,
            ];

            if($userFoundInfo->usertypeID == 3) {
                $student = $this->student_m->get_single_student(['studentID' => $userFoundInfo->$tableID]);
                // $student = $this->student_m->student_login_details($userFoundInfo->$tableID);

                // if($student){
                    $sessionArray['class_id'] = $student?$student->classesID:'';
                    $sessionArray['section_id'] = $student?$student->sectionID:'';
                    $sessionArray['roll_num'] = $student?$student->roll:'';
                    $sessionArray['bloodgroup'] = $student?$student->bloodgroup:'';
                    $sessionArray['registerNO'] = $student?$student->registerNO:'';
                    $sessionArray['state'] = $student?$student->state:'';
                    $sessionArray['library'] = $student?$student->library:'';
                    $sessionArray['hostel'] = $student?$student->hostel:'';
                    $sessionArray['transport'] = $student?$student->transport:'';
                    $sessionArray['parentID'] = $student?$student->library:'';
                    if($student){
                        $sessionArray['class_name'] = $this->classes_m->general_get_single_classes(['classesID' => $student->classesID])->classes;
                        $sessionArray['section_name'] = $this->section_m->general_get_single_section(['sectionID' => $student->sectionID])->section;
                    }else{
                        $sessionArray['class_name'] = '';
                        $sessionArray['section_name'] = '';
                    }
                    $sessionArray['sex'] = $student?$student->sex:'';
                    $sessionArray['religion'] = $student?$student->religion:'';
                    $sessionArray['dob'] = $student?$student->dob:'';
                // }
            } else if($userFoundInfo->usertypeID == 1) {
                $systemadmin = $this->systemadmin_m->get_single_systemadmin(['systemadminID' => $userFoundInfo->$tableID]);
                $sessionArray['jod'] = $systemadmin->jod;
                $sessionArray['sex'] = $systemadmin->sex;
                $sessionArray['religion'] = $systemadmin->religion;
                $sessionArray['dob'] = $systemadmin->dob;
            } else if($userFoundInfo->usertypeID == 2) {
                $teacher = $this->teacher_m->get_single_teacher(['teacherID' => $userFoundInfo->$tableID]);
                $sessionArray['jod'] = $teacher->jod;
                $sessionArray['sex'] = $teacher->sex;
                $sessionArray['religion'] = $teacher->religion;
                $sessionArray['dob'] = $teacher->dob;
            } else if($userFoundInfo->usertypeID == 4) { 
                $parent = $this->parents_m->get_single_parents(['parentsID' => $userFoundInfo->$tableID]);
                $sessionArray['father_name'] = $parent->father_name;
                $sessionArray['mother_name'] = $parent->mother_name;
                $sessionArray['father_profession'] = $parent->father_profession;
                $sessionArray['mother_profession'] = $parent->mother_profession;
            } else { 
                $user = $this->user_m->get_single_user(['userID' => $userFoundInfo->$tableID]);
                $sessionArray['jod'] = $user->jod;
                $sessionArray['sex'] = $user->sex;
                $sessionArray['religion'] = $user->religion;
                $sessionArray['dob'] = $user->dob;
                
            }


            $this->session->unset_userdata('master_permission_set');
            $this->session->set_userdata($sessionArray);
            
            $permissionSet  = [];
            $session        = $this->session->userdata;
            if($this->session->userdata('usertypeID') == 1 && $this->session->userdata('loginuserID') == 1) {
                if(isset($session['loginuserID'])) {
                    $features   = $this->permission_m->get_permission();
                    if(customCompute($features)) {
                        foreach ($features as $featureKey => $feature) {
                            $permissionSet['master_permission_set'][trim($feature->name)] = $feature->active;
                        }
                        $permissionSet['master_permission_set']['take_exam'] = 'yes';
                        $this->session->set_userdata($permissionSet);
                    }
                }
            } else {
                if(isset($session['loginuserID'])) {
                    $features   = $this->permission_m->get_modules_with_permission($session['usertypeID']);
                    foreach ($features as $feature) {
                        $permissionSet['master_permission_set'][$feature->name] = $feature->active;
                    }

                    if($session['usertypeID'] == 3) {
                        $permissionSet['master_permission_set']['take_exam'] = 'yes';
                    }
                    $this->session->set_userdata($permissionSet);
                }
            }

            return $sessionArray;
        } else {
        	return false;
        }
    }
}