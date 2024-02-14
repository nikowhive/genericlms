<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Globalsearch extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('student_m');
        $this->load->model('teacher_m');
        $this->load->model('user_m');
        $this->load->model('parents_m');
        $this->load->model('systemadmin_m');
        $this->load->model('subject_m');
        $this->load->model('usertype_m');
    }

    public function index_get() 
    {
        $text = (isset($_REQUEST['text']) && $_REQUEST['text'] != '')?$_REQUEST['text']:'';
        $page = (isset($_REQUEST['page']) && $_REQUEST['page'] != '')?$_REQUEST['page']:1;
        $page  = 7 * ($page - 1);

        list($students,$teachers,$parents,$users,$systemAdmins) = $this->getUsers($text,$page);
        
        $mergeArray = array_merge($students,$teachers,$parents,$users,$systemAdmins);
        $result = [
            'students' => $students,
            'teachers' => $teachers,
            'parents'  => $parents,
            'users'    => $users,
            'systemadmins' =>$systemAdmins 
        ];

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'total'     => count($mergeArray),
            'data'      => $mergeArray
        ], REST_Controller::HTTP_OK);
    }
    
    public function getUsers($text,$page){

        $students      = [];
        $users         = [];
        $teachers      = [];
        $parents       = [];
        $systemAdmins  = [];

        $studentPermission = permissionChecker('student_view');
        $teacherPermission = permissionChecker('teacher_view');
        $parentPermission = permissionChecker('parents_view');
        $userPermission = permissionChecker('user_view');
        $systemadminPermission = permissionChecker('systemadmin_view');

        if($studentPermission){
            if(strpos($text, 'student') !== false){
                $text1 = str_replace("student", "",$text);
            }elseif(strpos($text, 'class') !== false){
                $text1 = str_replace("class", "",$text);
            }else{
                $text1 = $text;
            }
            $students = $this->student_m->searchStudentsExport($text1, 7, $page);
        }

        if($teacherPermission){
            if(strpos($text, 'teacher') !== false){
                $text1 = str_replace("teacher", "",$text);
            }else{
                $text1 = $text;
            }
            $teachers   = $this->teacher_m->searchTeachersExport($text1, 7, $page);
        }

        if($parentPermission){
            if(strpos($text, 'parents') !== false){
                $text1 = str_replace("parents", "",$text);
               
            }else{
                $text1 = $text;
            }
            $parents    = $this->parents_m->searchParentsExport($text1, 7, $page);
        }

        if($userPermission){
            if(strpos($text, 'user') !== false){
                $text1 = str_replace("user", "",$text);
            }else{
                $text1 = $text;
            }
            $users    = $this->user_m->searchUsersExport($text1, 7, $page);
        }

        if($systemadminPermission){
            if(strpos($text, 'admin') !== false){
                $text1 = str_replace("admin", "",$text);
            }else{
                $text1 = $text;
            }
            $systemAdmins   = $this->systemadmin_m->searchSystemAdminsExport($text1, 7, $page);
        }

        return [$students,$teachers,$parents,$users,$systemAdmins];

    }


}
