<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('student_m');
        $this->load->model('teacher_m');
        $this->load->model('user_m');
        $this->load->model('conversation_m');
    }

    public function index_get($page = 1) 
    {
        // $page = 7 * ($page - 1);
        $userID = $this->session->userdata("loginuserID");
        $usertypeID = $this->session->userdata('usertypeID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        $conversations = [];
        $dbconversations = $this->conversation_m->get_my_conversations_for_api();
        if(customCompute($dbconversations)){
            $conversations = $dbconversations; 
        }else{
            $assignConversations = $this->conversation_m->get_my_assign_conversations_for_api();
            if(customCompute($assignConversations)){
                $conversations = $assignConversations; 
            }
        }

        $conversationUsers = [];
        $conversation_ids = [];
        $uniqueconversationUsers = [];
       
        if(customCompute($conversations)){
            foreach($conversations as $conversation){
                $conversation_ids[] = $conversation->conversation_id;
            }
            if(customCompute($conversation_ids) > 0){
                $conversationUsers = $this->conversation_m->get_conversation_users_by_conversation_id($conversation_ids);
            }
            if(customCompute($conversationUsers) > 0){
                    foreach($conversationUsers as $key=>$conversationUser){
                        $uniqueconversationUsers[$conversationUser['id'].$conversationUser['usertypeID']] = $conversationUser;
                    }
                    $uniqueconversationUsers = array_slice($uniqueconversationUsers,0,20);
            }
       }

        $newArray = [];
        // Student
        if($usertypeID == 3){
            $student = $this->student_m->getStudentByID($userID);
            $schoolyearID = $student->schoolyearID;
            $classesID = $student->classesID;
            $sectionID = $student->sectionID;

            $students = $this->student_m->getAllStudentsContact([
               'schoolyearID' => $schoolyearID,
               'classesID' => $classesID,
               'sectionID' => $sectionID,
               'studentID' => $student->studentID
            ]);

            $teachers = $this->teacher_m->getAllTeachersContact(['studentID' => $student->studentID]);
       
            $newArray = array_merge($students?$students:[],$teachers?$teachers:[]);
            $newArray = array_slice($newArray,0,40);

            if(customCompute($newArray)){
                foreach($newArray as $value){
                    $datawithkey[] = $value['id'].$value['usertypeID'];
                }
            }

            if(customCompute($uniqueconversationUsers) > 0){
                foreach($uniqueconversationUsers as $key=>$uniqueconversationUser){
                    if (!in_array($key, $datawithkey)) {
                        array_push($newArray,$uniqueconversationUser); 
                    }
                }
                $uniqueconversationUsers = array_slice($uniqueconversationUsers,0,10);
            }

        }

        // Teacher
        if($usertypeID == 2){

            $students = $this->student_m->getAllStudentsContact(['teacherID' => $userID,'schoolyearID' => $schoolyearID]);
            $teachers = $this->teacher_m->getAllTeachersContact(['teacherID' => $userID]);
            $newArray = array_merge($students?$students:[],$teachers?$teachers:[]);
            $newArray = array_slice($newArray,0,40);

            if(customCompute($newArray)){
                foreach($newArray as $value){
                    $datawithkey[] = $value['id'].$value['usertypeID'];
                }
            }

            if(customCompute($uniqueconversationUsers) > 0){
                foreach($uniqueconversationUsers as $key=>$uniqueconversationUser){
                    if (!in_array($key, $datawithkey)) {
                        array_push($newArray,$uniqueconversationUser); 
                    }
                }
            }
        }

        // Parents
        if($usertypeID == 4){
            $students = $this->student_m->getAllStudentsContact(['parentID' => $userID,'schoolyearID' => $schoolyearID]);
            $teachers = [];
            if(customCompute($students)){
                $studentIds = [];
                foreach($students as $s){
                    $studentIds[] = $s['id'];
                }
                 $teachers = $this->teacher_m->getAllTeachersContact(['studentIds' => $studentIds]);
            }

            $newArray = $teachers;
            $newArray = array_slice($newArray,0,40);

            if(customCompute($newArray)){
                foreach($newArray as $value){
                    $datawithkey[] = $value['id'].$value['usertypeID'];
                }
            }

            if(customCompute($uniqueconversationUsers) > 0){
                foreach($uniqueconversationUsers as $key=>$uniqueconversationUser){
                    if (!in_array($key, $datawithkey)) {
                        array_push($newArray,$uniqueconversationUser); 
                    }
                }
            }
        }

         // admins
         if($usertypeID == 1){

            $datawithkey = [];
            $teachers = $this->teacher_m->getAllTeachersContact('');
            $users = $this->user_m->getAllUsersContact('');
            $newArray = array_merge($users?$users:[],$teachers?$teachers:[]);
            $newArray = array_slice($newArray,0,40);
            
            if(customCompute($newArray)){
                foreach($newArray as $value){
                    $datawithkey[] = $value['id'].$value['usertypeID'];
                }
            }

            if(customCompute($uniqueconversationUsers) > 0){
                foreach($uniqueconversationUsers as $key=>$uniqueconversationUser){
                    if (!in_array($key, $datawithkey)) {
                        array_push($newArray,$uniqueconversationUser); 
                    }
                }
            }
        }

         // users
         if($usertypeID > 5 && $usertypeID < 11 ){
            
            $teachers = $this->teacher_m->getAllTeachersContact('');
            $users = $this->user_m->getAllUsersContact(['userID' => $userID]);
            $newArray = array_merge($users?$users:[],$teachers?$teachers:[]);
            $newArray = array_slice($newArray,0,40);

            if(customCompute($newArray)){
                foreach($newArray as $value){
                    $datawithkey[] = $value['id'].$value['usertypeID'];
                }
            }

            if(customCompute($uniqueconversationUsers) > 0){
                foreach($uniqueconversationUsers as $key=>$uniqueconversationUser){
                    if (!in_array($key, $datawithkey)) {
                        array_push($newArray,$uniqueconversationUser); 
                    }
                }
            }
       
        }


        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'count'     => count($newArray),
            'data'      => $newArray,
        ], REST_Controller::HTTP_OK);
    }
}
